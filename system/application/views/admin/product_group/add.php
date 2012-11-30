    <div class="body_header">
        <div style="float: left;">
            <img alt="" src="<?php echo base_url()?>img/ico_add_groups_big.png" width="32" height="32" />
        </div>
        <div class="header_pad">
            <div class="header_subject"><{product_group_add_page_title}></div>
            <div class="header_comment"><{product_group_add_page_descr}></div>
        </div>
    </div>
         
        <div class="mess_err" style="display: none; width: 500px; margin: 0 auto;" id="form_errors">

            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>

            
            <div class="box_err" style="display: none;"><{group_add_error_name}><br /></div>
            <div class="box_err" style="display: none;"><{group_add_error_descr}><br /></div>            
            
            
            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>
        </div>    
     
         
<table class="settings" align="center" style="margin-top: 10px;">
    <tr class="glav">
        <td align="right"><{product_group_add_page_group_name}> <span style="color: red;">*</span></td>
        <td><input id="g_name" type="text" style="width: 400px;" /></td>
    </tr>
    <tr class="glav">
        <td valign="top" align="right" style="padding-top: 5px;"><{product_group_add_page_group_descr}> <span style="color: red;">*</span></td>
        <td><textarea id="g_descr" style="width: 400px; height: 150px;"></textarea></td>
    </tr>    
</table>
    
<div class="after_table" style="padding-top: 10px;">
    <input type="button" class="button" value="<{product_group_add_page_add_button}>" onClick="click_add_group();" />
        &nbsp;
    <input type="button" class="button" value="<{product_group_add_page_cancel_button}>" onClick="load_group_list();" />
</div>
<br />
