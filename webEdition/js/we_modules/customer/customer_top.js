/*
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

var get_focus = 1;
var activ_tab = 0;
var hot = 0;
var scrollToVal = 0;

function setHot() {
	hot = "1";
}

function usetHot() {
	hot = "0";
}

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
	if (hot == 1 && arguments[0] !== "save_customer") {
		if (confirm(g_l.save_changed_customer)) {
			arguments[0] = "save_customer";
		} else {
			top.content.usetHot();
		}
	}
	switch (arguments[0]) {
		case "exit_customer":
			if (hot != "1") {
				top.opener.top.we_cmd("exit_modules");
			}
			break;
		case "new_customer":
			if (topFrame.editor.edbody.loaded) {
				topFrame.editor.edbody.document.we_form.cmd.value = arguments[0];
				topFrame.editor.edbody.document.we_form.cmdid.value = arguments[1];
				topFrame.editor.edbody.submitForm();
			} else {
				setTimeout(function () {
					we_cmd("new_customer");
				}, 10);
			}
			break;

		case "delete_customer":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				return;
			}
			if (!perms.DELETE_CUSTOMER) {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_WARNING, window);
				return;
			}

			if (topFrame.editor.edbody.loaded) {
				if (confirm(g_l.delete_alert)) {
					topFrame.editor.edbody.document.we_form.cmd.value = arguments[0];
					topFrame.editor.edbody.submitForm();
				}
			} else {
				top.we_showMessage(g_l.nothing_to_delete, WE_MESSAGE_WARNING, window);
			}


			break;

		case "save_customer":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				return;
			}
			if (!perms.EDIT_CUSTOMER && !perms.NEW_CUSTOMER) {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_WARNING, window);
				return;
			}

			if (topFrame.editor.edbody.loaded) {
				topFrame.editor.edbody.document.we_form.cmd.value = arguments[0];
				topFrame.editor.edbody.submitForm();
			} else {
				top.we_showMessage(g_l.nothing_to_save, WE_MESSAGE_WARNING, window);
			}

			top.content.usetHot();
			break;

		case "customer_edit":
			topFrame.editor.edbody.document.we_form.cmd.value = arguments[0];
			topFrame.editor.edbody.document.we_form.cmdid.value = arguments[1];
			topFrame.editor.edbody.submitForm();
			break;
		case "show_admin":
		case "show_sort_admin":
			if (topFrame.editor.edbody.document.we_form.cmd.value === "home") {
				topFrame.editor.edbody.document.we_form.home.value = 1;
			}
			topFrame.editor.edbody.document.we_form.cmd.value = arguments[0];
			topFrame.editor.edbody.document.we_form.cmdid.value = arguments[1];
			topFrame.editor.edbody.submitForm();
			break;
		case "show_search":
		case "show_customer_settings":
		case "export_customer":
		case "import_customer":
			topFrame.editor.edbody.we_cmd(arguments[0]);
			break;
		case "load":
			topFrame.cmd.location = frameUrl + "?pnt=cmd&pid=" + arguments[1] + "&offset=" + arguments[2] + "&sort=" + arguments[3];
			break;
		default:
			var args = [];
			for (var i = 0; i < arguments.length; i++) {
				args.push(arguments[i]);
			}
			top.opener.top.we_cmd.apply(this, args);
	}
}