<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
   <div class="panel_s mbot10">
   </div>
   <div class="row">
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
               <?php $this->load->view('admin/finances/ready_to_invoice/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
