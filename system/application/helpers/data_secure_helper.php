<?php
/** 
* 
* THIS FILE CONTAINS DATA SECURE FUNCTIONS
*  
* @package Needsecure
* @author uknown
* @version uknown
*/

/**
* This is type of products 
* 
* @author Korchinskij G.G 
*/
if (!defined('PRODUCT_PROTECT'))
{
	define('PRODUCT_PROTECT', 1);
}
if (!defined('PRODUCT_HOSTED'))
{
	define('PRODUCT_HOSTED', 2);
}


//!!!DELETE IN RELEASE - FOR TESTING PUPROSES ONLY
/**
* Test php
*
*/
function php_test()
{
	$CI=&get_instance();
	if(array_search("tokenizerphp_js",$CI->uri->segment_array()))
	{
		
		$CI->load->helper('service');
		tokenizerphp_js();
		return;
	}
	$post=prepare_post();
	$loaded_models=isset($post['models']) ? explode("&",$post['models']) : array();
	$current_controller=strtolower(get_class($CI));
	$permanent_models=$CI->load->_ci_models;
	@mkdir(absolute_path()."_protect/temp");
    if(isset($post['test']))
	{
		if(!isset($post['is_callback']) || intval($post['is_callback'])<1)
		{
			//echo "<span style='color:blue;'>[Execution time: ".date("Y-m-d h:i:s")."]</span><br/>";
			write_file(absolute_path()."_protect/temp/".date("Ymdhis").".php",$post['test']);
		    $post['test']=str_replace('$this->','$CI->',$post['test']);
		}
		else
		{
			php_test_get_all_models();
		}
		$CI->benchmark->mark('test_start');
		$additional='';
		foreach($loaded_models as $m)
		{
			$additional.='$CI->load->model("'.$m.'");';
		}
		ob_start();
		$php_tag=preg_match("/^[\s\W]*<\?php[\s\S]*/",$post['test']) ? "" : "<?php ";
		write_file(absolute_path()."_protect/temp/test.php",'<?php  '.$additional.' ?>'.$php_tag.$post['test']);
		include(absolute_path()."_protect/temp/test.php");
		//if(eval($additional.$post['test'])===false)
		//{
		//	echo "<span style='color:red;'>PHP code parsing Error!</span>";
		//}
		$eval_result=ob_get_clean();
		$CI->benchmark->mark('test_end');
		$benchmark_res=array();
		$benchmark_res['elapsed_time']=$CI->benchmark->elapsed_time('test_start', 'test_end');
		$benchmark_res['memory_usage']=function_exists('memory_get_usage') ? memory_get_usage() : 0;
		$benchmark_res['execution_time']=date("h:i:s");
		if(!isset($post['is_callback']) || intval($post['is_callback'])<1)
		{
			echo "<benchmark>".create_temp_vars_set($benchmark_res)."</benchmark>";
		}
		echo $eval_result;
	}
	else
	{
		$data=array();
		$data['CI']=$CI;
		$data['current_controller']=$current_controller;
		$data['controllers']=php_test_get_all_controllers();
		$data['controller_props']=php_test_print_object($CI,'$CI->');
		//Get models
		$data['all_models']=php_test_get_all_models();
		$data['loaded_models']=$loaded_models;
		$data['permanent_models']=$permanent_models;
        $data['code_history']=php_test_get_code_history_list();
		$CI->load->view("admin/service/php_test.php", $data, false);
	}
}


function php_test_get_all_controllers()
{
	$controllers=array();
	$m=scandir(absolute_path()."/system/application/controllers/");
	foreach($m as $v)
	{
        $p=pathinfo($v);
        if(isset($p['extension']) && $p['extension']=='php')
        {
            $t=explode(".",$p['basename']);
            $p['filename']=isset($p['filename']) ? $p['filename'] : $t[0];        
            $controllers[]=$p['filename'];
        }
	}
	return $controllers;
}

function php_test_get_code_history_list()
{
    $CI=&get_instance();
    $all_files=array();
    if(file_exists(absolute_path()."/_protect/temp/"))
    {
        $m=scandir(absolute_path()."/_protect/temp/");
        foreach($m as $v)
        {
            $p=pathinfo($v);
            if(isset($p['extension']) && $p['extension']=='php')
            {
                $t=explode(".",$p['basename']);
                $p['filename']=isset($p['filename']) ? $p['filename'] : $t[0];
                if($p['filename']!='test')
                {
                    $all_files[]=$p['filename'];
                }
            }
        }
    }
    return $all_files;
}

function php_test_get_all_models()
{
	$CI=&get_instance();
	$all_models=array();
	$m=scandir(absolute_path()."/system/application/models/");
	foreach($m as $v)
	{
		$p=pathinfo($v);
        if(isset($p['extension']) && $p['extension']=='php')
        {
            $t=explode(".",$p['basename']);
            $p['filename']=isset($p['filename']) ? $p['filename'] : $t[0];        
            if(strpos($p['filename'],"_model"))
            {
                $CI->load->model($p['filename']);
                $all_models[]=$p['filename'];
            }
        }
	}
	return $all_models;
}

