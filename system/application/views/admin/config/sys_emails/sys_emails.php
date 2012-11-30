<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_sys_emails_header_subject}></div>
            <div class="header_comment"><{admin_sys_emails_header_comment}></div>
          </div>
        </div>  
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        <div class="tema"><{admin_sys_emails_user_emails}></div>
		<table class="tab" align="center" width="700">
		<tr class="glav_big">
		<td><{admin_sys_emails_name}></td>
		<td width="70"><{admin_sys_emails_action}></td>
		</tr>
		<?php 
		if(isset($user_emails)&&is_array($user_emails)&&count($user_emails))
		{
			$flag=true;
			foreach($user_emails as $value)
			{				
				?>
				<tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
				<td class="left"><?php echo "<{admin_sys_emails_tpl_".$value['key']."}>";?></td>
				<td>
				<a style="cursor:pointer;" onclick="fieldLangsEdit(2,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_plain}>"><img id="user_emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_plain}>" src="<?php echo base_url()?>img/ico_activ_logging.png" width="16" height="16" /></a>&nbsp;
                <a style="cursor:pointer;" onclick="fieldLangsEdit(13,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_html}>"><img id="user_emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_html}>" src="<?php echo base_url()?>img/ico_html.png" width="16" height="16" /></a>
				</td>
				</tr>
				<?php
			}
		}
        else
        {
            ?>
            <tr class="dark">
            <td colspan="2"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
		?>
		</table>		
		
		<div class="tema"><{admin_sys_emails_admin_emails}></div>
		<table class="tab" align="center" width="700">
		<tr class="glav_big">
		<td><{admin_sys_emails_name}></td>
		<td width="70"><{admin_sys_emails_action}></td>
		</tr>		  
		<?php 
		if(isset($admin_emails)&&is_array($admin_emails)&&count($admin_emails))
		{
			$flag=true;
			foreach($admin_emails as $value)
			{				
				?>
				<tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
				<td class="left"><?php echo "<{admin_sys_emails_tpl_".$value['key']."}>";?></td>
				<td>
				<a style="cursor:pointer;" onclick="fieldLangsEdit(2,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_plain}>"><img id="admin_emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_plain}>" src="<?php echo base_url()?>img/ico_activ_logging.png" width="16" height="16" /></a>&nbsp;
                <a style="cursor:pointer;" onclick="fieldLangsEdit(13,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_html}>"><img id="admin_emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_html}>" src="<?php echo base_url()?>img/ico_html.png" width="16" height="16" /></a>
				</td>
				</tr>
				<?php
			}
		}
        else
        {
            ?>
            <tr class="dark">
            <td colspan="2"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
		?>
		</table>
		<br />		
      </div>
      <br />
