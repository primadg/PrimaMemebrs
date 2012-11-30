<?php
/**
 * 
 * THIS FILE CONTAINS Payment CLASS
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
 * Enter description here...
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Payment extends User_Controller {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */ 
    function Payment()
    {
        parent::User_Controller();
    }
	/**
	 * Enter description here...
	 *
	 */
    function Configure()
    {
        $post=prepare_post();
        $errors=array();
        $is_load_list=false;
        //if save configuration
        if(isset($post['action'])&&$post['action']=='save')
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_payment_modify');
            if($functionality_enabled_error!==true)
            {   
                $errors[]=$functionality_enabled_error;
                //*******End of functionality limitations********
            }
            else
            {
                
                
                if(isset($post['id']))
                {
                    $data=config_get('PAYMENT',$post['id']);
                    if($data!=false)
                    {
                        $temp=$this->config_validation($post);
                        if(count($temp['errors']))
                        {$errors=$temp['errors'];}
                        if(count($temp['mess']))
                        {$mess=$temp['mess'];}
                        $is_load_list=$temp['is_load_list'];
                    }
                    else
                    {
                        $errors[]="not_defined";
                    }
                }
                else
                {
                    $errors[]="invalid_id";
                }
                simple_admin_log('payment_system_modify',(isset($post['id']) ? $post['id'] : false),(count($errors)>0),$errors,session_get('user_id'));
            }
        }
        //if cancel configuration
        if(isset($post['action'])&&$post['action']=='cancel')
        {
            $is_load_list=true;
        }

        //view configuration of payment system
        if(!isset($post['action'])||$post['action']=='')
        {
            if(isset($post['id']))
            {
                $data=config_get('PAYMENT',$post['id']);
                if($data!=false)
                {
                    $data['id']=$post['id'];
                    $res=$this->config_form($data);
                    make_response("output", $res, 1);
                }
                else
                {
                $errors[]="not_defined";
                }
            }
            else
            {
            $errors[]="not_defined";
            }
        }
        //output list of payment systems
        if($is_load_list)
        {
            $this->load->model("config_model");
            $data = Array();
            $data=$this->config_model->add_panel_vars_ex($data,"payment_system");
            $data = $this->config_model->payment_system($data);
            $res = $this->load->view("/admin/config/payment_systems/payment_system", $data, true);
            make_response("output", $res, 1);
        }

        //if udefined action
        if(count($errors)==0 && isset($post['action']) && $post['action']!='' && !$is_load_list)
        {
        $errors[]="udefined_action";
        }

        //if is error
        if(count($errors)!=0)
        {
            make_response("error",create_temp_vars_set($errors), 1);
        }
    }
	/**
	 * Whether checks the user can buy the given product
	 *
	 * @return true
	 */
    function index()
    {
        $this->load->model('payment_model');
        $this->payment_model->checkout();
        return true;
    }
    
    /**
	 * Auto billing profile
	 * The substitution of billing information from user billing profile
	 */
        
    function Auto_additional_profiles()
    {
        fb(__FUNCTION__,"FUNCTION");
		$this->load->model('user_auth_model');
		$this->load->model('cart_model');
        if($this->cart_model->is_contains_product_hosted())
        {
            $page='profile_domain';
            $account_id=0;
            $user_id=$this->user_auth_model->uid;
            fb($_SESSION,'$_SESSION');
            if(!isset($_SESSION['additional_profiles']) || 
                    !isset($_SESSION['additional_profiles'][$page]) || 
                    !is_array($_SESSION['additional_profiles'][$page]))
            {
                $data = $this->user_model->Profile_additional_get($page,$user_id,$account_id);
                fb($data,"PREDEF_REGDATA");
                //fb($data,"BEFORE_REGDATA");
                if($data)
                {
                    $account_id=$data['id'];
                    if(!isset($data['forceuse']) || !intval($data['forceuse']))
                    {
                        redirect('user/profile_additional/'.$page.'/'.$account_id.'/'.encode_url($this->uri->uri_string()));
                        return false;
                    }
                }
                else
                {
                    $data=array();
                }
            }
            else
            {
                $data=$_SESSION['additional_profiles'][$page];
            }
            $data['error_box']=array();
            fb($data,"BEFORE_REGDATA");
            $data=$this->user_model->user_fields_check($data,$page);
            fb($data,"REGDATA");
            if(is_msg_displayed($data['error_box']))
            {
                redirect('user/profile_additional/'.$page.'/'.$account_id.'/'.encode_url($this->uri->uri_string()));
                return false;
            }
            else
            {
                $data['save_to_session']=true;
                $data['id']=$user_id;
                $this->user_model->profile_additional_set($page,$data,$account_id);
            }
        }
        
        if(intval(config_get('system','config','member_force_billing_info_input')))
        {
            $billing_profile=$this->get_billing_profile();
            if(isset($billing_profile['forceuse']) && intval($billing_profile['forceuse']))
            {
                $_POST=array_merge($billing_profile,$_POST);
                if(!isset($_POST['submit_action']))
                {
                    $_POST['submit_action']='order';
                }
            }   
        }
        return true;
    }
    
    function Set_additional_profiles()
    {
        $user_info_id = 0;
        $post = prepare_post();
        $addition_fields = array();
        $user_info_id = $this->payment_model->set_user_info($post,$addition_fields);
		$data=array();
		$this->load->model('user_model');
		$this->load->model('cart_model');
        $page='profile_domain';
		if($this->cart_model->is_contains_product_hosted())
		{
			if(isset($_SESSION['additional_profiles']) && 
					isset($_SESSION['additional_profiles'][$page]) && 
					is_array($_SESSION['additional_profiles'][$page]))
			{
				$data=$_SESSION['additional_profiles'][$page];
			}
			$data['id']=isset($data['user_id']) ? $data['user_id'] : 0;
			fb($user_info_id,'$user_info_id');
			$data['account_id']=$user_info_id;
			$data['restore_from_session']=true;
			$this->user_model->profile_additional_set($page,$data);
		}
		return $user_info_id;
    }
    
    /**
	 * Get billing profile
	 *
	 * @return array
	 */
    function Get_billing_profile()
    {
        $this->load->model('user_model');
        $this->load->model('user_auth_model');
        $profile_billing=$this->user_model->profile_additional_get('profile_billing',$this->user_auth_model->uid);
        $result=array();
        if($profile_billing)
        {
            if(!intval(_config_get('member_pages','profile_billing','name','enabled')) && ($info=$this->user_model->get_profile_by_uid($this->user_auth_model->uid)))
            {
                $profile_billing['name']=$info[0]['name']." ".$info[0]['last_name'];    
            }
            $result['forceuse']=$profile_billing['forceuse'];
            $result['billing_name']=$profile_billing['name'];
            $result['country']=$profile_billing['country'];
            $result['state']=$profile_billing['state'];
            $result['city']=$profile_billing['city'];
            $result['zip']=$profile_billing['zip'];
            $result['street']=$profile_billing['address1'];
            $result['phone']=$profile_billing['telnocc'].$profile_billing['telno'];
        }
        return $result;
    }
    
    /**
	 * Show payment form
	 *
	 * @param unknown_type $add_fields
	 * @param unknown_type $controller
	 * @param unknown_type $errors
	 * @param unknown_type $POST
	 */
   function _show_payment_form($add_fields,$controller,$errors,$POST)
   {
        fb($_SESSION,'$_SESSION');
        $data = array();
        $data['action'] = site_url($controller);
        $data['payment_add_fields'] = $add_fields;
        $data['errors']  = $errors;
        $data['POST']  = $POST;
        $this->load->model('user_model');
        $this->load->model('user_auth_model');
        $POST=array_merge($this->get_billing_profile(),$POST);
        $need_billing_info = config_get('system','config','member_force_billing_info_input');
        if( $need_billing_info!==false )
        {
            $data['need_billing_info']=intval($need_billing_info);
        }
		
		$data['error_message'] = print_msg_box('emsg',$data['errors']);
		$data['billing_name'] = (isset($POST['billing_name']) and !empty($POST['billing_name']))?output($POST['billing_name']):'';
		if( isset($need_billing_info) and intval($need_billing_info)==1)
        {
			$data['if_need_billing_info'] = array(array());
		}
		else
		{
			$data['if_need_billing_info'] = array();
		}
		$countries = get_countries();
		$p_i = 0;
        foreach( $countries as $code=>$name)
        {
			$data['countries'][$p_i]['if_post_country'] = (isset($POST['country']) and $POST['country']==$code)? array(array()) : array();
			$data['countries'][$p_i]['name'] = $name;
			$data['countries'][$p_i]['code'] = output($code);
			$p_i++;
		}
		$states = get_states();
		$p_i = 0;
        foreach( $states as $code=>$name)
        {
			$data['states'][$p_i]['if_post_state'] = (isset($POST['state']) and $POST['state']==$code)? array(array()) : array();
			$data['states'][$p_i]['name'] = $name;
			$data['states'][$p_i]['code'] = output($code);
			$p_i++;
        }
		$data['if_post_city'] = (isset($POST['city']) and !empty($POST['city']))? array(array()):array();
		$data['else_post_city'] = (isset($POST['city']) and !empty($POST['city']))? array():array(array());
		$data['post_city'] = (isset($POST['city']) and !empty($POST['city']))?output($POST['city']):'';
		$data['if_post_zip'] = (isset($POST['zip']) and !empty($POST['zip']))? array(array()):array();
		$data['else_post_zip'] = (isset($POST['zip']) and !empty($POST['zip']))? array():array(array());
		$data['post_zip'] = (isset($POST['zip']) and !empty($POST['zip']))?output($POST['zip']):'';
		$data['if_post_street'] = (isset($POST['street']) and !empty($POST['street']))? array(array()):array();
		$data['else_post_street'] = (isset($POST['street']) and !empty($POST['street']))? array():array(array());
		$data['post_street'] = (isset($POST['street']) and !empty($POST['street']))?output($POST['street']):'';
		$data['if_post_phone'] = (isset($POST['phone']) and !empty($POST['phone']))? array(array()):array();
		$data['else_post_phone'] = (isset($POST['phone']) and !empty($POST['phone']))? array():array(array());
		$data['post_phone'] = (isset($POST['phone']) and !empty($POST['phone']))?output($POST['phone']):'';
		$data['url_cart'] = isset($_SESSION['skip_cart']) && intval($_SESSION['skip_cart']) ? $_SESSION['cart_from_url'] : site_url('cart');
        //_view('user/payment_form',$data);
		print_page('user/payment_form.html',$data);
   }

	/**
	 * Validate payment form fields
	 *
	 * @param array $POST
	 * @return array
	 */
   function _check_payment_fields($POST)
   {
        $post=prepare_post();
        $need_billing_info = config_get('system','config','member_force_billing_info_input');

        /* errors  definition */
        $return_array  = array();
        $return_array['is_error'] = intval(0);
        $return_array['errors']['billing_name'] = array(
                                            'text'=>'<{user_payment_form_billing_name_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['street'] = array(
                                            'text'=>'<{user_payment_form_street_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['city'] = array(
                                            'text'=>'<{user_payment_form_city_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['state'] = array(
                                            'text'=>'<{user_payment_form_state_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['zip'] = array(
                                            'text'=>'<{user_payment_form_zip_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['country'] = array(
                                            'text'=>'<{user_payment_form_country_error}>',
                                            'display'=>false
                                            );
        $return_array['errors']['phone'] = array(
                                            'text'=>'<{user_payment_form_phone_error}>',
                                            'display'=>false
                                            );
        /* _errors definition */

        /* check standart fields */
        if ( $need_billing_info!==false and intval($need_billing_info)==1 )
        {
            /* billing_name */
            $billing_name = (isset($post['billing_name']))?$post['billing_name']:'';
            if( !isset($billing_name) or empty($billing_name) or mb_strlen($billing_name)>50)
            {
                $return_array['is_error']=1;
                $return_array['errors']['billing_name']['display'] = true;
            }
            /* billing_name */

            /* street */
            $street = (isset($post['street']))?$post['street']:'';
            if( !isset($street) or empty($street) or mb_strlen($street)>50)
            {
                $return_array['is_error']=1;
                $return_array['errors']['street']['display'] = true;
            }
            /* street */

            /* city */
            $city = (isset($post['city']))?$post['city']:'';
            if( !isset($city) or empty($city) or mb_strlen($city)>50)
            {
                $return_array['is_error']=1;
                $return_array['errors']['city']['display'] = true;
            }
            /* city */

            /* country */
            $country = (isset($post['country']))?$post['country']:'';
            $countries = get_countries();
            if( !isset($country) or empty($country) or !array_key_exists(mb_strtoupper($country),$countries))
            {
                $return_array['is_error']=1;
                $return_array['errors']['country']['display'] = true;
            }
            /* country */

            /* state */
            $state = (isset($post['state']))?$post['state']:'';
            $states = get_states();
            if( !isset($state) or empty($state) or !array_key_exists(mb_strtoupper($state),$states))
            {
                $return_array['is_error']=1;
                $return_array['errors']['state']['display'] = true;
            }
            /* state */

            /* phone */
            $phone = (isset($post['phone']))?$post['phone']:'';
            if( !isset($phone) or empty($phone) or mb_strlen($phone)>20 or eregi("^[0-9A-Za-z -\(\)]{1,20}$",$phone)===false)
            {
                $return_array['is_error']=1;
                $return_array['errors']['phone']['display'] = true;
            }
            /* phone */

            /* zip */
            $zip = (isset($post['zip']))?$post['zip']:'';
            if(eregi("^[0-9A-Za-z -]{1,10}$",$zip)===false)
            {
                $return_array['is_error']=1;
                $return_array['errors']['zip']['display'] = true;
            }
            /* zip */

        }
        /* _check standart fields */

        return $return_array;
   }
}
?>
