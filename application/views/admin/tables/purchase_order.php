<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    // '(SELECT order_no FROM ' . db_prefix() . 'purchase_order_phases where id = ' . db_prefix() . 'purchase_order.purchase_phase_id) as purchase_phase',
    db_prefix() . 'purchase_order_phases.order_no, tblpurchase_order_phases.phase',
    'approval',
    '(SELECT company FROM ' . db_prefix() . 'clients where userid = ' . db_prefix() . 'purchase_order.acc_list) as company',
    'note',
    // '(SELECT firstname FROM ' . db_prefix() . 'staff where staffid = ' . db_prefix() . 'purchase_order.created_user) as fname',
    db_prefix() . 'staff.firstname, tblstaff.lastname',
    'date_and_time',
    'updated_user',

];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'purchase_order';

$join = [
   'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'purchase_order.created_user',
   'LEFT JOIN ' . db_prefix() . 'purchase_order_phases ON ' . db_prefix() . 'purchase_order_phases.id = ' . db_prefix() . 'purchase_order.purchase_phase_id',
];

$additionalSelect = [
    db_prefix() . 'purchase_order.id',
    'acc_list',
    'created_user'

];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    $row[] = $aRow['id'];
    $subjectOutput = $aRow['phase'];
    // $subjectOutput = format_purchase_phase($aRow['order_no'],$aRow['phase']);
    $subjectOutput .= '<div class="row-options">';

    $subjectOutput .= '<a href="' . admin_url('purchases/manage_purchase_order/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    $subjectOutput .= ' | <a href="' . admin_url('purchases/delete_purchase_order/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

    $subjectOutput .= '</div>';
    $row[] = $subjectOutput;

    $row[] = $aRow['approval'];
    // $row[] = format_approval_status($aRow['approval']);

    $row[] = $aRow['company'];

    $row[] = $aRow['note'];

    $row[] = '<a href="' . admin_url('staff/member/' . $aRow['created_user']) . '">' . $aRow['firstname']. ' '. $aRow['lastname'] . '</a>';

    $row[] = $aRow['date_and_time'];

   if(!empty($aRow['updated_user']))
    {
        $u_user = @$this->ci->db->query('select * from tblstaff where `staffid`='.$aRow['updated_user'])->row();
        $u_user_name = $u_user->firstname. ' ' . $u_user->lastname;
        $row[] = '<a href="' . admin_url('staff/member/' . $aRow['updated_user']) . '">' . $u_user_name . '</a>';
    }
    else
        $row[] = '';
    $output['aaData'][] = $row;
}
