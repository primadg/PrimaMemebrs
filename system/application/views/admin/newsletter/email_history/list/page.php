<!-- Newsletter Email History List - MAIN PAGE -->



        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_email_history_page_title}></div>
            <div class="header_comment"><{admin_newsletter_email_history_page_desc}></div>
          </div>
        </div>
        
        <div id="msg_panel" class="mess" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
          <div class="box" id="msg_value"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
        </br>
        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>        
        
        <div class="filter">
          <div style="padding-left: 40px;">
            <div style="padding-top: 6px;"><{admin_newsletter_email_history_label_date}></div>
            <div>
              <input type="text" size="10" name="date_from" id="date_from" class="format-d-m-y highlight-days-67 divider-dash no-transparency" 
              value="<?php if(isset($date_from)){echo $date_from;}?>"
              onChange="set_date_fromto();">&nbsp; &mdash; &nbsp;
              <input type="text" size="10" name="date_to" id="date_to" class="format-d-m-y highlight-days-67 divider-dash no-transparency" value="<?php if(isset($date_to)){echo $date_to;}?>"> <{admin_newsletter_email_history_label_period}>
              <select id="date_period" style="width: 110px;"
                onChange="set_date_period();">
      					<option value="all_time"><{admin_member_control_member_list_or_period_select_option_all_time}></option>
      					<option value="today"
                        <?php if(isset($date_period) && $date_period == 'today'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_today}></option>
      					<option value="this_week"
                        <?php if(isset($date_period) && $date_period == 'this_week'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_this_week}></option>
      					<option value="this_month"
                        <?php if(isset($date_period) && $date_period == 'this_month'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_this_month}></option>
      					<option value="this_year"
                        <?php if(isset($date_period) && $date_period == 'this_year'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_this_year}></option>
      					<option value="yesterday"
                        <?php if(isset($date_period) && $date_period == 'yesterday'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_yesterday}></option>
      					<option value="prev_week"
                        <?php if(isset($date_period) && $date_period == 'prev_week'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_previous_week}></option>
      					<option value="prev_month"
                        <?php if(isset($date_period) && $date_period == 'prev_month'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_previous_month}></option>
      					<option value="prev_year"
                        <?php if(isset($date_period) && $date_period == 'prev_year'){echo " selected ";} ?>
                        ><{admin_member_control_member_list_or_period_select_option_previous_year}></option>              
      				</select>	
            </div>
            <div style="padding-top: 2px; padding-left: 10px;"><input type="button" class="button" value="<{admin_btn_show}>" align="middle" 
            onClick = "email_history_list({h_is_search: true});return false;" /></div>
          </div>
        </div>
<br />
        <div class="page">
            <?php echo $pager_node1;?>
        </div>
        
        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td width="20">#</td>
            <td >            
            <a href="#" onClick = "email_history_list({ord: 'by_subject'});return false;">
            <{admin_newsletter_email_history_table_header_subject}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_subject',true);?>            
            </td>
            <td>
            <a href="#" onClick = "email_history_list({ord: 'by_email_from'});return false;">
            <{admin_newsletter_email_history_table_header_from}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_email_from');?>            
            </td>
            <td>
            <a href="#" onClick = "email_history_list({ord: 'by_email_to'});return false;">
            <{admin_newsletter_email_history_table_header_to}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_email_to');?>            
            </td>
            <td width="80">
            <a href="#" onClick = "email_history_list({ord: 'by_date'});return false;">
            <{admin_newsletter_email_history_table_header_date}>
            </a>
            <?php echo draw_arrow($sort_by, $order_by, 'by_date');?>            
            </td>
            <td width="45">
            <{admin_newsletter_email_history_table_header_action}>
            </td>
          </tr>
          
          <?php echo $rows;?>
          
        </table>

        <div class="page">
            <?php echo $pager_node2;?>
	    </div>

        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        
<br />        
