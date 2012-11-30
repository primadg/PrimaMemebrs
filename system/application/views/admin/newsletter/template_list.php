<div id='main_panel_div'>
       <div id='temp_vars_set'style="display:none;">
		<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_tmpl_header_subject}></div>
            <div class="header_comment"><{admin_newsletter_tmpl_header_comment}></div>
          </div>
        </div>  
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
		<?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        <br />
        <div class="page">
        <?php  echo $pagers['pager'][0]; ?>
        </div> 
           
        <table class="tab" align="center" width="700">
		<tr class="glav_big">
		<td><a href="#" id="name"><{admin_newsletter_tmpl_name}></a>
        </td>
		<td width="70"><{admin_newsletter_tmpl_action}></td>
		</tr>
		<?php 
        if(isset($emails)&&is_array($emails)&&count($emails))
        {
            $flag=true;
            foreach($emails as $value)
            {				
                ?>
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td class="left"><?php echo isset($value['name'])?output($value['name']):""?></td>
                <td>
                <a style="cursor:pointer;" onclick="fieldLangsEdit(8,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_plain}>"><img id="emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_plain}>" src="<?php echo base_url()?>img/ico_activ_logging.png" width="16" height="16" /></a>&nbsp;
                <a style="cursor:pointer;" onclick="fieldLangsEdit(14,'<?php echo $value['id']?>');" class="email_edit_link" title="<{admin_newsletter_tmpl_list_html}>"><img id="emails_<?php echo $value['id']?>" alt="<{admin_newsletter_tmpl_list_html}>" src="<?php echo base_url()?>img/ico_html.png" width="16" height="16" /></a>&nbsp;
                <a style="cursor:pointer;"  onclick="templateDelete(<?php echo $value['id']?>); return false;" title="<{admin_newsletter_tmpl_list_delete}>"><img alt="<{admin_newsletter_tmpl_list_delete}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
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
		
        <div class="page">
        <?php  echo $pagers['pager'][1]; ?>          
        </div>
        
        <div class="add">
        <input type="button" class="button_big" value="<{admin_newsletter_tmpl_add_button}>" onClick="fieldLangsEdit(8,'');" />
        </div>
		<br />		
      </div>
      <br />
