/* global node, treeData, container,drawTree, WE, top */

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
'use strict';

container.prototype.openClose = function (id) {
	var sort = "";
	if (id === "") {
		return;
	}
	var eintragsIndex = treeData.indexOfEntry(id);
	if (eintragsIndex === -1) {
		return;
	}

	if (eintragsIndex === -1) {
		return;
	}

	if (treeData[eintragsIndex].typ === "group") {
		sort = top.content.document.we_form_treeheader.sort.value;
	}

	var openstatus = !treeData[eintragsIndex].open;

	treeData[eintragsIndex].open = openstatus;

	if (openstatus && !treeData[eintragsIndex].loaded) {
		id = encodeURI(id);
		sort = encodeURI(sort);
		id = id.replace(/\+/g, "%2B");
		sort = sort.replace(/\+/g, "%2B");
		top.content.cmd.location = top.getFrameset() + "&pnt=cmd&pid=" + id + (sort !== "" ? "&sort=" + sort : "");
	} else {
		drawTree();
	}
	if (openstatus) {
		treeData[eintragsIndex].loaded = true;
	}
};

Node.prototype.showSegment = function () {
	var sort = "";
	var parentnode = this.get(this.parentid);
	parentnode.clear();
	sort = top.document.we_form_treheader.sort.value;
	window.we_cmd("load", parentnode.id, this.offset, sort);
};

Node.prototype.getLayout = function () {
	if (this.typ === "threedots") {
		return treeData.node_layouts.threedots;
	}
	var layout_key = (this.typ === "group" ? "group" : "item");

	return treeData.node_layouts[layout_key] + (this.typ === "item" ? (this.published ? "" : " loginDenied") : "");
};

function doClick(id, typ) {
	var node = treeData.get(id);
	if (node.typ === "item") {
		window.we_cmd('customer_edit', node.id, node.typ, node.table);
	}
}

container.prototype.drawGroup = function (nf, ai, zweigEintrag) {
	var cur = nf[ai];
	var newAst = zweigEintrag;
	var oc_js = "top.content.treeData.openClose('" + cur.id + "')\"";
	var row = "<span onclick=\"" + oc_js + " class='treeKreuz fa-stack " + (ai == nf.len ? "kreuzungend" : "kreuzung") + "'><i class='fa fa-square fa-stack-1x we-color'></i><i class='fa fa-caret-" + (nf[ai].open ? "down" : "right") + " fa-stack-1x'></i></span>" +
		'<span ' +
		(cur.disabled ? "" : "name=\"_" + cur.id + "\" onclick=\"" + oc_js + "\"") +
		">" +
		WE().util.getTreeIcon(cur.contenttype, cur.open) +
		"<label id=\"lab_" + cur.id + "\" class=\"" + cur.getLayout() + "\">" + cur.text + "</label>" +
		"</span><br/>";
	if (cur.open) {
		newAst += '<span class="' + (ai == nf.len ? "" : "strich ") + 'treeKreuz"></span>';
		row += this.draw(cur.id, newAst);
	}
	return row;
};

container.prototype.drawSort = function (nf, ai, zweigEintrag) {
	var oc_js = "top.content.treeData.openClose('" + nf[ai].id + "')\"";

	return "<span onclick=\"" + oc_js + " class='treeKreuz fa-stack " + (ai == nf.len ? "kreuzungend" : "kreuzung") + "'><i class='fa fa-square fa-stack-1x we-color'></i><i class='fa fa-caret-" + (nf[ai].open ? "down" : "right") + " fa-stack-1x'></i></span>" +
		"<span name=\"_" + nf[ai].id + "\" onclick=\"" + oc_js + ";\">" +
		WE().util.getTreeIcon(nf[ai].contenttype, nf[ai].open) +
		"<label id=\"lab_" + nf[ai].id + "\" class=\"" + this.node_layout[nf[ai].state] + "\">" + nf[ai].text + "</label>" +
		"</span>" +
		"<br/>" +
		(nf[ai].open ?
			this.draw(nf[ai].id, zweigEintrag + '<span class="' + (ai == nf.len ? "" : "strich ") + 'treeKreuz"></span>') :
			"");
};

function applySort() {
	document.we_form_treeheader.pnt.value = "cmd";
	document.we_form_treeheader.cmd.value = "applySort";
	submitForm("", "", "", "we_form_treeheader");
}

function addSorting(sortname) {
	var len = document.we_form_treeheader.sort.options.length;
	for (var i = 0; i < len; i++) {
		if (document.we_form_treeheader.sort.options[i].value == sortname) {
			return;
		}
	}
	document.we_form_treeheader.sort.options[len] = new window.Option(sortname, sortname);

}
function submitForm(target, action, method, form) {
	var f = form ? window.document.forms[form] : window.document.we_form;
	f.target = target ? target : "cmd";
	f.action = action ? action : WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=customer";
	f.method = method ? method : "post";

	f.submit();
}