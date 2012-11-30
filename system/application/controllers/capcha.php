<?php
/**
 * 
 * THIS FILE CONTAINS Capcha CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file user_controller.php
 */
require_once("user_controller.php");
/**
 * 
 * THIS CLASS ...
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Capcha extends User_Controller {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Capcha()
    {
    	parent::User_Controller();	
    }

	/**
	 * Enter description here...
	 *
	 * @global uknowntype
	 */
    function index()
    {
        global $_helper_CONFIG;
    }
 	/**
 	 * Enter description here...
 	 *
 	 */
    function draw()
    {
        $min_length = config_get('SYSTEM','CAPCHA','min_length');
        $max_length = config_get('SYSTEM','CAPCHA','max_length');
    
        capcha_draw($min_length,$max_length);
    }
 
}
?>
