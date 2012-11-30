<?php

/**
 * 
 * THIS FILE CONTAINS Mail_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MAIL
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Mail_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Mail_model()
    {
        parent::Model();
    }

    // =========================================================================
    // ===  HERE WE START THE NEW MAIL MODEL FUNCTIONS                       ===
    // ===  NEXT FUNCTIONS ARE DUMMY (stub functions)                        ===
    // ===                                          author: Makarenko Sergey ===
    // =========================================================================

    /**
    * Replace all keys in the text to it's original values
    *
    * @author Drovorubov
    * @param string $text
    * @param array $keys
    * @return string
    */
    function replace_keys($text,$keys)
    {
        if( mb_strlen($text) > 65534 )
        {
            return false;
        }
        //Replace keys to values in text
        $text = preg_replace("/##(.+?)##/ies","set_key(\$keys,'$1')",$text);
        return $text;
    }
    /**
     * Create system email data for all template
     *
     */
    function Create_system_email_data()
    {
        $CI=&get_instance();
        $CI->load->model("lang_manager_model");

        $this->db->delete(db_prefix.'System_emails',array('id>' => 0));
        $this->db->delete(db_prefix.'Language_data',array('object_type' => 2));
        $this->db->delete(db_prefix.'Language_data',array('object_type' => 13));

        $admin_tmpl_names=Get_admin_system_emails_tmpl_names();
        $user_tmpl_names=Get_user_system_emails_tmpl_names();

        foreach($admin_tmpl_names as $name)
        {
            $tpl=$this->get_system_email_data($name);
            $data = array(
            'email_key' => $name,
            'email_type' => 'admin' ,
            'replace_keys' =>  implode(";",$tpl['replace_keys'])
            );
            $this->db->insert(db_prefix.'System_emails', $data);

            $post=array();
            $post['id']=$this->db->insert_id();
            $post['object_type']=2;
            $post['language_id']=-1;
            $post['name']=$tpl['subject'];
            $post['descr']=$tpl['body'];
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=13;
            $post['language_id']=-1;
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=2;
            $post['language_id']=1;
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=13;
            $post['language_id']=1;
            $CI->lang_manager_model->template_set($post);
        }

        foreach($user_tmpl_names as $name)
        {
            $tpl=$this->get_system_email_data($name);
            $data = array(
            'email_key' => $name ,
            'email_type' => 'user' ,
            'replace_keys' => implode(";",$tpl['replace_keys'])
            );
            $this->db->insert(db_prefix.'System_emails', $data);
            $post=array();
            $post['id']=$this->db->insert_id();
            $post['object_type']=2;
            $post['language_id']=-1;
            $post['name']=$tpl['subject'];
            $post['descr']=$tpl['body'];
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=13;
            $post['language_id']=-1;
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=2;
            $post['language_id']=1;
            $CI->lang_manager_model->template_set($post);
            $post['object_type']=13;
            $post['language_id']=1;
            $CI->lang_manager_model->template_set($post);
        }
    }


    /**
     * Get system email data by template key_name
     * Will return info on certain template in structure like
     * array("id","key_name", "name", "subject", "body", "replace_keys", "email_type")
     *
     * @param mixed string/integer $tmpl_name - string(tmpl_name) | "ADMIN_TEMPLATES_NAMES" | "USER_TEMPLATES_NAMES"
     * @param mixed $lang_id
     * @return mixed false/array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_system_email_data($tmpl_name,$lang_id=false)
    {
        $CI =& get_instance();
        if (empty($tmpl_name))
        {
            return false;
        }
        
        if ( $tmpl_name == 'ADMIN_TEMPLATES_NAMES')
        {
            $this->db->select('email_key as `key`');
            $query = $this->db->get_where(db_prefix.'System_emails', array('email_type' => 'admin'));
            $system_email = $query->result_array();
            return array_transform($system_email,false,'key');
        }
        elseif ( $tmpl_name == 'USER_TEMPLATES_NAMES')
        {
            $this->db->select('email_key as `key`');
            $query = $this->db->get_where(db_prefix.'System_emails', array('email_type' => 'user'));
            $system_email = $query->result_array();
            return array_transform($system_email,false,'key');
        }
        
        $system_email_info = array("id"=>0, "key_name"=>$tmpl_name, "name"=>"", "subject"=>"", "body"=>"", "replace_keys"=>Array(), "email_type"=>'user');

        $this->db->select('id, email_key as key_name, email_type, name, replace_keys');
        if(is_numeric($tmpl_name))
        {
            //newsletter
            $query = $this->db->get_where(db_prefix.'System_emails', array('id' => $tmpl_name));
            $tmpl_format = intval(config_get('system','mailer','mailer_in_html'))>0 ? 14 : 8;
            $system_email=$query->result_array();
            if(count($system_email)>0)
            {
                //fb($system_email,'$system_email');
                $CI->load->model("lang_manager_model");
                $system_email=$CI->lang_manager_model->combine_with_language_data($system_email,$tmpl_format,array('name'=>'name','descr'=>'subject','add'=>'body'),'id',false,$lang_id,&$add_params);
                $system_email_info=$system_email[0];
                //fb( $system_email_info,' $system_email_info');
                $system_email_info['replace_keys'] = explode(';', $system_email_info['replace_keys']);
            }
        }
        else
        {
            //sytem_email
            $query = $this->db->get_where(db_prefix.'System_emails', array('email_key' => $tmpl_name));
            $tmpl_format = intval(config_get('system','mailer','mailer_in_html'))>0 ? 13 : 2;
            $system_email=$query->result_array();
            if(count($system_email)>0)
            {
                $CI->load->model("lang_manager_model");
                $system_email=$CI->lang_manager_model->combine_with_language_data($system_email,$tmpl_format,array('name'=>'subject','descr'=>'body'),'id',false,$lang_id,&$add_params);
                $system_email_info=$system_email[0];
                $system_email_info['replace_keys'] = explode(';', $system_email_info['replace_keys']);
            }
        }
        
        //footer for all templates bodies ;)
        //$system_email_info['subject'] .= " (##site_name## system message)";
        //$system_email_info['body'] .= "\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##";
        //print_r($system_email_info);
        return $system_email_info;

/*        if (empty($tmpl_name))
        {
            return false;
        }
        $system_email_info = array("key_name"=>$tmpl_name, "name"=>"", "subject"=>"", "body"=>"", "replace_keys"=>Array(), "email_type"=>'user');

        switch ($tmpl_name)
        {
            //user system emails
            case 'user_profile_change':
                $system_email_info['name']    = "Your member profile was changed";
                $system_email_info['subject'] = "Your member profile was changed";
                $system_email_info['body']    = "Hello ##user_name##,\n we inform you that your profile information was changed.";
                $system_email_info['replace_keys'] = explode(';', '');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_change_password':
                $system_email_info['name']    = "Your password was changed";
                $system_email_info['subject'] = "Your profile was changed";
                $system_email_info['body']    = "Hello ##user_name##,\n your password to member account was changed.\n New password is ##user_new_password##";
                $system_email_info['replace_keys'] = explode(';', 'user_new_password');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_registration_completed':
                $system_email_info['name']    = "Successfully registered";
                $system_email_info['subject'] = "Your account was successfully registered";
                $system_email_info['body']    = "Hello ##user_name##,\n your account was successfully registered.\nHere is your account info to log in:\nlogin: ##user_login##\npassword: ##user_password##\n\nPlease store this information. You may log in to your member account here ##site_base_url##/user/login";
                $system_email_info['replace_keys'] = explode(';', 'user_password');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_account_activation':
                $system_email_info['name']    = "Your account needs activation";
                $system_email_info['subject'] = "Account activation request";
                $system_email_info['body']    = "Hello ##user_name##,\n your member account was successfully registered and needs activation. In order to activate this account please proceed to this link ##user_activation_link##";
                $system_email_info['replace_keys'] = explode(';', 'user_activation_link');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_profile_status_change':
                $system_email_info['name']    = "Your account status was changed";
                $system_email_info['subject'] = "Your account status was changed";
                $system_email_info['body']    = "Hello ##user_name##,\n your member account status was changed to '##user_account_status##'.\nIf you have any questions, please contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', 'user_account_status');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_account_expire':
                $system_email_info['name']    = "Your account is expired";
                $system_email_info['subject'] = "Your account is expired";
                $system_email_info['body']    = "Hello ##user_name##,\n we inform you that your account has expired at ##user_expire_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', '');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_subscription_expired':
                $system_email_info['name']    = "Product subscription is expired";
                $system_email_info['subject'] = "Product subscription is expired";
                $system_email_info['body']    = "Hello ##user_name##,\n we inform you that your subscription on product '##expired_product_name##' is expired at ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', 'expired_product_name;product_expiration_date');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_subscription_almost_expired':
                $system_email_info['name']    = "Product subscription is almost expired";
                $system_email_info['subject'] = "Product subscription is almost expired";
                $system_email_info['body']    = "Hello ##user_name##,\n we inform you that your subscription on product '##expired_product_name##' is almost expired (expiration date is: ##product_expiration_date##).\nIf you have any questions, please contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', 'expired_product_name;product_expiration_date');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_payment_notification':
                $system_email_info['name']    = "Payment notification";
                $system_email_info['subject'] = "Payment notification";
                $system_email_info['body']    = "Hello ##user_name##,\n we have received payment on product '##product_name##' subcsription. The term is extended until ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', 'product_name;product_expiration_date');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_payment_error':
                $system_email_info['name']    = "Payment error notification";
                $system_email_info['subject'] = "Payment error notification";
                $system_email_info['body']    = "Hello ##user_name##,\n we have not received payment on product '##product_name##' because of the payment error (Subscription ID: ##subscription_id## ; Transaction ID: ##transaction_id##).\nPlease contact our administrator ##site_admin_email##";
                $system_email_info['replace_keys'] = explode(';', 'product_name;subscription_id;transaction_id');
                $system_email_info['email_type'] = 'user';
                break;
            case 'user_remind_password':
                $system_email_info['name']    = "Remind password";
                $system_email_info['subject'] = "Remind password";
                $system_email_info['body']    = "Hello ##user_name##,\n somebody has generated this 'remind password' request. If that was you - please, follow this link ##user_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore 'remind password' request.";
                $system_email_info['replace_keys'] = explode(';', 'user_remind_password_link');
                $system_email_info['email_type'] = 'user';
                break;

            //admin personal system emails
            case 'your_admin_account_created':
                $system_email_info['name']    = "Your administrator account created";
                $system_email_info['subject'] = "Your administrator account was successfuly registered";
                $system_email_info['body']    = "Hello ##admin_login##,\n your admin account was successfuly registered.\n Your login:##admin_login##\n Your password:##admin_password##";
                $system_email_info['replace_keys'] = explode(';', 'admin_password');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'your_admin_account_changed':
                $system_email_info['name']    = "Your administrator account changed";
                $system_email_info['subject'] = "Your administrator account was changed";
                $system_email_info['body']    = "Hello ##admin_login##,\n your administrator account was changed.\n Your login:##admin_login##\n Your password:##admin_password##";
                $system_email_info['replace_keys'] = explode(';', 'admin_password');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'your_admin_account_deleted':
                $system_email_info['name']    = "Your administrator account deleted";
                $system_email_info['subject'] = "Your administrator account was deleted";
                $system_email_info['body']    = "Hello ##admin_login##,\n your administrator account was deleted";
                $system_email_info['replace_keys'] = explode(';', '');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'your_admin_remind_password':
                $system_email_info['name']    = "Remind password";
                $system_email_info['subject'] = "Remind password";
                $system_email_info['body']    = "Hello ##admin_login##,\n somebody has generated this 'remind password' request. If that was you - please, follow this link ##admin_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore 'remind password' request.";
                $system_email_info['replace_keys'] = explode(';', 'admin_remind_password_link');
                $system_email_info['email_type'] = 'admin';
                break;

            //system emails addressed to all administrators
            case 'admin_account_created':
                $system_email_info['name']    = "Administrator account created";
                $system_email_info['subject'] = "Administrator account was successfuly registered";
                $system_email_info['body']    = "Hello ##admin_login##,\n we inform you, that new administrator account ##created_admin_login## (with access level:##created_admin_level##) was created by ##current_admin_login##";
                $system_email_info['replace_keys'] = explode(';', 'current_admin_login;created_admin_login;created_admin_level');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_account_changed':
                $system_email_info['name']    = "Administrator account changed";
                $system_email_info['subject'] = "Administrator account was changed";
                $system_email_info['body']    = "Hello ##admin_login##,\n we inform you, that administrator account ##changed_admin_login## (with access level:##changed_admin_level##) was changed by ##current_admin_login##";
                $system_email_info['replace_keys'] = explode(';', 'current_admin_login;changed_admin_login;changed_admin_level');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_account_deleted':
                $system_email_info['name']    = "Administrator account deleted";
                $system_email_info['subject'] = "Administrator account was deleted";
                $system_email_info['body']    = "Hello ##admin_login##,\n we inform you, that administrator account ##deleted_admin_login## (with access level:##deleted_admin_level##) was deleted by ##current_admin_login##";
                $system_email_info['replace_keys'] = explode(';', 'current_admin_login;deleted_admin_login;deleted_admin_level');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_access_level_change':
                $system_email_info['name']    = "Access level changed";
                $system_email_info['subject'] = "Access level was changed";
                $system_email_info['body']    = "Hello ##admin_login##,\n we inform you, that access level ##access_level## was changed by ##current_admin_login##";
                $system_email_info['replace_keys'] = explode(';', 'current_admin_login;access_level');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_payment_notification':
                $system_email_info['name']    = "Payment notification";
                $system_email_info['subject'] = "Payment notification";
                $system_email_info['body']    = "Hello ##admin_login##,\n we have received payment on product '##product_name##' subcsription. The term is extended until ##product_expiration_date##.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.";
                $system_email_info['replace_keys'] = explode(';', 'product_name;subscription_id;transaction_id;amount');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_payment_error':
                $system_email_info['name']    = "Payment error notification";
                $system_email_info['subject'] = "Payment error notification";
                $system_email_info['body']    = "Hello ##admin_login##,\n we have not received payment on product '##product_name##' because of the payment error.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.";
                $system_email_info['replace_keys'] = explode(';', 'product_name;subscription_id;transaction_id;amount');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_subscription_started':
                $system_email_info['name']    = "Product subscription is started";
                $system_email_info['subject'] = "Product subscription is started";
                $system_email_info['body']    = "Hello ##admin_login##,\n subscription of ##user_login## on product '##product_name##' is started.";
                $system_email_info['replace_keys'] = explode(';', 'user_login;product_name');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_subscription_ended':
                $system_email_info['name']    = "Product subscription is ended/expired";
                $system_email_info['subject'] = "Product subscription is ended/expired";
                $system_email_info['body']    = "Hello ##admin_login##,\n subscription of ##user_login## on product '##expired_product_name##' is closed (product expiration date is ##product_expiration_date##).";
                $system_email_info['replace_keys'] = explode(';', 'user_login;expired_product_name;product_expiration_date');
                $system_email_info['email_type'] = 'admin';
                break;
            case 'admin_new_member_registered':
                $system_email_info['name']    = "New member account is registered";
                $system_email_info['subject'] = "New member account is registered";
                $system_email_info['body']    = "Hello ##admin_login##,\n new member account (login: ##user_login##) was successfully registered";
                $system_email_info['replace_keys'] = explode(';', 'user_login');
                $system_email_info['email_type'] = 'admin';
                break;
        } // switch

        //footer for all templates bodies ;)
        $system_email_info['subject'] .= " (##site_name## system message)";
        $system_email_info['body'] .= "\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##";

        if ( $tmpl_name =='ADMIN_TEMPLATES_NAMES')
        {
            //this is temporary fake
            return Array('your_admin_account_created', 'your_admin_account_changed', 'your_admin_account_deleted', 'your_admin_remind_password', 'admin_account_created', 'admin_account_changed', 'admin_account_deleted', 'admin_access_level_change', 'admin_payment_notification', 'admin_payment_error', 'admin_subscription_started', 'admin_subscription_ended', 'admin_new_member_registered');
            //_this is temporary fake
            //here we should select names of templates where 'email_type'=='admin' in future
        }
        elseif ( $tmpl_name =='USER_TEMPLATES_NAMES')
        {
            //this is temporary fake
            return Array('user_profile_change', 'user_change_password', 'user_registration_completed', 'user_account_activation', 'user_profile_status_change', 'user_account_expire', 'user_subscription_expired', 'user_subscription_almost_expired', 'user_payment_notification', 'user_payment_error', 'user_remind_password');
            //_this is temporary fake
            //here we should select names of templates where 'email_type'=='user' in future
        }

        return $system_email_info;*/
    }


    /**
     * Returns all replace keys for a certain email template
     *
     * @param string $tmpl_name
     * @return mixed false/array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_template_replace_keys($tmpl_name)
    {
        if (empty($tmpl_name))
        {
            return false;
        }
        //here we'll store the replace keys
        $template_replace_keys = Array();

        //read the info about the template
        $template_info = $this->Get_system_email_data($tmpl_name);
        $specific_replace_keys = $template_info['replace_keys'];

        //read the dynamic replace keys for user/admin depending on email type
        $dynamic_replace_keys = Array();
        if ($template_info['email_type']=='user')
        {
            $dynamic_replace_keys = $this->Get_dyn_replace_keys('user', 0, true);
        }
        elseif ($template_info['email_type']=='admin')
        {
            $dynamic_replace_keys = $this->Get_dyn_replace_keys('admin', 0, true);
        }

        //get system replace keys for emails
        $system_replace_keys = $this->Get_sys_replace_keys(true);

        //merge all placeholders variables to one array to return
        $template_replace_keys = array_merge($system_replace_keys, $dynamic_replace_keys, $specific_replace_keys);
        return $template_replace_keys;
    }


    /**
     * Returns system replace keys for emails
     * If ($keys_names_only==true) then keys names are returned only
     *
     * @param booleam $keys_names_only
     * @return mixed array/boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_sys_replace_keys($keys_names_only=false)
    {
        $sys_replace_keys = Array();

        $sys_replace_keys['current_time'] = date("H:i:s");
        $sys_replace_keys['current_date'] = nsdate(time(), false);
        $sys_replace_keys['site_name'] = config_get('system','config','site_name');
        $sys_replace_keys['site_base_url'] = config_get('system','config','base_url');
        $sys_replace_keys['site_admin_email'] = config_get('system','mailer','admin_email');

        return ($keys_names_only ? array_keys($sys_replace_keys) : $sys_replace_keys);
    }


    /**
    * Returns language replace keys for emails
    *
    * @param array $arr
    * @param integer $person_id
    * @param mixed $person_type
    * @return array
    *
    * @author onagr
    * @copyright 2008
    */
    function Get_language_replace_keys($arr,$person_id,$person_type='user')
    {
        foreach($arr as $key=>$value)
        {
            if(is_array($value))
            {
                if(isset($value['object_id']) && isset($value['object_type']))
                {
                    $CI =& get_instance();
                    $CI->load->model("lang_manager_model");
                    $data=$CI->lang_manager_model->get_language_data($value['object_type'],$value['object_id'],array('person_id'=>$person_id,'person_type'=>$person_type));
                    $value['column'] = isset($value['column']) ? $value['column'] : 'name';
                    $arr[$key] = $data[$value['object_id']][$value['column']];
                }
                else
                {
                    $arr[$key]='';
                }
            }
        }
        return $arr;
    }


    /**
     * Returns dynamic replace keys for user/admin with ID specified
     * If ($keys_names_only==true) then keys names are returned only
     *
     * @param string $for_whom ("admin" | "user")
     * @param integer $id - admin or user ID
     * @param booleam $keys_names_only
     * @return mixed array/false
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_dyn_replace_keys($for_whom, $id=0, $keys_names_only=false)
    {
        if ($for_whom == "admin")
        {
            return $this->Get_admin_dyn_replace_keys($id, $keys_names_only);
        }
        elseif ($for_whom == "user")
        {
            return $this->Get_user_dyn_replace_keys($id, $keys_names_only);
        }
        return false;
    }


    /**
     * Returns dynamic replace keys for admin with ID specified
     * If ($keys_names_only==true) then keys names are returned only
     *
     * @param integer $admin_id - admin ID
     * @param booleam $keys_names_only
     * @return mixed array/false
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_admin_dyn_replace_keys($admin_id, $keys_names_only=false)
    {
        $admin_id = intval($admin_id);
        if ($admin_id < 0)
        {
            return false;
        }
        elseif ($admin_id == 0)
        {
            $keys_names_only = true;
        }

        //here we will store the admin replace keys found
        //the array is initialized with empty values to work correct with param $keys_names_only==true;
        $admin_dyn_replace_keys = Array("admin_login"=>"", "admin_email"=>"", "admin_access_level"=>"", "admin_last_online"=>"");

        $this->db->select('admin.login as admin_login, admin.email as admin_email, admin.last_online as admin_last_online, access_levels.name as admin_access_level');
        $this->db->from(db_prefix.'Admins admin');
        $this->db->where('admin.id',$admin_id);
        $this->db->join(db_prefix.'Access_levels access_levels','access_levels.id = admin.access_id','LEFT');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $tmp = $query->result_array();
            $admin_dyn_replace_keys = $tmp[0];
        }
        //convert date formats to system format
        $admin_dyn_replace_keys['admin_last_online'] = nsdate($admin_dyn_replace_keys['admin_last_online'], true);

        return ($keys_names_only ? array_keys($admin_dyn_replace_keys) : $admin_dyn_replace_keys);
    }


    /**
     * Returns dynamic replace keys for user with ID specified
     * If ($keys_names_only==true) then keys names are returned only
     *
     * @param integer $uid - user ID
     * @param booleam $keys_names_only
     * @return mixed array/false
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_user_dyn_replace_keys($uid, $keys_names_only=false)
    {
        $uid = intval($uid);
        if ($uid < 0)
        {
            return false;
        }
        elseif ($uid == 0)
        {
            $keys_names_only = true;
        }
        //here we will store the user replace keys found
        //the array is initialized with empty values to work correct with param $keys_names_only==true;
        $user_dyn_replace_keys = Array("user_login"=>"", "user_email"=>"", "user_name"=>"", "user_last_name"=>"", "user_last_online"=>"", "user_expire_date"=>"");

        $this->db->select('user.login as user_login, user.email as user_email, user.name as user_name, user.last_name as user_last_name, user.last_online as user_last_online, account_status.expire as user_expire_date');
        $this->db->from(array(db_prefix.'Users user',db_prefix.'Account_status account_status'));
        $this->db->where('user.id',$uid);
        $this->db->where('account_status.user_id=user.id');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $tmp = $query->result_array();
            $user_dyn_replace_keys = $tmp[0];
        }
        //convert date formats to system format
        $user_dyn_replace_keys['user_last_online'] = nsdate($user_dyn_replace_keys['user_last_online'], true);
        $user_dyn_replace_keys['user_expire_date'] = nsdate($user_dyn_replace_keys['user_expire_date'], false);

        //get additional fields
        //===============================
        // TO BE CONTINUED... (����� ����� ����� ��������� ��� ���������� ���� ���������������, � ������ �����������)
        //===============================
        //_get additional fields

        return ($keys_names_only ? array_keys($user_dyn_replace_keys) : $user_dyn_replace_keys);
    }


    /**
     * Sends system email to user with ID specified
     * Third param with $replace_values for example Array('key1'=>'val1', 'key2'=>'val2', 'key3'=>Array('object_id'=>254 && 'object_type'=>4))
     *
     * @param integer $recipient_id
     * @param string $tmpl_name
     * @param array $replace_values
     * @return boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Send_system_email_to_user($recipient_id, $tmpl_name, $replace_values=false)
    {
        $CI=&get_instance();
        if (empty($tmpl_name) || $recipient_id<0)
        {
            return false;
        }
        if ($replace_values===false || !is_array($replace_values))
        {
            $replace_values = Array();
        }

        //get recipient email address
        $query = $this->db->get_where(db_prefix.'Users', array('id' => $recipient_id));
        if ($query->num_rows() <= 0)
        {
            //if no recipent email addresses found - exit function
            return false;
        }
        $row = $query->row_array();
        $to = $row['email'];    //user email address
        $CI->load->model('auth_model'); //for some unusual situations, when auth_model is not loaded
        $lang_id=intval($row['language_id'])>0 ? intval($row['language_id']) : $CI->auth_model->get_default_language();

        //the email template info will be stored here
        $email_template_info = $this->Get_system_email_data($tmpl_name,$lang_id);

        //keys with values to replace placeholders in email's subject and body
        $keys_to_replace = Array();
        $sys_replace_values = $this->Get_sys_replace_keys();
        $dyn_replace_values = $this->Get_dyn_replace_keys("user", $recipient_id);
        $spec_replace_values = $this->Get_language_replace_keys($replace_values,$recipient_id,'user');
        //merge all replace keys to a common array $keys_to_replace
        $keys_to_replace = array_merge($sys_replace_values, $dyn_replace_values, $spec_replace_values);

        return $this->push_email_to_queue($email_template_info['id'], $to, $recipient_id, 'user', 'system', $keys_to_replace, $email_template_info['subject'], $email_template_info['body']);

    }


    /**
     * Sends system email to admin with ID specified
     * Third param with $replace_values for example Array('key1'=>'val1', 'key2'=>'val2', 'key3'=>Array('object_id'=>254 && 'object_type'=>4))
     *
     * @param integer $recipient_id
     * @param string $tmpl_name
     * @param array $replace_values
     * @return boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Send_system_email_to_admin($recipient_id, $tmpl_name, $replace_values=false)
    {
        $CI=&get_instance();
        if (empty($tmpl_name) || $recipient_id<0)
        {
            return false;
        }
        if ($replace_values===false || !is_array($replace_values))
        {
            $replace_values = Array();
        }

        //get recipient email address
        $query = $this->db->get_where(db_prefix.'Admins', array('id' => $recipient_id));
        if ($query->num_rows() <= 0)
        {
            //if no recipent email addresses found - exit function
            return false;
        }
        $row = $query->row_array();
        $to = $row['email'];    //admin email address
        $lang_id=intval($row['language_id'])>0 ? intval($row['language_id']) : $CI->auth_model->get_default_language();
        //the email template info will be stored here
        $email_template_info = $this->Get_system_email_data($tmpl_name,$lang_id);

        //keys with values to replace placeholders in email's subject and body
        $keys_to_replace = Array();
        $sys_replace_values = $this->Get_sys_replace_keys();
        $dyn_replace_values = $this->Get_dyn_replace_keys("admin", $recipient_id);
        $spec_replace_values = $this->Get_language_replace_keys($replace_values,$recipient_id,'admin');

        //current_admin_login should be added to special replace keys if exists (such a little usability hack :) )
        $CI = &get_instance();
        if(isset($CI->admin_auth_model))
        {
            $query = $this->db->get_where(db_prefix.'Admins', array('id' => $CI->admin_auth_model->admin_id));
            //should be added to special replace keys if exists
            if ($query->num_rows() > 0)
            {
                $row = $query->row_array();
                $spec_replace_values['current_admin_login'] = $row['login']; //current_admin_login
            }
        }
        //_current_admin_login special replace keys

        //merge all replace keys to a common array $keys_to_replace
        $keys_to_replace = array_merge($sys_replace_values, $dyn_replace_values, $spec_replace_values);

        return $this->push_email_to_queue($email_template_info['id'], $to, $recipient_id, 'admin', 'system', $keys_to_replace, $email_template_info['subject'], $email_template_info['body']);

    }

    /**
     * Sends system email to admin with specified subscription
     *
     * @param string $tpl_name
     * @param array $replace_values =false
     * @param array $exclude_id =false
     * @return mixed boolean/array
     *
     * @author onagr
     * @copyright 2008
     */
    function Send_system_subscription_to_admins($tpl_name,$replace_values=false,$exclude_id=false)
    {
        $tmpl_bits=Get_admin_system_emails_tmpl_bits();
        if(isset($tmpl_bits[$tpl_name]))
        {
            $ML=$tmpl_bits[$tpl_name];
            $this->db->select('admins.id');
            $this->db->from(db_prefix.'Admins admins');
            $this->db->where('access_levels.ML&'.$ML,$ML);
            if($exclude_id!==false)
            {
                $this->db->where('admins.id!=',$exclude_id);
            }
            $this->db->join(db_prefix.'Access_levels access_levels','admins.access_id=access_levels.id','left');
            $this->db->distinct();
            $query = $this->db->get();
            $admins=$query->result_array();
            if(count($admins)==0){return false;}
            $result=array();
            foreach($admins as $value)
            {
                $result[$value['id']]=$this->send_system_email_to_admin($value['id'], $tpl_name, $replace_values);
            }
            return in_array(false,$result) ? (in_array(true,$result) ? $result : false) : true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Pushes email to email queue
     *
     * @param string $email_tpl_id
     * @param string $email
     * @param integer $user_id
     * @param string $user_type
     * @param string $priority
     * @param array $replace_values
     * @param string $tpl_subject
     * @param string $tpl_text
     * @return boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Push_email_to_queue($email_tpl_id, $email, $user_id, $user_type, $priority, $replace_values, $tpl_subject, $tpl_text)
    {
        if ( empty($email) ||
            !is_numeric($user_id) || !in_array($user_type, Array('user','admin')) ||
            !in_array($priority, Array('system','newsletter'))
            )
        {
            return false;
        }
        if (!is_array($replace_values))
        {
            $replace_values = Array();
        }
        $data = Array(
            'email_tpl_id' => $email_tpl_id,
            'email' => $email,
            'user_id' => $user_id,
            'user_type' => $user_type,
            'priority' => $priority,
            'replace_values' => serialize($replace_values),
            'tpl_subject' => $tpl_subject,
            'tpl_text' => $tpl_text,
            );
        $this->db->insert(db_prefix.'Email_queue', $data);
        return true;
    }


    /**
     * Pop email from email queue.
     * Gets first email from queue and sends it through $this->Send_email function.
     * If email was successfully send, it is pushed to `Email_history` table.
     *
     * @return boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Pop_email_from_queue()
    {
        $this->db->select('*');
        $this->db->from(db_prefix.'Email_queue');
        $this->db->order_by('priority ASC, date ASC');
        //***********Functionality limitations***********
        if(Functionality_enabled('action_newsletter_send')!==true)
        {
            $this->db->where('priority !=','newsletter');
        }
        //*******End of functionality limitations********        
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() <= 0)
        {
            //if no emails - exit function
            return false;
        }
        $email_info = $query->row_array();

        $replace_values = unserialize($email_info['replace_values']);
        //replace all placeholders in email's subject and body to according values
        $subject = $this->replace_keys($email_info['tpl_subject'], $replace_values);
        $body = $this->replace_keys($email_info['tpl_text'], $replace_values);
        $to = $email_info['email'];         //recipient email address

        //phisicaly sent email to recipient
        $is_send = $this->Send_email($to, $subject, $body);
        //successfully send?
        if ($is_send)
        {
            $queue_item_id = $email_info['id'];
            unset($email_info['id']);
            //copy this row to the Email_history
            $this->db->insert(db_prefix.'Email_history', $email_info);
            //remove email from queue
            $this->db->delete(db_prefix.'Email_queue', array('id' => $queue_item_id));
            return true;
        }
        //email was not successfully send
        return false;
    }


    /**
     * Initialize CodeIgniter SMTP mailer
     *
     * @param array $mail_params
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function _Initialize_CI_mailer($mail_params)
    {
        if( !is_array($mail_params) || count($mail_params) < 1 )
        {
            return false;
        }
        $this->email->clear();
        $config['charset'] = $mail_params['mailer_charset'];
        if( $mail_params['mailer_in_html'] == 1 )
        {
            $config['mailtype'] = 'html';
        }
        if( $mail_params['mailer_use_smtp'] == 1 )
        {
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = $mail_params['mailer_smtp_host'];
            $config['smtp_port'] = $mail_params['mailer_smtp_port'];
            if( $mail_params['mailer_use_auth'] == 1 )
            {
                $config['smtp_user'] = $mail_params['mailer_smtp_user'];
                $config['smtp_pass'] = $mail_params['mailer_smtp_pass'];
            }
        }
        else
        {
            $config['protocol'] = 'sendmail';
        }
        $this->email->initialize($config);
        if( $mail_params['mailer_use_smtp'] == 1 )
        {
            $this->email->set_newline("\r\n");  
        }         
        return true;
    }


    /**
     * Sends email using CI email library
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return bool
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Send_email($to, $subject='', $body='')
    {
        $this->load->library('email');
        //Gets Mailer params from Config file
        $CI = &get_instance();
        $CI->load->model('config_model');
        $mail_params = $CI->config_model->mailer_get(array());
        //initialize CodeIgniter SMTP mailer
        $this->_Initialize_CI_mailer($mail_params);

        //set email message params
        $this->email->to($to);
        $this->email->from($mail_params['admin_email']);
        //Set email subject and email body
        $this->email->subject($subject);
        $this->email->message($body);
        //Send email
        if ( !$this->email->send() )
        {
            return false;
        }
        return true;
    }
    /**
     * Send email
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param mixed $attach
     * @return boolean
     */
    function Send_email_ex($from, $to, $subject='', $body='',$attach=false)
    {
        $this->load->library('email');
        //Gets Mailer params from Config file
        $CI = &get_instance();
        $CI->load->model('config_model');
        $mail_params = $CI->config_model->mailer_get(array());
        $mail_params['mailer_in_html']=1;
        //initialize CodeIgniter SMTP mailer
        $this->_Initialize_CI_mailer($mail_params);

        //set email message params
        $this->email->to($to);
        $this->email->from($from);
        //Set email subject and email body
        $this->email->subject($subject);
        $this->email->message($body);
        if($attach!=false)
        {
            $this->email->attach($attach);
        }
        //Send email
        if ( !$this->email->send() )
        {
            return false;
        }
        return true;
    }


    // =========================================================================
    // ===  HERE WE END THE NEW MAIL MODEL FUNCTIONS                         ===
    // ===  PREVIOUS FUNCTIONS ARE DUMMY (stub functions)                    ===
    // =========================================================================

}
?>
