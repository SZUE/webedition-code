/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package    we_ui
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

function treeStartDrag(evt, type, table, id, ct) {
	evt.dataTransfer.setData('text', type + ',' + table + ',' + id + ',' + ct);
}

function getTreeLayout() {
	return this.tree_layouts[this.state];
}

function setTreeState() {
	this.state = arguments[0];
	if (this.state == this.tree_states.edit) {
		for (var i = 1; i <= this.len; i++) {
			if (this[i].checked == 1) {
				this[i].checked = 0;
			}
		}
	}
}

function applyLayout() {
	eval("if(" + treeData.treeFrame + ".document.getElementById(\"lab_" + this.id + "\"))" + treeData.treeFrame + ".document.getElementById(\"lab_" + this.id + "\").className =\"" +
					(arguments[0] ? arguments[0] : this.getlayout()) +
					"\";");
}

function rootEntry(id, text, rootstat, offset) {
	this.id = id;
	this.text = text;
	this.open = 1;
	this.loaded = 1;
	this.typ = "root";
	this.offset = offset;
	this.rootstat = rootstat;
	this.showsegment = showSegment;
	this.clear = clearItems;

	return this;
}

function node(attribs) {
	for (var aname in attribs) {
		var val = attribs[aname];
		this[aname] = val;
	}

	this.getlayout = getLayout;
	this.applylayout = applyLayout;
	this.showsegment = showSegment;
	this.clear = clearItems;
	return this;
}

function selectNode() {
	if (arguments[0]) {
		var ind;
		if (treeData.selection !== "" && treeData.selection_table == treeData.table) {
			ind = indexOfEntry(treeData.selection);
			if (ind != -1) {
				var oldnode = get(treeData.selection);
				oldnode.selected = 0;
				oldnode.applylayout();
			}
		}
		ind = indexOfEntry(arguments[0]);
		if (ind != -1) {
			var newnode = get(arguments[0]);
			newnode.selected = 1;
			newnode.applylayout();
		}
		treeData.selection = arguments[0];
		treeData.selection_table = treeData.table;
	}
}

function unselectNode() {
	if (treeData.selection !== "" && treeData.table == treeData.selection_table) {
		var ind = indexOfEntry(treeData.selection);
		if (ind != -1) {
			var node = get(treeData.selection);
			node.selected = 0;
			if (node.applylayout)
				node.applylayout();
		}
		treeData.selection = "";
	}
}

function deleteEntry(id) {
	var ai = 1;
	var ind = 0;
	while (ai <= treeData.len) {
		if (treeData[ai].id == id) {
			ind = ai;
			break;
		}
		ai++;
	}
	if (ind !== 0) {
		ai = ind;
		while (ai <= treeData.len - 1) {
			treeData[ai] = treeData[ai + 1];
			ai++;
		}
		treeData.len[treeData.len] = null;
		treeData.len--;
		drawTree();
	}
}

function makeFoldersOpenString() {
	var op = "";
	for (i = 1; i <= treeData.len; i++) {
		if (treeData[i].typ == "group" && treeData[i].open == 1) {
			op += treeData[i].id + ",";
		}
	}
	op = op.substring(0, op.length - 1);
	return op;
}

function clearTree() {
	treeData.clear();
}

function parentChecked(start) {
	var obj = top.treeData;
	for (var i = 1; i <= obj.len; i++) {
		if (obj[i].id == start) {
			if (obj[i].checked == 1) {
				return true;
			}

			if (obj[i].parentid !== 0) {
				parentChecked(obj[i].parentid);
			}
		}
	}

	return false;
}

function setCheckNode(imgName) {
	if (document.images[imgName]) {
		document.images[imgName].src = "/webEdition/images/tree/check0.gif";
	}
}

function setUnCheckNode(imgName) {
	if (document.images[imgName]) {
		document.images[imgName].src = "/webEdition/images/tree/check1.gif";
	}
}

