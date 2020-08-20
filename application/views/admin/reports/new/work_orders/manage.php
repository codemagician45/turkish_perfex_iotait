<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php render_datatable(array(
							_l('wo_heading_number'),
							_l('salesperson'),
							_l('client'),
							_l('created_at'),
							_l('current_phase'),
							_l('req_shipping_date'),
							_l('shipping_type'),
						),'work-order-report'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		initDataTable('.table-work-order-report', window.location.href,[],[],[],[0, 'asc']);
	});
</script>