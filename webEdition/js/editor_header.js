/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev: 9713 $
 * $Author: mokraemer $
 * $Date: 2015-04-10 01:33:24 +0200 (Fr, 10. Apr 2015) $
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package    we_ui
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

function we_setPath(path, text, id) {

	// update document-tab
	_EditorFrame.initEditorFrameData({
		"EditorDocumentText": text,
		"EditorDocumentPath": path
	});

	path = path.replace(/</g, '&lt;');
	path = path.replace(/>/g, '&gt;');
	path = '<span style="font-weight:bold;color:#006699">' + path + '</span>';
	if (document.getElementById) {
		var div = document.getElementById('h_path');
		div.innerHTML = path;
		if (id > 0) {
			var div = document.getElementById('h_id');
			div.innerHTML = id;
		}
	} else if (document.all) {
		var div = document.all['h_path'];
		div.innerHTML = path;
		if (id > 0) {
			var div = document.all['h_id'];
			div.innerHTML = id;
		}
	}
}

function we_cmd() {

	var args = [];
	for (var i = 0; i < arguments.length; i++) {
		args.push(arguments[i]);
	}

	switch (arguments[0]) {

		case 'switch_edit_page':
			_EditorFrame.setEditorEditPageNr(arguments[1]);
			parent.we_cmd.apply(this, args);
			break;
	}

}