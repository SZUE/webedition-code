CREATE TABLE ###TBLPREFIX###tblbannerviews (
  viewid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  ID bigint(20) unsigned NOT NULL default '0',
  `Timestamp` bigint(10) unsigned default NULL,
  IP varchar(30) NOT NULL default '',
  Referer varchar(255) NOT NULL default '',
  DID bigint(20) unsigned NOT NULL default '0',
  Page varchar(255) NOT NULL default '',
  PRIMARY KEY (`viewid`)
) ENGINE=MyISAM;
