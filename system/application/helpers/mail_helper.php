<?php
/**
 * 
 * THIS FILE CONTAINS MAIL FUNCTIONS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
    /**
    * Returns the value of array by key.
    * If key is not found, returns empty string.
    * Fuction is used in replace_keys function
    *
    * @author Drovorubov
    * @param array $keys
    * @param string $key_name
    * @return string
    */
    function set_key($keys,$key_name='')
    {
        if( isset($keys[$key_name]) and !empty($keys[$key_name]))
        {
            return $keys[$key_name];
        }
        else
        {
            return '';
        }
    }

    /**
     * Function returns an array of system email template names that can be addressed to admins
     *
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_admin_system_emails_tmpl_names()
    {
        $CI = &get_instance();
        $CI->load->model('mail_model');
        //read the whole info about templates and get template names for admin only
        $templates_info = $CI->mail_model->Get_system_email_data('ADMIN_TEMPLATES_NAMES');
        return $templates_info;
    }
    
    
    /**
     * Function returns an array of system email template names that can be addressed to users
     *
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Get_user_system_emails_tmpl_names()
    {
        $CI = &get_instance();
        $CI->load->model('mail_model');
        //read the whole info about templates and get template names for users only
        $templates_info = $CI->mail_model->Get_system_email_data('USER_TEMPLATES_NAMES');
        return $templates_info;
    }


    /**
     * Sends system email to user with ID specified (duplicate function of mail_model->Send_system_email_to_user)
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
        $CI = &get_instance();
        $CI->load->model('mail_model');
        return $CI->mail_model->Send_system_email_to_user($recipient_id, $tmpl_name, $replace_values);
    }


    /**
     * Sends system email to admin with ID specified (duplicate function of mail_model->Send_system_email_to_admin)
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
        $CI = &get_instance();
        $CI->load->model('mail_model');
        return $CI->mail_model->Send_system_email_to_admin($recipient_id, $tmpl_name, $replace_values);
    }
    
    
    /**
     * Sends system email to admin with specified subscription (duplicate function of mail_model->Send_system_email_to_admin)
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
        $CI = &get_instance();
        $CI->load->model('mail_model');
        return $CI->mail_model->Send_system_subscription_to_admins($tpl_name,$replace_values,$exclude_id);
    }


    /**
    * Gets email keys and returns array.
    *
    * @author Drovorubov
    * @param string $type
    * @return array $rv
    */
    function get_template_variables($type)
    {
        $rv = array();
        $CI = &get_instance();
        $CI->load->model('mail_model');
        $rv = $CI->mail_model->get_template_variables($type);
        return $rv;
    }
    
    /**
     * Function returns an array of system email template bits (name=>bit)
     *
     * @return array
     *
     * @author onagr
     * @copyright 2008
     */
    function Get_admin_system_emails_tmpl_bits()
    {
        $tpls=Get_admin_system_emails_tmpl_names();
        $tpls=array_values(preg_grep("/^your_[a-z_]+/",$tpls,PREG_GREP_INVERT));
        $result=array_flip($tpls);
        $counter=0;
        foreach($result as $key => $value)
        {
            $result[$key]=(0x1 << $counter);
            $counter++;
        }
        return $result;
    }

?>
