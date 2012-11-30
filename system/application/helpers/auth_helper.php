<?php
/**
 * 
 * THIS FILE CONTAINS AUTH FUNCTIONS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
//added by val petruchek
/**
 * "access_key"
 *
 */
Define("PROTECTION_COOKIE_NAME","access_key");

/**
* Generates cookie hash used by mod_rewrite_cookies protection model. function applies strtolower() to $username to avoid case sensitivity problems
*
* @param string $username
* @return string 32-byte hash built from username and config parameter 'crypt_cookie_key'
*
* 
*
* @author Val Petruchek
* @copyright 2008
*/

function build_cookie_hash($username)
{
    return md5(strtolower($username).config_get('user', 'security', 'crypt_cookie_key'));
}


/**
* Check if user is authorized.
* If not, redirect to login page with current URL as param
*
* @author Drovorubov
* finished Makarenko Sergey @ 08.10.2008 15:35:20
* @param string $return_url
*
*/
function check_user_auth($return_url="")
{
    $CI =& get_instance();
    $CI->load->model('user_auth_model');

    if (!$return_url)
    {//by default we are redirecting user to current page
        $return_url = site_url($CI->uri->uri_string());
    }

    if( !$CI->user_auth_model->is_auth() ) // user is not authorized
    {
        // user is not authorized - redirect to Login page
        redirect('user/login/1/'.encode_url($return_url));
        die;
    }
    else                                   // user is authorized
    {
        // we should check if the user is 'activated', 'approved', 'not expired', 'not suspended'
        $status = $CI->user_auth_model->get_login_status($CI->user_auth_model->uid);
        if (isset($status['status_error']))
        {
            $CI->user_auth_model->logout();
            // user has no access permission or it was changed - redirect to Login page
            redirect('user/login/1/'.encode_url($return_url));
            die;
        }
    }

}

/**
 * Get admins emails that depends from $ML
 *
 * @param integer $ML
 * @return array
 */
function getEmailsForAdminsML($ML)
{
    $CI =& get_instance();
    $admin_id = $CI->admin_auth_model->uid;
    $CI->db->select('admins.email');
    $CI->db->from(db_prefix.'Admins admins');
    $CI->db->where('access_levels.ML&'.$ML,$ML);
    $CI->db->join(db_prefix.'Access_levels access_levels','admins.access_id=access_levels.id','left');
    $CI->db->distinct();
    $query = $CI->db->get();
    $admins=$query->result_array();
    $result=array();
    foreach($admins as $key=>$value)
    {
        if($value['email']!='')
        $result[]=$value['email'];
    }
    return $result;
}


/**
* Writes log to Admin_logs table
*
* @param string $action performed; language variable <{admin_log_$action}> must exist
* @param array $details details of the action
* @param integer id of the admin who performed the action:
*      >0 for real admin
*       0 reserved, do not use
*      -1 for filesystem errors (protection_model)
* @return boolean success or not
*
* @author Val Petruchek
* @copyright 2008
*/
function admin_log($action, $details = null, $admin_id = false)
{
    //modified by Sergey Makarenko
    //if "Log administrators" == 0
    if ( !intval(config_get('SYSTEM', 'CONFIG', 'LOG_ADMINS')) )
    {
        //if "Log administrators" == 0 then exit
        return true;
    }
    //_modified by Sergey Makarenko

    if (!$action)
    {
        return false;
    }

    $CI = &get_instance();

    $CI->db->set('action', $action);
    $CI->db->set('time', "CURRENT_TIMESTAMP()", false); //do not escape - we need to use timeshift here
    $CI->db->set('ip', $CI->input->server("REMOTE_ADDR"));
    if ($admin_id === false)
    {//if no admin_id passed, trying to take current admin_id if it is set
        $admin_id = (isset($CI->admin_id)) ? $CI->admin_id : 0;
    }
    $CI->db->set('admin_id', intval($admin_id));
    if ($details)
    {
        $CI->db->set('details', serialize($details));
    }
    return (bool) $CI->db->insert(db_prefix.'Admin_logs');;
}

/**
 * Writes log to Admin_logs table with details
 *
 * @param string $log_action
 * @param boolean $enity_id
 * @param boolean $is_error
 * @param boolean $errors
 * @param integer $admin_id
 */
