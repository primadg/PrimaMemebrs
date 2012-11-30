<?php

class Host_plans_model extends Model
{
    /**
     * Constructor. Gets CI pointer because we need to call it frequently
     *
     * @return void
     */
    function Host_plans_model()
    {
        parent::Model();
    }
    
    /**
     * Reads list of assotiated products
     *
     * @return array of products
     */
    function Get_assotiated_products($id)
    {
        if(intval($id)>0)
        {
            $this->db->select('products.id, products.closed');
            $this->db->from(db_prefix.'Products `products`');
            $this->db->where('products.closed <> 1');
            $this->db->join(db_prefix.'Host_plans_products as host_plans_products', 'host_plans_products.product_id = products.id', 'LEFT');
            $this->db->where('host_plans_products.host_plan_id',$id);
            $query=$this->db->get();
            $result=$query->result_array();
            if(count($result)>0)
            {
                $CI =& get_instance();        
                $CI->load->model("lang_manager_model"); 
                $result = $CI->lang_manager_model->combine_with_language_data($result,4,array('name'=>'name'),'id',false,false,&$add_params);
                $result = array_transform($result,'id','name');
                return $result;
            }
        }
        return false;
    }
    /**
     * Calculates number of all host_plans in the db
     *
     * @return integer
     */
    function Number_of_host_plans()
    {
        return $this->db->count_all(db_prefix.'Host_plans');
    }


    function Get_host_plans($start, $amount, $orderby, $order)
    {
        /**
        * @TODO Replace construction WHERE IN (1,2,...,n)
        */
        $this->db->limit($start, $amount);
        $query=$this->db->get(db_prefix.'Host_plans');
        $host_plans=$query->result_array();                
        if(count($host_plans))
        {
            
            $this->db->select("d.id, count(product_id) as num_of_products");
            $this->db->from(db_prefix.'Host_plans d');
            $this->db->join(db_prefix.'Host_plans_products dp', "d.id=dp.host_plan_id", "left");

            //correct count - with valid products only
            $this->db->join(db_prefix.'Products p', "p.id=dp.product_id", "left");
            $this->db->where($this->_sql_valid_product('`p`'));

            //when host_plan has no products we still want it in the list
            //$this->db->or_where("p.id is null");
            $this->db->where_in('d.id',array_transform($host_plans,false,'id'));
            $this->db->group_by("d.id");

            //$this->db->order_by($orderby, $order);
            //$this->db->limit($start, $amount);

            $query=$this->db->get();
            $d=array_transform($query->result_array(),'id','num_of_products');
            foreach($host_plans as $key=>$value)
            {
                $host_plans[$key]['num_of_products']=isset($d[$value['id']]) ? $d[$value['id']] : 0;
            }
            //echo $this->db->last_query();
        }
        return result_array_sort($host_plans,$orderby,$order);

        //return $query->result_array();
    }

    /**
     * Reads list of host_plans from the database
     *
     * @return array of protected host_plans, every record contains array of (id, name)
     */
    function Get_host_plan_list($filter=false)
    {
//		fbq('Get_host_plan_list DO');
        $this->db->select("id, name");

        $this->db->from(db_prefix.'Host_plans');
        
        $this->db->order_by("name");

        $query=$this->db->get();
fb($query,'Get_host_plan_list') ;       
//		fbq('Get_host_plan_list');
        return $query->result_array();
    }

