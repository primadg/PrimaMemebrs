<?php
/**
 * 
 * THIS FILE CONTAINS Admin_control CLASS
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
/**
 * Include file admin_controller.php
 */
require_once("admin_controller.php");

/**
 * 
 * THIS CLASS IS USED TO OUTPUT AND UPDATE ADMIN CONTROLS
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */

class Admin_control extends Admin_Controller 
{
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
	function Admin_control()
    {
        $this->access_bit=ADMINISTRATOR_CONTROL;
        parent::Admin_Controller();
        $this->load->model("admin_control_model"); 
        $this->load->model("user_model");   
        $this->load->model("mail_model");
    }
    
    //******************************************************
    //*************************TEST*************************
    //******************************************************
    //Must bee delete - TEST
    /**
     * @todo Must bee delete - TEST
     */
    function Sys_emails()
    {
        $this->mail_model->create_system_email_data();
        //print_r($this->mail_model->get_system_email_data_ex('admin_subscription_started'));
        //print_r($this->mail_model->get_system_email_data_ex('USER_TEMPLATES_NAMES'));
        //print_r($this->mail_model->get_system_email_data_ex('ADMIN_TEMPLATES_NAMES'));        
    }
    //******************************************************
    //******************************************************
    //******************************************************
    
    /**
     * CHECK ACCESS
     *
     * @return boolean
     */
    function _access_rules()
    {
        $fun = $this->uri->segment(2);
        
        switch($fun)
        {
        case 'admin_edit':
            if((isset($_POST['from_header']) && $_POST['from_header']=='true' && 
            isset($_POST['id']) && $_POST['id']==0 ) || ( 
            isset($_POST['action']) && $_POST['action']=='save' && 
            isset($_POST['id']) && $_POST['id'] == $this->admin_id))
            {
                return true;    
            }
            return false;
            break;
        case 'value_exist':
            return true;
            break;
            
        }
        return false;
    }
    /**
     * OUTPUT THE LIST OF ADMIN
     */
    function Admin_list()
    {   
        $data=array();
        $post=prepare_post();
        $errs=array();
        $data=$this->admin_control_model->get_admin_list($post);                
        $data=$this->admin_control_model->add_panel_vars_ex($data,"administrators_list");                
        $res = $this->load->view("/admin/admin_control/administrators_list", $data, true);
        make_response("output", $res, 1);
    }
    /**
     * CREATE, MODIFY OR DELETE ADMIN ACCOUNT.
     */
    function Admin_edit()
    {
        $CI =& get_instance();

        $data=array();
        $post=prepare_post();
        $errs=array();
        if(isset($post['id'])&&$post['id']==0&&$post['id']!=='undefined')
        {
        //debug_response($post['id'],"OLD_ID");
        $post['id']=$this->admin_auth_model->uid; 
        //debug_response($post['id'],"NEW_ID");        
        }        
        if((isset($post['id']) && $post['id']==1 && !$this->is_super_admin) || ($CI->admin_auth_model->isAccessDenied()!==false&&!(isset($post['id'])&&$post['id']==$this->admin_auth_model->uid)))
        {
            $CI->admin_auth_model->showAccessDenied();
            return;
        }
        
        if(!isset($post['action'])||$post['action']=="")
        {
            $id=isset($post['id'])?$post['id']:null;
            $data=$this->admin_control_model->admin_edit($id);        
            $data=$this->admin_control_model->add_panel_vars_ex($data,"admin_edit");			
            $res = $this->load->view("/admin/admin_control/admin_edit", $data, true);
            make_response("output", $res, 1);
        }
        else
        {
            //***********Functionality limitations***********
            if(isset($post['id']) && intval($post['id'])>0)
            {
                $functionality_enabled_error=Functionality_enabled('admin_admin_account_modify', intval($post['id']));
                if($functionality_enabled_error!==true)
                {   
                    $errs[]=$functionality_enabled_error;
                }
            }
            //*******End of functionality limitations********
            
            
            if($post['action']=="save" && count($errs)==0)
            {
            $log_action=isset($post['id']) ? 'account_modify' : 'account_add';
                        
                //debug_response($post,"post");       
                if($post['pwd_gen']=="true")
                {                
                    $post['pwd'] = $this->user_model->generate_password();
                    $post['pwd_ret']=$post['pwd'];
                }
                
                $errs=$this->admin_control_model->panel_validate_ex($post,"admin_edit");
                if(count($errs)==0)
                {   
                    $re_login=false;
                    $save_err=$this->admin_control_model->admin_save($post);
                    if($save_err==="re_login")
                    {
                    $save_err=true;
                    $re_login=true;
                    }                    
                    if($save_err===true)
                    {                
                        $data=$this->admin_control_model->get_admin_list($post);  
                        $data=$this->admin_control_model->add_panel_vars_ex($data,"administrators_list");
                        $mess[]="saved_ok";                            
                        
                        $details=array("Enity_id"=>isset($post['id']) ? $post['id'] : "");
                        $details["Completed"]="true";
                        admin_log($log_action, $details,$this->admin_auth_model->admin_id);
                        
                        if($re_login)
                        {
                            make_response("message", create_temp_vars_set($mess), 1);
                            return;                            
                        }
                        else
                        {
                            if(isset($post['from_header'])&&$post['from_header']=="true")
                            {
                            $data=$this->admin_auth_model->main_page_info($data);                            
                            $data['content'] = "";  
                            $res = $this->load->view("/admin/main.php", $data, true);
                            }
                            else
                            {
                            $res = $this->load->view("/admin/admin_control/administrators_list", $data, true);
                            }
                            make_response("output", $res, 1,create_temp_vars_set($mess));
                        }
                    }
                    else
                    {
                        $errs[]=$save_err;  
                    }                    
                }                                
            }
            
            if($post['action']=="delete"  && count($errs)==0)
            {
                $log_action='account_delete';
            
                if($CI->admin_auth_model->isAccessDenied()!==false)
                {
                    $CI->admin_auth_model->showAccessDenied();
                    return;       
                }
                
                $id=isset($post['id'])?$post['id']:null;
                $delete_err=$this->admin_control_model->admin_delete($id);
                if($delete_err===true)                 
                {                
                    $data=$this->admin_control_model->get_admin_list($post);  
                    $data=$this->admin_control_model->add_panel_vars_ex($data,"administrators_list");
                    $res = $this->load->view("/admin/admin_control/administrators_list", $data, true);
                    $mess[]="deleted_ok";
                    make_response("output", $res, 1,create_temp_vars_set($mess));
                    
                    $details=array("Enity_id"=>isset($post['id']) ? $post['id'] : "");
                    $details["Completed"]="true";
                    admin_log($log_action, $details,$this->admin_auth_model->admin_id);
                        
                }
                else
                {
                    $errs[]=$delete_err;
                }
                
            }
            if(count($errs)!=0)
            {
                $details=array("Enity_id"=>isset($post['id']) ? $post['id'] : "");
                $details["Completed"]="false";
                $details["Exception"]="Errors (".implode(",",$errs).")";
                if(isset($log_action))
                {
                    admin_log($log_action, $details,$this->admin_auth_model->admin_id);
                }
                make_response("error",create_temp_vars_set($errs), 1);
                fb($errs,__FUNCTION__." errs ");
            }            
        }            
        
    }

