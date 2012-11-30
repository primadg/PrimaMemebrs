<?php
/**
* 
* THIS FILE CONTAINS CONFIG FUNCTIONS
* 
* @package Needsecure
* @author uknown
* @version uknown
*/

/**
* Enter description here...
*
*/
$_helper_CONFIG = array();
//this flag is used to mark if some variable in config was updated and we should store it in config file
/**
* this flag is used to mark if some variable in config was updated and we should store it in config file
*/
$_helper_CONFIG_updated_FLAG = false;

function ns_restore($dump=false)
{
    $result_string='';
    $dump_file=$dump ? $dump : absolute_path()."install/dump.sql";
    if(file_exists ($dump_file))
    {
        $CI=&get_instance();
        $CI->load->helper('file');
        //unprotecting users directory
        $CI->load->model('directories_model');
        $CI->load->model('protection_model');
        $CI->db->select('id');
        $query = $CI->db->get(db_prefix.'Dirs');
        foreach($query->result_array() as $dirs)
        {
            $id = $dirs['id'];
            if(Functionality_enabled('admin_dir_protect_modify', $id)===true)
            {
                $directory = $CI->directories_model->db_read($id);
                if($directory)
                {
                    if ($CI->protection_model->unprotect($id) && !$CI->protection_model->ProtectionIsOn($directory))
                    {
                        $CI->directories_model->db_delete($id);
                    }
                    else
                    {
                        $result_string.= date("\nY-m-d H:i:s ")."Unprotection error: ".$directory['fs_path']."<br/>";
                    }
                }
            }
        }
        //remove users posters
        $CI->load->model('product_model');
        $CI->db->select('id,image');
        $query = $CI->db->get(db_prefix.'Products');
        foreach($query->result_array() as $prods)
        {
            $id = $prods['id'];
            $image = $prods['image'];
            if(Functionality_enabled('admin_products_modify', $id)===true)
            {
                $CI->product_model->delete_poster_file($image);
            }
        }
        $query = $CI->db->query("SHOW TABLES LIKE '".db_prefix."%'");
        $table = '';
        $output='';
        if ($CI->db->affected_rows()>0)
        {
            foreach($query->result_array() as $dbs)
            {
                if (is_array($dbs))
                {
                    foreach($dbs as $tables)
                    {
                        
                        if ($table == '')
                        {
                            $table = $tables;
                        }
                        else 
                        {
                            $table .= ",".$tables;
                        }
                    }
                }
            }
            $output .= 'DROP TABLE IF EXISTS '.$table.';';
            $CI->db->query($output);
        }
        execute_sql_file($dump_file);
        $dirs=ns_reprotect_all();
        if($dirs!==false)
        {
            foreach($dirs as $dir)
            {
                $result_string.= "Directory (".$dir['name'] .($dir['result'] ? ") - reprotected!" : ") - <span style='color:red'> not reprotected!</span>")."<br/>";
            }
        }
        else
        {
            $result_string.= "<span style='color:red'>Please do 'Reprotect All' for reprotect all directories!</span><br/>";
        }
        
        return $result_string;
    } 
    return false;    
}

function ns_reprotect_all($max_count=20)
{
    $CI=&get_instance();
    $CI->load->model('directories_model');
    $dirs=$CI->directories_model->get_dir_list();
    if(count($dirs)<=$max_count)
    {
        $CI->load->model('protection_model');
        foreach($dirs as $key=>$dir)
        {
            $dirs[$key]['result'] = $CI->protection_model->reprotect($dir['id']);
        }
        return $dirs;
    }
    else
    {
        return false;
    }
} 

/**
* Convert array to json
*
* @return string
*/
function pay_sys_to_json()
{
    $pay_sys=array();
    $data=config_get('PAYMENT');
    foreach($data as $key=>$value)
    {
        $pay_sys[$key]=$value['controller'];
    }
    return create_temp_vars_set($pay_sys);
}

/**
    * Enter description here...
    *
    * @author onagr
    * @param array $paths
    * @return array
    */
function config_get_ex($paths)
{
    $vals=array();
    foreach ($paths as $key => $value)
    {
        if(isset($value[0]))
        {
            if(isset($value[1]))
            {
                $vals[$key]=config_get($value[0],$value[1],$key);
            }
            else
            {
                $vals[$key]=config_get($value[0],$key);
            }
        }
        else
        {
            $vals[$key]=config_get($key);
        }
    }
    return $vals;
}

