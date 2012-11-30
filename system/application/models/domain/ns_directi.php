<?php
$domain_manager_model_path=realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain_manager_model.php');
require_once ($domain_manager_model_path); //just in case


/**
 * This is class for controlling accounts in LogixBoxes (DirectI)
 *
 */
 
class Ns_directi extends Domain_manager_model
{
	
/*
	var $SERVICE_USERNAME = "ginginua@gmail.com";// User Name.
    var $SERVICE_PASSWORD = "j1jXNuWg";// Password
    var $SERVICE_ROLE     = "reseller";     // Role, always leave this as reseller
    var $SERVICE_LANGPREF = "ru";// Language Preference ISO Code, 'en' for English
    var $SERVICE_PARENTID = "999999998";// Parent id.
//    var $LIB_DIR           = "../lib/";	   // Absolute or relative Path to your Lib folder

    var $SERVICE_URL = "http://api.onlyfordemo.net/anacreon/servlet/APIv3"; // HTTP DEMO SERVICE URL
//	var $SERVICE_URL = "https://api.onlyfordemo.net/anacreon/servlet/APIv3"; // HTTPS DEMO SERVICE 
//  var $SERVICE_URL = "http://www.myorderbox.com/anacreon/servlet/APIv3"; // HTTP LIVE SERVICE URL
//  var $SERVICE_URL = "https://www.foundationapi.com/anacreon/servlet/APIv3"; // HTTPS LIVE SERVICE URL
*/
	
    var $SERVICE_USERNAME = "";// User Name (as email).
    var $SERVICE_PASSWORD = "";// Password (8-15 char)
    var $SERVICE_ROLE     = "";// Role, always leave this as "reseller"
    var $SERVICE_LANGPREF = "";// Language Preference ISO Code, 'en' for English
    var $SERVICE_PARENTID = "";// Parent id.
    var $SERVICE_URL      = "";// HTTP/HTTPS DEMO/LIVE SERVICE URL
 	var $DEBUG            = true;// To on/off Debuging.
	var $HTTPS_URL        = false;// To on/off HTTPS
 	
	var $serviceObj;
	var $wsdlFileName;
	var $contacts_type=array("Contact", "CoopContact", "UkContact", "EuContact", "Sponsor");
 	
/**
 * Error massage
 *
 * @var array
 */
	var $errors=array();
	
