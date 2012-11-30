<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;"><?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?></div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_lang_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><?php echo $title.word_wrap($sid,30,2)?></div>
            <div class="header_comment"><?php echo $comment?></div>
          </div>
       </div>
       <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
    <table class="settings table_pos" style="margin-top: 10px;">    			
    <?php if(isset($langs)){?>
    <tr class="glav">
    <td align="right" style="width: 120px;" class="table_first_child"><label for='langs'><{admin_lang_manager_langs}></label></td>
    <td> <span style="color: red;">*</span> </td>
    <td>
    <select id="langs" name="langs" onchange="reloadLang();" style="width:350px;">
    <?php 
    if(isset($langs)&&is_array($langs))
    {
        reset($langs);
        foreach($langs as $value)
        {
            ?><option value="<?php echo output($value['id'])?>" <?php echo ($value['id']==$language_id)?"selected":""?>><?php echo output($value['name'])?></option><?php
        }
    }
    ?>
    </select>
    <a class="" style="cursor: pointer;" onclick='exportDlg();' title="<{admin_lang_manager_copy_from}>">
    <img height="16" width="16"  src="<?php echo base_url()?>img/ico_import_export.gif" alt="<{admin_lang_manager_copy_from}>"/>
    </a>
    </td>
    </tr>
    <tr id="from_langs_tr" style='display:none;' class="glav">
    <td align="right" style="width: 120px;"><label for='from_langs'><{admin_lang_manager_from_langs}></label></td>
    <td> <span style="color: red;">*</span> </td>
    <td>
    <select id="from_langs" <?php echo $no_import?"disabled":"";?> name='langs'style="width:350px;">
    <?php 
    if($no_import==false)
    {
        reset($import_langs);
        foreach($import_langs as $value)
        {
            ?><option value="<?php echo output($value['id'])?>"><?php echo output($value['name'])?></option><?php
        }
    }
    ?>
    </select>
    <input type="button" <?php echo $no_import?"disabled":"";?> value="<{admin_lang_manager_btn_import}>" class="button" onClick="importLang()"/>
    </td>
    </tr>
    <?php }else{?>
    <input type="hidden" id="langs" name="langs" value="<?php echo isset($sys_default_lang)?$sys_default_lang:"1";?>"/>
    <?php 
    }
    foreach($fields as $key=>$field)
    {
        if($field['enable'])
        {
            ?>
            <tr class="glav">
            <td valign="top" align="right" style="padding-top: 5px;width: 120px;"><label for='<?php echo $key?>'><?php echo $field['name']?></label></td>
            <td <span style="color: red;"><?php echo $field['min']>0 ? "*" : ""?></span> </td>
            <td valign="top">
            <?php 
            if($field['type']=='text')
            {
                ?>
                <input id="<?php echo $key?>" name="<?php echo $key?>" type="text" style="width: 400px;" value="<?php echo output($field['value'])?>"/>
                <?php     
            }
            else
            {
                ?>
                <textarea name="<?php echo $key?>" id="<?php echo $key?>" style="width: 400px; height: 30px;"><?php echo output($field['value'])?></textarea>
                <?php 
            }
            ?>
            </td>
            </tr>
            <?php 
        }
    }
    
    if(isset($constants)&&is_array($constants))
    {
    ?>
    <tr>
    <td></td><td></td>
    <td style="padding-top:10px;">
    <select id="constants" style="width:350px;">
    <?php 
    foreach($constants as $value)
    {
        ?><option value="<?php echo output($value)?>"><?php echo output($value)?></option><?php
    }
    ?>
    </select>
    <input type="button" value="<{admin_lang_manager_btn_add}>" class="button" onClick="myOnAdd()"/>
    </td>
    </tr>
    
    <?php 
    }
    ?>
    <tr>
    	<td colspan="3" align="center" style="padding-top:15px;">
        	<?php if(!isset($langs) && isset($default_lang)){?>
            	<input type="button" class="button" value="<{admin_lang_manager_btn_default}>" onClick="importLang();" />&nbsp;
            <?php }?>
            <input type="button" class="button" value="<{admin_lang_manager_btn_save}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>')" />&nbsp;
            <input type="button" class="button" value="<{admin_lang_manager_btn_cancel}>" onClick="myOnSave('<?php echo isset($id)?output($id):""?>',true)" />
        </td>
    </tr>
    </table>
        <div class="after_table table_pos" style="padding-top: 10px;">
		&nbsp;
		</div>
<br/>		
</div>
<br />
