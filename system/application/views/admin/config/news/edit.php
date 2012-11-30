
        <div class="body_header">
          <div style="float: left;"><img alt="<{admin_config_manage_news_add_label}>" src="<?php echo base_url();?>img/ico_settings_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_manage_news_edit_label}></div>
            <div class="header_comment"><{admin_config_manage_news_edit_label_desc}></div>
          </div>
        </div>
        
        
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_fields_empty" class="box_err" style="display: none"><{admin_config_manage_news_error_empty_fields}></div>
            <div  id="jsvalid_error_date_wrong" class="box_err" style="display: none"><{admin_config_manage_news_error_field_date_wrong}></div>
            <div  id="jsvalid_error_header_toolong" class="box_err" style="display: none"><{admin_config_manage_news_error_field_header_toolong}></div>
            <div  id="jsvalid_error_brief_toolong" class="box_err" style="display: none"><{admin_config_manage_news_error_field_brief_toolong}></div>
            <div  id="jsvalid_error_content_toolong" class="box_err" style="display: none"><{admin_config_manage_news_error_field_content_toolong}></div>
            <div  id="jsvalid_error_members_only_wrong" class="box_err" style="display: none"><{admin_config_manage_news_error_field_members_only_wrong}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>

        <table class="settings" align="center">
    			<tr class="glav">
    				<td align="right"><{admin_config_manage_news_edit_fields_date}>: <span style="color: red;">*</span></td>
    				<td><input type="text" size="10" name="date_from" id="date1Entry" class="format-d-m-y highlight-days-67 divider-dash no-transparency" 
                    value="<?php if(isset($date)){echo $date;}?>" /></td>
    			</tr>
	        <tr class="glav">
    				<td align="right"><{admin_config_manage_news_add_fields_header}>: <span style="color: red;">*</span></td>
    				<td><input id="news_header" type="text" 
                    style="width: 400px;"  maxlength="50"
                    value="<?php if(isset($header)){echo $header;}?>"/></td>
    			</tr>
	        <tr class="glav">
    				<td align="right" valign="top"><{admin_config_manage_news_edit_fields_brief}>: <span style="color: red;">*</span></td>
    				<td><textarea  id="news_brief" style="width: 400px; height: 70px;" class="noresize"><?php if(isset($brief)){echo $brief;}?></textarea></td>
    			</tr>
    			<tr class="glav">
    				<td align="right" valign="top" style="padding-top: 5px;"><{admin_config_manage_news_edit_fields_content}>: <span style="color: red;">*</span></td>
    				<td>
                 <div class="resizable-textarea">   
                    <textarea  id="news_content" style="width: 400px; height: 70px;"><?php if(isset($content)){echo $content;}?></textarea>
              </td>
    			</tr>
    			<tr class="glav">
    				<td><{admin_config_manage_news_edit_fields_for_members}>:</td>
    				<td><input id="members_only" type="checkbox" 
                    <?php if( $members_only == '1' ){ echo ' checked ';}?>></td>
    			</tr>
  			</table>
        
        <input type="hidden" id="news_id" value="<?php echo $id;?>"> 
        
        <div class="after_table" style="padding-top: 10px;">
          <input type="button" class="button" value="<{admin_config_manage_news_edit_button_save}>" 
          onClick="edit_news(); return false;" />&nbsp;
          <input type="button" class="button" value="<{admin_config_manage_news_edit_button_cancel}>" onClick="load_news_list(); return false;" />
        </div>
        <br />

        
