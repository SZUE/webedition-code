/* global WE */

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 13374 $
 * $Author: mokraemer $
 * $Date: 2017-02-15 19:33:39 +0100 (Mi, 15. Feb 2017) $
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

var filter = WE().util.getDynamicVar(document, 'loadVarFilter', 'data-filter');

function checkForOpenChilds() {
	var
					_openChilds = [],
					_usedEditors = WE().layout.weEditorFrameController.getEditorsInUse();

	for (var frameId in _usedEditors) {

		// table muss FILE_TABLE sein
		if (_usedEditors[frameId].getEditorEditorTable() === filter.table) {
			if (filter.allChilds[_usedEditors[frameId].getEditorDocumentId()] && filter.allChilds[_usedEditors[frameId].getEditorDocumentId()] === _usedEditors[frameId].getEditorContentType()) {
				_openChilds.push(frameId);
			}
		}
	}

	if (_openChilds.length) {
		if (window.confirm(filter.question)) {
			// close all
			for (var i = 0; i < _openChilds.length; i++) {
				_usedEditors[_openChilds[i]].setEditorIsHot(false);
				WE().layout.weEditorFrameController.closeDocument(_openChilds[i]);
			}
		} else {
			window.close();
			return;
		}

	}
	document.getElementById("iframeCopyWeDocumentCustomerFilter").src = filter.redirect;
}