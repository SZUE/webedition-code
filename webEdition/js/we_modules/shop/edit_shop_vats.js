/* global WE */
'use strict';

/**
 * webEdition CMS
 *
 * webEdition CMS
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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
var allVats = WE().util.getDynamicVar(document, 'loadVarEdit_shop_vats', 'data-allVats');

var hot = false;

function doKeyDown(e) {
	switch (e.charCode) {
		case 27:
			top.close();
			break;
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

function setHot() {
	hot = true;
}

function addListeners() {
	for (var i = 1; i < document.we_form.elements.length; i++) {
		document.we_form.elements[i].addEventListener("change", setHot);
	}
}

function closeOnEscape() {
	return true;
}

function changeFormTextField(theId, newVal) {
	/*if (document.getElementById(theId) === null) {
		console.log(theId);
	}*/
	document.getElementById(theId).value = newVal;
}

function changeFormSelect(theId, newVal) {
	var elem = document.getElementById(theId);

	for (var i = 0; i < elem.options.length; i++) {
		if (elem.options[i].value == newVal) {
			elem.selectedIndex = i;
		}
	}
}

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);
	var elem,theVat;

	switch (args[0]) {
		case "save":
			document.we_form.onsaveclose.value = 1;
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_vats");
			break;

		case "save_notclose":
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_vats");
			break;

		case "close":
			if (hot) {
				new (WE().util.jsWindow)(window, WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=exitQuestion", "we_exit_doc_question", WE().consts.size.dialog.smaller, WE().consts.size.dialog.tiny, true, false, true);
			} else {
				window.close();
			}
			break;

		case "edit":
			elem = document.getElementById("editShopVatForm");
			if (elem.style.display === "none") {
				elem.style.display = "";
			}

			if ((theVat = allVats["vat_" + args[1]])) {
				changeFormTextField("weShopVatId", theVat.id);
				changeFormTextField("weShopVatText", theVat.text);
				changeFormTextField("weShopVatVat", theVat.vat);
				changeFormSelect("weShopVatStandard", theVat.standard);
				changeFormSelect("weShopVatCountry", theVat.country);
				changeFormTextField("weShopVatProvince", theVat.province);
				//changeFormTextField("weShopVatTextProvince", theVat.textProvince);
			}
			break;

		case "delete":
			WE().util.showConfirm(window, "", WE().consts.g_l.shop.vat_confirm_delete, ["delete_vats", args[1]]);
			break;
		case "delete_vats":
			document.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_vats&we_cmd[0]=deleteVat&weShopVatId=" + args[1];
			break;
		case 'cancel_notclose':
			elem = document.getElementById("editShopVatForm");
			elem.style.display = "none";
			break;

		case "addVat":
			elem = document.getElementById("editShopVatForm");
			if (elem.style.display == "none") {
				elem.style.display = "";
			}
			if ((theVat = allVats.vat_0)) {
				changeFormTextField("weShopVatId", theVat.id);
				changeFormTextField("weShopVatText", theVat.text);
				changeFormTextField("weShopVatVat", theVat.vat);
				changeFormSelect("weShopVatStandard", theVat.standard);
				changeFormSelect("weShopVatCountry", theVat.country);
				changeFormTextField("weShopVatProvince", theVat.province);
				//changeFormTextField("weShopVatTextProvince", theVat.textProvince);
			}
			break;
		default :
			break;
	}
}
