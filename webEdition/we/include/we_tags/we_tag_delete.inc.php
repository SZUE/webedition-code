<?php

/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
function we_tag_delete($attribs){
	$id = weTag_getAttribute('id', $attribs, 0);
	$type = weTag_getAttribute('type', $attribs, 'document');
	$userid = weTag_getAttribute('userid', $attribs); // deprecated  use protected=true instead
	$protected = weTag_getAttribute('protected', $attribs, false, true);
	$admin = weTag_getAttribute('admin', $attribs);
	$mail = weTag_getAttribute('mail', $attribs);
	$mailfrom = weTag_getAttribute('mailfrom', $attribs);
	$charset = weTag_getAttribute('charset', $attribs, "iso-8859-1");
	$doctype = weTag_getAttribute('doctype', $attribs);
	$classid = weTag_getAttribute('classid', $attribs);
	$pid = weTag_getAttribute('pid', $attribs);
	$forceedit = weTag_getAttribute('forceedit', $attribs, false, true);

	switch($type){
		case "document":
			$docID = $id ? $id : weRequest('int', 'we_delDocument_ID');
			if(!$docID){
				return '';
			}
			$doc = new we_webEditionDocument();
			$doc->initByID($docID);
			$table = FILE_TABLE;
			if($doctype){
				$doctypeID = f('SELECT ID FROM ' . DOC_TYPES_TABLE . " WHERE DocType LIKE '" . $GLOBALS['DB_WE']->escape($doctype) . "'");
				if($doc->DocType != $doctypeID){
					$GLOBALS['we_' . $type . '_delete_ok'] = false;
					return '';
				}
			}
			break;
		default:
			$docID = $id ? $id : weRequest('int', 'we_delObject_ID', $id);
			if(!$docID){
				return '';
			}
			$doc = new we_objectFile();
			$doc->initByID($docID, OBJECT_FILES_TABLE);
			$table = OBJECT_FILES_TABLE;
			if($classid && $doc->TableID != $classid){
				$GLOBALS["we_" . $type . "_delete_ok"] = false;
				return "";
			}
			break;
	}

	if($pid){
		if($doc->ParentID != $pid){
			$GLOBALS["we_" . $type . "_delete_ok"] = false;
			return "";
		}
	}

	$isOwner = ($protected ?
			($_SESSION["webuser"]["ID"] == $doc->WebUserID) :
			($userid ?
				($_SESSION["webuser"]["ID"] == $doc->getElement($userid)) : false));


	$isAdmin = ($admin ? isset($_SESSION["webuser"][$admin]) && $_SESSION["webuser"][$admin] : false);

	if($isAdmin || $isOwner || $forceedit){
		$GLOBALS["NOT_PROTECT"] = true;
		we_base_delete::deleteEntry($docID, $table);
		$GLOBALS["we_" . $type . "_delete_ok"] = true;
		if($mail){
			if(!$mailfrom){
				$mailfrom = "dontReply@" . $_SERVER['SERVER_NAME'];
			}
			if($type == "object"){
				$mailtext = sprintf(g_l('global', "[std_mailtext_delObj]"), $doc->Path) . "\n";
				$subject = g_l('global', "[std_subject_delObj]");
			} else {
				$mailtext = sprintf(g_l('global', "[std_mailtext_delDoc]"), $doc->Path) . "\n";
				$subject = g_l('global', "[std_subject_delDoc]");
			}
			$phpmail = new we_util_Mailer($mail, $subject, $mailfrom);
			$phpmail->setCharSet($charset);
			$phpmail->addTextPart(trim($mailtext));
			$phpmail->buildMessage();
			$phpmail->Send();
		}
	} else {
		$GLOBALS["we_" . $type . "_delete_ok"] = false;
	}
	return '';
}
