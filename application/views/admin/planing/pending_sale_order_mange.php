<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="_buttons">
							<!-- <a href="<?php echo admin_url('estimates/estimate'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_transfer'); ?></a> -->
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
								_l('estimate_dt_table_heading_number'),
							   _l('sale_phase'),

							   array(
							      'name'=>_l('estimate_dt_table_heading_client'),
							      'th_attrs'=>array('class'=> (isset($client) ? 'not_visible' : ''))
							   ),

							   _l('quote'),
							   _l('shipping_type'),
							   _l('req_shiping_date'),
							   _l('general_notes'),
							   _l('total_price'),
							   _l('created_user'),
							   _l('date_created'),
							   _l('updated_by'),
						),'p_sale_order'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		initDataTable('.table-p_sale_order', window.location.href);
	});
</script>
