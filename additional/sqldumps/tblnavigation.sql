CREATE TABLE tblnavigation (
  ID bigint(20) NOT NULL auto_increment,
  ParentID bigint(20) NOT NULL default '0',
  Path varchar(255) NOT NULL default '',
  Published int(11) NOT NULL DEFAULT '1',
  Text varchar(255) NOT NULL default '',
  Display varchar(255) NOT NULL default '',
  ContentType varchar(255) NOT NULL default 'weNavigation',
  Icon varchar(32) NOT NULL default '0',
  IsFolder tinyint(4) NOT NULL default '0',
  TitleField varchar(255) NOT NULL default '',
  IconID bigint(20) NOT NULL default '0',
  Selection varchar(32) NOT NULL default '',
  LinkID bigint(20) NOT NULL default '0',
  CurrentOnUrlPar tinyint(1) NOT NULL DEFAULT '0',
  CurrentOnAnker tinyint(1) NOT NULL DEFAULT '0',
  SelectionType varchar(32) NOT NULL default '',
  FolderID bigint(20) NOT NULL default '0',
  DocTypeID bigint(20) NOT NULL default '0',
  ClassID bigint(20) NOT NULL default '0',
  Categories text NOT NULL,
  Sort text NOT NULL,
  ShowCount int(11) NOT NULL default '0',
  Ordn int(11) NOT NULL default '0',
  Depended tinyint(4) NOT NULL default '0',
  WorkspaceID bigint(20) NOT NULL default '-1',
  CatParameter varchar(255) NOT NULL default '',
  Parameter varchar(255) NOT NULL default '',
  LinkSelection varchar(255) NOT NULL default '',
  Url varchar(255) NOT NULL default '',
  UrlID bigint(20) NOT NULL default '0',
  Charset varchar(255) NOT NULL default '',
  Attributes text NOT NULL,
  FolderSelection varchar(32) NOT NULL default '',
  FolderWsID bigint(20) NOT NULL default '0',
  FolderParameter varchar(255) NOT NULL default '',
  FolderUrl varchar(255) NOT NULL default '',
  LimitAccess tinyint(4) NOT NULL default '0',
  AllCustomers tinyint(4) NOT NULL default '1',
  ApplyFilter tinyint(4) NOT NULL default '0',
  Customers text NOT NULL,
  CustomerFilter text NOT NULL,
  BlackList text NOT NULL,
  WhiteList text NOT NULL,
  UseDocumentFilter tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY ParentID (ParentID),
  KEY LinkID (LinkID),
  KEY Path (Path)
) ENGINE=MyISAM;
