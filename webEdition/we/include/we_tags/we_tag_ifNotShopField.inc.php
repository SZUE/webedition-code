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

function we_tag_ifNotShopField($attribs,$content) {
	$foo = attributFehltError($attribs, "name", "ifShopField");if($foo) return $foo;
	$foo = attributFehltError($attribs, "reference", "ifShopField");if($foo) return $foo;
	$foo = attributFehltError($attribs, "shopname", "ifShopField");if($foo) return $foo;
	$foo = attributFehltError($attribs, "match", "ifShopField", true);if($foo) return $foo;

	$match = we_getTagAttribute("match", $attribs);

	$name      = we_getTagAttribute("name", $attribs);
	$reference = we_getTagAttribute("reference", $attribs);
	$shopname  = we_getTagAttribute("shopname", $attribs);

	$attribs['type']='print';
	unset($attribs['match']);

	$realvalue = we_tag('shopField',$attribs, "");
	return $realvalue != $match;
}
