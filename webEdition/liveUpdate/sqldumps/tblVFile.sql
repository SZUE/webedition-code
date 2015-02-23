CREATE TABLE ###TBLPREFIX###tblVFile (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  IsFolder tinyint(1) unsigned NOT NULL default '0',
  `Path` varchar(255) NOT NULL default '',
  CreatorID int(11) unsigned NOT NULL default '0',
  ModifierID int(11) unsigned NOT NULL default '0',
  CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModDate TIMESTAMP NOT NULL,
  RestrictOwners tinyint(1) unsigned NOT NULL default '0',
  Owners varchar(255) NOT NULL default '',
  OwnersReadOnly text NOT NULL,
	PRIMARY KEY  (ID),
  KEY Path (Path(30),IsFolder),
	KEY ParentID(ParentID)
) ENGINE=MyISAM;
