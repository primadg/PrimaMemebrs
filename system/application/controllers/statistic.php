<?php
/**
 * 
 * THIS FILE CONTAINS Statistic CLASS
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
 * This class contains methods for statistic and graphs.
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Statistic extends Admin_Controller
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Statistic()
    {
    	
        $this->access_bit=TRANSACTION;
        parent::Admin_Controller();
    	$this->load->model("Statistic_model");
    }


    /**
     * subscriptions statistic
     *
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Subscriptions_stats()
    {
        $data = Array();

        $post = prepare_post();
        //get all "subscription statistics" to $data array
        $data = $this->Statistic_model->Subscriptions_get($post);
        //get additional language constants for view
        $data = $this->Statistic_model->Subscriptions_stats_vars_add($data);
        //load the view and pass there $data
        $res = $this->load->view("/admin/statistic/subscriptions_stats", $data, true);
        make_response("output", $res, 1);
    }


    /**
     * Total statistics main controller
     *
     * @return void
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Total_stats()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_statistics_total')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $data = Array();
        $post = prepare_post();
        //get all products and product groups statistics to $data array
        $data = $this->Statistic_model->Total_stats_get($post);
        //get additional language constants for view
        $data = $this->Statistic_model->Total_stats_vars_add($data);
        //load the view and pass there $data
        $res = $this->load->view("/admin/statistic/total_stats", $data, true);
        make_response("output", $res, 1);
    }


    /**
     * transactions statistic by some subscription
     *
     * @param integer $id
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Transactions_stats($id)
    {
        $data = Array();

        $post = prepare_post();
        //get all "transactions statistics" about certain subscription into $data array
        $data = $this->Statistic_model->Transactions_get($id, $post);
        //get additional language constants for view
        $data = $this->Statistic_model->Transactions_stats_vars_add($data);

        $data['subscription_id'] = $id;
        //this is used to pass the subscription_id to JS
        $data['temp_vars_set']['subscription_id'] = $id;
        //load the view and pass there $data
        $res = $this->load->view("/admin/statistic/transactions_stats", $data, true);
        make_response("output", $res, 1);
    }


    /**
     * Show detailed info on some transaction
     *
     * @param integer $id
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Transaction_detailed($id)
    {
        $data = Array();

        //get all transaction details into $data array
        $data = $this->Statistic_model->Transaction_detailed_get($id);
        //get additional language constants for view
        $data = $this->Statistic_model->Transaction_detailed_vars_add($data);

        $data['transaction_id'] = $id;
        //this is used to pass the subscription_id to JS for "back" button
        $data['subscription_id_for_feedback'] = $data['transact']['subscr_id'];
        //load the view and pass there $data
        $res = $this->load->view("/admin/statistic/transaction_detailed", $data, true);
        make_response("output", $res, 1);
    }


    /*********************************************************************
    *                   GRAPHS begin
    *********************************************************************/

    /**
    * Loads page with Flash object
    *
    * @author Drovorubov
    */
    function graphs()
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_statistics_graphs')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $data = array();
        //Get Graphs page
		$res=$this->load->view("/admin/statistic/graphs", $data, true);
        make_response("output", $res, 1);
        return;
    }



    /**
    * Loads data to Flash object and displays it
    *
    * @author Drovorubov
    * @param string $period_start
    * @param string $period_end
    */
    function graphs_info($period_start='',$period_end='')
    {
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_statistics_graphs')!==true)
        {   
            return false;
        }
        //*******End of functionality limitations********        
        $period_start = prepare_text($period_start);
        $period_end = prepare_text($period_end);
        $chart_title = replace_lang("<{admin_stats_graphs_chart_title}>");
        $graph_name = replace_lang("<{admin_stats_graphs_graph_title}>");
        $max = 0;
        $data = array();
        //Load Graph library (Documentation on teethgrinder.co.uk/open-flash-chart)
        $this->load->library('graph');
        //Create the graph object:
        $g = new graph();
        //Prepare data array for chart
        if( !empty($period_start) && !empty($period_end) )
        {
            //Convert dates to arrays $start and $end
            list($start['d'],$start['m'],$start['y']) = explode('-',$period_start."--");
            $start = array_map('intval',$start);
            list($end['d'],$end['m'],$end['y']) = explode('-',$period_end."--");
            $end = array_map('intval',$end);
            //Initialize final array for a period
            $graph_data_fin = array();
            $error = '';
            //Validate dates params of a period
            if( $start['y'] < 1970 || ($end['y'] - $start['y']) > 30 )
            {
                $error = "<{admin_stats_graphs_error_period_outofrange}>";
            }
            else if( $start['m']<1 || $start['m']>12 || $start['d']<1 || $start['d']>31 || $end['m']<1 || $end['m']>12 || $end['d']<1 || $end['d']>31 )
            {
                $error = "<{admin_stats_graphs_error_period_invalid}>";
            }
            //Prepare graph data for period
            if( $error == '' )
            {
                //Set collected DB data to a final array
                $this->_set_data4period($graph_data_fin,$start,$end);
                //Distribute elements of final array to vars and objects
                $data = $graph_data_fin['data'];
                $g->set_x_labels($graph_data_fin['x_labels']);
                $max = $graph_data_fin['max_value'];
                $chart_title = $chart_title . " " . replace_lang("<{admin_stats_graphs_label_for_period}>");
            }
        }
        else
        {
            //Initialize empty graph array
            $data = array(0,0,0,0,0,0,0,0,0,0,0,0);
            //Get a current year
            $curr_year = date("Y");
            $chart_title = $chart_title . " " . $curr_year;
            $short_month_names = get_month_names('short');
            //Set labels for axis
            $g->set_x_labels( $short_month_names );
            $stat_data = $this->Statistic_model->graph_info4year($curr_year);
            //Set sum values for data array
            foreach( $stat_data as $item )
            {
                $data[$item['month']-1] = $item['summ'];
                //Set max value of the graph
                if( $item['summ'] > $max )
                {
                    $max = $item['summ'];
                }
            }
        }
        //Check error
        if( $error != '' )
        {
            $chart_title = replace_lang($error);
            $data = array();
        }
        //Set a title of the chart
        $g->title($chart_title,'{font-size: 26px;}');
        //Set Line Attributes
        $g->line_hollow( 2, 4, '0x80a033', $graph_name, 10 );
        //Set data to graph object
        $g->set_data($data);
        //Set axis attributes
        $max = ($max - fmod($max,10)) + 10;
        $g->set_y_max( $max );
        $g->y_label_steps(10);
        $g->set_x_label_style( 10, '#000000', 2 );
        //Set ToolTip
        $currency = config_get("system", "config", "currency_code");
        $g->set_tool_tip( '#x_label#<br>Sum: ' . $currency . ' #val#' );
        //Set thousand separator disabled
        $g->set_is_thousand_separator_disabled( true );
        //Render the object
        echo $g->render();
    }


    /**
    * Prepare an array with graph data for a period
    *
    * @author Drovorubov
    * @param reference to array $graph_data_fin
    * @param array $start (start period date)
    * @param array $end (end period date)
    */
    function _set_data4period(&$graph_data_fin,$start,$end)
    {
        //Set a temporary array
        $graph_data_per = array();
        //Prepare dates strings
        $period_start = $start['y'] ."-". $start['m'] ."-". $start['d'];
        $period_end = $end['y'] ."-". $end['m'] ."-". $end['d'];
        //Check a period interval to select data
        if( ($end['y'] - $start['y']) > 1  )
        {
            $stat_data = $this->Statistic_model->graph_info4period($period_start,$period_end,'year');
            $graph_data_fin = $this->_prepare_graph_data($stat_data,'year',$start['y'],$end['y']);
        }
        else if( ($end['y'] - $start['y']) == 1 )
        {
            //Set vars of compound period
            $first_period_end = $start['y'] . "-12-31";
            $second_period_start = $end['y'] . "-1-1";
            //Set first part of the graph
            $stat_data = $this->Statistic_model->graph_info4period($period_start,$first_period_end,'month');
            $graph_data_per[] = $this->_prepare_graph_data($stat_data,'month',$start['m'],'12');
            //Set second part of the graph
            unset($stat_data);
            $stat_data = $this->Statistic_model->graph_info4period($second_period_start,$period_end,'month');
            $graph_data_per[] = $this->_prepare_graph_data($stat_data,'month','1',$end['m']);
            //Set periods separator
            $this->_add_separator($graph_data_per,$start['y'],$end['y']);
        }
        else if( ($end['y'] - $start['y']) == 0 )
        {
            if( ($end['m'] - $start['m']) > 1 )
            {
                $stat_data = $this->Statistic_model->graph_info4period($period_start,$period_end,'month');
                $graph_data_fin = $this->_prepare_graph_data($stat_data,'month',$start['m'],$end['m']);
            }
            else if( ($end['m'] - $start['m']) == 1 )
            {
                $max_days = date("j",mktime(0,0,0,$start['m']+1,0,$start['y']));
                //Set vars of compound period
                $first_period_end = $start['y'] . "-" . $start['m'] . "-" . $max_days;
                $second_period_start = $end['y'] . "-" . $end['m'] . "-1";
                //Set first part of the graph
                $stat_data = $this->Statistic_model->graph_info4period($period_start,$first_period_end,'day');
                $graph_data_per[] = $this->_prepare_graph_data($stat_data,'day',$start['d'],$max_days);
                //Set second part of the graph
                unset($stat_data);
                $stat_data = $this->Statistic_model->graph_info4period($second_period_start,$period_end,'day');
                $graph_data_per[] = $this->_prepare_graph_data($stat_data,'day','1',$end['d']);
                //Set periods separator
                $short_month_names = get_month_names('short');
                $sep1 = $short_month_names[$start['m']-1] ." ". $start['y'];
                $sep2 = $short_month_names[$end['m']-1] ." ". $end['y'];
                $this->_add_separator($graph_data_per,$sep1,$sep2);
            }
            else if( ($end['m'] - $start['m']) == 0 )
            {
                $stat_data = $this->Statistic_model->graph_info4period($period_start,$period_end,'day');
                $graph_data_fin = $this->_prepare_graph_data($stat_data,'day',$start['d'],$end['d']);
            }
        }
        //Compound data for periods
        if( count($graph_data_per) > 1 )
        {
            $graph_data_fin['x_labels'] = array_merge($graph_data_per[0]['x_labels'],$graph_data_per[1]['x_labels']);
            $graph_data_fin['data'] = array_merge($graph_data_per[0]['data'],$graph_data_per[1]['data']);
            if( $graph_data_per[0]['max_value'] > $graph_data_per[1]['max_value'] )
            {
                $graph_data_fin['max_value'] = $graph_data_per[0]['max_value'];
            }
            else
            {
                $graph_data_fin['max_value'] = $graph_data_per[1]['max_value'];
            }
        }
    }




    /**
    * Adds string to first and last elements of array with key x_labels
    * Array $arr is a reference to array and contains two x_labels arrays
    * Two strings parameters are for two arrays with x_labels key
    *
    * @author Drovorubov
    * @param reference to array &$arr
    * @param string $str1
    * @param string $str2
    */
    function _add_separator(&$arr,$str1,$str2)
    {
        for($i=0; $i<2; $i++)
        {
            $str = ($i == 1) ? $str2 : $str1;
            if( is_array($arr[$i]['x_labels']) && count($arr[$i]['x_labels']) > 0 )
            {
                $arr[$i]['x_labels'][0] = $arr[$i]['x_labels'][0] . " " . $str;
                $last = count($arr[$i]['x_labels']) - 1;
                if( $arr[$i]['x_labels'][$last] != $arr[$i]['x_labels'][0] )
                {
                    $arr[$i]['x_labels'][$last] = $arr[$i]['x_labels'][$last] . " " . $str;
                }
            }
        }
    }



    /**
    * Prepare array with keys:
    * data is array with sum values,
    * x_labels is array with with labels for x axis,
    * max_value is max value of summ
    *
    * @author Drovorubov
    * @param array $stat_data
    * @param string $period (It contains values like day,month,year)
    * @param integer $start
    * @param integer $end
    * @return array  as (array,array,max value)
    */
    function _prepare_graph_data($stat_data,$period,$start,$end)
    {
        $data = array();
        $x_labels = array();
        $max = 0;
        if( $period == 'month' )
        {
            $short_month_names = get_month_names('short');
        }
        //Convert entry param stat data to key->value form
        $stat_data_prep = array();
        if( is_array($stat_data) && count($stat_data) > 0 )
        {
            foreach( $stat_data as $item )
            {
                $stat_data_prep[$item[$period]] = $item['summ'];
            }
        }
        //Prepare arrays
        for($i=$start; $i<=$end; $i++)
        {
            //Set value for graph
            $data[] = (isset($stat_data_prep[$i])) ? $stat_data_prep[$i] : '0';
            //Set value for x axis labels
            if( $period == 'month' )
            {
                $x_labels[] = $short_month_names[$i-1];
            }
            else
            {
                $x_labels[] = $i;
            }
            //Set max y value
            if(isset($stat_data_prep[$i]) && $stat_data_prep[$i] > $max )
            {
                $max = $stat_data_prep[$i];
            }
        }
        $rv = array();
        $rv['x_labels'] = $x_labels;
        $rv['data'] = $data;
        $rv['max_value'] = $max;
        return $rv;
    }


    /*********************************************************************
    *                   GRAPHS end
    *********************************************************************/
}
?>
