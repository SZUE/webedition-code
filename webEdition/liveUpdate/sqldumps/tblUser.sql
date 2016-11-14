###ONCOL(Icon,###TBLPREFIX###tblUser) UPDATE ###TBLPREFIX###tblUser SET IsFolder=1 WHERE Type=1;###
/* query separator */
###UPDATEDROPCOL(Icon,###TBLPREFIX###tblUser)###
/* query separator */
###UPDATEDROPCOL(Portal,###TBLPREFIX###tblUser)###
/* query separator */
###UPDATEDROPCOL(Text,###TBLPREFIX###tblUser)###
/* query separator */
###ONCOL(UseSalt,###TBLPREFIX###tblUser) ALTER TABLE ###TBLPREFIX###tblUser CHANGE COLUMN `Type` `Typeold` tinyint NOT NULL;###
/* query separator */

CREATE TABLE ###TBLPREFIX###tblUser (
  ID smallint unsigned NOT NULL auto_increment,
  ParentID smallint unsigned NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  IsFolder tinyint unsigned NOT NULL default '0',
  `Type` enum('user','group','alias') NOT NULL default 'user',
  username varchar(255) NOT NULL default '',
  passwd TINYTEXT NOT NULL,
  LoginDenied tinyint unsigned NOT NULL default '0',
  Permissions text NOT NULL,
  ParentPerms tinyint unsigned NOT NULL default '0',
  Alias smallint unsigned NOT NULL default '0',
  CreatorID smallint unsigned NOT NULL default '0',
  CreateDate int unsigned NOT NULL default '0',
  ModifierID smallint unsigned NOT NULL default '0',
  ModifyDate int unsigned NOT NULL default '0',
  Ping timestamp NULL default NULL,
  workSpace TEXT NOT NULL default '',
  workSpaceDef TEXT NOT NULL default '',
  workSpaceTmp TEXT NOT NULL default '',
  workSpaceNav TEXT NOT NULL default '',
  workSpaceObj TEXT NOT NULL default '',
  workSpaceNwl TEXT NOT NULL default '',
  workSpaceCust TEXT NOT NULL default '',
  ParentWs tinyint unsigned NOT NULL default '0',
  ParentWst tinyint unsigned NOT NULL default '0',
  ParentWsn tinyint unsigned NOT NULL default '0',
  ParentWso tinyint unsigned NOT NULL default '0',
  ParentWsnl tinyint unsigned NOT NULL default '0',
  ParentWsCust tinyint unsigned NOT NULL default '0',
  Salutation varchar(32) NOT NULL default '',
  `First` tinytext NOT NULL default '',
  `Second` tinytext NOT NULL default '',
  Address tinytext NOT NULL default '',
  HouseNo varchar(11) NOT NULL default '',
  City tinytext NOT NULL default '',
  PLZ varchar(32) NOT NULL default '',
  `State` tinytext NOT NULL default '',
  Country tinytext NOT NULL default '',
  Tel_preselection varchar(11) NOT NULL default '',
  Telephone varchar(32) NOT NULL default '',
  Fax_preselection varchar(11) NOT NULL default '',
  Fax varchar(32) NOT NULL default '',
  Handy varchar(32) NOT NULL default '',
  Email tinytext NOT NULL default '',
  Description TEXT NOT NULL,
  PRIMARY KEY (ID),
  KEY Ping (Ping),
  KEY Alias (Alias),
  UNIQUE KEY username (username)
) ENGINE=MyISAM;
/* query separator */
###INSTALLONLY###INSERT INTO ###TBLPREFIX###tblUser SET ID=1,Text='admin',Path='/admin',username='admin',passwd='c0e024d9200b5705bc4804722636378a',Permissions='a:1:{s:13:"ADMINISTRATOR";i:1;}',CreateDate=UNIX_TIMESTAMP();
/* query separator */
###ONCOL(Typeold,###TBLPREFIX###tblUser)UPDATE ###TBLPREFIX###tblUser SET Type='group' WHERE Typeold=1;###
/* query separator */
###ONCOL(Typeold,###TBLPREFIX###tblUser)UPDATE ###TBLPREFIX###tblUser SET Type='alias' WHERE Typeold=2;###
/* query separator */
###UPDATEDROPCOL(UseSalt,###TBLPREFIX###tblUser)###
/* query separator */
###UPDATEDROPCOL(Typeold,###TBLPREFIX###tblUser)###
