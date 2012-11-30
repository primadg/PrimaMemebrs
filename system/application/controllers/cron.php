<?php
/**
 * THIS FILE CONTAINS Cron CLASS
 * 
 * @package Needsecure
 * @author Drovorubov
 * @version 1.0
 * created 24-jun-2008 11:24:07
 */

 //exit if the script is launched from browser
 //if($_SERVER['SCRIPT_FILENAME'] != 'cron.php') exit;


/**
 * THIS CLASS ...
 * 
 * @package Needsecure
 * @author Drovorubov
 * @version 1.0
 * created 24-jun-2008 11:24:07
 */

class Cron extends Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Cron()
    {
        parent::Controller();
        pre_config();
        $t=time()-intval(_config_get('SYSTEM','CRON_TIME'));
        if($t<60 && $t>0)
        {
            $message=array("result"=>false,'text'=>"time_limit");
            echo  create_temp_vars_set($message);           
            exit;
        }
        fb("Cron init");            
        $this->load->model('mail_model');
        $this->load->model('member_model');
        $this->load->model('config_model');
        $this->load->model('payment_model');
        $this->load->helper('config_helper');

        $this->load->model('user_model');
        $this->load->model('auth_model');
        $this->load->model('user_auth_model');
        $this->load->model('admin_auth_model');        
    }
    /**
     * Enter description here...
     *
     */
    function Init()
    {   
        $this->Index();
    }

    /**
    * Main cron method calling other cron methods
    *
    * @author Val Petruchek
    */
    function Index()
    {
        _config_set(time(),'SYSTEM','CRON_TIME');
        $this->member_model->check_and_update_expiration_term();
        $this->payment_model->inform_almost_expired_subscriptions();
        $this->payment_model->inform_expired_subscriptions();
        $this->Send_portion_of_emails();
        $this->Delete_expired_history();
        
        //auto subscribe free products
        if(intval(config_get('SYSTEM', 'CONFIG', 'member_autosubscribe_free_products')))
        {
            $this->member_model->subscribe_all_free(50);
        } 
    }
	/**
	 * Enter description here...
	 *
	 */
    function Reset_demo()
    {
        _config_set(time(),'SYSTEM','CRON_TIME');
        if(defined('NS_DEMO_VERSION'))
        {
            $this->load->helper('file');
            write_file(absolute_path().'demo.log', date("\nY-m-d H:i:s ")."Reset begin.",'a');
            write_file(absolute_path().'demo.log',@str_replace('<br/>','\n',ns_restore()),'a');            
            $dump_file=absolute_path()."_DB/demo_db_enterprise.sql";
            if(defined('NS_PRO_VERSION'))
            {
                $dump_file=absolute_path()."_DB/demo_db_pro.sql";
            }
            if(defined('NS_BASIC_VERSION'))
            {
                $dump_file=absolute_path()."_DB/demo_db_basic.sql";
            }            
            if(file_exists ($dump_file))
            {
                execute_sql_file($dump_file);
            }
            _config_set("Sorry, the system is offline now!",'SYSTEM','STATUS','offline_msg');
            if(defined('NS_ENTERPRISE_VERSION'))
            {
                //Set design templates
                config_set(0,'DESIGN','active_reg_design');
                config_set(1,'DESIGN','active_unreg_design');
            }
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_products_modify_paid')!==true)
            {
                $this->product_model->delete_paid_products();
            }
            //*******End of functionality limitations********   
            ns_reprotect_all();
        write_file('./demo.log', date("\nY-m-d H:i:s ")."Reset end.",'a');
        }
    }
    
    
    /**
     * This function physically sends the protion of emails from queue
     *
     * @return boolean
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Send_portion_of_emails()
    {  
        //***********Functionality limitations***********
        if(Functionality_enabled('action_newsletter_send')!==true)
        {
             $message=array("result"=>false,'text'=>"demo_limit");
            echo  create_temp_vars_set($message); 
        }
        //*******End of functionality limitations********    
        
        _config_set(time(),'SYSTEM','CRON_TIME');
        $number_in_portion = intval(config_get("system","mailer","send_to_count"));
        if( $number_in_portion > 0 )
        {
            while ($number_in_portion--)
            {
                $this->mail_model->Pop_email_from_queue();
            } // while
        }
        return true;
    }
    /**
     * Enter description here...
     *
     */
    function Delete_expired_history()
    {
        _config_set(time(),'SYSTEM','CRON_TIME');
        $this->load->model('newsletter_model');
        $this->load->model('logging_model');
        $this->newsletter_model->history_remove(array('action'=>'delete','limit'=>'expired'));
        $this->logging_model->log_remove(array('action'=>'delete','table'=>'Admin_logs','limit'=>'expired'));
        $this->logging_model->log_remove(array('action'=>'delete','table'=>'User_logs','limit'=>'expired'));
    }
}

?>
