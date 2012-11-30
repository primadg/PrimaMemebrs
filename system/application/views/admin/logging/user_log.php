<?php

/*********************************************************************************
*   Edited by Konstantin X @ 11:07 01.07.2008
**********************************************************************************/

/*********************************************************************************
*   Modified by Sergey Makarenko @ 14:51:42 01.10.2008
**********************************************************************************/
#print_r($this);
#print_r($filter);
?>
    <div id="main_panel_div">
    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>
    <div class="body_header">
        <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_logg_big.png" width="32" height="32" /></div>
            <div class="header_pad">
                <div class="header_subject"><{admin_logging_user_title}></div><!-- Admin log -->
                <div class="header_comment"><{admin_logging_user_description}></div><!-- View administrator activity log -->
            </div>
        </div>
        <?php echo isset($messages) ? admin_print_msg_box('msg',$messages) : "";?>
        <?php echo isset($mess_err) ? admin_print_msg_box('emsg',$mess_err) : "";?>
        <br />        
        <table  class="tab" width="700" align="center"><tr><td>
        <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
        <table class='filter'>
        <tr height="50px">
        <td>
            <{admin_logging_date}>:
        </td>
        <td valign="top">
            <div class="filter" style="width:280px; padding-left:10px;">
                <div style="float:left; width:140px;"><input type="text" size="10" value="<?php echo (isset($filter[0]))?output($filter[0]):''?>" name="date_a" id="date_a" class="<?php echo datepicker_class();?>" value="<?php echo (isset($filter[0]))?output($filter[0]):''?>" /><label for='date_a'></label>&nbsp; &mdash; &nbsp;</div>
                <div style="float:left; width:130px;"><input type="text" size="10" value="<?php echo (isset($filter[1]))?output($filter[1]):''?>" name="date_b" id="date_b" class="<?php echo datepicker_class();?>" value="<?php echo (isset($filter[1]))?output($filter[1]):''?>" /><label for='date_b'></label>&nbsp;</div>
            </div>
        </td>
        <td>
        </td>
        <td>
            <input type="button" class="button" value="<{admin_logging_btn_show}>" onclick="setFilter(false); return false;" align="middle" />&nbsp;|&nbsp;<input type="button" class="button" value="<{admin_logging_btn_clear}>" onclick="setFilter(true); return false;" />
        </td>
        </tr>
        </table>
        </fieldset>
        </td></tr></table>
        <br clear='all'/>
        <div class="page">
          <?php  echo $pagers['pager'][0]; ?>
        </div>
        <br />
<div>
<?php
    // if elements in array > 0 then show the table
    if (count($log)>0)
    {
        $heading = '<tr class="glav_big">';
        foreach($log[0] as $k=>$v)
        {
            if($k != 'action')
            {
                $heading .= '<th><a href="#" id="' . $k . '"><{admin_logging_col_' . $k . '}></a></th>';
            }
            else
            {
                $heading .= '<th><{admin_logging_col_' . $k . '}></th>';
            }
        }
        $heading .= '</tr>';

        echo '<table id="log_list" class="tab" width="700" align="center">' . $heading;

        $i = 0;

        foreach($log as $v)
        {
            $row_class = ($i++ % 2 == 1) ? "dark" : "light";

            echo '<tr class="' . $row_class . '">';
            echo '<td style="text-align: center;">' . nsdate($v["time"]) . '</td>';
            echo '<td style="text-align: left; padding-left: 5px;">' . $v["person"] . '</td>';
            echo '<td style="text-align: left; padding-left: 5px;" title="' . site_url($v["url"]) . '"><a href="' . site_url($v["url"]) . '">' . word_wrap($v["url"], 30, 4) . '</a></td>';
            echo '<td>' . $v["ip"] . '</td>';
                $str = mb_ereg('([A-Za-z]+://){0,1}([^/]+?)(/.*)', $v["referer"], $match);
            echo '<td style="text-align: left; padding-left: 5px;" title="' . $v["referer"] . '"><a href="' . $v["referer"] .'">' . $match[2] . word_wrap($match[3], 12, 4) . '</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    // if no elements found
    else
    {
        echo '<table id="log_list" class="tab" width="700" align="center"><tr class="glav_big"><td>' . '<{admin_logging_tbl_no_elements}>' . '</td></tr></table>';
    }
?>
</div>
        <br />
        <div class="page">
            <?php echo $pagers['pager'][0]; ?>
        </div>
    </div>
    <br />
