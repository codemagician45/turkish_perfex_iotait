<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('product_code'),
                            _l('product_photo'),
                            _l('product_name'),
                            _l('pack_capacity'),
                            _l('packing_type'),
                            _l('volume_m3'),
                            _l('price'),
                        ),'product-recipe'); ?>
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
        initDataTable('.table-product-recipe', window.location.href);
    });

</script>
</body>
</html>
