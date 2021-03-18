<?php

defined('BASEPATH') or exit('No direct script access allowed');
$dimensions = $pdf->getPageDimensions();

$pdf_logo_url = pdf_logo_url();
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'L', true);

$pdf->ln(4);
// Get Y position for the separation
$y = $pdf->getY();

$proposal_info = '<div style="color:#424242;">';
    $proposal_info .= format_organization_info();
$proposal_info .= '</div>';

$pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

$rowcount = max([$pdf->getNumLines($proposal_info, 80)]);

// Proposal to
$client_details = '<b>' . _l('proposal_to') . '</b>';
$client_details .= '<div style="color:#424242;">';
    $client_details .= format_proposal_info($proposal, 'pdf');
$client_details .= '</div>';

$pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount * 7, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

$pdf->ln(6);

$proposal_date = _l('proposal_date') . ': ' . _d($proposal->date);
$open_till     = '';

if (!empty($proposal->open_till)) {
    $open_till = _l('proposal_open_till') . ': ' . _d($proposal->open_till) . '<br />';
}

$qty_heading = _l('estimate_table_quantity_heading', '', false);

if ($proposal->show_quantity_as == 2) {
    $qty_heading = _l($this->type . '_table_hours_heading', '', false);
} elseif ($proposal->show_quantity_as == 3) {
    $qty_heading = _l('estimate_table_quantity_heading', '', false) . '/' . _l('estimate_table_hours_heading', '', false);
}

// The items table
$items = get_items_table_data($proposal, 'proposal', 'pdf')
        ->set_headings('estimate');

$items_html = $items->table2();
$items_html .= '<br /><br />';
$items_html .= '';
$items_html .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';


$proposal->content = str_replace('{proposal_items}', $items_html, $proposal->content);

$customer = _l('client').' : '.$proposal->customer;
$shipping_date = _l('req_shipping_date').' : '.$proposal->shipping_date;
$shipping_type = _l('shipping_type').' : '.$proposal->shipping_type;
$total_m3 = _l('total_m3').' : '.$proposal->total_m3;
// Get the proposals css
// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<p style="font-size:25px;"># $number</p>
<br />
<p style="font-size:20px;">$customer</p>
<p style="font-size:20px;">$shipping_date</p>
<p style="font-size:20px;">$shipping_type</p>
<p style="font-size:20px;">$total_m3</p>
<br />
<div style="width:675px !important;">
$proposal->content
</div>
EOF;
//print_r($html);
$pdf->writeHTML($html, true, false, true, false, '');
