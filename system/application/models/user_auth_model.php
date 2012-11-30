<?php
/**
 * 
 * THIS FILE CONTAINS User_auth_model CLASS
 *  
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
/**
 * Include file auth_model.php
 */
require_once("auth_model.php");
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH USER AUTH
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
class User_auth_model extends Auth_model
{
	/**
	 * cookie prefix
	 *
	 * @var string
	 */
    var $cookie_prefix="user_";
	/**
	 * Set cookie access key
	 *
	 * @param string $login
	 */
       function set_cookie_access_key($login)
    {
        set_cookie(
                    array
                    (
                    'name'=>PROTECTION_COOKIE_NAME,
                    'value'=>build_cookie_hash($login),
                    'expire'=>0,
                    'path'=>'/',
                    'prefix'=>''
                    )
                );
    }

	/**
	 * Unset cookie access key
	 *
	 */
    function unset_cookie_access_key()
    {
        set_cookie(
                    array
                    (
                    'name'=>PROTECTION_COOKIE_NAME,
                    'value'=>'',
                    'expire'=>0,
                    'path'=>'/',
                    'prefix'=>''
                    )
                );
    }

	/**
	 * Prepare login data
	 *
	 * @return array
	 */
    function prepare_login_data()
    {
        $data=array
        (
            'error'=>array
                        (
                        'banned'            =>false,
                        'ban_reason'        =>false,
                        'blocked'           =>false,
                        'block_until'       =>0,
                        'capcha'            =>false,
                        'login_failed'      =>false,
                        'autoban'           =>false,
                        'restricted_area'   =>false
                        ),

	    'login_remember_me'=>$this->get_remember_option(),

	    'login_try_capcha'=>$this->get_login_try_capcha(),

	    'login_try_block_ip'=>$this->get_login_try_block_ip(),

	    'ip_block_timeout'=>$this->get_ip_block_timeout(),

	    'ip_block_selected_period'=>$this->get_ip_block_selected_period(),

	    'autoban_count'=>$this->get_autoban_count(),

	    'autoban_timeout'=>$this->get_autoban_timeout(),

		'show_remember_field'   =>$this->get_remember_option()
        );

        $data['error']['suspended']=false;
        $data['error']['suspended_reason']="";
        $data['error']['activate']=false;
        $data['error']['approve']=false;
        $data['error']['expired']=false;

        return $data;
    }


    /*
        get user status
        return false if user not exists
        or array with user status keys
        ['status_error'] - value is set AND true - when user status is invalid for process login
    */
    /**
     * Get user status
     *
     * @param mixed $id
     * @return mixed
     */
    function get_login_status($id)
    {
        $CI =& get_instance();
        $CI->load->model('user_model');

        if($status=$CI->user_model->get_status($id))
        {
            $status=$status[0];
            $data=array();
            $data['suspended']=(bool)$status['suspended'];
            $data['suspended_reason']=$status['suspend_reason'] ? $status['suspend_reason']: '';

            $data['activate']=(bool)!$status['activate'];
            $data['approve']=(bool)!$status['approve'];
            $data['expired']=(bool)$status['expired'];
            $data['expire']=$status['expire'];

            if  (
                     $data['activate']    ||
                     $data['approve']     ||
                     $data['expired']     ||
                     $data['suspended']
                )
                    $data['status_error']=true;
            return $data;
        }

        return false;
    }
    
    /**
	 * Get redirect after login link
     * @param array $user_info
	 * @return string
	 */
    function Get_redirect_after_login_link($user_info=false)
    {
        $link=config_get('SYSTEM', 'CONFIG', 'LOGIN_REDIRECT');
        if(intval(config_get("system","config","personal_login_redirect_flag")))
        {
            $CI =& get_instance();
            $CI->load->model('user_model');
            $this->is_auth();
            if(intval($this->uid) && !$user_info)
            {
                $user_info = $CI->user_model->get_profile_by_uid($this->uid);
                $user_info=$user_info[0];
            }
            if($user_info && isset($user_info['login_redirect']) && !empty($user_info['login_redirect']))
            {
                $link=$user_info['login_redirect'];
            }
        }
        return $link;
    }


    /*
        overrided for set cokie modrewrite feature
    */
    /**
     * overrided for set cokie modrewrite feature
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
        $this->set_cookie_access_key($login);
        return parent::auth($login, $pwd, $id, $remember,  $REMOTE_ADDR);
    }


	/**
	 * Enter description here...
	 *
	 * @return true
	 */
    function logout()
    {
        $this->unset_cookie_access_key();
        return parent::logout();
    }

	/**
	 * Enter description here...
	 *
	 * @param mixed $uid
	 * @param string $REMOTE_ADDR
	 * @return boolean
	 */
	function autoban($uid,$REMOTE_ADDR)
	{
		$uid = intval($uid);
		if( $uid <=0 )
		{
			return false;
		}

		if( !isset($REMOTE_ADDR) or empty($REMOTE_ADDR) )
		{
			return false;
		}

        // clear expired
        $this->db->where('DATE_ADD(`login_date`,INTERVAL '.intval(config_get('user','security','autoban_timeout')).' SECOND) < CURRENT_TIMESTAMP()');
        $this->db->delete(db_prefix.'User_logins');
        // _clear expired

		$this->db->query('REPLACE '.db_prefix.'User_logins(ip, user_id) VALUES ("'.$REMOTE_ADDR.'", "'.$uid.'")');

		$tries = 0;
		$this->db->select('COUNT(ip) as cnt');
		$query = $this->db->get_where(db_prefix.'User_logins',array('user_id'=>$uid));
        $this->db->distinct();

        if( $query->num_rows() > 0 )
        {
            $res = $query->row();
            $tries = intval($res->cnt);
        }
		if( intval(config_get('USER','SECURITY','autoban_count')) > 0 and intval($tries) > intval(config_get('user','security','autoban_count')))
		{
			$this->db->where('user_id',$uid);
            $this->db->update(db_prefix.'Account_status',array('suspended'=>intval(1)));
            return true;
        }
        return false;
	}


