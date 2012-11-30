<?php
/*********************************************************************************
*   Edited by Konstantin X @ 13.05.2008
**********************************************************************************/

if(isset($lang_id)) echo '<div id="language_id" name="language_id" rel="'. intval($lang_id) .'" style="display: none"></div>';

?>
    <div class="body_header">
      <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_import_export_big.gif" width="32" height="32" /></div>
      <div class="header_pad">
        <div class="header_subject"><{admin_config_language_translate_header}><?php echo isset($language_name) ? " (".$language_name.")" : "";?></div>
        <div class="header_comment"><{admin_config_language_translate_subheader}></div>
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
      <div class="box" style="display:none;" id="status_ok" ><{admin_msg_ok_0004}></div><!--                        Language data was updated successfully -->
      <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
    </div>
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
      <div class="box_err" style="display:none;" id="status_er" ><{admin_msg_er_0022}></div><!--                    The requsted language not found  -->
      <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
    </div>
        <div>
            <h1><{admin_config_language_translate_instruction}></h1>
                <ol>
                    <li><{admin_config_language_translate_step1}> <a href="<?php echo base_url();?>config/language_getXML/<?php echo $lang_id;?>/1/" target="_blank"><{admin_config_language_translate_user_xml}></a> <{admin_config_language_translate_or}> <a href="<?php echo base_url();?>config/language_getXML/<?php echo $lang_id;?>/2/" target="_blank"><{admin_config_language_translate_admin_xml}></a> <{admin_config_language_translate_or}> <a href="<?php echo base_url();?>config/language_getXML/<?php echo $lang_id;?>/" target="_blank"><{admin_config_language_translate_all_xml}></a></li>
                    <li><{admin_config_language_translate_step2}> <a href="https://open-language-tools.dev.java.net/" target="_blank">Open Language Tools</a></li>
                    <li><{admin_config_language_translate_step3}> <input id="submit_btn" type="submit" class="button_big" value="<{admin_config_lang_editor_choose}>"/></li>
                </ol>
        </div>
