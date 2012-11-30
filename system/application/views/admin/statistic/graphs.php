
        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url();?>img/ico_transaction_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_stats_graphs_header_subject}></div>
            <div class="header_comment"><{admin_stats_graphs_header_comment}></div>
          </div>
        </div>


        <div id="error_panel" class="mess_err" style="width: 500px; margin: 0 auto; display: none;">
          <b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
            <div  id="error_value" class="box_err"></div>
            <div  id="jsvalid_error_period_invalid" class="box_err" style="display: none"><{admin_stats_graphs_error_period_invalid}></div>
          <b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
        </div>
          
        
        <div id="graph" class="grafic">
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="680" height="300" id="ie_chart" align="middle" name="ie_chart">
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="movie" value="<?php echo base_url()?>swf/open-flash-chart.swf?data=<?php echo base_url()?>statistic/graphs_info/" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed id="chart"
src="<?php echo base_url()?>swf/open-flash-chart.swf?data=<?php echo base_url();?>statistic/graphs_info/" quality="high" bgcolor="#FFFFFF" width="680" height="300" name="chart" align="middle" allowScriptAccess="sameDomain"
type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" id="chart"/>
            </object>          
        </div>
        </br></br>
        <div class="filter">
		<table>
		<tr>
			<td>
			From: <input type="text" size="10" name="date_from" id="date1Entry" class="<?php echo datepicker_class();?>" value="" />&nbsp; - To &nbsp;
              <input type="text" size="10" name="date_to" id="date2Entry" class="<?php echo datepicker_class();?>" value="" />              
			</td>
			<td>
                    <input class="button" type="button" value="<{admin_stats_graphs_btn_show}>" 
                    onClick = "load_graphs();" />
                                    <input type="button" class="button" value="<{admin_coupon_statistic_search_button_clear}>" 
              onClick="field_clear({1:'date1Entry',2:'date2Entry'});" /> 
			</td>
        </tr>
		</table>
        </div>
<br />      
