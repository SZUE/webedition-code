CREATE TABLE tblLock (
  ID bigint(20) NOT NULL default '0',
  UserID bigint(20) NOT NULL default '0',
  sessionID varchar(64) NOT NULL default '',
  `lock` datetime NOT NULL,
  tbl varchar(32) NOT NULL default '',
  PRIMARY KEY (ID,tbl),
  KEY UserID (UserID,sessionID),
  KEY `lock` (`lock`)
) ENGINE=MyISAM;
