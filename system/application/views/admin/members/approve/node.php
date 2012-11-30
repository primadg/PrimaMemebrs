
          <tr class="<?php echo $tr_class; ?>">
            <td><input type="checkbox" 
                id="<?php echo "mbr_id-".$row['id']; ?>" /></td>
            <td><a href="#" title="<{admin_member_control_approve_suspend_link_title_view_member_info}>"
            onClick="load_member_info('<?php echo $row['id'];?>','load_unapproved_mbr_list');return false;"><?php echo $row['login'];?></a></td>
            <td><?php echo $row['name'];?> <?php echo $row['last_name'];?></td>
			<td><select id="<?php echo "sreason_id-".$row['id']; ?>" style="width: 150px;">
            <option value="0"><{admin_member_control_approve_suspend_suspend_reason_select_option_no_reason}></option>
            <?php 
                foreach($suspend_reasons_list as $reason)
                {
            ?>    
            <option value="<?php echo $reason['id']; ?>"
                <?php if($reason['id'] == $row['suspend_reason_id']){echo " selected ";} ?>
                ><?php echo $reason['name']; ?></option>
            <?php
                }
            ?>
			</select></td>
            <td>
			  <a href="#" title="<{admin_member_control_img_tooltip_view}>"
              onClick="load_member_info('<?php echo $row['id'];?>','load_unapproved_mbr_list');return false;"><img alt="<{admin_member_control_img_tooltip_view}>" src="<?php echo base_url();?>img/ico_coupon.png" width="16" height="16" /></a>
              <a href="#" title="<{admin_member_control_img_tooltip_approve}>"
              onClick="approve_member('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_confirm}>" src="<?php echo base_url();?>img/ico_approve_decline16.png" width="16" height="16" /></a>&nbsp;
              <a href="#" title="<{admin_member_control_img_tooltip_suspend}>"
              onClick="suspend_member('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_suspend}>" src="<?php echo base_url();?>img/ico_approve_decline_del.png" width="16" height="16" /></a>&nbsp;
            </td>
          </tr>
          
