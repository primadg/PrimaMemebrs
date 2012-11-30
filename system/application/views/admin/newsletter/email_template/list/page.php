<!-- Newsletter Email Templates List  main page -->


        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_email_templates_list_page_title}></div>
            <div class="header_comment"><{admin_newsletter_email_templates_list_page_desc}></div>
          </div>
        </div>
        
        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
      
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        </br>
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>        
        
        <div class="page">
            <?php echo $pager_node1;?>
        </div>
        
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td>
            <a href="#" onClick = "email_template_list({ord: 'name'});return false;">
            <{admin_newsletter_email_templates_list_table_header_name}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'name', true);?>
            </td>
            <td width="60"><{admin_newsletter_email_templates_list_table_header_action}></td>
          </tr>

          <?php echo $rows; ?>
          
        </table>
        
        <div class="after_table" style=" text-align: right;">
          <input type="button" class="button_big" value="<{admin_newsletter_email_templates_list_btn_add_template}>" onClick="add_email_template(); return false;" />&nbsp;
        </div>
        
        <div class="page">
            <?php echo $pager_node2;?>
        </div>

<br />
