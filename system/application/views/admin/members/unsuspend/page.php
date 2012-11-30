
<!-- Unsusped page -->
        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_approve_decline.png" width="32" height="32" alt="list of suspended members"/></div>
          <div class="header_pad">
              <div class="header_subject"><{admin_member_control_unsuspend_delete_label}></div>
              <div class="header_comment"><{admin_member_control_unsuspend_delete_label_desc}></div>
          </div>
        </div>
        
        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        <div  id="unsuspend_question" style="display: none"><{admin_msg_unsuspend_question}></div>
        
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
            <td width="20"><input id="setall" type="checkbox" 
                onClick="set_checked_all('mbr_id-');" /></td>
            <td>
            <a href="#" onClick = "load_suspended_mbr_list({ord: 'by_login'});return false;">
            <{admin_member_control_unsuspend_delete_table_login}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_login', true);?>
            </td>
            <td>
            <a href="#" onClick = "load_suspended_mbr_list({ord: 'by_fullname'});return false;">
            <{admin_member_control_unsuspend_delete_table_name}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_fullname');?>            
            </td>
            <td width="100">
            <{admin_member_control_unsuspend_delete_table_action}>
            </td>
          </tr>
            <?php echo $rows;?>
        </table>
          
        <div class="add">
          <input type="button" class="button_big" value="<{admin_member_control_unsuspend_delete_button_unsuspend}>" onClick="unsuspend_member_list();" />&nbsp;
          <input type="button" class="button_big" value="<{admin_member_control_unsuspend_delete_button_delete}>" onClick="delete_suspmember();" />
        </div>
          
        <div class="page">
            <?php echo $pager_node2;?>
        </div>

<br />        
