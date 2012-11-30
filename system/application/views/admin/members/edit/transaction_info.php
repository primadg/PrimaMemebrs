
        <div class="tema"><{admin_member_control_account_panel_transaction_info_page_title}>&nbsp;(<{admin_member_control_account_panel_transactions_list_label_subscr}>&nbsp;<?php echo (isset($subscription_id))?$subscription_id:'';?>,&nbsp;<{admin_member_control_account_panel_transaction_info_label_transaction_id}>&nbsp;<?php echo (isset($id))?$id:'';?>)</div>        
        
         
        <table class="settings" align="center">
        <tr class="glav">
            <td align="right" valign="top"><{admin_member_control_account_panel_transaction_info_label_info}></td>        
            <td align="left" valign="top">
            <?php 
            if( isset($info) && count($info) > 0 )
            {
                foreach( $info as $item )
                { 
            ?>
                    <div><?php echo $item;?></div>
            <?php
                }
            }
            ?>
            </td>
        </tr>
        <tr class="glav">
            <td align="right"><{admin_member_control_account_panel_transaction_info_label_date}></td>
      		<td><?php echo isset($date) ? nsdate($date,false) :"";?></td>
      	</tr>
        <tr class="glav">
            <td align="right"><{admin_member_control_account_panel_transaction_info_label_paysystem}></td>
      		<td><?php echo $pay_system;?></td>
      	</tr>
        <tr class="glav">
            <td align="right"><{admin_member_control_account_panel_transaction_info_label_amount}></td>
      		<td><?php echo $amount;?></td>
      	</tr>        
		</table>
        
        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
          <input type="button" class="button" value="<{admin_btn_back}>" 
                onClick="load_transactions({'sid':<?php echo $subscription_id;?>});" />
        </div>
        <br />                
