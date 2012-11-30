<?php
/**
 * 
 * THIS FILE CONTAINS Config CLASS
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
 * THIS FILE IS CONTAIN Config CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Config extends Admin_Controller
{
	/**
	 * Enter description here...
	 *
	 */
    function My_addfields()
    {
        echo "<table>";
        echo get_user_add_fields(726);
        echo "</table>";
    }
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Config()
    {
        $this->access_bit=SYSTEM_CONFIGURATION;
        parent::Admin_Controller();
        $this->load->model("config_model");
        $this->load->model("admin_control_model");
        $this->load->model("lang_manager_model");
        $this->load->helper('form');
        // Add by Konstantin X | 14:09 09.09.2008
    }
    
    function Member_pages($page="registration")
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_config_constructor')!==true)
        {
            $this->admin_auth_model->showAccessDenied();
            exit;
        }
        
        $post=prepare_post();
        $data=$this->config_model->add_panel_vars_ex(array(),"member_pages");
        $data['page_name']=$page;
		$data['page_icon']='ico_sysconf_big.png';
        
        if(isset($post['action']) && in_array($post['action'],array('up','down','save')))
        {
            if (($err_text=Functionality_enabled('admin_config_constructor_modify'))===true)
            {
                if($post['action']=='save')
                {
                    if(true===($errs=$this->config_model->member_page_set($data['page_name'],$post)))
                    {
                        $data['messages']['saved']['display']=true;
                    }
                    else
                    {
                        foreach($errs as $k=>$v)
                        {
                            if(empty($v))
                            {
                                $data['mess_err'][$k]['display']=true;
                            }
                            else
                            {
                                $data['mess_err'][$k]=array('display'=>true,'text'=>$v);
                            }
                        }
                    }            
                }
                if(in_array($post['action'],array('up','down')))
                {
                    if(true===($err=$this->config_model->member_page_order($data['page_name'],$post)))
                    {
                        $data['messages']['saved']['display']=true;
                    }
                    else
                    {
                        $data['mess_err'][$err]['display']=true;
                    }
                }
            }
            else
            {
                $data['mess_err']['functionality_disabled']=array('display'=>true,'text'=>$err_text);
            }
        }
        $data['preset']=isset($post['preset']) && !empty($post['preset']) ? $post['preset'] : false;
        $data['fields']=$this->config_model->member_page_get($data['page_name'],$data['preset']);
        $data['page_presets']=$this->config_model->member_page_presets_list($data['page_name']);
        $field_types=_config_get('fields','types');
        ksort($field_types);
        $data['field_types']=$field_types;
        $res = $this->load->view("/admin/config/member_pages", $data, true);
        make_response("output", $res, 1);
    }

    //***************************Payments***********************************
    /**
     * Enter description here...
     *
     * @return mixed
     */
    function Payment_system()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_config_payment')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $data = Array();
        $data=$this->config_model->add_panel_vars_ex($data,"payment_system");
        $data = $this->config_model->payment_system($data);
        $res = $this->load->view("/admin/config/payment_systems/payment_system", $data, true);
        make_response("output", $res, 1);
    }
	/**
	 * Enter description here...
	 *
	 * @return mixed
	 */
    function Payment_system_activate()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_config_payment')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $post=prepare_post();
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_payment_modify');
        if($functionality_enabled_error!==true)
        {
            $post['error_text']=$functionality_enabled_error;
        }
        //*******End of functionality limitations********

        $data = $this->config_model->payment_system_activate($post);
        make_response("message",create_temp_vars_set($data),1);

        simple_admin_log('payment_system_status_modify',(isset($data['id']) ? $data['id'] : ""),(isset($data['error']) && $data['error']!='false'),"Undefined paymant system!");
    }
    //*************************EndOfPayments********************************
    /**
     * Enter description here...
     *
     */
    function Currency_change()
    {
                //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_security_modify');
            if($functionality_enabled_error!==true)
            {
             //   make_response("error", $functionality_enabled_error, 1);
                make_response("message",create_temp_vars_set(array('error'=>'true','error_text'=>replace_lang($functionality_enabled_error)),true),1);
             //   return;
            }
            //*******End of functionality limitations********
            else
            {
                $post=prepare_post();
                $data = $this->config_model->currency_change($post);
                make_response("message",create_temp_vars_set($data,true),1);
            }
        simple_admin_log('currency_modify',(isset($post['currency_code']) ? $post['currency_code'] : ""),(isset($data['error']) && $data['error']!='false'),"Currency code not changed!");
    }

    /**
    * "mailer settings" main controller to depict the data in the view
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Mailer_settings()
    {
        $data = Array();
        //get all mailer settings vars to $data array
        $data = $this->config_model->Mailer_get($data);
        //get additional language constants for view
        $data = $this->config_model->add_panel_vars_ex($data,"mailer_settings");
        //load the view and pass there $data
        $res = $this->load->view("/admin/config/mailer_settings", $data, true);
        make_response("output", $res, 1);
    }


    /**
    * processes the data from "mailer settings" page and saves it
    *
    * @author Makarenko Sergey
    * @param string $mode
    * @copyright 2008
    */
    function Mailer_settings_save($mode)
    {
        $post_vars = prepare_post();
        //if we get "save" mode - then validate and save the data
        if ($mode == "save")
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_mailer_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********

            // save and validate data from the post
            $validated_errors = $this->config_model->config_validate_ex($post_vars,"mailer_settings");
            // if there some errors after $_POST data validation
            if(count($validated_errors)==0)
            {
                //save the new data from the post to ht_sys_config.cfg file
                $this->config_model->Mailer_set($post_vars);
                //show the page with the updated data
                $data = Array();
                $data = $this->config_model->Mailer_get($data);
                $data = $this->config_model->add_panel_vars_ex($data,"mailer_settings");
                $mess = array();
                $mess[] = "saved_ok";
                $res=$this->load->view("/admin/config/mailer_settings", $data, true);
                make_response("output", $res, 1, create_temp_vars_set($mess));
            }
            else
            {
                //there was some validating errors, display them
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }

            simple_admin_log('mailer_settings_modify',false,(count($validated_errors)!=0),$validated_errors);
        }
        //if we get "test connection" mode - then try to connect SMTP host
        else if ($mode=="test")
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_mailer_test');
            if($functionality_enabled_error!==true)
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********
            
            $validated_errors = $this->config_model->config_validate_ex($post_vars,"mailer_settings");
             if(count($validated_errors)!=0) {
                //there was some validating errors, display them
                make_response("error", create_temp_vars_set($validated_errors), 1);
                return;
            }
            $this->load->library('email');
            //set params of connection
            $config['protocol'] = ($post_vars['mailer_use_smtp'] == 'true') ? 'smtp' : 'sendmail';
            $config['charset'] = $post_vars['mailer_charset'];
            $config['mailtype'] = ($post_vars['mailer_in_html'] == 'true') ? 'html' : 'text';
            $config['smtp_host'] = $post_vars['mailer_smtp_host'];
            $config['smtp_port'] = $post_vars['mailer_smtp_port'];
            if ($post_vars['mailer_use_auth'] == 'true')
            {
                $config['smtp_user'] = $post_vars['mailer_smtp_user'];
                $config['smtp_pass'] = $post_vars['mailer_smtp_pass'];
            }
            else
            {
                //if this vars set to '' then _smtp_auth is automaticaly set to FALSE by email->initialize method
                $config['smtp_user'] = '';
                $config['smtp_pass'] = '';
            }
            //init email parameters
            $this->email->initialize($config);
            $validated_errors = array();
            $connection_test_result = true;
            //check if there was some errors on SMTP connect
            if (!$this->email->_smtp_connect()) //here we call the private undocumented method of the Email class of CodeIgniter 
            {
                $connection_test_result = false;
                $validated_errors[] = "tested_fail";
            }
            //check if there was some errors on authentication
            else if (!$this->email->_smtp_authenticate() && $post_vars['mailer_use_auth']=='true') //here we call the private undocumented method of the Email class of CodeIgniter
            {
                $connection_test_result = false;
                $validated_errors[] = "tested_auth_fail";
            }
