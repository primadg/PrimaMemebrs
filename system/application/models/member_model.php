<?php
/**
 * 
 * THIS FILE CONTAINS Member_model CLASS
 *  
 * @package Needsecure
 * @author Peter Yaroshenko
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MEMBER
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Member_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Member_model()
    {
        parent::Model();
        $this->product_types=$this->get_product_types();
    }
 
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
    
    
    /**
    * Gets member's active payments count
    *
    * @author Drovorubov
    * @param integer @uid (User ID)
    * @return mixed integer/false
    */
    function subscribe_summary_active($uid)
    {
        $uid = intval($uid);
        if( $uid<=0 )
        {
            return false;
        }

        $this->db->select('count(subscriptions.id) as active_num');
        $this->db->from(array(db_prefix.'Subscriptions as subscriptions',db_prefix.'Protection as protection'));        
        $this->db->where('protection.user_id',$uid);
        $this->db->where('protection.subscr_id = subscriptions.id');        
        $this->db->where('subscriptions.status = 1');              
        $query = $this->db->get();
       
        if( $query->num_rows() > 0 )
        {
            $rv = $query->result_array();
            return $rv[0]['active_num'];
        }

        return false;        
    }    
    
    
    
    
    /**
    * Gets member's payments count and total sum
    *
    * @author Drovorubov
    * @param integer @uid (User ID)
    * @return mixed array/false
    */
    function subscribe_summary_all($uid)
    {
        $uid = intval($uid);
        if( $uid<=0 )
        {
            return false;
        }

        $this->db->select('sum(subscriptions.regular_price) as total, count(subscriptions.id) as num');
        $this->db->from(array(db_prefix.'Subscriptions as subscriptions',db_prefix.'Protection as protection'));        
        $this->db->where('protection.user_id',$uid);
        $this->db->where('protection.subscr_id = subscriptions.id');        
        $query = $this->db->get();
       
        if( $query->num_rows() > 0 )
        {
            $rv = $query->result_array();
            return $rv[0];
        }

        return false;        
    }
    
    /**
    * Check and update expiration term
    * @author onagr
    * @return void
    */
    function Check_and_update_expiration_term()
    {
        $this->db->select('id');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id=account_status.user_id');
        $this->db->where('account_status.expired',0);
        $this->db->where('account_status.expire!=0');
        $this->db->where('account_status.expire <= NOW()');
        $query = $this->db->get();
        $users=$query->result_array();
        
        foreach($users as $user)
        {
            protection_event("USER_SUSPENDED",$user['id']);
            $this->db->where('user_id',$user['id']);
            $this->db->update(db_prefix.'Account_status',array('expired'=>1));
            $result=send_system_email_to_user($user['id'],'user_account_expire');
        }
        
        /* echo "<pre>";
        print_r($users);
        echo "</pre>";  */       
    }
    
    /**
    * Getting expired members list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function expired_list($page,$count,$sort_by,$sort_how)
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
        $sort_by = $this->_get_member_order($sort_by,$sort_how);

        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        $this->db->where("account_status.expired = 1");
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
        $this->db->select('id, login, name, last_name');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id=account_status.user_id');
        $this->db->where("account_status.expired = 1");
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
    * Check if product exists, is not blocked and not closed 
    * Return true or false
    *
    * @author Drovorubov
    * @param integer $product_id
    * @return bool
    */
    function is_product_available($product_id)
    {
        $product_id = intval($product_id);
        if( $product_id<=0 )
        {
            return false;
        }

        $this->db->select('id');
        $this->db->from(db_prefix.'Products as products');        
        $this->db->where('products.id',$product_id);
        $this->db->where('products.blocked = 0');        
        $this->db->where('products.closed = 0');                
        $this->db->limit(1);
        $query = $this->db->get();

        if( $query->num_rows() > 0 )
        {
            return true;
        }

        return false;
    }    
    
    
    
    /**
    * Gets the list of member's transactions
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $sid (subscription id)
    * @return array
    */
    function transaction_list($page,$count,$sort_by,$sort_how,$sid)
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
        
        $sort_param=array();
        $sort_param['by_trans_info']='info'; 
        $sort_param['by_trans_paysys']='pay_system_id'; 
        $sort_param['by_trans_amount']='summ';
        $sort_param['by_trans_date']='date';
        $sort_param['by_trans_id']='id';
        
        $rv['sort_by']=array_key_exists($sort_by,$sort_param) ? $sort_by : 'by_trans_date';
        $rv['sort_how']=$sort_how;
        
        $sort_by=$sort_param[$rv['sort_by']];
        //Get list
        $this->db->select('date, id, summ,  pay_system_id, subscription_id as subscr_id');
        $this->db->from(array(db_prefix.'Transactions'));
        $this->db->where('subscription_id',$sid);        
        
        $page_res=page_and_sort(array('pager'=>array('0'=>$sort_by,'1'=>$sort_how,'2'=>$count,'3'=>$page)),array('date', 'info', 'pay_system_id', 'summ', 'id'));
        
        $rv['per_page']=$page_res['pagers']['params']['limit'];
        $rv['total'] = $page_res['pagers']['params']['count'];
        $rv['items'] = $page_res['query']->result_array();
        
        $rv['result'] = true;
        return $rv;
    }
    
    
    
    
    /**
    * Gets the list of member's transactions
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $sid (subscription id)
    * @return array
    */
    
    /**
    * Gets member's transaction info
    *
    * @author Drovorubov
    * @param integer $id
    * @return mixed array/false
    */
    function get_transaction_info($id)
    {
        $id = intval($id);
        if($id < 1)
        {
            return false;
        }

        $this->db->select('id, date, summ, info, pay_system_id, subscription_id');
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
    * Gets the list of member's subscriptions and payments
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $uid
    * @return array
    */
    
    function susbcribe_list($page,$count,$sort_by,$sort_how,$uid)
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

        //Prepare SQL query
        $this->db->select('products.id as product_id, subscriptions.cdate, subscriptions.id, subscriptions.type, subscriptions.status, subscriptions.regular_price');
        $this->db->from(array(db_prefix.'Protection protection',db_prefix.'Subscriptions subscriptions'));
        $this->db->where('protection.user_id',$uid);
        $this->db->where('subscriptions.id=protection.subscr_id');
        $this->db->join(db_prefix.'Products products', 'products.id=protection.product_id', 'left');
        $query = $this->db->get();
        
        $sort_param=array();
        $sort_param['by_date']='cdate';
        $sort_param['by_product']='product_name';
        $sort_param['by_type']='type';
        $sort_param['by_price']='regular_price';
        
        $rv['sort_by']=array_key_exists($sort_by,$sort_param) ? $sort_by : 'by_date';
        $rv['sort_how']=$sort_how;
        
        $sort_by=$sort_param[$rv['sort_by']];
        
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
    * Gets all members count
    *
    * @author Drovorubov
    * @param integer $month
    * @param integer $year
    * @return mixed integer/false
    */
    function get_members_count($month,$year)
    {
        $month = intval($month);
        if( $month < 0 || $month > 12 )
        {
            return $rv;
        }
        $year = intval($year);
        if( $year < 1 )
        {
            return $rv;
        }
        $this->db->select("count(*) as num");
        $this->db->from(array(db_prefix.'Account_status  account_status'));
        $this->db->where("YEAR(account_status.added) <= ",$year);
        if($month > 0)
        {
            $this->db->where("MONTH(account_status.added) <= ",$month);
        }
        $query = $this->db->get();
//print $this->db->last_query(); die;
        if ( $query->num_rows() > 0 )
        {
            $row = $query->result_array();
            return $row[0]['num'];
        }
        return false;
    }

    /**
    * Gets new members count statistics array
    *
    * @author Drovorubov
    * @param integer $month
    * @param integer $year
    * @return array
    */
    function get_new_members($month,$year)
    {
        $rv = array();
        $month = intval($month);
        if( $month < 0 || $month > 12 )
        {
            return $rv;
        }
        $year = intval($year);
        if( $year < 1 )
        {
            return $rv;
        }
        //Prepare sql query
        $this->db->select("count(*) as num");
        if($month > 0)
        {
            $this->db->select("DAY(account_status.added) as day");
            $this->db->where("YEAR(account_status.added)",$year);
            $this->db->where("MONTH(account_status.added)",$month);
            $this->db->group_by("DAY(account_status.added)");
        }
        else
        {
            $this->db->select("MONTH(account_status.added) as month");
            $this->db->where("YEAR(account_status.added)",$year);
            $this->db->group_by("MONTH(account_status.added)");
        }
        $this->db->from(array(db_prefix.'Account_status  account_status'));
        //Execute query
        $query = $this->db->get();
//print $this->db->last_query(); die;
        if ( $query->num_rows() > 0 )
        {
            $rv = $query->result_array();
        }
        return $rv;
    }






    /**
    * Gets additional fields
    *
    * @author Drovorubov
    * @return mixed array/false
    */
    function get_add_fields_list()
    {
        $rv = array();

        //Get additional fields list
        $this->db->select('id');
        $this->db->from(db_prefix.'Add_fields');
        $this->db->order_by('taborder');
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv = $query->result_array();
            return $rv;
        }
        return false;
    }



    /**
    * Gets user additional fields by user id
    *
    * @author Drovorubov
    * @param integer $uid
    * @return mixed
    */
    function get_user_add_fields($uid)
    {
        $rv = array();

        //Get additional fields list
        $this->db->select('field_id, field_value');
        $this->db->from(db_prefix.'User_add_fields');
        $this->db->where('user_id',$uid);
        $this->db->order_by('field_id');
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv = $query->result_array();
            return $rv;
        }
        return false;
    }



    /**
    * Getting email details from DB
    *
    * @author Drovorubov
    * @param integer $email_id
    * @return array
    */
    function history_get($email_id)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        $email_id = intval($email_id);
        if($email_id < 1)
        {
            return $rv;
        }

        $this->db->select('users.email as email_to');
        $this->db->select('email_to_send.email_from as email_from');
        $this->db->select('language_data.name as subject');
        $this->db->select('language_data.descr as message');
        $this->db->from(array(db_prefix.'Users users', db_prefix.'Email_to_send email_to_send', db_prefix.'Email_queue email_queue', db_prefix.'Language_data language_data'));
        $this->db->where('email_to_send.id',$email_id);
        $this->db->where('email_queue.email_id = email_to_send.id');
        $this->db->where('email_queue.status = 1');
        $this->db->where('email_queue.user_id = users.id');
        $this->db->where('email_to_send.id = language_data.object_id');
        $this->db->where('language_data.language_id = 1');
        $this->db->where('language_data.object_type = 9');
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $tmp = $query->result_array();
            $rv['items'] = $tmp[0];
            $rv['result'] = true;
        }
        return $rv;
    }






    /**
    * Deletes member's email from a history list
    *
    * @author Drovorubov
    * @param  integer $id
    * @return bool
    */
    function rem_history($id)
    {
        if( intval($id) < 1 )
        {
            return false;
        }

        $query = $this->db->get_where(db_prefix.'Email_to_send', array('id' => $id));
        if( $query->num_rows() < 1 )
        {
            return false;
        }
        // Delete email info from Language_data
        $this->db->where('object_id',$id);
        $this->db->where('language_id = 1');
        $this->db->where('object_type = 9');
        $this->db->delete(db_prefix.'Language_data');
        // Delete email info from Email_queue
        $this->db->where('email_id',$id);
        $this->db->delete(db_prefix.'Email_queue');
        // Delete email info from Email_to_send
        $this->db->where('id',$id);
        $this->db->delete(db_prefix.'Email_to_send');
        if( $this->db->affected_rows() > 0 )
        {
            return true;
        }
        return false;
    }



    /**
    * Getting user's email history list
    *
    * @param mixed $page
    * @param mixed $count
    * @param string $sort_by
    * @param string $sort_how
    * @param integer $user_id
    * 
    * @author Drovorubov
    * 
    * @return array
    */
    function history_list($page,$count,$sort_by,$sort_how,$user_id)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if($user_id < 1)
        {
            return $rv;
        }

        if( intval($page) <= 0 || intval($count) <= 0 )
        {
            return $rv;
        }

        $rv['per_page'] = $count;

        //Set order type
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';
        //Set order before selection
        $sort_by = $this->_get_email_history_order($sort_by,$sort_how);
        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Email_to_send email_to_send', db_prefix.'Email_queue email_queue', db_prefix.'Language_data language_data'));
        $this->db->where('email_queue.user_id',$user_id);
        $this->db->where('email_queue.status = 1');
        $this->db->where('email_queue.email_id = email_to_send.id');
        $this->db->where('email_to_send.id = language_data.object_id');
        $this->db->where('language_data.language_id = 1');
        $this->db->where('language_data.object_type = 9');
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
        //Get reasons list
        $this->db->select('email_to_send.id');
        $this->db->select('email_to_send.email_from as email');
        $this->db->select('email_queue.date as date');
        $this->db->select('language_data.name as subject');
        $this->db->from(array(db_prefix.'Email_to_send email_to_send', db_prefix.'Email_queue email_queue', db_prefix.'Language_data language_data'));
        $this->db->where('email_queue.user_id',$user_id);
        $this->db->where('email_queue.status = 1');
        $this->db->where('email_queue.email_id = email_to_send.id');
        $this->db->where('email_to_send.id = language_data.object_id');
        $this->db->where('language_data.language_id = 1');
        $this->db->where('language_data.object_type = 9');
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
	 * Generate a list of user logs
	 *
	 * @param integer $page
	 * @param integer $count
	 * @param mixed $sort_by
	 * @param unknown_type $sort_how
	 * @param integer $user_id
	 * @param array $search
	 * @return array
	 */
    function member_access_log_list($page,$count,$sort_by,$sort_how,$user_id,$search)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );
        
        //Get list
        $this->db->select('product_id, ip, http_referer, url');
        $this->db->select('user_logs.time as time');
        $this->db->from(array(db_prefix.'User_logs user_logs'));
        $this->db->where('user_logs.user_id',$user_id);
        //Join table Language_data
        //Set search params
        $this->_add_search2accesslog_query($search);
        $query = $this->db->get();
        $sort_param=array();
        $sort_param['by_date']='time';
        $sort_param['by_url']='url';
        $sort_param['by_ip']='ip';
        $sort_param['by_http_referer']='http_referer';        
        $sort_param['by_product']='name';
        
        $rv['sort_by']=array_key_exists($sort_by,$sort_param) ? $sort_by : 'by_date';
        $rv['sort_how']=$sort_how;
        
        $sort_by=$sort_param[$rv['sort_by']];
        
        $t=$query->result_array();
        $pager=pager_params($page,$count,count($t));
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'name'),'product_id',array('col'=>$sort_by,'order'=>$sort_how,'limit'=>$pager['limit'],'offset'=>$pager['offset']),false,&$add_params);
        
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
    * Update account info in DB
    *
    * @author Drovorubov
    * @param integer $id
    * @param array $param
    * @return bool
    */
    function set_member($id,$param)
    {
        if( $id < 1 || !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Check if user exists
        $query = $this->db->get_where(db_prefix.'Users', array('id' => $id));
        $info=$query->result_array(); 
        if( $query->num_rows() < 1 )
        {
            return false;
        }
        
        if(isset($param['groups']))
        {
            $CI=&get_instance();
            $CI->load->model("member_group_model");
            $CI->member_group_model->set_member_groups($id,$param['groups']);
        }        
        //Prepare params before updating
        $data = $this->_prepare_member_params($param);
        //Update Users table
        if(count($data['users']) > 0)
        {
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_member_email_authentication')===true && isset($data['users']['email']))
            {
                if($data['users']['email']!=$info[0]['email'])
                {
                    $data['users']['login']=$data['users']['email'];
                }        
            }
            //*******End of functionality limitations********
            $this->db->where('id', $id);
            $this->db->update(db_prefix.'Users', $data['users']);
            
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_member_email_authentication')===true && isset($data['users']['email']))
            {
                if($data['users']['email']!=$info[0]['email'])
                {
                    protection_event("USER_UPDATED",$id);
                }        
            }
            elseif (Functionality_enabled('admin_product_hosted')===true && isset($data['users']['email']))
            {
                if($data['users']['email']!=$info[0]['email'])
                {
                    protection_event("USER_UPDATED",$id);
                }        
            }
            //*******End of functionality limitations********
        }
        //Update Account_status table
        if( count($data['account_status']) > 0 )
        {
            $this->db->where('user_id', $id);
            $this->db->update(db_prefix.'Account_status', $data['account_status']);
        }

        return true;
    }


    /**
    * Update member's password in DB
    *
    * @author Drovorubov
    * @param integer $id
    * @param string $pswd
    * @return array
    */
    function set_member_pwd($id,$pwd)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if( intval($id) < 1 || $pwd == '' || mb_strlen($pwd) > 254 )
        {
            return $rv;
        }

        $pwd = input_text($pwd);
        $pwd_enc = crypt($pwd);
        $pwd_enc_bf = ns_encrypt($pwd, $pwd_enc);
        $data = array('pass'=>$pwd_enc, 'sec_code'=>$pwd_enc_bf);
        $this->db->where('id', $id);
        $this->db->update(db_prefix.'Users', $data);

        if( $this->db->affected_rows() > 0 )
        {
            $rv['result'] = true;
            //Fire protection event
            protection_event("USER_UPDATED",$id);
        }
        return $rv;
    }

  

    /**
    * Gets member account info
    *
    * @author Drovorubov
    * @param integer $id
    * @return mixed array/false
    */
    function get_member_info($id)
    {
        $id = intval($id);
        if($id < 1)
        {
            return false;
        }

        $this->db->select('id, login, email, name, last_name, login_redirect');
        $this->db->select('approve, activate, suspended');
        $this->db->select("account_status.added as reg_date");
$this->db->select("account_status.expire as expire_date");
        $this->db->from(array(db_prefix.'Users users', db_prefix.'Account_status account_status'));
        $this->db->where('users.id',$id);
        $this->db->where('users.id = account_status.user_id');
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $tmp = $query->result_array();
            return $tmp[0];
        }

        return false;
    }





    /**
    * Set member's status as suspended
    *
    * @author Drovorubov
    * @param integer $uid
    * @param integer $reason_id
    * @return bool
    */
    function suspend($uid,$reason_id=0)
    {
        $uid = intval($uid);
        $reason_id = intval($reason_id);
        if( $uid < 1 || $reason_id < 0 )
        {
            return false;
        }

        $data = array();
        $data['suspended'] = '1';
        if( $reason_id > 0 )
        {
            $data['suspend_reason_id'] = $reason_id;
        }
        $this->db->where('user_id', $uid);
        $this->db->update(db_prefix.'Account_status',$data);
        protection_event("USER_SUSPENDED",$uid);
        return true;
    }


    /**
    * Set member's status as approved
    *
    * @author Drovorubov
    * @param integer $uid
    * @return bool
    */
    function approve($uid)
    {
        $uid = intval($uid);
        if( $uid < 1 )
        {
            return false;
        }

        $data = array('approve'=>'1');
        $this->db->where('user_id', $uid);
        $this->db->update(db_prefix.'Account_status',$data);
        $this->protection_change($uid);
        return true;
    }


    /**
    * Set member's status as unsuspend
    *
    * @author Drovorubov
    * @param integer $uid
    * @return bool
    */
    function unsuspend($uid)
    {
        $uid = intval($uid);
        if( $uid < 1 )
        {
            return false;
        }

        $data = array(
                        'suspended'=>'0',
                        'suspend_reason_id'=>'0'
                      );
        $this->db->where('user_id', $uid);
        $this->db->update(db_prefix.'Account_status',$data);
        //Fire protection event
        $this->protection_change($uid);
        return true; 
    }
    
    //$statuses=array('expired'=>0,'activate'=>0,'approve'=>0,'suspended'=>0);
    /**
     * Change user protection status
     *
     * @param mixed $user_id
     * @param mixed $statuses
     * @return mixed
     */
    function protection_change($user_id,$statuses=false)
    {
        if(!isset($user_id) || intval($user_id)<=0)
        {
            return false;
        }
        
        if(!is_array($statuses) || !isset($statuses['expired']) || !isset($statuses['activate']) || !isset($statuses['approve']) || !isset($statuses['suspended']))
        {
            $query = $this->db->get_where(db_prefix.'Account_status', array('user_id' => $user_id),1);
            $result=$query->result_array();
            if(count($result)>0)
            {
                $statuses=$result[0];    
            }
            else
            {
                return false;
            }
        }
        
        if(intval($statuses['expired'])>0 || intval($statuses['approve'])<=0 || intval($statuses['activate'])<=0 || intval($statuses['suspended'])>0)
        {
            protection_event("USER_SUSPENDED",$user_id);                
        }
        else
        {
            protection_event("USER_UNSUSPENDED",$user_id);
        }
    }



    /**
    * Set member's status as activate
    *
    * @author Drovorubov
    * @param integer $uid
    * @return bool
    */
    function activate($uid)
    {
        $uid = intval($uid);
        if( $uid < 1 )
        {
            return false;
        }

        $data = array('activate'=>'1');
        $this->db->where('user_id', $uid);
        $this->db->update(db_prefix.'Account_status',$data);
        $this->protection_change($uid);
        return true;
    }





    /**
    * Deletes member info from DB
    *
    * @author Drovorubov
    * @param integer $id
    * @return bool
    */
    function delete($id)
    {
        $id = intval($id);
        if( $id < 1 )
        {
            return false;
        }
        //Fire protection event
        protection_event("USER_DELETED",$id);
        //Set user_id as 1 in Protection
        $this->db->where('user_id', $id);
        $this->db->update(db_prefix.'Protection',array('user_id'=>'1'));
        //Set user_id as 1 in User_logs
        $this->db->where('user_id', $id);
        $this->db->update(db_prefix.'User_logs',array('user_id'=>'1'));        
        // Delete member info from Account_status
        $this->db->where('user_id', $id);
        $this->db->delete(db_prefix.'Account_status');
        // Delete member info from User_logins
        $this->db->where('user_id', $id);
        $this->db->delete(db_prefix.'User_logins');        
        // Delete member info from User_add_fields
        $this->db->where('user_id', $id);
        $this->db->delete(db_prefix.'User_add_fields');
        // Delete user info from Users
        $this->db->where('id', $id);
        $this->db->delete(db_prefix.'Users');        
        if( $this->db->affected_rows() > 0 )
        {
            $CI =& get_instance();        
            $CI->load->model("member_group_model"); 
            $CI->member_group_model->delete_member_groups($id);
            return true;
        }
        return false;
    }


    /**
    * Adds member info into DB
    *
    * @author Drovorubov
    * @param array $param
    * @return mixed
    */
    function add($param)
    {
        $rv = false;
        if( !is_array($param) || count($param) <= 0 )
        {
            return $rv;
        }

        //Prepare params before updating
        $data = $this->_prepare_member_params($param);
        $this->db->insert(db_prefix.'Users', $data['users']);
        $id = $this->db->insert_id();
        if($this->db->affected_rows() == 1 && $id > 0)
        {
            $data['account_status']['user_id'] = $id;
            $this->db->insert(db_prefix.'Account_status', $data['account_status']);
            if($this->db->affected_rows() == 1)
            {
                $rv = $id;
                if(isset($param['groups']))
                {
                    $CI=&get_instance();
                    $CI->load->model("member_group_model");
                    $CI->member_group_model->set_member_groups($id,$param['groups']);
                }
            }
            else
            {
                $this->db->delete(db_prefix.'Users', array('id' => $id));
            }
        }
        return $rv;
    }

    /**
    * Getting not approved members list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function not_approved_list($page,$count,$sort_by,$sort_how)
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
        $sort_by = $this->_get_member_order($sort_by,$sort_how);

        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        $this->db->where("account_status.approve = 0");
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
        $this->db->select('id, login, name, last_name, suspend_reason_id');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id=account_status.user_id');
        $this->db->where("account_status.approve = 0");
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
    * Getting not confirmed members list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function not_confirm_list($page,$count,$sort_by,$sort_how)
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
        $sort_by = $this->_get_member_order($sort_by,$sort_how);

        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        $this->db->where("account_status.activate = 0");
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
        $this->db->select('id, login, name, last_name');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id=account_status.user_id');
        $this->db->where("account_status.activate = 0");
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
    * Getting suspended members list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function suspended_list($page,$count,$sort_by,$sort_how)
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
        $sort_by = $this->_get_member_order($sort_by,$sort_how);

        //Get total rows count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        $this->db->where("account_status.suspended = 1");
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
        $this->db->select('id, login, name, last_name');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id = account_status.user_id');
        $this->db->where("account_status.suspended = 1");
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
    * Getting suspend reasons list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @return array
    */
    function suspend_reasons_list($page=0,$count=0,$sort_by='',$sort_how='')
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        
        //Get reasons list
        $this->db->select('suspend_reasons.id');
        $this->db->from(array(db_prefix.'Suspend_reasons suspend_reasons'));
        $query = $this->db->get();
        
        $sort_param=array();
        $sort_param['by_name']='name';
        $sort_param['by_descr']='descr';
        $sort_by=array_key_exists($sort_by,$sort_param) ? $sort_param[$sort_by] : $sort_param['by_name'];
        
        $t=$query->result_array();
        if( intval($page) > 0 && intval($count) > 0 )
        {
            $pager=pager_params($page,$count,count($t));
        }
        else
        {
            $pager=pager_params(1,count($t),count($t));
        }
        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,10,array('name'=>'name','descr'=>'descr'),'id',array('col'=>$sort_by,'order'=>$sort_how,'limit'=>$pager['limit'],'offset'=>$pager['offset']),false,&$add_params);
        
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
    * Getting reason value from DB
    *
    * @author Drovorubov
    * @param integer $id
    * @return mixed array/false
    */
    function suspend_reason_info($id)
    {
        $id = intval($id);
        if($id < 1)
        {
            return false;
        }

        $this->db->select('suspend_reasons.id');
        $this->db->select('language_data.name, language_data.descr');
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'Suspend_reasons suspend_reasons'));
        $this->db->where('suspend_reasons.id',$id);
        $this->db->where('suspend_reasons.id=language_data.object_id');
        $this->db->where('language_data.object_type',10);
        $this->db->where('language_data.language_id',1);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $tmp = $query->result_array();
            return $tmp[0];
        }

        return false;
    }


    /**
    *
    * Deletes suspend reason from DB
    *
    * @author Drovorubov
    * @param integer $rid
    * @return bool
    */
    function suspend_reason_delete($rid)
    {
        if( empty($rid)  )
        {
            return false;
        }
        // Delete item from Suspend reasons table
        $this->db->where('id', $rid);
        $this->db->delete(db_prefix.'Suspend_reasons');
        if( $this->db->affected_rows() > 0 )
        {
            $CI =& get_instance();        
            $CI->load->model("lang_manager_model"); 
            $CI->lang_manager_model->remove_language_data(10,$rid);
            return true;
        }
        return false;
    }



    /**
    *
    * Updates reason fields in DB
    *
    * @author Drovorubov
    * @param integer $rid
    * @param array $param
    * @return bool
    */
    function suspend_reason_edit($rid,$param)
    {
        $rid = intval($rid);
        if( $rid < 1 || !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Prepare data before updating
        $data = array();
        $data = $this->_prepare_suspend_reason_params($param);
        // Update table
        if( count($data) > 0 )
        {
            $this->db->where('object_id',$rid);
            $this->db->where('language_id',1);
            $this->db->where('object_type',10);
            $this->db->update(db_prefix.'Language_data', $data);
        }

        if( $this->db->affected_rows() == 1 )
        {
            return true;
        }

        return false;
    }

    
    //Get new suspend reason id
    /**
     * Get new suspend reason id
     *
     * @return integer
     */
    function Get_new_id()
    {
    $this->db->simple_query('insert into '.db_prefix.'Suspend_reasons ' . '  VALUE()');
    return $this->db->insert_id();    
    }

    /**
    * Add a new suspend reason into DB
    *
    * @author Drovorubov
    * @param array $param
    * @return mixed - bool or integer
    */
    function suspend_reason_add($param)
    {
        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        //Prepare data before updating
        $data = array();
        $data = $this->_prepare_suspend_reason_params($param);
        // Update table
        if( count($data) > 0 )
        {
            $this->db->simple_query('insert into '.db_prefix.'Suspend_reasons ' . '  VALUE()');
            $id = $this->db->insert_id();
            if($this->db->affected_rows() == 1 && $id > 0)
            {
                $data['language_id'] = 1;
                $data['object_type'] = 10;
                $data['object_id'] = $id;
                $this->db->insert(db_prefix.'Language_data', $data);
                if( $this->db->affected_rows() == 1 && $id > 0 )
                {
                    return $id;
                }
                else
                {
                    $this->db->delete(db_prefix.'Suspend_reasons', array('id' => $id));
                }
            }
        }
        return false;
    }



    /**
    * Gets members list
    *
    * @author Drovorubov
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    * @param array $search
    * @return array
    */
    function member_list($page,$count,$sort_by,$sort_how,$search)
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
        $sort_by = $this->_get_member_order($sort_by,$sort_how);

        //Get total members count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id > 1');        
        $this->db->where('users.id = account_status.user_id');
        //$this->db->where('account_status.suspended', '0');
        //Set search params for rows counting
        $this->_add_search2query($search);
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

        //Get member list
        $this->db->select('users.id, users.login, users.email, users.name, users.last_name');
        $this->db->select('account_status.added as reg_date');
        $this->db->select('account_status.approve, account_status.activate, account_status.suspended, account_status.suspend_reason_id');
        $this->db->from(array(db_prefix.'Account_status account_status', db_prefix.'Users users'));
        $this->db->where('users.id > 1');        
        $this->db->where('users.id = account_status.user_id');
        //$this->db->where('account_status.suspended', '0');
        
        //Set search params for getting data
        $this->_add_search2query($search);
        $this->db->limit($count,$row_start);
        $this->db->order_by($sort_by);
        $query = $this->db->get();
        $rv['count']  =  $query->num_rows() ;
        $rv['items'] = $query->result_array();
        $rv['result'] = true;
        return $rv;
    }

    
    /**
    * Returns member's subscription regular_price sum 
    * and the number of subscriptions with status 1
    *
    * @author Drovorubov
    * @param integer $uid
    * @return array
    */
    function get_member_subscriptions($uid)
    {
        $rv = array();
        if( $uid < 1 )
        {
            return $rv;
        }
        $this->db->select('sum(subscriptions.regular_price) as price_sum, count(subscriptions.id) as subscr_count');    
        $this->db->from(array(db_prefix.'Subscriptions subscriptions', db_prefix.'Protection protection'));        
        $this->db->where('protection.user_id',$uid);        
        $this->db->where('protection.subscr_id = subscriptions.id');
        $this->db->where('subscriptions.status = 1');
        $this->db->group_by("protection.user_id");
        $query = $this->db->get();
        if ( $query->num_rows() > 0 )
        {
            $rv = $query->result_array();
            return $rv[0];
        }
        
        return $rv;   
    }
    
    
    

    /**
    * Adds search params to this object SQL query as WHERE items
    *
    * @param array $data
    */
    function _add_search2query($data)
    {
        if( is_array($data) && count($data) > 0 )
        {
            //Add where like to query for letter
            if( isset($data['letter']) )
            {
                $this->db->like('users.login', ''.$data['letter'], 'after');
            }
            //Add where like to query for key
            if( isset($data['search_key']) && isset($data['search_val']) )
            {
                $key = input_text($data['search_key']);
                $val = input_text($data['search_val']);
                switch($key)
                {
                    case 'login':
                    {
                        $this->db->like('users.login', $val);
                        break;
                    }
                    case 'email':
                    {
                        $this->db->like('users.email', $val);
                        break;
                    }
                    case 'first_name':
                    {
                        $this->db->like('users.name', $val);
                        break;
                    }
                    case 'last_name':
                    {
                        $this->db->like('users.last_name', $val);
                        break;
                    }
                    case 'group':
                    {
                        $this->db->from(db_prefix.'Member_groups_members groups');
                        $this->db->where('groups.user_id=users.id');
                        $this->db->where('groups.group_id',$val);
                        break;
                    }
                }
            }
            //Add where like to query for date period
            if( isset($data['date_from']) && isset($data['date_to']) )
            {
                $date_from = convert_date($data['date_from']);
                $date_to = convert_date($data['date_to']);
                $this->db->where('DATE(account_status.added) >= ', $date_from);
                $this->db->where('DATE(account_status.added) <= ', $date_to);
            }
            else if(isset($data['date_period']))
            {
                switch($data['date_period'])
                {
                    case 'today':
                    {
                        $this->db->where("TO_DAYS(account_status.added) = TO_DAYS(CURDATE())");
                        break;
                    }
                    case 'this_week':
                    {
                        $this->db->where("WEEK(account_status.added) = WEEK(CURDATE())");
                        break;
                    }
                    case 'this_month':
                    {
                        $this->db->where("MONTH(account_status.added) = MONTH(CURDATE())");
                        break;
                    }
                    case 'this_year':
                    {
                        $this->db->where("YEAR(account_status.added) = YEAR(CURDATE())");
                        break;
                    }
                    case 'yesterday':
                    {
                        $this->db->where("TO_DAYS(account_status.added) = TO_DAYS(CURDATE()) - 1");
                        break;
                    }
                    case 'prev_week':
                    {
                        $this->db->where("WEEK(account_status.added) = WEEK(CURDATE()) - 1");
                        break;
                    }
                    case 'prev_month':
                    {
                        $this->db->where("MONTH(account_status.added) = MONTH(CURDATE()) - 1");
                        break;
                    }
                    case 'prev_year':
                    {
                        $this->db->where("YEAR(account_status.added) = YEAR(CURDATE()) - 1");
                        break;
                    }
                }
            }
			
			if (isset($data['search_status']))
			{
				switch($data['search_status'])
                {
					case 'status_all':
					{
						break;
					}
					case 'status_active':
					{
						$this->db->where("account_status.suspended = 0");
						$this->db->where("account_status.approve = 1");
						$this->db->where("account_status.activate = 1");
						break;
					}
					case 'status_suspend':
					{
						$this->db->where("account_status.suspended = 1");
						break;
					}
					case 'status_approve':
					{
						$this->db->where("account_status.approve = 0");
						break;
					}
					case 'status_activate':
					{
						$this->db->where("account_status.activate = 0");
						break;
					}
					case 'status_expired':
					{
						$this->db->where("account_status.expired = 1");
						break;
					}
					case 'status_inactive':
					{
						$this->db->where("(account_status.suspended = '1' OR account_status.approve = '0' OR account_status.activate = '0')");
						break;
					}
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
    function _get_member_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_regdate':
            {
                $rv = 'account_status.added ' . $ord_type;
                break;
            }
            case 'by_email':
            {
                $rv = 'users.email ' . $ord_type;
                break;
            }
            case 'by_login':
            {
                $rv = 'users.login ' . $ord_type;
                break;
            }
            case 'by_fullname':
            {
                $rv = 'users.name ' .$ord_type. ' , users.last_name ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'users.login ASC';
            }
        }

        return $rv;
    }

    
    /**
    * Returns if suspended member or not
    *
    * @author Drovorubov
    * @param integer $uid (member id)
    * @return bool
    */
    function is_suspended($uid)
    {
        $uid = intval($uid);
        if( intval($uid)<=0 )
        {
            return false;
        }
        
        $this->db->select('suspended');
        $this->db->limit(1);
        $query = $this->db->get_where(db_prefix.'Account_status',array('user_id'=>$uid));
        if( $query->num_rows() > 0 )
        {
            $suspend_info = $query->row();
            return (bool)$suspend_info->suspended;
        }
        return false;
    }
    

    /**
    *
    * Checks if email exists in DB
    * If id value is set exclude the row with this id
    *
    * @author Drovorubov
    * @param string $email
    * @param integer $id
    * @return bool
    */
    function is_email_exists($email,$id='')
    {

        if( !isset($email) || empty($email) )
        {
            return false;
        }

        $this->db->select('id');
        $this->db->where('email', $email);
        if(!empty($id))
        {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get(db_prefix.'Users');
        if( $query->num_rows() > 0 )
        {
            return true;
        }
        return false;
    }


    /**
    * Converts ORDER params for memeber's access log SELECT
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_access_log_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_date':
            {
                $rv = 'user_logs.time ' . $ord_type;
                break;
            }
            case 'by_url':
            {
                $rv = 'user_logs.url ' . $ord_type;
                break;
            }
            case 'by_ip':
            {
                $rv = 'user_logs.ip ' . $ord_type;
                break;
            }
            case 'by_http_referer':
            {
                $rv = 'user_logs.http_referer ' . $ord_type;
                break;
            }
            case 'by_product':
            {
                $rv = 'language_data.name ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'user_logs.time ' . $ord_type;
            }
        }

        return $rv;
    }


    /**
    * Adds search params to this object SQL query as WHERE items
    *
    * @param array $data
    */
    function _add_search2accesslog_query($data)
    {
        if( is_array($data) && count($data) > 0 )
        {
            //Add where like to query for date period
            if( isset($data['date_from']) && isset($data['date_to']) )
            {
                $date_from = convert_date($data['date_from']);
                $date_to = convert_date($data['date_to']);
                
                $this->db->where('DATE(user_logs.time) >= ', $date_from);
                $this->db->where('DATE(user_logs.time) <= ', $date_to);
            }
        }
    }


    /**
    * Converts ORDER params for suspend reasons SELECT
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_suspend_reason_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_name':
            {
                $rv = 'language_data.name ' . $ord_type;
                break;
            }
            case 'by_descr':
            {
                $rv = 'language_data.descr ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'language_data.name ASC';
            }
        }
        return $rv;


    }



    /**
    * Converts ORDER params for memeber's email history SELECT
    *
    * @author Drovorubov
    * @param string $ord_name
    * @param string $ord_type
    * @return string
    */
    function _get_email_history_order($ord_name,$ord_type='')
    {
        $rv = '';
        switch($ord_name)
        {
            case 'by_date':
            {
                $rv = 'email_queue.date ' . $ord_type;
                break;
            }
            case 'by_email':
            {
                $rv = 'email_to_send.email_from ' . $ord_type;
                break;
            }
            case 'by_subject':
            {
                $rv = 'language_data.name ' . $ord_type;
                break;
            }
            default:
            {
                $rv = 'email_queue.date ' . $ord_type;
            }
        }
        return $rv;
    }



    /**
    * Prepares reason entry params
    *
    * @author Drovorubov
    * @param array $prm
    * @return bool
    */
    function _prepare_suspend_reason_params($prm)
    {
        $data = array();
        foreach($prm as $key=>$val)
        {
            if( $key == 'name' )
            {
                if( mb_strlen($val) > 254 )
                {
                    $val = mb_strcut($val, 1, 254);
                }
                $data['name'] = input_text($val);
            }
            else if( $key == 'descr' )
            {
                if( mb_strlen($val) > 65534 )
                {
                    $val = mb_strcut($val, 1, 65534);
                }
                $data['descr'] = input_text($val);
            }
        }
        return $data;
    }



    /**
    * Prepares entry params
    *
    * @author Drovorubov
    * @param array $prm
    * @return bool
    */
    function _prepare_member_params($prm)
    {
        $data1 = array();
        $data2 = array();
        foreach($prm as $key=>$val)
        {
            if($key == 'login_redirect' && intval(config_get("system","config","personal_login_redirect_flag")))
            {
                $data1['login_redirect'] = $val;
            }
            if($key == 'language_id')
            {
                $data1['language_id'] = intval($val);
            }
            if( $key == 'login' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $data1['login'] = input_text($val);
            }
            else if( $key == 'pwd' )
            {
                if( mb_strlen($val) > 64 )
                {
                    $val = mb_strcut($val, 1, 64);
                }
                $pwd = input_text($val);
                $data1['pass'] = crypt($pwd);
                $data1['sec_code'] = ns_encrypt($pwd,$data1['pass']);
            }
            else if( $key == 'exp_date' )
            {
                if(!validate_date($val))
                {
                    $val = "";
                }
                else
                {
                    $val = input_text($val);
                    $val=convert_date($val);
                    //$val = $this->_convert_date_format($val);
                }
                $data2['expire'] = $val;
            }
            else if( $key == 'expired' )
            {
                $data2['expired'] = intval($val);
            }
            else if( $key == 'email' )
            {
                if( mb_strlen($val) > 64)
                {
                    $val = mb_strcut($val, 1, 64);
                }
                $data1['email'] = input_text($val);
            }
            else if( $key == 'name' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $data1['name'] = input_text($val);
            }
            else if( $key == 'last_name' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $data1['last_name'] = input_text($val);
            }
            else if( $key == 'status_approved' )
            {
                $val = intval($val);
                if( intval($val) > 1 || intval($val) < 0 )
                {
                    $val = 0;
                }
                $data2['approve'] = $val;
            }
            else if( $key == 'status_confirmed' )
            {
                $val = intval($val);
                if(intval($val) > 1 || intval($val) < 0)
                {
                    $val = 0;
                }
                $data2['activate'] = $val;
            }
            else if( $key == 'status_suspended' )
            {
                $val = intval($val);
                if(intval($val) > 1 || intval($val) < 0)
                {
                    $val = 0;
                }
                $data2['suspended'] = $val;
            }
            else if( $key == 'ac_code' )
            {
                $data2['ac_code'] = $val;
            }
        }
        $data['users'] = $data1;
        $data['account_status'] = $data2;
        return $data;
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
    * Converts ORDER params for memeber's subscriptions SELECT
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
    * Gets all unblocked products
    *
    * @author Drovorubov
    * @param integer $uid
    * @param string $subscribed_products (list of id separated by comma)
    * @return mixed array/false
    */
    function get_unblocked_products_list($uid,$subscribed_products)
    {
        if( $uid < 1 )
        {
            return false;
        }
        $hosting = '';
        $valid_protucts = ' AND products.product_type IN('. implode(',', $this->product_types).')';
       		if (Functionality_enabled('admin_product_hosted')===true)
			{
		        $CI=&get_instance();
				$CI->load->model("host_plans_model");
				$host_plans = $CI->host_plans_model->Load_User_host_plans($uid);
                if (is_array($host_plans) && count($host_plans)>0)
                {
/**
 * @todo one user - one hosting plan (while)
 */                	
                	$hosting = ' AND products.product_type != '.PRODUCT_HOSTED;
                }
			}
        //Prepare select
        $sql = '
                (SELECT products.id as id, product_type
                , prices.day, prices.month, prices.month3, prices.month6, prices.year, prices.unlimit
                , product_discount.discount, product_discount.discount_type
                FROM (`'. db_prefix .'Products` as products, `'. db_prefix .'Prices` as prices, `'. db_prefix .'Product_discount` as product_discount)

                LEFT OUTER JOIN '. db_prefix .'Protection protection ON products.id = protection.product_id
                AND protection.user_id='. $uid .

                '
                WHERE protection.product_id IS null
                AND products.blocked=0
                AND products.closed=0
                AND prices.id=products.id';
        if ($hosting)
        {
        	$sql .= $hosting;
        }
           $sql .= $valid_protucts;
          $sql .= ' AND product_discount.id=products.id)

                UNION

                (SELECT protection.product_id as id, product_type
                , prices.day, prices.month, prices.month3, prices.month6, prices.year, prices.unlimit
                , product_discount.discount, product_discount.discount_type
                FROM (`'. db_prefix .'Protection` protection, `'. db_prefix .'Subscriptions` subscriptions, `'. db_prefix .'Products` products)

                LEFT JOIN `'. db_prefix .'Prices` prices ON prices.id=protection.product_id
                
                LEFT JOIN `'. db_prefix .'Product_discount` product_discount ON product_discount.id=protection.product_id
                
                WHERE prices.id = products.id
                AND protection.subscr_id = subscriptions.id ';
    
        if( $subscribed_products != '' )
        {
            $sql .= ' AND protection.product_id NOT IN ('. $subscribed_products .') ';
        }
        if ($hosting)
        {
        	$sql .= $hosting;
        }
        $sql .= $valid_protucts;
        $sql .= ' AND protection.product_id = products.id 
                AND products.blocked=0 
                AND products.closed=0 
                AND protection.user_id = '. $uid .')';
        //Execute SQL string
        $query = $this->db->query($sql);
        $t=$query->result_array();
        
        //print_r($t);
fb($t,"get_unblocked_products_list");        
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'name'),'id',array('col'=>'name'),false,&$add_params);
        
        return $t;
    }



    /**
    * Creates a new subscription if it is not created and adds a new payment
    *
    * @author Drovorubov
    * @param array $param
    * @return bool
    */
    function susbcribe_add($param)
    {
        fb($param,"susbcribe_add");
        
        $CI = &get_instance();

        if( !is_array($param) || count($param) <= 0 )
        {
            return false;
        }
        if( !isset($param['uid']) || !isset($param['product']) )
        {
            return false;
        }
        $subscr_id = 0;
        //Prepare params before updating
        $data = $this->_prepare_subscribe_params($param);
        fb($data,"____susbcribe_add");
        //Create a new subscription
        $data['subscription']['user_info_id'] = isset($param['user_info_id']) ? $param['user_info_id'] : 0;
        $data['subscription']['status'] = 1;
        $this->db->insert(db_prefix.'Subscriptions', $data['subscription']);
        $subscr_id = $this->db->insert_id();
        if( $this->db->affected_rows() > 0 && $subscr_id > 0 )
        {
	        //***********Functionality limitations***********
	        if(Functionality_enabled('admin_product_hosted')===true)
	        {
	        	$CI->load->model("product_model");
				// Insert new record to host_subscription if PRODUCT_HOSTED
				if ($CI->product_model->_is_product_typeof($param['product']) == PRODUCT_HOSTED)
				{
					$CI->load->model("host_plans_model");
					$ok_insert = $CI->host_plans_model->insert_host_subscription($subscr_id,unserialize($data['transaction']['info']));
					if( !$ok_insert )
					{
						$this->db->delete(db_prefix.'Subscriptions',array('id'=>intval($subscr_id)));
						return false;
					}
				}
	        }
            //Insert new record to Protection
            $protection = array();
            $protection['user_id'] = $param['uid'];
            $protection['product_id'] = $param['product'];
            $protection['subscr_id'] = $subscr_id;
            $this->db->insert(db_prefix.'Protection', $protection);
            //Add new transaction
            $new_transaction_id = 0;
            $data['transaction']['subscription_id'] = $subscr_id;
            $data['transaction']['completed']=1;
            $this->db->insert(db_prefix.'Transactions', $data['transaction']);
            $new_transaction_id = $this->db->insert_id();
            if($this->db->affected_rows() > 0 && $new_transaction_id > 0)
            {
                //Fire protection event
                /**
                 * @todo need error handler
                 */
                Protection_event('SUBSCRIPTION_STARTED',$param['uid'],$subscr_id);
                
                $member_info = $this->get_member_info($param['uid']);
                // send email to user
                send_system_email_to_user($param['uid'],'user_payment_notification',array('product_name'=>array('object_id'=>$param['product'],'object_type'=>4),'product_expiration_date'=>nsdate($data['subscription']['expire_date'],false)));
                // notify all administrators by email
                send_system_subscription_to_admins('admin_subscription_started', array('user_login'=>$member_info['login'],'product_name'=>array('object_id'=>$param['product'],'object_type'=>4)));
                // notify all administrators by email
                send_system_subscription_to_admins('admin_payment_notification', array('product_name'=>array('object_id'=>$param['product'],'object_type'=>4), 'subscription_id'=>$subscr_id, 'transaction_id'=>$new_transaction_id, 'amount'=>$data['transaction']['summ']));
                // _inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                return true;
            }            
        }
        return false;
    }
    
    /**
    * Creates a free available products subscription for all users
    *
    * @author onagr
    * @param integer $portion
    * @param string $transaction
    * @param integer $payment_system
    * @return bool
    */
    function Subscribe_all_free($portion=100,$transaction='auto subscription',$payment_system=1)
    {
        $CI=&get_instance();
        $CI->load->model('market_model');
        $subscrs=$CI->market_model->get_available_free_subscriptions();
        $i=0;
        foreach($subscrs as $value)
        {   
            if($portion==$i){return true;}
            $this->susbcribe_add(array(
            'uid' =>$value['user_id'],
            'product_type' =>$value['product_id'].'-1',
            'product' =>$value['product_id'],
            'price' =>0,
            'period' =>'unlimit',
            'payment_system' =>$payment_system,
            'transaction' =>$transaction            
            ));
            $i++;        
        } 
        return true;
    }


    /**
    * Prepares entry params
    *
    * @author Drovorubov
    * @param array $prm
    * @return bool
    */
    function _prepare_subscribe_params($prm)
    {
        //Create 3 arrayes for 3 tables
        $protection = array();
        $subscription = array();
        $transaction = array();
        //Distribute params to 3 arrays according keys
        foreach($prm as $key=>$val)
        {
            if( $key == 'uid' )
            {
                $protection['user_id'] = intval($val);
            }
            else if( $key == 'product' )
            {
                $protection['product_id'] = intval($val);
            }
            else if( $key == 'price' )
            {
                $val = floatval($val);
                $subscription['regular_price'] = $val;
                $transaction['summ'] = $val;
            }
            else if( $key == 'payment_system' )
            {
                $transaction['pay_system_id'] = intval($val);
            }
            else if( $key == 'transaction' )
            {
                if( mb_strlen($val) > 32 )
                {
                    $val = mb_strcut($val, 1, 32);
                }
                $val = input_text($val);
                $transaction['info'] = serialize($val);
            }
            else if( $key == 'period' )
            {
                $cdate = date("Y-m-d");
                $subscription['cdate'] = $cdate;
                $transaction['date'] = $cdate;
                switch($val)
                {
                    case 'day':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+1,date("Y")));
                        $subscription['regular_period_type'] = 'day';
                        $subscription['regular_period_value'] = 1;
                        break;
                    case 'month':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m")+1,date("d"),date("Y")));
                        $subscription['regular_period_type'] = 'month';
                        $subscription['regular_period_value'] = 1;
                        break;
                    case 'month3':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m")+3,date("d"),date("Y")));
                        $subscription['regular_period_type'] = 'month';
                        $subscription['regular_period_value'] = 3;
                        break;
                    case 'month6':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m")+6,date("d"),date("Y")));
                        $subscription['regular_period_type'] = 'month';
                        $subscription['regular_period_value'] = 6;
                        break;
                    case 'year':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d"),date("Y")+1));
                        $subscription['regular_period_type'] = 'year';
                        $subscription['regular_period_value'] = 1;
                        break;
                    case 'unlimit':
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d"),date("Y")+5));
                        $subscription['regular_period_type'] = 'year';
                        $subscription['regular_period_value'] = 5;
                        break;                        
                    default:
                        $subscription['expire_date'] = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d"),date("Y")+5));                    
                        $subscription['regular_period_type'] = 'year';
                        $subscription['regular_period_value'] = 5;
                }
            }
        }
        //Add currency code
        $subscription['currency_code'] = config_get('system','config','currency_code');
        //Set subscription type to onetime
        $subscription['type'] = 1;
        //Prepare arrays
        $data['protection'] = $protection;
        $data['subscription'] = $subscription;
        $data['transaction'] = $transaction;
        return $data;
    }



    /**
    * Converts ORDER params for memeber's transactions SELECT
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
    * Update user statuses
    *
    * @author Zhalybin
    */
	function user_statuses_change($data, $mid,$is_normalize=false)
	{
			$CI = &get_instance();
			$CI->load->model("user_model");
			
			//Getting status params
            $data['status_approved'] = !isset($data['status_approved']) ? intval($this->input->post('approved')) : $data['status_approved'];
            $data['status_confirmed'] = !isset($data['status_confirmed']) ? intval($this->input->post('confirmed')) : $data['status_confirmed'];
            $data['status_suspended'] = !isset($data['status_suspended']) ? intval($this->input->post('suspended')) : $data['status_suspended'];
            
            //Get member suspend current status
            $crnt_suspend_status = $this->is_suspended($mid);
            $status_info=$CI->user_model->get_status($mid);
            $status_info=isset($status_info[0]) ? $status_info[0] : false;
            $lang_id=$CI->user_model->get_lang($mid);
			
			$update_res = $this->set_member($mid,$data);
			if(!$update_res)
            {
                $res = 'Error: The information is not changed';
                make_response("error", ($is_normalize ? create_temp_vars_set(array($res)) : $res), 1);
                simple_admin_log('member_info_modify',$mid,true,"not_updated");
                return false;
            }
            
            $result=send_system_email_to_user($mid,'user_profile_change');
            if(!$result)
            {
                $error = '<{admin_member_control_error_member_info_email_not_sent}>';
            }
            
            $is_event_changed=false;
			
			$statuses_strings=array();
        	if (isset($data['expired']))
				if($status_info && intval($status_info['expired'])!=intval($data['expired']))
				{
					$is_event_changed=true;
					if(intval($data['expired'])>0)
					{
						$result=send_system_email_to_user($mid,'user_account_expire');
					}
					else
					{
						$temp_date=($data['exp_date']!='' && convert_date($data['exp_date'])) ? " (".nsdate(convert_date($data['exp_date']),false).")" : '';
						$statuses_strings['extended']= "<{user_profile_status_extended}>".$temp_date;
					}
				}
            if($status_info && intval($status_info['approve'])!=intval($data['status_approved']))
            {
                $is_event_changed=true;
                $statuses_strings['approve']=intval($data['status_approved'])>0 ?"<{user_profile_status_approved}>" : "<{user_profile_status_not_approved}>";                
            }
            if($status_info && intval($status_info['activate'])!=intval($data['status_confirmed']))
            {
                $is_event_changed=true;
                $statuses_strings['activate']=intval($data['status_confirmed'])>0 ?"<{user_profile_status_activated}>" : "<{user_profile_status_not_activated}>";                          
            }
            
            if( $data['status_suspended'] == 1 && !$crnt_suspend_status)
            {
                $is_event_changed=true;
                //Send system email to member about suspending member
                if($status_info)
                {
                    $reason=$status_info['suspend_reason'] ? ' ('.$status_info['suspend_reason'].')' : '';
                }                
                $statuses_strings['suspend']="<{user_profile_status_suspended}>".$reason;
            }
            else if( $data['status_suspended'] == 0 && $crnt_suspend_status)
            {
                $is_event_changed=true;
                $statuses_strings['suspend']="<{user_profile_status_unsuspended}>";
                //modified by Sergey Makarenko @ 17.10.2008, 13:57
				$CI->load->model("user_auth_model");
                $CI->user_auth_model->Clear_autoban_records_for_user($mid);
            }

            //Fire protection event
            if($is_event_changed)
            {
                $this->protection_change($mid);
            }
            
            if(count($statuses_strings)>0)
            {
                $result=send_system_email_to_user($mid,'user_profile_status_change',array('user_account_status'=>replace_lang(implode(", ",$statuses_strings),$lang_id)));
                if(!$result)
                {
                    $error = '<{admin_member_control_error_email_not_sent}>';
                }
            }
	}
	
	/**
    * Change users statuses
    *
    * @author Zhalybin
    */
	function change_users_status($do_status, $mbr_list, $suspend_reason = 0)
	{
		$CI = &get_instance();
		$CI->load->model("user_model");
		switch ($do_status)
		{
			case 'do_status_notapprove ':
				foreach( $mbr_list as $mid )
        		{
					$data['status_suspended'] = $this->is_suspended($mid);
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = 0;
						$data['status_confirmed'] = $status_info['activate'];
					}
					$this->user_statuses_change($data, $mid);
				}
				//$this->approve_user();
				break;
			case 'do_status_approve ':
				foreach( $mbr_list as $mid )
        		{
					$data['status_suspended'] = $this->is_suspended($mid);
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = 1;
						$data['status_confirmed'] = $status_info['activate'];
					}
					$this->user_statuses_change($data, $mid);
				}
				break;
			case 'do_status_unsuspend ':
				foreach( $mbr_list as $mid )
        		{
					$data['status_suspended'] = 0;
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = $status_info['approve'];
						$data['status_confirmed'] = $status_info['activate'];
					}
					$this->user_statuses_change($data, $mid);
				}
				//$this->unsuspend_user();
				break;
			case 'do_status_suspend ':
				foreach( $mbr_list as $mid )
        		{
					/*$data['status_suspended'] = 1;
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = $status_info['approve'];
						$data['status_confirmed'] = $status_info['activate'];
					}
					$this->user_statuses_change($data, $mid);
                    */
                    $this->suspend($mid, $suspend_reason);
				}
				//$this->suspend_user();
				break;
			case 'do_status_notactivate ':
				foreach( $mbr_list as $mid )
        		{
					$data['status_suspended'] = $this->is_suspended($mid);
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = $status_info['approve'];
						$data['status_confirmed'] = 0;
					}
					$this->user_statuses_change($data, $mid);
				}
				//$this->approve_user();
				break;
			case 'do_status_activate ':
				foreach( $mbr_list as $mid )
        		{
					$data['status_suspended'] = $this->is_suspended($mid);
					$status_info=$CI->user_model->get_status($mid);
					$status_info=isset($status_info[0]) ? $status_info[0] : false;
					if ($status_info)
					{
						$data['status_approved'] = $status_info['approve'];
						$data['status_confirmed'] = 1;
					}
					$this->user_statuses_change($data, $mid);
				}
				break;
			case 'do_status_deleted ':
                foreach( $mbr_list as $mid )
        		{
                    $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $mid);//error does not output, because checkbox is disable
					if($functionality_enabled_error!==true)
					{   

					}
					else
					{
						$this->delete($mid);
					}
                }
				break;
		}
	}
	
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
		case "member_list":
            //**************************member_list*******************************
            //Temp variables javascript
            $temp_vars_set= array();
            /*$temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
            $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
            $temp_vars_set['processing']="<{admin_manage_news_msg_processing}>";*/
            $temp_vars_set['panel_script']=array (
												  base_url().'js/admin/member/common.js', 
												  base_url().'js/admin/member/list.js', 
												  base_url().'js/admin/member/edit/accnt_panel.js', 
												  base_url().'js/admin/member/edit/member_info_update.js', 
												  base_url().'js/admin/member/edit/change_pswd.js', 
												  base_url().'js/admin/member/edit/payments.js', 
												  base_url().'js/admin/member/edit/email_client.js', 
												  base_url().'js/admin/member/edit/email_history.js', 
												  base_url().'js/admin/member/edit/access_log.js', 
												  base_url().'js/admin/member/add.js', 
												  base_url().'js/admin/member/suspend.js', 
												  base_url().'js/admin/member/unsuspend.js', 
												  base_url().'js/admin/member/info.js', 
												  base_url().'js/admin/global.js', 
												  base_url().'js/admin/member/edit/transactions.js', 
												  base_url().'js/admin/member/confirm.js',  
												  base_url().'js/admin/member/suspreason.js', 
												  base_url().'js/admin/member/approve.js',  
												  base_url().'js/admin/config/pages_list.js');
            $temp_vars_set['object_type']=6;
            $temp_vars_set['panel_name']="manage_list";
            $temp_vars_set['controller']="member/member_list";
            $temp_vars_set['controller_action']="member/member_list";
            
            $data['temp_vars_set']=$temp_vars_set;
            //Green messages
            $messages = array();
            /*$messages['saved_ok'] = "<{admin_msg_ok_0001}>";  
            $messages['deleted_ok'] = "<{admin_manage_news_msg_ok_deleted}>";
            $messages['success_message']="<{admin_manage_news_success_message}>";
            $messages['edit_field_success']="<{admin_manage_news_edit_page_success}>";
            $messages['add_field_success']="<{admin_manage_news_add_page_success}>";*/
            
            $messages['success_delete']="<{admin_manage_news_remove_page_success}>";
            $data['messages'] = $messages;
            //Error messages
            $mess_err = array();
            /*$mess_err['0'] = "<{admin_msg_er_0000}>";
            $mess_err['not_saved'] = "<{admin_manage_news_msg_er_not_saved}>";
            $mess_err['duplicate_entry'] = "<{admin_manage_news_msg_er_duplicate_entry}>";
            $mess_err['not_deleted'] = "<{admin_manage_news_msg_er_not_deleted}>";
            $mess_err['not_found'] = "<{admin_manage_news_msg_er_not_found}>";*/
            $mess_err['access_denied'] = "<{admin_admin_edit_msg_er_access_denied}>";
            $data['mess_err'] = $mess_err;
            //***********************end_of_member_list***************************
            break;
        }
        return $data;
    }
}

?>
