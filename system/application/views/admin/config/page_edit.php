<?php
/*********************************************************************************
*   Edited by Konstantin X @ 13.05.2008
**********************************************************************************/
?>
    <div id='temp_vars_set' style="display:none;"><?php echo isset($temp_vars_set) ? $temp_vars_set : "";?></div> 
    <div class="body_header">
        <div style="float: left;"><img alt="system configuration" src="<?php echo base_url();?>img/ico_sysconf_big.png" width="32" height="32" /></div>
        <div class="header_pad">
            <div class="header_subject"><{admin_page_edit_title}></div>
            <div class="header_comment"><{admin_page_edit_description}></div>
        </div>
    </div>
    
    <table class="member_list">
      <tr><!-- Default active - Tab1 [style="background-color: #EEF8FE;"] -->
        <td id="tab1"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',1);"><{admin_page_edit_tab1}></a></td><!--   Terms of use -->
        <td id="tab2"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',2);"><{admin_page_edit_tab2}></a></td><!--   Privacy policy -->
        <td id="tab3"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',3);"><{admin_page_edit_tab3}></a></td><!--   Success payment page -->
        <td id="tab4"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',4);"><{admin_page_edit_tab4}></a></td><!--   Cancel payment page -->
        <td id="tab5"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',5);"><{admin_page_edit_tab5}></a></td><!--   Registered sucessfully page -->
        <td id="tab6"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',6);"><{admin_page_edit_tab6}></a></td><!--   Confirmation successful page -->
        <td id="tab7"><a href="#" onClick="tab_click('<?php echo site_url('config/page_edit');?>',7);"><{admin_page_edit_tab7}></a></td><!--   Confirmation error page -->
      </tr>
    </table>
      
    <div class="member_list_block">
        <?php echo admin_print_msg_box('msg',$messages);?>
        <?php echo admin_print_msg_box('emsg',$mess_err);?>
        <br />

        <div class="tema"><{admin_page_edit_tab<?php echo $obj_id;?>}></div>
      
        <table align="center">
            <tr>
                <td align="center">
                    <div>
                        <div style="float: left; padding-top: 3px; padding-right: 5px;">Language:</div>
                        <div style="float: left;"><?php echo form_dropdown('language_id', $lang_list["options"], $lang_list["selected"], 'id="language_id" style="width: 400px;" onChange="tab_click(\''.site_url('config/page_edit').'\',\''.$obj_id.'\')"'); ?></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 15px;"><textarea id="language_txt" name="language_txt" style="width: 600px; height: 300px;"><?php echo $content;?></textarea>
            </tr>
            <tr>
                <td align="center">
                    <input type="button" value="<{admin_btn_page_edit_reset}>" onClick="pageReset('<?php echo site_url('config/page_edit')?>')" class="button_save_as_template" />
                    <input type="button" value="<{admin_btn_page_edit_save}>" onClick="pageSave('<?php echo site_url('config/page_edit')?>')" class="button" style="margin: 0 auto;" />
                </td>
            </tr>
        </table>
    </div>
    <br />
