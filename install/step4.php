<?php if(!defined('cInstallReady')) define('cInstallReady', true);
/*
+-------------------------------------------------------------------------------------------------------------------
| STEP 4 - Admin name, e-mail, pass and crypt cookie key settings
+-------------------------------------------------------------------------------------------------------------------
| cInstall is a installation system developed by Conkurent LLC
| (C) 2008 Conkurent LLC.
*/

include_once('inc.config.php');
include_once('ktest.class.php');

$c = new kTest;

//step_sequence_management
$c->step_sequence(4);
//end_of_step_sequence_management

if (isset($_GET['lang'])) $sys_lang = $_GET['lang'];                                                                // Requested language from prev. step ...
else $sys_lang = $c->languageDetect();                                                                              // ... or check for supported languages from HTTP_ACCEPT_LANGUAGE

$aLanguage  = $c->init($sys_lang);                                                                                  // Load language array with selected language (if exist)

/*   Validation     ***********************************************************************************************/

$aErrors    = array();                                                                                              // 'sys_title' => 'e_not_empty', 'sys_url' => 'e_not_empty'
$aSecurity  = array();

if (!empty($_POST))
{
  if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_]{4,31}$/',trim($_POST['admin_name']))) $aErrors['admin_name'] = 'e_login';                           // if name empty
  if (!preg_match("/^((([0-9a-z\-\_]+)\.)*)(([0-9a-z\-\_])+)@((([0-9a-z\-\_]+)\.)+)(([0-9a-z\-\_])+)$/i", $_POST['admin_mail']))
    $aErrors['admin_mail'] = 'e_email_fail';                                                                        // if invalid e-mail
    
}

if (isset($_POST['admin_name']) && empty($aErrors))
{
    $aSecurity['admin_name'] = trim($_POST['admin_name']);
    $aSecurity['admin_mail'] = trim($_POST['admin_mail']);
    $aSecurity['admin_pass'] = $c->generatePass($iConfig['user_security_pass']);                                    // Generate Super-Admin pass
    $aSecurity['crypt_key']  = $c->generatePass($iConfig['user_security_key']);                                     // Generate Crypt cookie key ...very strong

    session_start();
    $_SESSION['security_settings'] = $aSecurity;
    //step_sequence_management
    $_SESSION['step'] = 4;
    //end_of_step_sequence_management
    header('Location: step5.php?lang='. $sys_lang);
    exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

    <title><?php echo $c->t('d_title',FALSE);?></title>

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
                <h1 class="p"><?php echo $c->t('d_title');?></h1>
            </td>
        </tr>
    </table>
    <div id="stylized" class="myform" style="width:580px; padding:5px;">
      <form name="system_form" id="system_form" action="" method="post">
        <input type="hidden" name="language" value="<?php echo $sys_lang;?>" />
        <fieldset><legend><span><?php echo $c->t('d_legend_admin');?></span></legend>
           <div class="fieldgrp">
              <label for="admin_name"><?php echo $c->t('d_label_name');?></label>
              <div class="field">
                <acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
                <input type="text" id="admin_name" name="admin_name" value="<?php echo htmlspecialchars($c->v('admin_name'));?>" />
                <?php echo $c->e('admin_name');?>
              </div>
           </div>

           <div class="fieldgrp">
              <label for="admin_mail"><?php echo $c->t('d_label_mail');?></label>
              <div class="field">
                 <acronym title="<?php echo $c->t('global_required',FALSE);?>" class="required">*</acronym>
                 <input type="text" id="admin_mail" name="admin_mail" value="<?php echo htmlspecialchars($c->v('admin_mail'));?>" />
                 <?php echo $c->e('admin_mail');?>
              </div>
           </div>
        </fieldset>
        <button type="submit" class="next"><?php echo $c->t('global_next',FALSE);?></button>
    </form>
    </div>
    <hr />
    <div id="copi"><a style="float:left;" href="<?php echo $iConfig['support_site'];?>"><?php echo $c->t('copyright').date(" 2002 - Y ").$c->t('global_copy');?></a><a style="float:right;" href="http://www.primadg.com"><?php echo $_SESSION['ns_version']?></a></div>
</div>
</body>
</html>