fb($connection_test_result, ' validated_errors - ');
            //if there was no errors - display "tested_ok" message
            if ($connection_test_result)
            {
                //show the page with the updated data
                $data = Array();
                $data = $this->config_model->Mailer_get($data);

                //overlay previuos variables with the data from $_POST
                $data = $post_vars;

                $data = $this->config_model->add_panel_vars_ex($data,"mailer_settings");
                $mess = array();
                $mess[] = "tested_ok";
                $res=$this->load->view("/admin/config/mailer_settings", $data, true);
                make_response("output", $res, 1, create_temp_vars_set($mess));
            }
            else
            {
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }

            simple_admin_log('mailer_connection_test',false,(!$connection_test_result),$validated_errors);
        }
    }


    /**
    * "design manager" main controller to depict the data in the view
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Design_manager()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_config_design')!==true)
        {
            $this->admin_auth_model->showAccessDenied();
            exit;
        }
        //*******End of functionality limitations********
        $data = Array();//$functionality_enabled_error_design=false;
		if (Functionality_enabled('admin_config_design_changer')===true)
		{
			//get all design settings vars to $data array
        	$data = $this->config_model->design_settings_get($data);
			//get additional language constants for view
        	$data = $this->config_model->add_panel_vars_ex($data,"design_settings");
			//load the view and pass there $data
			$data['design_manager'] = $this->load->view("/admin/config/design_manager_design", $data, true);
		}
        if (Functionality_enabled('admin_config_page_main')===true)
		{
			$data = $this->config_model->main_page_get($data);
        	$data = $this->config_model->add_panel_vars_ex($data,"main_page_settings");
			$data['main_page'] = $this->load->view("/admin/config/main_page", $data, true);
		}
        
        if (Functionality_enabled('admin_config_constructor')===true)
        {
            $data['constructor_page'] = $this->load->view("/admin/config/design_manager_constructor", $data, true);
        }
		
        $res = $this->load->view("/admin/config/design_manager", $data, true);//fb($res);
        make_response("output", $res, 1);
    }


    /**
    * processes the data from "design manager" page and saves it
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Design_manager_save()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_config_design')!==true)
        {
            $this->admin_auth_model->showAccessDenied();
            exit;
        }
        //*******End of functionality limitations********

        $post_vars = prepare_post();
		$data = array();
		$mess = array();
		$validated_errors = array();
        // save and validate data from the post
        if (Functionality_enabled('admin_config_design_changer')===true)
		{
            if (($err_text=Functionality_enabled('admin_config_design_changer_modify'))===true)
            {
                if(isset($post_vars['active_unreg_design']))
                {	
                    $validated_errors = $this->config_model->config_validate_ex($post_vars,"design_settings");
                }
                if(count($validated_errors)==0)
                {
                    if(isset($post_vars['active_unreg_design']))
                    {
                        $this->config_model->design_settings_set($post_vars);
                        $mess[] = "design_saved_ok";
                    }
                }
                //show the page with the updated data				
                $data = $this->config_model->design_settings_get($data);
                $data = $this->config_model->add_panel_vars_ex($data,"design_settings");
                $data['design_manager'] = $this->load->view("/admin/config/design_manager_design", $data, true);
            }
            else
            {
                $validated_errors[]=$err_text;
            }                        
		}
		//fb($post_vars, ' config data - ');
        if (Functionality_enabled('admin_config_page_main')===true)
        {
            if (($err_text=Functionality_enabled('admin_config_page_main_modify'))===true)
            {
                if (isset($post_vars['page_amount']))
                {
                    $is_error = $this->config_model->main_page_set($post_vars);
                    if (!$is_error)
                    {
                        $validated_errors[] = 'main_page_saved_error';
                    }
                    else
                    {
                        $mess[] = "main_page_saved_ok";
                        $data = $this->config_model->main_page_get($data);
                        $data = $this->config_model->add_panel_vars_ex($data,"main_page_settings");
                        $data['main_page'] = $this->load->view("/admin/config/main_page", $data, true);
                    }
                }
            }
            else
            {
                $validated_errors[]=$err_text;
            }
        }
        
        if (Functionality_enabled('admin_config_constructor')===true)
        {
            $data['constructor_page'] = $this->load->view("/admin/config/design_manager_constructor", $data, true);
        }
		
		if(count($validated_errors)==0)
		{
			$res=$this->load->view("/admin/config/design_manager", $data, true);
			make_response("output", $res, 1, create_temp_vars_set($mess));
		}
		else
		{
			make_response("error", create_temp_vars_set($validated_errors), 1);
		}
        simple_admin_log('design_modify',false,(count($validated_errors)!=0),$validated_errors);
    }

    /**
    * "member settings" main controller to depict the data in the view
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Member_settings()
    {
        $data = $this->config_model->member_get();
        //load additional language vars to $data array
        $data = $this->config_model->member_settings_vars_add($data);
        //load "member settings" view
        $res = $this->load->view("/admin/config/member", $data, true);
        make_response("output", $res, 1);
    }


    /**
    * processes the data from "member settings" page and saves it
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function Member_settings_save()
    {
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_members_modify');
        if($functionality_enabled_error!==true)
        {
            make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
            return;
        }
        //*******End of functionality limitations********


        $post_vars = prepare_post();
        $validated_errors = $this->config_model->member_validate($post_vars);
        // if there some errors after $_POST data validation
        if(count($validated_errors)==0)
        {
            //save the new values into config file and DB
            $this->config_model->member_set($post_vars);
            //show the page with the updated data
            $data = $this->config_model->member_get();
            $data = $this->config_model->member_settings_vars_add($data);
            $mess = array();
            $mess[] = "saved_ok";
            $res=$this->load->view("/admin/config/member", $data, true);
            make_response("output", $res, 1, create_temp_vars_set($mess));
        }
        else
        {
            make_response("error", create_temp_vars_set($validated_errors), 1);
        }
        simple_admin_log('members_settings_modify',false,(count($validated_errors)!=0),$validated_errors);
    }


    /**
     * edit_sys_emails
     * 
     * @author onagr
     */
    function edit_sys_emails()
    {
        $post=prepare_post();
        $errs=array();
        $mess=array();
        if($post['action']=='save'||$post['action']=='save_default')
        {
            $errs=$this->config_model->config_validate_ex($post,"sys_emails_template");
            if(count($errs)==0)
            {
                $save_err=$this->config_model->sys_emails_set($post);
                if($save_err===true)
                {
                    $mess[]="saved_ok";
                }
                else
                {
                    $errs[]=$save_err;
                }
            }
        }
        if(count($errs)==0)
        {
            if(!isset($post['action'])||$post['action']=='default'||$post['action']==''||$post['action']=='save_default')
            {
                $data=$this->config_model->sys_emails_get($post);
                $data=$this->config_model->add_panel_vars_ex($data,"sys_emails_template");
                //loading template specific variables for replace_keys selectbox
                $data['constants'] = array_merge($data['constants'], $this->mail_model->get_specific_template_variables($data['id']) );
                $res=$this->load->view("/admin/config/sys_emails/edit_sys_template", $data, true);
            }
            else
            {
                $data=$this->config_model->sys_emails();
                $data=$this->config_model->add_panel_vars_ex($data,"sys_emails");
                $res=$this->load->view("/admin/config/sys_emails/sys_emails", $data, true);
            }
            make_response("output", $res, 1,create_temp_vars_set($mess));
        }
        else
        {
            make_response("error",create_temp_vars_set($errs), 1);
        }
        simple_admin_log('system_emails_modify',false,(count($errs)!=0),$errs);
    }
    /*  edit_sys_emails */

    /**
    *    sys_emails
    *    @author onagr
    */
    function sys_emails()
    {
        $data=$this->config_model->sys_emails();
        $data=$this->config_model->add_panel_vars_ex($data,"sys_emails");
        $res=$this->load->view("/admin/config/sys_emails/sys_emails", $data, true);
        make_response("output", $res, 1);
    }
    /*   sys_emails */

    /**
    *    global_setup
    *    @author onagr
    */

    function global_setup()
    {
        $data=$this->config_model->global_get();
        $data=$this->config_model->add_panel_vars_ex($data,"global_setup");
        $res=$this->load->view("/admin/config/global_setup/global_setup", $data, true);
        make_response("output", $res, 1);
    }
    /*   global_setup */

    /**
    *    global_setup_save
    *    @author onagr
    */
    function global_setup_save()
    {
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_global_modify');
        if($functionality_enabled_error!==true)
        {
            make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
            return;
        }
        //*******End of functionality limitations********

        $post=prepare_post();
        $errs=$this->config_model->config_validate_ex($post,"global_setup");
        if(count($errs)==0)
        {
            $this->config_model->global_set($post);
            $data=$this->config_model->global_get();
            $data=$this->config_model->add_panel_vars_ex($data,"global_setup");
            $mess=array();
            $mess[]="saved_ok";
            $mess['date_format']=config_get('system','config','date_format');
            $mess['last_login']=nsdate($this->admin_auth_model->get_last_online());
            $res=$this->load->view("/admin/config/global_setup/global_setup", $data, true);
            make_response("output", $res, 1,create_temp_vars_set($mess));
        }
        else
        {
            make_response("error",create_temp_vars_set($errs), 1);
        }
        simple_admin_log('global_setup_modify',false,(count($errs)!=0),$errs);
    }
    /*   global_setup_save */


    /**
     * global_setup_path_exist
     *
     * @author onagr
     */
    function global_setup_path_exist()
    {
        $res=$this->config_model->path_exist(prepare_post());
        make_response("message", $res, 1);
    }
    /* global_setup_path_exist */



    //**************************************************************
    //*    Additional Fields begining
    //***************************************************************/
    /**
     * Sorting of additional fields
     *
     */
    function add_fields_sort()
    {
        $post=prepare_post();
        $data = $this->config_model->add_fields_sort($post);
        $data['type']='sort';
        make_response("message",create_temp_vars_set($data),1);
    }

	/**
	 * Additional fields
	 *
	 */
    function add_fields()
    {
        $data=$this->config_model->add_fields();
        $res=$this->load->view("/admin/config/add_fields/page", $data, true);
        make_response("output", $res, 1);
    }
	/**
	 * Edit an additional field
	 *
	 * @return mixed
	 */
    function add_field_edit()
    {
        //Get Additional Field ID and check it
        $id = intval($this->input->post('id',0));
        if( !isset($id) or intval($id)<=0 )
        {
            //Display error
            $err = "<{admin_admin_edit_msg_er_invalid_id}>";
            simple_admin_log('additional_field_modify',false,true,"Error: ID is invalid");
            make_response("error", $err, 1);
            return true;
        }
        //Get and check action post param. If it's empty load an initial form
        $action = prepare_text($this->input->post('action',''));
        if( !isset($action) || $action != 'edit' || !isset($id) or intval($id)<=0 )
        {
            $data=array();
            $this->db->limit(1);
            $query = $this->db->get_where(db_prefix.'Add_fields',array('id'=>$id));
            if($query->num_rows() > 0)
            {
                $t=$query->result_array();

                $this->load->model("lang_manager_model");
                $t=$this->lang_manager_model->combine_with_language_data($t,11,array('name'=>'name','descr'=>'descr'),'id',false,$this->default_language_id,&$add_params);
                $data['field'] = $t;
            }
            $data['edit_flag'] = 1;

            $data=$this->config_model->add_panel_vars_ex($data,"add_fields");
            $res=$this->load->view("/admin/config/add_fields/add", $data, true);
            make_response("output", $res, 1);
            return true;
        }

        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_add_fields_modify');
        if($functionality_enabled_error!==true)
        {
            make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
            return;
        }
        //*******End of functionality limitations********

        //Get post params
        $data['title'] = prepare_text($this->input->post('title',''));
        $data['descr'] = prepare_text($this->input->post('description',''));
        $data['required'] = intval($this->input->post('required_mark','false')=='true'?1:0);
        $data['type'] = intval($this->input->post('field_type',''));
        $data['field_value'] = prepare_text($this->input->post('field_values',''));
        $data['default_value'] = prepare_text($this->input->post('default_value',''));
        $data['check_rule'] = intval($this->input->post('check_rule',''));

        //Validate post params
        $errs = check_add_field($data);
        if( count($errs)>0)
        {
            //Display error
            make_response("error",create_temp_vars_set($errs), 1);
            simple_admin_log('additional_field_modify',$id,true,$errs);
            //make_response("error", $err, 1);
            return true;
        }
        //Update data in DB
        $res = $this->config_model->add_fields_edit($id,$data['title'],$data['descr'],$data['required'],$data['type'],$data['field_value'],$data['default_value'],$data['check_rule']);
        if( intval($res) == 1 )
        {
            $mess=array();
            $mess[]="edit_field_success";
            $data=$this->config_model->add_fields();
            $res=$this->load->view("/admin/config/add_fields/page", $data, true);
            make_response("output", $res, 1,create_temp_vars_set($mess));
            simple_admin_log('additional_field_modify',$id);
        }
        else
        {
            $errs[]="edit_field_error";
            make_response("error",create_temp_vars_set($errs), 1);
            simple_admin_log('additional_field_modify',$id,true,"Saving error!");
        }
    }



	/**
	 * Remove an additional field
	 *
	 * @return mixed
	 */
    function add_field_remove()
    {

        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_add_fields_modify');
        if($functionality_enabled_error!==true)
        {
            make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
            return;
        }
        //*******End of functionality limitations********

        $post=prepare_post();
        $data=array();
        $data['type']='remove';
        //$data = $this->config_model->add_fields_sort($post);
        $this->db->delete(db_prefix.'User_add_fields',array('field_id'=>$post['id']));

        $this->db->delete(db_prefix.'Add_fields',array('id'=>$post['id']));
        if( $this->db->affected_rows() > 0 )
        {
            $this->load->model("lang_manager_model");
            $this->lang_manager_model->remove_language_data(11,$post['id']);
            $data['result']='true';
            $data['id']=$post['id'];
        }
        else
        {
            $data['result']='false';
        }
        make_response("message",create_temp_vars_set($data),1);
        simple_admin_log('additional_field_delete',$post['id'],($data['result']=='false'),"Deleting error!");

        return false;
    }
	/**
	 * Add an additional field
	 *
	 * @return mixed
	 */
    function add_fields_add()
    {
        //Get and check action post param. If it's empty load empty form
        $action = prepare_text($this->input->post('action',''));
        if( !isset($action) || $action != 'add' )
        {
            $data=array();
            $data=$this->config_model->add_panel_vars_ex($data,"add_fields");
            $res=$this->load->view("/admin/config/add_fields/add", $data, true);
            make_response("output", $res, 1);
            return true;
        }

        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_config_add_fields_modify');
        if($functionality_enabled_error!==true)
        {
            make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
            return;
        }
        //*******End of functionality limitations********


        //Get post params
        $data['title'] = prepare_text($this->input->post('title',''));
        $data['descr'] = prepare_text($this->input->post('description',''));
        $data['required'] = intval($this->input->post('required_mark','false')=='true'?1:0);
        $data['type'] = intval($this->input->post('field_type',''));
        $data['field_value'] = prepare_text($this->input->post('field_values',''));
        $data['default_value'] = prepare_text($this->input->post('default_value',''));
        $data['check_rule'] = intval($this->input->post('check_rule',''));

        //Validate post params
        $errs = check_add_field($data);
        if( count($errs)>0)
        {
            //Display error
            make_response("error",create_temp_vars_set($errs), 1);
            simple_admin_log('additional_field_add',false,true,$errs);
            return true;
        }
        //Add data in DB
        $res = $this->config_model->add_fields_add($data['title'],$data['descr'],$data['required'],$data['type'],$data['field_value'],$data['default_value'],$data['check_rule']);
        if( intval($res) == 1 )
        {
            $mess=array();
            $mess[]="add_field_success";
            $data=$this->config_model->add_fields();
            $res=$this->load->view("/admin/config/add_fields/page", $data, true);
            make_response("output", $res, 1,create_temp_vars_set($mess));
            simple_admin_log('additional_field_add');
        }
        else
        {
            $errs[]="add_field_error";
            make_response("error",create_temp_vars_set($errs), 1);
            simple_admin_log('additional_field_add',false,true,"Saving error!");
        }
    }

    /**************************************************************
    *    Additional Fields end
    ***************************************************************/


    /*
* List exists languages
* @author Donin
*/

	/**
	 * List exists languages
	 *
	 * @author Donin
	 * @return boolean
	 */
    function language_list()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_multi_language')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $this->load->model('config_model');

        $action = $this->input->post('action','');
        $lang_id = $this->input->post('lang_id','');

        $data = array();

        if( isset($action) and $action == 'set_def_lang' and isset($lang_id) and intval($lang_id)>0 )
        {

            //***********Functionality limitations***********
            if(($message_err=Functionality_enabled('admin_config_languages_modify',1))===true)
            {
                //*******End of functionality limitations********
                unset($message_err);
                $this->db->select('id,name');
                $this->db->where('id',intval($lang_id));
                $this->db->limit(1);
                $query = $this->db->get(db_prefix.'Languages');
                if( $query->num_rows > 0 )
                {
                    $language_info = $query->row();

                    $query = $this->db->update(db_prefix.'Languages',array('is_default'=>0));

                    $this->db->where('id',intval($lang_id));
                    $query = $this->db->update(db_prefix.'Languages',array('is_default'=>1));

                    if( $this->db->affected_rows()!=-1 )
                    {
                        $message = '<{admin_config_lang_editor_set_def_lang_ok}> '.$language_info->name;
                    }
                    else
                    {
                        $message_err = '<{admin_config_lang_editor_set_def_lang_error}>';
                    }
                    simple_admin_log('default_language_modify',intval($lang_id),( $this->db->affected_rows()==-1 ),"Saving error!");
                }
            }
        }


        $data = $this->config_model->language_list();
        $data['languages_list'] = $data['items'];
        $default=0;
        $languages=$data['languages_list'];
        $exist_langs = array();
        $suppo_langs = get_lang_list();
        foreach ($languages as $lang_item)
        {        
            if(intval($lang_item['is_default'])>0)
            {
                $default=$lang_item['id'];
            }
            $exist_langs[$lang_item['lang_code']] = $lang_item['name'];
        }
        $data['aLangs'] = count(array_diff_assoc($suppo_langs, $exist_langs))>0 ? true : false;
        
        if( isset($message) ){ $data['message'] = $message; }
        if( isset($message_err) ){ $data['message_err'] = $message_err; }
        
        $data['temp_vars_set']=array();
        $data['temp_vars_set']['default_language']=$default;
        $data['temp_vars_set']['panel_script']=base_url()."js/admin/config/lang_editor.js";
        $res=$this->load->view("/admin/config/lang_editor/list", $data, true);
        make_response("output", $res, 1);

        return true;

    }
	/**
	 * Delete language
	 *
	 * @return mixed
	 */
    function language_delete()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_multi_language')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $this->load->model('config_model');

        $action = $this->input->post('action','');
        $lang_id = intval($this->input->post('lang_id',0));

        $data = array();
        if( isset($action) and $action=='delete_lang' and isset($lang_id) and intval($lang_id)>0 )
        {
/******************************************************************************          Functionality LIMITATION */
if (($res_r = Functionality_enabled('admin_config_languages_modify',intval($lang_id)))!==true)
{
            make_response("error", $res_r, 1);
            return false;
}
/******************************************************************************   end of Functionality LIMITATION */
            $this->db->where('id',$lang_id);
            $this->db->where('is_default !=','1');
            $query = $this->db->delete(db_prefix.'Languages');

            if ($this->db->affected_rows() == 1)
            {
                $res = $lang_id;

                $this->db->where('language_id',$lang_id);
                $query = $this->db->delete(db_prefix.'Interface_language');
                $this->lang_manager_model->remove_language($lang_id);                                               // Edited by Konstantin X @ 10:11 14.11.2008

                make_response("output", $res, 1);
            }
            else
            {
                $res = '<{admin_config_lang_editor_delete_lang_db_error}>';
                make_response("error", $res, 1);
            }
            simple_admin_log('default_language_delete',intval($lang_id),($this->db->affected_rows()<1),"Deleting error!");
        }

        return false;
    }
	/**
	 * Enter description here...
	 *
	 * @return mixed
	 */
    function language_set()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_multi_language')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $this->load->model('config_model');
        $action         = '';
        $action         = $this->input->post('action');
        $default_lang   = intval($this->input->post('default_lang'));
        $lang_id        = intval($this->input->post('lang_id'));
        $label_name     = prepare_text($this->input->post('label_name'));
        $key_name       = prepare_text($this->input->post('key_name'));
        $label_value    = prepare_text($this->input->post('label_value'));
        $keys           = $this->input->post('keys');
        $values         = $this->input->post('values');
        $message        = '';
        $message_err    = '';

        $filter_str     = prepare_text($this->input->post('filter_str'));                                           // "$filter_str" Added by Konstantin X @ 11:11 13.11.2008
        $filter_fld     = prepare_text($this->input->post('filter_fld'));                                           // "$filter_fld" Added by Konstantin X @ 15:50 01.12.2008

        if( !isset($lang_id) or intval($lang_id)<=0 )
        {
            $res = '<{admin_config_lang_editor_edit_error}>';
            make_response("error", $res, 1);
            simple_admin_log('language_modify',false,true,"Invalid language ID!");
            return false;
        }

        //***********Functionality limitations***********
        if(in_array($action,array('set_keys','new_label','delete_key','update')))
        {
            if(($res=Functionality_enabled('admin_config_languages_modify',intval($lang_id)))!==true)
            {
                make_response("error", $res, 1);
                return false;
            }
        }
        //*******End of functionality limitations********

        if( $action == 'set_keys' )
        {
            if( !isset( $keys) or !is_array($keys) or !isset($values) or !is_array($values) or sizeof($keys)!=sizeof($values))
            {
                $res = '<{admin_config_lang_editor_edit_error}>';
                make_response("error", $res, 1);
                simple_admin_log('language_modify',$lang_id,true,"Invalid params!");
                return false;
            }

            $error = intval(0);
            reset($keys);reset($values);
            for( $i=0;$i<sizeof($keys);$i++)
            {
                if( isset($keys[$i]) and !empty($keys[$i]) and eregi("[a-zA-Z_][a-zA-Z_0-9]+$",$keys[$i])!=false and isset($values[$i]) and !empty($values[$i]) )
                {
                    if( $result = $this->config_model->language_set(prepare_text($keys[$i]),prepare_text($values[$i]),intval($lang_id)) )
                    {
                        if( !$result )
                        {
                            $error++;
                        }
                    }
                }
                else
                {
                    $error++;
                }
            }

            simple_admin_log('language_modify',$lang_id,(intval($error)>0),"Saving error!");

            if( intval($error)<=0)
            {
                $message = "<{admin_config_lang_editor_edit_success}>";
            }
            else
            {
                $message_err = "<{admin_config_lang_editor_edit_error}>";
                make_response("error", $message_err, 1);
                return false;
            }


        }


        if( $action == 'new_label' )
        {
            if( !isset( $label_name) or empty($label_name) or eregi("^[a-zA-Z_][a-zA-Z_0-9]+$",$label_name)==false )
            {
                $res = '<{admin_config_lang_editor_new_label_name_error}>';
                make_response("error", $res, 1);

                simple_admin_log('language_modify',$lang_id,true,"new_label_name_error");
                return false;
            }


            if( $this->config_model->language_set($label_name,$label_value,$lang_id) )
            {
                $message = "<{admin_config_lang_editor_edit_success}>";
                simple_admin_log('language_modify',$lang_id);

            }
            else
            {
                $message_err = "<{admin_config_lang_editor_edit_error}>";
                make_response("error", $message_err, 1);
                simple_admin_log('language_modify',$lang_id,true,"adding_label_error");
                return false;
            }
        }

        if( $action == 'delete_key' )
        {
            if( !isset( $key_name) or empty($key_name) or eregi("[a-zA-Z_][a-zA-Z_0-9]+$",$key_name)==false )
            {
                $res = '<{admin_config_lang_editor_edit_error}>';
                make_response("error", $res, 1);
                simple_admin_log('language_modify',$lang_id,true,"invalid_label");
                return false;
            }


            if( $this->config_model->delete_key_name($key_name,$lang_id) )
            {
                $message = "<{admin_config_lang_editor_edit_success}>";
                simple_admin_log('language_modify',$lang_id);
            }
            else
            {
                $res = '<{admin_config_lang_editor_edit_error}>';
                make_response("error", $res, 1);
                simple_admin_log('language_modify',$lang_id,true,"deleting_label_error");
                return false;
            }
        }

        if( $action == 'update' )
        {
            if( !isset( $label_name) or empty($label_name) or eregi("^[a-zA-Z_][a-zA-Z_0-9]+$",$label_name)==false )
            {
                $res = '<{admin_config_lang_editor_new_label_name_error}>';
                make_response("error", $res, 1);
                return false;
            }


            if( $this->config_model->language_set($label_name,$label_value,$lang_id) )
            {
                $message = "<{admin_config_lang_editor_edit_success}>";
            }
            else
            {
                $message_err = "<{admin_config_lang_editor_edit_error}>";
                make_response("error", $message_err, 1);
                return false;
            }
        }

        $page = 1;
        $per_page = intval($this->input->post('ppage'));
        if( intval($per_page)<=0 )
        {
            $per_page = 5;
        }

        $ppage_set = array(5,10,15,20,30,50);
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = intval(config_get('SYSTEM','CONFIG','default_per_page'));;
            if( intval($per_page)<=0 )
            {
                $per_page = 5;
            }
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }



        $data = array();
        //$total = intval(config_get('SYSTEM','CONFIG','default_per_page'));


        $data = $this->config_model->interface_language_list($lang_id,$current_page,$per_page, $filter_str, $filter_fld); // "$filter_str" Added by Konstantin X @ 11:11 13.11.2008

        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil( intval($data['total']) / intval($per_page) );
        }
        else
        {
            $data['pages'] = 1;
        }

        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;

        $data['message'] = $message;
        $data['message_err'] = $message_err;
        $data['filter_str']  = $filter_str;
        $data['filter_fld']  = $filter_fld;
        $data['lang_id']     = $lang_id;

        fb($data);
        $lng=$this->lang_manager_model->get_languages($data['lang_id']);
        fb($lng);
        if(count($lng))
        {
            $data['language_name']=$lng[0]['name'];
        } 
        $res=$this->load->view("/admin/config/lang_editor/edit", $data, true);

        $tmp_vars = array('lang_id' => $lang_id);
        make_response("output", $res, 1, create_temp_vars_set($tmp_vars));

    }
	/**
	 * Add a new language
	 *
	 * @return boolean
	 */
    function language_add()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_multi_language')!==true)
        {
            return false;
        }
        //*******End of functionality limitations********
        $this->load->model('config_model');

        $action = $this->input->post('action','');
        $default_lang = $this->input->post('default_lang',0);
        $lang_name = prepare_text($this->input->post('lang_name',''));
                        
        $data = array();
        $total = intval(config_get('SYSTEM','CONFIG','default_per_page'));
        
        
        if (isset($action) and $action=='add_lang')
        {
            if (!isset($lang_name) or empty($lang_name) or eregi("^[0-9\'\"`~!@#$%^&*()]+",$lang_name) != false)
            {
                //return error;
                $res = '<{admin_config_lang_editor_add_lang_name_error}>';
                make_response("error", $res, 1);
                simple_admin_log('language_add',false,true,"language_name_error");
                return false;
            } else {
                // check language for exist
                $query = $this->db->get_where(db_prefix.'Languages', array('lang_code' => input_text($lang_name)));
                if ($query->num_rows() > 0)
                {
                    // exists error
                    $res = '<{admin_config_lang_editor_add_lang_exists_error}>';
                    make_response("error", $res, 1);
                    simple_admin_log('language_add',false,true,"language_name_exists");
                    return false;
                }
                else
                {
                    // insert new language
                    $all_langs = get_lang_list();
                    $query = $this->db->insert(db_prefix.'Languages', array('name' => $all_langs[input_text($lang_name)], 'lang_code' => input_text($lang_name)));
                    if ($this->db->affected_rows() == 1)
                    {

                        $new_lang  = intval($this->db->insert_id());
                        $copy_lang = $this->config_model->get_language_id($default_lang, TRUE);
                         
                        //copy languages data
                        if (intval($copy_lang)>0 and intval($new_lang)>0)
                        {
                            $this->db->query(
                            "
                                INSERT INTO `".db_prefix."Interface_language`
                                ( language_id,key_name,content,section )
                                SELECT
                                '".intval($new_lang)."',key_name,content,section
                                FROM `".db_prefix."Interface_language`
                                WHERE
                                language_id = '".intval($copy_lang)."'
                                "
                            );
                        }
                        //_copy languages data

                        $res = true;
                        make_response("output", $res, 1);
                        simple_admin_log('language_add',$new_lang);
                        return false;
                    }
                    else
                    {
                        $res = '<{admin_config_lang_editor_add_lang_db_error}>';
                        make_response("error", $res, 1);
                        simple_admin_log('language_add',false,true,"saving_error");
                        return false;
                    }

                }

            }
        }


        $data = $this->config_model->language_list(1,$total);
        $data['languages'] = $data['items'];
        $languages=$data['languages'];
        $exist_langs = array();
        $suppo_langs = get_lang_list();
        foreach ($languages as $lang_item)
        {        
            $exist_langs[$lang_item['lang_code']] = $lang_item['name'];
        }
        $data['aLangs'] = array_diff_assoc($suppo_langs, $exist_langs);
        $res=$this->load->view("/admin/config/lang_editor/add", $data, true);
        make_response("output", $res, 1);

        return true;
    }


