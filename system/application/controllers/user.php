<?php
/** 
 * 
 * THIS FILE CONTAINS User CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file user_controller.php
 */
require_once("user_controller.php");
/**
 * 
 * THIS class is intended for work with user
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class User extends User_Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function User()
    {
        parent::User_Controller();
    }
    
    /**
	 * Redirect to the mian page
	 * 
	 * @global uknown 
	 *
	 */
    function index()
    {
        global $_helper_CONFIG;
        redirect('user/main');
    }
    /**
     * Enter description here...
     *
     */
    function remote_login_form()
    {
        $data=array();
        $this->load->model('user_auth_model');
        if($this->user_auth_model->is_auth())
        {
            $this->load->model('user_model');
            if(false!==($d=$this->user_model->get_profile_by_uid($this->user_auth_model->uid)))
            {
                $data=$d[0];
            }
        }
        $data['base_url']=base_url();
        $data['login_label']=(Functionality_enabled('admin_member_email_authentication')===true) ? "<{user_login_email}>" : "<{user_login_login}>";
        $f=_view("user/remote_login_form.html", $data,true,'remote');
        $f=replace_lang($f); 
        $f=str_replace("\r","",$f);
        $f=str_replace("\n","",$f);
        $f=str_replace("\t","",$f);
        $f=str_replace("\"","\\\"",$f);
        echo "nsLoginFormObject.nsLoginFormInsert(\"".$f."\");";        
    }

    /**
    * main Log In method for member interface
    *
    * @param array $not_auth - is $red_url that contains redirect_url
    * @param string $red_url  - self-64encoded url
    *
    * @author Petr Yaroshenko
    * @finished Makarenko Sergey @ 06.10.2008 13:35:36
    * @copyright 2008
    */
    function login($not_auth=false, $red_url='')
    {
        //redirect after remind password
        if($not_auth=='remind_password')
        {
            $remind_password_message=$red_url;
            $not_auth=false;
            $red_url='';
        }
        //end of redirect after remind password
        
        $data = $this->user_auth_model->prepare_login_data();
        $data['ip']=$this->input->ip_address();

        //if not blocked or banned
        if(!(  $data['error']['ban_reason']     = $this->user_auth_model->is_ip_banned($data['ip']) )
                &&
                !(  $data['error']['block_until']    = $this->user_auth_model->is_ip_blocked($data['ip']) ))
        {

            if($this->user_auth_model->is_auth())
            {
                //if allready authorized - go away
                //$redirect_location = config_get('SYSTEM', 'CONFIG', 'LOGIN_REDIRECT');
                $redirect_location = $this->user_auth_model->get_redirect_after_login_link();
                if( empty($redirect_location) || $redirect_location == false )
                {
                    redirect('user/info');
                }
                else
                {
                    header('Location: '.$redirect_location);
                }
                die;
            }


            if($not_auth && $red_url)
            {
                session_set('red_url', $data['red_url']=decode_url($red_url));
            }
            else
            {
                $data['red_url']=session_get('red_url');
            }

            /* show error message, about access denied*/
            if($data['red_url'])
            {
                $data['error']['restricted_area']=true;
            }

            $data['show_capcha']=$this->_is_capcha2show($data['ip']);
            $data['show_capcha']=true;
            /*
                ip not banned and blocked
                AND FORM IS SUBMITED
            */
            if($this->input->post('action') ==  'login')
            {
                if(isset($_COOKIE['PHPSESSID']))
                {
                    if(!($this->_is_capcha2check($data['ip'])) or (check_code(input_post('capcha_code', ''))))
                    {
                        $data['login']=input_post('login', '');
                        $data['pwd']=input_post('pwd', '');
                        $data['remember']=(int)input_post('remember', '');

                        if($data['login'] && $data['pwd'] && $data['ip'] && ($info=$this->user_auth_model->login($data['login'],  $data['pwd'], $data['ip'])))
                        {
                            //restore lang_id in COOKIE to variable from DataBase for this user_ID
                            $lang_id = $this->user_model->get_lang($info['id']);
                            $this->user_auth_model->set_cookie_lang_id($lang_id);
                            //_restore lang_id in COOKIE to variable from DataBase for this user_ID

                            $status = $this->user_auth_model->get_login_status($info['id']);

                            $this->user_auth_model->clear_access_log_by_ip($data['ip']);

                            $data['error']=array_merge($data['error'], $status);
                            if  (!isset($status['status_error']))
                            {
                                //    check login for AUTOBAN
                                if( ! ($data['error']['autoban'] = $this->user_auth_model->autoban( $info['id'], $data['ip'])) )
                                {
                                    //Clear stats of failed login tries
                                    //                                              $this->user_auth_model->clear_access_log_by_ip($data['ip']);

                                    //LOGIN SUCCESS
                                    $this->user_auth_model->auth($info['login'], $info['pass'], $info['id'], $data['remember'], $data['ip']);

                                    //log this log in to the "User_logs" table in DB
                                    $this->User_log_model->set($info['id'], $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/login');

                                    if($data['red_url'])
                                    {
                                        session_set('red_url', '');
										if(($functionality_enabled_error=Functionality_enabled('admin_member_info_modify',intval($this->user_auth_model->uid)))!==true)
        								{
											$data['red_url'] = base_url().'market/is_demo_user';
										}
                                        header('Location: '.$data['red_url']);
                                        die;
                                    }
                                    else
                                    {
                                        //$redirect_location = config_get('SYSTEM', 'CONFIG', 'LOGIN_REDIRECT');
                                        $redirect_location = $this->user_auth_model->get_redirect_after_login_link();
                                        if( empty($redirect_location) || $redirect_location == false )
                                        {
                                            redirect('user/info');
                                        }
                                        else
                                        {
                                            header('Location: '.$redirect_location);
                                        }
                                        die;
                                    }
                                }
                                else // login is autobanned
                                {
                                    $data['status_error']=true;
                                    //if AUTOBANNED - logout
                                    $this->user_auth_model->logout();
                                }
                            }

                        }
                        else
                        {
                            $data['error']['login_failed']=true;
                            $data['status_error']=true;
                            if(($ip_error=$this->user_auth_model->try_block_ip($data['ip']))!==false)
                            {
                                $data['error']['block_until'] = $ip_error;
                                $data['error']['blocked'] = true;
                            }
                        }

                    }
                    else
                    {
                        $this->user_auth_model->failed_login_try($data['ip']);
                        $data['error']['capcha'] = true;
                        $data['status_error']=true;
                        if(($ip_error=$this->user_auth_model->try_block_ip($data['ip']))!==false)
                        {
                            $data['error']['block_until'] = $ip_error;
                            $data['error']['blocked'] = true;
                        }
                    }
                }
                else
                {
                    $data['error_text']="<{admin_login_form_msg_er_cookies_disabled}>";
                }

            }
        }
        else
        {
            //$data['error']['blocked']=(bool)$data['error']['block_until'];
            if(($ip_error=$this->user_auth_model->try_block_ip($data['ip']))!==false)
            {
                $data['error']['block_until'] = $ip_error;
                $data['error']['blocked'] = true;
            }
            if($data['error']['banned'] = (bool) $data['error']['ban_reason'])
            {
                /*
                        if banned and ban_reason is empty - convert ban_reason to string.
                        because if it will be convert into language constant
                    */
                if(!is_string($data['error']['ban_reason']))
                {
                    $data['error']['ban_reason']='';
                }
            }
        }

        /*
                LOAD LOGIN TEMPLATE

                all error messages inside $data array:
                $data['error']['banned']             true - ip is banned
                $data['error']['blocked']            true - ip blocked
                $data['error']['capcha']             true - capcha input invalid
                $data['error']['login_failed']       true - login or pass missed
                $data['error']['autoban']            true - login is autobanned

                    ****
                    after login check
                            ['suspended']            true - account suspended
                            ['suspended_reason']          - contain explanetions, for reason why account suspended
                            ['expired']            true - account is expired
                            ['expire']                  - date of expiration (0 - no expiration)
                            ['activate']           true - account is not activate
                            ['approve']            true - account is not approved
            */
        $data['show_capcha']=$this->_is_capcha2show($data['ip']);
        // used for displaying the errors in login_page view
        foreach ($data['error'] as $err_name=>$err_value)
        {
            if ($err_value === true && $err_name != 'status_error' && $err_name != 'restricted_area')
            {
                //***********Functionality limitations***********
                if($err_name=='login_failed' && Functionality_enabled('admin_member_email_authentication')===true)
                {
                    $err_name='email_login_failed';
                }
                //*******End of functionality limitations********
                
                $data['error_text'] = '<{user_login_error_'.$err_name.'}>';
                switch ($err_name)
                {
                case 'suspended':
                    $data['error_text'] .= " ".$data['error']['suspended_reason'];
                    break;
                case 'blocked':
                    $data['error_text'] .= "<span id='lf_ip_block_period'>".$data['error']['block_until']."</span>";
                    break;
                case 'expired':
                    $data['error_text'] .= nsdate($data['error']['expire'],false);
                    break;
                } // switch
                break; //foreach
            }
        }
        // if there is no errors to show then check&show $data['error']['restricted_area']
        if ($data['error']['restricted_area'] === true && !isset($data['error_text']))
        {
			$CI=&get_instance();
		    $CI->load->model('directories_model');
			$data['error_text'] = $CI->directories_model->check_access_type($data['red_url']);
        }
        
        if(isset($remind_password_message))
        {
            $data['mess_text']=$remind_password_message;
        }
        
        if($this->_remote_login($data))
        {
            exit;
        }
        
        //_view("user/login_page", $data);
        $this->print_login_page($data);
    }
    
    function print_login_page($data)
    {
        //parser_patch
        $data['message_box']=isset($data['message_box']) ? $data['message_box'] : array();
        if(isset($data['mess_text']) && !empty($data['mess_text']))
        {
            $data['message_box'][]=array('display'=>1,'text'=>$data['mess_text']);            
        }
        $data['error_box']=isset($data['error_box']) ? $data['error_box'] : array();
        if(isset($data['error_text']) && !empty($data['error_text']))
        {
            $data['error_box'][]=array('display'=>1,'text'=>$data['error_text']);            
        }
        if(!isset($_COOKIE['PHPSESSID']))
        {
            $data['error_box']['user_error']=array('display'=>0,'text'=>"<{admin_login_form_msg_er_cookies_disabled}>");            
        }
        $data['show_capcha']=(isset($data['show_capcha']) && intval($data['show_capcha'])==1) ? array(array()) : array();
        $data['show_remember_field']=(isset($data['show_remember_field']) && intval($data['show_remember_field'])==1) ? array(array()) : array();
        $data['demo_info']=array();
        $data['user_login']='';
        $data['user_password']='';
        if(defined('NS_DEMO_VERSION'))
        {
            $data['demo_info'][]=array();
            $data['user_login']='user';
            $data['user_password']='user';            
        }
        $data['user_login_title']=Functionality_enabled('admin_member_email_authentication')===true ? "<{user_login_email}>" : "<{user_login_login}>";
        
        $data['rand_code']=(isset($data['rand_code']) && !empty($data['rand_code'])) ? array(array('content'=>$data['rand_code'])) : array();
        $data['red_url']=(isset($data['red_url']) && !empty($data['red_url'])) ? array(array('content'=>encode_url($data['red_url']))) : array();
        
        $data['show_not_auth']=(isset($data['show_not_auth']) && intval($data['show_not_auth'])>1) ? array(array()) : array();
        
        print_page('user/login_page.html',$data);
        
        //end_of_parser_patch
    }
    
    /**
     * Process and redirect to alternative login form 
     * @author onagr
     * @param array $data
     * @return boolean
     */
    function _remote_login($data)
    {
        if(false!==($url=parse_url(base_url())))
        {
            $base_url=$url['host'].(isset($url['path']) ? $url['path'] : "");
            $login_page=config_get("SYSTEM","CONFIG","login_page");
            $login_page=(strpos($login_page,$base_url."user/login")===false) ? $login_page : "";
            $login_page=(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$base_url)===false && isset($_POST['action']) && $_POST['action']=='login') ? $_SERVER['HTTP_REFERER'] : $login_page;
            
            if(!empty($login_page) && false!==($u=parse_url($login_page)))
            {
                //remoute GET params
                $query=array();
                if(isset($u['query']))
                {
                    $q=explode("&",$u['query']);
                    foreach($q as $v)
                    {
                        //clear old ns params
                        if(strpos($v,"ns_")!==0)
                        {
                            $query[]=$v; 
                        }                            
                    }
                }
                
                //add ns error params
                if(isset($data['error']))
                {
                    $e=$data['error'];
                    if(isset($e['banned']) && intval($e['banned'])>0)
                    {
                        $query[]="ns_banned=".(isset($e['ban_reason']) ? base64_encode($e['ban_reason']) : "");
                        unset($e['banned']);
                        unset($e['ban_reason']);
                    }
                    if(isset($e['blocked']) && intval($e['blocked'])>0)
                    {
                        $query[]="ns_blocked=".(isset($e['block_until']) ? base64_encode($e['block_until']) : "");
                        unset($e['blocked']);
                        unset($e['block_until']);
                    }
                    if(isset($e['suspended']) && intval($e['suspended'])>0)
                    {
                        $query[]="ns_suspended=".(isset($e['suspended_reason']) ? base64_encode($e['suspended_reason']) : "");
                        unset($e['suspended']);
                        unset($e['suspended_reason']);
                    }
                    foreach($e as $k=>$v)
                    {
                        if(intval($v)>0)
                        {
                            $query[]="ns_".$k."=error";
                        }
                    }
                }
                
                //add ns params
                if(isset($data['show_remember_field']) && intval($data['show_remember_field'])>0)
                {
                    $query[]="ns_show_remember_field=1";
                }
                if(isset($data['show_capcha']) && intval($data['show_capcha'])>0)
                {
                    $query[]="ns_show_capcha=1";
                }
                if(isset($data['login']))
                {
                    $query[]="ns_login=".base64_encode($data['login']);
                }
                if(isset($data['error_text']))
                {
                    $query[]="ns_error_text=".base64_encode(replace_lang($data['error_text']));
                }                        
                if(isset($data['red_url']) && !empty($data['red_url']))
                {
                    $query[]="ns_red_url=".encode_url($data['red_url']);
                }
                
                $back_url=(isset($u['scheme']) ? $u['scheme']."://" : "").$u['host'].(isset($u['path']) ? $u['path'] : "").(count($query) ? "?".implode("&",$query) : "");
                header('Location: '.$back_url);
                return true; 
            }             
        }
        return false;
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $ip
	 * @return mixed
	 */
    function _is_capcha2show($ip)
    {
        /*
        echo "capcha_show";
        echo (int) config_get('user', 'security', 'login_try_capcha') <= $this->user_auth_model->tries_count($ip);
        echo "<br/>";
*/
        return config_get('user', 'security', 'login_try_capcha') <= $this->user_auth_model->tries_count($ip);
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $ip
	 * @return mixed
	 */
    function _is_capcha2check($ip)
    {
        return config_get('user', 'security', 'login_try_capcha') <= $this->user_auth_model->tries_count($ip);
    }
	/**
	 * Checks on login existence
	 *
	 * @global unknown
	 * @return mixed
	 */
    function check_available_login()
    {
        global $_helper_CONFIG;

        $login = mb_substr(prepare_text($this->input->post('login')),0,64);
        if(!isset($login) or empty($login))
        {
            make_response('output','false',1); // already exists
            return false;
        }
        $this->db->select('id');
        $query = $this->db->get_where(db_prefix.'Users',array('login'=>input_text($login)));
        if( $query->num_rows() <= 0 )
        {
            make_response('output','true',1); // do not exists
            return false;
        }


        make_response('output','false',1); // already exists
    }
	/**
	 * Checks on email existence
	 *
	 * @global unknown
	 * @return boolean
	 */
    function check_available_email()
    {
        global $_helper_CONFIG;

        // load models
        $this->load->model('user_auth_model');
        $this->load->model('user_model');
        $this->load->model('user_log_model');
        // _load models

        $email = mb_substr(prepare_text($this->input->post('email')),0,255);
        $domain_check = intval($this->input->post('domain_check'));
        if(!isset($email) or empty($email))
        {
            make_response('output','false',1); // already exists
            return false;
        }
        $this->db->select('id');
        $query = $this->db->get_where(db_prefix.'Users',array('email'=>input_text($email)));
        if( $query->num_rows() <= 0 )
        {

            if( intval($domain_check) > 0 )
            {
                //if( intval($this->user_auth_model->check_email_domain($email))!=1 )
                if( intval($this->user_auth_model->check_email_domain($email))!=2 )
                {
                    make_response('output','true',1); // not banned
                    return true;
                }
            }
            else
            {
                make_response('output','true',1); // do not exists
                return true;
            }

        }


        make_response('output','false',1); // already exists
    }

	/**
	 * Enter description here...
	 *
	 * @return true
	 */
    function protect()
    {
        // load models
        $this->load->model('user_auth_model');
        $this->load->model('user_model');
        $this->load->model('user_log_model');
        // _load models
        if($this->user_auth_model->is_auth())
        {
            echo "<h1>SUCCESSFULL!!!!!!</h1>";
        }
        else
        {
            session_destroy();
            $current_url = site_url('user/protect');
            $site = encode_url($current_url);
            redirect('user/login/1/'.$site,'refresh');
            return true;
            echo "<h1 style=\"color:Red;\">PERMISSION ERROR!!!!!!</h1>";
        }


        return true;


    }
    
    function Value_exist()
    {
        $this->load->model('user_model');
        $result=$this->user_model->value_exist(prepare_post());
        validation_response($result['name'],$result['value'],$result['is_error'],$result['error_text']);
    }
    
    
    /**
	 * Registrate a new user
	 *
	 * @return true
	 */
    function register()
    {
        // load models
        $this->load->model('user_auth_model');
        $this->load->model('user_model');
        $this->load->model('user_log_model');
        $this->load->model('config_model');
        // _load models
        
        $post=prepare_post();
        //$post['image_code']=isset($post['capcha_code']) ? $post['capcha_code'] : '';
        
        $data=$this->user_model->registration_init();
        
        //if registration not allowed, ip is banned or user authorezed
        if(true!==($result=$this->user_model->registration_allowed($data)))
        {
            $this->print_login_page($result);
            return false;
        }
        
        if(isset($post['action']) && $post['action']=='register')
        {
            $data=array_merge($data,$post); 
            fb($data,"DATA 0");
            $data=$this->user_model->registration_check($data);
            if(!is_msg_displayed($data['error_box']))
            {
                $data['enc_pwd'] = crypt($data['password']);
				$data['enc_pwd_bf'] = ns_encrypt($data['password'],$data['enc_pwd']);
                
				// DO REGISTER                
                $user_id = intval($this->user_model->register(
                $data['login'],
                $data['enc_pwd'],
                $data['email'],
                $data['fname'],
                0 /*expire*/,
                $data['lname'],
                $data['member_need_activation'],
                $data['activation_code'],
                $data['enc_pwd_bf']
                ));
                
                if($user_id>0)
                {
                    // insert addition fields
                    $_POST['id']=$data['user_id']=$user_id;
                    if($data['additional'])
                    {
                        $errs=set_user_add_fields($_POST);
                        if($errs !== true)
                        {
                            foreach($errs as $key=>$value)
                            {
                                $data['error_box'][$key]=array('display'=>1,'text'=>$value);
                            }
                        }
                    }
                    // _insert addition fields

                    //Send email to user and admins
                    $data=$this->user_model->registration_send_emails($data);
                    //_send email
                }
                else
                {
                    $data['error_box']['db_error']=array('display'=>1,'text'=>"<{user_registration_db_error}>");
                }
                // _DO REGISTER                
            }
            //Load successfull page
            if(!is_msg_displayed($data['error_box']))
            {
                print_page('user/register_success.html',array('need_activation'=>(intval(config_get('SYSTEM','CONFIG','member_need_activation'))>0 ? array(array()) : array())));
                return true;
            }
        }
        
        if(isset($data['user_add_fields']) && isset($data['user_add_fields']['values']))
        {
            $data['add_fields'] = get_user_add_fields_view(false,false,$data['user_add_fields']['values']);
        }
        else
        {
            $data['add_fields'] = get_user_add_fields_view();
        }
        
        $data['generate_password_checked']=(isset($data['generate']) && $data['generate']) ? "checked" : "";
        $data['tos']=(isset($data['tos']) && $data['tos']) ? "checked" : "";
        fb($data,'Data 1');
        //Load a form of registration        
        $data['form_fields']=array();
        foreach($data['fields'] as $key=>$value)
        {
            $data[$key]=isset($data[$key]) ? output($data[$key]) : "";            
            $data['form_fields'][$key]=array('if_field_submit_enabled'=> (($key=='tos') ? array(array()) : array()));
            foreach($data['fields'] as $id=>$field)
            {
                if($key==$id)
                {
                    $maxlength=255;
                    if(isset($field['length']) && is_array($field['length']))
                    {
                        $maxlength=(isset($field['length']['limit']) && $field['length']['limit']) ? $field['length']['limit'] : $maxlength;
                        $maxlength=(isset($field['length']['max']) && $field['length']['max']) ? $field['length']['max'] : $maxlength;
                    }
                    $data['form_fields'][$key]['field_maxlength']=$maxlength;
                    foreach($field as $k=>$v)
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                    }
                }
                else
                {
                    $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                    $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                }
            }
        }        
        print_page('user/registration.html',$data);
        return true;
    }
    

	/**
	 * Registrate a new user
	 *
	 * @return true
	 */
    function register1()
    {
        // load models
        $this->load->model('user_auth_model');
        $this->load->model('user_model');
        $this->load->model('user_log_model');
        // _load models

        //if_user_authorezed
        if($this->user_auth_model->is_auth())
        {
            redirect("user/info");
            die;
        }
        //end_of_if_user_authorezed

        $REMOTE_ADDR = $this->input->ip_address();
        $post=prepare_post();
        $data = array();
        //load user script
        $data['user_scripts']=base_url()."js/user/registration_init.js";
        //send configuration to user js window.server
        $temp_vars_set=array();
        $temp_vars_set['password_protection0'] = "Bad protection";
        $temp_vars_set['password_protection3'] = "Average protection";
        $temp_vars_set['password_protection4'] = "Good protection";
        $temp_vars_set['password_protection5'] = "Excellent protection";
        $temp_vars_set['password_not_match'] = "Passwords do not match";
        $temp_vars_set['password_is_match'] = "Passwords match";
        $data['config_script']=array('temp_vars_set'=>$temp_vars_set);
        $data['login']='';
        $data['email']='';
        $data['fname']='';
        $data['lname']='';
        $data['tos']='';
        $data['submit_disabled']='disabled';
        $data['login_authentication']=(Functionality_enabled('admin_member_email_authentication')!==true) ? array(array()) :array();
        $data['error_box']=array();
        //if_ip_is_banned
        if(($is_ip_banned_reason=$this->user_auth_model->is_ip_banned($REMOTE_ADDR))!=false)
        {
            $data['error_box']['ip_banned']=array(
            'display'=>1,
            'text'=>"<{user_login_banned_ip}> ".(!empty($is_ip_banned_reason) ? "<{user_login_banned_ip_reason}> ".$is_ip_banned_reason : "")."<br/><{user_login_banned_ip_admin_email}> ".config_get('SYSTEM','MAILER','admin_email')
            );
            $this->print_login_page($data);
            return false;    
        }
        //end_of_if_ip_is_banned        
        //registration_not_allowed
        if(intval(config_get('SYSTEM','CONFIG','member_allow_register'))<=0)
        {
            $data['error_box']['registration_not_allowed']=array('display'=>1,'text'=>"<{user_login_registration_not_allowed}>");
            $this->print_login_page($data);
            return false;
        }
        //end_of_registration_not_allowed        
        $data['member_force_pwd_gen'] = intval(config_get('SYSTEM','CONFIG','member_force_pwd_gen'))>0 ? array() : array(array());
        
        if(isset($post['action']) && $post['action']=='register')
        {
            $data=array_merge($data,$post); 
            //POST_VALIDATION
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_member_email_authentication')!==true)
            {
                if(!isset($data['login']) || empty($data['login']) || mb_strlen($data['login']) < 4 || mb_strlen($data['login']) > 64 || eregi("^[a-zA-Z]+[a-zA-Z0-9_-]*$",$data['login'])==false)
                {
                    $data['error_box']['login']=array('display'=>1,'text'=>"<{user_registration_error_login}>");
                }
                else if($this->user_auth_model->is_login_exists($data['login']) == true)
                {
                    $data['error_box']['login_exists']=array('display'=>1,'text'=>"<{user_registration_login_exists_error}>");
                }
            }
            else
            {
                $data['login'] = $data['email'];
            }
            //*******End of functionality limitations********
            
            // check email
            if(!isset($data['email']) || empty($data['email']) || mb_strlen($data['email']) < 4 || mb_strlen($data['email']) > 255 || eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $data['email']) == false ) 
            {
                $data['error_box']['email']=array('display'=>1,'text'=>"<{user_registration_error_email}>");
            }
            else
            {
                if( $this->user_auth_model->is_email_exists($data['email'])==true ) 
                { 
                    $data['error_box']['email_exists']=array('display'=>1,'text'=>"<{user_registration_email_exists_error}>");
                }

                $email_domain_status = intval($this->user_auth_model->check_email_domain($data['email']));
                if( $email_domain_status == 2 )
                {
                    $data['error_box']['email_denied_domain']=array('display'=>1,'text'=>"<{admin_msg_er_0028}>");
                }
            }
            // email domain not allowed                        
            // check first name
            if(!isset($data['fname']) || empty($data['fname']))
            {
                $data['error_box']['fname']=array('display'=>1,'text'=>"<{user_registration_error_fname}>");
            }
            // check first name
            // check last name
            if(!isset($data['lname']) or empty($data['lname']))
            {
                $data['error_box']['lname']=array('display'=>1,'text'=>"<{user_registration_error_lname}>");
            }
            // check last name
            $data['password'] =isset($data['password']) ? $data['password'] : "";
            $data['password2'] =isset($data['password2']) ? $data['password2'] : "";
            $data['generate_password_checked']="";
            if((isset($data['generate_password']) && intval($data['generate_password'])>0) || intval(config_get('SYSTEM','CONFIG','member_force_pwd_gen'))>0)
            {
                // generate password
                $data['password'] = $this->user_model->generate_password();
                $data['password2']=$data['password'];
                $data['generate_password_checked']="checked";
                // _generate password
            }
            // password check
            if(strcmp($data['password2'],$data['password'])!=0)
            {
                $data['error_box']['password_not_match']=array('display'=>1,'text'=>"<{user_registration_error_password_not_match}>");                
            }
            if(!(eregi("[a-zA-Z]+",$data['password'])!=false && (eregi("[0-9]+",$data['password'])!=false || eregi("[\!@#$%^&*=+\/~<>?;-]+",$data['password'])!=false)))
            {
                $data['error_box']['password']=array('display'=>1,'text'=>"<{user_registration_error_password}>");
            }
            //Check login and password coincidence
            if($data['password'] === $data['login'])
            {
                $data['error_box']['password_login_coincidence']=array('display'=>1,'text'=>"<{user_registration_error_password_login_coincidence}>");
            }
            $enc_pwd = crypt($data['password']);
            $enc_pwd_bf = ns_encrypt($data['password'],$enc_pwd);
            //_password check
            
            $user_add_fields=set_user_add_fields($_POST,true);
            if(count($user_add_fields['errors'])!=0)
            {
                foreach($user_add_fields['errors'] as $key=>$value)
                {
                    $data['error_box'][$key]=array('display'=>1,'text'=>$value);
                }
            }
            /* _CHECK ADD FIELDS */
            
            // check tos
            if(!isset($data['tos']) || intval($data['tos'])<=0 )
            {
                $data['error_box']['tos']=array('display'=>1,'text'=>"<{user_registration_error_tos}>");
                $data['tos']='';
            }
            else
            {
                $data['tos']='checked';
                $data['submit_disabled']='';
            }
            // _check tos
            // check captcha
            if(!isset($_COOKIE['PHPSESSID']))
            {
                $data['error_box']['cookies_disabled']=array('display'=>1,'text'=>"<{admin_login_form_msg_er_cookies_disabled}>");
            }
            else if(!isset($data['capcha_code']) || check_code($data['capcha_code']) == false)
            {
                $data['error_box']['capcha_code']=array('display'=>1,'text'=>"<{user_registration_error_capcha}>");
            }
            // _check captcha
            //END_OF_POST_VALIDATION
            
            if(!count($data['error_box']))
            {
                // DO REGISTER
                $activation_code = md5(mktime().uniqid("")).(md5(mktime()));
                //$email_domain_status
                $member_need_activation = intval(config_get('SYSTEM','CONFIG','member_need_activation'));
                if( $email_domain_status == 1)
                {
                    $member_need_activation = 0;
                }
                $user_id = intval($this->user_model->register($data['login'],$enc_pwd,$data['email'],$data['fname'],0 /*expire*/,$data['lname'],$member_need_activation,$activation_code,$enc_pwd_bf));
                
                if($user_id>0)
                {
                    // insert addition fields
                    $_POST['id']=$user_id;
                    $errs=set_user_add_fields($_POST);
                    if($errs !== true)
                    {
                        foreach($errs as $key=>$value)
                        {
                            $data['error_box'][$key]=array('display'=>1,'text'=>$value);
                        }
                    }
                    // _insert addition fields

                    //Send email to user and admins
                    $lang_id=$this->user_model->get_lang($user_id);
                    if(!send_system_email_to_user($user_id,'user_registration_completed',array('user_password'=>$data['password'])))
                    {
                        $data['error_box']['email_notsend']=array('display'=>1,'text'=>"<{user_registration_send_registration_error_notsend}>");
                    }
                    $result=send_system_subscription_to_admins('admin_new_member_registered',array(
                    'user_login'=>$data['login']));

                    if(intval(config_get('SYSTEM','CONFIG','member_need_activation')) > 0)
                    {
                        if(!send_system_email_to_user($user_id,'user_account_activation',array('user_activation_link'=>site_url('user/activate/'.$user_id.'/'.$activation_code))))
                        {
                            $data['error_box']['email_activation_notsend']=array('display'=>1,'text'=>"<{user_registration_send_activation_error_notsend}>");
                        }
                    }
                    //_send email
                    
                    //Load successfull page
                    if(!count($data['error_box']))
                    {
                        print_page('user/register_success.html',array('need_activation'=>(intval(config_get('SYSTEM','CONFIG','member_need_activation'))>0 ? array(array()) : array())));
                        //_view('user/register_success',array());
                        return true;
                    }
                }
                else
                {
                    $data['error_box']['db_error']=array('display'=>1,'text'=>"<{user_registration_db_error}>");
                }
                // _DO REGISTER                
            }
        }
        
        if(isset($user_add_fields) && isset($user_add_fields['values']))
        {
            $data['add_fields'] = get_user_add_fields_view(false,false,$user_add_fields['values']);
        }
        else
        {
            $data['add_fields'] = get_user_add_fields_view();
        }
        
        $data['error_box']['login']=isset($data['error_box']['login']) ? $data['error_box']['login'] : '<{user_registration_error_login}>';
        $data['error_box']['email']=isset($data['error_box']['email']) ? $data['error_box']['email'] : '<{user_registration_error_email}>';
        $data['error_box']['fname']=isset($data['error_box']['fname']) ? $data['error_box']['fname'] : '<{user_registration_error_fname}>';
        $data['error_box']['lname']=isset($data['error_box']['lname']) ? $data['error_box']['lname'] : '<{user_registration_error_lname}>';
        $data['error_box']['password']=isset($data['error_box']['password']) ? $data['error_box']['password'] : '<{user_registration_error_password}>';
        $data['error_box']['password_not_match']=isset($data['error_box']['password_not_match']) ? $data['error_box']['password_not_match'] : '<{user_registration_error_password_not_match}>';

        //***********Functionality limitations***********
        if(Functionality_enabled('admin_member_email_authentication')===true)
        {
            $data['login']="undefined";
        }
        //*******End of functionality limitations********
        
        //Load a form of registration
        
        $this->load->model('config_model');
        $fields=$this->config_model->member_page_get('registration');  
        $data['form_fields']=array();
        foreach($fields as $key=>$value)
        {
            $data['form_fields'][$key]=array('if_field_submit_enabled'=> (($key=='tos') ? array(array()) : array()));
            foreach($fields as $id=>$field)
            {
                if($key==$id)
                {
                    
                    foreach($field as $k=>$v)
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                    }
                }
                else
                {
                    $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                    $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                }
            }
        }        
        print_page('user/registration.html',$data);
        return true;
    }

	/**
	 * Load language variables
	 *
	 * @return true
	 */
    function tos()
    {
        $language_id = session_get('lang_id');
        if(intval($language_id)<=0) { $language_id = 1; }

        $this->db->select('descr');
        $this->db->where('language_id',$language_id);
        $this->db->where('object_type',7);
        $this->db->where('object_id',1);
        $this->db->limit(1);
        $this->db->where('name','user_registration_tos');

        $query = $this->db->get(db_prefix.'Language_data');

        if( $query->num_rows() > 0 )
        {
            $tos_text = $query->row();
            if( mb_strlen($tos_text->descr)>1 )
            {
                echo base64_decode($tos_text->descr);
            }
        }

        return true;
    }

	/**
	 * Set language for user
	 *
	 * @return true
	 */
    function set_lang()
    {
        // load models
        $this->load->model('user_model');
        $this->load->model('user_auth_model');
        // _load models

        $this->user_auth_model->is_auth();

        $lang_id = intval($this->input->post('lang_id'));
        $back_url = prepare_text($this->input->post('back_url'));

        //we should depict the view on the new language that was just set
        $_COOKIE['lang_id'] = $lang_id;

        if(Functionality_enabled('admin_member_info_modify', intval($this->user_auth_model->uid))===true)
        {
            $this->db->select('id');
            $this->db->limit(1);
            $query = $this->db->get_where(db_prefix.'Languages',array('id'=>$lang_id));
            if( $query->num_rows() <=0 )
            {
                $this->db->select('id');
                $this->db->limit(1);
                $query = $this->db->get_where(db_prefix.'Languages',array('is_default'=>1));
                if( $query->num_rows()>0 )
                {
                    $lang_id = $query->row();
                    $lang_id = intval($lang_id->id);
                }
                else
                {
                    $lang_id = 1;
                }
            }

            $this->user_model->set_lang($this->user_auth_model->uid,$lang_id);
            $this->user_auth_model->set_cookie_lang_id($lang_id);
        }
        if( isset($back_url) and !empty($back_url) )
            {
				$data['back_url'] = decode_url($back_url);
            }
            else
            {
                $data['back_url'] = site_url('user/login');
            }
		print_page('user/lang_changed.html', $data);
		//_view('user/lang_changed',array('back_url'=>decode_url($back_url)));
        //redirect(base64_decode($back_url),'refresh');
        header("Refresh: 0; url=".decode_url($back_url));

        return true;
    }
    
    function gtest()
    {
        print_page('user/activate_success.html',array());
        //redirect_page("ddddddddddd",'user/login');
    }

	/**
	 * Activate the user
	 *
	 * @param integer $uid
	 * @param string $ac_code
	 * @return boolean
	 */
    function activate($uid=0,$ac_code='')
    {
        $this->load->model('user_model');
        $uid = intval($uid);
        $ac_code = input_text(prepare_text($ac_code));

        if( $uid<=0 or empty($ac_code) or mb_strlen($ac_code)!=64 )
        {

            redirect_page('<{user_activate_wrong_parameters}>','user/login');
            return false;
        }

        $account_status = $this->user_model->get_status($uid);

        if( !is_array($account_status) or sizeof($account_status)<=0 or strcmp($ac_code,$account_status[0]['ac_code'])!=0 )
        {
            redirect_page('<{user_activate_wrong_parameters}>','user/login');
            return false;
        }


        // activate
        if( $this->user_model->activate($uid,$ac_code) )
        {
            print_page('user/activate_success.html',array());          
            //_view('user/activate_success',array());
            return true;
        }
        // _activate


        return false;
    }


    /**
     * calls user_auth_model->logout(), redirects user to LOGOUT_REDIRECT or site_url('user/login')
     *
     * @return bool
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function logout()
    {
        $this->load->model('user_auth_model');
        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_auth_model->is_auth();
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/logout');

        //clear red URL in session
        session_set('red_url', '');

        $redirect_location = config_get('SYSTEM', 'CONFIG', 'LOGOUT_REDIRECT');

        if(! $this->user_auth_model->is_auth() )
        {
            if( empty($redirect_location) || $redirect_location == false )
            {
                header("Refresh: 3; url=".site_url('user/login'));
            }
            else
            {
                header('Location: '.$redirect_location);
            }
            return true;
        }

        $this->user_auth_model->logout();
        print_page('user/logout.html',array());
        //_view('user/logout',array());

        if( empty($redirect_location) || $redirect_location == false )
        {
            header("Refresh: 3; url=".site_url('user/login'));
        }
        else
        {
            header('Location: '.$redirect_location);
        }

        return true;
    }


    /**
    * Check user fields and send password to user email
    *
    * @author Drovorubov
    * @param string
    * 
    * @return mixed
    */
    function remind_password($code='')
    {
        if($this->user_auth_model->is_auth())
        {
            //if allready authorized - go away
            redirect('user/info');
            die;
        }
        
        $error = '';
        $msg = '';
        $uid = '';
        $action = $this->input->post('action','');
        $data = array();
        $data['login'] = prepare_text($this->input->post('login',''));
        $data['email'] = prepare_text($this->input->post('email',''));
        if($code!='')
        {
            $new_password = $this->user_model->generate_password();
            $uid=$this->user_auth_model->compare_remind_code($code,$new_password,'Users');
            if($uid!==false)
            {
                $tmp = $this->user_model->get_profile_by_uid($uid);
                $user_info = $tmp[0];
                $result=send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$new_password));
                if(!$result)
                {
                    $msg = '';
                    $error = "<{user_remind_password_error_email_not_sent}>";
                }
                else
                {
                    $this->login('remind_password',"<{user_remind_password_msg_password_is_sent}>");
                    return;
                }
            }
            else
            {
                $error = "<{user_remind_password_msg_er_remind_code_error}>";
                $msg = '';
            }
        }
        if( $action == 'remind' )
        {
            $this->load->model('user_model');
            $data['capcha_code'] = prepare_text(trim($this->input->post('capcha_code','')));
            $error = $this->_check_remind_password_fields($data);
            if( $error === '' )
            {
                $uid = $this->user_model->get_id_by_login($data['login']);
                if( !$uid )
                {
                    $error = "<{user_error_login_incorrect}>";
                }
            }
            if( $error === '' )
            {
                $tmp = $this->user_model->get_profile_by_uid($uid);
                $user_info = $tmp[0];
                if( empty($data['email']) || $user_info['email'] != $data['email'] )
                {
                    $error = "<{user_error_email_incorrect}>";
                }
                if( empty($user_info['email']) )
                {
                    $error = "<{user_error_email_not_exist}>";
                }
            }
            if( $error === '' && $uid > 0 && !empty($user_info['email']) )
            {
                $user_remind_link=config_get('system','config','base_url')."user/remind_password/".$this->user_auth_model->get_remind_code($uid,'Users');
                $result=send_system_email_to_user($uid,'user_remind_password',array('user_remind_password_link'=>$user_remind_link));
                if(!$result)
                {
                    $msg = '';
                    $error = "<{user_remind_password_error_email_not_sent}>";
                }
                else
                {
                    $error = '';
                    $msg = "<{user_remind_password_msg_link_is_sent}>";
                }                
            }
        }
        $data['error'] = $error;
        $data['msg'] = $msg;
        $data['login'] = output($data['login']);
        $data['email'] = output($data['email']);
        //_view('user/remind_password',$data);
        
        //parser_patch
        $data['message_box']=array();
        if(isset($data['msg']) && !empty($data['msg']))
        {
            $data['message_box'][]=array('display'=>1,'text'=>$data['msg']);            
        }
        $data['error_box']=array();
        if(isset($data['error']) && !empty($data['error']))
        {
            $data['error_box'][]=array('display'=>1,'text'=>$data['error']);            
        }
        
        $capcha_error_display = "display:none;";
        if( isset($capcha_error) and intval($capcha_error) == 1 )
        {
            $capcha_error_display = "";
        }
        $data['capcha_error_display']=(isset($data['capcha_error']) && intval($data['capcha_error'])==1) ? "" : "display:none;";
        
        print_page('user/remind_password.html',$data);
        //end_of_parser_patch
        
        return true;
    }


    /**
    * Check entry params for remind password
    *
    * @author Drovorubov
    * @param array $data
    * @return string
    */
    function _check_remind_password_fields($data)
    {
        $rv = '';
        if( $data['login'] == '')
        {
            $rv = '<{user_remind_password_error_login_empty}>';
            return $rv;
        }
        if( mb_strlen($data['login']) > 64 )
        {
            $rv = '<{user_remind_password_error_login_toolong}>';
            return $rv;
        }
        if( $data['email'] == '')
        {
            $rv = '<{user_remind_password_error_email_empty}>';
            return $rv;
        }
        if( mb_strlen($data['email']) > 255 )
        {
            $rv = '<{user_remind_password_error_email_toolong}>';
            return $rv;
        }
        if( $data['capcha_code'] == '')
        {
            $rv = '<{user_remind_password_error_capcha_empty}>';
            return $rv;
        }
        // check captcha
        if( check_code($data['capcha_code']) == false )
        {
            $rv = '<{user_remind_password_error_capcha}>';
        }
        return $rv;
    }
	
	function main()
	{
		$this->load->model('user_auth_model');
	    $is_loginned = $this->user_auth_model->is_auth();
		
		$this->load->model('product_model');
        $this->load->model('payment_model');
        $this->load->model('market_model');
		
		$subscribed = array();
		$nodes_data = array();
		if ($is_loginned)
        {
			$subscribed=explode(',', $this->market_model->subscribed_products($this->user_auth_model->uid));
            $nodes_data = $this->product_model->special_offers_product_list($is_loginned, 'name', 'ASC', $this->user_auth_model->uid);		
		}
        else $nodes_data = $this->product_model->special_offers_product_list($is_loginned/*, 'name', 'ASC', $this->user_auth_model->uid*/);		        
//        $nodes_data['currency']=$data['currency'];
		$group_id=(int)input_post('group_id', 0);
		$per_page=(int)input_post('per_page', 0);
		$page=(int)input_post('page', 0);
		$CU=encode_url(base_url()."market/sale/$page/$per_page/$group_id");
		$nodes_data['cur_url']=output($CU);
        $nodes_data['if_products'] = array();
        $nodes_data['else_products'] = array(array());
        $posters_path_original=config_get("product_posters", "path_original");
        $posters_path_previews=config_get("product_posters", "path_previews");
        $absolute_path=config_get("SYSTEM","CONFIG","ABSOLUTE_PATH");
		
		if(is_array($nodes_data['items']) && count($nodes_data['items']))
        {
            $nodes_data['if_products'] = array(array());
            $nodes_data['else_products'] = array();
            
            foreach($nodes_data['items'] as $key=>$p)
            {
                $free=!(bool)($p['day'] + $p['month'] + $p['month3'] + $p['month6'] + $p['year'] + $p['unlimit']);
                $nodes_data['items'][$key]['if_free']=$free ? array(array()) : array();
                $nodes_data['items'][$key]['if_not_free']=$free ? array() : array(array());
                $nodes_data['items'][$key]['if_discount']=((float)$p['discount']) ? array(array()) : array();
                $nodes_data['items'][$key]['if_image']=array();
                
                $nodes_data['items'][$key]['product_name']=soft_wrap(output($p['product_name']), 30);
                $nodes_data['items'][$key]['product_descr']=soft_wrap(output($p['product_descr']), 20);
                $nodes_data['items'][$key]['group_name']=output(word_wrap($p['group_name'],30,2));
                
                $nodes_data['items'][$key]['if_is_recouring']=($p['is_recouring']==2) ? array(array()) : array();
                $nodes_data['items'][$key]['else_is_recouring']=($p['is_recouring']==2) ? array() : array(array());
                $nodes_data['items'][$key]['if_trial_period']=(!$free && isset($p['trial_period_value']) && $p['trial_period_value']) ? array(array()) : array();
                $nodes_data['items'][$key]['submit_disabled']=(in_array($p['product_id'], $subscribed) ||isset($p['available'])&&(!$p['available']) ? "disabled" : "");
                
                $p['new_year5']=$p['new_unlimit'];
                $periods=array('day','month','month3','month6','year','year5');
                $prices=array();
                foreach($periods as $period)
                {
                    if(floatval($p[$period]))
                    {
                        $prices[]=array(
                        'period'=>$period,
                        'price'=>print_price($nodes_data['currency'], $p[$period], $p['new_'.$period], (float)$p['discount'])
                        );
                    }
                }
                $nodes_data['items'][$key]['prices']=$prices;             
                
                if($p['image'])
                {
                    $orig=file_exists($absolute_path.$posters_path_original.$p['image']) ? base_url().$posters_path_original.$p['image'] : false;
                    $prew=file_exists($absolute_path.$posters_path_previews.$p['image']) ? base_url().$posters_path_previews.$p['image'] : $orig;
                    
                    $nodes_data['items'][$key]['if_image']=array(array());
                    $nodes_data['items'][$key]['image']=$orig ? $orig : base_url()."img/no_image.jpg";
                    $nodes_data['items'][$key]['preview_image']=$orig ? $prew : base_url()."img/no_image.jpg";
                }
            }
        }
        
		///////////////////////////// NEWS ////////////////////////////////////
		
		$this->load->model('news_model');
        $is_loginned = $this->user_auth_model->is_auth();
        $news = $this->news_model->news_list(1,3,'by_date','desc',1,$is_loginned, true); 
        foreach($news['items'] as $key=>$val)
        {
            // Convert field Name
            //$val['name'] = word_wrap($val['name'],50,2);
            $news['items'][$key]['name'] = output($val['name']);
            // Convert field Descr
            //$val['descr'] = output_html($val['descr']);
            //$val['descr'] = word_wrap($val['descr'],50,0,' ');
            $news['items'][$key]['descr'] = output($val['descr']);
        }

        $nodes_data['currency'] = config_get("system", "config", "currency_code");
		if( isset($news['items']) && is_array($news['items']) && sizeof($news['items']) > 0 )
		{
			$i=0;
			for ($n_i=0;$n_i<count($news['items']);$n_i++)
			{
				$news['items'][$n_i]['date'] = isset($news['items'][$n_i]['date']) ? nsdate($news['items'][$n_i]['date'],false) :"";
				if ($i==0)
				{
					$i=1;
					$news['items'][$n_i]['tr_style_list'] = ' class="dark latest_news_0" ';
				}
				else
				{
					$i=0;
					$news['items'][$n_i]['tr_style_list'] = ' class="light latest_news_0" ';
				}
				$news['items'][$n_i]['id'] = md5($news['items'][$n_i]['id']);
				$news['items'][$n_i]['name'] = soft_wrap($news['items'][$n_i]['name'],3);
				$news['items'][$n_i]['descr'] = soft_wrap($news['items'][$n_i]['descr'],3);
			}
			$nodes_data['if_news'] = array(array());
		}
		else
		{
			$nodes_data['if_news'] = array();
		}
        $nodes_data['news'] = $news['items'];
		
		//////////////////////////////////// admin message ////////////////////////////////////////////
		
		if ($is_loginned)
			$nodes_data['admin_msg'] = /*soft_wrap(*/config_get('SYSTEM','MAIN_PAGE', 'admin_msg')/*, 10)*/;
		else
			$nodes_data['admin_msg'] = /*soft_wrap(*/config_get('SYSTEM','MAIN_PAGE', 'unreg_admin_msg')/*, 10)*/;
				
        //$data['products']=print_page("user/market_sale_nodes.html",$nodes_data, TRUE);
		print_page("user/main.html",$nodes_data);
		
		
		//$data = array();
		
		
		//print_page('user/main.html',$data);
	}


    /**
    * Shows information as active user's subscriptions and last 2 news
    *
    * @author Drovorubov
    * modified Makarenko Sergey @ 14.10.2008 10:43:03
    * @return mixed
    *
    */
    function info()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('member_registered_redirect_info')!==true)
        {
            redirect("user/profile");
            return;
        }
        //*******End of functionality limitations********
        
        $products = array();
        check_user_auth();
        //Get user ID
        $this->load->model('user_auth_model');
        $uid = $this->user_auth_model->uid;

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/info');


        if( $uid > 0 )
        {
            //Get product id, subscription id
            $this->load->model('product_model');
            $this->load->model('payment_model');
            $this->load->model('market_model');
            $protection = $this->product_model->last_active_product($uid);
            if( $protection && count($protection > 0) )
            {
                foreach( $protection as $key=>$val )
                {
					
                    $tmp = array();
                    $tmp = $this->product_model->get($val['product_id']);
                    $products[$key] = $tmp[0];
                    $products[$key]['name'] = word_wrap($products[$key]['name'],50,0,' ');
                    $products[$key]['name'] = output($products[$key]['name']);
                    $products[$key]['dirs'] = $this->market_model->_get_product_links($val['product_id']);
                    unset($tmp);
                    $tmp = $this->payment_model->get_subscr_info($val['subscr_id']);
                    $products[$key]['subscr'] = $tmp[0];
                }
            }
        }

        //Get last 2 news items
        $this->load->model('news_model');
        $is_loginned = $this->user_auth_model->is_auth();
        $news = $this->news_model->news_list(1,2,'by_date','desc',1,$is_loginned);
        foreach($news['items'] as $key=>$val)
        {
            // Convert field Name
            //$val['name'] = word_wrap($val['name'],50,2);
            $news['items'][$key]['name'] = output($val['name']);
            // Convert field Descr
            //$val['descr'] = output_html($val['descr']);
            //$val['descr'] = word_wrap($val['descr'],50,0,' ');
            $news['items'][$key]['descr'] = output($val['descr']);
        }

        $data['currency'] = config_get("system", "config", "currency_code");
		if( isset($news['items']) && is_array($news['items']) && sizeof($news['items']) > 0 )
		{
			$i=0;
			for ($n_i=0;$n_i<count($news['items']);$n_i++)
			{
				$news['items'][$n_i]['date'] = isset($news['items'][$n_i]['date']) ? nsdate($news['items'][$n_i]['date'],false) :"";
				if ($i==0)
				{
					$i=1;
					$news['items'][$n_i]['tr_style_list'] = ' class="dark" ';
				}
				else
				{
					$i=0;
					$news['items'][$n_i]['tr_style_list'] = ' class="light" ';
				}
			}
			$data['if_news'] = array(array());
		}
		else
		{
			$data['if_news'] = array();
		}
        $data['news'] = $news['items'];
		
		if( isset($products) && is_array($products) && sizeof($products) > 0 )
		{
			for ($p_i=0;$p_i<count($products);$p_i++)
			{
				if(floatval($products[$p_i]['subscr']['regular_price']))
				{
					$products[$p_i]['if_product_free'] = array(array());
					$products[$p_i]['else_product_free'] = array();
					$products[$p_i]['output_regular_price'] = output($products[$p_i]['subscr']['regular_price']);
					$products[$p_i]['type1'] = ($products[$p_i]['subscr']['type']==1?"—&nbsp;<{user_active_products_node_type_one_time}>":"—&nbsp;<{user_active_products_node_type_recc}>"); 
				}
				else
				{
					$products[$p_i]['else_product_free'] = array(array());
					$products[$p_i]['if_product_free'] = array();
				}
				$products[$p_i]['subscr_cdate'] = isset($products[$p_i]['subscr']['cdate']) ? nsdate($products[$p_i]['subscr']['cdate'],false) :"";
				$products[$p_i]['subscr_expire_date'] = isset($products[$p_i]['subscr']['expire_date']) ? nsdate($products[$p_i]['subscr']['expire_date'],false) :"";
				$products[$p_i]['subscr_regular_period_value'] = output($products[$p_i]['subscr']['regular_period_value']);
				$products[$p_i]['subscr_regular_period_type'] = '<{user_info_product_period_'.output($products[$p_i]['subscr']['regular_period_type']).'}>';
                $data['p_dirs']=array();
				if( isset($products[$p_i]['dirs']) && is_array($products[$p_i]['dirs']) && sizeof($products[$p_i]['dirs']) > 0 )
				{
					$data['p_dirs']=array();
                    foreach($products[$p_i]['dirs'] as $key => $value)
                    {
                        $data['p_dirs'][$key]=array(
                        'http_path' => output($value['http_path']),
                        'name' => output($value['name'])
                        );
                    }
				}
				if($products[$p_i]['image'])
				{
					$products[$p_i]['if_image'] = array(array());
					$products[$p_i]['image_url'] = base_url().config_get("product_posters", "path_previews").$products[$p_i]['image'];
				}
				else
				{
					$products[$p_i]['if_image'] = array();
				}
				$products[$p_i]['descr'] = soft_wrap(output($products[$p_i]['descr']), 40);
			}
			$data['if_product_list'] = array(array());
			$data['else_product_list'] = array();
		}
		else
		{
			$data['if_product_list'] = array();
			$data['else_product_list'] = array(array());
		}
		
        $data['product_list'] = $products;        
        print_page('user/info.html',$data);
		//_view('user/info',$data);
        return true;
    }
    
    function profile_additional($page="",$account_id=0,$red_url=false)
    {        
        //$red_url=check_url(decode_url($red_url)) ? $red_url : false;
        //fb($red_url,"RED_URL");
        //profile_domain
        $error_box=array();
        $message_box=array();
        check_user_auth();
        //Get user ID
        $this->load->model('user_auth_model');

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/profile_additional/'.$page.'/'.$account_id);
        $uid = $this->user_auth_model->uid;
        if( $uid > 0 )
        {
            $this->load->model('user_model');
            $this->load->model('config_model');
            if(empty($page) || !in_array($page,$this->user_model->profile_types) || Functionality_enabled('member_registered_menu_'.$page)!==true)
            {
                redirect('user');
                exit;
            }
            $error_text="";
            //$db_add_fields_list = $this->user_model->get_add_fields('*');
            $action = $this->input->post('action','');
            //***********Functionality limitations***********
            $user_info = $this->user_model->get_profile_by_uid($uid);
            $data = $user_info[0];            
            $data['account_id']=$account_id;
            $data['forceuse']=1;
            $data['asdefault']=1;
            $data['customerlangpref']='en';
            $account_info = $this->user_model->Profile_additional_get($page,$uid,$account_id);
            if($account_info)
            {
                $account_info['account_id']=$account_info['id'];
                unset($account_info['id']);
                $data=array_merge($data,$account_info);
            }
            $data['fields'] = $this->config_model->member_page_get($page);
            $data['message_box']=array();
            $data['error_box']=array();            
            $post=prepare_post();
            
            if(isset($post['button_save']) || isset($post['button_autofill']))
            {
                if(($error_text=Functionality_enabled('admin_member_info_modify', $uid))===true)
                {
                    //*******End of functionality limitations********
                    $post['forceuse']=isset($post['forceuse']) ? $post['forceuse'] : 0;
                    $post['asdefault']=isset($post['asdefault']) ? $post['asdefault'] : 0;
                    $data=array_merge($data,$post);
                    
                    if(isset($post['button_autofill']))
                    {
                        $data = $this->user_model->profile_additional_autofill($page,$data);
                    }
                    $data=$this->user_model->user_fields_check($data,$page);
                    if(isset($post['button_save']))
                    {
                        if(!is_msg_displayed($data['error_box']))
                        {
                            $data['save_to_session']=$red_url ? true : false;
                            if($this->user_model->profile_additional_set($page,$data,$account_id))
                            {
                                if($red_url)
                                {
                                    redirect(decode_url($red_url));
                                }
                                $data['message_box']['update_successful']=array('display'=>1,'text'=>"<{user_".$page."_msg_successful}>");
                            }
                            else
                            {
                                $data['error_box']['not_saved']=array('display'=>1,'text'=>"<{user_".$page."_err_not_saved}>");
                            }
                        }
                    }
                }
                else if(!empty($error_text))
                {
                    $data['error_box']['modify_disabled']=array('display'=>1,'text'=>$error_text);
                }
            }
            //Get user profile from DB
            //fb($data['fields'],"profile_fields");
            //Prepare user data fields for display
            $languages=$data['fields']['customerlangpref']['languages'];
            $data['languages']=array();
            foreach($languages as $k=>$v){
            $data['languages'][]=array(
            'language_code'=>$k,
            'language_name'=>$v,
            'language_selected'=>(isset($data['customerlangpref']) && $data['customerlangpref']==$k ? "selected" : "")
            );}            
            $data['countries']=array();
            foreach(get_countries() as $k=>$v){
            $data['countries'][]=array(
            'country_code'=>$k,
            'country_name'=>$v,
            'country_selected'=>(isset($data['country']) && $data['country']==$k ? "selected" : "")
            );}
            $data['states']=array();
            foreach(get_states() as $k=>$v){
            $data['states'][]=array(
            'state_code'=>$k,
            'state_name'=>$v,
            'state_selected'=>(isset($data['state']) && $data['state']==$k ? "selected" : "")
            );}
            $data['telnocc']=isset($data['telnocc']) ? $data['telnocc'] : "";
            $data['alttelnocc']=isset($data['alttelnocc']) ? $data['alttelnocc'] : "";
            $data['faxnocc']=isset($data['faxnocc']) ? $data['faxnocc'] : "";
            $data['forceuse_checked']=isset($data['forceuse']) && intval($data['forceuse']) ? "checked" : ""; 
            //Show page
            $data['form_fields']=array();
            foreach($data['fields'] as $key=>$value)
            {
                $data[$key]=isset($data[$key]) ? output($data[$key]) : "";            
                foreach($data['fields'] as $id=>$field)
                {
                    if($key==$id)
                    {
                        $maxlength=255;
                        if(isset($field['length']) && is_array($field['length']))
                        {
                            $maxlength=(isset($field['length']['limit']) && $field['length']['limit']) ? $field['length']['limit'] : $maxlength;
                            $maxlength=(isset($field['length']['max']) && $field['length']['max']) ? $field['length']['max'] : $maxlength;
                        }
                        $data['form_fields'][$key]['field_maxlength']=$maxlength;
                        foreach($field as $k=>$v)
                        {
                            $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                            $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                            
                        }
                    }
                    else
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                    }
                }
            }
            $data['if_is_autofill']=($this->user_model->profile_additional_autofill($page)) ? array(array()) : array();            
            $data['page']=$page;
            $data['red_url']=($red_url) ? '/'.$red_url : ''; 
            $data['if_field_asdefault']=$red_url ? array(array()) : array();
            $data['asdefault_checked']=isset($data['asdefault']) && $data['asdefault'] ? "checked" : "";
            $data['save_button_value']=$red_url ? '<{user_'.$page.'_update_button_apply}>': '<{user_'.$page.'_update_button_save}>';
            //fb($data['form_fields'],"form_fields");
            print_page('user/'.$page.'.html',$data);
        }
        return false;
    }
    /**
    * Changes user's profile data
    *
    * @author Drovorubov
    * @return false
    */
    function profile()
    {
        $error_box=array();
        $message_box=array();
        
        check_user_auth();
        //Get user ID
        $this->load->model('user_auth_model');

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/profile');

        $uid = $this->user_auth_model->uid;
        if( $uid > 0 )
        {
            $error_text="";
            $this->load->model('user_model');
            //$db_add_fields_list = $this->user_model->get_add_fields('*');
            $action = $this->input->post('action','');
            //***********Functionality limitations***********
            $user_info = $this->user_model->get_profile_by_uid($uid);
            $data = $user_info[0];
            $data['message_box']=array();
            $data['error_box']=array();
            $data['lname'] = $data['last_name'];
            $data['fname'] = $data['name'];
            $data['email_retype']=$data['email'];
            $data['uid']=$uid;
            $_POST['id']=$uid;
            if( $action == 'save')
            {
                if(($error_text=Functionality_enabled('admin_member_info_modify', $uid))===true)
                {
                    
                    //*******End of functionality limitations********
                    //$user_info = $this->user_model->get_profile_by_uid($uid);
                    $post=prepare_post();
                    //fb($data,"DATA0");
                    $data=array_merge($data,$post);
                    //fb($data,"DATA");
                    $data['login'] = $user_info[0]['login'];
                    
                    //$data['email'] = prepare_text($this->input->post('email'));
                    //$data['fname'] = prepare_text($this->input->post('fname'));
                    //$data['lname'] = prepare_text($this->input->post('lname'));
                    
                    // Main Fields validation
                    $data=$this->user_model->profile_check($data);
                    
                    if(!is_msg_displayed($data['error_box']))
                    {
                        
                        $info=$data;
                        $info['name']=$info['fname'];
                        $info['last_name']=$info['lname'];
                        $this->user_model->set_profile($uid,$info);
                        if(!send_system_email_to_user($uid,'user_profile_change'))
                        {
                            $data['error_box']['email_not_sent']=array('display'=>1,'text'=>"<{user_profile_update_error_email_not_sent}>");
                        }
                        else
                        {
                            $data['message_box']['update_successful']=array('display'=>1,'text'=>"<{user_profile_update_successful}>");
                        }
                    }
                }
                else if(!empty($error_text))
                {
                    $data['error_box']['modify_disabled']=array('display'=>1,'text'=>$error_text);
                }
            }

            //Get user profile from DB
            
            $this->load->model("config_model");
            $data['fields'] = $this->config_model->member_page_get('profile');             
            

            //Prepare user data fields for display
            $data['email'] = $data['email'];
            $data['lname'] = $data['lname'];
            $data['fname'] = $data['fname'];
            
            if(isset($data['user_add_fields']) && isset($data['user_add_fields']['values']))
            {
                $data['add_fields'] = get_user_add_fields_view($uid,false,$data['user_add_fields']['values']);
            }
            else
            {
                $data['add_fields'] = get_user_add_fields_view($uid);
            }
            //Show page
            $data['form_fields']=array();
            foreach($data['fields'] as $key=>$value)
            {
                $data[$key]=isset($data[$key]) ? output($data[$key]) : "";            
                //$data['form_fields'][$key]=array('if_field_submit_enabled'=> (($key=='tos') ? array(array()) : array()));
                foreach($data['fields'] as $id=>$field)
                {
                    if($key==$id)
                    {
                        $maxlength=255;
                        if(isset($field['length']) && is_array($field['length']))
                        {
                            $maxlength=(isset($field['length']['limit']) && $field['length']['limit']) ? $field['length']['limit'] : $maxlength;
                            $maxlength=(isset($field['length']['max']) && $field['length']['max']) ? $field['length']['max'] : $maxlength;
                        }
                        $data['form_fields'][$key]['field_maxlength']=$maxlength;
                        foreach($field as $k=>$v)
                        {
                            $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                            $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                        }
                    }
                    else
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                    }
                }
            }            
/* for feature
    $data['domain_registration_info']=array(); 
            
	$CI = &get_instance();
    $CI->load->model("user_model");
    $data['profile_additional']=array();
    foreach($CI->user_model->profile_additional_list() as $k=>$v)
    {
        $pfs=count($v) ? $v : array(array('account_id'=>0,'account_type_string'=>$k,'account_name'=>('<{user_menu_profile_additional_'.$k.'_add}>')));
        $data['profile_additional']=array_merge($data['profile_additional'],$pfs);
    }    
*/
            print_page('user/profile.html',$data);
            //_view('user/profile',$data);
        }
        return false;
    }
    
    
    function profile1()
    {
        $error_box=array();
        $message_box=array();
        
        check_user_auth();
        //Get user ID
        $this->load->model('user_auth_model');

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/profile');

        $uid = $this->user_auth_model->uid;
        if( $uid > 0 )
        {
            $error_text="";
            $this->load->model('user_model');
            //$db_add_fields_list = $this->user_model->get_add_fields('*');
            $action = $this->input->post('action','');
            //***********Functionality limitations***********
            if( $action == 'save' && ($error_text=Functionality_enabled('admin_member_info_modify', $uid))===true)
            {
                //*******End of functionality limitations********
                $user_info = $this->user_model->get_profile_by_uid($uid);
                $data['login'] = $user_info[0]['login'];
                $data['email'] = prepare_text($this->input->post('email'));
                $data['name'] = prepare_text($this->input->post('first_name'));
                $data['last_name'] = prepare_text($this->input->post('last_name'));

                // Main Fields validation
                $error = $this->_check_user_fields($data,$uid);
                if(count($error))
                {
                    foreach($error as $key=>$value)
                    {
                        $error_box[$key]=array('display'=>1,'text'=>$value);    
                    }
                }

                //Update user profile                
                $_POST['id']=$uid;
                //Try update additional fields
                $user_add_fields=set_user_add_fields($_POST,true,(count($error_box)>0?true:false));
                if(count($user_add_fields['errors'])!=0)
                {
                    foreach($user_add_fields['errors'] as $key=>$value)
                    {
                        $error_box[$key]=array('display'=>1,'text'=>$value);    
                    }
                }
                else
                {   
                    if(count($error_box)==0)
                    {
                        $this->user_model->set_profile($uid,$data);
                    }
                }
                //Send system email about profile changing
                if(!send_system_email_to_user($uid,'user_profile_change'))
                {
                    $error_box['email_not_sent']=array('display'=>1,'text'=>"<{user_profile_update_error_email_not_sent}>");
                }
                //Set a page message about successful updating
                if(count($error_box)==0)
                {
                    $message_box['update_successful']=array('display'=>1,'text'=>"<{user_profile_update_successful}>");
                }
            }
            else if(!empty($error_text))
            {
                $error_box['modify_disabled']=array('display'=>1,'text'=>$error_text);
            }

            //Get user profile from DB
            if(!isset($data))
            {
                $data = array();
                $user_info = $this->user_model->get_profile_by_uid($uid);
                $data = $user_info[0];
            }

            //Prepare user data fields for display
            $data['email'] = output($data['email']);
            $data['name'] = output($data['name']);
            $data['last_name'] = output($data['last_name']);

            if(isset($user_add_fields) && isset($user_add_fields['values']))
            {
                $data['add_fields'] = get_user_add_fields_view($uid,false,$user_add_fields['values']);
            }
            else
            {
                $data['add_fields'] = get_user_add_fields_view($uid);
            }
            //Show page
            $data['error_box']=$error_box;
            $data['message_box']=$message_box;
            $data['email_authentication']=(Functionality_enabled('admin_member_email_authentication')!==true) ? array(array()) : array();
            
            print_page('user/profile.html',$data);
            //_view('user/profile',$data);
        }
        return false;
    }


    /**
    * Changes the registered user's password
    *
    * @author Drovorubov
    * @return true
    */
    function password()
    {
        check_user_auth();
        $data=array();
        $data['generate']=0;
        $data['error_box']=array();
        $data['message_box']=array(); 
        
        $action = $this->input->post('action','');
        $uid = $this->user_auth_model->uid;
        if( $action == 'save')
        {
            if(($error=Functionality_enabled('admin_member_info_modify', $uid))===true)
            {
                $this->load->model('user_model');

                //log this to the "User_logs" table in DB
                $this->load->model('user_log_model');
                $this->user_log_model->set($uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/password');
                
                if(intval($uid)>0)
                {
                    $post=prepare_post();
                    $data=array_merge($data,$post);
                    $data['uid']=$uid;
                    $data=$this->user_model->password_check($data);            
                    if(!is_msg_displayed($data['error_box']))
                    {
                        if( !$this->user_model->new_password($uid,$data['password'],$data['old_password'],$data['old_password_type']) )
                        {
                            $data['error_box']['not_changed']=array('display'=>1,'text'=>"<{user_change_password_error_pwd_not_changed}>");
                        }
                        else
                        {
                            //Authorize user
                            $user_info = $this->user_model->get_profile_by_uid($uid);
                            $user_info = $user_info[0];
                            $remote_addr = getenv('REMOTE_ADDR');
                            $this->load->model('user_auth_model');
                            $this->user_auth_model->auth($user_info['login'], $user_info['pass'], $uid, false, $remote_addr);
                            $result=send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['password']));
                            if(!send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['password'])))
                            {
                                $data['error_box']['not_sent']=array('display'=>1,'text'=>"<{user_change_password_error_email_not_sent}>");                            
                            }
                            else
                            {
                                $data['message_box']['changed']=array('display'=>1,'text'=>"<{user_change_password_msg_pwd_changed}>");
                            }                            
                        }
                    }
                }  
            }
            else
            {
                $data['error_box']['functionality_disabled']=array('display'=>1,'text'=>$error);
            }
        }
        
        $this->load->model("config_model");
        $data['fields'] = $this->config_model->member_page_get('password'); 
        fb($data['fields'],"password_fields");
        
        //Show page
        $data['form_fields']=array();
        foreach($data['fields'] as $key=>$value)
        {
            foreach($data['fields'] as $id=>$field)
            {
                if($key==$id)
                {
                    
                    foreach($field as $k=>$v)
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                    }
                }
                else
                {
                    $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                    $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                }
            }
        }
        fb($data['form_fields'],"form_fields");
        print_page('user/change_password.html',$data);
        return true;
    }
    
    function password1()
    {
        check_user_auth();
        $data=array();
        $data['error_box']=array();
        $data['message_box']=array();
        
        $action = $this->input->post('action','');
        $uid = $this->user_auth_model->uid;
        if( $action == 'save' && ($error=Functionality_enabled('admin_member_info_modify', $uid))===true)
        {
            $this->load->model('user_model');

            //log this to the "User_logs" table in DB
            $this->load->model('user_log_model');
            $this->user_log_model->set($uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/user/password');
            
            if(intval($uid)>0)
            {
                $post=prepare_post();
                $data=array_merge($data,$post);
                $data['uid']=$uid;
                $data=$this->user_model->password_check($data);            
                if(!is_msg_displayed($data['error_box']))
                {
                    if( !$this->user_model->new_password($uid,$data['password'],$data['old_password'],$data['old_password_type']) )
                    {
                        $data['error_box']['not_changed']=array('display'=>1,'text'=>"<{user_change_password_error_pwd_not_changed}>");
                    }
                    else
                    {
                        //Authorize user
                        $user_info = $this->user_model->get_profile_by_uid($uid);
                        $user_info = $user_info[0];
                        $remote_addr = getenv('REMOTE_ADDR');
                        $this->load->model('user_auth_model');
                        $this->user_auth_model->auth($user_info['login'], $user_info['pass'], $uid, false, $remote_addr);
                        $result=send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['new_pwd']));
                        if(!send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['new_pwd'])))
                        {
                            $data['error_box']['not_sent']=array('display'=>1,'text'=>"<{user_change_password_error_email_not_sent}>");                            
                        }
                        else
                        {
                            $data['message_box']['changed']=array('display'=>1,'text'=>"<{user_change_password_msg_pwd_changed}>");
                        }
                        
                    }
                }
            }
            
            
            //$data['old_pwd'] = prepare_text($this->input->post('old_pwd',''));
            //$data['new_pwd'] = prepare_text($this->input->post('new_pwd',''));
            //$data['retype_pwd'] = prepare_text($this->input->post('retype_pwd',''));
            //$data['generate_random'] = prepare_text($this->input->post('generate_random',''));
            //Check fields for changing password
            //$error = $this->_check_change_password_fields($data);
            
            
            if( $error === '' )
            {
                $uid = $this->user_auth_model->uid;
                if( $uid > 0 )
                {
                    //Check user's old password
                    if( !$this->user_model->check_old_password($uid,$data['old_pwd']) )
                    { 
                        $error = '<{user_change_password_error_old_pwd_incorrect}>';
                    }
                    if( $error === '' )
                    {
                        if( $data['generate_random'] == 1 )
                        {
                            $data['new_pwd'] = $this->user_model->generate_password();
                        }
                        //Set a new password
                        if( !$this->user_model->new_password($uid,$data['new_pwd'],$data['old_pwd']) )
                        {
                            $msg = '';
                            $error = '<{user_change_password_error_pwd_not_changed}>';
                        }
                        else
                        {
                            //Authorize user
                            $user_info = $this->user_model->get_profile_by_uid($uid);
                            $user_info = $user_info[0];
                            $remote_addr = getenv('REMOTE_ADDR');
                            $this->load->model('user_auth_model');
                            $this->user_auth_model->auth($user_info['login'], $user_info['pass'], $uid, false, $remote_addr);


                            $result=send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['new_pwd']));
                            if(!$result)
                            {
                                $error = '<{user_change_password_error_email_not_sent}>';
                            }
                            $msg = '<{user_change_password_msg_pwd_changed}>';
                        }
                    }
                }
            }
        }
        $data = array();
        //$data['error'] = $error;
        //$data['msg'] = $msg;
        
        $data['error_box']=empty($error) ? array() : array('password'=>array('display'=>1,'text'=>$error));
        $data['message_box']=empty($msg) ? array() : array('password'=>array('display'=>1,'text'=>$msg));
        
        
        
        
        
        
        $this->load->model("config_model");
        $data['fields'] = $this->config_model->member_page_get('password'); 
        fb($data['fields'],"password_fields");
        

        //Prepare user data fields for display
        //$data['email'] = output($data['email']);
        //$data['lname'] = output($data['lname']);
        //$data['fname'] = output($data['fname']);
        
        //Show page
        $data['form_fields']=array();
        foreach($data['fields'] as $key=>$value)
        {
            //$data['form_fields'][$key]=array('if_field_submit_enabled'=> (($key=='tos') ? array(array()) : array()));
            foreach($data['fields'] as $id=>$field)
            {
                if($key==$id)
                {
                    
                    foreach($field as $k=>$v)
                    {
                        $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=intval($v) ? array(array()) : array();
                        $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=intval($v) ? array() : array(array());
                    }
                }
                else
                {
                    $data['form_fields'][$key]['if_field_'.$id.'_'.$k]=array();
                    $data['form_fields'][$key]['else_field_'.$id.'_'.$k]=array();
                }
            }
        }
        fb($data['form_fields'],"form_fields");
        print_page('user/change_password.html',$data);
        //_view('user/change_password',$data);
        return true;
    }


    /**
    * This function shows the content of custom page
    *
    * @param string $sid
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function page($sid = false)
    {
        $page_content = $this->user_model->Get_page_content($sid);
        $restricted = false;
        // if something went wrong - redirect out from this page
        if ( empty($sid) || $page_content===false || !is_array($page_content) || !$page_content['published'])
        {
            redirect('user/login/');
            die;
        }
        if ($this->user_auth_model->is_auth()==false && $page_content['members_only']==true)
        {
            // restricted
            redirect('user/login/1/'.encode_url(site_url('user/page/'.$sid)));
            die;
        }
        
        $page_content['header']=print_header(array('header_title'=>$page_content['page_title'], 'keywords'=>$page_content['keywords']),true);
        $page_content['page_title'] = $page_content['page_title'];
        $page_content['page_content'] = $page_content['page_content'];
        print_page('user/custom_page.html',$page_content);
        //_view('user/custom_page',$page_content);
    }


    /**
    * Check entry params for changing password
    *
    * @author Drovorubov
    * @param array $data
    * @return string
    */
    function _check_change_password_fields($data)
    {
        $rv = '';
        if( $data['old_pwd'] == '')
        {
            $rv = '<{user_change_password_error_old_pwd_empty}>';
            return $rv;
        }
        if( $data['generate_random'] != 1 )
        {
            if( $data['new_pwd'] == '' || $data['retype_pwd'] == '' )
            {
                $rv = '<{user_change_password_error_new_pwd_empty}>';
                return $rv;
            }
            if( strcmp($data['new_pwd'],$data['retype_pwd']) != 0 )
            {
                $rv = '<{user_change_password_error_retype_pwd_not_match}>';
                return $rv;
            }
            if( !$this->user_model->check_password($data['new_pwd']) )
            {
                $rv = '<{user_change_password_error_new_pwd_is_invalid}>';
                return $rv;
            }
            if( mb_strlen($data['new_pwd']) > 64 )
            {
                $rv = '<{user_change_password_error_new_pwd_toolong}>';
                return $rv;
            }
            if( mb_strlen($data['new_pwd']) < 5 )
            {
                $rv = '<{user_change_password_error_new_pwd_tooshort}>';
                return $rv;
            }
        }

        return $rv;
    }




    /**
    * Prepare array as additional fields values from POST source
    *
    * @author Drovorubov
    * @param array $add_fields_list
    * @return array
    */
    function _prepare_post_usr_add_fields($add_fields_list)
    {
        $i = 0;
        $add_fields_user_data = array();
        if( count($add_fields_list) > 0 )
        {
            //Collect array as additional fields with user data
            foreach( $add_fields_list as $null=>$field_id_from_db )
            {
                $field_id_from_db = $field_id_from_db['id'];
                if( isset($_POST['add_field_'.intval($field_id_from_db)]) )
                {
                    unset($post_value,$key,$val,$prepare_post_value);
                    $post_value = $_POST['add_field_'.intval($field_id_from_db)];
                    $add_fields_user_data[$i]['id'] = intval($field_id_from_db);
                    if( is_array($post_value) and sizeof($post_value)>0)
                    {
                        foreach( $post_value as $key=>$val )
                        {
                            $prepare_post_value[$key] = prepare_text($val);
                        }
                        $add_fields_user_data[$i]['value'] = $prepare_post_value;
                    }
                    else
                    {
                        $add_fields_user_data[$i]['value'] = prepare_text($post_value);
                    }
                }
                else
                {
                    $add_fields_user_data[$i]['id'] = intval($field_id_from_db);
                    $add_fields_user_data[$i]['value'] = '';
                }
                $i++;
                unset($null,$field_id_from_db);
            }
        }
        return $add_fields_user_data;
    }



    /**
    * Convert user's additional fields values array as [0][id=>value] to id=>value
    * If the second param is not null it converts to array name=>value
    *
    * @author Drovorubov
    * @param array $add_fields_values
    * @param array $add_fields_names
    * @return array
    */
    function _convert_add_fields_values($add_fields_values, $add_fields_names = array() )
    {
        $rv = array();
        //Prepare array with id
        $idval_list = array();
        foreach( $add_fields_values as $null => $value )
        {
            $idval_list[intval($value['id'])] = $value['value'];
        }
        //Prepare array with names
        if( is_array($add_fields_names) && count($add_fields_names) > 1 )
        {
            foreach( $add_fields_names as $key => $value )
            {
                if( !isset($idval_list[$value['id']]) )
                {
                    $rv[$value['name']] = '';
                }
                else if( is_array($idval_list[$value['id']]) )
                {
                    $rv[$value['name']] = $idval_list[$value['id']][0];
                }
                else
                {
                    $rv[$value['name']] = $idval_list[$value['id']];
                }
                //wrap long strings;
                $rv[$value['name']] = word_wrap($rv[$value['name']],60,0,' ');
                $rv[$value['name']] = output($rv[$value['name']]);
            }
        }
        else
        {
            $rv = $idval_list;
        }
        return $rv;
    }



    /**
    * Check user's additional fields values
    *
    * @author Drovorubov
    * @param array $add_fields_arr
    * @return string
    */
    function _check_add_fields_values($add_fields_arr)
    {
        $error_text = "";
        $error_num = 0;
        foreach ( $add_fields_arr as $add_field )
        {
            $check_result = check_field($add_field['id'], $add_field['value']);
            if( $check_result !== true )
            {
                $error_num++;
                if( mb_strlen($check_result) > 1 )
                {
                    $error_text .= $check_result . '<br/>';
                }
            }
        }
        if( $error_num > 0 )
        {
            if( $error_text != '' )
            {
                return $error_text;
            }
            return "Error: Additinal fields have wrong values";
        }

        return '';
    }



    /**
    * Prepare array as additional fields values from DB source
    *
    * @author Drovorubov
    * @param array $add_fields_list
    * @param integer $uid
    * @return array
    */
    function _prepare_db_usr_add_fields($add_fields_list,$uid)
    {
        $i = 0;
        $add_fields_user_data = array();
        //Collect array from DB
        $data = $this->user_model->get_user_add_fields($uid);
        $user_data_arr = array();
        if( $data && count($data) > 0 )
        {
            foreach($data as $item)
            {
                //$tmp  = unserialize($item['field_value']);
                //$key = $tmp['id'];
                //$val = $tmp['value'];
                $key = $item['field_id'];
                $val = explode ('\n',$item['field_value']);
                $user_data_arr[$key] = $val;
            }
        }
        if( is_array($user_data_arr) && count($user_data_arr) > 0 )
        {
            //Collect array as additional fields with user data
            foreach( $add_fields_list as $null=>$field_id_from_db )
            {
                $field_id_from_db = intval($field_id_from_db['id']);
                if( isset($user_data_arr[$field_id_from_db]) )
                {
                    unset($user_value,$key,$val,$prepare_user_value);
                    $user_value = $user_data_arr[$field_id_from_db];
                    $add_fields_user_data[$i]['id'] = $field_id_from_db;
                    if( is_array($user_value) and sizeof($user_value)>0)
                    {
                        //add value as array
                        foreach( $user_value as $key=>$val )
                        {
                            $prepare_user_value[$key] = prepare_text($val);
                        }
                        $add_fields_user_data[$i]['value'] = $prepare_user_value;
                    }
                    else
                    {
                        $add_fields_user_data[$i]['value'] = prepare_text($user_value);
                    }
                }
                else
                {
                    $add_fields_user_data[$i]['id'] = $field_id_from_db;
                    $add_fields_user_data[$i]['value'] = '';
                }
                $i++;
                unset($null,$field_id_from_db);
            }
        }
        return $add_fields_user_data;
    }



    /**
    * Checks user's profile entry fields
    *
    * @author Drovorubov
    * @param array $param
    * @param integer $uid
    * @return string
    */
    function _check_user_fields($param,$uid)
    {
        $errors=array();
        $rv = '';
        if( $uid < 0 )
        {
            $rv = "<{user_error_id_invalid}>";
            $errors['uid']="<{user_error_id_invalid}>";                
        }
        else
        {
            foreach($param as $key=>$val)
            {
                if( $key == 'email' )
                {
                    if($val == '')
                    {
                        $rv = "<{user_profile_update_error_email_empty}>";
                        $errors['email_empty']= "<{user_profile_update_error_email_empty}>";
                        //break;
                    }
                    if( mb_strlen($val) < 4 || mb_strlen($val) > 64 )
                    {
                        $rv = "<{user_profile_update_error_email_length}>";
                        $errors['email_length']= "<{user_profile_update_error_email_length}>";
                        //break;
                    }
                    //Check email domain
                    if( !preg_match('/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}$/',$val) )
                    {
                        $rv = "<{user_profile_update_error_email_invalid}>";
                        $errors['email_invalid']= "<{user_profile_update_error_email_invalid}>";
                        //break;
                    }
                    //Check if email exests
                    if( $this->user_model->is_email_exists($val,$uid) )
                    {
                        $rv = "<{user_profile_update_error_email_exists}>";
                        $errors['email_exists']= "<{user_profile_update_error_email_exists}>";
                        //break;
                    }
                    //Check if email domain allowed
                    // Added by Konstantin X @ 2009.01.09
                    if( 2 == $this->user_auth_model->check_email_domain($val))
                    {
                        $rv = "<{admin_msg_er_0028}>";
                        $errors['email_denied_domain']= "<{admin_msg_er_0028}>";
                        //break;
                    }
                }
                else if( $key == 'name' )
                {
                    if($val == '')
                    {
                        $rv = "<{user_profile_update_error_name_empty}>";
                        $errors['name_empty']= "<{user_profile_update_error_name_empty}>";
                        //break;
                    }
                    else if( mb_strlen($val) > 32 )
                    {
                        $rv = "<{user_profile_update_error_name_length}>";
                        $errors['name_length']= "<{user_profile_update_error_name_length}>";
                        //break;
                    }
                }
                else if( $key == 'last_name' )
                {
                    if($val == '')
                    {
                        $rv = "<{user_profile_update_error_last_name_empty}>";
                        $errors['last_name_empty']= "<{user_profile_update_error_last_name_empty}>";
                        //break;
                    }
                    else if( mb_strlen($val) > 32 )
                    {
                        $rv = "<{user_profile_update_error_last_name_length}>";
                        $errors['last_name_length']= "<{user_profile_update_error_last_name_length}>";
                        //break;
                    }
                }
            }
        }
        
        //return $rv;
        return $errors;
    }
}
?>
