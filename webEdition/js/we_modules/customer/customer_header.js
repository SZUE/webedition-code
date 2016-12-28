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

var aTabs = WE().util.getDynamicVar(document, 'loadVarCustomerHeader', 'data-customerHeader');

function setTab(tab) {
	top.content.activ_tab = tab;
	window.parent.edbody.we_cmd("switchPage", tab);
}

function loaded() {
	weTabs.setFrameSize();
	if (top.content.activ_tab) {
		document.getElementById(aTabs[top.content.activ_tab]).className = "tabActive";
	} else {
		document.getElementById("common").className = "tabActive";
	}
}