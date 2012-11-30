<?php
if( isset($field) && is_array($field) && sizeof($field)>0 )
{
    ?>
    <tr class="glav">
    <td align="right" >
    <label for="add_field_<?php echo intval($field['id']).(intval($field['type'])==3?"[]":"");?>">
    <span style="display:none;" class="add_field_error_text">
    <?php 
    if(isset($validation_error_text)&&is_array($validation_error_text))
    {
        foreach($validation_error_text as $key=>$value)
        {
            ?><span class="<?php echo $key;?>"><?php echo $value;?></span><?php             
        }
    }
    ?>
    </span>
    <?php echo soft_wrap(output($field['name']))?>:&nbsp;
    <?php
    if(isset($field['descr']) && !empty($field['descr']))
    {
        ?>
        <br/>
        <span><small><?php echo soft_wrap(output($field['descr']));?></small></span>
        <?php
    }
    ?>
    </label>
    </td>
    <td>
    <?php
    if( intval($field['req'])>0)
    {
        ?>
        <span style="color: red;">*</span>
        <?php
    }
    ?>
    </td>
    <td>
    <?php
    $field_width=isset($field_width)?$field_width:300;
    switch (intval($field['type'])) 
    {
        //if type is text
    case 1:
        $value=isset($user_value)?((is_array($user_value) ? implode(", ",$user_value) : $user_value)) : $field['def_value'];
        ?>
        <input value="<?php echo output($value);?>" type="text" class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>" style="width: <?php echo $field_width;?>px;" />
        <?php
        break;
        //if type is select
    case 2:
        $value=isset($user_value)?(is_array($user_value) ? $user_value[0] : $user_value) : $field['def_value'];
        $field['val']=explode("\n",$field['val']);
        ?>
        <select class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>" style="width: <?php echo $field_width+10;?>px;">
        <?php
        foreach($field['val'] as $val)
        {
            ?>        
            <option <?php echo ($value==$val)?"selected":"";?> value="<?php echo output($val);?>"><?php echo output($val);?></option>
            <?php
        }
        ?>
        </select>
        <?php
        break;
        //if type is multiselect
    case 3:
        $value=isset($user_value)?(is_array($user_value) ? $user_value : explode("\n",$user_value)) : explode(",",$field['def_value']);
        $field['val']=explode("\n",$field['val']);
        ?>
        <select multiple size="<?php echo (sizeof($field['val']) < 9) ? sizeof($field['val']) : 9;?>" class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>[]" style="width: <?php echo $field_width+10;?>px;">
        <?php
        foreach($field['val'] as $val)
        {
            ?>        
            <option <?php echo (in_array($val,$value))?"selected":"";?> value="<?php echo output($val);?>"><?php echo output($val);?></option>
            <?php
        }
        ?>
        </select>
        <?php
        break;
        //if type is textarea
    case 4:
        $value=isset($user_value)?((is_array($user_value) ? implode(", ",$user_value) : $user_value)) : $field['def_value'];
        ?>
        <textarea class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>" style="width: <?php echo $field_width;?>px;" ><?php echo output($value);?></textarea>
        <?php
        break;
        //if type is radio
    case 5:
        $value=isset($user_value)?(is_array($user_value) ? $user_value[0] : $user_value) : $field['def_value'];
        $field['val']=explode("\n",$field['val']);
        foreach($field['val'] as $val)
        {
            ?> 
            <input value="<?php echo output($val);?>" <?php echo ($value==$val)?"checked":"";?> type="radio" class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>" /><span><?php echo output($val);?></span><br/>
            <?php
        }        
        break;
        //if type is checkbox
    case 6:
        $value=isset($user_value)?(is_array($user_value) ? $user_value[0] : $user_value) : $field['def_value'];
        $field['val']=explode("\n",$field['val']);
        ?>
        <input value="<?php echo output($field['val'][0]);?>" <?php echo ($value==$field['val'][0])?"checked":"";?> type="checkbox" class="user_registration_add_field<?php echo isset($validation_classes)&&is_array($validation_classes) ?" ".implode(" ",$validation_classes):"";?>" name="add_field_<?php echo intval($field['id']);?>" />
        <?php
        break;
    }    
    ?>
    </td>
    </tr>
    <?php
}
?>
