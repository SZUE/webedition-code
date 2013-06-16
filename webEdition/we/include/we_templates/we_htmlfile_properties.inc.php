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
$parts = array(
	array("icon" => "path.gif", "headline" => g_l('weClass', "[path]"), "html" => $GLOBALS['we_doc']->formPath(), "space" => 140),
	array("icon" => "doc.gif", "headline" => g_l('weClass', "[document]"), "html" => $GLOBALS['we_doc']->formDocTypeTempl(), "space" => 140),
	array("icon" => "cat.gif", "headline" => g_l('global', "[categorys]"), "html" => $GLOBALS['we_doc']->formCategory(), "space" => 140),
	array("icon" => "navi.gif", "headline" => g_l('global', "[navigation]"), "html" => $GLOBALS['we_doc']->formNavigation(), "space" => 140),
	array("icon" => "copy.gif", "headline" => g_l('weClass', "[copy" . $we_doc->ContentType . ']'), "html" => $GLOBALS['we_doc']->formCopyDocument(), "space" => 140),
	array("icon" => "charset.gif", "headline" => g_l('weClass', "[Charset]"), "html" => $GLOBALS['we_doc']->formCharset(), "space" => 140),
	array("icon" => "user.gif", "headline" => g_l('weClass', "[owners]"), "html" => $GLOBALS['we_doc']->formCreatorOwners(), "space" => 140));

$wepos = weGetCookieVariable("but_weHtmlDocProp");

print we_multiIconBox::getJS().
	we_multiIconBox::getHTML("weHtmlDocProp", "100%", $parts, 20);
