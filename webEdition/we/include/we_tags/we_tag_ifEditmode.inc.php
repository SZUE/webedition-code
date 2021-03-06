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
function we_tag_ifEditmode(array $attribs){
	switch(weTag_getAttribute('doc', $attribs, '', we_base_request::STRING)){
		case 'self':
			return !empty($GLOBALS['we_editmode']) && (!empty($GLOBALS['WE_MAIN_ID'])) && $GLOBALS['WE_MAIN_ID'] == $GLOBALS['we_doc']->ID;
		default:
			return (!empty($GLOBALS['we_editmode'])) || !empty($GLOBALS['WE_MAIN_EDITMODE'])/* || (isset($_SESSION['weS']['we_mode']) && $_SESSION['weS']['we_mode'] == 'seem') */;
	}
}
