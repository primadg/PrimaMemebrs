<?php
/**
 * 
 * THIS FILE CONTAINS Product_group CLASS
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
 * This class contains methods for management of product's groups.
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Product_group extends Admin_Controller 
{
    
    /**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Product_group()
    {
    	$this->access_bit=PRODUCT;
        parent::Admin_Controller();	        
        $this->load->model("product_group_model", "product_group");
        $this->load->model("product_model", "product");
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
     * Display list of groups
     *
     */
    function group_list()
    {
        $data=array();        
        
        $page=(int)input_post("page", 1);
        if(!$page || $page < 0)
            $page=1;
            
        $per_page=(int)input_post("per_page", config_get("PAGER", "default_perpage"));
        if(!$per_page || $per_page < 0)
            $per_page=config_get("PAGER", "default_perpage");
            
        $sort_by=input_post("sort_by", false);
        $data['sort_by']=$sort_by=_check_param_array($sort_by, $this->product_group->list_sort_types);
         
        $order_by=input_post("order_by", false);
        $order_by=($order_by=='true'?"ASC":"DESC");
        $order_by=_check_param_array($order_by , array("ASC", "DESC"));
        
        
        $groups=$this->product_group->list_group($page, $per_page, $sort_by, $order_by);
        
        $page=$groups['page'];
        
        $data['pager']=page_selectbox(ceil($groups['total']/$groups['per_page']), "group_list_prepare_pager" ,"",$page);
        
        $data['per_pager']=perpage_selectbox(0, "group_list_prepare_pager", "",$per_page);        
        
        if($groups['result'])
        {
            $data['items']=$this->load->view("/admin/product_group/nodes", array("groups"=>$groups['items']), TRUE );
        }
        $data['sort_by']=$sort_by;
        $data['order_by']=strtolower($order_by);
        $res=$this->load->view("/admin/product_group/list", $data, TRUE );
        make_response("output", $res, TRUE);
    }
    
    
    /**
     * Check group fields
     *
     * @return array
     */
    function _check_group_fields()
    {      
        $_errors=0;        
        $info=array();
        
        do
        {
            $info['_name']  =   prepare_text(   $this->input->post("name") );
            
            if( !   $this->_check_len($info['_name'],   1, 255))
            {
                $_errors=1;
                break;
            }
            
            $info['_descr']  =   prepare_text(  $this->input->post("descr") );
            
            if( !   $this->_check_len($info['_descr'],  1, 65535))
            {
                $_errors=2;
                break;
            }
                                     
            $info['_products']  =   $this->input->post("g_products");
            
            $_flag=false;
            
            if(is_array($info['_products']))
            {       
                
                foreach($info['_products'] as $k=>$v)
                {
                    $info['_products'][$k]= (int) $v;
                    
                    if(! $_flag )                        
                        $_flag=$v;
                }
            }
            else
            {           
                $_flag=(int)$info['_products'];
                $info['_products']=array($info['_products']);
            }
            
        }while(0);
        $info['errors']=$_errors;        
        return $info;
    
    }
    
    
    
    /**
     * Add group of product
     *
     */
    function add()
    {    
        $data=array('errors'=>0);
        
        
        if($this->input->post("action")=="add")
        {
            $info=$this->_check_group_fields();
            
            if(!$info['errors'])
            {
                if(!$this->product_group->add($info['_name'], $info['_descr']))
                {
                    $data['errors']=4;
                }
                else
                {
                    $data['errors']=-1;
                }
            }
            else
            {
                $data['errors']=$info['errors'];
            }
        }
        
        if($data['errors']>0)
        {            
            make_response("error", $data['errors'], TRUE);
            
        }
        else
        {
            if($data['errors']==-1)
                $res='';
            else
                $res=$this->load->view("/admin/product_group/add", $data, TRUE );
                
            make_response("output", $res, TRUE);
        }
        simple_admin_log('product_group_add',false,($data['errors']>0),"not_added");
    }
    
    /**
     * Modify group of product
     *
     * @param string $id
     */
    function edit($id='')
    {
        $id=(int)$id;
        $data=array('errors'=>0);
        
        
        if($this->input->post("action")=="save")
        {
            $info=$this->_check_group_fields();
            
            if(!$info['errors'])
            {
                if(!$this->product_group->group_set($id, $info['_name'], $info['_descr']))
                {
                    $data['errors']=4;
                }
                else
                {
                    $data['errors']=-1;
                }
            }
            else
            {
                $data['errors']=$info['errors'];
            }
            simple_admin_log('product_group_modify',$id,($data['errors']>0),"not_updated");
        }
        else
        {
            $d=$this->product_group->group_get($id);
            
            if(!$d)
            {
                $data['not_exists']=true;
            }
            else
            {
                $data=array_merge($d[0], $data);
            }            
        }
        
        if($data['errors']>0)
        {            
            make_response("error", $data['errors'], TRUE);
        }
        else
        {
            if($data['errors']==-1)
                $res='';
            else
                $res=$this->load->view("/admin/product_group/edit", $data, TRUE );
                
            make_response("output", $res, TRUE);
        }
    }
    
    /**
     * Delete product's group
     *
     */
    function delete_group()
    {
        $id=(int)input_post("id", 1);        
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_product_groups_modify', $id);
        if($functionality_enabled_error!==true)
        {
            make_response("error", '2', TRUE);
            return;
        }
        //*******End of functionality limitations********
        
        if( !$this->product_group->delete_group($id)  )
        {
            make_response("error", '1', TRUE);
            $error=true;
        }
        else
        {
            make_response("output", '', TRUE);
            $error=false;
        }
        simple_admin_log('product_group_delete',$id,$error,"not_deleted");
    }   
}
?>
