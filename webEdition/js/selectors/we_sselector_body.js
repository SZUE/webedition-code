/* global fileSelect, top */

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
var clickCount = 0,
	mk = null;
top.metaKeys = {
	ctrl: false,
	shift: false,
	doubleClick: false,
	inputClick: false,
	doubleTout: null
};
var selector = WE().util.getDynamicVar(document, 'loadVarSelectors', 'data-selector');

top.allentries=selector.allentries;

function doClick(id, ct, indb) {
	if (ct === 1) {
		if (top.metaKeys.doubleClick) {
			top.fscmd.selectDir(id);
			if (top.fileSelect.data.filter !== WE().consts.contentTypes.FOLDER && top.fileSelect.data.filter !== "filefolder") {
				top.fscmd.selectFile("");
			}
			window.setTimeout(function () {
				top.metaKeys.doubleClick = false;
			}, 400);
		} else {
			if ((top.fileSelect.data.filter === WE().consts.contentTypes.FOLDER || top.fileSelect.data.filter === "filefolder") && (!indb)) {
				top.fscmd.selectFile(id);
			}
		}
		if ((top.fileSelect.click.oldID === id) && (!top.metaKeys.doubleClick)) {
			clickEdit(id);
		}
	} else {
		top.fscmd.selectFile(id);
		top.dirsel = 0;
	}
	top.top.fileSelect.click.oldID = id;
}

function doSelectFolder(entry, indb) {
	switch (top.fileSelect.data.filter) {
		case "all_Types":
			if (!top.fileSelect.data.browseServer) {
				break;
			}
			/* falls through */
		case "folder":
		case "filefolder":
			if (!indb) {
				top.fscmd.selectFile(entry);
			}
			top.dirsel = 1;
	}
}

function clickEdit(dir) {
	switch (top.fileSelect.data.filter) {
		case "folder":
		case "filefolder":
			break;
		default:
			setScrollTo();
			top.fscmd.drawDir(top.fileSelect.data.currentDir, "rename_folder", dir);
	}
}

function clickEditFile(file) {
	setScrollTo();
	top.fscmd.drawDir(top.fileSelect.data.currentDir, "rename_file", file);
}

function doScrollTo() {
	if (window.parent.scrollToVal) {
		window.scrollTo(0, window.parent.scrollToVal);
		window.parent.scrollToVal = 0;
	}
}

function keypressed(e) {
	if (e.keyCode === 13) { // RETURN KEY => valid for all Browsers
		window.setTimeout(document.we_form.txt.blur, 30);
	}
}

function setScrollTo() {
	window.parent.scrollToVal = window.pageYOffset;
}

function initSelector(type) {
	document.we_form.elements.txt.focus();
	document.we_form.elements.txt.select();
	if (type === "rename_folder" || type === "rename_file") {
		document.we_form.elements.oldtxt.value = document.we_form.elements.txt.value;
	}
	document.we_form.elements.pat.value = top.fileSelect.data.currentDir;
}