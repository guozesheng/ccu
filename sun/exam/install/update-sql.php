<?php
//update sql file from version 0.8
if (!defined('install_safe')) {
	exit('Forbidden');
}

$_update_sql = array(
	'0.8.1' => array(
		'ALTER TABLE `##__exam_shijuan` ADD `timemix` TEXT NOT NULL AFTER `fenmix`, ADD `lvlmin` SMALLINT( 6 ) NOT NULL DEFAULT \'0\' AFTER `timemix` ,
ADD `lvlmax` VARCHAR( 6 ) NOT NULL DEFAULT \'0\' AFTER `lvlmin` ;',
		'ALTER TABLE `##__exam_shiti` ADD `ext1` TINYINT( 1 ) NOT NULL ,
ADD `ext2` VARCHAR( 250 ) NOT NULL DEFAULT \'\';',
	),
	'0.8.5' => array(
		'ALTER TABLE `##__exam_kaojuan` CHANGE `content` `content` MEDIUMTEXT NOT NULL ',
		'ALTER TABLE `##__exam_admin` CHANGE `userid` `userid` VARCHAR( 20 )  NOT NULL DEFAULT \'\',
		CHANGE `name` `name` VARCHAR( 40 ) NOT NULL DEFAULT \'\'',
		'ALTER TABLE `##__exam_shiti` CHANGE `ext1` `ext1` TINYINT( 1 ) NOT NULL DEFAULT \'0\'',
	),
	'0.9.0' => array(
		'ALTER TABLE `##__exam_shijuan` ADD `overdescrip` TEXT NOT NULL AFTER `descrip` '
	),
	'0.9.5' => array(
		'ALTER TABLE `##__exam_admin` 
		CHANGE `uid` `uid` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ',
		
		"ALTER TABLE `##__exam_kaojuan` 
		CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		CHANGE `shijuan` `shijuan` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `author` `author` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `start` `start` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `end` `end` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `time` `time` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_shiti` 
		CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		CHANGE `time` `time` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `tiku` `unuse` TINYINT( 1 ) NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_syslogs` 
		CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		CHANGE `adminid` `adminid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `dotime` `dotime` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_shijuan` 
		CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		CHANGE `start` `start` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `end` `end` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `time` `time` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `money` `money` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `tiku` `tiku` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `class` `class` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `lvlmax` `lvlmix` TEXT NOT NULL 
		",
		
		"ALTER TABLE `##__exam_xt` CHANGE `sjid` `sjid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `tiku` `tiku` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
		CHANGE `stid` `stid` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_xt` ADD `orderkey` INT( 11 ) NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_shijuan` ADD `del` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `ktimes`",
		
		"DROP TABLE `##__exam_syslogs` ",
	),
	'0.9.6' => array(
		"ALTER TABLE `##__exam_shijuan` ADD `mtype` TINYINT( 4 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `money`",
	),
	'1.2.5' => array(
		"ALTER TABLE `##__exam_shijuan`
		ADD `ltime` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `ktimes` ,
		ADD `jrate` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `ltime` ,
		ADD `jnum` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `jrate` ,
		ADD `jmtype` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `jnum` ,
		ADD `allowgroups` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `jmtype`",
		"ALTER TABLE `##__exam_admin`
		ADD `tikus` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `privkey`",
	),
	'1.2.7' => array(
		"ALTER TABLE `##__exam_shijuan` ADD `allowclass` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `allowgroups` ,
		ADD `allowclasses` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `allowclass` ,
		ADD `andor` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `allowclasses` ;",
		"ALTER TABLE `##__exam_shijuan` ADD `descripmix` TEXT NOT NULL AFTER `timemix` ",
	),
	'1.2.8' => array(
		"ALTER TABLE `##__exam_shiti` ADD `kf` SMALLINT( 6 ) NOT NULL DEFAULT '0' AFTER `fen`",
		"ALTER TABLE `##__exam_shiti` ADD `upid` INT( 11 ) NOT NULL DEFAULT '0' AFTER `type` ",
		"ALTER TABLE `##__exam_shijuan` ADD `kfmix` TEXT NOT NULL AFTER `lvlmix`,
										ADD `fensize` SMALLINT( 6 ) NOT NULL DEFAULT '1' AFTER `kfmix`",
		"ALTER TABLE `##__exam_shiti` ADD `orderkey` INT( 11 ) NOT NULL DEFAULT '0' AFTER `time`",
		"ALTER TABLE `##__exam_kaojuan` ADD `damix` TEXT NOT NULL AFTER `content`",
		"ALTER TABLE `##__exam_kaojuan` ADD `lastupdate` INT( 11 ) NOT NULL DEFAULT '0'",
		
		"ALTER TABLE `##__exam_tiku` ADD `allowpush` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `storetable` ",
		"CREATE TABLE `##__exam_errorreport` (
		  `id` int(11) NOT NULL auto_increment,
		  `tiku` int(11) NOT NULL default '0',
		  `stid` int(11) NOT NULL default '0',
		  `author` int(11) NOT NULL default '0',
		  `title` varchar(255) NOT NULL default '',
		  `why` text NOT NULL,
		  `time` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM ;",
	),
	'1.2.9.081118' => array(
		"ALTER TABLE `##__exam_shiti` ADD `author` INT( 11 ) NOT NULL DEFAULT '0' AFTER `unuse`",
	),
	'1.3.0.081215' => array(
		"ALTER TABLE `##__exam_shijuan` ADD `cjbm` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `protect` ,
										ADD `kjdata` MEDIUMTEXT NOT NULL AFTER `cjbm` ",
		"ALTER TABLE `##__exam_xt` ADD `fenzhi` SMALLINT( 6 ) NOT NULL DEFAULT '0',
								ADD `koufenzhi` SMALLINT( 6 ) NOT NULL DEFAULT '0';",
	),
	'1.3.1.090101' => array(
		"ALTER TABLE `##__exam_class` ADD `cktimes` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `name` ,
									ADD `oknum` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `cktimes` ,
									ADD `okban` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `oknum`",
		"ALTER TABLE `##__exam_shiti` ADD `adminid` INT( 10 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `author`",
		"ALTER TABLE `##__exam_shijuan` ADD `adminid` INT( 10 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `jmtype` ,
									ADD `okrate` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `adminid` ,
									ADD `okban` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `okrate` ,
									ADD `helplink` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `okban` ,
									ADD `dotimes` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `helplink` ,
									ADD `totals` SMALLINT( 6 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `dotimes` ,
									ADD `dtuptime` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `dotimes` ,
									ADD `showadmin` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `totals` ,
									ADD `dostyle` VARCHAR( 20 ) NOT NULL DEFAULT 'ppframe' AFTER `showadmin` ,
									ADD `allowcolleges` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `dostyle` ,
									ADD `allowpros` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `allowcolleges` ,
									ADD `allowgrades` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `allowpros`"
	),
	'1.3.2.090401' => array(
		"ALTER TABLE `##__exam_shijuan` ADD `allowbids` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `allowclasses`",
	),
);
?>