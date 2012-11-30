<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/ico_adm_control_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_manage_news_header_subject}></div>
            <div class="header_comment"><{admin_manage_news_header_comment}></div>
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
            <th width="12%"><{admin_manage_news_published}></th>
            <th width="46%"><a href="#" id="sid"><{admin_manage_news_sid}></a></th>
            <th width="14%"><a href="#" id="date"><{admin_manage_news_date}></a></th>
            <th width="8%"><{admin_manage_news_special}></th>
            <th width="10%"><{admin_manage_news_members_only}></th>
            <th width="10%"><{admin_manage_news_action}></th>
          </tr>
          </thead>
          <tbody>
        <?php 
        if(!isset($news)||!is_array($news)||count($news)==0)
        {
            $news=array();
            $page=array();
            $page['is_template']=true;
            $page['id']=0;
            $page['sid']='';
            $page['published']=0;
            $page['date']=0;
            $page['members_only']=1;
            $news[0]=$page;
        }
        $flag=false;
        foreach($news as $page)
        {				
            $thisdate = isset($page['date'])?nsdate($page['date'],false):"";
            ?>
            <tr <?php echo isset($page['is_template'])?"style='display:none;'":""?> id="<?php echo 'field_'.intval($page['id']);?>" class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
            <td style="text-align:center;" class="left">
            <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'published'); return false;" title="<{admin_news_published}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'">
            <div <?php echo ($page['published']==0)?"class='hidden_png_img'":""?> id="img_published_<?php echo $page['id']?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt="<{admin_news_published}>"   width="21" height="21"/></div>
            <div <?php echo ($page['published']>0)?"class='hidden_png_img'":""?> id="img_un_published_<?php echo $page['id']?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="<{admin_news_published}>" width="21" height="21"/></div>
            </a>
            </td>
            <td><?php echo isset($page['sid'])?output($page['sid']):""?></td>
            <td >
            	<span class='inlineDateEdit'>	
					<?php echo $thisdate?>
               	</span>
                <input style="display:none;" type="text" size="10" name="date_from_<?php echo $page['id']?>" id="dateEntry_<?php echo $page['id']?>" class="inlineDateEditTemp <?php echo datepicker_class();?>" value="<?php echo $thisdate?>"/>
            </td>
            
            <td>
                <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'special_news'); return false;" title="<{admin_news_special_news}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'">
                    <div <?php echo ($page['special_news']==0)?"class='hidden_png_img'":""?> id="img_special_news_<?php echo $page['id']?>"  >
                    	<img src="<?php echo base_url()?>img/favorite.png" alt="<{admin_news_special_news}>"   width="21" height="21"/>
                    </div>
                    <div <?php echo ($page['special_news']>0)?"class='hidden_png_img'":""?> id="img_un_special_news_<?php echo $page['id']?>">
                    	<img src="<?php echo base_url()?>img/favorite_off.png" alt="<{admin_news_special_news}>" width="21" height="21"/>
                    </div>
                </a>
            </td>
            
            <td>
            <a style="cursor:pointer;" onclick="pageAction(<?php echo $page['id']?>,'members_only'); return false;" title="<{admin_news_members_only}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'">
            <div <?php echo ($page['members_only']==0)?"class='hidden_png_img'":""?> id="img_members_only_<?php echo $page['id']?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt="<{admin_news_members_only}>"   width="21" height="21"/></div>
            <div <?php echo ($page['members_only']>0)?"class='hidden_png_img'":""?> id="img_un_members_only_<?php echo $page['id']?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="<{admin_news_members_only}>" width="21" height="21"/></div>
            </a>
            </td>
            <td>
            <a style="cursor:pointer;" onclick="fieldEdit(<?php echo $page['id']?>, 1); return false;" title="<{admin_news_edit}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'"><img alt="<{admin_news_edit}> '<?php echo isset($page['sid'])?output($page['sid']):""?>'" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16"  /></a>&nbsp;
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
            <input type="button" class="button_save_as_template" value="<{admin_manage_news_btn_add}>" onClick="fieldAdd();" />
            </div>
            <div class="page">
            <?php  echo $pagers['pager'][1]; ?>          
            </div>            
      </div>
      <br />
