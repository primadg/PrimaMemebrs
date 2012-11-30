<?php
error_reporting( E_ERROR );
/**
 * Prima Members
 *
 * The next generation web site protection system
 *
 * @package     Install
 * @author      Prima DG Team
 * @copyright   Copyright (c) 2012, Prima DG Ltd.
 * @link        http://primadg.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------


/*------------------------------------------------------------------------------------------------------------------
| Testing and Install functions library
+-------------------------------------------------------------------------------------------------------------------
| e                     - return error description if error happens
| generatePass          - Generate password
| init                  - initiate some settings
| languageDetect        - Detect user preferred language from HTTP_ACCEPT_LANGUAGE
| languageSelect        - Generate HTML-form with language switcher
| parseApacheModules    - Parse Apache modules from PHPinfo
| parsePHPConfig        - Returns single value or array of values from phpinfo()
| setCHMOD              - Try to set CHMOD for specified array of dirs/files
| str_split_php4        - PHP5 str_split function analogue for PHP4
| t                     - translate string key into current language phrase
| testReport            - Generate "System compatibility test" page
| testMySQLConnection   - Try to connect with specified options (... and OPTIONALY create table)
| v                     - help initialize fields value
| validateDateFormat    - Validates date format
| writeConfig           - Write config files and import db from MySQL dump file
| writeDB               - Restore DB tables from dump file
| writeHTAccess         - Write .htaccess files
+-------------------------------------------------------------------------------------------------------------------
*/

/**
 * kTest Class
 *
 * The main class for installer and testing.
 *
 * @package     Install
 * @author      Konstantin X
 * @todo        Split testing and install function into separate class
 */
class kTest
{

    /**
     * kTest constructor
     *
     * @access  public
     * @param   void
     * @return  void
     * @author  Konstantin X
     */
    function kTest()
    {
    }


    /**
     * Returns error description if error happens
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  string
     * @author  Konstantin X
     */
    function e($key='', $level='warn')
    {
        global $aLanguage, $aErrors;
        $e_open  = '<img src="css/i/ico_warn.png" title="';
        $e_close = '" class="warning" alt="warning sign" />';

        if (@array_key_exists($key, $aErrors) && !empty($aErrors[$key])) return $e_open . $this->t($aErrors[$key], FALSE) . $e_close;
    }



    /**
     * Initialization
     *
     * Check, Define and Load system settings
     *
     * @access  public
     * @param   ... if need to re-initialize settings
     * @return  TRUE or string error
     */
    function init($language='')
    {
        global $iConfig, $aLanguage;

        $def_lang = $iConfig['default_lng'];
        if (empty($language)) $language = $def_lang;
        $path = sprintf('language/%2s_lang.php', $language);

        if (!file_exists($path))
        {
            $path=sprintf('language/%2s_lang.php', 'en');            
        }        
        if (file_exists($path))
        {
            include_once($path);
            return $lang;          
        }
        return FALSE;
    }
    
    /**
     * include source lang fale
     *
     * @access  public
     * @param   string $file
     * @param   string $language
     * @return  void
     */
    function include_lang_file($file,$language)
    {
        global $iConfig;
        $def_lang = $iConfig['default_lng'];
        if (empty($language)) $language = $def_lang;
        $path = 'language/'.$language.$file;
        if (!file_exists($path))
        {
            $path='language/en'.$file;
        }        
        if (file_exists($path))
        {
            @include($path);                     
        }
    }


    /**
     * Generate password
     *
     * @access  public
     * @param   integer the Password length
     * @param   bool TRUE - if need to generate more secure password
     *
     * @return  string
     */
    function generatePass($pass_length, $strong=FALSE)
    {
                     $pass_symbols = 'abcdefghijkmnopqrstuvxyzABCDEFGHIJKLMNPQRTUVXYZ1234567890';                   // Some symbols are dropped for easy reading (ex. O!=0)
        if ($strong) $pass_symbols.= '.,()[]!?&^%@*$<>/|+-{}~';

        if(function_exists('str_split')) $arr = str_split($pass_symbols);                                           // for PHP5
        else                             $arr = $this->str_split_php4($pass_symbols);                               // for PHP4

        $pass  = "";
        $a_len = count($arr);

        for($i = 0; $i < $pass_length; $i++)
        {
          $index = rand(0, $a_len - 1);                                                                             // get random index
          $pass .= $arr[$index];
        }
        return $pass;
    }


