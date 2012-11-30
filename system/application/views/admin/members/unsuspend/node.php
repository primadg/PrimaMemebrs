
<!-- Unsusped node -->
          <tr class="<?php echo $tr_class; ?>">
            <td><input type="checkbox" 
                id="<?php echo "mbr_id-".$row['id']; ?>" /></td>
            <td><a href="#" title="View member info"
            onClick="load_member_info('<?php echo $row['id'];?>','load_suspended_mbr_list');return false;"><?php echo $row['login'];?></a></td>
            <td><?php echo $row['name'];?> <?php echo $row['last_name'];?></td>
            <td>
 			    <a href="#" title="<{admin_member_control_img_tooltip_view}>"
                onClick="load_member_info('<?php echo $row['id'];?>','load_suspended_mbr_list');return false;"><img alt="<{admin_member_control_img_tooltip_view}>" src="<?php echo base_url();?>img/ico_coupon.png" width="16" height="16" /></a>
				<a href="#" title="<{admin_member_control_img_tooltip_unsuspend}>"
                onClick="unsuspend_member('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_unsuspend}>" src="<?php echo base_url();?>img/ico_approve_decline16.png" width="16" height="16" /></a>&nbsp;
                <a href="#" title="<{admin_member_control_img_tooltip_delete}>"
                onClick="delete_suspmember('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
            </td>
          </tr>
