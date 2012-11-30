<?php

require_once ('host_plans_model.php');

class Host_manager_model extends Host_plans_model
{
    function Host_manager_model()
    {
        parent::Host_plans_model();
    }

    /**
     * Event handler for all host_plan events
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
     * @param array/integer $host_plan(s) for whose event happened
     * @param string new $site_ip
     *
     * 
     * @copyright 2009
     */
    function event($type, $users=false, $subscr_id=false, $products=false, $host_plans=false)
    {
fb($type,'Event Hosted Products');
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
        
 //       admin_log("debug",array('type'=>$type, 'users'=>$users, 'subscription'=>$subscr_id, 'products'=>$products, 'directories'=>$host_plans));

        $CI = &get_instance();

        switch($type)
        {
            case "USER_UNSUSPENDED":
                $host_plans = $this->Load_User_host_plans($users);
                fb($host_plans,'USER_UNSUSPENDED');
                foreach($host_plans as $host_plan)
                {
//                    $method = $host_plan['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
                     	if ($CI->$method->unsuspend($host_plan['login']) === false)
                    	{
	                    	simple_admin_log('hosted_user_unsuspended',$host_plan['login'],true,$CI->$method->errors);
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
                $host_plans = $this->Load_User_host_plans($users);
                if (is_array($host_plans) && count($host_plans)>0)
                {
                	/**
                	 * This user have hosted product
                	 */
//                    $method = $host_plan['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
                        if ( $CI->$method->revoke($host_plans[0]) === false)
                    	{
	                    	simple_admin_log('hosted_user_deleted',$host_plans[0]['login'],true,$CI->$method->errors);
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
                $host_plans = $this->Load_User_host_plans($users);
                fb($host_plans,'USER_SUSPENDED');
                foreach($host_plans as $host_plan)
                {
//                    $method = $host_plan['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
                     	if ($CI->$method->suspend($host_plan['login'],'NS2 suspended') === false)
                    	{
	                    	simple_admin_log('hosted_user_suspended',$host_plan['login'],true,$CI->$method->errors);
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
                $host_plans = $this->Load_User_host_plans($users);
                foreach($host_plans as $host_plan)
                {
//                    $method = $host_plan['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
                        if ( $CI->$method->update($host_plan) === false)
                    	{
                    		simple_admin_log('hosted_user_updated',$host_plan['login'],true,$CI->$method->errors);
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
                $subscription = $this->Load_Subscription_All_Info($subscr_id);
                if (!is_array($subscription) || count($subscription)<=0)
                {
                    return false;
                }
                if ($subscription['product_type']==PRODUCT_HOSTED)
                {
                
//                    $method = $arrived_host_plans['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
//                    	return $CI->$method->revoke($subscription);
                    	if ( $CI->$method->suspend($subscription['login'],'NS2_SUBSCRIPTION_EXPIRED_'. $subscr_id) === false)
                    	{
	                    	$CI->$method->errors[] = ' name_domen:'.$subscription['name_domen'] 
							                    	. ' login:'.$subscription['login'] 
							                    	. ' email:'.$subscription['email'] 
							                    	. ' packages:'.$subscription['packages'];
                    		simple_admin_log('hosted_subscription_expired',$subscr_id,true,$CI->$method->errors);
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
                	 * This is subscription for hosted product
                	 */
                	if ($subscription['regular_price'] == 0 and $subscription['name_domen'] == "")
                	{
                	/**
                	 * This is subscription for FREE hosted product
                	 */
                		$host = $this->get_hostname($subscription);
                		$host = $subscription['login']. "." ./* $host; //*/"resseler.killer.biz.ua";

                	/**
                	 * @todo Insert checking uniq $host
                	 */
                		if( $this->insert_host_subscription($subscr_id,$host,true))
                		{
			                $subscription = $this->Load_Subscription_All_Info($subscr_id);
			                if (!is_array($subscription) || count($subscription)<=0)
			                {
			                    return false; 
			                }
                			
                		}
                		
fb($host,"empty free hosting");                		
                	}
//                    $method = $subscription['method']; //current host_plan method
                    $method = 'ns_whm';
                    if ($this->_load_hosted_method($method))
                    {
                    	if ( $CI->$method->grant($subscription) === false)
                    	{
	                    	$CI->$method->errors[] = ' name_domen:'.$subscription['name_domen'] 
							                    	. ' login:'.$subscription['login'] 
							                    	. ' email:'.$subscription['email'] 
							                    	. ' packages:'.$subscription['packages'];
                    		simple_admin_log('hosted_subscription_started',$subscr_id,true,$CI->$method->errors);
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
//                $host_plans = $this->Get_all_protected();
//                foreach($host_plans as $host_plan)
//                {
////                    $method = $host_plan['method']; //current host_plan method
//                    $method = 'ns_whm';
//                    if ($this->_load_hosted_method($method))
//                    {
//                        $CI->$method->site_ip_changed($host_plan);
//                    }
//                }
                break;
            default:
                admin_log("unknown_event",array('type'=>$type, 'users'=>$users, 'subscriptions'=>$subscriptions, 'products'=>$products, 'directories'=>$host_plans),-1);
                return false;
                break;
        } // switch
        return true;
    }

    function _load_hosted_method($method)
    {
        $CI = &get_instance();
        if (isset($CI->$method) && is_object($CI->$method))
        {//method already loaded
            return true;
        }
                
        $subclass = "hosted/$method";
        //$filename = dirname(__FILE__)."/$subclass.php";
        $filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])).'/system/application/models/'.$subclass.".php";
        if (!file_exists($filename))
        {
            simple_admin_log('not_load_hosted_method',false,true, array(' method:'.$method.' filename:'.$filename));
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

        $method = 'ns_whm';
        
        if ($this->_load_hosted_method($method))
        {
            $packages = $CI->$method->All_packages();
fb($packages,'Get_packages');            
            return $packages;
        }
	
    	return false;
    }

    /**
     * Read array of name servers DNS by domain
     *
     * @param mixed $subscription
     * @return array of name servers DNS by domain
     */
	function get_nameservers($subscription)
    {
        $CI =& get_instance();        

        $method = 'ns_whm';
        
        if ($this->_load_hosted_method($method))
        {
            $ns = $CI->$method->getnameservers($subscription);
            if ($ns === false)
            {
                simple_admin_log('host_get_nameservers','method: '.$method. ' user: ' .$subscription['login'].' email: ' .$subscription['email'].' name domain: ' .$subscription['name_domen'] ,true, $CI->$method->errors);
            }
           	return $ns;
        }
                
    	return false;
    }
    	

    function get_hostname($subscription)
    {
        $CI =& get_instance();        

        $method = 'ns_whm';
        
        if ($this->_load_hosted_method($method))
        {
//            $hostname = $CI->$method->gethostname($subscription);
            $hostname = $CI->$method->getresellhost($subscription);
            if ($hostname === false)
            {
                simple_admin_log('host_get_hostname','method: '.$method. ' user: ' .$subscription['login'].' email: ' .$subscription['email'].' name domain: ' .$subscription['name_domen'] ,true, $CI->$method->errors);
            }
           	return $hostname;
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
