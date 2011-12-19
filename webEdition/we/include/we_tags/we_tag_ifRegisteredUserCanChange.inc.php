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

function we_tag_ifRegisteredUserCanChange($attribs, $content){
	$admin = weTag_getAttribute("admin", $attribs);
	$userid = weTag_getAttribute("userid", $attribs); // deprecated  use protected=true instead
	$protected = weTag_getAttribute("protected", $attribs, false, true);
	if (!(isset($_SESSION["webuser"]) && isset($_SESSION["webuser"]["ID"]))) {
		return false;
	}
	if ($admin) {
		if (isset($_SESSION["webuser"][$admin]) && $_SESSION["webuser"][$admin])
			return true;
	}

	$listview = isset($GLOBALS["lv"]);

	if ($listview) {
		if ($protected) {
			return $GLOBALS["lv"]->f("wedoc_WebUserID") == $_SESSION["webuser"]["ID"];
		} else {
			return $GLOBALS["lv"]->f($userid) == $_SESSION["webuser"]["ID"];
		}
	} else {
		if ($protected) {
			return $GLOBALS['we_doc']->WebUserID == $_SESSION["webuser"]["ID"];
		} else {
			return $GLOBALS['we_doc']->getElement($userid) == $_SESSION["webuser"]["ID"];
		}
	}
}