    /**
     * CHECK THE VALUE EXISTING
     *
     */
    function Value_exist()
    {
        $result=$this->admin_control_model->value_exist(prepare_post());
        validation_response($result['name'],$result['value'],$result['is_error'],$result['error_text']);
    }
    
    /**
     * OUTPUT THE LIST OF ADMIN'S LEVELS
     *
     */
    function Levels()
    {
        $data=$this->admin_control_model->level_list();   
        $data=$this->admin_control_model->add_panel_vars_ex($data,"level_list");                            
        $res = $this->load->view("/admin/admin_control/level_list", $data, true);
        make_response("output", $res, 1);
    }
    
    /**
     * CREATE, MODIFY OR DELETE ADMIN'S LEVEL.
     *
     */
    function Levels_edit()
    {
        $data=array();
        $post=prepare_post();
        $errs=array();

        $CI =& get_instance();
        
        if($CI->admin_auth_model->isAccessDenied()!==false)
        {
            $CI->admin_auth_model->showAccessDenied();
            return;       
        }
        
        if(count($post)!=0)
        {
            if($post['action']=="")
            {
                $id=isset($post['id'])?$post['id']:null;
                $data=$this->admin_control_model->access_level($id);        
                $data=$this->admin_control_model->add_panel_vars_ex($data,"access_level");			
                $res = $this->load->view("/admin/admin_control/access_level", $data, true);
                make_response("output", $res, 1);
            }
            
            //***********Functionality limitations***********
            if(in_array($post['action'],array("delete","save")) && isset($post['id']) && intval($post['id'])>0)
            {
                $functionality_enabled_error=Functionality_enabled('admin_access_level_modify', intval($post['id']));
                if($functionality_enabled_error!==true)
                {   
                    $errs[]=$functionality_enabled_error;
                }
            }
            //*******End of functionality limitations********
            
            
            if($post['action']=="delete" && count($errs)==0)
            {
                $log_action='access_level_delete';
                $id=isset($post['id'])?$post['id']:null;
                $delete_err=$this->admin_control_model->access_level_delete($id);
                if($delete_err===true)                 
                {                
                    $data=$this->admin_control_model->level_list();  
                    $data=$this->admin_control_model->add_panel_vars_ex($data,"level_list");
                    $res = $this->load->view("/admin/admin_control/level_list", $data, true);
                    $mess[]="deleted_ok";
                    make_response("output", $res, 1,create_temp_vars_set($mess));
                }
                else
                {
                    $errs[]=$delete_err;
                }
                
            }
            if($post['action']=="save" && count($errs)==0)
            {
                $log_action=isset($post['id']) ? 'access_level_modify' : 'access_level_add';
                $errs=$this->admin_control_model->panel_validate_ex($post,"access_level");
                if(count($errs)==0)
                {
                    $save_err=$this->admin_control_model->access_level_save($post);
                    if($save_err===true)
                    {                
                        $data=$this->admin_control_model->level_list();  
                        $data=$this->admin_control_model->add_panel_vars_ex($data,"level_list");
                        $res = $this->load->view("/admin/admin_control/level_list", $data, true);
                        $mess[]="saved_ok";
                        make_response("output", $res, 1,create_temp_vars_set($mess));
                    }
                    else
                    {
                        $errs[]=$save_err;                        
                    }                    
                }                
            }
            if(count($errs)!=0)
            {
                if(isset($log_action))
                {
                $details=array("Enity_id"=>isset($post['id']) ? $post['id'] : "");
                $details["Completed"]="false";
                $details["Exception"]="Errors (".implode(",",$errs).")";
                admin_log($log_action, $details,$this->admin_auth_model->admin_id);
                }
                
                make_response("error",create_temp_vars_set($errs), 1);
            }
            else
            {
                if(isset($log_action))
                {
                    $details=array("Enity_id"=>isset($post['id']) ? $post['id'] : "");
                    $details["Completed"]="true";
                    admin_log($log_action, $details,$this->admin_auth_model->admin_id);
                }
            }
        }
    }
}
?>
