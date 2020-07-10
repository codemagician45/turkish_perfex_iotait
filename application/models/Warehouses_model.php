<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $_POST data
     * @return boolean
     */
    public function warehouse_add($data)
    {
        unset($data['warehouseid']);
        // $data['name'] = strtoupper($data['name']);
        $this->db->insert(db_prefix() . 'warehouses', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Warehouse Added [ID: ' . $data['warehouse_name'] . ']');
            return true;
        }
        return false;
    }

    /**
     * @param  array $_POST data
     * @return boolean
     * Update currency values
     */
    public function warehouse_edit($data)
    {
        $warehouseid = $data['warehouseid'];
        unset($data['warehouseid']);
        // $data['name'] = strtoupper($data['name']);
        $this->db->where('id', $warehouseid);
        $this->db->update(db_prefix() . 'warehouses', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Warehouse Updated [' . $data['warehouse_name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete currency from database, if used return array with key referenced
     */
    public function warehouse_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'warehouses');
        if ($this->db->affected_rows() > 0) {
           log_activity('Warehouse Deleted [' . $id . ']');
            return true;
        }
        return false;
    }

}
