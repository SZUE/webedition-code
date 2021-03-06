CREATE TABLE ###TBLPREFIX###tblversionslog (
  `ID` int unsigned NOT NULL auto_increment,
  `timestamp` int unsigned NOT NULL,
	`typ` enum('prefs','reset','delete') NOT NULL,
  `userID` int unsigned NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM ;

/* query separator */
###ONCOL(action,###TBLPREFIX###tblversionslog)UPDATE ###TBLPREFIX###tblversionslog SET typ="prefs" WHERE action=3;###
/* query separator */
###ONCOL(action,###TBLPREFIX###tblversionslog)UPDATE ###TBLPREFIX###tblversionslog SET typ="reset" WHERE action=2;###
/* query separator */
###ONCOL(action,###TBLPREFIX###tblversionslog)UPDATE ###TBLPREFIX###tblversionslog SET typ="delete" WHERE action=1;###
/* query separator */
###UPDATEDROPCOL(action,###TBLPREFIX###tblversionslog)###

