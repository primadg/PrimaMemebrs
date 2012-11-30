        <br />
        <?php echo isset($message_box) ? admin_print_msg_box('msg',$message_box) : "";?>
		<?php echo isset($error_box) ? admin_print_msg_box('emsg',$error_box) : "";?>      
        <div class="tema"><{admin_member_control_account_panel_member_domain_info_page_title}></div>
          <table class="settings table_pos">
          <?php echo admin_print_fields($fields,"admin_member_control_account_panel_member_domain_info");?>      
                <tr>
                	<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{admin_member_control_account_panel_member_domain_info_button_save}>" onClick="myOnEdit(<?php echo intval($id)?>,'domain_info');" />&nbsp;<input type="button" class="button" value="<{admin_member_control_account_panel_member_domain_info_button_cancel}>" onClick="load_member_list()" />
                    </td>
                </tr>                     
    			</table>
          <br />