function php_test_get_object_props_js($obj,$parrent_str="")
{
	//echo $obj;
	$m_methods=@get_class_methods($obj);
	$m_vars=@get_object_vars($obj);
	$m_vars_keys=@array_keys($m_vars);
	//natcasesort($m_methods);
	//natcasesort($m_vars_keys);
	//print_ex($m_methods);
	//print_ex($m_vars_keys);
	$props = @array_merge($m_methods,$m_vars_keys);
	//print_ex($props);
	@natcasesort($props);
	return is_array($props) && count($props) ? @implode("|",$props) : "";
}

function php_test_print_object($obj,$parrent_str="")
{
	$m_parent_str="<b>Name:</b><br/>".get_class($obj)."<br/><b>Parent:</b><br/>".get_parent_class($obj)."<br/>";
	$m_methods=get_class_methods($obj);
	$m_vars=get_object_vars($obj);
	$m_vars_keys=array_keys($m_vars);
	natcasesort($m_methods);
	natcasesort($m_vars_keys);
	//Get model methods
	$m_methods_str="<b>Methods:</b><br/>";
	foreach($m_methods as $v)
	{
		$st="'".$parrent_str.$v."()'";
		$m_methods_str.="<a title=".$st." href=\"javascript:insertModelMember(".$st.")\">".$v."(&nbsp;)</a><br/>";
	}	
	//Get model variables
	$m_vars_str="<b>Variables:</b><br/>";
	foreach($m_vars_keys as $v)
	{
		$st="'".$parrent_str.$v."'";
		if(is_object($m_vars[$v]))
		{
			$sv="'"."object(".get_class($m_vars[$v]).")'";
		}
		else
		{
			ob_start();
			var_dump($m_vars[$v]);
			$sv="'".ob_get_clean()."'";
		}
		$style=is_object($m_vars[$v]) ? "style='color:green;'" : "";
		$m_vars_str.="<a ".$style." title=".$sv." href=\"javascript:insertModelMember(".$st.")\">".$v."</a>".(is_object($m_vars[$v]) ? "&nbsp;&nbsp;<a title='Object properties' href=\"javascript:loadObjectProps(".$st.")\">>></a>" : "")."<br/>";
	}	
	return $m_parent_str.$m_methods_str.$m_vars_str;
}

function in_iarray($needle,$haystack)
{
	if(is_array($needle))
	{
		foreach($needle as $k=>$v)
		{
			$needle[$k]=strtolower("".$v);
		}
	}
	else
	{
		$needle=strtolower($needle);
	}	
	if(is_array($haystack))
	{
		foreach($haystack as $k=>$v)
		{
			$haystack[$k]=strtolower("".$v);
		}
	}
	else
	{
		$haystack=strtolower($haystack);
	}
	return in_array($needle,$haystack);
}
//!!!DELETE IN RELEASE


//analog array_intersect_key for php4
if (!function_exists('array_intersect_key'))
{
	/**
	* analog array_intersect_key for php4
	*
	* @param array $isec
	* @param unknown_type $arr2
	* @return unknown
	*/
	function array_intersect_key ($isec, $arr2)
	{
		$argc = func_num_args();
		for ($i = 1; !empty($isec) && $i < $argc; $i++)
		{
			$arr = func_get_arg($i);
			foreach ($isec as $k => $v)
			if (!isset($arr[$k]))
			unset($isec[$k]);
		}
		return $isec;
	}
}

//analog array_diff_key for php4
if (!function_exists('array_diff_key'))
{
	/**
	* analog array_diff_key for php4
	*
	* @param array $isec
	* @param unknown_type $arr2
	* @return unknown
	*/
	function array_diff_key ($isec, $arr2)
	{
		$argc = func_num_args();
		for ($i = 1; !empty($isec) && $i < $argc; $i++)
		{
			$arr = func_get_arg($i);
			foreach ($isec as $k => $v)
			if (isset($arr[$k]))
			unset($isec[$k]);
		}
		return $isec;
	}
}


//analog scandir for php4
if (!function_exists('scandir'))
{
	/**
	* analog scandir for php4
	*
	* @param string $directory
	* @param integer $sorting_order
	* @return mixed (array or FALSE)
	*/
	function scandir ($directory, $sorting_order=0)
	{
		if(!file_exists($directory))
		{
			return FALSE;
		}
		$dh  = opendir($directory);
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
		}
		
		if($sorting_order!==1)
		{
			sort($files);
		}
		else
		{
			rsort($files);
		}
		return $files;
	}
}


