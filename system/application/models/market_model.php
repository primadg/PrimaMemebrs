<?php
/**
 * 
 * THIS FILE CONTAINS Market_model CLASS
 *  
 * @package Needsecure
 * @author Peter Yaroshenko
 * @version uknown
 */
///******************************
//    User Market Model
//    By:     Peter Yaroshenko
//    enc:    UTF-8
//    tab:    4 space's
//*******************************
//*/

/**
 * Include file auth_model.php
 */
require_once("payment_model.php");


/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MARKET
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Market_model extends Payment_model
{
/** 
 * @author kgg 
 * 
 * @var array of valid types default
 */
    var $product_types  = array();
/**
 * THIS METHOD SETS INITIAL VARS (constructor)
 */
    function Market_model()
    {
        parent::Model();
        $CI=&get_instance();
        $CI->load->model('product_model');
        $this->product_types=$CI->product_model->product_types;
        
    }
	/**
	 * Enter description here...
	 *
	 * @param mixed $group_id
	 * @param mixed $products
	 * @param mixed $user_id
	 * @param mixed $member_products
	 * @return mixed
	 */
    function sale_count($group_id=0, $products='', $user_id=0, $member_products=false)
    {
        $group_id=(int)$group_id;
        $user_id=(int)$user_id;
        $subscribed='';

        if($products)
           $subscribed=$products;
        else
        {
            if($user_id)
            {
                $subscribed=$this->subscribed_products($user_id);
            }
        }

        $this->db->select("count(products.id) cnt");
        $this->db->from(db_prefix."Products products");

        $gr='';

        if($group_id)
        {
            $gr=' and product_product_group.product_group_id='.$group_id;
        }

        $this->db->join(db_prefix.'Product_product_group `product_product_group`',
            'product_product_group.product_id=products.id'.$gr);

        //filtering member group
        if($member_products!==false && count($member_products))
        {
            $this->db->join(db_prefix.'Member_groups_products member_groups_products', 'member_groups_products.product_id=products.id' , 'LEFT');
            $this->db->where_in('member_groups_products.product_id',array_keys($member_products));
            $this->db->distinct();
        }
        
/* edited kgg
        $this->db->where("products.blocked <> 1");
        $this->db->where("products.closed <> 1");
*/
        $this->_sql_valid_product("products");
        
        if($subscribed)
        {
            $this->db->where("products.id not in($subscribed)");
        }

        $query=$this->db->get();

        if($query->num_rows())
        {
            $row=$query->row();
            return $row->cnt;
        }
        else
        {
            return false;
        }
    }
	/**
	 * Enter description here...
	 *
	 * @param array $item
	 * @param mixed $user_id
	 * @return mixed
	 */
    function _product_info($item, $user_id=0)
    {
        if($item=$this->get_product_info($item['id'], $user_id,(bool)$user_id))
        {
            $item=$item[0];
            $disc=(float)$item['discount'];
            $type=$item['discount_type'];

/*            if ($disc=0 and $type=1) //discount is 0% ?
            {
                $disc = 0;
            }*/

            $item['new_day']=    $disc ? $this->use_discount($item['day'],        $type, $disc):0;
            $item['new_month']=  $disc ? $this->use_discount($item['month'],      $type, $disc):0;
            $item['new_month3']= $disc ? $this->use_discount($item['month3'],     $type, $disc):0;
            $item['new_month6']= $disc ? $this->use_discount($item['month6'],     $type, $disc):0;
            $item['new_year']=   $disc ? $this->use_discount($item['year'],       $type, $disc):0;
            $item['new_unlimit']=$disc ? $this->use_discount($item['unlimit'],    $type, $disc):0;
/*            $item['new_day'] = $this->use_discount($item['day'], $type, $disc);
            $item['new_month'] = $this->use_discount($item['month'], $type, $disc);
            $item['new_month3'] = $this->use_discount($item['month3'], $type, $disc);
            $item['new_month6'] = $this->use_discount($item['month6'], $type, $disc);
            $item['new_year'] = $this->use_discount($item['year'], $type, $disc);
            $item['new_unlimit'] = $this->use_discount($item['unlimit'], $type, $disc);*/

            return $item;
        }
        else
            return false;
    }
	/**
	 * Enter description here...
	 *
	 * @param mixed $page
	 * @param mixed $pp
	 * @param mixed $group_id
	 * @param mixed $user_id
	 * @return array
	 */
    function sale_list($page=1, $pp=0, $group_id=0, $user_id=0)
    {

        $result=_standart_ret();
        fb($user_id,'$USER_ID');
        if($user_id)
            $subscribed=$this->subscribed_products($user_id);
        else
            $subscribed='';
            
        fb($subscribed,'$SUBSCRIBED');
        
        $CI=&get_instance();
        $CI->load->model("member_group_model");
        $member_products=$CI->member_group_model->get_member_products($user_id);

        if($subscribed)
        {
        	$hosted = '';
       		if (Functionality_enabled('admin_product_hosted')===true)
			{
				$CI->load->model("host_plans_model");
				$host_plans = $CI->host_plans_model->Load_User_host_plans($user_id);
                if (is_array($host_plans) && count($host_plans)>0)
                {
/**
 * @todo one user - one hosting plan (while)
 */                	
                	$hosted = 'products.product_type != '.PRODUCT_HOSTED;
                }
			}
        }
        
        $result['total']=$this->sale_count($group_id, $subscribed, $user_id, $member_products);

        $page       =(int)$page;
        $pp         =(int)$pp;
        $group_id   =(int)$group_id;
        $user_id    =(int)$user_id;

        if($result['total'] <= ( $page -1 ) * $pp)
        {
            $page=1;
        }
        
        $this->db->select("products.id");
        $this->db->from(db_prefix."Products products");

        $gr='';
        if($group_id)
        {
            $gr=" and product_product_group.product_group_id=$group_id";
        }

        if($subscribed)
        {
            $this->db->where("products.id not in($subscribed)");
            if ($hosted)
            {
            	$this->db->where($hosted);
            }
        }

        $this->db->join(db_prefix.'Product_product_group `product_product_group`',
        'product_product_group.product_id=products.id'.$gr);
        
        
        //filtering member group
        if(count($member_products))
        {
            $this->db->join(db_prefix.'Member_groups_products member_groups_products', 'member_groups_products.product_id=products.id' , 'LEFT');
            $this->db->where_in('member_groups_products.product_id',array_keys($member_products));
            $this->db->distinct();
        }

//        $this->db->where("blocked <> 1");
//        $this->db->where("products.closed <> 1");
        $this->_sql_valid_product("products");
        
        $this->db->order_by("products.id asc");
        $this->db->limit($pp, ($page-1)*$pp);
        $query=$this->db->get();
fbq("Market sale_list");
        $result['count']=$query->num_rows();
        $result['page']=$page;
        $result['result']=true;
        $result['per_page']=$pp;

        $result['items']=$query->result_array();

        foreach($result['items'] as $k=>$item)
        {
            if(!$result['items'][$k]=$this->_product_info($item, $user_id))
            {
                unset($result['items'][$k]);
            }
            else if(isset($member_products[$item['id']]))
            {
                $result['items'][$k]['available']=$member_products[$item['id']];
            }
        }
fb($result,"result");
        return $result;
    }

	/**
	 * Enter description here...
	 *
	 * @param mixed $user_id
	 * @return mixed
	 */
    function active_count($user_id)
    {
        $user_id=(int)$user_id;

        $this->db->select("count(products.id) cnt");
//        $this->db->from(db_prefix."Products products");

        $this->db->from(db_prefix."Products products");
        $this->db->from(db_prefix."Subscriptions subscr");
        $this->db->join(db_prefix."Protection protection",
                                    "
                                    protection.product_id=products.id and
                                    protection.subscr_id=subscr.id and
                                    protection.user_id=$user_id");

        $this->db->where("subscr.status in (1,2) ");

        $query=$this->db->get();

        if($query->num_rows())
        {
            $row=$query->row();
            return $row->cnt;
        }
        else
        {
            return false;
        }
    }






	/**
	 * Enter description here...
	 *
	 * @param mixed $user_id
	 * @param mixed $page
	 * @param mixed $pp
	 * @param mixed $subscr_id
	 * @return array
	 */
    function active_list($user_id, $page=1, $pp=0, $subscr_id=0)
    {
        $result=_standart_ret();

        $result['total']=$this->active_count($user_id);

        $page       =(int)$page;
        $pp         =(int)$pp;
        $user_id    =(int)$user_id;
        $subscr_id    =(int)$subscr_id;

        if($result['total'] <= ( $page -1 ) * $pp)
        {
            $page=1;
        }

        $sbscr='';

        if($subscr_id)
        {
            $page=1;
            $pp=1;
            $sbscr=" subscr.id=$subscr_id and ";
        }

        //$this->db->select("protection.subscr_id as sid, protection.product_id pid, products.image, p_data.name, p_data.descr ");
        $this->db->select("products.id, products.product_type, protection.subscr_id as sid, protection.product_id pid, products.image");
        $this->db->from(db_prefix."Subscriptions subscr");
        $this->db->join(db_prefix."Protection protection",
                    "
                    $sbscr
                    protection.subscr_id=subscr.id and
                    protection.user_id=$user_id
                    ");

        $this->db->join(db_prefix."Products products", "products.id=protection.product_id");
        //$this->db->join(db_prefix."Language_data p_data", "p_data.language_id=1 and p_data.object_id=products.id and object_type=".PRODUCT_OBJECT_TYPE, "left");

        $this->db->where("subscr.status in (1,2) ");

        //$this->db->order_by("protection.product_id");

        //$this->db->limit($pp, ($page-1)*$pp);
        $query=$this->db->get();
        
        $t=$query->result_array();
                
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'name', 'descr'=>'descr'),'id',array('col'=>'pid','offset'=>($page-1)*$pp,'limit'=>$pp),false,&$add_params); 
        //$rv['count']=count($t);
        
        $result['page']=$page;
        $result['result']=true;
        $result['per_page']=$pp;
        $result['count']=count($t);
        $result['items']=$t;
        
        

        foreach($result['items'] as $k=>$item)
        {
            if  (
                    $res=$this->get_subscr_info($item['sid'])
                )
            {
            	
                $result['items'][$k]=array_merge($res[0], $item);
				if ( (Functionality_enabled('admin_product_hosted')===true) && $item['product_type'] ==PRODUCT_HOSTED )
				{
					$CI->load->model("host_plans_model");
					$result['items'][$k]['dirs']=$CI->host_plans_model->_get_product_links($item['pid']);
                    $ok_res=$CI->host_plans_model->get_host_subscr_info($item['sid']);
					$result['items'][$k]['dirs'][0]['name_plan']=$result['items'][$k]['dirs'][0]['name'];
					$result['items'][$k]['dirs'][0]['name']=$ok_res[0]['name_domen'];
					$result['items'][$k]['dirs'][0]['http_path']='http://'.$ok_res[0]['name_domen'];
					unset($ok_res);
				}
				else
				{
	                $result['items'][$k]['dirs']=$this->_get_product_links($item['pid']);
				}

                if($this->check_used_trial($user_id,   $item['pid']))
                {
                    $result['items'][$k]['trial_period_value']=0;
                }

            }
            else
                unset($result['items'][$k]);

        }
        return $result;
    }

	/**
	 * Enter description here...
	 *
	 * @param mixed $user_id
	 * @return mixed
	 */
    function get_user_product_cnt($user_id)
    {
        $user_id=(int)$user_id;
        if($user_id)
        {
            $this->db->select("count(protection.product_id) cnt");
            $this->db->from(db_prefix."Subscriptions subscr");
            $this->db->join(db_prefix."Protection protection",
            "
            subscr.status=1 and
            protection.subscr_id=subscr.id and
            protection.user_id=$user_id");

            $q=$this->db->get();
            $r=$q->row();
            return $r->cnt;
        }
        return false;
    }


	/**
	 * Enter description here...
	 *
	 * @param mixed $pid
	 * @return mixed
	 */
    function _get_product_links($pid)
    {
        $pid=(int)$pid;

        $this->db->select("dirs.*");
        $this->db->from(db_prefix."Dirs dirs");

        $this->db->join(db_prefix."Dir_products pdirs",
                                        "pdirs.product_id=$pid and pdirs.dir_id=dirs.id");
        $q=$this->db->get();

        if($q->num_rows())
        {
            return $q->result_array();
        }
        return false;
    }
    
    /**
    * Get available subscriptions for all users and all products or specified users and/or products
    *
    * @author onagr
    * @param mixed $users_id
    * @param mixed $products_id
    * @return array
    */
    function Get_available_free_subscriptions($users_id=false,$products_id=false)
    {
        $users_id=($users_id && !is_array($users_id)) ? array($users_id) : $users_id;
        $users_id=($users_id && !count($users_id)) ? false : $users_id;
        $products_id=($products_id && !is_array($products_id)) ? array($products_id) : $products_id;
        $products_id=($products_id && !count($products_id)) ? false : $products_id;
        
        $query=$this->db->query('
    SELECT DISTINCT members.user_id, products.product_id 
    FROM (`'.db_prefix.'Member_groups` member_groups) 
    LEFT JOIN `'.db_prefix.'Member_groups_products` products ON products.group_id=member_groups.id 
    LEFT JOIN `'.db_prefix.'Member_groups_members` members ON members.group_id=member_groups.id 
    LEFT JOIN `'.db_prefix.'Products` prods ON prods.id=products.product_id      
    LEFT JOIN `'.db_prefix.'Prices` price ON price.id=products.product_id     
    WHERE members.user_id NOT IN (
        SELECT `user_id` FROM (`'.db_prefix.'Protection` Protection)
        LEFT JOIN `'.db_prefix.'Subscriptions` as Subscriptions ON Protection.subscr_id = Subscriptions.id
        WHERE Protection.user_id = members.user_id AND Protection.product_id = products.product_id AND
        (Subscriptions.status = 1 or Subscriptions.status = 2) 
    ) AND members.user_id <> 1 AND products.available = 1 AND prods.blocked <> 1 AND  prods.closed <> 1 AND
    (price.day+price.month+price.month3+price.month6+price.year+price.unlimit)=0 '.
        ($users_id ? 'AND members.user_id IN ('.implode(",",$users_id).')' : '').
        ($products_id ? 'AND products.product_id IN ('.implode(",",$products_id).')' : '')
        );
        fbq('AVAILABLE_SUBSCRIPTIONS');
        $result=$query->result_array();
        fb($result,'AVAILABLE_SUBSCRIPTIONS');    
        return $result;
    }
    

	/**
	 * Enter description here...
	 *
	 * @param mixed $user_id
	 * @return string
	 */
    function subscribed_products($user_id)
    {
        $user_id=(int)$user_id;


        $this->db->select("products.id as pid");
        $this->db->from(db_prefix."Products products");

        if($user_id)
        {
            $this->db->from(db_prefix.'Protection protect');

            $this->db->join(db_prefix.'Subscriptions subscr',
                    "protect.user_id=$user_id AND protect.product_id=products.id AND protect.subscr_id=subscr.id AND subscr.status in (1,2)");
        }

        $this->db->join(db_prefix.'Product_product_group `product_product_group`',
            'product_product_group.product_id=products.id');

/*        $this->db->where("products.blocked <> 1");
        $this->db->where("products.closed <> 1");
*/
        $this->_sql_valid_product("products");
        
        $query=$this->db->get();

        if($query->num_rows())
        {
            $str='';
            foreach($query->result_array() as $p)
            {
                $str.="{$p['pid']},";
            }
            return substr($str,0,strlen($str)-1);
        }
        else
        {
            return '';
        }
    }



    /**
    * Returns the number of user's subscriptions records
    *
    * @author drovorubov
    * @param integer $uid
    * @return mixed integer/false
    */
    function subscriptions_count($uid)
    {
        if( intval($uid) < 1 )
        {
            return false;
        }
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Protection protection',db_prefix.'Subscriptions subscriptions'));
        $this->db->where('protection.user_id',$uid);
        $this->db->where('subscriptions.id=protection.subscr_id');
        $this->db->join(db_prefix.'Products products', 'products.id=protection.product_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->all_rows;
        }
        return false;
    }




    /**
    * Returns subscriptions list according user id
    *
    * @param integer $page
    * @param integer $count (per page param)
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $uid
    * @return array
    */
    function subscriptions($page,$count,$sort_by,$sort_how,$uid)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if($uid < 1)
        {
            return $rv;
        }

        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $rv;
        }

        $rv['per_page'] = $count;

        //Set order type
        $sort_how = ($sort_how == 'ASC') ? 'ASC' : 'DESC';
        //Set order before selection
        //$sort_by = $this->_get_subscribe_order($sort_by,$sort_how);
        //Get total subscriptions count
        $rv['total'] = $this->subscriptions_count($uid);

        //Set rows start limit for select
        if($page > 1 && $rv['total'] > 0)
        {
            $row_start = intval(($page - 1) * $count);
			if( $row_start >= $rv['total'] )
			{
                if($page > 2)
                {
                    $page--;
                    $row_start = intval(($page - 1) * $count);
                }
                else
                {
                    $row_start = 0;
                }
			}
        }
        else
        {
            $row_start = 0;
        }
        //Prepare SQL query
        //$this->db->select('subscriptions.cdate');
        //$this->db->select('ld_product.name product_name');
        $this->db->select('products.id as product_id, subscriptions.cdate, subscriptions.id, subscriptions.type, subscriptions.status, subscriptions.regular_price');
        $this->db->from(array(db_prefix.'Protection protection',db_prefix.'Subscriptions subscriptions'));
        $this->db->where('protection.user_id',$uid);
        $this->db->where('subscriptions.id=protection.subscr_id');
        $this->db->join(db_prefix.'Products products', 'products.id=protection.product_id', 'left');
        //Join table Language_data
        //$this->db->join(db_prefix.'Language_data ld_product','ld_product.object_id=products.id AND ld_product.object_type=4 AND ld_product.language_id=1','LEFT');
        //$this->db->limit($count,$row_start);
        //$this->db->order_by($sort_by);
        //Execute query
        $query = $this->db->get();
        
        $sort_param=array();
        $sort_param['by_date']='cdate';
        $sort_param['by_product']='product_name';
        $sort_param['by_type']='type';
        $sort_param['by_price']='regular_price';
        //$sort_param['by_transaction']='';        
        $sort_by=array_key_exists($sort_by,$sort_param) ? $sort_param[$sort_by] : $sort_param['by_date'];
        
        
        $t=$query->result_array();
                
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name'),'product_id',array('col'=>$sort_by,'order'=>$sort_how,'offset'=>$row_start,'limit'=>$count),false,&$add_params);
        $rv['count']=count($t);
        
        //$rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $t;
            $rv['result'] = true;
        }
        return $rv;

    }



    /**
    * Converts ORDER params for user's subscriptions query
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_subscribe_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_date':
            {
                $rv = 'subscriptions.cdate ' . $ord_type;
                break;
            }
            case 'by_product':
            {
                $rv = 'protection.product_id ' . $ord_type;
                break;
            }
            case 'by_type':
            {
                $rv = 'subscriptions.type ' . $ord_type;
                break;
            }
            case 'by_price':
            {
                $rv = 'subscriptions.regular_price ' . $ord_type;
                break;
            }
            case 'by_transaction':
            {
                $rv = 'transactions.id ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'subscriptions.cdate DESC';
            }
        }

        return $rv;
    }


    /**
    * Gets the list of user's transactions according subscription id
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $sid (subscription id)
    * @return array
    */
    function transactions($page,$count,$sort_by,$sort_how,$sid)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if($sid < 1)
        {
            return $rv;
        }

        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $rv;
        }

        $rv['per_page'] = $count;

        //Set order type
        $sort_how = (strtoupper($sort_how) == 'ASC') ? 'ASC' : 'DESC';
        //Set order before selection
        $sort_by = $this->_get_transaction_order($sort_by,$sort_how);
        //Get total count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Transactions transactions'));
        $this->db->where('transactions.subscription_id',$sid);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $rv['total'] = $row->all_rows;
        }
        //Set rows start limit for select
        if($page > 1 && $rv['total'] > 0)
        {
            $row_start = intval(($page - 1) * $count);
			if( $row_start >= $rv['total'] )
			{
                if($page > 2)
                {
                    $page--;
                    $row_start = intval(($page - 1) * $count);
                }
                else
                {
                    $row_start = 0;
                }
			}
        }
        else
        {
            $row_start = 0;
        }
        //Prepare SQL query for getting the list
        $this->db->select("transactions.date as date");
        $this->db->select('transactions.id, transactions.summ,  transactions.pay_system_id, transactions.subscription_id as subscr_id');
        $this->db->from(array(db_prefix.'Transactions transactions'));
        $this->db->where('transactions.subscription_id',$sid);
        $this->db->limit($count,$row_start);
        $this->db->order_by($sort_by);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $query->result_array();
            $rv['result'] = true;
        }
        return $rv;
    }


    /**
    * Converts ORDER params for user's transactions query
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_transaction_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_trans_info':
            {
                $rv = 'transactions.info ' . $ord_type;
                break;
            }
            case 'by_trans_paysys':
            {
                $rv = 'transactions.pay_system_id ' . $ord_type;
                break;
            }
            case 'by_trans_amount':
            {
                $rv = 'transactions.summ ' . $ord_type;
                break;
            }
            case 'by_trans_date':
            {
                $rv = 'transactions.date ' . $ord_type;
                break;
            }
            case 'by_trans_id':
            {
                $rv = 'transactions.id ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'transactions.date DESC';
            }
        }

        return $rv;
    }



    /**
    * Gets user's transaction info
    *
    * @author Drovorubov
    * @param integer $id
    * @return mixed array/false
    */
    function transaction_info($id)
    {
        $id = intval($id);
        if($id < 1)
        {
            return false;
        }

        $this->db->select('id, summ, info, pay_system_id, subscription_id,date');
        $this->db->from(array(db_prefix.'Transactions'));
        $this->db->where('id',$id);
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $tmp = $query->result_array();
            return $tmp[0];
        }
        return false;
    }

    /**
     * Condition validate products
     *
     * @param string $table alias 
     * @return void set condition in $CI->db class
     * @author kgg
     */
    function _sql_valid_product($table='products')
    {
        $this->db->where("$table.blocked <> 1");
        $this->db->where("$table.closed <> 1");
        $aprod  = $this->product_types;
        $this->db->where_in("$table.`product_type`",$aprod);
    }

    



}



?>
