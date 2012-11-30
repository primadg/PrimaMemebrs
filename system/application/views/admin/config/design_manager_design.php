<?php
/**
 * Design Manager view
 *
 * @author Makarenko Sergey
 * @copyright 2008
 */
?>

	        <div id='main_panel_div'>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_design_manager_header_subject}></div>
            <div class="header_comment"><{admin_design_manager_header_comment}></div>
          </div>
        </div>

		<div id='temp_vars_set' style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>

        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br/>

		<table class="settings">
		<tr>
			<td valign="top">
				<table class="tab" align="center" width="350">
                    <tr class="glav_big">
                        <td colspan="2"><{admin_design_manager_frontend_unreg}></td>
                    </tr>
                <?php  $i = 0; ?>
                <?php  foreach ( $design_unreg_list as $elem ) :?>
                    <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
                        <td class="left"><?php echo $elem;?> <a href="#" onclick="window.open('<?php echo base_url()?>news/preview/<?php echo $elem;?>/unreg','popup','width=650,height=490,scrollbars=1,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">[ <{admin_design_manager_btn_preview}> ]</a></td>
                        <td>
                            <input type="radio" name="active_unreg_design" value="<?php echo ($i-1)?>" <?php echo (($i-1) == $active_unreg_design)?"checked":""?>/>
                        </td>
                    </tr>
                <?php  endforeach; ?>
				</table>
		</td>
			<td valign="top">
				<table class="tab" align="center" width="350">
                    <tr class="glav_big">
                        <td colspan="2"><{admin_design_manager_frontend}></td>
                    </tr>
				<?php  $i = 0; ?>
                <?php  foreach ( $design_reg_list as $elem ) :?>
                    <tr class="<?php echo ($i++ % 2 == 1) ? "dark" : "light";?>">
                        <td class="left"><?php echo $elem;?> <a href="#" onclick="window.open('<?php echo base_url()?>news/preview/<?php echo $elem;?>/reg','popup','width=650,height=490,scrollbars=1,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">[ <{admin_design_manager_btn_preview}> ]</a></td>
                        <td>
    						<input type="radio" name="active_reg_design" value="<?php echo ($i-1)?>" <?php echo (($i-1) == $active_reg_design)?"checked":""?>/>
    					</td>
                    </tr>
                <?php  endforeach; ?>
				</table>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<input type="submit" value="<{admin_design_manager_btn_save}>" onClick="design_manager_save('<?php echo site_url('config/design_manager_save')?>');" class="button" />
		</td>
	</tr>
	</table>
    </div>
    <br />
    <hr style="margin-right:10px;" />
    