/**
    * Enter description here...
    *
    * @param array $values
    * @param array $paths
    */
function config_set_ex($values,$paths)
{
    $vals=array();

    foreach ($paths as $key => $value)
    {
        if(isset($values[$key]))
        {
            if(isset($value[0]))
            {
                if(isset($value[1]))
                {
                    $vals[$key]=config_set($values[$key],$value[0],$value[1],$key);
                }
                else
                {
                    $vals[$key]=config_set($values[$key],$value[0],$key);
                }
            }
            else
            {
                $vals[$key]=config_set($values[$key],$key);
            }
        }
    }
}

/**
* Save configuration
*
* @global object
* @global array
* @global boolean
* @return boolean
*/
function save_config()
{
    global $CI, $_helper_CONFIG, $_helper_CONFIG_updated_FLAG;
    //global $_helper_CONFIG;
    //global $_helper_CONFIG_updated_FLAG;
    $xml = '';

    if (!$_helper_CONFIG_updated_FLAG)
    {
        //if no FILE update needed - exit this function
        return;
    }

    $filename=$CI->config->item('config_path');
    $error = intval(0);
    if( !is_array($_helper_CONFIG) )
    return false;

    if(empty($filename) or !is_readable($filename) or !is_writable($filename))
    {
        
    }

    $fd = @fopen($filename,'wb');
    if(!$fd)
    {
        return false;
    }
    $xml = array_2_xml($_helper_CONFIG);

    if( $xml!=false and !empty($xml) )
    {
        $wr = fwrite($fd, $xml);
        if( $wr == false )
        {
            $error=1;
        }
    }
    fclose($fd);

    if($error>0)return false;
    return true;
}
/**
* Load configuration
*
* @global array
* @return boolean
*/
function load_config()
{
    $CI=&get_instance();
    global $_helper_CONFIG;
    $xml = '';

    $filename=$CI->config->item('config_path');

    if(empty($filename) or !is_readable($filename))return false;
    if( filesize($filename)<=0 )return false;
    $error = intval(0);

    $fd = fopen($filename,'rb');
    if(!$fd)
    {
        return false;
    }
    $xml = fread($fd,filesize($filename));
    fclose($fd);
    $_helper_CONFIG = xml_2_array($xml);
    if(sizeof($_helper_CONFIG)<=0 or !is_array($_helper_CONFIG))
    {
        return false;
    }
    return true;
}
/**
* Get configuration parameters
*
* @global array
* @param string $key1
* @param string $key2
* @param string $key3
* @return mixed
*/
function config_get($key1,$key2='',$key3='')
{
    global $_helper_CONFIG;
    $key1 = strtolower(mb_substr($key1,0,254));
    $key2 = strtolower(mb_substr($key2,0,254));
    $key3 = strtolower(mb_substr($key3,0,254));

    if( !empty($key1) and isset($_helper_CONFIG[$key1]) )
    {
        if( !empty($key2) and isset($_helper_CONFIG[$key1][$key2]) )
        {
            if( !empty($key3) and isset($_helper_CONFIG[$key1][$key2][$key3]) )
            {
                return $_helper_CONFIG[$key1][$key2][$key3];
            }
            elseif (!empty($key3))
            {
                return false;
            }
            return $_helper_CONFIG[$key1][$key2];
        }
        elseif(!empty($key2))
        {
            return false;
        }
        return $_helper_CONFIG[$key1];
    }
    elseif(!empty($key1))
    {
        return false;
    }


    return '';
}

function member_fields_check($page,$name,$value,$retype='', $params=array())
{
    //fb($name."|".$value."|".$retype,'Validate');
    $CI=&get_instance();
    $CI->load->model('config_model');
    $fields = $CI->config_model->member_page_get($page);    
    $field=$fields[$name];
    $field['errors']=array();
    
    if(!count($field['errors']) && empty($value) && (!isset($field['required']) || $field['required']))
    {
        $field['errors'][]='required';
    }
    
    //check range
    if(!count($field['errors']) && isset($field['length']))
    {
        if((isset($field['length']['max']) && mb_strlen($value)>$field['length']['max']) || 
                (isset($field['length']['limit']) && mb_strlen($value)>$field['length']['limit']))
        {
            $field['errors'][]='max_range';
        }
        
        if(!(isset($field['required']) && !$field['required'] && empty($value)) && 
                isset($field['length']['min']) && mb_strlen($value)<$field['length']['min'])
        {
            $field['errors'][]='min_range';
        }
    }
    if(!count($field['errors']) && !empty($value) && isset($field['type']) && !empty($field['type']))
    {
        if(($type=_config_get('fields','types',$field['type'],'expression')) && !preg_match(base64_decode($type), $value))
        {
            $field['errors'][]='type_'.$field['type'];
        }
    }
    
    if(!count($field['errors']) && isset($field['retype']) && $field['retype'] && $value!=$retype)
    {
        $field['errors'][]='retype';
    }
    return $field; 
}