    /**
     * Validates data for protection
     *
     * @param array $data assosiative, contains the following fields:
     *        integer id host_plan id (0 means adding)
     *        string $name host_plan name
     * @param array $current_data array of the same structure - current state of the host_plan
     * @return array of errors, empty means no errors
     */
     function Validation_Errors($data, $current_data = false)
     {
        $result = array();
        $messages = $this->load_panel_vars(array());
// kgg debug
		
        $CI = &get_instance();
        $CI->load->model('protection_model');
		$CI->load->model('host_manager_model');
		
		$package_list = array();
		$package_list = $CI->host_manager_model->get_packages();
        
		if (($package_list == false) or !in_array($data['packages'], $CI->host_manager_model->get_packages()))
        {
            $result[] = $messages['error_messages']['packages'];
        }
        if (!$data['name'] || (mb_strlen($data['name']) > 255) || (mb_strlen($data['name']) < 1))
        {
            $result[] = $messages['error_messages']['name'];
        } 
		$ff = array(); 
		eregi('(http://)?(([[:alnum:]]*(\-)?)(\.)?)*', $data['type_domen'], $ff); 
		if (!isset($ff[0]))
			$ff = array('0'=>'');
		if (!$data['type_domen'] || (mb_strlen($data['type_domen']) > 255) || (mb_strlen($data['type_domen']) < 1))
        {
			$result[] = $messages['error_messages']['type_domen'];
        }
		else
			if ($data['type_domen'] != $ff[0])
			{
				$result[] = $messages['error_messages']['invalid_type_domen'];
			}
/*        if (!$data['http_path'] || (mb_strlen($data['http_path']) > 2048))
        {
            $result[] = $messages['error_messages']['http_path'];
        }
        if (!$data['id'] && $this->Is_protected($data['fs_path']))
        {//adding host_plan
            $result[] = $messages['temp_vars_set']['host_plan_is_already_protected'];
        }
        if ($data['id'] && $this->Is_protected($data['fs_path']) && ($data['fs_path'] != $current_data['fs_path']) )
        {//editing host_plan
            $result[] = $messages['temp_vars_set']['host_plan_is_already_protected'];
        }
        if (!$CI->protection_model->Is_ht_writable($data['fs_path']))
        {
            $result[] = $messages['temp_vars_set']['host_plan_is_not_protectable'];
        }
        if (mb_strlen($data['fs_path']) > 2048)
        {
            $result[] = $messages['temp_vars_set']['directory_path_is_too_long'];
        }*/
        return $result;
     }
     

    /**
     * Write info about protected host_plan to the database, calling either insert or update query
     *
     * @param array $data assosiative, contains the following fields:
     *        integer id host_plan id (0 means adding)
     *        string $method protection method
     *        string $name host_plan name
     *        string $http_path host_plan url
     *        string $fs_path host_plan path
     * @return integer: id of new record for insert, false if failure, true for successful update
     */
    function DB_Write($data)
    {
        if (!$data['id'])
        {//inserting
            unset($data['id']); //it will be auto-generated
 //           $data['last_protect_time']=date("Y-m-d h:i:s");
            $this->db->insert(db_prefix.'Host_plans', $data);
            return $this->db->insert_id();
        }
        else{
            return $this->db->update(db_prefix.'Host_plans', $data, "id = ".$data['id']);
        }
    }


    /**
     * Returns host_plan from database by id
     *
     * @param integer $id
     * @return mixed: associative array with host_plan or false if no records found
     */
    function DB_Read($id)
    {
        $query = $this->db->get_where(db_prefix.'Host_plans',array('id'=>$id));

        $result = $query->first_row('array');
        if (empty($result))
        {
            return false;
        }        
        return $result;
    }

    /**
     * Deletes host_plan from database by id (also deletes children records from host_plans_products)
     *
     * @param integer $id
     * @return boolean: success or failure
     */
    function DB_Delete($id)
    {
        $this->db->delete(db_prefix."Host_plans_products", array("host_plan_id"=>$id));
        $this->db->delete(db_prefix."Host_plans", array("id"=>$id));
        return (bool) $this->db->affected_rows();
    }

    function _sql_valid_user($table)
    {
        return "($table.approve=1 AND $table.activate=1 AND $table.deleted=0 AND $table.expired=0 AND $table.suspended=0)";
    }

    function _sql_valid_product($table)
    {
        return "(($table.`closed`=0)AND($table.`product_type`=".PRODUCT_HOSTED."))";
    }

    function Load_Users($uids)
    {
        //incoming parameters check
        if (is_array($uids) && empty($uids))
        {
            return array();
        }

        $this->db->select('u.login,u.pass,u.email');
        $this->db->from(db_prefix.'Users u');
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');

        if (is_array($uids))
        {//non-empty array of values
            $this->db->where_in('u.id',$uids);
        }
        else
        {//single value
            $this->db->where('u.id='.intval($uids));
        }

        //no need to ask for valid users only, because we need all of them!!!
        //$this->db->where($this->_sql_valid_user('`as`'));

        $query = $this->db->get();
        $result = $query->result_array();
/*        $result = array();
        foreach ($query->result_array() as $row)
        {
            $result[$row['login']] = $row['pass'];
        }
*/        return $result;
    }

