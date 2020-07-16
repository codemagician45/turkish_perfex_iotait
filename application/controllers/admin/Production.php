<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Production extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('production_model');
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
}