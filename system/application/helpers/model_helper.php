<?php
/**
 * 
 * THIS FILE CONTAINS MODEL FUNCTIONS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */


/**
 * Enter description here...
 *
 * @return array
 */
function _standart_ret()
{
    return array("result"=>false,"per_page"	=>0,"total"=>0, "items"=>array());
}    
/**
 * Checks if a value exists in an array
 *
 * @param mixed $val
 * @param array $arr
 * @return mixed
 */
function _check_param_array($val, $arr)
{
    if($val && in_array($val, $arr))
    {
        return $val;
    }
    else
    {   
        reset($arr);
        return current($arr);
    }
}     

?>