/**
* Language vars filtering
*
* @author Konstantin X | 10:11 13.11.2008
*
* @param   string  $name   name of the language var
* @return  mixed
*/
    function language_filter($name)
    {
        $lang_id = $this->input->post('lang_id');
        $action  = $this->input->post('action');

        $data = Array();
        $data['lang_id'] = $lang_id;

        if($action == 'instruction')
        {
            $lng=$this->lang_manager_model->get_languages($data['lang_id']);
            fb($lng);
            if(count($lng))
            {
                $data['language_name']=$lng[0]['name'];
            }            
            $res = $this->load->view("/admin/config/lang_editor/mass_translate", $data, true);
            make_response("output", $res, 1);
            return true;
        }

    }

/**
* Mass translate instruction
*
* @author Konstantin X | 14:09 03.09.2008
* @return mixed
*/
    function language_translate()
    {
        $lang_id = $this->input->post('lang_id');
        $action  = $this->input->post('action');

        $data = Array();
        $data['lang_id'] = $lang_id;

        if($action == 'instruction')
        {
            $lng=$this->lang_manager_model->get_languages($data['lang_id']);
            fb($lng);
            if(count($lng))
            {
                $data['language_name']=$lng[0]['name'];
            } 
            $res = $this->load->view("/admin/config/lang_editor/mass_translate", $data, true);
            make_response("output", $res, 1, create_temp_vars_set($data));
            return true;
        }

    }


