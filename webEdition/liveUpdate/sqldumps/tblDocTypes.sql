CREATE TABLE ###TBLPREFIX###tblDocTypes (
  ID smallint(6) unsigned NOT NULL auto_increment,
  DocType varchar(64) NOT NULL default '',
  Extension varchar(16) NOT NULL default '',
  ParentID int(11) unsigned NOT NULL default '0',
  ParentPath varchar(255) NOT NULL default '',
  SubDir enum('0','1','2','3') NOT NULL default '0',
  TemplateID int(11) unsigned NOT NULL default '0',
  IsDynamic tinyint(1) unsigned NOT NULL default '0',
  IsSearchable tinyint(1) unsigned NOT NULL default '0',
  ContentTable varchar(32) NOT NULL default '',
  JavaScript text NOT NULL,
  Notify text NOT NULL,
  NotifyTemplateID int(11) unsigned NOT NULL default '0',
  NotifySubject varchar(64) NOT NULL default '',
  NotifyOnChange tinyint(1) unsigned NOT NULL default '0',
  LockID int(11) unsigned NOT NULL default '0',
  Templates varchar(255) NOT NULL default '',
  Category varchar(255) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY  (ID)
) ENGINE=MyISAM;