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


//FIXME: check workflow where this is used & if we need this anymore => used in workflow
function showtip(current, e, text) {
	if (!document.layers) {
		thetitle = text.split('<br>');
		if (thetitle.length > 1) {
			thetitles = "";
			for (i = 0; i < thetitle.length; i++){
				thetitles += thetitle[i] + "\r\n";
			}
			current.title = thetitles;
		} else {
			current.title = text;
		}
	} else {
		document.tooltip.document.write(
						'<layer bgColor="#FFFFE7" style="border:1px ' +
						'solid black; font-size:12px;color:#000000;">' + text + '</layer>');
		document.tooltip.document.close();
		document.tooltip.left = e.pageX + 5;
		document.tooltip.top = e.pageY + 5;
		document.tooltip.visibility = "show";
	}
}

function hidetip() {
	if (document.layers) {
		document.tooltip.visibility = "hidden";
	}
}
