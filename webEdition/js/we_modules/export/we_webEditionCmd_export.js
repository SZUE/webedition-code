/* global WE, we_cmd_modules */

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
we_cmd_modules.export = function (args, url, caller) {
	switch (args[0]) {
		case "export_edit":
		case "export_edit_ifthere":
			new (WE().util.jsWindow)(caller, url, "edit_module", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, true, true, true);
			return true;
		case "new_export":
		case "new_export_group":
		case "save_export":
		case "delete_export":
		case "exit_export":
		case "start_export":
			WE().layout.pushCmdToModule(args);
			return true;
		case "unlock"://FIXME:???
			window.we_repl(window.load, url);
			return true;
	}
	return false;
};