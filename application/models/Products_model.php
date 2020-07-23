<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_pricing_category_by_permission($id)
    {
    	if(is_admin())
    	{
    		$this->db->order_by('id', 'asc');
        	return $this->db->get(db_prefix() . 'pricing_categories')->result_array();
    	} else {
    		$this->db->where('staff_id',$id);
    		$per = $this->db->get(db_prefix() . 'price_category_permission')->result_array();
    		if(!empty($per))
    			$price_id_arr =json_decode($per[0]['price_category_id']);
    		
    		$data_arr = [];
    		foreach ($price_id_arr as $key => $value) {
    			$this->db->where('id', $value);
    			$data = $this->db->get(db_prefix() . 'pricing_categories')->result_array();
    			array_push($data_arr, $data[0]);
    		}
    		return $data_arr;
    	}
    }

    public function add_product_recipe($data)
    {

    }

    public function update_product_recipe($data, $id)
    {

    }

    public function get_pack_by_product_code($id)
    {
    	$this->db->from(db_prefix() . 'pack_list');

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'pack_list.stock_product_code', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
}