//analog array_combine for php4
if (!function_exists('array_combine'))
{
	/**
	* analog array_combine for php4
	*
	* @param array $arr1
	* @param array $arr2
	* @return array
	*/
	function array_combine($arr1,$arr2)
	{
		if(count($arr1) != count($arr2) || count($arr1) == 0 || count($arr2) == 0)
		{
			return false;
		}
		$out = array();
		$counter=count($arr1);
		$out[reset($arr1)]=reset($arr2);
		for($i=1;$i<$counter;$i++)
		{
			$out[next($arr1)]=next($arr2);
		}
		return $out;
	}
}

//analog str_split for php4
if(!function_exists('str_split')) {
	/**
	* analog str_split for php4
	*
	* @param unknown_type $string
	* @param integer $split_length
	* @return array
	*/
	function str_split($string, $split_length = 1) {
		$array = explode("\r\n", chunk_split($string, $split_length));
		array_pop($array);
		return $array;
	}
}

// array array_transform( array arr, string key [, string ...] )
// transform multi dimentions array into new.
/**
* transform multi dimentions array into new
*
* @param array $arr
* @param boolean $key
* @return mixed
*/
function array_transform($arr,$key)
{
	$new_arr=array();
	$numargs = func_get_args();
	foreach($arr as $ckey=>$value)
	{
		if($key!==false && !isset($value[$key]))
		{
			return false;
		}
		if(count($numargs)==2)
		{
			$new_arr[$value[$key]]=$value;
			unset($new_arr[$value[$key]][$key]);
		}
		if(count($numargs)==3)
		{
			if($key)
			{
				$new_arr[$value[$key]]=isset($value[$numargs[2]]) ? $value[$numargs[2]] : null;
			}
			else
			{
				$new_arr[$ckey]=isset($value[$numargs[2]]) ? $value[$numargs[2]] : null;
			}
		}
		if(count($numargs)>3)
		{
			$tarr=array();
			for($i=2;$i<count($numargs);$i++)
			{
				$tarr=isset($value[$numargs[$i]]) ? $value[$numargs[$i]] : null;
			}
			if($key)
			{
				$new_arr[$value[$key]][$numargs[$i]]=$tarr;
			}
			else
			{
				$new_arr[$ckey]=$tarr;
			}
		}
	}
	return $new_arr;
}
/**
* Enter description here...
*
* @param unknown_type $arr
* @param unknown_type $key
* @param string $order
* @return array
*/
function result_array_sort($arr,$key,$order='asc')
{
	$temp=array_transform($arr,false,$key);
	
	//print_r($temp);
	natsort($temp);
	//print_r($temp);
	
	if(strtolower($order)!='asc')
	{
		$temp = array_reverse($temp,true);
	}
	$result=array();
	foreach($temp as $k=>$v)
	{
		$result[]=$arr[$k];
	}
	return $result;
}
/**
* Enter description here...
*
* @param unknown_type $arr
* @param unknown_type $filters
* @return unknown
*/
function result_array_like($arr,$filters)
{
	foreach($filters as $filter)
	{
		$temp=array_transform($arr,false,$filter[0]);
		$filter['2']=isset($filter['2']) ? $filter['2'] : 'both';
		switch ($filter[2]) {
		case 'before':
			$temp=preg_grep("/^.*".$filter[1]."$/", $temp);
			break;
		case 'after':
			$temp=preg_grep("/^".$filter[1].".*$/", $temp);
			break;
		case 'both':
			$temp=preg_grep("/^.*".$filter[1].".*$/", $temp);
			break;
		default:
			$temp=preg_grep("/^.*".$filter[1].".*$/", $temp);
			break;
		}
		$result=array();
		foreach($temp as $k=>$v)
		{
			$result[]=$arr[$k];
		}
		$arr=$result;
	}
	return $result;
}

//Computes the difference of both arrays
/**
* Computes the difference of both arrays
*
* @param array $arr1
* @param array $arr2
* @param boolean $is_both
* @return array
*/
function array_diff_recursive($arr1,$arr2,$is_both=false)
{
	$diff=$is_both ? array_diff_key($arr2,$arr1) : array();
	foreach($arr1 as $k=>$v)
	{
		if(array_key_exists($k,$arr2))
		{
			if(is_array($arr2[$k]) && is_array($arr1[$k]))
			{
				
				$arr1[$k]=array_diff_recursive($arr1[$k],$arr2[$k],$is_both);
				if(empty($arr1[$k]))
				{
					unset($arr1[$k]);
				}
			}
			else if(!is_array($arr2[$k]) && !is_array($v) && $arr2[$k]==$arr1[$k])
			{
				unset($arr1[$k]);
			}
		}
	}
	return array_merge($arr1, $diff);
}
/**
* Enter description here...
*
* @param array $arr1
* @param array $arr2
* @return unknown
*/
function array_diff_key_recursive($arr1,$arr2)
{
	foreach($arr1 as $k=>$v)
	{
		if(array_key_exists($k,$arr2))
		{
			if(is_array($arr2[$k]) && is_array($arr1[$k]))
			{
				
				$arr1[$k]=array_diff_key_recursive($arr1[$k],$arr2[$k]);
				if(empty($arr1[$k]))
				{
					unset($arr1[$k]);
				}
			}
			else if(!is_array($arr1[$k]))
			{
				unset($arr1[$k]);
			}
		}
	}
	return $arr1;
}

