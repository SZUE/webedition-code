/* global WE, top */

/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
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

var _EditorFrame = WE().layout.weEditorFrameController.getEditorFrame(parent.name);

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	//var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case 'switch_edit_page':
			_EditorFrame.setEditorEditPageNr(args[1]);
			parent.we_cmd.apply(this, args);
			break;
		default:
			if (top.we_cmd) {
				top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
			}
	}

}

var we_editor_header = {
	timeout: null,
	evtCounter: 0,
	dragEnter: function () {
		++this.evtCounter;
		this.scrollUpEditorContent();
	},
	dragLeave: function () {
		if (--this.evtCounter === 0) {
			clearTimeout(this.timeout);
		}
	},
	scrollUpEditorContent: function () {
		_EditorFrame.getContentEditor().scrollBy(0, -10);
		if (this.evtCounter) {
			this.timeout = setTimeout(we_editor_header.scrollUpEditorContent, 66);
		}
	}
};