   /**
	* Seperator used in Error string.
	*/
	var $seperator = "#~#";    
	
	
	/**
     * Just a constructor
     *
     * @return void
     */
    function Ns_directi()
    {
        parent::Domain_manager_model();
//        global $serviceurl;
        
/**
 * Load setting account registrar
 */
        $this->init(config_get('SYSTEM', 'REGISTRAR', 'DIRECTI'));
        
/**
 * Here load API Directi
 */        
        $CI = &get_instance();
        $CI->load->helper("api_directi");

    }

/**
 * Very important function - step by step registration user and domain
 * (detail process see in admin log)
 *
 * @param mixed $subscription
 * @return binary true/false full registration
 * 
 */
    function Grant($subscription)
    {

        $avail = $this->checkAvailabilityMultiple($subscription['name_domen'],"FALSE");
        $this->errorAnalyse($avail);
		if(!isset($avail[$subscription['name_domen']]) or !eregi('available',$avail[$subscription['name_domen']]['status']))
		{
//fb($avail[$subscription['name_domen']]['status'],"Grant avail[".$subscription['name_domen']."]['status']");			
	        	simple_admin_log('domain_grant_not_available', 'user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,"Domain is ".$avail[$subscription['name_domen']]['status']." (not available)");
	        	return false;
		}
		
    	$custom_id = $this->getCustomerId($subscription['email']);
    	if($this->errorAnalyse($custom_id))
    	{
    		$custom_id = $this->signUp($subscription);
	    	if($this->errorAnalyse($custom_id))
	    	{
	        	simple_admin_log('domain_grant_not_get_customid','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,$this->errors);
	        	return false;
	    	}
    		
    	}
    	
// see error in admin log    	
// fb($custom_id,"Grant custom_id") ;
		
    	$contacts = $this->getDefaultContacts($custom_id);
    	if($this->errorAnalyse($contacts))
    	{
    		$contacts = $this->addDefaultContacts($custom_id);
	    	if($this->errorAnalyse($contacts))
	    	{
	        	simple_admin_log('domain_grant_not_add_contacts','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,$this->errors);
	        	return false;
	    	}
    		
    	}
// see error in admin log    	
//fb($contacts,"Grant contacts") ;


    	$CI = &get_instance();
    	$CI->load->model("host_manager_model");
		$ns = $CI->host_manager_model->get_nameservers($subscription);
//fb($ns,__FUNCTION__ . " ns");
        if($ns === false)
        {
        	simple_admin_log('domain_grant_not_ns','user :'.$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,"Get Nameservers from hosting fail! Take empty");
			$ns = array();
        }
		
    	
		$domain_valid = $this->validateDomainRegistrationParams($custom_id,$subscription, $contacts,$ns);    	
        $this->errorAnalyse($domain_valid);
		if($domain_valid === false or !isset($domain_valid[$subscription['name_domen']]) or !eregi('success',$domain_valid[$subscription['name_domen']]['status']))
    	{
	        	simple_admin_log('domain_grant_not_valid_info','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true, isset($domain_valid[$subscription['name_domen']]['error'])?$domain_valid[$subscription['name_domen']]['error']:$this->errors);
				$ns = array();
	        	$domain_valid = $this->validateDomainRegistrationParams($custom_id,$subscription, $contacts,$ns);    	
		        $this->errorAnalyse($domain_valid);
				if($domain_valid === false or !isset($domain_valid[$subscription['name_domen']]) or !eregi('success',$domain_valid[$subscription['name_domen']]['status']))
		    	{
			        	simple_admin_log('domain_grant_not_valid_info','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true, isset($domain_valid[$subscription['name_domen']]['error'])?$domain_valid[$subscription['name_domen']]['error']:$this->errors);
			        	return false;
		       	}
       	}
       	
       	
		$domain_add = $this->addWithoutValidation($custom_id,$subscription, $contacts,$ns);    	
        $this->errorAnalyse($domain_add);
		if(!isset($domain_add[$subscription['name_domen']]) or !eregi('success',$domain_add[$subscription['name_domen']]['status'])) // or $domain_add[$subscription['name_domen']]['status']!='InvoicePaid') 
    	{
	        	simple_admin_log('domain_grant_not_regist','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,isset($domain_add[$subscription['name_domen']]['error'])?$domain_add[$subscription['name_domen']]['error']:$this->errors);
	        	return false;
       	}
    	simple_admin_log('domain_grant_not_regist','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],false);
		//		fb($domain_add,"Grant domain_add") ;
       	//    	$this->debug("signUp",$return,$subscription);
       	return true;
    }
/**
 * Enter description here...
 *
 * @param unknown_type $subscription
 * @return unknown
 */
    function unsuspend($subscription)
    {

    	$this->debug(__FUNCTION__,$return,$subscription);
    	return true;
    }

/**
 * Delete customer 
 *
 * @param mixed $subscription
 * @return mixed array or false
 */    
    function revoke($subscription)
    {
    	
    	$custom_id = $this->getCustomerId($subscription['email']);
    	if($this->errorAnalyse($custom_id))
    	{
//        	simple_admin_log('domain_revoke_not_get_customid','user: ' .$subscription['login'].' email: ' .$subscription['email'],true,$this->errors);
        	return false;
    		
    	}
    	    	
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/Customer.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("delete",
		array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID,
		$custom_id)); 
    	$this->debug(__FUNCTION__,$return,$subscription);
    	return !$this->errorAnalyse($return);
    }

    function suspend($subscription)
    {

    	$this->debug(__FUNCTION__,$return,$subscription);
    	return true;
    }

    function update($subscription)
    {

    	$this->debug(__FUNCTION__,$return,$subscription);
    	return true;
    }

/**
 * Check availability for registaration domain name (Multiple domain may be)
 *
 * @param string $name_domen
 * @param string $suggestAlternative "FALSE" or "TRUE" return alternative domain name
 *
 * @return mixed array or false 
 * 
 */    
    function checkAvailabilityMultiple($name_domen,$suggestAlternative = "FALSE")
    {

        $this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/DomOrder.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);

        $backrefs = explode(".", $name_domen);
		$domainNames = array($backrefs[0]);
		unset($backrefs[0]);
		$tlds = array(implode(".", $backrefs));
// 		$suggestAlternative = "FALSE";
    					
    	$return = $this->serviceObj->call("checkAvailabilityMultiple",
    					array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
    					$domainNames,$tlds,$suggestAlternative));
    	$this->debug(__FUNCTION__,$return,$name_domen);
    	return $return;
    }
    
    
/**
 * get customer Id if exist for this email 
 *
 * @param string $emailAddress
 * @return integer or false (CustomerId)
 */    
    function getCustomerId($emailAddress)
    {
//        $emailAddress =  	 "ginginua@gmail.com"; 
    	$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/Customer.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
    	$return = $this->serviceObj->call("getCustomerId",
				array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
    	    	$emailAddress));
    	$this->debug(__FUNCTION__,$return,$emailAddress);
    	return $return;
    }

/**
 * Registration customer and get they Id
 *
 * @param mixed $subscription
 * @return integer or false (CustomerId)
 */
    function signUp($subscription)
	{
		
		$CI = &get_instance();
		$info = $CI->user_model->Profile_additional_get($CI->user_model->profile_types[1],$subscription['user_id'],$subscription['user_info_id'],true);
        fb($info,__FUNCTION__ . " info");
        if($info === false)
        {
        	simple_admin_log('domain_signup_not_reg_info','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,$this->errors);
        	return false;
        }
		
		$emailAddress = $subscription['email']; 
		$password = ns_decrypt($subscription['sec_code'],$subscription['pass']);
		$name = $info['name']; 
		$company = $info['company']; 
		$address1 = $info['address1'];
		$address2 = $info['address2']; 
		$address3 = $info['address3']; 
		$city = $info['city']; 
		$stateName = $info['state']; 
		$country = $info['country']; 
		$zip = $info['zip']; 
		$telNoCc = $info['telnocc']; 
		$telNo = $info['telno']; 
		$altTelNoCc = $info['alttelnocc']; 
		$altTelNo = $info['alttelno']; 
		$faxNoCc = $info['faxnocc']; 
		$faxNo = $info['faxno']; 
		$langPref = $info['customerlangpref']; 
		$mobileNoCc = ''; 
		$mobileNo = '';
		
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/Customer.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("signUp",
		array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
		$emailAddress, $password, $name, $company, $address1, $address2, $address3, $city, $stateName, $country, $zip, $telNoCc, $telNo, $altTelNoCc, $altTelNo, $faxNoCc, $faxNo, $langPref, $mobileNoCc, $mobileNo));
    	$this->debug(__FUNCTION__,$return,$subscription);
		return $return;
	}

/**
 * Get default contacts for customerId
 *
 * @param integer $customerId
 * @param mixed $types
 * @return mixed or false;
 */
	function getDefaultContacts($customerId, $types = array("Contact"))
	{
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/DomContact.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("getDefaultContacts",
		array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
		$customerId, $types));
    	$this->debug(__FUNCTION__,$return,$customerId);
		return $return;
	}

/**
 * Add default contacts for customerId
 *
 * @param integer $customerId
 * @param mixed $types
 * @return mixed or false;
 */
	function addDefaultContacts($customerId, $types = array("Contact"))
	{
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/DomContact.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("addDefaultContacts",
		array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
		$customerId, $types));
    	$this->debug(__FUNCTION__,$return,$customerId);
		return $return;
	}

/**
 * Validate sets Params for Domain Registration
 *
 * @param integer $customerId
 * @param mixed $subscription
 * @param mixed $contacts
 * @param mixed $ns_arr
 * @return mixed or false;
 */	
	function validateDomainRegistrationParams($customerId, $subscription, $contacts, $ns_arr = array())
	{
		
		if($subscription['regular_period_type'] != 'year')
        {
	        simple_admin_log('domain_grant_not_valid_info','user: ' .$subscription['login'].' email: ' .$subscription['email'].' name_domen: ' .$subscription['name_domen'],true,'regular period type is not "year"');
        	return false;
        }
		$domainHash = array($subscription['name_domen']=>$subscription['regular_period_value']);
		$ns = $ns_arr;
//fb($ns,__FUNCTION__ . " ns");
		$registrantContactId = $contacts['Contact']['registrant'];
		$adminContactId =  $contacts['Contact']['admin'];
		$techContactId =  $contacts['Contact']['tech'];
		$billingContactId =  $contacts['Contact']['billing'];
		$invoiceOption = 'NoInvoice';
//		$invoiceOption = 'KeepInvoice';
		
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/DomOrder.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("validateDomainRegistrationParams",
				array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
				$domainHash, $ns, $registrantContactId, $adminContactId, $techContactId, $billingContactId, $customerId, $invoiceOption));
    	$this->debug(__FUNCTION__,$return,$customerId);
		return $return;
		
	}
		
