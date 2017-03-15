/* global WE, top */

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
'use strict';
var transaction;
WE().util.loadConsts(document, "g_l.messaging");

function we_cmd() {
	/*jshint validthis:true */
	var caller = (this && this.window === this ? this : window);
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "reloadMsgContent":
			top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + 'we_showMod.php?mod=messaging&pnt=cmd' + args[1].query;
			top.content.we_cmd('messaging_start_view', '', args[1].table);
			break;
		case 'setTrans':
			transaction = args[1];
			break;
		case 'updateViewClass':
			if (top.content.viewclass !== args[1]) {
				top.content.set_frames(args[1]);
			}
			break;
		case "new_msg":
			new (WE().util.jsWindow)(window, WE().consts.dirs.WE_MESSAGING_MODULE_DIR + "messaging_newmessage.php?we_transaction=" + transaction + "&mode=" + args[1], "messaging_new_message", WE().consts.size.dialog.medium, WE().consts.size.dialog.small, true, false, true, false);
			break;
		case "upd_tdo":
			new (WE().util.jsWindow)(window, WE().consts.dirs.WE_MESSAGING_MODULE_DIR + "todo_edit_todo.php?we_transaction=" + transaction + "&mode=" + args[1], "messaging_new_todo", WE().consts.size.dialog.medium, WE().consts.size.dialog.small, true, false, true, false);
			break;
		case "reset_right_v":
			top.content.editor.edbody.entries_selected = [];
			top.content.editor.edbody.messaging_messages_overview.location = WE().consts.dirs.WE_MESSAGING_MODULE_DIR + 'messaging_show_folder_content.php?we_transaction=' + transaction;
			top.content.editor.edbody.messaging_msg_view.location = "about:blank";
			break;
		default:
			window.parent.we_cmd.apply(caller, Array.prototype.slice.call(arguments));
	}
}

function we_submitForm(target, url) {
	var f = document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}

	var sel = "";
	for (var i = 1; i <= top.treeData.len; i++) {
		if (top.treeData[i].checked) {
			sel += (top.treeData[i].name + ",");
		}
	}
	if (!sel) {
		top.we_showMessage(WE().consts.g_l.main.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, window);
		return;
	}
	sel = sel.substring(0, sel.length - 1);
	f.sel.value = sel;
	f.target = target;
	f.action = url;
	f.method = "post";
	f.submit();
	return true;
}

function do_delete() {
	document.we_form.folders.value = top.content.entries_selected.join(",");
	document.we_form.submit();
}

function save() {
	document.edit_folder.submit();
}

var rcpt_sel = [];

function update_rcpts() {
	var rcpt_str = "";

	for (var i = 0; i < rcpt_sel.length; i++) {
		rcpt_str += rcpt_sel[i][2];
		if (i != rcpt_sel.length - 1) {
			rcpt_str += ", ";
		}
	}

	document.compose_form.mn_recipients.value = rcpt_str;
}

function do_send() {
	var rcpt_s = encodeURI(document.compose_form.mn_recipients.value);
	document.compose_form.rcpts_string.value = rcpt_s;
	document.compose_form.submit();
}

function save_settings() {
	document.search_adv.submit();
}

function selectRecipient() {
	new (WE().util.jsWindow)(window, WE().consts.dirs.WE_MESSAGING_MODULE_DIR + "messaging_usel.php?we_transaction=" + transaction + "&rs=" + encodeURI(document.compose_form.mn_recipients.value), "messaging_usel", WE().consts.size.dialog.small, WE().consts.size.dialog.smaller, true, false, true, false);
	//	    opener.top.add_win(msg_usel);
}

function doSearch() {
	top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=messaging&we_transaction=" + transaction + "&pnt=cmd&mcmd=search_messages&searchterm=" + document.we_messaging_search.messaging_search_keyword.value;
}

function launchAdvanced() {
	new (WE().util.jsWindow)(window, WE().consts.dirs.WE_MESSAGING_MODULE_DIR + "messaging_search_advanced.php?we_transaction=" + transaction, "messaging_search_advanced", WE().consts.size.dialog.tiny, WE().consts.size.dialog.tiny, true, false, true, false);
}

function clearSearch() {
	document.we_messaging_search.messaging_search_keyword.value = "";
	doSearch();
}

function doSort(sortitem) {
	var entrstr = "";
	top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=messaging&pnt=cmd&mcmd=show_folder_content&sort=" + sortitem + entrstr + "&we_transaction=" + transaction;
}