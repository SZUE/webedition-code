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
'use strict';
var hot = false;

function setHot() {
	hot = true;
}

function addListeners() {
	for (var i = 1; i < document.we_form.elements.length; i++) {
		document.we_form.elements[i].addEventListener("change", setHot());
	}
}

function we_submitForm(url) {
	var f = window.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
	f.action = url;
	f.method = "post";
	f.submit();
	return true;
}

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	/*jshint validthis:true */
	var caller = (this && this.window === this ? this : window);
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);
	if (hot && args[0] === "close") {
		top.we_cmd.apply(caller, ["exit_doc_question"]);
		return;
	}
	switch (args[0]) {
		case "exit_doc_question_no":
			top.content.hot = false;
			/*falls through*/
		case "close":
			window.close();
			break;
		case "exit_doc_question_yes":
		//save the document
		/*falls through*/
		case "module_shop_save":
			document.we_form["we_cmd[0]"].value = "saveShopCatRels";
			document.we_form.onsaveclose.value = 1;
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_categories");
			break;
		case "save_notclose":
			document.we_form["we_cmd[0]"].value = "saveShopCatRels";
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_categories");
			break;
		default:
			top.opener.top.we_cmd.apply(caller, Array.prototype.slice.call(arguments));
	}
}

function we_switch_active_by_id(id) {
	try {
		document.getElementById("destPrincipleRow_" + id).style.display =
			document.getElementById("defCountryRow_" + id).style.display =
			(document.getElementById("check_weShopCatIsActive[" + id + "]").checked) ? "" : "none";

		document.getElementById("countriesRow_" + id).style.display =
			document.getElementById("check_weShopCatIsActive[" + id + "]").checked &&
			(document.getElementById("taxPrinciple_tmp[" + id + "]").value == 1) ? "" : "none";
	} catch (e) {
	}
}

function we_switch_principle_by_id(id, obj, isShopCatsDir) {
	try {
		var active = isShopCatsDir ? true : document.getElementById("check_weShopCatIsActive[" + id + "]").checked;

		document.getElementById("taxPrinciple_tmp[" + id + "]").value = obj.value;
		document.getElementById("countriesRow_" + id).style.display =
			(active && obj.value == 1) ? "" : "none";
	} catch (e) {
	}
}
