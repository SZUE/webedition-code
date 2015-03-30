/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 8893 $
 * $Author: mokraemer $
 * $Date: 2015-01-06 01:52:56 +0100 (Di, 06. Jan 2015) $
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
function toggle(id) {
	var elem = document.getElementById(id);
	if (elem) {
		if (elem.style.display == 'none')
			elem.style.display = 'block';
		else
			elem.style.display = 'none';
	}
}

function setVisible(id, visible) {
	var elem = document.getElementById(id);
	if (elem) {
		if (visible == true)
			elem.style.display = 'block';
		else
			elem.style.display = 'none';
	}
}

function setPresentation(type) {

}

function closeAllSelection() {
	setVisible('dynamic', false);
	setVisible('static', false);
}

function getCsv(bTbl) {
	var iFolderID = _fo.FolderID.value;
	var sFolderPath = _fo.FolderPath.value;
	var iDtOrCls = (bTbl) ? _fo.classID.value : _fo.DocTypeID.value;
	var sCats = '';
	for (var j = 0; j < categories_edit.itemCount; j++) {
		sCats += opener.base64_encode(categories_edit.form.elements[categories_edit.name + '_variant0_' + categories_edit.name + '_item' + j].value);
		if (j < categories_edit.itemCount - 1)
			sCats += ',';
	}
	var sCsv = iFolderID + ',' + sFolderPath + ';' + iDtOrCls + ';' + sCats;
	return sCsv;
}

function getTreeSelected() {
	var sCsvIds = '';
	var iTemsLen = SelectedItems[table].length;
	for (var i = 0; i < iTemsLen; i++) {
		sCsvIds += SelectedItems[table][i];
		if (i < iTemsLen - 1 && SelectedItems[table][i] != undefined && SelectedItems[table][i] != '')
			sCsvIds += ',';
	}
	return sCsvIds;
}

function preview() {
	var sTitle = _fo.title.value;
	var sSel = (_fo.Selection.selectedIndex) ? '1' : '0';
	var sSwitch = (_fo.headerSwitch.selectedIndex) ? '1' : '0';
	var sCsv = (parseInt(sSel)) ? getTreeSelected() : getCsv(parseInt(sSwitch));
	previewPrefs();
	opener.rpc(sSel + sSwitch, (sCsv) ? sCsv : '', '', '', sTitle, _sObjId, _sMdcInc);
}

function exit_close() {
	var sTitle = _fo.elements.title.value;
	var sSel = (_fo.Selection.selectedIndex) ? '1' : '0';
	var sSwitch = (_fo.headerSwitch.selectedIndex) ? '1' : '0';
	var sCsv = (parseInt(sSel)) ? getTreeSelected() : getCsv(parseInt(sSwitch));
	var aInitCsv = _sInitCsv_.split(';');
	var sInitTitle = opener.base64_decode(aInitCsv[0]);
	if ((sInitTitle != '' && sInitTitle != sTitle) || aInitCsv[1] != sSel + sSwitch || aInitCsv[2] != sCsv) {
		opener.rpc(aInitCsv[1], aInitCsv[2], '', '', sInitTitle, _sObjId, _sMdcInc);
	}
	exitPrefs();
	self.close();
}


function we_submit() {
	var bSelection = _fo.Selection.selectedIndex;
	var bSelType = _fo.headerSwitch.selectedIndex;
	_fo.action = dirs.WE_INCLUDES_DIR + 'we_widgets/dlg/mdc.php?we_cmd[0]=' + _sObjId + '&we_cmd[1]=' + opener.base64_encode(_fo.title.value) + ';' +
					(bSelection ? '1' : '0') + (bSelType ? '1' : '0') + ';' + (bSelection ? getTreeSelected() : '');
	_fo.method = 'post';
	_fo.submit();
}