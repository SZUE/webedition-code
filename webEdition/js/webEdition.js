/* global WE, top */

/**
 * webEdition CMS
 *
 * webEdition CMS
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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

var regular_logout = false;
var widthBeforeDeleteMode = 0;
var widthBeforeDeleteModeSidebar = 0;
var we_mediaReferences = {};

function doClickDirect(id, ct, table, fenster) {
	if (!fenster) {
		fenster = window;
	}
	//  the actual position is the top-window, maybe the first window was closed
	//if (!fenster.top.opener || fenster.top.opener.isLoginScreen || fenster.top.opener.closed) {
		WE().layout.weEditorFrameController.openDocument(table, id, ct);

	/*} else {
		//  If a include-file is edited and another link is chosen, it will appear on the main window. And the pop-up will be closed.
		top.we_showMessage(WE().consts.g_l.main.open_link_in_SEEM_edit_include, WE().consts.message.WE_MESSAGE_WARNING, window);
		if (top.opener.top.doClickDirect) {
			top.opener.top.doClickDirect(id, ct, table, top.opener);
		}
		// clean session
		// get the EditorFrame - this is important due to edit_include_mode!
		var _ActiveEditor = WE().layout.weEditorFrameController.getActiveEditorFrame();
		if (_ActiveEditor) {
			trans = _ActiveEditor.getEditorTransaction();
			if (trans) {
				top.we_cmd('users_unlock', _ActiveEditor.getEditorDocumentId(), WE().session.userID, _ActiveEditor.getEditorEditorTable(), trans);
			}
		}
		top.close();
	}*/
}

function doClickWithParameters(id, ct, table, parameters) {
	WE().layout.weEditorFrameController.openDocument(table, id, ct, '', '', '', '', '', parameters);
}

function doExtClick(url) {
	// split url in url and parameters !
	var parameters = "";
	if ((_position = url.indexOf("?")) !== -1) {
		parameters = url.substring(_position);
		url = url.substring(0, _position);
	}

	WE().layout.weEditorFrameController.openDocument('', '', '', '', '', url, '', '', parameters);
}

WE().util.weSetCookie = function (doc, name, value, expires, path, domain) {
	doc.cookie = name + "=" + encodeURI(value) +
					((expires === undefined) ? "" : "; expires=" + expires.toGMTString()) +
					((path === undefined) ? "" : "; path=" + path) +
					((domain === undefined) ? "" : "; domain=" + domain);
};

WE().util.weGetCookie = function (doc, name) {
	var cname = name + "=";
	var dc = doc.cookie;
	if (dc.length > 0) {
		begin = dc.indexOf(cname);
		if (begin !== -1) {
			begin += cname.length;
			end = dc.indexOf(";", begin);
			if (end === -1) {
				end = dc.length;
			}
			return unescape(dc.substring(begin, end));
		}
	}
	return null;
};

WE().util.hashCode = function (s) {
	return s.split("").reduce(function (a, b) {
		a = ((a << 5) - a) + b.charCodeAt(0);
		return a & a;
	}, 0);
};

WE().t_e = function () {
	var msg = '';
	for (var i = 0; i < arguments.length; i++) {
		msg += JSON.stringify(arguments[i]) + (i < (arguments.length - 1) ? "\n" : "");
	}
	WE().handler.errorHandler(msg);
};

function treeResized() {
	var treeWidth = getTreeWidth();
	if (treeWidth <= WE().consts.size.tree.hidden) {
		//setTreeArrow("right");
	} else {
		//setTreeArrow("left");
		storeTreeWidth(treeWidth);
	}
}

var oldTreeWidth = WE().consts.size.tree.defaultWidth;
function toggleTree(setVisible) {
	var tfd = self.document.getElementById("treeFrameDiv");
	var w = top.getTreeWidth();

	if (setVisible || (tfd.style.display === "none" && setVisible !== false)) {
		oldTreeWidth = (oldTreeWidth < WE().consts.size.tree.min ? WE().consts.size.tree.defaultWidth : oldTreeWidth);
		setTreeWidth(oldTreeWidth);
		tfd.style.display = "block";
		//setTreeArrow("left");
		storeTreeWidth(oldTreeWidth);
		return true;
	}
	tfd.style.display = "none";
	oldTreeWidth = w;
	setTreeWidth(WE().consts.size.tree.hidden);
	//setTreeArrow("right");
	return false;
}

function treeOut() {
	if (getTreeWidth() <= WE().consts.size.tree.min) {
		toggleTree();
	}
}

function getTreeWidth() {
	var w = self.document.getElementById("bframeDiv").style.width;
	return w.substr(0, w.length - 2);
}

function incTree() {
	var w = parseInt(getTreeWidth());
	if ((w >= WE().consts.size.tree.min) && (w < WE().consts.size.tree.max)) {
		w += WE().consts.size.tree.step;
		setTreeWidth(w);
	}
	if (w >= WE().consts.size.tree.max) {
		w = WE().consts.size.tree.max;
		setTreeWidth(w);
		self.document.getElementById("incBaum").style.backgroundColor = "grey";
	} else
	if (w < WE().consts.size.tree.min) {
		toggleTree(true);
	}
}

function decTree() {
	var w = parseInt(getTreeWidth());
	w -= WE().consts.size.tree.step;
	if (w > WE().consts.size.tree.min) {
		setTreeWidth(w);
		self.document.getElementById("incBaum").style.backgroundColor = "";
	}
	if (w <= WE().consts.size.tree.min && ((w + WE().consts.size.tree.step) >= WE().consts.size.tree.min)) {
		toggleTree();
	}
}

function getSidebarWidth() {
	var obj = self.document.getElementById("sidebarDiv");
	if (obj === undefined || obj === null) {
		return 0;
	}
	var w = obj.style.left;
	return w.substr(0, w.length - 2);
}

function setSidebarWidth() {
	var obj = self.document.getElementById("sidebarDiv");
	if (obj !== undefined && obj !== null) {
		obj.style.left = top.w + "px";
	}
}

function setTreeWidth(w) {
	self.document.getElementById("bframeDiv").style.width = w + "px";
	self.document.getElementById("bm_content_frameDiv").style.left = w + "px";
	if (w > WE().consts.size.tree.hidden) {
		storeTreeWidth(w);
	}
}

function storeTreeWidth(w) {
	var ablauf = new Date();
	var newTime = ablauf.getTime() + 30758400000;
	ablauf.setTime(newTime);
	WE().util.weSetCookie(self.document, "treewidth_main", w, ablauf, "/");
}

function clickVTab(tab, table) {
	if (top.deleteMode) {
		we_cmd('exit_delete', table);
	}
	if (tab.classList.contains("tabActive")) {
		if (toggleTree()) {
			we_cmd('loadVTab', table, 0);
		}
	} else {
		setActiveVTab(table);
		treeOut();
		we_cmd('loadVTab', table, 0);
	}
}

function setActiveVTab(table) {
	var allTabs = document.getElementById("vtabs").getElementsByClassName("tab");
	for (var i = 0; i < allTabs.length; i++) {
		allTabs[i].className = "tab " + (allTabs[i].getAttribute("data-table") === table ? "tabActive" : "tabNorm");
	}
}

function we_repl(target, url) {
	if (target) {
		try {
			// use 2 loadframes to avoid missing cmds
			if (target.name === "load" || target.name === "load2") {
				if (top.lastUsedLoadFrame === target.name) {
					target = (target.name === "load" ?
									self.load2 :
									self.load);
				}
				top.lastUsedLoadFrame = target.name;
			}
		} catch (e) {
			// Nothing
		}
		if (target.location === undefined) {
			target.src = url;
		} else {
			target.location.replace(url);
		}
	}
}

function we_sbmtFrm(target, url, source) {
	if (source === undefined) {
		source = WE().layout.weEditorFrameController.getVisibleEditorFrame();
	}

	if (source) {
		if (source.we_submitForm !== undefined && source.we_submitForm) {
			return source.we_submitForm(target.name, url);
		}
		if (source.contentWindow && source.contentWindow.we_submitForm) {
			return source.contentWindow.we_submitForm(target.name, url);
		}
	}
	return false;
}

