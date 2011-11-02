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
if (str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) == '/we_showDocument.inc.php') {
	exit();
}

if (isset($noSess) && $noSess && !defined('NO_SESS')) {
	define('NO_SESS', 1);
}
include_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_live_tools.inc.php');


//  Diese we_cmds werden auf den Seiten gespeichert und nicht �bergeben!!!!!
//  Sie kommen von showDoc.php
$id = intval(isset($_REQUEST['we_cmd'][1]) ? $_REQUEST['we_cmd'][1] : 0);
$tmplID = intval(isset($_REQUEST['we_cmd'][4]) ? $_REQUEST['we_cmd'][4] : 0);
$baseHref = addslashes(isset($_REQUEST['we_cmd'][5]) ? $_REQUEST['we_cmd'][5] : '');
$we_editmode = addslashes(isset($_REQUEST['we_cmd'][6]) ? $_REQUEST['we_cmd'][6] : '');
$createFromTmpFile = addslashes(isset($_REQUEST['we_cmd'][7]) ? $_REQUEST['we_cmd'][7] : '');

$we_ID = $id;
$we_Table = FILE_TABLE;

$we_dt = isset($_SESSION['we_data'][$we_transaction]) ? $_SESSION['we_data'][$we_transaction] : '';

// init document
include ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_editors/we_init_doc.inc.php');

if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] != 'ResetVersion' && $_REQUEST['cmd'] != 'PublishDocs') {
	if (isset($FROM_WE_SHOW_DOC) && $FROM_WE_SHOW_DOC) { // when called showDoc.php
		$publ = $we_doc->Published;
		$prot = getServerProtocol();
		$preurl = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']) ? '$prot://' . $_SERVER['HTTP_HOST'] : '';

		if ((!$we_doc->IsDynamic) && (!$tmplID)) { // if the document is not a dynamic php-doc and is published we make a redirect to the static page
			header('Location: ' . $preurl . ($publ ? $we_doc->Path : '/this_file_does_not_exist_on_this_server'));
			exit();
		}
	}
}
if (isset($_REQUEST['vers_we_obj'])) {
	if ($_REQUEST['vers_we_obj']) {
		$f = $_SERVER['DOCUMENT_ROOT'] . VERSION_DIR . 'tmpSavedObj.txt';
		$_REQUEST['vers_we_obj'] = false;
		$tempFile = weFile::load($f);
		$obj = unserialize($tempFile);
		$we_doc = $obj;
	}

// deal with customerFilter
// @see we_object_showDocument.inc.php
} else if ($we_doc->documentCustomerFilter && !isset($GLOBALS['getDocContentVersioning'])) {

	// call session_start to init session, otherwise NO customer can exist
	@session_start();

		if (($_visitorHasAccess = $we_doc->documentCustomerFilter->accessForVisitor($we_doc))) {

		if (!($_visitorHasAccess == WECF_ACCESS || $_visitorHasAccess == WECF_CONTROLONTEMPLATE)) {
				// user has NO ACCESS => show errordocument
				$_errorDocId = $we_doc->documentCustomerFilter->getErrorDoc($_visitorHasAccess);
				if (($_errorDocPath = id_to_path($_errorDocId, FILE_TABLE))) { // use given document instead !
					if($_errorDocId){
					unset($_errorDocId);
//					header('Location: ' . getServerUrl() . $_errorDocPath);
					@include($_SERVER['DOCUMENT_ROOT'].$_errorDocPath);
					unset($_errorDocPath);
					}
					//exit();
					return;

				} else {
					die('Customer has no access to this document');

				}
			}
		}
	}
}

$we_doc->EditPageNr = $we_editmode ? WE_EDITPAGE_CONTENT : WE_EDITPAGE_PREVIEW;

if ($tmplID && ($we_doc->ContentType == 'text/webedition')) { // if the document should displayed with an other template
	$we_doc->setTemplateID($tmplID);
}

//$we_doc->setCache();

if (($we_include = $we_doc->editor($baseHref))) {
	if (substr(strtolower($we_include), 0, strlen($_SERVER['DOCUMENT_ROOT'])) == strtolower($_SERVER['DOCUMENT_ROOT'])) {
		if ((!defined('WE_CONTENT_TYPE_SET')) && isset($we_doc->elements['Charset']['dat']) && $we_doc->elements['Charset']['dat']) { //	send charset which might be determined in template
			define('WE_CONTENT_TYPE_SET', 1);
			//	@ -> to aware of unproper use of this element, f. ex in include-File
			@header('Content-Type: text/html; charset=' . $we_doc->elements['Charset']['dat']);
		}

		// --> Glossary Replacement

		if ((defined('GLOSSARY_TABLE') && (!isset($GLOBALS['WE_MAIN_DOC']) || $GLOBALS['WE_MAIN_DOC'] == $GLOBALS['we_doc'])) &&
						(isset($we_doc->InGlossar) && $we_doc->InGlossar == 0)) {
			include_once (WE_GLOSSARY_MODULE_DIR . 'weGlossaryCache.php');
			include_once (WE_GLOSSARY_MODULE_DIR . 'weGlossaryReplace.php');

			weGlossaryReplace::start();
			include ($we_include);
			weGlossaryReplace::end($GLOBALS['we_doc']->Language);
		} else {
			// --> Glossary Replacement

			if ((defined("GLOSSARY_TABLE") && (!isset($GLOBALS["WE_MAIN_DOC"]) || $GLOBALS["WE_MAIN_DOC"] == $GLOBALS["we_doc"])) &&
				(isset($we_doc->InGlossar) && $we_doc->InGlossar==0) ){
					include_once (WE_GLOSSARY_MODULE_DIR . "weGlossaryCache.php");
					include_once (WE_GLOSSARY_MODULE_DIR . "weGlossaryReplace.php");

					weGlossaryReplace::start();
					include ($we_include);
					weGlossaryReplace::end($GLOBALS["we_doc"]->Language);
			}else{
					include ($we_include);
			}
		}
	} else {
		protect(); //	only inside webEdition !!!
		include ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/' . $we_include);
	}
}else{
		exit('Nothing to include ...');
}