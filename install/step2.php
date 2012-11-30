<?php
if(!defined('cInstallReady')) define('cInstallReady', true);
/*
+-------------------------------------------------------------------------------------------------------------------
| STEP 2 - ToS and agreements
+-------------------------------------------------------------------------------------------------------------------
| cInstall is a installation system developed by Prima DG
| (C) 2008 Prima DG.
*/

include_once('inc.config.php');
include_once('ktest.class.php');

$c = new kTest;

//step_sequence_management
$c->step_sequence(2);
//end_of_step_sequence_management

if (isset($_GET['lang'])) $sys_lang = $_GET['lang'];                                                                // Requested language from prev. step ...
else $sys_lang = $c->languageDetect();                                                                              // ... or check for supported languages from HTTP_ACCEPT_LANGUAGE

if (isset($_POST['agree']))
{

    //step_sequence_management
    $_SESSION['step'] = 2;
    //end_of_step_sequence_management
    header('Location: step3.php?lang='. $sys_lang);
    exit;
}

$aLanguage  = $c->init($sys_lang);                                                                                  // Load language array with selected language (if exist)

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

    <title><?php echo $c->t('b_title',FALSE);?></title>

    <link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />

    <link type="text/css" href="css/main.css" rel="stylesheet" media="screen" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $("#next").attr("disabled","disabled");
            $("input#agree").bind("click", function(e){
                ($(this).attr("checked")) ? $("#next").removeAttr("disabled") : $("#next").attr("disabled","disabled");
            });
        });
    </script>
</head>
<body>
<div class="center">
    <table border="0" cellpadding="3" width="600">
        <tr class="h" border="0">
            <td border="0">
                <a href="http://www.primadg.com"><img border="0" src="../img/logo.png" alt="Logo" /></a>
                <h1 class="p"><?php echo $c->t('b_title');?></h1>
            </td>
        </tr>
        <tr>
            <td>
            </td>
        </tr>
        <tr>
            <td class="w">
<?php
        $c->include_lang_file('_tos.php',$sys_lang);   
?>
            </td>
        </tr>
        <tr>
            <td align="right">
                <form name="form_b2" id="form_b2" action="" method="post">
                    <input type="checkbox" name="agree" id="agree" value="yes"><label for="agree"><?php echo $c->t('b_label_agree');?></label>
                    <button id="next" type="submit"><?php echo $c->t('global_next', FALSE);?></button>
                </form>
            </td>
        </tr>
    </table>
    <br />
    <hr />
    <div id="copi"><a style="float:left;" href="<?php echo $iConfig['support_site'];?>"><?php echo $c->t('copyright').date(" 2002 - Y ").$c->t('global_copy');?></a><a style="float:right;" href="http://www.primadg.com"><?php echo $_SESSION['ns_version']?></a></div>
</div>
</body>
</html>
