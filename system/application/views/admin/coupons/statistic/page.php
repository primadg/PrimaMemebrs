
<script type="text/JavaScript">
    $(document).ready(set_dp);
    var per_page = <?php echo $per_page;?>;
</script>

<!-- Coupons Statistic -->

        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico_coupon_create.png" width="32" height="32" alt="<{admin_coupon_statistic_page_title}>"></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_coupon_statistic_page_title}></div>
            <div class="header_comment"><{admin_coupon_statistic_page_desc}></div>
          </div>
        </div>

        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br />
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div  id="error_value" class="box_err"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>

        <table class="tab" align="center" width="675px" style="padding: 20px 0 30px 0px;" border="0"><tr><td>
            <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
                <table class='filter'>
                    <tr>
                        <td align="left">
                            <{admin_coupon_statistic_search_label_from}>&nbsp;
                            <input type="text" size="10" name="date_from" id="date_from" class="<?php echo datepicker_class();?>" value="<?php if(isset($date_from)){echo $date_from;}?>">&nbsp; <{admin_coupon_statistic_search_label_to}> &nbsp;
                            <input type="text" size="10" name="date_to" id="date_to" class="<?php echo datepicker_class();?>" value="<?php if(isset($date_to)){echo $date_to;}?>">&nbsp;&nbsp;
                            <{admin_coupon_statistic_search_label_coupon_code}>&nbsp;<input id="search_by_code" type="text" size="10"  value="<?php if(isset($search_val)){echo $search_val;}?>">
                            <input type="button" class="button" value="<{admin_coupon_statistic_search_button_search}>" onClick = "coupons_statistic({is_search: true});return false;" />&nbsp;|&nbsp;
                            <input type="button" class="button" value="<{admin_coupon_statistic_search_button_clear}>" onClick="field_clear({1:'search_by_code',2:'date_from',3:'date_to'});" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td></tr></table>
        <br clear='all'/>

<!--
        <table class="tab" align="center" width="650" style="padding: 20px 0 30px 0px;" border="0"><tr><td>
            <fieldset><legend onclick="filterResize(this)"><{admin_label_filter}></legend>
                <table class='filter'>
                    <tr style="font-weight:bold;font-size:14px">
                        <td colspan="2" style="1background-color: #F9F9F9">
                            <div style="float:left;"><{admin_coupon_statistic_search_label_period}></div>
                            <div style="float:right;margin-right:10px;"><{admin_coupon_statistic_search_label_coupon_code}></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left"  style="width:420px;">
                            <{admin_coupon_statistic_search_label_from}>&nbsp;
                            <input type="text" size="10" name="date_from" id="date_from" class="<?php echo datepicker_class();?>" value="<?php if(isset($date_from)){echo $date_from;}?>">&nbsp; <{admin_coupon_statistic_search_label_to}> &nbsp;
                            <input type="text" size="10" name="date_to" id="date_to" class="<?php echo datepicker_class();?>" value="<?php if(isset($date_to)){echo $date_to;}?>">
                        </td>
                        <td>
                            <{admin_coupon_statistic_search_label_and}>&nbsp;<input id="search_by_code" type="text" size="10"  value="<?php if(isset($search_val)){echo $search_val;}?>" style="margin-left:45px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:5px; margin-bottom: -20px; text-align: right" colspan="2">
                            <input type="button" class="button" value="<{admin_coupon_statistic_search_button_clear}>" onClick="field_clear({1:'search_by_code',2:'date_from',3:'date_to'});" /> |
                            <input type="button" class="button" value="<{admin_coupon_statistic_search_button_search}>" onClick = "coupons_statistic({is_search: true});return false;" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td></tr></table>
        <br clear='all'/>
-->

        <div class="page">
            <?php echo $pager_node1;?>
        </div>

        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="94" style="white-space: nowrap; padding: 0 2px;"><nobr><a href="#" <?php echo ($sort_by=='code') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'code'});return false;"><{admin_coupon_statistic_table_coupon_code_group}></a><?php echo $sort_by=='code' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></nobr></td>
            <td style="white-space: nowrap; padding: 0 2px;"><nobr><a href="#" <?php echo ($sort_by=='change_time'||$sort_by=='begin_date'||$sort_by=='') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'change_time'});return false;"><{admin_coupon_statistic_table_change_time}></a><?php echo ($sort_by=='change_time'||$sort_by=='begin_date'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></nobr></td>
            <td width="94"><a href="#" <?php echo ($sort_by=='member') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'member'});return false;"><{admin_coupon_statistic_table_member}></a><?php echo $sort_by=='member' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td width="150"><a href="#" <?php echo ($sort_by=='product') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'product'});return false;"><{admin_coupon_statistic_table_product}></a><?php echo $sort_by=='product' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td width="150"><{admin_coupon_statistic_table_period}></td>
            <td width="94"><a href="#" <?php echo ($sort_by=='discount') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'discount'});return false;"><{admin_coupon_statistic_table_discount}></a><?php echo $sort_by=='discount' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td width="94"><a href="#" <?php echo ($sort_by=='amount') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'amount'});return false;"><{admin_coupon_statistic_table_amount}></a><?php echo $sort_by=='amount' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
            <td width="40"><a href="#" <?php echo ($sort_by=='paid') ? "class='sort'" : "";?>
            onClick = "coupons_statistic({ord: 'paid'});return false;"><{admin_coupon_statistic_table_paid}></a><?php echo $sort_by=='paid' ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?></td>
          </tr>
          
          <?php echo $rows; ?>
          
        </table>
          
        <div class="page">
            <?php echo $pager_node2;?>
        </div>
