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

}
