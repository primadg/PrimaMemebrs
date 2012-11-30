<tr class="light">
	<td>
    <?php
    if( isset($field_row['name']) and !empty($field_row['name']) )
    {
        echo output($field_row['name']);
    }
    else
    {
        echo "&nbsp;";
    }
    ?></td>
	<td>
    <?php
    if( isset($field_row['type']) and !empty($field_row['type']) )
    {
        switch($field_row['type'])
        {
            case "1":
            echo "<{admin_config_add_fields_field_type_text}>";
            break;
            
            case "2":
            echo "<{admin_config_add_fields_field_type_select_single}>";
            break;
            
            case "3":
            echo "<{admin_config_add_fields_field_type_select_multiple}>";
            break;
            
            case "4":
            echo "<{admin_config_add_fields_field_type_textarea}>";
            break;
            
            case "5":
            echo "<{admin_config_add_fields_field_type_radio}>";
            break;
            
            case "6":
            echo "<{admin_config_add_fields_field_type_checkbox}>";
            break;
            
            default:
            echo intval($field_row['type']);
            break;
        }
    }
    else
    {
        echo "&nbsp;";
    }
    ?>
    </td>
	<td>
    <?php
    if( isset($field_row['check_rule']) and !empty($field_row['check_rule']) )
    {
        switch($field_row['check_rule'])
        {
            case "":
            echo "--";
            break;
            
            case "1":
            echo "<{admin_config_add_fields_check_rule_not_empty}>";
            break;
            
            case "2":
            echo "<{admin_config_add_fields_check_rule_numbers_only}>";
            break;
            
            case "3":
            echo "<{admin_config_add_fields_check_rule_letters_only}>";
            break;
            
            case "4":
            echo "<{admin_config_add_fields_check_rule_email}>";
            break;
            
            case "5":
            echo "<{admin_config_add_fields_check_rule_chars_interval}>";
            break;
            
            case "6":
            echo "<{admin_config_add_fields_check_rule_phone}>";
            break;
        
            default:
            echo intval($field_row['check_rule']);
            break;
        }
    }
    else
    {
        echo "&nbsp;";
    }
    ?>
    </td>
        <td>
        <a onclick="load_panel('<?php echo site_url('config/add_field_edit')?>',{'id':'<?php echo intval($field_row['id']); ?>'}); return false;" href="#" title="<{admin_btn_edit}>">
        <img width="16" height="16" src="<?php echo base_url(); ?>img/ico_settings.png" alt="<{admin_btn_edit}>"/></a>&nbsp;<a onclick="if(confirm('<{admin_config_add_fields_remove_field_confirm}>')){admin_config_remove_field_request('<?php echo site_url('config/add_field_remove')?>','<?php echo intval($field_row['id']); ?>'); return false;}else{return false;}" href="#" title="<{admin_btn_delete}>"><img width="16" height="16" src="<?php echo base_url(); ?>img/ico_delete.png" alt="<{admin_btn_delete}>"/>
		</a>
    </td>
</tr>
