<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manufacturing_settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('manufacturing_settings_model');
        $this->load->model('warehouses_model');
    }

    public function list_of_machinery()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('list_of_machinery');
        }

        $data['warehouses'] = $this->warehouses_model->get_warehouse_list();

        $data['title'] = _l('list_of_machinery');
        $this->load->view('admin/manufacturing_settings/list_of_machinery/manage', $data);
    }

    public function manage_list_of_machinery()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['machineID'] == '') {
                $success = $this->manufacturing_settings_model->add_machine($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('machine'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->manufacturing_settings_model->edit_machine($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('machine'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function get_list_machine_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $machines = $this->manufacturing_settings_model->get_machine($id);
            echo json_encode($machines);
        }
    }

    public function change_machine_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->manufacturing_settings_model->change_machine_status($id, $status);
        }
    }

}
