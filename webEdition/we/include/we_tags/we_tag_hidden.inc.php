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
function we_tag_hidden($attribs){

	if(($foo = attributFehltError($attribs, "name", __FUNCTION__))){
		return $foo;
	}

	$name = weTag_getAttribute("name", $attribs, '', we_base_request::STRING);
	$xml = weTag_getAttribute('xml', $attribs, XHTML_DEFAULT, we_base_request::BOOL);

	switch(weTag_getAttribute("type", $attribs, '', we_base_request::STRING)){
		case 'session' :
			$value = $_SESSION[$name];
			break;
		case 'request' :
			$value = filterXss(we_base_util::rmPhp(we_base_request::_(we_base_request::RAW, $name, '')));
			break;
		default :
			$value = isset($GLOBALS[$name]) ? $GLOBALS[$name] : '';
			break;
	}

	return getHtmlTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value, 'xml' => $xml));
}
