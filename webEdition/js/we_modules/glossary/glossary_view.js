/* global WE, top, YAHOO, data */

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

var get_focus = 1;
var activ_tab = 1;
var scrollToVal = 0;
function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}


function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	if (hot === 1 && args[0] !== "save_glossary") {
		if (confirm(WE().consts.g_l.glossary.view.save_changed_glossary)) {
			args[0] = "save_glossary";
		} else {
			top.content.usetHot();
		}
	}
	var exc;

	switch (args[0]) {
		case "exit_glossary":
			if (hot !== 1) {
				top.opener.top.we_cmd("exit_modules");
			}
			break;
		case "new_glossary_acronym":
		case "new_glossary_abbreviation":
		case "new_glossary_foreignword":
		case "new_glossary_link":
		case "new_glossary_textreplacement":
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				if (args[1] !== undefined) {
					top.content.editor.edbody.document.we_form.cmdid.value = args[1];
				}
				top.content.editor.edbody.document.we_form.tabnr.value = 1;
				top.content.editor.edbody.submitForm();
			} else {
				setTimeout(we_cmd.apply, 10, args);
			}
			break;
		case "delete_glossary":
			exc = top.content.editor.edbody.document.we_form.cmdid.value;
			if (exc.substring(exc.length - 10, exc.length) == "_exception") {
				WE().util.showMessage(WE().consts.g_l.glossary.view.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, this);
				break;
			}
			switch (top.content.editor.edbody.document.we_form.cmd.value) {
				case "home":
				case "glossary_view_folder":
				case "glossary_view_type":
				case "glossary_view_exception":
					return;
			}
			if (top.content.editor.edbody.document.we_form.newone.value == 1) {
				WE().util.showMessage(WE().consts.g_l.glossary.view.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, this);
				return;
			}
			if (!WE().util.hasPerm("DELETE_GLOSSARY")) {
				WE().util.showMessage(WE().consts.g_l.glossary.view.no_perms, WE().consts.message.WE_MESSAGE_ERROR, this);
				break;

			}
			if (top.content.editor.edbody.loaded) {
				if (confirm(WE().consts.g_l.glossary.view.delete_alert)) {
					top.content.editor.edbody.document.we_form.cmd.value = args[0];
					top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;
					top.content.editor.edheader.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&home=1&pnt=edheader";
					top.content.editor.edfooter.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&home=1&pnt=edfooter";
					top.content.editor.edbody.submitForm();
				}
			} else {
				WE().util.showMessage(WE().consts.g_l.glossary.view.nothing_to_delete, WE().consts.message.WE_MESSAGE_ERROR, this);
			}
			break;
		case "save_exception":
		case "save_glossary":
			exc = top.content.editor.edbody.document.we_form.cmdid.value;
			if (exc.substring(exc.length - 10, exc.length) == "_exception") {
				args[0] = "save_exception";
			}
			switch (top.content.editor.edbody.document.we_form.cmd.value) {
				case "home":
				case "glossary_view_folder":
				case "glossary_view_type":
					return;
			}
			if (top.content.editor.edbody.loaded) {
				top.content.editor.edbody.document.we_form.cmd.value = args[0];
				top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;
				if (top.makeNewEntryCheck == 1) {
					top.content.editor.edbody.submitForm("cmd");
				} else {
					top.content.editor.edbody.submitForm();
				}
			} else {
				WE().util.showMessage(WE().consts.g_l.glossary.view.nothing_to_save, WE().consts.message.WE_MESSAGE_ERROR, this);
			}
			top.content.usetHot();
			break;
		case "glossary_edit_acronym":
		case "glossary_edit_abbreviation":
		case "glossary_edit_foreignword":
		case "glossary_edit_link":
		case "glossary_edit_textreplacement":
			if (!WE().util.hasPerm("EDIT_GLOSSARY")) {
				WE().util.showMessage(WE().consts.g_l.glossary.view.no_perms, WE().consts.message.WE_MESSAGE_ERROR, this);
				break;
			}
			top.content.hot = 0;
			top.content.editor.edbody.document.we_form.cmd.value = args[0];
			top.content.editor.edbody.document.we_form.cmdid.value = args[1];
			top.content.editor.edbody.document.we_form.tabnr.value = top.content.activ_tab;
			top.content.editor.edbody.submitForm();
			break;
		case "load":
			top.content.cmd.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=cmd&pid=" + args[1] + "&offset=" + args[2] + "&sort=" + args[3];
			break;
		case "home":
			top.content.editor.edbody.parent.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=glossary&pnt=editor";
			/* falls through */
		default:
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}

function populateWorkspaces(type) {
	switch (type) {
		case 'values':
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options.length = 0;
			top.content.editor.edbody.setDisplay("ObjectWorkspaceID", "block");
			return;
		case 'workspace':
			top.content.editor.edbody.setDisplay("ObjectWorkspaceID", "block");
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options.length = 0;
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options[top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options.length] = new Option("/", 0);
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].selectedIndex = 0;
			return;
		case 'noWorkspace':
			top.content.editor.edbody.setDisplay("ObjectWorkspaceID", "none");
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options.length = 0;
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options[top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectWorkspaceID]'].options.length] = new Option("-1", -1);
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectLinkID]'].value = "";
			top.content.editor.edbody.document.we_form.elements['link[Attributes][ObjectLinkPath]'].value = "";
			WE().util.showMessage(WE().consts.g_l.glossary.view.no_workspace, WE().consts.message.WE_MESSAGE_ERROR, this);
			return;
	}

}