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
we_cmd_modules.newsletter = function (args, url, caller) {
	switch (args[0]) {
		case "edit_settings_newsletter":
			new (WE().util.jsWindow)(caller, WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=newsletter&pnt=newsletter_settings", "newsletter_settings", WE().consts.size.dialog.small, WE().consts.size.dialog.medium, true, false, true);
			break;
		case "newsletter_edit":
			new (WE().util.jsWindow)(caller, url, "edit_module", WE().consts.size.dialog.big, WE().consts.size.dialog.medium, true, true, true, true);
			return true;
		case "save_newsletter":
		case "new_newsletter":
		case "new_newsletter_group":
		case "send_newsletter":
		case "preview_newsletter":
		case "delete_newsletter":
		case "send_test":
		case "domain_check":
		case "test_newsletter":
		case "show_log":
		case "print_lists":
		case "newsletter_settings":
		case "black_list":
		case "search_email":
		case "edit_file":
		case "clear_log":
		case "exit_newsletter":
			WE().layout.pushCmdToModule(args);
			return true;
	}
	return false;
};