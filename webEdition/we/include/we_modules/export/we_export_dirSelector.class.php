<?php
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
we_base_moduleInfo::isActive(we_base_moduleInfo::EXPORT);

class we_export_dirSelector extends we_selector_directory{

	function __construct($id, $JSIDName = "", $JSTextName = "", $JSCommand = "", $order = "", $we_editDirID = "", $FolderText = ""){
		parent::__construct($id, EXPORT_TABLE, $JSIDName, $JSTextName, $JSCommand, $order, '', $we_editDirID, $FolderText);
		$this->title = g_l('fileselector', '[exportDirSelector][title]');
		$this->userCanMakeNewFolder = true;
	}

	function printHeaderHeadlines(){
		return '
<table class="headerLines" width="550">
	<tr>
		<th class="selector treeIcon"></th>
		<th class="selector"colspan="2"><a href="#" onclick="javascript:top.orderIt(\'Text\');">' . g_l('export', '[name]') . '</a></th>
	</tr>
	<tr>
		<td width="25">' . we_html_tools::getPixel(25, 1) . '</td>
		<td width="200">' . we_html_tools::getPixel(200, 1) . '</td>
		<td width="300">' . we_html_tools::getPixel(300, 1) . '</td>
	</tr>
</table>';
	}

	protected function printFooterTable(){
		$cancel_button = we_html_button::create_button("cancel", "javascript:top.exit_close();");
		$yes_button = we_html_button::create_button("ok", "javascript:press_ok_button();");
		$buttons = we_html_button::position_yes_no_cancel($yes_button, null, $cancel_button);
		return '
<table class="footer">
	<tr>
		<td></td>
		<td class="defaultfont">
			<b>' . g_l('export', '[name]') . '</b>
		</td>
		<td></td>
		<td class="defaultfont" align="left">' . we_html_tools::htmlTextInput("fname", 24, $this->values["Text"], "", "style=\"width:100%\" readonly=\"readonly\"") . '
		</td>
		<td></td>
	</tr>
	<tr>
		<td width="10">' . we_html_tools::getPixel(10, 5) . '</td>
		<td width="70">' . we_html_tools::getPixel(70, 5) . '</td>
		<td width="10">' . we_html_tools::getPixel(10, 5) . '</td>
		<td>' . we_html_tools::getPixel(5, 5) . '</td>
		<td width="10">' . we_html_tools::getPixel(10, 5) . '</td>
	</tr>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="right">' . $buttons . '</td>
		<td width="10">' . we_html_tools::getPixel(10, 5) . '</td>
	</tr>
</table>';
	}

	protected function printHeaderTableExtraCols(){
		$makefolderState = permissionhandler::hasPerm("NEW_EXPORT");
		return '<td>' .
				we_html_element::jsElement('makefolderState=' . $makefolderState . ';') .
				we_html_button::create_button("image:btn_new_dir", "javascript:if(makefolderState==1){top.drawNewFolder();}", true, 0, 0, "", "", $makefolderState ? false : true) .
				'</td>';
	}

