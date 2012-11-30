
    <?php
    if( $count > $curpage )
    {
    ?>
        <div>
        <a href="#" 
           onClick="<?php echo $js_handler;?>({<?php echo $params;?> cpage: <?php echo ($curpage+1);?>}); return false; ">&gt;</a>
        <a href="#" onClick="<?php echo $js_handler;?>({<?php echo $params;?> cpage:<?php echo $count;?>}); return false; ">&gt;&gt;</a></div>
    <?php
    }
    ?>

    <?php 
    if( $count > 1 )
    {
    ?>    
        <div style="padding-top: 5px;"><select id="pageselect-<?php echo $pg_id_cnt;?>"
        onchange=" $('select[id^='+'pageselect'+']').val(this.value);  
        <?php echo $js_handler;?>({<?php echo $params;?> cpage: this.value})">
        <?php
        for($i=1; $i<=$count; $i++)
        {
        ?>
            <option value = "<?php echo $i;?>"
            <?php
            if($i == $curpage)
            {
                echo " selected ";
            }
            ?>
            ><?php echo $i;?></option>
        <?php    
        }
        ?>

        </select></div>
        
    <?php
    }
    ?>
        
        
    <?php 
    if( $curpage > 1 )
    {
    ?>
        <div>
        <a href="#" onClick = "<?php echo $js_handler;?>({<?php echo $params;?> cpage: 1}); return false; ">&lt;&lt;</a>
        <a href="#" onClick="<?php echo $js_handler;?>({<?php echo $params;?> cpage: <?php echo ($curpage-1);?>}); return false; ">&lt;</a>
        </div>
        
    <?php
    }
    ?>
    
    <?php 
    if( $count > 1 )
    {
    ?>    
        <div style="padding-right: 3px;"><{pager_name}>:</div>
    <?php
    }
    ?>
    
    
