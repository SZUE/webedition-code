###UPDATEONLY###DROP TABLE IF EXISTS ###TBLPREFIX###tblPrefs_old;
/* query separator */
CREATE TABLE ###TBLPREFIX###tblPrefs (
  userID int unsigned NOT NULL default '0',
  `key` varchar(100) NOT NULL default '',
	value text NOT NULL,
  PRIMARY KEY (`userID`,`key`),
	KEY lookup (`key`)
) ENGINE=MyISAM;
/* query separator */
###UPDATEONLY###DELETE FROM ###TBLPREFIX###tblPrefs WHERE `key`="cockpit_rss_feed_url";