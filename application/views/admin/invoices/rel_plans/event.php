<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _event" id="viewEvent">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $event->title; ?></h4>
      </div>
      <?php echo form_open('admin/invoices/calendar',array('id'=>'calendar-event-form')); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?php if($event->userid != get_staff_user_id()){ ?>
              <div class="alert alert-info"><?php echo _l('event_created_by','<a href="'.admin_url('profile/'.$event->userid).'" target="_blank">'.get_staff_full_name($event->userid)).'</a>'; ?></div>
            <?php } ?>
            <?php if($event->userid == get_staff_user_id() || is_admin()){ ?>
              <?php echo form_hidden('eventid',$event->eventid); ?>
              <?php echo render_datetime_input('start','utility_calendar_new_event_start_date',_dt($event->start)); ?>
              <div class="clearfix mtop15"></div>
              <?php echo render_datetime_input('end','utility_calendar_new_event_end_date',_dt($event->end)); ?>
              <?php echo render_input('production_calculate',_l('production_calculate'),$event->production_calculate,'number',array('placeholder'=>_l('production_calculate'))); ?>
              <?php
                $machine_option = '<option></option>';
                foreach ($machines as $key => $machine) {
                  if($machine->id == $event->machine_id)
                      $machine_option.='<option value="'.$machine->id.'" selected>'.$machine->name.'</option>';
                  else
                      $machine_option.='<option value="'.$machine->id.'">'.$machine->name.'</option>';
                }
              ?>
              <div class="form-group">
                <label for="machine_id"><?php echo _l('machine_in_mould_suitablity') ?></label>
                <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                      <select data-fieldto="machine_id" data-fieldid="machine_id" name="machine_id" id="machine_id" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                          <?php echo $machine_option;?>
                      </select>
                  </div>
              </div>

              <?php echo render_select('mould_id',$moulds,array('id','mould_name'),_l('mould_id'),$event->mould_id); ?>
           <hr />
           <p class="bold"><?php echo _l('event_color'); ?></p>
           <?php
           $event_colors = '';
           $favourite_colors = get_system_favourite_colors();
           $i = 0;
           foreach($favourite_colors as $color){
            $color_selected_class = 'cpicker-small';
            if($color == $event->color){
              $color_selected_class = 'cpicker-big';
            }
            $event_colors .= "<div class='calendar-cpicker cpicker ".$color_selected_class."' data-color='".$color."' style='background:".$color.";border:1px solid ".$color."'></div>";
            $i++;
          }
          echo '<div class="cpicker-wrapper">';
          echo $event_colors;
          echo '</div>';
          echo form_hidden('color',$event->color);
          ?>

          <div class="clearfix"></div>
          <hr />
          <!-- <div class="checkbox checkbox-primary">
            <input type="checkbox" name="public" id="event_public" <?php if($event->public == 1){echo 'checked';} ?>>
            <label for="event_public"><?php echo _l('utility_calendar_new_event_make_public'); ?></label>
          </div> -->
        <?php } else { ?>
          <a href="<?php echo admin_url('profile/'.$event->userid); ?>"><?php echo staff_profile_image($event->userid,array('staff-profile-xs-image')); ?> <?php echo get_staff_full_name($event->userid); ?></a>
          <hr />
          <h5 class="bold"><?php echo _l('event_description'); ?></h5>
          <p><?php echo $event->description; ?></p>
          <h5 class="bold"><?php echo _l('utility_calendar_new_event_start_date'); ?></h5>
          <p><?php echo _dt($event->start); ?></p>
          <?php if(is_date($event->end)){ ?>
            <h5 class="bold"><?php echo _l('utility_calendar_new_event_end_date'); ?></h5>
            <p><?php echo _dt($event->end); ?></p>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
    <?php if($event->userid == get_staff_user_id() || is_admin()){ ?>
      <button type="button" class="btn btn-danger" onclick="delete_event(<?php echo $event->eventid; ?>); return false"><?php echo _l('delete_event'); ?></button>
      <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
    <?php } ?>
  </div>
  <?php echo form_close(); ?>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