function doSave(url, trans, cmd) {
	_EditorFrame = WE().layout.weEditorFrameController.getEditorFrameByTransaction(trans);
	// _EditorFrame.setEditorIsHot(false);
	if (_EditorFrame.getEditorAutoRebuild()) {
		url += "&we_cmd[8]=1";
	}
	if (!we_sbmtFrm(self.load, url)) {
		url += "&we_transaction=" + trans;
		we_repl(self.load, url, cmd);
	}
}

function doPublish(url, trans, cmd) {
	if (!we_sbmtFrm(self.load, url)) {
		url += "&we_transaction=" + trans;
		we_repl(self.load, url, cmd);
	}
}

function openWindow(url, ref, x, y, w, h, scrollbars, menues) {
	new (WE().util.jsWindow)(window, url, ref, x, y, w, h, true, scrollbars, menues);
}

function openBrowser(url) {
	if (!url) {
		url = "/";
	}
	try {
		browserwind = window.open(WE().consts.dirs.WEBEDITION_DIR + "openBrowser.php?url=" + encodeURI(url), "browser", "menubar=yes,resizable=yes,scrollbars=yes,location=yes,status=yes,toolbar=yes");
	} catch (e) {
		top.we_showMessage(WE().consts.g_l.alert.browser_crashed, WE().consts.message.WE_MESSAGE_ERROR, window);
	}
}

function start(table_to_load) {
	if (table_to_load) {
		we_cmd("load", table_to_load);
	}
}

function hasPermDelete(eTable, isFolder) {
	if (eTable === "") {
		return false;
	}
	if (WE().session.permissions.ADMINISTRATOR) {
		return true;
	}
	switch (eTable) {
		case WE().consts.tables.FILE_TABLE:
			return (isFolder ? WE().session.permissions.DELETE_DOC_FOLDER : WE().session.permissions.DELETE_DOCUMENT);
		case WE().consts.tables.TEMPLATES_TABLE:
			return (isFolder ? WE().session.permissions.DELETE_TEMP_FOLDER : WE().session.permissions.DELETE_TEMPLATE);
		case WE().consts.tables.OBJECT_FILES_TABLE:
			return (isFolder ? WE().session.permissions.DELETE_OBJECTFILE : WE().session.permissions.DELETE_OBJECTFILE);
		case WE().consts.tables.OBJECT_TABLE:
			return (isFolder ? false : WE().session.permissions.DELETE_OBJECT);
		default:
			return false;
	}
}

function doUnloadSEEM(whichWindow) {
	// unlock all open documents
	var _usedEditors = WE().layout.weEditorFrameController.getEditorsInUse();

	var docIds = "";
	var docTables = "";

	for (var frameId in _usedEditors) {
		if (_usedEditors[frameId].EditorType != "cockpit") {
			docIds += _usedEditors[frameId].getEditorDocumentId() + ",";
			docTables += _usedEditors[frameId].getEditorEditorTable() + ",";
		}
	}

	if (docIds) {
		top.we_cmd('users_unlock', docIds, WE().session.userID, docTables);
		if (top.opener) {
			top.opener.focus();

		}
	}
	//  close the SEEM-edit-include when exists
	if (top.edit_include) {
		top.edit_include.close();
	}
	try {
		WE().util.jsWindow.prototype.closeAll();
		if (browserwind) {
			browserwind.close();
		}
	} catch (e) {

	}

	//  only when no SEEM-edit-include window is closed

	if (whichWindow !== "include") {
		if (opener) {
			opener.location.replace(WE().consts.dirs.WEBEDITION_DIR + 'we_loggingOut.php');
		}
	}
}

function doUnloadNormal(whichWindow) {
	var tinyDialog;
	if (!regular_logout) {

		if (window.tinyMceDialog !== undefined && window.tinyMceDialog !== null) {
			tinyDialog = tinyMceDialog;
			try {
				tinyDialog.close();
			} catch (err) {
			}
		}

		if (tinyMceSecondaryDialog !== undefined && tinyMceSecondaryDialog !== null) {
			tinyDialog = tinyMceSecondaryDialog;
			try {
				tinyDialog.close();
			} catch (err) {
			}
		}

		try {
			WE().util.jsWindow.prototype.closeAll();
			if (browserwind) {
				browserwind.close();
			}
		} catch (e) {
		}
		if (whichWindow !== "include") { 	// only when no SEEM-edit-include window is closed
			// FIXME: closing-actions for SEEM
			var logoutpopup;
			if (top.opener) {
				if (specialUnload) {
					top.opener.location.replace(WE().consts.dirs.WEBEDITION_DIR + 'we_loggingOut.php?isopener=1');
					top.opener.focus();
				} else {
					top.opener.history.back();
					logoutpopup = window.open(WE().consts.dirs.WEBEDITION_DIR + 'we_loggingOut.php?isopener=0', "webEdition", "width=350,height=70,toolbar=no,menubar=no,directories=no,location=no,resizable=no,status=no,scrollbars=no,top=300,left=500");
					if (logoutpopup) {
						logoutpopup.focus();
					}
				}
			} else {
				logoutpopup = window.open(WE().consts.dirs.WEBEDITION_DIR + 'we_loggingOut.php?isopener=0', "webEdition", "width=350,height=70,toolbar=no,menubar=no,directories=no,location=no,resizable=no,status=no,scrollbars=no,top=300,left=500");
				if (logoutpopup) {
					logoutpopup.focus();
				}
			}
		}
	}

}

function doUnload(whichWindow) { // triggered when webEdition-window is closed
	if (!WE().layout.weEditorFrameController.closeAllDocuments()) {
		return WE().consts.g_l.alert.exit_multi_doc_question;
	}
	if (WE().session.seemode) {
		doUnloadSEEM(whichWindow);
	} else {
		doUnloadNormal(whichWindow);
	}
}

function we_openMediaReference(id) {
	id = id ? id : 0;

	if (window.we_mediaReferences && window.we_mediaReferences['id_' + id]) {
		var ref = window.we_mediaReferences['id_' + id];
		switch (ref.type) {
			case 'module':
				top.we_cmd(ref.mod + '_edit_ifthere', ref.id);
				break;
			case 'cat':
				top.we_cmd('editCat', ref.id);
				break;
			default:
				if (ref.isTempPossible && ref.referencedIn === 'main' && ref.isModified) {
					top.we_showMessage('Der Link wurde bei einer unveröffentlichten Änderung entfernt: Er existiert nur noch in der veröffentlichten Version!', WE().consts.message.WE_MESSAGE_ERROR, window);
				} else {
					WE().layout.weEditorFrameController.openDocument(ref.table, ref.id, ref.ct);
				}
		}
	}
}

function we_showInNewTab(args, url) {
	var ctrl = WE().layout.weEditorFrameController;
	var nextWindow;
	if ((nextWindow = ctrl.getFreeWindow())) {
		we_repl(nextWindow.getDocumentReference(), url, args[0]);
		// activate tab
		var pos = (args[0] === "open_cockpit" ? 0 : undefined);
		WE().layout.multiTabs.addTab(nextWindow.getFrameId(), ' &hellip; ', ' &hellip; ', pos);
		// set Window Active and show it
		ctrl.setActiveEditorFrame(nextWindow.FrameId);
		ctrl.toggleFrames();
	} else {
		WE().util.showMessage(WE().consts.g_l.main.no_editor_left, WE().consts.message.WE_MESSAGE_INFO, window);
	}
}

