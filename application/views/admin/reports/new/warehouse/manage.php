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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stock_categories"><?php echo _l('stock_categories'); ?></label>
                                        <select name="stock_categories" id="stock_categories" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
                                           <?php foreach($stock_categories as $category){ ?>
                                               <option value="<?php echo $category['order_no']; ?>"><?php echo $category['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="_filters _hidden_inputs stock_categories">
                                            <?php
                                               foreach($stock_categories as $category){?>
                                                <input type="hidden" class="filter cate" name="category_<?php echo $category['order_no']?>">
                                               <?php }?>
                                         </div>
                                    </div>
                               </div>

                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stock_units"><?php echo _l('unit'); ?></label>
                                        <select name="stock_units" id="stock_units" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
                                           <?php foreach($stock_units as $unit){ ?>
                                               <option value="<?php echo $unit['unitid']; ?>"><?php echo $unit['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="_filters _hidden_inputs stock_units">
                                            <?php
                                               foreach($stock_units as $unit){?>
                                                <input type="hidden" class="filter unit" name="unit_<?php echo $unit['unitid']?>">
                                               <?php }?>
                                         </div>
                                    </div>
                               </div>

                               <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit"><?php echo _l('currency'); ?></label>
                                        <select name="currency" id="currency" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
                                           <?php foreach($currency as $curr){ ?>
                                               <option value="<?php echo $curr['id']; ?>"><?php echo $curr['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="_filters _hidden_inputs currency">
                                            <?php
                                               foreach($currency as $curr){?>
                                                <input type="hidden" class="filter curr" name="curr_<?php echo $curr['id']?>">
                                               <?php }?>
                                         </div>
                                    </div>
                               </div>
                            </div>
                            
                            <div id="date-range" class="mbot15">
                                <div class="row">
                                   <div class="col-md-3">
                                      <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                                      <div class="input-group date">
                                         <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                         <div class="input-group-addon">
                                            <i class="fa fa-calendar calendar-icon"></i>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="col-md-3">
                                      <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                                      <div class="input-group date">
                                         <input type="text" class="form-control datepicker" disabled="disabled" id="report-to" name="report-to">
                                         <div class="input-group-addon">
                                            <i class="fa fa-calendar calendar-icon"></i>
                                         </div>
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
							_l('product_name'),
							_l('unit'),
							_l('category'),
							_l('price'),
							_l('currency'),
							_l('stock_level'),
						),'warehouse-report'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
        // console.log($('#stock_categories').val())
		$('#stock_categories').change(function(){

            $('.filter.cate').val('')
            var stock_categories = $('#stock_categories').val();
            if(stock_categories.length == 0)
            {
                dt_custom_view('','.table-warehouse-report'); return false;

            } else 
                for(let i = 0; i < stock_categories.length; i++)
                {
                    dt_custom_view('category_'+ stock_categories[i], '.table-warehouse-report','category_'+ stock_categories[i]); 
                }

            
        })

        $('#stock_units').change(function(){
            $('.filter.unit').val('')
            var stock_units = $('#stock_units').val();
            if(stock_units.length == 0)
            {
                dt_custom_view('','.table-warehouse-report'); return false;

            } else 
                for(let i = 0; i < stock_units.length; i++)
                {
                    dt_custom_view('unit_'+ stock_units[i], '.table-warehouse-report','unit_'+ stock_units[i]); 
                }
        })

        $('#currency').change(function(){
            $('.filter.curr').val('')
            var currency = $('#currency').val();
            if(currency.length == 0)
            {
                dt_custom_view('','.table-warehouse-report'); return false;

            } else 
                for(let i = 0; i < currency.length; i++)
                {
                    dt_custom_view('curr_'+ currency[i], '.table-warehouse-report','curr_'+ currency[i]); 
                }
            
        })

        var fnServerParams = {
            "report_from": '[name="report-from"]',
            "report_to": '[name="report-to"]',
          };

        $.each($('._hidden_inputs._filters.stock_categories input'),function(){
           fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
         });

        $.each($('._hidden_inputs._filters.stock_units input'),function(){
           fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
         });

        $.each($('._hidden_inputs._filters.currency input'),function(){
           fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
         });

        var report_from = $('input[name="report-from"]');
        var report_to = $('input[name="report-to"]');

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

        function filter_by_date() {
         if ($.fn.DataTable.isDataTable('.table-warehouse-report')) {
           $('.table-warehouse-report').DataTable().destroy();
         }
         initDataTable('.table-warehouse-report', window.location.href,[],[],fnServerParams,[0, 'asc']);
       }

		initDataTable('.table-warehouse-report', window.location.href,[],[],fnServerParams,[0, 'asc']);
	});
</script>