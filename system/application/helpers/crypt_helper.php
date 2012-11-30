<?php
/**
 * 
 * THIS FILE CONTAINS CRYPT FUNCTIONS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Encrypt data
 *
 * @param string $data
 * @param string $key
 * @return string
 */
function encrypt($data, $key)
{
	require_once 'blowfish/crypt.php';
    $crypt = new pcrypt(MODE_CBC, "BLOWFISH", $key);
    return $crypt->encrypt($data);
}
/**
 * Decrypt data
 *
 * @param string $data
 * @param string $key
 * @return string
 */
function decrypt($data, $key)
{
	require_once 'blowfish/crypt.php';
    $crypt = new pcrypt(MODE_CBC, "BLOWFISH", $key);
    return $crypt->decrypt($data);
}

/**
 * Encrypt data with special for NS key
 *
 * @param unknown_type $data
 * @param unknown_type $key
 * @return unknown
 */
function ns_encrypt($data, $key)
{
	$ns = 'NS2Forever';
	if (function_exists('Functionality_enabled'))
	{ 
		if(Functionality_enabled('user_auth_product_hosted')===true)
		{
			return base64_encode(encrypt($data,$ns.$key));
		}
		else
		{ 
	        return "";
		}
	} 
	else
	{ 
	    return base64_encode(encrypt($data,$ns.$key));
	}
		
}

/**
 * Decrypt data with special for NS key
 *
 * @param string $data
 * @param string $key
 * @return string
 */
function ns_decrypt($data, $key)
{
	$ns = 'NS2Forever';
	return decrypt(base64_decode($data),$ns.$key);
}

?>
