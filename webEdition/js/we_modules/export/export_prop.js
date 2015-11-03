/* global top, WE */

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 10569 $
 * $Author: mokraemer $
 * $Date: 2015-10-12 19:56:23 +0200 (Mo, 12. Okt 2015) $
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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
var loaded = false;

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	var args = [];
	var url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?";
	for (var i = 0; i < arguments.length; i++) {
		url += "we_cmd[]=" + encodeURI(arguments[i]);
		args.push(arguments[i]);
		if (i < (arguments.length - 1)) {
			url += "&";
		}
	}

	switch (arguments[0]) {
		case "switchPage":
			document.we_form.cmd.value = arguments[0];
			document.we_form.tabnr.value = arguments[1];
			submitForm();
			break;
		case "we_export_dirSelector":
			url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?we_cmd[]=we_export_dirSelector&";
			for (var i = 0; i < args.length; i++) {
				url += "we_cmd[]=" + encodeURI(args[i]);
				if (i < (args.length - 1)) {
					url += "&";
				}
			}
			new (WE().util.jsWindow)(window, url, "we_exportselector", -1, -1, 600, 350, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(window, url, "we_catselector", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "we_selector_directory":
			new (WE().util.jsWindow)(window, url, "we_selector", -1, -1, WE().consts.size.windowSelect.width, WE().consts.size.windowSelect.height, true, true, true, true);
			break;
		case "add_cat":
		case "del_cat":
		case "del_all_cats":
			document.we_form.cmd.value = arguments[0];
			top.content.editor.edbody.document.we_form.pnt.value = "edbody";
			document.we_form.tabnr.value = top.content.activ_tab;
			document.we_form.cat.value = arguments[1];
			submitForm();
			break;
		default:
			top.content.we_cmd.apply(this, args);
	}
}

function submitForm() {
	var f = self.document.we_form;
	f.target = (arguments[0] ? arguments[0] : "edbody");
	f.action = (arguments[1] ? arguments[1] : data.frameset);
	f.method = (arguments[2] ? arguments[2] : "post");
	f.submit();
}
