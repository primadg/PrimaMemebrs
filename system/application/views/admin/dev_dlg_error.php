<table style="width:100%">
<tr>
<td align="right" style="width:100px;"><label for='dev_dlg_from'><{admin_developer_dialog_error_from}><span style="color: red;">*</span></label></td>
<td align="left"><input name="dev_dlg_from" id="dev_dlg_from" style="width:96%" type="text" value="<?php echo isset($email)?$email:"";?>" />
</td>
<td style="width:185px">
<input id="dev_dlg_btn_attach" class="set_default" type="button" value="<{admin_developer_dialog_error_attach}>" />
<input id="dev_dlg_btn_attach_flag" disabled type="checkbox" value="" />
</td>
</tr>
<tr>
<td align="right"><{admin_developer_dialog_error_name}></td>
<td colspan="2" align="center"><input id="dev_dlg_subject" style="width:97%" type="text" value="<?php echo isset($subject)?$subject:"";?>" /></td>
</tr>
<tr>
<td align="right"><{admin_developer_dialog_error_description}></td>
<td colspan="2" align="center"><textarea id="dev_dlg_description" style="width:97%"></textarea></td>
</tr>
<tr>
<td align="right"></td>
<td colspan="2" align="left"><input id="dev_dlg_browser_info" type="checkbox" checked value="" /><{admin_developer_dialog_error_send_browser_info}>&nbsp;&nbsp;&nbsp;&nbsp;<input id="dev_dlg_server_info" type="checkbox" checked value="" /><{admin_developer_dialog_error_send_server_info}></td>
</tr>
</table>