	protected function getWriteBodyHead(){
		return we_html_element::jsElement('
var ctrlpressed=false
var shiftpressed=false
var inputklick=false
var wasdblclick=false
function weonclick(e){
if(makeNewFolder ||  we_editDirID){
	if(!inputklick){
	top.makeNewFolder =  top.we_editDirID=false;
	document.we_form.we_FolderText.value=escape(document.we_form.we_FolderText_tmp.value);
	document.we_form.submit();
	}else{
	inputklick=false;
	}
	}else{
inputklick=false;
if(document.all){
if(e.ctrlKey || e.altKey){ ctrlpressed=true;}
if(e.shiftKey){ shiftpressed=true;}
}else{
if(e.altKey || e.metaKey || e.ctrlKey){ ctrlpressed=true;}
if(e.shiftKey){ shiftpressed=true;}
}
if(top.options.multiple){
if((self.shiftpressed==false) && (self.ctrlpressed==false)){top.unselectAllFiles();}
}else{
top.unselectAllFiles();
}
	}
}');
	}

	protected function printFramesetJSFunctioWriteBody(){
		ob_start();
		?><script type="text/javascript"><!--
			function writeBody(d) {
						var body = (top.we_editDirID ?
										'<input type="hidden" name="what" value="' + top.consts.DORENAMEFOLDER + '" />' +
										'<input type="hidden" name="we_editDirID" value="' + top.we_editDirID + '" />' :
										'<input type="hidden" name="what" value="' + top.consts.CREATEFOLDER + '" />'
										) +
										'<input type="hidden" name="order" value="' + top.order + '" />' +
										'<input type="hidden" name="rootDirID" value="' + top.options.rootDirID + '" />' +
										'<input type="hidden" name="table" value="' + top.options.table + '" />' +
										'<input type="hidden" name="id" value="' + top.currentDir + '" />' +
										'<table class="selector">' +
										(makeNewFolder ?
														'<tr style="background-color:#DFE9F5;">' +
														'<td align="center"><img class="treeIcon" src="' + top.dirs.TREE_ICON_DIR + top.consts.FOLDER_ICON + '"/></td>' +
														'<td><input type="hidden" name="we_FolderText" value="<?php echo g_l('export', '[newFolder]') ?>" /><input onMouseDown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="<?php echo g_l('export', '[newFolder]') ?>"  class="wetextinput" style="width:100%" /></td>' +
														'</tr>' :
														'');
						for (i = 0; i < entries.length; i++) {
							var onclick = ' onclick="weonclick(event);tout=setTimeout(\'if(top.wasdblclick==0){top.doClick(' + entries[i].ID + ',0);}else{top.wasdblclick=0;}\',300);return true"';
							var ondblclick = ' onDblClick="top.wasdblclick=1;clearTimeout(tout);top.doClick(' + entries[i].ID + ',1);return true;"';
							body += '<tr id="line_' + entries[i].ID + '" style="' + ((entries[i].ID == top.currentID && (!makeNewFolder)) ? 'background-color:#DFE9F5;' : '') + 'cursor:pointer;' + ((we_editDirID != entries[i].ID) ? '' : '') + '"' + ((we_editDirID || makeNewFolder) ? '' : onclick) + (entries[i].isFolder ? ondblclick : '') + ' >' +
											'<td class="selector" width="25" align="center">' +
											'<img class="treeIcon" src="' + top.dirs.TREE_ICON_DIR + entries[i].icon + '"/>' +
											'</td>' +
											(we_editDirID == entries[i].ID ?
															'<td class="selector"><input type="hidden" name="we_FolderText" value="' + entries[i].text + '" /><input onMouseDown="self.inputklick=true" name="we_FolderText_tmp" type="text" value="' + entries[i].text + '" class="wetextinput" style="width:100%" />' :
															'<td class="selector filename" style="" ><div class="cutText">' + entries[i].text + "</div>"
															) +
											'</td></tr>';
						}

						d.innerHTML = '<form name="we_form" target="fscmd" action="' + top.options.formtarget + '">' + body + '</table></form>';

						if (makeNewFolder || top.we_editDirID) {
							top.fsbody.document.we_form.we_FolderText_tmp.focus();
							top.fsbody.document.we_form.we_FolderText_tmp.select();
						}
					}
					//-->
		</script>
		<?php
		return ob_get_clean();
	}

	protected function getFramsetJSFile(){
		return parent::getFramsetJSFile() .
				we_html_element::jsScript(JS_DIR . 'selectors/exportdir_selector.js');
	}

	protected function printFramesetJSFunctionQueryString(){
		return we_html_element::jsElement('
		function queryString(what,id,o,we_editDirID){
		if(!o) o=top.order;
		if(!we_editDirID) we_editDirID="";
		return \'' . $_SERVER["SCRIPT_NAME"] . '?what=\'+what+\'&rootDirID=' .
						$this->rootDirID . (isset($this->open_doc) ?
								"&open_doc=" . $this->open_doc : '') .
						'&table=' . $this->table . '&id=\'+id+(o ? ("&order="+o) : "")+(we_editDirID ? ("&we_editDirID="+we_editDirID) : "");
		}');
	}

		protected function printFramesetJSFunctionAddEntries(){
		$ret = '';
		while($this->next_record()){
			$ret.='addEntry(' . $this->f("ID") . ',"' . $this->f("Icon") . '","' . $this->f("Text") . '",' . $this->f("IsFolder") . ',"' . $this->f("Path") . '");' . "\n";
		}
		return we_html_element::jsElement($ret);
	}

	protected function printCmdAddEntriesHTML(){
		$ret = '';
		$this->query();
		while($this->next_record()){
			$ret.= 'top.addEntry(' . $this->f("ID") . ',"' . $this->f("Icon") . '","' . $this->f("Text") . '",' . $this->f("IsFolder") . ',"' . $this->f("Path") . '");' . "\n";
		}
		return $ret;
	}

	function printCreateFolderHTML(){
		we_html_tools::protect();
		echo we_html_tools::getHtmlTop() .
		'<script type="text/javascript"><!--
top.clearEntries();
';
		$this->FolderText = rawurldecode($this->FolderText);
		$txt = rawurldecode(we_base_request::_(we_base_request::STRING, 'we_FolderText_tmp', ''));
		if(!$txt){
			echo we_message_reporting::getShowMessageCall(g_l('export', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
		} else {
			$folder = new we_folder();
			$folder->we_new();
			$folder->setParentID($this->dir);
			$folder->Table = $this->table;
			$folder->Icon = we_base_ContentTypes::FOLDER_ICON;
			$folder->Text = $txt;
			$folder->Path = $folder->getPath();
			if(f('SELECT 1 FROM ' . $this->db->escape($this->table) . " WHERE Path='" . $this->db->escape($folder->Path) . "'", '', $this->db)){
				echo we_message_reporting::getShowMessageCall(g_l('export', '[folder_path_exists]'), we_message_reporting::WE_MESSAGE_ERROR);
			} elseif(we_export_export::filenameNotValid($folder->Text)){
				echo we_message_reporting::getShowMessageCall(g_l('export', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
			} else {
				$folder->we_save();
				echo 'var ref;
if(top.opener.top.content.makeNewEntry){
	ref = top.opener.top.content;
	ref.makeNewEntry("' . we_base_ContentTypes::FOLDER_ICON . '",' . $folder->ID . ',"' . $folder->ParentID . '","' . $txt . '",1,"folder","' . $this->table . '",1);
}
';
				if($this->canSelectDir){
					echo 'top.currentPath = "' . $folder->Path . '";
top.currentID = "' . $folder->ID . '";
top.fsfooter.document.we_form.fname.value = "' . $folder->Text . '";
';
				}
			}
		}


		echo $this->printCmdAddEntriesHTML() .
		$this->printCMDWriteAndFillSelectorHTML() .
		'top.makeNewFolder = 0;
top.selectFile(top.currentID);
//-->
</script>
</head><body></body></html>';
	}

	function query(){
		$this->db->query('SELECT ' . $this->db->escape($this->fields) . ' FROM ' . $this->db->escape($this->table) . ' WHERE IsFolder=1 AND ParentID=' . intval(is_null($this->dir) ? $this->dir : $this->db->affected_rows()));
	}

	function printDoRenameFolderHTML(){
		we_html_tools::protect();
		echo we_html_tools::getHtmlTop() .
		'<script type="text/javascript"><!--
top.clearEntries();
';
		$this->FolderText = rawurldecode($this->FolderText);
		$txt = $this->FolderText;
		if(!$txt){
			echo we_message_reporting::getShowMessageCall(g_l('export', '[folder_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
		} else {
			$folder = new we_folder();
			$folder->initByID($this->we_editDirID, $this->table);
			$folder->Text = $txt;
			$folder->Filename = $txt;
			$folder->Path = $folder->getPath();
			$this->db->query("SELECT ID,Text FROM " . $this->db->escape($this->table) . " WHERE Path='" . $this->db->escape($folder->Path) . "' AND ID != " . intval($this->we_editDirID));
			if($this->db->next_record()){
				$we_responseText = sprintf(g_l('export', '[folder_exists]'), $folder->Path);
				echo we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_ERROR);
			} else {
				if(preg_match('/[%/\\"\']/', $folder->Text)){
					$we_responseText = g_l('export', '[wrongtext]');
					echo we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_ERROR);
				} else {
					if(f("SELECT Text FROM " . $this->db->escape($this->table) . " WHERE ID=" . intval($this->we_editDirID), "Text", $this->db) != $txt){
						$folder->we_save();
						echo 'var ref;
if(top.opener.top.content.updateEntry){
	ref = top.opener.top.content;
	ref.updateEntry(' . $folder->ID . ',"' . $txt . '","' . $folder->ParentID . '");
}
';
						if($this->canSelectDir){
							echo 'top.currentPath = "' . $folder->Path . '";
top.currentID = "' . $folder->ID . '";
top.fsfooter.document.we_form.fname.value = "' . $folder->Text . '";
';
						}
					}
				}
			}
		}

		print
				$this->printCmdAddEntriesHTML() .
				$this->printCMDWriteAndFillSelectorHTML() .
				'top.makeNewFolder = 0;
top.selectFile(top.currentID);
//-->
</script>
</head><body></body></html>';
	}

}
