<?php
/**
 * 
 * THIS FILE CONTAINS Directories_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */


//bitmasking:
/**
 * directory has subdirectories
 *
 */
define ("DIRECTORIES_BITMASK_SUBDIRECTORIES", 1); //directory has subdirectories
/**
 * directory is protected
 *
 */
define ("DIRECTORIES_BITMASK_PROTECTED", 2); //directory is protected
/**
 * directory info has been completely loaded by treeview
 *
 */
define ("DIRECTORIES_BITMASK_LOADED", 4); //directory info has been completely loaded by treeview
/**
 * directory is selected when view is open
 *
 */
define ("DIRECTORIES_BITMASK_SELECTED", 8); //directory is selected when view is open
/**
 * directory is protectable
 *
 */
define ("DIRECTORIES_BITMASK_PROTECTABLE", 16); //directory is protectable
//N.B. These bits are used in js/admin/protect/protect.js - make sure to update both files when editing this

/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH DIRECTORIES
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Directories_model extends Model
{
    /**
     * Constructor. Gets CI pointer because we need to call it frequently
     *
     * @return void
     */
    function Directories_model()
    {
        parent::Model();
    }
    
    /**
     * Reads list of assotiated products
     *
     * @param mixed $id
     * @return mixed
     */
    function Get_assotiated_products($id)
    {
        if(intval($id)>0)
        {
            $this->db->select('products.id, products.closed');
            $this->db->from(db_prefix.'Products `products`');
            $this->db->where('products.closed <> 1');
            $this->db->join(db_prefix.'Dir_products as dir_products', 'dir_products.product_id = products.id', 'LEFT');
            $this->db->where('dir_products.dir_id',$id);
            $query=$this->db->get();
            $result=$query->result_array();
            if(count($result)>0)
            {
                $CI =& get_instance();        
                $CI->load->model("lang_manager_model"); 
                $result = $CI->lang_manager_model->combine_with_language_data($result,4,array('name'=>'name'),'id',false,false,&$add_params);
                $result = array_transform($result,'id','name');
                return $result;
            }
        }
        return false;
    }

    /**
     * Reads list of supported protection methods from the database by parsing `Dirs` table DDL (`method` enum field)
     *
     * @return array of methods
     */
    function Get_protection_methods()
    {
        $ddl = $this->db->query("show create table `".db_prefix."Dirs`");
        $raw = $ddl->row_array();
        preg_match("#\`method\` enum\((.*?)\)#i",$raw["Create Table"],$matches); //field definition
        preg_match_all("#'([^']+)'#i",$matches[1],$matches); //enum options
        $result=$matches[1];
        
        //the excluding unsupported methods
        $mods=apache_get_modules();
        $phpinfo=phpinfo_array(true);

        if(!in_array('mod_auth',$mods) && !in_array('mod_auth_basic',$mods))
        {
            $result=array_diff($result,array('www_auth'));
        }
        if(!in_array('mod_proxy',$mods))
        {
            $result=array_diff($result,array('mod_rewrite_cookies'));
        }
        if(isset($phpinfo['PHP Configuration']) && isset($phpinfo['PHP Configuration']['Server API']) && strpos(strtoupper($phpinfo['PHP Configuration']['Server API']),"CGI")!==false)
        {
            $result=array_diff($result,array('php_prepend'));
        }        
        //Array ( [0] => mod_rewrite_standard [1] => mod_rewrite_cookies [2] => www_auth [3] => php_prepend ) 
        return $result;
    }


    /**
     * Reads list of protected directories methods from the database
     *
     * @return array of protected directories, with paths as keys
     */
    function Get_all_protected()
    {
        $result = array();

        $query = $this->db->get(db_prefix.'Dirs');

        foreach ($query->result_array() as $row)
        {
            $result[$row['fs_path']] = $row;
        }

        return $result;
    }


    /**
     * Calculates number of all directories in the db
     *
     * @return integer
     */
    function Number_of_directories()
    {
        return $this->db->count_all(db_prefix.'Dirs');
    }

	/**
	 * Get a list of protected directories
	 *
	 * @todo Replace construction WHERE IN (1,2,...,n)
	 * @param integer $start
	 * @param integer $amount
	 * @param string $orderby
	 * @param string $order
	 * @return array
	 */
    function Get_Directories($start, $amount, $orderby, $order)
    {
        /**
        * @TODO Replace construction WHERE IN (1,2,...,n)
        */
        $this->db->limit($start, $amount);
        $query=$this->db->get(db_prefix.'Dirs');
        $dirs=$query->result_array();                
        if(count($dirs))
        {
            
            $this->db->select("d.id, count(product_id) as num_of_products");
            $this->db->from(db_prefix.'Dirs d');
            $this->db->join(db_prefix.'Dir_products dp', "d.id=dp.dir_id", "left");

            //correct count - with valid products only
            $this->db->join(db_prefix.'Products p', "p.id=dp.product_id", "left");
            $this->db->where($this->_sql_valid_product('`p`'));

            //when directory has no products we still want it in the list
            //$this->db->or_where("p.id is null");
            $this->db->where_in('d.id',array_transform($dirs,false,'id'));
            $this->db->group_by("d.id");

            //$this->db->order_by($orderby, $order);
            //$this->db->limit($start, $amount);

            $query=$this->db->get();
            $d=array_transform($query->result_array(),'id','num_of_products');
            $methods=$this->Get_protection_methods();
            foreach($dirs as $key=>$value)
            {
                $dirs[$key]['num_of_products']=isset($d[$value['id']]) ? $d[$value['id']] : 0;
                $dirs[$key]['method_disabled']=in_array($dirs[$key]['method'],$methods) ? false : true;
            }
            //echo $this->db->last_query();
        }
        return result_array_sort($dirs,$orderby,$order);

        //return $query->result_array();
    }

    /**
     * Reads list of protected directories from the database
     *
     * @param array
     * @return array of protected directories, every record contains array of (id, name)
     */
    function Get_dir_list($filter=false)
    {
        $this->db->select("id, name, http_path");

        $this->db->from(db_prefix.'Dirs');
        
        if($filter!==false)
        {
            if(isset($filter['protect_period']))
            {
                $protect_period=intval($filter['protect_period']);
                $interval = array(
                0=>'1 SECOND',
                1=>'10 MINUTE',
                2=>'1 HOUR',
                3=>'1 DAY',
                4=>'1 WEEK',
                5=>'1 MONTH'
                );
                $protect_period=array_key_exists($protect_period,$interval) ? $protect_period : 3;               $this->db->where('DATE_ADD(`last_protect_time`, INTERVAL '.$interval[$protect_period].') < CURRENT_TIMESTAMP()');                       
            }
        }

        $this->db->order_by("name");

        $query=$this->db->get();
        return $query->result_array();
    }
	
	function check_access_type($url)
	{
		$error_text = '';
		$ns_url = base_url();
		$dir_url = $this->DB_Read_by_url($url);
		if (!$dir_url)
		{
			$dir_url = $this->Get_dir_list();
			foreach ($dir_url as $k=>$v)
			{
				if (strpos($url, $v['http_path'])!==false and strpos($url, $v['http_path'])!=="")
				{
					$error_text = '<{user_login_error_restricted_area_dir}>';
				}
			}
			if ($error_text=='')
			{
				if (strpos($url, $ns_url)!==false and strpos($url, $ns_url)!=="")
				{
					$error_text = '<{user_login_error_restricted_area_path}>';
				}
				else
				{
					$error_text = '<{user_login_error_restricted_area_file}>';
				}
			}
            	//$data['error_text'] = '<{user_login_error_restricted_area}>';
		}
		else
			$error_text = '<{user_login_error_restricted_area_dir}>';	
		return $error_text;
	}


    /**
     * Checks if directory is protected (i.e. there is a record in database)
     *
     * @param string $path absolute path to the server-side directory
     * @return boolean
     */
    function Is_protected($path)
    {
        static $all_protected;

        if (!isset($all_protected))
        {
            $all_protected = $this->Get_all_protected();
        }

        return array_key_exists($path, $all_protected);
    }


    /**
     * Builds list of directories from given parent directory and also reports if they themselves contain subdirectories.
     * Every directory path ends with "/"
     *
     * @param string $path absolute path to the server-side directory
     * @return array of directories: key is directory and value is from {0,1}:
     *         0 - this is directory without subdirectories
     *         1 - this is directory with subdirectories
     */
    function Load_subdirectories($path)
    {
        if (!@is_dir($path)) //won't work
            return array();

        $result = array();

        $this->load->helper('ns_file_helper');

        //getting list of directories in parent directory
        $directories = get_dir_contents($path, false, true);

        foreach($directories as $filepath => $filename)
        {
            $subs = get_dir_contents($filepath, false, true);
            $result[$filepath] = intval(count($subs)>0);
        }

        return $result;
    }


    /**
     * Validates data for protection
     *
     * @param array $data assosiative, contains the following fields:
     *        integer id directory id (0 means adding)
     *        string $method protection method
     *        string $name directory name
     *        string $http_path directory url
     *        string $fs_path directory path
     * @param array $current_data array of the same structure - current state of the directory
     * @return array of errors, empty means no errors
     */
     function Validation_Errors($data, $current_data = false)
     {
        $result = array();
        $messages = $this->load_panel_vars(array());

        $CI = &get_instance();
        $CI->load->model('protection_model');

        if (!in_array($data['method'],$this->get_protection_methods()))
        {
            $result[] = $messages['error_messages']['method'];
        }
        if (!$data['name'] || (mb_strlen($data['name']) > 255))
        {
            $result[] = $messages['error_messages']['name'];
        }
        if (!$data['http_path'] || (mb_strlen($data['http_path']) > 2048))
        {
            $result[] = $messages['error_messages']['http_path'];
        }
        if (!$data['id'] && $this->Is_protected($data['fs_path']))
        {//adding directory
            $result[] = $messages['temp_vars_set']['directory_is_already_protected'];
        }
        if ($data['id'] && $this->Is_protected($data['fs_path']) && ($data['fs_path'] != $current_data['fs_path']) )
        {//editing directory
            $result[] = $messages['temp_vars_set']['directory_is_already_protected'];
        }
        if (!$CI->protection_model->Is_ht_writable($data['fs_path']))
        {
            $result[] = $messages['temp_vars_set']['directory_is_not_protectable'];
        }
        if ($this->is_system_directory($data['fs_path']))
        {
            $result[] = $messages['temp_vars_set']['system_directory_is_not_protectable'];
        }
        if (mb_strlen($data['fs_path']) > 2048)
        {
            $result[] = $messages['temp_vars_set']['directory_path_is_too_long'];
        }
        return $result;
     }
     
    /**
     * Check system directories
     *
     * @param string $dir
     * @return bool
     */
    function Is_system_directory($dir)
    {
        $dir=mb_strtolower($this->Standartize_directory_name($dir));
        $absolute_path=mb_strtolower($this->Standartize_directory_name(config_get('system','config','absolute_path')));
        $sys_dir=array(
        "blowfish",
        "css",
        "img",
        "install",
        "js",
        "kcaptcha",
        "posters",
        "swf",
        "system",
        "upgrade",
        "_db",
        "_protect"
        );
        if(mb_strpos($absolute_path,$dir)==0)
        {
            $dir=str_replace($absolute_path,"",$dir);
            if(!empty($dir))
            {
                $res=explode("/",$dir);
                if(in_array(mb_strtolower($res[0]),$sys_dir))
                {
                    return true;
                }        
            }
            else
            {
                return true;
            }
        }
        return false;    
    }  
    


    /**
     * Write info about protected directory to the database, calling either insert or update query
     *
     * @param array $data assosiative, contains the following fields:
     *        integer id directory id (0 means adding)
     *        string $method protection method
     *        string $name directory name
     *        string $http_path directory url
     *        string $fs_path directory path
     * @return integer: id of new record for insert, false if failure, true for successful update
     */
    function DB_Write($data)
    {
        if (!$data['id'])
        {//inserting
            unset($data['id']); //it will be auto-generated
            $data['last_protect_time']=date("Y-m-d h:i:s");
            $this->db->insert(db_prefix.'Dirs', $data);
            return $this->db->insert_id();
        }
        else{
            return $this->db->update(db_prefix.'Dirs', $data, "id = ".$data['id']);
        }
    }


    /**
     * Returns directory from database by id
     *
     * @param integer $id
     * @return mixed: associative array with directory or false if no records found
     */
    function DB_Read($id)
    {
        $query = $this->db->get_where(db_prefix.'Dirs',array('id'=>$id));

        $result = $query->first_row('array');
        if (empty($result))
        {
            return false;
        }
        return $result;
    }

    /**
     * Returns directory from database by path
     *
     * @param string $path
     * @return mixed: associative array with directory or false if no records found
     */
    function DB_Read_by_path($path)
    {
        $query = $this->db->get_where(db_prefix.'Dirs',array('fs_path'=>$path));

        $result = $query->first_row('array');
        if (empty($result))
        {
            return false;
        }
        return $result;
    }
	
	/**
     * Returns directory from database by url
     *
     * @param string $path
     * @return mixed: associative array with directory or false if no records found
     */
    function DB_Read_by_url($url)
    {
        $query = $this->db->get_where(db_prefix.'Dirs',array('http_path'=>$url));

        $result = $query->first_row('array');
        if (empty($result))
        {
            return false;
        }
        return $result;
    }

    /**
     * Deletes directory from database by id (also deletes children records from dir_products)
     *
     * @param integer $id
     * @return boolean: success or failure
     */
    function DB_Delete($id)
    {
        $this->db->delete(db_prefix."Dir_products", array("dir_id"=>$id));
        $this->db->delete(db_prefix."Dirs", array("id"=>$id));
        return (bool) $this->db->affected_rows();
    }


    /**
     * Wrap Load_subdirectories() with simple caching layer
     *
     * @param string $directory path to directory
     * @return array it got from get_subdirectories()
     */
    function Get_subdirectories($path)
    {
        static $cache;

        if (!isset($cache))
        {
            $cache = array();
        }

        if (!array_key_exists($path, $cache))
        {
            $cache[$path] = $this->load_subdirectories($path);
        }

        return $cache[$path];
    }


    /**
     * Gets nearest existing directory for non-existing path
     *     (i.e. are we able to write to its .htaccess file or not)
     *
     * @param string $path path to the directory which doesn't exist
     * @param string $default_path
     * @return string path to the directory which exists
     */
    function Get_nearest_directory($path, $default_path)
    {
        $this->load->helper('ns_file_helper');
        if (dir_exists($path))
        {//no need to do anything since we are in the nearest directory already
            return $path;
        }
        if (mb_strpos($path,"/")===false)
        {//any valid path must contain at least one "/"
            return $default_path;
        }
        while($path)
        {//cutting $path step by step using last slash as delimiter
            $path = mb_substr($path,0,mb_strrpos($path,"/")+1);
            if (dir_exists($path) && ($path!="/")) //we are not going to protect root directory anyway
            {
                return $path;
            }
            $path = rtrim($path,"/");
        }
        return $default_path;
    }


    /**
     * Returns all directory info: protectable, protected, has_subdirectories
     *
     * @param string $path to directory
     * @return integer bitmask
     */
    function Get_directory_info($path)
    {
        $result = 0;

        $CI = &get_instance();
        $CI->load->model('protection_model');

        if ($CI->protection_model->is_ht_writable($path))
        {
            $result |= DIRECTORIES_BITMASK_PROTECTABLE;
        }
        if ($this->is_protected($path))
        {
            $result |= DIRECTORIES_BITMASK_PROTECTED;
        }

        $directories = $this->get_subdirectories($path);
        if (count($directories) > 0)
        {
            $result |= DIRECTORIES_BITMASK_SUBDIRECTORIES;
        }

        return $result;
    }


    /**
     * Converts directory name to the standard form: /path/to/directory/
     * converts both unix names without ending slash or windows names with drives & wrong slashes
     *
     * @param string $directory path to directory
     * @return string standartized path to directory
     */
    function Standartize_directory_name($directory)
    {
        if (mb_substr($directory,1,1)==":") //anti-windows
        {
            $directory = str_replace("\\","/",$directory);
            $directory = mb_substr($directory,2); //remove drive
        }
        if (mb_substr($directory,-1,1)!="/") //ending slash
        {
            $directory .= "/";
        }
    return $directory;
    }


    /**
     * Recursively reads $directory for subdirectories (1 or 2 levels deep)
     *
     * @param string $directory absolute path to the server-side directory
     * @param boolean $preload_subs shall we also preload subs or not
     * @return array (path => bitmask)
     */
    function Load_directory($directory, $preload_subs)
    {
        $result = array();
        $result[$directory] = DIRECTORIES_BITMASK_LOADED | $this->get_directory_info($directory); //loaded & preloaded

        $CI = &get_instance();
        $CI->load->model('protection_model');

        $subdirectories = $this->get_subdirectories($directory);

        foreach($subdirectories as $_directory=>$_f) //2nd level
        {
            if ($CI->protection_model->is_ht_writable($_directory))
            {
                $subdirectories[$_directory] = $this->get_directory_info($_directory);
            }
        }

        $result = array_merge($result, $subdirectories);

        if ($preload_subs)
        {
            foreach($subdirectories as $directory=>$_f) //2nd level
            {
                $result[$directory] = $this->get_directory_info($directory); //preloaded
            }
        }

        return $result;
    }
	/**
	 * Condition for valid user
	 *
	 * @param string $table
	 * @return string
	 */
    function _sql_valid_user($table)
    {
        return "($table.approve=1 AND $table.activate=1 AND $table.deleted=0 AND $table.expired=0 AND $table.suspended=0)";
    }
	/**
	 * Condition for valid product
	 *
	 * @param string $table
	 * @return string
	 */
    function _sql_valid_product($table)
    {
/**
 * For separate type of products only PRODUCT_PROTECT for this model
 * @author kgg
 */
    	return "(($table.`closed`=0)AND($table.`product_type`=".PRODUCT_PROTECT."))";
    }
	/**
	 * Select users and their passwords
	 *
	 * @param mixed $uids
	 * @return array
	 */
    function Load_Users($uids)
    {
        //incoming parameters check
        if (is_array($uids) && empty($uids))
        {
            return array();
        }

        $this->db->select('u.login,u.pass');
        $this->db->from(db_prefix.'Users u');
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');

        if (is_array($uids))
        {//non-empty array of values
            $this->db->where_in('u.id',$uids);
        }
        else
        {//single value
            $this->db->where('u.id='.intval($uids));
        }

        //no need to ask for valid users only, because we need all of them!!!
        //$this->db->where($this->_sql_valid_user('`as`'));

        $query = $this->db->get();
        $result = array();
        foreach ($query->result_array() as $row)
        {
            $result[$row['login']] = $row['pass'];
        }
        return $result;
    }
	/**
	 * Load subscription
	 *
	 * @param mixed $subscr_id
	 * @return array
	 */
    function Load_Subscription($subscr_id)
    {
        $this->db->select('user_id,product_id');
        $this->db->from(db_prefix.'Protection');
        $this->db->where('subscr_id='.intval($subscr_id));
        $query = $this->db->get();
        return $query->row_array();
    }
	/**
	 * Select user info by directory id
	 *
	 * @param mixed $did
	 * @return array
	 */
    function Load_Directory_Users($did)
    {
        $this->db->select('u.id,u.login,u.pass');
        $this->db->from(db_prefix.'Dir_products dp');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('dp.dir_id='.intval($did));
        $this->db->group_by("u.login");
        $query = $this->db->get();
        $result = array();
        return $query->result_array();
    }

	/**
	 * Select directory info by subscription
	 *
	 * @param mixed $subscr_id
	 * @return array
	 */
    function Load_Subscription_Directories($subscr_id)
    {
        $this->db->select('d.*');
        $this->db->from(db_prefix.'Dirs d');
        $this->db->join(db_prefix.'Dir_products dp','dp.dir_id = d.id','LEFT');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');

        //we are not joining Account_status, because we need user directories no matter what user status is

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.id='.intval($subscr_id));

        $query = $this->db->get();
        return $query->result_array();
    }

	/**
	 * Select directories id by subscriptions ststus
	 *
	 * @param mixed $uids
	 * @param mixed $exclude_subscription
	 * @return array
	 */
    function Load_User_Directories($uids, $exclude_subscription = false)
    {
        //incoming parameters check
        if (is_array($uids) && empty($uids))
        {
            $uids = 0;
        }

        $this->db->select('u.id as `uid`,d.*');
        $this->db->from(db_prefix.'Dirs d');
        $this->db->join(db_prefix.'Dir_products dp','dp.dir_id = d.id','LEFT');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //we are not joining Account_status, because we need user directories no matter what user status is

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->group_by("uid,id");
        $this->db->where('s.status=1');

        if ($exclude_subscription > 0)
        {
            $this->db->where('s.id <>'.intval($exclude_subscription));
        }

        //one user or several users?
        if (is_array($uids))
        {//non-empty array of values
            $this->db->where_in('u.id',$uids);
        }
        else
        {//single value
            $this->db->where('u.id='.intval($uids));
        }

        $query = $this->db->get();
        $result = array();
        foreach ($query->result_array() as $row)
        {
            if (!array_key_exists($row['id'], $result))
            {
                $result[$row['id']] = $row;
                unset ($result[$row['id']]['uid']); //this data is incomplete and not needed, valid and complete data is stored in 'users'
                $result[$row['id']]['users'] = array();
            }
            $result[$row['id']]['users'][] = $row['uid'];
        }
        return $result;
    }

	/**
	 * Is user allow access to directory
	 *
	 * @param mixed $uid
	 * @param mixed $did
	 * @return boolean
	 */
    function Is_Access_To_Directory_Allowed($uid,$did)
    {
        $this->db->select('u.id');
        $this->db->from(db_prefix.'Dir_products dp');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('dp.dir_id='.intval($did));
        $this->db->where('u.id='.intval($uid));
        $this->db->group_by("u.id");
        $query = $this->db->get();
//        admin_log('debug', array('sql'=>$this->db->last_query(),'did'=>$did,'uid'=>$uid));
        return ( $query->num_rows() > 0 );
    }

	/**
	 * Is user allow access to product
	 *
	 * @param mixed $pid
	 * @param mixed $uid
	 * @return boolean
	 */
    function Is_Access_To_Product_Allowed($pid,$uid)
    {
        $this->db->select('u.id');
        $this->db->from(db_prefix.'Products prod');
        $this->db->join(db_prefix.'Protection p','prod.id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('prod.id='.intval($pid));
        $this->db->where('u.id='.intval($uid));
        $this->db->group_by("u.id");
        $query = $this->db->get();
//        admin_log('debug', array('sql'=>$this->db->last_query(),'pid'=>$pid,'uid'=>$uid));
        return ( $query->num_rows() > 0 );
    }


    /**
     * Loads language variables for the panel
     *
     * @param array $data array to load variables into
     * @return array $data with loaded variables
     */
    function Load_panel_vars($data)
    {
        //Error messages
        $error_messages = array();
        $error_messages['method'] = "<{directories_add_protection_method_not_selected}>";
        $error_messages['name'] = "<{directories_add_protection_directory_name_is_empty}>";
        $error_messages['http_path'] = "<{directories_add_protection_directory_url_is_empty}>";
        $error_messages['fs_path'] = "<{directories_add_protection_directory_can_not_be_protected}>";
        $error_messages['validation_failed'] = "<{directories_add_protection_unable_to_protect_directory}>";
        $error_messages['insert_failed'] = "<{directories_add_protection_db_unable_to_insert}>";
        $error_messages['update_failed'] = "<{directories_add_protection_db_unable_to_update}>";
        $error_messages['delete_failed'] = "<{directories_add_protection_db_unable_to_delete}>";
        $error_messages['protect_failed'] = "<{directories_add_protection_db_unable_to_protect}>";
        $error_messages['reprotect_failed'] = "<{directories_add_protection_unable_to_reprotect}>";
        $error_messages['unprotect_failed'] = "<{directories_add_protection_unable_to_unprotect}>";
        $error_messages['directory_not_found'] = "<{directories_add_protection_directory_not_found}>";
        $error_messages['directory_not_found_on_server'] = "<{directories_add_protection_directory_not_found_on_server}>";
        $error_messages['assotiated_products'] = array('text'=>"<{directories_add_protection_assotiated_products_exist}>",'display'=>false);
        

        //Temp_vars for js
        $temp_vars_set= array();
        $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
        $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/directories/directory.js";            
        $temp_vars_set['directory_is_not_protectable'] = "<{directories_add_protection_directory_is_not_protectable}>";
        $temp_vars_set['directory_is_already_protected'] = "<{directories_add_protection_directory_is_already_protected}>";
        $temp_vars_set['directory_path_is_too_long'] = "<{directories_add_protection_directory_path_is_too_long}>";
		
		$temp_vars_set['system_directory_is_not_protectable'] = "<{directories_add_protection_system_directory_is_not_protectable}>";
        
        $temp_vars_set['reprotect_begin']="<{directories_reprotect_begin}>";
        $temp_vars_set['reprotect_progress']="<{directories_reprotect_progress}>";
        $temp_vars_set['reprotect_end']="<{directories_reprotect_end}>";

        //Green messages
        $ok_messages = array();
        $ok_messages['directory_has_been_added'] = "<{directories_directory_has_been_added}>";
        $ok_messages['directory_has_been_updated'] = "<{directories_directory_has_been_updated}>";
        $ok_messages['directory_has_been_deleted'] = "<{directories_directory_has_been_deleted}>";
        $ok_messages['directory_has_been_reprotected'] = "<{directories_directory_has_been_reprotected}>";

        $data['temp_vars_set'] = $temp_vars_set;
        $data['ok_messages'] = $ok_messages;
        $data['error_messages'] = $error_messages;

        return $data;
    }


}
?>
