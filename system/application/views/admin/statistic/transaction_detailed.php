<?php
/**
 * Transaction Detailed Statistics view
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
            <div class="header_subject"><{admin_stats_detailed_transact_header_subject}> (<{admin_stats_detailed_transact_header_subject_part2}>: <?php echo $transaction_id?>)</div>
            <div class="header_comment"><{admin_stats_detailed_transact_header_comment}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />

        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="200px"><{admin_stats_detailed_transact_tbl_title_field_name}></td>
            <td><{admin_stats_detailed_transact_tbl_title_field_value}></td>
          </tr>
    <?php  if(isset($transact)): ?>
        <?php  $i = 0; ?>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_pay_system}></td>
            <td align="left"><?php echo word_wrap(output($transact['pay_system']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_completed}></td>
            <td align="left"><?php echo word_wrap(output($transact['completed']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_transact_date}></td>
            <td align="left"><?php echo isset($transact['transdate']) ? nsdate($transact['transdate']) :"";?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_amount}></td>
            <td align="left"><?php echo word_wrap(output($transact['summ']),45,4)?>&nbsp;<?php echo output($transact['currency_code'])?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_billing_name}></td>
            <td align="left" title="<?php echo output($transact['billing_name'])?>"><?php echo word_wrap(output($transact['billing_name']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_country_code}></td>
            <td align="left" title="<?php echo output($transact['country_code'])?>"><?php echo word_wrap(output($transact['country_code']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_state_code}></td>
            <td align="left" title="<?php echo output($transact['state_code'])?>"><?php echo word_wrap(output($transact['state_code']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_city}></td>
            <td align="left" title="<?php echo output($transact['city'])?>"><?php echo word_wrap(output($transact['city']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_street}></td>
            <td align="left" title="<?php echo output($transact['street'])?>"><?php echo word_wrap(output($transact['street']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_zip_code}></td>
            <td align="left" title="<?php echo output($transact['zip_code'])?>"><?php echo word_wrap(output($transact['zip_code']),45,4)?></td>
          </tr>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_phone}></td>
            <td align="left" title="<?php echo output($transact['phone'])?>"><?php echo word_wrap(output($transact['phone']),45,4)?></td>
          </tr>
        <?php  if( is_array($transact['additional_fields']) && !empty($transact['additional_fields']) ): ?>
            <?php  foreach ( $transact['additional_fields'] as $field_name=>$field_value ): ?>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><?php echo output($field_name)?></td>
            <td align="left" title="<?php echo output($field_value)?>"><?php echo word_wrap(output($field_value),45,4)?></td>
          </tr>
            <?php  endforeach; ?>
        <?php  endif; ?>
        <?php  if( is_array($transact['info']) && !empty($transact['info']) ): ?>
          <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
            <td><{admin_stats_detailed_transact_tbl_payment_info}></td>
            <td align="left">
            <?php  foreach ( $transact['info'] as $field_name=>$field_value ): ?>
              <?php echo word_wrap(output($field_name),45,4)?> = <?php echo word_wrap(output($field_value),45,4)?> <br/>
            <?php  endforeach; ?>
            </td>
          </tr>
        <?php  endif; ?>
    <?php  else: ?>
    <tr class="dark">
    <td colspan="2"><{admin_table_empty}></td>                                
    </tr>          
    <?php  endif; ?>
        </table>

    <?php  if(isset($subscription_id_for_feedback)): ?>
        <br/>
        <div style="float: right; padding-right: 30px;"><a href="#" onclick="ShowTransactionDetails(<?php echo intval($subscription_id_for_feedback);?>); return false;"><{admin_stats_detailed_transact_feedback_link}></a></div>
    <?php  endif; ?>

</div>
<br />
