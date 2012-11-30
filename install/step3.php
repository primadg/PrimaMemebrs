<?php if(!defined('cInstallReady')) define('cInstallReady', true);
/*
+-------------------------------------------------------------------------------------------------------------------
| STEP 3 - Title and system settings
+-------------------------------------------------------------------------------------------------------------------
| cInstall is a installation system developed by Conkurent LLC
| (C) 2008 Conkurent LLC.
*/

include_once('inc.config.php');
include_once('ktest.class.php');

$c = new kTest;

//step_sequence_management
$c->step_sequence(3);
//end_of_step_sequence_management

if (isset($_GET['lang'])) $sys_lang = $_GET['lang'];                                                                // Requested language from prev. step ...
else $sys_lang = $c->languageDetect();                                                                              // ... or check for supported languages from HTTP_ACCEPT_LANGUAGE

$aLanguage  = $c->init($sys_lang);                                                                                  // Load language array with selected language (if exist)

/*   auto-detect    ***********************************************************************************************/

//System URL
$protocol = (array_key_exists("HTTPS", $_SERVER) && ($_SERVER["HTTPS"] == "on")) ? 'https://' : 'http://';          // HTTPS or HTTP
$hts_url  = dirname(dirname($c->parsePHPConfig('script_name'))).'/';
$sys_url  = $protocol . $c->parsePHPConfig('http_host') . $hts_url;         // get url without "install/step3.php"

//$hts_url  = substr($c->parsePHPConfig('script_name'),0, -17);                                                       // URL For internal use without "install/step3.php"
//System Path

$sys_path = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/';  // get path without "install"

if(!isset($_POST['sys_path'])||mb_strlen($_POST['sys_path'])==0||mb_strlen($_POST['sys_path'])>254||!file_exists($_POST['sys_path']))
{
    $sp=$sys_path;    
}
else
{
    $sp=$_POST['sys_path'];
}
if(file_exists($sp.'system/application/models/main_version.php'))
{
    include_once($sp.'system/application/models/main_version.php');
}
if(file_exists($sp.'system/application/models/version.php'))
{
    include_once($sp.'system/application/models/version.php');
}
else
{
    define('NS_UPGRADE', TRUE);
}
if(defined('NS_UPGRADE'))
{
    $_SESSION['is_upgrade']=true;
}
else if(isset($_SESSION['is_upgrade']))
{
    unset($_SESSION['is_upgrade']);
}
$product = 0;
    if (defined('NS_BASIC_VERSION')) $product = 12;
    if (defined('NS_PRO_VERSION')) $product = 13;
    if (defined('NS_ENTERPRISE_VERSION')) $product = 14;
    if (defined('NS_TRIAL_VERSION')) $product = 0;
    $_SESSION['product'] = $product;
/*   Validation     ***********************************************************************************************/
$aErrors = array(); // 'sys_title' => 'e_not_empty', 'sys_url' => 'e_not_empty'

