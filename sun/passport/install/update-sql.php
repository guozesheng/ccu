<?php
//update sql file from version 0.8
if (!defined('install_safe')) {
	exit('Forbidden');
}

$_update_sql = array(
	'0.8.1' => array(
		'ALTER TABLE `##__passport` ADD UNIQUE (`username`)',
	),
	'0.9.0' => array(
		'ALTER TABLE `##__passport` CHANGE `uid` `uid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `regtime` `regtime` INT( 11 ) UNSIGNED NOT NULL DEFAULT \'0\'',
		'ALTER TABLE `##__message` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		CHANGE `read` `read` INT( 11 ) UNSIGNED NOT NULL DEFAULT \'0\',
		CHANGE `time` `time` INT( 11 ) UNSIGNED NOT NULL DEFAULT \'0\'',
	),
	'0.9.5' => array(
		"ALTER TABLE `##__passport` 
		ADD `money0` INT( 10 ) NOT NULL DEFAULT '0' AFTER `email` ,
		ADD `money1` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money0` ,
		ADD `money2` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money1` ,
		ADD `money3` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money2` ,
		ADD `money4` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money3` ,
		ADD `money5` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money4` ,
		ADD `money6` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money5` ,
		ADD `money7` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money6` ,
		ADD `money8` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money7` ,
		ADD `money9` INT( 10 ) NOT NULL DEFAULT '0' AFTER `money8` ;",
		
		"CREATE TABLE IF NOT EXISTS `##__passport_order` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `orderno` varchar(32) NOT NULL,
		  `uid` int(11) unsigned NOT NULL default '0',
		  `mtype` smallint(6) NOT NULL default '0',
		  `num` int(11) unsigned NOT NULL default '0',
		  `fee` int(11) unsigned NOT NULL default '0',
		  `time` int(11) unsigned NOT NULL default '0',
		  `state` tinyint(1) NOT NULL default '0',
		  `descrip` varchar(255) NOT NULL default '',
		  `title` varchar(100) NOT NULL default '',
		  `payer` varchar(100) NOT NULL default '',
		  `tool` varchar(20) NOT NULL default '',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM",
		
		"CREATE TABLE IF NOT EXISTS `##__passport_moneylog` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `uid` int(11) unsigned NOT NULL default '0',
		  `num` int(11) NOT NULL,
		  `yue` int(11) NOT NULL,
		  `mtype` tinyint(4) NOT NULL default '0',
		  `time` int(11) unsigned NOT NULL,
		  `descrip` varchar(255) NOT NULL default '',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM ",
	),
	'0.9.6' => array(
		"ALTER TABLE `##__passport_order` ADD `paytime` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `time` ",
	),
	'1.2.3' => array(
		"ALTER TABLE `##__passport`
		ADD `group` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `email` ,
		ADD `groups` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `group`",
	),
	'1.2.6' => array(
		"ALTER TABLE `##__passport` ADD `grade` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `groups` ,
		ADD `class` INT( 11 ) NOT NULL DEFAULT '0' AFTER `grade` ,
		ADD `classes` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `class` ;
		",
		"
		CREATE TABLE `##__passport_class` (
		  `id` int(11) NOT NULL auto_increment,
		  `classname` varchar(60) NOT NULL default '',
		  `grade` smallint(6) NOT NULL default '0',
		  `ctype` tinyint(1) NOT NULL default '0',
		  `allowapply` tinyint(1) NOT NULL default '0',
		  `apmtype` smallint(6) NOT NULL default '0',
		  `apmoney` int(11) NOT NULL default '0',
		  `applysh` tinyint(1) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM ;
		",
		"
		CREATE TABLE `##__passport_classsh` (
		  `id` int(11) NOT NULL auto_increment,
		  `uid` int(11) NOT NULL default '0',
		  `classid` int(11) NOT NULL default '0',
		  `time` int(11) NOT NULL default '0',
		  `sh` tinyint(1) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM ;
		"
	),
	'1.2.7.081118' => array(
		"CREATE TABLE `##__passport_moneycard` (
	  `cardno` varchar(15) NOT NULL,
	  `password` varchar(18) NOT NULL,
	  `createtime` int(10) NOT NULL default '0',
	  `date` varchar(8) NOT NULL,
	  `timeout` int(10) NOT NULL default '0',
	  `mnum` smallint(8) NOT NULL default '1',
	  `mtype` smallint(8) NOT NULL default '0',
	  `state` tinyint(4) NOT NULL default '0',
	  `useruse` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`cardno`),
	  UNIQUE KEY `password` (`password`)
	) ENGINE=MyISAM DEFAULT CHARSET=gbk"
	),
	'1.2.8.090101' => array(
		"ALTER TABLE `##__passport` ADD `u_lastlogin` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `regip` ,
									ADD `u_lastlogip` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `u_lastlogin`",
		"ALTER TABLE `##__passport_class` ADD `cid` INT( 10 ) NOT NULL DEFAULT '0' AFTER `grade`",
		
		"CREATE TABLE `##__passport_college` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `name` varchar(60) NOT NULL DEFAULT '',
		  `code` varchar(20) NOT NULL DEFAULT '',
		  `discript` text NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=gbk",
		
		"CREATE TABLE `##__passport_professional` (
		  `id` int(10) unsigned NOT NULL auto_increment,
		  `pname` varchar(60) NOT NULL DEFAULT '',
		  `cid` int(10) unsigned NOT NULL DEFAULT '0',
		  `pcode` varchar(20) NOT NULL DEFAULT '',
		  `xuezhi` varchar(20) NOT NULL DEFAULT '',
		  `discript` text NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=gbk",
		
		"ALTER TABLE `##__passport` ADD `u_cid` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `grade` ,
					ADD `u_pid` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `u_cid` ,
					ADD `u_expired` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `regtime` ,
					ADD `u_loginban` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `u_expired`
					",
		"ALTER TABLE `##__passport` ADD `u_name` VARCHAR( 20 ) NOT NULL DEFAULT '' AFTER `email` ,
					ADD `u_cardid` VARCHAR( 30 ) NOT NULL DEFAULT '' AFTER `u_name` ,
					ADD `u_cardtype` TINYINT( 4 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `u_cardid` ,
					ADD `u_namecheck` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `u_cardtype`
		",
	),
	'1.2.9.090401' => array(
		"CREATE TABLE IF NOT EXISTS `##__passport_buss` (
		`id` SMALLINT( 6 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 100 ) NOT NULL DEFAULT ' ',
		`descrip` VARCHAR( 255 ) NOT NULL DEFAULT ' ',
		`istop` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
		`upid` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0',
		`ids` VARCHAR( 255 ) NOT NULL DEFAULT ' ',
		`orderkey` INT( 0 ) NOT NULL DEFAULT '0'
		) ENGINE = MYISAM",
		"ALTER TABLE `##__passport` ADD `u_bid` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `u_pid` ,
		ADD  `u_position` VARCHAR( 100 ) NOT NULL DEFAULT '' AFTER `u_bid`",
	),
);
?>