function _result_to_dump($table,$result_array,$is_escape=false)
{
    $CI=&get_instance();
    $patch=array();
    foreach($result_array as $value)
    {
        if($is_escape)
        {
            foreach($value as $k=>$v)
            {
                $value[$k]=$CI->db->escape_str($v);
            }
        }
        $patch[]="REPLACE INTO `db_prefix_".$table."` (`".implode("`, `",array_keys($value))."`) VALUES ('".implode("', '",$value)."');";
    }
    return $patch;
} 

function _config_section_dump()
{
    $args=func_get_args();
    $CI=&get_instance();
    $CI->db->from(db_prefix.'System_settings');
    $targs=array();
    foreach($args as $a)
    {
        //filtering '/'
        $targs[]=str_replace("/","<[(slash)]>",$a);
    }
    $key=implode("/",$targs);
    if(func_num_args()>0)
    {        
        $CI->db->where("`config_key`='".$CI->db->escape_str($key)."' OR `config_key` LIKE '".$CI->db->escape_str($key)."/%'");
    }
    $CI->db->order_by('config_key', 'desc'); 
    $query=$CI->db->get();
    $result=$query->result_array();
    
    if(count($result))
    {
        $patch=array();
        $valid_patch_keys=array();
        $patch[]="DELETE FROM `db_prefix_System_settings` WHERE config_key LIKE '".$CI->db->escape_str($key."/")."%';";
        $valid_patch_keys[]="/([#]*)(DELETE FROM `db_prefix_System_settings` WHERE config_key LIKE '".preg_quote($CI->db->escape_str($key."/"),"/").")/";
        $valid_patch_keys[]="/([#]*)(REPLACE INTO `db_prefix_System_settings` \(`config_key_hash`, `config_key`, `config_value`\) VALUES \('[a-zA-Z0-9]*', '".preg_quote($CI->db->escape_str($key."/"),"/").")/";
        foreach($result as $value)
        {
            $patch[]="REPLACE INTO `db_prefix_System_settings` (`".implode("`, `",array_keys($value))."`) VALUES ('".implode("', '",$value)."');";
        }
        $CI->admin_auth_model->main_page_info();
        $patch_str="\n\n# ".date("Y-m-d h:i:s")."  (by ".$CI->admin_auth_model->username.")"."\n".implode("\n",$patch);
        $f="dump_patch_".(1+floor($CI->updater_model->Normalize_version(NEEDSECURE_VERSION))).".sql";
        
        $warning=array();
        $dump_files=array();
        $dump_files[]=absolute_path()."_protect/".$f;
        $dump_files[]=absolute_path()."patch/".$f;
        foreach($dump_files as $dump_file)
        {
            if(!file_exists($dump_file) || !@file_put_contents($dump_file,preg_replace($valid_patch_keys,"#$2",@file_get_contents($dump_file)).$patch_str))
            {
                $warning[]="File ".$dump_file." is not writable!";
            }
        }
        //write_file(absolute_path()."_protect/".$f, $patch_str,'a+');
        return $patch_str;
    }
    return '';
}

$_CONFIG_CASH = array();

