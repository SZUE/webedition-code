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

function we_save_docType(doc, url) {
	var acStatus = '';
	invalidAcFields = false;
	if (YAHOO && YAHOO.autocoml) {
		acStatus = YAHOO.autocoml.checkACFields();
	} else {
		we_submitForm(doc, url);
		return;
	}
	acStatusType = typeof acStatus;
	if (countSaveLoop > 10) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		countSaveLoop = 0;
	} else if (acStatusType.toLowerCase() === 'object') {
		if (acStatus.running) {
			countSaveLoop++;
			setTimeout(we_save_docType, 100, doc, url);
		} else if (!acStatus.valid) {
			top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
			countSaveLoop = 0;
		} else {
			countSaveLoop = 0;
			we_submitForm(doc, url);
		}
	} else {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
	}
}

function we_submitForm(target, url) {
	var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
	f.target = target;
	f.action = url;
	f.method = "post";
	f.submit();
	return true;
}

function doUnload() {
	/*WE().util.jsWindow.prototype.closeAll(window);
	 opener.top.dc_win_open = false;*/
}

function disableLangDefault(allnames, allvalues, deselect) {
	var arr = allvalues.split(",");

	for (var v in arr) {
		w = allnames + '[' + arr[v] + ']';
		e = document.getElementById(w);
		e.disabled = false;
	}
	w = allnames + '[' + deselect + ']';
	e = document.getElementById(w);
	e.disabled = true;
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "we_selector_image":
		case "we_selector_document":
		case "we_selector_directory":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(this, url, "we_catselector", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "add_dt_template":
		case "delete_dt_template":
		case "dt_add_cat":
		case "dt_delete_cat":
		case "save_docType":
			we_save_docType(this.name, url);
			break;
		case "newDocType":
			var name = prompt(WE().consts.g_l.doctypeEdit.newDocTypeName, "");
			if (name !== null) {
				if ((name.indexOf("<") !== -1) || (name.indexOf(">") !== -1)) {
					top.we_showMessage(WE().consts.g_l.main.name_nok, WE().consts.message.WE_MESSAGE_ERROR, window);
					return;
				}
				if (name.indexOf("'") !== -1 || name.indexOf('"') !== -1 || name.indexOf(',') !== -1) {
					top.we_showMessage(WE().consts.g_l.doctypeEdit.doctype_hochkomma, WE().consts.message.WE_MESSAGE_ERROR, window);
				} else if (name === "") {
					top.we_showMessage(WE().consts.g_l.doctypeEdit.doctype_empty, WE().consts.message.WE_MESSAGE_ERROR, window);
				} else if (WE().util.in_array(name, docTypeNames)) {
					top.we_showMessage(WE().consts.g_l.doctypeEdit.doctype_exists, WE().consts.message.WE_MESSAGE_ERROR, window);
				} else {
					/*						if (top.opener.top.header) {
					 top.opener.top.header.location.reload();
					 }*/
					this.location = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[0]=newDocType&we_cmd[1]=" + encodeURIComponent(name);
				}
			}
			break;
		case "change_docType":
		case "deleteDocType":
		case "deleteDocTypeok":
			this.location = url;
			break;
		default:
			opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));

	}
}
