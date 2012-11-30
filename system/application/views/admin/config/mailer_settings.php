<?php

/**
 * Mailer settings view
 *
 * @author Makarenko Sergey
 * @copyright 2008
 */

?>
        <div id='main_panel_div'>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_mailer_settings_header_subject}></div>
            <div class="header_comment"><{admin_mailer_settings_header_comment}></div>
          </div>
        </div>

        <div id='temp_vars_set' style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br/>

        <table class="settings table_pos" align="center">
          <tr class="glav">
            <td align="center" colspan="3" class="tema"><{admin_mailer_settings_header_general}></td>
          </tr>
          <tr class="glav">
            <td align="right" width="240px" class="table_first_td"><{admin_mailer_settings_outgoing_address}>:<br/><label for='admin_email'></label></td><td><span style="color: red;">*</span> </td>
            <td><input type="text" name="admin_email" value="<?php echo (isset($admin_email))?output($admin_email):"";?>" style="width: 400px" /></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_mailer_settings_email_charset}>: <br/><label for='mailer_charset'></label></td><td></td>
            <td><input type="text" readonly="true" name="mailer_charset" value="<?php echo (isset($mailer_charset))?output($mailer_charset):"";?>" style="width: 400px" /></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_mailer_settings_email_format}>:</td><td></td>
            <td>
              <select name="mailer_in_html" style="width: 410px;">
                <option value="false" <?php echo (isset($mailer_in_html)&&(($mailer_in_html=='0')||($mailer_in_html=='false')))?"selected":""?>><{admin_mailer_settings_format_plain}></option>
                <option value="true" <?php echo (isset($mailer_in_html)&&(($mailer_in_html=='1')||($mailer_in_html=='true')))?"selected":""?>><{admin_mailer_settings_format_html}></option>
              </select>
            </td>
          </tr>

          <tr class="glav">
            <td align="right"><{admin_mailer_settings_email_send_to_count}>: <br/><label for='send_to_count'></label></td><td></td>
            <td><input type="text" name="send_to_count" value="<?php echo (isset($send_to_count))?output($send_to_count):"";?>" style="width: 40px" /></td>
          </tr>


          <tr class="glav">
            <td align="center" colspan="3" class="tema"><{admin_mailer_settings_header_smtp}></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_mailer_settings_use_smtp}>: </td><td></td>
            <td>
				<{admin_mailer_settings_yes}><input type="radio" name="mailer_use_smtp" value="true" <?php echo (isset($mailer_use_smtp)&&(($mailer_use_smtp=='1')||($mailer_use_smtp=='true')))?"checked":""?>> <{admin_mailer_settings_no}><input type="radio" name="mailer_use_smtp" value="false" <?php echo (isset($mailer_use_smtp)&&(($mailer_use_smtp=='0')||($mailer_use_smtp=='false')))?"checked":""?>>
            </td>
          </tr>
	      <tr class="glav">
            <td align="right"><{admin_mailer_settings_smtp_host}>: <br/><label for='mailer_smtp_host'></label></td><td></td>
            <td><input type="text" name="mailer_smtp_host" value="<?php echo (isset($mailer_smtp_host))?output($mailer_smtp_host):"";?>" style="width: 400px" /></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_mailer_settings_smtp_port}>: <br/><label for='mailer_smtp_port'></label></td><td></td>
            <td><input type="text" name="mailer_smtp_port" value="<?php echo (isset($mailer_smtp_port))?output($mailer_smtp_port):"";?>" style="width: 80px" /> </td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_mailer_settings_use_authentication}>:</td><td></td>
            <td>
				<{admin_mailer_settings_yes}><input type="radio" name="mailer_use_auth" value="true" <?php echo (isset($mailer_use_auth)&&(($mailer_use_auth=='1')||($mailer_use_auth=='true')))?"checked":""?>> <{admin_mailer_settings_no}><input type="radio" name="mailer_use_auth" value="false" <?php echo (isset($mailer_use_auth)&&(($mailer_use_auth=='0')||($mailer_use_auth=='false')))?"checked":""?>>
            </td>
          </tr>
		 <tr class="glav">
			<td align="right"><{admin_mailer_settings_username}>: <br/></td><td><label for='mailer_smtp_user'></label></td>
			<td><input type="text" name="mailer_smtp_user" value="<?php echo (isset($mailer_smtp_user))?output($mailer_smtp_user):"";?>" style="width: 80px" /></td>
		 </tr>
		 <tr class="glav">
			<td align="right"><{admin_mailer_settings_password}>: <br/><label for='mailer_smtp_pass'></label></td><td></td>
			<td><input type="password" name="mailer_smtp_pass" value="<?php echo (isset($mailer_smtp_pass))?output($mailer_smtp_pass):"";?>" style="width: 80px" /></td>
		</tr>
        <tr>
        	<td colspan="3" align="center" style="padding-top:15px;">
            <input type="button" class="button_save_as_template" value="<{admin_mailer_settings_btn_test_connection}>" onClick="mailer_settings_save('<?php echo site_url('config/mailer_settings_save/test')?>');" />&nbsp;
            	<input type="button" class="button" value="<{admin_mailer_settings_btn_save}>" onClick="mailer_settings_save('<?php echo site_url('config/mailer_settings_save/save')?>');" />&nbsp;
          		<input type="button" class="button" value="<{admin_mailer_settings_btn_cancel}>" onClick="mailer_settings_save('<?php echo site_url('config/mailer_settings')?>',true);" />
            </td>
        </tr>
        </table>
        </div>
        <br />
