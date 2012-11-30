<div id='main_panel_div'>

    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{directories_list_title}></div>
            <div class="header_comment"><{directories_list_description}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$ok_messages); ?>
		<?php echo admin_print_msg_box('emsg',$error_messages); ?>
        <br />

        <div class="page"><?php echo $pagers['pager'][0];?></div>

        <div class="add"><input type="button" class="button_super_big" value="<{directories_add_directory}>" onClick="add_directory();" /></div>

        <table class="tab" align="center" width="700" id="dir_list">
          <tr class="glav_big" style="font-wieght: bold;">
            <td width="30"><a href="#" id="id"><{directories_list_id}></a></td>
            <td><a href="#" id="name"><{directories_directory_name}></a></td>
            <td><a href="#" id="fs_path"><{directories_protected_directory}></a></td>
            <td width="80"><a href="#" id="num_of_products"><{directories_number_of_products}></a></td>
            <td width="80px"><{directories_action}></td>
          </tr>
<?php
if(isset($directories) && @count($directories))
{
foreach($directories as $i=>$directory)
{
?>
          <tr class="<?php echo ($i%2)?'dark':'light'?>">
            <td><?php echo $directory['id']?></td>
            <td class="left" <?php echo $directory['method_disabled'] ? "style='color:red;' title='".$directory['method']." <{directories_method_disabled}>'" : "title='".output($directory['name'])."'";?> ><?php echo output(word_wrap($directory['name'],30,4))?></td>
            <td class="left" title="<?php echo output($directory['fs_path'])?>"><?php  if (strlen($directory['fs_path'])>30) { echo '...'.output(mb_substr($directory['fs_path'],strlen($directory['fs_path'])-30)); } else { echo output($directory['fs_path']); } ?>
            <td><?php echo $directory['num_of_products']?></td>
            <td>
              <a class="handpointer" onClick="edit_directory(<?php echo $directory['id']?>)"><img alt="<{directories_action_edit}> '<?php echo output(word_wrap($directory['name'],30,4))?>'" title="<{directories_action_edit}> '<?php echo output(word_wrap($directory['name'],30,4))?>'" src="<?php echo base_url()?>img/page_edit16.png" width="16" height="16" /></a>&nbsp;
              <a class="handpointer" onClick="reprotect_directory(<?php echo $directory['id']?>)"><img alt="<{directories_action_reprotect}>" title="<{directories_action_reprotect}>" src="<?php echo base_url()?>img/password.png" width="18" height="18" /></a>&nbsp;
              <a class="handpointer" onClick="delete_directory(<?php echo $directory['id']?>)"><img alt="<{directories_action_delete}>" title="<{directories_action_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>
            </td>
          </tr>
          <?php if(isset($delete_dir) && $delete_dir==$directory['id']){?>
          <tr class="<?php echo ($i%2)?'dark':'light'?>">
            <td><img src="<?php echo base_url()?>img/ico_warn2.png" width="18" height="18" /></td>
            <td colspan=4 style="text-align:left;"><span style="float:left; font-weight:bold;"><span style="color:#FF7D00;"><{directories_assotiated_products}> </span><?php echo isset($assotiated_products)&&is_array($assotiated_products) ? implode(", ",$assotiated_products) : "";?></span><input style="float:right; right:0px;" class="button_super_big" type="button" value="<{directories_btn_delete_anyway}>" onclick="delete_directory(<?php echo $directory['id']?>,true);"/></td>
          </tr>
          <?php }?>
<?php
}
}
else
{
?>
        <tr class="dark">
            <td colspan="5"><{admin_table_empty}></td>                                
        </tr>
        <?php 
}
?>
        </table>
        <div class="add"><span id="reprotect_progress"><{directories_reprotect_comment}></span>
        <select id="reprotect_period" style="width: 100px;">
        <option value="0"><{directories_reprotect_period_1_second}></option>
        <option value="1"><{directories_reprotect_period_10_minute}></option>
        <option value="2"><{directories_reprotect_period_1_hour}></option>
        <option value="3"><{directories_reprotect_period_1_day}></option>
        <option value="4"><{directories_reprotect_period_1_week}></option>
        <option value="5"><{directories_reprotect_period_1_month}></option>
        </select>
        <input type="button" class="button_super_big" value="<{directories_reprotect_all}>" onClick="reprotect_all();" />
        <input type="button" class="button_super_big" value="<{directories_add_directory}>" onClick="add_directory();" /></div>

        <div class="page"><?php echo $pagers['pager'][1];?></div>

      </div>
      <br />
