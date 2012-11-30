<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <style>
    body {visibility:hidden;} 
    </style>
    <noscript>
    <style>
    body {visibility:visible;} 
    </style>
    </noscript>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title><?php if(isset($site_name)){echo $site_name;} ?></title>    
    <link rel="icon" href="<?php echo base_url()?>img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo base_url()?>img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url()?>css/default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/datepicker.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/jquery.flexbox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url()?>js/jquery/jquery-1.2.6.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery/getScriptSet-plugin.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/png.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/png.js"></script>
    <script type='text/javascript' id='lang_script'></script>
    <script type="text/javascript" >
    <?php  
    if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(3)>0)
    {
    echo "window.debugLog=true;\n";
    }
    echo "window.debugMenuItems={};\n";
    $res=array();
    $params=debug_params();
    $params_values=get_debug_params();
    foreach($params as $key=>$value)
    {
        echo "window.debugMenuItems[".$key."]={};\n";
        echo "window.debugMenuItems[".$key."]['enable']=".$params_values[$key].";\n";
        echo "window.debugMenuItems[".$key."]['text']='".$value."';\n";
        
    }
    ?>
    window.cronManually=<?php echo (get_debug_params(6)>0)?'true':'false'?>;
    window.copyToClipboardDialog='<{admin_copy_to_clipboard_dialog}>';
    window.date_format='<?php echo config_get('system','config','date_format');?>';
    <?php echo ht_sys_config_not_writable() ? "window.ht_sys_config_not_writable='<{admin_sys_er_config_file_is_not_writable}>';" : "";?>
    var base_url='<?php echo base_url()?>';
    <?php if(isset($location) && !empty($location))
    {
        ?>
        if(window.location.hash=='')
        {
            window.location.hash='<?php echo "#".$location?>';
        }
        <?php 
    }
    ?>
    window.nsconfig={};
    window.nsconfig.paymentSystems=<?php echo pay_sys_to_json();?>;
    window.scriptResources=new Array(
    base_url+"js/jquery/jquery.ajax_upload.0.6.min.js",
    base_url+"js/interface.js",
    base_url+"js/jquery/jquery.bgiframe.js",
    base_url+"js/jquery/dimensions.js",
    base_url+"js/jquery/jquery.tooltip.pack.js",
    base_url+"js/jquery/jquery.tablednd_0_5.js",
    base_url+"js/jquery/jquery.treeview.pack.js",
    base_url+"js/jquery/jquery.flexbox.min.js",
    base_url+"js/jquery/AjaxUpload.2.0.js",
    base_url+"js/service.js",
    base_url+"js/tab.js",
    base_url+"js/validation.js",
    base_url+"js/admin/panel_init.js",
    base_url+"js/datepicker.js",
    base_url+"js/design.js",
    base_url+"js/block.js",
    base_url+"js/validation.js",
    base_url+"js/admin/login.js",
    base_url+"js/clipboard.js",
    base_url+"js/admin/global.js"
    );
    $(document).ready(function(){
        <?php  apache_get_modules();
        if(defined("APACHE_GET_MODULES")){ ?>
        $.getScript(base_url+"system/application/helpers/apache_get_modules/apache_get_modules.php");
        <?php  } ?>
        //$("body").children().css("visibility","hidden");
        $.getScriptSet(window.scriptResources,function(){
            $("body").css("visibility","visible");
            myMainPageOnLoad();
            cronInit();
        });
    });
    </script>
    </head>
  <body>
    <?php  require_once ('login_form.php');?>
    <noscript>
    <style>span.demo{display:none;}</style>
    <span class='demo' style="display:inline;"><{admin_login_form_msg_er_javascript_disabled}></span>
    </noscript>
    <?php if(defined('DEBUG_RESPONSE_FLAG')){?>
        <span class='debug'>
        <div id='debug_bg' style='margin: 0 auto;width:100px;'>
        <span class='debug_blink'><{debug_header_warning}></span>
        <br/>
        <div id='debug_cn' style='height:1px;'>
        </div>
        </div>
        </span>
        <?php }?>
    <?php echo defined('NS_DEMO_VERSION') ? "<span id='demo_timer' class='demo'><{demo_header_warning}>".time_left(date('Y-m-d 00:00:00',time()+86400))."</span>" : ""?>
    <?php echo defined('NS_TRIAL_VERSION') ? "<span style='color:red;text-decoration:blink;font-weight:bold;position:absolute;width:100%;text-align:center;margin-top:10px;'>TRIAL VERSION</span>" : ""?>
    <span id="admin_header_status_offline" class='demo' style='<?php echo intval(config_get('system','status','online'))>0 ? "display:none;" : ""?>'>
    <{admin_header_status_offline}>
    </span>
    <div class="header">

      <div class="block_error" id="error" style="display: none;">
        <div class="global_error" onClick="$('#error').fadeOut();">System Error</div>
        <div class="global_error" id="error_msg"></div>
      </div>

      <!--div для меню<div style="z-index: 10; position: absolute; width: 238px; height: 100px; background-image: url('./img/fon.png');"></div>-->

      <a href="<?php echo base_url()?>admin" title=""><img class="logo" alt="LOGO" src="<?php echo base_url()?>img/logo.png" /></a>
      <div class="header_text">
    <table align="center" border="0px" width="100%" border="1" height="80" cellspacing="0" cellpadding="0">
    <tr>
    <td>
    </td>
    <td style="color: #fff; vertical-align: bottom; text-align: right; height:50%; padding-right:12px;">
    <?php if(Functionality_enabled('admin_multi_language')===true){?>
        <select <?php echo (Functionality_enabled('admin_admin_account_modify', intval($this->admin_auth_model->admin_id))!==true) ? "disabled" : ""?> id='language_selector' name="lang_id" onChange="changeLanguage();" style="color:#ffffff ;border:1px solid #22AEFF; background-color:#56c9f5;width: 100px;">
        <?php
        if(isset($languages) and is_array($languages) and sizeof($languages)>0 )
        {
            foreach($languages as $lang)
            {
                ?><option style="background-color:#56c9f5;" <?php echo ($lang['id']==$current_language) ? "selected" : ""?> value="<?php echo $lang['id']?>"><?php echo $lang['name'];?></option><?php 
            }
        }
        ?>
        </select>
        <?php if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0){?>
            <a style="text-decoration: none;" class="link_buttons" onClick="languageSimpleTranslate();return false;" href="#"><img src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" /></a>
            <?php }
    }?>
    
    </td>
    </tr>
    <tr>
    <td style="vertical-align: bottom;width:200px;">
    <span id='user_info'><div style="float:left;"><{admin_header_user_info_start}> <span id='user_info_username'><?php echo isset($username)?output($username):""?></span><{admin_header_user_info_medium}></div><div style="float:left;"><{admin_header_user_info_end}> <span id='user_info_last_login'><?php echo isset($last_login)?nsdate($last_login):""?></span></div></span>
    </td>
    <td style="vertical-align: bottom;" width="270"><a href="#" style="vertical-align: -4px;"></a>
    <a href="#" onClick="load_home();return false;"><img alt="" src="<?php echo base_url()?>img/index.png" border="0" class="header_icon"></a><a href="#" onClick="load_home();return false;" class="header_link"><{admin_header_home}></a>&nbsp;
    
    <?php  if(Functionality_enabled('admin_developers_notification')===true) { ?>
    <a style="text-decoration: none;" class="link_buttons" onClick="devDlg();return false;" href="#"><img src="<?php echo base_url()?>img/ico_newsletter.png" width="16" height="16" style="vertical-align:bottom;" title="<{admin_header_developers_notification}>"  /> </a><a onClick="devDlg();return false;" href="#" class="header_link"><{admin_header_support}></a>&nbsp;
    <?php  } ?>
    
    <a href="#" onClick="adminChangePassword();return false"><img alt="" src="<?php echo base_url()?>img/change_pass.png" border="0" class="header_icon"></a><a href="#" onClick="adminChangePassword();return false;" class="header_link"><{admin_header_change_password}></a>&nbsp;
    <a href="#" onClick="sendPost(base_url+'admin',{'action':'logout'});return false"><img alt="" src="<?php echo base_url()?>img/logout.png" border="0" class="header_icon"></a><a href="#" onClick="sendPost(base_url+'admin',{'action':'logout'});return false;" class="header_link"><{admin_header_logout}></a>&nbsp;
    </td>
    </tr>
    </table>
      </div>
    </div>
    <div class="wrapper" style="background-color: #fff;">
      <div class="line_body">&nbsp;</div>
