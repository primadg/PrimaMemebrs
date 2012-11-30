<?php
/**
 * 
 * THIS FILE CONTAINS Member CLASS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file admin_controller.php
 */
require_once("admin_controller.php");
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK THIS MEMBER
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Member extends Admin_Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
	function Member()
	{
        
        $this->access_bit=MEMBER_CONTROL;
        parent::Admin_Controller();
		$this->load->model("member_model");
        $this->load->model("user_model");
        $this->load->model("user_auth_model");
        $this->load->model("mail_model");
	}
    /**
     * Check expiration term
     *
     */
    function Test()
    {
    $this->member_model->check_expiration_term();
    }

    /**
    * Shows expired members list
    *
    * @author Drovorubov
    */
    function expired()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Getting data from DB
        $data = array();
        $data=$this->member_model->expired_list($current_page, $per_page, $sort_by, $sort_how);

        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                //Prepare data to display
                $item['login'] = word_wrap($item['login'],20,2);
                $item['login'] = output($item['login']);
                $item['name'] = output($item['name']);
                $item['last_name'] = output($item['last_name']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/members/expired/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/members/expired/empty", array(), true);
        }
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_expired_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_expired_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_expired_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_expired_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        
        $data['sort_by']=$sort_by;
        $data['order_by']=$sort_how;
		$res=$this->load->view("/admin/members/expired/page", $data, true);
        make_response("output", $res, 1);
    }

    /**
    * Loads data to Flash object and display it
    *
    * @author Drovorubov
    * @param integer $month
    * @param integer $year
    */
    function load_statistics($month,$year)
    {
        $month = intval($month);
        $year = intval($year);
        $error = '';
        if( $year < 1 || $month < 0 )
        {
            $error = replace_lang('<{admin_member_control_statistics_error_param_invalid}>');
        }
        //Set chart labels
        $bar_newmbr_title = replace_lang('<{admin_member_control_statistics_bar_title_new_members}>');
        $bar_allmbr_title = replace_lang('<{admin_member_control_statistics_bar_title_all_members}>');
        //Set months names array
        $long_month_names = get_month_names('long');
        $short_month_names = get_month_names('short');
        //Load Graph library (Documentation on teethgrinder.co.uk/open-flash-chart)
        $this->load->library('graph');
        //Create the graph object:
        $g = new graph();
        //Initialize bars arrays and attributes for bars objects
        $bar_new_mbr = new bar_3d( 75, '#D54C78' );
        $bar_new_mbr->key( $bar_newmbr_title, 10 );
        $bar_all_mbr = new bar_3d( 75, '#3334AD' );
        $bar_all_mbr->key( $bar_allmbr_title, 10 );
        $tmp = array();
        $is_valid_date = true;
        $current = 0; //Current day or month value
        if( $month > 0 )
        {
            $period = 'day';
            //Get curent year and month
            $curr_year = date("Y");
            $curr_month = date("n");
            //Compare Date params with current date
            if( ($year == $curr_year && $month > $curr_month) || ($year > $curr_year) )
            {
                $is_valid_date = false;
            }
            //Set current day
            if($month == date("n") )
            {
                $current = date("j");
            }
            $max_days = date("j",mktime(0,0,0,$month+1,0,$year));
            //Initialize data array
            for($i=0; $i<$max_days; $i++)
            {
                $bar_new_mbr->data[] = 0;
                $bar_all_mbr->data[] = 0;
                $tmp[] = $i+1;
            }
            $g->set_x_labels($tmp);
        }
        else
        {
            $period = 'month';
            $current = date("n");
            //Get curent year
            $curr_year = date("Y");
            //Compare Date param with current date
            if( $year > $curr_year )
            {
                $is_valid_date = false;
            }
            //Set labels for axis
            $g->set_x_labels( $short_month_names );
            //Initialize data array
            for($i=0; $i<12; $i++)
            {
                $bar_new_mbr->data[] = 0;
                $bar_all_mbr->data[] = 0;
            }
        }
        $tmp = array();
        $max = 0;

        if( $is_valid_date == true )
        {
            //Get data for New Members from Database
            $tmp = $this->member_model->get_new_members($month,$year);
            //Set DB values of new members to graph object
            foreach( $tmp as $item )
            {
                if( $item['num'] > $max )
                {
                    $max = $item['num'];
                }
                $bar_new_mbr->data[$item[$period]-1] = $item['num'];
            }
            //Get data for All Members count from Database
            $members_count = $this->member_model->get_members_count($month,$year);
            if( $members_count > 0 )
            {
                $max = $members_count;
                $period_num = count($bar_all_mbr->data);
                //Set start position according current value
                $start_pos = ($current > 0)? $current-1 : $period_num-1;
                //Set DB values to graph object for all numbers of members
                for( $i=$start_pos; $i>=0; $i-- )
                {
                    $bar_all_mbr->data[$i] = $members_count;
                    if( $bar_new_mbr->data[$i] > 0 )
                    {
                        $members_count = $members_count - $bar_new_mbr->data[$i];
                    }
                }
            }
        }

        //Set Chart title
        $chart_title = '';
        if( $error == '' )
        {
            if( $month > 0 ){$chart_title = $long_month_names[$month-1].', ';}
            $chart_title .= $year;
        }
        else
        {
            $chart_title = $error;
        }
        $g->title($chart_title,'{font-size: 20px; color: #800000}');

        //Set data to graph object
        $g->data_sets[] = $bar_all_mbr;
        $g->data_sets[] = $bar_new_mbr;
        //Set axis
        $g->set_x_axis_3d( 12 );
        $g->x_axis_colour( '#909090', '#ADB5C7' );
        $g->y_axis_colour( '#909090', '#ADB5C7' );

        //Set axis attributes
        $max = $max - intval($max%10) + 10;
        $g->set_y_max( $max );
        $g->y_label_steps( intval($max/10) );
        $y_axis_title = replace_lang('<{admin_member_control_statistics_y_axis_title}>');
        $g->set_y_legend( $y_axis_title, 12, '#736AFF' );
        //Render the object
        echo $g->render();
    }



    /**
    * Loads the page with Flash object
    *
    * @author Drovorubov
    */
    function statistics()
    {
        //Set years array beginning from current year
        $year = date("Y");
        $data = array();
        for($i=10; $i>0; $i--)
        {
            $data['years'][] = $year;
            $year--;
        }
        //Get the whole page
		$res=$this->load->view("/admin/members/statistics", $data, true);
        make_response("output", $res, 1);
        return;
    }



    /**
    * Displayes the list of member suspend reasons
    *
    * @author Drovorubov
    * 
    * @return true
    */
    function suspend_reasons_list()
    {
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        $data=array();
		$data=$this->member_model->suspend_reasons_list('','',$sort_by,$sort_how);
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if( count($data['items']) > 0 )
        {
            foreach( $data['items'] as $item )
            {
                //Prepare node
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $item['name'] = word_wrap($item['name'],40,2);
                $item['name'] = output($item['name']);
                $item['descr'] = word_wrap($item['descr'],40,2);
                $item['descr'] = output($item['descr']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/members/suspreasons/list/node", $node, true);
            }
        }
        else
        {
            $data['rows'] .= $this->load->view("/admin/members/suspreasons/list/empty", array(), true);
        }
        $data['sort_by']=in_array($sort_by,array('by_name','by_descr')) ? $sort_by : "";
        $data['sort_how']=$sort_how;
        //Get the whole page
		$res=$this->load->view("/admin/members/suspreasons/list/page", $data, true);
        make_response("output", $res, 1);

        return true;
    }



    /**
    * Add suspend reason
    *
    * @author Drovorubov
    */
    function suspend_reason_add()
    {
        $error = '';
        $data=array();
        $action = prepare_text($this->input->post('action'));
        if($action == 'add')
        {
            //Getting post params
            $data['name'] = prepare_text($this->input->post('name'));
            $data['descr'] = prepare_text($this->input->post('descr'));

            // Fields validation
            $error = $this->_check_suspreason_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('suspend_reason_add',false,true,"validation_error");
                return;
            }

            //Add data to DB
            $new_id = $this->member_model->suspend_reason_add($data);
            if( $new_id > 0 )
            {
                $res = "<{admin_member_control_approve_suspend_reason_add_success}>";
                make_response("output", $res, 1);
            }
            else
            {
                $err = "<{admin_member_control_error_action_suspend_reason_add}>";
                make_response("error", $err, 1);
            }
            simple_admin_log('suspend_reason_add',$new_id,($new_id<=0),"not_saved");
            return;
                
        }
        $data['action'] = 'add';
        $data['to_save'] = 'add_suspend_reason()';
        //Show form
		$res=$this->load->view("/admin/members/suspreasons/addedit_reason", $data, true);
		make_response("output", $res, 1);
    }




    /**
    * Update suspend reason
    *
    * @author Drovorubov
    */
    function suspend_reason_edit()
    {
        $id = intval($this->input->post('id'));
        if( $id < 1 )
        {
            $res = '<{admin_member_control_error_action_suspend_reason_edit}>';
            make_response("error", $res, 1);
            simple_admin_log('suspend_reason_modify',$id,true,"invalid_id");
            return;
        }
        $error = '';
        $data=array();
        $action = prepare_text($this->input->post('action'));
        if($action == 'edit')
        {
            //Getting post params
            $data['name'] = prepare_text($this->input->post('name'));
            $data['descr'] = prepare_text($this->input->post('descr'));

            // Fields validation
            $error = $this->_check_suspreason_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('suspend_reason_modify',$id,true,"validation_error");
                return;
            }

            //Update data in DB
            $update = $this->member_model->suspend_reason_edit($id,$data);
            if( $update )
            {
                $res = "<{admin_member_control_approve_suspend_reason_edit_success}>";
                make_response("output", $res, 1);
                return;
            }
            else
            {
                $err = "";
                make_response("output", $err, 1);
                return;
            }
            simple_admin_log('suspend_reason_modify',$id,(!$update),"not_updated");
            
        }
        $data = array();
        $data = $this->member_model->suspend_reason_info($id);
        if(!$data)
        {
            $res = "<{admin_member_control_approve_suspend_reason_error_not_exist}>";
            make_response("error", $res, 1);
            return;
        }
        //Prepare data to display
        $data['items']['name'] = output($data['name']);
        $data['items']['descr'] = output($data['descr']);
        $data['action'] = 'edit';
        $data['to_save'] = "edit_suspend_reason('".$id."')";
        //Show form
		$res=$this->load->view("/admin/members/suspreasons/addedit_reason", $data, true);
		make_response("output", $res, 1);
    }




    /**
    * Deletes suspend reason
    *
    * @author Drovorubov
    */
	function suspend_reason_delete()
	{
        $id = $this->input->post('id');
        if( $id < 1 )
        {
            $res = '<{admin_member_control_error_suspend_reason_id_empty}>';
            make_response("error", $res, 1);
            simple_admin_log('suspend_reason_delete',$id,true,"invalid_id");
            return;
        }
        
        //***********Functionality limitations***********
        $functionality_enabled_error=Functionality_enabled('admin_member_suspend_reason_modify', $id);
        if($functionality_enabled_error!==true)
        {   
            make_response("error",$functionality_enabled_error, true);
            return;
        }
        //*******End of functionality limitations********
        
        $del_res = $this->member_model->suspend_reason_delete($id);
         if( $del_res )
        {
            $res = '<{admin_member_control_approve_suspend_reason_delete_success}>';
            make_response("output", $res, 1);
        }
        else
        {
            $res = '<{admin_member_control_error_action_suspend_reason_delete}>';
            make_response("error", $res, 1);
        }
        simple_admin_log('suspend_reason_delete',$id,(!$del_res),"not_deleted");
        return;
    }


    /**
    * Load User's transactions list
    *
    * @author Drovorubov
    */
    function edit_member_transactions()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Prepare params
        $sid = intval($this->input->post('sid'));
        //Getting data from DB
        $data = array();
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        $data=$this->member_model->transaction_list($current_page, $per_page, $sort_by, $sort_how, $sid);
        $info = array();
        foreach($data['items'] as $key=>$val)
        {
            //Prepare transaction summ and Payment System value
            $tmp = $this->_get_payment_attr($val['summ'],$val['pay_system_id']);
            $data['items'][$key]['summ'] = $tmp['sum'];
            $data['items'][$key]['pay_system'] = $tmp['pay_system'];
            
            //Set subscription id
            if( isset($data['items'][$key]['subscr_id']) )
            {
                $data['subscr_id'] = $val['subscr_id'];
            }
        }

        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;

        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_transactions',array('sid'=>$sid),$data['per_page']) . page_selectbox($data['pages'] ,'load_transactions',array('sid'=>$sid,'ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_transactions',array('sid'=>$sid),$data['per_page']) . page_selectbox($data['pages'] ,'load_transactions',array('sid'=>$sid,'ppage'=>$data['per_page']), $data['current_page']);
        $data['subscr_id'] = $sid;

        $res=$this->load->view("/admin/members/edit/transactions_list", $data, true);
        make_response("output", $res, 1);
    }



    /**
    * Getting member's transaction info
    *
    * @author Drovorubov
    */
    function edit_member_transaction_info()
    {
        $tid = intval($this->input->post('tid'));
        if( $tid < 1 )
        {
            $err = "<{admin_member_control_account_panel_transaction_info_error_id_invalid}>";
            make_response("error", $err, 1);
            return;
        }

        $data = $this->member_model->get_transaction_info($tid);
        if( !$data )
        {
            $err = "<{admin_member_control_account_panel_transaction_info_error_not_exist}>";
            make_response("error", $err, 1);
            return;
        }
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
        //Set data to view
        $res=$this->load->view("/admin/members/edit/transaction_info", $data, true);
        make_response("output", $res, 1);
        return;
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
            $rv['sum'] =  "<{admin_member_control_member_list_const_free_price}>";
            //Set Payment System as free product
            $rv['pay_system'] = "<{admin_member_control_account_panel_payments_const_free_product}>";
        }
        return $rv;
    }




    /**
    * Adds new transaction and subscription
    *
    * @author Drovorubov
    */
    function edit_member_payment_add()
    {
        $error = '';
		$data=array();
        $action = prepare_text($this->input->post('action'));
        if($action == 'add')
        {
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', intval($this->input->post('uid')));
            if($functionality_enabled_error!==true)
            {   
                make_response("error",$functionality_enabled_error, true);
                return;
            }
            
            //Getting post params
            $data['uid'] = intval($this->input->post('uid'));
			$data['product_type'] = $this->input->post('product_type');
            $data['product'] = intval($this->input->post('product'));
            list($data['period'],$data['price']) = explode("-",prepare_text($this->input->post('period')));
            $data['price'] = floatval($data['price']);
            $data['payment_system'] = intval($this->input->post('payment_system'));
            //***********Functionality limitations***********
            if(Functionality_enabled('admin_products_modify_paid')!==true)
            {   
                $data['payment_system']=0;
            }
            //*******End of functionality limitations********        
            $data['transaction'] = prepare_text($this->input->post('transaction'));
            // Fields validation
            $error = $this->_check_payment_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('member_payment_add',false,true,"validation_error");
                return;
            }
			
			//check registration data
			$this->load->model('user_auth_model');
			$this->load->model('cart_model');
            $this->load->model('payment_model');
            $this->load->model('user_model');
			$product_type=explode("-",$data['product_type']);
			$product_type=isset($product_type[1]) ? $product_type[1] : 0;
			fb($product_type,'$DATA["PRODUCT_TYPE"]');
			if($product_type==PRODUCT_HOSTED)
			{
				$page='profile_domain';
				$account_id=0;
				$user_id=$data['uid'];
				$additional_profile = $this->user_model->profile_additional_get($page,$user_id,$account_id);
				$is_additional_profile=false;
                if($additional_profile)
                {
                    $additional_profile['error_box']=array();
                    $additional_profile=$this->user_model->user_fields_check($additional_profile,$page);
                    if(!is_msg_displayed($additional_profile['error_box']))
                    {
                        $additional_profile['id']=$user_id;
                        $additional_profile['account_id']=$this->payment_model->set_user_info(array(1),array());
                        $data['user_info_id']=$additional_profile['account_id'];
                        $additional_profile['restore_from_session']=true;
                        $this->user_model->profile_additional_set($page,$additional_profile);
                        $is_additional_profile=true;	
                    }
                }
				if(!$is_additional_profile)
				{
					$err = "<{admin_member_control_account_panel_payments_add_error_additional_profile_invalid}>";
					make_response("error", $err, 1);
					simple_admin_log('member_payment_add',false,true,"additional_profile_invalid");
					return;
				}
			}
            //Check if the product is available to be subscribed
            if( !$this->member_model->is_product_available($data['product']) )
            {
                $err = "<{admin_member_control_account_panel_payments_add_error_product_notavailable}>";
                make_response("error", $err, 1);
                simple_admin_log('member_payment_add',false,true,"product_not_avaible");
                return;
            }
            //Check if the product is bought
            $this->load->model('payment_model');
            if( $this->payment_model->is_subscr_available($data['product'],$data['uid']) )
            {
                $err = "<{admin_member_control_account_panel_payments_add_error_already_subscribed}>";
                make_response("error", $err, 1);
                simple_admin_log('member_payment_add',false,true,"already_subscribed");
                return;
            }
            //Add data to DB
            $result = $this->member_model->susbcribe_add($data);
            if( !$result )
            {
                $err = "<{admin_member_control_account_panel_payments_add_error_invoice_notadded}>";
                make_response("error", $err, 1);
                simple_admin_log('member_payment_add',false,true,"not_added");
                return;
            }

            $res = $data['product'];
            make_response("output", $res, 1);
            simple_admin_log('member_payment_add');
            return;

        }
    }


    /**
    * Load User payments and subscription page
    *
    * @author Drovorubov
    */
    function edit_member_payment()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Prepare params
        $uid = intval($this->input->post('user_id'));
        //Getting data from DB
        $data = array();
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        $data=$this->member_model->susbcribe_list($current_page, $per_page, $sort_by, $sort_how, $uid);
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_products_modify_paid')!==true)
        {   
            $data['only_free_product']=true;
        }
        //*******End of functionality limitations********
        foreach($data['items'] as $key=>$val)
        {
            $val['product_name'] = word_wrap($val['product_name'],25,0,' ');
            $data['items'][$key]['product_name'] = output($val['product_name']);
            $data['items'][$key]['type'] = ($val['type'] == 2)? '<{admin_member_control_account_panel_payments_type_reccuring}>' : '<{admin_member_control_account_panel_payments_type_one_time}>';
            if(isset($val['regular_price']))
            {
                $data['items'][$key]['regular_price'] = $this->_convert_price($val['regular_price']);
            }
        }

        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;

        //Get products list
        $this->load->model('market_model');
        $subscribed_products = $this->market_model->subscribed_products($uid);
        $data['products'] = $this->member_model->get_unblocked_products_list($uid,$subscribed_products);
        foreach( $data['products'] as $key=>$val)
        {
            $val['name'] = word_wrap($val['name'],45,2);
            $data['products'][$key]['name'] = output($val['name']);
            //Set product discount
            $this->load->model('payment_model');
            if( $val['day'] > 0 )
            {
                $data['products'][$key]['day'] = $this->payment_model->use_discount($val['day'], $val['discount_type'], $val['discount']);
            }
            if( $val['month'] > 0 )
            {
                $data['products'][$key]['month'] = $this->payment_model->use_discount($val['month'], $val['discount_type'], $val['discount']);
            }
            if( $val['month3'] > 0 )
            {
                $data['products'][$key]['month3'] = $this->payment_model->use_discount($val['month3'], $val['discount_type'], $val['discount']);
            }
            if( $val['month6'] > 0 )
            {
                $data['products'][$key]['month6'] = $this->payment_model->use_discount($val['month6'], $val['discount_type'], $val['discount']);
            }
            if( $val['year'] > 0 )
            {
                $data['products'][$key]['year'] = $this->payment_model->use_discount($val['year'], $val['discount_type'], $val['discount']);
            }
            if( $val['unlimit'] > 0 )
            {
                $data['products'][$key]['unlimit'] = $this->payment_model->use_discount($val['unlimit'], $val['discount_type'], $val['discount']);
            }
        }
        //Get if member's account is expired
        //$this->load->model('user_model');
        $data['accnt_is_expired'] = $this->user_model->is_expired($uid);
        //Get payment systems list
        $data['payment_systems'] = config_get('PAYMENT');
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_payments',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_payments',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_payments',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_payments',array('ppage'=>$data['per_page']), $data['current_page']);
        $res=$this->load->view("/admin/members/edit/payment", $data, true);
        make_response("output", $res, 1);
    }


    /**
    * Send email to user
    *
    * @author Drovorubov
    *
    */
    /* function edit_member_email_client()
    {
        //Get member id
        $mid = intval($this->input->post('member_id'));
        if( $mid < 1 )
        {
            $res = 'Error: Member is invalid';
            make_response("error", $res, 1);
            return;
        }
        //Get email template
        $template_id = intval($this->input->post('template_id'));
        if( $template_id < 1 )
        {
            $res = 'Error: Template has a wrong value';
            make_response("error", $res, 1);
            return;
        }
        //Get email_from
        $email_from = config_get('SYSTEM','MAILER','admin_email');
        //Get member's email
        $minfo = $this->member_model->get_member_info($mid);
        $email_to = $minfo['email'];
        $action = prepare_text($this->input->post('action'));
        if($action == 'send')
        {
            $data = array();
            $data['to'] =  $email_to;
            $data['from'] = prepare_text($this->input->post('from'));
            $data['subject'] = prepare_text($this->input->post('subject'));
            $data['body'] = prepare_text($this->input->post('msg'));
            // Fields validation
            $error = $this->_check_email_client_fields($data);
            if(!empty($error))
            {
                $res = $error;
                make_response("error", $res, 1);
                simple_admin_log('member_email_client',false,true,"validation_error");
                return;
            }
            //Get email keys array with values according user id
            $user_email_keys = $this->mail_model->get_user_keys($mid);
            //Replace e-mail body keys to the keys values
            $data['body'] = $this->mail_model->replace_keys($data['body'],$user_email_keys);

            //Prepare array from with current admin login
            
            //Send email
            if( $this->mail_model->mail_model->send_letter($data['to'],$data['from'],$data['subject'],$data['body']) )
            {
                $res = '<{admin_member_control_account_panel_email_client_success_send_email}>';
                make_response("output", $res, 1);
                simple_admin_log('member_email_client');
                return;
            }
            else
            {
                $res = "<{admin_member_control_account_panel_email_client_error_send_email}>";
                make_response("error", $res, 1);
                simple_admin_log('member_email_client',false,true,"not_sent");
                return;
            }
        }
        //Get email template
        $template_info = $this->mail_model->get_template($template_id);
        if($action == 'tpl_change')
        {
            $subj = $template_info['subject'];
            $msg = $template_info['message'];
            $res = $subj."!:sep:!".$msg;
            make_response("output", $res, 1);
            return;
        }
        //Load full form
        $data = array();
        $data['sel_tpl_id'] = $template_id;
        $email_to = word_wrap($email_to,60,0,' ');
        $email_to = output($email_to);
        $data['email_to'] =  $email_to;
        $data['email_from'] =  $email_from;
        $data['subject'] = $template_info['subject'];
        $data['msg'] = $template_info['message'];
        //Get email template list
        $template_list = $this->mail_model->get_template_names();
        $data['template_list'] = $template_list['items'];
        foreach( $data['template_list'] as $key=>$value )
        {
            $value['name'] = word_wrap($value['name'],60,2);
            $value['name'] = output($value['name']);
            $data['template_list'][$key] = $value;
        }
        //Prepare email keys string
        $data['email_keys_str'] = get_email_keys_str('user');
        // Load email client form
		$res=$this->load->view("/admin/members/edit/email_client", $data, true);
		make_response("output", $res, 1);
        return;
    } */

    /**
    * Shows member's email history list
    *
    * @author Drovorubov
    */
    /* function edit_member_email_history()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Prepare params
        $user_id = intval($this->input->post('user_id'));
        //Getting data from DB
        $data = array();
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        $data=$this->member_model->history_list($current_page, $per_page, $sort_by, $sort_how, $user_id);
        foreach($data['items'] as $key=>$val)
        {
            $data['items'][$key]['cdate']=$val['date'];
            
            $datetime = explode(' ',$val['date']);
            $data['items'][$key]['date'] = $datetime[0];
            $data['items'][$key]['time'] = $datetime[1];
            $val['subject'] = word_wrap($val['subject'],25,0,' ');
            $data['items'][$key]['subject'] = output($val['subject']);
            $val['email'] = word_wrap($val['email'],35,2);
            $data['items'][$key]['email'] = output($val['email']);
        }
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;

        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_email_history_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_email_history_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_email_history_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_email_history_list',array('ppage'=>$data['per_page']), $data['current_page']);

		$res=$this->load->view("/admin/members/edit/email_history", $data, true);
        make_response("output", $res, 1);
        return;
    } */

  /**
    * Delete email from history
    *
    * @author Drovorubov
    */
	/* function edit_member_rem_email_history()
	{
        $email_id = intval($this->input->post('email_id'));
        if($email_id < 1)
        {
            $res = 'Error: Email id is wrong';
            make_response("error", $res, 1);
            simple_admin_log('member_email_history_delete',$email_id,true,"invalid_id");
            return;
        }
        $del = $this->member_model->rem_history($email_id);
        if( $del )
        {
            $res = '';
            make_response("output", $res, 1);
        }
        else
        {
            $res = 'Error: Email is not deleted';
            make_response("error", $res, 1);
        }
        simple_admin_log('member_email_history_delete',$email_id,(!$del),"not_deleted");
        return;
    } */

    /**
    * Getting member's email info
    *
    * @author Drovorubov
    */
    /* function edit_member_detail_history_view()
    {
        $id = intval($this->input->post('email_id'));
        if( $id < 1 )
        {
            $res = 'Error: Email id is wrong';
            make_response("error", $res, 1);
            return;
        }

        $data = array();
        $data = $this->member_model->history_get($id);
        if(!$data['result'])
        {
            $res = 'Error: Email details are not found';
            make_response("error", $res, 1);
            return;
        }
        $data = $data['items'];
        $data['email_to'] = word_wrap($data['email_to'],60,0," ");
        $data['email_to'] = output($data['email_to']);
        $data['email_from'] = word_wrap($data['email_from'],60,0," ");
        $data['email_from'] = output($data['email_from']);
        $data['subject'] = word_wrap($data['subject'],60,0," ");
        $data['subject'] = output($data['subject']);
        $data['message'] = word_wrap($data['message'],60,0," ");
        $data['message'] = output($data['message']);
        $res=$this->load->view("/admin/members/edit/email_view", $data, true);
        make_response("output", $res, 1);

        return;
    } */


    /**
    * Shows member's access log list
    *
    * @author Drovorubov
    */
    function edit_member_access_logs()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Prepare params
        $user_id = intval($this->input->post('user_id'));
        //Set search params for date from and to
        $search = array();
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        if( !empty($date_from) && !empty($date_to) )
        {
            if( validate_date($date_from) && validate_date($date_to) )
                    {
                        $search['date_from'] = $date_from;
                        $search['date_to'] = $date_to;
                    }
        }
        //Getting data from DB
        $data = array();
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        $data=$this->member_model->member_access_log_list($current_page, $per_page, $sort_by, $sort_how, $user_id, $search);
        foreach($data['items'] as $key=>$val)
        {
            $data['items'][$key]['cdate']=$val['time'];            
            $datetime = explode(' ',$val['time']);
            $data['items'][$key]['date'] = $datetime[0];
            $data['items'][$key]['time'] = $datetime[1];
        }
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_access_log_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_access_log_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_access_log_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_access_log_list',array('ppage'=>$data['per_page']), $data['current_page']);
        
        $res=$this->load->view("/admin/members/edit/access_log", $data, true);
        make_response("output", $res, 1);
    }

    
    function _profile_additional($page)
    {
        $this->load->model('user_model');
        if(!in_array($page,$this->user_model->profile_types) || Functionality_enabled('member_registered_menu_'.$page)!==true)
        {
            $this->admin_auth_model->showAccessDenied("<{admin_msg_er_access_denied}>");
            exit;
        }
        $post=prepare_post();
  fb($post, __FUNCTION__ ." post");
        //return;
        $uid=$id=isset($post['id']) ? intval($post['id']) : 0; 
        $account_id=isset($post['account_id'])?$post['account_id']:0;
        if($id < 2 )
        {
            $res = 'Error: ID is wrong';
            make_response("error", create_temp_vars_set(array($res)), 1);
            simple_admin_log('member_info_modify',$id,true,"invalid_id");
            return;
        }
        $error_text="";
        $this->load->model('config_model');
        //***********Functionality limitations***********
        $user_info = $this->user_model->get_profile_by_uid($uid);
        $data = $user_info[0];
        $data['page']=$page;
        $data['account_id']=$account_id;
        $data['forceuse']=1;
        $data['customerlangpref']='en';
        $data['fields'] = $this->config_model->member_page_get($page);
        $account_info = $this->user_model->profile_additional_get($page,$uid,$account_id);
        if($account_info)
        {
            $account_info['account_id']=$account_info['id'];
            unset($account_info['id']);
            $data=array_merge($data,$account_info);
        }
        $data['message_box']=array();
        $data['error_box']=array();
        
        if(isset($post['action']) && $post['action']=='save')
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $id);
            if($functionality_enabled_error!==true)
            {   
                make_response("error",create_temp_vars_set(array($functionality_enabled_error)), true);
                return;
            }
            //*******End of functionality limitations********
            $post['forceuse']=isset($post['forceuse']) && $post['forceuse']=='true' ? 1 : 0;
            $data=array_merge($data,$post);
            //if autofill
            if(isset($post['autofill']))
            {
                $data = $this->user_model->profile_additional_autofill($page,$data);
            }
            fb($data,"DATA1");
            $data=$this->user_model->user_fields_check($data,$page,true);
            //if not autofill
            if(!isset($post['autofill']))
            {
                if(!is_msg_displayed($data['error_box']))
                {
                    if($this->user_model->profile_additional_set($page,$data,$account_id))
                    {
                        $data['message_box']['update_successful']="<{user_".$page."_update_successful}>";
                        make_response("output", create_temp_vars_set($data['message_box']), 1);
                        simple_admin_log('member_info_modify',$id);
                        return;
                    }
                    else
                    {
                        $data['error_box']['not_saved']=array('display'=>1,'text'=>"<{user_".$page."_update_not_saved}>");
                    }
                }            
                if(is_msg_displayed($data['error_box']))
                {
                    $arr=array();
                    foreach($data['error_box'] as $k=>$v)
                    {
                        if(is_array($v) && isset($v['display']) && intval($v['display']))
                        {
                            $arr[$k]=$v['text'];
                        }
                    }
                    make_response("error", create_temp_vars_set($arr), 1);
                    simple_admin_log('member_add',false, true, "validation_error");
                    return;
                }
            }
        }
        $data['states']=get_states();
        foreach($data['fields'] as $k=>$v)
        {
            $data['fields'][$k]['value']=isset($data[$k]) ? $data[$k] : "";
        }
        if(isset($data['fields']['forceuse']) && intval($data['fields']['forceuse']['enabled']))
        {
            $data['fields']['forceuse']['input_type']='checkbox';
        }
        if(isset($data['fields']['customerlangpref']) && intval($data['fields']['customerlangpref']['enabled']))
        {
            $data['fields']['customerlangpref']['input_type']='select';
            $data['fields']['customerlangpref']['items']=$data['fields']['customerlangpref']['languages'];
        }
        if(isset($data['fields']['country']) && intval($data['fields']['country']['enabled']))
        {
            $data['fields']['country']['input_type']='select';
            $data['fields']['country']['items']=get_countries();
        }
        if(isset($data['fields']['state']) && intval($data['fields']['state']['enabled']))
        {
            $data['fields']['state']['input_type']='select';
            $data['fields']['state']['items']=get_states();
        }
		if(isset($data['fields']['telno']) && intval($data['fields']['telno']['enabled']))
        {
            $data['fields']['telno']['input_type']='phone';
            $data['fields']['telno']['width']=250;
			$data['fields']['telno']['code_value']=isset($data['telnocc']) ? $data['telnocc'] : "";
        }
		if(isset($data['fields']['alttelno']) && intval($data['fields']['alttelno']['enabled']))
        {
            $data['fields']['alttelno']['input_type']='phone';
            $data['fields']['alttelno']['width']=250;
			$data['fields']['alttelno']['code_value']=isset($data['alttelnocc']) ? $data['alttelnocc'] : "";
        }
		if(isset($data['fields']['faxno']) && intval($data['fields']['faxno']['enabled']))
        {
            $data['fields']['faxno']['input_type']='phone';
            $data['fields']['faxno']['width']=250;
			$data['fields']['faxno']['code_value']=isset($data['faxnocc']) ? $data['faxnocc'] : "";
        }
        $data['is_autofill']=($this->user_model->profile_additional_autofill($page)) ? true : false;
        fb($data,__FUNCTION__." DATA out");
        $res = $this->load->view("/admin/members/edit/".$page, $data, true);
        make_response("output", $res, 1);
        return false;
    }
    
   /**
    * Update member's account info
    *
    * @author Drovorubov
    */
    function edit_member_info($page='')
    {
        if(!empty($page))
        {
            $this->_profile_additional($page);
            return;
        }
        
        $data=array();
        $data['error_box']=array();
        $data['message_box']=array();
        $data['fname'] = "";
        $data['lname'] = "";
        $data['add_fields_values'][0] = "";
        $data['add_fields_values'][1] = "";
        $post=prepare_post();  
        $id=isset($post['id']) ? intval($post['id']) : 0; 
        if($id < 2 )
        {
            $res = 'Error: ID is wrong';
            make_response("error", create_temp_vars_set(array($res)), 1);
            simple_admin_log('member_info_modify',$id,true,"invalid_id");
            return;
        }
        if(isset($post['action']) && $post['action']=='save')
        {
            $data=array_merge($data,$post);
            //Prepare input values
            $data['uid']=$data['id'];
            $data['name'] = $data['fname'];            
            $data['last_name'] = $data['lname'];
            $data['status_approved'] = $data['status_approved']=='true' ? 1 : 0;
            $data['status_confirmed'] = $data['status_confirmed']=='true' ? 1 : 0;
            $data['status_suspended'] = $data['status_suspended']=='true' ? 1 : 0;            
            $data['add_field_1'] = $data['add_fields_values'][0];
            $data['add_field_2'] = $data['add_fields_values'][1];            
            $data=$this->user_model->profile_check($data,true);
        
        //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $id);
            if($functionality_enabled_error!==true)
            {   
                make_response("error", create_temp_vars_set(array($functionality_enabled_error)), 1);
                return;
            }
            //*******End of functionality limitations********
            //Check Expire date and set the flag expired to 0
            
            if(intval(config_get("system","config","personal_login_redirect_flag")) && 
                    !isset($data['login_redirect'])||
                    (!empty($data['login_redirect']) && (!check_lenght($data['login_redirect'],1,2048)||
                            !check_url($data['login_redirect']))))
            {
                $data['error_box']['exp_date']=array('display'=>1,'text'=>"<{user_registration_err_login_redirect}>");
            }            
            if( $data['exp_date'] != '' )
            {
                $data['expired'] = 1;
                if(validate_date($data['exp_date']))
                {                
                    if(time() < convert_date($data['exp_date'],true))
                    {
                        $data['expired'] = 0;
                    }
                }
                else
                {
                    $data['error_box']['exp_date']=array('display'=>1,'text'=>"<{user_registration_err_exp_date}>");
                }
            }
            else
            {
                $data['expired'] = 0;
            }
                        
            if(is_msg_displayed($data['error_box']))
            {
                $arr=array();
                foreach($data['error_box'] as $k=>$v)
                {
                    if(is_array($v) && isset($v['display']) && intval($v['display']))
                    {
                        $arr[$k]=$v['text'];
                    }
                }
                make_response("error", create_temp_vars_set($arr), 1);
                simple_admin_log('member_add',false, true, "validation_error");
                return;
            }
			
            if($this->member_model->user_statuses_change($data, $id, true)===false)
            {
                return;
            }
                        
            //Set output data
            $data['name'] = output($data['name']);
            $data['last_name'] = output($data['last_name']);
            $res=array();
            $res['name'] = $data['name']." ".$data['last_name'];
            $res['message']=array();
            $res['message']['saved'] = '<{admin_member_control_account_panel_member_info_msg_info_changed}>';
            if(isset($data['denied_domain']) && $data['denied_domain'])
            {
                $res['message']['denied_domain'] = '<span style="color: yellow;"><{admin_msg_er_0028}></span>';
            }      
fb($res['message']['saved'],'$res[message][saved] ');            
            make_response("output", create_temp_vars_set($res,true), 1);
            simple_admin_log('member_info_modify',$id);
            return;
        }
        //Load edit member info form
        $data = array();
        $data = $this->member_model->get_member_info($id);
        $data['items']=$data;
        $data['error_box']=array(1=>"default");
        $data['message_box']=array(1=>"default");
        $data['id']=$id;
        $data['fname']=$data['name'];
        $data['lname']=$data['last_name'];
        
        $this->load->model('config_model');
        $data['fields'] = $this->config_model->member_page_get('profile'); 
        fb($data['fields'], __FUNCTION__." data_");
        foreach($data['fields'] as $k=>$v)
        {
            $data['fields'][$k]['value']=isset($data[$k]) ? $data[$k] : "";
        }
        
        //value_retype
        if(isset($data['fields']['email']) && intval($data['fields']['email']['enabled']) && intval($data['fields']['email']['retype']))
        {
            $data['fields']['email']['value_retype']=isset($data['email_retype']) ? $data['email_retype'] : $data['email'];            
        }
        if(isset($data['fields']['login']) && intval($data['fields']['login']['enabled']))
        {
            $data['fields']['login']['input_type']='label';
        }
        if(isset($data['fields']['additional']) && intval($data['fields']['additional']['enabled']))
        {
            $data['fields']['additional']['html']=get_member_add_fields_view($id,$data['fields']['additional']);        
        }       
         
        if(!$data['items'])
        {
            $res = "<{admin_member_control_error_user_not_exist}>";
            make_response("error", $res, 1);
            return;
        }
        $tmp = explode('.',$data['items']['expire_date']);
        if( intval($tmp[0]) > 0 )
        {
            $data['items']['expire_date'] = str_replace('.','-',$data['items']['expire_date']);
        }
        else
        {
            $data['items']['expire_date'] = '';
        }
        //Prepare user names
        $data['items']['name_short'] = word_wrap($data['items']['name'],20,2);
        $data['items']['name_short'] = output($data['items']['name_short']);
        $data['items']['last_name_short'] = word_wrap($data['items']['last_name'],20,2);
        $data['items']['last_name_short'] = output($data['items']['last_name_short']);
        $data['items']['full_name'] = $data['items']['name_short'] . " " . $data['items']['last_name_short'];
        //Prepare login
        $data['items']['login_short'] = word_wrap($data['items']['login'],20,2);
        $data['items']['login_short'] = output($data['items']['login_short']);
        $data['items']['login'] = word_wrap($data['items']['login'],50,0,' ');
        $data['items']['login'] = output($data['items']['login']);
        $data['items']['name'] = output($data['items']['name']);
        $data['items']['last_name'] = output($data['items']['last_name']);
        //Member groups
        $this->load->model("member_group_model");
        $data['items']['all_groups']=$this->member_group_model->group_list(true);
        $data['items']['groups']=$this->member_group_model->get_member_groups($id);        
        /* ADDITIONAL FIELDS */
        $data['add_fields'] = get_member_add_fields_view($id);
        /* _ADDITIONAL FIELDS */
       
       $data['error_box']['warning_not_active'] = array('display' => FALSE, 'text' => '<{admin_member_control_account_panel_member_info_button_save_alert}>');
        if( $this->input->post('accnt_panel') == 'show_panel' )
        {
            $data['panel_body'] = $this->load->view("/admin/members/edit/info", $data, true);
            //profile_additional_list
            $this->load->model("user_model");
            $data['profile_additional']=array();
            foreach($this->user_model->profile_additional_list() as $k=>$v)
            {
                $pfs=count($v) ? $v : array(array('account_id'=>0,'account_type_string'=>$k,'account_name'=>('<{user_menu_profile_additional_'.$k.'_add}>')));
                $data['profile_additional']=array_merge($data['profile_additional'],$pfs);
            }
            //end_of_profile_additional_list
            $res=$this->load->view("/admin/members/edit/accnt_panel", $data, true);
        }
        else
        {
            $res = $this->load->view("/admin/members/edit/info", $data, true);
        }
        make_response("output", $res, 1);
        return;
    }


    /**
    * Change Member's Password
    *
    * @author Drovorubov
    */
    function edit_member_pwd()
    {
        $data=array();
        $data['generate']=0;
        $data['error_box']=array();
        $data['message_box']=array(); 
        $post=prepare_post();
        $uid = $post['id'];
        $data['id']=$post['id'];
        if(isset($post['action']) && $post['action'] == 'save')
        {
            if(($error=Functionality_enabled('admin_member_info_modify', $uid))===true)
            {
                $this->load->model('user_model');
                if(intval($uid)>0)
                {
                    $data=array_merge($data,$post);
                    $data['uid']=$uid;
                    $data['generate']=$data['generate']=='true' ? 1 : 0;
                    $data=$this->user_model->password_check($data,true);            
                    if(!is_msg_displayed($data['error_box']))
                    {
                        if( !$this->user_model->new_password($uid,$data['password'],$data['old_password'],$data['old_password_type']) )
                        {
                            $data['error_box']['not_changed']=array('display'=>1,'text'=>"<{user_change_password_error_pwd_not_changed}>");
                        }
                        else
                        {
                            //Authorize user
                            $user_info = $this->user_model->get_profile_by_uid($uid);
                            $user_info = $user_info[0];
                            $remote_addr = getenv('REMOTE_ADDR');
                            $this->load->model('user_auth_model');
                            $this->user_auth_model->auth($user_info['login'], $user_info['pass'], $uid, false, $remote_addr);
                            $result=send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['password']));
                            if(!send_system_email_to_user($uid,'user_change_password',array('user_new_password'=>$data['password'])))
                            {
                                $data['error_box']['not_sent']=array('display'=>1,'text'=>"<{user_change_password_error_email_not_sent}>");                            
                            }
                            else
                            {
                                $data['message_box']['changed']=array('display'=>1,'text'=>"<{user_change_password_msg_pwd_changed}>");
                            }                            
                        }
                    }
                }  
            }
            else
            {
                $data['error_box']['functionality_disabled']=array('display'=>1,'text'=>$error);
            }
            
        fb($data['error_box'],'$data["error_box"]');
            if(is_msg_displayed($data['error_box']))
            {
                $arr=array();
                foreach($data['error_box'] as $k=>$v)
                {
                    if(is_array($v) && isset($v['display']) && intval($v['display']))
                    {
                        $arr[$k]=$v['text'];
                    }
                }
                make_response("error", create_temp_vars_set($arr), 1);
                simple_admin_log('member_pwd_modify',false, true, "validation_error");
                return;
            }
            if(is_msg_displayed($data['message_box']))
            {
                fb($data['message_box'],'$data["message_box"]');
                $arr=array();
                foreach($data['message_box'] as $k=>$v)
                {
                    if(is_array($v) && isset($v['display']) && intval($v['display']))
                    {
                        $arr[$k]=$v['text'];
                    }
                }
                make_response("output", create_temp_vars_set($arr), 1);
                simple_admin_log('member_pwd_modify');
                return;
            }                                    
        }
        
        
        $this->load->model("config_model");
        $data['fields'] = $this->config_model->member_page_get('password');
        unset($data['fields']['old_password']);
        if(isset($data['fields']['password']) && intval($data['fields']['password']['enabled']))
        {
            $data['fields']['password']['input_type']='password';
            if(isset($data['fields']['password']['generate']) && intval($data['fields']['password']['generate']))
            {
                $data['fields']['password']['optional']="<tr><td align='right'>
                <{admin_member_control_account_panel_change_password_field_generate_password}></td><td></td>
            <td><input type='checkbox' id='generate' name='generate' /></td><td></td></tr>";
            }
        }
        fb($data['fields'],"password_fields");
        
        //Show page
        $data['form_fields']=array();
        $res = $this->load->view("/admin/members/edit/pwd", $data, true);
        make_response("output", $res, 1);
        return;
    }
    
    function edit_member_pwd1()
    {
        $pswd4update = '';
        $id = intval($this->input->post('id'));
        if( $id < 2 )
        {
            $res = 'wrong id';
            make_response("error", $res, 1);
            simple_admin_log('member_pwd_modify',$id,true,"invalid_id");
            return;
        }
        //Get post param action
        $action = $this->input->post('action');
        if( $action == 'update')
        {
            
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $id);
            if($functionality_enabled_error!==true)
            {   
                make_response("error",$functionality_enabled_error, true);
                return;
            }
            //*******End of functionality limitations********
            
            //Getting password params
            $is_gen_pass = prepare_text($this->input->post('is_gen_pass'));
            //Set password value
            if($is_gen_pass)
            {
                $pswd4update = $this->user_model->generate_password();
            }
            else
            {
                $password1 = prepare_text($this->input->post('password1'));
                $password2 = prepare_text($this->input->post('password2'));
                //Check empty password
                if( $password1 == '' )
                {
                    $res = "<{admin_member_control_error_empty_fields}>";
                    make_response("error", $res, 1);
                    simple_admin_log('member_pwd_modify',$id,true,"validation_error");
                    return;
                }
                // Check password length
                if( mb_strlen($password1) > 64 )
                {
                    $res = "<{admin_member_control_error_field_password_toolong}>";
                    make_response("error", $res, 1);
                    simple_admin_log('member_pwd_modify',$id,true,"validation_error");
                    return;
                }
                if( mb_strlen($password1) < 5 )
                {
                    $res = "<{admin_member_control_error_field_password_tooshort}>";
                    make_response("error", $res, 1);
                    simple_admin_log('member_pwd_modify',$id,true,"validation_error");
                    return;
                }
                //Check password chars
                if( !$this->_check_password_chars($password1) )
                {
                    $res = "<{admin_member_control_error_field_password_wrong_chars}>";
                    make_response("error", $res, 1);
                    simple_admin_log('member_pwd_modify',$id,true,"validation_error");
                    return;
                }
                // Check password coincidence
                if( $password1 != $password2)
                {
                    $res = "<{admin_member_control_error_passwords_coincidence}>";
                    make_response("error", $res, 1);
                    simple_admin_log('member_pwd_modify',$id,true,"validation_error");
                    return;
                }
                $pswd4update = $password1;
            }
            //Update password
            $updated = $this->member_model->set_member_pwd($id,$pswd4update);
            if(!$updated['result'])
            {
                $res = "<{admin_member_control_error_password_not_changed}>";
                make_response("error", $res, 1);
                simple_admin_log('member_pwd_modify',$id,true,"not_updated");
                return;
            }
            
            $result=send_system_email_to_user($id,'user_change_password',array('user_new_password'=>$pswd4update));
            if(!$result)
            {
                $error = '<{admin_member_control_error_member_info_email_not_sent}>';
            }
            
            
            //Prepare output data
            $res = '<{admin_member_control_account_panel_change_password_msg_update_success}>';
            make_response("output", $res, 1);
            simple_admin_log('member_pwd_modify',$id);
            return;
        }
        $data = array();
        //Load change password form
        $res = $this->load->view("/admin/members/edit/pwd", $data, true);
        make_response("output", $res, 1);
        return;
    }


    /**
    * Getting member account info
    *
    * @author Drovorubov
    */
    function info()
    {
        $error = '';
        $id = intval($this->input->post('id'));
        if( $id < 2 )
        {
            $error = "<{admin_member_control_error_user_not_exist}>";
        }

        $data = array();
        if( $error == '' )
        {
            $data['items'] = $this->member_model->get_member_info($id);
            //fb($data['items'],"data['items']");
            
            if(!$data['items'])
            {
                $error = "<{admin_member_control_error_user_not_exist}>";
            }
            else
            {
                $this->load->model("member_group_model");
                $data['items']['groups']=implode(", ",$this->member_group_model->get_member_groups($id));
            }
        }

        if( $error != '' )
        {
            $data['error'] = $error;
        }
        else
        {
            $data['items']['login_title'] = word_wrap($data['items']['login'],30,0,' ');
            $data['items']['login_title'] = output($data['items']['login_title']);
            $data['items']['login'] = word_wrap($data['items']['login'],60,0,' ');
            $data['items']['login'] = output($data['items']['login']);
            $data['items']['email'] = word_wrap($data['items']['email'],60,0,' ');
            $data['items']['email'] = output($data['items']['email']);
            $data['items']['name'] = word_wrap($data['items']['name'],60,0,' ');
            $data['items']['name'] = output($data['items']['name']);
            $data['items']['last_name'] = word_wrap($data['items']['last_name'],60,0,' ');
            $data['items']['last_name'] = output($data['items']['last_name']);
            // Get additional fields from DB
            /* ADDITIONAL FIELDS */
            $data['add_field_values'] = get_user_add_fields($id);
            /* _ADDITIONAL FIELDS */

            // Add Payments summary information
            $data['items']['summary'] = $this->member_model->subscribe_summary_all($id);
            if($data['items']['summary'])
            {
                $data['items']['summary']['num'] = intval($data['items']['summary']['num']);
                $data['items']['summary']['total'] = amount_to_print($data['items']['summary']['total']);
            }
            $data['items']['summary']['active_num'] = $this->member_model->subscribe_summary_active($id);
            //Set currency
            $data['items']['summary']['currency'] = config_get("system", "config", "currency_code");
        }
        $data['back'] =  input_text($this->input->post('back_link'));
        //fb($data,"data");
        $res=$this->load->view("/admin/members/info", $data, true);
        make_response("output", $res, 1);
        return;
    }


    /**
    * Delete a member
    *
    * @author Drovorubov
    */
	function delete_user()
	{
        $mbr_list = $this->input->post('mbrlist');
        $mbr_list = explode('!',$mbr_list);
        $num = count($mbr_list);
        if( empty($mbr_list[$num-1]) && $num==2 )
        {
            unset($mbr_list[$num-1]);
        }
        if( !is_array($mbr_list) || count($mbr_list) < 1 )
        {
            $res = '<{admin_member_control_error_member_id_empty}>';
            make_response("error", $res, 1);
            simple_admin_log('member_delete',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
            return;
        }
        $error = false;
        //Delete members for all id values in array
        foreach( $mbr_list as $mid )
        {
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $mid);
            if($functionality_enabled_error!==true)
            {   
                $fun_error=$functionality_enabled_error;
                $error = true;
                //*******End of functionality limitations********
            }
            else
            {
                $del = intval($mid!=1) ? $this->member_model->delete($mid) : false;
                if( !$del )
                {
                    $error = true;
                }
            }
        }
        if( !$error )
        {
            $res = '<{admin_member_control_member_list_msg_delete_success}>';
            make_response("output", $res, 1);            
        }
        else
        {
            $res = '<{admin_member_control_error_member_id_invalid}>';
            make_response("error", isset($fun_error)?$fun_error:$res, 1);            
        }
         simple_admin_log('member_delete',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), $error, "not_deleted");
        return;
    }


    /**
    * Add a new member
    *
    * @author Drovorubov
    */
    function add()
	{
        $error = '';
		$data=array();
        $data['login']='';
        $data['email']='';
        $data['email_retype']='';
        $data['fname']='';
        $data['lname']='';
        $data['tos']='';
        $data['generate']='';
        $data['password']='';
        $data['error_box']=array(1=>"default");
        $data['message_box']=array(1=>"default");
        $post=prepare_post();
        fb($post,"post");

        if(isset($post['action']) && $post['action']=='save')
        {
            $data=array_merge($data,$post);
            $data['generate']=$data['generate']=='true' ? 1 : 0;
            $data['status_approved'] = $data['status_approved']=='true' ? 1 : 0;
            $data['status_confirmed'] = $data['status_confirmed']=='true' ? 1 : 0;
            $data['status_suspended'] = $data['status_suspended']=='true' ? 1 : 0;
            $data=$this->user_model->registration_check($data,true);
            //Getting post params
            $data['exp_date'] = prepare_text($this->input->post('exp_date'));
            $data['pwd'] = $data['password'];
            $data['name'] = $data['fname'];
            $data['last_name'] = $data['lname'];
            $data['groups']=$this->input->post('groups');
            $data['language_id']=isset($data['language_id']) && intval($data['language_id'])>0 ? $data['language_id'] : $this->user_auth_model->get_default_language();
            
            //check login redirect if enabled
            if(intval(config_get("system","config","personal_login_redirect_flag")) && 
                    !isset($data['login_redirect'])||
                    (!empty($data['login_redirect']) && (!check_lenght($data['login_redirect'],1,2048)||
                            !check_url($data['login_redirect']))))
            {
                $data['error_box']['exp_date']=array('display'=>1,'text'=>"<{user_registration_err_login_redirect}>");
            }
            //Check Expire date and set the flag expired to 0
            if( $data['exp_date'] != '' )
            {
                $data['expired'] = 1;
                if(validate_date($data['exp_date']))
                {                
                    if(time() < convert_date($data['exp_date'],true))
                    {
                        $data['expired'] = 0;
                    }
                }
                else
                {
                    $data['error_box']['exp_date']=array('display'=>1,'text'=>"<{user_registration_err_exp_date}>");
                }
            }
            else
            {
                $data['expired'] = 0;
            }
            
            if(intval($data['status_confirmed'])<=0)
            {
                $data['ac_code'] = md5(mktime().uniqid("")).(md5(mktime()));
            }
            
            if(is_msg_displayed($data['error_box']))
            {
                $arr=array();
                foreach($data['error_box'] as $k=>$v)
                {
                    if(is_array($v) && isset($v['display']) && intval($v['display']))
                    {
                        $arr[$k]=$v['text'];
                    }
                }
                make_response("error", create_temp_vars_set($arr), 1);
                simple_admin_log('member_add',false, true, "validation_error");
                return;
            }
            
            fb($data,"DATA_BEFORE_ADD");
            //Add data to DB
            $id = $this->member_model->add($data);
            if( $id > 0 )
            {
                //insert additional fields
                $_POST['id']=$id;
                $errs=set_user_add_fields($_POST);
                if($errs !== true)
                {
                    make_response("error",create_temp_vars_set($errs), 1);
                    simple_admin_log('member_add',$id, true, $errs);
                    return;
                }
                // _insert additional fields
                $lang_id=$this->user_model->get_lang($id);
                $result=send_system_email_to_user($id,'user_registration_completed',array('user_password'=>$data['pwd']));
                if(!$result)
                {
                    $error = '<{admin_member_control_error_member_info_email_not_sent}>';
                }
                $result=send_system_subscription_to_admins('admin_new_member_registered',array(
                'user_login'=>$data['login']));
                
                $statuses_strings=array();                
                if(intval($data['expired'])>0)
                {
                    $result=send_system_email_to_user($id,'user_account_expire');
                }                
                if(intval($data['status_approved'])>0)
                {
                    $statuses_strings['approve']="<{user_profile_status_approved}>" ;
                }
                
                if(intval($data['status_confirmed'])>0)
                {
                    $statuses_strings['activate']="<{user_profile_status_activated}>";
                }
                else
                {
                    $result=send_system_email_to_user($id,'user_account_activation',array('user_activation_link'=>site_url('user/activate/'.$id.'/'.$data['ac_code'])));
                    if(!$result)
                    {
                        $error = '<{admin_member_control_error_member_activation_link_not_sent}>';
                    }
                }                
                if(intval($data['status_confirmed'])>0)
                {
                    $statuses_strings['suspend']="<{user_profile_status_suspended}>";
                }
                 
                if(count($statuses_strings)>0)
                {
                    $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang(implode(", ",$statuses_strings),$lang_id)));
                    if(!$result)
                    {
                        $error = '<{admin_member_control_error_email_not_sent}>';
                    }
                }
                
                //Set output data
                $res=array();
                $res['saved'] = '<{admin_member_control_member_list_msg_added_success}>';
// Added by Konstantin X @ 2009.01.09 +++
                //$this->load->model("auth_model");
                //$is_trusted = $this->auth_model->check_email_domain($data['email']);
                //if ($is_trusted == 2) $res .= ' <span style="color: yellow;"><{admin_msg_er_0028}></span>';
                if(isset($data['denied_domain']) && $data['denied_domain'])
                {
                    $res['denied_domain'] = '<span style="color: yellow;"><{admin_msg_er_0028}></span>';
                }
// Added by Konstantin X @ 2009.01.09 ---
                $this->member_list(create_temp_vars_set($res));
                return;
                
                make_response("message", create_temp_vars_set($res), 1);
                simple_admin_log('member_add',$id);
                return;
            }
            else
            {
                $err = "<{admin_member_control_error_action_member_add}>";
                make_response("error", create_temp_vars_set(array('not_added'=>$err)), 1);
                simple_admin_log('member_add',false, true, "not_added");
                return;
            }
        }
        
        $this->load->model('config_model');
        $data['fields'] = $this->config_model->member_page_get('registration'); 
        unset($data['fields']['image_code']);
        unset($data['fields']['tos']);
        if(isset($data['fields']['additional']) && intval($data['fields']['additional']['enabled']))
        {
            $data['fields']['additional']['html']=get_member_add_fields_view();        
        }
        if(isset($data['fields']['password']) && intval($data['fields']['password']['enabled']))
        {
            $data['fields']['password']['width']=200;
            $data['fields']['password']['input_type']='password';
            if(isset($data['fields']['password']['generate']) && intval($data['fields']['password']['generate']))
            {
                $data['fields']['password']['optional']="<tr><td align='right'>
                <{admin_member_control_add_member_field_generate_password}></td><td></td>
            <td><input type='checkbox' id='generate' name='generate' /></td><td></td></tr>";
            }
        }
        
        fb($data['fields'],'$data["fields"]');
        // Get additional fields from DB
        
        $this->load->model("member_group_model");
        $data['all_groups']=$this->member_group_model->group_list(true);        
        // Show adding form
		$res=$this->load->view("/admin/members/add", $data, true);
		make_response("output", $res, 1);
	}


    /**
    * Prepare array as additional fields values from POST source
    *
    * @author Drovorubov
    * @param array $add_fields_list
    * @return array
    */
    function _prepare_post_usr_add_fields($add_fields_list)
    {
        $i = 0;
        $add_fields_user_data = array();
        if( is_array($_POST['add_fields_keys']) && count($_POST['add_fields_keys']) > 0 )
        {
            //Collect array from input POST
            $add_fields_post_data = array();
            foreach( $_POST['add_fields_keys'] as $key=>$val )
            {
                if( strpos($val,'[]') > 10 )
                {
                    $val = substr($val,0,strpos($val,'[]'));
                    $vlist = explode(',',$_POST['add_fields_values'][$key]);
                    $add_fields_post_data[$val] = (!$vlist) ? '' : $vlist;
                }
                else
                {
                    $add_fields_post_data[$val] = $_POST['add_fields_values'][$key];
                }
            }
            //Collect array as additional fields with user data
            foreach( $add_fields_list as $null=>$field_id_from_db )
            {
                $field_id_from_db = $field_id_from_db['id'];
                if( isset($add_fields_post_data['add_field_'.intval($field_id_from_db)]) )
                {
                    unset($post_value,$key,$val,$prepare_post_value);
                    $post_value = $add_fields_post_data['add_field_'.intval($field_id_from_db)];
                    $add_fields_user_data[$i]['id'] = intval($field_id_from_db);
                    if( is_array($post_value) and sizeof($post_value)>0)
                    {
                        foreach( $post_value as $key=>$val )
                        {
                            $prepare_post_value[$key] = prepare_text($val);
                        }
                        $add_fields_user_data[$i]['value'] = $prepare_post_value;
                    }
                    else
                    {
                        $add_fields_user_data[$i]['value'] = prepare_text($post_value);
                    }
                }
                else
                {
                    $add_fields_user_data[$i]['id'] = intval($field_id_from_db);
                    $add_fields_user_data[$i]['value'] = '';
                }
                $i++;
                unset($null,$field_id_from_db);
            }
        }
        return $add_fields_user_data;
    }



    /**
    * Prepare array as additional fields values from DB source
    *
    * @author Drovorubov
    * @param array $add_fields_list
    * @param integer $uid
    * @return array
    */
    function _prepare_db_usr_add_fields($add_fields_list,$uid)
    {
        $i = 0;
        $add_fields_user_data = array();
        //Collect array from DB
        $data = $this->member_model->get_user_add_fields($uid);
        $user_data_arr = array();
        if( $data && count($data) > 0 )
        {
            foreach($data as $item)
            {

                //$tmp  = unserialize($item['field_value']);
                //$key = $tmp['id'];
                //$val = $tmp['value'];
                $key = $item['field_id'];
                $val = explode("\n",$item['field_value']);
                $user_data_arr[$key] = $val;
            }
        }
        if( is_array($user_data_arr) && count($user_data_arr) > 0 )
        {
            //Collect array as additional fields with user data
            foreach( $add_fields_list as $null=>$field_id_from_db )
            {
                $field_id_from_db = intval($field_id_from_db['id']);
                if( isset($user_data_arr[$field_id_from_db]) )
                {
                    unset($user_value,$key,$val,$prepare_user_value);
                    $user_value = $user_data_arr[$field_id_from_db];
                    $add_fields_user_data[$i]['id'] = $field_id_from_db;
                    if( is_array($user_value) and sizeof($user_value)>0)
                    {
                        //add value as array
                        foreach( $user_value as $key=>$val )
                        {
                            $prepare_user_value[$key] = prepare_text($val);
                        }
                        $add_fields_user_data[$i]['value'] = $prepare_user_value;
                    }
                    else
                    {
                        $add_fields_user_data[$i]['value'] = prepare_text($user_value);
                    }
                }
                else
                {
                    $add_fields_user_data[$i]['id'] = $field_id_from_db;
                    $add_fields_user_data[$i]['value'] = '';
                }
                $i++;
                unset($null,$field_id_from_db);
            }
        }
        return $add_fields_user_data;
    }

    /**
    * Convert user additional fields values array as [0][id=>value] to id=>value
    * If the second param is not null it converts to array name=>value
    *
    * @author Drovorubov
    * @param array $add_fields_values
    * @param array $add_fields_names
    * @return array
    */
    function _convert_add_fields_values($add_fields_values, $add_fields_names = array() )
    {
        $rv = array();
        //Prepare array with id
        $idval_list = array();
        foreach( $add_fields_values as $null => $value )
        {
            $idval_list[intval($value['id'])] = $value['value'];
        }
        //Prepare array with names
        if( is_array($add_fields_names) && count($add_fields_names) > 1 )
        {
            foreach( $add_fields_names as $key => $value )
            {
                if( !isset($idval_list[$value['id']]) )
                {
                    $rv[$value['name']] = '';
                }
                else if( is_array($idval_list[$value['id']]) )
                {
                    $rv[$value['name']] = $idval_list[$value['id']][0];
                }
                else
                {
                    $rv[$value['name']] = $idval_list[$value['id']];
                }
                //wrap long strings;
                $rv[$value['name']] = word_wrap($rv[$value['name']],60,0,' ');
                $rv[$value['name']] = output($rv[$value['name']]);
            }
        }
        else
        {
            $rv = $idval_list;
        }
        return $rv;
    }



    /**
    * Check user additional fields values
    *
    * @author Drovorubov
    * @param array $add_fields_arr
    * @return string
    */
    function _check_add_fields_values($add_fields_arr)
    {
        $error_text = "";
        $error_num = 0;
        foreach ( $add_fields_arr as $add_field )
        {
            $check_result = check_field($add_field['id'], $add_field['value']);
            if( $check_result !== true )
            {
                $error_num++;
                if( mb_strlen($check_result) > 1 )
                {
                    $error_text .= $check_result . '<br/>';
                }
            }
        }
        if( $error_num > 0 )
        {
            if( $error_text != '' )
            {
                return $error_text;
            }
            return "Error: Additinal fields have wrong values";
        }

        return '';
    }

    /**
    *
    * Shows Member list
    *
    * @author Drovorubov
    *
    */
    function member_list($additional="")
    {
		$is_oper = $this->input->post('is_oper');
		if ($is_oper)
		{
			if ($this->change_users_status() === false)
				return;
		}
		$search_params_err = false;
		$data = array();
		
		$per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
		//Set search params
		
		$operation = array(
						   '<{admin_memberlist_status_unsuspend}>'=>1,
						   '<{admin_memberlist_status_suspend}>'=>1,
						   '<{admin_memberlist_status_approve}>'=>1,
						   '<{admin_memberlist_status_disapprove}>'=>1,
						   '<{admin_memberlist_status_inactivate}>'=>1,
						   '<{admin_memberlist_status_activate}>'=>1,
						   '<{admin_memberlist_status_delete}>'=>1,
						   );
		
		$search_params = array();
		$search_status = trim($this->input->post('search_status'));
		if (!$search_status)
		{
			$search_status = 'status_active';
			$search_params['search_status'] = $search_status;
		}
		switch ($search_status)
		{
			case 'status_active':
				$operation['<{admin_memberlist_status_unsuspend}>'] = 0;
				break;
			case 'status_all':
				//$operation['<{admin_memberlist_status_approve}>'] = 0;
				break;
			case 'status_suspend':
				$operation['<{admin_memberlist_status_suspend}>'] = 0;
				break;
			case 'status_approve':
				$operation['<{admin_memberlist_status_disapprove}>'] = 0;
				break;
			case 'status_activate':
				$operation['<{admin_memberlist_status_inactivate}>'] = 0;
				break;
			case 'status_expired':
				//$operation['<{admin_memberlist_status_activate}>'] = 0;
				break;
			case 'status_inactive':
				//$operation['<{admin_memberlist_status_activate}>'] = 0;
				break;
		}
        if( $this->input->post('is_search') == 'true' )
        {
			$search_params['search_status'] = $search_status;
			if($this->input->post('letter')&&$this->input->post('letter')!='null')
            {
                $search_params['letter'] = substr(input_text($this->input->post('letter')),0,1);
            }
            else
            {
                //Getting searching params for key
                if($this->input->post('search_key')&&$this->input->post('search_key')!='null')
                {
                    $search_params['search_key'] = input_text($this->input->post('search_key'));
                    $search_params['search_val'] = input_text($this->input->post('search_val'));
                }
                //Getting searching params for 2 dates
                $date_from = $this->input->post('date_from')=='null' ? '' : $this->input->post('date_from');
                $date_to = $this->input->post('date_to')=='null' ? '' : $this->input->post('date_to');
                if( !empty($date_from) && !empty($date_to) && $date_to!='null' && $date_from!='null')
                {
                    if( validate_date($date_from) && validate_date($date_to) )
                    {
                        $search_params['date_from'] = $date_from;
                        $search_params['date_to'] = $date_to;
                    }
                    else
                    {
                        $search_params_err = true;
                    }
                }
                else if($this->input->post('date_period') && $this->input->post('date_period') != 'null' )
                {
                    $date_period = $this->input->post('date_period');
                    $search_params['date_period'] = $date_period;
                }
            }
        }
        //Getting data from DB
        //$data = array();
		
		///////////////////// for unsuspend user //////////////////////
		$from_page = $this->input->post('from_page');
		if (isset($from_page) and ($from_page=='member_list') and $from_page )
		{
			$mid = intval($this->input->post('mbrlist')); 
			$unsuspend = $this->member_model->unsuspend($mid);
            if( !$unsuspend )
            {
                $error = true;
            }
            else
            {
                $id=$mid;
                $lang_id=$this->user_model->get_lang($id); 
                $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang("<{user_profile_status_unsuspended}>",$lang_id)));
            }
            //modified by Sergey Makarenko @ 17.10.2008, 14:54
            $this->user_auth_model->Clear_autoban_records_for_user($mid);
			if( isset($error) and $error )
			{
				$mess_err = array();
				//$data['mess_err'] = array();
				//$data['mess_err']['unsuccess'] = array('Error: Wrong ID value', 'display'=>1);
				$mess_err = array('Error: Wrong ID value', 'display'=>1);
			}
			else
			{ 
				$messages = array();
				//$data['messages'] = array();
				//$data['messages'][] = array('text'=>'<{admin_member_control_unsuspend_delete_msg_success_unsuspended}>', 'display'=>1);
				//$data['messages']['success'] = array('text'=>'<{admin_member_control_unsuspend_delete_msg_success_unsuspended}>', 'display'=>1);
				$messages = array('text'=>'<{admin_member_control_unsuspend_delete_msg_success_unsuspended}>', 'display'=>1);
			}
		}
		
		/////////////////////// end for unsuspend user ///////////////////////////////////
		
		
        $data['pages'] = 1;
        $data['per_page'] = $per_page;
        if(!$search_params_err)
        {
            $data=$this->member_model->member_list($current_page, $per_page, $sort_by, $sort_how, $search_params);//fb($this->db->last_query(), 'data my - ');
            if ($data['total'] > $per_page)
            {
                $data['pages'] = ceil($data['total'] / $per_page);
                if( $current_page > $data['pages'])
                {
                    $current_page = ($current_page > 2) ? $current_page - 1 : 1;
                }
            }
            else
            {
                $data['pages'] = 1;
            }
        }
		
		if (isset($mess_err) and is_array($mess_err))
		{
			$data['mess_err'] = array();
			$data['mess_err']['unsuccess'] = $mess_err;
		}
		if (isset($messages) and is_array($messages))
		{
			$data['messages'] = array();
			$data['messages']['success'] = $messages;
		}
		
        $this->load->model("member_group_model");
        $data['groups']=$this->member_group_model->group_list();

        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Set search params for key
        if(isset($search_params['search_key']))
        {
            $data['search_key'] = output($search_params['search_key']);
        }
        if(isset($search_params['search_val']))
        {
            $data['search_val'] = output($search_params['search_val']);
        }
        //Set search params for date from and to
        if( isset($date_from) && !empty($date_from) &&
            isset($date_to) && !empty($date_to) )
        {
            $data['date_from'] = $date_from;
            $data['date_to'] = $date_to;
        }
        else if( isset($date_period) )
        {
            $data['date_period'] = $date_period;
        }
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if( count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                $item['login'] = word_wrap($item['login'],15,2);
                $item['login'] = output($item['login']);
                $item['email2show'] = word_wrap($item['email'],15,2);
                $item['email2show'] = output($item['email2show']);
                $item['email'] = output($item['email']);
                //Get subscriptions count
                $tmp = array();
                $tmp = $this->member_model->get_member_subscriptions($item['id']);
                if(isset($tmp['price_sum']))
                {
                    $tmp['price_sum'] = $this->_convert_price($tmp['price_sum']);
                }
				$item['susp_reason'] = $this->member_model->suspend_reason_info($item['suspend_reason_id']);
                $item['subscriptions'] = $tmp;
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
				$node['is_demo_user'] = false;
				$functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $item['id']);
				if($functionality_enabled_error!==true)
				{   
					$node['is_demo_user'] = true;
				}
                $data['rows'] .= $this->load->view("/admin/members/list/node", $node, true);
            }
        }
        else
        {
            $data['rows'] .= $this->load->view("/admin/members/list/empty", array(), true);
        }
		$data['operation'] = $operation;
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_member_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_member_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_member_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_member_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Get the page
        
        $data['sort_by']=$sort_by;
        $data['sort_how']=$sort_how;
				
		$data['search_status'] = $search_status;
		
		$data['is_activate'] = config_get('SYSTEM', 'CONFIG', 'member_need_activation');
		$data['is_approve'] = config_get('SYSTEM', 'CONFIG', 'member_approve_needed');
		
		$data = array_merge($data,$this->member_model->add_panel_vars_ex($data,"member_list"));
        
        $data['susp_reason'] = $this->member_model->suspend_reasons_list('','','by_name',$sort_how);
		
		$res=$this->load->view("/admin/members/list/page", $data, true);
        make_response("output", $res, 1,$additional);
    }


    /**
    * Shows not approved members list
    *
    * @author Drovorubov
    */
    function approve()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));

        //Getting data from DB
        $data = array();
        $data=$this->member_model->not_approved_list($current_page, $per_page, $sort_by, $sort_how);
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        //Get suspend reasons list
        unset($tmp);
        $tmp = $this->member_model->suspend_reasons_list();
        $node['suspend_reasons_list'] = array();
        foreach( $tmp['items'] as $key=>$val )
        {
            $name = word_wrap($val['name'],50,2);
            $name = output($name);
            $node['suspend_reasons_list'][] = array('id'=>$val['id'],'name'=>$name);
        }
        //Prepare data for a node page
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                //Prepare data to display
                $item['login'] = word_wrap($item['login'],15,2);
                $item['login'] = output($item['login']);
                $item['name'] = word_wrap($item['name'],25,2);
                $item['name'] = output($item['name']);
                $item['last_name'] = word_wrap($item['last_name'],25,2);
                $item['last_name'] = output($item['last_name']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/members/approve/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/members/approve/empty", array(), true);
        }
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_unapproved_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_unapproved_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_unapproved_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_unapproved_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        
        $data['sort_by']=$sort_by;
        $data['order_by']=$sort_how;
        //Get the whole page
		$res=$this->load->view("/admin/members/approve/page", $data, true);
        make_response("output", $res, 1);
    }


    /**
    * Approve members from the list
    *
    * @author Drovorubov
    */
    function approve_user()
    {
        $mbr_list = $this->input->post('mbrlist'); 
        $mbr_list = explode('!',$mbr_list);
        $num = count($mbr_list);
        if( empty($mbr_list[$num-1]) && $num==2 )
        {
            unset($mbr_list[$num-1]);
        }
        if(!is_array($mbr_list) || count($mbr_list) < 1)
        {
            $res = '<{admin_member_control_approve_suspend_error_id_wrong}>';
            make_response("error", $res, 1);
            simple_admin_log('member_approve',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
            return;
        }
        $error = false;
        //Set approved values for every member's id
        foreach( $mbr_list as $mid )
        {
            $approved = $this->member_model->approve($mid);
            if( !$approved )
            {
                $error = true;
            }
            else
            {
                $id=$mid;
                $lang_id=$this->user_model->get_lang($id);
                $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang("<{user_profile_status_approved}>",$lang_id)));
            }
        }
        //Set return message
        if( $error )
        {
            $res = 'Error: Wrong ID value';
            make_response("error", $res, 1);
        }
        else
        {
            $res = '<{admin_member_control_approve_suspend_success_approved}>';
            make_response("output", $res, 1);
        }
        simple_admin_log('member_approve',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), $error, "not_approved");
        return;
    }



    /**
    * Shows not confirmed members list
    *
    * @author Drovorubov
    */
    function confirmation()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Getting data from DB
        $data = array();
        $data=$this->member_model->not_confirm_list($current_page, $per_page, $sort_by, $sort_how);
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                //Prepare data to display
                $item['login'] = word_wrap($item['login'],20,2);
                $item['login'] = output($item['login']);
                $item['name'] = output($item['name']);
                $item['last_name'] = output($item['last_name']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/members/confirmation/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/members/confirmation/empty", array(), true);
        }
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_unconfirmed_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_unconfirmed_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_unconfirmed_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_unconfirmed_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        
        $data['sort_by']=$sort_by;
        $data['order_by']=$sort_how;
        
		$res=$this->load->view("/admin/members/confirmation/page", $data, true);
        make_response("output", $res, 1);
    }


    /**
    * Confirm members from the list
    *
    * @author Drovorubov
    */
    function confirm_user()
    {
        $mbr_list = $this->input->post('mbrlist');
        $mbr_list = explode('!',$mbr_list);
        $num = count($mbr_list);
        if( empty($mbr_list[$num-1]) && $num==2 )
        {
            unset($mbr_list[$num-1]);
        }
        if(!is_array($mbr_list) || count($mbr_list) < 1)
        {
            $res = '<{admin_member_control_confirm_suspend_error_not_all_confirmed}>';
            make_response("error", $res, 1);
            simple_admin_log('member_activation',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
            return;
        }
        $error = false;;
        //Set confirmed values for every member's id
        foreach( $mbr_list as $mid )
        {
            $confirmed = $this->member_model->activate($mid);
            if( !$confirmed )
            {
                $error = true;
            }
            else
            {
                $id=$mid;
                $lang_id=$this->user_model->get_lang($id);
                $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang("<{user_profile_status_activated}>",$lang_id)));
            }
        }
        //Set return message
        if( $error )
        {
            $res = 'Error: Wrong ID value';
            make_response("error", $res, 1);
        }
        else
        {
            $res = '<{admin_member_control_confirm_suspend_success_confirmed}>';
            make_response("output", $res, 1);
        }
        simple_admin_log('member_activation',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), $error, "not_activated");
        return;
    }



    /**
    * Shows suspended members list
    *
    * @author Drovorubov
    */
    function unsuspend()
    {
        $per_page = intval($this->input->post('ppage'));
        $ppage_set = get_perpagelist();
        if( !in_array($per_page, $ppage_set) )
        {
            $per_page = config_get('PAGER','default_perpage');
        }

        $current_page = intval($this->input->post('cpage'));
        if( intval($current_page) <= 0 )
        {
            $current_page = 1;
        }
        $sort_by = input_text($this->input->post('ord'));
        $sort_how = input_text($this->input->post('ord_type'));
        //Getting data from DB
        $data = array();
        $data=$this->member_model->suspended_list($current_page, $per_page, $sort_by, $sort_how);
        if ($data['total'] > $per_page)
        {
            $data['pages'] = ceil($data['total'] / $per_page);
            if( $current_page > $data['pages'])
            {
                $current_page = ($current_page > 2) ? $current_page - 1 : 1;
            }
        }
        else
        {
            $data['pages'] = 1;
        }
        $data['current_page'] = $current_page;
        $data['per_page_set'] = $ppage_set;
        //Prepare data for the node page
        $data['rows'] = '';
        $tr_class = 'dark';
        $node = array();
        if(count($data['items']) > 0 )
        {
            foreach($data['items'] as $item)
            {
                $tr_class = ($tr_class == 'dark') ? 'light' : 'dark';
                //Prepare data to display
                $item['login'] = word_wrap($item['login'],20,2);
                $item['login'] = output($item['login']);
                $item['name'] = output($item['name']);
                $item['last_name'] = output($item['last_name']);
                $node['row'] = $item;
                $node['tr_class'] = $tr_class;
                $data['rows'] .= $this->load->view("/admin/members/unsuspend/node", $node, true);
            }
        }
        else
        {
            $data['rows'] = $this->load->view("/admin/members/unsuspend/empty", array(), true);
        }
        //Compose pager node
        $data['pager_node1'] = perpage_selectbox($data['per_page_set'],'load_suspended_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_suspended_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        //Compose pager node
        $data['pager_node2'] = perpage_selectbox($data['per_page_set'],'load_suspended_mbr_list',array(),$data['per_page']) . page_selectbox($data['pages'] ,'load_suspended_mbr_list',array('ppage'=>$data['per_page']), $data['current_page']);
        
        $data['sort_by']=$sort_by;
        $data['order_by']=$sort_how;        
        //Get the whole page
		$res=$this->load->view("/admin/members/unsuspend/page", $data, true);
        make_response("output", $res, 1);
    }





    /**
    * Suspend members from the list
    *
    * @author Drovorubov
    */
    function suspend_user()
    {
        $mbr_list = $this->input->post('mbrlist');
        $mbr_list = explode('!',$mbr_list);
        $num = count($mbr_list);
        if( empty($mbr_list[$num-1]) && $num==2 )
        {
            unset($mbr_list[$num-1]);
        }
        if( !is_array($mbr_list) || count($mbr_list) < 1 )
        {
            $res = '<{admin_member_control_approve_suspend_error_id_wrong}>';
            make_response("error", $res, 1);
            simple_admin_log('member_suspend',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
            return;
        }
        //Prepare members list
        $member_params = array();
        for($i=0; $i < count($mbr_list); $i++)
        {
            $tmp = array();
            $tmp = explode('-',$mbr_list[$i]);
            if( $tmp[0] < 1 || (isset($tmp[1]) && !is_numeric($tmp[1])))
            {
                $res = '<{admin_member_control_approve_suspend_error_id_wrong}>';
                make_response("error", $res, 1);
                simple_admin_log('member_suspend',$mbr_list[$i], true, "invalid_id");
                return;
            }
			if (!isset($tmp[1]))
			{
				$tmp[1] = 0;
			}
            $member_params[$i]['user_id'] = $tmp[0];
            $member_params[$i]['reason_id'] = $tmp[1];
        }
        $error = '';
        //Set suspend values for every member's id
        foreach( $member_params as $item )
        {
            
            //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $item['user_id']);
            if($functionality_enabled_error!==true)
            {   
                $error = $functionality_enabled_error;
                continue;
            }
            //*******End of functionality limitations********
            
            $suspended = ($item['user_id']!=1) ? $this->member_model->suspend($item['user_id'],$item['reason_id']) : false;
            if( $suspended )
            {
                $id=$item['user_id'];
                $lang_id=$this->user_model->get_lang($id);
                if($reason=$this->user_model->get_status($id,$lang_id))
                {
                    $reason=$reason[0]['suspend_reason'] ? ' ('.$reason[0]['suspend_reason'].')' : '';
                }                
                $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang("<{user_profile_status_suspended}>".$reason,$lang_id)));
                if(!$result)
                {
                    $error = '<{admin_member_control_error_suspend_email_not_sent}>';
                }                
            }
            else
            {
                $error = '<{admin_member_control_approve_suspend_error_not_all_suspended}>';
            }
        }
        //Set return message
        if( $error != '')
        {
            $res = $error;
            make_response("error", $res, 1);
        }
        else
        {
            $res = '<{admin_member_control_approve_suspend_success_suspend}>';
            make_response("output", $res, 1);
        }
        simple_admin_log('member_suspend',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), ($error != ''),"not_suspended");
        return;
    }


    /**
    * Gets system email attributes by key and send email to member
    *
    * @author Drovorubov
    * @param integer $uid
    * @param integer $reason_id
    * @param string $sysemail_key
    * @return bool
    */
    function _send_sys_email($uid,$reason_id,$sysemail_key)
    {
        return true;
        //Get system email body
        $sys_email = getSysEmail($sysemail_key);
        //Get suspend reason info
        $reason = $this->member_model->suspend_reason_info($reason_id);
        if( $reason )
        {
            $sys_email['descr'] .= "\n" . $reason['descr'];
        }
        //Get member's email
        $user_info = $this->member_model->get_member_info($uid);
        $email_to = $user_info['email'];
        //Get email keys array with values according user id
        $user_email_keys = $this->mail_model->get_user_keys($uid);
        //Replace e-mail body keys to the keys values
        $sys_email['descr'] = $this->mail_model->replace_keys($sys_email['descr'],$user_email_keys);
        //Get current admin email
        $current_admin = $this->mail_model->get_current_admin_info();
        $from = array();
        $from['email'] = $current_admin['email'];
        $from['name'] = $current_admin['login'];
        //Send email
        if( $this->mail_model->mail_model->send_letter($email_to,$from,$sys_email['subject'],$sys_email['descr']) )
        {
            return true;
        }
        return false;
    }


    /**
    * Unsuspend members from the list
    *
    * @author Drovorubov
    */
    function unsuspend_user()
    {
        $mbr_list = $this->input->post('mbrlist');
        $mbr_list = explode('!',$mbr_list);
        $num = count($mbr_list);
        if( empty($mbr_list[$num-1]) && $num==2 )
        {
            unset($mbr_list[$num-1]);
        }
        if(!is_array($mbr_list) || count($mbr_list) < 1)
        {
            $res = '<{admin_member_control_unsuspend_delete_error_id_wrong}>';
            make_response("error", $res, 1);
            simple_admin_log('member_unsuspend',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
            return;
        }
        $error = false;
        //Set unsuspend values for every member's id
        foreach( $mbr_list as $mid )
        {
            $unsuspend = $this->member_model->unsuspend($mid);
            if( !$unsuspend )
            {
                $error = true;
            }
            else
            {
                $id=$mid;
                $lang_id=$this->user_model->get_lang($id);
                $result=send_system_email_to_user($id,'user_profile_status_change',array('user_account_status'=>replace_lang("<{user_profile_status_unsuspended}>",$lang_id)));
            }
            //modified by Sergey Makarenko @ 17.10.2008, 14:54
            $this->user_auth_model->Clear_autoban_records_for_user($mid);
        }
        //Set return message
        if( $error )
        {
            $res = 'Error: Wrong ID value';
             make_response("error", $res, 1);
        }
        else
        {
            $res = '<{admin_member_control_unsuspend_delete_msg_success_unsuspended}>';
             make_response("output", $res, 1);
        }
        simple_admin_log('member_unsuspend',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), $error, "not_unsuspended");
        return;
    }


    /**
    * Validates date format
    *
    * @author Drovorubov
    * @param string $date
    * @return bool
    */
    function _check_date($date)
    {
        if( empty($date) )
        {
            return false;
        }
        //Dates validation
        if( !preg_match('/[0-9]{2}[-]{1}[0-9]{2}[-]{1}[0-9]{4}$/', $date) )
        {
            return false;
        }

        return true;
    }


    /**
    * Validates password allowed characters
    *
    * @author Drovorubov
    * @param string $pwd
    * @return bool
    */
    function _check_password_chars($pwd)
    {
        if( preg_match('/^[^a-zA-Z0-9]/',$pwd) || preg_match('/[\!@#$%^&*=+\/~<>?;-]/',$pwd) )
        {
            return false;
        }
        return true;
    }




    /**
    * Checks member entry fields
    *
    * @author Drovorubov
    * @param array $param
    * @return string
    */
    function _check_member_fields($param)
    {
            $rv = '';
            foreach($param as $key=>$val)
            {
                //Check fields length
                if( $key == 'login')
                {
                    if($val == '')
                    {
                        $rv = "<{admin_member_control_error_empty_fields}>";
                        break;
                    }
                    else if(mb_strlen($val) > 32)
                    {
                        $rv = "<{admin_member_control_error_field_login_toolong}>";
                        break;
                    }
                    else if(mb_strlen($val) < 4)
                    {
                        $rv = "<{admin_member_control_error_field_login_tooshort}>";
                        break;
                    }
                    else if( !preg_match('/^[a-z_].*/',$val) || preg_match('/[^A-Za-z0-9_]+/',$val) )
                    {
                        $rv = "<{admin_member_control_error_field_login_wrong_chars}>";
                        break;
                    }
                }
                else if( $key == 'email' )
                {
                    if($val == '')
                    {
                        $rv = "<{admin_member_control_error_empty_fields}>";
                        break;
                    }
                    else if(mb_strlen($val) > 64)
                    {
                        $rv = "<{admin_member_control_error_field_email_toolong}>";
                        break;
                    }
                    else if(!preg_match('/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}$/',$val))
                    {
                        $rv = "<{admin_member_control_error_field_email_wrong}>";
                        break;
                    }
                }
                else if( $key == 'name' )
                {
                    if($val == '')
                    {
                        $rv = "<{admin_member_control_error_empty_fields}>";
                        break;
                    }
                    else if( mb_strlen($val) > 32 )
                    {
                        $rv = "<{admin_member_control_error_field_first_name_toolong}>";
                        break;
                    }
                }
                else if( $key == 'last_name' )
                {
                    if($val == '')
                    {
                        $rv = "<{admin_member_control_error_empty_fields}>";
                        break;
                    }
                    else if( mb_strlen($val) > 32 )
                    {
                        $rv = "<{admin_member_control_error_field_last_name_toolong}>";
                        break;
                    }
                }
                else if( $key == 'exp_date' && $val != '' && !validate_date($val))
                {
                        $rv = "<{admin_member_control_error_field_expiration_date_wrong}>";
                        break;
                }
            }
            return $rv;
    }



	/**
	 * Check suspreason fields
	 *
	 * @param array $param
	 * @return string
	 */
    function _check_suspreason_fields($param)
    {
        $rv = '';
        foreach($param as $key=>$val)
        {
            if( $key == 'name' )
            {
                if( empty($val) )
                {
                    $rv = "<{admin_member_control_suspend_reason_error_empty_field}>";
                    break;
                }
                else if( mb_strlen($val) > 255 )
                {
                    $rv = "<{admin_member_control_suspend_reason_error_name_toolong}>";
                    break;
                }
            }
            else if( $key == 'descr' )
            {
                if( $val == '' )
                {
                    $rv = "<{admin_member_control_suspend_reason_error_empty_field}>";
                    break;
                }
                else if( mb_strlen($val) > 65536 )
                {
                    $rv = "<{admin_member_control_suspend_reason_error_description_toolong}>";
                    break;
                }
            }
        }
        return $rv;
    }



    /**
    * Checks email client fields
    *
    * @author Drovorubov
    * @param array $param
    * @return string
    */
    function _check_email_client_fields($param)
    {
            $rv = '';
            foreach($param as $key=>$val)
            {
                if( $key == 'template_id' )
                {
                    if( empty($val))
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_empty_field}>";
                        break;
                    }
                    else if( $val < 1 )
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_template_id_wrong}>";
                        break;
                    }
                }
                else if( $key == 'from' )
                {
                    if(mb_strlen($val) > 50)
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_email_toolong}>";
                        break;
                    }
                    else if(!preg_match('/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}$/',$val))
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_email_wrong}>";
                        break;
                    }
                }
                else if( $key == 'subject' )
                {
                    if( empty($val))
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_empty_field}>";
                        break;
                    }
                    else if( mb_strlen($val) > 255 )
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_subject_toolong}>";
                        break;
                    }
                }
                else if( $key == 'msg' )
                {
                    if( $val == '' )
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_empty_field}>";
                        break;
                    }
                    else if( mb_strlen($val) > 65536 )
                    {
                        $rv = "<{admin_member_control_account_panel_email_client_error_message_toolong}>";
                        break;
                    }
                }
            }
            return $rv;
    }


    /**
    * Compares two dates
    *
    * @param string $date_from
    * @param string $date_to
    * @return bool
    */
    function _compare_dates($date_from,$date_to)
    {
        
        
        
        $from = explode("-", $date_from);
        $to = explode("-", $date_to);
        $time_from = mktime(0, 0, 0, $from[1], $from[0], $from[2]);
        $time_to = mktime(0, 0, 0, $to[1], $to[0], $to[2]);
        if ($time_from > $time_to)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    /**
    * Checks email client fields
    *
    * @author Drovorubov
    * @param array $param
    * @return string
    */
    function _check_payment_fields($param)
    { 
            $rv = '';
            foreach($param as $key=>$val)
            {
                if( $key == 'uid' )
                {
                    if( empty($val))
                    {
                        $rv = "<{admin_member_control_account_panel_payments_add_error_member_empty}>";
                        break;
                    }
                    else if( $val < 1 )
                    {
                        $rv = "<{admin_member_control_account_panel_payments_add_error_member_empty}>";
                        break;
                    }
                }
                if( $key == 'product' )
                {
                    if( empty($val))
                    {
                        $rv = "<{admin_member_control_account_panel_payments_add_error_product_empty}>";
                        break;
                    }
                    else if( $val < 1 )
                    {
                        $rv = "<{admin_member_control_account_panel_payments_add_error_product_empty}>";
                        break;
                    }
                }
                
                if( $key == 'transaction' )
                { fb($param);
					if (isset($param['product_type']) and substr($param['product_type'],strlen($param['product_type'])-1)==1)
						if( empty($val))
						{
							$rv = "<{admin_member_control_account_panel_payments_add_error_transaction_empty}>";
							break;
						}
						else if( mb_strlen($val) < 6 || mb_strlen($val) > 32 )
						{
							$rv = "<{admin_member_control_account_panel_payments_add_error_transaction_length}>";
							break;
						}
					else 
						if( empty($val))
						{
							$rv = "<{admin_member_control_account_panel_payments_add_error_domain_name_empty}>";
							break;
						}
						else if( mb_strlen($val) < 6 || mb_strlen($val) > 64 )
						{
							$rv = "<{admin_member_control_account_panel_payments_add_error_domain_name_length}>";
							break;
						}
                }
                if( $key == 'price' )
                {
                    if( floatval($val) < 0 )
                    {
                        $rv = "<{admin_member_control_account_panel_payments_add_error_price_empty}>";
                        break;
                    }
                }
            }
            return $rv;
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
            $p = "<{admin_member_control_member_list_const_free_price}>";
        }
        return $p;
    }
	
		
	/**
    * Change users statuses
    *
    * @author Zhalybin
    */
	function change_users_status()
	{
		$mbr_list = array();
		$mbr_list = $this->input->post('mbrlist');
		
        $suspend_reason = $this->input->post('sreason_id') ? $this->input->post('sreason_id') : '0';          
		$mbr_list = explode('!',$mbr_list);
		$num = count($mbr_list);
		if( empty($mbr_list[$num-1]) && $num==2 )
		{
			unset($mbr_list[$num-1]);
		}
		if(!is_array($mbr_list) || count($mbr_list) < 1)
		{
			$res = '<{admin_member_control_approve_suspend_error_id_wrong}>';
			simple_admin_log('member_approve',(is_array($mbr_list) ? implode(",",$mbr_list) : $mbr_list), true, "invalid_id");
			make_response("error", $res, 1);
			return false;
		}
        
         //***********Functionality limitations***********
            $functionality_enabled_error=Functionality_enabled('admin_member_info_modify', $mbr_list[0]);
            if($functionality_enabled_error!==true)
            {                   
                make_response("error",$functionality_enabled_error, true);
                return false;
            }
            //*******End of functionality limitations********

		$do_status = $this->input->post('do_by_status') ? $this->input->post('do_by_status') : $this->input->post('type');
		$this->member_model->change_users_status($do_status, $mbr_list, $suspend_reason);
		return true;
		
	}

}

?>
