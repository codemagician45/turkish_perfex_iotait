<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sale extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sale_model');
        $this->load->model('warehouses_model');
    }

    /*--------------Pricing Category-------------*/
    public function pricing_categories()
    {
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('pricing-category');
        }

        $data['currency_exchanges'] = $this->warehouses_model->get_currency_exchange();
        $data['title'] = _l('pricing_categories');
        $this->load->view('admin/sale/settings/pricing_categories_manage', $data);
    }

    public function manage_pricing_categories()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['pricingCatId'] == '') {
                $success = $this->sale_model->add_pricing_category($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('pricing_category'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->sale_model->edit_pricing_category($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('pricing_category'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }
    public function delete_pricing_category($id)
    {
        if (!$id) {
            redirect(admin_url('sale/pricing_categories'));
        }
        $response = $this->sale_model->delete_pricing_category($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('pricing_category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('pricing_category')));
        }
        redirect(admin_url('sale/pricing_categories'));
    }

    public function get_price_category_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $priceCategory = $this->sale_model->get_pricing_category($id);
            echo json_encode($priceCategory);
        }
    }
    /*-----------Sale Phases----------*/
    public function sale_phases()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('sale_phases');
        }
        $data['title'] = _l('Sale Phases');
        $this->load->view('admin/sale/settings/sale_phases_manage', $data);
    }

    public function manage_sale_phases()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['salephaseid'] == '') {
                $success = $this->sale_model->add_sale_phases($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('sale_phases'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->sale_model->edit_sale_phases($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('sale_phases'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function delete_sale_phases($id)
    {
        if (!$id) {
            redirect(admin_url('sale/sale_phases'));
        }
        $response = $this->sale_model->delete_sale_phases($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('sale_phases')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('sale_phases')));
        }
        redirect(admin_url('sale/sale_phases'));
    }

    /*----------Quote Phases------*/
    public function quote_phases()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('quote_phase');
        }

        $data['title'] = _l('quote_phase');
        $this->load->view('admin/sale/settings/quote_phases_manage', $data);
    }

    public function manage_quote_phases()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['quoteId'] == '') {
                $success = $this->sale_model->add_quote_phases($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('quote_phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->sale_model->edit_quote_phases($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('quote_phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function delete_quote_phases($id)
    {
        if (!$id) {
            redirect(admin_url('sale/quote_phases'));
        }
        $response = $this->sale_model->delete_quote_phases($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('quote_phase')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('quote_phase')));
        }
        redirect(admin_url('sale/quote_phases'));
    }
}