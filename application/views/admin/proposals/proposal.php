<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content accounting-template proposal">
      <div class="row">
         <?php
            if(isset($proposal)){
             echo form_hidden('isedit',$proposal->id);
            }
            $rel_type = '';
            $rel_id = '';
            if(isset($proposal) || ($this->input->get('rel_id') && $this->input->get('rel_type'))){
             if($this->input->get('rel_id')){
               $rel_id = $this->input->get('rel_id');
               $rel_type = $this->input->get('rel_type');
             } else {
               $rel_id = $proposal->rel_id;
               $rel_type = $proposal->rel_type;
             }
            }
            ?>
         <?php echo form_open($this->uri->uri_string(),array('id'=>'proposal-form','class'=>'_transaction_form proposal-form')); ?>
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row">
                     <?php if(isset($proposal)){ ?>
                     <div class="col-md-12">
                        <?php echo format_proposal_status($proposal->status); ?>
                     </div>
                     <div class="clearfix"></div>
                     <hr />
                     <?php } ?>
                     <div class="col-md-6 border-right">
                        <?php $value = (isset($proposal) ? $proposal->subject : ''); ?>
                        <?php $attrs = (isset($proposal) ? array() : array('autofocus'=>true)); ?>
                        <?php echo render_input('subject','proposal_subject',$value,'text',$attrs); ?>

                        <div class="form-group select-placeholder">
                           <label class="control-label"><?php echo _l('quote_phase'); ?></label>
                           <select name="quote_phase_id" id="quote_phase" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('nothing_selected'); ?>">
                              <option></option>
                              <?php foreach($quote_phases as $quote_phase){ echo $quote_phase->id;?>

                                  <option value="<?= $quote_phase['id'];?>" <?php if((isset($proposal) &&  $proposal->quote_phase_id == $quote_phase['id'])){echo 'selected';} ?>><?= $quote_phase['phase']; ?></option>

                              <?php }?>
                              
                           </select>
                        </div>

                        <div class="form-group select-placeholder">
                           <label for="rel_type" class="control-label"><?php echo _l('proposal_related'); ?></label>
                           <select name="rel_type" id="rel_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                              <option value=""></option>
                             <!--  <option value="lead" <?php if((isset($proposal) && $proposal->rel_type == 'lead') || $this->input->get('rel_type')){if($rel_type == 'lead'){echo 'selected';}} ?>><?php echo _l('proposal_for_lead'); ?></option> -->
                              <option value="customer" <?php if((isset($proposal) &&  $proposal->rel_type == 'customer') || $this->input->get('rel_type')){if($rel_type == 'customer'){echo 'selected';}} ?>><?php echo _l('proposal_for_customer'); ?></option>
                           </select>
                        </div>
                        <div class="form-group select-placeholder<?php if($rel_id == ''){echo ' hide';} ?> " id="rel_id_wrapper">
                           <label for="rel_id"><span class="rel_id_label"></span></label>
                           <div id="rel_id_select">
                              <select name="rel_id" id="rel_id" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                              <?php if($rel_id != '' && $rel_type != ''){
                                 $rel_data = get_relation_data($rel_type,$rel_id);
                                 $rel_val = get_relation_values($rel_data,$rel_type);
                                    echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                 } ?>
                              </select>
                           </div>
                        </div>

                        <div class="form-group select-placeholder">
                           <label class="control-label"><?php echo _l('pricing_category'); ?></label>
                           <select name="pricing_category_id" id="pricing_category" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('nothing_selected'); ?>">
                              <option></option>
                              <?php foreach($pricing_categories as $pricing_category){ echo $pricing_category->id;?>

                                  <option value="<?= $pricing_category['id'];?>" <?php if((isset($proposal) &&  $proposal->pricing_category_id == $pricing_category['id'])){echo 'selected';} ?>><?= $pricing_category['name']; ?></option>

                              <?php }?>
                              
                           </select>
                        </div>
                        <!-- <div class="row">
                          <div class="col-md-6">
                              <?php $value = (isset($proposal) ? _d($proposal->date) : _d(date('Y-m-d'))) ?>
                              <?php echo render_date_input('date','proposal_date',$value); ?>
                          </div>
                          <div class="col-md-6">
                            <?php
                        $value = '';
                        if(isset($proposal)){
                          $value = _d($proposal->open_till);
                        } else {
                          if(get_option('proposal_due_after') != 0){
                              $value = _d(date('Y-m-d',strtotime('+'.get_option('proposal_due_after').' DAY',strtotime(date('Y-m-d')))));
                          }
                        }
                        echo render_date_input('open_till','proposal_open_till',$value); ?>
                          </div>
                        </div> -->
                        <?php
                           $selected = '';
                           $currency_attr = array('data-show-subtext'=>true);
                           foreach($currencies as $currency){
                            if($currency['isdefault'] == 1){
                              $currency_attr['data-base'] = $currency['id'];
                            }
                            if(isset($proposal)){
                              if($currency['id'] == $proposal->currency){
                                $selected = $currency['id'];
                              }
                              if($proposal->rel_type == 'customer'){
                                $currency_attr['disabled'] = true;
                              }
                            } else {
                              if($rel_type == 'customer'){
                                $customer_currency = $this->clients_model->get_customer_default_currency($rel_id);
                                if($customer_currency != 0){
                                  $selected = $customer_currency;
                                } else {
                                  if($currency['isdefault'] == 1){
                                    $selected = $currency['id'];
                                  }
                                }
                                $currency_attr['disabled'] = true;
                              } else {
                               if($currency['isdefault'] == 1){
                                $selected = $currency['id'];
                              }
                            }
                           }
                           }
                           $currency_attr = apply_filters_deprecated('proposal_currency_disabled', [$currency_attr], '2.3.0', 'proposal_currency_attributes');
                           $currency_attr = hooks()->apply_filters('proposal_currency_attributes', $currency_attr);
                           ?>
                           <div class="row">
                             <div class="col-md-6">
                              <?php
                              echo render_select('currency', $currencies, array('id','name','symbol'), 'proposal_currency', $selected, $currency_attr);
                              ?>
                             </div>
                             <!-- <div class="col-md-6">
                               <div class="form-group select-placeholder">
                                 <label for="discount_type" class="control-label"><?php echo _l('discount_type'); ?></label>
                                 <select name="discount_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                  <option value="" selected><?php echo _l('no_discount'); ?></option>
                                  <option value="before_tax" <?php
                                  if(isset($estimate)){ if($estimate->discount_type == 'before_tax'){ echo 'selected'; }}?>><?php echo _l('discount_type_before_tax'); ?></option>
                                  <option value="after_tax" <?php if(isset($estimate)){if($estimate->discount_type == 'after_tax'){echo 'selected';}} ?>><?php echo _l('discount_type_after_tax'); ?></option>
                                </select>
                              </div>
                            </div> -->
                           </div>
                        <?php $fc_rel_id = (isset($proposal) ? $proposal->id : false); ?>
                        <?php echo render_custom_fields('proposal',$fc_rel_id); ?>
                         <div class="form-group no-mbot">
                           <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                           <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($proposal) ? prep_tags_input(get_tags_in($proposal->id,'proposal')) : ''); ?>" data-role="tagsinput">
                        </div>
                        <!-- <div class="form-group mtop10 no-mbot">
                            <p><?php echo _l('proposal_allow_comments'); ?></p>
                            <div class="onoffswitch">
                              <input type="checkbox" id="allow_comments" class="onoffswitch-checkbox" <?php if((isset($proposal) && $proposal->allow_comments == 1) || !isset($proposal)){echo 'checked';}; ?> value="on" name="allow_comments">
                              <label class="onoffswitch-label" for="allow_comments" data-toggle="tooltip" title="<?php echo _l('proposal_allow_comments_help'); ?>"></label>
                            </div>
                          </div> -->
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group select-placeholder">
                                 <label for="status" class="control-label"><?php echo _l('proposal_status'); ?></label>
                                 <?php
                                    $disabled = '';
                                    if(isset($proposal)){
                                     if($proposal->estimate_id != NULL || $proposal->invoice_id != NULL){
                                       $disabled = 'disabled';
                                     }
                                    }
                                    ?>
                                 <select name="status" class="selectpicker" data-width="100%" <?php echo $disabled; ?> data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach($statuses as $status){ ?>
                                    <option value="<?php echo $status; ?>" <?php if((isset($proposal) && $proposal->status == $status) || (!isset($proposal) && $status == 0)){echo 'selected';} ?>><?php echo format_proposal_status($status,'',false); ?></option>
                                    <?php } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <?php
                                 $i = 0;
                                 $selected = '';
                                 foreach($staff as $member){
                                  if(isset($proposal)){
                                    if($proposal->assigned == $member['staffid']) {
                                      $selected = $member['staffid'];
                                    }
                                  }
                                  $i++;
                                 }
                                 echo render_select('assigned',$staff,array('staffid',array('firstname','lastname')),'proposal_assigned',$selected);
                                 ?>
                           </div>
                        </div>
                        <?php $value = (isset($proposal) ? $proposal->proposal_to : ''); ?>
                        <?php echo render_input('proposal_to','proposal_to',$value); ?>
                        <?php $value = (isset($proposal) ? $proposal->address : ''); ?>
                        <?php echo render_textarea('address','proposal_address',$value); ?>
                        <div class="row">
                           <div class="col-md-6">
                              <?php $value = (isset($proposal) ? $proposal->city : ''); ?>
                              <?php echo render_input('city','billing_city',$value); ?>
                           </div>
                           <div class="col-md-6">
                              <?php $value = (isset($proposal) ? $proposal->state : ''); ?>
                              <?php echo render_input('state','billing_state',$value); ?>
                           </div>
                           <div class="col-md-6">
                              <?php $countries = get_all_countries(); ?>
                              <?php $selected = (isset($proposal) ? $proposal->country : ''); ?>
                              <?php echo render_select('country',$countries,array('country_id',array('short_name'),'iso2'),'billing_country',$selected); ?>
                           </div>
                           <div class="col-md-6">
                              <?php $value = (isset($proposal) ? $proposal->zip : ''); ?>
                              <?php echo render_input('zip','billing_zip',$value); ?>
                           </div>
                           <div class="col-md-6">
                              <?php $value = (isset($proposal) ? $proposal->email : ''); ?>
                              <?php echo render_input('email','proposal_email',$value); ?>
                           </div>
                           <div class="col-md-6">
                              <?php $value = (isset($proposal) ? $proposal->phone : ''); ?>
                              <?php echo render_input('phone','proposal_phone',$value); ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="btn-bottom-toolbar bottom-transaction text-right">
                  <p class="no-mbot pull-left mtop5 btn-toolbar-notice"><?php echo _l('include_proposal_items_merge_field_help','<b>{proposal_items}</b>'); ?></p>
                    <button type="button" class="btn btn-info mleft10 proposal-form-submit save-and-send transaction-submit">
                        <?php echo _l('save_and_send'); ?>
                    </button>
                    <button class="btn btn-info mleft5 proposal-form-submit transaction-submit" type="button">
                      <?php echo _l('submit'); ?>
                    </button>
               </div>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="panel_s">
               <?php $this->load->view('admin/proposals/_add_edit_items'); ?>
            </div>
         </div>
         <?php echo form_close(); ?>
         <?php $this->load->view('admin/invoice_items/item'); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>
