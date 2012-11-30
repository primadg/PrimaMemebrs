<?php
/*********************************************************************************
*	Edited by Konstantin X @ 16:49 28.05.2008
**********************************************************************************/
?>
<div id='main_panel_div'>
<div id='temp_vars_set' style="display:none;">
<?php echo isset($temp_vars_set) ? create_temp_vars_set($temp_vars_set):"";?>
</div> 
<div class="body_header">
	  <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
	  <div class="header_pad">
		<div class="header_subject"><{admin_config_status_page_title}></div>
		<div class="header_comment"><{admin_config_status_page_description}></div>
	  </div>
</div>
<?php echo admin_print_msg_box('msg',$messages); ?>
<?php echo admin_print_msg_box('emsg',$mess_err); ?>
<br />
<table class="settings" align="center">
	<tr class="glav"> 
		<td colspan="2" align="center"><h3><{admin_status_current}> <?php echo $online ? '<span style="color: #00AA15;"><{admin_status_online}></span>' : '<span style="color: #FF0000;"><{admin_status_offline}></span>';?></h3>
			<input type="button" class="button" value="<?php echo $online ? '<{admin_btn_switch_off}>' : '<{admin_btn_switch_on}>';?>" onClick="stat_switch('<?php echo site_url('config/status_settings')?>')" />
		</td>
	</tr>
	<tr> 
		<td colspan="2"><hr /></td>
	</tr>
	<tr> 
		<td valign="top"><label for="offline_msg"><{admin_status_offline_msg}></label></td>
		<td><textarea id="offline_msg" name="offline_msg" style="width: 450px; height: 100px;"><?php echo $offline_msg;?></textarea></td>
	</tr>
</table>
<div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
  <input type="button" class="button" value="<{admin_btn_save_status}>" onClick="msg_save('<?php echo site_url('config/status_settings')?>', 'save')" />&nbsp;
  <input type="button" class="button" value="<{admin_btn_cancel_status}>" onClick="msg_save('<?php echo site_url('config/status_settings')?>', 'reload')" />
</div>
</div>
<br />