    function Load_Subscription_All_Info($subscr_id)
    {
        $this->db->select("*");
        $this->db->from(db_prefix.'Subscriptions as subscr');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
        $this->db->join(db_prefix.'Host_subscription as host_subscription', 'subscr.id=host_subscription.subscr_id', 'left');
        $this->db->join(db_prefix.'Users as u','protect.user_id=u.id','LEFT');
        $this->db->join(db_prefix.'Products as pr','protect.product_id=pr.id','LEFT');
        $this->db->join(db_prefix.'Products as pr','protect.product_id=pr.id','LEFT');
        $this->db->join(db_prefix.'Host_plans_products hp', "pr.id=hp.product_id", "left");
        $this->db->join(db_prefix.'Host_plans h', "h.id=hp.host_plan_id", "left");
        $this->db->where('subscr.id', $subscr_id);
        $this->db->where($this->_sql_valid_product('`pr`'));
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();
fb($result,'Load_Subscription_All');
        return $result;
    }

function Load_Subscription($subscr_id)
    {
        $this->db->select('user_id,product_id,subscr_id');
        $this->db->from(db_prefix.'Protection');
        $this->db->where('subscr_id='.intval($subscr_id));
        $query = $this->db->get();
        $result = $query->row_array();
fb($result,'Load_Subscription');
        return $result;
    }

    function Load_Host_plan_Users($did)
    {
        $this->db->select('u.id,u.login,u.pass');
        $this->db->from(db_prefix.'Host_plans_products dp');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('dp.host_plan_id='.intval($did));
        $this->db->group_by("u.login");
        $query = $this->db->get();
        $result = $query->result_array();
        fb($result,'Load_Host_plan_Users');
        return $result;
    }


    function Load_Subscription_Host_plans($subscr_id)
    {
        $this->db->select('d.*');
        $this->db->from(db_prefix.'Host_plans d');
        $this->db->join(db_prefix.'Host_plans_products dp','dp.host_plan_id = d.id','LEFT');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');

        //we are not joining Account_status, because we need user host_plans no matter what user status is

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.id='.intval($subscr_id));

        $query = $this->db->get();
        $result = $query->result_array();
   fb($result,'Load_Subscription_Host_plans');
        return $result;
    }


    function Load_User_Host_plans($uids, $exclude_subscription = false)
    {
        //incoming parameters check
        if (is_array($uids) && empty($uids))
        {
            $uids = 0;
        }

        $this->db->select('u.id as `uid`, u.login, u.email,u.pass,u.sec_code ,d.*');
        $this->db->from(db_prefix.'Host_plans d');
        $this->db->join(db_prefix.'Host_plans_products dp','dp.host_plan_id = d.id','LEFT');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //we are not joining Account_status, because we need user host_plans no matter what user status is

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->group_by("uid,d.id");
        $this->db->where('s.status=1');

        if ($exclude_subscription > 0)
        {
            $this->db->where('s.id <>'.intval($exclude_subscription));
        }

        //one user or several users?
        if (is_array($uids))
        {//non-empty array of values
            $this->db->where_in('u.id',$uids);
        }
        else
        {//single value
            $this->db->where('u.id='.intval($uids));
        }

        $query = $this->db->get();
        $result = $query->result_array();
        
/*        $result = array();
        foreach ($query->result_array() as $row)
        {
            if (!array_key_exists($row['id'], $result))
            {
                $result[$row['id']] = $row;
                unset ($result[$row['id']]['uid']); //this data is incomplete and not needed, valid and complete data is stored in 'users'
                $result[$row['id']]['users'] = array();
            }
            $result[$row['id']]['users'][] = $row['uid'];
        }
*/
        fbq('Load_User_Host_plans');
        fb($result,'Load_User_Host_plans');
        return $result;
    }


