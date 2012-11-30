<div class="<?php echo (isset($class) and $class=='msg')?'mess':'mess_err';?>" style="width: 500px; margin: 0 auto;<?php echo (isset($box_display) and intval($box_display)==1)?'':'display:none;';?>">
      <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
        <?php
        if( isset($keys) and is_array($keys) and sizeof($keys)>0 )
        {
            foreach($keys as $id=>$value)
            {
                $value_text = $value;
                $display = ' display:none; ';
                if( empty($id) ) { continue; }
                if( is_array($value) and isset($value['text']) )
                {
                    $value_text = $value['text']; 
                    if( isset($value['display']) and intval($value['display'])==1 )
                    {
                        $display = '';
                    }
                }
        ?>
            <div id="<?php echo $class?>_<?php echo $id?>" style="<?php echo $display?>" class="<?php echo (isset($class) and $class=='msg')?'box':'box_err';?>" ><?php echo $value_text?></div>
        <?php
            unset($id,$value,$display,$value_text);
            }
        }
        ?>
      <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
</div>