/*** Update language constant** @author Konstantin X | 14:09 03.09.2008* @return true*/    
    /* function language_update()
    {
        $upd_data['lc_data']    = prepare_text($this->input->post('lc_data'));
        $upd_data['lc_name']    = prepare_text($this->input->post('lc_name'));
        $upd_data['lang_id']    = $this->input->post('lang_id');
        $action                 = prepare_text($this->input->post('action'));

        if($action == 'update')
        {
            $this->config_model->lang_update($upd_data);
            $res = $this->load->view("/admin/config/lang_editor/mass_translate", $data, true);
        }
        $res=$this->load->view("/admin/config/lang_editor/edit", $data, true);
        make_response("output", $res, 1);
        make_response("output", $res, 1);
        return true;
    } */


    /**
    * Generate XML-file for translator and HTTP-headers to save file
    *
    * @author Konstantin X | 14:09 03.09.2008
    * @param ineteger $lang_id
    * @param integer $part
    */
    function language_getXML($lang_id,$part=0)
    {
        $this->load->library('user_agent');
        $lang     = intval($lang_id);
        $data     = $this->config_model->lang_translate($lang,intval($part));

        if($data["result"])
        {
            $filename    = 'ns_'. $data["lang_name"];

            $words_total = 0;
            $words_unit  = 0;
            $xml_header  = '';
            $xml_content = "<body>\n";
            $i           = 0;

            foreach($data["items"] as $row)
            {
                $i++;
                $words_unit  = str_word_count($row["content"]);
                $words_total+= $words_unit;

                $xml_content.= "\t".'<trans-unit id="'. $row["key_name"] .'">'."\n";
                $xml_content.= "\t\t".'<source>'. htmlspecialchars($row["content"]) .'</source>'."\n";
                $xml_content.= "\t\t<target></target>\n";
                $xml_content.= "\t\t".'<count-group name="word count">'."\n";
                $xml_content.= "\t\t\t".'<count count-type="word count" unit="word">'. $words_unit .'</count>'."\n";
                $xml_content.= "\t\t</count-group>\n";
                $xml_content.= "\t</trans-unit>\n";
            }
            $xml_content.= '</body></file></xliff>';

            $xml_header = "<?xml version=\"1.0\" ?>\n";
            $xml_header.= "<!DOCTYPE xliff PUBLIC \"-//XML-INTL XLIFF-XML 1.0//EN\" \"http://www.oasis-open.org/committees/xliff/documents/xliff.dtd\">\n";
            $xml_header.= "<xliff version=\"1.0\">\n";
            $xml_header.= "<file source-language=\"en-US\" target-language=\"". $data["lang_name"] ."\" datatype=\"xml\" original=\"". $filename .".xlf\">\n";
            $xml_header.= "<header></header>\n";

            $xml_all = $xml_header . $xml_content;

            if ($this->agent->is_browser())
            {
                $agent = $this->agent->browser();
            }

            header('Content-Type: text/xml');
            header('Content-Encoding: none');
            header('Expires: '. gmdate('D, d M Y H:i:s') .' GMT');

            if ($agent == 'Internet Explorer')
            {
                header('Content-Disposition: inline; filename="'. $filename .'.xlf"');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
            }else{
                header('Content-Disposition: attachment; filename="'. $filename .'.xlf"');
                header('Pragma: no-cache');
            }

            echo $xml_all;
            simple_admin_log('language_export',$lang);
            exit;
        }
        simple_admin_log('language_export',$lang,true,"export_error");
    }

    /**
     * Get XML-file from translator and generate array
     *
     * @author Konstantin X | 12:09 04.09.2008
     * @param integer $ilang_id
     */
    function language_putXML($ilang_id)
    {
        $error_msg = '';
        $array_upd = array();
        $contents  = '';
        $langs     = array();
        $match     = array();

        $sReturn = 'success';                                                                                       // Create return string

        if (isset($_FILES['input_lang_file']))
        {
            if (is_uploaded_file($_FILES['input_lang_file']['tmp_name']))
            {
                $filename = $_FILES['input_lang_file']['tmp_name'];
                $filetype = $_FILES['input_lang_file']['type'];

                $aNames = explode(".", $_FILES['input_lang_file']['name']);
                $nr     = count($aNames);
                $ext    = $aNames[$nr-1];

                if ($ext != "xlf" and $ext != "xml")
                {
                    simple_admin_log('language_import',false,true,"invalid_file_ext");

                    echo replace_lang('<{admin_msg_er_0026}>');                                                     // admin_msg_er_0026 = "Invalid file extension"
                    exit;
                }

                $handle = fopen($filename, "r");
                $contents = fread($handle, filesize($filename));
                fclose($handle);
            }
        } else {
            simple_admin_log('language_import',false,true,"empty_file");

            echo replace_lang('<{admin_msg_er_0024}>');                                                             // admin_msg_er_0024 = "File is empty"
            exit;
        }

        $reg_exp = '/<file source-language="([\w-]+)" target-language="([\w-]+)".*/';
        if (!preg_match($reg_exp, $contents, $langs))
        {
            simple_admin_log('language_import',false,true,"invalid_file_content");
            echo replace_lang('<{admin_msg_er_0027}>');                                                             // admin_msg_er_0027 = "Invalid file content"
            exit;
        }
        $array_upd['lang'] = $langs[2];

        $lang_id = $this->config_model->get_language_id($array_upd['lang'], TRUE);                                  // Get lang_id by ISO-639 code
        if(intval($lang_id)==0)
        {
            $lang_id=$this->config_model->language_add($array_upd['lang']);
        }
        
            if (intval($lang_id)==0)
            {
                simple_admin_log('language_import',false,true,"invalid_lang_id");
                echo replace_lang('<{admin_msg_er_0029}>');                                                             // admin_msg_er_0029 = "Invalid language"
                exit;
            }


/******************************************************************************          Functionality LIMITATION */
if (($res_report = Functionality_enabled('admin_config_languages_modify',intval($lang_id))) !== true)
{
            echo replace_lang($res_report);                                                                         // if try to rewrite default lang
            exit;
}
/******************************************************************************   end of Functionality LIMITATION */


        $reg_exp = '/<trans-unit id="([\w]*?)"[^>]*?>[\s\S]*?[\s]*?<target[^>]*?>([^><]*?)<\/target>/';
        preg_match_all($reg_exp, $contents, $match);

        unset($match[0]);
#        unset($match[2]);

        foreach ($match[2] as $key=>$val)
        {
          $array_upd['data'][$match[1][$key]] = htmlspecialchars_decode($val);
        }

        $data = $this->config_model->lang_translate_put($array_upd);

        if ($data['status'])
        {
            simple_admin_log('language_import');
            echo 'success';
            exit;
        } else {
            simple_admin_log('language_import',false,true,"import_error");
            echo replace_lang($data['message']);                                                                    // error message
            exit;
        }
    }


    /******************************************** BAN IP *************************************/

    /**
    *
    * Shows banned IP addresses list
    *
    * @author Drovorubov
    *
    */
    function ban_ip_list()
    {
        $post=prepare_post();
        $err=array();
        $mess=array();

        $data=array();
		
		if(isset($post['action']) && ($post['action']=='delete' || $post['action']=='add'))
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_ban_ip_modify');
            if($functionality_enabled_error!==true)
            {
                $err[]=$functionality_enabled_error;
            }
            else
            {
                //*******End of functionality limitations********

                if($post['action']=='delete')
                {
                    if(isset($post['ip']))
                    {
                        if($this->config_model->ban_ip_delete($post['ip']))
                        {
                            $mess[]='deleted_ok';
                        }
                        else
                        {
                            $err[]='not_deleted';
                        }
                    }
                    else
                    {
                        $err[]='ip_empty';
                    }
                }
                if($post['action']=='add')
                {
                    $err=$this->config_model->config_validate_ex($post,"ban_ip");
                    fb($post,'post');
                    if(count($err)==0)
                    {
                        if($this->config_model->ban_ip_check_ip($post['ip']))
                        {
                            $err[]='ip_exist';
                        }
                        else if($this->config_model->ban_ip_add($post))
                        {
                            $mess[]='saved_ok';
                        }
                        else
                        {
                            $err[]='not_saved';
                        }
                    }
                }
            }
        }
		
		if(count($err)>0)
        {
            make_response("error", create_temp_vars_set($err), 1);
            return;
        }
        else
        {
            $data=$this->config_model->ban_ip_list($post);
            $data=$this->config_model->add_panel_vars_ex($data,"ban_ip");
        }

        $data['ip'] = isset($post['ip']) ? $post['ip'] : '';
        $data['reason'] = isset($post['reason']) ? $post['reason'] : '';
        //Prepare list for view
        $data['items'] = $this->_prepare_iplist($data['items']);
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if( count($data['items']) > 0 )
        {
            foreach( $data['items'] as $item )
            {
                //Prepare node
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/config/banned_ip/node", $node, true);
            }
        }
        else
        {
            $data['rows'] .= $this->load->view("/admin/config/banned_ip/empty", array(), true);
        }
		$data['ban_list'] = $this->load->view("/admin/config/banned_ip/page_list", $data, true);
		$data['label'] = '<{admin_config_ban_ip_label}>';
		$data['desc'] = '<{admin_config_ban_ip_label_desc}>';
        //Get the whole page
        $res=$this->load->view("/admin/config/banned_ip/page", $data, true);
        make_response("output", $res, 1,create_temp_vars_set($mess));
    }

	/**
    *
    * Add IP addresse to banned list
    *
    * @author Drovorubov
    *
    */
	function ban_ip_add()
    {
        $post=prepare_post();
        $err=array();
        $mess=array();

        $data=array();
		$res=array();
		
		$data=$this->config_model->add_panel_vars_ex($data,"ban_ip");

        $data['ip'] = isset($post['ip']) ? $post['ip'] : '';
        $data['reason'] = isset($post['reason']) ? $post['reason'] : '';
		$data['label'] = '<{admin_config_ban_ip_add_label}>';
		$data['desc'] = '<{admin_config_ban_ip_add_label_desc}>';
		$data['ban_add'] = $this->load->view("/admin/config/banned_ip/page_add", $data, true);
        //Get the whole page
        $res=$this->load->view("/admin/config/banned_ip/page", $data, true);
        make_response("output", $res, 1,create_temp_vars_set($mess));
    }

    /**
    *
    * Prepares data IP addresses list for showing in the view
    *
    * @author Drovorubov
    * @param array $data
    * @return array
    */
    function _prepare_iplist($data)
    {
        if( is_array($data) && count($data) > 0)
        {
            foreach($data as $key=>$val)
            {
                // Convert field Name
                $data[$key]['ip'] = output($data[$key]['ip']);
                // Convert field Descr
                $data[$key]['reason'] = output($data[$key]['reason']);
                $data[$key]['reason'] = wordwrap($data[$key]['reason'],60,'<br> ',1);
            }
        }
        else
        {
            $data = array();
        }
        return $data;
    }



    /**
    *
    * Validates form values of IP addresses fields
    *
    * @author Drovorubov
    * @param array $param
    * @return string
    */
    function _check_banip_fields($param)
    {
        $rv = '';
        foreach($param as $key=>$val)
        {
            // Check empty fields
            if($val == '')
            {
                $rv = "<{admin_config_ban_ip_error_empty_fields}>";
                break;
            }
            //Check fields length
            else if( $key == 'ip' )
            {
                if( mb_strlen($val) > 31 )
                {
                    $rv = "<{admin_config_ban_ip_error_field_ip_toolong}>";
                    break;
                }
                if( !preg_match('/^(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/',$val) && !preg_match('/^(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)-(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/',$val) )
                {
                    $rv = "<{admin_config_ban_ip_error_field_ip_wrong}>";
                    break;
                }
            }
            else if( $key == 'reason' && mb_strlen($val) > 200)
            {
                $rv = "<{admin_config_ban_ip_error_field_reason_toolong}>";
                break;
            }
        }
        return $rv;
    }


    /******************************************** _BAN IP *************************************/
	/**
	 * Security settings
	 * 
	 * @author Konstantin X
	 *
	 */
    function security_settings(){
        /**************************************************************
*   Security settings
*   @author Konstantin X
*   @short_description = security settings
*   @description = Full description of method
***************************************************************/
        if($this->input->post('action') == 'save'){

            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_security_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", $functionality_enabled_error, 1);
                return;
            }
            //*******End of functionality limitations********



            $input = Array();
            $pre_check = '';


            $input['login_remember_me']   = ($this->input->post('login_remember_me') == 'true') ? 1 : 0;
            $input['login_try_capcha']    = intval($this->input->post('login_try_capcha'));
            if($input['login_try_capcha'] < 1 || $input['login_try_capcha'] > 32768)
            {
                $pre_check = '<{admin_msg_er_0004}> ';                                                              //login_try_captcha_fail;
            }

            $input['login_try_block_ip']  = intval($this->input->post('login_try_block_ip'));
            if($input['login_try_block_ip'] < 1 || $input['login_try_block_ip'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0005}> ';                                                            //login_try_block_ip fail;
            }

            $input['ip_block_timeout']    = intval($this->input->post('ip_block_timeout'));
            if($input['ip_block_timeout'] < 60 || $input['ip_block_timeout'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0006}> ';                                                            //ip_block_timeout fail;
            }

            $input['ip_block_selected_period']    = intval($this->input->post('ip_block_selected_period'));         // Added by Konstantin X @ 12:08 04.08.2008
            if($input['ip_block_selected_period'] < 60 || $input['ip_block_selected_period'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0020}> ';                                                            //ip_block_selected_period fail;
            }

            $input['login_block_message'] = $this->input->post('login_block_message');
            if(mb_strlen($input['login_block_message']) > 1024)
            {
                $pre_check .= ' <{admin_msg_er_0007}> ';                                                            //login_block_message fail;
            }

            $input['autoban_count']       = intval($this->input->post('autoban_count'));
            if($input['autoban_count'] < 2 || $input['autoban_count'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0008}> ';                                                            //autoban_count fail;
            }

            $input['autoban_timeout']     = intval($this->input->post('autoban_timeout'));
            if($input['autoban_timeout'] < 60 || $input['autoban_timeout'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0009}> ';                                                            //autoban_timeout fail;
            }

            $input['session_expiration']     = intval($this->input->post('session_expiration'));                    // Transfered form MEMBER_SETTINGS by Konstantin X @ 11:08 05.08.2008
            if($input['session_expiration'] < 60 || $input['session_expiration'] > 32768)
            {
                $pre_check .= ' <{admin_msg_er_0021}> ';                                                            //session_expiration fail;
            }

            $input['max_length'] = intval($this->input->post('captcha_char_max'));
            $input['min_length'] = intval($this->input->post('captcha_char_min'));

            if($input['max_length'] < $input['min_length'] || $input['max_length'] > 6)
            {
                $input['max_length'] = $input['max_length'] > $input['min_length'] ? $input['max_length'] : $input['min_length'];
                $pre_check .= ' <{admin_msg_er_0010}> ';                                                            // captcha char max fail
            }

            if($input['min_length'] < 2 || $input['min_length'] > $input['max_length'])
            {
                $pre_check .= ' <{admin_msg_er_0011}> ';                                                            //captcha_char_min fail;
            }

            if($pre_check){
                make_response("error", $pre_check, 1);
                simple_admin_log('security_settings_modify',false,true,"validation_error");
                return;
            }

            $result = $this->config_model->security_set($input);
            simple_admin_log('security_settings_modify',false,($result !== true),"saving_error");
            if($result !== true){
                make_response("error", $result, 1);
                return;
            }
        }

        $data = Array();

        $result = $this->config_model->security_get();
        //  Prepare output fields
        $data['login_remember_me']        = output($result['login_remember_me']);
        $data['login_try_capcha']         = output($result['login_try_capcha']);
        $data['login_try_block_ip']       = output($result['login_try_block_ip']);
        $data['ip_block_timeout']         = output($result['ip_block_timeout']);
        $data['ip_block_selected_period'] = output($result['ip_block_selected_period']);                            // Added by Konstantin X @ 12:08 04.08.2008
        $data['login_block_message']      = output($result['login_block_message']);
        $data['autoban_count']            = output($result['autoban_count']);
        $data['autoban_timeout']          = output($result['autoban_timeout']);

        $data['session_expiration']       = output($result['session_expiration']);                                  // Transfered form MEMBER_SETTINGS by Konstantin X @ 11:08 05.08.2008

        $data['captcha_char_min']         = output($result['min_length']);
        $data['captcha_char_max']         = output($result['max_length']);

        $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
        $temp_vars_set['ValidationError']="<{admin_msg_validation_fail}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/config/security.js";
        $data['temp_vars_set']=$temp_vars_set;

        // Cancel changes, are you sure?

        $messages = array();
        $messages['ok_1'] = '<{admin_msg_ok_0001}>';                                                                // Settings was changed successfully
        $data['messages'] = $messages;

        //  Prepare error messages array
        $mess_err = array();
        $mess_err['main_error']               = '<{admin_msg_er_0000}>';
        $mess_err['login_try_capcha']         = '<{admin_msg_er_000111}>';                                            // Value mast be integer and smaller than 32768
        $mess_err['login_try_block_ip']       = '<{admin_msg_er_000111}>';
        $mess_err['ip_block_timeout']         = '<{admin_msg_er_0001}>';
        $mess_err['ip_block_selected_period'] = '<{admin_msg_er_0001}>';
        $mess_err['login_block_message']      = '<{admin_msg_er_0002}>';                                            // Maximum allowed 1024 symbols
        $mess_err['captcha_char_min']         = '<{admin_msg_er_0003}>';                                            // Value mast be integer and smaller than 11
        $mess_err['captcha_char_max']         = '<{admin_msg_er_0003}>';
        $mess_err['autoban_count']            = '<{admin_msg_er_000112}>';
        $mess_err['autoban_timeout']          = '<{admin_msg_er_0001}>';
        $mess_err['session_expiration']       = '<{admin_msg_er_0001}>';                                            // Transfered form MEMBER_SETTINGS by Konstantin X @ 11:08 05.08.2008

        $data['mess_err'] = $mess_err;

        $res=$this->load->view("/admin/config/security", $data, true);
        make_response("output", $res, 1);
    }/***                      Security settings                                                           ***/
	/**
	 * Status settings
	 *
	 * @author Konstantin X
	 */
    function status_settings()
    {
        /**************************************************************
*   Status settings
*   @author Konstantin X
*   @short_description Change system status
*   @description GET/SET System status and Offline message
***************************************************************/
        $input = Array();
        $pre_check = '';

        if($this->input->post('action') == 'switch')
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_status_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", $functionality_enabled_error, 1);
                return;
            }
            //*******End of functionality limitations********

            $state = $this->config_model->status_switch();
            simple_admin_log('status_modify',false,($state !== true),"changing_error");
            if($state !== true){
                make_response("error", $state, 1);
                return;
            }
        }
        //------------------------------------------------------------------------------------------------

        if($this->input->post('action') == 'save'){

			$functionality_enabled_error=Functionality_enabled('admin_config_status_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", $functionality_enabled_error, 1);
                return;
            }

            $input = Array();
            $pre_check = '';

            $input['offline_msg'] = $this->input->post('offline_msg');
            if(mb_strlen($input['offline_msg']) > 512)
            {
                $pre_check .= ' <{admin_msg_er_0012}> ';//offline_msg fail;
            }
            //------------------------------------------------------------------------------------------------
            if($pre_check){
                make_response("error", $pre_check, 1);
                simple_admin_log('status_message_modify',false,true,"validation_error");
                return;
            }

            $result = $this->config_model->status_set($input);
            simple_admin_log('status_message_modify',false,($result !== true),"saving_error");
            if($result !== true){
                make_response("error", $result, 1);
                return;
            }
        }

        $data = Array();

        $result = $this->config_model->status_get();
        //  Prepare output fields
        $data['online']      = output($result['online']);
        $data['offline_msg'] = output($result['offline_msg']);
        //      print_r($result);
        $temp_vars_set=array();
        $temp_vars_set['panel_script']=base_url()."js/admin/config/status.js";
        $temp_vars_set['ValidationError'] = '<{admin_msg_validation_fail}>';
        $temp_vars_set['cancelText'] = '<{admin_msg_cancel}>';
        $temp_vars_set['status'] = intval(config_get('SYSTEM','STATUS','online'))>0 ? "true" : "false";
        $data['temp_vars_set'] = $temp_vars_set;

        $messages = array();
        $messages['ok_1'] = '<{admin_msg_ok_0001}>'; // Settings was changed successfully
        $data['messages'] = $messages;

        $mess_err = array();
        $mess_err['main_error'] = '<{admin_msg_er_0000}>';
        $mess_err['offline_msg'] = '<{admin_msg_er_0013}>'; // Maximum allowed 512 symbols
        $data['mess_err'] = $mess_err;

        $res=$this->load->view("/admin/config/status", $data, true);
        make_response("output", $res, 1,true);
    }/***                      Status settings                                                           ***/

	/**
	 * Enter description here...
	 *
	 * @return mixed
	 */
    function Manage_pages()
    {
        //***********Functionality limitations***********
        //if(Functionality_enabled('admin_config_pages')!==true)
        //{
            //return false;
        //}
        //*******End of functionality limitations********
        $data=array();
        $post=prepare_post();
        $errs=array();
        $data=$this->config_model->manage_pages($post);
        $data=$this->config_model->add_panel_vars_ex($data,"manage_pages");
        $res = $this->load->view("/admin/config/pages_list", $data, true);
        make_response("output", $res, 1);
    }
	/**
	 * Enter description here...
	 *
	 * @return mixed
	 */
    function Manage_page_action()
    {
        //***********Functionality limitations***********
		$functionality_enabled_error=Functionality_enabled('admin_config_pages');
        if(Functionality_enabled('admin_config_pages')!==true)
        {
            //$data=$post;
            $data['disabled']=1;
            $data['status']=0;
            $data['error']=$functionality_enabled_error;
            make_response("message",create_temp_vars_set($data),1);
			return;
        }
        //*******End of functionality limitations********
        $post=prepare_post();
        if(isset($post['id']) && intval($post['id'])>0)
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_pages_modify',intval($post['id']));
            if($functionality_enabled_error==true && intval($post['id'])==1)
            {
                $functionality_enabled_error=Functionality_enabled('admin_config_pages_tos_modify');
            }
            
            if($functionality_enabled_error!==true)
            {
                $data=$post;
                $data['disabled']=1;
                $data['status']=0;
                $data['error']=$functionality_enabled_error;
                make_response("message",create_temp_vars_set($data),1);
                return;
            }
            //*******End of functionality limitations********
        }
        $data = $this->config_model->manage_page_action($post);
        simple_admin_log('menage_pages'.(isset($post['type']) ? '_'.$post['type'] : ''),(isset($post['id']) ? $post['id'] : false),(isset($data['status']) && $data['status'] == 0),isset($data['error'])?$data['error']:"");
        make_response("message",create_temp_vars_set($data),1);
    }
	/**
	 * Enter description here...
	 *
	 */
    function Manage_news()
    {
        $data=array();
        $post=prepare_post();
        fb($post,"post"); 
        $errs=array();
        $data=$this->config_model->manage_news($post);
        $data=$this->config_model->add_panel_vars_ex($data,"manage_news");
        $res = $this->load->view("/admin/config/manage_news", $data, true);
        make_response("output", $res, 1);
    }
	/**
	 * Enter description here...
	 *
	 */
    function Manage_news_action()
    {
        $post=prepare_post();
        if(isset($post['id']) && intval($post['id'])>0)
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_news_modify',intval($post['id']));
            if($functionality_enabled_error!==true)
            {
                $data=$post;
                $data['disabled']=1;
                $data['status']=0;
                $data['error']=$functionality_enabled_error;
                make_response("message",create_temp_vars_set($data),1);
                return;
            }
            //*******End of functionality limitations********
        }
        
        $data = $this->config_model->manage_news_action($post);
        fb($post, "post");
        fb($data, "data");
        if(isset($data['date']))
        {
            $data['date']=nsdate($data['date'],false);
        }
        simple_admin_log('menage_news'.(isset($post['type']) ? '_'.$post['type'] : ''),(isset($post['id']) ? $post['id'] : false),(isset($data['status']) && $data['status'] == 0),isset($data['error'])?$data['error']:"");
        make_response("message",create_temp_vars_set($data),1);
    }
	
    function Host_plans_settings()
    {
        //***********Functionality limitations***********
/*        if(Functionality_enabled('admin_config_payment')!==true)
        {
            return false;
        }
*/        //*******End of functionality limitations********
        $data = Array();
        $data = $this->config_model->hosting_get($data);
        $data = $this->config_model->add_panel_vars_ex($data,"host_settings");
fb($data,'Settings data2');
        $res = $this->load->view("/admin/host_plans/host_settings", $data, true);
        make_response("output", $res, 1);
    }
    
        function Domain_settings()
    {
        //***********Functionality limitations***********
/*        if(Functionality_enabled('admin_config_payment')!==true)
        {
            return false;
        }
*/        //*******End of functionality limitations********     
        
        $data = Array();
        if(!$data = $this->config_model->domain_get($data)){        
        $data['service_username']='';
        $data['service_password']='';
        $data['service_langpref']='';
        $data['service_parentid']='';
        $data['debug']=0;
        $data['https_url']=0;       
      }        
        $languages = _config_get('member_pages','profile_domain','customerlangpref','languages');
        foreach($languages as $k=>$v){
            $data['service_lang'][]=array(
            'language_code'=>$k,
            'language_name'=>$v,
            'language_selected'=>(isset($data['service_langpref']) && $data['service_langpref']==$k ? "selected" : ""));
            }   
        $data = $this->config_model->add_panel_vars_ex($data,"domain_settings");        
     fb($data,'data - ');
        $res = $this->load->view("/admin/domain/domain_settings", $data, true);
        make_response("output", $res, 1);
    }

    
   /**
    * processes the data from "Domain settings" page and saves it
    *
    * @author 
    * @copyright 2009
    */
    function Domain_settings_save($mode)
    {    
        $post_vars = prepare_post();          
        //if we get "save" mode - then validate and save the data
        if ($mode == "save")
        {
            //***********Functionality limitations***********              
            $functionality_enabled_error=Functionality_enabled('admin_config_domain_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********

            // save and validate data from the post
            foreach ($post_vars as $key => $value)
	        {
	            if(in_array($key,array('https_url','debug','service_password','service_langpref','service_parentid', 'service_username')))
	            {
	             $data[$key] = $value;
	            }
	        }
            $validated_errors = $this->config_model->config_validate_ex($data,"domain_settings");            
            // if there some errors after $_POST data validation
            if(count($validated_errors)==0)
            {
                //save the new data from the post to ht_sys_config.cfg file                
                $this->config_model->domain_set($post_vars);
                //show the page with the updated data
                $data = Array();
                $data = $this->config_model->domain_get($data);
                $languages = _config_get('member_pages','profile_domain','customerlangpref','languages');
                foreach($languages as $k=>$v)
                    {
                        $data['service_lang'][]=array(
                        'language_code'=>$k,
                        'language_name'=>$v,
                        'language_selected'=>(isset($data['service_langpref']) && $data['service_langpref']==$k ? "selected" : ""));
                    }   
                $data = $this->config_model->add_panel_vars_ex($data,"domain_settings");
                fb($data,'data save'); 
                $mess = array();
                $mess[] = "saved_ok";
                $res=$this->load->view("/admin/domain/domain_settings", $data, true);
                make_response("output", $res, 1, create_temp_vars_set($mess));
            }
            else
            {
                //there was some validating errors, display them
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }

            simple_admin_log('domain_settings_modify',false,(count($validated_errors)!=0),$validated_errors);
        }
        //if we get "test connection" mode - then try to connect DirectI host
        else if ($mode=="test")
        {
        //***********Functionality limitations***********
               
            $functionality_enabled_error=Functionality_enabled('admin_config_domain_test');
            if($functionality_enabled_error!==true)
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                fb(create_temp_vars_set(array($functionality_enabled_error)),"error");
                return;
            }
            //*******End of functionality limitations********
        	$CI = &get_instance();
        	$CI->load->model('domain_manager_model');
/**
 * @todo used while only DirectI metod
 */
        	$method = 'ns_directi';
            if ($CI->domain_manager_model->_load_domain_method($method)===FALSE)
            {
            	$validated_errors[] = "tested_fail";
                $connection_test_result = false;
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }
            else
            {
            //set params of connection
	            $config = array();	  
	            foreach ($post_vars as $key => $value)
		        {
		            if(in_array($key,array('https_url','debug','service_password','service_langpref','service_parentid', 'service_username')))
		            {
			            if(in_array($key,array('https_url','debug')))
			            {
			                switch (mb_strtolower($value))
			                {
			                case "true":
			                    $config[$key]='1';
			                    break;
			                case "false":
			                    $config[$key]='0';
			                    break;
			                }
		            	}
		            	else 
		            	{
		            		$config[$key] = $value;
		            	}
		            }
		        }                
	            //init method parameters
	            $CI->$method->init($config);
	
	            $validated_errors = array(); 
	            //$this->config_model->config_validate_ex($config,"domain_settings");
	            
	            $connection_test_result = true;
	            //check if there was some errors on load method
	            $connection_test_result = $CI->$method->testConnect();

                if ($connection_test_result === false)
	            {
	            	$validated_errors[] = "tested_auth_fail";
	            	foreach ($CI->$method->errors as $host_errors)
	            	{
	            	    $validated_errors[] = $host_errors;
	            	}
	            	make_response("error", create_temp_vars_set($validated_errors), 1);
	            }
				else 
                {
                    //if there was no errors - display "tested_ok" message
                    //show the page with the updated data
                    $data = Array();
                    $data = $this->config_model->domain_get($data);
                    $data = $post_vars;
                    $languages = _config_get('member_pages','profile_domain','customerlangpref','languages');
                    foreach($languages as $k=>$v)
                    {
                        $data['service_lang'][]=array(
                        'language_code'=>$k,
                        'language_name'=>$v,
                        'language_selected'=>(isset($data['service_langpref']) && $data['service_langpref']==$k ? "selected" : ""));
                    }
	                //overlay previuos variables with the data from $_POST
	               
	
	                $data = $this->config_model->add_panel_vars_ex($data,"domain_settings");
	                $mess = array();
	                $mess[] = "tested_ok";
	                $mess[] = "Host " . $connection_test_result;
	                $res=$this->load->view("/admin/domain/domain_settings", $data, true);
	                
	                make_response("output", $res, 1, create_temp_vars_set($mess));
	            }
            }
            simple_admin_log('domain_connection_test',false,(!$connection_test_result),$validated_errors);
        }
    }
   /**
    * processes the data from "Host_plans settings" page and saves it
    *
    * @author Korchinskij G.G.
    * @copyright 2009
    */
    function Host_plans_settings_save($mode)
    {
        $post_vars = prepare_post();
        //if we get "save" mode - then validate and save the data
        if ($mode == "save")
        {
            //***********Functionality limitations***********
               
            $functionality_enabled_error=Functionality_enabled('admin_config_hosting_modify');
            if($functionality_enabled_error!==true)
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********

            // save and validate data from the post
            $validated_errors = $this->config_model->config_validate_ex($post_vars,"host_settings");
            // if there some errors after $_POST data validation
            if(count($validated_errors)==0)
            {
                //save the new data from the post to ht_sys_config.cfg file
                $this->config_model->hosting_set($post_vars);
                //show the page with the updated data
                $data = Array();
                $data = $this->config_model->hosting_get($data);
                $data = $this->config_model->add_panel_vars_ex($data,"host_settings");
                $mess = array();
                $mess[] = "saved_ok";
                $res=$this->load->view("/admin/host_plans/host_settings", $data, true);
                make_response("output", $res, 1, create_temp_vars_set($mess));
            }
            else
            {
                //there was some validating errors, display them
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }

            simple_admin_log('host_settings_modify',false,(count($validated_errors)!=0),$validated_errors);
        }
        //if we get "test connection" mode - then try to connect SMTP host
        else if ($mode=="test")
        {
        	$CI = &get_instance();
        	$CI->load->model('host_manager_model');
        //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_config_hosting_test');
            if($functionality_enabled_error!==true) 
            {
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********
/**
 * @todo used while only cPanel and WHM metod
 */
        	$method = 'ns_whm';
            if ($CI->host_manager_model->_load_hosted_method($method)===FALSE)
            {
            	$validated_errors[] = "tested_fail";
                $connection_test_result = false;
                make_response("error", create_temp_vars_set($validated_errors), 1);
            }
            else
            {
            //set params of connection
            	            	
	            $config['host_host'] = $post_vars['host_host'];
	            $config['host_port'] = $post_vars['host_port'];
	            $config['host_user'] = $post_vars['host_user'];
	            $config['host_pass'] = $post_vars['host_pass'];
	
	            //init method parameters
	            $CI->$method->init($config['host_host'], $config['host_port'], $config['host_user'], $config['host_pass']);
	
	            $validated_errors = array();
	            $connection_test_result = true;
	            //check if there was some errors on load method
	            $connection_test_result = $CI->$method->version();

                if ($connection_test_result==false)
	            {
	            	$validated_errors[] = "tested_auth_fail";
	            	foreach ($CI->$method->errors as $host_errors)
	            	{
	            	    $validated_errors[] = $host_errors;
	            	}
	            	make_response("error", create_temp_vars_set($validated_errors), 1);
	            }
				else 
	            {
	            	//if there was no errors - display "tested_ok" message
	                //show the page with the updated data
	                $data = Array();
	                $data = $this->config_model->hosting_get($data);
	
	                //overlay previuos variables with the data from $_POST
	                $data = $post_vars;
	
	                $data = $this->config_model->add_panel_vars_ex($data,"host_settings");
	                $mess = array();
	                $mess[] = "tested_ok";
	                $mess[] = $connection_test_result;
	                $res=$this->load->view("/admin/host_plans/host_settings", $data, true);
	                make_response("output", $res, 1, create_temp_vars_set($mess));
	            }
            }
            simple_admin_log('hosting_connection_test',false,(!$connection_test_result),$validated_errors);
        }
    }
	
	function manage_page_main()
	{
		$data = Array();
        //$data = $this->config_model->hosting_get($data);
        $data = $this->config_model->add_panel_vars_ex($data,"main_page_settings");
		$data['page_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'page_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'page_amount')) : 3;
		$data['news_amount'] = intval(config_get('SYSTEM','MAIN_PAGE', 'news_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE', 'news_amount')) : 3;
		$data['admin_msg'] = (config_get('SYSTEM','MAIN_PAGE', 'admin_msg')!='' or config_get('SYSTEM','MAIN_PAGE', 'admin_msg')!=false) ? config_get('SYSTEM','MAIN_PAGE', 'admin_msg') : 'Some text';

        $res = $this->load->view("/admin/config/main_page", $data, true);
        make_response("output", $res, 1);
	}
	
	function main_page_settings_save()
    {
		if($this->input->post('action') == 'save'){

			$functionality_enabled_error=Functionality_enabled('main_page_settings');
            if($functionality_enabled_error!==true)
            {
                make_response("error", $functionality_enabled_error, 1);
                return;
            }

            $input = Array();
            $pre_check = '';

            $input['admin_msg'] = $this->input->post('admin_msg');
			$input['news_amount'] = $this->input->post('news_amount');
			$input['page_amount'] = $this->input->post('page_amount');
			$input['unreg_admin_msg'] = $this->input->post('unreg_admin_msg');
			$input['unreg_news_amount'] = $this->input->post('unreg_news_amount');
			$input['unreg_page_amount'] = $this->input->post('unreg_page_amount');

            //------------------------------------------------------------------------------------------------

            $result = $this->config_model->main_page_set($input);
            simple_admin_log('main_page_admin_message_modify',false,($result !== true),"saving_error");
            if($result !== true){
                make_response("error", $result, 1);
                return;
            }
        }

        $data = Array();

        $result = $this->config_model->main_page_get($data);
        //  Prepare output fields
        $data['page_amount'] = output($result['page_amount']);
		$data['news_amount'] = output($result['news_amount']);
        $data['admin_msg'] = output($result['admin_msg']);
        //      print_r($result);
        $temp_vars_set=array();
        $temp_vars_set['panel_script']=base_url()."js/admin/config/main_page_settings.js";
        $temp_vars_set['ValidationError'] = '<{admin_msg_validation_fail}>';
        $temp_vars_set['cancelText'] = '<{admin_msg_cancel}>';
        $temp_vars_set['page_amount'] = intval(config_get('SYSTEM','MAIN_PAGE','page_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE','page_amount')) : 3;
		$temp_vars_set['news_amount'] = intval(config_get('SYSTEM','MAIN_PAGE','news_amount'))>0 ? intval(config_get('SYSTEM','MAIN_PAGE','news_amount')) : 3;
		$temp_vars_set['admin_msg'] = config_get('SYSTEM','MAIN_PAGE','news_amount');
        $data['temp_vars_set'] = $temp_vars_set;

        $messages = array();
        $messages['ok_1'] = '<{admin_msg_ok_0001}>'; // Settings was changed successfully
        $data['messages'] = $messages;

        $mess_err = array();
        $mess_err['main_error'] = '<{admin_msg_er_0000}>';
        $mess_err['offline_msg'] = '<{admin_msg_er_0013}>'; // Maximum allowed 512 symbols
        $data['mess_err'] = $mess_err;

        $res=$this->load->view("/admin/config/design_manager", $data, true);
        make_response("output", $res, 1,true);
	}
	
}
?>
