<?php
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
	error_reporting( E_ERROR );
    //error_reporting(E_ALL^E_COMPILE_WARNING);

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
| http://codeigniter.com/user_guide/general/managing_apps.html
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
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname($_SERVER['SCRIPT_FILENAME'])) !== FALSE)
	{
		$system_folder = realpath(dirname($_SERVER['SCRIPT_FILENAME'])).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF		- The name of THIS file (typically "index.php)
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
define('EXT', '.'.pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_EXTENSION));
define('FCPATH', $_SERVER['SCRIPT_FILENAME']);
define('SELF', pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME));
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

//***************************FILE_DELETING_FOR_UPDATE************************************
if(file_exists(dirname(FCPATH)."/updater.php"))
{
    include(dirname(FCPATH)."/updater.php");
}
//************************END_OF_FILE_DELETING_FOR_UPDATE********************************

//*******************************ERROR_REPORTING*****************************************
$cpath=BASEPATH."application/models/";
$extended_version=false;
if(file_exists($cpath."/_version.php"))
{
    include($cpath."/_version.php");
}
if(!$extended_version)
{
    if(file_exists(BASEPATH."application/models/version.php"))
    {
        include_once(BASEPATH."application/models/version.php");
    }
}
if(defined('NS_DEBUG_VERSION'))
{
    error_reporting(E_ALL^E_COMPILE_WARNING);
}
//*****************************END_OF_ERROR_REPORTING************************************


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
