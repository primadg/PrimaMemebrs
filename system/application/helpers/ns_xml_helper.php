<?php
/**
 * 
 * THIS FILE CONTAINS NS XML FUNCTIONS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Convert array to xml
 *
 * @param array $array
 * @param integer $flag
 * @return unknown
 */
function array_2_xml($array,$flag=0)
{
    static $count;
    static $xml;
    if( !isset($xml) )
    {
        $xml='';
    }
    
    if( isset($count) )
    {
        $count++;
    }
    else
    {
        $count = intval(0);
    }
    
    if( !is_array($array) )
    {
        return false;
    }
    
    
    foreach($array as $key=>$value)
    {
        $key = input_xml($key);
        if(!is_array($value))
        {
          $value = input_xml($value);
        }
        if( is_array($value) )
        {
            if($flag>0)
            {
                for($i=0;$i<$flag;$i++)
                {
                    $xml.="\t";
                }
            }
            $xml.= "<".trim($key).">\n";
            if($flag>0)
            {
                $new_flag=$flag+1;
            }
            else
            {
                $new_flag=1;
            }
            array_2_xml($value,$new_flag);
            if($flag>0)
            {
                for($i=0;$i<$flag;$i++)
                {
                    $xml.="\t";
                }
            }
            $xml.= "</".trim($key).">\n";
        }
        else
        {
            if($flag>0)
            {
                for($i=0;$i<$flag;$i++)
                {
                    $xml.="\t";
                }
            }
            $xml.= "<".trim($key).">";
            $xml.= $value;
            $xml.= "</".trim($key).">\n";
        }   
    }
    $return = $xml;
    if($flag<=0)$xml='';
    return $return;
}
/**
 * Convert xml to array
 *
 * @param string $data
 * @return array
 */
function xml_2_array($data)
{

    $a = 0;
    return _xml_2_array($data, $a );

}

/**
 * Convert xml to array
 *
 * @param string $data
 * @param integer &$end
 * @param string $tag
 * @return array
 */
function _xml_2_array($data, &$end, $tag='')
{
    

    $res=array();
    
    $len=mb_strlen($data);
        
        
    if( ( $start=mb_strpos($data, '<', $end) ) !== false)
    {                   
        
        if( $data{$start+1}=='/' )
        {                       
            return output_xml(mb_substr($data, $end, $start-$end));
        }
                
        
        while($end<$len && ($start=mb_strpos($data, '<', $end))!==false)
        {               
            if(mb_substr($data, $start+1, 1)==='/')
            {           
                $end=$start+1;              
                
                
                $t_end=mb_strpos($data, '>', $start+1);
                
                if($t_end!==false)
                {
                    
                    if($tag!=output_xml(mb_substr($data, $start+2, $t_end-$start-2)))
                    {
                        break;
                    }
                    
                }
                
                
                continue;
            }           
            
            if( ( $end = mb_strpos($data, '>', $start) ) !== false )
            {   
                $end++;
                $tag=$keyname=output_xml(mb_substr($data, $start+1, $end-$start-2));
                $res[$keyname]=_xml_2_array($data, $end, $tag);
            }
            else 
            
                break;  
            
        }
    }
    
    return $res;
}
/**
 * Replace #,<,>,& chars for #001,#002,#003,#004 in the string
 *
 * @param string $text
 * @return string
 */
function input_xml($text)
{
    if( function_exists('mb_eregi_replace'))
    {
        $text = mb_eregi_replace("#",'#001;',$text);
        $text = mb_eregi_replace("<",'#002;',$text);
        $text = mb_eregi_replace(">",'#003;',$text);
        $text = mb_eregi_replace("&",'#004;',$text);
    }
    else
    {
        $text = str_replace("#",'#001;',$text);
        $text = str_replace("<",'#002;',$text);
        $text = str_replace(">",'#003;',$text);
        $text = str_replace("&",'#004;',$text);
    }
    
    return $text;
}

/**
 * Split a string $data for parts of length $block_size and create an array of this parts 
 *
 * @param string $data
 * @param integer $block_size
 * @return array
 */
function split_text($data, $block_size=4095) 
{
    if( function_exists('mb_strlen'))
    {
        $len=mb_strlen($data);
        
        $start=0;
        $res=array();
        while($start < $len)
        {
            if($len - $start < $block_size)
            {
                $step=$len - $start;
            }
            else
                $step=$block_size;

            array_push($res, mb_substr($data, $start, $step));
            
            $start+=$step;          
        }
        return $res;
    }
    
    
    $len=strlen($data);
    
    $start=0;
    $res=array();
    while($start < $len)
    {
        if($len - $start < $block_size)
        {
            $step=$len - $start;
        }
        else
            $step=$block_size;

        array_push($res, substr($data, $start, $step));
        
        $start+=$step;          
    }
    return $res;
    
}

////////////////////DEBUG///////////////////
//define("DEBUG_RESPONSE_FLAG",true);

/**
 * Enter description here...
 *
 * @return array
 */
 
