<div id='main_panel_div'>
		<div id='temp_vars_set'style="display:none;">
			<?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
		</div>
        
        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico32_members.png" width="29" height="32" alt="<{admin_member_control_member_list_label}>"></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_member_control_member_list_label}></div>
            <div class="header_comment"><{admin_member_control_member_list_label_desc}></div>
          </div>
        </div>

		<div  id="unsuspend_question" style="display: none"><{admin_msg_unsuspend_question}></div>

        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        <div  id="confirm_question" style="display: none"><{admin_msg_confirm_question}></div>
        <div  id="suspend_question" style="display: none"><{admin_msg_suspend_question}></div>
        
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
        <?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        
        
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <div id="jsvalid_error_mbr_notchecked" class="box_err" style="display: none"><{admin_member_control_error_mbr_notchecked}></div>
          <div id="error_value" class="box_err" style="display: none"></div>
        </div>
        
        <br/> 
        <table class="tab" align="center" style="width:700px">
        	<tr>
            	<td>        
        			<fieldset>
                    	<legend class="handpointer" onclick="filterResize(this)">
                        	<{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span>
                        </legend>
						<table class='filter'>
        					<tr>
        						<td style="width:60px; text-align:left;">
                                	<{admin_member_control_member_list_search_label_search_by}>
                                </td>
                                <td>
                                	<select id="search_by_status" style="width: 100px;">
                                        <option value="status_all" <?php if(isset($search_status) && $search_status == 'status_all'){echo " selected ";}?> >
                                            <{admin_memberlist_status_all}>
                                        </option>
                                        <option value="status_active" <?php if(isset($search_status) && $search_status == 'status_active'){echo " selected ";}?> >
                                        	<{admin_memberlist_status_active}>
                                        </option>
                                        <option value="status_suspend" <?php if(isset($search_status) && $search_status == 'status_suspend'){echo " selected ";}?>>
                                            <{admin_memberlist_status_suspend}>
                                        </option>
                                        <option value="status_approve" <?php if(isset($search_status) && $search_status == 'status_approve'){echo " selected ";}?> >
                                            <{admin_memberlist_status_disapprove}>
                                        </option>
                                        <option value="status_activate" <?php if(isset($search_status) && $search_status == 'status_activate'){echo " selected ";}?> >
                                            <{admin_memberlist_status_inactivate}>
                                        </option>
                                        <option value="status_expired" <?php if(isset($search_status) && $search_status == 'status_expired'){echo " selected ";}?> >
                                            <{admin_memberlist_status_expired}>
                                        </option>
                                        <option value="status_inactive" <?php if(isset($search_status) && $search_status == 'status_inactive'){echo " selected ";}?> >
                                            <{admin_memberlist_status_inactive}>
                                        </option>
                                  	</select>
                                </td>
                                <td>
                                	<select id="search_by_key" style="width: 100px;" onChange="serchByKeyChange(this);">
                                        <option value="login">
                                        	<{admin_member_control_member_list_search_by_select_option_login}>
                                        </option>
                                        <option value="first_name" <?php if(isset($search_key) && $search_key == 'first_name'){echo " selected ";}?> >
                                            <{admin_member_control_member_list_search_by_select_option_first_name}>
                                        </option>
                                        <option value="last_name" <?php if(isset($search_key) && $search_key == 'last_name'){echo " selected ";}?>>
                                            <{admin_member_control_member_list_search_by_select_option_last_name}>
                                        </option>
                                        <option value="email" <?php if(isset($search_key) && $search_key == 'email'){echo " selected ";}?> >
                                            <{admin_member_control_member_list_search_by_select_option_email}>
                                        </option>
                                        <option value="group" <?php if(isset($search_key) && $search_key == 'group'){echo " selected ";}?> >
                                            <{admin_member_control_member_list_search_by_select_option_group}>
                                        </option>
                                  	</select>
                                </td>
                                <td>
                                	<input id="search_by_val" type="text" style="width: 300px; height:14px; margin:0px; <?php echo (isset($search_key) && $search_key == 'group') ? "display:none;" : "";?>" value="<?php if(isset($search_val)){echo $search_val;}?>" maxlength="250" />
            						<select style="width: 300px;<?php echo (isset($search_key) && $search_key == 'group') ? "" : "display:none;";?>" id="member_group" onChange="memberGroupChange(this);">
										<?php                
                                            $group_id=(isset($search_key) && $search_key == 'group') ? $search_val : 1;
                                            if($groups && @count($groups))
                                            foreach($groups as $group)
                                            {
	                                        ?>                                
                                                <option <?php echo ($group_id==$group['id'])?"selected='true' ":""?> value="<?php echo (int)$group['id'];?>">
                                                    <?php echo output(word_wrap($group['name'], 100, 2));?>
                                                </option>
                                        	<?php
                                            }
                                        ?>                    
                                      </select>
                                </td>
                          	</tr>
                     	</table>
                        <table class='filter'>
                            <tr>
                            	<td style="width:60px; text-align:left;">
                                	<{admin_member_control_member_list_search_label_date}>
                                </td>
                                <td>
                                	<input type="text" size="10" name="date_from" id="date_from" class="<?php echo datepicker_class()?>" value="<?php echo isset($date_from) ? $date_from : "";?>" onChange="set_date_fromto();">
                                </td>
                                <td>
                                	&nbsp; &mdash; &nbsp;
                                </td>
                                <td>
                                	<input type="text" size="10" name="date_to" id="date_to" class="<?php echo datepicker_class()?>" value="<?php echo isset($date_to) ? $date_to : "";?>">
                                </td>
                                <td>
                                	&nbsp;<{admin_member_control_member_list_search_label_or_period}>
                                </td>
                                <td>
                                	<select id="date_period" style="width: 110px;" onChange="set_date_period();">
                                        <option value="all_time">
                                        	<{admin_member_control_member_list_or_period_select_option_all_time}>
                                        </option>
                                        <option value="today" <?php if(isset($date_period) && $date_period == 'today'){echo " selected ";} ?> >
                                        	<{admin_member_control_member_list_or_period_select_option_today}>
                                        </option>
                                        <option value="this_week" <?php if(isset($date_period) && $date_period == 'this_week'){echo " selected ";} ?> >
                                        	<{admin_member_control_member_list_or_period_select_option_this_week}>
                                        </option>
                                        <option value="this_month" <?php if(isset($date_period) && $date_period == 'this_month'){echo " selected ";} ?> >
                                        	<{admin_member_control_member_list_or_period_select_option_this_month}>
                                        </option>
                                        <option value="this_year" <?php if(isset($date_period) && $date_period == 'this_year'){echo " selected ";} ?> >
                                        	<{admin_member_control_member_list_or_period_select_option_this_year}>
                                        </option>
                                        <option value="yesterday" <?php if(isset($date_period) && $date_period == 'yesterday'){echo " selected ";} ?>>
                                        	<{admin_member_control_member_list_or_period_select_option_yesterday}>
                                        </option>
                                        <option value="prev_week" <?php if(isset($date_period) && $date_period == 'prev_week'){echo " selected ";} ?> >
                                        	<{admin_member_control_member_list_or_period_select_option_previous_week}>
                                        </option>
                                        <option value="prev_month" <?php if(isset($date_period) && $date_period == 'prev_month'){echo " selected ";} ?>>
                                        	<{admin_member_control_member_list_or_period_select_option_previous_month}>
                                        </option>
                                        <option value="prev_year" <?php if(isset($date_period) && $date_period == 'prev_year'){echo " selected ";} ?>>
                                            <{admin_member_control_member_list_or_period_select_option_previous_year}>
                                        </option>
                                    </select>
                                </td>
                                <td>
                                	<input type="button" class="button" value="<{admin_member_control_member_list_button_search}>" align="middle"onClick = "load_member_list({is_search: true, letter: null});return false;"/>
                                </td>
                           	</tr>
                      	</table>
                    </fieldset>
           		</td>
         	</tr>
     	</table>

        <table align="center">
                <tr>
                    <td>
                    <a  href="#"
                        onClick = "load_member_list({is_search: false, letter: null});return false;"><{admin_member_control_member_list_label_all}></a>&nbsp;
                <?php 
                    $letters = array('A','B','C','D','E','F','G','I','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                    foreach($letters as $character)
                    {
                ?>
                    <a  href="#"
                        onClick = "load_member_list({is_search: true, letter: '<?php echo $character;?>'});return false;"><?php echo $character;?></a>
                <?php
                    }
                ?>
                &nbsp;<a  href="#"
                        onClick = "load_member_list({is_search: false});return false;"><{admin_member_control_member_list_label_all}></a>
                    </td>
                </tr>
            </table>

        
        <div class="page">
            <?php echo $pager_node1;?>
        </div>
        
        

        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="20">
            <input id="setall" type="checkbox" onClick="set_checked_all('mbr_id-');" />
            </td>
            <td><a  href="#" 
                    onClick = "load_member_list({ord: 'by_login'});return false;"><{admin_member_control_member_list_table_login}></a>
                    <?php echo ($sort_by=='by_login'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
            <td><a  href="#" 
                    onClick = "load_member_list({ord: 'by_email'});return false;"><{admin_member_control_member_list_table_email}></a>
                    <?php echo ($sort_by=='by_email') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
            <td><a  href="#" 
                    onClick = "load_member_list({ord: 'by_regdate'});return false;"><{admin_member_control_member_list_table_reg_date}></a>
                    <?php echo ($sort_by=='by_regdate') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
                    </td>
            <td><{admin_member_control_member_list_table_subscriptions}></td>
            <!--<td><{admin_member_control_account_panel_member_info_checkbox_label_approved}></td>
            <td><{admin_member_control_account_panel_member_info_checkbox_label_confirmed}></td>-->
            <td width="85"><{admin_member_control_member_list_table_status}> <?php echo create_tooltip_div('<{admin_member_control_member_list_table_status_tooltip}>', true)?></td>
            <td width="80"><{admin_member_control_member_list_table_action}></td>
          </tr>
            
            <?php echo $rows; ?>
          
        </table>
        
        

        <div class="page">
            <?php echo $pager_node2;?>
        </div>
        
		<div class="add" style="float:left; text-align:left; padding-left:22px; width:500px; vertical-align:top;" id="operation_form">
       		<div style="float:left; margin-top:6px;">
            <select id="do_by_status" style="width: 120px;" onChange="operChange(<?php echo $is_activate;?>, <?php echo $is_approve;?>);">
               	<?php  if ($operation['<{admin_memberlist_status_delete}>']!=0) { ?>
                <option value="do_status_deleted" <?php if(isset($do_status) && $do_status == 'status_deleted'){echo " selected ";}?> >
                 	<{admin_memberlist_status_delete}>
                </option>
                <?php  } ?>
				<?php  if ($operation['<{admin_memberlist_status_approve}>']!=0) { ?>
                <option value="do_status_approve" <?php if(isset($do_status) && $do_status == 'status_approve'){echo " selected ";}?> >
                	<{admin_memberlist_status_approve}>
              	</option>
                <?php  } ?>
                <?php  if ($operation['<{admin_memberlist_status_disapprove}>']!=0) { ?>
                <option value="do_status_notapprove" <?php if(isset($do_status) && $do_status == 'status_notapprove'){echo " selected ";}?> >
                	<{admin_memberlist_status_disapprove}>
              	</option>
                <?php  } ?>
                <?php  if ($operation['<{admin_memberlist_status_unsuspend}>']!=0) { ?>
                <option value="do_status_unsuspend" <?php if(isset($do_status) && $do_status == 'status_suspend'){echo " selected ";}?> >
                	<{admin_memberlist_status_unsuspend}>
               	</option>
                <?php  } ?>
                <?php  if ($operation['<{admin_memberlist_status_suspend}>']!=0) { ?>
               	<option value="do_status_suspend" <?php if(isset($do_status) && $do_status == 'status_unsuspend'){echo " selected ";}?>>
               		<{admin_memberlist_status_suspend}>
               	</option>
                <?php  } ?>
                <?php  if ($operation['<{admin_memberlist_status_inactivate}>']!=0) { ?>
                <option value="do_status_notactivate" <?php if(isset($do_status) && $do_status == 'status_notactivate'){echo " selected ";}?> >
                	<{admin_memberlist_status_inactivate}>
              	</option>
                <?php  } ?>
                <?php  if ($operation['<{admin_memberlist_status_activate}>']!=0) { ?>
                <option value="do_status_activate" <?php if(isset($do_status) && $do_status == 'status_activate'){echo " selected ";}?> >
                 	<{admin_memberlist_status_activate}>
                </option>
                <?php  } ?>
          	</select>
            </div>
            <input type="button" class="button_super_big" value="<{admin_memberlist_oper_button}>" onClick="load_member_list({is_search: true, letter: null, is_oper: true });return false;" />
            <?php echo create_tooltip_div('<{tool_tip_delete}>', true)?>
              <span id="tool_tip_activate" style="display: none"><{tool_tip_activate}></span>
              <span id="tool_tip_inactivate" style="display: none"><{tool_tip_inactivate}></span>
              <span id="tool_tip_suspend" style="display: none"><{tool_tip_suspend}></span>
              <span id="tool_tip_unsuspend" style="display: none"><{tool_tip_unsuspend}></span>
              <span id="tool_tip_approve" style="display: none"><{tool_tip_approve}></span>
              <span id="tool_tip_disapprove" style="display: none"><{tool_tip_disapprove}></span>
              <span id="tool_tip_system_is_activate" style="display: none"><{tool_tip_system_is_activate}></span>
              <span id="tool_tip_system_is_inactivate" style="display: none"><{tool_tip_system_is_inactivate}></span>
              <span id="tool_tip_system_is_approval" style="display: none"><{tool_tip_system_is_approval}></span>
              <span id="tool_tip_system_is_disapproval" style="display: none"><{tool_tip_system_is_disapproval}></span>
              <span id="tool_tip_delete" style="display: none"><{tool_tip_delete}></span>
        </div>
        <div class="add" style="float:right; width:100px; padding-right:25px;"><input type="button" class="button_super_big" value="<{admin_member_control_member_list_button_add_member}>" onClick="clickMenu(2,1);" />        </div>
        
        <br/>
        
        <div style="display:none; float:left; margin-left:21px; margin-top:6px;" id="status_reason">
            <select id="sreason_id" style="width: 225px;">
                <option value="0" selected>  
                    <{admin_member_control_approve_suspend_suspend_reason_select_option_no_reason}>
                </option>
                <?php 
                    foreach($susp_reason['items'] as $reason)
                    {
                    ?>    
                    <option value="<?php echo $reason['id']; ?>">
                        <?php echo $reason['name']; ?>
                    </option>
                    <?php
                    }
                ?>
			</select>
            <a href="#suspend_reasons" title="<{admin_member_control_img_tooltip_edit}>" onClick="suspend_reasons_list();" style="vertical-align:middle;">
                <img alt="<{admin_member_control_img_tooltip_edit}>" src="<?php echo base_url();?>img/ico_settings.png" width="16" height="16" />
            </a>
        </div>
        


<br />        

<br />  <br />  <br />
<br />  <br />  <br />  
<br />  <br />  <br />  
</div>
