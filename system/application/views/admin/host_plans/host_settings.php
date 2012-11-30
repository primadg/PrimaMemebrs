<?php

/**
 * Hosting settings view
 *
 * @author Korchinskij G.G.
 * @copyright 2009
 */

?>
        <div id='main_panel_div'>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_host_plans_settings_header_subject}></div>
            <div class="header_comment"><{admin_host_plans_settings_header_comment}></div>
          </div>
        </div>

        <div id='temp_vars_set' style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br/>

        <table align="center" class="settings table_pos">
          <tr class="glav">
            <td colspan="2" class="tema" style="margin-left:0px;"><{admin_host_plans_settings_header_general}></td>
          </tr>
	      <tr class="glav">
            <td align="right" class="table_first_td"><{admin_host_plans_settings_host_host}>: <br/><label for='host_host'></label></td>
            <td><input type="text" name="host_host" value="<?php echo (isset($host_host))?output($host_host):"";?>" style="width: 400px" /></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_host_plans_settings_port}>: <br/><label for='host_port'></label></td>
            <td><input type="text" name="host_port" value="<?php echo (isset($host_port))?output($host_port):"";?>" style="width: 80px" /> <input type="button" class="button_save_as_template" value="<{admin_host_plans_settings_btn_test_connection}>" onClick="host_settings_save('<?php echo site_url('config/host_plans_settings_save/test')?>');" /></td>
          </tr>
          <?php /*?><tr class="glav">
            <td align="right"><{admin_host_settings_use_authentication}>:</td>
            <td>
				<{admin_host_settings_yes}><input type="radio" name="host_use_auth" value="true" <?php echo (isset($host_use_auth)&&(($host_use_auth=='1')||($host_use_auth=='true')))?"checked":""?>> <{admin_host_settings_no}><input type="radio" name="host_use_auth" value="false" <?php echo (isset($host_use_auth)&&(($host_use_auth=='0')||($host_use_auth=='false')))?"checked":""?>>
            </td>
          </tr><?php */?>
		 <tr class="glav">
			<td align="right"><{admin_host_plans_settings_username}>: <br/><label for='host_user'></label></td>
			<td><input type="text" name="host_user" value="<?php echo (isset($host_user))?output($host_user):"";?>" style="width: 80px" /></td>
		 </tr>
		 <tr class="glav">
			<td align="right" valign="top"><{admin_host_plans_settings_password}>: <br/><label for='host_pass'></label></td>
			<td>
<?php /*?>  <input type="text" name="host_pass" value="<?php echo (isset($host_pass))?output($host_pass):"";?>" style="width: 80px" />
<?php */?>
                <textarea rows="30" cols="35" name="host_pass" ><?php echo (isset($host_pass))?output($host_pass):"";?>
                </textarea>
            </td>
		</tr>
        <tr>
        	<td colspan="2" align="center" style="padding-top:15px;">
            	<input type="button" class="button" value="<{admin_host_plans_settings_btn_save}>" onClick="host_settings_save('<?php echo site_url('config/host_plans_settings_save/save')?>');" />&nbsp;
          <input type="button" class="button" value="<{admin_host_plans_settings_btn_cancel}>" onClick="host_settings_save('<?php echo site_url('config/host_plans_settings')?>',true);" />
            </td>
        </tr>
        </table>
        </div>
        <br />
