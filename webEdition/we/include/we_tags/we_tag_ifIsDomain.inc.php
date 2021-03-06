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
function we_tag_ifIsDomain(array $attribs){
	if(($foo = attributFehltError($attribs, 'domain', __FUNCTION__))){
		echo $foo;
		return false;
	}
	if((!empty($GLOBALS['we_editmode']))){
		return true;
	}

	$domain = explode(',', strtolower(weTag_getAttribute('domain', $attribs, '', we_base_request::STRING)));
	$matchType = weTag_getAttribute('matchType', $attribs, 'exact', we_base_request::STRING);
	$servername = strtolower($_SERVER['SERVER_NAME']);
	switch($matchType){
		case 'exact':
			return in_array($servername, $domain);
		case 'contains':
			foreach($domain as $d){
				if(strstr($servername, $d) !== FALSE){
					return true;
				}
			}
			return false;
		case 'front':
			foreach($domain as $d){
				if(strpos($servername, $d) === 0){
					return true;
				}
			}
			return false;
		case 'back':
			$len = strlen($servername);
			foreach($domain as $d){
				$pos = strpos($servername, $d);
				if($pos !== FALSE && ($pos + strlen($d)) === $len){
					return true;
				}
			}
			return false;
		default:
			return false;
	}
}
