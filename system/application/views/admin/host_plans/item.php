<?php
    $interface = ($id) ? "edit" : "add";
?>
<div id='main_panel_div'>

    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>

		<link rel="stylesheet" href="<?php echo base_url()?>css/jquery.treeview.css" />

        <div class="body_header">
          <div style="float: left;"><img alt="<{admin_host_plans_item_<?php echo $interface?>_title}>" src="<?php echo base_url()?>img/page_edit32.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_host_plans_item_<?php echo $interface?>_title}></div>
            <div class="header_comment"><{admin_host_plans_item_<?php echo $interface?>_description}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$ok_messages); ?>
		<?php echo admin_print_msg_box('emsg',$error_messages); ?>

        <table class="settings table_pos" style="margin-top: 10px;">
            <input type="hidden" name="id" id="frm_id" value="<?php echo output($id)?>" />
    			<tr class="glav">
    				<td align="right" class="table_first_td"><label for="packages"><{admin_host_plans_item_add_packages}>:</label></td>
                    <td> <span style="color: red;">*    </span> </td>
    				<td>
              <select name="packages" id="frm_packages">
<?php
	foreach ($packages as $package)
	{
        $selected = ($id && ($package==$host_plan['packages'])) ? " selected='selected'" : null;
?>
				<option value="<?php echo $package?>"<?php echo $selected?>><?php echo $package?></option>
<?php
	}
?>
              </select>
            </td>
    			</tr>
    			<tr class="glav">
    				<td align="right"><label for="name"><{admin_host_plans_item_add_host_plan_name}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
    				<td><input type="text" maxlength="255" style="width: 400px;" name="name" id="frm_name" value="<?php if ($id) echo output($host_plan['name'])?>" /></td>
    			</tr>
    			<tr class="glav">
    				<td align="right" nowrap><label for="type_domen"><{admin_host_plans_item_add_protected_host_plan_url}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
    				<td><input type="text" maxlength="2048" style="width: 400px;" name="type_domen" id="frm_type_domen" value="<?php if ($id) echo output($host_plan['type_domen'])?>" /></td>
    			</tr>
                <tr>
                	<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{admin_host_plans_item_add_btn_<?php echo $interface?>}>" onClick="button_click_handler('save')" />&nbsp;<input type="button" class="button" value="<{admin_host_plans_item_add_btn_cancel}>" onClick="button_click_handler('cancel')" />
                    </td>
                </tr>
  	    </table>
      <br />