<?php init_tail(); ?>
<script>
   var _rel_id = $('#rel_id'),
   _rel_type = $('#rel_type'),
   _rel_id_wrapper = $('#rel_id_wrapper'),
   data = {};

   $(function(){
    init_currency();
    // Maybe items ajax search
    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
    validate_proposal_form();
    $('body').on('change','#rel_id', function() {
     if($(this).val() != ''){
      $.get(admin_url + 'proposals/get_relation_data_values/' + $(this).val() + '/' + _rel_type.val(), function(response) {
        $('input[name="proposal_to"]').val(response.to);
        $('textarea[name="address"]').val(response.address);
        $('input[name="email"]').val(response.email);
        $('input[name="phone"]').val(response.phone);
        $('input[name="city"]').val(response.city);
        $('input[name="state"]').val(response.state);
        $('input[name="zip"]').val(response.zip);
        $('select[name="country"]').selectpicker('val',response.country);
        var currency_selector = $('#currency');
        if(_rel_type.val() == 'customer'){
          if(typeof(currency_selector.attr('multi-currency')) == 'undefined'){
            currency_selector.attr('disabled',true);
          }

         } else {
           currency_selector.attr('disabled',false);
        }
        var proposal_to_wrapper = $('[app-field-wrapper="proposal_to"]');
        if(response.is_using_company == false && !empty(response.company)) {
          proposal_to_wrapper.find('#use_company_name').remove();
          proposal_to_wrapper.find('#use_company_help').remove();
          proposal_to_wrapper.append('<div id="use_company_help" class="hide">'+response.company+'</div>');
          proposal_to_wrapper.find('label')
          .prepend("<a href=\"#\" id=\"use_company_name\" data-toggle=\"tooltip\" data-title=\"<?php echo _l('use_company_name_instead'); ?>\" onclick='document.getElementById(\"proposal_to\").value = document.getElementById(\"use_company_help\").innerHTML.trim(); this.remove();'><i class=\"fa fa-building-o\"></i></a> ");
        } else {
          proposal_to_wrapper.find('label #use_company_name').remove();
          proposal_to_wrapper.find('label #use_company_help').remove();
        }
       /* Check if customer default currency is passed */
       if(response.currency){
         currency_selector.selectpicker('val',response.currency);
       } else {
        /* Revert back to base currency */
        currency_selector.selectpicker('val',currency_selector.data('base'));
      }
      currency_selector.selectpicker('refresh');
      currency_selector.change();
    }, 'json');
    }
   });
    $('.rel_id_label').html(_rel_type.find('option:selected').text());
    _rel_type.on('change', function() {
      var clonedSelect = _rel_id.html('').clone();
      _rel_id.selectpicker('destroy').remove();
      _rel_id = clonedSelect;
      $('#rel_id_select').append(clonedSelect);
      proposal_rel_id_select();
      if($(this).val() != ''){
        _rel_id_wrapper.removeClass('hide');
      } else {
        _rel_id_wrapper.addClass('hide');
      }
      $('.rel_id_label').html(_rel_type.find('option:selected').text());
    });
    proposal_rel_id_select();
    <?php if(!isset($proposal) && $rel_id != ''){ ?>
      _rel_id.change();
      <?php } ?>
    });
   function proposal_rel_id_select(){
      var serverData = {};
      serverData.rel_id = _rel_id.val();
      data.type = _rel_type.val();
      init_ajax_search(_rel_type.val(),_rel_id,serverData);
   }
   function validate_proposal_form(){
      appValidateForm($('#proposal-form'), {
        subject : 'required',
        proposal_to : 'required',
        rel_type: 'required',
        rel_id : 'required',
        date : 'required',
        email: {
         email:true,
         required:true
       },
       currency : 'required',
     });
   }