function we_cmd_base(args, url) {
	switch (args[0]) {
		case "loadVTab":
			var op = top.treeData.makeFoldersOpenString();
			parent.we_cmd("load", args[1], 0, op, top.treeData.table);
			break;
		case "exit_modules":
			WE().util.jsWindow.prototype.closeByName('edit_module');
			break;
		case "openUnpublishedObjects":
			we_cmd("tool_weSearch_edit", "", "", 7, 3);
			break;
		case "openUnpublishedPages":
			we_cmd("tool_weSearch_edit", "", "", 4, 3);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(this, url, "we_cateditor", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "openSidebar":
			WE().layout.sidebar.open("default");
			break;
		case "loadSidebarDocument":
			top.weSidebarContent.location.href = url;
			break;
		case "versions_preview":
			new (WE().util.jsWindow)(this, url, "version_preview", -1, -1, 1000, 750, true, false, true, false);
			break;
		case "versions_wizard":
			new (WE().util.jsWindow)(this, url, "versions_wizard", -1, -1, 600, 620, true, false, true);
			break;
		case "versioning_log":
			new (WE().util.jsWindow)(this, url, "versioning_log", -1, -1, 600, 500, true, false, true);
			break;

		case "delete_single_document_question":
			var ctrl = WE().layout.weEditorFrameController;
			var cType = ctrl.getActiveEditorFrame().getEditorContentType();
			var eTable = ctrl.getActiveEditorFrame().getEditorEditorTable();
			var path = ctrl.getActiveEditorFrame().getEditorDocumentPath();

			if (ctrl.getActiveDocumentReference()) {
				if (!hasPermDelete(eTable, (cType === "folder"))) {
					top.we_showMessage(WE().consts.g_l.main.no_perms_action, WE().consts.message.WE_MESSAGE_ERROR, this);
				} else if (this.confirm(WE().consts.g_l.main.delete_single_confirm_delete + path)) {
					url2 = url.replace(/we_cmd\[0\]=delete_single_document_question/g, "we_cmd[0]=delete_single_document");
					we_sbmtFrm(self.load, url2 + "&we_cmd[2]=" + ctrl.getActiveEditorFrame().getEditorEditorTable(), ctrl.getActiveDocumentReference().frames.editFooter);
				}
			} else {
				top.we_showMessage(WE().consts.g_l.main.no_document_opened, WE().consts.message.WE_MESSAGE_ERROR, this);
			}
			break;
		case "delete_single_document":
			var ctrl = WE().layout.weEditorFrameController;
			var cType = ctrl.getActiveEditorFrame().getEditorContentType();
			var eTable = ctrl.getActiveEditorFrame().getEditorEditorTable();

			if (ctrl.getActiveDocumentReference()) {
				if (!hasPermDelete(eTable, (cType === "folder"))) {
					top.we_showMessage(WE().consts.g_l.main.no_perms_action, WE().consts.message.WE_MESSAGE_ERROR, this);
				} else {
					we_sbmtFrm(self.load, url + "&we_cmd[2]=" + ctrl.getActiveEditorFrame().getEditorEditorTable(), ctrl.getActiveDocumentReference().editFooter);
				}
			} else {
				top.we_showMessage(WE().consts.g_l.main.no_document_opened, WE().consts.message.WE_MESSAGE_ERROR, this);
			}
			break;
		case "do_delete":
			we_sbmtFrm(self.load, url, document.getElementsByName("treeheader")[0]);
			break;
		case "move_single_document":
			we_sbmtFrm(self.load, url, WE().layout.weEditorFrameController.getActiveDocumentReference().editFooter);
			break;
		case "do_move":
			we_sbmtFrm(self.load, url, document.getElementsByName("treeheader")[0]);
			break;
		case "do_addToCollection":
			we_sbmtFrm(self.load, url, document.getElementsByName("treeheader")[0]);
			break;
		case "change_passwd":
			new (WE().util.jsWindow)(this, url, "we_change_passwd", -1, -1, 300, 400, true, false, true, false);
			break;
		case "update":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "liveUpdate/liveUpdate.php?active=update", "we_update_" + WE().session.sess_id, -1, -1, 600, 500, true, true, true);
			break;
		case "upgrade":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "liveUpdate/liveUpdate.php?active=upgrade", "we_update_" + WE().session.sess_id, -1, -1, 600, 500, true, true, true);
			break;
		case "languageinstallation":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "liveUpdate/liveUpdate.php?active=languages", "we_update_" + WE().session.sess_id, -1, -1, 600, 500, true, true, true);
			break;
		case "del":
			we_cmd('delete', 1, args[2]);
			treeData.setState(treeData.tree_states.select);
			top.treeData.unselectNode();
			top.drawTree();
			break;
		case "mv":
			we_cmd('move', 1, args[2]);
			treeData.setState(treeData.tree_states.selectitem);
			top.treeData.unselectNode();
			top.drawTree();
			break;//add_to_collection
		case "tocollection":
			we_cmd('addToCollection', 1, args[2]);
			treeData.setState(treeData.tree_states.select);
			top.treeData.unselectNode();
			top.drawTree();
			break;
		case "changeLanguageRecursive":
		case "changeTriggerIDRecursive":
			we_repl(self.load, url, args[0]);
			break;
		case "logout":
			we_repl(self.load, url, args[0]);
			break;
		case "dologout":
			// before the command 'logout' is executed, ask if unsaved changes should be saved
			if (WE().layout.weEditorFrameController.doLogoutMultiEditor()) {
				regular_logout = true;
				we_cmd('logout');
			}
			break;
		case "exit_multi_doc_question":
			new (WE().util.jsWindow)(this, url, "exit_multi_doc_question", -1, -1, 500, 300, true, false, true);
			break;
		case "loadFolder":
		case "closeFolder":
			we_repl(self.load, url, args[0]);
			break;
		case "reload_editfooter":
			we_repl(WE().layout.weEditorFrameController.getActiveDocumentReference().frames.editFooter, url, args[0]);
			break;
		case "rebuild":
			new (WE().util.jsWindow)(this, url, "rebuild", -1, 0, 609, 645, true, false, true);
			break;
		case "openPreferences":
			new (WE().util.jsWindow)(this, url, "preferences", -1, -1, 900, 670, true, true, true, true);
			break;
		case "editCat":
			we_cmd("we_selector_category", 0, WE().consts.tables.CATEGORY_TABLE, "", "", "", "", "", 1);
			break;
		case "editThumbs":
			new (WE().util.jsWindow)(this, url, "thumbnails", -1, -1, 570, 660, true, true, true);
			break;
		case "editMetadataFields":
			new (WE().util.jsWindow)(this, url, "metadatafields", -1, -1, 500, 550, true, true, true);
			break;
		case "doctypes":
			new (WE().util.jsWindow)(this, url, "doctypes", -1, -1, 800, 670, true, true, true);
			break;
		case "info":
			new (WE().util.jsWindow)(this, url, "info", -1, -1, 432, 360, true, false, true);
			break;
		case "webEdition_online":
			new (WE().util.jsWindow)(this, "http://www.webedition.org/", "webEditionOnline", -1, -1, 960, 700, true, true, true, true);
			break;
		case "snippet_shop":
			alert("Es gibt noch keine URL für die Snippets Seite");
			break;
		case "help_modules":
			WE().util.jsWindow.prototype.focus('edit_module');
			url = "http://help.webedition.org/index.php?language=" + WE().session.helpLang;
			new (WE().util.jsWindow)(this, url, "help", -1, -1, 800, 600, true, false, true, true);
			break;
		case "info_modules":
			WE().util.jsWindow.prototype.focus('edit_module');
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=info";
			new (WE().util.jsWindow)(this, url, "info", -1, -1, 432, 350, true, false, true);
			break;
		case "help_tools":
			WE().util.jsWindow.prototype.focus('tool_window') ||
							WE().util.jsWindow.prototype.focus('tool_window_navigation') ||
							WE().util.jsWindow.prototype.focus('tool_window_weSearch');
			url = "http://help.webedition.org/index.php?language=" + WE().session.helpLang;
			new (WE().util.jsWindow)(this, url, "help", -1, -1, 800, 600, true, false, true, true);
			break;
		case "info_tools":
			WE().util.jsWindow.prototype.focus('tool_window') ||
							WE().util.jsWindow.prototype.focus('tool_window_navigation') ||
							WE().util.jsWindow.prototype.focus('tool_window_weSearch');
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=info";
			new (WE().util.jsWindow)(this, url, "info", -1, -1, 432, 350, true, false, true);
			break;
		case "help":
			url = "http://help.webedition.org/index.php?language=" + WE().session.helpLang;
			new (WE().util.jsWindow)(this, url, "help", -1, -1, 720, 600, true, false, true, true);
			break;
		case "help_forum":
			new (WE().util.jsWindow)(this, "http://forum.webedition.org", "help_forum", -1, -1, 960, 700, true, true, true, true);
			break;
		case "help_bugtracker":
			new (WE().util.jsWindow)(this, "http://qa.webedition.org/tracker/", "help_bugtracker", -1, -1, 960, 700, true, true, true, true);
			break;
		case "help_changelog":
			new (WE().util.jsWindow)(this, "http://www.webedition.org/de/webedition-cms/versionshistorie/", "help_changelog", -1, -1, 960, 700, true, true, true, true);
			break;
		case "we_customer_selector":
		case "we_selector_file":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.windowSelect.width, WE().consts.size.windowSelect.height, true, true, true, true);
			break;
		case "we_selector_directory":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.windowDirSelect.width, WE().consts.size.windowDirSelect.height, true, true, true, true);
			break;
		case "we_selector_image":
		case "we_selector_document":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "we_fileupload_editor":
			new (WE().util.jsWindow)(this, url, "we_fileupload_editor", -1, -1, 500, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "setTab":
			if (treeData !== undefined) {
				setActiveVTab(args[1]);
				treeData.table = args[1];
			} else {
				setTimeout(we_cmd, 500, "setTab", args[1]);
			}
			break;
		case "update_image":
		case "update_file":
		case "copyDocument":
		case "insert_entry_at_list":
		case "delete_list":
		case "down_entry_at_list":
		case "up_entry_at_list":
		case "down_link_at_list":
		case "up_link_at_list":
		case "add_entry_to_list":
		case "add_link_to_linklist":
		case "change_link":
		case "change_linklist":
		case "delete_linklist":
		case "insert_link_at_linklist":
		case "change_doc_type":
		case "doctype_changed":
		case "remove_image":
		case "delete_link":
		case "delete_cat":
		case "add_cat":
		case "delete_all_cats":
		case "schedule_add":
		case "schedule_del":
		case "schedule_add_schedcat":
		case "schedule_delete_all_schedcats":
		case "schedule_delete_schedcat":
		case "template_changed":
		case "add_navi":
		case "delete_navi":
		case "delete_all_navi":
			// set Editor hot
			var _EditorFrame = WE().layout.weEditorFrameController.getActiveEditorFrame();
			_EditorFrame.setEditorIsHot(true);
			/* falls through */
		case "reload_editpage":
		case "wrap_on_off":
		case "restore_defaults":
		case "do_add_thumbnails":
		case "del_thumb":
		case "resizeImage":
		case "rotateImage":
		case "doImage_convertGIF":
		case "doImage_convertPNG":
		case "doImage_convertJPEG":
		case "doImage_crop":
		case "revert_published":

			// get editor root frame of active tab
			var _currentEditorRootFrame = WE().layout.weEditorFrameController.getActiveDocumentReference();
			// get visible frame for displaying editor page
			var _visibleEditorFrame = WE().layout.weEditorFrameController.getVisibleEditorFrame();
			// if cmd equals "reload_editpage" and there are parameters, attach them to the url
			if (args[0] === "reload_editpage" && _currentEditorRootFrame.parameters) {
				url += _currentEditorRootFrame.parameters;
			}

			// attach necessary parameters if available
			if (args[0] === "reload_editpage" && args[1]) {
				url += '#f' + args[1];
			} else if (args[0] === "remove_image" && args[2]) {
				url += '#f' + args[2];
			}

			// focus visible editor frame
			if (_visibleEditorFrame) {
				_visibleEditorFrame.focus();
			}

			if (_currentEditorRootFrame) {
				if (!we_sbmtFrm(_visibleEditorFrame, url, _visibleEditorFrame)) {
					if (args[0] !== "update_image") {
						// add we_transaction, if not set
						if (!args[2]) {
							args[2] = WE().layout.weEditorFrameController.getActiveEditorFrame().getEditorTransaction();
						}
						url += "&we_transaction=" + args[2];
					}
					we_repl(_visibleEditorFrame, url, args[0]);
				}
			}

			break;
		case "edit_document_with_parameters":
		case "edit_document":
			try {
				if ((self.treeData !== undefined) && treeData) {
					treeData.unselectNode();
					if (args[1]) {
						treeData.selection_table = args[1];
					}
					if (args[2]) {
						treeData.selection = args[2];
					}
					if (treeData.selection_table === treeData.table) {
						treeData.selectNode(treeData.selection);
					}
				}
			} catch (e) {
			}
			var nextWindow;
			var ctrl = WE().layout.weEditorFrameController;
			if ((nextWindow = ctrl.getFreeWindow())) {
				_nextContent = nextWindow.getDocumentReference();
				// activate tab and set state to loading
				WE().layout.multiTabs.addTab(nextWindow.getFrameId(), nextWindow.getFrameId(), nextWindow.getFrameId());
				// use Editor Frame
				nextWindow.initEditorFrameData(
								{
									"EditorType": "model",
									"EditorEditorTable": args[1],
									"EditorDocumentId": args[2],
									"EditorContentType": args[3]
								}
				);
				// set Window Active and show it
				ctrl.setActiveEditorFrame(nextWindow.FrameId);
				ctrl.toggleFrames();
				if (_nextContent.frames && _nextContent.frames[1]) {
					if (!we_sbmtFrm(_nextContent, url)) {
						we_repl(_nextContent, url + "&frameId=" + nextWindow.getFrameId());
					}
				} else {
					we_repl(_nextContent, url + "&frameId=" + nextWindow.getFrameId());
				}
			} else {
				alert(WE().consts.g_l.main.no_editor_left);
			}
			break;
		case "open_extern_document":
		case "new_document":
			var nextWindow;
			var ctrl = WE().layout.weEditorFrameController;
			if ((nextWindow = ctrl.getFreeWindow())) {
				_nextContent = nextWindow.getDocumentReference();
				// activate tab and set it status loading ...
				WE().layout.multiTabs.addTab(nextWindow.getFrameId(), nextWindow.getFrameId(), nextWindow.getFrameId());
				nextWindow.updateEditorTab();
				// set Window Active and show it
				ctrl.setActiveEditorFrame(nextWindow.getFrameId());
				ctrl.toggleFrames();
				// load new document editor
				we_repl(_nextContent, url + "&frameId=" + nextWindow.getFrameId());
			} else {
				alert(WE().consts.g_l.main.no_editor_left);
			}
			break;
		case "close_document":
			if (args[1]) { // close special tab
				WE().layout.weEditorFrameController.closeDocument(args[1]);
			} else if ((_currentEditor = WE().layout.weEditorFrameController.getActiveEditorFrame())) {
				// close active tab
				WE().layout.weEditorFrameController.closeDocument(_currentEditor.getFrameId());
			}
			break;
		case "close_all_documents":
			WE().layout.weEditorFrameController.closeAllDocuments();
			break;
		case "close_all_but_active_document":
			activeId = null;
			if (args[1]) {
				activeId = args[1];
			}
			WE().layout.weEditorFrameController.closeAllButActiveDocument(activeId);
			break;
		case "open_url_in_editor":
			we_repl(self.load, url, args[0]);
			break;
		case "publish":
		case "unpublish":
			doPublish(url, args[1], args[0]);
			break;
		case "save_document":
			var _EditorFrame = WE().layout.weEditorFrameController.getActiveEditorFrame();
			if (_EditorFrame && _EditorFrame.getEditorFrameWindow().frames && _EditorFrame.getEditorFrameWindow().frames[1]) {
				_EditorFrame.getEditorFrameWindow().frames[1].focus();
			}

			if (!args[1]) {
				args[1] = _EditorFrame.getEditorTransaction();
			}

			doSave(url, args[1], args[0]);
			break;
		case "we_selector_delete":
			new (WE().util.jsWindow)(this, url, "we_del_selector", -1, -1, WE().consts.size.windowDelSelect.width, WE().consts.size.windowDelSelect.height, true, true, true, true);
			break;
		case "browse":
			openBrowser();
			break;
		case "home":
			if (top.treeData) {
				top.treeData.unselectNode();
			}
			WE().layout.weEditorFrameController.openDocument('', '', '', 'open_cockpit');
			break;
		case "browse_server":
			new (WE().util.jsWindow)(this, url, "browse_server", -1, -1, 840, 400, true, false, true);
			break;
		case "make_backup":
			new (WE().util.jsWindow)(this, url, "export_backup", -1, -1, 680, 600, true, true, true);
			break;
		case "recover_backup":
			new (WE().util.jsWindow)(this, url, "recover_backup", -1, -1, 680, 600, true, true, true);
			break;
		case "import_docs":
			new (WE().util.jsWindow)(this, url, "import_docs", -1, -1, 480, 390, true, false, true);
			break;
		case "import":
			new (WE().util.jsWindow)(this, url, "import", -1, -1, 650, 720, true, false, true);
			break;
		case "import_files":
			new (WE().util.jsWindow)(this, url, "import_files", -1, -1, 650, 720, true, false, true);
			break;
		case "export":
			new (WE().util.jsWindow)(this, url, "export", -1, -1, 600, 540, true, false, true);
			break;
		case "copyWeDocumentCustomerFilter":
			new (WE().util.jsWindow)(this, url, "copyWeDocumentCustomerFilter", -1, -1, 400, 115, true, true, true);
			break;
		case "copyFolder":
			new (WE().util.jsWindow)(this, url, "copyfolder", -1, -1, 550, 320, true, true, true);
			break;
		case "del_frag":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "delFrag.php?currentID=" + args[1], "we_del", -1, -1, 600, 130, true, true, true);
			break;
		case "open_wysiwyg_window":
			if (WE().layout.weEditorFrameController.getActiveDocumentReference()) {
				WE().layout.weEditorFrameController.getActiveDocumentReference().openedWithWE = false;
			}
			var wyw = args[2];
			wyw = Math.max((wyw ? wyw : 0), 400);
			var wyh = args[3];
			wyh = Math.max((wyh ? wyh : 0), 300);
			if (window.screen) {
				var screen_height = ((screen.height - 50) > screen.availHeight) ? screen.height - 50 : screen.availHeight;
				screen_height = screen_height - 40;
				var screen_width = screen.availWidth - 10;
				wyw = Math.min(screen_width, wyw);
				wyh = Math.min(screen_height, wyh);
			}
			// set new width & height
			url = url.replace(/we_cmd\[2\]=[^&]+/, 'we_cmd[2]=' + wyw);
			url = url.replace(/we_cmd\[3\]=[^&]+/, 'we_cmd[3]=' + (wyh - args[10]));
			new (WE().util.jsWindow)(this, url, "we_wysiwygWin", -1, -1, Math.max(220, wyw + (document.all ? 0 : ((navigator.userAgent.toLowerCase().indexOf('safari') > -1) ? 20 : 4))), Math.max(100, wyh + 60), true, false, true);
			break;
		case "not_installed_modules":
			we_repl(self.load, url, args[0]);
			break;
		case "start_multi_editor":
			we_repl(self.load, url, args[0]);
			break;
		case "customValidationService":
			new (WE().util.jsWindow)(this, url, "we_customizeValidation", -1, -1, 700, 700, true, false, true);
			break;
		case "edit_home":
			if (args[1] === 'add') {
				self.load.location = WE().consts.dirs.WEBEDITION_DIR + 'we_cmd.php?we_cmd[0]=widget_cmd&we_cmd[1]=' + args[1] + '&we_cmd[2]=' + args[2] + '&we_cmd[3]=' + args[3];
			}
			break;
		case "edit_navi":
			new (WE().util.jsWindow)(this, url, "we_navieditor", -1, -1, 400, 360, true, true, true, true);
			break;
		case "initPlugin":
			weplugin_wait = new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "editors/content/eplugin/weplugin_wait.php?callback=" + args[1], "weplugin_wait", -1, -1, 300, 100, true, false, true);
			break;
		case "edit_settings_editor":
			if (top.plugin.editSettings) {
				top.plugin.editSettings();
			} else {
				we_cmd("initPlugin", "top.plugin.editSettings()");
			}
			break;
		case "sysinfo":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=sysinfo", "we_sysinfo", -1, -1, 720, 660, true, false, true);
			break;
		case "showerrorlog":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "errorlog.php", "we_errorlog", -1, -1, 920, 660, true, false, true);
			break;
		case "view_backuplog":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=backupLog", "we_backuplog", -1, -1, 720, 660, true, false, true);
			break;
		case "show_message_console":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=messageConsole", "we_jsMessageConsole", -1, -1, 600, 500, true, false, true, false);
			break;
		case "remove_from_editor_plugin":
			if (args[1] && top.plugin && top.plugin.remove) {
				top.plugin.remove(args[1]);
			}
			break;
		case "new":
			if (WE().session.seemode) {
				WE().layout.weEditorFrameController.openDocument(args[1], args[2], args[3], "", args[4], "", args[5]);
				break;
			}
			treeData.unselectNode();
			if (args[5] !== undefined) {
				WE().layout.weEditorFrameController.openDocument(args[1], args[2], args[3], "", args[4], "", args[5]);
			} else {
				WE().layout.weEditorFrameController.openDocument(args[1], args[2], args[3], "", args[4]);
			}
			break;
		case "load":
			if (!WE().session.seemode) {
				top.setScrollY();

				var table = (args[1] !== undefined && args[1]) ? args[1] : WE().consts.tables.FILE_TABLE;
				we_cmd("setTab", table);
				we_repl(self.load, url, args[0]);
			}
			break;
		case "exit_delete":
		case "exit_move":
		case "exit_addToCollection":
			deleteMode = false;
			if (!WE().session.seemode) {
				treeData.setState(treeData.tree_states.edit);
				drawTree();
				var cl = self.document.getElementById("bm_treeheaderDiv").classList;
				cl.remove('deleteSelector');
				cl.remove('moveSelector');
				cl.remove('collectionSelector');
				cl = self.document.getElementById("treetable").classList;
				cl.remove('deleteSelector');
				cl.remove('moveSelector');
				cl.remove('collectionSelector');
				top.setTreeWidth(widthBeforeDeleteMode);
				top.setSidebarWidth(widthBeforeDeleteModeSidebar);
			}
			break;
		case "delete":
			if (WE().session.seemode) {
				if (top.deleteMode != args[1]) {
					top.deleteMode = args[1];
				}
				if (args[2] != 1) {
					we_repl(WE().layout.weEditorFrameController.getActiveDocumentReference(), url, args[0]);
				}
				break;
			}
			if (top.deleteMode != args[1]) {
				top.deleteMode = args[1];
			}
			if (!top.deleteMode && treeData.state == treeData.tree_states.select) {
				treeData.setState(treeData.tree_states.edit);
				drawTree();
			}
			self.document.getElementById("bm_treeheaderDiv").classList.add('deleteSelector');
			self.document.getElementById("treetable").classList.add('deleteSelector');
			top.toggleTree(true);
			var width = top.getTreeWidth();

			widthBeforeDeleteMode = width;

			if (width < WE().consts.size.tree.deleteWidth) {
				top.setTreeWidth(WE().consts.size.tree.deleteWidth);
			}
			top.storeTreeWidth(widthBeforeDeleteMode);

			var widthSidebar = top.getSidebarWidth();

			widthBeforeDeleteModeSidebar = widthSidebar;

			if (args[2] != 1) {
				we_repl(document.getElementsByName("treeheader")[0], url, args[0]);
			}
			break;
		case "move":
			if (WE().session.seemode) {
				if (top.deleteMode != args[1]) {
					top.deleteMode = args[1];
				}
				if (args[2] != 1) {
					we_repl(WE().layout.weEditorFrameController.getActiveDocumentReference(), url, args[0]);
				}
			} else {
				if (top.deleteMode != args[1]) {
					top.deleteMode = args[1];
				}
				if (!top.deleteMode && treeData.state == treeData.tree_states.selectitem) {
					treeData.setState(treeData.tree_states.edit);
					drawTree();
				}
				self.document.getElementById("bm_treeheaderDiv").classList.add('moveSelector');
				self.document.getElementById("treetable").classList.add('moveSelector');
				top.toggleTree(true);
				var width = top.getTreeWidth();

				widthBeforeDeleteMode = width;

				if (width < WE().consts.size.tree.moveWidth) {
					top.setTreeWidth(WE().consts.size.tree.moveWidth);
				}
				top.storeTreeWidth(widthBeforeDeleteMode);

				var widthSidebar = top.getSidebarWidth();

				widthBeforeDeleteModeSidebar = widthSidebar;

				if (args[2] != 1) {
					we_repl(document.getElementsByName("treeheader")[0], url, args[0]);
				}
			}
			break;
		case "addToCollection":
			if (WE().session.seemode) {
				//
			} else {
				if (top.deleteMode != args[1]) {
					top.deleteMode = args[1];
				}
				if (!top.deleteMode && treeData.state == treeData.tree_states.select) {
					treeData.setState(treeData.tree_states.edit);
					drawTree();
				}
				self.document.getElementById("bm_treeheaderDiv").classList.add('collectionSelector');
				self.document.getElementById("treetable").classList.add('collectionSelector');
				top.toggleTree(true);
				var width = top.getTreeWidth();
				widthBeforeDeleteMode = width;
				if (width < WE().consts.size.tree.moveWidth) {
					top.setTreeWidth(WE().consts.size.tree.moveWidth);
				}
				top.storeTreeWidth(widthBeforeDeleteMode);

				var widthSidebar = top.getSidebarWidth();
				widthBeforeDeleteModeSidebar = widthSidebar;

				if (args[2] != 1) {
					we_repl(document.getElementsByTagName("iframe").treeheader, url, args[0]);
				}
			}
			break;
		case "reset_home":
			var _currEditor = WE().layout.weEditorFrameController.getActiveEditorFrame();
			if (_currEditor && _currEditor.getEditorType() === "cockpit") {
				if (confirm(WE().consts.g_l.cockpit.reset_settings)) {
					//FIXME: currently this doesn't work
					WE().layout.weEditorFrameController.getActiveDocumentReference().location = WE().consts.dirs.WEBEDITION_DIR + 'we_cmd.php?we_cmd[0]=widget_cmd&we_cmd[1]' + args[0];
					if ((window.treeData !== undefined) && window.treeData) {
						window.treeData.unselectNode();
					}
				}
			} else {
				top.we_showMessage(WE().consts.g_l.cockpit.not_activated, WE().consts.message.WE_MESSAGE_NOTICE, window);
			}
			break;

		case "new_widget_sct":
		case "new_widget_rss":
		case "new_widget_msg":
		case "new_widget_usr":
		case "new_widget_mfd":
		case "new_widget_upb":
		case "new_widget_mdc":
		case "new_widget_pad":
		case "new_widget_shp":
		case "new_widget_fdl":
			if (WE().layout.weEditorFrameController.getActiveDocumentReference() && WE().layout.weEditorFrameController.getActiveDocumentReference().quickstart) {
				WE().layout.weEditorFrameController.getActiveDocumentReference().createWidget(args[0].substr(args[0].length - 3), 1, 1);
			} else {
				top.we_showMessage(WE().consts.g_l.cockpit.not_activated, WE().consts.message.WE_MESSAGE_ERROR, this);
			}
			break;
		case "open_document":
			we_cmd("load", WE().consts.tables.FILE_TABLE);
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=we_selector_document&we_cmd[2]=" + WE().consts.tables.FILE_TABLE + "&we_cmd[5]=" + encodeURIComponent("WE().layout.weEditorFrameController.openDocument(table,currentID,currentType)") + "&we_cmd[9]=1";
			new (WE().util.jsWindow)(this, url, "we_dirChooser", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "open_collection":
			we_cmd("load", WE().consts.tables.VFILE_TABLE);
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=we_selector_document&we_cmd[2]=" + WE().consts.tables.VFILE_TABLE + "&we_cmd[5]=" + encodeURIComponent("WE().layout.weEditorFrameController.openDocument(table,currentID,currentType)") + "&we_cmd[9]=1";
			new (WE().util.jsWindow)(this, url, "we_dirChooser", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "edit_new_collection":
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=editNewCollection&we_cmd[1]=" + args[1] + "&we_cmd[2]=" + args[2] + "&fixedpid=" + args[3] + "&fixedremtable=" + args[4];
			new (WE().util.jsWindow)(this, url, "weNewCollection", -1, -1, 590, 560, true, true, true, true);
			break;
		case "help_documentation":
			new (WE().util.jsWindow)(this, "http://documentation.webedition.org/", "help_documentation", -1, -1, 960, 700, true, true, true, true);
			break;

		case "help_tagreference":
			new (WE().util.jsWindow)(this, "http://tags.webedition.org/" + WE().session.docuLang + "/", "help_tagreference", -1, -1, 960, 700, true, true, true, true);
			break;
		case "open_tagreference":
			var docupath = "http://tags.webedition.org/" + WE().session.docuLang + "/" + args[1];
			new (WE().util.jsWindow)(this, docupath, "we_tagreference", -1, -1, 1024, 768, true, true, true);
			break;
		case "open_template":
			we_cmd("load", WE().consts.tables.TEMPLATES_TABLE);
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=we_selector_document&we_cmd[2]=" + WE().consts.tables.TEMPLATES_TABLE + "&we_cmd[5]=" + encodeURIComponent("WE().layout.weEditorFrameController.openDocument(table,currentID,currentType)") + "&we_cmd[8]=" + WE().consts.contentTypes.TEMPLATE + "&we_cmd[9]=1";
			new (WE().util.jsWindow)(this, url, "we_dirChooser", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "switch_edit_page":
			// get editor root frame of active tab
			var _currentEditorRootFrame = WE().layout.weEditorFrameController.getActiveDocumentReference();
			// get visible frame for displaying editor page
			var _visibleEditorFrame = WE().layout.weEditorFrameController.getVisibleEditorFrame();
			// frame where the form should be sent from
			var _sendFromFrame = _visibleEditorFrame;
			// set flag to true if active frame is frame nr 2 (frame for displaying editor page 1 with content editor)
			var _isEditpageContent = (_visibleEditorFrame === _currentEditorRootFrame.frames[2]);
			//var _isEditpageContent = _visibleEditorFrame == _currentEditorRootFrame.document.getElementsByTagName("div")[2].getElementsByTagName("iframe")[0];

			// if we switch from we_base_constants::WE_EDITPAGE_CONTENT to another page
			if (_isEditpageContent && args[1] !== WE().consts.global.WE_EDITPAGE_CONTENT) {
				// clean body to avoid flickering
				try {
					_currentEditorRootFrame.frames[1].document.body.innerHTML = "";
				} catch (e) {
					//can be caused by not loaded content
				}
				// switch to normal frame
				WE().layout.weEditorFrameController.switchToNonContentEditor();
				// set var to new active editor frame
				_visibleEditorFrame = _currentEditorRootFrame.frames[1];
				//_visibleEditorFrame = _currentEditorRootFrame.document.getElementsByTagName("div")[1].getElementsByTagName("iframe")[0];

				// set flag to false
				_isEditpageContent = false;
				// if we switch to we_base_constants::WE_EDITPAGE_CONTENT from another page
			} else if (!_isEditpageContent && args[1] === WE().consts.global.WE_EDITPAGE_CONTENT) {
				// switch to content editor frame
				WE().layout.weEditorFrameController.switchToContentEditor();
				// set var to new active editor frame
				_visibleEditorFrame = _currentEditorRootFrame.frames[2];
				//_visibleEditorFrame = _currentEditorRootFrame.document.getElementsByTagName("div")[2].getElementsByTagName("iframe")[0];
				// set flag to false
				_isEditpageContent = true;
			}

			// frame where the form should be sent to
			var _sendToFrame = _visibleEditorFrame;
			// get active transaction
			var _we_activeTransaction = WE().layout.weEditorFrameController.getActiveEditorFrame().getEditorTransaction();
			// if there are parameters, attach them to the url
			if (_currentEditorRootFrame.parameters) {
				url += _currentEditorRootFrame.parameters;
			}

			// focus the frame
			if (_sendToFrame) {
				_sendToFrame.focus();
			}
			// if visible frame equals to editpage content and there is already content loaded
			if (_isEditpageContent && _visibleEditorFrame && _visibleEditorFrame.weIsTextEditor !== undefined && _currentEditorRootFrame.frames[2].location !== "about:blank") {
				// tell the backend the right edit page nr and break (don't send the form)
				YAHOO.util.Connect.asyncRequest('POST', WE().consts.dirs.WEBEDITION_DIR + "rpc.php", {
					success: function (o) {
					},
					failure: function (o) {
						alert(WE().consts.g_l.main.unable_to_call_setpagenr);
					}
				}, "protocol=json&cmd=SetPageNr&transaction=" + _we_activeTransaction + "&editPageNr=" + args[1]);
				if (_visibleEditorFrame.reloadContent === false) {
					break;
				}
				_visibleEditorFrame.reloadContent = false;
			}

			if (_currentEditorRootFrame) {

				if (!we_sbmtFrm(_sendToFrame, url, _sendFromFrame)) {
					// add we_transaction, if not set
					if (!args[2]) {
						args[2] = _we_activeTransaction;
					}
					url += "&we_transaction=" + args[2];
					we_repl(_sendToFrame, url, args[0]);
				}
			}

			break;
		case "insert_variant":
		case "move_variant_up":
		case "move_variant_down":
		case "remove_variant":
			url += "#f" + (parseInt(args[1]) - 1);
			we_sbmtFrm(WE().layout.weEditorFrameController.getActiveDocumentReference().frames[1], url);
			break;
		case 'preview_variant':
			url += "#f" + (parseInt(args[1]) - 1);
			var prevWin = new (WE().util.jsWindow)(this, url, "previewVariation", -1, -1, 1600, 1200, true, true, true, true);
			we_sbmtFrm(prevWin.wind, url);
			break;

		default:
			return false;
	}
	return true;
}

WE().util.in_array = function (needle, haystack) {
	for (var i = 0; i < haystack.length; i++) {
		if (haystack[i] == needle) {
			return true;
		}
	}
	return false;
};

WE().util.hasPerm = function (perm) {
	return (WE().session.permissions.ADMINISTRATOR || WE().session.permissions[perm] ? true : false);
};


/**
 * This function sets incons inside elements of a given class. The element must have the property data-contenttype and data-extension set to determine the correct icon
 * @param string classname the elements classname
 * @returns noting
 */
WE().util.setIconOfDocClass = function (doc, classname) {
	var elements = doc.getElementsByClassName(classname);
	for (var i = 0; i < elements.length; i++) {
		elements[i].innerHTML = this.getTreeIcon(elements[i].getAttribute("data-contenttype"), false, elements[i].getAttribute("data-extension"));
	}
};


/**
 * Get a file icon out of a given type, used in tree, selectors & tabs
 * @param {type} contentType
 * @param {type} open
 * @returns icon to be drawn as html-code
 */
WE().util.getTreeIcon = function (contentType, open, extension) {
	var simplepre = '<span class="fa-stack fa-lg fileicon">';
	var pre = simplepre + '<i class="fa fa-file fa-inverse fa-stack-2x fa-fw"></i>',
					post = '</span>';
	switch (contentType) {
		case 'cockpit':
			return simplepre + '<i class="fa fa-th-large fa-stack-2x"></i>' + post;
		case 'class_folder'://FIXME: this contenttype is not set
		case 'we/bannerFolder':
		case 'folder':
			return simplepre + '<i class="fa fa-folder' + (open ? '-open' : '') + ' fa-stack-2x"></i><i class="fa fa-folder' + (open ? '-open' : '') + '-o fa-stack-2x"></i>' + post;
		case  'image/*':
			return pre + '<i class="fa fa-file-image-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'text/js':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-otherfiles"><i class="fa fa-stack-1x">js</i></span>' + post;
		case 'text/css':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-otherfiles"><i class="fa fa-stack-1x">cs</i></span>' + post;
		case 'text/htaccess':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-otherfiles"><i class="fa fa-stack-1x">ht</i></span>' + post;
		case 'text/weTmpl':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-icon"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-stack-1x fa-inverse">e</i></span><span class="we-classification"><i class="fa fa-stack-1x">T</i></span>' + post;
		case 'text/webedition':
			return pre + '<i class="fa fa-file-text-o fa-stack-2x"></i><span class="we-icon"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-stack-1x fa-inverse">e</i></span>' + post;
		case 'text/xml':
		case 'text/html':
			return pre + '<i class="fa fa-file-code-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'application/x-shockwave-flash':
		case 'video/quicktime':
		case 'video/*':
			return pre + '<i class="fa fa-file-video-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'audio/*':
			return pre + '<i class="fa fa-file-sound-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'text/plain':
			return pre + '<i class="fa fa-file-text-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'file':
		case 'application/*':
			switch (extension) {
				case '.pdf':
					return pre + '<i class="fa fa-file-pdf-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
				case '.zip' :
				case '.sit' :
				case '.hqx' :
				case '.bin' :
					return pre + '<i class="fa fa-file-archive-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
				case '.odg':
				case '.otg':
				case '.odt':
				case '.ott':
				case '.dot' :
				case '.doc' :
					return pre + '<i class="fa fa-file-word-o fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
				case '.ods':
				case '.ots':
				case '.xlt' :
				case '.xls' :
					return pre + '<i class="fa fa-table fa-stack-1x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
				case '.odp':
				case '.otp':
				case '.ppt' :
					return pre + '<i class="fa fa-line-chart fa-stack-1x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
				default:
					return pre + '<i class="fa fa-file-o fa-stack-2x"></i>' + post;
			}
			return '';
		case 'object':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-icon"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-stack-1x fa-inverse">e</i></span><span class="we-classification"><i class="fa fa-stack-1x">C</i></span>' + post;
		case 'objectFile':
			return pre + '<i class="fa fa-file-o fa-stack-2x"></i><span class="we-icon"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-stack-1x fa-inverse">e</i></span><span class="we-classification"><i class="fa fa-stack-1x">O</i></span>' + post;
		case 'text/weCollection':
			return simplepre + '<i class="fa fa-archive fa-stack-2x we-color"></i>' + post;
//Banner module
		case 'we/banner':
			return simplepre + '<i class="fa fa-flag-checkered fa-stack-2x we-color"></i>' + post;
		case 'we/customerGroup':
		case 'we/userGroup':
			return simplepre + '<i class="fa fa-users fa-stack-2x we-color"></i>' + post;
		case 'we/alias':
			return simplepre + '<i class="fa fa-user fa-stack-2x" style="color:grey"></i>' + post;
		case 'we/customer':
		case 'we/user':
			return simplepre + '<i class="fa fa-user fa-stack-2x we-color"></i>' + post;
		case 'we/export':
			return pre + '<i class="fa fa-download fa-stack-2x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'we/glossar':
			return simplepre + '<i class="fa fa-commenting fa-stack-2x we-color"></i>' + post;
		case 'we/newsletter':
			return simplepre + '<i class="fa fa-newspaper-o fa-stack-2x we-color"></i>' + post;
		case 'we/voting':
			return simplepre + '<i class="fa fa-thumbs-up fa-stack-2x we-color"></i>' + post;
		case 'we/navigation':
			return simplepre + '<i class="fa fa-compass fa-stack-2x we-color"></i>' + post;
		case 'we/search':
			return pre + '<i class="fa fa-search fa-stack-1x we-color"></i><i class="fa fa-file-o fa-stack-2x"></i>' + post;
		case 'we/shop':
			return simplepre + '<i class="fa fa-shopping-cart fa-stack-2x we-color"></i></i>' + post;
		case 'we/workflow':
			return simplepre + '<i class="fa fa-fa-gears fa-stack-2x we-color"></i></i>' + post;
		case 'we/categories':
			return simplepre + '<i class="fa fa-tags fa-stack-2x we-color"></i>' + post;
		case 'we/category':
			return simplepre + '<i class="fa fa-tag fa-stack-2x we-color"></i>' + post;
		case 'symlink':
			return pre + '<i class="fa fa-link fa-stack-2x we-color"></i>' + post;
		case 'settings':
			return simplepre + '<i class="fa fa-list fa-stack-2x we-color"></i>' + post;

		default:
			return pre + '<i class="fa fa-file-o fa-stack-2x ' + contentType + '"></i>' + post;
	}
};

WE().util.sprintf = function (argum) {
	if (!arguments || arguments.length === 0) {
		return;
	}

	var regex = /([^%]*)%(%|d|s)(.*)/;
	var arr = [];
	var iterator = 0;
	var matches = 0;

	while ((arr = regex.exec(argum))) {
		var left = arr[1];
		var type = arr[2];
		var right = arr[3];

		matches++;
		iterator++;

		var replace = arguments[iterator];

		switch (type) {
			case "d":
				replace = parseInt(arguments[iterator]) ? parseInt(arguments[iterator]) : 0;
				break;
			case "s":
				replace = arguments[iterator];
				break;
		}
		argum = left + replace + right;
	}
	return argum;
};

WE().util.IsDigitPercent = function (e) {
	var key;
	if (e.charCode === undefined) {
		key = event.keyCode;
	} else {
		key = e.charCode;
	}

	return (((key >= 48) && (key <= 57)) || (key === 37) || (key === 0) || (key === 46) || (key === 101) || (key === 109) || (key === 13) || (key === 8) || (key <= 63235 && key >= 63232) || (key === 63272));
};

WE().util.IsDigit = function (e) {
	var key = e.charCode === undefined ? event.keyCode : e.charCode;
	return ((key === 46) || ((key >= 48) && (key <= 57)) || (key === 0) || (key === 13) || (key === 8) || (key <= 63235 && key >= 63232) || (key === 63272));
};

WE().util.getWe_cmdArgsArray = function (arr) {
	if (arr.lenght > 0 && typeof arr[0] === "object") {
		return arr[0];
	}
	return arr;
};

WE().util.getWe_cmdArgsUrl = function (args, base) {
	var url = (base === undefined ? WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?" : base);

	if (Object.prototype.toString.call(args) === '[object Array]') {
		for (var i = 0; i < args.length; i++) {
			url += "we_cmd[" + i + "]=" + encodeURIComponent(args[i]) + (i < (args.length - 1) ? "&" : "");
		}
	} else {
		url += Object.keys(args).map(function (key) {
			return "we_cmd[" + key + "]=" + encodeURIComponent(args[key]);
		}).join("&");
	}

	return url;
};


/**
 * setting is built like the unix file system privileges with the 3 options
 * see notices, see warnings, see errors
 *
 * 1 => see Notices
 * 2 => see Warnings
 * 4 => see Errors
 *
 * @param message string
 * @param prio integer one of the values 1,2,4
 * @param win object reference to the calling window
 */
WE().util.showMessage = function (message, prio, win) {
	win = (win ? win : this.window);
	// default is error, to avoid missing messages
	prio = prio ? prio : WE().consts.message.WE_MESSAGE_ERROR;

	// always show in console !
	WE().layout.messageConsole.addMessage(prio, message);

	if (prio & WE().session.messageSettings) { // show it, if you should

		// the used vars are in file JS_DIR . "weJsStrings.php";
		switch (prio) {
			// Notice
			case WE().consts.message.WE_MESSAGE_INFO:
			case WE().consts.message.WE_MESSAGE_NOTICE:
				win.alert(WE().consts.g_l.message_reporting.notice + ":\n" + message);
				break;

				// Warning
			case WE().consts.message.WE_MESSAGE_WARNING:
				win.alert(WE().consts.g_l.message_reporting.warning + ":\n" + message);
				break;

				// Error
			case WE().consts.message.WE_MESSAGE_ERROR:
				win.alert(WE().consts.g_l.message_reporting.error + ":\n" + message);
				break;
		}
	}
};

WE().util.clip = function (doc, unique, width) {
	var text = doc.getElementById("td_" + unique);
	var btn = doc.getElementById("btn_" + unique).firstChild;

	if (text.classList.contains("cutText")) {
		text.classList.remove("cutText");
		text.style.maxWidth = "";
		btn.classList.remove("fa-caret-right");
		btn.classList.add("fa-caret-down");
	} else {
		text.classList.add("cutText");
		text.style.maxWidth = width + "ex";
		btn.classList.remove("fa-caret-down");
		btn.classList.add("fa-caret-right");
	}
};

WE().util.validate = {
	email: function (email) {
		email = email.replace(/".*"/g, "y");
		email = email.replace(/\\./g, "z");
		var parts = email.split("@");
		if (parts.length != 2) {
			return false;
		}
		if (!WE().util.validate.domain(parts[1])) {
			return false;
		}
		if (!parts[0].match(/(.+)/)) {
			return false;
		}
		return true;
	},
	domain: function (domain) {
		var parts = domain.split(".");
		//if(parts.length>3 || parts.length<1) return false;
		//if(parts.length===1 && !WE().util.validate.domainname(parts[0])) return false;
		for (var i = 0; i < (parts.length - 1); i++) {
			if (!WE().util.validate.domainname(parts[i])) {
				return false;
			}
		}
		if (!parts[parts.length - 1].match(/^[a-z][a-z]+$/i)) {
			return false;
		}
		return true;
	},
	domainname: function (domainname) {
		var pattern = /^[^_\-\s/=?\*"'#!§$%&;()\[\]\{\};:,°<>\|][^\s/=?\*"'#!§$%&;()\[\]\{\};:,°<>\|]+$/i;
		if (domainname.match(pattern)) {
			return true;
		}
		return false;
	},
	date: function () {
		// TODO
	},
	currency: function () {
		// TODO
	}
};

WE().util.Base64 = {
	// private property
	_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	// public method for encoding
	encode: function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = this._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
							this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
							this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},
	// public method for decoding
	decode: function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 !== 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 !== 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = this._utf8_decode(output);

		return output;

	},
	// private method for UTF-8 encoding
	_utf8_encode: function (string) {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if ((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			} else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},
	// private method for UTF-8 decoding
	_utf8_decode: function (utftext) {
		var string = "";
		var i = 0;
		var c = c2 = 0;

		while (i < utftext.length) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if ((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i + 1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			} else {
				c2 = utftext.charCodeAt(i + 1);
				c3 = utftext.charCodeAt(i + 2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
};

WE().layout.openToEdit = function (tab, id, contentType) {
	if (id > 0) {
		WE().layout.weEditorFrameController.openDocument(tab, id, contentType);
	}
};

WE().layout.we_setPath = function (_EditorFrame, path, text, id, classname) {
	_EditorFrame = _EditorFrame ? _EditorFrame : WE().layout.weEditorFrameController.getActiveEditorFrame();
	// update document-tab
	_EditorFrame.initEditorFrameData({
		EditorDocumentText: text,
		EditorDocumentPath: path
	});

	if (classname) {
		WE().layout.multiTabs.setTextClass(_EditorFrame.FrameId, classname);
	}
	if (_EditorFrame.getDocumentReference().frames.editHeader === undefined) {
		return;
	}
	var doc = _EditorFrame.getDocumentReference().frames.editHeader.document;
	if (doc) {
		var div = doc.getElementById('h_path');
		if (div) {
			div.innerHTML = path.replace(/</g, '&lt;').replace(/>/g, '&gt;');
			if (id > 0) {
				div = doc.getElementById('h_id');
				div.innerHTML = id;
			}
		}
	}
};