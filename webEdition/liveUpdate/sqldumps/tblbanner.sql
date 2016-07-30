###UPDATEDROPCOL(Icon,###TBLPREFIX###tblbanner)###
/* query separator */
CREATE TABLE ###TBLPREFIX###tblbanner (
  ID bigint unsigned NOT NULL auto_increment,
  ParentID bigint unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  Path varchar(255) NOT NULL default '',
  IsFolder tinyint unsigned NOT NULL default '0',
  CreatorID int unsigned NOT NULL default '0',
  CreateDate int unsigned NOT NULL default '0',
  ModifierID int unsigned NOT NULL default '0',
  ModifyDate int unsigned NOT NULL default '0',
  bannerID bigint unsigned NOT NULL default '0',
  bannerUrl varchar(255) NOT NULL default '',
  bannerIntID int unsigned NOT NULL default '0',
  IntHref tinyint unsigned NOT NULL default '0',
  maxShow int unsigned NOT NULL default '0',
  maxClicks int unsigned NOT NULL default '0',
  IsDefault tinyint unsigned NOT NULL default '0',
  clickPrice double NOT NULL default '0',
  showPrice double NOT NULL default '0',
  StartOk tinyint unsigned NOT NULL default '0',
  EndOk tinyint unsigned NOT NULL default '0',
  StartDate int unsigned NOT NULL default '0',
  EndDate int unsigned NOT NULL default '0',
  FileIDs varchar(255) NOT NULL default '',
  FolderIDs varchar(255) NOT NULL default '',
  CategoryIDs text NOT NULL,
  DoctypeIDs text NOT NULL,
  IsActive tinyint unsigned NOT NULL default '0',
  clicks int unsigned NOT NULL default '0',
  views int unsigned NOT NULL default '0',
  Customers varchar(255) NOT NULL default '',
  TagName varchar(255) NOT NULL default '',
  weight tinyint unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
	KEY weight (weight),
	KEY run (StartOk,EndOk,StartDate,EndDate,maxShow,maxClicks,TagName),
	KEY ParentID (ParentID),
	KEY Path (Path)
) ENGINE=MyISAM;