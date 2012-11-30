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
            <th width="15%"><{admin_member_pages_<?php echo $page_name?>_option_name}></th>
            <th ><{admin_member_pages_<?php echo $page_name?>_option_options}></th>
            <th width="4%"></th>
          </tr>
          </thead>
          <tbody>
        <?php 
        $flag=false;
        foreach($fields as $id=>$field)
        {				
            ?>
            <tr style="vertical-align:top;" class="<?php echo ($flag=!$flag)?"light":"dark";?>">
            <td align="left">
            <img class="handpointer" onclick="memberPageOptionsOrder('<?php echo $page_name?>','up','<?php echo $id?>')" title="Up" alt="Up" src="<?php echo base_url()?>img/up.png" width="16" height="16" />
            <img class="handpointer" onclick="memberPageOptionsOrder('<?php echo $page_name?>','down','<?php echo $id?>')" title="Down" alt="Down" src="<?php echo base_url()?>img/down.png" width="16" height="16" />            
            </td>
            <td align="left"><{admin_member_pages_<?php echo $page_name."_".$id?>_option_name}></td>
            <td align="left"><span class="handpointer" onclick="memberPageOptionsToggle(this)"><span class="arrow">&#9660;</span> <{admin_member_pages_<?php echo $page_name."_".$id?>_field_comment}></span>
            <div name="<?php echo $id?>" class="options">
            <?php 
            if(isset($field['enabled']))
            {
                ?>
                <input <?php echo isset($field['obligate'])?"disabled":"";?> name="enabled" <?php echo ($field['enabled'])?"checked":"";?> type="checkbox" value="enabled"> <{admin_member_pages_<?php echo $page_name?>_option_enabled}><br/>
                <?php 
                unset($field['obligate']); 
                unset($field['enabled']);               
            }
            if(isset($field['type']))
            {
                ?>
                <select name="type">
                <option value=""></option> 
                <?php  foreach(_config_get('fields','types') as $type=>$val){?>
                    <option <?php echo ($type==$field['type'])?"selected":"";?> value="<?php echo $type?>"><?php echo $type?></option>
                    <?php  } ?>
                </select> <{admin_member_pages_<?php echo $page_name?>_option_type}><br/> 
                <?php 
                unset($field['type']);               
            }
            
            if(isset($field['length']))
            {
                ?>
                <{admin_member_pages_<?php echo $page_name?>_option_length}>
                <?php  if(isset($field['length']['min'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_min}>
                <input value="<?php echo intval($field['length']['min'])?>" name="length_min" type="text"  maxlength="7"  style="width: 30px;" />
                <?php  } if(isset($field['length']['max'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_max}>
                <input value="<?php echo intval($field['length']['max'])?>" name="length_max" type="text"  maxlength="7"  style="width: 30px;" />
                <?php  } if(isset($field['length']['limit'])) {?>
                <{admin_member_pages_<?php echo $page_name?>_option_length_limit}>(<?php echo intval($field['length']['limit'])?>)
                <?php  } ?>
                <br/>
                <?php 
                unset($field['length']);
            }
            
            foreach($field as $k=>$v)
            {
                if($k!='order')
                {
                ?>
                <input name="<?php echo $k?>" <?php echo ($v)?"checked":"";?> type="checkbox" value="<?php echo $k?>"> <{admin_member_pages_<?php echo $page_name?>_option_<?php echo $k?>}><br/>
                <?php 
                }
            }            
            ?>
            </div>
            </td>
            <td><?php echo create_tooltip_div('admin_member_pages_'.$page_name."_".$id.'_option_ttip')?></td>  
            </tr>
            <?php
        }        
        ?>
        </tbody>
        </table>
            <div class="add">
            <input type="button" class="button_save_as_template" value="<{admin_member_pages_btn_save}>" onClick="memberPageSave('<?php echo $page_name?>');"/>
            </div>        
      </div>
      <br />
