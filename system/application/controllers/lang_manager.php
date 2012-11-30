<?php
/**
 * 
 * THIS FILE CONTAINS Lang_manager CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file admin_controller.php
 */
require_once("admin_controller.php");
/**
 * 
 * THIS CLASS IS CONTAIN METHODS FOR LANGUAGE MANAGMENT
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Lang_manager extends Admin_Controller {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Lang_manager()
    {
        if(isset($_POST['object_type']))
        {
            switch (intval($_POST['object_type'])) {
           case 3:
                $this->access_bit=PRODUCT;
                break;
            case 4:
                $this->access_bit=PRODUCT;
                break;
            case 10:
                $this->access_bit=MEMBER_CONTROL;
                break;
            case 15:
                $this->access_bit=MEMBER_CONTROL;
                break;
            default:
                $this->access_bit=SYSTEM_CONFIGURATION;                
            }
        }
        parent::Admin_Controller();
        $this->load->model("lang_manager_model");
        $this->load->model("auth_model");       
    }
    /**
     * Import language variables
     *
     */
    function Import()
    {
        $post=prepare_post();
        $data=$this->lang_manager_model->template_get($post);
        $data['name']=base64_encode($data['name']);
        $data['descr']=base64_encode($data['descr']);
        $data['add']=base64_encode($data['add']);        
        make_response("message", create_temp_vars_set($data), 1);    
    }
    /**
     * Edit language variables
     *
     */
    function Edit()
    {
        $post=prepare_post();
        $errs=array();
        $mess=array();
        if(!isset($post['object_type']) || intval($post['object_type'])<=0)
        {
            $post['action']='';
            $errs[]='undefined_object_type';    
        }
        
        if(isset($post['action']) && $post['action']=='save')
        {
        fb($post['object_type'], __function__." object_type");
        if(($errs[]=Functionality_enabled_by_object_type($post['object_type'],isset($post['id']) ? $post['id'] : 0))===true)
        {
            $errs=$this->lang_manager_model->validate_ex($post);
        }
			if (isset($post['name']))
				//$post['name'] = htmlentities($post['name'], ENT_QUOTES, 'utf-8');fb($post['name']);
            $log_action=(!isset($post['id']) || $post['id']=='') ? 'lang_manager_add' : 'lang_manager_modify';
            $log_id=(!isset($post['id']) || $post['id']=='') ? $post['object_type'] : $post['object_type']."/".$post['id'];
            if(count($errs)==0)
            {
                $save_err=$this->lang_manager_model->template_set($post);
                if($save_err===true)
                {
                    $data=$this->lang_manager_model->add_panel_vars_ex($post,$post['object_type']);
                    $temp['action']="save";
                    $temp['mess']=$data['messages']['saved_ok'];
                    make_response("message", create_temp_vars_set($temp), 1);
                    simple_admin_log($log_action,$log_id);
                    return;
                }
                else
                {
                    $errs[]=$save_err;
                }
            }
            simple_admin_log($log_action,$log_id,true,$errs);
        }
        if(count($errs)==0)
        {
            if(!isset($post['action'])||$post['action']=='')
            {
                $data=$this->lang_manager_model->template_get($post);
                $data['sys_default_lang']=$this->auth_model->get_default_language(); 
                //***********Functionality limitations***********
                if(Functionality_enabled('admin_multi_language')===true)
                {   
                    $data['langs']=$this->lang_manager_model->get_languages();
                }
                //*******End of functionality limitations********
                $data['import_langs']=$this->lang_manager_model->get_import_languages($post['object_type'],isset($post['id']) ? $post['id'] : false);
                $data=$this->lang_manager_model->add_panel_vars_ex($data,$post['object_type']);
                if(isset($data['default_lang']))
                {
                    array_unshift($data['import_langs'],array('id'=>'-1','name'=>$data['default_lang']));
                }
				
				$data['sid'] = (isset($post['sid'])) ? htmlentities($post['sid'], ENT_QUOTES, 'utf-8') : ((isset($post['name'])) ? htmlentities($post['name'], ENT_QUOTES, 'utf-8') : '');
                $data['no_import']=(is_array($data['import_langs']) && count($data['import_langs'])) ? false : true;
                $res = $this->load->view("/admin/lang_manager", $data, true);
                make_response("output", $res, 1,create_temp_vars_set($mess));
            }
            else
            {
                $errs[]='undefined_action';    
            }            
        }
        if(count($errs)!=0)        
        {
            make_response("error",create_temp_vars_set($errs), 1);
        }
    }
}
?>
