<?php
/**
 * Prima Members
 *
 * The next generation web site protection system
 *
 * @package     Install
 * @author      Prima DG Team
 * @copyright   Copyright (c) 2012, Prima DG Ltd.
 * @link        http://primadg.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------


if(!defined('cInstallReady')) define('cInstallReady', true);
/*
+-------------------------------------------------------------------------------------------------------------------
| STEP 1 - Welcome message and Precheck
+-------------------------------------------------------------------------------------------------------------------
| cInstall is a installation system developed by Prima DG
| (C) 2012 Prima DG.
*/
include_once('inc.config.php');
include_once('ktest.class.php');

session_start();
$sp = substr(dirname($_SERVER['SCRIPT_FILENAME']),0, -7);                                                                        // get path without "install"
if(file_exists($sp.'system/application/models/main_version.php'))
{
    include_once($sp.'system/application/models/main_version.php');
}
if(file_exists($sp.'system/application/models/version.php'))
{
    include_once($sp.'system/application/models/version.php');
}
$subversion=" enterpise";
if(defined('NS_PRO_VERSION')){$subversion=" pro";}
if(defined('NS_BASIC_VERSION')){$subversion=" basic";}
if(defined('NS_DEBUG_VERSION')){$subversion=" debug".$subversion;}
if(defined('NS_HOSTED_MODULE')){$subversion=$subversion." hosted";}
if(defined('NS_DEMO_VERSION')){$subversion=$subversion." demo";}
if(defined('NS_UPGRADE')){$subversion=$subversion." upgrade";}       
if(!defined('NEEDSECURE_SUBVERSION'))
{
    define('NEEDSECURE_SUBVERSION', $subversion);
}
$_SESSION['ns_version']="Prima Members ".(defined("NEEDSECURE_VERSION") ? NEEDSECURE_VERSION . (defined("NEEDSECURE_SUBVERSION") ? NEEDSECURE_SUBVERSION : "") : "Unregistered!");

$c          = new kTest;
$aHTDetect  = array();
if (isset($_REQUEST['modules'])) $aHTDetect = explode("|", $_REQUEST['modules']);

if (isset($_POST['language']))
{
    $sys_lang = $_POST['language'];                                                     // If user change language ...
}
else if(isset($_GET['lang']))
{
    $sys_lang = $_GET['lang'];
}
else
{
    $sys_lang = $c->languageDetect();
}                                                                              // ... or check for supported languages from HTTP_ACCEPT_LANGUAGE

$aLanguage  = $c->init($sys_lang);                                                                                  // Load language array with selected language (if exist)


$aPHPval    = array_keys($iConfig['php_modules']);
$aPHPOptval    = array_keys($iConfig['php_modules_optional']);
$aPaths     = array_keys($iConfig['chmod_paths']);

$dataMods   = $c->parseApacheModules($iConfig['server_modules'], $aHTDetect);
$dataModsOpt   = $c->parseApacheModules($iConfig['server_modules_optional'], $aHTDetect);
$dataPHP    = $c->parsePHPConfig($aPHPval);
$dataPHPOpt    = $c->parsePHPConfig($aPHPOptval);
$dataPaths  = $c->setCHMOD($iConfig['chmod_paths']);

$error_mods = 0;
$warning_mods = 0;
$error_php  = 0;
$warning_php  = 0;
$error_path = 0;

$table_mods = '
<table border="0" cellpadding="3" width="600" class="e_report">
    <tbody>
        <tr class="h"><th width="400">'. $c->t('report_mod_server') .'</th><th>'. $c->t('report_module_local') .'</th><th>'. $c->t('report_module_desired') .'</th></tr>';
foreach ($dataMods as $k => $v)
{
    $td_class = 'error';
    if ($v)
    {
        $td_class = '';
    }
    if ( ! empty($td_class))
    {
$table_mods.= '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'" title="'. $dataMods[$k] .'">'. $c->t('report_module_fail') .'</td><td class="'. $td_class .'">'. $c->t('report_module_ok') .'</td></tr>';
        $error_mods++;
    }
}
foreach ($dataModsOpt as $k => $v)
{
    $td_class = 'warningmsg';
    if ($v)
    {
        $td_class = '';
    }
    if ( ! empty($td_class))
    {
$table_mods.= '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'" title="'. $dataModsOpt[$k] .'">'. $c->t('report_module_fail') .'</td><td class="'. $td_class .'">'. $c->t('report_module_ok') .'</td></tr>';
$table_mods.= '  <tr><td colspan="3" class="wt">'.$c->t($k.'_optional').'</td></td></tr>'; 

        $warning_mods++;
    }
}

