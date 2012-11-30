<?php
$host_manager_model_path=realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/host_manager_model.php');
require_once ($host_manager_model_path); //just in case


/**
 * This is class for controlling accounts in cPanel with WHM
 *
 */ 
class Ns_whm extends Host_manager_model
{

/**
 * Hame host where cPanel
 *
 * @var string 
 */
	var $host = null;

/**
 * Name user (cPanel)
 *
 * @var string
 */
	var $user=null;
	
/**
 * Number of port for access to cPanel
 *
 * @var integer
 */
	var $port=null;
	
/**
 * Enter description here...
 *
 * @var string
 */
	var $accessHash = null;

/**
 * Error massage
 *
 * @var array
 */
	var $errors=array();

/**
 * Handler socket
 *
 * @var unknown_type
 */
	var $fp=null;

	/**
     * Just a constructor
     *
     * @return void
     */
    function Ns_whm()
    {
        parent::Host_manager_model();
        $this->init(config_get('SYSTEM', 'HOSTING', 'host_host'),
                    config_get('SYSTEM', 'HOSTING', 'host_port'),
                    config_get('SYSTEM', 'HOSTING', 'host_user'),
                    config_get('SYSTEM', 'HOSTING', 'host_pass'));
    }

/**
 * Return all available packages in cPanel
 *
 * @return mixed/array
 */
    function All_packages()
    {
    	$packages = $this->listPkgs();
fb($packages,'packages');
        if ($packages !== false )
        {
	        $list_packages = array();
	        if (isset($packages[0]))
	        {
	            foreach ($packages as $package)
		        {
		        	$list_packages[] = $package['name'];
		        }
	        }
	        else
	        {
	        	$list_packages[] = $packages['name'];
	        }
	        return $list_packages;
        }
        else 
        {
        	return false;
        }
    }

    /**
     * Create account for users and Grants access to cPanel for users
     *
	 * @param mixed $subscription
	 * @return boolean
	 */
    function Grant($subscription)
    {
fb($subscription,'Grant');

 	   	$account_info = $this->accountsummary($subscription['login']);
       	if ($account_info !== false)
       	{
       	    if ( $account_info['status'] == 1)
       	    {
/**
 * User exist - unsuspend account if needed
 */
       	    	if ($account_info['acct']['suspended']== 1 && $this->unsuspend($subscription['login']) === false)
	        	{	
	                return false;
	        	}
/**
 * User exist - update account 
 */
	        	
	        	
//	        		$result = $this->update($subscription);
//		        	if ($result === false)
//		        	{
//		        	    return false;
//		        	}
	        	
	        	if ($subscription['packages'] !== $account_info['acct']['plan'])
	        	{
	        		$result = $this->changepackage($subscription['login'],$subscription['packages']);
		        	if ($result === false)
		        	{
		        	    return false;
		        	}
	        	} 
	        	else
	        	fb($subscription['packages'],'Package already is set');
	        	
		        return $this->update($subscription);
	        	
	        	
       	    }
       	    else 
       	    {
/**
 * User not exist - created account
 */
       	    	return $this->createAccount($subscription['name_domen'],$subscription['login'],
                                       ns_decrypt($subscription['sec_code'],$subscription['pass']),$subscription['email'],$subscription['packages']);
       	    }
       	}
        else 
        {
        	return false;
        }
        return true;
	}


