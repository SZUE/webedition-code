<?php

/**
 * webEdition CMS
 *
 * $Rev: 9713 $
 * $Author: mokraemer $
 * $Date: 2015-04-10 01:33:24 +0200 (Fr, 10. Apr 2015) $
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
class we_workflow_tree extends weTree{

	function customJSFile(){
		return parent::customJSFile() . we_html_element::jsScript(JS_DIR . 'workflow_tree.js');
	}

	function getJSStartTree(){
		return parent::getTree_g_l() . '
function startTree(){
			frames={
	"top":' . $this->topFrame . ',
	"cmd":' . $this->cmdFrame . '
	};
	treeData.frames=frames;
	frames.cmd.location=treeData.frameset+"?pnt=cmd&pid=0";
}';
	}

	public static function getItems($ParentId, $Offset = 0, $Segment = 500){
		$items = array();
		$db = new DB_WE();
		$db->query('SELECT ID FROM ' . WORKFLOW_TABLE . ' ORDER BY Text ASC');
		$ids = $db->getAll(true);
		foreach($ids as $id){
			$workflowDef = new we_workflow_workflow($id);
			$items[] = array(
				'icon' => 'folder.gif',
				'id' => $workflowDef->ID,
				'parentid' => 0,
				'text' => oldHtmlspecialchars(addslashes($workflowDef->Text)),
				'typ' => 'group',
				'open' => 0,
				'contentType' => 'folder',
				'table' => WORKFLOW_TABLE,
				'loaded' => 0,
				'checked' => false,
				'class' => ($workflowDef->Status ? '' : 'blue')
			);

			foreach($workflowDef->documents as $v){
				$items[] = array(
					'icon' => $v["Icon"],
					'id' => $v["ID"],
					'parentid' => $workflowDef->ID,
					'text' => oldHtmlspecialchars(addslashes($v["Text"])),
					'typ' => 'item',
					'open' => 0,
					'contentType' => 'file',
					'table' => FILE_TABLE,
				);
			}
		}

		return $items;
	}

}