$is_cgi = strtolower($c->parsePHPConfig('server_api'));
$aCGI   = array('cgi'); 
if (in_array($is_cgi, $aCGI))
{
    $table_mods.= '  <tr><td class="e">php_integration_type</td><td class="warningmsg">CGI</td><td class="warningmsg">Module PHP</td></tr>';
    $table_mods.= '  <tr><td colspan="3" class="wt">'.$c->t('w_cgi_module').'</td></td></tr>'; 
    $warning_mods++;
            //echo '<h1 class="warning">'. $c->t('w_cgi_module') .'</h1>';
}

$table_mods.='</tbody></table>';


$table_php = '
<table border="0" cellpadding="3" width="600" class="e_report">
    <tbody>
        <tr class="h"><th width="400">'. $c->t('report_module') .'</th><th>'. $c->t('report_module_local') .'</th><th>'. $c->t('report_module_desired') .'</th></tr>';
foreach ($iConfig['php_modules'] as $k => $v)
{
    $td_class = 'error';
    $val=isset($dataPHP[$k]) ? $dataPHP[$k]['value'] : $c->t('report_browser_local');
            
    if (isset($dataPHP[$k]) && $dataPHP[$k]['value'] == $v)
    {
        $td_class = '';
    }
    if ($k == 'php_version' && version_compare($dataPHP[$k]['value'], $v, ">="))
    {
        $td_class = '';
    }
    if (! empty($td_class))
    {
$table_php.= '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $val.'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
        $error_php++;
    }
}

foreach ($iConfig['php_modules_optional'] as $k => $v)
{
    $td_class = 'warningmsg';
    $val=isset($dataPHPOpt[$k]) ? $dataPHPOpt[$k]['value'] : $c->t('report_browser_local');
            
    if (isset($dataPHPOpt[$k]) && $dataPHPOpt[$k]['value'] == $v)
    {
        $td_class = '';
    }
    if (! empty($td_class))
    {
$table_php.= '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $val.'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
$table_php.= '  <tr><td colspan="3" class="wt">'.$c->t($k.'_optional').'</td></td></tr>'; 
        $warning_php++;
    }
}

$table_php.='</tbody></table>';


$table_path = '
<table border="0" cellpadding="3" width="600" class="e_report">
    <tbody>
        <tr class="h"><th width="400">'. $c->t('report_path') .'</th><th>'. $c->t('report_module_local') .'</th><th>'. $c->t('report_module_desired') .'</th></tr>';
foreach ($iConfig['chmod_paths'] as $k => $v)
{
    $td_class = '';
    if ( ! empty($dataPaths[$k]['status'])) $td_class = 'error';
    if ( ! empty($td_class))
    {
$table_path.= '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'" title="'. $dataPaths[$k]['status'] .'">'. $dataPaths[$k]['mode'] .'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
        $error_path++;
    }
}
$table_path.='</tbody></table>';

//step_sequence_management
if(($error_mods + $error_php + $error_path) == 0)
{
    $_SESSION['step'] = 1;
}
//end_of_step_sequence_management
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

    <title><?php echo $c->t('a_title',FALSE);?></title>

    <link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />

    <link type="text/css" href="css/main.css" rel="stylesheet" media="screen" />
    <link type="text/css" href="css/form.css" rel="stylesheet" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script>
    $(document).ready(function(){
        $("#lang_submit").css("display","none");
        $("#language").change(function(){
            $("#lang_switch").submit();
        });
        var cookie = document.cookie.toUpperCase();
        if(cookie.indexOf("PHPSESSID")<0)
        {
            $('.browser_cookie').show();
        } 
        else
        {
            $('.browser_test').hide();
            $('.next').show();
        }
    });
    </script>