    /**
     * Revokes access to cPanel from users - Terminate account users from cPanel 
     *
     * @param integer directory id
     * @param array of integers (user ids)
     * @return boolean - success or failure
     */
    function Revoke($user)
    {
//        $users = $this->Load_Users($user_id);
//        $user = $users[0]; 
fb($user,'Revoke');
		if(isset($user['login']) && $user['login']!=="")
		{
	        $result = $this->terminate($user['login']);
	
	       if ($result!==false)
	       {
	        fb($result,'Succes Revoke');
	       	return true;
	       } 
	       else
	       {
	        fb($result,'Fail Revoke');
	       	return false; 
	       }
			
		}
		else 
		{
			return false;
		}
}

/**
 * Check by exist user 
 * And suspend  cpanel account
 * 
 * @param string $acctUser
 * @param string $reason
 * @return TRUE/FALSE
 */
	function suspend($acctUser,$reason)
	{
	   	$account_info = $this->accountsummary($acctUser);
       	if ($account_info !== false && $account_info['status'] == 1)
       	{
        	if ($account_info['acct']['suspended']== 0)
        	{
        		return $this->_suspend($acctUser,$reason);	
        	}
       	}
        else 
        {
        	return false;
        }
        return true;
	}
	
/**
 * Check by exist user and a suspended.
 * And unsuspend  cpanel account
 *
 * @param string $acctUser
 * @return logical true on success, false on fail
 */
	function unsuspend($acctUser)
	{
	   	$account_info = $this->accountsummary($acctUser);
       	if ($account_info !== false && $account_info['status'] == 1)
       	{
        	if ($account_info['acct']['suspended']== 1)
        	{
        		return  $this->_unsuspend($acctUser);	
        	}
       	}
        else 
        {
//            simple_admin_log('host_unsuspend',false,true, array('user: '.$acctUser,'reason : failed'), -1);
        	return false;
        }
        return true;
	}

/**
 * Update account user
 *
 * @param mixed $subscription
 * @return boolean
 */
	function update($subscription)
    {
fb($subscription,'update');

 	   	$account_info = $this->accountsummary($subscription['login']);
       	if ($account_info !== false)
       	{
       	    if ( $account_info['status'] == 1)
       	    {
/**
 * User exist - update account 
 */
				if (!isset($subscription['name_domen']))
				{
				     $subscription['name_domen'] =  $account_info['acct']['domain'];  	
				}
	        	$result = $this->passwd($subscription['login'],ns_decrypt($subscription['sec_code'],$subscription['pass']));
	        	if ($result === false || $result['status'] == 0 )
	        	{
	        	    return false;
	        	}
	        	
	        	if( ($subscription['email'] !== $account_info['acct']['email']) ||
	        	    ($subscription['name_domen'] !== $account_info['acct']['domain']) )
	        	{
/**
 * @todo update email not realised in xml API. Need addon functional
 */		        	
	        		$pack = $this->packages_info($account_info['acct']['plan']);
		        	if ($pack !== false)
		        	{
		        		$pack = $pack[$account_info['acct']['plan']];
fb($pack,__FUNCTION__);
		        		$result = $this->updateacct($subscription['login'],$subscription['name_domen'],$subscription['email'],
			        	$pack['CGI'],$pack['CPMOD'],$pack['LANG'],$pack['MAXPOP'],$pack['MAXFTP'],$pack['MAXLST'],$pack['MAXSUB'],$pack['MAXPARK'],$pack['MAXADDON'],$pack['MAXSQL'],$pack['HASSHELL']);
			        	if ($result === false )
			        	{
			        	    return false;
			        	}
		        	}
	        	}
	        	else
	        	fb($subscription['name_domen'],'name_domen already is set');
	        	
	        	
       	    }
       	    else 
       	    {
/**
 * User not exist - error
 */
       	    	return false;
       	    }
       	}
        else 
        {
        	return false;
        }
        return true;
	}
	
/**
 * Get array name servers from dump DNS zone
 *
 * @param mixed $subscription
 * @return array or false
 */  	
	function getnameservers($subscription)
    {
//fb($subscription['name_domen'],__FUNCTION__);

        $nameservers = array();
 	   	$dump_zone = $this->getDNSzone($subscription['name_domen']);
       	if ($dump_zone !== false)
       	{
       		foreach ($dump_zone as $rec)
       		{
       			if (isset($rec['type']) and $rec['type'] === 'NS')
       			{
       				$nameservers[] = $rec['nsdname'];
       			}
       		}
//fb($nameservers,__FUNCTION__ . " NS");
       		return $nameservers;
       	}
       	else
       	{
       		return false;
       	}
		
	}

	

/**
 * 
 **************************************************
 *      Part WHM
 **************************************************
 *  
 */
    
	/*
	 * initialization
	 */
	function init($host,$port,$user,$accessHash)
	{
		$this->host=$host;
		$this->user=$user;
		$this->port=$port;

		$accessHash = str_replace(array("\r", "\n"," "),"",$accessHash);
//		$accessHash = str_replace(array("\r", "\n"),"",$accessHash);
		$this->accessHash=$accessHash;
	}