function simple_admin_log($log_action,$enity_id=false,$is_error=false,$errors=false,$admin_id = false)
{
    $details=array();
    if($enity_id!==false && $enity_id!="")
    {
        $details["Enity_id"]=$enity_id;
    }
    if($is_error && $errors!==false)
    {
        $details["Completed"]="false";
        $details["Exception"]=is_array($errors) ? "Errors (".implode(',',$errors).")" : $errors;
    }
    else
    {
        $details["Completed"]="true";
    }
    admin_log($log_action,$details,$admin_id);
}


/**
* It's just wrapper for protection_model->event() method - check there for details
*
* @param unknown $type
* @param unknown $users
* @param unknown $subscriptions
* @param unknown $products
* @param unknown $directories
* @return boolean success or not
*
* @author Val Petruchek
* @copyright 2008
*/
function protection_event($type, $users=false, $subscriptions=false, $products=false, $directories=false)
{
    $CI = &get_instance();
/**
 * For PRODUCT_PROTECT
 */
    $CI->load->model("protection_model");
    $CI->protection_model->event($type, $users, $subscriptions, $products, $directories);

/**
 * For PRODUCT_HOSTED
 */
    //***********Functionality limitations***********
    if(Functionality_enabled('admin_product_hosted')===true)
    {
		 $CI->load->model("host_manager_model");
		 if(false !== $CI->host_manager_model->event($type, $users, $subscriptions, $products, $directories))
		 {

			 $CI->load->model("domain_manager_model");
			 $CI->domain_manager_model->event($type, $users, $subscriptions, $products, $directories);
		 	
		 }
	}
}

/**
* This function is compute submenu items count.
*
* @param array $items - items for test
* @param array $count - initially count
* @return integer
*
* @author onagr
* @copyright 2008
*/
function Submenu_items_count($items,$count)
{
    foreach($items as $item)
    {
        $count-=Functionality_enabled($item)===true ? 0 : 1;
    }
    return $count;
}

/**
* This function is wrapper for Functionality_enabled function.
* Function works with $access_bit names.
*
* @param unknown $object_type
* @param unknown $object_id
* @return mixed
*
* @author Makarenko Sergey
* @copyright 2008
*/
function Functionality_enabled_by_object_type($object_type,$object_id=false)
{
    $object_id=intval($object_id)>0 ? intval($object_id) : false;
    $object_type=intval($object_type);
    //translation matrix for converting $access_bit into $functionality_key
    $function_key = Array(
    2=>'admin_config_emails_modify',
    3=>'admin_product_groups_modify',
    4=>'admin_products_modify',    
    5=>'admin_coupon_modify',
    6=>'admin_config_news_modify',
    8=>'admin_section_newsletter',
    9=>'admin_config_pages_modify',
    10=>'admin_member_suspend_reason_modify',
    11=>'admin_config_add_fields_modify',
    12=>'admin_config_ban_ip_modify',
    13=>'admin_config_emails_modify',
    15=>'admin_config_member_group_modify',
    14=>'admin_section_newsletter');
    
    if (array_key_exists($object_type, $function_key))
    {
        return Functionality_enabled($function_key[$object_type],$object_id);
    }
    return true;
}

/**
* This function is wrapper for Functionality_enabled function.
* Function works with $access_bit names.
*
* @param integer $access_bit - name of the access_bit to check
* @return bool
*
* @author Makarenko Sergey
* @copyright 2008
*/
function Functionality_enabled_by_access_bit($access_bit)
{
    //translation matrix for converting $access_bit into $functionality_key
    $function_key = Array(
    PRODUCT => 'admin_section_product',
    MEMBER_CONTROL => 'admin_section_member_control',
    TRANSACTION => 'admin_section_statistics',
    NEWSLETTER => 'admin_section_newsletter',
    COUPON => 'admin_section_coupon',
    SYSTEM_CONFIGURATION => 'section_system_config',
    ADMINISTRATOR_CONTROL => 'admin_section_admin_control',
    ACTIVITY_LOGGING => 'admin_section_logging'
    );
    if (array_key_exists($access_bit, $function_key))
    {
        return Functionality_enabled($function_key[$access_bit]);
    }
    return true;
}

