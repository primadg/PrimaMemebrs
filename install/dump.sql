-- MySQL dump 10.11
--
-- Host: dev.primadg    Database: ns2dump
-- ------------------------------------------------------
-- Server version	5.0.37-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `db_prefix_Access_levels`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Access_levels` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(64) default NULL,
  `ACL` int(11) default NULL,
  `ML` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Account_info`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Account_info` (
  `id` int(11) NOT NULL auto_increment,
  `account_type` int(11) NOT NULL,
  `account_name` varchar(255) default NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(64) default NULL,
  `company` varchar(64) default NULL,
  `address1` varchar(64) default NULL,
  `address2` varchar(64) default NULL,
  `address3` varchar(64) default NULL,
  `city` varchar(64) default NULL,
  `state` varchar(2) default NULL,
  `country` varchar(2) default NULL,
  `zip` varchar(10) default NULL,
  `telnocc` varchar(3) default NULL,
  `telno` varchar(12) default NULL,
  `alttelnocc` varchar(3) default NULL,
  `alttelno` varchar(12) default NULL,
  `faxnocc` varchar(3) default NULL,
  `faxno` varchar(12) default NULL,
  `customerlangpref` varchar(2) default NULL,
  `forceuse` tinyint(4) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Account_status`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Account_status` (
  `user_id` int(11) NOT NULL default '0',
  `expire` date default NULL,
  `ac_code` char(64) default NULL,
  `suspended` tinyint(1) NOT NULL default '0',
  `approve` tinyint(1) NOT NULL default '0',
  `activate` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `added` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `suspend_reason_id` int(11) default NULL,
  `expired` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Add_fields`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Add_fields` (
  `id` int(11) NOT NULL auto_increment,
  `req` tinyint(4) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `def_value` varchar(64) default NULL,
  `check_rule` varchar(255) default NULL,
  `val` text,
  `taborder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Admin_logs`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Admin_logs` (
  `id` int(11) NOT NULL auto_increment,
  `action` varchar(255) default NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ip` varchar(15) default NULL,
  `admin_id` int(11) default NULL,
  `details` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Admins`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Admins` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '''''',
  `pwd` varchar(32) NOT NULL default '''''',
  `access_id` int(11) NOT NULL default '0',
  `main` tinyint(1) NOT NULL default '0',
  `pwd_code` varchar(64) NOT NULL default '''''',
  `on_line` tinyint(1) NOT NULL default '0',
  `last_online` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL default '''''',
  `remind_code` varchar(64) default NULL,
  `language_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `remind_code` (`remind_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Banned_ip`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Banned_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(32) NOT NULL default '''''',
  `reason` varchar(255) default NULL,
  PRIMARY KEY  (`ip`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Blocked_ip`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Blocked_ip` (
  `ip` char(32) NOT NULL default '''''',
  `expired` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Coupon_group`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Coupon_group` (
  `id` int(11) NOT NULL auto_increment,
  `use_per_user` tinyint(4) default NULL,
  `cnt` int(11) default NULL,
  `time_limit` tinyint(4) NOT NULL default '0',
  `start_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL default '0000-00-00 00:00:00',
  `discount_percent` tinyint(4) default NULL,
  `discount_value` decimal(10,2) default NULL,
  `code_length` tinyint(3) default NULL,
  `locked` tinyint(1) NOT NULL default '0',
  `available_use` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Coupon_groups_products`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Coupon_groups_products` (
  `product_id` int(11) NOT NULL default '0',
  `coupon_group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`coupon_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Coupons`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Coupons` (
  `id` int(11) NOT NULL auto_increment,
  `coupon_code` varchar(255) default NULL,
  `coupon_group_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Coupons_users_2delete`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Coupons_users_2delete` (
  `user_id` int(11) NOT NULL default '0',
  `coupon_id` int(11) NOT NULL default '0',
  `when` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `product_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`coupon_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Dir_products`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Dir_products` (
  `product_id` int(11) NOT NULL default '0',
  `dir_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`dir_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Dirs`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Dirs` (
  `id` int(11) NOT NULL auto_increment,
  `fs_path` text NOT NULL,
  `http_path` text NOT NULL,
  `name` varchar(255) NOT NULL default '''''',
  `method` enum('mod_rewrite_standard','mod_rewrite_cookies','www_auth','php_prepend') NOT NULL default 'mod_rewrite_standard',
  `last_protect_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Email_domains`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Email_domains` (
  `domain` char(255) NOT NULL default '''''',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Email_history`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Email_history` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email_tpl_id` int(10) unsigned default NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `email` varchar(255) default NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_type` enum('admin','user') NOT NULL default 'admin',
  `priority` enum('system','newsletter') NOT NULL default 'system',
  `replace_values` text,
  `tpl_subject` text,
  `tpl_text` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Email_queue`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Email_queue` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email_tpl_id` int(10) unsigned default NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `email` varchar(255) default NULL,
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_type` enum('admin','user') NOT NULL default 'admin',
  `priority` enum('system','newsletter') NOT NULL default 'system',
  `replace_values` text,
  `tpl_subject` text,
  `tpl_text` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Host_plans`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Host_plans` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `type_domen` varchar(255) NOT NULL,
  `packages` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Host_plans_products`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Host_plans_products` (
  `product_id` int(11) NOT NULL,
  `host_plan_id` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Host_subscription`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Host_subscription` (
  `subscr_id` int(11) NOT NULL,
  `name_domen` varchar(255) default NULL,
  PRIMARY KEY  (`subscr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Interface_language`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Interface_language` (
  `key_name` varchar(255) NOT NULL default '''''',
  `language_id` int(11) NOT NULL default '0',
  `content` text,
  `section` enum('undef','admin','user','both') NOT NULL default 'undef',
  `_last_used` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`key_name`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Ip_access_log`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Ip_access_log` (
  `id` int(11) NOT NULL auto_increment,
  `ip` char(15) NOT NULL default '''''',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Language_data`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Language_data` (
  `language_id` int(11) NOT NULL default '0',
  `object_type` tinyint(4) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `descr` text NOT NULL,
  `object_id` int(11) NOT NULL default '0',
  `language_add` text,
  PRIMARY KEY  (`language_id`,`object_type`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Languages`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Languages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '''''',
  `is_default` tinyint(1) NOT NULL default '0',
  `lang_code` varchar(16) NOT NULL default '''''',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Member_groups`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Member_groups` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Member_groups_members`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Member_groups_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Member_groups_products`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Member_groups_products` (
  `group_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `available` tinyint(4) default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_News`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_News` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `members_only` tinyint(4) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `sid` varchar(255) default NULL,
  `special_news` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Pages`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Pages` (
  `id` int(11) NOT NULL auto_increment,
  `sid` varchar(255) NOT NULL default '''''',
  `taborder` int(11) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `show_in_menu` tinyint(3) NOT NULL default '1',
  `members_only` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Prices`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Prices` (
  `id` int(11) NOT NULL default '0',
  `day` decimal(10,2) NOT NULL default '0.00',
  `month` decimal(10,2) NOT NULL default '0.00',
  `month3` decimal(10,2) NOT NULL default '0.00',
  `month6` decimal(10,2) NOT NULL default '0.00',
  `year` decimal(10,2) NOT NULL default '0.00',
  `unlimit` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Product_discount`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Product_discount` (
  `id` int(11) NOT NULL auto_increment,
  `discount` decimal(10,2) NOT NULL default '0.00',
  `discount_type` tinyint(1) NOT NULL default '0',
  `cumulative` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Product_groups`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Product_groups` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Product_product_group`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Product_product_group` (
  `product_group_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_group_id`,`product_id`),
  KEY `FKProduct_pr722005` (`product_group_id`),
  KEY `FKProduct_pr693997` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Products`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Products` (
  `id` int(11) NOT NULL auto_increment,
  `is_recouring` tinyint(1) default NULL,
  `blocked` tinyint(1) NOT NULL default '0',
  `image` char(254) default NULL,
  `group_id` int(11) default NULL,
  `free` tinyint(4) NOT NULL default '0',
  `closed` tinyint(1) NOT NULL default '0',
  `product_type` tinyint(3) unsigned NOT NULL default '1',
  `special_offers` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Protection`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Protection` (
  `user_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  `subscr_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`product_id`,`subscr_id`),
  KEY `product_id` (`product_id`,`subscr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Subscription_info`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Subscription_info` (
  `id` int(11) NOT NULL,
  `account_type` int(11) NOT NULL,
  `account_name` varchar(255) default NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(64) default NULL,
  `company` varchar(64) default NULL,
  `address1` varchar(64) default NULL,
  `address2` varchar(64) default NULL,
  `address3` varchar(64) default NULL,
  `city` varchar(64) default NULL,
  `state` varchar(2) default NULL,
  `country` varchar(2) default NULL,
  `zip` varchar(10) default NULL,
  `telnocc` varchar(3) default NULL,
  `telno` varchar(12) default NULL,
  `alttelnocc` varchar(3) default NULL,
  `alttelno` varchar(12) default NULL,
  `faxnocc` varchar(3) default NULL,
  `faxno` varchar(12) default NULL,
  `customerlangpref` varchar(2) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Subscriptions`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Subscriptions` (
  `id` int(11) NOT NULL auto_increment,
  `cdate` date NOT NULL default '0000-00-00',
  `expire_date` date NOT NULL default '0000-00-00',
  `almost_expired` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `trial_period_type` varchar(6) default NULL,
  `trial_price` decimal(10,2) default NULL,
  `trial_period_value` tinyint(1) default NULL,
  `regular_period_type` varchar(7) default NULL,
  `regular_period_value` tinyint(1) default NULL,
  `regular_price` decimal(10,2) default NULL,
  `user_info_id` int(11) NOT NULL default '0',
  `currency_code` varchar(3) default NULL,
  `coupon_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Suspend_reasons`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Suspend_reasons` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_System_emails`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_System_emails` (
  `id` int(11) NOT NULL auto_increment,
  `email_key` varchar(255) NOT NULL default '''''',
  `email_type` enum('admin','user','newsletter') NOT NULL default 'admin',
  `name` varchar(255) NOT NULL default '''''',
  `replace_keys` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_System_info`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_System_info` (
  `id` int(11) NOT NULL auto_increment,
  `content` text,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_System_settings`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_System_settings` (
  `config_key_hash` varchar(32) NOT NULL,
  `config_key` longtext NOT NULL,
  `config_value` text,
  PRIMARY KEY  (`config_key_hash`),
  UNIQUE KEY `config_key_hash` (`config_key_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Transactions`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Transactions` (
  `id` int(11) NOT NULL auto_increment,
  `summ` decimal(10,2) default NULL,
  `info` text,
  `completed` int(11) default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `pay_system_id` tinyint(1) default NULL,
  `subscription_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Trial`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Trial` (
  `id` int(11) NOT NULL auto_increment,
  `price` decimal(10,2) NOT NULL default '0.00',
  `period_type` varchar(5) NOT NULL default '0',
  `period_value` tinyint(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Used_trials`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Used_trials` (
  `user_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_User_add_fields`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_User_add_fields` (
  `user_id` int(11) NOT NULL default '0',
  `field_value` text,
  `field_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`field_id`),
  KEY `FKUser_add_f368155` (`user_id`),
  KEY `FKUser_add_f306955` (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_User_info`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_User_info` (
  `id` int(11) NOT NULL auto_increment,
  `billing_name` varchar(50) NOT NULL default '''''',
  `street` varchar(50) NOT NULL default '''''',
  `city` varchar(50) NOT NULL default '''''',
  `state_code` varchar(2) NOT NULL default '''''',
  `zip` varchar(10) NOT NULL default '0',
  `country_code` varchar(2) NOT NULL default '''''',
  `phone` varchar(20) NOT NULL default '''''',
  `additional` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_User_logins`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_User_logins` (
  `ip` char(15) NOT NULL default '''''',
  `login_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ip`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_User_logs`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_User_logs` (
  `id` int(11) NOT NULL auto_increment,
  `ip` char(15) default NULL,
  `http_referer` char(255) default NULL,
  `url` char(255) NOT NULL default '''''',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL default '0',
  `product_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db_prefix_Users`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db_prefix_Users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '''''',
  `pass` varchar(64) NOT NULL default '''''',
  `email` varchar(255) NOT NULL default '''''',
  `language_id` int(11) default NULL,
  `name` varchar(32) NOT NULL default '''''',
  `last_name` varchar(32) NOT NULL default '''''',
  `on_line` tinyint(1) default NULL,
  `last_online` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `remind_code` varchar(64) default NULL,
  `sec_code` varchar(255) NOT NULL default '''''',
  `login_redirect` varchar(2048) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `remind_code` (`remind_code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-08-03 15:06:13
-- MySQL dump 10.11
--
-- Host: dev.primadg    Database: ns2dump
-- ------------------------------------------------------
-- Server version	5.0.37-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `db_prefix_Access_levels`
--

LOCK TABLES `db_prefix_Access_levels` WRITE;
/*!40000 ALTER TABLE `db_prefix_Access_levels` DISABLE KEYS */;
INSERT INTO `db_prefix_Access_levels` VALUES (1,'Supreme_level',255,511),
(2,'Access level 1',255,48),
(3,'Access level 2',147,0);
/*!40000 ALTER TABLE `db_prefix_Access_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Account_info`
--

LOCK TABLES `db_prefix_Account_info` WRITE;
/*!40000 ALTER TABLE `db_prefix_Account_info` DISABLE KEYS */;
INSERT INTO `db_prefix_Account_info` VALUES (1,1,'Domain registration profile',2,'DEMO user','DEMO user company','4502 Jay St NE','','','Washington','DC','US','20019-3729','202','3961812','','','','','en',0),
(2,2,'Billing profile',2,'DEMO user','','4502 Jay St NE','','','Washington','DC','US','20019-3729','202','3961812','','','','','',1);
/*!40000 ALTER TABLE `db_prefix_Account_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Account_status`
--

LOCK TABLES `db_prefix_Account_status` WRITE;
/*!40000 ALTER TABLE `db_prefix_Account_status` DISABLE KEYS */;
INSERT INTO `db_prefix_Account_status` VALUES (1,'0000-00-00',NULL,0,1,1,0,'2008-12-26 05:44:49',NULL,0),
(2,'0000-00-00',NULL,0,1,1,0,'2008-12-26 05:50:57',NULL,0);
/*!40000 ALTER TABLE `db_prefix_Account_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Add_fields`
--

LOCK TABLES `db_prefix_Add_fields` WRITE;
/*!40000 ALTER TABLE `db_prefix_Add_fields` DISABLE KEYS */;
INSERT INTO `db_prefix_Add_fields` VALUES (1,0,1,'demo','0','',1),
(2,0,4,'demo','0','',2);
/*!40000 ALTER TABLE `db_prefix_Add_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Admin_logs`
--

LOCK TABLES `db_prefix_Admin_logs` WRITE;
/*!40000 ALTER TABLE `db_prefix_Admin_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Admin_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Admins`
--

LOCK TABLES `db_prefix_Admins` WRITE;
/*!40000 ALTER TABLE `db_prefix_Admins` DISABLE KEYS */;
INSERT INTO `db_prefix_Admins` VALUES (1,'super_admin','ed49c3fed75a513a79cb8bd1d4715d57',1,0,'1111',1,'2009-01-12 07:08:02','hello@primadg.com','67184d448e69d41fbf50832b7f4087dd76eceb53323acf9e00549f46cd454339',1),
(2,'admin1','e00cf25ad42683b3df678c61f42c6bda',2,0,'\'\'',0,'2008-12-25 05:37:45','hello@primadg.com',NULL,NULL),
(3,'admin2','c84258e9c39059a89ab77d846ddab909',3,0,'\'\'',1,'2008-12-27 02:23:39','hello@primadg.com',NULL,NULL);
/*!40000 ALTER TABLE `db_prefix_Admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Banned_ip`
--

LOCK TABLES `db_prefix_Banned_ip` WRITE;
/*!40000 ALTER TABLE `db_prefix_Banned_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Banned_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Blocked_ip`
--

LOCK TABLES `db_prefix_Blocked_ip` WRITE;
/*!40000 ALTER TABLE `db_prefix_Blocked_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Blocked_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Coupon_group`
--

LOCK TABLES `db_prefix_Coupon_group` WRITE;
/*!40000 ALTER TABLE `db_prefix_Coupon_group` DISABLE KEYS */;
INSERT INTO `db_prefix_Coupon_group` VALUES (1,100,10,0,'2008-12-26 06:02:18','0000-00-00 00:00:00',10,'0.00',5,0,100);
/*!40000 ALTER TABLE `db_prefix_Coupon_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Coupon_groups_products`
--

LOCK TABLES `db_prefix_Coupon_groups_products` WRITE;
/*!40000 ALTER TABLE `db_prefix_Coupon_groups_products` DISABLE KEYS */;
INSERT INTO `db_prefix_Coupon_groups_products` VALUES (2,1),
(3,1),
(4,1),
(5,1);
/*!40000 ALTER TABLE `db_prefix_Coupon_groups_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Coupons`
--

LOCK TABLES `db_prefix_Coupons` WRITE;
/*!40000 ALTER TABLE `db_prefix_Coupons` DISABLE KEYS */;
INSERT INTO `db_prefix_Coupons` VALUES (1,'53BA4',1),
(2,'C9795',1),
(3,'C346C',1),
(4,'7A41D',1),
(5,'E6257',1),
(6,'AEDA9',1),
(7,'1B2C7',1),
(8,'76603',1),
(9,'3B4E9',1),
(10,'E267E',1);
/*!40000 ALTER TABLE `db_prefix_Coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Coupons_users_2delete`
--

LOCK TABLES `db_prefix_Coupons_users_2delete` WRITE;
/*!40000 ALTER TABLE `db_prefix_Coupons_users_2delete` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Coupons_users_2delete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Dir_products`
--

LOCK TABLES `db_prefix_Dir_products` WRITE;
/*!40000 ALTER TABLE `db_prefix_Dir_products` DISABLE KEYS */;
INSERT INTO `db_prefix_Dir_products` VALUES (1,1),
(2,2),
(3,2),
(4,3),
(5,3);
/*!40000 ALTER TABLE `db_prefix_Dir_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Dirs`
--

LOCK TABLES `db_prefix_Dirs` WRITE;
/*!40000 ALTER TABLE `db_prefix_Dirs` DISABLE KEYS */;
INSERT INTO `db_prefix_Dirs` VALUES (1,'/home/needsecu/public_html/demo2/dir_for_protect/dir1/','http://www.demo.primadg.com/dir_for_protect/dir1/','protected directory 1','www_auth','0000-00-00 00:00:00'),
(2,'/home/needsecu/public_html/demo2/dir_for_protect/dir2/','http://www.demo.primadg.com/dir_for_protect/dir2/','protected directory 2','mod_rewrite_cookies','0000-00-00 00:00:00'),
(3,'/home/needsecu/public_html/demo2/dir_for_protect/dir3/','http://www.demo.primadg.com/dir_for_protect/dir3/','protected directory 3','mod_rewrite_cookies','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `db_prefix_Dirs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Email_domains`
--

LOCK TABLES `db_prefix_Email_domains` WRITE;
/*!40000 ALTER TABLE `db_prefix_Email_domains` DISABLE KEYS */;
INSERT INTO `db_prefix_Email_domains` VALUES ('denied.com',2),
('trusted.com',1);
/*!40000 ALTER TABLE `db_prefix_Email_domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Email_history`
--

LOCK TABLES `db_prefix_Email_history` WRITE;
/*!40000 ALTER TABLE `db_prefix_Email_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Email_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Email_queue`
--

LOCK TABLES `db_prefix_Email_queue` WRITE;
/*!40000 ALTER TABLE `db_prefix_Email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Email_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Host_plans`
--

LOCK TABLES `db_prefix_Host_plans` WRITE;
/*!40000 ALTER TABLE `db_prefix_Host_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Host_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Host_plans_products`
--

LOCK TABLES `db_prefix_Host_plans_products` WRITE;
/*!40000 ALTER TABLE `db_prefix_Host_plans_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Host_plans_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Host_subscription`
--

LOCK TABLES `db_prefix_Host_subscription` WRITE;
/*!40000 ALTER TABLE `db_prefix_Host_subscription` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Host_subscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Interface_language`
--

LOCK TABLES `db_prefix_Interface_language` WRITE;
/*!40000 ALTER TABLE `db_prefix_Interface_language` DISABLE KEYS */;
INSERT INTO `db_prefix_Interface_language` VALUES ('admin_menu_host_plans',1,'Hosting plans','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_id',1,'Id','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_host_plan_name',1,'Name','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_number_of_products',1,'Nubmer of products','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_add_host_plan',1,'Add','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_header_subject',1,'Hosting plans settings','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_header_general',1,'General settings','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_host_host',1,'Host','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_port',1,'Port','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_username',1,'Username','admin','2009-05-20 12:29:28'),
('admin_host_plans_settings_password',1,'Remote access key','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_btn_test_connection',1,'Test connection','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_btn_save',1,'Save','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_btn_cancel',1,'Cancel','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_table_empty',1,'No hosting plans','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_description',1,'List of hosting plans','admin','2008-12-19 14:24:53'),
('admin_host_plans_settings_header_comment',1,'Settings of hosting plans','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_host_plan_name_is_empty',1,'Hosting plan name is empty','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_type_domen_host_plan_url_can_not_be_is_empty',1,'Hosting plan url can not be empty','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_packages_host_plan_can_not_be_is_empty',1,'Hosting plan can not be empty','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_unable_validation_failed_host_plan',1,'Validation failed','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_db_unable_to_insert',1,'Database error. Unable to insert','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_db_unable_to_update',1,'Database error. Unable to update','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_db_unable_to_delete',1,'Database error. Unable to delete','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_protection_assotiated_products_exist',1,'Product already exist','admin','2008-12-19 14:24:53'),
('admin_host_plans_msg_cancel',1,'Action cancel','admin','2008-12-19 14:24:53'),
('admin_host_plans_msg_are_you_sure',1,'Are you sure you want to do this?','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_protection_host_plan_is_not_protectable',1,'Hosting plan is not protectable','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_protection_host_plan_is_already_protected',1,'Hosting plan is already protected','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_protection_host_plan_path_is_too_long',1,'Hosting plan path is too long','admin','2008-12-19 14:24:53'),
('admin_host_plans_reprotect_begin',1,'Reprotect begin','admin','2008-12-19 14:24:53'),
('admin_host_plans_reprotect_progress',1,'Reprotect in progress','admin','2008-12-19 14:24:53'),
('admin_host_plans_reprotect_end',1,'Reprotect is end','admin','2008-12-19 14:24:53'),
('admin_host_plans_host_plan_has_been_added',1,'Hosting plan has been added','admin','2008-12-19 14:24:53'),
('admin_host_plans_host_plan_has_been_updated',1,'Hosting plan has been updated','admin','2008-12-19 14:24:53'),
('admin_host_plans_host_plan_has_been_deleted',1,'Hosting plan has been deleted','admin','2008-12-19 14:24:53'),
('admin_host_plans_host_plan_has_been_reprotected',1,'Hosting plan has been check OK!','admin','2009-05-29 01:59:12'),
('admin_host_plans_list_btn_delete_anyway',1,'Delete anyway','admin','2008-12-19 14:24:53'),
('admin_menu_hosted_settings',1,'Hosting plans settings','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_action',1,'Action','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_action_edit',1,'Edit hosting plan','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_action_reprotect',1,'To check availability','admin','2009-01-10 14:44:35'),
('admin_host_plans_list_action_delete',1,'Delete','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_assotiated_products',1,'Products','admin','2008-12-19 14:24:53'),
('admin_product_hosted_save_host_plans',1,'Hosting plan','admin','2009-05-14 10:34:03'),
('admin_product_hosted_add_host_plans',1,'Hosting plan','admin','2009-05-14 10:34:03'),
('admin_host_plans_not_available_packages',1,'Hosting plan not available','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_edit_title',1,'Edit title','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_title',1,'Add title','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_edit_description',1,'Edit description','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_description',1,'Add description','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_packages',1,'Select package','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_host_plan_name',1,'Hosting plan name','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_protected_host_plan_url',1,'Domain type','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_btn_edit',1,'Edit','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_btn_add',1,'Add','admin','2008-12-19 14:24:53'),
('admin_host_plans_item_add_btn_cancel',1,'Cancel','admin','2008-12-19 14:24:53'),
('admin_host_plans_list_title',1,'Hosting plans list','admin','2008-12-19 14:24:53'),
('user_cart_hosted_domain',1,'Name domain','user','2008-04-07 15:24:53'),
('admin_config_mainpage_header_subject',1,'Main page design manager','admin','2008-12-19 14:24:53'),
('admin_config_mainpage_header_comment',1,'Here you can change the number of news, products and add some text to the main page','admin','2008-12-19 14:24:53'),
('admin_config_mainpage_pages',1,'Product\r\namount','admin','2009-05-25 08:41:38'),
('admin_config_mainpage_news',1,'News amount','admin','2008-12-19 14:24:53'),
('admin_config_mainpage_adm_text',1,'Admin message','admin','2008-12-19 14:24:53'),
('admin_config_mainpage_btn_save',1,'save','admin','2008-12-19 14:24:53'),
('user_config_mainpage_header_subject',1,'Special offers','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_group',1,'Group','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_subscr_type',1,'Type of subscription','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_subscr_type_one_time',1,'one time','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_subscr_type_recurring',1,'Recurring','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_trial_duration',1,'Trial duration','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_trial_period_type_day',1,'Day','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_trial_period_type_week',1,'Week','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_trial_period_type_month',1,'Month','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_trial_period_type_year',1,'Year','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_day',1,'Day','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_week',1,'Week','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_month',1,'Month','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_year',1,'Year','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_order_button',1,'order','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_no_available',1,'No products available ','user','2008-12-19 14:24:53'),
('user_config_mainpage_label_latest_news',1,'Latest News','user','2008-12-19 14:24:53'),
('admin_lang_manager_header_subject_undefined',1,'Subject undefined','admin','2009-04-10 14:24:53'),
('admin_lang_manager_msg_er_subject',1,'Error subject','admin','2009-04-10 14:24:53'),
('admin_lang_manager_msg_er_undefined_type',1,'Undefined type','admin','2009-04-10 14:24:53'),
('admin_config_ban_ip_tooltip_edit',1,'Edit','admin','2009-04-10 14:24:53'),
('admin_config_ban_ip_tooltip_delete',1,'Delete','admin','2009-04-10 14:24:53'),
('admin_lang_manager_header_comment_undefined',1,'Comment undefined','admin','2009-04-10 14:24:53'),
('admin_global_setup_msg_er_login_page',1,'The value must correspond to a valid URL','admin','2008-12-19 14:24:53'),
('admin_global_setup_field_login_page',1,'Alternative login page:','admin','2009-05-20 02:45:39'),
('admin_global_setup_ttip_login_page',1,'If you use an alternative login form, i.e. the form integrated to a web page, specify this page here. This field brings out an alternative authorization page where to members will be redirected if they go to Prima DG or a protected content without passing the authorization.','admin','2009-05-20 02:45:39'),
('user_login_email',1,'Login(Email):','user','2008-12-19 14:24:53'),
('user_login_error_email_login_failed',1,'Login(Email) or password is incorrect.','user','2008-12-19 14:24:53'),
('admin_member_settings_email_as_login',1,'Use email as login','admin','2008-12-19 14:24:53'),
('admin_member_settings_email_as_login_ttip',1,'Once this box is checked, email address will be used as a username. The username field will be hidden. Existing members will be authenticated by their registered usernames. ','admin','2009-05-20 12:25:56'),
('admin_admin_edit_msg_er_unaccepted_currency',1,'Unaccepted currency','admin','2008-12-19 14:24:53'),
('admin_admin_edit_msg_er_undefined_currency',1,'Undefined currency','admin','2008-12-19 14:24:53'),
('admin_admin_edit_msg_er_undefined_action',1,'Undefined action','admin','2008-12-19 14:24:53'),
('admin_manage_pages_msg_er_tos',1,'This page cannot be removed','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_msg_er_not_sended',1,'Message not sended','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_msg_sended_ok',1,'The message is sent successfully','admin','2008-12-19 14:24:53'),
('admin_header_developers_notification',1,'This form allows you to send messages about bugs in the system or other technical questions to the products developers.','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_installed_title',1,' Prima Members is installed successfully. Would you like to send the system developers your server configurations? This will help them improve the software compatibility and support. No confidential data will be transmitted and Prima DG, Ltd will not misuse or sell these details under any circumstances.','admin','2009-05-29 07:41:43'),
('admin_developer_dialog_installed_subject',1,'Is installed successfully','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_msg_er_email',1,'Invalid email','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_title',1,'You can use this form to send a message to the system developers. No confidential data will be transmitted and Prima Members, Ltd will not misuse or sell these details under any circumstances.','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_from',1,'From:','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_attach',1,'Attach screenshot','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_name',1,'Subject:','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_description',1,'Description:','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_send_browser_info',1,'Send browser info:','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_error_send_server_info',1,'Send server info:','admin','2008-12-19 14:24:53'),
('admin_developer_dialog_details',1,'Details','admin','2008-12-19 14:24:53'),
('admin_btn_send',1,'send','admin','2008-12-19 14:24:53'),
('admin_config_language_translate_user_xml',1,'User part XML','admin','2008-12-19 14:24:53'),
('admin_config_language_translate_or',1,'or','admin','2008-12-19 14:24:53'),
('admin_config_language_translate_admin_xml',1,'Admin part XML','admin','2008-12-19 14:24:53'),
('admin_config_language_translate_all_xml',1,'All XML','admin','2008-12-19 14:24:53'),
('admin_config_lang_editor_import_from_file',1,'Import from file','admin','2008-12-19 14:24:53'),
('admin_currency_cad',1,'Canadian Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_eur',1,'Euros','admin','2008-12-19 14:24:53'),
('admin_currency_gbp',1,'British Pounds','admin','2008-12-19 14:24:53'),
('admin_currency_usd',1,'U.S. Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_jpy',1,'Yen','admin','2008-12-19 14:24:53'),
('admin_currency_aud',1,'Australian Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_nzd',1,'New Zealand Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_chf',1,'Swiss Francs','admin','2008-12-19 14:24:53'),
('admin_currency_hkd',1,'Hong Kong Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_sgd',1,'Singapore Dollars','admin','2008-12-19 14:24:53'),
('admin_currency_sek',1,'Swedish Kroner','admin','2008-12-19 14:24:53'),
('admin_currency_dkk',1,'Danish Kroner','admin','2008-12-19 14:24:53'),
('admin_currency_pln',1,'Polish Zloty','admin','2008-12-19 14:24:53'),
('admin_currency_nok',1,'Norwegian Kroner','admin','2008-12-19 14:24:53'),
('admin_currency_huf',1,'Hungarian Forints','admin','2008-12-19 14:24:53'),
('admin_currency_czk',1,'Czech Koruny','admin','2008-12-19 14:24:53'),
('admin_currency_ils',1,'Israeli Shekels','admin','2008-12-19 14:24:53'),
('admin_currency_mxn',1,'Mexican Pesos','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_er_undefined_currency',1,'Undefined currency!','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_er_unaccepted_currency',1,'This payment system cannot be activated because it does not support the active currency.','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_currency_changed',1,'The currency has been changed successfully.','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_not_compatible',1,'Because this currency is not supported, payment systems have been disabled','admin','2008-12-19 14:24:53'),
('admin_payment_system_current_currency',1,'Current currency:','admin','2008-12-19 14:24:53'),
('admin_payment_system_btn_currency_change',1,'Change','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_er_undefined_action',1,'Undefined action!','admin','2008-12-19 14:24:53'),
('admin_payment_system_msg_really_change',1,'Attention! On a currency change, the existing products cost will not be recalculated. Also, no changes to the cost of existing subscriptions are available. Change currency only if you are sure you will be able to make corrections to the existing products and subscriptions. Besides, payment systems not supporting this currency will be disabled.','admin','2008-12-19 14:24:53'),
('admin_member_settings_simple_menu',1,'Member simple menu','admin','2008-12-19 14:24:53'),
('admin_msg_no_data',1,'No items','user','2008-12-19 14:24:53'),
('admin_log_currency_modify',1,'Currency modify','admin','2008-12-19 14:24:53'),
('admin_menu_member_group_list',1,'Member Groups','admin','2008-12-19 14:24:53'),
('admin_member_group_add_header_subject',1,'Adding member group','admin','2008-12-19 14:24:53'),
('directories_reprotect_all',1,'reprotect all','admin','2008-12-19 14:24:53'),
('directories_reprotect_begin',1,'Reprotection begined!','admin','2008-12-19 14:24:53'),
('directories_reprotect_progress',1,'Already processed ','admin','2008-12-19 14:24:53'),
('directories_reprotect_end',1,'Reprotection finished. Reprotected ','admin','2008-12-19 14:24:53'),
('demo_server_info_disabled',1,'hidden in demo','undef','2008-12-19 14:24:53'),
('admin_upgrade_denied_title',1,'Upgrading','admin','2008-12-19 14:24:53'),
('admin_upgrade_denied_heading',1,'Updates are not allowed!','admin','2008-12-19 14:24:53'),
('admin_upgrade_denied_message',1,'Sorry, updates can only be performed by a super administrator. Please log into the system if you are a super administrator.','admin','2008-12-19 14:24:53'),
('admin_config_lang_editor_empty_list',1,'No items','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_html_header_subject',1,'Edit/Add email html template','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_html_header_comment',1,'Edit/Add emails template using the form below','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_name',1,'Name','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_action',1,'Action','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_header_subject',1,'Email Templates','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_header_comment',1,'Edit email templates here','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_add_button',1,'Add','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_header_subject',1,'Edit/Add email template:','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_header_comment',1,'Edit/Add emails template using the form below','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_name',1,'Name:','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_subject',1,'Subject:','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_message',1,'Message:','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_msg_er_name',1,'Must not be empty!(1-100)','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_msg_er_subject',1,'Must not be empty!(1-100)','admin','2008-12-19 14:24:53'),
('admin_newsletter_tmpl_template_msg_er_message',1,'Must not be empty!(1-5000)','admin','2008-12-19 14:24:53'),
('admin_newsletter_send_email_btn_return',1,'back','admin','2008-12-19 14:24:53'),
('admin_newsletter_email_history_tpl_priority_newsletter',1,'newsletter','admin','2008-12-19 14:24:53'),
('admin_newsletter_email_history_tpl_priority_system',1,'system','admin','2008-12-19 14:24:53'),
('admin_newsletter_email_history_btn_return',1,'back','admin','2008-12-19 14:24:53'),
('user_news_latest_label',1,'Latest News','user','2008-12-19 14:24:53'),
('perpage_name',1,'per page','both','2009-01-12 08:58:32'),
('perpage_btn',1,'go','both','2009-01-12 08:58:32'),
('admin_logging_action_any',1,'any action','admin','2009-01-10 15:19:39'),
('admin_btn_save_security',1,'save','admin','2009-01-10 15:38:23'),
('menu_user_active_information',1,'Account Information','undef','0000-00-00 00:00:00'),
('user_news_latest_label_desc',1,'  View the latest news here','user','2008-12-19 14:24:53'),
('user_news_button_all_news',1,'All News','user','2008-12-19 14:24:53'),
('user_menu_registration',1,'Registration','user','2009-01-12 03:55:38'),
('user_menu_news',1,'News','user','2009-01-12 03:55:38'),
('user_menu_login',1,'Login','user','2009-01-12 03:55:38'),
('user_menu_products',1,'Products','user','2009-01-12 03:55:38'),
('user_menu_site_info',1,'Site Info','user','2009-01-12 03:55:38'),
('user_menu_join_us',1,'Join Us','user','2009-01-12 03:55:38'),
('user_header_title',1,'Need Secure','user','2008-12-04 10:50:50'),
('user_header_home',1,'home','user','2009-01-10 10:31:08'),
('user_header_language',1,'Language','user','2009-01-12 03:55:38'),
('user_news_all_label',1,'All News','user','2008-12-18 12:54:09'),
('user_login_login',1,'Login:','user','2009-01-12 03:55:38'),
('user_login_password',1,'Password:','user','2009-01-12 03:55:38'),
('user_login_remember_me',1,'Remember me:','user','2009-01-12 03:55:38'),
('user_login_remind_password',1,'Remind password','user','2009-01-12 03:55:38'),
('user_login_input_error',1,'Input error. Login or password is incorrect.','user','0000-00-00 00:00:00'),
('user_login_error_restricted_area',1,'Access to media denied.\nPlease authorize.','user','2008-12-27 04:21:00'),
('admin_config_add_fields_label',1,'Additional fields','admin','2009-01-10 14:44:35'),
('user_login_remote_addr_error',1,'Remote address error.','user','0000-00-00 00:00:00'),
('admin_config_add_fields_labe_descr',1,'Add additional fields to the signup and members profile form','admin','2009-05-20 12:26:48'),
('admin_config_add_fields_table_title',1,'Title','admin','2009-01-10 14:44:35'),
('admin_config_add_fields_table_field_type',1,'Field type','admin','2009-01-10 14:44:35'),
('admin_config_add_fields_table_check_rule',1,'Check rule','admin','2009-01-10 14:44:35'),
('admin_config_add_fields_table_action',1,'Action','admin','2009-01-10 14:44:35'),
('pager_name',1,'page','both','2009-01-10 16:39:02'),
('admin_config_add_fields_button_add',1,'add fields','admin','2009-01-10 14:44:35'),
('user_login_banned_ip',1,'Your IP address is banned.','user','2008-10-31 15:03:51'),
('user_news_show_label_desc',1,' News details','user','2008-12-19 14:44:52'),
('user_news_show_label',1,'Show News','user','2008-12-19 14:44:52'),
('admin_config_add_fields_add_label',1,'Create additional fields','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_add_fields_title',1,'Field title:','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_add_fields_descr',1,'Field Description:','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_add_fields_require',1,'Required mark:','admin','2009-01-10 14:44:40'),
('user_login_blocked_ip',1,'Your IP address is blocked. Time left: ','user','0000-00-00 00:00:00'),
('admin_config_add_fields_add_descr',1,'Create a new field','admin','2008-12-26 08:49:43'),
('user_login_seconds',1,'seconds','user','0000-00-00 00:00:00'),
('user_login_banned_ip_reason',1,'Reason:','user','2008-10-31 15:03:51'),
('user_login_banned_ip_admin_email',1,'Admin email:','user','2008-10-31 15:03:51'),
('user_login_not_exists',1,'Incorrect login or password.','user','0000-00-00 00:00:00'),
('admin_config_manage_news_label',1,'Manage News','admin','2008-09-18 14:47:44'),
('admin_config_manage_news_label_desc',1,'Here you can manage the news content for registered and unregistered users','admin','2008-09-18 14:47:44'),
('admin_config_manage_news_table_content',1,'Content','admin','2008-09-18 14:47:44'),
('admin_config_manage_news_table_date',1,'Date','admin','2008-09-18 14:47:44'),
('admin_config_manage_news_table_action',1,'Action','admin','2008-09-18 14:47:44'),
('admin_config_manage_news_button_add_news',1,'add news','admin','2008-09-18 14:47:44'),
('user_login_not_active',1,'Your account is inactive.','user','0000-00-00 00:00:00'),
('admin_config_manage_news_add_label',1,'Create News','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_label_desc',1,'Fill all fields which are marked with *','admin','2008-09-17 17:17:32'),
('user_login_not_approve',1,'Your account is not approved.','user','0000-00-00 00:00:00'),
('admin_config_manage_news_add_fields_date',1,'Date','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_fields_header',1,'Header','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_fields_content',1,'Full Description','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_fields_for_members',1,'For members only','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_button_add',1,'add','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_add_button_cancel',1,'cancel','admin','2008-09-17 17:17:32'),
('user_login_suspend',1,'Your account is suspended','user','0000-00-00 00:00:00'),
('user_login_suspend_reason',1,'Reason:','user','0000-00-00 00:00:00'),
('user_login_expired',1,'Your account is expired.','user','0000-00-00 00:00:00'),
('user_login_expiration_date',1,'Expiration date','user','0000-00-00 00:00:00'),
('user_login_input_capcha',1,'Input code from the image','user','2008-12-22 11:30:56'),
('user_login_incorrect_capcha',1,'Code from the image is incorrect.','user','0000-00-00 00:00:00'),
('admin_config_manage_news_add_fields_brief',1,'Brief Description','admin','2008-09-17 17:17:32'),
('page_edit_head_subj',1,'Page Edit','undef','0000-00-00 00:00:00'),
('page_edit_term_use',1,'Terms of use','undef','0000-00-00 00:00:00'),
('page_edit_privacy_police',1,'Privacy policy','undef','0000-00-00 00:00:00'),
('page_edit_succ_pay_page',1,'Successful payment page','undef','0000-00-00 00:00:00'),
('page_edit_canc_pay_page',1,'Cancel payment page','undef','0000-00-00 00:00:00'),
('page_edit_not_reg_page',1,'The page is registered sucessfully','undef','0000-00-00 00:00:00'),
('page_edit_confirm_error_page',1,'Activation error page','undef','0000-00-00 00:00:00'),
('admin_config_manage_news_edit_label',1,'Edit News','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_label_desc',1,'Here you can change news properties','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_fields_date',1,'Date','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_fields_header',1,'Header','admin','0000-00-00 00:00:00'),
('admin_config_manage_news_edit_fields_brief',1,'Brief','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_fields_content',1,'Content','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_fields_for_members',1,'For members only','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_button_save',1,'Save','admin','2008-09-17 17:13:53'),
('admin_config_manage_news_edit_button_cancel',1,'Cancel','admin','2008-09-17 17:13:53'),
('admin_log_directory_protected',1,'Protected directory','admin','2008-12-23 16:16:29'),
('admin_config_manage_news_edit_msg_success',1,'Changes were saved successfully','admin','2008-09-09 14:21:58'),
('admin_config_manage_news_add_msg_success',1,'News was added successfully','admin','2008-09-09 14:19:55'),
('admin_config_manage_news_error_empty_fields',1,'Not all required fields are filled','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_error_field_header_toolong',1,'Field Header is too long','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_error_field_brief_toolong',1,'Field Brief is too long','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_error_field_content_toolong',1,'Field Content is too long','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_error_field_date_wrong',1,'Field Date is wrong','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_error_field_members_only_wrong',1,'Field For Members Only has a wrong value','admin','2008-09-17 17:17:32'),
('admin_config_manage_news_edit_error_id_empty',1,'Id value is empty','admin','0000-00-00 00:00:00'),
('admin_menu_additional_fields',1,'Additional Fields','admin','2009-01-12 08:57:39'),
('admin_config_add_fields_field_type',1,'Field type','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_field_type_text',1,'Text','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_field_type_select_single',1,'Select (single value)','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_field_type_select_multiple',1,'Select (multiple values)','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_field_type_textarea',1,'Text area','admin','2008-12-26 08:49:48'),
('admin_config_add_fields_field_type_radio',1,'RadioButtons','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_field_type_checkbox',1,'CheckBoxes','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_field_values',1,'Field Values','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_default_value',1,'Default value','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_check_rule',1,'Check rule','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_check_rule_not_empty',1,'Not empty','admin','2008-09-11 10:26:07'),
('admin_config_add_fields_check_rule_numbers_only',1,'Numbers only','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_check_rule_letters_only',1,'Letters only','admin','2008-09-11 10:26:07'),
('admin_config_add_fields_check_rule_email',1,'Email','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_check_rule_chars_interval',1,'Chars interval','admin','2008-09-11 10:26:07'),
('admin_config_add_fields_error_title',1,'Error: The Field title is empty.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_error_field_type',1,'Error: Field type is not choosen.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_add_field_success',1,'Field added successfully.','admin','2009-01-10 14:44:35'),
('user_login_suspend_reason_id2',1,'Your account is suspended!','user','0000-00-00 00:00:00'),
('admin_config_add_fields_add_field_error',1,'Error has occured.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_custom_error',1,'Error has occured.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_remove_field_success',1,'The field was removed successfully.','admin','2009-01-10 14:44:35'),
('admin_config_add_fields_remove_field_error',1,'Error has occured while removing the field.','admin','0000-00-00 00:00:00'),
('admin_config_add_fields_edit_field_success',1,'Field edited successfully.','admin','2009-01-10 14:44:35'),
('admin_config_add_fields_edit_field_error',1,'Error has occured.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_add_alt',1,'Create field','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_edit_alt',1,'Edit field.','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_edit_label',1,'Edit field:','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_edit_descr',1,'Edit the field','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_edit_submit',1,'edit','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_add_submit',1,'add','admin','2008-12-26 08:49:43'),
('admin_config_add_fields_remove_field_confirm',1,'Do you realy want to remove the field?','admin','2008-09-10 14:39:17'),
('admin_config_add_fields_cancel_button',1,'cancel','admin','2009-01-10 14:44:40'),
('user_registration_header',1,'Registration','user','2009-01-09 12:02:29'),
('user_registration_header_comment',1,'register your account','user','2009-01-09 12:02:29'),
('user_registration_field_login',1,'Login','user','2009-01-09 12:02:29'),
('user_registration_field_email',1,'Email','user','2009-01-09 12:02:29'),
('user_registration_field_fname',1,'First name','user','2009-01-09 12:02:29'),
('user_registration_field_lname',1,'Last name','user','2009-01-09 12:02:29'),
('user_registration_field_generate_password',1,'Generate password','user','2009-01-09 12:02:29'),
('user_registration_field_password',1,'Password','user','2009-01-09 12:02:29'),
('user_registration_field_password2',1,'Retype password','user','2009-01-09 12:02:29'),
('user_registration_email_exists_error',1,'This email address is not allowed.','user','2009-01-09 12:02:29'),
('user_registration_login_exists_error',1,'Such login already exists in the database','user','2009-01-09 12:02:29'),
('user_registration_submit_button',1,'Registration','user','2009-01-09 12:02:29'),
('user_news_all_label_desc',1,'View the news history here','user','2008-12-18 12:54:09'),
('user_news_all_table_date',1,'date','user','2008-12-18 12:54:09'),
('user_news_all_table_description',1,'description','user','2008-12-18 12:54:09'),
('user_news_all_table_subject',1,'Subjects','user','2008-12-18 12:54:09'),
('user_news_button_back',1,'Back','user','2008-12-19 14:44:52'),
('admin_config_ban_ip_label',1,'Ban List','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_label_desc',1,'This is the list of banned IP addresses','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_table_ban_ip',1,'Ban IP','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_table_ban_reason',1,'Ban reason','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_table_action',1,'Action','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_button_add',1,'add','admin','2009-01-10 16:51:49'),
('user_registration_error_login',1,'Login is incorrect.','user','2009-01-09 12:02:29'),
('user_registration_error_email',1,'Warning: Denied domain!','user','2009-01-09 12:02:29'),
('user_registration_error_fname',1,'Please check the First name field.','user','2009-01-09 12:02:29'),
('user_registration_error_lname',1,'The Last name field is empty.','user','2009-01-09 12:02:29'),
('user_registration_error_password',1,'Please check your password field.','user','2009-01-09 12:02:29'),
('user_registration_error_password_not_match',1,'Passwords do not match','user','2009-01-09 12:02:29'),
('admin_config_manage_news_error_action_add',1,'News is not added','admin','0000-00-00 00:00:00'),
('admin_config_manage_news_error_action_edit',1,'Changes are not saved','admin','0000-00-00 00:00:00'),
('admin_config_manage_news_error_action_delete',1,'News item is not deleted','admin','0000-00-00 00:00:00'),
('admin_config_ban_ip_error_ip_empty',1,'Error: IP is empty','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_error_action_delete',1,'Error: IP is not deleted','admin','0000-00-00 00:00:00'),
('user_registration_password_protection0',1,'Bad protection','user','2009-01-10 14:54:20'),
('user_registration_password_protection3',1,'Average protection','user','2009-01-10 14:54:20'),
('user_registration_password_protection4',1,'Good protection','user','2009-01-10 14:54:20'),
('user_registration_password_protection5',1,'Excellent protection','user','2009-01-10 14:54:20'),
('admin_config_ban_ip_error_empty_fields',1,'Error: All fields are required','admin','2008-12-23 13:54:25'),
('admin_config_ban_ip_error_field_ip_wrong',1,'Error: Ban IP is invalid','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_error_field_reason_toolong',1,'Error: Ban Reason is too long','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_error_field_ip_toolong',1,'Error: IP value is too long','admin','2008-12-23 13:54:25'),
('user_registration_password_not_match',1,'Passwords do not match','user','2009-01-10 14:54:20'),
('user_registration_password_is_match',1,'Passwords match','user','2009-01-10 14:54:20'),
('admin_config_ban_ip_error_ip_exists',1,'Error: This IP address already exists in the Database','admin','2009-01-10 16:51:49'),
('user_registration_email_not_valid_error',1,'Email address is not valid','user','2009-01-09 12:02:29'),
('user_login_registration_not_allowed',1,'Sorry, new member registration is not allowed at this time.','user','2008-12-02 12:48:39'),
('user_registration_login_not_valid_error',1,'Login must be 4-64 symbols, start with a character and include alpha-numeric values.','user','2009-01-09 12:02:29'),
('user_registration_db_error',1,'Registration error.','user','2008-10-01 16:46:38'),
('admin_member_control_add_member_label',1,'Add Member','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_label_desc',1,'Account information','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_login',1,'Login:','admin','2009-01-10 10:10:15'),
('admin_levels_access_category',1,'Access category','admin','2009-01-10 14:55:45'),
('admin_member_control_add_member_field_expiration_date',1,'Expiration date:','admin','2009-01-10 10:10:15'),
('admin_levels_name_level',1,'Name level','admin','2009-01-10 14:55:45'),
('admin_levels_header_comment',1,'Manage administrator accounts on this page','admin','2009-01-10 14:55:45'),
('admin_levels_header_subject',1,'Administrator accounts control','admin','2009-01-10 14:55:45'),
('admin_menu_level_list',1,'Access Level List','admin','2008-12-19 14:24:53'),
('admin_member_control_confirm_suspend_error_not_all_confirmed',1,'Error: Not all accounts are activated','admin','0000-00-00 00:00:00'),
('admin_coupon_coupon_groups_delete_msg_success',1,'Coupon Group was deleted successfully','admin','2008-12-08 14:20:22'),
('admin_member_control_add_member_field_email',1,'Email:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_generate_password',1,'Generate password:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_password',1,'Password:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_retype_password',1,'Retype password:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_first_name',1,'First name:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_last_name',1,'Last name:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_status',1,'Status:','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_field_additional_info',1,'Additional Info','admin','0000-00-00 00:00:00'),
('admin_member_control_add_member_button_add',1,'Add','admin','2009-01-10 10:10:15'),
('admin_member_control_add_member_button_cancel',1,'Cancel','admin','2009-01-10 10:10:15'),
('admin_member_control_error_action_member_add',1,'Error: Member is not added','admin','0000-00-00 00:00:00'),
('admin_member_control_member_list_label',1,'Member List','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_label_desc',1,'This is the list of all the members registered in the system','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_msg_added_success',1,'Member was added successfully','admin','2009-01-10 10:10:50'),
('admin_member_control_error_action_member_delete',1,'Error: Member is not deleted','admin','0000-00-00 00:00:00'),
('admin_member_control_error_member_id_empty',1,'Error: Member ID value is empty','admin','0000-00-00 00:00:00'),
('admin_newsletter_email_history_view_page_desc',1,'The contents of e-mail','admin','2008-11-11 11:33:11'),
('admin_member_control_error_empty_fields',1,'Error: Not all required fields are filled','admin','2009-01-10 10:10:50'),
('admin_member_control_error_field_login_toolong',1,'Error: Login field value is too long','admin','2009-01-10 10:10:15'),
('admin_member_control_error_field_login_tooshort',1,'Error: Login field value is too short','admin','2009-01-10 10:10:15'),
('admin_member_control_error_field_expiration_date_wrong',1,'Error: Expiration date field has a wrong value','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_email_toolong',1,'Error: Email field is too long','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_email_wrong',1,'Error: Email field is incorrect','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_password_toolong',1,'Error: Password field is too long','admin','0000-00-00 00:00:00'),
('admin_member_control_error_field_first_name_toolong',1,'Error: First name field value is too long','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_last_name_toolong',1,'Error: Last name field value is too long','admin','2009-01-10 10:10:45'),
('admin_member_control_error_passwords_coincidence',1,'Error: Field Password does not match the field Retype password ','admin','2009-01-10 10:10:50'),
('admin_member_control_error_field_email_exists',1,'Error: Email field value already exists','admin','2008-12-18 11:09:13'),
('admin_member_control_error_field_login_exists',1,'Error: Login field value already exists','admin','2008-11-21 11:05:56'),
('admin_member_control_error_field_login_wrong_chars',1,'Error: Login field contains wrong characters','admin','2009-01-10 10:10:15'),
('admin_member_control_confirm_suspend_success_confirmed',1,'Accounts were activated successfully','admin','2009-01-09 12:04:11'),
('admin_member_control_member_list_table_login',1,'Login','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_table_email',1,'Email','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_table_reg_date',1,'Reg. Date','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_table_subscriptions',1,'Subscriptions','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_table_status',1,'Status','admin','2008-12-19 14:24:53'),
('admin_design_manager_btn_preview',1,'Preview','admin','2009-01-10 14:44:26'),
('admin_member_control_member_list_table_action',1,'Action','admin','2009-01-10 10:10:41'),
('admin_design_manager_frontend',1,'Members area','admin','2009-01-10 14:44:26'),
('user_language_changed_page_header',1,'Language succesefully changed','user','2008-12-26 06:39:42'),
('admin_design_manager_frontend_unreg',1,'Unregistered member area','admin','2009-05-20 12:27:54'),
('user_login_submit_button',1,'Login','user','2009-01-12 03:55:38'),
('user_language_changed_page_you_can',1,'you can','user','2008-12-26 06:39:42'),
('admin_design_manager_header_comment',1,'Here you can change the appearance of the front end','admin','2009-01-10 14:44:26'),
('user_language_changed_page_go_back',1,'go back','user','2008-12-26 06:39:42'),
('admin_member_control_img_tooltip_approve',1,'Approve','admin','2008-12-23 13:53:52'),
('user_language_changed_page_or_wait',1,'or wait three seconds for auto redirection','user','2008-12-26 06:39:42'),
('admin_design_manager_btn_save',1,'Save','admin','2009-01-10 14:44:26'),
('admin_design_manager_header_subject',1,'Design Manager','admin','2009-01-10 14:44:26'),
('user_header_logout',1,'Logout','user','2009-01-10 10:31:08'),
('admin_menu_design_manager',1,'Design Manager','admin','2009-01-12 08:57:39'),
('user_header_hello',1,'Hello ','user','2009-01-10 10:31:08'),
('user_header_last_login',1,'Your last login was on ','user','2009-01-10 10:31:08'),
('product_save_poster_removed',1,'Poster successfully removed ','admin','2009-01-12 09:15:57'),
('user_logout_page_header',1,'Logout page','user','2009-01-10 10:31:08'),
('user_logout_page_you_can',1,'You can','user','2009-01-10 10:31:08'),
('user_logout_page_go_to_login',1,'go to the login page','user','2009-01-10 10:31:08'),
('user_logout_page_or_wait',1,'or wait three second for auto redirection','user','2009-01-10 10:31:08'),
('admin_msg_er_0015',1,'The content for this page in the selected language not found in the database','admin','2008-09-08 12:13:23'),
('user_redirect_page_you_can',1,'You can','user','2008-12-27 06:38:38'),
('user_redirect_page_go_to_url',1,'click here','user','2008-12-27 06:38:38'),
('user_redirect_page_or_wait',1,'or wait three second for auto redirection','user','2008-12-27 06:38:38'),
('admin_access_level_msg_er_not_updated',1,'List of access has not been changed!','admin','2008-12-26 08:43:40'),
('admin_edit_sys_template_msg_er_subject',1,'Must not be empty!(1-100)','admin','2009-01-12 08:57:33'),
('admin_edit_sys_template_msg_er_message',1,'Must not be empty!(1-5000)','admin','2009-01-12 08:57:33'),
('user_redirect_title',1,'Redirect','user','2008-12-27 06:38:38'),
('admin_access_level_msg_er_not_added',1,'List of access has not been added!','admin','2008-12-26 08:43:40'),
('user_registration_complete_activation_text',1,'Activation email was sent to your email address','user','2009-01-09 12:03:12'),
('admin_page_edit_content',1,'Page content','admin','0000-00-00 00:00:00'),
('admin_access_level_msg_er_empty_list',1,'At least one access category must be selected!','admin','2008-12-26 08:43:40'),
('user_registration_complete_go_to_login_page',1,'login page','user','2009-01-09 12:03:12'),
('admin_access_level_msg_er_not_found',1,'List of access was not found!','admin','2009-01-10 14:55:45'),
('user_registration_complete_title',1,'Registration complete','user','2009-01-09 12:03:12'),
('admin_img_tip_edit',1,'Edit','admin','2009-01-10 10:13:04'),
('user_activate_wrong_parameters',1,'Wrong parameters','user','2008-11-20 13:33:25'),
('admin_member_control_account_panel_member_info_msg_info_changed',1,'Account Information has been saved successfully','admin','2009-01-09 10:41:59'),
('admin_img_tip_delete',1,'Delete','admin','2009-01-10 10:13:04'),
('admin_member_control_member_list_search_link_panel_hide',1,'Search Panel Show/Hide','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_label_search_by',1,'Search by','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_by_select_option_login',1,'Login','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_by_select_option_first_name',1,'First name','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_by_select_option_last_name',1,'Last name','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_by_select_option_email',1,'Email','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_search_label_registration_date',1,'Registration date:','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_all_time',1,'All time','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_today',1,'Today','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_this_week',1,'This week','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_this_month',1,'This month','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_this_year',1,'This year','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_yesterday',1,'Yesterday','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_previous_week',1,'Previous week','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_previous_month',1,'Previous month','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_or_period_select_option_previous_year',1,'Previous year','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_button_search',1,'search','admin','2009-01-10 10:10:41'),
('user_activation_complete_title',1,'Activation completed','user','2008-11-21 11:16:59'),
('admin_code_generation_textarea_header',1,'HMTL code for login form','admin','2008-09-12 16:18:47'),
('user_activation_complete_text',1,'The activation is now complete','user','2008-11-21 11:16:59'),
('admin_code_generation_header_comment',1,'HMTL code generation for login form','admin','2008-09-12 16:18:47'),
('user_activation_complete_go_to_login_page',1,'Go to the login page','user','2008-11-21 11:16:59'),
('admin_member_control_suspend_reason_manage_table_action',1,'Action','admin','2009-01-10 10:13:04'),
('admin_member_control_approve_suspend_error_not_all_approved',1,'Error: Not all selected accounts are approved','admin','0000-00-00 00:00:00'),
('admin_member_control_approve_suspend_success_approved',1,'Accounts were approved successfully','admin','2008-12-03 11:12:38'),
('admin_member_control_approve_suspend_error_id_wrong',1,'Error: Wrong ID value','admin','0000-00-00 00:00:00'),
('admin_member_control_approve_suspend_error_not_all_suspended',1,'Error: Not all accounts are suspended','admin','0000-00-00 00:00:00'),
('admin_member_control_approve_suspend_success_suspend',1,'Accounts were suspended successfully','admin','2009-01-10 10:14:00'),
('admin_member_control_unsuspend_delete_error_id_wrong',1,'Error: Wrong ID value','admin','0000-00-00 00:00:00'),
('admin_member_control_unsuspend_delete_error_not_all_unsuspended',1,'Error: Not all accounts are unsuspended','admin','0000-00-00 00:00:00'),
('admin_member_control_unsuspend_delete_msg_success_unsuspended',1,'Accounts were unsuspended successfully','admin','2009-01-10 10:14:12'),
('admin_member_control_unsuspend_delete_msg_success_delete',1,'Accounts were deleted successfully','admin','0000-00-00 00:00:00'),
('admin_member_control_approve_suspend_label',1,'List of not approved members','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_label_desc',1,'Comment','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_table_login',1,'Login','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_table_name',1,'Name','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_table_suspend_reason',1,'Suspend Reason','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_table_action',1,'Action','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_suspend_reason_select_option_no_reason',1,'no reason','admin','2008-12-23 13:53:52'),
('admin_member_control_approve_suspend_button_approve',1,'Approve','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_button_suspend',1,'Suspend','admin','2009-01-10 10:12:59'),
('admin_member_control_approve_suspend_link_title_view_member_info',1,'View member info','admin','2008-12-23 13:53:52'),
('admin_member_control_confirm_suspend_label',1,'List of not activated members','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_label_desc',1,'Comment','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_table_login',1,'Login','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_table_name',1,'Name','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_table_action',1,'Action','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_link_title_view_member_info',1,'View member info','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_button_confirm',1,'Activate','admin','2009-01-10 10:13:52'),
('admin_member_control_confirm_suspend_button_suspend',1,'Suspend','admin','2009-01-10 10:13:52'),
('admin_member_control_unsuspend_delete_label',1,'List of suspended members','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_label_desc',1,'View the list of suspended members here','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_table_login',1,'Login','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_table_name',1,'Name','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_table_action',1,'Action','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_button_unsuspend',1,'Unsuspend','admin','2009-01-10 10:14:13'),
('admin_member_control_unsuspend_delete_button_delete',1,'Delete','admin','2009-01-10 10:14:13'),
('admin_member_control_member_info_error_member_not_exists',1,'Error: No such user in the database','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_error_wrong_id',1,'Error: Wrong ID value','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_label_desc',1,'Some description','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_view_error_member_not_exists',1,'Error: No such user in the database','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_view_error_wrong_id',1,'Error: Wrong ID value','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_view_label_desc',1,'View the selected member details','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_label',1,'View Member:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_block_title_user_info',1,'User info','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_block_title_summary',1,'Summary','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_login',1,'Login:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_email',1,'Email:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_name',1,'Name:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_additional_field',1,'Additional Field:','admin','0000-00-00 00:00:00'),
('admin_member_control_member_info_view_field_user_payments',1,'User Payments:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_payment_total',1,'Total Payments:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_active_subscribtions',1,'Active Subscriptions:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_registered_date',1,'Registration Date:','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_button_back',1,'Back','admin','2009-01-10 10:13:37'),
('admin_member_control_member_info_view_field_expiration_date',1,'Expiration Date:','admin','2009-01-10 10:13:37'),
('user_registration_add_fields_error_text_not_empty',1,'is empty','user','2008-12-08 13:11:39'),
('admin_btn_back',1,'back','admin','2009-01-10 10:13:04'),
('user_registration_add_fields_error_field_text',1,'Field','user','2009-01-10 10:10:45'),
('admin_member_control_suspend_reason_manage_btn_add_reason',1,'Add Reason','admin','2009-01-10 10:13:04'),
('user_registration_add_fields_error_text_numbers_only',1,'can contain numbers only','user','2008-12-08 13:11:39'),
('admin_member_control_suspend_reason_manage_table_reason',1,'Reason','admin','2009-01-10 10:13:04'),
('admin_member_control_suspend_reason_manage_table_name',1,'Name','admin','2009-01-10 10:13:04'),
('user_registration_add_fields_error_text_letters_only',1,'can contain letters only','user','2008-10-23 09:31:35'),
('admin_code_generation_header_subject',1,'Code Generation','admin','2008-09-12 16:18:47'),
('user_registration_add_fields_error_text_email',1,'must be a valid email address','user','2008-11-07 12:12:46'),
('admin_member_control_suspend_reason_manage_page_desc',1,'You can add, edit and delete any Suspend Reason from the list','admin','2009-01-10 10:13:04'),
('user_registration_add_fields_',1,'must contain letters only','user','0000-00-00 00:00:00'),
('admin_config_add_fields_check_rule_phone',1,'Phone','admin','2009-01-10 14:44:40'),
('admin_coupon_coupon_groups_delete_error_notdeleted',1,'Error: Coupon Group is not deleted','admin','0000-00-00 00:00:00'),
('user_registration_add_fields_error_text_phone',1,'must be a valid phone number','user','2009-01-10 10:10:45'),
('product_list_filter_button',1,'Search','admin','2009-01-10 10:02:05'),
('admin_member_control_account_panel_header_label_member',1,'Member:','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_header_label_login',1,'Login:','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_header_label_member_id',1,'Member ID:','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_header_tab_member_info',1,'Info','admin','2009-05-29 07:05:20'),
('admin_member_control_account_panel_header_tab_change_password',1,'Change Password','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_header_tab_user_payments_subscriptions',1,'Payments/Subscriptions','admin','2009-05-29 07:05:20'),
('admin_member_control_account_panel_header_tab_email_client',1,'Email Client','admin','2009-01-09 10:44:44'),
('admin_member_control_account_panel_header_tab_email_history',1,'Email History','admin','2009-01-09 10:44:44'),
('admin_member_control_account_panel_header_tab_access_log',1,'Access Log','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_change_password_title',1,'Change Password','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_change_password_desc',1,'Here you can change your password. If you want to generate a random password, check the \'random password\' option and submit the form without entering a new password.','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_change_password_field_random_password',1,'Random password','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_change_password_field_new_password',1,'New password','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_change_password_field_retype_new_password',1,'Retype the new password','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_change_password_button_update_password',1,'Update Password','admin','2009-01-10 10:10:50'),
('admin_member_control_account_panel_member_info_page_title',1,'Account Information','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_access_log_page_title',1,'Access Log','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_member_info_field_login',1,'Login:','both','2009-05-14 08:26:25'),
('admin_member_control_account_panel_member_info_field_expiration_date',1,'Expiration Date','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_field_email',1,'Email:','both','2009-05-14 08:26:25'),
('admin_member_control_account_panel_member_info_field_first_name',1,'First Name','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_field_last_name',1,'Last Name','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_field_status',1,'Status:','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_checkbox_label_approved',1,'approved','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_checkbox_label_confirmed',1,'activated','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_checkbox_label_suspended',1,'suspended','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_checkbox_label_additional_info',1,'Additional Info:','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_member_info_button_save',1,'Save','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_button_cancel',1,'Cancel','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_access_log_field_date',1,'Date:','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_access_log_table_date_time',1,'Date/Time','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_access_log_table_products',1,'products','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_access_log_table_url',1,'URL','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_access_log_table_ip',1,'IP','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_access_log_table_http_referrer',1,'HTTP referrer','admin','2009-01-10 10:10:55'),
('admin_member_control_account_panel_email_history_table_subject',1,'Subject','admin','2008-12-23 12:56:41'),
('admin_member_control_account_panel_email_history_table_from',1,'From','admin','2008-12-23 12:56:41'),
('admin_member_control_account_panel_email_history_table_date',1,'Date','admin','2008-12-23 12:56:41'),
('admin_member_control_account_panel_email_history_table_action',1,'Action','admin','2008-12-23 12:56:41'),
('admin_member_control_account_panel_email_history_page_title',1,'Email History','admin','2008-12-23 12:56:41'),
('admin_member_control_account_panel_email_history_img_alt_view',1,'View','admin','2008-12-03 11:41:23'),
('admin_member_control_account_panel_email_history_img_alt_delete',1,'Delete','admin','2008-12-03 11:41:23'),
('admin_member_control_suspend_reason_addedit_label_description_tip',1,'User will see this text in his/her email message','admin','2008-10-20 14:28:53'),
('user_registration_error_please_check',1,'Please check','user','2008-10-01 16:37:30'),
('admin_member_control_account_panel_email_history_view_label_to',1,'To:','admin','2008-11-13 12:41:26'),
('product_add_button_cancel',1,'Cancel','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_email_history_view_label_from',1,'From:','admin','2008-11-13 12:41:26'),
('admin_member_control_account_panel_email_history_view_label_subject',1,'Subject:','admin','2008-11-13 12:41:26'),
('admin_member_control_account_panel_email_history_view_label_message',1,'Message:','admin','2008-11-13 12:41:26'),
('admin_member_control_account_panel_email_history_view_button_back',1,'Back','admin','2008-11-13 12:41:26'),
('admin_member_control_account_panel_email_client_error_empty_field',1,'Error: Not all required fields are filled','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_email_client_error_template_id_wrong',1,'Error: Template field has a wrong value','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_error_email_toolong',1,'Error: The field From has too long value','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_error_email_wrong',1,'Error: The field From must have a valid email address','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_error_subject_toolong',1,'Error: Subject length must be from 1 to 254 characters','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_error_message_toolong',1,'Error: Message length must be from 1 to 65536 characters','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_success_send_email',1,'Email was sent successfully','admin','2008-11-11 13:28:50'),
('admin_member_control_account_panel_email_client_error_send_email',1,'Error: Email is not sent','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_email_client_page_title',1,'Send Email','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_field_template',1,'Template:','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_field_to',1,'To:','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_field_from',1,'From:','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_field_subject',1,'Subject:','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_field_message',1,'Message:','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_button_send',1,'Send','admin','2008-12-23 12:57:38'),
('admin_member_control_account_panel_email_client_button_add',1,'add','admin','2008-12-23 12:57:38'),
('user_registration_read_and_accept_tos',1,'Please read and accept','user','2009-01-09 12:02:29'),
('product_list_filter_all',1,'All','admin','2009-01-10 10:02:05'),
('product_add_button_add',1,'Add','admin','2008-12-26 06:12:26'),
('user_registration_tos_not_accept_error',1,'You must accept','user','2009-01-09 12:02:29'),
('product_list_table_add_button',1,'Add','admin','2009-01-10 10:02:05'),
('user_registration_tos',1,'TOS','user','2009-01-09 12:02:29'),
('admin_table_empty',1,'No items','admin','2008-12-26 03:06:03'),
('user_registration_tos_title',1,'Terms of service','user','2009-01-09 12:02:29'),
('product_list_table_block',1,'Block product','admin','2009-01-10 10:02:05'),
('user_registration_error_tos',1,'You must read and accept TOS','user','2009-01-09 12:02:29'),
('product_list_table_action',1,'Action','admin','2009-01-10 10:02:05'),
('admin_coupon_create_coupons_error_field_empty',1,'Error: All required fields must be filled','admin','2009-01-10 14:10:11'),
('user_registration_input_capcha',1,'Input the code from the image','user','2009-01-09 12:02:29'),
('product_list_table_type',1,'Payment type','admin','2009-01-10 10:02:05'),
('user_registration_capcha_error',1,'Please check the code','user','2009-01-09 12:02:29'),
('product_list_table_product_group',1,'Group','admin','2009-01-10 10:02:05'),
('user_registration_error_capcha',1,'Please check the code from the image','user','2009-01-09 12:02:29'),
('product_list_table_name',1,'Product name','admin','2009-01-10 10:02:05'),
('product_list_table_users_in',1,'Subscribers','admin','2009-01-10 10:02:05'),
('admin_member_control_account_panel_payments_page_title',1,'User Payments and Subscriptions','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_msg_add_success',1,'Invoice was added successfully','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_table_product',1,'Product','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_table_payment_system',1,'Payment system','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_table_transaction_id',1,'Transaction ID','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_table_price',1,'Price','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_table_status',1,'Status','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_link_add_payment',1,'Add Payment Manually','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_title',1,'Add invoice','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_field_product',1,'Product:','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_field_period',1,'Period:','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_field_payment_system',1,'Payment system:','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_field_transaction_id',1,'Transaction info:','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_form_field_price',1,'Price:','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_form_field_paid',1,'Paid:','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_form_button_add_invoice',1,'Add Invoice','admin','2009-01-10 10:10:59'),
('admin_menu_lang_editor',1,'Language Editor','admin','2009-01-12 08:57:39'),
('admin_config_language_list_header',1,'Language List','admin','2009-01-12 09:12:43'),
('admin_config_language_list_subheader',1,'Edit language details','admin','2009-01-12 09:12:43'),
('admin_config_lang_editor_rec_per_page',1,'records per page','admin','0000-00-00 00:00:00'),
('admin_config_lang_editor_page',1,'page','admin','0000-00-00 00:00:00'),
('admin_config_lang_editor_title_default',1,'Default','admin','2009-01-12 09:12:43'),
('admin_config_lang_editor_title_language',1,'Language','admin','2009-01-12 09:12:43'),
('admin_config_lang_editor_title_action',1,'Action','admin','2009-01-12 09:12:43'),
('product_add_page_title',1,'Add product','admin','2008-12-26 06:12:26'),
('product_add_page_descr',1,'Add product details using the below form','admin','2008-12-26 06:12:26'),
('product_add_product_name',1,'Name','admin','2008-12-26 06:12:26'),
('product_add_product_image',1,'Poster','admin','2008-12-26 06:12:26'),
('product_add_product_groups',1,'Groups','admin','2008-12-26 06:12:26'),
('product_add_product_descr',1,'Description','admin','2008-12-26 06:12:26'),
('product_add_product_price_day',1,'Day','admin','2008-12-26 06:12:26'),
('product_add_product_prices',1,'Prices','admin','2008-12-26 06:12:26'),
('product_add_product_price_month',1,'Month','admin','2008-12-26 06:12:26'),
('product_add_product_price_month3',1,'3 month','admin','2008-12-26 06:12:26'),
('product_add_product_price_month6',1,'six month','admin','2008-12-26 06:12:26'),
('product_add_product_price_year',1,'Year','admin','2008-12-26 06:12:26'),
('product_add_product_price_year5',1,'Five years','admin','2008-12-26 06:12:26'),
('product_add_product_recouring',1,'Recurring','admin','2008-12-26 06:12:26'),
('product_add_product_recouring_yes',1,'yes','admin','2008-12-26 06:12:26'),
('product_add_product_recouring_no',1,'no','admin','0000-00-00 00:00:00'),
('product_add_product_discount',1,'Discount Type','admin','2008-12-26 06:12:26'),
('product_add_product_discount_percent',1,'percent','admin','2008-12-26 06:12:26'),
('product_add_product_discount_value',1,'value','admin','2008-12-26 06:12:26'),
('product_add_product_trial_price',1,'Trial price','admin','2008-12-26 06:12:26'),
('product_add_product_trial_duration',1,'Trial period duration','admin','2008-12-26 06:12:26'),
('product_add_product_trial_duration_day',1,'day','admin','2008-12-26 06:12:26'),
('product_add_product_trial_duration_month',1,'month','admin','2008-12-26 06:12:26'),
('product_add_product_trial_duration_year',1,'year','admin','2008-12-26 06:12:26'),
('product_add_product_cumulative',1,'is discount cumulative','admin','0000-00-00 00:00:00'),
('admin_config_lang_editor_set_as_default',1,'Set as Default Language','admin','2009-01-12 09:12:43'),
('admin_config_lang_editor_add_language',1,'Add language','admin','2009-01-12 09:12:43'),
('product_add_error_name',1,'The Name field is empty or too large (1 - 255 chars)','admin','2008-12-26 06:12:26'),
('admin_config_lang_editor_set_def_lang_ok',1,'Default language is set to','admin','2008-12-24 07:29:37'),
('product_add_error_poster_ext',1,'File has an invalid extension (jpeg, jpg, gif is allowed)','admin','2008-12-26 06:12:26'),
('product_add_error_groups',1,'Please select product groups','admin','2008-12-26 06:12:26'),
('admin_config_lang_editor_set_def_lang_error',1,'Error has occured while setting default language','admin','0000-00-00 00:00:00'),
('product_add_error_poster_size',1,'Image file has an invalid size (1 mb max)','admin','2008-12-26 06:12:26'),
('product_add_error_descr',1,'Product description is empty or too large (max 65k length)','admin','2008-12-26 06:12:26'),
('product_add_error_price',1,'Some price value has an invalid format','admin','2008-12-26 06:12:26'),
('product_add_error_trial_price',1,'Trial price has an invalid format','admin','2008-12-26 06:12:26'),
('product_add_error_trial_period',1,'Only numbers are allowed for a trial period','admin','2008-12-26 06:12:26'),
('product_add_error_dirs',1,'Select the protected dirs','admin','2008-12-26 06:12:26'),
('product_add_error_discount_value',1,'Discount value has an invalid format','admin','2008-12-26 06:12:26'),
('admin_coupon_create_coupons_error_field_name_toolong',1,'Error: The field Coupon name is too long','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_comment_toolong',1,'Error: The field Comment is too long','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_coupons_count_small_value',1,'Error: The field Coupons Codes Count must be higher than zero','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_mbr_use_count_small_value',1,'Error: The field \"No of times the coupon can be used\"  must be above zero','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_use_count_small_value',1,'Error: The field Coupon Usage Number must be above zero ','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_code_length_small_value',1,'Error: Code Length must be more than 5 and less 32 ','admin','2009-03-30 00:00:01'),
('admin_coupon_create_coupons_error_field_discount_type_wrong',1,'Error: The Discount Type field has a wrong value','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_discount_val_notint',1,'Error: Discount must be a positive number','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_checkbox_dates_limit_wrong',1,'Error: The checkbox Don\'t Limit the Usage by Date has a wrong value','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_date_wrong',1,' Error: The Dates fields have wrong values','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_checkbox_locked_wrong',1,'Error: The checkbox Locked has a wrong value','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_error_field_products_notenough_elements',1,'Error: No elements are selected in the Products field ','admin','2009-01-10 14:10:11'),
('admin_coupon_coupon_groups_msg_added_success',1,'Coupon was added successfully','admin','2008-12-26 08:02:18'),
('admin_coupon_create_coupons_error_action_coupon_add',1,'Error: Coupon was not added','admin','0000-00-00 00:00:00'),
('admin_coupon_create_coupons_error_dates_compare',1,'Error: Wrong dates range in the Dates field. The first date value must be lower than the second value.','admin','2009-01-10 14:10:11'),
('product_add_error_model_error',1,'Error while adding to the database','admin','2008-12-26 06:12:26'),
('product_add_success',1,'Product was successfully added','admin','2009-01-10 10:02:05'),
('admin_config_lang_editor_add_lang_header',1,'Add language','admin','2008-12-18 13:35:58'),
('admin_config_lang_editor_add_lang_subheader',1,'Add new language','admin','2008-12-18 13:35:58'),
('admin_config_lang_editor_add_lang_language',1,'Language','admin','2008-12-18 13:35:58'),
('admin_config_lang_editor_add_lang_use_def_set',1,'Use default setting from','admin','2008-12-18 13:35:58'),
('admin_config_lang_editor_add_lang_not_using',1,'Not in use','admin','2008-12-09 14:31:32'),
('admin_config_lang_editor_add_lang_name_error',1,'Error. Wrong language name.','admin','2008-12-05 16:16:18'),
('admin_config_lang_editor_add_lang_exists_error',1,'Such language already exists','admin','2008-12-17 16:18:14'),
('admin_config_lang_editor_add_lang_db_error',1,'\'Add new language\' error has occured','admin','0000-00-00 00:00:00'),
('admin_config_lang_editor_add_lang_success',1,'Language was added successfully','admin','2009-01-12 09:12:43'),
('admin_config_lang_editor_delete_lang_success',1,'Language was deleted successfully','admin','2009-01-12 09:12:43'),
('product_list_title',1,'Product list','both','2009-01-10 10:02:05'),
('product_list_descr',1,'This is the list of all available products','both','2009-01-10 10:02:05'),
('admin_config_lang_editor_delete_lang_db_error',1,'Delete the language error','admin','2009-01-12 09:12:43'),
('product_list_filter',1,'Filter','both','2009-01-10 10:02:05'),
('admin_member_control_suspend_reason_addedit_page_desc',1,'You can add or edit Suspend reason.','admin','2008-10-20 14:28:53'),
('admin_member_control_suspend_reason_manage_page_title',1,'Manage Reasons','admin','2009-01-10 10:13:04'),
('admin_member_control_suspend_reason_addedit_label_name',1,'Name:','admin','2008-10-20 14:28:53'),
('admin_member_control_suspend_reason_addedit_label_description',1,'Description:','admin','2008-10-20 14:28:53'),
('admin_menu_code_generation',1,'Code Generation','admin','2008-09-15 16:33:58'),
('product_uploading_remove_poster',1,'remove','admin','2008-12-26 08:00:11'),
('admin_member_control_suspend_reason_addedit_page_title',1,'Add/Edit Reason','admin','2008-10-20 14:28:53'),
('admin_member_control_suspend_reason_error_description_toolong',1,'Error: The field Description is too long','admin','2008-10-20 14:28:53'),
('admin_newsletter_email_history_view_page_title',1,'Email History View','admin','2008-11-11 11:33:11'),
('product_save_success',1,'Product successfully saved','admin','2009-01-10 10:02:05'),
('admin_member_control_suspend_reason_error_empty_field',1,'Error: Not all required fields are filled','admin','0000-00-00 00:00:00'),
('admin_member_control_suspend_reason_error_name_toolong',1,'Error: The field Name is too long','admin','2008-10-20 14:28:53'),
('admin_btn_page_edit_save',1,'save','admin','2008-09-12 16:40:06'),
('admin_btn_page_edit_reset',1,'reset to default','admin','2008-09-12 16:40:06'),
('admin_member_control_approve_suspend_reason_delete_success',1,'Reason was deleted successfully','admin','2008-12-08 14:00:26'),
('admin_member_control_error_action_suspend_reason_add',1,'Reason was not added','admin','0000-00-00 00:00:00'),
('admin_member_control_error_action_suspend_reason_edit',1,'Reason was not changed','admin','0000-00-00 00:00:00'),
('admin_member_control_error_action_suspend_reason_delete',1,'Error: ID is invalid','admin','2008-11-21 16:18:36'),
('admin_menu_manage_pages',1,'Manage Pages','admin','2009-01-12 08:57:39'),
('admin_member_control_error_suspend_reason_id_empty',1,'Error: Suspend Reason ID value is empty','admin','0000-00-00 00:00:00'),
('admin_page_edit_title',1,'Page Edit','admin','2008-09-12 16:40:06'),
('admin_page_edit_description',1,'Here you can edit text for system pages','admin','2008-09-12 16:40:06'),
('admin_member_control_approve_suspend_reason_edit_success',1,'Reason was changed successfully','admin','2008-10-06 16:37:00'),
('admin_member_control_approve_suspend_reason_add_success',1,'Reason was added successfully','admin','2008-10-17 15:31:15'),
('admin_status_offline_msg',1,'system offline message','admin','2009-01-10 14:10:40'),
('admin_edit_sys_template_btn_reset',1,'reset to default','admin','2008-09-08 18:06:53'),
('admin_btn_switch_off',1,'turn off','admin','2009-01-10 14:10:40'),
('admin_edit_sys_template_btn_cancel',1,'cancel','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_btn_save',1,'save','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_header_comment',1,'Edit system emails template using the form below','admin','2009-01-12 08:56:47'),
('admin_btn_switch_on',1,'turn on','admin','2008-12-08 16:16:41'),
('admin_edit_sys_template_message',1,'Message: ','admin','2009-01-12 08:57:14'),
('admin_edit_sys_template_header_subject',1,'Edit system template: ','admin','2009-01-12 08:56:47'),
('admin_edit_sys_template_btn_add',1,'add','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_subject',1,'Subject: ','admin','2009-01-12 08:57:14'),
('admin_btn_cancel_status',1,'cancel','admin','2009-01-10 14:10:40'),
('admin_status_offline',1,'offline','admin','2008-12-08 16:16:41'),
('admin_btn_save_status',1,'save','admin','2009-01-10 14:10:40'),
('admin_status_online',1,'online','admin','2009-01-10 14:10:40'),
('admin_status_current',1,'Current system status is','admin','2009-01-10 14:10:40'),
('admin_config_status_page_description',1,'Here you can view and edit the global system status and offline message','admin','2009-01-10 14:10:40'),
('admin_config_status_page_title',1,'Change system status','admin','2009-01-10 14:10:40'),
('admin_menu_status_settings',1,'Change System Status','admin','2009-01-12 08:57:39'),
('admin_msg_er_0202',1,'Value must be a valid email domain!','admin','2009-01-10 14:44:46'),
('product_save_error_poster_remove_error',1,'Error while removing the poster','admin','2009-01-12 09:15:57'),
('admin_btn_cancel_security',1,'cancel','admin','2009-01-10 15:38:23'),
('admin_member_settings_btn_select_all',1,'select all','admin','2009-01-10 14:44:46'),
('admin_member_settings_btn_delete',1,'delete','admin','2009-01-10 14:44:46'),
('admin_member_settings_btn_add',1,'add','admin','2009-01-10 14:44:46'),
('admin_member_settings_btn_save',1,'save','admin','2009-01-10 14:44:46'),
('admin_member_settings_btn_cancel',1,'cancel','admin','2009-01-10 14:44:46'),
('admin_msg_er_0013',1,'Maximum allowed 512 symbols','admin','2009-01-10 14:10:40'),
('user_paypal_redirect_header',1,'Click on the Buy now button or wait while redirecting...','user','2008-10-30 15:48:21'),
('admin_msg_er_0012',1,'Offline message failed','admin','0000-00-00 00:00:00'),
('admin_access_level_access_category_product',1,'Product','admin','2009-01-10 14:55:45'),
('admin_sys_emails_name',1,'Name','admin','2009-01-12 08:57:33'),
('admin_sys_emails_action',1,'Action','admin','2009-01-12 08:57:33'),
('admin_sys_emails_admin_emails',1,'Admin Emails','admin','2009-01-12 08:57:33'),
('admin_sys_emails_user_emails',1,'Members Emails','admin','2009-05-20 12:26:29'),
('admin_sys_emails_header_comment',1,'Edit system email templates here','admin','2009-01-12 08:57:33'),
('admin_sys_emails_header_subject',1,'System email','admin','2009-01-12 08:57:33'),
('admin_msg_er_0006',1,'Block IP timeout failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0007',1,'Block message failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0008',1,'Login attempts counting (autoban) failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0009',1,'Autoban timeout failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0010',1,'CAPTCHA maximum chars failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0011',1,'CAPTCHA minimum chars failed','admin','0000-00-00 00:00:00'),
('product_save_not_exists',1,'Product doesn\'t exist','admin','2008-11-04 11:27:12'),
('admin_msg_er_0004',1,'CAPTCHA attempt failed','admin','0000-00-00 00:00:00'),
('admin_msg_er_0005',1,'Block IP attempt failed','admin','0000-00-00 00:00:00'),
('admin_member_control_img_tooltip_confirm',1,'Activate','admin','2009-01-10 10:13:52'),
('admin_member_control_img_tooltip_unsuspend',1,'Unsuspend member','admin','2008-12-19 14:24:53'),
('admin_msg_er_0201',1,'Value must be a valid number!','admin','0000-00-00 00:00:00'),
('admin_member_control_img_tooltip_suspend',1,'Suspend member','admin','2008-12-19 14:24:53'),
('admin_member_control_img_tooltip_delete',1,'Delete member','admin','2008-12-19 14:24:53'),
('admin_member_control_img_tooltip_view',1,'View member','admin','2008-12-19 14:24:53'),
('admin_member_control_img_tooltip_edit',1,'Edit member','admin','2008-12-19 14:24:53'),
('product_save_product_dirs',1,'Protected dirs','admin','2009-01-12 09:15:57'),
('product_save_produc_dirs',1,'Protected dirs','admin','0000-00-00 00:00:00'),
('product_add_product_dirs',1,'Protected dirs','admin','2008-12-26 06:12:26'),
('product_save_product_image',1,'Poster','admin','2009-01-12 09:15:57'),
('product_save_button_add',1,'Save','admin','2009-01-12 09:15:57'),
('product_save_cancel',1,'Cancel','admin','2009-01-12 09:15:57'),
('product_save_product_cumulative',1,'is discount cumulative','admin','0000-00-00 00:00:00'),
('product_save_product_trial_duration_month',1,'Month','admin','2009-01-12 09:15:57'),
('product_save_product_trial_duration_year',1,'Year','admin','2009-01-12 09:15:57'),
('product_save_product_trial_duration_day',1,'Day','admin','2009-01-12 09:15:57'),
('product_save_product_trial_duration',1,'Trial period duration','admin','2009-01-12 09:15:57'),
('product_save_product_discount',1,'Discount type','admin','2009-01-12 09:15:57'),
('product_save_product_discount_percent',1,'percent','admin','2009-01-12 09:15:57'),
('product_save_product_discount_value',1,'value','admin','2009-01-12 09:15:57'),
('product_save_product_trial_price',1,'Trial price','admin','2009-01-12 09:15:57'),
('product_save_product_price_year',1,'Year','admin','2009-01-12 09:15:57'),
('product_save_error_discount_value',1,'Discount value has an invalid format','admin','2009-01-12 09:15:57'),
('product_save_product_recouring',1,'Recurring','admin','2009-01-12 09:15:57'),
('product_save_product_recouring_yes',1,'yes','admin','2009-01-12 09:15:57'),
('admin_levels_btn_add',1,'add level','admin','2009-01-10 14:55:45'),
('product_save_product_price_month6',1,'six months','admin','2009-01-12 09:15:57'),
('product_save_product_price_month3',1,'3 month','admin','2009-01-12 09:15:57'),
('product_save_product_price_month',1,'Month','admin','2009-01-12 09:15:57'),
('product_save_product_price_day',1,'Day','admin','2009-01-12 09:15:57'),
('product_save_product_prices',1,'Prices','admin','2009-01-12 09:15:57'),
('product_save_product_groups',1,'Groups','admin','2009-01-12 09:15:57'),
('product_save_product_name',1,'Name','admin','2009-01-12 09:15:57'),
('product_save_product_descr',1,'Description','admin','2009-01-12 09:15:57'),
('product_save_error_model_error',1,'Error while adding to the database','admin','2009-01-12 09:15:57'),
('product_save_error_dirs',1,'Select protected dirs','admin','2009-01-12 09:15:57'),
('product_save_error_trial_period',1,'Only numbers allowed for trial period','admin','2009-01-12 09:15:57'),
('product_save_error_price',1,'Some of price values have invalid format','admin','2009-01-12 09:15:57'),
('product_save_error_trial_price',1,'Trial price has an invalid format','admin','2009-01-12 09:15:57'),
('state_YT',1,'Yukon Territory','both','2008-12-27 06:37:55'),
('cart_paypal_buy_now_button',1,'Buy now button','user','2008-10-30 15:48:21'),
('product_save_page_title',1,'Edit product','admin','2009-01-12 09:15:57'),
('product_save_page_descr',1,'Change information about the product','admin','2009-01-12 09:15:57'),
('product_save_error_name',1,'The Name field is empty or too large (1 - 255 chars)','admin','2009-01-12 09:15:57'),
('product_save_error_poster_ext',1,'File has an invalid extension (jpeg, jpg, gif is allowed)','admin','2009-01-12 09:15:57'),
('product_save_error_poster_size',1,'Image file has an invalid size (1 mb max)','admin','2009-01-12 09:15:57'),
('product_save_error_descr',1,'Product description is empty or too large (max 65k length)','admin','2009-01-12 09:15:57'),
('product_save_error_groups',1,'Please select product groups','admin','2009-01-12 09:15:57'),
('state_VA',1,'Virginia','both','2008-12-27 06:37:55'),
('state_WA',1,'Washington','both','2008-12-27 06:37:55'),
('state_WV',1,'West Virginia','both','2008-12-27 06:37:55'),
('state_WI',1,'Wisconsin','both','2008-12-27 06:37:55'),
('state_WY',1,'Wyoming','both','2008-12-27 06:37:55'),
('state_RI',1,'Rhode Island','both','2008-12-27 06:37:55'),
('state_SK',1,'Saskatchewan','both','2008-12-27 06:37:55'),
('state_SC',1,'South Carolina','both','2008-12-27 06:37:55'),
('state_SD',1,'South Dakota','both','2008-12-27 06:37:55'),
('state_TN',1,'Tennessee','both','2008-12-27 06:37:55'),
('state_TX',1,'Texas','both','2008-12-27 06:37:55'),
('state_UT',1,'Utah','both','2008-12-27 06:37:55'),
('state_VT',1,'Vermont','both','2008-12-27 06:37:55'),
('state_VI',1,'Virgin Islands','both','2008-12-27 06:37:55'),
('state_PE',1,'Prince Edward Island','both','2008-12-27 06:37:55'),
('state_QC',1,'Province du Quebec','both','2008-12-27 06:37:55'),
('state_PR',1,'Puerto Rico','both','2008-12-27 06:37:55'),
('state_ND',1,'North Dakota','both','2008-12-27 06:37:55'),
('state_MP',1,'Northern Mariana Is','both','2008-12-27 06:37:55'),
('state_NT',1,'Northwest Territories','both','2008-12-27 06:37:55'),
('state_NS',1,'Nova Scotia','both','2008-12-27 06:37:55'),
('state_NU',1,'Nunavut','both','2008-12-27 06:37:55'),
('state_OH',1,'Ohio','both','2008-12-27 06:37:55'),
('state_OK',1,'Oklahoma','both','2008-12-27 06:37:55'),
('state_ON',1,'Ontario','both','2008-12-27 06:37:55'),
('state_OR',1,'Oregon','both','2008-12-27 06:37:55'),
('state_PW',1,'Palau','both','2008-12-27 06:37:55'),
('state_PA',1,'Pennsylvania','both','2008-12-27 06:37:55'),
('state_NE',1,'Nebraska','both','2008-12-27 06:37:55'),
('state_NV',1,'Nevada','both','2008-12-27 06:37:55'),
('state_NB',1,'New Brunswick','both','2008-12-27 06:37:55'),
('state_NH',1,'New Hampshire','both','2008-12-27 06:37:55'),
('state_NJ',1,'New Jersey','both','2008-12-27 06:37:55'),
('state_NM',1,'New Mexico','both','2008-12-27 06:37:55'),
('state_NY',1,'New York','both','2008-12-27 06:37:55'),
('state_NF',1,'Newfoundland','both','2008-12-27 06:37:55'),
('state_NC',1,'North Carolina','both','2008-12-27 06:37:55'),
('state_MA',1,'Massachusetts','both','2008-12-27 06:37:55'),
('state_MI',1,'Michigan','both','2008-12-27 06:37:55'),
('state_MN',1,'Minnesota','both','2008-12-27 06:37:55'),
('state_MS',1,'Mississippi','both','2008-12-27 06:37:55'),
('state_MO',1,'Missouri','both','2008-12-27 06:37:55'),
('state_MT',1,'Montana','both','2008-12-27 06:37:55'),
('state_MB',1,'Manitoba','both','2008-12-27 06:37:55'),
('state_MD',1,'Maryland','both','2008-12-27 06:37:55'),
('state_IN',1,'Indiana','both','2008-12-27 06:37:55'),
('state_IA',1,'Iowa','both','2008-12-27 06:37:55'),
('state_KS',1,'Kansas','both','2008-12-27 06:37:55'),
('state_KY',1,'Kentucky','both','2008-12-27 06:37:55'),
('state_LA',1,'Louisiana','both','2008-12-27 06:37:55'),
('state_ME',1,'Maine','both','2008-12-27 06:37:55'),
('state_ID',1,'Idaho','both','2008-12-27 06:37:55'),
('state_IL',1,'Illinois','both','2008-12-27 06:37:55'),
('state_BC',1,'British Columbia','both','2008-12-27 06:37:55'),
('state_CA',1,'California','both','2008-12-27 06:37:55'),
('state_CO',1,'Colorado','both','2008-12-27 06:37:55'),
('state_CT',1,'Connecticut','both','2008-12-27 06:37:55'),
('state_DE',1,'Delaware','both','2008-12-27 06:37:55'),
('state_DC',1,'District Of Columbia','both','2008-12-27 06:37:55'),
('state_FL',1,'Florida','both','2008-12-27 06:37:55'),
('state_GA',1,'Georgia','both','2008-12-27 06:37:55'),
('state_GU',1,'Guam','both','2008-12-27 06:37:55'),
('state_HI',1,'Hawaii','both','2008-12-27 06:37:55'),
('state_AE',1,'Armed Forces Europe','both','2008-12-27 06:37:55'),
('state_AP',1,'Armed Forces Pacific','both','2008-12-27 06:37:55'),
('state_AR',1,'Arkansas','both','2008-12-27 06:37:55'),
('state_AA',1,'Armed Forces Americas','both','2008-12-27 06:37:55'),
('state_AS',1,'American Samoa','both','2008-12-27 06:37:55'),
('state_AZ',1,'Arizona','both','2008-12-27 06:37:55'),
('state_AK',1,'Alaska','both','2008-12-27 06:37:55'),
('state_AB',1,'Alberta','both','2008-12-27 06:37:55'),
('state_XX',1,'Outside US and Canada','both','2008-12-27 06:37:55'),
('state_AL',1,'Alabama','both','2008-12-27 06:37:55'),
('country_VN',1,'Vietnam','both','2008-12-27 06:37:55'),
('country_WF',1,'Wallis and Futuna Islands','both','2008-12-27 06:37:55'),
('country_YE',1,'Yemen','both','2008-12-27 06:37:55'),
('country_ZM',1,'Zambia','both','2008-12-27 06:37:55'),
('country_VA',1,'Vatican City State','both','2008-12-27 06:37:55'),
('country_VE',1,'Venezuela','both','2008-12-27 06:37:55'),
('country_UY',1,'Uruguay','both','2008-12-27 06:37:55'),
('country_VU',1,'Vanuatu','both','2008-12-27 06:37:55'),
('country_GB',1,'United Kingdom','both','2008-12-27 06:37:55'),
('country_US',1,'United States of America','both','2008-12-27 06:37:55'),
('country_UA',1,'Ukraine','both','2008-12-27 06:37:55'),
('country_AE',1,'United Arab Emirates','both','2008-12-27 06:37:55'),
('country_TV',1,'Tuvalu','both','2008-12-27 06:37:55'),
('country_UG',1,'Uganda','both','2008-12-27 06:37:55'),
('country_TM',1,'Turkmenistan','both','2008-12-27 06:37:55'),
('country_TC',1,'Turks and Caicos Islands','both','2008-12-27 06:37:55'),
('country_TN',1,'Tunisia','both','2008-12-27 06:37:55'),
('country_TR',1,'Turkey','both','2008-12-27 06:37:55'),
('country_TO',1,'Tonga','both','2008-12-27 06:37:55'),
('country_TT',1,'Trinidad and Tobago','both','2008-12-27 06:37:55'),
('country_TZ',1,'Tanzania','both','2008-12-27 06:37:55'),
('country_TH',1,'Thailand','both','2008-12-27 06:37:55'),
('country_TG',1,'Togo','both','2008-12-27 06:37:55'),
('country_SB',1,'Solomon Islands','both','2008-12-27 06:37:55'),
('country_SO',1,'Somalia','both','2008-12-27 06:37:55'),
('country_ZA',1,'South Africa','both','2008-12-27 06:37:55'),
('country_KR',1,'South Korea','both','2008-12-27 06:37:55'),
('country_ES',1,'Spain','both','2008-12-27 06:37:55'),
('country_LK',1,'Sri Lanka','both','2008-12-27 06:37:55'),
('country_SH',1,'St. Helena','both','2008-12-27 06:37:55'),
('country_KN',1,'St. Kitts and Nevis','both','2008-12-27 06:37:55'),
('country_LC',1,'St. Lucia','both','2008-12-27 06:37:55'),
('country_PM',1,'St. Pierre and Miquelon','both','2008-12-27 06:37:55'),
('country_SR',1,'Suriname','both','2008-12-27 06:37:55'),
('country_SJ',1,'Svalbard and Jan Mayen Islands','both','2008-12-27 06:37:55'),
('country_SZ',1,'Swaziland','both','2008-12-27 06:37:55'),
('country_SE',1,'Sweden','both','2008-12-27 06:37:55'),
('country_CH',1,'Switzerland','both','2008-12-27 06:37:55'),
('country_TW',1,'Taiwan','both','2008-12-27 06:37:55'),
('country_TJ',1,'Tajikistan','both','2008-12-27 06:37:55'),
('country_SK',1,'Slovakia','both','2008-12-27 06:37:55'),
('country_SI',1,'Slovenia','both','2008-12-27 06:37:55'),
('country_SL',1,'Sierra Leone','both','2008-12-27 06:37:55'),
('country_SG',1,'Singapore','both','2008-12-27 06:37:55'),
('country_SN',1,'Senegal','both','2008-12-27 06:37:55'),
('country_SC',1,'Seychelles','both','2008-12-27 06:37:55'),
('country_ST',1,'Sao Tome and Principe','both','2008-12-27 06:37:55'),
('country_SA',1,'Saudi Arabia','both','2008-12-27 06:37:55'),
('country_VC',1,'Saint Vincent and the Grenadines','both','2008-12-27 06:37:55'),
('country_WS',1,'Samoa','both','2008-12-27 06:37:55'),
('country_SM',1,'San Marino','both','2008-12-27 06:37:55'),
('country_CG',1,'Republic of the Congo','both','2008-12-27 06:37:55'),
('country_RE',1,'Reunion','both','2008-12-27 06:37:55'),
('country_RO',1,'Romania','both','2008-12-27 06:37:55'),
('country_RU',1,'Russia','both','2008-12-27 06:37:55'),
('country_RW',1,'Rwanda','both','2008-12-27 06:37:55'),
('country_PG',1,'Papua New Guinea','both','2008-12-27 06:37:55'),
('country_PE',1,'Peru','both','2008-12-27 06:37:55'),
('country_PH',1,'Philippines','both','2008-12-27 06:37:55'),
('country_PN',1,'Pitcairn Islands','both','2008-12-27 06:37:55'),
('country_PL',1,'Poland','both','2008-12-27 06:37:55'),
('country_PT',1,'Portugal','both','2008-12-27 06:37:55'),
('country_QA',1,'Qatar','both','2008-12-27 06:37:55'),
('country_PW',1,'Palau','both','2008-12-27 06:37:55'),
('country_PA',1,'Panama','both','2008-12-27 06:37:55'),
('country_NU',1,'Niue','both','2008-12-27 06:37:55'),
('country_NF',1,'Norfolk Island','both','2008-12-27 06:37:55'),
('country_NO',1,'Norway','both','2008-12-27 06:37:55'),
('country_OM',1,'Oman','both','2008-12-27 06:37:55'),
('country_NI',1,'Nicaragua','both','2008-12-27 06:37:55'),
('country_NE',1,'Niger','both','2008-12-27 06:37:55'),
('country_NC',1,'New Caledonia','both','2008-12-27 06:37:55'),
('country_NZ',1,'New Zealand','both','2008-12-27 06:37:55'),
('country_MS',1,'Montserrat','both','2008-12-27 06:37:55'),
('country_MA',1,'Morocco','both','2008-12-27 06:37:55'),
('country_MZ',1,'Mozambique','both','2008-12-27 06:37:55'),
('country_NA',1,'Namibia','both','2008-12-27 06:37:55'),
('country_NR',1,'Nauru','both','2008-12-27 06:37:55'),
('country_NP',1,'Nepal','both','2008-12-27 06:37:55'),
('country_NL',1,'Netherlands','both','2008-12-27 06:37:55'),
('country_AN',1,'Netherlands Antilles','both','2008-12-27 06:37:55'),
('country_MX',1,'Mexico','both','2008-12-27 06:37:55'),
('country_MN',1,'Mongolia','both','2008-12-27 06:37:55'),
('country_MU',1,'Mauritius','both','2008-12-27 06:37:55'),
('country_YT',1,'Mayotte','both','2008-12-27 06:37:55'),
('country_MQ',1,'Martinique','both','2008-12-27 06:37:55'),
('country_MR',1,'Mauritania','both','2008-12-27 06:37:55'),
('country_MT',1,'Malta','both','2008-12-27 06:37:55'),
('country_MH',1,'Marshall Islands','both','2008-12-27 06:37:55'),
('country_MV',1,'Maldives','both','2008-12-27 06:37:55'),
('country_ML',1,'Mali','both','2008-12-27 06:37:55'),
('country_LA',1,'Laos','both','2008-12-27 06:37:55'),
('country_LV',1,'Latvia','both','2008-12-27 06:37:55'),
('country_LS',1,'Lesotho','both','2008-12-27 06:37:55'),
('country_LI',1,'Liechtenstein','both','2008-12-27 06:37:55'),
('country_LT',1,'Lithuania','both','2008-12-27 06:37:55'),
('country_LU',1,'Luxembourg','both','2008-12-27 06:37:55'),
('country_MG',1,'Madagascar','both','2008-12-27 06:37:55'),
('country_MW',1,'Malawi','both','2008-12-27 06:37:55'),
('country_MY',1,'Malaysia','both','2008-12-27 06:37:55'),
('country_IT',1,'Italy','both','2008-12-27 06:37:55'),
('country_JM',1,'Jamaica','both','2008-12-27 06:37:55'),
('country_JP',1,'Japan','both','2008-12-27 06:37:55'),
('country_JO',1,'Jordan','both','2008-12-27 06:37:55'),
('country_KZ',1,'Kazakhstan','both','2008-12-27 06:37:55'),
('country_KE',1,'Kenya','both','2008-12-27 06:37:55'),
('country_KI',1,'Kiribati','both','2008-12-27 06:37:55'),
('country_KW',1,'Kuwait','both','2008-12-27 06:37:55'),
('country_KG',1,'Kyrgyzstan','both','2008-12-27 06:37:55'),
('country_GY',1,'Guyana','both','2008-12-27 06:37:55'),
('country_HN',1,'Honduras','both','2008-12-27 06:37:55'),
('country_HK',1,'Hong Kong','both','2008-12-27 06:37:55'),
('country_HU',1,'Hungary','both','2008-12-27 06:37:55'),
('country_IS',1,'Iceland','both','2008-12-27 06:37:55'),
('country_IN',1,'India','both','2008-12-27 06:37:55'),
('country_ID',1,'Indonesia','both','2008-12-27 06:37:55'),
('country_IE',1,'Ireland','both','2008-12-27 06:37:55'),
('country_IL',1,'Israel','both','2008-12-27 06:37:55'),
('country_GN',1,'Guinea','both','2008-12-27 06:37:55'),
('country_GW',1,'Guinea Bissau','both','2008-12-27 06:37:55'),
('country_GD',1,'Grenada','both','2008-12-27 06:37:55'),
('country_GP',1,'Guadeloupe','both','2008-12-27 06:37:55'),
('country_GT',1,'Guatemala','both','2008-12-27 06:37:55'),
('country_GR',1,'Greece','both','2008-12-27 06:37:55'),
('country_GL',1,'Greenland','both','2008-12-27 06:37:55'),
('country_DE',1,'Germany','both','2008-12-27 06:37:55'),
('country_GI',1,'Gibraltar','both','2008-12-27 06:37:55'),
('country_GA',1,'Gabon Republic','both','2008-12-27 06:37:55'),
('country_GM',1,'Gambia','both','2008-12-27 06:37:55'),
('country_FM',1,'Federated States of Micronesia','both','2008-12-27 06:37:55'),
('country_FJ',1,'Fiji','both','2008-12-27 06:37:55'),
('country_FI',1,'Finland','both','2008-12-27 06:37:55'),
('country_FR',1,'France','both','2008-12-27 06:37:55'),
('country_GF',1,'French Guiana','both','2008-12-27 06:37:55'),
('country_PF',1,'French Polynesia','both','2008-12-27 06:37:55'),
('country_FK',1,'Falkland Islands','both','2008-12-27 06:37:55'),
('country_FO',1,'Faroe Islands','both','2008-12-27 06:37:55'),
('country_EC',1,'Ecuador','both','2008-12-27 06:37:55'),
('country_SV',1,'El Salvador','both','2008-12-27 06:37:55'),
('country_ER',1,'Eritrea','both','2008-12-27 06:37:55'),
('country_EE',1,'Estonia','both','2008-12-27 06:37:55'),
('country_ET',1,'Ethiopia','both','2008-12-27 06:37:55'),
('country_DM',1,'Dominica','both','2008-12-27 06:37:55'),
('country_DO',1,'Dominican Republic','both','2008-12-27 06:37:55'),
('country_DK',1,'Denmark','both','2008-12-27 06:37:55'),
('country_DJ',1,'Djibouti','both','2008-12-27 06:37:55'),
('country_CR',1,'Costa Rica','both','2008-12-27 06:37:55'),
('country_HR',1,'Croatia','both','2008-12-27 06:37:55'),
('country_CY',1,'Cyprus','both','2008-12-27 06:37:55'),
('country_CZ',1,'Czech Republic','both','2008-12-27 06:37:55'),
('country_CD',1,'Democratic Republic of the Congo','both','2008-12-27 06:37:55'),
('country_CO',1,'Colombia','both','2008-12-27 06:37:55'),
('country_KM',1,'Comoros','both','2008-12-27 06:37:55'),
('country_CK',1,'Cook Islands','both','2008-12-27 06:37:55'),
('country_CL',1,'Chile','both','2008-12-27 06:37:55'),
('country_CN',1,'China','both','2008-12-27 06:37:55'),
('country_BA',1,'Bosnia and Herzegovina','both','2008-12-27 06:37:55'),
('country_BW',1,'Botswana','both','2008-12-27 06:37:55'),
('country_BR',1,'Brazil','both','2008-12-27 06:37:55'),
('country_VG',1,'Virgin Islands, British','both','2008-12-27 06:37:55'),
('country_BN',1,'Brunei','both','2008-12-27 06:37:55'),
('country_BG',1,'Bulgaria','both','2008-12-27 06:37:55'),
('country_BF',1,'Burkina Faso','both','2008-12-27 06:37:55'),
('country_BI',1,'Burundi','both','2008-12-27 06:37:55'),
('country_KH',1,'Cambodia','both','2008-12-27 06:37:55'),
('country_CA',1,'Canada','both','2008-12-27 06:37:55'),
('country_CV',1,'Cape Verde','both','2008-12-27 06:37:55'),
('country_KY',1,'Cayman Islands','both','2008-12-27 06:37:55'),
('country_TD',1,'Chad','both','2008-12-27 06:37:55'),
('country_BE',1,'Belgium','both','2008-12-27 06:37:55'),
('country_BZ',1,'Belize','both','2008-12-27 06:37:55'),
('country_BJ',1,'Benin','both','2008-12-27 06:37:55'),
('country_BM',1,'Bermuda','both','2008-12-27 06:37:55'),
('country_BT',1,'Bhutan','both','2008-12-27 06:37:55'),
('country_BO',1,'Bolivia','both','2008-12-27 06:37:55'),
('country_BS',1,'Bahamas','both','2008-12-27 06:37:55'),
('country_BH',1,'Bahrain','both','2008-12-27 06:37:55'),
('country_BB',1,'Barbados','both','2008-12-27 06:37:55'),
('country_AO',1,'Angola','both','2008-12-27 06:37:55'),
('country_AI',1,'Anguilla','both','2008-12-27 06:37:55'),
('country_AG',1,'Antigua and Barbuda','both','2008-12-27 06:37:55'),
('country_AM',1,'Armenia','both','2008-12-27 06:37:55'),
('country_AW',1,'Aruba','both','2008-12-27 06:37:55'),
('country_AU',1,'Australia','both','2008-12-27 06:37:55'),
('country_AT',1,'Austria','both','2008-12-27 06:37:55'),
('country_AZ',1,'Azerbaijan Republic','both','2008-12-27 06:37:55'),
('user_paypal_form_field1_error',1,'PayPal field error','user','0000-00-00 00:00:00'),
('country_AR',1,'Argentina','both','2008-12-27 06:37:55'),
('country_AL',1,'Albania','both','2008-12-27 06:37:55'),
('country_DZ',1,'Algeria','both','2008-12-27 06:37:55'),
('country_AD',1,'Andorra','both','2008-12-27 06:37:55'),
('user_payment_form_country_error',1,'Country error','user','2008-12-18 10:37:04'),
('user_payment_form_phone_error',1,'Phone error','user','2008-12-18 10:37:04'),
('admin_menu_system_emails',1,'System Emails','admin','2009-01-12 08:57:39'),
('admin_coupon_create_coupons_error_field_notnumber',1,'Error: Discount must be a number','admin','0000-00-00 00:00:00'),
('user_payment_form_billing_name_error',1,'Billing name error','user','2008-12-18 10:37:04'),
('user_payment_form_street_error',1,'Street address error','user','2008-12-18 10:37:04'),
('user_payment_form_city_error',1,'City error','user','2008-12-18 10:37:04'),
('user_payment_form_state_error',1,'State error','user','2008-12-18 10:37:04'),
('admin_coupon_create_coupons_error_field_discount_val_notfloat',1,'Error: The  Discount field must include currency format','admin','0000-00-00 00:00:00'),
('user_payment_form_zip_error',1,'Zip code error','user','2008-12-18 10:37:04'),
('admin_member_settings_denied_email_ttip',1,'Members with these email domains will not be able to register','admin','2009-01-10 14:44:46'),
('admin_msg_validation_fail',1,'Validation failed','admin','2009-01-12 08:58:32'),
('user_payment_form_btn_cancel',1,'Cancel','user','2008-12-27 06:37:55'),
('user_cart_refresh_when_change_coupon_code',1,'Press the recalculate button, if you want to change the coupon code.','user','2008-12-22 11:18:08'),
('admin_msg_er_0104',1,'Must not be empty!(1-255)','admin','2009-01-10 15:37:42'),
('admin_msg_er_0105',1,'Must be a valid path, and not empty!(1-255)','admin','2009-01-10 15:37:42'),
('admin_msg_er_0106',1,'Path does not exist!','admin','2008-12-18 11:03:38'),
('admin_member_settings_trusted_email_ttip',1,'Members with these email domains will not need the administrator approval','admin','2009-01-10 14:44:46'),
('admin_msg_cancel',1,'Cancel changes, are you sure?','admin','2009-01-12 08:57:33'),
('admin_member_settings_denied_email',1,'Denied email domains','admin','2009-01-10 14:44:46'),
('admin_login_form_msg_er_not_exist',1,'The members does not exist','admin','2009-05-20 12:14:54'),
('admin_member_settings_trusted_email',1,'Trusted email domains','admin','2009-01-10 14:44:46'),
('admin_member_settings_force_billing_info_input',1,'Force members to input billing info when making order','admin','2009-05-20 12:25:56'),
('admin_member_settings_allow_register',1,'Allow new members to register','admin','2009-01-10 14:44:46'),
('admin_member_settings_need_activation',1,'Use new accounts activation','admin','2009-01-10 14:44:46'),
('admin_member_settings_approve_needed',1,'New accounts wait for approval','admin','2009-01-10 14:44:46'),
('admin_member_settings_new_registrations_notify',1,'Notify administrator about new user registrations','admin','2008-11-28 14:42:46'),
('admin_member_settings_error',1,'Please check your input, some error occured.','admin','2009-01-10 14:44:46'),
('admin_member_settings_saved_successfully',1,'New member settings were changed successfully','admin','2009-01-10 14:44:46'),
('admin_member_settings_header_comment',1,'Here you can change member options','admin','2009-01-10 14:44:46'),
('admin_member_settings_header_subject',1,'Member Settings','admin','2009-01-10 14:44:46'),
('admin_msg_er_0003',1,'Value must be integer, greater 2 and lower 5','admin','2009-01-10 15:38:23'),
('admin_msg_ok_0002',1,'Information was changed successfully','admin','2008-09-12 16:40:06'),
('admin_msg_er_0001',1,'Value mast be integer, greater 60 and lower 32768','admin','2009-06-29 12:09:40'),
('admin_msg_er_0002',1,'Maximum allowed 1024 symbols','admin','2009-01-10 15:38:23'),
('user_cart_btn_login_to_use_coupons',1,'Log in','user','2008-12-03 13:12:17'),
('admin_msg_er_0103',1,'It should not contain: \\/:*?\"<>\'|., symbols!','admin','2009-01-10 15:37:42'),
('admin_msg_er_0102',1,'Must be a valid IP, and not empty!','admin','2009-01-10 15:37:42'),
('user_cart_login_to_use_coupons',1,'You must be logged in to use coupons.','user','2008-12-03 13:12:17'),
('product_add_image_upload_error_resize',1,'Can\'t resize the image. Check the file type.','admin','2008-10-20 16:41:56'),
('admin_msg_er_0101',1,'Must be a  valid URL, and not empty!(1-255)','admin','2009-01-10 15:37:42'),
('admin_menu_member_settings',1,'Member Settings','admin','2009-01-12 08:57:39'),
('user_cart_btn_refresh_coupons',1,'Recalculate','user','2008-12-22 11:18:08'),
('user_cart_coupons',1,'Coupon','user','2008-12-27 06:36:45'),
('product_uploading_wait',1,'Uploading in process. Please wait...','admin','2009-01-12 09:15:57'),
('user_payment_form_subheader',1,'Create order','user','2008-12-27 06:37:55'),
('user_payment_form_header',1,'Order Registration','user','2008-12-27 06:37:55'),
('upload_not_writable',1,'The upload destination folder does not appear to be writable.','admin','0000-00-00 00:00:00'),
('user_cart_choose_payment_system',1,'Payment system:','user','2008-12-22 11:18:08'),
('common_year',1,'year','both','2008-12-27 06:36:45'),
('user_login_checkout_denied',1,'Please log in to continue the checkout.','user','0000-00-00 00:00:00'),
('upload_bad_filename',1,'The file name you submitted already exists on the server.','admin','0000-00-00 00:00:00'),
('upload_no_filepath',1,'The upload path does not appear to be valid.','admin','0000-00-00 00:00:00'),
('upload_no_file_types',1,'You have not specified any allowed file types.','admin','0000-00-00 00:00:00'),
('upload_invalid_filesize',1,'The file you are attempting to upload is larger than the permitted size.','admin','0000-00-00 00:00:00'),
('upload_invalid_dimensions',1,'The image you are attempting to upload exceedes the maximum height or width.','admin','0000-00-00 00:00:00'),
('upload_file_exceeds_form_limit',1,'The uploaded file exceeds the maximum size allowed by the submission form.','admin','0000-00-00 00:00:00'),
('upload_file_partial',1,'The file was only partially uploaded.','admin','0000-00-00 00:00:00'),
('upload_unable_to_write_file',1,'The file could not be written to disk.','admin','0000-00-00 00:00:00'),
('upload_no_file_selected',1,'You did not select a file to upload','admin','0000-00-00 00:00:00'),
('upload_invalid_filetype',1,'The filetype you are attempting to upload is not allowed.','admin','0000-00-00 00:00:00'),
('common_day',1,'day','both','2008-12-22 11:18:08'),
('common_month',1,'month','both','2008-10-30 14:19:09'),
('common_months',1,'months','both','0000-00-00 00:00:00'),
('user_cart_recouring',1,'recurring','user','2008-11-04 10:35:46'),
('user_cart_trial_period',1,'Trial','user','2008-12-27 06:36:45'),
('upload_stopped_by_extension',1,'The file upload was stopped by extension.','admin','0000-00-00 00:00:00'),
('upload_no_temp_directory',1,'The temporary folder is missing.','admin','0000-00-00 00:00:00'),
('admin_member_control_error_field_password_wrong_chars',1,'Error: Password contains wrong characters, is too short or  is very simple','admin','2009-01-10 10:10:15'),
('upload_userfile_not_set',1,'Unable to find a post variable called userfile.','admin','0000-00-00 00:00:00'),
('upload_file_exceeds_limit',1,'The uploaded file exceeds the maximum allowed size in your PHP configuration file.\";','admin','0000-00-00 00:00:00'),
('admin_member_control_error_field_password_tooshort',1,'Error: Password is too short','admin','0000-00-00 00:00:00'),
('product_uploading_image',1,'Uploading poster image...','admin','2009-01-12 09:15:57'),
('admin_config_manage_news_edit_msg_no_changes',1,'News is not changed','admin','0000-00-00 00:00:00'),
('user_cart_price',1,'Price','user','2008-12-27 06:36:45'),
('admin_payment_system_authorize_net_btn_cancel',1,'cancel','admin','2008-12-08 17:05:31'),
('user_cart_btn_checkout',1,'Checkout','user','2008-12-27 06:36:45'),
('user_cart_empty_product_list',1,'Empty product list','user','2008-12-19 12:07:50'),
('user_cart_total',1,'Total:','user','2008-12-27 06:36:45'),
('user_news_no_available',1,'Sorry, no news','user','2009-01-12 09:15:57'),
('product_upload_image',1,'Upload image','admin','2009-01-12 09:15:57'),
('user_cart_subheader',1,'View the choosen products','user','2008-12-27 06:36:45'),
('user_cart_delete',1,'Delete','user','0000-00-00 00:00:00'),
('user_cart_product_name',1,'Product Name','user','2008-12-27 06:36:45'),
('user_cart_regular_period',1,'Regular','user','2008-12-27 06:36:45'),
('user_cart_header',1,'Shopping Cart','user','2008-12-27 06:36:45'),
('admin_btn_delete',1,'delete','admin','2009-01-12 09:12:43'),
('admin_member_control_add_member_field_expiration_date_tooltip',1,'(if you leave the field blank, the account duration date will be unlimited)','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_last_name_wrong_chars',1,'Error: Last name contains wrong characters','admin','2009-01-10 10:10:45'),
('admin_member_control_error_field_first_name_wrong_chars',1,'Error: First name contains wrong characters','admin','2009-01-10 10:10:45'),
('product_delete_success',1,'Product was successfully deleted','admin','2009-01-10 10:02:05'),
('admin_menu_security_settings',1,'Security settings','admin','2009-01-12 08:57:39'),
('admin_menu_global_setup',1,'Global Setup','admin','2009-01-12 08:57:39'),
('admin_config_ban_ip_table_ban_ip_tooltip',1,'IP must be have the following formats 123.123.123.123 OR 123.123.123.* OR 123.123.123.123 - 255.255.255.255','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_table_ban_reason_tooltip',1,'Users see this text after they attempt to login from a banned IP','admin','2009-01-10 16:51:49'),
('product_group_page_descr',1,'View the list of product groups here','admin','2009-01-10 15:37:32'),
('product_lock_error',1,'Error while blocking/unblocking the product','undef','2009-01-10 10:02:05'),
('product_delete_error',1,'Error while deleting the product','undef','2009-01-10 10:02:05'),
('admin_config_ban_ip_empty_list',1,'No Data','admin','2009-01-10 16:51:49'),
('product_group_page_title',1,'Products Groups','undef','2009-01-10 15:37:32'),
('admin_mailer_settings_outgoing_address',1,'Outgoing address','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_email_charset',1,'Outgoing email charset','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_email_format',1,'Outgoing email format','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_format_plain',1,'Plain text','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_format_html',1,'HTML','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_use_smtp',1,'Use SMTP','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_yes',1,'Yes','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_no',1,'No','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_smtp_host',1,'SMTP host','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_smtp_port',1,'SMTP port','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_btn_test_connection',1,'Test connection','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_use_authentication',1,'Use authentication','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_username',1,'Username','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_password',1,'Password','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_btn_save',1,'Save','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_btn_cancel',1,'Cancel','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_msg_er_admin_email',1,'Value must be a valid email address!','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_msg_er_charset',1,'Value must be a valid charset!','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_msg_er_smtp_host',1,'Value must be a valid URL or IP address!','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_msg_er_smtp_port',1,'Value must be a valid integer, in the range (0 - 65535).','admin','2009-01-10 14:44:49'),
('admin_btn_radio_yes',1,'yes','admin','0000-00-00 00:00:00'),
('product_group_list_sort_name',1,'Name','admin','2009-01-10 15:37:32'),
('admin_btn_radio_no',1,'no','admin','0000-00-00 00:00:00'),
('admin_global_setup_ttip_012',1,'Members can input file extensions that will be displayed directly to members not using mod rewrite protection','admin','2009-05-20 12:22:24'),
('admin_global_setup_field_label_011',1,'Select file extension to be ignored:','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_010',1,'Enable/disable the log feature','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_010',1,'Log administrators','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_009',1,'Log members','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_008',1,'Log settings: ','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_007',1,'Force password generation:','admin','2009-01-10 15:37:42'),
('admin_btn_test_connection',1,'test connection','admin','0000-00-00 00:00:00'),
('admin_btn_cancel',1,'cancel','admin','2009-01-10 15:37:42'),
('admin_btn_save',1,'save','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_006',1,'Set current site IP: ','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_005',1,'Choose the number of records per page','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_005',1,'Default records per page: ','admin','2009-01-10 15:37:42'),
('admin_msg_er_0000',1,'Error:','admin','2009-01-10 15:38:23'),
('admin_global_setup_ttip_003',1,'Site Root server path','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_004',1,'URL where member will be redirected after they click on the logout link','admin','2009-05-20 12:22:24'),
('admin_btn_clear_all',1,'clear all','admin','2008-11-17 16:25:03'),
('admin_global_setup_field_label_003',1,'Script absolute path: ','admin','2009-01-10 15:37:42'),
('admin_msg_ok_0001',1,'Settings were changed successfully','admin','2009-01-12 08:58:32'),
('admin_global_setup_ttip_001',1,'Site title','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_004',1,'Redirect after logout: ','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_001',1,'Your site name: ','admin','2009-01-10 15:37:42'),
('admin_global_setup_header_comment',1,'Here you can edit information about paths and URLs\nNote: Do not make changes to the fields if you are not absolutely shure what you are doing','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_002',1,'Main script URL: ','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_002',1,'Root URL','admin','2009-01-10 15:37:42'),
('admin_config_security_ttip_pwd_period',1,'Period in seconds to count IP addresses that access one and the same account. Once the specified number of IP addresses for an account is reached, the account will be suspended.','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_captcha_char_max',1,'Maximum number of characters in the CAPTCHA image. The number must be bigger than in the Min Characters field. ','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_pwd_autoban',1,'Number of different IP adresses that can access one account before this account is banned','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_block_message',1,'Message that members will see when IP is temporary blocked','admin','2009-05-20 12:23:22'),
('admin_config_security_ttip_captcha_char_min',1,'Minimum number of characters in the CAPTCHA image. The number must be more than 1 ','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_block_period',1,'Defines how many seconds IP addresses will be blocked','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_before_ip',1,'Number of failed login attempts before an IP address is temporary blocked','admin','2009-01-10 15:38:23'),
('admin_config_lang_editor_label_name_tooltip',1,'Label must not contain spaces and special characters','admin','2008-09-18 16:11:52'),
('admin_config_security_login_before_ip',1,'Failed logins before IP block','admin','2009-01-10 15:38:23'),
('admin_config_security_login_before_captcha',1,'Failed logins before CAPTCHA','admin','2009-01-10 15:38:23'),
('admin_config_security_login_block_period',1,'Block period (sec.)','admin','2009-01-10 15:38:23'),
('admin_config_security_login_block_message',1,'Block message','admin','2009-01-10 15:38:23'),
('admin_coupon_edit_coupons_checkbox_dates_desc',1,'Don\'t limit date of usage','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_button_cancel',1,'cancel','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_button_save',1,'save','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_locked',1,'Locked:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_products',1,'Products:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_dates',1,'Dates:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_comment',1,'Comment:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_discount',1,'Discount:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_member_coupons_usage_count',1,'No of times the coupon can be used:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_coupons_usage_count',1,'Coupons Usage Number:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_label_coupon_name',1,'Coupon name:','admin','2008-12-19 16:19:41'),
('admin_coupon_edit_coupons_page_desc',1,'You can change details in the selected coupons','admin','2008-12-19 16:19:41'),
('product_add_cancel',1,'Cancel','admin','2008-12-26 06:12:26'),
('admin_coupon_edit_coupons_page_title',1,'Edit coupon','admin','2008-12-19 16:19:41'),
('admin_btn_add',1,'add','admin','2009-01-10 15:37:42'),
('admin_config_security_captcha_char_max',1,'Max Characters','admin','2009-01-10 15:38:23'),
('admin_config_security_pwd_settings',1,'Pasword sharing prevention system','admin','2009-01-10 15:38:23'),
('admin_config_security_pwd_autoban',1,'No of IP addresses before autoban','admin','2009-01-10 15:38:23'),
('admin_config_security_pwd_autoban_period',1,'Time period before autoban (seconds)','admin','2009-01-10 15:38:23'),
('admin_global_setup_ttip_006',1,'IP address must be specified for the mod rewrite protection','admin','2009-01-10 15:37:42'),
('admin_config_security_ttip_remember_me',1,'If enabled members can use the \"remeber me\" feature. \r\nNote: this will make the account access system less secure. ','admin','2009-05-20 12:23:22'),
('admin_global_setup_header_subject',1,'Global setup','admin','2009-01-10 15:37:42'),
('admin_config_security_captcha_settings',1,'CAPTCHA settings','admin','2009-01-10 15:38:23'),
('admin_config_security_captcha_char_min',1,'Min Characters','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_before_captcha',1,'Number of failed login attempts before CAPTCHA appears in the login form','admin','2009-01-10 15:38:23'),
('admin_config_security_login_feat_remember_me',1,'Enable \"Remember me\" feature','admin','2009-01-10 15:38:23'),
('admin_coupon_create_coupons_label_dates',1,'Dates:','admin','2009-01-10 14:10:11'),
('admin_config_security_login_settings',1,'Login settings','admin','2009-01-10 15:38:23'),
('admin_coupon_create_coupons_label_locked',1,'Locked:','admin','2009-01-10 14:10:11'),
('admin_btn_select_all',1,'select all','admin','2009-01-10 15:37:42'),
('admin_coupon_create_coupons_label_dates_tooltip',1,'Date range when the coupon can be used','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_discount',1,'Discount:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_discount_tooltip',1,'Generated coupons discount','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_coupon_count',1,'Coupon Codes Count:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_coupon_count_tooltip',1,'The number of coupon codes to be generated','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_comment',1,'Comment:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_comment_tooltip',1,'Comment is visible only to the administrator','admin','2009-01-10 14:10:11'),
('admin_config_lang_editor_delete_key_error',1,'Error has occured while deleting the key','admin','0000-00-00 00:00:00'),
('admin_coupon_create_coupons_label_code_length',1,'Code Length:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_coupon_name_tooltip',1,'The name of coupons group. All coupons will be created with this group name.','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_code_length_tooltip',1,'Generated coupons code length','admin','2009-01-10 14:10:11'),
('admin_global_setup_ttip_007',1,'If checked members won\'t be able to input custom passwords. The password will be generated by the system instead','admin','2009-05-20 12:22:24'),
('admin_config_lang_editor_label_value_tooltip',1,'Text that user will see instead of label','admin','2008-09-18 12:18:21'),
('admin_coupon_create_coupons_button_add',1,'Add','admin','2008-09-02 14:34:26'),
('admin_coupon_create_coupons_button_cancel',1,'Cancel','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_checkbox_dates_desc',1,'Don\'t limit the usage by date','admin','2009-01-10 14:10:11'),
('admin_config_security_page_description',1,'Here you can view and edit security settings','admin','2009-01-10 15:38:23'),
('admin_coupon_create_coupons_label_member_coupons_usage_count',1,'No of times the coupon can be used:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_member_coupons_usage_count_tooltip',1,'The number of times each coupon can be used by one and the same member','admin','2009-05-20 12:21:20'),
('admin_coupon_create_coupons_field_discount_type_value',1,'USD','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_field_discount_type_percent',1,'%','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_products',1,'Products:','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_products_tooltip',1,'Coupons can be used with the selected products. Hold Ctrl Key to select multple products.','admin','2009-01-10 14:10:11'),
('admin_config_security_page_title',1,'Security Settings','admin','2009-01-10 15:38:23'),
('admin_coupon_create_coupons_label_locked_tooltip',1,'Disable this coupons batch, but keep it in the database. Coupons can be enabled at a later time.','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_use_count_tooltip',1,'The number of times each coupon can be used','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_page_title',1,'Create coupons','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_page_desc',1,'You can create coupons group with coupons here','admin','2009-01-10 14:10:11'),
('admin_coupon_create_coupons_label_use_count',1,'Coupon Usage Number','admin','2009-01-10 14:10:11'),
('admin_config_lang_editor_delete_confirm',1,'Are you sure?','admin','2009-01-12 09:12:43'),
('admin_coupon_create_coupons_label_coupon_name',1,'Coupon name:','admin','2009-01-10 14:10:11'),
('admin_coupon_statistic_table_paid_value_yes',1,'yes','admin','2008-12-19 15:59:31'),
('admin_coupon_statistic_table_paid_value_no',1,'no','admin','2008-12-19 15:45:55'),
('admin_coupon_statistic_search_label_from',1,'From:','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_paid',1,'Paid','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_amount',1,'Amount','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_discount',1,'Discount','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_change_time',1,'Change time','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_member',1,'Member','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_product',1,'Product','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_period',1,'Period','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_button_search',1,'Search','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_table_coupon_code_group',1,'Coupon code / Group #','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_button_clear',1,'Clear','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_label_to',1,'to','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_label_and',1,'and','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_label_coupon_code',1,'Coupon code','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_search_label_period',1,'Period','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_page_desc',1,'Here you can view the statistics on coupons usage','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_page_title',1,'Coupon statistics','admin','2009-01-12 09:28:35'),
('admin_coupon_statistic_empty_list',1,'No Data','admin','2009-01-12 09:28:35'),
('admin_coupon_coupons_list_table_disabled_value_no',1,'no','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_empty_list',1,'No Data','admin','2008-10-23 14:24:44'),
('admin_coupon_coupons_list_table_disabled_value_yes',1,'yes','admin','2008-12-03 11:32:19'),
('admin_coupon_empty_list',1,'No data','admin','0000-00-00 00:00:00'),
('admin_coupon_coupon_groups_button_add_group',1,'Add Group','admin','2008-09-02 14:38:05'),
('admin_coupon_coupon_groups_table_action',1,'action','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_disabled',1,'Disabled','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_count_used',1,'Count/Used','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_expire_date',1,'Expiration Date','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_begin_date',1,'Begin Date','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_coupons_count',1,'Coupon Codes','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_table_id',1,'ID','admin','2009-01-10 14:09:05'),
('admin_admin_edit_msg_er_not_deleted',1,'Administrator account has not been removed.','admin','2009-01-10 14:55:17'),
('admin_log_login',1,'Logged in','admin','2009-01-10 15:19:29'),
('admin_coupon_coupon_groups_page_desc',1,'Coupons List','admin','2009-01-10 14:09:05'),
('admin_coupon_coupon_groups_page_title',1,'Coupons List','admin','2009-01-10 14:09:05'),
('admin_coupon_coupons_list_page_desc',1,'This is the list of coupons','admin','2008-12-19 16:06:54'),
('admin_log_logout',1,'Logged out','admin','2008-12-23 16:16:29'),
('admin_coupon_coupons_list_button_back',1,'Back','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_table_disabled',1,'Disabled','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_table_action',1,'action','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_table_count_used',1,'Number/Used','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_table_code',1,'Code','admin','2008-12-19 16:06:54'),
('admin_coupon_coupons_list_page_title',1,'Coupon - ','admin','2008-12-19 16:06:54'),
('admin_member_control_account_panel_transactions_list_label_details',1,'details','admin','2008-12-27 06:39:17'),
('product_list_unlock_button_alt',1,'Unlock','admin','2009-01-10 10:02:05'),
('admin_member_control_account_panel_transactions_list_table_transaction_id',1,'ID','admin','2009-01-10 10:11:14'),
('admin_config_lang_editor_edit_success',1,'Changes were saved successfully','admin','2009-01-10 14:47:00'),
('admin_config_lang_editor_edit_error',1,'Error has occured','admin','2008-12-08 17:08:11'),
('admin_config_lang_editor_new_label_name_error',1,'Error. Wrong label name.','admin','0000-00-00 00:00:00'),
('admin_config_lang_editor_label_action',1,'Action','admin','2008-09-18 14:09:10'),
('admin_config_lang_editor_key_delete',1,'Delete','admin','2008-09-16 16:53:18'),
('admin_config_lang_editor_label_value',1,'Value','admin','2008-09-18 12:18:21'),
('admin_config_lang_editor_label_name',1,'Label Name','admin','2008-09-18 14:57:41'),
('admin_config_lang_editor_key_name',1,'Name','admin','2009-01-10 16:39:13'),
('admin_config_lang_editor_key_translation',1,'Translation','admin','2009-01-10 16:39:13'),
('admin_levels_email_newsletter',1,'Email newsletters','admin','2009-01-10 14:55:45'),
('admin_levels_action',1,'Action','admin','2009-01-10 14:55:45'),
('product_save_product_price_year5',1,'Five years','admin','2009-01-12 09:15:57'),
('admin_page_edit_language',1,'Language','admin','0000-00-00 00:00:00'),
('admin_design_manager_saved_successfully',1,'New design settings was changed successfully','admin','2009-01-10 14:44:26'),
('admin_design_manager_error',1,'Please check your input, some error occured.','admin','2009-01-10 14:44:26'),
('admin_access_level_email_newsletter',1,'Notification by email:','admin','2008-12-26 08:43:40'),
('admin_access_level_msg_er_level_name',1,'Must be not empty!(1-64)','admin','2008-12-26 08:43:40'),
('admin_msg_ok_0003',1,'Information was set to default successfully','admin','2008-09-12 16:40:06'),
('admin_member_control_error_mbr_notchecked',1,'Error: No member is checked','admin','2009-01-10 10:14:13'),
('admin_msg_er_0014',1,'Maximum allowed 32K symbols','admin','2008-09-12 16:40:06'),
('admin_access_level_header_subject_edit',1,'Access level','admin','2008-12-26 08:43:40'),
('admin_access_level_header_comment_edit',1,'Access level comment','admin','2008-12-26 08:43:40'),
('admin_access_level_header_subject_add',1,'Add level','admin','2008-12-04 16:09:52'),
('admin_access_level_header_comment_add',1,'Add admin level on this page','admin','2008-12-04 16:09:52'),
('admin_access_level_btn_add',1,'add','admin','2008-12-04 16:09:52'),
('admin_access_level_btn_save',1,'save','admin','2008-12-26 08:43:40'),
('admin_access_level_btn_cancel',1,'cancel','admin','2008-12-26 08:43:40'),
('admin_access_level_access_category',1,'Access category:','admin','2008-12-26 08:43:40'),
('admin_access_level_name',1,'Name:','admin','2008-12-26 08:43:40'),
('admin_access_level_access_category_activity_logging',1,'Activity logs','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_administrator_control',1,'Administrator control','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_system_configuration',1,'System configuration','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_coupon',1,'Coupon','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_newsletter',1,'Newsletter','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_transaction',1,'Statistics','admin','2009-01-10 14:55:45'),
('admin_access_level_access_category_member_control',1,'Member control','admin','2009-01-10 14:55:45'),
('admin_page_edit_tab1',1,'Terms of use','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab2',1,'Privacy policy','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab3',1,'Success payment page','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab4',1,'Cancel payment page','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab5',1,'Registered sucessfully page','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab6',1,'Activation successful page','admin','2008-09-12 16:40:06'),
('admin_page_edit_tab7',1,'Activation error page','admin','2008-09-12 16:40:06'),
('admin_newsletter_email_templates_delete_msg_success',1,'Template was deleted successfully','admin','0000-00-00 00:00:00'),
('admin_newsletter_email_templates_delete_error_notdeleted',1,'Error: Template was not deleted','admin','0000-00-00 00:00:00'),
('admin_newsletter_email_templates_add_msg_success',1,'Template was created successfully','admin','2008-11-05 15:08:58'),
('admin_newsletter_email_templates_error_field_empty',1,'Error: Not all required fields are filled','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_add_error_notadded',1,'Error: Template was not created','admin','0000-00-00 00:00:00'),
('admin_newsletter_email_templates_error_field_name_toolong',1,'Error: Template name is too long','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_error_field_subject_toolong',1,'Error: Subject is too long','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_error_field_message_toolong',1,'Error: Message is too long','admin','2008-11-21 14:08:01'),
('admin_access_level_msg_er_name_is_exist',1,'The name of access list already exists.','admin','2008-12-26 08:43:40'),
('admin_access_level_msg_er_not_deleted',1,'List of access has not been removed.','admin','2009-01-10 14:55:45'),
('admin_access_level_msg_ok_deleted',1,'List of access was successfully removed.','admin','2009-01-10 14:55:45'),
('admin_msg_are_you_sure',1,'Are you sure?','admin','2009-01-12 08:58:32'),
('admin_menu_mailer_settings',1,'Mailer Settings','admin','2009-01-12 08:57:39'),
('admin_mailer_settings_saved_successfully',1,'New mailer settings was changed successfully','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_error',1,'Please check your input, some error occured.','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_header_subject',1,'Mailer Settings','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_header_comment',1,'You can change the mailer settings using this page','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_header_general',1,'General settings','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_header_smtp',1,'SMTP settings','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_msg_er_smtp_user',1,'Value must be a valid username!','admin','2009-05-20 12:24:56'),
('product_group_list_sort_product_count',1,'#products','admin','2009-01-10 15:37:32'),
('admin_mailer_settings_msg_er_smtp_pass',1,'Value must be a valid password!','admin','2009-01-10 14:44:49'),
('product_group_list_action',1,'Action','admin','2009-01-10 15:37:32'),
('product_group_list_add_button',1,'Add group','admin','2009-01-10 15:37:32'),
('product_group_list_id',1,'ID','admin','2009-01-10 15:37:32'),
('product_delete_ask_confirm',1,'Are you sure','undef','2009-01-10 10:02:05'),
('admin_newsletter_send_email_step1_error_field_from_toolong',1,'Error: The From field is too long','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_error_field_from_email_wrong',1,'Error: From contains an invalid email','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_error_field_template_wrong',1,'Error: Template is invalid','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_error_users_wrong',1,'Error: Users are not selected','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_error_pgroups_wrong',1,'Error: Groups are not selected','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_error_products_wrong',1,'Error: Products are not selected','admin','2008-12-01 10:12:59'),
('product_group_add_page_title',1,'Add groups','admin','2008-10-17 12:26:14'),
('product_group_add_page_descr',1,'Product add page','admin','2008-10-17 12:26:14'),
('admin_newsletter_send_email_step1_error_field_to_empty',1,'Error: Field To: is empty. Select and add necessary parameters','admin','2008-12-01 10:12:59'),
('product_group_add_page_group_descr',1,'Description','admin','2008-10-17 12:26:14'),
('product_group_add_page_group_name',1,'Name','admin','2008-10-17 12:26:14'),
('product_group_add_page_group_products',1,'Products','admin','0000-00-00 00:00:00'),
('product_group_add_page_add_button',1,'Add','admin','2008-10-17 12:26:14'),
('product_group_add_page_cancel_button',1,'Cancel','admin','2008-10-17 12:26:14'),
('admin_newsletter_send_email_step1_error_field_from_empty',1,'Error: The From field is empty','admin','2008-12-01 10:12:59'),
('group_add_error_products',1,'Some products must be selected','admin','0000-00-00 00:00:00'),
('group_add_error_name',1,'Name is empty, or too large (1-255 chars)','admin','2008-10-17 12:26:14'),
('group_add_error_descr',1,'Description is empty or too large (1 - 65,535 chars)','admin','2008-10-17 12:26:14'),
('admin_mailer_settings_tested_successfully',1,'SMTP connection was successfully established.','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_tested_fail',1,'SMTP connection could not be established.','admin','2009-01-10 14:44:49'),
('admin_administrators_list_header_subject',1,'Administrator accounts control','admin','2009-01-10 14:55:17'),
('admin_administrators_list_header_comment',1,'Manage administrator accounts here','admin','2009-01-10 14:55:17'),
('admin_administrators_list_btn_add',1,'Add administrator','admin','2009-01-10 14:55:17'),
('admin_administrators_list_user_name',1,'Member name','admin','2009-05-20 12:30:39'),
('admin_administrators_list_level',1,'Level','admin','2009-01-10 14:55:17'),
('admin_administrators_list_last_login',1,'Last login','admin','2009-01-10 14:55:17'),
('admin_administrators_list_action',1,'Action','admin','2009-01-10 14:55:17'),
('admin_administrators_list_msg_ok_deleted',1,'Accounts were deleted successfully','admin','2009-01-10 14:55:17'),
('admin_admin_edit_header_subject_edit',1,'Admin edit','admin','2009-01-10 14:54:20'),
('admin_admin_edit_header_subject_edit_super',1,'Super admin edit','admin','2008-12-23 13:24:20'),
('admin_admin_edit_header_subject_add',1,'Add administration','admin','2009-01-10 14:50:12'),
('admin_admin_edit_header_comment_add',1,'Add administrators on this page','admin','2009-01-10 14:50:12'),
('admin_admin_edit_header_comment_edit',1,'Edit admin details on this page','admin','2009-01-10 14:54:20'),
('admin_admin_edit_header_comment_edit_super',1,'Edit super admin details here','admin','2008-12-23 13:24:20'),
('admin_mailer_settings_tested_auth_fail',1,'Members authentication failed, please check your Username and Password.','admin','2009-05-20 12:24:56'),
('admin_admin_edit_btn_save',1,'save','admin','2009-01-10 14:54:20'),
('admin_admin_edit_btn_add',1,'add','admin','2009-01-10 14:50:12'),
('admin_admin_edit_btn_cancel',1,'cancel','admin','2009-01-10 14:54:20'),
('admin_admin_edit_id',1,'ID:','admin','2009-01-10 14:54:20'),
('admin_admin_edit_username',1,'Username: ','admin','2009-01-10 14:54:20'),
('admin_admin_edit_email',1,'Email: ','admin','2009-01-10 14:54:20'),
('admin_admin_edit_generate_new_password',1,'Generate new password:','admin','2009-01-10 14:54:20'),
('admin_admin_edit_password',1,'Password: ','admin','2009-01-10 14:54:20'),
('admin_admin_edit_retype_password',1,'Retype password: ','admin','2009-01-10 14:54:20'),
('admin_admin_edit_access_level',1,'Access level: ','admin','2009-01-10 14:54:20'),
('group_add_success',1,'Group is added','admin','2009-01-10 15:37:32'),
('group_delete_error',1,'Error while deleting the group','undef','2009-01-10 15:37:32'),
('group_delete_success',1,'Group is deleted','undef','2009-01-10 15:37:32'),
('group_not_empty',1,'You can\'t delete the group with products in it. First you must change the product group to another group.','undef','2009-01-10 15:37:32'),
('product_group_save_page_title',1,'Edit group','undef','2008-10-17 11:59:12'),
('product_group_save_page_descr',1,'Set group information','undef','2008-10-17 11:59:12'),
('group_save_error_name',1,'Name is empty, or too large (1-255 chars)','admin','2008-10-17 11:59:12'),
('group_save_error_descr',1,'Description is empty or too large (1 - 65,535 chars)','admin','2008-10-17 11:59:12'),
('product_group_save_page_group_name',1,'Name','admin','2008-10-17 11:59:12'),
('product_group_save_page_group_descr',1,'Description ','admin','2008-10-17 11:59:12'),
('product_group_save_page_save_button',1,'Save','admin','2008-10-17 11:59:12'),
('product_group_save_page_cancel_button',1,'Cancel','admin','2008-10-17 11:59:12'),
('group_saved_success',1,'Group is saved successfully','admin','2009-01-10 15:37:32'),
('group_not_exists',1,'Group doesn\'t exist','admin','0000-00-00 00:00:00'),
('user_authorize_net_redirect_header',1,'Press the Buy now button or wait while redirecting...','user','2008-12-09 14:38:40'),
('cart_authorize_net_buy_now_button',1,'Buy now button','undef','0000-00-00 00:00:00'),
('admin_admin_edit_msg_er_email_exist',1,'This email already exists!','admin','2009-01-10 14:54:20'),
('admin_admin_edit_msg_er_name_is_exist',1,'This username already exists!','admin','2009-01-10 14:54:20'),
('user_active_products_title',1,'Active Products','user','2008-12-27 06:38:47'),
('admin_menu_admin_log',1,'Administrator Log','admin','2009-01-12 08:57:39'),
('user_active_products_descr',1,'The products you are currently subscribed to','user','2008-12-27 06:38:47'),
('admin_menu_user_log',1,'Members Log','admin','2009-05-20 12:14:54'),
('user_active_products_node_cost',1,'Cost','user','0000-00-00 00:00:00'),
('user_active_products_node_subscr_start',1,'Subscription start','user','2008-12-27 06:38:47'),
('user_active_products_node_subscr_end',1,'Subscription ends','user','2008-12-27 06:38:47'),
('user_active_products_node_period',1,'Operating period','user','2008-12-27 06:38:47'),
('user_active_products_node_button_cancel_subscr',1,'Cancel Subscription','user','2008-12-27 06:38:47'),
('user_sale_products_title',1,'Products','user','2008-12-27 06:36:22'),
('user_sale_products_descr',1,'Available products ','user','2008-12-27 06:36:22'),
('user_sale_products_group_filter',1,'Filter by group','user','2008-12-27 06:36:22'),
('user_sale_products_group_button',1,'Show','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_no_available',1,'No products available ','user','2008-11-18 15:57:04'),
('user_sale_products_nodes_group',1,'Group','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_subscr_type',1,'Type of subscription','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_subscr_type_one_time',1,'one time','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_trial_duration',1,'Trial duration','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_subscr_type_recurring',1,'Recurring','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_trial_period_type_day',1,'Day','user','2008-12-08 09:59:29'),
('user_sale_products_nodes_trial_period_type_week',1,'Week','user','2008-09-16 13:23:42'),
('user_sale_products_nodes_trial_period_type_month',1,'Month','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_trial_period_type_year',1,'Year','user','2008-12-22 11:18:20'),
('user_sale_products_nodes_trial_price',1,'Trial price','user','0000-00-00 00:00:00'),
('user_sale_products_nodes_price',1,'Price','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_day',1,'Day','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_week',1,'Week','user','0000-00-00 00:00:00'),
('user_sale_products_nodes_price_month',1,'Month','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_month3',1,'Three month','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_month6',1,'Six month','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_year',1,'Year','user','2008-12-27 06:36:22'),
('user_sale_products_nodes_price_year5',1,'Five year','user','2008-12-27 06:36:22'),
('admin_menu_billing',1,'Billing','admin','2009-01-12 08:57:39'),
('admin_admin_edit_msg_er_name',1,'Invalid login!','admin','2009-01-10 14:54:20'),
('admin_admin_edit_msg_er_email',1,'Value must be a valid email address!','admin','2009-01-10 14:54:20'),
('admin_logging_admin_title',1,'Administrator Log','admin','2009-01-10 15:19:29'),
('admin_logging_admin_description',1,'View administrators activity log','admin','2009-01-10 15:19:29'),
('admin_stats_subscr_header_subject',1,'Subscriptions billing','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_header_comment',1,'Here you can see the subscriptions statistics.','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_search_by',1,'Search by','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_search_option_subscr_id',1,'Subscription ID','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_search_option_price',1,'Price','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_search_option_user_name',1,'Members name','admin','2009-05-20 12:18:25'),
('admin_stats_subscr_search_option_product_name',1,'Product name','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_search_payment_date',1,'Payment Date','admin','2009-01-10 10:14:40'),
('admin_msg_er_access_denied',1,'You don\'t have permissions for this action!','admin','2008-12-09 11:00:09'),
('admin_mailer_settings_msg_er_send_to_count',1,'Value must be a valid integer, in the range (1 - 65535).','admin','2009-01-10 14:44:49'),
('admin_mailer_settings_email_send_to_count',1,'Number of emails in one pack','admin','2009-01-10 14:44:49'),
('admin_logging_btn_clear',1,'Clear','admin','2009-01-12 08:58:32'),
('admin_logging_btn_delete',1,'Delete','admin','2009-01-10 15:19:29'),
('admin_menu_administrator_list',1,'Administrator List','admin','2009-01-12 08:57:39'),
('admin_menu_add_administrator',1,'Add Administrator','admin','2009-01-12 08:57:39'),
('admin_stats_subscr_btn_show',1,'Show','admin','2009-01-10 10:14:40'),
('admin_newsletter_send_email_msg_email_is_enqueued',1,'E-mails are enqueued into the mailer. They will be sent later.','admin','2008-11-05 15:12:37'),
('product_add_free',1,'It\'s free ','admin','2009-01-12 09:15:57'),
('product_save_free',1,'It\'s free','admin','0000-00-00 00:00:00'),
('admin_newsletter_email_templates_list_page_title',1,'Template list','admin','2008-11-28 18:09:27'),
('admin_newsletter_email_templates_list_page_desc',1,'This is the page to manage e-mail templates. You can add, edit or delete any template from the list.','admin','2008-11-28 18:09:27'),
('admin_msg_delete_question',1,'Are you sure you want to delete the member?','admin','2009-01-10 15:19:39'),
('admin_newsletter_email_templates_list_table_header_name',1,'Name','admin','2008-11-28 18:09:27'),
('admin_newsletter_email_templates_list_table_header_action',1,'Action','admin','2008-11-28 18:09:27'),
('admin_newsletter_email_templates_list_btn_add_template',1,'Add Template','admin','2008-11-28 18:09:27'),
('admin_newsletter_email_templates_edit_page_title',1,'Edit template:','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_edit_page_desc',1,'You can change e-mail template fields here.','admin','2008-11-21 14:08:01'),
('admin_logging_col_person',1,'Person','admin','2009-01-10 15:19:29'),
('admin_img_tip_view',1,'View','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_page_title',1,'Email History','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_page_desc',1,'This is the list of all newsletters sent to the registered members','admin','2009-05-20 12:20:48'),
('product_add_error_free_product',1,'Paid product must include at least one price','admin','2008-12-26 06:12:26'),
('product_save_error_free_product',1,'Paid products must include at least one price','admin','2009-01-12 09:15:57'),
('admin_newsletter_select_email_keys_desc',1,'Choose the field to add','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_add_label_message',1,'Message:','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_add_label_subject',1,'Subject:','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_add_label_template_name',1,'Template name:','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_add_page_desc',1,'You can create a new e-mail template here. Fill in all the required fields and click the Add button.','admin','2008-11-05 15:09:46'),
('admin_newsletter_email_templates_add_page_title',1,'Create template','admin','2008-11-05 15:09:46'),
('admin_newsletter_email_history_label_date',1,'Date:','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_label_period',1,'or Period:','admin','2008-12-03 12:12:47'),
('admin_btn_show',1,'Show','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_table_header_subject',1,'Subject','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_table_header_from',1,'From','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_table_header_to',1,'To','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_table_header_date',1,'Date','admin','2008-12-03 12:12:47'),
('admin_newsletter_email_history_table_header_action',1,'action','admin','2008-12-03 12:12:47'),
('admin_admin_edit_msg_er_not_added',1,'Administrator account has not been added!','admin','2009-01-10 14:54:20'),
('admin_admin_edit_msg_er_not_updated',1,'Administrator account has not been changed!','admin','2009-01-10 14:54:20'),
('admin_admin_edit_msg_er_not_found',1,'Administrator account was not found!','admin','2009-01-10 14:55:17'),
('admin_newsletter_email_history_view_label_to',1,'To:','admin','2008-11-11 11:33:11'),
('admin_newsletter_email_history_view_label_from',1,'From:','admin','2008-11-11 11:33:11'),
('admin_newsletter_email_history_view_label_subject',1,'Subject:','admin','2008-11-11 11:33:11'),
('admin_newsletter_email_history_view_label_message',1,'Message:','admin','2008-11-11 11:33:11'),
('admin_newsletter_send_email_step1_page_title',1,'Send newsletter','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_step1_page_desc',1,'Select an e-mail template and member groups you want to send the message to.','admin','2009-05-20 12:19:45'),
('admin_newsletter_send_email_template_list_empty',1,'You should create newsletter templates first.','admin','2009-01-19 13:01:44'),
('admin_newsletter_send_email_step1_label_from',1,'From:','admin','2008-12-01 10:12:59'),
('admin_newsletter_send_email_step1_label_template',1,'Template:','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_step1_user_category_all',1,'all members','admin','2009-05-20 12:19:45'),
('admin_newsletter_send_email_step1_user_category_all_expired',1,'all expired members','admin','2009-05-20 12:19:45'),
('admin_newsletter_send_email_step1_user_category_all_active',1,'all active members','admin','2009-05-20 12:19:45'),
('admin_newsletter_send_email_step1_group_category_all',1,'all groups','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_step1_product_category_all',1,'all products','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_step1_label_to',1,'to:','admin','2008-12-05 16:06:17'),
('admin_btn_next',1,'next','admin','2008-12-05 16:06:17'),
('admin_newsletter_send_email_step2_page_title',1,'Send mail: step #2','admin','2008-11-05 15:12:37'),
('admin_admin_edit_msg_er_access_denied',1,'You don\'t have permissions for this action!','admin','2009-01-10 15:20:14'),
('admin_newsletter_send_email_step2_page_desc',1,'This is the step 2 of 3 steps of sending email. Click the button Send Now to send e-mail immediately or you can enqueue this email to send it later by mailer.\n','admin','2008-11-05 15:12:37'),
('admin_newsletter_send_email_step2_label_subject',1,'Subject:','admin','2008-11-05 15:12:37'),
('admin_newsletter_send_email_step2_label_message',1,'Message:','admin','2008-11-05 15:12:37'),
('admin_btn_send_now',1,'Send Now','admin','2008-11-05 15:12:37'),
('admin_btn_save_as_template',1,'Save as Template','admin','2008-11-05 15:12:37'),
('admin_btn_enqueue',1,'Enqueue','admin','2008-11-05 15:12:37'),
('admin_newsletter_send_email_step3_page_title',1,'Send mail: step #3','admin','2008-11-05 15:12:45'),
('admin_newsletter_send_email_step3_page_desc',1,'Click the triangle to start the process of sending e-mail. You can also pause the sending process or cancel it.','admin','2008-11-05 15:12:45'),
('product_add_error_discount_large_price',1,'Discount value is bigger than price','admin','0000-00-00 00:00:00'),
('product_save_error_discount_large_price',1,'Discount value is bigger than price','admin','2009-01-12 09:15:57'),
('admin_stats_subscr_tbl_subscription_id',1,'Subscription ID','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_tbl_user_name',1,'Members Name','admin','2009-05-20 12:18:25'),
('admin_stats_subscr_tbl_date',1,'Date','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_tbl_transactions',1,'Transactions','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_tbl_subscr_type',1,'Subscription Type','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_tbl_regular_price',1,'Regular Price','admin','2009-01-10 10:14:40'),
('admin_stats_subscr_tbl_href_details',1,'details','admin','2009-01-10 10:14:40'),
('admin_logging_btn_show',1,'Show','admin','2009-01-12 08:58:32'),
('admin_stats_subscr_tbl_onetime',1,'onetime','admin','2009-01-10 10:14:40'),
('admin_logging_col_ip',1,'IP','admin','2009-01-10 15:19:29'),
('admin_logging_col_time',1,'Time','admin','2009-01-10 15:19:29'),
('admin_logging_col_action',1,'Action','admin','2009-01-10 15:19:29'),
('admin_logging_col_record',1,'Admin action','admin','2009-01-10 15:19:29'),
('admin_stats_subscr_tbl_recurring',1,'recurring','admin','2008-12-23 15:14:53'),
('admin_newsletter_email_history_msg_delete_success',1,'Email History item was deleted successfully','admin','2008-11-10 11:47:59'),
('admin_newsletter_email_history_error_id_invalid',1,'Error: Email History ID is invalid','admin','0000-00-00 00:00:00'),
('admin_newsletter_send_email_step2_error_users_not_subscribed',1,'Error: Users are not subscribed to newsletter','admin','2008-09-15 12:49:12'),
('admin_newsletter_send_email_step2_error_email_isnot_added',1,'Error: Email is not added to the sendlist','admin','0000-00-00 00:00:00'),
('admin_msg_er_0017',1,'Not deleted','admin','0000-00-00 00:00:00'),
('admin_msg_er_0018',1,'Not found','admin','0000-00-00 00:00:00'),
('admin_stats_subscr_err_date',1,'Value must be a valid date!','admin','2009-01-10 10:14:40'),
('admin_coupon_create_coupons_error_field_discount_prc_not_in_range',1,'Error: You must select percents in the Discount field. The range for values is 1-99','admin','0000-00-00 00:00:00'),
('admin_coupon_create_coupons_error_only_spaces',1,'Error: Coupon name must contain alphabetical characters','admin','0000-00-00 00:00:00'),
('admin_coupon_create_coupons_error_field_discount_val_notpositive',1,'Error: Discount must be a positive number','admin','0000-00-00 00:00:00'),
('admin_coupon_coupon_groups_table_discount',1,'Discount','admin','2009-01-10 14:09:05'),
('user_sale_products_order_button',1,'order','user','2008-12-27 06:36:22'),
('admin_logging_date',1,'Date','admin','2009-01-12 08:58:32'),
('admin_msg_er_0016',1,'Wrong date','admin','2009-01-10 15:19:39'),
('admin_member_control_error_suspend_email_not_sent',1,'Error: System email is not sent to the suspended members','admin','0000-00-00 00:00:00'),
('admin_member_control_approve_suspend_reason_error_not_exist',1,'Error: Suspend reason doesn\'t exist','admin','0000-00-00 00:00:00'),
('admin_member_control_error_user_not_exist',1,'Error: User doesn\'t exist','admin','0000-00-00 00:00:00'),
('admin_member_control_error_member_info_email_not_sent',1,'Error: Email about the account changes is not sent to the member ','admin','0000-00-00 00:00:00'),
('admin_member_control_error_password_not_changed',1,'Error: Password is not changed','admin','0000-00-00 00:00:00'),
('admin_login_form_msg_er_ip_banned',1,'IP address is banned: ','admin','2009-01-12 08:57:39'),
('user_remind_password_input_capcha',1,'Input code from the image','user','2008-12-22 11:32:03'),
('user_remind_password_error_capcha',1,'Error: Please check the code from the image','user','2008-12-19 17:10:02'),
('user_active_products_node_period_day',1,'Day','user','2008-12-19 14:16:39'),
('user_active_products_node_period_month',1,'Month','user','2008-11-24 12:44:29'),
('user_active_products_node_period_month3',1,'Three month','user','0000-00-00 00:00:00'),
('user_active_products_node_period_month6',1,'Six month\'s','user','0000-00-00 00:00:00'),
('user_active_products_node_period_year',1,'Year','user','2008-12-27 06:38:47'),
('user_active_products_node_period_year5',1,'Five years','user','0000-00-00 00:00:00'),
('user_error_login_incorrect',1,'Error: Login is incorrect','user','2008-12-19 17:09:44'),
('user_error_email_incorrect',1,'Error: Email is incorrect','user','2008-12-22 11:32:03'),
('user_error_email_not_exist',1,'Error: Email doesn\'t exist','user','0000-00-00 00:00:00'),
('user_remind_password_error_email_not_sent',1,'Error: Email is not sent','user','0000-00-00 00:00:00'),
('user_remind_password_error_login_empty',1,'Error: Login is empty','user','2008-11-04 16:38:09'),
('user_remind_password_error_email_empty',1,'Error: Email is empty','user','0000-00-00 00:00:00'),
('user_remind_password_error_capcha_empty',1,'Error: Input code from the image is required','user','2008-11-10 14:30:37'),
('user_remind_password_msg_password_is_sent',1,'Password is sent to your email','user','2008-12-19 14:15:56'),
('user_remind_password_error_login_toolong',1,'Error: Login is too long','user','0000-00-00 00:00:00'),
('user_remind_password_error_email_toolong',1,'Error: Email is too long','user','0000-00-00 00:00:00'),
('user_remind_password_page_title',1,'Remind password','user','2008-12-22 11:32:03'),
('user_remind_password_label_login',1,'Login:','user','2008-12-22 11:32:03'),
('user_remind_password_label_email',1,'Email:','user','2008-12-22 11:32:03'),
('admin_logging_col_referer',1,'Referrer','admin','2008-12-23 15:30:30'),
('directories_add_protection_method_www_auth',1,'WWW Authentication','admin','2009-01-10 10:06:39'),
('admin_logging_user_title',1,'Members log','admin','2009-05-20 12:07:15'),
('admin_logging_user_description',1,'View members activity log','admin','2009-05-20 12:07:15'),
('admin_logging_col_url',1,'URL','admin','2008-12-23 15:30:30'),
('directories_list_title',1,'Directory protection list','admin','2009-01-10 10:09:59'),
('directories_directory_name',1,'Directory name','admin','2009-01-10 10:09:59'),
('directories_protected_directory',1,'Protected directory','admin','2009-01-10 10:09:59'),
('directories_number_of_products',1,'#products','admin','2009-01-10 10:09:59'),
('directories_action',1,'Action','admin','2009-01-10 10:09:59'),
('directories_action_edit',1,'Edit directory','admin','2008-12-19 14:24:53'),
('directories_action_delete',1,'Delete','admin','2009-01-10 10:09:59'),
('directories_add_directory',1,'add directory','admin','2009-01-10 10:09:59'),
('directories_list_description',1,'This is the list of all your protected directories ','admin','2009-01-10 10:09:59'),
('directories_list_id',1,'ID','admin','2009-01-10 10:09:59'),
('directories_add_protection_method_mod_rewrite_standard',1,'Standard Mod_Rewrite','admin','2009-01-10 10:06:39'),
('directories_add_protection_method_mod_rewrite_cookies',1,'Cookie-based Mod_Rewrite','admin','2009-01-10 10:06:39'),
('directories_add_protection_method_php_prepend',1,'PHP Prepend','admin','2009-01-10 10:06:39'),
('directories_add_protection_directory_not_found_on_server',1,'Directory not found on the server','admin','2009-01-10 10:09:59'),
('directories_add_protection_method',1,'Protection Method','admin','2009-01-10 10:06:39'),
('directories_add_directory_name',1,'Directory Name','admin','2009-01-10 10:06:39'),
('directories_add_protected_directory_url',1,'Protected Directory URL','admin','2009-01-10 10:06:39'),
('directories_add_directory_itself',1,'Directory','admin','2009-01-10 10:06:39'),
('directories_add_title',1,'Add directory protection','admin','2008-12-27 05:08:58'),
('directories_add_description',1,'Use this form to protect any directory','admin','2008-12-27 05:08:58'),
('admin_newsletter_send_email_step3_tip_start',1,'start','admin','2008-11-05 15:12:45'),
('admin_newsletter_send_email_step3_tip_pause',1,'pause','admin','2008-11-05 15:12:45'),
('admin_newsletter_send_email_step3_tip_cancel',1,'cancel','admin','2008-11-05 15:12:45'),
('admin_newsletter_send_email_step3_label_sent_letters_number',1,'Number of sent letters:','admin','2008-11-05 15:12:45'),
('admin_newsletter_send_email_step3_label_total_letters',1,'Total letters to send:','admin','2008-11-05 15:12:45'),
('admin_newsletter_email_templates_error_field_template_name_only_spaces',1,'Error: Template name must contain alphabetical characters','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_error_field_subject_only_spaces',1,'Error: Subject must contain alphabetical characters','admin','2008-11-21 14:08:01'),
('admin_newsletter_email_templates_error_field_message_only_spaces',1,'Error: Message must contain alphabetical characters','admin','2008-11-21 14:08:01'),
('admin_msg_er_0019',1,'Undefined ID','admin','2008-12-23 15:34:36'),
('user_active_products_node_type_one_time',1,'One time','user','2008-12-27 06:40:12'),
('user_active_products_node_type_recc',1,'Recurring \n\n\n','user','2008-10-30 15:39:09'),
('user_active_products_node_free',1,'Free','user','2008-12-23 16:22:49'),
('user_info_product_cost',1,'Cost','user','2008-12-23 16:22:49'),
('user_info_product_subscr_start',1,'Subscribtion start','user','2008-12-23 16:22:49'),
('user_info_product_subscr_end',1,'Subscribtion ends','user','2008-12-23 16:22:49'),
('user_info_product_period',1,'Operating period','user','2008-12-23 16:22:49'),
('user_info_product_links',1,'Links','user','2008-12-23 16:22:49'),
('user_info_product_period_day',1,'Day','user','2008-12-19 14:20:22'),
('user_info_product_period_month',1,'Month','user','2008-11-24 12:06:06'),
('user_info_product_period_month3',1,'Three month','user','0000-00-00 00:00:00'),
('user_info_product_period_month6',1,'Six month\'s','user','0000-00-00 00:00:00'),
('user_info_product_period_year',1,'Year','user','2008-12-23 16:22:49'),
('user_info_product_period_year5',1,'Five years','user','0000-00-00 00:00:00'),
('user_cancel_subscr_title',1,'Cancel product','user','2008-12-08 13:29:47'),
('user_cancel_subscr_descr',1,'Cancel product subscription','user','2008-12-08 13:29:47'),
('user_cancel_subscr_product_name',1,'Product','user','2008-12-08 13:29:38'),
('user_cancel_subscr_approve',1,'Please Approve','user','2008-12-08 13:29:38'),
('user_cancel_subscr_approve_label',1,'Type in the \"I confirm\" phrase to approve the subscription cancellation','user','2008-12-08 13:29:38'),
('user_cancel_subscr_approve_phrase',1,'I confirm','user','2008-12-08 13:29:38'),
('user_cancel_subscr_button_cancel',1,'cancel product','user','2008-12-08 13:29:38'),
('user_cancel_subscr_product_not_exists',1,'Sorry, this subscription is not active','user','2008-12-08 13:29:47'),
('user_cancel_subscr_phrase_incorrect',1,'Check phrase is incorrect. Retype it.','user','2008-12-08 13:29:47'),
('admin_menu_payment_system_settings',1,'Payment System Settings','admin','2009-01-12 08:57:39'),
('admin_payment_system_header_subject',1,'Payment Systems','admin','2009-01-10 14:44:54'),
('admin_payment_system_header_comment',1,'This is the list of available payment options','admin','2009-01-10 14:44:54'),
('admin_payment_system_name',1,'System Name','admin','2009-01-10 14:44:54'),
('admin_payment_system_status',1,'Status','admin','2009-01-10 14:44:54'),
('admin_payment_system_action',1,'Action','admin','2009-01-10 14:44:54'),
('admin_payment_system_active',1,'active','admin','2009-01-10 14:44:54'),
('admin_payment_system_inactive',1,'inactive','admin','2009-01-10 14:44:54'),
('user_cancel_subscr_canceled',1,'Subscriptions successfully canceled. \nYou cannot access now the protected directory included to the subscribed product. \nPlease visit you customer payment account.','user','2008-12-08 13:29:47'),
('admin_payment_system_paypal_business',1,'Merchant email address:','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_sandbox',1,'Use PayPal Sandbox:','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_sandbox_ttip',1,'Check this if you want to use a Paypal testing server, so no actual monetary transactions are made. You need to have a developer account with Paypal, and be logged into the developer panel in another browser window for the transaction to be successful.','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_business_ttip',1,'Used to identify you with PayPal.','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_header_subject',1,'PayPal','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_header_comment',1,'Specify your PayPal account details','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_msg_er_business',1,'Value must be a valid email address!','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_btn_cancel',1,'cancel','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_btn_save',1,'save','admin','2009-01-10 14:44:58'),
('user_cart_btn_cancel',1,'Cancel','user','2008-12-27 06:36:45'),
('admin_payment_system_authorize_net_btn_save',1,'save','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_header_subject',1,'Authorize.net','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_header_comment',1,'Specify your Authorize.net account details','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_test',1,'Demo Mode:','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_api_login',1,'Authorize.Net API Login ID:','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_transaction_key',1,'Authorize.Net Transaction Key:','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_msg_er_api_login',1,'Must be not empty!','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_msg_er_transaction_key',1,'Must be not empty!','admin','2008-12-08 17:05:31'),
('directories_add_protection_directory_name_is_empty',1,'Directory Name is empty or too long','admin','2009-01-10 10:09:59'),
('directories_add_protection_directory_url_is_empty',1,'Directory URL is empty or too long','admin','2009-01-10 10:09:59'),
('directories_add_protection_directory_can_not_be_protected',1,'Directory cannot be protected','both','2009-05-19 01:19:00'),
('directories_add_protection_directory_protection_method_not_selected',1,'Protection Method not selected','admin','0000-00-00 00:00:00'),
('directories_add_protection_method_not_selected',1,'Protection Method not selected','admin','2009-01-10 10:09:59'),
('directories_add_protection_directory_is_not_protectable',1,'Directory cannot be protected','admin','2009-01-10 10:09:59'),
('directories_add_protection_directory_is_already_protected',1,'Directory is already protected','admin','2009-01-10 10:09:59'),
('admin_payment_system_checkout2_btn_cancel',1,'cancel','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_btn_save',1,'save','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_header_comment',1,'2Checkout Details','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_header_subject',1,'2Checkout','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_merchant_id',1,'Merchant Id:','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_demo',1,'Demo mode:','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_merchant_id_ttip',1,'Merchant Id Tooltip','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_demo_ttip',1,'Demo mode tooltip','admin','2008-12-26 08:22:07'),
('admin_payment_system_checkout2_msg_er_merchant_id',1,'Value must be not empty!','admin','2008-12-26 08:22:07'),
('directories_add_protection_btn_add',1,'add','admin','2008-12-27 05:08:58'),
('directories_add_protection_btn_cancel',1,'cancel','admin','2009-01-10 10:06:39'),
('user_active_products_node_in_pending',1,'Payment in pending.\nWait for validation and transactions.\n','user','2008-12-09 11:50:44'),
('directories_add_protection_unable_to_protect_directory',1,'Unable to protect the directory','admin','2009-01-10 10:09:59'),
('user_change_password_error_old_pwd_empty',1,'Error: Old password is empty','user','2008-11-20 15:20:42'),
('user_change_password_error_new_pwd_empty',1,'Error: New password or Retype new password is empty','user','2008-11-20 15:20:52'),
('user_change_password_error_retype_pwd_not_match',1,'Error: Retype new password does not match New password','user','0000-00-00 00:00:00'),
('user_change_password_error_new_pwd_is_invalid',1,'Error: New password is very simple. Use additional characters and numbers to make the password more complicated','user','0000-00-00 00:00:00'),
('user_change_password_error_new_pwd_toolong',1,'Error: New password is too long','user','0000-00-00 00:00:00'),
('user_change_password_error_new_pwd_tooshort',1,'Error: New password is too short','user','0000-00-00 00:00:00'),
('user_after_buy_descr',1,'If you subscribe to a free product - you already have access to the protected dir\'s. Please go to your active products and check the subscriptions status.','user','2008-12-27 06:38:40'),
('user_after_buy_title',1,'Subscription created','user','2008-12-27 06:38:40'),
('user_change_password_error_pwd_not_changed',1,'Error: Password is not changed','user','2008-09-08 11:17:57'),
('user_after_buy_d',1,'If you subscribe to a free product &mdash; you already have access to the protected directory. Please go to your active products and check the subscriptions status.','user','0000-00-00 00:00:00'),
('user_change_password_error_old_pwd_incorrect',1,'Error: Old Password is incorrect','user','0000-00-00 00:00:00'),
('user_change_password_msg_pwd_changed',1,'Password was changed successfully','user','2008-11-20 15:13:45'),
('directories_add_protection_db_unable_to_insert',1,'Failed to insert the record to the database.','admin','2009-01-10 10:09:59'),
('directories_add_protection_db_unable_to_update',1,'Failed to update the database.','admin','2009-01-10 10:09:59'),
('user_change_password_page_title',1,'Change Password','user','2008-12-19 14:22:14'),
('user_change_password_page_desc',1,'You can change your password using this form ','user','2008-12-19 14:22:14'),
('user_change_password_info_how_to',1,'Here you can change member password.\nIf you want to generate a random password, check \'random password\' option and submit the form without entering new password.','user','2008-12-19 14:22:14'),
('user_change_password_label_old_password',1,'Old password:','user','2008-12-19 14:22:14'),
('user_change_password_label_new_password',1,'New password:','user','2008-12-19 14:22:14'),
('user_change_password_label_random_password',1,'Random password:','user','2008-12-19 14:22:14'),
('user_change_password_label_retype_new_password',1,'Retype new password:','user','2008-12-19 14:22:14'),
('user_change_password_button_update_password',1,'Update Password','user','2008-12-19 14:22:14'),
('user_change_password_img_tip',1,'Change password','user','2008-12-19 14:22:14'),
('user_remind_password_error_set_new_password',1,'Error: Password cannot be renewed','user','0000-00-00 00:00:00'),
('user_remind_password_button_submit',1,'remind','user','2008-12-22 11:32:03'),
('after_cancel_subscr_link',1,'You go to active subscriptions','undef','0000-00-00 00:00:00'),
('directories_edit_title',1,'Edit directory protection','admin','2009-01-10 10:06:39'),
('directories_edit_description',1,'Modify directory protection','admin','2009-01-10 10:06:39'),
('directories_add_protection_btn_edit',1,'edit','admin','2009-01-10 10:06:39'),
('user_profile_db_error',1,'Error: The data cannot be updated. Please try later. ','user','0000-00-00 00:00:00'),
('user_profile_update_successful',1,'Your information is saved successfully','user','2008-12-18 15:39:03'),
('user_profile_update_error_email_empty',1,'Error: Email is required','user','2008-11-28 15:30:46'),
('user_profile_update_error_email_length',1,'Error: Email is too short or too long','user','0000-00-00 00:00:00'),
('user_profile_update_error_email_invalid',1,'Error: Email is invalid','user','2008-11-27 13:56:38'),
('user_profile_update_error_email_exists',1,'Error: Email already exists','user','2008-12-02 11:45:21'),
('user_profile_update_error_name_empty',1,'Error: First name is required','user','2008-11-28 15:30:41'),
('user_profile_update_error_last_name_empty',1,'Error: Last Name is required','user','2008-10-30 10:22:35'),
('user_profile_update_error_name_length',1,'Error: First name is too long','user','0000-00-00 00:00:00'),
('user_profile_update_error_last_name_length',1,'Error: Last name is too long','user','0000-00-00 00:00:00'),
('user_error_id_invalid',1,'Error: User doesn\'t exist','user','0000-00-00 00:00:00'),
('user_profile_update_page_title',1,'Account Information','user','2009-01-09 12:05:20'),
('user_profile_update_page_desc',1,'You can change your profile data here','user','2009-01-09 12:05:20'),
('user_profile_update_label_login',1,'Login:','user','2009-01-09 12:05:20'),
('user_profile_update_label_email',1,'Email:','user','2009-01-09 12:05:20'),
('user_profile_update_label_first_name',1,'First Name:','user','2009-01-09 12:05:20'),
('user_profile_update_label_last_name',1,'Last Name:','user','2009-01-09 12:05:20'),
('user_profile_update_img_tip',1,'Account information','user','2009-01-09 12:05:20'),
('user_profile_update_button_save',1,'save','user','2009-01-09 12:05:20'),
('user_profile_update_button_cancel',1,'cancel','user','2009-01-09 12:05:20'),
('pager_showing_items',1,'showing items','both','2009-01-10 16:51:41'),
('pager_from',1,'from','both','2009-01-10 16:51:41'),
('admin_member_control_account_panel_payments_table_date',1,'Date','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_table_subscription_type',1,'Subscription Type','admin','2009-01-10 10:10:59'),
('directories_directory_has_been_added',1,'Directory has been added','admin','2009-01-10 10:09:59'),
('directories_directory_has_been_updated',1,'Directory has been updated','admin','2009-01-10 10:09:59'),
('directories_directory_has_been_deleted',1,'Directory has been deleted','admin','2009-01-10 10:09:59'),
('directories_add_protection_directory_path_is_too_long',1,'Directory path is too long','admin','2009-01-10 10:09:59'),
('directories_action_reprotect',1,'re-protect','admin','2009-01-10 10:09:59'),
('directories_add_protection_unable_to_reprotect',1,'Unable to re-protect the directory','admin','2009-01-10 10:09:59'),
('directories_directory_has_been_reprotected',1,'Directory has been re-protected','admin','2009-01-10 10:09:59'),
('directories_treeview_reload',1,'Reload the current directory','admin','2009-01-10 10:06:39'),
('admin_stats_transact_header_subject',1,'Transactions billing','admin','2009-01-10 10:14:43'),
('admin_stats_transact_header_comment',1,'Here you can see the transactions statistics on a certain subscription.','admin','2009-01-10 10:14:43'),
('admin_stats_transact_header_subject_part2',1,'on Subscription ID','admin','2009-01-10 10:14:43'),
('admin_member_control_account_panel_payments_add_period_day',1,'per day','admin','2008-12-23 16:00:39'),
('admin_member_control_account_panel_payments_add_period_month',1,'per month','admin','2008-12-23 16:00:39'),
('admin_member_control_account_panel_payments_add_period_month3',1,'per 3 months','admin','2008-12-23 16:00:39'),
('admin_member_control_account_panel_payments_add_period_month6',1,'per 6 months','admin','2008-12-23 16:00:39'),
('admin_member_control_account_panel_payments_add_period_year',1,'per year','admin','2008-12-23 16:00:39'),
('admin_member_control_account_panel_payments_add_period_unlimit',1,'unlimited','admin','2008-12-23 16:00:39'),
('admin_stats_transact_err_date',1,'Value must be a valid date!','admin','2009-01-10 10:14:43'),
('admin_stats_transact_search_option_summ',1,'Amount','admin','2009-01-10 10:14:43'),
('admin_stats_transact_search_option_user_name',1,'User name','admin','2009-01-10 10:14:43'),
('admin_stats_transact_search_option_product_name',1,'Product name','admin','2009-01-10 10:14:43'),
('admin_stats_transact_search_payment_date',1,'Payment Date','admin','2009-01-10 10:14:43'),
('admin_stats_transact_btn_show',1,'Show','admin','2009-01-10 10:14:43'),
('admin_member_control_account_panel_payments_add_error_period_empty',1,'Error: Period must be selected','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_add_error_pay_system_empty',1,'Error: Payment system must be selected','admin','2009-01-10 10:10:59'),
('admin_stats_transact_tbl_transaction_id',1,'Transaction ID','admin','2009-01-10 10:14:43'),
('admin_member_control_account_panel_payments_add_error_transaction_empty',1,'Error: Transaction is empty','admin','2009-01-10 10:10:59'),
('admin_stats_transact_tbl_user_name',1,'User Name','admin','2009-01-10 10:14:43'),
('admin_stats_transact_tbl_product_name',1,'Product Name','admin','2009-01-10 10:14:43'),
('admin_stats_transact_tbl_date',1,'Date','admin','2009-01-10 10:14:43'),
('admin_member_control_account_panel_payments_add_error_transaction_length',1,'Error: Transaction info length is out of range','admin','2009-01-10 10:10:59'),
('admin_stats_transact_tbl_more_details',1,'More Details','admin','2009-01-10 10:14:43'),
('admin_member_control_account_panel_payments_add_error_paid_empty',1,'Error: Paid must be selected','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_add_error_member_empty',1,'Error: Member id is invalid','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_add_error_price_empty',1,'Error: Period must be selected','admin','0000-00-00 00:00:00'),
('admin_stats_transact_tbl_pay_system',1,'Payment System','admin','2009-01-10 10:14:43'),
('admin_stats_transact_tbl_amount',1,'Amount','admin','2009-01-10 10:14:43'),
('admin_stats_transact_tbl_href_details',1,'details','admin','2008-12-27 06:56:02'),
('admin_stats_transact_search_by',1,'Search by','admin','2009-01-10 10:14:43'),
('user_active_products_empty',1,'Sorry no subscriptions yet','user','2008-12-17 17:29:57'),
('user_sale_products_empty',1,'No products available','user','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_add_error_product_empty',1,'Error: Product ID is invalid','admin','2009-01-10 10:10:59'),
('admin_logging_action',1,'Action:','admin','2009-01-10 15:19:39'),
('admin_member_control_account_panel_payments_table_transactions',1,'Transactions','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_table_details',1,'details','admin','2009-01-10 10:10:59'),
('admin_log_debug',1,'Debug\n','admin','2009-01-10 15:19:29'),
('admin_edit_sys_template_btn_save_default',1,'save as default','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_confirm_reset_text',1,'Do you really want to reset to the default values?','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_confirm_save_default_text',1,'Do you really want to set this value as default?','admin','2008-09-08 18:06:53'),
('admin_member_control_account_panel_transactions_list_page_title',1,'Transactions billing','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_label_subscr',1,'on Subscription ID:','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_table_date',1,'Date','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_table_transaction',1,'Transaction','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_table_paysystem',1,'Payment System','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_table_amount',1,'Amount','admin','2009-01-10 10:11:14'),
('admin_member_control_account_panel_transactions_list_label_info',1,'info','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_transaction_info_page_title',1,'Transaction info','admin','2008-12-04 17:04:08'),
('admin_member_control_account_panel_transaction_info_label_info',1,'Info:','admin','2008-12-04 17:04:08'),
('admin_member_control_account_panel_transaction_info_label_date',1,'Date:','admin','2008-12-04 17:04:08'),
('admin_member_control_account_panel_transaction_info_label_paysystem',1,'Payment System:','admin','2008-12-04 17:04:08'),
('admin_member_control_account_panel_transaction_info_label_amount',1,'Amount:','admin','2008-12-04 17:04:08'),
('admin_member_control_account_panel_transaction_info_label_transaction_id',1,'Transaction ID:','admin','2008-12-04 17:04:08'),
('admin_edit_sys_template_msg_er_not_saved',1,'Template was not saved or not modified!','admin','2008-09-17 14:32:26'),
('admin_edit_sys_template_msg_ok_saved_default',1,'Template saved as default!','admin','2008-09-08 18:06:53'),
('admin_edit_sys_template_msg_er_not_saved_default',1,'Default template was not saved or not modified!','admin','2008-09-08 18:06:53'),
('product_list_delete_button_alt',1,'Delete','admin','2009-01-10 15:37:32'),
('product_list_edit_button_alt',1,'Edit','admin','2009-01-10 10:02:05'),
('product_list_lock_button_alt',1,'Lock','admin','2008-12-24 06:46:19'),
('product_pay_type_one_time',1,'one time','admin','2008-12-27 04:21:12'),
('product_pay_type_reccuring',1,'recurring','admin','2009-01-10 10:02:05'),
('user_info_product_page_title',1,'Active Products','user','2009-01-10 10:30:57'),
('admin_login_form_username',1,'Login:','admin','2009-01-12 08:57:39'),
('admin_login_form_password',1,'Password:','admin','2009-01-12 08:57:39'),
('admin_login_form_remind',1,'Remind password','admin','2009-01-12 08:57:39'),
('admin_login_form_btn_login',1,'login','admin','2009-01-12 08:57:39'),
('admin_login_form_msg_er_username',1,'Invalid login!','admin','2009-01-12 08:57:39'),
('admin_login_form_msg_er_password',1,'Invalid password!','admin','2009-01-12 08:57:39'),
('directories_add_protection_db_unable_to_protect',1,'Failed to protect directory on the server &mdash; check the FileSystem log (\"Protection errors\") for details','admin','2009-01-10 10:09:59'),
('admin_log_load_protection_method',1,'Failed to load the selected protection method','admin','2008-12-23 15:34:43'),
('admin_log_file_write',1,'Failed to write file','admin','2008-12-23 15:34:43'),
('admin_log_file_delete',1,'Failed to delete file','admin','2008-12-23 15:34:43'),
('admin_login_form_msg_er_login',1,'Sorry, an error occurred. Please try again.','admin','2009-01-12 08:57:39'),
('directories_add_protection_unable_to_unprotect',1,'Unable to un-protect the directory','admin','2009-01-10 10:09:59'),
('admin_msg_er_0020',1,'IP block for the selected period failed','admin','0000-00-00 00:00:00'),
('directories_add_protection_directory_not_found',1,'Directory not found in the database','admin','2009-01-10 10:09:59'),
('admin_member_control_statistics_page_title',1,'Members Statistics','admin','2009-01-10 10:14:24'),
('admin_member_control_statistics_page_desc',1,'The chart below shows the number of registered members','admin','2009-01-10 10:14:24'),
('admin_member_control_statistics_label_year',1,'Year:','admin','2009-01-10 10:14:24'),
('admin_member_control_statistics_label_month',1,'Month:','admin','2009-01-10 10:14:24'),
('admin_login_form_msg_er_remind_code_error',1,'Wrong remind code!','admin','2009-01-12 08:35:12'),
('admin_member_control_statistics_name_month_all',1,'All Months','admin','2009-01-10 10:14:24'),
('admin_menu_directories_protection',1,'Directories Protection','admin','2009-01-12 08:57:39'),
('admin_menu_products_list',1,'Products List','admin','2009-01-12 08:57:39'),
('admin_menu_products_groups',1,'Product Groups','admin','2009-01-12 08:57:39'),
('admin_config_security_session_expiration',1,'Session expires after (sec.)','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_ip_block_selected_period',1,'Period for the block of a selected IP address (in seconds)','admin','2009-01-10 15:38:23'),
('admin_menu_product',1,'Product','admin','2009-01-12 08:57:39'),
('admin_msg_er_0021',1,'Session expiration failed','admin','0000-00-00 00:00:00'),
('admin_login_form_msg_mail_sended',1,'Please check your email for further instructions.','admin','2009-01-12 08:57:39'),
('admin_config_security_ttip_session_expiration',1,'The number of seconds a session will be valid','admin','2009-01-10 15:38:23'),
('admin_member_control_statistics_button_go',1,'Go','admin','2009-01-10 10:14:24'),
('admin_member_control_statistics_error_param_invalid',1,'Error: Year or month parameters value is invalid','admin','0000-00-00 00:00:00'),
('admin_member_control_statistics_bar_title_new_members',1,'new members','admin','2009-01-10 10:14:25'),
('admin_member_control_statistics_bar_title_all_members',1,'all members','admin','2009-01-10 10:14:25'),
('admin_member_control_statistics_y_axis_title',1,'Members Chart','admin','2009-01-10 10:14:25'),
('admin_config_security_block_selected_period',1,'IP block selected period (sec.)','admin','2009-01-10 15:38:23'),
('admin_config_security_ttip_block_selected_period',1,'Define a block period for the selected IP','admin','0000-00-00 00:00:00'),
('admin_log_unknown_event',1,'Unknown protection event happened','admin','2008-12-23 16:16:29'),
('admin_login_form_msg_er_script_disabled',1,'JavaScript is disabled!','admin','0000-00-00 00:00:00'),
('user_registration_send_activation_error_notsend',1,'Error: Activation link has not been sent to your email. Please contact the Administrator for information.','user','2008-10-08 15:29:15'),
('admin_config_language_translate_header',1,'Mass translate','admin','2009-01-10 14:45:24'),
('user_registration_error_password_login_coincidence',1,'Error: Login and Password must not match.','user','2008-10-14 16:52:05'),
('admin_menu_file_protection',1,'Files Protection','admin','2009-01-12 08:57:39'),
('admin_menu_member_control',1,'Member Control','admin','2009-01-12 08:57:39'),
('admin_menu_add_member',1,'Add Member','admin','2009-01-12 08:57:39'),
('admin_menu_member_list',1,'Member List','admin','2009-01-12 08:57:39'),
('admin_menu_approve_suspend',1,'Approve / Suspend','admin','2009-01-12 08:57:39'),
('admin_menu_activate_suspend',1,'Activate / Suspend','admin','2009-01-12 08:57:39'),
('admin_menu_unsuspend_delete',1,'Unsuspend / Delete','admin','2009-01-12 08:57:39'),
('admin_menu_member_statistics',1,'Members Statistics','admin','2009-01-12 08:57:39'),
('admin_menu_statistics',1,'Statistics','admin','2009-01-12 08:57:39'),
('admin_menu_total_statistics',1,'Total Statistics','admin','2009-01-12 08:57:39'),
('admin_menu_graphs',1,'Graphs','admin','2009-01-12 08:57:39'),
('admin_menu_newsletter',1,'Newsletter','admin','2009-01-12 08:57:39'),
('admin_menu_email_templates',1,'Email Templates','admin','2009-01-12 08:57:39'),
('admin_menu_send_email',1,'Send Email','admin','2009-01-12 08:57:39'),
('admin_menu_email_history',1,'Email History','admin','2009-01-12 08:57:39'),
('admin_menu_coupon',1,'Coupon','admin','2009-01-12 08:57:39'),
('admin_menu_coupon_groups',1,'Coupons List','admin','2009-01-12 08:57:39'),
('admin_menu_create_coupons',1,'Create Coupons','admin','2009-01-12 08:57:39'),
('admin_menu_coupons_statistics',1,'Coupon Statistics','admin','2009-01-12 08:57:39'),
('admin_menu_system_configuration',1,'System Configuration','admin','2009-01-12 08:57:39'),
('admin_menu_ban_ip',1,'Ban IP','admin','2009-01-12 08:57:39'),
('admin_menu_manage_news',1,'Manage News','admin','2009-01-12 08:57:39'),
('admin_menu_administrator_control',1,'Administrator Control','admin','2009-01-12 08:57:39'),
('admin_menu_activity_logging',1,'Activity Logs','admin','2009-01-12 08:57:39'),
('admin_stats_detailed_transact_header_subject_part2',1,'on Transaction ID','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_header_subject',1,'Transaction details','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_header_comment',1,'Here you can see a detailed information on transactions','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_feedback_link',1,'return back to the transactions list','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_title_field_name',1,'Field Name','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_title_field_value',1,'Field Value','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_pay_system',1,'Payment System','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_completed',1,'Completed','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_transact_date',1,'Transaction Date','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_amount',1,'Amount','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_billing_name',1,'Billing Name','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_country_code',1,'Country Code','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_state_code',1,'State Code','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_city',1,'City','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_street',1,'Street','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_zip_code',1,'ZIP Code','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_phone',1,'Phone','admin','2008-12-27 06:55:52'),
('admin_stats_detailed_transact_tbl_payment_info',1,'Payment Information','admin','2008-12-27 06:55:52'),
('admin_member_control_member_list_const_free_price',1,'free','admin','2009-01-10 10:10:59'),
('user_active_products_node_period_unlimit',1,'Five year','user','0000-00-00 00:00:00'),
('user_access_problem_title_unknown_directory',1,'Unknown directory','user','2008-12-08 09:37:30'),
('user_access_problem_msg_unknown_directory',1,'Sorry, the directory you are trying to access can not be identified','user','2008-12-08 09:37:30'),
('admin_member_control_account_panel_payments_add_error_invoice_notadded',1,'Error: Invoice is not added','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_payments_add_error_already_subscribed',1,'Error: Member is already subscribed to this product','admin','0000-00-00 00:00:00'),
('admin_header_logout',1,'Logout','admin','2009-01-12 08:57:39'),
('admin_header_change_password',1,'Change account','admin','2009-01-12 08:57:39'),
('admin_header_home',1,'Home','admin','2009-01-12 08:57:39'),
('admin_stats_graphs_error_period_invalid',1,'Error: Period is invalid','admin','2009-01-10 10:14:59'),
('admin_header_user_info_start',1,'Welcome','admin','2009-01-12 08:57:39'),
('admin_header_user_info_end',1,'Your last login was on','admin','2009-01-12 08:57:39'),
('user_access_problem_title_access_denied',1,'Access denied','user','2008-12-08 14:11:24'),
('user_access_problem_description_access_denied',1,'Sorry, You are not allowed to access this directory','user','2008-12-08 14:11:24'),
('admin_main_page_payments',1,'Payments for the last 7 days','both','2009-05-15 07:41:56'),
('admin_main_page_payments_date',1,'Date','admin','2009-01-12 08:57:39'),
('admin_main_page_payments_quantity_paid',1,'Quantity paid','admin','2009-01-12 08:57:39'),
('admin_main_page_payments_price',1,'Price','admin','2009-01-12 08:57:39'),
('admin_member_control_member_list_msg_delete_success',1,'Member was deleted successfully','admin','2008-12-08 13:44:28'),
('admin_main_page_system_status',1,'System Status','admin','2009-01-12 08:57:39'),
('admin_main_page_software_info',1,'Software version info','admin','2009-01-12 08:57:39'),
('admin_main_page_members_statistic',1,'Members Statistics','admin','2009-01-12 08:57:39'),
('admin_member_control_account_panel_payments_add_error_product_blocked',1,'Error: Product is blocked','admin','0000-00-00 00:00:00'),
('admin_member_control_member_list_label_all',1,'ALL','admin','2009-01-10 10:10:41'),
('admin_member_control_member_list_button_add_member',1,'add member','admin','2009-01-10 10:10:41'),
('day_name_short_0',1,'Sun','both','2009-01-12 08:57:39'),
('day_name_short_1',1,'Mon','both','2009-01-12 08:57:39'),
('day_name_short_2',1,'Tue','both','2009-01-12 08:57:39'),
('day_name_short_3',1,'Wed','both','2009-01-12 08:57:39'),
('day_name_short_4',1,'Thu','both','2009-01-12 08:57:39'),
('day_name_short_5',1,'Fri','both','2009-01-12 08:57:39'),
('day_name_short_6',1,'Sat','both','2009-01-12 08:57:39'),
('admin_main_page_system_status_global',1,'Global system status','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_online',1,'Online','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_offline',1,'Offline','admin','2008-12-23 14:46:56'),
('admin_main_page_system_status_confirmation',1,'Confirmation status','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_confirmation_need',1,'Members need to confirm their sign up','admin','0000-00-00 00:00:00'),
('admin_main_page_system_status_confirmation_not_need',1,'Members do not need to confirm their registration','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_approving',1,'Approval status','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_approving_have',1,'Administrator has to approve new members after registration','admin','0000-00-00 00:00:00'),
('admin_main_page_system_status_approving_not_have',1,'Administrator does not have to approve new members registration','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_accounts',1,'Total member accounts','admin','2009-01-12 08:57:39'),
('admin_main_page_system_status_expired',1,'Expired accounts','admin','2009-01-12 08:57:39'),
('admin_main_page_members_statistic_not_approved',1,'not approved','admin','2009-01-12 08:57:39'),
('admin_main_page_members_statistic_not_confirmed',1,'not activated','admin','2009-01-12 08:57:39'),
('admin_main_page_members_statistic_suspended',1,'suspended','admin','2009-01-12 08:57:39'),
('user_info_label_latest_news',1,'Latest News','user','2009-01-10 10:30:57'),
('month_name_1',1,'January','both','2009-01-10 10:15:00'),
('month_name_2',1,'February','both','2009-01-10 10:15:00'),
('month_name_3',1,'March','both','2009-01-10 10:15:00'),
('month_name_4',1,'April','both','2009-01-10 10:15:00'),
('month_name_5',1,'May','both','2009-01-10 10:15:00'),
('month_name_6',1,'June','both','2009-01-10 10:15:00'),
('month_name_7',1,'July','both','2009-01-10 10:15:00'),
('month_name_8',1,'August','both','2009-01-10 10:15:00'),
('month_name_9',1,'September','both','2009-01-10 10:15:00'),
('month_name_10',1,'October','both','2009-01-10 10:15:00'),
('month_name_11',1,'November','both','2009-01-10 10:15:00'),
('month_name_12',1,'December','both','2009-01-10 10:15:00'),
('admin_member_control_account_panel_payments_add_error_product_notavailable',1,'Error: Product is not available','admin','0000-00-00 00:00:00'),
('admin_menu_member_expired',1,'Expired accounts','admin','2009-01-12 08:57:39'),
('admin_member_control_expired_accounts_page_title',1,'Expired accounts','admin','2009-01-10 10:14:17'),
('admin_member_control_expired_accounts_page_desc',1,'The list of expired member accounts ','admin','2009-01-10 10:14:17'),
('admin_member_control_expired_accounts_table_login',1,'Login','admin','2009-01-10 10:14:17'),
('admin_member_control_expired_accounts_table_name',1,'Name','admin','2009-01-10 10:14:17'),
('admin_header_user_info_medium',1,'!','admin','2009-01-12 08:57:39'),
('user_paid_invoices_page_title',1,'Paid Invoices','user','2008-12-27 06:40:12'),
('user_paid_invoices_page_descr',1,'Payments and subscriptions','user','2008-12-27 06:40:12'),
('user_paid_invoices_table_product',1,'Product','user','2008-12-27 06:40:12'),
('user_paid_invoices_table_date',1,'Date','user','2008-12-27 06:40:12'),
('user_paid_invoices_table_transactions',1,'Transactions','user','2008-12-27 06:40:12'),
('user_paid_invoices_table_subscription_type',1,'Subscription Type','user','2008-12-27 06:40:12'),
('user_paid_invoices_table_price',1,'Price','user','2008-12-27 06:40:12'),
('user_paid_invoices_label_details',1,'details','user','2008-12-27 06:40:12'),
('user_paid_invoices_msg_no_data',1,'Sorry no paid invoices yet','user','2008-12-08 10:27:28'),
('admin_member_control_account_panel_payments_type_one_time',1,'One time','admin','2009-01-10 10:10:59'),
('admin_member_control_account_panel_payments_type_reccuring',1,'Recurring','admin','2008-11-13 13:40:00'),
('user_paid_invoices_transactions_list_page_title',1,'Transactions list','user','2008-12-27 06:39:17'),
('user_paid_invoices_transactions_list_page_descr',1,' ','user','2008-12-27 06:39:17'),
('user_paid_invoices_transactions_list_table_transaction',1,'Transaction','user','2008-12-27 06:39:17'),
('user_paid_invoices_transactions_list_table_date',1,'Date','user','2008-12-27 06:39:17'),
('user_paid_invoices_transactions_list_table_paysystem',1,'Payment System','user','2008-12-27 06:39:17'),
('user_paid_invoices_transactions_list_table_amount',1,'Amount','user','2008-12-27 06:39:17'),
('user_paid_invoices_error_invoice_id_invalid',1,'Error: Invoice ID is invalid','user','0000-00-00 00:00:00'),
('user_paid_invoices_const_free_price',1,'free','user','2008-12-27 06:40:12'),
('user_btn_back',1,'back','user','2008-12-27 06:39:17'),
('admin_member_control_account_panel_transaction_info_error_id_invalid',1,'Error: Transaction ID is invalid','admin','0000-00-00 00:00:00'),
('admin_member_control_account_panel_transaction_info_error_not_exist',1,'Error: Transaction doesn\'t exist','admin','0000-00-00 00:00:00'),
('user_login_error_banned',1,'Your IP address is banned.','user','2008-11-10 15:52:37'),
('user_paid_invoices_error_transaction_not_exist',1,'Error: Transaction doesn\'t exist','user','0000-00-00 00:00:00'),
('user_paid_invoices_error_transaction_id_invalid',1,'Error: Transaction id is invalid','user','0000-00-00 00:00:00'),
('user_login_error_blocked',1,'Your ip is blocked: ','user','2008-12-19 12:01:35'),
('admin_login_form_msg_er_mail_not_sended',1,'Message could not be delivered to your emai address, please contact the administrator.','admin','2009-01-12 08:57:39'),
('user_paid_invoices_transaction_info_page_title',1,'Transaction info','user','2008-12-27 06:39:11'),
('user_paid_invoices_transaction_info_page_descr',1,'The information about transaction attributes','user','2008-12-27 06:39:11'),
('user_paid_invoices_transaction_info_label_info',1,'Info:','user','2008-12-27 06:39:11'),
('user_paid_invoices_transaction_info_label_date',1,'Date:','user','2008-12-27 06:39:11'),
('user_paid_invoices_transaction_info_label_paysystem',1,'Payment System:','user','2008-12-27 06:39:11'),
('user_paid_invoices_transaction_info_label_amount',1,'Amount:','user','2008-12-27 06:39:11'),
('admin_log_directory_delete',1,'Failed to delete directory','admin','2008-12-23 15:34:43'),
('admin_log_directory_create',1,'Failed to create directory','admin','2008-12-23 15:34:43'),
('user_menu_account_information',1,'Account Information','user','2009-01-10 10:30:57'),
('user_menu_change_password',1,'Change Password','user','2009-01-10 10:30:57'),
('user_menu_active_products',1,'Active Products','user','2009-01-10 10:30:57'),
('user_menu_paid_invoices',1,'Paid Invoices','user','2009-01-10 10:30:57'),
('user_login_error_expired',1,'Your account is expired ','user','2009-03-30 00:00:02'),
('user_login_error_capcha',1,'Code from the image is incorrect.','user','2008-12-22 11:30:56'),
('user_login_error_activate',1,'Account is not active','user','2009-01-09 12:03:30'),
('user_login_error_login_failed',1,'Login or password is incorrect.','user','2009-01-09 11:59:36'),
('user_login_error_autoban',1,'Your login is automatically banned. Too many connections from different IP addresses.','user','2008-10-20 13:34:00'),
('admin_login_form_msg_pwd_new_sended',1,'The new password was sent to your email address!','admin','2009-01-12 08:35:12'),
('user_login_error_aprove',1,'Your account is not approved','user','0000-00-00 00:00:00'),
('user_login_error_approve',1,'Your login is not approved','user','2008-12-05 10:21:08'),
('user_login_error_suspended',1,'Your login is suspended','user','2008-12-22 11:30:04'),
('admin_member_control_account_panel_payments_error_account_expired',1,'Error: Member\'s account is expired','admin','2009-01-10 10:10:59'),
('admin_config_add_fields_error',1,'Error: ','admin','2009-01-10 14:44:40'),
('admin_member_control_account_panel_change_password_msg_update_success',1,'Password has been changed successfully','admin','2008-12-10 11:32:22'),
('admin_config_lang_editor_translator',1,'Import/Export','admin','2009-01-12 09:12:43'),
('directories_file_protection_message',1,'Choose the product from the list above and paste the code from below into the very beginning of the php file you wish to protect.','admin','2009-01-10 10:10:08'),
('directories_file_protection_title',1,'File protection','admin','2009-01-10 10:10:08'),
('directories_file_protection_description',1,'Generate a snippet of PHP code to manually protect php files','admin','2009-01-10 10:10:08'),
('admin_config_language_translate_subheader',1,'Here you can import/export language files','admin','2009-01-10 14:45:24'),
('admin_config_language_translate_instruction',1,'Instruction','admin','2009-01-10 14:45:24'),
('admin_config_language_translate_step1',1,'Download the file -> ','admin','2009-01-10 14:45:24'),
('admin_config_language_translate_step2',1,'Translate it with translating tools like','admin','2009-01-10 14:45:24'),
('admin_config_language_translate_step3',1,'Upload the translated XML-file','admin','2009-01-10 14:45:24'),
('admin_config_lang_editor_submit',1,'submit','admin','2008-12-09 16:44:05'),
('user_authorize_net_buy_now_btn',1,'Buy now','user','2008-12-09 14:38:40'),
('admin_payment_system_authorize_net_api_login_ttip',1,'Authorize.Net API Login ID Tooltip','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_transaction_key_ttip',1,'Authorize.Net Transaction Key Tooltip','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_test_ttip',1,'Demo Mode Tooltip','admin','2008-12-08 17:05:31'),
('admin_coupon_coupon_groups_button_create_coupons',1,'Create Coupons','admin','2009-01-10 14:09:05'),
('admin_coupon_create_coupons_button_create',1,'Create','admin','2009-01-10 14:10:11'),
('user_paid_invoices_const_free_product',1,'Free payment','user','2008-12-27 06:39:17'),
('admin_payment_system_authorize_net_md5hash',1,'MD5 Hash','admin','2008-12-08 17:05:31'),
('admin_payment_system_authorize_net_md5hash_ttip',1,' The MD5 Hash value is a random value that you configure in the Merchant Interface, it allows you to authenticate that transaction responses are securely received from Authorize.Net.','admin','2008-12-08 17:05:31'),
('admin_member_control_account_panel_payments_const_free_product',1,'Free payment','admin','2008-12-23 16:04:24'),
('admin_payment_system_authorize_net_msg_er_md5hash',1,'Must be not more than 255 characters.','admin','2008-12-08 17:05:31'),
('admin_stats_total_err_date',1,'Value must be a valid date!','admin','2009-01-10 10:14:51'),
('admin_stats_graphs_error_period_outofrange',1,'Error: Period is out of range','admin','2008-12-02 09:32:46'),
('admin_config_add_fields_error_field_values_empty',1,'Error: Field Values is empty','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_error_check_rule',1,'Error: Field Values and Default Value must match the selected Check rule','admin','2008-09-04 15:43:44'),
('admin_config_add_fields_error_check_rule_email',1,'must be a valid email address according to the selected Check rule','admin','2009-01-10 14:44:40'),
('admin_logging_protection_title',1,'Protection errors','admin','2009-01-10 15:19:39'),
('admin_menu_protection_errors',1,'Protection errors','admin','2009-01-12 08:57:39'),
('admin_logging_protection_description',1,'Protection errors description','admin','2009-01-10 15:19:39'),
('admin_logging_person',1,'Person:','admin','2009-01-10 15:19:29'),
('admin_logging_person_any',1,'any person','admin','2009-01-10 15:19:29'),
('admin_logging_person_undefined',1,'Undefined person','admin','2009-01-10 15:19:29'),
('admin_config_add_fields_error_check_rule_numbers',1,' must contain only numbers according to the selected Check rule','admin','2009-01-10 14:44:40'),
('admin_member_control_member_list_search_label_or_period',1,'or Period:','admin','2009-01-10 10:10:41'),
('admin_config_add_fields_error_check_rule_phone',1,' must contain only +- and numeric characters according to the selected Check rule','admin','2009-01-10 14:44:40'),
('admin_config_add_fields_error_default_value_empty',1,'Error: Default Value is empty','admin','2009-01-10 14:44:40'),
('admin_lang_manager_from_langs',1,'From languge:','admin','2009-01-12 08:57:14'),
('admin_lang_manager_langs',1,'Language:','admin','2009-01-12 08:57:14'),
('admin_lang_manager_btn_import',1,'import','admin','2009-01-12 08:57:14'),
('admin_lang_manager_btn_add',1,'add','admin','2009-01-12 08:57:14'),
('admin_lang_manager_btn_save',1,'save','admin','2009-01-12 08:57:14'),
('admin_lang_manager_btn_cancel',1,'cancel','admin','2009-01-12 08:57:14'),
('admin_stats_graphs_header_subject',1,'Sales Statistics Summary','admin','2009-01-10 10:14:59'),
('admin_stats_graphs_header_comment',1,'Here you can see the summary sales chart.','admin','2009-01-10 10:14:59'),
('admin_lang_manager_msg_er_not_saved',1,'Data was not saved or not modified!','admin','2008-12-19 14:24:53'),
('admin_lang_manager_msg_ok_saved_default',1,'Your changes successfully saved!','admin','2008-12-19 14:24:53'),
('admin_edit_sys_template_default_language',1,'Default','admin','2009-01-12 08:57:14'),
('admin_msg_er_0022',1,'The requested language not found','admin','2009-01-10 14:45:24'),
('admin_stats_graphs_btn_show',1,'Show','admin','2009-01-10 10:14:59'),
('admin_config_add_fields_success_message',1,'Saved!','admin','2009-01-10 14:44:35'),
('admin_msg_ok_0004',1,'Language data was updated successfully','admin','2009-01-10 14:45:24'),
('admin_config_add_fields_explanation',1,'Use drag&drop to change the order of fields.','admin','2009-01-10 14:44:35'),
('admin_stats_graphs_chart_title',1,'Summary sales','admin','2009-01-10 10:15:00'),
('admin_stats_graphs_graph_title',1,'Summary sales graph','admin','2009-01-10 10:15:00'),
('admin_stats_graphs_label_for_period',1,'for period','admin','2008-12-03 12:09:19'),
('admin_stats_total_header_subject',1,'Total statistics','admin','2009-01-10 10:14:51'),
('admin_stats_total_header_comment',1,'Here you can see the total statistics on products and product groups.','admin','2009-01-10 10:14:51'),
('admin_stats_total_search_payment_date',1,'Payment Date','admin','2009-01-10 10:14:51'),
('admin_stats_total_btn_show',1,'Show','admin','2009-01-10 10:14:51'),
('admin_stats_total_tbl_percentage',1,'Percentage','admin','2009-01-10 10:14:51'),
('admin_stats_total_tbl_product_name',1,'Product Name','admin','2009-01-10 10:14:51'),
('admin_stats_total_tbl_transactions',1,'Transactions','admin','2009-01-10 10:14:51'),
('directories_file_protection_btn_copy_to_clipboard',1,'Copy to clipboard','admin','2009-01-10 10:10:08'),
('admin_coupon_create_coupons_error_usecount_compare',1,'Error: No of times the coupon can be used must be less or equal to Coupon Usage Number','admin','2008-11-07 10:06:17'),
('admin_msg_er_0023',1,'UPDATE operation failed','admin','2008-09-15 16:34:15'),
('admin_stats_total_tbl_groups_total',1,'TOTAL','admin','2009-01-10 10:14:51'),
('admin_menage_pages_edit_header_subject',1,'Edit page: ','admin','2009-01-10 14:51:58'),
('admin_menage_pages_edit_header_comment',1,'Edit page here ','admin','2009-01-10 14:51:58'),
('admin_menage_pages_edit_default_language',1,'Default','admin','2008-09-15 14:33:09'),
('admin_menage_pages_edit_subject',1,'Page title','admin','2009-01-10 14:51:58'),
('admin_menage_pages_edit_message',1,'Page content','admin','2009-01-10 14:51:58'),
('admin_menage_pages_edit_additional',1,'Keywords (comma separated)','admin','2009-01-10 14:51:58'),
('admin_stats_total_tbl_group_name',1,'Product Group Name','admin','2009-01-10 10:14:51'),
('admin_stats_total_tbl_amount',1,'Amount','admin','2009-01-10 10:14:51'),
('admin_global_setup_msg_er_date_format',1,'Date format is invalid','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_date_format',1,'example: m/d/y; valid separators: slash, dot, dash.\nChars: d (2 digits), D (1 digit), m (2 digits), M (1 digit), y (2 digits),Y (4 digits).','admin','2009-01-10 15:37:42'),
('admin_global_setup_date_format',1,'Date format:','admin','2009-01-10 15:37:42'),
('user_offline_msg',1,'system offline message:','user','2008-12-04 11:37:24'),
('user_offline_header',1,'System is offline','user','2008-12-04 11:37:24'),
('user_registration_add_fields_error_text_unknown_value',1,'value  is incorrect','user','2008-10-02 16:34:48'),
('admin_logging_tbl_no_elements',1,'Sorry, no activity logs found!','admin','2009-01-10 15:19:39'),
('admin_stats_subscr_tbl_product_name',1,'Product Name','admin','2009-01-10 10:14:40'),
('admin_manage_pages_header_subject',1,'Manage pages','admin','2009-01-10 15:20:14'),
('admin_manage_pages_header_comment',1,'Manage pages here','admin','2009-01-10 15:20:14'),
('admin_manage_pages_btn_add',1,'Add page','admin','2009-01-10 15:20:14'),
('admin_manage_pages_published',1,'Published','admin','2009-01-10 15:20:14'),
('admin_manage_pages_show_in_menu',1,'Show in menu','admin','2009-01-10 15:20:14'),
('admin_manage_pages_members_only',1,'Members only','admin','2009-01-10 15:20:14'),
('admin_manage_pages_sid',1,'Sid','admin','2009-01-10 15:20:14'),
('admin_manage_pages_action',1,'Action','admin','2009-01-10 15:20:14'),
('admin_menage_pages_edit_msg_er_subject',1,'Page title error','admin','2009-01-10 14:51:58'),
('admin_menage_pages_edit_msg_er_message',1,'Page content error','admin','2009-01-10 14:51:58'),
('admin_manage_pages_msg_ok_deleted',1,'Page was successfully deleted!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_success_message',1,'Successfully saved!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_edit_page_success',1,'Page has been updated.','admin','2009-01-10 15:20:14'),
('admin_manage_pages_add_page_success',1,'Page has been addes.','admin','2009-01-10 15:20:14'),
('admin_manage_pages_remove_page_success',1,'Page has been removed.','admin','2009-01-10 15:20:14'),
('admin_manage_pages_msg_er_not_saved',1,'Not saved!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_msg_er_duplicate_entry',1,'Duplicated entry!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_msg_er_not_deleted',1,'Not deleted!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_msg_er_not_found',1,'Not found!','admin','2009-01-10 15:20:14'),
('admin_manage_pages_msg_processing',1,'Processing...','admin','2009-01-10 15:20:14'),
('admin_btn_update',1,'update','admin','2009-01-10 16:39:13'),
('user_paid_invoices_label_total',1,'Total -','user','2008-12-27 06:40:12'),
('admin_msg_suspend_question',1,'Are you sure you want to suspend the member(s)?','admin','2009-01-10 10:13:52'),
('admin_msg_approve_question',1,'Are you sure you want to approve the member?','admin','2009-01-10 10:12:59'),
('admin_msg_confirm_question',1,'Are you sure you want to activate the member?','admin','2009-01-10 10:13:52'),
('admin_msg_unsuspend_question',1,'Are you sure you want to unsuspend the member?','admin','2009-01-10 10:14:13'),
('admin_manage_news_edit_msg_er_subject',1,'Field Header is too long','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_msg_er_additional',1,'Field Content is too long','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_msg_er_message',1,'Field Brief is too long','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_additional',1,'Content','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_header_comment',1,'Edit news on this page','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_header_subject',1,'Edit news:','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_message',1,'Brief','admin','2009-01-10 14:33:42'),
('admin_manage_news_edit_subject',1,'Header','admin','2009-01-10 14:33:42'),
('admin_manage_news_action',1,'Action','admin','2009-01-10 14:44:29'),
('admin_manage_news_add_page_success',1,'News has been addes.','admin','2009-01-10 14:44:29'),
('admin_manage_news_btn_add',1,'Add news','admin','2009-01-10 14:44:29'),
('admin_manage_news_edit_page_success',1,'News has been updated.','admin','2009-01-10 14:44:29'),
('admin_manage_news_header_comment',1,'Manage news here','admin','2009-01-10 14:44:29'),
('admin_manage_news_header_subject',1,'Manage news','admin','2009-01-10 14:44:29'),
('admin_manage_news_members_only',1,'Members only','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_er_duplicate_entry',1,'Duplicated entry!','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_er_not_deleted',1,'Not deleted!','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_er_not_found',1,'Not found!','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_er_not_saved',1,'Not saved!','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_ok_deleted',1,'News was successfully deleted!','admin','2009-01-10 14:44:29'),
('admin_manage_news_msg_processing',1,'Processing...','admin','2009-01-10 14:44:29'),
('admin_manage_news_published',1,'Published','admin','2009-01-10 14:44:29'),
('admin_manage_news_remove_page_success',1,'News has been removed.','admin','2009-01-10 14:44:29'),
('admin_manage_news_sid',1,'Sid','admin','2009-01-10 14:44:29'),
('admin_manage_news_success_message',1,'Successfully saved!','admin','2009-01-10 14:44:29'),
('admin_global_setup_field_login_redirect',1,'Redirect after login:','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_login_redirect',1,'Members will be redirected to the specified URL once they log into the system. If the \'Individual redirection\' option is enabled, you\'ll be able to specify the Redirection URL for each individual user in the user settings','admin','2009-06-16 11:33:01'),
('admin_treeview_ie6_disabled',1,'Tree-view control is disabled because it doesn\'t work properly in IE6. Please consider upgrading to IE7 or using another browser.','admin','2009-01-10 10:06:39'),
('product_list_edit_lang_button_alt',1,'Edit','admin','2009-01-12 09:15:57'),
('admin_product_list_edit_subject',1,'Name:','admin','2009-01-09 14:33:23'),
('admin_product_list_edit_message',1,'Description:','admin','2009-01-09 14:33:23'),
('admin_product_list_edit_header_subject',1,'Edit product:','admin','2009-01-09 14:33:23'),
('admin_product_list_edit_header_comment',1,'Edit product here','admin','2009-01-09 14:33:23'),
('admin_product_list_edit_msg_er_subject',1,'Name error!','admin','2009-01-09 14:33:23'),
('admin_product_list_edit_msg_er_message',1,'Description error!','admin','2009-01-09 14:33:23'),
('product_save_not_saved_confirmation',1,'Warning: Data isn`t saved, do you really want to leave this page?','admin','2009-01-12 09:15:57'),
('admin_product_group_edit_header_comment',1,'Add a new product group using the below form','admin','2009-01-10 10:04:47'),
('admin_product_group_edit_header_subject',1,'Edit product group:','admin','2009-01-10 10:04:47'),
('admin_product_group_edit_message',1,'Description:','admin','2009-01-10 10:04:47'),
('admin_product_group_edit_msg_er_message',1,'Description error!','admin','2009-01-10 10:04:47'),
('admin_product_group_edit_msg_er_subject',1,'Name error!','admin','2009-01-10 10:04:47'),
('admin_product_group_edit_subject',1,'Name:','admin','2009-01-10 10:04:47'),
('admin_menage_pages_edit_msg_er_additional',1,'values should be comma separated','admin','2009-01-10 14:51:58'),
('admin_product_group_add_header_subject',1,'Add new product group','admin','2008-12-25 08:47:08'),
('admin_member_control_suspend_reason_add_header_subject',1,'Add new suspend reason','admin','2008-12-26 07:39:39'),
('admin_member_control_suspend_reason_edit_header_comment',1,'Edit suspend reason here','admin','2008-12-26 07:39:39'),
('admin_member_control_suspend_reason_edit_header_subject',1,'Edit suspend reason:','admin','2008-12-08 13:59:56'),
('admin_member_control_suspend_reason_edit_message',1,'Description:','admin','2008-12-26 07:39:39'),
('admin_member_control_suspend_reason_edit_msg_er_message',1,'Description error!','admin','2008-12-26 07:39:39'),
('admin_member_control_suspend_reason_edit_msg_er_subject',1,'Name error!','admin','2008-12-26 07:39:39'),
('admin_member_control_suspend_reason_edit_subject',1,'Name:','admin','2008-12-26 07:39:39'),
('admin_lang_manager_empty_name',1,'language_object_','admin','2009-01-10 14:44:40'),
('admin_lang_manager_empty_descr',1,'language_object_','admin','2009-01-10 14:44:40'),
('admin_lang_manager_empty_add',1,'language_object_','admin','2009-01-10 14:44:40'),
('user_registration_success_page_title',1,'Registration was successful','user','0000-00-00 00:00:00'),
('admin_config_add_fields_button_lang_alt',1,'Edit','admin','2009-01-10 14:44:35'),
('admin_btn_find',1,'Find','admin','2009-01-10 16:39:13'),
('admin_btn_clear',1,'clear','admin','2009-01-10 16:39:13'),
('admin_admin_edit_msg_er_invalid_id',1,'Error: ID is invalid','admin','2008-12-08 17:35:58'),
('admin_msg_er_0024',1,'File is empty','admin','0000-00-00 00:00:00'),
('admin_sys_emails_tpl_your_admin_remind_password',1,'Remind password','admin','2009-01-12 08:57:33'),
('admin_payment_system_authorize_net_msg_er_not_defined',1,'Sorry, error occured.','admin','2008-12-08 17:05:31'),
('admin_sys_emails_tpl_admin_access_level_change',1,'Access level changed','admin','2009-01-12 08:57:33'),
('admin_log_mailer_settings_modify',1,'mailer setup modified','admin','2008-12-23 16:16:29'),
('admin_log_language_import',1,'language imported','admin','2008-12-23 16:16:29'),
('admin_log_language_modify',1,'language modified','admin','2009-01-10 15:19:29'),
('admin_log_lang_manager_add',1,'language constant added','admin','2009-01-10 15:19:29'),
('admin_log_lang_manager_delete',1,'language constant deleted','admin','2008-12-23 16:16:29'),
('admin_log_lang_manager_language_delete',1,'language deleted','admin','2008-12-23 16:16:29'),
('admin_config_add_fields_lang_msg_er_subject',1,'You should input the correct additional field name','admin','2008-12-09 14:41:14'),
('admin_config_add_fields_lang_msg_er_message',1,'You should input the correct additional field description','admin','2008-12-09 14:41:14'),
('admin_config_add_fields_lang_subject',1,'Additional field name','admin','2008-12-09 14:41:14'),
('admin_config_add_fields_lang_message',1,'Additional field description','admin','2008-12-09 14:41:14'),
('admin_log_suspend_reason_delete',1,'suspend reason deleted','admin','2008-12-23 16:16:29'),
('admin_log_status_modify',1,'system status modified','admin','2008-12-23 16:16:29'),
('admin_log_product_modify',1,'product modified','admin','2009-01-10 15:19:29'),
('admin_log_product_image_add',1,'product image added','admin','2008-12-23 16:16:29'),
('admin_log_product_delete',1,'product deleted','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_sort',1,'custom pages order changed','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_published',1,'custom page published','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_edit',1,'custom page modified','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_add',1,'custom page added','admin','2008-12-23 16:16:29'),
('admin_log_member_unsuspend',1,'member unsuspended','admin','2009-01-10 15:19:29'),
('admin_log_member_suspend',1,'member suspended','admin','2009-01-10 15:19:29'),
('admin_log_member_payment_add',1,'payment added to member','admin','2008-12-23 16:16:29'),
('admin_log_member_approve',1,'member approved','admin','2008-12-23 16:16:29'),
('admin_log_member_add',1,'member added','admin','2009-01-10 15:19:29'),
('admin_log_members_settings_modify',1,'members setup modified','admin','2008-12-23 16:16:29'),
('admin_log_lang_manager_modify',1,'language constant modified','admin','2008-12-23 16:16:29'),
('admin_log_language_export',1,'language exported','admin','2008-12-23 16:16:29'),
('admin_log_language_add',1,'language added','admin','2008-12-23 16:16:29'),
('admin_log_global_setup_modify',1,'global setup modify','admin','2008-12-23 16:16:29'),
('admin_log_directory_protection_delete',1,'directory protection deleted','admin','2008-12-23 16:16:29'),
('admin_log_design_modify',1,'design modified','admin','2008-12-23 16:16:29'),
('admin_log_default_language_modify',1,'default language modified','admin','2008-12-23 16:16:29'),
('admin_log_coupon_group_modify',1,'coupon modified','admin','2008-12-23 16:16:29'),
('admin_log_coupon_group_add',1,'coupon added','admin','2008-12-23 16:16:29'),
('admin_log_coupon_delete',1,'coupon code deleted','admin','2008-12-23 16:16:29'),
('admin_log_ban_ip_delete',1,'banned IP address deleted','admin','2008-12-23 16:16:29'),
('admin_log_additional_field_modify',1,'additional field modified','admin','2008-12-23 16:16:29'),
('admin_log_security_settings_modify',1,'security settings modified','admin','2008-12-23 16:16:29'),
('admin_log_product_image_delete',1,'product image deleted','admin','2008-12-23 16:16:29'),
('admin_config_add_fields_lang_edit_header_subject',1,'Edit Additional Field:','admin','2008-12-09 14:41:14'),
('admin_log_product_group_delete',1,'product group deleted','admin','2008-12-23 16:16:29'),
('admin_newsletter_email_history_type',1,'Type:','admin','2009-01-12 08:58:32'),
('admin_coupon_edit_coupons_changed_ok',1,'Coupon was successfully modified','admin','2008-12-19 16:18:47'),
('admin_log_additional_field_add',1,'additional field added','admin','2008-12-23 16:16:29'),
('admin_newsletter_email_history_tpl_type_all',1,'All','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_tpl_type',1,'Template type:','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_type_queue',1,'Queue','admin','2009-01-12 08:58:32'),
('demo_msg_er_functionality_disabled',1,'Sorry, DEMO version, this functionality is disabled.','both','2009-06-02 11:00:38'),
('admin_log_access_level_modify',1,'access level modified','admin','2008-12-23 16:16:29'),
('user_profile_status_suspended',1,'suspended','user','2009-01-10 10:13:59'),
('admin_log_ban_ip_add',1,'banned IP address added','admin','2008-12-23 16:16:29'),
('user_profile_status_not_approved',1,'not_approved','user','2008-12-03 11:13:04'),
('admin_log_additional_field_delete',1,'additional field deleted','admin','2008-12-23 16:16:29'),
('admin_log_menage_news_edit',1,'news edited','admin','2008-12-23 16:16:29'),
('admin_log_menage_news_members_only',1,'news changed','admin','2008-12-23 16:16:29'),
('admin_log_menage_news_remove',1,'news deleted','admin','2008-12-23 16:16:29'),
('admin_newsletter_email_history_tpl_type_user',1,'Members','admin','2009-05-20 12:20:48'),
('admin_log_menage_news_published',1,'news published','admin','2008-12-23 16:16:29'),
('admin_log_account_delete',1,'account deleted','admin','2008-12-23 16:16:29'),
('admin_log_product_block',1,'product blocked','admin','2008-12-23 16:16:29'),
('admin_log_payment_system_status_modify',1,'payment system status modified','admin','2008-12-23 16:16:29'),
('admin_log_payment_system_modify',1,'payment system modified','admin','2008-12-23 16:16:29'),
('admin_log_news_modify',1,'news modified','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_show_in_menu',1,'custom page modified (status in menu)','admin','2008-12-23 16:16:29'),
('admin_log_menage_pages_remove',1,'custom page removed','admin','2008-12-23 16:16:29'),
('admin_member_control_error_email_not_sent',1,'Error: System email is not sent to the member','admin','0000-00-00 00:00:00'),
('user_profile_status_activated',1,'activated','user','2009-01-09 12:04:11'),
('user_profile_status_approved',1,'approved','user','2009-01-09 11:05:51'),
('user_profile_status_unsuspended',1,'unsuspended','user','2009-01-10 10:14:12'),
('user_profile_status_not_activated',1,'not_activated','user','2008-12-02 10:33:17'),
('admin_log_mailer_connection_test',1,'mailer connection test','admin','2008-12-23 16:16:29'),
('admin_log_member_pwd_modify',1,'member password changed','admin','2008-12-23 16:16:29'),
('admin_log_member_delete',1,'member deleted','admin','2008-12-23 16:16:29'),
('admin_log_member_info_modify',1,'member information modified','admin','2009-01-10 15:19:29'),
('user_profile_status_extended',1,'extended','user','2008-12-23 12:58:00'),
('admin_member_control_error_member_activation_link_not_sent',1,'Error: Activation link is not sent to the member','admin','0000-00-00 00:00:00'),
('admin_log_menage_pages_members_only',1,'custom page modified (status members only)','admin','2008-12-23 16:16:29'),
('admin_access_level_msg_er_is_in_use',1,'Access level is in use','admin','2009-01-10 14:55:45'),
('admin_log_product_add',1,'product added','admin','2008-12-23 16:16:29'),
('admin_log_coupon_group_delete',1,'coupon deleted','admin','2008-12-23 16:16:29'),
('admin_newsletter_email_history_action',1,'Action','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_priority',1,'Priority','admin','2009-01-12 08:58:32'),
('admin_payment_system_checkout2_msg_er_not_defined',1,'Sorry, error occured.','admin','2008-12-26 08:22:07'),
('admin_newsletter_email_history_user_type',1,'Members type','admin','2009-05-20 12:20:48'),
('admin_newsletter_email_history_user_login',1,'Login','admin','2009-01-12 08:58:32'),
('admin_payment_system_checkout2_msg_er_udefined_action',1,'Sorry, can\'t procceed with this type of action.','admin','2008-12-26 08:22:07'),
('admin_newsletter_email_history_date',1,'Date','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_email_tpl_id',1,'Template ID','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_person',1,'Person:','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_history_tpl_type_admin',1,'Admins','admin','2009-01-12 08:58:32'),
('demo_header_warning',1,'DEMO VERSION! All data will be cleared in ','undef','2009-01-12 08:35:24'),
('admin_log_account_modify',1,'account modified','admin','2008-12-23 16:16:29'),
('admin_newsletter_email_history_tpl_type_newsletter',1,'Newsletters','admin','2008-12-18 17:31:35'),
('admin_admin_edit_msg_er_undefined_access',1,'You should define access level.','admin','2009-01-10 14:54:20'),
('admin_log_access_level_delete',1,'access level deleted','admin','2008-12-23 16:16:29'),
('admin_log_directory_protection_modify',1,'directory protection modified','admin','2008-12-23 16:16:29'),
('admin_member_settings_exp_subscr_notif_period',1,'Inform members by email about almost expired subscription before period, days: ','admin','2009-05-20 12:25:56'),
('admin_log_menage_news_add',1,'news added','admin','2008-12-23 16:16:29'),
('admin_log_remind_password',1,'remind password','admin','2008-12-23 16:16:29'),
('admin_log_remind_code',1,'remind code generated','admin','2008-12-23 16:16:29'),
('admin_log_current_language_modify',1,'current language modified','admin','2008-12-23 16:16:29'),
('admin_manage_pages_copy_link',1,'Copy link to clipboard','admin','2009-01-10 15:20:14'),
('admin_newsletter_email_history_type_history',1,'History','admin','2009-01-12 08:58:32'),
('admin_manage_pages_copy_link_success',1,'The link was successfully copied','admin','2009-01-10 15:20:14'),
('admin_header_status_offline',1,'SYSTEM STATUS IS OFFLINE','admin','2009-01-12 08:57:39'),
('admin_config_lang_editor_tab_label',1,'Label','admin','2009-01-10 16:39:13'),
('admin_config_lang_editor_tab_value',1,'Value','admin','2009-01-10 16:39:13'),
('admin_copy_to_clipboard_dialog',1,'Your browser does not support the copy-to-clipboard functionality. Please copy the link below manually.','admin','2009-01-12 08:57:39'),
('admin_member_settings_msg_er_emails_intersect',1,'You have input the same value in trusted and in denied domains.','admin','2009-01-10 14:44:46'),
('admin_member_settings_msg_er_exp_subscr',1,'Period must be specified in days!','admin','2009-01-10 14:44:46'),
('admin_sys_emails_tpl_user_payment_notification',1,'Payment notification','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_subscription_almost_expired',1,'Product subscription is almost expired','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_subscription_expired',1,'Product subscription is expired','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_profile_status_change',1,'Your account status was changed','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_account_expire',1,'Your account is expired','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_account_activation',1,'Your account needs activation','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_change_password',1,'Your password was changed','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_registration_completed',1,'Successfully registered','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_profile_change',1,'Your member profile was changed','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_user_payment_error',1,'Payment error notification','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_new_member_registered',1,'New member account is registered','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_subscription_ended',1,'Product subscription is ended/expired','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_subscription_started',1,'Product subscription is started','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_payment_error',1,'Payment error notification','admin','2009-01-12 08:57:33'),
('admin_payment_system_authorize_net_msg_er_udefined_action',1,'Sorry, can\'t procceed with this type of action.','admin','2008-12-26 08:22:07'),
('admin_sys_emails_tpl_admin_payment_notification',1,'Payment notification','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_account_deleted',1,'Administrator account deleted','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_account_changed',1,'Administrator account changed','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_account_created',1,'Administrator account created','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_your_admin_account_deleted',1,'Your administrator account deleted','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_admin_remind_password',1,'Remind password','admin','2008-12-04 14:53:14'),
('admin_sys_emails_tpl_your_admin_account_changed',1,'Your administrator account changed','admin','2009-01-12 08:57:33'),
('admin_sys_emails_tpl_your_admin_account_created',1,'Your administrator account created','admin','2009-01-12 08:57:33'),
('directories_add_protection_db_unable_to_delete',1,'Failed to delete the record from the database. ','admin','2009-01-10 10:09:59'),
('admin_lang_manager_msg_er_additional',1,'You should specify the correct field value.','admin','2009-01-12 08:57:14'),
('admin_payment_system_paypal_msg_er_udefined_action',1,'Sorry, can\'t procceed with this type of action.','admin','2009-01-10 14:44:58'),
('admin_payment_system_paypal_msg_er_not_defined',1,'Sorry, error occured.','admin','2009-01-10 14:44:58'),
('user_registration_error_validation_fail',1,'Please provide a correct data to proceed with the registration.','user','2009-01-09 12:02:29'),
('admin_newsletter_email_templates_msg_ok_deleted',1,'Email template was successfully deleted.','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_templates_msg_er_subject',1,'Please specify the correct email subject.','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_templates_msg_er_message',1,'Please specify the correct email body.','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_templates_msg_er_not_deleted',1,'Sorry, email template was not deleted.','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_templates_msg_er_not_found',1,'Sorry, email template was not found.','admin','2009-01-12 08:58:32'),
('admin_newsletter_email_templates_msg_er_access_denied',1,'Access denied! Sorry, you have no appropriate access level.','admin','2009-01-12 08:58:32'),
('admin_edit_sys_template_msg_er_name',1,'You should specify the correct template name!','admin','2009-01-10 10:25:54'),
('admin_edit_sys_template_name',1,'Template name','admin','2009-01-10 10:25:54'),
('admin_config_add_fields_error_message',1,'Sorry, error occured while updating.','admin','2009-01-10 14:44:35'),
('admin_coupon_delete_coupons_deleted_ok',1,'Coupon was successfully deleted','admin','2008-12-08 14:27:29'),
('admin_config_ban_ip_lang_edit_header_subject',1,'Edit ban reason:','admin','2009-01-10 16:51:05'),
('admin_config_ban_ip_lang_header_comment',1,'You can change ban reasons on this page','admin','2009-01-10 16:51:05'),
('admin_config_ban_ip_lang_msg_er_subject',1,'Please, input a correct ban reason value!','admin','2009-01-10 16:51:05'),
('admin_config_ban_ip_lang_subject',1,'Ban reason','admin','2009-01-10 16:51:05'),
('admin_lang_manager_msg_er_message',1,'You should specify the correct field value.','admin','2009-01-10 16:51:05'),
('admin_edit_sys_template_html_header_subject',1,'Edit html template for system emails:','admin','2009-01-12 08:57:14'),
('admin_edit_sys_template_html_header_comment',1,'On this page you can edit HTML template for system emails ','admin','2009-01-12 08:57:14'),
('admin_config_add_fields_lang_header_comment',1,'Edit Additional Field header here','admin','2008-12-09 14:41:14'),
('admin_config_lang_editor_choose',1,'Choose file','admin','2009-01-10 14:45:24'),
('admin_msg_er_0025',1,'Invalid file type','admin','0000-00-00 00:00:00'),
('admin_msg_er_0026',1,'Invalid file extension','admin','0000-00-00 00:00:00'),
('admin_msg_er_0027',1,'Invalid file content','admin','0000-00-00 00:00:00'),
('directories_btn_delete_anyway',1,'delete anyway','admin','2008-12-18 13:59:24'),
('admin_login_form_msg_capcha_code',1,'Wrong verification code','admin','2008-12-23 11:14:18'),
('admin_login_form_msg_er_ip_blocked',1,'Your`s IP address temporary baned, try again later.','admin','2009-01-12 08:57:39'),
('debug_header_warning',1,'DEBUG MODE','undef','2009-01-12 09:15:49'),
('admin_login_input_capcha',1,'Input code from the image','admin','2009-01-12 08:57:39'),
('directories_add_protection_assotiated_products_exist',1,'Associated products exist','admin','2009-01-10 10:09:59'),
('admin_msg_er_0028',1,'Warning: Denied domain!','admin','2009-01-09 12:05:08'),
('admin_login_form_msg_er_capcha_code',1,'Wrong CAPTCHA code','admin','2009-01-12 08:57:39'),
('admin_sys_emails_add_button',1,'add template','admin','2009-01-10 13:01:32'),
('admin_login_capcha_reload',1,'Reload','admin','2009-01-12 08:57:39'),
('admin_log_default_language_delete',1,'Default language delete','admin','2008-12-23 16:16:29'),
('admin_log_status_message_modify',1,'Status message modify','admin','2008-12-23 16:16:29'),
('admin_coupon_statistic_table_undefined_end_date',1,'termless','admin','2008-12-19 16:42:44'),
('admin_config_ban_ip_msg_ok_deleted',1,'Deleted successfully','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_success_message',1,'Done successfully','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_msg_delete_success',1,'Delete success','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_msg_er__not_saved',1,'Saving fail','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_msg_er_not_deleted',1,'Deleting fail','admin','2009-01-10 16:51:49'),
('admin_config_ban_ip_msg_er_access_denied',1,'Access denied','admin','2009-01-10 16:51:49'),
('admin_sys_emails_tpl_user_remind_password',1,'User remind password','admin','2009-01-12 08:57:33'),
('admin_msg_er_0029',1,'Invalid target language','admin','2008-12-23 16:11:36'),
('user_remind_password_msg_link_is_sent',1,'Link is sent','user','2008-12-19 14:15:26'),
('user_remind_password_msg_er_remind_code_error',1,'Wrong remind code','user','0000-00-00 00:00:00'),
('user_remind_password_capcha_error',1,'Wrong CAPTCHA code','user','2008-12-22 11:32:03'),
('directories_assotiated_products',1,'Directories associated products ','admin','0000-00-00 00:00:00'),
('country_AF',1,'Afghanistan','both','2008-12-27 06:37:55'),
('country_AS',1,'American Samoa','both','2008-12-27 06:37:55'),
('country_BD',1,'Bangladesh','both','2008-12-27 06:37:55'),
('country_BY',1,'Belarus','both','2008-12-27 06:37:55'),
('country_CF',1,'Central African Republic','both','2008-12-27 06:37:55'),
('country_CI',1,'Cote d`Ivoire','both','2008-12-27 06:37:55'),
('country_CM',1,'Cameroon','both','2008-12-27 06:37:55'),
('country_CU',1,'Cuba','both','2008-12-27 06:37:55'),
('country_EG',1,'Egypt','both','2008-12-27 06:37:55'),
('country_EH',1,'Western Sahara','both','2008-12-27 06:37:55'),
('country_GE',1,'Georgia','both','2008-12-27 06:37:55'),
('country_GH',1,'Ghana','both','2008-12-27 06:37:55'),
('country_GQ',1,'Equatorial Guinea','both','2008-12-27 06:37:55'),
('country_GU',1,'Guam','both','2008-12-27 06:37:55'),
('country_HT',1,'Haiti','both','2008-12-27 06:37:55'),
('country_IQ',1,'Iraq','both','2008-12-27 06:37:55'),
('country_KP',1,'Korea, Democratic People\'s Republic of','both','2008-12-27 06:37:55'),
('country_LB',1,'Lebanon','both','2008-12-27 06:37:55'),
('country_LR',1,'Liberia','both','2008-12-27 06:37:55'),
('country_LY',1,'Libyan Arab Jamahiriya','both','2008-12-27 06:37:55'),
('country_MC',1,'Monaco','both','2008-12-27 06:37:55'),
('country_MD',1,'Moldova','both','2008-12-27 06:37:55'),
('country_MK',1,'Macedonia','both','2008-12-27 06:37:55'),
('country_MM',1,'Myanmar','both','2008-12-27 06:37:55'),
('country_MO',1,'Macao','both','2008-12-27 06:37:55'),
('country_MP',1,'Northern Mariana Islands','both','2008-12-27 06:37:55'),
('country_NG',1,'Nigeria','both','2008-12-27 06:37:55'),
('country_PK',1,'Pakistan','both','2008-12-27 06:37:55'),
('country_PR',1,'Puerto Rico','both','2008-12-27 06:37:55'),
('country_PS',1,'Palestinian Territory','both','2008-12-27 06:37:55'),
('country_PY',1,'Paraguay','both','2008-12-27 06:37:55'),
('country_RS',1,'Serbia','both','2008-12-27 06:37:55'),
('country_SD',1,'Sudan','both','2008-12-27 06:37:55'),
('country_SY',1,'Syrian Arab Republic','both','2008-12-27 06:37:55'),
('country_TK',1,'Tokelau','both','2008-12-27 06:37:55'),
('country_TL',1,'Timor-Leste','both','2008-12-27 06:37:55'),
('country_VI',1,'Virgin Islands, U.S.','both','2008-12-27 06:37:55'),
('country_ZW',1,'Zimbabwe','both','2008-12-27 06:37:55'),
('country_IR',1,'Iran','both','2008-12-27 06:37:55'),
('country_UZ',1,'Uzbekistan','both','2008-12-27 06:37:55'),
('admin_coupon_coupon_groups_empty_list',1,'No items','admin','2008-12-26 07:56:23'),
('admin_member_control_account_panel_member_info_page_send_email',1,'Send personal email','admin','2009-01-10 10:10:45'),
('admin_member_control_account_panel_member_info_page_email_history',1,'Personal email history','admin','2009-01-10 10:10:45'),
('admin_login_form_msg_er_javascript_disabled',1,'JavaScript disabled','admin','2009-01-12 08:35:12'),
('admin_login_form_msg_er_cookies_disabled',1,'Cookies are not supported.','admin','2009-01-12 08:35:12'),
('admin_global_setup_msg_er_history_kept',1,'Must be integer (1 - 365)','admin','2009-01-10 15:37:42'),
('admin_global_setup_field_label_013',1,'History is kept, days:','admin','2009-01-10 15:37:42'),
('admin_global_setup_ttip_013',1,'Must be integer between 1 and 365  ','admin','2009-01-10 15:37:42'),
('admin_newsletter_tmpl_list_plain',1,'Plain text','admin','2009-01-12 08:57:33'),
('directories_file_protection_msg_copied',1,'The data was successfully copied to clipboard.','admin','2009-01-10 10:10:08'),
('admin_log_member_activation',1,'Member activation','admin','2009-01-10 15:19:29'),
('admin_newsletter_send_email_step1_user_category_all_suspended',1,'all suspended','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_btn_new',1,'new','admin','2009-01-10 13:01:44'),
('admin_newsletter_send_email_btn_send',1,'send','admin','2009-01-10 13:01:44'),
('admin_newsletter_email_history_sending_text',1,'Sending...','admin','2009-01-12 08:58:32'),
('admin_newsletter_tmpl_list_html',1,'HTML','admin','2009-01-12 08:57:33'),
('admin_newsletter_tmpl_list_delete',1,'Delete','admin','2009-01-10 13:01:32'),
('admin_btn_edit',1,'Edit','admin','2009-01-12 09:12:43'),
('admin_lang_manager_copy_from',1,'Copy from another language','admin','2009-01-12 08:57:14'),
('admin_label_filter',1,'Filter','admin','2009-01-12 10:19:14'),
('admin_lang_manager_btn_default',1,'default','admin','2009-01-12 11:05:46'),
('admin_member_control_error_member_id_invalid',1,'Some member ID is invalid','admin','2009-01-10 15:37:42'),
('admin_newsletter_email_history_send_portion',1,'Send a portion','admin','0000-00-00 00:00:00'),
('product_list_filter_member_group',1,'Member group:','admin','2008-12-19 14:24:53'),
('product_list_filter_member_group_available',1,'Only available','admin','2008-12-19 14:24:53'),
('product_add_member_groups_warning_not_visible',1,'Attention! The product cannot be seen in any of the user groups!','admin','2008-12-19 14:24:53'),
('product_add_member_groups_description',1,'visible / available','admin','2008-12-19 14:24:53'),
('product_add_member_groups',1,'Member_groups','admin','2008-12-19 14:24:53'),
('product_save_member_groups_warning_not_visible',1,'Attention! The product cannot be seen in any of the user groups!','admin','2008-12-19 14:24:53'),
('product_save_member_groups_description',1,'visible / available','admin','2008-12-19 14:24:53'),
('product_save_member_groups',1,'Member_groups','admin','2008-12-19 14:24:53'),
('admin_member_control_member_list_search_by_select_option_group',1,'Group','admin','2008-12-19 14:24:53'),
('admin_member_control_member_info_view_field_groups',1,'Groups:','admin','2008-12-19 14:24:53'),
('admin_member_control_add_member_field_groups',1,'Groups:','admin','2008-12-19 14:24:53'),
('admin_member_control_account_panel_member_info_field_groups',1,'Groups:','admin','2008-12-19 14:24:53'),
('admin_member_group_msg_are_you_sure',1,'Once the group is deleted, the products become invisible for the members in the group.','admin','2009-05-20 12:17:18'),
('admin_member_group_page_title',1,'Member groups','admin','2008-12-19 14:24:53'),
('admin_member_group_page_desc',1,'This is the list of the members groups.','admin','2009-05-20 12:17:18'),
('admin_member_group_msg_ok_deleted',1,'The group is deteled successfully.','admin','2008-12-19 14:24:53'),
('admin_member_group_msg_er_not_deleted',1,'The group is not deleted.','admin','2008-12-19 14:24:53'),
('admin_member_group_msg_er_not_found',1,'The group is not found.','admin','2008-12-19 14:24:53'),
('admin_member_group_msg_er_access_denied',1,'The access is not allowed.','admin','2008-12-19 14:24:53'),
('admin_member_group_id',1,'Id','admin','2008-12-19 14:24:53'),
('admin_member_group_name',1,'Name','admin','2008-12-19 14:24:53'),
('admin_member_group_users',1,'Members','admin','2009-05-20 12:17:18'),
('admin_member_group_products',1,'Products','admin','2008-12-19 14:24:53'),
('admin_member_group_action',1,'Action','admin','2008-12-19 14:24:53'),
('admin_member_group_btn_add',1,'Add group','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_header_subject',1,'Editing member group:','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_header_comment',1,'The change of the user group.','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_msg_er_subject',1,'Name is invalid!','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_msg_er_message',1,'Description is invalid!','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_subject',1,'Name:','admin','2008-12-19 14:24:53'),
('admin_member_group_edit_message',1,'Description:','admin','2008-12-19 14:24:53'),
('product_list_filter_unavailable',1,'Unavailable','admin','2008-12-19 14:24:53'),
('directories_reprotect_comment',1,'Re-protect all the directories which were last protected more than:','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_1_second',1,'1 second ago','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_10_minute',1,'10 minutes ago','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_1_hour',1,'1 hour ago','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_1_day',1,'1 day ago','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_1_week',1,'1 week ago','admin','2008-12-19 14:24:53'),
('directories_reprotect_period_1_month',1,'1 month ago','admin','2008-12-19 14:24:53'),
('product_list_edit_settings_button_alt',1,'Edit product ','admin','2008-12-19 14:24:53'),
('user_active_products_node_links',1,'Links ','user','2009-03-30 00:00:03'),
('directories_add_protection_system_directory_is_not_protectable',1,'The current directory cannot be protected because this will cause the system failure','admin','2009-03-30 00:00:04'),
('user_cart_error_domain_must_by',1,'Domain name must be check and select','user','2008-12-19 14:24:53'),
('user_cart_additional_panel',1,'Check for domain name availability','user','2008-12-19 14:24:53'),
('user_cart_hosted_check_domain',1,'Domain name: ','user','2008-12-19 14:24:53'),
('user_cart_hosted_btn_check_domain',1,'Check','user','2008-12-19 14:24:53'),
('user_cart_btn_next',1,'Next','user','2008-12-19 14:24:53'),
('product_add_product_type',1,'Product type','admin','2008-12-19 14:24:53'),
('product_save_product_type_1',1,'protection','admin','2008-12-19 14:24:53'),
('product_save_product_type_2',1,'hosting','admin','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_month3',1,'3 month','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_month6',1,'6 month','user','2008-12-19 14:24:53'),
('user_config_mainpage_product_price_year5',1,'5 years','user','2008-12-19 14:24:53'),
('product_save_error_host_type_error',1,'Please select product plan','admin','2008-12-19 14:24:53'),
('admin_member_control_account_panel_member_info_button_save_alert',1,'Attention! You are editing unactive user.','user','2009-05-20 09:49:07'),
('admin_member_control_account_panel_payments_form_field_domen_name',1,'Enter domain name:','admin','2009-01-12 08:57:39'),
('admin_member_control_account_panel_payments_add_error_domain_name_empty',1,'Error: Domain name is empty','admin','2008-12-19 14:24:53'),
('admin_member_control_account_panel_payments_add_error_domain_name_length',1,'Error: Domain name info length is out of range','admin','2008-12-19 14:24:53'),
('product_list_edit_not_active',1,'product is not active','admin','2008-12-19 14:24:53'),
('product_list_edit_special',1,'click to remove from special','admin','2008-12-19 14:24:53'),
('product_list_edit_not_special',1,'click to do special','admin','2008-12-19 14:24:53'),
('user_cart_btn_prev',1,'Back','user','2008-12-19 14:24:53'),
('admin_log_host_plan_add',1,'Host plan added','admin','2008-12-19 14:24:53'),
('admin_log_host_plan_delete',1,'Host plan deleted','admin','2008-12-19 14:24:53'),
('admin_log_host_plan_modify',1,'Host plan modified','admin','2008-12-19 14:24:53'),
('admin_log_host_settings_modify',1,'Host plan settings modified','admin','2008-12-19 14:24:53'),
('admin_member_group_action_edit',1,'Edit','admin','2008-12-19 14:24:53'),
('admin_coupon_edit',1,'Edit coupon','admin','2008-12-19 14:24:53'),
('admin_field_edit',1,'Edit additional field','admin','2008-12-19 14:24:53'),
('admin_news_edit',1,'Edit news','admin','2008-12-19 14:24:53'),
('admin_page_edit',1,'Edit page','admin','2008-12-19 14:24:53'),
('admin_language_edit',1,'Edit language','admin','2008-12-19 14:24:53'),
('admin_admin_edit',1,'Edit administrator','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_active',1,'Active','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_all',1,'All','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_suspend',1,'Suspend','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_notapproved',1,'Not approved','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_notaactivated',1,'Not activate','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_expired',1,'Expire','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_unsuspend',1,'Unsuspend','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_approve',1,'Approve','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_disapprove',1,'Disapprove','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_activate',1,'Activate','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_inactivate',1,'Inactivate','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_delete',1,'Delete','admin','2008-12-19 14:24:53'),
('admin_memberlist_oper_button',1,'Apply','admin','2009-05-12 02:05:42'),
('admin_header_support',1,'Support','admin','2008-12-19 14:24:53'),
('admin_log_product_special',1,'Changed special product','admin','2009-04-28 19:24:53'),
('admin_log_not_load_hosted_method',1,'Not loaded hosted method','admin','2009-04-28 19:24:53'),
('admin_log_menage_news_special_news',1,'Menage special news','admin','2009-04-28 19:24:53'),
('admin_log_hosting_connection_test',1,'Testing connection to host','admin','2009-04-28 19:24:53'),
('admin_log_hosted_user_updated',1,'Hosted user updated','admin','2009-04-28 19:24:53'),
('admin_log_hosted_user_unsuspended',1,'Hosted user unsuspended','admin','2009-04-28 19:24:53'),
('admin_log_hosted_user_suspended',1,'Hosted user suspended','admin','2009-04-28 19:24:53'),
('admin_log_hosted_subscription_expired',1,'Hosted subscription expired','admin','2009-04-28 19:24:53'),
('admin_log_hosted_subscription_started',1,'Hosted subscription started','admin','2009-04-28 19:24:53'),
('admin_log_hosted_user_deleted',1,'Hosted user deleted','admin','2009-04-28 19:24:53'),
('admin_menu_ban_ip_list',1,'Ban IP List','admin','2008-12-19 14:24:53'),
('admin_menu_ban_ip_add',1,'Ban IP Add','admin','2008-12-19 14:24:53'),
('admin_config_ban_ip_add_label',1,'Add IP ','admin','2008-12-19 14:24:53'),
('admin_config_ban_ip_add_label_desc',1,'Add IP adresses to banned list','admin','2008-12-19 14:24:53'),
('admin_host_plans_add_type_domen_host_plan_url_is_invalid',1,'Type domain is invalid','admin','2009-04-30 13:24:53'),
('admin_host_settings_tested_auth_fail',1,'Members authentication failed, please check your settings.','admin','2009-05-20 12:29:28'),
('admin_host_settings_tested_successfully',1,'Connection was successfully established.','admin','2009-01-10 14:44:49'),
('admin_host_settings_saved_successfully',1,'New settings was changed successfully','admin','2009-01-10 14:44:49'),
('admin_host_settings_error',1,'Please check your input, some error occured.','admin','2009-01-10 14:44:49'),
('admin_host_settings_tested_fail',1,'Connection could not be established.','admin','2009-01-10 14:44:49'),
('admin_host_settings_msg_er_host',1,'Value must be a valid URL or IP address!','admin','2009-01-10 14:44:49'),
('admin_host_settings_msg_er_port',1,'Value must be a valid integer, in the range (0 - 65535).','admin','2009-01-10 14:44:49'),
('admin_host_settings_msg_er_user',1,'Value must be a valid username!','admin','2009-05-20 12:29:28'),
('admin_host_settings_msg_er_pass',1,'Value must be a valid password!','admin','2009-01-10 14:44:49'),
('admin_member_control_member_list_search_label_date',1,'Date','admin','2008-12-19 14:24:53'),
('tool_tip_activate',1,'Activates a member account. The account is inactivated automatically if a paragraph \"New accounts wait for approval\" is selected in \"System configuration->member settings\" while registration process. The account will stay inactivated until a member confirms his registration passing on the hyperlink in received confirmation e-mail.','admin','2008-12-19 14:24:53'),
('tool_tip_inactivate',1,'Inactivates a member account. It inactivates member\'s account while registration process. The account will stay inactivated until the member passes on the hyperlink received in the activation e-mail. Such kind of block can be deactivated in \'System configuration->member settings\' (\'Use new accounts activation\' paragraph). The member cannot log into the system through the inactivated account.','both','2009-05-19 01:21:52'),
('tool_tip_suspend',1,'Suspend a member account. The account can\'t be suspend automatically. The member can\'t log into the system through the inactivated account.','admin','2008-12-19 14:24:53'),
('tool_tip_unsuspend',1,'Unsuspend a member account','admin','2008-12-19 14:24:53'),
('tool_tip_approve',1,'Approve a member account. The account can\'t be approved automatically. The account is approved automatically if a paragraph \'New accounts wait for approval\' is selected in \'System configuration->member settings\'.','admin','2008-12-19 14:24:53'),
('tool_tip_disapprove',1,'Disapprove a member account. The account is approved automatically if a paragraph \"New accounts wait for approval\" is selected in \"System configuration->member settings\" while registration process.','admin','2008-12-19 14:24:53'),
('tool_tip_system_is_activate',1,'Current settings require activation of a member account.','admin','2008-12-19 14:24:53'),
('tool_tip_system_is_inactivate',1,'Current settings do not require activation of a member account.','admin','2008-12-19 14:24:53'),
('tool_tip_system_is_approval',1,'Current settings require approval of a member account.','admin','2008-12-19 14:24:53'),
('tool_tip_system_is_disapproval',1,'Current settings do not require approval of a member account.','admin','2008-12-19 14:24:53'),
('tool_tip_delete',1,'Delete checked member(s).','admin','2008-12-19 14:24:53'),
('admin_member_control_member_list_table_status_tooltip',1,'This column displays the current state of the user account. The first is activate/unactivate account. The second is approve/disapprove account. The third is suspend/unsuspend account. Click the corresponding flag in order to change the account status.','admin','2008-12-19 14:24:53'),
('tool_tip_status_inactivate',1,'Disable a member','admin','2008-12-19 14:24:53'),
('tool_tip_status_activate',1,'Activate a member','admin','2008-12-19 14:24:53'),
('tool_tip_status_disapprove',1,'Disapprove a member','admin','2008-12-19 14:24:53'),
('tool_tip_status_approve',1,'Approve a member','admin','2008-12-19 14:24:53'),
('tool_tip_status_unsuspend',1,'Unsuspend a member','admin','2008-12-19 14:24:53'),
('tool_tip_status_suspend',1,'Suspend a member','admin','2008-12-19 14:24:53'),
('admin_memberlist_status_inactive',1,'Inactive','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_header_subject',1,'Page Configurator','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_header_comment',1,'Here you can reconfigurate pages','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_table',1,'Available pages','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_reg',1,'Member registration page config','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_profile',1,'Member profile page config','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_tooltip_reg',1,'Includes fields display options for the registration page.','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_tooltip_reg_button',1,'Options','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_tooltip_profile',1,'Includes fields display options for the member profile management page.','admin','2009-05-20 12:27:55'),
('admin_design_manager_constructor_tooltip_profile_button',1,'Options','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_tooltip_pass',1,'Includes fields display options for the user password change page.','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_tooltip_pass_button',1,'Options','admin','2008-12-19 14:24:53'),
('admin_design_manager_constructor_pass',1,'Member password page config','admin','2008-12-19 14:24:53'),
('admin_member_pages_registration_header_subject',1,'Registration Page','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_image_code_field_comment',1,'Image code field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_image_code_option_name',1,'Image code','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_image_code_option_ttip',1,'Dynamic image code (CAPTCHA) activation','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_lname_field_comment',1,'Last name field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_lname_option_name',1,'Last name','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_lname_option_ttip',1,'Last name field. \nYou can set minimum and maximum length for the input field. \nThe option Р Р†Р вЂљРЎС™RequiredР Р†Р вЂљРЎСљ doesnР Р†Р вЂљРІвЂћСћt allow empty values in the field.','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_login_field_comment',1,'Login field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_login_option_name',1,'Login','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_login_option_ttip',1,'Username field. If disabled, user email address is used as username. \nFor a proper system functioning, you need to select a field type (\"Latin\",\"Simple latin\").\nThere\'s also a possibility to set minimum and maximum field length. \nThe option \"Required\" doesn\'t allow empty values for the field.','admin','2009-05-06 13:23:46'),
('admin_member_pages_profile_fname_option_ttip',1,'First name input field. \nThere is a possibility to set minimum and maximum length\nThe option \"Required\" doesn\'t allow empty values for the field.','admin','2009-05-06 11:13:05'),
('admin_member_pages_registration_option_enabled',1,'Enabled','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_generate',1,'Generate checkbox','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_length',1,'Length','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_length_limit',1,'limit','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_length_max',1,'max','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_length_min',1,'min','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_name',1,'Name','admin','2009-05-06 09:39:37'),
('admin_member_pages_registration_option_options',1,'Options','admin','2009-05-06 09:39:37'),
('admin_member_pages_registration_option_required',1,'Required','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_retype',1,'Retype','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_option_type',1,'Type','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_password_field_comment',1,'Password field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_password_option_name',1,'Password','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_password_option_ttip',1,'Password input field. When disabled, passwords are generated automatically. \nThe option \"Repeat\" adds another field for password entry. \nFor a correct system functioning, it is required to select a field type (\"Password\", \"Simple password\", \"Complex password\"). \nThe option \"Allow generation\" enables automatic password generation.','admin','2009-05-06 13:23:46'),
('admin_member_pages_profile_additional_option_ttip',1,'Enable additional fields display.','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_additional_field_comment',1,'Additional fields options','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_additional_option_name',1,'Additional fields','admin','2009-05-06 11:13:05'),
('admin_member_pages_btn_save',1,'Save','admin','2009-05-06 13:23:46'),
('admin_member_pages_msg_er_not_changed',1,'Page settings not modified!','admin','2009-05-06 13:23:46'),
('admin_member_pages_msg_er_not_saved',1,'Page settings not saved!','admin','2009-05-06 13:23:46'),
('admin_member_pages_msg_saved',1,'Page settings saved!','admin','2009-05-06 13:23:46'),
('admin_member_pages_option_name',1,'Name','admin','2009-05-06 13:23:46'),
('admin_member_pages_option_options',1,'Options','admin','2009-05-06 13:23:46'),
('admin_member_pages_password_header_comment',1,'Here you can change options for fields display on a \"Change password\" page for users.','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_header_subject',1,'Change Password page','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_old_password_field_comment',1,'Old password field option','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_old_password_option_name',1,'Old password','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_old_password_option_ttip',1,'The field for old password. When disabled, no Р Р†Р вЂљРЎС™old passwordР Р†Р вЂљРЎСљ is requested.','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_option_enabled',1,'Enabled','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_option_generate',1,'Generate checkbox','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_option_retype',1,'Retype','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_option_type',1,'Type','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_password_field_comment',1,'Password field option','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_password_option_name',1,'Password','admin','2009-05-06 13:05:11'),
('admin_member_pages_password_password_option_ttip',1,'Password input field. When disabled, passwords are generated automatically. \nThe option \"Repeat\" enables another field for an additional password entry. \nFor a proper system functioning, you should select a field type (\"Password\", \"Simple password\", \"Complex password\") \nThe option \"Enable password generation\" enables automatic password generation.','admin','2009-05-06 13:05:11'),
('admin_member_pages_registration_tos_field_comment',1,'Tos field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_tos_option_name',1,'Tos','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_tos_option_ttip',1,'Make the license agreement page required.','admin','2009-05-06 13:23:46'),
('admin_member_pages_profile_email_field_comment',1,'Email field options','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_email_option_name',1,'Email','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_email_option_ttip',1,'Email address field. \nFor a proper system functioning, you should select a field type (\"Email\"). \nThere is a possibility to specify minimum and maximum length. \nThe option \"Repeat\" enables another field for an additional email address entry.','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_header_comment',1,'Here you can change display options for the user profile management page.','admin','2009-05-06 13:03:19'),
('admin_member_pages_profile_header_subject',1,'User profile page','admin','2009-05-06 13:03:19'),
('admin_member_pages_profile_lname_field_comment',1,'Last name field options','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_lname_option_name',1,'Last name','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_lname_option_ttip',1,'Last name field.  nThere is a possibility to specify minimum and maximum length.\nThe option Р Р†Р вЂљРЎС™RequiredР Р†Р вЂљРЎСљ doesnР Р†Р вЂљРІвЂћСћt allow empty values in the field.','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_login_field_comment',1,'Login field options','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_login_option_name',1,'Login','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_login_option_ttip',1,'Enable/disable username display','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_enabled',1,'Enabled','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_length',1,'Length','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_length_limit',1,'limit','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_length_max',1,'max','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_length_min',1,'min','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_required',1,'Required','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_retype',1,'Retype','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_option_type',1,'Type','admin','2009-05-06 11:13:05'),
('admin_member_pages_registration_additional_field_comment',1,'Additional fields options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_additional_option_name',1,'Additional fields','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_additional_option_ttip',1,'Enable/disable additional fields display','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_email_field_comment',1,'Email field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_email_option_name',1,'Email','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_email_option_ttip',1,'Email address input field. \nFor a proper system functioning, you should select a field type (\"Email\"). nThere is a possibility to specify minimum and maximum length. \nThe option \"Repeat\" enables another field for an additional email address entry.','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_field_comment',1,'comment','admin','2009-04-27 09:12:14'),
('admin_member_pages_registration_field_name',1,'name','admin','2009-04-27 09:12:14'),
('admin_member_pages_registration_field_status',1,'status','admin','2009-04-27 09:12:14'),
('admin_member_pages_registration_fname_field_comment',1,'First name field options','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_fname_option_name',1,'First name','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_fname_option_ttip',1,'First name input field. \nThere is a possibility to specify minimum and maximum length.\nThe option \"Required\" doesn\'t allow empty values in the field.','admin','2009-05-06 13:23:46'),
('admin_member_pages_registration_header_comment',1,'Here you can change display options for the user registration page.','admin','2009-05-06 13:23:46'),
('admin_member_pages_profile_fname_option_name',1,'First name','admin','2009-05-06 11:13:05'),
('admin_member_pages_profile_fname_field_comment',1,'First name field options','admin','2009-05-06 11:13:05'),
('admin_member_pages_btn_back',1,'Back','admin','2008-12-19 14:24:53'),
('admin_log_main_page_admin_message_modify',1,'Admin message on main page changed','admin','2009-01-10 14:44:35'),
('admin_member_pages_registration_options_ttip',1,'Page fields and their options','admin','2009-01-10 14:44:35'),
('admin_member_pages_profile_options_ttip',1,'Page fields and their options','admin','2009-01-10 14:44:35'),
('admin_member_pages_password_options_ttip',1,'Page fields and their options','admin','2009-01-10 14:44:35'),
('user_profile_update_label_email_retype',1,'Retype email','admin','2009-01-10 14:44:35'),
('admin_host_settings_msg_er_send_to_count',1,'Value must be a valid integer, in range (1 - 65535).','admin','2009-01-10 14:44:35'),
('user_registration_field_email_retype',1,'Retype email','user','2009-05-08 05:27:29'),
('user_registration_err_not_checked',1,'Field \"{$field}\" is not checked','both','2009-05-08 03:55:05'),
('user_registration_field_email_title',1,'email','user','2009-05-08 03:55:05'),
('user_registration_field_login_title',1,'login','user','2009-05-08 03:55:05'),
('user_registration_field_fname_title',1,'first name','user','2009-05-08 03:55:05'),
('user_registration_field_lname_title',1,'last name','user','2009-05-08 03:55:05'),
('user_registration_field_password_title',1,'password','user','2009-05-08 03:55:05'),
('user_registration_field_tos_title',1,'tos','user','2009-05-08 03:55:05'),
('user_registration_field_image_code_title',1,'image code','user','2009-05-08 03:55:05'),
('user_registration_err_max_range',1,'\"{$field}\" field content is too long','both','2009-05-08 04:58:49'),
('user_registration_err_min_range',1,'\"{$field}\" field content is too short','both','2009-05-08 05:27:29'),
('user_registration_err_required',1,'\"{$field}\" field is required','both','2009-05-25 09:22:40'),
('user_registration_err_type_normal_password',1,'\"{$field}\"  field content must contain at least 2 symbol types from the groups enumerated (capital Latin letters, small Latin letters, digits, symbols: !@#$%^&*=+/~<>?;-)','both','2009-05-08 05:27:29'),
('user_registration_err_invalid',1,'\"{$field}\" field content is invalid','both','2009-05-08 05:27:29'),
('user_registration_err_type_simple_password',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-)','both','2009-05-08 05:53:49'),
('user_registration_err_type_complex_password',1,'\"{$field}\" field must contain capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-) ','both','2009-05-08 05:57:30'),
('user_profile_err_not_checked',1,'Field \"{$field}\" is not checked','both','2009-05-08 03:55:05'),
('user_profile_field_email_title',1,'email','user','2009-05-08 03:55:05'),
('user_profile_field_login_title',1,'login','user','2009-05-08 03:55:05'),
('user_profile_field_fname_title',1,'first name','user','2009-05-08 03:55:05'),
('user_profile_field_lname_title',1,'last name','user','2009-05-08 03:55:05'),
('user_profile_err_max_range',1,'\"{$field}\" field content is too long','both','2009-05-08 04:58:49'),
('user_profile_err_min_range',1,'\"{$field}\" field content is too short','both','2009-05-08 05:27:29'),
('user_profile_err_required',1,'\"{$field}\" field is required','both','2009-05-25 09:22:40'),
('user_profile_err_type_normal_password',1,'\"{$field}\"  field content must contain at least 2 symbol types from the groups enumerated (capital Latin letters, small Latin letters, digits, symbols: !@#$%^&*=+/~<>?;-)','both','2009-05-08 05:27:29'),
('user_profile_err_invalid',1,'\"{$field}\" field content is invalid','both','2009-05-08 05:27:29'),
('user_profile_field_email_retype',1,'Retype email:','user','2009-05-08 05:27:29'),
('user_profile_err_type_simple_password',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-)','both','2009-05-08 05:53:49'),
('user_profile_err_type_complex_password',1,'\"{$field}\" field must contain capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-) ','both','2009-05-08 05:57:30'),
('user_password_field_old_password_title',1,'old password','user','2009-05-08 03:55:05'),
('user_password_field_password_title',1,'password','user','2009-05-08 03:55:05'),
('user_password_err_max_range',1,'\"{$field}\" field content is too long','both','2009-05-08 04:58:49'),
('user_password_err_min_range',1,'\"{$field}\" field content is too short','both','2009-05-08 05:27:29'),
('user_password_err_required',1,'\"{$field}\" field is required','both','2009-05-25 09:22:40'),
('user_password_err_type_normal_password',1,'\"{$field}\"  field content must contain at least 2 symbol types from the groups enumerated (capital Latin letters, small Latin letters, digits, symbols: !@#$%^&*=+/~<>?;-)','both','2009-05-08 05:27:29'),
('user_password_err_invalid',1,'\"{$field}\" field content is invalid','both','2009-05-08 05:27:29'),
('user_password_field_email_retype',1,'Retype email:','user','2009-05-08 05:27:29'),
('user_password_err_type_simple_password',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-)','both','2009-05-08 05:53:49'),
('user_password_err_type_complex_password',1,'\"{$field}\" field must contain capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-) ','both','2009-05-08 05:57:30'),
('user_registration_err_exists',1,'This {$field} is already being used in system','both','2009-05-12 09:58:50'),
('user_registration_err_type_email',1,'\"{$field}\" field must contain valid email address','both','2009-05-12 10:12:19'),
('user_registration_err_type_simple_latin',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol (_)','both','2009-05-12 10:20:41'),
('user_registration_err_retype',1,'Retyped data are not identical to \"{$field}\" field content','both','2009-05-12 10:25:33'),
('user_profile_err_exists',1,'This {$field} is already being used in system','both','2009-05-12 09:58:50'),
('user_profile_err_type_email',1,'\"{$field}\" field must contain valid email address','both','2009-05-12 10:12:19'),
('user_profile_err_type_simple_latin',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol (_)','both','2009-05-12 10:20:41'),
('user_profile_err_retype',1,'Retyped data are not identical to \"{$field}\" field content','both','2009-05-12 10:25:33'),
('user_password_err_exists',1,'This {$field} is already being used in system','both','2009-05-12 09:58:50'),
('user_password_err_type_email',1,'\"{$field}\" field must contain valid email address','both','2009-05-12 10:12:19'),
('user_password_err_type_simple_latin',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol (_)','both','2009-05-12 10:20:41'),
('user_password_err_retype',1,'Retyped data are not identical to \"{$field}\" field content','both','2009-05-12 10:25:33'),
('admin_design_manager_constructor_registration',1,'Member registration page config','admin','2009-05-12 02:05:42'),
('admin_design_manager_constructor_tooltip_registration',1,'Includes fields display options for the registration page.','admin','2009-05-12 02:05:42'),
('admin_design_manager_constructor_tooltip_registration_button',1,'Options','admin','2009-05-12 02:05:42'),
('admin_design_manager_constructor_password',1,'Member password page config','admin','2009-05-12 02:05:42'),
('admin_design_manager_constructor_tooltip_password',1,'Includes fields display options for the member password change page.','admin','2009-05-20 12:27:55'),
('admin_design_manager_constructor_tooltip_password_button',1,'Options','admin','2009-05-12 02:05:42'),
('admin_manage_news_date',1,'Date','admin','2009-05-12 02:05:42'),
('admin_manage_news_special',1,'Special','admin','2009-05-12 02:05:42'),
('demo_user_er_functionality_disabled',1,'Sorry, DEMO version, this functionality is disabled, for preview purposes for this user only','admin','2009-05-12 02:05:42'),
('demo_msg_er_functionality_group_disabled',1,'Sorry, DEMO version, this functionality is disabled for this group. Please select another group.','admin','2009-01-10 14:45:24'),
('admin_host_plans_list_check_package',1,'This package is unable.','admin','2009-01-10 14:45:24'),
('user_login_error_restricted_area_path',1,'Access to path denied. Please authorize.','admin','2008-12-26 08:22:07'),
('user_login_error_restricted_area_file',1,'Access to file denied. Please authorize.','admin','2008-12-26 08:22:07'),
('user_login_error_restricted_area_dir',1,'Access to directory denied. Please authorize.','admin','2009-06-01 11:06:45'),
('admin_member_control_add_member_field_email_retype',1,'Retype email:','both','2009-05-13 12:05:35'),
('admin_member_control_add_member_field_fname',1,'First name:','both','2009-05-13 12:05:35'),
('admin_member_control_add_member_field_lname',1,'Last name:','both','2009-05-13 12:05:35'),
('admin_member_control_add_member_field_password_retype',1,'Retype password:','both','2009-05-13 12:05:35'),
('admin_member_control_account_panel_member_info_field_email_retype',1,'Email retype:','both','2009-05-14 08:26:25'),
('admin_member_control_account_panel_member_info_field_fname',1,'First name:','both','2009-05-14 08:26:25'),
('admin_member_control_account_panel_member_info_field_lname',1,'Last name','both','2009-05-14 08:26:25'),
('admin_member_control_account_panel_change_password_field_old_password',1,'Old password:','both','2009-05-14 10:36:15'),
('admin_member_control_account_panel_change_password_field_password',1,'New password:','both','2009-05-14 10:36:15'),
('admin_member_control_account_panel_change_password_field_password_retype',1,'New password retype:','both','2009-05-14 10:36:15'),
('admin_member_control_account_panel_change_password_field_generate_password',1,'Generate passworg:','both','2009-05-14 10:39:14'),
('admin_member_pages_profile_domain_name_option_name',1,'Name','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_name_field_comment',1,'Name field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_company_option_name',1,'Company','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_company_field_comment',1,'Company field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address1_option_name',1,'Address1','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address1_field_comment',1,'Address1 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address2_option_name',1,'Address2','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address2_field_comment',1,'Address2 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address3_option_name',1,'Address3','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_address3_field_comment',1,'Address3 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_city_option_name',1,'City','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_city_field_comment',1,'City field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_state_option_name',1,'State','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_state_field_comment',1,'State field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_country_option_name',1,'Country','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_country_field_comment',1,'Country field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_zip_option_name',1,'Zip','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_zip_field_comment',1,'Zip field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_telnocc_option_name',1,'TelNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_telnocc_field_comment',1,'TelNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_telno_option_name',1,'TelNo','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_telno_field_comment',1,'TelNo field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_alttelnocc_option_name',1,'AltTelNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_alttelnocc_field_comment',1,'AltTelNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_alttelno_option_name',1,'AltTelNo','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_alttelno_field_comment',1,'AltTelNo field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_faxnocc_option_name',1,'FaxNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_faxnocc_field_comment',1,'FaxNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_domain_faxno_option_name',1,'FaxNo','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_domain_faxno_field_comment',1,'FaxNo field options','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_domain_customerlangpref_option_name',1,'CustomerLangPref','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_domain_customerlangpref_field_comment',1,'CustomerLangPref field options','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_domain_header_subject',1,'Domain registration info page','both','2009-05-15 10:56:47'),
('admin_design_manager_constructor_profile_domain',1,'Member domain registration info page config','both','2009-05-15 11:03:28'),
('admin_design_manager_constructor_tooltip_profile_domain',1,'Includes fields display options for the member domain registration info management page.','both','2009-05-20 12:27:55'),
('admin_design_manager_constructor_tooltip_profile_domain_button',1,'Options','both','2009-05-15 11:03:28'),
('admin_member_pages_profile_domain_option_enabled',1,'Enabled','both','2009-05-15 11:07:09'),
('admin_member_pages_profile_domain_option_length',1,'Length','both','2009-05-15 11:24:31'),
('admin_member_pages_profile_domain_option_length_min',1,'min','both','2009-05-15 11:24:31'),
('admin_member_pages_profile_domain_option_length_max',1,'max','both','2009-05-15 11:24:32'),
('admin_member_pages_profile_domain_option_length_limit',1,'limit','both','2009-05-15 11:24:32'),
('admin_member_pages_profile_domain_option_required',1,'Required','both','2009-05-15 11:36:36'),
('admin_member_pages_profile_domain_option_type',1,'type','both','2009-05-15 11:43:55'),
('user_profile_domain_update_name',1,'Name:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_company',1,'Company:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_address1',1,'Address 1:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_address2',1,'Address 2:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_address3',1,'Address 3:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_city',1,'City:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_state',1,'State:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_country',1,'Country:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_zip',1,'Zip code:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_telno',1,'Phone:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_alttelno',1,'Alternative phone:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_faxno',1,'Fax:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_customerlangpref',1,'Language:','both','2009-05-15 12:55:59'),
('user_profile_domain_update_button_save',1,'Save','both','2009-05-15 12:55:59'),
('user_profile_domain_err_min_range',1,'\"{$field}\" field content is too short','both','2009-05-08 05:27:29'),
('admin_member_pages_profile_domain_forceuse_option_name',1,'Don\'t ask for confirmation','both','2009-05-27 11:20:31'),
('admin_member_pages_profile_domain_forceuse_field_comment',1,'Don\'t ask for confirmation options','both','2009-05-27 11:24:26'),
('user_profile_domain_update_forceuse',1,'Don\'t ask for confirmation:','both','2009-05-27 11:21:22'),
('user_menu_account_domain_info',1,'Domain registration info','both','2009-05-18 10:32:43'),
('user_profile_domain_update_page_title',1,'Domain registration information','both','2009-05-18 10:34:50'),
('user_profile_domain_update_page_desc',1,'You can change your domain registration data here','both','2009-05-18 10:34:50'),
('admin_member_control_account_panel_header_tab_domain_info',1,'Domain registration info','both','2009-05-18 10:46:36'),
('admin_member_control_account_panel_member_domain_info_field_forceuse',1,'Don\'t ask for confirmation:','both','2009-05-27 11:22:56'),
('admin_member_control_account_panel_member_domain_info_field_name',1,'Name:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_company',1,'Company:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_address1',1,'Address1:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_address2',1,'Address2:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_address3',1,'Address3:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_city',1,'City:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_state',1,'State:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_country',1,'Country:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_zip',1,'Zip code:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_telno',1,'Phone:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_alttelno',1,'Alternative phone:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_faxno',1,'Fax:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_field_customerlangpref',1,'Language:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_domain_info_button_save',1,'Save','both','2009-05-19 09:16:25'),
('admin_member_control_account_panel_member_domain_info_button_cancel',1,'Cancel','both','2009-05-19 09:29:32'),
('admin_member_control_account_panel_member_domain_info_page_title',1,'Domain registration information','both','2009-05-19 09:20:30'),
('admin_member_pages_profile_billing_name_option_name',1,'Name','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_name_field_comment',1,'Name field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_company_option_name',1,'Company','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_company_field_comment',1,'Company field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address1_option_name',1,'Address1','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address1_field_comment',1,'Address1 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address2_option_name',1,'Address2','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address2_field_comment',1,'Address2 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address3_option_name',1,'Address3','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_address3_field_comment',1,'Address3 field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_city_option_name',1,'City','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_city_field_comment',1,'City field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_state_option_name',1,'State','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_state_field_comment',1,'State field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_country_option_name',1,'Country','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_country_field_comment',1,'Country field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_zip_option_name',1,'Zip','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_zip_field_comment',1,'Zip field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_telnocc_option_name',1,'TelNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_telnocc_field_comment',1,'TelNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_telno_option_name',1,'TelNo','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_telno_field_comment',1,'TelNo field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_alttelnocc_option_name',1,'AltTelNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_alttelnocc_field_comment',1,'AltTelNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_alttelno_option_name',1,'AltTelNo','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_alttelno_field_comment',1,'AltTelNo field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_faxnocc_option_name',1,'FaxNoCc','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_faxnocc_field_comment',1,'FaxNoCc field options','both','2009-05-15 10:51:42'),
('admin_member_pages_profile_billing_faxno_option_name',1,'FaxNo','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_billing_faxno_field_comment',1,'FaxNo field options','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_billing_customerlangpref_option_name',1,'CustomerLangPref','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_billing_customerlangpref_field_comment',1,'CustomerLangPref field options','both','2009-05-15 10:51:43'),
('admin_member_pages_profile_billing_header_subject',1,'Billing info page','both','2009-05-15 10:56:47'),
('admin_design_manager_constructor_profile_billing',1,'Member billing info page config','both','2009-05-15 11:03:28'),
('admin_design_manager_constructor_tooltip_profile_billing',1,'Includes fields display options for the user billing info management page.','both','2009-05-15 11:03:28'),
('admin_design_manager_constructor_tooltip_profile_billing_button',1,'Options','both','2009-05-15 11:03:28'),
('admin_member_pages_profile_billing_option_enabled',1,'Enabled','both','2009-05-15 11:07:09'),
('admin_member_pages_profile_billing_option_length',1,'Length','both','2009-05-15 11:24:31'),
('admin_member_pages_profile_billing_option_length_min',1,'min','both','2009-05-15 11:24:31'),
('admin_member_pages_profile_billing_option_length_max',1,'max','both','2009-05-15 11:24:32'),
('admin_member_pages_profile_billing_option_length_limit',1,'limit','both','2009-05-15 11:24:32'),
('admin_member_pages_profile_billing_option_required',1,'Required','both','2009-05-15 11:36:36'),
('admin_member_pages_profile_billing_option_type',1,'type','both','2009-05-15 11:43:55'),
('user_profile_billing_update_name',1,'Name:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_company',1,'Company:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_address1',1,'Address 1:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_address2',1,'Address 2:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_address3',1,'Address 3:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_city',1,'City:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_state',1,'State:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_country',1,'Country:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_zip',1,'Zip code:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_telno',1,'Phone:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_alttelno',1,'Alternative phone:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_faxno',1,'Fax:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_customerlangpref',1,'Language:','both','2009-05-15 12:55:59'),
('user_profile_billing_update_button_save',1,'Save','both','2009-05-15 12:55:59'),
('user_profile_billing_err_min_range',1,'\"{$field}\" field content is too short','both','2009-05-08 05:27:29'),
('admin_member_pages_profile_billing_forceuse_option_name',1,'Don\'t ask for confirmation','both','2009-05-27 11:19:12'),
('admin_member_pages_profile_billing_forceuse_field_comment',1,'Don\'t ask for confirmation options','both','2009-05-27 11:24:53'),
('user_profile_billing_update_forceuse',1,'Don\'t ask for confirmation:','both','2009-05-27 11:21:53'),
('user_menu_account_billing_info',1,'Billing info','both','2009-05-18 10:32:43'),
('user_profile_billing_update_page_title',1,'Billing information','both','2009-05-18 10:34:50'),
('user_profile_billing_update_page_desc',1,'You can change your billing data here','both','2009-05-18 10:34:50'),
('admin_member_control_account_panel_header_tab_billing_info',1,'Billing info','both','2009-05-18 10:46:36'),
('admin_member_control_account_panel_member_billing_info_field_forceuse',1,'Don\'t ask for confirmation:','both','2009-05-27 11:23:14'),
('admin_member_control_account_panel_member_billing_info_field_name',1,'Name:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_company',1,'Company:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_address1',1,'Address1:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_address2',1,'Address2:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_address3',1,'Address3:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_city',1,'City:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_state',1,'State:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_country',1,'Country:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_zip',1,'Zip code:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_telno',1,'Phone:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_alttelno',1,'Alternative phone:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_faxno',1,'Fax:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_field_customerlangpref',1,'Language:','both','2009-05-18 12:12:25'),
('admin_member_control_account_panel_member_billing_info_button_save',1,'Save','both','2009-05-19 09:16:25'),
('admin_member_control_account_panel_member_billing_info_button_cancel',1,'Cancel','both','2009-05-19 09:29:32'),
('admin_member_control_account_panel_member_billing_info_page_title',1,'Billing information','both','2009-05-19 09:20:30'),
('user_profile_domain_err_type_simple_latin',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol (_)','both','2009-05-12 10:20:41'),
('user_profile_domain_field_zip_title',1,'zip code','both','2009-05-25 10:15:24'),
('user_menu_profile_additional_profile_domain_add',1,'Domain registration profile','user','2009-05-19 01:54:38'),
('user_menu_profile_additional_profile_billing_add',1,'Billing profile','user','2009-05-19 01:54:38'),
('admin_member_pages_btn_load_preset',1,'Load','admin','2009-05-20 12:46:21'),
('admin_member_pages_presets',1,'Presets:','admin','2009-05-20 12:44:50'),
('admin_member_pages_option_enabled',1,'Enabled','admin','2009-05-20 12:56:50'),
('admin_member_pages_last_saved',1,'Last saved','admin','2009-05-21 10:11:53'),
('admin_member_pages_preset_last_saved',1,'Last_saved','admin','2009-05-21 10:20:13'),
('admin_member_pages_preset_default',1,'Default','admin','2009-05-21 11:17:35'),
('admin_member_pages_profile_domain_header_comment',1,'Here you can change display options for the user domain registration info page.','admin','2009-05-21 11:26:56'),
('admin_member_pages_options_ttip',1,'Field options','admin','2009-05-21 01:10:35'),
('admin_domain_settings_btn_test_connection',1,'Test connection','admin','2009-05-22 10:03:07'),
('admin_domain_settings_btn_save',1,'Save','admin','2009-05-22 10:03:07'),
('admin_domain_settings_btn_cancel',1,'Cancel','admin','2009-05-22 10:03:07'),
('admin_domain_settings_saved_successfully',1,'New settings was saved successfully','admin','2009-05-27 04:09:58'),
('admin_domain_settings_msg_er_host',1,'Value must be a valid URL or IP address!','admin','2009-05-27 04:09:58'),
('admin_domain_settings_msg_er_user',1,'Value must be a valid email!','admin','2009-05-27 04:09:58'),
('admin_domain_settings_msg_er_pass',1,'Value must be a valid password!','admin','2009-05-27 04:09:58'),
('admin_domain_settings_password',1,'Password','admin','2009-05-27 04:09:58'),
('admin_domain_parent_id',1,'Parent id','admin','2009-05-22 01:22:21'),
('https_url',1,'https','both','2009-05-22 01:22:21'),
('debug',1,'debug','both','2009-05-22 01:22:21'),
('admin_menu_domain_settings',1,'Domain settings','admin','2009-05-22 01:23:41'),
('admin_domain_settings_error',1,'Settings error!','admin','2009-05-27 04:20:19'),
('admin_domain_settings_test_err',1,'Test disabled','admin','2009-05-27 04:20:19'),
('admin_domain_settings_header_subject',1,'Domains settings','admin','2009-05-22 02:46:18'),
('admin_domain_settings_header_comment',1,'Settings of domain','admin','2009-05-22 02:46:18'),
('admin_domain_settings_header_general',1,'Resellers settings','admin','2009-05-27 04:09:58'),
('admin_domain_settings_username',1,'Username','admin','2009-05-22 02:48:03'),
('user_registration_err_type_phone',1,'\"{$field}\" field must contain valid phone number','both','2009-05-25 08:50:31'),
('user_registration_err_type_zip_code',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol \"-\"','both','2009-05-25 08:50:31'),
('user_registration_err_type_text',1,'\"{$field}\" field may contain any symbols','both','2009-05-25 08:50:31'),
('user_password_err_type_phone',1,'\"{$field}\" field must contain valid phone number','both','2009-05-25 08:50:31'),
('user_password_err_type_zip_code',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol \"-\"','both','2009-05-25 08:50:31'),
('user_password_err_type_text',1,'\"{$field}\" field may contain any symbols','both','2009-05-25 08:50:31'),
('user_password_err_not_checked',1,'Field \"{$field}\" is not checked','both','2009-05-08 03:55:05'),
('user_profile_err_type_phone',1,'\"{$field}\" field must contain valid phone number','both','2009-05-25 08:50:31'),
('user_profile_err_type_zip_code',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol \"-\"','both','2009-05-25 08:50:31'),
('user_profile_err_type_text',1,'\"{$field}\" field may contain any symbols','both','2009-05-25 08:50:31'),
('user_profile_domain_err_type_complex_password',1,'\"{$field}\" field must contain capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-) ','both','2009-05-08 05:57:30'),
('user_profile_domain_err_type_email',1,'\"{$field}\" field must contain valid email address','both','2009-05-12 10:12:19'),
('user_profile_domain_err_type_normal_password',1,'\"{$field}\"  field content must contain at least 2 symbol types from the groups enumerated (capital Latin letters, small Latin letters, digits, symbols: !@#$%^&*=+/~<>?;-)','both','2009-05-08 05:27:29'),
('user_profile_domain_err_type_simple_password',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-)','both','2009-05-08 05:53:49'),
('user_profile_domain_err_type_phone',1,'\"{$field}\" field must contain valid phone number','both','2009-05-25 08:50:31'),
('user_profile_domain_err_type_zip_code',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol \"-\"','both','2009-05-25 08:50:31'),
('user_profile_domain_err_type_text',1,'\"{$field}\" field may contain any symbols','both','2009-05-25 08:50:31'),
('user_profile_domain_err_required',1,'\"{$field}\" field is required','both','2009-05-25 09:22:40'),
('user_profile_domain_err_retype',1,'Retyped data are not identical to \"{$field}\" field content','both','2009-05-12 10:25:33'),
('user_profile_domain_err_not_checked',1,'Field \"{$field}\" is not checked','both','2009-05-08 03:55:05'),
('user_profile_domain_err_exists',1,'This {$field} is already being used in system','both','2009-05-12 09:58:50'),
('user_profile_domain_err_invalid',1,'\"{$field}\" field content is invalid','both','2009-05-08 05:27:29'),
('user_profile_domain_err_max_range',1,'\"{$field}\" field content is too long','both','2009-05-08 04:58:49'),
('user_profile_billing_err_type_complex_password',1,'\"{$field}\" field must contain capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-) ','both','2009-05-08 05:57:30'),
('user_profile_billing_err_type_email',1,'\"{$field}\" field must contain valid email address','both','2009-05-12 10:12:19'),
('user_profile_billing_err_type_normal_password',1,'\"{$field}\"  field content must contain at least 2 symbol types from the groups enumerated (capital Latin letters, small Latin letters, digits, symbols: !@#$%^&*=+/~<>?;-)','both','2009-05-08 05:27:29'),
('user_profile_billing_err_type_simple_latin',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol (_)','both','2009-05-12 10:20:41'),
('user_profile_billing_err_type_simple_password',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbols (!@#$%^&*=+/~<>?;-)','both','2009-05-08 05:53:49'),
('user_profile_billing_err_type_phone',1,'\"{$field}\" field must contain valid phone number','both','2009-05-25 08:50:31'),
('user_profile_billing_err_type_zip_code',1,'\"{$field}\" field may contain only capital and small Latin letters, numbers and symbol \"-\"','both','2009-05-25 08:50:31'),
('user_profile_billing_err_type_text',1,'\"{$field}\" field may contain any symbols','both','2009-05-25 08:50:31'),
('user_profile_billing_err_required',1,'\"{$field}\" field is required','both','2009-05-25 09:22:40'),
('user_profile_billing_err_retype',1,'Retyped data are not identical to \"{$field}\" field content','both','2009-05-12 10:25:33'),
('user_profile_billing_err_not_checked',1,'Field \"{$field}\" is not checked','both','2009-05-08 03:55:05'),
('user_profile_billing_err_exists',1,'This {$field} is already being used in system','both','2009-05-12 09:58:50'),
('user_profile_billing_err_invalid',1,'\"{$field}\" field content is invalid','both','2009-05-08 05:27:29'),
('user_profile_billing_err_max_range',1,'\"{$field}\" field content is too long','both','2009-05-08 04:58:49'),
('user_profile_domain_field_name_title',1,'name','both','2009-05-25 10:15:24'),
('user_profile_domain_field_company_title',1,'company','both','2009-05-25 10:15:24'),
('user_profile_domain_field_country_title',1,'country','both','2009-05-25 10:15:24'),
('user_profile_domain_field_state_title',1,'state','both','2009-05-25 10:15:24'),
('user_profile_domain_field_city_title',1,'city','both','2009-05-25 10:15:24'),
('user_profile_domain_field_address1_title',1,'address 1','both','2009-05-25 10:15:24'),
('user_profile_domain_field_address2_title',1,'address 2','both','2009-05-25 10:15:24'),
('user_profile_domain_field_address3_title',1,'address 3','both','2009-05-25 10:15:24'),
('user_profile_domain_field_telno_title',1,'phone','both','2009-05-25 10:15:24'),
('user_profile_domain_field_alttelno_title',1,'alternative phone','both','2009-05-25 10:15:24'),
('user_profile_domain_field_faxno_title',1,'fax','both','2009-05-25 10:15:24'),
('user_profile_domain_field_customerlangpref_title',1,'language','both','2009-05-25 10:15:24'),
('user_profile_billing_field_name_title',1,'name','both','2009-05-25 10:15:24'),
('user_profile_billing_field_company_title',1,'company','both','2009-05-25 10:15:24'),
('user_profile_billing_field_country_title',1,'country','both','2009-05-25 10:15:24'),
('user_profile_billing_field_state_title',1,'state','both','2009-05-25 10:15:24'),
('user_profile_billing_field_city_title',1,'city','both','2009-05-25 10:15:24'),
('user_profile_billing_field_zip_title',1,'zip code','both','2009-05-25 10:15:24'),
('user_profile_billing_field_address1_title',1,'address 1','both','2009-05-25 10:15:24'),
('user_profile_billing_field_address2_title',1,'address 2','both','2009-05-25 10:15:24'),
('user_profile_billing_field_address3_title',1,'address 3','both','2009-05-25 10:15:24'),
('user_profile_billing_field_telno_title',1,'phone','both','2009-05-25 10:15:24'),
('user_profile_billing_field_alttelno_title',1,'alternative phone','both','2009-05-25 10:15:24'),
('user_profile_billing_field_faxno_title',1,'fax','both','2009-05-25 10:15:24'),
('user_profile_billing_field_customerlangpref_title',1,'language','both','2009-05-25 10:15:24'),
('user_profile_domain_update_successful',1,'Domain registration information has been saved successfully!','both','2009-05-25 10:55:27'),
('user_profile_billing_update_successful',1,'Billing profile information has been saved successfully!','both','2009-05-25 10:57:38'),
('admin_newsletter_email_history_tpl_type_custom',1,'custom','admin','2009-05-25 09:33:03'),
('admin_domain_settings_customerlangpref',1,'Language:','admin','2009-05-25 11:36:49'),
('admin_member_pages_msg_confirm_load',1,'The changes cannot be saved. Are you sure you want to proceed?','admin','2009-05-25 01:18:51'),
('admin_member_pages_enabled_ttip',1,'Display the field on a web page','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_name_option_ttip',1,'Username input field that will be used during domain registration. When disabled, the username will be generated based on the user first and last name specified in the system. ','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_company_option_ttip',1,'Company input field - will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_country_option_ttip',1,'Country input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_state_option_ttip',1,'State input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_city_option_ttip',1,'City input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_zip_option_ttip',1,'Zip code input field that will be used during domain registration. The format must be: zip_code','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_address1_option_ttip',1,'Address input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_address2_option_ttip',1,'Additional address input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_address3_option_ttip',1,'Additional address input field that will be used during domain registration','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_telno_option_ttip',1,'Phone number input field that will be used during domain registration. The format must be: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_alttelno_option_ttip',1,'Alternative phone number input field that will be used during domain registration. For a correct system functionality, the format must be this: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_faxno_option_ttip',1,'Fax input field that will be used during domain registration. For a correct system functionality, the format must be this: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_domain_customerlangpref_option_ttip',1,'Language input field that will be used during domain registration. When disabled, current user language (used during registration) or English (if the user language isn\'t supported) will be used. ','admin','2009-05-25 01:33:38'),
('admin_member_pages_presets_title',1,'Load default settings','admin','2009-05-25 01:33:38'),
('admin_member_pages_profile_domain_forceuse_option_ttip',1,'If checked, the option allows the user to select between automatic system use of the input data and its pre-editing before each use. If disabled, each time the system will ask for data pre-editing before its each use. ','admin','2009-05-25 02:53:58'),
('admin_member_pages_profile_billing_name_option_ttip',1,'Username input field that will be used when paying through a payment system. When disabled, the username will be generated based on the user first and last name specified in the system. ','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_company_option_ttip',1,'Company input field - will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_country_option_ttip',1,'Country input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_state_option_ttip',1,'State input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_city_option_ttip',1,'City input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_zip_option_ttip',1,'Zip code input field that will be used when paying through a payment system. The format must be: zip_code','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_address1_option_ttip',1,'Address input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_address2_option_ttip',1,'Additional address input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_address3_option_ttip',1,'Additional address input field that will be used when paying through a payment system','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_telno_option_ttip',1,'Phone number input field that will be used when paying through a payment system. The format must be: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_alttelno_option_ttip',1,'Alternative phone number input field that will be used when paying through a payment system. For a correct system functionality, the format must be this: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_faxno_option_ttip',1,'Fax input field that will be used when paying through a payment system. For a correct system functionality, the format must be this: phone','admin','2009-05-25 01:33:37'),
('admin_member_pages_profile_billing_customerlangpref_option_ttip',1,'Language input field that will be used when paying through a payment system. When disabled, current user language (used during registration) or English (if the user language isn\'t supported) will be used. ','admin','2009-05-25 01:33:38'),
('admin_member_pages_profile_billing_forceuse_option_ttip',1,'If checked, the option allows the user to select between automatic system use of the input data and its pre-editing before each use. If disabled, each time the system will ask for data pre-editing before its each use. ','admin','2009-05-25 02:53:58'),
('user_profile_domain_msg_successful',1,'Domain registration information has been saved successfully!','user','2009-05-25 03:02:11'),
('user_profile_domain_err_not_saved',1,'Domain registration information has not been saved!','user','2009-05-25 03:04:08'),
('user_profile_billing_msg_successful',1,'Billing account information has been saved successfully!','user','2009-05-25 03:02:11'),
('user_profile_billing_err_not_saved',1,'Billing account information has not been saved!','user','2009-05-25 03:04:08'),
('admin_member_pages_profile_billing_header_comment',1,'Here you can change display options for the user billing info page.','admin','2009-05-25 03:21:43'),
('admin_log_domain_connection_test',1,'Test connection with domain registrar','admin','2009-05-27 01:18:01'),
('admin_log_domain_directi_not_init',1,'Registration domain - initiation API LogixBoxes','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_available',1,'Registration domain - name domain available','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_get_customid',1,'Registration domain - get customid','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_regist',1,'Registration domain - completed','admin','2009-05-27 01:18:01'),
('admin_log_domain_settings_modify',1,'Registration domain - settings modify','admin','2009-05-27 01:18:01'),
('admin_log_domain_signup_not_reg_info',1,'Registration domain - sign up customer','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_add_contacts',1,'Registration domain - add contacts for customer','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_ns',1,'Registration domain - read information NameServers for domain','admin','2009-05-27 01:18:01'),
('admin_log_domain_grant_not_valid_info',1,'Registration domain - validation information for domain','admin','2009-05-27 01:18:01'),
('admin_log_domain_subscription_started',1,'Registration domain - subscription started','admin','2009-05-27 01:18:01'),
('admin_level_list_edit',1,'Edit level','admin','2009-05-27 02:37:07'),
('admin_domain_settings_tested_successfully',1,'Connection was successfully established.','admin','2009-05-27 04:09:58'),
('admin_domain_settings_tested_fail',1,'Connection could not be established, please check your settings ','admin','2009-05-27 04:09:58'),
('admin_domain_settings_tested_auth_fail',1,'Resellers authentication failed, please check your settings.','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_001',1,'User Name your resellers account (as email)','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_002',1,'Password your resellers account','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_003',1,'Parent id your resellers account','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_004',1,'Language preference','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_005',1,'HTTP or HTTPS service URL','admin','2009-05-27 04:09:58'),
('admin_domain_ttip_006',1,'DEMO or LIVE service URL','admin','2009-05-27 04:09:58'),
('admin_log_host_get_nameservers',1,'Get nameservers from hosting','admin','2009-05-28 05:23:53'),
('admin_log_load_hosted_method',1,'Load hosted method','admin','2009-05-28 05:23:53'),
('admin_log_domain_subscription_free',1,'Registration domain - free hosting without registration domain','admin','2009-05-28 11:26:29'),
('user_cart_hosted_msg_domain_not_available',1,'Domain name is not available','user','2009-05-29 02:37:20'),
('admin_member_control_account_panel_member_billing_info_title_autofill',1,'If left empty, the fields will be pre-filled with the values from corresponding fields of the your profile','admin','2009-05-28 10:44:24'),
('admin_member_control_account_panel_member_billing_info_button_autofill',1,'Autofill','admin','2009-05-28 10:44:24'),
('admin_member_control_account_panel_member_domain_info_title_autofill',1,'If left empty, the fields will be pre-filled with the values from corresponding fields of the your profile','admin','2009-05-28 10:45:11'),
('admin_member_control_account_panel_member_domain_info_button_autofill',1,'Autofill','admin','2009-05-28 10:45:11'),
('user_profile_domain_update_autofill_title',1,'If left empty, the fields will be pre-filled with the values from corresponding fields of the your profile','user','2009-05-28 10:46:01'),
('user_profile_domain_update_button_autofill',1,'Autofill','user','2009-05-28 10:46:01'),
('user_profile_billing_update_autofill_title',1,'If left empty, the fields will be pre-filled with the values from corresponding fields of the your profile','user','2009-05-28 10:46:41'),
('user_profile_billing_update_button_autofill',1,'Autofill','user','2009-05-28 10:46:41'),
('admin_domain_settings_error_service_username',1,'Username field error. Username field type must be Email','admin','2009-05-28 08:45:13'),
('admin_domain_settings_error_service_password',1,'Password field error. Password field is your password which is from 8 to 15 characters long','admin','2009-05-28 08:45:13'),
('admin_domain_settings_error_service_parentid',1,'Parent id field must be a number','admin','2009-05-28 08:45:13'),
('user_profile_domain_update_asdefault',1,'Save as default:','user','2009-05-29 12:04:32'),
('user_profile_domain_update_button_apply',1,'Apply','user','2009-05-29 12:04:33'),
('admin_log_domain_user_deleted',1,'Registration domain - customer domain deleted','admin','2009-05-31 07:12:38'),
('admin_member_control_account_panel_payments_add_error_additional_profile_invalid',1,'Information in domain registration profile this member is not valid','admin','2009-06-01 03:43:47'),
('admin_manage_emsg_news_emp',1,'The news status cannot be changed. Please fill out the required field in Edit','admin','2009-06-10 12:04:47'),
('admin_global_setup_field_label_personal_login_redirect_flag',1,'Individual redirection','admin','2009-06-16 11:31:53'),
('admin_member_control_account_panel_member_info_field_login_redirect',1,'Redirect after login:','admin','2009-06-16 01:12:40'),
('admin_member_control_add_member_field_login_redirect_tooltip',1,'Once logged in, the user will be redirected to the URL you specify in this field. If you leave the field empty, users will be redirected to the URL specified in the \'Redirect after login\' field under Global Setup configurations','admin','2009-06-16 01:12:40'),
('user_registration_err_login_redirect',1,'\"redirect after login\" field must be a valid URL, contain no more than 2048 characters or be empty.','user','2009-06-16 01:38:47'),
('admin_news_published',1,'Publish/unpublish news','admin','2009-06-29 08:52:38'),
('admin_news_special_news',1,'Publish in special news/unpublish in special news','admin','2009-06-29 08:52:38'),
('admin_news_members_only',1,'Publish for members only/publish for all','admin','2009-06-29 08:52:38'),
('admin_manage_emsg_pages_emp',1,'The pages status cannot be changed. Please fill out the required field in Edit','admin','2009-06-29 09:32:48'),
('admin_member_settings_autosubscribe_free_products',1,'Autimatic subscription for  free  products','admin','2009-06-29 11:54:00'),
('admin_member_settings_autosubscribe_free_products_ttip',1,'This checkbox includes  the automatic subscription to available free products for registered users. ATTENTION! The current option requires turned on and configured Task Scheduler (CRON). The process of subscription takes certain time, therefore products will become available for users not simultaneously.','admin','2009-06-29 11:54:00'),
('admin_msg_er_000111',1,'Value mast be integer, greater 1 and lower 32768','admin','2009-06-29 12:28:20'),
('admin_msg_er_000112',1,'Value mast be integer, greater 2 and lower 32768','admin','2009-06-29 12:28:20'),
('admin_global_setup_msg_change_baseurl',1,'The change of the Main URL can diable NS2. Are you sure you want to continue?','admin','2009-06-30 09:21:50'),
('admin_global_setup_msg_change_path',1,'The change of the Absolute path can diable NS2. Are you sure you want to continue?','admin','2009-06-30 09:21:50'),
('admin_newsletter_email_templates_msg_sended_ok',1,'Message was sent successfully.','admin','2009-07-02 02:33:17'),
('user_registration_err_denied_domain',1,'Error: Denied email domain!','user','2009-07-03 10:41:45'),
('admin_email_history_send_msg_er_time_limit',1,'The frequency of sending mail is limited in order to ensure security. Please try again later.','admin','2009-07-07 07:27:44'),
('admin_config_ban_ip_error_ip_validation_fail',1,'IP must be have the following formats 123.123.123.123 OR 123.123.123.* OR 123.123.123.123 - 255.255.255.255','admin','2009-07-07 08:41:03'),
('admin_license_error_unavailable_connect_to_the_server',1,'Unavailable connect to the server!','admin','2009-07-16 02:22:43'),
('admin_license_error_unknown_license_key',1,'Unknown license key!','admin','2009-07-17 08:31:32'),
('admin_license_error_invalid_product',1,'Invalid product','admin','2009-07-17 08:31:32'),
('admin_license_error_this_license_is_disabled',1,'This license is disabled','admin','2009-07-17 08:31:32'),
('admin_license_error_domain_check_failed',1,'Domain check failed','admin','2009-07-17 08:31:32'),
('admin_license_error_ip_check_failed',1,'IP check failed','admin','2009-07-17 08:31:32'),
('admin_license_error_unrecognized_error',1,'Unrecognized error','admin','2009-07-17 08:31:32'),
('admin_input_license_key',1,'Enter license key','admin','2009-07-17 08:31:32'),
('admin_dialog_cheked_license',1,'Licence Verification','admin','2009-07-17 11:22:23'),
('admin_dialog_key_length_license',1,'Licence key must contain 32 characters (only Latin letters and numbers)','admin','2009-07-17 11:22:23'),
('admin_email_history_send_msg_er_demo_limit',1,'Sorry but in demo mode only system messages are sent','admin','2009-07-29 10:13:21');
/*!40000 ALTER TABLE `db_prefix_Interface_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Ip_access_log`
--

LOCK TABLES `db_prefix_Ip_access_log` WRITE;
/*!40000 ALTER TABLE `db_prefix_Ip_access_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Ip_access_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Language_data`
--

LOCK TABLES `db_prefix_Language_data` WRITE;
/*!40000 ALTER TABLE `db_prefix_Language_data` DISABLE KEYS */;
INSERT INTO `db_prefix_Language_data` VALUES (-1,9,'Terms of use','',1,NULL),
(-1,13,'Remind password (##site_name## system message)','Hello ##user_name##,\n somebody has generated this \'remind password\' request. If that was you - please, follow this link ##user_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',24,NULL),
(-1,2,'Remind password (##site_name## system message)','Hello ##user_name##,\n somebody has generated this \'remind password\' request. If that was you - please, follow this link ##user_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',24,NULL),
(-1,2,'Payment error notification (##site_name## system message)','Hello ##user_name##,\n we have not received payment on product \'##product_name##\' because of the payment error (Subscription ID: ##subscription_id## ; Transaction ID: ##transaction_id##).\nPlease contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',23,NULL),
(-1,13,'Payment error notification (##site_name## system message)','Hello ##user_name##,\n we have not received payment on product \'##product_name##\' because of the payment error (Subscription ID: ##subscription_id## ; Transaction ID: ##transaction_id##).\nPlease contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',23,NULL),
(-1,2,'Payment notification (##site_name## system message)','Hello ##user_name##,\n we have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',22,NULL),
(-1,13,'Payment notification (##site_name## system message)','Hello ##user_name##,\n we have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',22,NULL),
(-1,2,'Product subscription is almost expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your subscription on product \'##expired_product_name##\' is almost expired (expiration date is: ##product_expiration_date##).\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',21,NULL),
(-1,13,'Product subscription is almost expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your subscription on product \'##expired_product_name##\' is almost expired (expiration date is: ##product_expiration_date##).\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',21,NULL),
(-1,2,'Product subscription is expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your subscription on product \'##expired_product_name##\' is expired at ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',20,NULL),
(-1,13,'Product subscription is expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your subscription on product \'##expired_product_name##\' is expired at ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',20,NULL),
(-1,2,'Your account is expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your account has expired at ##user_expire_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',19,NULL),
(-1,13,'Your account is expired (##site_name## system message)','Hello ##user_name##,\n we inform you that your account has expired at ##user_expire_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',19,NULL),
(-1,2,'Your account status was changed (##site_name## system message)','Hello ##user_name##,\n your member account status was changed to \'##user_account_status##\'.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',18,NULL),
(-1,13,'Your account status was changed (##site_name## system message)','Hello ##user_name##,\n your member account status was changed to \'##user_account_status##\'.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',18,NULL),
(-1,2,'Account activation request (##site_name## system message)','Hello ##user_name##,\n your member account was successfully registered and needs activation. In order to activate this account please proceed to this link ##user_activation_link##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',17,NULL),
(-1,13,'Account activation request (##site_name## system message)','Hello ##user_name##,\n your member account was successfully registered and needs activation. In order to activate this account please proceed to this link ##user_activation_link##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',17,NULL),
(-1,2,'Your account was successfully registered (##site_name## system message)','Hello ##user_name##,\n your account was successfully registered.\nHere is your account info to log in:\nlogin: ##user_login##\npassword: ##user_password##\n\nPlease store this information. You may log in to your member account here ##site_base_url##user/login\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',16,NULL),
(-1,13,'Your account was successfully registered (##site_name## system message)','Hello ##user_name##,\n your account was successfully registered.\nHere is your account info to log in:\nlogin: ##user_login##\npassword: ##user_password##\n\nPlease store this information. You may log in to your member account here ##site_base_url##user/login\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',16,NULL),
(-1,13,'Your member profile was changed (##site_name## system message)','Hello ##user_name##,\n we inform you that your profile information was changed.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',14,NULL),
(-1,2,'Your profile was changed (##site_name## system message)','Hello ##user_name##,\n your password to member account was changed.\n New password is ##user_new_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',15,NULL),
(-1,13,'Your profile was changed (##site_name## system message)','Hello ##user_name##,\n your password to member account was changed.\n New password is ##user_new_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',15,NULL),
(-1,2,'New member account is registered (##site_name## system message)','Hello ##admin_login##,\n new member account (login: ##user_login##) was successfully registered\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',13,NULL),
(-1,13,'New member account is registered (##site_name## system message)','Hello ##admin_login##,\n new member account (login: ##user_login##) was successfully registered\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',13,NULL),
(-1,2,'Your member profile was changed (##site_name## system message)','Hello ##user_name##,\n we inform you that your profile information was changed.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',14,NULL),
(-1,2,'Product subscription is ended/expired (##site_name## system message)','Hello ##admin_login##,\n subscription of ##user_login## on product \'##expired_product_name##\' is closed (product expiration date is ##product_expiration_date##).\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',12,NULL),
(-1,13,'Product subscription is ended/expired (##site_name## system message)','Hello ##admin_login##,\n subscription of ##user_login## on product \'##expired_product_name##\' is closed (product expiration date is ##product_expiration_date##).\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',12,NULL),
(-1,2,'Product subscription is started (##site_name## system message)','Hello ##admin_login##,\n subscription of ##user_login## on product \'##product_name##\' is started.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',11,NULL),
(-1,13,'Product subscription is started (##site_name## system message)','Hello ##admin_login##,\n subscription of ##user_login## on product \'##product_name##\' is started.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',11,NULL),
(-1,2,'Payment error notification (##site_name## system message)','Hello ##admin_login##,\n we have not received payment on product \'##product_name##\' because of the payment error.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',10,NULL),
(-1,13,'Payment error notification (##site_name## system message)','Hello ##admin_login##,\n we have not received payment on product \'##product_name##\' because of the payment error.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',10,NULL),
(-1,2,'Payment notification (##site_name## system message)','Hello ##admin_login##,\n we have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',9,NULL),
(-1,13,'Payment notification (##site_name## system message)','Hello ##admin_login##,\n we have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',9,NULL),
(-1,2,'Access level was changed (##site_name## system message)','Hello ##admin_login##,\n we inform you, that access level ##access_level## was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',8,NULL),
(-1,13,'Access level was changed (##site_name## system message)','Hello ##admin_login##,\n we inform you, that access level ##access_level## was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',8,NULL),
(-1,2,'Administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\n we inform you, that administrator account ##deleted_admin_login## (with access level:##deleted_admin_level##) was deleted by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',7,NULL),
(-1,13,'Administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\n we inform you, that administrator account ##deleted_admin_login## (with access level:##deleted_admin_level##) was deleted by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',7,NULL),
(-1,13,'Administrator account was changed (##site_name## system message)','Hello ##admin_login##,\n we inform you, that administrator account ##changed_admin_login## (with access level:##changed_admin_level##) was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',6,NULL),
(-1,2,'Administrator account was changed (##site_name## system message)','Hello ##admin_login##,\n we inform you, that administrator account ##changed_admin_login## (with access level:##changed_admin_level##) was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',6,NULL),
(-1,2,'Administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\n we inform you, that new administrator account ##created_admin_login## (with access level:##created_admin_level##) was created by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',5,NULL),
(-1,13,'Administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\n we inform you, that new administrator account ##created_admin_login## (with access level:##created_admin_level##) was created by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',5,NULL),
(-1,13,'Remind password (##site_name## system message)','Hello ##admin_login##,\n somebody has generated this \'remind password\' request. If that was you - please, follow this link ##admin_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',4,NULL),
(-1,2,'Remind password (##site_name## system message)','Hello ##admin_login##,\n somebody has generated this \'remind password\' request. If that was you - please, follow this link ##admin_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',4,NULL),
(-1,13,'Your administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\n your administrator account was deleted\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',3,NULL),
(-1,2,'Your administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\n your administrator account was deleted\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',3,NULL),
(-1,2,'Your administrator account was changed (##site_name## system message)','Hello ##admin_login##,\n your administrator account was changed.\n Your login:##admin_login##\n Your password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',2,NULL),
(-1,2,'Your administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\n your admin account was successfuly registered.\n Your login:##admin_login##\n Your password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',1,NULL),
(-1,13,'Your administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\n your admin account was successfuly registered.\n Your login:##admin_login##\n Your password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',1,NULL),
(-1,13,'Your administrator account was changed (##site_name## system message)','Hello ##admin_login##,\n your administrator account was changed.\n Your login:##admin_login##\n Your password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',2,NULL),
(1,3,'Demo Product Group 1','Description of the demo product group. This group was created for demonstration purposes only.',1,NULL),
(1,3,'Demo Product Group 2','Description of the demo product group. This group was created for demonstration purposes only.',2,NULL),
(1,4,'Free Demo Product 1','Description of the free demo product. It protects the directory named \"protected directory 1\". This product was created for demonstration purposes only.',1,NULL),
(1,4,'Demo Product 2','Description of the demo product. It protects the directory named \"protected directory 2\". This product was created for demonstration purposes only.',2,NULL),
(1,4,'Demo Product 3 (with discount)','Description of the demo product with discount on a price. It protects the directory named \"protected directory 2\". This product was created for demonstration purposes only.',3,NULL),
(1,4,'Demo Product 4 (with discount and free trial period)','Description of the demo product with discount on a price and free trial period. It protects the directory named \"protected directory 3\". This product was created for demonstration purposes only.',4,NULL),
(1,4,'Demo Product 5 (with discount and trial period for a fee)','Description of the demo product with discount on a price and trial period for a fee. The subscription to this product may be recurring. It protects the directory named \"protected directory 3\". This product was created for demonstration purposes only.',5,NULL),
(1,6,'DEMO news 1','Brief news description',1,'Here is content of news. It was created for demonstration purposes only.'),
(1,10,'Policy Violation','If an account violates our policy, the account will be suspended. This account may have violated our policy.',1,NULL),
(1,10,'Requested Suspension','If a user requests that his/her site be suspended, it will be suspended. This account\'s owner may have requested the account be suspended for some period of time.',2,NULL),
(1,10,'Overdue account','The account may be temporary disabled due to non-payment.',3,NULL),
(1,5,'Demo coupons','This coupon was created for demonstration purposes only. You can use it for DEMO products only.',1,NULL),
(1,11,'Additional information 1','This additional field was created for demonstration purposes only.',1,NULL),
(1,11,'Additional information 2','This additional field was created for demonstration purposes only.',2,NULL),
(1,6,'DEMO news 2','Brief news description',2,'Here is content of news. It was created for demonstration purposes only.'),
(1,9,'Terms of Service','<h1 class=\"header_subject\">Terms of service</h1>\n<p>Please read through and understand our Terms of Service below.</p>\n<h3>1.&nbsp;Acceptance of Terms</h3>\n<p>Welcome to Prima Members - all-on-one password protection and membership management system (the \'Service\')! This user agreement (the \'Agreement\') covers your use of the Prima Members service provided to you by Prima DG Ltd. The agreement is subject to the terms and conditions set forth herein (collectively, the \'Terms of Service\' or \'TOS\') and can be updated or modified by us from time to time without notice to.</p>\n<p>The Service is available only to registered and approved by the administrator individuals. Since we will be notifying you via your email address, you are certifying in this Agreement that you have the right to use your email address.</p>\n<p>By using the Service, you agree to be legally bound and abide by these Terms of Service. You agree to use the Service in a manner consistent with all applicable laws and regulations and in accordance with these Terms of Service.</p>\n<h3>2.&nbsp;Registration Obligations</h3>\n<p>In consideration of your use of the Service, you agree to:</p>\n<p><ul style=\"color: black;\">\n    <li>provide true, accurate, current and complete information about yourself as prompted by the Service\'s registration form (such information being the \'Registration Data\') and </li>\n    <li>maintain and promptly update the Registration Data to keep it true, accurate, current and complete. </li>\n</ul></p>\n<p>If you provide any information that is untrue, inaccurate, not current or incomplete, or the Prima Members administrator(s) has reasonable grounds to suspect that such information is untrue, inaccurate, not current or incomplete, the administrator(s) has the right to suspend or terminate your account and refuse any and all current or future use of the Service. In addition, any fraudulent, abusive, or otherwise illegal activity may be grounds for termination of your account, at the Service administration sole discretion, and you may be reported to appropriate law-enforcement agencies.</p>\n<h3>3.&nbsp;Account, Password and Security</h3>\n<p>You will receive your password and account details upon completing the Service\'s signup process. You are responsible for maintaining the confidentiality of the password and account, and are fully responsible for all activities that occur under your password or account.</p>\n<p>You agree to:<br/>\n<ul style=\"color: black;\">\n    <li>immediately notify the Prima Members administration of any unauthorized use of your password or account or any other breach of security, and </li>\n    <li>ensure that you exit from your account at the end of each session. The system administration cannot and will not be liable for any loss or damage arising from your failure to comply with this Section.</li>\n</ul>\n</p>\n<h3>4.&nbsp;No Resale of Service</h3>\n<p>You may use the Service for personal and business purposes. However, you may not sell, lease or resell the services to or on behalf of any other party. In addition, you may not use, or permit to be used, the Service, in violation of any applicable law, regulation or policy, or in violation of any third parties\' rights.</p>\n<h3>5.&nbsp;Termination of Service</h3>\n<p>This Agreement can be terminated either by you or the Prima Members administration at any time and for any reason. Until the termination, the Agreement remains in full force. In addition, you may not assign this Agreement to anyone else.</p>\n<p>You agree that the Service administration, in its sole discretion, may terminate your password, account (or any part thereof) or use of the Service, and remove and discard any information, data, text, software, music, sound, photographs, graphics, video, message or other materials (collectively termed as \'Content\'), within the Service, for any reason, including, without limitation, for lack of use or if the Service administration believes that you have violated or acted inconsistently with the letter or spirit of the TOS. The Prima Members administrator(s) may also in its sole discretion and at any time discontinue providing the Service, or any part thereof, with or without notice. You agree that any termination of your access to the Service under any provision of this TOS may be effected without prior notice, and acknowledge and agree that the system administration may immediately deactivate or delete your account and all related information and files in your account and/or ban any further access to such files or the Service.  Further, you agree that the Service administration shall not be liable to you or any third-party for any termination of your access to the Service.</p>\n<h3>6.&nbsp;Modifications to Service</h3>\n<p>The Service administration reserves the right at any time to modify or discontinue, temporarily, or permanently, the Service (or any part thereof) with or without notice. You agree that the administration shall not be liable to you or to any third party for any modification, suspension or discontinuance of the Service.</p>\n<h3>7.&nbsp;Links</h3>\n<p>The Service may provide, or third parties may provide, links to other web sites or resources. Because Prima DG, Ltd has no control over such sites and resources, you acknowledge and agree that Prima DG Ltd is not responsible for the availability of such external sites or resources, and does not endorse and is not responsible or liable for any Content, advertising, products, or other materials on or available from such sites or resources. You further acknowledge and agree that Prima DG shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance of any such Content, goods or services available on or through any such site or resource.</p>\n<h3>8.&nbsp;Ownership</h3>\n<p>Prima DG Ltd has and shall retain all right, title and interest in and to any software or technology used in providing the Service, and all intellectual property rights therein.</p>\n<h3>9.&nbsp;Privacy Policy</h3>\n<p>While Prima DG and the Prima Members administration will not knowingly divulge your email address or any other personal information to outside parties, you agree that Prima DG will not be liable for acquisition or use of your information by any third party without Prima DG\'s knowledge or consent. In turn, you agree that Prima DG will not be liable for the acquisition or use of personal information about you that is not under our control. By using the Service, you agree to allow Prima DG and the Service administrators to use your comments, suggestions, writings or remarks without compensation or any other rights or interests.</p>\n<h3>10.&nbsp;DISCLAIMERS</h3>\n<p>THE SERVICE IS PROVIDED \'AS IS\'. NO WARRANTIES ARE PROVIDED, WHETHER WRITTEN OR ORAL, EXPRESSED OR IMPLIED, WITH RESPECT TO THE SERVICE, THE AVAILABILITY OR USE OF THE SERVICE, ANY INFORMATION OR PRODUCTS OBTAINED OR DERIVED THROUGH USE OF THE SERVICE, THE TRANSMISSION, STORAGE OR USE OF YOUR INFORMATION, OR ANY PRODUCTS OR SERVICES USED IN CONJUNCTION WITH THE SERVICE. Prima DG AND ITS SUPPLIERS SPECIFICALLY DISCLAIM THE IMPLIED WARRANTIES OF MERCHANTABILITY, NON-INFRINGEMENT AND FITNESS FOR A PARTICULAR PURPOSE WITH RESPECT TO THE SERVICE, AVAILABILITY AND USE OF THE SERVICE, ANY INFORMATION OR PRODUCTS OBTAINED OR DERIVED THROUGH USE OF THE SERVICE, AND ANY TRANSMISSION OR USE OF YOUR INFORMATION.</p>\n<p>THE AVAILABILITY OF THE SERVICE DEPENDS ON MANY FACTORS, INCLUDING YOUR CONNECTION TO THE INTERNET, THE AVAILABILITY OF THE SERVICE, ETC. Prima DG MAKES NO WARRANTY THAT:</p>\n<p>\n<ol style=\"color: black;\">\n    <li>THE SERVICE WILL MEET YOUR REQUIREMENTS,</li>\n    <li>THE SERVICE WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE,</li>\n    <li>THE RESULTS THAT MAY BE OBTAINED FROM THE USE OF THE SERVICE WILL BE ACCURATE OF RELIABLE,</li>\n    <li>THE QUALITY OF ANY PRODUCTS, SERVICES, INFORMATION, OR OTHER MATERIAL PURCHASED OR OBTAINED BY YOU THROUGH THE SERVICE WILL MEET YOUR EXPECTATIONS, AND</li>\n    <li>ANY ERRORS IN THE SOFTWARE WILL BE CORRECTED.</li>\n</ol>\n</p>\n<p>ANY MATERIAL DOWNLOADED OR OTHERWISE OBTAINED THROUGH THE USE OF THE SERVICE IS DONE AT YOUR OWN DISCRETION AND RISK THAT YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM OR LOSS OF DATA THAT RESULTS FROM THE DOWNLOAD OF ANY SUCH MATERIAL.</p>\n<p>NO ADVICE OR INFORMATION OBTAINED BY YOU FROM Prima DG OR THROUGH OR FROM THIS SITE SHALL CREATE ANY WARRANTY NOT EXPRESSLY STATED HEREIN.</p>\n<h3>11.&nbsp;LIMITATION OF LIABILITY</h3>\n<p>TO THE FULLEST EXTENT PERMITTED BY APPLICABLE LAW, IN NO EVENT WILL Prima DG, ITS SUPPLIERS, LICENSEES OR AFFILIATES BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY OR CONSEQUENTIAL DAMAGES THAT RESULT OR ARISE OUT OF THE SERVICE, THE AVAILABILITY OR USE OF THE SERVICE, ANY INFORMATION OR PRODUCTS OBTAINED OR DERIVED THROUGH THE USE OF THE SERVICE, ANY OTHER PRODUCTS OR SERVICES USED IN CONJUNCTION WITH THE SERVICE, OR ANY TRANSMISSION OR USE OF YOUR INFORMATION.</p>\n<p>IF YOU ARE DISSATISFIED WITH THE SERVICE, OR WITH ANY OF THE POINTS IN THE TOS, YOUR ONLY REMEDY IS TO DISCONTINUE USING THE SERVICE.</p>\n<p>SOME JURISDICTIONS DO NOT ALLOW THE LIMITATION OR EXCLUSION OF INCIDENTAL, CONSEQUENTIAL OR OTHER TYPES OF DAMAGES, SO SOME OF THE ABOVE LIMITATIONS MAY NOT APPLY TO YOU.</p>\n<h3>12.&nbsp;Indemnification</h3>\n<p>You agree to indemnify and hold Prima DG Ltd, its affiliates, partners, and its employees harmless from any claim or demand, including reasonable attorneys fees, made by any third party due to or arising out of content User submits, posts to or transmits through the Service, User use of the Service, its connection to the Service, Users violation of the terms of use, or Users violation of any rights of another person or entity, if he is no consumer.</p>\n<h3>13.&nbsp;Notice</h3>\n<p>Notices to you may be made via either email or regular mail. The Service may also provide notices of changes to the TOS or other matters by displaying notices or links to notices to you generally on the Service. By continuing to use the Service after changes to the TOS have been posted, you agree and accept the changes.</p>\n<h3>14.&nbsp;Trademark Information</h3>\n<p>Copyrights and Prima Members logotypes are the trademarks of Prima DG, Ltd. Certain of the names, logos, and other materials displayed in the Service constitute trademarks or intellectual property of Prima DG or other entities. You are not authorized to use any such marks. Ownership of such trademarks and other intellectual property remains with Prima DG or those other entities.</p>',1,'Terms of Service, Agreement'),
(1,2,'Your member profile was changed (##site_name## system message)','Hello ##user_name##,\nwe inform you that your profile information was changed.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',14,NULL),
(1,13,'Your member profile was changed (##site_name## system message)','Hello ##user_name##,\n we inform you that your profile information was changed.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',14,NULL),
(1,2,'Your profile was changed (##site_name## system message)','Hello ##user_name##,\nyour password to member account was changed.\nNew password is ##user_new_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',15,NULL),
(1,13,'Your profile was changed (##site_name## system message)','Hello ##user_name##,\nyour password to member account was changed.\nNew password is ##user_new_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',15,NULL),
(1,2,'Your account was successfully registered (##site_name## system message)','Hello ##user_name##,\nyour account was successfully registered.\nHere is your account information to log in:\nlogin: ##user_login##\npassword: ##user_password##\n\nPlease store this information. You may log in to your member account here ##site_base_url##user/login\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',16,NULL),
(1,13,'Your account was successfully registered (##site_name## system message)','Hello ##user_name##,\nyour account was successfully registered.\nHere is your account information to log in:\nlogin: ##user_login##\npassword: ##user_password##\n\nPlease store this information. You may log in to your member account here ##site_base_url##user/login\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',16,NULL),
(1,2,'Account activation request (##site_name## system message)','Hello ##user_name##,\nyour member account was successfully registered and needs activation. In order to activate this account please proceed to this link ##user_activation_link##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',17,NULL),
(1,13,'Account activation request (##site_name## system message)','Hello ##user_name##,\nyour member account was successfully registered and needs activation. In order to activate this account please proceed to this link ##user_activation_link##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',17,NULL),
(1,2,'Your account status was changed (##site_name## system message)','Hello ##user_name##,\nyour member account status was changed to \'##user_account_status##\'.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',18,NULL),
(1,13,'Your account status was changed (##site_name## system message)','Hello ##user_name##,\nyour member account status was changed to \'##user_account_status##\'.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',18,NULL),
(1,2,'Your account is expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your account has expired at ##user_expire_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',19,NULL),
(1,13,'Your account is expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your account has expired at ##user_expire_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',19,NULL),
(1,2,'Product subscription is expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your subscription on product \'##expired_product_name##\' is expired at ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',20,NULL),
(1,13,'Product subscription is expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your subscription on product \'##expired_product_name##\' is expired at ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',20,NULL),
(1,2,'Product subscription is almost expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your subscription on product \'##expired_product_name##\' is almost expired (expiration date is: ##product_expiration_date##).\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',21,NULL),
(1,13,'Product subscription is almost expired (##site_name## system message)','Hello ##user_name##,\nwe inform you that your subscription on product \'##expired_product_name##\' is almost expired (expiration date is: ##product_expiration_date##).\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',21,NULL),
(1,2,'Payment notification (##site_name## system message)','Hello ##user_name##,\nwe have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',22,NULL),
(1,13,'Payment notification (##site_name## system message)','Hello ##user_name##,\nwe have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nIf you have any questions, please contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',22,NULL),
(1,2,'Payment error notification (##site_name## system message)','Hello ##user_name##,\nwe have not received payment on product \'##product_name##\' because of the payment error (Subscription ID: ##subscription_id## ; Transaction ID: ##transaction_id##).\nPlease contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',23,NULL),
(1,13,'Payment error notification (##site_name## system message)','Hello ##user_name##,\nwe have not received payment on product \'##product_name##\' because of the payment error (Subscription ID: ##subscription_id## ; Transaction ID: ##transaction_id##).\nPlease contact our administrator ##site_admin_email##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',23,NULL),
(1,2,'Remind password (##site_name## system message)','Hello ##user_name##,\nsomebody has generated this \'remind password\' request. If that was you - please, follow this link ##user_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',24,NULL),
(1,13,'Remind password (##site_name## system message)','Hello ##user_name##,\nsomebody has generated this \'remind password\' request. If that was you - please, follow this link ##user_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',24,NULL),
(1,2,'Your administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\nyour admin account was successfuly registered.\n\nYour login:##admin_login##\nYour password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',1,NULL),
(1,13,'Your administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\nyour admin account was successfuly registered.\n\nYour login:##admin_login##\nYour password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',1,NULL),
(1,2,'Your administrator account was changed (##site_name## system message)','Hello ##admin_login##,\nyour administrator account was changed.\n\nYour login:##admin_login##\nYour password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',2,NULL),
(1,13,'Your administrator account was changed (##site_name## system message)','Hello ##admin_login##,\nyour administrator account was changed.\n\nYour login:##admin_login##\nYour password:##admin_password##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',2,NULL),
(1,2,'Your administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\nyour administrator account was deleted\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',3,NULL),
(1,13,'Your administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\nyour administrator account was deleted\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',3,NULL),
(1,2,'Remind password (##site_name## system message)','Hello ##admin_login##,\nsomebody has generated this \'remind password\' request. If that was you - please, follow this link ##admin_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',4,NULL),
(1,13,'Remind password (##site_name## system message)','Hello ##admin_login##,\nsomebody has generated this \'remind password\' request. If that was you - please, follow this link ##admin_remind_password_link## and new password will be automaticaly send to your email, otherwise ignore \'remind password\' request.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',4,NULL),
(1,2,'Administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that new administrator account ##created_admin_login## (with access level:##created_admin_level##) was created by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',5,NULL),
(1,13,'Administrator account was successfuly registered (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that new administrator account ##created_admin_login## (with access level:##created_admin_level##) was created by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',5,NULL),
(1,2,'Administrator account was changed (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that administrator account ##changed_admin_login## (with access level:##changed_admin_level##) was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',6,NULL),
(1,13,'Administrator account was changed (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that administrator account ##changed_admin_login## (with access level:##changed_admin_level##) was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',6,NULL),
(1,2,'Administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that administrator account ##deleted_admin_login## (with access level:##deleted_admin_level##) was deleted by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',7,NULL),
(1,13,'Administrator account was deleted (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that administrator account ##deleted_admin_login## (with access level:##deleted_admin_level##) was deleted by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',7,NULL),
(1,2,'Access level was changed (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that access level ##access_level## was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',8,NULL),
(1,13,'Access level was changed (##site_name## system message)','Hello ##admin_login##,\nwe inform you, that access level ##access_level## was changed by ##current_admin_login##\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',8,NULL),
(1,2,'Payment notification (##site_name## system message)','Hello ##admin_login##,\nwe have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',9,NULL),
(1,13,'Payment notification (##site_name## system message)','Hello ##admin_login##,\nwe have received payment on product \'##product_name##\' subcsription. The term is extended until ##product_expiration_date##.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',9,NULL),
(1,2,'Payment error notification (##site_name## system message)','Hello ##admin_login##,\nwe have not received payment on product \'##product_name##\' because of the payment error.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',10,NULL),
(1,13,'Payment error notification (##site_name## system message)','Hello ##admin_login##,\nwe have not received payment on product \'##product_name##\' because of the payment error.\nPayment details:\n- Subscription ID: ##subscription_id##;\n- Transaction ID: ##transaction_id##;\n- Amount: ##amount##.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',10,NULL),
(1,2,'Product subscription is started (##site_name## system message)','Hello ##admin_login##,\nsubscription of ##user_login## on product \'##product_name##\' is started.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',11,NULL),
(1,13,'Product subscription is started (##site_name## system message)','Hello ##admin_login##,\nsubscription of ##user_login## on product \'##product_name##\' is started.\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',11,NULL),
(1,2,'Product subscription is ended/expired (##site_name## system message)','Hello ##admin_login##,\nsubscription of ##user_login## on product \'##expired_product_name##\' is closed (product expiration date is ##product_expiration_date##).\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',12,NULL),
(1,13,'Product subscription is ended/expired (##site_name## system message)','Hello ##admin_login##,\nsubscription of ##user_login## on product \'##expired_product_name##\' is closed (product expiration date is ##product_expiration_date##).\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',12,NULL),
(1,2,'New member account is registered (##site_name## system message)','Hello ##admin_login##,\nnew member account (login: ##user_login##) was successfully registered\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',13,NULL),
(1,13,'New member account is registered (##site_name## system message)','Hello ##admin_login##,\nnew member account (login: ##user_login##) was successfully registered\n\n[ this email was automaticaly generated at ##current_date## ##current_time## ]\n--\nbest regards,\nadministration of ##site_name##\n##site_base_url##',13,NULL),
(1,15,'General','',1,NULL),
(1,3,'Demo Product Group 3','Description of the demo product group. You can add your products to this group.',3,NULL);
/*!40000 ALTER TABLE `db_prefix_Language_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Languages`
--

LOCK TABLES `db_prefix_Languages` WRITE;
/*!40000 ALTER TABLE `db_prefix_Languages` DISABLE KEYS */;
INSERT INTO `db_prefix_Languages` VALUES (1,'English (US)',1,'en-US');
/*!40000 ALTER TABLE `db_prefix_Languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Member_groups`
--

LOCK TABLES `db_prefix_Member_groups` WRITE;
/*!40000 ALTER TABLE `db_prefix_Member_groups` DISABLE KEYS */;
INSERT INTO `db_prefix_Member_groups` VALUES (1);
/*!40000 ALTER TABLE `db_prefix_Member_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Member_groups_members`
--

LOCK TABLES `db_prefix_Member_groups_members` WRITE;
/*!40000 ALTER TABLE `db_prefix_Member_groups_members` DISABLE KEYS */;
INSERT INTO `db_prefix_Member_groups_members` VALUES (1,1),
(1,2);
/*!40000 ALTER TABLE `db_prefix_Member_groups_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Member_groups_products`
--

LOCK TABLES `db_prefix_Member_groups_products` WRITE;
/*!40000 ALTER TABLE `db_prefix_Member_groups_products` DISABLE KEYS */;
INSERT INTO `db_prefix_Member_groups_products` VALUES (1,1,1),
(1,2,1),
(1,3,1),
(1,4,1),
(1,5,0);
/*!40000 ALTER TABLE `db_prefix_Member_groups_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_News`
--

LOCK TABLES `db_prefix_News` WRITE;
/*!40000 ALTER TABLE `db_prefix_News` DISABLE KEYS */;
INSERT INTO `db_prefix_News` VALUES (1,'2009-01-01',0,1,'Demo-news-1',1),
(2,'2009-01-01',1,1,'Demo-news-2',0);
/*!40000 ALTER TABLE `db_prefix_News` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Pages`
--

LOCK TABLES `db_prefix_Pages` WRITE;
/*!40000 ALTER TABLE `db_prefix_Pages` DISABLE KEYS */;
INSERT INTO `db_prefix_Pages` VALUES (1,'TOS',0,1,1,0);
/*!40000 ALTER TABLE `db_prefix_Pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Prices`
--

LOCK TABLES `db_prefix_Prices` WRITE;
/*!40000 ALTER TABLE `db_prefix_Prices` DISABLE KEYS */;
INSERT INTO `db_prefix_Prices` VALUES (1,'0.00','0.00','0.00','0.00','0.00','0.00'),
(2,'10.99','200.00','299.80','350.25','500.00','699.98'),
(3,'10.99','200.00','299.80','350.25','500.00','699.98'),
(4,'0.00','19.25','0.00','84.00','0.00','0.00'),
(5,'0.00','19.25','0.00','84.00','0.00','0.00');
/*!40000 ALTER TABLE `db_prefix_Prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Product_discount`
--

LOCK TABLES `db_prefix_Product_discount` WRITE;
/*!40000 ALTER TABLE `db_prefix_Product_discount` DISABLE KEYS */;
INSERT INTO `db_prefix_Product_discount` VALUES (1,'0.00',1,0),
(2,'0.00',1,0),
(3,'15.00',1,0),
(4,'1.25',2,0),
(5,'1.25',2,0);
/*!40000 ALTER TABLE `db_prefix_Product_discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Product_groups`
--

LOCK TABLES `db_prefix_Product_groups` WRITE;
/*!40000 ALTER TABLE `db_prefix_Product_groups` DISABLE KEYS */;
INSERT INTO `db_prefix_Product_groups` VALUES (1),
(2),
(3);
/*!40000 ALTER TABLE `db_prefix_Product_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Product_product_group`
--

LOCK TABLES `db_prefix_Product_product_group` WRITE;
/*!40000 ALTER TABLE `db_prefix_Product_product_group` DISABLE KEYS */;
INSERT INTO `db_prefix_Product_product_group` VALUES (1,1),
(2,2),
(2,3),
(2,4),
(2,5);
/*!40000 ALTER TABLE `db_prefix_Product_product_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Products`
--

LOCK TABLES `db_prefix_Products` WRITE;
/*!40000 ALTER TABLE `db_prefix_Products` DISABLE KEYS */;
INSERT INTO `db_prefix_Products` VALUES (1,1,0,NULL,1,0,0,1,1),
(2,1,0,NULL,2,0,0,1,0),
(3,1,0,NULL,2,0,0,1,0),
(4,1,0,NULL,2,0,0,1,0),
(5,2,0,NULL,2,0,0,1,0);
/*!40000 ALTER TABLE `db_prefix_Products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Protection`
--

LOCK TABLES `db_prefix_Protection` WRITE;
/*!40000 ALTER TABLE `db_prefix_Protection` DISABLE KEYS */;
INSERT INTO `db_prefix_Protection` VALUES (2,1,1);
/*!40000 ALTER TABLE `db_prefix_Protection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Subscription_info`
--

LOCK TABLES `db_prefix_Subscription_info` WRITE;
/*!40000 ALTER TABLE `db_prefix_Subscription_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Subscription_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Subscriptions`
--

LOCK TABLES `db_prefix_Subscriptions` WRITE;
/*!40000 ALTER TABLE `db_prefix_Subscriptions` DISABLE KEYS */;
INSERT INTO `db_prefix_Subscriptions` VALUES (1,'2008-12-27','2013-12-27',0,1,1,'','0.00',0,'year',5,'0.00',1,'USD',0);
/*!40000 ALTER TABLE `db_prefix_Subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Suspend_reasons`
--

LOCK TABLES `db_prefix_Suspend_reasons` WRITE;
/*!40000 ALTER TABLE `db_prefix_Suspend_reasons` DISABLE KEYS */;
INSERT INTO `db_prefix_Suspend_reasons` VALUES (1),
(2),
(3);
/*!40000 ALTER TABLE `db_prefix_Suspend_reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_System_emails`
--

LOCK TABLES `db_prefix_System_emails` WRITE;
/*!40000 ALTER TABLE `db_prefix_System_emails` DISABLE KEYS */;
INSERT INTO `db_prefix_System_emails` VALUES (1,'your_admin_account_created','admin','','admin_password'),
(2,'your_admin_account_changed','admin','','admin_password'),
(3,'your_admin_account_deleted','admin','',''),
(4,'your_admin_remind_password','admin','','admin_remind_password_link'),
(5,'admin_account_created','admin','','current_admin_login;created_admin_login;created_admin_level'),
(6,'admin_account_changed','admin','','current_admin_login;changed_admin_login;changed_admin_level'),
(7,'admin_account_deleted','admin','','current_admin_login;deleted_admin_login;deleted_admin_level'),
(8,'admin_access_level_change','admin','','current_admin_login;access_level'),
(9,'admin_payment_notification','admin','','product_name;subscription_id;transaction_id;amount'),
(10,'admin_payment_error','admin','','product_name;subscription_id;transaction_id;amount'),
(11,'admin_subscription_started','admin','','user_login;product_name'),
(12,'admin_subscription_ended','admin','','user_login;expired_product_name;product_expiration_date'),
(13,'admin_new_member_registered','admin','','user_login'),
(14,'user_profile_change','user','',''),
(15,'user_change_password','user','','user_new_password'),
(16,'user_registration_completed','user','','user_password'),
(17,'user_account_activation','user','','user_activation_link'),
(18,'user_profile_status_change','user','','user_account_status'),
(19,'user_account_expire','user','',''),
(20,'user_subscription_expired','user','','expired_product_name;product_expiration_date'),
(21,'user_subscription_almost_expired','user','','expired_product_name;product_expiration_date'),
(22,'user_payment_notification','user','','product_name;product_expiration_date'),
(23,'user_payment_error','user','','product_name;subscription_id;transaction_id'),
(24,'user_remind_password','user','','user_remind_password_link');
/*!40000 ALTER TABLE `db_prefix_System_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_System_info`
--

LOCK TABLES `db_prefix_System_info` WRITE;
/*!40000 ALTER TABLE `db_prefix_System_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_System_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_System_settings`
--

LOCK TABLES `db_prefix_System_settings` WRITE;
/*!40000 ALTER TABLE `db_prefix_System_settings` DISABLE KEYS */;
INSERT INTO `db_prefix_System_settings` VALUES ('23b736407149f1f8bfcb82831df7cb24','member_pages/profile/login/order','0'),
('fbc4b4b8df0f00ea277458c0c59a25ef','member_pages/profile_billing/address1/enabled','1'),
('f1f916462e817a5964ed79e5bb97c9ee','member_pages/profile_billing/address1/length/limit','64'),
('3f571ebe603ac2ac27f71af90867a15e','fields/types/email/name','email'),
('45721c991985be353a0248083f8328e3','fields/types/normal_password/name','password normal'),
('8e26ec2c31963045da845b0b7ecfde19','fields/types/normal_password/expression','LygoKD89LipbYS16XSkoPz0uKltBLVowLTlcIUAjJCVeJio9K1wvfjw+PzstXSkpfCgoPz0uKltBLVpdKSg/PS4qW2EtejAtOVwhQCMkJV4mKj0rXC9+PD4/Oy1dKSl8KCg/PS4qWzAtOV0pKD89LipbYS16QS1aXCFAIyQlXiYqPStcL348Pj87LV0pKXwoKD89LipbXCFAIyQlXiYqPStcL348Pj87LV0pKD89LipbYS16QS1aMC05XSkpKS8='),
('b19a0a5b2139e9fd085f9aea9498969f','registration','member_pages'),
('2f45a5ea8a49f0986d178242e76e9e27','member_pages/profile_billing/address1/length/max','64'),
('e329ca7e769e5b4c2cbe255ceb1b4073','member_pages/profile_billing/address1/length/min','1'),
('876dc72623466c81ad9d76cca679784c','member_pages/profile_billing/address1/obligate','1'),
('fb3709c70ce6989689228c2aa0499863','member_pages/profile_billing/address1/order','7'),
('08a0a58fbd3d02be034c25bd57ad0868','member_pages/profile_billing/address1/type','text'),
('f0538a08a8d0436e7290f9c2130e37bd','member_pages/profile_billing/address2/enabled','0'),
('bbf73d45627ecd54b101c65f37bb8687','member_pages/profile_billing/address2/length/limit','64'),
('76d30eb39236f6491b9b3fa82da8cf8a','member_pages/profile_billing/address2/length/max','64'),
('c1e56ebd5b3856531a26df1d3f52d6d7','member_pages/profile_billing/address2/length/min','1'),
('c5160a459f150c28332522c49a9cf750','member_pages/profile_billing/address2/order','8'),
('d5f1aa8fa8a2fc717067c29a29dd21a8','member_pages/profile_billing/address2/required','0'),
('add3920f266fc8479171a51f0cc62108','member_pages/profile_billing/address2/type','text'),
('371e508f4b2059fb8f74c1fbd5dd18ca','member_pages/profile_billing/address3/enabled','0'),
('5f89d697556417582c925fe7a30d64dd','member_pages/profile_billing/address3/length/limit','64'),
('878819bcbc41f5e857d9124809154f1c','member_pages/profile_billing/address3/length/max','64'),
('f83ef5ace7a8d1ad29c75ff4420e7a83','member_pages/profile_billing/address3/length/min','1'),
('a5e3d9ce02ecc8f29d4a04c65c5326db','member_pages/profile_billing/address3/order','9'),
('e9da7142bac0848328312f66f8336b9b','member_pages/profile_billing/address3/required','0'),
('310e963b4cf57c4149db10854953948b','fields/types/phone/expression','L15cKFtcZF17MSwzfVwpW1xkXXs0LDEyfSQv'),
('c1a1a5676b3d33a971c70b3deddf6193','fields/types/phone/name','phone'),
('cbbe0990e1cace7cf7590b1f7ea4f9ee','member_pages/profile_billing/address3/type','text'),
('621c97bbd29e2996be91e40335881ee5','member_pages/profile_billing/alttelno/enabled','0'),
('4cc0baa3d766228ed316520e1b912671','member_pages/profile_billing/alttelno/order','11'),
('241e41b5c4e953d18cf6446de91e8edd','member_pages/profile_billing/alttelno/required','0'),
('410b6230d8c484ff121b0cce54aee9da','member_pages/profile_billing/alttelno/type','phone'),
('f644663b1b91b6f8200756e9cb35e8f5','member_pages/profile_billing/city/enabled','1'),
('552c859a5c8bbec24d9e529d5bcfb586','member_pages/profile_billing/city/length/limit','64'),
('57e92c6e4cdf9f8f1e3925e73fb4978d','member_pages/profile_billing/city/length/max','64'),
('f09b7f9edf959af1edbf8cfdd03d8bbb','member_pages/profile_billing/city/length/min','1'),
('6a0b0c0eaaec731e6c06bb84bf87a240','member_pages/profile_billing/city/obligate','1'),
('69737aa37739c5eef0143781c440b698','key1/key2/key3','xxxx'),
('1c00d190b1b887076d29ed7958395c25','fields/types/simple_latin/expression','L15bYS16QS1aXStbYS16QS1aMC05Xy1dKiQvCg=='),
('9c25d254a244c3f8a1509cecff04d65d','fields/types/simple_latin/name','simple latin'),
('7406a09de8e1f5e3cdaedad0417ea2d2','member_pages/profile_billing/city/order','5'),
('18d4609fd9c9f0bf7217b5e2b18f9cd6','member_pages/profile_billing/city/type','text'),
('557292d25d414a9c5d661802bbba1e97','member_pages/profile_billing/company/enabled','0'),
('12cc5b2cbcb4e2cb1ff286d2fb830087','member_pages/profile_billing/company/length/limit','64'),
('8b7043f9fdcf3f9f4d0a2ca062f08390','member_pages/profile_billing/company/length/max','64'),
('cc81f07e146e080b888f5d1656f983cd','member_pages/profile_billing/company/length/min','1'),
('7bf9525d32092438dab99463f6464718','member_pages/profile_billing/company/order','2'),
('fb6537d9593329cd364e9db74832a366','member_pages/profile_billing/company/type','text'),
('904e763b3e0f5b69da8e1c3afbf95116','member_pages/profile_billing/country/enabled','1'),
('d2a39cabbb6a13342d0a8fba8bda377d','member_pages/profile_billing/country/obligate','1'),
('30b7b5124c3b1854c0d101a4a292bd2f','member_pages/profile_billing/country/order','3'),
('16b2c8571dd21c9677cb5e9995006917','member_pages/profile_billing/customerlangpref/enabled','0'),
('5b9a711ef9baec0873ceefba46799c7f','member_pages/profile_billing/customerlangpref/hidden/0','languages'),
('c7f057b94e2396eaf681d801452323b5','member_pages/profile_billing/customerlangpref/languages/ch','chinese'),
('f93aaf0829e3ba26ef2405f5e641ba22','member_pages/profile_billing/customerlangpref/languages/el','greek'),
('8495b8c4134ae9b9f1131a04d6bf5f5f','member_pages/profile_billing/customerlangpref/languages/en','english'),
('3040e9ba6c4a172399e09c8deb281cf9','member_pages/profile_billing/customerlangpref/languages/es','spanish'),
('0e1a24042fd450642b39504f15e45e61','member_pages/profile_billing/customerlangpref/languages/fr','french'),
('e9bf2ac5ecd237af8fe61759d5a83739','member_pages/profile_billing/customerlangpref/languages/it','italian'),
('2dacb154ad4421d4672879c33fe83a60','member_pages/profile_billing/customerlangpref/languages/ru','russian'),
('d9a9e4c05a8a9b643835782de09b7074','member_pages/profile_billing/customerlangpref/languages/sl','slovenian'),
('003c4022e759828facdddde934b85304','member_pages/profile_billing/customerlangpref/languages/tr','turkish'),
('40a75cf295e4f91d993e5907b52f2f61','member_pages/profile_billing/customerlangpref/order','13'),
('f9a490a4556df83cb3a92bfee03060d7','member_pages/profile_billing/faxno/enabled','0'),
('f1f8f5a0448929eb4fe065b8f42ff20c','member_pages/profile_billing/faxno/order','12'),
('58a8b42b27fe2e8d6c6c4da14677ae43','member_pages/profile_billing/faxno/required','0'),
('e4119034df7e9a0959fee446d4a3b728','member_pages/profile_billing/faxno/type','phone'),
('df00fd6ac667e18547c36ae443e773ce','member_pages/profile_billing/forceuse/enabled','1'),
('a57bdcd6e5a08044e0b47b068435eb79','member_pages/profile_billing/forceuse/order','0'),
('4f04950bd0186b9142090d10008b7346','member_pages/profile_billing/name/enabled','1'),
('239405a24a67ab678a50b8255f3436d4','member_pages/profile_billing/name/length/limit','64'),
('67387ee79f8b2ea0c864cc00c11f34a9','member_pages/profile_billing/name/length/max','64'),
('4d6be0c886ae376f85e5abf516e38e30','member_pages/profile_billing/name/length/min','1'),
('7b7533233c97f61cf4df9c555e790ecf','member_pages/profile_billing/name/order','1'),
('4934e6f7da65cedf4f6d4d135298c74a','member_pages/profile_billing/name/type','text'),
('c72f147b9e80760b0214dd12c2d872c3','member_pages/profile_billing/state/enabled','1'),
('1be3f93b0357d195f29c59328ef69343','member_pages/profile_billing/state/order','4'),
('2d7df270c38c8c6fe276bb4d89f841c7','member_pages/profile_billing/telno/enabled','1'),
('1faa00a28920828d30d7b6300b529115','member_pages/profile_billing/telno/obligate','1'),
('c6851d100f7086b5765eb0f0c2d9c38f','member_pages/profile_billing/telno/order','10'),
('c2723c8238cbf1577c43ee3c0b11606c','member_pages/profile_billing/telno/type','phone'),
('08df9803115e080e4d54e1e976c96fb2','member_pages/profile_billing/zip/enabled','1'),
('d8d27a7884299e42d93605f2cec5f8ac','member_pages/profile_billing/zip/length/limit','10'),
('248ad2efb9edbacf224bffcc52146e7f','member_pages/profile_billing/zip/length/max','10'),
('8421efeabe5b74c37a913d764d692cfe','member_pages/profile_billing/zip/length/min','1'),
('b08595f98009a59e96b15631f33dc436','member_pages/profile_billing/zip/obligate','1'),
('0d7e7ecff19b61a9eb4830ff1e74d04a','member_pages/profile_billing/zip/order','6'),
('fd8c297616dbf87403724b7a11991405','member_pages/profile_billing/zip/type','zip_code'),
('cadf659a884431619ee9f261f5b84e3b','member_pages/profile_domain/address1/enabled','1'),
('468de88dfe6419cd3fbaf394fea82d0f','member_pages/profile_domain/address1/length/limit','64'),
('4bb445c164864d93774d28f4ac4692d5','member_pages/profile_domain/address1/length/max','64'),
('d538b69636cb9cdb2c766d3147340eef','member_pages/profile_domain/address1/length/min','1'),
('f0974bd4e4362af1fec72106ec56ef60','member_pages/profile_domain/address1/obligate','1'),
('b25642092cd0d2047b2327e285e1ccd3','member_pages/profile_domain/address1/order','7'),
('40bbb04f1dedc7a4d045ef3dcdec4117','member_pages/profile_domain/address1/type','text'),
('1a3afdca5c9d83aca65eb093acc3b86a','member_pages/profile_domain/address2/enabled','1'),
('5176295202eab3ab2a572d49ac241a14','member_pages/profile_domain/address2/length/limit','64'),
('6840ed61619e396f37639909db558552','member_pages/profile_domain/address2/length/max','64'),
('46a1415191241551ef01f75f1572a86b','member_pages/profile_domain/address2/length/min','1'),
('667a06144c7bf498c18948370bdc788b','member_pages/profile_domain/address2/order','8'),
('93845a5b04434b0853a860c6ed65cbaa','member_pages/profile_domain/address2/required','0'),
('0a8f5c268a189501a8f1b91058be0e73','member_pages/profile_domain/address2/type','text'),
('d6a92122bcea73143e2be5c3298be508','member_pages/profile_domain/address3/enabled','1'),
('b4ca7c747ddbeb9c91710db8e6087df2','member_pages/profile_domain/address3/length/limit','64'),
('ba80e73d55958bc306130978a571126c','member_pages/profile_domain/address3/length/max','64'),
('197d2e0e4b98e945bc0519741bc2aacc','member_pages/profile_domain/address3/length/min','1'),
('860f9f66e4e31d68c462872bffdd2167','member_pages/profile_domain/address3/order','9'),
('ec51d3fa01f3bace1aed08f08f06be8a','member_pages/profile_domain/address3/required','0'),
('5d5936bcc46331b88389c36ccf26192d','member_pages/profile_domain/address3/type','text'),
('603adbf9d0bf31c0ddd3a41c1b1e7820','member_pages/profile_domain/alttelno/enabled','1'),
('13b99e9b27f1d9d0b1a1edaa78d3d844','member_pages/profile_domain/alttelno/order','11'),
('002d069a537f4c253babaafde5ee39df','member_pages/profile_domain/alttelno/required','0'),
('ee7f8a489cdfe094e020c2e4614e27bc','member_pages/profile_domain/alttelno/type','phone'),
('947bb643b9d60729b1f9fd7b323e1427','member_pages/profile_domain/city/enabled','1'),
('99fbb31bf0bacf41e3e8a6dd9b2b0d50','member_pages/profile_domain/city/length/limit','64'),
('a586ca06577a5645758aec48b85a0836','member_pages/profile_domain/city/length/max','64'),
('9d740c26fcb5dfa065ec2d4dbb22f577','member_pages/profile_domain/city/length/min','1'),
('52297a12c579ba80e3fc33c0f5e09949','member_pages/profile_domain/city/obligate','1'),
('791209f04636d5f48cb13dda045a277e','member_pages/profile_domain/city/order','5'),
('c4658eccf5f81d2efa6a61d4e747df48','member_pages/profile_domain/city/type','text'),
('0e1eeec6d663be696f1a4256043a3cbb','member_pages/profile_domain/company/enabled','1'),
('b8b5e0453e5839982d36a946f34ea500','member_pages_presets/profile_billing/default/address1/length/max','64'),
('c88f5d8ddfce331bf2abd7bc8c2b371a','member_pages_presets/profile_billing/default/address1/length/min','1'),
('d7858bd7ecf09d4786728f504bc3edae','member_pages_presets/profile_billing/default/address1/obligate','1'),
('e10fe297c3dc4868c7803115c48ad519','member_pages_presets/profile_billing/default/address1/order','7'),
('735261180f452bd3b4452fe334e8e538','member_pages_presets/profile_billing/default/address1/type','text'),
('e1fdc3863580ddb1b2c3603d4934b115','member_pages_presets/profile_billing/default/address2/enabled','0'),
('b967969fc75582e4960194de8f8feb50','member_pages_presets/profile_billing/default/address2/length/limit','64'),
('f8bf57c43047cf31b68a73eacdd6f28e','member_pages_presets/profile_billing/default/address2/length/max','64'),
('f5ee22ac725f99081f0a78c4e8926257','member_pages_presets/profile_billing/default/address2/length/min','1'),
('17261f5ca011c591b179bc1a3ed06bcf','member_pages_presets/profile_billing/default/address2/order','8'),
('71c89b25329505c909f52f9a39f68b12','member_pages_presets/profile_billing/default/address2/required','0'),
('3929cb1e9b55b5e39a6c7be3a996a53e','member_pages_presets/profile_billing/default/address2/type','text'),
('c622f01047a1c7c42561098c9a547403','member_pages_presets/profile_billing/default/address3/enabled','0'),
('fd689e9fac699088b271ad04b5985673','member_pages_presets/profile_billing/default/address3/length/limit','64'),
('d36e42454a69671cabef6729753d42d3','member_pages_presets/profile_billing/default/address3/length/max','64'),
('f9f7bac2c1c1dfa2944b3156e7f4786b','member_pages_presets/profile_billing/default/address3/length/min','1'),
('d4d196f1ba38cc92e34a6c68d3b1ea16','member_pages_presets/profile_billing/default/address3/order','9'),
('d28fb09579b80330ba69799afc5da454','member_pages_presets/profile_billing/default/address3/required','0'),
('6fc49f15f71ee45336c338a685694b21','member_pages_presets/profile_billing/default/address3/type','text'),
('571a1f308d781917f93528c02a8a1666','member_pages_presets/profile_billing/default/alttelno/enabled','0'),
('a4a2232f9c9b473fc3c7692359b3dbeb','member_pages_presets/profile_billing/default/alttelno/order','11'),
('581d78ad11ba68feb67133383cbb4c23','member_pages_presets/profile_billing/default/alttelno/required','0'),
('c10509376b15197f1a2c4c65f980446e','member_pages_presets/profile_billing/default/alttelno/type','phone'),
('f8d7c45daf653ae9e8e8f1475b1f77e8','member_pages_presets/profile_billing/default/city/enabled','1'),
('c56cc17eaf89fce7cea3f420dda74bca','member_pages_presets/profile_billing/default/city/length/limit','64'),
('2a7f3729c80759758e027c422324fa57','member_pages_presets/profile_billing/default/city/length/max','64'),
('df951dcbcb9bce121cf9a9fb0c5bfd93','member_pages_presets/profile_billing/default/city/length/min','1'),
('00f5cc7d726b93be180e1e3694b7764f','member_pages_presets/profile_billing/default/city/obligate','1'),
('ad0f6aa5f00f96ba85013cbbd3c94959','member_pages_presets/profile_billing/default/city/order','5'),
('9d4fc05851c4952b6cdda3649cfe5c94','member_pages_presets/profile_billing/default/city/type','text'),
('2d8cb098dc540c59e367c9ba89bd3b14','member_pages_presets/profile_billing/default/company/enabled','0'),
('0b77b1d8af2fc985bbefa7de1fd26a80','member_pages_presets/profile_billing/default/company/length/limit','64'),
('c28d31d2df3b53639371bbf09477f030','member_pages_presets/profile_billing/default/company/length/max','64'),
('a50fe357b6b45e4f4f834b89e629cacc','member_pages_presets/profile_billing/default/company/length/min','1'),
('7d11009621b9ab8306dfebdedc30fc51','member_pages_presets/profile_billing/default/company/order','2'),
('aa72beaf94cca674a9e20758644cf3d5','member_pages_presets/profile_billing/default/company/type','text'),
('93b624daf445c9b3985d19a36549964a','member_pages_presets/profile_billing/default/country/enabled','1'),
('9aa6eecd0288c743077b9f8b358aa3fa','member_pages_presets/profile_billing/default/country/obligate','1'),
('ac5973ec0b6b972a3d97a385fd67d0ab','member_pages_presets/profile_billing/default/country/order','3'),
('3901f2851e835a00062a2fe23fe5412f','member_pages_presets/profile_billing/default/customerlangpref/enabled','0'),
('9311793dea140a129d7eeac1a186e9d9','member_pages_presets/profile_billing/default/customerlangpref/hidden/0','languages'),
('e8d1cd93b699de01f16e4ced8e3cd1ad','member_pages_presets/profile_billing/default/customerlangpref/languages/ch','chinese'),
('300fcafa0952fe9944def656ff069958','member_pages_presets/profile_billing/default/customerlangpref/languages/el','greek'),
('fe045856162225d348d6402b19315168','member_pages_presets/profile_billing/default/customerlangpref/languages/en','english'),
('046a81ebfc801a2bb42e9a88c5694035','member_pages_presets/profile_billing/default/customerlangpref/languages/es','spanish'),
('28bfdeb4519cfb8eb14b12b269172d56','member_pages_presets/profile_billing/default/customerlangpref/languages/fr','french'),
('49627f4aaeb5f8d279fa8446c2812caf','member_pages_presets/profile_billing/default/customerlangpref/languages/it','italian'),
('f035500526fc9ebec2711d449d07e967','member_pages_presets/profile_billing/default/customerlangpref/languages/ru','russian'),
('06dea3ce7d66457524148d3a38de550b','member_pages_presets/profile_billing/default/customerlangpref/languages/sl','slovenian'),
('87f06fc17029593059b84de794cf4911','member_pages_presets/profile_billing/default/customerlangpref/languages/tr','turkish'),
('f30b9e45c9e435bfe987131085c3a48a','member_pages_presets/profile_billing/default/customerlangpref/order','13'),
('acd0a3102b8063954da5608d154a1098','member_pages_presets/profile_billing/default/faxno/enabled','0'),
('134e682aaf026eef02b0b9a571b21418','member_pages_presets/profile_billing/default/faxno/order','12'),
('61b700d2a18e706ae70084e979989512','member_pages_presets/profile_billing/default/faxno/required','0'),
('a2291eeb6eca749b4c938bdc40648659','member_pages_presets/profile_billing/default/faxno/type','phone'),
('5be15086e471294d051936690488fabc','member_pages_presets/profile_billing/default/forceuse/enabled','1'),
('a7153cde062b6fc15040299e49707216','member_pages_presets/profile_billing/default/forceuse/order','0'),
('906be4dc45b3f13980c482ac966c494e','member_pages_presets/profile_billing/default/name/enabled','1'),
('26ece1013c0d4280ae60dd6e3900b6fe','member_pages_presets/profile_billing/default/name/length/limit','64'),
('950e04cba9c5ba85b325fec26793616b','member_pages_presets/profile_billing/default/name/length/max','64'),
('49810eb3f68338d067aee0c3cedc2e8c','member_pages_presets/profile_billing/default/name/length/min','1'),
('18e789a58dca92617e3df504bbd20653','member_pages_presets/profile_billing/default/name/order','1'),
('849ad72ea9e53b6e37bfc37d1f7140a4','member_pages_presets/profile_billing/default/name/type','text'),
('606090ea312b51c5aa4babfdceafd5e1','member_pages_presets/profile_billing/default/state/enabled','1'),
('4d1508998e3dbd9c096d5944fe0f6d4d','member_pages_presets/profile_billing/default/state/order','4'),
('97907b578a03ab1f8734d7beed658bb2','member_pages_presets/profile_billing/default/telno/enabled','1'),
('8027373f105bfff011cea6812d79b837','member_pages_presets/profile_billing/default/telno/obligate','1'),
('2fcb9d2e1e4dda761e3525d3c46e7419','member_pages_presets/profile_billing/default/telno/order','10'),
('53a71a609ca6b89f497ad0706dc5e55c','member_pages_presets/profile_billing/default/telno/type','phone'),
('6a72444fa057b884fe12fdb9d6a4e883','member_pages_presets/profile_billing/default/zip/enabled','1'),
('30b040a7bdcc0fb0acd162c4b8a47c7b','member_pages_presets/profile_billing/default/zip/length/limit','10'),
('3b757f5dace8eee16bb79b61de230984','member_pages_presets/profile_billing/default/zip/length/max','10'),
('ecdaa1177d9051d940fdaeae98a196e0','member_pages_presets/profile_billing/default/zip/length/min','1'),
('e1fd0a5f25dc3f90a9ac20316cf95e86','member_pages_presets/profile_billing/default/zip/obligate','1'),
('f2c3263f094821b74d3e35fb776ddbb1','member_pages_presets/profile_billing/default/zip/order','6'),
('85b102b9bbb5c41d0beb3b2441f707b1','member_pages_presets/profile_billing/default/zip/type','zip_code'),
('94ca5bd4cad0ff79c5664f40432bafa7','member_pages_presets/profile_domain/default/address1/enabled','1'),
('e59f3ed4e3206f6c6058b36e1dfa4b8b','member_pages_presets/profile_domain/default/address1/length/limit','64'),
('cde5ca00300b18a6a0e9bb19a6891a44','member_pages_presets/profile_domain/default/address1/length/max','64'),
('33f571c6706228b910b9e4e27f92e61e','member_pages_presets/profile_domain/default/address1/length/min','1'),
('47025673b449af46c3833583ea265700','member_pages_presets/profile_domain/default/address1/obligate','1'),
('8cd0a8d7e3e3cf5c66e9757726c19324','member_pages_presets/profile_domain/default/address1/order','7'),
('401b924f4763f785642c07ee37df1339','member_pages_presets/profile_domain/default/address1/type','text'),
('ee19c4529147f06a09f1f44689ca2dc8','member_pages_presets/profile_domain/default/address2/enabled','1'),
('ef77412cde425a51a7606babf293afd8','member_pages_presets/profile_domain/default/address2/length/limit','64'),
('268ba5569a103abb747b414ae96fbad0','member_pages_presets/profile_domain/default/address2/length/max','64'),
('a7964b2dbf8b7fc182d8adcdb3760d85','member_pages_presets/profile_domain/default/address2/length/min','1'),
('9e07c3029758ad5eeeb200b6dd47d4a4','member_pages_presets/profile_domain/default/address2/order','8'),
('3b6cde1e465dd48b419314c451dadaa0','member_pages_presets/profile_domain/default/address2/required','0'),
('2de4217d6763f1d7735898a84e1864d3','member_pages_presets/profile_domain/default/address2/type','text'),
('817dab87ffa66d6357e318ddb4fbf6c7','member_pages_presets/profile_domain/default/address3/enabled','1'),
('4889df860919cda21510c7c1f85ecb57','member_pages_presets/profile_domain/default/address3/length/limit','64'),
('a6b750a295da0733d284723ca330cff6','member_pages_presets/profile_domain/default/address3/length/max','64'),
('9b8d66bc7db982808f1fb6bd8efca1f4','member_pages_presets/profile_domain/default/address3/length/min','1'),
('69621fba493d706e848d23a40b434fe0','member_pages_presets/profile_domain/default/address3/order','9'),
('8ed15234ad28c0811ab2fd26c969b765','member_pages_presets/profile_domain/default/address3/required','0'),
('f3510c0568e2e2b1adb2eab875eceb8a','member_pages_presets/profile_domain/default/address3/type','text'),
('6ff601964f6f6dbf4d43a36f0f9dc580','member_pages_presets/profile_domain/default/alttelno/enabled','1'),
('21110dc879f1d4fa45aca463b9f011a1','member_pages_presets/profile_domain/default/alttelno/order','11'),
('dd45fd8890e96b042027feb5a79ee1b8','member_pages_presets/profile_domain/default/alttelno/required','0'),
('a66341caf10fe150d6d2766f57053844','member_pages_presets/profile_domain/default/alttelno/type','phone'),
('4c06ed7354eef863d2bb83833d895e7c','member_pages_presets/profile_domain/default/city/enabled','1'),
('6b7dcc06050a3932984c95459a6aeaa2','member_pages_presets/profile_domain/default/city/length/limit','64'),
('afe915a9b7f36f43aeea45cb32f1024b','member_pages_presets/profile_domain/default/city/length/max','64'),
('0af9ec088699c682b81637f8b0f63ccb','member_pages_presets/profile_domain/default/city/length/min','1'),
('9c8e9d48fc7526a1c62a0361bb0d75f4','member_pages_presets/profile_domain/default/city/obligate','1'),
('8473f3c080d95ae8599c93ab6c032854','member_pages_presets/profile_domain/default/city/order','5'),
('fe215a938ee69eda6a4bb3bf0205bdbf','member_pages_presets/profile_domain/default/city/type','text'),
('0fda09f530299cde57aa70eec3ccd1c0','member_pages_presets/profile_domain/default/company/enabled','1'),
('b45f1aad8544ee824f18cb173d3d0ead','member_pages_presets/profile_domain/default/company/length/limit','64'),
('ea1dde003a61e2eab964a29c250a6aa0','member_pages_presets/profile_domain/default/company/length/max','64'),
('2253ecc2119d30c847a950eda6b672b2','member_pages/profile_domain/company/length/limit','64'),
('c744b72a08dd8cbee55c640f7c4141a9','member_pages/profile_domain/company/length/max','64'),
('7bbe248d7e3f583e26f0c3fa66cd81aa','member_pages/profile_domain/company/length/min','1'),
('42dee7bdc38ebfc049c744e1f8fdf642','member_pages/profile_domain/company/order','2'),
('66c2b84b9219594c244b5907471da176','member_pages_presets/profile_domain/default/company/length/min','1'),
('b49cab705fd66f6c3b158f273e6489af','member_pages_presets/profile_domain/default/company/order','2'),
('8bc42ff7b4113167796e00e2c00f9fbc','member_pages_presets/profile_domain/default/company/type','text'),
('edc80aa00f1f6e255033cadf924d90c7','member_pages_presets/profile_domain/default/country/enabled','1'),
('1f6757e9e24a970e4f97de7cfe30b4b5','member_pages_presets/profile_domain/default/country/obligate','1'),
('3b6e24e3d485b4c56bfa41d5456b7e01','member_pages/profile_domain/company/type','text'),
('ce692265459b8afcaf3141beee89b165','member_pages/profile_domain/country/enabled','1'),
('713fb4ff317a21b46226ba7b401c261a','member_pages/profile_domain/country/obligate','1'),
('3c238d93e2935ca324dc6f2d4bcc4d10','member_pages/profile_domain/country/order','3'),
('d33c0027c2d1f750c2adc4c68135544b','member_pages/profile_domain/customerlangpref/enabled','1'),
('25384727e4c2a5cdf8108736d59af41e','member_pages/profile_domain/customerlangpref/hidden/0','languages'),
('b8181df17f8ee2269ee78fe6d6d3ebcf','member_pages/profile_domain/customerlangpref/languages/ch','chinese'),
('cf2d8fa6c8ffc7899c745cd5f3f8f2cb','member_pages/profile_domain/customerlangpref/languages/el','greek'),
('c6432e82bd6437cf2bc5240dbf2cabac','member_pages/profile_domain/customerlangpref/languages/en','english'),
('705705ece46245dfaabdb018d0275c3a','member_pages/profile_domain/customerlangpref/languages/es','spanish'),
('fd273898eed069128011dfac9d79e1d9','member_pages/profile_domain/customerlangpref/languages/fr','french'),
('b62b5d6982f0a296da11e0d488fdcefe','member_pages_presets/profile_domain/default/country/order','3'),
('fa39df7a55dc2623402799126da152e2','member_pages_presets/profile_domain/default/customerlangpref/enabled','1'),
('470cfbd923cd9f0c7f67b8a4e06a4098','member_pages_presets/profile_domain/default/customerlangpref/hidden/0','languages'),
('292132cc911a239a5bd01367f7acc3e7','member_pages_presets/profile_domain/default/customerlangpref/languages/ch','chinese'),
('207482740aa99409c47035101e2b23e0','member_pages_presets/profile_domain/default/customerlangpref/languages/el','greek'),
('172d66576e4ec44436858ca91d7d5489','member_pages_presets/profile_domain/default/customerlangpref/languages/en','english'),
('c60cff2eff4e6c5d1c8b9a80675752d9','member_pages_presets/profile_domain/default/customerlangpref/languages/es','spanish'),
('43204b0244920c01c9a1c2474bb61f8d','member_pages_presets/profile_domain/default/customerlangpref/languages/fr','french'),
('562b74bbb60fafc64506a18db0b16c3b','member_pages_presets/profile_domain/default/customerlangpref/languages/it','italian'),
('1f3f047002a023f8de7d1d2c66433dd9','member_pages_presets/profile_domain/default/customerlangpref/languages/ru','russian'),
('b8f71fc31bc0a1f8c96df2f38de972c8','member_pages_presets/profile_domain/default/customerlangpref/languages/sl','slovenian'),
('0a61e11fa4fe070d3de7f4b19096e121','member_pages_presets/profile_domain/default/customerlangpref/languages/tr','turkish'),
('a0878462af29b7dee36d0b95f907e1c9','member_pages_presets/profile_domain/default/customerlangpref/order','13'),
('f3f51f015270e4c7be8b545c97b62bdd','member_pages/profile_domain/customerlangpref/languages/it','italian'),
('96d4239b4555c3e4d02ac3c47e3aedb0','member_pages/profile_domain/customerlangpref/languages/ru','russian'),
('b4f34b893bcedae88ae44e8eb9d0f8f1','member_pages/profile_domain/customerlangpref/languages/sl','slovenian'),
('634efab0c72eebd8275aa814f2bedca1','member_pages/profile_domain/customerlangpref/languages/tr','turkish'),
('d32e83db282b34b6030b633f24607e94','member_pages/profile_domain/customerlangpref/order','13'),
('a8b88bfdcec978018c90bc3099d8f0c9','member_pages_presets/profile_domain/default/faxno/enabled','1'),
('8117f83e76174bf1a3091ab453cba94d','member_pages_presets/profile_domain/default/faxno/order','12'),
('bfe5b0bae12a0fbebe44494c9f03eef6','member_pages_presets/profile_domain/default/faxno/required','0'),
('1720dcaa8bcf563235b62e0592fb078a','member_pages_presets/profile_domain/default/faxno/type','phone'),
('58a25c2a909f3fe90b5e36b0282d2a40','member_pages_presets/profile_domain/default/forceuse/enabled','1'),
('7de9a1cd45773b774465db939cf81e1c','fields/types/simple_password/expression','L15bYS16QS1aXStbYS16QS1aMC05Xy1dKiQvCg=='),
('cccc2cb9671e0500d3b6110436f54932','fields/types/simple_password/name','password simple'),
('6b630ee34310d3e5deda6c1b3a50bd71','member_pages/profile_domain/faxno/enabled','1'),
('3f2feaf41f95faf2448d0e79e60bcff4','member_pages/profile_domain/faxno/order','12'),
('628e4b3a63ac810b78cbfd4342fd5f53','member_pages/profile_domain/faxno/required','0'),
('e7b045c6ef330a68d219931c68099ac5','member_pages/profile_domain/faxno/type','phone'),
('a13d162724e0d80e04003f51174d7267','member_pages/profile_domain/forceuse/enabled','1'),
('18388eaed8ab915b9619902ea9276f3a','member_pages/profile_domain/forceuse/order','0'),
('70eb57d16664218c8ec441d27f9b4c66','member_pages/profile_domain/name/enabled','1'),
('3b9cf8392547ffe5ab9490e40b23820f','member_pages/profile_domain/name/length/limit','64'),
('7de9d20a7f86df3046ad14a34b6fb089','member_pages/profile_domain/name/length/max','64'),
('7b0403ddd42e556b41900277679d2bb2','member_pages/profile_domain/name/length/min','1'),
('53dbdd182832a64240749374ee4f6db8','member_pages/profile_domain/name/order','1'),
('517bcba5d71a78a5249617ede96fa17a','member_pages/profile_domain/name/type','text'),
('7649375cdf88f68a88a5b3f18009d0d9','member_pages/profile_domain/state/enabled','1'),
('91c4a1d7c68383aa4f0614b0255fc6ac','member_pages/profile_domain/state/order','4'),
('d2d4e618f46650cd76d74dc0aa1116d0','member_pages/profile_domain/telno/enabled','1'),
('d5ab62a76ab05b01687f9cc8878d1f61','member_pages/profile_domain/telno/obligate','1'),
('c141b4465833c0e9bac240776f73da9e','member_pages/profile_domain/telno/order','10'),
('e182a46b008992d09aece529af14f7ef','member_pages/profile_domain/telno/type','phone'),
('b04b16bcfb35d08d508b6f578146c5d8','member_pages/profile_domain/zip/enabled','1'),
('9c4fca133d8d931543f4611acaafe1db','member_pages_presets/profile_domain/default/forceuse/order','0'),
('712a05ea2210caf4694b189585824c4b','member_pages_presets/profile_domain/default/name/enabled','1'),
('c3813e181c59d50fb1fbdc65aa1f01a0','member_pages_presets/profile_domain/default/name/length/limit','64'),
('b3d486fcc107be3b5d00fbfcc560bf2d','member_pages_presets/profile_domain/default/name/length/max','64'),
('dd121b1406f2a223304762972626c2b2','member_pages_presets/profile_domain/default/name/length/min','1'),
('95a42667c48d98d9bc54a24afc0f319a','member_pages_presets/profile_domain/default/name/order','1'),
('9a004bc38d22d032d3b65997574f7d51','member_pages_presets/profile_domain/default/name/type','text'),
('6def058d68eab4f9b21ce4a6143adbf0','member_pages_presets/profile_domain/default/state/enabled','1'),
('0455c99469dce7b210c552d2683fa5ae','member_pages_presets/profile_domain/default/state/order','4'),
('0f9ae130cd5f61fb98b398ba22cfa522','member_pages_presets/profile_domain/default/telno/enabled','1'),
('4f347b66c41b77582fdaad3640da2794','member_pages_presets/profile_domain/default/telno/obligate','1'),
('0dcad1f95cc4684080962b86702ca71b','member_pages_presets/profile_domain/default/telno/order','10'),
('07f1a4e69589b4cb62736de6c9d46538','member_pages_presets/profile_domain/default/telno/type','phone'),
('d14c995f8a2b12600cdcd55eeb2c64d0','member_pages_presets/profile_domain/default/zip/enabled','1'),
('b61720d1d0b7f5011fb088bca669bd2a','member_pages_presets/profile_domain/default/zip/length/limit','10'),
('4ec0b065b8d2ef50db0ad855bf836b27','member_pages_presets/profile_domain/default/zip/length/max','10'),
('54a3ea1191695b7ac8a94c135d09c694','member_pages_presets/profile_domain/default/zip/length/min','1'),
('3de9824cff4f67aaa6113e2f96bc7ce1','member_pages_presets/profile_domain/default/zip/obligate','1'),
('c401be0a0b3947acc9fcbaed45a79e89','member_pages_presets/profile_domain/default/zip/order','6'),
('ef32107723a94a76961a6eee3d5df6ff','member_pages_presets/profile_domain/default/zip/type','zip_code'),
('6a3c2c5ab27884f5fbeee9e41fed9e80','member_pages_presets/registration/default/additional/enabled','1'),
('5550fb76ec05dba268b223fbba40362a','member_pages/profile_domain/zip/length/limit','10'),
('edd75cf203ee313549fc75a6ef215964','member_pages/profile_domain/zip/length/max','10'),
('80b84a21d076fc77c98710b21252bf8b','member_pages/profile_domain/zip/length/min','1'),
('39f89de6080e5c8f6872ece4a71a5be6','member_pages/profile_domain/zip/obligate','1'),
('5a3a33f6dbcafc0d382932d5fbca297a','member_pages/profile_domain/zip/order','6'),
('1af46eef27243d031205563b16327396','member_pages/profile_domain/zip/type','zip_code'),
('563075690bf425fa5e34eb3dd2075149','member_pages/registration/additional/enabled','1'),
('15cb43102a1d50237072a7ff7db199a4','member_pages_presets/registration/default/additional/order','7'),
('368681e028e589d0ac2f767d4bdca689','member_pages_presets/registration/default/email/enabled','1'),
('506878a50afde9ee12eba661f018ac42','member_pages_presets/registration/default/email/length/limit','255'),
('5324c31ad4e3f0b91e0ecf4e5b0c0ba3','member_pages_presets/registration/default/email/length/max','64'),
('08430bc832de1e80d9f127115ec41837','member_pages_presets/registration/default/email/length/min','4'),
('41ca8ba32d457ee7bd81c35ddec37c4d','fields/types/text/name','text'),
('3416c1611894c14ff73ec8f9701cd35d','fields/types/text/expression',''),
('bb24a70a7a7e910c20c38544b4558531','fields/types/zip_code/expression','L15bYS16QS1aMC05XStbYS16QS1aMC05LV0qJC8='),
('d911d3baf39d6d1adb13a1b9b46eb8ee','fields/types/zip_code/name','zip code'),
('c1f88c8cf0351df2591ce90f83e0b4c8','member_pages/registration/additional/order','7'),
('89d5f23e27698ee562d760aff907c71e','member_pages/registration/email/enabled','1'),
('b84828e39f85f902bce2cdc8aadc0f91','member_pages/registration/email/length/limit','255'),
('d2c06acfa5acd6a9f46f033c824b8308','member_pages/registration/email/length/max','64'),
('bfe41eed279492d64faccef81fecbed9','member_pages/registration/email/length/min','4'),
('f489f7801d9cad01351c6991bbcdb9c0','member_pages/registration/email/obligate','1'),
('f67891ae4a09dd83426ec2a0241170e3','member_pages/registration/email/order','1'),
('3b0f4b4c5b8ce2cf0bf09f208801bf0d','member_pages/registration/email/retype','0'),
('789d59ff91ef6157cf950e9617e20377','member_pages/registration/email/type','email'),
('7fcbca28f6365e321de673b9b484d2e1','member_pages/registration/fname/enabled','1'),
('259b069c7a4a329d36eb33112b279d2a','member_pages/registration/fname/length/limit','32'),
('c30fc198b6992f3efb33e2cb93a4df49','member_pages/registration/fname/length/max','32'),
('52489bff8f8fd41579fd512d7ffd9a39','member_pages/registration/fname/length/min','3'),
('2b62f88fd59c6b7ce8fc13ab444877f8','member_pages/registration/fname/order','2'),
('cd35f0b16f039bd339fd62ff21990a83','member_pages/registration/fname/required','1'),
('3047b78debe3ce505594fa1bed7b1ff7','member_pages/registration/fname/type','text'),
('d03cbc44bcf87f583db87adceb064e3c','member_pages/registration/image_code/enabled','1'),
('5423a3552c6f196de36e8fccceb102a2','member_pages/registration/image_code/order','8'),
('8b170a77b0119fc3c380672bb02cecd9','member_pages/registration/lname/enabled','1'),
('c74aaa7da0d1106e281ce813dbd56c52','member_pages_presets/registration/default/email/obligate','1'),
('630064ccb23e350c0ac6be26f21151b6','member_pages_presets/registration/default/email/order','1'),
('f5d04a412bd41837b49dc165e39313fb','member_pages_presets/registration/default/email/retype','0'),
('77fe9b2889e7435144a51a8cbc672a8d','member_pages_presets/registration/default/email/type','email'),
('17c1e9d246c0cfc50b965a9f8cd5e0b6','member_pages_presets/registration/default/fname/enabled','1'),
('5bb74b02fec9e03403a1de1929f29053','member_pages_presets/registration/default/fname/length/limit','32'),
('54f96080c86734c2e9fb045c1fe40bdf','member_pages_presets/registration/default/fname/length/max','32'),
('427a3d194bf907d5192f226e7ef2191d','member_pages_presets/registration/default/fname/length/min','3'),
('438fa5c1599e45c6f4b9025785f15308','member_pages_presets/registration/default/fname/order','2'),
('4a9ad354f0f430b5c4e987c321a9e918','member_pages_presets/registration/default/fname/required','1'),
('65cec2bb7f9733909242abc34266163f','member_pages_presets/registration/default/fname/type','text'),
('808524361ee0429c6cb5b28758ead9d9','member_pages_presets/registration/default/image_code/enabled','1'),
('44a2da49e0918ef691fcbac5e9fd582d','member_pages_presets/registration/default/image_code/order','8'),
('d21ffc9503c9b4a9588d4cb85993c208','member_pages_presets/registration/default/lname/enabled','1'),
('e03156c4e2ad4f003aed7aefbf0ece19','member_pages_presets/registration/default/lname/length/limit','32'),
('20095bc80d3e556e2758343903a25090','member_pages_presets/registration/default/lname/length/max','32'),
('710f708ce573258de3bdba399e4f27bb','member_pages_presets/registration/default/lname/length/min','3'),
('dba6ddd12ee950248146a0cd23bc6d23','member_pages_presets/registration/default/lname/order','3'),
('c67b2c7ab742db1150e20a2b76e9de46','member_pages_presets/registration/default/lname/required','1'),
('027c5b956ca2f4a5c9b4a7b01ffde400','member_pages/registration/lname/length/limit','32'),
('d631606c0386d30fed337a63d44da994','member_pages/registration/lname/length/max','32'),
('90fe797ce58c3c64740d1b60e8aac4a5','member_pages/registration/lname/length/min','3'),
('8ba8fc49ba73d3017f5333fbdc9f9112','member_pages/registration/lname/order','3'),
('f6f895f982e6c53db1dc98c2425617ce','member_pages/registration/lname/required','1'),
('9ce0c8c3ce5ba763be9f70a3b91c081c','member_pages/registration/lname/type','text'),
('5496158078764bb07abc2198f668dc68','member_pages/registration/login/enabled','1'),
('7b7660e6a0c38f6e85c45d7d816218c1','member_pages_presets/registration/default/lname/type','text'),
('e56256e0c99c13f4b8b2fe341dce8d6d','member_pages_presets/registration/default/login/enabled','1'),
('ed96489cf8e1a1794add1d091dfcfeab','member_pages_presets/registration/default/login/length/limit','64'),
('f2881d57fcf079edef6f9f397932d374','member_pages_presets/registration/default/login/length/max','64'),
('bb5022548ed476565b5f8e6ca49d8caa','member_pages_presets/registration/default/login/length/min','4'),
('86d83d4b0dad2354a2417a86940dc7c6','member_pages_presets/registration/default/login/order','0'),
('1254b045e95a599bd917a37618beb36b','member_pages_presets/registration/default/login/required','1'),
('30f4d91825a7aee84193ba65b4044b58','member_pages/registration/login/length/limit','64'),
('8b710b74fa255fca3002c089cf0f412e','member_pages/registration/login/length/max','64'),
('4d4a3ae74fb9d267879ccafe771d721e','member_pages/registration/login/length/min','4'),
('e9cde1ba6bceec472bff245e62e4a18b','member_pages/registration/login/order','0'),
('4bd8b85eaade767ef0e857f7f9220725','member_pages/registration/login/required','1'),
('1630b35e3468bdfc8544a466fbe85900','member_pages/registration/login/type','simple_latin'),
('454bdffe88ae078e6fd93d5a48a20d61','member_pages/registration/password/enabled','1'),
('ba66b576e529150bb90c266999b256da','member_pages/registration/password/generate','1'),
('199fd2400f2987ff49ae310309b6600e','member_pages/registration/password/order','5'),
('e59403960e5f0b65fccf6917bd63e97c','member_pages/registration/password/retype','1'),
('7d0d1ae01a7ce240b59dd91846ee8b38','member_pages/registration/password/type','normal_password'),
('af187dc89e721f6e8b21fd64bf710109','member_pages/registration/tos/enabled','1'),
('93af022f8d082706f3b45b80c0fff5bb','member_pages/registration/tos/order','9'),
('0fb7cb57758031f86645d7eeb2b9a6eb','member_pages_presets/registration/default/login/type','simple_latin'),
('a4a97f5f77c271bf947d6ebdefd9ef0c','member_pages_presets/registration/default/password/enabled','1'),
('63cfb30cf90ba658c993e8f7663d3c10','member_pages_presets/registration/default/password/generate','1'),
('0656fd44ce6b30d75ac81ccd9258e38d','member_pages_presets/registration/default/password/order','5'),
('847ca2f6d03857a1815bf96211fb1056','member_pages_presets/registration/default/password/retype','1'),
('d539399c8ca69e2e53cdc454355450fa','member_pages_presets/registration/default/password/type','normal_password'),
('90bfccdff38f2bafbb631362f70f1067','member_pages_presets/registration/default/tos/enabled','1'),
('4be90f9496d0b5edfa759b1915c2830b','member_pages_presets/registration/default/tos/order','9'),
('1ef7aa3396754e5c946795c3d307a4d0','fields/types/email/expression','L14oKChbMC05YS16XC1cX10rKVwuKSopKChbMC05YS16XC1cX10pKylAKCgoWzAtOWEtelwtXF9dKylcLikrKSgoWzAtOWEtelwtXF9dKSspJC9p'),
('657fb7005b88387ff4f9b353b199c054','fields/types/complex_password/name','password complex'),
('59f1ac7b4a3914498476d118a5d7e65f','fields/types/complex_password/expression','LygoPz0uKlswLTldKSg/PS4qW2Etel0pKD89LipbQS1aXSkoPz0uKltcIUAjJCVeJio9K1wvfjw+PzstXSkpLw=='),
('f861fbbdc89784747fdfab3f5be7af7f','member_pages/profile/login/enabled','1'),
('97ebd81b339973badeebc701eb9befcc','member_pages/profile/lname/type','text'),
('d6e400ef8b27f8fc46d25be503bbdeb7','member_pages/profile/lname/required','1'),
('c009a156afdc0c49dafdc5a44e96542e','member_pages/profile/lname/order','3'),
('70ae6d7274fa756ea1d456f7f6ac5aaf','member_pages/profile/lname/length/min','3'),
('ea0d0b119f5842772ece054076f50c33','member_pages/profile/lname/length/max','32'),
('564be5a3cbef662635abf177562dfb22','member_pages/profile/lname/length/limit','32'),
('e0b2cc67882f24ab179a26f4b51502d0','member_pages/profile/lname/enabled','1'),
('52fcf6928e6b7698645edf3d76420513','member_pages/profile/fname/type','text'),
('be75279b66ba2b862d89b77bdae60a4f','member_pages/profile/fname/required','1'),
('98bd02c270681d8823dff6b857b0d2cd','member_pages/profile/fname/order','2'),
('f16dc680dda483532c4bc8211e7257c2','member_pages/profile/fname/length/min','3'),
('7db65258cadfb229133dc5565ba53809','member_pages/profile/fname/length/max','32'),
('c54091944faaa33e882df75f4416d9ea','member_pages/profile/fname/length/limit','32'),
('39bdc5a1584fd604b545389f7ba07915','member_pages/profile/fname/enabled','1'),
('3c62f4b64e2a9ed513afe07fe89b5d6b','member_pages/profile/email/type','email'),
('f1427c0562fcc7491337dd8eaa186c89','member_pages/profile/email/retype','0'),
('79b0167c153e33873eb1c0f34ed15fe4','member_pages/profile/email/order','1'),
('3ad8c11ded735afb74db4491fdb64b0d','member_pages/profile/email/obligate','1'),
('488b68a68fea34875fc200f11a0051e5','member_pages/profile/email/length/min','4'),
('27e3474f23778fc08398eaf74c88f9b0','member_pages/profile/email/length/max','64'),
('d148bf1b07fc99ead39d821732e1e0aa','member_pages/profile/email/length/limit','255'),
('b90ab72ba1098dee815f90e9dcdbc6d2','member_pages/profile/email/enabled','1'),
('603aff5759d6649a2280571367e5cb37','member_pages/profile/additional/order','7'),
('ed392e685bcc64bf85529eae0810c546','member_pages/profile/additional/enabled','1'),
('ad39c6bcf4d3fdc00fa77b07b71af0ab','member_pages/password/password/type','normal_password'),
('bd816ff97eb9b6bdd7c3fab3979ffd7d','member_pages/password/password/retype','1'),
('6ca0e973f97354426e9ce6ebe68a3130','member_pages/password/password/order','1'),
('55542f8100dad74a8a64daff9d56350e','member_pages/password/password/generate','1'),
('e0ece0dbfa97c297fc3604d976314361','member_pages/password/password/enabled','1'),
('3f99628ba8cd3f7a405cb9ecd8f9d46d','member_pages/password/old_password/order','0'),
('f43bad4b1c9f597deb14c84ee6b8264d','member_pages/password/old_password/enabled','1'),
('ee4e15c7c04d90234ae626d21f404b9a','member_pages_presets/profile_billing/default/address1/length/limit','64'),
('6681c583878a62c0358c0f4e8c1a9786','member_pages_presets/profile_billing/default/address1/enabled','1'),
('91848d6ac19eef496108aa5081b6be60','member_pages_presets/profile/default/login/order','0'),
('0eb94b86fd4d06177171c858574a7419','member_pages_presets/profile/default/login/enabled','1'),
('23ceb66bde3032bb63601e5cca4ec0d5','member_pages_presets/profile/default/lname/type','text'),
('dee6e587f618b982f8a427507790be53','member_pages_presets/profile/default/lname/required','1'),
('1309f5810da3c4698a7fba0de6326f7c','member_pages_presets/profile/default/lname/order','3'),
('558ff32bfe2f4a83c8ace5ddd7af3672','member_pages_presets/profile/default/lname/length/min','3'),
('fd989f6c37c9b98e0bec764a3db020cd','member_pages_presets/profile/default/lname/length/max','32'),
('67406d8e48f4937499aa487f60cd6ae6','member_pages_presets/profile/default/lname/length/limit','32'),
('5c1fbeccedbce3d134ccac3efaf61e74','member_pages_presets/profile/default/lname/enabled','1'),
('fe656783ff8940e2f2a0912277a5b07c','member_pages_presets/profile/default/fname/type','text'),
('718d1429c3cb899451df197b00d866be','member_pages_presets/profile/default/fname/required','1'),
('e88be647bba3910fe99a8589a179fc90','member_pages_presets/profile/default/fname/order','2'),
('e66d971721c034670bf15410edb0910a','member_pages_presets/profile/default/fname/length/min','3'),
('a439770e8bf9b57a971f5af7fa30b04d','member_pages_presets/profile/default/fname/length/max','32'),
('e0994d715d1436440718cf66d68d9756','member_pages_presets/profile/default/fname/length/limit','32'),
('5093dfb5bf579532a798af70f596042f','member_pages_presets/profile/default/fname/enabled','1'),
('a0a9672b8ea01e04a2acaa61591c4177','member_pages_presets/profile/default/email/type','email'),
('44b029fa56e851d956b9767b5cd214b9','member_pages_presets/profile/default/email/retype','0'),
('180a8567c3b28fe67ca2c97cebfd38e7','member_pages_presets/profile/default/email/order','1'),
('2a67c1be2a2add74ddeb12e20abce532','member_pages_presets/profile/default/email/obligate','1'),
('106c97c20e1e418872780da48f53632c','member_pages_presets/profile/default/email/length/min','4'),
('4cfbb81bf5a1ce1767127c650d6b6d85','member_pages_presets/profile/default/email/length/max','64'),
('fba27cac9ceaab96ee76de62c5fba868','member_pages_presets/profile/default/email/length/limit','255'),
('0caa489338af2bf96d7c01f0da11dacd','member_pages_presets/profile/default/email/enabled','1'),
('9cd5a5f49d880a8f758f9a1d9c6c8533','member_pages_presets/profile/default/additional/order','7'),
('dc2d303ea94cd1d80c8495657c8c305a','member_pages_presets/profile/default/additional/enabled','1'),
('ec44845517787a53db78e387d1b71aa0','member_pages_presets/password/default/password/type','normal_password'),
('22ad4bda15bd5c32651b6901d5c5879b','member_pages_presets/password/default/password/retype','1'),
('b8dd0aff2c0192c65efe1428b9b4ee16','member_pages_presets/password/default/password/order','1'),
('1015884d27efbdef4cb4611c7025b2d3','member_pages_presets/password/default/password/generate','1'),
('2e1e31efc6dd1c8592b892e333a12b78','member_pages_presets/password/default/password/enabled','1'),
('5c455da1d9477a2a42f726b02ee97bae','member_pages_presets/password/default/old_password/order','0'),
('8639d4839cb27cc2caf059cf30daeaad','member_pages_presets/password/default/old_password/enabled','1'),
('bddd63f2b202ccdce8426355ffcaec1b','member_pages/profile_domain/forceuse/required','0'),
('8b2b703b7ca1abb93061387d50bc4482','member_pages/profile_domain/forceuse/hidden/0','required'),
('49eb6ae5476883ef10d9d0497a9979eb','member_pages/profile_billing/forceuse/required','0'),
('2e63ffc3e068b2792fb49e6e1c6c8268','member_pages/profile_billing/forceuse/hidden/0','required'),
('f7a73ed9ee0562f0a58cd4c2cd1f7e45','member_pages/registration/additional/required','0'),
('d64fb7d71e31d1d5a71ef3101df18d64','member_pages/registration/additional/hidden/0','required'),
('b831393066da3313b48d0b32e6506dd8','member_pages/profile/additional/required','0'),
('d492c6d481d254a025978f104c04dec8','member_pages/profile/additional/hidden/0','required'),
('9778c04d4b730a4a6b4b4458cc53249b','member_pages_presets/profile_domain/default/forceuse/required','0'),
('46b6e726128f2aeafeec957848ce3b22','member_pages_presets/profile_domain/default/forceuse/hidden/0','required'),
('9896a25ab7a137380c8c1e9e5aa420c9','member_pages_presets/profile_billing/default/forceuse/required','0'),
('1b4c5e9de166e74ddb055e81a0a23d76','member_pages_presets/profile_billing/default/forceuse/hidden/0','required'),
('aec15124b47e828f7ec1902b08d53ac2','member_pages_presets/registration/default/additional/required','0'),
('367c0181fd6c6f5ccf827d224ec4ca9a','member_pages_presets/registration/default/additional/hidden/0','required'),
('3de9a22226d5428d6276e9c81c6b1b64','member_pages_presets/profile/default/additional/required','0'),
('6304c49aa2e4e40569613684ea288dff','member_pages_presets/profile/default/additional/hidden/0','required');
/*!40000 ALTER TABLE `db_prefix_System_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Transactions`
--

LOCK TABLES `db_prefix_Transactions` WRITE;
/*!40000 ALTER TABLE `db_prefix_Transactions` DISABLE KEYS */;
INSERT INTO `db_prefix_Transactions` VALUES (1,'0.00','a:1:{s:12:\"free_payment\";s:4:\"true\";}',1,'2008-12-27 04:38:38',0,1);
/*!40000 ALTER TABLE `db_prefix_Transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Trial`
--

LOCK TABLES `db_prefix_Trial` WRITE;
/*!40000 ALTER TABLE `db_prefix_Trial` DISABLE KEYS */;
INSERT INTO `db_prefix_Trial` VALUES (1,'0.00','day',0),
(2,'0.00','day',0),
(3,'0.00','day',0),
(4,'0.00','month',1),
(5,'5.00','month',1);
/*!40000 ALTER TABLE `db_prefix_Trial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Used_trials`
--

LOCK TABLES `db_prefix_Used_trials` WRITE;
/*!40000 ALTER TABLE `db_prefix_Used_trials` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_Used_trials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_User_add_fields`
--

LOCK TABLES `db_prefix_User_add_fields` WRITE;
/*!40000 ALTER TABLE `db_prefix_User_add_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_User_add_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_User_info`
--

LOCK TABLES `db_prefix_User_info` WRITE;
/*!40000 ALTER TABLE `db_prefix_User_info` DISABLE KEYS */;
INSERT INTO `db_prefix_User_info` VALUES (1,'Demo user','Unknown','Unknown','XX','12345','US','123456789','');
/*!40000 ALTER TABLE `db_prefix_User_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_User_logins`
--

LOCK TABLES `db_prefix_User_logins` WRITE;
/*!40000 ALTER TABLE `db_prefix_User_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_User_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_User_logs`
--

LOCK TABLES `db_prefix_User_logs` WRITE;
/*!40000 ALTER TABLE `db_prefix_User_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `db_prefix_User_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `db_prefix_Users`
--

LOCK TABLES `db_prefix_Users` WRITE;
/*!40000 ALTER TABLE `db_prefix_Users` DISABLE KEYS */;
INSERT INTO `db_prefix_Users` VALUES (1,'undefined user','$1$Bc0sQnWS$vHlo76PSgippuJpqQoRp./','',NULL,'Undefined user','Undefined user',NULL,'2008-12-26 05:49:10',NULL,'\'\'',NULL),
(2,'user','$1$Tg7nWpuC$p/hvjr0DS1olKVXcUmL1t0','hello@primadg.com',NULL,'DEMO user','Lastname',1,'2008-12-27 04:40:29',NULL,'\'\'',NULL);
/*!40000 ALTER TABLE `db_prefix_Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-08-03 15:06:13