	function addWithoutValidation($customerId, $subscription, $contacts, $ns_arr = array())
	{
		
		$domainHash = array($subscription['name_domen']=>$subscription['regular_period_value']);
		$ns = $ns_arr;
//		$ns = array('ns.resseler.killer.biz.ua');
		$registrantContactId = $contacts['Contact']['registrant'];
		$adminContactId =  $contacts['Contact']['admin'];
		$techContactId =  $contacts['Contact']['tech'];
		$billingContactId =  $contacts['Contact']['billing'];
		$invoiceOption = 'NoInvoice';
		
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/DomOrder.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("addWithoutValidation",
				array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID, 
				$domainHash, $ns, $registrantContactId, $adminContactId, $techContactId, $billingContactId, $customerId, $invoiceOption));
    	$this->debug(__FUNCTION__,$return,$customerId);
		return $return;
		
	}
	
	
/**
 * Debug output properties serviceObj (DirectI)
 *
 * @param unknown_type $name_function
 * @param unknown_type $return
 * @param unknown_type $subscription
 */
    function debug($name_function,$return="null",$subscription=array())
    {
		$debug_str = array('parameters'=>$subscription,'return'=>$return);
    	fb($debug_str,"$name_function: param ");
      
/*
      $request = array($this->serviceObj->request);
      fb($request,"$name_function: request");
      $response = array($this->serviceObj->response);
      fb($response,"$name_function: response");
      fb($return,"$name_function: return");

      $debug = array($this->serviceObj->debug_str);
      fb($debug,"$name_function: debug");
      $endpoint = array($this->serviceObj->endpoint);
      fb($endpoint,"$name_function: endpoint");
      $getError = array($this->serviceObj->error_str);
      fb($getError,"$name_function: error_str");
*/
    }
    
