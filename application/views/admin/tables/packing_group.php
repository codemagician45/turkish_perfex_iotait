<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [

    'product_code',
    'default_pack'

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'package_group';

$join = [
//    'LEFT JOIN ' . db_prefix() . 'stock_lists ON ' . db_prefix() . 'stock_lists.id = ' . db_prefix() . 'transfer_lists.stock_product_code',
];

$additionalSelect = [
    db_prefix() . 'package_group.id'

];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $attributes = [
            'data-toggle'             => 'modal',
            'data-target'             => '#mould_suitability',
            'data-id'                 => $aRow['id'],
        ];

        if ($aColumns[$i] == 'stock_product_code') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
        }
        $row[] = $_data;
    }
    $options = icon_btn('list_of_packaging/update/' . $aRow['id'], 'pencil-square-o', 'btn-default');


    $row[]              = $options .= icon_btn('list_of_packaging/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
