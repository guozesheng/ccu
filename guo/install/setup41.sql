CREATE TABLE IF NOT EXISTS `#@__accounts` (
  `id` smallint(8) NOT NULL auto_increment,
  `atype` varchar(10) NOT NULL,
  `amoney` double NOT NULL,
  `abank` smallint(6) NOT NULL,
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `apeople` varchar(10) NOT NULL,
  `atext` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__area` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `reid` int(10) unsigned NOT NULL default '0',
  `disorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3118 ;

CREATE TABLE IF NOT EXISTS `#@__bank` (
  `id` smallint(6) NOT NULL auto_increment,
  `bank_name` varchar(30) NOT NULL,
  `bank_money` float NOT NULL,
  `bank_account` varchar(30) NOT NULL,
  `bank_default` smallint(6) NOT NULL,
  `bank_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__basic` (
  `id` smallint(6) NOT NULL auto_increment,
  `cp_number` varchar(15) NOT NULL,
  `cp_tm` varchar(15) NOT NULL,
  `cp_name` varchar(50) NOT NULL,
  `cp_isdrop` int(11) NOT NULL,
  `cp_dropdate` date NOT NULL,
  `cp_gg` varchar(50) NOT NULL,
  `cp_categories` smallint(6) NOT NULL,
  `cp_categories_down` smallint(6) NOT NULL,
  `cp_dwname` smallint(6) NOT NULL,
  `cp_jj` float NOT NULL,
  `cp_sale` float NOT NULL,
  `cp_saleall` float NOT NULL,
  `cp_sdate` date NOT NULL default '0000-00-00',
  `cp_edate` date NOT NULL default '0000-00-00',
  `cp_gys` varchar(50) NOT NULL,
  `cp_helpword` varchar(50) NOT NULL,
  `cp_bz` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `#@__borrow` (
  `id` int(11) NOT NULL auto_increment,
  `askid` int(11) NOT NULL,
  `basic_id` varchar(32) NOT NULL,
  `boss_id` varchar(32) NOT NULL,
  `amount` int(11) NOT NULL,
  `borrow_t` date NOT NULL,
  `return_t` date NOT NULL,
  `is_return` int(11) NOT NULL,
  `comment` text character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='仪器借用表' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#@__borrow_ask` (
  `id` int(11) NOT NULL auto_increment,
  `basic_id` varchar(32) NOT NULL,
  `boss_id` varchar(32) NOT NULL,
  `amount` int(11) NOT NULL,
  `askdate` date NOT NULL,
  `asktime` date NOT NULL,
  `retime` date NOT NULL,
  `isallow` int(11) NOT NULL,
  `allowtime` date NOT NULL,
  `comment` text character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='等审批借用表' AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#@__boss` (
  `id` int(11) NOT NULL auto_increment,
  `boss` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `sex` varchar(5) NOT NULL COMMENT '性别',
  `phone` varchar(15) NOT NULL COMMENT '电话',
  `logindate` datetime NOT NULL,
  `loginip` varchar(15) NOT NULL,
  `errnumber` int(11) NOT NULL,
  `rank` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `#@__categories` (
  `id` smallint(6) NOT NULL auto_increment,
  `categories` varchar(50) NOT NULL,
  `reid` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `#@__config` (
  `id` int(11) NOT NULL,
  `config_name` varchar(30) NOT NULL,
  `config_mem` varchar(50) NOT NULL,
  `config_value` varchar(30) NOT NULL,
  `config_type` varchar(20) NOT NULL,
  `config_len` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

CREATE TABLE IF NOT EXISTS `#@__drop_ask` (
  `id` int(11) NOT NULL auto_increment,
  `basic_id` varchar(32) character set gbk NOT NULL,
  `boss_id` varchar(32) character set gbk NOT NULL,
  `askdate` date NOT NULL,
  `isallow` int(11) NOT NULL,
  `allowdate` date NOT NULL,
  `allow_id` varchar(32) character set gbk NOT NULL,
  `comment` text character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='用户申请报废表' AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `#@__dw` (
  `id` smallint(6) NOT NULL auto_increment,
  `dwname` varchar(20) NOT NULL,
  `reid` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `#@__flink` (
  `ID` int(11) NOT NULL auto_increment,
  `sortrank` int(11) NOT NULL default '0',
  `url` varchar(100) NOT NULL default '',
  `webname` varchar(30) NOT NULL default '',
  `msg` varchar(250) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `logo` varchar(100) NOT NULL default '',
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `typeid` int(11) NOT NULL default '0',
  `ischeck` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#@__group` (
  `id` smallint(6) NOT NULL auto_increment,
  `groupname` varchar(30) NOT NULL,
  `sub` float NOT NULL default '10',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#@__guest` (
  `id` smallint(6) NOT NULL auto_increment,
  `g_name` varchar(10) NOT NULL,
  `g_sex` varchar(5) NOT NULL,
  `g_address` varchar(120) NOT NULL,
  `g_phone` varchar(15) NOT NULL,
  `g_qq` varchar(50) NOT NULL,
  `g_birthday` date NOT NULL default '0000-00-00',
  `g_card` varchar(50) NOT NULL,
  `g_group` smallint(6) NOT NULL,
  `g_people` varchar(20) NOT NULL,
  `g_dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#@__gys` (
  `id` smallint(6) NOT NULL auto_increment,
  `g_name` varchar(100) character set gb2312 NOT NULL,
  `g_address` varchar(120) character set gb2312 NOT NULL,
  `g_people` varchar(10) character set gb2312 NOT NULL,
  `g_phone` varchar(12) NOT NULL,
  `g_qq` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `#@__kc` (
  `id` smallint(6) NOT NULL auto_increment,
  `productid` varchar(15) NOT NULL,
  `number` int(11) NOT NULL,
  `labid` smallint(6) NOT NULL,
  `rdh` varchar(20) NOT NULL,
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__kcbackgys` (
  `id` smallint(6) NOT NULL auto_increment,
  `productid` varchar(15) NOT NULL,
  `number` int(11) NOT NULL,
  `labid` smallint(6) NOT NULL,
  `rdh` varchar(20) NOT NULL,
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__lab` (
  `id` smallint(6) NOT NULL auto_increment,
  `l_name` varchar(30) NOT NULL,
  `l_city` varchar(30) NOT NULL,
  `l_mang` varchar(10) NOT NULL,
  `l_default` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__mainkc` (
  `id` smallint(6) NOT NULL auto_increment,
  `p_id` varchar(15) NOT NULL,
  `l_id` smallint(6) NOT NULL,
  `d_id` varchar(20) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__menu` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `reid` int(10) NOT NULL,
  `rank` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=85 ;

CREATE TABLE IF NOT EXISTS `#@__recordline` (
  `id` int(11) NOT NULL auto_increment,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `userid` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=122 ;

CREATE TABLE IF NOT EXISTS `#@__repair_ask` (
  `id` int(11) NOT NULL auto_increment,
  `basic_id` varchar(32) character set gbk NOT NULL,
  `boss_id` varchar(32) character set gbk NOT NULL,
  `askdate` date NOT NULL,
  `isallow` int(11) NOT NULL,
  `allowdate` date NOT NULL,
  `comment` text character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='维修申请表' AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `#@__reportbackgys` (
  `id` smallint(6) NOT NULL auto_increment,
  `r_dh` varchar(20) NOT NULL,
  `r_people` varchar(10) NOT NULL,
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_status` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__reportrk` (
  `id` smallint(6) NOT NULL auto_increment,
  `r_dh` varchar(20) NOT NULL,
  `r_people` varchar(10) NOT NULL,
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_status` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__reportsale` (
  `id` smallint(6) NOT NULL auto_increment,
  `r_dh` varchar(20) NOT NULL,
  `r_people` varchar(10) NOT NULL,
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_status` smallint(6) NOT NULL default '0',
  `r_adid` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__reportsback` (
  `id` smallint(6) NOT NULL auto_increment,
  `r_dh` varchar(20) NOT NULL,
  `r_people` varchar(10) NOT NULL,
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_status` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__sale` (
  `id` smallint(6) NOT NULL auto_increment,
  `productid` varchar(15) NOT NULL,
  `number` int(11) NOT NULL,
  `rdh` varchar(20) NOT NULL,
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__saleback` (
  `id` smallint(6) NOT NULL auto_increment,
  `productid` varchar(15) NOT NULL,
  `number` int(11) NOT NULL,
  `rdh` varchar(20) NOT NULL,
  `dtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__staff` (
  `id` smallint(6) NOT NULL auto_increment,
  `s_name` varchar(10) NOT NULL,
  `s_address` varchar(120) NOT NULL,
  `s_phone` varchar(15) NOT NULL,
  `s_part` varchar(50) NOT NULL,
  `s_way` smallint(6) NOT NULL default '0',
  `s_money` float NOT NULL,
  `s_utype` smallint(6) NOT NULL,
  `s_duty` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#@__topmenu` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) character set gbk NOT NULL,
  `url` varchar(100) character set gbk NOT NULL,
  `flag` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

CREATE TABLE IF NOT EXISTS `#@__usertype` (
  `rank` smallint(6) NOT NULL,
  `typename` varchar(30) NOT NULL,
  `system` smallint(6) NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
