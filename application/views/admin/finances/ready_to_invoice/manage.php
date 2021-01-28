<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			$this->load->view('admin/finances/ready_to_invoice/list_template');
			?>
		</div>
	</div>
</div>
<script>var hidden_columns = [2,6,7,8];</script>
<?php init_tail(); ?>
<script>
	$(function(){
		initDataTable('.table-installation_work_order', window.location.href);
	});
</script>
</body>
</html>
