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
                                <div class="col-md-3 pricing_categories">
                                    <?php echo render_select( 'product_category',$product_categories,array( 'order_no','name'), _l('product_category')); ?>
                                </div>
                            </div>
                            
                            <div class="_filters _hidden_inputs">
                                <?php
                                   foreach($product_categories as $category){?>
                                    <input type="hidden" class="filter" name="products_<?php echo $category['order_no']?>">
                                   <?php }?>
                                   <!-- <input type="hidden" class="filter" name="products_2" value="products_2"> -->
                             </div>
                        </div>
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

        $('#product_category').change(function(){
            
            $('.filter').val('')
            dt_custom_view('products_'+ $('#product_category option:selected').val(), '.table-product-recipe','products_'+ $('#product_category option:selected').val()); return false;
        })

        var ProductRecipe_ServerParams = {};
        $.each($('._hidden_inputs._filters input'),function(){
           ProductRecipe_ServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
         });
        console.log(ProductRecipe_ServerParams)
        initDataTable('.table-product-recipe', window.location.href,['undefined'], ['undefined'], ProductRecipe_ServerParams, []);
    });
</script>
</body>
</html>
