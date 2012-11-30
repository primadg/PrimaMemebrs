<!-- Coupons list page -->

      <div class="body_header">
        <div style="float: left;"><img src="<?php echo base_url();?>img/ico_coupon_create.png" width="32" height="32" alt="<{admin_coupon_coupons_list_page_title}> <?php echo  $coupons_group_name;?>"></div>
        <div class="header_pad">
          <div class="header_subject"><{admin_coupon_coupons_list_page_title}> <?php echo  $coupons_group_name;?></div>
          <div class="header_comment"><{admin_coupon_coupons_list_page_desc}></div>
        </div>
      </div>

      <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>      
      
      <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
      </div>
        <br />
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div  id="error_value" class="box_err"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
      
      
      <table class="tab" align="center" width="700">
        <tr class="glav_big">
          <td width="175"><a href="#" class="sort"
          onClick = "show_coupons(<?php echo  $coupons_group_id;?>,{ord: 'code'});return false;"><{admin_coupon_coupons_list_table_code}></a></td>
          <td width="175"><{admin_coupon_coupons_list_table_count_used}></td>
          <td width="175"><a href="#"
          onClick = "show_coupons(<?php echo  $coupons_group_id;?>,{ord: 'disabled'});return false;"><{admin_coupon_coupons_list_table_disabled}></a></td>
          <td width="175"><{admin_coupon_coupons_list_table_action}></td>
        </tr>

        <?php echo $rows; ?>

      </table>
        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
          <input type="button" class="button" value="<{admin_coupon_coupons_list_button_back}>" onClick="<?php echo $back;?>();" />
        </div>
<br />
