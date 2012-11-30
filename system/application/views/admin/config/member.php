<?php
/**
 * Member Settings view
 *
 * @author Makarenko Sergey
 * @copyright 2008
 */

?>
            <div id='temp_vars_set' style="display:none;">
            <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
            </div>


        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_settings_header_subject}></div>
            <div class="header_comment"><{admin_member_settings_header_comment}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />

        <!--
        <div class="mess" style="width: 500px; margin: 0 auto; padding-bottom: 10px;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box"><{admin_member_settings_saved_successfully}><?php echo admin_print_msg_box('msg',$messages);?></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br />
        <div class="mess_err" style="width: 500px; margin: 0 auto;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div class="box_err"><{admin_member_settings_error}>: <?php echo admin_print_msg_box('emsg',$mess_err);?></div>
         <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br />
        -->

        <table class="settings table_pos" width="520px">
          <tr class="glav">
            <td align="right" width="250px" class="table_first_td"><{admin_member_settings_allow_register}></td>
            <td></td>
            <td>
                <input type="checkbox" name="member_allow_register" <?php echo (isset($member_allow_register)&&$member_allow_register==1)?"checked":""?> />
            </td>
            <td></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_member_settings_need_activation}></td>
            <td></td>
            <td>
                <input type="checkbox" name="member_need_activation" <?php echo (isset($member_need_activation)&&$member_need_activation==1)?"checked":""?> />
            </td>
            <td></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_member_settings_approve_needed}></td>
            <td></td>
            <td>
                <input type="checkbox" name="member_approve_needed" <?php echo (isset($member_approve_needed)&&$member_approve_needed==1)?"checked":""?> />
            </td>
            <td></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_member_settings_force_billing_info_input}></td>
            <td></td>
            <td>
                <input type="checkbox" name="member_force_billing_info_input" <?php echo (isset($member_force_billing_info_input)&&$member_force_billing_info_input==1)?"checked":""?> />
            </td>
            <td></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_member_settings_autosubscribe_free_products}></td>
            <td></td>
            <td>
                <input type="checkbox" name="member_autosubscribe_free_products" <?php echo (isset($member_autosubscribe_free_products)&&$member_autosubscribe_free_products==1)?"checked":""?> />
                </td>
                <td valign="top" style="padding-top:10px;"><?php echo create_tooltip_div('admin_member_settings_autosubscribe_free_products_ttip')?></td>                
          </tr>
          <?php if(Functionality_enabled('admin_config_member_simple_menu')===true){?>
          <tr class="glav">
            <td align="right"><{admin_member_settings_simple_menu}></td>
            <td></td>
            <td>
                <input type="checkbox" id="member_simple_menu" name="member_simple_menu" <?php echo (isset($member_simple_menu)&&$member_simple_menu==1)?"checked":""?> />
            </td>
            <td></td>
          </tr>
          <?php  } ?>
          <tr class="glav">
            <td align="right"><{admin_member_settings_email_as_login}></td>
            <td></td>
            <td>
            
                <input type="checkbox" id="member_email_as_login" name="member_email_as_login" <?php echo (isset($member_email_as_login)&&$member_email_as_login==1)?"checked":""?> />
            
            </td>
            <td valign="top" style="padding-top:10px;"><?php echo create_tooltip_div('admin_member_settings_email_as_login_ttip')?></td>            
          </tr>
          <tr class="glav">
            <td align="right"><label for='member_exp_subscr_notif_period'><{admin_member_settings_exp_subscr_notif_period}></label></td>
            <td></td>
            <td >
               <input type="text" size="3" style="margin-left:3px;" maxlength="3" name="member_exp_subscr_notif_period" value="<?php echo isset($member_exp_subscr_notif_period)?$member_exp_subscr_notif_period:""?>" />
            </td>
            <td></td>
          </tr>
          <tr class="glav">
            <td align="right" valign="top" style="padding-top: 10px;">
              <{admin_member_settings_trusted_email}>
            </td>
            <td></td>
            <td>
            <table>
                <tr>
                    <td style="border: 0px;" valign="top">
                    <div style="width: 290px;">
                        <input id="trust_text" name="trust_text"  type="text" style="width: 190px;">
                        <input type="button" class="button" onclick="addEmail('trust');" value="<{admin_member_settings_btn_add}>" /><br/><label for='trust_text'></label>
                    </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    <select name="trust_emails" style="width: 200px; height: 100px;" multiple id="trustsel">
                    <?php
                        if (isset($trusted_emails) && is_array($trusted_emails))
                        {
                            foreach($trusted_emails as $trusted_email)
                            {
                    ?>
                        <option value="<?php echo output($trusted_email['domain'])?>"><?php echo output($trusted_email['domain'])?></option>
                    <?php
                            }
                        }
                    ?>
                    </select>
                    <div style="text-align: left; width: 200px; padding-top: 10px;">
                        <input type="button" class="button_big" onclick="selector.selectAll('trustsel');" value="<{admin_member_settings_btn_select_all}>" />
                        <input type="button" class="button_big" onclick="delEmail('trustsel');" value="<{admin_member_settings_btn_delete}>" />
                    </div>
                    </td>
                    
             	</tr>
            </table>

            </td>
            <td valign="top" style="padding-top: 10px;"><?php echo create_tooltip_div('admin_member_settings_trusted_email_ttip')?></td>
          </tr>
          <tr class="glav">
            <td align="right" valign="top" style="padding-top: 10px;">
              	<{admin_member_settings_denied_email}>
            </td>
            <td></td>
            <td>
            <table>
            	<tr>
                	<td style="border: 0px;" valign="top">
                    <div style="text-align: left; width: 290px;">
                        <input id="denied_text" name="denied_text"  type="text" style="width: 190px;">
                        <input type="button" class="button" onclick="addEmail('denied');" value="<{admin_member_settings_btn_add}>" /><br/><label for='denied_text'></label>
                    </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    <select name="denied_emails" style="width: 200px; height: 100px;" multiple id="deniedsel">
                    <?php
                        if (isset($denied_emails) && is_array($denied_emails))
                        {
                            foreach($denied_emails as $denied_email)
                            {
                    ?>
                        <option value="<?php echo output($denied_email['domain'])?>"><?php echo output($denied_email['domain'])?></option>
                    <?php
                            }
                        }
                    ?>
                    </select>
                    <div style="text-align: center; width: 200px; padding-top: 10px;">
                        <input type="button" class="button_big" onclick="selector.selectAll('deniedsel');" value="<{admin_member_settings_btn_select_all}>" />
                        <input type="button" class="button_big" onclick="delEmail('deniedsel');" value="<{admin_member_settings_btn_delete}>" />
                    </div>
                    </td>
                    
              	</tr>
            </table>
            </td>
            <td valign="top" style="padding-top: 10px;"><?php echo create_tooltip_div('admin_member_settings_denied_email_ttip')?></td>            
          </tr>
          <tr class="glav">
		  		<td colspan="4" align="center" style="padding-top:15px;">
                	<input type="button" class="button" value="<{admin_member_settings_btn_save}>" onClick="member_settings_save('<?php echo site_url('config/member_settings_save')?>');" />&nbsp;
          			<input type="button" class="button" value="<{admin_member_settings_btn_cancel}>" onClick="member_settings_save('<?php echo site_url('config/member_settings')?>',true);" />
                </td>
          </tr>
        </table>
        <br />
