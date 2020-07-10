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

/* Warehouse Material */

    public function warehouse()
    {
    	if (!is_admin()) {
            access_denied('warehouses');
        }
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
            redirect(admin_url('warehouses/warehouse'));
        }
        $response = $this->warehouses_model->warehouse_delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('warehouse')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('warehouse')));
        }
        redirect(admin_url('warehouses/warehouse'));
    }

    public function stock_categories()
    {
    	if (!is_admin()) {
            access_denied('stock_category');
        }
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('stock_categories');
        }

        $data['title'] = _l('stock_category');
        $this->load->view('admin/warehouses_material/stock_categories', $data);
    }

    public function stock_categories_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['stockId'] == '') {
                $success = $this->warehouses_model->stock_category_add($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('stock_category'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->warehouses_model->stock_category_edit($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('stock_category'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function stock_category_delete($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses/stock_categories'));
        }
        $response = $this->warehouses_model->stock_category_delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('stock_category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('stock_category')));
        }
        redirect(admin_url('warehouses/stock_categories'));
    }

    public function stock_units()
    {
    	if (!is_admin()) {
            access_denied('stock_units');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('stock_units');
        }
        $data['title'] = _l('stock_units');
        $this->load->view('admin/warehouses_material/stock_units', $data);
    }

    public function stock_unit_manage()
    {
    	if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['stock_unit_id'] == '') {
                $success = $this->warehouses_model->stock_unit_add($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('stock_unit'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->warehouses_model->stock_unit_edit($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('stock_unit'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function change_unit_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->warehouses_model->change_unit_status($id, $status);
        }
    }

    public function stock_lists()
    {
    	echo 1;
    }
}