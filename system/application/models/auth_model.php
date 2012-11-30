<?php
/**
 * 
 * THIS FILE CONTAINS Auth_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/*
    base model for auth
*/


//************************************************************************************
//this constant is used to define whether we should limit functionality to DEMO or not
//    define('NS_DEMO_VERSION', TRUE);
//comment the string above, if you don't need DEMO version of NeedSecure
//************************************************************************************
/**
 * 
 * base model for auth
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Auth_model extends Model
{
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
    var $is_auth;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $uid;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $cookie_prefix='';
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Auth_model()
    {
        parent::Model();
        $this->is_auth=false;
        $this->uid=false;
        
        //******************VERSION_DEFINITION******************
        $cur_path=BASEPATH."application/models";
        ns_define_version();	
	    if(file_exists($cur_path."/main_version.php"))
        {
            include_once($cur_path."/main_version.php");
        }

        if(!defined('NS_DEBUG_VERSION') && !defined('NS_BASIC_VERSION') && !defined('NS_PRO_VERSION') && !defined('NS_ENTERPRISE_VERSION') && !defined('NS_DEMO_VERSION'))
        {
            define('NS_DEMO_VERSION', TRUE);
        }        
        
        //this constant is used to define whether we should limit functionality to DEMO or not
        if(defined('NS_DEBUG_VERSION'))
        {
            if(get_debug_params(0)>0)
            {
                //ini_set("allow_call_time_pass_reference",1);
                //error_reporting(E_ALL^E_WARNING);
                //error_reporting(E_ALL);
            }
            //this constant is used to define whether we should limit functionality to TRIAL or not
            if(get_debug_params(8)>0 && !defined('NS_TRIAL_VERSION'))
            {
                define('NS_TRIAL_VERSION', TRUE);
            }
            //this constant is used to define whether we should limit functionality to DEMO or not
            if(get_debug_params(1)>0 && !defined('NS_DEMO_VERSION'))
            {
                define('NS_DEMO_VERSION', TRUE);
            }
            //this constant is used to define whether we should limit functionality to HOSTED or not
            if(get_debug_params(9)>0 && !defined('NS_HOSTED_MODULE'))
            {
                define('NS_HOSTED_MODULE', TRUE);
            }
            //this constant is used to define whether we should limit functionality to PROTECTED or not
            if(get_debug_params(10)>0 && !defined('NS_PROTECTED_MODULE'))
            {
                define('NS_PROTECTED_MODULE', TRUE);
            }
            //this constant is used to define whether we should limit functionality to PRO or not
            if(get_debug_params(4)>0 && !defined('NS_PRO_VERSION'))
            {
                define('NS_PRO_VERSION', TRUE);
            }
            //this constant is used to define whether we should limit functionality to BASIC or not
            if(get_debug_params(5)>0 && !defined('NS_BASIC_VERSION'))
            {
                define('NS_BASIC_VERSION', TRUE);
            }
            
            if(get_debug_params(0)>0 && !defined('DEBUG_RESPONSE_FLAG'))
            {
                define('DEBUG_RESPONSE_FLAG',true);
            }
        }
        else
        {
            if(!defined('NS_PROTECTED_MODULE'))
            {
                define('NS_PROTECTED_MODULE', TRUE);
            }
        }

        $subversion=" enterprise";
        if(defined('NS_PRO_VERSION')){$subversion=" pro";}
        if(defined('NS_BASIC_VERSION')){$subversion=" basic";}
        if(defined('NS_DEBUG_VERSION')){$subversion=" debug".$subversion;}
        if(defined('NS_HOSTED_MODULE')){$subversion=$subversion." hosted";}
        if(defined('NS_PROTECTED_MODULE')){$subversion=$subversion." protected";}
        if(defined('NS_DEMO_VERSION')){$subversion=$subversion." demo";}
        if(defined('NS_UPGRADE')){$subversion=$subversion." upgrade";}       
        if(!defined('NEEDSECURE_SUBVERSION'))
        {
            define('NEEDSECURE_SUBVERSION', $subversion);
        }
        
        //**************END_OF_VERSION_DEFINITION***************
    }
    /**
     * Return system info
     *
     * @return array
     */
    function System_info()
    {
        $diff=$sys_info=phpinfo_array(true);
        $this->db->select_max('id');
        $query = $this->db->get(db_prefix.'System_info');
        $max_id=$query->result_array();
        if(count($max_id) && intval($max_id[0]['id'])>0)
        {
            $max_id=$max_id[0]['id'];
            
            $query=$this->db->get_where(db_prefix.'System_info',array('id'=>$max_id));
            $res=$query->result_array();
            $old_sys_info=unserialize($res[0]['content']);
            $exclusions=array(
            'Apache Environment' => array(
            'REDIRECT_UNIQUE_ID' => '',
            'UNIQUE_ID' => '',
            'REMOTE_PORT' => '',
            'CONTENT_LENGTH' =>''
            ),
            'PHP Variables' => array(
            '_SERVER["REDIRECT_UNIQUE_ID"]' => '',
            '_SERVER["UNIQUE_ID"]' => '',
            '_SERVER["REMOTE_PORT"]' => '',
            '_SERVER["REQUEST_TIME"]' => ''
            ),
            'Environment' => array(
            'AP_PARENT_PID' => ''
            ),
            'HTTP Headers Information' => array(
            'Content-Length' => ''
            )
            );
            //$exclusions=array();
            
            $diff=array_diff_recursive($old_sys_info,$sys_info,true);
            $diff=array_diff_key_recursive($diff,$exclusions);
        }
        
        if(!empty($diff))
        {
            if($this->db->count_all(db_prefix.'System_info')>10)
            {            
                $this->db->select_min('id');
                $query = $this->db->get(db_prefix.'System_info');
                $min_id=$query->result_array();
                $min_id=$min_id[0]['id'];                
                $this->db->delete(db_prefix.'System_info', array('id' => $min_id)); 
            }
            
            $this->db->insert(db_prefix.'System_info',array('content'=>serialize($sys_info))); 
        }
        return array('different'=>$diff,'system_info' => $sys_info);
    }
    /**
     * Get default language
     *
     * @return mixed
     */
    function get_default_language()
    {
        $query = $this->db->get_where(db_prefix.'Languages', array('is_default' => 1),1);
        $result=$query->result_array();
        if(count($result)>0)
        {
            return $result[0]['id'];
        }
        return false;
    }

	/**
	 * Set cookie for language
	 *
	 * @param mixed $lang_id
	 */
    function set_cookie_lang_id($lang_id=false)
    {
        $time_to_live = 60*60*24*365; //TTL - is one year (365 days)

        $CI =& get_instance();
        set_cookie(array(
        'name'=>'lang_id',
        'value'=>($lang_id!==false ? $lang_id : $CI->current_language_id),
        'expire'=>$time_to_live,
        'path'=>'/',
        'prefix'=>''));
    }


    /**
    * returns current language ID from cookie, if no cookie is detected then returns get_default_language()
    *
    * @return integer $lang_id
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function get_cookie_lang_id()
    {
        $CI =& get_instance();
        $CI->load->model('lang_manager_model');

        $languages_info = $CI->lang_manager_model->Get_languages();
        $lang_ids = Array();
        foreach ($languages_info as $lang)
        {
            $lang_ids[] = $lang['id'];
        }

        $lang_id = false;
        if (isset($_COOKIE['lang_id']) && in_array($_COOKIE['lang_id'], $lang_ids))
        {
            $lang_id = $_COOKIE['lang_id'];
        }
        return ($lang_id!==false) ? $lang_id : $this->get_default_language();
    }


    /*
        FUNCTIOONS FOR OVERRIDING
    */
	/**
	 * FUNCTIOON FOR OVERRIDING
	 *
	 * @return false
	 */
    function get_remember_option()
    {return false;}

	/**
	 * FUNCTIOON FOR OVERRIDING
	 *
	 * @return false
	 */
    function set_online($id)
    {return false;}


    /*
        getting from DB member row by login and pwd
        and do checking (online, expired, suspended... etc.)
        return memeber id or zero on false

        @pass hashed password (from session or cookies)
    */

    
    /**
     * FUNCTIOON FOR OVERRIDING
     *
     * @param unknown_type $login
     * @param unknown_type $pass
     * @param unknown_type $check_on_line
     * @return 0
     */
    function check_member($login, $pass, $check_on_line)
    {return 0;}

	/**
	 * Checking email domain
	 *
	 * @param mixed $email
	 * @return mixed
	 */
    function check_email_domain($email)
    {
        $email = prepare_text($email);
        if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
        {
            return false;
        }

        $email_info = explode('@',$email);
        $domain = $email_info[1];

        $this->db->select('status');
        $query = $this->db->get_where(db_prefix.'Email_domains',array('domain'=>$domain));
        if ($query->num_rows() > 0)
        {
            $status = $query->row();
            $status = intval($status->status);
            $status = intval($status);
            if( intval($status)==1 or intval($status)==2 )
            {
                return $status;                                                                                     // Trusted = 1, Denied = 2
            }
        }

        return false;
    }

    //generating remind code
    
    /**
     * generating remind code
     *
     * @param integer $id
     * @param string $table
     * @return mixed
     */
    
    function get_remind_code($id,$table="Admins")
    {
        $table=($table=="admins") ? "Admins" : $table;
        $table=($table!="Admins") ? "Users" : $table;

        $code = md5("petr".time()).md5("vova".time());

        $data = array('remind_code' => $code);
        $this->db->where('id',$id);
        $this->db->update(db_prefix.$table, $data);
        if($this->db->affected_rows()>0)
        {
            return $code;
        }
        return false;
    }

    //comparing remind code
    /**
     * comparing remind code
     *
     * @param string $code
     * @param string $new_pwd
     * @param string $table
     * @return mixed
     */
    function compare_remind_code($code,$new_pwd,$table="Admins")
    {
        $table=($table=="admins") ? "Admins" : $table;
        $table=($table!="Admins") ? "Users" : $table;

        $this->db->select('id');
        $this->db->from(db_prefix.$table);
        $this->db->where('remind_code',$code);
        $this->db->where('id<>',$code);

        $query = $this->db->get();
        $admins_list=$query->result_array();
        if(count($admins_list)>0)
        {
            $data = array('remind_code' => $admins_list[0]['id']);
            if($table=="Admins")
            {
                $data['pwd']=md5($new_pwd);
            }
            else
            {
                $data['pass']=crypt($new_pwd);
            }
            $this->db->where('remind_code',$code);
            $this->db->where('id<>',$code);
            $this->db->update(db_prefix.$table, $data);
            if($this->db->affected_rows()>0)
            {
                return $admins_list[0]['id'];
            }
            return false;
        }
        return false;
    }


    /*
        called when login's failed
    */
    /**
     * called when login's failed
     *
     * @param integer $ip
     */
    function failed_login_try($ip)
    {
        $this->db->insert(db_prefix.'Ip_access_log',array('ip'=>$ip));
    }

	/**
	 * Clear access log for ip
	 *
	 * @param integer $ip
	 */
    function clear_access_log_by_ip($ip)
    {
        $this->db->where('ip',$ip);
        $this->db->delete(db_prefix.'Ip_access_log');
        $this->db->where('ip',$ip);
        $this->db->delete(db_prefix.'Blocked_ip');
    }


    /*
        check login and pass in DB
        @pwd - NOT HASHED PASSWORD!

        if checking success � return hashed password and login,
        or false if failed
        return format
        array(login, pwd , uid)

    */
    /**
     * Enter description here...
     *
     * @param unknown_type $login
     * @param unknown_type $pwd
     * @return false
     */
    function try_login($login, $pwd)
    {
        return false;
    }
    /*
    =======================================================================
    */


    /*
        @pwd checked and hashed password
    */
    /**
     * Save user info in session and cookie
     *
     * @param string $login
     * @param string $pwd
     * @param integer $id
     * @param boolean $remember
     * @param string $REMOTE_ADDR
     * @return true
     */
    function auth($login, $pwd, $id, $remember,  $REMOTE_ADDR)
    {
        $remember_enable=$this->get_remember_option();


        if($remember_enable && $remember)
        {
            $this->set_cookie_auth_info($login, $pwd, $id);
        }

        $this->set_session_auth_info($REMOTE_ADDR, $login, $pwd);


        $this->set_online($id);
        $this->uid=$id;

        session_set('user_id',$this->uid);
        $this->is_auth=true;

        return true;
    }

	/**
	 * Block ip
	 *
	 * @param string $REMOTE_ADDR
	 * @return mixed
	 */
    function block_ip($REMOTE_ADDR)
    {
        if( empty($REMOTE_ADDR) or mb_strlen($REMOTE_ADDR)<1 or mb_strlen($REMOTE_ADDR)>15 )
        {
            return false;
        }

        $ip_block_timeout = config_get('USER','SECURITY','ip_block_timeout');
        $expired_time = mktime()+intval($ip_block_timeout);
        $expire = date('Y-m-d H:i:s',$expired_time);

        $query = $this->db->get_where(db_prefix.'Blocked_ip',array('ip'=>$REMOTE_ADDR));

        if( $query->num_rows() >0 )
        {
            $this->db->where('ip',$REMOTE_ADDR);
            $this->db->update(db_prefix.'Blocked_ip',array('ip'=>$REMOTE_ADDR,'expired'=>$expire));
        }
        else
        {
            $this->db->insert(db_prefix.'Blocked_ip',array('ip'=>$REMOTE_ADDR,'expired'=>$expire));
        }
        return time_left($expire);
    }
	/**
	 * Try to block ip
	 *
	 * @param string $REMOTE_ADDR
	 * @return mixed
	 */
    function try_block_ip($REMOTE_ADDR)
    {
        if((intval(config_get('user', 'security', 'login_try_block_ip'))+intval(config_get('user', 'security', 'login_try_capcha'))) <= $this->tries_count($REMOTE_ADDR))
        {
            return $this->block_ip($REMOTE_ADDR);
        }
        return false;
    }


	/**
	 * Check if time of ip block expired, if not return time
	 *
	 * @param string $REMOTE_ADDR
	 * @return mixed
	 */
    function is_ip_blocked($REMOTE_ADDR)
    {
        if( empty($REMOTE_ADDR) or mb_strlen($REMOTE_ADDR)<1 or mb_strlen($REMOTE_ADDR)>15 )
        {
            return false;
        }

        if(!$block_count = (int)config_get('USER','SECURITY','login_try_block_ip')+config_get('user', 'security', 'login_try_capcha'))
        {
            return false;
        }

        $res=$this->db->query("DELETE FROM ".db_prefix."Blocked_ip WHERE ip='$REMOTE_ADDR' AND NOW() >= expired");
        if(intval($this->db->affected_rows())>0)
        {
            $this->clear_access_log_by_ip($REMOTE_ADDR);
        }

        $block_period = (int)config_get('USER','SECURITY','ip_block_selected_period');

        $res=$this->db->query
        (
        "SELECT NOW() now,
            max(expired) as bp
            FROM ".db_prefix."Blocked_ip
            WHERE
            ip='$REMOTE_ADDR'"
        );
        if($res->num_rows())
        {
            $r=$res->result_array();
            if($r[0]['now']<=$r[0]['bp'])
            {
                $this->db->query("DELETE FROM ".db_prefix."Blocked_ip WHERE ip='$REMOTE_ADDR' AND expired<'{$r[0]['bp']}'");

                return time_left($r[0]['bp']);
            }
        }
        else{
            $res=$this->db->query
            (
            "
            SELECT
            COUNT(ip) as bp
            FROM ".db_prefix."Ip_access_log
            WHERE
            ip='$REMOTE_ADDR' and time>=(NOW()-$block_period)
            HAVING bp >= $block_count
            "
            );

            echo $this->db->last_query()."\n";

            if($res->num_rows())
            {
                $r=$res->result_array();
                /*
                    count of acces tries is bigger than block_tries value
                    and IP must be blocked
                */

                $ip_block_timeout = config_get('USER','SECURITY','ip_block_timeout');
                $expire = mktime()+intval($ip_block_timeout);
                $this->db->insert(db_prefix."Blocked_ip", array("ip"=>$REMOTE_ADDR, "expired"=>$expire));
                return time_left($expire);
            }
        }
        return false;
    }
	/**
	 * Return count of tries
	 *
	 * @param string $REMOTE_ADDR
	 * @return mixed
	 */
    function tries_count($REMOTE_ADDR)
    {
        if( empty($REMOTE_ADDR) or mb_strlen($REMOTE_ADDR)<1 or mb_strlen($REMOTE_ADDR)>15 )
        {
            return false;
        }
        //security_ip_block_select_period
        $ip_block_select_period = config_get('user','security','ip_block_selected_period');


        // clear expired ip blocks
        $this->db->where('DATE_ADD(`time`, INTERVAL '.intval($ip_block_select_period).' SECOND) < CURRENT_TIMESTAMP()');
        $this->db->delete(db_prefix.'Ip_access_log');
        // _clear expired ip blocks

        // count tries
        $this->db->where('ip',$REMOTE_ADDR);
        $this->db->from(db_prefix.'Ip_access_log');
        $ips = $this->db->count_all_results();
        return intval($ips);
    }

    /*
        @pwd � text-plain password
    */
    /**
     * Enter description here...
     *
     * @param string $login
     * @param string $pwd
     * @param string $REMOTE_ADDR
     * @return mixed
     */
    function login($login,  $pwd, $REMOTE_ADDR)
    {
        if( empty($login) or mb_strlen($login)<1 or mb_strlen($login)>32 )
        {
            return false;
        }

        if( empty($pwd) or mb_strlen($pwd)<1 or mb_strlen($pwd)>64 )
        {
            return false;
        }

        if( empty($REMOTE_ADDR) or mb_strlen($REMOTE_ADDR)<1 or mb_strlen($REMOTE_ADDR)>15 )
        {
            return false;
        }

        if($info=$this->try_login($login, $pwd))
        {
            return $info;
        }
        else
        {
            $this->failed_login_try($REMOTE_ADDR);
            return false;
        }
    }

	/**
	 * Enter description here...
	 *
	 * @return boolean
	 */
    function is_auth()
    {
   //  fb($REMOTE_ADDR." | ".$login." | " );
        //IMPORTANT!!!
        //only one checking per request session
        if($this->is_auth)
        return true;
        ///---

        if (! $REMOTE_ADDR = $this->input->ip_address())
        {
            $this->clear_authorize();
            return false;
        }

        $check_on_line=true;

        if(!    $this->is_ip_banned($REMOTE_ADDR))
        {
            if($info=$this->get_session_auth_info())
            {
                //standart situation, when member is online
                //check it ip
                if($REMOTE_ADDR != $info['login_ip'])
                {
                    $this->clear_authorize();
                    return false;
                }
            }

            $remember_enable=$this->get_remember_option();

            if( $remember_enable && ($info2=$this->get_cookie_auth_info() ) )
            {
                //member is not online
                //but it try to remember ;)

                if(!$info)
                $info=$info2;
                $check_on_line=false;//if rememberring -- dont check online
            }
            /*
                getting from DB user info by login and pass
            */
            if($info)
            {
                if($this->uid = $this->check_member($info['login'], $info['pwd'], $check_on_line))
                {
                    $this->set_online($this->uid);
                    $this->is_auth=true;
                    return true;
                }
                else
                {
                    $this->clear_authorize();
                }
            }
        }
        return false;
    }

	/**
	 * Loguot user
	 *
	 * @return true
	 */
    function logout()
    {
        $this->clear_authorize();

        $this->is_auth = false;
        $this->uid = false;
        return true;
    }


    /*
        @pwd � hashed password
    */
    /**
     * Sets session auth info
     *
     * @param unknown_type $ip
     * @param string $login
     * @param string $pwd  hashed password
     */
    function set_session_auth_info($ip, $login, $pwd)
    {
        $crypt_key = config_get('user','security','crypt_cookie_key');

        session_set($this->cookie_prefix."login_ip", $ip);

        session_set($this->cookie_prefix."enc_login", base64_encode(encrypt($login, $crypt_key)));
        session_set($this->cookie_prefix."enc_pwd", base64_encode(encrypt($pwd, $crypt_key)));
    }


    /*
        @pwd � hashed password
    */
    /**
     * Sets cookie auth info
     *
     * @param string $login
     * @param string $pwd hashed password
     * @param unknown_type $id
     */
    function set_cookie_auth_info($login, $pwd, $id)
    {
        $crypt_key = config_get('user','security','crypt_cookie_key');
        $ttl=(int) config_get('system','config','remember_time');


        set_cookie(array(
        'name'=>$this->cookie_prefix.'enc_login',
        'value'=>base64_encode(encrypt($login, $crypt_key)),
        'expire'=>$ttl,
        'path'=>'/',
        'prefix'=>''));


        set_cookie(array(
        'name'=>$this->cookie_prefix.'enc_pwd',
        'value'=>base64_encode(encrypt($pwd, $crypt_key)),
        'expire'=>$ttl,
        'path'=>'/',
        'prefix'=>''));

        $this->set_cookie_lang_id();
    }

	/**
	 * Get session auth info.
	 *
	 * @return mixed
	 */
    function get_session_auth_info()
    {
        $crypt_key = config_get('USER','SECURITY','crypt_cookie_key');

        $ip=session_get($this->cookie_prefix."login_ip");
        $login=base64_decode(session_get($this->cookie_prefix."enc_login"));
        $pwd=base64_decode(session_get($this->cookie_prefix."enc_pwd"));

        if($ip && $login && $pwd)
        {
            return
            array(
            "login_ip"  =>$ip,
            "login"     =>decrypt($login, $crypt_key),
            "pwd"     =>decrypt($pwd, $crypt_key)
            );
        }
        return false;
    }

	/**
	 * Get cookie auth info
	 *
	 * @return mixed
	 */
    function get_cookie_auth_info()
    {
        $crypt_key = config_get('USER','SECURITY','crypt_cookie_key');

        $enc_login=@base64_decode(@$_COOKIE[$this->cookie_prefix.'enc_login']);
        $enc_pwd=@base64_decode(@$_COOKIE[$this->cookie_prefix.'enc_pwd']);

        if($enc_login && $enc_pwd)
        {

            return
            array
            (
            "login" =>decrypt($enc_login, $crypt_key),
            "pwd"   =>decrypt($enc_pwd, $crypt_key)
            );
        }
        else
        return false;
    }


    /*
        needs to call when authirization checking if failed
    */
    /**
     * Clear session and cookie. Needs to call when authirization checking if failed
     *
     */
    function clear_authorize()
    {
        session_set("user_id", "");

        session_set($this->cookie_prefix."login_ip", "");

        session_set($this->cookie_prefix."enc_login", "");
        session_set($this->cookie_prefix."enc_pwd", "");

        set_cookie(array(
        'name'=>$this->cookie_prefix.'enc_login',
        'value'=>"",
        'expire'=>0,
        'path'=>'/',
        'prefix'=>''));


        set_cookie(array(
        'name'=>$this->cookie_prefix.'enc_pwd',
        'value'=>"",
        'expire'=>0,
        'path'=>'/',
        'prefix'=>''));
    }


    /*
        FALSE IF-NOT! BANNED
    */
    /**
     * Check is ip banned
     *
     * @param string $REMOTE_ADDR
     * @return boolean
     */
    function is_ip_banned($REMOTE_ADDR)
    {
        if(!isset($REMOTE_ADDR))
        {
            return false;
        }
        $cur_ip = $REMOTE_ADDR;

        $this->db->select('id,ip as value');
        $query = $this->db->get(db_prefix.'Banned_ip');

        if( $query->num_rows() <= 0 )
        {
            return false; // Ip is not banned
        }
        else
        {
            $a_ip = $query->result_array();
        }

        $s_parts=preg_split("/\./", $cur_ip);

        $is_banned=false;
        if(is_array($a_ip) && count($a_ip))
        {
            foreach($a_ip as $k => $ip)
            {
                $temp_ip=$ip;
                $reason=true;
                //$reason = $ip['reason'];
                $ip=$ip['value'];

                if( !isset($reason) or empty($reason) )
                {
                    $reason=true;
                }

                if(strstr($ip, "-"))
                {
                    /*
                        10.10.110.10    -   10.10.110.30
                    */
                    if($_tip=preg_split("/-/", $ip))
                    {
                        $l_ip=$_tip[0];         //10.10.110.10  -- left ip, smaller

                        $r_ip=$_tip[1];         //10.10.110.30  -- left ip, bigger

                        $l_parts=preg_split("/\./", $l_ip);

                        $r_parts=preg_split("/\./", $r_ip);

                        if(count($l_parts)!=4 || count($r_parts)!=4)
                        return false; // err

                        $l_ip=($l_parts[3]+($l_parts[2]*256)+($l_parts[1]*65536)+($l_parts[0]*16777216));

                        $r_ip=($r_parts[3]+($r_parts[2]*256)+($r_parts[1]*65536)+($r_parts[0]*16777216));

                        $s_ip=($s_parts[3]+($s_parts[2]*256)+($s_parts[1]*65536)+($s_parts[0]*16777216));

                        if($s_ip<$l_ip || $s_ip>$r_ip)
                        {
                            //return false; // ip is not banned
                        }
                        else
                        {
                            $is_banned=$temp_ip;
                            break;
                            //return $reason; // ip is banned. return reason
                        }
                    }
                }
                elseif( !(mb_strpos($ip,"*")===false) )
                {
                    /*
                    Any of ip class may be `*`

                    10.10.1.*
                    or
                    10.*.*.3
                    */
                    $parts=preg_split("/\./", $ip);
                    //$flag=false; // ip is not banned as default

                    $zvezda = intval(0);
                    $matches = intval(0);
                    foreach($parts as $k=>$sip)
                    {
                        if($sip=='*')
                        {
                            $zvezda++;
                        }
                        else
                        {
                            if($sip==$s_parts[$k])
                            {
                                $matches++;
                            }
                            else
                            {
                                //return false; // ip is out of range!
                            }
                        }
                    }

                    if( intval($zvezda) + intval($matches) == 4 )
                    {
                        $is_banned=$temp_ip;
                        break;
                        //return $reason;
                    }

                }
                else
                {
                    /*
                if source ip fully consistent with the rule-ip from DB
                */
                    if($cur_ip==$ip)
                    {
                        $is_banned=$temp_ip;
                        break;
                        //return $reason; // ip is banned
                    }
                }
            }
            if($is_banned!==false)
            {
            $t=array();
            $t[0]=$is_banned;
            //$query->free_result();
            $CI =& get_instance();
            $CI->load->model("lang_manager_model");
            $t=$CI->lang_manager_model->combine_with_language_data($t,12,array('name'=>'reason'),'id',array('col'=>'value'),1,&$add_params);
            return empty($t[0]['reason']) ? true : $t[0]['reason'];
            }
        }
        return false; // ip is not banned for default
    }

}
?>
