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
function we_parse_tag_listdir($attribs, $content){
	return '<?php
		' . we_tag_tagParser::printTag('listdir', $attribs) . ';
while(' . we_tag_tagParser::printTag('listdir', array('_internal' => true)) . '){
	?>' . $content . '<?php
}
unset($GLOBALS[\'we_position\'][\'listdir\']);
we_post_tag_listview();?>';
}

function we_tag_listdir($attribs){
	static $files = array();
	if(weTag_getAttribute('_internal', $attribs, false, we_base_request::BOOL)){
		$pos = $GLOBALS['we_position']['listdir']['position'];
		if(!isset($files[$pos])){
			return false;
		}
		$we_locfile = $files[$pos];
		$GLOBALS['lv']->field = $we_locfile['name'];
		$GLOBALS['lv']->ID = $we_locfile['ID'];
		$GLOBALS['lv']->Path = $we_locfile['Path'];
		$GLOBALS['we_position']['listdir'] = array(
			'position' => $pos + 1,
			'size' => count($files),
			'field' => $we_locfile['name'],
			'id' => $we_locfile['ID'],
			'path' => $we_locfile['Path']);
		return true;
	} else {
		$files = array();
	}

	$dirID = weTag_getAttribute('id', $attribs, $GLOBALS['we_doc']->ParentID, we_base_request::INT);
	$index = explode(',', weTag_getAttribute('index', $attribs, 'index.html,index.htm,index.php,default.htm,default.html,default.php', we_base_request::STRING));
	$name = weTag_getAttribute('field', $attribs, '', we_base_request::STRING);
	$dirfield = weTag_getAttribute('dirfield', $attribs, $name, we_base_request::STRING);
	$sort = weTag_getAttribute('order', $attribs, $name, we_base_request::STRING);
	$desc = weTag_getAttribute('desc', $attribs, false, we_base_request::BOOL);
	$searchable = weTag_getAttribute('searchable', $attribs, false, we_base_request::BOOL);

	$indexes = ' Text IN ("' . implode('","', $index) . '")';
	$db = new DB_WE();

	$db->query('SELECT ID,Text,IsFolder,Path,IF(IsFolder,(SELECT ID FROM ' . FILE_TABLE . ' WHERE ParentID=f.ID AND IsFolder=0 AND (' . $indexes . ') AND (Published>0 ' . ($searchable ? 'AND IsSearchable=1' : '') . ') LIMIT 1),0) AS FolderIndex FROM ' . FILE_TABLE . ' f WHERE ((Published>0 ' . ($searchable ? 'AND IsSearchable=1' : '') . ') OR (IsFolder=1)) AND ParentID=' . intval($dirID));

	while($db->next_record()){
		$id = intval($db->f('IsFolder') ?
						$db->f('FolderIndex') :
						$db->f('ID'));

		if($id){
			$files[] = array(
				'ID' => $db->f('ID'),
				'Path' => $db->f('Path'),
				'Text' => $db->f('Text'),
				'sort' => _listdir_getField($sort, $id, $db->f('Text')),
				'name' => _listdir_getField($dirfield, $id, $db->f('Text'))
			);
		}
	}
	$GLOBALS['we_position']['listdir'] = array('position' => 0);
	usort($files, ($sort ? 'we_cmpField' : 'we_cmpText') . ($desc ? 'Desc' : ''));
	//Fake listview
	$GLOBALS['lv'] = new stdClass();
	if(!isset($GLOBALS['we_lv_array']) || !is_array($GLOBALS['we_lv_array'])){
		$GLOBALS['we_lv_array'] = array();
	}
	$GLOBALS['we_lv_array'][] = null;
}

function _listdir_getField($field, $id, $text){
	if(!$field){
		return $text;
	}
	$db = $GLOBALS['DB_WE'];
	$dat = f('SELECT c.Dat FROM ' . LINK_TABLE . ' l JOIN ' . CONTENT_TABLE . ' c ON c.ID=l.CID WHERE l.DID=' . intval($id) . ' AND l.Name="' . $db->escape($field) . '"', '', $db);
	return $dat ? : $text;
}

function we_cmpText($a, $b){
	$x = strtolower(correctUml($a['Text']));
	$y = strtolower(correctUml($b['Text']));
	return ($x == $y ? 0 : ($x < $y ? -1 : 1));
}

function we_cmpTextDesc($a, $b){
	$x = strtolower(correctUml($a['Text']));
	$y = strtolower(correctUml($b['Text']));
	return ($x == $y ? 0 : ($x > $y ? -1 : 1));
}

function we_cmpField($a, $b){
	$x = strtolower(correctUml($a['sort']));
	$y = strtolower(correctUml($b['sort']));
	return ($x == $y ? 0 : ($x < $y ? -1 : 1));
}

function we_cmpFieldDesc($a, $b){
	$x = strtolower(correctUml($a['sort']));
	$y = strtolower(correctUml($b['sort']));
	return ($x == $y ? 0 : ($x > $y ? -1 : 1));
}
