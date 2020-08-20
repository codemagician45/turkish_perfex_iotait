<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');

$aColumns = [
    db_prefix().'invoices.number',
    db_prefix() .'staff.firstname as c_firstname',
    get_sql_select_client_company(),
    db_prefix().'invoices.datecreated as datecreated',
    db_prefix().'work_order_phases.phase as work_order_phase',
    db_prefix().'estimates.shipping_type as shipping_type',
    db_prefix().'estimates.req_shipping_date as shipping_date',
    ];

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency',
    'LEFT JOIN ' . db_prefix() . 'work_order_phases ON ' . db_prefix() . 'work_order_phases.order_no = ' . db_prefix() . 'invoices.wo_phase_id',
    'LEFT JOIN ' . db_prefix() . 'staff  ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'invoices.addedfrom',
    'LEFT JOIN ' . db_prefix() . 'estimates  ON ' . db_prefix() . 'estimates.invoiceid = ' . db_prefix() . 'invoices.id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'invoices';


$where  = [];
$filter = [];


if (!has_permission('invoices', '', 'view')) {
    $userWhere = 'AND ' . get_invoices_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$aColumns = hooks()->apply_filters('invoices_table_sql_columns', $aColumns);


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'invoices.id',
    db_prefix() . 'invoices.addedfrom',
    db_prefix() . 'invoices.clientid',
    db_prefix(). 'currencies.name as currency_name',
    db_prefix() .'staff.lastname as c_lastname',
    ]);

$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];
    $numberOutput = '';
   
    $numberOutput = '<a href="#">' . format_invoice_number($aRow['id']) . '</a>';

    $row[] = $numberOutput;

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['addedfrom']) . '">' . $aRow['c_firstname']. ' '. $aRow['c_lastname'] . '</a>';

    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';

    $row[] = $aRow['datecreated'];
    $row[] = $aRow['work_order_phase'];
    $row[] = $aRow['shipping_date'];
    $row[] = $aRow['shipping_type'];
   
    $output['aaData'][] = $row;
}

