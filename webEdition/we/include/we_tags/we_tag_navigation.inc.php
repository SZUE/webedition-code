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
function we_tag_navigation(array $attribs){
	$parentid = weTag_getAttribute('parentid', $attribs, -1, we_base_request::INT);
	$id = weTag_getAttribute('id', $attribs, 0, we_base_request::INT);
	$name = weTag_getAttribute('navigationname', $attribs, 'default', we_base_request::STRING);

	if(empty($GLOBALS['we_navigation'][$name]) || !$GLOBALS['we_navigation'][$name] instanceof we_navigation_items){
		$GLOBALS['we_navigation'][$name] = new we_navigation_items();
	}
	$realId = ($id ? : ($parentid != -1 ? $parentid : 0));
	$showRoot = ($id ? true : ($parentid == -1));
	$GLOBALS['we_navigation'][$name]->init($realId, $showRoot);
}
