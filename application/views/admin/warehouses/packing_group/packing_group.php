<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(),array('id'=>'packing_list')); ?>
            <div class="col-md-12 transfers">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel_s">
                            <?php $this->load->view('admin/warehouses/packing_group/_add_edit_package'); ?>
                        </div>
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                    </div>
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>

</script>

</body>
</html>
