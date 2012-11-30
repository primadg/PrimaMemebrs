<?php
/**
 * 
 * THIS FILE CONTAINS News CLASS
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
* Class is responsible for displaying news for user.
* User sees news list according his login status and selected language
*
*/
/**
 * 
 * THIS Class is responsible for displaying news for user.
 * User sees news list according his login status and selected language
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class News extends User_Controller {
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function News()
    {
    	parent::User_Controller();
    	$this->load->model('news_model');

        $this->load->helper('html_helper');
        $this->load->helper('cookie');
    }
    
    
    /**
    * Enter description here...
    *
    */
    function preview($design,$section='unreg')
    {
        $section=($section=='reg'||$section=='unreg') ? $section : 'unreg';        
        if(!empty($design) && file_exists(config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/".$design."/".$section."/"))
        {
            if(!defined("NS_PREVIEW_DESIGN"))
            {
                define("NS_PREVIEW_DESIGN",$design."/".$section."/");
            }
            $this->latest();        
        }        
    }


  /**
   * Redirect to latest news
   *
   */
  function index()
  {
      redirect('/news/latest');
  }


  /**
   * Shows the latest 5 news
   *
   * @author Drovorubov
   *
   */
  function latest()
  {
    //Get if user is loggined or not
    $this->load->model('user_auth_model');
    $is_loginned = $this->user_auth_model->is_auth();
    //log this to the "User_logs" table in DB
    $this->load->model('user_log_model');
    $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/news/latest");

    //Get language id
    $this->load->model('user_model');
    $lang = $this->user_model->get_lang(intval($this->user_auth_model->uid));
    //Get news list
    //$data = $this->news_model->last(1,5,'by_date','desc',$lang,$is_loginned);
    $data = $this->news_model->news_list(1,5,'by_date','desc',$lang,$is_loginned);
    //Prepare news list for view
    //Set cookie
    $back_url_enc = encode_url("news/latest");
    delete_cookie("BACK_URL");
    set_cookie('BACK_URL', $back_url_enc, 0);
    //Load view
    if(isset($data['items']) && is_array($data['items']) && sizeof($data['items'])>0)
    {
        $data['if_news_list']=array(array());
        $data['else_news_list']=array();
        $i=0;
        foreach($data['items'] as $key=>$value)
        {
            $data['items'][$key]['name']=word_wrap(output($value['name']), 50, 0);
            $data['items'][$key]['date']=nsdate($value['date'],false);
            $data['items'][$key]['descr']=mb_substr(output($value['descr']),0,40);
            $data['items'][$key]['id']=md5($value['id']);
            for($j=0;$j<5;$j++)
            {
                $data['items'][$key]['if_item_'.$j]=($j==$i)?array(array()):array();
            }
            $i++;
        }
        $data['submit_button']=submit_button(base_url().'news/all',"<{user_news_button_all_news}>");
    }
    else
    {
        $data['if_news_list']=array();
        $data['else_news_list']=array(array());
    }
    print_page('user/news_latest.html',$data);
    //_view('user/news_latest',$data);
  }


  /**
   * Shows news list according the page and per page values
   *
   *
   * @author Drovorubov
   * @param integer $page
   * @param integer $per_page
   * @param string $sort_by
   * @param string $sort_how
   * @param integer $isChanged
   */
  function all($page=1, $per_page=5, $sort_by = 'date', $sort_how = 'asc')
  {
    if( empty($page) || intval($page) <= 0 )
    {
        $page = 1;
    }

    if( empty($per_page) || intval($per_page) < 5 )
    {
        $per_page = intval($this->input->post('per_page'));
        if($per_page < 5)
        {
            $per_page = 5;
        }
    }

    if( empty($sort_by) )
    {
        $sort_by = 'date';
    }

    //Get if user is loggined or not
    $this->load->model('user_auth_model');
    $is_loginned = $this->user_auth_model->is_auth();

    //log this to the "User_logs" table in DB
    $this->load->model('user_log_model');
    $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/news/all/$page/$per_page/$sort_by/$sort_how");

    //Get language id
    $this->load->model('user_model');
    $sort_param=array(
    'date'=>'by_date',
    'name'=>'by_subject'
    );
    $temp_sort_by=isset($sort_param[$sort_by]) ? $sort_param[$sort_by] : $sort_param['date'];
    
    $lang = $this->user_model->get_lang(intval($this->user_auth_model->uid));
    //Get news list
    //$data = $this->news_model->all($page,$per_page, $sort_by, $sort_how, $lang,$is_loginned);
    $data = $this->news_model->news_list($page,$per_page, $temp_sort_by, $sort_how, $lang,$is_loginned);
    
    $data['current_page'] = $page;
    if($data['total'] > $per_page)
    {
      $data['pages'] = ceil($data['total'] / $per_page);
    }
    else
    {
      $data['pages'] = 1;
    }

    $data['sort_by'] = $sort_by;
    $data['sort_how'] = $sort_how;
    //Prepare news list for view
    $data['items'] = $this->_prepare_allnews($data['items']);

    //Set cookie
    $back_url_enc = encode_url("news/all/".$page."/".$per_page);
    delete_cookie("BACK_URL");
    set_cookie('BACK_URL', $back_url_enc, 0);
    //Load view
    
    $settings=array();
    $settings['url']=base_url().'news/all/';
    $settings['table_width']='700px';
    //$settings['table_class']='first_class second_class';
    $settings['order']=array($sort_by=>$sort_how);
    $settings['pager']=array('current_page'=>$data['current_page'], 'per_page'=>$data['per_page'], 'pages'=>$data['pages']);
    
    $settings['columns']=array(
    'date'=>array(
        'width'=>'100px',
        'name'=>'<{user_news_all_table_date}>',
        'sortable'=>true
        //,'link'=>'param1/param2'
    ),
    'name'=>array(
        'width'=>'100px',
        'name'=>'<{user_news_all_table_subject}>',
        'sortable'=>true
    ),
    'descr'=>array(
        'name'=>'<{user_news_all_table_description}>'
    ));
    
    $items=$data['items'];
    foreach($items as $key=>$value)
    {
        $items[$key]['name']=word_wrap(output($value['name']), 30, 0);
        $items[$key]['date']=nsdate($value['date'],false);
        
        $items[$key]['descr']=array(
        'text'=>word_wrap(output($value['descr']), 30, 0),
        'link'=>base_url().'news/show/'.md5($value['id']),
        'class'=>'align_left'
        //,'link_class'=>'first_class second_class'
        );        
    }    
    $data['news_table']=print_table($items,$settings);
    print_page('user/news_all.html',$data);
    //_view('user/news_all',$data);
  }


  /**
   * Shows news info according news id. If no id it redirects to news list
   *
   * @author Drovorubov
   * @param integer $id
   */
  function show($id=0)
  {
    //Get last URL from cookie
    $back_url = decode_url(get_cookie('BACK_URL'));

    if(empty($id))
    {
        if(!empty($back_url))
        {
            redirect($back_url);
        }
        else
        {
            redirect('/news/all/');
        }
    }
    //Get language id
    $this->load->model('user_model');
    $this->load->model('user_auth_model');
    $lang = $this->user_model->get_lang(intval($this->user_auth_model->uid));

    //log this to the "User_logs" table in DB
    $this->load->model('user_log_model');
    $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/news/show/$id");

    //Get news data from DB
    if (!$data = $this->news_model->get($id,$lang)) {
    if(!empty($back_url))
        {
            redirect($back_url);
        }
        else
        {
            redirect('news/latest');
        }
    }
    //Prepare news items for view
    $data['items'] = $this->_prepare_newsitems($data['items']);
    //Set back URL for page back link
    //$data['back_url'] = $back_url;
    //Load view
    $data['name']=output($data['items']['name']);
    $data['date']=isset($data['items']['date']) ? nsdate($data['items']['date']) : "";
    $data['add']=output($data['items']['add']);    
    $data['submit_button']=submit_button(base_url().$back_url,"<{user_news_button_back}>");
    print_page('user/news_show.html',$data);    
    //_view('user/news_show',$data);
  }

    /**
    * Prepare news list for showing in the view
    *
    * @param array $data
    * @return array
    */
    function _prepare_allnews($data)
    {
        if(is_array($data) && count($data) > 0)
        {
            foreach($data as $key=>$val)
            {
                
            }
        }
        else
        {
            $data = array();
        }
        return $data;
    }


    /**
    * Prepare news items for showing in the view
    *
    * @param array $data
    * @return array
    */
    function _prepare_newsitems($data)
    {
        if(is_array($data) && count($data) > 0)
        {
            foreach($data as $key=>$val)
            {
                switch($key)
                {
                    case 'name':
                    {
                        $data['name'] = word_wrap($data['name'],50,0,' ');

                        break;
                    }
                    case 'descr':
                    {
                        $data['descr'] = word_wrap($data['descr'],60,0,' ');
                        break;
                    }
                    case 'add':
                    {
                        $data['add'] = word_wrap($data['add'],60,0,' ');
                        break;
                    }
                }
            }
        }
        else
        {
            $data = array();
        }
        return $data;
    }

    /**
    * Prepare news list for showing in the view
    *
    * @param array $data
    * @return array
    */
    function _prepare_latestnews($data)
    {
        if(is_array($data) && count($data) > 0)
        {
            foreach($data as $key=>$val)
            {
                // Convert field Name
                $data[$key]['name'] = output($data[$key]['name']);
                // Convert field Descr
                $data[$key]['descr'] = output($data[$key]['descr']);

            }
        }
        else
        {
            $data = array();
        }
        return $data;
    }
}
?>
