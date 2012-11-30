<?php
/**
 * 
 * THIS FILE CONTAINS Mod_rewrite_standard CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
$protection_model_path=realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/system/application/models/protection_model.php');
/**
 * Include file $protection_model_path.php
 */
require_once ($protection_model_path); //just in case
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MOD REWRITE
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Mod_rewrite_standard extends Protection_Model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Mod_rewrite_standard()
    {
        parent::Protection_Model();
    }
	/**
	 * Get protectopn code
	 *
	 * @param array $directory
	 * @return string
	 */
    function Get_Protection_Code($directory)
    {
        $AbsoluteURL = $this->get_absolute_url().PROTECTION_FILENAME_PROTECT_FILE;

        return
            "RewriteEngine On\n".
            "RewriteCond %{REMOTE_ADDR} !^".config_get('system','config','site_ip')."$\n".
            "RewriteRule (.*) {$AbsoluteURL}?needsecure_directory=".$directory['id']."&needsecure_https=%{HTTPS}&needsecure_host=%{HTTP_HOST}&needsecure_ip=%{REMOTE_ADDR}&needsecure_request_uri=%{REQUEST_URI}&needsecure_method=mod_rewrite_standard [QSA,L]";
    }
	/**
	 * Protect directory
	 *
	 * @param array $directory
	 * @param boolean $protect
	 * @return boolean
	 */
    function Protect($directory, $protect = true)
    {
        $protection_code = ($protect) ? $this->Get_Protection_Code($directory) : "";
        $htaccess_path = $this->Htaccess_path($directory);
        $htaccess_contents = $this->Htaccess_read($htaccess_path);
        $htaccess_contents['protection'] = $protection_code;
        if (!$this->Htaccess_write($htaccess_path, $htaccess_contents))
        {
            return false;
        }
        return true;
    }
	/**
	 * Unprotect directory
	 *
	 * @param array $directory
	 * @param boolean $remove_access
	 * @param boolean $touch_htaccess
	 * @return boolean
	 */
    function Unprotect($directory, $remove_access = false, $touch_htaccess = true)
    {
        $result = true; //so far we are doing good
        if ($touch_htaccess)
        {//sometimes we do not need to touch .htaccess - i.e. when directory is reprotected with another method
            $result = $this->Protect($directory, false);
        }
        return $result;
    }
	/**
	 * Protect directory
	 *
	 * @param array $directory
	 * @return boolean
	 */
    function Site_ip_changed($directory)
    {
        return $this->Protect($directory);
    }

}
?>
