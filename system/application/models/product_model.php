<?php
/**
* 
* THIS FILE CONTAINS Product_model CLASS
*  
* @package Needsecure
* @author uknown
* @version uknown
*/
/******************************
    Admins  Product model
    By:     Peter Yaroshenko
    start:  22_04_2008
    end:
    enc:    UTF-8
    tab:    4 space's
*******************************
*/
/**
* Enter description here...
*
*/
define('PRICES_COUNT', 6);
/**
* Enter description here...
*
*/
define('PRODUCT_OBJECT_TYPE', 4);
/**
* Enter description here...
*
*/
define('PRODUCT_MAX_PRICE', 99999999.99);

/**
* 
* THIS CLASS CONTAINS METHODS FOR WORK WITH PRODUCT
* 
* @package Needsecure
* @author uknown
* @version uknown
*/
class Product_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Product_model()
    {
        parent::Model();
        $this->product_types=$this->get_product_types();
    }

    /**
    * Array of sort types for product
    *
    * @var array
    */
    var $product_list_sort_types= array("name", "users_in", "group_name", "subscr_type");
    /**
    * Array of product trial periods
    *
    * @var array
    */
    var $product_trial_periods  = array("day", "week", "month", "year");
    /**
* kgg
*
* @var array of valid types default
*/
    var $product_types  = array(0);
    
    function get_product_types()
    {
        /** 
* This is type of products 
* 
* @author Korchinskij G.G 
*/
        if (!defined('PRODUCT_PROTECT'))
        {
            define('PRODUCT_PROTECT', 1);
        }
        if (!defined('PRODUCT_HOSTED'))
        {
            define('PRODUCT_HOSTED', 2);
        }
        $types=array(0);
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===true)
        {
            $types[] = PRODUCT_HOSTED;
        }
        if(Functionality_enabled('admin_product_protected')===true)
        {
            $types[] = PRODUCT_PROTECT;
        }
        //*******End of functionality limitations********
        return $types;
    }

    /**	 * Count amoount of products 	 *	 * @param integer $group_id	 * @param mixed $member_group_id	 * @param mixed $member_groups_available	 * @return integer	 */    
    function products_count($group_id=0,$member_group_id=0,$member_groups_available=0)
    {
        $this->db->select('count(products.id) as `cnt`');

        $this->db->from(db_prefix.'Products `products`');

        /**
* Type products
*/
        $this->_where_in_valid_product();

        $gr='';

        if($group_id)
        {
            $gr=' and product_product_group.product_group_id='.$group_id;
        }

        if(intval($member_group_id)!=0||intval($member_groups_available)>0)
        {
            $this->db->join(db_prefix.'Member_groups_products member_groups_products', 'member_groups_products.product_id=products.id' , 'LEFT');
            if(intval($member_group_id)>0)
            {
                $this->db->where('member_groups_products.group_id',$member_group_id);
            }

            if(intval($member_group_id)==-1)
            {
                $this->db->where('member_groups_products.available=0');
            }

            if(intval($member_groups_available)>0)
            {
                $this->db->where('member_groups_products.available>0');
            }
            $this->db->distinct();
        }


        $this->db->join(db_prefix.'Product_product_group `product_product_group`',
        'product_product_group.product_id=products.id'.$gr);


        $this->db->where("products.closed<>1");
        $query=$this->db->get();
        fbq('Products count');
        $row  = $query->row();

        return $row->cnt;
    }

	
	function special_offers_products_count()
	{
		$CI=&get_instance();
		$CI->load->model("market_model");
		$this->db->select('count(products.id) as `cnt`');
		$this->db->from(db_prefix.'Products `products`');
		$this->db->where('products.special_offers=1');
		$CI->market_model->_sql_valid_product("products");
		$query=$this->db->get();
		$row  = $query->row();
		return $row->cnt;
	}
	
	function special_offers_product_list($is_loginned=true, $sort_by='name', $order_by='ASC', /*$count=3,*/ $user_id=0)
	{         
		$result=_standart_ret();
		$CI=&get_instance();
		$CI->load->model("market_model");
        if($user_id and $is_loginned)
            $subscribed=$CI->market_model->subscribed_products($user_id);
        else
            $subscribed='';
		if ($is_loginned)
			$count = intval(config_get('SYSTEM','MAIN_PAGE', 'page_amount'));
		else
			$count = intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_page_amount'));
		if (!$count)
		{
			$count = 3;
		}

		$CI=&get_instance();
        $CI->load->model("member_group_model");
		
        $member_products=$CI->member_group_model->get_member_products($user_id);
        fb($user_id, __function__." user_id ");
		fb($member_products, __function__." member_products");
		$result['total']=$this->special_offers_products_count();
		
		
		if ($count==0)
		{
			$count=$result['total'];
		}
		$r = array();
		if ($count>$result['total'])
		{
			$count=$result['total'];
		}
		
		if ($count < $result['total'])
		{
			$this->db->select("products.id");
			$this->db->from(db_prefix."Products products");
			$this->db->where('products.special_offers=1');
			$CI->market_model->_sql_valid_product("products");
			$this->db->order_by("products.id asc");
			$query=$this->db->get();
			$r = $query->result_array();
			$rr = array();

			for ($r_i=0;$r_i<$count;$r_i++)
			{
				$tmp_r = mt_rand(0, $result['total']-1);
				while (in_array($tmp_r,$rr))
				{
					$tmp_r = mt_rand(0, $result['total']-1);
				}
				$rr[] = $tmp_r;
			}
		}
		$sort_by    =_check_param_array($sort_by    , $this->product_list_sort_types);
        $order_by   =_check_param_array($order_by   , array("ASC", "DESC"));
		
		$this->db->select("products.id");
        $this->db->from(db_prefix."Products products");
		if($subscribed)
        {
            $this->db->where("products.id not in($subscribed)");
        }
		$this->db->join(db_prefix.'Product_product_group `product_product_group`', 'product_product_group.product_id=products.id');
		
		//filtering member group
        if(count($member_products))
        {
            $this->db->join(db_prefix.'Member_groups_products member_groups_products', 'member_groups_products.product_id=products.id' , 'LEFT');
            $this->db->where_in('member_groups_products.product_id',array_keys($member_products));
            $this->db->distinct();
        }
		
		
		
		if (count($r))
		{
			$where = '';
			$r_i = 0;
			for ($r_i=0;$r_i<$count-1;$r_i++)
			{
				$where .= 'products.id='.$r[$rr[$r_i]]['id'].' OR ';	
			}
			$where .= 'products.id='.$r[$rr[$r_i]]['id'];
			$this->db->where($where);
		}
		
		$this->db->where('products.special_offers=1');
		$CI->market_model->_sql_valid_product("products");
		$this->db->order_by("products.id asc");
		
		

		$query=$this->db->get();
		
		$result['count']=$query->num_rows();
		$result['currency'] = config_get('system','config','currency_code');
        //$result['page']=$page;
        $result['result']=true;
        //$result['per_page']=$pp;

        $result['items']=$query->result_array();

        foreach($result['items'] as $k=>$item)
        {
            if(!$result['items'][$k]=$CI->market_model->_product_info($item, $user_id))
            {
                unset($result['items'][$k]);
            }
            else if(isset($member_products[$item['id']]))
            {
                $result['items'][$k]['available']=$member_products[$item['id']];
            }
        }
        return $result;
		
	}
	
	
	 function block_unblock_special_offers_product($id)
    {
        $id=(int)$id;
        $this->db->query("UPDATE ".db_prefix."Products set `special_offers` = NOT `special_offers` where id=$id");
        return (bool) $this->db->affected_rows();
    }
	
	
	/**
	 * Get products list wiyj amount of subscription
	 *
	 * @param mixed $page
	 * @param mixed $pp
	 * @param string $sort_by
	 * @param string $order_by
	 * @param mixed $group_id
	 * @param mixed $member_group_id
	 * @param mixed $member_groups_available
	 * @return array
	 */
    function product_list($page, $pp, $sort_by, $order_by, $group_id=0,$member_group_id=0,$member_groups_available=0)
    {
        $CI =& get_instance();
        $result=_standart_ret();

        $result['total']=$this->products_count($group_id,$member_group_id,$member_groups_available);

        $page       =(int)$page;
        $pp         =(int)$pp;
        $group_id   =(int)$group_id;

        if($result['total'] <= ( $page -1 ) * $pp)
        {
            $page=1;
        }

        $sort_by    =_check_param_array($sort_by    , $this->product_list_sort_types);
        $order_by   =_check_param_array($order_by   , array("ASC", "DESC"));

        $this->db->select("products.id, products.blocked as locked, product_product_group.product_group_id, products.is_recouring as `subscr_type`, closed, special_offers, product_type, dir_id, host_plan_id");
        $this->db->select("count(subscr.id) as `users_in`");
        $this->db->from(db_prefix.'Products `products`');
        /**
* Type products
*/
        $this->_where_in_valid_product();

        $gr='';
        if($group_id)
        {
            $gr=" and product_product_group.product_group_id=$group_id";
        }

        if(intval($member_group_id)!=0||intval($member_groups_available)>0)
        {
            $this->db->join(db_prefix.'Member_groups_products member_groups_products', 'member_groups_products.product_id=products.id' , 'LEFT');
            if(intval($member_group_id)>0)
            {
                $this->db->where('member_groups_products.group_id',$member_group_id);
            }

            if(intval($member_group_id)==-1)
            {
                $this->db->where('(member_groups_products.available=0 OR member_groups_products.product_id is null)');
            }

            if(intval($member_groups_available)>0)
            {
                $this->db->where('member_groups_products.available>0');
            }
            $this->db->distinct();
        }

        $this->db->join(db_prefix.'Product_product_group `product_product_group`',    'product_product_group.product_id=products.id'.$gr);
        $this->db->join(db_prefix.'Protection as `protection`', 'products.id = protection.product_id', 'LEFT');
        $this->db->join(db_prefix.'Subscriptions as `subscr`', 'subscr.id  = protection.subscr_id and subscr.status=1', 'LEFT');
		$this->db->join(db_prefix.'Dir_products as `dir_p`', 'dir_p.product_id  = products.id', 'LEFT');
		$this->db->join(db_prefix.'Host_plans_products as `dir_h`', 'dir_h.product_id  = products.id', 'LEFT');

        $this->db->where("products.closed<>1");
        $this->db->group_by(array("products.id", "products.blocked", "products.is_recouring"));
        $query=$this->db->get();
        //        echo $this->db->last_query();
        fbq('Products list');
fb($query->result_array(), 'query - ');
        $result['page']=$page;
        $result['result']=true;
        $result['per_page']=$pp;

        /**
* @TODO already defined before
*/
        $CI =& get_instance();

        $CI->load->model("lang_manager_model");
        $t=$CI->lang_manager_model->combine_with_language_data($query->result_array(),PRODUCT_GROUP_OBJECT_TYPE,array('name'=>'group_name'),'product_group_id',false,false,&$add_params);
        $result['items']=$CI->lang_manager_model->combine_with_language_data($t,PRODUCT_OBJECT_TYPE,array('name'=>'name'),'id',array('col'=>$sort_by,'order'=>$order_by,'offset'=>($page-1)*$pp,'limit'=>$pp),false,&$add_params);
        $result['count']=count($result['items']);
        return $result;
    }

    /**
    * Add new product
    *
    * @param string $name
    * @param string $descr
    * @param mixed $group_id
    * @param array $prices
    * @param mixed $recurring
    * @param mixed $discount_type
    * @param mixed $discount_value
    * @param mixed $trial_price
    * @param mixed $trial_duration_type
    * @param mixed $trial_duration_value
    * @param mixed $is_comulative
    * @param array $dirs
    * @return mixed
    */
    function product_add($name, $descr, $group_id, $prices, $recurring,
    $discount_type, $discount_value, $trial_price, $trial_duration_type,
    $trial_duration_value, $is_comulative, $product_type, $dirs)
    {
        /* checking */
        $name   =mb_substr($name,   0,   255);
        $descr  =mb_substr($descr,  0,   65535);

        $group_id=(int)$group_id;
        $prices =   $this->_check_prices($prices);
        if( ! $prices )
        {
            return false;
        }
        
        /**
* Check valid type of products
*/
        $product_type = $this->_is_valid_type_product($product_type);
        if( !$product_type )
        {
            return false;
        }
        
        $recurring=(int)$recurring;
        $discount_type=(int)$discount_type;

        if(!is_numeric($discount_value))
        return false;

        if(!is_numeric($trial_price))
        return false;

        $trial_duration_type=_check_param_array($trial_duration_type,  $this->product_trial_periods);

        $trial_duration_value=(int)$trial_duration_value;
        $is_comulative=(int)$is_comulative;
        $dirs=$this->_check_ints($dirs);

        /*inserting*/
        $this->db->insert(db_prefix.'Products', array("is_recouring"=>$recurring, "blocked"=>0, "group_id"=>$group_id, "product_type"=>$product_type));

        $product_id=$this->db->insert_id();
        $CI=&get_instance();
        $CI->load->model("lang_manager_model");
        $lang_data=array();
        $lang_data['id']=$product_id;
        $lang_data['object_type']=PRODUCT_OBJECT_TYPE;
        $lang_data['language_id']=$CI->default_language_id;
        $lang_data['name']=$name;
        $lang_data['descr']=$descr;
        $CI->lang_manager_model->template_set($lang_data);

        $this->db->insert(db_prefix.'Product_discount', array("id"=>$product_id, "discount"=>$discount_value, "discount_type"=>$discount_type, "cumulative"=>$is_comulative));

        $this->db->insert(db_prefix.'Trial'   , array("id"=>$product_id, "period_type"=>$trial_duration_type , "period_value"=>$trial_duration_value  , "price"=>$trial_price));

        $this->db->insert(db_prefix.'Prices'  , array("id"=>$product_id, "day"=>$prices[0], "month"=>$prices[1], "month3"=>$prices[2], "month6"=>$prices[3], "year"=>$prices[4], "unlimit"=>$prices[5]) );

        $this->db->insert(db_prefix.'Product_product_group'  , array("product_id"=>$product_id, "product_group_id"=>$group_id) );

        /**
* kgg 
*/
        
        switch ($product_type) 
        {
        case PRODUCT_PROTECT:
            foreach($dirs as $di)
            {
                $this->db->insert(db_prefix.'Dir_products',   array("product_id"=>$product_id, "dir_id"=>$di) );
            }
            break;
        case PRODUCT_HOSTED:
            foreach($dirs as $di)
            {
                $this->db->insert(db_prefix.'Host_plans_products',   array("product_id"=>$product_id, "host_plan_id"=>$di) );
            }
            break;
        }	
        
        return $product_id;
    }

    /*provate*/
    
    /**
    * Check prices
    *
    * @param array $p
    * @return array
    */
    function _check_prices($p)
    {
        if( @count($p)   !=  PRICES_COUNT   )
        return false;

        foreach($p as $k=>$v)
        {
            $p[$k]=is_numeric($v)?$v:0;
        }
        return $p;
    }
    /**
    * check integer values of the array
    *
    * @param array $p
    * @return array
    */
    function _check_ints($p)
    {
        if( ! @count($p)  )
        return false;

        foreach($p as $k=>$v)
        {
            $p[$k]=(int)$v;
        }
        return $p;
    }
    /**
    * Get list of all products
    *
    * @param mixed $by_group_id
    * @param boolean $all
    * @return array
    */
    function list_all($by_group_id=0, $all=false)
    {
        $by_group_id=(int)$by_group_id;

        $this->db->select("*");
        $this->db->from(db_prefix.'Products `products`');
        /**
* Type products
*/
        $this->_where_in_valid_product();

        if($by_group_id)
        {
            $this->db->where("products.group_id=$by_group_id");
        }

        if($all)
        {
            $this->db->where("products.blocked <> 1");
        }

        $this->db->where("products.closed <> 1");

        $query=$this->db->get();
        fbq('Products list all');

        $CI =& get_instance();
        $CI->load->model("lang_manager_model");
        return $CI->lang_manager_model->combine_with_language_data($query->result_array(),PRODUCT_OBJECT_TYPE,array('name'=>'name'),'id',array('col'=>'name'),false,&$add_params);
    }
    /**
    * Block or unblock the product
    *
    * @param mixed $id
    * @return boolean
    */
    function block_unblock_product($id)
    {
        $id=(int)$id;
        $this->db->query("UPDATE ".db_prefix."Products set `blocked` = NOT `blocked` where id=$id");
        return (bool) $this->db->affected_rows();
    }
    /**
    * Enter description here...
    *
    * @return array
    */
    function Get_paid_products_id()
    {
        $this->db->select('prices.id');
        $this->db->from(db_prefix.'Prices as prices');
        $this->db->where('(prices.day> 0 OR prices.month> 0 OR prices.month3> 0 OR prices.month6> 0 OR prices.year> 0 OR prices.unlimit> 0)');
        $this->db->join(db_prefix.'Products products','products.id=prices.id','left');
        $this->db->where('products.closed',0);
        $query=$this->db->get();
        fbq('Products paid_products_id');
        return $query->result_array();
    }
    /**     * Enter description here...     *     */    function delete_paid_products()
    {
        $paid_products=$this->get_paid_products_id();
        if(count($paid_products))
        {
            foreach($paid_products as $paid_product)
            {
                if(intval($paid_product['id'])>0)
                {
                    $this->delete_product($paid_product['id']);
                }
            }
        }
    }
    /**     * Delete product     *     * @param mixed $id     * @return true     */    function delete_product($id)
    {
        $id=(int)$id;

        $CI=&get_instance();
        $this->load->model("payment_model");
        $this->load->model("member_model");


        $this->db->select('protection.user_id, protection.subscr_id');
        $this->db->from(db_prefix.'Protection as protection');
        $this->db->where('protection.product_id',$id);
        $this->db->where('subscr.status<','3');
        $this->db->join(db_prefix.'Subscriptions as subscr','protection.subscr_id = subscr.id','left');
        $query = $this->db->get();
        fbq('Products delete_product');
        $subscrs=$query->result_array();
        if(count($subscrs)>0)
        {
            $users=array();
            foreach($subscrs as $subscr)
            {
                $CI->payment_model->end_subscr($subscr['subscr_id']);
                $CI->payment_model->create_transaction($subscr['subscr_id'],0,1,0,Array("product_deleted"=>"true"));
                $member_info = $CI->member_model->get_member_info($subscr['user_id']);
                $users[]=$member_info['login'];


                send_system_email_to_user($subscr['user_id'],'user_subscription_expired',array('expired_product_name'=>array('object_id'=>$id,'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
            }
            send_system_subscription_to_admins('admin_subscription_ended',array('user_login'=>implode(", ",$users),'expired_product_name'=>array('object_id'=>$id,'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
        }
        if($im=$this->delete_poster($id))
        {
            $this->delete_poster_file($im);
        }

        $this->db->query("UPDATE ".db_prefix."Products set `closed` = 1 where id=$id");

        $CI->load->model("member_group_model");
        $CI->member_group_model->delete_product_groups($id);
        //@added by val petruchek - firing DIRECTORIES_REMOVED event
        $this->_update_protection($id,$this->get_product_dirs($id), array());
        //@val petruchek code ends: event fired

        return true;
    }



    /**
    * Upload image
    *
    * @param string $field_name
    * @return array
    */
    function upload_image($field_name="")
    {
        $res=array("result"=>false, "errors"=>"", "data"=>array());
        $exts=config_get("product_posters", "exts");
        $config['upload_path']  = config_get("product_posters", "path_original");
        $config['allowed_types']= count($exts) ? join("|",$exts)."|".strtoupper(join("|",$exts)) : "";
        $config['max_size']	    = config_get("product_posters", "max_size");
        $config['max_width']    = config_get("product_posters", "max_width");
        $config['max_height']   = config_get("product_posters", "max_height");
        $config['encrypt_name'] = TRUE;
        $config['overwrite']    = FALSE;
        

        $this->load->library('upload', $config);

        if( $this->upload->do_upload($field_name))
        {
            $res['result']=true;
            $res['data']=$this->upload->data();
        }
        else
        {
            $res['result']=false;
            $res['errors']=$this->upload->display_errors();
        }
        return $res;
    }



    /**
    * Resize image
    *
    * @param string $full_from
    * @param unknown_type $path_to
    * @param unknown_type $name
    * @param unknown_type $width
    * @param unknown_type $height
    * @return mixed
    */
    function resize_image($full_from, $path_to, $name, $width, $height)
    {
        $this->load->library('image_lib');

        $imagesize = @getimagesize($full_from);

        if(!$imagesize)
        {
            return false;
        }

        $config['image_library'] = 'GD2';
        $config['maintain_ratio'] = TRUE;
        $config['source_image'] = $full_from;
		if ($imagesize[0]<$width and $imagesize[1]<$height)
		{
			$config['width'] = $imagesize[0];
        	$config['height']= $imagesize[1];
		}
		else
		{
        	$config['width'] = $width;
        	$config['height']= $height;
		}
        $config['new_image'] =$path_to.$name;

        if ($imagesize[0] > $imagesize[1])
        {
            $config['master_dim'] = 'width';
        }
        else
        {
            $config['master_dim'] = 'height';
        }
fb($config, 'config my - ');
fb($imagesize, 'imagesize my - ');
		
        $this->image_lib->clear();
        $this->image_lib->initialize($config);

        return $this->image_lib->resize();
    }
    /**
    * Set image for product
    *
    * @param mixed $id
    * @param string $poster_name
    * @return boolean
    */
    function set_product_image($id, $poster_name)
    {
        $id=(int)$id;

        $this->db->query("UPDATE ".db_prefix."Products set `image` = '$poster_name' where id=$id");

        return (bool) $this->db->affected_rows();
    }
    /**
    * Delete link on image in db
    *
    * @param mixed $id
    * @return mixed
    */
    function delete_poster($id)
    {
        $id=(int)$id;

        $this->db->select("image");
        $query = $this->db->get_where(db_prefix.'Products', array('id' => $id));

        if($query->num_rows())
        {
            $r=$query->row();
            $this->db->query("UPDATE ".db_prefix."Products set `image` = '' where id=$id");
            return $r->image;
        }

        return false;
    }
    /**
    * Delete image file 
    *
    * @param unknown_type $image
    */
    function delete_poster_file($image)
    {
        if(@file_exists(config_get("product_posters", "path_original").$image))
        {
            @unlink(config_get("product_posters", "path_original").$image);
        }

        if(@file_exists(config_get("product_posters", "path_previews").$image))
        {
            @unlink(config_get("product_posters", "path_previews").$image);
        }

    }

    /**
    * Enter description here...
    *
    * @param mixed $id
    * @return mixed
    */
    function get($id)
    {
        $id=(int)$id;
        $CI =& get_instance();

        $this->db->select("products.id, products.image, products.product_type, product_product_group.product_group_id as gid, products.is_recouring");
        $this->db->select("prices.day, prices.month , prices.month3 , prices.month6 , prices.year , prices.unlimit");
        $this->db->select("discount.discount, discount.discount_type");
        $this->db->select("trial.price as trial_price, trial.period_type as trial_period_type, trial.period_value as trial_period_value");

        $this->db->from(db_prefix.'Products as `products`');
        /**
* Type products
*/
        $this->_where_in_valid_product('`products`');

        $this->db->join(db_prefix.'Trial as `trial`', 'trial.id = products.id', 'LEFT');

        $this->db->join(db_prefix.'Product_discount as `discount`', 'discount.id = products.id', 'LEFT');

        $this->db->join(db_prefix.'Prices as `prices`', 'prices.id = products.id', 'LEFT');

        $this->db->join(db_prefix.'Protection as `protection`', 'products.id = protection.product_id', 'LEFT');

        $this->db->join(db_prefix.'Product_product_group `product_product_group`', 'product_product_group.product_id=products.id');

        $this->db->where("products.id=$id");

        $this->db->limit(1);

        $query=$this->db->get();
       // fbq('Products get');

        if($query->num_rows())
        {
            $CI =& get_instance();
            $CI->load->model("lang_manager_model");


            $t = $CI->lang_manager_model->combine_with_language_data($query->result_array(),PRODUCT_OBJECT_TYPE,array('name'=>'name','descr'=>'descr'),'id',false,false,&$add_params);
           // fb($t,'t');
            return $t;
        }
        else
        {
            return false;
        }
    }
    /**
    * Enter description here...
    *
    * @param mixed $id
    * @return mixed
    */
    function get_product_dirs($id)
    {
        $id=(int)$id;

        $this->db->select("dir_products.dir_id as id, dirs.name");

        $this->db->from(db_prefix.'Dirs as `dirs`');

        $this->db->join(db_prefix.'Dir_products as `dir_products`',
        "dir_products.dir_id = dirs.id AND dir_products.product_id=$id");


        $query=$this->db->get();
        fbq('Products get_product_dirs');


        if($query->num_rows())
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }


    /**
    * Enter description here...
    *
    * @param mixed $id
    * @param string $name
    * @param string $descr
    * @param mixed $group_id
    * @param array $prices
    * @param mixed $recurring
    * @param mixed $discount_type
    * @param mixed $discount_value
    * @param mixed $trial_price
    * @param mixed $trial_duration_type
    * @param mixed $trial_duration_value
    * @param mixed $is_comulative
    * @param array $dirs
    * @return boolean
    */
    function set($id, $name, $descr, $group_id, $prices, $recurring,
    $discount_type, $discount_value, $trial_price, $trial_duration_type,
    $trial_duration_value, $is_comulative, $product_type, $dirs)
    {
        /* checking */

        $name   =mb_substr($name,   0,   255);
        $descr  =mb_substr($descr,  0,   65535);


        $group_id=(int)$group_id;
        $id=(int)$id;

        $prices =   $this->_check_prices($prices);
        if( ! $prices )
        {
            return false;
        }

        /**
* Check valid type of products
*/
        $product_type = $this->_is_valid_type_product($product_type);
        if( !$product_type )
        {
            fb($product_type,'product_type error');
            return false;
        }

        $recurring=(int)$recurring;
        $discount_type=(int)$discount_type;

        if(!is_numeric($discount_value))
        return false;

        if(!is_numeric($trial_price))
        return false;

        $trial_duration_type=_check_param_array($trial_duration_type,  $this->product_trial_periods);

        $trial_duration_value=(int)$trial_duration_value;
        $is_comulative=(int)$is_comulative;

        $dirs=$this->_check_ints($dirs);

        /* inserting */
        $this->db->where(array("id"=>$id));
        /**
* Type products
*/
        $this->_where_in_valid_product(db_prefix.'Products');

        $this->db->update(db_prefix.'Products', array("is_recouring"=>$recurring,  "group_id"=>$group_id, "product_type"=>$product_type));
        fbq('Set Update prod');
        $CI=&get_instance();
        $CI->load->model("lang_manager_model");
        $lang_data=array();
        $lang_data['id']=$id;
        $lang_data['object_type']=PRODUCT_OBJECT_TYPE;
        $lang_data['language_id']=$CI->default_language_id;
        $lang_data['name']=$name;
        $lang_data['descr']=$descr;
        $CI->lang_manager_model->template_set($lang_data);

        $this->db->where(array("id"=>$id));
        $this->db->update(db_prefix.'Product_discount', array("discount"=>$discount_value, "discount_type"=>$discount_type, "cumulative"=>$is_comulative));


        $this->db->where(array("id"=>$id));
        $this->db->update(db_prefix.'Trial'   , array("period_type"=>$trial_duration_type , "period_value"=>$trial_duration_value  , "price"=>$trial_price) );

        $this->db->where(array("id"=>$id));
        $this->db->update(db_prefix.'Prices'  , array("day"=>$prices[0], "month"=>$prices[1], "month3"=>$prices[2], "month6"=>$prices[3], "year"=>$prices[4], "unlimit"=>$prices[5]) );


        $this->db->delete(db_prefix.'Product_product_group'  , array("product_id"=>$id) );

        $this->db->insert(db_prefix.'Product_product_group'  , array("product_group_id"=>$group_id, "product_id"=>$id) );

        /**
* kgg
*
* @TODO rest change type of product may be  only for DEBUG
*/

        switch ($product_type) 
        {
        case PRODUCT_PROTECT:
            //@added by val petruchek - remember current product dirs
            $_current_dirs = $this->get_product_dirs($id);
            //@val petruchek code ends: dirs remembered
            
            $this->db->delete(db_prefix.'Dir_products', array('product_id' => $id) );
            
            foreach($dirs as $di)
            {
                $this->db->insert(db_prefix.'Dir_products',   array("dir_id"=>$di, "product_id"=>$id) );
            }
            
            //@added by val petruchek - fire events
            $this->_update_protection($id,$_current_dirs, $dirs);
            //@val petruchek code ends: events fired

            break;
        case PRODUCT_HOSTED:

            $this->db->delete(db_prefix.'Host_plans_products', array('product_id' => $id) );

            foreach($dirs as $di)
            {
                $this->db->insert(db_prefix.'Host_plans_products',   array("product_id"=>$id, "host_plan_id"=>$di) );
            }
            break;
        }	

        return true;
    }

    /**
    * Enter description here...
    *
    * @param unknown_type $id
    * @param array $previous_dirs
    * @param array $current_dirs
    */
    function _update_protection($id,$previous_dirs, $current_dirs)
    {
        foreach($previous_dirs as $_i => $_dir)
        {//make simple array of their ids - so we can compare arrays with array_diff
            $previous_dirs[$_i] = $_dir['id'];
        }
        //comparing lists of ids: the one from database and another just submitted by user
        $_dirs_added = array_diff($current_dirs, $previous_dirs);
        $_dirs_removed = array_diff($previous_dirs, $current_dirs);
        if (!empty($_dirs_added))
        {//something added
            protection_event("DIRECTORIES_ADDED", null, null, $id, $_dirs_added);
        }
        if (!empty($_dirs_removed))
        {//something removed
            @protection_event("DIRECTORIES_REMOVED", null, null, $id, $_dirs_removed);
        }
        return;
    }
    /**
    * Check whether is the product block or not
    *
    * @param mixed $id
    * @return boolean
    */
    function is_product_blocked($id)
    {
        $id=(int)$id;
        $this->db->select('blocked');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix."Products",array('id'=>$id));
        
        if( $query->num_rows()>0 )
        {
            $product_info = $query->row();
            $blocked = intval($product_info->blocked);
            if( $blocked == 1 )
            {
                return true;
            }
        }

        return false;
    }


    /**
    * Function checks if product is not closed and blocked
    *
    * @param integer $id
    * @return boolean
    *
    * @author Makarenko Sergey
    * @copyright 2008
    */
    function is_product_available_for_buy($id)
    {
        $id=intval($id);
        if ($id <= 0)
        {
            return false;
        }
        $this->db->select('blocked, closed');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix."Products",array('id'=>$id));
        if( $query->num_rows()>0 )
        {
            $product_info = $query->row();
            $blocked = intval($product_info->blocked);
            $closed = intval($product_info->closed);
            if( $blocked==1 || $closed==1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Get last active product
    *
    * @param mixed $user_id
    * @param mixed $cnt
    * @return mixed
    */
    function last_active_product($user_id, $cnt=2)
    {
        $user_id=(int)$user_id;
        $cnt=(int)$cnt;
        $this->db->select("protect.*");

        $this->db->from(db_prefix."Protection protect");
        $this->db->join(db_prefix."Subscriptions subscr", "subscr.id = protect.subscr_id and subscr.status=1");
        $this->db->where("protect.user_id=$user_id");
        $this->db->limit($cnt);
        $this->db->order_by('subscr.cdate DESC');
        $res=$this->db->get();
        fbq('Products last_active_product');

        if($res->num_rows())
        {
            return $res->result_array();
        }

        return false;
    }

    /**
    * Function return type of product
    *
    * @param integer $id
    * @return integer
    *
    * @author Korchinskij_GG
    * @copyright 2009
    */

    function _is_product_typeof($id)
    {
        $id=intval($id);
        if ($id <= 0)
        {
            return false;
        }
        $this->db->select('product_type');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix."Products",array('id'=>$id));
        if( $query->num_rows()>0 )
        {
            $product_info = $query->row();
            return intval($product_info->product_type);
        }
        return false;
    }

    function _where_in_valid_product($table='`products`')
    {
        /**
* For separate type of products for this model
*/

        $aprod  = $this->product_types;

        $this->db->where_in("$table.`product_type`",$aprod);

        return;
    }

    function _is_valid_type_product($product_type = 0)
    {
        $product_type=(int)$product_type;

        if ( $product_type <= 0 )
        {
            return false;
        }

        $aprod  = $this->product_types;
        
        $res = array_search($product_type, $aprod);

        if( $res===FALSE )
        {
            return false;
        }

        return $product_type;
    }

}

?>
