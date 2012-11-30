<?php
if( isset($field_data) and is_array($field_data) and sizeof($field_data)>0 )
{
?>
          <tr class="glav">
            <td align="right"><?php echo output($field_data[0]['name'])?>:&nbsp;
            <?php
            if( intval($field_data[0]['req'])>0 )
            {
            ?>
                <span style="color: red;">*</span>
            <?php
            }
            ?>
            <?php
            if( isset($field_data[0]['descr']) and !empty($field_data[0]['descr']) )
            {
            ?>
                <br /><span><small><?php echo output($field_data[0]['descr']);?></small></span>
            <?php
            }
            ?></td>
            <td>
            <select id="user_registration_add_field_<?php echo intval($field_data[0]['id']);?>" name="add_field_<?php echo intval($field_data[0]['id']);?>" style="width: 360px;">
            <?php
                if( isset($field_data[0]['val']) and !empty($field_data[0]['val']))
                {
                    $values = explode(',',$field_data[0]['val']);
                    if(is_array($values) and sizeof($values)>0)
                    {
                        foreach( $values as $current_value)
                        {
                            if( is_array($user_value))
                            {
                                $user_value = implode(", ",$user_value);
                            }
                            $current_value = trim($current_value);
                            echo "<option ";
                            if( isset($user_value) and !empty($user_value) and strcmp($user_value,$current_value)===0 )
                            {
                                echo " selected=true ";
                            }
                            elseif( !isset($user_value) or empty($user_value) )
                            {
                                if( isset($field_data[0]['def_value']) and !empty($field_data[0]['def_value']) and $field_data[0]['def_value']==$current_value)
                                {
                                    echo " selected=true ";
                                }
                            }
                            echo " value=\"".trim(output($current_value))."\">".trim(output($current_value))."</option > \n";
                        }
                    }
                }
            ?>            
            </select>
            </td>
          </tr>
<?php
}
?>
