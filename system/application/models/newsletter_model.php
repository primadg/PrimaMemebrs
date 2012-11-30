<?php
/**
 * 
 * THIS FILE CONTAINS Newsletter_model CLASS
 *  
 * @package Needsecure
 * @author Peter Yaroshenko
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH NEWSLETTERS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Newsletter_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Newsletter_model()
    {
        parent::Model();
    }
    
    /**************************************************************
    *    add_panel_vars_ex
    *    @author onagr
    ***************************************************************/
    /**
     * Add variables to the temlpate
     *
     * @author onagr
     * @param array $data
     * @param string $section
     * @return array
     */
    function add_panel_vars_ex($data,$section)
    {
        switch ($section)
        {
        
        case "template_list":
            //**************************template_list*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/newsletter/template_list.js";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_newsletter_email_templates_msg_ok_deleted}>";
            
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['subject']="<{admin_newsletter_email_templates_msg_er_subject}>";
            $mess_err['message']="<{admin_newsletter_email_templates_msg_er_message}>";
            
            $mess_err['not_deleted'] = "<{admin_newsletter_email_templates_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_newsletter_email_templates_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_newsletter_email_templates_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_template_list***************************
            break;
            
         case "send_email":
            //**************************send_email*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/newsletter/send_email_ex.js";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['sended_ok'] = "<{admin_newsletter_email_templates_msg_sended_ok}>";
            $messages['deleted_ok'] = "<{admin_newsletter_email_templates_msg_ok_deleted}>";
            
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['subject']="<{admin_newsletter_email_templates_msg_er_subject}>";
            $mess_err['message']="<{admin_newsletter_email_templates_msg_er_message}>";
            
            $mess_err['not_deleted'] = "<{admin_newsletter_email_templates_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_newsletter_email_templates_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_newsletter_email_templates_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_send_email***************************
            break;
            case "email_list":
            //**************************template_list*******************************
            //Создание массива временных javascript переменных
            $temp_vars_set= array();
            
            $temp_vars_set['sending_text']="<{admin_newsletter_email_history_sending_text}>";
            $temp_vars_set['cancelText']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/newsletter/email_list.js";
            $data['temp_vars_set']=$temp_vars_set;

            //Создание массива сообщений на странице
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_newsletter_email_templates_msg_ok_deleted}>";
            
            $data['messages'] = $messages;

            //Создание массива ошибок на странице
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['subject']="<{admin_newsletter_email_templates_msg_er_subject}>";
            $mess_err['message']="<{admin_newsletter_email_templates_msg_er_message}>";
            $mess_err['time_limit']="<{admin_email_history_send_msg_er_time_limit}>";
            $mess_err['demo_limit']="<{admin_email_history_send_msg_er_demo_limit}>";
            
            $mess_err['not_deleted'] = "<{admin_newsletter_email_templates_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_newsletter_email_templates_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_newsletter_email_templates_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_template_list***************************
            break;
        }
        return $data;
    }
    /*   add_panel_vars_ex  */
    /**
     * Delete expired newsletter, delete all letters, delete from email queue or email history
     *
     * @param array $post
     * @return mixed
     */
    function History_remove($post)
    {
        if(isset($post['action']) && $post['action']=='delete')
        {
            $table_name=(isset($post['filters']) && intval($post['filters'][0])==2) ? 'Email_queue' : 'Email_history';
            //delete certain newsletter
            if(isset($post['id']) && intval($post['id'])>0)
            {
                $query = $this->db->get_where(db_prefix.$table_name, array('id' => $post['id']),1);
                $email_list=$query->result_array();
                if(count($email_list)>0)
                {
                    if($this->db->delete(db_prefix.$table_name, array('id' => intval($post['id']))) && $this->db->affected_rows()>0)
                    {
                        return true;
                    }
                    else
                    {
                        return "not_deleted";
                    }
                }
            }
            //delete expired newsletter
            if(isset($post['limit']) && $post['limit']=='expired')
            {
                $period = intval(config_get("system","config","history_kept"));
                if($period>0)
                {
                    $this->db->where("NOW()>DATE_ADD(date,INTERVAL '".$period."' DAY)");
                    if($this->db->delete(db_prefix.$table_name) && $this->db->affected_rows()>0)
                    {
                        return true;
                    }
                    else
                    {
                        return "not_deleted";
                    } 
                }                
            }
            //delete all newsletter
            if(isset($post['limit']) && $post['limit']=='all')
            {
                if($this->db->delete(db_prefix.$table_name) && $this->db->affected_rows()>0)
                {
                    return true;
                }
                else
                {
                    return "not_deleted";
                } 
            }
            return "not_found";
        }
        return "not_found";
    }
    /**
     * Get history info
     *
     * @param array $post
     * @return array
     */
    function History_info($post)
    {
        $data=array();
        $data['errs']=array();
        if(isset($post['action']) && $post['action']=='info' && isset($post['id']) && intval($post['id'])>0)
        {
            $table_name=(isset($post['filters']) && intval($post['filters'][0])==2) ? 'Email_queue' : 'Email_history';
            //print_r($post);
        
            $query = $this->db->get_where(db_prefix.$table_name, array('id' => $post['id']),1);
            $email_list=$query->result_array();
            if(count($email_list)>0)
            {
                $email_info=$email_list[0];
                $replace_values = unserialize($email_info['replace_values']);
                //replace all placeholders in email's subject and body to according values
                
                $CI=&get_instance();
                $subject = $CI->mail_model->replace_keys($email_info['tpl_subject'], $replace_values);
                //print_r($subject);
            
                $body = $CI->mail_model->replace_keys($email_info['tpl_text'], $replace_values);
                //print_r($body);
            
                $data['email_to'] = word_wrap($email_info['email'],60,0,' ');
                $data['email_to'] = output($data['email_to']);
                $data['subject'] = word_wrap($subject,60,0,' ');
                $data['subject'] = output($data['subject']);
                $data['message'] = word_wrap($body,60,0,' ');
                $data['message'] = output($data['message']);
            }
            else
            {
                $data['errs'][]="not_found";
            }
        }
        else
        {
            $data['errs'][]="not_found";
        }
        //print_r($data);
        
            
            
        return $data;
    }
    /**
     * Send email to queue
     *
     * @param array $newsletter
     * @return array
     */
    function Send_emails($newsletter)
    {
fb($newsletter,__FUNCTION__ . " newsletter");        
    	//print_r($newsletter);
        
        $CI=&get_instance();
        $this->db->select('users.id as id ,users.email as email, users.language_id as language_id');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        if(isset($newsletter[0]))
        {
            if(intval($newsletter[0])<0)
            {
                switch(intval($newsletter[0]))
                {
                case -1:
                    $this->db->where('account_status.suspended != 1');
                    $this->db->where('account_status.approve = 1');
                    $this->db->where('account_status.activate = 1');
                    $this->db->where('account_status.deleted != 1');
                    $this->db->where('account_status.expired != 1');        
                    break;
                case -2:
                    $this->db->where('account_status.expired = 1');
                    break;
                case -3:
                    $this->db->where('account_status.suspended = 1');
                    break;
                }
            }
            if(intval($newsletter[0])>0)
            {
                $this->db->where('users.id',intval($newsletter[0]));
            }
        }
        
        if(isset($newsletter[1]) && intval($newsletter[1])>0)
        {
            $this->db->join(db_prefix.'Protection as protection', 'users.id = protection.user_id', 'LEFT');
            $this->db->join(db_prefix.'Subscriptions as subscriptions', 'subscriptions.id = protection.subscr_id', 'LEFT');
            $this->db->join(db_prefix.'Products as products', 'products.id = protection.product_id', 'LEFT');
            $this->db->join(db_prefix.'Product_product_group as product_product_group', 'products.id = product_product_group.product_id', 'LEFT');
            $this->db->join(db_prefix.'Product_groups as product_groups', 'product_groups.id = product_product_group.product_group_id', 'LEFT');
            $this->db->where('product_groups.id',intval($newsletter[1]));
            $this->db->where('subscriptions.status = 1');
            
            if(isset($newsletter[2]) && intval($newsletter[2])>0)
            {
                $this->db->where('products.id',intval($newsletter[2]));    
            }
        }
        
        $query=$this->db->get();
        $users=$query->result_array();
        //print_r($users);
        $sys_replace_values = $this->mail_model->Get_sys_replace_keys();
        $template_info_cache=array();
        foreach($users as $user)
        {
            $template_info=array();
            $template_info['id']=intval($newsletter[3]);
            $template_info['subject']=isset($newsletter[4])?$newsletter[4]:"10";
            $template_info['body']=isset($newsletter[5])?$newsletter[5]:"10";
            if(intval($newsletter[3])>0)
            {
                if(isset($template_info_cache[intval($user['language_id'])]))
                {
                    $template_info=$template_info_cache[intval($user['language_id'])];
                }
                else
                {
                    $template_info=$CI->mail_model->get_system_email_data(intval($newsletter[3]),intval($user['language_id'])>0 ? intval($user['language_id']) : false);
                    $template_info_cache[intval($user['language_id'])]=$template_info;
                }
            }
            $dyn_replace_values = $this->mail_model->Get_dyn_replace_keys("user", $user['id']);
            $replace_values = array_merge($sys_replace_values, $dyn_replace_values);
            $CI->mail_model->push_email_to_queue($template_info['id'], $user['email'], $user['id'], 'user', 'newsletter', $replace_values, $template_info['subject'], $template_info['body']);
        }
        
        //print_r($users);
        return array();
        //print_r($post);
    }    
    /**
     * Get list of email history or email queue 
     *
     * @param array $post
     * @return array
     */
    function History($post)
    {
        $data=array();
        
        //selectors
        $data['type']['items']['1']='<{admin_newsletter_email_history_type_history}>';
        $data['type']['items']['2']='<{admin_newsletter_email_history_type_queue}>';
        $data['tpl_type']['items']['all']='<{admin_newsletter_email_history_tpl_type_all}>';
        $data['tpl_type']['items']['user']='<{admin_newsletter_email_history_tpl_type_user}>';
        $data['tpl_type']['items']['admin']='<{admin_newsletter_email_history_tpl_type_admin}>';
        $data['tpl_priority']['system']='<{admin_newsletter_email_history_tpl_priority_system}>';
        $data['tpl_priority']['newsletter']='<{admin_newsletter_email_history_tpl_priority_newsletter}>';
        //end_of_selectors
        
        //filter
        $data['type']['selected']=isset($post['filters']) && isset($post['filters'][0]) && array_key_exists($post['filters'][0],$data['type']['items']) ? $post['filters'][0] : '1';
        $data['tpl_type']['selected']=isset($post['filters']) && isset($post['filters'][1]) && array_key_exists($post['filters'][1],$data['tpl_type']['items']) ? $post['filters'][1] : 'all';
        $data['person']=isset($post['filters']) && isset($post['filters'][2]) ? $post['filters'][2] : '';
        $data['date_from']=isset($post['filters']) && isset($post['filters'][3]) ? $post['filters'][3] : '';
        $data['date_to']=isset($post['filters']) && isset($post['filters'][4]) ? $post['filters'][4] : '';
        $member=isset($post['member']) ? $post['member'] : 0;
        //end_of_filter
        
        $table_name=intval($data['type']['selected'])==2 ? 'Email_queue as emails' : 'Email_history as emails';
        
        $this->db->select("emails.id, emails.email_tpl_id, emails.date, emails.user_id, if (emails.user_type='admin', admins.login,users.login)  as login, emails.user_type, emails.priority");
        $this->db->from(array(db_prefix.$table_name));
        
        $this->db->join(db_prefix."Admins `admins`","emails.user_type='admin' AND emails.user_id=admins.id","left");
            
        $this->db->join(db_prefix."Users `users`","emails.user_type='user' AND emails.user_id=users.id","left");

        if($data['tpl_type']['selected']!='all')
        {
            $this->db->where("emails.user_type",$data['tpl_type']['selected']);
        }
        
        if(validate_date($data['date_from']))
        {
            $this->db->where("DATE(emails.date)>=", convert_date($data['date_from']));
        }        
        if(validate_date($data['date_to']))
        {
            $this->db->where("DATE(emails.date)<=", convert_date($data['date_to']));
        }
        
        $this->db->where("(users.login like '".$this->db->escape_str($data['person']).($member>0 ? "" : "%")."' OR admins.login like '".$this->db->escape_str($data['person']).($member>0 ? "" : "%")."')");
        
        if (count($post)>0) {
            if(isset($post['pager']) && isset($post['pager'][0]))
            $page_res=page_and_sort($post,array('id', 'email_tpl_id', 'date', 'user_id', 'user_type', 'priority', 'replac_values', 'tpl_text'));
            else $page_res=page_and_sort($post,array('date'),2,'desc');
        }
        else $page_res=page_and_sort($post,array('date'),2,'desc');
        
        $data['pagers']=$page_res['pagers'];
        $history_list=$page_res['query']->result_array();
        
        $data['history_list']=$history_list;
        //print_r($data);
        
        return $data;
    }
    
    //Get new email template id
    /**
     * Get new email template id
     *
     * @return integer
     */
    function Get_new_id()
    {
        $this->db->insert(db_prefix.'System_emails',array('email_type' => 'newsletter')); 
        return $this->db->insert_id();    
    }
    /**
     * Get member list
     *
     * @param array $post
     * @return array
     */
    function Member_list($post)
    {
        $data=array();
        $p=isset($post['p']) ? $post['p'] : 1;
        $s=isset($post['s']) ? $post['s'] : 5;
        $q=isset($post['q']) ? $post['q'] : '';
        
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Users users'));
        $this->db->where('users.id > 1');
        $this->db->like('login',$q);
        $query = $this->db->get();
        $count = $query->result_array();
        $count=$count[0]['all_rows'];
        
        $limit=$s;
        $offset=$p*$s-$s;
        
        $this->db->select('users.id, users.login');
        $this->db->from(array(db_prefix.'Users users'));
        $this->db->where('users.id > 1');        
        $this->db->like('login',$q);
        $this->db->limit($limit,$offset);
        $this->db->order_by('login');
        $query = $this->db->get();
        $data['items'] = $query->result_array();
        $data['total'] = $count;
        return $data;
    }
    /**
     * Get template list
     *
     * @param array $post
     * @return array
     */
    function Template_list($post)
    {
        $data=array();
        $this->db->select('system_emails.id');
        $this->db->from(db_prefix.'System_emails system_emails');
        $this->db->where('system_emails.email_type','newsletter');
        $query = $this->db->get();
        
        $t=$query->result_array();
        $total=count($t);
        $data['pagers'] = pager_ex($post, $total, array('name'));
        $params = $data['pagers']['params'];
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,8,array('name'=>'name'),'id',array('col'=>$params['column'],'order'=>$params['order'],'limit'=>$params['limit'],'offset'=>$params['offset']),false,&$add);
        $data['emails']=$t;      
        return $data;
    }
    /**
     * Delete template
     *
     * @param array $post
     * @return mixed
     */
    function Template_delete($post)
    {
        if(isset($post['id']) && intval($post['id'])>0)
        {
            $id=$post['id'];
            $this->db->select('system_emails.id');
            $this->db->from(db_prefix.'System_emails system_emails');
            $this->db->where('system_emails.email_type','newsletter');
            $this->db->where('system_emails.id',$id);
            $query = $this->db->get();
            $emails=$query->result_array();
            if(count($emails)>0)
            {
                if($this->db->delete(db_prefix.'System_emails', array('id' => $id, 'email_type'=>'newsletter')) && $this->db->affected_rows()>0)
                {
                    $CI =& get_instance();        
                    $CI->load->model("lang_manager_model"); 
                    $CI->lang_manager_model->remove_language_data(8,$id);
                    $CI->lang_manager_model->remove_language_data(14,$id);
                    return true;
                }
                else
                {
                    return "not_deleted";
                }
            }
            return "not_found";
        }
        return "not_found";
    }
       
}

?>
