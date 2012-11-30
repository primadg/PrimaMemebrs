<?php
/**
* 
* THIS FILE CONTAINS Capcha CLASS
* 
* @package Needsecure
* @author uknown
* @version uknown
*/
/**
* Include file user_controller.php
*/
require_once("user_controller.php");
/**
* 
* THIS CLASS ...
* 
* @package Needsecure
* @author uknown
* @version uknown
*/
class Cart extends User_Controller
{
    /**
    * THIS METHOD SETS INITIAL VARS (constructor)
    */
    function Cart()
    {
        parent::User_Controller();
    }

    /**
    * Enter description here...
    *
    * @global unknown
    * @return true
    */
    function index()
    {
        global $_helper_CONFIG;
        $from = $this->input->post('from');
        $coupons = $this->input->post('coupons');
        $prep_post = prepare_post();
        //fb($prep_post,'prep_post');
        $this->cart_list($from,$coupons,$prep_post);
        return true;
    }

    /**
    * Enter description here...
    *
    * @param integer $product_id
    */
    function remove($product_id=0)
    {
        $this->load->model('cart_model');
        $product_id = intval($product_id);
        if( $product_id>0 )
        {
            $this->cart_model->remove($product_id);
        }
        redirect_page('<{user_redirect_title}>','cart');
    }

    /**
    * Clear shopping cart
    *
    */
    function clear_cart()
    {
        //TODO: delete later
        $this->load->model('cart_model');
        $this->cart_model->clear_cart();
    }
    
    /**
    * Enter description here...
    *
    * @return true
    */
    function service()
    {
        fb($_REQUEST);
        $post=$_REQUEST;
        if(isset($post['action']) && $post['action']=='check_domain' && isset($post['domain']) && !empty($post['domain']))
        {
            $result=$this->_check_domain($post['domain']);
            echo create_temp_vars_set($result);
        }
        return true;
    }
    
    function _check_domain($domain)
    {
        fb(isset($_SESSION['hosted_domains'])?$_SESSION['hosted_domains']:'',"SESSION1");
        if(isset($_SESSION['hosted_domains']) && is_array($_SESSION['hosted_domains']))
        {
            foreach($_SESSION['hosted_domains'] as $key=>$value)
            {
                if(!in_array($value,array(0,6,7)))
                {
                    unset($_SESSION['hosted_domains'][$key]);
                }                
            } 
        }
        $domains=(isset($_SESSION['hosted_domains']) && is_array($_SESSION['hosted_domains'])) ? $_SESSION['hosted_domains'] : array();
        $CI =& get_instance();
        $CI->load->model('host_plans_model');
        $result_whois = $CI->host_plans_model->get_whois($domain);
        fb($_SESSION['hosted_domains'],"SESSION2");
        if(isset($_SESSION['hosted_domains']) && is_array($_SESSION['hosted_domains']))
        {
            fb($domains,'DOMAIN1');
            $domains=array_diff_key($_SESSION['hosted_domains'],$domains);
            ksort($domains);
            fb($domains,'DOMAIN2');
        }
        else
        {
            $domains=array();
        }
        
        $result=array();
        $result['result']=0;
        $errors=array(
        1=>'<{user_cart_hosted_msg_er_domain_length}>',
        2=>'<{user_cart_hosted_msg_er_domain_invalid}>',      
        3=>'<{user_cart_hosted_msg_er_domain_invalid}>',      
        4=>'<{user_cart_hosted_msg_er_domain_not_lookup}>',      
        5=>'<{user_cart_hosted_msg_er_domain_exceeded}>',                  
        );
        foreach($domains as $key=>$value)
        {
            if(in_array($value,array(1,2,3,4,5)))
            {
                $result['error']=$errors[$value];   
            }
            if($value==0 || $value==7)
            {
                $result['result']=1;
                $result['message']='<{user_cart_hosted_msg_domain_available}>';
            }
        }
        
        if($result['result']==0 && !isset($result['error']))
        {
            $result['message']=count($domains) ? '<{user_cart_hosted_msg_domain_not_available}>' : '<{user_cart_hosted_msg_domain_not_changed}>';
            $result['result']=1;
        } 
        if(isset($result['message']))
        {
            $result['message']=replace_lang($result['message']);
        }
        if(isset($result['error']))
        {
            $result['error']=replace_lang($result['error']);
        }            
        $result['domains']=$domains;
        return $result;
    }
    
