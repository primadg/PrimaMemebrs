<?php 
if(isset($items) and is_array($items) and sizeof($items) > 0)
{
    $lang_id = intval($items[0]['language_id']);
}
else
{
    $lang_id = 0;
}
?>
          
                 

       <form id="admin_config_lang_editor_list_form" action="<?php echo base_url()?>" method="post">
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_language_list_header}><?php echo isset($language_name) ? " (".$language_name.")" : "";?></div>
            <div class="header_comment"><{admin_config_language_list_subheader}></div>
          </div>
        </div>
        
        <table id="search_tbl" class="tab" align="center" width="700px">
          <tr class="glav_big">
            <td nowrap align="left" style="width: 550px; font-weight: bold;">
                <input id="admin_config_lang_editor_label_name" name="admin_config_lang_editor_label_name" style="width: 540px; margin-left: 3px;" value="<?php echo $filter_str;?>" />
            </td>
            <td width="150px" nowrap>
              <input type="button" value="<{admin_btn_find}>" onClick="admin_config_lang_editor_find('<?php echo intval($lang_id)?>');return false;" class="button" />
              <input type="button" value="<{admin_btn_clear}>" onClick="admin_config_lang_editor_clear('<?php echo intval($lang_id)?>');return false;" class="button" />
            </td>
          </tr>
          <tr class="light" id="admin_config_lang_editor_label_value_tr" style="display: none;">
            <td><input type="text" name="label_value" id="admin_config_lang_editor_label_value" style="width: 540px; margin-left: 3px;" /></td>
            <td>
              <input type="button" id="lang_update" value="<{admin_btn_update}>" onClick="admin_config_lang_editor_update('<?php echo intval($lang_id)?>');return false;" class="button" disabled="disabled"/>
            </td>
          </tr>
          <tr id="admin_config_lang_editor_label_value_tabs">
            <td colspan="2">
                <a id="admin_config_lang_editor_label_value_tab_label" class="tab_active handpointer" ><{admin_config_lang_editor_tab_label}></a>
                <a id="admin_config_lang_editor_label_value_tab_value" class="tab_passive handpointer" ><{admin_config_lang_editor_tab_value}></a>
            </td>
          </tr>
        </table>
        <br/>
        <?php
            if( isset($message) and !empty($message) )
            {
                $message_display = "";
            }
            else
            {
                $message_display = " display:none; ";            
            }
        ?>
        <div  class="mess" id="admin_config_lang_editor_message_area" style="width: 500px; margin: 0 auto;<?php echo $message_display?>">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div id="admin_config_lang_editor_list_php_message" class="box" style="<?php echo $message_display?>" ><?php echo $message?></div>
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
                $message_err_display = " display:none; ";
            }
            ?>
        <div class="mess_err" id="admin_config_lang_editor_message_err_area" style="width: 500px; margin: 0 auto;<?php echo $message_err_display?>">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box_err" id="admin_config_lang_editor_list_php_message_err" style="<?php echo $message_err_display?>" ><?php echo $message_err?></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <?php
            
        ?>
<?php
if(isset($lang_id))
{
    echo '<div id="language_id" name="language_id" rel="'. intval($lang_id) .'" style="display: none"></div>';
}
?>
        <div class="page">
          <?php
            echo perpage_selectbox($per_page_set,'admin_config_lang_editor_edit_lang',array('lang_id'=>$lang_id,'filter_str'=>addslashes($filter_str),'filter_fld'=>$filter_fld), $per_page);
          ?>
          <?php
            echo page_selectbox($pages,'admin_config_lang_editor_edit_lang',array('lang_id'=>$lang_id,'filter_str'=>addslashes($filter_str),'filter_fld'=>$filter_fld,'ppage'=>$per_page), $current_page);
          ?>
        </div>
        
        <table id="lang_tbl" name="lang_tbl" class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="700"><{admin_config_lang_editor_key_name}> - <{admin_config_lang_editor_key_translation}></td>
          </tr>
            <?php
            if(isset($items) and is_array($items) and sizeof($items)>0)
            {
                $i=1;
                foreach($items as $language)
                {
            ?>
          <tr class="light" id="admin_config_lang_editor_list_tr_<?php echo output($language['key_name'])?>">
            <td class="left" style="padding: 4px 10px;">
                <span class="my_splitter"><?php echo str_replace("_", "</span><span class=\"my_splitter\">_", output($language['key_name']));?></span><br/>
                <div class="lang_data" style="padding: 5px 10px 2px; text-decoration: none;" id="<?php echo output($language['key_name'])?>"><?php echo $language['content']?></div>
            </td>
          </tr>
            <?php
                $i++;
                }
            }
            else
            {
            ?>
          <tr class="light">
            <td align="center"><{admin_config_lang_editor_empty_list}></td>
          </tr>
            <?php
            }
            ?>
        </table>
    </form>
    <br />
