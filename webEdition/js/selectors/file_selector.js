/* global top, WE */

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

var allIDs = "";
var allPaths = "";
var allTexts = "";
var allIsFolder = "";
var ctrlpressed = false;
var shiftpressed = false;
var wasdblclick = false;
var inputklick = false;
var tout = null;

function applyOnEnter(evt) {
	_elemName = "target";
	if (evt.srcElement !== undefined) { // IE
		_elemName = "srcElement";
	}

	if (!(evt[_elemName].tagName === "SELECT" ||
					(evt[_elemName].tagName === "INPUT" && evt[_elemName].name !== "fname")
					)) {
		top.press_ok_button();
		return true;
	}

}
function closeOnEscape() {
	top.exit_close();
}

function orderIt(o) {
	order = o + (order === o ? " DESC" : "");
	top.fscmd.location.replace(top.queryString(WE().consts.selectors.CMD, top.currentDir, order));
}

function goBackDir() {
	setDir(parentID);
}

function getEntry(id) {
	for (var i = 0; i < top.entries.length; i++) {
		if (top.entries[i].ID == id) {
			return top.entries[i];
		}
	}
	return {
		"ID": 0,
		"icon": "",
		"text": "/",
		"isFolder": 1,
		"path": "/"
	};
}

function clearEntries() {
	entries = [];
}

function exit_close() {
	self.close();
}

function doClick(id, ct) {
	if (ct == 1) {
		if (wasdblclick) {
			setDir(id);
			setTimeout(function () {
				wasdblclick = false;
			}, 400);
		}
	} else if (top.options.multiple) {
		if (top.shiftpressed) {
			var oldid = top.currentID;
			var currendPos = getPositionByID(id);
			var firstSelected = getFirstSelected();

			if (currendPos > firstSelected) {
				selectFilesFrom(firstSelected, currendPos);
			} else if (currendPos < firstSelected) {
				selectFilesFrom(currendPos, firstSelected);
			} else {
				selectFile(id);
			}
			top.currentID = oldid;
		} else if (!top.ctrlpressed) {
			selectFile(id);
		} else if (isFileSelected(id)) {
			unselectFile(id);
		} else {
			selectFile(id);
		}
	} else {
		selectFile(id);

	}
	if (top.ctrlpressed) {
		top.ctrlpressed = 0;
	}
	if (top.shiftpressed) {
		top.shiftpressed = 0;
	}
}

function setDir(id) {
	e = getEntry(id);
	top.currentID = id;
	top.currentDir = id;
	top.currentPath = e.path;
	top.currentText = e.text;
	top.document.getElementsByName("fname")[0].value = e.text;
	top.fscmd.location.replace(top.queryString(WE().consts.selectors.CMD, id));
}

function setRootDir() {
	setDir(options.rootDirID);
}

function selectFile(id) {
	var a = top.document.getElementsByName("fname")[0];
	if (id) {
		e = getEntry(id);

		if (
						a.value != e.text &&
						a.value.indexOf(e.text + ",") == -1 &&
						a.value.indexOf("," + e.text + ",") == -1 &&
						a.value.indexOf("," + e.text + ",") == -1) {

			a.value = a.value ?
							(a.value + "," + e.text) :
							e.text;
		}
		top.fsbody.document.getElementById("line_" + id).style.backgroundColor = "#DFE9F5";
		top.currentPath = e.path;
		top.currentID = id;
	} else {
		a.value = "";
		top.currentPath = "";
	}
}

function addEntry(id, txt, folder, pth, ct) {
	entries.push({
		ID: id,
		text: txt,
		isFolder: folder,
		path: pth,
		contentType: ct
	});
}

function writeBody(d) {
	var body = '<table class="selector">';
	for (i = 0; i < entries.length; i++) {
		var onclick = ' onclick="return selectorOnClick(event,' + entries[i].ID + ');"';
		var ondblclick = ' onDblClick="return selectorOnDblClick(' + entries[i].ID + ');"';
		body += '<tr' + ((entries[i].ID == top.currentID) ? ' style="background-color:#DFE9F5;"' : '') + ' id="line_' + entries[i].ID + '"' + onclick + (entries[i].isFolder ? ondblclick : '') + ' >' +
						'<td class="selector selectoricon">' + WE().util.getTreeIcon(entries[i].contentType, false) + '</td>' +
						'<td class="selector filename"  title="' + entries[i].text + '"><div class="cutText">' + entries[i].text + '</div></td>' +
						'</tr>';
	}
	body += '</table>';
	d.innerHTML = body;
}

