/* global top, fileSelect, WE */

/**
 * webEdition CMS
 *
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
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

function writeBody(d) {
	switch (fileSelect.options.view) {
		case WE().consts.selectors.VIEW_LIST:
			writeBodyDocument(d);
			break;
		default:
			var body = (fileSelect.data.we_editDirID ?
							'<input type="hidden" name="what" value="' + WE().consts.selectors.DORENAMEFOLDER + '" />' +
							'<input type="hidden" name="we_editDirID" value="' + fileSelect.data.we_editDirID + '" />' :
							'<input type="hidden" name="what" value="' + WE().consts.selectors.CREATEFOLDER + '" />'
							) +
							'<input type="hidden" name="order" value="' + order + '" />' +
							'<input type="hidden" name="rootDirID" value="' + fileSelect.options.rootDirID + '" />' +
							'<input type="hidden" name="table" value="' + fileSelect.options.table + '" />' +
							'<input type="hidden" name="id" value="' + fileSelect.data.currentDir + '" />' +
							(fileSelect.data.makeNewFolder ?
											'<div class="imgDiv">' + WE().util.getTreeIcon('folder', false) + '<br/>' +
											'<input type="hidden" name="we_FolderText" value="' + WE().consts.g_l.fileselector.new_folder_name + '" /><input onMouseDown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="' + WE().consts.g_l.fileselector.new_folder_name + '" class="wetextinput" style="width:100%" />' +
											'</div>' :
											'');
			for (i = 0; i < entries.length; i++) {
				var onclick = ' onclick="return selectorOnClick(event,' + entries[i].ID + ');"';
				var ondblclick = ' onDblClick="return selectorOnDblClick(' + entries[i].ID + ');"';
				body += '<div class="imgDiv ' + ((entries[i].ID == fileSelect.data.currentID) ? "selected" : "") + '" id="line_' + entries[i].ID + '" title="' + entries[i].text + '" ' + ((fileSelect.data.we_editDirID || fileSelect.data.makeNewFolder) ? "" : onclick) + (entries[i].isFolder ? ondblclick : "") + '>' +
								(entries[i].isFolder ? WE().util.getTreeIcon("folder") : '<img src="' + WE().consts.dirs.WEBEDITION_DIR + "thumbnail.php?id=" + entries[i].ID + "&amp;size[width]=150&amp;path=" + entries[i].path + "&amp;extension=.jpg&amp;size[height]=200" + '" class="icon"/>') +
								'<div class="imgText selector">' +
								(fileSelect.data.we_editDirID == entries[i].ID ?
												'<input type="hidden" name="we_FolderText" value="' + entries[i].text + '" /><input onmousedown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="' + entries[i].text + '" class="wetextinput" style="width:100%" />' :
												entries[i].text) +
								'</div></div>';
			}
			d.innerHTML = '<form name="we_form" target="fscmd" method="post" action="' + fileSelect.options.formtarget + '" onsubmit="document.we_form.we_FolderText.value=escape(document.we_form.we_FolderText_tmp.value);return true;">' + body + '</form>';
			if (fileSelect.data.makeNewFolder || fileSelect.data.we_editDirID) {
				top.fsbody.document.we_form.we_FolderText_tmp.focus();
				top.fsbody.document.we_form.we_FolderText_tmp.select();
			}
			break;
	}
}