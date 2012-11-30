<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="<?php echo isset($page_icon_title)?$page_icon_title:"";?>" src="<?php echo base_url()?>img/<?php echo isset($page_icon)?$page_icon:"";?>" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_pages_<?php echo $page_name?>_header_subject}></div>
            <div class="header_comment"><{admin_member_pages_<?php echo $page_name?>_header_comment}></div>
          </div>
        </div> 
        <div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        </div>        
        <br />         
        <table id="field_list" class="tab" align="center" width="95%">
          <thead>
          <tr class="glav_big">
            <th width="36px"></th>
            <th width="15%"><{admin_member_pages_option_name}></th>
            <th width="4%" align="center"><?php echo create_tooltip_div('admin_member_pages_enabled_ttip')?></th>
            <th ><div class="all_options_toggler all_options_toggler_show handpointer" onclick="memberPageOptionsToggle('.options_toggler');"; style="width:3%;float:left;color:white;">&#9660;</div><div style="width:97%;float:right"><{admin_member_pages_option_options}> <?php echo create_tooltip_div('admin_member_pages_options_ttip')?></div></th>
            <th width="4%"></th>
          </tr>
          </thead>
          <tbody>
        <?php 
        $flag=false;
        foreach($fields as $id=>$field)
        {				
            //delete hidden options from view list
            if(isset($field['hidden']) && is_array($field['hidden']))
            {
                foreach($field['hidden'] as $f)
                {
                    unset($field[$f]);    
                }
                unset($field['hidden']);
            }
            ?>
            <tr style="vertical-align:top;" class="<?php echo ($flag=!$flag)?"light":"dark";?>">
            <td align="left">
            <img class="handpointer" onclick="memberPageOptionsOrder('<?php echo $page_name?>','up','<?php echo $id?>')" title="Up" alt="Up" src="<?php echo base_url()?>img/up.png" width="16" height="16" />
            <img class="handpointer" onclick="memberPageOptionsOrder('<?php echo $page_name?>','down','<?php echo $id?>')" title="Down" alt="Down" src="<?php echo base_url()?>img/down.png" width="16" height="16" />            
            </td>
            <td align="left"><{admin_member_pages_<?php echo $page_name."_".$id?>_option_name}></td>
            <td align="center">
            <?php 
            if(isset($field['enabled']))
            {
                ?>
                <input title="<{admin_member_pages_<?php echo $page_name?>_option_enabled}>" <?php echo isset($field['obligate'])?"disabled":"";?> name="enabled" <?php echo ($field['enabled'])?"checked":"";?> type="checkbox" value="enabled">
                <?php 
                unset($field['obligate']); 
                unset($field['enabled']);               
            }
            ?>
            </td>
            <td align="left"><span <?php echo count($field)>1?"class='options_toggler handpointer' onclick='memberPageOptionsToggle(this)'":"";?> ><?php echo count($field)>1 ? "<span class='arrow'>&#9660;</span> " : "<span style='color:grey;'>&#9658;</span> ";?><{admin_member_pages_<?php echo $page_name."_".$id?>_field_comment}></span>
            <div name="<?php echo $id?>" class="options">
            <?php  if(count($field)>1){ ?>
            <table width="100%">
            <tr style="vertical-align:top;">
            <td align="left" width="50%">
            <?php 
            if(isset($field['type']))
            {
                ?>
                <select name="type">
                <?php  
                foreach($field_types as $type=>$val){ ?>
                    <option <?php echo ($type==$field['type'] || (empty($field['type']) && $type=='text'))?"selected":"";?> value="<?php echo $type?>"><?php echo $val['name']?></option>
                    <?php  } ?> 
                </select> <{admin_member_pages_<?php echo $page_name?>_option_type}><br/> 
                <?php 
                unset($field['type']);               
            }
            
            foreach($field as $k=>$v)
            {
                if($k!='order' && $k!='length')
                {
                ?>
                <input name="<?php echo $k?>" <?php echo ($v)?"checked":"";?> type="checkbox" value="<?php echo $k?>"> <{admin_member_pages_<?php echo $page_name?>_option_<?php echo $k?>}><br/>
                <?php 
                }
            }
            ?>
            </td>
            <td>
            <?php 
            if(isset($field['length']))
            {
                ?>
                <table style="vertical-align:top;" width="100%">
                <tr>
                <td align="right">
                <{admin_member_pages_<?php echo $page_name?>_option_length}>&nbsp;
                <?php  if(isset($field['length']['min'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_min}><br/>
                <?php  } ?>
                </td>
                <td align="left">
                <?php  if(isset($field['length']['min'])) {?>
                <input value="<?php echo intval($field['length']['min'])?>" name="length_min" type="text"  maxlength="7"  style="width: 30px;" />
                <?php  } ?>
                </td>
                </tr>
                <tr>
                <td align="right">
                <?php  if(isset($field['length']['max'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_max}>
                <?php  } ?>
                </td>
                <td align="left">
                <?php  if(isset($field['length']['max'])) {?>
                <input value="<?php echo intval($field['length']['max'])?>" name="length_max" type="text"  maxlength="7"  style="width: 30px;" />
                <?php  } if(isset($field['length']['limit'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_limit}>(<?php echo intval($field['length']['limit'])?>)
                <?php  } ?>
                </td>
                </tr>
                </table>
                <?php 
                unset($field['length']);
            }
            ?>
            </td>            
            </tr>
            </table>
            <?php  } ?>
            </div>
            </td>
            <td><?php echo create_tooltip_div('admin_member_pages_'.$page_name."_".$id.'_option_ttip')?></td>  
            </tr>
            <?php
        }        
        ?>
        </tbody>
        </table>
        <br/>
            <div style="float:left; margin-left:20px;">
            <input align="left" type="button" class="button_save_as_template" value="<{admin_member_pages_btn_back}>" onClick="memberPageBack('<?php echo base_url().'config/design_manager'?>');"/>
            &nbsp;<{admin_member_pages_presets}>&nbsp;
            <select title="<{admin_member_pages_presets_title}>" name="page_presets" id="page_presets" style="width:150px;" >
            <option value=''><{admin_member_pages_preset_last_saved}></option>
            <?php  foreach($page_presets as $k=>$v){ ?>
            <option <?php echo ($preset==$v?"selected":"")?> value='<?php echo $v?>'><{admin_member_pages_preset_<?php echo $v?>}></option>
            <?php  } ?>
            </select>
            <input align="left" type="button" class="button" value="<{admin_member_pages_btn_load_preset}>" onClick="memberPageLoadPreset('<?php echo $page_name?>');"/>
            </div>
            <div style="float:right; margin-right:20px;">
            <input align="right" type="button" class="button_save_as_template" value="<{admin_member_pages_btn_save}>" onClick="memberPageSave('<?php echo $page_name?>');"/>
            </div>
      </div>
      <br /><br /><br /><br /><br /><br /><br />
