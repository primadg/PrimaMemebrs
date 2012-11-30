<?php
/**
 * 
 * THIS FILE CONTAINS User_log_model CLASS
 *  
 * @package Prima DG
 * @author uknown
 * @version uknown
 */

/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH USER LOG
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
class User_log_model extends Model {

	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function User_log_model()
    {
        parent::Model();
    }

	/**
	 * Set user log
	 *
	 * @param mixed $uid
	 * @param string $REMOTE_ADDR
	 * @param string $referer
	 * @param string $url
	 * @param integer $product_id
	 * @return boolean
	 */
    function set($uid,$REMOTE_ADDR,$referer,$url,$product_id=NULL)
    {
        if ( !intval(config_get('SYSTEM', 'CONFIG', 'LOG_MEMBERS')) )
        {
            //if "Log members" == 0 then exit
            return true;
        }

        if( intval($uid)<=0 )
        {
            return false;
        }

        $data = array(
        'ip' => $REMOTE_ADDR,
        'http_referer' => $referer,
        'url' => $url,
        'user_id' => $uid,
        'product_id' => $product_id
        );
        $query = $this->db->insert(db_prefix.'User_logs',$data);
        if( $this->db->affected_rows() > 0 )
        {
            return true;
        }

        return false;
    }

	/**
	 * Enter description here...
	 *
	 * @return true
	 */
    function get()
    {
        return true;
    }

	/**
	 * Enter description here...
	 *
	 * @return true
	 */
    function last_online()
    {
        return true;
    }

}
?>
