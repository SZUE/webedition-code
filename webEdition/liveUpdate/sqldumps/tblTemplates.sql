###UPDATEDROPCOL(Icon,###TBLPREFIX###tblTemplates)###
/* query separator */
###UPDATEDROPCOL(CacheLifeTime,###TBLPREFIX###tblTemplates)###
/* query separator */
###UPDATEDROPCOL(CacheType,###TBLPREFIX###tblTemplates)###
/* query separator */
CREATE TABLE ###TBLPREFIX###tblTemplates (
  ID int unsigned NOT NULL auto_increment,
  ParentID int unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  IsFolder tinyint unsigned NOT NULL default '0',
  ContentType enum('folder','text/weTmpl') NOT NULL default 'text/weTmpl',
  CreationDate int unsigned NOT NULL default '0',
  ModDate int unsigned NOT NULL default '0',
  RebuildDate int unsigned NOT NULL default '0',
  Path varchar(1000) NOT NULL default '',
  Filehash char(40) NOT NULL default '',
  Filename varchar(64) NOT NULL default '',
  Extension enum('','.tmpl') NOT NULL default '',
  ClassName ENUM('we_folder','we_template') NOT NULL default 'we_template',
  Owners varchar(255) default NULL,
  RestrictOwners tinyint unsigned default NULL,
  OwnersReadOnly text,
  CreatorID int unsigned NOT NULL default '0',
  ModifierID int unsigned NOT NULL default '0',
  MasterTemplateID int unsigned NOT NULL default '0',
  IncludedTemplates varchar(255) NOT NULL default '',
  PRIMARY KEY  (ID),
	KEY Path(Path(250)),
  UNIQUE KEY ParentID (ParentID,Filename,Extension),
  KEY MasterTemplateID (MasterTemplateID),
  KEY IncludedTemplates (IncludedTemplates)
) ENGINE=MyISAM;
