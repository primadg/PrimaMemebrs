<?php
    $interface = ($id) ? "edit" : "add";
?>
<div id='main_panel_div'>

    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>

		<link rel="stylesheet" href="<?php echo base_url()?>css/jquery.treeview.css" />

        <div class="body_header">
          <div style="float: left;"><img alt="<{directories_<?php echo $interface?>_title}>" src="<?php echo base_url()?>img/page_edit32.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{directories_<?php echo $interface?>_title}></div>
            <div class="header_comment"><{directories_<?php echo $interface?>_description}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$ok_messages); ?>
		<?php echo admin_print_msg_box('emsg',$error_messages); ?>

        <table class="settings table_pos" style="margin-top: 10px;">
            <input type="hidden" name="id" id="frm_id" value="<?php echo output($id)?>" />
    			<tr class="glav">
    				<td align="right" class="table_first_td"><label for="method"><{directories_add_protection_method}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
    				<td>
              <select name="method" id="frm_method">
<?php
	foreach ($protection_methods as $method)
	{
        $selected = ($id && ($method==$directory['method'])) ? " selected='selected'" : null;
?>
				<option value="<?php echo $method?>"<?php echo $selected?>><{directories_add_protection_method_<?php echo $method?>}></option>
<?php
	}
?>
              </select>
            </td>
    			</tr>
    			<tr class="glav">
    				<td align="right"><label for="name"><{directories_add_directory_name}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
    				<td><input type="text" maxlength="255" style="width: 400px;" name="name" id="frm_name" value="<?php if ($id) echo output($directory['name'])?>" /></td>
    			</tr>
    			<tr class="glav">
    				<td align="right" nowrap><label for="http_path"><{directories_add_protected_directory_url}>:</label></td>
                    <td> <span style="color: red;">*</span> </td>
    				<td><input type="text" maxlength="2048" style="width: 400px;" name="http_path" id="frm_http_path" value="<?php if ($id) echo output($directory['http_path'])?>" /></td>
    			</tr>
                <tr class="glav">
    				<td valign="top" align="right" style="padding-top: 5px;"><label for="fs_path"><{directories_add_directory_itself}>:</label></td>
                    <td valign="top" style="padding-top:5px;"> <span style="color: red;">*</span> </td>
    				<td valign="top" style="padding-top:6px;">
                        <input type="text" maxlength="2048" id="frm_fs_path" style="width: 375px;" name="fs_path" />
                        <a id="treeview_reload_btn" class="handpointer" onClick="treeview_reload(); return false;" title="<{directories_treeview_reload}>"><img alt="<{directories_treeview_reload}>" src="<?php echo base_url()?>img/repeat.png" width="24" height="24" align="absbottom" /></a>
                        <a id="treeview_stop_btn" style="display:none;" class="handpointer" onClick="treeviewStopLoad(); return false;" title="<{directories_treeview_stop}>"><img alt="<{directories_treeview_stop}>" src="<?php echo base_url()?>img/repeat_stop.png" width="24" height="24" align="absbottom" /></a>
                        <br />
                        <ul id="treeview_ul_0" class="filetree" style="overflow:scroll; width:400px; height:500px;padding:5px;border:1px #919191 dashed; margin-top:0px; margin-left:0px; margin-bottom:5px;"></ul>
						<div id="treeview_disabled_text" style="display:none; padding:10px;"><i><{admin_treeview_ie6_disabled}></i></div>
                    </td>
    			</tr>
                <tr>
                	<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" class="button" value="<{directories_add_protection_btn_<?php echo $interface?>}>" onClick="button_click_handler('save')" />&nbsp;<input type="button" class="button" value="<{directories_add_protection_btn_cancel}>" onClick="button_click_handler('cancel')" />
                    </td>
               	</tr>
  	    </table>
        <br />
