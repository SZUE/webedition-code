/* global top, WE */

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
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */


function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}


function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);

	if (hot === 1 && args[0] !== "save_workflow") {
		var hotConfirmMsg = confirm(WE().consts.g_l.workflow.view.save_changed_workflow);
		if (hotConfirmMsg === true) {
			args[0] = "save_workflow";
			top.content.usetHot();
		} else {
			top.content.setHot();
		}
	}
	switch (args[0]) {
		case "exit_workflow":
			if (hot !== 1) {
				top.opener.top.we_cmd('exit_modules');
			}
			break;
		case "new_workflow":
			top.content.editor.edbody.document.we_form.wcmd.value = args[0];
			top.content.editor.edbody.document.we_form.wid.value = args[1];
			top.content.editor.edbody.submitForm();
			break;
		case "delete_workflow":
			if (!WE().util.hasPerm("DELETE_WORKFLOW")) {
				top.we_showMessage(WE().consts.g_l.main.no_perms, WE().consts.message.WE_MESSAGE_ERROR, this);
			} else {
				if (top.content.editor.edbody.loaded) {
					if (!confirm(WE().consts.g_l.workflow.view.delete_question))
						return;
				} else {
					top.we_showMessage(WE().consts.g_l.workflow.view.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, this);
				}

				top.content.editor.edbody.document.we_form.wcmd.value = args[0];
				top.content.editor.edbody.submitForm();
			}
			break;
		case "save_workflow":
			if (!WE().util.hasPerm("EDIT_WORKFLOW") && !WE().util.hasPerm("NEW_WORKFLOW")) {
				top.we_showMessage(WE().consts.g_l.main.no_perms, WE().consts.message.WE_MESSAGE_ERROR, this);
			} else {
				if (top.content.editor.edbody.loaded) {
					top.content.editor.edbody.setStatus(top.content.editor.edfooter.document.we_form.status_workflow.value);
					chk = top.content.editor.edbody.checkData();
					if (!chk) {
						return;
					}
					num = top.content.editor.edbody.getNumOfDocs();
					if (num > 0) {
						if (!confirm(WE().consts.g_l.workflow.view.save_question)) {
							return;
						}
					}
				} else {
					top.we_showMessage(WE().consts.g_l.workflow.view.nothing_to_save, WE().consts.message.WE_MESSAGE_ERROR, this);
				}
				top.content.editor.edbody.document.we_form.wcmd.value = args[0];
				top.content.editor.edbody.submitForm();
				top.content.usetHot();
			}
			break;
		case "workflow_edit":
		case "show_document":
			top.content.editor.edbody.document.we_form.wcmd.value = args[0];
			top.content.editor.edbody.document.we_form.wid.value = args[1];
			top.content.editor.edbody.submitForm();
			break;
			/*
			 case "reload_workflow":
			 top.content.tree.location.reload(true);
			 break;
			 */
		case "empty_log":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=workflow&pnt=qlog", "log_question", -1, -1, 360, 230, true, false, true);
			break;
		default:
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));

	}
}