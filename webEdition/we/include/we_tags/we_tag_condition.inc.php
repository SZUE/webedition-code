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
function we_parse_tag_condition($a, $content, array $attribs){
	$attribs['_type'] = 'start';
	return '<?php ' . we_tag_tagParser::printTag('condition', $attribs) . ';?>' . $content . '<?php ' . we_tag_tagParser::printTag('condition', array('_type' => 'stop')) . ';?>';
}

function we_tag_condition(array $attribs){
	$name = weTag_getAttribute('name', $attribs, 'we_lv_condition', we_base_request::STRING);
	//internal Attribute
	switch(weTag_getAttribute('_type', $attribs, '', we_base_request::STRING)){
		case 'start':

			$GLOBALS['we_lv_conditionCount'] = isset($GLOBALS['we_lv_conditionCount']) ? intval($GLOBALS['we_lv_conditionCount']) + 1 : 1;

			if($GLOBALS['we_lv_conditionCount'] == 1){
				$GLOBALS['we_lv_conditionName'] = $name;
				$GLOBALS[$GLOBALS['we_lv_conditionName']] = '(';
			} else {
				$GLOBALS[$GLOBALS['we_lv_conditionName']] .= '(';
			}
			break;
		case 'stop':
			$GLOBALS[$GLOBALS['we_lv_conditionName']] .= ')';
			$GLOBALS[$GLOBALS['we_lv_conditionName']] = str_replace('()', '', $GLOBALS[$GLOBALS['we_lv_conditionName']]);
			$GLOBALS['we_lv_conditionCount'] --;
			break;
	}
	return '';
}
