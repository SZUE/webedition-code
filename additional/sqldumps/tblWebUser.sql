CREATE TABLE tblWebUser (
  ID bigint(20) NOT NULL auto_increment,
  Username varchar(255) NOT NULL default '',
  `Password` varchar(255) NOT NULL default '',
  Anrede_Anrede varchar(200) NOT NULL default '',
  Anrede_Titel varchar(200) NOT NULL default '',
  Forename varchar(128) NOT NULL default '',
  Surname varchar(128) NOT NULL default '',
  Kontakt_Addresse1 varchar(255) NOT NULL default '',
  Kontakt_Addresse2 varchar(255) NOT NULL default '',
  Kontakt_Bundesland varchar(200) NOT NULL default '',
  Kontakt_Land varchar(255) NOT NULL default '',
  Kontakt_Tel1 varchar(64) NOT NULL default '',
  Kontakt_Tel2 varchar(64) NOT NULL default '',
  Kontakt_Tel3 varchar(64) NOT NULL default '',
  Kontakt_Email varchar(128) NOT NULL default '',
  Kontakt_Homepage varchar(128) NOT NULL default '',
  LoginDenied tinyint(1) NOT NULL default '0',
  MemberSince int(10) NOT NULL default '0',
  LastLogin int(10) NOT NULL default '0',
  LastAccess int(10) NOT NULL default '0',
  AutoLoginDenied tinyint(1) NOT NULL default '0',
  AutoLogin tinyint(1) NOT NULL default '0',
  ParentID bigint(20) NOT NULL default '0',
  Path varchar(255) default NULL,
  IsFolder tinyint(1) default NULL,
  Icon varchar(255) default NULL,
  `Text` varchar(255) default NULL,
  Newsletter_Ok varchar(200) NOT NULL default '',
  Newsletter_HTMLNewsletter varchar(200) NOT NULL default '',
  Gruppe varchar(200) NOT NULL default '',
  PRIMARY KEY  (ID),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM;
