
        <div class="tema"><{admin_member_control_account_panel_transactions_list_page_title}>(<{admin_member_control_account_panel_transactions_list_label_subscr}>&nbsp;<?php echo (isset($subscr_id))?$subscr_id:'';?>)</div>

        <br />

        <div class="page">
            <?php echo $pager_node1; ?>
        </div>

        <table class="tab" align="center" width="690">
          <tr class="glav_big">
            <td><a href="#" id="trans_id"
            onClick = "load_transactions({sid: '<?php echo $subscr_id;?>',ord: 'by_trans_id'});return false;"><{admin_member_control_account_panel_transactions_list_table_transaction_id}></a>
            <?php echo ($sort_by=='by_trans_id') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
            </td>          
            <td><{admin_member_control_account_panel_transactions_list_table_transaction}></td>
            <td width="127px"><a href="#" id="transdate"
            onClick = "load_transactions({sid: '<?php echo $subscr_id;?>',ord: 'by_trans_date'});return false;"><{admin_member_control_account_panel_transactions_list_table_date}></a>
            <?php echo ($sort_by=='by_trans_date'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
            </td>
            <td><a href="#" id="paysys"
            onClick = "load_transactions({sid: '<?php echo $subscr_id;?>',ord: 'by_trans_paysys'});return false;"><{admin_member_control_account_panel_transactions_list_table_paysystem}></a>
            <?php echo ($sort_by=='by_trans_paysys') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
            </td>
            <td><a href="#" id="amount"
            onClick = "load_transactions({sid: '<?php echo $subscr_id;?>',ord: 'by_trans_amount'});return false;"><{admin_member_control_account_panel_transactions_list_table_amount}></a>
            <?php echo ($sort_by=='by_trans_amount') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
            </td>
          </tr>
            <?php 
            if( isset($items) && count($items) > 0 )
            {
                $tr_class = 'dark';
                foreach( $items as $row )
                { 
                    $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
            ?>            
            <tr class="<?php echo $tr_class;?>">
              <td><?php echo $row['id'];?></td>
              <td><a href="#"
              onClick = "transaction_info('<?php echo $row['id'];?>');return false;"><{admin_member_control_account_panel_transactions_list_label_details}></a></td>            
              <td><?php echo isset($row['date']) ? nsdate($row['date'],false) :"";?></td>
              <td><?php echo $row['pay_system'];?></td>
              <td><?php echo $row['summ'];?></td>
            </tr>
            <?php
                }
            }
            else
            {
                echo '<tr class="dark"><td colspan="5"><{admin_table_empty}></td></tr>';
            }            
            ?>          
        </table>

        <div class="page">
            <?php echo $pager_node2; ?>
        </div>

        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px;">
          <input type="button" class="button" value="<{admin_btn_back}>" 
                onClick="load_payments();" />
        </div>
        <br />        
        
        
