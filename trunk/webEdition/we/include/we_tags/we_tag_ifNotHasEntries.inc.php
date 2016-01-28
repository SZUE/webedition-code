<?php

/**
 * webEdition CMS
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
function we_parse_tag_ifNotHasEntries($attribs, $content){
	return '<?php if(!' . we_tag_tagParser::printTag('ifHasEntries', $attribs) . '){ ?>' . $content . '<?php } ?>';
}

function we_tag_ifNotHasEntries($attribs){
	return !we_tag('ifHasEntries', $attribs);
}
