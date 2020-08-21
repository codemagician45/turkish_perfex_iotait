<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Production_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_work_order_phase($data)
    {
        unset($data['workorderphaseid']);
        $this->db->insert(db_prefix() . 'work_order_phases', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Work Order Phase Added [ID: ' . $data['phase'] . ']');

            return true;
        }

        return false;
    }

    
    public function edit_work_order_phase($data)
    {
        $work_order_phase_id = $data['workorderphaseid'];
        unset($data['workorderphaseid']);
        $this->db->where('id', $work_order_phase_id);
        $this->db->update(db_prefix() . 'work_order_phases', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Work Order Phase Updated [' . $data['phase'] . ']');

            return true;
        }

        return false;
    }

    public function get_wo_phases()
    {
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'work_order_phases')->result_array();
    }

    public function produced_qty($data)
    {
        // print_r($data); exit();
        $data['userid'] = get_staff_user_id();
        if(isset($data['p_qty_id']))
        {
            $this->db->where('p_qty_id',$data['p_qty_id']);
            $this->db->update(db_prefix().'produced_qty',$data);

            $this->db->join(db_prefix().'events', db_prefix().'events.eventid='.db_prefix().'produced_qty.rel_event_id','left');
            $this->db->join(db_prefix().'plan_recipe',db_prefix().'plan_recipe.id='.db_prefix().'events.recipe_id','left');
            $this->db->where('p_qty_id',$data['p_qty_id']);
            $res = $this->db->get(db_prefix().'produced_qty')->row();

            $plus_transfer_stock = [];
            $plus_transfer_stock['stock_product_code'] = $res->wo_product_id;
            $plus_transfer_stock['transaction_qty'] = $res->produced_quantity;

            // $this->db->where('p_qty_id',$data['p_qty_id']);
            // $produced_qty = $this->db->get(db_prefix().'produced_qty')->row();
            // $plus_transfer_stock = [];
            // $plus_transfer_stock['transaction_qty'] = $data['produced_quantity'];

            $this->load->model('warehouses_model');
            $last_transaction_qty = $this->warehouses_model->get_transfer($res->plus_transfer_id)->transaction_qty;
            $plus_transfer_stock['delta'] = $plus_transfer_stock['transaction_qty'] - $last_transaction_qty;
            $this->warehouses_model->update_transfer_by_production($plus_transfer_stock, $res->plus_transfer_id);

            if ($this->db->affected_rows() > 0) {
               log_activity('Daily Produced Qty Updated [' . $data['p_qty_id'] . ']');
                return true;
            }

        } else {

            $this->db->insert(db_prefix() . 'produced_qty', $data);
            $insert_id = $this->db->insert_id();

            if ($insert_id) {
                $this->db->where('id',$data['machine_id']);
                $machine = $this->db->get(db_prefix().'machines_list')->row();
                $take_from = $machine->take_from;
                $export_to = $machine->export_to;

                $this->db->join(db_prefix().'events', db_prefix().'events.eventid='.db_prefix().'produced_qty.rel_event_id','left');
                $this->db->join(db_prefix().'plan_recipe',db_prefix().'plan_recipe.id='.db_prefix().'events.recipe_id','left');
                $this->db->where('p_qty_id',$insert_id);
                $res = $this->db->get(db_prefix().'produced_qty')->row();
                // $plus_transfer_stock = $res->wo_product_id;
                // print_r($res);exit();
                $minus_transfer_stock = [];
                $minus_transfer_stock['stock_product_code'] = $res->ingredient_item_id;
                $minus_transfer_stock['transaction_from'] = $take_from;
                $minus_transfer_stock['transaction_to'] = NULL;
                $minus_transfer_stock['transaction_qty'] = $res->used_qty;
                $minus_transfer_stock['wo_no'] = $res->rel_wo_id;

                $plus_transfer_stock = [];
                $plus_transfer_stock['stock_product_code'] = $res->wo_product_id;
                $plus_transfer_stock['transaction_from'] = NULL;
                $plus_transfer_stock['transaction_to'] = $export_to;
                $plus_transfer_stock['transaction_qty'] = $res->produced_quantity;
                $plus_transfer_stock['wo_no'] = $res->rel_wo_id;

                $this->load->model('warehouses_model');
                $minus_transfer_id = $this->warehouses_model->add_transfer_by_production($minus_transfer_stock, -1);
                $plus_transfer_id = $this->warehouses_model->add_transfer_by_production($plus_transfer_stock, 1);

                $data['minus_transfer_id'] = $minus_transfer_id;
                $data['plus_transfer_id'] = $plus_transfer_id;

                $this->db->where('p_qty_id',$insert_id);
                $this->db->update(db_prefix().'produced_qty',$data);

                return true;
            } else{
                return false;
            }

        }
    }

    public function get_produced_qty($date)
    {
        $this->db->where('current_time_selection',$date);
        return $this->db->get(db_prefix().'produced_qty')->row();
    }


}
