<?php
/**
* 
* THIS FILE CONTAINS Config_model CLASS
*  
* @package Needsecure
* @author uknown
* @version uknown
*/

/**
* 
* THIS CLASS CONTAINS METHODS FOR WORK WITH CONFIGURATION
* 
* @package Needsecure
* @author uknown
* @version uknown
*/
class Config_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Config_model()
    {
        parent::Model();
    }
    
    /**
    * Get member page params
    *
    * @author onagr
    * @param string $page
    * @return array
    */
    function Member_page_get($page,$preset=false)
    {
        $fields=$preset ? _config_get('member_pages_presets', $page, $preset) : _config_get('member_pages', $page);
        $f=array();
        $max=0;
        if(!is_array($fields) || !count($fields))
        {
            return false;
        }
        foreach($fields as $id=>$field)
        {
            $max=$field['order']>$max ? $field['order'] : $max;
            $f[$field['order']] = !isset($f[$field['order']]) ? array() : $f[$field['order']];
            $f[$field['order']][]=$id;
        }
        $result=array();
        for($i=0;$i<=$max;$i++)
        {
            if(isset($f[$i]))
            {
                foreach($f[$i] as $id)
                {
                    $result[$id]=$fields[$id];
                }
            }        
        }
        return $result;
    }
    
    /**
    * Set member page params
    *
    * @author onagr
    * @param string $page
    * @param array $post
    * @return array
    */
    function Member_page_set($page,$post)
    {
        $fields=_config_get('member_pages', $page);
        $modified=false;
        $errors=array();
        foreach($fields as $id=>$field)
        {
            if(isset($post['option_'.$id]))
            {
                $arr=array();
                parse_str($post['option_'.$id],$arr);
                fb($arr);
                fb('admin_member_pages_'.$page.'_'.$id.'_disabling');
                if(isset($arr['enabled']) && !intval($arr['enabled']) && ($e=Functionality_enabled('admin_config_constructor_'.$page.'_'.$id.'_disabling'))!==true)
                {
                    $errors[$id.'_disabling']=lvar($e,array('field'=>'<{user_'.$page.'_field_'.$id.'_title}>'));
                }
                foreach($arr as $k=>$v)
                {
                    if(preg_match('/^(length|size)_([a-zA-Z_]+)$/', $k, $matches))
                    {
                        if(isset($matches[1]) && isset($matches[2]))
                        {                            
                            if(isset($fields[$id][$matches[1]]) && is_array($fields[$id][$matches[1]]) &&
                                    isset($fields[$id][$matches[1]][$matches[2]]) && $fields[$id][$matches[1]][$matches[2]]!=$v)
                            {
                                if($k!='enabled' || !isset($fields[$id]['obligate']))
                                {                        
                                    $modified=true;
                                    $fields[$id][$matches[1]][$matches[2]]=$v;
                                }                        
                            }                            
                        }                        
                    }                    
                    
                    if(isset($fields[$id][$k]) && $fields[$id][$k]!=$v)
                    {
                        if($k!='enabled' || !isset($fields[$id]['obligate']))
                        {                        
                            $modified=true;
                            $fields[$id][$k]=$v;
                        }                        
                    }
                }               
            }   
        }
        if(count($errors))
        {
            return $errors;
        }
        if($modified)
        {
            return (_config_set($fields,'member_pages', $page)) ? true : array('not_saved'=>'');            
        }
        else
        {
            return array('not_changed'=>'');
        }
    }
    
    /**
    * Set member page params
    *
    * @author onagr
    * @param string $page
    * @param array $post
    * @return array
    */
    function Member_page_order($page,$post)
    {
        if(isset($post['id']))
        {
            $id=$post['id'];
            $fields=$this->member_page_get($page);
            if(array_key_exists($id,$fields))
            {
                $sibl=false;
                $temp=false;
                $i=0;
                foreach($fields as $k=>$v)
                {
                    if($post['action']=='up')
                    {
                        $sibl = ($k==$id) ? $temp : $sibl;                    
                    }
                    else
                    {
                        $sibl = ($temp==$id) ? $k : $sibl;
                    }
                    $temp=$k;
                    $fields[$k]['order']=$i;
                    $i++;
                }
                if($sibl)
                {
                    $temp=$fields[$sibl]['order'];
                    $fields[$sibl]['order']=$fields[$id]['order'];
                    $fields[$id]['order']=$temp;
                    return (_config_set($fields,'member_pages', $page)) ? true : 'not_saved';
                }
                return true;
            }
        }
        return 'undefined_id';
    }
    
    /**
    * Set member page params
    *
    * @author onagr
    * @param string $page
    * @param array $post
    * @return array
    */
    function Member_page_presets_list($page)
    {
        $presets=_config_get('member_pages_presets', $page);
        $presets=is_array($presets) ? $presets : array();
        return array_keys($presets);
    }

    /**************************************************************
    *    get_config_xml_path
    *    @author onagr
    ***************************************************************/
    /**
    * Enter description here...
    *
    * @author onagr
    * @param string $sect
    * @return array
    */
    function get_config_xml_path($sect)
    {
        $path=array();
        switch ($sect) {
        case "global_setup":
            $path['site_name']=array("system","config");
            $path['base_url']=array("system","config");
            $path['absolute_path']=array("system","config");
            $path['logout_redirect']=array("system","config");
            $path['login_redirect']=array("system","config");
            $path['personal_login_redirect_flag']=array("system","config");
            $path['login_page']=array("system","config");
            $path['perpage_list'] = array("pager");
            $path['default_perpage'] =array("pager");
            $path['site_ip']=array("system","config");
            $path['date_format']=array("system","config");            
            //$path['member_force_pwd_gen']=array("system","config");
            $path['log_members']=array("system","config");
            $path['log_admins']=array("system","config");
            $path['history_kept']=array("system","config");
            $path['ignored_extensions']=array("system","config");
            break;
        case "security_settings":
            $path['login_remember_me']        = array("USER","SECURITY");
            $path['login_try_capcha']         = array("USER","SECURITY");
            $path['login_try_block_ip']       = array("USER","SECURITY");
            $path['ip_block_timeout']         = array("USER","SECURITY");
            $path['ip_block_selected_period'] = array("USER","SECURITY");                                           // Added by Konstantin X @ 12:08 04.08.2008
            $path['login_block_message']      = array("USER","SECURITY");
            $path['autoban_count']            = array("USER","SECURITY");
            $path['autoban_timeout']          = array("USER","SECURITY");

            $path['session_expiration']       = array("SYSTEM","CONFIG");                                           // Transfered form MEMBER_SETTINGS by Konstantin X @ 11:08 05.08.2008

            $path['min_length']               = array("SYSTEM","CAPCHA");
            $path['max_length']               = array("SYSTEM","CAPCHA");
            break;
        }
        return $path;
    }

    //***************************Payments***********************************
    /**
    * Defines payment system
    *
    * @param array $data
    * @return array
    */
    function Payment_system($data)
    {
        $data['payments']=config_get('PAYMENT');
        return $data;
    }
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function Payment_system_activate($post)
    {
        $result=array();
        $result['id']='';
        $result['active']='';
        $result['error']='false';

        if(isset($post['id']))
        {
            $result['id']=$post['id'];
        }
        else
        {
            $result['error']='1';
        }

        $data['payments']=config_get('PAYMENT');
        if(isset($data['payments'][$post['id']]) && !isset($post['error_text']))
        {
            if($data['payments'][$post['id']]['active']=='1')
            {                
                $data['payments'][$post['id']]['active']='0';
                $result['active']='false';                
            }
            else
            {
                $accepted_currency=isset($data['payments'][$post['id']]['accepted_currency'])?$data['payments'][$post['id']]['accepted_currency']:array();
                if(is_array($accepted_currency) && in_array(config_get('system','config','currency_code'),$accepted_currency))
                {
                    $data['payments'][$post['id']]['active']='1';
                    $result['active']='true';
                }
                else
                {
                    $result['error_text']='unaccepted_currency';
                    $result['active']='false';
                }
            }
            config_set($data['payments'],'PAYMENT');
        }
        else
        {
            $result['error']='2';
            if(isset($post['error_text']))
            {
                $result['error_text']=$post['error_text'];
            }
        }
        return $result;
    }
    //*************************EndOfPayments********************************
    /**
    * Change currency
    *
    * @param array $post
    * @return array
    */
    function Currency_change($post)
    {
        if(isset($post['action']) && $post['action']=='currency_change')
        {
            $result=array();
            $result['action']='currency_change';
            $result['error']='false'; 
            
            $currency_list=config_get('system','config','currency_list');
            if(is_array($currency_list) && in_array($post['currency_code'],$currency_list))
            {
                
                config_set($post['currency_code'],'system','config','currency_code');
                $disabled=array();
                $payment_systems=config_get('PAYMENT');
                fb($payment_systems,"payment_systems");
                foreach($payment_systems as $key=>$sys)
                {
                    $sys['accepted_currency']=isset($sys['accepted_currency'])?$sys['accepted_currency']:array();
                    if(!is_array($sys['accepted_currency']) || !in_array($post['currency_code'],$sys['accepted_currency']))
                    {
                        $disabled[$key]=$sys['name'];
                        config_set(0,'PAYMENT',$key,'active');
                    }                    
                }
                $result['message_text']='<{admin_payment_system_msg_currency_changed}>';
                if(count($disabled))
                {
                    $result['message_text'].='<br/><{admin_payment_system_msg_not_compatible}> ('.implode(", ",$disabled).')';
                    $result['disabled']=$disabled;
                }
                $result['message_text']=replace_lang($result['message_text']);
                
            }
            else
            {
                $result['error']='true';
                $result['error_text']='undefined_currency';
            }
            
        }
        else
        {
            $result['error']='true';
            $result['error_text']='undefined_action';
        }
        return $result;
    }

    /**
    * reads all config needed for "mailer settings", adds them to the $data array
    *
    * @param array $data
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function mailer_get($data)
    {
        if(!isset($data)||!is_array($data))
        {
            $data=array();
        }
        $data = config_get('SYSTEM','MAILER');
        return $data;
    }


    /**
    * saves $data array to <MAILER> section in ht_sys_config.cfg
    * parameter is - Array(admin_email, mailer_charset, mailer_in_html, mailer_use_smtp, mailer_smtp_host, mailer_smtp_port, mailer_use_auth, mailer_smtp_user, mailer_smtp_pass)
    *
    * @param array $data
    * @return boolean
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Mailer_set($data)
    {
        if(!isset($data)||!is_array($data))
        {
            return false;
        }
        //set variables in ht_sys_config.cfg
        config_set($data['admin_email'], 'SYSTEM', 'MAILER', 'admin_email');
        //config_set($data['mailer_charset'], 'SYSTEM', 'MAILER', 'mailer_charset');
        config_set(($data['mailer_in_html']=='true')?'1':'0', 'SYSTEM', 'MAILER', 'mailer_in_html');
        config_set(($data['mailer_use_smtp']=='true')?'1':'0', 'SYSTEM', 'MAILER', 'mailer_use_smtp');
        config_set($data['mailer_smtp_host'], 'SYSTEM', 'MAILER', 'mailer_smtp_host');
        config_set(intval($data['mailer_smtp_port']), 'SYSTEM', 'MAILER', 'mailer_smtp_port');
        config_set(($data['mailer_use_auth']=='true')?'1':'0', 'SYSTEM', 'MAILER', 'mailer_use_auth');
        config_set($data['mailer_smtp_user'], 'SYSTEM', 'MAILER', 'mailer_smtp_user');
        config_set($data['mailer_smtp_pass'], 'SYSTEM', 'MAILER', 'mailer_smtp_pass');
        config_set($data['send_to_count'], 'SYSTEM', 'MAILER', 'send_to_count');
        return true;
    }

    /**
    * reads all config needed for "design manager", adds them to the $data array
    *
    * @param array $data
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function design_settings_get($data)
    {
        if(!isset($data)||!is_array($data))
        {
            $data=array();
        }
        
        design_check();
        
        $data = config_get('DESIGN');
        return $data;
    }

    /**
    * saves all config needed for "design settings"
    *
    * @param array $data
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function design_settings_set($data)
    {
        config_set(intval($data['active_unreg_design']), 'DESIGN', 'active_unreg_design');
        config_set(intval($data['active_reg_design']), 'DESIGN', 'active_reg_design');
        return true;
    }


    /**
    * reads all config needed for member settings
    *
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function member_get()
    {
        //get variables from ht_sys_config.cfg
        $data = config_get('SYSTEM','CONFIG');
        //read the data email domains data from DB
        $data['trusted_emails'] = $this->email_domains_get(1);
        $data['denied_emails'] = $this->email_domains_get(2);
        return $data;
    }


    /**
    * saves all congif needed for member settings
    *
    * @param array $data
    * @return bool
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function member_set($data)
    {
        //set variables in ht_sys_config.cfg
        config_set(($data['member_allow_register']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_allow_register');
        config_set(($data['member_need_activation']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_need_activation');
        config_set(($data['member_approve_needed']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_approve_needed');
        config_set(($data['member_force_billing_info_input']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_force_billing_info_input');
        if(isset($data['member_simple_menu']))
        {
            config_set(($data['member_simple_menu']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_simple_menu');
        }
        config_set(($data['member_autosubscribe_free_products']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_autosubscribe_free_products');
        config_set(($data['member_email_as_login']=='true')?'1':'0', 'SYSTEM', 'CONFIG', 'member_email_as_login');
        config_set((intval($data['member_exp_subscr_notif_period'])>0) ? intval($data['member_exp_subscr_notif_period']):'0', 'SYSTEM', 'CONFIG', 'member_exp_subscr_notif_period');

        //delete previous info about Trusted and Denied email domains from DB
        $this->db->where('status', 1);
        $this->db->or_where('status', 2);
        $this->db->delete(db_prefix.'Email_domains');

        //insert new trusted domain emails
        if(trim($data['trusted_emails'])!='')
        {
            $trusted_emails = explode(",",$data['trusted_emails']);
            foreach($trusted_emails as $value)
            {
                $this->db->set('domain', $value);
                $this->db->set('status', 1);
                $this->db->insert(db_prefix.'Email_domains');
            }
        }

        //insert new trusted domain emails
        if(trim($data['denied_emails'])!='')
        {
            $denied_emails = explode(",",$data['denied_emails']);
            foreach($denied_emails as $value)
            {
                $this->db->set('domain', $value);
                $this->db->set('status', 2);
                $this->db->insert(db_prefix.'Email_domains');
            }
        }
    }


    /**
    * reads all additional vars and adds them to the $data array
    *
    * @param array $data
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function member_settings_vars_add($data)
    {
        if(!isset($data)||!is_array($data))
        {
            $data=array();
        }

        //create temp vars to pass PHP array into javascript array
        $temp_vars_set = array();
        $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/config/member_settings.js";
        $data['temp_vars_set'] = $temp_vars_set;

        //create message array at the html page
        $messages = array();
        $messages['saved_ok'] = "<{admin_member_settings_saved_successfully}>";
        $data['messages'] = $messages;

        //create error messages array at the html page
        $mess_err = array();
        $mess_err['validation_fail'] = "<{admin_member_settings_error}>";
        $mess_err['trust_text'] = "<{admin_msg_er_0202}>";
        $mess_err['denied_text'] = "<{admin_msg_er_0202}>";
        $mess_err['member_exp_subscr_notif_period'] = "<{admin_member_settings_msg_er_exp_subscr}>";
        $mess_err['emails_intersect'] = "<{admin_member_settings_msg_er_emails_intersect}>";
        
        $data['mess_err'] = $mess_err;

        return $data;
    }


    /**
    * Validates all data from $_POST passed to the Member_settings_save controller
    *
    * @param array $data
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function member_validate($data)
    {
        $errors = Array();
        //validate checkboxes
        if(!isset($data['member_allow_register']) || (mb_strtolower($data['member_allow_register'])!="true" && mb_strtolower($data['member_allow_register'])!="false"))
        {$errors[] = "member_allow_register";}
        if(!isset($data['member_need_activation']) || (mb_strtolower($data['member_need_activation'])!="true" && mb_strtolower($data['member_need_activation'])!="false"))
        {$errors[] = "member_need_activation";}
        if(!isset($data['member_approve_needed']) || (mb_strtolower($data['member_approve_needed'])!="true" && mb_strtolower($data['member_approve_needed'])!="false"))
        {$errors[] = "member_approve_needed";}
        if(!isset($data['member_force_billing_info_input']) || (mb_strtolower($data['member_force_billing_info_input'])!="true" && mb_strtolower($data['member_force_billing_info_input'])!="false"))
        {$errors[] = "member_force_billing_info_input";}
        if(!isset($data['member_autosubscribe_free_products']) || (mb_strtolower($data['member_autosubscribe_free_products'])!="true" && mb_strtolower($data['member_autosubscribe_free_products'])!="false"))
        {$errors[] = "member_autosubscribe_free_products";}
        if(!isset($data['member_exp_subscr_notif_period']) || mb_strlen($data['member_exp_subscr_notif_period'])>3 || eregi("^[0-9]{0,3}$", $data['member_exp_subscr_notif_period'])===false)
        {$errors[] = "member_exp_subscr_notif_period";}
        //validate trusted domain emails
        $trusted_emails = trim($data['trusted_emails']);
        if(!empty($trusted_emails))
        {
            $trusted_emails = explode(",",$trusted_emails);
            foreach($trusted_emails as $value)
            {
                if(!isset($value) || eregi("^([a-zA-Z0-9][a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,6}$", $value)===false)
                {
                    $errors[] = "trust_text";
                    break;
                }
            }
        }
        //validate denied domain emails
        $denied_emails = trim($data['denied_emails']);
        if(!empty($denied_emails))
        {
            $denied_emails = explode(",",$denied_emails);
            foreach($denied_emails as $value)
            {
                if(!isset($value) || eregi("^([a-zA-Z0-9][a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,6}$", $value)===false)
                {
                    $errors[] = "denied_text";
                    break;
                }
            }
        }
        
        if(!empty($trusted_emails) && !empty($denied_emails) && count(array_intersect($trusted_emails,$denied_emails))>0)
        {
            $errors[] = "emails_intersect";
        }
        

        return $errors;
    }


    /**
    * reads all trusted and denied email domains from DB
    *
    * next 'Status' are used in 'Email_domains' table:
    * 0 – normal
    * 1 – trusted
    * 2 – denied
    *
    * @param integer $status
    * @return array
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function email_domains_get($status = false)
    {
        // if $status == false - then we will SELECT all table rows
        if ($status)
        {
            $query = $this->db->get_where(db_prefix.'Email_domains', array('status' => $status));
        }
        else
        {
            $query = $this->db->get(db_prefix.'Email_domains');
        }
        if($query->num_rows() <= 0)
        {
            return false;
        }
        return $query->result_array();
    }

    /**************************************************************
    *    config_validate_ex
    *    @author onagr
    ***************************************************************/
    /**
    * Validate config data in the post 
    *
    * @author onagr
    * @param array $post
    * @param string $section
    * @return array
    */
    function config_validate_ex($post,$section)
    {
        $errors=array();
        switch ($section)
        {
        case "design_settings":
            //**************************design_settings*******************************
            if(!isset($post['active_unreg_design']) || eregi("^[0-9]+$", $post['active_unreg_design'])===false)
            {$errors[] = "active_unreg_design";}
            if(!isset($post['active_reg_design']) || eregi("^[0-9]+$", $post['active_reg_design'])===false)
            {$errors[] = "active_reg_design";}
            //***********************end_of_design_settings***************************
            break;
        case "mailer_settings":
            //**************************mailer_settings*******************************
            if(!isset($post['admin_email']) || eregi("^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$", $post['admin_email'])===false)
            {$errors[] = "admin_email";}
            if(!isset($post['mailer_charset']))
            {$errors[] = "mailer_charset";}
            if(!isset($post['mailer_in_html']) || (mb_strtolower($post['mailer_in_html'])!="true" && mb_strtolower($post['mailer_in_html'])!="false"))
            {$errors[] = "mailer_in_html";}
            if(!isset($post['mailer_use_smtp']) || (mb_strtolower($post['mailer_use_smtp'])!="true" && mb_strtolower($post['mailer_use_smtp'])!="false"))
            {$errors[] = "mailer_use_smtp";}
            if(!isset($post['mailer_smtp_host']) || (!check_url($post['mailer_smtp_host']) && eregi("^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$", $post['mailer_smtp_host'])===false))
            {$errors[] = "mailer_smtp_host";}
            if(!isset($post['mailer_smtp_port']) || eregi("^[0-9]+$", $post['mailer_smtp_port'])===false || intval($post['mailer_smtp_port'])<0 || intval($post['mailer_smtp_port'])>65535)
            {$errors[] = "mailer_smtp_port";}
            if(!isset($post['mailer_use_auth']) || (mb_strtolower($post['mailer_use_auth'])!="true" && mb_strtolower($post['mailer_use_auth'])!="false"))
            {$errors[] = "mailer_use_auth";}
            if(!isset($post['mailer_smtp_user']) || mb_strlen($post['mailer_smtp_user'])>254)
            {$errors[] = "mailer_smtp_user";}
            if(!isset($post['mailer_smtp_pass']) || mb_strlen($post['mailer_smtp_pass'])>254)
            {$errors[] = "mailer_smtp_pass";}
            if(!isset($post['send_to_count']) || eregi("^[0-9]+$", $post['send_to_count'])===false || intval($post['send_to_count'])<1 || intval($post['send_to_count'])>65535)
            {$errors[] = "send_to_count";}//***********************end_of_mailer_settings***************************
            break;
        case "host_settings":
            break;
        case "domain_settings":   
         if(!isset($post['service_username']) || eregi("^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$", $post['service_username'])===false)  {$errors[]="service_username";} 
         if(!isset($post['service_password'])||(!empty($post['service_password']) && (!check_lenght($post['service_password'],8,15))))
            {$errors[]="service_password";}
            if(!isset($post['service_parentid']) || eregi("^[0-9]+$", $post['service_parentid'])===false || intval($post['service_parentid'])<1 || intval($post['service_parentid'])>655350000000)
             {$errors[]="service_parentid";}
            break;            
        case "ban_ip":
            //**************************ban_ip*******************************
            if( !preg_match('/^(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/',$post['ip']) && !preg_match('/^(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)-(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/',$post['ip']) ){$errors[]="ip";}
            if(!isset($post['reason'])||mb_strlen($post['reason'])>254)
            {$errors[]="reason";}
            //***********************end_of_ban_ip***************************
            break;
        case "sys_emails_template":
            //**************************sys_emails*******************************
            if(!isset($post['subject'])||mb_strlen($post['subject'])==0||mb_strlen($post['subject'])>100)
            {$errors[]="subject";}
            if(!isset($post['message'])||mb_strlen($post['message'])==0||mb_strlen($post['message'])>5000)
            {$errors[]="message";}
            //***********************end_of_sys_emails***************************
            break;
        case "global_setup":
            //**************************global_setup*******************************
            if(!isset($post['site_name'])||!check_lenght($post['site_name'],0,254))
            {$errors[]="site_name";}
            if(!isset($post['base_url'])||!check_lenght($post['base_url'],0,254)||!check_url($post['base_url']))
            {$errors[]="base_url";}
            if(!isset($post['absolute_path'])||!check_lenght($post['absolute_path'],0,254)||!file_exists($post['absolute_path']))
            {$errors[]="absolute_path";}
            if(!isset($post['logout_redirect'])||(!empty($post['logout_redirect']) && (!check_lenght($post['logout_redirect'],1,254)||!check_url($post['logout_redirect']))))
            {$errors[]="logout_redirect";}
            if(!isset($post['login_redirect'])||(!empty($post['login_redirect']) && (!check_lenght($post['login_redirect'],1,254)||!check_url($post['login_redirect']))))
            {$errors[]="login_redirect";}
            if(!isset($post['personal_login_redirect_flag'])||(mb_strtolower($post['personal_login_redirect_flag'])!="true"&&mb_strtolower($post['personal_login_redirect_flag'])!="false"))
            {$errors[]="personal_login_redirect_flag";}
            if(!isset($post['login_page'])||(!empty($post['login_page']) && (!check_lenght($post['login_page'],1,254)||!check_url($post['login_page']))))
            {$errors[]="login_page";}
            if(!isset($post['default_perpage'])||!check_range($post['default_perpage'],1,100))
            {$errors[]="default_perpage";}
            if(!isset($post['site_ip'])||ip2long($post['site_ip'])==-1||ip2long($post['site_ip'])===false)
            {$errors[]="site_ip";}
            if(!isset($post['date_format'])||!validate_date_format($post['date_format']))
            {$errors[]="date_format";}
            //if(!isset($post['member_force_pwd_gen'])||(mb_strtolower($post['member_force_pwd_gen'])!="true"&&mb_strtolower($post['member_force_pwd_gen'])!="false"))
            //{$errors[]="member_force_pwd_gen";}
            if(!isset($post['log_members'])||(mb_strtolower($post['log_members'])!="true"&&mb_strtolower($post['log_members'])!="false"))
            {$errors[]="log_members";}
            if(!isset($post['log_admins'])||(mb_strtolower($post['log_admins'])!="true"&&mb_strtolower($post['log_admins'])!="false"))
            {$errors[]="log_admins";}
            if(!isset($post['history_kept']) || !check_range($post['history_kept'],0,365))
            {$errors[] = "history_kept";}
            if(isset($post['ignored_extensions'])&&$post['ignored_extensions']!="")
            {
                $exts=explode(",",$post['ignored_extensions']);
                foreach($exts as $value)
                {
                    if(!isset($value)||!check_lenght($value,1,10)||eregi("^[^\\\/:;\*\?\"\<\>\'\|\.,&]+$",$value)===false)
                    {$errors[]="ignored_extensions";
                        break;
                    }
                }
            }
            //***********************end_of_global_setup***************************
            break;
        }
        return $errors;
    }
    /*   config_validate_ex  */

    /**************************************************************
    *    add_panel_vars_ex
    *    @author onagr
    ***************************************************************/
    /**
    * Add tmp variables and messages
    *
    * @param array $data
    * @param string $section
    * @return array
    */
    function add_panel_vars_ex($data,$section)
    {
        switch ($section)
        {
        case "member_pages":
        $temp_vars_set = array();
        $temp_vars_set['confirm_load'] = "<{admin_member_pages_msg_confirm_load}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/config/member_pages.js";
        $data['temp_vars_set'] = $temp_vars_set;
        $messages = array();
        $messages['saved']=array('display'=>false,'text'=>'<{admin_member_pages_msg_saved}>');
        $data['messages']=$messages;
        $mess_err=array();
        $mess_err['not_saved']=array('display'=>false,'text'=>'<{admin_member_pages_msg_er_not_saved}>');
        $mess_err['not_changed']=array('display'=>false,'text'=>'<{admin_member_pages_msg_er_not_changed}>');
        $data['mess_err']=$mess_err;
        break;
    case "design_settings":
            //*************************design_settings*****************************
            //Temp variables javascript
            $temp_vars_set = array();
            $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/design_manager.js";
            $data['temp_vars_set'] = $temp_vars_set;

            //Green messages
            $messages = array();
            $messages['design_saved_ok'] = "<{admin_design_manager_saved_successfully}>";
            $messages['main_page_saved_ok'] = "Main page settings was changed successfully";
            $data['messages'] = $messages;

            //Error messages
            $mess_err = array();
            $mess_err['validation_fail'] = "<{admin_design_manager_error}>";
            $mess_err['main_page_saved_error'] = "<{admin_design_manager_error}>";
            $data['mess_err'] = $mess_err;
            //*********************end_of_design_settings*************************
            break;
        case "mailer_settings":
            //*************************mailer_settings*****************************
            //Temp variables javascript
            $temp_vars_set = array();
            $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/mailer_settings.js";
            $data['temp_vars_set'] = $temp_vars_set;

            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_mailer_settings_saved_successfully}>";
            $messages['tested_ok'] = "<{admin_mailer_settings_tested_successfully}>";
            $data['messages'] = $messages;

            //Error messages
            $mess_err = array();
            $mess_err['validation_fail'] = "<{admin_mailer_settings_error}>";
            $mess_err['tested_fail'] = "<{admin_mailer_settings_tested_fail}>";
            $mess_err['tested_auth_fail'] = "<{admin_mailer_settings_tested_auth_fail}>";
            $mess_err['admin_email'] = "<{admin_mailer_settings_msg_er_admin_email}>";
            $mess_err['mailer_charset'] = "<{admin_mailer_settings_msg_er_charset}>";
            $mess_err['mailer_smtp_host'] = "<{admin_mailer_settings_msg_er_smtp_host}>";
            $mess_err['mailer_smtp_port'] = "<{admin_mailer_settings_msg_er_smtp_port}>";
            $mess_err['mailer_smtp_user'] = "<{admin_mailer_settings_msg_er_smtp_user}>";
            $mess_err['mailer_smtp_pass'] = "<{admin_mailer_settings_msg_er_smtp_pass}>";
            $mess_err['send_to_count'] = "<{admin_mailer_settings_msg_er_send_to_count}>";
            $data['mess_err'] = $mess_err;
            //*********************end_of_mailer_settings*************************
            break;
        case "host_settings":
            //*************************host_settings*****************************
            //Temp variables javascript
            $temp_vars_set = array();
            $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/host_plans/host_settings.js";
            $data['temp_vars_set'] = $temp_vars_set;

            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_host_settings_saved_successfully}>";
            $messages['tested_ok'] = "<{admin_host_settings_tested_successfully}>";
            $data['messages'] = $messages;

            //Error messages
            $mess_err = array();
            $mess_err['validation_fail'] = "<{admin_host_settings_error}>";
            $mess_err['tested_fail'] = "<{admin_host_settings_tested_fail}>";
            $mess_err['tested_auth_fail'] = "<{admin_host_settings_tested_auth_fail}>";
            $mess_err['setting_host'] = "<{admin_host_settings_msg_er_host}>";
            $mess_err['setting_port'] = "<{admin_host_settings_msg_er_port}>";
            $mess_err['setting_user'] = "<{admin_host_settings_msg_er_user}>";
            $mess_err['setting_pass'] = "<{admin_host_settings_msg_er_pass}>";
            $data['mess_err'] = $mess_err;
            //*********************end_of_host_settings*************************
            break;
        case "domain_settings":
            //*************************domain_settings*****************************
            //Temp variables javascript
            $temp_vars_set = array();
            $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/domain/domain_settings.js";
            fb($temp_vars_set,"temp_vars_set");
            $temp_vars_set['test_disabled']="<{admin_domain_settings_test_err}>";
            $data['temp_vars_set'] = $temp_vars_set; 

            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_domain_settings_saved_successfully}>";
            $messages['tested_ok'] = "<{admin_domain_settings_tested_successfully}>";
            $data['messages'] = $messages;

            //Error messages
            $mess_err = array();
            $mess_err['validation_fail'] = "<{admin_domain_settings_error}>";
            $mess_err['service_username'] = "<{admin_domain_settings_error_service_username}>";
            $mess_err['service_password'] = "<{admin_domain_settings_error_service_password}>";
            $mess_err['service_parentid'] = "<{admin_domain_settings_error_service_parentid}>";
            $mess_err['tested_fail'] = "<{admin_domain_settings_tested_fail}>";
            $mess_err['tested_auth_fail'] = "<{admin_domain_settings_tested_auth_fail}>";
            $mess_err['setting_domain'] = "<{admin_domain_settings_msg_er_host}>";            
            $mess_err['setting_user'] = "<{admin_domain_settings_msg_er_user}>";
            $mess_err['setting_pass'] = "<{admin_domain_settings_msg_er_pass}>";
            $data['mess_err'] = $mess_err;            
            //*********************end_of_domain_settings*************************
            break;
        case "main_page_settings":
            //*************************host_settings*****************************
            //Temp variables javascript
            $temp_vars_set = array();
            $temp_vars_set['cancelText'] = "<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/design_manager.js";
            $data['temp_vars_set'] = $temp_vars_set;

            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_host_settings_saved_successfully}>";
            $messages['tested_ok'] = "<{admin_host_settings_tested_successfully}>";
            $data['messages'] = $messages;

            //Error messages
            $mess_err = array();
            $mess_err['validation_fail'] = "<{admin_host_settings_error}>";
            $mess_err['tested_fail'] = "<{admin_host_settings_tested_fail}>";
            $mess_err['tested_auth_fail'] = "<{admin_host_settings_tested_auth_fail}>";
            //            $mess_err['admin_email'] = "<{admin_host_settings_msg_er_admin_email}>";
            //            $mess_err['setting_charset'] = "<{admin_host_settings_msg_er_charset}>";
            $mess_err['setting_host'] = "<{admin_host_settings_msg_er_host}>";
            $mess_err['setting_port'] = "<{admin_host_settings_msg_er_port}>";
            $mess_err['setting_user'] = "<{admin_host_settings_msg_er_user}>";
            $mess_err['setting_pass'] = "<{admin_host_settings_msg_er_pass}>";
            $mess_err['send_to_count'] = "<{admin_host_settings_msg_er_send_to_count}>";
            $data['mess_err'] = $mess_err;
            //*********************end_of_host_settings*************************
            break;
        case "ban_ip":
            //*************************pages_list*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/ban_ip.js";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";  
            $messages['deleted_ok'] = "<{admin_config_ban_ip_msg_ok_deleted}>";
            $messages['success_message']="<{admin_config_ban_ip_success_message}>";
            $messages['success_delete']="<{admin_config_ban_ip_msg_delete_success}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['ip'] = "<{admin_config_ban_ip_error_field_ip_wrong}>";
            $mess_err['reason'] = "<{admin_config_ban_ip_error_field_reason_toolong}>";
            $mess_err['not_saved'] = "<{admin_config_ban_ip_msg_er__not_saved}>";
            $mess_err['ip_empty'] = "'<{admin_config_ban_ip_error_ip_empty}>'";
            $mess_err['not_deleted'] = "<{admin_config_ban_ip_msg_er_not_deleted}>";
            $mess_err['ip_exist'] = "<{admin_config_ban_ip_error_ip_exists}>";
            $mess_err['access_denied'] = "<{admin_config_ban_ip_msg_er_access_denied}>";
            $mess_err['validation_fail'] = "<{admin_config_ban_ip_error_ip_validation_fail}>";
            $data['mess_err'] = $mess_err;
            
            //*********************end_of_pages_list*************************
            break;
        case "manage_news":
            //*************************pages_list*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['processing']="<{admin_manage_news_msg_processing}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/pages_list.js";
            $temp_vars_set['object_type']=6;
            $temp_vars_set['panel_name']="manage_news";
            $temp_vars_set['controller']="config/manage_news";
            $temp_vars_set['controller_action']="config/manage_news_action";
            
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";  
            $messages['deleted_ok'] = "<{admin_manage_news_msg_ok_deleted}>";
            $messages['success_message']="<{admin_manage_news_success_message}>";
            $messages['edit_field_success']="<{admin_manage_news_edit_page_success}>";
            $messages['add_field_success']="<{admin_manage_news_add_page_success}>";
            
            $messages['success_delete']="<{admin_manage_news_remove_page_success}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_saved'] = "<{admin_manage_news_msg_er_not_saved}>";
            $mess_err['duplicate_entry'] = "<{admin_manage_news_msg_er_duplicate_entry}>";
            $mess_err['not_deleted'] = "<{admin_manage_news_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_manage_news_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            
            //*********************end_of_pages_list*************************
            break;
        case "manage_pages":
            //*************************pages_list*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['processing']="<{admin_manage_pages_msg_processing}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/pages_list.js";
            $temp_vars_set['object_type']=9;            
            $temp_vars_set['panel_name']="manage_pages";
            $temp_vars_set['controller']="config/manage_pages";
            $temp_vars_set['controller_action']="config/manage_page_action";            
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";  
            $messages['deleted_ok'] = "<{admin_manage_pages_msg_ok_deleted}>";
            $messages['success_message']="<{admin_manage_pages_success_message}>";
            $messages['edit_field_success']="<{admin_manage_pages_edit_page_success}>";
            $messages['add_field_success']="<{admin_manage_pages_add_page_success}>";
            $messages['copy_link_success']="<{admin_manage_pages_copy_link_success}>";
            
            
            $messages['success_delete']="<{admin_manage_pages_remove_page_success}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_saved'] = "<{admin_manage_pages_msg_er_not_saved}>";
            $mess_err['duplicate_entry'] = "<{admin_manage_pages_msg_er_duplicate_entry}>";
            $mess_err['not_deleted'] = "<{admin_manage_pages_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_manage_pages_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            
            //*********************end_of_pages_list*************************
            break;
        case "add_fields_list":
            //*************************add_fields*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/add_fields.js";
            $temp_vars_set['panel_hash']="additional_fields";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_administrators_list_msg_ok_deleted}>";
            $messages['success_message']="<{admin_config_add_fields_success_message}>";
            $messages['edit_field_success']="<{admin_config_add_fields_edit_field_success}>";
            $messages['add_field_success']="<{admin_config_add_fields_add_field_success}>";
            
            $messages['success_delete']="<{admin_config_add_fields_remove_field_success}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_deleted'] = "<{admin_admin_edit_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_admin_edit_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['error_message']="<{admin_config_add_fields_error_message}>";
            $data['mess_err'] = $mess_err;
            
            $data['field_type']=$this->add_fields_types();
            $data['check_rule']=$this->add_fields_rules();
            
            //*********************end_of_add_fields*************************
            break;
            
        case "add_fields":
            //*************************add_fields*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/add_fields_edit.js";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_administrators_list_msg_ok_deleted}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['not_deleted'] = "<{admin_admin_edit_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_admin_edit_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['title'] = "<{admin_config_add_fields_error_title}>";
            $mess_err['field_values_empty'] = "<{admin_config_add_fields_error_field_values_empty}>";
            $mess_err['default_value_empty'] = "<{admin_config_add_fields_error_default_value_empty}>";
            $mess_err['fields_error'] = "<{admin_config_add_fields_error}>";
            $mess_err['default_value'] = "<{admin_config_add_fields_default_value}>";
            $mess_err['field_values'] = "<{admin_config_add_fields_field_values}> ";
            $mess_err['check_rule_numbers'] = "<{admin_config_add_fields_error_check_rule_numbers}>";
            $mess_err['check_rule_email'] = "<{admin_config_add_fields_error_check_rule_email}>";
            $mess_err['check_rule_phone'] = "<{admin_config_add_fields_error_check_rule_phone}>";
            $mess_err['edit_field_error'] = "<{admin_config_add_fields_edit_field_error}>";
            $mess_err['add_field_error'] = "<{admin_config_add_fields_add_field_error}>";
            
            $data['mess_err'] = $mess_err;
            
            $data['field_type']=$this->add_fields_types();
            $data['check_rule']=$this->add_fields_rules();
            
            //*********************end_of_add_fields*************************
            break;
        case "payment_system":
            //*************************payment_system*****************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['really_change']="<{admin_payment_system_msg_really_change}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/payment_system.js";
            $temp_vars_set['panel_hash']="system_settings";            
            
            $data['temp_vars_set']=$temp_vars_set;

            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_administrators_list_msg_ok_deleted}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_deleted'] = "<{admin_admin_edit_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_admin_edit_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['unaccepted_currency'] = "<{admin_payment_system_msg_er_unaccepted_currency}>";
            $mess_err['undefined_currency'] = "<{admin_payment_system_msg_er_undefined_currency}>";
            $mess_err['undefined_action'] = "<{admin_payment_system_msg_er_undefined_action}>";

            $data['mess_err'] = $mess_err;
            //*********************end_of_payment_system*************************
            break;
        case "sys_emails_template":
            //**************************sys_emails*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['reset_text']="<{admin_edit_sys_template_confirm_reset_text}>";
            $temp_vars_set['save_default_text']="<{admin_edit_sys_template_confirm_save_default_text}>";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_edit_sys_template_msg_ok_saved_default}>";
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['not_saved'] = "<{admin_edit_sys_template_msg_er_not_saved}>";
            $mess_err['not_saved_default'] = "<{admin_edit_sys_template_msg_er_not_saved_default}>";
            $mess_err['subject']="<{admin_edit_sys_template_msg_er_subject}>";
            $mess_err['message']="<{admin_edit_sys_template_msg_er_message}>";
            $data['mess_err'] = $mess_err;

            $constants=get_template_variables($data['admins']?'admin':'user');
            foreach($constants as $key=>$value)
            {$constants[$key] = "##".$value."##";}
            $data['constants']=$constants;
            //***********************end_of_sys_emails***************************
            break;
        case "sys_emails":
            //**************************sys_emails*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/sys_emails.js";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['subject']="<{admin_edit_sys_template_msg_er_subject}>";
            $mess_err['message']="<{admin_edit_sys_template_msg_er_message}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_sys_emails***************************
            break;
        case "global_setup":
            //**************************global_setup*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['warningChangeUrl']="<{admin_global_setup_msg_change_baseurl}>";
            $temp_vars_set['warningChangePath']="<{admin_global_setup_msg_change_path}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/config/global_setup.js";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['site_name']="<{admin_msg_er_0104}>";
            $mess_err['base_url']="<{admin_msg_er_0101}>"; //*
            $mess_err['absolute_path']="<{admin_msg_er_0105}>"; //-
            $mess_err['logout_redirect']="<{admin_msg_er_0101}>"; //-
            $mess_err['login_redirect']="<{admin_msg_er_0101}>"; //-             
            $mess_err['login_page']="<{admin_global_setup_msg_er_login_page}>"; //-            
            $mess_err['site_ip']="<{admin_msg_er_0102}>";
            $mess_err['history_kept']="<{admin_global_setup_msg_er_history_kept}>";
            $mess_err['date_format']="<{admin_global_setup_msg_er_date_format}>";            
            $mess_err['ext_text']= "<{admin_msg_er_0103}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_global_setup***************************
            break;
        }
        return $data;
    }
    /*   add_panel_vars_ex  */

    /**************************************************************
    *    sys_emails_set
    *    @author onagr
    ***************************************************************/
    /**
    * Set system email messages
    *
    * @author onagr
    * @param array $post
    * @return mixed
    */
    function sys_emails_set($post)
    {
        $data = array('name' => $post['subject'],'descr' => $post['message']);
        $this->db->where('language_data.object_id',$post['id']);
        $this->db->where('language_data.object_type','2');
        if($post['action']=='save_default')
        {
            $this->db->where('language_data.language_id','-1');
        }
        else
        {
            $this->db->where('language_data.language_id','1');
        }
        $this->db->update(db_prefix.'Language_data language_data', $data);
        if($this->db->affected_rows()>0)
        {
            return true;
        }
        else
        {
            if($post['action']=='save_default')
            {
                return "not_saved_default";
            }
            else
            {
                return "not_saved";
            }
        }
    }
    /*   sys_emails_set  */

    /**************************************************************
    *    sys_emails_get
    *    @author onagr
    ***************************************************************/
    /**
    * Get system email messages
    *
    * @author onagr
    * @param array $post
    * @return array
    */
    function sys_emails_get($post)
    {
        $id=explode("_",$post['id']);
        $id=isset($id[2])?$id[2]:$post['id'];

        $this->db->select('language_data.object_id,language_data.name as subject,language_data.descr,system_emails.name,system_emails.email_type');
        $this->db->from(db_prefix.'Language_data language_data');
        $this->db->where('language_data.object_id',$id);
        $this->db->where('language_data.object_type','2');

        if($post['action']=='default'||$post['action']=='save_default')
        {
            $this->db->where('language_data.language_id','-1');
        }
        else
        {
            $this->db->where('language_data.language_id','1');
        }
        $this->db->where('system_emails.id',$id);
        $this->db->join(db_prefix.'System_emails system_emails','language_data.object_id=system_emails.id','left');
        $this->db->distinct();
        $query = $this->db->get();
        $email=$query->result_array();

        $data=array();
        if(count($email)>0)
        {
            $data['name']=$email[0]['name'];
            $data['subject']=$email[0]['subject'];
            $data['message']=$email[0]['descr'];
            $data['admins']=$email[0]['email_type']=='email_type' ? true : false;
            $data['id']=$id;
        }
        return $data;
    }
    /*   sys_emails_get  */

    /**************************************************************
    *    sys_emails
    *    @author onagr
    ***************************************************************/
    /**
    * Get admin and user system emails messages
    *
    * @author onagr
    * @return array
    */
    function sys_emails()
    {
        $data=array();
        $this->db->select('system_emails.id,system_emails.email_key as `key`, system_emails.email_type');
        $this->db->from(db_prefix.'System_emails system_emails');
        $this->db->where('system_emails.email_type','user');
        $query = $this->db->get();
        $data['user_emails']=$query->result_array();

        $this->db->select('system_emails.id,system_emails.email_key as `key`,system_emails.email_type');
        $this->db->from(db_prefix.'System_emails system_emails');
        $this->db->where('system_emails.email_type','admin');
        $query = $this->db->get();
        $data['admin_emails']=$query->result_array();
        
        return $data;
    }
    /*   sys_emails  */

    /**************************************************************
    *    path_exist
    *    @author onagr
    ***************************************************************/
    /**
    * Check is file exist
    *
    * @author onagr
    * @param array $data
    * @return string
    */
    function path_exist($data)
    {
        if(isset($data['action']))
        unset($data['action']);
        $str="{";
        $flag=false;
        foreach ($data as $key => $value)
        {
            $is_exist=file_exists($value)?"true":"false";
            $str.=($flag?",":"")."'".$key."':{'path':'".base64_encode($value)."','is_exist':".$is_exist.",'error_text':'<{admin_msg_er_0106}>'}";
            $flag=true;
        }
        $str.="}";
        return $str;
    }
    /*   path_exist  */

    /**************************************************************
    *    global_get
    *    @author onagr
    ***************************************************************/
    /**
    * Enter description here...
    *
    * @author onagr
    * @return array
    */
    function global_get()
    {
        $paths = $this->get_config_xml_path('global_setup');
        $data=config_get_ex($paths);

        if(!isset($data['perpage_list']) or empty($data['perpage_list']) or eregi('^[\s]*$', $data['perpage_list'])!=false )
        {
            $data['perpage_list']="5,10,15,20,30,50";
            $temp=array();
            $temp['perpage_list']= $data['perpage_list'];
            config_set_ex($temp,$paths);
        }

        foreach ($data as $key => $value)
        {
            //if(in_array($key,array('member_force_pwd_gen','log_members','log_admins')))
            if(in_array($key,array('log_members','log_admins')))
            {
                switch ($value)
                {
                case "1":
                    $data[$key]='true';
                    break;
                case "0":
                    $data[$key]='false';
                    break;
                }
            }
        }

        return $data;
    }
    /*   global_get  */

    /**************************************************************
    *    global_set
    *    @author onagr
    ***************************************************************/
    /**
    * Enter description here...
    *
    * @author onagr
    * @param array $data
    */
    function global_set($data)
    {
        unset($data['perpage_list']);

        //change of site ip means we need to reprotect all directories protected with mod_rewrite_standard
        $site_ip_changed = isset($data['site_ip']) && (config_get('system','config','site_ip') != $data['site_ip']);

        if(isset($data['base_url']) && $data['base_url']!=='')
        {
            $data['base_url']=trim($data['base_url']);
            $data['base_url']=(substr($data['base_url'],-1)!="/") ? $data['base_url']."/" : $data['base_url'];  
        }
        
        foreach ($data as $key => $value)
        {
            //if(in_array($key,array('member_force_pwd_gen','log_members','log_admins')))
            if(in_array($key,array('log_members','log_admins','personal_login_redirect_flag')))
            {
                switch (mb_strtolower($value))
                {
                case "true":
                    $data[$key]='1';
                    break;
                case "false":
                    $data[$key]='0';
                    break;
                }
            }
        }
        //print_ex($data,"GLOBAL_DATA");
        $paths = $this->get_config_xml_path('global_setup');
        config_set_ex($data,$paths);

        //site_ip_changed
        if ($site_ip_changed)
        {
            protection_event("SITE_IP_CHANGED");
        }
    }
    /*   global_set */


    /**************************************************************
    *   Additional Fields
    ***************************************************************/
    /**
    * Enter description here...
    *
    * @return array
    */
    function add_fields()
    {
        $data=array();
        $fields=$this->add_fields_list();
        if( $fields!=false )
        {
            $data['field_type']=$this->add_fields_types();
            $data['check_rule']=$this->add_fields_rules();
            $data['fields']=$fields;
        }
        $data=$this->add_panel_vars_ex($data,"add_fields_list");
        return $data;
    }
    /**
    * Enter description here...
    *
    * @return array
    */
    function add_fields_types()
    {
        $field_type=array();
        $field_type['1']="<{admin_config_add_fields_field_type_text}>";
        $field_type['2']="<{admin_config_add_fields_field_type_select_single}>";
        $field_type['3']="<{admin_config_add_fields_field_type_select_multiple}>";
        $field_type['4']="<{admin_config_add_fields_field_type_textarea}>";
        $field_type['5']="<{admin_config_add_fields_field_type_radio}>";
        $field_type['6']="<{admin_config_add_fields_field_type_checkbox}>";
        return $field_type;
    }
    /**
    * Enter description here...
    *
    * @return array
    */
    function add_fields_rules()
    {
        $check_rule=array();
        $check_rule['0']="--";
        //$check_rule['1']="<{admin_config_add_fields_check_rule_not_empty}>";;
        $check_rule['2']="<{admin_config_add_fields_check_rule_numbers_only}>";
        //$check_rule['3']="<{admin_config_add_fields_check_rule_letters_only}>";
        $check_rule['4']="<{admin_config_add_fields_check_rule_email}>";
        //$check_rule['5']="<{admin_config_add_fields_check_rule_chars_interval}>";
        $check_rule['6']="<{admin_config_add_fields_check_rule_phone}>";
        return $check_rule;
    }
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function add_fields_sort($post)
    {
        $pos=explode(",",$post['pos']);
        foreach($pos as $key=>$val)
        {    
            $this->db->update(db_prefix."Add_fields", array('taborder'=>$key), array('id' => $val));
        }    
        $this->db->select('id');
        $this->db->order_by('taborder');
        $query = $this->db->get(db_prefix."Add_fields");
        $arr=$query->result_array();
        $res=array();
        foreach($arr as $val)
        {
            $res[]=$val['id'];
        }
        $result=array();
        $result['pos']=implode(",",$res);
        //print_r($pos);
        
        return $result;     
    }
    /**
    * Get fiedl list
    *
    * @return mixed
    */
    function add_fields_list()
    {
        //$this->db->order_by('taborder');
        $query = $this->db->get(db_prefix."Add_fields");
        if( $query->num_rows()>0 )
        {$t=$query->result_array();
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $t=$CI->lang_manager_model->combine_with_language_data($t,11,array('name'=>'name','descr'=>'descr'),'id',array('col'=>'taborder'),false,&$add_params);
            return $t;
        }
        return false;
    }
    
    /**
    * Add new field
    *
    * @param string $title
    * @param string $descr
    * @param string $required
    * @param string $type
    * @param string $field_value
    * @param string $default_value
    * @param string $check_rule
    * @return boolean
    */
    function add_fields_add($title,$descr,$required,$type,$field_value,$default_value,$check_rule)
    {

        $this->db->select_max('taborder');
        $query = $this->db->get(db_prefix.'Add_fields');
        $arr=$query->result_array();
        $taborder=$arr[0]['taborder']+1;
        $this->db->insert(db_prefix.'Add_fields',array(
        'req'=>input_text($required),
        'type'=>input_text($type),
        'def_value'=>input_text($default_value),
        'val'=>input_text($field_value),
        'check_rule'=>input_text($check_rule),
        'taborder'=>$taborder
        ));
        if( $this->db->affected_rows() == 1 )
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $t=$CI->lang_manager_model->template_set(array('id'=>$this->db->insert_id(),'object_type'=>11,'name'=>input_text($title),'descr'=>input_text($descr),'language_id'=>$CI->default_language_id));
            return true;
        }
        return false;

    }

    
    
    /**
    * Edit field
    *
    * @param integer $id
    * @param string $title
    * @param string $descr
    * @param mixed $required
    * @param string $type
    * @param string $field_value
    * @param string $default_value
    * @param string $check_rule
    * @return boolean
    */
    function add_fields_edit($id,$title,$descr,$required,$type,$field_value,$default_value,$check_rule)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix.'Add_fields',array(
        'req'=>intval($required),
        'type'=>input_text($type),
        'def_value'=>input_text($default_value),
        'val'=>input_text($field_value),
        'check_rule'=>input_text($check_rule)
        ));
        if( $this->db->affected_rows() != -1 )
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $t=$CI->lang_manager_model->template_set(array('id'=>$id,'object_type'=>11,'name'=>input_text($title),'descr'=>input_text($descr),'language_id'=>$CI->default_language_id));
            return true;
        }
        return false;

    }
    

    /**************************************************************
    *   _Additional Fields
    ***************************************************************/    

    /**
    * Update or insert language variable
    *
    * @param string $key_name
    * @param string $content
    * @param mixed $lang_id
    * @return boolean
    */
    function language_set($key_name,$content,$lang_id,$section=false)
    {

        if( !isset($key_name) or empty($key_name) or !isset($content) or empty($content) or intval($lang_id)<=0 )
        {
            return false;
        }
        $lang_id = intval($lang_id);

        $this->db->select('key_name,content');
        $language_query = $this->db->get_where(db_prefix.'Interface_language',array('language_id'=>$lang_id,'key_name'=>$key_name));
        if( $language_query->num_rows() > 0)
        {
            $a=$language_query->result_array();
            if($a[0]['content']==input_text($content))
            {
                return false;
            }
            $data = array();
            $data = array(
            'content'=>input_text($content)
            );
            if($section!==false)
            {
                $data['section']=$section;
            }            
            $this->db->update(db_prefix.'Interface_language',$data,array('key_name'=>$key_name,'language_id'=>$lang_id));
        }
        else
        {
            $data = array();
            $data = array(
            'key_name'=>trim(input_text($key_name)),
            'content'=>input_text($content),
            'language_id'=>intval($lang_id)
            );
            if($section!==false)
            {
                $data['section']=$section;
            }  
            $this->db->insert(db_prefix.'Interface_language',$data);
        }

        $data = array();
        if( $this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }



    }
    /**
    * Get all languages
    *
    * @return array
    */
    function language_list()                                                                                        // Modified by Konstantin X @ 14:08 21.08.2008
    {
        $return = array("result"=>false, "items"=>array());

        $this->db->order_by('name','asc');
        $query = $this->db->get(db_prefix.'Languages');

        if( $query->num_rows() > 0 )
        {
            $return['items'] = $query->result_array();
        }

        $return['result'] = true;

        return $return;
    }


    /**
    * Language constants
    * 
    * Show list of language constants, generate PAGER data, apply filter
    *
    * @author  Konstantin X @ 10:11 14.11.2008
    * @access  public
    * @param   array $post
    * @return  array
    */
    function language_const_list($post)
    {
        $this->db->select('*');
        $this->db->from(db_prefix.'News');
        $count=$this->db->count_all_results();
        $data['pagers']=pager_ex($post,$count,'date');
        $params=$data['pagers']['params'];
        $this->db->select('*');
        $this->db->from(db_prefix.'News');
        $this->db->limit($params['limit'],$params['offset']);
        $this->db->order_by($params['column'],$params['order']);
        $query = $this->db->get();
        $news=$query->result_array();
        $data['news']=$news;
        return $data;
    }

    /**
    * Get language variables for user or admin part
    *
    * @param integer $lang_id
    * @param mixed $part
    * @return array
    */
    function lang_translate($lang_id,$part=0) // Modified by Konstantin X @ 14:08 21.08.2008
    {
        $return = array("result" => false, "items" => array(), "lang_name" => '');

        $this->db->select('key_name, content');
        $this->db->where('language_id',$lang_id);
        if(intval($part)==1)
        {
            $this->db->where('section!=','admin');
        }
        if(intval($part)==2)
        {
            $this->db->where('section','admin');
        }
        $this->db->order_by('key_name','asc');
        $query = $this->db->get(db_prefix.'Interface_language');
        /* echo $this->db->last_query();
        echo "<pre>";
        print_r($query->result_array());
        echo "</pre>";
        return; */

        if($query->num_rows() > 0)
        {
            $return["items"] = $query->result_array();

            $query               = $this->db->get_where(db_prefix.'Languages', array('id' => $lang_id), 1);
            $lang_name           = $query->result_array();
            $return["lang_name"] = $lang_name[0]["lang_code"];
            
            $return["result"] = TRUE;
            
        }else{
            $return["result"] = FALSE;
        }

        return $return;
    }
    /**
    * Insert new language
    *
    * @param string $lang_code
    * @param string $default_lang
    * @return mixed
    */
    function language_add($lang_code,$default_lang="")
    {
        $default_lang = $this->config_model->get_language_id($default_lang, TRUE);
        if(intval($default_lang)==0)
        {        
            $query = $this->db->get_where(db_prefix.'Languages', array('is_default' => 1),1);
            $result=$query->result_array();
            if(count($result)==0)
            {
                return false;
            }
            $default_lang=$result[0]['id'];
        }
        // insert new language
        $all_langs = get_lang_list();
        if(array_key_exists($lang_code,$all_langs))
        {
            $query = $this->db->insert(db_prefix.'Languages', array('name' => $all_langs[$lang_code], 'lang_code' => $lang_code));
            if ($this->db->affected_rows() == 1)
            {

                $new_lang  = intval($this->db->insert_id());
                //copy languages data
                if (intval($default_lang)>0 and intval($new_lang)>0)
                {
                    $this->db->query(
                    "
                                INSERT INTO `".db_prefix."Interface_language`
                                ( language_id,key_name,content,section )
                                SELECT
                                '".intval($new_lang)."',key_name,content,section
                                FROM `".db_prefix."Interface_language`
                                WHERE
                                language_id = '".intval($default_lang)."'
                                "
                    );
                    
                }
                //_copy languages data   
                return $new_lang;                
            }
        }
        return false;
    }
    /**
    * Add language variables
    *
    * @param array $data
    * @return array
    */
    function lang_translate_put($data)
    {
        $aReturn['status'] = TRUE;
        $this->db->select('id');
        $this->db->where('lang_code', $data['lang']);
        $sql = $this->db->get(db_prefix.'Languages');

        if($sql->num_rows() == 1)
        {
            $row     = $sql->row_array();
            $lang_id = $row['id'];
        }else{
            $aReturn['message'] = '<{admin_msg_er_0022}>';                                                          // "The requsted language not found"
            $aReturn['status']  = FALSE;
            return $aReturn;
        }

        $this->benchmark->mark('lng_upd_start');
        $sql = '';

        foreach($data['data'] as $k => $v)
        {
            if(strlen($v) > 0)
            {
                $sql = sprintf('UPDATE '. db_prefix .'Interface_language SET content = "%s" WHERE key_name = "%s" AND language_id = %d', addslashes($v), $k, $lang_id);
                $rez = $this->db->query($sql);
                if(!(bool) $rez)
                {
                    $aReturn['message'] = '<{admin_msg_er_0023}>';                                                  // "UPDATE operation fail"
                    //                  $aReturn['err_msg'] = mysql_error();
                    $aReturn['status']  = FALSE;
                    return $aReturn;
                }
            }
        }

        $this->benchmark->mark('lng_upd_end');

        $aReturn['timer']   = $this->benchmark->elapsed_time('lng_upd_start', 'lng_upd_end');
        $aReturn['message'] = '<{admin_msg_ok_0004}>';                                                              // "Language data was updated successfully"
        return $aReturn;
    }

    /**
    * Delete language variable
    *
    * @param string $key_name
    * @param mixed $lang_id
    * @return boolean
    */
    function delete_key_name($key_name,$lang_id)
    {
        if( empty($key_name) or intval($lang_id)<=0 )
        {
            return false;
        }

        $this->db->where('language_id',intval($lang_id));
        $this->db->where('key_name',input_text($key_name));
        $this->db->delete(db_prefix.'Interface_language');

        if( $this->db->affected_rows()==1 )
        {
            return true;
        }
        return false;
    }
    /**
    * List of language variables
    *
    * @param mixed $lang_id
    * @param mixed $page
    * @param mixed $count
    * @param string $filter_str
    * @param string $filter_fld
    * @return array
    */
    function interface_language_list($lang_id, $page=5, $count=0, $filter_str='', $filter_fld='key_name')
    {

        $return = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>intval(config_get('SYSTEM','CONFIG','default_per_page')),
        "items"=>array()
        );

        $lang_id = intval($lang_id);

        if( $lang_id<=0 )
        {
            return $return;
        }

        $page = intval($page);
        $count = intval($count);

        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $return;
        }

        $return['per_page'] = $count;

        // get languages count
        $this->db->select('count(key_name) as all_rows',false);
        $this->db->distinct();
        $this->db->where('language_id',$lang_id);

        if($filter_str)
        {
            if ($filter_fld == 'content')   $this->db->like('content', $filter_str, 'both');                        // Update by Konstantin X @ 16:01 01.12.2008
            else                            $this->db->like('key_name', $filter_str, 'after');                      // Added by Konstantin X @ 11:11 13.11.2008
        }

        $this->db->limit(1);
        $query = $this->db->get(db_prefix.'Interface_language');
        if ( $query->num_rows > 0)
        {
            $all_rows = $query->row();
            $return['total'] = intval($all_rows->all_rows);
        }
        // _get languages count


        //Set limit
        if( $page > 1 and intval($return['total']) > 0 )
        {
            $row_start = intval(($page - 1) * intval($count));
            if( $row_start >= $return['total'] )
            {
                $page = ceil( intval($return['total']) / intval($count) );
                $row_start = intval(($page - 1) * $count);
            }
        }
        else
        {
            $row_start = 0;
        }
        //_Set limit

        $this->db->where('language_id',$lang_id);

        if($filter_str)
        {
            if ($filter_fld == 'content')   $this->db->like('content', $filter_str, 'both');                        // Update by Konstantin X @ 16:01 01.12.2008
            else                            $this->db->like('key_name', $filter_str, 'after');                      // Added by Konstantin X @ 11:11 13.11.2008
        }

        $this->db->order_by('key_name','asc');
        $this->db->distinct();
        $this->db->limit($count,$row_start);
        $query = $this->db->get(db_prefix.'Interface_language');

        $return['count'] = $query->num_rows();
        if( $query->num_rows() > 0 )
        {
            $return['items'] = $query->result_array();
        }

        if ($filter_fld == 'content')
        {
            $i = 0;
            foreach ($return['items'] as $v)
            {
                $pattern = '('. $filter_str .')';
                $return['items'][$i]['content'] = mb_eregi_replace($pattern, '<span style="background-color: yellow;">\\1</span>', output($v['content']));
                $i++;
            }
        }

        $return['result'] = true;


        return $return;
    }


    /********************************************* BAN IP ***********************************/
    /**
    * Adds IP address into Banned IP table
    *
    * @author Drovorubov
    * @param array $param
    * @return bool
    */
    function ban_ip_add($param)
    {
        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        
        $this->db->insert(db_prefix.'Banned_ip', array('ip'=>$param['ip']));
        if($this->db->affected_rows() == 1)
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $t=$CI->lang_manager_model->template_set(array('id'=>$this->db->insert_id(),'object_type'=>12,'name'=>input_text($param['reason']),'language_id'=>$CI->default_language_id));
            return true;
        }

        return false;
    }

    /**
    * Checks if IP address exists in DB
    *
    * @author Drovorubov
    * @param string $ip
    * @return bool
    */
    function ban_ip_check_ip($ip)
    {
        if( empty($ip) )
        {
            return false;
        }

        $this->db->select('ip');
        $this->db->from(db_prefix.'Banned_ip');
        $this->db->where('ip', $ip);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            return true;
        }

        return false;
    }
    /**
    * list of ban ip
    *
    * @param array $post
    * @return array
    */
    function ban_ip_list($post)
    {
        $rv = array();
        $query=$this->db->query("SELECT id, ip, (SUBSTRING_INDEX(ip, '.', 1 )<<24 ) + ( SUBSTRING_INDEX( SUBSTRING_INDEX(ip, '.', 2 ), '.', -1 )<<16 ) + ( SUBSTRING_INDEX( SUBSTRING_INDEX(ip, '.', -2 ), '.', 1 )<<8 ) + SUBSTRING_INDEX(ip, '.', -1) as `IPvINT` FROM ".db_prefix."Banned_ip ORDER BY `IPvINT` ASC;");
        
        $t=$query->result_array();
        $rv['pagers'] = pager_ex($post,count($t));
        $pager=$rv['pagers']['params'];
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,12,array('name'=>'reason'),'id',array('limit'=>$pager['limit'],'offset'=>$pager['offset']),false,&$add_params);
        $rv['items'] = $t;
        if ( count($t) > 0 )
        {
            $rv['result'] = true;
        }
        return $rv;        
    }


    /**
    * Deletes IP address from Banned IP table
    *
    * @author Drovorubov
    * @param string $ip
    * @return bool
    */
    function ban_ip_delete($ip)
    {
        if( empty($ip)  )
        {
            return false;
        }
        $query = $this->db->get_where(db_prefix.'Banned_ip', array('ip' => $ip));
        $id=array_transform($query->result_array(),false,'id');
        
        // Delete IP item from Banned_ip
        $this->db->where('ip', $ip);
        $this->db->delete(db_prefix.'Banned_ip');
        if( $this->db->affected_rows() > 0 )
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $CI->lang_manager_model->remove_language_data(12,$id);
            return true;
        }
        return false;
    }

    /********************************************* BAN IP ***********************************/


    /********************************************* NEWS **************************************/
    /**
    * Select items from news and language_data tables
    * according page, count values and language
    * Form array to return
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function news_list($page,$count,$sort_by,$sort_how)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $rv;
        }

        $rv['per_page'] = $count;

        // Set order before selection
        $sort_by = $this->_get_news_order($sort_by);
        // Set order type
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';

        //Get total news count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id = language_data.object_id');
        $this->db->where('language_data.object_type = 6');
        $this->db->where('language_data.language_id = 1');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $rv['total'] = $row->all_rows;
        }

        //Set rows start limit for select
        if($page > 1 && $rv['total'] > 0)
        {
            $row_start = intval(($page - 1) * $count);
            if( $row_start >= $rv['total'] )
            {
                $page = ceil( $rv['total'] / $count );
                $row_start = intval(($page - 1) * $count);
            }
        }
        else
        {
            $row_start = 0;
        }

        //Get news list
        $this->db->select('id, name, descr');
        $this->db->select("DATE_FORMAT(news.date, '%d.%m.%Y') as date");
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id = language_data.object_id');
        $this->db->where('language_data.object_type = 6');
        $this->db->where('language_data.language_id = 1');
        $this->db->limit($count,$row_start);
        $this->db->order_by($sort_by, $sort_how);
        $query = $this->db->get();

        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $query->result_array();
        }

        $rv['result'] = true;

        return $rv;
    }


    /**
    * Get news item by id
    *
    * @author Drovorubov
    * @param string $id
    * @return array
    */
    function news_get($id)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if(empty($id))
        {
            return $rv;
        }

        //Get news item
        $this->db->select('news.id, name, descr, add, members_only');
        $this->db->select("DATE_FORMAT(news.date, '%d-%m-%Y') as date");
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id',$id);
        $this->db->where('news.id = language_data.object_id');
        $this->db->where('language_data.object_type = 6');
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $data = $query->result_array();
            $rv['items'] = $data[0];
            $rv['result'] = true;
        }

        return $rv;
    }


    /**
    * Add news to DB
    *
    * @author Drovorubov
    * @param array $param
    * @return bool
    */
    function news_add($param)
    {
        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }

        $data['date'] = $param['date'];
        $data['members_only'] = $param['members_only'];
        $this->db->insert(db_prefix.'News', $data);
        $id = $this->db->insert_id();
        if($this->db->affected_rows() == 1 && $id > 0)
        {
            $data = array();
            $data['language_id'] = 1;
            $data['object_type'] = 6;
            $data['name'] = $param['header'];
            $data['descr'] = $param['brief'];
            $data['language_add'] = $param['content'];
            $data['object_id'] = $id;
            $this->db->insert(db_prefix.'Language_data', $data);
            if($this->db->affected_rows() == 1)
            {
                return true;
            }
            //Delete data from news
            $this->db->delete(db_prefix.'News', array('id' => $id));
        }

        return false;
    }



    /**
    * Update news item in DB
    *
    * @author Drovorubov
    * @param integer $id
    * @param array $param
    * @return bool
    */
    function news_set($id, $param)
    {
        if( empty($id) || !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        // Update News table
        $data = array();
        if(isset($param['date'])){ $data['date'] = $param['date']; }
        if(isset($param['members_only'])){ $data['members_only'] = $param['members_only']; }
        if(count($data) > 0)
        {
            $this->db->where('id', $id);
            $this->db->update(db_prefix.'News', $data);
        }
        $news_updated = $this->db->affected_rows() ;
        // Update Language_data table
        $data = array();
        if(isset($param['header'])){ $data['name'] = $param['header']; }
        if(isset($param['brief'])){ $data['descr'] = $param['brief']; }
        if(isset($param['content'])){ $data['language_add'] = $param['content']; }
        if( count($data) > 0 )
        {
            $this->db->where('object_id', $id);
            $this->db->where('object_type = 6');
            $this->db->update(db_prefix.'Language_data', $data);
        }

        if($this->db->affected_rows() > 0 || $news_updated > 0 )
        {
            return true;
        }

        return false;
    }


    /**
    * Delete news item from DB
    *
    * @author Drovorubov
    * @param integer $id
    * @return bool
    */
    function news_remove($id)
    {
        if( empty($id)  )
        {
            return false;
        }
        // Delete news item from Language_data
        $this->db->where('object_id', $id);
        $this->db->where('object_type = 6');
        $this->db->where('language_id = 1');
        $this->db->delete(db_prefix.'Language_data');
        if( $this->db->affected_rows() > 0 )
        {
            // Delete news item from News
            $this->db->where('id', $id);
            $this->db->delete(db_prefix.'News');
            if( $this->db->affected_rows() > 0 )
            {
                return true;
            }
        }
        return false;
    }


    /**
    * Converts param for ORDER in SELECT
    *
    * @author Drovorubov
    * @param string $param
    * @return string
    */
    function _get_news_order($param)
    {
        $rv = '';
        switch($param)
        {
        case 'by_date':
            {
                $rv = 'news.date';
                break;
            }
        case 'by_subject':
            {
                $rv = 'language_data.name';
                break;
            }
        case 'by_by_descr':
            {
                $rv = 'language_data.descr';
                break;
            }
        default:
            {
                $rv = 'news.date';
            }
        }

        return $rv;
    }


    /********************************************* _NEWS **************************************/




    //**************************************************************************************************** SECURITY EDIT
    
    /**
    * Get securety settings
    *
    * @author Konstantin X
    * @return array
    */
    
    function security_get(){
        /**************************************************************
    *   @author Konstantin X | 17:06 20.05.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $path = $this->get_config_xml_path('security_settings');
        $data = config_get_ex($path);
        return $data;
    }                                                                                                               //________ security_get
    /**
    * set security settings
    *
    * @author Konstantin X
    * @param array $data
    * @return mixed
    */
    function security_set($data){
        /**************************************************************
    *   @author Konstantin X | 17:06 20.05.2008
    *
    *   @param ARRAY $data
    *   @return BOOL || ERROR_STRING
    ***************************************************************/
        $result = true;
        $paths  = $this->get_config_xml_path('security_settings');
        $answer = config_set_ex($data,$paths);
        if($answer === false){$result = 'SECURITY SETTINGS SAVE ERROR';}
        return $result;
    }                                                                                                               //________ security_set
    //____________________________________________________________________________________________________ SECURITY EDIT


    //****************************************************************************************************** STATUS EDIT
    
    /**
    * Get system status
    *
    * @author Konstantin X
    * @return array
    */
    function status_get(){
        /**************************************************************
    *   @author Konstantin X | 17:13 28.05.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $data = config_get('SYSTEM','STATUS');
        return $data;
    }                                                                                                               //________ status_get
    /**
    * Set system status
    *
    * @author Konstantin X
    * @param array $data
    * @return mixed
    */
    function status_set($data){
        /**************************************************************
    *   @author Konstantin X | 17:13 28.05.2008
    *
    *   @return BOOL || ERROR_STRING
    ***************************************************************/
        $result = true;
        $return = config_set($data['offline_msg'],'SYSTEM','STATUS','offline_msg');
        if($return === false){$result = 'SYSTEM STATUS SAVE ERROR ';}
        return $result;
    }                                                                                                               //________ status_set
    
    function main_page_set($data)
    {
        $result = true;
        if (!eregi("^[0-9]+$", $data['page_amount']) or !eregi("^[0-9]+$", $data['news_amount']) or !eregi("^[0-9]+$", $data['unreg_page_amount']) or !eregi("^[0-9]+$", $data['unreg_news_amount']))
        {
            return false;	
        }
        $return = config_set($data['admin_msg'],'SYSTEM','MAIN_PAGE','admin_msg');
        $return = config_set(intval($data['page_amount']),'SYSTEM','MAIN_PAGE','page_amount');
        $return = config_set(intval($data['news_amount']),'SYSTEM','MAIN_PAGE','news_amount');
        $return = config_set($data['unreg_admin_msg'],'SYSTEM','MAIN_PAGE','unreg_admin_msg');
        $return = config_set(intval($data['unreg_page_amount']),'SYSTEM','MAIN_PAGE','unreg_page_amount');
        $return = config_set(intval($data['unreg_news_amount']),'SYSTEM','MAIN_PAGE','unreg_news_amount');
        if($return === false){$result = false;}//{$result = 'SYSTEM STATUS SAVE ERROR ';}
        return $result;
    }
    
    function main_page_get($data)
    {

        $data['page_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'page_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'page_amount')) : 3;
        $data['news_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'news_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'news_amount')) : 3;
        $data['admin_msg'] = (config_get('SYSTEM','MAIN_PAGE', 'admin_msg')!='' or config_get('SYSTEM','MAIN_PAGE', 'admin_msg')!=false) ? config_get('SYSTEM','MAIN_PAGE', 'admin_msg') : '';
        $data['unreg_page_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_page_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_page_amount')) : 3;
        $data['unreg_news_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_news_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_news_amount')) : 3;
        $data['unreg_admin_msg'] = (config_get('SYSTEM','MAIN_PAGE', 'unreg_admin_msg')!='' or config_get('SYSTEM','MAIN_PAGE', 'unreg_admin_msg')!=false) ? config_get('SYSTEM','MAIN_PAGE', 'unreg_admin_msg') : '';
        return $data;
    }
    
    /**
    * Switch system status
    *
    * @author Konstantin X
    * @return true
    */
    function status_switch(){
        /**************************************************************
    *   @author Konstantin X | 12:44 29.05.2008
    *
    *   @return TRUE
    ***************************************************************/
        $status = intval(config_get('SYSTEM','STATUS','online'));
        $result = ($status == 1) ? 0 : 1;
        $status = config_set($result,'SYSTEM','STATUS','online');
        return true;
    }                                                                                                               //________ status_switch
    //______________________________________________________________________________________________________ STATUS EDIT


    //******************************************************************************************************** PAGE EDIT
    
    /**
    * Enter description here...
    *
    * @author Konstantin X
    * @return array
    */
    function get_languages(){
        /**************************************************************
    *   @author Konstantin X | 9:36 31.05.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $this->db->select('id, name');
        $this->db->order_by('id');
        $sql = $this->db->get(db_prefix.'Languages');
        $rez = $sql->result_array();

        foreach($rez as $row)
        {
            $data['options'][$row['id']] = $row['name'];
        }

        $this->db->select('id');
        $this->db->where('is_default', 1);
        $sql = $this->db->get(db_prefix.'Languages');

        if($this->db->affected_rows() == 1)
        {
            $selected = $sql->result_array();
            $data['selected'] = $selected[0]['id'];
        }else{
            $data['selected'] = $rez[0]['id'];
        }

        return $data;
    }                                                                                                               //________ get_languages


    /**
    * Get Langueage ID by NAME or LANG_CODE else return default language ID
    *
    * @author Konstantin X 
    * @param string $language
    * @param string $is_code
    * @return integer
    */
    function get_language_id($language='', $is_code=FALSE)
    {
        $this->db->select('id');

        if (empty($language))
        {
            $this->db->where('is_default', 1);
        } else {
            if ($is_code) $this->db->where('lang_code', $language);
            else          $this->db->where('name', $language);
        }

        $sql = $this->db->get(db_prefix.'Languages');
        $rez = $sql->result_array();

        if(count($rez) == 1) return $rez[0]['id'];

        return 0;
    }                                                                                                               //________ get_language_id
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function Manage_pages($post)
    {
        $this->db->select('id');
        $this->db->from(db_prefix.'Pages');
        $this->db->order_by('taborder');
        $query = $this->db->get();
        $pos=$query->result_array();
        foreach($pos as $key=>$val)
        {    
            $this->db->update(db_prefix.'Pages', array('taborder'=>$key), array('id' => $val['id']));            
        }
        
        $this->db->select('*');
        $this->db->from(db_prefix.'Pages');
        $count=$this->db->count_all_results();
        $data['pagers']=pager_ex($post,$count,'sid');
        $params=$data['pagers']['params'];
        
        $this->db->select('*');
        $this->db->from(db_prefix.'Pages');
        $this->db->limit($params['limit'],$params['offset']);
        $this->db->order_by('taborder');
        $query = $this->db->get();
        $pages=$query->result_array();
        $data['pages']=$pages;
        return $data;
    }
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function Manage_page_action($post)
    {
        $result=array();
        $result['type']=$post['type'];
        
        if($post['type']=='edit')
        {
            $this->db->select('id');
            $this->db->from(db_prefix.'Pages');
            $this->db->where('sid',$post['sid']);
            $this->db->where('id!=',$post['id']);
            $query = $this->db->get();
            $res=$query->result_array();
            if(count($res)>0)
            {
                $result['status']=0;
                $result['error']='duplicate_entry';
            }
            else
            {
                $this->db->where('id',$post['id']);
                $this->db->update(db_prefix.'Pages',array('sid'=>$post['sid']));
                if($this->db->affected_rows()>0)
                {
                    $result['status']=1;   
                }
                else
                {
                    $result['status']=0;
                    $result['error']='not_saved';
                }                
            }
            $result['id']=$post['id'];
            $result['sid']=$post['sid'];
            
        }
        
        if($post['type']=='add')
        {
            $count=$this->db->count_all(db_prefix.'Pages');
            if($count>0)
            {            
                $pos=explode(",",$post['pos']);
                $id=$pos[count($pos)-1];
                $query=$this->db->query("select max(1*substr(sid,10))+1 as max_val from ".db_prefix."Pages where sid like 'new-page-%';");
                $arr=$query->result_array();
                
                //$max=count($arr)>0?$arr[0]['max_val']:1;
                $max=(count($arr)>0 && $arr[0]['max_val']!=NULL)?$arr[0]['max_val']:1;
                
                $this->db->select('taborder');
                $this->db->from(db_prefix.'Pages');
                $this->db->where('id',$id);
                $query = $this->db->get();
                $arr=$query->result_array();
                
                $cur_pos=count($arr)>0?$arr[0]['taborder']:0;
                
                $this->db->select('id');
                $this->db->from(db_prefix.'Pages');
                $this->db->where('taborder>',$cur_pos);
                $this->db->order_by('taborder');
                $query = $this->db->get();
                $pos=$query->result_array();
                foreach($pos as $key=>$val)
                {    
                    $this->db->update(db_prefix.'Pages', array('taborder'=>($cur_pos+$key+2)), array('id' => $val['id']));            
                }   
            }
            else
            {
                $max=1;
                $cur_pos=-1;
            }
            
            $this->db->insert(db_prefix.'Pages', array('sid'=>'new-page-'.$max, 'taborder'=>$cur_pos+1)); 
            $id=$this->db->insert_id();

            $this->db->select('*');
            $this->db->from(db_prefix.'Pages');
            $this->db->where('id',$id);        
            $query = $this->db->get();
            $res=$query->result_array();
            if(count($res)>0)
            {
                foreach($res[0] as $key=>$val)
                {
                    $result[$key]=$val;
                }     
            }
        }
        
        if($post['type']=='sort')
        {
            $pos=explode(",",$post['pos']);
            
            $this->db->select_min('taborder');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $query = $this->db->get();
            $arr=$query->result_array();
            $taborder=$arr[0]['taborder'];
            
            $this->db->select_max('taborder');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $query = $this->db->get();
            $arr=$query->result_array();
            $max=$arr[0]['taborder'];
            
            
            foreach($pos as $key=>$val)
            {    
                $this->db->update(db_prefix.'Pages', array('taborder'=>$taborder), array('id' => $val));
                if($taborder<$max)
                {
                    $taborder++;
                }
            }    
            $this->db->select('id');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $this->db->order_by('taborder');
            $query = $this->db->get(db_prefix.'Pages');
            $arr=$query->result_array();
            $res=array();
            foreach($arr as $val)
            {
                $res[]=$val['id'];
            }
            $result['pos']=implode(",",$res);
        }
        
        if($post['type']=='remove')
        {            
            $this->db->delete(db_prefix.'Pages',array('id'=>$post['id']));
            $result['id']=$post['id'];
            $result['status']=0;
            if( $this->db->affected_rows() > 0 )
            {
                $CI =& get_instance();        
                $CI->load->model("lang_manager_model"); 
                $CI->lang_manager_model->remove_language_data(9,$post['id']);
                $result['status']=1;
            }            
        }
        
        if($post['action']=='click')
        {
        $this->db->select('*');        
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'Pages page'));
        $this->db->where('page.id',$post['id']);
        $this->db->where('page.id = language_data.object_id');
        $this->db->where('language_data.object_type = 9');
        $query = $this->db->get();
        $data = $query->result_array();
        if ($post['status']==0)
        {
            if ( $query->num_rows() == 0 )
            {
                $result['id']=$post['id'];
                $result['error']='<{admin_manage_emsg_pages_emp}>';
                $result['status']=$post['status'];
                $result['disabled']=1;
                return $result;
            }
        }
        fb($data,"data");
        
            $this->db->where('id',$post['id']);
            $this->db->update(db_prefix.'Pages',array($post['type']=>$post['status']>0?0:1));
            $result['id']=$post['id'];
            $result['status']=$this->db->affected_rows()>0?($post['status']>0?0:1):$post['status'];            
        }
        return $result;
    }
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function Manage_news($post)
    {
        /* $this->db->select('id');
        $this->db->from(db_prefix.'Pages');
        $this->db->order_by('taborder');
        $query = $this->db->get();
        $pos=$query->result_array();
        foreach($pos as $key=>$val)
        {    
            $this->db->update(db_prefix.'Pages', array('taborder'=>$key), array('id' => $val['id']));            
        } */        
        $this->db->select('*');
        $this->db->from(db_prefix.'News');
        $count=$this->db->count_all_results();
        $data['pagers']=pager_ex($post,$count,'date');
        $params=$data['pagers']['params'];
        $this->db->select('*');
        $this->db->from(db_prefix.'News');
        $this->db->limit($params['limit'],$params['offset']);
        $this->db->order_by($params['column'],$params['order']);
        $query = $this->db->get();
        $news=$query->result_array();
        $data['news']=$news;
        return $data;
    }
    
    /**
    * Enter description here...
    *
    * @param array $post
    * @return array
    */
    function Manage_news_action($post)
    {
        $result=array();
        $result['type']=$post['type'];        
        if($post['type']=='edit')
        {
            $result['id']=$post['id'];
            if(isset($post['date']))
            {
                
                $query=$this->db->query("UPDATE `".db_prefix."News` SET `date` = '".convert_date($post['date'])."' WHERE `id` = '".$post['id']."';");
                if($this->db->affected_rows()>0)
                {
                    $result['status']=1;   
                }
                else
                {
                    $result['status']=0;
                    $result['error']='not_saved';
                }
                $result['date']=$post['date'];
            }
            
            if(isset($post['sid']))
            {
                $this->db->select('id');
                $this->db->from(db_prefix.'News');
                $this->db->where('sid',$post['sid']);
                $this->db->where('id!=',$post['id']);
                $query = $this->db->get();
                $res=$query->result_array();
                if(count($res)>0)
                {
                    $result['status']=0;
                    $result['error']='duplicate_entry';
                }
                else
                {
                    $this->db->where('id',$post['id']);
                    $this->db->update(db_prefix.'News',array('sid'=>$post['sid']));
                    if($this->db->affected_rows()>0)
                    {
                        $result['status']=1;   
                    }
                    else
                    {
                        $result['status']=0;
                        $result['error']='not_saved';
                    }                
                }
                $result['sid']=$post['sid'];
            }
        }
        
        if($post['type']=='add')
        {
            $query=$this->db->query("select max(1*substr(sid,6))+1 as max_val from ".db_prefix."News where sid like 'news-%';");
            $arr=$query->result_array();
            //$max=count($arr)>0?$arr[0]['max_val']:1;
            $max=(count($arr)>0 && $arr[0]['max_val']!=NULL)?$arr[0]['max_val']:1;
            
            $this->db->insert(db_prefix.'News', array('sid'=>'news-'.$max, 'date'=>date('Y-m-d'))); 
            $id=$this->db->insert_id();

            $this->db->select('*');
            $this->db->from(db_prefix.'News');
            $this->db->where('id',$id);        
            $query = $this->db->get();
            $res=$query->result_array();
            if(count($res)>0)
            {
                foreach($res[0] as $key=>$val)
                {
                    $result[$key]=$val;
                }                
            }
        }
        
        if($post['type']=='sort')
        {
            $pos=explode(",",$post['pos']);
            
            $this->db->select_min('taborder');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $query = $this->db->get();
            $arr=$query->result_array();
            $taborder=$arr[0]['taborder'];
            
            $this->db->select_max('taborder');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $query = $this->db->get();
            $arr=$query->result_array();
            $max=$arr[0]['taborder'];
            
            
            foreach($pos as $key=>$val)
            {    
                $this->db->update(db_prefix.'Pages', array('taborder'=>$taborder), array('id' => $val));
                if($taborder<$max)
                {
                    $taborder++;
                }
            }    
            $this->db->select('id');
            $this->db->from(db_prefix.'Pages');
            $this->db->where_in('id', $pos);
            $this->db->order_by('taborder');
            $query = $this->db->get(db_prefix.'Pages');
            $arr=$query->result_array();
            $res=array();
            foreach($arr as $val)
            {
                $res[]=$val['id'];
            }
            $result['pos']=implode(",",$res);
        }
        
        if($post['type']=='remove')
        {            
            $this->db->delete(db_prefix.'News',array('id'=>$post['id']));
            $result['id']=$post['id'];
            $result['status']=0;
            if( $this->db->affected_rows() > 0 )
            {
                $CI =& get_instance();        
                $CI->load->model("lang_manager_model"); 
                $CI->lang_manager_model->remove_language_data(6,$post['id']);
                $result['status']=1;
            }        
        }
        
        if($post['action']=='click')
        {
               
                //Get news item
        $this->db->select('*');        
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id',$post['id']);
        $this->db->where('news.id = language_data.object_id');
        $this->db->where('language_data.object_type = 6');
        $query = $this->db->get();
        $data = $query->result_array();
        if ($post['status']==0)
        {
            if ( $query->num_rows() == 0 )
            {
                $result['id']=$post['id'];
                $result['error']='<{admin_manage_emsg_news_emp}>';
                $result['status']=$post['status'];
                $result['disabled']=1;
                return $result;
            }
        }
        fb($data,"data");
        //$this->news_get($post['id']);
        fb($post['id'] , "post['id']"); 
            $this->db->where('id',$post['id']);
            $this->db->update(db_prefix.'News',array($post['type']=>$post['status']>0?0:1));
            $result['id']=$post['id'];
            $result['status']=$this->db->affected_rows()>0?($post['status']>0?0:1):$post['status']; 
            fb($result,"result");            
        }
        return $result;
    }
    
    
    /**
    * Enter description here...
    *
    * @author Konstantin X 
    * @param array $data
    * @return array
    */
    
    function page_get($data){
        /**************************************************************
    *   @author Konstantin X | 9:36 31.05.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $this->db->select('descr');
        $this->db->where('language_id', $data['language_id']);
        $this->db->where('object_id', $data['object_id']);
        $this->db->where('object_type', '9');
        $sql = $this->db->get(db_prefix.'Language_data');
        $rez = $sql->result_array();
        //print_r($rez);
        return $rez[0]['descr'];
    }                                                                                                               //________ page_get
    /**
    * Enter description here...
    *
    * @author Konstantin X 
    * @param array $data
    * @return array
    */
    function page_get_default($data){
        /**************************************************************
    *   @author Konstantin X | 17:08 15.08.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $this->db->select('descr');
        $this->db->where('language_id', '-1');
        $this->db->where('object_id', $data['object_id']);
        $this->db->where('object_type', '9');
        $sql = $this->db->get(db_prefix.'Language_data');
        $rez = $sql->result_array();
        //print_r($rez);
        return $rez[0]['descr'];
    }                                                                                                               //________ page_get_default
    /**
    * Enter description here...
    *
    * @author Konstantin X 
    * @param array $data
    * @return array
    */
    function page_set($data){
        /**************************************************************
    *   @author Konstantin X | 16:08 15.08.2008
    *
    *   @return ARRAY
    ***************************************************************/
        $this->db->select('descr');
        $this->db->where('language_id', $data['language_id']);
        $this->db->where('object_id', $data['object_id']);
        $this->db->where('object_type', '9');
        $sql = $this->db->get(db_prefix.'Language_data');
        if($sql->num_rows() == 1)
        {
            $this->db->set('descr',$data['descr']);
            $this->db->where('language_id', $data['language_id']);
            $this->db->where('object_id', $data['object_id']);
            $this->db->where('object_type', '9');
            $this->db->update(db_prefix.'Language_data');
        }else{
            $this->db->set('descr',$data['descr']);
            $this->db->set('language_id', $data['language_id']);
            $this->db->set('object_id', $data['object_id']);
            $this->db->set('object_type', '9');
            $this->db->insert(db_prefix.'Language_data');
        }

        if($this->db->affected_rows() == 1)
        {
            return true;
        }else{
            return false;
        }
    }                                                                                                               //________ page_set
    //________________________________________________________________________________________________________ PAGE EDIT
    

    /**
    * reads all config needed for "hostings settings", adds them to the $data array
    *
    * @param array $data
    * @return array
    *
    * @author 
    * @copyright 2008
    */
    function hosting_get($data)
    {
        if(!isset($data)||!is_array($data))
        {
            $data=array();
        }
        $data = config_get('SYSTEM','HOSTING');
        fb($data,'hosting get');
        return $data;
    }

        function domain_get($data)
    {
        if(!isset($data)||!is_array($data))
        {
            $data=array();
        }
        $data = config_get('SYSTEM','REGISTRAR', 'DIRECTI');
        fb($data,'domain get');
        return $data;
    }
    
    /**
    * saves $data array to <DOMAIN> section in ht_sys_config.cfg
    *
    * @param array $data
    * @return boolean
    *
    * @author Korchinskij G.G.
    * @copyright 2009
    */
    function domain_set($data)
    {
        if(!isset($data)||!is_array($data))
        {
            return false;
        }    
        foreach ($data as $key => $value)
        {
            if(in_array($key,array('https_url','debug')))
            {
                switch (mb_strtolower($value))
                {
                case "true":
                    $data[$key]='1';
                    break;
                case "false":
                    $data[$key]='0';
                    break;
                }
            }
        }        
        config_set($data, 'SYSTEM', 'REGISTRAR', 'DIRECTI');
        //config_set($data['DIRECTI']['service_password'], 'SYSTEM',  'REGISTRAR', 'DIRECTI','service_password');
        //config_set($data['DIRECTI']['service_langpref'], 'SYSTEM', 'REGISTRAR', 'DIRECTI','service_langpref');
        //config_set($data['DIRECTI']['service_parentid'], 'SYSTEM', 'REGISTRAR', 'DIRECTI', 'service_parentid');
        //config_set(($data['DIRECTI']['debug']=='true')?'1':'0', 'SYSTEM', 'REGISTRAR', 'DIRECTI','debug');
        //config_set(($data['DIRECTI']['https_url']=='true')?'1':'0', 'SYSTEM', 'REGISTRAR', 'DIRECTI', 'https_url');
        return true;
    }

    /**
    * saves $data array to <HOSTING> section in ht_sys_config.cfg
    *
    * @param array $data
    * @return boolean
    *
    * @author Korchinskij G.G.
    * @copyright 2009
    */
    function hosting_set($data)
    {
        if(!isset($data)||!is_array($data))
        {
            return false;
        }
        //set variables in ht_sys_config.cfg
        //        config_set($data['admin_email'], 'SYSTEM', 'HOSTING', 'admin_email');
        //config_set($data['host_charset'], 'SYSTEM', 'HOSTING', 'host_charset');
        //        config_set(($data['host_in_html']=='true')?'1':'0', 'SYSTEM', 'HOSTING', 'host_in_html');
        //        config_set(($data['host_use_smtp']=='true')?'1':'0', 'SYSTEM', 'HOSTING', 'host_use_smtp');
        config_set($data['host_host'], 'SYSTEM', 'HOSTING', 'host_host');
        config_set(intval($data['host_port']), 'SYSTEM', 'HOSTING', 'host_port');
        //        config_set(($data['host_use_auth']=='true')?'1':'0', 'SYSTEM', 'HOSTING', 'host_use_auth');
        config_set($data['host_user'], 'SYSTEM', 'HOSTING', 'host_user');
        config_set($data['host_pass'], 'SYSTEM', 'HOSTING', 'host_pass');
        //        config_set($data['send_to_count'], 'SYSTEM', 'HOSTING', 'send_to_count');
        return true;
    }



}
?>
