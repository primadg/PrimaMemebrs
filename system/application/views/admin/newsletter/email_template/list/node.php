<!-- Newsletter Email Templates List  node page -->


          <tr class="<?php echo $tr_class;?>">
            <td class="left"><?php echo $row['name'];?></td>
            <td>
              <a href="#" title="<{admin_img_tip_edit}>"
              onClick="load_template_edit_form('<?php echo $row['id'];?>');return false;"><img alt="<{admin_img_tip_edit}>" src="<?php echo base_url();?>img/ico_settings.png" width="16" height="16" /></a>&nbsp;
              <a href="#" title="<{admin_img_tip_delete}>"
              onClick="delete_email_template('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_img_tip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
            </td>
          </tr>
