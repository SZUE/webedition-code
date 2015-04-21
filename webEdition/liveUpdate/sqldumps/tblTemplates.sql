CREATE TABLE ###TBLPREFIX###tblTemplates (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  Icon enum('folder.gif','we_template.gif') NOT NULL default 'we_template.gif',
  IsFolder tinyint(1) unsigned NOT NULL default '0',
  ContentType enum('folder','text/weTmpl') NOT NULL default 'text/weTmpl',
  CreationDate int(11) unsigned NOT NULL default '0',
  ModDate int(11) unsigned NOT NULL default '0',
  RebuildDate int(11) unsigned NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  Filehash char(40) NOT NULL default '',
  Filename varchar(64) NOT NULL default '',
  Extension enum('','.tmpl') NOT NULL default '',
  ClassName ENUM('we_folder','we_template') NOT NULL default 'we_template',
  Owners varchar(255) default NULL,
  RestrictOwners tinyint(1) unsigned default NULL,
  OwnersReadOnly text,
  CreatorID int(11) unsigned NOT NULL default '0',
  ModifierID int(11) unsigned NOT NULL default '0',
  MasterTemplateID int(11) unsigned NOT NULL default '0',
  IncludedTemplates varchar(255) NOT NULL default '',
  CacheType enum('','none','tag','document','full') NOT NULL default 'none',
  CacheLifeTime int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY ParentID (ParentID,Filename(3)),
  KEY MasterTemplateID (MasterTemplateID),
  KEY IncludedTemplates (IncludedTemplates)
)

/* query separator */
###UPDATEONLY###UPDATE ###TBLPREFIX###tblTemplates SET Icon="we_template.gif" WHERE IsFolder=0;
