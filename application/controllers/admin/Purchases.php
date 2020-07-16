<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Purchases extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchases_model');
    }

    public function purchase_orders_phases()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('purchase_order_phases');
        }
        $data['title'] = _l('purchase_order_phases');
        $this->load->view('admin/purchase_tunning/purchase_orders_phases', $data);
    }

    public function purchase_orders_phases_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['purchaseOrderPhaseid'] == '') {
                $success = $this->purchases_model->add_phase($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('Purchase Order Phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->purchases_model->edit_phase($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('Purchase Order Phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function purchase_orders()
    {
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('purchase_order');
        }

        $data['title'] = _l('purchase_orders');
        $this->load->view('admin/purchases/purchase_order/manage', $data);
    }

    public function purchase_order_manage($id = '')
    {
    	if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {

                $id = $this->purchases_model->add_purchase_order($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('purchase_order')));
                    redirect(admin_url('purchases/purchase_orders'));
                }
            } else {
                $success = $this->purchases_model->update_purchase_order($data, $id);
                
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('purchase_order')));
                }
                redirect(admin_url('purchases/purchase_orders'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('purchase_order'));
        } else {
            $title = _l('edit', _l('purchase_order'));
      
        }
        $data['acc_list'] = $this->purchases_model->get_acc_list();
        $data['purchase_id'] = $this->purchases_model->get_purchase_id();
        $data['product_code'] = $this->purchases_model->get_product_code();
        $data['title']         = $title;
        $this->load->view('admin/purchases/purchase_order/purchase_order', $data);
    }
}