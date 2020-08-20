<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
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
		initDataTable('.table-sale-report', window.location.href,[],[],[],[0, 'asc']);
	});
</script>