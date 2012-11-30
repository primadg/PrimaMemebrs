<div class="page">
	<?php  echo $pagers['pager'][0]; ?>
</div> 

<table class="tab" align="center" width="700">
	<tr class="glav_big">
		<td width="180"><{admin_config_ban_ip_table_ban_ip}></td>
		<td><{admin_config_ban_ip_table_ban_reason}></td>
		<td width="70"><{admin_config_ban_ip_table_action}></td>
	</tr>
          
	<?php echo $rows;?>

</table>
<br/><br/>
<div class="add" style="float:right; width:100px; padding-right:25px;">
	<input type="button" value="<{admin_config_ban_ip_button_add}>" class="button" onClick="menu_click(6, 5, '<?php echo site_url('config/ban_ip_add')?>',{1:'<?php echo base_url()?>js/admin/init.js'}); return false;" />
</div>
<br/><br/>
<div class="page">
	<?php  echo $pagers['pager'][1]; ?>
</div>

