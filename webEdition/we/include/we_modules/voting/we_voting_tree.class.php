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
class we_voting_tree extends weMainTree{

	function customJSFile(){
		return we_html_element::jsScript(WE_JS_VOTING_MODULE_DIR . 'voting_tree.js');
	}

	function getJSOpenClose(){
		return '';
	}

	function getJSUpdateItem(){
		return '';
	}

	function getJSStartTree(){
		return 'var g_l={
			"save_changed_voting":"' . g_l('modules_voting', '[save_changed_voting]') . '"

			};
			function startTree(){
			frames={
	"top":' . $this->topFrame . ',
	"cmd":' . $this->cmdFrame . '
};
treeData.frames=frames;
				frames.cmd.location=treeData.frameset+"?pnt=cmd&pid=0";
				drawTree();
			}';
	}

	function getJSMakeNewEntry(){
		return '';
	}

	function getJSInfo(){
		return '';
	}

	function getJSShowSegment(){
		return '';
	}

}
