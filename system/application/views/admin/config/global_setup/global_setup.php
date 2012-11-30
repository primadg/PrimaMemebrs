<div id='main_panel_div'>
			<div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
			</div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_global_setup_header_subject}></div>
            <div class="header_comment"><{admin_global_setup_header_comment}></div>
          </div>
        </div>        
		<?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>	   
		<br />        
        <table class="settings table_pos" align="center">
  			<tr class="glav">
  				<td align="right" class="table_first_td"><label for='site_name'><{admin_global_setup_field_label_001}></label></td>
                <td> <span style="color: red;">*</span> </td>
  				<td><input id='site_name' name='site_name' type="text" value="<?php echo isset($site_name)?output($site_name):""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_001')?><!--site title--></td>
  			</tr>
  			<tr class="glav">
  				<td align="right"><label for='base_url'><{admin_global_setup_field_label_002}></label></td>
                <td> <span style="color: red;">*</span> </td>
  				<td><input type="text" name="base_url" value="<?php echo isset($base_url)?output($base_url):""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_002')?><!--Root URL--></td>
  			</tr>
  			<tr class="glav">
  				<td align="right"><label for='absolute_path'><{admin_global_setup_field_label_003}></label></td>
                <td> <span style="color: red;">*</span> </td>
  				<td><input type="text" name="absolute_path"  value="<?php echo isset($absolute_path)?output($absolute_path):""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_003')?><!--Site Root server path--></td>
  			</tr>
  			<tr class="glav">
  				<td align="right"><label for='logout_redirect'><{admin_global_setup_field_label_004}></label></td>
                <td></td>
  				<td><input type="text" name='logout_redirect'  value="<?php echo isset($logout_redirect)?output($logout_redirect):""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_004')?><!--URL where user will be redirected after logout link clicked--></td>
  			</tr>
            <tr class="glav">
  				<td align="right"><label for='login_page'><{admin_global_setup_field_login_page}></label></td>
                <td></td>
  				<td><input type="text" name='login_page'  value="<?php echo isset($login_page)?output($login_page):""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_login_page')?><!--URL where user will be logged in--></td>
  			</tr>
            <tr class="glav">
  				<td align="right"><label for='login_redirect'><{admin_global_setup_field_login_redirect}></label></td>
                <td></td>
  				<td>
                <input type="text" name='login_redirect'  value="<?php echo isset($login_redirect)?output($login_redirect):""?>" style="width: 400px;"><br/>
                <div style="float: left;"><input type="checkbox" name="personal_login_redirect_flag" style="border: 0px;" <?php echo (isset($personal_login_redirect_flag)&&intval($personal_login_redirect_flag))?"checked":""?>/></div>
  				<div style="float: left; padding-top: 2px; padding-right: 10px;"><{admin_global_setup_field_label_personal_login_redirect_flag}></div>
  				</td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_login_redirect')?><!--URL where user will be redirected after login link clicked--></td>
  			</tr>
  			<tr class="glav">
  				<td align="right"><{admin_global_setup_field_label_005}></td>
                <td><span style="color: red;">*</span></td>
  				<td>	
	
				<select name="perpage_list" style="width: 50px;">
				<?php 
				$default_perpage=isset($default_perpage)?$default_perpage:"";
                $perpage_array=explode(",",$perpage_list);
				reset($perpage_array);
				foreach($perpage_array as $per_page)
				{
				?>
				<option value="<?php echo output($per_page)?>" <?php echo ($per_page==$default_perpage)?"selected":""?>><?php echo output($per_page)?></option>
				<?php
				}
				?>				
				</select></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_005')?><!--Choose number of records per page--></td>
  			</tr>
  			<tr class="glav">
  				<td align="right"><label for='site_ip'><{admin_global_setup_field_label_006}></label></td>
                <td> <span style="color: red;">*</span> </td>
  				<td><input type="text" name="site_ip"  value="<?php echo isset($site_ip)?$site_ip:""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_006')?><!--IP address, need to be set for mod rewrite protection--></td>
  			</tr>
            
            <tr class="glav">
  				<td align="right"><label for='date_format'><{admin_global_setup_date_format}></label></td>
                <td> <span style="color: red;">*</span> </td>
  				<td><input type="text" name="date_format"  value="<?php echo isset($date_format)?$date_format:""?>" style="width: 400px;"></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_date_format')?><!----></td>
  			</tr>
            <!-- The function has been replaced by (Member page constructor)
  			<tr class="glav">
  				<td align="right"><{admin_global_setup_field_label_007}></td>
                <td></td>
  				<td><input type="checkbox" name="member_force_pwd_gen" style="border: 0px;" <?php echo (isset($member_force_pwd_gen)&&$member_force_pwd_gen=="true")?"checked":""?>/></td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_007')?></td>
  			</tr>
            -->
  			<tr class="glav">
  				<td align="right"><{admin_global_setup_field_label_008}></td>
                <td> <span style="color: red;"></span> </td>
  				<td>
            
  					<div style="float: left;"><input type="checkbox" name="log_members" style="border: 0px;" <?php echo (isset($log_members)&&$log_members=="true")?"checked":""?>/></div>
  					<div style="float: left; padding-top: 2px; padding-right: 10px;"><{admin_global_setup_field_label_009}></div>
  					<div style="float: left;"><input type="checkbox" name="log_admins" style="border: 0px;" <?php echo (isset($log_admins)&&$log_admins=="true")?"checked":""?>/></div>
  					<div style="float: left; padding-top: 2px;"><{admin_global_setup_field_label_010}></div>
            
  				</td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_010')?><!--Enable/disable log feature--></td>
  			</tr>
            <tr class="glav">
                <td align="right">
                	<label for="history_kept"><{admin_global_setup_field_label_013}></label>
                </td>
                <td></td>
                <td>
                	<input type="text" value="<?php echo isset($history_kept)?$history_kept:""?>" name="history_kept" maxlength="3" size="3" />
                </td>
                <td>
                	<?php echo create_tooltip_div('admin_global_setup_ttip_013')?><!--History is kept, days-->
                </td>
            </tr>
            <?php 
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_config_global_ignor_ext')===true){
            //*******End of functionality limitations********
            ?>
  			<tr class="glav">
  				<td valign="top" style="padding-top: 10px;"><{admin_global_setup_field_label_011}></td>
                <td></td>
  				<td>
  				
  				<table>
  				<tr>
  					<td>
  					
  					<select name="ignored_extensions" style="width: 200px; height: 100px;" multiple id="ignorsel">
		              <?php 
				$ignor_exts=explode(",",$ignored_extensions);
                reset($ignor_exts);
                foreach($ignor_exts as $ignor_ext)
                {
                    if($ignor_ext!="")
                    {
                        ?>
                        <option value="<?php echo output($ignor_ext)?>"><?php echo output($ignor_ext)?></option>
                        <?php
                    }
                }
				?>
  					</select>  					
  					<div style="text-align: center; width: 200px; padding-top: 10px;">
  						<input type="button" class="button_big" onclick="selector.selectAll('ignorsel');" value="<{admin_btn_select_all}>" />
  						<input type="button" class="button_big" onclick="selector.delSelected('ignorsel');" value="<{admin_btn_delete}>" />
  					</div>
  					
  					</td>
  					<td style="border: 0px;" valign="top">
  					
  					<div style="text-align: center; width: 200px;">
  						<input id="ext_text" name="ext_text"  type="text" style="width: 120px;">
  						<input type="button" class="button" onclick="addExt();" value="<{admin_btn_add}>" /><br/><label for='ext_text'></label>
  					</div>
  					</td>
  				</tr>
  				</table>
  				
  				</td>
				<td><?php echo create_tooltip_div('admin_global_setup_ttip_012')?><!--User can imput file extansions that will be showed directly to users not using mod rewrite protection--></td>
  			</tr>
            <?php }?>
            <tr>
            	<td colspan="4" align="center" style="padding-top:15px;">
                	<input type="button" class="button" value="<{admin_btn_save}>" onClick="global_save('<?php echo site_url('config/global_setup_save')?>')" />&nbsp;<input type="button" class="button" value="<{admin_btn_cancel}>" onClick="global_save('<?php echo site_url('config/global_setup')?>',true)" />
                </td>
            </tr>
  			</table>
      </div>      
<br />
