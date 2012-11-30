<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;">
		  <?php  if(isset($icon)){?>
		  <img alt="<?php echo isset($icon_title)?$icon_title:"";?>" src="<?php echo base_url()?>img/<?php echo $icon?>" width="32" height="32" />
		  <?php  } ?>
		  </div>
          <div class="header_pad">
            <div class="header_subject"><?php echo isset($header_subject)?$header_subject:"";?></div>
            <div class="header_comment"><?php echo isset($header_comment)?$header_comment:"";?></div>
          </div>
        </div> 
        <div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        </div>        
        <br />         
		<?php echo isset($content) ? $content : "";?>
      </div>
      <br />
