<?php
/**
 * 
 * THIS FILE CONTAINS Protection_model CLASS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
/**
 * Include file directories_model.php
 */
require_once ('directories_model.php');
/**
 * Enter description here...
 *
 */
define ("PROTECTION_CODE_START", "#NEEDSECURE PROTECTION CODE BEGINS");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_CODE_END", "#NEEDSECURE PROTECTION CODE ENDS");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_CHMOD_MODE", 0777);
/**
 * Enter description here...
 *
 */
define ("PROTECTION_FILENAME_HTACCESS", ".htaccess");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_DIRNAME_PROTECT", "_protect");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_DIRNAME_HT_PWD", "ht_pwd");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_DIRNAME_HT_COOKIE", "ht_cookie");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_FILENAME_PROTECT_FILE", "protect.php");
/**
 * Enter description here...
 *
 */
define ("PROTECTION_PREPEND_FILE_SETENV", "NEEDSECURE_DID");
/**
 * 
 * THIS CLASS CONTAINS METHODS FOR WORK WITH MARKET
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
class Protection_model extends Directories_model
{
	/**
     * THIS METHOD SETS INITIAL VARS (constructor)
     */
    function Protection_model()
    {
        parent::Directories_model();
    }
	/**
	 * Load protection method
	 *
	 * @param unknown_type $method
	 * @return boolean
	 */
    function _load_protection_method($method)
    {
        $CI = &get_instance();
        if (isset($CI->$method) && is_object($CI->$method))
        {//method already loaded
            return true;
        }
                
        $subclass = "protection/$method";
        //$filename = dirname(__FILE__)."/$subclass.php";
        $filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])).'/system/application/models/'.$subclass.".php";
        if (!file_exists($filename))
        {
            admin_log ('load_protection_method', array('method'=>$method,'filename'=>$filename), -1);
            return false;
        }
        $CI->load->model($subclass, $method);
        return true;
    }


    /**
     * Event handler for all protection events
     * for any parameter except $type it can accept both - integer or array of integers
     *
     * @param string $type of the event:
     *        - SUBSCRIPTION_STARTED:  $user, $subscription
     *        - SUBSCRIPTION_EXPIRED:  $subscription
     *        - DIRECTORIES_ADDED:     $product, $directories
     *        - DIRECTORIES_REMOVED:   $product, $directories
     *        + USER_SUSPENDED:        $user
     *        + USER_UNSUSPENDED:      $user
     *        + USER_DELETED:          $user
     *        + USER_UPDATED:          $user
     *        - SITE_IP_CHANGED:
     * @param mixed $users for whose event happened
     * @param integer $subscr_id for whose event happened
     * @param mixed $products for whose event happened
     * @param mixed $directories for whose event happened
     * 
     * @return boolean
     *
     * @author Val Petruchek
     * @copyright 2008
     */
    function event($type, $users=false, $subscr_id=false, $products=false, $directories=false)
    {
        //edited by onagr
        if(is_array($users))
        {
            foreach($users as $key=>$value)
            {
                if(intval($value)==0)
                {
                    unset($users[$key]);
                }            
            }
        }
        //end of edited by onagr        
        
        //admin_log("debug",array('type'=>$type, 'users'=>$users, 'subscription'=>$subscr_id, 'products'=>$products, 'directories'=>$directories));

        $CI = &get_instance();

        switch($type)
        {
            case "USER_UNSUSPENDED":
                $directories = $this->Load_User_Directories($users);
                foreach($directories as $directory)
                {
                    $method = $directory['method']; //current protection method
                    if ($this->_load_protection_method($method))
                    {
                        $CI->$method->grant($directory['id'],$directory['users']);
                    }
                }
                break;
            case "USER_DELETED":
            case "USER_SUSPENDED":
                $directories = $this->Load_User_Directories($users);
                foreach($directories as $directory)
                {
                    $method = $directory['method']; //current protection method
                    if ($this->_load_protection_method($method))
                    {
                        $CI->$method->revoke($directory['id'],$directory['users']);
                    }
                }
                break;
            case "USER_UPDATED":
                $directories = $this->Load_User_Directories($users);
                foreach($directories as $directory)
                {
                    $method = $directory['method']; //current protection method
                    if ($this->_load_protection_method($method))
                    {
                        $CI->$method->update($directory['id'],$directory['users']);
                    }
                }
                break;
            case "DIRECTORIES_ADDED":
                $directories=is_array($directories) ? $directories : array($directories);
                foreach($directories as $did)
                {
                    $this->reprotect($did);
                }
                break;
            case "DIRECTORIES_REMOVED":
                $directories=is_array($directories) ? $directories : array($directories);
                foreach($directories as $did)
                {
                    $this->reprotect($did);
                }
                break;
            case "SUBSCRIPTION_EXPIRED":
                //we need to remove access from directories which are not found in other user subscriptions
                //simply removing access from Subscription_Directories is wrong
                $subscription = $this->Load_Subscription($subscr_id);
                if (!is_array($subscription))
                {
                    return false;
                }
                $expired_dirs = $this->Load_Subscription_Directories($subscr_id); //directories from expired subscription
                $current_dirs = $this->Load_User_Directories($subscription['user_id']); //directories which are still accessible

                foreach($expired_dirs as $directory)
                {
                    if (!array_key_exists($directory['id'], $current_dirs)) //lost access
                    {
                        $method = $directory['method']; //current protection method
                        if ($this->_load_protection_method($method))
                        {
                            $CI->$method->revoke($directory['id'],$subscription['user_id']);
                        }
                    }
                }
                break;
            case "SUBSCRIPTION_STARTED":
                $subscription = $this->Load_Subscription($subscr_id);
                if (!is_array($subscription))
                {
                    return false;
                }
                $arrived_dirs = $this->Load_Subscription_Directories($subscr_id); //new directories for the user
                $current_dirs = $this->Load_User_Directories($subscription['user_id'], $subscr_id); //directories which were accessible before this subscription

                foreach($arrived_dirs as $directory)
                {
                    if (!array_key_exists($directory['id'], $current_dirs)) //lost access
                    {
                        $method = $directory['method']; //current protection method
                        if ($this->_load_protection_method($method))
                        {
                            $CI->$method->grant($directory['id'],$subscription['user_id']);
                        }
                    }
                }
                break;
            case "SITE_IP_CHANGED":
                $directories = $this->Get_all_protected();
                foreach($directories as $directory)
                {
                    $method = $directory['method']; //current protection method
                    if ($this->_load_protection_method($method))
                    {
                        $CI->$method->site_ip_changed($directory);
                    }
                }
                break;
            default:
                admin_log("unknown_event",array('type'=>$type, 'users'=>$users, 'subscriptions'=>$subscriptions, 'products'=>$products, 'directories'=>$directories),-1);
                return false;
                break;
        } // switch
        return true;
    }


    /**
     * Actually protects the directory
     *
     * @param integer $did of the directory (in the database)
     * @param array $before assosiative - previous state of the record
     * @return boolean successful protection or not
     */
    function Protect($did, $before=false)
    {
        $directory = $this->Db_read($did); //reading from database
        if (!$directory)
        {//sorry, can't do anything on non-existing directory
            return false;
        }
        $method = $directory['method']; //current protection method
        if (!$this->_load_protection_method($method))
        {//failed to load specific protector
            return false;
        }

        $CI = &get_instance();

        //trying to protect $directory
        $result = $CI->$method->protect($directory);

        if ($result && is_array($before))
        {//we protected new directory but there is old directory
            $old_method = $before['method']; //previous method

            if ($method!=$old_method) //methods are different
            {
                if (!$CI->$method->synchronize($did))
                {//we are required to build access file for new method
                    return false;
                }
                if ($this->_load_protection_method($old_method))
                {//full unprotect - code from .htaccess already removed, just delete access file
                    $CI->$old_method->unprotect($before, true, false);
                }
            }
            elseif ($directory['fs_path']!=$before['fs_path'])
            {//same method, but another directory
                $CI->$old_method->unprotect($before, false, true);
            }
        }

        return $result;
    }


    /**
     * re-protects the directory
     *
     * @param integer $did of the directory (in the database)
     * @param array $before assosiative - previous state of the record
     * @return boolean successful protection or not
     */
    function Reprotect($did)
    {
        $directory = $this->Db_read($did);
        if (!$directory)
        {
            return false;
        }
        $method = $directory['method'];
        if (!$this->_load_protection_method($method))//failed to load specific protector
        {
            return false;
        }
        $CI = &get_instance();

        //trying to protect and to synchronize
        if (!$CI->$method->protect($directory) || !$CI->$method->synchronize($directory['id']))
        {
            return false;
        }
        //last_protect_time
        $this->db->update(db_prefix.'Dirs', array('last_protect_time'=>date('Y-m-d H:i:s')), array('id'=>$did));        
        return true;
    }


    /**
     * Completely unprotects the directory
     *
     * @param integer $did of the directory (in the database)
     * @return boolean successful protection or not
     */
    function UnProtect($did)
    {
        $directory = $this->Db_read($did);
        if (!$directory)
        {
            return false;
        }
        $method = $directory['method'];
        if (!$this->_load_protection_method($method))//failed to load specific protector
        {
            return false;
        }
        $CI = &get_instance();
        return $CI->$method->unprotect($directory, true, true);
    }


    /**
     * Checks if $path directory is htaccess-writeable
     *     (i.e. are we able to write to its .htaccess file or not)
     *
     * @param string $path path to the directory with ending slash
     * @return boolean writeable or not
     */
    function Is_ht_writable($path)
    {
        clearstatcache();
        if (!@is_dir($path)) //we don't eat shit
        {
            return false;
        }

        $htaccess = $path.PROTECTION_FILENAME_HTACCESS;

        return (file_exists($htaccess)) ? is_writable($htaccess) : is_writable($path);
    }


    /**
     * Reads protection file
     *
     * @param string $filename - full path to the file
     * @return associative array with keys {before, protection, after}
     */
    function Htaccess_read($filename)
    {
        $s = @file_get_contents($filename);
        $k = mb_strpos($s, PROTECTION_CODE_START);
        $l = mb_strpos($s, PROTECTION_CODE_END, $k);

        $result = array();
        if ($k === false) //no start snippet
        {
            $result['before']     = rtrim($s);
            $result['protection'] = "";
            $result['after']      = "";
        }
        else
        {
            $result['before']      = rtrim(mb_substr($s, 0, $k));
            $k = $k + mb_strlen(PROTECTION_CODE_START); //move $k to the end of start snippet
            $result['protection'] = trim(mb_substr($s, $k, $l-$k ));
            $result['after']      = ltrim(mb_substr($s, $l + mb_strlen(PROTECTION_CODE_END)));
        }
        return $result;
    }


    /**
     * Writes protection file and chmodes it
     *
     * @param string $filename - full path to the file
     * @param string $contents - contents before protection
     * @return boolean success or failure
     */
    function Htaccess_write($filename, $contents)
    {
        if (!array_key_exists('before', $contents))
        {
            $contents['before'] = "";
        }
        if (!array_key_exists('after', $contents))
        {
            $contents['after'] = "";
        }
        if (!array_key_exists('protection', $contents))
        {
            $contents['protection'] = "";
        }

        if ($contents['before']!="")
        {//to avoid appending empty lines to the beginning of file
            $contents['before'] .= "\n\n";
        }
        if ($contents['after']!="")
        {//to avoid appending empty lines to the end of file
            $contents['after'] = "\n\n".$contents['after'];
        }

        $contents = ($contents['protection']) ?
            $contents['before'].PROTECTION_CODE_START."\n\n".$contents['protection']."\n\n".PROTECTION_CODE_END.$contents['after']
            :
            $contents['before'].$contents['after'];

        return $this->File_Write($filename, $contents);
    }

	/**
	 * wirte to file
	 *
	 * @param string $filename
	 * @param string $contents
	 * @param string $mode
	 * @return boolean
	 */
    function File_Write($filename, $contents, $mode = "w")
    {
        $bytes = strlen($contents); //yes, strlen and not mb_strlen because we need number of bytes
        if ($f = @fopen($filename, $mode))
        {
            $result = @fwrite ($f, $contents);
            @fclose($f);
            @chmod($filename, PROTECTION_CHMOD_MODE);
            if ($result == $bytes)
            {
                return true;
            }
            else
            {
                admin_log ('file_write', array('filename'=>$filename, 'bytes'=>$bytes, 'reason'=>'fwrite() failed'), -1);
                return false;
            }
        }
      admin_log ('file_write', array('filename'=>$filename, 'bytes'=>$bytes, 'reason'=>'fopen() failed'), -1);
      return false;
    }
	/**
	 * Delete file
	 *
	 * @param string $filename
	 * @return boolean
	 */
    function File_Delete($filename)
    {
        if (@unlink($filename))
        {
            return true;
        }
        admin_log ('file_delete', array('filename'=>$filename), -1);
        return false;
    }
	/**
	 * Write to file. If it is not exist, create it
	 *
	 * @param string $filename
	 * @param string $contents
	 * @return boolean
	 */
    function File_Add($filename, $contents)
    {
        return $this->File_Write($filename, $contents, "a");
    }
	/**
	 * Does a file empty
	 *
	 * @param string $filename
	 * @return boolean
	 */
    function File_Empty($filename)
    {
        return $this->File_Write($filename, "");
    }

	/**
	 * Create a dirrectrory
	 *
	 * @param string $path
	 * @return mixed
	 */
    function Directory_Create($path)
    {
        $this->load->helper('ns_file_helper');

        if (dir_exists($path))
        {
            return true;
        }
        if (@mkdir($path))
        {
            chmod($path, PROTECTION_CHMOD_MODE);
        }

        if (dir_exists($path))
        {
            return true;
        }

        admin_log ('directory_create', array('directory'=>$path), -1);
    }
	/**
	 * delete dirrectory
	 *
	 * @param array $path
	 * @return boolean
	 */
    function Directory_Delete($path)
    {
        if (!$this->Directory_Empty($path))
        {//if there are any files inside the dir, we can't delete it
            return false;
        }
        if (@rmdir($path))
        {
            return true;
        }
        admin_log ('directory_delete', array('directory'=>$path), -1);
        return false;
    }

	/**
	 * Delete all files from directory
	 *
	 * @param string $path
	 * @return boolean
	 */
    function Directory_Empty($path)
    {
        $this->load->helper('ns_file_helper');
        $files = get_dir_contents($path, true, false);
        foreach($files as $filename => $_f)
        {
            if (!$this->File_Delete($filename))
            {
                return false;
            }
        }
        return true;
    }
	/**
	 * Get absolute url
	 *
	 * @return mixed
	 */
    function get_absolute_url()
    {
        $result = config_get('system', 'config', 'base_url');
        return $result;
    }
	/**
	 * Get absolute path
	 *
	 * @return string
	 */
    function get_absolute_path()
    {
        $result = config_get('system', 'config', 'absolute_path');
        return $this->Standartize_directory_name($result);
    }
	/**
	 * Check whether the directory is protected or not
	 *
	 * @param array $directory
	 * @return mixed
	 */
    function ProtectionIsOn($directory)
    {
        $method = $directory['method']; //current protection method
        if (!$this->_load_protection_method($method))
        {//failed to load specific protector
            return false;
        }

        $CI = &get_instance();
        return $CI->$method->IsProtected($directory);
    }
	/**
	 * Return path to htaccess
	 *
	 * @param array $directory
	 * @return string
	 */
    function Htaccess_path($directory)
    {
        return $directory['fs_path'].PROTECTION_FILENAME_HTACCESS;
    }
	/**
	 * File protection code
	 *
	 * @return string
	 */
    function file_protection_code()
    {
    return "<?php\n//NEEDSECURE FILE PROTECTION CODE SNIPPET\ndefine(\"NEEDSECURE_PRODUCT_ID\",<ID>);\n
    if(!defined('NEEDSECURE_PROTECT_FILE_PATH')){define('NEEDSECURE_PROTECT_FILE_PATH','".$this->get_absolute_path().PROTECTION_FILENAME_PROTECT_FILE."');}\nrequire_once(\"".$this->get_absolute_path().PROTECTION_FILENAME_PROTECT_FILE."\");\n//NEEDSECURE FILE PROTECTION CODE SNIPPET ENDS\n?>";
    }

//the methods below will (or won't) be inherited by protection methods
	/**
	 * the methods below will (or won't) be inherited by protection methods
	 *
	 * @param array $directory
	 * @return boolean
	 */

    function IsProtected($directory)
    {
        $htaccess_path = $this->Htaccess_path($directory);
        $htaccess_contents = $this->Htaccess_read($htaccess_path);
        return ( strpos($htaccess_contents['protection'], $this->Get_Protection_Code($directory)) !== false);
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $directory
	 * @return string
	 */
    function Get_Protection_Code($directory)
    {
        return "";
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @param unknown_type $uids
	 * @return true
	 */
    function Grant($did, $uids)
    {
        return true;
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @param unknown_type $uids
	 * @return true
	 */
    function Revoke($did, $uids)
    {
        return true;
    }
	/**
	 * Enter description here...
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
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @return true
	 */
    function Synchronize($did)
    {
        return true;
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $did
	 * @return true
	 */
    function Nullify($did)
    {
        return true;
    }
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $directory
	 * @return true
	 */
    function Site_ip_changed($directory)
    {
        return true;
    }

}
?>
