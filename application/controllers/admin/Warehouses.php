<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouses_model');
        $this->load->model('staff_model');
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

    /* End Warehouse Material */

    /*-------------------------Stock List---------------------------*/
    public function stock_lists()
    {
    	if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('stock_lists');
        }
        // $data['warehouses'] = $this->stock_lists_model->get_warehouses();
        $data['stock_units'] = $this->warehouses_model->get_units();
        $data['stock_categories'] = $this->warehouses_model->get_stock_categories();
        $data['currency_exchange'] = $this->warehouses_model->get_currency_exchange();
        $data['title'] = _l('stock_list');
        $this->load->view('admin/warehouses/stock_lists/manage', $data);
    }

    public function stock_lists_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            // print_r($_FILES);
            // print_r($_POST); exit();
            $folderPath = "uploads/stock_lists/";
            if (move_uploaded_file($_FILES["product_photo"]["tmp_name"], $folderPath . $_FILES["product_photo"]["name"])) {
                $data['product_photo'] = $folderPath . $_FILES["product_photo"]["name"];
            }
            
            if ($data['stocklistId'] == '') {
                
                $success = $this->warehouses_model->stock_list_add($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('stock_list'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->warehouses_model->stock_list_edit($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('stock_list'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    /* Get stock_list by id / ajax */
    public function get_stock_list_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $stock_lists_by_id = $this->warehouses_model->stock_list_get($id);
            echo json_encode($stock_lists_by_id);
        }
    }

    public function stock_list_delete($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses/stock_lists'));
        }
        $response = $this->warehouses_model->stock_list_delete($id);
        if ($response) {
            // later
            // $barcode_delete = $this->stock_lists_model->barcode_list_delete($id);
            // $product_list_delete = $this->stock_lists_model->product_list_delete($id);
            // $product_recipe_delete = $this->stock_lists_model->product_recipe_delete($id);
            // $product_transfer_delete = $this->stock_lists_model->product_transfer_delete($id);
            // $allocation_transfer_delete = $this->stock_lists_model->product_allocation_delete($id);
            set_alert('success', _l('deleted', _l('Item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('Item')));
        }
        redirect(admin_url('warehouses/stock_lists'));
    }

    /*-------------------Transfer----------------------------*/
    public function transfers()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('transfers');
        }

        $data['title'] = _l('transfers');
        $this->load->view('admin/warehouses/transfers/manage', $data);
    }

    public function transfers_manage($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if(isset($data['allocation']) && $data['allocation'] == 'on')
            {
                $data['allocation'] = 1;
            } 
            else
            {
                $data['allocation'] = 0;
            }
            if ($id == '') {
                // Allocated Items saving if allocation is enabled
                $id = $this->warehouses_model->add_transfer($data);
                if($data['allocation'] == 1)
                {
                    $allocation_data['transfer_id'] = $id;
                    $allocation_data['allocation_product_code'] = $data['stock_product_code'];
                    $stock_list = $this->warehouses_model->stock_list_get($allocation_data['allocation_product_code']);
                    $allocation_data['product_name'] = $stock_list->product_name;
                    $allocation_data['stock_category'] = $stock_list->category;
                    $location = $this->warehouses_model->get_warehouse($data['transaction_from'])->warehouse_name;
                    $allocation_data['current_location'] = $location;
                    $allocation_data['stock_quantity'] = $data['transaction_qty'];
                    $allocation_data['wo_no'] = $data['wo_no'];
                    $user = $this->staff_model->get(get_staff_user_id());
                    $allocation_data['created_user'] = $user->firstname.' '. $user->lastname;
                    $allocation_id = $this->warehouses_model->add_allocated_items($allocation_data); 
                    $this->db->query('UPDATE tbltransfer_lists SET allocation_id = '.$allocation_id.' WHERE `id` ='.$id);
                }
                
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('transfer')));
                    redirect(admin_url('warehouses/transfers'));
                }
            } else {
                $allocation_id = $this->warehouses_model->get_transfer($id)->allocation_id;
                // print_r($allocation_id); exit();
                if($data['allocation'] == 1)
                {
                    $allocation_data['transfer_id'] = $id;
                    $allocation_data['allocation_product_code'] = $data['stock_product_code'];
                    $stock_list = $this->warehouses_model->stock_list_get($allocation_data['allocation_product_code']);
                    $allocation_data['product_name'] = $stock_list->product_name;
                    $allocation_data['stock_category'] = $stock_list->category;
                    $location = $this->warehouses_model->get_warehouse($data['transaction_from'])->warehouse_name;
                    $allocation_data['current_location'] = $location;
                    $allocation_data['stock_quantity'] = $data['transaction_qty'];
                    $allocation_data['wo_no'] = $data['wo_no'];
                    $user = $this->staff_model->get(get_staff_user_id());
                    $allocation_data['created_user'] = $user->firstname.' '. $user->lastname;
    
                    if($allocation_id != 0)
                    {
                        $this->warehouses_model->update_allocated_items($allocation_data,$allocation_id); 
                    }
                    else{
                        $allocation_id = $this->warehouses_model->add_allocated_items($allocation_data);
                        $data['allocation_id'] = $allocation_id;
                    }
                }
                if($data['allocation'] == 0)
                {
                    // print_r($allocation_id); exit();
                    $this->warehouses_model->delete_allocated_items($allocation_id);
                    $data['allocation_id'] = 0; 
                }
                $success = $this->warehouses_model->update_transfer($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('transfer')));
                }
                redirect(admin_url('warehouses/transfers'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('transfer'));
        } else {
            $data['transfer'] = $this->warehouses_model->get_transfer($id);
            $created_user = $this->staff_model->get($data['transfer']->created_user);
            $data['created_user_name'] = $created_user->firstname . ' ' . $created_user->lastname;
            if(!empty($data['transfer']->updated_user)){
               $updated_user = $this->staff_model->get($data['transfer']->updated_user);
               $data['updated_user_name'] = $updated_user->firstname . ' ' . $updated_user->lastname; 
            }
            $title = _l('edit', _l('transfer'));
        }
        $data['title']         = $title;
        $data['product_code'] = $this->warehouses_model->get_product_code();
        $data['warehouse_list'] = $this->warehouses_model->get_warehouse_list();
        // if(isset($allocation_id))
        //     $data['allocation_id'] = $allocation_id;
        $this->load->view('admin/warehouses/transfers/transfer', $data);
    }

    public function transfer_delete($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses/transfers'));
        }
        $response = $this->warehouses_model->delete_transfer($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('transfer')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('transfer')));
        }
        redirect(admin_url('warehouses/transfers'));
    }

    public function get_transfers_by_product_code($id)
    {
        if ($this->input->is_ajax_request()) {
            $transfer = $this->warehouses_model->get_transfer_by_code($id);
            // print_r($transfer); exit();
            echo json_encode($transfer);
        }
    }

    public function allocated_items()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('allocated_items');
        }
        $data['title'] = _l('allocated_items');
        $this->load->view('admin/warehouses/allocated_items/manage', $data);
    }

    /*-----------------Barcode List---------------*/

    public function barcode_list()
    {
        if ($this->input->is_ajax_request()) {
           $this->app->get_table_data('barcode_list');
       }

        $data['products'] = $this->warehouses_model->get_product_code();
        $data['title'] = _l('barcode_list');
        $this->load->view('admin/warehouses/barcode_list/manage', $data);
    }

    public function barcode_list_manage()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['barcodelistId'] == '') {
                $success = $this->warehouses_model->add_barcode($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('Barcode'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->warehouses_model->edit_barcode($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('Barcode'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function barcode_list_delete($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses/barcode_list'));
        }
        $response = $this->warehouses_model->delete_barcode($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('Barcode')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('Barcode')));
        }
        redirect(admin_url('warehouses/barcode_list'));
    }

    public function get_barcode_list_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $barcodelistByid = $this->warehouses_model->get_barcode($id);


            echo json_encode($barcodelistByid);
        }
    }

    /* Get get_barcode / ajax */
    public function get_barcode($barocde_id)
    {
        if ($this->input->is_ajax_request()) {
            $success = $this->warehouses_model->get_barcode($barocde_id);
            $message = '';
            if($success == true){
                $message = "Barode ID already Exists !";
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }else{
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function packing_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('packing_list');
            // $this->app->get_table_data('packing_group');
        }
        $data['title'] = _l('packing_list');
        $this->load->view('admin/warehouses/packing_list/manage', $data);
    }

    public function packing_list_manage($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                $id = $this->warehouses_model->add_packing_list($data);
                if(isset($data['newitems']))
                {
                    $group_data = $data['newitems'];
                    $group_data['packing_id'] = $id;
                    $this->warehouses_model->add_packing_group($group_data);
                }
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('packing_list and packing_group')));
                    redirect(admin_url('warehouses/packing_list'));
                }
            } else {
                $success = $this->warehouses_model->update_packing_list($data, $id);
                $current_packing_group = $this->warehouses_model->get_packing_group($id);

                if(empty($current_packing_group) && isset($data['newitems']))
                {
                    $group_data = $data['newitems'];
                    $group_data['packing_id'] = $id;
                    $this->warehouses_model->add_packing_group($group_data);
                }
                else {
                    if(isset($data['newitems']))
                    $group_data['newitems'] = $data['newitems'];
                    if(isset($data['removed_items']))
                        $group_data['removed_items'] = $data['removed_items'];
                    if(isset($data['items']))
                        $group_data['items'] = $data['items'];
                    $group_data['packing_id'] = $id;
                    $this->warehouses_model->update_packing_group($group_data);
                }
                
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('packing_list')));
                }
                redirect(admin_url('warehouses/packing_list'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('packing_list'));
        } else {
            $title = _l('edit', _l('packing_list'));
            $data['packing_list'] = $this->warehouses_model->get_packing_list($id);
        }

        //packing group

        $data['ajaxItems'] = false;
        if (total_rows(db_prefix() . 'stock_lists') > 0) {
            $data['items'] = $this->warehouses_model->get_grouped();
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }
        if(isset($data['packing_list']))
            $data['packing_group'] = $this->warehouses_model->get_packing_group($data['packing_list']->id);
        // print_r($data); exit();
        $data['title']         = $title;
        $this->load->view('admin/warehouses/packing_list/packing_list', $data);
    }

    public function delete_packing_list($id)
    {
        if (!$id) {
            redirect(admin_url('warehouses/packing_list'));
        }
        $response = $this->warehouses_model->delete_packing_list($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('packing_list')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('packing_list')));
        }
        redirect(admin_url('warehouses/packing_list'));
    }

    public function packing_group()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('packing_group');
        }
        $data['title'] = _l('packing_group');
        $this->load->view('admin/warehouses/packing_group/manage', $data);
    }
    /* Get stock item by id in packing group/ ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->warehouses_model->stock_list_get($id);
            echo json_encode($item);
        }
    }
}