/*Quote Item part*/

$("body").on('change', 'select[name="item_select"]', function () {
    var itemid = $(this).selectpicker('val');
    if (itemid != '') {
        add_item_to_preview_quote(itemid);
    }
});

function add_item_to_preview_quote(id) {
    requestGetJSON('warehouses/get_item_by_id_with_currency/' + id).done(function(response) {
        clear_item_preview_values();
        $('input[name="product_name"]').val(response.product_name);
        $('input[name="rel_product_id"]').val(response.id);
        $('input[name="original_price"]').val(response.price);

        init_selectpicker();
        init_color_pickers();
        init_datepicker();

        $(document).trigger({
            type: "item-added-to-preview",
            item: response,
            item_type: 'item',
        });
    });
}

$('#pack_capacity').change(function(){
  var pack_capacity = $(this).val();
  requestGetJSON('warehouses/get_pack_by_capacity/' + pack_capacity).done(function(response) {
    $('input[name="volume_m3"]').val(response.volume);
  });
})

function add_item_to_table_quote(data, itemid, merge_invoice, bill_expense){
    // If not custom data passed get from the preview
    data = typeof(data) == 'undefined' || data == 'undefined' ? get_item_preview_values_quote() : data;
    if (data.item_id === "" && data.product_name === "") { return; }

    requestGetJSON('warehouses/get_pack_by_capacity').done(function(res) {
      // console.log('pack_capacity', res)
      var pack_capacity = '<option></option>';
      res.forEach(e => {
          if(e.pack_capacity == data.pack_capacity)
              pack_capacity += '<option value="'+e.pack_capacity+'" selected>'+e.pack_capacity+'</option>';
          else
              pack_capacity += '<option value="'+e.pack_capacity+'">'+e.pack_capacity+'</option>';
      })
      data.pack_capacity = pack_capacity;

      requestGetJSON('warehouses/get_units').done(function(res) {
        // console.log('units', res)
        var unit = '<option></option>';
        res.forEach(e => {
            if(e.unitid == data.unitid)
                unit += '<option value="'+e.unitid+'" selected>'+e.name+'</option>';
            else
                unit += '<option value="'+e.unitid+'">'+e.name+'</option>';
        })
        data.unit = unit;

        var table_row = '';
        var item_key = $("body").find('tbody .item').length + 1;

        table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';
        // table_row += '<td class="dragger">';

        $("body").append('<div class="dt-loader"></div>');
        var regex = /<br[^>]*>/gi;
        
        table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][item_order]">';

        // table_row += '</td>';

        table_row += '<td class="bold description"><input type="text" name="newitems[' + item_key + '][product_name]" class="form-control" value="'+data.product_name+'"></td>';
        console.log('data',data.pack_capacity)
        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="pack_capacity" data-fieldid="pack_capacity" name="newitems[' + item_key + '][pack_capacity]" id="newitems[' + item_key + '][pack_capacity]" class="selectpicker form-control pack_capacity" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.pack_capacity+'</select></div></td>';

        table_row += '<td><input type="number" data-quantity name="newitems[' + item_key + '][qty]" class="form-control" value="'+data.qty+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

        table_row += '<td><div class="dropdown bootstrap-select form-control bs3" style="width: 100%;"><select data-fieldto="unit" data-fieldid="unit" name="newitems[' + item_key + '][unit]" id="newitems[' + item_key + '][unit]" class="selectpicker form-control" data-width="100%" data-none-selected-text="None" data-live-search="true" tabindex="-98">'+data.unit+'</select></div></td>';


        table_row += '<td><input type="number" name="newitems[' + item_key + '][original_price]" readonly class="form-control" value="'+data.original_price+'"></td>';

        table_row += '<td class="sale-price"><input type="number" name="newitems[' + item_key + '][sale_price]" class="form-control" value="'+data.sale_price+'" onkeyup="calculate_total_quote();" onchange="calculate_total_quote();"></td>';

        table_row += '<td><input type="number" name="newitems[' + item_key + '][volume_m3]" readonly class="form-control" value="'+data.volume_m3+'"></td>';

        if(data.approval_need) {

            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox" checked  name="newitems[' + item_key + '][approval_need]" disabled><label></label></div>';
        }
        else{

            table_row += '<td><div class="checkbox" style="margin-top: 8px;padding-left: 50%"><input type="checkbox"  name="newitems[' + item_key + '][approval_need]" disabled><label></label></div></td>';
        }

        table_row += '<td><input type="text" name="newitems[' + item_key + '][notes]" readonly class="form-control" value="'+data.notes+'"></td>';

        table_row += '<td><a href="#" class="btn btn-danger pull-right" onclick="delete_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';

        table_row += '</tr>';

        $('table.items tbody').append(table_row);

        $(document).trigger({
            type: "item-added-to-table",
            data: data,
            row: table_row
        });

        setTimeout(function() {
            calculate_total_quote();
        }, 15);

        if ($('#item_select').hasClass('ajax-search') && $('#item_select').selectpicker('val') !== '') {
            $('#item_select').prepend('<option></option>');
        }

        init_selectpicker();
        init_datepicker();
        init_color_pickers();
        clear_item_preview_values_quote();
        reorder_items();
        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');

      })

      

    })
}

