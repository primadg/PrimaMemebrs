<?php
/**
 * 
 * THIS FILE CONTAINS News_model CLASS
 *  
 * @package Needsecure
 * @author Peter Yaroshenko
 * @version uknown
 */
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH NEWS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class News_model extends Model {

	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function News_model()
    {
        parent::Model();
    }
    /**
     * Get news list
     *
     * @param mixed $page
     * @param mixed $count
     * @param unknown_type $sort_by
     * @param unknown_type $sort_how
     * @param mixed $language
     * @param boolean $is_loginned
     * @return array
     */
    function news_list($page,$count,$sort_by,$sort_how,$language,$is_loginned=false, $is_special=false)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );
	
		if ($is_loginned)
			$count = intval(config_get('SYSTEM','MAIN_PAGE', 'news_amount'));
		else
			$count = intval(config_get('SYSTEM','MAIN_PAGE', 'unreg_news_amount'));
		if (!$count)
		{
			$count = 3;
		}
		
        if( intval($page) <= 0 || intval($count) <= 0 || intval($language) <= 0 )
        {
            return $rv;
        } 
        $rv['per_page'] = $count;
        // Convert sorting values for DB
        $this->db->select('news.id, news.date');
        $this->db->from(db_prefix.'News news');
		if( $is_special )
		{
			$this->db->where('news.special_news = 1');
		}
	
		if( !$is_loginned )
		{
			$this->db->where('news.members_only = 0');
		}
		$this->db->where('news.published = 1');
		
		$query = $this->db->get();
        
        
        $sort_param=array();
        $sort_param['by_date']='date';
        $sort_param['by_subject']='name';
        $sort_param['by_by_descr']='descr';
        $sort_by=array_key_exists($sort_by,$sort_param) ? $sort_param[$sort_by] : $sort_param['by_date'];
        $t=$query->result_array();fb($t, 'ttt - ');
        $total=count($t);
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,6,array('name'=>'name','descr'=>'descr','add'=>'add'),'id',array('col'=>$sort_by,'order'=>$sort_how,'limit'=>$count,'offset'=>($page-1)*$count),false,&$add_params);
        
        $rv['total']=$total;
        $rv['count']=count($t);
        $rv['items']=$t;        
        $rv['result'] = true;        
        
        /* echo "<pre>";
        print_r($rv);
        echo "</pre>"; */
        
        return $rv;
    }
    
    

    /**
     * Selects last 5 items from news table
     *
     * 
     * @author Drovorubov
     * @param integer $page
     * @param integer $count
     * @param string $sort_by
     * @param string $sort_how
     * @param integer $language
     * @param bool $is_loginned
     * @return array
     */
    function last($page,$count,$sort_by,$sort_how,$language,$is_loginned=false)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if( intval($page) <= 0 || intval($count) <= 0 || intval($language) <= 0 )
        {
            return $rv;
        } 

        // Convert sorting values for DB
        //$sort_by = $this->_get_news_order($sort_by);
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';
        // Prepare SQL query
        
        $this->db->select('news.id, news.date');
        $this->db->from(db_prefix.'News news');
        if( !$is_loginned )
        {
            $this->db->where('news.members_only = 0');
        }
        $this->db->where('news.published = 1');
        $query = $this->db->get();
        
        
        $sort_param=array();
        $sort_param['by_date']='date';
        $sort_param['by_subject']='name';
        $sort_param['by_by_descr']='descr';
        $sort_by=array_key_exists($sort_by,$sort_param) ? $sort_param[$sort_by] : $sort_param['by_date'];
        $t=$query->result_array();
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,6,array('name'=>'name','descr'=>'descr'),'id',array('col'=>$sort_by,'order'=>$sort_how,'limit'=>$count,'offset'=>0),false,&$add_params);
        $rv['count']=count($t);
        $rv['items']=$t;
        $rv['result'] = true;        

        return $rv;
    }

    

    /**
     * Select items from news and language_data tables
     * according page, count values and language
     * Form array to return 
     *
     * @author Drovorubov
     * @param integer $page
     * @param integer $count
     * @param string $sort_by
     * @param string $sort_how
     * @param integer $language
     * @param bool $is_loginned     
     * @return array
     */
    function all($page,$count,$sort_by,$sort_how,$language,$is_loginned=false)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if( intval($page) <= 0 || intval($count) <= 0 || intval($language) <= 0 )
        {
            return $rv;
        }

        $rv['per_page'] = $count;

        // Set order before selection
        $sort_by = $this->_get_news_order($sort_by);
        // Set order type
        $sort_how = ($sort_how == 'asc') ? 'ASC' : 'DESC';
            
        //Get total news count
        $this->db->select('count(*) as all_rows');
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id = language_data.object_id');
        $this->db->where('language_data.language_id',$language);
        $this->db->where('language_data.object_type = 6');        
        if( !$is_loginned )
        {
            $this->db->where('news.members_only = 0');
        }        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
            $rv['total'] = $row->all_rows;
        }
        
        //Set rows start limit for select
        if($page > 1 && $rv['total'] > 0)
        {
            $row_start = intval(($page - 1) * $count);
    		if( $row_start >= $rv['total'] )
    		{
                $page = ceil( $rv['total'] / $count );
                $row_start = intval(($page - 1) * $count);
    		}
        }
        else
        {
            $row_start = 0;
        }
     
        //Get news list
        $this->db->select('news.id');
        $this->db->select("DATE_FORMAT(news.date, '%d.%m.%Y') as date");
        $this->db->select('language_data.name, language_data.descr');
        $this->db->from(array(db_prefix.'Language_data language_data', db_prefix.'News news'));
        $this->db->where('news.id=language_data.object_id');
        $this->db->where('language_data.language_id',$language);
        $this->db->where('language_data.object_type = 6');        
        if( !$is_loginned )
        {
            $this->db->where('news.members_only = 0');
        }        
        $this->db->limit($count,$row_start);
        $this->db->order_by($sort_by, $sort_how);
        $query = $this->db->get();
        
        $rv['count']  =  $query->num_rows() ;
        if ( $rv['count'] > 0 )
        {
            $rv['items'] = $query->result_array();
        }        
   
        $rv['result'] = true;

        return $rv;
    }


    /**
     * Get news info by id
     *
     * @author Drovorubov
     * @param string $id
     * @param string $language
     * @return array
     */
    function get($id, $language)
    {
        $rv = array(
        "result"=>false,
        "per_page"=>0,
        "total"=>0,
        "count"=>0,
        "items"=>array()
        );

        if(empty($id))
        {
            return $rv;
        }

        //Get news item
        //$this->db->select('language_data.name, language_data.descr, language_data.add');
        $this->db->select('news.id, news.date, news.members_only');        
        $this->db->from(db_prefix.'News news');
        $this->db->where('md5(news.id)',$id);
        $this->db->where('news.published = 1');        
        $query = $this->db->get();
        
        
        $t=$query->result_array();
        $CI =& get_instance();        
        $CI->load->model("lang_manager_model"); 
        $t=$CI->lang_manager_model->combine_with_language_data($t,6,array('name'=>'name','descr'=>'descr','add'=>'add'),'id',false,false,&$add_params);
        if ( count($t) > 0 )
        {
            $rv['result'] = true;
            $rv['items']=$t[0];
        } 
        else return FALSE;

        return $rv;
    }

    

    
    
    /**
    * Converts param for ORDER in SELECT 
    *
    * @author Drovorubov
    * @param string $param
    * @return string
    */    
    function _get_news_order($param)
    {
        $rv = '';
        switch($param)
        {
            case 'by_date':
            {
                $rv = 'news.date';
                break;
            }
            case 'by_subject':
            {
                $rv = 'language_data.name';
                break;
            }
            case 'by_by_descr':
            {
                $rv = 'language_data.descr';
                break;
            }            
            default:
            {
                $rv = 'news.date';
            }
        }    
        
        return $rv;
    }

    
    
}

?>
