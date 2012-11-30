<?php
/**
 * 
 * THIS FILE CONTAINS Directories CLASS
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
 * THIS CLASS ...
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Directories extends Admin_Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Directories()
    {
        $this->access_bit=PRODUCT;
        parent::Admin_Controller();
        $this->load->model("directories_model");
        $this->post = prepare_post();
        $this->data_fields = array("id","method","name","http_path","fs_path");
    }
    /**
     * Enter description here...
     *
     */
    function All_dirs_id()
    {
    $post=prepare_post();
    $post['period']=isset($post['period']) ? $post['period'] : 3;
    $res=array_transform($this->directories_model->get_dir_list(array('protect_period'=>$post['period'])),false,'id');
    echo create_temp_vars_set($res);
    //print_r($res);    
    }
    /**
     * Reprotect
     *
     */
    function Reprotect()
    {
        $this->load->model("protection_model");
        $post=prepare_post();
        $res=array('number'=>intval($post['number']),'id'=>intval($post['id']));
        $res['result']=$this->protection_model->reprotect(intval($post['id'])) ? 'true' : 'false';
        echo create_temp_vars_set($res);    
    }

    /**
     * Controller for list of protected directories.
     * This is dummy function so far.
     */
    function Dir_list()
    {
        $vars = array();
        $vars = $this->directories_model->load_panel_vars($vars);
        $additional = "";

        $this->load->model("protection_model");

        //***********Functionality limitations***********
        if(in_array($this->input->post("action"),array("save","delete","reprotect")))
        {
            $functionality_enabled_error=Functionality_enabled('admin_dir_protect_modify', $this->post['id']);
            if($functionality_enabled_error===true)
            {
                $functionality_enabled_error=Functionality_enabled('admin_dir_protect_limit');
                if(!($functionality_enabled_error!==true && $this->input->post("action")=="save" && isset($this->post['fs_path']) && strpos($this->post['fs_path'],DEMO_PROTECT_ROOT)===false))
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
                    $current_data = $this->directories_model->db_read($data['id']);
                    if (!$current_data)
                    {
                        $errors[] = 'directory_not_found';
                    }
                }
                if (empty($errors))
                {
                    $errors = $this->directories_model->validation_errors($data, $current_data);
                }
                if (empty($errors))
                {//$id contains last_insert_id now
                    if ($data['id'] && !$this->directories_model->db_write($data))
                    {
                        $errors[] = "update_failed";
                    }
                    elseif(!($id = $this->directories_model->db_write($data)))
                    {
                        $errors[] = "insert_failed";
                    }
                }
                if (empty($errors))
                {
                    if (!$data['id'])
                    {//we've just added new directory, need to protect it
                        if (!$this->protection_model->protect($id))
                        {//rolling back
                            $this->directories_model->DB_delete($id);
                            $errors[] = "protect_failed";
                        }
                    }
                    else
                    {//we've just edited directory, need to protect it again
                        if (!$this->protection_model->protect($data['id'], $current_data))
                        {//rolling back
                            $this->directories_model->db_write($current_data);
                            $errors[] = "protect_failed";
                        }
                    }
                }
                if (empty($errors))
                {
                    $additional = ($data['id']) ? "directory_has_been_updated" : "directory_has_been_added";
                    if (!$data['id']) //added new directory
                    {
                        unset($data['id']);
                        admin_log('directory_protected',$data);
                        //simple_admin_log('directory_protection_add');
                    }
                    else
                    {
                    simple_admin_log('directory_protection_modify',$data['id']);
                    }
                    
                }
                else
                {
                    fb($errors,'errors');
                    make_response("error", create_temp_vars_set($errors), true);
                    simple_admin_log('directory_protection_modify',$data['id'],true,$errors);
                    return;
                }
            break;
            case "delete":
                $id = intval($this->post["id"]);
                if(!isset($this->post["confirmed"]) || intval($this->post["confirmed"])<=0)
                {
                    $assotiated_products=$this->directories_model->get_assotiated_products($id);
                    if(is_array($assotiated_products) && count($assotiated_products)>0)
                    {
                        $vars['delete_dir']=$id;
                        $vars['assotiated_products']=$assotiated_products;
                        $vars['error_messages']['assotiated_products']['display']=true;
                        break;
                    }
                }
                $directory = $this->directories_model->db_read($id);
                if (!$directory)
                {
                    $errors[] = "directory_not_found";
                }
                if (empty($errors))
                {
                    if (!$this->protection_model->unprotect($id) && $this->protection_model->ProtectionIsOn($directory))
                    {
                        $errors[] = "unprotect_failed";
                    }
                }
                if (empty($errors))
                {
                    if (!$this->directories_model->db_delete($id))
                    {
                        $errors[] = "delete_failed";
                    }
                }
                if (empty($errors))
                {
                    $additional = "directory_has_been_deleted";
                    simple_admin_log('directory_protection_delete',$id);
                    
                }
                else
                {
                    make_response("error", create_temp_vars_set($errors), true);
                    simple_admin_log('directory_protection_delete',$id,true,$errors);
                    return;
                }
            break;
            case "reprotect":
                $id = intval($this->post["id"]);
                $directory = $this->directories_model->db_read($id);
                if (!$directory)
                {
                    $errors[] = "directory_not_found";
                }
                else
                {
                    if (!$this->protection_model->reprotect($id))
                    {
                        $errors[] = "reprotect_failed";
                    }
                }
                if (empty($errors))
                {
                    $additional = "directory_has_been_reprotected";
                    simple_admin_log('directory_protection_modify',$id);
                }
                else
                {
                    make_response("error", create_temp_vars_set($errors), true);
                    simple_admin_log('directory_protection_modify',$id,true,$errors);
                    return;
                }
            break;
        } // switch

        $this->load->helper('html_helper');

        $vars['pagers'] = pager_ex($this->post, $this->directories_model->number_of_directories(), 'id', 2, 'desc');
        $params = $vars['pagers']['params'];

        $vars['directories'] = $this->directories_model->get_directories(intval($params['limit']), intval($params['offset']), $params['column'], $params['order']);
        fb($vars);
        $output=$this->load->view("admin/directories/list", $vars, true);
        make_response("output", $output, true, $additional);
        //make_response("output", "<pre>".print_r($this->db->last_query(),true)."</pre>", true);
    }


    /**
     * Controller for adding new directory or editing existing directory
     * The following variables are passed to the template:
     *     protection_methods - array of available protection methods (extracted from the database)
     *     temp_var_set - array with misc javascript
     *
     */
    function Dir_item()
    {
        $additional = "";
        $vars = array();
        $vars = $this->directories_model->load_panel_vars($vars);

        $directory = array();
        $start_directory = config_get('SYSTEM','CONFIG','absolute_path');
        $start_directory=file_exists($start_directory) ? $start_directory : $this->input->server("DOCUMENT_ROOT");
        
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_dir_protect_limit')!==true)
        {
            $start_directory = $this->directories_model->standartize_directory_name($start_directory);
            $start_directory_temp=explode("/",$start_directory);
            $start_directory_temp[count($start_directory_temp)-2]=DEMO_PROTECT_ROOT;
            $start_directory=implode("/",$start_directory_temp); 
            if(isset($this->post['fs_path']) && strpos($this->post['fs_path'],DEMO_PROTECT_ROOT)===false)
            {
                $this->post['fs_path']=$start_directory;
            }
        }
        //*******End of functionality limitations********
        
        
        $vars['id'] = 0;
        $vars['directory'] = $directory;

        if (isset($this->post['id']) && intval($this->post['id'])>0)
        {//editing
            $id = intval($this->post["id"]);
            $directory = $this->directories_model->db_read($id);
            if ($directory)
            {
                $this->load->helper('ns_file_helper');
                if (!dir_exists($directory['fs_path']))
                {
                    $additional = "-directory_not_found_on_server";
                }
                $start_directory = $this->directories_model->get_nearest_directory($directory['fs_path'], $start_directory);
                $vars['directory'] = $directory;
                $vars['id'] = $id;
            }
            else
            {
                echo "www_editing/n";
                make_response("error", create_temp_vars_set(array('directory_not_found')), true);
                return;
            }
        }

        //reloading screen
        if (isset($this->post['action']) && $this->post['action']=="reload")
        {
            //passing current=db values - we need to use it as form_initials
            $vars['temp_vars_set']['form_has_been_reloaded'] = 1;
            foreach($vars['directory'] as $key=>$value)
            {
                $vars['temp_vars_set']['original_'.$key] = $value;
            }

            //last_auto_suggestion
            $vars['temp_vars_set']['last_auto_suggestion'] = $this->post['last_auto_suggestion'];

            //passing values from previous screen to use instead of current=db
            $vars['directory']['method'] = $this->post['method'];
            $vars['directory']['name'] = $this->post['name'];
            $vars['directory']['http_path'] = $this->post['http_path'];
            $start_directory = $this->directories_model->get_nearest_directory($this->post['fs_path'], $start_directory);
        }

        $vars['protection_methods'] = $this->directories_model->get_protection_methods();

        $vars['temp_vars_set']['document_root'] = $this->directories_model->standartize_directory_name($this->input->server("DOCUMENT_ROOT"));
        $vars['temp_vars_set']['http_root'] = "http://".$this->input->server("HTTP_HOST")."/"; //well, I'm not really sure about http:// - some users desire https:// as default protocol
                
        //$vars['temp_vars_set']['file_system_javascript'] = base64_encode($this->_generate_initial_javascript($start_directory));
        $vars['temp_vars_set']['start_directory'] = $this->directories_model->standartize_directory_name($start_directory);
        
        if (isset($this->post['load_start_directory']))
        {
            make_response("message", base64_encode($this->_generate_initial_javascript($start_directory)), true);
            return;
        }
        
        $output = $this->load->view("admin/directories/item", $vars, true);
        make_response("output", $output, true, $additional);
    }

    /**
     * Ajax-controller for communicating with treeview component
     * Requires 'path' variable passed via post - path to server-side directory to get info about
     * The following variables are being passed in the message:
     *     base64-encoded array of directories (both keys and values are encoded)
     *
     * Keys of array are directory paths, values are bitmasks
     *
     */
    function Treeview()
    {
        make_response("message", $this->_create_base64_temp_vars_set($this->directories_model->load_directory($this->input->post("path"), true)), true); //javascript doesn't like slashes
    }


    /**
     * Generates javascript with initial definitions for treeview component
     * Actually does the same as treeview(), but:
     *     returns pure javascript instead of encoded array
     *     parses all directories from root / directory to $directory
     *     marks $directory as selected by default
     *
     * @param string $directory path to default $directory
     * @return string javascript definitions of file_system array
     */
    function _generate_initial_javascript($directory)
    {
        $directory = $this->directories_model->standartize_directory_name($directory);
        
        $directories = array(); //list of directories to retrieve on first use
        $_f = explode("/",$directory); //breaking path into pieces
        array_pop($_f); //last piece not needed
        $_p = ""; //current path, concatenating
        
        $struct_arr=array();
        for ($i=0;$i<count($_f);$i++)
        {
            if(!empty($_p))
            {
                $struct_arr[]=$_p;
            }
            $_p .= $_f[$i]."/";
            $directories[] = $_p;
        }
        $data = array();
        
        
        foreach($directories as $_directory)
        {
            $data = array_merge($data, $this->directories_model->load_directory($_directory, $_directory == $directory));
        }
        
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_dir_protect_limit')!==true)
        {
            $temp_data=array();
            foreach($data as $key=>$value)
            {
                if(strpos($key,DEMO_PROTECT_ROOT)!==false || in_array($key,$directories))
                {
                    $temp_data[$key]=$data[$key];
                }    
            }
            $data=$temp_data;
        }
        //*******End of functionality limitations********
        
        //print_r($data);
        //print_r($temp_data);
        $result = array();

        $result = "";
        $i = 0;

        $data[$directory] |= DIRECTORIES_BITMASK_SELECTED; //selected directory
        foreach($data as $path=>$value)
        {
            if(in_array($path,$struct_arr))
            {
            $value |= DIRECTORIES_BITMASK_SUBDIRECTORIES;
            }
            
            $result .= "\tfile_system[$i] = {path: \"".str_replace("\"","\\\"",$path)."\", value: $value};\n"; //parent info will be build automatically
            $i++;
        }

        return $result;
    }


    /**
     * Base64 complete encoder for array. Encodes both keys and values of the array.
     * Encoded array has the same length as unencoded array.
     *
     * @param array $vars array to encode
     * @return array encoded array
     */
    function _create_base64_temp_vars_set($vars)
    {
        $result = array();
        foreach ($vars as $key=>$value)
        {
            $result[base64_encode($key)] = base64_encode($value);
        }
        return create_temp_vars_set($result);
    }
	/**
	 * Enter description here...
	 *
	 */
    function file_protection()
    {
        $data = array();

        //loading product groups
        $this->load->model("product_group_model");
        $data['groups'] = $this->product_group_model->list_all();

        //loading products
        $this->load->model("product_model");
        $data['products'] = $this->product_model->list_all();

        //loading code template
        $this->load->model("protection_model");
        $data['code'] = $this->protection_model->file_protection_code();
        
        $data['temp_vars_set']=array();
        $data['temp_vars_set']['panel_script']=base_url()."js/admin/directories/file_protection.js";
        $data['messages']=array();
        $data['messages']['copied_ok'] = "<{directories_file_protection_msg_copied}>";

        $output = $this->load->view("admin/directories/file_protection", $data, true);
        make_response("output", $output, true);
    }
}
?>
