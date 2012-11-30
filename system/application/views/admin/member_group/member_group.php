    <div id="main_panel_div">
    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>
    <div class="body_header">
    <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico32_members.png" width="32" height="32" /></div>
    <div class="header_pad">
    <div class="header_subject"><{admin_member_group_page_title}></div>
    <div class="header_comment"><{admin_member_group_page_desc}></div>
    </div>
    </div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
        <?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        <br/>
        <?php if(isset($pagers)){?><div class="page"><?php  echo $pagers['pager'][0]; ?></div><?php }?>
        <table class="tab" align="center" width="95%">
          <tr class="glav_big">
            <td width="10%"><a href="#" id="id"><{admin_member_group_id}></a></td>
            <td width="38%"><a href="#" id="name"><{admin_member_group_name}></a></td>
            <td width="20%"><a href="#" id="users"><{admin_member_group_users}></a></td>
            <td width="20%"><a href="#" id="products"><{admin_member_group_products}></a></td>
            <td width="12%"><{admin_member_group_action}></td>
          </tr>       
        <?php 
        if(isset($group_list)&&is_array($group_list)&&count($group_list))
        {
            $flag=true;
            foreach($group_list as $value)
            {				
                ?>
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td><?php echo isset($value['id']) ? output($value['id']) : ""?></td>
                <td class="left" title="<?php echo isset($value['title']) ? output($value['title']) : ""?>"><?php echo isset($value['name']) ? output($value['name']) : ""?></td>
                <td>
                <a href="#" title="Members list" onclick="groupMembersList(<?php echo $value['id']?>); return false;"><?php echo isset($value['users']) ? output($value['users']) : ""?></a>
                </td>
                <td>
                <a href="#" title="Visible products list" onclick="groupProductsList(<?php echo $value['id']?>,'visible'); return false;"><?php echo isset($value['products']) ? output($value['products']) : ""?></a> / <a href="#" title="Available products list" onclick="groupProductsList(<?php echo $value['id']?>,'available'); return false;"><?php echo isset($value['available_products']) ? output($value['available_products']) : ""?></a>
                </td>
                <td>
                <a style="cursor:pointer;" title="<{admin_member_group_action_edit}>" onclick="fieldLangsEdit(15,<?php echo $value['id']?>); return false;"><img src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" /></a>&nbsp;
                <?php if(intval($value['id'])!=1){?>
                <a style="cursor:pointer;" title="Delete group" onclick="deleteMemberGroup(<?php echo $value['id']?>,<?php echo (intval($value['users'])>0||intval($value['products'])>0) ? "true" : "false"?>); return false;"><img src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
                <?php }?>
                </td>
                </tr>                
                <?php                
            }
        }
        else
        {
            ?>
            <tr class="dark">
            <td colspan="6"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
        ?>
        </table>
        <br />
        <div class="add">
        <input type="button" class="button_super_big" value="<{admin_member_group_btn_add}>" onClick="fieldLangsEdit(15,'');"/>
        </div>
        <?php if(isset($pagers)){?><div class="page"><?php  echo $pagers['pager'][1]; ?></div><?php }?>
        <br/>
    </div>
