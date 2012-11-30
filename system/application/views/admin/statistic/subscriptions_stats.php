<?php
/**
 * Subscriptions Statistics view
 *
 * @author Makarenko Sergey
 * @copyright 2008
 */
?>

<div id='main_panel_div'>

        <div id='temp_vars_set' style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_transaction_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_stats_subscr_header_subject}></div>
            <div class="header_comment"><{admin_stats_subscr_header_comment}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />


		<table class="tab" align="center" style="width:700px">
        	<tr>
            	<td>        
        			<fieldset>
                    	<legend class="handpointer" onclick="filterResize(this)">
                        	<{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span>
                        </legend>
                        <table class='filter'>
        					<tr>
        						<td style="padding-left: 55px;">
                                	<{admin_stats_subscr_search_by}>:
                                </td>
                                <td>
                                	<select name="search_by" style="width: 100px;">
                                        <option value="subscr_id" <?php echo (isset($filter[0])&&$filter[0]=="subscr_id")?"selected":""?>><{admin_stats_subscr_search_option_subscr_id}></option>
                                        <option value="regular_price" <?php echo (isset($filter[0])&&$filter[0]=="regular_price")?"selected":""?>><{admin_stats_subscr_search_option_price}></option>
                                        <option value="user_name" <?php echo (isset($filter[0])&&$filter[0]=="user_name")?"selected":""?>><{admin_stats_subscr_search_option_user_name}></option>
                                        <option value="product_name" <?php echo (isset($filter[0])&&$filter[0]=="product_name")?"selected":""?>><{admin_stats_subscr_search_option_product_name}></option>
                                    </select>
                                </td>
                                <td>
                                	<input name="search_word" value="<?php echo (isset($filter[1]))?output($filter[1]):''?>" type="text" style="width: 300px;" />
                                </td>
                             </tr>
                         </table>
                         <table class='filter'>
        					<tr>
        						<td style="padding-left: 31px;">
                                	<{admin_stats_subscr_search_payment_date}>:
                                </td>
                                <td>
                                	<input type="text" size="10" value="<?php echo (isset($filter[2]))?output($filter[2]):''?>" name="date_from" id="date_from" class="<?php echo datepicker_class();?>" value="" />
                                </td>
                                <td>
                                	<label for='date_from'></label>
                                </td>
                                <td>
                                	&nbsp; &mdash; &nbsp;
                                </td>
                                <td>
                                	<input type="text" size="10" value="<?php echo (isset($filter[3]))?output($filter[3]):''?>" name="date_to" id="date_to" class="<?php echo datepicker_class();?>" value="" />
                                </td>
                                <td>
                                	<label for='date_to'></label>
                                </td>
                                <td>
                                	<input type="button" class="button" value="<{admin_stats_subscr_btn_show}>" onclick="NewSearchParamsSet(); return false;" align="middle" />
                                </td>
                            </tr>
                         </table>    
                    </fieldset>
                    </td>
             	</tr>
         	</table>


       

        <br />

        <div class="page">
            <?php  echo $pagers['pager'][0]; ?>
        </div>

        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td><a href="#" id="subscr_id"><{admin_stats_subscr_tbl_subscription_id}></a></td>
            <td><a href="#" id="user_name"><{admin_stats_subscr_tbl_user_name}></a></td>
            <td><a href="#" id="product_name"><{admin_stats_subscr_tbl_product_name}></a></td>
            <td width="70px"><a href="#" id="subscr_date"><{admin_stats_subscr_tbl_date}></a></td>
            <td><{admin_stats_subscr_tbl_transactions}></td>
            <td><a href="#" id="subscr_type"><{admin_stats_subscr_tbl_subscr_type}></a></td>
            <td><a href="#" id="regular_price"><{admin_stats_subscr_tbl_regular_price}></a></td>
          </tr>
    <?php  if(isset($subscr) && @count($subscr)): ?>
        <?php  $i = 0; ?>
        <?php  foreach ( $subscr as $elem ): ?>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><?php echo output($elem['subscr_id'])?></td>
            <td title="<?php echo output($elem['user_name'])?>"><?php echo word_wrap(output($elem['user_name']),15,4)?></td>
            <td title="<?php echo output($elem['product_name'])?>"><?php echo word_wrap(output($elem['product_name']),25,4)?></td>
            <td><?php echo isset($elem['subscr_date']) ? nsdate($elem['subscr_date'],false) :"";?></td>
            <td><a href="#" onclick="ShowTransactions(<?php echo $elem['subscr_id']?>); return false;"><{admin_stats_subscr_tbl_href_details}></a></td>
            <td><?php echo ($elem['subscr_type']==1)? "<{admin_stats_subscr_tbl_onetime}>" : "<{admin_stats_subscr_tbl_recurring}>";?></td>
            <td align="right"><?php echo output($elem['regular_price'])?>&nbsp;<?php echo output($elem['currency_code'])?></td>
          </tr>
        <?php  endforeach; ?>
    <?php  else: ?>
    <tr class="dark">
    <td colspan="7"><{admin_table_empty}></td>                                
    </tr>
    <?php  endif; ?>
        </table>

        <div class="page">
            <?php  echo $pagers['pager'][1]; ?>
        </div>

</div>
<br />
