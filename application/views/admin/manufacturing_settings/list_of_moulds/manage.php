<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#mould_cavity_modal"><?php echo _l('new_mould'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('mould_name'),
                            _l('mould_cavity'),
                            _l('active'),
                            _l('options'),
                        ),'list-moulds'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mould_cavity_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('edit_mould'); ?></span>
                    <span class="add-title"><?php echo _l('add_mould'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/manufacturing_settings/manage_list_of_moulds',array('id'=>'list_moulds_form')); ?>
            <?php echo form_hidden('mouldid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('mould_name',_l('mould_name'),'','text',array('placeholder'=>_l('mould_name'))); ?>
                        <div id="barcode_exists_info" class="hide"></div>
                        <?php echo render_input('mould_cavity',_l('mould_cavity'),'','number',array('placeholder'=>_l('mould_cavity'))); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){

        initDataTable('.table-list-moulds', window.location.href, [3], [3]);

        appValidateForm($('form'), {
            type: 'required',
        }, manage_mould_cavity);

        $('#mould_cavity_modal').on('show.bs.modal', function(event) {

            var button = $(event.relatedTarget)
            var id = button.data('id');
            console.log(id);

            $('#mould_cavity_modal input[name="mould_name"]').val('');
            $('#mould_cavity_modal input[name="mould_cavity"]').val('');
            $('#mould_cavity_modal input[name="mouldid"]').val('');

            $('#mould_cavity_modal .add-title').removeClass('hide');
            $('#mould_cavity_modal .edit-title').addClass('hide');

            if (typeof(id) !== 'undefined') {
                $('input[name="mouldid"]').val(id);
                var mould_name = $(button).parents('tr').find('td').eq(0).find('span.name').text();
                var mould_cavity = $(button).parents('tr').find('td').eq(1).find('span.cavity').text();
                $('#mould_cavity_modal .add-title').addClass('hide');
                $('#mould_cavity_modal .edit-title').removeClass('hide');
                $('#mould_cavity_modal input[name="mould_name"]').val(mould_name);
                $('#mould_cavity_modal input[name="mould_cavity"]').val(mould_cavity);
            }
        });
    });
    /* manage_mould_cavity MANAGE FUNCTIONS */
    function manage_mould_cavity(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                $('.table-list-moulds').DataTable().ajax.reload();
                alert_float('success', response.message);
            }
            $('#mould_cavity_modal').modal('hide');
        });
        return false;
    }

    $('input[name="mould_name"]').keyup(function () {
        var mould_name = $(this).val();
        var $barcodeExistsDiv = $('#barcode_exists_info');
        if(mould_name == '') {
            $barcodeExistsDiv.addClass('hide');
            return;
        }
        $.post(admin_url+'manufacturing_settings/check_duplicate_mould', {mould_name:mould_name})
        .done(function(response) {
            if(response) {
                response = JSON.parse(response);
                if(response.exists == true) {
                    $barcodeExistsDiv.removeClass('hide');
                    $barcodeExistsDiv.html('<div class="info-block mbot15">'+response.message+'</div>');
                    $('#submit').prop('disabled',true);

                } else {
                    $barcodeExistsDiv.addClass('hide');
                    $('#submit').prop('disabled',false);
                }
            }
        });
    })
</script>
</body>
</html>
