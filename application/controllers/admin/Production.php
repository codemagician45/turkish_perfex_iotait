<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Production extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('production_model');
        $this->load->model('warehouses_model');
        $this->load->model('utilities_model');
        $this->load->model('invoices_model');
    }

    public function work_order_phases()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('work_order_phases');
        }
        $data['title'] = _l('work_order_phases');
        $this->load->view('admin/production/settings/work_order_phases_manage', $data);
    }

    public function manage_work_order_phase()
    {
    	if ($this->input->post()) {
            $data = $this->input->post();
            if ($data['workorderphaseid'] == '') {
                $success = $this->production_model->add_work_order_phase($data);
                $message = '';
                if ($success == true) {
                    $message = _l('added_successfully', _l('work_order_phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            } else {
                $success = $this->production_model->edit_work_order_phase($data);
                $message = '';
                if ($success == true) {
                    $message = _l('updated_successfully', _l('work_order_phase'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function production_work_order(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('production_work_order');
        }
        $data['title'] = _l('production_work_order');
        $this->load->view('admin/production/work_order/production_work_order_manage', $data);
    }

    public function production_machine_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('list_of_machinery_production');
        }

        $data['warehouses'] = $this->warehouses_model->get_warehouse_list();

        $data['title'] = _l('list_of_machinery');
        $data['google_ids_calendars'] = $this->misc_model->get_google_calendar_ids();
        $data['google_calendar_api']  = get_option('google_calendar_api_key');
        add_calendar_assets();

        $this->load->model('manufacturing_settings_model');
        // $machines_in_suitability = $this->manufacturing_settings_model->get_mould_suitability();
        // $machines_id_array = [];
        // foreach ($machines_in_suitability as $key => $value) {
        //     array_push($machines_id_array, $value['machine_id']);
        // }
        // $machines_id_array_unique = array_unique($machines_id_array);

        // $machines = [];

        // foreach ($machines_id_array_unique as $key => $id) {
        //     $machine = $this->manufacturing_settings_model->get_machine($id);
        //     array_push($machines, $machine);
        // }

        // $data['machines'] = $machines;
        $data['moulds'] = $this->manufacturing_settings_model->get_mould_list();
        // print_r($data); exit();
        $this->load->view('admin/production/list_of_machinery/manage', $data);
    }

    

    public function view_machine_event($id)
    {
        
        // $data['event'] = $this->utilities_model->get_event($id);
        
        /*Planning part*/
        // $this->load->model('manufacturing_settings_model');
        // $machines_in_suitability = $this->manufacturing_settings_model->get_mould_suitability();
        // $machines_id_array = [];
        // foreach ($machines_in_suitability as $key => $value) {
        //     array_push($machines_id_array, $value['machine_id']);
        // }
        // $machines_id_array_unique = array_unique($machines_id_array);

        // $machines = [];

        // foreach ($machines_id_array_unique as $key => $id) {
        //     $machine = $this->manufacturing_settings_model->get_machine($id);
        //     array_push($machines, $machine);
        // }

        // $data['machines'] = $machines;

        // $data['moulds'] = $this->manufacturing_settings_model->get_mould_list();
        $data['produced_qty'] = $this->production_model->get_produced_qty($id);

        if ($data['produced_qty']->public == 1 && !is_staff_member()
            || $data['produced_qty']->public == 0 && $data['produced_qty']->userid != get_staff_user_id()) {
        } else {
            // $this->load->view('admin/utilities/event', $data);
            $this->load->view('admin/production/list_of_machinery/machine_event', $data);
        }
    }

    public function day_production_qty()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $data    = $this->input->post();
            $success = $this->production_model->produced_qty($data);
            $message = '';
            if ($success) {
                if (isset($data['p_qty_id'])) {
                    // $message = _l('produced_qty_updated');
                    $message = _l('updated_successfully', _l('produced_qty'));
                } else {
                    // $message = _l('produced_qty_added_successfully');
                     $message = _l('added_successfully', _l('produced_qty'));
                }
            }
            echo json_encode([
                'success' => $success,
                'message' => $message,
            ]);
            die();
        }
        $data['google_ids_calendars'] = $this->misc_model->get_google_calendar_ids();
        $data['google_calendar_api']  = get_option('google_calendar_api_key');
        $data['title']                = _l('calendar');
        add_calendar_assets();

        $this->load->view('admin/production/list_of_machinery/manage', $data);
    }

    public function get_produced_qty($date)
    {
        $data['produced_qty'] = $this->production_model->get_produced_qty($date);
        if(empty($data['produced_qty']))
        {
            return false;
        } else {
             $this->load->view('admin/production/list_of_machinery/machine_event', $data);
        }
       
    }

    public function get_total_amount($eventid){
        $total = $this->production_model->get_total_amount($eventid);
        echo $total;
    }

    public function work_order_email(){
        if (!is_admin()) {
            access_denied('work_order_email');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('work_order_email');
        }
        $data['title'] = _l('work_order_email');
        $this->load->view('admin/production/settings/work_order_email', $data);
    }
}