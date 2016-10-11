CREATE TABLE ###TBLPREFIX###tblFileLink (
  ID int unsigned NOT NULL COMMENT 'collection ID',
	DocumentTable enum('tblFile','tblObjectFiles','tblVFile','tblCategorys','tblTemplates','tblObject','tblnavigation','tblNewsletter','tblglossary','tblbanner','tblWebUser') NOT NULL default 'tblFile' COMMENT 'the table where ID matches',
	type enum('media','document','object','master','archive','collection','css') NOT NULL default 'media' COMMENT 'referenced type',
	remObj int unsigned NOT NULL default '0' COMMENT 'the referenced object',
	remTable enum('tblFile','tblObjectFiles') NOT NULL default 'tblFile' COMMENT 'the table where to find the referenced object',
	nHash binary(16) NULL default NULL,
	`position` smallint unsigned NOT NULL default '0' COMMENT 'optional position in case ordering is important',
	`isTemp` tinyint unsigned NOT NULL default '0' COMMENT 'this is one if the referenced object is in temporary table',
	KEY `position`(`position`),
	KEY remObj (remTable,remObj)
) ENGINE=MyISAM;
/* primary key is added in update*/

/* query separator */
###INSTALLONLY###ALTER TABLE ###TBLPREFIX###tblFileLink ADD PRIMARY KEY (ID,DocumentTable,`type`,remObj,nHash,`position`,isTemp)
