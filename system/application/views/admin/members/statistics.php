
        <div class="body_header">
          <div style="float: left;"><img src="<?php echo base_url();?>img/ico32_members.png" width="29" height="32" alt="<{admin_member_control_statistics_page_title}>" /></div>
          <div class="header_pad">
              <div class="header_subject"><{admin_member_control_statistics_page_title}></div>
              <div class="header_comment"><{admin_member_control_statistics_page_desc}></div>
          </div>
        </div>

        <table align="center">
		<tr>
            <td width="50" align="right"><{admin_member_control_statistics_label_year}></td>
            <td>
              <select id="year">
                <?php
                if( isset($years) && count($years) > 0 )
                {
                    foreach( $years as $item )
                    {
                        echo "<option>" . $item;
                    }
                }
                ?>
              </select>
            </td>
            <td width="50" align="right"><{admin_member_control_statistics_label_month}></td>
            <td>
              <select id="month">
                <option value="0"><{admin_member_control_statistics_name_month_all}>
                <?php
                    for($i=0; $i<12; $i++)
                    {
                        $j = $i + 1;
                        echo "<option value=\"".$j."\"><{month_name_".$j."}>";
                    }
                ?>
              </select>
            </td>
            <td><input type="button" class="button" value="<{admin_member_control_statistics_button_go}>"
            onClick="load_member_statistics();"/></td>
        </tr>
        </table>
        <br/><br/>
        <div id="graph" class="grafic">
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="680" height="300" id="ie_chart" align="middle" name="ie_chart">
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="movie" value="<?php echo base_url()?>swf/open-flash-chart.swf?data=<?php echo base_url()?>member/load_statistics/0/<?php echo $years[0]; ?>" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed id="chart"
src="<?php echo base_url()?>swf/open-flash-chart.swf?data=<?php echo base_url();?>member/load_statistics/0/<?php echo $years[0]; ?>" quality="high" bgcolor="#FFFFFF" width="680" height="300" name="chart" align="middle" allowScriptAccess="sameDomain"
type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" id="chart"/>
            </object>
        </div>

<br />
