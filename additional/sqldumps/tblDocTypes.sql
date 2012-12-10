CREATE TABLE tblDocTypes (
  ID tinyint  NOT NULL IDENTITY(1,1),
  DocType varchar(64) NOT NULL default '',
  Extension varchar(10) NOT NULL default '',
  ParentID int  NOT NULL default '0',
  ParentPath varchar(255) NOT NULL default '',
  SubDir int  NOT NULL default '0',
  TemplateID int  NOT NULL default '0',
  IsDynamic tinyint  NOT NULL default '0',
  IsSearchable tinyint  NOT NULL default '0',
  ContentTable varchar(32) NOT NULL default '',
  JavaScript text NOT NULL,
  Notify text NOT NULL,
  NotifyTemplateID int  NOT NULL default '0',
  NotifySubject varchar(64) NOT NULL default '',
  NotifyOnChange tinyint  NOT NULL default '0',
  LockID int  NOT NULL default '0',
  Templates varchar(255) NOT NULL default '',
  Deleted int  NOT NULL default '0',
  Category varchar(255) default NULL,
  Language varchar(5) default NULL,
  PRIMARY KEY  (ID)
) 
