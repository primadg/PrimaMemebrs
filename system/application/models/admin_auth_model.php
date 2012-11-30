<?php
/**
 * 
 * THIS FILE CONTAINS Admin_auth_model CLASS
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
 * THIS CLASS CONTAINS METHODS FOR WORK WITH ADMIN 
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
class Admin_auth_model extends Auth_model
{
	/**
	 * Enter description here...
	 *
	 * @var string
	 */
    var $cookie_prefix="admin_";
    /**
     * Enter description here...
     *
     * @var string
     */
    var $username="";
    /**
     * Enter description here...
     *
     * @var string
     */
    var $last_online="";

//dummy Admin_auth_model based on User_auth_model
//@author Val Petruchek

    //var $is_auth;
    //var $user_is_auth;
    /**
     * id of current administrator
     *
     * @var integer
     */
    var $admin_id; //id of current administrator
	/**
	 * Enter description here...
	 *
	 * @var array
	 */
    var $access_category_array = array(
        'product'=>'',
        'member_control'=>'',
        'transaction'=>'',
        'newsletter'=>'',
        'coupon'=>'',
        'system_configuration'=>'',
        'administrator_control'=>'',
        'activity_logging'=>''
        );
	/**
	 * Enter description here...
	 *
	 * @var array
	 */
    var $email_newsletter_array = array();

    /**
     * Dummy constructor, sets admin_id to 13
     *
     * @return void
     *
     * @author Val Petruchek
     * @copyright 2008
     */
    function Admin_auth_model()
    {
        parent::Auth_model();
        if(isset($_SESSION["NS_SUPPORT_ADMIN"]) && $_SESSION["NS_SUPPORT_ADMIN"]==true)
        {
            $this->is_auth=true;
            $this->uid=1;   
        }
        fb($this->is_auth());
        $this->is_auth();
        $this->admin_id=$this->uid;        
        
        $counter=0;
        foreach($this->access_category_array as $key => $value)
        {
            $this->access_category_array[$key]=(0x1 << $counter);
            $counter++;
        }
        $this->email_newsletter_array=Get_admin_system_emails_tmpl_bits();

    }
    /**
     * Return info about admin with id $id
     *
     * @param integer $id
     * @return mixed
     */
    function Get_admin_info($id)
    {
        $this->db->select('admins.id,admins.login,admins.access_id,access_levels.name as access_level,admins.last_online');
        $this->db->from(db_prefix.'Admins admins');
        $this->db->where('admins.id',$id);
        $this->db->join(db_prefix.'Access_levels access_levels','admins.access_id=access_levels.id','left');
        $query = $this->db->get();
        $admin=$query->result_array();
        return count($admin)>0 ? $admin[0] : false;
    }
    
    /**
     * Change admin status to online
     *
     * @param integer $id
     */
    function set_online($id)
    {
        $id=(int)$id;
        $this->db->query("UPDATE ".db_prefix."Admins SET on_line=1, last_online=now() where id=$id");
    }
    /**
     * Try to login
     *
     * @param string $login
     * @param string $pwd
     * @return mixed
     */
    function try_login($login, $pwd)
    {
        $this->db->select('id, login, pwd, last_online');
        $this->db->from(db_prefix.'Admins');
        $this->db->where('login',   $login);
        $this->db->where('pwd',   md5($pwd));        
                
        $query = $this->db->get();
        
        if($query->num_rows())
        {
            $query=$query->result_array();
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_section_admin_control')!==true && $query[0]['id']!=1)
            {
                return false;
            }
            //*******End of functionality limitations********
            $this->username=$query[0]['login'];
            $this->last_online=$query[0]['last_online'];            
            return $query[0];            
        }
        return false;
    }
    
    function License_check($license_num='', $if_post = false)
   {
       $product = 0;
       if(defined('NS_BASIC_VERSION')) $product = 12;
       if(defined('NS_PRO_VERSION')) $product = 13;
       if(defined('NS_ENTERPRISE_VERSION')) $product = 14;
           if(defined('NS_TRIAL_VERSION'))    $product = 0;

   $license_data['status'] = 'OK';
       /*$license_data['status'] = 'UNCHECKED';
       if ($license_num == '') $license_num
=config_get('SYSTEM','LICENSE_NUMBER');
       if ($product != 0) {
           $license_str = _config_get('SYSTEM','LICENSE_CHECK');
           $license_hash = _config_get('SYSTEM','LICENSE_HESH');
           $license_num = output($license_num);
           if(preg_match('/^[a-z0-9]+$/i', $license_num)){
               $license_data = CheckMegaLicense($license_num,
$license_str, $license_hash, $product);
           }
           else
           {
               $license_data['status']='BAD';
               $license_data['text'] ='Unknown license key';
           }
           if(isset($license_data['cript'])) {
               _config_set($license_data['cript'],'SYSTEM','LICENSE_CHECK');
               if(isset($license_data['hash'])) {
                   _config_set($license_data['hash'],'SYSTEM','LICENSE_HESH');
               }
               if (isset($license_data['status']) &&
$license_data['status']!='BAD')
               {
                   config_set($license_num, 'SYSTEM','LICENSE_NUMBER');
               }
           }
       }  */

       if (!isset($license_data['status']) || $license_data['status']=='BAD')
       {
           config_set(0,'SYSTEM','STATUS','online');
           if(!$if_post){
               $this->license();
           }
       }
       return $license_data;
   }
    
    function License()
    {           
        
        $data=array();
        $data['messages']=array();
        $data['errors']=array(            
        'length'=>"<{admin_dialog_key_length_license}>",
        'unknown_license_key'=>"<{admin_license_error_unknown_license_key}>",
        'invalid_product'=>"<{admin_license_error_invalid_product}>",
        'license_is_disabled'=>"<{admin_license_error_this_license_is_disabled}>",
        'domain_check_failed'=>"<{admin_license_error_domain_check_failed}>",
        'ip_check_failed'=>"<{admin_license_error_ip_check_failed}>",
        'unrecognized_error'=>"<{admin_license_error_unrecognized_error}>",
        'connect_to_the_server'=>"<{admin_license_error_unavailable_connect_to_the_server}>",
        );            
        $data['title']="<{admin_dialog_cheked_license}>";
        $data = $this->load->view("/admin/license_dialog.php", $data, true);
        $result=array(
        "html"=>$data,
        "serviceProcessor"=>"lisenseForm",
        "params" =>array()
        );    
        make_response("service", create_temp_vars_set($result,true), 1);
        config_set(0, 'SYSTEM','STATUS','online');
        exit;

    }
    
	/**
	 * Get last entry of current admin
	 *
	 * @return string
	 */   
     
    function get_last_online()
    {
        $query = $this->db->get_where(db_prefix.'Admins', array('id' => $this->uid),1);
        if($query->num_rows())
        {
            $query=$query->result_array();
            $this->last_online=$query[0]['last_online'];
        }
        return $this->last_online;
    }
    /**
     * Defines the id on login adn password
     *
     * @param string $login
     * @param string $pwd
     * @param boolean $check_on_line
     * @return mixed
     */
    function check_member($login, $pwd, $check_on_line=true)
    {
        $sess_exp=(int)config_get("system", "config", "session_expiration");
        $this->db->limit(1);
        $this->db->select('id');
        $this->db->from(db_prefix."Admins admins");
        $this->db->where(array('login'=>$login, 'pwd'=>$pwd));
        
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
    
	/**
	 * Define if current idmin is superadmin
	 *
	 * @return boolean
	 */
    function isSuperAdmin()
    {
        return ($this->uid==1);
    }
	/**
	 * Check current admin permissions
	 *
	 * @param integer $permission
	 * @param string $section
	 * @return boolean
	 */
    function checkAdminPermissions($permission,$section='')
    {
        /* if($this->isSuperAdmin())
        {
            return true;
        } */

        $CI =& get_instance();
        $admin_id = $this->uid;
        $section = (isset($section) and $section=='ML') ? 'ML' : 'ACL';
        $CI->db->select('admins.id, access_levels.'.$section.' as access_level');
        $CI->db->from(db_prefix.'Admins admins');
        $CI->db->where('admins.id',$admin_id);

        $CI->db->join(db_prefix.'Access_levels access_levels','admins.access_id=access_levels.id','left');
        $CI->db->limit(1);
        $query = $CI->db->get();
        $levels_list=$query->result_array();
        if(count($levels_list)==0)
        {
            return false;
        }
        else
        {
            $access_level = $levels_list[0]['access_level'];
            return ( ($access_level&$permission) == $permission );
        }
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $permission
	 * @param unknown_type $section
	 * @return unknown
	 */
    function isAccessDenied($permission='',$section='')
    {
        $permission = (!isset($permission)||$permission=='') ? ADMINISTRATOR_CONTROL : $permission;
        $result=(!$this->checkAdminPermissions($permission,$section=''));
        $func_enable=Functionality_enabled_by_access_bit($permission);
        $result=!$result ? (is_bool($func_enable)? !$func_enable : $func_enable) : $result;
        return $result;
    }
	/**
	 * Display access denied message
	 *
	 * @param mixed $error
	 */
    function showAccessDenied($error=true)
    {
        $mess_err=array();
        if($error!=true)
        {
            $error=is_array($error) ? $error : array($error);
            foreach($error as $key=>$value)
            {
                $mess_err['error_'+$key]=array('display'=>true,'text'=>$value);
            }   
        }
        else
        {
            $mess_err['error_1']=array('display'=>true,'text'=>'<{admin_msg_er_access_denied}>');       
        }
        $CI = &get_instance();
        $res = $CI->load->view('admin/access_denied',array('mess_err'=>$mess_err),true);
        make_response("output", $res, 1);
    }
    
    //*******************main page function*********************
    
    /**
     * Main page info
     *
     * @param array $data
     * @return array
     */
    function Main_page_info($data=array())
    {
        $CI =& get_instance();
        $data['admin_id'] = $this->uid;
        $this->db->select('login, pwd, last_online');
        $this->db->from(db_prefix.'Admins');
        $this->db->where('id', $this->uid);
        $query = $this->db->get();
        if($query->num_rows())
        {
            $query=$query->result_array();            
            $this->username=$query[0]['login'];
            $this->last_online=$query[0]['last_online']; 
        }
        $data['content'] = "";
        $data['username']=$this->username;
        $data['last_login']=$this->last_online;
        //get 7 days paymants
        $payments=array();
        $this->db->select('TO_DAYS(NOW())-TO_DAYS(date) as how_long, COUNT(transactions.id) as quantity_paid, SUM(summ) as price, subscr.currency_code as currency_code');
        $this->db->from(db_prefix.'Transactions transactions');
        $this->db->join(db_prefix.'Subscriptions as subscr', 'transactions.subscription_id=subscr.id', 'left');
        $this->db->where('TO_DAYS(date)>TO_DAYS(NOW())-7 AND completed=1');
        $this->db->group_by('how_long'); 
        $query = $this->db->get();
        $res=$query->result_array();
        
        for($i=0;$i<7;$i++)
        {
        $day  = mktime(0, 0, 0, date("m")  , date("d")+($i-6), date("Y"));
        $payments[$i]=array();
        $payments[$i]['date']="<{day_name_short_".date ('w',$day)."}> ".nsdate($day,false);
        $payments[$i]['quantity_paid']=0;
        $payments[$i]['price']= amount_to_print(0)." ".config_get('system','config','currency_code');
        }
        
        foreach($res as $val)
        {
        $index=6-$val['how_long'];
        $payments[$index]['quantity_paid']=$val['quantity_paid'];
        $payments[$index]['price']=amount_to_print($val['price'])." ".$val['currency_code'];       
        }
        
        $data['payments']=$payments; 
        //end of get 7 days paymants
        
        $system_status=array();
        $system_status[0]=array();
        $system_status[0]['name']='<{admin_main_page_system_status_global}>';
        $system_status[0]['value']='<{admin_main_page_system_status_'.(config_get('system','status','online')?'online':'offline').'}>';        
        $system_status[1]=array();
        $system_status[1]['name']='<{admin_main_page_system_status_confirmation}>';
        $system_status[1]['value']='<{admin_main_page_system_status_confirmation'.(config_get('system','status','member_need_activation')?'':'_not').'_need}>';
        $system_status[2]=array();
        $system_status[2]['name']='<{admin_main_page_system_status_approving}>';
        $system_status[2]['value']='<{admin_main_page_system_status_approving'.(config_get('system','status','member_need_activation')?'':'_not').'_have}>';
        $system_status[3]=array();
        $system_status[3]['name']='<{admin_main_page_system_status_accounts}>';
        $this->db->where('user_id!=',1);
        $this->db->from(db_prefix.'Account_status');
        $system_status[3]['value']=$this->db->count_all_results();
        $system_status[3]['link']='clickMenu(2,2);';
        $system_status[4]=array();
        $system_status[4]['name']='<{admin_main_page_system_status_expired}>';
        $this->db->where('expired', 1);
        $this->db->where('user_id!=',1);
        $this->db->from(db_prefix.'Account_status');
        $system_status[4]['value']=$this->db->count_all_results();
        $system_status[4]['link']='clickMenu(2,6);';
        $data['system_status']=$system_status;
        
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_server_info')!==true)
        {   
            $admin_server_info = false;
        }
        else
        {
            $admin_server_info = true;
        }
        //*******End of functionality limitations********
        $software_info=array();
        $software_info[0]=array();
        $software_info[0]['name']='version';
        $software_info[0]['value']=defined("NEEDSECURE_VERSION") ? NEEDSECURE_VERSION . (defined("NEEDSECURE_SUBVERSION") ? NEEDSECURE_SUBVERSION : "") : "Unregistered!";
        $software_info[1]=array();
        $software_info[1]['name']='Server';
        $software_info[1]['value']=$admin_server_info ? $CI->input->server('SERVER_SOFTWARE') : "<{demo_server_info_disabled}>";
        $software_info[2]=array();
        $software_info[2]['name']='OS';
        $software_info[2]['value']=$admin_server_info ? Get_Os() : "<{demo_server_info_disabled}>";
        $software_info[3]=array();
        $software_info[3]['name']='MySQL';
        $software_info[3]['value']=$admin_server_info ? $this->db->version() : "<{demo_server_info_disabled}>";
        $software_info[4]=array();
        $software_info[4]['name']='Root Folder';
        $software_info[4]['value']=$admin_server_info ? config_get('system','config','absolute_path') : "<{demo_server_info_disabled}>"; 
        $data['software_info']=$software_info;
        
        $members_statistic=array();
        $members_statistic[0]=array();
        $members_statistic[0]['name']='<{admin_main_page_members_statistic_not_approved}>';
        $this->db->where('approve', 0);
        $this->db->where('user_id!=',1);
        $this->db->from(db_prefix.'Account_status');
        $members_statistic[0]['value']=$this->db->count_all_results();
        $members_statistic[0]['link']='clickMenu(2,3);';
        $members_statistic[1]=array();
        $members_statistic[1]['name']='<{admin_main_page_members_statistic_not_confirmed}>';
        $this->db->where('activate', 0);
        $this->db->where('user_id!=',1);
        $this->db->from(db_prefix.'Account_status');
        $members_statistic[1]['value']=$this->db->count_all_results();
        $members_statistic[1]['link']='clickMenu(2,4);';
        $members_statistic[2]=array();
        $members_statistic[2]['name']='<{admin_main_page_members_statistic_suspended}>';
        $this->db->where('suspended', 1);
        $this->db->where('user_id!=',1);
        $this->db->from(db_prefix.'Account_status');
        $members_statistic[2]['value']=$this->db->count_all_results();
        $members_statistic[2]['link']='clickMenu(2,5);';
        $data['members_statistic']=$members_statistic; 

        return $data;
    }
    
    //***************end of main page function******************

}
?>
