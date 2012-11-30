<?php
/**
 * 
 * THIS FILE CONTAINS Member_group CLASS
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
 * THIS CLASS DISPLAY THE LIST OF USER'S GROUP
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Member_group extends Admin_Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Member_group()
    {
        
        $this->access_bit=MEMBER_CONTROL;
        parent::Admin_Controller();
        $this->load->model("member_group_model");
        $this->load->model("member_model");
        $this->load->model("user_model");
        $this->load->model("user_auth_model");
        $this->load->model("mail_model");
    }
    
    /**
     * Display the list of user's group
     *
     */
    function Group_list()
    {
        $data=array();
        $post=prepare_post();
        $errs=array();
        $mess=array();
        
        if(isset($post['action']) && $post['action']=="delete")
        {
            $result=$this->member_group_model->delete_group($post);
            if($result!==true)
            {
                $errs[]=$result;
            }
            else
            {
                $mess[]='deleted_ok';
            }
        }
        
        if(count($errs)==0)
        {
            $data=$this->member_group_model->get_group_list($post);                
            $data=$this->member_group_model->add_panel_vars_ex($data,"member_group");                
            $res = $this->load->view("/admin/member_group/member_group", $data, true);
            make_response("output", $res, 1,create_temp_vars_set($mess));
        }
        else
        {
            make_response("error",create_temp_vars_set($errs), 1);
        }
    }
}
?>