function _config_get()
{
    global $_CONFIG_CASH;
    $args=func_get_args();
    fb($args);
    //is cashing
    $key_cash=array_search(false,$args,true);
    $is_cash=true;
    if($key_cash!==false && $key_cash!==null)
    {
        if($key_cash==(count($args)-1))
        {
            array_pop($args);
            $is_cash=false;
        }
        
        if($key_cash==0)
        {
            array_shift($args);
            $is_cash=false;
        }
    }
    $is_cash=!count($args) ? false : $is_cash;
    //fb($args);
    //fb($is_cash);
    if($is_cash)
    {
        $res=$_CONFIG_CASH;
        $flag=true;
        foreach($args as $k=>$v)
        {
            if(isset($res[$v]))
            {
                $res=$res[$v];    
            }
            else
            {
                $flag=false;
                break;
            }
        }
        if($flag)
        {
            return $res;
        }
    }
    
    $CI=&get_instance();
    $CI->db->from(db_prefix.'System_settings');
    $targs=array();
    foreach($args as $a)
    {
        //filtering '/'
        $targs[]=str_replace("/","<[(slash)]>",$a);
    }
    $key=implode("/",$targs);
    if(func_num_args()>0)
    {        
        $CI->db->where("`config_key`='".$CI->db->escape_str($key)."' OR `config_key` LIKE '".$CI->db->escape_str($key)."/%'");
    }
    $CI->db->order_by('config_key', 'desc'); 
    $query=$CI->db->get();
    $result=$query->result_array();
    $cfg=array();
    foreach($result as $k=>$val)
    {
        $temp_key=preg_replace("/^".addcslashes(strtolower($key),"/()[]")."[\/]{0,1}/", "$1", strtolower($val['config_key']));
        $keys = array_reverse(explode("/",$temp_key));
        $sect=array((' '.$keys[0].'')=>$val['config_value']);
        for($i=1;$i<count($keys);$i++)
        {
            $sect=array((' '.$keys[$i].'')=>$sect);   
        }
        $cfg=array_merge_recursive($cfg,$sect);
    }
    $cfg=_config_key_trim_recursive($cfg);
    if(count($cfg)==1 && isset($cfg[""]))
    {
        $cfg = $cfg[""];
    }
    else
    {
        unset($cfg[""]);
        $cfg=count($cfg) ? $cfg : "";
        
    }
    $_CONFIG_CASH=array_set_value($_CONFIG_CASH,$args,$cfg);
    return $cfg;
}

function _config_key_trim_recursive($arr)
{
    $r=array();
    foreach($arr as $k=>$v)
    {
        $r[str_replace("<[(slash)]>","/",trim($k))]=is_array($v) ? _config_key_trim_recursive($v) : $v;    
    }
    return $r;
}

function _config_set()
{
    global $_CONFIG_CASH;
    $args=func_get_args();
    if(count($args)>1 || (count($args)>0 && is_array($args[0]) && count($args[0])))
    {
        $val=$args[0];
        unset($args[0]);
        $result=array();
        $targs=array();
        foreach($args as $a)
        {
            //filtering '/'
            $targs[]=str_replace("/","<[(slash)]>",$a);
        }
        $key=implode("/",$targs);
        if(is_array($val))
        {
            $result=_create_config_key($val,(empty($key) ? "" : $key."/"));
        }
        else if(!empty($key)) 
        {
            $result=array($key=>$val);
        }
        
        //if(count(preg_grep ("/^[\S]{1,333}$/", array_keys($result),PREG_GREP_INVERT)))
        //{
        //    return 'long_key';
        //} 
        
        $CI=&get_instance();
        if(!empty($key))
        {
            $CI->db->where("`config_key`='".$CI->db->escape_str($key)."' OR `config_key` LIKE '".$CI->db->escape_str($key)."/%'");
        }
        else
        {
            $CI->db->like('config_key', ''); 
        }
        $CI->db->delete(db_prefix.'System_settings'); 
        fbq('Q1');
        foreach($result as $k=>$v)
        {
            $CI->db->insert(db_prefix.'System_settings', array('config_key_hash'=>md5($k),'config_key'=>$k,'config_value'=>$v));     
        }
        if(count($args))
        {        
            $_CONFIG_CASH=array_set_value($_CONFIG_CASH,$args,$val);    
        }
        return true;
    }
    return false;
}

function array_set_value($arr,$keys,$value)
{
    $arr=isset($arr) && is_array($arr) ? $arr : array();
    if(is_array($keys) && count($keys))
    {
        $key=array_shift($keys);
        if(count($keys))
        {
            $arr[$key]=array_set_value((isset($arr[$key]) && is_array($arr[$key]) ? $arr[$key] : array()),$keys,$value);
        }
        else
        {
            $arr[$key]=$value;
        }
        return $arr;
    }
    return false;
}

