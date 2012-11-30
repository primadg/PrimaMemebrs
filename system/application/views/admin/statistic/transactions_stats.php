<?php
/**
 * Transactions Statistics view
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
            <div class="header_subject"><{admin_stats_transact_header_subject}> (<{admin_stats_transact_header_subject_part2}>: <?php echo $subscription_id?>)</div>
            <div class="header_comment"><{admin_stats_transact_header_comment}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />

        <div class="filter">
          <div style="padding-left: 75px;">
            <div style="padding-top: 5px;"><{admin_stats_transact_search_by}>:</div>
            <div style="padding-top: 3px;">
              <select name="search_by" style="width: 100px;">
                <option value="summ" <?php echo (isset($filter[0])&&$filter[0]=="summ")?"selected":""?>><{admin_stats_transact_search_option_summ}></option>
                <option value="user_name" <?php echo (isset($filter[0])&&$filter[0]=="user_name")?"selected":""?>><{admin_stats_transact_search_option_user_name}></option>
                <option value="product_name" <?php echo (isset($filter[0])&&$filter[0]=="product_name")?"selected":""?>><{admin_stats_transact_search_option_product_name}></option>
              </select>
            </div>
            <div><input name="search_word" value="<?php echo (isset($filter[1]))?output($filter[1]):''?>" type="text" style="width: 300px;" /></div>
          </div>
        </div>

        <div class="filter">
          <div style="padding-left: 50px; width:650px;">
            <div style="padding-top: 6px;"><{admin_stats_transact_search_payment_date}>:</div>
              <div style="float:left; width:140px;"><input type="text" size="10" value="<?php echo (isset($filter[2]))?output($filter[2]):''?>" name="date_from" id="date_from" class="<?php echo datepicker_class()?>" value="" /><label for='date_from'></label>&nbsp; &mdash; &nbsp;</div>
              <div style="float:left; width:130px;"><input type="text" size="10" value="<?php echo (isset($filter[3]))?output($filter[3]):''?>" name="date_to" id="date_to" class="<?php echo datepicker_class()?>" value="" /><label for='date_to'></label>&nbsp;</div>
              <input type="button" class="button" value="<{admin_stats_transact_btn_show}>" onclick="NewSearchParamsSet(); return false;" align="middle" />
            </div>

        </div>
        <br />

        <div class="page">
            <?php  echo $pagers['pager'][0]; ?>
        </div>

        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td><a href="#" id="transact_id"><{admin_stats_transact_tbl_transaction_id}></a></td>
            <td><a href="#" id="user_name"><{admin_stats_transact_tbl_user_name}></a></td>
            <td><a href="#" id="product_name"><{admin_stats_transact_tbl_product_name}></a></td>
            <td width="127px"><a href="#" id="transdate"><{admin_stats_transact_tbl_date}></a></td>
            <td><{admin_stats_transact_tbl_more_details}></td>
            <td><a href="#" id="pay_system"><{admin_stats_transact_tbl_pay_system}></a></td>
            <td><a href="#" id="summ"><{admin_stats_transact_tbl_amount}></a></td>
          </tr>
    <?php  if(isset($transact) && @count($transact)): ?>
        <?php  $i = 0; ?>
        <?php  foreach ( $transact as $elem ): ?>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><?php echo output($elem['transact_id'])?></td>
            <td title="<?php echo output($elem['user_name'])?>"><?php echo word_wrap(output($elem['user_name']),15,4)?></td>
            <td title="<?php echo output($elem['product_name'])?>"><?php echo word_wrap(output($elem['product_name']),25,4)?></td>
            <td><?php echo nsdate($elem['transdate'])?></td>
            <td><a href="#" onclick="ShowTransactionDetails(<?php echo $elem['transact_id']?>); return false;"><{admin_stats_transact_tbl_href_details}></a></td>
            <td><?php echo output($elem['pay_system'])?></td>
            <td align="right"><?php echo $elem['summ']?>&nbsp;<?php echo output($elem['currency_code'])?></td>
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
