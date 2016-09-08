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
class we_newsletter_dirSelector extends we_selector_directory{
	var $fields = "ID,ParentID,Text,Path,IsFolder";

	function __construct($id, $JSIDName = "", $JSTextName = "", $JSCommand = "", $order = "", $sessionID = "", $we_editDirID = "", $FolderText = "", $rootDirID = 0, $multiple = 0){
		$table = NEWSLETTER_TABLE;
		parent::__construct($id, $table, $JSIDName, $JSTextName, $JSCommand, $order, 0, $we_editDirID, $FolderText, $rootDirID, $multiple);
	}

	protected function printCreateFolderHTML(){
		$this->FolderText = rawurldecode($this->FolderText);
		$txt = $this->FolderText;
		$folder = new we_folder();
		$folder->we_new($this->table, $this->dir, $txt);
		if(!($msg = $folder->checkFieldsOnSave())){
			$folder->we_save();
		}

		$weCmd = new we_base_jsCmd();
		$weCmd->addCmd('clearEntries');

		$js = '
top.fileSelect.data.makeNewFolder=false;' .
			($msg ?
				we_message_reporting::getShowMessageCall($msg, we_message_reporting::WE_MESSAGE_ERROR) :
				'var ref;
if(top.opener.top.content.makeNewEntry){
	ref = top.opener.top.content;
	ref.treeData.makeNewEntry({id:' . $folder->ID . ',parentid:' . $folder->ParentID . ',text:"' . $txt . '",open:1,contenttype:"' . $folder->ContentType . '",table:"' . $this->table . '",published:1});
}
' .
				($this->canSelectDir ?
					'top.fileSelect.data.currentPath = "' . $folder->Path . '";
top.fileSelect.data.currentID = "' . $folder->ID . '";
top.document.getElementsByName("fname")[0].value = "' . $folder->Text . '";
' : '')
			) .
			$this->printCmdAddEntriesHTML($weCmd) .
			'top.selectFile(top.fileSelect.data.currentID);';
		$this->setWriteSelectorData($weCmd);
		echo we_html_tools::getHtmlTop('', '', '', $weCmd->getCmds() .
			we_html_element::jsElement($js), we_html_element::htmlBody());
	}

	protected function printDoRenameFolderHTML(){
		$this->FolderText = rawurldecode($this->FolderText);
		$txt = $this->FolderText;
		$folder = new we_folder();
		$folder->initByID($this->we_editDirID, $this->table);
		$folder->Text = $txt;
		$folder->ModDate = time();
		$folder->Filename = $txt;
		$folder->Published = time();
		$folder->Path = $folder->getPath();
		$folder->ModifierID = isset($_SESSION['user']["ID"]) ? $_SESSION['user']["ID"] : "";
		if(!($msg = $folder->checkFieldsOnSave())){
			$folder->we_save();
		}
		$weCmd = new we_base_jsCmd();
		$weCmd->addCmd('clearEntries');
		if($msg){
			$weCmd->addCmd('msg', ['msg' => $msg, 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
		} else {
			$weCmd->addCmd('updateTreeEntry', ['id' => $folder->ID, 'text' => $txt, 'parentid' => $folder->ParentID]);
		}

		$js = ($msg ? '' :
				($this->canSelectDir ?
					'top.fileSelect.data.currentPath = "' . $folder->Path . '";
top.fileSelect.data.currentID = "' . $folder->ID . '";
top.document.getElementsByName("fname")[0].value = "' . $folder->Text . '";
' :
					''
				)
			) .
			'top.fileSelect.data.makeNewFolder=false;' .
			$this->printCmdAddEntriesHTML($weCmd) .
			'top.selectFile(top.fileSelect.data.currentID);';
		$this->setWriteSelectorData($weCmd);
		echo we_html_tools::getHtmlTop('', '', '', $weCmd->getCmds() . we_html_element::jsElement($js), we_html_element::htmlBody());
	}

	protected function query(){
		$this->db->query('SELECT ' . $this->fields . ' FROM ' . $this->db->escape($this->table) . ' WHERE IsFolder=1 AND ParentID=' . intval($this->dir) .
			getWsQueryForSelector(NEWSLETTER_TABLE) .
			($this->order ? (' ORDER BY IsFolder DESC,' . $this->order) : '')
		);
	}

	protected function printCmdAddEntriesHTML(we_base_jsCmd $weCmd){
		$ret = '';
		$entries = [];
		$this->query();
		while($this->db->next_record()){
			$entries[] = [
				$this->db->f("ID"),
				$this->db->f("Text"),
				$this->db->f("IsFolder"),
				$this->db->f("Path")
			];
		}
		$weCmd->addCmd('addEntries', $entries);
		$ret.=' function startFrameset(){' . ($this->userCanMakeNewDir() ?
				'top.enableNewFolderBut();' :
				'top.disableNewFolderBut();') . '}';
		return $ret;
	}

	protected function getFramsetJSFile(){
		return parent::getFramsetJSFile() .
			we_html_element::jsScript(JS_DIR . 'selectors/newsletterdir_selector.js');
	}

	protected function printHeaderHeadlines(){
		return '
<table class="headerLines" style="width:550px;">
<colgroup><col style="width:25px;"/><col style="width:200px;"/><col style="width:300px;"/></colgroup>
	<tr>
		<th class="selector treeIcon"></th>
		<th class="selector"colspan="2"><a href="#" onclick="javascript:top.orderIt(\'Text\');">' . g_l('fileselector', '[filename]') . '</a></th>
	</tr>
</table>';
	}

	protected function userCanSeeDir($showAll = false){
		return true;
	}

	protected function userCanRenameFolder(){
		return permissionhandler::hasPerm('EDIT_NEWSLETTER');
	}

	protected function userCanMakeNewDir(){
		return permissionhandler::hasPerm('NEW_NEWSLETTER');
	}

	protected function userHasRenameFolderPerms(){
		return permissionhandler::hasPerm('EDIT_NEWSLETTER');
	}

	protected function userHasFolderPerms(){
		return permissionhandler::hasPerm('NEW_NEWSLETTER');
	}

	public function printHTML($what = we_selector_file::FRAMESET, $withPreview = true){
		parent::printHTML($what, false);
	}

}
