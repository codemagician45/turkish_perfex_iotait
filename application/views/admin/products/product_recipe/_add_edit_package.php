<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div class="row" style="margin-top: 30px; margin-bottom: 20pxx">
        <div class="col-md-4" style="margin-top: 30px;">
            <?php $this->load->view('admin/products/product_recipe/select_package'); ?>


        </div>
        <div class="col-md-2" style="margin-top: 30px;" >
        </div>
        <div class="col-md-4" style="margin-top: 30px;margin-bottom: 20px">

           <span>Installation cost</span> <input type="number" readonly id="ins_cost" name="ins_cost" class="form-control" align="right" placeholder="<?php echo _l('Installation cost'); ?>">

        </div>

    </div>
    <div class="table-responsive s_table" id="item-section">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
<!--                <th></th>-->


                <th width="80%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true"
                                                data-toggle="tooltip"
                                                data-title="<?php echo _l('product name'); ?>"></i> <?php echo _l('product name'); ?>
                </th>
                <th width="5%" align="left"><?php echo _l('Pre-produced QTY'); ?></th>
                <th width="5%" align="left"><?php echo _l('Used QTY'); ?></th>
                <th width="5%" align="left"><?php echo _l('Rate of waste'); ?></th>
                <th width="10%" align="left"><?php echo _l('Default machine'); ?></th>
                <th width="10%" align="left"><?php echo _l('Mould ID'); ?></th>
                <th width="5%" align="left"><?php echo _l('Mould Cavity'); ?></th>
                <th width="5%" align="left"><?php echo _l('Cycle Time'); ?></th>
<!--                <th width="10%" align="left">--><?php //echo _l('Consumed Time'); ?><!--</th>-->
<!--                <th width="10%" align="left">--><?php //echo _l('Line Price'); ?><!--</th>-->
                <th width="10%" align="left"><?php echo _l('Material cost'); ?></th>
                <th width="10%" align="left"><?php echo _l('Production cost'); ?></th>
                <th width="10%" align="left"><?php echo _l('Expected profit'); ?></th>
<!--                <th width="10%" align="left">--><?php //echo _l('Installation cost'); ?><!--</th>-->



                <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr class="main">
<!--                <td></td>-->

<!--                <td>-->
<!--                    --><?php //$this->load->view('admin/list_of_packaging/select_package'); ?>
<!--                </td>-->
                <td>
                    <input type="hidden" name="item_id">

                    <textarea name="product_name" rows="4" class="form-control"
                              placeholder="<?php echo _l('product name'); ?>"></textarea>
                </td>
                <?php //echo render_custom_fields_items_table_add_edit_preview(); ?>
                <td>
                    <input type="checkbox" name="pre_produced" id="pre_produced" >
                    <label for="default_pack"> <?php echo _l('Pre-produced'); ?></label>
                    <input type="hidden" name="ingredient_id" class="form-control" placeholder="<?php echo _l(''); ?>">
                </td>

                <td>
                    <input type="text" name="used_qty" onchange="inst_cost()" onkeyup="material_cost_calc()" class="form-control" placeholder="<?php echo _l(''); ?>">
                </td>
                <td>
                    <input type="text" name="rate_of_waste" onkeyup="material_cost_calc()" class="form-control" placeholder="<?php echo _l(''); ?>">
                </td>
                <td>
                    <input type="text" name="default_machine" readonly class="form-control" value="" placeholder="<?php echo _l(''); ?>">
                </td>

                <td class="pack_drpdown">
                    <?php
                    //$default_tax = unserialize(get_option('default_tax'));
                    $select = '<select class="selectpicker display-block mould_item" data-width="100%" onchange="select_mould();" name="mould_id" data-none-selected-text="'._l('select pack').'">';
                    $select .= '<option value="">select pack</option>';
                    foreach($mould as $pack_list){

                        $selected = '';

                        $select .= '<option value="'.$pack_list['id'].'">'.$pack_list['mould_name'].' '.$selected.'</option>';
                    }
                    $select .= '</select>';
                    echo $select;
                    ?>
                </td>

                <td>
                    <input type="text" readonly name="mould_cavity" class="form-control" placeholder="<?php echo _l('mould cavity'); ?>" value="">
                </td>
                <td>
                    <input type="number"  name="cycle_time" class="form-control" onchange="prod_cost_cal()" onkeyup="expected_cost_cal()" placeholder="<?php echo _l('cycle time'); ?>">
                </td>
                <td>
                    <input type="number" readonly name="mater_cost" class="form-control" placeholder="<?php echo _l('Material cost'); ?>">
                </td>
                <td>
                    <input type="number" readonly name="pro_cost" class="form-control" placeholder="<?php echo _l('Production cost'); ?>">
                </td>
                <td>
                    <input type="number" readonly name="exp_profit" class="form-control" placeholder="<?php echo _l('Expected profit'); ?>">
                </td>
