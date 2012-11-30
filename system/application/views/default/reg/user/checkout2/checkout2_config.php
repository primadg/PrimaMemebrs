<div id='main_panel_div'>
        <div id='temp_vars_set'style="display:none;">
        <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
        </div>      
        <div class="body_header">
          <div style="float: left;"><img alt="edit <?php echo $controller?>" src="<?php echo base_url()?>img/ico_settings_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_payment_system_<?php echo $controller?>_header_subject}></div>
            <div class="header_comment"><{admin_payment_system_<?php echo $controller?>_header_comment}></div>
          </div>
        </div>
        
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>
        <br/>
        <?php echo isset($comment_html)?$comment_html:""?>
        <table class="settings table_pos" align="center">
        <!--(EDIT)Form field-->
        <tr class="glav">
        <td align="right" class="table_first_td"><label for='merchant_id'><{admin_payment_system_<?php echo $controller?>_merchant_id}></label></td>
        <td> <span style="color: red;">*</span> </td>
        <td><input type="text" name="merchant_id" style="width: 300px;" value="<?php echo isset($merchant_id)?output($merchant_id):""?>"/></td>
        <td><?php echo create_tooltip_div('admin_payment_system_'.$controller.'_merchant_id_ttip')?></td><!--Used to identify you to authorize_net. -->
        </tr>
        <tr class="glav">
        <td align="right"><{admin_payment_system_<?php echo $controller?>_demo}></td> <td> </td>
        <td><input type="checkbox" name="demo" style="border: 0px;" <?php echo (isset($demo)&&$demo==1)?"checked":""?>/></td>
        <td><?php echo create_tooltip_div('admin_payment_system_'.$controller.'_demo_ttip')?><!--Select YES if you want to use authorize_net's testing server, so no actual monetary transactions are made. You need to have a developer account with authorize_net, and be logged-in in the developer panel in another browser window for the transaction to be successful.--></td>
        </tr>
        <tr>
        	<td colspan="3" align="center" style="padding-top:15px;">
            	<input type="button" class="button" value="<{admin_payment_system_<?php echo $controller?>_btn_save}>" onClick="myPanelSave('<?php echo base_url()?><?php echo $controller?>/configure/',<?php echo isset($id)?$id:""?>)" />&nbsp;
          		<input type="button" class="button" value="<{admin_payment_system_<?php echo $controller?>_btn_cancel}>" onClick="myPanelSave('<?php echo base_url()?><?php echo $controller?>/configure/','',true)" />
            </td>
        </tr>
        <!--End of form field-->
        </table>        
      </div>
