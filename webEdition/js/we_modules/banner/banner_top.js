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

var hot = 0;

function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}

function setHot() {
	hot = "1";
}

function usetHot() {
	hot = "0";
}


function we_cmd() {
	var args = "";
	var url = dirs.WEBEDITION_DIR + "we_cmd.php?";
	for (var i = 0; i < arguments.length; i++) {
		url += "we_cmd[" + i + "]=" + encodeURIComponent(arguments[i]);
		if (i < (arguments.length - 1)) {
			url += "&";
		}
	}
	if (hot == "1" && arguments[0] != "save_banner") {
		if (confirm(g_l.save_changed_banner)) {
			arguments[0] = "save_banner";
		} else {
			top.content.usetHot();
		}
	}
	switch (arguments[0]) {
		case "exit_banner":
			if (hot != "1") {
				top.opener.top.we_cmd('exit_modules');
			}
			break;
		case "new_banner":
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.ncmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
			} else {
				setTimeout(function () {
					we_cmd("new_banner");
				}, 10);
			}
			break;
		case "new_bannergroup":
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.ncmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
			} else {
				setTimeout(function () {
					we_cmd("new_bannergroup");
				}, 10);
			}
			break;
		case "delete_banner":
			if (perms.DELETE_BANNER) {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_ERROR, window);
			} else {

				if (top.content.editor.edbody.loaded && top.content.editor.edbody.we_is_home === undefined) {
					if (!confirm(g_l.delete_question)) {
						return;
					}
				} else {
					top.we_showMessage(g_l.nothing_to_delete, WE_MESSAGE_WARNING, window);
					return;
				}
				top.content.editor.edbody.document.we_form.ncmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
			}
			break;
		case "save_banner":
			if (perms.EDIT_BANNER || perms.NEW_BANNER) {
				if (top.content.editor.edbody.loaded && top.content.editor.edbody.we_is_home === undefined) {
					if (!top.content.editor.edbody.checkData()) {
						return;
					}
				} else {
					top.we_showMessage(g_l.nothing_to_save, WE_MESSAGE_WARNING, window);
					return;
				}

				top.content.editor.edbody.document.we_form.ncmd.value = arguments[0];
				top.content.editor.edbody.submitForm();
			} else {
				top.we_showMessage(g_l.no_perms, WE_MESSAGE_ERROR, window);

			}
			top.content.usetHot();
			break;
		case "banner_edit":
			top.content.editor.edbody.document.we_form.ncmd.value = arguments[0];
			top.content.editor.edbody.document.we_form.bid.value = arguments[1];
			top.content.editor.edbody.submitForm();
			break;
		default:
			var args = [];
			for (var i = 0; i < arguments.length; i++) {
				args.push(arguments[i]);
			}
			top.opener.top.we_cmd.apply(this, args);

	}
}