<?php
/**
 * 
 * THIS FILE CONTAINS Coupons CLASS
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
class Coupons extends Admin_Controller 
{
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
	function Coupons()
    {
    	$this->access_bit=COUPON;
        parent::Admin_Controller();			
    	$this->load->model("coupons_model");
        $this->load->model("product_model");
    }
    
    
    /**
    * 
    * Shows Coupons Statistic
    * 
    * @author Drovorubov
    * 
    */    
    function statistic()
    {
        $search_params_err = false;
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
            
        }
        
        $current_page = intval($this->input->post('cpage')); 
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord')); 
        $sort_how = input_text($this->input->post('ord_type'));        
    	//Set search params
        $search_params = array();
        if( $this->input->post('is_search') == 'true' )
        {
                //Getting searching params for key
                if($this->input->post('search_key'))
                {
                    $search_params['search_key'] = input_text($this->input->post('search_key'));
                    $search_params['search_val'] = input_text($this->input->post('search_val')=='null' ? '' : $this->input->post('search_val'));
                }
                //Getting searching params for 2 dates
                $date_from = $this->input->post('date_from')=='null' ? '' : $this->input->post('date_from');
                $date_to = $this->input->post('date_to')=='null' ? '' : $this->input->post('date_to');
                if( !empty($date_from) )
                {
                    //Checking date from param
                    if( validate_date($date_from) )
                    {
                        $search_params['date_from'] = $date_from;
                    }
                    else
                    {
                        $search_params_err = true;
                    }
                    //Checking date to param
                    if( !$search_params_err && validate_date($date_to) )
                    {
                        $search_params['date_to'] = $date_to;
                    }
                    else
                    {
                        $search_params['date_to'] = '';
                    }                    
                }
                else if($this->input->post('date_period'))
                {
                    $date_period = $this->input->post('date_period');
                    $search_params['date_period'] = $date_period;
                }
        }
        //Getting data from DB
        $data = array();
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        if(!$search_params_err)
        {
            $data=$this->coupons_model->statistic($current_page, $per_page, $sort_by, $sort_how, $search_params);
            if ($data['total'] > $per_page)
            { 
                $data['pages'] = ceil($data['total'] / $per_page);
                if( $current_page > $data['pages'])
                {
                    $current_page = ($current_page > 2) ? $current_page - 1 : 1;
                }                
            }
            else 
            {
                $data['pages'] = 1;
            } 
        }

        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        
        //Set search params for key
        if(isset($search_params['search_key']))
        {
            $data['search_key'] = output($search_params['search_key']);
        }
        if(isset($search_params['search_val']))
        {
            $data['search_val'] = output($search_params['search_val']);
        }
        //Set search params for date from and to
        if( isset($date_from) && !empty($date_from) )
        {
            $data['date_from'] = $date_from;
        }
        if( isset($date_to) && !empty($date_to) )
        {
            $data['date_to'] = $date_to;
        }        
        else if( isset($date_period) )
        {
            $data['date_period'] = $date_period;
        }
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if( isset($data['items']) && count($data['items']) > 0 )
        {
            foreach( $data['items'] as $item )
            {
                //Prepare node
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $item['coupon_code'] = word_wrap($item['coupon_code'],20,2);
                $item['coupon_code'] = output($item['coupon_code']);
                $item['user_name'] = word_wrap($item['user_name'],20,2);
                $item['user_name'] = output($item['user_name']);
                $item['product_name'] = word_wrap($item['product_name'],20,0,' ');
                $item['product_name'] = output($item['product_name']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/coupons/statistic/node", $node, true);
            }
        }
        else
        {
            $data['rows'] .= $this->load->view("/admin/coupons/statistic/empty", array(), true);
        }
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'coupons_statistic',array(),$data['per_page']) . page_selectbox($data['pages'] ,'coupons_statistic',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'coupons_statistic',array(),$data['per_page']) . page_selectbox($data['pages'] ,'coupons_statistic',array('ppage'=>$data['per_page']), $data['current_page']);        
        //Get the page
        //print_r($data);
        
        $data['sort_by']=$sort_by;
        $data['sort_how']=$sort_how;
        
        $res=$this->load->view("/admin/coupons/statistic/page", $data, true);
        make_response("output", $res, 1);
    }
    
    

    /**
    * Delete Coupon Group
    *
    * @author Drovorubov
    */
    function remove()
    {
        $id = intval($this->input->post('id'));
        if($id < 1)        
        {
            $res = '<{admin_coupon_coupon_groups_error_invalid_id}>';
            make_response("error", $res, 1);
            simple_admin_log('coupon_group_delete',false,true,"invalid_id");
            return;
        }
        
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_coupon_modify', $id);
        if($functionality_enabled_error!==true)
        {   
            make_response("error",$functionality_enabled_error, true);
            return;
        }
        //*******End of functionality limitations********
        
        $result = $this->coupons_model->remove($id);
         if( $result ) 
        {
            $res ='<{admin_coupon_coupon_groups_delete_msg_success}>';
            make_response("output", $res, 1);
        }
        else
        {
            $res = '<{admin_coupon_coupon_groups_delete_error_notdeleted}>';
            make_response("error", $res, 1);
        }
        simple_admin_log('coupon_group_delete',$id,!$result,"deleting_error");
        return;
    }    
    
    
    /**
    * 
    * Shows Coupons Groups list
    * 
    * @author Drovorubov
    * 
    */    
    function coupons_list()
    {
    
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
            
        }

        $current_page = intval($this->input->post('cpage')); 
        if( $current_page <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord')); 
        $sort_how = input_text($this->input->post('ord_type'));         

        //Getting data from DB
        $data = array();
        $data=$this->coupons_model->coupons_list($current_page, $per_page, $sort_by, $sort_how);
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else 
        {
            $data['pages'] = 1;
        } 
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        //Prepare data for a node page
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $item['coupons_used'] = $this->coupons_model->get_coupons_used($item['id']);
                if( intval($item['discount_percent']) > 0 )
                {
                    $item['discount_value'] = $item['discount_percent'];
                    $item['discount_type'] = '<{admin_coupon_create_coupons_field_discount_type_percent}>';
                }
                else
                {
                    $item['discount_value'] = $item['discount_value'];
                    // kgg 
                    //$item['discount_type'] = '';
                    if(config_get('system','config','currency_code')!=false)
                        $item['discount_type'] =  config_get('system','config','currency_code');
                    else 
                        $item['discount_type'] =  "<{admin_coupon_create_coupons_field_discount_type_value}>";
                }                
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/coupons/groups_list/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/coupons/groups_list/empty", array(), true);
        }
        //Compose pager node1
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'coupons_group_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'coupons_group_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node2
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'coupons_group_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'coupons_group_list',array('ppage'=>$data['per_page']), $data['current_page']);        
        //Get the whole page
        
        $data['sort_by']=$sort_by;
        $data['sort_how']=$sort_how;
        
    	$res=$this->load->view("/admin/coupons/groups_list/page", $data, true);
        make_response("output", $res, 1);
    }
    
    
    /**
    * Create the new coupons group
    *
    * @author Drovorubov
    */
    function add()
    {
        $error = '';
    	$data=array();
       
        $action = prepare_text($this->input->post('action'));    
        if($action == 'add')
        {   
            //Getting post params
            $data['name'] = prepare_text($this->input->post('name'));
            $data['descr'] = prepare_text($this->input->post('descr'));    
            $data['coupons_count'] = intval($this->input->post('coupons_count'));
            $data['per_user_use'] = intval($this->input->post('per_user_use'));
            $data['coupon_use'] = intval($this->input->post('coupon_use'));
            $data['code_len'] = intval($this->input->post('code_len'));
            $data['discount_type'] = prepare_text($this->input->post('discount_type'));
            if( $data['discount_type'] == 'val')
            {
                $data['discount_val'] = $this->input->post('discount_val');
                $data['discount_val'] = str_replace(',','.',$data['discount_val']);
                $data['discount_val'] = floatval($data['discount_val']);                
            }
            else
            {
                $data['discount_val'] = intval($this->input->post('discount_val'));
            }
            $data['locked'] = intval($this->input->post('locked'));
            $data['donot_limit_dates'] = intval($this->input->post('donot_limit_dates'));
            $products_line = prepare_text($this->input->post('products'));
            $data['products'] = ($products_line != '') ? explode('!',$products_line) : array();
            // Fields validation            
            $error = $this->_check_coupons_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('coupon_group_add',false,true,"validation_error");
                return;
            }

            // Dates Validation
            if( $data['donot_limit_dates'] == 0 )
            {
                $start_date = prepare_text($this->input->post('start_date'));            
                $end_date = prepare_text($this->input->post('end_date'));
                if( !validate_date($start_date) || !validate_date($end_date) )
                {
                    $res = "<{admin_coupon_create_coupons_error_field_date_wrong}>";
                    make_response("error", $res, 1);
                    simple_admin_log('coupon_group_add',false,true,"validation_error");
                    return;                
                }
                if(!compare_dates($start_date,$end_date))
                {
                    $res = "<{admin_coupon_create_coupons_error_dates_compare}>";
                    make_response("error", $res, 1);
                    simple_admin_log('coupon_group_add',false,true,"validation_error");
                    return;                    
                }
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;                
            }
            //Compare Coupons count fields values
            if( !$this->_check_coupons_counts($data['coupon_use'],$data['per_user_use']) )
            {
                $res = "<{admin_coupon_create_coupons_error_usecount_compare}>";
                simple_admin_log('coupon_group_add',false,true,"validation_error");
                make_response("error", $res, 1);
                return;            
            }
            //Add data to DB
            $adding = $this->coupons_model->add($data);
            if( $adding ) 
            {
                $res = "<{admin_coupon_coupon_groups_msg_added_success}>";
                make_response("output", $res, 1);
            }
            else
            {
                $err = "<{admin_coupon_create_coupons_error_action_coupon_add}>";
                make_response("error", $err, 1);
            }
            simple_admin_log('coupon_group_add',false,!$adding,"saving_error");
            return;
        }
        //Get products list node
        $data['products_node'] = $this->_get_products_node();
        //Get tooltips node
        
        // Show adding form  
    	$res=$this->load->view("/admin/coupons/add", $data, true);
    	make_response("output", $res, 1);
        return;
    }    
   
  
  
    /**
    * Update coupons group
    *
    * @author Drovorubov
    */
    function edit()
    {
        //Get coupons group id 
        $id = intval($this->input->post('id'));
        if( $id < 1 )
        {
            $res = '<{admin_coupon_edit_coupons_error_invalid_id}>';
            make_response("error", $res, 1);
            simple_admin_log('coupon_group_modify',$id,true,"Error: ID is invalid");
            return;        
        }
        //Get post param action
        $action = prepare_text($this->input->post('action'));    
        if($action == 'save')
        {   
            $functionality_enabled_error=Functionality_enabled('admin_coupon_modify', $id);
            if($functionality_enabled_error!==true)
            {   
                make_response("error",$functionality_enabled_error, true);
                return;
            }            
            
            $data = array();
            //Getting post params
            $data['name'] = prepare_text($this->input->post('name'));
            $data['descr'] = prepare_text($this->input->post('descr'));    
            $data['per_user_use'] = intval($this->input->post('per_user_use'));
            $data['coupon_use'] = intval($this->input->post('coupon_use'));
            $data['discount_type'] = prepare_text($this->input->post('discount_type'));
            if( $data['discount_type'] == 'val')
            {
                $data['discount_val'] = $this->input->post('discount_val');
                $data['discount_val'] = str_replace(',','.',$data['discount_val']);
                $data['discount_val'] = floatval($data['discount_val']);
            }
            else
            {
                $data['discount_val'] = intval($this->input->post('discount_val'));
            }
            $data['locked'] = intval($this->input->post('locked'));
            $data['donot_limit_dates'] = intval($this->input->post('donot_limit_dates'));
            $products_line = prepare_text($this->input->post('products'));
            $data['products'] = ($products_line != '') ? explode('!',$products_line) : array();
            // Fields validation            
            $error = $this->_check_coupons_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('coupon_group_modify',$id,true,"validation_error");
                return;
            }
            // Dates Validation
            if( $data['donot_limit_dates'] == 0 )
            {
                $start_date = prepare_text($this->input->post('start_date'));            
                $end_date = prepare_text($this->input->post('end_date'));
                if( !validate_date($start_date) || !validate_date($end_date) )
                {
                    $res = "<{admin_coupon_create_coupons_error_field_date_wrong}>";
                    make_response("error", $res, 1);
                    simple_admin_log('coupon_group_modify',$id,true,"validation_error");
                    return;                
                }
                if(!compare_dates($start_date,$end_date))
                {
                    $res = "<{admin_coupon_create_coupons_error_dates_compare}>";
                    make_response("error", $res, 1);
                    simple_admin_log('coupon_group_modify',$id,true,"validation_error");
                    return;                    
                }
                $data['start_date'] = $start_date;
                $data['end_date'] = $end_date;
            }
            //Compare Coupons count fields values
            if( !$this->_check_coupons_counts($data['coupon_use'],$data['per_user_use']) )
            {
                $res = "<{admin_coupon_create_coupons_error_usecount_compare}>";
                make_response("error", $res, 1);
                simple_admin_log('coupon_group_modify',$id,true,"validation_error");
                return;            
            }            
            //Update data in DB
            $updating = $this->coupons_model->edit($id,$data);
            //Return the result of updating
            if( !$updating ) 
            {
                $res = '<{admin_coupon_edit_coupons_error_group_not_changed}>';
                make_response("error", $res, 1);
                simple_admin_log('coupon_group_modify',$id,true,"not_changed");
                return;            
            }
            $res = "<{admin_coupon_edit_coupons_changed_ok}>";
            make_response("output", $res, 1);
            simple_admin_log('coupon_group_modify',$id);
            return;
        }
        $data = array();        
        //Get coupons group data by id
        $data = $this->coupons_model->get($id);
        fb($data, "data");
        if(!$data['result'])
        {
            $res = "<{admin_coupon_edit_coupons_error_group_not_exist}>";
            make_response("error", $res, 1);            
            return;            
        }
        //Set discount values
        if($data['items']['discount_percent'] > 0 && $data['items']['discount_value'] == 0)
        {
            $data['items']['discount_type'] = 'prc';
            $data['items']['discount_val'] = $data['items']['discount_percent'];
        }
        else
        {
            $data['items']['discount_type'] = 'val';
            $data['items']['discount_val'] = $data['items']['discount_value'];        
        }
        //Set dates
        if( $data['items']['time_limit'] == 0 )
        {
            $data['items']['start_date'] = '';
            $data['items']['end_date'] = '';
        }
        $data['items']['name'] = output($data['items']['name']);
        $data['items']['descr'] = output($data['items']['descr']);
        //Get products list node
        $data['products_node'] = $this->_get_products_node($data['items']['products']);        
        // Load edit form  
    	$res=$this->load->view("/admin/coupons/edit", $data, true);
    	make_response("output", $res, 1);
        return;
    }      
  
  
    /**
    * 
    * Shows Coupons list
    * 
    * @author Drovorubov
    * 
    */    
    function show_coupons()
    {
        $group_id = intval($this->input->post('id'));
        if( $group_id < 1 )
        {
            $res = '<{admin_coupon_edit_coupons_error_invalid_id}>';
            make_response("error", $res, 1);            
            return;
        }
        $sort_by = input_text($this->input->post('ord')); 
        $sort_how = input_text($this->input->post('ord_type'));
        
        //Getting data from DB
        $data = array();
        $data=$this->coupons_model->get_coupons($group_id,  $sort_by, $sort_how);
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        //Prepare data for a node page
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $item['coupons_used'] = $this->coupons_model->get_coupons_used($group_id,$item['id']);                 
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/coupons/coupons_list/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/coupons/coupons_list/empty", array(), true);
        }
        //Get Coupons Group Name
        $data['coupons_group_name'] = $this->coupons_model->get_group_name($group_id);
        $data['coupons_group_name'] = word_wrap($data['coupons_group_name'],50,' ',1);
        $data['coupons_group_name'] = output($data['coupons_group_name']);
        $data['coupons_group_id'] = $group_id;
        //Get Back Link
        $data['back'] = input_text($this->input->post('back'));
        //Get the whole page
    	$res=$this->load->view("/admin/coupons/coupons_list/page", $data, true);
        make_response("output", $res, 1);
    }  
  
  
  
    
  
  
  
    /**
    * Delete Coupon 
    *
    * @author Drovorubov
    */
    function delete_coup()
    {
        $id = intval($this->input->post('coupon_id'));
        if($id < 1)        
        {
            $res = '<{admin_coupon_delete_coupons_error_invalid_id}>';
            make_response("error", $res, 1);
            simple_admin_log('coupon_delete',$id,true,"invalid_id");
            return;
        }
        
        //***********Functionality limitations***********
        $query = $this->db->get_where(db_prefix.'Coupons', array('id' => $id));
        $cps=$query->result_array();
        if(count($cps)>0)
        {
            $functionality_enabled_error=Functionality_enabled('admin_coupon_modify', $cps[0]['coupon_group_id']);
            if($functionality_enabled_error!==true)
            {   
                make_response("error",$functionality_enabled_error, true);
                return;
            }
        }
        //*******End of functionality limitations********
        
        
        $result = $this->coupons_model->delete_coup($id);
         if( $result ) 
        {
            $res ='<{admin_coupon_delete_coupons_deleted_ok}>';
            make_response("output", $res, 1);
        }
        else
        {
            $res = '<{admin_coupon_delete_coupons_error_not_deleted}>';
            make_response("error", $res, 1);
        }
        simple_admin_log('coupon_delete',$id,!$result,"not_deleted");
        return;
            
    }  
  
  
    /**
    * Check coupons using count values
    *
    * @author Drovorubov
    * @param integer $use_count
    * @param integer $mbr_use_count    
    * @return bool
    */
    function _check_coupons_counts($use_count,$mbr_use_count)
    {
        if( $mbr_use_count <= $use_count )
        {
            return true;
        }
        return false;
    }
  
  
    /**
    * Checks coupons entry fields
    *
    * @author Drovorubov
    * @param array $param
    * @return string
    */
    function _check_coupons_fields($param)
    {
            $rv = '';
            foreach($param as $key=>$val)
            {
                if( $key == 'name' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }
                    if(mb_strlen($val) > 254)
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_name_toolong}>";
                        break;
                    }
                    if( trim($val) === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_only_spaces}>";
                        break;
                    }                    
                }
                else if( $key == 'descr' ) 
                {
                    if(mb_strlen($val) > 65534)
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_comment_toolong}>";
                        break;
                    }
                }
                else if( $key == 'coupons_count' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }   
                    if( intval($val) < 1 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_coupons_count_small_value}>";
                        break;
                    }
                }
                else if( $key == 'per_user_use' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }                
                    if( intval($val) < 1 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_mbr_use_count_small_value}>";
                        break;
                    }
                }
                else if( $key == 'coupon_use' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }                
                    if( intval($val) < 1 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_use_count_small_value}>";
                        break;
                    }
                }
                else if( $key == 'code_len' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }                
                    if( intval($val) < 5 || intval($val) > 32 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_code_length_small_value}>";
                        break;
                    }
                }
                else if( $key == 'discount_type' ) 
                {
                    if( $val != 'prc' && $val != 'val' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_discount_type_wrong}>";
                        break;
                    }
                }
                else if( $key == 'discount_val' ) 
                {
                    if( $val === '' )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_empty}>";
                        break;
                    }
                    
                    if( $param['discount_type'] == 'prc' )
                    {
                        if( !is_int(intval($val)) )
                        {
                            $rv = "<{admin_coupon_create_coupons_error_field_discount_val_notint}>";
                            break;
                        }
                        if( intval($val) > 99 || intval($val) < 1 )
                        {
                            $rv = "<{admin_coupon_create_coupons_error_field_discount_prc_not_in_range}>";
                            break;
                        }                        
                    }
                    else if( $param['discount_type'] == 'val' )
                    {
                        if( !is_float(floatval($val)) )
                        {
                            $rv = "<{admin_coupon_create_coupons_error_field_discount_val_notfloat}>";
                            break;
                        }
                        if( floatval($val) < 0 || floatval($val) == 0 )
                        {
                            $rv = "<{admin_coupon_create_coupons_error_field_discount_val_notpositive}>";
                            break;
                        }                        
                    }                    
                }
                else if( $key == 'donot_limit_dates' ) 
                {
                    if( intval($val) != 1 && intval($val) != 0 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_checkbox_dates_limit_wrong}>";
                        break;
                    }
                }                
                else if( $key == 'locked' ) 
                {
                    if( intval($val) != 1 && intval($val) != 0 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_checkbox_locked_wrong}>";
                        break;
                    }
                }                
                else if( $key == 'products' ) 
                {
                    if( count($val) < 1 )
                    {
                        $rv = "<{admin_coupon_create_coupons_error_field_products_notenough_elements}>";
                        break;
                    }
                }                
            }        
            return $rv;
    }

    
    /**
    * Compares two dates
    *
    * @param string $date_from
    * @param string $date_to    
    * @return bool
    */
    function _compare_dates($date_from,$date_to)
    {
        $from = explode("-", $date_from);
        $to = explode("-", $date_to);
        $time_from = mktime(0, 0, 0, $from[1], $from[0], $from[2]);
        $time_to = mktime(0, 0, 0, $to[1], $to[0], $to[2]);
        if ($time_from > $time_to)         
        {
            return false;
        } 
        else 
        {
            return true;
        }    
    }
  
    /**
    * Validates date format
    *
    * @author Drovorubov
    * @param string $date
    * @return bool
    */
    function _check_date($date)
    {
        if( empty($date) )
        {
            return false;
        }
        //Dates validation
        if( !preg_match('/[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}$/', $date) )
        {
            return false;
        }
    
        return true;
    }
  
  
    /**
    * Prepares HTML Select as a node to insert into the page
    *
    * @author Drovorubov
    * @param array $selected
    * @return string
    */
    function _get_products_node($selected = array())
    {
        $rv = "";
         $product=array();
    $product_list = $this->product_model->list_all();
    if(count($product_list)>0){
        foreach($product_list as $item)
        {
            $temp=$this->product_model->get($item['id']);
            if ($temp[0]['day'] ==0.00 &&        
                    $temp[0]['month'] ==0.00 &&
                    $temp[0]['month3'] ==0.00 &&
                    $temp[0]['month6'] ==0.00 &&
                    $temp[0]['year'] ==0.00 &&
                    $temp[0]['unlimit'] ==0.00 &&
                    $temp[0]['discount'] ==0.00 &&       
                    $temp[0]['trial_price'] ==0.00)
            {}else $product[]=$temp[0]; 
        }
    }
    fb($product,"product");
    $product_list = $product;
 //       $product_list = $this->product_model->list_all(false,true);
 //       fb($product_list,__function__." product_list ");
        if( count($product_list) > 0 )
        {
            foreach($product_list as $item)
            {
                $item['name'] = word_wrap($item['name'],50,2);
                $item['name'] = output($item['name']);
                $rv .= "<option value=\"".$item['id']."\"";
                if( in_array($item['id'],$selected) )
                {
                    $rv .= " selected ";
                }
                $rv .= " >".$item['name']."</option>";        // вывод всех продуктов. платные продукты выделенные
/*                
                if( in_array($item['id'],$selected) )
                {
                    $rv .= "<option value=\"".$item['id']."\"";                     
                    $rv .= " >".$item['name']."</option>";     // вывод только платных продуктов
                }*/
            }
        }

        return $rv;
    }  
  
  
  
}
?>
