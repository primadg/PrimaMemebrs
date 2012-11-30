<?php
/**
 * 
 * THIS FILE CONTAINS Logging_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH LOGS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Logging_model extends Model {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Logging_model()
    {
        parent::Model();
    }

    /**
     * Gets list of all action types that have been logged to Admin_logs table.
     * Used for filtering by type.
     *
     * @param boolean
     * @return array of types
     *
     * @author Val Petruchek
     * @copyright 2008
     */
    function get_action_list($is_admin=true)
    {
        $result = array();
        $this->db->select('action');
        $this->db->where('admin_id'.($is_admin?'<>':''),-1);
        $this->db->distinct();
        $this->db->order_by('action');
        $query = $this->db->get(db_prefix.'Admin_logs');

        foreach ($query->result_array() as $row)
        {
            $result[] = $row['action'];
        }
        return $result;
    }
	/**
	 * Get list of all admins
	 *
	 * @return array
	 */
    function get_admin_list()
    {
        $result = array();
        $this->db->select('login');
        $this->db->distinct();
        $this->db->order_by('login');
        $query = $this->db->get(db_prefix.'Admins');
        $result[] = 'UNDEFINED';
        foreach ($query->result_array() as $row)
        {
            $result[] = $row['login'];
        }
        return $result;
    }

	/**
	 * Get a list of actions for admin
	 *
	 * @todo admin_id, -1 for file_system log
	 * @param array $post
	 * @param boolean $is_admin
	 * @return array
	 */
    function admin_list($post,$is_admin=true)
    {
    /*
     * $filter[0] - start date
     * $filter[1] - end date
     * $filter[2] - TODO - admin_id, -1 for file_system log
     * $filter[3] - action type
     */

     //print_r ($post);
        $data   = array();
        $filter = array();

        $data['actions'] = $this->get_action_list($is_admin);
        if($is_admin)
        {
        $data['persons'] = $this->get_admin_list();
        }

                 $this->db->select('Admins.id, Admin_logs.action');
                 $this->db->from(db_prefix.'Admin_logs Admin_logs');
                 $this->db->join(db_prefix.'Admins Admins','Admins.id = Admin_logs.admin_id','LEFT');

        if(isset($post['filter'][0]) && strlen($post['filter'][0]) > 0)
        {
            $filter[0] = convert_date($post["filter"][0]) . ' 00:00:00';
            $data['filter'][0] = $post["filter"][0];

                $this->db->where("Admin_logs.time >= STR_TO_DATE('". $filter[0] ."','%Y-%m-%d %H:%i:%s')");
        }
        if(isset($post['filter'][1]) && strlen($post['filter'][1]) > 0)
        {
            $filter[1] = convert_date($post["filter"][1]) . ' 23:59:59';
            $data['filter'][1] = $post["filter"][1];
                $this->db->where("Admin_logs.time <= STR_TO_DATE('" . $filter[1] . "','%Y-%m-%d %H:%i:%s')");
        }

        //person filter

        if(isset($post['filter'][2])&&$is_admin)
        {
            $filter[2] = $post["filter"][2];
            if (!in_array($filter[2],$data['persons']))
            {
                $filter[2] = '-';
            }
            if ($filter[2] <> '-')
            {
                if($filter[2]=='UNDEFINED')
                {
                $this->db->where('Admins.id',null);
                }
                else
                {
                //$this->db->where('log.admin_id<>',0);
                //$this->db->where('log.admin_id<>',-1);
                $this->db->where('Admins.login',$filter[2]);
                }
            }
            $data['filter'][2] = $filter[2];
        }

        $this->db->where('Admin_logs.admin_id'.($is_admin?'<>':''),-1);

        //action filter
        if(isset($post['filter'][3]))
        {
            $filter[3] = $post["filter"][3];
            if (!in_array($filter[3],$data['actions']))
            {
                $filter[3] = '-';
            }
            if ($filter[3] <> '-')
            {
                $this->db->where('Admin_logs.action',$filter[3]);
            }
            $data['filter'][3] = $filter[3];
        }

        $count = $this->db->count_all_results();
        $data['pagers'] = pager_ex($post,$count,'time',2,'desc');
        $params = $data['pagers']['params'];


                 if($is_admin)
                 {
                 $this->db->select('IF(admin.id, CONCAT("<a href=\"mailto:",admin.email,"\">",admin.login,"</a>"), CONCAT("<{admin_msg_er_0019}>=",log.admin_id)) AS person');
                 }
                 $this->db->select('CONCAT("<{admin_log_",log.action,"}>") AS record');
                 $this->db->select('ip');
                 //$this->db->select('admin.id');
                 $this->db->select('details');
                 $this->db->select('`time`');
                 $this->db->select('CONCAT("<a href=\"#\" onclick=\"recDelete(",log.id,"); return false;\" style=\"cursor:pointer;\" title=\"<{admin_logging_btn_delete}>\"><img src=\"'.base_url().'img/ico_delete.png\" width=\"16\" height=\"16\" alt=\"<{admin_logging_btn_delete}>\" /></a>") AS action');
        if(isset($filter[0]))
        {
                $this->db->where("log.time >= STR_TO_DATE('". $filter[0] ."','%Y-%m-%d %H:%i:%s')");
        }
        if(isset($filter[1]))
        {
                $this->db->where("log.time <= STR_TO_DATE('" . $filter[1] . "','%Y-%m-%d %H:%i:%s')");
        }

        //person filter
        if(isset($filter[2]))
        {
            if ($filter[2] <> '-')
            {
                if($filter[2]=='UNDEFINED')
                {
                $this->db->where('admin.id',null);
                }
                else
                {
                $this->db->where('admin.login',$filter[2]);
                }
            }
        }

        $this->db->where('log.admin_id'.($is_admin?'<>':''),-1);

        //action filter
        if(isset($filter[3]))
        {
            if ($filter[3] <> '-')
            {
                $this->db->where('log.action',$filter[3]);
            }
        }
                 $this->db->from(db_prefix . 'Admin_logs log');
                 $this->db->join(db_prefix . 'Admins admin','admin.id = log.admin_id','LEFT');


                 $this->db->limit($params['limit'],$params['offset']);
                 $this->db->order_by($params['column'],$params['order']);

        $query = $this->db->get();
        $data['log'] = $query->result_array();

        //print_r($data['log']);

        for($i=0;$i<count($data['log']);$i++)
        {
            if ($data['log'][$i]['details'])
            {
                $data['log'][$i]['details'] = unserialize($data['log'][$i]['details']);
            }
        }
        //echo $this->db->last_query();
        return $data;
    }
	/**
	 * Get a list of actions for user
	 *
	 * @param array $post
	 * @return array
	 */
    function user_list($post)
    {
        $data   = array();
        $filter = array();


                 $this->db->select('Users.id');
                 $this->db->from(db_prefix.'User_logs User_logs');
                 $this->db->join(db_prefix.'Users Users','Users.id = User_logs.user_id','LEFT');

        if(isset($post['filter'][0]) && strlen($post['filter'][0]) > 0)
        {
            $filter[0] = convert_date($post["filter"][0]) . ' 00:00:00';
            $data['filter'][0] = $post["filter"][0];

                $this->db->where("User_logs.time >= STR_TO_DATE('". $filter[0] ."','%Y-%m-%d %H:%i:%s')");
        }
        if(isset($post['filter'][1]) && strlen($post['filter'][1]) > 0)
        {
            $filter[1] = convert_date($post["filter"][1]) . ' 23:59:59';
            $data['filter'][1] = $post["filter"][1];
                $this->db->where("User_logs.time <= STR_TO_DATE('" . $filter[1] . "','%Y-%m-%d %H:%i:%s')");
        }
        
        $count = $this->db->count_all_results();

        $data['pagers'] = pager_ex($post,$count,'time',2,'desc');
        $params = $data['pagers']['params'];


                 $this->db->select('`time`');
                 $this->db->select('IF(user.id, CONCAT("<a href=\"mailto:",user.email,"\">",user.login,"</a>"), CONCAT("<{admin_msg_er_0019}>=",log.user_id)) AS person');
//                 $this->db->select('CONCAT("<a href=\"mailto:",user.email,"\">",user.login,"</a>") AS person');
//                 $this->db->select('CONCAT("<a href=\"",log.url,"\">link</a>") AS urla');
                 $this->db->select('url');
                 $this->db->select('ip');
                 $this->db->select('log.http_referer AS referer');
        
        if(isset($filter[0]))
        {
                $this->db->where("log.time >= STR_TO_DATE('". $filter[0] ."','%Y-%m-%d %H:%i:%s')");
        }
        if(isset($filter[1]))
        {
                $this->db->where("log.time <= STR_TO_DATE('" . $filter[1] . "','%Y-%m-%d %H:%i:%s')");
        }
        
                 $this->db->from(db_prefix . 'User_logs log');
                 $this->db->join(db_prefix . 'Users user','user.id = log.user_id','LEFT');


                 $this->db->limit($params['limit'],$params['offset']);
                 $this->db->order_by($params['column'],$params['order']);

        $query = $this->db->get();
        $data['log']    = $query->result_array();
        return $data;
    }
	/**
	 * Delete record from admin logs 
	 *
	 * @param integer $id
	 * @return mixed
	 */
    function record_del($id)
    {
                 $this->db->select('id');
                 $this->db->from(db_prefix.'Admin_logs log');
                 $this->db->where('id',$id);
        $query = $this->db->get();
        $levels_list=$query->result_array();
        if(count($levels_list)>0)
        {
            if($this->db->delete(db_prefix.'Admin_logs', array('id' => $id)) && $this->db->affected_rows()>0)
            {
                return true;
            }
            else
            {
            return "<{admin_msg_er_0017}>"; // not deleted
            }
        }
        return '<{admin_msg_er_0018}>'; //not_found
    }
    /**
     * Delete one or all logs
     *
     * @param array $post
     * @return mixed
     */
    function Log_remove($post)
    {
        if(isset($post['action']) && $post['action']=='delete')
        {
            $table_name=(isset($post['table']) && $post['table']=='Admin_logs') ? 'Admin_logs' : 'User_logs';
            //delete certain log
            if(isset($post['id']) && intval($post['id'])>0)
            {
                $query = $this->db->get_where(db_prefix.$table_name, array('id' => $post['id']),1);
                $email_list=$query->result_array();
                if(count($email_list)>0)
                {
                    if($this->db->delete(db_prefix.$table_name, array('id' => intval($post['id']))) && $this->db->affected_rows()>0)
                    {
                        return true;
                    }
                    else
                    {
                        return "not_deleted";
                    }
                }
            }
            //delete expired log
            if(isset($post['limit']) && $post['limit']=='expired')
            {
                $period = intval(config_get("system","config","history_kept"));
                if($period>0)
                {
                    $this->db->where("NOW()>DATE_ADD(time,INTERVAL '".$period."' DAY)");
                    if($this->db->delete(db_prefix.$table_name) && $this->db->affected_rows()>0)
                    {
                        return true;
                    }
                    else
                    {
                        return "not_deleted";
                    } 
                }                
            }
            //delete all log
            if(isset($post['limit']) && $post['limit']=='all')
            {
                if($this->db->delete(db_prefix.$table_name) && $this->db->affected_rows()>0)
                {
                    return true;
                }
                else
                {
                    return "not_deleted";
                } 
            }
            return "not_found";
        }
        return "not_found";
    }
}
?>
