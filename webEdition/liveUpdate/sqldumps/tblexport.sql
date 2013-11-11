CREATE TABLE ###TBLPREFIX###tblexport (
  ID smallint(6) unsigned NOT NULL auto_increment,
  ParentID smallint(6) unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  Icon ENUM('folder.gif','link.gif') NOT NULL default 'link.gif',
  IsFolder tinyint(1) unsigned NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  ExportTo enum('','local','server') NOT NULL default '',
  ServerPath varchar(255) NOT NULL default '',
  Filename varchar(255) NOT NULL default '',
  Selection enum('auto','manual') NOT NULL default 'auto',
  SelectionType enum('doctype','classname') NOT NULL default 'doctype',
  DocType smallint(6) NOT NULL,
  Folder smallint(6) unsigned NOT NULL default '0',
  ClassName varchar(255) NOT NULL default '',
  Categorys text NOT NULL,
  selDocs text NOT NULL,
  selTempl text NOT NULL,
  selObjs text NOT NULL,
  selClasses text NOT NULL,
  HandleDefTemplates tinyint(1) unsigned NOT NULL default '0',
  HandleDocIncludes tinyint(1) unsigned NOT NULL default '0',
  HandleObjIncludes tinyint(1) unsigned NOT NULL default '0',
  HandleDocLinked tinyint(1) unsigned NOT NULL default '0',
  HandleDefClasses tinyint(1) unsigned NOT NULL default '0',
  HandleObjEmbeds tinyint(1) unsigned NOT NULL default '0',
  HandleDoctypes tinyint(1) unsigned NOT NULL default '0',
  HandleCategorys tinyint(1) unsigned NOT NULL default '0',
  ExportDepth tinyint(3) NOT NULL default '0',
  HandleOwners tinyint(1) unsigned NOT NULL default '0',
  HandleNavigation tinyint(1) unsigned NOT NULL default '0',
  HandleThumbnails tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID)
) ENGINE=MyISAM;
