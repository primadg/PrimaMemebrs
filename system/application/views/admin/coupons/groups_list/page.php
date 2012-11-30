<!-- Coupons groups list -->

<script type="text/javascript" >
var per_page = <?php echo $per_page;?>;
</script>
 
      <div class="body_header">
        <div style="float: left;"><img src="<?php echo base_url();?>img/ico_coupon_create.png" width="32" height="32" alt="<{admin_coupon_coupon_groups_page_title}>"></div>
        <div class="header_pad">
          <div class="header_subject"><{admin_coupon_coupon_groups_page_title}></div>
          <div class="header_comment"><{admin_coupon_coupon_groups_page_desc}></div>
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
          <td width="20"><{admin_coupon_coupon_groups_table_id}></td>
          <td width="200"><a href="#" 
          onClick = "coupons_group_list({ord: 'coupons_count'});return false;"><{admin_coupon_coupon_groups_table_coupons_count}></a><?php echo $sort_by=='coupons_count' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
          <td width="96"><a href="#" 
          onClick = "coupons_group_list({ord: 'begin_date'});return false;"><{admin_coupon_coupon_groups_table_begin_date}></a><?php echo ($sort_by=='begin_date'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
          <td width="96"><a href="#" 
          onClick = "coupons_group_list({ord: 'expire_date'});return false;"><{admin_coupon_coupon_groups_table_expire_date}></a><?php echo $sort_by=='expire_date' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
          <td width="96"><{admin_coupon_coupon_groups_table_count_used}></td>
          <td width="96"><{admin_coupon_coupon_groups_table_discount}></td>
          <td width="96"><a href="#" 
          onClick = "coupons_group_list({ord: 'disabled'});return false;"><{admin_coupon_coupon_groups_table_disabled}></a><?php echo $sort_by=='disabled' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
          <td width="96"><{admin_coupon_coupon_groups_table_action}></td>
        </tr>
        
        <?php echo $rows; ?>
        
      </table>
        
      <div class="add"><input type="button" class="button_super_big" value="<{admin_coupon_coupon_groups_button_create_coupons}>" onClick="clickMenu(5,2);" /></div>
        
      <div class="page">
        <?php echo $pager_node2;?>      
      </div>
 <br />
