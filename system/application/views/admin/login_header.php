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
    <title><?php echo config_get('system','config','site_name');?></title>
    <link rel="icon" href="<?php echo base_url()?>img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo base_url()?>img/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo base_url()?>css/default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/datepicker.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url()?>js/jquery/jquery-1.2.6.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery/getScriptSet-plugin.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/png.js"></script>
    <script type="text/javascript" >
    var base_url='<?php echo base_url()?>';
    window.cronManually=<?php echo (get_debug_params(6)>0)?'true':'false'?>;
    <?php if(isset($location) && !empty($location)){?>
        window.location.hash='<?php echo "#".$location?>';
        <?php }?>
    var resources=new Array(
    base_url+"js/interface.js",
    base_url+"js/jquery/jquery.bgiframe.js",
    base_url+"js/jquery/dimensions.js",
    base_url+"js/jquery/jquery.tooltip.pack.js",
    base_url+"js/tab.js",
    base_url+"js/service.js",
    base_url+"js/validation.js",
    base_url+"js/admin/panel_init.js",
    base_url+"js/datepicker.js",
    base_url+"js/design.js",
    base_url+"js/block.js",
    base_url+"js/admin/login.js"
    );
    $(document).ready(function(){
        $.getScriptSet(resources,function(){
            $("body").css("visibility","visible");
            var cookie_enabled=true;
            if($('#emsg_lf_javascript_disabled').length>0)
            {
                $.cookie('cookie_test',1);
                if($.cookie('cookie_test')!=1)
                {
                    cookie_enabled=false;
                }
                $.cookie('cookie_test',null);
            }
            displayMessageEx("",true,"lf_javascript_disabled");
            if(!cookie_enabled)
            {
                displayMessageEx("lf_cookies_disabled",true);
            }
            loginPageInit();
            cronInit();
        });
    });
    </script>
  </head>
  <body>
    <?php echo defined('NS_DEMO_VERSION') ? "<span id='demo_timer' class='demo'><{demo_header_warning}>".time_left(date('Y-m-d 00:00:00',time()+86400))."</span>" : ""?>
    <div class="header">

      <!--<div class="block_error">
        <div class="installe">Не удален файл installe.php</div>
        <div class="pay_system">System Error</div>
      </div>-->

      <img class="logo" alt="LOGO" src="<?php echo base_url()?>img/logo.png" />
      <div class="header_text">
        &nbsp;
      </div>
    </div>
    <div class="wrapper">
      <div class="line_body">&nbsp;</div>
