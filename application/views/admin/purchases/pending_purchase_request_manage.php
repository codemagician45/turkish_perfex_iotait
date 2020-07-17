<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('purchases/manage_purchase_order'); ?>" class="btn btn-info pull-left" ><?php echo _l('new_purchase_order'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('purchase_phase_id'),
                            _l('approval'),
                            _l('bought_company_name'),
                            _l('notes'),
                            _l('created_user'),
                            _l('created_date_time'),
                            _l('last_updated_user'),
                        ),'pending_purchase_request'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-pending_purchase_request', window.location.href, [1], [1]);
    });

</script>
</body>
</html>