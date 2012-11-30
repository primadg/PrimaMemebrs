<?php
/**
 * 
 * THIS FILE CONTAINS Paypal CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file payment.php
 */
require_once ('payment.php');
/**
 * 
 * Enter description here...
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Paypal extends Payment {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */ 
    function Paypal()
    {
        parent::Payment();
    }
	/**
	 * Validate and write fields to config file
	 *
	 * @param array $post
	 * @return array
	 */
    function Config_validation($post)
    {
        $result=array();
        $result['errors']=array();
        $result['mess']=array();
        $result['is_load_list']=false;
        //(EDIT)Validation fields
        if(( !isset($post['business']) or empty($post['business']) ) || eregi("^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$", $post['business'])===false)
        {
            $result['errors'][]="business";
        }
        //End of validation fields
        if(count($result['errors'])==0)
        {
            //(EDIT)Writing fields to config file
            config_set($post['business'],'PAYMENT',$post['id'],'BUSINESS');
            config_set($post['sandbox']=="false"?0:1,'PAYMENT',$post['id'],'SANDBOX');
            //End of writing fields to config file
            $result['is_load_list']=true;
            $result['mess'][]="saved_ok";
        }
        return $result;
    }
	/**
	 * Configurate form
	 *
	 * @param array $data
	 * @return string
	 */
    function Config_form($data)
    {
        $controller=$data['controller'];
        $temp_vars_set= array();
        $temp_vars_set['panel_script']=base_url()."js/admin/".$controller."/".$controller."_config.js";
        $temp_vars_set['cancelText']="<{admin_msg_cancel}>";
        $temp_vars_set['are_you_sure']="<{admin_msg_are_you_sure}>";
        $temp_vars_set['id']=isset($data['id'])?$data['id']:"";
        $data['temp_vars_set']=$temp_vars_set;
        //Green messages
        $messages = array();
        $messages['saved_ok'] = "<{admin_msg_ok_0001}>";
        $data['messages'] = $messages;
        //Error messages
        $mess_err = array();
        $mess_err['not_defined'] = "<{admin_payment_system_".$controller."_msg_er_not_defined}>";
        $mess_err['udefined_action'] = "<{admin_payment_system_".$controller."_msg_er_udefined_action}>";
        $mess_err['validation_fail'] = "<{admin_msg_validation_fail}>";
        //(EDIT)Form field error
        $mess_err['business']="<{admin_payment_system_".$controller."_msg_er_business}>";
        //End form field error
        $data['mess_err'] = $mess_err;
        //(EDIT)Additional comment in html format
        $data['comment_html'] = "";
        $res = $this->load->view("/default/reg/user/".$controller."/".$controller."_config", $data, true);return $res;
    }
	/**
	 * Displate form
	 *
	 */
    function send_ipn()
    {


        echo "<form action=\"".site_url('paypal/ipn')."\" method=\"post\">";
        echo "<input type=\"text\" name=\"txn_type\" value=\"subscr_signup\"> txn_type<br>";
        echo "<input type=\"text\" name=\"subscr_id\" value=\"S-61W60983GU915774M\"> subscr_id<br>";
        echo "<input type=\"text\" name=\"last_name\" value=\"User\"> last_name<br>";
        echo "<input type=\"text\" name=\"residence_country\" value=\"FR\"> residence_country<br>";
        echo "<input type=\"text\" name=\"mc_currency\" value=\"USD\"> mc_currency<br>";
        echo "<input type=\"text\" name=\"item_name\" value=\"my subscr\"> item_name<br>";
        echo "<input type=\"text\" name=\"amount1\" value=\"3.32\"> amount1<br>";
        echo "<input type=\"text\" name=\"amount3\" value=\"10.97\"> amount3<br>";
        echo "<input type=\"text\" name=\"business\" value=\"uashop_1203411946_biz@ua.fm\"> business<br>";
        echo "<input type=\"text\" name=\"recurring\" value=\"1\"> recurring<br>";
        echo "<input type=\"text\" name=\"verify_sign\" value=\"A0QGu5BdJLfusBA3-iyDATTNH-P9AjJXfpvkH6bxuhRRewJCTwEkvbVp\"> verify_sign<br>";
        echo "<input type=\"text\" name=\"payer_status\" value=\"verified\"> payer_status<br>";
        echo "<input type=\"text\" name=\"test_ipn\" value=\"1\"> test_ipn<br>";
        echo "<input type=\"text\" name=\"payer_email\" value=\"uashop_1203510491_per@ua.fm\"> payer_email<br>";
        echo "<input type=\"text\" name=\"first_name\" value=\"Test\"> first_name<br>";
        echo "<input type=\"text\" name=\"receiver_email\" value=\"uashop_1203411946_biz@ua.fm\"> receiver_email<br>";
        echo "<input type=\"text\" name=\"payer_id\" value=\"9KYE4PEDNQKD2\"> payer_id<br>";
        echo "<input type=\"text\" name=\"reattempt\" value=\"0\"> reattempt<br>";
        echo "<input type=\"text\" name=\"item_number\" value=\"9e81cccb29\"> item_number<br>";
        echo "<input type=\"text\" name=\"subscr_date\" value=\"04:29:13 Jun 03, 2008 PDT\"> subscr_date<br>";
        echo "<input type=\"text\" name=\"custom\" value=\"09dfd6b192\"> custom<br>";
        echo "<input type=\"text\" name=\"period1\" value=\"10 D\"> period1<br>";
        echo "<input type=\"text\" name=\"period3\" value=\"1 M\"> period3<br>";
        echo "<input type=\"text\" name=\"mc_amount1\" value=\"3.32\"> mc_amount1<br>";
        echo "<input type=\"text\" name=\"mc_amount3\" value=\"10.97\"> mc_amount3<br>";
        echo "<input type=\"text\" name=\"charset\" value=\"windows-1252\"> charset<br>";
        echo "<input type=\"text\" name=\"notify_version\" value=\"2.4\"> notify_version<br>";
        echo "<input type=\"submit\" value=\"subscr_signup\">";
        echo "</form>";

        echo "<form action=\"".site_url('paypal/ipn')."\" method=\"post\">";
        echo "<input type=\"text\" name=\"payment_date\" value=\"04:29:15 Jun 03, 2008 PDT\"> payment_date<br>";
        echo "<input type=\"text\" name=\"txn_type\" value=\"subscr_payment\"> txn_type<br>";
        echo "<input type=\"text\" name=\"subscr_id\" value=\"S-61W60983GU915774M\"> subscr_id<br>";
        echo "<input type=\"text\" name=\"last_name\" value=\"User\"> last_name<br>";
        echo "<input type=\"text\" name=\"residence_country\" value=\"FR\"> residence_country<br>";
        echo "<input type=\"text\" name=\"item_name\" value=\"my subscr\"> item_name<br>";
        echo "<input type=\"text\" name=\"payment_gross\" value=\"3.32\"> payment_gross<br>";
        echo "<input type=\"text\" name=\"mc_currency\" value=\"USD\"> mc_currency<br>";
        echo "<input type=\"text\" name=\"business\" value=\"uashop_1203411946_biz@ua.fm\"> business<br>";
        echo "<input type=\"text\" name=\"payment_type\" value=\"instant\"> payment_type<br>";
        echo "<input type=\"text\" name=\"verify_sign\" value=\"A0QGu5BdJLfusBA3-iyDATTNH-P9AjJXfpvkH6bxuhRRewJCTwEkvbVp\"> verify_sign<br>";
        echo "<input type=\"text\" name=\"payer_status\" value=\"verified\"> payer_status<br>";
        echo "<input type=\"text\" name=\"test_ipn\" value=\"1\"> test_ipn<br>";
        echo "<input type=\"text\" name=\"payer_email\" value=\"uashop_1203510491_per@ua.fm\"> payer_email<br>";
        echo "<input type=\"text\" name=\"txn_id\" value=\"52873725NN5817427\"> txn_id<br>";
        echo "<input type=\"text\" name=\"receiver_email\" value=\"uashop_1203411946_biz@ua.fm\"> receiver_email<br>";
        echo "<input type=\"text\" name=\"first_name\" value=\"Test\"> first_name<br>";
        echo "<input type=\"text\" name=\"payer_id\" value=\"9KYE4PEDNQKD2\"> payer_id<br>";
        echo "<input type=\"text\" name=\"receiver_id\" value=\"EMEXJGN26HF5G\"> receiver_id<br>";
        echo "<input type=\"text\" name=\"item_number\" value=\"9e81cccb29\"> item_number<br>";
        echo "<input type=\"text\" name=\"payment_status\" value=\"Completed\"> payment_status<br>";
        echo "<input type=\"text\" name=\"payment_fee\" value=\"0.43\"> payment_fee<br>";
        echo "<input type=\"text\" name=\"mc_fee\" value=\"0.43\"> mc_fee<br>";
        echo "<input type=\"text\" name=\"mc_gross\" value=\"3.32\"> mc_gross<br>";
        echo "<input type=\"text\" name=\"charset\" value=\"windows-1252\"> charset<br>";
        echo "<input type=\"text\" name=\"notify_version\" value=\"2.4\"> notify_version<br>";
        echo "<input type=\"submit\" value=\"subscr_payment\">";
        echo "</form>";
    }

	/**
	 * Enter description here...
	 *
	 * @return boolean
	 */
    function ipn()
    {
        $this->load->model('payment_model');
        $this->load->model("member_model");

        $item_number = (isset($_POST['item_number']))?intval($this->input->post('item_number')):0;
        $summ = (isset($_POST['mc_gross']))?floatval($this->input->post('mc_gross')):0;
        $txn_type = (isset($_POST['txn_type']))?trim($this->input->post('txn_type')):'';
        $payment_status = (isset($_POST['payment_status']))?trim($this->input->post('payment_status')):'';
        $original_ipn = $_POST;

        if( intval($item_number)<=0 or !$this->payment_model->is_subscr_exist($item_number) )
        {
            // subscription with this id is not exists.
            return false;
        }

        // create transaction
        $transaction_id = $this->payment_model->create_transaction(
                                                                    $item_number,
                                                                    1 /*pay_system_id*/,
                                                                    0 /*completed*/,
                                                                    $summ /*summ*/,
                                                                    $original_ipn /*info*/
                                                                    );
        // _create transaction
        if( intval($transaction_id)<=0 ) { return false; }

        $verified = 'INVALID';
        // verify transaction
            $paypal_request = 'cmd=_notify-validate';

            reset($_POST);
            while ( list($key,$value) = each($_POST) )
            {
                $value = urlencode($value);
    		    $value = str_replace('+','%20',$value);
    		    $paypal_request .= '&'.$key.'='.$value;
    		}
    		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    		$header .= "Content-Length: " . mb_strlen($paypal_request) . "\r\n\r\n";

            if( isset($_POST['test_ipn']) and intval($_POST['test_ipn'])==1 )
            {
                $paypal_gateway = "www.sandbox.paypal.com";
            }
            else
            {
                $paypal_gateway = "www.paypal.com";
            }

            $fp = @fsockopen ($paypal_gateway, 80, $errno, $errstr, 30);
            if( !$fp )
            {
                return false;
            }
            fputs ($fp, $header . $paypal_request);
            while (!feof($fp))
            {
                $res = fgets ($fp, 1024);

                if ( eregi (".*VERIFIED$",$res) !=false )
                {
                  $verified = 'VERIFIED';
                  break;
                }
                else
                {
                  $verified = 'INVALID';
                  break;
                }
            }
            fclose($fp);
        // _verify transaction

        $subscription_info = $this->payment_model->get_subscr_info($item_number);
        if( $subscription_info === false or !is_array($subscription_info) or sizeof($subscription_info)<=0 )
        {
            return false;
        }

        //!!!DELETE IN RELEASE - FOR TESTING PUPROSES ONLY
        $verified = 'VERIFIED';
        //!!!DELETE IN RELEASE
        if( $verified == 'INVALID' )
        {
            // inform admins and user with system emails "user_payment_error" and "admin_payment_error"
            $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
            // send email to user
            send_system_email_to_user($subscription_info[0]['user_id'],'user_payment_error',array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4), "subscription_id"=>$item_number, "transaction_id"=>$transaction_id));
            // notify all administrators by email
            send_system_subscription_to_admins('admin_payment_error', array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4), "subscription_id"=>$item_number, "transaction_id"=>$transaction_id, "amount"=>$summ));
            // _inform admins and user with system emails "user_payment_error" and "admin_payment_error"

            return false;
        }

        // subscription signup
        if( $txn_type == 'subscr_signup' and $verified == 'VERIFIED' )
        {
            // accept transaction
            $this->payment_model->accept_trans($transaction_id);
            // _accept transaction

            // check for free trial period
            $trial_period_value = (isset($subscription_info[0]['trial_period_value']))?intval($subscription_info[0]['trial_period_value']):0;
            $trial_period_type = (isset($subscription_info[0]['trial_period_type']))?$subscription_info[0]['trial_period_type']:'';
            $trial_price = (isset($subscription_info[0]['trial_price']))?floatval($subscription_info[0]['trial_price']):0;

            if( $trial_period_value>0 and !empty($trial_period_type) and floatval($trial_price)<=0 )
            {
                // Change subscription status
                $old_expire_date = $subscription_info[0]['expire_date'];
                if( $old_expire_date == '' or $old_expire_date == '0000-00-00' or $old_expire_date == '1970-01-11')
                {
                    $old_expire_date = date('Y-m-d');
                }
                $new_expire_date = date('Y-m-d',strtotime('+'.$trial_period_value.' '.$trial_period_type,strtotime($old_expire_date)));
                $this->payment_model->continue_subscr($item_number,$new_expire_date);
                // _Change subscription status

                // inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
                // send email to user
                send_system_email_to_user($subscription_info[0]['user_id'],'user_payment_notification',array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($new_expire_date,false)));
                // notify all administrators by email
                send_system_subscription_to_admins('admin_payment_notification', array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4), "subscription_id"=>$item_number, "transaction_id"=>$transaction_id, "amount"=>$summ));
                // _inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"

                // if trial is not used
                if (!$this->payment_model->check_used_trial($subscription_info[0]['user_id'],$subscription_info[0]['product_id']))
                {
                    // mark trial as used
                    $this->payment_model->set_used_trial($subscription_info[0]['user_id'],$subscription_info[0]['product_id']);
                }
            }
            // _check for free trial period
        }
        // _subscription signup

        // subscription payment
        if( $txn_type == 'subscr_payment'  and $verified == 'VERIFIED' and $payment_status=='Completed' )
        {
            $is_trial_period_used = $this->payment_model->check_used_trial($subscription_info[0]['user_id'],$subscription_info[0]['product_id']);

            // check if this payment is payment for TRIAL period
            if (!$is_trial_period_used and $subscription_info[0]['trial_period_value']>0)
            {
                // check for correct TRIAL payment
                if( isset($subscription_info[0]['trial_price']) and floatval($subscription_info[0]['trial_price']) === floatval($summ) )
                {
                    // mark trial as used
                    $this->payment_model->set_used_trial($subscription_info[0]['user_id'],$subscription_info[0]['product_id']);

                    // accept transaction
                    $this->payment_model->accept_trans($transaction_id);

                    $trial_period_value = (isset($subscription_info[0]['trial_period_value']))?intval($subscription_info[0]['trial_period_value']):0;
                    $trial_period_type = (isset($subscription_info[0]['trial_period_type']))?$subscription_info[0]['trial_period_type']:'';

                    // continue subscription
                    $old_expire_date = $subscription_info[0]['expire_date'];
                    if( $old_expire_date == '' or $old_expire_date == '0000-00-00' or $old_expire_date == '1970-01-11')
                    {
                        $old_expire_date = date('Y-m-d');
                    }
                    $new_expire_date = date('Y-m-d',strtotime('+'.$trial_period_value.' '.$trial_period_type,strtotime($old_expire_date)));
                    $this->payment_model->continue_subscr($item_number,$new_expire_date);
                    // _continue subscription

                    // inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                    $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
                    // send email to user
                    send_system_email_to_user($subscription_info[0]['user_id'],'user_payment_notification',array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($new_expire_date,false)));
                    // notify all administrators by email
                    send_system_subscription_to_admins('admin_payment_notification', array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4), "subscription_id"=>$item_number, "transaction_id"=>$transaction_id, "amount"=>$summ));
                    // _inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                }
                // _check for correct TRIAL payment
            }
            // else payment is for REGULAR period
            else
            {
                // check for correct regular payment
                if( isset($subscription_info[0]['regular_price']) and floatval($subscription_info[0]['regular_price']) === floatval($summ) )
                {
                    // accept transaction
                    $this->payment_model->accept_trans($transaction_id);

                    $regular_period_value = (isset($subscription_info[0]['regular_period_value']))?intval($subscription_info[0]['regular_period_value']):0;
                    $regular_period_type = (isset($subscription_info[0]['regular_period_type']))?$subscription_info[0]['regular_period_type']:'';

                    // continue subscription
                    $old_expire_date = $subscription_info[0]['expire_date'];
                    if( $old_expire_date == '' or $old_expire_date == '0000-00-00' or $old_expire_date == '1970-01-11')
                    {
                        $old_expire_date = date('Y-m-d');
                    }
                    $new_expire_date = date('Y-m-d',strtotime('+'.$regular_period_value.' '.$regular_period_type,strtotime($old_expire_date)));
                    $this->payment_model->continue_subscr($item_number,$new_expire_date);
                    // _continue subscription

                    // inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                    $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
                    // send email to user
                    send_system_email_to_user($subscription_info[0]['user_id'],'user_payment_notification',array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate($new_expire_date,false)));
                    // notify all administrators by email
                    send_system_subscription_to_admins('admin_payment_notification', array('product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4), "subscription_id"=>$item_number, "transaction_id"=>$transaction_id, "amount"=>$summ));
                    // _inform admins and user with system emails "user_payment_notification" and "admin_payment_notification"
                }
                // _check for correct regular payment
            }
        }
        // _subscription payment

        // subscription cancel
        if( $txn_type == 'subscr_cancel'  and $verified == 'VERIFIED' )
        {
                // accept transaction
                $this->payment_model->accept_trans($transaction_id);
                // _accept transaction

                // cancel subscription
                $this->payment_model->end_subscr($item_number);
                // _cancel subscription

                // inform admins and user with system emails "user_subscription_expired" and "admin_subscription_ended"
                $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
                // send email to user
                send_system_email_to_user($subscription_info[0]['user_id'],'user_subscription_expired',array('expired_product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
                // notify all administrators by email
                send_system_subscription_to_admins('admin_subscription_ended',array('user_login'=>$member_info['login'],'expired_product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
                // _inform admins and user with system emails "user_subscription_expired" and "admin_subscription_ended"
        }
        // _subscription cancel

        // subscription failed
        if( $txn_type == 'subscr_failed'  and $verified == 'VERIFIED' )
        {
            // accept transaction
                $this->payment_model->fail_trans($transaction_id);
            // _accept transaction
        }
        // _subscription failed


        // subscription eot
        if( $txn_type == 'subscr_eot' and $verified == 'VERIFIED' )
        {
            // accept transaction
            $this->payment_model->accept_trans($transaction_id);
            // _accept transaction

            // cancel subscription
            $this->payment_model->end_subscr($item_number);
            // _cancel subscription

            // inform admins and user with system emails "user_subscription_expired" and "admin_subscription_ended"
            $member_info = $this->member_model->get_member_info($subscription_info[0]['user_id']);
            // send email to user
            send_system_email_to_user($subscription_info[0]['user_id'],'user_subscription_expired',array('expired_product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
            // notify all administrators by email
            send_system_subscription_to_admins('admin_subscription_ended',array('user_login'=>$member_info['login'],'expired_product_name'=>array('object_id'=>$subscription_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
            // _inform admins and user with system emails "user_subscription_expired" and "admin_subscription_ended"
        }
        // _subscription eot

        // subscription modify
        if( $txn_type == 'subscr_modify'  and $verified == 'VERIFIED' )
        {
                // accept transaction
                $this->payment_model->accept_trans($transaction_id);
                // _accept transaction
        }
        // _subscription modify

        return true;

    }

    /**
     * The main method of paypal (handles the process of starting subscription payment)
     *
     * @global array
     * @author Makarenko Sergey
     * @copyright 2008
     * @return boolean
     */
    function index()
    {
        global $_helper_CONFIG;
        check_user_auth();
        $this->load->model('cart_model');
        $this->load->model('payment_model');
        $this->load->model('user_auth_model');
        $this->load->model('coupons_model');
        $uid = intval($this->user_auth_model->uid);
        $products = $this->cart_model->product_list();
				
				
        if( !isset($products) or !is_array($products) or sizeof($products)<=0 )
        {
            redirect_page('<{user_redirect_title}>','market/active');
            return true;
        }	

        if(!$this->auto_additional_profiles())
        {
            return;
        }
        //The substitution of billing information from user billing profile
        //$this->auto_billing_profile();

        $paypal_fields = array();
        $paypal_fields = _view('user/paypal/paypal_form',array('POST'=>prepare_post()),true);
        $submit_action = $this->input->post('submit_action');
        $need_billing_info = config_get('system','config','member_force_billing_info_input');

        $paypal_errors = array();

        // check paypal fields
        if( isset($submit_action) and $submit_action=='order' )
        {
            $paypal_errors = $this->_paypal_check_fields(prepare_post());
            if($paypal_errors['is_error']==1)
            {
                $paypal_errors = $paypal_errors['errors'];
            }
            else
            {
                $paypal_errors = array();
            }
        }
        // _check_paypal fields

        $payment_errors = array();
        if( $need_billing_info!==false and intval($need_billing_info)>0 )
        {
        // check standart fields
            if(
            $need_billing_info!==false and intval($need_billing_info)==1
            and
            isset($submit_action) and $submit_action=='order'
            )
            {
                $payment_errors = $this->_check_payment_fields(prepare_post());
                if($payment_errors['is_error']==1)
                {
                    $payment_errors = $payment_errors['errors'];
                }
                else
                {
                    $payment_errors = array();
                }
            }
        // _check_standart fields
        }
        if( (is_array($paypal_errors) and sizeof($paypal_errors)>0
            or
            is_array($payment_errors) and sizeof($payment_errors)>0
            )
            and
            isset($submit_action) and $submit_action=='order'
           )
        {
            $errors = array_merge($paypal_errors,$payment_errors);
            $this->_show_payment_form($paypal_fields,'paypal/',$errors,prepare_post());
            return true;
        }
        elseif( (!isset($submit_action) or $submit_action!='order') and $need_billing_info!==false and intval($need_billing_info)>0 )
        {
            $errors = array();
            $this->_show_payment_form($paypal_fields,'paypal/',$errors,prepare_post());
            return true;
        }

        $final_products_info = session_get('final_products_info');
        $products = session_get('products');
        if( !isset($final_products_info)
            or sizeof($final_products_info)<=0
            or !isset($final_products_info['products'])
            or sizeof($final_products_info['products'])<=0
            or !isset($products)
            or sizeof($products)<=0
            )
        {
           redirect_page('<{user_redirect_title}>','market/active');
           return true;
        }


        $paypal_product_id = 0;

        // one product per payment (so we get only one product from array of products)
        reset($products);
        list($product_id, $product_information) = each($products);

        if( intval($product_id)>0 )
        {
            //if subscription is NOT available then clear cart and redirect to active products page
            if( $this->payment_model->is_subscr_available($product_id,$uid) )
            {
                // clear shopping cart
                $this->cart_model->clear_cart();

                redirect_page('<{user_redirect_title}>','market/active');
                return false;
            }
            $paypal_product_id = intval($product_id);
        }

        // no errors
        if( intval($paypal_product_id)>0 )
        {
            // if this vars are not set then probably product is FREE, redirect to the CART
            if (!isset($final_products_info['products'][$paypal_product_id]['regular_period_type']) ||
                !isset($final_products_info['products'][$paypal_product_id]['regular_period_value']) )
            {
                redirect_page('<{user_redirect_title}>','cart/');
                return false;
            }

            //format the regular period string to the format like "month3"
            $regular_period_type = $final_products_info['products'][$paypal_product_id]['regular_period_type'];
            $regular_period_value = $final_products_info['products'][$paypal_product_id]['regular_period_value'];
            if( $regular_period_value==1 && $regular_period_type=='<{common_day}>' )    { $period = 'day'; }
            if( $regular_period_value==1 && $regular_period_type=='<{common_month}>' )  { $period = 'month'; }
            if( $regular_period_value==3 && $regular_period_type=='<{common_month}>' )  { $period = 'month3'; }
            if( $regular_period_value==6 && $regular_period_type=='<{common_month}>' )  { $period = 'month6'; }
            if( $regular_period_value==1 && $regular_period_type=='<{common_year}>' )   { $period = 'year'; }
            if( $regular_period_value==5 && $regular_period_type=='<{common_year}>' )   { $period = 'year5'; }

            // get product info array from DB to set trial period type
            $product_info = $this->payment_model->get_product_info($paypal_product_id,$uid,$final_products_info['user_force_trial']);

            //if product is FREE, then redirect to cart; payment controller should be FREE_PAYMENT
            if ($product_info[0][$period]<=0)
            {
                redirect_page('<{user_redirect_title}>','cart/');
                return false;
            }

/*            //count the price in session using discount values from session
            $counted_price_from_db = $product_info[0][$period];
            $counted_price_from_db = $this->payment_model->use_discount($counted_price_from_db,$product_info[0]['discount_type'],$product_info[0]['discount']);
            $counted_price_from_db = $this->payment_model->use_discount($counted_price_from_db,$final_products_info['products'][$paypal_product_id]['coupon_type'],$final_products_info['products'][$paypal_product_id]['coupon_value']);*/

            //if data in SESSION is NOT UP TO DATE with data in DB then redirect to CART to update the data
            if ( !$this->payment_model->Check_session_data_is_up_to_date($uid) )
            {
                redirect_page('<{user_redirect_title}>','cart/');
                return false;
            }

            // set additional info
            $user_info_id = intval(0);
            $POST = prepare_post();
            $addition_fields = array();
/*            $addition_fields['field1'] = (isset($POST['field1']))?$POST['field1']:'';
            $addition_fields['field2'] = (isset($POST['field2']))?$POST['field2']:'';*/
            $user_info_id = $this->set_additional_profiles();
            // _set additional info

            //subscription type: 1 - one time; 2 - recurring
            $subscr_type = ($final_products_info['products'][$paypal_product_id]['recouring']) ? 2 : 1;
            //create subscription
            $subscription_id = $this->payment_model->create_subscr($paypal_product_id,$uid,$period,$subscr_type,$user_info_id,config_get('system','config','currency_code'));
            if( intval($subscription_id)<=0 )
            {
                // clear shopping cart
                $this->cart_model->clear_cart();

                redirect_page('<{user_redirect_title}>','market/active');
                return false;
            }

            // inform admins with system email "admin_subscription_started"
            $this->load->model("member_model");
            $member_info = $this->member_model->get_member_info($uid);
            send_system_subscription_to_admins('admin_subscription_started', array('user_login'=>$member_info['login'],'product_name'=>array('object_id'=>$paypal_product_id,'object_type'=>4)));
            // _inform admins with system email "admin_subscription_started"

            // mark coupon as USED if there is some
            $product_coupon_info = $this->coupons_model->check_coupon($final_products_info['products'][$paypal_product_id]['coupon_code'], $paypal_product_id, $uid);
            if ( $product_coupon_info['result']===true )
            {
                $this->coupons_model->use_coupon($final_products_info['products'][$paypal_product_id]['coupon_code'], $paypal_product_id, $subscription_id);
            }

            $paypal = array();
            $paypal['sandbox'] = intval(config_get('payment','1','sandbox'));
            $paypal['business'] = (config_get('payment','1','business')!=false)?config_get('payment','1','business'):'';
            $paypal['currency_code'] = (config_get('system','config','currency_code')!=false)?config_get('system','config','currency_code'):'';
            $paypal['product_name'] = $final_products_info['products'][$paypal_product_id]['name'];
            $paypal['subscription_id'] = intval($subscription_id);

            // if trial period is not used
            if( !$this->payment_model->check_used_trial($uid,$paypal_product_id) && $final_products_info['user_force_trial'])
            {
                $paypal['trial_price'] = amount_to_print($final_products_info['products'][$paypal_product_id]['trial_price']);
                $paypal['trial_period_value'] = $final_products_info['products'][$paypal_product_id]['trial_period_value'];
                $paypal['trial_period_type'] = mb_strtoupper(mb_substr($product_info[0]['trial_period_type'],0,1));
            }
            else // else trial period is not allowed
            {
                $paypal['trial_price'] = amount_to_print(0);
                $paypal['trial_period_value'] = 0;
                $paypal['trial_period_type'] = mb_strtoupper(mb_substr($product_info[0]['trial_period_type'],0,1));
            }

            //count the regular price using discount and coupon code
            $regular_price_with_discounts = $product_info[0][$period];
            $regular_price_with_discounts = $this->payment_model->use_discount($regular_price_with_discounts,$product_info[0]['discount_type'],$product_info[0]['discount']);
            if ($product_coupon_info['result']===true)
            {
                $regular_price_with_discounts = $this->payment_model->use_discount($regular_price_with_discounts,$final_products_info['products'][$paypal_product_id]['coupon_type'],$final_products_info['products'][$paypal_product_id]['coupon_value']);
            }

            $paypal['regular_price'] = amount_to_print($regular_price_with_discounts);
            $paypal['regular_period_value'] = $final_products_info['products'][$paypal_product_id]['regular_period_value'];;
            $paypal['regular_period_type'] = mb_strtoupper(mb_substr($products[$paypal_product_id]['period'],0,1));

            $paypal['recurring'] = ($subscr_type==2) ? 1 : 0;

            // clear shopping cart
            $this->cart_model->clear_cart();
			
			
			// for view form
			
			if(isset($paypal['title']) and !empty($paypal['title']))
            {
                $paypal['if_title'] = array(array());
            }
			else
			{
				$paypal['if_title'] = array();
			}
			if( isset( $paypal['sandbox'] ) and intval($paypal['sandbox'])==1 )
			{
				$paypal['p_form'] = '<form id="paymentform" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="POST">';
			}
			else
			{
				$paypal['p_form'] = '<form id="paymentform" action="https://www.paypal.com/cgi-bin/webscr" method="POST">';
			}
			$site_url_return = site_url('market/after_buy');
			$site_url_notify = site_url('paypal/ipn');
			$site_url_cancel = site_url('market/active');
			
			$business = isset($paypal['business'])?output($paypal['business']):'';
			$currency_code = (isset($paypal['currency_code']) and !empty($paypal['currency_code']))?output($paypal['currency_code']):'USD';
			$product_name = isset($paypal['product_name'])?output(word_wrap($paypal['product_name'],126,4)):'';
			$subscription_id = isset($paypal['subscription_id'])?$paypal['subscription_id']:'';
			$recurring = isset($paypal['recurring'])?$paypal['recurring']:'0';
			$trial_price = (isset($paypal['trial_price']) and floatval($paypal['trial_price'])>0)?amount_to_print($paypal['trial_price']):'0.00';
			$trial_period_value = isset($paypal['trial_period_value'])?$paypal['trial_period_value']:'';
			$trial_period_type = isset($paypal['trial_period_type'])?$paypal['trial_period_type']:'';
			$regular_price = (isset($paypal['regular_price']) and floatval($paypal['regular_price'])>0)?amount_to_print($paypal['regular_price']):'1';
			$regular_period_value = isset($paypal['regular_period_value'])?$paypal['regular_period_value']:'';
			$regular_period_type = isset($paypal['regular_period_type'])?$paypal['regular_period_type']:'';
			$paypal['p_form'].= '<script>$("#paymentform").ready(function(){$("#paymentform").submit();});</script>'.'<input type="hidden" name="cmd" value="_xclick-subscriptions">
				<input type="hidden" name="undefined_quantity" value="0">
				<input type="hidden" name="business" value="'.$business.'">
				<input type="hidden" name="currency_code" value="'.$currency_code.'">
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="return" value="'.$site_url_return.'">
				<input type="hidden" name="notify_url" value="'.$site_url_notify.'">
				<input type="hidden" name="cancel_return" value="'.$site_url_cancel.'">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="no_note" value="1">
		
				<input type="hidden" name="item_name" value="'.$product_name.'">
				<input type="hidden" name="item_number" value="'.$subscription_id.'">
				<input type="hidden" name="src" value="'.$recurring.'">
		
				<input type="hidden" name="a1" value="'.$trial_price.'">
				<input type="hidden" name="p1" value="'.$trial_period_value.'">
				<input type="hidden" name="t1" value="'.$trial_period_type.'">
		
				<input type="hidden" name="a3" value="'.$regular_price.'">
				<input type="hidden" name="p3" value="'.$regular_period_value.'">
				<input type="hidden" name="t3" value="'.$regular_period_type.'">
		
				<input type="submit" value="<{cart_paypal_buy_now_button}>" >
				</form>';
			
            // process order
            $this->_process_order($paypal);
            return true;
        }
        // _no errors

        //there was some error
        redirect_page('<{user_redirect_title}>','market/active');
        return false;
        // _there was some error
    }

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $paypal
	 * @return true
	 */
    function _process_order($paypal)
    {
       //_view('user/paypal/paypal_form_proceed',$paypal);
	   print_page('user/paypal/paypal_form_proceed.html',$paypal);
        return true;
    }

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $POST
	 * @return array
	 */
    function _paypal_check_fields($POST)
    {
        $return_array  = array();
        $return_array['is_error'] = intval(0);

        return $return_array;
    }

}
?>
