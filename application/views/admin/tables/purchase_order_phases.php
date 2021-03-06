<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'order_no',
    'phase',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'purchase_order_phases';
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    'id',
]);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $attributes = [
        'data-toggle'             => 'modal',
        'data-target'             => '#purchase_order_phase_modal',
        'data-id'                 => $aRow['id'],
        ];

        if ($aColumns[$i] == 'phase') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
        }
        $row[] = $_data;
    }



    $row[]              =  $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', $attributes);
    $output['aaData'][] = $row;
}