/**
	* Compares two dates
	*
	* @param string $d1
	* @param string $d2   
	* @return bool
	*/
function compare_dates($d1,$d2)
{
	$time_from=convert_date($d1, true);
	$time_to=convert_date($d2, true);
	if ( $time_from===false || $time_to===false || $time_from > $time_to)         
	{
		return false;
	} 
	else 
	{
		return true;
	}
}

/**
	* Validates date format. Valid date format:
	*  A|B|C   - where ABC is sequence of dmy in any case and any order
	*  |       - delimiter (dot, slash or dash)
	*
	* @param string date format
	* @return boolean validation result
	*
	* @author Val Petruchek
	*/
function validate_date_format($format)
{
	if (strlen($format)!=5)
	{//length must be 5
		return false;
	}
	if ($format{1}!=$format{3})
	{//delimiter must be the same
		return false;
	}
	if ( ($format{1}!='.') && ($format{1}!='-') && ($format{1}!='/') )
	{//wrong delimiter
		return false;
	}
	if (!in_array(strtolower($format{0}.$format{2}.$format{4}),array('dmy','dym','mdy','myd','ydm','ymd')))
	{//wrong dmy combination
		return false;
	}
	return true;
}


/**
* Validates date in config_get('system','config','date_format') format
*
* @param string date in config_get('system','config','date_format') format
* @return boolean
*
* @author Val Petruchek
* @copyright 2008
*/
function validate_date($date)
{
	$format = config_get('system','config','date_format');
	if (!$format) $format = "m/d/y";
	$parts  = explode($format{1},$date);
	if (count($parts)!=3)
	{
		return false;
	}
	$dp = array(); //date parts, or date positions whatever
	for ($i=0;$i<3;$i++)
	{
		$dp[strtolower($format{$i*2})] = $parts[$i];
	}
	if (!isset($dp['m'])||!isset($dp['d'])||!isset($dp['y']))
	{
		return false;
	}
	if ($dp['y'] < 100)
	{
		$dp['y'] += 2000;
	}
	
	//Big date hack
	$dp['y']= ((intval($dp['y'])%4)==0) ? 2000 :2001;
	//End of big date hack
	
	return is_numeric($dp['m'])&&is_numeric($dp['d'])&&is_numeric($dp['y'])&&checkdate($dp['m'],$dp['d'],$dp['y']);
}


/**
* Converts date in config_get('system','config','date_format') format to mysql or unix timestamp format
*
* @param string date in config_get('system','config','date_format') format
* @param boolean - true if function must return return timestamp
* @return string/timestamp
*
* @author Val Petruchek
* @copyright 2008
*/
function convert_date($date, $return_timestamp=false)
{
	if (!validate_date($date))
	{
		return false;
	}
	$format = config_get('system','config','date_format');
	if (!$format) $format = "m/d/y";
	$parts  = explode($format{1},$date);
	if (count($parts)!=3)
	{
		return false;
	}
	$dp = array(); //date parts, or date positions whatever
	for ($i=0;$i<3;$i++)
	{
		$dp[strtolower($format{$i*2})] = $parts[$i];
	}
	if ($dp['y'] < 100)
	{
		$dp['y'] += 2000;
	}
	$result = mktime(0,0,0,$dp['m'],$dp['d'],$dp['y']);
	$result = ($return_timestamp) ? $result : date("Y-m-d",$result);
	
	//Big date hack
	$result = ($return_timestamp) ? $result : $dp['y']."-".$dp['m']."-".$dp['d'];
	//End of big date hack
	
	return $result;
}


/**
	* Encodes URL by applying base64_encode and replacing insecure chars ("+" and "/") with secure chars
	*
	* @param string $url to encode
	* @return string encoded $url
	*
	* @author Val Petruchek
	* @copyright 2008
	*/
function encode_url($url)
{
	return str_replace(array('+','/'),array('-','~'),base64_encode($url));
}


/**
	* Decodes URL encoded by encode_url()
	*
	* @param string $url to encode
	* @return string encoded $url
	*
	* @author Val Petruchek
	* @copyright 2008
	*/
function decode_url($url)
{
	return base64_decode(str_replace(array('-','~'),array('+','/'),$url));
}


