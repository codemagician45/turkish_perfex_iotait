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

    /* Begin of Stock Category */
    public function stock_category_add($data)
    {
        unset($data['stockId']);
        $this->db->insert(db_prefix() . 'stock_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Installation Added [ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function stock_category_edit($data)
    {
        $installation_id = $data['stockId'];
        unset($data['stockId']);
        $this->db->where('id', $installation_id);
        $this->db->update(db_prefix() . 'stock_categories', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Installation Updated [' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function stock_category_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'stock_categories');
        if ($this->db->affected_rows() > 0) {

            log_activity('Installation Deleted [' . $id . ']');

            return true;
        }

        return false;
    }
    /* End of Stock Category */

    /* Begin of Stock Unit */
    public function stock_unit_add($data)
    {
        unset($data['stock_unit_id']);
        $this->db->insert(db_prefix() . 'units', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Installation Added [ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function stock_unit_edit($data)
    {
        $installation_id = $data['stock_unit_id'];
        unset($data['stock_unit_id']);
        $this->db->where('id', $installation_id);
        $this->db->update(db_prefix() . 'units', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Installation Updated [' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function change_unit_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'units', [
            'active' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('unit_status_changed', [
                'id'     => $id,
                'status' => $status,
            ]);

            log_activity('Unit Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

            return true;
        }

        return false;
    }
    /* End of Stock Unit */

    /* Begin of Stock List*/
    public function get_units()
    {
        $this->db->order_by('name', 'asc');
        $this->db->where('active',1);
        return $this->db->get(db_prefix() . 'units')->result_array();
    }

    public function get_stock_categories()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'stock_categories')->result_array();
    }

    public function get_currency_exchange()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'currencies_exchange')->result_array();
    }

    public function stock_list_add($data){
        unset($data['stocklistId']);
        $data['created_by'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'stock_lists', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Item Added [ID: ' . $data['product_name'] . ']');

            return true;
            // return $insert_id;
        }
        return false;
    }

    public function stock_list_edit($data)
    {
        $stock_list_Id = $data['stocklistId'];
        unset($data['stocklistId']);
        $data['updated_by'] = get_staff_user_id();
        $data['updated_at'] = date('Y-m-d h:i:s');
        $this->db->where('id', $stock_list_Id);
        $this->db->update(db_prefix() . 'stock_lists', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Item Updated [' . $data['product_name'] . ']');
            return true;
        }
        return false;
    }

    public function stock_list_get($id = '')
    {
        $this->db->from(db_prefix() . 'stock_lists');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'stock_lists.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    
    public function stock_list_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'stock_lists');
        if ($this->db->affected_rows() > 0) {
            log_activity('Item Deleted [' . $id . ']');
            return true;
        }
        return false;
    }
    /* End of Stock List*/


    /* ------------------Transfer----------------- */
    public function add_transfer($data)
    {
        
        $data['created_user'] = get_staff_user_id();
        $data['created_at'] = date('Y-m-d h:i:s');
        // print_r($data); exit();
        $this->db->insert(db_prefix() . 'transfer_lists', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Tansfer Added [ID: ' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_transfer($data,$id)
    {
        unset($data['created_user']);
        unset($data['updated_user']);
        $data['updated_user'] = get_staff_user_id();
        $data['updated_at'] = date('Y-m-d h:i:s');
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'transfer_lists', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Tansfer Updated [' . $transfer_id . ']');

            return true;
        }

        return false;
    }

    public function get_transfer($id)
    {
        $this->db->from(db_prefix() . 'transfer_lists');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'transfer_lists.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_product_code()
    {
        // $this->db->select('product_code');
        $this->db->order_by('product_code', 'asc');
        return $this->db->get(db_prefix() . 'stock_lists')->result_array();
    }

    public function get_warehouse_list()
    {
        // $this->db->select('warehouse_name');
        $this->db->order_by('warehouse_name', 'asc');
        return $this->db->get(db_prefix() . 'warehouses')->result_array();
    }

    public function delete_transfer($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'transfer_lists');
        if ($this->db->affected_rows() > 0) {

            log_activity('Transfer Deleted [' . $id . ']');

            return true;
        }

        return false;
    }


    public function add_allocated_items($data)
    {

        $data['created_at']=date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'allocated_items', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Allocated Items Added [ID: ' .$insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_allocated_items($data, $id){
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'allocated_items', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Allocated Item Updated [' . $allocateItem_id . ']');
            return true;
        }
        return false;
    }

    public function delete_allocated_items($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'allocated_items');
        if ($this->db->affected_rows() > 0) {

            log_activity('Allocated Item Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_barcode($id = '')
    {

        $this->db->from(db_prefix() . 'barcode_list');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'barcode_list.id', $id);

            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function add_barcode($data)
    {
        unset($data['barcodelistId']);
        $data['created_by']=get_staff_user_id();
        $data['created_at']=date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'barcode_list', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('Barcode Added [ID: ' . $data['barcode_id'] . ']');

            return true;
        }

        return false;
    }

    public function edit_barcode($data)
    {
        $barcode_list_id = $data['barcodelistId'];
        unset($data['barcodelistId']);
        $data['updated_by']=get_staff_user_id();
        $data['updated_at']=date('Y-m-d h:i:s');
        $this->db->where('id', $barcode_list_id);
        $this->db->update(db_prefix() . 'barcode_list', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Barcode Updated [' . $data['barcode_id'] . ']');

            return true;
        }

        return false;
    }

    public function delete_barcode($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'barcode_list');
        if ($this->db->affected_rows() > 0) {

           log_activity('Barcode Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function add_packing_list($data)
    {
        $data['user_id'] = get_staff_user_id();
        $data['created_at']=date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'pack_list', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Pack List Added [ID: ' .$insert_id . ']');
            return $insert_id;
        }
        return false;
    }

}
