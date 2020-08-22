<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
				                        <label for="staffs"><?php echo _l('salesperson'); ?></label>
				                        <select name="staffs" id="staffs" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($staffs as $staff){ ?>
				                               <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'].' '.$staff['lastname'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs staffs">
				                            <?php
				                               foreach($staffs as $staff){?>
				                                <input type="hidden" class="filter staff" name="staff_<?php echo $staff['staffid']?>">
				                               <?php }?>
				                         </div>
				                    </div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
				                        <label for="customers"><?php echo _l('client'); ?></label>
				                        <select name="customers" id="customers" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($customers as $customer){ ?>
				                               <option value="<?php echo $customer['userid']; ?>"><?php echo $customer['company'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs customers">
				                            <?php
				                               foreach($customers as $customer){?>
				                                <input type="hidden" class="filter customer" name="customer_<?php echo $customer['userid']?>">
				                               <?php }?>
				                         </div>
				                    </div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
				                        <label for="pricing_categories"><?php echo _l('pricing_categories'); ?></label>
				                        <select name="pricing_categories" id="pricing_categories" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($pricing_categories as $pcate){ ?>
				                               <option value="<?php echo $pcate['order_no']; ?>"><?php echo $pcate['name'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs pricing_categories">
				                            <?php
				                               foreach($pricing_categories as $pcate){?>
				                                <input type="hidden" class="filter pcate" name="pcate_<?php echo $pcate['order_no']?>">
				                               <?php }?>
				                         </div>
				                    </div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
				                        <label for="sale_phases"><?php echo _l('sale_phases'); ?></label>
				                        <select name="sale_phases" id="sale_phases" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
				                           <?php foreach($sale_phases as $s_phase){ ?>
				                               <option value="<?php echo $s_phase['order_no']; ?>"><?php echo $s_phase['phase'] ?></option>
				                            <?php } ?>
				                        </select>
				                        <div class="_filters _hidden_inputs sale_phases">
				                            <?php
				                               foreach($sale_phases as $s_phase){?>
				                                <input type="hidden" class="filter s_phase" name="s_phase_<?php echo $s_phase['order_no']?>">
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
							_l('sale_order_dt_table_heading_number'),
							_l('salesperson'),
							_l('client'),
							_l('pricing_category'),
							_l('current_phase'),
							_l('sold_price'),
						),'sale-report'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){

		$('#staffs').change(function(){
	        $('.filter.staff').val('')
	        var staffs = $('#staffs').val();
	        if(staffs.length == 0)
	        {
	            dt_custom_view('','.table-sale-report'); return false;

	        } else 
	            for(let i = 0; i < staffs.length; i++)
	            {
	                dt_custom_view('staff_'+ staffs[i], '.table-sale-report','staff_'+ staffs[i]); 
	            }
	    })

	    $('#customers').change(function(){
	        $('.filter.customer').val('')
	        var customers = $('#customers').val();
	        if(customers.length == 0)
	        {
	            dt_custom_view('','.table-sale-report'); return false;

	        } else 
	            for(let i = 0; i < customers.length; i++)
	            {
	                dt_custom_view('customer_'+ customers[i], '.table-sale-report','customer_'+ customers[i]); 
	            }
	    })

	    $('#pricing_categories').change(function(){
	        $('.filter.pcate').val('')
	        var pricing_categories = $('#pricing_categories').val();
	        if(pricing_categories.length == 0)
	        {
	            dt_custom_view('','.table-sale-report'); return false;

	        } else 
	            for(let i = 0; i < pricing_categories.length; i++)
	            {
	                dt_custom_view('pcate_'+ pricing_categories[i], '.table-sale-report','pcate_'+ pricing_categories[i]); 
	            }
	    })

	    $('#sale_phases').change(function(){
	        $('.filter.s_phase').val('')
	        var sale_phases = $('#sale_phases').val();
	        if(sale_phases.length == 0)
	        {
	            dt_custom_view('','.table-sale-report'); return false;

	        } else 
	            for(let i = 0; i < sale_phases.length; i++)
	            {
	                dt_custom_view('s_phase_'+ sale_phases[i], '.table-sale-report','s_phase_'+ sale_phases[i]); 
	            }
	    })

	    var fnServerParams = {};
	    $.each($('._hidden_inputs._filters.staffs input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.customers input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.pricing_categories input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });
	    $.each($('._hidden_inputs._filters.sale_phases input'),function(){
	       fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
	     });

		initDataTable('.table-sale-report', window.location.href,[],[],fnServerParams,[0, 'asc']);
	});
</script>