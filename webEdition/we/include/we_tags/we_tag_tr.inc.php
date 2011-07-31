<?php
/**
 * webEdition CMS
 *
 * $Rev: 2814 $
 * $Author: mokraemer $
 * $Date: 2011-04-24 22:23:28 +0200 (So, 24. Apr 2011) $
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

function we_parse_tag_tr($attribs, $content) {
	//NOTE: _type is an internal attribute.
	return '<?php printElement('.we_tagParser::printTag('tr',array('_type'=>'start')).');?>' . $content . '<?php printElement('.we_tagParser::printTag('tr',array('_type'=>'end')).');?>';
}


function we_tag_tr($attribs, $content) {
		$_type = we_getTagAttribute('_type', $attribs);

switch($_type){
	case 'start':
		return ($GLOBALS["lv"]->shouldPrintStartTR() ? getHtmlTag('tr', $arr, "", false, true): '');
	case 'end':
		return ($GLOBALS["lv"]->shouldPrintEndTR()?'</tr>':'');
}
}