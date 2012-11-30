<div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
        <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
        </div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="./img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_payment_system_header_subject}></div>
            <div class="header_comment"><{admin_payment_system_header_comment}></div>
          </div>          
        </div>        
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>       
        <br />        
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td><{admin_payment_system_name}></td>
            <td width="60"><{admin_payment_system_status}></td>
            <td width="60"><{admin_payment_system_action}></td>
          </tr>
          
          <?php 
        if(isset($payments)&&is_array($payments)&&count($payments))
        {
            $flag=true;
            foreach($payments as $key=>$payment)
            {				
                ?>
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td class="left"><?php echo isset($payment['name'])?output($payment['name']):""?></td>
                <td><a style="cursor:pointer;" onclick="paySysActivate(<?php echo $key?>); return false;">
                <div <?php echo ($payment['active']==0)?"class='hidden_png_img'":""?> id="img_active_<?php echo $key?>"  ><img src="<?php echo base_url()?>img/ico_active.png" alt="<{admin_payment_system_active}>"   width="21" height="21"/></div>
                <div <?php echo ($payment['active']==1)?"class='hidden_png_img'":""?> id="img_inactive_<?php echo $key?>"><img src="<?php echo base_url()?>img/ico_delete.png" alt="<{admin_payment_system_inactive}>" width="21" height="21"/></div>
                </a></td>
                <td><a style="cursor:pointer;" onclick="paySysEdit(<?php echo $key?>,'<?php echo $payment['controller']?>'); return false;" title="<{admin_btn_edit}> '<?php echo isset($payment['name'])?output($payment['name']):""?>'"><img alt="<{admin_btn_edit}> '<?php echo isset($payment['name'])?output($payment['name']):""?>'" src="<?php echo base_url()?>img/ico_settings.png" width="16" height="16" /></a></td>
                </tr>                
                <?php
            }
        }
        else
        {
            ?>
            <tr class="dark">
            <td colspan="3"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
        ?>
        <tr><td align="right"><br/>
        <{admin_payment_system_current_currency}>
        <select id="current_currency">
        <?php  
        $cur=config_get('system','config','currency_code');
        foreach(config_get('system','config','currency_list') as $value){ 
        ?>
        <option <?php echo ($value==$cur) ? "selected" : "";?> value="<?php echo $value;?>"><{admin_currency_<?php echo mb_strtolower($value);?>}></option>
        <?php  } ?>
        </select>
        </td><td colspan="2"><br/><input type="button" class="button_big" onclick="changeCurrentCurrency()" value="<{admin_payment_system_btn_currency_change}>" /></td></tr>
        </table>        
        </div>
        <br />
