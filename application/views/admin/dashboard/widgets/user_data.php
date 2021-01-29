<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('user_widget'); ?>">
   <div class="panel_s user-data">
      <div class="panel-body home-activity">
         <div class="widget-dragger"></div>
         <div class="horizontal-scrollable-tabs">
            <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                  <?php if(is_admin()){ ?>
                  <li role="presentation">
                     <a href="#home_tab_activity" aria-controls="home_tab_activity" role="tab" data-toggle="tab">
                     <i class="fa fa-window-maximize menu-icon"></i> <?php echo _l('home_latest_activity'); ?>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
               <hr class="hr-panel-heading hr-user-data-tabs" />
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="home_tab_activity">
                     <a href="<?php echo admin_url('utilities/activity_log'); ?>" class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                     <div class="clearfix"></div>
                     <div class="activity-feed">
                        <?php foreach($activity_log as $log){ ?>
                        <div class="feed-item">
                           <div class="date">
                              <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($log['date']); ?>">
                              <?php echo time_ago($log['date']); ?>
                              </span>
                           </div>
                           <div class="text">
                              <?php echo $log['staffid']; ?><br />
                              <?php echo $log['description']; ?>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
