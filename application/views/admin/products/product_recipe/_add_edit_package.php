<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="row">
        <div class="col-md-6">
            <?php $this->load->view('admin/products/product_recipe/select_package'); ?>
        </div>
        <div class="col-md-4" style="margin-top: 30px;margin-bottom: 20px">
           <label><?php echo _('installation_cost')?></label><input type="number" readonly id="ins_cost" name="ins_cost" class="form-control" align="right" placeholder="<?php echo _l('installation_cost'); ?>">
        </div>
    </div>
    <div class="table-responsive s_table" id="item-section">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
                <th  align="left"><?php echo _l('ingredient_item_id'); ?></th>
                <th  align="left"><?php echo _l('product_name'); ?></th>
                <th  align="left"><?php echo _l('pre_produced_qty'); ?></th>
                <th  align="left"><?php echo _l('used_qty'); ?></th>
                <th  align="left"><?php echo _l('rate_of_waste'); ?></th>
                <th  align="left"><?php echo _l('default_machine'); ?></th>
                <th  align="left"><?php echo _l('mould_id'); ?></th>
                <th  align="left"><?php echo _l('mould_cavity'); ?></th>
                <th  align="left"><?php echo _l('cycle_time'); ?></th>
                <!-- <th width="9%" align="left"><?php echo _l('material_cost'); ?></th>
                <th width="9%" align="left"><?php echo _l('production_cost'); ?></th>
                <th width="9%" align="left"><?php echo _l('expected_profit'); ?></th> -->
                <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
                <td>

                </td>
                <td>
                    <input type="text" name="product_name" class="form-control" placeholder="<?php echo _l('product_name'); ?>">
                </td>
                <td>
                    <div class="checkbox" style="margin-top: 8px;padding-left: 50%">
                        <input type="checkbox" id="ingredient_item_id" name="ingredient_item_id" >
                        <label for="ingredient_item_id"></label>
                    </div>
                </td>
                <td>
                    <input type="text" name="used_qty" class="form-control">
                </td>
                <td>
                    <input type="text" name="rate_of_waste" onkeyup="material_cost_calc()" class="form-control">
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-8 col-md-offset-4">
        <table class="table text-right">
            <tbody>
                <td><span class="bold"><?php echo _l('estimate_total'); ?> :</span>
                </td>
                <td id="total" class="total">
                </td>
                <input type="hidden" id="subtotal_val" name="subtotal_val">
            </tr>
            </tbody>
        </table>
    </div>
    <div id="removed-items"></div>

<script>


</script>