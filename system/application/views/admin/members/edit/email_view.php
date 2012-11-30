
          <table class="settings" align="center" width="100%">
      			<tr class="glav">
      				<td align="right" width="100"><{admin_member_control_account_panel_email_history_view_label_to}></td>
      				<td><?php echo $email_to;?></td>
      			</tr>
      			<tr class="glav"> 
      				<td align="right"><{admin_member_control_account_panel_email_history_view_label_from}> </td>
      				<td><?php echo $email_from;?></td>
            </tr>
      			<tr class="glav"> 
      				<td align="right"><{admin_member_control_account_panel_email_history_view_label_subject}> </td>
      				<td><?php echo $subject;?></td>
      			</tr>
      			<tr class="glav">
      				<td align="right" valign="top" style="padding-top: 8px;"><{admin_member_control_account_panel_email_history_view_label_message}></td>
      				<td><?php echo $message;?></td>
      			</tr>
   			</table>
        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;"><input type="button" class="button" value="<{admin_member_control_account_panel_email_history_view_button_back}>" onClick="load_email_history_list();" /></div>

<br />
