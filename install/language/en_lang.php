<?php
/**
 * Prima Members
 *
 * The next generation web site protection system
 *
 * @package     Install
 * @author      Prima DG Dev Team
 * @copyright   Copyright (c) 2012, Prima DG.
 * @link        http://primadg.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/* Messages for kTest->testReport()                             ***************************************************/
$lang['report_title']               = 'System compatibility test';
$lang['report_mod_php']             = 'PHP modules check';
$lang['report_module']              = 'Module';
$lang['report_module_local']        = 'Local Value';
$lang['report_module_desired']      = 'Desired Value';
$lang['report_paths']               = 'CHMODs';
$lang['report_path']                = 'Path';
$lang['report_mod_server']          = 'Server Modules';
$lang['report_module_fail']         = 'Absent';
$lang['report_module_ok']           = 'Present';
$lang['report_browser_test']        = 'Browser capabilities';
$lang['report_browser']             = 'Browser compatibility test';
$lang['report_browser_local']       = 'disabled';
$lang['report_browser_desired']     = 'enabled';
$lang['report_error_happens']       = 'Error happens, try to fix it or contact our <a href="mailto:%s">support team</a>';
$lang['report_warning_happens']     = 'The absense of these features will result in the Prima Members functionality limitation';

/* Install page A (Welcome and Preinstall test)                 ***************************************************/
$lang['a_title']                    = 'Welcome to Prima Members';


/* Install page B (ToS and Agreements)                          ***************************************************/
$lang['b_title']                    = 'Terms of service';

$lang['b_label_agree']              = 'I agree';


/* Install page C (Title and System settings)                   ***************************************************/
$lang['c_title']                    = 'System settings';

$lang['c_label_license']           = 'License code';
$lang['c_legend_general']           = 'General settings';
$lang['c_label_title']              = 'Site title';
$lang['c_label_url']                = 'Script URL';
$lang['c_label_path']               = 'Absolute path';
$lang['c_label_ns1path']            = 'NeedSecure 1 absolute path';
$lang['c_label_ip']                 = 'IP address';

$lang['c_legend_db']                = 'Database settings';
$lang['c_label_dbhost']             = 'DB host [port]';
$lang['c_label_dbuser']             = 'DB username';
$lang['c_label_dbpass']             = 'DB password';
$lang['c_label_dbname']             = 'DB name';
$lang['c_label_dbprefix']           = 'DB table prefix';

$lang['c_legend_demodata']          = 'Demo Data settings';
$lang['c_label_demodata']           = ' Load demo members?';
$lang['c_help_demodata']          = 'When you load demo members, demo-user "user" and demo-administrators "admin1" and "admin2" will be loaded, too. 
For security purposes, we do not recommend to load these settings. If you load the settings, we recommend you to change access details for the demo administrators or remove them.';

$lang['c_legend_time']              = 'Date-time settings';
$lang['c_label_dateformat']         = 'Date format';
$lang['c_label_timeshift']          = 'Time shift';

$lang['c_help_autodetect']          = 'The '. $lang['c_label_url'] .', '. $lang['c_label_path'] .' and '. $lang['c_label_ip'] .' was detected automatically';
$lang['c_help_dateformat']          = 'Example: m/d/y; Valid separators: slash, dot, dash<br />
                                       Chars: d (2 digits), D (1 digit), m (2 digits), M (1 digit), y (2 digits),Y (4 digits)';


/* Install page D (Admin name and e-mail)                       ***************************************************/
$lang['d_title']                    = 'Admin settings';

$lang['d_legend_admin']             = 'Admin settings';
$lang['d_label_name']               = 'Super-Admin name';
$lang['d_label_pass']               = 'Super-Admin password';
$lang['d_label_mail']               = 'Super-Admin e-mail';
$lang['d_account_comment']          = 'You can change your password after the initial login in the account settings.';

/* Install page Finish (Finish message)                         ***************************************************/
$lang['f_title']                    = 'Final page';
$lang['f_upgrade']                  = 'Upgrade';
$lang['f_upgrade_text']             = '<p></p>';


/* Global                                                       ***************************************************/
$lang['global_copy']                = 'Prima DG Ltd';
$lang['copyright']                  = '&copy;';
$lang['global_next']                = 'Next >>';
$lang['global_required']            = 'The required field';
$lang['global_autodetect_language'] = 'Language was detected by the system, You can change it : ';
$lang['global_go']                  = '>>';
$lang['global_call_support']        = 'Error happens, try to fix it or contact our <a href="mailto:%s">support team</a>';


/* Mailer                                                       ***************************************************/
$lang['mail_install_subj']          = 'NS installation is completed';
$lang['mail_install_login']         = 'Your login';
$lang['mail_install_pass']          = 'Your pass';
$lang['mail_install_link']          = 'Your site - %s';


/* Warning messages                                             ***************************************************/
$lang['w_cgi_module']              = 'PHP is running on a server as CGI. PHP-prepend protection method will be disabled for directories.';
$lang['w_not_apache']              = 'Unfortunately an all-in-one work (functionality) on other servers than Apache cannot be guaranteed.';
$lang['mod_proxy_optional']        = 'Once the protection methods based on the Cookie-based Mod_Rewrite method will not be available.';
$lang['mod_auth_basic / mod_auth_optional']        = 'One of the protection methods based on the WWW Authentication method will not be available.';

/* Error messages                                               ***************************************************/
$lang['e_title']                    = 'ERROR: '; 
$lang['e_license_fail']             = 'Licence key must contain 32 characters (only Latin letters and numbers)';
$lang['e_chmod_fail']               = 'Cannot change the mode of %s.';
$lang['e_permition_fail']           = 'Cannot read  %s file permissions.';
$lang['e_missing_file']             = 'The %s is absent.';
$lang['e_chmod_deny']               = 'chmod() is not permitted.';
$lang['e_precheck']                 = 'During the precheck some errors were detected';
$lang['e_not_empty']                = 'This field must be filled';
$lang['e_login']                    = 'The field name must start with a latin character, have only latin letters, numbers or underline character (_). The field should be from 5 to 31 characters long.';
$lang['e_date_format']              = 'Wrong date format';
$lang['e_email_fail']               = 'Enter the correct e-mail';
$lang['e_file_read']                = 'Cannot open file(<strong>%s</strong>) for reading';
$lang['e_file_write']               = 'Cannot open file(<strong>%s</strong>) for writing';
$lang['e_mail_send']                = 'Cannot send e-mail';
$lang['e_apache_modules']           = 'No Apache modules loaded';
$lang['e_module_fail']              = 'Absent';
$lang['e_not_exist']                = 'This path does not exist';


/* MySQL errors */
$lang['e_mysql_connect']            = 'Cannot connect to SQL server';
$lang['e_mysql_version']            = 'Unsupported version of MySQL server (4.1.22-standard more)';
$lang['e_mysql_create_db']          = 'Cannot create DB';
$lang['e_mysql_latin']              = 'The field name must have only latin letters, numbers or underline character (_).';
$lang['e_mysql_nodb']               = 'The specified DB does not exist';
$lang['e_mysql_table_exist']        = 'The specified DB exists and contains the same tables, delete tables manualy or use '. $lang['c_label_dbprefix'];
$lang['e_mysql_file']               = 'Cannot open MySQL-dump file for reading';
$lang['e_mysql_import']             = 'Cannot import SQL-dump';
$lang['e_mysql_admin_add']          = 'Cannot create SuperAdmin record';
/* End of file en_lang.php */
