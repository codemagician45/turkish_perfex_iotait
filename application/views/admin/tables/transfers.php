<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [

    db_prefix() . 'stock_lists.product_code as p_code',
    db_prefix() . 'warehouses.warehouse_name as t_from',
    'transaction_to',
    'transaction_notes',
    'transaction_qty',
    'date_and_time',
    'description',
    db_prefix() . 'staff.firstname, tblstaff.lastname',
    'updated_user'

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'transfer_lists';

$join = [
    'LEFT JOIN ' . db_prefix() . 'stock_lists ON ' . db_prefix() . 'stock_lists.id = ' . db_prefix() . 'transfer_lists.stock_product_code',
    'LEFT JOIN ' . db_prefix() . 'warehouses ON ' . db_prefix() . 'warehouses.id = ' . db_prefix() . 'transfer_lists.transaction_from',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'transfer_lists.created_user',
];

$additionalSelect = [
    db_prefix() . 'transfer_lists.id',
    'stock_product_code',
    'transaction_from',
    'created_user',
];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];
    
    $subjectOutput = '<a href="' . admin_url('warehouses/transfer_manage/' . $aRow['id']) . '" target="_blank">' . $aRow['p_code'] . '</a>';
    $subjectOutput .= '<div class="row-options">';

    $subjectOutput .= '<a href="' . admin_url('warehouses/transfers_manage/' . $aRow['id']) . '">' . _l('edit') . '</a>';
  

    // if (has_permission('contracts', '', 'delete')) {
        $subjectOutput .= ' | <a href="' . admin_url('warehouses/transfer_delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    // }

    $subjectOutput .= '</div>';
    $row[] = $subjectOutput;
    $row[] = $aRow['t_from'];

    $t_to = @$this->ci->db->query('select * from tblwarehouses where `id`='.$aRow['transaction_to'])->row()->warehouse_name;
    $row[] = $t_to;

    $row[] = $aRow['transaction_notes'];

    $row[] = $aRow['transaction_qty'];

    $row[] = $aRow['date_and_time'];

    $row[] = $aRow['description'];

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['created_user']) . '">' . $aRow['firstname']. ' '. $aRow['lastname'] . '</a>';

    $row[] = $aRow['updated_user'];

    $output['aaData'][] = $row;
}
