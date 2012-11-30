<?php header("HTTP/1.1 404 Not Found"); 
$base_path=explode("index.php",$_SERVER ['PHP_SELF']);
$base_path=$base_path[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style>
body {visibility:visible;} 
</style>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>404 Page Not Found</title>
<link rel="icon" href="<?php echo $base_path;?>img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo $base_path;?>img/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo $base_path;?>css/default.css" type="text/css" />
<script type="text/javascript" src="<?php echo $base_path;?>js/jquery/jquery-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $base_path;?>js/png.js"></script>
</head>
<body>
<div class="header">
<img class="logo" alt="LOGO" src="<?php echo $base_path;?>img/logo.png" />
<div class="header_text">
</div>
</div>
<div class="wrapper" style="background-color: #fff;">
<div class="line_body">&nbsp;</div>
<div class="body_center" style="text-align:center;width: 969px; height: 300px;">

<h1><?php echo $heading; ?></h1>
<?php echo $message; ?>
      
</div>
<div class="line_body"></div>

</div>

<div class="footer">
<img alt="" src="<?php echo $base_path;?>img/bootom_left_sqare.png" style="float: left;" width="5" height="5" />
<img alt="" src="<?php echo $base_path;?>img/bootom_right_sqare.png" style="float: right;" width="5" height="5" />
</div>

<div class="footer_copyrights"></div>
</body>
</html>
