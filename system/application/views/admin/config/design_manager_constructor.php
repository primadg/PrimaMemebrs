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
            <div class="header_subject"><{admin_design_manager_constructor_header_subject}></div>
            <div class="header_comment"><{admin_design_manager_constructor_header_comment}></div>
          </div>
        </div>

		<div id='temp_vars_set' style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>

        		<table class="tab" align="center" width="700">
                    <tr class="glav_big">
                        <td colspan="2"><{admin_design_manager_constructor_table}></td>
                    </tr>
                    <?php  
                    $flag=true;
                    foreach(_config_get("member_pages") as $page=>$val){ 
                    ?>
                    <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                        <td class="left">
                           	<{admin_design_manager_constructor_<?php echo $page?>}>  <?php echo create_tooltip_div("<{admin_design_manager_constructor_tooltip_".$page."}>", true)?>
                        </td>
                        <td>
                        	<a href="javascript:void(0)" onclick="load_panel(base_url+'config/member_pages/<?php echo $page?>',{},{1:base_url+'js/admin/init.js'})" title="<{admin_design_manager_constructor_tooltip_<?php echo $page?>_button}>">
                            	<img alt="<{admin_design_manager_constructor_tooltip_<?php echo $page?>_button}>" src="<?php echo base_url()?>img/ico_settings.png" width="16" height="16" />
                            </a>
                        </td>
                    </tr>                    
                    <?php  } ?>                    
				</table>
    </div>
    <br />
    <hr style="margin-right:10px;" />
    