	/**
	 * Function mainly used to clear the records from `User_logins` table after USER_UNSUSPENDED event
	 *
	 * @param integer $uid
	 * @return boolean
	 *
	 * @author Makarenko Sergey
	 * @copyright 2008
	 */
	function Clear_autoban_records_for_user($uid=0)
	{
		$uid = intval($uid);
		if( $uid <=0 )
		{
			return false;
		}

        // clear all records for some user
        $this->db->where('user_id =', $uid);
        $this->db->delete(db_prefix.'User_logins');
        // _clear all records for some user

        return true;
	}

	/**
	 * Check whether login is exist or not
	 *
	 * @param string $login
	 * @return boolean
	 */
    function is_login_exists($login)
    {

        if( !isset($login) or empty($login) )
        {
            return false;
        }

        $this->db->select('id');
        $query = $this->db->get_where(db_prefix.'Users',array('login'=>$login));
        if( $query->num_rows() > 0 )
        {
            return true;
        }


        return false;

    }
	/**
	 * Check whether email is exist or not
	 *
	 * @param string $email
	 * @return boolean
	 */
    function is_email_exists($email,$id='')
    {
        if( !isset($email) or empty($email) or mb_strlen($email) > 255 )
        {
            return false;
        }

        $this->db->select('id');
        $this->db->where('email', $email);
        if(!empty($id))
        {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get(db_prefix.'Users');
        fbq('Email check');
        if( $query->num_rows() > 0 )
        {
            return true;
        }        
        return false;
    }

	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_login_try_capcha()
    {
        return (int) config_get('user','security','login_try_capcha');
    }
	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_login_try_block_ip()
    {
        return (int) config_get('user','security','login_try_block_ip');
    }
	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_ip_block_timeout()
    {
        return (int) config_get('user','security','ip_block_timeout');
    }
	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_ip_block_selected_period()
    {
        return (int) config_get('user','security','ip_block_selected_period');
    }
	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_autoban_count()
    {
        return (int) config_get('user','security','autoban_count');
    }
	/**
	 * Enter description here...
	 *
	 * @return integer
	 */
    function get_autoban_timeout()
    {
        return (int) config_get('user','security','autoban_timeout');
    }



    /*
        OVERRIDED METHODS
        for details see Auth_model ;)
    */
    /**
     * OVERRIDED METHODS for details see Auth_model
     *
     * @return integer
     */
    function get_remember_option()
    {
        return (int) config_get('user','security','login_remember_me');
    }

	/**
	 * Set user status online
	 *
	 * @param mixed $id
	 */
    function set_online($id)
    {
        $id=(int)$id;
        $this->db->query("UPDATE ".db_prefix."Users SET on_line=1, last_online=now() where id=$id");
    }

	/**
	 * Enter description here...
	 *
	 * @param string $login
	 * @param string $pwd
	 * @return mixed
	 */
    function try_login($login, $pwd)
    {
fb($login,'try_login');
    	$this->db->select('id, login, pass, sec_code');
        $this->db->from(db_prefix.'Users');
        $this->db->where('login',   $login);
        $query = $this->db->get();

        if($query->num_rows())
        {
            $query=$query->result_array();
            /*
                IMPORTANT!
                using an stored hash for SALT key
            */
            if($query[0]['pass'] == crypt($pwd, $query[0]['pass']))
            {
	            if(Functionality_enabled('user_auth_product_hosted')===true)
	            {
	            	if (strlen(trim($query[0]['sec_code']))==0 
					|| ((strlen(trim($query[0]['sec_code']))==2) && ($query[0]['sec_code']=="''")))
	            	{
	            		$pwd_enc_bf = ns_encrypt($pwd, $query[0]['pass']);
	            		$data = array('sec_code'=>$pwd_enc_bf);
				        $this->db->where('id', $query[0]['id']);
				        $this->db->update(db_prefix.'Users', $data);
	            	}
	            }
                return $query[0];
            }
        }
        return false;
    }
	/**
	 * Check user account status
	 *
	 * @param string $login
	 * @param string $pass
	 * @param boolean $check_on_line
	 * @return mixed
	 */
    function check_member($login, $pass, $check_on_line=true)
    {
fb($login,"check_member");

        $sess_exp=(int)config_get("system", "config", "session_expiration");


        $this->db->limit(1);
        $this->db->select('id, length(trim(users.sec_code)) as length');
        $this->db->from(db_prefix."Users users");
        $this->db->join(db_prefix."Account_status ac", "users.id=ac.user_id and ac.suspended<>1 and expired <>1");
        $this->db->where(array('login'=>$login, 'pass'=>$pass));
        if(Functionality_enabled('user_auth_product_hosted')===true)
        {
			/**
			 * kgg
			 *
             * @todo for enter password used hosted version
			 * 
			 */
        	$this->db->where("length(trim(users.sec_code))>0 AND trim(users.sec_code)<>''''''");
        }
        
        if($check_on_line)// NOT NEED FOR REMEMBER USERS
        {
            $this->db->where("last_online + $sess_exp  > NOW() AND on_line=1");
        }

        $query = $this->db->get();
        if($query->num_rows())
        {
            $query=$query->row();
            return $query->id;
        }
        return false;
    }
}
?>
