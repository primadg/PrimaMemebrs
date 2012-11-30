<?php
/**
 * 
 * THIS FILE CONTAINS CAPTCHA FUNCTIONS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Draw the capcha
 *
 * @param integer $min_length
 * @param integer $max_length
 * @return true
 */
function capcha_draw($min_length,$max_length)
{

    require_once('kcaptcha/kcaptcha.php');

    $kcaptcha = new KCAPTCHA($min_length,$max_length);
    //$_SESSION['capcha_code'] = $kcaptcha->getKeyString();
    session_set('capcha_code',$kcaptcha->getKeyString());
    return true;
}
/**
 * Check capcha code
 *
 * @param string $input
 * @return boolean
 */
function check_code($input)
{
    if( !isset($input) or empty($input) or mb_strlen($input)<1 or mb_strlen($input)>255 )
    {
        return false;
    }

    //if( strcmp($_SESSION['capcha_code'],$input)==0 )
    if( strcmp(session_get('capcha_code'),$input)==0 )
    {
        return true;
    }

    //$_SESSION['capcha_code']='';
    session_set('capcha_code','');
    return false;
}


?>
