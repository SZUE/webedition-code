CREATE TABLE tblTODO (
  ID int  NOT NULL IDENTITY(1,1),
  ParentID int  default NULL,
  UserID int  NOT NULL default '0',
  account_id int  NOT NULL default '0',
  msg_type tinyint  NOT NULL default '0',
  obj_type tinyint  NOT NULL default '0',
  headerDate int  default NULL,
  headerSubject varchar(255) default NULL,
  headerCreator int  default NULL,
  headerAssigner int  default NULL,
  headerStatus tinyint  default NULL,
  headerDeadline int  default NULL,
  Priority tinyint  default NULL,
  Properties int  default NULL,
  MessageText text,
  Content_Type varchar(10) default NULL,
  seenStatus int  NOT NULL default '0',
  tag int  default NULL,
  PRIMARY KEY  (ID)
) 
