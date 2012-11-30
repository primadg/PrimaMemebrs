<div>
<?php 
if(isset($messages))
{
    foreach($messages as $k=>$v)
    {
        ?>
        <div class="dlg_messages" id="dlg_msg_<?php echo $k;?>" style="font-weight:bold;text-align:center;color:green;display:none;"><?php echo $v;?></div>
        <?php 
    }
}
?>
</div>
<div>
<?php 
if(isset($errors))
{
    foreach($errors as $k=>$v)
    {
        ?>
        <div class="dlg_errors" id="dlg_emsg_<?php echo $k;?>" style="font-weight:bold;text-align:center;color:red;display:none;"><?php echo $v;?></div>
        <?php 
    }
}
?>
</div>
<table id="dlg_table"  style="width:470px;" align='center'>
<tr class="dlg_data" id="dlg_title">
<td colspan="4" align="center" style="font-weight: bold;">
<?php echo isset($title)?$title:"";?>
</td>
</tr>
<tr class="dlg_data" id="dlg_text">
<td colspan="4">
<?php echo isset($text)?$text:"";?>
</td>
</tr>
<?php if(isset($details)){?>
<tr class="dlg_data" id="dlg_details">
<td colspan="4">
<fieldset style="overflow: visible; width:460px; height: 165px; display: block; border:1px solid #22AEFF;"><legend style="color:#22AEFF;" class="handpointer" onclick="filterResize(this,165)"><{admin_developer_dialog_details}><span id="toggle_arrow">&nbsp;&#9658;</span></legend>
<div class="filter" id="dev_detail" style="width:455px;height:150px;overflow:auto;">
<?php echo isset($details)?$details:"";?>
</div>
</fieldset>
<br/>
</td>
</tr>
<?php  } ?>
<tr id="dlg_progress" style="display:none;">
<td align="center" colspan="4"><img src="<?php echo base_url()?>img/8.gif"></td>
</tr>
<tr>
<td align="left" width="25%">
</td>
<td align="center" colspan="2" width="50%">
<input type="button" id="dlg_btn_ok" class="button_big" value="<{admin_btn_send}>" onClick="" />
</td>
<td align="center" width="25%">
</td>
</tr>
</table>
