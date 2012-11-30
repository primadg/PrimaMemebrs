<?php
if( isset($field_data) and is_array($field_data) and sizeof($field_data)>0 )
{
?>
          <tr class="glav">
            <td align="right"><?php echo output($field_data[0]['name'])?>:&nbsp;<?php
            if( intval($field_data[0]['req'])>0)
            {
            ?><span style="color: red;">*</span><?php
            }
            ?>
            <?PHP
            if( isset($field_data[0]['descr']) and !empty($field_data[0]['descr']) )
            {
            ?><br /><span><small><?php echo output($field_data[0]['descr']);?></small></span><?php
            }
            ?></td>
            <td><input value="<?php
            if( isset($user_value) and !empty($user_value) )
            {
                if( is_array( $user_value ))
                {
                    $user_value = implode(", ",$user_value);
                }
                echo output($user_value);
            }
            else
            {
                if( isset($field_data[0]['val']) and !empty($field_data[0]['val']) )
                {
                    echo output($field_data[0]['val']);
                }
                else
                {
                    echo output($field_data[0]['def_value']);
                }
            }
            ?>" type="text" id="user_registration_add_field_<?php echo intval($field_data[0]['id']);?>" name="add_field_<?php echo intval($field_data[0]['id']);?>" style="width: 350px;" /></td>
          </tr>
<?php

}
?>
