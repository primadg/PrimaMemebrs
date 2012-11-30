<!-- Suspend Reasons list main page -->

        <div class="body_header">
         <div style="float: left;"><img src="<?php echo base_url();?>img/ico32_members.png" width="29" height="32" alt="<{admin_member_control_suspend_reason_manage_page_title}>"></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_suspend_reason_manage_page_title}></div>
            <div class="header_comment"><{admin_member_control_suspend_reason_manage_page_desc}></div>
          </div>
        </div>
        
        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="msg_value" class="box"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        </br>
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br/>
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td><a href="#suspend_reasons " 
            onClick = "suspend_reasons_list({ord: 'by_name'});return false;"><{admin_member_control_suspend_reason_manage_table_name}></a>
            <?php echo $sort_by=='by_name'||$sort_by=="" ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td ><a href="#suspend_reasons" 
            onClick = "suspend_reasons_list({ord: 'by_descr'});return false;"><{admin_member_control_suspend_reason_manage_table_reason}></a>
            <?php echo $sort_by=='by_descr' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td width="60"><{admin_member_control_suspend_reason_manage_table_action}></td>
          </tr>

         <?php echo $rows;?>   
          
        </table>
        
        
        <div class="add">
          <input type="button" class="button_big" value="<{admin_btn_back}>" 
          onClick="load_unapproved_mbr_list();" />
          <input type="button" class="button_big" value="<{admin_member_control_suspend_reason_manage_btn_add_reason}>" 
          onClick="fieldLangsEdit(10,'')" />
        </div>

<br />        

