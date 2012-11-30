<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/ico_adm_control_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_manage_pages_header_subject}></div>
            <div class="header_comment"><{admin_manage_pages_header_comment}></div>
          </div>
        </div> 
        <div>
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>
        </div>        
        <br />        
            <div class="page">
            <?php  echo $pagers['pager'][0]; ?>
            </div>
        <table id="manage_pages_list" class="tab" align="center" width="95%">
          <thead>
          <tr class="glav_big nodrag nodrop">
            <th width="12%"><{admin_manage_pages_published}></th>
            <th width="52%"><{admin_manage_pages_sid}></th>
            <th width="12%"><{admin_manage_pages_show_in_menu}></th>
            <th width="12%"><{admin_manage_pages_members_only}></th>
            <th width="12%"><{admin_manage_pages_action}></th>
          </tr>
          </thead>
          <tbody>
        <?php 
        if(!isset($pages)||!is_array($pages)||count($pages)==0)
        {
            $pages=array();
            $page=array();
            $page['is_template']=true;
            $page['id']=0;
            $page['sid']='';
            $page['published']=0;
            $page['show_in_menu']=0;
            $page['members_only']=1;
            $pages[0]=$page;
        }
        $flag=false;
        foreach($pages as $page)
        {				
            ?>
            <tr <?php echo isset($page['is_template'])?"style='display:none;'":""?> id="<?php echo 'field_'.intval($page['id']);?>" class="<?php echo $flag?"light":"dark";?>">
            <td style="text-align:center;" class="left">
            <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'published'); return false;">
            <div <?php echo ($page['published']==0)?"class='hidden_png_img'":""?> id="img_published_<?php echo $page['id']?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt=""   width="21" height="21"/></div>
            <div <?php echo ($page['published']>0)?"class='hidden_png_img'":""?> id="img_un_published_<?php echo $page['id']?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="" width="21" height="21"/></div>
            </a>
            </td>
            <td title="<?php echo isset($page['sid']) ? base_url()."user/page/".$page['sid'] : ""?>"><?php echo isset($page['sid'])?output($page['sid']):""?></td>
            <td>
            <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'show_in_menu'); return false;">
            <div <?php echo ($page['show_in_menu']==0)?"class='hidden_png_img'":""?> id="img_show_in_menu_<?php echo $page['id']?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt=""   width="21" height="21"/></div>
            <div <?php echo ($page['show_in_menu']>0)?"class='hidden_png_img'":""?> id="img_un_show_in_menu_<?php echo $page['id']?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="" width="21" height="21"/></div>
            </a>
            </td>
            <td>
            <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'members_only'); return false;">
            <div <?php echo ($page['members_only']==0)?"class='hidden_png_img'":""?> id="img_members_only_<?php echo $page['id']?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt=""   width="21" height="21"/></div>
            <div <?php echo ($page['members_only']>0)?"class='hidden_png_img'":""?> id="img_un_members_only_<?php echo $page['id']?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="" width="21" height="21"/></div>
            </a>
            </td>
            <td>
            <a style="cursor:pointer;" id="link_source_<?php echo $page['id']?>" title="<{admin_manage_pages_copy_link}>" onclick="return false;"><img alt="<{admin_manage_pages_copy_link}>" src="<?php echo base_url()?>img/ico_copy.png" width="16" height="16" /></a>&nbsp;
            <a style="cursor:pointer;" onclick="fieldEdit(<?php echo $page['id']?>, 1); return false;" title="<{admin_page_edit}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'"><img alt="<{admin_page_edit}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" /></a>&nbsp;
            <a style="cursor:pointer;"  onclick="fieldDelete(<?php echo $page['id']?>); return false;" title="<{admin_btn_delete}>"><img alt="<{admin_btn_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
            </td>
            </tr>            
            <?php
        }        
        ?>
        </tbody>
        <tfoot>
        <tr id="empty_row" class="dark" style="<?php echo isset($page['is_template'])?"":"display:none;";?>">
        <td colspan="5"><{admin_table_empty}></td>                                
        </tr>
        </tfoot>
        </table>
            <div class="add">
            <input type="button" class="button_save_as_template" value="<{admin_manage_pages_btn_add}>" <?php  if(Functionality_enabled('admin_config_pages')===true){ echo 'onClick="fieldAdd();"'; } else { echo 'onClick="fieldAdd(1);"'; } ?> />
            </div>
            <div class="page">
            <?php  echo $pagers['pager'][1]; ?>          
            </div>            
      </div>
      <br />
