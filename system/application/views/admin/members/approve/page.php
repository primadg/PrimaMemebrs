<!--  List of not approved members Main page    -->

        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_approve_decline.png" width="32" height="32" alt="list of not approved members"/></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_approve_suspend_label}></div>
            <div class="header_comment"><{admin_member_control_approve_suspend_label_desc}></div>
          </div>
        </div>
        
        <div  id="suspend_question" style="display: none"><{admin_msg_suspend_question}></div>
        <div  id="approve_question" style="display: none"><{admin_msg_approve_question}></div>
        
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="msg_value" class="box"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br />
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div  id="error_value" class="box_err"></div>
          <div id="jsvalid_error_mbr_notchecked" class="box_err" style="display: none"><{admin_member_control_error_mbr_notchecked}></div>          
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        
        <div class="page">
          <?php echo $pager_node1;?>           
        </div>
        
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="20">
            <input id="setall" type="checkbox" onClick="set_checked_all('mbr_id-');"/>
            </td>
            <td width="100">
            <a href="#" onClick = "load_unapproved_mbr_list({ord: 'by_login'});return false;">
            <{admin_member_control_approve_suspend_table_login}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_login', true);?>
            </td>
            <td >
            <a href="# " onClick = "load_unapproved_mbr_list({ord: 'by_fullname'});return false;">
            <{admin_member_control_approve_suspend_table_name}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_fullname');?>            
            </td>
            <td >
            <{admin_member_control_approve_suspend_table_suspend_reason}>
            <a href="#suspend_reasons" title="<{admin_member_control_img_tooltip_edit}>" onClick="suspend_reasons_list();">
            <img alt="<{admin_member_control_img_tooltip_edit}>" src="<?php echo base_url();?>img/ico_settings.png" width="16" height="16" />
            </a>
            </td>
            <td width="100"><{admin_member_control_approve_suspend_table_action}></td>
          </tr>

        <?php echo $rows; ?>
            
        </table>

        <div class="add">
          <input type="button" class="button_big" value="<{admin_member_control_approve_suspend_button_approve}>" onClick="approve_member_list();" />&nbsp;
          <input type="button" class="button_big" value="<{admin_member_control_approve_suspend_button_suspend}>" onClick="suspend_member_list();" />
        </div>

        <div class="page">
            <?php echo $pager_node2;?>
        </div>
<br />

        

