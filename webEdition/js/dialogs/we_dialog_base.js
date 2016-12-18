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

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function weSaveToGlossaryFn() {
	document.we_form.elements.weSaveToGlossary.value = 1;
	document.we_form.submit();
}

function doKeyDown(e) {
	var key = e.keyCode === undefined ? event.keyCode : e.keyCode;

	switch (key) {
		case 27:
			top.close();
			break;
		case 13:
			if (onEnterKey) {
				weDoOk();
			}
			break;
	}
}

function addKeyListener() {
	document.addEventListener("keyup", doKeyDown, true);
}

function openExtSource(argName) {
	if (argName && this.document.we_form.elements['we_dialog_args[' + argName + ']']) {
		var val = this.document.we_form.elements['we_dialog_args[' + argName + ']'].value;
		if (val && val !== '" . we_base_link::EMPTY_EXT . "') {
			window.open(val);
		}
	}
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "we_selector_document":
		case "we_selector_image":
			new (WE().util.jsWindow)(window, url, "we_fileselector", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(window, url, "we_cateditor", WE().consts.size.dialog.big, WE().consts.size.dialog.small, true, true, true, true);
			break;
		case "browse_server":
			new (WE().util.jsWindow)(window, url, "browse_server", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, false, true);
			break;
		case "edit_new_collection":
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=editNewCollection&we_cmd[1]=" + args[1] + "&we_cmd[2]=" + args[2] + "&fixedpid=" + args[3] + "&fixedremtable=" + args[4] + "&caller=" + args[5];
			new (WE().util.jsWindow)(window, url, "weNewCollection", WE().consts.size.dialog.small, WE().consts.size.dialog.small, true, true, true, true);
			break;
		case "setFocus":
			var elem = document.forms[0].elements[args[1]];
			elem.focus();
			elem.select();
			break;
		default:
			opener.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}