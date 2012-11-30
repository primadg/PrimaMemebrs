<?php
/**
 * 
 * THIS FILE CONTAINS Mod_rewrite_standard CLASS
 *  
 * @package Prima DG
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
 * THIS CLASS CONTAINS METHODS FOR WORK WITH WWW AUTH
 * 
 * @package Prima DG
 * @author uknown
 * @version uknown
 */
class Www_auth extends Protection_Model
{
    /**
     * Just a constructor
     *
     * @return void
     */
    function Www_auth()
    {
        parent::Protection_Model();
    }


    /**
     * Builds filename of AuthUserFile for directory identified by $did
     *
     * @param integer $did of the directory (in the database)
     * @return string filename (with path) of authuserfile
     */
    function _AuthUserFileName($did)
    {
        return $this->get_absolute_path().PROTECTION_DIRNAME_PROTECT."/".PROTECTION_DIRNAME_HT_PWD."/.ht_$did";
    }


    /**
     * Builds contents of .htaccess file for $directory
     *
     * @param array containing all $directory info
     * @return string htaccess contents for www_auth protection method
     */
    function Get_Protection_Code($directory)
    {
        $AuthUserFile = $this->_AuthUserFileName($directory['id']);
        //AuthName may content double quotes
        $name = str_replace("\"", "''", $directory['name']);
        return "AuthType Basic\nAuthName \"$name\"\nAuthUserFile $AuthUserFile\nRequire valid-user";
    }


    /**
     * Actually (un)protects $directory by modifying its htaccess
     *
     * @param array containing all $directory info
     * @param boolean - true if protect, false for unprotect
     * @return boolean - success or failure
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
     * Unprotects $directory by modifying its htaccess file and/or removing its access file(s)
     *
     * @param array containing all $directory info
     * @param boolean remove access files or not
     * @param boolean modify htaccess or not
     * @return boolean - success or failure
     */
    function Unprotect($directory, $remove_access = false, $touch_htaccess = true)
    {
        $result = true; //so far we are doing good
        if ($touch_htaccess)
        {//sometimes we do not need to touch .htaccess - i.e. when directory is reprotected with another method
            $result = $this->Protect($directory, false);
        }
        if ($remove_access)
        {//sometimes we do not need to delete access file - i.e. when fs_path is changed but protection method is the same
            $this->File_Delete($this->_AuthUserFileName($directory['id']));
        }
        return $result;
    }

    /**
     * Grants access to directory for users
     *
     * @param integer directory id
     * @param array of integers (user ids)
     * @return boolean - success or failure
     */
    function Grant($did, $uids)
    {
        $users = $this->Load_Users($uids);
        $contents = "";
        foreach($users as $username => $password)
        {
            $contents .= "$username:$password\n";
        }
        return $this->File_Add($this->_AuthUserFileName($did), $contents);
    }


    /**
     * Revokes access to directory from users
     *
     * @param integer directory id
     * @param array of integers (user ids)
     * @return boolean - success or failure
     */
    function Revoke($did, $uids)
    {
        return $this->Synchronize($did, $uids);
    }


    /**
     * Updates access to directory for users
     *
     * @param integer directory id
     * @param array of integers (user ids)
     * @return boolean - success or failure
     */
    function Update($did, $uids)
    {
        return $this->Synchronize($did);
    }


    /**
     * Synchronizes directory access file(s) with database, excluding some user ids
     *
     * @param integer directory id
     * @param array of integers (user ids to exclude)
     * @return boolean - success or failure
     */
    function Synchronize($did, $exclude_uids = false)
    {
        $users = $this->Load_Directory_Users($did);
        $contents = "";
        foreach($users as $user)
        {
            if (!is_array($exclude_uids) || !in_array($user['id'], $exclude_uids))
            {
                $contents .= $user['login'].":".$user['pass']."\n";
            }
        }
        return $this->File_Write($this->_AuthUserFileName($did), $contents);
    }


    /**
     * Nullifies directory access file(s)
     *
     * @param integer directory id
     * @return boolean - success or failure
     */
    function Nullify($did)
    {
        return $this->File_Empty($this->_AuthUserFileName($did));
    }

}
?>
