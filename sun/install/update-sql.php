<?php
if (!defined('install_safe')) {
	exit('Forbidden');
}
//update sql array
$_update_sql = array(
	'0.9.5' => array(
		"CREATE TABLE IF NOT EXISTS `##__frame_syslogs` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `adminid` int(11) unsigned NOT NULL default '0',
		  `adminname` varchar(20) NOT NULL default '',
		  `module` varchar(20) NOT NULL,
		  `dotype` varchar(60) NOT NULL default '',
		  `doaction` varchar(20) NOT NULL default '',
		  `identifyid` varchar(20) NOT NULL default '',
		  `dourl` varchar(250) NOT NULL default '',
		  `dotime` int(11) unsigned NOT NULL default '0',
		  `doip` varchar(20) NOT NULL default '',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM",
		
	),
	'1.2.9.090101' => array(
		"ALTER TABLE `##__frame_admin` ADD `u_lastlogin` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `loginban` ,
			ADD `u_lastlogip` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `u_lastlogin` ",
		"ALTER TABLE `##__frame_admin` ADD `u_discript` TEXT NOT NULL ",
	),
);
?>