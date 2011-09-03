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
include_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_tools/navigation/class/weNavigationItems.class.php');

function we_parse_tag_navigationEntry($attribs, $content) {
	$tp = new we_tagParser($content);
	$tp->parseTags($content);
	return '<?php ' . we_tagParser::printTag('navigationEntry', $attribs, $content, true) . ';?>';
}

function we_tag_navigationEntry($attribs, $content) {
	if (($foo = attributFehltError($attribs, 'type', 'navigation'))) {
		echo $foo;
		return;
	}

	$navigationName = weTag_getAttribute('navigationname', $attribs, "default");
	$type = weTag_getAttribute('type', $attribs);
	$level = weTag_getAttribute('level', $attribs, 'defaultLevel');
	$current = weTag_getAttribute('current', $attribs, 'defaultCurrent');
	$positions = makeArrayFromCSV(weTag_getAttribute('position', $attribs, 'defaultPosition'));

	foreach ($positions as $position) {
		if ($position == 'first') {
			$position = 1;
		}
		$GLOBALS['we_navigation'][$navigationName]->setTemplate($content, $type, $level, $current, $position);
	}
}
