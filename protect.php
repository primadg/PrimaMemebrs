<?php
/**
 * This file enables php_prepend protection method.
 *
 * @author Val Petruchek
 * @copyright 2008
 */
    
    // Is there a PATH_INFO variable?
    // Note: some servers seem to have trouble with getenv() so we'll test it two ways		
    $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');			
    $_SERVER['PATH_INFO'] = $path;
    if ($path != '' AND $path != '/')
    {
        $_SERVER['PATH_INFO'] = $path;
    }
    else{    
        // No PATH_INFO?... What about QUERY_STRING?
        $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');	
        if ($path != '' AND $path != '/')
        {
            $_SERVER['PATH_INFO'] = $path;
        }
        else{
            // No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
            $path = (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO');	
            if ($path != '' AND $path != '/')
            {
                $_SERVER['PATH_INFO'] = $path;
            }
        }
    }
        
    
    $current_file_path=strlen(getenv("NEEDSECURE_PROTECT_FILE_PATH"))>0 ? getenv("NEEDSECURE_PROTECT_FILE_PATH") : realpath($_SERVER ['SCRIPT_FILENAME']);
    if(defined('NEEDSECURE_PROTECT_FILE_PATH')){
        $current_file_path=realpath(NEEDSECURE_PROTECT_FILE_PATH);
    }
    
    function ns_build_url($https, $host, $request_uri, $query_string)
    {
        $result = (strtolower($https) == "on") ? "https://" : "http://";
        $result .= $host.$request_uri;
        if ($query_string)
        {
            $result .= "?".$query_string;
        }
    return $result;
    }

    /**
     * Returns current URL
     * Better to say: does its best to return current url
     *
     * @return string
     */
    function ns_current_url()
    {
        //protocol
        $result  = (array_key_exists("HTTPS", $_SERVER) && ($_SERVER["HTTPS"] == "on")) ? 'https://' : 'http://';
        //host
        $result .= getenv("HTTP_HOST");
        //port
        if  ( ($_SERVER["SERVER_PORT"] != "80") && (strpos(getenv("HTTP_HOST"),":")===false) )
        {//it looks like if port ne 80, HTTP_HOST contains it, but we are double checking
            $result .= ":".$_SERVER["SERVER_PORT"];
        }
        if (array_key_exists("REQUEST_URI", $_SERVER))
        {//good apache sets REQUEST_URI
            $result .= $_SERVER["REQUEST_URI"];
        }
        elseif (array_key_exists("SCRIPT_NAME", $_SERVER))
        {//bad apache doesn't set REQUEST_URI, trying to build uri by hand: SCRIPT_NAME?QUERY_STRING
            $result .= $_SERVER["SCRIPT_NAME"];
            if (getenv("QUERY_STRING"))
            {
                $result .= "?".getenv("QUERY_STRING");
            }
        }
        /**
         * Well, I'm almost sure there will be unique apache configuration which won't let this function work properly
         * Therefore, this function needs further development.
         * Please create test phpinfo() file and forward its output to us.
         * We will study it and add support for your apache configuration.
         */
        return $result;
    }

    $_GET['needsecure_method']=isset($_GET['needsecure_method']) ? $_GET['needsecure_method'] : '';
    
    switch($_GET['needsecure_method'])
    {
        case "mod_rewrite_standard":
            //we are inside [P] apache subrequest here, so need to hack around CodeIgniter&NeedSecure
            $method = "mod_rewrite_standard";

            //contains both needsecure_ and [QSA] vars
            $query_string = getenv("QUERY_STRING");

            //this must be a last needsecure_ variable in RewriteRule
            $last_system_parameter = "needsecure_method=$method";

            //leaving only variables from [QSA]
            $query_string = substr($query_string,strpos($query_string,$last_system_parameter)+strlen($last_system_parameter)+1);

            //reconstructing the original URL for further redirection
            define("NEEDSECURE_FILE_PROTECT_URL", ns_build_url($_GET['needsecure_https'],$_GET['needsecure_host'],$_GET['needsecure_request_uri'],$query_string));

            //these constants are used by file_protect controller
            define("NEEDSECURE_DID", $_GET['needsecure_directory']);
            define("NEEDSECURE_FILE_PROTECT_DIR", getcwd());
            define("NEEDSECURE_FILE_PROTECT_PATH_INFO", $_SERVER['PATH_INFO']);

            //preventing auth model from destroying user session because when in [P] mode apache sends server IP instead of client's
            $_SERVER['REMOTE_ADDR'] = $_GET['needsecure_ip'];

            //echo('all is OK, we are here'.__LINE__.','.$_SERVER['REMOTE_ADDR']);

            break;

        case "mod_rewrite_cookie":
            //we are inside [P] apache subrequest here, so need to hack around CodeIgniter&NeedSecure
            $method = "mod_rewrite_cookie";

            //contains both needsecure_ and [QSA] vars
            $query_string = getenv("QUERY_STRING");

            //this must be a last needsecure_ variable in RewriteRule
            $last_system_parameter = "needsecure_method=$method";

            //leaving only variables from [QSA]
            $query_string = substr($query_string,strpos($query_string,$last_system_parameter)+strlen($last_system_parameter)+1);

            //reconstructing the original URL for further redirection
            define("NEEDSECURE_FILE_PROTECT_URL", ns_build_url($_GET['needsecure_https'],$_GET['needsecure_host'],$_GET['needsecure_request_uri'],$query_string));

            //preventing auth model from destroying user session because when in [P] mode apache sends server IP instead of client's
            $_SERVER['REMOTE_ADDR'] = $_GET['needsecure_ip'];

            break;

        default: //php_prepend or file protection
            $method = (defined("NEEDSECURE_PRODUCT_ID")) ? "file_protection" : "php_prepend";

            //these constants are used by file_protect controller
            define("NEEDSECURE_FILE_PROTECT_URL", ns_current_url());
            define("NEEDSECURE_FILE_PROTECT_DIR", getcwd());
            define("NEEDSECURE_FILE_PROTECT_PATH_INFO", $_SERVER['PATH_INFO']);

            break;
    } // switch

    //CI needs it
    chdir(dirname($current_file_path));

    //hiding GET from CI
    $_NS_GET = $_GET;
    $_GET = array();

    if (!isset($method) || (!$method))
    {
        die("Sorry, but we have lost our mind.");
    }

    //hacking CI as recommended on their site - calling specific method of specific controller
    $_SERVER['PATH_INFO'] = "/file_protect/$method";

    //below is the copy of CI index.php
/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
    error_reporting(E_ERROR);

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
    $system_folder = "system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://www.codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
    $application_folder = "application";


/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
|
*/
if (function_exists('realpath') AND @realpath(dirname($current_file_path)) !== FALSE)
{
    $system_folder = str_replace("\\", "/", realpath(dirname($current_file_path))).'/'.$system_folder;
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT       - The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF        - The name of THIS file (typically "index.php)
| BASEPATH    - The full server path to the "system" folder
| APPPATH    - The full server path to the "application" folder
|
*/
define('EXT', '.'.pathinfo($current_file_path, PATHINFO_EXTENSION));
define('FCPATH', $current_file_path);
define('SELF', pathinfo($current_file_path, PATHINFO_BASENAME));
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
    define('APPPATH', $application_folder.'/');
}
else
{
    if ($application_folder == '')
    {
        $application_folder = 'application';
    }

    define('APPPATH', BASEPATH.$application_folder.'/');
}

//***************************GLOBAL_HELPER************************************
if(file_exists(APPPATH."helpers/global_helper.php"))
{
    require_once(APPPATH."helpers/global_helper.php");
}
//************************END_OF_GLOBAL_HELPER********************************

/*
|---------------------------------------------------------------
| DEFINE E_STRICT
|---------------------------------------------------------------
|
| Some older versions of PHP don't support the E_STRICT constant
| so we need to explicitly define it otherwise the Exception class
| will generate errors.
|
*/
if ( ! defined('E_STRICT'))
{
    define('E_STRICT', 2048);
}

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;
?>