</head>
<body>
<div class="center">
    <table border="0" cellpadding="3" width="600">
        <tr class="h" border="0"><td border="0"><a href="http://www.primadg.com"><img border="0" src="../img/logo.png" alt="Logo" /></a><h1 class="p"><?php echo $c->t('a_title');?></h1></td></tr>
        <tr class="nav"><td><?php echo $c->languageSelect($sys_lang);?></td></tr>
        <tr><td></td></tr>
        <tr><td class="w"><?php $c->include_lang_file('_welcome.php',$sys_lang);?></td></tr>
    </table>
<?php
    if($error_mods + $warning_mods + $error_php + $warning_php + $error_path > 0)
    {
        echo '<h1>'. $c->t('e_precheck') .'</h1>';
        if (($error_mods+$warning_mods) > 0) echo '<h2>'. $c->t('report_mod_server') .'</h2>'. $table_mods;
        if (($error_php+$warning_php) > 0) echo '<h2>'. $c->t('report_mod_php') .'</h2>'. $table_php;
        if ($error_path > 0) echo '<h2>'. $c->t('report_paths') .'</h2>'. $table_path;
    }
    
    if(($error_mods + $error_php + $error_path) == 0)
    {
        echo '<h1 class="browser_test">'. $c->t('e_precheck') .'</h1>';
    }
    
    ?>
    <h2 class="browser_test"><?php echo $c->t('report_browser');?></h2>
    <table border="0" cellpadding="3" width="600" class="e_report browser_test">
    <tbody>
    <tr class="h">
    <th width="400"><?php echo $c->t('report_browser_test');?></th>
    <th><?php echo $c->t('report_module_local');?></th>
    <th><?php echo $c->t('report_module_desired');?></th>
    </tr>
    <noscript>
    <tr>
    <td class="e">JavaScript</td>
    <td class='error'><?php echo $c->t('report_browser_local');?></td>
    <td class='error'><?php echo $c->t('report_browser_desired');?></td>
    </tr>
    </noscript>
    <tr class="browser_cookie" style="display:none;">
    <td class="e">Cookie</td>
    <td class='error'><?php echo $c->t('report_browser_local');?></td>
    <td class='error'><?php echo $c->t('report_browser_desired');?></td>
    </tr>
    </tbody></table>
    <?php
    echo "<br/>";
    if(($warning_mods + $warning_php) > 0)
    {
        echo '<table width="600" class="e_report"><tr><td width="20" class="warningmsg"></td><td> - '. sprintf($c->t('report_warning_happens'), $iConfig['support_mail']) .'</td></tr></table>';
    }
    
    if(($error_mods + $error_php + $error_path) > 0)
    {
        echo '<table width="600" class="e_report"><tr><td width="20" class="error"></td><td> - '. sprintf($c->t('report_error_happens'), $iConfig['support_mail']) .'</td></tr></table>';
        echo '<table border="0" cellpadding="3" width="600"><tr><td align="right"><button disabled>'. $c->t('global_next', FALSE) . '</button></td></tr></table>';
    }
    else
    {
        echo '<table width="600" class="browser_test e_report"><tr><td width="20" class="error"></td><td> - '. sprintf($c->t('report_error_happens'), $iConfig['support_mail']) .'</td></tr></table>';
        echo '<table class="browser_test" border="0" cellpadding="3" width="600"><tr><td align="right"><button disabled>'. $c->t('global_next', FALSE) . '</button></td></tr></table>';
        
        echo '<table class="next" style="display:none;" border="0" cellpadding="3" width="600"><tr><td align="right"><form action="step2.php?lang='. $sys_lang .'" method="post"><button type="submit">'. $c->t('global_next', FALSE) .'</button></form></td></tr></table>';
    }
?>
    <br />
    <hr />
    <div id="copi"><a style="float:left;" href="<?php echo $iConfig['support_site'];?>"><?php echo $c->t('copyright').date(" 2002 - Y ").$c->t('global_copy');?></a><a style="float:right;" href="http://www.primadg.com"><?php echo $_SESSION['ns_version']?></a></div>
</div>
</body>
</html>
