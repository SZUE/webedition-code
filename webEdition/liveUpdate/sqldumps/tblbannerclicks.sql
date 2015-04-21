CREATE TABLE ###TBLPREFIX###tblbannerclicks (
  clickid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  ID bigint(20) unsigned NOT NULL default '0',
  `Timestamp` int(10) unsigned default NULL,
  IP varchar(40) NOT NULL default '',
  Referer varchar(255) NOT NULL default '',
  DID int(11) unsigned NOT NULL default '0',
  Page varchar(255) NOT NULL default '',
  PRIMARY KEY (`clickid`),
	KEY ID (ID,Page,`Timestamp`)
)
