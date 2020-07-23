<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(),array('id'=>'products_recipe')); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($product) ? $product->product_code: ''); 
                                    echo render_input('product_code', _l('product_code'), $value, 'text', array('readonly' => true)); ?>
                                <?php //echo render_input('rel_product_id', '', '', 'hidden'); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($product) ? $product->product_name: '');
                                    echo render_input('product_name', _l('product_name'), $value, 'text', array('readonly' => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($pack) ? $pack->packing_type: '');
                                    echo render_input('packing_type', _l('packing_type'), $value, 'text', array('readonly' => 'readonly'));?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($pack) ? $pack->pack_capacity: '');
                                    echo render_input('pack_capacity', _l('pack_capacity'), $value, 'number', array('readonly' => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($pack) ? $pack->volume: '');
                                    echo render_input('volume', _l('volume_m3'), $value, 'number', array('readonly' => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    // $value = (isset($product) ? $product->price: ''); 
                                    echo render_input('price', _l('price'), '', 'number', array('placeholder' => _l('pack price'),'readonly'    => 'readonly')); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('other_cost_details', _l('other_cost_details'), '', 'text', array('placeholder' => _l('other_cost_details'))); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('other_cost', _l('other_cost'), '', 'number', array('placeholder' => _l('other_cost'))); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('op_cost_per_sec', _l('op_cost_per_sec'), '', 'number', array('placeholder' => _l('op_cost_per_sec'))); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_input('consumed_time', _l('installation_consumed_time'), '', 'number', array('placeholder' => _l('installation_consumed_time'))); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel_s">
                            <?php $this->load->view('admin/products/product_recipe/_add_edit_package'); ?>
                        </div>
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>


<script>
  
</script>

</body>
</html>

