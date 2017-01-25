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
we_cmd_modules.voting = function (args, url, caller) {
	switch (args[0]) {
		case "voting_edit":
		case "voting_edit_ifthere":
			new (WE().util.jsWindow)(caller, url, "edit_module", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, true, true, true);
			return true;
		case "new_voting":
		case "new_voting_group":
		case "save_voting":
		case "exit_voting":
		case "delete_voting":
			WE().layout.pushCmdToModule(args);
			return true;
		case "unlock"://FIXME:???
			window.we_repl(window.load, url, args[0]);
			return true;
	}
	return false;
};