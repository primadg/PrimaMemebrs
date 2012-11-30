<div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
        <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
        </div>
        <div class="body_header">
          <div style="float: left;"><img alt="administrator control" src="<?php echo base_url()?>img/<?php echo isset($is_edit)&&$is_edit?"page_edit32.png":"ico_level_add_big.png"?>" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject">
            <?php echo isset($is_edit)&&$is_edit?($is_super?"<{admin_admin_edit_header_subject_edit_super}>":"<{admin_admin_edit_header_subject_edit}>"):"<{admin_admin_edit_header_subject_add}>";?>
            </div>
            <div class="header_comment">
            <?php echo isset($is_edit)&&$is_edit?($is_super?"<{admin_admin_edit_header_comment_edit_super}>":"<{admin_admin_edit_header_comment_edit}>"):"<{admin_admin_edit_header_comment_add}>";?>            
            </div>
          </div>
        </div>                
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>
        
        <table align="center" class="settings table_pos" style="margin-top: 10px;" >
        <?php 
        if(isset($is_edit)&&$is_edit)
        {
            ?>
            
            <tr class="glav table_first_td">
            <td align="right"><{admin_admin_edit_id}></td>
            <td> </td>
            <td align="left"><?php echo isset($id)?output($id):""?></td>
            </tr>
            <?php 
        }
        ?>          
          <tr class="glav">
            <td align="right"><label for='login'><{admin_admin_edit_username}></label></td><td> <span style="color: red;">*</span> </td>
            <td><input type="text" name="login" style="width: 350px;" value="<?php echo isset($login)?output($login):""?>"/></td>
          </tr>
          <tr class="glav">
            <td align="right"><label for='email'><{admin_admin_edit_email}></label></td><td> <span style="color: red;">*</span> </td>
            <td><input type="text" name="email" style="width: 350px;" value="<?php echo isset($email)?output($email):""?>"/></td>
          </tr>
          <tr>
            <td align="right" class="table_first_td"><label for='pwd_gen'><{admin_admin_edit_generate_new_password}></label></td><td> </td>
            <td><input type="checkbox" name="pwd_gen" <?php echo (isset($pwd_gen)&&$pwd_gen=="true")?"checked":""?>/>
            </td>
          </tr>
          <tr class='pwd_block' <?php echo (isset($pwd_gen)&&$pwd_gen=="true")?"style='display:none;'":""?> >
            <td align="right"><label for='pwd'><{admin_admin_edit_password}></label></td><td> <span style="color: red;">*</span> </td>
            <td><input type="password" name="pwd" style="width: 350px;" value="<?php echo isset($pwd)?output($pwd):""?>"/>
            </td>
          </tr>
          <tr style="display: none;" class='pwd_ret_block' <?php echo (isset($pwd_gen)&&$pwd_gen=="true")?"style='display:none;'":""?> >
            <td align="right"><label for='pwd_ret'><{admin_admin_edit_retype_password}></label></td><td> <span style="color: red;">*</span> </td>
            <td><input type="password" name="pwd_ret" style="width: 350px;" value="<?php echo isset($pwd_ret)?output($pwd_ret):""?>"/></td>
          </tr>          
        <?php 
        if(isset($is_super)&&!$is_super&&$is_perm)
        {
            ?>
            <tr class="glav">
            <td align="right"><label for='level'><{admin_admin_edit_access_level}></label></td><td><span style="color: red;">*</span> </td>
            <td>
            <select name="access_id" style="width:360px;">
            <?php 
            reset($levels);
            foreach($levels as $level)
            {
                ?>
                <option value="<?php echo output($level['id'])?>" <?php echo ($level['id']==$access_id)?"selected":""?>><?php echo output($level['name'])?></option>
                <?php
            }
            ?>
            </select>
            </td>
            </tr>
            <?php
        }
        ?>          
        	<tr>
            	<td colspan="3" align="center" style="padding-top:15px;">
                	<input type="button" class="button" value="<?php echo isset($is_edit)&&$is_edit?"<{admin_admin_edit_btn_save}>":"<{admin_admin_edit_btn_add}>"?>" onClick="myOnSave(<?php echo isset($id)?$id:""?>)"/>&nbsp;
          			<input type="button" class="button" value="<{admin_admin_edit_btn_cancel}>" onClick="myOnSave('',true)"/>
                </td>
            </tr>
        </table>
      </div>
      <br />
