<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'stock_lists.product_code as product_code',
    'product_photo',
    db_prefix() .'stock_lists.product_name as product_name',
    db_prefix() .'pack_list.pack_capacity as pack_capacity',
    db_prefix() .'pack_list.packing_type as packing_type',
    db_prefix() .'pack_list.volume as volume',
    db_prefix() . 'pricing_calculation.price as price'
    // 'product_list_price as price'

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'stock_lists';

$join = [
   'LEFT JOIN ' . db_prefix() . 'package_group ON ' . db_prefix() . 'package_group.product_id = ' . db_prefix() . 'stock_lists.id',
   'LEFT JOIN ' . db_prefix() . 'pack_list ON ' . db_prefix() . 'pack_list.id = ' . db_prefix() . 'package_group.packing_id',
   'LEFT JOIN ' . db_prefix() . 'stock_categories ON ' . db_prefix() . 'stock_categories.order_no = ' . db_prefix() . 'stock_lists.category',
   'LEFT JOIN ' . db_prefix() . 'pricing_calculation ON ' . db_prefix() . 'pricing_calculation.rel_product_id = ' . db_prefix() . 'stock_lists.id',
];

$additionalSelect = [
    db_prefix() . 'stock_lists.id'
];

// $where =['AND '.db_prefix().'product_list.created_by = '.get_login_user_id().''];
// $where = ['AND '.db_prefix().'stock_lists.category = 9'];
// $where = ['AND '.db_prefix().'stock_categories.order_no = 3'];
$where = ['AND  '.db_prefix().'stock_categories.order_no = 3 OR '.db_prefix().'stock_categories.order_no = 2'];


// print_r($this->ci->input->post()); exit();
// $this->ci->input->post('product_2') = 'product_2';
if ($this->ci->input->post('products_2')) {
    $where = ['AND '.db_prefix().'stock_categories.order_no = 2'];
    // $where = ['AND '.db_prefix().'package_group.default_pack = 1'];
}
if ($this->ci->input->post('products_3')) {
    $where = ['AND '.db_prefix().'stock_categories.order_no = 3'];
    // $where = ['AND '.db_prefix().'package_group.default_pack = 1'];
}
// $where = ['AND '.db_prefix().'package_group.default_pack = 1'];
// if (count($filter) > 0) {
//     $where = [];
//     array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
// }

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join ,$where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $subjectOutput = '<input type = "hidden"  value="'.$aRow['id'].'"><span>'. $aRow['product_code'] . '</span>';
        $subjectOutput .= '<div class="row-options">';

        $subjectOutput .= '<a href="' . admin_url('products/manage_product_recipe/' . $aRow['id']) . '">' . _l('edit') . '</a>';
        // $subjectOutput .= ' | <a href="' . admin_url('products/delete_product_recipe/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

        $subjectOutput .= '</div>';
        $row[] = $subjectOutput;

        if($aRow['product_photo'] != '')
            $row[] = '<a href="#"><img src="'.base_url($aRow['product_photo']).'" class="staff-profile-image-small"></a>';
        else
            $row[] = '<a href="#"><img src="'.base_url('assets/images/user-placeholder.jpg').'" class="staff-profile-image-small"></a>';
        
        $row[] = $aRow['product_name'];
        $row[] = $aRow['pack_capacity'];
        $row[] = $aRow['packing_type'];
        $row[] = $aRow['volume'];
        $row[] = $aRow['price'];
    }


    $output['aaData'][] = $row;
}
