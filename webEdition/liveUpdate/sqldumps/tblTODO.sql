###UPDATEDROPCOL(account_id,###TBLPREFIX###tblTODO)###
/* query separator */

CREATE TABLE ###TBLPREFIX###tblTODO (
  ID int(11) unsigned NOT NULL auto_increment,
  ParentID int(11) unsigned default NULL,
  UserID int(11) unsigned NOT NULL default '0',
  msg_type tinyint(1) unsigned NOT NULL default '0',
  obj_type tinyint(1) unsigned NOT NULL default '0',
  headerDate int(11) unsigned default NULL,
  headerSubject varchar(255) default NULL,
  headerCreator int(11) unsigned default NULL,
  headerAssigner int(11) unsigned default NULL,
  headerStatus tinyint(4) unsigned default NULL,
  headerDeadline int(11) unsigned default NULL,
  Priority tinyint(4) unsigned default NULL,
  Properties smallint(5) unsigned default NULL,
  MessageText text,
  Content_Type varchar(10) default NULL,
  seenStatus tinyint(3) unsigned NOT NULL default '0',
  tag tinyint(3) unsigned default NULL,
  PRIMARY KEY  (ID)
) ENGINE=MyISAM;
