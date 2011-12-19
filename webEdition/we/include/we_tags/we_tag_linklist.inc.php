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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
function we_parse_tag_linklist($attribs, $content){
	return '<?php $GLOBALS[\'we_ll\']=' . we_tagParser::printTag('linklist', $attribs) . '; while($ll->next()){?>' . $content . '<?php } unset($GLOBALS[\'we_ll\']);' . we_tagParser::printTag('linklist', array('_type' => 'stop')) . ';?>';
}

function we_tag_linklist($attribs, $content){
	switch(weTag_getAttribute('_type', $attribs)){
		default:
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we/include/we_linklist.inc.php");
			$name = weTag_getAttribute("name", $attribs);
			$foo = attributFehltError($attribs, "name", "linklist");
			$hidedirindex = weTag_getAttribute("hidedirindex", $attribs, (defined('TAGLINKS_DIRECTORYINDEX_HIDE') && TAGLINKS_DIRECTORYINDEX_HIDE), true);
			$objectseourls = weTag_getAttribute("objectseourls", $attribs, (defined('TAGLINKS_OBJECTSEOURLS') && TAGLINKS_OBJECTSEOURLS), true);
			if(($foo = attributFehltError($attribs, "name", "linklist"))){
				return $foo;
			}
			$isInListview = isset($GLOBALS["lv"]);

			$linklist = ($isInListview ? $GLOBALS["lv"]->f($name) : (isset($GLOBALS['we_doc']) ? $GLOBALS['we_doc']->getElement($name) : ''));

			$ll = new we_linklist($linklist, $hidedirindex, $objectseourls,$GLOBALS['we_doc']->Name,$attribs);
			$ll->setName($name);
			return $ll;
		case 'stop':
			/* $out = $ll->getHTML(
			  (isset($GLOBALS["we_editmode"]) && $GLOBALS["we_editmode"] && (!$isInListview)), $attribs, $content, $GLOBALS['we_doc']->Name);
			 */
			return '';
	}
}