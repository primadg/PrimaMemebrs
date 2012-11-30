<?php
$base_url_declared=explode("index.php",$_SERVER ['PHP_SELF']);
$base_url_declared=function_exists("base_url") ? base_url() : $base_url_declared[0];
$_SESSION['db_error']=true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style>
body {visibility:visible;} 
#content  {
background-color:	#fff;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#22AEFF;
margin: 			0 0 4px 0;
}
</style>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Database Error</title>
<link rel="icon" href="<?php echo $base_url_declared;?>img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo $base_url_declared;?>img/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo $base_url_declared;?>css/default.css" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url_declared;?>js/jquery/jquery-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $base_url_declared;?>js/png.js"></script>
</head>
<body>
<div class="header">
<img class="logo" alt="LOGO" src="<?php echo $base_url_declared;?>img/logo.png" />
<div class="header_text">
</div>
</div>
<div class="wrapper" style="background-color: #fff;">
<div class="line_body">&nbsp;</div>
<div class="body_center" style="text-align:center;width: 969px; height: 300px;">
<div id="content">
<h1><?php echo $heading; ?></h1>
<?php echo $message; ?>
</div>
</div>
<div class="line_body"></div>
</div>
<div class="footer">
<img alt="" src="<?php echo $base_url_declared;?>img/bootom_left_sqare.png" style="float: left;" width="5" height="5" />
<img alt="" src="<?php echo $base_url_declared;?>img/bootom_right_sqare.png" style="float: right;" width="5" height="5" />
</div>
<div class="footer_copyrights"></div>
</body>
</html>
