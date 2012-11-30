<div id='main_panel_div'>
<!-- Newsletter Send Email Step 1 -->
<div id='temp_vars_set'style="display:none;"><?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?></div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_send_email_step1_page_title}></div>
            <div class="header_comment"><{admin_newsletter_send_email_step1_page_desc}></div>
          </div>
        </div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        
        <table class="settings" align="center" style="margin-top: 10px;">
                    <tr>
            <td colspan="2">
              <table align="center">
      				<tr>
      					<td >
      						<!--<div id="ffb7"></div>-->
                            <select id="user_category" name="user_category" style="width: 150px;" <?php echo isset($is_one_memeber)&&$is_one_memeber ? "disabled='disabled'" : "";?> >
      						<?php 
                            if(isset($user_category)&&is_array($user_category))
                            {
                                foreach($user_category as $key=>$value)
                                {				
                                    ?>
                                    <option style="width: 250px;" value="<?php echo $key?>" ><?php echo $value?></option>
                                    <?php 
                                }
                            }
                            if(isset($users)&&is_array($users))
                            {
                                foreach($users as $value)
                                {				
                                    ?>
                                    <option style="width: 250px;" value="<?php echo $value['id']?>" ><?php echo $value['name']?></option>
                                    <?php 
                                }
                            }
                            ?>
      						</select>
      					</td>
      					<td>
      						<select id="product_group" name="product_group" style="width: 150px;"
                            onChange="loadProducts();" <?php echo isset($is_one_memeber)&&$is_one_memeber ? "disabled='disabled'" : "";?>>
      						<option value="0"><{admin_newsletter_send_email_step1_group_category_all}></option>
                            <?php
                            if(isset($product_groups)&&is_array($product_groups))
                            {
                                foreach($product_groups as $value)
                                {
                                    ?>    
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                    <?php
                                }
                            }
                            ?>
      						</select>
      					</td>
      					<td>
      						<select id="product" name="product" style="width: 150px;" disabled="disabled" >
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
      				</tr>
      				</table>
            </td>
          </tr>          
          
          <tr>
            <?php if(isset($emails)&&is_array($emails)&&count($emails)){?>
            <td><{admin_newsletter_send_email_step1_label_template}> </td>
            <td style="width: 420px;">
                <select id="template" name="template">
                <?php 
                if(isset($emails)&&is_array($emails))
                {
                    foreach($emails as $value)
                    {				
                        ?>
                        <option style="width: 250px;" value="<?php echo $value['id']?>" ><?php echo output($value['name']);?></option>
                        <?php 
                    }
                    
                }
                ?>
                </select>
                <input type="checkbox" onclick="editTemplate();" name="edit_template" id="edit_template" style="border: 0px;"/> edit
            </td>
            <?php }else{?>
            <td colspan="2" style="text-align:center;"><{admin_newsletter_send_email_template_list_empty}></td>
            <?php }?>
          </tr>
            <tr class="template_form pre_init_hide">
            <td align="right" valign="top"><label for="descr">Subject: <span style="color: red;">*</span></label></td>
            <td valign="top" style="width: 420px;">
            <input type="text" value="" style="width: 400px;" name="descr" id="descr"/>
            </td>
            </tr>
            <tr class="template_form pre_init_hide">
            <td align="right" valign="top"><label for="add">Message: <span style="color: red;">*</span></label> 
            </td>
            <td valign="top" style="width: 420px;">
            <textarea style="width: 400px; height: 70px;" id="add" name="add"></textarea>
            </td>
            </tr>
            <?php 
            if(isset($constants)&&is_array($constants))
            {
                ?>
                <tr class="template_form pre_init_hide">
                <td></td>
                <td style="padding-top:10px;">
                <select id="constants" style="width:350px;">
                <?php 
                foreach($constants as $value)
                {
                    ?><option value="<?php echo output($value)?>"><?php echo output($value)?></option><?php
                }
                ?>
                </select>
                <input type="button" value="<{admin_lang_manager_btn_add}>" class="button" onClick="constantAdd()"/>
                </td>
                </tr>
                <?php 
            }
            ?>
  	    </table>
        <div>
        <table class="tab" align="center" id="newsletter_list">
        <tr class="light pre_init_hide" >
        <td style="padding:2px 8px;"></td><td style="padding:2px 8px;"></td><td style="padding:2px 8px;"></td><td style="padding:2px 8px;"></td><td style="padding:2px 8px;"></td>
        <td style="padding:2px 8px;">
        <a class="action_save pre_init_hide" style="cursor:pointer;"><img src="<?php echo base_url()?>img/ico_active.png" width="16" height="16" /></a>&nbsp;
        <a class="action_edit" style="cursor:pointer;"><img src="<?php echo base_url()?>img/ico_settings.png" width="16" height="16" /></a>&nbsp;
        <a class="action_delete" style="cursor:pointer;"><img alt="Delete" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
        </td>
        </tr>
        </table>
        </div>

<div class="after_table" style="padding-top: 10px;">
<input type="button" <?php echo !(isset($emails)&&is_array($emails)&&count($emails))?"disabled='disabled'":"";?> value="<{admin_newsletter_send_email_btn_new}>" class="button" 
                        onClick="addNewsletter(); return false;" />
<input type="button" <?php echo !(isset($emails)&&is_array($emails)&&count($emails))?"disabled='disabled'":"";?> class="button" value="<{admin_newsletter_send_email_btn_send}>" onClick="sendNewsletters();" />
<?php if(isset($temp_vars_set) && isset($temp_vars_set['member'])){?>
<input type="button" class="button" value="<{admin_newsletter_send_email_btn_return}>" onClick="document.location.href='#member_list/<?php echo $temp_vars_set['member']?>/edit';"/>
<?php }?>
</div>
</div>
<br />
