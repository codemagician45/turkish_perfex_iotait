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
        $data['userid'] = get_staff_user_id();
        if(isset($data['p_qty_id']))
        {
            $this->db->where('p_qty_id',$data['p_qty_id']);
            $this->db->update(db_prefix().'produced_qty',$data);
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
