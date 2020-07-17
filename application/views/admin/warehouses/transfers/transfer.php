<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <!-- <?php echo form_open('admin/warehouses/transfers_manage',array('id'=>'transfer','class'=>'_transaction_form transferId')); ?> -->
            <?php echo form_open($this->uri->uri_string(),array('id'=>'transfer')); ?>
            <div class="col-md-12 transfers">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $selected = (isset($transfer) ? $transfer->stock_product_code : '');
                                    echo render_select('stock_product_code',$product_code,array('id','product_code'),_l('product_code'),$selected); 
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <?php 
                                    $selected = (isset($transfer) ? $transfer->transaction_from : '');
                                    echo render_select('transaction_from',$warehouse_list,array('id','warehouse_name'),_l('transaction_from'),$selected); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $selected = (isset($transfer) ? $transfer->transaction_to : '');
                                    echo render_select('transaction_to',$warehouse_list,array('id','warehouse_name'),_l('transaction_to'),$selected); 
                                    ?>
                            </div>

                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($transfer) ? $transfer->transaction_notes : '');
                                    echo render_input('transaction_notes',_l('transaction_notes'),$value,'text',array('placeholder'=>_l('transaction_notes'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    $value = (isset($transfer) ? $transfer->transaction_qty : ''); 
                                    echo render_input('transaction_qty',_l('transaction_qty'),$value,'number',array('placeholder'=>_l('transaction_qty'))); ?>
                                <!-- <input type="hidden" name="hidden_qty" id="hidden_qty"> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="checkbox checkbox-primary" style="margin-top: 25px">
                                    <input type="checkbox" id="allocation" name="allocation" <?php if(isset($transfer)){if($transfer->allocation == 1){echo ' checked';}}; ?>>
                                   <label for="allocation"><?php echo _l('allocation_enable'); ?></label>
                                </div>
                            </div>
                        <!-- </div>
                        <div class="row"> -->
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($transfer) ? $transfer->allocation_reason : '');
                                    echo render_input('allocation_reason',_l('allocation_reason'),$value,'text',array('placeholder'=>_l('allocation_reason'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($transfer) ? $transfer->wo_no : '');
                                    echo render_input('wo_no',_l('wo_no'),$value,'number',array('placeholder'=>_l('wo_no'))); ?>
                            </div>

                            <div class="col-md-6">
                                <?php 
                                    $value = (isset($transfer) ? $transfer->purchase_id : '');
                                    echo render_input('purchase_id',_l('purchase_id'),$value,'number',array('placeholder'=>_l('purchase_id'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $createdUserNameValue = (isset($created_user_name) ? $created_user_name : "");?>
                                <?php echo render_input('created_user',_l('created_user'),$createdUserNameValue,'text',array('placeholder'=>_l('created_user'),'readonly'    => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $updatedUserNameValue = (isset($updated_user_name) ? $updated_user_name : "");?>
                                <?php echo render_input('updated_user',_l('updated_user'),$updatedUserNameValue,'text',array('placeholder'=>_l('updated_user'),'readonly'    => 'readonly')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $value = (isset($transfer) ? _d($transfer->date_and_time) : _d(date('Y-m-d h:i:s'))) ?>
                                <?php echo render_date_input('date_and_time','proposal_date',$value); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    $value = (isset($transfer) ? $transfer->description : ''); 
                                    echo render_textarea('description',_l('description'),$value); ?>
                            </div>
                        </div>
                        <!-- <button class="btn btn-info mleft5 transferId-submit transaction-submit" type="button">
                            <?php echo _l('submit'); ?>
                        </button> -->
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
    <?php init_tail(); ?>


    <script>
        $(function(){
            appValidateForm($('form'), {
                stock_product_code: 'required',
                transaction_from: 'required',
                transaction_to:'required',
                transaction_qty:'required',
                // allocation:'required',
                // wo_no:'required',
                // purchase_id:'required',
            });
        });

        var id;
        var warehouses = [];
        var currentWarehouseQty = 0;
        $('#stock_product_code').change(function(){
            id = $(this).val()
            var tranferReqUrl = admin_url +'warehouses/get_transfers_by_product_code/' + id ;
            requestGetJSON(tranferReqUrl).done(function (results) {
                warehouses = results;
                console.log(results)
                var wId = $('#transaction_from').val();
                if(warehouses.length > 0 && !wId)
                {
                    var currentWarehouse = warehouses.filter(e => {
                        return e.warehouse_id == wId;
                    })
                    currentWarehouseQty = currentWarehouse[0] && currentWarehouse[0].qty;
                }
            });
        })
        
        $('#transaction_from').change(function(){
            var wId = $(this).val();
            if(warehouses.length > 0)
            {
                var currentWarehouse = warehouses.filter(e => {
                    return e.warehouse_id == wId;
                })
                currentWarehouseQty = currentWarehouse[0] && currentWarehouse[0].qty;
            }
            
        })

        $('#transaction_qty').keyup(function(){
            var wId = $('#transaction_from').val();
            if($('#stock_product_code').val()){
               var tranferReqUrl = admin_url +'warehouses/get_transfers_by_product_code/' + $('#stock_product_code').val() ;
                requestGetJSON(tranferReqUrl).done(function (results) {
                    warehouses = results;
                    if(warehouses.length > 0)
                    {
                        var currentWarehouse = warehouses.filter(e => {
                            return e.warehouse_id == wId;
                        })
                        currentWarehouseQty = currentWarehouse[0] && currentWarehouse[0].qty;
                    }
                }); 
            }
            else {
                alert('Please Select Product code');
                $(this).val('');
            }

            if(!wId){
                alert('Please Select Warehouse');
                $(this).val('');
            }
            else{
                var url = admin_url +'warehouses/get_current_warehouse/' + wId ;
                requestGetJSON(url).done(function (result) {
                    // console.log(result)
                    if(result.order_no != 1)
                    {
                        // console.log($('#transaction_qty').val(),currentWarehouseQty)
                        // console.log('aaa')
                        if($('#transaction_qty').val() > currentWarehouseQty)
                        {
                            alert('Overflowed Quantity from this Warehouse');
                            $('#transaction_qty').val('');
                        } 
                    }
                });
            }
            
        })
        
    </script>

    </body>
    </html>
