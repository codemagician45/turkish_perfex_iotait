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
            <?php echo form_open('admin/list_of_packaging/add',array('id'=>'proposal-form','class'=>'_transaction_form proposal-form')); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('packing_type','Packing Type','','text',array('placeholder'=>_l('Packing Type'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('pack_capacity','Pack capacity','','number',array('placeholder'=>_l('Pack capacity'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('box_quality','Box Quality','','text',array('placeholder'=>_l('Box Quality'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('box_type','Box Type','','text',array('placeholder'=>_l('Box type'))); ?>
                            </div>

                            <div class="col-md-6">
                                <?php echo render_input('l_size','L size','','number',array('placeholder'=>_l('L size'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('w_size','W Size','','number',array('placeholder'=>_l('W Size'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('h_size','H Size','','number',array('placeholder'=>_l('H size'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('volume_m3','Volume M3','','number','','','','volume_m3',array('placeholder'=>_l('volume m3'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('pack_price','Pack Price','','number',array('placeholder'=>_l('pack price'))); ?>
                            </div>

                            <div class="col-md-6">
                                <?php echo render_input('price_per_item','Pack Price Per Item','','number',array('placeholder'=>_l('Pack price per item'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('stock_qty','Stock quantity','','number',array('placeholder'=>_l('Stock Quantity'))); ?>


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

            <?php echo form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
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
    $('body').on('click','.volume_m3', function() {

        var l_value = $('input[name="l_size"]').val();
        var w_value = $('input[name="w_size"]').val();
        var h_value = $('input[name="h_size"]').val();
        var total_value = ((l_value*w_value*h_value)/1000000000);
        var values = total_value.toFixed(8);
        $('input[name="volume_m3"]').val(values);


    });
</script>
</body>
</html>
