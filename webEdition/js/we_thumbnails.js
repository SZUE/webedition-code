/* global top, WE */

/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
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

function init() {
	self.focus();
	changeFormat();
}

function closeOnEscape() {
	return true;
}

function saveOnKeyBoard() {
	we_save();
	return true;
}

function we_save() {
	top.document.getElementById("thumbnails_dialog").style.display = "none";
	top.document.getElementById("thumbnails_save").style.display = "";
	top.document.we_form.save_thumbnails.value = "1";
	top.document.we_form.submit();
}

function changeFormat() {
	if (document.getElementById('Format').value == 'jpg' || document.getElementById('Format').value == 'none') {
		document.getElementById('thumbnail_quality_text_cell').style.display = '';
		document.getElementById('thumbnail_quality_value_cell').style.display = '';
	} else {
		document.getElementById('thumbnail_quality_text_cell').style.display = 'none';
		document.getElementById('thumbnail_quality_value_cell').style.display = 'none';
	}
}

function change_thumbnail(id) {
	self.location = consts.reloadUrl + '&id=' + id;
}

function add_thumbnail() {
	var name = prompt(g_l.thumbnail_new, '');

	if (name === null) {
		return;
	}
	if ((name.indexOf('<') !== -1) || (name.indexOf('>') !== -1)) {
		top.we_showMessage(WE().consts.g_l.main.name_nok, WE().consts.message.WE_MESSAGE_ERROR, window);
		return;
	}

	if (name.indexOf("'") !== -1 || name.indexOf(",") !== -1) {
		top.we_showMessage(g_l.thumbnail_hochkomma, WE().consts.message.WE_MESSAGE_ERROR, window);
	} else if (name === "") {
		top.we_showMessage(g_l.thumbnail_empty, WE().consts.message.WE_MESSAGE_ERROR, window);
	} else if (WE().util.in_array(name, thumbnail_names)) {
		top.we_showMessage(g_l.thumbnail_exists, WE().consts.message.WE_MESSAGE_ERROR, window);
	} else {
		self.location = consts.reloadUrl + '&newthumbnail=' + encodeURI(name);
	}

}
