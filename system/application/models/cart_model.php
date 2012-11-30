<?php
/**
 * 
 * THIS FILE CONTAINS Cart_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file payment_model.php
 */
require_once ('payment_model.php');
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH SHOPPING CART
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Cart_model extends Payment_model {

    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Cart_model()
    {
        parent::Payment_model();
    }

    /**
     * clear shopping cart
     *
     */
    function clear_cart()
    {
        // clear shopping cart
        session_set('coupons','');
        session_set('products','');
        session_set('payment_system_id','');
        session_set('checkout','');
        session_set('final_products_info','');
        session_set('name_domen','');
        session_set('additional_profiles','');
        fb('CLEAR_CART');
        // _clear shopping cart
    }
    /**
     * List of products
     *
     * @param integer $user_id
     * @param integer $user_force_trial
     * @return mixed
     */
    function product_list($user_id=0,$user_force_trial=0)
    {
        $user_id = intval($user_id);
        $products = session_get('products');
        if( !is_array($products) or sizeof($products)<=0 )
        {
            return false;
        }
    
        foreach( $products as $product_id=>$product_info )
        {
            $tmp_db_info = $this->get_product_info($product_id,$user_id,$user_force_trial);
            if( $tmp_db_info!== false )
            {
                $products[$product_id]['dbinfo'] = $tmp_db_info[0];
            }
            else
            {
                $products[$product_id]['dbinfo'] = array();
            }
            unset($tmp_db_info);
        }
        
        if( is_array($products) or sizeof($products)>0 )
        {
            return $products;
        }
        return false;
    }

    /**
     * Add product
     *
     * @param mixed $product_id
     * @param string $period
     * @param mixed $type
     * @return boolean
     */
    function add($product_id,$period,$type)
    {
        $product_id = intval($product_id);
        $type = intval($type);
        
        if( $product_id<=0 or $type<=0 or empty($period) )
        {
            //echo __LINE__.': false<br>';
            return false;
        }
    
        if( $this->get_product_info($product_id) === false )
        {
            return false;
        }
    
        if( !in_array($period,array('day','month','month3','month6','year','year5')) )
        {
            //echo __LINE__.': false<br>';
            return false;
        }

        session_set('products','');
        $products = session_get('products');
        if( !isset($products) ){ $products = array(); }
        $products[$product_id] = array(
                                        'type' => $type,
                                        'period' => $period
                                      );
        return  session_set('products',$products);
    
    }
	
	/**
     * Check is cart contains hosted product
     *
     * @return boolean
     */
	function Is_contains_product_hosted()
	{
		if(Functionality_enabled('admin_product_domain')===true && isset($_SESSION['final_products_info']) && isset($_SESSION['final_products_info']['products']) && is_array($_SESSION['final_products_info']['products']))
		{
			foreach($_SESSION['final_products_info']['products'] as $product)
			{
				if(isset($product['product_type']) && $product['product_type']==PRODUCT_HOSTED)
				{
					return true;
				}
			}			
		}
		return false;
	}
    

    /**
     * Remove product
     *
     * @param integer $product_id
     * @return boolean
     */
    function remove($product_id)
    {
        $product_id = intval($product_id);
        
        if( $product_id<=0 )
        {
            return false;
        }
    
        $products = session_get('products');
        if( isset($products[$product_id]) )
        {
            unset($products[$product_id]); 
        }

        return  session_set('products',$products);
    }
    
}
?>
