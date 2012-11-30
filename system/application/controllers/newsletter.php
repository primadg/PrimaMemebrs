<?php
/**
 * 
 * THIS FILE CONTAINS Newsletter CLASS
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
 * Enter description here...
 *
 */
define("ALL_USER","all_user");
/**
 * Enter description here...
 *
 */
define("ALL_EXPIRED_USER","all_expired_user");
/**
 * Enter description here...
 *
 */
define("ALL_ACTIVE_USER","all_active_user");
/**
 * 
 * THIS class is intended for work with letter
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Newsletter extends Admin_Controller 
{
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */    
    function Newsletter()
    {
    	$this->access_bit=NEWSLETTER;
        parent::Admin_Controller();			
    	$this->load->model("newsletter_model");
   		$this->load->model("mail_model");
   		$this->load->model("product_model");
        $this->load->model("product_group_model");
        $this->load->model("config_model");
    }
    /**
     * Dispaly list of newsletters
     *
     * @return mixed
     */    
    function template_list()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_newsletter_template')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $post=prepare_post();
        $data=$this->newsletter_model->template_list($post);
        $data=$this->newsletter_model->add_panel_vars_ex($data,"template_list");
        $res=$this->load->view("/admin/newsletter/template_list", $data, true);
        make_response("output", $res, 1);
    }
    /**
     * Delete a newsletters
     *
     * @return mixed
     */
    function template_delete()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_newsletter_template')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $errs=array();
        $post=prepare_post();
        $delete_err=$this->newsletter_model->template_delete($post);
        if($delete_err===true)
        {
            $data=$this->newsletter_model->template_list($post);
            $data=$this->newsletter_model->add_panel_vars_ex($data,"template_list");
            $res=$this->load->view("/admin/newsletter/template_list", $data, true);
            $mess[]="deleted_ok";
            make_response("output", $res, 1,create_temp_vars_set($mess));
        }
        else
        {
            $errs[]=$delete_err;
        }
        if(count($errs)!=0)
        {
            make_response("error",create_temp_vars_set($errs), 1);
        } 
    }
    /**
     * Display list of newsletters for a member
     *
     */
    function Member_list()
    {
        $result=array();
        $post=prepare_post();
        $data=$this->newsletter_model->member_list($post);
        $result[]="{'id':'all_user','name':'<{admin_newsletter_send_email_step1_user_category_all}>'}"; 
        $result[]="{'id':'all_expired_user','name':'<{admin_newsletter_send_email_step1_user_category_all_expired}>'}"; 
        $result[]="{'id':'all_active_user','name':'<{admin_newsletter_send_email_step1_user_category_all_active}>'}"; 
        
        foreach($data['items'] as $value)
        {
            $result[]="{'id':".$value['id'].",'name':'".$value['login']."'}"; 
        }
        $res="{'results':[".implode(",",$result)."],'total':'".($data['total']+3)."'}";
        echo replace_lang($res);
    }
    /**
     * Enter description here...
     *
     */
    function subscribe()
    {
        $post=prepare_post();
        $data=$this->newsletter_model->template_list($post);
        $data=$this->newsletter_model->add_panel_vars_ex($data,"send_email");
        $res=$this->load->view("/admin/newsletter/send_email", $data, true);
        make_response("output", $res, 1);
    }
    
    /**
     * Enter description here...
     *
     * @param integer $user_id
     * @return mixed
     */
    function send_email($user_id=0)
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_newsletter_send')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $post=prepare_post();
        
        if(isset($post['action'])&&$post['action']=='product_list')
        {
            if(isset($post['group_id']) && intval($post['group_id'])>0)
            {
                $group_id=intval($post['group_id']);
                $products=array();
                $count = $this->product_model->products_count($group_id);
                $result = $this->product_model->product_list(1, $count,'name','ASC',$group_id);
                $products=array_transform($result['items'],'id','name');
                $res=array();
                $res['action']='product_list';
                $res['group_id']=$group_id;
                $res['items']=$products;
                make_response("message", create_temp_vars_set($res,true), 1);
            }
            return;
        }
        
        if(isset($post['action'])&&$post['action']=='template')
        {
            if(isset($post['template_id']) && intval($post['template_id'])>0)
            {
                $template_id=intval($post['template_id']);
                $items=$this->lang_manager_model->template_get(array('id'=>$template_id,'object_type'=>(intval(config_get('system','mailer','mailer_in_html'))>0 ? 14 : 8)));
                $res=array();
                $res['action']='template';
                $res['template_id']=$template_id;
                $res['items']=array();
                $res['items']['descr']=$items['descr'];
                $res['items']['add']=$items['add'];
                make_response("message", create_temp_vars_set($res,true), 1);
            }
            else{                
                $res=array();
                $res['action']='template';
                $res['template_id']=$template_id;
                $res['items']=array();
                $res['items']['descr']=$items['descr'];
                $res['items']['add']=$items['add'];
                make_response("message", create_temp_vars_set($res,true), 1);
            }
            return;
        }
        
        $mess=array();
        $errs=array();
        if(isset($post['action'])&&$post['action']=='send')
        {
            for($i=0;$i<$post['count'];$i++)
            {
                if(isset($post['newsletter_'.$i]))
                {
                    $errs=$this->newsletter_model->send_emails($post['newsletter_'.$i]);
                }
            }
            if(count($errs)==0)
            {
                $mess[]='sended_ok';
            }
        }
        
        $is_one_memeber=false;
        $user_category=array();
        if(isset($user_id) && intval($user_id)>0)
        {
            $this->load->model("member_model");
            $user=$this->member_model->get_member_info($user_id);
            if($user!==false)
            {
                $is_one_memeber=true;
                $user_category[$user['id']]=$user['login'];
            }
        }
        $user_category[0]="<{admin_newsletter_send_email_step1_user_category_all}>";
        $user_category[-1]="<{admin_newsletter_send_email_step1_user_category_all_active}>";
        $user_category[-2]="<{admin_newsletter_send_email_step1_user_category_all_expired}>";
        $user_category[-3]="<{admin_newsletter_send_email_step1_user_category_all_suspended}>";
      	
        $sys_replace_values = $this->mail_model->Get_sys_replace_keys(true);
        $dyn_replace_values = $this->mail_model->Get_dyn_replace_keys("user", 0, true);
        $constants = array_merge($sys_replace_values, $dyn_replace_values);
        foreach($constants as $key=>$value)
        {
            $constants[$key] = "##".$value."##";
        }        
        $data=$this->newsletter_model->template_list($post);
        
        if (count($data['emails'])==0) $data['emails']=array('0'=>array('id'=>'1', 'name'=>'New template'));      
        $data=$this->newsletter_model->add_panel_vars_ex($data,"send_email");
        if(isset($user_id) && intval($user_id)>0)
        {
            $data['temp_vars_set']['member']=intval($user_id);
        }
        $data['product_groups']=$this->product_group_model->list_all();
        $data['constants']=$constants;
        $data['user_category']=$user_category;
        $data['is_one_memeber']=$is_one_memeber;        
        $res=$this->load->view("/admin/newsletter/send_email", $data, true);        
        if(count($errs)>0)
        {
            make_response("error",create_temp_vars_set($errs), 1);
            return;
        }
        
        if(count($mess)>0)
        {
            make_response("output", $res, 1,create_temp_vars_set($mess));
        }
        else
        {
            make_response("output", $res, 1);
        }
    }
    /**
     * Dispaly or remove user history
     *
     * @param integer $user_id
     */
    function history($user_id=0)
    {
        $post=prepare_post();
        $errs=array();
        $msgs=array();
        $data=array();
        $section="email_list";
        $view="/admin/newsletter/email_history/email_list";
        if(isset($post['action']) && $post['action']=='delete')
        {
            $err = $this->newsletter_model->history_remove($post);
            if($err!==true)
            {
                $errs[]=$err;
            }
            $msgs[]='deleted_ok';            
        }
        
        if(isset($post['action']) && $post['action']=='info')
        {
            $data = $this->newsletter_model->history_info($post);
            if(count($data['errs'])>0)
            {
                $errs=$data['errs'];
            }
            else
            {
                $view="/admin/newsletter/email_history/info";
            }
        }
        
        if($view=="/admin/newsletter/email_history/email_list")
        {
            if(isset($user_id) && intval($user_id)>0)
            {
                $this->load->model("member_model");
                $user=$this->member_model->get_member_info($user_id);
                if($user!==false)
                {
                    $post['filters']=isset($post['filters']) ? $post['filters'] : array();
                    $post['filters'][1]='user';
                    $post['filters'][2]=$user['login'];
                    $post['member']=intval($user_id);
                }
            }
            $data = $this->newsletter_model->history($post);
        }
        
        $data=$this->newsletter_model->add_panel_vars_ex($data,$section);
        if(isset($user_id) && intval($user_id)>0)
        {
            $data['temp_vars_set']['member']=intval($user_id);
        }
        $res=$this->load->view($view, $data, true);
        
        
        if(count($errs)>0)
        {
            make_response("error",create_temp_vars_set($errs), 1);
            return;
        }
        
        if(count($msgs)>0)
        {
            make_response("output", $res, 1,create_temp_vars_set($msgs));
        }
        else
        {
            make_response("output", $res, 1);
        }
    }
}
?>
