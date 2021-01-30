<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
			                        <label for="product_code"><?php echo _l('product_code'); ?></label>
			                        <select name="product_code" id="product_code" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($product_codes as $product_code){ ?>
			                               <option value="<?php echo $product_code['id']; ?>"><?php echo $product_code['product_code']?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs product_codes">
			                            <?php
			                               foreach($product_codes as $product_code){?>
			                                <input type="hidden" class="filter product_code" name="product_code_<?php echo $product_code['id']?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
			                        <label for="transaction_from"><?php echo _l('transaction_from'); ?></label>
			                        <select name="transaction_from" id="transaction_from" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($warehouses as $warehouse){ ?>
			                               <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['warehouse_name'] ?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs from_warehouses">
			                            <?php
			                               foreach($warehouses as $warehouse){?>
			                                <input type="hidden" class="filter from_warehouse" name="from_warehouse_<?php echo $warehouse['id']?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
			                        <label for="transaction_to"><?php echo _l('transaction_to'); ?></label>
			                        <select name="transaction_to" id="transaction_to" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($warehouses as $warehouse){ ?>
			                               <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['warehouse_name'] ?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs to_warehouses">
			                            <?php
			                               foreach($warehouses as $warehouse){?>
			                                <input type="hidden" class="filter to_warehouse" name="to_warehouse_<?php echo $warehouse['id']?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
			                        <label for="transaction_notes"><?php echo _l('transaction_notes'); ?></label>
			                        <select name="transaction_notes" id="transaction_notes" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($note_list as $note){ ?>
			                               <option value="<?php echo $note; ?>"><?php echo $note ?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs note_list">
			                            <?php
			                               foreach($note_list as $note){?>
			                                <input type="hidden" class="filter note" name="note_<?php echo $note?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
			                        <label for="transaction_qty"><?php echo _l('transaction_qty'); ?></label>
			                        <select name="transaction_qty" id="transaction_qty" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($qty_list as $qty){ ?>
			                               <option value="<?php echo $qty; ?>"><?php echo $qty ?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs qty_list">
			                            <?php
			                               foreach($qty_list as $qty){?>
			                                <input type="hidden" class="filter qty" name="qty_<?php echo $qty?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
			                        <label for="description"><?php echo _l('description'); ?></label>
			                        <select name="description" id="description" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
			                           <?php foreach($des_list as $des){ ?>
			                               <option value="<?php echo $des; ?>"><?php echo $des ?></option>
			                            <?php } ?>
			                        </select>
			                        <div class="_filters _hidden_inputs des_list">
			                            <?php
			                               foreach($des_list as $des){?>
			                                <input type="hidden" class="filter des" name="des_<?php echo $des?>">
			                               <?php }?>
			                         </div>
			                    </div>
							</div>
						</div>

						<div id="date-range" class="mbot15">
	                        <div class="row">
	                           <div class="col-md-2">
                                  <?php echo render_date_input('report-from','report_sales_from_date'); ?>
	                           </div>
	                           <div class="col-md-2">
	                              <?php echo render_date_input('report-to','report_sales_to_date','',array('disabled'=> true)); ?>
	                           </div>
								<div class="col-md-2">
									<div class="form-group">
				                        <label for="created_user"><?php echo _l('created_user'); ?></label>
				                        <select name="created_user" id="created_user" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($staffs as $staff){ ?>
				                               <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'].' '.$staff['lastname'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs created_user">
				                            <?php
				                               foreach($staffs as $staff){?>
				                                <input type="hidden" class="filter created_staff" name="created_staff_<?php echo $staff['staffid']?>">
				                               <?php }?>
				                         </div>
				                    </div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
				                        <label for="updated_user"><?php echo _l('updated_user'); ?></label>
				                        <select name="updated_user" id="updated_user" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($staffs as $staff){ ?>
				                               <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'].' '.$staff['lastname'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs updated_user">
				                            <?php
				                               foreach($staffs as $staff){?>
				                                <input type="hidden" class="filter updated_staff" name="updated_staff_<?php echo $staff['staffid']?>">
				                               <?php }?>
				                         </div>
				                    </div>
								</div>                
	                        </div>
	                     </div>

						<div class="clearfix"></div>
            			<hr class="hr-panel-heading" />
            			<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('product_code'),
							_l('updated_at'),
							_l('transaction_from'),
							_l('transaction_to'),
							_l('transaction_notes'),
							_l('transaction_qty'),
							_l('description'),
							_l('created_user'),
							_l('updated_user'),
						),'transfer-report'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	var report_from = $('input[name="report-from"]');
 	var report_to = $('input[name="report-to"]');


	$(function(){

		$('#product_code').change(function(){
	        $('.filter.product_code').val('')
	        var product_code = $('#product_code').val();
	        if(product_code.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < product_code.length; i++)
	            {
	                dt_custom_view('product_code_'+ product_code[i], '.table-transfer-report','product_code_'+ product_code[i]); 
	            }
	    })

	    $('#transaction_from').change(function(){
	        $('.filter.from_warehouse').val('')
	        var from_warehouses = $('#transaction_from').val();
	        if(from_warehouses.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < from_warehouses.length; i++)
	            {
	                dt_custom_view('from_warehouse_'+ from_warehouses[i], '.table-transfer-report','from_warehouse_'+ from_warehouses[i]); 
	            }
	    })

	    $('#transaction_to').change(function(){
	        $('.filter.to_warehouse').val('')
	        var to_warehouses = $('#transaction_to').val();
	        if(to_warehouses.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < to_warehouses.length; i++)
	            {
	                dt_custom_view('to_warehouse_'+ to_warehouses[i], '.table-transfer-report','to_warehouse_'+ to_warehouses[i]); 
	            }
	    })

	    $('#transaction_notes').change(function(){
	        $('.filter.note').val('')
	        var notes = $('#transaction_notes').val();
	        if(notes.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < notes.length; i++)
	            {
	                dt_custom_view('note_'+ notes[i], '.table-transfer-report','note_'+ notes[i]); 
	            }
	    })

	    $('#transaction_qty').change(function(){
	        $('.filter.qty').val('')
	        var qtys = $('#transaction_qty').val();
	        if(qtys.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < qtys.length; i++)
	            {
	                dt_custom_view('qty_'+ qtys[i], '.table-transfer-report','qty_'+ qtys[i]); 
	            }
	    })

	    $('#description').change(function(){
	        $('.filter.des').val('')
	        var des_list = $('#description').val();
	        if(des_list.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < des_list.length; i++)
	            {
	                dt_custom_view('des_'+ des_list[i], '.table-transfer-report','des_'+ des_list[i]); 
	            }
	    })

	    $('#created_user').change(function(){
	        $('.filter.created_staff').val('')
	        var created_staffs = $('#created_user').val();
	        if(created_staffs.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < created_staffs.length; i++)
	            {
	                dt_custom_view('created_staff_'+ created_staffs[i], '.table-transfer-report','created_staff_'+ created_staffs[i]); 
	            }
	    })

	    $('#updated_user').change(function(){
	        $('.filter.updated_staff').val('')
	        var updated_staffs = $('#updated_user').val();
	        if(updated_staffs.length == 0)
	        {
	            dt_custom_view('','.table-transfer-report'); return false;

	        } else 
	            for(let i = 0; i < updated_staffs.length; i++)
	            {
	                dt_custom_view('updated_staff_'+ updated_staffs[i], '.table-transfer-report','updated_staff_'+ updated_staffs[i]); 
	            }
	    })

	    var fnServerParams = {
	    	"report_from": '[name="report-from"]',
   			"report_to": '[name="report-to"]',
   			"product_code" : '[name="product_code"]',
   			"from_warehouse" : '[name="from_warehouse"]',
   			"to_warehouse" : '[name="to_warehouse"]',
   			"note" : '[name="note"]',
   			"qty" : '[name="qty"]',
   			"des" : '[name="des"]',
   			"created_staff" : '[name="created_staff"]',
   			"updated_staff" : '[name="updated_staff"]',
	    };

	    $.each($('._hidden_inputs._filters.product_codes input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.from_warehouses input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.to_warehouses input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.note_list input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.qty_list input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.des_list input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.created_user input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.updated_user input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    
	    report_from.on('change', function() {
		    var val = $(this).val();
		    var report_to_val = report_to.val();
		    if (val != '') {
		       report_to.attr('disabled', false);
		       if (report_to_val != '') {
		         filter_by_date();
		       }
		     } else {
		       report_to.attr('disabled', true);
		     }
		});

	    report_to.on('change', function() {
		    var val = $(this).val();
		    if (val != '') {
		       filter_by_date();
		     }
		});

		initDataTable('.table-transfer-report', window.location.href,[],[],fnServerParams,[1, 'desc']);

		function filter_by_date() {
	     if ($.fn.DataTable.isDataTable('.table-transfer-report')) {
	       $('.table-transfer-report').DataTable().destroy();
	     }
	     initDataTable('.table-transfer-report', window.location.href,[],[],fnServerParams,[1, 'desc']);
	   }
	});
</script>