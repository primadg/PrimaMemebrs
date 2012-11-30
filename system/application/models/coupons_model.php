<?php
/**
 * 
 * THIS FILE CONTAINS Coupons_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */

/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH COUPONS AND PRODUCTS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Coupons_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Coupons_model()
    {
        parent::Model();
    }
 

    /**
    *   Add coupon record into Coupons_users
    *
    * @author Drovorubov
    * @param string $code
    * @param integer $product_id
    * @param integer $subscr_id
    * @return bool
    */
    function use_coupon($code, $product_id, $subscr_id)
    {
        //Check entry params
        if( mb_strlen($code) > 254 )
        {
            return false;
        }
        if( !is_int(intval($product_id)) || $product_id <= 0 || !is_int(intval($subscr_id)) || $subscr_id <= 0 )
        {
            return false;
        }
        
        //Get coupon id
        $coupon_id = 0;
        $this->db->select('coupons.id as id');
        $this->db->from(array(db_prefix.'Coupons coupons', db_prefix.'Coupon_groups_products coupon_groups_products'));
        $this->db->where('coupons.coupon_code',$code);
        $this->db->where('coupon_groups_products.product_id',$product_id);
        $this->db->where('coupon_groups_products.coupon_group_id=coupons.coupon_group_id');
        //Execute query
        $query = $this->db->get();
        if ( $query->num_rows() == 1 )
        {
            $row = $query->row();
            $coupon_id = $row->id;
        }
        if( $coupon_id < 1 )
        {
            return false;
        }
        
        //Add Coupon ID to subscriptions
        unset($data);
        $data = array('coupon_id'=>$coupon_id);
        $this->db->where('id',$subscr_id);
        $this->db->update(db_prefix.'Subscriptions', $data);
        if( $this->db->affected_rows() == 1 )
        {
            return true;
        }

        return false;
    }




    /**
    *   Checks if coupon is available and return coupon values
    *
    * @author Drovorubov
    * @param string $code
    * @param integer $product_id
    * @param integer $user_id
    * @return array
    */
    function check_coupon($code, $product_id, $user_id)
    {
        $rv = array(
        "result"=>false,
        "type"=>'',
        "value"=>0
        );
        //Check entry params
        if( mb_strlen($code) > 254 )
        {
            return $rv;
        }
        if( !is_int(intval($product_id)) || $product_id <= 0 || !is_int(intval($user_id)) || $user_id <= 0 )
        {
            return $rv;
        }
        //Get coupon ID & coupon group ID
        $coupon_id = 0;
        $coupon_group_id = 0;
        $this->db->select('coupons.id, coupons.coupon_group_id');
        $this->db->from(array(db_prefix.'Coupons coupons', db_prefix.'Coupon_groups_products coupon_groups_products'));
        $this->db->where('coupons.coupon_code',$code);
        $this->db->where('coupon_groups_products.product_id',$product_id);
        $this->db->where('coupon_groups_products.coupon_group_id=coupons.coupon_group_id');
        //Execute query
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row();
            $coupon_id = $row->id;
            $coupon_group_id = $row->coupon_group_id;
        }
        if( $coupon_id < 1 || $coupon_group_id < 1 )
        {
            return $rv;
        }

        //Get coupon group values
        $group = array();
        $this->db->select('use_per_user, cnt, time_limit, UNIX_TIMESTAMP(start_time) start_time, UNIX_TIMESTAMP(end_time) end_time, discount_percent, discount_value, locked, available_use, UNIX_TIMESTAMP(CURDATE()) as today');
        $this->db->from(array(db_prefix.'Coupon_group coupon_group'));
        $this->db->where('coupon_group.id',$coupon_group_id);
        //Execute query
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $tmp = $query->result_array();
            $group = $tmp[0];
        }
        if( $group['cnt'] < 1 || $group['locked'] != 0 || $group['available_use'] < 1 )
        {
            return $rv;
        }

        //Check date
        if( $group['time_limit'] > 0 )
        {
            if ( $group['today'] < $group['start_time'] || $group['today'] > $group['end_time'] )
            {
                return $rv;
            }
        }
        //Count used coupons of the group by all users
        $group_used_coupons_cnt = 0;
        $this->db->select('count(*) as rows_count');
        $this->db->from(array(db_prefix.'Subscriptions subscriptions', db_prefix.'Coupons coupons'));
        $this->db->where('subscriptions.coupon_id=coupons.id');
        $this->db->where('subscriptions.coupon_id',$coupon_id);
        $this->db->where('coupons.coupon_group_id',$coupon_group_id);
        //Execute query
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $group_used_coupons_cnt = $row->rows_count;
        }
        //Break if all users have used coupon of the group more than available for group
        if( $group_used_coupons_cnt >= $group['available_use'])
        {
            return $rv;
        }
        //Count used coupon by user
        $used_coupons_cnt = 0;
        $this->db->select('count(*) as rows_count');
        $this->db->from(array(db_prefix.'Subscriptions subscriptions',db_prefix.'Coupons coupons',db_prefix.'Protection protection'));
        $this->db->where('protection.subscr_id = subscriptions.id');
        $this->db->where('subscriptions.coupon_id=coupons.id');
        $this->db->where('protection.user_id',$user_id);
        $this->db->where('subscriptions.coupon_id',$coupon_id);
        $this->db->where('coupons.coupon_group_id',$coupon_group_id);
        //Execute query
        $query = $this->db->get();
        if ($query->num_rows() > 0)  
        {
            $row = $query->row();
            $used_coupons_cnt = $row->rows_count;
        }
        /* Break if count of used coupons by the one user more than the value of 
         * use per user value in this group */
        if( $used_coupons_cnt >= $group['use_per_user'] )
        {
            return $rv;
        }
        //Prepare coupon values
        if( intval($group['discount_percent']) > 0 )
        {
            $rv['type'] = "1";
            $rv['value'] = $group['discount_percent'];

        }
        else if( floatval($group['discount_value']) > 0 )
        {
            $rv['type'] = "2";
            $rv['value'] = $group['discount_value'];
        }
        else
        {
            return $rv;
        }

        $rv['result'] = true;
        return $rv;
    }

    
    /**
    * Gets Coupons Statistic
    *
    * @author Drovorubov
    * editor onagr
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param array $search
    * @return array
    */
    function statistic($page,$count,$sort_by,$sort_how,$search)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );
        
        //Get coupons list
        $this->db->select('(coupon_group.discount_percent + coupon_group.discount_value) as composed_discount');
        $this->db->select('users.id as user_id, users.login as user_name');
        $this->db->select('coupons.coupon_code');
        $this->db->select('coupon_group.id as group_id, coupon_group.discount_percent, coupon_group.discount_value');
        $this->db->select('coupon_group.start_time');
        $this->db->select('coupon_group.end_time');
        $this->db->select('subscriptions.cdate');
        //select joined tables
        $this->db->select('transactions.summ, transactions.completed');
        $this->db->select('products.id as product_id');
        $this->db->from(array(db_prefix.'Subscriptions subscriptions', db_prefix.'Users users', db_prefix.'Coupons coupons', db_prefix.'Coupon_group coupon_group', db_prefix.'Protection protection'));
        $this->db->where('subscriptions.coupon_id != 0');
        $this->db->where('coupons.id = subscriptions.coupon_id');
        $this->db->where('coupon_group.id = coupons.coupon_group_id');
        $this->db->where('protection.subscr_id = subscriptions.id');
        $this->db->where('users.id=protection.user_id');
        //Join tables
        $this->db->join(db_prefix.'Transactions transactions','transactions.subscription_id = subscriptions.id 
AND transactions.completed = 1 
AND transactions.summ > 0','LEFT');
        $this->db->join(db_prefix.'Products products','products.id=protection.product_id','LEFT');
        //Set search params for getting data
        $this->_add_search2query_stat($search);
        $query = $this->db->get();
        
        
        $sort_param=array();
        $sort_param['code']='coupon_code';
        $sort_param['member']='user_name';
        $sort_param['period']='start_time';
        $sort_param['discount']='composed_discount';        
        $sort_param['change_time']='cdate';
        $sort_param['amount']='summ';
        $sort_param['paid']='completed';
        $sort_param['product']='product_name';
        $sort_param['discount']='composed_discount';
        $sort_param['discount']='composed_discount';
        
        $sort_by=array_key_exists($sort_by,$sort_param) ? $sort_param[$sort_by] : $sort_param['code'];
        
        $t=$query->result_array();
        $pager=pager_params($page,$count,count($t));
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name'),'product_id',array('col'=>$sort_by,'order'=>$sort_how,'limit'=>$pager['limit'],'offset'=>$pager['offset']),false,&$add_params);
        
        $rv['count']=count($t);
        $rv['total'] = $pager['count'];
        $rv['per_page'] = $pager['limit'];
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $t;
            $rv['result'] = true;
        }
        return $rv;
        
    }

    /**
    * Adds search params to this object SQL query as WHERE items
    *
    * @param array $data
    */
    function _add_search2query_stat($data)
    {
        if( is_array($data) && count($data) > 0 )
        {
            //Add where like to query for key
            if( isset($data['search_key']) && isset($data['search_val']) )
            {
                $key = input_text($data['search_key']);
                $val = input_text($data['search_val']);
                switch($key)
                {
                    case 'code':
                    {
                        if($val != '')
                        {
                            $this->db->like('coupons.coupon_code', $val);
                        }
                        break;
                    }
                }
            }
            //Add where like to query for date period
            if( isset($data['date_from']) && !empty($data['date_from']) )
            {
                $date_from = input_text($data['date_from']);
                $date_from = convert_date($date_from);
                $date_to = input_text($data['date_to']);
                $this->db->where('subscriptions.cdate >= ', $date_from);
                if( isset($data['date_to']) && !empty($data['date_to']) )
                {
                    $date_to = convert_date($date_to);
                    $this->db->where('subscriptions.cdate <= ', $date_to);
                }
            }
        }
    }


    /**
    * Converts param for ORDER in SELECT
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_stat_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'code':
            {
                $rv = 'coupons.coupon_code ' . $ord_type;
                break;
            }
            case 'member':
            {
                $rv = 'user_name ' . $ord_type;
                break;
            }
            case 'period':
            {
                $rv = 'coupon_group.start_time ' .$ord_type. ' , coupon_group.end_time ' . $ord_type;
                break;
            }
            case 'discount':
            {
                $this->db->select('(coupon_group.discount_percent + coupon_group.discount_value) as composed_discount');
                $rv = 'composed_discount ' . $ord_type;
                break;
            }
            case 'change_time':
            {
                $rv = 'subscriptions.cdate ' . $ord_type;
                break;
            }
            case 'amount':
            {
                $rv = 'transactions.summ ' . $ord_type;
                break;
            }
            case 'paid':
            {
                $rv = 'transactions.completed ' . $ord_type;
                break;
            }
            case 'product':
            {
                $rv = 'ld_product.name ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'coupons.coupon_code ' . $ord_type;
            }
        }

        return $rv;
    }





    /**
    * Gets coupons group name
    *
    * @author Drovorubov
    * @param integer $id
    * @return string $rv
    */
    function get_group_name($id)
    {
        $rv = '';
        if($id < 1)
        {
            return $rv;
        }
        $this->db->select('name');
        $this->db->from(db_prefix.'Language_data');
        $this->db->where('object_id',$id);
        $this->db->where('object_type = 5');
        $this->db->where('language_id = 1');
        $query = $this->db->get();
        $recs  =  $query->num_rows() ;
        if( $recs > 0 )
        {
            $tmp = $query->result_array();
            $rv = $tmp[0]['name'];
        }
        return $rv;
    }

    /**
    * Gets coupons group info
    *
    * @author Drovorubov
    * @param integer $id
    * @return array
    */
    function get($id)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        $id = intval($id);
        if($id < 1)
        {
            return $rv;
        }
        //Select data from Coupon_group table
        $this->db->select('id, use_per_user, cnt, time_limit, discount_percent, discount_value, code_length, locked, available_use');
        $this->db->select("start_time as start_date");
        $this->db->select("end_time as end_date");
        $this->db->from(db_prefix.'Coupon_group coupon_group');
        $this->db->where('coupon_group.id',$id);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $tmp = $query->result_array();
            $rv['items'] = $tmp[0];
            $rv['result'] = true;
            //Get Language data
            $rv['items']['name'] = '';
            $rv['items']['descr'] = '';
            $this->db->select('name, descr');
            $this->db->from(db_prefix.'Language_data');
            $this->db->where('object_id',$id);
            $this->db->where('object_type = 5');
            $this->db->where('language_id = 1');
            $query = $this->db->get();
            $recs  =  $query->num_rows() ;
            if ( $recs > 0 )
            {
                $tmp = $query->result_array();
                $rv['items']['name'] = $tmp[0]['name'];
                $rv['items']['descr'] = $tmp[0]['descr'];
            }
            //Get products
            $rv['items']['products'] = array();
            $this->db->select('product_id');
            $this->db->from(db_prefix.'Coupon_groups_products');
            $this->db->where('coupon_group_id',$id);
            $query = $this->db->get();
            $recs  =  $query->num_rows() ;
            if ( $recs > 0 )
            {
                $tmp = $query->result_array();
                foreach($tmp as $key=>$val)
                {
                   $rv['items']['products'][] = $val['product_id'];
                }
            }
        }
        return $rv;
    }


    /**
    * Deletes Coupon Group elements by id
    *
    * @author Drovorubov
    * @param  integer $id
    * @return bool
    */
    function remove($id)
    {
        if( $id < 1 )
        {
            return false;
        }

        $query = $this->db->get_where(db_prefix.'Coupon_group', array('id' => $id));
        if( $query->num_rows() < 1 )
        {
            return false;
        }
        // Delete Coupon Group elements from Language_data
        $this->db->where('object_id',$id);
        $this->db->where('language_id = 1');
        $this->db->where('object_type = 5');
        $this->db->delete(db_prefix.'Language_data');
        // Delete Coupon Group elements from Coupons
        $this->db->where('coupon_group_id',$id);
        $this->db->delete(db_prefix.'Coupons');
        // Delete Coupon Group elements from Coupons_group_products
        $this->db->where('coupon_group_id',$id);
        $this->db->delete(db_prefix.'Coupon_groups_products');
        // Delete Coupon Group element from Coupon_group
        $this->db->where('id',$id);
        $this->db->delete(db_prefix.'Coupon_group');

        return true;
    }


    /**
    * Adds coupon group and coupons into DB
    *
    * @author Drovorubov
    * @param array $param
    * @return bool
    */
    function add($param)
    {
        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Check products
        if(count($param['products']) < 1)
        {
            return false;
        }
        //Prepare params for inserting into DB
        $prep_data = $this->_prepare_coupons_data($param);
        //Insert data into Coupon group table
        $data = array();
        $data = $prep_data['coupon_group'];
        $this->db->insert(db_prefix.'Coupon_group', $data);
        $group_id = $this->db->insert_id();
        if($this->db->affected_rows() != 1 && $group_id < 1)
        {
            return false;
        }
        //Insert data into Language_data table
        $data = array();
        $data = $prep_data['language_data'];
        $data['language_id'] = '1';
        $data['object_type'] = '5';
        $data['object_id'] = $group_id;
        $this->db->insert(db_prefix.'Language_data', $data);
        if($this->db->affected_rows() != 1)
        {
            $this->db->delete(db_prefix.'Coupon_group', array('id' => $group_id));
            return false;
        }
        //Insert data to Coupons table
        $code_length = $prep_data['coupon_group']['code_length'];
        for($i=0; $i<$prep_data['coupon_group']['cnt']; $i++)
        {
            //Create coupon code
            $j=0;
            do // DO WHILE START____
            {
                $randval = md5(mktime().mt_rand());
                //$randval = md5(uniqid());
                $coupon_code = substr($randval,$j,$code_length);
                $coupon_code = strtoupper($coupon_code);
                $query = $this->db->get_where(db_prefix.'Coupons', array('coupon_code' => $coupon_code), 1, 0);
                if( $query->num_rows() > 0 )
                {
                    $j++;
                }
                else
                {
                    $j = 11;
                }
            } while( $j < 10 ); //DO WHILE _____END
            //Insert coupon code
            $this->db->insert(db_prefix.'Coupons', array('coupon_code'=>$coupon_code,'coupon_group_id'=>$group_id));
        }
        //Insert data into Coupon_groups_products
        $this->_set_products($group_id,$param['products']);

        return true;
    }



    /**
    * Updates coupon group info in DB
    *
    * @author Drovorubov
    * @param integer $id
    * @param array $param
    * @return bool
    */
    function edit($id,$param)
    {
        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Check id
        if( $id < 1 )
        {
            return false;
        }
        //Check products
        if( count($param['products']) < 1 )
        {
            return false;
        }
        //Prepare params for inserting into DB
        $prep_data = $this->_prepare_coupons_data($param);
        //Update data in Coupon group table
        $data = array();
        $data = $prep_data['coupon_group'];
        if( count($data) > 0 )
        {
            $this->db->where('id', $id);
            $this->db->update(db_prefix.'Coupon_group', $data);
        }
        //Update data in Language_data table
        $data = array();
        $data = $prep_data['language_data'];
        if( count($data) > 0 )
        {
            $this->db->where('object_id', $id);
            $this->db->where('language_id = 1');
            $this->db->where('object_type = 5');
            $this->db->update(db_prefix.'Language_data', $data);
        }
        //Update data in Coupon_groups_products
        $this->db->delete(db_prefix.'Coupon_groups_products', array('coupon_group_id' => $id));
        $this->_set_products($id,$param['products']);

        return true;
    }


    /**
    * Getting coupons groups list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function coupons_list($page,$count,$sort_by,$sort_how)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );
        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $rv;
        }
        $rv['per_page'] = $count;
        //Set order type
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';
        //Set order before selection
        $sort_by = $this->_get_coupons_group_order($sort_by,$sort_how);

        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(db_prefix.'Coupon_group');
        //Execute query
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
        //Get users list
        $this->db->select('id, cnt, locked, available_use');
        $this->db->select('discount_percent, discount_value');
        $this->db->select('start_time');
        $this->db->select('end_time');
        $this->db->from(db_prefix.'Coupon_group coupon_group');
        $this->db->limit($count,$row_start);
        $this->db->order_by($sort_by);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $query->result_array();
        }
        $rv['result'] = true;
        return $rv;
    }



    /**
    * Getting coupons list of the group
    *
    * @author Drovorubov
    * @param integer $group_id
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function get_coupons($group_id,$sort_by,$sort_how)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );
        if( $group_id < 1 )
        {
            return $rv;
        }

        //Set order type
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';
        //Set order before selection
        $sort_by = $this->_get_coupons_order($sort_by,$sort_how);
        //Get users list
        $this->db->select('locked, available_use');
        $this->db->select('coupon_code, coupons.id');
        $this->db->from(array(db_prefix.'Coupons coupons', db_prefix.'Coupon_group coupon_group'));
        $this->db->where('coupons.coupon_group_id', $group_id);
        $this->db->where('coupons.coupon_group_id = coupon_group.id');
        $this->db->order_by($sort_by);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $query->result_array();
        }
        $rv['result'] = true;
        return $rv;
    }


    /**
    * Deletes Coupon
    *
    * @author Drovorubov
    * @param  integer $id
    * @return bool
    */
    function delete_coup($id)
    {
        if( $id < 1 )
        {
            return false;
        }

        $query = $this->db->get_where(db_prefix.'Coupons', array('id' => $id));
        if( $query->num_rows() < 1 )
        {
            return false;
        }

        // Get coupon group id
        $this->db->select('coupon_group_id');
        $this->db->from(db_prefix.'Coupons');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $recs  =  $query->num_rows() ;
        if( $recs > 0 )
        {
            $tmp = $query->result_array();
            $group_id = $tmp[0]['coupon_group_id'];
            // Change numbers of coupons in the group
            $this->db->simple_query("UPDATE " . db_prefix."Coupon_group" . " SET cnt = cnt - 1 WHERE id = " . $group_id);
            // Delete Coupon from Coupons
            $this->db->where('id',$id);
            $this->db->delete(db_prefix.'Coupons');
        }

        return true;
    }



    /**
    * Sets products for coupons group
    *
    * @author Drovorubov
    * @param integer $group_id
    * @param array $products
    * @return bool
    */
    function _set_products($group_id,$products)
    {
        if( count($products) < 1 || $group_id < 1 )
        {
            return false;
        }

        //Insert products
        foreach($products as $prod_id)
        {
            $this->db->select('id');
            $this->db->from(db_prefix.'Products');
            $this->db->where('id', $prod_id);
            $query = $this->db->get();
            if( $query->num_rows() > 0 )
            {
                $this->db->insert(db_prefix.'Coupon_groups_products', array('product_id'=>$prod_id,'coupon_group_id'=>$group_id));
            }
        }
        return true;
    }


    /**
    * Converts param for ORDER in SELECT
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_coupons_group_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'begin_date':
            {
                $rv = 'coupon_group.start_time ' . $ord_type;
                break;
            }
            case 'expire_date':
            {
                $rv = 'coupon_group.end_time ' . $ord_type;
                break;
            }
            case 'coupons_count':
            {
                $rv = 'coupon_group.cnt ' . $ord_type;
                break;
            }
            case 'disabled':
            {
                $rv = 'coupon_group.locked ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'coupon_group.start_time ' . $ord_type;
            }
        }
        return $rv;
    }




    /**
    * Counts coupons which are used by a member
    *
    * @author Drovorubov
    * @param integer $group_id
    * @param integer $coupon_id
    * @return integer
    */
    function get_coupons_used($group_id,$coupon_id=0)
    {
        $rv = 0;
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Coupons coupons', db_prefix.'Subscriptions subscriptions'));
        if( !empty($coupon_id) )
        {
            $this->db->where('coupons.id',$coupon_id);
        }
        else
        {
            $this->db->where('coupons.coupon_group_id',$group_id);
        }
        $this->db->where('coupons.id = subscriptions.coupon_id');
        //Execute query
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $rv = $row->all_rows;
        }
        return $rv;
    }


    /**
    * Prepares data for DB inserting and updating
    *
    * @author Drovorubov
    * @param array $param
    * @return array $data
    */
    function _prepare_coupons_data($param)
    {
        $data = array();
        $data2 = array();
        foreach($param as $key=>$val)
        {
            if( $key == 'name' )
            {
                $val = input_text($val);
                if( mb_strlen($val) > 254 )
                {
                    $val = mb_substr($val,0,253);
                }
                $data2['name'] = $val;
            }
            else if( $key == 'descr' )
            {
                $val = input_text($val);
                if( mb_strlen($val) > 65534 )
                {
                    $val = mb_substr($val,0,65533);
                }
                $data2['descr'] = $val;
            }
            else if( $key == 'coupons_count' )
            {
                $val = intval($val);
                $data['cnt'] = ($val < 1) ? '1' : $val;
            }
            else if( $key == 'per_user_use' )
            {
                $val = intval($val);
                $data['use_per_user'] = ($val < 1) ? '1' : $val;
            }
            else if( $key == 'coupon_use' )
            {
                $val = intval($val);
                $data['available_use'] = ($val < 1) ? '1' : $val;
            }
            else if( $key == 'code_len' )
            {
                $val = intval($val);
                $data['code_length'] = ($val < 1) ? '1' : $val;
            }
            else if( $key == 'discount_type' )
            {
                $val = input_text($val);
                if( $val == 'prc' )
                {

                    $data['discount_percent'] = intval($param['discount_val']);
                    $data['discount_value'] = 0;
                }
                else
                {
                    $data['discount_percent'] = 0;
                    $data['discount_value'] = floatval($param['discount_val']);
                }
            }
            else if( $key == 'donot_limit_dates' )
            {
                $val = intval($val);
                $data['time_limit'] = ($val == 1) ? '0' : '1';
                if( $data['time_limit'] == 1 )
                {
                    $start_date = input_text($param['start_date']);
                    $end_date = input_text($param['end_date']);
                    if( validate_date($start_date) && validate_date($end_date) && compare_dates($start_date,$end_date))
                    {
                        $data['start_time'] = convert_date($start_date);
                        $data['end_time'] = convert_date($end_date);
                    }
                    else{ $data['time_limit'] = 1; }
                }
                else
                {
                    $data['end_time'] = '';
                }
            }
            else if( $key == 'locked' )
            {
                $data['locked'] = (intval($val) == 1) ? '1' : '0';
            }
        }
        $rv['language_data'] = $data2;
        $rv['coupon_group'] = $data;
        return $rv;
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
    * Compares two dates
    *
    * @author Drovorubov
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

        return true;
    }


    /**
    * Converts date to DB format
    *
    * @param string $date
    * @return string $date
    */
    function _convert_date_format($date)
    {
        if( !empty($date) )
        {
            $date = preg_replace("/(\d+)-(\d+)-(\d+)/", "$3-$2-$1", $date);
        }
        return $date;
    }


    /**
    * Converts param for ORDER in SELECT of Coupons list
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_coupons_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'code':
            {
                $rv = 'coupons.coupon_code ' . $ord_type;
                break;
            }
            case 'disabled':
            {
                $rv = 'coupon_group.locked ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'coupons.coupon_code ' . $ord_type;
            }
        }
        return $rv;
    }
}
?>
