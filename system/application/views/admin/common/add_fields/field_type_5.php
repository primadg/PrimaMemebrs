<?php
if( isset($field_data) and is_array($field_data) and sizeof($field_data)>0 )
{
?>
          <tr class="glav">
            <td align="right"><?php echo output($field_data[0]['name'])?>:
            <?php
            if( isset($field_data[0]['descr']) and !empty($field_data[0]['descr']) )
            {
            ?><br /><span><small><?php echo output($field_data[0]['descr']);?></small></span>
            <?php
            }
            ?></td>
            <td>

              <?php
                if( isset($field_data[0]['val']) and !empty($field_data[0]['val']))
                {
                    $values = explode(',',$field_data[0]['val']);
                    if(is_array($values) and sizeof($values)>0)
                    {
                        foreach( $values as $current_value)
                        {
               ?>
              <input value="<?php
                if( isset($current_value) and !empty($current_value) )
                {
                    echo trim(output($current_value));
                }
            ?>"<?php
            if( isset($user_value) and !empty($user_value) and is_string($user_value) and strcmp($user_value,trim($current_value))===0 )
            {
                echo " checked=true ";
            }
            elseif( (!isset($user_value) or empty($user_value)) and isset($field_data[0]['def_value']) and !empty($field_data[0]['def_value']) )
            {
				$default_value = explode(',',$field_data[0]['def_value']);
				if( is_array($default_value) )
				{
					if( in_array(trim($current_value),$default_value) )
					{
						echo " checked=true ";
					}
				}
            }
            ?> type="radio" id="user_registration_add_field_<?php echo intval($field_data[0]['id']);?>" name="add_field_<?php echo intval($field_data[0]['id']);?>" /><?php
            if( isset( $current_value) and !empty($current_value ))
            {
                echo "&nbsp;".trim(output($current_value));
            }
            ?><br>
            <?php
                        
                        
                        }
                    }
                }
                ?>
            
            </td>
          </tr>
<?php

}
?>
