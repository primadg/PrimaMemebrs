<?php
/**
 * 
 * THIS FILE CONTAINS GLOBAL FUNCTIONS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */

/**
 * Base URL
 *
 * Returns the "base_url" item from your config file
 *
 * @access	public
 * @return	string
 */	
 
if (! function_exists('base_url'))
{
	function base_url()
	{
		$CI =& get_instance();
		$base_url = $CI->config->slash_item('base_url'); 
		if ($base_url=='' or $base_url=='undefined')
		{
			$base_url=$_SERVER ['SCRIPT_NAME'];
			$base_url_pos=strrpos($base_url,"/");
			if ($base_url_pos===false)
				exit('An Error Was Encountered');
			$base_url=substr($base_url,0,$base_url_pos+1);
		}
		return $base_url;
	}
}
