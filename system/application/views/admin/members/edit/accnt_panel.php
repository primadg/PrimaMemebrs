        <div class="stat" style="width: 700px; margin: 0 auto; padding-top: 10px;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box_stat" style="height: 40px; padding-top: 10px;">
            <div style="float: left; width: 33%;"><{admin_member_control_account_panel_header_label_member}>&nbsp;<div id="mbr_name"><?php echo $items['full_name'];?></div></div>
            <?php if(Functionality_enabled('admin_member_email_authentication')!==true){?>
            <div style="float: left; width: 33%;"><{admin_member_control_account_panel_header_label_login}>&nbsp; <?php echo $items['login_short'];?></div>
            <?php  } ?>
            <div style="float: left; width: 33%;"><{admin_member_control_account_panel_header_label_member_id}>&nbsp;<?php echo $items['id'];?></div>
          </div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        
        <div style="marging-top: 10px;">&nbsp;</div>
        
        
        <table class="member_list" style="width: 704px;">
          <tr style="height: 35px;">
            <td id="tab_member_info" style="text-align: center; background-color: #EEF8FE;"><a href="#"
            onClick="load_account_edit_form(); return false;"><{admin_member_control_account_panel_header_tab_member_info}></a></td>
            <?php  foreach($profile_additional as $pa){ ?>
            <td id="tab_<?php echo $pa['account_type_string'].$pa['account_id']?>" style="text-align: center;"><a href="#"
            onClick="load_additional_profile_form('<?php echo $pa['account_type_string']?>','<?php echo $pa['account_id']?>'); return false;"><?php echo $pa['account_name']?></a></td>
            <?php  } ?>
            <td id="tab_change_pswd" style="text-align: center;"><a href="#"
            onClick="load_change_pswd_form(); return false;"><{admin_member_control_account_panel_header_tab_change_password}></a></td>
            <td id="tab_payments" style="text-align: center;"><a href="#"
            onClick="load_payments();return false;"><{admin_member_control_account_panel_header_tab_user_payments_subscriptions}></a></td>
            <?php if(false && !defined('NS_DEMO_VERSION')){?>
            <td id="tab_email_client" style="text-align: center;"><a href="#"
            onClick="load_email_client_form(); return false;"><{admin_member_control_account_panel_header_tab_email_client}></a></td>
            <td id="tab_email_history"><a href="#"
            onClick="load_email_history_list(); return false;"><{admin_member_control_account_panel_header_tab_email_history}></a></td>
            <?php }?>
            <td id="tab_access_log" style="text-align: center;"><a href="#"
            onClick="load_access_log_list({log_is_search: false}); return false;"><{admin_member_control_account_panel_header_tab_access_log}></a></td>
          </tr>
        </table>
        <input type="hidden" id="mbr_id" value="<?php echo $items['id'];?>"  />
        <div id="account_panel" class="member_list_block">
            <?php echo $panel_body ;?>
        </div>
 <br />       
        
