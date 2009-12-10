DROP TABLE IF EXISTS `blacklist`;
CREATE TABLE IF NOT EXISTS `blacklist` (
  `blacklist_id` int(10) NOT NULL auto_increment,
  `domain_id` int(10) NOT NULL,
  PRIMARY KEY  (`blacklist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `object`;
CREATE TABLE IF NOT EXISTS `object` (
  `object_id` int(10) NOT NULL auto_increment,
  `date_create` int(11) NOT NULL,
  `redirect` varchar(12) collate utf8_bin NOT NULL,
  `name` varchar(32) collate utf8_bin default NULL,
  `url` mediumtext collate utf8_bin,
  `block` text character set utf8 collate utf8_unicode_ci,
  `wrap` tinyint(1) NOT NULL default '1',
  `php` tinyint(1) NOT NULL default '0',
  `hash` varchar(96) character set utf8 collate utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`object_id`),
  UNIQUE KEY `hash` (`redirect`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `view`;
CREATE TABLE IF NOT EXISTS `view` (
  `object_id` int(10) NOT NULL,
  `date_view` int(11) NOT NULL,
  `ip_address` varchar(20) collate utf8_unicode_ci NOT NULL,
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;