CREATE TABLE `tblversions` (
  `ID` bigint(20) NOT NULL auto_increment,
  `documentID` bigint(20) NOT NULL,
  `documentTable` varchar(64) NOT NULL,
  `documentElements` longtext NOT NULL,
  `documentScheduler` longtext NOT NULL,
  `documentCustomFilter` longtext NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` enum('saved','published','unpublished','deleted') NOT NULL,
  `version` bigint(20) NOT NULL,
  `binaryPath` varchar(255) NOT NULL,
  `modifications` varchar(255) NOT NULL,
  `modifierID` bigint(20) NOT NULL,
  `IP` varchar(30) NOT NULL,
  `Browser` varchar(255) NOT NULL,
  `ContentType` varchar(32) NOT NULL,
  `Text` varchar(255) NOT NULL,
  `ParentID` int(11) NOT NULL,
  `Icon` varchar(64) NOT NULL,
  `CreationDate` int(11) NOT NULL,
  `CreatorID` bigint(20) NOT NULL,
  `Path` varchar(255) NOT NULL,
  `TemplateID` int(11) NOT NULL,
  `Filename` varchar(255) NOT NULL,
  `Extension` varchar(16) NOT NULL,
  `IsDynamic` tinyint(4) NOT NULL,
  `IsSearchable` tinyint(1) NOT NULL,
  `ClassName` varchar(64) NOT NULL,
  `DocType` varchar(64) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `RestrictOwners` tinyint(1) NOT NULL,
  `Owners` varchar(255) NOT NULL,
  `OwnersReadOnly` text NOT NULL,
  `Language` varchar(5) NOT NULL,
  `WebUserID` bigint(20) NOT NULL,
  `Workspaces` varchar(255) NOT NULL,
  `ExtraWorkspaces` varchar(255) NOT NULL,
  `ExtraWorkspacesSelected` varchar(255) NOT NULL,
  `Templates` varchar(255) NOT NULL,
  `ExtraTemplates` varchar(255) NOT NULL,
  `TableID` bigint(20) NOT NULL,
  `ObjectID` bigint(20) NOT NULL,
  `IsClassFolder` tinyint(1) NOT NULL,
  `IsNotEditable` tinyint(1) NOT NULL,
  `Charset` varchar(64) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fromScheduler` tinyint(1) NOT NULL,
  `fromImport` tinyint(1) NOT NULL,
  `resetFromVersion` bigint(20) NOT NULL,
  `InGlossar` tinyint(1) NOT NULL,
  PRIMARY KEY  (`ID`)
  KEY `timestamp` (`timestamp`,`CreationDate`),
  KEY `binaryPath` (`binaryPath`)
) ENGINE=MyISAM ;
