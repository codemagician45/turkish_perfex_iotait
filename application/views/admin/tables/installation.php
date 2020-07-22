<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    db_prefix() . 'warehouses.warehouse_name',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'installation';

 $join = [
     'LEFT JOIN ' . db_prefix() . 'warehouses ON ' . db_prefix() . 'warehouses.id = ' . db_prefix() . 'installation.take_from',
     ];

$additionalSelect = [
    db_prefix() . 'installation.id',
    'take_from',
    'export_to'
    ];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
// print_r($rResult); exit();
foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['tblwarehouses.warehouse_name'];
    if(!empty($aRow['export_to'])){
        $t_to = @$this->ci->db->query('select * from tblwarehouses where `id`='.$aRow['export_to'])->row()->warehouse_name;
        $row[] = $t_to;
    } else {
        $row[] = '';
    }

    $attributes = [
        'data-toggle'             => 'modal',
        'data-target'             => '#installation_process_modal',
        'data-id'                 => $aRow['id'],
        ];
    // for ($i = 0; $i < count($aColumns); $i++) {
    //     $_data = $aRow[$aColumns[$i]];

        

    //     if ($aColumns[$i] == 'name') {
    //         $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
    //     }
    //     $row[] = $_data;
    // }
    $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', $attributes);


    $row[]              = $options .= icon_btn('manufacturing_settings/delete_installation_process/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $output['aaData'][] = $row;
}
