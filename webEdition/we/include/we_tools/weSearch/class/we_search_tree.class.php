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
class we_search_tree extends we_tree_base{
	static $treeItems = array();

	function getJSTreeCode(){
		return parent::getJSTreeCode() .
			we_html_element::jsElement('drawTree.selection_table="' . SUCHE_TABLE . '";');
	}

	protected function customJSFile(){
		return we_html_element::jsScript(JS_DIR . 'search_tree.js');
	}

	public static function getItemsFromDB($ParentID = 0, $offset = 0, $segment = 500){
		$db = new DB_WE();
		$openFolders = array();

		if(isset($_SESSION['weS']['weSearch']["modelidForTree"])){
			$id = $_SESSION['weS']['weSearch']["modelidForTree"];
			$pid = f('SELECT ParentID FROM ' . $db->escape(SUCHE_TABLE) . ' WHERE ID=' . intval($id), '', $db);
			$openFolders[] = $pid;
			while($pid > 0){
				$pid = f('SELECT ParentID FROM ' . $db->escape(SUCHE_TABLE) . ' WHERE ID=' . intval($pid), '', $db);
				$openFolders[] = $pid;
			}
		}

		$wsQuery = '';
		$prevoffset = max(0, $offset - $segment);
		if($offset && $segment){
			self::$treeItems[] = array(
				'id' => 'prev_' . $ParentID,
				'parentid' => $ParentID,
				'text' => 'display (' . $prevoffset . '-' . $offset . ')',
				'contenttype' => 'arrowup',
				'table' => SUCHE_TABLE,
				'typ' => 'threedots',
				'open' => 0,
				'published' => 0,
				'disabled' => 0,
				'tooltip' => '',
				'offset' => $prevoffset
			);
		}

		$where = ' WHERE ' . $wsQuery . ' ParentID=' . intval($ParentID) . ' ';

		$db->query('SELECT ID,ParentID,Path,Text,IsFolder FROM ' . $db->escape(SUCHE_TABLE) . $where . ' ORDER BY (Text REGEXP "^[0-9]") DESC,abs(Text),Text' . ($segment ? ' LIMIT ' . abs($offset) . ',' . abs($segment) : ''));

		while($db->next_record(MYSQL_ASSOC)){//FIXME: this is no good code
			if(($db->f('ID') == 3 || $db->f('ID') == 7) && (!defined('OBJECT_FILES_TABLE') || !defined('OBJECT_TABLE') || !permissionhandler::hasPerm('CAN_SEE_OBJECTFILES'))){

			} elseif(($db->f('ID') == 2 || $db->f('ID') == 4 || $db->f('ID') == 5 || $db->f('ID') == 6) && !permissionhandler::hasPerm('CAN_SEE_DOCUMENTS')){

			} elseif(strpos($db->f('Path'), '/Versionen') === 0 && !permissionhandler::hasPerm('SEE_VERSIONS')){

			} else {
				$OpenCloseStatus = (in_array($db->f('ID'), $openFolders) ? 1 : 0);

				$typ = array(
					'typ' => ($db->f('IsFolder') ? 'group' : 'item'),
					'contentType' => ($db->f('IsFolder') ? 'folder' : 'we/search'),
					'open' => $OpenCloseStatus,
					'disabled' => 0,
					'tooltip' => $db->f('ID'),
					'offset' => $offset,
					'order' => $db->f('Ordn'),
					'published' => 1,
					'disabled' => 0,
					'text' => oldHtmlspecialchars(we_search_model::getLangText($db->f('Path'), $db->f('Text'))),
				);
				$fields = array();

				foreach($db->Record as $k => $v){
					$fields[strtolower($k)] = $v;
				}

				self::$treeItems[] = array_merge($fields, $typ);

				if($typ['typ'] === "group" && $OpenCloseStatus == 1){
					self::getItemsFromDB($db->f('ID'), 0, $segment);
				}
			}
		}

		$total = f('SELECT COUNT(1) FROM `' . $db->escape(SUCHE_TABLE) . "` $where", '', $db);
		$nextoffset = $offset + $segment;
		if($segment && ($total > $nextoffset)){
			self::$treeItems[] = array(
				'id' => 'next_' . $ParentID,
				'parentid' => $ParentID,
				'text' => 'display (' . $nextoffset . '-' . ($nextoffset + $segment) . ')',
				'contenttype' => 'arrowdown',
				'table' => SUCHE_TABLE,
				'typ' => 'threedots',
				'open' => 0,
				'disabled' => 0,
				'tooltip' => '',
				'offset' => $nextoffset
			);
		}

		return self::$treeItems;
	}

}
