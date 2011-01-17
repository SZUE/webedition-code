CREATE TABLE tblbannerclicks (
  clickid bigint(20) NOT NULL AUTO_INCREMENT,
  ID bigint(20) NOT NULL default '0',
  `Timestamp` bigint(20) default NULL,
  IP varchar(30) NOT NULL default '',
  Referer varchar(255) NOT NULL default '',
  DID bigint(20) NOT NULL default '0',
  Page varchar(255) NOT NULL default '',
  PRIMARY KEY (`clickid`)
) ENGINE=MyISAM;
