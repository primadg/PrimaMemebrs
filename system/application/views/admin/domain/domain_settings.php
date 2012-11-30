<?php

/**
 * domain settings view
 *
 * @author 
 * @copyright 2009
 */

?>
        <div id='main_panel_div'>      
			<div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
			</div>        
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_domain_settings_header_subject}></div>
            <div class="header_comment"><{admin_domain_settings_header_comment}></div>
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
            <td colspan="3" class="tema" style="margin-left:0px;"><{admin_domain_settings_header_general}></td>
          </tr>	      
 <tr class="glav">
			<td align="right" class="table_first_td"><{admin_domain_settings_username}>: <br/></td>
			<td><input type="text" name="service_username" value="<?php echo (isset($service_username))?output($service_username):"";?>" style="width: 400px" /></td>
            <td><?php echo create_tooltip_div('admin_domain_ttip_001')?><!--username--></td>
		 </tr>
		 <tr class="glav">
			<td align="right"><{admin_domain_settings_password}>: <br/></td>
			<td><input type="password" name="service_password" value="<?php echo (isset($service_password))?output($service_password):"";?>" style="width: 400px" /></td>
            <td><?php echo create_tooltip_div('admin_domain_ttip_002')?><!--pass--></td>
		</tr>         
	 <tr class="glav">
			<td align="right"><{admin_domain_parent_id}>: <br/></td>
			<td><input type="text" name="service_parentid" value="<?php echo (isset($service_parentid))?output($service_parentid):"";?>" style="width: 400px" /></td>
            <td><?php echo create_tooltip_div('admin_domain_ttip_003')?><!--service_parentid--></td>
		 </tr>	
    <tr class="glav">
    <td align="right"><{admin_domain_settings_customerlangpref}>    </td>
    <td><select name="service_langpref">
     <?php 
                    foreach($service_lang as $lang)
                    {
                    ?>    
    <option <?php echo $lang['language_selected']?> value="<?php echo $lang['language_code']?>" ><?php echo $lang['language_name'] ?></option>
                    <?php
                    }
      ?>    
    </select>
    </td><td><?php echo create_tooltip_div('admin_domain_ttip_004')?><!--language--></td>
    </tr>
    <tr class="glav">
        <td align="right">     </td>
        <td><input <?php if(($https_url=='true')or($https_url==1)) echo "checked";?> type="checkbox" name="https_url" value="1" /> <{https_url}></td>
        <td><?php echo create_tooltip_div('admin_domain_ttip_005')?><!--https_url--></td>
    </tr>
    <tr class="glav">
        <td align="right"></td>
        <td><input <?php if(($debug=='true')or($debug==1)) echo "checked";?> type="checkbox" name="debug" value="1" /> <{debug}></td>
         <td><?php echo create_tooltip_div('admin_domain_ttip_006')?><!--domain_debug--></td>
    </tr>
        <tr>
        	<td colspan="3" align="center" style="padding-top:15px;">
            <input type="button" class="button_save_as_template" value="<{admin_domain_settings_btn_test_connection}>" onClick="domain_settings_save('<?php echo site_url('config/domain_settings_save/test')?>');" />&nbsp;
            	<input type="button" class="button" value="<{admin_domain_settings_btn_save}>" onClick="domain_settings_save('<?php echo site_url('config/domain_settings_save/save')?>');" />&nbsp;
          <input type="button" class="button" value="<{admin_domain_settings_btn_cancel}>" onClick="domain_settings_save('<?php echo site_url('config/domain_settings')?>',true);" />
            </td>
        </tr>
        </table>
        </div>
                <br />
