###UPDATEDROPCOL(Icon,###TBLPREFIX###tblWebUser)###
/* query separator */
###UPDATEDROPCOL(Text,###TBLPREFIX###tblWebUser)###
/* query separator */
###UPDATEDROPCOL(Path,###TBLPREFIX###tblWebUser)###
/* query separator */
###UPDATEDROPCOL(IsFolder,###TBLPREFIX###tblWebUser)###
/* query separator */
###UPDATEDROPCOL(ParentID,###TBLPREFIX###tblWebUser)###
/* query separator */

CREATE TABLE ###TBLPREFIX###tblWebUser (
  ID bigint(20) unsigned NOT NULL auto_increment,
  Username varchar(255) NOT NULL default '',
  `Password` varchar(255) NOT NULL default '',
  Forename varchar(128) NOT NULL default '',
  Surname varchar(128) NOT NULL default '',
  LoginDenied tinyint(1) unsigned NOT NULL default '0',
  MemberSince int(10) unsigned NOT NULL default '0',
  LastLogin int(10) unsigned NOT NULL default '0',
  LastAccess int(10) unsigned NOT NULL default '0',
  AutoLoginDenied tinyint(1) unsigned NOT NULL default '0',
  AutoLogin tinyint(1) unsigned NOT NULL default '0',
  ModifyDate int(10) unsigned NOT NULL default '0',
  ModifiedBy enum('','backend','frontend','external') NOT NULL default '',
  Newsletter_Ok enum('','ja','0','1','2') NOT NULL,
  PRIMARY KEY  (ID),
  UNIQUE KEY Username (Username),
  KEY Surname (Surname(3))
)  ENGINE=MyISAM;