<?php
/**
 * 
 * THIS FILE CONTAINS NS FILE FUNCTIONS
 *  
 * @package Needsecure
 * @author uknown
 * @version uknown
 */

    /**
     * Gets list of files and (or) directories in specified directory
     *
     * @param string $path path to directory with ending slash
     * @param boolean $return_files include files in result or not
     * @param boolean $return_directories include directories in result or not
     * @return assosiative array: key is path to file, value is filename
     *          or false if directory specified doesn't exist or is n/a
     *          values are sorted in natural case-insensitive order
     *
     * @author Val Petruchek
     * @copyright 2008
     */
    function get_dir_contents($path, $return_files = true, $return_directories = false)
    {
        $result = array();
        @clearstatcache();
        $d = @dir($path);
        if (!@$d)
        {
            return array();
        }
        while (false !== ($filename = $d->read()))
        {
            $realfile = $path.$filename;
            if ($return_files && is_file($realfile))
            {
                $result[$realfile] = $filename;
            }
            if ($return_directories && @is_dir($realfile) && ($filename != '.') && ($filename != '..'))
            {
                $result[$realfile."/"] = $filename;
            }
        }
        $d->close();
        //sorting directories
        natcasesort($result);
        return $result;
    }
    
    /**
     * Read file and delete BOM  
     *
     * @param string $file path to file
     * @return mixed
     *
     * @author onagr
     * @copyright 2009
     */
    function read_utf8_file($file)
    {
        if(file_exists($file) && false!==$handle = @fopen($file, "rb"))
        {
            $contents = false;
            while (!feof($handle)) {
                if($contents===false)
                {
                    $c = fread($handle, 3);
                    $contents=(strtoupper(bin2hex($c))=='EFBBBF') ? '' : $c;
                }
                else
                {
                    $contents .= fread($handle, 8192);
                }
            }
            fclose($handle);
            return $contents;
        }
        return false;
    }

    /**
     * Check whether directory $path exists (accomodates both is_dir and file_exists checks)
     *
     * @param string $path path to directory with ending slash
     * @return boolean
     *
     * @author Val Petruchek
     * @copyright 2008
     */
    function dir_exists($path)
    {
        @clearstatcache();
        return (file_exists($path) && is_dir($path));
    }
?>
