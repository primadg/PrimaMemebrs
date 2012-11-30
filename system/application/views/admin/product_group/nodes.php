<?php
$odd=1;

    if(isset($groups) && @count($groups))
    {
        foreach($groups as $group)
        {   
            ?>
            
            <tr class="<?php echo (   $odd++%2    ?   "light"   :   "dark")?>">
                <td>
                    <?php echo (int)$group['id']?>
                </td>
                <td class="left">
                    <?php echo output(word_wrap($group['name'], 60, 2))?></td>
                <td>
                    <?php echo (int)$group['p_cnt']?>
                </td>
                <td>                    
                    <a style="text-decoration: none;" class="link_buttons" onClick="fieldLangsEdit(3,<?php echo (int)$group['id']?>); return false;" href="#" title="<{product_list_edit_lang_button_alt}>"><img alt="<{product_list_edit_lang_button_alt}>" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" />&nbsp;</a>
                    <a style="text-decoration: none;" class="link_buttons" onClick="click_delete_group(<?php echo (int)$group['id']?>, <?php echo (int)$group['p_cnt']?>); return false;" href="#" title="<{product_list_delete_button_alt}>"><img alt="<{product_list_delete_button_alt}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" />&nbsp;</a>
                </td>
            </tr>
            
        <?php 
        }
        }
        else
        {
        ?>
        <tr class="dark">
            <td colspan="4"><{admin_table_empty}></td>                                
        </tr>
        <?php 
        }
        ?>
