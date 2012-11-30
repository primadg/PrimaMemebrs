<?php
/*********************************************************************************
*   Edited by Konstantin X @ 13.05.2008
**********************************************************************************/
?>
<div id='main_panel_div'>
    <div id='temp_vars_set'style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>
    <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_security_page_title}></div>
            <div class="header_comment"><{admin_config_security_page_description}></div>
          </div>
    </div>
        <?php echo admin_print_msg_box('msg',$messages); ?>
        <?php echo admin_print_msg_box('emsg',$mess_err); ?>
        <br />
        <table class="settings table_pos">
                <tr class="glav">
                    <td align="center" colspan=3><h3><{admin_config_security_login_settings}></h3></td>
                </tr>
                <tr class="glav">
                    <td align="right" class="table_first_td"><label for="login_remember_me"><{admin_config_security_login_feat_remember_me}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td>
                        <input type="checkbox" id="login_remember_me" name="login_remember_me"<?php if($login_remember_me == 1) echo " checked";?>>
                    </td>
                    <td><?php echo create_tooltip_div('admin_config_security_ttip_remember_me')?>
                        <!-- If enabled user will can use "remeber me" feature, but it will be less secure -->
                    </td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="login_try_capcha"><{admin_config_security_login_before_captcha}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="login_try_capcha" name="login_try_capcha" size="10" style="width: 100px;" value="<?php echo $login_try_capcha;?>"></td>
                    <td><?php echo create_tooltip_div('admin_config_security_ttip_before_captcha')?>
                        <!-- Number of failed logins befaore CAPTCHA appears on login form --></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="login_try_block_ip"><{admin_config_security_login_before_ip}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="login_try_block_ip" name="login_try_block_ip" size="10" style="width: 100px;" value="<?php echo $login_try_block_ip;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_before_ip')?>
                        <!--Number of failed logins before temporary IP block apply--></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="ip_block_timeout"><{admin_config_security_login_block_period}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="ip_block_timeout" name="ip_block_timeout" size="10" style="width: 100px;" value="<?php echo $ip_block_timeout;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_block_period')?>
                        <!-- How many minutes IP will be blocked --></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="ip_block_selected_period"><{admin_config_security_block_selected_period}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="ip_block_selected_period" name="ip_block_selected_period" size="10" style="width: 100px;" value="<?php echo $ip_block_selected_period;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_ip_block_selected_period')?>
                        <!-- Period for select IP will be blocked --></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="session_expiration"><{admin_config_security_session_expiration}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="session_expiration" name="session_expiration" size="10" style="width: 100px;" value="<?php echo $session_expiration;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_session_expiration')?>
                        <!-- How many seconds session will be valid --></td>
                </tr>
                  <tr class="glav">
                    <td valign="top" align="right" style="padding-top: 5px;"><label for="login_block_message"><{admin_config_security_login_block_message}>:</label></td>
                    <td> </td>
                    <td><textarea id="login_block_message" name="login_block_message" style="width: 350px; height: 150px;"><?php echo $login_block_message;?></textarea></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_block_message')?>
                        <!-- Message that user will see when IP temporary blocked --></td>
                </tr>
                <tr class="glav">
                    <td align="center" colspan=3><h3><{admin_config_security_captcha_settings}></h3></td>
                </tr>
                <tr class="glav">
                    <td align="right" class="strong"><label for="captcha_char_min"><{admin_config_security_captcha_char_min}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="captcha_char_min" name="captcha_char_min" size="10" style="width: 100px;" value="<?php echo $captcha_char_min;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_captcha_char_min')?>
                        <!-- Minimum number of characters that can be displayed in CAPTCHA number must be more than 1 --></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="captcha_char_max"><{admin_config_security_captcha_char_max}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="captcha_char_max" name="captcha_char_max" size="10" style="width: 100px;" value="<?php echo $captcha_char_max;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_captcha_char_max')?>
                        <!-- Maximum number of characters that can be displayed in CAPTCHA number must be more than previous field value --></td>
                </tr>
                <tr class="glav">
                    <td align="center" colspan=3><h3><{admin_config_security_pwd_settings}></h3></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="autoban_count"><{admin_config_security_pwd_autoban}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="autoban_count" name="autoban_count" size="10" style="width: 100px;" value="<?php echo $autoban_count;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_pwd_autoban')?>
                        <!-- Number of different IP adress that can be access one account before this account will be banned --></td>
                </tr>
                <tr class="glav">
                    <td align="right"><label for="autoban_timeout"><{admin_config_security_pwd_autoban_period}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
                    <td><input type="text" id="autoban_timeout" name="autoban_timeout" size="10" style="width: 100px;" value="<?php echo $autoban_timeout;?>"></td>
                    <td>
                        <?php echo create_tooltip_div('admin_config_security_ttip_pwd_period')?>
                        <!-- Period in wich IP count for autoban must be reached to suspend account --></td>
                </tr>
                <tr>
					<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{admin_btn_save_security}>" onClick="secure_save('<?php echo site_url('config/security_settings')?>', 'save')" />&nbsp;
          				<input type="button" class="button" value="<{admin_btn_cancel_security}>" onClick="secure_save('<?php echo site_url('config/security_settings')?>', 'reload')" />
                    </td>
                </tr>
            </table>
        </div>
        <br />
