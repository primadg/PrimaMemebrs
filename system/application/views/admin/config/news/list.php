
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_config_manage_news_label}></div>
            <div class="header_comment"><{admin_config_manage_news_label_desc}></div>
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
        
        
        
        <div  id="delete_question" style="display: none"><{admin_msg_delete_question}></div>
        
        <div class="page">    
          <?php  echo perpage_selectbox($per_page_set,'load_news_list',array(), $per_page); ?>
          <?php echo page_selectbox($pages,'load_news_list',array('ppage'=>$per_page), $current_page); ?>
        </div>


        <table class="tab" align="center" width="700">
          <tr class="glav_big">
            <td><a href="#" class="sort"
                   onClick = "load_news_list({ord: 'by_subject'});return false;"><{admin_config_manage_news_table_content}></a></td>
            <td width="80"><a href="#"
                              onClick = "load_news_list({ord: 'by_date'});return false;"><{admin_config_manage_news_table_date}></a></td>
            <td width="60"><{admin_config_manage_news_table_action}></td>
          </tr>

        <?php 
            if( isset($items) && count($items) > 0 )
            {
                foreach( $items as $row )
                { 
        ?>
            <tr class="light">
                <td class="left"><strong><?php echo $row['name'] ;?></strong><br /><?php echo $row['descr'];?></td>
                <td><?php echo $row['date'];?></td>
                <td>
                    <a href="#"  
                    title="<{admin_member_control_img_tooltip_edit}>"
                    onClick="req_load_edit_news_form('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_edit}>" src="<?php echo base_url();?>img/ico_settings.png" width="16" height="16" /></a>&nbsp;
                    <a href="#" 
                    title="<{admin_member_control_img_tooltip_delete}>"
              onClick="delete_news('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>

                </td>
            </tr>
        <?php
                }
            }    
        ?>


        </table>
          

        <div class="add"><input type="button" class="button_big" value="<{admin_config_manage_news_button_add_news}>" onClick="load_panel('<?php echo base_url()?>config/news_add');" /></div>

        <div class="page">    
          <?php  echo perpage_selectbox($per_page_set,'load_news_list',array(), $per_page); ?>
          <?php echo page_selectbox($pages,'load_news_list',array('ppage'=>$per_page), $current_page); ?>
        </div>
        <br />        
        
