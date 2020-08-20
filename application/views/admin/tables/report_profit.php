<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');

$aColumns = [
    'number',
    db_prefix() .'staff.firstname as c_firstname',
    get_sql_select_client_company(),
    db_prefix() . 'pricing_categories.name as price_category_name'
    ];

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'estimates.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'estimates.currency',
    'LEFT JOIN ' . db_prefix() . 'staff ON '. db_prefix() . 'staff.staffid = ' . db_prefix() . 'estimates.addedfrom',
    'LEFT JOIN ' . db_prefix() . 'proposals ON '. db_prefix() . 'proposals.id = ' . db_prefix() . 'estimates.rel_quote_id',
    'LEFT JOIN ' . db_prefix() . 'pricing_categories ON '. db_prefix() . 'pricing_categories.order_no = ' . db_prefix() . 'proposals.pricing_category_id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'estimates';


$where  = [];
$filter = [];


if (!has_permission('estimates', '', 'view')) {
    $userWhere = 'AND ' . get_estimates_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$aColumns = hooks()->apply_filters('estimates_table_sql_columns', $aColumns);


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'estimates.id',
    db_prefix() . 'estimates.clientid',
    db_prefix() . 'estimates.invoiceid',
    db_prefix() . 'currencies.name as currency_name',
    
    db_prefix() . 'estimates.addedfrom',
    db_prefix() .'staff.lastname as c_lastname',
]);

$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];
    $numberOutput = '';
   
    $numberOutput = '<a href="#" onclick="init_estimate(' . $aRow['id'] . '); return false;">' . format_estimate_number($aRow['id']) . '</a>';
    $numberOutput .= '<div class="row-options">';

    $numberOutput .= '</div>';

    $row[] = $numberOutput;

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['addedfrom']) . '">' . $aRow['c_firstname']. ' '. $aRow['c_lastname'] . '</a>';

    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';

    $row[] = $aRow['price_category_name'];
    $row[] = '';
    $row[] = '';
    $row[] = '';
    $output['aaData'][] = $row;
}

