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
	var url = WE().util.getWe_cmdArgsUrl(args);

	if (hot === 1 && args[0] !== "save_user") {
		if (confirm(WE().consts.g_l.users.view.save_changed_user)) {
			args[0] = "save_user";
		} else {
			top.content.usetHot();
		}
	}
	switch (args[0]) {
		case "exit_users":
			if (hot !== 1) {
				top.opener.top.we_cmd("exit_modules");
			}
			break;
		case "new_user":
			top.content.editor.edbody.focus();
			if (hot === 1 && top.content.editor.edbody.document.we_form.ucmd) {
				if (confirm(WE().consts.g_l.users.view.save_changed_user)) {
					top.content.editor.edbody.document.we_form.ucmd.value = "save_user";
					top.content.editor.edbody.document.we_form.sd.value = 1;
				} else {
					top.content.usetHot();
					top.content.editor.edbody.document.we_form.ucmd.value = "new_user";
				}
				if (args[1]) {
					top.content.editor.edbody.document.we_form.uid.value = args[1];
				}
				if (args[2]) {
					top.content.editor.edbody.document.we_form.ctype.value = args[2];
				}
				if (args[3]) {
					top.content.editor.edbody.document.we_form.ctable.value = args[3];
				}
				top.content.editor.edbody.we_submitForm("cmd", WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd");
			} else {
				top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=new_user&cgroup=" + cgroup;
			}
			break;
		case "check_user_display":
			top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=check_user_display&uid=" + args[1];
			break;
		case "display_user":
			top.content.editor.edbody.focus();
			if (hot === 1 && top.content.editor.edbody.document.we_form.ucmd) {
				if (confirm(WE().consts.g_l.users.view.save_changed_user)) {
					top.content.editor.edbody.document.we_form.ucmd.value = "save_user";
					top.content.editor.edbody.document.we_form.sd.value = 1;
				} else {
					top.content.usetHot();
					top.content.editor.edbody.document.we_form.ucmd.value = "display_user";
				}
				if (args[1]) {
					top.content.editor.edbody.document.we_form.uid.value = args[1];
				}
				if (args[2]) {
					top.content.editor.edbody.document.we_form.ctype.value = args[2];
				}
				if (args[3]) {
					top.content.editor.edbody.document.we_form.ctable.value = args[3];
				}
				top.content.editor.edbody.we_submitForm("cmd", WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd");
			} else {
				top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=display_user&uid=" + args[1];
			}
			break;
		case "new_group":
			if (hot === 1 && top.content.editor.edbody.document.we_form.ucmd) {
				if (confirm(WE().consts.g_l.users.view.save_changed_user)) {
					top.content.editor.edbody.document.we_form.ucmd.value = "save_user";
					top.content.editor.edbody.document.we_form.sd.value = 1;
				} else {
					top.content.usetHot();
					top.content.editor.edbody.document.we_form.ucmd.value = "new_group";
				}
				if (args[1]) {
					top.content.editor.edbody.document.we_form.uid.value = args[1];
				}
				if (args[2]) {
					top.content.editor.edbody.document.we_form.ctype.value = args[2];
				}
				if (args[3]) {
					top.content.editor.edbody.document.we_form.ctable.value = args[3];
				}
				top.content.editor.edbody.we_submitForm("cmd", WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd");
			} else {
				top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=new_group&cgroup=" + cgroup;
			}
			break;
		case "new_alias":
			if (hot === 1 && top.content.editor.edbody.document.we_form.ucmd) {
				if (confirm(WE().consts.g_l.users.view.save_changed_user)) {
					top.content.editor.edbody.document.we_form.ucmd.value = "save_user";
					top.content.editor.edbody.document.we_form.sd.value = 1;
				} else {
					top.content.usetHot();
					top.content.editor.edbody.document.we_form.ucmd.value = "new_alias";
				}
				if (args[1]) {
					top.content.editor.edbody.document.we_form.uid.value = args[1];
				}
				if (args[2]) {
					top.content.editor.edbody.document.we_form.ctype.value = args[2];
				}
				if (args[3]) {
					top.content.editor.edbody.document.we_form.ctable.value = args[3];
				}
				top.content.editor.edbody.we_submitForm("cmd", WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd");
			} else {
				top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=new_alias&cgroup=" + cgroup;
			}
			break;
		case "save_user":
			if (top.content.editor.edbody.document.we_form) {
				top.content.editor.edbody.document.we_form.ucmd.value = "save_user";
				if (top.content.editor.edbody.we_submitForm("cmd", WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd")) {
					top.content.usetHot();
				}
			}
			break;
		case "delete_user":
			top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=delete_user";
			break;
		case "search":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WE_USERS_MODULE_DIR + "edit_users_sresults.php?kwd=" + args[1], "customer_settings", -1, -1, 580, 400, true, false, true);
			break;
		case "new_organization":
			var orgname = prompt(WE().consts.g_l.users.view.give_org_name, "");
			if (orgname !== null) {
				top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=new_organization&orn=" + orgname;
			}
			break;
		default:
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));

	}
}

function setHot() {
	hot = 1;
}

function usetHot() {
	hot = 0;
}