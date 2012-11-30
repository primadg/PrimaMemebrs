<?php if(!defined('cInstallReady')) define('cInstallReady', true);
/*
+-------------------------------------------------------------------------------------------------------------------
| STEP 5 - Create config & db
+-------------------------------------------------------------------------------------------------------------------
| cInstall is a installation system developed by Conkurent LLC
| (C) 2008 Conkurent LLC.
*/

include_once('inc.config.php');
include_once('ktest.class.php');

$c = new kTest;

//step_sequence_management
$c->step_sequence(5);
//end_of_step_sequence_management

if (isset($_GET['lang'])) $sys_lang = $_GET['lang'];                                                                // Requested language from prev. step ...
else $sys_lang = $c->languageDetect();                                                                              // ... or check for supported languages from HTTP_ACCEPT_LANGUAGE

$aLanguage  = $c->init($sys_lang);                                                                                  // Load language array with selected language (if exist)

session_start();
$update_sys_path="";
$update_source_path="";
if(isset($_SESSION['step']) && intval($_SESSION['step'])==5)
{
    $lastError = TRUE;
    $final_str  = '';
}
else
{
    $aSystemCFG = $_SESSION['system_settings'];
    $aSecurity  = $_SESSION['security_settings'];
    $update_sys_path=$aSystemCFG['sys_path'];
    $update_source_path=isset($aSystemCFG['ns1path']) ? $aSystemCFG['ns1path'] : "";
    $final_str  = '';

    $lastError = $c->writeConfig($aSystemCFG, $aSecurity);                                                              // Write config files or return Error massage
    
    $super_admin_account = $c->t('d_label_name') .' - <span class="account">'. $aSecurity['admin_name'] .'</span><br />';
    $super_admin_account.= $c->t('d_label_pass') .' - <span class="account">'. $aSecurity['admin_pass'] .'</span><br />';
    $super_admin_account.='<span class="account_comment">'.$c->t('d_account_comment').'</span>';
    
    if ($lastError === 'e_mail_send')
    {
        $final_str.= '<h1>'. $c->t('e_mail_send') .'</h1>';
        $final_str.= $super_admin_account;
        
    } elseif ($lastError !== TRUE) {
        $final_str.= $c->t('e_title') . $c->t($lastError, FALSE);
        $final_str.= '<p class="call_support">'. sprintf($c->t('global_call_support'), $iConfig['support_mail']) .'</p>';
    }
    else
    {
        $final_str.=$super_admin_account;
    }
    
    if ($lastError === TRUE || $lastError === 'e_mail_send')
    {
        $_SESSION['step']=5;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

    <title><?php echo $c->t('f_title',FALSE);?></title>

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
                <a href="http://www.primadg.com"><img border="0" src="../img/logo.png" alt="Logo" /></a>
                <h1 class="p"><?php echo $c->t('f_title');?></h1>
            </td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td class="w">

<?php
    if ($lastError === TRUE || $lastError === 'e_mail_send')
    { 
        //Show success message
        $c->include_lang_file('_success.php',$sys_lang);
        echo $final_str;        
    }   
    else
    {
        //Show error messages
        echo $final_str;
    }                                                                                           
?>

            </td>
        </tr>
     <?php if(isset($_SESSION['is_upgrade'])){?>
    <tr><td>
    <?php echo $c->t('f_upgrade_text');?>
    <form action="../upgrade/index.php" method="POST" style="text-align: center;">
    <input type="hidden" name="ns1path" value="<?php echo $update_source_path;?>"/>
    <input type="hidden" name="ns2path" value="<?php echo $update_sys_path;?>"/>
    <input type="hidden" name="exp_time" value="60"/>
    <input style="width:200px;" type="submit" value="<?php echo $c->t('f_upgrade');?>"/>
    </form>
    </td></tr>
    <?php }?>
    </table>    
    <hr />
    <div id="copi"><a style="float:left;" href="<?php echo $iConfig['support_site'];?>"><?php echo $c->t('copyright').date(" 2002 - Y ").$c->t('global_copy');?></a><a style="float:right;" href="http://www.primadg.com"><?php echo $_SESSION['ns_version']?></a></div>
</div>
</body>
</html>
