<?php
/**
 * 
 * THIS FILE CONTAINS Free_payment CLASS
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
 * Payment controller for FREE products purchases
 *
 * @package Needsecure
 * @author Makarenko Sergey
 * @copyright 2008
. */

class Free_payment extends Payment
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Free_payment()
    {
        parent::Payment();
    }

	/**
	 * Payment controller for FREE products purchases
	 *
	 * @global array
	 * @return boolean
	 */
    function index()
    {
        global $_helper_CONFIG;
        check_user_auth();
        $this->load->model('cart_model');
        $this->load->model('payment_model');
        $this->load->model('user_auth_model');
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

        $submit_action = $this->input->post('submit_action');
        $need_billing_info = config_get('system','config','member_force_billing_info_input');

        $payment_errors = array();
        if( $need_billing_info!==false and intval($need_billing_info)>0 )
        {
        //$submit_action
        // check standart fields
            if( $need_billing_info!==false and intval($need_billing_info)==1 and isset($submit_action) and $submit_action=='order' )
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
        // _check standart fields
        }

        if( is_array($payment_errors) and sizeof($payment_errors)>0 and isset($submit_action) and $submit_action=='order' )
        {
            $errors = $payment_errors;
            $this->_show_payment_form('','free_payment/',$errors,prepare_post());
            return true;
        }
        elseif( (!isset($submit_action) or $submit_action!='order') and $need_billing_info!==false and intval($need_billing_info)>0 )
        {
            $errors = array();
            $this->_show_payment_form('','free_payment/',$errors,prepare_post());
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

        if( $this->payment_model->is_subscr_available() )
        {
           redirect_page('<{user_redirect_title}>','market/active');
           return true;
        }

        $free_payment_product_id = 0;

        // one product per payment (so we get only one product from array of products)
        reset($products);
        list($product_id, $product_info) = each($products);

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
            $free_payment_product_id = intval($product_id);
        }

        // no errors
        if( intval($free_payment_product_id)>0 )
        {
            //if data in SESSION is NOT UP TO DATE with data in DB then redirect to CART to update the data
            if ( !$this->payment_model->Check_session_data_is_up_to_date($uid) )
            {
                redirect_page('<{user_redirect_title}>','cart/');
                return false;
            }

            // get product info array to set trial period type
            $product_info = $this->payment_model->get_product_info($free_payment_product_id,$uid,$final_products_info['user_force_trial']);

            $periods = Array();
            $periods[] = Array('day',   '<{common_day}>',   1);
            $periods[] = Array('month', '<{common_month}>', 1);
            $periods[] = Array('month3','<{common_month}>', 3);
            $periods[] = Array('month6','<{common_month}>', 6);
            $periods[] = Array('year',  '<{common_year}>',  1);
            $periods[] = Array('year5', '<{common_year}>',  5);
            foreach ($periods as $period)
            {
                // if the price for some period IS NOT FREE then change the period and redirect to the cart
                if ($product_info[0][$period[0]]>0)
                {
                    //update the period type in SESSION
                    $final_products_info['products'][$free_payment_product_id]['regular_period_type'] = $period[1];
                    $final_products_info['products'][$free_payment_product_id]['regular_period_value'] = $period[2];
                    session_set('final_products_info', $final_products_info);
                    //redirect to the cart with updated data in SESSION
                    redirect_page('<{user_redirect_title}>','cart');
                    return false;
                }
            }

            //this vars are needed because get_product_info doesn't return them
            $product_info['period'] = "year5";
            $product_info['type'] = 1;

            $final_products_info['products'][$free_payment_product_id]['regular_period_type'] = '<{common_year}>';
            $final_products_info['products'][$free_payment_product_id]['regular_period_value'] = 5;

            // set additional info
            $user_info_id = intval(0);
            $POST = prepare_post();
            $addition_fields = array();
            //$addition_fields['PRODUCT IS FREE'] = 'YES';
            $user_info_id = $this->set_additional_profiles();
            // _set additional info

            //create subscription
            $subscription_id = $this->payment_model->create_subscr($product_id,$uid,$product_info['period'],$product_info['type'],$user_info_id,config_get('system','config','currency_code'));
            if( intval($subscription_id)<=0 )
            {
                // clear shopping cart
                $this->cart_model->clear_cart();

                redirect_page('<{user_redirect_title}>','market/active');
                return false;
            }

            $free_payment = Array();
            $free_payment['trial_price'] = $final_products_info['products'][$free_payment_product_id]['trial_price'];
            $free_payment['regular_price'] = $final_products_info['products'][$free_payment_product_id]['regular_price'];
            $free_payment['regular_period_value'] = $final_products_info['products'][$free_payment_product_id]['regular_period_value'];
            $free_payment['regular_period_type'] = $final_products_info['products'][$free_payment_product_id]['regular_period_type'];

            if( $this->payment_model->check_used_trial($uid,$free_payment_product_id) )
            {
                // set used trial
                $this->payment_model->set_used_trial($uid,$free_payment_product_id);
            }

            // create transaction
            $transaction_id = $this->payment_model->create_transaction( $subscription_id,
                                                                        0 /*pay_system_id is FREE_PAYMENT*/,
                                                                        1 /*completed = yes*/,
                                                                        0 /*summ*/,
                                                                        Array("free_payment"=>"true") /*info*/
                                                                        );

            // clear shopping cart
            $this->cart_model->clear_cart();
            // _clear shopping cart

            // free order
            if( floatval($free_payment['regular_price']<=0) )
            {
                // ACCEPT ORDER, PERMIT ACCESS, REDIRECT TO market/after_buy

                $regular_period_type = $this->payment_model->Convert_to_period_type($free_payment['regular_period_type']);
                $new_expire_date = date('Y-m-d', strtotime('+'.$free_payment['regular_period_value'].' '.$regular_period_type));

                $this->payment_model->continue_subscr($subscription_id,$new_expire_date);

                // inform admins with system email "admin_subscription_started"
                $this->load->model("member_model");
                $member_info = $this->member_model->get_member_info($uid);
                send_system_subscription_to_admins('admin_subscription_started', array('user_login'=>$member_info['login'],'product_name'=>array('object_id'=>$free_payment_product_id,'object_type'=>4)));
                // _inform admins with system email "admin_subscription_started"

                redirect_page('<{user_redirect_title}>','market/after_buy');
                return true;
            }
            // _free order
        }
        // _no errors

        redirect_page('<{user_redirect_title}>','market/active');
        return false;
    }


}

?>