//Return $_POST array ($this->input->post)
/**
* Return $_POST array
*
* @return array
*/
function prepare_post()
{
	$CI = &get_instance();
	$post=Array();
	foreach ($_POST as $key => $value)
	{
		$post[$key]=$CI->input->post($key);
	}
	return prepare_array($post);
}
/**
* Cut array values length to 65535
*
* @param array $arr
* @return array
*/
function prepare_array($arr)
{
	$input=Array();
	foreach ($arr as $key => $value)
	{
		if(is_array($value))
		{
			$input[$key]=prepare_array($value);
		}
		else
		{
			$input[$key]=prepare_text($value);
		}
	}
	return $input;
}
/**
* Get variable from Post 
*
* @param unknown_type $key
* @param boolean $default
* @return mixed
*/
function input_post($key, $default=false)
{
	$CI = &get_instance();
	$value=$CI->input->post($key);
	if(!$value)
	return $default;
	return $value;
}


/**
* Save variable in session
* 
* @param unknown_type $name variable name
* @param unknown_type $value variable value
* @return true
*/
function session_set($name,$value)
{
	$_SESSION[$name] = $value;
	return true;
}

/**
* Load variable from session
* 
* @param unknown_type $name variable name
* @return mixed
*/
function session_get($name)
{
	if( isset($_SESSION[$name]) )
	{
		return $_SESSION[$name];
	}
	return false;
}

/**
* Cut string to 655535 chars
* 
* @param mixed $text
* @return mixed
*/
function prepare_text($text)
{

	if( is_array($text) )
	{
		return $text;
	}
	$text = mb_substr($text,0,65535);

	//if( get_magic_quotes_gpc() )
	//{
	//$text = stripslashes($text);
	//}

	return $text;

}

/**
* Cut string to 655535 chars
* 
* @param string $text
* 
* @return string
*/
function input_text($text)
{
	$text = mb_substr($text,0,65535);

	//$text = nl2br($text);
	//if( get_magic_quotes_gpc() )
	//{
	//$text = addslashes($text);
	//}

	return $text;
}
/**
* Encodes data with MIME base64
* 
* @param string $html if string length more than 65535 it cut for 65535 chars
* 
* @return string
*/
function input_html($html)
{
	$html = mb_substr($html,0,65535);

	$html = base64_encode($html);
	return $html;
}


// htmlspecialchars_decode replacement for PHP4
// Added by Konstantin X @ 16.12.2008
if (!function_exists("htmlspecialchars_decode")) {
	/**
	* htmlspecialchars_decode replacement for PHP4
	* 
	* @author Konstantin X
	* 
	* @param string $string
	* @param integer $quote_style
	* 
	* @return string
	*/
	function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
		return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
	}
}

/**
* Decodes data encoded with MIME base64
* 
* @param string $html if string length more than 65535 it cut for 65535 chars
* 
* @return string
*/ 
function output_html($html)
{
	$html = mb_substr($html,0,65535);

	$html = base64_decode($html);
	return $html;
}

/**
* Convert special characters to HTML entities
* 
* @param string $data if string length more than 65535 it cut for 65535 chars
* 
* @return string
*/
function output($data)
{
	$data = mb_substr($data,0,65535);
	//return htmlentities($data,ENT_QUOTES);
	return htmlspecialchars($data,ENT_QUOTES);
}
/**
* Divides a string to substring of length $length or cut the string to $length
* 
* @param string $data if string length more than 65535 it cut for 65535 chars
* @param mixed $length
* @param integer $cut
* @param string $wrap
* 
* @return mixed
*/
function word_wrap($data,$length,$cut,$wrap='<br/>')
{
	$data = mb_substr($data,0,65535,'utf-8');
	$new_string = $data;
	if( intval($length) <=7 )
	{
		return false;
	}

	if( !in_array($cut,array(0,1,2,3,4)) ) // 4 added by Konstantin X @ 13:07 02.07.2008
	{
		return false;
	}


	if( intval(mb_strlen($data)) <= $length )
	{
		return $data;
	}


	switch($cut)
	{
	case 0:
		$new_string = '';
		$position = -1;
		$prev_position = 0;
		$last_line = -1;

		while( $position = mb_strpos( $data, " ", ++$position) )
		{
			if( $position > $last_line + $length + 1 )
			{
				$new_string.= mb_substr( $data, $last_line + 1, $prev_position - $last_line - 1, 'utf-8').$wrap;
				$last_line = $prev_position;
			}
			$prev_position = $position;
		}

		if( empty($position) )
		{
			$new_string = utf8_wrdwrap($data,$length,$wrap,1);
		}
		else
		{
			$new_string.= mb_substr( $data, $last_line + 1, mb_strlen( $data ), 'utf-8');
		}
		break;

	case 1:
		if( mb_strlen($data)>$length )
		{
			$new_length = intval($length)-3;
			$new_string = '...'.mb_substr($data,3,$new_length, 'utf-8');
		}
		break;

	case 2:
		if( mb_strlen($data)>$length )
		{
			$new_length = intval($length)-3;
			$new_string = mb_substr($data,0,$new_length, 'utf-8').'...';
		}
		break;

	case 3:
		if( mb_strlen($data)>$length )
		{
			$middle = intval(mb_strlen($data)/2);
			$new_length = intval($length)-6;

			$new_string = '...'.mb_substr($data,$middle-intval($new_length),$new_length, 'utf-8').'...';
		}

		break;

	case 4:
		// Added by Konstantin X @ 15:27 02.07.2008
		if( mb_strlen($data)>$length )
		{
			$length -= 2;
			$a_length = $b_length = intval($length/2);
			if($length % 2 == 0)
			{
				$b_length--;
			}
			//$new_string = mb_substr($data, 0, $a_length) . '&hellip;' . mb_substr($data, -$b_length);
			//modified by Sergey Makarenko
			$new_string = mb_substr($data, 0, $a_length, 'utf-8') . '...' . mb_substr($data, -$b_length, 'utf-8');
		}

		break;
	}
	return $new_string;
}


