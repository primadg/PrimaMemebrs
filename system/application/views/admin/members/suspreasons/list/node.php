

<!--   Suspend Reasons list page node   -->


          <tr class="<?php echo $tr_class;?>">
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['descr'];?></td>
            <td>
              <a href="# " title="<{admin_img_tip_edit}>"
              onClick="fieldLangsEdit(10,'<?php echo $row['id'];?>'); return false;"><img alt="<{admin_img_tip_edit}>" src="<?php echo base_url();?>img/ico_lang.png" width="16" height="16" /></a>&nbsp;
              <a href="# " title="<{admin_img_tip_delete}>";
              onClick="delete_suspend_reason('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_img_tip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
            </td>
          </tr>

