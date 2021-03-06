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
function we_parse_tag_addPercent($a, $content, array $attribs){
	$attribs['_type'] = 'stop';
	return '<?php ' . we_tag_tagParser::printTag('addPercent', array('_type' => 'start')) . ';?>' . $content . '<?php printElement(' . we_tag_tagParser::printTag('addPercent', $attribs) . ');?>';
}

function we_tag_addPercent(array $attribs, $content){
	//internal Attribute
	switch(weTag_getAttribute('_type', $attribs, '', we_base_request::STRING)){
		case 'start':
			$GLOBALS['calculate'] = 1;
			ob_start();
			return;
		case 'stop':
			$content = we_base_util::std_numberformat(ob_get_clean());
			unset($GLOBALS['calculate']);
			if(($foo = attributFehltError($attribs, 'percent', __FUNCTION__))){
				return $foo;
			}
			$percent = weTag_getAttribute('percent', $attribs, 0, we_base_request::FLOAT);
			$num_format = weTag_getAttribute('num_format', $attribs, '', we_base_request::STRING);
			$result = ($content / 100) * (100 + $percent);
			return ($num_format ? //bug 6437 gibt immer deutsch zurück (das ist der default von formatnaumber), was das verhalten ändert
							we_base_util::formatNumber($result, $num_format) :
							$result);
		default:
			return attributFehltError($attribs, '_type', __FUNCTION__);
	}
}
