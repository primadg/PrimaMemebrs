

<!-- Expired accounts page -->

        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_approve_decline.png" width="32" height="32" alt="<{admin_member_control_expired_accounts_page_title}>"/></div>
          <div class="header_pad">
              <div class="header_subject"><{admin_member_control_expired_accounts_page_title}></div>
              <div class="header_comment"><{admin_member_control_expired_accounts_page_desc}></div>
          </div>
        </div>
        
        <div class="page">
            <?php echo $pager_node1;?>
        </div>
          
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td>
            <a href="#" onClick = "load_expired_mbr_list({ord: 'by_login'});return false;">
            <{admin_member_control_expired_accounts_table_login}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_login', true);?>            
            </td>
            <td>
            <a href="#" onClick = "load_expired_mbr_list({ord: 'by_fullname'});return false;">
            <{admin_member_control_expired_accounts_table_name}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_fullname');?>            
            </td>
          </tr>
            <?php echo $rows;?>
        </table>
          
          
        <div class="page">
            <?php echo $pager_node2;?>
        </div>

<br />        
