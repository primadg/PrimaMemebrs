<?php
/**
 * 
 * THIS FILE CONTAINS User_Controller CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS class extend Controller class
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class User_Controller extends Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
     function User_Controller()
    {
        parent::Controller();

        pre_config();
        
       
        $this->load->model('user_auth_model');
        $this->load->model('admin_auth_model');
        $this->load->model('user_model');
        $this->admin_auth_model->license_check('',true);
       //if ()config_set(0,'SYSTEM','STATUS','online');
        $this->default_language_id=$this->user_auth_model->get_default_language();
        $this->current_language_id=$this->user_model->get_lang(intval($this->user_auth_model->uid));
    }
    
    /**
     * Debug console method
     *
     * @return boolean
     */
    function debug_console()
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            php_test();
        }
    }
}
?>