function getFirstSelected() {
	for (var i = 0; i < entries.length; i++) {
		if (top.fsbody.document.getElementById("line_" + entries[i].ID).style.backgroundColor != "white") {
			return i;
		}
	}
	return -1;
}

function unselectFile(id) {
	e = getEntry(id);
	top.fsbody.document.getElementById("line_" + id).style.backgroundColor = "white";

	var foo = top.document.getElementsByName("fname")[0].value.split(/,/);

	for (var i = 0; i < foo.length; i++) {
		if (foo[i] == e.text) {
			foo[i] = "";
			break;
		}
	}
	var str = "";
	for (i = 0; i < foo.length; i++) {
		if (foo[i]) {
			str += foo[i] + ",";
		}
	}
	str = str.replace(/(.*),$/, "$1");
	top.document.getElementsByName("fname")[0].value = str;
}


function selectFilesFrom(from, to) {
	unselectAllFiles();
	for (var i = from; i <= to; i++) {
		selectFile(entries[i].ID);
	}
}

function getPositionByID(id) {
	for (var i = 0; i < entries.length; i++) {
		if (entries[i].ID == id) {
			return i;
		}
	}
	return -1;
}

function isFileSelected(id) {
	return (top.fsbody.document.getElementById("line_" + id).style.backgroundColor && (top.fsbody.document.getElementById("line_" + id).style.backgroundColor != "white"));
}

function unselectAllFiles() {
	for (var i = 0; i < entries.length; i++) {
		if ((elem = top.fsbody.document.getElementById("line_" + entries[i].ID))) {
			elem.style.backgroundColor = "white";
		}
	}
	top.document.getElementsByName("fname")[0].value = "";
	top.disableDelBut();
}

function queryString(what, id, o) {
	if (!o) {
		o = top.order;
	}
	return options.formtarget + 'what=' + what + '&table=' + options.table + '&id=' + id + "&order=" + o + "&filter=" + currentType;
}

function fillIDs(asArray) {
	allIDs = [];
	allPaths = [];
	allTexts = [];
	allIsFolder = [];

	for (var i = 0; i < entries.length; i++) {
		if (isFileSelected(entries[i].ID)) {
			allIDs.push(entries[i].ID);
			allPaths.push(entries[i].path);
			allTexts.push(entries[i].text);
			allIsFolder.push(entries[i].isFolder);
		}
	}
	if (top.currentID !== "" && allIDs.indexOf(top.currentID) === -1) {
		allIDs.push(top.currentID);
	}
	if (top.currentPath !== "" && allPaths.indexOf(top.currentPath) === -1) {
		allPaths.push(top.currentPath);
		allTexts.push(we_makeTextFromPath(top.currentPath));
	}

	if (!asArray) {
		allIDs = allIDs.join(',');
		allPaths = allPaths.join(',');
		allTexts = allTexts.join(',');
		allIsFolder = allIsFolder.join(',');
		//keep old behaviour
		if (allIDs) {
			allIDs = "," + allIDs + ",";
			allPaths = "," + allPaths + ",";
			allTexts = "," + allTexts + ",";
			allIsFolder = "," + allIsFolder + ",";
		}
	}
}

function we_makeTextFromPath(path) {
	position = path.lastIndexOf("/");
	if (position > -1 && position < path.length) {
		return path.substring(position + 1);
	}
	return "";
}

function weonclick(e) {
	if (document.all) {
		if (e.ctrlKey || e.altKey) {
			ctrlpressed = true;
		}
		if (e.shiftKey) {
			shiftpressed = true;
		}
	} else {
		if (e.altKey || e.metaKey || e.ctrlKey) {
			ctrlpressed = true;
		}
		if (e.shiftKey) {
			shiftpressed = true;
		}
	}
	if (top.options.multiple) {
		if ((self.shiftpressed === false) && (self.ctrlpressed === false)) {
			top.unselectAllFiles();
		}
	} else {
		top.unselectAllFiles();
	}
}

