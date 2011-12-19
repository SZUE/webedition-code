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


/*****************************************************************************
 * INCLUDES
 *****************************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we.inc.php');

/*****************************************************************************
 * INITIALIZATION
 *****************************************************************************/

we_html_tools::protect();

we_html_tools::htmlTop();

/*****************************************************************************
 * CREATE JAVASCRIPT
 *****************************************************************************/

// Define needed JS
$_javascript = "
<!--
	self.focus();
//-->
";

/*****************************************************************************
 * RENDER FILE
 *****************************************************************************/

print
	we_htmlElement::jsElement($_javascript) .
	we_htmlElement::jsScript(JS_DIR . "keyListener.js") .
	we_htmlElement::jsElement(
		"
			function closeOnEscape() {
				return true;

			}

			function saveOnKeyBoard() {
				window.frames[1].we_save();
				return true;

			}"
	) .
"</head>";

$frameset = new we_htmlFrameset(array("rows" => "*,40", "framespacing" => "0", "border" => "1",  "frameborder" => "no"), 0);
$frameset->addFrame(array("src" => WEBEDITION_DIR . "we/include/we_editors/we_metadata_fields/editor.php", "name" => "we_metadatafields", "scrolling" => "auto", "noresize" => "noresize"));
$frameset->addFrame(array("src" => WEBEDITION_DIR . "we/include/we_editors/we_metadata_fields/footer.php?closecmd=".(isset($_REQUEST['we_cmd'][1]) ? rawurlencode($_REQUEST['we_cmd'][1]) : ""), "name" => "we_metadatafields_footer", "scrolling" => "no", "noresize" => "noresize"));

print $frameset->getHtmlCode() . we_htmlElement::htmlBody(array()) . "</html>";
