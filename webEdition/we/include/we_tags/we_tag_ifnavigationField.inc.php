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
/* TODO
function we_tag_ifnavigationField(array $attribs){
	if(!empty($GLOBALS['weNavigationItemArray']) && is_array($GLOBALS['weNavigationItemArray'])){
		$element = end($GLOBALS['weNavigationItemArray']);
		$realvalue = $element->getNavigationField($attribs);
	} else {
		$realvalue = '';
	}

	$match = weTag_getAttribute('match', $attribs, '', we_base_request::STRING);

	switch(weTag_getAttribute('operator', $attribs, 'equal', we_base_request::STRING)){
		default:
		case 'equal':
			return $realvalue == $match;
		case 'less':
			return intval($realvalue) < intval($match);
		case 'less|equal':
			return intval($realvalue) <= intval($match);
		case 'greater':
			return intval($realvalue) > intval($match);
		case 'greater|equal':
			return intval($realvalue) >= intval($match);
		case 'contains':
			return (strpos($realvalue, $match) !== false);
		case 'isin':
			return (strpos(',' . $match . ',', ',' . $realvalue . ',') !== false);
	}
}
*/