    
    <div style="float: left; padding-top: 10px; padding-right: 5px;"><{perpage_name}>:</div>

    <div style="float: left; padding-top: 7px;"> 
    <select id="pp_select-<?php echo $pp_id_cnt;?>" style="width: 50px;" 
    onChange="$('select[id^='+'pp_select'+']').val(this.value);">
    <?php
    foreach($perpages as $item)
    {
    ?>
        <option value="<?php echo $item;?>"
        <?php
        if($item == $cur_perpage)
        {
            echo " selected ";
        }
        ?>
        ><?php echo $item;?> </option>
    <?php
    }
    ?>
    
    </select></div>
    
    <div style="float: left; padding-top: 2px; padding-left: 5px;">
    <input type="button" 
        class="button_go" value="<{perpage_btn}>" 
        onClick=" var ppval = document.getElementById('pp_select-<?php echo $pp_id_cnt;?>').value;
        <?php echo $js_handler;?>({<?php echo $params;?> ppage:  ppval})" /></div>
    
    
