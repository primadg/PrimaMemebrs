<?php
/**
 * 
 * THIS FILE CONTAINS Payment_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH PAYMENT
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Payment_model extends Model {

	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Payment_model()
    {
        parent::Model();
    }
    /**
     * Check is product available for buy
     *
     * @global array
     * @return mixed
     */
    function Checkout()
    {
        $CI=&get_instance();
        $CI->user_auth_model->is_auth();
        //***********Functionality limitations***********
        if(($functionality_enabled_error=Functionality_enabled('admin_member_info_modify',intval($CI->user_auth_model->uid)))!==true)
        {
            return true;
        }
        //*******End of functionality limitations********
        
        global $_helper_CONFIG;
        $CI->load->model('cart_model');
        $CI->load->model('product_model');
        $payment_system_id = intval($CI->input->post('payment_system'));
        if( !isset($payment_system_id) or intval($payment_system_id)<0 )
        {
            $payment_system_id = session_get('payment_system_id');
        }
        $products = $CI->cart_model->product_list();
fb($products,'checkout');
        if( isset($products) and is_array($products) and sizeof($products)>0 )
        {
            session_set('checkout',1);
            session_set('payment_system_id',$payment_system_id);
        }
        else
        {
           redirect_page('<{user_redirect_title}>','market/sale');
           return true;
        }

        // check is_product_available_for_buy
        foreach( $products as $product_id=>$product_info )
        {
            //log this to the "User_logs" table in DB
            $CI->load->model('user_log_model');
            $CI->user_log_model->set($CI->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/payment", $product_id);
            //_log this to the "User_logs" table in DB

            if ( !$CI->product_model->is_product_available_for_buy($product_id) )
            {
                // clear shopping cart
                $CI->cart_model->clear_cart();
                // redirect to empty cart
                redirect_page('<{user_redirect_title}>','cart');
                return true;
            }
        }

        //check_user_auth();
        $CI->load->model('user_auth_model');
        $current_url = base_url().'cart';
        $current_url_64 = encode_url($current_url);
        if( !$CI->user_auth_model->is_auth() )
        {
            redirect('user/login/1/'.$current_url_64);
            exit(0);
        }

        $payments    = config_get('payment');
        $payments[0] = Array('name'=>'Free payment', 'controller'=>'free_payment');
        if( isset($payments) and is_array($payments) and sizeof($payments)>0 )
        {
            if( isset($payments[$payment_system_id]['controller']) and !empty($payments[$payment_system_id]['controller']) )
            {
                $redirect_url = $payments[$payment_system_id]['controller'];
                redirect_page('<{user_redirect_title}>',$redirect_url);
                return true;
            }
        }
        redirect_page('<{user_redirect_title}>','cart');
    }    

    /**
     * This function send email once for almost expired and not closed subscriptions
     *
     * @return void
     *
     * @author onagr
     * @copyright 2008
     */
    function inform_almost_expired_subscriptions()
    {
        $period=intval(config_get('SYSTEM', 'CONFIG', 'member_exp_subscr_notif_period'));
        if($period>0)
        {
            $this->db->select("subscr.id as subscr_id, user.id as user_id, user.language_id as language_id, product.id as product_id, subscr.expire_date as expire_date");
            $this->db->from(db_prefix.'Subscriptions as subscr');
            $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
            $this->db->join(db_prefix.'Users as user', 'protect.user_id=user.id', 'left');
            $this->db->join(db_prefix.'Products as product', 'protect.product_id=product.id', 'left');
            $this->db->where("NOW()<subscr.expire_date");
            $this->db->where("subscr.expire_date<=DATE_ADD(NOW(),INTERVAL '".$period."' DAY)");
            $this->db->where("subscr.status=1");
            $this->db->where("subscr.almost_expired!=1");
            $query = $this->db->get();
            $subscr=$query->result_array();

            foreach($subscr as $key=>$value)
            {
                $result=send_system_email_to_user($value['user_id'],'user_subscription_almost_expired',array('expired_product_name'=>array('object_id'=>$value['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($value['expire_date'])));

                if($result)
                {
                    $this->db->update(db_prefix.'Subscriptions', array('almost_expired'=>1), array('id' =>$value['subscr_id']));
                }
            }
        }
    }

    /**
     * This function send email once for expired and not closed subscriptions
     *
     * @return void
     *
     * @author onagr
     * @copyright 2008
     */
    function inform_expired_subscriptions()
    {
        $this->db->select("subscr.id as subscr_id, user.id as user_id, user.login as user_login, user.language_id as language_id, product.id as product_id, subscr.expire_date as expire_date");
        $this->db->from(db_prefix.'Subscriptions as subscr');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
        $this->db->join(db_prefix.'Users as user', 'protect.user_id=user.id', 'left');
        $this->db->join(db_prefix.'Products as product', 'protect.product_id=product.id', 'left');
        $this->db->where("subscr.expire_date<=NOW()");
        $this->db->where("subscr.status=1");
        $query = $this->db->get();
        $subscr=$query->result_array();
        
        /* echo "<pre>";
        print_r($subscr);
        echo "</pre>"; */
        
        if(count($subscr)>0)
        {

            $this->db->where('expire_date<=NOW()');
            $this->db->where('status=1');
            $this->db->update(db_prefix.'Subscriptions', array('status' => 3));
            foreach($subscr as $key=>$value)
            {
                Protection_event('SUBSCRIPTION_EXPIRED',false,$value['subscr_id']);
                
                $result=send_system_email_to_user($value['user_id'],'user_subscription_expired',array('expired_product_name'=>array('object_id'=>$value['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($value['expire_date'],false)));

                $result=send_system_subscription_to_admins('admin_subscription_ended',array('user_login'=>$value['user_login'],'expired_product_name'=>array('object_id'=>$value['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($value['expire_date'],false)));
            }
        }
    }

    /**
     * This function returns all data from DB tables "Subscription" and "Protection"
     *
     * @param integer $subscription_id
     * @return mixed
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function get_subscr_info($subscription_id)
    {
        $subscription_id = intval($subscription_id);
        if( $subscription_id<=0 )
        {
            return false;
        }

        $this->db->select("*");
        $this->db->from(db_prefix.'Subscriptions as subscr');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
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
	 * Check is subscription exist
	 *
	 * @param mixed $subscription_id
	 * @return boolean
	 */
    function is_subscr_exist($subscription_id)
    {
        $subscription_id = intval($subscription_id);
        $this->db->select('id');
        $this->db->from(db_prefix."Subscriptions");
        $this->db->where('id',$subscription_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if( $query->num_rows() > 0 )
        {
            return true;
        }

        return false;
    }

	/**
	 * Set trial period of product for user
	 *
	 * @param mixed $user_id
	 * @param mixed $product_id
	 * @return boolean
	 */
    function set_used_trial($user_id,$product_id)
    {
        $user_id = intval($user_id);
        $product_id = intval($product_id);

        if( $user_id<=0 or $product_id<=0 )
        {
            return false;
        }

        $query = $this->db->query("INSERT IGNORE INTO ".db_prefix."Used_trials (`user_id`, `product_id`) VALUES ($user_id, $product_id)");
        //Active Record mechanism is not used because the keyword IGNORE is not possible in query
        //$query = $this->db->insert(db_prefix.'Used_trials',array('user_id'=>$user_id,'product_id'=>$product_id));

        if( $query )
        {
            return true;
        }
        return false;
    }

	/**
	 * Check if user has trial products
	 *
	 * @param mixed $user_id
	 * @param mixed $product_id
	 * @return boolean
	 */
    function check_used_trial($user_id,$product_id)
    {

        $user_id = intval($user_id);
        $product_id = intval($product_id);

        if( $user_id<=0 or $product_id<=0 )
        {
            return false;
        }

        $this->db->select('user_id');
        $this->db->from(db_prefix.'Used_trials');
        $this->db->where('user_id',$user_id);
        $this->db->where('product_id',$product_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if( $query->num_rows()>0)
        {
            return true;
        }

        return false;

    }

	/**
	 * Delete user's trial product   
	 *
	 * @param mixed $user_id
	 * @param mixed $product_id
	 * @return boolean
	 */
    function del_used_trial($user_id,$product_id)
    {
        $user_id = intval($user_id);
        $product_id = intval($product_id);

        if( $user_id<=0 or $product_id<=0 )
        {
            return false;
        }

        $this->db->from(db_prefix.'Used_trials');
        $this->db->where('user_id',$user_id);
        $this->db->where('product_id',$product_id);
        $query = $this->db->delete();

        if( $query->affected_rows()==1 )
        {
            return true;
        }

        return false;
    }

	/**
	 * Check possibility of subscription
	 *
	 * @param mixed $product_id
	 * @param mixed $user_id
	 * @return boolean
	 */
    function is_subscr_available($product_id=0,$user_id=0)
    {
        $product_id = intval($product_id);
        $user_id = intval($user_id);

        if( $product_id<=0 or $user_id<=0 )
        {
            return false;
        }

        	if (Functionality_enabled('admin_product_hosted')===true)
			{
                $CI=&get_instance();
				$CI->load->model("host_plans_model");
				$CI->load->model("product_model");
				$host_plans = $CI->host_plans_model->Load_User_host_plans($user_id);
fb($host_plans,"host_plans ".__FUNCTION__);
				if (is_array($host_plans) && count($host_plans)>0 && PRODUCT_HOSTED==$CI->product_model->_is_product_typeof($product_id))
                {
/**
 * @todo one user - one hosting plan (while)
 */                	
                    return true;
                }
			}
        
        $this->db->select('id');
        $this->db->where('Protection.user_id',$user_id);
        $this->db->where('Protection.product_id',$product_id);
        $this->db->where(' (Subscriptions.status = 1 or Subscriptions.status = 2) '); //active or pending

        $this->db->from(db_prefix.'Protection as Protection');
        $this->db->join(db_prefix.'Subscriptions as Subscriptions','Protection.subscr_id = Subscriptions.id','left');
        $this->db->limit(1);
        $query = $this->db->get();

        //echo "<br><Br>".$this->db->last_query()."<br><br>";

        if( $query->num_rows() > 0 )
        {
            return true;
        }

        return false;
    }

	/**
	 * Set user payment info
	 *
	 * @param array $POST
	 * @param array $additional_fields
	 * @return mixed
	 */
    function set_user_info($POST,$additional_fields)
    {
        if( !isset($POST) or !is_array($POST) or sizeof($POST)<=0 )
        {
            return false;
        }
        $data = array();
        // standart fields
        $data['billing_name'] = (isset($POST['billing_name']))?$POST['billing_name']:'';
        $data['street'] = (isset($POST['street']))?$POST['street']:'';
        $data['city'] = (isset($POST['city']))?$POST['city']:'';
        $data['state_code'] = (isset($POST['state']))?$POST['state']:'';
        $data['zip'] = (isset($POST['zip']))?$POST['zip']:'';
        $data['country_code'] = (isset($POST['country']))?$POST['country']:'';
        $data['phone'] = (isset($POST['phone']))?$POST['phone']:'';
        // standart fields
    fb($data,'SET_USER_INFO');
        // additional fields
        $data['additional'] = '';
        if( isset($additional_fields) and is_array($additional_fields) and sizeof($additional_fields)>0 )
        {
            $data['additional'] = serialize($additional_fields);
        }
        // _additional fields

        $this->db->insert(db_prefix."User_info",$data);
        if( $this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }

        return false;

    }

	/**
	 * Return amount with discount
	 *
	 * @param mixed $amount
	 * @param mixed $discount_type
	 * @param mixed $discount_value
	 * @return float
	 */
    function use_discount($amount, $discount_type, $discount_value )
    {
        $amount = floatval($amount);
        $discount_type = intval($discount_type);
        $discount_value = floatval($discount_value);

        if( $amount <=0 or $discount_value<=0 ) { return $amount; }
        if( $discount_type!==1 and $discount_type!==2 ){ return $amount; }
        if( $discount_type == 1 and $discount_value > 99 ) { return $amount; }
        if( $discount_type == 2 and $discount_value >= $amount ) { return $amount; }

        if( $discount_type == 1 /*percent*/ )
        {
            $amount = floatval($amount) - floatval((floatval($amount)*intval($discount_value))/100);
        }
        elseif( $discount_type == 2 /*number*/ )
        {
            $amount = floatval($amount) - floatval($discount_value);
        }

        return round($amount, 2);
    }

	/**
	 * Get product info
	 *
	 * @param mixed $product_id
	 * @param mixed $user_id
	 * @param mixed $user_force_trial
	 * @return mixed
	 */
    function get_product_info($product_id=0,$user_id=0,$user_force_trial=0)
    {
        $product_id = intval($product_id);
        $do_not_use_trial=0;
        $user_id = intval($user_id);
        if( $product_id<=0 )
        {
            return false;
        }

        if( $user_id > 0)
        {
            if( $this->check_used_trial($user_id,$product_id)===true )
            {
                    $do_not_use_trial=1;
            }
            else
            {
                if( intval($user_force_trial) <=0 )
                {
                    $do_not_use_trial=1;
                }
            }
        }

        $trial_price = 'trial.price';
        $trial_period_type = 'trial.period_type';
        $trial_period_value = 'trial.period_value';
        if( $do_not_use_trial>0)
        {
            $trial_price = '0';
            $trial_period_type = "''";
            $trial_period_value = 0;
        }

        $this->db->select('
                            products.id as product_id,
                            products.group_id as group_id,
                            products.blocked as blocked,
                            products.is_recouring as is_recouring,
                            products.image as image,
							products.product_type as product_type,
                            product_discount.discount as discount,
                            product_discount.discount_type as discount_type,
                            prices.day as day,
                            prices.month as month,
                            prices.month3 as month3,
                            prices.month6 as month6,
                            prices.year as year,
                            prices.unlimit as year5,
                            prices.unlimit as unlimit,
                            '.$trial_price.' as trial_price,
                            '.$trial_period_type.' as trial_period_type,
                            '.$trial_period_value.' as trial_period_value
                          ',FALSE);
        $this->db->where('products.id',$product_id);
        $this->db->from(db_prefix.'Products products');
        $this->db->join(db_prefix.'Product_discount product_discount','products.id = product_discount.id','left');
        $this->db->join(db_prefix.'Prices prices','products.id = prices.id','left');
        $this->db->join(db_prefix.'Trial trial','products.id = trial.id','left');
        $this->db->limit(1);
        $query = $this->db->get();

        if( $query->num_rows() > 0)
        {
            $CI =& get_instance();
            $CI->load->model("lang_manager_model");
            $t=$CI->lang_manager_model->combine_with_language_data($query->result_array(),3,array('name'=>'group_name'),'group_id',false,false,&$add_params);
            return $CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name','descr'=>'product_descr'),'product_id',false,false,&$add_params);            
        }

        return false;
    }
	/**
	 * Create subscription 
	 *
	 * @param mixed $product_id
	 * @param mixed $user_id
	 * @param string $period
	 * @param mixed $type
	 * @param mixed $user_info_id
	 * @param string $currency_code
	 * @return mixed
	 */
    function create_subscr( $product_id=0,$user_id=0,$period='',$type=1,$user_info_id=0,$currency_code='')
    {
        $CI = &get_instance();
        $CI->load->model('coupons_model');

        $product_id = intval($product_id);
        $user_id = intval($user_id);
        $user_info_id = intval($user_info_id);
        $user_force_trial = 0;
        $type = intval($type);

        if( !isset($currency_code) or empty($currency_code))
        {
            $currency_code = config_get('system','config','currency_code');
        }

        $final_products_info = session_get('final_products_info');
        $user_force_trial = (isset($final_products_info['user_force_trial']))?intval($final_products_info['user_force_trial']):0;

        if( $product_id<=0 or $user_id<=0 or $type<=0)
        {
            return false;
        }

        if( $this->is_subscr_available($product_id,$user_id) === true )
        {
            return false; // active subscription is already available
        }

        if( !in_array($period,array('day','month','month3','month6','year','year5')) )
        {
            return false;
        }

        $period1 = 0;
        $period2 = '';
        if( $period == 'day' ){ $regular_period_value = 1; $regular_period_type='day'; }
        if( $period == 'month' ){ $regular_period_value = 1; $regular_period_type='month'; }
        if( $period == 'month3' ){ $regular_period_value = 3; $regular_period_type='month'; }
        if( $period == 'month6' ){ $regular_period_value = 6; $regular_period_type='month'; }
        if( $period == 'year' ){ $regular_period_value = 1; $regular_period_type='year'; }
        if( $period == 'year5' ){ $regular_period_value = 5; $regular_period_type='year'; }

        /* get product  info */
        $product_info = $this->get_product_info($product_id,$user_id,$user_force_trial);
        if( $product_info === false)
        {
            return false;
        }
        $product_info = $product_info[0];
        /*
        cdate
        type [ 1 - onetyme | 2 - recouring ]
        status (1 - active | 2 - pending | 3 - inactive)
        free [1 - free]
        */
        $free = 0;
        $regular_price = floatval(0);

        $trial_period_type = '';
        $trial_period_value = 0;
        $trial_price = floatval(0);

        if( isset($product_info['trial_price']) and floatval($product_info['trial_price'])>0 )
        {
            $trial_price  =  floatval($product_info['trial_price']);
        }
        if( isset($product_info['trial_period_type']) and in_array($product_info['trial_period_type'],array('day','month','year')) )
        {
            $trial_period_type  =  $product_info['trial_period_type'];
        }
        if( isset($product_info['trial_period_value']) and intval($product_info['trial_period_value'])>0 )
        {
            $trial_period_value  =  intval($product_info['trial_period_value']);
        }
        
        if( isset($product_info[$period]) )
        {
            if( floatval($product_info[$period])<=0 )
            {
                $free = intval(1);
            }
            elseif( floatval($product_info[$period])>0 )
            {
                $regular_price = floatval($product_info[$period]);
            }
        }

        if(
            isset($product_info['discount']) and floatval($product_info['discount'])>0
                and
            isset($product_info['discount_type']) and intval($product_info['discount_type'])>0
                and
            $free<=0
          )
        {
            $regular_price = $this->use_discount($regular_price,$product_info['discount_type'],$product_info['discount']);
        }

        if( isset($final_products_info['products']) and isset($final_products_info['products'][$product_id]) )
        {
            // if coupon code is available then use its discount
            $product_coupon_info = $CI->coupons_model->check_coupon($final_products_info['products'][$product_id]['coupon_code'], $product_id, $user_id);
            if ( $product_coupon_info['result']===true )
            {
                $coupon_type = $final_products_info['products'][$product_id]['coupon_type'];
                $coupon_value = $final_products_info['products'][$product_id]['coupon_value'];
                $regular_price = $this->use_discount($regular_price,$coupon_type,$coupon_value);
            }
        }

        if( $free>0 )
        {
            $regular_price = floatval(0);
        }


        $this->db->insert(db_prefix.'Subscriptions',array(
        'cdate'=>date('Y-m-d'),
        'expire_date'=>'',
        'status'=>'2'/*pending*/,
        'type' => $type,
        'user_info_id' => $user_info_id,
        'regular_period_type' => $regular_period_type,
        'regular_period_value' => $regular_period_value,
        'regular_price' => round($regular_price,2),
        'trial_period_type' => $trial_period_type,
        'trial_period_value' => $trial_period_value,
        'trial_price' => round($trial_price,2),
        'currency_code' => $currency_code
        ));

        if( $this->db->affected_rows()!=1 )
        {
            return false;
        }
        $subscription_id = intval($this->db->insert_id());
        if( $subscription_id<=0 )
        {
            return false;
        }
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===true)
        {
	        if ($product_info['product_type'] == PRODUCT_HOSTED)
			{
			    $CI->load->model("host_plans_model");
				if ( isset($final_products_info['products'][$product_id]['name_domen']) )
				{
					$ok_insert = $CI->host_plans_model->insert_host_subscription($subscription_id,$final_products_info['products'][$product_id]['name_domen']);
				}
				else
				{
					$ok_insert = $CI->host_plans_model->insert_host_subscription($subscription_id);
				}
	
				if( !$ok_insert )
				{
					$this->db->delete(db_prefix.'Subscriptions',array('id'=>intval($subscription_id)));
					return false;
				}
			}
        }
        // isert data into protection table
        $this->db->insert(db_prefix.'Protection',array(
                                            'user_id'=>intval($user_id),
                                            'product_id'=>intval($product_id),
                                            'subscr_id'=>intval($subscription_id))
                          );
        if( $this->db->affected_rows()!=1 )
        {
            $this->db->delete(db_prefix.'Subscriptions',array('id'=>intval($subscription_id)));
            return false;
        }
        // _isert data into protection table

        if( $free>0 )
        {
            $query_text = "
            UPDATE ".db_prefix."Subscriptions SET
            `status`=1,
            `cdate` = CURRENT_TIMESTAMP()";
            if( $period!='unlimited')
            {
                $query_text .= ',
                `expire_date` = DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL '.intval($regular_period_value).' '.$regular_period_type.'),
                `trial_price` = 0,
                `trial_period_type` = "",
                `trial_period_value` = 0
                ';
            }
            $query_text .= " WHERE id=".intval($subscription_id)."";
            $this->db->query($query_text);
            unset($query_text);
            
            
            if( $this->db->affected_rows()!=1 )
            {
                $this->db->where('id',$subscription_id);
                $this->db->delete(db_prefix.'Subscriptions');
                return false;
            }
            else
            {
                /**
                 * @todo need error handler
                 */
            	Protection_event('SUBSCRIPTION_STARTED',$user_id,$subscription_id);
            }
        }

        return $subscription_id;
    }

	/**
	 * Set subscription status to expired
	 *
	 * @param integer $sid
	 * @return mixed
	 */
    function end_subscr($sid)
    {
        $this->db->where('id',  $sid);
        $this->db->update(db_prefix.'Subscriptions',array('status'=>'3'));

        if( $this->db->affected_rows()==1)
        {
            Protection_event('SUBSCRIPTION_EXPIRED',false,$sid);
            return true;
        }
    }

	/**
	 * Continue subscription
	 *
	 * @param mixed $subscr_id
	 * @param string $expire_date
	 * @return boolean
	 */
    function continue_subscr($subscr_id=0,$expire_date='')
    {
        $subscr_id = intval($subscr_id);
        $date_part = explode('-',$expire_date);
        if( !is_array($date_part) or sizeof($date_part)!=3 )
        {
            return false;
        }

        if( checkdate($date_part[1],$date_part[2],$date_part[0]) )
        {
            $expire_date = $date_part[0].'-'.$date_part[1].'-'.$date_part[2];
        }
        else
        {
            return false;
        }

        $this->db->where('id',$subscr_id);
        $this->db->update(db_prefix.'Subscriptions',array('status'=>1,'expire_date'=>$expire_date, 'almost_expired'=>0));
        if( $this->db->affected_rows() ==1 )
        {
            // disable this product protection for user
                /**
                 * @todo need error handler
                 */
        	Protection_event('SUBSCRIPTION_STARTED',false,$subscr_id);
            return true;
        }

        return false;
    }

	/**
	 * Get user total subscription
	 *
	 * @param mixed $user_id
	 * @return mixed
	 */
    function get_user_total_subscr($user_id=0)
    {
        $user_id = intval($user_id);
        if( $user_id<=0 )
        {
            return false;
        }

        $this->db->select('COUNT(id) as num_rows');
        $this->db->where('Protection.user_id',$user_id);
        $this->db->where('Subscriptions.status','1'); //active

        $this->db->from(db_prefix.'Protection as Protection');
        $this->db->join(db_prefix.'Subscriptions as Subscriptions','Protection.subscr_id = Subscriptions.id','left');
        $query = $this->db->get();
        if( $query->num_rows()>0 )
        {
            $query_info = $query->row();
            $num_rows = intval($query_info->num_rows);
            if( $num_rows>0 )
            {
                return $num_rows;
            }
        }
        return false;
    }
    
	/**
	 * Create transaction
	 *
	 * @param mixed $subscription_id
	 * @param mixed $pay_system_id
	 * @param mixed $completed
	 * @param mixed $summ
	 * @param mixed $info
	 * @return mixed
	 */
    function create_transaction( $subscription_id, $pay_system_id, $completed=0, $summ=0 , $info='')
    {
        $subscription_id = intval($subscription_id);
        $pay_system_id = intval($pay_system_id);
        $summ = floatval($summ);
        $completed = intval($completed);

        if( $subscription_id<=0 or $pay_system_id<0 )
        {
            return false;
        }
        $data = array();
        $data['subscription_id'] = $subscription_id;
        $data['pay_system_id'] = $pay_system_id;
        $data['summ'] = floatval($summ);
        $data['completed'] = intval($completed);

        if( isset($info) and is_array($info) and sizeof($info)>0 )
        {
            $data['info'] = serialize($info);
        }

        $this->db->insert(db_prefix."Transactions",$data);

        if( $this->db->affected_rows()==1 )
        {
            return $this->db->insert_id();
        }

        return false;
    }

	/**
	 * Accept transaction
	 *
	 * @param mixed $trans_id
	 * @return boolean
	 */
    function accept_trans($trans_id)
    {
        $trans_id = intval($trans_id);
        if( $trans_id<=0 )
        {
            return false;
        }

        $this->db->where('id',$trans_id);
        $this->db->update(db_prefix.'Transactions',array('completed'=>1));
        if( $this->db->affected_rows()==1 )
        {
            return true;
        }
        return false;
    }

	/**
	 * Transaction failed
	 *
	 * @param mixed $trans_id
	 * @return boolean
	 */
    function fail_trans($trans_id)
    {
        $trans_id = intval($trans_id);
        if( $trans_id<=0 )
        {
            return false;
        }

        $this->db->where('id',$trans_id);
        $this->db->update(db_prefix.'Transactions',array('completed'=>0));
        if( $this->db->affected_rows()==1 )
        {
            return true;
        }

        return false;
    }


    /**
     * This function can be used to convert <{common_day}> into "day" e.g.
     *
     * @param string $period_type
     * @return string
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Convert_to_period_type($period_type='')
    {
        $regular_period_type = "";
        switch($period_type)
        {
            case "<{common_day}>":
                $regular_period_type = "day";
                break;
            case "<{common_month}>":
                $regular_period_type = "month";
                break;
            case "<{common_year}>":
                $regular_period_type = "year";
                break;
        }
        return $regular_period_type;
    }


    /**
     * Checks if the data in SESSION is up to date with date in DATABASE
     *
     * @param int $uid
     * @return bool
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Check_session_data_is_up_to_date($uid)
    {
        // if incorrect user ID
        if ( intval($uid)<=0 )
        {
            return false;
        }

        // get the data from session
        $products = session_get('products');
        $final_products_info = session_get('final_products_info');
        if (!isset($final_products_info)
            || sizeof($final_products_info)<=0
            || !isset($final_products_info['products'])
            || sizeof($final_products_info['products'])<=0
            || !isset($products)
            || sizeof($products)<=0)
        {
           return false;
        }

        // one product per payment (so we get only one product from array of products)
        reset($products);
        list($product_id, $product_information) = each($products);

        // format the regular period string to the format like "month3"
        $regular_period_type = $final_products_info['products'][$product_id]['regular_period_type'];
        $regular_period_value = $final_products_info['products'][$product_id]['regular_period_value'];
        if( $regular_period_value==1 && $regular_period_type=='<{common_day}>' )    { $period = 'day'; }
        if( $regular_period_value==1 && $regular_period_type=='<{common_month}>' )  { $period = 'month'; }
        if( $regular_period_value==3 && $regular_period_type=='<{common_month}>' )  { $period = 'month3'; }
        if( $regular_period_value==6 && $regular_period_type=='<{common_month}>' )  { $period = 'month6'; }
        if( $regular_period_value==1 && $regular_period_type=='<{common_year}>' )   { $period = 'year'; }
        if( $regular_period_value==5 && $regular_period_type=='<{common_year}>' )   { $period = 'year5'; }

        // get product info array from DB to compare with the data in SESSION
        $product_info = $this->payment_model->get_product_info($product_id,$uid,$final_products_info['user_force_trial']);
/*        echo "final_product_info this is from SESSION&nbsp;<br/>";  print_r($final_products_info); echo "<br/>&nbsp;<br/>";
        echo "product_info this is from BD&nbsp;<br/>";             print_r($product_info); echo "<br/>&nbsp;<br/>";*/

        //count the price in session using discount values from session
        $counted_price_from_db = $product_info[0][$period];
        $counted_price_from_db = $this->payment_model->use_discount($counted_price_from_db,$product_info[0]['discount_type'],$product_info[0]['discount']);
        $counted_price_from_db = $this->payment_model->use_discount($counted_price_from_db,$final_products_info['products'][$product_id]['coupon_type'],$final_products_info['products'][$product_id]['coupon_value']);

        //if data in SESSION is NOT UP TO DATE with data in DB then return FALSE
        if ( $final_products_info['products'][$product_id]['regular_price']!=$counted_price_from_db
            || $final_products_info['products'][$product_id]['trial_period_value']!=$product_info[0]['trial_period_value']
            || $final_products_info['products'][$product_id]['trial_period_value']!=$product_info[0]['trial_period_value']
            || $final_products_info['products'][$product_id]['trial_price']!=$product_info[0]['trial_price'])
        {
            // data in SESSION is not up to date
            return false;
        }
        else
        {
            // data in SESSION is up to date
            return true;
        }
    }

}
?>
