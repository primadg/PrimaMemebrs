<?php
/**
* Updater_model
*
* A model for the correction (in Prima Membership)
*
* @package		Prima Memebership
* @author		onagr
* @filesource
*/

// ------------------------------------------------------------------------

/**
* Updater_model
*
* A model for the correction (in Prima Membership Update)
*
* @package		Prima Membership
* @category	model
* @author		onagr
*/
class Updater_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author onagr
    * @return void
    */
    function Member_group_model()
    {
        parent::Model();
    }
    
    /**
    * Get_new_id
    * Converting to integer version (2.3.1 -> 20301).
    * @param string $version
    * @return integer version
    */
    function Normalize_version($version)
    {
        $v=explode(".",$version);
        $version=intval($v[0])*pow(100,2);
        $version+=isset($v[1]) ? intval($v[1])*pow(100,1) : 0;
        $version+=isset($v[2]) ? intval($v[2])*pow(100,0) : 0;
        if(defined('NS_DEBUG_VERSION') && isset($v[3]))
        {
            $version+=floatval("0.".$v['3']);
        }
        return $version;
    }
    /**
     * Try update
     *
     * @param string $cur_ver
     * @param string $need_ver
     */
    function Try_update($cur_ver,$need_ver)
    {
        $cur_ver=$this->normalize_version($cur_ver);
        $need_ver=$this->normalize_version($need_ver);
        if(defined('NS_DEBUG_VERSION') && isset($_SESSION['db_error']) && $_SESSION['db_error']==true)
        {
            fb("EXTRA DB PATCHING!");
            $cur_ver=20008;
        }
        $_SESSION['db_error']=false;
        
        if($cur_ver != $need_ver)
        {
            $this->update($cur_ver,ceil($need_ver));
        }
    }
    
    /**
    * Update()
    * Updated to reflect the current version.
    * @param unknown_type $cur_ver
    * @param unknown_type $need_ver
    * @return boolean
    */
    function Update($cur_ver,$need_ver)
    {
        fb('Version:'.$need_ver.'('.NEEDSECURE_VERSION.') Current:'.$cur_ver);
        $absolute_path=config_get('system','config','absolute_path');
        ////////////////////update to 2.0.1///////////////////////
        $ver=$this->normalize_version("2.0.1");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");
            //add users into member group - general(id=1)
            $this->db->select('id');
            $this->db->distinct();
            $query = $this->db->get_where(db_prefix.'Users', array('id!=' => 1));
            $users=$query->result_array();
            if(count($users))
            {
                $this->db->delete(db_prefix.'Member_groups_members',array('group_id' => 1)); 
                foreach($users as $user)
                {
                    $this->db->insert(db_prefix.'Member_groups_members',array('group_id' => 1,'user_id'=>$user['id']));     
                }
            }
            //add products into member group - general(id=1)
            $this->db->select('id');
            $this->db->distinct();
            $query = $this->db->get_where(db_prefix.'Products', array('closed' => 0));
            $products=$query->result_array();
            if(count($products))
            {
                $this->db->delete(db_prefix.'Member_groups_products',array('group_id' => 1)); 
                foreach($products as $product)
                {
                    $this->db->insert(db_prefix.'Member_groups_products',array('group_id' => 1,'product_id'=>$product['id'],'available'=>1));     
                }
            }
        }
        /////////////////end of update to 2.0.1///////////////////
        
        ////////////////////update to 2.0.2///////////////////////
        $ver=$this->normalize_version("2.0.2");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");
            //add currency codes
            $cr=array('CAD','EUR','GBP','USD','JPY','AUD','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN');
            config_set($cr,'system','config','currency_list');
            $payment_systems=config_get('PAYMENT');
            $currency=array();
            $currency['paypal']=$cr;
            $currency['authorize_net']=array('USD');
            
            foreach($payment_systems as $key=>$sys)
            {
                if(isset($currency[$sys['controller']]))
                {
                    config_set($currency[$sys['controller']],'PAYMENT',$key,'accepted_currency');
                }                
            }
            config_set("Sorry, the system is offline now!",'system','status','offline_msg');
            config_set(0,'system','config','history_kept');
        }
        /////////////////end of update to 2.0.2///////////////////
        
        ////////////////////update to 2.0.3///////////////////////
        $ver=$this->normalize_version("2.0.3");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");            
        }
        
        /////////////////end of update to 2.0.3///////////////////
        
        ////////////////////update to 2.0.4///////////////////////
        $ver=$this->normalize_version("2.0.4");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");            
        }
        /////////////////end of update to 2.0.4///////////////////
        
        ////////////////////update to 2.0.5///////////////////////
        $ver=$this->normalize_version("2.0.5");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");            
        }
        /////////////////end of update to 2.0.5///////////////////
        
        ////////////////////update to 2.0.7///////////////////////
        $ver=$this->normalize_version("2.0.7");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");            
            config_set('0000000000','system','status','debug_mode');
        }
        /////////////////end of update to 2.0.7///////////////////

        ////////////////////update to 2.0.8///////////////////////
        $ver=$this->normalize_version("2.0.8");
        if($cur_ver<$ver && $need_ver>=$ver)
        {
            //execute_sql_file($absolute_path."patch/dump_patch_".$ver.".sql");            
        }
        /////////////////end of update to 2.0.8///////////////////
        
        ///////////////////update sql patches/////////////////////
        $sql_files=scandir($absolute_path."patch/");
        for($i=ceil($cur_ver);$i<=$need_ver;$i++)
        {
            if(in_array("dump_patch_".$i.".sql",$sql_files))
            {
                if(execute_sql_file($absolute_path."patch/dump_patch_".$i.".sql"))
                {
                    fb("Execute: ".$absolute_path."patch/dump_patch_".$i.".sql");
                }
                else
                {
                    fb("EXECUTION ERROR: ".$absolute_path."patch/dump_patch_".$i.".sql");
                }
            }
        }
        ////////////end of update update sql patches//////////////

/**
 * Here insert future update
 */


/**
 * Set version to config 
 */
        config_set(NEEDSECURE_VERSION,'system','config','version');        
    }   
}
?>
