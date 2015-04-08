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
class we_navigation_dirSelector extends we_selector_directory{

	function __construct($id, $JSIDName = '', $JSTextName = '', $JSCommand = '', $order = '', $we_editDirID = '', $FolderText = ''){
		parent::__construct($id, NAVIGATION_TABLE, stripslashes($JSIDName), stripslashes($JSTextName), $JSCommand, $order, '', $we_editDirID, $FolderText);
		$this->title = g_l('fileselector', '[navigationDirSelector][title]');
		$this->userCanMakeNewFolder = true;
		$this->fields.=',Charset';
	}

	function printHeaderHeadlines(){
		return '
<table class="headerLines" width="550">
	<tr>
		<th class="selector treeIcon"></th>
		<th class="selector"colspan="2"><a href="#" onclick="javascript:top.orderIt(\'Text\');">' . g_l('navigation', '[name]') . '</a></th>
	</tr>
	<tr>
		<td width="25">' . we_html_tools::getPixel(25, 1) . '</td>
		<td width="200">' . we_html_tools::getPixel(200, 1) . '</td>
		<td width="300">' . we_html_tools::getPixel(300, 1) . '</td>
	</tr>
</table>';
	}

	protected function printFooterTable(){
		$cancel_button = we_html_button::create_button('cancel', 'javascript:top.exit_close();');
		$yes_button = we_html_button::create_button('ok', "javascript:press_ok_button();");
		return '
<table id="footer">
	<tr>
		<td class="defaultfont description">' . g_l('navigation', '[name]') . '</td>
		<td class="defaultfont" align="left"><div id="showDiv" style="width:100%; height:2.2ex; background-color: #dce6f2; border: #AAAAAA solid 1px;"></div><div style="display:none;">' . we_html_tools::htmlTextInput('fname', 24, $this->values['Text'], '', 'style="width:100%" readonly="readonly"') . '</div></td>
	</tr>
</table><div id="footerButtons">' . we_html_button::position_yes_no_cancel($yes_button, null, $cancel_button) . '</div>';
	}

	protected function printHeaderTableExtraCols(){
		$makefolderState = permissionhandler::hasPerm("EDIT_NAVIGATION");
		return '<td>' .
			we_html_element::jsElement('makefolderState=' . $makefolderState . ';') .
			we_html_button::create_button("image:btn_new_dir", "javascript:if(makefolderState==1){top.drawNewFolder();}", true, 0, 0, "", "", $makefolderState ? false : true) .
			'</td>';
	}

	protected function printFramesetJSFunctionAddEntries(){
		$ret = '';
		while($this->next_record()){
			$_text = $this->f('Text');
			$_charset = $this->f('Charset');
			if(function_exists('mb_convert_encoding') && !empty($_charset)){
				$_text = mb_convert_encoding($this->f('Text'), 'HTML-ENTITIES', $_charset);
			}
			$ret.='addEntry(' . $this->f('ID') . ',"' . $this->f('Icon') . '","' . $_text . '",' . $this->f('IsFolder') . ',"' . $this->f('Path') . '");' . "\n";
		}
		return we_html_element::jsElement($ret);
	}

	protected function printCmdAddEntriesHTML(){
		$ret = '';
		$this->query();
		while($this->next_record()){
			$_text = $this->f('Text');
			$_charset = $this->f('Charset');
			if(function_exists('mb_convert_encoding') && !empty($_charset)){
				$_text = mb_convert_encoding($this->f('Text'), 'HTML-ENTITIES', $_charset);
			}
			$ret.='top.addEntry(' . $this->f('ID') . ',"' . $this->f('Icon') . '","' . $_text . '",' . $this->f('IsFolder') . ',"' . $this->f('Path') . '");';
		}
		return $ret;
	}

