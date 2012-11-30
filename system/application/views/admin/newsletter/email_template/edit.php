<!-- Newsletter Updating Email Templates -->

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_settings_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_email_templates_edit_page_title}> <?php echo $name2display;?></div>
            <div class="header_comment"><{admin_newsletter_email_templates_edit_page_desc}></div>
          </div>
        </div>
        
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_empty}></div>
            <div  id="jsvalid_error_name_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_name_toolong}></div>
            <div  id="jsvalid_error_name_only_spaces" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_template_name_only_spaces}></div>
            <div  id="jsvalid_error_subject_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_subject_toolong}></div>            
            <div  id="jsvalid_error_subject_only_spaces" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_subject_only_spaces}></div>
            <div  id="jsvalid_error_message_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_message_toolong}></div>                        
          <div  id="jsvalid_error_message_only_spaces" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_message_only_spaces}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
          </div>
        
        <table class="settings" align="center" style="margin-top: 10px;">
    			<tr class="glav">
    				<td align="right"><{admin_newsletter_email_templates_add_label_template_name}> <span style="color: red;">*</span></td>
    				<td><input type="text" style="width: 400px;" 
                    id="tpl_name" maxlength="254" 
                    value="<?php echo $name;?>" /></td>
    			</tr>
          <tr class="glav">
    				<td align="right"><{admin_newsletter_email_templates_add_label_subject}> <span style="color: red;">*</span></td>
    				<td><input type="text" style="width: 400px;" 
                    id="tpl_subject" maxlength="254" 
                    value="<?php echo $subject;?>" /></td>
    			</tr>
    			<tr class="glav">
    				<td valign="top" align="right" style="padding-top: 5px;">
              <{admin_newsletter_email_templates_add_label_message}> <span style="color: red;">*</span>
            </td>
    				<td valign="top"><textarea style="width: 400px; height: 250px;"
                    id="tpl_message" ><?php echo $message;?></textarea></td>
    			</tr>
          <tr>
            <td></td>
            <td style="padding-top:10px;">
              <select id="addition" style="width:350px;">
                <?php echo $email_keys_str;?>
              </select>
              <input type="button" value="<{admin_btn_add}>" class="button" 
              onClick="insertAtCaret(getElementById('tpl_message'), ' ' + $('#addition').val());" />
            </td>
          </tr>
          <tr>
            <td></td>
            <td style="color:#8D8D8D;" valign="top"><{admin_newsletter_select_email_keys_desc}></td>
          </tr>
  	    </table>
        
        <div class="after_table" style="padding-top: 10px;"><input type="button" class="button" value="<{admin_btn_save}>" onClick="save_template('<?php echo $id;?>')" />&nbsp;<input type="button" class="button" value="<{admin_btn_cancel}>" onClick="email_template_list({})" /></div>
       
<br />
