<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(),array('id'=>'packing_list')); ?>
            <div class="col-md-12 transfers">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->packing_type : '');
                                echo render_input('packing_type', _l('packing_type'), $value, 'text', array('placeholder' => _l(_l('packing_type')))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $value = (isset($packing_list) ? $packing_list->pack_capacity : ''); 
                                echo render_input('pack_capacity', _l('pack_capacity'), $value, 'number', array('placeholder' => _l('pack_capacity'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->box_quality : '');
                                echo render_input('box_quality', _l('box_quality'), $value, 'text', array('placeholder' => _l('box_quality'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->box_type : '');
                                echo render_input('box_type', _l('box_type'), $value, 'text', array('placeholder' => _l('box_type'))); ?>
                            </div>

                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->l_size : '');
                                echo render_input('l_size', _l('l_size'), $value, 'number', array('placeholder' => _l('l_size'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->w_size : '');
                                echo render_input('w_size', _l('w_size'), $value, 'number', array('placeholder' => _l('w_size'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->h_size : '');
                                echo render_input('h_size', _l('h_size'), $value, 'number', array('placeholder' => _l('h_size'))); ?>
                            </div>
                            <!-- <div class="col-md-6">
                                <?php echo render_input('volume', _l('volume_m3'), '', 'number', '', '', '', 'volume_m3', array('placeholder' => _l('volume_m3'))); ?>
                            </div> -->
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->volume : '');
                                echo render_input('volume', _l('volume_m3'), $value, 'number', array('placeholder' => _l('volume_m3'),'readonly'=>true)); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->pack_price : '');
                                echo render_input('pack_price', _l('pack_price'), $value, 'number', array('placeholder' => _l('pack_price'))); ?>
                            </div>

                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->price_per_item : '');
                                echo render_input('price_per_item', _l('price_per_item'), $value, 'number', array('placeholder' => _l('price_per_item'),'readonly'=>true)); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                $value = (isset($packing_list) ? $packing_list->stock_qty : '');
                                echo render_input('stock_qty', _l('stock_qty'), $value, 'number', array('placeholder' => _l('stock_qty'))); ?>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                    </div>
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>


<script>
    $(function () {
        appValidateForm($('form'), {
        });
    });


    $("#l_size, #w_size, #h_size").keyup(function () {
        update();
    });

    function update() {
        var l_value = $('input[name="l_size"]').val();
        var w_value = $('input[name="w_size"]').val();
        var h_value = $('input[name="h_size"]').val();
        var total_value = ((l_value * w_value * h_value) / 1000000000);
        var values = total_value.toFixed(8);
        $('input[name="volume"]').val(values);
    }

    $("#pack_capacity, #pack_price").keyup(function () {
        calculate();
    });

    function calculate() {

        var pack_capacity = $('input[name="pack_capacity"]').val();
        var pack_price = $('input[name="pack_price"]').val();

        var value = (pack_price / pack_capacity);
        var f_val = value.toFixed(2);
        $('input[name="price_per_item"]').val(f_val);


    }


</script>

</body>
</html>
