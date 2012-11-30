<!-- Send Email Step 2 -->


        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_send_email_step2_page_title}></div>
            <div class="header_comment"><{admin_newsletter_send_email_step2_page_desc}></div>
          </div>
        </div>
         
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <div  id="js_msg_emails_is_enqueued" class="box" style="display: none"><{admin_newsletter_send_email_msg_email_is_enqueued}></div>          
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>        
        <br />
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>        
            <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_empty}></div>
            <div  id="jsvalid_error_name_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_name_toolong}></div>
            <div  id="jsvalid_error_subject_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_subject_toolong}></div>            
            <div  id="jsvalid_error_message_toolong" class="box_err" style="display: none"><{admin_newsletter_email_templates_error_field_message_toolong}></div>            
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        
        
        <table class="settings" align="center" style="margin-top: 10px;">
          <tr class="glav">
    				<td align="right"><{admin_newsletter_send_email_step2_label_subject}> <span style="color: red;">*</span></td>
    				<td><input id="subject" type="text" 
                    style="width: 400px;" maxlength="254"
                    value="<?php echo $template['subject'];?>" /></td>
    			</tr>
    			<tr class="glav">
    				<td valign="top" align="right" style="padding-top: 5px;">
						<{admin_newsletter_send_email_step2_label_message}> <span style="color: red;">*</span>
                     </td>
    				<td valign="top"><textarea id="message" style="width: 400px; height: 250px;"><?php echo $template['message'];?></textarea></td>
    			</tr>
          <tr>
            <td></td>
            <td style="padding-top:10px;">
              <select id="addition" style="width:350px;">
                <?php echo $email_keys_str;?>
              </select>
              <input type="button" value="<{admin_btn_add}>" class="button" 
              onClick="insertAtCaret(getElementById('message'), ' ' + $('#addition').val());" />
            </td>
          </tr>
  	    </table>
        
        <div class="after_table" style="padding-top: 10px;">
          <input type="button" class="button_big" value="<{admin_btn_send_now}>" onClick="send_email('now'); return false;" />&nbsp;
          <input type="button" class="button_save_as_template" value="<{admin_btn_save_as_template}>" onClick="update_template('<?php echo $template['id'];?>')" />
          <input type="button" class="button" value="<{admin_btn_enqueue}>" 
onClick="send_email('later'); return false;" />
        </div>
<br />