function _create_config_key($a,$name='')
{
    $result=array();
    foreach($a as $k=>$v)
    {
        //filtering '/'
        $n=str_replace("/","<[(slash)]>",$k);
        if(is_array($v))
        {
            $result=array_merge($result,_create_config_key($v,$name.$n."/"));    
        }
        else
        {
            $result[$name.$n]=$v;    
        }            
    }
    return $result;
}

/**
* Set value for parameter 
*
* @global array
* @global boolean
* 
* @param array $value
* @param string $key1
* @param string $key2
* @param string $key3
* 
* @return mixed
*/
function config_set($value,$key1,$key2='',$key3='')
{
    global $_helper_CONFIG, $_helper_CONFIG_updated_FLAG;
    //global $_helper_CONFIG_updated_FLAG;

    $key1 = strtolower(mb_substr(trim($key1),0,254));
    $key2 = strtolower(mb_substr(trim($key2),0,254));
    $key3 = strtolower(mb_substr(trim($key3),0,254));


    if( empty($key1) )
    {
        return false;
    }

    //mark with a FLAG, that some changes are and we should update config file
    $_helper_CONFIG_updated_FLAG = true;

    if( !is_array($value) )
    {
        $value = strval($value);
        $value = mb_substr($value,0,2048);
    }

    if( !isset($_helper_CONFIG[$key1]) )
    {
        $_helper_CONFIG[$key1] = array();
    }

    if( !empty($key2) )
    {
        if( !isset($_helper_CONFIG[$key1][$key2]) )
        {
            $_helper_CONFIG[$key1][$key2] = array();
        }
    }

    if( !empty($key3) )
    {
        if( !isset($_helper_CONFIG[$key1][$key2][$key3]) )
        {
            $_helper_CONFIG[$key1][$key2][$key3] = array();
        }
    }


    if( !empty($key2) and !empty($key3) )
    {
        if( !is_array($_helper_CONFIG[$key1][$key2]) )
        {
            $_helper_CONFIG[$key1][$key2] = array();
        }
        $_helper_CONFIG[$key1][$key2][$key3] = $value;
    }
    elseif( !empty($key2) )
    {
        if( !is_array($_helper_CONFIG[$key1]) )
        {
            $_helper_CONFIG[$key1] = array();
        }
        $_helper_CONFIG[$key1][$key2] = $value;
    }
    else
    {
        $_helper_CONFIG[$key1] = $value;
    }

}

/**
* Check current design configuration
*
* @return mixed
*/
function design_check()
{
    $dir=config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/";
    $dirs=scandir($dir, 1);
    $reg_dirs=array();
    $unreg_dirs=array();
    foreach($dirs as $d)
    {
        if(!in_array($d,array('.','..','admin')) && is_dir($dir.$d))
        {
            if(file_exists($dir.$d."/reg/user"))
            {
                $reg_dirs[]=$d;
            }
            if(file_exists($dir.$d."/unreg/user"))
            {
                $unreg_dirs[]=$d;
            }
        }
    }
    
    $active_reg_design = config_get('DESIGN','active_reg_design');
    $design_reg_list = config_get('DESIGN','design_reg_list');
    $reg_prefix = $design_reg_list[$active_reg_design];        
    if(($reg_index=array_search($reg_prefix,$reg_dirs))===false)
    {
        if(($reg_index=array_search('default',$reg_dirs))===false)
        {
            $reg_index==0;
        }
    }
    config_set(intval($reg_index),'DESIGN','active_reg_design');
    config_set($reg_dirs,'DESIGN','design_reg_list');
    
    $active_unreg_design = config_get('DESIGN','active_unreg_design');
    $design_unreg_list = config_get('DESIGN','design_unreg_list');
    $unreg_prefix = $design_unreg_list[$active_unreg_design];
    if(($unreg_index=array_search($unreg_prefix,$unreg_dirs))===false)
    {
        if(($unreg_index=array_search('default',$unreg_dirs))===false)
        {
            $unreg_index==0;
        }
    } 
    config_set(intval($unreg_index),'DESIGN','active_unreg_design');
    config_set($unreg_dirs,'DESIGN','design_unreg_list');
    
    //fb($reg_dirs,"reg_dirs");
    //fb($unreg_dirs,"reg_dirs");
}


/**
* Get current db tables prefix
*
* @return mixed
*/
function prefix()
{
    return config_get('DB','MYSQL','PREFIX');
}

