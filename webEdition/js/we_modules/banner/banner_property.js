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
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "we_selector_file":
			new (WE().util.jsWindow)(this, url, "we_selector", -1, -1, WE().consts.size.windowSelect.width, WE().consts.size.windowSelect.height, true, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(this, url, "we_catselector", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "we_selector_image":
		case "we_selector_document":
			new (WE().util.jsWindow)(this, url, "we_docselector", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "we_selector_directory":
			new (WE().util.jsWindow)(this, url, "we_dirselector", -1, -1, WE().consts.size.windowDirSelect.width, WE().consts.size.windowDirSelect.height, true, true, true, true);
			break;
		case "we_banner_dirSelector":
			new (WE().util.jsWindow)(this, url, "we_bannerselector", -1, -1, 600, 350, true, true, true);
			break;
		case "switchPage":
			document.we_form.ncmd.value = args[0];
			document.we_form.page.value = args[1];
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
			document.we_form.ncmd.value = args[0];
			document.we_form.ncmdvalue.value = args[1];
			submitForm();
			break;
		case "delete_stat":
			if (confirm(WE().consts.g_l.banner.view.deleteStatConfirm)) {
				document.we_form.ncmd.value = args[0];
				submitForm();
			}
			break;
		default:
			top.content.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}

function submitForm(target, action, method) {
	var f = self.document.we_form;
	f.target = (target ? target : "edbody");
	f.action = (action ? action : WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=banner");
	f.method = (method ? method : "post");
	f.submit();
}

function checkData() {
	return true;
}