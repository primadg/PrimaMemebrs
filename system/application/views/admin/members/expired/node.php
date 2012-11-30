

<!-- Expired accounts node -->

          <tr class="<?php echo $tr_class; ?>">
            <td><a href="#" title="<{admin_member_control_confirm_suspend_link_title_view_member_info}>"
            onClick="load_member_info('<?php echo $row['id'];?>','load_expired_mbr_list');return false;"><?php echo $row['login'];?></a></td>
            <td><?php echo $row['name'];?> <?php echo $row['last_name'];?></td>
          </tr>
