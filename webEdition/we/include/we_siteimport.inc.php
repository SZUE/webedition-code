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
we_html_tools::protect();

function we_siteimport_sort($a, $b){
	if($a["contentType"] == we_base_ContentTypes::WEDOCUMENT && $b["contentType"] != we_base_ContentTypes::WEDOCUMENT){
		return 1;
	} elseif($a["contentType"] != we_base_ContentTypes::WEDOCUMENT && $b["contentType"] == we_base_ContentTypes::WEDOCUMENT){
		return -1;
	}
	return 0;
}

$import_object = new we_import_site();

echo $import_object->getHTML();
