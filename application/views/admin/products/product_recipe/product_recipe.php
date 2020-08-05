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

            var amount = data.material_cost + data.production_cost + data.expected_profit;
            var table_row = '';
            var item_key = $("body").find('tbody .item').length + 1;

            table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';

            table_row += '<input type="hidden" name="newitems[' + item_key + '][item_id]" value = "' + data.item_id + '"><td class="bold description"><input type="text" name="newitems[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"><input type="hidden" name="newitems[' + item_key + '][ingredient_item_id]" class="form-control" value="' + data.ingredient_item_id + '"></td>';

            var checks = $('input[name="pre_produced"]');
            if(checks.prop("checked") == true) {

                table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox" checked  name="newitems[' + item_key + '][pre_produced]"  data-pre-check value="1" ><label for="pre_produced"></label></div></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox"  name="newitems[' + item_key + '][pre_produced]"  data-pre-check value="0" ><label for="pre_produced"></label></div></td>';
            }

            table_row += '<td><input type="number" name="newitems[' + item_key + '][used_qty]" data-qty class="form-control" onkeyup = "material_cost_calc_for_added(this)" onchange = "material_cost_calc_for_added(this)" value="' + data.used_qty + '"></td>';

            if(checks.prop("checked") == true) {

                table_row += '<td><input type="number" name="newitems[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" onchange = "material_cost_calc_for_added(this)" value=""></td>';
                table_row += '<td><input type="text" name="newitems[' + item_key + '][default_machine]" readonly class="form-control" value=""></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="newitems[' + item_key + '][mould]" id="newitems[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98"></select></div></td>';
                table_row += '<td><input type="text" readonly name="newitems[' + item_key + '][mould_cavity]" class="form-control" value=""></td>';
                table_row += '<td><input type="number" name="newitems[' + item_key + '][cycle_time]" class="form-control cycle_time" value=""></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><input type="number" name="newitems[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value="' + data.rate_of_waste + '"></td>';
                table_row += '<td><input type="text" name="newitems[' + item_key + '][default_machine]" readonly class="form-control" value="' + data.default_machine + '"></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="newitems[' + item_key + '][mould]" id="newitems[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98" onchange="production_cost_calc_for_added(this);        expected_profit_calc_for_added(this);">'+data.option+'</select></div></td>';
                table_row += '<td><input type="text" readonly name="newitems[' + item_key + '][mould_cavity]" class="form-control" value="' + data.mould_cavity + '"></td>';
                table_row += '<td><input type="number" name="newitems[' + item_key + '][cycle_time]" class="form-control cycle_time" value="' + data.cycle_time + '" onchange="production_cost_calc_for_added(this);        expected_profit_calc_for_added(this);" onkeyup="production_cost_calc_for_added(this);        expected_profit_calc_for_added(this);"></td>';
            }

            table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][material_cost]" class="form-control" data-material-cost value="' + data.material_cost + '"></td>';

            if(checks.prop("checked") == true) {

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][production_cost]" class="form-control" data-production-cost value=""></td>';

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][expected_profit]" class="form-control" data-expected-profit value=""></td>';
            }
            else if(checks.prop("checked") == false) {

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][production_cost]" class="form-control" data-production-cost value="' + data.production_cost + '"></td>';

                table_row += '<td><input type="number" readonly name="newitems[' + item_key + '][expected_profit]" class="form-control" data-expected-profit value="' + data.expected_profit + '"></td>';
            }


            table_row += '<td class="amount" align="right">' + format_money(amount, true) + '</td>';

            table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_product_recipe_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

            table_row += '</tr>';

            $('table.items tbody').append(table_row);

            $(document).trigger({
                type: "item-added-to-table",
                data: data,
                row: table_row
            });

            setTimeout(function() {
                calculate_total_recipe();
            }, 15);

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
                calculate_total_recipe();
            }, 50);
        });
        $('#removed-items').append(hidden_input('removed_items[]', itemid));
    }

    function calculate_total_recipe()
    {
        var used_qty, mat_cost = 0, pro_cost = 0, exp_profit = 0, _amount = 0, subtotal = 0, total = 0;
        var other_cost = 0, ins_cost = 0;

        other_cost = parseFloat($('#other_cost').val());
        ins_cost = parseFloat($('#ins_cost').val());

        rows = $('.table.has-calculations tbody tr.item'),
        $.each(rows, function() {
            used_qty = $(this).find('[data-qty]').val();
            mat_cost = $(this).find('[data-material-cost]').val();
            pro_cost = $(this).find('[data-production-cost]').val();
            exp_profit = $(this).find('[data-expected-profit]').val();
            pre_produced = $(this).find('[data-pre-check]').prop('checked')

            if(!pre_produced)
                _amount = parseFloat(mat_cost) + parseFloat(pro_cost) + parseFloat(exp_profit);
            else
                _amount = parseFloat(used_qty) * parseFloat(mat_cost);
            // console.log(pre_produced,used_qty,mat_cost,pro_cost,exp_profit,_amount)
            subtotal += _amount;
            $(this).find('td.amount').html(format_money(_amount, true));
            row = $(this);
        });
        console.log(subtotal, other_cost, ins_cost)
        total = (total + subtotal + other_cost + ins_cost);
        $('.total').html(format_money(total) + hidden_input('total', accounting.toFixed(total, app.options.decimal_places)));
    }

    $(document).ready(function(){
        calculate_total_recipe()
    })

    function material_cost_calc_for_added(row)
    {
        // console.log(engergyPrice,workHour,defaultMachineData)
        var productIdAdded = $(row).parents('tr').children()[1].lastChild.value;
        var usedQtyAdded = $(row).parents('tr').children()[3].firstChild.value;
        var wasteRateAdded = $(row).parents('tr').children()[4].firstChild.value;

        requestGetJSON('warehouses/get_item_by_id_with_currency/' + productIdAdded).done(function(response) {
            var materialCostAdded = response.price * usedQtyAdded * response.rate * (1+ wasteRateAdded/100);
            $(row).parents('tr').find('[data-material-cost]').val(materialCostAdded);
            calculate_total_recipe()
        });
    }

    function production_cost_calc_for_added(row){
        
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

                    // p_cost1 = (((powerUsage * engergyPrice)/3600)*cycleTime);
                    // p_cost2 = (opCostPerSec*cycleTime);
                    // p_cost3 = ((profitExp/workHour)/(3600/cycleTime*mouldCavity))
                    // console.log(p_cost1,p_cost2,p_cost3)
                    // productionCost = (((powerUsage * engergyPrice)/3600)*cycleTime) + (opCostPerSec*cycleTime) + ((profitExp/workHour)/(3600/cycleTime*mouldCavity)).toFixed(2);
                    productionCost = ((((powerUsage * engergyPrice)/3600)*cycleTime) + (opCostPerSec*cycleTime) + ((profitExp/workHour)/(3600/cycleTime*mouldCavity))).toFixed(2);

                    $(row).parents('tr').find('[data-production-cost]').val(productionCost);
                    calculate_total_recipe()
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
                    expectedProfitCost = (profitExp/(3600/(cycleTime*mouldCavity)*workHour)).toFixed(2);
                    $(row).parents('tr').find('[data-expected-profit]').val(expectedProfitCost);
                    calculate_total_recipe()
                }
            }); 
    }

    function material_cost_calc () {
        var usedQty = $('input[name = "used_qty"]').val();
        var wasteRate = $('input[name = "rate_of_waste"]').val();
        
        if(productData)
            materialCost = productData.price * usedQty * productData.rate * (1+wasteRate/100); 
        $('input[name="material_cost"]').val(materialCost);
        calculate_total_recipe()
    }

    function production_cost_cal(){
        if(defaultMachineData)
        {
            var powerUsage = defaultMachineData.power_usage;
            var cycleTime = $('input[name = "cycle_time"]').val();
            var opCostPerSec = $('input[name = "op_cost_per_sec"]').val();
            var profitExp = defaultMachineData.profit_expectation;

            // p_cost1 = (((powerUsage * engergyPrice)/3600)*cycleTime);
            // p_cost2 = (opCostPerSec*cycleTime);
            // p_cost3 = ((profitExp/workHour)/(3600/cycleTime*mouldCavity))
            // console.log(p_cost1,p_cost2,p_cost3)
            productionCost = ((((powerUsage * engergyPrice)/3600)*cycleTime) + (opCostPerSec*cycleTime) + ((profitExp/workHour)/(3600/cycleTime*mouldCavity))).toFixed(2);
            // // productionCost = ((powerUsage * engergyPrice)/3600*cycleTime + opCostPerSec*cycleTime + (profitExp/workHour)/(3600/cycleTime*mouldCavity)).toFixed(2);
            $('input[name=production_cost]').val(productionCost);
            calculate_total_recipe()
        }
    }

    function expected_profit_calc(){
        if(defaultMachineData)
        {
            var cycleTime = $('input[name = "cycle_time"]').val();
            var profitExp = defaultMachineData.profit_expectation;

            expectedProfitCost = (profitExp/(3600/(cycleTime*mouldCavity)*workHour)).toFixed(2);
            $('input[name=expected_profit]').val(expectedProfitCost);
            calculate_total_recipe()
        }
        
    }

    function installation_cost_calc()
    {
        var installConsumeTime = $('input[name="consumed_time"]').val();
        var opCostPerSec = $('input[name = "op_cost_per_sec"]').val();
        installationCost = installConsumeTime*opCostPerSec;
        $('input[name="ins_cost"]').val(installationCost);
    }

    $('.base_cal').keyup(function(){
        calculate_total_recipe();
    })
    $('.base_cal').change(function(){
        calculate_total_recipe();
    })


</script>

</body>
</html>

