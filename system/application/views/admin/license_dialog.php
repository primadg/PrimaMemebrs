<div>
<h2 style="text-align:center; margin-top:-10px;"><?php echo isset($title)?$title:"";?></h2>
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
        <div class="dlg_errors" id="lisense_emsg_<?php echo $k;?>" style="font-weight:bold;text-align:center;color:red;display:none;"><?php echo $v;?></div>
        <?php 
    }   
}
?>
</div>
<br>
<table id="dlg_table"  style="width:470px;" align='center'>
<tr class="dlg_data" id="dlg_text"><label for='license_dlg_key'><{admin_input_license_key}><span style="color: red;">*</span></label>
<td align="center" colspan="3" width ="60%"><input type="text" value="" style="width: 97%;" id="license_dlg_key" name="license_dlg_key"/></td>

</tr>
<tr id="dlg_progress" style="display:none;">
<td align="center" colspan="4"><img src="<?php echo base_url()?>img/8.gif"></td>
</tr>
<tr>
<td align="center" colspan="4" width="50%">
<input type="button" id="lisense_btn_ok" class="button_big" value="<{admin_btn_send}>" onClick="" />
</td>

</tr>
</table>
