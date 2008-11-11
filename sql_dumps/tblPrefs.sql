CREATE TABLE tblPrefs (
  userID bigint(20) NOT NULL default '0',
  FileFilter int(11) NOT NULL default '0',
  openFolders_tblFile text NOT NULL,
  openFolders_tblTemplates text NOT NULL,
  DefaultTemplateID int(11) NOT NULL default '0',
  DefaultStaticExt varchar(7) NOT NULL default '',
  DefaultDynamicExt varchar(7) NOT NULL default '',
  DefaultHTMLExt varchar(7) NOT NULL default '',
  sizeOpt tinyint(1) NOT NULL default '0',
  weWidth int(11) NOT NULL default '0',
  weHeight int(11) NOT NULL default '0',
  usePlugin tinyint(1) NOT NULL default '0',
  autostartPlugin tinyint(1) NOT NULL default '0',
  promptPlugin tinyint(1) NOT NULL default '0',
  `Language` varchar(64) NOT NULL default '',
  openFolders_tblObject text,
  openFolders_tblObjectFiles text,
  phpOnOff tinyint(1) NOT NULL default '0',
  seem_start_file int(11) NOT NULL default '0',
  seem_start_type varchar(10) NOT NULL default '',
  editorSizeOpt tinyint(1) NOT NULL default '0',
  editorWidth int(11) NOT NULL default '0',
  editorHeight int(11) NOT NULL default '0',
  debug_normal tinyint(1) NOT NULL default '0',
  debug_seem tinyint(1) NOT NULL default '0',
  editorFontname varchar(255) NOT NULL default '',
  editorFontsize int(2) NOT NULL default '0',
  editorFont tinyint(1) NOT NULL default '0',
  default_tree_count int(11) NOT NULL default '0',
  xhtml_show_wrong tinyint(1) NOT NULL default '0',
  xhtml_show_wrong_text tinyint(2) NOT NULL default '0',
  xhtml_show_wrong_js tinyint(2) NOT NULL default '0',
  xhtml_show_wrong_error_log tinyint(2) NOT NULL default '0',
  import_from varchar(255) NOT NULL default '',
  siteImportPrefs longtext NOT NULL,
  cockpit_amount_last_documents int(2) NOT NULL default '3',
  cockpit_rss_feed_url text,
  use_jupload tinyint(1) NOT NULL default '1',
  cockpit_dat text,
  cockpit_amount_columns int(2) NOT NULL default '3',
  message_reporting int(11) NOT NULL default '7',
  force_glossary_check tinyint(1) NOT NULL default '0',
  force_glossary_action tinyint(1) NOT NULL default '0',
  editorFontcolor varchar(255) NOT NULL,
  editorWeTagFontcolor varchar(255) NOT NULL,
  editorWeAttributeFontcolor varchar(255) NOT NULL,
  editorHTMLTagFontcolor varchar(255) NOT NULL,
  editorHTMLAttributeFontcolor varchar(255) NOT NULL,
  editorPiTagFontcolor varchar(255) NOT NULL,
  editorCommentFontcolor varchar(255) NOT NULL,
  use_jeditor tinyint(1) NOT NULL default '1',
  specify_jeditor_colors tinyint(1) NOT NULL
) TYPE=MyISAM;
/* query separator */
INSERT INTO tblPrefs (userID, FileFilter, openFolders_tblFile, openFolders_tblTemplates, DefaultTemplateID, DefaultStaticExt, DefaultDynamicExt, DefaultHTMLExt, sizeOpt, weWidth, weHeight, usePlugin, autostartPlugin, promptPlugin, Language, openFolders_tblObject, openFolders_tblObjectFiles, phpOnOff, seem_start_file, seem_start_type, editorSizeOpt, editorWidth, editorHeight, debug_normal, debug_seem, editorFontname, editorFontsize, editorFont, default_tree_count, xhtml_show_wrong, xhtml_show_wrong_text, xhtml_show_wrong_js, xhtml_show_wrong_error_log, import_from, siteImportPrefs, cockpit_amount_last_documents, cockpit_rss_feed_url, use_jupload, cockpit_dat, cockpit_amount_columns, message_reporting, force_glossary_check, force_glossary_action, editorFontcolor, editorWeTagFontcolor, editorWeAttributeFontcolor, editorHTMLTagFontcolor, editorHTMLAttributeFontcolor, editorPiTagFontcolor, editorCommentFontcolor, use_jeditor, specify_jeditor_colors) VALUES (1, 0, ',1', '1,10', 0, '.html', '.php', '.html', 0, 0, 0, 0, 0, 0, 'English_UTF-8', '', '', 0, 0, 'cockpit', 1, 900, 700, 0, 0, 'none', -1, 0, 0, 0, 0, 0, 0, '', '', 5, 'http://www.living-e.de/de/pressezentrum/pr-mitteilungen/rss2.xml', 1, 'a:3:{i:0;a:2:{i:0;a:4:{i:0;s:3:"pad";i:1;s:4:"blue";i:2;i:1;i:3;s:18:"U29uc3RpZ2Vz,30020";}i:1;a:4:{i:0;s:3:"mfd";i:1;s:5:"green";i:2;i:1;i:3;s:12:"1111;0;5;00;";}}i:1;a:2:{i:0;a:4:{i:0;s:3:"rss";i:1;s:6:"yellow";i:2;i:1;i:3;s:106:"aHR0cDovL3d3dy5saXZpbmctZS5kZS9kZS9wcmVzc2V6ZW50cnVtL3ByLW1pdHRlaWx1bmdlbi9yc3MyLnhtbA==,111000,0,110000,1";}i:1;a:4:{i:0;s:3:"sct";i:1;s:3:"red";i:2;i:1;i:3;s:124:"open_document,new_document,new_template,new_directory,unpublished_pages;unpublished_objects,new_object,new_class,preferences";}}i:2;a:20:{i:0;a:2:{i:0;s:16:"bGl2aW5nLWUgQUc=";i:1;s:88:"aHR0cDovL3d3dy5saXZpbmctZS5kZS9kZS9wcmVzc2V6ZW50cnVtL3ByLW1pdHRlaWx1bmdlbi9yc3MyLnhtbA==";}i:1;a:2:{i:0;s:16:"Rk9DVVMtT25saW5l";i:1;s:60:"aHR0cDovL2ZvY3VzLm1zbi5kZS9mb2wvWE1ML3Jzc19mb2xuZXdzLnhtbA==";}i:2;a:2:{i:0;s:12:"U2xhc2hkb3Q=";i:1;s:56:"aHR0cDovL3Jzcy5zbGFzaGRvdC5vcmcvU2xhc2hkb3Qvc2xhc2hkb3Q=";}i:3;a:2:{i:0;s:24:"aGVpc2Ugb25saW5lIE5ld3M=";i:1;s:56:"aHR0cDovL3d3dy5oZWlzZS5kZS9uZXdzdGlja2VyL2hlaXNlLnJkZg==";}i:4;a:2:{i:0;s:20:"dGFnZXNzY2hhdS5kZQ==";i:1;s:68:"aHR0cDovL3d3dy50YWdlc3NjaGF1LmRlL3htbC90YWdlc3NjaGF1LW1lbGR1bmdlbi8=";}i:5;a:2:{i:0;s:12:"U0FUVklTSU9O";i:1;s:52:"aHR0cDovL3d3dy5zYXR2aXNpb24ub3JnL25ld3MvcnNzLnhtbA==";}i:6;a:2:{i:0;s:20:"QmFzZWwtSUkuaW5mbw==";i:1;s:52:"aHR0cDovL3d3dy5iYXNlbC1paS5pbmZvL0Jhc2VsLUlJLnBocA==";}i:7;a:2:{i:0;s:52:"LrAuTGlxdWlkIE1vdGlvbiBXZWItICYgR3JhZmlrZGVzaWdusC6w";i:1;s:52:"aHR0cDovL3d3dy5saXF1aWQtbW90aW9uLmRlL3Jzcy9yc3MueG1s";}i:8;a:2:{i:0;s:12:"RkFaLk5FVA==";i:1;s:64:"aHR0cDovL3d3dy5mYXoubmV0L3MvUnViL1RwbH5FcGFydG5lcn5TUnNzXy54bWw=";}i:9;a:2:{i:0;s:20:"RmlsbXN0YXJ0cy5kZQ==";i:1;s:60:"aHR0cDovL3d3dy5maWxtc3RhcnRzLmRlL3htbC9maWxtc3RhcnRzLnhtbA==";}i:10;a:2:{i:0;s:20:"TkVUWkVJVFVORy5ERQ==";i:1;s:76:"aHR0cDovL3d3dy5uZXR6ZWl0dW5nLmRlL2V4cG9ydC9uZXdzL3Jzcy90aXRlbHNlaXRlLnhtbA==";}i:11;a:2:{i:0;s:28:"aHR0cDovL3d3dy5zcGllZ2VsLmRl";i:1;s:52:"aHR0cDovL3d3dy5zcGllZ2VsLmRlL3NjaGxhZ3plaWxlbi9yc3Mv";}i:12;a:2:{i:0;s:8:"R0VPLmRl";i:1;s:48:"aHR0cDovL3d3dy5nZW8uZGUvcnNzL0dFTy9pbmRleC54bWw=";}i:13;a:2:{i:0;s:44:"MTAwMGUgU3By/GNoZSAoU3BydWNoIGRlcyBUYWdlcyk=";i:1;s:96:"aHR0cDovL3d3dy5ob21lcGFnZXNlcnZpY2Uudm9zc3dlYi5pbmZvL2F1c3dhaGwvc3BydWNoL3Jzcy9oZXV0ZS9yc3MueG1s";}i:14;a:2:{i:0;s:32:"QnVuZGVzcmVnaWVydW5nIEFrdHVlbGw=";i:1;s:56:"aHR0cDovL3d3dy5idW5kZXNyZWdpZXJ1bmcuZGUvYWt0dWVsbC5yc3M=";}i:15;a:2:{i:0;s:20:"QW53YWx0cy1UaXBwcw==";i:1;s:60:"aHR0cDovL3d3dy5hbndhbHRzc3VjaGRpZW5zdC5kZS9yc3MvcnNzLnhtbA==";}i:16;a:2:{i:0;s:56:"UHJvbW9NYXN0ZXJzIEludGVybmV0IE1hcmtldGluZyBSU1MgQmxvZw==";i:1;s:56:"aHR0cDovL3d3dy5wcm9tb21hc3RlcnMuYXQvcnNzL2luZGV4LnhtbA==";}i:17;a:2:{i:0;s:20:"U1dSMyBSREYtRmVlZA==";i:1;s:40:"aHR0cDovL3d3dy5zd3IzLmRlL3JkZi1mZWVkLw==";}i:18;a:2:{i:0;s:12:"Q0hJUC5ERQ==";i:1;s:44:"aHR0cDovL3d3dy5jaGlwLmRlL3Jzc19uZXdzLnhtbA==";}i:19;a:2:{i:0;s:12:"U3Rlcm4uZGU=";i:1;s:64:"aHR0cDovL3d3dy5zdGVybi5kZS9zdGFuZGFyZC9yc3MucGhwP2NoYW5uZWw9YWxs";}}}', 2, 7, 0, 0, '', '', '', '', '', '', '', 1, 0);
