

<div class="tema"><{admin_member_control_account_panel_email_client_page_title}></div>
          
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>          
          
        <br />
          
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_member_control_error_empty_fields}></div>
            <div  id="jsvalid_error_template_id_wrong" class="box_err" style="display: none"><{admin_member_control_account_panel_email_client_error_template_id_wrong}></div>
            <div  id="jsvalid_error_from_toolong" class="box_err" style="display: none"><{admin_member_control_account_panel_email_client_error_email_toolong}></div>
            <div  id="jsvalid_error_from_wrong" class="box_err" style="display: none"><{admin_member_control_account_panel_email_client_error_email_wrong}></div>
            <div  id="jsvalid_error_subject_toolong" class="box_err" style="display: none"><{admin_member_control_account_panel_email_client_error_subject_toolong}></div>
            <div  id="jsvalid_error_msg_toolong" class="box_err" style="display: none"><{admin_member_control_account_panel_email_client_error_message_toolong}></div>            
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>          
          
          <table class="settings" align="center">
      			<tr>
      				<td align="right"><{admin_member_control_account_panel_email_client_field_template}></td>
      				<td>
                <select id="template"
                onChange="load_email_client_form('tpl_change');">
                <?php 
                    foreach($template_list as $tpl_info)
                    {
                ?>    
                    <option value="<?php echo $tpl_info['id']; ?>"
                    <?php if($tpl_info['id'] == $sel_tpl_id){echo " selected ";} ?>
                    ><?php echo $tpl_info['name']; ?></option>
                <?php
                    }
                ?>                
                </select>
              </td>
      			</tr>
      			<tr>
      				<td align="right"><{admin_member_control_account_panel_email_client_field_to}></td>
      				<td><?php echo $email_to; ?></td>
      			</tr>
      			<tr class="glav"> 
      				<td align="right"><{admin_member_control_account_panel_email_client_field_from}> <span style="color: red;">*</span></td>
      				<td><input id="from" type="text" 
                    style="width: 400px;" maxlength="50"
                    value="<?php echo $email_from; ?>"></td>
            </tr>
      			<tr class="glav"> 
      				<td align="right"><{admin_member_control_account_panel_email_client_field_subject}> <span style="color: red;">*</span></td>
      				<td><input id="subject" type="text" 
                    style="width: 400px;" maxlength="254" 
                    value="<?php echo $subject; ?>"></td>
      			</tr>
      			<tr class="glav">
      				<td align="right" valign="top" style="padding-top: 8px;"><{admin_member_control_account_panel_email_client_field_message}> <span style="color: red;">*</span></td>
      				<td><textarea id="message" style="width: 400px; height: 150px;"
                    onBlur=" console.log(this.createTextRange);"><?php echo $msg; ?></textarea></td>
      			</tr>
          <tr>
            <td></td>
            <td style="padding-top:10px;">
              <select id="addition" style="width:350px;">
                <?php echo $email_keys_str;?>
              </select>
              <input type="button" value="<{admin_member_control_account_panel_email_client_button_add}>" class="button" 
              onClick="insertAtCaret(getElementById('message'), ' ' + $('#addition').val());" />
            </td>
          </tr>
		</table>
          
          <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;"><input type="button" class="button" value="<{admin_member_control_account_panel_email_client_button_send}>" onClick="email_client_send();" /></div>
<br />
