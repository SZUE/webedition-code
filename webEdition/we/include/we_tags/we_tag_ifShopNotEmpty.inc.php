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
we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP);

/**
 * This functions checks if the shops basket is not empty
 *
 * @param          $attribs                                array
 *
 * @return         bool
 */
function we_tag_ifShopNotEmpty(array $attribs){
	if(($foo = attributFehltError($attribs, 'shopname', __FUNCTION__))){
		echo $foo;
		return false;
	}

	$shopname = weTag_getAttribute('shopname', $attribs, '', we_base_request::STRING);
	$basket = isset($GLOBALS[$shopname]) ? $GLOBALS[$shopname] : '';
	if($basket){
		$items = $basket->getShoppingItems();
		return !empty($items);
	}
	return false;
}