function get_item_preview_values_quote() {
    var response = {};
    response.item_id = $('input[name="item_id"]').val();
    response.rel_product_id = $('input[name="rel_product_id"]').val();
    response.product_name = $('input[name="product_name"]').val();
    response.pack_capacity = $('select[name="pack_capacity"]').val();
    response.qty = $('input[name="qty"]').val();
    response.unitid = $('select[name="unit"]').val();
    response.original_price = $('input[name="original_price"]').val();
    response.sale_price = $('input[name="sale_price"]').val();
    response.volume_m3 = $('input[name="volume_m3"]').val();
    response.approval_need = $('input[name="approval_need"]').prop('checked');
    response.notes = $('input[name="notes"]').val();
    // response.item_order = $('input[name="item_order"]').val();
    // console.log(response);
    return response;
}

function clear_item_preview_values_quote(data){
    var previewArea = $('.main');
    previewArea.find('input[name="product_name"]').val('');
    previewArea.find('input[name="item_id"]').val('');
    previewArea.find('select[name="pack_capacity"]').selectpicker('val','');
    previewArea.find('input[name="qty"]').val('');
    previewArea.find('input[name="original_price"]').val('');
    previewArea.find('select[name="unit"]').selectpicker('val','');

    previewArea.find('input[name="sale_price"]').val('');
    previewArea.find('input[name="volume_m3"]').val('');
    previewArea.find('input[name="approval_need"]').val('');
    previewArea.find('input[name="notes"]').val('');
}

