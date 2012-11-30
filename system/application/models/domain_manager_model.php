<?php

require_once ('domain_model.php');

class Domain_manager_model extends domain_model
{
    function Domain_manager_model()
    {
        parent::domain_model();
    }

    /**
     * Event handler for all domain events
     * for any parameter except $type it can accept both - integer or array of integers
     *
     * @param string $type of the event:
     *        - SUBSCRIPTION_STARTED:  $user, $subscription
     *        - SUBSCRIPTION_EXPIRED:  $subscription
     *        - DIRECTORIES_ADDED:     $product, $directories
     *        - DIRECTORIES_REMOVED:   $product, $directories
     *        + USER_SUSPENDED:        $user
     *        + USER_UNSUSPENDED:      $user
     *        + USER_DELETED:          $user
     *        + USER_UPDATED:          $user
     *        - SITE_IP_CHANGED:
     * @param array/integer $user(s) for whose event happened
     * @param integer $subscription(s) for whose event happened
     * @param array/integer $product(s) for whose event happened
     * @param array/integer $dom(s) for whose event happened
     * @param string new $site_ip
     *
     * 
     * @copyright 2009
     */
    function event($type, $users=false, $subscr_id=false, $products=false, $domain=false)
    {
fb($type,'Event Domain Products');
        if(is_array($users))
        {
            foreach($users as $key=>$value)
            {
                if(intval($value)==0)
                {
                    unset($users[$key]);
                }            
            }
        }
        
 //       admin_log("debug",array('type'=>$type, 'users'=>$users, 'subscription'=>$subscr_id, 'products'=>$products, 'directories'=>$domain));

        $CI = &get_instance();
        $CI->load->model("user_model");
        $CI->load->model("host_plans_model");

        switch($type)
        {
            case "USER_UNSUSPENDED":
            	 break;
                $domain = $CI->host_plans_model->Load_User_Host_plans($users);
                fb($domain,'USER_UNSUSPENDED');
                foreach($domain as $dom)
                {
//                    $method = $dom['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
                     	if ($CI->$method->unsuspend($dom['login']) === false)
                    	{
	                    	simple_admin_log('domain_user_unsuspended',$dom['login'],true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
                break;
            case "USER_DELETED":
                $domain = $CI->host_plans_model->Load_User_Host_plans($users);
fb($domain,'USER_DELETED');
                if (is_array($domain) && count($domain)>0)
                {
                	/**
                	 * This user have domain product
                	 */
//                    $method = $dom['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
                        if ( $CI->$method->revoke($domain[0]) === false)
                    	{
	                    	simple_admin_log('domain_user_deleted',$domain[0]['login'],true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
            	break;
            case "USER_SUSPENDED":
            	 break;
                $domain = $CI->host_plans_model->Load_User_Host_plans($users);
                fb($domain,'USER_SUSPENDED');
                foreach($domain as $dom)
                {
//                    $method = $dom['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
                     	if ($CI->$method->suspend($dom['login'],'NS2 suspended') === false)
                    	{
	                    	simple_admin_log('domain_user_suspended',$dom['login'],true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
                break;
            case "USER_UPDATED":
            	 break;
                $domain = $CI->host_plans_model->Load_User_Host_plans($users);
                foreach($domain as $dom)
                {
//                    $method = $dom['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
                        if ( $CI->$method->update($dom) === false)
                    	{
                    		simple_admin_log('domain_user_updated',$dom['login'],true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
                break;
            case "DIRECTORIES_ADDED":
                break;
            case "DIRECTORIES_REMOVED":
                break;
            case "SUBSCRIPTION_EXPIRED":
            	 break;
                $subscription = $this->Load_Subscription_All_Info($subscr_id);
                if (!is_array($subscription) || count($subscription)<=0)
                {
                    return false;
                }
                if ($subscription['product_type']==PRODUCT_HOSTED)
                {
//                    $method = $arrived_domain['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
//                    	return $CI->$method->revoke($subscription);
                    	if ( $CI->$method->suspend($subscription['login'],'NS2_SUBSCRIPTION_EXPIRED_'. $subscr_id) === false)
                    	{
                    		simple_admin_log('domain_subscription_expired',' subscr_id:'.$subscr_id.' name_domen:'.$subscription['name_domen'] 
							                    	. ' login:'.$subscription['login'] 
							                    	. ' email:'.$subscription['email'],true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
                break;
            case "SUBSCRIPTION_STARTED":
           //     $subscription = $this->Load_Subscription($subscr_id);
                $subscription = $this->Load_Subscription_All_Info($subscr_id);
                if (!is_array($subscription) || count($subscription)<=0)
                {
                    return false; 
                }
                if ($subscription['product_type'] == PRODUCT_HOSTED)
                {
                	/**
                	 * This is subscription for domain product
                	 */
                	if ($subscription['regular_price'] == 0)
                	{
                	/**
                	 * This is subscription for FREE hosted product
                	 */
                		
fb($subscription['regular_price']," free hosting without domain");
                    	simple_admin_log('domain_subscription_free',' subscr_id:'.$subscr_id.' name_domen:'.$subscription['name_domen'] 
							                    	. ' login:'.$subscription['login'] 
							                    	. ' email:'.$subscription['email'],false);
						return true;                		
                	}
                	
                	//                    $method = $subscription['method']; //current domain method
                    $method = 'ns_directi';
                    if ($this->_load_domain_method($method))
                    {
                    	if ( $CI->$method->grant($subscription) === false)
                    	{
	                    	$CI->$method->errors[] = ' name_domen:'.$subscription['name_domen'] 
							                    	. ' login:'.$subscription['login'] 
							                    	. ' email:'.$subscription['email'] 
							                    	. ' packages:'.$subscription['packages'];
                    		simple_admin_log('domain_subscription_started',$subscr_id,true,$CI->$method->errors);
                    		return false;
	                    }
                    }
                    else 
                    {
                    	return false;
                    }
                }
                break;
            case "SITE_IP_CHANGED":
//                $domain = $this->Get_all_protected();
//                foreach($domain as $dom)
//                {
////                    $method = $dom['method']; //current domain method
//                    $method = 'ns_directi';
//                    if ($this->_load_domain_method($method))
//                    {
//                        $CI->$method->site_ip_changed($dom);
//                    }
//                }
                break;
            default:
                admin_log("unknown_event",array('type'=>$type, 'users'=>$users, 'subscriptions'=>$subscriptions, 'products'=>$products, 'directories'=>$domain),-1);
                return false;
                break;
        } // switch
        return true;
    }

    function _load_domain_method($method)
    {
        $CI = &get_instance();
        if (isset($CI->$method) && is_object($CI->$method))
        {//method already loaded
            return true;
        }
                
        $subclass = "domain/$method";
        //$filename = dirname(__FILE__)."/$subclass.php";
        $filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])).'/system/application/models/'.$subclass.".php";
        if (!file_exists($filename))
        {
            simple_admin_log('not_load_domain_method',false,true, array(' method:'.$method.' filename:'.$filename));
            return false;
        }
        $CI->load->model($subclass, $method);
        return true;
    }

    /**
     * Reads list of supported packages from the host
     *
     * @return array of packages
     */
    function Get_packages()
    {
        $CI =& get_instance();        

        $method = 'ns_directi';
        
        if ($this->_load_domain_method($method))
        {
            $packages = $CI->$method->All_packages();
fb($packages,'Get_packages');            
            return $packages;
        }
	
    	return false;
    }

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @param unknown_type $uids
	 * @return true
	 */
    function Grant($did, $uids)
    {
        return true;
    }
    
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @param unknown_type $uids
	 * @return true
	 */
    function Revoke($did, $uids)
    {
        return true;
    }
    
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @return true
	 */
    function Update($did)
    {
        return true;
    }

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $directory
	 * @return true
	 */
    function Site_ip_changed($directory)
    {
        return true;
    }
    

}
?>
