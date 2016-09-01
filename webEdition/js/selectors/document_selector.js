/* global WE, top, fileSelect */

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

function doClick(id, ct) {
	if (top.fspreview && top.fspreview.document.body) {
		top.fspreview.document.body.innerHTML = "";
	}
	if (ct == 1) {
		if (wasdblclick) {
			setDir(id);
			setTimeout(function () {
				wasdblclick = false;
			}, 400);
		}
	} else if (getEntry(id).contentType != "folder" || (fileSelect.options.canSelectDir)) {
		if (fileSelect.options.multiple) {
			if (top.shiftpressed) {
				var oldid = fileSelect.data.currentID;
				var currendPos = getPositionByID(id);
				var firstSelected = getFirstSelected();

				if (currendPos > firstSelected) {
					selectFilesFrom(firstSelected, currendPos);
				} else if (currendPos < firstSelected) {
					selectFilesFrom(currendPos, firstSelected);
				} else {
					selectFile(id);
				}
				fileSelect.data.currentID = oldid;

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
	} else {
		showPreview(id);

	}
	if (top.ctrlpressed) {
		top.ctrlpressed = 0;
	}
	if (top.shiftpressed) {
		top.shiftpressed = 0;
	}
}

function previewFolder(id) {
	alert(id);
}

function setDir(id) {
	showPreview(id);
	if (top.fspreview && top.fspreview.document.body) {
		top.fspreview.document.body.innerHTML = "";
	}
	top.fscmd.location.replace(top.queryString(WE().consts.selectors.SETDIR, id));
	e = getEntry(id);
	top.document.getElementById('fspath').innerHTML = e.path;
}

function selectFile(id) {
	fname = top.document.getElementsByName("fname");
	if (id) {
		e = getEntry(id);
		top.document.getElementById('fspath').innerHTML = e.path;
		if (fname && fname[0].value != e.text &&
						fname[0].value.indexOf(e.text + ",") == -1 &&
						fname[0].value.indexOf("," + e.text + ",") == -1 &&
						fname[0].value.indexOf("," + e.text + ",") == -1) {
			fname[0].value = fname[0].value ?
							(fname[0].value + "," + e.text) :
							e.text;
		}

		if (top.fsbody.document.getElementById("line_" + id)) {
			top.fsbody.document.getElementById("line_" + id).classList.add("selected");
		}
		top.currentPath = e.path;
		fileSelect.data.currentID = id;
		fileSelect.data.we_editDirID = 0;
		fileSelect.data.currentType = e.contentType;
		showPreview(id);
	} else {
		fname[0].value = "";
		top.currentPath = "";
		fileSelect.data.we_editDirID = 0;
	}
}

function addEntry(ID, text, extension, isFolder, path, modDate, contentType, published, title) {
	entries.push({
		"ID": ID,
		"text": text,
		"extension": extension,
		"isFolder": isFolder,
		"path": path,
		"modDate": modDate,
		"contentType": contentType,
		"published": published,
		"title": title,
	});
}

function setFilter(ct) {
	top.fscmd.location.replace(top.queryString(WE().consts.selectors.CMD, fileSelect.data.currentDir, "", "", ct));
}

function showPreview(id) {
	if (top.fspreview) {
		top.fspreview.location.replace(top.queryString(WE().consts.selectors.PREVIEW, id));
	}
}

function reloadDir() {
	top.fscmd.location.replace(top.queryString(WE().consts.selectors.CMD, fileSelect.data.currentDir));
}

function newFile() {
	var url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=we_fileupload_editor&we_cmd[1]=" + fileSelect.data.currentType + "&we_cmd[3]=selector&we_cmd[6]=" + fileSelect.data.currentDir + "&we_cmd[7]=1&we_cmd[8]=selector";
	new (WE().util.jsWindow)(window, url, "we_fileupload_editor", -1, -1, 500, 550, true, true, true, true);
}

function newCollection() {
	url = "we_cmd.php?we_cmd[0]=editNewCollection&fixedpid=" + fileSelect.data.currentDir + "&caller=selector";
	new (WE().util.jsWindow)(window, url, "we_newICollection", -1, -1, 460, 560, true, false, true);
}

function writeBodyDocument(d) {
	var body = (fileSelect.data.we_editDirID ?
					'<input type="hidden" name="what" value="' + WE().consts.selectors.DORENAMEFOLDER + '" />' +
					'<input type="hidden" name="we_editDirID" value="' + fileSelect.data.we_editDirID + '" />' :
					'<input type="hidden" name="what" value="' + WE().consts.selectors.CREATEFOLDER + '" />'
					) +
					'<input type="hidden" name="order" value="' + top.order + '" />' +
					'<input type="hidden" name="rootDirID" value="' + fileSelect.options.rootDirID + '" />' +
					'<input type="hidden" name="table" value="' + fileSelect.options.table + '" />' +
					'<input type="hidden" name="id" value="' + fileSelect.data.currentDir + '" />' +
					'<table class="selector">' +
					(fileSelect.data.makeNewFolder ?
									'<tr class="newEntry">' +
									'<td class="treeIcon selectoricon">' + WE().util.getTreeIcon('folder', false) + '</td>' +
									'<td class="filename"><input type="hidden" name="we_FolderText" value="' + WE().consts.g_l.fileselector.new_folder_name + '" /><input onMouseDown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="' + WE().consts.g_l.fileselector.new_folder_name + '" class="wetextinput" /></td>' +
									'<td class="selector title">' + WE().consts.g_l.fileselector.folder + '</td>' +
									'<td class="selector moddate">' + WE().consts.g_l.fileselector.date_format + '</td>' +
									'</tr>' :
									'');
	for (i = 0; i < entries.length; i++) {
		var onclick = ' onclick="return selectorOnClick(event,' + entries[i].ID + ');"';
		var ondblclick = ' onDblClick="return selectorOnDblClick(' + entries[i].ID + ');"';
		body += '<tr id="line_' + entries[i].ID + '" class="' + ((entries[i].ID == fileSelect.data.currentID) ? 'selected' : "") + '" ' + ((fileSelect.data.we_editDirID || fileSelect.data.makeNewFolder) ? "" : onclick) + (entries[i].isFolder ? ondblclick : "") + '>' +
						'<td class="selector treeIcon selectoricon">' + WE().util.getTreeIcon(entries[i].contentType, false) + '</td>' +
						'<td class="selector filename"' + (entries[i].published === 0 && entries[i].isFolder === 0 ? ' style="color: red;"' : "") + ' title="' + entries[i].text + '">' +
						(fileSelect.data.we_editDirID == entries[i].ID ?
										'<input type="hidden" name="we_FolderText" value="' + entries[i].text + '" /><input onMouseDown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="' + entries[i].text + '" class="wetextinput" style="width:100%" />' :
										'<div class="cutText">' + entries[i].text + '</div><div class="extension">' + entries[i].extension + '</div>'
										) +
						'</td>' +
						'<td class="selector title" title="' + (fileSelect.options.useID ? entries[i].ID : entries[i].title) + '"><div class="cutText">' + (fileSelect.options.useID ? entries[i].ID : entries[i].title) + '</div></td>' +
						'<td class="selector moddate">' + entries[i].modDate + '</td>' +
						'</tr>';
	}
	d.innerHTML = '<form name="we_form" target="fscmd" method="post" action="' + fileSelect.options.formtarget + '" onsubmit="document.we_form.we_FolderText.value=escape(document.we_form.we_FolderText_tmp.value);return true;">' + body + '</table></form>';
	if (fileSelect.data.makeNewFolder || fileSelect.data.we_editDirID) {
		top.fsbody.document.we_form.we_FolderText_tmp.focus();
		top.fsbody.document.we_form.we_FolderText_tmp.select();
	}
}

function writeBody(d) {
	writeBodyDocument(d);
}

function queryString(what, id, o, we_editDirID, filter) {
	if (!o) {
		o = top.order;
	}
	if (!filter) {
		filter = fileSelect.data.currentType;
	}
	return fileSelect.options.formtarget + 'what=' + what + '&rootDirID=' + fileSelect.options.rootDirID + '&open_doc=' + fileSelect.options.open_doc + '&table=' + fileSelect.options.table + '&id=' + id + (o ? ("&order=" + o) : "") + (we_editDirID ? ("&we_editDirID=" + we_editDirID) : "") + (filter ? ("&filter=" + filter) : "");
}

function weonclick(e) {
	if (fileSelect.data.makeNewFolder || fileSelect.data.we_editDirID) {
		if (!inputklick) {
			fileSelect.data.makeNewFolder = fileSelect.data.we_editDirID = false;
			document.we_form.we_FolderText.value = escape(document.we_form.we_FolderText_tmp.value);
			document.we_form.submit();
		} else {
			inputklick = false;
		}
	} else {
		inputklick = false;
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
		if (fileSelect.options.multiple) {
			if (!self.shiftpressed && !self.ctrlpressed) {
				top.unselectAllFiles();
			}
		} else {
			top.unselectAllFiles();
		}
	}
}