function debug_params()
{
    $params=array();
    $params[0]='debug';
    $params[1]='demo';
    $params[8]='trial';
    $params[5]='basic';
    $params[4]='pro';
    $params[10]='protected';
    $params[9]='hosted';
    $params[2]='translate';
    $params[3]='debug log';
    $params[6]='cron';
    $params[7]='FirePHP';
    return $params;
}
/**
 * Enter description here...
 *
 * @param boolean $pos
 * @return mixed
 */
function get_debug_params($pos=false)
{
    $s=str_split(config_get('SYSTEM','STATUS','debug_mode'));
    $params=debug_params();
    $result=array();
    $i = 0;
    foreach($params as $key=>$value)
    {
        $result[$key]=(isset($s[$i]) && intval($s[$i])>0) ? 1 : 0;
        $i++;
    }
    if($pos!==false)
    {
        return isset($result[intval($pos)]) ? $result[intval($pos)] : 0;
    }
    else
    {
        return $result;
    }
}
/**
 * Enter description here...
 *
 * @param mixed $post
 * @param boolean $val
 * @return mixed
 */
function set_debug_params($post,$val=false)
{
    $params=get_debug_params();

    if(is_array($post))
    {
        foreach($params as $key=>$value)
        {
            $n=(isset($post[''.$key]) && intval($post[''.$key])>0) ? 1 : 0;
            $params[$key]=isset($post[''.$key]) ? $n : $params[$key];
        }
    }
    else if(isset($params[intval($post)]))
    {
        $params[intval($post)]=($val===false) ? (intval($params[intval($post)])>0 ? 0 :1) :(intval($val)>0 ? 1 : 0);    
    }
    return config_set(implode("",$params),'SYSTEM','STATUS','debug_mode');
}
/**
 * Enter description here...
 *
 * @param mixed $var
 * @param unknown_type $name
 * @return mixed
 */
function debug_response($var=false,$name=false)
{
    static $debug_info="";
    if(defined('DEBUG_RESPONSE_FLAG'))
    {
        if($var==false)
        {
            return $debug_info;
        }
        ob_start();
        echo ">>> ".$name."\n";
        var_dump($var);
        echo "\n";
        $debug_info.= ob_get_contents();
        ob_end_clean();
    }
}
/////////////////////////////////////////////

/**
 * Enter description here...
 *
 * @param string $name
 * @param string $value
 * @param boolean $is_error
 * @param string $error_text
 * @return boolean
 */
 
function validation_response($name,$value,$is_error,$error_text)
{   
    if(isset($name)&&isset($value)&& isset($is_error))
    {
        $str="{'".$name."':{'value':'".base64_encode($value)."','is_error':".($is_error?"true":"false").((isset($error_text)&&$error_text!="")?(",'error_text':'".$error_text)."'":"")."}}";        
        make_response("message", $str, 1);
        return true;
    }
    else
    {
        return false;
    }    
}
/**
 * Enter description here...
 *
 * @global unknown_type 
 * @param string $status
 * @param string $response
 * @param integer $header_print
 * @param string $additional
 * @return string
 */
