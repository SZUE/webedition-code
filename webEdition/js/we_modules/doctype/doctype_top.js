/* global WE, top */

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
function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	/*jshint validthis:true */
	var caller = (this && this.window === this ? this : window);
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url2 = WE().util.getWe_cmdArgsUrl(args, WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edbody&");

	switch (args[0]) {
		case 'unsetHot':
			unsetHot();
			break;
		case "setTab":
			top.content.activ_tab = args[1];
			break;
		case "save_docType":
			top.content.unsetHot();
			top.content.editor.edbody.we_save_docType(document, url2);
			break;
		case "newDocType":
			if (top.content.hot) {
				top.content.editor.edbody.askForSaveOrRefireCmd(args);
				break;
			}
			top.content.editor.edheader.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edheader&we_cmd[0]=" + args[0];
			top.content.editor.edbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edbody&we_cmd[0]=" + args[0];
			top.content.editor.edfooter.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edfooter&&we_cmd[0]=" + args[0];
			break;
		case 'display_doctype':
			top.content.editor.edbody.focus();
			top.content.editor.edheader.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edheader&we_cmd[1]=" + args[1];
			top.content.editor.edbody.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edbody&we_cmd[1]=" + args[1];
			top.content.editor.edfooter.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=doctype&pnt=edfooter&we_cmd[1]=" + args[1];
			break;
			/* fall through */
		case "deleteDocType":
		case "deleteDocTypeok":
			top.content.unsetHot();
			caller.location = url2;
			break;
		case "confirmDeleteDocType":
			WE().util.showConfirm(window, "", WE().util.sprintf(WE().consts.g_l.doctypeEdit.doctype_delete_prompt, args[1]), ["deleteDocTypeok", args[2]]);
			break;
		case 'updateMenu':
			top.opener.top.we_cmd.apply(caller, Array.prototype.slice.call(arguments));
			break;
		default:
			top.we_cmd.apply(caller, Array.prototype.slice.call(arguments));
	}
}