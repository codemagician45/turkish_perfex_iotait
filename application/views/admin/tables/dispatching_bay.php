<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');

$aColumns = [
    'number',
    db_prefix() . 'work_order_phases.phase',
    get_sql_select_client_company(),
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'invoices.id and rel_type="invoice" ORDER by tag_order ASC) as tags',
    'sum_volume_wo',
    'staff1.firstname as c_firstname',
    db_prefix() . 'invoices.datecreated',
    'staff2.firstname as u_firstname',
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'invoices';

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'invoices.project_id',
    'LEFT JOIN ' . db_prefix() . 'work_order_phases ON ' . db_prefix() . 'work_order_phases.order_no = ' . db_prefix() . 'invoices.wo_phase_id',
    'LEFT JOIN ' . db_prefix() . 'staff staff1 ON staff1.staffid = ' . db_prefix() . 'invoices.addedfrom',
    'LEFT JOIN ' . db_prefix() . 'staff staff2 ON staff2.staffid = ' . db_prefix() . 'invoices.updated_user',
];


$custom_fields = get_table_custom_fields('invoice');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'invoices.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];

if (!has_permission('invoices', '', 'view')) {
    $userWhere = 'AND ' . get_invoices_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

$aColumns = hooks()->apply_filters('invoices_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'invoices.id',
    db_prefix() . 'invoices.addedfrom',
    db_prefix() . 'invoices.updated_user',
    db_prefix() . 'invoices.clientid',
    db_prefix(). 'currencies.name as currency_name',
    'project_id',
    'hash',
    'recurring',
    'rel_sale_id',
    'deleted_customer_name',
    'staff1.lastname as c_lastname',
    'staff2.lastname as u_lastname',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';

    $numberOutput = '<a href="#">' . format_invoice_number($aRow['id']) . '</a>';

    if ($aRow['recurring'] > 0) {
        $numberOutput .= '<br /><span class="label label-primary inline-block mtop4"> ' . _l('invoice_recurring_indicator') . '</span>';
    }

    $numberOutput .= '<div class="row-options">';

    if (has_permission('invoices', '', 'edit')) {
        $numberOutput .= '<a href="' . admin_url('warehouses/dispatching_bay/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }

    $numberOutput .= '</div>';

    $row[] = $numberOutput;

    $row[] = format_estimate_number($aRow['rel_sale_id']);

    $row[] = $aRow[db_prefix() . 'work_order_phases.phase'];

    if (empty($aRow['deleted_customer_name'])) {
        $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
    } else {
        $row[] = $aRow['deleted_customer_name'];
    }

    $row[] = render_tags($aRow['tags']);

    $row[] = $aRow['sum_volume_wo'];

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['addedfrom']) . '">' . $aRow['c_firstname']. ' '. $aRow['c_lastname'] . '</a>';
    
    $row[] = date("d-m-Y H:i:s", strtotime($aRow[db_prefix() . 'invoices.datecreated']));

    if(!empty($aRow['updated_user']))
    {
        $row[] = '<a href="' . admin_url('staff/member/' . $aRow['updated_user']) . '">' . $aRow['u_firstname']. ' '. $aRow['u_lastname'] . '</a>';
    }
    else
        $row[] = '';

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
