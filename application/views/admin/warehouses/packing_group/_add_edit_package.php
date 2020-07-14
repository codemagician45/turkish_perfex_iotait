<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="row">
        <div class="col-md-4">
            <?php $this->load->view('admin/warehouses/packing_group/select_package'); ?>
        </div>
        <div class="col-md-8 text-right show_quantity_as_wrapper">
        </div>
    </div>
    <div class="table-responsive s_table">
        <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
            <thead>
            <tr>
                <th></th>
                <th width="30%" align="left">
                    <i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('product_name'); ?>"></i>
                    <?php echo _l('product_name'); ?>
                </th>
                <th width="40%" align="left"><?php echo _l('product_code'); ?></th>
                <th width="30%" align="left"><?php echo _l('default'); ?></th>
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
                    <input type="text" name="product_code" class="form-control" placeholder="<?php echo _l('product_code'); ?>">
                </td>
                <td>
                    <div class="checkbox checkbox-primary" style="margin-top: 8px">
                        <input type="checkbox" id="default_pack" name="default_pack" >
                       <label for="default_pack"><?php echo _l('default_pack'); ?></label>
                    </div>
                    <input type="hidden" name="product_id" class="form-control" placeholder="<?php echo _l('unit'); ?>">
                </td>


                <td>
                    <!-- <?php
                    $new_item = 'undefined';
                    if (isset($estimate)) {
                        $new_item = true;
                    }
                    ?> -->
                    <button type="button"
                            onclick="add_item_to_table2('undefined','undefined',<?php echo $new_item; ?>); return false;"
                            class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                </td>
            </tr>
            <!-- <?php if (isset($estimate) || isset($add_items)) {
                $i = 1;
                $items_indicator = 'newitems';
                if (isset($estimate)) {
                    $add_items = $estimate->items;
                    $items_indicator = 'items';
                }

                foreach ($estimate as $item) {
                    $manual = false;
                    $table_row = '<tr class="sortable item">';
                    $table_row .= '<td class="dragger">';


                    $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);


                    // order input
                    $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                    $table_row .= '</td>';
                    $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][product_name]" class="form-control" rows="5">' . clear_textarea_breaks($item['product_name']) . '</textarea></td>';
                    $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][product_code]" class="form-control" rows="5">' . clear_textarea_breaks($item['product_code']) . '</textarea></td>';
                    $table_row .= render_custom_fields_items_table_in($item, $items_indicator . '[' . $i . ']');
                    if ($item['default_pack'] == 1) {

                        $table_row .= '<td><input type="checkbox" checked  name="' . $items_indicator . '[' . $i . '][default_pack]"  value="'.$item['default_pack'].'"><label for="default_pack"> Default pack</label><input type="hidden"  name="' . $items_indicator . '[' . $i . '][product_id]" class="form-control input-transparent text-right" value="' . $item['product_id'] . '"></td>';
                    } else {
                        $table_row .= '<td><input type="checkbox"  name="' . $items_indicator . '[' . $i . '][default_pack]"  value="'.$item['default_pack'].'"><label for="default_pack"> Default pack</label><input type="hidden"  name="' . $items_indicator . '[' . $i . '][product_id]" class="form-control input-transparent text-right" value="' . $item['product_id'] . '"></td>';

                    }


                    $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_package_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                    $table_row .= '</tr>';
                    echo $table_row;
                    $i++;
                }
            }
            ?> -->
            </tbody>
        </table>
    </div>

    <div id="removed-items"></div>
</div>