function clearItems() {
	var ai = 1;
	var deleted = 0;

	while (ai <= treeData.len) {
		if (treeData[ai].parentid == this.id) {
			if (treeData[ai].contenttype == "group") {
				deleted += treeData[ai].clear();
			} else {
				ind = ai;
				while (ind <= treeData.len - 1) {
					treeData[ind] = treeData[ind + 1];
					ind++;
				}
				treeData.len[treeData.len] = null;
				treeData.len--;
			}
			deleted++;
		} else {
			ai++;
		}
	}
	drawTree();
	return deleted;
}


function clickHandler(cur) {
	var row = "";
	if (treeData.selection_table == treeData.table && cur.id == treeData.selection) {
		cur.selected = 1;
	}
	if (cur.disabled != 1) {
		if (treeData.state == treeData.tree_states.select) {
			row += "<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">";
		} else if (treeData.state == treeData.tree_states.selectitem && cur.typ == "item") {
			row += "<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">";
		} else if (treeData.state == treeData.tree_states.selectgroup && cur.typ == "group") {
			row += "<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">";
		} else {
			row += "<a name=\"_" + cur.id + "\" href=\"javascript://\"  ondblclick=\"" + treeData.topFrame + ".wasdblclick=1;clearTimeout(" + treeData.topFrame + ".tout);" + treeData.topFrame + ".doClick('" + cur.id + "');return true;\" onclick=\"" + treeData.topFrame + ".tout=setTimeout('if(" + treeData.topFrame + ".wasdblclick==0){ " + treeData.topFrame + ".doClick(\\'" + cur.id + "\\'); }else{ " + treeData.topFrame + ".wasdblclick=0;}',300);return true;\" onmouseover=\"" + treeData.topFrame + ".info('ID:" + (cur.we_id ? cur.we_id : cur.id) + "')\" onmouseout=\"" + treeData.topFrame + ".info(' ');\">";
		}
	}
	row += "<img src=" + treeData.tree_icon_dir + cur.icon + " alt=\"\">" +
					(cur.disabled != 1 ?
									"</a>" :
									""
									);
	if (cur.disabled != 1) {
		switch (treeData.state) {
			case treeData.tree_states.selectitem:
				row += (cur.typ == "group" ?
								"<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\">&nbsp;" + cur.text + "</label>" :
								"<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\"><img src=\"" + treeData.tree_image_dir + (cur.checked == 1 ? "check1.gif" : "check0.gif") + "\" alt=\"\" name=\"img_" + cur.id + "\"></a>" +
								"<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\" onclick=\"" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">&nbsp;" + cur.text + "</label>"
								);
				break;
			case treeData.tree_states.selectgroup:
				row += (cur.typ == "item" ?
								"<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\">&nbsp;" + cur.text + "</label>" :
								"<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\"><img src=\"" + treeData.tree_image_dir + (cur.checked == 1 ? "check1.gif" : "check0.gif") + "\" alt=\"\" name=\"img_" + cur.id + "\"></a>" +
								"<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\" onclick=\"" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">&nbsp;" + cur.text + "</label>"
								);
				break;
			case treeData.tree_states.select:
				row += "<a href=\"javascript:" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\"><img src=\"" + treeData.tree_image_dir + (cur.checked == 1 ? "check1.gif" : "check0.gif") + "\" alt=\"\" name=\"img_" + cur.id + "\"></a>" +
								"<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\" onclick=\"" + treeData.topFrame + ".checkNode('img_" + cur.id + "')\">&nbsp;" + cur.text + "</label>";

				break;
			default:
				row += "<a ondragstart=\"treeStartDrag(event,('" + cur.contenttype + "' === 'folder' ? 'dragFolder' : 'dragItem'),'" + cur.table + "'," + parseInt(cur.id) + ", '" + cur.contenttype + "')\" name=\"_" + cur.id + "\" href=\"javascript://\"  onDblClick=\"" + treeData.topFrame + ".wasdblclick=1;clearTimeout(" + treeData.topFrame + ".tout);" + treeData.topFrame + ".doClick('" + cur.id + "');return true;\" onclick=\"" + treeData.topFrame + ".tout=setTimeout('if(" + treeData.topFrame + ".wasdblclick==0)" + treeData.topFrame + ".doClick(\\'" + cur.id + "\\'); else " + treeData.topFrame + ".wasdblclick=0;',300);return true;\" onMouseOver=\"" + treeData.topFrame + ".info('ID:" + (cur.we_id ? cur.we_id : cur.id) + "')\" onMouseOut=\"" + treeData.topFrame + ".info(' ');\"><label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\">&nbsp;" + cur.text + "</label></a>";
		}
	} else {
		row += "<label id=\"lab_" + cur.id + "\"" + (cur.tooltip !== "" ? " title=\"" + cur.tooltip + "\"" : "") + " class=\"" + cur.getlayout() + "\">&nbsp;" + cur.text + "</label>";
	}
	row += "&nbsp;&nbsp;<br/>";
	return row;
}