	/*
	 * connect to the xml api
	 * Output: true on success, false on fail
	 */
	function connect($api_path)
	{
		$errno = 0;
		$errstr = "";
		/*
		 *  Open a socket 
		 */
		$this->fp = @fsockopen($this->host, $this->port, $errno, $errstr, 30);

		/*
		 * Die on error initializing socket
		 */
		if ($errno == 0 && $this->fp === false)
		{
			$this->errors[]="Socket Error: Could not initialize socket.";
			return false;
		}
		elseif ($this->fp === false)
		{
			$this->errors[]="Socket Error #" . $errno . ": " . $errstr;
			return false;
		}

		/*
		 *  Assemble the header to send
		 */
		$header = "";
		$header .= "GET " . $api_path . " HTTP/1.0\r\n";
		$header .= "Host: " . $this->host . " \r\n";
		$header .= "Connection: Close\r\n";
		$header .= "Authorization: WHM " . $this->user . ":" . $this->accessHash . "\r\n";
		// Comment above line and uncomment below line to use password authentication in place of hash authentication
		//$header .= "Authorization: Basic " . base64_encode($user . ":" . $pass) . "\r\n";
		$header .= "\r\n";
//fb($header,'header');
		/*
		 * Send the Header
		 */
		if(!@fputs($this->fp, $header))
		{
			$this->errors[]='Unable to send header.';
			return false;
		}
		return true;
	}

	/*
	 * Close the socket
	 */
	function disconnect()
	{
		fclose($this->fp);
	}

	/*
	 * Get the raw output from the server
	 * Output: string
	 */
	function getOutput()
	{
		$rawResult = "";
		while ($this->fp!=FALSE && !feof($this->fp))
		{
			$rawResult .= @fgets($this->fp, 128); // Suppress errors with @
		}

//fb($rawResult,'rawResult uncutt');
		/*
		 * Ignore headers
		 */
		if ($rawResult !== "")
		{
			$rawResultParts = explode("\r\n\r\n",$rawResult);
			$headers = explode("\r\n",$rawResultParts[0]);
//fb($headers,"headers");
			if (preg_match("/ 200 OK/",$headers[0]))
			{
				$result = xml2array($rawResultParts[1]);
			    if (count($result) <= 0)
			    {
					$result = "";
					$this->errors[] = $headers[0];
			    }
   			}
			else
			{
				$result = "";
				$this->errors[] = $headers[0];
			}
		}
		else 
			$result = "";

		/*
		 * Output XML
		 */
fb($result,'getOutput');
		return $result;
	}


	/*
	 * This function lists the version of cPanel and WHM installed on the server.
	 * Output: string
	 */
	function version()
	{
		//connect using prpoer xml api address
		$this->connect('/xml-api/version');
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//get the output xml as an array using simple xml
//		$xml = new SimpleXMLElement($xmlarray);
        if (isset($xmlarray['version']))
        {
        	return "cPanel v.".$xmlarray['version']['version'];
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
  	}

/**
 * Get dump DNS zone for domain
 *
 * @param string $domain
 * @return mixed array or false
 */
  	function getDNSzone($domain)
	{
		//connect using prpoer xml api address
		$this->connect("xml-api/dumpzone?domain=$domain");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//get the output xml as an array using simple xml
        if (isset($xmlarray['dumpzone']))
        {
			if($xmlarray['dumpzone']['result']['status']==1)
			{
				return $xmlarray['dumpzone']['result']['record'];
			}
			else
			{
			    $this->errors[]=$xmlarray['dumpzone']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }

	}
  	
	/*
	 * This return name resselers host.
	 * Output: string
	 */
	function getresellhost($subscription)
	{
fb($this->host,__FUNCTION__ . " host" );		
		if ($this->host!=="")
		{
/**
 * @todo Insert checking valid domain name
 */			
			return $this->host;
		}
		$this->errors[] = "Resellers host name not set";
		return false;
	}
	
	/*
	 * This function lists the server's hostname.
	 * Output: string
	 */
	function gethostname()
	{
		//connect using prpoer xml api address
		$this->connect('/xml-api/gethostname');
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//get the output xml as an array using simple xml
		//$xml = new SimpleXMLElement($xmlarray);

        if (isset($xmlarray['gethostname']))
        {
        	return $xmlarray['gethostname']['hostname'];
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
	}

	/*
	 * list currently active accounts
	 * Output: array on success, false on fail
	 */
	function listaccts()
	{
		//connect using prpoer xml api address
		$this->connect('/xml-api/listaccts');
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['listaccts']))
        {
        	return $xmlarray['listaccts']['acct'];
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
	}


	/*
	 * list packages
	 * Output: array on success, false on fail
	 */
	function listPkgs()
	{
		//connect using prpoer xml api address
		$this->connect('/xml-api/listpkgs');
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['listpkgs']['package']) && is_array($xmlarray['listpkgs']['package']))
        {
        	return $xmlarray['listpkgs']['package'];
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }

	}

