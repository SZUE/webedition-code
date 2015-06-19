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
class we_search_tree extends weTree{

	function getJSTreeCode(){
		return parent::getJSTreeCode() .
			we_html_element::jsElement('drawTree.selection_table="' . SUCHE_TABLE . '";');
	}

	function getJSStartTree(){
		return '
function startTree(){
frames={
	"top":' . $this->topFrame . ',
	"cmd":' . $this->cmdFrame . '
};
treeData.frames=frames;
	pid = arguments[0] ? arguments[0] : 0;
	offset = arguments[1] ? arguments[1] : 0;
	frames.cmd.location=treeData.frameset+"?pnt=cmd&pid="+pid+"&offset="+offset;
	drawTree();
}';
	}

	function getHTMLContruct(){
		return we_html_tools::getHtmlTop(''/* FIXME: missing title */, '', '', STYLESHEET .
				we_html_element::cssLink(CSS_DIR . 'tree.css'), we_html_element::htmlBody(array('id' => 'treetable'), '<div id="treetable" style="height:100%;border-right:1px solid black"></div>')
		);
	}

	function customJSFile(){
		return parent::customJSFile() . we_html_element::jsScript(JS_DIR . 'search_tree.js');
	}

}
