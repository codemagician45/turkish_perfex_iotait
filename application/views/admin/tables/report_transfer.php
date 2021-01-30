<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'stock_lists.product_code as p_code',
    db_prefix() . 'transfer_lists.updated_at',
    'w1.warehouse_name as t_from',
    'w2.warehouse_name as t_to',
    'transaction_notes',
    'transaction_qty',
    'description',
    'staff1.firstname as c_firstname',
    'staff2.firstname as u_firstname',
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'transfer_lists';
$where  = ['AND '.db_prefix() . 'transfer_lists.allocation != 1'];
$filter = [];

$this->ci->load->model('warehouses_model');

$product_codes = $this->ci->warehouses_model->get_product_code();
$productCodesIds = [];

foreach ($product_codes as $p_code) {
    if ($this->ci->input->post('product_code_' . $p_code['id'])) {
        array_push($productCodesIds, $p_code['id']);
    }
}
if (count($productCodesIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.stock_product_code IN (' . implode(', ', $productCodesIds) . ')');
}

$warehouses = $this->ci->warehouses_model->get_warehouse_list();
$from_warehousesIds = [];
$to_warehousesIds = [];

foreach ($warehouses as $warehouse) {
    if ($this->ci->input->post('from_warehouse_' . $warehouse['id'])) {
        array_push($from_warehousesIds, $warehouse['id']);
    }
    if ($this->ci->input->post('to_warehouse_' . $warehouse['id'])) {
        array_push($to_warehousesIds, $warehouse['id']);
    }
}
if (count($from_warehousesIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.transaction_from IN (' . implode(', ', $from_warehousesIds) . ')');
}

if (count($to_warehousesIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.transaction_to IN (' . implode(', ', $to_warehousesIds) . ')');
}

$this->ci->load->model('staff_model');
$staffs  = $this->ci->staff_model->get();
$createdUserIds = [];
$updatedUserIds = [];
foreach ($staffs as $staff) {
    if ($this->ci->input->post('created_staff_' . $staff['staffid'])) {
        array_push($createdUserIds, $staff['staffid']);
    }
    if ($this->ci->input->post('updated_staff_' . $staff['staffid'])) {
        array_push($updatedUserIds, $staff['staffid']);
    }
}
if (count($createdUserIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.created_user IN (' . implode(', ', $createdUserIds) . ')');
}

if (count($updatedUserIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.updated_user IN (' . implode(', ', $updatedUserIds) . ')');
}

$transfers = $this->ci->warehouses_model->get_transfer();
$note_list = [];
$qty_list = [];
$des_list = [];
foreach ($transfers as $transfer) {
    $note_val = str_replace('.', '_', $transfer['transaction_notes']);
    $note_val = str_replace(' ', '_', $note_val);
    if ($this->ci->input->post('note_' .$note_val)) {
        array_push($note_list, '"'.$transfer['transaction_notes'].'"');
    }
    if ($this->ci->input->post('qty_' . str_replace('.', '_', $transfer['transaction_qty']))) {
        array_push($qty_list, $transfer['transaction_qty']);
    }
    $des_val = str_replace(' ', '_', $transfer['description']);
    if ($this->ci->input->post('des_' . $des_val)) {
        array_push($des_list, '"'.$transfer['description'].'"');
    }
}
if (count($note_list) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.transaction_notes IN (' . implode(', ', $note_list) . ')');
}
if (count($qty_list) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.transaction_qty IN (' . implode(', ', $qty_list) . ')');
}
if (count($des_list) > 0) {
    array_push($filter, 'AND '.db_prefix().'transfer_lists.description IN (' . implode(', ', $des_list) . ')');
}

$field = db_prefix().'transfer_lists.updated_at';
$from_date = to_sql_date($this->ci->input->post('report_from'));
$to_date   = to_sql_date($this->ci->input->post('report_to'));
$custom_date_select = '';
if($from_date != '')
    if ($from_date == $to_date) {
        $custom_date_select = 'AND ' . $field . ' = "' . $this->ci->db->escape_str($from_date) . '"';
    } else {
        $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $this->ci->db->escape_str($from_date) . '" AND "' . $this->ci->db->escape_str($to_date) . '")';
    }

if ($custom_date_select != '') {
    array_push($filter, $custom_date_select);
}


$join = [
    'LEFT JOIN ' . db_prefix() . 'stock_lists ON ' . db_prefix() . 'stock_lists.id = ' . db_prefix() . 'transfer_lists.stock_product_code',
    'LEFT JOIN ' . db_prefix() . 'warehouses w1 ON w1.id = ' . db_prefix() . 'transfer_lists.transaction_from',
    'LEFT JOIN ' . db_prefix() . 'warehouses w2 ON w2.id = ' . db_prefix() . 'transfer_lists.transaction_to',
    'LEFT JOIN ' . db_prefix() . 'staff staff1 ON staff1.staffid = ' . db_prefix() . 'transfer_lists.created_user',
    'LEFT JOIN ' . db_prefix() . 'staff staff2 ON staff2.staffid = ' . db_prefix() . 'transfer_lists.updated_user',
];

$additionalSelect = [
    db_prefix() . 'transfer_lists.id',
    'staff1.lastname as c_lastname',
    'staff2.lastname as u_lastname',
    'stock_product_code',
    'transaction_from',
    'created_user',
    'updated_user'
];

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($this->ci->db->last_query()); exit();
foreach ($rResult as $aRow) {
    $row = [];
    
    $subjectOutput = $aRow['p_code'];
    $subjectOutput .= '<div class="row-options">';
    $subjectOutput .= '<a href="' . admin_url('warehouses/transfers_manage/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    $subjectOutput .= ' | <a href="' . admin_url('warehouses/transfer_delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    $subjectOutput .= '</div>';
    $row[] = $subjectOutput;

    $row[] = date("d-m-Y H:i:s", strtotime($aRow['tbltransfer_lists.updated_at']));

    $row[] = $aRow['t_from'];

    $row[] = $aRow['t_to'];

    $row[] = $aRow['transaction_notes'];

    $row[] = $aRow['transaction_qty'];

    $row[] = $aRow['description'];

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['created_user']) . '">' . $aRow['c_firstname']. ' '. $aRow['c_lastname'] . '</a>';

    if(!empty($aRow['updated_user']))
    {
        $row[] = '<a href="' . admin_url('staff/member/' . $aRow['updated_user']) . '">' . $aRow['u_firstname']. ' '. $aRow['u_lastname'] . '</a>';
    }
    else
        $row[] = '';
    $output['aaData'][] = $row;
}
