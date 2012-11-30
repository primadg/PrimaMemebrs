<?php
/**
 * 
 * THIS FILE CONTAINS File_Protect CLASS
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
 * 
 * THIS CLASS CONTAIN MOTHODS FOR FILE PROTECTION
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class File_Protect extends User_Controller
{
    //just a basic constructor calling his daddy
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function File_Protect()
    {
        parent::User_Controller();
    }
	/**
	 * Enter description here...
	 *
	 */
    function File_Protection()
    {
        if (!defined("NEEDSECURE_PRODUCT_ID") || !preg_match("#\d+#",NEEDSECURE_PRODUCT_ID))
        {
            die("ACCESS DENIED && HACK NOT ALLOWED");
        }

        //checking user auth now
        check_user_auth(NEEDSECURE_FILE_PROTECT_URL); //also loads auth_user_model

        //loading protection_model
        $this->load->model("protection_model");

        //user is logged in, directory is located - checking permissions
        if (!$this->protection_model->Is_Access_To_Product_Allowed(NEEDSECURE_PRODUCT_ID,$this->user_auth_model->uid))
        {
            $this->_show_error("user/access_problem.html",array("title"=>"<{user_access_problem_title_access_denied}>","message"=>"<{user_access_problem_description_access_denied}>"));
        }

        /*
            if we reached this line, it means everything is OK:
            directory has been identified, user is logged in and has access to the directory

            now we need to restore environment and get out of codeigniter for god sake
        */

        //restoring environment after codeigniter
        $this->_restore_environment();
    }


    /**
     * Checks whether current user has access to the directory (php_prepend protection method)
     *
     * Function is called from /protect.php by hacking PATH_INFO
     *
     * Function checks for NEEDSECURE_FILE_PROTECT_URL constant - it must be defined in /protect.php
     *
     * Also function checks for Apache Environment Variable from .htaccess.
     * It's name is defined as PROTECTION_PREPEND_FILE_SETENV
     *
     * For hosts without mod_env function is locating directory by its fs_path.
     * /file.php is passing original cwd here using NEEDSECURE_FILE_PROTECT_DIR constant.
     * This is done because CodeIgniter requires chdir(__FILE__) to work properly.
     * If host doesn't support mod_env and sets wrong cwd, then php_prepend protection method won't work.
     * Simply because auto_prepend_file is unable to locate the directory.
     *
     * @return void
     *      it must die when detecting hacking attempt or wrong directory
     *
     */
    function php_prepend()
    {
        if (!defined("NEEDSECURE_FILE_PROTECT_URL"))
        {
            die("ACCESS DENIED && HACK NOT ALLOWED");
        }
        //loading protection_model
        $this->load->model("protection_model");
        //locating directory
        //sorry, but the next string will not work at Apache<2.0 (Sergey Makarenko)
        //$did = apache_getenv(PROTECTION_PREPEND_FILE_SETENV);
        $did = getenv(PROTECTION_PREPEND_FILE_SETENV);

        $directory = false;
        $dpath = (NEEDSECURE_FILE_PROTECT_DIR) ? $this->protection_model->Standartize_directory_name(NEEDSECURE_FILE_PROTECT_DIR) : "";
        if (preg_match("#\d+#",$did))
        {
            $directory = $this->protection_model->DB_read($did);
        }
        elseif($dpath)
        {
            $directory = $this->protection_model->DB_read_by_path($dpath);
        }
        if (!$directory)
        {//we don't know what is it
            $this->_show_error("user/access_problem.html",array("title"=>"<{user_access_problem_title_unknown_directory}>","message"=>"<{user_access_problem_msg_unknown_directory}>"));
        }

        //checking user auth now
        check_user_auth(NEEDSECURE_FILE_PROTECT_URL); //also loads auth_user_model

        //user is logged in, directory is located - checking permissions
        if (!$this->protection_model->Is_Access_To_Directory_Allowed($this->user_auth_model->uid, $directory['id']))
        {
            $this->_show_error("user/access_problem.html",array("title"=>"<{user_access_problem_title_access_denied}>","message"=>"<{user_access_problem_description_access_denied}>"));
        }

        /*
            if we reached this line, it means everything is OK:
            directory has been identified, user is logged in and has access to the directory

            now we need to restore environment and get out of codeigniter for god sake
        */

        //restoring environment after codeigniter
        $this->_restore_environment();
    }


    /**
     * Checks whether current user has access to the directory (mod_rewrite_standard protection method)
     *
     * @return void
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function mod_rewrite_standard()
    {
        if (!defined("NEEDSECURE_DID") || !defined("NEEDSECURE_FILE_PROTECT_URL"))
        {
            die("ACCESS DENIED && HACK NOT ALLOWED");
        }

        //checking user auth now
        check_user_auth(NEEDSECURE_FILE_PROTECT_URL); //also loads auth_user_model

        //loading protection_model
        $this->load->model("protection_model");

        //user is logged in, we know the directory ID - checking permissions
        if (!$this->protection_model->Is_Access_To_Directory_Allowed($this->user_auth_model->uid, NEEDSECURE_DID))
        {
            //if no permissions to this directory - show the error to user "access denied"
            $this->_show_error("user/access_problem.html",array("title"=>"<{user_access_problem_title_access_denied}>","message"=>"<{user_access_problem_description_access_denied}>"));
        }

        //restoring environment after codeigniter
        $this->_restore_environment();

        //if we reached here, it means the user has all rights to access this directory
        //we should initiate the request from server and return the result to user browser

        //if cURL is available - use it
        if ((bool)function_exists("curl_init"))
        {
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, NEEDSECURE_FILE_PROTECT_URL);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

            // grab URL and pass it to the browser
            $data = curl_exec($ch);

    //        header("Content-type: image/jpeg");
            $headers = substr($data,0,curl_getinfo($ch,CURLINFO_HEADER_SIZE));
            $data = substr($data,curl_getinfo($ch,CURLINFO_HEADER_SIZE));
            // parse headers
            $headers=explode("\r\n",$headers);
            foreach ($headers as $header)
            {
                // pass each header to browser
                header($header);
            }
            // pass the rest of content to browser
            print($data);
            // close cURL resource, and free up system resources
            curl_close($ch);

        }
        //if nothing is available - try using fopen
        else
        {
            $fp = @fopen(NEEDSECURE_FILE_PROTECT_URL, "rb");

            if (!$fp)
            {
                die('SORRY, Error occured while reading '.NEEDSECURE_FILE_PROTECT_URL.' using FOPEN function.');
            }
            else
            {
                //try to determine mime type of requested file
                $mime = $this->Determine_mime_type_of_file_by_url(NEEDSECURE_FILE_PROTECT_URL);
                //make headers
                if (!empty($mime))
                {
                    header("Content-type: ".$mime);
                }

                while (!feof($fp))
                {
                    // pass the rest of content to browser
                    echo fgets($fp, 128);
                }
                fclose($fp);
            }
        }

    }

	/**
	 * Check user cookie for access_key
	 *
	 */
    function mod_rewrite_cookie()
    {
        if (!defined("NEEDSECURE_FILE_PROTECT_URL"))
        {
            die("ACCESS DENIED && HACK NOT ALLOWED");
        }

        //checking user auth now
        check_user_auth(NEEDSECURE_FILE_PROTECT_URL); //also loads auth_user_model

        //user is logged in but no access_key cookie => access to media denied
        $this->_show_error("user/access_problem.html",array("title"=>"<{user_access_problem_title_access_denied}>","message"=>"<{user_access_problem_description_access_denied}>"));
    }


    /**
     * Wrapper for _view helper: calls it, retrieves html, replaces language, outputs it and dies
     *
     * Simply calling _view and exiting doesn't work for some reason, therefore this "kostyl" is here
     *
     * @param string with template name
     * @param array of template vars
     * @return void
     *      it dies after output
     *
     */
    function _show_error($template, $data)
    {
        $data['error_box']=array();
        if(isset($data['message']))
        {
            $data['error_box']['access_problem']=array('display'=>1,'text'=>$data['message']);
        }
        die(replace_lang(print_page($template,$data,true)));
        //die(replace_lang(_view($template,$vars,true)));
    }

	/**
	 * Enter description here...
	 *
	 * @global array
	 */
    function _restore_environment()
    {
        //1. turning back PATH INFO
        $_SERVER['PATH_INFO'] = NEEDSECURE_FILE_PROTECT_PATH_INFO;

        //2. turning cwd back to previous
        chdir(NEEDSECURE_FILE_PROTECT_DIR);

        //3. returning $_GET
        global $_NS_GET;
        $_GET = $_NS_GET;
    }


    /**
     * This functions was taken from http://php.net/function.parse-url
     * Returns parsed on parts URL
     *
     * @param string $url
     * @return array
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function parseUrl($url)
    {
        $r  = "^(?:(?P<scheme>\w+)://)?";
        $r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
        $r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?" . "(?P<domain>\w+\.(?P<extension>\w+)))";
        $r .= "(?::(?P<port>\d+))?";
        $r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
        $r .= "(?:\?(?P<arg>[\w=&]+))?";
        $r .= "(?:#(?P<anchor>\w+))?";
        $r = "!$r!";                                                // Delimiters

        preg_match ( $r, $url, $out );

        return $out;
    }


    /**
     * Function determines the mime type of the requested file by URL passed to function
     *
     * @param string $url_to_parse
     * @return mixed string/bool
     *
     * @author Makarenko Sergey
     * @copyright 2008
     */
    function Determine_mime_type_of_file_by_url($url_to_parse)
    {
        if (empty($url_to_parse))
        {
            return false;
        }

        $parsed_url = $this->parseUrl($url_to_parse);
        //var_dump($parsed_url); die();
        $filename = isset($parsed_url['file']) ? $parsed_url['file'] : '';
        // Try to determine if the filename includes a file extension.
        if (false === strpos($filename, '.'))
        {
        	return false;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        @include(APPPATH.'config/mimes'.EXT);
        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension]))
        {
        	//$mime = 'application/octet-stream';
        	$mime = '';
        }
        else
        {
        	$mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        // the list of exceptions for extensions
        $exceptions = Array('php','php4','php3','phtml','phps');
        if (in_array($extension, $exceptions))
        {
            $mime = "text/html";
        }
        // _the list of exceptions for extensions

        return $mime;
    }

}
?>
