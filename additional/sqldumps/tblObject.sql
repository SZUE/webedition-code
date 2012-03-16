CREATE TABLE ###TBLPREFIX###tblObject (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned NOT NULL default '0',
  strOrder text NOT NULL,
  `Text` varchar(255) NOT NULL default '',
  Icon varchar(64) NOT NULL default '',
  IsFolder tinyint(4) unsigned NOT NULL default '0',
  ContentType enum('object') NOT NULL default 'object',
  CreationDate int(11) unsigned NOT NULL default '0',
  ModDate int(11) unsigned NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  CreatorID bigint(20) unsigned NOT NULL default '0',
  ModifierID bigint(20) unsigned NOT NULL default '0',
  RestrictOwners tinyint(1) unsigned NOT NULL default '0',
  Owners varchar(255) NOT NULL default '',
  OwnersReadOnly text NOT NULL,
  RestrictUsers tinyint(1) unsigned NOT NULL default '0',
  Users varchar(255) NOT NULL default '',
  UsersReadOnly text NOT NULL,
  DefaultCategory text NOT NULL,
  DefaultParentID bigint(20) unsigned NOT NULL default '0',
  DefaultText varchar(255) NOT NULL default '',
  DefaultValues longtext NOT NULL,
  DefaultDesc varchar(255) NOT NULL default '',
  DefaultTitle varchar(255) NOT NULL default '',
  DefaultKeywords varchar(255) NOT NULL default '',
  DefaultUrl varchar(255) NOT NULL default '',
  DefaultUrlfield0 varchar(255) NOT NULL DEFAULT '_',
  DefaultUrlfield1 varchar(255) NOT NULL DEFAULT '_',
  DefaultUrlfield2 varchar(255) NOT NULL DEFAULT '_',
  DefaultUrlfield3 varchar(255) NOT NULL DEFAULT '_',
  DefaultTriggerID bigint(20) unsigned NOT NULL default '0',
  ClassName varchar(64) NOT NULL default '',
  Workspaces varchar(1000) NOT NULL default '',
  DefaultWorkspaces varchar(1000) NOT NULL default '',
  Templates varchar(255) NOT NULL default '',
  CacheType enum('','none','tag','document','full') NOT NULL default 'none',
  CacheLifeTime int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY Path (Path),
  KEY IsFolder (IsFolder)
) ENGINE=MyISAM;
