      <div class="body_header">
      <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{product_list_title}></div>
            <div class="header_comment"><{product_list_descr}></div>
          </div>
        </div>
        
        <div class="mess" style="width: 500px; margin: 0 auto; display: none;" id="form_msgs">
            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>
            <div style="display: none;" class="box"><{product_add_success}></div>            
            <div style="display: none;" class="box"><{product_delete_success}></div>
            <div style="display: none;" class="box"><{product_save_success}></div>  
            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>
        </div>
        
        <div class="mess_err" style="display: none; width: 500px; margin: 0 auto;" id="form_errors">

            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>

            <div class="box_err" style="display: none;"><{product_lock_error}><br /></div>  
            <div class="box_err" style="display: none;"><{product_delete_error}><br /></div>
            <div class="box_err" style="display: none;"><{demo_msg_er_functionality_disabled}><br /></div>

            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>

        </div>
        
        <div id="special_product" style="display: none;"><{product_list_edit_not_special}></div>
        <div id="unspecial_product" style="display: none;"><{product_list_edit_special}></div>
        
        <table class="tab" align="center" style="width:700px"><tr><td>        
        <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
        <table class='filter'>
        <tr>
        <td><{product_list_filter_member_group}></td>
        <td>
        <select style="width: 180px;" id="member_group_selector">
        <option style="background-color: rgb(223, 253, 255);" value="0"><{product_list_filter_all}></option>
        <option <?php echo (intval($member_group_id)==-1)?"selected='true' ":""?> style="background-color: rgb(223, 253, 255);" value="-1"><{product_list_filter_unavailable}></option>
        <?php        
        if($member_groups && @count($member_groups))
        foreach($member_groups as $member_group)
        {
            ?>                                
            <option <?php echo ($member_group_id==$member_group['id'])?"selected='true' ":""?> value="<?php echo (int)$member_group['id'];?>">
            <?php echo output(word_wrap($member_group['name'], 100, 2));?>
            </option>
            <?php
        }
        ?>        
        </select>
        </td>
        <td><{product_list_filter_member_group_available}></td>
        <td>
        <input id="member_groups_available" type="checkbox" <?php echo isset($member_groups_available) && intval($member_groups_available)>0 ? "checked" : "";?> style="border: 0px none ;" name="member_groups_available"/>
        </td>
        <td><{product_list_filter}></td>
        <td>
        <select style="width: 180px;" id="product_groups">
        <option style="background-color: rgb(223, 253, 255);" value="0"><{product_list_filter_all}></option>
        <?php
        
        if($groups && @count($groups))
        foreach($groups as $group)
        {
            ?>                                
            <option <?php echo ($group_id==$group['id'])?"selected='true' ":""?> value="<?php echo (int)$group['id'];?>">
            <?php echo output(word_wrap($group['name'], 100, 2));?>
            </option>
            <?php
        }
        ?>
        
        </select>
        </td>
        <td>
        <input onclick="product_list_group_search();" type="button" class="button" value="<{product_list_filter_button}>" align="middle" />
        </td>
        </tr>
        </table>
        </fieldset>
        </td></tr></table>
       
        <div class="page">
            <?php echo $per_pager?>            
            
            <?php echo $pager?>
        </div>
         
        <table class="tab" align="center" width="700">
              <tr class="glav_big">
                <td width="20">ID</td>
                <td>
                    <a id="plist_tab_name" onclick="tab_sort_click('name'); return false;" href="#"><{product_list_table_name}></a>
                    <?php echo $sort_by=='name' || $sort_by=="" ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
                <td width="80">
                    <a id="plist_tab_users_in" onclick="tab_sort_click('users_in'); return false;" href="#"><{product_list_table_users_in}></a>
                    <?php echo $sort_by=='users_in' ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
                <td width="150">
                    <a id="plist_tab_group" onclick="tab_sort_click('group_name'); return false;" href="#"><{product_list_table_product_group}></a>
                    <?php echo $sort_by=='group_name' ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
                <td>
                    <a id="plist_tab_subscr_type" onclick="tab_sort_click('subscr_type'); return false;" href="#"><{product_list_table_type}></a>
                    <?php echo $sort_by=='subscr_type' ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
                <td width="80px"><{product_list_table_action}></td>
                <td width="60"><{product_list_table_block}></td>
              </tr>
              
              <?php echo $products?>
          
        </table>
        

        <div class="add">
            <input type="button" class="button_big" value="<{product_list_table_add_button}>" onClick="add_product_click();" />
        </div>
       
        <div id="product_delete_ask" style="display: none;">
            <{product_delete_ask_confirm}>
        </div>
        <br />
