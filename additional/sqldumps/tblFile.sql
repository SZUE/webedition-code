CREATE TABLE ###TBLPREFIX###tblFile (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned NOT NULL default '0',
  `Text` varchar(255) NOT NULL default '',
  Icon enum('pdf.gif','zip.gif','word.gif','excel.gif','powerpoint.gif','prog.gif','image.gif','html.gif','we_dokument.gif','we_template.gif','javascript.gif','css.gif','htaccess.gif''link.gif','folder.gif','class_folder.gif','flashmovie.gif','quicktime.gif','object.gif','objectFile.gif') NOT NULL,
  IsFolder tinyint(1) unsigned NOT NULL default '0',
  ContentType varchar(32) NOT NULL default '',
  CreationDate int(11) unsigned NOT NULL default '0',
  ModDate int(11) unsigned NOT NULL default '0',
  `Path` varchar(255) NOT NULL default '',
  TemplateID int(11) unsigned NOT NULL default '0',
  temp_template_id int(11) unsigned NOT NULL default '0',
  Filename varchar(255) NOT NULL default '',
  Extension varchar(16) NOT NULL default '',
  IsDynamic tinyint(1) unsigned NOT NULL default '0',
  IsSearchable tinyint(1) unsigned NOT NULL default '0',
  DocType smallint(6) NOT NULL,
  temp_doc_type smallint(6) NOT NULL,
  ClassName varchar(64) NOT NULL default '',
  Category text NULL default NULL,
  temp_category text NULL default NULL,
  Deleted int(11) unsigned NOT NULL default '0',
  Published int(11) unsigned NOT NULL default '0',
  CreatorID bigint(20) unsigned NOT NULL default '0',
  ModifierID bigint(20) unsigned NOT NULL default '0',
  RestrictOwners tinyint(1) unsigned NOT NULL default '0',
  Owners varchar(255) NOT NULL default '',
  OwnersReadOnly text NOT NULL,
  documentArray text NOT NULL,
  `Language` varchar(5) NOT NULL default '',
  WebUserID bigint(20) unsigned NOT NULL default '0',
  listview tinyint(1) unsigned NOT NULL default '0',
  InGlossar tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY Path (Path),
  KEY WebUserID (WebUserID)
) ENGINE=MyISAM;
