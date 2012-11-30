<div>
       <div id='temp_vars_set'style="display:none;"><?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?></div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_settings_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_edit_sys_template_header_subject}><?php echo isset($name)?output($name):""?></div>
            <div class="header_comment"><{admin_edit_sys_template_header_comment}></div>
          </div>
       </div>        
       <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
       <table class="settings" align="center" style="margin-top: 10px;">    			
          <tr class="glav">
    				<td align="right" style="width: 120px;"><label for='subject'><{admin_edit_sys_template_subject}><span style="color: red;">*</span></label></td>
    				<td><input name="subject" type="text" style="width: 400px;" value="<?php echo isset($subject)?output($subject):""?>"/></td>
    			</tr>
    			<tr class="glav">
				<td valign="top" align="right" style="padding-top: 5px;width: 120px;"><label for='message'><{admin_edit_sys_template_message}><span style="color: red;">*</span></label> 
				</td>
    				<td valign="top">
					<textarea name="message" style="width: 400px; height: 30px;"><?php echo isset($message)?output($message):""?></textarea>
					</td>
    			</tr>
          <tr>
            <td></td>
            <td style="padding-top:10px;">
			<select id="constants" style="width:350px;">
			<?php 
			if(isset($constants)&&is_array($constants))
			{
				reset($constants);
				foreach($constants as $value)
				{
					?><option value="<?php echo output($value)?>"><?php echo output($value)?></option><?php
				}
			}
			?>
			</select>
              <input type="button" value="<{admin_edit_sys_template_btn_add}>" class="button" onClick="myOnAdd()"/>
            </td>
          </tr>
  	    </table>
        
        <div class="after_table" style="padding-top: 10px;">
		<input type="button" class="button" value="<{admin_edit_sys_template_btn_cancel}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>',true)" />&nbsp;
		<input type="button" class="button" value="<{admin_edit_sys_template_btn_save}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>')" />&nbsp;
		<input type="button" class="button_save_as_template" value="<{admin_edit_sys_template_btn_reset}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>',true,'reset')" />
		<input type="button" class="button_save_as_template" value="<{admin_edit_sys_template_btn_save_default}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>',false,'save')" />
		</div>
<br/>		
</div>
