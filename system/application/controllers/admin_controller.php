<?php
/**
 * 
 * THIS FILE INITIALIZES THE CONFIGURATION FOR ADMIN 
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */

//defining PERMISSION CONSTANTS
$access_category_array = array(
'product'=>'',
'member_control'=>'',
'transaction'=>'',
'newsletter'=>'',
'coupon'=>'',
'system_configuration'=>'',
'administrator_control'=>'',
'activity_logging'=>''
);

$email_newsletter_array = array();

$counter=0;
foreach($access_category_array as $key => $value)
{
    $access_category_array[$key]=(0x1 << $counter);
    /**
     * DEFINES CONSTANTS
     */
    if(!define(mb_strtoupper($key), $access_category_array[$key]))
    {die(mb_strtoupper($key)." - error of defining!\n");}
    $counter++;
}
/**
 * 
 * THIS CLASS IS INITIALIZED THE CONFIGURATION FOR ADMIN 
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Admin_Controller extends Controller
{
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
	function Admin_Controller()
    {
        parent::Controller();
        //hook to load config and database
        pre_config();
        $email_newsletter_array=Get_admin_system_emails_tmpl_bits();
        foreach($email_newsletter_array as $key => $value)
        {
            if(!define(mb_strtoupper($key), $email_newsletter_array[$key]))
            {die(mb_strtoupper($key)." - error of defining!\n");}
        }

        $this->load->model("admin_auth_model");
        $this->load->model("lang_manager_model");
        $this->admin_id = $this->admin_auth_model->admin_id;
        $this->is_super_admin = $this->admin_auth_model->isSuperAdmin();
        $this->default_language_id=$this->admin_auth_model->get_default_language();
        $this->current_language_id=$this->lang_manager_model->get_current_language();
        if(!$this->admin_auth_model->is_auth())
        {
            $result['login']='true';
            make_response("authorize", create_temp_vars_set($result), 1);
            exit;
        }
        
        $this->admin_auth_model->license_check();    
        
        if((isset($this->access_bit)&&($access_error=$this->admin_auth_model->isAccessDenied($this->access_bit))!==false && !$this->_is_access_exclusion()))
        {
            $this->admin_auth_model->showAccessDenied($access_error);
            exit;
        }
    }
    /**
     * Enter description here...
     *
     * @return boolean
     */
    function _is_access_exclusion()
    {
        if(method_exists($this,'_access_rules') && $this->_access_rules())
        {
        unset($this->access_bit);
        return true;
        }
        return false;
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
