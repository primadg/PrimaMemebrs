<?php
/**
 * 
 * THIS FILE CONTAINS Checkout2 CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file payment.php
 */
require_once ('payment.php');
/**
 * 
 * THIS CLASS ...
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Checkout2 extends Payment {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Checkout2()
    {
        parent::Payment();	
    }
	/**
	 * Configurate validation rules for form's fields
	 *
	 * @param array $post
	 * @return array
	 */
    function Config_validation($post)
    {
        $result=array();
        $result['errors']=array();
        $result['mess']=array();
        $result['is_load_list']=false;
        
        //(EDIT)Validation fields
        if( !isset($post['merchant_id']) or empty($post['merchant_id']))
        {
            $result['merchant_id'][]="merchant_id";
        }
        //End of validation fields
        if(count($result['errors'])==0)
        {
            //(EDIT)Writing fields to config file
            config_set($post['merchant_id'],'PAYMENT',$post['id'],'MERCHANT_ID');
            config_set($post['demo']=="false"?0:1,'PAYMENT',$post['id'],'demo'); 
            //End of writing fields to config file
            $result['is_load_list']=true;
            $result['mess'][]="saved_ok";
        }
        return $result;
    }
    /**
     * Configurate form's fields
     *
     * @param array $data
     * @return string
     */
    function Config_form($data)
    {
        $controller=$data['controller'];
        //Temp variables javascript 
        $temp_vars_set= array();
        $temp_vars_set['panel_script']=base_url()."js/admin/".$controller."/".$controller."_config.js";            
        $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
        $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";            
        $temp_vars_set['id']=isset($data['id'])?$data['id']:"";
        $data['temp_vars_set']=$temp_vars_set;            
        //Green messages
        $messages = array();
        $messages['saved_ok'] = "<{admin_msg_ok_0001}>";         
        $data['messages'] = $messages;            
        //Error messages
        $mess_err = array();
        $mess_err['not_defined'] = "<{admin_payment_system_".$controller."_msg_er_not_defined}>";
        $mess_err['udefined_action'] = "<{admin_payment_system_".$controller."_msg_er_udefined_action}>";        
        $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
        //(EDIT)Form field error
        $mess_err['merchant_id']="<{admin_payment_system_".$controller."_msg_er_merchant_id}>";
        //End form field error
        $data['mess_err'] = $mess_err;
        //(EDIT)Additional comment in html format
        $data['comment_html'] = "";
        $res = $this->load->view("/default/reg/user/".$controller."/".$controller."_config", $data, true);
        return $res;
    }
}
?>
