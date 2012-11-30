<?php require_once("login_header.php");?>
      <div class="body_center" style="width: 969px; height: <?php echo defined('NS_DEMO_VERSION')?"350":"300";?>px; ">
        <br/>
        <?php echo admin_print_msg_box('msg',$messages); ?>
		<?php echo admin_print_msg_box('emsg',$mess_err); ?>
        <br/>        
        <input name='is_page' id='is_page' type="hidden"/>
        <input name='action' id='action' type="hidden"/>
        <table align="center">
        <?php if(defined('NS_DEMO_VERSION')){?>
            <tr>
            <td colspan=2 align="center" style="color:blue;">To access demo version please use the following data:</td>
            </tr>
            <tr>
            <td align="right" style="font-weight: bold; font-size: 11px;">Login:<br/>Password:</td>
            <td align="left" style="color: orange; font-weight: bold; font-size: 11px;">super_admin<br/>super_admin</td>
            </tr>
            <tr>
            <td align="right" style="font-weight: bold; font-size: 11px;">Login:<br/>Password:</td>
            <td align="left" style="color: orange; font-weight: bold; font-size: 11px;">admin1<br/>admin1</td>
            </tr>
            <tr>
            <td align="right" style="font-weight: bold; font-size: 11px;">Login:<br/>Password:</td>
            <td align="left" style="color: orange; font-weight: bold; font-size: 11px;">admin2<br/>admin2</td>
            </tr>
            <?php }?>
        <tr>
        <td align="right" style="width: 160px;"><label for='lf_login'><{admin_login_form_username}></label></td>
        <td><input name='lf_login' type="text" style="width: 160px;" <?php echo defined('NS_DEMO_VERSION')?"value='super_admin'":"";?>/></td>
        </tr>
        <tr>
        <td align="right"><label for='lf_pwd'><{admin_login_form_password}></label></td>
        <td><input name='lf_pwd' type="password" style="width: 160px;" <?php echo defined('NS_DEMO_VERSION')?"value='super_admin'":"";?>/></td>
        </tr>
        <?php if(isset($show_capcha) and intval($show_capcha)==1){?>
            <tr>
            <td align="right"><label for='lf_capcha_code'><{admin_login_input_capcha}>:</label></td>
            <td>
            <input valign="bottom" type="text" name="lf_capcha_code" id="lf_capcha_code" value="" style="width: 70px; margin-top:10px;" />
            <img onclick="reloadCapcha(this);" title="<{admin_login_capcha_reload}>" src="<?php echo site_url('capcha/draw');?>" border="0" style="vertical-align:top;" >
            </td>
            </tr>
            <?php }?>
        <tr>
        <td align="right">
        <a class="handpointer" href="javascript: loginFormRemind();"><{admin_login_form_remind}></a>
        </td>
        <td align="right">
        <input type="button" class="button" value="<{admin_login_form_btn_login}>" onClick="loginFormSend();" />
        </td>
        </tr>
        </table>
       </div>
<?php require_once("footer.php");?>
