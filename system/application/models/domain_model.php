<?php

class Domain_model extends Model
{
    /**
     * Constructor. Gets CI pointer because we need to call it frequently
     *
     * @return void
     */
    function Domain_model()
    {
        parent::Model();
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
    
    function _sql_valid_product($table)
    {
        return "(($table.`closed`=0)AND($table.`product_type`=".PRODUCT_HOSTED."))";
    }

} // end class Domain_model
?>
