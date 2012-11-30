<?php
/**
 * Member Settings view
 *
 * @author Zhalybin Roman
 * @copyright 2008
 */

?>

    <br />
    <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_mainpage_header_subject}></div>
            <div class="header_comment"><{admin_config_mainpage_header_comment}></div>
          </div>
        </div>
        <?php echo admin_print_msg_box('msg',isset($message_box)?$message_box:array());?>
		<?php echo admin_print_msg_box('emsg',isset($error_box)?$error_box:array());?>      
        <br />
    <table class="settings" align="center">
          	<tr class="glav">
            	<td align="right">
            		<label for='unreg_page_amount'>	
                		<{admin_config_mainpage_pages}>
               		</label>
           		</td>
            	<td>
                	<input type="text" size="3" maxlength="3" id="field_unreg_page_amount" name="unreg_page_amount" value="<?php echo isset($unreg_page_amount) ? $unreg_page_amount : ''; ?>" />
            	</td>
                
                
                
                <td align="right">
            		<label for='page_amount'>	
                		<{admin_config_mainpage_pages}>
               		</label>
           		</td>
            	<td>
                	<input type="text" size="3" maxlength="3" id="field_page_amount" name="page_amount" value="<?php echo isset($page_amount) ? $page_amount : ''; ?>" />
            	</td>
     		</tr>
        	<tr class="glav">
          		<td align="right">
            		<label for='unreg_news_amount'>
                		<{admin_config_mainpage_news}>
               		</label>
           		</td>
            	<td>
                	<input type="text" size="3" maxlength="3" id="field_unreg_news_amount" name="unreg_news_amount" value="<?php echo isset($unreg_news_amount) ? $unreg_news_amount : ''; ?>" />
            	</td>
                
                
                
                <td align="right">
            		<label for='news_amount'>
                		<{admin_config_mainpage_news}>
               		</label>
           		</td>
            	<td>
                	<input type="text" size="3" maxlength="3" id="field_news_amount" name="news_amount" value="<?php echo isset($news_amount) ? $news_amount : ''; ?>" />
            	</td>
        	</tr>
          	<tr class="glav">
            	<td align="right" valign="top" style="padding-top: 5px;">
              		<{admin_config_mainpage_adm_text}>
            	</td>
            	<td style="vertical-align: top;">
            		<textarea rows="6" cols="30" id="field_unreg_admin_msg" name="unreg_admin_msg" style="margin-right:20px;"><?php echo isset($unreg_admin_msg) ? $unreg_admin_msg : ''; ?></textarea>
            	</td>
                
                
                
                <td align="right" valign="top" style="padding-top: 5px;">
              		<{admin_config_mainpage_adm_text}>
            	</td>
            	<td style="vertical-align: top;">
            		<textarea rows="6" cols="30" id="field_admin_msg" name="admin_msg" style="margin-right:20px;"><?php echo isset($admin_msg) ? $admin_msg : ''; ?></textarea>
            	</td>
          	</tr>
        </table>

        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
          <input type="button" class="button" value="<{admin_config_mainpage_btn_save}>" onClick="member_settings_save('<?php echo site_url('config/design_manager_save')?>', 'save');" />
        </div>
        <br />
        
        <hr style="margin-right:10px;" />
