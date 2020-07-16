<?php

defined('BASEPATH') or exit('No direct script access allowed');

function format_approval_status($status, $classes = '', $label = true)
{
    $id = $status;
    if ($status == 1) {
        $status      = _l('Approval Need');
        $label_class = 'warning';
    } elseif ($status == 2) {
        $status      = _l('proposal_status_declined');
        $label_class = 'danger';
    } elseif ($status == 3) {
        $status      = _l('proposal_status_accepted');
        $label_class = 'success';
    } elseif ($status == 4) {
        $status      = _l('proposal_status_sent');
        $label_class = 'info';
    } elseif ($status == 5) {
        $status      = _l('proposal_status_revised');
        $label_class = 'info';
    } elseif ($status == 6) {
        $status      = _l('proposal_status_draft');
        $label_class = 'default';
    }

    if ($label == true) {
        return '<span class="label label-' . $label_class . ' ' . $classes . ' s-status proposal-status-' . $id . '">' . $status . '</span>';
    }

    return $status;
}
function format_purchase_phase($status, $classes = '', $label = true)
{
    $id = $status;
    if ($status == 1) {
        $status      = _l('Pending purchase request');
        $label_class = 'default';
    } elseif ($status == 2) {
        $status      = _l('Pending for arrival');
        $label_class = 'info';
    } elseif ($status == 3) {
        $status      = _l('Items Received');
        $label_class = 'success';
    } elseif ($status == 4) {
        $status      = _l('Items Received With Issues');
        $label_class = 'warning';
    } elseif ($status == 5) {
        $status      = _l('Cancelled');
        $label_class = 'danger';
    } elseif ($status == 6) {
        $status      = _l('Items Rejected');
        $label_class = 'danger';
    }

    if ($label == true) {
        return '<span class="label label-' . $label_class . ' ' . $classes . ' s-status proposal-status-' . $id . '">' . $status . '</span>';
    }

    return $status;
}