<!--[if lte IE 6]>
<style type="text/css">
html{background:url(fake.gif) no-repeat 0 0}/* use a 1px x 1px transparent gif which cures the jitters on the footer when using this expression*/
#login_form 
{
margin-left:0%; 
margin-top:-28.5%; 
position: absolute;
overflow:hidden;
top:expression(eval(document.compatMode && document.compatMode=='CSS1Compat') ?documentElement.scrollTop+(documentElement.clientHeight-this.clientHeight)-300:document.body.scrollTop+(document.body.clientHeight-this.clientHeight));
}
</style>
<![endif]-->
<div id="login_form_temp" class="login_form" style="display:none;">
<div class="login_mess" style="width: 500px; margin: 0 auto;">
<b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b>
<div style="background-color: #D6F0FF;  border-right:3px solid #22AEFF; border-left:3px solid #22AEFF;">
<div class="handpointer" onclick="loginFormHide();" style="float: right; margin-right:8px; margin-right:8px; color: #22AEFF; font-weight: bold;" >X</div>
<div style="height: 20px;">&nbsp;</div>
<div class="lf_message" id="lf_mail_sended_temp">
<{admin_login_form_msg_mail_sended}>
</div>
<div class="lf_error" id="lf_login_error_temp">
<{admin_login_form_msg_er_username}>
</div>
<div class="lf_error" id="lf_pwd_error_temp">
<{admin_login_form_msg_er_password}>
</div>
<div class="lf_error" id="lf_login_pwd_error_temp">
<{admin_login_form_msg_er_login}>
</div>
<div class="lf_error" id="lf_not_exist_temp">
<{admin_login_form_msg_er_not_exist}>
</div>
<div class="lf_error" id="lf_mail_not_sended_temp">
<{admin_login_form_msg_er_mail_not_sended}>
</div>
<div class="lf_error" id="lf_capcha_code_temp">
<{admin_login_form_msg_er_capcha_code}>
</div>
<div class="lf_error" id="lf_ip_banned_temp">
<{admin_login_form_msg_er_ip_banned}><span id="lf_ip_ban_reason_temp"></span>
</div>
<div class="lf_error" id="lf_ip_blocked_temp">
<{admin_login_form_msg_er_ip_blocked}><span id="lf_ip_block_period_temp"></span>
</div>
<table align="center">
<tr>
<td align="right" style="width: 80px;"><label for='lf_login_temp'><{admin_login_form_username}></label></td>
<td><input name='lf_login_temp' type="text" style="width: 160px;"/></td>
</tr>
<tr>
<td align="right"><label for='lf_pwd_temp'><{admin_login_form_password}></label></td>
<td><input name='lf_pwd_temp' type="password" style="width: 160px;"/></td>
</tr>
<tr id="lf_capcha_row_temp" style="display:none;">
<td align="right"><label for='lf_capcha_code_temp'><{admin_login_input_capcha}>:</label></td>
<td>
<input valign="bottom" type="text" name="lf_capcha_code_temp" value="" style="width: 70px; margin-top:10px;" />
<img onclick="reloadCapcha(this);" title="<{admin_login_capcha_reload}>" src="<?php echo site_url('capcha/draw');?>" border="0" style="vertical-align:top;" >
</td>
</tr>
<tr>
<td colspan="2" align="right">
<div style="float: left; padding-top: 3px;"><a class="handpointer" href="javascript: loginFormRemind();"><{admin_login_form_remind}></a></div>
<div><input type="button" class="button" value="<{admin_login_form_btn_login}>" onClick="loginFormSend();" /></div>
</td>
</tr>
</table>
<div style="height: 20px;">&nbsp;</div>
</div>
<b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b>
<!--[if lte IE 6]><iframe></iframe><![endif]-->
</div>
</div>
