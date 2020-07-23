<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('invoice_item_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('invoice_item_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/invoice_items/manage', array('id' => 'invoice_item_form')); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <?php echo render_input('description', 'invoice_item_add_edit_description'); ?>
                        <?php echo render_textarea('long_description', 'invoice_item_long_description'); ?>
                        <div class="form-group">
                            <label for="rate" class="control-label">
                                <?php echo _l('invoice_item_add_edit_rate_currency', $base_currency->name . ' <small>(' . _l('base_currency_string') . ')</small>'); ?></label>
                            <input type="number" id="rate" name="rate" class="form-control" value="">
                        </div>
                        <?php
                        foreach ($currencies as $currency) {
                            if ($currency['isdefault'] == 0 && total_rows(db_prefix() . 'clients', array('default_currency' => $currency['id'])) > 0) { ?>
                                <div class="form-group">
                                    <label for="rate_currency_<?php echo $currency['id']; ?>" class="control-label">
                                        <?php echo _l('invoice_item_add_edit_rate_currency', $currency['name']); ?></label>
                                    <input type="number" id="rate_currency_<?php echo $currency['id']; ?>"
                                           name="rate_currency_<?php echo $currency['id']; ?>" class="form-control"
                                           value="">
                                </div>
                            <?php }
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="tax"><?php echo _l('tax_1'); ?></label>
                                    <select class="selectpicker display-block" data-width="100%" name="tax"
                                            data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                        <option value=""></option>
                                        <?php foreach ($taxes as $tax) { ?>
                                            <option value="<?php echo $tax['id']; ?>"
                                                    data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>
                                                %
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label>
                                    <select class="selectpicker display-block" disabled data-width="100%" name="tax2"
                                            data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                        <option value=""></option>
                                        <?php foreach ($taxes as $tax) { ?>
                                            <option value="<?php echo $tax['id']; ?>"
                                                    data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>
                                                %
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix mbot15"></div>
                        <?php echo render_input('unit', 'unit'); ?>
                        <div id="custom_fields_items">
                            <?php echo render_custom_fields('items'); ?>
                        </div>
                        <?php echo render_select('group_id', $items_groups, array('id', 'name'), 'item_group'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    if (typeof (jQuery) != 'undefined') {
        init_item_js();
    } else {
        window.addEventListener('load', function () {
            var initItemsJsInterval = setInterval(function () {
                if (typeof (jQuery) != 'undefined') {
                    init_item_js();
                    clearInterval(initItemsJsInterval);
                    calculate_total2();
                }
            }, 1000);
        });
    }

    // Items add/edit
    function manage_invoice_items(form) {
        var data = $(form).serialize();

        var url = form.action;
        $.post(url, data).done(function (response) {
            response = JSON.parse(response);
            if (response.success == true) {
                var item_select = $('#item_select');
                if ($("body").find('.accounting-template').length > 0) {
                    if (!item_select.hasClass('ajax-search')) {
                        var group = item_select.find('[data-group-id="' + response.item.group_id + '"]');
                        if (group.length == 0) {
                            var _option = '<optgroup label="' + (response.item.group_name == null ? '' : response.item.group_name) + '" data-group-id="' + response.item.group_id + '">' + _option + '</optgroup>';
                            if (item_select.find('[data-group-id="0"]').length == 0) {
                                item_select.find('option:first-child').after(_option);
                            } else {
                                item_select.find('[data-group-id="0"]').after(_option);
                            }
                        } else {
                            group.prepend('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">(' + accounting.formatNumber(response.item.rate) + ') ' + response.item.description + '</option>');
                        }
                    }
                    if (!item_select.hasClass('ajax-search')) {
                        item_select.selectpicker('refresh');
                    } else {

                        item_select.contents().filter(function () {
                            return !$(this).is('.newitem') && !$(this).is('.newitem-divider');
                        }).remove();

                        var clonedItemsAjaxSearchSelect = item_select.clone();
                        item_select.selectpicker('destroy').remove();
                        $("body").find('.items-select-wrapper').append(clonedItemsAjaxSearchSelect);
                        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
                    }

                    add_item_to_preview(response.item.itemid);
                } else {
                    // Is general items view
                    $('.table-invoice-items').DataTable().ajax.reload(null, false);
                }
                alert_float('success', response.message);
            }
            $('#sales_item_modal').modal('hide');
        }).fail(function (data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }

    function init_item_js() {
        // Add item to preview from the dropdown for invoices estimates
        $("body").on('change', 'select[name="item_select"]', function () {
            var itemid = $(this).selectpicker('val');
            if (itemid != '') {
                // add_item_to_preview(itemid);
                // console.log('anything')
                add_item_to_preview2(itemid);
            }
        });

        // Items modal show action
        $("body").on('show.bs.modal', '#sales_item_modal', function (event) {

            $('.affect-warning').addClass('hide');

            var $itemModal = $('#sales_item_modal');
            $('input[name="itemid"]').val('');
            $itemModal.find('input').not('input[type="hidden"]').val('');
            $itemModal.find('textarea').val('');
            $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
            $('select[name="tax2"]').selectpicker('val', '').change();
            $('select[name="tax"]').selectpicker('val', '').change();
            $itemModal.find('.add-title').removeClass('hide');
            $itemModal.find('.edit-title').addClass('hide');

            var id = $(event.relatedTarget).data('id');
            // If id found get the text from the datatable
            if (typeof (id) !== 'undefined') {

                $('.affect-warning').removeClass('hide');
                $('input[name="itemid"]').val(id);

                requestGetJSON('invoice_items/get_item_by_id/' + id).done(function (response) {
                    $itemModal.find('input[name="description"]').val(response.description);
                    $itemModal.find('textarea[name="long_description"]').val(response.long_description.replace(/(<|<)br\s*\/*(>|>)/g, " "));
                    $itemModal.find('input[name="rate"]').val(response.rate);
                    $itemModal.find('input[name="unit"]').val(response.unit);
                    $('select[name="tax"]').selectpicker('val', response.taxid).change();
                    $('select[name="tax2"]').selectpicker('val', response.taxid_2).change();
                    $itemModal.find('#group_id').selectpicker('val', response.group_id);
                    $.each(response, function (column, value) {
                        if (column.indexOf('rate_currency_') > -1) {
                            $itemModal.find('input[name="' + column + '"]').val(value);
                        }
                    });

                    $('#custom_fields_items').html(response.custom_fields_html);

                    init_selectpicker();
                    init_color_pickers();
                    init_datepicker();

                    $itemModal.find('.add-title').addClass('hide');
                    $itemModal.find('.edit-title').removeClass('hide');
                    validate_item_form();
                });

            }
        });

        $("body").on("hidden.bs.modal", '#sales_item_modal', function (event) {
            $('#item_select').selectpicker('val', '');
        });

        validate_item_form();
    }

    function validate_item_form() {
        // Set validation for invoice item form
        appValidateForm($('#invoice_item_form'), {
            description: 'required',
            rate: {
                required: true,
            }
        }, manage_invoice_items);
    }


    // Add item to preview
    function add_item_to_preview2(id) {
        requestGetJSON('stock_lists/get_item_by_id/' + id).done(function (response) {

            clear_item_preview_values();

            $('.main input[name="ingredient_id"]').val(response.id);
            $('.main textarea[name="product_name"]').val(response.product_name);
            $('.main textarea[name="product_code"]').val(response.product_code);
            // $('.main input[name="product_id"]').val(response.id);

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
    function add_item_to_table2(data, itemid, merge_invoice, bill_expense) {


        // If not custom data passed get from the preview
        data = typeof (data) == 'undefined' || data == 'undefined' ? get_item_preview_values2() : data;
        if (data.item_id === "" && data.product_name === "") {
            return;
        }


        var table_row = '';
        var item_key = $("body").find('tbody .item').length + 1;

        table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';

        // table_row += '<td class="dragger">';
        //
        // // Check if quantity is number
        // if (isNaN(data.quantity)) {
        //     data.quantity = 1;
        // }
        //
        // // Check if received_qty is number
        // if (data.received_qty === '' || isNaN(data.received_qty)) {
        //     data.received_qty = 0;
        // }
        //
        // var amount = data.received_qty * data.quantity;
        // console.log(amount);
        //
        // // var tax_name = 'newitems[' + item_key + '][taxname][]';
        // $("body").append('<div class="dt-loader"></div>');
        // var regex = /<br[^>]*>/gi;
        // // get_taxes_dropdown_template(tax_name, data.taxname).done(function(tax_dropdown) {
        //
        // //     // order input
        // table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][order]">';
        //
        // table_row += '</td>';

        table_row += '<td><textarea name="newitems[' + item_key + '][product_name]" class="form-control item_long_description" rows="5">' + data.product_name + '</textarea></td>';


        var checks = $('input[name="pre_produced"]');
        if (checks.prop("checked") == true) {

            table_row += '<td><input type="checkbox" checked  name="newitems[' + item_key + '][pre_produced]"  value="1" ><label for="default_pack">Pre-produced</label></td>';
        } else if (checks.prop("checked") == false) {

            table_row += '<td><input type="checkbox"  name="newitems[' + item_key + '][pre_produced]"  value="0" ><label for="Pre-produced">Default pack </label>';
        }
        //
        table_row += '<td><input type="text" data-quantity onkeyup="calculate_total2();" name="newitems[' + item_key + '][used_qty]" class="form-control" data-quantity value="' + data.used_qty + '" ><input type="hidden" name="newitems[' + item_key + '][ingredient_id]" class="form-control" value="' + data.ingredient_id + '"></td></td>';

        table_row += '<td class="waste"><input type="text" onkeyup="calculate_total2();" name="newitems[' + item_key + '][rate_of_waste]" class="form-control" value="' + data.rate_of_waste + '" ></td>';
        table_row += '<td class="def-machine"><input type="text" onclick="calculate_total2();" readonly name="newitems[' + item_key + '][default_machine]" class="form-control def-machine-'+item_key+'" value="' + data.default_machine + '"></td>';
        var pack_name = 'mould_id';
        table_row += '<td>' + packageDropdown(pack_name, data.mould_id, item_key) + '</td>';
        table_row += '<td class="mould-num"><input type="text" onclick="calculate_total2();" readonly name="newitems[' + item_key + '][mould_cavity]" class="form-control cavity-'+item_key+'" value="' + data.mould_cavity + '"></td>';
        table_row += '<td class="cycle-time"><input type="number" onkeyup="calculate_total2();"  name="newitems[' + item_key + '][cycle_time]" class="form-control" value="' + data.cycle_time + '"></td>';
        table_row += '<td class="mt-cost"><input type="number" readonly name="newitems[' + item_key + '][mater_cost]" class="form-control" value="' + data.mater_cost + '"></td>';
        table_row += '<td class="pro-cost"><input type="number" readonly name="newitems[' + item_key + '][pro_cost]" class="form-control" value="' + data.pro_cost + '"></td>';
        table_row += '<td class="exp-cost"><input type="number" readonly name="newitems[' + item_key + '][exp_profit]" class="form-control" value="' + data.exp_profit + '"></td>';
        // table_row += '<td><input type="number" name="newitems[' + item_key + '][ins_cost]" class="form-control" value="' + data.ins_cost + '"></td>';
        // table_row += '<td><input type="number" name="newitems[' + item_key + '][consumed_time]" class="form-control" value="' + data.consumed_time + '"></td>';
        // table_row += '<td><input type="text" readonly  name="newitems[' + item_key + '][line_price]" class="form-control" value="' + data.line_price + '"></td>';

        //     table_row += '<td class="rate"><input type="number" data-toggle="tooltip" title="' + app.lang.item_field_not_formatted + '" onblur="calculate_total();" onchange="calculate_total();" name="newitems[' + item_key + '][rate]" value="' + data.rate + '" class="form-control"></td>';

        //     table_row += '<td class="taxrate">' + tax_dropdown + '</td>';


        table_row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item2(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

        table_row += '</tr>';

        $('table.items tbody').append(table_row);

        // var checks = $('input[name="default_pack"]');
        // if(checks.prop("checked") == true){
        //     console.log("Checkbox is checked.");
        // }
        // else if(checks.prop("checked") == false){
        //     console.log("Checkbox is unchecked.");
        // }

        // console.log('ok');
        $(document).trigger({
            type: "item-added-to-table",
            data: data,
            row: table_row
        });
        setTimeout(function () {
            calculate_total2();
            clear_item_preview_values2();
        }, 10);

        //     setTimeout(function() {
        //         calculate_total();
        //     }, 15);



        if ($('#item_select').hasClass('ajax-search') && $('#item_select').selectpicker('val') !== '') {
            $('#item_select').prepend('<option></option>');
        }

        init_selectpicker();
        init_datepicker();
        init_color_pickers();

        reorder_items();

        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        // $('#item_select').selectpicker('val', '');

        //     if (cf_has_required && $('.invoice-form').length) {
        //         validate_invoice_form();
        //     } else if (cf_has_required && $('.estimate-form').length) {
        //         validate_estimate_form();
        //     } else if (cf_has_required && $('.proposal-form').length) {
        //         validate_proposal_form();
        //     } else if (cf_has_required && $('.credit-note-form').length) {
        //         validate_credit_note_form();
        //     }

        return true;

        // });

        return false;
    }

    // Get the preview main values
    function get_item_preview_values2() {
        var response = {};

        response.item_id = $('.main input[name="item_id"]').val();
        response.product_name = $('.main textarea[name="product_name"]').val();
        response.used_qty = $('.main input[name="used_qty"]').val();
        response.ingredient_id = $('.main input[name="ingredient_id"]').val();
        response.rate_of_waste = $('.main input[name="rate_of_waste"]').val();
        response.default_machine = $('.main input[name="default_machine"]').val();
        response.mould_cavity = $('.main input[name="mould_cavity"]').val();
        response.cycle_time = $('.main input[name="cycle_time"]').val();
        response.mater_cost = $('.main input[name="mater_cost"]').val();
        response.pro_cost = $('.main input[name="pro_cost"]').val();
        response.exp_profit = $('.main input[name="exp_profit"]').val();
        response.ins_cost = $('.main input[name="ins_cost"]').val();
        // response.ins_cost = $('.main input[name="ins_cost"]').val();
        response.consumed_time = $('.main input[name="consumed_time"]').val();
        // response.line_price = $('.main input[name="line_price"]').val();
        response.mould_id = $('.mould_item').find(":selected").val();


        // response.long_description = $('.main textarea[name="long_description"]').val();

        // response.taxname = $('.main select.tax').selectpicker('val');

        // response.ingredient_id = $('.main input[name="ingredient_id"]').val();
        response.default_pack = $('.main input[name="default_pack"]').val();

        return response;
    }

    // Clear the items added to preview
    function clear_item_preview_values2(default_taxes) {

        // Get the last taxes applied to be available for the next item
        // var last_taxes_applied = $('table.items tbody').find('tr:last-child').find('select').selectpicker('val');
        var previewArea = $('.main');

        previewArea.find('textarea[name="product_name"]').val('');
        // previewArea.find('textarea[name="product_code"]').val('');
        // previewArea.find('textarea').val(''); // includes cf
        // previewArea.find('td.custom_field input[type="checkbox"]').prop('checked', false); // cf
        // previewArea.find('td.custom_field input:not(:checkbox):not(:hidden)').val(''); // cf // not hidden for chkbox hidden helpers
        // previewArea.find('td.custom_field select').selectpicker('val', ''); // cf

        previewArea.find('input[name="used_qty"]').val('');
        // previewArea.find('input[name="ingredient_id"]').val('');
        previewArea.find('input[name="rate_of_waste"]').val('');
        previewArea.find('input[name="mould_cavity"]').val('');
        previewArea.find('input[name="cycle_time"]').val('');
        previewArea.find('input[name="mater_cost"]').val('');
        previewArea.find('input[name="exp_profit"]').val('');
        previewArea.find('input[name="pro_cost"]').val('');
        previewArea.find('input[name="ins_cost"]').val('');
        previewArea.find('input[name="consumed_time"]').val('');
        previewArea.find('input[name="ingredient_id"]').val('');
        previewArea.find('input[name="default_pack"]').val('');
        previewArea.find('input[name="default_machine"]').val('');


        // previewArea.find('select.tax').selectpicker('val', last_taxes_applied);


        // $('input[name="task_id"]').val('');
        // $('input[name="expense_id"]').val('');
    }

    // Calculate invoice total - NOT RECOMENDING EDIT THIS FUNCTION BECUASE IS VERY SENSITIVE

    //developed by shovon

    // test calculation


    function calculate_total2() {

        if ($('body').hasClass('no-calculate-total')) {
            return false;
        }

        var calculated_tax,
            taxrate,
            item_taxes,
            row,
            _amount,
            _tax_name,
            taxes = {},
            taxes_rows = [],
            sum_volume_m3 = 0,
            ingredient_item_price = 0,
            subtotal = 0,
            other_val = 0,
            sum_total = 0.0,
            material_total = 0,
            ins_cost = 0,
            profit_total = 0,
            production_total = 0,

            total = 0,
            quantity = 1,
            total_discount_calculated = 0,
            quote_phase_counter = 0,
            rows = $('.table.has-calculations tbody tr.item'),
            discount_area = $('#discount_area'),
            adjustment = $('input[name="adjustment"]').val(),
            discount_percent = $('input[name="discount_percent2"]').val(),
            discount_fixed = $('input[name="discount_total"]').val(),
            discount_total_type = $('.discount-total-type.selected');
        // discount_type = $('select[name="discount_type"]').val();

        //console.log(discount_percent);

        $('.tax-area').remove();

        var operator_val = $('input[name="op_cost_per_sec"]').val();
        other_val = $('input[name="other_cost"]').val();
        var consumed_value = $('input[name="consumed_time"]').val();

        $.each(rows, function () {

            // _amount = accounting.toFixed($(this).find('td.sale-price input').val() * quantity, app.options.decimal_places);
            // _amount = parseFloat(_amount);

            // $(this).find('td.amount').html(format_money(_amount, true));
            // subtotal += _amount;

            // subtotal += _amount;
            // row = $(this);

            var pack_value = $('input[name="ingredient_id"]').val();
            var profit_value = $('input[name="default_machine"]').val();
            var cycle_value = $('input[name="cycle_time"]').val();

            var mould_val = $('input[name="mould_cavity"]').val();
            // console.log(quantity);

            // Filling each individual row volume_m3 valude based on the selection of Pack Capacity....
            var packId = parseInt(pack_value);
            // var profitId = parseInt(profit_value);

            var ingredient_item_price = getPrice(packId);

            console.log(pack_value);

            var profit_val = getProfit(profit_value);
            var power_val = getPower(profit_value);
            var get_workhours = getWorkHours();
            var get_energy = getEnergy();
            // console.log(operator_val);


            var expected_val = parseFloat(profit_val);

            $(this).find('td.volumem3cal').html(ingredient_item_price);
            // console.log(volume_val);
            //End Selection and showing values of volume and pack...

            // Start of approval checked / unchecked

            var salePrices = $('input[name="used_qty"]').val();
            var salePrice = salePrices.replace(",", ".");
            var wastes = $('input[name="rate_of_waste"]').val();
            var waste = wastes.replace(",", ".");
            var used_quantity = parseFloat(salePrice)


            //calculate material cost
            material_total = (parseFloat(used_quantity) * parseFloat(ingredient_item_price) * parseFloat(waste));

            //end


            //installation time calculation


            //end


            //calculate expected profit

            profit_total = (parseFloat(expected_val) / ((3600 / (parseFloat(cycle_value) * parseFloat(mould_val))) * parseFloat(get_workhours)));

            //end

            //calculate production cost

            production_total = (((parseFloat(power_val) * parseFloat(get_energy)) / 3600) * parseFloat(cycle_value)) + (parseFloat(operator_val) / parseFloat(cycle_value)) + ((parseFloat(expected_val) / parseFloat(get_workhours)) / (3600 / parseFloat(cycle_value) * parseFloat(mould_val)));

            //

            //End of approval checked / unchecked

            ins_cost = (parseFloat(consumed_value) * parseFloat(operator_val));

            var all = material_total + production_total + profit_total + ins_cost + other_val;
            console.log(all);

            // _amount = ;
            sum_total += parseInt(material_total + production_total + profit_total + ins_cost + other_val);
            // row = $(this);
        });

// console.log(sum_total);
        subtotal = sum_total;

        if (quote_phase_counter >= 1) {
            $("#quote_phase option[value=1]").attr('selected', 'selected');
            $('input[name="quote_phase_val"]').val(1);
            // console.log(quote_phase_counter);
        } else if (quote_phase_counter == 0) {
            // $("#quote_phase").val($("#quote_phase option:first").val());
            $("#quote_phase option[value=0]").attr('selected', 'selected');
            $('input[name="quote_phase_val"]').val(0);
            // console.log(quote_phase_counter);
        }



        total = (total + subtotal);


        adjustment = parseFloat(adjustment);

        var discount_html = '-' + format_money(total_discount_calculated);
        $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));

        // // Append, format to html and display
        // $('.discount-total').html(discount_html);
        // $('.adjustment').html(format_money(adjustment));
        // $('.total').html(format_money(total));
        // $('.volumem3').html(format_money(sum_volume_m3));
        $('.total').html(format_money(subtotal));

        // Value for hidden fields...
        var sub_total = accounting.toFixed(subtotal, app.options.decimal_places);
        var to_tal_val = accounting.toFixed(total, app.options.decimal_places);

        $('input[name="subtotal_val"]').val(sub_total);
        // $('input[name="sum_volume_m3_val"]').val(sum_volume_m3);
        // $('input[name="discount_percent2_val"]').val(total_discount_calculated);
        // $('input[name="adjustment_val"]').val(adjustment);
        // $('input[name="total_val"]').val(to_tal_val);

        $(document).trigger('sales-total-calculated');
    }

//another calc


    function calculate_total3() {

        if ($('body').hasClass('no-calculate-total')) {
            return false;
        }

        var calculated_tax,

            row,
            _amount,
            _expamount,
            _pramount,
            _instamount,
            _sumammount = 0,
            sum_volume_m3 = 0,
            subtotal = 0,
            total = 0,
            quantity = 1,
            total_discount_calculated = 0,
            quote_phase_counter = 0,
            rows = $('.table.has-calculations tbody tr.item'),

            discount_total_type = $('.discount-total-type.selected');
        var operator_val = $('input[name="op_cost_per_sec"]').val();
        var other_val = $('input[name="other_cost"]').val();
        var consumed_value = $('input[name="consumed_time"]').val();


        $.each(rows, function() {

            var quant = $(this).find('[data-quantity]').val();
            quantity = quant.replace(",", ".");

            // if (quantity === '') {
            //     quantity = 1;
            //     $(this).find('[data-quantity]').val(1);
            // }
            var wastes = $(this).find('td.waste input').val()

            var waste = wastes.replace(",", ".");
            _amount = accounting.toFixed(waste * quantity, app.options.decimal_places);

            _amount = parseFloat(_amount);
            $(this).find('td.mt-cost input').val(_amount);


            var mould_cavity = $(this).find('td.mould-num input').val();
            var cycle_time = $(this).find('td.cycle-time input').val();

            var machine_name = $(this).find('td.def-machine input').val();
            var profit_val = getProfit(machine_name);
            var expected_val = parseFloat(profit_val);
            var get_workhours = getWorkHours();

            _expamount = (parseFloat(expected_val) / ((3600 / (parseFloat(cycle_time) * parseFloat(mould_cavity))) * parseFloat(get_workhours)));
            _expamount = parseInt(_expamount);
            $(this).find('td.exp-cost input').val(_expamount);

            var power_val = getPower(machine_name);
            var get_workhours = getWorkHours();
            var get_energy = getEnergy();

            _pramount = (((parseFloat(power_val) * parseFloat(get_energy)) / 3600) * parseFloat(cycle_time)) + (parseFloat(operator_val) / parseFloat(cycle_time)) + ((parseFloat(expected_val) / parseFloat(get_workhours)) / (3600 / parseFloat(cycle_time) * parseFloat(mould_cavity)));
            _pramount = parseInt(_pramount);
            $(this).find('td.pro-cost input').val(_pramount);

            _instamount =(parseFloat(consumed_value) * parseFloat(operator_val));
            _instamount =parseInt(_instamount);

            _sumammount += parseInt(_amount + _pramount + _expamount + _instamount);


            // $(this).find('td.amount').html(format_money(_amount, true));


            row = $(this);

        });
        subtotal = _sumammount;
        // if(quote_phase_counter >= 1){
        //     $("#quote_phase option[value=1]").attr('selected', 'selected');
        //     $('input[name="quote_phase_val"]').val(1);
        //     // console.log(quote_phase_counter);
        // }else if(quote_phase_counter == 0){
        //     // $("#quote_phase").val($("#quote_phase option:first").val());
        //     $("#quote_phase option[value=0]").attr('selected', 'selected');
        //     $('input[name="quote_phase_val"]').val(0);
        //     // console.log(quote_phase_counter);
        // }


        // total = (total + subtotal);

        // Discount by percent
        // if ((discount_percent !== '' && discount_percent != 0)) {
        //     total_discount_calculated = (total * discount_percent) / 100;
        // }
        //
        // // console.log(total_discount_calculated);
        // if(total_discount_calculated > 0){
        //     total = total - total_discount_calculated;
        // }
        // adjustment = parseFloat(adjustment);
        //
        // // Check if adjustment not empty
        // if (!isNaN(adjustment)) {
        //     total = total + adjustment;
        // }

        // var discount_html = '-' + format_money(total_discount_calculated);
        // $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));
        //
        // // Append, format to html and display
        // $('.discount-total').html(discount_html);
        // $('.adjustment').html(format_money(adjustment));
        // $('.subtotal').html(format_money(subtotal));
        // $('.volumem3').html(format_money(sum_volume_m3));
        $('.total').html(format_money(subtotal));
        $('input[name="subtotal_val"]').val(subtotal);
        // Value for hidden fields...
        // var sub_total = accounting.toFixed(subtotal, app.options.decimal_places);
        // var to_tal_val = accounting.toFixed(total, app.options.decimal_places);
        //
        // $('input[name="subtotal_val"]').val(sub_total);
        // $('input[name="sum_volume_m3_val"]').val(sum_volume_m3);
        // $('input[name="discount_percent2_val"]').val(total_discount_calculated);
        // $('input[name="adjustment_val"]').val(adjustment);
        // $('input[name="total_val"]').val(to_tal_val);

        $(document).trigger('sales-total-calculated');
    }


    //end


    //end
    // Deletes package items
    function delete_recipe_item(row, itemid) {
        $(row).parents('tr').addClass('animated fadeOut', function () {
            setTimeout(function () {
                $(row).parents('tr').remove();
                calculate_total3();
            }, 50);
        });
        // If is edit we need to add to input removed_items to track activity
        if ($('input[name="recipeId"]').length > 0) {
            $('#removed-items').append(hidden_input('removed_items[]', itemid));
        }
    }


    function material_cost_calc() {


        var pack_value = $('input[name="ingredient_id"]').val();
        var packId = parseInt(pack_value);
        // var profitId = parseInt(profit_value);

        var ingredient_item_price = getPrice(packId);
        var salePrices = $('input[name="used_qty"]').val();
        var salePrice = salePrices.replace(",", ".");
        var wastes = $('input[name="rate_of_waste"]').val();
        var waste = wastes.replace(",", ".");
        var used_quantity = parseFloat(salePrice)


        //calculate material cost
        var material_total = (parseFloat(used_quantity) * parseFloat(ingredient_item_price) * parseFloat(waste));
        material_total = parseInt(material_total);
        // console.log(material_total)
        // $('#mater_cost').val(material_total);
        $('input[name="mater_cost"]').val(material_total);

    }

    function expected_cost_cal() {


        var profit_value = $('input[name="default_machine"]').val();
        var cycle_value = $('input[name="cycle_time"]').val();

        var mould_val = $('input[name="mould_cavity"]').val();


        var profit_val = getProfit(profit_value);
        var get_workhours = getWorkHours();

        var expected_val = parseFloat(profit_val);


        var profit_total = (parseFloat(expected_val) / ((3600 / (parseFloat(cycle_value) * parseFloat(mould_val))) * parseFloat(get_workhours)));
        profit_total = parseInt(profit_total);
        $('input[name="exp_profit"]').val(profit_total);

    }


    function prod_cost_cal() {

        var operator_val = $('input[name="op_cost_per_sec"]').val();
        var pack_value = $('input[name="ingredient_id"]').val();
        var profit_value = $('input[name="default_machine"]').val();
        var cycle_value = $('input[name="cycle_time"]').val();

        var mould_val = $('input[name="mould_cavity"]').val();
        // console.log(quantity);

        // Filling each individual row volume_m3 valude based on the selection of Pack Capacity....
        var packId = parseInt(pack_value);
        // var profitId = parseInt(profit_value);

        var ingredient_item_price = getPrice(packId);

        console.log(pack_value);

        var profit_val = getProfit(profit_value);
        var power_val = getPower(profit_value);
        var get_workhours = getWorkHours();
        var get_energy = getEnergy();
        // console.log(operator_val);


        var expected_val = parseFloat(profit_val);

        $(this).find('td.volumem3cal').html(ingredient_item_price);
        // console.log(volume_val);
        //End Selection and showing values of volume and pack...

        // Start of approval checked / unchecked

        var salePrice = $('input[name="used_qty"]').val();
        var waste = $('input[name="rate_of_waste"]').val();
        var used_quantity = parseFloat(salePrice)


        var production_total = (((parseFloat(power_val) * parseFloat(get_energy)) / 3600) * parseFloat(cycle_value)) + (parseFloat(operator_val) / parseFloat(cycle_value)) + ((parseFloat(expected_val) / parseFloat(get_workhours)) / (3600 / parseFloat(cycle_value) * parseFloat(mould_val)));
        production_total = parseInt(production_total);
        $('input[name="pro_cost"]').val(production_total);

    }



    function packageDropdown(pack_name, mould_id, item_key) {
        // console.log(package.id+": "+pack_capacity);
        var select = '';
        var i = 0;

        var requestUrl = admin_url + 'products_recipe/get_mould_in_added/';
        var packsJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();

        // console.log(packsJsonData);

        select = '<select class="selectpicker display-block mould-item-table mould_items-'+item_key+'" data-width="100%" id="'+item_key+'" onchange="select_mould1(this.id)" name="newitems[' + item_key + '][mould_id]"  data-none-selected-text="select pack">';

        $.each(packsJsonData, function (key, value) {
            // console.log(key+' : '+value.packing_type);

            var selected = '';
            if (parseInt(mould_id)) {
                if (parseInt(mould_id) == parseInt(value.id)) {
                    selected = 'selected';
                }
            }

            select += '<option value="' + parseInt(value.id) + '" ' + selected + '>' + value.mould_name + '</option>';
        });

        select += '</select>';
        //console.log(select);

        return select;

    }

    function select_mould() {

        var pack_id = $('.mould_item').find(":selected").val();
        console.log(pack_id);

        requestGetJSON('products_recipe/get_mould_cavity/' + pack_id).done(function (response) {

            // console.log(pack_id);
            $('.main input[name="mould_cavity"]').val(response.mould_cavity);

        });


        requestGetJSON('products_recipe/get_default_machine_list/' + pack_id).done(function (response) {

            // console.log(response);
            if (response != null) {

                $('.main input[name="default_machine"]').val(response.name);
            } else {
                var exist = $('.main input[name="default_machine"]').val();
                if (exist != null || exist != "") {
                    $('.main input[name="default_machine"]').val('');
                }
            }


        });


    }
    function select_mould1(id,item_key) {

        var pack_id = $('.mould_items-'+id).find(":selected").val();
        // console.log(pack_id);

        requestGetJSON('products_recipe/get_mould_cavity/' + pack_id).done(function (response) {

            console.log(response.mould_cavity);
            $('.cavity-'+id).val(response.mould_cavity);

        });


        requestGetJSON('products_recipe/get_default_machine_list/' + pack_id).done(function (response) {

            // console.log(response);
            if (response != null) {

                $('.def-machine-'+id).val(response.name);
            } else {
                var exist = $('.def-machine-'+id).val();
                if (exist != null || exist != "") {
                    $('.def-machine-'+id).val('');
                }
            }


        });


    }
    function select_edited_mould(id) {

        var pack_id = $('.moulds_item-'+id).find(":selected").val();
        // console.log(pack_id);

        requestGetJSON('products_recipe/get_mould_cavity/' + pack_id).done(function (response) {

            console.log(response.mould_cavity);
            $('.cavit-'+id).val(response.mould_cavity);

        });


        requestGetJSON('products_recipe/get_default_machine_list/' + pack_id).done(function (response) {

            // console.log(response);
            if (response != null) {

                $('.mach-'+id).val(response.name);
            } else {
                var exist = $('.mach-'+id).val();
                if (exist != null || exist != "") {
                    $('.mach-'+id).val('');
                }
            }


        });


    }


    //for costing calculation
    function getPrice(pack_id) {
        var requestUrl = admin_url + 'products_recipe/get_ingredient_price_by_id/' + pack_id;
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.price;
    }

    function getOperatorCost(pack_id) {
        var requestUrl = admin_url + 'products_recipe/get_operator_cost_by_id/' + pack_id;
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.other_cost_per_sec;
    }

    function getOtherCost(pack_id) {
        var requestUrl = admin_url + 'products_recipe/get_other_cost_by_id/' + pack_id;
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.other_cost;
    }

    function getProfit(profit_id) {
        var requestUrl = admin_url + 'products_recipe/get_profit_expectation_by_id/' + profit_id;
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });

            return result;
        })();
        return packJsonData.profit_expectation;
    }

    function getPower(profit_id) {
        var requestUrl = admin_url + 'products_recipe/get_power_usage_by_id/' + profit_id;
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.power_usage;
    }

    function getWorkHours() {
        var requestUrl = admin_url + 'products_recipe/get_works_hours/';
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.capacity_hours;
    }

    function getEnergy() {
        var requestUrl = admin_url + 'products_recipe/get_energy_prices/';
        var packJsonData = (function () {
            var result;
            $.ajax({
                type: 'GET',
                url: requestUrl,
                dataType: 'json',
                async: false,
                success: function (data) {
                    result = data;
                }
            });
            return result;
        })();
        return packJsonData.energy_price;
    }

    //end

    function select_moulds() {

        var pack_id = $('.mould_items').find(":selected").val();

        var mould_id = $('.mould_items').parents('tr').find('td').eq(1).text();
        // console.log(mould_id)
        requestGetJSON('products_recipe/get_mould_cavity/' + pack_id).done(function (response) {

            // console.log(pack_id);
            $('input[name="mould_cavity"]').val(response.mould_cavity);

        });


        requestGetJSON('products_recipe/get_default_machine_list/' + pack_id).done(function (response) {

            // console.log(response.name);
            $('input[name="default_machine"]').val(response);

        });

    }

    function delete_item2(row, itemid) {
        $(row).parents('tr').addClass('animated fadeOut', function () {
            setTimeout(function () {
                $(row).parents('tr').remove();
                calculate_total2();
            }, 50);
        });
        // If is edit we need to add to input removed_items to track activity
        if ($('input[name="recipeId"]').length > 0) {
            $('#removed-items').append(hidden_input('removed_items[]', itemid));
        }
    }

    function inst_cost() {

        var operator_val = $('input[name="op_cost_per_sec"]').val();
        var consumed_value = $('input[name="consumed_time"]').val();
        var ins_cost = (parseFloat(consumed_value) * parseFloat(operator_val));
        $('input[name="ins_cost"]').val(ins_cost);
        // console.log(ins_cost);
    }

</script>