if (!empty($_POST))
{
    $aErrors = $c->testMySQLConnection(trim($_POST['db_host']), trim($_POST['db_port']), trim($_POST['db_user']), trim($_POST['db_pass']), trim($_POST['db_name']), trim($_POST['db_prefix']), TRUE);

    if(!isset($_POST['sys_title'])||mb_strlen($_POST['sys_title'])==0||mb_strlen($_POST['sys_title'])>254)
    {
        $aErrors['sys_title'] = 'e_not_empty';
    }
    if (isset($_SESSION['product']) && $_SESSION['product']!= 0)
               $check_license['status']='OK';
        
    
   // if(!isset($_POST['sys_url'])||mb_strlen($_POST['sys_url'])==0||mb_strlen($_POST['sys_url'])>254||eregi("^((news|telnet|nttp|file|http|ftp|https)://){0,1}(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?",$_POST['sys_url'])===false)  
    if(!isset($_POST['sys_url'])||mb_strlen($_POST['sys_url'])==0||mb_strlen($_POST['sys_url'])>254||preg_match("/^((news|telnet|nttp|file|http|ftp|https):\/\/){0,1}((([0-9a-z\-\_]+)\.)+)(([0-9a-z\-\_])+)(:(\d{1,5}))?(\/([0-9a-z\-\_]*))*\/$/i",$_POST['sys_url'])==false)
    {
        $aErrors['sys_url'] = 'e_not_empty';
    }
    if(!isset($_POST['sys_path'])||mb_strlen($_POST['sys_path'])==0||mb_strlen($_POST['sys_path'])>254||!file_exists($_POST['sys_path'])||(mb_strrpos($_POST['sys_path'],'/')!=mb_strlen($_POST['sys_path'])-1))
    {
        $aErrors['sys_path'] = 'e_not_empty';
    }
    else if(isset($_SESSION['is_upgrade']))
    {
        if(file_exists($_POST['sys_path'].'upgrade/kUpgrade.class.php'))
        {
            include_once($_POST['sys_path'].'upgrade/kUpgrade.class.php');
            $u = new kUpgrade(4);
            if(empty($_POST['ns1path']) && defined('NS_DEBUG_VERSION'))
            {
                unset($_SESSION['is_upgrade']);                
            }
            else if (!$u->init_test($_POST['ns1path']))
            {
                $aErrors['ns1path'] = 'e_not_exist';
            }            
        }
        else
        {
            $aErrors['ns1path'] = 'e_not_empty';
        }
    }
    
    
    if( !preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/',$_POST['sys_ip']))
    {
        $aErrors['sys_ip'] = 'e_not_empty';
    }

    if (!$c->validateDateFormat(trim($_POST['date_format']))) $aErrors['date_format'] = 'e_date_format';
    

}else{
    $aValue = array('sys_url'        => $sys_url,
    'sys_path'       => $sys_path,
    'sys_ip'         => $c->parsePHPConfig('server_addr'),
    'db_host'        => $iConfig['db_host'],
    'db_port'        => $iConfig['db_port'],
    'db_prefix'      => $iConfig['db_prefix'],
    'date_format'    => $iConfig['date_format']);
}

if (isset($_POST['sys_title']) && empty($aErrors))
{
    session_start();
    $aSystem =array();
    foreach($_POST as $key=>$value)
    {
        $aSystem[$key]=trim($value);
    }
    if (isset($aSystem['demodata'])&&$aSystem['demodata'] =="on") $aSystem['demodata']=1;
    else $aSystem['demodata']=0;
    if(defined('NS_UPGRADE')) $aSystem['demodata']=1;
    $aSystem['version']=defined('NEEDSECURE_VERSION') ? NEEDSECURE_VERSION : "undefined";
    $aSystem['sys_title']=$aSystem['sys_title'];    
    $aSystem['hts_url'] = $hts_url;
    $_SESSION['system_settings'] = $aSystem;
    //step_sequence_management
    $_SESSION['step'] = 3;
    //end_of_step_sequence_management
    header('Location: step4.php?lang='. $sys_lang);
    exit;
}
else
{
    if(isset($_POST['sys_title']))
    {
        $_POST['sys_title']=$_POST['sys_title'];
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

<title><?php echo $c->t('c_title',FALSE);?></title>

<link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />

<link type="text/css" href="css/main.css" rel="stylesheet" media="screen" />
<link type="text/css" href="css/form.css" rel="stylesheet" media="screen" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>

</head>
<body>
<div class="center">
<table class="header" width="600">
<tr class="h">
<td>
<a href="http://www.primadg.com"><img border="0" src="img/logo.png" alt="Logo" /></a>
<h1 class="p"><?php echo $c->t('c_title');?></h1>
</td>
</tr>
</table>
<div id="stylized" class="myform" style="width:580px; padding:5px;">
<form name="system_form" id="system_form" action="" method="post">
<input type="hidden" name="language" value="<?php echo $sys_lang;?>" />
<fieldset><legend><span><?php echo $c->t('c_legend_general');?></span></legend>
<div class="fieldgrp">
<label for="sys_title"><?php echo $c->t('c_label_title');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="sys_title" name="sys_title" value="<?php echo htmlspecialchars($c->v('sys_title'));?>" style="width:300px" />
<?php echo $c->e('sys_title');?>
</div>
</div>

<div class="fieldgrp">
<label for="sys_url"><?php echo $c->t('c_label_url');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="sys_url" name="sys_url" value="<?php echo htmlspecialchars($c->v('sys_url'));?>" style="width:300px" />
<?php echo $c->e('sys_url');?>
</div>
</div>

<div class="fieldgrp">
<label for="sys_path"><?php echo $c->t('c_label_path');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="sys_path" name="sys_path" value="<?php echo htmlspecialchars($c->v('sys_path'));?>" style="width:300px" />
<?php echo $c->e('sys_path');?>
</div>
</div>

<?php if(isset($_SESSION['is_upgrade'])){ ?>
    <div class="fieldgrp">
    <label for="ns1path"><?php echo $c->t('c_label_ns1path');?></label>
    <div class="field">
    <acronym <?php echo defined('NS_DEBUG_VERSION') ? "style='visibility:hidden;'" : "";?> title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
    <input type="text" id="ns1path" name="ns1path" value="<?php echo htmlspecialchars($c->v('ns1path'));?>" style="width:300px" />
    <?php echo $c->e('ns1path');?>
    </div>
    </div>
    <?php }?>

<div class="fieldgrp">
<label for="sys_ip"><?php echo $c->t('c_label_ip');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="sys_ip" name="sys_ip" value="<?php echo htmlspecialchars($c->v('sys_ip'));?>" style="width:100px" />
<?php echo $c->e('sys_ip');?>
</div>
<div class="info"><?php echo $c->t('c_help_autodetect');?></div>
</div>
</fieldset>

<fieldset><legend><span><?php echo $c->t('c_legend_db');?></span></legend>
<div class="fieldgrp">
<label for="db_host"><?php echo $c->t('c_label_dbhost');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="db_host" name="db_host" value="<?php echo htmlspecialchars($c->v('db_host'));?>" style="width: 140px;" />
<input type="text" id="db_port" name="db_port" value="<?php echo htmlspecialchars($c->v('db_port'));?>" style="width:  50px;" />
<?php echo $c->e('db_host');?>
</div>
</div>

<div class="fieldgrp">
<label for="db_user"><?php echo $c->t('c_label_dbuser');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="db_user" name="db_user" value="<?php echo htmlspecialchars($c->v('db_user'));?>" />
<?php echo $c->e('db_user');?>
</div>
</div>

<div class="fieldgrp">
<label for="db_pass"><?php echo $c->t('c_label_dbpass');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="password" id="db_pass" name="db_pass" value="<?php echo htmlspecialchars($c->v('db_pass'));?>" />
<?php echo $c->e('db_pass');?>
</div>
</div>

<div class="fieldgrp">
<label for="db_name"><?php echo $c->t('c_label_dbname');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="db_name" name="db_name" value="<?php echo htmlspecialchars($c->v('db_name'));?>" />
<?php echo $c->e('db_name');?>
</div>
</div>

<div class="fieldgrp">
<label for="db_prefix" style="width: 160px;"><?php echo $c->t('c_label_dbprefix');?></label>
<div class="field">
<input type="text" id="db_prefix" name="db_prefix" value="<?php echo htmlspecialchars($c->v('db_prefix'));?>" />
<?php echo $c->e('db_prefix');?>
</div>
</div>
</fieldset>

<fieldset><legend><span><?php echo $c->t('c_legend_time');?></span></legend>
<div class="fieldgrp">
<label for="date_format"><?php echo $c->t('c_label_dateformat');?></label>
<div class="field">
<acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
<input type="text" id="date_format" name="date_format" value="<?php echo htmlspecialchars($c->v('date_format'));?>" />
<?php echo $c->e('date_format');?>
</div>
<div class="help"><?php echo $c->t('c_help_dateformat');?></div>
</div>
</fieldset>
<?php if(!isset($_SESSION['is_upgrade'])){ ?>
<fieldset><legend><span><?php echo $c->t('c_legend_demodata');?></span></legend>
<div class="fieldgrp">
<label for="demodata"><?php echo $c->t('c_label_demodata');?></label>
<input type="checkbox" name="demodata" id="demodata"  style="width:16px;">
<div class="help"><?php echo $c->t('c_help_demodata');?></div>
</div>
</fieldset>
<?php } ?>
<button type="submit" class="next"><?php echo $c->t('global_next',FALSE);?></button>
</form>
</div>
<hr />
<div id="copi"><a style="float:left;" href="<?php echo $iConfig['support_site'];?>"><?php echo $c->t('copyright').date(" 2002 - Y ").$c->t('global_copy');?></a><a style="float:right;" href="http://www.primadg.com"><?php echo $_SESSION['ns_version']?></a></div>
</div>
</body>
</html>
