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
function we_tag_ifNotShopField(array $attribs){
	if(($foo = attributFehltError($attribs, array('name' => false, 'reference' => false, 'shopname' => false, 'match' => true), __FUNCTION__))){
		echo $foo;
		return false;
	}

	$match = weTag_getAttribute('match', $attribs, '', we_base_request::STRING);

	$attribs['type'] = 'print';
	unset($attribs['match']);

	return we_tag('shopField', $attribs) != $match;
}
