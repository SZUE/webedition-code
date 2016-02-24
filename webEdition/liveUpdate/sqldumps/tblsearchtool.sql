###ONCOL(Icon,###TBLPREFIX###tblsearchtool) DELETE FROM ###TBLPREFIX###tblsearchtool WHERE predefined=1 AND ID>13;###
/* query separator */
###ONCOL(Icon,###TBLPREFIX###tblsearchtool) UPDATE ###TBLPREFIX###tblsearchtool SET ID=ID+25 WHERE ID<25 AND predefined=0;###
/* query separator */
###UPDATEDROPCOL(Icon,###TBLPREFIX###tblsearchtool)###
/* query separator */

CREATE TABLE ###TBLPREFIX###tblsearchtool (
  ID smallint unsigned NOT NULL auto_increment,
  ParentID smallint unsigned NOT NULL default '0',
  IsFolder tinyint unsigned NOT NULL default '0',
  `Path` varchar(255) NOT NULL,
  `Text` varchar(255) NOT NULL,
  predefined tinyint unsigned NOT NULL,
  folderIDDoc int unsigned NOT NULL,
  folderIDTmpl int unsigned NOT NULL,
  folderIDMedia int unsigned NOT NULL,
  searchDocSearch varchar(255) NOT NULL,
  searchTmplSearch varchar(255) NOT NULL,
  searchMediaSearch varchar(255) NOT NULL,
  searchForTextDocSearch varchar(255) NOT NULL,
  searchForTitleDocSearch tinyint unsigned NOT NULL,
  searchForContentDocSearch varchar(255) NOT NULL,
  searchForTextTmplSearch varchar(255) NOT NULL,
  searchForContentTmplSearch varchar(255) NOT NULL,
  searchForTextMediaSearch varchar(255) NOT NULL,
  searchForTitleMediaSearch tinyint unsigned NOT NULL,
  searchForMetaMediaSearch varchar(255) NOT NULL,
  searchForImageMediaSearch tinyint unsigned NOT NULL,
  searchForAudioMediaSearch tinyint unsigned NOT NULL,
  searchForVideoMediaSearch tinyint unsigned NOT NULL,
  searchForOtherMediaSearch tinyint unsigned NOT NULL,
  anzahlDocSearch tinyint unsigned NOT NULL,
  anzahlTmplSearch tinyint unsigned NOT NULL,
  anzahlAdvSearch tinyint unsigned NOT NULL,
  anzahlMediaSearch tinyint unsigned NOT NULL,
  setViewDocSearch tinyint unsigned NOT NULL,
  setViewTmplSearch tinyint unsigned NOT NULL,
  setViewAdvSearch tinyint unsigned NOT NULL,
  setViewMediaSearch tinyint unsigned NOT NULL,
  OrderDocSearch varchar(64) NOT NULL,
  OrderTmplSearch varchar(64) NOT NULL,
  OrderAdvSearch varchar(64) NOT NULL,
  OrderMediaSearch varchar(64) NOT NULL,
  searchAdvSearch varchar(255) NOT NULL,
  locationAdvSearch varchar(255) NOT NULL,
  locationMediaSearch varchar(255) NOT NULL,
  searchFieldsAdvSearch varchar(255) NOT NULL,
  searchFieldsMediaSearch varchar(255) NOT NULL,
  search_tables_advSearch varchar(255) NOT NULL,
  activTab tinyint unsigned NOT NULL default '1',
  PRIMARY KEY  (ID),
  UNIQUE KEY Path (Path)
) ENGINE=MyISAM AUTO_INCREMENT=25;
/* query separator */
REPLACE INTO ###TBLPREFIX###tblsearchtool (ID,`ParentID`, `IsFolder`, `Path`, `Text`, `predefined`, `folderIDDoc`, `folderIDTmpl`, `searchDocSearch`, `searchTmplSearch`, `searchForTextDocSearch`, `searchForTitleDocSearch`, `searchForContentDocSearch`, `searchForTextTmplSearch`, `searchForContentTmplSearch`, `anzahlDocSearch`, `anzahlTmplSearch`, `anzahlAdvSearch`, `setViewDocSearch`, `setViewTmplSearch`, `setViewAdvSearch`, `OrderDocSearch`, `OrderTmplSearch`, `OrderAdvSearch`, `searchAdvSearch`, `locationAdvSearch`, `searchFieldsAdvSearch`, `search_tables_advSearch`, `activTab`) VALUES
(1,0, 1,  '/_PREDEF_', 'p', 1, 0, 0, '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '',4),
(2,1, 1,  '/_PREDEF_/document', 'p', 1, 0, 0, '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '',4),
(3,1, 1,  '/_PREDEF_/object', 'p', 1, 0, 0, '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '',4),
(4,2, 0,  '/_PREDEF_/document/unpublished', 'p', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 25, 25, 25, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:17:"geparkt_geaendert";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:6:"Status";}', 'a:4:{s:7:"tblFile";s:1:"1";s:14:"tblObjectFiles";s:1:"0";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3),
(5,2, 0,  '/_PREDEF_/document/static', 'p', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 25, 25, 25, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:8:"statisch";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:11:"Speicherart";}', 'a:4:{s:7:"tblFile";s:1:"1";s:14:"tblObjectFiles";s:1:"0";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3),
(6,2, 0,  '/_PREDEF_/document/dynamic', 'p', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 25, 25, 25, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:9:"dynamisch";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:11:"Speicherart";}', 'a:4:{s:7:"tblFile";s:1:"1";s:14:"tblObjectFiles";s:1:"0";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3),
(7,3, 0,  '/_PREDEF_/object/unpublished', 'p', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 25, 25, 25, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:17:"geparkt_geaendert";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:6:"Status";}', 'a:4:{s:7:"tblFile";s:1:"0";s:14:"tblObjectFiles";s:1:"1";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3),
(8,0, 1,  '/_CUSTOM_', 'c', 1, 0, 0, '', '', 0, 0, 0, 0, 0, 10, 10, 10, 0, 0, 0, '', '', '', '', '', '', '', 4),
(9,0,1, '/_VERSION_','v', 1, 0, 0, '', '', 0, 0, 0, 0, 0, 10, 10, 10, 0, 0, 0, '', '', '', '', '', '', '', 4),
(10,9, 1,  '/_VERSION_/document', 'v', 1, 0, 0, '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '',4),
(11,9, 1,  '/_VERSION_/object', 'v', 1, 0, 0, '', '', '', 0, '', '', '', 0, 0, 0, 0, 0, 0, '', '', '', '', '', '', '',4),
(12,10, 0,  '/_VERSION_/document/deleted', 'v', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 10, 10, 10, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:7:"deleted";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:6:"Status";}', 'a:5:{s:7:"tblFile";s:1:"1";s:14:"tblObjectFiles";s:1:"0";s:11:"tblversions";s:1:"1";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3),
(13,11, 0,  '/_VERSION_/object/deleted', 'v', 1, 0, 0, 'a:0:{}', 'a:0:{}', 1, 1, 1, 1, 1, 10, 10, 10, 0, 0, 0, 'Text', 'Text', 'Text', 'a:1:{i:0;s:7:"deleted";}', 'a:1:{i:0;s:7:"CONTAIN";}', 'a:1:{i:0;s:6:"Status";}', 'a:5:{s:7:"tblFile";s:1:"0";s:14:"tblObjectFiles";s:1:"1";s:11:"tblversions";s:1:"1";s:12:"tblTemplates";s:1:"0";s:9:"tblObject";s:1:"0";}',3);