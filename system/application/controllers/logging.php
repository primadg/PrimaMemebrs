<?php
/**
 * 
 * THIS FILE CONTAINS Logging CLASS
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
 * THIS CLASS CONTAINS METHODS FOR LOG
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Logging extends Admin_Controller {
	
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Logging()
    {
        $this->access_bit=ACTIVITY_LOGGING;
        parent::Admin_Controller();
        $this->load->library('table');
        $this->load->model("logging_model");        
    }
	/**
	 * Enter description here...
	 *
	 */
    function admin()
    {
        $this->admin_list();
    }
    /**
     * Enter description here...
     *
     */
    function protect()
    {
        $this->admin_list(false);
    }
    /**
     * Enter description here...
     *
     * @param boolean $is_admin
     */
    function admin_list($is_admin=true)
    {
/**************************************************************
*   @author Konstantin X | 15:44 17.06.2008
***************************************************************/
        $data = array();
        $post = prepare_post();

        if( isset($post['action']) && ($post['action'] == 'delete') )
        {
            $drez = $this->logging_model->record_del(intval($post['id']));

            if($drez !== TRUE)
            {
                $errs[] = $drez;
                make_response("error", create_temp_vars_set($errs), 1);
                return;
            }
        }

        $data = $this->logging_model->admin_list($post,$is_admin);

        $mess_err = array();
        $mess_err['main_error'] = '<{admin_msg_er_0000}>';
        $mess_err['date_a'] = '<{admin_msg_er_0016}>'; // Maximum allowed 65K symbols
        $mess_err['date_b'] = '<{admin_msg_er_0016}>'; // Maximum allowed 65K symbols

        $temp_vars_set['deleteText']='<{admin_msg_delete_question}>';
        $temp_vars_set['ValidationError']='<{admin_msg_validation_fail}>';
        $temp_vars_set['is_admin']=$is_admin?'true':'false'; 
        $temp_vars_set['panel_script']=base_url()."js/admin/logging/admin_log.js";
                    
        $data['temp_vars_set']=$temp_vars_set;
        
        $data['mess_err'] = $mess_err;
        $data['is_admin'] = $is_admin;
        
        
        $res  = $this->load->view('admin/logging/admin_log', $data, TRUE);
        make_response("output", $res, 1);
    }/***                      admin                     ***/
    
    
	/**
	 * Enter description here...
	 *
	 */
    function user()
    {
/**************************************************************
*   @author Konstantin X | 11:07 01.07.2008
***************************************************************/
        $data = array();
        $post = prepare_post();

        $data = $this->logging_model->user_list($post);

        $mess_err = array();
        $mess_err['main_error'] = '<{admin_msg_er_0000}>';
        $mess_err['date_a'] = '<{admin_msg_er_0016}>'; // Maximum allowed 65K symbols
        $mess_err['date_b'] = '<{admin_msg_er_0016}>'; // Maximum allowed 65K symbols

        $temp_vars_set['deleteText']='<{admin_msg_delete_question}>';
        $temp_vars_set['ValidationError']='<{admin_msg_validation_fail}>';        
        $temp_vars_set['panel_script']=base_url()."js/admin/logging/user_log.js";
        $data['temp_vars_set']=$temp_vars_set;
        $data['mess_err'] = $mess_err;


        $res  = $this->load->view('admin/logging/user_log', $data, TRUE);
        make_response("output", $res, 1);
    }/***                      user                     ***/
	/**
	 * Delete action from admin logs
	 *
	 */
    function admin_del()
    {
/**************************************************************
*   @author Konstantin X | 17:06 23.06.2008
***************************************************************/

        $data = array();
        $post = prepare_post();
//print_r($post);
        $drez = $this->logging_model->record_del($post);
//        $res  = $this->load->view('/admin/logging/admin_log', $data, TRUE);
//        make_response("output", $res, 1);
    }/***                      admin                     ***/
}
?>
