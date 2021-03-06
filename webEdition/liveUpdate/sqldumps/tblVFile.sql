CREATE TABLE ###TBLPREFIX###tblVFile (
  ID int unsigned NOT NULL auto_increment,
  ParentID int unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  ContentType enum('folder','text/weCollection') NOT NULL default 'text/weCollection',
  IsFolder tinyint unsigned NOT NULL default '0',
  Path varchar(800) NOT NULL default '',
  CreatorID int unsigned NOT NULL default '0',
  ModifierID int unsigned NOT NULL default '0',
  CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ModDate TIMESTAMP NOT NULL,
  RestrictOwners tinyint unsigned NOT NULL default '0',
  Owners varchar(255) NOT NULL default '',
  OwnersReadOnly text NOT NULL,
  remTable enum('tblFile','tblObjectFiles') NOT NULL default 'tblFile',
  remCT varchar(255) NOT NULL default '',
  remClass TEXT NOT NULL default '',
  DefaultDir int unsigned NOT NULL default '0',
  IsDuplicates tinyint unsigned NOT NULL default '1',
  InsertRecursive tinyint unsigned NOT NULL default '1',
  PRIMARY KEY (ID),
  KEY Path (Path(250),IsFolder),
  KEY ParentID(ParentID)
) ENGINE=MyISAM;