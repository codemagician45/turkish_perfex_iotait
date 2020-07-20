<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
    }

    public function product_list()
    {
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('product_list');
        }

        $data['title'] = _l('product_list');
        // $data['price_cat'] = $this->product_list_model->pricing_category();

        // $product_price_category = $this->product_list_model->get_product_price_category_user();
        // if($product_price_category->price_category){
        //     $data['price_cat_index'] = $product_price_category->price_category;
        // }
        
        $this->load->view('admin/products/product_list/manage', $data);
    }
}