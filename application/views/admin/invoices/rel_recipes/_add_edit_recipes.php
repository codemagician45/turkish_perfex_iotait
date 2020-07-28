<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
  <h3><?php echo _l('rel_wo_items')?></h3>
   <div class="row">
      <div class="col-md-4">
          <?php //$this->load->view('admin/invoice_items/item_select'); ?>
          <?php $this->load->view('admin/invoices/rel_recipes/select_package'); ?>
      </div>
   </div>
   <div class="table-responsive s_table" id="item-section">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
                <th width="9%"><?php echo _l('product_name'); ?></th>
                <th width="4%"><?php echo _l('pre_produced'); ?></th>
                <th width="9%"><?php echo _l('used_qty'); ?></th>
                <th width="9%"><?php echo _l('rate_of_waste'); ?></th>
                <th width="10%"><?php echo _l('default_machine'); ?></th>
                <th width="10%"><?php echo _l('mould_id'); ?></th>
                <th width="10%"><?php echo _l('mould_cavity'); ?></th>
                <th width="9%"><?php echo _l('cycle_time'); ?></th>
                <th width="10%"><?php echo _l('material_cost'); ?></th>
                <th width="10%"><?php echo _l('production_cost'); ?></th>
                <th width="10%"><?php echo _l('expected_profit'); ?></th>
                <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
                <!-- <tr class="main">
                    <td>
                        <input type="text" name="product_name" class="form-control">
                        <input type="hidden" name="ingredient_item_id">
                    </td>
                    <td>
                        <div class="checkbox" style="margin-top: 8px;padding-left: 50%">
                            <input type="checkbox" id="pre_produced" name="pre_produced" >
                            <label for="pre_produced"></label>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="used_qty" class="form-control material" onkeyup="material_cost_calc()">
                    </td>
                    <td>
                        <input type="number" name="rate_of_waste"  class="form-control material" onkeyup="material_cost_calc()">
                    </td>
                    <td>
                        <input type="text" name="default_machine" readonly class="form-control">
                    </td>
                    <td>
                        <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="mould" data-fieldid="mould" name="mould" id="mould" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">
                                <option value=""></option>
                              <?php foreach ($moulds as $key => $mould) {?>
                                <option value="<?php echo $mould['id'];?>"><?php echo $mould['mould_name'];?></option>
                              <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="text" readonly name="mould_cavity" class="form-control">
                    </td>
                    <td>
                        <input type="number"  name="cycle_time" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="material_cost" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="production_cost" class="form-control">
                    </td>
                    <td>
                        <input type="number" readonly name="expected_profit" class="form-control">
                    </td>
                    <td>
                        <?php
                            $new_item = 'undefined';
                            if (isset($product_receipe_item)) {
                                $new_item = true;
                            }
                        ?>
                        <button type="button" onclick="add_item_to_table_product_recipe('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                    </td>
                </tr> -->
                
            </tbody>
        </table>
    </div>
   <!-- <div class="col-md-8 col-md-offset-4">
      <table class="table text-right">
         <tbody>
            <tr id="subtotal">
               <td><span class="bold"><?php echo _l('estimate_subtotal'); ?> :</span>
               </td>
               <td class="subtotal">
               </td>
            </tr>
            <tr id="discount_area">
               <td>
                  <div class="row">
                     <div class="col-md-7">
                        <span class="bold"><?php echo _l('estimate_discount'); ?></span>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group" id="discount-total">

                           <input type="number" value="<?php echo (isset($estimate) ? $estimate->discount_percent : 0); ?>" class="form-control pull-left input-discount-percent<?php if(isset($estimate) && !is_sale_discount($estimate,'percent') && is_sale_discount_applied($estimate)){echo ' hide';} ?>" min="0" max="100" name="discount_percent">

                           <input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php echo (isset($estimate) ? $estimate->discount_total : 0); ?>" class="form-control pull-left input-discount-fixed<?php if(!isset($estimate) || (isset($estimate) && !is_sale_discount($estimate,'fixed'))){echo ' hide';} ?>" min="0" name="discount_total">

                           <div class="input-group-addon">
                              <div class="dropdown">
                                 <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                 <span class="discount-total-type-selected">
                                  <?php if(!isset($estimate) || isset($estimate) && (is_sale_discount($estimate,'percent') || !is_sale_discount_applied($estimate))) {
                                    echo '%';
                                    } else {
                                    echo _l('discount_fixed_amount');
                                    }
                                    ?>
                                 </span>
                                 <span class="caret"></span>
                                 </a>
                                 <ul class="dropdown-menu" id="discount-total-type-dropdown" aria-labelledby="dropdown_menu_tax_total_type">
                                   <li>
                                    <a href="#" class="discount-total-type discount-type-percent<?php if(!isset($estimate) || (isset($estimate) && is_sale_discount($estimate,'percent')) || (isset($estimate) && !is_sale_discount_applied($estimate))){echo ' selected';} ?>">%</a>
                                  </li>
                                  <li>
                                    <a href="#" class="discount-total-type discount-type-fixed<?php if(isset($estimate) && is_sale_discount($estimate,'fixed')){echo ' selected';} ?>">
                                      <?php echo _l('discount_fixed_amount'); ?>
                                    </a>
                                  </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </td>
               <td class="discount-total"></td>
            </tr>
            <tr>
               <td>
                  <div class="row">
                     <div class="col-md-7">
                        <span class="bold"><?php echo _l('estimate_adjustment'); ?></span>
                     </div>
                     <div class="col-md-5">
                        <input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php if(isset($estimate)){echo $estimate->adjustment; } else { echo 0; } ?>" class="form-control pull-left" name="adjustment">
                     </div>
                  </div>
               </td>
               <td class="adjustment"></td>
            </tr>
            <tr>
               <td><span class="bold"><?php echo _l('estimate_total'); ?> :</span>
               </td>
               <td class="total">
               </td>
               <input type="hidden" name="subtotal">
               <input type="hidden" name="sum_volume_m3">
               <input type="hidden" name="discount_percent">
               <input type="hidden" name="discount_total">
               <input type="hidden" name="adjustment">
               <input type="hidden" name="total">
            </tr>
         </tbody>
      </table>
   </div> -->
   <div id="removed-items"></div>
</div>
