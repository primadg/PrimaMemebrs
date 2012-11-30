

<div class="tema"><{admin_member_control_account_panel_payments_page_title}></div>
          
          <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
            <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div class="box" id="msg_value"></div>
            <div  id="jsaction_msg_add_invoice_success" class="box" style="display: none"><{admin_member_control_account_panel_payments_msg_add_success}></div>            
            <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
          </div>          
          
          <br />
          
          <div class="page">
            <?php echo $pager_node1; ?>
          </div>
          
          <table class="tab" align="center" width="680" style="padding-bottom: 10px;">
            <tr class="glav_big">
              <td><a href="#"
              onClick = "load_payments({ord: 'by_product'});return false;"><{admin_member_control_account_panel_payments_table_product}></a>
              <?php echo ($sort_by=='by_product') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_payments({ord: 'by_date'});return false;"><{admin_member_control_account_panel_payments_table_date}></a>
              <?php echo ($sort_by=='by_date'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><{admin_member_control_account_panel_payments_table_transactions}></td>
              <td><a href="#"
              onClick = "load_payments({ord: 'by_type'});return false;"><{admin_member_control_account_panel_payments_table_subscription_type}></a>
              <?php echo ($sort_by=='by_type') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_payments({ord: 'by_price'});return false;"><{admin_member_control_account_panel_payments_table_price}></a>
              <?php echo ($sort_by=='by_price') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
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
              <td><?php //Emphesize the name if subscription status is active
                    if( intval($row['status']) == 1)
                    {
                        echo "<font style=\"color:#FF0000;\">" .
                            $row['product_name'] .
                            "</font>";
                    }
                    else
                    {
                        echo $row['product_name'];
                    }
                  ?></td>
              <td><?php echo isset($row['cdate']) ? nsdate($row['cdate'],false) :"";?></td>
              <td><a href="#"
       onClick = "load_transactions({'sid':<?php echo $row['id'];?>});return false;"><{admin_member_control_account_panel_payments_table_details}></a></td>
              <td><?php echo $row['type'];?></td>
              <td><?php echo $row['regular_price'];?></td>
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
           
          <div>
          <a href="#" onClick="show_payment_form(<?php echo $accnt_is_expired;?>);return false;" style="margin-left: 20px;"><{admin_member_control_account_panel_payments_link_add_payment}></a>
          </div>
          
        <br/>
          
        <div id="sub_error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="sub_error_value" class="box_err"></div>
            <div  id="sub_error_member_expired" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_error_account_expired}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>          
          
            
 		  <div id="show_hide_add_invoice_div" style="display: none;">
          <div class="tema"><{admin_member_control_account_panel_payments_form_title}></div>

        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_member_id_wrong" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_member_empty}></div>            <div  id="jsvalid_error_product_id_wrong" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_product_empty}></div>
            <div  id="jsvalid_error_period_empty" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_period_empty}></div>
            <div  id="jsvalid_error_payment_system_wrong" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_pay_system_empty}></div>
            <div  id="jsvalid_error_transaction_toolong" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_transaction_length}></div>
            <div  id="jsvalid_error_domain_name_toolong" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_domain_name_length}></div>
            <div  id="jsvalid_error_transaction_empty" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_transaction_empty}></div>
            <div  id="jsvalid_error_domain_name_empty" class="box_err" style="display: none"><{admin_member_control_account_panel_payments_add_error_domain_name_empty}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
          
          <table class="settings" align="center">
          <tr class="glav">
            <td align="right"><{admin_member_control_account_panel_payments_form_field_product}> <span style="color: red;">*</span></td>
            <td>
            <input type="hidden" id="product" value="0" />
            <select id="product_type" style="width: 230px;"
            onChange="change_price_selector(this.value);">
                <option></option>
            <?php 
            if( isset($products) && count($products) > 0 )
            {
                foreach( $products as $item )
                { 
            ?>            
                    <option value="<?php echo $item['id'].'-'.$item['product_type'];?>"><?php echo $item['name'];?></option>
             <?php
                }
            }
            ?>            
            </select> 
            </td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_member_control_account_panel_payments_form_field_period}> <span style="color: red;">*</span></td>
            <td>
            <?php 
            if( isset($products) && count($products) > 0 )
            {
                foreach( $products as $item )
                { 
                    $periods = array('day','month','month3','month6','year','unlimit');
            ?>  
                    <select id="period-<?php echo $item['id'];?>" style="width: 230px;display:none">
                    <?php
                        $is_free = true;
                        foreach( $periods as $pname)
                        {
                            if( $item[$pname] > 0 )
                            {
                                $is_free = false;
                    ?>
                        <option value="<?php echo $pname.'-'.$item[$pname];?>"><{admin_member_control_account_panel_payments_add_period_<?php echo $pname ?>}> - <?php echo $item[$pname];?></option>
                    <?php
                            }
                        }
                    ?>
                    <?php
                        if($is_free)
                        {
                    ?>
                        <option value="unlimit-0"><{admin_member_control_account_panel_payments_add_period_unlimit}></option>
                    <?php
                        }
                    ?>
                    </select>            
             <?php
                }
            }
            ?>            
            </td>
          </tr>
          <tr class="glav <?php echo isset($only_free_product)?"hidden_element":"";?>">
            <td align="right"><{admin_member_control_account_panel_payments_form_field_payment_system}> <span style="color: red;">*</span></td>
            <td>
              <select id="payment_system" style="width: 230px;">
                <option></option>
                <?php 
                if( isset($payment_systems) && count($payment_systems) > 0 )
                {
                    foreach( $payment_systems as $key=>$val )
                    { 
                ?>            
                        <option value="<?php echo $key;?>"><?php echo $val['name'];?></option>
                <?php
                    }
                }
                ?>              
              </select>
            </td>
          </tr>
          <tr class="glav">
            <td id="transaction_type" align="right">
            	<span id="type_1"><{admin_member_control_account_panel_payments_form_field_transaction_id}> </span>
                <span id="type_2" style="display:none;"><{admin_member_control_account_panel_payments_form_field_domen_name}> </span>
                <span style="color: red;">*</span>
            </td>
            <td><input id="transaction" type="text" style="width: 220px" maxlength="64" /></td>
          </tr>
          </table>
          
          <div class="after_table" style="padding-bottom: 10px;">
            <input type="button" id="button" class="button_big" disabled="disabled" value="<{admin_member_control_account_panel_payments_form_button_add_invoice}>" onClick="add_payment()" />&nbsp;
          </div>
<br />