/**
 * Initialization
 *
 * @param mixed $registrar
 */    
	function init($registrar=array())
	{
fb($registrar,"init registrar ");		
/*
        $this->DEBUG = true; // To on/off Debuging.
		$this->HTTPS_URL = false; // To on/off HTTPS
		$this->SERVICE_USERNAME ="ginginua@gmail.com";// User Name.
		$this->SERVICE_PASSWORD ="j1jXNuWg";// Password
		$this->SERVICE_LANGPREF ="ru";// Language Preference ISO Code, 'en' for English
		$this->SERVICE_PARENTID ="999999998";// Parent id.
*/

		$this->DEBUG     = isset($registrar['debug'])?($registrar['debug']==1?true:false):false ; // To on/off Debuging.
		$this->HTTPS_URL = isset($registrar['https_url'])?($registrar['https_url']==1?true:false):false;// To on/off HTTPS
		$this->SERVICE_USERNAME =isset($registrar['service_username'])?$registrar['service_username']:"";// User Name.
		$this->SERVICE_PASSWORD =isset($registrar['service_password'])?$registrar['service_password']:"";// Password
		$this->SERVICE_LANGPREF =isset($registrar['service_langpref'])?$registrar['service_langpref']:"";// Language Preference ISO Code, 'en' for English
		$this->SERVICE_PARENTID =isset($registrar['service_parentid'])?$registrar['service_parentid']:"";// Parent id.
		
		$this->SERVICE_ROLE     ="reseller";     // Role, always leave this as reseller
		
        if($this->DEBUG=== true)
        {
        	if($this->HTTPS_URL === false)
        	{
		        $this->SERVICE_URL = "http://api.onlyfordemo.net/anacreon/servlet/APIv3"; // HTTP DEMO SERVICE URL
        	}else{
        		$this->SERVICE_URL = "https://api.onlyfordemo.net/anacreon/servlet/APIv3"; // HTTPS DEMO SERVICE 
        	}
        }else 
        {
        	if($this->HTTPS_URL === false)
        	{
        		$this->SERVICE_URL = "http://www.myorderbox.com/anacreon/servlet/APIv3"; // HTTP LIVE SERVICE URL
        	}else{
        		$this->SERVICE_URL = "https://www.foundationapi.com/anacreon/servlet/APIv3"; // HTTPS LIVE SERVICE URL
        	}
        }

/*
fb($this->DEBUG," init registrar ");		
fb($this->HTTPS_URL," init registrar this->HTTPS_URL");		
fb($this->SERVICE_USERNAME," init registrar SERVICE_USERNAME");		
fb($this->SERVICE_PASSWORD," init registrar SERVICE_PASSWORD");		
fb($this->SERVICE_LANGPREF," init registrar SERVICE_LANGPREF");		
fb($this->SERVICE_PARENTID," init registrar SERVICE_PARENTID");		
fb($this->SERVICE_ROLE," init registrar SERVICE_ROLE");		
fb($this->SERVICE_URL," init registrar SERVICE_URL");		
*/
        if ($this->SERVICE_USERNAME =="" or
			$this->SERVICE_PASSWORD =="" or
			$this->SERVICE_LANGPREF =="" or
			$this->SERVICE_PARENTID =="" or
			$this->SERVICE_ROLE    !=="reseller" )
        {
	        simple_admin_log('domain_directi_not_init',false,true, ' reason : check config information (may by not full)');
        	return false;
        }else
        {
        	return true;
        }
	    	
	}

