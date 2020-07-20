<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'product_code',
    'product_photo',
    db_prefix() .'stock_lists.product_name as product_name',
    db_prefix() . 'barcode_list.barcode_id as barcode_no',
    

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'stock_lists';

$join = [
   'LEFT JOIN ' . db_prefix() . 'barcode_list ON ' . db_prefix() . 'barcode_list.products_code = ' . db_prefix() . 'stock_lists.id',
];

$additionalSelect = [
    db_prefix() . 'stock_lists.id'
];

// $where =['AND '.db_prefix().'product_list.created_by = '.get_login_user_id().''];
$where = [];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join ,$where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $row[] = $aRow['product_code'];

        if($aRow['product_photo'] != '')
            $row[] = '<a href="#"><img src="'.base_url($aRow['product_photo']).'" class="staff-profile-image-small"></a>';
        else
            $row[] = '<a href="#"><img src="'.base_url('assets/images/user-placeholder.jpg').'" class="staff-profile-image-small"></a>';
        
        $row[] = $aRow['product_name'];
        $row[] = $aRow['barcode_no'];
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
    }


    $output['aaData'][] = $row;
}
