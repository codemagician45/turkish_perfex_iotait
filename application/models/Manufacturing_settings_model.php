<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manufacturing_settings_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $_POST data
     * @return boolean
     */
    public function add_machine($data)
    {
        unset($data['machineID']);
        $data['user_id']=get_staff_user_id();
        $data['created_at']=date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'machines_list', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New machinery Added [ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  array $_POST data
     * @return boolean
     * Update currency values
     */
    public function edit_machine($data)
    {
        $currencyid = $data['machineID'];
        unset($data['machineID']);
        $data['updated_at']=date('Y-m-d h:i:s');
        $this->db->where('id', $currencyid);
        $this->db->update(db_prefix() . 'machines_list', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Machine list Updated [' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function get_machine($id = '')
    {

        $this->db->from(db_prefix() . 'machines_list');
        // $this->db->join(db_prefix() . 'warehouses', '' . db_prefix() . 'warehouses.id = ' . db_prefix() . 'machines_list.take_from AND machines_list.export_to', 'left');
        // $this->db->order_by('name', 'asc');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'machines_list.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function change_machine_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'machines_list', [
            'active' => $status,
        ]);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('machine_status_changed', [
                'id'     => $id,
                'status' => $status,
            ]);
            log_activity('Machine Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');
            return true;
        }
        return false;
    }

}