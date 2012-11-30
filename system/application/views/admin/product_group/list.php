        <div class="body_header">
            <div style="float: left;">
                <img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" />
            </div>
            <div class="header_pad">
                <div class="header_subject"><{product_group_page_title}></div>
                <div class="header_comment"><{product_group_page_descr}></div>
            </div>
        </div>
        
        
        <div class="mess_err" style="display: none; width: 500px; margin: 0 auto;" id="form_errors">
            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>
            
            <div class="box_err" style="display: none;"><{group_delete_error}><br /></div>
            <div class="box_err" style="display: none;"><{demo_msg_er_functionality_disabled}><br /></div>

            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>
        </div>    
       
        <div class="mess" style="width: 500px; margin: 0 auto; display: none;" id="form_msgs">
            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>
            
            <div style="display: none;" class="box"><{group_add_success}></div>
            <div style="display: none;" class="box"><{group_delete_success}></div>
            <div style="display: none;" class="box"><{group_saved_success}></div>
           
            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>
        </div>
       
       
        <div class="page">
            <?php echo $per_pager?>            
            
            <?php echo $pager?>
        </div>
         
         
        <table class="tab" align="center" width="700">
            <tr class="glav_big">
                <td width="20"><{product_group_list_id}></td>
                <td>
                    <a id="glist_tab_name" onclick="click_group_sort('name'); return false;" href="#">
                        <{product_group_list_sort_name}>
                    </a>
                        <?php echo $sort_by=='name'||$sort_by=="" ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    
                </td>
                <td width="100">
                    <a id="glist_tab_p_cnt" onclick="click_group_sort('p_cnt'); return false;" href="#">
                        <{product_group_list_sort_product_count}>
                    </a>
                        <?php echo $sort_by=='p_cnt'||$sort_by=="" ? ($order_by=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    
                </td>
                <td width="100"><{product_group_list_action}></td>
            </tr>
          
           <?php echo $items?>
         
        </table>
        
        <div class="add">
            <input type="button" class="button_big" value="<{product_group_list_add_button}>" onClick="fieldLangsEdit(3,'');" />
        </div>
        
        
        <div style="display: none;" id="group_not_empty_mess">
            <{group_not_empty}>
        </div>

<br />        
        
