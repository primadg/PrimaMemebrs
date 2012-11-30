<?php
/**
 * Member_group_model
 *
 * A model for working with user groups.
 *
 * @package		Needsecure 2
 * @author		onagr
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Member_group_model
 *
 * A model for working with user groups.
 *
 * @package		Needsecure 2
 * @category	model
 * @author		onagr
 */
class Member_group_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author onagr
    * @return void
    */
    function Member_group_model()
    {
        parent::Model();
    }
    
    /**
    * Get_new_id
    * Creating new member group 
    * @return integer New group id
    */
    function Get_new_id()
    {
        $this->db->query("insert into ".db_prefix."Member_groups values ()");
        return $this->db->insert_id();    
    }
    
    /**
    * Delete_product_groups
    * Deleting all relation product-member_groups 
    * @param integer $product_id
    * @return boolean
    */
    function Delete_product_groups($product_id)
    {
        if(intval($product_id))
        {
            return $this->db->delete(db_prefix.'Member_groups_products', array('product_id' => $product_id));
        }
        return false;
    }
    
    /**
    * Delete_member_groups
    * Deleting all relation member-member_groups 
    * @param integer $user_id
    * @return boolean
    */
    function Delete_member_groups($user_id)
    {
        if(intval($user_id))
        {
            return $this->db->delete(db_prefix.'Member_groups_members', array('user_id' => $user_id));
        }
        return false;
    }
    
    /**
    * add_panel_vars_ex
    * Add errors, messages and temp variabled
    * @param array $data current data array
    * @param string $section
    * @return array result data array
    */
    function add_panel_vars_ex($data,$section)
    {
        switch ($section)
        {
        case "member_group":
            //**************************member_group*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            $temp_vars_set['are_you_sure']="<{admin_member_group_msg_are_you_sure}>";
            $temp_vars_set['panel_script']=base_url()."js/admin/member_group/member_group.js";
            $temp_vars_set['panel_hash']="member_groups";
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
            $messages['deleted_ok'] = "<{admin_member_group_msg_ok_deleted}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            $mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_deleted'] = "<{admin_member_group_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_member_group_msg_er_not_found}>";
            $mess_err['access_denied'] = "<{admin_member_group_msg_er_access_denied}>";

            $data['mess_err'] = $mess_err;
            //***********************end_of_member_group***************************
            break;
        }
        return $data;
    }
    
    /**
    * Is_product_available
    * Checks the availability of the product to the user.
    * @param integer $user_id user identificator
    * @param integer $product_id product identificator
    * @return boolean
    */
    function Is_product_available($user_id,$product_id)
    {
        if(intval($product_id))
        {
            $this->db->select('member_groups.id, products.available');
            $this->db->from(db_prefix.'Member_groups member_groups');
            $this->db->join(db_prefix.'Member_groups_products products',
            'products.group_id=member_groups.id' , 'LEFT');
            $this->db->where('products.product_id',$product_id);
            if(intval($user_id))
            {
                $this->db->join(db_prefix.'Member_groups_members members',
                'members.group_id=member_groups.id' , 'LEFT');
                $this->db->where('members.user_id',$user_id);
            }
            else
            {
                $this->db->where('member_groups.id',1);    
            }
            $query=$this->db->get();
            $t=$query->result_array();
            foreach($t as $value)
            {
                if(intval($value['available'])>0)
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
    * Get_product_groups
    * Selects all the users who see the product and indicates its availability for purchase.
    * @param integer $product_id product identificator
    * @return array ('id'=>'available')
    */
    function Get_product_groups($product_id)
    {
        $t=array();
        if(intval($product_id)>0)
        {
            $this->db->select('member_groups.id, products.available');
            $this->db->from(db_prefix.'Member_groups member_groups');
            $this->db->join(db_prefix.'Member_groups_products products',
            'products.group_id=member_groups.id' , 'LEFT');
            $this->db->where('products.product_id',$product_id);
            $query=$this->db->get();
            $t=$query->result_array();
            $t=array_transform($t,'id','available');
        }
        return $t;
    }
    
    /**
    * Set_product_groups
    * Sets the group of users who see the product and indicates its availability for purchase.
    * @param integer $product_id product identificator
    * @param array $groups ('id'=>'available')
    * @return boolean
    */
    function Set_product_groups($product_id,$groups)
    {
        if(intval($product_id)>0)
        {
            $real_groups=array();
            if(!empty($groups) && count($groups))
            {
                $this->db->select('id');
                $this->db->where_in('id',array_keys($groups));
                $query=$this->db->get(db_prefix.'Member_groups');
                $real_groups=array_transform($query->result_array(),false,'id');
            }
            $this->db->delete(db_prefix.'Member_groups_products', array('product_id' => $product_id));
            foreach($real_groups as $group_id)
            {
                $this->db->insert(db_prefix.'Member_groups_products', array('group_id'=>intval($group_id),'product_id'=>intval($product_id),'available'=>intval($groups[$group_id]))); 
            }
            return true;
        }
        return false;
    }
    
    /**
    * Set_member_groups
    * Sets the user group to which user.
    * @param integer $user_id user identificator
    * @param array $groups group identificators
    * @return boolean
    */
    function Set_member_groups($user_id,$groups=array())
    {
        if(intval($user_id)>0)
        {
            
            if(!empty($groups) && count($groups))
            {
                $this->db->select('id');
                $this->db->where_in('id',$groups);
                $query=$this->db->get(db_prefix.'Member_groups');
                $groups=array_transform($query->result_array(),false,'id');
            }
            if(!in_array(1,$groups))
            {
                $groups[]=1;
            }
            $groups=array_unique($groups);
            $this->db->delete(db_prefix.'Member_groups_members', array('user_id' => $user_id));
            foreach($groups as $group_id)
            {
                $this->db->insert(db_prefix.'Member_groups_members', array('group_id'=>intval($group_id),'user_id'=>intval($user_id))); 
            }
            return true;
        }
        return false;
    }
    
    /**
    * Get_member_products
    * Select products visible to a user and indicates their availability for purchase.
    * @param integer $user_id user identificator
    * @return array ('id'=>'available')
    */
    function Get_member_products($user_id)
    {
        $this->db->select('products.product_id, products.available');
        $this->db->from(db_prefix.'Member_groups member_groups');
        $this->db->join(db_prefix.'Member_groups_products products',
        'products.group_id=member_groups.id' , 'LEFT');
        if(intval($user_id))
        {
            $this->db->join(db_prefix.'Member_groups_members members',
            'members.group_id=member_groups.id' , 'LEFT');
            $this->db->where('members.user_id',$user_id);
        }
        else
        {
            $this->db->where('member_groups.id',1);    
        }
        $query=$this->db->get();
        $t=$query->result_array();
        $res=array();
        fb($t, __FUNCTION__ .'t');
        foreach($t as $value)
        {
            $id=$value['product_id'];
            $res[$id]=isset($res[$id]) ? $res[$id] : 0;
            $res[$id]=intval($value['available'])>0 ? 1 : $res[$id];
        }
        fb($res, 'res');
        return $res;        
    }
    
    /**
    * Get_member_groups
    * Select a user group to which the user.
    * @param integer $user_id user identificator
    * @return array ('id'=>'name')
    */
    function Get_member_groups($user_id)
    {
        $t=array();
        if(intval($user_id)>0)
        {
            $this->db->select('member_groups.id');
            $this->db->from(db_prefix.'Member_groups member_groups');
            $this->db->join(db_prefix.'Member_groups_members members',
            'members.group_id=member_groups.id' , 'LEFT');
            $this->db->where('members.user_id',$user_id);
            $query=$this->db->get();
            $t=$query->result_array();
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $t=$CI->lang_manager_model->combine_with_language_data($t,15,array('name'=>'name'),'id',false,false,&$add);
            $t=array_transform($t,'id','name');
        }
        return $t;
    }
    
    /**
    * Get_member_groups
    * Select all the user groups. 
    * @param boolean $general_less Indicates to ignore the general group (id = 1)
    * @return array (0=>array('id','name'))
    */
    function Group_list($general_less=false)
    {
        $this->db->order_by('id','asc'); 
        if($general_less)
        {
            $this->db->where('id!=1');
        }
        $query = $this->db->get(db_prefix.'Member_groups');
        $t=$query->result_array();
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,15,array('name'=>'name'),'id',false,false,&$add);
        return $t;
    }
    
    /**
    * Get_member_groups
    * Select all the user groups. 
    * @param array $post pager data
    * @return array (0=>array('id','name','users','products'))
    */
    function Get_group_list($post)
    {
        $data=array();        
        
        $this->db->select('member_groups.id, count(members.user_id) as users');
        $this->db->from(db_prefix.'Member_groups member_groups');
        $this->db->join(db_prefix.'Member_groups_members members',
                                    'members.group_id=member_groups.id' , 'LEFT');
        $this->db->group_by('id');
        $query=$this->db->get();
        $group_list=$query->result_array();
        //print_r($group_list);
        
        $this->db->select('member_groups.id, count(products.product_id) as products');
        $this->db->from(db_prefix.'Member_groups member_groups');
        $this->db->join(db_prefix.'Member_groups_products products',
                                    'products.group_id=member_groups.id' , 'LEFT');
        $this->db->group_by('id');
        $query=$this->db->get();
        $products=array_transform($query->result_array(),'id','products');
        
        $this->db->select('member_groups.id, count(products.product_id) as available_products');
        $this->db->from(db_prefix.'Member_groups member_groups');
        $this->db->join(db_prefix.'Member_groups_products products',
                                    'products.group_id=member_groups.id' , 'LEFT');
        $this->db->where('products.available>0');        
        $this->db->group_by('id');
        $query=$this->db->get();
        $available=array_transform($query->result_array(),'id','available_products');
        
        foreach($group_list as $key=>$value)
        {
            $group_list[$key]['available_products']=isset($available[$value['id']]) ? $available[$value['id']]['available_products'] : 0;
            $group_list[$key]['products']=isset($products[$value['id']]) ? $products[$value['id']]['products'] : 0;            
        }
        
        $t=$group_list;
        $total=count($t);
        $data['pagers'] = pager_ex($post, $total, array('id','name','users','products'));
        $params = $data['pagers']['params'];
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,15,array('name'=>'name','descr'=>'title'),'id',array('col'=>$params['column'],'order'=>$params['order'],'limit'=>$params['limit'],'offset'=>$params['offset']),false,&$add);
        $data['group_list']=$t;
        return $data;        
    }
    
    /**
    * Delete group
    * Deletes a group of users, without the removal of such users.
    * @param array $post Contains the ID of the group.
    * @return boolean|string true|error_type
    */
    function Delete_group($post)
    {
        if(isset($post['id']) && intval($post['id'])>0)
        {
            $id=intval($post['id']);
            $query = $this->db->get_where(db_prefix.'Member_groups', array('id' => $id));
            if($query->result_array())
            {
                if($id==1)
                {
                    return "not_deleted";
                }
                if($this->db->delete(db_prefix.'Member_groups', array('id' => $id)) && $this->db->affected_rows()>0)
                {
                    $this->db->delete(db_prefix.'Member_groups_members', array('group_id' => $id));
                    $this->db->delete(db_prefix.'Member_groups_products', array('group_id' => $id));
                    $CI =& get_instance();        
                    $CI->load->model("lang_manager_model"); 
                    $CI->lang_manager_model->remove_language_data(15,$id);
                    return true;
                }
                else
                {
                    return "not_deleted";
                }
            }
            return "not_found";            
        }
        return "not_found";
    }    
}
?>
