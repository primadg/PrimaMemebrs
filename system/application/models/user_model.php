<?php
/**
 *
 * THIS FILE CONTAINS User_model CLASS
 *
 * @package Prima DG
 * @author uknown
 * @version uknown
 */

/**
 *
 * THIS CLASS CONTAINS METHODS FOR WORK WITH USER
 *
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class User_model extends Model {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function User_model()
    {
        parent::Model();
    }

    function Registration_init()
    {
        $CI=&get_instance();
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
        $data['email_retype']='';
        $data['fname']='';
        $data['lname']='';
        $data['tos']='';
        $data['generate']='';
        $data['password']='';
        $data['submit_disabled']='disabled';
        $data['login_authentication']=(Functionality_enabled('admin_member_email_authentication')!==true) ? array(array()) :array();
        //$data['member_force_pwd_gen'] = intval(config_get('SYSTEM','CONFIG','member_force_pwd_gen'))>0 ? array() : array(array());
        $data['member_need_activation'] = intval(config_get('SYSTEM','CONFIG','member_need_activation'));
        $data['activation_code'] = md5(mktime().uniqid("")).(md5(mktime()));
        $data['error_box']=array();
        
        $data['error_box']['login']=array('display'=>false,'text'=>'<{user_registration_error_login}>');
        $data['error_box']['email']=array('display'=>false,'text'=>'<{user_registration_error_email}>');
        $data['error_box']['fname']=array('display'=>false,'text'=>'<{user_registration_error_fname}>');
        $data['error_box']['lname']=array('display'=>false,'text'=>'<{user_registration_error_lname}>');
        $data['error_box']['password']=array('display'=>false,'text'=>'<{user_registration_error_password}>');
        $data['error_box']['password_not_match']=array('display'=>false,'text'=>'<{user_registration_error_password_not_match}>'); 
        
        $data['fields'] = $CI->config_model->member_page_get('registration'); 
        
        return $data;
    }
    
    function Registration_allowed($data)
    {
        $CI=&get_instance();
        //if_user_authorezed
        if($CI->user_auth_model->is_auth())
        {
            redirect("user/info");
            die;
        }
        //end_of_if_user_authorezed
        $error=false;
        //if_ip_is_banned
        if(($is_ip_banned_reason=$CI->user_auth_model->is_ip_banned($CI->input->ip_address()))!=false)
        {
            $data['error_box']['ip_banned']=array(
            'display'=>1,
            'text'=>"<{user_login_banned_ip}> ".(!empty($is_ip_banned_reason) ? "<{user_login_banned_ip_reason}> ".$is_ip_banned_reason : "")."<br/><{user_login_banned_ip_admin_email}> ".config_get('SYSTEM','MAILER','admin_email')
            );
            $error=true;    
        }
        //end_of_if_ip_is_banned
        
        //registration_not_allowed
        if(intval(config_get('SYSTEM','CONFIG','member_allow_register'))<=0)
        {
            $data['error_box']['registration_not_allowed']=array('display'=>1,'text'=>"<{user_login_registration_not_allowed}>");
            $error=true;
        }
        //end_of_registration_not_allowed
        
        return $error ? $data : true;
    }
    
    
    function Password_check($data,$is_admin=false)
    {
        $CI=&get_instance();
        //POST_VALIDATION
        $page="password";
        $errors=array();
        fb($data,"Check data");
        
        //old password
        $data['old_password_type']=false;
        $name='old_password';
        $value=isset($data[$name]) ? $data[$name] : '';
        $value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if(!$is_admin && $result['enabled'])
        {
            if(!count($result['errors']))
            {
                if(!$this->check_old_password($data['uid'],$value)) 
                { 
                    $result['errors'][]='invalid';
                }
            }
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $user_info = $this->get_profile_by_uid($data['uid']);
            $data[$name]=$user_info[0]['pass'];
            $data[$name.'_type']=true;
        }
         
        //password
        $name='password';
		$value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        fb($data,"-DATA-");
        fb($result,"PWD");
        if($result['enabled'] && (!$result['generate'] || ($result['generate'] && !$data['generate'])))
        {
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]= $this->generate_password();
        }		
		return $this->field_errors_normalize($data,$page,$errors);
    }
    
    
    function Field_errors_normalize($data,$page,$errors)
    {
        //print_r($errors);
        //fb($errors,"errors");
        
        foreach($errors as $key=>$value)
        {
            foreach($value as $e)
            {
                //$data['error_box'][$key.'_'.$e]=array('display'=>1,'text'=>'<{user_'.$page.'_err_'.$key.'_'.$e.'}>');
                $data['error_box'][$key.'_'.$e]=array('display'=>1,'text'=>lvar('<{user_'.$page.'_err_'.$e.'}>',array('field'=>'<{user_'.$page.'_field_'.$key.'_title}>')));
            }
        }
        //fb($data,"Result");
        return $data;
    }
        
    var $profile_types=array(1=>'profile_domain',2=>'profile_billing');
    function Profile_additional_list($uid=0) 
    {
        $CI=&get_instance();
        $uid=intval($uid) ? $uid : $CI->user_auth_model->uid;        
        $where=array('user_id'=>intval($uid));
        $this->db->select("id,account_type,account_name");
        $query=$this->db->get_where(db_prefix."Account_info",$where);
        $r=$query->result_array();
        $result=array_combine($this->profile_types, array_fill(0,count($this->profile_types),array()));
        foreach($r as $v)
        {
            if(intval($v['account_type']) && isset($this->profile_types[$v['account_type']]))
            {
                $result[$this->profile_types[$v['account_type']]][]=array_merge(array(
                'account_id'=>$v['id'],
                'account_type_string'=>$this->profile_types[$v['account_type']]
                ),$v);
            }
        }
        $return=array();
        foreach($result as $k=>$v)
        {
            if(Functionality_enabled('member_registered_menu_'.$k)===true)
            {
                $return[$k]=$v;
            }
        }
        return $return;
    }
    
    function Profile_additional_get($page,$id,$account_id=0,$is_subscr_info=false)
    {
        if(!in_array($page,$this->profile_types))
        {
            return false;
        }
        $where=array('user_id'=>intval($id));
        if(intval($account_id))
        {
            $where['id']=intval($account_id);
        }
        $where['account_type']=array_search($page,$this->profile_types);
        $table=$is_subscr_info ? "Subscription_info" : "Account_info"; 
		$query=$this->db->get_where(db_prefix.$table,$where);
        $result=$query->result_array();
        return count($result) ? $result[0] : false;        
    } 
    
    function Profile_additional_set($page,$data)
    {
        if(!in_array($page,$this->profile_types))
        {
            return false;
        }
        $CI=&get_instance();
        if(!isset($data['fields']))
        {
            $CI->load->model('config_model');
            $data['fields']=$CI->config_model->member_page_get($page);
        }
        //fb($data,"DATA");
        $info=array();
        foreach($data['fields'] as $k=>$v)
        {
            $info[$k]=isset($data[$k]) ? $data[$k] : '';            
        }
        $info['user_id']=$data['id'];
        $info['telnocc']=isset($data['telnocc']) ? $data['telnocc'] : "";
        $info['alttelnocc']=isset($data['alttelnocc']) ? $data['alttelnocc'] : "";
        $info['faxnocc']=isset($data['faxnocc']) ? $data['faxnocc'] : "";
        $info['account_type']=array_search($page,$this->profile_types);
        $info['account_name']=isset($data['account_name']) ? $data['account_name'] : replace_lang('<{user_menu_profile_additional_'.$page.'_add}>');
        //fb($info,"INFO");
        if(isset($data['save_to_session']) && $data['save_to_session'])
        {
            $_SESSION['additional_profiles'][$page]=$info;
            if(!isset($data['asdefault']) || !intval($data['asdefault']))
            {
                return true;
            }
        }
        $table="Account_info";
        if(isset($data['restore_from_session']) && $data['restore_from_session'])
        {
            $table="Subscription_info";
            $info['id']=$data['account_id'];
			unset($info['forceuse']);
            unset($data['account_id']);
        }
        if(isset($data['account_id']) && intval($data['account_id']))
        {
            return $this->db->update(db_prefix.$table,$info,array('id'=>intval($data['account_id'])));
        }
        else
        {
            return $this->db->insert(db_prefix.$table,$info);
        }        
    }
    
    function Profile_additional_autofill($page,$data=false)
    {
        $relations=array();
        $profiles=$this->profile_additional_list();
        if(isset($profiles['profile_billing']))
        {$relations['profile_domain']='profile_billing';}
        if(isset($profiles['profile_domain']))
        {$relations['profile_billing']='profile_domain';}
        
        if(isset($relations[$page]))
        {
            //Autofill available for this page
            if($data==false)
            {
                return true;
            }
            $autofill_info = $this->Profile_additional_get($relations[$page],$data['id']);
            foreach($data['fields'] as $k=>$v)
            {
                if(isset($data[$k]) && $k!='forceuse')
                {
                    if(     ($k=='country' && $data[$k]=='AD') ||
                            ($k=='state' && $data[$k]=='XX') ||
                            ($k=='customerlangpref' && $data[$k]=='en') ||
                            empty($data[$k]))
                    {
                        $data[$k]=$autofill_info[$k];
                        if(in_array($k,array('telno','alttelno','fax')) && isset($data[$k.'cc']))
                        {
                            $data[$k.'cc']=$autofill_info[$k.'cc'];
                        }
                    }
                }
            }
        }
        return $data;
    }
    
    function User_fields_check($data,$page,$is_admin=false)
    {
        $CI=&get_instance();
        //Preparing data before validations
        //convert phones to validation
        $phones=array('telno','alttelno','faxno');
        foreach($phones as $p)
        {
            if(isset($data[$p]))
            {
                $data['temp_'.$p]=$data[$p];
                $data[$p]=empty($data[$p])&&empty($data[$p."cc"]) ? "" : "(".$data[$p."cc"].")".$data[$p];
            }
        }
        //End of preparing data before validations
        
        $errors=array();
        if(!isset($data['fields']))
        {
            $CI->load->model('config_model');
            $data['fields']=$CI->config_model->member_page_get($page);
        }
        //fb($data,"Check data");
        foreach($data['fields'] as $name=>$field)
        {
            $value=isset($data[$name]) ? $data[$name] : '';
            $value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
            $result=member_fields_check($page,$name,$value,$value_retype);
            if($result['enabled'])
            {
                if(count($result['errors']))
                {
                    $errors=array_merge($errors,array($name=>$result['errors']));
                }            
            }
            else
            {
                $data[$name]='';    
            }
        }
        $data['field_checking_errors']=$errors;
        //Preparing data after validations
        //return unconverted phones
        foreach($phones as $p)
        {
            if(isset($data['temp_'.$p]))
            {
                $data[$p]=$data['temp_'.$p];
                unset($data['temp_'.$p]);
            }
        }        
        //End of preparing data after validations
        return $this->field_errors_normalize($data,$page,$errors);
    }
    
    function Profile_check($data,$is_admin=false)
    {
        $CI=&get_instance();
        //POST_VALIDATION
        $page="profile";
        $errors=array();
        fb($data,"Check data");
        
        //email
        $name='email';
		$value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
			if(!count($result['errors']))
			{
				if($CI->user_auth_model->is_email_exists($value,$data['uid'])==true) 
				{ 
					$result['errors'][]='exists';
				}
				$email_domain_status = intval($CI->user_auth_model->check_email_domain($value));
                if( $email_domain_status == 2 )
                {
                    if($is_admin)
                    {
                        $data['denied_domain']=true;
                    }
                    else
                    {
                        $result['errors'][]='denied_domain';
                    }
                }
				if( $email_domain_status == 1)
				{
					$data['member_need_activation'] = 0;
				}
			}
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
        
        /*
        //login
        $name='login';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        fb($result,"Login result");
        if($result['enabled'])
        {
            if(!count($result['errors']))
            {
                if( $CI->user_auth_model->is_login_exists($value)==true ) 
                { 
                    $result['errors'][]='exists';
                }                
            }
            fb($result['errors'],"Login errors");
             
            if(count($result['errors']))
            {
                //$errors=array_merge($errors,array($name=>$result['errors']));
                fb($errors,'Login pre');
                $a=array($name=>$result['errors']);
                fb($a,'Login a');
                $errors=array_merge($errors,$a);
                fb($errors,'Login post');
                
                
            }            
        }
        else
        {
            $data[$name]=$data['email'];    
        }
        */
		
		//fname
		$name='fname';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
		//lname
		$name='lname';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
				
		//additional
		$name='additional';
        $value=isset($data[$name]) ? 'additional' : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(!count($result['errors']))
            {
                $data['user_add_fields']=set_user_add_fields($_POST,true);
                foreach($data['user_add_fields']['errors'] as $key=>$value)
                {
                    $data['error_box'][$key]=array('display'=>1,'text'=>$value);
                }
            }
			if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }
            else
            {
                $data[$name]=true; 
            }
        }
        else
        {
            $data[$name]=false;    
        }
		return $this->field_errors_normalize($data,$page,$errors);
    }
    
    
    
    function Registration_check($data,$is_admin=false)
    {
        $CI=&get_instance();
        //POST_VALIDATION
        $page="registration";
        $errors=array();
        fb($data,"Check data");
        
        //email
        $name='email';
		$value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
			if(!count($result['errors']))
			{
				if($CI->user_auth_model->is_email_exists($value)==true) 
				{ 
					$result['errors'][]='exists';
				}
				$email_domain_status = intval($CI->user_auth_model->check_email_domain($value));
				if( $email_domain_status == 2 )
				{
                    if($is_admin)
                    {
                        $data['denied_domain']=true;
                    }
                    else
                    {
                        $result['errors'][]='denied_domain';
                    }
				}
				if( $email_domain_status == 1)
				{
					$data['member_need_activation'] = 0;
				}
			}
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
        
        //login
        $name='login';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        fb($result,"Login result");
        if($result['enabled'])
        {
            if(!count($result['errors']))
            {
                if( $CI->user_auth_model->is_login_exists($value)==true ) 
                { 
                    $result['errors'][]='exists';
                }                
            }
            fb($result['errors'],"Login errors");
             
            if(count($result['errors']))
            {
                //$errors=array_merge($errors,array($name=>$result['errors']));
                fb($errors,'Login pre');
                $a=array($name=>$result['errors']);
                fb($a,'Login a');
                $errors=array_merge($errors,$a);
                fb($errors,'Login post');
            }            
        }
        else
        {
            $data[$name]=$data['email'];    
        }
		
		//fname
		$name='fname';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
		//lname
		$name='lname';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
		//password
        $name='password';
		$value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        fb($result,"pwd");
        if($result['enabled'] && (!$result['generate'] || ($result['generate'] && !$data['generate'])))
        {
			if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]= $this->generate_password();
        }

		//tos
		$name='tos';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if(!$is_admin && $result['enabled'])
        {
            
			if(!count($result['errors']))
            {
                if(intval($value)<=0) 
                { 
                    $result['errors'][]='not_checked';
                }                
            }
			if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
		//image_code
        fb($data,"IMAGE CODE DATA");
		$name='image_code';
        $value=isset($data[$name]) ? $data[$name] : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        
        if(!$is_admin && $result['enabled'])
        {
            
			if(!count($result['errors']))
			{
				if(!isset($_COOKIE['PHPSESSID']))
				{
					$result['errors'][]='cookie';
				}
				else if(check_code($value) == false)
				{ 
					$result['errors'][]='invalid';
				}                
			}
			if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }            
        }
        else
        {
            $data[$name]='';    
        }
		
		//additional
		$name='additional';
        $value=isset($data[$name]) ? 'additional' : '';
		$value_retype=isset($data[$name.'_retype']) ? $data[$name.'_retype'] : '';
        $result=member_fields_check($page,$name,$value,$value_retype);
        if($result['enabled'])
        {
            if(!count($result['errors']))
            {
                $data['user_add_fields']=set_user_add_fields($_POST,true);
                foreach($data['user_add_fields']['errors'] as $key=>$value)
                {
                    $data['error_box'][$key]=array('display'=>1,'text'=>$value);
                }
            }
			if(count($result['errors']))
            {
                $errors=array_merge($errors,array($name=>$result['errors']));
            }
            else
            {
                $data[$name]=true; 
            }
        }
        else
        {
            $data[$name]=false;    
        }		
		return $this->field_errors_normalize($data,$page,$errors);
    }
    
    
    function Registration_send_emails($data)
    {
        
        $lang_id=$this->get_lang($data['user_id']);
        if(!send_system_email_to_user($data['user_id'],'user_registration_completed',array('user_password'=>$data['password'])))
        {
            $data['error_box']['email_notsend']=array('display'=>1,'text'=>"<{user_registration_send_registration_error_notsend}>");
        }
        $result=send_system_subscription_to_admins('admin_new_member_registered',array(
        'user_login'=>$data['login']));

        if($data['member_need_activation'] > 0) 
        {
            if(!send_system_email_to_user($data['user_id'],'user_account_activation',array('user_activation_link'=>site_url('user/activate/'.$data['user_id'].'/'.$data['activation_code']))))
            {
                $data['error_box']['email_activation_notsend']=array('display'=>1,'text'=>"<{user_registration_send_activation_error_notsend}>");
            }
        } 
        fb($data['error_box'],"error_box");
        return $data;
    }
    
    
    
    function value_exist($data)
    {
        $is_exist=false;
        $error_text="";
        $action="";
        $value="";
        if(isset($data['name'])&& isset($data['value']))
        {
            $action=$data['name'];
            $value=$data['value'];
            $this->db->select('users.id');
            $this->db->from(db_prefix.'Users users');
            $this->db->where('users.'.$action,$value);
            if(isset($data['id'])&&$data['id']!="undefined")
            {
                $this->db->where('users.id!=',$data['id']);
            }
            $count=$this->db->count_all_results();
            $is_exist=$count>0?true:false;

            switch ($action)
            {
            case "email":
                $error_text='email_is_exist';
                break;
            case "login":
                $error_text='name_is_exist';
                break;
            }
        }

        $result=array();
        $result['is_error']=$is_exist;
        $result['error_text']=$error_text;
        $result['name']=$action;
        $result['value']=$value;
        return $result;
    }

	/**
	 * Check whether user status is expire or not
	 *
	 * @param mixed $uid
	 * @return boolean
	 */
    function is_expired($uid)
    {
        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return true;
        }

        $CI=&get_instance();
        $CI->load->model('member_model');
        $CI->member_model->check_and_update_expiration_term();

        $this->db->select('expired');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Account_status',array('user_id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            $expired = $query->row();
            $expired = $expired->expired;
            if( intval($expired)==0 )
            {
               return false;
            }
        }
        return true;
    }
	/**
	 * Get user status
	 *
	 * @param integer $uid
	 * @param integer $lang_id
	 * @return mixed
	 */
    function get_status($uid,$lang_id=false)
    {
        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }

        $this->db->limit(1);
        $this->db->select("ac.*");
        $this->db->from(db_prefix.'Account_status ac');
        $this->db->join(db_prefix.'Suspend_reasons sr', 'sr.id=ac.suspend_reason_id', 'left');

        $this->db->where(array('user_id'=>$uid));
        $query=$this->db->get();
        if( $query->num_rows() > 0 )
        {
            $t=$query->result_array();
            $CI =& get_instance();
            $CI->load->model("lang_manager_model");
            $t=$CI->lang_manager_model->combine_with_language_data($t,10,array('descr'=>'suspend_reason'),'suspend_reason_id',false,$lang_id,&$add_params);
        return $t;
        }

        return false;

    }

	/**
	 * Set language for user
	 *
	 * @param mixed $uid
	 * @param mixed $lang_id
	 * @return integer
	 */
    function set_lang($uid,$lang_id)
    {

        $uid = intval($uid)==0 ? intval(session_get('user_id')) : intval($uid);

        $lang_id = intval($lang_id);

        if( $lang_id <=0 )
        {
            return false;
        }

        if( $uid > 0 )
        {
            $this->db->where('id',$uid);
            $query = $this->db->update(db_prefix.'Users',array('language_id'=>$lang_id));
        }
        // this function is modified by Makarenko Sergey @ 05.11.08 15:53
        $CI = &get_instance();
        $CI->load->model('auth_model');
        $CI->auth_model->set_cookie_lang_id($lang_id);

        $this->lang_id=$lang_id;

        return $lang_id;
    }


    /**
     * Returns current language ID from DB for user, if none is detected then returns get_default_language()
     *
     * @param integer $uid
     * @return integer $lang_id
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function get_lang($uid=0)
    {
        $uid = intval($uid)==0 ? intval(session_get('user_id')) : intval($uid);

        $query=false;

        if($uid)
        {
            $this->db->select('users.id as uid, users.language_id as lang_id');
            $this->db->from(db_prefix.'Users as users, '.db_prefix.'Languages as lang');
            $this->db->where('users.language_id = lang.id');
            $this->db->where('users.id', $uid);
            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $res = $query->result_array();
                $lang_id = $res[0]['lang_id'];
            }
            else
            {
                $CI =& get_instance();
                $CI->load->model("user_auth_model");
                $lang_id = $CI->user_auth_model->get_default_language();
            }
            return $lang_id;
        }
        else
        {
            $CI =& get_instance();
            $CI->load->model("user_auth_model");
            $lang_id = $CI->user_auth_model->get_default_language();
            return $lang_id;
        }
    }

	/**
	 * Enter description here...
	 *
	 * @param mixed $uid
	 * @param array $fields
	 * @return boolean
	 */
    function set_addition_fields($uid,$fields)
    {
        if( intval($uid) <=0 )
        {
            return false;
        }

        if( !is_array($fields) or sizeof($fields)<=0 )
        {
            return false;
        }

        $result = 0;
        foreach( $fields as $key=>$value )
        {
            $field_value = $value['value'];

            if( is_array($field_value) )
            {
                $field_value = implode("\n",$field_value);
            }
            $query = $this->db->insert(db_prefix.'User_add_fields',array('user_id'=>$uid,'field_value'=>$field_value,'field_id'=>intval($value['id'])));
            if( $this->db->affected_rows() !==1 )
            {
                return false;
            }
            else
            {
                $result++;
            }
        }

        if( $result == sizeof($fields) )
        {
            return true;
        }


        return false;
    }
	/**
	 * Enter description here...
	 *
	 * @param mixed $uid
	 * @param array $fields
	 * @return boolean
	 */
    function update_addition_fields($uid,$fields)
    {
        if( intval($uid) <=0 )
        {
            return false;
        }

        if( !is_array($fields) or sizeof($fields)<=0 )
        {
            return false;
        }

        //Get current user additional fields list from DB
        $curr_addfields = $this->get_user_add_fields($uid);
        $curr_addfields_idlist = array();
        //Set an array with user field_id only
        foreach( $curr_addfields as $item )
        {
            $curr_addfields_idlist[] = $item['field_id'];
        }
        $error = false;
        foreach( $fields as $key=>$value )
        {
            $addfield_id = $value['id'];
            if( is_array($value['value']) )
            {
                $value['value'] = implode("\n",$value['value']);
            }
            $value['value'] = input_text($value['value']);
            //Add new record of update the old one for user additional fields
            if( in_array($addfield_id, $curr_addfields_idlist) )
            {
                $this->db->where('user_id',$uid);
                $this->db->where('field_id',$addfield_id);
                $query = $this->db->update(db_prefix.'User_add_fields',array('field_value'=>$value['value']));
                if( $this->db->affected_rows() == -1 )
                {
                    $error = true;
                }
            }
            else
            {
                $query = $this->db->insert(db_prefix.'User_add_fields',array('user_id'=>$uid,'field_value'=>$value['value'],'field_id'=>$addfield_id));
                if( $this->db->affected_rows() !== 1 )
                {
                    $error = true;
                }
            }
        }

        if( !$error )
        {
            return true;
        }
        return false;
    }
    
    
    /**
	 * Register a new user
	 *
	 * @param string $login
	 * @param string $enc_pwd
	 * @param string $email
	 * @param string $firstname
	 * @param string $expired
	 * @param string $lastname
	 * @param mixed $member_need_activation
	 * @param string $activation_code
	 * @param string $enc_pwd_bf
	 * @return mixed
	 */
    function register($login,$enc_pwd,$email,$firstname,$expired,$lastname,$member_need_activation,$activation_code,$enc_pwd_bf)
    {
        $member_approve_needed = intval(config_get('SYSTEM','CONFIG','member_approve_needed'));
        
        // this is modified by Makarenko Sergey @ 05.11.08 16:50
        $CI = &get_instance();
        $CI->load->model('auth_model');
        if ( intval($CI->auth_model->get_cookie_lang_id())>0 )
        {
            //$language_id = intval($_SESSION['lang_id']);
            $language_id = intval($CI->auth_model->get_cookie_lang_id());
        }
        else
        {

            // get default lang_id
            $this->db->select('id');
            $default_lang_query = $this->db->get_where(db_prefix.'Languages',array('is_default'=>1));
            if( $default_lang_query->num_rows() > 0 )
            {
                $default_lang_info = $default_lang_query->row();
                $language_id = intval($default_lang_info->id);
            }
            // _get default lang_id

        }

        if( intval($member_need_activation)<=0 )
        {
            $activate = intval(1);
        }
        else
        {
            $activate = intval(0);
        }

        $expire = intval(0);
        if( !empty($expired) )
        {
            $expire = intval(1);
        }

        $query = $this->db->insert(db_prefix.'Users',array(
        'login'=> $login,
        'pass' => $enc_pwd,
        'email' => $email,
        'language_id' => $language_id,
        'name' => $firstname,
        'last_name' => $lastname,
        'sec_code' => $enc_pwd_bf
        ));

        if( $this->db->affected_rows() == 1 )
        {
            // set Account status
            $user_id = intval($this->db->insert_id());
            $query = $this->db->insert(db_prefix.'Account_status',array(
            'user_id' => $user_id,
            'ac_code' => $activation_code,
            'approve' => (intval($member_approve_needed)>0)?0:1,
            'activate' => $activate,
            'expire' => intval($expire),
            'expired' => $expired
            ));
            // _set Account status
            if( $this->db->affected_rows() == 1 )
            {
                $CI=&get_instance();
                $CI->load->model("member_group_model");
                $CI->member_group_model->set_member_groups($user_id);
                return intval($user_id);
            }
            else
            {
                $this->db->where('user_id',$user_id);
                $this->db->delete(db_prefix.'Account_status');
                $this->db->where('id',$user_id);
                $this->db->delete(db_prefix.'Users');
                return false;
            }

        }
        else
        {
            return false;
        }
        
        return false;
    }

	/**
	 * Set user account status to activate
	 *
	 * @param mixed $uid
	 * @param string $ac_code
	 * @return boolean
	 */
    function activate($uid,$ac_code)
    {
        $uid = intval($uid);


        if( $uid <=0 )
        {
            return false;
        }

        if( mb_strlen($ac_code) !=64 )
        {
            return false;
        }

        $this->db->where('user_id',$uid);
        $this->db->where('ac_code',$ac_code);
        $query = $this->db->update(db_prefix.'Account_status',array('ac_code'=>'','activate'=>1));

        return (bool)$this->db->affected_rows();

        return false;
    }
	/**
	 * Get user info by $uid
	 *
	 * @param mixed $uid
	 * @return mixed
	 */
    function get_profile_by_uid($uid)
    {
        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }

        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Users',array('id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }

        return false;
    }
	/**
	 * Check is user acount status active
	 *
	 * @param mixed $uid
	 * @return boolean
	 */
    function is_active($uid)
    {
        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }

        $this->db->select('activate');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Account_status',array('user_id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            $active = $query->row();
            $active = $active->activate;
            if( intval($active) == 1 )
            {
                return true;
            }
        }


        return false;
    }


	/**
	 * Check is user acount status approve
	 *
	 * @param mixed $uid
	 * @return boolean
	 */
    function is_approve($uid)
    {

        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }

        $this->db->select('approve');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Account_status',array('user_id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            $approve = $query->row();
            $approve = $approve->approve;
            if( intval($approve) == 1 )
            {
                return true;
            }
        }

        return false;
    }

	/**
	 * Generate password
	 *
	 * @return string
	 */
    function generate_password()
    {
        $pass_range=array();
        $new_pass="";
        $pass_range[] = "0123456789";
        $pass_range[] = "bcdfghjkmnpqrstvwxyz";
        $pass_range[] = "BCDFGHJKMNPQRSTVWXYZ";
        $pass_range[] = "!@#$%^&*=+~?;-";

        while ( $this->check_password($new_pass)!==true )
        {
            $new_pass="";
            $length = intval(rand(7,13));
            for($i=0;$i<count($pass_range);$i++)
            {
                $l=intval(($length - strlen($new_pass))/(count($pass_range)-$i));
                $l=$l>1?$l:2;
                $new_pass.=(implode("", array_rand(array_flip(preg_split('//',$pass_range[$i])),$l)));
            }
            $arr=preg_split('//',$new_pass);
            shuffle($arr);
            $new_pass=implode("",$arr);
        }
        return $new_pass;
    }

	/**
	 * Check password for correct symbols
	 *
	 * @param string $pwd
	 * @return boolean
	 */
    function check_password($pwd)
    {
        if( mb_strlen($pwd)<7 )
        {
            return false;
        }

        if( (eregi("[a-zA-Z]+",$pwd)!=false and ( eregi("[0-9]+",$pwd)!=false or eregi("[\!@#$%^&*=+\/~<>?;-]+",$pwd)!=false  )) )
        {
            return true;
        }
        else
        {
            return false;
        }

    }
	/**
	 * Check is user acount status suspend
	 *
	 * @param mixed $uid
	 * @return mixed
	 */
    function is_suspend($uid)
    {

        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }

        $this->db->select('suspended,suspend_reason_id');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Account_status',array('user_id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            $suspend_info = $query->row();
            $suspended = $suspend_info->suspended;
            $suspend_reason_id = $suspend_info->suspend_reason_id;
            if( intval($suspend_reason_id) < 0 )
            {
                return true;
            }
            elseif( intval($suspended) > 0 )
            {
                $this->db->select('descr');
                $this->db->limit(1);
                $query = $this->db->get_where(db_prefix.'Language_data',array('object_id'=>$suspend_reason_id, 'object_type'=>10, 'language_id'=>1));
                if( $query->num_rows() > 0 )
                {
                    $reason = $query->row();
                    $reason = $reason->descr;
                    return strval($reason);
                }
                else
                {
                    return true;
                }
            }
        }

        return false;
    }



    /**
    * Gets additional fields values and return array
    *
    * @author Drovorubov
    * @param array $names /field names for select
    * @return mixed array/false
    */
    function get_add_fields($names)
    {
        $names_str = ($names == '*')? '*' : implode(",",$names);
        //Get additional fields list
        $this->db->select($names_str);
        $this->db->from(db_prefix.'Add_fields');
        $this->db->order_by('id');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }
        return false;
    }


    /**
    * Gets user id by login param
    *
    * @author Drovorubov
    * @param integer $login
    * @return mixed integer/false
    */
    function get_id_by_login($login)
    {
        if( $login === '' )
        {
            return false;
        }
        $this->db->select('id');
        $this->db->from(db_prefix.'Users');
        $this->db->where('login',$login);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row();
            return $row->id;
        }
        return false;
    }


    /**
    * Sets new password
    *
    * @author Drovorubov
    * @param integer $uid
    * @param string $new
    * @param string $old
    * @param boolean $old_type
    * @return boolean
    */
    function new_password($uid,$new,$old,$old_type=false)
    {
        if( $uid < 1 )
        {
            return false;
        }
        //Check new password
        $len = mb_strlen($new);
        if( $len < 1 || $len > 64 )
        {
            return false;
        }
        //Check old password
        $len = mb_strlen($old);
        if( $len < 1 || $len > 64 )
        {
            return false;
        }
        //Check coincidence
        if( strcmp($new,$old) == 0 )
        {
            return false;
        }
        //Encrypt new password
        $new = input_text($new);
        $new_enc = crypt($new);
        $new_enc_bf = ns_encrypt($new,$new_enc);
        
        //Get an old password by uid and use it as hash string
        $pass_hash = $this->_get_password('id',$uid);
        if( !isset($pass_hash) or empty($pass_hash) )
        {
            return false;
        }
        //Set a new password
        $this->db->where('id',$uid);
        if($old_type == true)
        {
            $this->db->where('pass',$old);
        }
        else
        {
            $this->db->where('pass',crypt($old,$pass_hash));
        }
        $query = $this->db->update(db_prefix.'Users',array('pass'=>$new_enc, 'sec_code'=>$new_enc_bf ));
        $rv = $this->db->affected_rows();
        //Fire protection event
        protection_event("USER_UPDATED",$uid);
        return (bool)$rv;
    }


    /**
    * Checks if an old password is correct
    *
    * @author Drovorubov
    * @param integer $uid
    * @param string $pwd
    * @return bool
    */
    function check_old_password($uid,$pwd)
    {
        if( $uid < 1 || $pwd === '' )
        {
            return false;
        }
        //Get an old password by uid and use it as hash string
        $pass_hash = $this->_get_password('id',$uid);
        if( !isset($pass_hash) or empty($pass_hash) )
        {
            return false;
        }
        //Get password according hash string
        $this->db->select('*');
        $this->db->from(db_prefix.'Users');
        $this->db->where('id',$uid);
        $this->db->where('pass',crypt($pwd,$pass_hash));
        $query = $this->db->get();
        if ( $query->num_rows() == 1 )
        {
            return true;
        }
        return false;
    }


    /**
    * Gets user's password by key
    *
    * @param string $key
    * @param string $val
    * @return string
    */
    function _get_password($key,$val)
    {
        $query = $this->db->get_where(db_prefix.'Users',array($key=>$val));
        if( $query->num_rows() == 1 )
        {
            $query_info = $query->row();
            return $query_info->pass;
        }
        return '';
    }


    /**
    * Gets user's additional fields values by user id
    *
    * @author Drovorubov
    * @param integer $uid
    * @return mixed array/false
    */
    function get_user_add_fields($uid)
    {
        $rv = array();

        //Get additional fields list
        $this->db->select('field_id, field_value');
        $this->db->from(db_prefix.'User_add_fields');
        $this->db->where('user_id',$uid);
        $this->db->order_by('field_id');
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv = $query->result_array();
            return $rv;
        }
        return false;
    }


    /**
    * Checks if user's email exists in DB
    * If id value is set exclude the row with this id
    *
    * @param string $email
    * @param mixed $id
    * @author Drovorubov
    * @return boolean
    */
    function is_email_exists($email,$id='')
    {
        fb($email,'EMAIL');
        if( !isset($email) || empty($email) )
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
    * Update user profile
    *
    * @author Drovorubov
    * @param integer $id
    * @param array $param
    * @return bool
    */
    function set_profile($id,$param)
    {
        if( $id < 1 || !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Check if user exists
        $query = $this->db->get_where(db_prefix.'Users', array('id' => $id));
        $info=$query->result_array();

        if( $query->num_rows() < 1 )
        {
            return false;
        }
        //Prepare params before updating
        $data = $this->_prepare_user_params($param);

        //Update Users table
        if(count($data) > 0)
        {
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_member_email_authentication')===true && isset($data['email']))
            {
                if($data['email']!=$info[0]['email'])
                {
                    $data['login']=$data['email'];
                }
            }
            //*******End of functionality limitations********

            $this->db->where('id', $id);
            $this->db->update(db_prefix.'Users', $data);

            //***********Functionality limitations***********
            if(Functionality_enabled('admin_member_email_authentication')===true && isset($data['email']))
            {
                if($data['email']!=$info[0]['email'])
                {
                    protection_event("USER_UPDATED",$id);
                }
            }
            //*******End of functionality limitations********
        }
        return true;
    }


    /**
    * Prepares entry params
    *
    * @author Drovorubov
    * @param array $prm
    * @return bool
    */
    function _prepare_user_params($prm)
    {
        $data = array();
        foreach($prm as $key=>$val)
        {
            if( $key == 'email' )
            {
                if( mb_strlen($val) > 64)
                {
                    $val = mb_strcut($val, 1, 64);
                }
                $data['email'] = input_text($val);
            }
            else if( $key == 'name' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $data['name'] = input_text($val);
            }
            else if( $key == 'last_name' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $data['last_name'] = input_text($val);
            }
        }
        return $data;
    }

    /**
    * Get additional pages list array(sid=>page_title)
    *
    * @author onagr
    * @param boolean $return_native
    * @return mixed
    */
    function Get_pages_list($return_native=false)
    {
        $conditions=array();
        $conditions['published']=1;
        $conditions['show_in_menu']=1;

        $CI=&get_instance();
        if(!$CI->user_auth_model->is_auth())
        {
            $conditions['members_only']=0;
        }
        $this->db->order_by('taborder');
        $query = $this->db->get_where(db_prefix.'Pages',$conditions);
        $pages=$query->result_array();
        if(count($pages)>0)
        {
            $CI->load->model("lang_manager_model");
            $pages=$CI->lang_manager_model->combine_with_language_data($pages,9,array('name'=>'page_title'),'id',false,false,&$add_params);
            if(!$return_native)
            {
                $pages=array_transform($pages,'sid','page_title');
            }
            return $pages;
        }
        return false;
    }

    /**
    * Get additional page
    *
    * @author onagr
    * @param string $sid
    * @return mixed
    */
    function Get_page_content($sid)
    {
        $CI=&get_instance();
        $query = $this->db->get_where(db_prefix.'Pages',array('sid'=>$sid));
        $pages=$query->result_array();
        if(count($pages)>0)
        {
            $CI->load->model("lang_manager_model");
            $pages=$CI->lang_manager_model->combine_with_language_data($pages,9,array('name'=>'page_title','descr'=>'page_content','add'=>'keywords'),'id',false,false,&$add_params);
            $pages=$pages[0];
            $pages['keywords']=empty($pages['keywords']) ? false : explode(",",$pages['keywords']);
            unset($pages['taborder']);
            unset($pages['id']);
            return $pages;
        }
        return false;
    }




}
?>
