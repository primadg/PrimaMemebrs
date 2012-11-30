<br/><br/><br/>
<table class="tab" align="center" width="700">
	<tr class="glav_big" onclick="hideTableContent(this);">
		<td width="180"><{admin_config_ban_ip_table_ban_ip}> <?php echo create_tooltip_div('admin_config_ban_ip_table_ban_ip_tooltip');?><!--IP must be in following formats 123.123.123.123 OR 123.123.123.* OR 123.123.123.123 - 255.255.255.255--></td>
		<td><{admin_config_ban_ip_table_ban_reason}> <?php echo create_tooltip_div('admin_config_ban_ip_table_ban_reason_tooltip');?><!--text that user will see when try to login from banned IP--></td>
		<td width="70"><{admin_config_ban_ip_table_action}></td>
	</tr>
	<tr class="light">
		<td valign="top" style="padding: 12px;">
        	<input type="text" id="ip" name="ip" maxlength="31" style="width: 150px;" value="<?php if(isset($ip)){echo $ip;}?>" /><label for='ip'></label>
		</td>
		<td align="left" style="padding: 12px;">
        	<div class="resizable-textarea">
                <textarea id="reason" name="reason" style="width: 400px; height: 100px;"><?php if(isset($reason)){echo $reason;}?></textarea>
            </div>
            <label for='reason'></label>
		</td>
		<td>
			<input type="button" value="<{admin_config_ban_ip_button_add}>" class="button" onClick="add_ip();" />
		</td>
	</tr>
</table>
