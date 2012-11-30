<?php

require_once("admin_controller.php");

class Host_plans extends Admin_Controller
{
    /**
     * class constructor
     *
     * @return nothing
     */
    function Host_plans()
    {
        $this->access_bit=PRODUCT;
        parent::Admin_Controller();
        $this->load->model("host_plans_model");
        $this->load->model("host_manager_model");
        $this->post = prepare_post();
        $this->data_fields = array("id","name","type_domen","packages");
    }
    
    function Host_plans_id()
    {
//    $post=prepare_post();
    $res=array_transform($this->host_plans_model->Get_host_plan_list(),false,'id');
    echo create_temp_vars_set($res);
    //print_r($res);    
    }
    
    /**
     * Controller for list of host plans.
     *
     * @return nothing - it calls make_response instead
     */
    function Host_plans_list()
    {
        $vars = array();
        $vars = $this->host_plans_model->load_panel_vars($vars);
        $additional = "";

//        $this->load->model("protection_model");

        //***********Functionality limitations***********
        if(in_array($this->input->post("action"),array("save","delete")))
        {
            $functionality_enabled_error=Functionality_enabled('admin_products_hosted_modify', $this->post['id']);
            if($functionality_enabled_error===true)
            {
                $functionality_enabled_error=Functionality_enabled('admin_products_hosted_limit');
                if(!($functionality_enabled_error!==true && $this->input->post("action")=="save" ))
                {
                    $functionality_enabled_error=true;
                }
            }
            if($functionality_enabled_error!==true)
            {   
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), true);
                return;
            }
        }
        //*******End of functionality limitations********
        
        switch($this->input->post("action"))
        {
            case "save":
                $data = array();
                foreach($this->data_fields as $key)
                {
                    $data[$key] = $this->post[$key];
                }
                $data['id'] = intval($data['id']);
                $errors = $current_data = array();
                if ($data['id'])
                {
                    $current_data = $this->host_plans_model->db_read($data['id']);
                    if (!$current_data)
                    {
                        $errors[] = 'host_plan_not_found';
                    }
                }
                if (empty($errors))
                {
                    $errors = $this->host_plans_model->validation_errors($data, $current_data);
                }
                if (empty($errors))
                {//$id contains last_insert_id now
                    if ($data['id'] && !$this->host_plans_model->db_write($data))
                    {
                        $errors[] = "update_failed";
                    }
                    elseif(!($id = $this->host_plans_model->db_write($data)))
                    {
                        $errors[] = "insert_failed";
                    }
                }
                if (empty($errors))
                {
                    $additional = ($data['id']) ? "host_plan_has_been_updated" : "host_plan_has_been_added";
                    if (!$data['id']) //added new host_plan
                    {
                        unset($data['id']);
                        admin_log('host_plan_add',$data);
                        //simple_admin_log('host_plan_add');
                    }
                    else
                    {
                    simple_admin_log('host_plan_modify',$data['id']);
                    }
                    
                }
                else
                {
                    make_response("error", create_temp_vars_set($errors), true);
                    simple_admin_log('host_plan_modify',$data['id'],true,$errors);
                    return;
                }
            break;
            case "delete":
                $id = intval($this->post["id"]);
                if(!isset($this->post["confirmed"]) || intval($this->post["confirmed"])<=0)
                {
                    $assotiated_products=$this->host_plans_model->get_assotiated_products($id);
                    if(is_array($assotiated_products) && count($assotiated_products)>0)
                    {
                        $vars['delete_host_plan']=$id;
                        $vars['assotiated_products']=$assotiated_products;
                        $vars['error_messages']['assotiated_products']['display']=true;
                        break;
                    }
                }
                $host_plan = $this->host_plans_model->db_read($id);
                if (!$host_plan)
                {
                    $errors[] = "host_plan_not_found";
                }
                if (empty($errors))
                {
                    if (!$this->host_plans_model->db_delete($id))
                    {
                        $errors[] = "delete_failed";
                    }
                }
                if (empty($errors))
                {
                    $additional = "host_plan_has_been_deleted";
                    simple_admin_log('host_plan_delete',$id);
                    
                }
                else
                {
                    make_response("error", create_temp_vars_set($errors), true);
                    simple_admin_log('host_plan_delete',$id,true,$errors);
                    return;
                }
            break;
			case "reprotect":
                $current_data = array();
				$current_data = $this->host_manager_model->get_packages();
                fb($current_data,'current_data');
                 if ($this->input->post("id"))
                {
                    $package_data = $this->host_plans_model->db_read($this->input->post("id"));
                    if (!$package_data)
                    {
                        $errors[] = 'host_plan_not_found';
                        make_response("error", create_temp_vars_set($errors), true);
                        return;
                    }                    
                }             
                //if (!$current_data or !in_array($this->input->post("id"), $current_data))
                if (!$current_data or !in_array($package_data['packages'], $current_data))
				{
					if (!$current_data) $errors[] = "<{admin_host_plans_list_check_settings}>";                    
                    else $errors[] = "<{admin_host_plans_list_check_package}>";                    
					make_response("error", create_temp_vars_set($errors), true);
					return;
				}
                else 
                {               
                    $additional = "host_plan_has_been_checed_ok";                                       
                    $vars['ok_messages']['host_plan_has_been_checed_ok']['display']=true;
                }
            break;
         } // switch

        $this->load->helper('html_helper'); 
        $vars['pagers'] = pager_ex($this->post, $this->host_plans_model->number_of_host_plans(), 'id', 2, 'desc');
        $params = $vars['pagers']['params'];        
        $vars['host_plans'] = $this->host_plans_model->get_host_plans(intval($params['limit']), intval($params['offset']), $params['column'], $params['order']); 
        $output=$this->load->view("admin/host_plans/list", $vars, true);
        make_response("output", $output, true, $additional);
        //make_response("output", "<pre>".print_r($this->db->last_query(),true)."</pre>", true);
    }

    /**
     * Controller for adding new host_plan or editing existing host_plan
     * The following variables are passed to the template:
     *     packages - array of available protection methods (extracted from the database)
     *     temp_var_set - array with misc javascript
     *
     * @return void - it calls make_response instead
     */
    function host_plan_item()
    {
        $additional = "";
        $vars = array();
        $vars = $this->host_plans_model->load_panel_vars($vars);
        $host_plan = array();
        
        //***********Functionality limitations***********
//        if(Functionality_enabled('admin_host_plans_limit')!==true)
//        {
//        	;
//        }
        //*******End of functionality limitations********
        
        
        $vars['id'] = 0;
        $vars['host_plan'] = $host_plan;

        if (isset($this->post['id']) && intval($this->post['id'])>0)
        {//editing
            $id = intval($this->post["id"]);
            $host_plan = $this->host_plans_model->db_read($id);
            if ($host_plan)
            {
                $vars['host_plan'] = $host_plan;
                $vars['id'] = $id;
            }
            else
            {
                echo "www_editing/n";
                make_response("error", create_temp_vars_set(array('host_plan_not_found')), true);
                return;
            }
        }

        //reloading screen
        if (isset($this->post['action']) && $this->post['action']=="reload")
        {
            //passing current=db values - we need to use it as form_initials
            $vars['temp_vars_set']['form_has_been_reloaded'] = 1;
            foreach($vars['host_plan'] as $key=>$value)
            {
                $vars['temp_vars_set']['original_'.$key] = $value;
            }

            //last_auto_suggestion
            $vars['temp_vars_set']['last_auto_suggestion'] = $this->post['last_auto_suggestion'];

            //passing values from previous screen to use instead of current=db
            $vars['host_plan']['packages'] = $this->post['packages'];
            $vars['host_plan']['name'] = $this->post['name'];
            $vars['host_plan']['type_domen'] = $this->post['type_domen'];
        }

        $package = $this->host_manager_model->get_packages();
        if ($package !== false && is_array($package))
        {
        	$vars['packages'] = $package;
        }
        else 
        {
//             fb($vars,"vars")   ;
             $errors[] = "<{admin_host_plans_list_check_settings}>";
			 make_response("error", create_temp_vars_set($errors), true);
                return;
        }
        

/*        $vars['temp_vars_set']['document_root'] = $this->host_plans_model->standartize_host_plan_name($this->input->server("DOCUMENT_ROOT"));
        $vars['temp_vars_set']['http_root'] = "http://".$this->input->server("HTTP_HOST")."/"; //well, I'm not really sure about http:// - some users desire https:// as default protocol
                
        $vars['temp_vars_set']['file_system_javascript'] = base64_encode($this->_generate_initial_javascript($start_host_plan));
        $vars['temp_vars_set']['start_host_plan'] = $this->host_plans_model->standartize_host_plan_name($start_host_plan);
*/
        $output = $this->load->view("admin/host_plans/item", $vars, true);
//fb($output,'host item output');
        
        make_response("output", $output, true, $additional);
    }
}
?>
