<?php
/**
* 
* THIS FILE CONTAINS Logging CLASS
* 
* @package Needsecure
* @author Peter Yaroshenko
* @version uknown
*/
/**
* Include file admin_controller.php
*/
require_once("user_controller.php");


/******************************
    User Market Controller
    By:     Peter Yaroshenko
    enc:    UTF-8
    tab:    4 space's
*******************************
*/
/**
* 
* THIS CLASS CONTAINS METHODS FOR WORKING WITH MARKET
* 
* @package Needsecure
* @author Peter Yaroshenko
* @version uknown
*/
class Market extends User_Controller
{
    /**
    * THIS METHOD SETS INITIAL VARS (constructor)
    */
    function Market()
    {
        parent::User_Controller();

        $this->load->model("market_model", "market");
        $this->load->model("product_group_model", "product_group");
        $this->load->model("product_model");
        $this->load->model("user_auth_model");
    }

    /**
    * DO NOTHING
    *
    */
    function index()
    {

    }

	/**
    * DO NOTHING
    *
    */
    function is_demo_user()
    {
		$this->user_auth_model->is_auth();
		//fb($this->user_auth_model->uid, 'is_demo_user');
		$_POST['error_mes'] = Functionality_enabled('admin_member_info_modify',$this->user_auth_model->uid);
		$this->sale();
    }

    /**
    * Enter description here...
    *
    * @param integer $page
    * @param integer $per_page
    */
    function active($page=1, $per_page=0)
    {
        check_user_auth();

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/market/active');

        $data=array("");
        $data['pager']='';
        $data['pager2']='';

        $data['currency']=config_get("system", "config", "currency_code");

        $page=(int)$page;
        if(!$page || $page<0)
        $page=1;

        $per_page=(int)$per_page;

        if(!$per_page)
        {
            $per_page=(int)input_post('per_page', 0);
        }

        if(!$per_page || $per_page <0)
        $per_page=config_get("PAGER", "default_perpage");

        $products=$this->market->active_list($this->user_auth_model->uid, $page, $per_page);


        $per_page=$data['per_page']=$products['per_page'];
        $page=$data['page']=$products['page'];
        $data['pager']="";
        if($products['count'])
        {
            $data['pager']=html_pager(base_url()."market/active/", $page, $per_page, ceil($products['total']    /   $per_page));
            //$data['pager2']=html_pager(base_url()."market/active/", $page, $per_page, ceil($products['total']    /   $per_page));
        }

        if($products['result'])
        {
        $nodes_data=array();
        $nodes_data['products']=$products['items'];
        $nodes_data['currency']=$data['currency'];
        $nodes_data['if_products'] = array();
        $nodes_data['else_products'] = array(array());
        $posters_path_original=config_get("product_posters", "path_original");
        $posters_path_previews=config_get("product_posters", "path_previews");
        $absolute_path=config_get("SYSTEM","CONFIG","ABSOLUTE_PATH");
        
        if(is_array($nodes_data['products']) && count($nodes_data['products']))
        {            
            $nodes_data['if_products'] = array(array());
            $nodes_data['else_products'] = array();
            
            foreach($nodes_data['products'] as $key=>$p)
            {
                $nodes_data['products'][$key]['if_pending']=($p['status']==2) ? array(array()) : array();
                $nodes_data['products'][$key]['if_subscr_end']=($p['status']==1) ? array(array()) : array();
                $nodes_data['products'][$key]['expire_date']=isset($p['expire_date']) ? nsdate($p['expire_date'],false) : "";
                $nodes_data['products'][$key]['regular_period_type']=isset($p['regular_period_type']) ? output($p['regular_period_type']) : "";
                
                $nodes_data['products'][$key]['name']=soft_wrap(output($p['name']), 30);
                $nodes_data['products'][$key]['cdate']=isset($p['cdate']) ? nsdate($p['cdate'],false) :"";
                
                $nodes_data['products'][$key]['if_dirs']=array();
                if(@is_array($p['dirs']))
                {
                    $nodes_data['products'][$key]['if_dirs']=array(array());
                    foreach($p['dirs'] as $k=>$dir)
                    {
                        $nodes_data['products'][$key]['dirs'][$k]['http_path']=output($dir['http_path']);
                        $nodes_data['products'][$key]['dirs'][$k]['dname']=output($dir['name']);
                    }
                }
                
                $nodes_data['products'][$key]['if_image']=array();
                if(isset($p['image']) && !empty($p['image']))
                {
                    $orig=file_exists($absolute_path.$posters_path_original.$p['image']) ? base_url().$posters_path_original.$p['image'] : false;
                    $prew=file_exists($absolute_path.$posters_path_previews.$p['image']) ? base_url().$posters_path_previews.$p['image'] : $orig;
                   
                    $nodes_data['products'][$key]['if_image']=array(array());
                    $nodes_data['products'][$key]['image']=$orig ? $orig : base_url()."img/no_image.jpg";
                    $nodes_data['products'][$key]['preview_image']=$orig ? $prew : base_url()."img/no_image.jpg";
                }
            }
        }        
        $data['products']=print_page("user/market_active_nodes.html", $nodes_data, TRUE);
            //$data['products']=_view("user/market_active_nodes", array("products"=>$products['items'], 'currency'=>$data['currency'] ), TRUE);            
        }
        else
        {
        $data['products']="";
        }
        
        print_page("user/market_active_page.html", $data);
        //_view("user/market_active_page", $data);
    }