function elementSelected() {
	return top.document.getElementsByName("fname")[0].value !== "";
}

function press_ok_button() {
	if (elementSelected()) {
		top.exit_open();
	} else {
		top.exit_close();
	}
}

function disableDelBut() {
	WE().layout.button.switch_button_state(document, "delete", "disabled");
	WE().layout.button.switch_button_state(document, "btn_function_trash", "disabled");
	changeCatState = 0;
}

function enableDelBut() {
	WE().layout.button.switch_button_state(document, "delete", "enabled");
	if (top.options.userCanEditCat) {
		WE().layout.button.switch_button_state(document, "btn_function_trash", "enabled");
		changeCatState = 1;
	}
}

function startFrameset() {
}

function disableRootDirButs() {
	WE().layout.button.switch_button_state(document, "root_dir", "disabled");
	WE().layout.button.switch_button_state(document, "btn_fs_back", "disabled");
	rootDirButsState = false;
}
function enableRootDirButs() {
	WE().layout.button.switch_button_state(document, "root_dir", "enabled");
	WE().layout.button.switch_button_state(document, "btn_fs_back", "enabled");
	rootDirButsState = true;
}
function disableNewFolderBut() {
	WE().layout.button.switch_button_state(document, "btn_new_dir", "disabled");
	makefolderState = false;
}
function enableNewFolderBut() {
	WE().layout.button.switch_button_state(document, "btn_new_dir", "enabled");
	makefolderState = true;
}
function disableNewBut() {
	WE().layout.button.switch_button_state(document, "btn_new_dir", "disabled");
	WE().layout.button.switch_button_state(document, "btn_add_cat", "disabled");
}

function enableNewBut() {
	if (top.options.userCanEditCat) {
		WE().layout.button.switch_button_state(document, "btn_new_dir", "enabled");
		WE().layout.button.switch_button_state(document, "btn_add_cat", "enabled");
	}
}

function disableNewFileBut() {
	WE().layout.button.switch_button_state(document, "btn_add_file", "disabled");
	newFileState = false;
}

function enableNewFileBut() {
	WE().layout.button.switch_button_state(document, "btn_add_file", "enabled");
	newFileState = true;
}

function clearOptions() {
	var a = top.document.getElementById("lookin");
	while (a.options.length) {
		a.options.remove(0);
	}
}
function addOption(txt, id) {
	var a = top.document.getElementById("lookin");
	a.options[a.options.length] = new Option(txt, id);
	a.selectedIndex = (a.options.length > 0 ?
					a.options.length - 1 :
					0);

}
function selectIt() {
	var a = top.document.getElementById("lookin");
	a.selectedIndex = a.options.length - 1;
}

function setview(view) {
	top.options.view = view;
	var zoom = top.document.getElementsByName("zoom")[0];
	switch (view) {
		case 'list':
			zoom.value = 100;
			if (zoom.onchange) {
				zoom.onchange();
			}
			zoom.disabled = true;
			zoom.style.display = "none";
			break;
		case 'icons':
			zoom.disabled = false;
			zoom.style.display = "inline";
			break;
	}
	top.document.getElementById('list').style.display = (view == 'list' ? "none" : "table-cell");
	top.document.getElementById('icons').style.display = (view == 'icons' ? "none" : "table-cell");

	top.writeBody(top.fsbody.document.body);
}

function we_cmd() {
	//var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
//	var url = WE().util.getWe_cmdArgsUrl(args);

	opener.we_cmd.apply(this, Array.prototype.slice.call(arguments));
}

function selectorOnClick(event, id) {
	weonclick(event);
	tout = setTimeout(function () {
		if (!top.wasdblclick) {
			top.doClick(id, 0);
		} else {
			top.wasdblclick = false;
		}
	}, 300);
	return true;
}

function selectorOnDblClick(id) {
	top.wasdblclick = true;
	clearTimeout(tout);
	top.doClick(id, 1);
	return true;
}