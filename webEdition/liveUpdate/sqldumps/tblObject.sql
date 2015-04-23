CREATE TABLE ###TBLPREFIX###tblObject (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned NOT NULL default '0',
  strOrder text NOT NULL,
  `Text` varchar(255) NOT NULL default '',
  Icon ENUM('object.gif') NOT NULL default 'object.gif',
  IsFolder tinyint(4) unsigned NOT NULL default '0',
  ContentType enum('object') NOT NULL default 'object',
  CreationDate int(11) unsigned NOT NULL default '0',
  ModDate int(11) unsigned NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  CreatorID int(11) unsigned NOT NULL default '0',
  ModifierID int(11) unsigned NOT NULL default '0',
  RestrictOwners tinyint(1) unsigned NOT NULL default '0',
  Owners varchar(255) NOT NULL default '',
  OwnersReadOnly text NOT NULL,
  RestrictUsers tinyint(1) unsigned NOT NULL default '0',
  Users varchar(255) NOT NULL default '',
  UsersReadOnly text NOT NULL,
  DefaultCategory text NOT NULL,
  DefaultParentID int(11) unsigned NOT NULL default '0',
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
  DefaultTriggerID int(11) unsigned NOT NULL default '0',
  ClassName enum('we_object') NOT NULL default 'we_object',
  Workspaces varchar(1000) NOT NULL default '',
  DefaultWorkspaces varchar(1000) NOT NULL default '',
  Templates varchar(255) NOT NULL default '',
  CacheType enum('','none','tag','document','full') NOT NULL default 'none',
  CacheLifeTime tinyint(5) unsigned NOT NULL default '0',
  PRIMARY KEY (ID),
  KEY Path (Path),
  KEY IsFolder (IsFolder)
) ENGINE=MyISAM;