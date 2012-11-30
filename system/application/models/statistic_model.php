<?php
/**
 * 
 * THIS FILE CONTAINS Statistic_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH STATISTIC
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Statistic_model extends Model
{
    /**
    * Class contstructor - just calls parent::Model for now
    *
    * @author Val Petruchek
    * @return void
    */
    function Statistic_model()
    {
        parent::Model();
    }


    /**
     * reads all details about a certain transaction
     *
     * @param integer $transact_id
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Transaction_detailed_get($transact_id = 0)
    {
        $data = Array();

        //prepare the full query string
        $this->db->select("transact.id as transact_id, transact.subscription_id as subscr_id, transact.summ as summ, subscr.currency_code as currency_code, transact.info as info, transact.completed as completed, transact.`date` as transdate, transact.pay_system_id as pay_system, userinfo.billing_name as billing_name, userinfo.street as street, userinfo.city as city, userinfo.state_code as state_code, userinfo.zip as zip_code, userinfo.country_code as country_code, userinfo.phone as phone, userinfo.additional as additional_fields");
        $this->db->from(db_prefix.'Transactions as transact');
        $this->db->join(db_prefix.'Subscriptions as subscr', 'transact.subscription_id=subscr.id', 'left');
        $this->db->join(db_prefix.'User_info as userinfo', 'subscr.user_info_id=userinfo.id', 'left');
        $this->db->where('transact.id', $transact_id);
        $this->db->limit(1);
        $query = $this->db->get();

        //echo $this->db->last_query(); die('debug');

        //process the result if there are any rows
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                //get the real name of the payment system accordingly to pay_system ID
                if ($row['pay_system'] > 0)
                {
                    $row['pay_system'] = config_get('PAYMENT', $row['pay_system'], 'NAME');
                }
                else
                {
                    $row['pay_system'] = 'Free payment';
                }
                //parse info from payment system, if there are some
                $row['info'] = unserialize($row['info']);
                if (!is_array($row['info']))
                {
                    // something went wrong, initialize to empty array
                    $row['info'] = array();
                }
                //parse additional fields, if there are some
                $row['additional_fields'] = @unserialize($row['additional_fields']);
                if (!is_array($row['additional_fields']))
                {
                    // something went wrong, initialize to empty array
                    $row['additional_fields'] = array();
                }
                //add to $data['transact'] array new row of data from DB
                $data['transact'] = $row;
            }
        }
        $query->free_result();
        return $data;
    }


    /**
     * reads all additional language constants and adds them to the $data array
     *
     * @param array $data
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Transaction_detailed_vars_add($data)
    {
        if (!isset($data) || !is_array($data))
        {
            $data=array();
        }

        //create temp vars to pass PHP array into javascript array
        $temp_vars_set = array();
        $temp_vars_set['panel_script'] = base_url()."js/admin/statistic/transaction_detailed.js";
        $data['temp_vars_set'] = $temp_vars_set;

        //create message array at the html page
        $messages = array();
        $data['messages'] = $messages;

        //create error messages array at the html page
        $mess_err = array();
        $data['mess_err'] = $mess_err;

        return $data;
    }

	/**
	 * Get transaction and information about it
	 *
	 * @param integer $subscr_id
	 * @param array $post
	 * @return array
	 */
    function Transactions_get($subscr_id = 0, $post)
    {
        //here we'll put the resulting array
        $data = Array();

        //here we will prepare params for search filters if they are set
        if (is_array($post) && isset($post['filter']))
        {
            $data['filter'] = $post['filter'];
            $filter = $post['filter'];
            //if value of 'search_word' <input> element is not EMPTY then create search by it
            if ($filter[1]!='' && in_array($filter[0], Array('summ','user_name','product_name')))
            {
                // set the flags to filter
                switch ($filter[0])
                {
                    case "summ":
                        $filter_summ = true; break;
                    case "user_name":
                        $filter_user_name = true; break;
                    case "product_name":
                        $filter_product_name = true; break;
                }
            }
            //if value of 'date_from' <input> element is not EMPTY then create search by it
            if ($filter[2]!='')
            {
                $filter_date_from = true;
            }
            //if value of 'date_to' <input> element is not EMPTY then create search by it
            if ($filter[3]!='')
            {
                $filter_date_to = true;
            }
        }

        //prepare the full query string
        $this->db->select("product.id as product_id, transact.id as transact_id, user.login as user_name, transact.`date` as transdate, transact.summ as summ, subscr.currency_code as currency_code, transact.pay_system_id as pay_system");
        $this->db->from(db_prefix.'Transactions as transact');
        $this->db->join(db_prefix.'Subscriptions as subscr', 'transact.subscription_id=subscr.id','left');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
        $this->db->join(db_prefix.'Users as user', 'protect.user_id=user.id', 'left');
        $this->db->join(db_prefix.'Products as product', 'protect.product_id=product.id', 'left');
        //$this->db->join(db_prefix.'Language_data as lang', 'product.id=lang.object_id AND lang.object_type=4', 'left');
        $this->db->where('transact.subscription_id', $subscr_id);

        if (isset($filter_date_from))
        {
            $this->db->where('DATE(transact.date)>=', convert_date($filter[2]));
        }
        if (isset($filter_date_to))
        {
            $this->db->where('DATE(transact.date)<=', convert_date($filter[3]));
        }

        if (isset($filter_summ))            { $this->db->where('transact.summ', floatval($filter[1])); }
        if (isset($filter_user_name))       { $this->db->like('user.login', $filter[1], 'both'); }
        $query = $this->db->get();
        $t=$query->result_array();
        foreach ($t as $key=>$row)
        {
            //get the real name of the payment system accordingly to pay_system ID
            if ($row['pay_system'] > 0)
            {
                $row['pay_system'] = config_get('PAYMENT', $row['pay_system'], 'NAME');
            }
            else
            {
                $row['pay_system'] = 'Free payment';
            }
            $t[$key] = $row;
        }

        $total=count($t);
        $data['pagers'] = pager_ex($post, $total, array('transact_id','user_name','transdate','summ','currency_code','pay_system'));
        $params = $data['pagers']['params'];
        $filter_add=array();
        if (isset($filter_product_name)){ $filter_add=array(array('product_name', $filter[1], 'both')); }
        $add=array();
        $sort_flag=in_array($params['column'],array('transact_id','summ')) ? SORT_NUMERIC :SORT_STRING;
        $CI =& get_instance();
        $CI->load->model("lang_manager_model");
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name'),'product_id',array('col'=>$params['column'],'order'=>$params['order'],'sort_flags'=>$sort_flag,'limit'=>$params['limit'],'offset'=>$params['offset'],'filter'=>$filter_add),false,&$add);

        $total=$add['total'];
        $data['pagers'] = pager_ex($post, $total, array('transact_id','user_name','transdate','summ','currency_code','pay_system'));
        $params = $data['pagers']['params'];

        $data['transact']=$t;
        return $data;
    }


    /**
     * reads all additional language constants and adds them to the $data array
     *
     * @param array $data
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Transactions_stats_vars_add($data)
    {
        if (!isset($data) || !is_array($data))
        {
            $data=array();
        }

        //create temp vars to pass PHP array into javascript array
        $temp_vars_set = array();
        $temp_vars_set['panel_script'] = base_url()."js/admin/statistic/transactions_stats.js";
        $data['temp_vars_set'] = $temp_vars_set;

        //create message array at the html page
        $messages = array();
        $data['messages'] = $messages;

        //create error messages array at the html page
        $mess_err = array();
        $mess_err['date_from'] = "<{admin_stats_transact_err_date}>";
        $mess_err['date_to'] = "<{admin_stats_transact_err_date}>";
        $data['mess_err'] = $mess_err;

        return $data;
    }


    /**
     * Counts sum in multidimesion array by some key
     *
     * @param array $arr
     * @param unknown_type
     * @return mixed integer/float
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function _Sum_subarrays_by_key($arr, $key)
    {
        $sum = 0;
        foreach($arr as $sub_array)
        {
            $sum += $sub_array[$key];
        }
        return $sum;

    }


    /**
     * Sorts multidimesion array by some key
     *
     * @param array $data - multidimention array
     * @param array keys - array(key=>column1, sort=>desc, type=>numeric)
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function _Multisort_array($data, $keys)
    {
        // make rows as columns
        $sort_array = Array();
        foreach ($data as $uniqid => $row)
        {
            foreach ($row as $key => $value)
            {
                $sort_array[$key][$uniqid] = $value;
            }
        }
        // prepare sort expression
        $sort = '';
        $sort .= '$sort_array[\'' . $keys['key'] . '\']';
        if ($keys['sort'])
        {
            $sort .= ',SORT_' . strtoupper($keys['sort']);
        }
        if ($keys['type'])
        {
            $sort .= ',SORT_' . strtoupper($keys['type']);
        }
        $sort .= ',$data';
        // prepare sort function
        $sort = 'array_multisort(' . $sort . ');';
        eval($sort);
        // return full array
        return $data;
    }


    /**
     * reads statistics from DB about products and product groups
     *
     * @param array $post
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Total_stats_get($post)
    {
        // here we'll put the resulting array
        $data = Array();

        // here we will prepare params for search filters if they are set
        if (is_array($post) && isset($post['filter']))
        {
            $data['filter'] = $post['filter'];
            $filter = $post['filter'];
            // if value of 'date_from' <input> element is not EMPTY then create search by it
            if ($filter[0]!='')
            {
                $filter_date_from = true;
            }
            // if value of 'date_to' <input> element is not EMPTY then create search by it
            if ($filter[1]!='')
            {
                $filter_date_to = true;
            }
        }

        //prepare some parts of a query string
        $filter_date_from_str = '';
        $filter_date_to_str = '';
        if (isset($filter_date_from))
        {
            $data['filter']['date_to']=convert_date($filter[0]);
            if($data['filter']['date_to']!==false)
            {
                $filter_date_from_str = " AND transact.`date`>=DATE('".$data['filter']['date_to']."')";
            }
        }
        if (isset($filter_date_to))
        {
            $data['filter']['date_from']=convert_date($filter[1]);
            if($data['filter']['date_from']!==false)
            {
                $filter_date_to_str = " AND transact.`date`<=DATE_ADD(DATE('".$data['filter']['date_from']."'),INTERVAL '1' DAY)";
            }
        }
        //prepare the full query string
        $this->db->select("groups.id as group_id, products.id as product_id, COUNT(transact.id) as transactions_count, SUM(transact.summ) as amount, subscr.currency_code as currency_code");
        $this->db->from(db_prefix.'Product_groups as groups');
        $this->db->join(db_prefix.'Products as products', 'groups.id=products.group_id', 'left');
        $this->db->join(db_prefix.'Protection as protect', 'products.id=protect.product_id', 'left');
        $this->db->join(db_prefix.'Subscriptions as subscr', 'protect.subscr_id=subscr.id', 'left');
        $this->db->join(db_prefix.'Transactions as transact', 'subscr.id=transact.subscription_id AND transact.completed=1'.$filter_date_from_str.$filter_date_to_str, 'left');
        $this->db->group_by('groups.id, products.id');
        $query = $this->db->get();

        //process the result if there are any rows
        if ($query->num_rows() > 0)
        {

            $CI =& get_instance();
            $CI->load->model("lang_manager_model");
            $t=$CI->lang_manager_model->combine_with_language_data($query->result_array(),3,array('name'=>'group_name'),'group_id',array('col'=>'group_name'),false,&$add_params);

            $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name'),'product_id',false,false,&$add_params);
            //$result['count']=count($result['items']);


            $query_result = $t;
            $data['total']['percentage'] = 100;
            $data['total']['transactions_count'] = $this->_Sum_subarrays_by_key($query_result, 'transactions_count');
            $data['total']['amount'] = $this->_Sum_subarrays_by_key($query_result, 'amount');
            $data['total']['currency_code'] = config_get('system','config','currency_code');
            $data['total']['products_count'] = 0;
            //that means that the previous product_group was not exist
            $old_product_group_name = '';
            $group_count = 0;

            // go by all products, create product groups with subarrays, resort elements
            foreach ($query_result as $row)
            {
                // have we looked through all products in group and should create next new product group?
                if ($old_product_group_name != $row['group_name'])
                {
                    $old_product_group_name = $row['group_name'];
                    $group_count += 1;
                    //initialize data in new product group
                    $data['total_stats'][$group_count]['group_name'] = $old_product_group_name;
                    $data['total_stats'][$group_count]['percentage'] = 0;
                    $data['total_stats'][$group_count]['transactions_count'] = 0;
                    $data['total_stats'][$group_count]['amount'] = 0;
                }

                // check $row['amount']==0.0 to avoid division by zero
                if (!empty($data['total']['amount']) || $data['total']['amount']!=0.0)
                {
                    $row['percentage'] = $row['amount'] / $data['total']['amount'] * 100;
                }
                else
                {
                    $row['percentage'] = 0;
                }

                //save new product in the current product group array
                $data['total_stats'][$group_count]['products'][] = $row;
                //increase products count
                $data['total']['products_count'] += 1;

                //increase count of percentage, amount and transactions by products values in it
                $data['total_stats'][$group_count]['percentage'] += $row['percentage'];
                $data['total_stats'][$group_count]['transactions_count'] += $row['transactions_count'];
                $data['total_stats'][$group_count]['amount'] += $row['amount'];
            } // _foreach $query_result





            $count = $group_count; // no pager in a pure state, so we symbolically put $count=1
            // generate pager data
            $data['pagers'] = pager_ex($post, $count, array('product_name','percentage','transactions_count','amount'));
            $params = $data['pagers']['params'];

            $data['total_stats']=array_slice($data['total_stats'], $params['offset'], $params['limit']);


            //define the type of each sortable column in order to use with _Multisort_array function
            switch ($params['column'])
            {
            case 'product_name':
                $params['type'] = 'STRING';
                break;
            case 'percentage':
                $params['type'] = 'NUMERIC';
                break;
            case 'transactions_count':
                $params['type'] = 'NUMERIC';
                break;
            case 'amount':
                $params['type'] = 'NUMERIC';
                break;
            default:
                $params['type'] = 'STRING';
            } // switch

            // re-sort the products in subarrays (in product groups)
            foreach ($data['total_stats'] as $i => $product_group)
            {
                $data['total_stats'][$i]['products'] = $this->_Multisort_array($product_group['products'], array('key'=>$params['column'], 'sort'=>$params['order'], 'type'=>$params['type']));
            }
        }
        else
        {
            $data['pagers'] = pager_ex($post, 0, array('product_name','percentage','transactions_count','amount'));
        }
        $query->free_result();

        return $data;
    }


    /**
     * reads all additional language constants and adds them to the $data array
     *
     * @param array $data
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Total_stats_vars_add($data)
    {
        if (!isset($data) || !is_array($data))
        {
            $data=array();
        }

        //create temp vars to pass PHP array into javascript array
        $temp_vars_set = array();
        $temp_vars_set['panel_script'] = base_url()."js/admin/statistic/total_stats.js";
        $data['temp_vars_set'] = $temp_vars_set;

        //create message array at the html page
        $messages = array();
        $data['messages'] = $messages;

        //create error messages array at the html page
        $mess_err = array();
        $mess_err['date_from'] = "<{admin_stats_total_err_date}>";
        $mess_err['date_to'] = "<{admin_stats_total_err_date}>";
        $data['mess_err'] = $mess_err;

        return $data;
    }

	/**
	 * Get subscription and info about it
	 *
	 * @param array $post
	 * @return array
	 */
    function Subscriptions_get($post)
    {
        //here we'll put the resulting array
        $data = Array();

        //here we will prepare params for search filters if they are set
        if (is_array($post) && isset($post['filter']))
        {
            $data['filter'] = $post['filter'];
            $filter = $post['filter'];
            //if value of 'search_word' <input> element is not EMPTY then create search by it
            if ($filter[1]!='' && in_array($filter[0], Array('subscr_id','regular_price','user_name','product_name')))
            {
                // set the flags to filter
                switch ($filter[0])
                {
                    case "subscr_id":
                        $filter_subscr_id = true; break;
                    case "regular_price":
                        $filter_regular_price = true; break;
                    case "user_name":
                        $filter_user_name = true; break;
                    case "product_name":
                        $filter_product_name = true; break;
                }
            }
            //if value of 'date_from' <input> element is not EMPTY then create search by it
            if ($filter[2]!='')
            {
                $filter_date_from = true;
            }
            //if value of 'date_to' <input> element is not EMPTY then create search by it
            if ($filter[3]!='')
            {
                $filter_date_to = true;
            }
        }

        //prepare the full query string
        $this->db->select("product.id as product_id, subscr.id as subscr_id, user.login as user_name, subscr.cdate as subscr_date, subscr.type as subscr_type, subscr.regular_price as regular_price, subscr.currency_code as currency_code");
        $this->db->from(db_prefix.'Subscriptions as subscr');
        $this->db->join(db_prefix.'Protection as protect', 'subscr.id=protect.subscr_id', 'left');
        $this->db->join(db_prefix.'Users as user', 'protect.user_id=user.id', 'left');
        $this->db->join(db_prefix.'Products as product', 'protect.product_id=product.id', 'left');
        if (isset($filter_subscr_id))       { $this->db->where('subscr.id', intval($filter[1])); }
        if (isset($filter_regular_price))   { $this->db->where('subscr.regular_price', floatval($filter[1])); }
        if (isset($filter_user_name))       { $this->db->like('user.login', $filter[1], 'both'); }
        if (isset($filter_date_from))       { $this->db->where("DATE(subscr.cdate)>='".convert_date($filter[2])."'"); }
        if (isset($filter_date_to))       { $this->db->where("DATE(subscr.cdate)<='".convert_date($filter[3])."'"); }
        $query = $this->db->get();

        $t=$query->result_array();
        $total=count($t);
        $data['pagers'] = pager_ex($post, $total, array('subscr_id','user_name','product_name','subscr_date','subscr_type','regular_price'));
        $params = $data['pagers']['params'];

        $filter_add=array();
        if (isset($filter_product_name)){ $filter_add=array(array('product_name', $filter[1], 'both')); }
        $add=array();
        $sort_flag=in_array($params['column'],array('subscr_id','regular_price')) ? SORT_NUMERIC :SORT_STRING;
        $CI =& get_instance();
        $CI->load->model("lang_manager_model");
        $t=$CI->lang_manager_model->combine_with_language_data($t,4,array('name'=>'product_name'),'product_id',array('col'=>$params['column'],'order'=>$params['order'],'sort_flags'=>$sort_flag,'limit'=>$params['limit'],'offset'=>$params['offset'],'filter'=>$filter_add),false,&$add);

        $total=$add['total'];
        $data['pagers'] = pager_ex($post, $total, array('subscr_id','user_name','product_name','subscr_date','subscr_type','regular_price'));
        $params = $data['pagers']['params'];


        $data['subscr']=$t;
        return $data;
    }


    /**
     * reads all additional language constants and adds them to the $data array
     *
     * @param array $data
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Subscriptions_stats_vars_add($data)
    {
        if (!isset($data) || !is_array($data))
        {
            $data=array();
        }

        //create temp vars to pass PHP array into javascript array
        $temp_vars_set = array();
        $temp_vars_set['panel_script'] = base_url()."js/admin/statistic/subscriptions_stats.js";
        $data['temp_vars_set'] = $temp_vars_set;

        //create message array at the html page
        $messages = array();
        $data['messages'] = $messages;

        //create error messages array at the html page
        $mess_err = array();
        $mess_err['date_from'] = "<{admin_stats_subscr_err_date}>";
        $mess_err['date_to'] = "<{admin_stats_subscr_err_date}>";
        $data['mess_err'] = $mess_err;

        return $data;
    }


    /*********************************************************************
    *                   GRAPHS begin
    *********************************************************************/

     /**
    * Return transactions sums for the year grouped by months
    *
    * @author Drovorubov
    * @param string $year
    * @return mixed array/false
    */
    function graph_info4year($year)
    {
        $year = intval($year);
        if( $year < 1900 )
        {
            return false;
        }
        //Prepare select query
        $sql = "SELECT ROUND(SUM(`summ`),2) as summ, MONTH(`date`) as `month`
                FROM (`db_prefix_Transactions`)
                WHERE `completed` = 1
                AND YEAR(`date`) = " . $year .
                " GROUP BY MONTH(`date`)";
        //Execute query
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            $tmp = $query->result_array();
            return $tmp;
        }
        return false;
    }

    /**
    *
    *
    * @author Drovorubov
    * @param string $period_start
    * @param string $period_end
    * @param string $group_by
    * @return array
    */
    function graph_info4period($period_start,$period_end,$group_by)
    {
        switch($group_by)
        {
            case 'year':
            {
                $sel_line = " YEAR(`date`) as `year` ";
                $group_by_line = " GROUP BY YEAR(`date`) ";
                $order_by_line = " ORDER BY `year` ASC";
                break;
            }
            case 'month':
            {
                $sel_line = " MONTH(`date`) as `month` ";
                $group_by_line = " GROUP BY MONTH(`date`) ";
                $order_by_line = " ORDER BY `month` ASC ";
                break;
            }
            case 'day':
            {
                $sel_line = " DAY(`date`) as `day` ";
                $group_by_line = " GROUP BY DAYOFMONTH(`date`) ";
                $order_by_line = " ORDER BY `day` ASC ";
                break;
            }
            default:
            {
                $sel_line = " YEAR(`date`) as `year` ";
                $group_by_line = " GROUP BY YEAR(`date`) ";
                $order_by_line = " ORDER BY `year` ASC ";
            }
        }
        //Prepare select query
        $sql = "SELECT ROUND(SUM(`summ`),2) as summ, " . $sel_line .
               " FROM (`db_prefix_Transactions`)
                 WHERE `completed` = 1
                 AND TO_DAYS(`date`) >= TO_DAYS('" . $period_start . "') " .
                " AND TO_DAYS(`date`) <= TO_DAYS('" . $period_end . "') " .
                " " . $group_by_line .
                " " . $order_by_line;
        //Execute query
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            $tmp = $query->result_array();
            return $tmp;
        }
        return false;
    }

    /*********************************************************************
    *                   GRAPHS end
    *********************************************************************/




}
?>
