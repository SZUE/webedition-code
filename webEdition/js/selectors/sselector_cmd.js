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
var filter = '';
var selectInternal = 0;

function drawNewFolder() {
	for (var i = 0; i < top.allentries.length; i++) {
		if ((elem = top.fsbody.document.getElementById(top.allentries[i]))) {
			elem.style.backgroundColor = 'white';
		}
	}
	drawDir(top.currentDir, "new_folder");
}

function setFilter(filter) {
	top.currentFilter = filter;
	drawDir(top.currentDir);
}


function selectFile(fid) {
	var i;
	if (fid !== "/") {
		top.currentID = top.sitepath + top.rootDir + top.currentDir + ((top.currentDir !== "/") ? "/" : "") + fid;
		top.currentName = fid;
		top.document.getElementsByName("fname")[0].value = fid;
		if (top.fsbody.document.getElementById(fid)) {
			for (i = 0; i < top.allentries.length; i++) {
				if (top.fsbody.document.getElementById(top.allentries[i]))
					top.fsbody.document.getElementById(top.allentries[i]).style.backgroundColor = 'white';
			}
			top.fsbody.document.getElementById(fid).style.backgroundColor = '#DFE9F5';
		}
	} else {
		top.currentID = top.sitepath;
		top.currentName = fid;
		top.document.getElementsByName("fname")[0].value = fid;
		if (top.fsbody.document.getElementById(fid)) {
			for (i = 0; i < top.allentries.length; i++) {
				if (top.fsbody.document.getElementById(top.allentries[i]))
					top.fsbody.document.getElementById(top.allentries[i]).style.backgroundColor = 'white';
			}
			top.fsbody.document.getElementById(fid).style.backgroundColor = '#DFE9F5';
		}
	}
}


function reorderDir(dir, order) {
	setTimeout(function (url) {
		top.fsbody.location = url;
	}, 100, WE().consts.dirs.WEBEDITION_DIR + 'we_cmd.php?we_cmd[0]=selectorBrowse&dir=' + dir + '&ord=' + order + '&file=' + top.currentFilter + '&curID=' + encodeURI(top.currentID));
}

function selectDir(path) {
	if (path) {
		top.currentDir = top.currentDir + (top.currentDir === "/" ? "" : "/") + path;
		top.addOptionh(path, top.currentDir);
	}

	if (top.currentDir.substring(0, 12) === "/webEdition/" || top.currentDir === "/webEdition") {
		WE().layout.button.disable(document, "btn_new_dir_ss");
		WE().layout.button.disable(document, "btn_add_file_ss");
		WE().layout.button.disable(document, "btn_function_trash_ss");
	} else {
		WE().layout.button.enable(document, "btn_new_dir_ss");
		WE().layout.button.enable(document, "btn_add_file_ss");
		WE().layout.button.enable(document, "btn_function_trash_ss");
	}

	drawDir(top.currentDir);

}

function goUp() {
	var a = top.document.getElementById("lookin").options;
	if (a.length - 2 > -1) {
		setDir(a[a.length - 2].value);
	} else {
		top.we_showMessage(WE().consts.g_l.sfselector.already_root, WE().consts.message.WE_MESSAGE_ERROR, window);
	}
}

function delFile(ask) {
	if ((top.currentID !== "") && (top.document.getElementsByName("fname")[0].value !== "")) {
		top.fscmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=selectorBrowseCmd&cmd=delete_file&fid=" + top.currentID + "&ask=" + ask;
	} else {
		top.we_showMessage(WE().consts.g_l.sfselector.edit_file_nok, WE().consts.message.WE_MESSAGE_ERROR, window);
	}
}

function setDir(dir) {
	var a = top.document.getElementById("lookin").options;
	if (a.length - 2 > -1) {
		for (j = 0; j < a.length; j++) {
			if (a[j].value === dir) {
				a.length = j + 1;
				a[j].selected = true;
			}
		}
		switch (filter) {
			case 'folder':
			case 'filefolder':
				selectFile(dir);
		}
		top.currentDir = dir;
		selectDir();
	} else {
		top.we_showMessage(WE().consts.g_l.sfselector.already_root, WE().consts.message.WE_MESSAGE_ERROR, window);
	}
}

function drawDir(dir, what, sid) {
	switch (what) {
		case "new_folder":
			top.fsbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=selectorBrowse&dir=" + encodeURI(top.rootDir + dir) + "&nf=new_folder&file=" + top.currentFilter + "&curID=" + encodeURI(top.currentID) + "&selectInternal=" + selectInternal;
			break;
		case "rename_folder":
			if (sid) {
				top.fsbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=selectorBrowse&dir=" + encodeURI(top.rootDir + dir) + "&nf=rename_folder&sid=" + encodeURI(sid) + "&file=" + top.currentFilter + "&curID=" + encodeURI(top.currentID) + "&selectInternal=" + selectInternal;
			}
			break;
		case "rename_file":
			if (sid) {
				top.fsbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=selectorBrowse&dir=" + encodeURI(top.rootDir + dir) + "&nf=rename_file&sid=" + encodeURI(sid) + "&file=" + top.currentFilter + "&curID=" + encodeURI(top.currentID) + "&selectInternal=" + selectInternal;
			}
			break;
		default:
			setTimeout(function (url) {
				top.fsbody.location = url;
			}, 100, WE().consts.dirs.WEBEDITION_DIR + 'we_cmd.php?we_cmd[0]=selectorBrowse&dir=' + encodeURI(top.rootDir + dir) + '&file=' + top.currentFilter + '&curID=' + encodeURI(top.currentID) + '&selectInternal=' + selectInternal);
	}
}