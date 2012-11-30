
    <div class="tema"><{admin_member_control_account_panel_email_history_page_title}></div>
   
        <div class="page">
            <?php echo $pager_node1; ?>
        </div>
        
        <table class="tab" align="center" width="680">
          <tr class="glav_big">
            <td width="20">#</td>
            <td><a href="#" class="sort"
            onClick = "load_email_history_list({ord: 'by_subject'});return false;"><{admin_member_control_account_panel_email_history_table_subject}></a></td>
            <td width="80"><a href="#"
            onClick = "load_email_history_list({ord: 'by_email'});return false;"><{admin_member_control_account_panel_email_history_table_from}></a></td>
            <td width="80"><a href="#"
            onClick = "load_email_history_list({ord: 'by_date'});return false;"><{admin_member_control_account_panel_email_history_table_date}></a></td>
            <td width="45"><{admin_member_control_account_panel_email_history_table_action}></td>
          </tr>
        <?php 
            if( isset($items) && count($items) > 0 )
            {
                $tr_class = 'dark';
                $i = 0;
                foreach( $items as $row )
                { 
                    $i++;
                    $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
        ?>          
          <tr class="<?php echo $tr_class;?>">
            <td><?php echo $i;?></td>
            <td class="left"><?php echo $row['subject'];?></td>
            <td><?php echo $row['email'];?></td>
            <td class="left"><?php echo isset($row['cdate']) ? nsdate($row['cdate']) :"";?></td>
           <td>
              <a href="#"
              onClick="view_email_info('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_account_panel_email_history_img_alt_view}>" src="<?php echo base_url();?>img/ico_coupon.png" width="16" height="16" /></a>&nbsp;
              <a href="#" 
              onClick="delete_email_history('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_account_panel_email_history_img_alt_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
           </td>
          </tr>
        <?php
                }
            }    
            else
            {
                echo '<tr><td colspan="5"><{admin_msg_no_data}></td></tr>';
            }
        ?>          
        </table>

        <div class="page">
            <?php echo $pager_node2; ?>
        </div>
        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        
<br />        
        
