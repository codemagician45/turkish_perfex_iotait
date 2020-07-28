<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'estimate-form','class'=>'_transaction_form'));
			if(isset($estimate)){
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<?php $this->load->view('admin/estimates/estimate_template'); ?>
			</div>
			<?php echo form_close(); ?>
			<?php $this->load->view('admin/invoice_items/item'); ?>
		</div>
	</div>
</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		validate_estimate_form();
		// Init accountacy currency symbol
		init_currency();
		// Project ajax search
		init_ajax_project_search_by_customer_id();
		// Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
	});

	/*Quote Item part*/

	$("body").on('change', 'select[name="item_select"]', function () {
	    var itemid = $(this).selectpicker('val');
	    console.log('aaaa')
	    if (itemid != '') {
	        add_item_to_preview_quote(itemid);
	    }
	});

	function add_item_to_preview_quote(id) {
	    requestGetJSON('warehouses/get_item_by_id_with_currency/' + id).done(function(response) {
	        clear_item_preview_values();
	        $('input[name="product_name"]').val(response.product_name);
	        $('input[name="rel_product_id"]').val(response.id);
	        $('input[name="original_price"]').val(response.price);

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

	$('#pack_capacity').change(function(){
	  var pack_capacity = $(this).val();
	  requestGetJSON('warehouses/get_pack_by_capacity/' + pack_capacity).done(function(response) {
	    $('input[name="volume_m3"]').val(response.volume);
	  });
	})

	function add_item_to_table_quote(data, itemid, merge_invoice, bill_expense){
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
	        
	        table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][item_order]">';

	        // table_row += '</td>';

	        table_row += '<td class="bold description"><input type="text" name="newitems[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"></td>';
	        console.log('data',data.pack_capacity)
	        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="pack_capacity" data-fieldid="pack_capacity" name="newitems[' + item_key + '][pack_capacity]" id="newitems[' + item_key + '][pack_capacity]" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.pack_capacity+'</select></div></td>';

	        table_row += '<td><input type="number" data-quantity name="newitems[' + item_key + '][qty]" class="form-control" value="'+data.qty+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

	        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="unit" data-fieldid="unit" name="newitems[' + item_key + '][unit]" id="newitems[' + item_key + '][unit]" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.unit+'</select></div></td>';


	        table_row += '<td><input type="number" name="newitems[' + item_key + '][original_price]" readonly class="form-control" value="'+data.original_price+'"></td>';

	        table_row += '<td class="sale-price"><input type="number" name="newitems[' + item_key + '][sale_price]" class="form-control" value="'+data.sale_price+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

	        table_row += '<td><input type="number" name="newitems[' + item_key + '][volume_m3]" readonly class="form-control" value="'+data.volume_m3+'"></td>';

	        if(data.approval_need) {

	            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="newitems[' + item_key + '][approval_need]" disabled><label></label></div>';
	        }
	        else{

	            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox"  name="newitems[' + item_key + '][approval_need]" disabled><label></label></div></td>';
	        }

	        table_row += '<td><input type="text" name="newitems[' + item_key + '][notes]" readonly class="form-control" value="'+data.notes+'"></td>';

	        table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

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

	function get_item_preview_values_quote() {
	    var response = {};
	    response.item_id = $('input[name="item_id"]').val();
	    response.rel_product_id = $('input[name="rel_product_id"]').val();
	    response.product_name = $('input[name="product_name"]').val();
	    response.pack_capacity = $('select[name="pack_capacity"]').val();
	    response.qty = $('input[name="qty"]').val();
	    response.unitid = $('select[name="unit"]').val();
	    response.original_price = $('input[name="original_price"]').val();
	    response.sale_price = $('input[name="sale_price"]').val();
	    response.volume_m3 = $('input[name="volume_m3"]').val();
	    response.approval_need = $('input[name="approval_need"]').prop('checked');
	    response.notes = $('input[name="notes"]').val();
	    // response.item_order = $('input[name="item_order"]').val();
	    // console.log(response);
	    return response;
	}

	function clear_item_preview_values_quote(data){
	    var previewArea = $('.main');
	    previewArea.find('input[name="product_name"]').val('');
	    previewArea.find('input[name="item_id"]').val('');
	    previewArea.find('select[name="pack_capacity"]').selectpicker('val','');
	    previewArea.find('input[name="qty"]').val('');
	    previewArea.find('input[name="original_price"]').val('');
	    previewArea.find('select[name="unit"]').selectpicker('val','');

	    previewArea.find('input[name="sale_price"]').val('');
	    previewArea.find('input[name="volume_m3"]').val('');
	    previewArea.find('input[name="approval_need"]').val('');
	    previewArea.find('input[name="notes"]').val('');
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



	function calculate_total_quote()
	{
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
	        subtotal = 0,
	        total = 0,
	        quantity = 1,
	        total_discount_calculated = 0,
	        quote_phase_counter = 0,
	        rows = $('.table.has-calculations tbody tr.item'),
	        discount_area = $('#discount_area'),
	        adjustment = $('input[name="adjustment"]').val(),
	        discount_percent = $('input[name="discount_percent"]').val(),
	        discount_fixed = $('input[name="discount_total"]').val(),
	        discount_total_type = $('.discount-total-type.selected');

	    $('.tax-area').remove();

	    $.each(rows, function() {

	        quantity = $(this).find('[data-quantity]').val();
	        // if (quantity === '') {
	        //     quantity = 1;
	        //     $(this).find('[data-quantity]').val(1);
	        // }
	        _amount = accounting.toFixed($(this).find('td.sale-price input').val() * quantity, app.options.decimal_places);
	        _amount = parseFloat(_amount);

	        $(this).find('td.amount').html(format_money(_amount, true));
	        subtotal += _amount;
	        row = $(this);
	    });

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

	    total = (total + subtotal);

	    // Discount by percent
	    if ((discount_percent !== '' && discount_percent != 0)) {
	        total_discount_calculated = (total * discount_percent) / 100;
	    }

	    // console.log(total_discount_calculated);
	    if(total_discount_calculated > 0){
	        total = total - total_discount_calculated;
	    }
	    adjustment = parseFloat(adjustment);

	    // Check if adjustment not empty
	    if (!isNaN(adjustment)) {
	        total = total + adjustment;
	    }

	    var discount_html = '-' + format_money(total_discount_calculated);
	    $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));

	    // Append, format to html and display
	    $('.discount-total').html(discount_html);
	    $('.adjustment').html(format_money(adjustment));
	    $('.subtotal').html(format_money(subtotal));
	    $('.volumem3').html(format_money(sum_volume_m3));
	    $('.total').html(format_money(total));
	    
	    // Value for hidden fields...
	    var sub_total = accounting.toFixed(subtotal, app.options.decimal_places);
	    var to_tal_val = accounting.toFixed(total, app.options.decimal_places);
	    console.log('sub_total',sub_total)
	    $('input[name="subtotal"]').val(sub_total);
	    $('input[name="sum_volume_m3"]').val(sum_volume_m3);
	    $('input[name="discount_percent"]').val(discount_fixed);
	    $('input[name="discount_total"]').val(total_discount_calculated);
	    $('input[name="adjustment"]').val(adjustment);
	    $('input[name="total"]').val(to_tal_val);

	    $(document).trigger('sales-total-calculated');
	}

	
</script>
</body>
</html>