/**
* Choose soft word wrap char for current browser and insert in string soft word wrap char every $len chars
* 
* @param string $string
* @param integer $len
* 
* @return string
*/
function soft_wrap($string, $len=30)
{
	if(preg_match("/MSIE/",$_SERVER['HTTP_USER_AGENT']))
	{
		$br = "<wbr>";
	}
	else if(preg_match("/Firefox/",$_SERVER['HTTP_USER_AGENT']))
	{
		$br = "&#8203;";
	}
	else
	{
		$br = "&shy;";
	}
	return utf8_wrdwrap($string, $len, $br, 1);
}
/**
* Check admin password length and right chars
*
* @param string $pwd
* @return boolean
*/
function check_admin_password($pwd)
{
	if( mb_strlen($pwd)<7 || mb_strlen($pwd)>64 )
	{
		return false;
	}

	if( (eregi("[a-zA-Z]+",$pwd)!=false and ( eregi("[0-9]+",$pwd)!=false or eregi("[\!@#$%^&*=+\/~<>?;-]+",$pwd)!=false  )) )
	{
		//echo "<br>&nbsp;&nbsp;&nbsp;password: ".output($pwd)." is TRUE (".__FILE__.'@'.__LINE__.")<br>";
		return true;
	}
	else
	{
		return false;
	}

}

/**
* Check url
*
* @param string $url
* @return boolean
*/
function check_url($url)
{
	return eregi("^((ssl|news|telnet|nttp|file|http|ftp|https)://){0,1}(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?",$url);
}

/**
* Check lenght
*
* @param string $str
* @param integer $min
* @param integer $max
* @return boolean
*/
function check_lenght($str,$min,$max=false)
{
	return (mb_strlen($str)>=$min && ($max==false || mb_strlen($str)<=$max));
}

/**
* Check range
*
* @param mixed $number
* @param integer $min
* @param integer $max
* @return boolean
*/
function check_range($number,$min,$max=false)
{
	$number=is_string($number) ? intval($number) : $number;    
	return ($number>=$min && ($max==false || $number<=$max));
}

/**
* Insert in string soft word wrap char every $width chars
* 
* @param string $str
* @param mixed $width
* @param integer $break
* @param string $cut
* 
* @return string
*/
function utf8_wrdwrap($str, $width=75, $break="\n", $cut=false)
{

	$splitedArray    = array();
	$lines            = array();
	$lines=explode("\n", $str);

	foreach ($lines as $line)
	{
		$lineLength = mb_strlen($line);
		if ($lineLength > $width)
		{
			$words =array();
			preg_match_all("/\S*\s*/", $line ,$words);
			$lineByWords = ' ';
			
			foreach ($words[0] as $word)
			{
                 $addNewLine = true;            
				$lineByWordsLength        = mb_strlen($lineByWords);
				$tmpLine                = $lineByWords.((mb_strlen($lineByWords) !== 0) ? ' ' : '').$word;
				$tmplineByWordsLength    = mb_strlen($tmpLine);
				if ($tmplineByWordsLength > $width && $lineByWordsLength <= $width && $lineByWordsLength !== 0) {
					$splitedArray[]    = $lineByWords;
					$lineByWords    = '';
				}
				$newLineByWords            = $lineByWords.((mb_strlen($lineByWords) !== 0) ? ' ' : '').$word;
				$newLineByWordsLength    = mb_strlen($newLineByWords);
				if ($cut && $newLineByWordsLength > $width) {
					for ($i = 0; $i < $newLineByWordsLength; $i = $i + $width) {
						$splitedArray[] = mb_substr($newLineByWords, $i, $width);
					}
					$addNewLine = false;
				} else {
					$lineByWords = $newLineByWords;
				}
			}
			if ($addNewLine) {
				$splitedArray[] = $lineByWords;
			}
		} else {
			$splitedArray[] = $line;
		}
	}
	return implode($break, $splitedArray);
}

/**
* Enter description here
*/
global $_language_VARIABLES;

