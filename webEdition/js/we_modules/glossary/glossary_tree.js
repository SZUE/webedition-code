/* global node, treeData, container, top, WE */

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

container.prototype.openClose = function(id) {
	var sort = "";
	if (id === "") {
		return;
	}
	var eintragsIndex = treeData.indexOfEntry(id);
	var openstatus = (treeData[eintragsIndex].open ? 0 : 1);

	treeData[eintragsIndex].open = openstatus;

	if (openstatus && !treeData[eintragsIndex].loaded) {
		treeData.frames.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=cmd&pid=" + id + (sort !== "" ? ("&sort=" + sort) : "");
	} else {
		drawTree();
	}
	if (openstatus) {
		treeData[eintragsIndex].loaded = true;
	}
};

function doClick(id, typ) {
	var node;
	if (top.content.hot === 1) {
		if (confirm(WE().consts.g_l.glossary.view.save_changed_glossary)) {
			top.content.we_cmd("save_glossary");
		} else {
			top.content.usetHot();
			node = treeData.get(id);
			treeData.frames.top.editor.edbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=edbody&cmd=" + node.cmd + "&cmdid=" + node.id + "&tabnr=" + treeData.frames.top.activ_tab;
		}
	} else {
		node = treeData.get(id);
		treeData.frames.top.editor.edbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=edbody&cmd=" + node.cmd + "&cmdid=" + node.id + "&tabnr=" + treeData.frames.top.activ_tab;
	}
}

function info(text) {
}

node.prototype.showSegment = function () {
	parentnode = this.get(this.parentid);
	parentnode.clear();
	treeData.frames.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=cmd&pid=" + this.parentid + "&offset=" + this.offset;
	drawTree();
};
