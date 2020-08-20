<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
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
		initDataTable('.table-warehouse-report', window.location.href,[],[],[],[0, 'asc']);
	});
</script>