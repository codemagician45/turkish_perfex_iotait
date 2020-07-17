<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open('admin/purchases/purchase_order_manage',array('id'=>'purchase')); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                    $value = (isset($purchase_order) ? $purchase_order->purchase_phase_id : ''); 
                                    echo render_select('purchase_phase_id',$purchase_id,array('id','phase'),_l('purchase_phase_id')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    $value = (isset($purchase_order) ? $purchase_order->approval : '');  
                                    echo render_input('approval',_l('approval'),'1','text',array('placeholder'=>_l(''))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($purchase_order) ? $purchase_order->acc_list : '');
                                    echo render_select('acc_list',$acc_list,array('id','company'),_l('bought_company_name')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    $value = (isset($purchase_order) ? $purchase_order->note : ''); 
                                    echo render_input('note',_l('note'),'','text',array('placeholder'=>_l('note'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $createdUserNameValue = (isset($created_user_name) ? $created_user_name : "");?>
                                <?php echo render_input('created_user',_l('created_user'),$createdUserNameValue,'text',array('placeholder'=>_l('created_user'),'readonly'    => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $updatedUserNameValue = (isset($updated_user_name) ? $updated_user_name : "");?>
                                <?php echo render_input('updated_user',_l('last_updated_user'),$updatedUserNameValue,'text',array('placeholder'=>_l('last_updated_user'),'readonly'    => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $value = (isset($purchase_order) ? _d($purchase_order->date_and_time) : _d(date('Y-m-d h:i:s'))) ?>
                                <?php echo render_date_input('date_and_time','proposal_date',$value); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
             <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel_s">
                            <?php $this->load->view('admin/purchases/purchase_order/_add_edit_package'); ?>
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
    $(function(){
        appValidateForm($('form'), {
            // purchase_phase_id: 'required',
            // acc_list: 'required',
        });
    });

    $("body").on('change', 'select[name="item_select"]', function () {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview_purchase_item(itemid);
        }
    });

    function add_item_to_preview_purchase_item(id) {
        requestGetJSON('warehouses/get_item_by_id/' + id).done(function(response) {
            clear_item_preview_values();

            $('input[name="product_name"]').val(response.product_name);
            $('input[name="product_code"]').val(response.product_code);
            $('input[name="product_id"]').val(response.id);

            init_selectpicker();
            init_color_pickers();
            init_datepicker();

            $(document).trigger({
                type: "item-added-to-preview",
                item: response,
                item_type: 'item',
            });
        });
    } 
</script>

</body>
</html>
