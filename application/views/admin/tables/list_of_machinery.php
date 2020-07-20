<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'power_usage',
    'profit_expectation',
    'take_from',
    'export_to',
    'order_no',
    'active'

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'machines_list';

$join = [
//    'LEFT JOIN ' . db_prefix() . 'warehouses ON ' . db_prefix() . 'warehouses.id = ' . db_prefix() . 'machines_list.export_to',
];

$additionalSelect = [
    db_prefix() . 'machines_list.id',


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
            'data-target'             => '#list_machine_modal',
            'data-id'                 => $aRow['id'],
        ];

        if ($aColumns[$i] == 'name') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
        }
        if($aColumns[$i] == 'active'){
            $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
            <input type="checkbox"' . ' data-switch-url="' . admin_url() . 'manufacturing_settings/change_machine_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['active'] == 1 ? 'checked' : '') . '>
            <label class="onoffswitch-label" for="' . $aRow['id'] . '"></label>
            </div>';
            $_data = $toggleActive;
        }
        $row[] = $_data;
    }
    $options = icon_btn('#' . $aRow['id'], 'pencil-square-o', 'btn-default', $attributes);
    $row[]              = $options;
    $output['aaData'][] = $row;
}
