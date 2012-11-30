<div id='main_panel_div'>
<div id='temp_vars_set'style="display:none;">
<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
</div>
<div class="body_header">
<div style="float: left;"><img width="32" height="32" src="<?php echo base_url()?>img/<?php echo (isset($edit_flag)&&intval($edit_flag)>0)?"ico_settings_big.png":"ico_add_field_big.png";?>" alt="<?php echo (isset($edit_flag)&&intval($edit_flag)>0)?"<{admin_config_add_fields_edit_alt}>":"<{admin_config_add_fields_add_alt}>";?>"/></div>
<div class="header_pad">
<div class="header_subject"><{admin_config_add_fields_<?php echo (isset($edit_flag)&&intval($edit_flag)>0)?"edit":"add";?>_label}><?php echo isset($field[0]['name'])?output($field[0]['name']):""?></div>
<div class="header_comment"><{admin_config_add_fields_<?php echo (isset($edit_flag)&&intval($edit_flag)>0)?"edit":"add";?>_descr}></div>
</div>
</div>
<?php echo admin_print_msg_box('msg',$messages); ?>
<?php echo admin_print_msg_box('emsg',$mess_err); ?>
<br/>
<!--

<div id="admin_config_add_fields_errors_area" style="margin: 0pt auto; width: 500px;display:none;" class="mess_err" >
<b class="r3" style=""> </b><b class="r1" style=""> </b><b class="r1" style=""> </b>
<div id="admin_config_add_fields_title_error" class="box_err" style="display:none;" ><{admin_config_add_fields_error_title}></div>
<div id="admin_config_add_fields_field_type_error" class="box_err" style="display:none;" ><{admin_config_add_fields_error_field_type}></div>
<div id="admin_config_add_fields_custom_error" class="box_err" style="display:none;" ><{admin_config_add_fields_custom_error}></div>
<div id="admin_config_add_fields_add_error" class="box_err" style="display:none;" ></div>
<div id="jserror_field_values_empty" class="box_err" style="display:none;" ><{admin_config_add_fields_error_field_values_empty}></div>      
<div id="jserror_default_value_empty" class="box_err" style="display:none;" ><{admin_config_add_fields_error_default_value_empty}></div>      
<b class="r1" style=""> </b><b class="r1" style=""> </b><b class="r3" style=""> </b>
</div>
-->

<table align="center" class="settings table_pos" >
<tbody>
<tr class="glav">
<td class="table_first_td">
<label for='title'><{admin_config_add_fields_add_fields_title}></label></td><td> <span style="color: red;">*</span> </td>
<td><input name="title" value="<?php echo isset($field[0]['name'])?output($field[0]['name']):"";?>" id="admin_config_add_fields_title" type="text" maxlength="64" style="width: 400px;"/>
<input name="id" value="<?php echo isset($field[0]['id'])?output($field[0]['id']):"";?>" id="admin_config_add_fields_id" type="hidden" />
</td>
</tr>
<tr>
<td valign="top" align="right" style="padding-top: 5px;">
<label for='description'><{admin_config_add_fields_add_fields_descr}></label>
</td>
<td> </td>
<td>
<div class="resizable-textarea"><textarea name="description" id="admin_config_add_fields_description" style="width: 400px; height: 30px;" ><?php echo isset($field[0]['descr'])?output($field[0]['descr']):"";?></textarea></div><div class="resizable-textarea2" style="margin-right: 0px;"/></div>
</td>
</tr>
<tr>
<td align="right">
<label for='required_mark'><{admin_config_add_fields_add_fields_require}></label>
</td>
<td> </td>
<td><input name="required_mark" <?php echo (isset($field[0]['req'])&&$field[0]['req'])?"checked":"";?> id="admin_config_add_fields_required_mark" value="1" type="checkbox"/></td>
</tr>
<tr class="glav">
<td align="right">
<label for='field_type'><{admin_config_add_fields_field_type}></label>
</td>
<td> <span style="color: red;">*</span> </td>
<td>
<?php 
if(isset($edit_flag) && intval($edit_flag) == 1 && isset($field[0]['type']) && $field[0]['type']>0)
{
    ?>
    <input name="field_type_" readonly="true" value="<?php echo isset($field[0]['type'])?$field_type[$field[0]['type']]:"";?>" type="text" maxlength="64" style="width: 400px;"/>
    <input name="field_type" value="<?php echo isset($field[0]['type'])?$field[0]['type']:"";?>" id="admin_config_add_fields_field_type" type="hidden" />
    <?php 
}
else
{
    ?>
    <select name="field_type"  id="admin_config_add_fields_field_type" style="width: 410px;">
    <?php 
    foreach($field_type as $key=>$type)
    {
        ?>
        <option value="<?php echo output($key)?>"><?php echo $type?></option>
        <?php
    } 
    ?>
    </select>
    <?php 
}
?>
</td>
</tr>
<tr>
<td valign="top" align="right" style="padding-top: 5px;">
<label for='field_values'><{admin_config_add_fields_field_values}></label>
</td>
<td> <span style="color: red;">*</span> </td>
<td><div class="resizable-textarea"><textarea  name="field_values"  id="admin_config_add_fields_field_values" style="width: 400px; height: 30px;" ><?php echo isset($field[0]['val'])?output($field[0]['val']):"";?></textarea></div><div class="resizable-textarea2" style="margin-right: 0px;"/></div></td>
</tr>
<tr>
<td align="right">
<label for='default_value'><{admin_config_add_fields_default_value}></label>
</td>
<td> </td>
<td><input  name="default_value" value="<?php echo isset($field[0]['def_value'])?output($field[0]['def_value']):"";?>" id="admin_config_add_fields_default_value"  type="text" style="width: 400px;"/></td>
</tr>
<tr>
<td align="right"><label for='check_rule'><{admin_config_add_fields_check_rule}></label></td>
<td> </td>
<td>
<select  name="check_rule"  id="admin_config_add_fields_check_rule"  style="width: 410px;">
<?php                 
reset($check_rule);
foreach($check_rule as $key=>$rule)
{
    ?>
    <option value="<?php echo output($key)?>" <?php echo (isset($field[0]['check_rule'])&&$field[0]['check_rule']==$key)?"selected":""?>><?php echo $rule?></option>
    <?php
}            
?>
</select>
</td>
</tr>
<tr>
	<td colspan="3" align="center" style="padding-top:15px;">
    	<input type="button" onclick="myOnSave('<?php echo isset($field[0]['id'])?output($field[0]['id']):"";?>');" value="<{admin_config_add_fields_<?php echo (isset($edit_flag)&&intval($edit_flag)>0)?"edit":"add";?>_submit}>" class="button"/> 
		<input type="button" onclick="myOnSave('',true);" value="<{admin_config_add_fields_cancel_button}>" class="button"/>
    </td>
</tr>
</tbody>
</table>
</div>
<br />

