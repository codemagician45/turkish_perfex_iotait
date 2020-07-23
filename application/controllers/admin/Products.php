<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('sale_model');
        $this->load->model('warehouses_model');
    }

    public function product_list()
    {
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('product_list');
        }

        $data['title'] = _l('product_list');
        $data['price_cat'] = $this->products_model->get_pricing_category_by_permission(get_staff_user_id());
        // print_r($data); exit();
        $this->load->view('admin/products/product_list/manage', $data);
    }

    public function get_price_category_calc($id)
    {
         if ($this->input->is_ajax_request()) {
            $res = $this->sale_model->get_pricing_category($id);
            echo json_encode($res);
        }
    }

    public function product_recipe()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('product_recipe');
        }

        $data['title'] = _l('product_recipe');
        $this->load->view('admin/products/product_recipe/manage', $data);
    }

    public function manage_product_recipe($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            // print_r($data); exit();
            if ($id == '') {
                $id = $this->products_model->add_product_recipe($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('product_recipe')));
                    redirect(admin_url('products/product_recipes'));
                }
            } else {
                $success = $this->products_model->update_product_recipe($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('product_recipe')));
                }
                redirect(admin_url('products/product_recipes'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('product_recipe'));
        } else {
            $title = _l('edit', _l('product_recipe'));
        }
        $data['title']         = $title;
        $data['product'] = $this->warehouses_model->stock_list_get($id);
        $data['pack'] = $this->products_model->get_pack_by_product_code($id);

        $data['ajaxItems'] = false;
        if (total_rows(db_prefix() . 'stock_lists') > 0) {
            $data['items'] = $this->warehouses_model->get_grouped();
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }
        // print_r($data); exit();

        $this->load->view('admin/products/product_recipe/product_recipe', $data);
    }
}