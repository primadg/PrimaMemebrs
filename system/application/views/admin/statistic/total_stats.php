<?php
/**
 * Total Statistics view
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
            <div class="header_subject"><{admin_stats_total_header_subject}></div>
            <div class="header_comment"><{admin_stats_total_header_comment}></div>
          </div>
        </div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />
        <table class="tab" align="center" style="width:700px">
        	<tr>
            	<td>        
                    <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
                        <table class='filter'>
                        	<tr>
                        		<td><{admin_stats_total_search_payment_date}>:</td>
                                <td>
                                    <input type="text" size="10" value="<?php echo (isset($filter['date_to']))?nsdate($filter['date_to'],false):''?>" name="date_from" id="date_from" class="<?php echo datepicker_class()?>" value="" /><label for='date_from'></label>&nbsp; &mdash; &nbsp;
                                </td>
                                <td><input type="text" size="10" value="<?php echo (isset($filter['date_from']))?nsdate($filter['date_from'],false):''?>" name="date_to" id="date_to" class="<?php echo datepicker_class()?>" value="" /><label for='date_to'></label>&nbsp;</td>
                                <td>
                                	<input type="button" class="button" value="<{admin_stats_total_btn_show}>" onclick="NewSearchParamsSet(); return false;" align="middle" />
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
            <td><{admin_stats_total_tbl_group_name}> / <a href="#" id="product_name"><{admin_stats_total_tbl_product_name}></a></td>
            <td><a href="#" id="percentage"><{admin_stats_total_tbl_percentage}></a></td>
            <td><a href="#" id="transactions_count"><{admin_stats_total_tbl_transactions}></a></td>
            <td><a href="#" id="amount"><{admin_stats_total_tbl_amount}></a></td>
          </tr>
    <?php  if(isset($total_stats) && @count($total_stats)): ?>
        <?php 
            //counter for product_groups
            $i = 0;
            //display all products groups collapsed?
            if ($total['products_count']>15)
            {
                $collapsed = true;
            }
            else
            {
                $collapsed = false;
            }
        ?>
        <?php  foreach ($total_stats as $product_group): ?>
          <tr class="dark handpointer" style="font-weight:bold;" onClick="$('#product_group_<?php echo $i;?>').toggle();return false;">
            <td align="left" title="<?php echo output($product_group['group_name'])?>"><a href="#"><?php echo word_wrap(output($product_group['group_name']),60,4)?></a></td>
            <td><?php echo amount_to_print($product_group['percentage'])?>%</td>
            <td><?php echo $product_group['transactions_count']?></td>
            <td align="right"><?php echo amount_to_print($product_group['amount'])?>&nbsp;<?php echo output($total['currency_code'])?></td>
          </tr>
          <tbody id="product_group_<?php echo $i++;?>" <?php echo ($collapsed) ? 'style="display:none;"' : ''?> >
            <?php  foreach ($product_group['products'] as $product): ?>
                <?php  if (!empty($product['product_name'])): ?>
          <tr class="light">
            <td align="left" style="padding-left:30px;" title="<?php echo output($product['product_name'])?>"><?php echo word_wrap(output($product['product_name']),60,4)?></td>
            <td><?php echo amount_to_print($product['percentage'])?>%</td>
            <td><?php echo $product['transactions_count']?></td>
            <td align="right"><?php echo (empty($product['amount']) ? amount_to_print(0) : output($product['amount']))?>&nbsp;<?php echo (empty($product['currency_code']) ? output($total['currency_code']) : output($product['currency_code']))?></td>
          </tr>
                <?php  endif; ?>
            <?php  endforeach; ?>
          </tbody>
        <?php  endforeach; ?>
          <tr class="dark" style="font-weight:bold;">
            <td align="left"><{admin_stats_total_tbl_groups_total}></td>
            <td><?php echo $total['percentage'];?>%</td>
            <td><?php echo $total['transactions_count'];?></td>
            <td align="right"><?php echo $total['amount'];?>&nbsp;<?php echo output($total['currency_code']);?></td>
          </tr>
    <?php  else: ?>
    <tr class="dark">
    <td colspan="4"><{admin_table_empty}></td>                                
    </tr>   
    <?php  endif; ?>
        </table>
        <br/>
        <div class="page">
            <?php  echo $pagers['pager'][1]; ?>
        </div>

</div>
<br />
