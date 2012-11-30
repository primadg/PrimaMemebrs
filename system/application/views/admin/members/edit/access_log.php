
<script type="text/JavaScript">
    $(document).ready(set_dp);
</script>


    <div class="filter1">
        <div style="padding-left: 120px;">
            <div style="padding-top: 6px;"><{admin_member_control_account_panel_access_log_field_date}></div>
            <div>
                <input type="text" size="10" name="date_from" id="date_from" class="<?php echo datepicker_class();?>" value="<?php echo isset($date_from)? $date_from : "";?>">
                &nbsp; &mdash; &nbsp;
                <input type="text" size="10" name="date_to" id="date_to" class="<?php echo datepicker_class();?>" value="<?php echo isset($date_to) ?$date_to : "";?>">
                &nbsp; &nbsp; &nbsp;
                <input type="button" class="button" value="search" align="middle" 
                 onClick = "load_access_log_list({log_is_search: true}); return false;"></div>
        </div>
    </div> 

    
    <div class="tema"><{admin_member_control_account_panel_access_log_page_title}></div>
          
        <div class="page">
            <?php echo $pager_node1; ?>
        </div>


          <table class="tab" align="center" width="680">
            <tr class="glav_big">
              <td><a href="#" 
              onClick = "load_access_log_list({ord: 'by_date'});return false;"><{admin_member_control_account_panel_access_log_table_date_time}></a>
              <?php echo ($sort_by=='by_date'||$sort_by=='') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_access_log_list({ord: 'by_product'});return false;"><{admin_member_control_account_panel_access_log_table_products}></a>
              <?php echo ($sort_by=='by_product') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_access_log_list({ord: 'by_url'});return false;"><{admin_member_control_account_panel_access_log_table_url}></a>
              <?php echo ($sort_by=='by_url') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_access_log_list({ord: 'by_ip'});return false;"><{admin_member_control_account_panel_access_log_table_ip}></a>
              <?php echo ($sort_by=='by_ip') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
              <td><a href="#"
              onClick = "load_access_log_list({ord: 'by_http_referer'});return false;"><{admin_member_control_account_panel_access_log_table_http_referrer}></a>
              <?php echo ($sort_by=='by_http_referer') ? ($sort_how=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";?>
              </td>
            </tr>
            
        <?php 
            if( isset($items) && count($items) > 0 )
            {
                $tr_class = 'dark';
                foreach( $items as $row )
                { 
                    $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
        ?>            
            <tr class="<?php echo $tr_class;?>">
              <td><?php echo isset($row['cdate']) ? nsdate($row['cdate']) :"";?></td>
              <td><?php echo wordwrap($row['name'],30,'<br>',1);?></td>
              <td><a href="#"><?php echo wordwrap($row['url'],30,'<br>',1);?></a></td>
              <td><?php echo $row['ip'];?></td>
              <td><a href="#"><?php echo wordwrap($row['http_referer'],20,'<br>',1);?></a></td>
            </tr>
        <?php
                }
            }
            else
            {
                echo '<tr class="dark"><td colspan="5"><{admin_table_empty}></td></tr>';
            }            
        ?>
        </table>
          
        <div class="page">
            <?php echo $pager_node2; ?>
        </div>
<br />  
