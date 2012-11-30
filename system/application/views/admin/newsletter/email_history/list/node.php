<!-- Newsletter Email History List - NODE PAGE -->
 
          <tr class="light">
            <td><?php echo $row['i'];?></td>
            <td class="left"><?php echo $row['subject'];?></td>
            <td><?php echo $row['email_from'];?></td>
           <td><?php echo $row['email_to'];?></td>
            <td class="left"><?php echo $row['date'];?> <?php echo $row['time'];?></td>
           <td>
              <a href="#" title="<{admin_img_tip_view}>"
              onClick="email_history_info('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_img_tip_view}>" src="<?php echo base_url();?>img/ico_coupon.png" width="16" height="16" 
               /></a>&nbsp;
              <a href="#" title="<{admin_img_tip_delete}>"
              onClick="email_history_delete('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_img_tip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" 
               /></a>
           </td>
          </tr>
