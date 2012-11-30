<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/ico_adm_control_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_administrators_list_header_subject}></div>
            <div class="header_comment"><{admin_administrators_list_header_comment}></div>
          </div>
        </div>        
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>       
        <br />
        <?php 
        if(isset($is_perm)&&$is_perm)
        {
            ?>
            <div class="page">
            <?php  echo $pagers['pager'][0]; ?>
            </div> 
            <?php 
        }
        ?>
        <table class="tab" align="center" width="95%">
          <tr class="glav_big">
            <td width="45%"><a href="#" id="login"><{admin_administrators_list_user_name}></a></td>
            <td width="23%"><a href="#" id="access_level"><{admin_administrators_list_level}></a></td>
            <td width="20%"><a href="#" id="last_online"><{admin_administrators_list_last_login}></a></td>
            <td width="12%"><{admin_administrators_list_action}></td>
          </tr>          
        <?php 
        if(isset($admin_list)&&is_array($admin_list)&&count($admin_list))
        {
            $flag=true;
            foreach($admin_list as $admin)
            {				
                ?>
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td class="left"><?php echo isset($admin['login'])?output($admin['login']):""?></td>
                <td><?php echo isset($admin['access_level'])?output($admin['access_level']):""?></td>
                <td><?php echo isset($admin['last_online'])?nsdate($admin['last_online']):""?></td>
                <td>
                <a style="cursor:pointer;" onclick="adminEdit(<?php echo $admin['id']?>); return false;" title="<{admin_admin_edit}> '<?php echo isset($admin['login'])?output($admin['login']):""?>'"><img alt="<{admin_admin_edit}> '<?php echo isset($admin['login'])?output($admin['login']):""?>'" src="<?php echo base_url()?>img/page_edit16.png" width="16" height="16" /></a>&nbsp;
                <?php 
                if(isset($admin['id'])&&$admin['id']!=$admin_id)
                {
                    ?>
                    <a style="cursor:pointer;"  onclick="adminDelete(<?php echo $admin['id']?>); return false;" title="<{admin_btn_delete}>"><img alt="<{admin_btn_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
                    <?php 
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
            <div class="add">
            <input type="button" class="button_save_as_template" value="<{admin_administrators_list_btn_add}>" onClick="myOnAdd();" />
            </div>
            <div class="page">
            <?php  echo $pagers['pager'][1]; ?>          
            </div>
            <?php 
        }
        ?>
      </div>
      <br />
