/* global WE, top*/

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 13205 $
 * $Author: mokraemer $
 * $Date: 2017-01-01 23:58:18 +0100 (So, 01. Jan 2017) $
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

function we_cmd() {
	/*jshint validthis:true */
	var caller = (this && this.window === this ? this : window);
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
//	var url = WE().util.getWe_cmdArgsUrl(args);
	switch (args[0]) {
		case "reloadFooter":
			WE().layout.weEditorFrameController.getActiveDocumentReference().frames.editFooter.location.reload();

			break;
		default:
			if (opener.top.we_cmd) {
				opener.top.we_cmd.apply(caller, Array.prototype.slice.call(arguments));
			}
	}
}
