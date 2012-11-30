    <div id="main_panel_div">
    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>
    <div class="body_header">
    <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_newsletter_big.png" width="32" height="32" /></div>
    <div class="header_pad">
    <div class="header_subject"><{admin_newsletter_email_history_page_title}></div>
    <div class="header_comment"><{admin_newsletter_email_history_page_desc}></div>
    </div>
    </div>
        <?php echo isset($messages)?admin_print_msg_box('msg',$messages):"";?>
        <?php echo isset($mess_err)?admin_print_msg_box('emsg',$mess_err):"";?>
        <br />
        <table  class="tab" width="700" align="center"><tr><td>
        <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
        <table class='filter'>
        <tr>
        <td>
        <{admin_newsletter_email_history_type}>
        </td>
        <td align="left" style="padding-left:10px;">
        <select style="width:100px;" name="type" id="frm_type">
        <?php foreach($type['items'] as $key=>$value):?>
        <option value="<?php echo $key;?>" <?php echo $type['selected']==$key ? "selected" : "";?>><?php echo $value;?></option>
        <?php endforeach;?>
        </select>
        </td>
        <td>
        <{admin_newsletter_email_history_tpl_type}>
        </td>
        <td align="left" style="padding-left:10px;">
        <select style="width:100px;" name="tpl_type" id="frm_tpl_type" <?php echo isset($temp_vars_set)&&isset($temp_vars_set['member']) ? "disabled='disabled'" : "";?>>
         <?php foreach($tpl_type['items'] as $key=>$value):?>
        <option value="<?php echo $key;?>" <?php echo $tpl_type['selected']==$key ? "selected" : "";?>><?php echo $value;?></option>
        <?php endforeach;?>
        </select>
        </td>
        <td>
        <{admin_newsletter_email_history_person}>
        </td>
        <td>
        <input type="text" size="16" name="person" id="frm_person" value="<?php echo $person;?>" <?php echo isset($temp_vars_set)&&isset($temp_vars_set['member']) ? "disabled='disabled'" : "";?>/>
        </td>
        </tr>
        <tr height="50px">
        <td>
            <{admin_logging_date}>:
        </td>
        <td valign="top" colspan=3>
            <div class="filter" style="width:280px; padding-left:10px;">
                <div style="float:left; width:140px;"><input type="text" size="10" name="date_from" id="date_from" class="<?php echo datepicker_class();?>" value="<?php echo $date_from;?>" /><label for='date_from'></label>&nbsp; &mdash; &nbsp;</div>
                <div style="float:left; width:130px;"><input type="text" size="10" name="date_to" id="date_to" class="<?php echo datepicker_class();?>" value="<?php echo $date_to;?>" /><label for='date_to'></label>&nbsp;</div>
            </div>
        </td>
        <td>
        </td>
        <td>
            <input type="button" class="button" value="<{admin_logging_btn_show}>" onclick="myPagerHandler(); return false;" align="middle" />&nbsp;|&nbsp;<input type="button" class="button" value="<{admin_logging_btn_clear}>" onclick="filterValidator.reset(); return false;" />
        </td>
        </tr>
        </table>
        </fieldset>
        </td></tr></table>
        <br clear='all'/>
        <div class="page">
            <?php  echo $pagers['pager'][0]; ?>
        </div>
        <table class="tab" align="center" width="95%">
          <tr class="glav_big">
            <td width="10%"><a href="#" id="email_tpl_id"><{admin_newsletter_email_history_email_tpl_id}></a></td>
            <td width="20%"><a href="#" id="date"><{admin_newsletter_email_history_date}></a></td>
            <td width="20%"><a href="#" id="user_id"><{admin_newsletter_email_history_user_login}></a></td>
            <td width="20%"><a href="#" id="user_type"><{admin_newsletter_email_history_user_type}></a></td>
            <td width="18%"><a href="#" id="priority"><{admin_newsletter_email_history_priority}></a></td>
            <td width="12%"><{admin_newsletter_email_history_action}></td>
          </tr>       
        <?php 
        if(isset($history_list)&&is_array($history_list)&&count($history_list))
        {
            $flag=true;
            foreach($history_list as $value)
            {				
                ?>
                <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
                <td class="left"><?php echo isset($value['email_tpl_id'])?(intval($value['email_tpl_id'])==0 ? '<{admin_newsletter_email_history_tpl_type_custom}>' : output($value['email_tpl_id'])):""?></td>
                <td class="left"><?php echo isset($value['date'])?output(nsdate($value['date'])):""?></td>
                <td style="cursor:pointer;" onclick="setLoginFilter(this);" class="left"><?php echo isset($value['login'])?output($value['login']):""?></td>
                <td style="cursor:pointer;" onclick="setUserTypeFilter('<?php echo isset($value['user_type'])?$value['user_type']:""?>');" class="left"><?php echo isset($value['user_type'])?$tpl_type['items'][$value['user_type']]:""?></td>
                <td class="left"><?php echo isset($value['priority'])?$tpl_priority[$value['priority']]:""?></td>
                <td>
                <!--<a style="cursor:pointer;" title="Send Email" onclick="emailSend(<?php echo $value['id']?>); return false;"><img src="<?php echo base_url()?>img/ico_newsletter.png" width="16" height="16" /></a>&nbsp;-->
                <a style="cursor:pointer;" title="View Email" onclick="emailInfo(<?php echo $value['id']?>); return false;"><img src="<?php echo base_url()?>img/ico_coupon.png" width="16" height="16" /></a>&nbsp;
                <a style="cursor:pointer;" title="Delete Email" onclick="emailDelete(<?php echo $value['id']?>); return false;" ><img alt="Delete" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
                </td>
                </tr>                
                <?php
            }
        }
        else
        {
            ?>
            <tr class="dark">
            <td colspan="6"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
        ?>
        </table>
        <br />
        <div class="page">
            <?php  echo $pagers['pager'][1]; ?>
        </div>
        <div class="after_table" style="padding-top: 10px; padding-bottom: 20px; text-align: right;">
        <?php if(intval($type['selected'])==2){?>
            <input type="button" class="button_super_big" id="send_portion_btn" value="<{admin_newsletter_email_history_send_portion}>" onClick="sendPortion()"/>
            <?php }?>
        <?php if(isset($temp_vars_set) && isset($temp_vars_set['member'])){?>
            <input type="button" class="button" value="<{admin_newsletter_email_history_btn_return}>" onClick="document.location.href='#member_list/<?php echo $temp_vars_set['member']?>/edit';"/>
            <?php }?>
        </div>
        <br/>
    </div>
    <br />
