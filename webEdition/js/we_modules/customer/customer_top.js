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

/* global WE, top */

var get_focus = 1;
var activ_tab = 0;
var hot = 0;
var scrollToVal = 0;

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
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);

	if (hot === 1 && args[0] !== "save_customer") {
		if (confirm(WE().consts.g_l.customer.view.save_changed_customer)) {
			args[0] = "save_customer";
		} else {
			top.content.usetHot();
		}
	}

	switch (args[0]) {
		case "exit_customer":
			if (hot !== 1) {
				top.opener.top.we_cmd("exit_modules");
			}
			break;
		case "new_customer":
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				top.content.editor.edbody.document.we_form.cmdid.value = args[1];
				top.content.editor.edbody.submitForm();
			} else {
				setTimeout(we_cmd, 10, "new_customer");
			}
			break;
		case "delete_customer":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				return;
			}
			if (!WE().util.hasPerm("DELETE_CUSTOMER")) {
				top.we_showMessage(WE().consts.g_l.main.no_perms, WE().consts.message.WE_MESSAGE_WARNING, this);
				return;
			}

			if (top.content.editor.edbody.loaded) {
				if (confirm(WE().consts.g_l.customer.view.delete_alert)) {
					top.content.editor.edbody.document.we_form.cmd.value = args[0];
					top.content.editor.edbody.submitForm();
				}
			} else {
				top.we_showMessage(WE().consts.g_l.customer.view.nothing_to_delete, WE().consts.message.WE_MESSAGE_WARNING, this);
			}
			break;
		case "save_customer":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				return;
			}
			if (!WE().util.hasPerm("EDIT_CUSTOMER") && !WE().util.hasPerm("NEW_CUSTOMER")) {
				top.we_showMessage(WE().consts.g_l.main.no_perms, WE().consts.message.WE_MESSAGE_WARNING, this);
				return;
			}

			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				top.content.editor.edbody.submitForm();
			} else {
				top.we_showMessage(WE().consts.g_l.customer.view.nothing_to_save, WE().consts.message.WE_MESSAGE_WARNING, this);
			}
			top.content.usetHot();
			break;
		case "customer_edit":
			top.content.editor.edbody.document.we_form.cmd.value = args[0];
			top.content.editor.edbody.document.we_form.cmdid.value = args[1];
			top.content.editor.edbody.submitForm();
			break;
		case "show_admin":
		case "show_sort_admin":
			if (top.content.editor.edbody.document.we_form.cmd.value === "home") {
				top.content.editor.edbody.document.we_form.home.value = 1;
			}
			top.content.editor.edbody.document.we_form.cmd.value = args[0];
			top.content.editor.edbody.document.we_form.cmdid.value = args[1];
			top.content.editor.edbody.submitForm();
			break;
		case "show_search":
		case "show_customer_settings":
		case "export_customer":
		case "import_customer":
			top.content.editor.edbody.we_cmd(args[0]);
			break;
		case "load":
			top.content.cmd.location = frameUrl + "&pnt=cmd&pid=" + args[1] + "&offset=" + args[2] + "&sort=" + args[3];
			break;
		default:
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}