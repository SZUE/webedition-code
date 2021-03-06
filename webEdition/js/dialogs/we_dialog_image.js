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


function imageChanged(wasThumbnailChange) {
	if (wasThumbnailChange !== null && wasThumbnailChange) {
		document.we_form.wasThumbnailChange.value = '1';
	}
	if (top.opener.tinyMCECallRegisterDialog) {
		top.opener.tinyMCECallRegisterDialog(null, 'block');
	}
	//document.we_form.target = "we_weImageDialog_edit_area";
	document.we_form.target = 'we_we_dialog_image_cmd_frame';//TODO: send form to iFrame cmd for and for not reloading whole editor
	document.we_form.we_what.value = 'cmd';
	document.we_form['we_cmd[0]'].value = 'update_editor';
	document.we_form.imgChangedCmd.value = '1';
	document.we_form.submit();
}

function checkWidthHeight(field) {
	var ratioCheckBox = document.getElementById('check_we_dialog_args[ratio]'),
		v = parseInt(field.value);

	if (ratioCheckBox.checked) {
		if (field.value.indexOf('%') == -1) {
			/*
			ratiow = ratiow ? ratiow : (field.form.elements.tinyMCEInitRatioW.value ? field.form.elements.tinyMCEInitRatioW.value : 0);
			ratioh = ratioh ? ratioh : (field.form.elements.tinyMCEInitRatioH.value ? field.form.elements.tinyMCEInitRatioH.value : 0);
			*/

			ratiow = (parseInt(field.form.elements['we_dialog_args[rendered_width]'].value) / parseInt(field.form.elements['we_dialog_args[rendered_height]'].value));
			ratioh = (parseInt(field.form.elements['we_dialog_args[rendered_height]'].value) / parseInt(field.form.elements['we_dialog_args[rendered_width]'].value));

			//if ((field.form.elements['we_dialog_args[width]'].value && field.form.elements['we_dialog_args[height]'].value) || (!field.form.elements['we_dialog_args[width]'].value && !field.form.elements['we_dialog_args[height]'].value)) {
				if(field.name === 'we_dialog_args[height]'){
					field.form.elements['we_dialog_args[width]'].value = v ? Math.round(v * ratiow) : '';
				} else {
					field.form.elements['we_dialog_args[height]'].value = v ? Math.round(v * ratioh) : '';
				}
				field.value = v ? v : '';
			//}
		} else {
			ratioCheckBox.checked = false;
		}
	} else {
		field.value = v ? v : '';
	}
	return true;
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case 'we_selector_document':
		case 'we_selector_image':
		case 'we_selector_directory':
			new (WE().util.jsWindow)(this, url, 'we_fileselector', -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case 'browse_server':
			new (WE().util.jsWindow)(this, url, 'browse_server', -1, -1, 840, 400, true, false, true);
			break;
		case "we_fileupload_editor":
			new (WE().util.jsWindow)(this, url, "we_fileupload_editor", -1, -1, 500, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		default :
			top.opener.we_cmd.apply(this, Array.prototype.slice.call(arguments));
			break;
	}
}
