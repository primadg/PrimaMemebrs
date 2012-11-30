        <div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
			<div style="float: left;">
				<img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" />
			</div>
			<div class="header_pad">
				<div class="header_subject"><{admin_config_add_fields_label}></div>
				<div class="header_comment"><{admin_config_add_fields_labe_descr}></div>
			</div>
        </div>
        <div>
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?> 
        </div>        
        <br />
        <table id="add_fields" class="tab" align="center" width="700" >
        <thead>
        <tr class="glav_big nodrag nodrop">
        <th><{admin_config_add_fields_table_title}></th>
        <th><{admin_config_add_fields_table_field_type}></th>
        <th><{admin_config_add_fields_table_check_rule}></th>
        <th width="70"><{admin_config_add_fields_table_action}></th>
        </tr> 
        </thead>
        <tbody>
        <?php 
        if(isset($fields)&&is_array($fields)&&count($fields))
        {
            $flag=false;
            foreach($fields as $field)
            {				
                ?>
                <tr id="<?php echo 'field_'.intval($field['id']);?>" class="<?php echo $flag?"light":"dark";?>">
                <td class="left"><?php echo isset($field['name'])?output($field['name']):""?></td>
                <td><?php echo isset($field['type'])?(array_key_exists($field['type'],$field_type)?$field_type[$field['type']]:$field['type']):""?></td>
                <td><?php echo isset($field['check_rule'])?(array_key_exists($field['check_rule'],$check_rule)?$check_rule[$field['check_rule']]:$field['check_rule']):""?></td>
                <td>
                <a onclick="fieldEdit('<?php echo intval($field['id']);?>'); return false;" href="#" title="<{admin_field_edit}> '<?php echo isset($field['name'])?output($field['name']):""?>'"><img width="16" height="16" src="<?php echo base_url();?>img/ico_settings.png" alt="<{admin_field_edit}> '<?php echo isset($field['name'])?output($field['name']):""?>'"/></a>&nbsp;
                <?php if(Functionality_enabled('admin_multi_language')===true){?>
                <a style="text-decoration: none;" class="link_buttons" onClick="fieldLangsEdit(11,<?php echo intval($field['id'])?>); return false;" href="#" title="<{admin_config_add_fields_button_lang_alt}>"><img alt="<{admin_config_add_fields_button_lang_alt}>" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" />&nbsp;</a>
                <?php }?>
                <a onclick="fieldDelete('<?php echo intval($field['id']);?>');return false;" href="#" title="<{admin_btn_delete}>"><img width="16" height="16" src="<?php echo base_url(); ?>img/ico_delete.png" alt="<{admin_btn_delete}>"/></a>
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
        </tbody>
        </table>
        <br/>
        <div style="padding-left:25px;"><{admin_config_add_fields_explanation}></div>
        <div class="add">
        <input type="button" class="button_big" value="<{admin_config_add_fields_button_add}>" onClick="fieldAdd();" />
        </div>
        </div>
        <br />
