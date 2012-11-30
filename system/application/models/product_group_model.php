<?php
/**
 * 
 * THIS FILE CONTAINS Product group model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/******************************
    Admins  Product group model
    By:     Peter Yaroshenko
    start:  22_04_2008
    end:
    enc:    UTF-8
    tab:    4 space's
*******************************
*/
/**
 * Enter description here...
 */
define('PRODUCT_GROUP_OBJECT_TYPE', 3);
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH PRODUCT GROUP
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Product_group_model extends Model
{
	/**
	 * Enter description here...
	 *
	 * @var array
	 */
    var $list_sort_types= array("name", "p_cnt");


    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Product_group_model()
    {
        parent::Model();
    }

	/**
	 * Get list of all product groups
	 *
	 * @return array
	 */
    function list_all()
    {
        $this->db->select("product_groups.id");

        $this->db->from(db_prefix.'Product_groups `product_groups`');
        
        $query=$this->db->get();
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model");        
        return $CI->lang_manager_model->combine_with_language_data($query->result_array(),PRODUCT_GROUP_OBJECT_TYPE,array('name'=>'name'),'id',array('col'=>'name'),false,&$add_params);
    }

	/**
	 * Get total counts of groups
	 *
	 * @return mixed
	 */
    function group_count()
    {
        $this->db->select("count(product_groups.id) as cnt");

        $this->db->from(db_prefix.'Product_groups `product_groups`');

        $query=$this->db->get();

        if($query->num_rows())
        {
            $row=$query->row();

            return $row->cnt;
        }
        return false;
    }

	/**
	 * Get list of groups with counts of products 
	 *
	 * @param mixed $page
	 * @param mixed $pp
	 * @param string $sort_by
	 * @param string $order_by
	 * @return array
	 */
    function list_group($page, $pp, $sort_by='', $order_by='')
    {
        $result=_standart_ret();

        $page=(int)$page;
        $pp=(int)$pp;

        $result['total']=$this->group_count();

        if($result['total'] <= ( $page -1 ) * $pp)
        {
            $page=1;
        }

        $result['page']=$page;

        $sort_by    =_check_param_array($sort_by    , $this->list_sort_types);
        $order_by   =_check_param_array($order_by   , array("ASC", "DESC"));


        $this->db->select("product_groups.id, count(products.id) as p_cnt");
        $this->db->from(db_prefix.'Product_groups `product_groups`');

        $this->db->join(db_prefix.'Products products',
                                    'products.group_id=product_groups.id and products.closed<>1' , 'LEFT');
        $this->db->group_by("id");

        $query=$this->db->get();
        $result['page']=$page;
        $result['result']=true;
        $result['per_page']=$pp;
        $result['count']=$query->num_rows();
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model");        
        $result['items']=$CI->lang_manager_model->combine_with_language_data($query->result_array(),PRODUCT_GROUP_OBJECT_TYPE,array('name'=>'name'),'id',array('col'=>$sort_by,'order'=>$order_by,'offset'=>($page-1)*$pp,'limit'=>$pp),false,&$add_params);
        
        return $result;
    }
    
    

	/**
	 * Add new product group
	 *
	 * @param string $name
	 * @param string $descr
	 * @return boolean
	 */
    function add($name, $descr)
    {
        $name=mb_substr($name,   0,   255);
        $descr=mb_substr($descr,   0,   65535);

        $this->db->query("insert into ".db_prefix."Product_groups values ()");
        $group_id=$this->db->insert_id();

        $this->db->insert   (db_prefix.'Language_data',
                            array   (
                                    "object_id"=>$group_id,
                                    "object_type"=>PRODUCT_GROUP_OBJECT_TYPE,
                                    "language_id"=>1,
                                    "name"=>$name,
                                    "descr"=>$descr
                                    )
                            );
        return (bool) $this->db->affected_rows();

    }
    /**
     * Enter description here...
     *
     * @return integer
     */
    function Get_new_id()
    {
    $this->db->query("insert into ".db_prefix."Product_groups values ()");
    return $this->db->insert_id();    
    }

	/**
	 * Delete product group
	 *
	 * @param mixed $id
	 * @return boolean
	 */
    function delete_group($id)
    {
        $id=(int)$id;

        $this->db->select("count(products.id) cnt");
        $this->db->from(db_prefix."Products products");
        $this->db->where("group_id=$id and products.closed<>1");
        $query=$this->db->get();

        if($query->num_rows())
        {
            $row=$query->row();
            if($row->cnt)
            {
                return false;
            }
        }

        $this->db->delete(db_prefix."Product_groups", array("id"=>$id));
        $res=$this->db->affected_rows();
        if($res)
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $CI->lang_manager_model->remove_language_data(3,$id);
        }        
        return (bool) $res;
    }


	/**
	 * Get group info
	 *
	 * @param mixed $id
	 * @return mixed
	 */
    function group_get($id)
    {
        $id=(int)$id;

        $this->db->select("product_groups.id, pg_data.name as name, pg_data.descr as descr");

        $this->db->from(db_prefix.'Product_groups `product_groups`');

        $this->db->join(db_prefix.'Language_data pg_data',
                                    'pg_data.object_id=product_groups.id AND
                                    pg_data.object_type='.PRODUCT_GROUP_OBJECT_TYPE , 'INNER');
        $this->db->where("id=$id");

        $query=$this->db->get();

        if($query->num_rows())
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }


	/**
	 * Set info for group
	 *
	 * @param mixed $id
	 * @param string $name
	 * @param string $descr
	 * @return boolean
	 */
    function group_set($id, $name, $descr)
    {
        $id=(int)$id;
        $name=mb_substr($name,   0,   255);
        $descr=mb_substr($descr,   0,   65535);



        $this->db->update   (db_prefix.'Language_data',
                            array
                                (
                                    "name"=>$name,
                                    "descr"=>$descr
                                )
                            ,array
                                (
                                "object_type"=>PRODUCT_GROUP_OBJECT_TYPE,
                                "language_id"=>1,
                                "object_id"=>$id
                                )
                            );
        return (bool) $this->db->affected_rows();
    }
}
?>