	/*
	 * create a cpanel account
	 * Output: array on success, false on fail
	 */
	function createAccount($acctDomain,$acctUser,$acctPass,$acctEmail,$acctPackg)
	{
        $url = "/xml-api/createacct?username=$acctUser&password=$acctPass&plan=$acctPackg&contactemail=$acctEmail&domain=$acctDomain&ip=n&cgi=y&frontpage=y&cpmod=x3&useregns=0&reseller=0";
		fb($url,'createAccount');
		//connect using prpoer xml api address
		$this->connect($url);
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//get the output xml as an array using simple xml
/*		$xml = new SimpleXMLElement($xmlarray);


		if($xml->result->status==1)
		{
			$result['status']=$xml->result->status;
			$result['statusmsg']=$xml->result->statusmsg;
			$result['ip']=$xml->result->options->ip;
			$result['nameserver']=$xml->result->options->nameserver;
			$result['nameserver2']=$xml->result->options->nameserver2;
			$result['nameserver3']=$xml->result->options->nameserver3;
			$result['nameserver4']=$xml->result->options->nameserver4;
			$result['package']=$xml->result->options->package;
			$result['rawout']=$xml->result->rawout;
			return $result;
		}
		else
		{
			$this->errors[]=$xml->result->statusmsg;
			return false;
		}
*/
		//return the result array
        if (isset($xmlarray['createacct']))
        {
			if($xmlarray['createacct']['result']['status']==1)
			{
				return $xmlarray['createacct']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['createacct']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }

	}


	/*
	 * This function displays pertient account information for a specific account.
	 * Output: array on success , false on fail
	 */
	function accountsummary($accUser)
	{
		//connect using prpoer xml api address
		$this->connect("/xml-api/accountsummary?user=$accUser");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		if (isset($xmlarray['accountsummary']))
        {
			if (isset($xmlarray['accountsummary']['status']) && $xmlarray['accountsummary']['status'] == 0)
			{
				$this->errors[]=$xmlarray['accountsummary']['statusmsg'];
			}
        	return $xmlarray['accountsummary'];
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }

	}

/*
 *This function changes the passwd of a domain owner (cPanel) or reseller (WHM) account.
 * Output: array on success , false on fail
 */
	function passwd($accUser,$pass)
	{
fb($pass,'pass');
		if ($accUser == "" || $pass  == "")
		{
			return false;
		}
		//connect using prpoer xml api address
		$this->connect("/xml-api/passwd?user=$accUser&pass=$pass");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		if (isset($xmlarray['passwd']))
        {
			if($xmlarray['passwd']['passwd']['status']==1)
			{
				return $xmlarray['passwd']['passwd'];
			}
			else
			{
			    $this->errors[]=$xmlarray['passwd']['passwd']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
	}

	
    /*
	 * suspend a cpanel account
	 * Output: string (statusmsg) on success, false on fail
	 */
	function _suspend($acctUser,$reason)
	{
		//connect using prpoer xml api address
		$this->connect("/xml-api/suspendacct?user=$acctUser&reason=$reason");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

/*		//get the output xml as an array using simple xml
		$xml = new SimpleXMLElement($xmlarray);

		if($xml->result->status==1)
		{
			return $xml->result->statusmsg;
		}
		else
		{
			$this->errors[]=$xml->result->statusmsg;
			return false;
		}
*/		
		//return the result array
        if (isset($xmlarray['suspendacct']))
        {
			if($xmlarray['suspendacct']['result']['status']==1)
			{
				return $xmlarray['suspendacct']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['suspendacct']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
		
	}

	
	/*
	 * unsuspend a suspended cpanel account
	 * Output: string (statusmsg) on success, false on fail
	 */
	function _unsuspend($acctUser)
	{
		//connect using prpoer xml api address
		$this->connect("/xml-api/unsuspendacct?user=$acctUser");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['unsuspendacct']))
        {
			if($xmlarray['unsuspendacct']['result']['status']==1)
			{
				return $xmlarray['unsuspendacct']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['unsuspendacct']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
		
	}


	/*
	 * terminate a cpanel account
	 * Output: string (statusmsg) on success, false on fail
	 */
	function terminate($acctUser,$keepDns=0)
	{
		//connect using prpoer xml api address
		$this->connect("/xml-api/removeacct?user=$acctUser&keepdns=$keepDns");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['removeacct']))
        {
			if($xmlarray['removeacct']['result']['status']==1)
			{
				return $xmlarray['removeacct']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['removeacct']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
		
	}


	/*
	 * Upgrade/Downgrade and Account (Change Package)
	 * Output: array on success, false on fail
	 */
	function changepackage($accUser,$pkg)
	{
		//connect using prpoer xml api address
		$this->connect("/xml-api/changepackage?user=$accUser&pkg=$pkg");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['changepackage']))
        {
			if($xmlarray['changepackage']['result']['status']==1)
			{
				return $xmlarray['changepackage']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['changepackage']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
		
	}
    

	/*
	 * add domain
	 * Output: array on success, false on fail
	 */
	function adddns($acctDomain,$ip)
	{
		$url = "/xml-api/adddns?domain=$acctDomain&ip=$ip";
        fb($url,'adddns');
		//connect using prpoer xml api address
		$this->connect($url);
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

fb($xmlarray,'adddns');
		//return the result array
        if (isset($xmlarray['adddns']))
        {
			if($xmlarray['adddns']['result']['status']==1)
			{
				return $xmlarray['adddns']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['adddns']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }

	}


/**
 * Update account
 *
 * @param string $accUser
 * @param string $domain
 * @return mixed false/array
 */
	function updateacct($accUser,$domain,$email,$cgi = '',$cpmod ='',$lang='',$pop='',$ftp='',$lst='',$sub='',$park='',$addon='',$sql='',$shell='')
	{
		//connect using prpoer xml api address
//		$this->connect("/xml-api/modifyacct?user=$accUser&domain=$domain&email=$email&HASCGI=0&CPTHEME=x3&LANG=english&MAXPOP=3&MAXFTP=0&MAXLST=1&MAXSUB=3&MAXPARK=4&MAXADDON=5&MAXSQL=6&shell=1");
		$this->connect("/xml-api/modifyacct?user=$accUser&domain=$domain&email=$email&HASCGI=$cgi&CPTHEME=$cpmod&LANG=$lang&MAXPOP=$pop&MAXFTP=$ftp&MAXLST=$lst&MAXSUB=$sub&MAXPARK=$park&MAXADDON=$addon&MAXSQL=$sql&shell=$shell");
		//get the output
		$xmlarray=$this->getOutput();
		if($xmlarray=='')
		{
			$this->errors[]='No output.';
			return false;
		}
		//disconnect
		$this->disconnect();

		//return the result array
        if (isset($xmlarray['modifyacct']))
        {
			if($xmlarray['modifyacct']['result']['status']==1)
			{
				return $xmlarray['modifyacct']['result'];
			}
			else
			{
			    $this->errors[]=$xmlarray['modifyacct']['result']['statusmsg'];
				return false;
			}
        } else 
        {
			$this->errors[]='No output.';
			return false;
        }
		
	}
	
/**
 * Return properties all or requested packages
 * 
 * @param string Name package
 * @return unknown
 */
	function packages_info($my_pkg = NULL )
    {
    	$packages = $this->listPkgs();
fb($packages,__FUNCTION__);
        if ($packages !== false )
        {
	        $list_packages = array();
	        if (isset($packages[0]))
	        {
	            foreach ($packages as $package)
		        {
		        	if (!is_null($my_pkg) && $my_pkg === $package['name'])
		        	{
		        		$list_packages[$package['name']] = $package;
		        		return $list_packages;
		        	}
		        	elseif (is_null($my_pkg))
		        	{
		        		$list_packages[$package['name']] = $package; 
		        	}
		        }
	        }
	        else
	        {
	        	if (!is_null($my_pkg) && $my_pkg === $package['name'])
	        	{
		        	$list_packages[$packages['name']] = $packages;
		        	return $list_packages;
	        	}
	        	elseif (is_null($my_pkg))
	        	{
	        		$list_packages[$packages['name']] = $packages;
	        	}
   		    }
	        if (count($list_packages)>0)
	        {
	        	return $list_packages;
	        }
	        else 
	        {
	        	return false;
	        }
	        
        }
        else 
        {
        	return false;
        }
    }
}
?>
