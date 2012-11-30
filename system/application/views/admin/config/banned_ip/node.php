
<!--  Ban IP list node -->

    <tr class="<?php echo $tr_class;?>">
        <td><?php echo $row['ip'];?></td>
        <td class="left"><?php echo $row['reason'];?></td>
        <td>
            
            <a style="text-decoration: none;" class="link_buttons" onClick="fieldLangsEdit(12,<?php echo (int)$row['id']?>); return false;" href="#"><img title="<{admin_config_ban_ip_tooltip_edit}>" alt="<{product_list_edit_lang_button_alt}>" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" />&nbsp;</a>
            <a href="#" onClick="delete_ip('<?php echo $row['ip'];?>');return false;"><img title="<{admin_config_ban_ip_tooltip_delete}>" alt="Delete" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
        </td>
    </tr>
