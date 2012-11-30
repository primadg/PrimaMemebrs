<?php 
/**
 * 
 * THIS FILE CONTAINS Admin_auth_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */

/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH ADMIN AND ACCESS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Admin_control_model extends Model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Admin_control_model()
    {
        parent::Model();
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $access
	 * @param array $permissions
	 * @return unknown
	 */
    function getAccessLevel($access,$permissions)
    {
        foreach($permissions as $key => $value)
        {
            if(($access&$value)!=$value)
            {
                unset($permissions[$key]);
            }
        }
        return $permissions;
    }
	/**
	 * Enter description here...
	 *
	 * @param array $access_array
	 * @param array $permissions
	 * @return unknown
	 */
    function setAccessLevel($access_array,$permissions)
    {
        $access=0;
        foreach($access_array as $key => $value)
        {
            if($value!="false"&&isset($permissions[$key]))
            {
                $access=$access|$permissions[$key];
            }
        }
        return $access;
    }
	/**
	 * Enter description here...
	 *
	 * @param array $section_array
	 * @param string $section_name
	 * @return array
	 */
    function getAccessNames($section_array,$section_name)
    {
        $arr=array();
        if($section_array)
        {
            foreach($section_array as $key=>$value)
            {
                if($section_name=="email_newsletter")
                {
                    $arr[$key]="<{admin_sys_emails_tpl_".$key."}>";
                }
                else
                {
                    $arr[$key]="<{admin_access_level_".$section_name."_".$key."}>";
                }
            }
        }
        return $arr;
    }
    /**
     * Enter description here...
     *
     * @return array
     */
    function level_list()
    {
        $CI =& get_instance();

        $is_perm=$CI->admin_auth_model->checkAdminPermissions(ADMINISTRATOR_CONTROL);
        $admin_id= $CI->admin_id;
        $data=array();
        $data['is_perm']=$is_perm;
        $data['admin_id']=$admin_id;

        $this->db->select('access_levels.id,access_levels.name,access_levels.ACL,access_levels.ML');
        $this->db->from(db_prefix.'Access_levels access_levels');
        
        if(!$CI->is_super_admin)
        {
            $this->db->where('access_levels.id!=1');
        }

        if(!$is_perm)
        {
            $this->db->where('admins.id',$admin_id);
            $this->db->join(db_prefix.'Admins admins','admins.access_id=access_levels.id','left');
        }
        $this->db->order_by("id", "asc");
        

        $query = $this->db->get();
        $levels_list=$query->result_array();

        foreach($levels_list as $key => $value)
        {
            $levels_list[$key]['ACL']=$this->getAccessNames($this->getAccessLevel($levels_list[$key]['ACL'],$CI->admin_auth_model->access_category_array),"access_category");
            $levels_list[$key]['ML']=$this->getAccessNames($this->getAccessLevel($levels_list[$key]['ML'],$CI->admin_auth_model->email_newsletter_array),"email_newsletter");
        }

        $data['levels_list']=$levels_list;
        return $data;
    }
	/**
	 * Enter description here...
	 *
	 * @param integer $id
	 * @return array
	 */
    function access_level($id)
    {
        $perm_ACL=0;
        $perm_ML=0;
        $is_edit=false;
        $name="";
        if(isset($id)&&$id!=null)
        {
            $this->db->select('access_levels.id,access_levels.name,access_levels.ACL,access_levels.ML');
            $this->db->from(db_prefix.'Access_levels access_levels');
            $this->db->where('access_levels.id',$id);
            $query = $this->db->get();
            $levels_list=$query->result_array();
            if(count($levels_list)>0)
            {
                $name=$levels_list[0]['name'];
                $perm_ACL=$levels_list[0]['ACL'];
                $perm_ML=$levels_list[0]['ML'];
                $is_edit=true;
            }
        }

        $CI =& get_instance();

        $access_category=$this->getAccessNames( $CI->admin_auth_model->access_category_array,"access_category");
        $email_newsletter=$this->getAccessNames($CI->admin_auth_model->email_newsletter_array,"email_newsletter");

        $access_category_checked = $this->getAccessLevel($perm_ACL,$CI->admin_auth_model->access_category_array);
        $email_newsletter_checked  = $this->getAccessLevel($perm_ML,$CI->admin_auth_model->email_newsletter_array);

        $data=array();

        $data['name']=$name;
        $data['id']=$id;
        $data['is_edit']=$is_edit;
        $data['access_category']=array();

        foreach($CI->admin_auth_model->access_category_array as $key=>$value)
        {
            $data['access_category'][$key]=array();
            $data['access_category'][$key]['name']=$access_category[$key];
            $data['access_category'][$key]['checked']=isset($access_category_checked[$key])?true:false;
        }
        $data['email_newsletter']=array();
        foreach($CI->admin_auth_model->email_newsletter_array as $key=>$value)
        {
            $data['email_newsletter'][$key]=array();
            $data['email_newsletter'][$key]['name']=$email_newsletter[$key];
            $data['email_newsletter'][$key]['checked']=isset($email_newsletter_checked[$key])?true:false;
        }
        return $data;
    }
	/**
	 * Enter description here...
	 *
	 * @param array $post
	 * @return unknown
	 */
    function access_level_save($post)
    {
        $CI =& get_instance();
        
        if($post['id']==1 && !$CI->is_super_admin)
        {
            return "not_found";
        }
        
        $ACL = $this->setAccessLevel($post,$CI->admin_auth_model->access_category_array);
        $ML  = $this->setAccessLevel($post,$CI->admin_auth_model->email_newsletter_array);
        $id_is_exist=false;

        $this->db->select('access_levels.id');
        $this->db->from(db_prefix.'Access_levels access_levels');
        if(intval($post['id'])==$post['id'])
        {
            $this->db->where('access_levels.id !=',$post['id']);
        }
        $this->db->where('access_levels.name',$post['level_name']);
        $query = $this->db->get();
        $levels_list=$query->result_array();

        if(count($levels_list)>0)
        {
            return "name_is_exist";
        }

        $this->db->select('access_levels.id');
        $this->db->from(db_prefix.'Access_levels access_levels');
        $this->db->where('access_levels.id',$post['id']);
        $query = $this->db->get();
        $levels_list=$query->result_array();
        if(count($levels_list)>0)
        {
            $id_is_exist=true;
        }

        $data = array('name' => $post['level_name'],'ACL' => $ACL,'ML' => $ML);
        
        
        
        if($post['id']=="undefined")
        {
            $this->db->insert(db_prefix.'Access_levels', $data);
            $post['id']=$this->db->insert_id();
            if($this->db->affected_rows())
            {
                $result=send_system_subscription_to_admins('admin_access_level_change',array('access_level'=>$data['name']));
                return true;
            }
            else
            {
                return "not_added";
            }
        }
        else
        {
            if($post['id']==1)
            {
                unset($data['ACL']);
                $result=send_system_email_to_admin(1,'admin_access_level_change',array('access_level'=>$data['name']));                
            }
            else
            {
                $result=send_system_subscription_to_admins('admin_access_level_change',array('access_level'=>$data['name']));
            }
            $this->db->where('access_levels.id',$post['id']);
            $this->db->update(db_prefix.'Access_levels access_levels', $data);
            if($this->db->affected_rows()>0)
            {
                return true;
            }
            else if($id_is_exist)
            {
                return "not_updated";
            }
            else
            {
                return "not_found";
            }
        }
        return true;
    }
	/**
	 * Delete access level
	 *
	 * @param integer $id
	 * @return unknown
	 */
    function access_level_delete($id)
    {
        if($id==1)
        {
            return "not_deleted";
        }
        $this->db->select('access_levels.id,access_levels.name');
        $this->db->from(db_prefix.'Access_levels access_levels');
        $this->db->where('access_levels.id',$id);
        $query = $this->db->get();
        $levels_list=$query->result_array();
        if(count($levels_list)>0)
        {
            $query = $this->db->get_where(db_prefix.'Admins', array('access_id' => $id));
            if(count($query->result_array())>0)
            {
                return "is_in_use";
            }
            if($this->db->delete(db_prefix.'Access_levels', array('id' => $id))&&$this->db->affected_rows()>0)
            {
                $result=send_system_subscription_to_admins('admin_access_level_change',array('access_level'=>$levels_list[0]['name']));
                return true;
            }
            else
            {
                return "not_deleted";
            }
        }
        return "not_found";
    }

	/**
	 * Return list of admin
	 *
	 * @param unknown_type $post
	 * @return unknown
	 */
    function get_admin_list($post)
    {
        $CI =& get_instance();
        $is_perm=$CI->admin_auth_model->checkAdminPermissions(ADMINISTRATOR_CONTROL);
        $admin_id = $CI->admin_id;
        $data=array();
        $data['is_perm']=$is_perm;
        $data['admin_id']=$admin_id;
        $this->db->select('admins.id,admins.login,admins.access_id,admins.last_online');
        $this->db->from(db_prefix.'Admins admins');
        if(!$CI->is_super_admin)
        {
            $this->db->where('admins.id!=',1);
        }
        if(!$is_perm)
        {
            $this->db->where('admins.id',$admin_id);
        }
        $count=$this->db->count_all_results();
        $data['pagers']=pager_ex($post,$count,array('login','id','login','access_id','last_online'));
        $params=$data['pagers']['params'];

        $this->db->select('admins.id,admins.login, access_levels.name as access_level,admins.last_online');
        $this->db->from(db_prefix.'Admins admins');
        if(!$CI->is_super_admin)
        {
            $this->db->where('admins.id!=',1);
        }
        if(!$is_perm)
        {
            $this->db->where('admins.id',$admin_id);
        }
        $this->db->join(db_prefix.'Access_levels access_levels','admins.access_id=access_levels.id','left');
        $this->db->limit($params['limit'],$params['offset']);
        $this->db->order_by($params['column'],$params['order']);
        $query = $this->db->get();
        $levels_list=$query->result_array();
        $data['admin_list']=$levels_list;
        return $data;
    }

	/**
	 * Return info about admin
	 *
	 * @param integer $id
	 * @return array
	 */
    function admin_edit($id)
    {
        $CI =& get_instance();

        $is_perm=$CI->admin_auth_model->checkAdminPermissions(ADMINISTRATOR_CONTROL);
        $data=array();

        if($id!=null)
        {
            $this->db->select('admins.id,admins.login,admins.pwd,admins.email,admins.access_id');
            $this->db->from(db_prefix.'Admins admins');
            $this->db->where('admins.id',$id);
            $query = $this->db->get();
            $admin=$query->result_array();
            $data=$admin[0];
            $data['is_edit'] = true;
            $data['is_super'] = $data['id']==1;
        }
        else
        {
            $data['id'] = '';
            $data['login'] = '';
            $data['email'] = '';
            $data['access_id'] = 0;
            $data['is_edit']  = false;
            $data['is_super'] = false;
        }

        $data['pwd'] = '';
        $data['pwd_ret'] = '';
        $data['pwd_gen'] = false;


        if($is_perm)
        {
        $this->db->select('access_levels.id,access_levels.name');
        $this->db->from(db_prefix.'Access_levels access_levels');
        $this->db->where('access_levels.id!=1');
        $query = $this->db->get();
        $data['levels']=$query->result_array();
        }

        $data['is_perm']=$is_perm;
        return $data;
    }
	/**
	 * Add new or edit existing admin
	 *
	 * @param array $post
	 * @return mixed
	 */
    function admin_save($post)
    {
        $CI =& get_instance();

        if(isset($post['id']) && $post['id']==1 && !$CI->is_super_admin)
        {
            return "access_denied";
        }
        $re_login=false;
        $data=array();
        $admin_data=array();
        $data['login'] = $post['login'];
        if(!empty($post['pwd']))
        {
        $data['pwd'] =md5($post['pwd']);
        $admin_data['admin_password']=$post['pwd'];
        }
        $data['email'] = $post['email'];
        
        if(isset($post['access_id']) && !empty($post['access_id']))
        {
            $data['access_id'] = $post['access_id'];
            
            if(intval($post['access_id'])==1)
            {
                return "not_found";
            } 
        }
        //debug_response($post,"post");
        if($post['id']=="undefined")
        {
            if(!isset($post['access_id']) || empty($post['access_id']))
            {
                return "undefined_access";
            }
            
            $this->db->insert(db_prefix.'Admins', $data);
            $data['id']=$this->db->insert_id();
            if($this->db->affected_rows()>0)
            {            
                $access_level_info=$this->access_level($data['access_id']);
                $result=send_system_email_to_admin($data['id'],'your_admin_account_created',array('admin_password'=>$post['pwd']));
                $result=send_system_subscription_to_admins('admin_account_created',array(
                'created_admin_login'=>$data['login'],
                'created_admin_level'=>$access_level_info['name']
                ),$data['id']); 
                return true;
            }
            else
            {
                return "not_added";
            }
        }
        else
        {
            $this->db->select('admins.id,admins.login');
            $this->db->from(db_prefix.'Admins admins');
            $this->db->where('admins.id',$post['id']);
            $query = $this->db->get();
            $admins_list=$query->result_array();
            if(count($admins_list)>0)
            {
                $id_is_exist=true;                
                if($post['id']==$CI->admin_auth_model->uid)
                {
                    if(!empty($post['pwd'])||$post['login']!=$admins_list[0]['login'])
                    {
                    $re_login=true;    
                    }
                }
            }

            $this->db->where('admins.id',$post['id']);
            $this->db->update(db_prefix.'Admins admins', $data);
            if($this->db->affected_rows()>0)
            {
                $access_level_info=$this->access_level(isset($data['access_id']) ? $data['access_id'] : 1);
                $result=send_system_email_to_admin($post['id'],'your_admin_account_changed',array('admin_password'=>$post['pwd']));
                if($post['id']!=1)
                {
                    $result=send_system_subscription_to_admins('admin_account_changed',array(
                    'changed_admin_login'=>$data['login'],
                    'changed_admin_level'=>$access_level_info['name']
                    ),$post['id']); 
                }
                return $re_login?"re_login":true;                
            }
            else if($id_is_exist)
            {
                return "not_updated";
            }
            else
            {
                return "not_found";
            }
        }
        return true;
    }
	/**
	 * Delete admin
	 *
	 * @param unknown_type $id
	 * @return unknown
	 */
    function admin_delete($id)
    {
        $this->db->select('admins.id,admins.login,admins.access_id');
        $this->db->from(db_prefix.'Admins admins');
        $this->db->where('admins.id',$id);
        $query = $this->db->get();
        $admins_list=$query->result_array();
        if(count($admins_list)>0)
        {
            if($id==1)
            {
                return "not_deleted";
            }
            $admin_info=$admins_list[0];
            $access_level_info=$this->access_level($admin_info['access_id']);
            $result=send_system_email_to_admin($admin_info['id'],'your_admin_account_deleted');
            $result=send_system_subscription_to_admins('admin_account_deleted',array(
            'deleted_admin_login'=>$admin_info['login'],
            'deleted_admin_level'=>$access_level_info['name']
            ),$admin_info['id']); 
            
            if($this->db->delete(db_prefix.'Admins', array('id' => $id)) && $this->db->affected_rows()>0)
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
	/**
	 * Check for admin with such parameters
	 *
	 * @param array $data
	 * @return array
	 */
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
            $this->db->select('admins.id');
            $this->db->from(db_prefix.'Admins admins');
            $this->db->where('admins.'.$action,$value);
            if(isset($data['id'])&&$data['id']!="undefined")
            {
                $this->db->where('admins.id!=',$data['id']);
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

    /**************************************************************
    *    add_config_vars_ex
    *    @author onagr
    ***************************************************************/
    
    /**
     * Enter description here...
     *
     * @param array $data
     * @param string $section
     * @return array
     */
    
    function add_panel_vars_ex($data,$section)
    {
        $CI =& get_instance();

        switch ($section)
        {
        case "access_level":
            //**************************access_level*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['count']=count($CI->admin_auth_model->access_category_array);
            $temp_vars_set['panel_script']=base_url()."js/admin/admin_control/access_level.js";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['level_name']="<{admin_access_level_msg_er_level_name}>";
            $mess_err['empty_list']="<{admin_access_level_msg_er_empty_list}>";
            $mess_err['not_added']="<{admin_access_level_msg_er_not_added}>";
            $mess_err['not_updated']="<{admin_access_level_msg_er_not_updated}>";
            $mess_err['name_is_exist']="<{admin_access_level_msg_er_name_is_exist}>";
            $mess_err['not_found'] = "<{admin_access_level_msg_er_not_found}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_access_level***************************
            break;
        case "level_list":
            //**************************level_list*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/admin_control/level_list.js";
            $temp_vars_set['panel_hash']="level_list";
            
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_access_level_msg_ok_deleted}>";
            $data['messages'] = $messages;
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['not_deleted'] = "<{admin_access_level_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_access_level_msg_er_not_found}>";
            $mess_err['is_in_use'] = "<{admin_access_level_msg_er_is_in_use}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_access_level***************************
            break;
        case "administrators_list":
            //**************************level_list*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/admin_control/administrators_list.js";
            $temp_vars_set['panel_hash']="administrator_list";
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

            $data['mess_err'] = $mess_err;
            //***********************end_of_access_level***************************
            break;
        case "admin_edit":
            //**************************access_level*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['from_header']=isset($_POST['from_header'])?"true":"false";
            $temp_vars_set['panel_script']=base_url()."js/admin/admin_control/admin_edit.js";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['count']=count($CI->admin_auth_model->access_category_array);
            $temp_vars_set['id']=isset($data['id'])?$data['id']:"";
            $temp_vars_set['is_edit']=isset($data['is_edit'])&&$data['is_edit']?"true":"false";
            $temp_vars_set['password_protection0']="<{user_registration_password_protection0}>";
            $temp_vars_set['password_protection3']="<{user_registration_password_protection3}>";
            $temp_vars_set['password_protection4']="<{user_registration_password_protection4}>";
            $temp_vars_set['password_protection5']="<{user_registration_password_protection5}>";
            $temp_vars_set['password_not_match']="<{user_registration_password_not_match}>";
            $temp_vars_set['password_is_match']="<{user_registration_password_is_match}>";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
            $mess_err['login']="<{admin_admin_edit_msg_er_name}>";
            $mess_err['email']="<{admin_admin_edit_msg_er_email}>";
            $mess_err['not_added']="<{admin_admin_edit_msg_er_not_added}>";
            $mess_err['not_updated']="<{admin_admin_edit_msg_er_not_updated}>";
            $mess_err['not_found'] = "<{admin_admin_edit_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $mess_err['email_is_exist']="<{admin_admin_edit_msg_er_email_exist}>";
            $mess_err['name_is_exist']="<{admin_admin_edit_msg_er_name_is_exist}>";
            $mess_err['undefined_access']="<{admin_admin_edit_msg_er_undefined_access}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_access_level***************************
            break;
        }
        return $data;
    }
    /*   add_config_vars_ex  */
	/**
	 * Enter description here...
	 *
	 * @param array $post
	 * @param string $section
	 * @return array
	 */
    function panel_validate_ex($post,$section)
    {
        $errors=array();
        switch ($section)
        {
        case "access_level":
            //**************************access_level*******************************
            if(!isset($post['level_name'])||mb_strlen($post['level_name'])==0||mb_strlen($post['level_name'])>64)
            {$errors[]="level_name";}
            //***********************end_of_access_level***************************
            break;
        case "admin_edit":
            //**************************admin_edit*******************************
            if(( !isset($post['login']) or empty($post['login']) ) || ( mb_strlen($post['login']) <= 4 or mb_strlen($post['login']) > 32  )||( eregi("^[a-zA-Z]+[a-zA-Z0-9_-]*$",$post['login'])==false ))
            {$errors[]="login";}

            $temp=$post;
            $temp['name']='login';
            $temp['value']=$post['login'];
            $result=$this->value_exist($temp);
            if($result['is_error'])
            {$errors[]=$result['error_text'];}

            if(( !isset($post['email']) or empty($post['email']) ) || !preg_match("/^((([0-9a-z\-\_]+)\.)*)(([0-9a-z\-\_])+)@((([0-9a-z\-\_]+)\.)+)(([0-9a-z\-\_])+)$/i", $_POST['email']))
            {$errors[]="email";}

            $temp=$post;
            $temp['name']='email';
            $temp['value']=$post['email'];
            $result=$this->value_exist($temp);

            if($result['is_error'])
            {$errors[]=$result['error_text'];}

            if((( !isset($post['pwd']) or empty($post['pwd']) ) ||!check_admin_password($post['pwd']))&& !($post['id']!="undefined"&&empty($post['pwd'])))
            {$errors[]="pwd";}
            //***********************end_of_admin_edit***************************
            break;
        }

        return $errors;
    }

}
?>
