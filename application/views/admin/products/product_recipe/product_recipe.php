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

    $("body").on('change', 'select[name="item_select"]', function () {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview_product_recipe(itemid);
        }
    });

    var productData;
    var defaultMachineData;
    var workHour = 0;
    var engergyPrice = 0 ;
    var mouldCavity = 0;

    var materialCost = 0;
    var productionCost = 0;
    var expectedProfitCost = 0;
    var installationCost = 0;

    requestGetJSON('manufacturing_settings/get_default_machine').done(function(response) {
        defaultMachineData = response;
        $('input[name="default_machine"]').val(response.name);
        // console.log(defaultMachineData)
    });

    requestGetJSON('manufacturing_settings/get_work_hour').done(function(response) {
        workHour = response.capacity_hours;
    });

    requestGetJSON('manufacturing_settings/get_energy_price').done(function(response) {
        engergyPrice = response.energy_price;
    });

    function add_item_to_preview_product_recipe(id) {
        requestGetJSON('warehouses/get_item_by_id_with_currency/' + id).done(function(response) {
            clear_item_preview_values();
            // console.log(response)
            productData = response;
            $('input[name="product_name"]').val(response.product_name);
            $('input[name="ingredient_item_id"]').val(response.id);

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

    $('#mould').change(function(){
        var mouldId = $('select[name="mould"]').val();
        requestGetJSON('manufacturing_settings/get_mould_activity_by_id/' + mouldId).done(function(response) {
            mouldCavity = response.mould_cavity;
            $('input[name="mould_cavity"]').val(mouldCavity)
            production_cost_cal();
            expected_profit_calc();
        });        
    });

    $('input[name="cycle_time"]').keyup(function(){
        production_cost_cal();
        expected_profit_calc();
    })

    $('#op_cost_per_sec').keyup(function(){
        production_cost_cal();
        installation_cost_calc();
    });

    $('#consumed_time').keyup(function(){
        installation_cost_calc();
    })


    function material_cost_calc () {
        var usedQty = $('input[name = "used_qty"]').val();
        var wasteRate = $('input[name = "rate_of_waste"]').val();
        
        if(productData)
            materialCost = productData.price * usedQty * productData.rate * wasteRate; 
        $('input[name="material_cost"]').val(materialCost);
    }

    function production_cost_cal(){
        if(defaultMachineData)
        {
            var powerUsage = defaultMachineData.power_usage;
            var cycleTime = $('input[name = "cycle_time"]').val();
            var opCostPerSec = $('input[name = "op_cost_per_sec"]').val();
            var profitExp = defaultMachineData.profit_expectation;

            productionCost = ((powerUsage * engergyPrice)/3600*cycleTime + opCostPerSec*cycleTime + (profitExp/workHour)/(3600/cycleTime*mouldCavity)).toFixed(2);
            $('input[name=production_cost]').val(productionCost);
        }
    }

    function expected_profit_calc(){
        if(defaultMachineData)
        {
            var cycleTime = $('input[name = "cycle_time"]').val();
            var profitExp = defaultMachineData.profit_expectation;

            expectedProfitCost = (profitExp/(3600/(cycleTime*mouldCavity))*workHour).toFixed(2);
            $('input[name=expected_profit]').val(expectedProfitCost);
        }
        
    }

    function installation_cost_calc()
    {
        var installConsumeTime = $('input[name="consumed_time"]').val();
        var opCostPerSec = $('input[name = "op_cost_per_sec"]').val();
        installationCost = installConsumeTime*opCostPerSec;
        $('input[name="ins_cost"]').val(installationCost);
    }


    function add_item_to_table_product_recipe(data, itemid, merge_invoice, bill_expense) {

        data = typeof (data) == 'undefined' || data == 'undefined' ? get_item_preview_product_recipe() : data;
        if (data.item_id === "" && data.product_name === "") {
            return;
        }
        // console.log('data',data)
        requestGetJSON('products/get_moulds_by_ajax').done(function(res) {
            var option = '<option></option>';
            res.forEach(e => {
                if(e.id == data.mouldid)
                    option += '<option value="'+e.id+'" selected>'+e.mould_name+'</option>';
                else
                    option += '<option value="'+e.id+'">'+e.mould_name+'</option>';
            })
            data.option = option;
            var table_row = '';
            var item_key = $("body").find('tbody .item').length + 1;

            table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';

            table_row += '<input type="hidden" name="newitems[' + item_key + '][item_id]" value = "' + data.item_id + '"><td class="bold description"><input type="text" name="newitems[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"><input type="hidden" name="newitems[' + item_key + '][ingredient_item_id]" class="form-control" value="' + data.ingredient_item_id + '"></td>';

            var checks = $('input[name="pre_produced"]');
            if(checks.prop("checked") == true) {

                table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox" checked  name="newitems[' + item_key + '][pre_produced]"  value="1" ><label for="pre_produced"></label></div></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox"  name="newitems[' + item_key + '][pre_produced]"  value="0" ><label for="pre_produced"></label></div></td>';
            }

            table_row += '<td><input type="number" name="newitems[' + item_key + '][used_qty]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value="' + data.used_qty + '"></td>';

            if(checks.prop("checked") == true) {

                table_row += '<td><input type="number" name="newitems[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value=""></td>';
                table_row += '<td><input type="text" name="newitems[' + item_key + '][default_machine]" readonly class="form-control" value=""></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="newitems[' + item_key + '][mould]" id="newitems[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98"></select></div></td>';
                table_row += '<td><input type="text" readonly name="newitems[' + item_key + '][mould_cavity]" class="form-control" value=""></td>';
                table_row += '<td><input type="number" name="newitems[' + item_key + '][cycle_time]" class="form-control cycle_time" value=""></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><input type="number" name="newitems[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value="' + data.rate_of_waste + '"></td>';
                table_row += '<td><input type="text" name="newitems[' + item_key + '][default_machine]" readonly class="form-control" value="' + data.default_machine + '"></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="newitems[' + item_key + '][mould]" id="newitems[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.option+'</select></div></td>';
                table_row += '<td><input type="text" readonly name="newitems[' + item_key + '][mould_cavity]" class="form-control" value="' + data.mould_cavity + '"></td>';
                table_row += '<td><input type="number" name="newitems[' + item_key + '][cycle_time]" class="form-control cycle_time" value="' + data.cycle_time + '"></td>';
            }

            table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][material_cost]" class="form-control" value="' + data.material_cost + '"></td>';

            if(checks.prop("checked") == true) {

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][production_cost]" class="form-control" value=""></td>';

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][expected_profit]" class="form-control" value=""></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][production_cost]" class="form-control" value="' + data.production_cost + '"></td>';

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][expected_profit]" class="form-control" value="' + data.expected_profit + '"></td>';
            }

            if(checks.prop("checked") == true) {
                var subtotalVal = Number(data.used_qty) * Number(data.material_cost)
                table_row += '<input type="hidden" name="newitems[' + item_key + '][subtotal]" class="subtotal" value="'+ subtotalVal.toFixed(2) +'">';
            }
            else if(checks.prop("checked") == false) {
                var subtotalVal = Number(data.material_cost) + Number(data.production_cost) + Number(data.expected_profit);
                table_row += '<input type="hidden" name="newitems[' + item_key + '][subtotal]" class="subtotal" value="' + subtotalVal.toFixed(2) + '">';
            }


            table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_product_recipe_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

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
            clear_item_preview_values_product_recipe(data);
            reorder_items();

            $('body').find('#items-warning').remove();
            $("body").find('.dt-loader').remove();
            $('#item_select').selectpicker('val', '');
        });
        
        var subtotal = 0;
        var total = $('#total').text();
        if(total == '') total = 0;

        if(data.pre_produced)
        {
            subtotal = Number(data.used_qty) * Number(data.material_cost);

        } else {
            subtotal = Number(data.material_cost) + Number(data.production_cost) + Number(data.expected_profit);
        }

        total  = Number(total) +  subtotal;
        // console.log('total',total)
        $('#total').text(total.toFixed(2))
        $('#total_value').val(total.toFixed(2));
    }

    function get_item_preview_product_recipe() {
        var response = {};

        response.item_id = $('.main input[name="item_id"]').val();
        response.ingredient_item_id = $('.main input[name="ingredient_item_id"]').val();
        response.product_name = $('.main input[name="product_name"]').val();
        response.pre_produced = $('.main input[name="pre_produced"]').prop('checked');
        response.used_qty = $('.main input[name="used_qty"]').val();
        response.rate_of_waste = $('.main input[name="rate_of_waste"]').val();
        response.default_machine = $('.main input[name="default_machine"]').val();
        response.mouldid = $('.main select[name="mould"]').val();
        response.mould_cavity = $('.main input[name="mould_cavity"]').val();
        response.cycle_time = $('.main input[name="cycle_time"]').val();
        response.material_cost = $('.main input[name="material_cost"]').val();
        response.production_cost = $('.main input[name="production_cost"]').val();
        response.expected_profit = $('.main input[name="expected_profit"]').val();
        response.ins_cost = $('.main input[name="ins_cost"]').val();
        response.consumed_time = $('.main input[name="consumed_time"]').val();

        return response;
    }

    function clear_item_preview_values_product_recipe(data){
        var previewArea = $('.main');
        previewArea.find('input[name="product_name"]').val('');
        previewArea.find('input[name="item_id"]').val('');
        previewArea.find('input[name="ingredient_item_id"]').val('');
        previewArea.find('input[name="product_name"]').val('');
        previewArea.find('input[name="used_qty"]').val('');
        previewArea.find('input[name="rate_of_waste"]').val('');
        previewArea.find('input[name="default_machine"]').val('');
        previewArea.find('select[name="mould"]').selectpicker('val','');
        previewArea.find('input[name="mould_cavity"]').val('');
        previewArea.find('input[name="cycle_time"]').val('');
        previewArea.find('input[name="material_cost"]').val('');
        previewArea.find('input[name="production_cost"]').val('');
        previewArea.find('input[name="expected_profit"]').val('');
    }

    function delete_product_recipe_item(row, itemid) {
        $(row).parents('tr').addClass('animated fadeOut', function() {
            setTimeout(function() {
                $(row).parents('tr').remove();
                var subtotal_elements = $('.subtotal');
                var total_value = 0;
                for(let i=0; i<subtotal_elements.length; i++)
                {
                    // console.log(subtotal_elements[i])
                    // console.log(Number(subtotal_elements[i].value))
                    total_value += Number(subtotal_elements[i].value);
                }
                // console.log('subtotal_elements',subtotal_elements)
                // console.log(total_value)
                $('#total').text(total_value.toFixed(2))
                $('#total_value').val(total_value.toFixed(2));

            }, 50);
        });
        $('#removed-items').append(hidden_input('removed_items[]', itemid));
    }

    // function calculate_total(){
    //     var subtotal_elements = $('.subtotal');
    //     var total_value = 0;
    //     for(let i=0; i<subtotal_elements.length; i++)
    //     {
    //         total_value += Number(subtotal_elements[i].value);
    //     }
    //     console.log('total_value',total_value)
    //     $('#total').text(total_value.toFixed(2))
    //     $('#total_value').val(total_value.toFixed(2));
    // }

    function material_cost_calc_for_added(row)
    {
        // console.log(engergyPrice,workHour,defaultMachineData)
        var productIdAdded = $(row).parents('tr').children()[1].lastChild.value;
        var usedQtyAdded = $(row).parents('tr').children()[3].firstChild.value;
        var wasteRateAdded = $(row).parents('tr').children()[4].firstChild.value;

        requestGetJSON('warehouses/get_item_by_id_with_currency/' + productIdAdded).done(function(response) {
            var materialCostAdded = response.price * usedQtyAdded * response.rate * wasteRateAdded;
            $(row).parents('tr').children()[9].firstChild.value = materialCostAdded;
            let currentProductionCost = $(row).parents('tr').children()[10].firstChild.value;
            let currentExpectedProfit = $(row).parents('tr').children()[11].firstChild.value;
            let subtotal = Number(materialCostAdded) + Number(currentProductionCost) + Number(currentExpectedProfit);
            $(row).parents('tr').children()[12].value = subtotal.toFixed(2);

            var subtotal_elements = $('.subtotal');
            var total_value = 0;
            for(let i=0; i<subtotal_elements.length; i++)
            {
                total_value += Number(subtotal_elements[i].value);
            }
            $('#total').text(total_value.toFixed(2))
            $('#total_value').val(total_value.toFixed(2));

        });
    }

    function production_cost_calc_for_added (row){
        
        var sel = $(row).parents('tr').children()[6].getElementsByTagName('select')[0];
        var mouldIdAdded = sel.options[sel.selectedIndex].value;
        if(mouldIdAdded)
            requestGetJSON('manufacturing_settings/get_mould_activity_by_id/' + mouldIdAdded).done(function(response) {
                mouldCavity = response.mould_cavity;
                if(defaultMachineData)
                {
                    var powerUsage = defaultMachineData.power_usage;
                    var cycleTime = $(row).parents('tr').children()[8].firstChild.value;
                    var opCostPerSec = $('input[name = "op_cost_per_sec"]').val();
                    var profitExp = defaultMachineData.profit_expectation;

                    productionCost = ((powerUsage * engergyPrice)/3600*cycleTime + opCostPerSec*cycleTime + (profitExp/workHour)/(3600/cycleTime*mouldCavity)).toFixed(2);
                    $(row).parents('tr').children()[10].firstChild.value = productionCost;

                    let currentMaterialCost = $(row).parents('tr').children()[9].firstChild.value;
                    let currentExpectedProfit = $(row).parents('tr').children()[11].firstChild.value;
                    let subtotal = Number(currentMaterialCost) + Number(productionCost) + Number(currentExpectedProfit);
                    $(row).parents('tr').children()[12].value = subtotal.toFixed(2);

                    var subtotal_elements = $('.subtotal');
                    var total_value = 0;
                    for(let i=0; i<subtotal_elements.length; i++)
                    {
                        total_value += Number(subtotal_elements[i].value);
                    }

                    $('#total').text(total_value.toFixed(2))
                    $('#total_value').val(total_value.toFixed(2));

                }
            }); 

        
    }

    function expected_profit_calc_for_added(row)
    {
        var sel = $(row).parents('tr').children()[6].getElementsByTagName('select')[0];
        var mouldIdAdded = sel.options[sel.selectedIndex].value;
        if(mouldIdAdded)
            requestGetJSON('manufacturing_settings/get_mould_activity_by_id/' + mouldIdAdded).done(function(response) {
                mouldCavity = response.mould_cavity;
                if(defaultMachineData)
                {
                    var cycleTime = $(row).parents('tr').children()[8].firstChild.value;
                    var profitExp = defaultMachineData.profit_expectation;

                    expectedProfitCost = (profitExp/(3600/(cycleTime*mouldCavity))*workHour).toFixed(2);
                    $(row).parents('tr').children()[11].firstChild.value = expectedProfitCost;

                    let currentMaterialCost = $(row).parents('tr').children()[9].firstChild.value;
                    let currentProductionCost = $(row).parents('tr').children()[10].firstChild.value;
                    let subtotal = Number(currentMaterialCost) + Number(currentProductionCost) + Number(expectedProfitCost);
                    $(row).parents('tr').children()[12].value = subtotal.toFixed(2);

                    var subtotal_elements = $('.subtotal');
                    var total_value = 0;
                    for(let i=0; i<subtotal_elements.length; i++)
                    {
                        total_value += Number(subtotal_elements[i].value);
                    }

                    $('#total').text(total_value.toFixed(2))
                    $('#total_value').val(total_value.toFixed(2));

                }
            }); 
    }

    $('.cycle_time').keyup(function(){

        production_cost_calc_for_added(this)
        expected_profit_calc_for_added(this)

    });

    $('.mouldid').change(function(){
        production_cost_calc_for_added(this)
        expected_profit_calc_for_added(this)
    })


</script>

</body>
</html>

