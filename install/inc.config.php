<?php
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
| Config array
+-------------------------------------------------------------------------------------------------------------------
*/

$iConfig['default_lng']         = 'en';                                                                             // Language code from ISO 639-1

$iConfig['db_host']             = 'localhost';                                                                      // Default host
$iConfig['db_port']             = '3306';                                                                           // Default port
$iConfig['db_prefix']           = 'pm_';                                                                            // Default prefix
$iConfig['db_install _dump']    = 'dump.sql';                                                                       // file with MySQL dump, UTF-8 without BOM !!!

$iConfig['date_format']         = 'm/d/y';                                                                          // Default date format

$iConfig['support_mail']        = 'support@primadg.com';
$iConfig['support_co']          = 'Prima DG Ltd';
$iConfig['support_site']        = 'http://primadg.com';

$iConfig['user_security_pass']  = 6;                                                                                // Length of auto-generated USER password
$iConfig['user_security_key']   = 20;                                                                               // Length of auto-generated crypt_cookie_key

$iConfig['server_modules']      = array('mod_env',
                                        'mod_rewrite');

$iConfig['server_modules_optional']     = array(array('mod_auth_basic','mod_auth'),
                                        'mod_proxy');                                        
                                        
$iConfig['server_info']         = array('system','configure_command','virtual_directory_support','server_api',
                                        'apache_version','server_name','server_port','server_addr','remote_addr',
                                        'server_administrator','accept_language',
                                        'http_host','server_protocol','request_uri','script_name');

$iConfig['php_modules']         = array('php_version'       => '4.4.3',
                                        'curl_support'      => 'enabled',
                                        'gd_support'        => 'enabled',
                                        'multibyte_support' => 'enabled');
                                        
//$iConfig['php_modules_optional']         = array('openssl_support'   => 'enabled');
$iConfig['php_modules_optional']         = array();                                        
$iConfig['mysql']               = array('mysql_version'     => '4.1.22-standard'                           );
                                        
                                                                                                                    // ?! fsockopen ?!
$iConfig['chmod_paths']         = array('_protect/ht_cookie'                            => '0777',
                                        '_protect'                                      => '0777',
                                        '_protect/ht_pwd'                               => '0777',
                                        'posters'                                       => '0777',
                                        'posters/original'                              => '0777',
                                        'posters/previews'                              => '0777',
                                        '.htaccess'                                     => '0666',
                                        'install/.htaccess'                             => '0666',
                                        'system/application/config/.ht_sys_config.cfg'  => '0666');

/* End of file inc.config.php */