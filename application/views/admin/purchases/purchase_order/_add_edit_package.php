<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="row">
        <div class="col-md-4">
            <?php $this->load->view('admin/purchases/purchase_order/purchase_item_select'); ?>
        </div>
        <div class="col-md-8 text-right show_quantity_as_wrapper">
        </div>
    </div>
    <div class="table-responsive s_table">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
                <th></th>
                <th width="10%" align="center"><?php echo _l('product_name'); ?></th>
                <th width="10%" align="center"><?php echo _l('description'); ?></th>
                <th width="10%" align="center"><?php echo _l('ordered_qty'); ?></th>
                <th width="10%"  align="center"><?php echo _l('received_qty')?></th>
                <th width="10%"  align="center"><?php echo _l('unit') ?></th>
                <th width="10%"  align="center"><?php echo _l('price'); ?></th>
                <th width="10%"  align="center"><?php echo _l('volume_m3') ?></th>
                <th width="10%"  align="center"><?php echo _l('notes') ?></th>
                <th width="10%"  align="center"><?php echo _l('item_order') ?></th>
                <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr class="main">
                <td></td>
                <td>
                    <input type="hidden" name="item_id">
                    <input type="text" name="product_name" class="form-control" placeholder="<?php echo _l('product_name'); ?>">
                </td>

                <td>
                    <div class="checkbox checkbox-primary" style="margin-top: 8px;padding-left: 50%">
                        <input type="checkbox" id="description" name="description" >
                        <label for="description"></label>
                    </div>
                </td>

                <td>
                    <input type="number" name="ordered_qty" class="form-control" placeholder="<?php echo _l('ordered_qty'); ?>">
                </td>

                <td>
                    <input type="number" name="received_qty" class="form-control" placeholder="<?php echo _l('received_qty'); ?>">
                </td>

                <td>
                    <input type="text" name="unit" class="form-control" placeholder="<?php echo _l('unit'); ?>">
                    <input type="hidden" name="product_id" class="form-control" >
                </td>

                <td>
                    <input type="number" name="price" class="form-control" placeholder="<?php echo _l('price'); ?>">
                </td>

                 <td>
                    <input type="number" name="volume" class="form-control" placeholder="<?php echo _l('volume_m3'); ?>">
                </td>

                <td>
                    <input type="text" name="notes"  class="form-control"  placeholder="<?php echo _l('notes'); ?>">
                </td>
                
                <td>
                    <input type="number" name="item_order" class="form-control" placeholder="<?php echo _l('item_order'); ?>">
                </td>
                
                <td>
                    <?php
                    $new_item = 'undefined';
                    if(isset($estimate)){
                        $new_item = true;
                    }
                    ?>
                    <button type="button" onclick="add_item_to_table2('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                </td>
            </tr>

            <?php if (isset($estimate) || isset($add_items)) {
                $i               = 1;
                $items_indicator = 'newitems';
                if (isset($estimate)) {
                    $add_items       = $estimate->items;
                    $items_indicator = 'items';
                }

                foreach ($estimate as $item) {
                    $manual    = false;
                    $table_row = '<tr class="sortable item">';
                    $table_row .= '<td class="dragger">';


                    $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);


                    // order input
                    $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                    $table_row .= '</td>';
                    $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" rows="5">' . clear_textarea_breaks($item['product_name']) . '</textarea></td>';
                    $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][notes]" class="form-control" rows="5">' . clear_textarea_breaks($item['notes']) . '</textarea></td>';
                    $table_row .= render_custom_fields_items_table_in($item,$items_indicator.'['.$i.']');

                    $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][unit]" class="form-control" value="'.$item['unit'].'"><input type="hidden"  name="'.$items_indicator.'['.$i.'][product_id]" class="form-control input-transparent text-right" value="'.$item['product_id'].'"></td>';
                    $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][ordered_qty]" class="form-control" value="'.$item['ordered_qty'].'"></td>';
                    $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][received_qty]" class="form-control" value="'.$item['received_qty'].'"></td>';
                    $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][price]" class="form-control" value="'.$item['price'].'"></td>';
                    $table_row .= '<td><input type="number"  name="'.$items_indicator.'['.$i.'][volume]" class="form-control" value="'.$item['volume'].'"></td>';

                    $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_purchase_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                    $table_row .= '</tr>';
                    echo $table_row;
                    $i++;
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div id="removed-items"></div>
</div>
