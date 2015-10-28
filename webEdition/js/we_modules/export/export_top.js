/* global WE, top */

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 10569 $
 * $Author: mokraemer $
 * $Date: 2015-10-12 19:56:23 +0200 (Mo, 12. Okt 2015) $
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

function setHot() {
	hot = 1;
}

function usetHot() {
	hot = 0;
}

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}


function we_cmd() {
	var args = [];
	var url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?";
	for (var i = 0; i < arguments.length; i++) {
		args.push(arguments[i]);

		url += "we_cmd[" + i + "]=" + encodeURI(arguments[i]);
		if (i < (arguments.length - 1)) {
			url += "&";
		}
	}
	if (hot === 1 && args[0] !== "save_export") {
		if (confirm(WE().consts.g_l.exports.save_changed_export)) {
			args[0] = "save_export";
		} else {
			top.content.usetHot();
		}
	}
	switch (args[0]) {
		case "exit_export":
			if (hot !== 1) {
				top.opener.top.we_cmd("exit_modules");
			}
			break;
		case "new_export_group":
			if (!WE().util.hasPerm("NEW_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.IsFolder.value = 1;
			}
		case "new_export":
			if (!WE().util.hasPerm("NEW_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				top.content.editor.edbody.document.we_form.cmdid.value = args[1];
				top.content.editor.edbody.document.we_form.pnt.value = "edbody";
				top.content.editor.edbody.document.we_form.tabnr.value = 1;
				top.content.editor.edbody.submitForm();
			} else {
				setTimeout("we_cmd(" + args[0] + ");", 10);
			}
			break;
		case "delete_export":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				return;
			}
			if (!WE().util.hasPerm("DELETE_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}

			if (top.content.editor.edbody.loaded) {
				var message = WE().consts.g_l.exports.delete_question;
				if (top.content.editor.edbody.document.we_form.IsFolder.value == 1)
					message = WE().consts.g_l.exports.delete_group_question;

				if (confirm(message)) {
					top.content.editor.edbody.document.we_form.cmd.value = args[0];
					top.content.editor.edbody.document.we_form.pnt.value = "cmd";
					top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;
					top.content.editor.edbody.submitForm("cmd");
				}
			} else {
				WE().util.showMessage(WE().consts.g_l.exports.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, window);
			}

			break;
		case "start_export":
			if (top.content.hot !== 0) {
				WE().util.showMessage(WE().consts.g_l.exports.must_save, WE().consts.message.WE_MESSAGE_ERROR, window);
				break;
			}
			if (!WE().util.hasPerm("NEW_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}
			if (top.content.editor.edheader.setTab)
				top.content.editor.edheader.weTabs.setActiveTab("tab_3");
			if (top.content.editor.edheader.setTab)
				top.content.editor.edheader.setTab(3);
			if (top.content.editor.edfooter.doProgress)
				top.content.editor.edfooter.doProgress(0);
			if (top.content.editor.edbody.clearLog)
				top.content.editor.edbody.clearLog();
			if (top.content.editor.edbody.addLog)
				top.content.editor.edbody.addLog("<br/><br/>");
		case "save_export":
			if (!WE().util.hasPerm("NEW_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}
			if (top.content.editor.edbody.document.we_form.cmd.value === "home")
				return;

			if (top.content.editor.edbody.loaded) {
				if (top.content.editor.edbody.document.we_form.Text.value === "") {
					WE().util.showMessage(WE().consts.g_l.exports.name_empty, WE().consts.message.WE_MESSAGE_ERROR, window);
					return;
				}
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				top.content.editor.edbody.document.we_form.pnt.value = args[0] === "start_export" ? "load" : "edbody";
				top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;
				if (top.content.editor.edbody.document.we_form.IsFolder.value != 1) {
					top.content.editor.edbody.document.we_form.selDocs.value = top.content.editor.edbody.SelectedItems[WE().consts.dirs.FILE_TABLE].join(",");
					top.content.editor.edbody.document.we_form.selTempl.value = top.content.editor.edbody.SelectedItems[WE().consts.dirs.TEMPLATES_TABLE].join(",");
					top.content.editor.edbody.document.we_form.selDocs_open.value = top.content.editor.edbody.openFolders[WE().consts.dirs.FILE_TABLE];
					top.content.editor.edbody.document.we_form.selTempl_open.value = top.content.editor.edbody.openFolders[WE().consts.dirs.TEMPLATES_TABLE];

					if (WE().consts.tables.OBJECT_FILES_TABLE !== "OBJECT_FILES_TABLE") {
						top.content.editor.edbody.document.we_form.selObjs.value = top.content.editor.edbody.SelectedItems[WE().consts.dirs.OBJECT_FILES_TABLE].join(",");
						top.content.editor.edbody.document.we_form.selObjs_open.value = top.content.editor.edbody.openFolders[WE().consts.dirs.OBJECT_FILES_TABLE];
					}
					if (WE().consts.tables.OBJECT_TABLE !== "OBJECT_TABLE") {
						top.content.editor.edbody.document.we_form.selClasses.value = top.content.editor.edbody.SelectedItems[WE().consts.dirs.OBJECT_TABLE].join(",");
						top.content.editor.edbody.document.we_form.selClasses_open.value = top.content.editor.edbody.openFolders[WE().consts.dirs.OBJECT_TABLE];
					}
				}

				top.content.editor.edbody.submitForm(args[0] == "start_export" ? "cmd" : "edbody");
			} else {
				WE().util.showMessage(WE().consts.g_l.exports.nothing_to_save, WE().consts.message.WE_MESSAGE_ERROR, window);

			}
			top.content.usetHot();
			break;

		case "export_edit":
			if (!WE().util.hasPerm("EDIT_EXPORT")) {
				WE().util.showMessage(WE().consts.g_l.exports.no_perms, WE().consts.message.WE_MESSAGE_ERROR, window);
				return;
			}
			top.content.hot = 0;
			top.content.editor.edbody.document.we_form.cmd.value = args[0];
			top.content.editor.edbody.document.we_form.pnt.value = "edbody";
			top.content.editor.edbody.document.we_form.cmdid.value = args[1];
			top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;

			top.content.editor.edbody.submitForm();
			break;
		case "load":
			top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=export&pnt=cmd&pid=" + args[1] + "&offset=" + args[2] + "&sort=" + args[3];
			break;
		case "home":
			top.content.editor.edbody.parent.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=export&pnt=editor";
			break;
		default:
			top.opener.top.we_cmd.apply(this, args);

	}
}