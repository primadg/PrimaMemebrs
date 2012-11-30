<?php
/**
* 
* THIS FILE CONTAINS Admin CLASS
* 
* @package Prima DG
* @author uknown
* @version uknown
*/
/**
* Include file admin_controller.php
*/
require_once("admin_controller.php");
/**
* 
* THIS CLASS IS CONTAIN BASE ADMIN ACTIONS
* 
* @package Prima DG
* @author uknown
* @version uknown
*/
class Admin extends Controller
{
    //!!!DELETE IN RELEASE - FOR TESTING PUPROSES ONLY
    /**
    * SELECT ALL ADMINISTRATORS AND CREATE A STRING LIKE  {ID:LOGIN,ID:LOGIN...}
    *
    */
    function selector()
    {
        $post=prepare_post();
        $this->db->select('id,login');
        $this->db->from(db_prefix.'Admins');
        $this->db->like('login',$post['filter'],'after'); 
        $query=$this->db->get();
        $arr=array_transform($query->result_array(),'id','login');
        echo create_temp_vars_set($arr);
    }
    
    function post()
    {
    echo "ddddddddddd";
    print_ex($_POST);
    }
    
    /**
    * Pseudonim debug_console
    *
    */
    function test()
    {
        $this->debug_console();
    }
    
    /**
    * Enter description here...
    *
    */
    function debug_console()
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            php_test();
        }
    }
    
    
    function debug_config()
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            // echo "<pre>";
            // print_r($_POST);
            // echo "</pre>";
            //exit;
            global $_helper_CONFIG;
            $data=$this->_debug_config_array($_helper_CONFIG);            
            $post=prepare_post();
            
            if(count($post))
            {
                $res=array();
                foreach($data as $key=>$value)
                {
                    $k=implode("::",explode("->",$key));
                    if(isset($post[$k]) && ($post[$k]!=$value || count(explode("->",$key))>3))
                    {
                        //echo $key."<br/>"; 
                        $p=explode("::",$k);
                        $val=$post[$k];
                        $count=count($p);
                        switch($count)
                        {
                        case 0:
                            break;
                        case 1:
                            config_set($val,$p[0]);
                            break;
                        case 2:
                            config_set($val,$p[0],$p[1]);
                            break;
                        case 3:
                            config_set($val,$p[0],$p[1],$p[2]);
                            break;
                        default:
                            if($count>3)
                            {
                                for($i=$count;$i>3;$i--)
                                {                    
                                    $val=array($p[$count-1]=>$val);
                                }
                                $res[$p[0]."::".$p[1]."::".$p[2]]=isset($res[$p[0]."::".$p[1]."::".$p[2]]) ? array_merge_recursive($res[$p[0]."::".$p[1]."::".$p[2]],$val) : $val;
                            }                            
                            break;
                        }
                    }
                }
                foreach($res as $k=>$v)
                {
                    $p=explode("::",$k);
                    config_set($v,$p[0],$p[1],$p[2]);
                }
                
            $data=$this->_debug_config_array($_helper_CONFIG);            
            }
            
            ksort($data);                        
            //echo create_temp_vars_set($data);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            
            $text="<div align='center'><form method='POST' action='".base_url()."admin/debug_config/'><div style='width:920px;height:400px;overflow:auto;'><table style='width:900px' class='tab'>";
            $i=0;
            foreach($data as $key=>$value)
            {
                $text.="<tr class='".((($i%2)==0)?"dark":"light")."'>";
                $text.="<td align='left'>".$key."</td>";
                //$text.="<td style='width:410px;'><textarea style='padding:0px;width:400px; height:".(mb_strlen($value)>50?60:20)."px;' name='"."a".$i."'>"."b".$i."</textarea></td>";
                $text.="<td style='width:410px;'><textarea style='padding:0px;width:400px; height:".(mb_strlen($value)>50?60:20)."px;' name='".implode("::",explode("->",$key))."'>".$value."</textarea></td>";
                $text.="</tr>";
                $i++;
            }
            $text.="</table></div><br/><input class='button_save_as_template' type='submit'/></form></div>";
            
            $data['title']='Simple config editor';
            $data['heading']='Simple config editor';
            $data['message']=$text;
            $data = $this->load->view("/admin/message.php", $data);
        }  
    }
    
    function _debug_config_array($a,$name='')
    {
        $result=array();
        foreach($a as $k=>$v)
        {
            if(is_array($v))
            {
                $result=array_merge($result,$this->_debug_config_array($v,$name.$k."->"));    
            }
            else
            {
                $result[$name.$k]=$v;    
            }            
        }
        return $result;
    }
    
    function License()
    {       
        if(!$this->admin_auth_model->is_auth())
        {
            $result['login']='true';
            make_response("service", create_temp_vars_set($result,true), 1);
            exit;
        }
        if($this->admin_auth_model->is_auth)
        {
            $post=prepare_post();            
            if(isset($post['action']) && $post['action']=='license')
            {  
                $return_array = $this->admin_auth_model->license_check($post['key'],true);      
                if($return_array['status'] =='BAD'){  
                   if(!isset($return_array['text']))$return_array['text'] = 'Unavailable connect to the server!';
                    switch($return_array['text'])
                    {
                    case 'Unknown license key':
                        $error = 'unknown_license_key';
                        break;
                    case 'Invalid product':
                        $error = 'invalid_product';
                        break;
                    case 'This license is disabled':
                        $error = 'license_is_disabled';
                        break;
                    case 'Domain check failed':
                        $error = 'domain_check_failed';
                        break;
                    case 'IP check failed':
                        $error = 'ip_check_failed';
                        break;
                    case 'Unrecognized error':
                        $error = 'unrecognized_error';
                        break;
                    default:
                        $error = 'connect_to_the_server';
                        break;
                    }      

              //      $this->admin_auth_model->license();                    
                    echo create_temp_vars_set(array('result'=>'error', 'message'=>$error),true);
                    
                    exit;
                    
                }
                
            } 
            
        }
    }
    
    //Send message to developers
    /**
    * Send message to developers
    *
    */
    function Dev()
    {
        if(!$this->admin_auth_model->is_auth())
        {
            $result['login']='true';
            make_response("authorize", create_temp_vars_set($result), 1);
            exit;
        }
        if($this->admin_auth_model->is_auth && Functionality_enabled('admin_developers_notification')===true)
        {
            $post=prepare_post();
            fb($post,'post');
            
            if(isset($post['action']) && $post['action']=='error')
            {
                $developer_email=config_get('SYSTEM','CONFIG','DEVELOPER_EMAIL');
                $developer_email=(eregi("^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9][a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$", $developer_email)!==false) ? $developer_email : (defined("DEVELOPER_EMAIL") ? DEVELOPER_EMAIL : "onagr@conkurent.com");
                fb($developer_email,"developer_email");
                
                $new_name=false;
                $body="";
                if(isset($_FILES['userfile']))
                {
                    $new_name=dirname($_FILES['userfile']['tmp_name'])."/".$_FILES['userfile']['name'];
                    if(!copy($_FILES['userfile']['tmp_name'],$new_name))
                    {
                        $new_name=$_FILES['userfile']['tmp_name'];
                    } 
                }
                if(isset($post['description']))
                {
                    $body.="<h2>User comment</h2>";
                    $body.=$post['description'];
                    $undefined_lang_vars=$this->lang_manager_model->get_undefined_lang_vars();
                }
                if(count($undefined_lang_vars))
                {
                    $body.="<h2>Undefined english language variables</h2>";
                    $body.=array_to_html_list($undefined_lang_vars);
                }
                $body.="<h2>Prima Membership version</h2>";
                $body.=defined("NEEDSECURE_VERSION") ? NEEDSECURE_VERSION . (defined("NEEDSECURE_SUBVERSION") ? NEEDSECURE_SUBVERSION : "") : "Unregistered!";
                if(isset($post['browser_info']) && !empty($post['browser_info']))
                {
                    $body.="<h2>Client screen resolution</h2>";
                    $body.="<pre>".$post['browser_info']."</pre><br/>";
                }
                if(!isset($post['server_info']) || $post['server_info']=='true')
                {
                    $body.="<h2>MySQL Server version</h2>";
                    $body.="<pre>".$this->db->version()."</pre><br/>";
                    ob_start();
                    phpinfo(-1);
                    $info=ob_get_clean();
                    $body.=$info;
                }
                fb($post,'post');
                
                if($this->mail_model->send_email_ex($post['email'], $developer_email, $post['subject'], $body,$new_name))
                {
                    $result=array('result'=>'message','message'=>'sended_ok');
                }
                else
                {
                    $result=array('result'=>'error','message'=>'not_sended');
                }
                echo create_temp_vars_set($result);
                return;
            } 

            //$this->output->enable_profiler(TRUE);
            $data=array();
            $details=$this->admin_auth_model->system_info();
            $this->load->model("admin_control_model");
            $data=$this->admin_control_model->admin_edit($this->admin_auth_model->uid); 
            
            $data['details']=(Functionality_enabled('admin_server_info')===true) ? array_to_html_list($details['system_info']) : '<{demo_server_info_disabled}>';
            $data['messages']=array(
            'sended_ok'=>"<{admin_developer_dialog_msg_sended_ok}>"
            );
            $data['errors']=array(
            'email'=>"<{admin_developer_dialog_msg_er_email}>",
            'not_sended'=>"<{admin_developer_dialog_msg_er_not_sended}>"
            );
            
            $data['title']="<{admin_developer_dialog_title}>";
            //$data['text']="<{admin_developer_dialog_text}>";
            
            if(isset($post['action']) && $post['action']=='installed')
            {
                $data['title']="<{admin_developer_dialog_installed_title}>";
                $data['subject']="<{admin_developer_dialog_installed_subject}>";
            }
            
            
            $data['text']=$this->load->view("/admin/dev_dlg_error.php", $data, true);;
            
            $data = $this->load->view("/admin/dev_dialog.php", $data, true);
            make_response("output", $data, 1);  
        }
    }
    
    /**
    * Enter description here...
    *
    */
    function translate()
    {
        $data['title']='Simple language editor';
        $data['heading']='Simple language editor';
        $data['message']='<div id="lang_container"></div>';      
        
        $data = $this->load->view("/admin/message.php", $data);    
    }
    
    /**
    * Enter description here...
    *
    */
    function language_simple_translate()
    {
        $this->load->model("auth_model");
        $this->load->model("config_model");
        $this->load->model("lang_manager_model");
        
        if( intval($this->auth_model->get_cookie_lang_id())>0 )
        {
            $sess_lang_id = $this->auth_model->get_cookie_lang_id();
        }
        else
        {
            if(isset($this->lang_manager_model))
            {
                $sess_lang_id = $this->lang_manager_model->get_current_language();
            }
            else
            {
                $sess_lang_id = $this->auth_model->get_default_language();
            }
        }
        $lang_id = intval($sess_lang_id);
        $query=$this->db->get_where(db_prefix.'Languages',array('id'=>$lang_id));
        $res=$query->result_array();
        $lang_name=count($res)>0 ? $res[0]['name'] : 'Undefined';
        
        $post=prepare_post();
        
        if($lang_id>0 && count($post)>0)
        {
            $res_str="";
            $i=0;
            $n=0;
            $patch=array();
            $valid_patch_keys=array();
            foreach($post as $key=>$value)
            {
                if(!strstr($key,"section:"))
                {
                    if(!empty($value))
                    {
                        if($this->config_model->language_set($key,$value,$lang_id,(isset($post["section:".$key])?$post["section:".$key]:false)))
                        {
                            $valid_patch_keys[]=$key;
                            $patch[]="REPLACE INTO `db_prefix_Interface_language` (`key_name`, `language_id`, `content`, `section`, `_last_used`) VALUES ('".$key."',".intval($lang_id).",'".addcslashes($value,"'\\")."','".(isset($post["section:".$key])?$post["section:".$key]:"undef")."','".date("Y-m-d h:i:s")."');";
                            $res_str.="<tr class='".((($i%2)==0)?"dark":"light")."'><td>".$key."</td><td><span style='color:blue;'>updated</span></td></tr>";
                            $n++;
                        }
                        else
                        {
                            $res_str.="<tr class='".((($i%2)==0)?"dark":"light")."'><td>".$key."</td><td><span style='color:red;'>not_updated</span></td></tr>";
                        }
                    }
                    else
                    {
                        $res_str.="<tr class='".((($i%2)==0)?"dark":"light")."'><td>".$key."</td><td><span style='color:red;'>empty</span></td></tr>";
                    }
                    $i++;
                }                
            }
            
            if(count($patch))
            {
                $this->admin_auth_model->main_page_info();
                $patch_str="\n\n# ".date("Y-m-d h:i:s")."  (by ".$this->admin_auth_model->username.")"."\n".implode("\n",$patch);
                foreach($valid_patch_keys as $k=>$v)
                {
                    $valid_patch_keys[$k]="/([#]*)(REPLACE INTO `db_prefix_Interface_language` \(`key_name`, `language_id`, `content`, `section`, `_last_used`\) VALUES \('".$v."',".intval($lang_id).")/";
                }
                
                $f="dump_patch_".(1+floor($this->updater_model->Normalize_version(NEEDSECURE_VERSION))).".sql";
                $warning=array();
                $dump_files=array();
                $dump_files[]=absolute_path()."_protect/".$f;
                $dump_files[]=absolute_path()."patch/".$f;
                foreach($dump_files as $dump_file)
                {
                    if(!@file_put_contents($dump_file,preg_replace($valid_patch_keys,"#$2",@file_get_contents($dump_file)).$patch_str))
                    {
                        $warning[]="File ".$dump_file." is not writable!";
                    }
                }
                //write_file(absolute_path()."_protect/".$f, $patch_str,'a+');
            }
            
            $data=array();
            $data['message'] = "<div id='lang_container'><div align='center'>".(isset($warning)&&count($warning) ? "<div style='color:red;'>".implode("<br/>",$warning)."</div>" : "")."Updated ".$n." variables from ".$i." (".$lang_name." /".$lang_id."/)<div style='width:920px;height:400px;overflow:auto;'><table style='width:900px' class='tab'>".$res_str."</table></div></div><br/></div>";
            $data['title']='Simple language editor';
            $data['heading']='Simple language editor';
            $data = $this->load->view("/admin/message.php", $data);
        }
    }
    
    
    /**
    * Enter description here...
    *
    */
    function debug()
    {
        if($this->admin_auth_model->is_auth && defined('NS_DEBUG_VERSION'))
        {
            $post=prepare_post();
            if(count($post)==0)
            {
                $return=set_debug_params(0);
            }
            else
            {
                $return=set_debug_params($post);
            }
            make_response("message",'dddddddd',1);
        }
    }
    
    function patch()
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            $_SESSION['db_error']=true;
            redirect('admin');
        }
    }
    
    function profiles($file='')
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            $text="";
            $data=array();
            $data['title']='Profiles';
            $data['heading']='Profiles';
            if($file!='')
            {
                if(file_exists(absolute_path()."system/application/config/".$file))
                {
                    copy (absolute_path()."system/application/config/".$file,absolute_path()."system/application/config/.ht_sys_config.cfg");
                    load_config();
                    config_set($file,'SYSTEM','CONFIG','CURRENT_CONFIG');
                    $text.= "File ".$file." set as config.<br/>";
                }
                else
                {
                    $text.= "File ".$file." not exists.<br/>";
                }
                $text.="<br/>";
            }            
            $files=scandir(absolute_path()."system/application/config");
            fb($files);
            $files = preg_grep("/^.+\.cfg$/", $files);
            $current=config_get('SYSTEM','CONFIG','CURRENT_CONFIG');
            foreach($files as $file){
                $text.= ($file==$current?"[ ":"")."<a ".($file==$current?"style='color:orange;'":"")." href='".base_url()."admin/profiles/".$file."'>".$file."</a>".($file==$current?" ]":"")."<br/>";
            }
            fb($files);
            $data['message']=$text."<br/>";
            $data = $this->load->view("/admin/message.php", $data);
            
        }
    }
    
    function restore($file='')
    {
        if(defined('NS_DEBUG_VERSION'))
        {
            $text="";
            $data=array();
            $data['title']='Database restore';
            $data['heading']='Database restore';
                
            if($file!='')
            {            
                $query = $this->db->get_where(db_prefix.'Admins', array('id' => 1));
                $super_admin=$query->result_array();
                $text.= date("\nY-m-d H:i:s ")."Restore begin.<br/>";
                if($file=='dump.sql')
                {
                    $text.= ns_restore();
                }
                else
                {
                    $text.= ns_restore(absolute_path()."_protect/".$file);
                }
                if(count($super_admin))
                {
                    $this->db->query("REPLACE INTO ".db_prefix."Admins (".implode(",",array_keys($super_admin[0])).") VALUES ('".implode("','",$super_admin[0])."');");
                    $text.= date("\nY-m-d H:i:s ")."Superadministrator <span style='color:red'>".$super_admin[0]['login']."</span> saved!<br/>";
                }
                else
                {
                    $text.= date("\nY-m-d H:i:s ")."<span style='color:red'>Superadministrator not saved! Try login: 'super_admin' password: 'super_admin'!</span><br/>";
                }
                $text.= date("\nY-m-d H:i:s ")."Restore end.<br/>";                                
            }
            else
            {
            ob_start();
            $text.= $this->backup();
            @ob_end_clean();
            $files=scandir(absolute_path()."_protect/");
            $files = preg_grep("/^[\d]+\.sql$/", $files);
            $text.= "<a href='".base_url()."admin/restore/dump.sql'>Default</a><br/>";
            foreach($files as $file){
                $text.= "<a href='".base_url()."admin/restore/".$file."'>".$file."</a><br/>";
            }
            fb($files);
            }
            $data['message']=$text."<br/>";
            $data = $this->load->view("/admin/message.php", $data);
                
        }
    }
    
    function backup()
    {
        if($this->admin_auth_model->is_auth && defined('NS_DEBUG_VERSION'))
        {
            $text="";
            $text.= "Backup database begin!<br/>";
            $tables = $this->db->list_tables();
            $tables = preg_grep("/^(".db_prefix.")+[\w]+$/", $tables);
            sort($tables);
            // echo "<pre>";
            // print_r($tables);
            // echo "</pre>";
            // Load the DB utility class
            $this->load->dbutil();
            $prefs = array(
                'tables'      => $tables,  // Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                //'filename'    => 'mybackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );
            $backup=$this->dbutil->backup($prefs); 
            //echo $backup;
            $res_tables=array();
            foreach($tables as $key=>$value)
            {
                $res_tables[$key]=preg_replace("/^(".db_prefix.")+/","db_prefix_",$value);
                $tables[$key]="/".$value."/";
            }
            // echo "<pre>";
            // print_r($res_tables);
            // echo "</pre>";
            
            $backup=preg_replace($tables,$res_tables,$backup);
            $backup=preg_replace("/(INSERT INTO [a-z0-9_]+ )(\(.*\))/iU","$1",$backup);
            
            
            $backup = stripslashes($backup);
            $file=absolute_path().'_protect/'.date("YmdHis").'.sql';
            write_file($file, $backup); 
            $text.= "Backup database to file (".$file.") ended!<br/>";
            //$backup=nl2br($backup);
            //echo $backup;
            $data=array();
            $data['title']='Database backup';
            $data['heading']='Database backup';
            $data['message']=$text."<br/>";
            $data = $this->load->view("/admin/message.php", $data);
            return $text;
        }
    }
    //!!!DELETE IN RELEASE
    /**
    * LOAD VIEW FILE - /admin/message.php
    *
    */
    function Upgrade()
    {
        $data=array();
        $data['title']="<{admin_upgrade_denied_title}>";
        $data['heading']="<{admin_upgrade_denied_heading}>";
        $data['message']="<{admin_upgrade_denied_message}>";      
        $this->load->view("/admin/message.php", $data);        
    }
    /**
    * THIS METHOD SETS INITIAL VARS (constructor)
    */
    function Admin()
    {
        parent::Controller();
        pre_config();
        $this->load->model("admin_auth_model");
        $this->load->model("mail_model");
        $this->load->model("lang_manager_model");
     //   if (true) {$this->license(); exit;}
    }
    /**
    * MAIN PAGE OF ADMIN PART.
    *
    */
    function index()
    {   
             
        //if language change
        if(isset($_POST['language']) && Functionality_enabled('admin_admin_account_modify', intval($this->admin_auth_model->admin_id))===true)
        {
            $this->lang_manager_model->set_current_language($_POST['language']);
            // this is modified by Makarenko Sergey @ 05.11.08 16:20
            $this->load->model('auth_model');
            $this->auth_model->set_cookie_lang_id(intval($_POST['language']));
            $_COOKIE['lang_id'] = intval($_POST['language']);
            admin_log("current_language_modify", array("Enity_id"=>$_POST['language'],"Completed"=>"true"),$this->admin_auth_model->admin_id);
        }

        if(isset($_POST['action'])&&$_POST['action']=='logout')
        {
            admin_log("logout",null,$this->admin_auth_model->admin_id);
            //support admin logout
            unset($_SESSION["NS_SUPPORT_ADMIN"]); 
            $this->admin_auth_model->logout();
        }

        //if view login page
        if(isset($_POST['is_page']))
        {
            $this->login();
            return;
        }
        //if not authorized
        if(!$this->admin_auth_model->is_auth)
        {
            //if viev home page not authorized
            if(isset($_POST['action'])&&$_POST['action']=='home')
            {
                $result['login']='true';
                make_response("authorize", create_temp_vars_set($result), 1);
                exit;
            }
            else
            {
                //if viev login page
                $this->login();
            }
        }
        else
        {
            //if viev home page authorized
            if(isset($_POST['action'])&&$_POST['action']=='home')
            {            
       $this->admin_auth_model->license_check();    

                $this->load_main_page(true);
            }
            else
            {
                //if viev main page
                $this->load_main_page();
            }
        }
    }
    
    function Logout()
    {
        $_POST['action']='logout';
        $this->index();
    }
    /**
    * Load main or home page authorized
    *
    * @param boolean $is_frame   what page must be loaded
    */
    function Load_main_page($is_frame=false)
    {
        $data=$this->admin_auth_model->main_page_info();
        $data['languages']=$this->lang_manager_model->get_languages();
        $data['current_language']=$this->lang_manager_model->get_current_language();
        $data['content'] = $this->load->view("/admin/main.php", $data, true);
        if($is_frame)
        {
            make_response("output", $data['content'], 1);
        }
        else
        {
            $data['site_name'] = config_get('system','config','site_name');
            $post=prepare_post();
            if(isset($post['location']) && !empty($post['location']))
            {
                $data['location']=$post['location'];
            }
            $array_config = config_get('system','config');
            if (isset($array_config['demo_data'])){
            if($array_config['demo_data']==0){ 
                //удаляяем демо польз-лей и админов
                 $this->load->model("member_model");                 
                 $this->member_model->delete(2);
                 $this->load->model("admin_control_model");
                 $this->admin_control_model->admin_delete(2);
                 $this->admin_control_model->admin_delete(3);                
                }
            unset($array_config['demo_data']);
            config_set($array_config, 'system','config');                
            }
            if(isset($_SESSION['step']) && intval($_SESSION['step'])==5)
            {
                $data['location']="installed";
                unset($_SESSION['step']);
            }            
            $this->load->view("/admin/main_page.php", $data);
        }
    }
    /**
    * Show login page for admin. Check is this admin enable adn its ip not blocked. Login admin adn remind a password.
    *
    * @param string $code  Enter description here
    */
    function Login($code="")
    {
        $post=prepare_post();
        $result=array();
        $result['site_name'] = config_get('system','config','site_name');  
        $ip=$this->input->ip_address();
        $ip_blocked=false;
        $ip_banned=false;
        $ip_banned=$this->admin_auth_model->is_ip_banned($ip);
        if($ip_banned===false)
        {
            $ip_blocked=$this->admin_auth_model->is_ip_blocked($ip);
        }
        if($ip_banned===false && $ip_blocked===false)
        {
            $result['show_capcha']=config_get('user','security','login_try_capcha') <= $this->admin_auth_model->tries_count($ip);
            
            //$result['show_capcha']=true;
            
            if(isset($post['action'])&&($post['action']=='login'||$post['action']=='remind'))
            {
                $result['action']=$post['action'];
                $result['login']='false';
                $result['ip']=$ip;
                //if remind
                if($post['action']=='remind')
                {
                    $temp=$post;
                    $temp['name']='login';
                    $temp['value']=$post['lf_login'];
                    $this->load->model("admin_control_model");
                    $res=$this->admin_control_model->value_exist($temp);
                    //if login is exist
                    if($res['is_error'])
                    {
                        $this->db->select('admins.id');
                        $this->db->from(db_prefix.'Admins admins');
                        $this->db->where('admins.login',$temp['value']);
                        $query = $this->db->get();
                        $admins_list=$query->result_array();
                        if(count($admins_list)>0)
                        {
                            //$CI = &get_instance();
                            $email_keys=array();
                            $email_keys['admin_remind_link']=config_get('system','config','base_url')."admin/login/".$this->admin_auth_model->get_remind_code($admins_list[0]['id']);
                            
                            $res=send_system_email_to_admin($admins_list[0]['id'],'your_admin_remind_password',array('admin_remind_password_link'=>$email_keys['admin_remind_link']));
                            if($res)
                            {
                                $result['error']="lf_mail_sended";
                                $log_comleted=true;
                            }
                            else
                            {
                                $result['error']="lf_mail_not_sended";
                                $log_comleted="Mail is not sended!";
                            }
                        }
                    }
                    else
                    {
                        $result['error']="lf_not_exist";
                        $log_comleted="Login is not exist!";
                    }
                    
                    $details=array("Enity_id"=>$this->admin_auth_model->admin_id);
                    if($log_comleted===true)
                    {
                        $details["Completed"]="true";
                    }
                    else
                    {
                        $details["Completed"]="false";
                        $details["Exception"]=$log_comleted;
                    }
                    admin_log("remind_password", $details,$this->admin_auth_model->admin_id);
                }
                //if login
                if($post['action']=='login')
                {
                    if(isset($result['show_capcha']) && intval($result['show_capcha'])>0)
                    {
                        $capcha_result=check_code(isset($post['lf_capcha_code'])?$post['lf_capcha_code']:"");
                    }
                    else
                    {
                        $capcha_result=true;
                    }
                    
                    if($capcha_result)
                    {
                        $info=$this->admin_auth_model->login($post['lf_login'],$post['lf_pwd'], $this->input->ip_address());
                        if($info!=false)
                        {
                            $this->admin_auth_model->auth($info['login'], $info['pwd'], $info['id'], false,  $this->input->ip_address());
                            if($this->admin_auth_model->is_auth)
                            {
                                $this->admin_auth_model->clear_access_log_by_ip($ip);
                                $this->admin_auth_model->admin_id = $info['id'];
                                //restore lang_id in COOKIE to variable from DataBase for this admin_ID
                                $lang_id = $this->lang_manager_model->get_current_language();
                                $this->load->model('auth_model');
                                $this->auth_model->set_cookie_lang_id($lang_id);
                                // hehe, dummy code, I know =) (author: Makarenko Sergey)
                                $_COOKIE['lang_id'] = $lang_id;
                                //_restore lang_id in COOKIE to variable from DataBase for this admin_ID

                                $result['login']='true';
                                $result['username']=$this->admin_auth_model->username;
                                $result['last_login']=nsdate($this->admin_auth_model->last_online);
                                $log_comleted=true;
                            }
                            else
                            {
                                $this->admin_auth_model->try_block_ip($ip);
                                $result['error']="lf_login_pwd_error";
                                $log_comleted="invalid_login_pwd";
                            }
                        }
                        else
                        {
                            $this->admin_auth_model->try_block_ip($ip);
                            $result['error']="lf_login_pwd_error";
                            $log_comleted="invalid_login_pwd";
                        }
                    }
                    else
                    {
                        $this->admin_auth_model->failed_login_try($ip);
                        $this->admin_auth_model->try_block_ip($ip);
                        $result['error']="lf_capcha_code";
                        $log_comleted="invalid_image_code";
                    }
                    $details=array("Enity_id"=>$this->admin_auth_model->admin_id);
                    if($log_comleted===true)
                    {
                        $details["Completed"]="true";
                    }
                    else
                    {
                        $details["Completed"]="false";
                        $details["Exception"]=$log_comleted;
                    }
                    admin_log("login", $details,$this->admin_auth_model->admin_id);    
                }

                if(isset($post['is_page']))
                {
                    if($result['login']=='true')
                    {
                        $this->load_main_page();
                    }
                    else
                    {
                        if(isset($post['location']) && !empty($post['location']))
                        {
                            $result['location']=$post['location'];
                        }
                        $this->load->view("/admin/login.php",$this->add_panel_vars_ex($result));
                    }
                }
                else
                {
                    make_response("output", create_temp_vars_set($result), 1);
                }
            }
            else
            {
                if(isset($code)&&$code!="")
                {
                    //send new password to email
                    $this->load->model("user_model");
                    $new_pwd=$this->user_model->generate_password();
                    $id=$this->admin_auth_model->compare_remind_code($code,$new_pwd);
                    if($id!==false)
                    {
                        $res=false;
                        $admin_data=$this->admin_auth_model->get_admin_info($id);
                        if($admin_data!==false)
                        {
                            $admin_data['admin_password']=$new_pwd;
                            $res=send_system_email_to_admin($admin_data['id'],'your_admin_account_changed',array('admin_password'=>$admin_data['admin_password']));
                            send_system_subscription_to_admins('admin_account_changed',array(
                            'changed_admin_login'=>$admin_data['login'],
                            'changed_admin_level'=>$admin_data['access_level']
                            ),$admin_data['id']);                            
                        }
                        if($res)
                        {
                            $result['error']="lf_pwd_new_sended";
                            $log_comleted=true;
                        }
                        else
                        {
                            $result['error']="lf_mail_not_sended";
                            $log_comleted="Mail not sended!";
                        }
                    }
                    else
                    {
                        $result['error']="lf_remind_code_error";
                        $log_comleted="Remind code error!";
                    }
                    $this->load->view("/admin/login.php",$this->add_panel_vars_ex($result));
                    $details=array("Enity_id"=>$this->admin_auth_model->admin_id);
                    if($log_comleted===true)
                    {
                        $details["Completed"]="true";
                    }
                    else
                    {
                        $details["Completed"]="false";
                        $details["Exception"]=$log_comleted;
                    }
                    admin_log("remind_code", $details,$this->admin_auth_model->admin_id);   
                    
                }
                else
                {
                    if(!isset($result['error']))
                    {
                        $result['error']='lf_javascript_disabled';
                    }
                    $this->load->view("/admin/login.php",$this->add_panel_vars_ex($result));
                }
            }
        }
        else
        {
            if($ip_blocked!==false)
            {
                $this->admin_auth_model->failed_login_try($ip);
                $ip_blocked=$this->admin_auth_model->try_block_ip($ip);
            }
            
            if(isset($post['action'])&&($post['action']=='login'||$post['action']=='remind') && !isset($post['is_page']))    
            {
                $result['action']='login';
                $result['login'] = 'false';
                
                if(isset($ip_blocked) && $ip_blocked!==false)
                {
                    $result['block_period']=$ip_blocked;
                }
                if(isset($ip_banned) && $ip_banned!==false)
                {
                    $result['ban_reason']=$ip_banned;
                }
                $result['error']=(isset($ip_banned) && $ip_banned!==false) ? 'lf_ip_banned' : 'lf_ip_blocked';
                make_response("output", create_temp_vars_set($result), 1);  
            }
            else
            {
                $result['error_ip_banned']=$ip_banned;
                $result['error_ip_blocked']=$ip_blocked;
                
                $details=array("Enity_id"=>$this->admin_auth_model->admin_id);
                $details["Completed"]="false";
                $details["Exception"]=$ip_banned===false ? 'ip_blocked' : 'ip_banned';
                admin_log("login", $details,$this->admin_auth_model->admin_id);
                $this->load->view("/admin/login.php",$this->add_panel_vars_ex($result));
            }
        }
    }
    /**
    * Enter description here...
    *
    * @param array $result  Enter description here...
    * @return array
    */
    function add_panel_vars_ex($result=array())
    {
        if(!isset($result['error']))
        {
            $result['error']="";
        }
        $data=array();
        $messages=array();
        $messages['lf_mail_sended']=array('display'=>($result['error']=='lf_mail_sended'),'text'=>'<{admin_login_form_msg_mail_sended}>');
        $messages['lf_pwd_new_sended']=array('display'=>($result['error']=='lf_pwd_new_sended'),'text'=>'<{admin_login_form_msg_pwd_new_sended}>');
        $mess_err['lf_mail_not_sended']=array('display'=>($result['error']=='lf_mail_not_sended'),'text'=>'<{admin_login_form_msg_er_mail_not_sended}>');
        $mess_err['lf_remind_code_error']=array('display'=>($result['error']=='lf_remind_code_error'),'text'=>'<{admin_login_form_msg_er_remind_code_error}>');
        $mess_err['lf_login_pwd_error']=array('display'=>($result['error']=='lf_login_pwd_error'),'text'=>'<{admin_login_form_msg_er_login}>');
        $mess_err['lf_not_exist']=array('display'=>($result['error']=='lf_not_exist'),'text'=>'<{admin_login_form_msg_er_not_exist}>');
        $mess_err['lf_login']=array('display'=>($result['error']=='lf_login'),'text'=>'<{admin_login_form_msg_er_username}>');
        $mess_err['lf_pwd']=array('display'=>($result['error']=='lf_pwd'),'text'=>'<{admin_login_form_msg_er_password}>');
        $mess_err['lf_capcha_code']=array('display'=>($result['error']=='lf_capcha_code'),'text'=>'<{admin_login_form_msg_er_capcha_code}>');
        $mess_err['lf_javascript_disabled']=array('display'=>($result['error']=='lf_javascript_disabled'),'text'=>'<{admin_login_form_msg_er_javascript_disabled}>');
        $mess_err['lf_cookies_disabled']=array('display'=>($result['error']=='lf_cookies_disabled'),'text'=>'<{admin_login_form_msg_er_cookies_disabled}>');
        

        if(isset($result['error_ip_banned']) && $result['error_ip_banned']!==false)
        {
            $mess_err['lf_ip_banned']=array('display'=>true,'text'=>'<{admin_login_form_msg_er_ip_banned}>'.$result['error_ip_banned']);
        }
        
        if(isset($result['error_ip_blocked']) && $result['error_ip_blocked']!==false)
        {
            $mess_err['lf_ip_blocked']=array('display'=>true,'text'=>'<{admin_login_form_msg_er_ip_blocked}>'.$result['error_ip_blocked']);
        }
        
        $data['messages']=$messages;
        $data['mess_err']=$mess_err;
        if(isset($result['location']))
        {
            $data['location']=$result['location'];
        }
        
        if(isset($result['show_capcha']))
        {
            $data['show_capcha']=$result['show_capcha'];
        }
        return $data;
    }

}
?>