    /**
     * Detect langueage
     *
     * Detect user preferred language
     *
     * @access  public
     * @return  detected language or nothing
     *
     * @TODO    upgrade with incoming language
     */
    function languageDetect()
    {
        global $iConfig;

        $langs = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {

            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);   // break up string into pieces (languages and q factors)

            if (count($lang_parse[1]))
            {
//          $langs = array_combine($lang_parse[1], $lang_parse[4]);                                                 // create a list like "en" => 0.8  // PHP5 only
                foreach ($lang_parse[1] as $k => $v) $langs[$v] = $lang_parse[4][$k];                               // replace "array_combine" for PHP4

                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;                                                             // set default to 1 for any without q factor
                }
                arsort($langs, SORT_NUMERIC);                                                                       // sort list based on value
            }
        }

        foreach ($langs as $lang => $val)
        {
            $lang_2s = substr($lang, 0,2);
            $path = sprintf('language/%s_lang.php', $lang_2s);
            if (file_exists($path)) return $lang_2s;
        }

        return $iConfig['default_lng'];
    }


    /**
     * Langueage switcher
     *
     * Generate HTML-form with language switcher
     *
     * @access  public
     * @params  selected language
     * @return  html-form
     */
    function languageSelect($lang='', $target='', $method='post')
    {
      $langs = array();

      $d     = @dir('language');
      if ($d)
      {
        while (false !== ($entry = $d->read()))
        {
          if ($entry != '..' && $entry != '.' && substr($entry,2) == '_lang.php') $langs[] = substr($entry,0,2);
        }
      $d->close();
      }

      if (! empty($langs))
      {
        if (empty($lang) || !in_array($lang, $langs)) $lang = $this->languageDetect();
        if (count($langs) < 2) return "";

        $return_form = '<form id="lang_switch" name="lang_switch" target="'. $target .'" method="'. $method .'">';
        $return_form.= $this->t('global_autodetect_language');
        $return_form.= '<select id="language" name="language">';
        foreach ($langs as $language)
        {
          $return_form.= '<option value="'. $language .'"';
          if ($lang == $language) $return_form.= ' selected';
          $return_form.= '>'. $language .'</option>';
        }
        $return_form.='</select><button id="lang_submit" type="submit">'. $this->t('global_go', FALSE).'</button></form>';
      }
      return $return_form;
    }
    
    /**
     * Get MySQL Version
     *
     * Get MySQL Version without authorization in mysql server
     *
     * @access  public
     * @param   string
     * @param   integer
     * @return  string OR FALSE
     */
    function getMysqlVersion($host='localhost',$port=3306)
    {
        $res="";
        $fp = @fsockopen ($host, $port, $errno, $errstr, 30);
        if( !$fp )
        {
            return false;
        }
        
        while(false!==($c=fgetc($fp)))
        {
            $res.=$c;
            preg_match("/((\d\.)+[\d]*[A-Za-z-]*)[^0-9A-Za-z\.-]+$/", $res, $matches);
            if(isset($matches[1]))
            {
                fclose($fp);
                return $matches[1];                
            }  
        }
        preg_match("/((\d\.)+[\d]*[A-Za-z-]*)[^0-9A-Za-z\.-]+$/", $res, $matches);
        if(isset($matches[1]))
        {
            fclose($fp);
            return $matches[1];
        }
        
        /* 
        
        
        
        while (!feof($fp))
        {
            $r = fgets ($fp, 1024);
            $res.=$r;
            preg_match("/([\d\.]+[\d]*[A-Za-z-]*)/", $res, $matches);
            if(isset($matches[0]))
            {
                fclose($fp);
                return $matches[0];
            }
        } */
        fclose($fp);        
        return false;        
    }


    /**
     * Parse Apache Modules
     *
     * Parse Apache modules from PHPinfo
     *
     * @access  public
     * @param   string OR array
     * @param   array
     * @return  string OR array OR FALSE - if requested wrong key
     */
    function parseApacheModules($param='', $aHTdetect='')
    {
        $aReturn    = array();
        $aModules   = array();

        if ( ! empty($aHTdetect))
        {
            $aModules = $aHTdetect;
        } else {
            $sModules = $this->parsePHPConfig("loaded_modules");
            if ($sModules !== FALSE) $aModules = explode(" ", $sModules);                                           // is modules loaded
        }

        if (empty($param))
        {
            foreach ($aModules as $v)
            {
                $aReturn[$v] = TRUE;
            }
            return $aReturn;
        }

        if (is_array($param))
        {
            foreach ($param as $v)
            {
                if(is_array($v))
                {

                    $flag=false;
                    foreach($v as $val)
                    {
                        if (in_array($val, $aModules))
                        {
                            $aReturn[$val] = TRUE;
                            $flag=true;
                            break;
                        }
                    }
                    if(!$flag)
                    {
                        $aReturn[implode(" / ",$v)] = FALSE;
                    }
                }
                else
                {
                    if (in_array($v, $aModules)) $aReturn[$v] = TRUE;
                    else                         $aReturn[$v] = FALSE;
                }
            }
            return $aReturn;
        }else{
            if (in_array($param, $aModules)) return TRUE;
            else                             return FALSE;
        }
    }


    /**
     * Parse PHP Config
     *
     * Parse phpinfo output into array and return requested value or array of values
     *
     * @access  public
     * @param   string OR array
     * @return  string OR array OR FALSE - if requested wrong key
     */
    function parsePHPConfig($params='')
    {
		ob_start();
        phpinfo(-1);
        $s = ob_get_contents();
        ob_end_clean();

        $a      = $mtc = array();
        $c_dash = array(' ', ':', '/', '-');
        $c_none = array('"]', '(', ')');
        $c_dot  = array('["');

        if (preg_match_all('/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/',$s,$mtc,PREG_SET_ORDER))
        {
            /* echo "<pre>";
            print_r($mtc);
            echo "</pre>"; */
            foreach ($mtc as $v)
            {
                if ($v[2] == '<i>no value</i>') continue;

                $label = trim($v[1]);
                $key   = strtolower($label);
                $key   = str_replace($c_none,"", $key);
                $key   = str_replace($c_dash,"_",$key);
                $key   = str_replace($c_dot, ".",$key);

                $a[$key] = array('label' => $label,'value' => trim($v[2]));
            }
        }
        $a['php_version'] = array('label' => 'PHP version','value' => phpversion());

        if (empty($params))
        {
            $params = 'all';
        }

        if (is_array($params))
        {
            foreach ($params as $v)
            {
                if (array_key_exists($v, $a))
                {
                    $return_array[$v] = $a[$v];
                }
            }
            return $return_array;
        }else{
            if (array_key_exists($params, $a))
            {
                $return_value = $a[$params]['value'];
            }
            elseif ($params == 'all')
            {
                return $a;
            }else{
                $return_value = FALSE;
            }
            return $return_value;
        }
    }


    /**
     * Set CHMOD
     *
     * Try to set CHMOD for specified array of dirs/files
     *
     * @access  public
     * @param   array
     * @return  array OR FALSE - if requested wrong key
     */
    function setCHMOD($params='')
    {
        global $iConfig;
        $aReturn = array();

        if (empty($params))
        {
            $params = $iConfig['chmod_paths'];
        }

        foreach ($params as $path => $mode)
        {
            $aReturn[$path]['status']   = '';
            $real_path                  = realpath(dirname($_SERVER['SCRIPT_FILENAME']) .'/../'. $path);                               // Get real path

            if (file_exists($real_path))
            {
                if (!fileperms($real_path))
                {
                    $aReturn[$path]['status'] = sprintf($this->t('e_permition_fail'), $path);
                }else{
//                    if (!@chmod($real_path, $mode)) $aReturn[$path]['status'] = sprintf($this->t('e_chmod_fail'), $path); // Need to test

                    $aReturn[$path]['mode'] = '0'.decoct(0777 & fileperms($real_path));
                    //echo $path.": (".fileperms($real_path).")".($aReturn[$path]['mode'])." - ".($mode)."<br/>";
                    $s1=strrev("".$aReturn[$path]['mode']);
                    $s2=strrev("".$mode);
                    $aReturn[$path]['status'] = '';
                    for($i=0;$i<strlen($s2);$i++)
                    {
                        if(intval($s1[$i])<intval($s2[$i]))
                        {
                            $aReturn[$path]['status'] = sprintf($this->t('e_chmod_fail'), $path);
                            break;
                        }
                    }
                    /* if(intval($aReturn[$path]['mode']) != intval($mode)) $aReturn[$path]['status'] = sprintf($this->t('e_chmod_fail'), $path);
                    else $aReturn[$path]['status'] = ''; */
                }
            }else{
                $aReturn[$path]['status'] = sprintf($this->t('e_missing_file'), $path);
            }
        }
        return $aReturn;
    }


    /**
     * str_split for PHP4
     *
     * @access  public
     * @param   string
     * @return  array
     */
    function str_split_php4($string, $split_length = 1)
    {
        $aResult = explode("\r\n", chunk_split($string, $split_length));
        array_pop($aResult);
        return $aResult;

/*  Update 15.12.2008 need to test
       $aResult = array();
       $iLength = strlen($sText);
       for($i=0; $i < $iLength; $i++) {
           $aResult[]=substr($sText, $i, 1);
       }
       return $aResult;
*/
    }


    /**
     * Translate tool
     *
     * string translate
     *
     * @access  public
     * @param   string language constant
     * @param   bool - show error with decoration
     * @return  string assigned to requested language constant
     *
     * @TODO    upgrade with %s replacement
     */
    function t($key='', $decorate=TRUE)
    {
        global $aLanguage;

        if (empty($key)) return '';
        if (@array_key_exists($key, $aLanguage)) return $aLanguage[$key];

        if ($decorate) return '<span style="color: #f00; text-decoration: blink;" title="['.$key.'] - not exist">'. $key.'</span>';
        else return $key;
    }


    /**
     * Translate tool
     *
     * string translate
     *
     * @access  public
     * @param   string language constant
     * @return  string assigned to requested language constant
     *
     * @TODO    upgrade with mysql_errno();
     */
    function testMySQLConnection($host, $port, $user, $pass, $db_name, $db_prefix='', $db_create=FALSE)
    {
        if (preg_match("/[^a-zA-Z0-9_]+/", $db_prefix)) return array('db_prefix' => 'e_mysql_latin');                          // Error: prefix is not latin
        if (preg_match("/[^a-zA-Z0-9_]+/", $db_name))   return array('db_name'   => 'e_mysql_latin');                          // Error: prefix is not latin

        if (!$link = @mysql_connect($host .':'. $port, $user, $pass)) return array('db_host' => 'e_mysql_connect'); // return 'e_mysql_'. mysql_errno($link); // Upgrade
        
        global $iConfig;
        if (version_compare(preg_replace("/(\d+\.)(\d+[\.]*)(\d*)[\s\S]*/i", "\$1\$2\$3",mysql_get_server_info()), $iConfig['mysql']['mysql_version'], '<'))
        {
            return array('db_host' => 'e_mysql_version');
        }

        if (!mysql_select_db($db_name, $link))
        {
            if ($db_create)
            {
                $sql = sprintf("CREATE DATABASE `%s` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';", $db_name);   // TODO: Kill DB on Uninstall
                if (!mysql_query($sql, $link)) return array('db_name' => 'e_mysql_create_db');                      // return 'e_mysql_'. mysql_errno($link); // Upgrade
            }else{
                return array('db_name' => 'e_mysql_nodb');                                                           // specified DB not exist
            }
        }
        
        $sql = sprintf('SELECT COUNT(*) FROM %s', $db_prefix.'Product_product_group');                              // Check method : If this table exist - maybe NeedSecuer already installed
        if (mysql_query($sql, $link)) return array('db_prefix' => 'e_mysql_table_exist');                           // return 'e_mysql_'. mysql_errno($link); // Upgrade
                
        
        mysql_close($link);

        return array();
    }

    /**
     * Generate "System compatibility test" page
     *
     * Include file with config arrays and compare with server info from parsePHPConfig function
     * generate report page
     *
     * @access  public
     * @return  HTML page
     */
    function testReport($aServer='', $aHTDetect='')
    {
        global $iConfig, $sys_lang;
      
        $aPHPval    = array_keys($iConfig['php_modules']);
        $aPaths     = array_keys($iConfig['chmod_paths']);

        $dataServer = (empty($aServer)) ? $this->parsePHPConfig($iConfig['server_info']) : $this->parsePHPConfig($aServer);
        $dataMods   = (empty($aHTDetect)) ? $this->parseApacheModules($iConfig['server_modules']) : $this->parseApacheModules($iConfig['server_modules'], $aHTDetect);
		$dataModsOpt   = (empty($aHTDetect)) ? $this->parseApacheModules($iConfig['server_modules_optional']) : $this->parseApacheModules($iConfig['server_modules_optional'], $aHTDetect);
        $dataPHP    = $this->parsePHPConfig($aPHPval);
        $dataPaths  = $this->setCHMOD($iConfig['chmod_paths']);
        $is_error=false;
        ob_start();
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
    <html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />

    <title><?php echo $this->t('report_title');?></title>

    <link type="text/css" href="css/report.css" rel="stylesheet" media="screen" />
    <link rel="shortcut icon" href="css/favicon.ico" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script>
    $(document).ready(function(){
        var cookie = document.cookie.toUpperCase();
        $('.browser_js').show();
        if(cookie.indexOf("PHPSESSID")<0)
        {
            $('.browser_cookie_disabled').show();
        } 
        else
        {
            $('.browser_cookie_enabled').show();
            $('.browser_control').hide();
        }
    });
    </script>
    
    </head>
    <body>
    <div class="center">
        <table border="0" cellpadding="3" width="600">
          <tr class="h"><td><a href="http://www.primadg.com/"><img border="0" src="css/logo.png" alt="Logo" /></a><h1 class="p"><?php echo $this->t('report_title');?></h1></td></tr>
          <tr class="nav"><td><?php echo $this->languageSelect($sys_lang, '', 'get');?></td></tr>
        </table>
        <br />
        <?php
        $is_cgi = strtolower($this->parsePHPConfig('server_api'));
        $aCGI   = array('cgi');
        if (in_array($is_cgi, $aCGI))
        {
            echo '<h1 class="warning">'. $this->t('w_cgi_module') .'</h1>';
        }
        if (strpos('apache',strtolower($is_cgi))<0)
        {
            echo '<h1 class="warning">'. $this->t('w_not_apache') .'</h1>';
        }
        ?>
        <table border="0" cellpadding="3" width="600">
    <?php
        foreach ($dataServer as $k => $v)
        {
    echo '   <tr><td class="e" width="200px" title="'. $k .'">'. $v['label'] .'</td><td class="v">'. $v['value'] .'</td></tr>';
        }
    ?>
        </table>

        <h2><?php echo $this->t('report_mod_server');?></h2>
        <table width="600" cellpadding="3" border="0">
            <tbody>
                <tr class="h"><th width="200"><?php echo $this->t('report_module');?></th><th><?php echo $this->t('report_module_local');?></th><th><?php echo $this->t('report_module_desired');?></th><th><?php echo $this->t('report_module_hosting');?></th></tr>
    <?php
        if ($dataMods === FALSE)
        {
            echo '  <tr><td class="error" colspan="4">'. $this->t('e_apache_modules') .'</td></tr>';
        }else{
            foreach ($dataMods as $k => $v)
            {
                $td_class = 'error';
                $msg      = $this->t('report_module_fail');

                if ($v)
                {
                    $td_class = 'v';
                    $msg      = $this->t('report_module_ok');
                }
                
                if($td_class == 'error')
                {
                    $is_error=true;
                }

                echo '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $msg .'</td><td class="'. $td_class .'">'. $this->t('report_module_ok') .'</td><td class="'. $td_class .'">'. $this->t('report_module_ok') .'</td></tr>';
            }
			$out_td = '';
            foreach ($dataModsOpt as $k => $v)
            {
                $td_class = 'test_warning';
                $msg      = $this->t('report_module_fail');

                if ($v)
                {
                    $td_class = 'v';
                    $msg      = $this->t('report_module_ok');
                }
                
                if($td_class == 'error')
                {
                    $is_error=true;
                }

                $out_td .= '<tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $msg .'</td><td class="'. $td_class .'">'. $this->t('report_module_ok') .'</td><td class="'. $td_class .'">'. $this->t('report_module_ok') .'</td></tr>';
                if (!$v)
                    $out_td .= '<tr><td colspan="4" class="wt">'.$this->t($k.'_optional').'</td></td></tr>';
                
            }
            echo $out_td;
        }
    ?>
            </tbody>
        </table>

        <h2><?php echo $this->t('report_mod_php');?></h2>
        <table width="600" cellpadding="3" border="0">
            <tbody>
                <tr class="h"><th width="200"><?php echo $this->t('report_module');?></th><th><?php echo $this->t('report_module_local');?></th><th><?php echo $this->t('report_module_desired');?></th><th><?php echo $this->t('report_module_hosting');?></th></tr>
    <?php
        foreach ($iConfig['php_modules'] as $k => $v)
        {
            $td_class = 'error';
            $val=isset($dataPHP[$k]) ? $dataPHP[$k]['value'] : $this->t('report_browser_local');
            
            if (isset($dataPHP[$k]) && $dataPHP[$k]['value'] == $v)
            {
                $td_class = 'v';
            }            
            if ($k == 'php_version' && version_compare($dataPHP[$k]['value'], $v, ">="))
            {
                $td_class = 'v';
            }
            if($td_class == 'error')
            {
                $is_error=true;
            }
    	if ($k == 'openssl_support')
			echo '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $val .'</td><td class="'. $td_class .'">'. 'for hosting only' .'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
		else
    		echo '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'">'. $val .'</td><td class="'. $td_class .'">'. $v .'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
        }
    ?>
            </tbody>
        </table>
        
        <h2><?php echo $this->t('report_mysql');?></h2>
        <?php 
        $td_class = 'error';
        $version=$this->getMysqlVersion();
        if ($version===false||version_compare($version, $iConfig['mysql']['mysql_version'], ">="))
        {
            $td_class = 'v';
        }
        if($td_class == 'error')
        {
            $is_error=true;
        }
        ?>
        <table width="600" cellpadding="3" border="0">
            <tbody>
                <tr class="h"><th width="200"><?php echo $this->t('report_module');?></th><th><?php echo $this->t('report_module_local');?></th><th><?php echo $this->t('report_module_desired');?></th><th><?php echo $this->t('report_module_hosting');?></th></tr>
    <tr><td class="e"><?php echo $this->t('report_mysql_version');?></td><td class="<?php echo $td_class;?>"><?php echo ($version!==false)?$version:'Undefined';?></td><td class="<?php echo $td_class;?>"><?php echo $iConfig['mysql']['mysql_version'];?></td><td class="<?php echo $td_class;?>"><?php echo $iConfig['mysql']['mysql_version'];?></td></tr>
            </tbody>
        </table>

        <!--<h2><?php echo $this->t('report_paths');?></h2>
        <table width="600" cellpadding="3" border="0">
            <tbody>
                <tr class="h"><th width="400"><?php echo $this->t('report_path');?></th><th><?php echo $this->t('report_module_local');?></th><th><?php echo $this->t('report_module_desired');?></th></tr>
    <?php
        foreach ($iConfig['chmod_paths'] as $k => $v)
        {
            $td_class = 'error';
            
            if (empty($dataPaths[$k]['status'])) $td_class = 'v';

    echo '  <tr><td class="e">'. $k .'</td><td class="'. $td_class .'" title="'. $dataPaths[$k]['status'] .'">'. $dataPaths[$k]['mode'] .'</td><td class="'. $td_class .'">'. $v .'</td></tr>';
        }
    ?>
            </tbody>
        </table>-->
        
        <h2><?php echo $this->t('report_browser');?></h2>
        <table border="0" cellpadding="3" width="600">
        <tbody>
        <tr class="h">
        <th width="200"><?php echo $this->t('report_browser_test');?></th>
        <th><?php echo $this->t('report_module_local');?></th>
        <th><?php echo $this->t('report_module_desired');?></th>
        <th><?php echo $this->t('report_module_hosting');?></th>
        </tr>
        <noscript>
        <tr>
        <td class="e">JavaScript</td>
        <td class='error'><?php echo $this->t('report_browser_local');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        </tr>
        <tr class="browser_cookie">
        <td class="e">Cookie</td>
        <td class='error'><?php echo $this->t('report_browser_undefined');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        </tr>
        </noscript>
        <tr class="browser_js" style="display:none;">
        <td class="e">JavaScript</td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        </tr>
        <tr class="browser_cookie_disabled" style="display:none;">
        <td class="e">Cookie</td>
        <td class='error'><?php echo $this->t('report_browser_local');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        <td class='error'><?php echo $this->t('report_browser_desired');?></td>
        </tr>
        <tr class="browser_cookie_enabled" style="display:none;">
        <td class="e">Cookie</td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        <td class='v'><?php echo $this->t('report_browser_desired');?></td>
        </tr>
        </tbody></table>        
        <table <?php echo (!$is_error) ? "class='browser_control'" : "";?> width="600"><tr><td width="20" class="error"></td><td> - <?php echo sprintf($this->t('report_error_happens'), $iConfig['support_mail']);?></td></tr></table>
        <hr />
        <div id="copi"><a href="<?php echo $iConfig['support_site'];?>"><?php echo $this->t('copyright').date(" 2002 - Y ").$this->t('global_copy');?></a></div>
    </div>
    </body>
    </html>
    <?php
        ob_end_flush();
    }


    /**
     * output value
     *
     * @access  public
     * @param   field name
     * @return  value from POST array or predifined
     */
    function v($key='')
    {
        global $aValue;

        if (empty($key))                      return '';

        if (@array_key_exists($key, $_POST))
        {
          if (get_magic_quotes_gpc()) return stripslashes($_POST[$key]);
          else return $_POST[$key];
        }

        if (@array_key_exists($key, $aValue)) return $aValue[$key];
    }


    /**
    * Validates date format. Valid date format:
    *  A|B|C   - where ABC is sequence of dmy in any case and any order
    *  |       - delimiter (dot, slash or dash)
    *
    * @access  public
    * @param  string date format
    * @return  boolean validation result
    *
    * @author  Val Petruchek
    * @TODO    upgrade error reporting
    */
    function validateDateFormat($format)
    {
        if (strlen($format)!=5)
            return false;                                                                                           //length must be 5

        if ($format{1}!=$format{3})
            return false;                                                                                           //delimiter must be the same

        if ( ($format{1}!='.') && ($format{1}!='-') && ($format{1}!='/') )
            return false;                                                                                           //wrong delimiter

        if (!in_array(strtolower($format{0}.$format{2}.$format{4}),array('dmy','dym','mdy','myd','ydm','ymd')))
            return false;                                                                                           //wrong dmy combination

        return true;
    }

 /**
 * Replace #,<,>,& chars for #001,#002,#003,#004 in the string
 *
 * @param string $text
 * @return string
 */   
    function input_xml($text)
{
    if( function_exists('mb_eregi_replace'))
    {
        $text = mb_eregi_replace("#",'#001;',$text);
        $text = mb_eregi_replace("<",'#002;',$text);
        $text = mb_eregi_replace(">",'#003;',$text);
        $text = mb_eregi_replace("&",'#004;',$text);
    }
    else
    {
        $text = str_replace("#",'#001;',$text);
        $text = str_replace("<",'#002;',$text);
        $text = str_replace(">",'#003;',$text);
        $text = str_replace("&",'#004;',$text);
    }
    
    return $text;
}


    /**
    *  Write config files and import db from MySQL dump file
    *
    * @access  public
    * @param   array SYSTEM settings
    * @param   array SECURITY settings
    * @return  mixed TRUE or ERROR message
    */
    
    
    function writeConfig($aSystem, $aSecurity)
    {
        global $iConfig;

        $cfg_file = $aSystem['sys_path'] . 'system/application/config/.ht_sys_config.cfg';
        if (!is_writable($cfg_file))
        {
            return sprintf($this->t('e_file_write'), $cfg_file);                                                    // Error: The file is not writable
        }else{
            if (!$r = @fopen($cfg_file, 'r')) return sprintf($this->t('e_file_read'), $cfg_file);                   // Error: Cannot open file for reading

            $cfg_txt = fread($r, filesize($cfg_file));
            fclose($r);
            
            $pattern = "/<version>(.*)<\/version>/";                                                              // Replace password
            $cfg_txt = preg_replace($pattern, '<version>'.$aSystem['version'].'</version>', $cfg_txt);
            
            $pattern = "/<license_number>(.*)<\/license_number>/";                                                 // Replace LICENSE_NUMBER
            $cfg_txt = preg_replace($pattern, '<license_number>'.$aSystem['sys_license'].'</license_number>', $cfg_txt);
            
            $pattern = "/<(host)>(.*)<\/\\1>/";                                                                     // Replace host
            $cfg_txt = preg_replace($pattern, '<$1>'.$aSystem['db_host'].':'.$aSystem['db_port'].'</$1>', $cfg_txt);

            $pattern = "/<user>(.*)<\/user>/";                                                                      // Replace user
            $cfg_txt = preg_replace($pattern, '<user>'.$aSystem['db_user'].'</user>', $cfg_txt);

            $pattern = "/<password>(.*)<\/password>/";                                                              // Replace password
            $cfg_txt = preg_replace($pattern, '<password>'.$aSystem['db_pass'].'</password>', $cfg_txt);
            
            $pattern = "/<database>(.*)<\/database>/";                                                              // Replace database
            $cfg_txt = preg_replace($pattern, '<database>'.$aSystem['db_name'].'</database>', $cfg_txt);

            $pattern = "/<dbprefix>(.*)<\/dbprefix>/";                                                              // Replace dbprefix
            $cfg_txt = preg_replace($pattern, '<dbprefix>'.$aSystem['db_prefix'].'</dbprefix>', $cfg_txt);

            $pattern = "/<crypt_cookie_key>(.*)<\/crypt_cookie_key>/";                                              // Replace crypt_cookie_key
            $cfg_txt = preg_replace($pattern, '<crypt_cookie_key>'.$aSecurity['crypt_key'].'</crypt_cookie_key>', $cfg_txt);

            $pattern = "/<admin_email>(.*)<\/admin_email>/";                                                        // Replace admin_email
            $cfg_txt = preg_replace($pattern, '<admin_email>'.$aSecurity['admin_mail'].'</admin_email>', $cfg_txt);

            $pattern = "/<site_name>(.*)<\/site_name>/";                                                            // Replace site_name
            $cfg_txt = preg_replace($pattern, '<site_name>'.$this->input_xml($aSystem['sys_title']).'</site_name>', $cfg_txt);

            $pattern = "/<base_url>(.*)<\/base_url>/";                                                              // Replace base_url
            $cfg_txt = preg_replace($pattern, '<base_url>'.$aSystem['sys_url'].'</base_url>', $cfg_txt);

            $pattern = "/<(logout_redirect)>(.*)<\/\\1>/";                                                          // Replace logout_redirect
            $cfg_txt = preg_replace($pattern, '<$1>'.$aSystem['sys_url'].'user/main</$1>', $cfg_txt);

            $pattern = "/<(login_redirect)>(.*)<\/\\1>/";                                                           // Replace login_redirect
            $cfg_txt = preg_replace($pattern, '<$1>'.$aSystem['sys_url'].'user/main</$1>', $cfg_txt);

            $pattern = "/<absolute_path>(.*)<\/absolute_path>/";                                                    // Replace absolute_path
            $cfg_txt = preg_replace($pattern, '<absolute_path>'.$aSystem['sys_path'].'</absolute_path>', $cfg_txt);

            $pattern = "/<site_ip>(.*)<\/site_ip>/";                                                                // Replace site_ip
            $cfg_txt = preg_replace($pattern, '<site_ip>'.$aSystem['sys_ip'].'</site_ip>', $cfg_txt);

            $pattern = "/<date_format>(.*)<\/date_format>/";                                                        // Replace date_format
            $cfg_txt = preg_replace($pattern, '<date_format>'.$aSystem['date_format'].'</date_format>', $cfg_txt);

            $pattern = "/<demo_data>(.*)<\/demo_data>/"; 
                                                        // Replace demo_data
            $cfg_txt = preg_replace($pattern, '<demo_data>'.$aSystem['demodata'].'</demo_data>', $cfg_txt);

            if (!$w = fopen($cfg_file,'wb')) return sprintf($this->t('e_file_write'), $cfg_file);                    // Error: Cannot open file for writing
            fwrite($w, $cfg_txt); 
            fclose($w);
        }

/* create DB */
        $sql_rez = $this->writeDB($iConfig['db_install _dump'], $aSystem, $aSecurity);                              // UTF-8 without BOM !!!
        if ($sql_rez !== TRUE) return $sql_rez;                                                                     // Error: mysql_error

/* create .htaccess */
        $rez = $this->writeHTAccess($aSystem['sys_path'], $aSystem['hts_url']);                                     // UTF-8 without BOM !!!
        if ($rez !== TRUE) return $rez;        
        // Error: write_error
        
        $this->writeHTAccess($aSystem['sys_path']."install/", '',true);
        //echo "*****".$aSystem['sys_path']."install/.htaccess"."*****";
        //@unlink($aSystem['sys_path']."install/.htaccess");

/* Send Mail */
        $mailTo         = trim($aSecurity['admin_mail']);
        $mailFrom       = $iConfig['support_mail'];                                                                 // support mail
        $mailFromName   = $iConfig['support_co'];                                                                   // support company
        $mailSubject    = $this->t('mail_install_subj', FALSE);                                                     // Install mail subject
        $mailMessage    = $this->t('mail_install_login',FALSE) .' : '. $aSecurity['admin_name'] ."\n";              // Your login
        $mailMessage   .= $this->t('mail_install_pass', FALSE) .' : '. $aSecurity['admin_pass'] ."\n";              // Your pass
        $mailMessage   .= sprintf($this->t('mail_install_link'), $aSystem['sys_url'] .'admin/') ."\n";              // Your site link

        $r=mail($mailTo, $mailSubject, $mailMessage, 'From: '. $mailFromName .' <'. $mailFrom .'>');
        if(!$r)
        {
            return 'e_mail_send';
        }

        return TRUE;
    }


    /**
    *  Restore DB tables from dump file
    *
    * @access  public
    * @param   string MySQL-dump filename
    * @param   array - contains MySQL settings       [db_prefix, db_host, db_port, db_user, db_pass]
    * @param   array - contains Admin pass and name  [admin_name, admin_mail, admin_pass]
    * @return  mixed TRUE or ERROR message
    */
    function writeDB($dump_file, $aSystem, $aSecurity)
    {
        if (!$link = @mysql_connect($aSystem['db_host'] .':'. $aSystem['db_port'], $aSystem['db_user'], $aSystem['db_pass']))
            return 'e_mysql_connect';                                                                               // Error: MySQL connect

        if (!mysql_select_db($aSystem['db_name'], $link)) return 'e_mysql_no_db';                                   // Error: specified DB not exist

        if (!$r = @fopen($dump_file, 'r')) return 'e_mysql_file';                                                   // Error: Cannot open file for reading

        $sql_query = '';
        $q         = 0;
        $aTemp     = array();



$sql_statements =  file_get_contents($dump_file);
$arr_sql =  preg_split('/;[\n|\n\r]+/',$sql_statements);
        
        mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
        foreach ($arr_sql as $query)
        {
            $q++;
            $sql_query = str_replace('db_prefix_', $aSystem['db_prefix'], $query);

            if ( ! empty($sql_query))
            {
                $sql_rez    = mysql_query($sql_query);

                if ( ! $sql_rez) return mysql_error() ."<p>Query #". $q ."<hr />". $sql_query ."<hr /></p>";
            }

        }

/*
        while(($str = fgets($r, 4096)) !== false)
        {
        	if (empty($str) || preg_match("/^(#|--|COMMIT)/", $str))
            {
                $sql_query.= $str;
                if (!empty($sql_query))
                {
                    $q++;
                    $sql_query  = str_replace('db_prefix_', $aSystem['db_prefix'], $sql_query);
                    $sql_rez    = mysql_query($sql_query);

                    if (!$sql_rez) return mysql_error() ."<p>Query #". $q ."<hr />". $sql_query ."<hr /></p>";
                    $aTemp[] = $sql_query;
                    $sql_query = '';
                }
            }else{
                $sql_query .= trim($str);
            }
        }
        print_r($aTemp);
*/
// Add Super Admin
        $s = $aSecurity;
        $sql_query = sprintf("UPDATE %sAdmins SET `login`='%s', `pwd`=md5('%s'), `last_online`=NOW(), `email`='%s' WHERE `id`='1'",
                                          $aSystem['db_prefix'], $s['admin_name'], $s['admin_pass'], $s['admin_mail']);
        $sql_rez = mysql_query($sql_query);

        if (!$sql_rez) return 'e_mysql_admin_add';

// If no error - return TRUE
        return TRUE;
        fclose($r);
        mysql_close($link);
    }


    /**
    *  Write .htaccess files
    *
    * @param   string sys_path
    * @param   string sys_url
    * @access  public
    * @return  mixed TRUE or ERROR message
    */
    function writeHTAccess($sys_path, $sys_url, $empty=false)
    {
        $ht_file = $sys_path .'.htaccess';
        if (!$w = @fopen($ht_file, 'w')) return sprintf($this->t('e_file_write'), $ht_file);                        // Error: Cannot open file for reading
    
$cfg_txt = '## Need Secure v2 ##
Options -indexes
order allow,deny
allow from all
Satisfy any
php_value auto_prepend_file none

<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteCond $1 !^(install/js|install/img|install/css|js|index\.php|upgrade|img|css|posters|swf|protect\.php|cron\.php)
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
DirectoryIndex index.html';

        $cfg_txt = !$empty ? $cfg_txt : ""; 

        fwrite($w, $cfg_txt);
        fclose($w);
        return TRUE;
    }

    /**
    *  Step sequence management
    *
    * @param   integer current
    * @access  public
    * @return  void
    */
    function step_sequence($current)
    {
        $sys_lang =isset($_GET['lang']) ? '?lang='.$_GET['lang'] : "";
        session_start();
        if(!isset($_SESSION['step']) || intval($_SESSION['step'])==0)
        {
            header("Location: index.php".$sys_lang);
            exit();
        }
        else if(($current-intval($_SESSION['step']))!=1)
        {
            header("Location: step".((intval($_SESSION['step']))+1>5 ? 5 : (intval($_SESSION['step'])+1)).".php".$sys_lang);
            exit();
        }
    }
}



/* End of file ktest.class.php */