function make_response($status,  $response,  $header_print=0,   $additional='')
{       
    global $_language_VARIABLES;
    $array = array();
    $xml = '';
    
    $response=replace_lang($response);       
    $response=input_xml($response);
    
    $additional=replace_lang($additional);
    $additional=input_xml($additional);
        
    $xml=
"<?xml version='1.0' encoding='UTF-8'?>
<response>
    <status>$status</status>";
   
    $response=split_text($response);
        
    
    foreach($response as $resp)
    {           
        $xml.=(
"
<content>$resp</content>
");
    }
    
    if($additional)
    {
        $xml.=(
"
<additional>$additional</additional>
");
    }
    
    if(is_array($_language_VARIABLES))
    {
        $lngs=create_temp_vars_set($_language_VARIABLES);
        $xml.=(
            "
<langugage_data>$lngs</langugage_data>
");        
    }
    
////////////////////DEBUG///////////////////
   $debug_info=debug_response();
    if($debug_info!="" && defined('DEBUG_RESPONSE_FLAG') && get_debug_params(3)>0)
    {
        $xml.=(
"
<debug>$debug_info</debug>
");
    }
/////////////////////////////////////////////
    
        
        $xml.=(
"
</response>");

    if(intval($header_print)==1)
    {           
        header("Content-type: text/xml");   
        echo $xml;
        return "";
    }
    return $xml;
}
/**
 * Replace #001,#002,#003,#004 chars for #,<,>,& in the string
 *
 * @param string $text
 * @return string
 */
function output_xml($text)
{

    if( function_exists('mb_eregi_replace'))
    {
        $text = mb_eregi_replace("#001;",'#',$text);
        $text = mb_eregi_replace("#002;",'<',$text);
        $text = mb_eregi_replace("#003;",'>',$text);
        $text = mb_eregi_replace("#004;",'&',$text);
    }
    else
    {
        $text = str_replace("#001;",'#',$text);
        $text = str_replace("#002;",'<',$text);
        $text = str_replace("#003;",'>',$text);
        $text = str_replace("#004;",'&',$text);        
    }
        
    return $text;
}
/**
 * Convert array to json
 *
 * @param array $array
 * @return string
 */
function array_to_json($array=array())
{    
    $str="{ ";    
    foreach($array as $k=>$v)
    {
        $v=htmlspecialchars($v, ENT_QUOTES);
        $str.="$k:'$v',";
    }    
    return mb_substr($str, 0, mb_strlen($str)-1)." }";
}
  
/**
 * Function fb() for debuging output string, array and etc. in FireBug Console 
 *
 * @see class FirePHP. For param see class FirePHP function FirePHP->fb()
 * 
 * @author Korchinskij G.G.
 * @return unknown
 */

function fb() {

/**
 * *****************VERSION_DEFINITION******************
 */
 	ns_define_version();	
	//this constant is used to define whether we should limit functionality to DEMO or not
	if(defined('NS_DEBUG_VERSION'))
	{
            if(get_debug_params(0)>0 && get_debug_params(7)>0)
            {
                //include firePHP (ver 0.3) now for PHP 5 and PHP 4!
//				if (floor(phpversion()) < 5)
//				{
//					log_message('error', 'PHP 5 is required to run FirePHP');
//				} else {
					  $CI = &get_instance();
					  
					  if (!class_exists('firephp')) 
					  {
					      $CI->load->library('firephp');
					  }
					  
					  $args = func_get_args();
					
					  return call_user_func_array(array($CI->firephp, 'fb'), $args);
					
//				}
            }
	}
	
  return FALSE;

}

/**
 * Function fbq() for debuging output last query string in FireBug Console 
 *
 * @see class FirePHP. Param string - label for output
 * 
 * @author Korchinskij G.G.
 * @return unknown
 */
function fbq() {
	//******************VERSION_DEFINITION******************
	ns_define_version();	
	//this constant is used to define whether we should limit functionality to DEMO or not
	if(defined('NS_DEBUG_VERSION'))
	{
            if(get_debug_params(0)>0 && get_debug_params(7)>0)
            {
                //include firePHP (ver 0.3) now for PHP 5 and PHP 4!
//				if (floor(phpversion()) < 5)
//				{
//					log_message('error', 'PHP 5 is required to run FirePHP');
//				} else {
					  $CI = &get_instance();
					  
					  if (!class_exists('firephp')) 
					  {
					      $CI->load->library('firephp');
					  }
					  
/*					  if (class_exists('CI_DB_mysql_driver'))
					  { 
*/	    				  $str['Last Query'] = $CI->db->last_query();
						  $args[] =  $str;
						  if ($argsOrg = func_get_args())
						  {
							   $args[] =  $argsOrg[0];
						  }
					      return call_user_func_array(array($CI->firephp, 'fb'), $args);
/*					  }
*/					
//				}
            }
	}
	
  return FALSE;
}
  

/**
 * Convert xml to array with double keys (variant 2)
 *
 * @param string $data
 * @return array
 */
function xml2array($data)
{

	$a = 0;
    return _xml2array($data, $a );

}

/**
 * Convert xml to array with double keys (variant 2)
 *
 * @param string $data
 * @param integer &$end
 * @param string $tag
 * @return array
 */
function _xml2array($data, &$end, $tag='')
{
    

    $res=array();
    
    $len=mb_strlen($data);
        
        
    if( ( $start=mb_strpos($data, '<', $end) ) !== false)
    {                   
        
        if( $data{$start+1}=='/' )
        {                       
            return output_xml(mb_substr($data, $end, $start-$end));
        }
                
        
        while($end<$len && ($start=mb_strpos($data, '<', $end))!==false)
        {               
            if(mb_substr($data, $start+1, 1)==='/')
            {           
                $end=$start+1;              
                
                
                $t_end=mb_strpos($data, '>', $start+1);
                
                if($t_end!==false)
                {
                    
                    if($tag!=output_xml(mb_substr($data, $start+2, $t_end-$start-2)))
                    {
                        break;
                    }
                    
                }
                
                
                continue;
            }           
            
            if( ( $end = mb_strpos($data, '>', $start) ) !== false )
            {   
                $end++;
                $tag=$keyname=output_xml(mb_substr($data, $start+1, $end-$start-2));

                if (isset($res[$keyname]))
                {
                	if (!is_array($res[$keyname]) || !isset($res[$keyname][1]))
	                {
	                	$res_res = $res[$keyname];
	                	unset($res[$keyname]);
	                	$res[$keyname][]=$res_res;
	                	$res[$keyname][]=_xml2array($data, $end, $tag);
	                }
	                else 
	                {
	                	$res[$keyname][]=_xml2array($data, $end, $tag);
	                }
                }
                else 
                {
                    $res[$keyname]=_xml2array($data, $end, $tag);
                }
            }
            else 
            
                break;  
            
        }
    }
    
    return $res;
}
  
?>
