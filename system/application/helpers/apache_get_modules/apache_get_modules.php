<?php 
/**
 * 
 * THIS FILE READ INSTALL MODULES BY MEANS OF .HTACCESS, SAVE THEN TO SESSION AND RETURN A STRING LIST OF MODULES
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
if (isset($_REQUEST['modules']) && !empty($_REQUEST['modules']))
{
    if(isset($_REQUEST['autoload']))
    {
        echo $_REQUEST['modules'];
    }
    @session_start();
    $_SESSION['apache_get_modules'] = explode("|", $_REQUEST['modules']);
}
?>
