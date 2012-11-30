<?php
/**
 * 
 * THIS FILE CONTAINS Lang_manager_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH LANGUAGES
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Lang_manager_model extends Model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Lang_manager_model()
    {
        parent::Model();
    }
    /**
     * Get undefined language variables
     *
     * @param integer $lang_id
     * @return mixed
     */
    function Get_undefined_lang_vars($lang_id=1)
    {
        $this->db->select('key_name');
        $this->db->like('content','UNDEFINED-<{%}>');
        $this->db->where('language_id',$lang_id);
        $this->db->from(db_prefix.'Interface_language');
        $query=$this->db->get();
        return array_transform($query->result_array(),false,'key_name');
    }
	/**
	 * Validate a field length 
	 *
	 * @param array $post
	 * @return array
	 */
    function Validate_ex($post)
    {
        $errors=array();
        $section=$post['object_type'];
        $data=$this->add_panel_vars_ex($post,$post['object_type']);
        foreach($data['fields'] as $key=>$field)
        {
            if($field['enable'])
            {
                $value=isset($post[$key]) ? $post[$key] : "";
                if((mb_strlen($value)<$field['min']||mb_strlen($value)>$field['max']))
                {
                    $errors[]=$key;
                }
            }
        }

        switch ($section)
        {
        case 2:
            break;
        }
        return $errors;
    }

    /**************************************************************
    *    add_panel_vars_ex
    *    @author onagr
    ***************************************************************/
    /**
     * Add variables to the template
     *
     * @author onagr
     * @param array $data
     * @param integer $section
     * @return array
     */
    function Add_panel_vars_ex($data,$section)
    {
        //Отдельное условие для шаблонов в html формате
        $temp_section=0;
        if($section==13)
        {
            $section=2;
            $temp_section=13;
        }
        if($section==14)
        {
            $section=8;
            $temp_section=14;
        }
        $fields=array();
        $fields['name']=array();
        $fields['name']['name']='';
        $fields['name']['value']=isset($data['name']) ? $data['name'] : '';
        $fields['name']['type']='text';
        $fields['name']['min']=1;
        $fields['name']['max']=100;
        $fields['name']['enable']=true;
        $fields['name']['constants']=false;

        $fields['descr']=array();
        $fields['descr']['name']='';
        $fields['descr']['value']=isset($data['descr']) ? $data['descr'] : '';
        $fields['descr']['type']='textarea';
        $fields['descr']['min']=1;
        $fields['descr']['max']=5000;
        $fields['descr']['enable']=true;
        $fields['descr']['constants']=false;

        $fields['add']=array();
        $fields['add']['name']='';
        $fields['add']['value']=isset($data['add']) ? $data['add'] : '';
        $fields['add']['type']='textarea';
        $fields['add']['min']=1;
        $fields['add']['max']=5000;
        $fields['add']['enable']=false;
        $fields['add']['constants']=false;
        

        //Создание массива временных javascript переменных
        $temp_vars_set= array();
        $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
        $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/lang_manager.js";
        $temp_vars_set['id']=isset($data['id']) ? $data['id'] : "0";
        $temp_vars_set['object_type']=$data['object_type'];
        $temp_vars_set['referer']=isset($data['referer']) ? $data['referer'] : "";
        $temp_vars_set['template_name']=isset($data['template_name'])?$data['template_name']:"";

        //Массив сообщений по умолчанию
        $messages = array();
        $messages['saved_ok'] = "<{admin_lang_manager_msg_ok_saved_default}>";

        //Массив ошибок по умолчанию
        $mess_err = array();
        $mess_err['0'] = "<{admin_msg_er_0000}>";
        $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
        $mess_err['not_saved'] = "<{admin_lang_manager_msg_er_not_saved}>";
        $mess_err['name']="<{admin_lang_manager_msg_er_subject}>";
        $mess_err['descr']="<{admin_lang_manager_msg_er_message}>";
        $mess_err['add']="<{admin_lang_manager_msg_er_additional}>";
        
        //Добавление переменных в зависимости от типа
        switch ($section)
        {
        case 2:
            //**************************sys_emails*******************************
            //Константы страницы
            $data['title']="<{admin_edit_sys_template_header_subject}>";
            $data['comment']="<{admin_edit_sys_template_header_comment}>";
            if($temp_section==13)
            {
            $data['title']="<{admin_edit_sys_template_html_header_subject}>";
            $data['comment']="<{admin_edit_sys_template_html_header_comment}>";
            }
            $data['default_lang']=replace_lang("<{admin_edit_sys_template_default_language}>");

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_edit_sys_template_msg_er_subject}>";
            $mess_err['descr']="<{admin_edit_sys_template_msg_er_message}>";

            $this->db->select('*, email_key as `key`');
            $query = $this->db->get_where(db_prefix.'System_emails', array('id' => $data['id']));
            $temp=$query->result_array();
            if(count($temp)>0)
            {
                $data['template_name']=$temp[0]['key'];
                $data['admins']=($temp[0]['email_type']=='admin') ? true : false;
            }
            //$constants=get_template_variables($data['id']);
            $CI=&get_instance();
            $CI->load->model("mail_model");
            $constants=$CI->mail_model->get_template_replace_keys($temp[0]['key']);
            foreach($constants as $key=>$value)
            {$constants[$key] = "##".$value."##";}
            $data['constants']=$constants;

            //Создание массива полей
            $fields['name']['name']='<{admin_edit_sys_template_subject}>';
            $fields['descr']['name']='<{admin_edit_sys_template_message}>';
            $fields['name']['constants']=true;
            $fields['descr']['constants']=true;

            //***********************end_of_sys_emails***************************
            break;
        case 3:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']= $data['id'] ? "<{admin_product_group_edit_header_subject}>" : "<{admin_product_group_add_header_subject}>";
            $data['comment']="<{admin_product_group_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_product_group_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_product_group_edit_msg_er_message}>";

            //Создание массива полей
            $fields['name']['name']='<{admin_product_group_edit_subject}>';
            $fields['descr']['name']='<{admin_product_group_edit_message}>';
            $fields['descr']['min']=0;

            //***********************end_of_sys_emails***************************
            break;
        case 4:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']="<{admin_product_list_edit_header_subject}>";
            $data['comment']="<{admin_product_list_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_product_list_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_product_list_edit_msg_er_message}>";

            //Создание массива полей
            $fields['name']['name']='<{admin_product_list_edit_subject}>';
            $fields['descr']['name']='<{admin_product_list_edit_message}>';

            //***********************end_of_sys_emails***************************
            break;
        case 6:
            //**************************menage_pages*******************************

            //Константы страницы
            $data['title']="<{admin_manage_news_edit_header_subject}>";
            $data['comment']="<{admin_manage_news_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_manage_news_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_manage_news_edit_msg_er_message}>";
            $mess_err['add']="<{admin_manage_news_edit_msg_er_additional}>";

            //Создание массива полей
            $fields['name']['name']='<{admin_manage_news_edit_subject}>';
            $fields['descr']['name']='<{admin_manage_news_edit_message}>';
            $fields['add']['name']='<{admin_manage_news_edit_additional}>';
            $fields['add']['enable']=true;

            //***********************end_of_sys_emails***************************
            break;
        case 8:
            //**************************sys_emails*******************************
            //Константы страницы
            $data['title']="<{admin_newsletter_tmpl_template_header_subject}>";
            $data['comment']="<{admin_newsletter_tmpl_template_header_comment}>";
            if($temp_section==14)
            {
            $data['title']="<{admin_newsletter_tmpl_template_html_header_subject}>";
            $data['comment']="<{admin_newsletter_tmpl_template_html_header_comment}>";
            }

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_newsletter_tmpl_template_msg_er_name}>";
            $mess_err['descr']="<{admin_newsletter_tmpl_template_msg_er_subject}>";
            $mess_err['add']="<{admin_newsletter_tmpl_template_msg_er_message}>";

            $this->db->select('*, email_key as `key`');
            $query = $this->db->get_where(db_prefix.'System_emails', array('id' => $data['id']));
            $temp=$query->result_array();
            if(count($temp)>0)
            {
                $data['template_name']=$temp[0]['id'];
                $data['admins']=($temp[0]['email_type']=='admin') ? true : false;
            }
            //$constants=get_template_variables($data['id']);
            $CI=&get_instance();
            $CI->load->model("mail_model");
            //$constants=$CI->mail_model->get_template_replace_keys($temp[0]['key']);
            $sys_replace_values = $CI->mail_model->Get_sys_replace_keys(true);
            $dyn_replace_values = $CI->mail_model->Get_dyn_replace_keys("user", 0, true);

            $constants = array_merge($sys_replace_values, $dyn_replace_values);
            foreach($constants as $key=>$value)
                {$constants[$key] = "##".$value."##";}
                $data['constants']=$constants;

            //Создание массива полей
            $fields['name']['name']='<{admin_newsletter_tmpl_template_name}>';
            $fields['descr']['name']='<{admin_newsletter_tmpl_template_subject}>';
            $fields['add']['name']='<{admin_newsletter_tmpl_template_message}>';

            $fields['descr']['constants']=true;
            $fields['add']['constants']=true;

            $fields['descr']['type']='text';
            if($temp_section==14)
            {
            $fields['name']['enable']=false;
            }
            $fields['add']['enable']=true;
        //***********************end_of_emails***************************
            break;
        case 9:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']="<{admin_menage_pages_edit_header_subject}>";
            $data['comment']="<{admin_menage_pages_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_menage_pages_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_menage_pages_edit_msg_er_message}>";
            $mess_err['add']="<{admin_menage_pages_edit_msg_er_additional}>";

            $fields['name']['name']='<{admin_menage_pages_edit_subject}>';
            $fields['descr']['name']='<{admin_menage_pages_edit_message}>';
            $fields['descr']['max']=64000;
            $fields['add']['name']='<{admin_menage_pages_edit_additional}>';
            $fields['add']['enable']=true;
            $fields['add']['min']=0;

            //***********************end_of_menage_pages***************************
            break;
        case 10:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']= $data['id'] ? "<{admin_member_control_suspend_reason_edit_header_subject}>" : "<{admin_member_control_suspend_reason_add_header_subject}>";
            $data['comment']="<{admin_member_control_suspend_reason_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_member_control_suspend_reason_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_member_control_suspend_reason_edit_msg_er_message}>";

            $fields['name']['name']='<{admin_member_control_suspend_reason_edit_subject}>';
            $fields['descr']['name']='<{admin_member_control_suspend_reason_edit_message}>';
            //***********************end_of_sys_emails***************************
            break;
        case 11:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']= $data['id'] ? "<{admin_config_add_fields_lang_edit_header_subject}>" : "<{admin_config_add_fields_lang_add_header_subject}>";
            $data['comment']="<{admin_config_add_fields_lang_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_config_add_fields_lang_msg_er_subject}>";
            $mess_err['descr']="<{admin_config_add_fields_lang_msg_er_message}>";

            $fields['name']['name']='<{admin_config_add_fields_lang_subject}>';
            $fields['descr']['name']='<{admin_config_add_fields_lang_message}>';
            //***********************end_of_sys_emails***************************
            break;
         case 12:
            //**************************menage_pages*******************************
            //Константы страницы
            $data['title']= $data['id'] ? "<{admin_config_ban_ip_lang_edit_header_subject}>" : "<{admin_config_ban_ip_lang_add_header_subject}>";
            $data['comment']="<{admin_config_ban_ip_lang_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_config_ban_ip_lang_msg_er_subject}>";

            $fields['name']['name']='<{admin_config_ban_ip_lang_subject}>';
            $fields['descr']['enable']=false;
            //***********************end_of_sys_emails***************************
            break;
        case 15:
            //**************************member_groups*******************************
            //Константы страницы
            $data['title'] = $data['id'] ? "<{admin_member_group_edit_header_subject}>" : "<{admin_member_group_add_header_subject}>";
            $data['comment']="<{admin_member_group_edit_header_comment}>";

            //Создание массива сообщений на странице

            //Создание массива ошибок на странице
            $mess_err['name']="<{admin_member_group_edit_msg_er_subject}>";
            $mess_err['descr']="<{admin_member_group_edit_msg_er_message}>";

            //Создание массива полей
            $fields['name']['name']='<{admin_member_group_edit_subject}>';
            $fields['descr']['name']='<{admin_member_group_edit_message}>';
            $fields['descr']['min']=0;

            //***********************end_of_member_groups***************************
            break;
        }

        foreach($fields as $key=>$field)
        {
            $temp_vars_set[$key.'_max']=strval($field['max']);
            $temp_vars_set[$key.'_min']=strval($field['min']);
            if($field['constants'])
            {
                $temp_vars_set['constants_'.$key]='true';
            }
        }
        
        if(!isset($data['title']))
        {
            $data['title']="<{admin_lang_manager_header_subject_undefined}>";
            $data['comment']="<{admin_lang_manager_header_comment_undefined}>";
            $mess_err['undefined_type']=array('display'=>true, 'text'=>"<{admin_lang_manager_msg_er_undefined_type}>");
        }

        $data['temp_vars_set']=$temp_vars_set;
        $data['messages'] = $messages;
        $data['mess_err'] = $mess_err;
        $data['fields']=$fields;
        return $data;
    }
    /*   add_panel_vars_ex  */

	/**
	 * Get all or one language
     *
     * @param mixed $id
	 * @return array
	 */
    function Get_languages($id=false)
    {
    if(intval($id)>0)
    {
        $this->db->where('id',$id);
    }
    $query = $this->db->get(db_prefix.'Languages');
    $langs=$query->result_array();
    return $langs;
    }
	/**
	 * Enter description here...
	 *
	 * @param integer $object_type
	 * @param mixed $id
	 * @return array
	 */
    function Get_import_languages($object_type,$id)
    {
        if(intval($id)>0)
        {
            $this->db->select('langs.id,langs.name');
            $this->db->from(db_prefix.'Languages as langs');
            $this->db->join(db_prefix.'Language_data as lang_data', 'langs.id = lang_data.language_id', 'LEFT');
            $this->db->where('lang_data.object_type',$object_type);
            $this->db->where('lang_data.object_id',$id);
            $query = $this->db->get();
            $langs=$query->result_array();
            return $langs;
        }
        return array();
    }
	/**
	 * Get current language of admin
	 *
	 * @return mixed
	 */
    function Get_current_language()
    {
        $CI =& get_instance();
        if(isset($CI->admin_auth_model->admin_id) && $CI->admin_auth_model->admin_id!='')
        {
            $this->db->select('language_id');
            $query = $this->db->get_where(db_prefix.'Admins', array('id' => $CI->admin_auth_model->admin_id),1);
            $result=$query->result_array();
            if(count($result)>0 && intval($result[0]['language_id'])>0)
            {
                return $result[0]['language_id'];
            }
        }
        $this->db->select('id');
        $query = $this->db->get_where(db_prefix.'Languages', array('is_default' => 1),1);
        $result=$query->result_array();
        if(count($result)>0)
        {
            return $result[0]['id'];
        }
        return 0;
    }
	/**
	 * Set current language for admin
	 *
	 * @param mixed $lang_id
	 */
    function Set_current_language($lang_id)
    {
        $CI =& get_instance();
        $query = $this->db->get_where(db_prefix.'Languages', array('id' =>intval($lang_id)),1);
        if($query->num_rows()>0 and ($CI->admin_auth_model->admin_id))
        {
            $this->db->update(db_prefix.'Admins', array('language_id'=>intval($lang_id)),"id = ".$CI->admin_auth_model->admin_id);
        }
    }
	/**
	 * Get new id 
	 *
	 * @param integer $type
	 * @return integer
	 */
    function Get_new_id($type)
    {
        $CI =& get_instance();
        switch ($type)
        {
        case 3:
            $CI->load->model("product_group_model");
            $id = $CI->product_group_model->get_new_id();
            return $id;
            break;
        case 8:
            $CI->load->model("newsletter_model");
            $id = $CI->newsletter_model->get_new_id();
            return $id;
            break;
        case 10:
            $CI->load->model("member_model");
            $id = $CI->member_model->get_new_id();
            return $id;
            break;
        case 15:
            $CI->load->model("member_group_model");
            $id = $CI->member_group_model->get_new_id();
            return $id;
            break;
        }
        return -1;
    }
	/**
	 * Enter description here...
	 *
	 * @param array $arr
	 * @param mixed $type
	 * @param mixed $names
	 * @param string $join_column
	 * @param mixed $sorting
	 * @param mixed $language_id
	 * @param mixed &$additional
	 * @return array
	 */
    function Combine_with_language_data($arr,$type,$names=false,$join_column='id',$sorting=false,$language_id=false,&$additional)
    {
        $additional['total']=count($arr);
        if(count($arr)>0)
        {
            $r=$this->get_language_data($type,array_transform($arr,false,$join_column),$language_id);
            foreach($arr as $key=>$temp)
            {
                if($names===false || isset($names['name']))
                {
                    $arr[$key][$names===false ? 'name' : $names['name']]=$r[$temp[$join_column]]['name'];
                }
                if($names===false || isset($names['descr']))
                {
                    $arr[$key][$names===false ? 'descr' : $names['descr']]=$r[$temp[$join_column]]['descr'];
                }
                if($names===false || isset($names['add']))
                {
                    $arr[$key][$names===false ? 'add' : $names['add']]=$r[$temp[$join_column]]['add'];
                }
            }
            if($sorting!==false)
            {
                if(isset($sorting['filter']) && count($sorting['filter']))
                {
                    $arr=result_array_like($arr,$sorting['filter']);

                }
                if($additional!==false)
                {
                    $additional['total']=count($arr);
                }
                if(isset($sorting['col']))
                {
                    $sorting['order']=isset($sorting['order']) ? $sorting['order'] : 'asc';
                    $arr=result_array_sort($arr,$sorting['col'],$sorting['order']);
                }
                $sorting['offset']=isset($sorting['offset']) ? $sorting['offset'] : 0;

                if(isset($sorting['offset']) && isset($sorting['limit']))
                {
                    $arr = array_slice($arr,$sorting['offset'],$sorting['limit']);
                }
            }

       }
        return $arr;
    }
	/**
	 * Delete language
	 *
	 * @param integer $language_id
	 * @return boolean
	 */
    function Remove_language($language_id)
    {
        if(isset($language_id))
        {
            $this->db->where('language_id',$language_id);
            if($this->db->delete(db_prefix.'Language_data'))
            {
                simple_admin_log('lang_manager_language_delete',$language_id);
                return true;

                $CI = &get_instance();
                $CI->load->model('auth_model');
                $default_language_id = $CI->auth_model->get_default_language();
                $this->db->update(db_prefix.'Admins', array('language_id'=>intval($default_language_id)),"language_id = ".$language_id);
                $this->db->update(db_prefix.'Users', array('language_id'=>intval($default_language_id)),"language_id = ".$language_id);
            }
        }
        simple_admin_log('lang_manager_language_delete',$language_id,true,'invalid_id');
        return false;
    }
	/**
	 * Delete language data
	 *
	 * @param integer $object_type
	 * @param integer $object_id
	 * @return boolean
	 */
    function Remove_language_data($object_type,$object_id=false)
    {
        $log_id=$object_id;
        if(isset($object_type))
        {
            $this->db->where('object_type',$object_type);
            if($object_id!=false)
            {
                $object_id=is_array($object_id) ? $object_id : array($object_id);
                $this->db->where_in('object_id',$object_id);
            }
            if($this->db->delete(db_prefix.'Language_data'))
            {
                simple_admin_log('lang_manager_delete',$log_id);
                return true;
            }
        }
        simple_admin_log('lang_manager_delete',$log_id,true,'deleting_error');
        return false;
    }


    /**
     * This function get language data
     *
     * @param int
     * @param int
     * @param int or array (person information array('person_id'=>(int),'person_type'=>(string)('user'/'admin')))
     * @return array
     *
     * @author onagr
     * @copyright 2008
     */
    function Get_language_data($object_type,$object_id=false,$language_id=false)
    {
        if(is_array($language_id))
        {
            if(isset($language_id['person_id']) && isset($language_id['person_type']))
            {
                $table=($language_id['person_type']=='user') ? 'Users' : 'Admins';
                $query = $this->db->get_where(db_prefix.$table, array('id' => $language_id['person_id']));
                $res=$query->result_array();
                //print_r($res);
                //echo $this->db->last_query();
                $language_id=count($res)>0 ? $res[0]['language_id'] :false;
            }
            else
            {
                $language_id=false;
            }
        }
        $CI =& get_instance();
        $CI->load->model("auth_model");
        $default_language = isset($CI->default_language_id) ? $CI->default_language_id : $CI->auth_model->get_default_language();

        $current_language = $language_id ? $language_id : intval($CI->auth_model->get_cookie_lang_id());

        if($object_id !== false && !is_array($object_id))
        {
            $object_id=array($object_id);
        }
        $this->db->select('language_data.object_id,language_data.language_id,language_data.name,language_data.descr,language_data.language_add as `add`');
        $this->db->from(db_prefix.'Language_data language_data');
        if($object_id !== false && is_array($object_id))
        {
            $this->db->where_in('language_data.object_id',$object_id);
        }
        $this->db->where('language_data.object_type',$object_type);
        $this->db->order_by('language_data.object_id asc, language_data.language_id asc');
        $query = $this->db->get();
        $language_data=$query->result_array();
        $result=array();
        foreach($language_data as $value)
        {
            if(isset($result[$value['object_id']]) && isset($result[$value['object_id']]['language_id']))
            {
                $lang_n = $value['language_id'];
                $lang_c = $result[$value['object_id']]['language_id'];
                if(($lang_c != $language_id && $lang_c != $current_language && $lang_c != $default_language) ||
                        ($lang_n==$default_language && $lang_c != $language_id && $lang_c != $current_language) ||
                        ($lang_n==$current_language && $lang_c != $language_id) ||
                        ($lang_n==$language_id))
                {
                    $result[$value['object_id']]=$value;
                    unset($result[$value['object_id']]['object_id']);
                }

            }
            else
            {
                $result[$value['object_id']]=$value;
                unset($result[$value['object_id']]['object_id']);
            }
        }

        if($object_id !== false && is_array($object_id))
        {
            $t_larr=array('<{admin_lang_manager_empty_name}>','<{admin_lang_manager_empty_descr}>','<{admin_lang_manager_empty_add}>');
            $t_larr=replace_lang(implode("<|*+-+*|>",$t_larr),$current_language);
            $t_larr=explode("<|*+-+*|>",$t_larr);
            $t_name=$t_larr[0];
            $t_descr=$t_larr[1];
            $t_add=$t_larr[2];
            $diff_keys=array_diff($object_id,array_keys($result));
            foreach($diff_keys as $val)
            {
                if(intval($val)>0)
                {
                    $result[$val]=array();
                    $result[$val]['language_id']=0;
                    $result[$val]['name']=$t_name.$val;
                    $result[$val]['descr']=$t_descr.$val;
                    $result[$val]['add']=$t_add.$val;
                }
                else
                {
                    $result[$val]=array();
                    $result[$val]['language_id']=0;
                    $result[$val]['name']='';
                    $result[$val]['descr']='';
                    $result[$val]['add']='';
                }
            }
        }
        return $result;
    }
	/**
	 * Get language data
	 *
	 * @param array $post
	 * @return array
	 */
    function Template_get($post)
    {
        $CI =& get_instance();
        $language_id=isset($post['language_id'])?$post['language_id']:$CI->default_language_id;
        $object_type=$post['object_type'];
        $id=$post['id'];
        $this->db->select('language_data.name,language_data.descr,language_data.language_add as `add`');
        $this->db->from(db_prefix.'Language_data language_data');
        $this->db->where('language_data.object_id',$id);
        $this->db->where('language_data.object_type',$object_type);
        $this->db->where('language_data.language_id',$language_id);
        $query = $this->db->get();
        $template=$query->result_array();

        $data=array();
        $data['template_name']=isset($post['template_name'])?$post['template_name']:"";
        $data['name']='';
        $data['descr']='';
        $data['add']='';
        $data['id']=$id;
        $data['language_id']=$language_id;
        $data['object_type']=$object_type;
        $data['referer']=isset($post['referer'])?$post['referer']:"";
        $data['action']=isset($post['action'])?$post['action']:"";

        if(count($template)>0 && $post['id'])
        {
            $data['name']=$template[0]['name'];
            $data['descr']=$template[0]['descr'];
            $data['add']=$template[0]['add'];
            $data['id']=$id;
        }
        return $data;
    }
	/**
	 * Set new language data
	 *
	 * @param array $post
	 * @return mixed
	 */
    function Template_set($post)
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_multi_language')!==true)
        {
            $CI=&get_instance();
            $post['language_id']=$CI->auth_model->get_default_language();
        }
        //*******End of functionality limitations********
        if(intval($post['id'])<=0)
        {
            $post['id'] = $this->get_new_id($post['object_type']);
            //adding html template in newwsletter
            if(intval($post['object_type'])==8)
            {
                $t_post=$post;
                $t_post['object_type']=14;
                $this->template_set($t_post);
            }
        }

        $vars=$this->add_panel_vars_ex($post,$post['object_type']);
        $data = array();
        foreach($vars['fields'] as $key=>$field)
        {
            if($field['enable'])
            {
                if(isset($post[$key]))
                {
                    if ($key == 'add')							//
						$data['language_add']=$post[$key];		//	add when rename tables
					else										//
						$data[$key]=$post[$key];
                }
            }
        }
        $query = $this->db->get_where(db_prefix.'Language_data', array('object_id' => $post['id'],'object_type' => $post['object_type'],'language_id' => $post['language_id']),1);
        if($query->num_rows()>0)
        {
            $this->db->where('language_data.object_id',$post['id']);
            $this->db->where('language_data.object_type',$post['object_type']);
            $this->db->where('language_data.language_id',$post['language_id']);
            $this->db->update(db_prefix.'Language_data language_data', $data);
        }
        else
        {
            $data['object_id']=$post['id'];
            $data['object_type']=$post['object_type'];
            $data['language_id']=$post['language_id'];
            $this->db->insert(db_prefix.'Language_data',$data);
        }
        if($this->db->affected_rows()>0)
        {
            return true;
        }
        else
        {
            return "not_saved";
        }
    }
}
?>
