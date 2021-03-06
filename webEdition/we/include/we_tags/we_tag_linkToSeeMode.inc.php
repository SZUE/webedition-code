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
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
function we_tag_linkToSeeMode(array $attribs){
	$id = weTag_getAttribute('id', $attribs, 0, we_base_request::INT); //	if a document-id is selected go to that document
	$oid = weTag_getAttribute('oid', $attribs, 0, we_base_request::INT); //	if an object-id is selected go to that object
	$permission = weTag_getAttribute('permission', $attribs, '', we_base_request::STRING);
	$docAttr = weTag_getAttribute('doc', $attribs, "top", we_base_request::STRING);
	$xml = weTag_getAttribute('xml', $attribs, XHTML_DEFAULT, we_base_request::BOOL);

	return ''; //this feature is currently unsupported
	// check for value attribute
	if(($foo = attributFehltError($attribs, "value", __FUNCTION__))){
		return $foo;
	}

	$value = weTag_getAttribute('value', $attribs, '', we_base_request::STRING);

	if(!empty($id) ){
		$type = 'document';
	} elseif(isset($GLOBALS['we_obj']) || $oid){ // use object if possible
		$type = 'object';
		if($oid){
			$id = $oid;
		} elseif(isset($GLOBALS['we_obj'])){
			$id = $GLOBALS['we_obj']->ID;
		}
	} else {
		$type = 'document';
		$doc = we_getDocForTag($docAttr, true); // check if we should use the top document or the  included document
		$id = $doc->ID;
	}

	if(!empty($_SESSION["webuser"]) && $_SESSION["webuser"]["registered"] && !isset($_REQUEST["we_transaction"])){
		if(!$permission || !empty($_SESSION["webuser"][$permission])){ // Has webUser the right permissions??
			//	check if the customer is a user, too.
			$tmpDB = $GLOBALS['DB_WE'];

			$q = f('SELECT passwd FROM ' . USER_TABLE . ' WHERE IsFolder=0 AND LoginDenied=0 AND username="' . $tmpDB->escape($_SESSION["webuser"]["Username"]) . '"','', $tmpDB);

			if($q && we_users_user::comparePasswords($_SESSION['webuser']['Username'], $q, $_SESSION['webuser']['Password'])){// customer is also a user
				unset($q);
				$retStr = getHtmlTag(
						'form', array(
						'method' => 'post',
						'name' => 'startSeeMode_' . $type . '_' . $id,
						'target' => '_parent',
						'action' => WEBEDITION_DIR . 'loginToSuperEasyEditMode.php'
						), getHtmlTag('input', array('type' => 'hidden', 'name' => 'username', 'value' => $_SESSION["webuser"]["Username"], 'xml' => $xml)) .
						getHtmlTag('input', array('type' => 'hidden', 'name' => 'type', 'value' => $type, 'xml' => $xml)) .
						getHtmlTag('input', array('type' => 'hidden', 'name' => 'id', 'value' => $id, 'xml' => $xml)) .
						getHtmlTag('input', array('type' => 'hidden', 'name' => 'path', 'value' => $_SERVER['HTTP_REQUEST_URI'], 'xml' => $xml))
					) .
					getHtmlTag('a', array(
						'href' => 'javascript:document.forms[\'startSeeMode_' . $type . '_' . $id . '\'].submit();',
						'xml' => $xml
						), $value);
			} else { //	customer is no user
				return '<!-- ERROR: CUSTOMER IS NO USER! -->';
			}
			unset($tmpDB);
		} else { // User has not the right permissions.
			return '<!-- ERROR: USER DOES NOT HAVE REQUIRED PERMISSION! -->';
		}
	} else { //	webUser is not registered, show nothing
		return '<!-- ERROR: USER HAS NOT BEEN LOGGED IN! -->';
	}
	return $retStr;
}