    /**
    * Enter description here...
    *
    * @param integer $page
    * @param integer $per_page
    * @param integer $group_id
    */
    function sale($page=1, $per_page=0, $group_id=0)
    {
        $this->user_auth_model->is_auth();fb($_POST,'Post - ');

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/market/sale/$page/$per_page/$group_id");

        $data=array();
        $data['pager']='';
        $data['pager2']='';
		if (input_post('error_mes')!==false)
			$data['error_box'][]=array('display'=>1,'text'=>input_post('error_mes'));
        $data['currency']=config_get("system", "config", "currency_code");

        $group_id=(int)$group_id;
        if(!$group_id)
        {
            $group_id=(int)input_post('group_id', 0);
        }
        $data['group_id']=$group_id;

        $page=(int)$page;
        if(!$page || $page<0)
        $page=1;

        $per_page=(int)$per_page;

        if(!$per_page)
        {
            $per_page=(int)input_post('per_page', 0);
        }

        if(!$per_page || $per_page <0)
        $per_page=config_get("PAGER", "default_perpage");

        $data['products']=$this->market->sale_list($page, $per_page, $group_id, $this->user_auth_model->uid);

        $per_page=$data['per_page']=$data['products']['per_page'];
        $page=$data['page']=$data['products']['page'];

        $data['pager']="";
        if($data['products']['count'])
        {
            $data['pager']=html_pager(base_url()."market/sale/", $page, $per_page, ceil($data['products']['total']/$per_page), $group_id);
            //$data['pager2']=html_pager(base_url()."market/sale/", $page, $per_page, ceil($data['products']['total']/$per_page), $group_id);;
        }

        $CU=encode_url(base_url()."market/sale/$page/$per_page/$group_id");
        
        $nodes_data=array();
        $nodes_data['products']=$data['products']['items'];
        $nodes_data['currency']=$data['currency'];
        $nodes_data['cur_url']=output($CU);
        $nodes_data['if_products'] = array();
        $nodes_data['else_products'] = array(array());
        $posters_path_original=config_get("product_posters", "path_original");
        $posters_path_previews=config_get("product_posters", "path_previews");
        $absolute_path=config_get("SYSTEM","CONFIG","ABSOLUTE_PATH");
        
        if(is_array($nodes_data['products']) && count($nodes_data['products']))
        {
            fb($nodes_data['products'], 'products - ');
            $nodes_data['if_products'] = array(array());
            $nodes_data['else_products'] = array();
            
            foreach($nodes_data['products'] as $key=>$p)
            {
                $free=!(bool)($p['day'] + $p['month'] + $p['month3'] + $p['month6'] + $p['year'] + $p['unlimit']);
                $nodes_data['products'][$key]['if_free']=$free ? array(array()) : array();
				if ($free and Functionality_enabled('admin_member_info_modify',$this->user_auth_model->uid)!==true)
				{
					$nodes_data['products'][$key]['if_free_demo']=array(array());
					$nodes_data['products'][$key]['else_free_demo']=array();
				}
				else
				{
					$nodes_data['products'][$key]['if_free_demo']=array();
					$nodes_data['products'][$key]['else_free_demo']=array(array());
				}
                $nodes_data['products'][$key]['if_not_free']=$free ? array() : array(array());
                $nodes_data['products'][$key]['if_discount']=((float)$p['discount']) ? array(array()) : array();
                $nodes_data['products'][$key]['if_image']=array();
                
                $nodes_data['products'][$key]['product_name']=soft_wrap(output($p['product_name']), 30);
                $nodes_data['products'][$key]['product_descr']=soft_wrap(output($p['product_descr']), 40);
                $nodes_data['products'][$key]['group_name']=output(word_wrap($p['group_name'],30,2));
                
                $nodes_data['products'][$key]['if_is_recouring']=($p['is_recouring']==2) ? array(array()) : array();
                $nodes_data['products'][$key]['else_is_recouring']=($p['is_recouring']==2) ? array() : array(array());
                $nodes_data['products'][$key]['if_trial_period']=(!$free && isset($p['trial_period_value']) && $p['trial_period_value']) ? array(array()) : array();
                $nodes_data['products'][$key]['submit_disabled']=(intval($p['available']) ? "" : "disabled");
                
                $p['new_year5']=$p['new_unlimit'];
                $periods=array('day','month','month3','month6','year','year5');
                $prices=array();
                foreach($periods as $period)
                {
                    if(floatval($p[$period]))
                    {
                        $prices[]=array(
                        'period'=>$period,
                        'price'=>print_price($nodes_data['currency'], $p[$period], $p['new_'.$period], (float)$p['discount'])
                        );
                    }
                }
                $nodes_data['products'][$key]['prices']=$prices;             
                
                if($p['image'])
                {
                    $orig=file_exists($absolute_path.$posters_path_original.$p['image']) ? base_url().$posters_path_original.$p['image'] : false;
                    $prew=file_exists($absolute_path.$posters_path_previews.$p['image']) ? base_url().$posters_path_previews.$p['image'] : $orig;
                    
                    $nodes_data['products'][$key]['if_image']=array(array());
                    $nodes_data['products'][$key]['image']=$orig ? $orig : base_url()."img/no_image.jpg";
                    $nodes_data['products'][$key]['preview_image']=$orig ? $prew : base_url()."img/no_image.jpg";
                }
            }
        }
        
        $data['products']=print_page("user/market_sale_nodes.html",$nodes_data, TRUE);       
        
        /*$data['products']=_view("user/market_sale_nodes",
                                                        array(
                                                            "products"=>$data['products']['items'],
                                                            'currency'=>$data['currency'],
                                                            'cur_url'=>$CU
                                                            )
                                                                , TRUE);
                                                                */
        $data['groups']=$this->product_group->list_all();
        foreach($data['groups'] as $key=>$value)
        {
            $data['groups'][$key]['selected']=($value['id']==$group_id) ? 'selected' : '';
            $data['groups'][$key]['name']=output(word_wrap($value['name'], 50, 2));
        }
        print_page("user/market_sale_page.html", $data);
        //_view("user/market_sale_page", $data);
    }

