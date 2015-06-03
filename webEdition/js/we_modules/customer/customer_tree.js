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

function openClose(id) {
	var sort = "";
	if (id === "") {
		return;
	}
	var eintragsIndex = indexOfEntry(id);

	if (treeData[eintragsIndex].typ == "group") {
		sort = frames.top.document.we_form_treeheader.sort.value;
	}

	var openstatus = !treeData[eintragsIndex].open;

	treeData[eintragsIndex].open = openstatus;

	if (openstatus && !treeData[eintragsIndex].loaded) {
		id = encodeURI(id);
		sort = encodeURI(sort);
		id = id.replace(/\+/g, "%2B");
		sort = sort.replace(/\+/g, "%2B");
		frames.cmd.location = treeData.frameset + "?pnt=cmd&pid=" + id + (sort !== "" ? "&sort=" + sort : "");
	} else {
		drawTree();
	}
	if (openstatus) {
		treeData[eintragsIndex].loaded = 1;
	}
}


function updateEntry(id, text) {
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].id == id) {
			text = text.replace(/</g, "&lt;");
			text = text.replace(/>/g, "&gt;");
			treeData[ai].text = text;
		}
		ai++;
	}
	drawTree();
}

function showSegment() {
	var sort = "";
	parentnode = frames.top.get(this.parentid);
	parentnode.clear();
	sort = frames.top.document.we_form_treheader.sort.value;
	we_cmd("load", parentnode.id, this.offset, sort);
}

function getLayout() {
	if (this.typ == "threedots") {
		return treeData.node_layouts.threedots;
	}
	var layout_key = (this.typ == "group" ? "group" : "item");

	return treeData.node_layouts[layout_key] + (this.typ == "item" && this.published == 1 ? " loginDenied" : "");
}

function doClick(id, typ) {
	var node = frames.top.get(id);
	if (node.typ == "item") {
		frames.top.we_cmd('customer_edit', node.id, node.typ, node.table);
	}
}

function drawCustomerGroup(nf, ai, zweigEintrag) {
	var cur = nf[ai];
	var newAst = zweigEintrag;
	var oc_js = treeData.topFrame + ".setScrollY();" + treeData.topFrame + ".openClose('" + cur.id + "')\"";
	row = "<a href=\"javascript:" + oc_js + " border=0><span class='treeKreuz fa-stack " + (ai == nf.len ? "kreuzungend" : "kreuzung") + "'><i class='fa fa-square fa-stack-1x we-color'></i><i class='fa fa-" + (nf[ai].open === 0 ? "plus" : "minus") + "-square-o fa-stack-1x'></i></span></a>";

	row += (cur.disabled ?
					"" :
					"<a name=\"_" + cur.id + "\" href=\"javascript:" + oc_js + "\">") +
					getTreeIcon(cur.contenttype, cur.open) +
					(cur.disabled ?
									"" :
									"</a><a name=\"_" + cur.id + "\" href=\"javascript:" + oc_js + "\">") +
					"<label id=\"lab_" + cur.id + "\" class=\"" + cur.getlayout() + "\">" + cur.text + "</label>" +
					(cur.disabled ?
									"" :
									"</a>") +
					"<br/>";
	if (cur.open) {
		newAst += (ai == nf.len ?
						'<span class="treeKreuz"></span>' :
						'<span class="strich treeKreuz"></span>');

		row += draw(cur.id, newAst);
	}
	return row;
}

function drawCustomerSort(nf, ai, zweigEintrag) {
	var newAst = zweigEintrag;
	var oc_js = treeData.topFrame + ".openClose('" + nf[ai].id + "')\"";

	row += "<a href=\"javascript:" + oc_js + "><span class='treeKreuz fa-stack " + (ai == nf.len ? "kreuzungend" : "kreuzung") + "'><i class='fa fa-square fa-stack-1x we-color'></i><i class='fa fa-" + (nf[ai].open === 0 ? "plus" : "minus") + "-square-o fa-stack-1x'></i></span></a>" +
					"<a name=\"_" + nf[ai].id + "\" href=\"javascript://\" onclick=\"" + oc_js + ";return true;\" border=0>" +
					getTreeIcon(nf[ai].contenttype, nf[ai].open) +
					"</a>" +
					"<a name=\"_" + nf[ai].id + "\" href=\"javascript://\" onclick=\"" + oc_js + ";return true;\">" +
					"<label id=\"lab_" + nf[ai].id + "\" class=\"" + treeData.node_layout[nf[ai].state] + "\">" + nf[ai].text + "</label>" +
					"</a>" +
					"<br/>";

	if (nf[ai].open) {
		if (ai == nf.len) {
			newAst += "<span class=\"treeKreuz\"></span>";
		} else {
			newAst += '<span class="strich treeKreuz "></span>';
		}
		row += draw(nf[ai].id, newAst);
	}
}