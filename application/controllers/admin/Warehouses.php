<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouses_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('warehouse');
        }
        $data['title'] = _l('warehouses');
        $this->load->view('admin/warehouses_material/warehouses', $data);
    }

    public function warehouse_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['warehouseid'] == '') {
                $success = $this->warehouses_model->warehouse_add($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('warehouse'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->warehouses_model->warehouse_edit($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('warehouse'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function warehouse_delete($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses'));
        }
        $response = $this->warehouses_model->warehouse_delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('Warehouse')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('Warehouse')));
        }
        redirect(admin_url('warehouses'));
    }


    public function stock_lists()
    {
    	echo 1;
    }
}