/**
* Get current absolute_path from config
*
* @return mixed
*/
function absolute_path($path='')
{
    return config_get("SYSTEM","CONFIG","ABSOLUTE_PATH").$path;
}

/**
    * This funciton is used to check if the file ht_sys_config.cfg is readable & writable
    * If config file is not writable - function will return TRUE.
    *
    * @return boolean
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
function ht_sys_config_not_writable()
{
    $CI = &get_instance();
    // check .ht_sys_config
    $filename=$CI->config->item('config_path');
    if(empty($filename) or !is_readable($filename) or !is_writable($filename))
    {
        return true;
    }
    return false;
}

/**
* Enter description here...
*
* @return mixed
*/
function pre_config()
{
    static $loaded=false;
    if($loaded)
    return false;
    $loaded=true;
    @session_start();
    $CI = &get_instance();
    //DEBUG CONFIG SWITCH 
    if(false && defined("NS_DEBUG_VERSION"))
    {
        $s=explode("/",$_SERVER['SCRIPT_NAME']);
        array_pop($s);
        $f=str_replace("/","_",str_replace(".","_",$_SERVER['HTTP_HOST']."_".$_SERVER['SERVER_PORT'].implode("/",$s)));
        $f="./system/application/config/".$f.".ht_sys_config.cfg";
        if(file_exists($f))
        {
            $CI->config->set_item('config_path',$f);
        }
    }
    //END OF DEBUG CONFIG SWITCH
    load_config();

    // check .ht_sys_config
    $filename=$CI->config->item('config_path');
    if(empty($filename) or !is_readable($filename))
    {
        echo "<h2 style=\"color:black;\">ERROR: please check file access permissions or config file is corrupted!<br/>".$filename."</h2>";
        exit(-1);
    }

    // _check .ht_sys_config

    $config['hostname'] = config_get('DB','MYSQL','HOST');
    $config['username'] = config_get('DB','MYSQL','USER');
    $config['password'] = config_get('DB','MYSQL','PASSWORD');
    $config['database'] = config_get('DB','MYSQL','DATABASE');
    $config['dbdriver'] = config_get('DB','MYSQL','DRIVER');
    $config['pconnect'] = config_get('DB','MYSQL','PCONNECT');
    $config['db_debug'] = config_get('DB','MYSQL','DEBUG');
    $config['char_set'] = "utf8";
    $config['dbcollat'] = "utf8_general_ci";

    $CI->load->database($config);

	$conn = $CI->load->database($config, true);

	if (empty($conn->conn_id))
	{
		show_error('Cannot connect to database');
		exit;
	}

    ///////////// HTTP to HTTPS switcher!!!!
    $CI->config->set_item('base_url', config_get('system','config','base_url'));
    /////////////

    define("db_prefix", config_get('DBPREFIX'));
    define('PRODUCT_LANG_ID',1);
    mb_internal_encoding('UTF-8');        
    update_version();
}

/**
    * Enter description here...
    *
    */
function ns_define_version()
{
    $absolute_path=config_get('system','config','absolute_path');
    $cpath=$absolute_path."system/application/models/";
    $extended_version=false;
    if(file_exists($cpath."/_version.php"))
    {
        include($cpath."/_version.php");
    }
    if(!$extended_version)
    {
        if(file_exists($absolute_path."system/application/models/version.php"))
        {
            include($absolute_path."system/application/models/version.php");
        }
    }
}

/**
    * Enter description here...
    *
    */
function update_version()
{
    $absolute_path=config_get('system','config','absolute_path');
    if(file_exists($absolute_path."system/application/models/main_version.php"))
    {
        include($absolute_path."system/application/models/main_version.php");
    }
    if(defined('NEEDSECURE_VERSION'))
    {
        $CI=&get_instance();
        $CI->load->model("updater_model");
        $CI->updater_model->try_update(config_get('system','config','version'),NEEDSECURE_VERSION);                        
    }
}

