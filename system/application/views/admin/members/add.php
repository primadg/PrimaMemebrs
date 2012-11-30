        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_add_member_big.png" width="32" height="32" alt="<{admin_member_control_add_member_label}>"/></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_add_member_label}></div>
            <div class="header_comment"><{admin_member_control_add_member_label_desc}></div>
          </div>
        </div>
        <?php echo isset($message_box) ? admin_print_msg_box('msg',$message_box) : "";?>
		<?php echo isset($error_box) ? admin_print_msg_box('emsg',$error_box) : "";?>      
        <br />
        <table class="settings table_pos" style="margin-top: 10px;" >
        <?php echo admin_print_fields($fields,"admin_member_control_add_member");?>      
        <tr>
            <td width="30%" align="right"><{admin_member_control_add_member_field_expiration_date}></td><td width="10px"></td>
            <td width="320px">
              <input type="text" 
                style="width: 200px;"  maxlength="10"
                name="exp_date" id="mbr_exp_date" class="<?php echo datepicker_class()?>" value="<?php echo isset($exp_date) ? $exp_date : "";?>" />
            </td>
            <td><?php echo create_tooltip_div(config_get('system','config','date_format').'<{admin_member_control_add_member_field_expiration_date_tooltip}>',true);?></td>
          </tr>
          <tr class="glav">
      				<td align="right"><{admin_member_control_add_member_field_groups}></td><td></td>
      				<td> 
                    <select name="groups" style="width: 310px; height: 100px;" multiple id="mbr_groups">
		              <?php  foreach($all_groups as $value){?>
                        <option <?php echo isset($value['selected']) && intval($value['selected']) ? "selected" : "";?>  value="<?php echo $value['id']?>"><?php echo output($value['name'])?></option>
                        <?php } ?>
  					</select> 
                    </td>
      			</tr>
          <tr>
            <td align="right"><{admin_member_control_add_member_field_status}></td><td></td>
            <td><input type="checkbox" id="mbr_status_approved" name="status_approved" checked="checked" /><{admin_member_control_account_panel_member_info_checkbox_label_approved}>&nbsp;&nbsp;<input type="checkbox" id="mbr_status_confirmed" name="status_confirmed" checked="checked" /><{admin_member_control_account_panel_member_info_checkbox_label_confirmed}>&nbsp;&nbsp;<input type="checkbox" id="mbr_status_suspended" name="status_suspended" /><{admin_member_control_account_panel_member_info_checkbox_label_suspended}>
            </td>
            <td></td>
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
            	<input type="button" class="button" value="<{admin_member_control_add_member_button_add}>" onClick="myOnSave();" />&nbsp;
          <input type="button" class="button" value="<{admin_member_control_add_member_button_cancel}>" onClick="load_member_list();" />
            </td>
          </tr>     
        </table>
<br />
