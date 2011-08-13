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

function we_tag_objectLanguage($attribs, $content){
	$type = weTag_getAttribute("type", $attribs, "complete");
	$case = weTag_getAttribute("case", $attribs, "unchanged");

	if (isset($GLOBALS['lv']) && isset($GLOBALS['lv']->object->DB_WE->Record['OF_Language'])){
		$lang=$GLOBALS['lv']->object->DB_WE->Record['OF_Language'];
	} elseif (isset($GLOBALS['lv']) && isset($GLOBALS['lv']->DB_WE->Record['OF_Language'])) {
		$lang=$GLOBALS['lv']->DB_WE->Record['OF_Language'];
	} else {
		$lang='';
	}
	$out="";
	switch ($type){
		case "language":
			$out=substr($lang,0,2);
			break;
		case "country":
			$out=substr($lang,3,2);
			break;
		default:
			$out=$lang;
	}
	switch ($case){
		case "uppercase":
			$out= strtoupper ($out);
			break;
		case "lowercase":
			$out= strtolower ($out);
			break;
		default:
			$out=$out;
	}
	return $out;
}