    /**
    * Enter description here...
    *
    * @param string $from
    * @param string $coupons
    * @param string $POST
    * @return boolean
    */
    function cart_list($from='',$coupons='',$POST='')
    {
        if(isset($_SESSION) && isset($_SESSION['skip_cart']))
        {
            unset($_SESSION['skip_cart']);
        }
        $is_checkout=(isset($_POST['preview']) || !isset($_POST['next'])) ? false : true;
        //        print_r($POST);
        if( !isset($coupons) or !is_array($coupons) )
        {
            $coupons = session_get('coupons');
        }

        if( isset($from) and !empty($from) and mb_strlen($from)<4096 )
        {
            $from = base64_decode($from);
            session_set('cart_from_url',$from);
        }
        else
        {
            $from = session_get('cart_from_url');
        }
        //fb(__FUNCTION__,'debug_backtrace'); 
        //fb($from,'from');
        if(isset($_POST['domains_selector']))
        {
            $domains_selector = $POST['domains_selector'];
        } 
        else
        {
            $domains_selector = session_get('domains_selector');
        }
        
        if(isset($_POST['name_domen']))
        {
            $name_domen = $POST['name_domen'];
        } 
        else
        {
            $name_domen = session_get('name_domen');
        }
        
        $user_force_trial = 0;
        if(isset($_POST['user_force_trial']))
        {
            $user_force_trial = intval($POST['user_force_trial']);
        }
        else if (isset($POST['period']))
        {
            $user_force_trial = 0;
        }
        else
        {
            $tmp_array = session_get('final_products_info');
            if( is_array($tmp_array) and isset($tmp_array['user_force_trial']))
            {
                $user_force_trial  = intval($tmp_array['user_force_trial']);
            }
            unset($tmp_array);
        }

        $this->load->model('cart_model');
        $this->load->model('payment_model');
        $this->load->model('product_model');
        $this->load->model('coupons_model');
        $this->load->model('user_auth_model');
        $this->user_auth_model->is_auth();

        /// add product to cart
        $product_id = (isset($POST['product_id']))?intval($POST['product_id']):0;
        $period = (isset($POST['period']) and in_array($POST['period'],array('day','month','month3','month6','year','year5')))?$POST['period']:'year5';
        $type = (isset($POST['type']) and intval($POST['type'])>0)?intval($POST['type']):0;

        // if the new product was added to cart we should reload all data in SESSION
        if (isset($POST['product_id']))
        {
            //this FLAG means that we shouldn't use the previous data from SESSION
            $new_product_is_added = true;
        }
        else
        {
            $new_product_is_added = false;
        }
        
        
        //filtering anavailable products
        $this->load->model('member_group_model');
        if (isset($POST['product_id']))
        {
            $product_id_available = $product_id;
        } else 
        {
            /**
            * kgg
            * define $product_id for recalculate coupon
            * 
            * @TODO Need change for multiproduct cart
            */
            $tmp_array  = session_get('products');
            $tmp_array = array_keys($tmp_array);
            $product_id_available = $tmp_array[0];
            unset($tmp_array);
        }
        if(!$this->member_group_model->is_product_available($this->user_auth_model->uid,$product_id_available))
        {
            redirect('market/sale');
            return true;
        }

        //additional period check
        $period_is_right = false;
        if ($new_product_is_added)    //when $new_product_is_added
        {
            // clear previous Coupons data from session
            session_set('coupons','');
            unset($coupons);

            // clear previous Name_Domen from session
            session_set('name_domen','');
            unset($name_domen);
            
            // clear previous domains_selector from session
            session_set('domains_selector','');
            unset($domains_selector);
                        

            // get product info
            $tmp_db_info = $this->payment_model->get_product_info($product_id,intval($this->user_auth_model->uid),$user_force_trial);
            if( $tmp_db_info !== false )
            {
                $period_is_right = true;
            }
            unset($tmp_db_info);
        }
        else            //when !isset($POST['period'])
        {
            $final_products_info = session_get('final_products_info');
            if( is_array($final_products_info) and !empty($final_products_info) )   //when !isset($POST['period']) && product info IS in SESSION
            {   //when product data was previously set in SESSION
                reset($final_products_info['products']);
                list($prod_id, $product_info) = each($final_products_info['products']);
                $regular_period_type    = $product_info['regular_period_type'];
                $regular_period_value   = $product_info['regular_period_value'];
                //make "translation" table
                $periods_array = Array();
                $periods_array[] = Array('day',   '<{common_day}>',   1);
                $periods_array[] = Array('month', '<{common_month}>', 1);
                $periods_array[] = Array('month3','<{common_month}>', 3);
                $periods_array[] = Array('month6','<{common_month}>', 6);
                $periods_array[] = Array('year',  '<{common_year}>',  1);
                $periods_array[] = Array('year5', '<{common_year}>',  5);
                foreach ($periods_array as $period_elem)
                {
                    //we should find the $period value in concordance with $regular_period_type && $regular_period_value
                    if ($regular_period_type==$period_elem[1] && $regular_period_value==$period_elem[2])
                    {
                        //we found the $period
                        $period_is_right = true;
                        $period = $period_elem[0];
                        $product_id = $prod_id;
                        $type = (isset($product_info['recouring']) && $product_info['recouring']!=0) ? 2 : 1;
                        break; //break the foreach compare cycle
                    }
                }
            }
            else        //when !isset($POST['period']) && no product info in SESSION
            {
                //when product is free
                $period_is_right = true;
                $period = 'year5';          //means product is FREE
            }
            unset($tmp_array);
        }
        if( intval($product_id)>0 and !$this->product_model->is_product_blocked($product_id) and !empty($period) and intval($this->user_auth_model->uid)>0 and $period_is_right)
        {
            if( $this->payment_model->is_subscr_available($product_id,intval($this->user_auth_model->uid)) !== true )
            {
                $this->clear_cart();
                $this->cart_model->add($product_id,$period,$type);
            }
        }
        else if( intval($product_id)>0 and !$this->product_model->is_product_blocked($product_id) and !empty($period) and intval($this->user_auth_model->uid)<=0 and $period_is_right)
        {
            $this->clear_cart();
            $this->cart_model->add($product_id,$period,$type);
        }
        /// _add product to cart

        $data = array();
        $data['currency_code'] = config_get('system','config','currency_code');
        $data['from_url'] = $from;
        $mess_err=array();
        
        if(Functionality_enabled('admin_product_hosted')===true)
        {
            $data['checked_domain']='';
            fb($_SESSION,'SESSION');
            if(isset($_POST['domain_check_button']) && isset($_POST['domain']) && !empty($_POST['domain']))
            {
                $is_checkout=false;
                $result=$this->_check_domain($_POST['domain']);
                if(isset($result['message']))
                {
                    $data['message_box']=isset($data['message_box']) ? $data['message_box'] : array();
                    $data['message_box']['check_domain']=array(
                    'display'=>true,
                    'text'=>$result['message']
                    );
                    $data['checked_domain']=output($_POST['domain']);
                }
                if(isset($result['error']))
                {
                    $mess_err['check_domain']=array(
                    'display'=>true,
                    'text'=>$result['error'] 
                    );
                }
            }
        }        
        
        do
        {
            $products_modified = false; // this is control FLAG that is used in do-while cycle

            // load the products data from CART
            $products = $this->cart_model->product_list($this->user_auth_model->uid,$user_force_trial);
            $new_array = array();

            //this var is used to indicate if some product in cart is FREE
            $some_product_is_free = false;

            fb($products,'from cart_model');
            if( $products!==false )
            {
                //echo "<pre>";
                //echo "</pre>";

                if( !isset($coupons) or !is_array($coupons) )
                {
                    $coupons = array();
                }

                if( !isset($name_domen) or !is_array($name_domen) )
                {
                    $name_domen = array();
                }
                
                if( !isset($domains_selector) or !is_array($domains_selector) )
                {
                    $domains_selector = array();
                }

                $new_array = array();
                $new_array['total'] = 0;

                $new_array['user_force_trial'] = $user_force_trial;
                $new_array['products'] = array();
                foreach( $products  as $product_id=>$product_info )
                {
                    // if the current price is FREE, then we should check is the product REALLY FREE?
                    if ($product_info['dbinfo'][$period]<=0 and !$new_product_is_added)
                    {
                        $periods_array = Array('day','month','month3','month6','year','year5');
                        foreach ($periods_array as $period_elem)
                        {
                            // if the price for some period IS NOT FREE then change the period to it
                            if ($product_info['dbinfo'][$period_elem]>0)
                            {
                                // change the period to the found one
                                $period = $period_elem;
                                // "reload" cart with new product data
                                $this->clear_cart();
                                $this->cart_model->add($product_id,$period,$type);
                                // set the flag $products_modified = TRUE
                                $products_modified = true;

                                break; // _foreach ($periods_array as $period_elem)
                            }
                        }
                        if ($products_modified)
                        {
                            break; // _foreach ($products  as $product_id=>$product_info)
                        }
                    }
                    // _if the current price is FREE, then we should check is the product REALLY FREE?

                    // additional check if somehow product was added to cart which is not subscr_available
                    if( $this->user_auth_model->uid && $this->payment_model->is_subscr_available($product_id,intval($this->user_auth_model->uid))==true )
                    {
                        $this->clear_cart();
                        continue;
                    }
                    // _additional check if somehow product was added to cart which is not subscr_available

                    //log this to the "User_logs" table in DB
                    $this->load->model('user_log_model');
                    $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/cart", $product_id);
                    //_log this to the "User_logs" table in DB

                    if(!$user_force_trial)
                    {
                        $product_info['dbinfo']['trial_period_value']=0;
                        $product_info['dbinfo']['trial_price']=0;
                        $product_info['dbinfo']['trial_period_type']='';
                    }

                    if( !isset($product_info['dbinfo']) or !is_array($product_info['dbinfo']) or sizeof($product_info['dbinfo'])<=0 )
                    {
                        continue;
                    }
                    fb($product_info,"product_info");
                    $new_array['products'][$product_id] = array();
                    $new_array['products'][$product_id]['name'] = (isset($product_info['dbinfo']['product_name']))?$product_info['dbinfo']['product_name']:'';
                    $new_array['products'][$product_id]['descr'] = (isset($product_info['dbinfo']['product_descr']))?$product_info['dbinfo']['product_descr']:'';
                    $new_array['products'][$product_id]['product_type'] = (isset($product_info['dbinfo']['product_type']))?$product_info['dbinfo']['product_type']:'';
                    $new_array['products'][$product_id]['coupon_code'] = '';
                    $new_array['products'][$product_id]['coupon_type'] = '';
                    $new_array['products'][$product_id]['coupon_value'] = 0;

                    $new_array['products'][$product_id]['old_regular_price'] = floatval(0);

                    /* coupon code */
                    if( intval($this->user_auth_model->uid)>0 )
                    {
                        unset($product_coupon_info,$coupon_code);
                        $user_id = intval($this->user_auth_model->uid);

                        $product_coupon_info = array();
                        $product_coupon_info = array(
                        'result'=>false,
                        'type'=>0,
                        'value'=>0
                        );
                        fb($coupons);
                        if( isset($coupons[$product_id]) and !empty($coupons[$product_id]) )
                        {
                            $coupon_code = prepare_text($coupons[$product_id]);
                            $product_coupon_info = $this->coupons_model->check_coupon($coupon_code, $product_id, $user_id);
                            fb($product_coupon_info);
                            if( $product_coupon_info['result']===true )
                            {
                                $new_array['products'][$product_id]['coupon_code'] = $coupon_code;
                                $new_array['products'][$product_id]['coupon_type'] = $product_coupon_info['type'];
                                $new_array['products'][$product_id]['coupon_value'] = $product_coupon_info['value'];
                            }
                            else
                            {
                                unset($coupons[$product_id]);
                            }
                        }
                    }
                    /* _coupon code */

                    /* Name domen */
                    //***********Functionality limitations***********
                    if(Functionality_enabled('admin_product_hosted')===true)
                    {
                        if( $product_info['dbinfo']['product_type'] == PRODUCT_HOSTED)
                        {
                            $data_hosted=array();
                            $data_hosted['input_width'] =185;
                            $data_hosted['product_id']=$product_id;
                            $data_hosted['name_domen']='';
                            $data_hosted['domains']=array();
                            $data_hosted['domains_display']='display:none;';
                            if(isset($_SESSION['hosted_domains']) && is_array($_SESSION['hosted_domains']))
                            {
                                ksort($_SESSION['hosted_domains']);
                                foreach($_SESSION['hosted_domains'] as $key=>$value)
                                {
                                    $data_hosted['domains'][$key]=array(
                                    'status'=>$value,
                                    'domain'=>$key,
                                    'attributes'=>((intval($value)>0 && intval($value)!=7) ? 'class="hosted_domain_item" disabled':'') 
                                    );               
                                }
                            }
                            fb($data_hosted['domains'],"domains");
                            if( isset($name_domen[$product_id]) and !empty($name_domen[$product_id]) )
                            {
                                $data_hosted['name_domen']=$new_array['products'][$product_id]['name_domen'] = isset($name_domen[$product_id]) ? prepare_text($name_domen[$product_id]) : '';                                
                            }
                            else
                            {
                            $is_checkout=false;
                            $mess_err['check_domain']=array(
                            'display'=>true,
                            'text'=>'<{user_cart_error_domain_must_by}>' 
                            );
                            }
                            
                            
                            
                            
                            
                            if(!empty($data_hosted['name_domen']) && isset($data_hosted['domains'][$data_hosted['name_domen']]) && in_array(intval($data_hosted['domains'][$data_hosted['name_domen']]['status']),array(0,7)))
                            {
                                $data_hosted['domains'][$data_hosted['name_domen']]['attributes'].=" selected";
                            } 
                            $data_hosted['name_domen_disabled']=$is_checkout ? 'disabled' : '';
                            $new_array['products'][$product_id]['node_name_domen'] =  print_page('user/hosting/shoping_cart_name_domen.html',$data_hosted,TRUE);
                            
                        }
                    }
                    //***********Functionality limitations***********
                    /* _Name domen */

                    /* recouring flag */
                    $new_array['products'][$product_id]['recouring'] = 0;
                    if( isset($product_info['dbinfo']['is_recouring']) and intval($product_info['dbinfo']['is_recouring'])>0 )
                    {
                        if( isset($product_info['type']) and intval($product_info['type'])==2 )
                        {
                            $new_array['products'][$product_id]['recouring'] = 1;
                        }
                    }
                    /* recouring flag */

                    /* regular period */
                    $new_array['products'][$product_id]['regular_period_type'] = ''; $regular_period_type='';
                    $new_array['products'][$product_id]['regular_period_value'] = intval(0); $regular_period_value=0;
                    //                        $period = $product_info['period'];
                    if( $period == 'day' ){ $regular_period_value = 1; $regular_period_type='<{common_day}>'; }
                    if( $period == 'month' ){ $regular_period_value = 1; $regular_period_type='<{common_month}>'; }
                    if( $period == 'month3' ){ $regular_period_value = 3; $regular_period_type='<{common_month}>'; }
                    if( $period == 'month6' ){ $regular_period_value = 6; $regular_period_type='<{common_month}>'; }
                    if( $period == 'year' ){ $regular_period_value = 1; $regular_period_type='<{common_year}>'; }
                    if( $period == 'year5' ){ $regular_period_value = 5; $regular_period_type='<{common_year}>'; }
                    $new_array['products'][$product_id]['regular_period_type'] = $regular_period_type;
                    $new_array['products'][$product_id]['regular_period_value'] = $regular_period_value;
                    /* _regular period */

                    /* trial period */
                    $trial_period_type = '';
                    if( isset($product_info['dbinfo']['trial_period_type']) )
                    {
                        $trial_period_type = $product_info['dbinfo']['trial_period_type'];
                        if( $trial_period_type == 'day' ){ $trial_period_type='<{common_day}>'; }
                        if( $trial_period_type == 'month' ){ $trial_period_type='<{common_month}>'; }
                        if( $trial_period_type == 'year' ){ $trial_period_type='<{common_year}>'; }
                    }
                    $new_array['products'][$product_id]['trial_period_type'] = $trial_period_type;
                    $new_array['products'][$product_id]['trial_period_value'] = (isset($product_info['dbinfo']['trial_period_value']))?intval($product_info['dbinfo']['trial_period_value']):0;
                    /* _trial period */

                    /* trial price */
                    $trial_price = (isset($product_info['dbinfo']['trial_price']))?floatval($product_info['dbinfo']['trial_price']):0;
                    $new_array['products'][$product_id]['trial_price'] = amount_to_print($trial_price);
                    /* _trial price */

                    /* regular price */
                    $new_array['products'][$product_id]['regular_price'] = floatval(0);
                    $regular_price = floatval(0);
                    $free = intval(0);

                    if( array_key_exists($period,$product_info['dbinfo']) )
                    {
                        if( floatval($product_info['dbinfo'][$period])<=0 )
                        {
                            $free = intval(1);
                        }
                        elseif( floatval($product_info['dbinfo'][$period])>0 )
                        {
                            $regular_price = floatval($product_info['dbinfo'][$period]);
                        }
                    }

                    if ($free)
                    {
                        $some_product_is_free = true;
                    }

                    if(
                            isset($product_info['dbinfo']['discount']) and floatval($product_info['dbinfo']['discount'])>0
                            and
                            isset($product_info['dbinfo']['discount_type']) and intval($product_info['dbinfo']['discount_type'])>0
                            and
                            $free<=0
                            )
                    {
                        $regular_price = $this->payment_model->use_discount($regular_price,$product_info['dbinfo']['discount_type'],$product_info['dbinfo']['discount']);
                    }
                    //echo __LINE__."<h1>regular price {$regular_price}</h1>";
                    if( $free>0 )
                    {
                        $regular_price = floatval(0);
                    }

                    $new_array['products'][$product_id]['free'] = $free;

                    if(
                            isset($new_array['products'][$product_id]['coupon_type'])
                            and
                            isset($new_array['products'][$product_id]['coupon_value'])
                            and
                            intval($new_array['products'][$product_id]['coupon_type'])>0
                            and
                            floatval($new_array['products'][$product_id]['coupon_type'])>0
                            )
                    {
                        $new_array['products'][$product_id]['old_regular_price'] = amount_to_print(floatval(round($regular_price,2)));

                        $new_array['products'][$product_id]['regular_price'] = amount_to_print($this->payment_model->use_discount($regular_price,$new_array['products'][$product_id]['coupon_type'],$new_array['products'][$product_id]['coupon_value']));
                    }
                    else
                    {
                        $new_array['products'][$product_id]['regular_price'] = amount_to_print(floatval(round($regular_price,2)));
                    }
                    /* _regular price */

                    /*  total */
                    $new_array['products'][$product_id]['total'] = '00.00';
                    if( $free<=0 )
                    {
                        if( floatval($trial_price)>0 and $new_array['products'][$product_id]['trial_period_value']>0 )
                        {
                            $new_array['products'][$product_id]['total'] = amount_to_print(floatval(floatval($new_array['products'][$product_id]['regular_price']) + floatval($trial_price)));
                        }
                        else
                        {
                            $new_array['products'][$product_id]['total'] = amount_to_print(floatval($new_array['products'][$product_id]['regular_price']));
                        }
                        $new_array['total']+=floatval($new_array['products'][$product_id]['total']);
                    }
                    /* _total */

                    
                    
                    unset($period,$regular_price,$trial_price,$trial_period_type,$regular_period_value,$regular_period_type);
                } // _foreach ($periods_array as $period_elem)
            } // _if( $products!==false )
            fb($new_array,'new array');
        } while($products_modified);

        $payments = array();
        if ($some_product_is_free)
        {
            // for free products
            $payments[] = array('name'=>'Free payment','id'=>0);
        }
        else
        {
            // else load all available payment_systems
            $payment_systems = config_get('payment');
            if( isset($payment_systems) and is_array($payment_systems) and sizeof($payment_systems)>0 )
            {
                foreach($payment_systems as $payment_system_id=>$payment_system_info)
                {
                    if( isset($payment_system_info['active']) and intval($payment_system_info['active'])==1 )
                    {
                        if (
                                isset($payment_system_info['name']) and !empty($payment_system_info['name'])
                                and isset($payment_system_info['controller']) and !empty($payment_system_info['controller']) )
                        {
                            $name = $payment_system_info['name'];
                            $controller = $payment_system_info['controller'];
                        }
                        else
                        {
                            continue;
                        }

                        $payments[] = array('name'=>$name,'id'=>$payment_system_id);
                        unset($name,$controller);
                    }
                }
            }
        }
        

        $data['products'] = $new_array;
        $data['payment_systems'] = $payments;
        
        if(!isset($_POST['payment_system']))
        {
            $payment_system_id = session_get('payment_system_id');
            fb($payment_system_id,'payment_system_id1');
        }
        else
        {
            $payment_system_id=intval($_POST['payment_system']);
            session_set('payment_system_id',$payment_system_id);
            fb($payment_system_id,'payment_system_id2');
        }
        foreach($data['payment_systems'] as $k=>$v)
        {
            $data['payment_systems'][$k]['selected_payment_systems']=($v['id']==$payment_system_id) ? "selected" : "";
        }
        
        
        
        $data['some_product_is_free'] = $some_product_is_free;

        if (!isset($coupons))
        {
            //if variable was not set somehow, then make dummy value to avoid "Message: Undefined variable"
            $coupons = '';
        }

        if (!isset($name_domen))
        {
            $name_domen = '';
        }
        $data['coupons'] = $coupons;
        $data['name_domen'] = $name_domen;
        session_set('coupons',$coupons);
        session_set('name_domen',$name_domen);
        session_set('final_products_info',$data['products']);

        // move up
        //        $mess_err=array();
        
        
        //***********Functionality limitations***********
        if(($functionality_enabled_error=Functionality_enabled('admin_member_info_modify',intval($this->user_auth_model->uid)))!==true)
        {
            $mess_err['functionality_enabled']=array();
            $mess_err['functionality_enabled']['display']=true;
            $mess_err['functionality_enabled']['text']=$functionality_enabled_error;
            $data['demo']=true;
        }
        //*******End of functionality limitations********
        
        $data['mess_err']=$mess_err;
        
        
        //***********Functionality limitations***********
        if($data['some_product_is_free'] || Functionality_enabled('admin_products_modify_paid')!==true)
        {   
            session_set('skip_cart',true);
            $this->payment_model->checkout();
            return true;
        }
        //*******End of functionality limitations********
        fb($data,'All data');
        //_view('user/shoping_cart',$data);
        
        $data['error_box']=$data['mess_err'];
        $data['if_payment_systems'] = (isset($data['payment_systems']) && is_array($data['payment_systems']) && count($data['payment_systems']) && !$data['some_product_is_free']) ? array(array()) : array();
        $data['else_payment_systems'] = count($data['if_payment_systems']) ? array() : array(array());
        
        //additinal panel
        $data['additional_panel'] = false;
        
        $data['if_products']=array();
        $data['else_products']=array(array());
        if(isset($data['products']) && isset($data['products']['products']) && count($data['products']['products']))
        {
            $data['if_products']=array(array());
            $data['else_products']=array();
            $data['total']=amount_to_print(isset($data['products']['total']) ? floatval($data['products']['total']) : floatval(0));
            
            $products=array();
            $not_dotted=array_pop(array_keys($data['products']['products']));
            $data['additional_columns_titles']=array();
            $data['colspan_total']=2;
            $data['colspan_all']=5;
            foreach($data['products']['products'] as $id=>$product)
            {
                fb($product,"Product ".$id.":");
                $products[$id]=$product;
                $products[$id]['additional_columns_values']=array();
                $products[$id]['dotted']=($id!==$not_dotted) ? "dotted_bottom" : "";
                $products[$id]['id']=$id;
                $products[$id]['name']=soft_wrap(output($product['name']),40);
                $products[$id]['additional_info'] = (Functionality_enabled('admin_product_hosted')===true && isset($product['node_name_domen'])) ? $product['node_name_domen'] : "";
                if(Functionality_enabled('admin_product_hosted')===true && $product['product_type']==PRODUCT_HOSTED)
                {
                    $data['additional_columns_titles']['domain']=array('content'=>'<{user_cart_hosted_domain}>');
                    $products[$id]['additional_columns_values']['domain']=array('content'=>(Functionality_enabled('admin_product_hosted')===true && isset($product['node_name_domen'])) ? $product['node_name_domen'] : "");
                    $data['additional_panel']='';
                }
                $data['colspan_total']=3;
                $data['colspan_all']=6;
                $products[$id]['trial'] =(intval($product['trial_period_value'])>0 && !empty($product['trial_period_type'])) ? intval($product['trial_period_value']).' '.$product['trial_period_type'].' '.$product['trial_price'].' '.$data['currency_code'] : "";
                
                $products[$id]['if_old_regular_price']=(isset($product['old_regular_price']) and floatval($product['old_regular_price'])>0) ? array(array()) : array();
                
                $products[$id]['if_recouring'] = (intval($product['recouring'])) ? array(array()) : array();
                $products[$id]['coupon_disabled'] = ($data['some_product_is_free'] || $is_checkout) ? "disabled" : "";
                $products[$id]['coupon'] = (isset($data['coupons'][$id])) ? output($data['coupons'][$id]):'';
            } 
            $data['products']=$products;
        }
        else
        {
            $data['products']=array();
        }
        $data['if_recalculate'] = (!$data['some_product_is_free']) ? array(array()) : array();
        $data['payment_disabled']= (isset($data['demo']) || !count($data['if_products']) || (!$data['some_product_is_free'] && !count($data['if_payment_systems']))) ? "disabled" : "";
        fb($data);
        
        $data['message_box']=isset($data['message_box']) && count($data['message_box']) ? $data['message_box'] : array('default'=>'default');
        //$data['from_url']=site_url('cart');
        $data['payment_url']=!isset($data['demo']) ? site_url('payment') : '';
        $data['prev_button']="<{user_cart_btn_prev}>";
        $data['prev_button_name']="preview";
        $data['next_button']="<{user_cart_btn_checkout}>";
        
        //next recursion or checkout
        fb($data['from_url'],"data['from_url']");
        if(!$is_checkout)
        {
            $data['payment_url']=!isset($data['demo']) ? site_url('cart') : '';
            $data['from_url']=(isset($data['from_url']) && !empty($data['from_url'])) ? output($data['from_url']) : site_url('market/sale');
            $data['prev_button']="<{user_cart_btn_cancel}>";
            $data['prev_button_name']="cancel";
            $data['next_button']="<{user_cart_btn_next}>";            
            if(Functionality_enabled('admin_product_hosted')===true && $data['additional_panel']!==false)
            {   
                $data['additional_panel'].=print_page('user/hosting/shoping_cart_check_domain.html',array('checked_domain'=>$data['checked_domain']),TRUE);
            }
        } 
        else
        {
            $data['from_url']=site_url('cart');
        }
        
        //redirect if unauthirezed user click next
        if(isset($_POST['next']) && !$this->user_auth_model->is_auth() )
        {
            redirect('user/login/1/'.encode_url(base_url().'cart'));
            exit(0);
        }
        fb($data, 'data'); 
        print_page('user/shoping_cart.html',$data);
        return true;
    }


