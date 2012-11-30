<div id='main_panel_div'>
<div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
			</div>
        <form id="admin_config_lang_editor_list_form" action="<?php echo base_url()?>" method="post" >
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_language_list_header}></div>
            <div class="header_comment"><{admin_config_language_list_subheader}></div>
          </div>
        </div>
        
        <?php
            if( isset($message) and !empty($message) )
            {
                $message_display = "";
            }
            else
            {
                $message = '';
                $message_display = " display:none; ";            
            }
        ?>
        <div  class="mess" id="admin_config_lang_editor_message_area" style="width: 500px; margin: 0 auto;<?php echo $message_display?>">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="admin_config_lang_editor_list_php_message" class="box" style="<?php echo $message_display?>" ><?php echo $message?></div>
          <div class="box" style="display:none;" id="admin_config_lang_editor_list_message_add_lang_ok" ><{admin_config_lang_editor_add_lang_success}></div>
          <div class="box" style="display:none;" id="admin_config_lang_editor_list_message_delete_lang_ok" ><{admin_config_lang_editor_delete_lang_success}></div>
          <div class="box" style="display:none;" id="status_ok" ><{admin_msg_ok_0004}></div><!--                        Language data was updated successfully -->
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <?php

        ?>

        <?php
            if( isset($message_err) and !empty($message_err) )
            {
                $message_err_display = "";
            }
            else
            {
                $message_err = '';
                $message_err_display = " display:none; ";
            }
            ?>
        <div class="mess_err" id="admin_config_lang_editor_message_err_area" style="width: 500px; margin: 0 auto;<?php echo $message_err_display?>">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box_err" id="admin_config_lang_editor_list_php_message_err" style="<?php echo $message_err_display?>" ><?php echo $message_err?></div>
          <div class="box_err" style="display:none;" id="admin_config_lang_editor_list_message_delete_lang_error" ><{admin_config_lang_editor_delete_lang_db_error}></div>
          <div class="box_err" style="display:none;" id="status_er" ><{admin_msg_er_0022}></div><!--                    The requsted language not found  -->
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br/>
        <table id="lang_table" class="tab" align="center" width="700">
        <thead>
          <tr class="glav_big">
            <td width="60"><{admin_config_lang_editor_title_default}></td>
            <td><{admin_config_lang_editor_title_language}></td>
            <td width="80"><{admin_config_lang_editor_title_action}></td>
          </tr>
          </thead>
          <tbody>
            <?php
            if( isset($languages_list) and is_array($languages_list) and sizeof($languages_list)>0 )
            {
                $i=1;
                foreach( $languages_list as $language )
                {
            ?>
          <tr class="light" id="admin_config_lang_editor_list_tr_<?php echo intval($language['id'])?>" >
            <td><input onclick="admin_config_lang_editor_set_default_lang(<?php echo intval($language['id'])?>);" type="radio" <?php echo (intval($language['is_default'])>0) ? " checked=true " : "";?>name="is_default" value="<?php echo intval($language['id'])?>" /></td>
            <td class="left"><?php echo output($language['name'])?></td>
            <td>
              <a href="#" onClick="admin_config_lang_editor_translate(<?php echo intval($language['id'])?>);return false;" title="<{admin_config_lang_editor_translator}>"><img alt="<{admin_config_lang_editor_translator}>" title="<{admin_config_lang_editor_translator}>" src="<?php echo base_url()?>img/ico_import_export.gif" width="16" height="16" /></a>&nbsp;
              <a href="#" onClick="admin_config_lang_editor_edit(<?php echo intval($language['id'])?>);return false;" title="<{admin_language_edit}> '<?php echo output($language['name'])?>'"><img alt="<{admin_language_edit}> '<?php echo output($language['name'])?>'" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" /></a>&nbsp;
<?php echo (intval($language['is_default'])>0) ? "<!-- " : "";?>
              <a href="#" title="<{admin_btn_delete}>" onClick="if(confirm('<{admin_config_lang_editor_delete_confirm}>')){ admin_config_lang_editor_delete('<?php echo intval($language['id'])?>');return false; }else{ return false; }"><img alt="<{admin_btn_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>
<?php echo (intval($language['is_default'])>0) ? "-->" : "";?>
            </td>
          </tr>
            <?php
                $i++;
                }
            }
            else
            {
                ?>
                <tr class="dark">
                <td colspan="3"><{admin_table_empty}></td>                                
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
        <tr>
        	<td colspan='3'>
        		<table width='100%'>
        			<tr>
        				<td align='left'> 
                        <input id="submit_btn" type="button" class="set_default" value="<{admin_config_lang_editor_import_from_file}>"/>
                        </td>
        				<td align='right'>
        				</td>
                        <td align='right'><input type="button" <?php echo (isset($aLangs)&&$aLangs==false) ? "disabled='disabled'" : "";?> class="set_default" value="<{admin_config_lang_editor_add_language}>" onClick="admin_config_lang_editor_add_lang();return false;" />
                        </td>
        			</tr>
        		</table>
        	</td>
        </tr>
        </tfoot>
        </table>
    </form>
    <br />
    </div>
