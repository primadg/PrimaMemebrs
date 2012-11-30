<div id='main_panel_div'>

    <div id='temp_vars_set' style="display:none;">
    <?php echo isset($temp_vars_set)?create_temp_vars_set($temp_vars_set):""?>
    </div>

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_products_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{directories_file_protection_title}></div>
            <div class="header_comment"><{directories_file_protection_description}></div>
          </div>
        </div>
        <?php echo isset($messages) ? admin_print_msg_box('msg',$messages) : "";?>
		<?php echo isset($mess_err) ? admin_print_msg_box('emsg',$mess_err) : ""; ?>	   
		<br />        
        <div style="text-align:center;">
        <select id='product_selector' name='product' size='15' style='width:600px' onchange='product_selected()'>
<?php
foreach ($groups as $group)
{
    echo "<optgroup label='".output($group['name'])."'>";
	    foreach ($products as $product)
	    {
	        if ($product['group_id']==$group['id'])
	        {
	            echo "<option value='{$product['id']}'>".output($product['name'])."</option>";
	        }
	    }
    echo "</optgroup>";
}
?>
        </select>
        </div>

        <p><{directories_file_protection_message}></p>

        <table align='center' border='0' cellspacing='0' cellpadding='0'>
        <input type='hidden' id='code_template' value='<?php echo output($code)?>'/>
        <tr>
        <td>
        <textarea id='code_area' style="width:590px;" rows="5" onfocus="this.select()"></textarea>
        </td>
        </tr>
        <tr>
        <td style="text-align: center;">
        <br/>
        <input type="button" value="<{directories_file_protection_btn_copy_to_clipboard}>" class="clipboard button_save_as_template"/>
        </td>
        </tr>
        </table>
</div>
<br />
