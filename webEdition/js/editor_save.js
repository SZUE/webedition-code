/*
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
/* global WE, top */

var editorSave = WE().util.getDynamicVar(document, 'loadVarEditor_save', 'data-editorSave');

var _EditorFrame = WE().layout.weEditorFrameController.getEditorFrameByTransaction(editorSave.we_transaction);
var _EditorFrameDocumentRef = _EditorFrame.getDocumentReference();

if (editorSave.we_editor_save) {//called from we_editor_save.inc.php

	if (editorSave.isHot) {
		_EditorFrame.setEditorIsHot(true);
	}

	if (editorSave.wasSaved) {
		// DOC was saved, mark open tabs to reload if necessary
		// was saved - not hot anymore

		_EditorFrame.setEditorIsHot(false);
		if (_EditorFrame.getContentFrame().refreshContentCompare !== undefined) {
			_EditorFrame.getContentFrame().refreshContentCompare();
		}
		if (editorSave.reloadEditors) {
			WE().layout.reloadUsedEditors(editorSave.reloadEditors);
		}
		if (!editorSave.isSEEMode) {
			WE().layout.makeNewDoc(_EditorFrame, editorSave.ContentType, editorSave.docID, editorSave.saveTmpl, !editorSave.isClose);
		}
	}

//FIXME eval
	eval(editorSave.we_JavaScript);

	window.focus();
	var showAlert = false;
	var contentEditor = WE().layout.weEditorFrameController.getVisibleEditorFrame();

// enable navigation box if doc has been published
	if (editorSave.wasPublished) {
		try {
			if (_EditorFrame && _EditorFrame.getEditorIsInUse() && contentEditor) {
				WE().layout.button.switch_button_state(contentEditor.document, "add", "enabled");
			}
		} catch (e) {
		}
	}

	if (editorSave.isSEEMode && !editorSave.showAlert) {
		//	Confirm Box or alert in seeMode
		if (editorSave.isPublished) {
			//	edit include and pulish then close window and reload
			if (isEditInclude) {
				showAlert = true;
			}
			if (editorSave.docHasPreview && editorSave.EditPageNr != WE().consts.tabs.PREVIEW) {
				if (!showAlert) { //	alert or confirm
					if (confirm(editorSave.we_responseText + "\n\n" + WE().consts.g_l.alert.confirm_change_to_preview)) {
						_EditorFrameDocumentRef.frames.editHeader.we_cmd('switch_edit_page', WE().consts.tabs.PREVIEW, editorSave.we_transaction);
					} else {
						_EditorFrameDocumentRef.frames.editHeader.we_cmd('switch_edit_page', editorSave.EditPageNr, editorSave.we_transaction);
					}
				} else {
					top.we_showMessage(editorSave.we_responseText, editorSave.we_responseTextType, window);
				}
			} else {
				//	alert when in preview mode
				top.we_showMessage(editorSave.we_responseText, editorSave.we_responseTextType, window);
				_EditorFrameDocumentRef.frames.editHeader.we_cmd('switch_edit_page', editorSave.EditPageNr, editorSave.we_transaction);
				//FIXME eval
				eval(editorSave.we_cmd5);
			}
			if (editorSave.isPublished) {
				if (isEditInclude) {
					top.we_showMessage(WE().consts.g_l.alert.changed_include, WE().consts.message.WE_MESSAGE_NOTICE, window);
					weWindow.top.we_cmd("reload_editpage");
					weWindow.edit_include.close();
					top.close();
				}
			}
		}
	} else {
		top.we_showMessage(editorSave.we_responseText, editorSave.we_responseTextType, window);
		for (var i = 0; i < editorSave.we_responseJS; i++) {
			top.we_cmd.apply(this, editorSave.we_responseJS[i]);
		}

		for (var i = 0; i < editorSave.we_cmd5; i++) {
			top.we_cmd.apply(this, editorSave.we_cmd5[i]);
		}
	}
} else {//called from we_editor_publish.inc.php
//FIXME eval
	eval(editorSave.we_JavaScript);
	top.we_showMessage(editorSave.we_responseText, editorSave.we_responseTextType, window);
}