function drawItem(nf, ai) {
	return "&nbsp;&nbsp;<img src=\"" + treeData.tree_image_dir + (ai == nf.len ? "kreuzungend.gif" : "kreuzung.gif") + "\" class=\"treeKreuz\" >" + clickHandler(nf[ai]);
}

function drawThreeDots(nf, ai) {
	return "&nbsp;&nbsp;<img src=\"" + treeData.tree_image_dir + (ai == nf.len ? "kreuzungend.gif" : "kreuzung.gif") + "\" class=\"treeKreuz\" >" +
					"<a name=\"_" + nf[ai].id + "\" href=\"javascript://\"  onclick=\"" + treeData.topFrame + ".setSegment('" + nf[ai].id + "');return true;\">" +
					"<img src=\"" + treeData.tree_image_dir + nf[ai].icon + "\" style=\"width:100px;height:7px\" alt=\"\">" +
					"</a>" +
					"&nbsp;&nbsp;<br/>";

}

function drawGroup(nf, ai, zweigEintrag) {
	var newAst = zweigEintrag;

	row = "&nbsp;&nbsp;<a href=\"javascript:" + treeData.topFrame + ".setScrollY();" + treeData.topFrame + ".openClose('" + nf[ai].id + "')\"><img src=" + treeData.tree_image_dir + (nf[ai].open === 0 ? "auf" : "zu") + (ai == nf.len ? "end" : "") + ".gif class=\"treeKreuz\" alt=\"\"></a>";

	if (nf[ai].contenttype !== 'text/weCollection') {
		nf[ai].icon = "folder" + (nf[ai].open == 1 ? "open" : "") + (nf[ai].disabled == 1 ? "_disabled" : "") + ".gif";
	}

	row += clickHandler(nf[ai]);

	if (nf[ai].open == 1) {
		newAst += "<img src=\"" + treeData.tree_image_dir + (ai == nf.len ? "leer.gif" : "strich2.gif") + "\" class=\"treeKreuz\"/>";
		row += draw(nf[ai].id, newAst);
	}
	return row;
}

function indexOfEntry(id) {
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].id == id) {
			return ai;
		}
		ai++;
	}
	return -1;
}

function get(eintrag) {
	var nf = new container();
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].id == eintrag) {
			nf = treeData[ai];
		}
		ai++;
	}
	return nf;
}

function search(eintrag) {
	var nf = new container();
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].parentid == eintrag) {
			nf.add(treeData[ai]);
		}
		ai++;
	}
	return nf;
}

function add(object) {
	this[++this.len] = object;
}

function containerClear() {
	this.len = 0;
}

function updateTreeAfterDel(ind) {
	if (ind !== 0) {
		ai = ind;
		while (ai <= menuDaten.laenge - 1) {
			menuDaten[ai] = menuDaten[ai + 1];
			ai++;
		}
		menuDaten.laenge[menuDaten.laenge] = null;
		menuDaten.laenge--;
		drawEintraege();
	}
}

var startloc = 0;
var treeHTML;
self.focus();