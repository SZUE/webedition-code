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
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
var textareaFocus = false;
if (document.addEventListener) {
	document.addEventListener("keyup",doKeyDown,true);
}else{
	document.onkeydown = doKeyDown;
}

function doKeyDown(e) {
	var key = (e.charCode === undefined ?event.keyCode:e.charCode);
	switch (key) {
		case 27:
			top.close();
			break;
	}
}

function weDoOk() {
	top.opener.tinyMCECallRegisterDialog({},"unregisterDialog");
	WefullscreenDialog.writeback();
	top.close();
}

function IsDigit(e) {
	var key = (e.charCode === undefined ?event.keyCode:e.charCode);
	return (((key >= 48) && (key <= 57)) || (key == 0) || (key == 13));
}

function IsDigitPercent(e) {
	var key = (e.charCode === undefined ?event.keyCode:e.charCode);
	return (((key >= 48) && (key <= 57)) || (key == 37) || (key == 0)  || (key == 13));
}

function doUnload() {
	jsWindowCloseAll();
}
