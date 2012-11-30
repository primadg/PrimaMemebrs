<!-- Progress bar sending process page -->
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_newsletter_send_email_step3_page_title}></div>
            <div class="header_comment"><{admin_newsletter_send_email_step3_page_desc}></div>
          </div>
        </div>
        
        </br>
        <table class="settings" align="left" style="margin-left: 10px;" >
          <tr class="glav">
            <td align="right"><{admin_newsletter_send_email_step3_label_sent_letters_number}>&nbsp;</td>
            <td><div id="letters_are_sent"></div></td>
          </tr>
          <tr class="glav">
            <td align="right"><{admin_newsletter_send_email_step3_label_total_letters}>&nbsp;</td>
            <td><?php echo $emails_count;?></td>
          </tr>          
        </table>
        </br>
        
        <div style="padding-top:200px">
          <div class="progress_block">
            <div class="start_pause">
              <a href="#"><img 
              id="pb_slider_btn" 
              src="<?php echo base_url();?>img/start.gif" width="19" height="26" border="0" style="display:block;" 
              alt="<{admin_newsletter_send_email_step3_tip_start}>" 
              title="<{admin_newsletter_send_email_step3_tip_start}>" 
              onClick="sending_start(); return false;"><img id="pb_pause_btn" 
              src="<?php echo base_url();?>img/pause.gif" width="19" height="26" border="0" alt="" style="display:none;" 
              alt="<{admin_newsletter_send_email_step3_tip_pause}>" 
              title="<{admin_newsletter_send_email_step3_tip_pause}>"  
              onClick="sending_pause(); return false;"></a>
            </div>

            <div class="block_progress_center">

              <div class="prog_procent">
                <div id="start_percent" style="">0%</div>
                <div id="current_percent" class="procent" style="font-size:17px;" ></div>
                <div id="end_percent" style="float:right;">100%</div>
              </div>

              <div id="bg_progress_id" class="bg_progress" style="">
               <div id="progressbar_line" style="width: 0%;">&nbsp;</div> 
              </div>

            </div>

            <div class="progr_cancel">
              <a href="#"
              onClick="destroy_process('Sending emails is canceled'); return false;"><img src="<?php echo base_url();?>img/cancel.gif" width="25" height="25" border="0" alt="<{admin_newsletter_send_email_step3_tip_cancel}>" title="<{admin_newsletter_send_email_step3_tip_cancel}>"></a>
            </div>

          </div>
        </div>
        
        <!-- Contains the number of all emails to send -->
        <div id="all_emails4sending" style="display:none;"><?php echo $emails_count;?></div>
        
        <!-- DISPLAY PAUSE ATTRIBUTES FOR JAVASCRIPT FUNCTIONS-->
        <div id="pause_text_color" style="color:#bcbcbc; display:none;"></div>
        <div id="pause_bg_img" style="background-image: url('<?php echo base_url();?>img/bg_progress_pause.gif'); display:none;"></div>        
        <div id="pause_progressbar_line_bg" style="background-color: #ececec;  display:none;"></div>
        <div id="pause_bg_progress_img" style="background-image: url('<?php echo base_url();?>img/bg_progress_pause.gif'); display:none;"></div>
        <!-- DISPLAY PAUSE ATTRIBUTES FOR JAVASCRIPT FUNCTIONS-->
<br />        
