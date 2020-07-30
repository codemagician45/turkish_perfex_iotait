<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content" style="padding-bottom: 0">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'invoice-form','class'=>'_transaction_form invoice-form'));
			if(isset($invoice)){
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<?php $this->load->view('admin/invoices/invoice_template'); ?>
			</div>
			<?php echo form_close(); ?>
			<?php $this->load->view('admin/invoice_items/item'); ?>
		</div>

		<div class="content" style="padding-top: 0">
			<div class="row">
				<div class="col-md-12">
					<div class="panel_s">
						<?php
							$this->load->view('admin/invoices/rel_plans/calendar.php'); ?>
					</div>
				</div>	
			</div>
		</div>
	</div>

</div>
<?php init_tail(); ?>
<script>
	$(function(){
		validate_invoice_form();
	    // Init accountacy currency symbol
	    init_currency();
	    // Project ajax search
	    init_ajax_project_search_by_customer_id();
	    // Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
	});

	$("body").on('change', 'select[name="item_select"]', function () {
	    var itemid = $(this).selectpicker('val');
	    if (itemid != '') {
	        add_item_to_preview_quote(itemid);
	    }
	});

	$('#pack_capacity').change(function(){
	  var pack_capacity = $(this).val();
	  requestGetJSON('warehouses/get_pack_by_capacity/' + pack_capacity).done(function(response) {
	    $('input[name="volume_m3"]').val(response.volume);
	  });
	})

	function add_item_to_table_wo(data, itemid, merge_invoice, bill_expense){
	    // If not custom data passed get from the preview
	    data = typeof(data) == 'undefined' || data == 'undefined' ? get_item_preview_values_quote() : data;
	    if (data.item_id === "" && data.product_name === "") { return; }

	    requestGetJSON('warehouses/get_pack_by_capacity').done(function(res) {
	      // console.log('pack_capacity', res)
	      var pack_capacity = '<option></option>';
	      res.forEach(e => {
	          if(e.pack_capacity == data.pack_capacity)
	              pack_capacity += '<option value="'+e.pack_capacity+'" selected>'+e.pack_capacity+'</option>';
	          else
	              pack_capacity += '<option value="'+e.pack_capacity+'">'+e.pack_capacity+'</option>';
	      })
	      data.pack_capacity = pack_capacity;

	      requestGetJSON('warehouses/get_units').done(function(res) {
	        // console.log('units', res)
	        var unit = '<option></option>';
	        res.forEach(e => {
	            if(e.unitid == data.unitid)
	                unit += '<option value="'+e.unitid+'" selected>'+e.name+'</option>';
	            else
	                unit += '<option value="'+e.unitid+'">'+e.name+'</option>';
	        })
	        data.unit = unit;

	        var table_row = '';
	        var item_key = $("body").find('tbody .item').length + 1;

	        table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';
	        // table_row += '<td class="dragger">';

	        $("body").append('<div class="dt-loader"></div>');
	        var regex = /<br[^>]*>/gi;
	        
	        table_row += '<input type="hidden" class="order" name="wo_items[newitems][' + item_key + '][item_order]">';

	        // table_row += '</td>';

	        table_row += '<td class="bold description"><input type="text" name="wo_items[newitems][' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"><input type="hidden" name="wo_items[newitems][' + item_key + '][rel_product_id]" value="'+data.rel_product_id+'"></td>';
	        console.log('data',data.pack_capacity)
	        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="pack_capacity" data-fieldid="pack_capacity" name="wo_items[newitems][' + item_key + '][pack_capacity]" id="wo_items[newitems][' + item_key + '][pack_capacity]" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.pack_capacity+'</select></div></td>';

	        table_row += '<td><input type="number" data-quantity name="wo_items[newitems][' + item_key + '][qty]" class="form-control" value="'+data.qty+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

	        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="unit" data-fieldid="unit" name="wo_items[newitems][' + item_key + '][unit]" id="wo_items[newitems][' + item_key + '][unit]" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.unit+'</select></div></td>';


	        // table_row += '<td><input type="number" name="wo_items[newitems][' + item_key + '][original_price]" readonly class="form-control" value="'+data.original_price+'"></td>';

	        // table_row += '<td class="sale-price"><input type="number" name="wo_items[newitems][' + item_key + '][sale_price]" class="form-control" value="'+data.sale_price+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

	        table_row += '<td><input type="number" name="wo_items[newitems][' + item_key + '][volume_m3]" readonly class="form-control volume_m3" value="'+data.volume_m3+'"></td>';

	        if(data.approval_need) {

	            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="wo_items[newitems][' + item_key + '][approval_need]" disabled><label></label></div>';
	        }
	        else{

	            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox"  name="wo_items[newitems][' + item_key + '][approval_need]" disabled><label></label></div></td>';
	        }

	        table_row += '<td><input type="text" name="wo_items[newitems][' + item_key + '][notes]" readonly class="form-control" value="'+data.notes+'"></td>';

	        table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_wo_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

	        table_row += '</tr>';

	        $('table.items tbody').append(table_row);

	        $(document).trigger({
	            type: "item-added-to-table",
	            data: data,
	            row: table_row
	        });

	        setTimeout(function() {
	            calculate_total_quote();
	        }, 15);

	        if ($('#item_select').hasClass('ajax-search') && $('#item_select').selectpicker('val') !== '') {
	            $('#item_select').prepend('<option></option>');
	        }

	        init_selectpicker();
	        init_datepicker();
	        init_color_pickers();
	        clear_item_preview_values_quote();
	        reorder_items();
	        $('body').find('#items-warning').remove();
	        $("body").find('.dt-loader').remove();
	        $('#item_select').selectpicker('val', '');

	      })

	      

	    })
	}

	$('.pack_capacity').change(function(){
	  var pack_capacity = $(this).val();
	  var currentV = $(this).parents('tr').children()[7].firstChild;
	  console.log(currentV)
	  requestGetJSON('warehouses/get_pack_by_capacity/' + pack_capacity).done(function(response) {
	    currentV.value = response.volume;
	  });
	})

	// $('input[name="discount_percent"]').keyup(function(){
	//   calculate_total_quote()
	// })

	// $('input[name="discount_total"]').keyup(function(){
	//   calculate_total_quote()
	// })

	$('input[name="discount_percent"]').change(function(){
	  calculate_total_quote()
	})

	$('input[name="discount_total"]').change(function(){
	  calculate_total_quote()
	})

	function delete_wo_item(row, itemid) {
	    $(row).parents('tr').addClass('animated fadeOut', function() {
	        setTimeout(function() {
	            $(row).parents('tr').remove();
	            calculate_total_quote();
	        }, 50);
	    });
	    // If is edit we need to add to input removed_items to track activity
	    if ($('input[name="isedit"]').length > 0) {
	        $('#wo_removed-items').append(hidden_input('wo_removed-items[]', itemid));
	    }
	}

	

	$(document).ready(function(){
	  calculate_total_quote();
	})


	$("body").on('change', 'select[name="item_select_recipe"]', function () {
		var itemid = $(this).selectpicker('val');
	    add_recipes_from_product_recipe(itemid);
	});

	function add_recipes_from_product_recipe(id)
	{
		requestGetJSON('products/get_recipes_by_product/' + id).done(function(response) {
			i = 0
            response.forEach(e => {
            	i += 1;
            	add_item_to_table_plan_recipe(e,i)
            })
        });
	}

	function add_item_to_table_plan_recipe(data,i) {
		$('.recipe').find('tbody').empty();
        requestGetJSON('products/get_moulds_by_ajax').done(function(res) {
            var option = '<option></option>';
            res.forEach(e => {
                if(e.id == data.mould)
                    option += '<option value="'+e.id+'" selected>'+e.mould_name+'</option>';
                else
                    option += '<option value="'+e.id+'">'+e.mould_name+'</option>';
            })
            data.option = option;
            var table_row = '';
            // var item_key = $("body").find('.recipe .item').length + 1;
            var item_key = i;
            table_row += '<tr>';

            table_row += '<input type="hidden" name="plan_items[' + item_key + '][item_id]" value = "' + data.id + '"><td class="bold description"><input type="text" name="plan_items[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"><input type="hidden" name="plan_items[' + item_key + '][ingredient_item_id]" class="form-control" value="' + data.ingredient_item_id + '"></td>';

            // if(data.pre_produced == 1) {

            //     table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox" checked  name="plan_items[' + item_key + '][pre_produced]"  value="1" ><label for="pre_produced"></label></div></td>';
            // }
            // else if(data.pre_produced == 0) {

            //     table_row += '<td><div class="checkbox" style="margin-top: 8px; padding-left: 50%"><input type="checkbox"  name="plan_items[' + item_key + '][pre_produced]"  value="0" ><label for="pre_produced"></label></div></td>';
            // }

            table_row += '<td><input type="number" name="plan_items[' + item_key + '][used_qty]" class="form-control qty" onkeyup = "material_cost_calc_for_added(this)" value="' + data.used_qty + '"></td>';

            if(data.pre_produced == 1) {

                table_row += '<td><input type="number" name="plan_items[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value=""></td>';
                // table_row += '<td><input type="text" name="plan_items[' + item_key + '][default_machine]" readonly class="form-control" value=""></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="plan_items[' + item_key + '][mould]" id="plan_items[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98"></select></div></td>';
                table_row += '<td><input type="text" readonly name="plan_items[' + item_key + '][mould_cavity]" class="form-control mould_cavity" value=""></td>';
                table_row += '<td><input type="number" name="plan_items[' + item_key + '][cycle_time]" class="form-control cycle_time" value=""></td>';
            }
            else if(data.pre_produced == 0) {

                table_row += '<td><input type="number" name="plan_items[' + item_key + '][rate_of_waste]" class="form-control" onkeyup = "material_cost_calc_for_added(this)" value="' + data.rate_of_waste + '"></td>';
                // table_row += '<td><input type="text" name="plan_items[' + item_key + '][default_machine]" readonly class="form-control" value="' + data.default_machine + '"></td>';
                table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="mould" data-fieldid="mould" name="plan_items[' + item_key + '][mould]" id="plan_items[' + item_key + '][mould]" class="selectpicker form-control mouldid" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.option+'</select></div></td>';
                table_row += '<td><input type="text" readonly name="plan_items[' + item_key + '][mould_cavity]" class="form-control mould_cavity" value="' + data.mould_cavity + '"></td>';
                table_row += '<td><input type="number" name="plan_items[' + item_key + '][cycle_time]" class="form-control cycle_time" value="' + data.cycle_time + '"></td>';
            }

            // table_row += '<td><input type="number" readonly name="items[' + item_key + '][material_cost]" class="form-control" value="' + data.material_cost + '"></td>';

            // if(data.pre_produced == 1) {

            //     table_row += '<td><input type="number" readonly name="items[' + item_key + '][production_cost]" class="form-control" value=""></td>';

            //     table_row += '<td><input type="number" readonly name="items[' + item_key + '][expected_profit]" class="form-control" value=""></td>';
            // }
            // else if(data.pre_produced == 0) {

            //     table_row += '<td><input type="number" readonly name="items[' + item_key + '][production_cost]" class="form-control" value="' + data.production_cost + '"></td>';

            //     table_row += '<td><input type="number" readonly name="items[' + item_key + '][expected_profit]" class="form-control" value="' + data.expected_profit + '"></td>';
            // }

            // if(data.pre_produced == 1) {
            //     var subtotalVal = Number(data.used_qty) * Number(data.material_cost)
            //     table_row += '<input type="hidden" name="items[' + item_key + '][subtotal]" class="subtotal" value="'+ subtotalVal.toFixed(2) +'">';
            // }
            // else if(data.pre_produced == 0) {
            //     var subtotalVal = Number(data.material_cost) + Number(data.production_cost) + Number(data.expected_profit);
            //     table_row += '<input type="hidden" name="items[' + item_key + '][subtotal]" class="subtotal" value="' + subtotalVal.toFixed(2) + '">';
            // }


            table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_plan_recipe_item(this,' + data.id + '); return false;"><i class="fa fa-trash"></i></a></td>';

            // table_row += '<td><a href="#" class="btn btn-info" onclick="set_plan(this,'+ data.id +'); return false;"><i class="fa fa-calendar-plus-o"></i></a></td>';

            table_row += '</tr>';

            $('.recipe tbody').append(table_row);

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
            reorder_items();

            $('body').find('#items-warning').remove();
            $("body").find('.dt-loader').remove();
            $('#item_select').selectpicker('val', '');


        });
        
        // var subtotal = 0;
        // var total = $('#total').text();
        // if(total == '') total = 0;

        // if(data.pre_produced == 1)
        // {
        //     subtotal = Number(data.used_qty) * Number(data.material_cost);

        // } else {
        //     subtotal = Number(data.material_cost) + Number(data.production_cost) + Number(data.expected_profit);
        // }

        // total  = Number(total) +  subtotal;
        // // console.log('total',total)
        // $('#total').text(total.toFixed(2))
        // $('#total_value').val(total.toFixed(2));
    }

     function delete_plan_recipe_item(row, itemid) {
        $(row).parents('tr').addClass('animated fadeOut', function() {
            setTimeout(function() {
                $(row).parents('tr').remove();
            }, 50);
        });
        $('#recipe_removed-items').append(hidden_input('recipe_removed-items[]', itemid));
    }




	/* Calendar*/

    $(function(){
		if(get_url_param('eventid')) {
			view_event(get_url_param('eventid'));
		}
	});

	function set_plan(row,recipe_id)
	{
		var qty = $(row).parents('tr').find('.qty').val();
		var mould_cavity = $(row).parents('tr').find('.mould_cavity').val();
		var cycle_time = $(row).parents('tr').find('.cycle_time').val();
		var production_time = ((qty/mould_cavity)*cycle_time/60/24).toFixed(6);
		console.log($(row).parents('tr').children()[0].value)
		$('input[name="recipe_id"]').val($(row).parents('tr').children()[0].value)		
		$('input[name="production_calculate"]').val(production_time);
		$('#newEventModal').modal('show');
	}

</script>
</body>
</html>
