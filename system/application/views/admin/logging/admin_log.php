<?php
/*********************************************************************************
*   Edited by Konstantin X @ 13:58 17.06.2008
**********************************************************************************/

/*********************************************************************************
*   Modified by Sergey Makarenko @ 14:07:42 01.10.2008
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
                <div class="header_subject"><{admin_logging_<?php echo $is_admin?'admin':'protection'?>_title}></div><!-- Admin log -->
                <div class="header_comment"><{admin_logging_<?php echo $is_admin?'admin':'protection'?>_description}></div><!-- View administrator activity log -->
            </div>
        </div>
        <?php echo isset($messages) ? admin_print_msg_box('msg',$messages) : "";?>
        <?php echo isset($mess_err) ? admin_print_msg_box('emsg',$mess_err) : "";?>
        <br />
        <table  class="tab" width="700" align="center"><tr><td>
        <fieldset><legend class="handpointer" onclick="filterResize(this)"><{admin_label_filter}><span id="toggle_arrow">&nbsp;&#9660;</span></legend>
        <table class='filter'>
        <tr>
        <td>
            <{admin_logging_action}>
        </td>
        <td align="left" style="padding-left:10px;">
        <select style="width: 100%;" name="action" id="frm_action">
        <?php $selected = (isset($filter[3]) && ($filter[3]=='-')) ? " selected='selected'" : null; ?>
            <option value="-"<?php echo $selected?>><{admin_logging_action_any}></option>
        <?php
        foreach ($actions as $action)
        {
            $selected = (isset($filter[3]) && ($action==$filter[3])) ? " selected='selected'" : null;
            ?>
            <option value="<?php echo $action?>"<?php echo $selected?>><{admin_log_<?php echo $action?>}></option>
            <?php
        }
        ?>
        </select>
        </td>
        <td>
            <?php echo $is_admin?"<{admin_logging_person}>":"";?>
        </td>
        <td>
        <?php
        if($is_admin)
        {
        ?>
            <select style="width: 100%;" name="frm_person" id="frm_person">
            <?php $selected_person = (isset($filter[2]) && ($filter[2]=='-')) ? " selected='selected'" : null; ?>
            <option value="-"<?php echo $selected_person?>><{admin_logging_person_any}></option>
            <?php
            foreach ($persons as $person)
            {
                $selected_person = (isset($filter[2]) && ($person==$filter[2])) ? " selected='selected'" : null;
                ?>
                <option value="<?php echo $person?>"<?php echo $selected_person?>><?php echo ($person=='UNDEFINED'?'<{admin_logging_person_undefined}>':$person)?></option>
                <?php
            }
            ?>
            </select>
        <?php
        }
        ?>
        </td>
        </tr>
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
<?php
//    var_dump($log);
    // if elements in array > 0 then show the table
    if (count($log)>0)
    {
        $heading = '<tr class="glav_big">';
        foreach($log[0] as $k=>$v)
        {
            if ($k != 'details')
            {
                if($k != 'action')
                {
                    $heading .= '<th><a href="#" id="' . $k . '"><{admin_logging_col_' . $k . '}></a></th>';
                }else{
                    $heading .= '<th><{admin_logging_col_' . $k . '}></th>';
                }
            }
        }
        $heading .= '</tr>';

        echo '<table id="log_list" class="tab" width="700" align="center">' . $heading;

        $i = 0;
        foreach($log as $i=>$v)
        {
            $row_class = ($i++ % 2 == 1) ? "dark" : "light";

            $onclick = "";
            if ($v['details'])
            {
                $name = "tr_details_$i";
                $onclick = ' class="handpointer" onclick="$(\'#'.$name.'\').toggle(); changeArrow(\''.$name.'\', \''.$v["record"].'\', \''.$i.'\'); return false;"';
                $v["record"] = "<span id='arrow_$i'>&#9658;</div> ".$v['record'];
            }

            echo '<tr class="' . $row_class . '">';
            if($is_admin)
            {
                echo '<td style="text-align: left; padding-left: 5px;">' . $v["person"] . '</td>';
            }
            echo "<td style='text-align: left; padding-left: 5px;'$onclick>" . $v["record"] . '</td>';
            echo "<td$onclick>" . $v["ip"] . '</td>';
            echo "<td$onclick>" . nsdate($v["time"]) . '</td>';
            echo '<td>' . $v["action"] . '</td>';
            echo '</tr>';

            if ($v['details'])
            {
                echo "<tr class='$row_class' id='$name' style='display:none'>";
                if($is_admin)
                {
                    echo "<td>&nbsp;</td>";
                }
                echo "<td colspan='4' align='left'>".array_to_string($v['details'])."</td>";
                echo '</tr>';
            }

        }
        echo '</table>';
    }
    // if no elements found
    else
    {
        echo '<table id="log_list" class="tab" width="700" align="center"><tr class="glav_big"><td>' . '<{admin_logging_tbl_no_elements}>' . '</td></tr></table>';
    }
?>
        <br />
        <div class="page">
            <?php  echo $pagers['pager'][1]; ?>
        </div>
    </div>
    <br />
