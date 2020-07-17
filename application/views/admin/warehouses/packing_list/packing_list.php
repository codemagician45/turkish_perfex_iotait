<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(),array('id'=>'packing_list')); ?>
            <!-- <?php
            $tras_id = 0;
            if (isset($transfer->id)) {
                $trans_id = (int)$transfer->id;
                ?>
                <input type="hidden" name="packId" value="<?= $trans_id; ?>">
                <?php
            } else {
                $trans_empty_id = '';
                ?>
                <input type="hidden" name="packId" value="<?= $trans_empty_id; ?>">
                <?php
            }
            ?> -->
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
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel_s">
                            <?php $this->load->view('admin/warehouses/packing_group/_add_edit_package'); ?>
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

    /*packing group part*/
    $("body").on('change', 'select[name="item_select"]', function () {
            var itemid = $(this).selectpicker('val');
            if (itemid != '') {
                add_item_to_preview_packing_group(itemid);
            }
        }); 

    function add_item_to_preview_packing_group(id) {
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

    // Append the added items to the preview to the table as items
    function add_item_to_table_pack_group(data, itemid, merge_invoice, bill_expense) {
        // If not custom data passed get from the preview
        data = typeof(data) == 'undefined' || data == 'undefined' ? get_item_preview_values_pack_group() : data;
        if (data.item_id === "" && data.product_name === "") { return; }

        var table_row = '';
        var item_key = $("body").find('tbody .item').length + 1;

        table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';
        table_row += '<td class="dragger">';

        $("body").append('<div class="dt-loader"></div>');
        var regex = /<br[^>]*>/gi;
        
        table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][order]">';

        table_row += '</td>';

        table_row += '<input type="hidden" name="newitems[' + item_key + '][item_id]" value = "' + data.item_id + '"><td class="bold description"><input type="text" name="newitems[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"></td>';
        
        table_row += '<td><input type="text" name="newitems[' + item_key + '][product_code]" class="form-control" value="'+data.product_code+'"></td>';

        var checks = $('input[name="default_pack"]');
        if(checks.prop("checked") == true) {

            table_row += '<td><div class="checkbox checkbox-primary" style="margin-top: 8px"><input type="checkbox" checked  name="newitems[' + item_key + '][default_pack]"  value="1" ><label for="default_pack"><?php echo _l('default_pack'); ?></label></div><input type="hidden" name="newitems[' + item_key + '][product_id]" class="form-control" value="' + data.product_id + '"></td>';
        }
        else if(checks.prop("checked") == false) {

            table_row += '<td><div class="checkbox checkbox-primary" style="margin-top: 8px"><input type="checkbox"  name="newitems[' + item_key + '][default_pack]"  value="0" ><label for="default_pack"><?php echo _l('default_pack'); ?></label></div><input type="hidden" name="newitems[' + item_key + '][product_id]" class="form-control" value="' + data.product_id + '"></td>';
        }
        
        table_row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

        table_row += '</tr>';

        $('table.items tbody').append(table_row);

        $(document).trigger({
            type: "item-added-to-table",
            data: data,
            row: table_row
        });

        if ($('#item_select').hasClass('ajax-search') && $('#item_select').selectpicker('val') !== '') {
            $('#item_select').prepend('<option></option>');
        }

        init_selectpicker();
        init_datepicker();
        init_color_pickers();
        clear_item_preview_values_pack_group();
        reorder_items();
        console.log($('#item_select'))
        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');
       
    }   

    function get_item_preview_values_pack_group() {
        var response = {};

        response.item_id = $('input[name="item_id"]').val();
        
        response.product_name = $('input[name="product_name"]').val();
        response.product_code = $('input[name="product_code"]').val();

        response.product_id = $('input[name="product_id"]').val();
        response.default_pack = $('input[name="default_pack"]').val();
    
        return response;
    }

    function clear_item_preview_values_pack_group(default_taxes) {
        var previewArea = $('.main');
        previewArea.find('input[name="product_name"]').val('');
        previewArea.find('input[name="product_code"]').val('');
        previewArea.find('input[name="product_id"]').val('');
        previewArea.find('input[name="default_pack"]').prop('checked',false);
    }

    function delete_package_item(row, itemid) {
        $(row).parents('tr').addClass('animated fadeOut', function() {
            setTimeout(function() {
                $(row).parents('tr').remove();
                calculate_total();
            }, 50);
        });
        
        $('#removed-items').append(hidden_input('removed_items[]', itemid));
    }

    // var checkboxDiv = $('body').find('.sortable');
    // console.log(checkboxDiv)
    // checkboxDiv.change(function(){
    //     console.log('aaa')
    //     for(let i=0; i<checkboxDiv.length; i++)
    //     {
    //         console.log(checkboxDiv[i].querySelectorAll('input[type="checkbox"]')[0].checked)
    //         if(checkboxDiv[i].querySelectorAll('input[type="checkbox"]')[0].checked == true)
    //             checkboxDiv[i].firstChild.value = 1;
    //         else
    //             checkboxDiv[i].firstChild.value = 0;
    //     }
    // })

    $('#packing_list').submit(function(e){
        e.preventDefault();
        var rows = $('body').find('.sortable');
        var cnt = 0;
        for(let k=0; k<rows.length; k++)
        {

            if(rows[k].querySelectorAll('input[type="checkbox"]')[0].checked == true)
            {
                rows[k].querySelectorAll('input[type="checkbox"]')[0].value = 1;
                cnt++;
            }
            else
                rows[k]. querySelectorAll('input[type="checkbox"]')[0].value = 0;
        }
        console.log(cnt)
        if(cnt>1)
        {
            alert('You Must Set Default Pack to One Item');
            return false;
        }
        document.getElementById("packing_list").submit();
    })


</script>

</body>
</html>