/**
 * Testing sets params for connecting
 *
 * @return string - current service URL or false
 */			
	function testConnect()
	{
				
		$this->wsdlFileName = realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/domain/directi/Currency.wsdl');
        $this->serviceObj = new  soapclientMy($this->wsdlFileName,"wsdl",false,false,false,false,
                                                  $this->SERVICE_URL);
		$return = $this->serviceObj->call("getAllCurrencyList",
		array($this->SERVICE_USERNAME,$this->SERVICE_PASSWORD,$this->SERVICE_ROLE,$this->SERVICE_LANGPREF,$this->SERVICE_PARENTID));
    	$this->debug(__FUNCTION__,$return);
    	if($this->errorAnalyse($return))
    	{
    		return false;
    	}
    	else 
    	{
//        	return true;
        	return $this->SERVICE_URL;
       	}
	}
			

/**
 *  This function analyse the data for error.
 *  If data consists of Error string it fills the array $errors.
 * 
 * @param mixed $data
 * @return boolean if Error - true else false
 */	
	function errorAnalyse($dataArr)
	{
		$isError = false;
		
		if($dataArr === false)
		{
//			$this->errors[] = "Error. No output";
			return true;
		}
    	if (is_array($dataArr))
    	{
			foreach ($dataArr as $key => $value)
			{
		    	if (is_array($value))
		    	{
		    		$this->errorAnalyse($value);
		    	}
		    	else 
		    	{
					if(in_array($key, array('faultstring')))
			    	{
						$error = explode($this->seperator ,$value);
			
						if(isset($error[2]))
						{
							$this->errors[] = $error[2];
						}
						else 
						{
							$this->errors[] = $value;
						}
						
						$isError =  true;
						
			    	}
			    	if(in_array($key, array('error')))
			    	{
			
						$this->errors[] =$value;
						$isError =  true;
						
			    	}
		    	}
			}
    	}
//fb($this->errors,"Domain errors ");
		return $isError;
	}
	

}
?>
