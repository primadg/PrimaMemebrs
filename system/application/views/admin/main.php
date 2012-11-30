<div id='main_panel_div'>
<br/>
<div id='temp_vars_set'style="display:none;">
<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
</div>
<?php echo isset($messages)?admin_print_msg_box('msg',$messages):""?>
<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):""?>
<?php echo isset($content)?$content:""?>
<div class="subject"><{admin_main_page_payments}></div>
<table class="tab" width="700" align="center">
<tr class="glav_big">
<td> <{admin_main_page_payments_date}></td>
<td ><{admin_main_page_payments_quantity_paid}></td>
<td width="90"><{admin_main_page_payments_price}></td>
</tr>          
<?php 
if(isset($payments)&&is_array($payments))
{
    $flag=true;
    foreach($payments as $payment)
    {				
        ?>
        <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
        <td class="left"><?php echo isset($payment['date'])?$payment['date']:""?></td>
        <td><?php echo isset($payment['quantity_paid'])?output($payment['quantity_paid']):""?></td>
        <td><?php echo isset($payment['price'])?output($payment['price']):""?></td>
        </tr>                
        <?php
    }
}
?>
</table>
<?php 
create_tab_table('<{admin_main_page_system_status}>',$system_status,$width=700,$class='left');
?>
<table width="700" align="center">
<tr>
<td>
<?php 
create_tab_table('<{admin_main_page_software_info}>',$software_info,$width=300);
?>
</td>
<td valign="top">
<?php 
create_tab_table('<{admin_main_page_members_statistic}>',$members_statistic,$width=300);
?>
</td>
</tr>
</table>

</div>