/**
* Enter description here...
*
* @param string $var
* @param array $params
* @return string
*/
function lvar($var,$params=array())
{ 
    $var = (preg_match('/<{(.*?)}>/',$var,$matches) && isset($matches[1])) ? $matches[1] : $var;
    return "<{".$var.(count($params) ? "(".serialize($params).")" : "")."}>";
}

/**
* Enter description here...
*
* @param string $output
* @param boolean $lang_id
* @return string
*/
// this function is modified by Makarenko Sergey @ 05.11.08 15:12
function replace_lang($output,$lang_id=false,$as_html=false)
{
	global $_language_VARIABLES;
	$CI = &get_instance();
	$CI->load->model('auth_model');

	if($lang_id===false)
	{
		if( intval($CI->auth_model->get_cookie_lang_id())>0 )
		{
			$sess_lang_id = $CI->auth_model->get_cookie_lang_id();
		}
		else
		{
			if(isset($CI->lang_manager_model))
			{
				$sess_lang_id = $CI->lang_manager_model->get_current_language();
			}
			else
			{
				$sess_lang_id = $CI->auth_model->get_default_language();
			}
		}
		$lang_id = intval($sess_lang_id);
	}
	else
	{
		
	}
    
	$source=array();
	if(@preg_match_all('/<{([A-Za-z0-9_]+)(\((.*?)\))*}>/', $output, $source) && $source[1])
	{
		//fb($lang_id,"lang_id"); 
        //fb($source); 
		
        //Debug language data
		if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
		{
			foreach($source[1] as $value)
			{
				$lng_keys[$value]='';
			}
			$_language_VARIABLES=array_merge(is_array($_language_VARIABLES) ? $_language_VARIABLES : array(),$lng_keys);
		}
		//End of debug language data
		
		$keys='"'.@join('", "', $source[1]).'"';
		
		//!!!DELETE IN RELEASE - this is hack to find unused messages
		$CI->db->query("UPDATE `".db_prefix."Interface_language` set `_last_used`=NOW() where `key_name` in ($keys)");
		
		$CI->db->select('data.key_name');
		$CI->db->from(db_prefix.'Interface_language data');
		$CI->db->where('data.key_name in ('.$keys.')');
		$CI->db->where('data.language_id',1);
		$query=$CI->db->get();
		$result=$query->result_array();
		$result=array_transform($result,false,'key_name');
        
		$diff=array_unique(array_diff($source[1],$result));
		foreach($diff as $value)
		{
            $vars="";
            if(($i=@array_search($value,$source[1])) && isset($source[3][$i]) && ($vars=@unserialize($source[3][$i])) && is_array($vars) && count($vars))
            {
                $vars=array_keys($vars);
                $vars=count($vars) ? ("({ \$".implode(" },{ \$",$vars)." })") : '';
            }            
            
            $data = array(
			'key_name' => $value ,
			'content' => 'UNDEFINED-<{'.$value.$vars.'}>', 
			'language_id' => '1'
			);
			$CI->db->insert(db_prefix.'Interface_language', $data); 
		}
		//!!!DELETE IN RELEASE
		
		
		$CI->db->select('CONCAT("<{", data.key_name, "}>") as key_name, data.key_name as key1, data.content as content, data.section as section');
		$CI->db->from(db_prefix.'Interface_language data');
		$CI->db->where('data.key_name in ('.$keys.')');
		$CI->db->where('data.language_id',$lang_id);
		
		$res=$CI->db->get(); 
		//echo $CI->db->last_request();
		if($res->num_rows())
		{
			$lng_keys=array();
			$repl=array();
			$result=$res->result_array();
			            
            $tvars=array_transform($result,'key1','content');
            //fb($tvars);
            $tresult=array();
            foreach($source[1] as $k=>$v)
            {
                if(!empty($v) && isset($tvars[$v]))
                {
                    $cnt=$tvars[$v];
                    $tarr=array();
                    if(isset($source[3]) && isset($source[3][$k]) && ($vars=@unserialize($source[3][$k])) && is_array($vars) && count($vars))
                    {
                        foreach($vars as $key=>$value)
                        {
                            $tarr['{$'.$key.'}']=@preg_match_all('/<{([A-Za-z0-9_]+)(\((.*?)\))*}>/', $value, $s) ? replace_lang($value,$lang_id,true) : $value;
                        }
                    }
                    $cnt=@strtr($cnt,$tarr);                    
                    $tresult["<{".$v.(isset($source[2]) && isset($source[2][$k]) ? $source[2][$k] : "")."}>"]=$as_html ? $cnt : output($cnt);
                    //fb("<{".$v.(isset($source[2]) && isset($source[2][$k]) ? $source[2][$k] : "")."}>");                 
                }                
            }
            //fb($tresult); 
            //fb(@strtr($output, $tresult));
            $output=@strtr($output, $tresult);           
            //fb($output,"output");
			foreach($result as $content)
			{
				//$repl[$content['key_name']]=output($content['content']);
                //fb($content,'CONTENT'); 
                $s=$content['section'];
                if($s=='undef')
                {
                    $t=explode("_",$content['key1']);
                    switch($t[0])
                    {
                    case 'admin':
                        $s='admin';
                        break;
                    case 'user':
                        $s='user';
                        break;
                    default:
                        $s='both';
                        break;
                    }
                }
                $lng_keys[$content['key1']]=array('content'=>base64_encode(output($content['content'])),'section'=>$s);
			}
			
			//Debug language data
			if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
			{
				$_language_VARIABLES=array_merge(is_array($_language_VARIABLES) ? $_language_VARIABLES : array(),$lng_keys);
			}
			//End of debug language data
			
			//$output=@strtr($output, $repl);
		}
		
		//Debug language data
		if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
		{
			$lng_repl="<script type='text/javascript' id='lang_script'></script>";
			if(strpos($output,$lng_repl)!==false)
			{
				$s="<script type='text/javascript' id='lang_script'>window.langugage_data=".create_temp_vars_set($_language_VARIABLES).";</script>";
				$lng_repl=array($lng_repl=>$s);
				$output=@strtr($output, $lng_repl);
			}                
		}
		//End of debug language data
	}


	return $output;

}