/**
* Read File. Opens the file specfied in the path and returns it as a string.
*
*
* @access	public
* @param	string	path to file
* @return	string
*/	
if (! function_exists('read_file'))
{
    function read_file($file)
    {
        if ( ! file_exists($file))
        {
            return FALSE;
        }
        
        if (function_exists('file_get_contents'))
        {
            return file_get_contents($file);		
        }

        if ( ! $fp = @fopen($file, 'rb'))
        {
            return FALSE;
        }
        
        flock($fp, LOCK_SH);
        
        $data = '';
        if (filesize($file) > 0)
        {
            $data =& fread($fp, filesize($file));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }
}    
/**
    * Execute sql file
    *
    * @param string $dump_file
    * @return boolean
    */
function execute_sql_file($dump_file)
{
    if(file_exists($dump_file) && FALSE!==($sql_statements=read_utf8_file($dump_file)))
    {
        $sql_statements = str_replace('db_prefix_',db_prefix,$sql_statements);
        $arr_sql =  preg_split('/;[\n\r]+/',$sql_statements);
        $CI=&get_instance();        
        $config=array();
        $config['hostname'] = config_get('DB','MYSQL','HOST');
        $config['username'] = config_get('DB','MYSQL','USER');
        $config['password'] = config_get('DB','MYSQL','PASSWORD');
        $config['database'] = config_get('DB','MYSQL','DATABASE');
        $config['dbdriver'] = config_get('DB','MYSQL','DRIVER');
        $config['pconnect'] = config_get('DB','MYSQL','PCONNECT');
        $config['db_debug'] = config_get('DB','MYSQL','DEBUG');
        $config['db_debug'] = FALSE;
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        $DB=$CI->load->database($config,TRUE);
        foreach($arr_sql as $sql)
        {
            if (!empty($sql))
            {
                if(!$DB->query($sql))
                {
                    fb(array(mysql_error(),$sql),"SQL error: ");
                    write_file(absolute_path('sql.log'), date("\nY-m-d H:i:s ")."SQL error: ".mysql_error() ."\n". $sql ,'a');
                }
            }
        }
        unset($DB);
        return true;
    }
    return false;
}

//get current operation system
/**
    * get current operation system
    *
    * @return string
    */
function Get_Os()
{
    if (function_exists('php_uname')) return php_uname();

    if (PHP_OS == 'WINNT')
    return 'Windows NT';

    return PHP_OS;
}

if(!function_exists("apache_get_modules"))
{
    /**
        * Get appache mmodeles
        *
        * @return array
        */
    function apache_get_modules()
    {
        $base_url_declared=explode("index.php",$_SERVER ['PHP_SELF']);
        $base_url_declared=function_exists("base_url") ? base_url() : $base_url_declared[0];
        $content="";
        $filename=$base_url_declared."system/application/helpers/apache_get_modules/apache_get_modules.php?autoload1=1";
        if($handle = @fopen($filename, "rb"))
        {
            while (!feof($handle)) {
                $content .= fread($handle, 8192);
            }
            fclose($handle);
        }
        
        if(!empty($content))
        {
            return explode("|", $content);
        }
        else
        {
            if(!defined("APACHE_GET_MODULES"))
            {
                define("APACHE_GET_MODULES",false);
            }
        }
        
        if(isset($_SESSION['apache_get_modules']) && is_array($_SESSION['apache_get_modules']))
        {
            return $_SESSION['apache_get_modules'];     
        }
        return array();
    }
}
/**
    * Display or return php info
    *
    * @param boolean $return
    * @return mixed
    */
function phpinfo_array($return=false)
{
    
    
    //VARIANT 1
    /*  
        ob_start();
        phpinfo();
        $phpinfo = array('phpinfo' => array());
        if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
        foreach($matches as $match)
        if(strlen($match[1]))
        $phpinfo[$match[1]] = array();
        elseif(isset($match[3]))
        $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
        else
        $phpinfo[end(array_keys($phpinfo))][] = $match[2];
        return $phpinfo; 
        */
    
    //VARIANT 2
    ob_start();
    phpinfo(-1);

    $pi = preg_replace(
    array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
    '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
    "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
    '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
    .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
    '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
    '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
    "# +#", '#<tr>#', '#</tr>#'),
    array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
    '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
    "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
    '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
    '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
    '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
    ob_get_clean());

    $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
    unset($sections[0]);

    $pi = array();
    foreach($sections as $section){
        $n = substr($section, 0, strpos($section, '</h2>'));
        preg_match_all(
        '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
        $section, $askapache, PREG_SET_ORDER);
        foreach($askapache as $m)
        {
            $pi[$n][$m[1]]=!isset($m[2]) ? "" : ((!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2));
        }
    }

    return ($return === false) ? print_r($pi) : $pi; 
}

?>
