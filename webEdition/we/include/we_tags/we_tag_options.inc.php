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
function we_tag_options(array $attribs){
	$name = weTag_getAttribute('name', $attribs, '', we_base_request::STRING);
	$classid = weTag_getAttribute('classid', $attribs, 0, we_base_request::INT);
	$field = weTag_getAttribute('field', $attribs, '', we_base_request::STRING);

	$o = '';
	if($classid && $field){
		if(!isset($GLOBALS['WE_OBJECT_DEFARRAY'])){
			$GLOBALS['WE_OBJECT_DEFARRAY'] = array();
		}
		if(!isset($GLOBALS['WE_OBJECT_DEFARRAY']['cid_' . $classid])){
			$GLOBALS['WE_OBJECT_DEFARRAY']['cid_' . $classid] = we_unserialize(f('SELECT DefaultValues FROM ' . OBJECT_TABLE . ' WHERE ID=' . intval($classid)));
		}
		$foo = $GLOBALS['WE_OBJECT_DEFARRAY']['cid_' . $classid]['meta_' . $field]['meta'];
		foreach($foo as $key => $val){
			$o .= '<option value="' . $key . '"' . ((($GLOBALS[$name] == $key) && strlen($GLOBALS[$name]) != 0) ? ' selected="selected"' : '') . '>' . $val . '</option>';
		}
		return $o;
	}
	return '';
}