    function Is_Access_To_Host_plan_Allowed($uid,$did)
    {
        $this->db->select('u.id');
        $this->db->from(db_prefix.'Host_plans_products dp');
        $this->db->join(db_prefix.'Protection p','dp.product_id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('dp.host_plan_id='.intval($did));
        $this->db->where('u.id='.intval($uid));
        $this->db->group_by("u.id");
        $query = $this->db->get();
//        admin_log('debug', array('sql'=>$this->db->last_query(),'did'=>$did,'uid'=>$uid));
        return ( $query->num_rows() > 0 );
    }


    function Is_Access_To_Product_Allowed($pid,$uid)
    {
        $this->db->select('u.id');
        $this->db->from(db_prefix.'Products prod');
        $this->db->join(db_prefix.'Protection p','prod.id = p.product_id','LEFT');
        $this->db->join(db_prefix.'Users u','p.user_id=u.id','LEFT');

        //checking for valid user
        $this->db->join(db_prefix.'Account_status `as`','`as`.user_id=u.id','LEFT');
        $this->db->where($this->_sql_valid_user('`as`'));

        //checking for valid product
        $this->db->join(db_prefix.'Products pr','p.product_id=pr.id','LEFT');
        $this->db->where($this->_sql_valid_product('`pr`'));

        //checking for valid subscription
        $this->db->join(db_prefix.'Subscriptions s','p.subscr_id = s.id','LEFT');
        $this->db->where('s.status=1');

        $this->db->where('prod.id='.intval($pid));
        $this->db->where('u.id='.intval($uid));
        $this->db->group_by("u.id");
        $query = $this->db->get();
//        admin_log('debug', array('sql'=>$this->db->last_query(),'pid'=>$pid,'uid'=>$uid));
        return ( $query->num_rows() > 0 );
    }


    /**
     * Loads language variables for the panel
     *
     * @param array $data array to load variables into
     * @return array $data with loaded variables
     */
    function Load_panel_vars($data)
    {
        //Error messages
        $error_messages = array(); //"id","name","type_domen","packages"
        $error_messages['name'] = "<{admin_host_plans_add_host_plan_name_is_empty}>";
        $error_messages['type_domen'] = "<{admin_host_plans_add_type_domen_host_plan_url_can_not_be_is_empty}>";
		$error_messages['invalid_type_domen'] = "<{admin_host_plans_add_type_domen_host_plan_url_is_invalid}>";
        $error_messages['packages'] = "<{admin_host_plans_add_packages_host_plan_can_not_be_is_empty}>";
        $error_messages['validation_failed'] = "<{admin_host_plans_add_unable_validation_failed_host_plan}>";
        $error_messages['insert_failed'] = "<{admin_host_plans_add_db_unable_to_insert}>";
        $error_messages['update_failed'] = "<{admin_host_plans_add_db_unable_to_update}>";
        $error_messages['delete_failed'] = "<{admin_host_plans_add_db_unable_to_delete}>";
        $error_messages['assotiated_products'] = array('text'=>"<{admin_host_plans_add_protection_assotiated_products_exist}>",'display'=>false);
        $error_messages['host_plan_has_been_checed_err_settings'] = array('display'=>false,'text'=>'<{host_plan_has_been_checed_err_settings}>');  
        

        //Temp_vars for js
        $temp_vars_set= array();
        $temp_vars_set['cancelText']="<{admin_host_plans_msg_cancel}>";
        $temp_vars_set['are_you_sure']="<{admin_host_plans_msg_are_you_sure}>";
        $temp_vars_set['panel_script']=base_url()."js/admin/host_plans/host_plan.js";            
        $temp_vars_set['host_plan_is_not_protectable'] = "<{admin_host_plans_add_protection_host_plan_is_not_protectable}>";
        $temp_vars_set['host_plan_is_already_protected'] = "<{admin_host_plans_add_protection_host_plan_is_already_protected}>";
        $temp_vars_set['host_plan_path_is_too_long'] = "<{admin_host_plans_add_protection_host_plan_path_is_too_long}>";
        
        $temp_vars_set['reprotect_begin']="<{admin_host_plans_reprotect_begin}>";
        $temp_vars_set['reprotect_progress']="<{admin_host_plans_reprotect_progress}>";
        $temp_vars_set['reprotect_end']="<{admin_host_plans_reprotect_end}>";

        //Green messages
        $ok_messages = array();
        $ok_messages['host_plan_has_been_added'] = "<{admin_host_plans_host_plan_has_been_added}>";
        $ok_messages['host_plan_has_been_updated'] = "<{admin_host_plans_host_plan_has_been_updated}>";
        $ok_messages['host_plan_has_been_deleted'] = "<{admin_host_plans_host_plan_has_been_deleted}>";
        $ok_messages['host_plan_has_been_reprotected'] = "<{admin_host_plans_host_plan_has_been_reprotected}>";
        $ok_messages['host_plan_has_been_checed_ok'] = array('display'=>false,'text'=>'<{admin_host_plans_host_plan_has_been_reprotected}>');        

        $data['temp_vars_set'] = $temp_vars_set;
        $data['ok_messages'] = $ok_messages;
        $data['error_messages'] = $error_messages;

        return $data;
    }

    function get_product_host_plans($id)
    {
        $id=(int)$id;

        $this->db->select("host_plans_products.host_plan_id as id, host_plans.name");

        $this->db->from(db_prefix.'Host_plans as `host_plans`');

        $this->db->join(db_prefix.'Host_plans_products as `host_plans_products`',
        "host_plans_products.host_plan_id = host_plans.id AND host_plans_products.product_id=$id");


        $query=$this->db->get();
fbq('Products get_product_host_plans');


        if($query->num_rows())
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }
	
    function insert_host_subscription($subscription_id,$name_domen="",$upd=false)
	{
        if ($subscription_id > 0)
		{
			if ($upd===false)
			{
				$this->db->insert(db_prefix.'Host_subscription',array(
				'subscr_id'=>$subscription_id,
				'name_domen' => $name_domen	));
			}
			else 
			{
				$this->db->where(array('subscr_id'=>$subscription_id));
				$this->db->update(db_prefix.'Host_subscription',array('name_domen' => $name_domen));
			}
			if( $this->db->affected_rows()!=1 )
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		return FALSE;

	}
	
	
	function _get_product_links($pid)
    {
        $pid=(int)$pid;

        $this->db->select("host_plans.*");
        $this->db->from(db_prefix."Host_plans as host_plans");

        $this->db->join(db_prefix."Host_plans_products as host_plans_products",
                                        "host_plans_products.product_id=$pid and host_plans_products.host_plan_id=host_plans.id");
        $q=$this->db->get();

        if($q->num_rows())
        {
            return $q->result_array();
        }
        return false;
    }


    function get_host_subscr_info($subscription_id)
    {
        $subscription_id = intval($subscription_id);
        if( $subscription_id<=0 )
        {
            return false;
        }

        $this->db->select("*");
        $this->db->from(db_prefix.'Subscriptions as subscr');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
        $this->db->join(db_prefix.'Host_subscription as host_subscription', 'subscr.id=host_subscription.subscr_id', 'left');
        $this->db->where('subscr.id', $subscription_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if( $query->num_rows() > 0 )
        {
            return $query->result_array();
        }

        return false;
    }

    
/**
 * Enter description here...
 *
 * @param unknown_type $domainname
 */
    function get_whois($domainname)
    {
    	
    	$status = array();
    	
//    	$default_whois_servers = "whois.crsnic.net";
//    	$default_whois_servers = "whois.internic.net";

		$default_whois_servers = config_get('system','hosting','default_whois_servers');
    	
/**
 * @todo add parameters pattern for detect avalable, not found and EXCEEDED
 * 
 * 
 */
    	//Add your whois server here Or do notihing
		$whois_servers = config_get('system','hosting','whois_servers');
		
/*    	$whois_servers = Array(
		'com' => 'whois.internic.net',
		'net' => 'whois.internic.net',
		'org' => 'whois.pir.org',
		'info' => 'whois.afilias.net',
		'biz' => 'whois.nic.biz',
		'us' => 'whois.nic.us',
		"coop"=>"whois.nic.coop",
		"museum"=>"whois.museum",
		"info"=>"whois.afilias.net",
		"name"=>"whois.nic.name",
		"gov"=>"whois.nic.gov",
		"ru"=>"whois.ripn.net",
		"su"=>"whois.ripn.net",
		"tw"=>"whois.twnic.net",
		"tv"=>"whois.nic.tv",
		"ua"=>"whois.net.ua",
		"uk"=>"whois.nic.uk",
		"net.ru"=>"whois.ripn.net",
		"org.ru"=>"whois.ripn.net",
		"pp.ru"=>"whois.ripn.net",
		"spb.ru"=>"whois.relcom.ru",
		"msk.ru"=>"whois.relcom.ru",
		"ru.net"=>"whois.relcom.ru",
		"yes.ru"=>"whois.regtime.net",
		"uk.com"=>"whois.centralnic.com",
		"gb.net"=>"whois.centralnic.com",
		"eu.com"=>"whois.centralnic.com"
		);
*/
/*        config_set($whois_servers,'system','hosting','whois_servers');
        config_set($default_whois_servers,'system','hosting','default_whois_servers');
*/        
		$whois_error = Array('Can\'t get information');
		
		//status message
		$statusmsg[0] = "is available!";
		$statusmsg[1] = "Domain name length < 3 letter.";
		$statusmsg[2] = "Domain name cannot start or end with '-' and '.' .";
		$statusmsg[3] = "Please use letters , numbers and - only.";
		$statusmsg[4] = "Can't lookup. Please try later";
		$statusmsg[5] = "This server exceeded whois server quota. Please try again later.";
		$statusmsg[6] = "is taken";
		$statusmsg[7] = "Domain name is set in order";
		
		
		//-------------------------Start main program-------------------------
		
		if ($domainname != '')
		{
		

		$domainname = strtolower(trim($domainname));
		
		//Strip html
		$domainname = strip_tags($domainname);
		
		//Strip www. and http://
		$domainname = str_replace("www.", "", $domainname);
		$domainname = str_replace("http://", "", $domainname);
		
		//Replace Space
		$domainname = str_replace(" ", "", $domainname);
		
			
		//check valid or not
		$isvalid = $this->check_valid_address($domainname);
		//Check tld
		if($isvalid ==0){
			
			if (!is_array($whois_servers))
			{
				return false;
			}
			$tld = array_keys($whois_servers);
fb($tld,'tld');
			
			$domainstatus = array();
			
			for($i=0;$i<count($tld);$i++)
			{
		 		 $domainstatus[$i] = $this->whois($tld[$i],$domainname ,$whois_servers[$tld[$i]],$whois_error);
			}
		
		//-----------------------Prepare Results ----------------------------
		
			for($i=0;$i<count($tld);$i++)
			{
				@session_start();
                $_SESSION['hosted_domains']=isset($_SESSION['hosted_domains']) ? $_SESSION['hosted_domains'] : array();
                $_SESSION['hosted_domains'][$domainname.".".$tld[$i]]=$domainstatus[$i];
                if($domainstatus[$i] > 0)
				{
				
				
				//Domain Taken or Error
				
				        $status[$i] =  ".".$tld[$i]." ";
						$status[$i] .= $statusmsg[$domainstatus[$i]];
						$status[$i] .= " <a href=\"http://www.$domainname.$tld[$i]\">Website</a>";
				
				}
				else
				{
				
					//Domain Available
					$status[$i] = $domainname . ".".$tld[$i] . " is available!";
				
				}
			}
				
		}elseif($isvalid ==7)
		{
			//Check domain full name
            $server = $default_whois_servers;
            
//			ereg(".+\.(.+)\.{0,1}",$domainname,$backrefs);
            $backrefs = explode(".", $domainname);
			unset($backrefs[0]);
			$backrefs =  implode(".", $backrefs);
fb($backrefs,"backrefs");
			
			$tld_set = $backrefs;

			if(isset($whois_servers[$backrefs]))
			{
				$server = $whois_servers[$backrefs];
			} 
			else 
				do
				{
					
		            $backrefs = explode(".", $backrefs);
					unset($backrefs[0]);
					$backrefs =  implode(".", $backrefs);
					//if(isset($backrefs[1]) && strlen($backrefs[1])>0 && isset($whois_servers[$backrefs[1]]))
					if(isset($whois_servers[$backrefs]))
					{
						$server = $whois_servers[$backrefs];
						break;
					}
				} while (ereg(".",$backrefs));
			
			if ($server != '')
			{
		        $domainstatus_set = $this->whois($tld_set,$domainname ,$server,$whois_error);
                @session_start();
                $_SESSION['hosted_domains']=isset($_SESSION['hosted_domains']) ? $_SESSION['hosted_domains'] : array();
                $_SESSION['hosted_domains'][$domainname]=$domainstatus_set;
                
			}
			else 
			{
				return false;
			}
		    
			if($domainstatus_set > 0)
			{
				//Domain Taken or Error
			
		        $status =  ".".$tld_set." ";
				$status .= $statusmsg[$domainstatus_set];
				$status .= " <a href=\"http://www.$domainname\">Website</a>";
		
			}
			else
			{
			
				//Domain Available
				$status = $domainname . " : " . $statusmsg[$isvalid];
                @session_start();
                $_SESSION['hosted_domains']=isset($_SESSION['hosted_domains']) ? $_SESSION['hosted_domains'] : array();
                $_SESSION['hosted_domains'][$domainname]=$isvalid;
			}
			   			
		}
		else 
		{
		
			//not valid domain
			$status = $statusmsg[$isvalid];
            @session_start();
            $_SESSION['hosted_domains']=isset($_SESSION['hosted_domains']) ? $_SESSION['hosted_domains'] : array();
            $_SESSION['hosted_domains'][$domainname]=$isvalid;
		}
		
		}
fb($status,'get_whois()');		
		return $status;
    	
    } // get_whois

/**
 * Enter description here...
 *
 * @param string $domainname
 * @return integer
 */    
		function check_valid_address($domainname) {
		
		//Check domain length > 2 || < 63 characters
		    if (strlen($domainname) < 3){
		        return 1;
		//Check domain cannot start with - and 
		    } elseif (ereg("^-|-$|^\.|\.$", $domainname)){
		       return 2;
		//Domain is set in order
		    } elseif (ereg("\.", $domainname)){
		        return 7;
		//Letters , numbers and - _ only
		    } elseif (!ereg("([a-z]|[A-Z]|[0-9]|-){".strlen($domainname)."}", $domainname)){
		        return 3;
		    }
		  return 0;
		}
		
/**
 * Enter description here...
 *
 * @param unknown_type $tld
 * @param unknown_type $getdomainname
 * @param unknown_type $whois_servers
 * @param unknown_type $whois_available
 * @param unknown_type $whois_error
 * @param unknown_type $statusmsg
 * @return unknown
 */
		function whois ($tld, $getdomainname,$select_server,$whois_error)
		{
		
				
		//Split .com .net .org or .* (Split text after .)
		$getdomainname = explode(".", $getdomainname);
		
		//Join domain with tld (.com,.net,.org, .*)
		$domainname = $getdomainname[0] . "." . $tld;
		
		//Connect to selected whois server
		$sock = @fsockopen($select_server,43, $errno, $errstr, 30);
fb($select_server,'$select_server');
fb($errno,'$errno');
fb($errstr,'$errstr');

				
		//Initial value for display status message in config.inc.php
		$domainstatus = 0;
		$result = '';
				
			if(!$sock) {
			//Can't connect to Server
			$domainstatus = 4;
			}else{
			$send_request = @fputs($sock,"$domainname\r\n");
fb($send_request,'$send_request');
			
				if(!$send_request) {
				//Unable to send request
				$domainstatus = 4;
				}else{
				while(!feof($sock)) {
				$result .= fgets($sock,128);
				}
		
		$result = str_replace("\n", "<br>", $result);
fb($result,'$result');
		
		
		//Check error or Available
		for($i=0;$i<count($whois_error);$i++){
		
		if(@eregi($whois_error[$i],$result)) {
		//error?
		$domainstatus = 4;
		}
		
		}
		
		//Check excedded quota from whois server (.org limited 4 query per minute/server ip) :(
		if(@eregi("EXCEEDED",$result)) {
		//Exceeded server quota?
		$domainstatus = 5;
		}
		
		//No error
		if($domainstatus == 0){
			//Check Available
	
			if(eregi("No match for", $result)
			 || eregi("Not found", $result)
			 || eregi("No entries found for", $result)) 
			{
				//Available
				$domainstatus = 0;
			}else{
				$domainstatus = 6; //taken
			}
		
		}
		
		
		
				}
		@fclose($sock);
		
				}
		return $domainstatus;
		
		}
    

}
?>
