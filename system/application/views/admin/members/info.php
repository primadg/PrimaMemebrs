
        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_account_info_big.png" width="32" height="32"alt="View member"></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_member_info_view_label}>&nbsp;<?php if(isset($items['login_title'])){echo $items['login_title'];}?></div>
            <div class="header_comment"><{admin_member_control_member_info_view_label_desc}></div>
          </div>
        </div>
        
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="msg_value" class="box"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br />
        
        <?php
        if(isset($error) && $error != '')
        {
        ?>
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="error_value" class="box_err">
          <?php echo $error;?>
          </div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>        
        <?php
        }
        ?>
        
          <table align="center"  class="info_memb table_pos">
            <tr>
              <td align="right" style="font-size: 16px; padding:10px;" class="table_first_td">
                <strong><{admin_member_control_member_info_view_block_title_user_info}></strong>
              </td>
              <td></td>
           <tr>
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_login}></td>
              <td><?php if(isset($items['login'])){echo $items['login'];}?></td>
            </tr>
            <tr> 
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_expiration_date}></td>
              <td><?php echo isset($items['expire_date']) ? nsdate($items['expire_date'],false) :"";?></td>
            </tr>
            <tr> 
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_email}></td>
              <td><?php if(isset($items['email'])){echo $items['email'];}?></td>
            </tr>
            <tr>
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_name}></td>
              <td><?php if(isset($items['name']) && isset($items['last_name'])){echo $items['name']." ".$items['last_name'];}?></td>
            </tr>
            <tr>
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_groups}></td>
              <td><?php if(isset($items['groups'])){echo $items['groups'];}?></td>
            </tr>
            <?php 
            if( isset($add_field_values) && count($add_field_values) > 0 )
            {
                foreach( $add_field_values as $key=>$val )
                {
                    ?>
                    <tr>
                    <td valign="top" class="info_memb_left"><?php echo soft_wrap(output($val['field']['name']));?>: </td>
                    <td valign="top"><?php echo isset($val['user_value']) ? implode("<br/>",explode("\n",output($val['user_value']))) : "";?></td>
                    </tr>
                    <?php 
                }
            }
            ?>
            <tr>
              <td align="right" class="tema1"style="font-size: 16px; padding:10px; padding-top:20px;"><strong><{admin_member_control_member_info_view_block_title_summary}></strong></td>
              <td></td>
            </tr>
            <tr>
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_user_payments}></td>
              <td><?php if(isset($items['summary']['num'])){echo $items['summary']['num'];}?></td>
            </tr>
            <tr>
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_payment_total}></td>
              <td><?php if(isset($items['summary']['total'])){echo $items['summary']['total'] . ' ' . $items['summary']['currency'];}?></td>
            </tr>
            <tr> 
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_active_subscribtions}></td>
              <td><?php if($items['summary']['active_num']){echo $items['summary']['active_num'];}?></td>
            </tr>
            <tr> 
              <td class="info_memb_left"><{admin_member_control_member_info_view_field_registered_date}></td>
              <td><?php echo isset($items['reg_date']) ? nsdate($items['reg_date'],false) : "";?></td>
            </tr>
            <tr>
            	<td colspan="2" align="center" style="padding-top:15px;">
                	<input type="button" class="button" value="<{admin_member_control_member_info_view_button_back}>" onClick="<?php echo $back;?>();" />
                </td>
            </tr>
          </table>
<br />
