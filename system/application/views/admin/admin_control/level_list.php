<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/ico_adm_control_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_levels_header_subject}></div>
            <div class="header_comment"><{admin_levels_header_comment}></div>
          </div>
        </div> 
           <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
           <?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
           
        <table class="tab" align="center" width="700" style="margin-top:15px;">
        <tr class="glav_big">
        <td width="150"><{admin_levels_name_level}></td>
        <td width="200"><{admin_levels_access_category}></td>
        <td width="200"><{admin_levels_email_newsletter}></td>
        <td width="150"><{admin_levels_action}></td>
        </tr>        
        <?php 
        if(isset($levels_list)&&is_array($levels_list)&&count($levels_list))
        {
            $flag=true;
            foreach($levels_list as $level)
            {				
                ?>          
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td valign="top"style="padding-top:10px;"><?php echo isset($level['name'])?output($level['name']):""?></td>
                <td align="left" style="padding-bottom:10px;">
                <ul>
                <?php 
                if(isset($level['ACL'])&&is_array($level['ACL']))
                {                    
                    foreach($level['ACL'] as $value)
                    {				
                        ?><li><?php echo isset($value)?$value:""?></li><?php
                    }
                }
                ?>
                </ul>
                </td>
                <td align="left" style="padding-bottom:10px;">
                <ul>
                <?php 
                if(isset($level['ML'])&&is_array($level['ML']))
                {                    
                    foreach($level['ML'] as $value)
                    {				
                        ?><li><?php echo isset($value)?$value:""?></li><?php
                    }
                }
                ?>
                </ul>
                </td>
                <td>
                <?php 
                if(isset($is_perm)&&$is_perm)
                {
                    ?>
                    <a style="cursor:pointer;" onclick="levelEdit(<?php echo $level['id']?>); return false;" title="<{admin_level_list_edit}> '<?php echo isset($level['name'])?output($level['name']):""?>'"><img alt="<{admin_level_list_edit}> '<?php echo isset($level['name'])?output($level['name']):""?>'" src="<?php echo base_url()?>img/ico_settings.png" width="16" height="16" /></a>&nbsp;
                    <?php 
                    if($level['id']!=1)
                    {
                    ?>
                    <a style="cursor:pointer;" onclick="levelDelete(<?php echo $level['id']?>); return false;" title="<{admin_btn_delete}>"><img alt="<{admin_btn_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
                    <?php 
                    }
                }
                ?>   
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
        </table>
        <?php 
        if(isset($is_perm)&&$is_perm)
        {
            ?>
            <div class="after_table" style="padding-top: 10px; padding-bottom: 20px; text-align: right;">
            <input type="button" class="button_big" value="<{admin_levels_btn_add}>" onClick="myOnAdd()"/>
            </div> 
            <?php 
        }
        ?>        
      </div>
      <br />