$('.pack_capacity').change(function(){
  var pack_capacity = $(this).val();
  var currentV = $(this).parents('tr').children()[7].firstChild;
  console.log(currentV)
  requestGetJSON('warehouses/get_pack_by_capacity/' + pack_capacity).done(function(response) {
    currentV.value = response.volume;
  });
})

// $('input[name="discount_percent"]').keyup(function(){
//   calculate_total_quote()
// })

// $('input[name="discount_total"]').keyup(function(){
//   calculate_total_quote()
// })

$('input[name="discount_percent"]').change(function(){
  calculate_total_quote()
})

$('input[name="discount_total"]').change(function(){
  calculate_total_quote()
})



function calculate_total_quote()
{
  if ($('body').hasClass('no-calculate-total')) {
        return false;
    }

    var calculated_tax,
        taxrate,
        item_taxes,
        row,
        _amount,
        _tax_name,
        taxes = {},
        taxes_rows = [],
        sum_volume_m3 = 0,
        subtotal = 0,
        total = 0,
        quantity = 1,
        total_discount_calculated = 0,
        quote_phase_counter = 0,
        rows = $('.table.has-calculations tbody tr.item'),
        discount_area = $('#discount_area'),
        adjustment = $('input[name="adjustment"]').val(),
        discount_percent = $('input[name="discount_percent"]').val(),
        discount_fixed = $('input[name="discount_total"]').val(),
        discount_total_type = $('.discount-total-type.selected');

    $('.tax-area').remove();

    $.each(rows, function() {

        quantity = $(this).find('[data-quantity]').val();
        // if (quantity === '') {
        //     quantity = 1;
        //     $(this).find('[data-quantity]').val(1);
        // }
        _amount = accounting.toFixed($(this).find('td.sale-price input').val() * quantity, app.options.decimal_places);
        _amount = parseFloat(_amount);

        $(this).find('td.amount').html(format_money(_amount, true));
        subtotal += _amount;
        row = $(this);
    });

    // if(quote_phase_counter >= 1){
    //     $("#quote_phase option[value=1]").attr('selected', 'selected');
    //     $('input[name="quote_phase_val"]').val(1);
    //     // console.log(quote_phase_counter);
    // }else if(quote_phase_counter == 0){
    //     // $("#quote_phase").val($("#quote_phase option:first").val());
    //     $("#quote_phase option[value=0]").attr('selected', 'selected');
    //     $('input[name="quote_phase_val"]').val(0);
    //     // console.log(quote_phase_counter);
    // }

    total = (total + subtotal);

    // Discount by percent
    if ((discount_percent !== '' && discount_percent != 0)) {
        total_discount_calculated = (total * discount_percent) / 100;
    }

    // console.log(total_discount_calculated);
    if(total_discount_calculated > 0){
        total = total - total_discount_calculated;
    }
    adjustment = parseFloat(adjustment);

    // Check if adjustment not empty
    if (!isNaN(adjustment)) {
        total = total + adjustment;
    }

    var discount_html = '-' + format_money(total_discount_calculated);
    $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));

    // Append, format to html and display
    $('.discount-total').html(discount_html);
    $('.adjustment').html(format_money(adjustment));
    $('.subtotal').html(format_money(subtotal));
    $('.volumem3').html(format_money(sum_volume_m3));
    $('.total').html(format_money(total));
    
    // Value for hidden fields...
    var sub_total = accounting.toFixed(subtotal, app.options.decimal_places);
    var to_tal_val = accounting.toFixed(total, app.options.decimal_places);
    console.log('sub_total',sub_total)
    $('input[name="subtotal"]').val(sub_total);
    $('input[name="sum_volume_m3"]').val(sum_volume_m3);
    $('input[name="discount_percent"]').val(discount_fixed);
    $('input[name="discount_total"]').val(total_discount_calculated);
    $('input[name="adjustment"]').val(adjustment);
    $('input[name="total"]').val(to_tal_val);

    $(document).trigger('sales-total-calculated');
}

</script>
</body>
</html>
