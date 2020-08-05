<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'product_code',
    'product_photo',
    'product_name',
    db_prefix() . 'units.name',
    db_prefix() . 'stock_categories.name',
    'price',
    // '(SELECT name FROM ' . db_prefix() . 'currencies_exchange where id = ' . db_prefix() . 'stock_lists.currency_id) as currency_id',
    // 'currency_id',
    db_prefix() . 'currencies.name',
    'stock_level'
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'stock_lists';

$join = [
     'LEFT JOIN ' . db_prefix() . 'units ON ' . db_prefix() . 'units.unitid = ' . db_prefix() . 'stock_lists.unit',
     'LEFT JOIN ' . db_prefix() . 'stock_categories ON ' . db_prefix() . 'stock_categories.id = ' . db_prefix() . 'stock_lists.category',
     'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'stock_lists.currency_id',
     ];

$additionalSelect = [
    db_prefix() . 'stock_lists.id',
     'unit',
     'category',
    ];
$where =['AND '.db_prefix().'stock_lists.created_by = '.get_staff_user_id().''];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $attributes = [
        'data-toggle'             => 'modal',
        'data-target'             => '#stock_lists_modal',
        'data-id'                 => $aRow['id'],
        ];
        if ($aColumns[$i] == 'product_photo') {
            if($aRow['product_photo'] != '')
                $_data = '<a href="#"><img src="'.base_url($aRow['product_photo']).'" class="staff-profile-image-small"></a>';
            else
                $_data = '<a href="#"><img src="'.base_url('assets/images/user-placeholder.jpg').'" class="staff-profile-image-small"></a>';
        }
        
        if ($aColumns[$i] == 'product_name') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
        }
        $row[] = $_data;
    }
    $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', $attributes);


    $row[]              = $options .= icon_btn('warehouses/stock_list_delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