if(!defined("DEMO_PROTECT_ROOT"))
{
	/**
	 * Enter description here...
	 *
	 */
    define("DEMO_PROTECT_ROOT","dir_for_protect");
}

/**
* This function is used everywhere, where the functionality should be enabled/disabled.
*
* Function returns whether we should limit some functionality or not.
* FALSE is returned - this functionality is DISABLED.
* TRUE is returned - this functionality is ENABLED.
* STRING is returned - this functionality is DISABLED, with STRING error.
* ARRAY(string,string) - this functionality is DISABLED, with ARRAY of STRING errors
*
* @param string $functionality_key - the key name of functionality to check access
* @param integer $object_id - optional param to check if action is enabled to a certain object ID
* @return mixed bool/string/array
*
* @author Makarenko Sergey
* @copyright 2008
*/
function Functionality_enabled($functionality_key, $object_id=false)
{
    $demo_error='<{demo_msg_er_functionality_disabled}>';
    
    //load model, because there is defined 'NS_DEMO_VERSION' constant
    $CI = &get_instance();
    $CI->load->model("auth_model");

    //we work with case insensitive key names
    $functionality_key = strtolower($functionality_key);
    // if NS_DEMO_VERSION then $demo=TRUE
    $debug = defined('DEBUG_RESPONSE_FLAG');
    $demo = defined('NS_DEMO_VERSION');
    $pro = defined('NS_PRO_VERSION');
    $basic = defined('NS_BASIC_VERSION');
    $hosted = defined('NS_HOSTED_MODULE');
    $domain = $hosted;
    $protected = defined('NS_PROTECTED_MODULE');

    switch ($functionality_key)
    {
        //cases by SECTION NAMES
        //this enables/disables the WHOLE admin/user SECTION with menu items and subsections
    case 'admin_section_product':           //admin Product section
        return true;                        //enabled
        break;
    case 'admin_section_member_control':    //admin Member Control section
        return true;                        //enabled
        break;
    case 'admin_section_statistics':        //admin Statistics section
        return true;                        //enabled
        break;
    case 'admin_section_newsletter':        //admin Newsletter section
        return true;                        //enabled
        break;
    case 'admin_section_coupon':            //admin Coupon section
        if ($basic)
        return false;
        return true;                        //enabled
        break;
    case 'admin_section_system_config':     //admin System Configuration section
        return true;                        //enabled
        break;
    case 'admin_section_admin_control':     //admin Administrator Control section
        if ($basic)
        return false;
        return true;                        //enabled
        break;
    case 'admin_section_logging':           //admin Activity Logging section
        return true;                        //enabled
        break;
        //_cases by SECTION NAMES

        // ---------------------------------------------------------------------
        //cases by ACTIONS in SUBSECTIONS
        //this enables/disables some ACTION in admin SUBSECTIONs
    case 'admin_products_modify':
        if ($demo && value_in_range($object_id,1,5))       //DEMO: delete & edit is disabled for first 5 products
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_products_modify_paid':
        if ($basic)
        return false;                       //product paid field disabled
        return true;
        break;
    case 'admin_product_groups_modify':
        if ($demo && value_in_range($object_id,1,2))       //DEMO: delete & edit is disabled for first 2 product groups
        return $demo_error;
        return true;                        //enabled
    case 'admin_dir_protect_modify':
        if ($demo && value_in_range($object_id,1,3))       //DEMO: protection modification is disabled for first 3 directories
        return $demo_error;
        return true;                        //enabled
        break;
        case 'admin_member_group':
        return true;
        break;
    case 'admin_member_info_modify':
        if ($demo && value_in_range($object_id,1,2))       //DEMO: delete & edit & suspend is disabled for first member
        return '<{demo_user_er_functionality_disabled}>';
        return true;
        break;
    case 'admin_member_suspend_reason_modify':
        if ($demo && value_in_range($object_id,1,3))       //DEMO: delete is disabled for first 3 suspend reason
        return $demo_error;
        return true;
        break;
    case 'admin_member_email_authentication':
        if(config_get('system','config','member_email_as_login'))
        return true;
        return false;
        break;
    case 'admin_coupon_modify':
        if ($demo && value_in_range($object_id,1,1))       //DEMO: delete & edit is disabled for first coupon
        return $demo_error;
        return true;
        break;
    case 'admin_admin_account_modify':
        if ($demo && value_in_range($object_id,1,3))       //DEMO: disabled for first two admins and super_user
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_access_level_modify':
        if ($demo && value_in_range($object_id,1,3))       //DEMO: disabled for first 3 access levels
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_status_modify':
        if ($demo)                          //DEMO: disabled switch
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_security_modify':
        if ($demo)                          //DEMO: disabled saving security settings
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_global_modify':
        if ($demo)                          //DEMO: disabled saving global setup
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_global_ignor_ext':
        if ($demo)                          //DEMO: disabled file extension to be ignored
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_payment_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //enabled
        break;
     case 'admin_config_payment':           //DEMO: disabled payment system view
        if ($basic)
        return false;
        return true;
        break;
    case 'admin_config_ban_ip_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_mailer_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
  //      return false;                       //disabled
        return true;                        
        break;
    case 'admin_config_mailer_test': 
        if ($demo)                          //DEMO: disabled SMTP settings test connection
        return $demo_error;
  //      return false;                       //disabled
        return true;                        
        break;        
    case 'admin_config_hosting_modify':
        if ($demo)                          //DEMO: disabled hosting plans settings system
        return $demo_error;
  //      return false;                       //disabled
        return true;                        
        break;
    case 'admin_config_hosting_test':  
        if ($demo)                          //DEMO: disabled hosting settings test connection
        return $demo_error;
 //       return false;                       //disabled
        return true;                        
        break;         
    case 'admin_config_domain_modify':
        if ($demo)                          //DEMO: disabled hosting plans settings system
        return $demo_error;
  //      return false;                       
        return true;                        //enabled
        break;
    case 'admin_config_domain_test':  
        if ($demo)                          //DEMO: disabled hosting settings test connection
        return $demo_error;
//        return "function disabled";                       //disabled
        return true;                        
        break;        
    case 'admin_config_members_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_emails_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //disabled modifying system emails
        break;
    case 'admin_config_member_group_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //disabled modifying system emails
        break;
    case 'admin_config_add_fields_modify':
        if ($demo)                          //DEMO: disabled inactivating payment system
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_news_modify':
        if ($demo && value_in_range($object_id,1,2))       //DEMO: disabled for first 2 news 
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_pages_modify':
        if ($demo && value_in_range($object_id,1,1))       //DEMO: disabled for first page 
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_config_design':
        return true;
        break;
    case 'admin_config_page_main':
        if ($basic)
        return false;                        //disabled menage pages
		return true;
        break;
    case 'admin_config_page_main_modify':
        if ($demo)                           //DEMO: disabled modifying 
        return $demo_error;
        if ($basic)
        return false;                        //disabled modifying
		return true;
        break;
	case 'admin_product_do_special':
        if ($demo && value_in_range($object_id,1,1))       //DEMO: special disabled for first products
        return $demo_error;
		return true;
        break;
    case 'admin_config_constructor':
        return true;
        break;
    case 'admin_config_constructor_modify':
        if ($demo)                           //DEMO: disabled modifying 
        return $demo_error;
        return true;
        break;
    case 'admin_config_design_changer':
        if ($basic)
        return false;                        //disabled modifying
		if ($pro)
        return false;                        //disabled modifying
		return true;
        break;
    case 'admin_config_constructor_registration_image_code_disabling':
        if ($demo)
        return "<{admin_member_pages_err_field_disabling}>";    //disabled field disabling
		return true;
        break;
    case 'admin_config_design_changer_modify':
        if ($demo)                           //DEMO: disabled modifying 
        return $demo_error;
        return true;
        break;
    case 'admin_config_pages':
		if ($demo and $basic)
        return false;
        if ($demo)
        return $demo_error;                        //disabled menage pages
        if ($basic)
        return false;
        return true;
        break;
    case 'admin_config_pages_tos_modify':
        return '<{admin_manage_pages_msg_er_tos}>';//disable deleting tos page and modifing tos link 
        break;    
    case 'admin_config_languages_modify':
        if ($demo && value_in_range($object_id,1,1))       //DEMO: disabled for first laguage 
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_dir_protect_limit':
        if ($demo)
        return $demo_error;                  //only dir_for_protect directory view
        return true;
        break;
    case 'admin_multi_language':
        if ($basic)
        return false;
        if ($pro)
        return false;                        //multilanguage disabled
        return true;
        break;
    case 'admin_newsletter_template':
        if ($basic)
        return false;                       //newsletter template submenu disabled
        return true;
        break;
    case 'admin_newsletter_send':
        if ($basic)
        return false;                       //newsletter send submenu disabled
        return true;
        break;
    case 'admin_statistics_graphs':
        if ($basic)
        return false;                       //statistics graphs submenu disabled
        return true;
        break;
    case 'admin_statistics_total':
        if ($basic)
        return false;                       //statistics total submenu disabled
        return true;
        break;
    case 'admin_server_info':
        if ($demo)
        return false;                       //main page server info disabled
        return true;
        break;  
    case 'admin_developers_notification':
        return true;
        break;
    
    case 'member_registered_menu_profile_billing':
    if (intval(config_get('system','config','member_force_billing_info_input')))
    return true;                       //enabled if member_force_billing_info_input chtcked
    return false;
    break;    
    case 'member_registered_menu_profile_domain':
    if($domain)
    return true;                       //enabled in hosting version
    return false;
    break;   
    ///////////////////////simple_user_menu//////////////////////////////  
    case 'admin_config_member_simple_menu':
    return true;                       //unregistered member menu disable
    break;           
case 'member_unregistered_menu_site_info':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //unregistered member menu disable
    return true;
    break;   
case 'member_unregistered_menu_additional':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //unregistered member menu disable
    return true;
    break;  
case 'member_registered_menu_site_info':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //unregistered member menu disable
    return true;
    break;   
case 'member_registered_menu_additional':
    if (config_get('system','config','member_simple_menu'))
    return false;                      //unregistered member menu disable
    return true;
    break;   
case 'member_registered_menu_active_products':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //unregistered member menu disable
    return true;
    break; 
case 'member_registered_link_home':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //unregistered member link home disabled
    return true;
    break;
case 'member_registered_redirect_info':
    if (config_get('system','config','member_simple_menu'))
    return false;                       //registered member redirect to info disabled
    return true;
    break;
        return true;
        break;
    ////////////////////end_of_simple_user_menu//////////////////////////    
    
        
        //_cases by ACTIONS in SUBSECTIONS

        // -------------------------
        //cases by SYSTEM ACTIONS
    case 'action_newsletter_send':     //physically send newsletter emails
        if ($demo)
        return false;                   //DEMO: disabled
        return true;                        //enabled
        break;
        //_cases by SYSTEM ACTIONS

        // -------------------------
        //cases by PROTECTED module
    case 'admin_product_protected':     //
        if (!$protected)
            return false;
        return true;                        //enabled PROTECTED PRODUCT
        break;
        //cases by HOSTED module
    case 'admin_product_hosted':     //
        if (!$hosted)
            return false;
        return true;                        //enabled HOSTED PRODUCT
        break;
    case 'admin_product_domain':     //
        if (!$domain)
            return false;
        return true;                        //enabled DOMAIN PRODUCT
        break;
    case 'admin_domain_settings':     //
        if (!$domain)
            return false;
        return true;                        //enabled HOSTED PRODUCT
        break;        
    case 'user_auth_product_hosted':     //
        if (!$hosted)
            return false;
        return true;                        //enabled HOSTED PRODUCT
        break;
    case 'admin_products_hosted_modify':
        if ($demo && value_in_range($object_id,1,5))       //TODO: DELETE(not needed)!!!!  DEMO: delete & edit is disabled for first 5 products
        return $demo_error;
        return true;                        //enabled
        break;
    case 'admin_products_hosted_limit':
        if ($demo)
        return $demo_error;                  //only dir_for_protect directory view
        return true;
        break;
        //_cases by HOSTED version
        
    
    } //_switch ($functionality_key)

    //if no key name of functionality found - return response 'ENABLED'
    return true;
} //_function Functionality_enabled

//check including value in range
/**
 * check including value in range
 *
 * @param unknown_type $value
 * @param unknown_type $min
 * @param unknown_type $max
 * @return boolean
 */
function value_in_range($value,$min,$max=false)
{
    if($min!==false && $value<$min){return false;}
    if($max!==false && $value>$max){return false;}
    return true;
}

?>
