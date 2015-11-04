/* global WE, top, YAHOO, data */

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 10522 $
 * $Author: mokraemer $
 * $Date: 2015-10-03 10:55:38 +0200 (Sa, 03. Okt 2015) $
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
	var url = WE().consts.dirs.WEBEDITION_DIR + "we_cmd.php?";
	if(typeof arguments[0] === "object" && arguments[0]["we_cmd[0]"] !== undefined){
		var args = {}, i = 0, tmp = arguments[0];
		url += Object.keys(tmp).map(function(key){args[key] = tmp[key]; args[i++] = tmp[key]; return key + "=" + encodeURIComponent(tmp[key]);}).join("&");
	} else {
		var args = Array.prototype.slice.call(arguments);
		for (var i = 0; i < args.length; i++) {
			url += "we_cmd[" + i + "]=" + encodeURIComponent(args[i]) + (i < (args.length - 1) ? "&" : "");
		}
	}

	switch (args[0]) {
		case "switchPage":
			document.we_form.cmd.value = args[0];
			document.we_form.tabnr.value = args[1];
			submitForm();
			break;
		default:
			top.content.we_cmd.apply(this, arguments);
	}
}
function submitForm() {
	var f = self.document.we_form;
	f.target = (arguments[0] ? arguments[0] : "edbody");
	f.action = (arguments[1] ? arguments[1] : data.frameset);
	f.method = (arguments[2] ? arguments[2] : "post");
	f.submit();
}