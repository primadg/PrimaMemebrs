        <br />
        <?php echo isset($message_box) ? admin_print_msg_box('msg',$message_box) : "";?>
		<?php echo isset($error_box) ? admin_print_msg_box('emsg',$error_box) : "";?>      
        <div class="tema"><{admin_member_control_account_panel_member_info_page_title}></div>
        <?php if(!$this->admin_auth_model->isAccessDenied(NEWSLETTER)!==false){ ?>
            <div style="text-align:right;">
            <?php if(Functionality_enabled('admin_newsletter_send')===true){?>
            <a href="#" onclick="send_email_to_memeber('<?php echo $items['id']?>');return false;"><{admin_member_control_account_panel_member_info_page_send_email}></a>&nbsp;<br />
            <?php }?>            
            <a href="#" onclick="memeber_email_history('<?php echo $items['id']?>');return false;"><{admin_member_control_account_panel_member_info_page_email_history}></a>&nbsp;
            </div>
            <?php }else{?>
            <br/>
            <?php }?>          
          <table class="settings table_pos">
          <?php echo admin_print_fields($fields,"admin_member_control_account_panel_member_info");?>      
          <tr> 
      				<td width="30%" align="right"><{admin_member_control_account_panel_member_info_field_expiration_date}></td><td td width="10px"></td>
      				<td width="320px"><input id="mbr_exp_date" type="text" maxlength="10"
                        name="exp_date" id="date1Entry" class="<?php echo datepicker_class();?>" 
                    value="<?php echo isset($items['expire_date']) ? nsdate($items['expire_date'],false) :"";?>" /></td>
              <td><?php echo create_tooltip_div(config_get('system','config','date_format').'<{admin_member_control_add_member_field_expiration_date_tooltip}>',true);?></td><!--Date when user becomes suspended dd-mm-yyyy(00-00-0000 means unlimited)-->
      			</tr>
      			<tr class="glav">
      				<td align="right"><{admin_member_control_account_panel_member_info_field_groups}></td><td></td>
      				<td>
                    <select name="groups" style="width: 310px; height: 100px;" multiple id="mbr_groups">
		              <?php  foreach($items['all_groups'] as $value){?>
                        <option <?php echo isset($items['groups'][$value['id']]) ? "selected" : "";?> value="<?php echo $value['id']?>"><?php echo output($value['name'])?></option>
                        <?php } ?>
  					</select> 
                    </td><td></td>
      			</tr>
                <tr class="glav">
                    <td align="right"><{admin_member_control_account_panel_member_info_field_status}></td><td></td>
                    <td><input
                        type="checkbox" 
                        id="mbr_status_approved" 
                        name="status_approved" 
                        <?php if($items['approve'] == '1'){echo " checked ";}?> /><{admin_member_control_account_panel_member_info_checkbox_label_approved}>&nbsp;&nbsp;<input 
                        type="checkbox" 
                        id="mbr_status_confirmed" 
                        name="status_confirmed" 
                        <?php if($items['activate'] == '1'){echo " checked ";}?> /><{admin_member_control_account_panel_member_info_checkbox_label_confirmed}>&nbsp;&nbsp;<input 
                        type="checkbox" 
                        id="mbr_status_suspended" 
                        name="status_suspended" 
                        <?php if($items['suspended'] == '1'){echo " checked ";}?> /><{admin_member_control_account_panel_member_info_checkbox_label_suspended}>
                    </td><td></td>
                </tr>
                <?php  if(intval(config_get("system","config","personal_login_redirect_flag"))){ ?>
                <tr> 
      				<td width="30%" align="right"><{admin_member_control_account_panel_member_info_field_login_redirect}></td><td td width="10px"></td>
      				<td width="320px"><input id="mbr_login_redirect" type="text" maxlength="2048"
                        style="width:300px;" name="login_redirect" value="<?php echo isset($items['login_redirect']) ? $items['login_redirect'] :"";?>" /></td>
                    <td><?php echo create_tooltip_div('<{admin_member_control_add_member_field_login_redirect_tooltip}>',true);?></td>
      			</tr>
                <?php  } ?>
                <tr>
                	<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{admin_member_control_account_panel_member_info_button_save}>" onClick="myOnEdit(<?php echo intval($id)?>);" />&nbsp;<input type="button" class="button" value="<{admin_member_control_account_panel_member_info_button_cancel}>" onClick="load_member_list()" />
                    </td>
                </tr>                     
    			</table>
          <br />
