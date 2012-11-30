        <br />
        <?php echo isset($message_box) ? admin_print_msg_box('msg',$message_box) : "";?>
		<?php echo isset($error_box) ? admin_print_msg_box('emsg',$error_box) : "";?>      
        <div class="tema"><{admin_member_control_account_panel_change_password_title}></div>
        <table class="settings" align="center" width="500">
      			<tr>
      				<td colspan="4"><{admin_member_control_account_panel_change_password_desc}></td>
      			</tr>
      			<?php echo admin_print_fields($fields,"admin_member_control_account_panel_change_password");?>
    			</table>
          <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;"><input type="button" class="button_save_as_template" value="<{admin_member_control_account_panel_change_password_button_update_password}>" onClick="myOnPasswordSave(<?php echo intval($id)?>);" /></div>
<br />
