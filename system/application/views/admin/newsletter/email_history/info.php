<!-- Newsletter Email History View -->

        <div id="main_panel_div">
    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_email_history_view_page_title}></div>
            <div class="header_comment"><{admin_newsletter_email_history_view_page_desc}></div>
          </div>
        </div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
        <?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        <br />
          <table class="settings table_pos" align="center">
      			<tr class="glav">
      				<td align="right" width="200" valign="top" class="table_first_td"><strong><{admin_newsletter_email_history_view_label_to}></strong></td>
      				<td><?php echo $email_to;?></td>
      			</tr>
                <tr class="glav"> 
      				<td align="right"><strong><{admin_newsletter_email_history_view_label_subject}> </strong></td>
      				<td><?php echo $subject;?></td>
      			</tr>
      			<tr class="glav">
      				<td align="right" valign="top" style="padding-top: 4px;"><strong><{admin_newsletter_email_history_view_label_message}></strong></td>
      				<td width="350"><?php echo $message;?></td>
      			</tr>
                <tr>
                	<td colspan="2" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{admin_btn_back}>" onClick="myPagerHandler({},'email_history');" />
                    </td>
                </tr>
     			</table>
<br />
