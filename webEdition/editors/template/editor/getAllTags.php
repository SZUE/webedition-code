<?php

//used by old javaeditor
require_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();

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
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>
<tags>
';
$allWeTags = we_wizard_tag::getExistingWeTags();
foreach($allWeTags as $tag){
	$tagData = weTagData::getTagData($tag);
	echo "\t" . '<tag needsEndtag="' . ($tagData->needsEndTag() ? "true" : "false") . '" name="' . $tagData->getName() . '" />' . "\n";
}
echo "</tags>\n";