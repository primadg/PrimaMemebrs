        <form id="admin_config_lang_editor_add_form" action="<?php echo base_url()?>" method="post" >
        <div class="body_header">
          <div style="float: left;"><img alt="edit product" src="<?php echo base_url()?>img/ico_new_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_lang_editor_add_lang_header}></div>
            <div class="header_comment"><{admin_config_lang_editor_add_lang_subheader}></div>
          </div>
        </div>
        
        <div class="mess_err" style="width: 500px; margin: 0 auto;display:none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box_err" id="admin_config_lang_editor_add_lang_error" ></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        
        <table class="setting table_pos">
          <tr>
            <td align="right" class="table_first_td"><{admin_config_lang_editor_add_lang_language}>:</td>
            <td>
                <select id="admin_config_lang_editor_add_lang_name" name="lang_name">
                <?php
                    if (isset($aLangs) and is_array($aLangs) and count($aLangs)>0)
                    {
//                        reset($all_langs);
                        foreach ($aLangs as $c => $language) echo "<option value=\"". output($c) ."\">". output($language) ."</option>\n";
                    }
                ?>
                </select>
            </td>
          </tr>
          <tr>
            <td valign="top" align="right"><{admin_config_lang_editor_add_lang_use_def_set}>:</td>
            <td>
                <select id="lang_default" name="lang_default">
                <?php
                if (isset($languages) and is_array($languages) and count($languages)>0)
                {
                    reset($languages);
                    foreach ($languages as $language)
                    {
                ?>
                    <option value="<?php echo output($language['lang_code'])?>"<?php echo (intval($language['is_default']) == 1) ? ' selected' : '';?>><?php echo output($language['name'])?></option>
                <?php
                    }
                }
                ?>
                </select>
            </td>
          </tr>
        </table>
        
        <div class="after_table table_pos" style="padding-top: 10px; padding-bottom: 20px;">
            <input type="button" class="button" onClick="admin_config_lang_editor_list();return false;" value="Cancel" />&nbsp;
            <input type="button" class="button" onClick="admin_config_lang_editor_add();return false;" value="Add" />
        </div>
        <br />
