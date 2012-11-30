<!-- Ban IP list main page -->
<div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><?php echo $label;?></div>
            <div class="header_comment"><?php echo $desc;?></div>
          </div>
        </div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>        

        <?php if (isset($ban_list)) echo $ban_list; else '';?>
        
        <?php if (isset($ban_add)) echo $ban_add; else '';?>
        <br/>
</div>
<br />
