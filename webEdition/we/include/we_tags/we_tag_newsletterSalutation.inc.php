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
function we_tag_newsletterSalutation(array $attribs){
	$type = trim(weTag_getAttribute('type', $attribs, '', we_base_request::STRING));
	switch($type){
		case 'customerid':
			return (!empty($GLOBALS['WE_CUSTOMERID'])) ? $GLOBALS['WE_CUSTOMERID'] : '';
		case 'title':
			return isset($GLOBALS['WE_TITLE']) ? $GLOBALS['WE_TITLE'] : '';
		case 'firstname':
			return isset($GLOBALS['WE_FIRSTNAME']) ? $GLOBALS['WE_FIRSTNAME'] : '';
		case 'lastname':
			return (isset($GLOBALS['WE_LASTNAME']) ) ? $GLOBALS['WE_LASTNAME'] : '';
		case 'email':
			return isset($GLOBALS['WE_MAIL']) ? $GLOBALS['WE_MAIL'] : (isset($GLOBALS['WE_NEWSLETTER_EMAIL']) ? $GLOBALS['WE_NEWSLETTER_EMAIL'] : '');
		case 'salutation':
		default:
			return isset($GLOBALS['WE_SALUTATION']) ? $GLOBALS['WE_SALUTATION'] : '';
	}
}
