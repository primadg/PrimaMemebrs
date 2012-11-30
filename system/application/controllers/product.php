<?php
/**
 * 
 * THIS FILE CONTAINS Product CLASS
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
 * This class contains methods for management of product.
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Product extends Admin_Controller 
{
   
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Product()
    {
    	
        $this->access_bit=PRODUCT;
        parent::Admin_Controller();	
        $this->load->model("product_model", "product");
        $this->load->model("product_group_model", "product_group");
        $this->load->model("directories_model", "directories");
        //** kgg
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===TRUE)
        {
            $this->load->model("host_plans_model","host_plans");
        }
    }
    /**
     * Enter description here...
     *
     * @param string $str
     * @param integer $min
     * @param integer $max
     * @return boolean
     */
    function _check_len($str, $min, $max)
    {
        $len=mb_strlen($str);            
        return !($len<$min || $len >$max); 
    }
    /**
     * checks product parametres
     *
     * @return array
     */
    function _check_product_params()
    {
        $_errors=0;        
        $pinfo=array();
        
        do
        {
            $pinfo['_free']  =   (int)prepare_text(   $this->input->post("free") );
            
            $pinfo['_name']  =   prepare_text(   $this->input->post("name") );
            
            if( !   $this->_check_len($pinfo['_name'],   1, 255))
            {
                $_errors=1;
                break;
            }
            
            $pinfo['_descr']  =   prepare_text(  $this->input->post("descr") );
            
            if( !   $this->_check_len($pinfo['_descr'],  1, 65535))
            {
                $_errors=4;
                break;
            }
                                     
            $pinfo['_group']  =   $this->input->post("group");
            
            if( !   $pinfo['_group'] )
            {
                $_errors=5;
                break;
            }                    
 
            if($pinfo['_free']==2)
            {
                $pinfo['_prices']               =array_fill(0,6,0);
                
                $pinfo['_reccuring']            =1;
                
                $pinfo['_discount_type']        =1;
                
                $pinfo['_discount_value']       =0;
                
                $pinfo['_trial_price']          =0;
                
                $pinfo['_trial_period_type']    ='';
                
                $pinfo['_trial_period_value']   =0;
                
                $pinfo['_cumulative']           =0;
            }
            else
            {
                $_sel  =   $this->input->post("prices");
                
                $pinfo['_prices']=array_fill(0,6,0);
                $one_price=0;
                
                if(is_array($_sel))
                {                
                    for($i=0; $i<6;$i++)
                    {                        
                        if($_sel[$i] <= PRODUCT_MAX_PRICE)
                        {   
                            $one_price+=($pinfo['_prices'][$i]   = (float) (isset($_sel[$i])?$_sel[$i]:0));
                        }
                        else
                        {                            
                            $_errors=12;
                            break;  
                        }
                    }         
                }
                else
                {    
                    if($_sel[$i] <= PRODUCT_MAX_PRICE)
                    {
                        $one_price+=($pinfo['_prices'][0]=(float)$_sel);
                    }
                    else
                    {
                        $_errors=12;
                        break;  
                    }
                }
                
                if(!$one_price)
                {
                    $_errors=12;
                    break;
                }
                
                $pinfo['_reccuring']            =(int)$this->input->post("reccuring");
                
                $pinfo['_discount_type']        =(int)$this->input->post("discount_type");
                
                $pinfo['_discount_value']       =(float)$this->input->post("discount_value");
                
                $pinfo['_trial_price']          =(float)$this->input->post("trial_price");
                
                $pinfo['_trial_period_type']    =$this->input->post("trial_period_type");
                
                $pinfo['_trial_period_type']    =_check_param_array($pinfo['_trial_period_type'], $this->product->product_trial_periods);
                
                $pinfo['_trial_period_value']   =(int)$this->input->post("trial_period_value");
                
                $pinfo['_cumulative']           =(int)$this->input->post("cumulative");
            
            }
            
            //** kgg 
            //***********Functionality limitations***********
            if(count($this->product->product_types)<3)
            {
                foreach($this->product->product_types as $t)
                {
                    if($t!=0)
                    {
                        $pinfo['_product_type']  = intval($t);
                    }
                }
            }
            else
            {
                $pinfo['_product_type']  =(int)$this->input->post("product_type");
            }
            //*******End of functionality limitations********
	        //** kgg


            $pinfo['_dirs']                 =$this->input->post("dirs");            
            fb($pinfo['_dirs'],"pinfo['_dirs']");
            $_flag=false;
            
            if(is_array($pinfo['_dirs']))
            {       
                
                foreach($pinfo['_dirs'] as $k=>$v)
                {
                    $pinfo['_dirs'][$k]= (int) $v;
                    
                    if(! $_flag )                        
                        $_flag=$v;
                }
            }
            else
            {           
                $_flag=(int)$pinfo['_dirs'];
                $pinfo['_dirs']=array($pinfo['_dirs']);
            }
            
            if($_flag===false)
            {
                /*$_errors=10;
                break;*/
                $pinfo['_dirs']=array();
            }
            
        }while(0);
        $pinfo['errors']=$_errors;        
        return $pinfo;
    }
    /**
     * Add new product
     *
     */ 
    function add()
    {
        $this->load->model("member_group_model");
        $data=array();
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_products_modify_paid')!==true)
        {   
            $data['only_free_product']=true;
            $_POST['free']='2';
        }
        //*******End of functionality limitations********
        $_errors=0;
        $product_id=0;
        $data['protect_dirs']=$this->directories->get_dir_list();
        $data['product_types']=$this->product->product_types;
        //** kgg
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===TRUE)
        {        	
            $data['host_plans']=$this->host_plans->get_host_plan_list();
            $data['product_host_plans']='';
            $data['product_type']= PRODUCT_HOSTED;
        }
        if(Functionality_enabled('admin_product_protected')===TRUE)
        {        	
            $data['product_type']= PRODUCT_PROTECT;
        }
        //*******End of functionality limitations********
        //** kgg
        
        $data['product_groups']=$this->product_group->list_all();        
                      
        if($this->input->post("action", "") == "add")
        {                
            //***********Functionality limitations***********
            if(($func_error1=Functionality_enabled('admin_product_groups_modify', intval($this->input->post("group",""))))!==true)
            {
                $data['functionality_enabled_error']=$func_error1;
                $_errors=16;              
            }
            //*******End of functionality limitations********
            
            
            /*
                geting and validating product fields
            */  
            if(!$_errors)
            {
                $pinfo=$this->_check_product_params();
                $_errors=$pinfo['errors'];
            }
            if(!$_errors)
            {
                /*
                    try add product, into database
                */
                $product_id=$this->product->product_add
                                            (
                                                $pinfo['_name'], $pinfo['_descr'], $pinfo['_group'], $pinfo['_prices'], $pinfo['_reccuring'], 
                                                $pinfo['_discount_type'], $pinfo['_discount_value'], $pinfo['_trial_price'], $pinfo['_trial_period_type'], 
                                                $pinfo['_trial_period_value'], $pinfo['_cumulative'], $pinfo['_product_type'], $pinfo['_dirs']
                                            );
                if(!$product_id)
                {
                    $_errors=11;
                
                }
                else
                {
                    $_errors=-1;//ERROR_SUCCESS technology � by MICROSOFT FUNDATION, 1998
                    
                    $visible=array_flip($this->input->post('member_groups_visible'));
                    $available=array_flip($this->input->post('member_groups_available'));
                    foreach($visible as $key=>$value)
                    {
                        $visible[$key]=isset($available[$key]) ? 1 : 0;
                    }
                    $this->member_group_model->set_product_groups($product_id,$visible);
                }
            }
        }
        
        $data['member_groups']=$this->member_group_model->group_list();
        $data['selected_member_groups']=array(1=>1);
        
        if($_errors > 0)
        {
            /*
                some errors in validation/DB
            */
            $ret="error";
            $output=$_errors;
            simple_admin_log('product_add',$product_id,true,"not_added");
        }
        else
        {
            $ret="output";
            if($_errors==-1)
            {
                /*
                    product was successfully added
                    return back its ID
                */
                $output=$product_id;
                simple_admin_log('product_add',$product_id);
            }
            else
            {
                $data['upload_params']=array();
                $data['upload_params']['max_size']	   = config_get("product_posters", "max_size");
                $data['upload_params']['max_width']    = config_get("product_posters", "max_width");
                $data['upload_params']['max_height']   = config_get("product_posters", "max_height");
                
                
                /*nothing submited, show add form*/
                $output=$this->load->view("admin/product/add", $data, TRUE);
            }
        }        
        make_response($ret, $output, 1);
    }
 	/**
 	 * Display product list
 	 *
 	 */
    function product_list() 
    {
        $data=array();
          
        $_errors=0;        
        
        /*
            INPUT
        */
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_products_modify_paid')!==true)
        {
            $this->product->delete_paid_products();
        }
        //*******End of functionality limitations********   
                
        $data['group_id']   =$group_id   =(int)input_post("group", 0);
        $data['member_group_id']   =$member_group_id   =(int)input_post("member_group", 0);
        $data['member_groups_available'] = $member_groups_available =(int)input_post("member_groups_available", 0);
        
        $page               =(int)input_post("page", 1);        
        if(!$page || $page<0)
            $page=1;
            
        $per_page           =(int)input_post("per_page", config_get("PAGER", "default_perpage"));
        if(!$per_page || $per_page <0)
            $per_page=config_get("PAGER", "default_perpage");
         
                
        $sort_by=input_post("sort_by", false);
        $data['sort_by']=$sort_by=_check_param_array($sort_by, $this->product->product_list_sort_types);
         
        $order_by=input_post("order_by", false);
        $order_by=($order_by=='true'?"DESC":"ASC");
        $order_by=_check_param_array($order_by , array("ASC", "DESC"));
                
        /*
            GETING ITEMS LIST 
        */
        $products=$this->product->product_list($page, $per_page, $sort_by, $order_by, $group_id,$member_group_id,$member_groups_available); 
        
        $page=$products['page'];
        $per_page=$products['per_page'];
        
        $data['pager']=page_selectbox(ceil($products['total']/$products['per_page']), "product_list_prepare_pager" ,"",$page);        
        
        $data['per_pager']=perpage_selectbox(0, "product_list_prepare_pager", "",$per_page);        
        
        $data['products']=$this->load->view("admin/product/nodes", array("products"=>$products['items']), TRUE);        

        $data['groups']=$this->product_group->list_all();
        
        $this->load->model("member_group_model");
        $data['member_groups']=$this->member_group_model->group_list();
        
        $data['sort_by']=$sort_by;
        $data['order_by']=strtolower($order_by);
        $output=$this->load->view("admin/product/list", $data, TRUE);        
        
        make_response("output", $output, 1);
      
    }
    
	function special_offers_product()
	{
        $id =(int)input_post("id", 0);
        $result="";
        $functionality_enabled_error=Functionality_enabled('admin_product_do_special', $id);
        if($functionality_enabled_error!==true)
        {
            $result=3;
            $res="error";
        }
        else
        {
            $res=$this->product->block_unblock_special_offers_product($id) ?   "output"    :   "error";
            $result=($res=="error") ? 1 : "";
            simple_admin_log('product_special',$id,($res=="error"),"unspecial");
        }
        make_response($res, $result, 1);
    }
	
	
    /**
     * Block product
     *
     */
    function block_product()
    {
        $id =(int)input_post("id", 0);
        $result="";
        $functionality_enabled_error=Functionality_enabled('admin_products_modify', $id);
        if($functionality_enabled_error!==true)
        {
            $result=3;
            $res="error";
        }
        else
        {
            $res=$this->product->block_unblock_product($id) ?   "output"    :   "error";
            $result=($res=="error") ? 1 : "";fb($res);
            simple_admin_log('product_block',$id,($res=="error"),"not_blocked");
        }
        make_response($res, $result, 1);
    }

    /**
     * Delete product
     *
     */
    function delete_product()
    {
        $id =(int)input_post("id", 0);
        $result="";
        $functionality_enabled_error=Functionality_enabled('admin_products_modify', $id);
        if($functionality_enabled_error!==true)
        {
            $result=3;
            $res="error";
        }
        else
        {
            $res=$this->product->delete_product($id) ? "output" : "error";
            $result=($res=="error") ? 2 : "";
            simple_admin_log('product_delete',$id,($res=="error"),"not_deleted");
        }
        make_response($res, $result, 1);
    }
    
   /**
    * Enter description here...
    *
    */
    function iframe()
    {
        $res=array("status"=>"", "error"=>0);
               
        if(isset($_FILES['poster']))
        {
            $id=(int)input_post('pid', 0);
            
            $upload_info=$this->product->upload_image('poster');
            
            if($upload_info['result'])
            {
                if  (
                        $this->product->resize_image
                        (
                            $upload_info['data']['full_path'],
                            config_get("product_posters", "path_previews"),
                            $upload_info['data']['file_name'],
                            config_get("product_posters", "preview_width"),
                            config_get("product_posters", "preview_height")
                        )
                    )
                {
                    if($im=$this->product->delete_poster($id))
                    {
                        $this->product->delete_poster_file($im);
                    }
                    
                    $this->product->set_product_image($id, $upload_info['data']['file_name']);
                    $res['status']="success";
                }
                else
                {
                    $res['status']="error";
                    $res['error']=replace_lang("<{product_add_image_upload_error_resize}>");
                }
            }
            else 
            {   
                $res['status']='error';
                $res['error']=preg_replace("/<[^<>]+>/", "", replace_lang($upload_info['errors']));
            }
            simple_admin_log('product_image_add',$id,($res['status']=="error"),"not_added");
            echo array_to_json($res);
        }
    }
    
    
    
    /**
     * Edit product
     *
     * @param string $id
     */
    function edit($id='')
    {
       fb($_POST,__FUNCTION__." POST");
       $this->load->model("member_group_model");
        $id=(int)$id;
        
        $data=array();
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_products_modify_paid')!==true)
        {   
            $data['only_free_product']=true;
            $_POST['free']='2';
        }
        //*******End of functionality limitations********
        $_errors=0;
        $product_id=0;
        
        $data['protect_dirs']=$this->directories->get_dir_list();
        $data['product_types']=$this->product->product_types;
        $data['product_groups']=$this->product_group->list_all();
        if($d=$this->product->get($id))
        {            
            if($this->input->post("action", "") == "save")
            {                
                /*
                geting and validating product fields
            */                        
                $pinfo=$this->_check_product_params();
                $_errors=$pinfo['errors'];
                $pinfo['_product_type']=$d[0]['product_type']; 
                //***********Functionality limitations***********
                
            if($func_error2=Functionality_enabled('admin_products_modify', $id)!==true)
            {
                $data['functionality_enabled_error']=$func_error2;
                $_errors=15;
            }
            if(($func_error1=Functionality_enabled('admin_product_groups_modify', intval($this->input->post("group",""))))!==true)
            {
                $data['functionality_enabled_error']=$func_error1;
                $_errors=17;                
            }           

     
                //*******End of functionality limitations********
                
                if(!$_errors)
                {
                    /*
                    try add product, into database
                */
                    if  
                    (
                    ! $this->product->set
                    (
                    $id,
                    $pinfo['_name'], $pinfo['_descr'], $pinfo['_group'], $pinfo['_prices'], $pinfo['_reccuring'], 
                    $pinfo['_discount_type'], $pinfo['_discount_value'], $pinfo['_trial_price'], $pinfo['_trial_period_type'], 
                    $pinfo['_trial_period_value'], $pinfo['_cumulative'],  $pinfo['_product_type'], $pinfo['_dirs']
                    )
                    )
                    {
                        $_errors=11;
                    }
                    else
                    {
                        $_errors=-1;//ERROR_SUCCESS technology by MICROSOFT FUNDATION, 1998
                        
                        $visible=$this->input->post('member_groups_visible');
                        $available=$this->input->post('member_groups_available');
                        $visible=is_array($visible) ? array_flip($this->input->post('member_groups_visible')) : array();
                        $available=is_array($available) ? array_flip($this->input->post('member_groups_available')) : array();
                        foreach($visible as $key=>$value)
                        {
                            $visible[$key]=isset($available[$key]) ? 1 : 0;
                        }
                        $this->member_group_model->set_product_groups($id,$visible);
                        
                    }
                }
            }
            else
            {
                $data=array_merge($d[0], $data);
                $data['product_id']=$id;
                switch ($data['product_type']) 
                {
                case PRODUCT_PROTECT:
                    $data['product_dirs']=$this->product->get_product_dirs($id);
                    break;
                case PRODUCT_HOSTED:
                    $data['host_plans']=$this->host_plans->get_host_plan_list();
                    $data['product_host_plans']=$this->host_plans->get_product_host_plans($id);
                    fb($data['product_host_plans'],"product_host_plans");
                    break;
                }
                fb($data,__FUNCTION__.' data');
            }
        }
        else
        {
            $data['not_exists']=true;
        }
        $data['member_groups']=$this->member_group_model->group_list();
        $data['selected_member_groups']=$this->member_group_model->get_product_groups($id); 

        //print_r($data['member_groups']);
        //print_r($data['selected_member_groups']);
        
        if($_errors > 0)
        {
            /*
                some errors in validation/DB
            */ 
            $ret="error";
            $output=$_errors;            
        }
        else
        {
            $ret="output";
            if($_errors  ==  -1)
            {
                /*  product was successfully changed
                    return back its ID
                */
                $output=$id;
            }
            else
            {
                /*  nothing submited, show add form  */
                $data['upload_params']=array();
                $data['upload_params']['max_size']	   = config_get("product_posters", "max_size");
                $data['upload_params']['max_width']    = config_get("product_posters", "max_width");
                $data['upload_params']['max_height']   = config_get("product_posters", "max_height");
                
                $output=$this->load->view("admin/product/edit", $data, TRUE);
            }
        }
        simple_admin_log('product_modify',$id,($ret=="error"),"not_updated");        
        make_response($ret, $output, 1);
    }

    /**
     * Enter description here...
     *
     * @param string $id
     */
    function remove_poster($id='')
    {
        $id=(int)$id;
        
        if($im=$this->product->delete_poster($id))
        {
            $this->product->delete_poster_file($im);
            $error=false;
            make_response("output", '1' ,1);
        }
        else
        {
            $error=true;
            make_response("error",  '13',1);
        }
        simple_admin_log('product_image_delete',$id,$error,"not_deleted");
    }
    
    /**
     * Enter description here...
     *
     */
    function dir_list()
    {
        $output=$this->load->view("admin/product/edit", $data, TRUE);
            
        make_response($ret, $output, 1);
    }
}



?>
