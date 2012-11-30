
<!--Edit coupons group -->

        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/page_edit32.png" width="32" height="32"alt="Create groups" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_coupon_edit_coupons_page_title}></div>
            <div class="header_comment"><{admin_coupon_edit_coupons_page_desc}></div>
          </div>
        </div>
        
        
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_empty}></div>
            <div  id="jsvalid_error_name_toolong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_name_toolong}></div>
            <div  id="jsvalid_error_descr_toolong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_comment_toolong}></div>
            <div  id="jsvalid_error_coupons_count_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_coupons_count_small_value}></div>
            <div  id="jsvalid_error_per_user_use_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_mbr_use_count_small_value}></div>
            <div  id="jsvalid_error_coupon_use_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_use_count_small_value}></div>
            <div  id="jsvalid_error_code_len_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_code_length_small_value}></div>
            <div  id="jsvalid_error_discount_type_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_discount_type_wrong}></div>
            <div  id="jsvalid_error_discount_val_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_discount_val_notint}></div>
            <div  id="jsvalid_error_dont_use_time_limit_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_checkbox_dates_limit_wrong}></div>
            <div  id="jsvalid_error_locked_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_checkbox_locked_wrong}></div>
            <div  id="jsvalid_error_products_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_products_notenough_elements}></div>
            <div  id="jsvalid_error_date_wrong" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_field_date_wrong}></div>
            <div  id="jsvalid_error_dates_compare" class="box_err" style="display: none"><{admin_coupon_create_coupons_error_dates_compare}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        
        <table class="settings table_pos" style="margin-top: 10px;" >
          <tr class="glav">
            <td align="right" class="table_first_td"><{admin_coupon_edit_coupons_label_coupon_name}></td><td> <span style="color: red;">*</span></td>
            <td><input type="text" style="width: 350px;" maxlength="254" 
            id="coupons_name" value="<?php echo $items['name'];?>" /></td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_coupon_name_tooltip');?>
              <!--how much times coupon can be used enter big value (ex.:99999) for unlimited-->
            </td>
          </tr>
          <tr  class="glav">
            <td align="right"><{admin_coupon_edit_coupons_label_coupons_usage_count}></td><td> <span style="color: red;">*</span></td>
            <td><input type="text" style="width: 350px;" 
            id="use_count" value="<?php echo $items['available_use'];?>" /></td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_use_count_tooltip');?>
              <!--how many coupons need to be generated-->
            </td>
          </tr>            
          <tr class="glav">
            <td align="right"><{admin_coupon_edit_coupons_label_member_coupons_usage_count}></td><td> <span style="color: red;">*</span></td>
            <td><input type="text" style="width: 350px;" 
            id="mbr_use_count" value="<?php echo $items['use_per_user'];?>" />&nbsp;</td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_member_coupons_usage_count_tooltip');?>
              <!--how much times coupon can be used by member-->
            </td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_coupon_edit_coupons_label_discount}></td><td> <span style="color: red;">*</span></td>
            <td>
              <input id="discount_val"  type="text" style="width: 50px;" value="<?php echo $items['discount_val'];?>" />
              <select id="discount_type" style="width:50px;">
                <option value="prc"><{admin_coupon_create_coupons_field_discount_type_percent}>
                <option value="val"
                	<?php if($items['discount_type'] == 'val'){echo " selected ";}?>
                >
                <?php
                  if(config_get('system','config','currency_code')!=false)
                    {echo config_get('system','config','currency_code');}
                  else 
                    {echo "<{admin_coupon_create_coupons_field_discount_type_value}>";}
                ?>
                </select>
            </td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_discount_tooltip');?>
              <!--order discount-->
            </td>
          </tr>
          <tr class="glav">
            <td align="right" valign="top"><{admin_coupon_edit_coupons_label_comment}></td><td></td>
            <td><textarea id="descr" style="width: 350px;height: 200px;" ><?php echo $items['descr'];?></textarea></td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_comment_tooltip');?>
              <!--comment - visible only for admin-->
            </td>
          </tr>
          <tr>
            <td align="right" valign="top"><{admin_coupon_edit_coupons_label_dates}></td><td></td>
            <td>
              <div style="height: 25px;">
                <div style="float: left;"><input id="no_dates_limit" type="checkbox" 
                <?php if($items['time_limit'] == '0'){echo " checked ";}?>
                onClick="hide_dates();" /></div>
                <div style="padding-top: 2px;"><{admin_coupon_edit_coupons_checkbox_dates_desc}></div>
              </div>
              <div id="limit_dates" <?php echo ($items['time_limit'] == '0') ? "style='display: none;'" : "";?>>
              <input id="start_date" type="text" size="10" name="date_from" class="<?php echo datepicker_class();?>" value="<?php echo isset($items['start_date']) ? nsdate($items['start_date'],false) :"";?>" />
              &nbsp; - &nbsp;
              <input id="end_date" type="text" size="10" name="date_to" class="<?php echo datepicker_class();?>" value="<?php echo isset($items['end_date']) ? nsdate($items['end_date'],false) :"";?>" onChange="uncheck('no_dates_limit');" />&nbsp;
              </div>
            </td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_dates_tooltip');?>
              <!--date range when coupon can be used-->
            </td>
          </tr>
          <tr>
            <td align="right"><{admin_coupon_edit_coupons_label_locked}></td><td></td>
            <td><input id="locked" type="checkbox"
            <?php if($items['locked'] == '1'){echo " checked ";}?> /></td>
            <td><?php echo create_tooltip_div('admin_coupon_create_coupons_label_locked_tooltip');?>
              <!--disable this coupons batch, but keep in database it can be enabled later-->
            </td>
          </tr>
          <tr>
            <td align="right" valign="top" style="padding-top: 5px;"><{admin_coupon_edit_coupons_label_products}></td>
            <td style="vertical-align: top; padding-top: 5px;"> <span style="color: red;">*</span></td>
            <td>
                <select id="products" style="width: 360px; height: 100px;" multiple>
                    <?php echo $products_node;?>
                </select>
            </td>
            <td valign="top" style="padding-top: 7px;"><?php echo create_tooltip_div('admin_coupon_create_coupons_label_products_tooltip');?>
              <!--coupons can be used with selected products hold Ctrl Key to select multple products if nothing selected, coupon can be used with ANY product-->
            </td>
          </tr>
          <tr>
          	<td colspan="3" align="center" style="padding-top:15px;">
            	<input type="button" class="button" value="<{admin_coupon_edit_coupons_button_save}>" onClick="save_coupons_group('<?php echo $items['id'];?>');"/>&nbsp;
          		<input type="button" class="button" value="<{admin_coupon_edit_coupons_button_cancel}>" onClick="coupons_group_list();"/>
            </td>
          </tr>
        </table>
<br />
