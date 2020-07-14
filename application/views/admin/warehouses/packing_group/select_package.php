<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="form-group mbot25 items-wrapper select-placeholder">
    <div class="<?php echo 'input-group input-group-select'; ?>">
        <div class="items-select-wrapper">
            <select name="item_select" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?> data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                <option value=""></option>
                <?php foreach($items as $group_id=>$_items){ ?>
                    <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                        <?php foreach($_items as $item){ ?>
                            <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['product_code'],0,200)).'...'; ?>"><?php echo $item['product_name']; ?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
        </div>

        <!-- Applied Css to display none this bottom section... -->
        <?php if(has_permission('items','','create')){ ?>
            <div class="input-group-addon" style="display: none;">
                <a href="#" data-toggle="modal" data-target="#sales_item_modal">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