<!--                <td>-->
<!--                    <input type="number" name="ins_cost" class="form-control" placeholder="--><?php //echo _l('Installation cost'); ?><!--">-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input type="number" name="consumed_time" class="form-control" placeholder="--><?php //echo _l('consumed time'); ?><!--">-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input type="text" name="line_price" readonly class="form-control" placeholder="--><?php //echo _l('line price'); ?><!--">-->
<!--                </td>-->


                <td>
                    <?php
                    $new_item = 'undefined';
                    if (isset($estimate)) {
                        $new_item = true;
                    }
                    ?>
                    <button type="button"
                            onclick="add_item_to_table2('undefined','undefined',<?php echo $new_item; ?>); return false;"
                            class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                </td>
            </tr>
            <!--            --><?php //    print_r($estimate);
            //            exit(); ?>
            <?php if (isset($estimate) || isset($add_items)) {
                $i = 1;
                $items_indicator = 'newitems';
                if (isset($estimate)) {
                    $add_items = $estimate->items;
                    $items_indicator = 'items';
                }

                foreach ($estimate as $item) {
                    $manual = false;
                    $table_row = '<tr class="sortable item">';
//                    $table_row .= '<td class="dragger">';
//
//
//                    $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
//
//
//                    // order input
//                    $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
//                    $table_row .= '</td>';
                    $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" rows="5">' . clear_textarea_breaks($item['product_name']) . '</textarea></td>';

                    $table_row .= render_custom_fields_items_table_in($item, $items_indicator . '[' . $i . ']');
                    if ($item['pre_produced'] == 1) {

                        $table_row .= '<td><input type="checkbox" checked  name="' . $items_indicator . '[' . $i . '][pre_produced]"  value="'.$item['pre_produced'].'"><label for="default_pack"> Pre-produced</label></td>';
                    } else {
                        $table_row .= '<td><input type="checkbox"  name="' . $items_indicator . '[' . $i . '][pre_produced]"  value="'.$item['pre_produced'].'"><label for="default_pack"> Pre-produced</label></td>';

                    }

                    $table_row .= '<td><input type="text" data-quantity onchange="inst_cost()" onkeyup="calculate_total3();" class="form-control"  name="' . $items_indicator . '[' . $i . '][used_qty]"  value="'.$item['used_qty'].'"> <input type="hidden" class="form-control"  name="' . $items_indicator . '[' . $i . '][ingredient_id]"  value="'.$item['ingredient_id'].'"></td>';
                    $table_row .= '<td class="waste"><input type="text" onkeyup="calculate_total3();"  data-waste class="form-control"  name="' . $items_indicator . '[' . $i . '][rate_of_waste]"  value="'.$item['rate_of_waste'].'"></td>';
                    $table_row .= '<td class="def-machine"><input type="text" onclick="calculate_total3();" readonly class="form-control mach-'.$i.'"  name="' . $items_indicator . '[' . $i . '][default_machine]"  value="'.$item['default_machine'].'"></td>';
                    $table_row .= '<td>' . $this->misc_model->get_moulds_dropdown_template($i,$item['mould_cavity'] , $items_indicator,$mould) . '</td>';


                    $table_row .= '<td class="mould-num"><input type="text" readonly onclick="calculate_total3();" class="form-control cavit-'.$i.'"  name="' . $items_indicator . '[' . $i . '][mould_cavity]"  value="'.$item['mould_cavity'].'"></td>';
                    $table_row .= '<td class="cycle-time"><input type="number" onkeyup="calculate_total3();" class="form-control"  name="' . $items_indicator . '[' . $i . '][cycle_time]"  value="'.$item['cycle_time'].'"></td>';
                    $table_row .= '<td class="mt-cost"><input type="number" readonly class="form-control"  name="' . $items_indicator . '[' . $i . '][mater_cost]"  value="'.$item['mater_cost'].'"></td>';
                    $table_row .= '<td class="pro-cost"><input type="number" readonly class="form-control"  name="' . $items_indicator . '[' . $i . '][pro_cost]"  value="'.$item['pro_cost'].'"></td>';
                    $table_row .= '<td class="exp-cost"><input type="number" readonly class="form-control"  name="' . $items_indicator . '[' . $i . '][exp_profit]"  value="'.$item['exp_profit'].'"></td>';
//                    $table_row .= '<td><input type="number" class="form-control"  name="' . $items_indicator . '[' . $i . '][ins_cost]"  value="'.$item['ins_cost'].'"></td>';
//                    $table_row .= '<td><input type="text" readonly class="form-control"  name="' . $items_indicator . '[' . $i . '][line_price]"  value="'.$item['line_price'].'"></td>';

                    $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_recipe_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                    $table_row .= '</tr>';
                    echo $table_row;
                    $i++;
                }
            }
            ?>
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