	function printCreateFolderHTML(){
		we_html_tools::protect();

		echo we_html_tools::getHtmlTop() .
		'<script type="text/javascript"><!--
top.clearEntries();';
		$this->FolderText = rawurldecode($this->FolderText);
		$txt = rawurldecode(we_base_request::_(we_base_request::FILE, 'we_FolderText_tmp', ''));

		if(!$txt){
			echo we_message_reporting::getShowMessageCall(g_l('navigation', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
		} else {
			$folder = new we_folder();
			$folder->we_new();
			$folder->setParentID($this->dir);
			$folder->Table = $this->table;
			$folder->Icon = we_base_ContentTypes::FOLDER_ICON;
			$folder->Text = $txt;
			$folder->Path = $folder->getPath();
			$this->db->query('SELECT ID FROM ' . $this->table . ' WHERE Path="' . $folder->Path . '"');
			if($this->db->next_record()){
				echo we_message_reporting::getShowMessageCall(g_l('navigation', '[folder_path_exists]'), we_message_reporting::WE_MESSAGE_ERROR);
			} elseif(we_navigation_navigation::filenameNotValid($folder->Text)){
				echo we_message_reporting::getShowMessageCall(g_l('navigation', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
			} else {
				$folder->we_save();
				echo 'var ref;
if(top.opener.top.makeNewEntry){
	ref = top.opener.top;
	ref.makeNewEntry("' . we_base_ContentTypes::FOLDER_ICON . '",' . $folder->ID . ',"' . $folder->ParentID . '","' . $txt . '",1,"folder","' . $this->table . '",0,0);
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

		echo
		$this->printCmdAddEntriesHTML() .
		$this->printCMDWriteAndFillSelectorHTML() .
		'top.makeNewFolder = 0;
top.selectFile(top.currentID);
//-->
</script>
</head><body></body></html>';
	}

	function query(){
		$this->db->query('SELECT ' . $this->fields . ' FROM ' . $this->table . ' WHERE IsFolder=1 AND ParentID=' . intval($this->dir) . ' ' . getWsQueryForSelector(NAVIGATION_TABLE) . ' ORDER BY Ordn, (text REGEXP "^[0-9]") DESC,ABS(text),Text');
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
			echo we_message_reporting::getShowMessageCall(g_l('navigation', '[folder_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
		} else {
			$folder = new we_folder();
			$folder->initByID($this->we_editDirID, $this->table);
			$folder->Text = $txt;
			$folder->Filename = $txt;
			$folder->Path = $folder->getPath();
			$this->db->query('SELECT ID,Text FROM ' . $this->db->escape($this->table) . " WHERE Path='" . $this->db->escape($folder->Path) . "' AND ID != " . intval($this->we_editDirID));
			if($this->db->next_record()){
				echo we_message_reporting::getShowMessageCall(sprintf(g_l('navigation', '[folder_exists]'), $folder->Path), we_message_reporting::WE_MESSAGE_ERROR);
			} elseif(strpbrk($folder->Text, '%/\\"\'') !== false){
				echo we_message_reporting::getShowMessageCall(g_l('navigation', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
			} elseif(f('SELECT Text FROM ' . $this->db->escape($this->table) . ' WHERE ID=' . intval($this->we_editDirID), "Text", $this->db) != $txt){
				$folder->we_save();
				echo 'var ref;
if(top.opener.top.updateEntry){
	ref = top.opener.top;
	ref.updateEntry(' . $folder->ID . ',"' . $txt . '","' . $folder->ParentID . '",1,0);
}' . ($this->canSelectDir ?
					'top.currentPath = "' . $folder->Path . '";
top.currentID = "' . $folder->ID . '";
top.fsfooter.document.we_form.fname.value = "' . $folder->Text . '";
' :
					''
				);
			}
		}

		echo
		$this->printCmdAddEntriesHTML() .
		$this->printCMDWriteAndFillSelectorHTML() .
		'top.makeNewFolder = 0;
top.selectFile(top.currentID);
//-->
</script>
</head><body></body></html>';
	}

	protected function getFramsetJSFile(){
		return parent::getFramsetJSFile() . we_html_element::jsScript(JS_DIR . 'selectors/naviagationDir_selector.js');
	}

	function getFramesetJavaScriptDef(){
		return parent::getFramesetJavaScriptDef() . we_html_element::jsElement('
g_l.newFolder="' . g_l('navigation', '[newFolder]') . '";
');
	}

}
