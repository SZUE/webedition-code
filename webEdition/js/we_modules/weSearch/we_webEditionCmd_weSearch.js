/* global we_cmd_modules, WE */

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
'use strict';
we_cmd_modules.weSearch = function (args, url, caller) {
	switch (args[0]) {
		case "weSearch_edit":
			new (WE().util.jsWindow)(caller, url, "tool_window_weSearch", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, true, true, true);
			break;
		case "weSearch_new_forDocuments":
		case "weSearch_new_forTemplates":
		case "weSearch_new_forObjects":
		case "weSearch_new_advSearch":
		case "weSearch_delete":
		case "weSearch_save":
		case "weSearch_exit":
			var wind = WE().util.jsWindow.prototype.find('tool_window_weSearch');
			if (wind) {
				wind.content.we_cmd(args[0]);
				wind.focus();
			}
			break;
		default:
			return false;
	}
	return true;
};