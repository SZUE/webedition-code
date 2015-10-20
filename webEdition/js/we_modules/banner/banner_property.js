/* global WE, top */

/**
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

var loaded;

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	var url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?";
	for (var i = 0; i < arguments.length; i++) {
		url += "we_cmd[" + i + "]=" + encodeURIComponent(arguments[i]);
		if (i < (arguments.length - 1)) {
			url += "&";
		}
	}
	switch (arguments[0]) {
		case "we_selector_file":
			new (WE().util.jsWindow)(window, url, "we_selector", -1, -1, WE().consts.size.windowSelect.width, WE().consts.size.windowSelect.height, true, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(window, url, "we_catselector", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "we_selector_image":
		case "we_selector_document":
			new (WE().util.jsWindow)(window, url, "we_docselector", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "we_selector_directory":
			new (WE().util.jsWindow)(window, url, "we_dirselector", -1, -1, WE().consts.size.windowDirSelect.width, WE().consts.size.windowDirSelect.height, true, true, true, true);
			break;
		case "we_banner_dirSelector":
			new (WE().util.jsWindow)(window, url, "we_bannerselector", -1, -1, 600, 350, true, true, true);
			break;
		case "switchPage":
			document.we_form.ncmd.value = arguments[0];
			document.we_form.page.value = arguments[1];
			submitForm();
			break;
		case "add_cat":
		case "del_cat":
		case "del_all_cats":
		case "add_file":
		case "del_file":
		case "del_all_files":
		case "add_folder":
		case "del_folder":
		case "del_customer":
		case "del_all_customers":
		case "del_all_folders":
		case "add_customer":
			document.we_form.ncmd.value = arguments[0];
			document.we_form.ncmdvalue.value = arguments[1];
			submitForm();
			break;
		case "delete_stat":
			if (confirm(WE().consts.g_l.banner.view.deleteStatConfirm)) {
				document.we_form.ncmd.value = arguments[0];
				submitForm();
			}
			break;
		default:
			var args = [];
			for (i = 0; i < arguments.length; i++) {
				args.push(arguments[i]);
			}
			top.content.we_cmd.apply(this, args);

	}
}

function submitForm() {
	var f = self.document.we_form;
	f.target = (arguments[0] ? arguments[0] : "edbody");
	f.action = (arguments[1] ? arguments[1] : WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=banner");
	f.method = (arguments[2] ? arguments[2] : "post");
	f.submit();
}
function checkData() {

	return true;
}

self.focus();