    /**
    * Enter description here...
    *
    * @param integer $subscr_id
    */
    function cancel_subscr($subscr_id=0)
    {
        check_user_auth();
        $subscr_id=(int)$subscr_id;
        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->load->model('payment_model');
        //get product_id using $subscr_id
        $subscr_info = $this->payment_model->get_subscr_info($subscr_id);
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/market/cancel_subscr/$subscr_id", $subscr_info[0]['product_id']);
        //_log this to the "User_logs" table in DB

        $data=array();

        if( ($inf=$this->market->active_list($this->user_auth_model->uid, 0,0, $subscr_id) ) && $inf['count'] == 1 )
        {
            $data=$inf['items'][0];

            if($this->input->post("ch_e") && ($data['functionality_enabled_error']=Functionality_enabled('admin_member_info_modify', $this->user_auth_model->uid))===true)
            {
                if($this->input->post("ch_e")!=$this->input->post("ch"))
                {
                    $data['error']=2;
                }
                else
                {
                    if($this->market->end_subscr($subscr_id))
                    {
                        $this->market->create_transaction( $subscr_id,
                        0 /*pay_system_id is FREE_PAYMENT*/,
                        1 /*completed = yes*/,
                        0 /*summ*/,
                        Array("canceled_by_user"=>"true") /*info*/
                        );
                        $data['success']=1;

                        // inform user and admins with system email "user_subscription_expired" and "admin_subscription_ended"
                        $this->load->model("member_model");
                        $uid = $this->user_auth_model->uid;
                        $member_info = $this->member_model->get_member_info($uid);
                        // send email to user
                        send_system_email_to_user($uid,'user_subscription_expired',array('expired_product_name'=>array('object_id'=>$subscr_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
                        // notify all administrators by email
                        send_system_subscription_to_admins('admin_subscription_ended',array('user_login'=>$member_info['login'],'expired_product_name'=>array('object_id'=>$subscr_info[0]['product_id'],'object_type'=>4),'product_expiration_date'=>nsdate(time(),false)));
                        // _inform user and admins with system email "user_subscription_expired" and "admin_subscription_ended"
                    }
                    else
                    {
                        $data['error']=1;
                    }
                }

            }
        }
        else
        {
            $data['error']=1;
        }
        $data['message_box']=array();
        if (isset($data['success']) && $data['success']==1)
        {
            $data['message_box']['mes1']=array(
            'display'=>1,
            'text'=>"<{user_cancel_subscr_canceled}>"
            );
        }
        else
        {
            $data['message_box']['mes1']=array(
            'display'=>0,
            'text'=>"<{user_cancel_subscr_canceled}>"
            );
        }
		$data['error_box']=array();
        if (isset($data['error']) && $data['error']==1)
        {
            $data['error_box']['error1']=array(
            'display'=>1,
            'text'=>"<{user_cancel_subscr_product_not_exists}>"
            );
        }
        else
        {
            $data['error_box']['error1']=array(
            'display'=>0,
            'text'=>"<{user_cancel_subscr_product_not_exists}>"
            );
        }
        if (isset($data['error']) && $data['error']==2)
        {
            $data['error_box']['error2']=array(
            'display'=>1,
            'text'=>"<{user_cancel_subscr_phrase_incorrect}>"
            );
        }
        else
        {
            $data['error_box']['error2']=array(
            'display'=>0,
            'text'=>"<{user_cancel_subscr_phrase_incorrect}>"
            );
        }
        if (isset($data['functionality_enabled_error'])&&$data['functionality_enabled_error']!==true)
        {
            $data['error_box']['func']=array(
            'display'=>1,
            'text'=>$data['functionality_enabled_error']
            );
        }
        else
        {
            $data['error_box']['func']=array(
            'display'=>0,
            'text'=>""
            );
        }
        if((!isset($data['error']) || $data['error']!=1) && !isset($data['success']))
        {
            $data['if_form_show'] = array(array());
        }
        else
        {
            $data['if_form_show'] = array();
        }
        if (isset($error) || (isset($functionality_enabled_error)&&$functionality_enabled_error!==true))
        {
            $data['else_er_func'] = array();
        }
        else
        {
            $data['else_er_func'] = array(array());
        }
        if (isset($data['name']))
        {
            $data['name'] = soft_wrap(output($data['name']),80);
        }
        
        print_page("user/cancel_subscr.html", $data);
        
        //_view("user/cancel_subscr", $data);
    }

    /**
    * Enter description here...
    *
    */
    function after_buy()
    {
        check_user_auth();

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', '/market/after_buy');
		
		print_page("user/after_buy.html", array());
        //_view("user/after_buy", array());
    }


    /**
    * Displays the list of subscriptions of the user
    *
    * @author Drovorubov
    * @param integer
    * @param integer
    * @param string
    * @param string
    * 
    * @return mixed
    * 
    */
    function invoice($page=1, $per_page=0, $sort_by='', $sort_how='')
    {
        check_user_auth();
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_products_modify_paid')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********
        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/market/invoice/$page/$per_page/$sort_by/$sort_how");

        //Set per page parameter
        if(!$per_page)
        {
            $per_page = (int)input_post('per_page', 0);
        }
        if(!$per_page || $per_page <0)
        {
            $per_page = config_get("PAGER", "default_perpage");
        }
        //Set page parameter
        $page=(int)$page;
        if( !$page || $page<0 )
        {
            $page=1;
        }
        //Set order link params
        if( $sort_by != '' )
        {
            $sort_by = output($sort_by);
            $sort_how = output($sort_how);$sort_how = strtoupper($sort_how);
            $sort_how_converted = ($sort_how == 'DESC') ? 'ASC' : 'DESC';
            $sort_link = "/" . $sort_by . "/" . $sort_how;
        }
        else
        {
            $sort_by = 'cdate';
            $sort_how = 'DESC';
            $sort_how_converted = 'DESC';
            $sort_link = '';
        }
        $sort_param=array(
        'cdate'=>'by_date',
        'product_name'=>'by_product',
        'regular_price'=>'by_price',
        'type'=>'by_type'
        );
        $temp_sort_by=isset($sort_param[$sort_by]) ? $sort_param[$sort_by] : $sort_param['cdate'];

        //Get data from DB
        //$subscr = $this->market->subscriptions($page, $per_page, $sort_by, $sort_how, $this->user_auth_model->uid);
        $subscr = $this->market->subscriptions($page, $per_page, $temp_sort_by, $sort_how, $this->user_auth_model->uid);

        //Set pagers
        if($subscr['count'])
        {
            $data['pager1'] = html_pager(base_url()."market/invoice/", $page, $per_page, ceil($subscr['total'] / $per_page), $sort_by, $sort_how);
            $data['pager2'] = html_pager(base_url()."market/invoice/", $page, $per_page, ceil($subscr['total'] / $per_page), $sort_by, $sort_how);
        }

        //Set page order links
        $data['ord_by_product'] = base_url()."market/invoice/".$page."/".$per_page."/by_product/".$sort_how_converted;
        $data['ord_by_date'] = base_url()."market/invoice/".$page."/".$per_page."/by_date/".$sort_how_converted;
        $data['ord_by_type'] = base_url()."market/invoice/".$page."/".$per_page."/by_type/".$sort_how_converted;
        $data['ord_by_price'] = base_url()."market/invoice/".$page."/".$per_page."/by_price/".$sort_how_converted;

        //Prepare back URL
        $back_url = base_url()."market/invoice/".$page."/".$per_page.$sort_link;
        $data['back_url_encoded'] = encode_url($back_url);

        //Prepare suscriptions list to output
        foreach( $subscr['items'] as $key=>$val)
        {
            $val['product_name'] = word_wrap($val['product_name'],45,2);
            $subscr['items'][$key]['product_name'] = output($val['product_name']);
            if(isset($val['regular_price']))
            {
                $subscr['items'][$key]['regular_price'] = $this->_convert_price($val['regular_price']);
            }
        }
        //Add subscriptions list to data
        $data['items'] = $subscr['items'];
        $data['total'] = $subscr['total'];
        
        if( isset($data['total']) and $data['total']!=0 )
        {
            $data['if_total'] = array(array());
        }
        else
        {
            $data['if_total'] = array();
        }
        if( isset($items) && count($items) > 0 )
        {
            $data['if_items'] = array(array());
            $data['else_items'] = array();
        }
        else
        {
            $data['if_items'] = array();
            $data['else_items'] = array(array());
        }
        //table
        $settings['url'] = base_url()."market/invoice/";
        $settings['table_class'] = 'tab';
        $settings['table_width'] = '680px';
        $settings['order']=array($sort_by=>$sort_how);
        $settings['pager']=array('current_page'=>$page, 'per_page'=>$per_page, 'pages'=>ceil($subscr['total'] / $per_page));
        $settings['columns']=array(
        'product_name'=>array(
        'name'=>'<{user_paid_invoices_table_product}>',
        'sortable'=>true
        ),
        'cdate'=>array(
        'name'=>'<{user_paid_invoices_table_date}>',
        'sortable'=>true
        ),
        'transactions'=>array(
        'name'=>'<{user_paid_invoices_table_transactions}>'
        ),
        'type'=>array(
        'name'=>'<{user_paid_invoices_table_subscription_type}>',
        'sortable'=>true
        ),
        'regular_price'=>array(
        'name'=>'<{user_paid_invoices_table_price}>',
        'sortable'=>true
        ));
        
        $items=$data['items'];
        
        if( isset($items) && count($items) > 0 )
        {
            $data['if_items'] = array(array());
			$data['else_items'] = array();
        }
        else
        {
            $data['if_items'] = array();
			$data['else_items'] = array(array());
        }
		
        foreach($items as $key=>$value)
        {
            $items[$key]['product_name']=array(
            'text'=>output($value['product_name']),
            'class'=> intval($items[$key]['status']) == 1 ? 'color_red' : ''
            );
            $items[$key]['cdate']=nsdate($value['cdate'],false);
            
            $items[$key]['transactions']=array(
            'text'=>'<{user_paid_invoices_label_details}>',
            'link'=>base_url()."market/transaction/".$value['id']."/".$data['back_url_encoded']
            );
            $items[$key]['type']=($value['type'] == 2) ?"<{user_active_products_node_type_recc}>" :"<{user_active_products_node_type_one_time}>";
            $items[$key]['regular_price']=$value['regular_price'];
            
        }  

        $data['market_table']=print_table($items,$settings);
        
        print_page('user/subscription.html',$data);
        
        //_view("user/subscription", $data);
    }


    /**
    * Displays the list of transactions of the user
    *
    * @author Drovorubov
    * @param integer $sid (subscription id)
    * @param string $subscr_back_url (encoded)
    * @param integer $page
    * @param integer $count
    * @param string $sort_by
    * @param string $sort_how
    */
    function transaction($sid, $subscr_back_url, $page=1, $per_page=0, $sort_by='date', $sort_how='DESC')
    {
        check_user_auth();

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/market/transaction/$sid/$subscr_back_url/$page/$per_page");

        $data = array();

        if( intval($sid) < 1 )
        {
            $data['error'] = "<{user_paid_invoices_error_invoice_id_invalid}>";
        }
        //Set per page parameter
        if(!$per_page)
        {
            $per_page = (int)input_post('per_page', 0);
        }
        if(!$per_page || $per_page <0)
        {
            $per_page = config_get("PAGER", "default_perpage");
        }
        fb($per_page,"per_page");
        //Set page parameter
        $page=(int)$page;
        if( !$page || $page<0 )
        {
            $page=1;
        }
        //Set order link params
        if( $sort_by != '' )
        {
            $sort_by = output($sort_by);
            $sort_how = output($sort_how);
            $sort_how_converted = ($sort_how == 'DESC') ? 'ASC' : 'DESC';
            $sort_link = "/" . $sort_by . "/" . $sort_how;
        }
        else
        {
            $sort_by = '';
            $sort_how = '';
            $sort_how_converted = 'DESC';
            $sort_link = '';
        }
        
        $sort_param=array(
        'date'=>'by_trans_date',
        'pay_system'=>'by_trans_paysys',
        'summ'=>'by_trans_amount'
        );
        $temp_sort_by=isset($sort_param[$sort_by]) ? $sort_param[$sort_by] : $sort_param['date'];

        $transactions = $this->market->transactions($page, $per_page, $temp_sort_by, $sort_how, $sid);
        fb($transactions,"transactions1");
        //Set pagers
        /* if($transactions['count'])
        {
            //$data['pager'] = html_pager(base_url()."market/transaction/".$sid."/".$subscr_back_url."/", $page, $per_page, ceil($transactions['total'] / $per_page), $sort_by, $sort_how);
            //$data['pager2'] = html_pager(base_url()."market/transaction/".$sid."/".$subscr_back_url."/", $page, $per_page, ceil($transactions['total'] / $per_page), $sort_by, $sort_how);
        }

        foreach($transactions['items'] as $key=>$val)
        {
            //Prepare transaction summ and Payment System value
            $tmp = $this->_get_payment_attr($val['summ'],$val['pay_system_id']);
            $transactions['items'][$key]['summ'] = $tmp['sum'];
            $transactions['items'][$key]['pay_system'] = $tmp['pay_system'];
        }
        //Set page order links
        $data['ord_by_trans_date'] = base_url()."market/transaction/".$sid."/".$subscr_back_url."/".$page."/".$per_page."/by_trans_date/".$sort_how_converted;
        $data['ord_by_trans_paysys'] = base_url()."market/transaction/".$sid."/".$subscr_back_url."/".$page."/".$per_page."/by_trans_paysys/".$sort_how_converted;
        $data['ord_by_trans_amount'] = base_url()."market/transaction/".$sid."/".$subscr_back_url."/".$page."/".$per_page."/by_trans_amount/".$sort_how_converted; */
        //Prepare back URL to transactions page
        $trans_back_url = base_url()."market/transaction/".$sid."/".$subscr_back_url."/".$page."/".$per_page.$sort_link;
        $data['trans_back_url_encoded'] = encode_url($trans_back_url);
        //Prepare back URL to subscriptions page
        $data['subscr_id'] = $sid;
        $data['subscr_back_url_encoded'] = $subscr_back_url;
        //Add subscriptions list to data
        $data['items'] = $transactions['items'];
        //Load view
        fb($transactions,"transactions2");
        //_view("user/transaction", $data);
        //return;
                   
        $pages=isset($transactions['total']) ? ceil($transactions['total'] / $per_page) : 1;
        
        $settings=array();
        $settings['url']=base_url().'market/transaction/'.$sid.'/'.$subscr_back_url;
        $settings['table_width']='700px';
        //$settings['table_class']='first_class second_class';
        $settings['order']=array($sort_by=>$sort_how);
        $settings['pager']=array('current_page'=>$page, 'per_page'=>$per_page, 'pages'=>$pages);
        
        $settings['columns']=array(
        'info'=>array(
        'name'=>'<{user_paid_invoices_transactions_list_table_transaction}>'
        ),
        'date'=>array(
        'width'=>'127px',
        'name'=>'<{user_paid_invoices_transactions_list_table_date}>',
        'sortable'=>true
        //,'link'=>'param1/param2'
        ),
        'pay_system'=>array(
        'name'=>'<{user_paid_invoices_transactions_list_table_paysystem}>',
        'sortable'=>true
        ),
        'summ'=>array(
        'name'=>'<{user_paid_invoices_transactions_list_table_amount}>',
        'sortable'=>true
        ));
        
        $items=$data['items'];
        foreach($items as $key=>$value)
        {
            $tmp = $this->_get_payment_attr($value['summ'],$value['pay_system_id']);
            $items[$key]['summ'] = $tmp['sum'];
            $items[$key]['pay_system'] = $tmp['pay_system'];
            $items[$key]['info']=array(
            'text'=>'<{admin_member_control_account_panel_transactions_list_label_details}>',
            'link'=>base_url()."market/transaction_info/".$value['id']."/".$data['trans_back_url_encoded']
            );
            $items[$key]['date']=nsdate($value['date'],false);
        }    
        $data['table']=print_table($items,$settings);
        if(isset($data['error']) && !empty($data['error']))
        {
            $data['error_box']=array('error'=>$data['error']);
        }
        $data['submit_button']=submit_button(decode_url($data['subscr_back_url_encoded']),"<{user_btn_back}>");
        print_page("user/transaction.html", $data);
    }


    /**
    * Convert price sum to display
    *
    * @author Drovorubov
    * @param integer $p
    * @return string $p
    */
    function _convert_price($p)
    {
        if( floatval($p) > 0 )
        {
            $p = amount_to_print($p);
        }
        else
        {
            $p = "<{user_paid_invoices_const_free_price}>";
        }
        return $p;
    }


    /**
    * Getting user's transaction info
    *
    * @author Drovorubov
    * @param integer $tid (transaction id)
    * @param string $back_url (encoded)
    */
    function transaction_info( $tid, $back_url )
    {
        check_user_auth();

        //log this to the "User_logs" table in DB
        $this->load->model('user_log_model');
        $this->user_log_model->set($this->user_auth_model->uid, $_SERVER['REMOTE_ADDR'], isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'', "/market/transaction_info/$tid/$back_url");

        if( $tid < 1 )
        {
            $data['error'] = "<{user_paid_invoices_error_transaction_id_invalid}>";
        }

        $data = $this->market->transaction_info($tid);
        if( !$data )
        {
			$data['if_no_error'] = array();
            $data['error'] = "<{user_paid_invoices_error_transaction_not_exist}>";
        }
		else
		{
			$data['if_no_error'] = array(array());
			//Convert transaction info field to strings array
			$info_str_arr = array();
			unset($info);
			$info = unserialize($data['info']);
			if( is_array($info) && count($info) > 0 )
			{
				foreach($info as $info_key=>$info_val)
				{
					$info_val = output($info_val);
					$info_str_arr[] = $info_key . ' ' . $info_val;
				}
				$data['info'] = $info_str_arr;
			}
			else
			{
				$info_str_arr[] = output($info);
				$data['info'] = $info_str_arr;
			}
			$data['id'] = intval($data['id']);
			//Prepare transaction summ and Payment System value
			$tmp = $this->_get_payment_attr($data['summ'],$data['pay_system_id']);
		
			$data['amount'] = $tmp['sum'];
			$data['pay_system'] = $tmp['pay_system'];
			//Prepare back URL
			//$data['back_url_encoded'] = $back_url;
		}
			if( isset($data['error']) && $data['error'] != '' )
			{
				$data['error_box']['user_error']=array('display'=>1,'text'=>"<{admin_login_form_msg_er_cookies_disabled}>"); 
			}
			else
			{
				$data['error_box']['user_error']=array('display'=>0,'text'=>""); 
			}
			if( isset($data['info']) && count($data['info']) > 0 )
			{
				$data['if_info'] = array(array());
				//foreach ($data['info'] as $key=>$v)
				for ($i_i=0;$i_i<count($data['info']);$i_i++)
				{
					$data['info'][$i_i] = array('item'=>$data['info'][$i_i]);
				}
			}
			else
			{
				$data['if_info'] = array();
			}
			if (isset($data['date']))
			{
				$data['if_date'] = array(array());
				$data['date'] = nsdate($data['date'],false);
			}
			else
			{
				$data['if_date'] = array();
			}
			$data['back_url_encoded'] = $back_url;
			$data['button'] = submit_button(decode_url($data['back_url_encoded']),"<{user_btn_back}>");
		
        //Load view		
		print_page("user/transaction_info.html", $data);
		//_view("user/transaction_info", $data);
    }


    /**
    * Convert payment attributes to display
    *
    * @author Drovorubov
    * @param float $sum
    * @param integer $paysys_id
    * @return array
    */
    function _get_payment_attr($sum,$paysys_id)
    {
        //Set initial array to return
        $rv = array('sum'=>'','pay_system'=>'');
        //Set sum and payment system values
        if( floatval($sum) > 0 )
        {
            $rv['sum'] = amount_to_print($sum);
            //Get payment systems info by id
            $paysys_info = config_get('PAYMENT',$paysys_id);
            if(isset($paysys_info['name']))
            {
                $rv['pay_system'] = $paysys_info['name'];
            }
        }
        else
        {
            //Set sum as free
            $rv['sum'] =  "<{user_paid_invoices_const_free_price}>";
            //Set Payment System as free product
            $rv['pay_system'] = "<{user_paid_invoices_const_free_product}>";
        }
        return $rv;
    }

}

?>
