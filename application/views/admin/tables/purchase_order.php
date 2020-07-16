<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'purchase_phase_id',
    'approval',
    // '(SELECT company FROM ' . db_prefix() . 'account_list where id = ' . db_prefix() . 'purchase_order.acc_list) as company',
    'acc_list',
    // 'purchase_phase_id',
    // 'approval',
    'note',
    'created_user',
    'date_and_time',
    'updated_user',



//'acc_list'
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'purchase_order';

$join = [
//    'LEFT JOIN ' . db_prefix() . 'account_list ON ' . db_prefix() . 'account_list.id = ' . db_prefix() . 'purchase_order.acc_list',
];

$additionalSelect = [
    db_prefix() . 'purchase_order.id',
    'acc_list'

];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

//        $attributes = [
//            'data-toggle'             => 'modal',
//            'data-target'             => '#mould_suitability',
//            'data-id'                 => $aRow['id'],
//        ];
//
//        if ($aColumns[$i] == 'id') {
//            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
//        }

        // $row[] = $aRow['company'];
        // $row[] = format_approval_status($aRow['approval']);
        // $row[] = format_purchase_phase($aRow['purchase_phase_id']);
        // $options = icon_btn('purchase_order/update/' . $aRow['id'], 'pencil-square-o', 'btn-default');


        // $row[]              = $options .= icon_btn('purchase_order/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
        $row[] = $_data;
    }
    $options = icon_btn('purchase_order/update/' . $aRow['id'], 'pencil-square-o', 'btn-default');


    $row[]              = $options .= icon_btn('purchase_order/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
