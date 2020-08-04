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

    public function add_mould($data)
    {
        unset($data['mouldid']);
        $this->db->insert(db_prefix() . 'moulds', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Mould Added [ID: ' . $data['mould_cavity'] . ']');

            return true;
        }

        return false;
    }

    public function edit_mould($data)
    {
        $mould_id = $data['mouldid'];
        unset($data['mouldid']);
        $this->db->where('id', $mould_id);
        $this->db->update(db_prefix() . 'moulds', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Mould Updated [' . $data['mould_cavity'] . ']');

            return true;
        }

        return false;
    }

    public function get_mould_activity_status($id)
    {
        $this->db->from(db_prefix() . 'moulds');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'moulds.id', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function change_mould_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'moulds', [
            'active' => $status,
        ]);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('moulds_status_changed', [
                'id'     => $id,
                'status' => $status,
            ]);
            log_activity('Moulds Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');
            return true;
        }
        return false;
    }

    public function get_machine_list()
    {
        $this->db->order_by('name', 'asc');
        return $this->db->get(db_prefix() . 'machines_list')->result_array();

    }
    public function get_mould_list()
    {
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'moulds')->result_array();
    }
    public function add_moulds_suitability($data)
    {
        unset($data['mouldID']);
        $data['user_id']=get_staff_user_id();
        $data['created_at']=date('Y-m-d h:i:s');
        if (isset($data['default_machine'])) {
            $data['default_machine'] = 1;
        } else {
            $data['default_machine'] = 0;
        }
        
        $this->db->insert(db_prefix() . 'mould_suitability', $data);
        $insert_id = $this->db->insert_id();
        // print_r($insert_id ); exit();
        if ($insert_id) {
            log_activity('New mould_suitability Added');
            return true;
        }
        return false;
    }

    public function edit_moulds_suitability($data)
    {
        $mouldSuitableId = $data['mouldID'];
        unset($data['mouldID']);
        $data['updated_at']=date('Y-m-d h:i:s');
        if (isset($data['default_machine'])) {
            $data['default_machine'] = 1;
        } else {
            $data['default_machine'] = 0;
        }
        $this->db->where('id', $mouldSuitableId);
        $this->db->update(db_prefix() . 'mould_suitability', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('mould_suitability Updated');

            return true;
        }

        return false;
    }

    public function delete_moulds_suitability($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mould_suitability');
        if ($this->db->affected_rows() > 0) {

            log_activity('moulds_suitability Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_suitability($id = ''){
        $this->db->from(db_prefix() . 'mould_suitability');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'mould_suitability.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function get_default_machine_status_by_id($id){
        $this->db->from(db_prefix() . 'mould_suitability');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'mould_suitability.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }

    public function add_energy_prices($data)
    {
        unset($data['enerypriceid']);
        $this->db->insert(db_prefix() . 'energy_prices', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Energy Price Added [ID: ' . $data['energy_price'] . ']');

            return true;
        }

        return false;
    }

    public function edit_energy_prices($data)
    {
        $enery_price_id = $data['enerypriceid'];
        unset($data['enerypriceid']);
        $this->db->where('id', $enery_price_id);
        $this->db->update(db_prefix() . 'energy_prices', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Energy Price Updated [' . $data['energy_price'] . ']');

            return true;
        }

        return false;
    }

    public function delete_energy_prices($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'energy_prices');
        if ($this->db->affected_rows() > 0) {

           log_activity('Energy Price Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_energy_price()
    {
        $this->db->from(db_prefix() . 'energy_prices');
        return $this->db->get()->row();
    }

    public function add_work_hours_capacity($data)
    {
        unset($data['workhoursid']);
        $this->db->insert(db_prefix() . 'work_hours', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('New Work Hours Added [ID: ' . $data['capacity_hours'] . ']');

            return true;
        }

        return false;
    }

    public function edit_work_hours_capacity($data)
    {
        $work_hours_id = $data['workhoursid'];
        unset($data['workhoursid']);
        $this->db->where('id', $work_hours_id);
        $this->db->update(db_prefix() . 'work_hours', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Work Hours Updated [' . $data['capacity_hours'] . ']');

            return true;
        }

        return false;
    }

    public function delete_work_hours_capacity($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'work_hours');
        if ($this->db->affected_rows() > 0) {

           log_activity('Work Hours Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_work_hour()
    {
        $this->db->from(db_prefix() . 'work_hours');
        return $this->db->get()->row();
    }

    public function add_installation_process($data)
    {
        unset($data['installationId']);
        $data['user_id']=get_staff_user_id();
        $data['created_at']=date('Y-m-d h:i:s');
        $this->db->insert(db_prefix() . 'installation', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           log_activity('Installation Added [ID: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function edit_installation_process($data)
    {
        $installation_id = $data['installationId'];
        unset($data['installationId']);
        $data['updated_at']=date('Y-m-d h:i:s');
        $this->db->where('id', $installation_id);
        $this->db->update(db_prefix() . 'installation', $data);
        if ($this->db->affected_rows() > 0) {
           log_activity('Installation Updated [' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_installation_process($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'installation');
        if ($this->db->affected_rows() > 0) {

           log_activity('Installation Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_installation_by_id($id = '')
    {

        $this->db->from(db_prefix() . 'installation');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'installation.id', $id);

            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_default_machine()
    {
        $this->db->from(db_prefix() . 'mould_suitability');
        $this->db->where(db_prefix() . 'mould_suitability.default_machine', 1);
        $default_suitability =  $this->db->get()->row();
        if(isset($default_suitability))
            $default_machine_id = $default_suitability->machine_id;
        return $this->get_machine($default_machine_id);
    }
}