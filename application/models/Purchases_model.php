<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Purchases_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add_phase($data)
    {
    	unset($data['purchaseOrderPhaseid']);
        $this->db->insert(db_prefix() . 'purchase_order_phases', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Purchase Order Phase Added [ID: ' . $data['phase'] . ']');

            return true;
        }

        return false;
    }

    public function edit_phase($data)
    {
        $purchaseOrderPhaseid = $data['purchaseOrderPhaseid'];
        unset($data['purchaseOrderPhaseid']);
        $this->db->where('id', $purchaseOrderPhaseid);
        $this->db->update(db_prefix() . 'purchase_order_phases', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Purchase Order Phase Updated [' . $data['phase'] . ']');

            return true;
        }

        return false;
    }

    public function add_purchase_order($data)
    {
        unset($data['item_select']);
        unset($data['item_id']);
        unset($data['product_name']);
        unset($data['ordered_qty']);
        unset($data['received_qty']);
        unset($data['unit']);
        unset($data['product_id']);
        unset($data['price']);
        unset($data['volume']);
        unset($data['item_order']);
        unset($data['newitems']);
        unset($data['notes']);
        unset($data['description']);

    	$data['created_user'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'purchase_order', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Purchase Order Added [ID: ' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_purchase_order($data,$id)
    {
        unset($data['item_select']);
        unset($data['item_id']);
        unset($data['product_name']);
        unset($data['ordered_qty']);
        unset($data['received_qty']);
        unset($data['unit']);
        unset($data['product_id']);
        unset($data['price']);
        unset($data['volume']);
        unset($data['item_order']);
        unset($data['newitems']);
        unset($data['removed_items']);
        unset($data['items']);
        unset($data['notes']);
        unset($data['description']);
        unset($data['created_user']);
        $data['updated_user'] = get_staff_user_id();
        $data['updated_at'] = date('Y-m-d h:i:s');
        // print_r($data); exit();
        $this->db->where('id',$id);
        $this->db->update(db_prefix() . 'purchase_order', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Purchase Order Updated [' . $id . ']');
            return true;
        }
        return false;
    }

    public function get_purchase_order($id)
    {
        $this->db->from(db_prefix() . 'purchase_order');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'purchase_order.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_acc_list()
    {
    	$this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }

    public function get_purchase_id()
    {
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'purchase_order_phases')->result_array();

    }

    public function get_purchase_id_by_order_no($order_no = 3)
    {
        $this->db->order_by('id', 'asc');
        $this->db->where('order_no',$order_no);
        return $this->db->get(db_prefix() . 'purchase_order_phases')->result_array();

    }

    public function get_product_code()
    {
        $this->db->order_by('product_code', 'asc');
        return $this->db->get(db_prefix() . 'stock_lists')->result_array();

    }

    public function delete_purchase_order($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'purchase_order');
        if ($this->db->affected_rows() > 0) {
            log_activity('Purchase Deleted [' . $id . ']');
            return true;
        }
        return false;
    }

    public function add_purchase_order_item($data)
    {
        // print_r($data); exit();
        $rel_purchase_id = $data['rel_purchase_id'];
        unset($data['rel_purchase_id']);
        foreach ($data as $val) {
            
            $val['rel_purchase_id'] = $rel_purchase_id;
            $val['order'] = $val['item_order'];
            unset($val['item_order']);
            unset($val['item_id']);
            $this->db->insert(db_prefix() . 'purchase_order_item', $val);
            $insert_id = $this->db->insert_id();
        }
    }

    public function update_purchase_order_item($data)
    {
        // print_r($data); exit();
        $rel_purchase_id = $data['rel_purchase_id'];
        
        if(isset($data['newitems']))
        {
            $newitems = $data['newitems'];
            foreach ($newitems as $val) {
                $val['rel_purchase_id'] = $rel_purchase_id;
                unset($val['item_id']);
                $val['order'] = $val['item_order'];
                unset($val['item_order']);
                $this->db->insert(db_prefix() . 'purchase_order_item', $val);
                $insert_id = $this->db->insert_id();
            }
        }
        
        if(isset($data['items'])){
            $items = $data['items'];
            // print_r($items); exit();
            foreach ($items as $key => $value) {
                $id = $value['itemid'];
                unset($value['itemid']);
                $val['order'] = $val['item_order'];
                unset($val['item_order']);
                // if(!isset($value['description']))
                //     $value['description'] = 0;
                $this->db->where('id',$id);
                $this->db->update(db_prefix() . 'purchase_order_item', $value);
            }
        }
        

        if(isset($data['removed_items'])){
            $removed_items = $data['removed_items'];
            foreach ($removed_items as $val) {
                $this->db->where('id',$val);
                $this->db->delete(db_prefix() . 'purchase_order_item');
            }
        }
        
    }

    public function get_purchase_order_item($id)
    {
        $this->db->from(db_prefix() . 'purchase_order_item');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'purchase_order_item.rel_purchase_id', $id);
            return $this->db->get()->result_array();
        }
    }
}