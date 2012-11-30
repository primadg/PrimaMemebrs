<div id='main_panel_div'>

    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_host_plans_list_title}></div>
            <div class="header_comment"><{admin_host_plans_list_description}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$ok_messages); ?>
		<?php echo admin_print_msg_box('emsg',$error_messages); ?>
        <br />

        <div class="page"><?php echo $pagers['pager'][0];?></div>

        <div class="add"><input type="button" class="button_super_big" value="<{admin_host_plans_list_add_host_plan}>" onClick="add_host_plan();" /></div>

        <table class="tab" align="center" width="700" id="dir_list">
          <tr class="glav_big" style="font-wieght: bold;">
            <td width="30"><a href="#" id="id"><{admin_host_plans_list_id}></a></td>
            <td><a href="#" id="name"><{admin_host_plans_list_host_plan_name}></a></td>
            <td width="80"><a href="#" id="num_of_products"><{admin_host_plans_list_number_of_products}></a></td>
            <td width="80"><{admin_host_plans_list_action}></td>
          </tr>
<?php
if(isset($host_plans) && @count($host_plans))
{
foreach($host_plans as $i=>$host_plan)
{
?>
          <tr class="<?php echo ($i%2)?'dark':'light'?>">
            <td><?php echo $host_plan['id']?></td>
            <td class="left" title="<?php echo output($host_plan['name'])?>"><?php echo output(word_wrap($host_plan['name'],35,4))?></td>
            <td><?php echo $host_plan['num_of_products']?></td>
            <td>
              <a class="handpointer" onClick="edit_host_plan(<?php echo $host_plan['id']?>)"><img alt="<{admin_host_plans_list_action_edit}>" title="<{admin_host_plans_list_action_edit}> '<?php echo output($host_plan['name'])?>'" src="<?php echo base_url()?>img/page_edit16.png" width="16" height="16" /></a>&nbsp;
              <a class="handpointer" onClick="reprotect_host_plan(<?php echo $host_plan['id']?>)"><img alt="<{admin_host_plans_list_action_reprotect}>" title="<{admin_host_plans_list_action_reprotect}>" src="<?php echo base_url()?>img/ico_transaction.png" width="16" height="16" /></a>&nbsp;
              <a class="handpointer" onClick="delete_host_plan(<?php echo $host_plan['id']?>)"><img alt="<{admin_host_plans_list_action_delete}>" title="<{admin_host_plans_list_action_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>
            </td>
          </tr>
          <?php if(isset($delete_host_plan) && $delete_host_plan==$host_plan['id']){?>
          <tr class="<?php echo ($i%2)?'dark':'light'?>">
            <td><img src="<?php echo base_url()?>img/ico_warn2.png" width="18" height="18" /></td>
            <td colspan=4 style="text-align:left;"><span style="float:left; font-weight:bold;"><span style="color:#FF7D00;"><{admin_host_plans_list_assotiated_products}> </span><?php echo isset($assotiated_products)&&is_array($assotiated_products) ? implode(", ",$assotiated_products) : "";?></span><input style="float:right; right:0px;" class="button_super_big" type="button" value="<{admin_host_plans_list_btn_delete_anyway}>" onclick="delete_host_plan(<?php echo $host_plan['id']?>,true);"/></td>
          </tr>
          <?php }?>
<?php
}
}
else
{
?>
        <tr class="dark">
            <td colspan="5"><{admin_host_plans_list_table_empty}></td>                                
        </tr>
        <?php 
}
?>
        </table>
        <div class="add"> <?php /*<span id="reprotect_progress"><{host_plans_reprotect_comment}></span>
        <select id="reprotect_period" style="width: 100px;">
        <option value="0"><{host_plans_reprotect_period_1_second}></option>
        <option value="1"><{host_plans_reprotect_period_10_minute}></option>
        <option value="2"><{host_plans_reprotect_period_1_hour}></option>
        <option value="3"><{host_plans_reprotect_period_1_day}></option>
        <option value="4"><{host_plans_reprotect_period_1_week}></option>
        <option value="5"><{host_plans_reprotect_period_1_month}></option>
        </select>
        <input type="button" class="button_super_big" value="<{host_plans_reprotect_all}>" onClick="reprotect_all();" /> */ ?>
        <input type="button" class="button_super_big" value="<{admin_host_plans_list_add_host_plan}>" onClick="add_host_plan();" /></div>

        <div class="page"><?php echo $pagers['pager'][1];?></div>

      </div>
      <br />
