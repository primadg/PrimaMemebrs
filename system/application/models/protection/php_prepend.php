<?php
/**
 * 
 * THIS FILE CONTAINS Mod_rewrite_standard CLASS
 *  
 * @package Prima Members
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
 * THIS CLASS CONTAINS METHODS FOR WORK WITH PHP PREPEND
 * 
 * @package Prima Members
 * @author uknown
 * @version uknown
 */
class Php_prepend extends Protection_Model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Php_prepend()
    {
        parent::Protection_Model();
    }
	/**
	 * Get absolute path to protection file
	 *
	 * @param unknown_type $did
	 * @return string
	 */
    function _php_prepend_filename($did)
    {
        return $this->get_absolute_path().PROTECTION_FILENAME_PROTECT_FILE;
    }
	/**
	 * Get protection code
	 *
	 * @param array $directory
	 * @return string
	 */
    function Get_Protection_Code($directory)
    {
        return "php_value auto_prepend_file ".$this->_php_prepend_filename($directory['id'])."\n<IfModule mod_env.c>\nSetEnv ".PROTECTION_PREPEND_FILE_SETENV." {$directory['id']}\nSetEnv NEEDSECURE_PROTECT_FILE_PATH ".$this->_php_prepend_filename($directory['id'])."\n</IfModule>";
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

}
?>