    /**
    * Test product
    *
    * @param integer $product_id
    * @param string $period
    * @param integer $type
    * @param integer $remove
    */
    function test($product_id=0,$period='',$type=0,$remove=0)
    {
        if (isset($_POST['product_id']))
        {
            $product_id = $_POST['product_id'];
            $period = $_POST['period'];
            $type = $_POST['type'];
            $remove = $_POST['remove'];
        }

        echo "<h1>This is <b>FOR TEST ONLY</b>!</h1><br/><br/>";

        echo "product_id: ".$product_id."<br>";
        echo "period: ".$period."<br>";
        echo "type: ".$type."<br>";
        echo "remove: ".$remove."<br>";

        echo '<br/><br/><form name="test form" action="test" method="post">';
        echo 'product id: <input name="product_id"><br/>';
        echo 'period: <select name="period">
                        <option value="day">day
                        <option value="month">month
                        <option value="month3">month3
                        <option value="month6">month6
                        <option value="year">year
                        <option value="year5">year5
                    </select><br/>';
        echo 'type: <select name="type">
                        <option value="1">onetime (1)
                        <option value="2">recurring (2)
                    </select><br/>';
        echo 'remove: <select name="remove">
                        <option value="0" selected>no (0)
                        <option value="1">YES (1)
                    </select><br/>';
        echo '<input type="submit" value="Don\'t fuck anything up!"';
        echo '</form>';

        $this->load->model('cart_model');

        var_dump($this->cart_model->add($product_id,$period,$type));

        if($remove==1)
        {
            var_dump($this->cart_model->remove($product_id));
        }

        echo "<pre>";
        echo "Products:";
        print_r($this->cart_model->product_list());
        echo "</pre></br>";
        echo "<pre>";
        echo "Session:";
        print_r($_SESSION);
        echo "</pre>";

    }
    
    
}
?>
