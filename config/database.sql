-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `membership_fee` text NULL,
  `membership_since` text NULL,
  `membership_until` text NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_member_fees`
-- 

CREATE TABLE `tl_member_fees` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `year` varchar(4) NOT NULL default '',
  `fee` varchar(32) NOT NULL default '0',
  `status` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
