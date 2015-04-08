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
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}


function we_cmd() {
	var args = "";
	var url = dirs.WEBEDITION_DIR + "we_cmd.php?";
	for (var i = 0; i < arguments.length; i++) {
		url += "we_cmd[" + i + "]=" + encodeURI(arguments[i]);
		if (i < (arguments.length - 1)) {
			url += "&";
		}
	}
	if (hot == 1 && arguments[0] != "save_workflow") {
		var hotConfirmMsg = confirm(g_l.save_changed_workflow);
		if (hotConfirmMsg === true) {
			arguments[0] = "save_workflow";
			top.content.usetHot();
		} else {
			top.content.setHot();
		}
	}
	switch (arguments[0]) {
		case "exit_workflow":
			if (hot != 1) {
				top.opener.top.we_cmd('exit_modules');
			}
			break;
		case "new_workflow":
			top.content.editor.edbody.document.we_form.wcmd.value = arguments[0];
			top.content.editor.edbody.document.we_form.wid.value = arguments[1];
			top.content.editor.edbody.submitForm();
			break;
		case "delete_workflow":
			if (!perms.DELETE_WORKFLOW) {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_ERROR, window);
			} else {
				if (top.content.editor.edbody.loaded) {
					if (!confirm(g_l.delete_question))
						return;
				} else {
					top.we_showMessage(g_l.nothing_to_delete, WE_MESSAGE_ERROR, window);
				}

				top.content.editor.edbody.document.we_form.wcmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
			}
			break;
		case "save_workflow":
			if (!perms.EDIT_WORKFLOW && !perms.NEW_WORKFLOW) {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_ERROR, window);
			} else {
				if (top.content.editor.edbody.loaded) {
					top.content.editor.edbody.setStatus(top.content.editor.edfooter.document.we_form.status_workflow.value);
					chk = top.content.editor.edbody.checkData();
					if (!chk) {
						return;
					}
					num = top.content.editor.edbody.getNumOfDocs();
					if (num > 0) {
						if (!confirm(g_l.save_question)) {
							return;
						}
					}
				} else {
					top.we_showMessage(g_l.nothing_to_save, WE_MESSAGE_ERROR, window);
				}
				top.content.editor.edbody.document.we_form.wcmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
				top.content.usetHot();
			}
			break;
		case "workflow_edit":
		case "show_document":
			top.content.editor.edbody.document.we_form.wcmd.value = arguments[0];
			top.content.editor.edbody.document.we_form.wid.value = arguments[1];
			top.content.editor.edbody.submitForm();
			break;
			/*
			 case "reload_workflow":
			 top.content.tree.location.reload(true);
			 break;
			 */
		case "empty_log":
			new jsWindow(dirs.WE_WORKFLOW_MODULE_DIR + "edit_workflow_frameset.php?pnt=qlog", "log_question", -1, -1, 360, 230, true, false, true);
			break;
		default:
			var args = [];
			for (var i = 0; i < arguments.length; i++) {
				args.push(arguments[i]);
			}
			top.opener.top.we_cmd.apply(this, args);

	}
}