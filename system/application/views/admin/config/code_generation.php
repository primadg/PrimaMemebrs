<?php

/**
 * Code Generation view
 *
 * @author Makarenko Sergey
 * @copyright 2008
 */

?>

        <div class="body_header">
          <div style="float: left;"><img alt="" src="<?php echo base_url()?>img/ico_sysconf_big.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{admin_code_generation_header_subject}></div>
            <div class="header_comment"><{admin_code_generation_header_comment}></div>
          </div>
        </div>

        <table align="center">
        <tr>
          <td><div class="tema"><{admin_code_generation_textarea_header}></div></td>
        </tr>
        <tr>
          <td><textarea id="html_code" readonly="true" style="width: 600px; height: 300px;" onclick="this.focus(),this.select()"><?php echo $html_code_for_login_form;?></textarea></td>
        </tr>
        <tr>
          <td style="text-align: center;"><input type="button" class="button_save_as_template" value="Copy to clipboard" onclick="clipboardCopy('html_code'); return false;" /></td>
        </tr>
        </table>
        <br />