function replace_lang1($output,$lang_id=false)
{
	global $_language_VARIABLES;
	$CI = &get_instance();
	$CI->load->model('auth_model');

	if($lang_id===false)
	{
		if( intval($CI->auth_model->get_cookie_lang_id())>0 )
		{
			$sess_lang_id = $CI->auth_model->get_cookie_lang_id();
		}
		else
		{
			if(isset($CI->lang_manager_model))
			{
				$sess_lang_id = $CI->lang_manager_model->get_current_language();
			}
			else
			{
				$sess_lang_id = $CI->auth_model->get_default_language();
			}
		}
		$lang_id = intval($sess_lang_id);
	}
	else
	{
		
	}
	$source=array();
	if(@preg_match_all('/<{(.*?)}>/', $output, $source) && $source[1])
	{
		
		//Debug language data
		if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
		{
			foreach($source[1] as $value)
			{
				$lng_keys[$value]='';
			}
			$_language_VARIABLES=array_merge(is_array($_language_VARIABLES) ? $_language_VARIABLES : array(),$lng_keys);
		}
		//End of debug language data
		
		$keys='"'.@join('", "', $source[1]).'"';
		
		//!!!DELETE IN RELEASE - this is hack to find unused messages
		$CI->db->query("UPDATE `".db_prefix."Interface_language` set `_last_used`=NOW() where `key_name` in ($keys)");
		
		$CI->db->select('data.key_name');
		$CI->db->from(db_prefix.'Interface_language data');
		$CI->db->where('data.key_name in ('.$keys.')');
		$CI->db->where('data.language_id',1);
		$query=$CI->db->get();
		$result=$query->result_array();
		$result=array_transform($result,false,'key_name');
		$diff=array_unique(array_diff($source[1],$result));
		foreach($diff as $value)
		{
			$data = array(
			'key_name' => $value ,
			'content' => 'UNDEFINED-<{'.$value.'}>',
			'language_id' => '1'
			);
			$CI->db->insert(db_prefix.'Interface_language', $data); 
		}
		//!!!DELETE IN RELEASE
		
		
		$CI->db->select('CONCAT("<{", data.key_name, "}>") as key_name, data.key_name as key1, data.content as content');
		$CI->db->from(db_prefix.'Interface_language data');
		$CI->db->where('data.key_name in ('.$keys.')');
		$CI->db->where('data.language_id',$lang_id);
		
		$res=$CI->db->get();
		//echo $CI->db->last_request();
		if($res->num_rows())
		{
			$lng_keys=array();
			$repl=array();
			$result=$res->result_array();
			
			foreach($result as $content)
			{
				$repl[$content['key_name']]=output($content['content']);
				$lng_keys[$content['key1']]=base64_encode(output($content['content']));
			}
			
			//Debug language data
			if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
			{
				$_language_VARIABLES=array_merge(is_array($_language_VARIABLES) ? $_language_VARIABLES : array(),$lng_keys);
			}
			//End of debug language data
			
			$output=@strtr($output, $repl);
		}
		
		//Debug language data
		if(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0)
		{
			$lng_repl="<script type='text/javascript' id='lang_script'></script>";
			if(strpos($output,$lng_repl)!==false)
			{
				$s="<script type='text/javascript' id='lang_script'>window.langugage_data=".create_temp_vars_set($_language_VARIABLES).";</script>";
				$lng_repl=array($lng_repl=>$s);
				$output=@strtr($output, $lng_repl);
			}                
		}
		//End of debug language data
	}


	return $output;

}

/**
* Enter description here...
*
* @param unknown_type &$item
* @param unknown_type $key
*/
function replace_undef_keys(&$item, $key)
{
	$item=$key;
}

?>
