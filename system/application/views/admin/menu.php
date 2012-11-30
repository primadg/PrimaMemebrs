<div class="body_left" style="width: 232px;">

      <div class="line_shadow" style="height: 100%; float: left; width: 232px; background-image: url('<?php echo base_url()?>img/shadow_top_fon.gif'); background-repeat: repeat-y; background-position: right;">

      <div style="height: 300px; margin-top: -300px;">
        <div id="a_block" style="width: 212px; display: none; z-index:2; height: 150px; background-color:#abe1ff; position: relative; left:3px; top: 0px; border:2px #3d99da solid;">

      </div>
      </div>
      
      <?php 
      if(!$this->admin_auth_model->isAccessDenied(PRODUCT)!==false)
      {
      $item_counter=Submenu_items_count(array('admin_product_protected','admin_product_protected','admin_product_hosted'),5);
      ?>
      <div class="menu_containter" style="height: <?php echo ($item_counter*15+30)?>px;" id="top1">
        <img alt="" src="<?php echo base_url()?>img/ico_product.png" width="16" height="14" /><{admin_menu_product}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_1_1" />
            <a href="#products_list" onclick="menu_click(1, 1, '<?php echo base_url()?>product/product_list',{a1:'<?php echo base_url()?>js/admin/member/common.js', a2:'<?php echo base_url()?>js/admin/product/add.js', a3:'<?php echo base_url()?>js/validation.js', a4:'<?php echo base_url()?>js/admin/product/product_list.js', a5:'<?php echo base_url()?>js/admin/product/product_form.js', a6:'<?php echo base_url()?>js/admin/product/image_upload.js', a7:'<?php echo base_url()?>js/admin/product/edit.js', a8:'<?php echo base_url()?>js/admin/form_error.js'}); return false;"><{admin_menu_products_list}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_1_2" />
            <a href="#products_groups" onclick="menu_click(1, 2, '<?php echo base_url()?>product_group/group_list', {a1:'<?php echo base_url()?>js/admin/member/common.js', a2:'<?php echo base_url()?>js/validation.js', a3:'<?php echo base_url()?>js/admin/product_group/list.js', a4:'<?php echo base_url()?>js/admin/product_group/form.js', a5:'<?php echo base_url()?>js/admin/product_group/add.js', a6:'<?php echo base_url()?>js/admin/product_group/edit.js', a7:'<?php echo base_url()?>js/admin/form_error.js'}); return false;"><{admin_menu_products_groups}></a>
          </div>
          <?php //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_protected')===true)
        {
        ?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_1_4" />
            <a href="#directories_protection" onclick="menu_click(1, 4, '<?php echo base_url()?>directories/dir_list', {'0':base_url+'js/admin/init.js','1':base_url+'js/admin/directories/directory.js'}); return false;"><{admin_menu_directories_protection}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_1_5" />
            <a href="#file_protection" onclick="menu_click(1, 5, '<?php echo base_url()?>directories/file_protection', {'0':base_url+'js/admin/init.js','1':base_url+'js/admin/directories/file_protection.js'}); return false;"><{admin_menu_file_protection}></a>
          </div>
        <?php 
        }
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===true)
        {
        ?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_1_6" />
            <a href="#host_plans" onclick="menu_click(1, 6, '<?php echo base_url()?>host_plans/host_plans_list', {'0':base_url+'js/admin/init.js','1':base_url+'js/admin/host_plans/host_plan.js'}); return false;"><{admin_menu_host_plans}></a>
          </div>
        <?php 
        } 
        ?>
          
        </div>
      </div>
      <?php
      }
      if(!$this->admin_auth_model->isAccessDenied(MEMBER_CONTROL)!==false)
      {
      $item_counter=Submenu_items_count(array('admin_member_group'),4);
      ?>      
      <div class="menu_containter" style="height: <?php echo ($item_counter*13+30)?>px;" id="top2">
        <img alt="" src="<?php echo base_url()?>img/ico_member_control.png" width="16" height="16" /><{admin_menu_member_control}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_1" />
            <a href="#add_member" onclick="menu_click(2, 1, '<?php echo base_url()?>member/add',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/add.js',3:'<?php echo base_url()?>js/admin/member/list.js',4:'<?php echo base_url()?>js/admin/member/edit/accnt_panel.js',5:'<?php echo base_url()?>js/admin/member/edit/member_info_update.js',6:'<?php echo base_url()?>js/admin/member/edit/change_pswd.js',7:'<?php echo base_url()?>js/admin/member/edit/payments.js',8:'<?php echo base_url()?>js/admin/member/edit/email_client.js',9:'<?php echo base_url()?>js/admin/member/edit/email_history.js',10:'<?php echo base_url()?>js/admin/member/edit/access_log.js',11:'<?php echo base_url()?>js/admin/member/suspend.js',12:'<?php echo base_url()?>js/admin/member/unsuspend.js',13:'<?php echo base_url()?>js/admin/member/info.js',14:'<?php echo base_url()?>js/admin/global.js',15:'<?php echo base_url()?>js/admin/member/edit/transactions.js'}); return false;"><{admin_menu_add_member}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_2" />
            <a href="#member_list" onclick="menu_click(2, 2, '<?php echo base_url()?>member/member_list',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_member_list}></a>
          </div>
          <?php if(Functionality_enabled('admin_member_group')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_8" />
            <a href="#member_groups" onclick="menu_click(2, 8, '<?php echo site_url('member_group/group_list')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_member_group_list}></a>
          </div>
          <?php }?>
          <div>
            <!--<img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_3" />
            <a href="#approve_suspend" onclick="menu_click(2, 3, '<?php echo base_url()?>member/approve',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/approve.js',3:'<?php echo base_url()?>js/admin/member/suspend.js',4:'<?php echo base_url()?>js/admin/member/info.js',5:'<?php echo base_url()?>js/admin/member/suspreason.js',6:'<?php echo base_url()?>js/admin/global.js',7:'<?php echo base_url()?>js/admin/member/unsuspend.js'}); return false;"><{admin_menu_approve_suspend}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_4" />
            <a href="#activate_suspend" onclick="menu_click(2, 4, '<?php echo base_url()?>member/confirmation',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/confirm.js',3:'<?php echo base_url()?>js/admin/member/suspend.js',4:'<?php echo base_url()?>js/admin/member/info.js',5:'<?php echo base_url()?>js/admin/member/unsuspend.js'}); return false;"><{admin_menu_activate_suspend}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_5" />
            <a href="#unsuspend_delete" onclick="menu_click(2, 5, '<?php echo base_url()?>member/unsuspend',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/unsuspend.js',3:'<?php echo base_url()?>js/admin/member/info.js'}); return false;"><{admin_menu_unsuspend_delete}></a>
          </div>
          <div>
          <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_6" />
          <a href="#member_expired" onclick="menu_click(2, 6, '<?php echo base_url()?>member/expired',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/expired.js',3:'<?php echo base_url()?>js/admin/member/info.js'}); return false;"><{admin_menu_member_expired}></a>
          </div>
          <div>-->
          <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_2_7" />
          <a href="#member_statistics" onclick="menu_click(2, 7, '<?php echo base_url()?>member/statistics',{1:'<?php echo base_url()?>js/admin/member/common.js',2:'<?php echo base_url()?>js/admin/member/statistics.js'}); return false;"><{admin_menu_member_statistics}></a>
          </div>
        </div>
      </div>
       <?php
      }
      if(!$this->admin_auth_model->isAccessDenied(TRANSACTION)!==false)
      {
      $item_counter=Submenu_items_count(array('admin_statistics_graphs','admin_statistics_total'),3);
      ?>
      <div class="menu_containter" style="height: <?php echo ($item_counter*15+30)?>px;" id="top3">
        <img alt="" src="<?php echo base_url()?>img/ico_transaction.png" width="15" height="16" /><{admin_menu_statistics}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_3_1" />
            <a href="#billing" onclick="menu_click(3, 1, '<?php echo site_url('statistic/subscriptions_stats')?>',{1:'<?php echo base_url()?>js/validation.js', 2:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_billing}></a>
          </div>
          <?php if(Functionality_enabled('admin_statistics_total')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_3_2" />
            <a href="#total_statistics" onclick="menu_click(3, 2, '<?php echo site_url('statistic/total_stats')?>',{1:'<?php echo base_url()?>js/validation.js', 2:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_total_statistics}></a>
          </div>
          <?php }?>
          <?php if(Functionality_enabled('admin_statistics_graphs')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_3_4" />
            <a href="#menu_graphs" onclick="menu_click(3, 4, '<?php echo base_url()?>statistic/graphs',{1:'<?php echo base_url()?>js/admin/statistic/graphs.js',2:'<?php echo base_url()?>js/admin/global.js'}); return false;"><{admin_menu_graphs}></a>
          </div>
          <?php }?>
        </div>
      </div>
        <?php
    }
    if(!$this->admin_auth_model->isAccessDenied(NEWSLETTER)!==false)
    {
    $item_counter=Submenu_items_count(array('admin_newsletter_send','admin_newsletter_template'),3);
    ?>
      <div class="menu_containter" style="height: <?php echo ($item_counter*15+30)?>px;" id="top4">
        <img alt="" src="<?php echo base_url()?>img/ico_newsletter.png" width="16" height="16" /><{admin_menu_newsletter}>
        <div class="menu_pod">
          <?php if(Functionality_enabled('admin_newsletter_template')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_4_1" />
            <a href="#email_templates" onclick="menu_click(4, 1, '<?php echo base_url()?>newsletter/template_list',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_email_templates}></a>
          </div>
          <?php }?>
          <?php if(Functionality_enabled('admin_newsletter_send')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_4_2" />
            <a href="#send_email" onclick="menu_click(4, 2, '<?php echo base_url()?>newsletter/send_email',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_send_email}></a>
          </div>
          <?php }?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_4_3" />
            <a href="#email_history" onclick="menu_click(4, 3, '<?php echo base_url()?>newsletter/history',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_email_history}></a>
          </div>
        </div>
      </div>
        <?php
    }
    if(!$this->admin_auth_model->isAccessDenied(COUPON)!==false)
    {
    ?>
      <div class="menu_containter" style="height: <?php echo (3*15+30)?>px;" id="top5">
        <img alt="" src="<?php echo base_url()?>img/ico_coupon.png" width="16" height="16" /> <{admin_menu_coupon}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_5_1" />
            <a href="#coupon_groups" onclick="menu_click(5, 1, '<?php echo base_url()?>coupons/coupons_list',{1:'<?php echo base_url()?>js/admin/coupons/common.js',2:'<?php echo base_url()?>js/admin/coupons/groups_list.js',3:'<?php echo base_url()?>js/admin/coupons/edit.js',4:'<?php echo base_url()?>js/admin/coupons/coupons_list.js',5:'<?php echo base_url()?>js/admin/global.js',6:'<?php echo base_url()?>js/admin/coupons/add.js',7:'<?php echo base_url()?>js/validation.js'}); return false;"><{admin_menu_coupon_groups}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_5_2" />
            <a href="#create_coupons" onclick="menu_click(5, 2, '<?php echo base_url()?>coupons/add',{1:'<?php echo base_url()?>js/admin/coupons/common.js',2:'<?php echo base_url()?>js/admin/coupons/add.js',3:'<?php echo base_url()?>js/admin/coupons/groups_list.js',4:'<?php echo base_url()?>js/admin/global.js',5:'<?php echo base_url()?>js/validation.js',6:'<?php echo base_url()?>js/admin/coupons/edit.js',7:'<?php echo base_url()?>js/admin/coupons/coupons_list.js'}); return false;"><{admin_menu_create_coupons}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_5_3" />
            <a href="#coupons_statistics" onclick="menu_click(5, 3, '<?php echo base_url()?>coupons/statistic',{1:'<?php echo base_url()?>js/admin/coupons/common.js',2:'<?php echo base_url()?>js/admin/coupons/statistic.js',3:'<?php echo base_url()?>js/admin/coupons/groups_list.js',4:'<?php echo base_url()?>js/admin/member/info.js',5:'<?php echo base_url()?>js/admin/coupons/edit.js',6:'<?php echo base_url()?>js/admin/coupons/coupons_list.js',7:'<?php echo base_url()?>js/admin/global.js'}); return false;"><{admin_menu_coupons_statistics}></a>
          </div>
      </div>
      </div>
        <?php
    }
  
    if(!$this->admin_auth_model->isAccessDenied(ADMINISTRATOR_CONTROL)!==false)
    {
    ?>
      <div class="menu_containter" style="height: <?php echo (3*15+30)?>px;" id="top7">
        <img alt="" src="<?php echo base_url()?>img/ico_adm_control.png" width="15" height="15" /> <{admin_menu_administrator_control}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_7_1" />
            <a href="#add_administrator" onclick="menu_click(7, 1, '<?php echo site_url('admin_control/admin_edit')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_add_administrator}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_7_2" />
            <a href="#administrator_list" onclick="menu_click(7, 2, '<?php echo site_url('admin_control/admin_list')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_administrator_list}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_7_3" />
            <a href="#level_list" onclick="menu_click(7, 3, '<?php echo site_url('admin_control/levels')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_level_list}></a>
          </div>
        </div>
      </div>
        <?php
    }
    if(!$this->admin_auth_model->isAccessDenied(ACTIVITY_LOGGING)!==false)
    {
    ?>
      <div class="menu_containter" style="height: <?php echo (3*15+30)?>px;" id="top8">
        <img alt="" src="<?php echo base_url()?>img/ico_activ_logging.png" width="15" height="16" /> <{admin_menu_activity_logging}>
        <div class="menu_pod">
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_8_1" />
            <a href="#admin_log" onclick="menu_click(8, 1, '<?php echo site_url('logging/admin')?>',{0:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_admin_log}></a><!-- Administrator Log -->
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_8_2" />
            <a href="#protection_errors" onclick="menu_click(8, 2, '<?php echo site_url('logging/protect')?>',{0:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_protection_errors}></a><!-- Protection Errors -->
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_8_3" />
            <a href="#user_log" onclick="menu_click(8, 3, '<?php echo site_url('logging/user')?>',{0:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_user_log}></a><!-- User Log --->
          </div>
        </div>
      </div>
        <?php
    }
      if(!$this->admin_auth_model->isAccessDenied(SYSTEM_CONFIGURATION)!==false)
    {
    $item_counter=Submenu_items_count(array('admin_config_design','admin_multi_language','admin_config_pages','admin_config_payment','admin_product_hosted'),14);
    ?>
      <div class="menu_containter" style="height: <?php echo ($item_counter*16+30)?>px;" id="top6">
        <img alt="" src="<?php echo base_url()?>img/ico_sysconf.png" width="15" height="14" /> <{admin_menu_system_configuration}>
        <div class="menu_pod">
        <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_3" />
            <a href="#global_setup" onclick="menu_click(6, 3, '<?php echo site_url('config/global_setup')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_global_setup}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_1" />
            <a href="#status_settings" onclick="menu_click(6, 1, '<?php echo site_url('config/status_settings')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_status_settings}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_2" />
            <a href="#security_settings" onclick="menu_click(6, 2, '<?php echo site_url('config/security_settings')?>', {1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_security_settings}></a>
          </div>          
          <?php if(Functionality_enabled('admin_config_payment')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_4" />
            <a href="#system_settings" onclick="menu_click(6, 4, '<?php echo site_url('config/payment_system')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_payment_system_settings}></a>
          </div>
          <?php }?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_5" />
            <a href="#ban_ip_list" onclick="menu_click(6, 5,  '<?php echo site_url('config/ban_ip_list')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_ban_ip_list}></a>
          </div>
          <!--<div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_6" />
            <a href="#ban_ip_add" onclick="menu_click(6, 6,  '<?php echo site_url('config/ban_ip_add')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_ban_ip_add}></a>
          </div>-->
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_7" />
            <a href="#mailer_settings" onclick="menu_click(6, 7, '<?php echo site_url('config/mailer_settings')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_mailer_settings}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_8" />
            <a href="#member_settings" onclick="menu_click(6, 8, '<?php echo site_url('config/member_settings')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_member_settings}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_9" />
            <a href="#system_emails" onclick="menu_click(6, 9, '<?php echo site_url('config/sys_emails')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_system_emails}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_10" />
            <a href="#additional_fields" onclick="menu_click(6, 10, '<?php echo site_url('config/add_fields')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_additional_fields}></a>
          </div>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_11" />
            <a href="#manage_news" onclick="menu_click(6, 11, '<?php echo site_url('config/manage_news')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_manage_news}></a>
          </div>
          <?php  if(Functionality_enabled('admin_config_design')===true){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_12" />
            <a href="#design_manager" onclick="menu_click(6, 12, '<?php echo site_url('config/design_manager')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_design_manager}></a>
          </div>
          <?php  } ?>
          <?php  if(Functionality_enabled('admin_config_pages')!==false){?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_13" />
            <a href="#manage_pages" onclick="menu_click(6, 13, '<?php echo site_url('config/manage_pages')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_manage_pages}></a>
          </div>
          <?php  } ?>
        <?php  if(Functionality_enabled('admin_multi_language')===true){?>
            <div id="link1">
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_14" />
            <a href="#lang_editor" onclick="menu_click(6, 14, '<?php echo site_url('config/language_list')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_lang_editor}></a>
            </div>
        <?php }?>
        <?php //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===true)
        {
        ?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_15" />
            <a href="#host_plans_settings" onclick="menu_click(6, 15, '<?php echo site_url('config/host_plans_settings')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_hosted_settings}></a>
          </div>
        <?php 
        } 
         //***********Functionality limitations***********
         if(Functionality_enabled('admin_domain_settings')===true)
        {
        ?>
          <div>
            <img src="<?php echo base_url()?>img/strelka.gif" id="cursor_6_16" />
            <a href="#domain_settings" onclick="menu_click(6, 16, '<?php echo site_url('config/domain_settings')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;"><{admin_menu_domain_settings}></a>
          </div>
        <?php 
        } 
        ?>
        </div>
      </div>
        <?php
    }
    ?>
    <div style="height: 15px;">
    </div>
      </div>
    </div>
