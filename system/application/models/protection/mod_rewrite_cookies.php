<?php
/**
 * 
 * THIS FILE CONTAINS Mod_rewrite_cookies CLASS
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
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MOD REWRITE COOKIE
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Mod_rewrite_cookies extends Protection_Model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function mod_rewrite_cookies()
    {
        parent::Protection_Model();
    }
	/**
	 * Get absolute path of cookie's directory
	 *
	 * @param mixed $did
	 * @return string
	 */
    function _CookiesDirectoryName($did)
    {
        return $this->get_absolute_path().PROTECTION_DIRNAME_PROTECT."/".PROTECTION_DIRNAME_HT_COOKIE."/$did/";
    }
	/**
	 * Generate cookie file name
	 *
	 * @param mixed $did
	 * @param string $username
	 * @return string
	 */
    function _CookieFileName($did, $username)
    {
        return $this->_CookiesDirectoryName($did).build_cookie_hash($username);
    }
	/**
	 * Get protection code
	 *
	 * @param array $directory
	 * @return string
	 */
    function Get_Protection_Code($directory)
    {
        $CookiesDirectoryName = $this->_CookiesDirectoryName($directory['id']);
        $AbsoluteURL = $this->get_absolute_url().PROTECTION_FILENAME_PROTECT_FILE;

        return
            "RewriteEngine On\n".
            "RewriteCond %{HTTP_COOKIE} ".PROTECTION_COOKIE_NAME."=([a-zA-Z0-9]{32})\n".
            "RewriteCond {$CookiesDirectoryName}%1 -f\n".
            "RewriteRule ^(.*)$ - [L]\n\n".
            "RewriteRule (.*) {$AbsoluteURL}?needsecure_https=%{HTTPS}&needsecure_host=%{HTTP_HOST}&needsecure_ip=%{REMOTE_ADDR}&needsecure_request_uri=%{REQUEST_URI}&needsecure_method=mod_rewrite_cookie [QSA,L,P]";
    }
	/**
	 * Write protection code to htaccess 
	 *
	 * @param array $directory
	 * @param boolean $protect
	 * @return boolean
	 */
    function Protect($directory, $protect = true)
    {
        $protection_code = ($protect) ? $this->Get_Protection_Code($directory) : "";
        if ($protect && !$this->Directory_Create($this->_CookiesDirectoryName($directory['id'])))
        {
            return false;
        }
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
        if ($touch_htaccess)
        {//sometimes we do not need to touch .htaccess - i.e. when directory is reprotected with another method
            $result = $this->Protect($directory, false);
        }
        if ($remove_access)
        {//sometimes we do not need to delete access file - i.e. when fs_path is changed but protection method is the same
            $result = $this->Directory_Delete($this->_CookiesDirectoryName($directory['id']));
        }
        return $result;
    }
	/**
	 * Grants access to directory for users
	 *
	 * @param mixed $did
	 * @param mixed $uids
	 * @return boolean
	 */
    function Grant($did, $uids)
    {
        $users = $this->Load_Users($uids);
        $success = true;
        foreach($users as $username => $password)
        {
            $success = $success && $this->File_Write($this->_CookieFileName($did,$username),"");
        }
        return $success;
    }
	/**
	 * Revokes access to directory from users
	 *
	 * @param mixed $did
	 * @param mixed $uids
	 * @return boolean
	 */
    function Revoke($did, $uids)
    {
        $users = $this->Load_Users($uids);
        $success = true;
        foreach($users as $username => $password)
        {
            $success = $success && $this->File_Delete($this->_CookieFileName($did,$username));
        }
        return $success;
    }
	/**
	 * Updates access to directory for users
	 *
	 * @param unknown_type $did
	 * @param unknown_type $uids
	 * @return true
	 */
    function Update($did, $uids)
    {
        return true;
    }
	/**
	 * Synchronizes directory access file(s) excluding some user ids
	 *
	 * @param mixed $did
	 * @param array $exclude_ids
	 * @return boolean
	 */
    function Synchronize($did, $exclude_ids = false)
    {
        $this->Nullify($did);
        $users = $this->Load_Directory_Users($did);
        $uids = array();

        foreach($users as $user)
        {
            if (!is_array($exclude_ids) || !in_array($user['id'], $exclude_ids))
            {
                $uids[] = $user['id'];
            }
        }
        return $this->Grant($did, $uids);
    }
	/**
	 * Delete all files from directory
	 *
	 * @param mixed $did
	 * @return boolean
	 */
    function Nullify($did)
    {
        return $this->Directory_Empty($this->_CookiesDirectoryName($did));
    }

}
?>
