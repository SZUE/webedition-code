/* global WE */

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

function add_thumbnails() {
	sel = document.getElementById("Thumbnails");
	var thumbs = "";
	for (var i = 0; i < sel.options.length; i++) {
		if (sel.options[i].selected) {
			thumbs += (sel.options[i].value + ",");
		}
	}

	if (thumbs.length) {
		thumbs = "," + thumbs;
		opener.we_cmd("do_add_thumbnails", thumbs);
	}

	self.close();

}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "editThumbs":
			new (WE().util.jsWindow)(this, url, "thumbnails", -1, -1, 500, 550, true, true, true);
			break;
		default:
			parent.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}

function select_thumbnails(sel) {
	var thumbs = [];

	for (var i = 0; i < sel.options.length; i++) {
		if (sel.options[i].selected) {
			thumbs.push(sel.options[i].value);
		}
	}

	WE().layout.button.switch_button_state(document, "add", (thumbs.length ? "enabled" : "disabled"));

	self.showthumbs.location = WE().consts.dirs.WEBEDITION_DIR + "showThumb.php?u=" + Math.random() + "&t=" + transaction + "&id=" + encodeURI(thumbs.join(","));

}