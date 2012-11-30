<div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
			</div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/<?php echo isset($is_edit)&&$is_edit?"page_edit32.png":"ico_level_add_big.png"?>" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject">
            <?php echo isset($is_edit)&&$is_edit?"<{admin_access_level_header_subject_edit}>":"<{admin_access_level_header_subject_add}>"?>
            </div>
            <div class="header_comment">
            <?php echo isset($is_edit)&&$is_edit?"<{admin_access_level_header_comment_edit}>":"<{admin_access_level_header_comment_add}>"?>
            </div>
          </div>
        </div>
        
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"" ?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"" ?>
         
        <table class="settings table_pos" style="margin-top: 10px;" >
          <tr class="glav">
            <td align="right" style="width: 120px;" class="table_first_td"><label for='level_name'><{admin_access_level_name}><span style="color: red;"> *</span></label></td>
            <td><input type="text" name="level_name" style="width:350px" value="<?php echo isset($name)?output($name):""?>" <?php echo ($id==1) ? "disabled": ""?>/></td>
          </tr>
          <tr>
            <td align="right" valign="top" style="padding-top:10px;"><{admin_access_level_access_category}></td>
            <td style="padding-top:10px;">
              
            <?php 
            if(isset($access_category)&&is_array($access_category))
            {
                foreach($access_category as $key=>$value)
                {				
                    ?>
                    <div style="height: 20px;">
                    <div style="float: left;"><input type="checkbox" name="<?php echo $key?>" style="padding: 0px;" <?php echo (isset($value['checked'])&&$value['checked'])?"checked":"";?> <?php echo ($id==1) ? "disabled": ""?>/></div>
                    <div style="float: left;"><?php echo isset($value['name'])?$value['name']:""?></div>
                    </div>                    
                    <?php
                }
            }
            ?> 
              
            </td>
          </tr>
          <tr>
            <td align="right" valign="top" style="padding-top:10px;"><{admin_access_level_email_newsletter}></td>
            <td style="padding-top:10px;">
              
              <?php 
            if(isset($email_newsletter)&&is_array($email_newsletter))
            {
                foreach($email_newsletter as $key=>$value)
                {				
                    ?>
                    <div style="height: 20px;">
                    <div style="float: left;"><input type="checkbox" name="<?php echo $key?>" style="padding: 0px;" <?php echo (isset($value['checked'])&&$value['checked'])?"checked":""?>/></div>
                    <div style="float: left;"><?php echo isset($value['name'])?$value['name']:""?></div>
                    </div>                    
                    <?php
                }
            }
            ?> 
              
            </td>
          </tr>
          <tr>
          		<td colspan="2" align="center" style="padding-top:15px;">
                	<input type="button" class="button" value="<?php echo isset($is_edit)&&$is_edit?"<{admin_access_level_btn_save}>":"<{admin_access_level_btn_add}>"?>" onClick="myOnSave(<?php echo isset($id)?$id:""?>)"/>&nbsp;
          			<input type="button" class="button" value="<{admin_access_level_btn_cancel}>" onClick="myOnSave(<?php echo isset($id)?$id:"''"?>,true)"/>
                </td>
          </tr>
        </table>
        
        <div class="after_table table_pos" style="padding-top: 10px; padding-bottom: 20px;">
          
        </div>
        
      </div>
<br />
