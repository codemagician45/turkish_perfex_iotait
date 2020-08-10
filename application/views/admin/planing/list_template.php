<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
 <div class="row">
  <div class="col-md-12" id="small-table">
    <div class="panel_s">
      <div class="panel-body">
        <?php echo form_hidden('estimateid'); ?>
        <?php //$this->load->view('admin/estimates/table_html'); ?>
        <?php $this->load->view('admin/planing/table_html'); ?>
      </div>
    </div>
  </div>
  <div class="col-md-7 small-table-right-col">
    <div id="estimate" class="hide">
    </div>
  </div>
</div>
</div>
