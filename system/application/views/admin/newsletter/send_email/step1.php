
<!-- Newsletter Send Email Step 1 -->

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_send_email_step1_page_title}></div>
            <div class="header_comment"><{admin_newsletter_send_email_step1_page_desc}></div>
          </div>
        </div>

        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_from_empty" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_from_empty}></div>
            <div  id="jsvalid_error_from_toolong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_from_toolong}></div>
            <div  id="jsvalid_error_from_wrong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_from_email_wrong}></div>
            <div  id="jsvalid_error_template_empty" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_template_wrong}></div>
            <div  id="jsvalid_error_template_wrong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_template_wrong}></div>            
            <div  id="jsvalid_error_users_empty" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_to_empty}></div>
            <div  id="jsvalid_error_users_wrong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_users_wrong}></div>
            <div  id="jsvalid_error_pgroups_empty" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_to_empty}></div>
            <div  id="jsvalid_error_pgroups_wrong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_pgroups_wrong}></div>
            <div  id="jsvalid_error_products_empty" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_field_to_empty}></div>
            <div  id="jsvalid_error_products_wrong" class="box_err" style="display: none"><{admin_newsletter_send_email_step1_error_products_wrong}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        <br /> 
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>



        <table class="settings" align="center" style="margin-top: 10px;">
          <tr class="glav">
    				<td align="right"><{admin_newsletter_send_email_step1_label_from}> <span style="color: red;">*</span></td>
    				<td><input id="from" type="text" style="width: 400px;" 
                    value="<?php echo $from;?>" /></td>
    			</tr>
          <tr>
            <td><{admin_newsletter_send_email_step1_label_template}> </td>
            <td>
                <select id="template">
                <?php 
                    foreach($template_list as $tpl_info)
                    {
                ?>    
                    <option value="<?php echo $tpl_info['id']; ?>"
                    <?php if($tpl_info['id'] == $sel_tpl_id){echo " selected ";} ?>
                    ><?php echo $tpl_info['name']; ?></option>
                <?php
                    }
                ?>                
                </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              
              <table align="center">
      				<tr>
      					<td>
      						<select id="user_category" style="width: 130px;">
      						<option value="all_user"><{admin_newsletter_send_email_step1_user_category_all}></option>
      						<option value="all_expired_user"><{admin_newsletter_send_email_step1_user_category_all_expired}></option>
      						<option value="all_active_user"><{admin_newsletter_send_email_step1_user_category_all_active}></option>
      						</select>
      					</td>
      					<td>
      						<select id="product_group" style="width: 130px;"
                            onChange="send_email_load_products();">
      						<option value="0"><{admin_newsletter_send_email_step1_group_category_all}></option>
                            <?php 
                                foreach($product_group_list as $pgroup_item)
                                {
                            ?>    
                                <option value="<?php echo $pgroup_item['id']; ?>"><?php echo $pgroup_item['name']; ?></option>
                            <?php
                                }
                            ?>
      						</select>
      					</td>
      					<td>
      						<select id="product" style="width: 130px;">
      						<option value="0"><{admin_newsletter_send_email_step1_product_category_all}></option>
                            <?php 
                                foreach($product_list as $prod_item)
                                {
                            ?>    
                                <option value="<?php echo $prod_item['id']; ?>"><?php echo $prod_item['name']; ?></option>
                            <?php
                                }
                            ?>                            
      						</select>
      					</td>
      					<td><input type="button" value="<{admin_btn_add}>" class="button" 
                        onClick="add_email(); return false;" /></td>
      				</tr>
      				</table>
              
            </td>
          </tr>
          <tr class="glav">
            <td align="right" valign="top"><{admin_newsletter_send_email_step1_label_to}> <span style="color: red;">*</span></td>
            <td id="section_email_to" valign="bottom">
                <div id="orig" style="width: 400px; height: 20px; padding-top: 4px; display:none">
                    <div style="float: left;">
                        <div name="email_num"  style="float: left;"></div>
                        <div name="user_cat" style="float: left;"></div>
                        <div name="group_id" style="float: left;display: none"></div>
                        <div name="group_name" style="float: left;"></div>
                        <div name="prod_id" style="float: left;display: none"></div>
                        <div name="prod_name" style="float: left;"></div>
                    </div>
                    <div name="del_email" style="text-align: right; vertical-align: bottom"><a 
                        href="#" onclick=""><img src="./img/ico_delete.png" width="16" height="16" /></a></div>
                </div>
            </td>
          </tr>
  	    </table>


<div class="after_table" style="padding-top: 10px;"><input type="button" class="button" value="<{admin_btn_next}>" onClick="step1_next();" /></div>
<br />
