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

function shopCloseWindow(args) {
	var wind = WE().util.jsWindow.prototype.find('edit_module');
	if (wind && wind.content) {
		wind.content.we_cmd(args[0]);
		wind.focus();
		return true;
	}
	return false;
}

function we_cmd_shop(args, url) {
	switch (args[0]) {
		case "edit_settings_shop":
			new (WE().util.jsWindow)(this, WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_pref.php", "shoppref", -1, -1, 470, 600, true, false, true);
			break;
		case "shop_edit_ifthere":
		case "shop_edit":
			new (WE().util.jsWindow)(this, url, "edit_module", -1, -1, 970, 760, true, true, true, true);
			break;
		case "pref_shop":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_pref.php";
			new (WE().util.jsWindow)(this, url, "shoppref", -1, -1, 470, 600, true, true, true, false);
			break;
		case "edit_shop_status":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_status.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_status", -1, -1, 700, 580, true, true, true, false);
			break;
		case "edit_shop_vat_country":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_vat_country.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_vat_country", -1, -1, 700, 780, true, true, true, false);
			break;
		case "edit_shop_categories":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_categories.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_categories", -1, -1, 740, 650, true, false, true, false);
			break;
		case "edit_shop_vats":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_vats.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_vats", -1, -1, 650, 650, true, false, true, false);
			break;
		case "edit_shop_shipping":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_shipping.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_shipping", -1, -1, 700, 600, true, false, true, false);
			break;
		case "payment_val":
			shopCloseWindow(args);
			url = WE().consts.dirs.WE_SHOP_MODULE_DIR + "edit_shop_payment.php";
			new (WE().util.jsWindow)(this, url, "edit_shop_payment", -1, -1, 520, 720, true, false, true, false);
			break;
		case 'yearCmd'://pseudocommand
		case "revenue_view":
		case "new_article":
		case "delete_shop":
			var wind = WE().util.jsWindow.prototype.find('edit_module');
			if (wind) {
				wind.content.we_cmd.apply(this, args);
				wind.focus();
			}
			break;
		case "exit_shop":
			top.opener.top.we_cmd("exit_modules");
			break;
		default:
			return false;
	}
	return true;
}