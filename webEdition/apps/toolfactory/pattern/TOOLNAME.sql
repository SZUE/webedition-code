CREATE TABLE `###TBLPREFIX###<?php print $TABLENAME?>` (`ID` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`ParentID` BIGINT NOT NULL DEFAULT '0',`IsFolder` TINYINT NOT NULL DEFAULT '0',`ContentType` VARCHAR( 32 ) NOT NULL ,`Path` VARCHAR( 255 ) NOT NULL ,`Text` VARCHAR( 255 ) NOT NULL, `Published` INT( 11 ) NOT NULL default '0', `Status` VARCHAR( 255 ) NOT NULL default '') ENGINE = MYISAM;