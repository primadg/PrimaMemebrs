


        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_add_member_big.png" width="32" height="32" alt="Add Suspend Reason"/></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_suspend_reason_addedit_page_title}></div>
            <div class="header_comment"><{admin_member_control_suspend_reason_addedit_page_desc}></div>
          </div>
        </div>
        
        
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div  id="error_value" class="box_err"></div>
          <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_member_control_error_empty_fields}></div>
          <div  id="jsvalid_error_name_toolong" class="box_err" style="display: none"><{admin_member_control_suspend_reason_error_name_toolong}></div>
          <div  id="jsvalid_error_description_toolong" class="box_err" style="display: none"><{admin_member_control_suspend_reason_error_description_toolong}></div>          
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>

        
        <table class="settings" align="center" style="margin-top: 10px;" >
          <tr class="glav">
            <td align="right"><{admin_member_control_suspend_reason_addedit_label_name}> <span style="color: red;">*</span></td>
            <td><input type="text" style="width: 350px;" maxlength="255"
                        id="sr_name" 
                        value="<?php echo (isset($items['name'])) ? $items['name'] : '' ;?>" /></td>
          </tr>
          <tr class="glav">
            <td align="right" valign="top" style="padding-top:8px;"><{admin_member_control_suspend_reason_addedit_label_description}> <span style="color: red;">*</span><br>
			<small><{admin_member_control_suspend_reason_addedit_label_description_tip}></small></td>
            <td><textarea style="width:350px;" id="sr_descr"><?php echo (isset($items['descr'])) ? $items['descr'] : '' ;?></textarea></td>
          </tr>
        </table>
        
        <input type="hidden" id="sr_act" value="<?php echo $action;?>" />
        
        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
          <input type="button" class="button" value="<{admin_btn_save}>" onClick="<?php echo $to_save;?>"/>
          <input type="button" class="button" value="<{admin_btn_cancel}>" onClick="suspend_reasons_list();"/>
        </div>
<br />
