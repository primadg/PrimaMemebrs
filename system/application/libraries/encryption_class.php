<?php
    define('LICENSE_TIME', 259200);
    define('LICENSE_DIE_TIME', 172800);
    define('LICENSE_CHECK_ERROR', 900);
    define('LICENSE_CHECK_ERROR_NEXT', 900);
    define('LICENSE_CHECK_HOST', 'lmh.conkurent.com');
  
    
    function CheckLicense($license = '', $product=0)
    {    
        $domain = $_SERVER['SERVER_NAME'];          
        $hash = md5(time().rand(0,9999));
        $host = LICENSE_CHECK_HOST;
        $port = 80;
        $is_connect=false;$response='';
        $crypt = new encryption_class;
        $key = "host=$domain&license=$license&product=$product";
        $key = $crypt->encrypt($hash,$key,strlen($key));   
        if (function_exists('fsockopen')) //fsockopen
        {
            $fp  = @fsockopen($host, $port, $errno, $errstr, 10);
            if (!$fp)
            {
                $is_connect = false;
            } else
            {
                fputs($fp, "POST /check.php?key=".urlencode($key)."&hash=".urlencode($hash)." HTTP/1.0\r\n");
                fputs($fp, "Host: $host\r\n\r\n");
                while (!feof($fp))
                {
                    $response .=  fgets ($fp);
                }
                $is_connect=true;
                fclose ($fp);
            }
        }
        if (!$is_connect) // No Socket Try Curl
        {
            $TIMEOUT = 10;
            $httpheaders = array(
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3",
            "Accept: image/gif, image/jpeg, image/pjpeg, text/plain, text/html, */*",
            "Accept-Language: en",
            "Accept-Encoding: gzip,deflate");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://".$host."/check.php");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".urlencode($key)."&hash=".urlencode($hash));
            curl_setopt($ch, CURLOPT_TIMEOUT, $TIMEOUT);
            curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, $TIMEOUT);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $TIMEOUT);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //   curl_setopt($ch, CURLOPT_HEADER, $headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
            $s=curl_exec($ch);
            curl_close ($ch);
            $response = gzdecode($s);    
            $is_connect    = true;
        }
        if ($is_connect)
        {
            //echo 'connect';
            $license_data['last_check'] = time();
            $response = strstr($response, "\r\n\r\n");
            $response = trim($response);
            $crypt = new encryption_class;
            $response = $crypt->decrypt($hash, $response);
            $resp = explode(';', $response);
            foreach($resp as $r)
            {
                $ra = explode(':',$r);
                $values[trim($ra[0])] = $ra[1];
            }
            if($values['response'] == 0)
            {
                $license_data['status']='BAD';
            } else
            {
                $license_data['status']='OK';
            }
        } else
        {
            $values['text'] = 'CONNECT ERROR!';
            if (!$no_change && $license_data['status'] !='BAD')
            $license_data['status']='ERROR';                            
        }
        if ($license_data['status']=='BAD'){
            if (!isset($values['text']) or empty($values['text'])){
                $license_data['text'] = 'Unavailable connect to the server!';
                }
                else $license_data['text'] = $values['text'];
        }
        $license_data['value']=$values;
        return $license_data;
    }
    
    function CheckMegaLicense($license_num = '',$license_str = '',$license_hesh = '', $product=0)
    {        
        $license_data=array();
        $cr = new encryption_class;      
        $is_check=false;	
        if($license_str)    //≈сть ли данные последней проверки?
        {                  //если есть - то провер€ем состо€ние последней проверки и выставл€ем $is_check
            //если необходимо выполнить запрос на сервер
            //$str = 'last_check=1245043635;status=OK;error_time=0';           
            $t = $cr->decrypt($license_hesh, $license_str);            
            if( isset($t) && strrchr($t,'=') && strrchr($t,';')) 
            {
                $s=explode(";",$t);   
                if($s[0]!=$t){
                    foreach($s as $v)
                    {
                        $it=explode('=', $v);                
                        $license_data[$it[0]]=$it[1];
                    }   
                }
            }
            if (!isset($license_data['status']))
                {
                    $license_data['status'] = 'ERROR';
                    $license_data['last_check'] = 0;
                    $is_check=true;
                }
                if($license_data['status']=='OK')
                {
                    $license_data['error_time']=0;
                }
                if($license_data['status']=='OK' && ($license_data['last_check']+LICENSE_TIME<time()))
                {
                    $is_check=true;
                } elseif($license_data['status']=='BAD')                
                {
                    $is_check=true;
                } elseif($license_data['status']=='ERROR')
                {
                    $license_data['error_time']=time()-$license_data['last_check']+1000000;
                    if ($license_data['error_time']>LICENSE_DIE_TIME)
                    {
                        $license_data['status']='BAD';
                        $no_change=true;
                    } else
                    {
                        if (!isset($license_data['next_time_check']) || $license_data['next_time_check']<time() || empty($license_data['next_time_check']))
                        {
                            $is_check=true;
                            $license_data['next_time_check']=time()+($license_data['error_time']/10)+LICENSE_CHECK_ERROR_NEXT;
                        }
                    }
                }
            
        } 
        else $is_check=true;
      //  echo "<hr>is_check = ".(int)$is_check."<hr>";
        //≈сли необходимо делать проверку
        $domain = $_SERVER['SERVER_NAME'];    
        $license = $license_num;
        $values = array();
        $hash = md5(time().rand(0,9999));    
        $key = "host=$domain&license=$license&product=$product";       
        $key = $cr->encrypt($hash,$key,strlen($key));  
         if($is_check)
        {
            $domain = $_SERVER['SERVER_NAME'];                     
            $host = LICENSE_CHECK_HOST;
            $port = 80;
            
            $is_connect=false;
            if (function_exists('fsockopen')) //fsockopen
            {
                $fp  = @fsockopen($host, $port, $errno, $errstr, 10);
                if (!$fp)
                {
                    $is_connect = false;
                } else
                {
                    $response='';
                    fputs($fp, "GET /check.php?key=".urlencode($key)."&hash=".urlencode($hash)." HTTP/1.0\r\n");
                    fputs($fp, "Host: $host\r\n\r\n");
                    while (!feof($fp))
                    {
                        $response .=  fgets ($fp);
                    }
                    $is_connect=true;
                    fclose ($fp);
                }
            }
            if (!$is_connect) // No Socket Try Curl
            {
                $TIMEOUT = 10;
                $httpheaders = array(
                "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3",
                "Accept: image/gif, image/jpeg, image/pjpeg, text/plain, text/html, */*",
                "Accept-Language: en",
                "Accept-Encoding: gzip,deflate");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://".$host."/check.php");
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".urlencode($key)."&hash=".urlencode($hash));
                curl_setopt($ch, CURLOPT_TIMEOUT, $TIMEOUT);
                curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, $TIMEOUT);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $TIMEOUT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             //   curl_setopt($ch, CURLOPT_HEADER, $headers);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
                $s=curl_exec($ch);             
                curl_close ($ch);
                if (isset($s) && $s!=''){
                    $response = gzdecode($s);    
                    $is_connect    = true;
                }
                else {
                $is_connect = false;
                $no_change = false;
                }
            }
            if ($is_connect)
            {
                //echo 'connect';
                $license_data['last_check'] = time();
                $response = strstr($response, "\r\n\r\n");
                $response = trim($response);
                $crypt = new encryption_class;
                $response = $crypt->decrypt($hash, $response);
                $resp = explode(';', $response);     
                foreach($resp as $r)
                {
                    $ra = explode(':',$r);
                    $values[trim($ra[0])] = $ra[1];
                }
                if($values['response'] == 0)
                {
                    $license_data['status']='BAD';
                } else
                {
                    $license_data['status']='OK';
                }
            } else
            {
                $values['text'] = 'CONNECT ERROR!';
                if(!isset($license_data['status'])) $license_data['status'] = 'BAD';
                if (!$no_change && $license_data['status'] !='BAD'){
                $license_data['status']='ERROR';
                if(!isset($license_data['last_check'])) $license_data['last_check']=time();
                }
            }
        }
        if ($license_data['status']=='BAD'){
            if (!isset($values['text']) or empty($values['text'])){
                $license_data['text'] = 'Unavailable connect to the server!';
                }
                else $license_data['text'] = $values['text'];
        }
        
        $put = array();
        foreach($license_data as $key => $value)
        {
            $put[] = implode('=', array($key,$value));
        }
        
        
        $fData = implode(';', $put);
        $license_data['cript'] = $cr->encrypt($hash, $fData, strlen($fData)); 
        $license_data['hash'] = $hash;
        return $license_data;
    }
    
    function gzdecode($s)
{
	if (substr($s,0,3)!="\x1f\x8b\x08")
	{
		return $s;
	} else
		{
			return gzinflate(substr($s,10));
		}
}

    class encryption_class 
    { 
        var $scramble1;
        var $scramble2;
        var $errors;
        var $adj;
        var $mod;
        function encryption_class () 
        { 
            $this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
            $this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';
            if (strlen($this->scramble1) <> strlen($this->scramble2))
            { 
                trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR); 
            }
            $this->adj = 1.75;
            $this->mod = 3;
        }
        function encrypt ($key, $source, $sourcelen=0) 
        {
            $this->errors = array();
            $fudgefactor = $this->_convertKey($key); 
            if ($this->errors) return; 
            if (empty($source))
            { 
                $this->errors[] = 'No value has been supplied for encryption'; 
                return; 
            }
            while (strlen($source) < $sourcelen)
            { 
                $source .= ' '; 
            }
            $target  = NULL; 
            $factor2 = 0;
            for ($i = 0; $i < strlen($source); $i++)
            {
                $char1 = substr($source, $i, 1); 
                $num1 = strpos($this->scramble1, $char1); 
                if ($num1 === false)
                { 
                    $this->errors[] = "Source string contains an invalid character ($char1)"; 
                    return; 
                }
                $adj = $this->_applyFudgeFactor($fudgefactor);
                $factor1 = $factor2 + $adj;
                $num2    = round($factor1) + $num1;
                $num2    = $this->_checkRange($num2);
                $factor2 = $factor1 + $num2;
                $char2 = substr($this->scramble2, $num2, 1); 
                $target .= $char2;
            } 
            return $target; 
        }
        function _convertKey ($key) 
        { 
            if (empty($key))
            { 
                $this->errors[] = 'No value has been supplied for the encryption key'; 
                return; 
            }
            $array[] = strlen($key);
            $tot = 0;  
            for ($i = 0; $i < strlen($key); $i++)
            {
                $char = substr($key, $i, 1); 
                $num = strpos($this->scramble1, $char); 
                if ($num === false)
                { 
                    $this->errors[] = "Key contains an invalid character ($char)"; 

                    return; 
                } // if 
                $array[] = $num; 
                $tot = $tot + $num;
            }
            $array[] = $tot; 
            return $array; 
        } 
        function _applyFudgeFactor (&$fudgefactor) 
        {
            $fudge = array_shift($fudgefactor);
            $fudge = $fudge + $this->adj;
            $fudgefactor[] = $fudge;
            if (!empty($this->mod)) 
            {
                if ($fudge % $this->mod == 0)
                { 
                    $fudge = $fudge * -1;
                }
            }
            return $fudge;  
        }
        function _checkRange ($num) 
        {
            $num = round($num);
            $limit = strlen($this->scramble1); 
            while ($num >= $limit)
            { 
                $num = $num - $limit; 
            }
            while ($num < 0)
            { 
                $num = $num + $limit; 
            }
            return $num; 
        }
        function decrypt ($key, $source) 
        { 
            $this->errors = array();
            $fudgefactor = $this->_convertKey($key); 
            if ($this->errors) return; 
            if (empty($source))
            { 
                $this->errors[] = 'No value has been supplied for decryption'; 
                return; 
            }
            $target  = NULL; 
            $factor2 = 0; 
            for ($i = 0; $i < strlen($source); $i++)
            {
                $char2 = substr($source, $i, 1); 
                $num2  = strpos($this->scramble2, $char2); 
                if ($num2 === false)
                { 
                    $this->errors[] = "Source string contains an invalid character ($char2)"; 
                    return; 
                }
                $adj = $this->_applyFudgeFactor($fudgefactor); 
                $factor1 = $factor2 + $adj;
                $num1    = $num2 - round($factor1);
                $num1    = $this->_checkRange($num1);
                $factor2 = $factor1 + $num2;
                $char1 = substr($this->scramble1, $num1, 1); 
                $target .= $char1;
            }
            return rtrim($target); 
        } 
    }
?>
