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


    public function get_acc_list()
    {
    	$this->db->order_by('company', 'asc');
        return $this->db->get(db_prefix() . 'account_list')->result_array();
    }

    public function get_purchase_id()
    {
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'purchase_order_phases')->result_array();

    }

    public function get_product_code()
    {
        $this->db->order_by('product_code', 'asc');
        return $this->db->get(db_prefix() . 'stock_lists')->result_array();

    }
}