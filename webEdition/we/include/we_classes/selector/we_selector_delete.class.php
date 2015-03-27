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
class we_selector_delete extends we_selector_multiple{

	function __construct($id, $table = FILE_TABLE){
		parent::__construct($id, $table);
		$this->title = g_l('fileselector', '[delSelector][title]');
	}

	function printHTML($what = we_selector_file::FRAMESET){
		switch($what){
			case we_selector_file::HEADER:
				$this->printHeaderHTML();
				break;
			case we_selector_file::FOOTER:
				$this->printFooterHTML();
				break;
			case we_selector_file::BODY:
				$this->printBodyHTML();
				break;
			case we_selector_file::CMD:
				$this->printCmdHTML();
				break;
			case self::DEL:
				$this->printDoDelEntryHTML();
				break;
			case we_selector_file::FRAMESET:
			default:
				$this->printFramesetHTML();
		}
	}

	protected function printFramesetJSFunctions(){
		$tmp = (isset($_SESSION['weS']['seemForOpenDelSelector']['ID']) ? $_SESSION['weS']['seemForOpenDelSelector']['ID'] : 0);
		unset($_SESSION['weS']['seemForOpenDelSelector']['ID']);

		return parent::printFramesetJSFunctions() . we_html_element::jsElement('
function deleteEntry(){
	if(confirm(\'' . g_l('fileselector', '[deleteQuestion]') . '\')){
		var todel = "";
		var docIsOpen = false;
		for	(var i=0;i < entries.length; i++){
			if(isFileSelected(entries[i].ID)){
				todel += entries[i].ID + ",";' .
				($tmp ? '
						if(entries[i].ID=="' . $_SESSION['weS']['seemForOpenDelSelector']['ID'] . '") {
							docIsOpen = true;
						}' : '') . '
			}
		}
		if (todel) {
			todel = "," + todel;
		}

		top.fscmd.location.replace(top.queryString(' . self::DEL . ',top.currentID)+"&todel="+encodeURI(todel));
		top.fsfooter.disableDelBut();

		if(docIsOpen) {
			top.opener.top.we_cmd("close_all_documents");
			top.opener.top.we_cmd("start_multi_editor");
		}
	}
}');
	}

	protected function getFramsetJSFile(){
		return parent::getFramsetJSFile() .we_html_element::jsScript(JS_DIR . 'selectors/delete_selector.js');
	}

	protected function printCmdHTML(){
		echo we_html_element::jsElement('
top.clearEntries();' .
				$this->printCmdAddEntriesHTML() .
				$this->printCMDWriteAndFillSelectorHTML() .
				(intval($this->dir) == 0 ? '
top.fsheader.disableRootDirButs();
top.fsfooter.disableDelBut();' : '
top.fsheader.enableRootDirButs();
top.fsfooter.enableDelBut();') . '
top.currentPath = "' . $this->path . '";
top.parentID = "' . $this->values["ParentID"] . '";');
	}

	function renameChildrenPath($id){
		//FIXME: this can be done with one db connection!
		$db = new DB_WE();
		$db2 = new DB_WE();
		$db->query('SELECT ID,IsFolder,Text FROM ' . $db->escape($this->table) . ' WHERE ParentID=' . intval($id));
		while($db->next_record()){
			$newPath = f('SELECT Path FROM ' . $db->escape($this->table) . ' WHERE ID=' . intval($id), "", $db2) . '/' . $db->f('Text');
			$db2->query('UPDATE ' . $db->escape($this->table) . " SET Path='" . $db->escape($newPath) . "' WHERE ID=" . intval($db->f('ID')));
			if($db->f('IsFolder')){
				$this->renameChildrenPath($db->f("ID"));
			}
		}
	}

	function printDoDelEntryHTML(){
		we_html_tools::protect();
		echo we_html_tools::getHtmlTop();
		if(($del = we_base_request::_(we_base_request::RAW, "todel"))){
			$_SESSION['weS']['todel'] = $del;
			echo we_html_element::jsScript(JS_DIR . 'windows.js') . we_html_element::jsElement('
top.opener.top.we_cmd("del_frag", "' . $del . '");
top.close();');
		}
		echo '</head><body></body></html>';
	}

	protected function printFooterTable(){
		if($this->values["Text"] === "/"){
			$this->values["Text"] = "";
		}
		$okBut = we_html_button::create_button("delete", "javascript:if(document.we_form.fname.value==''){top.exit_close();}else{top.deleteEntry();}", true, 100, 22, "", "", true, false);

		$cancelbut = we_html_button::create_button("cancel", "javascript:top.exit_close();");
		$buttons = ($okBut ? we_html_button::position_yes_no_cancel($okBut, null, $cancelbut) : $cancelbut);

		return '
<table class="footer">
	<tr>
		<td></td>
		<td class="defaultfont">
			<b>' . g_l('fileselector', '[filename]') . '</b>
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

	function query(){
		$this->db->query('SELECT ' . $this->fields . ' FROM ' . $this->db->escape($this->table) . ' WHERE ParentID=' . intval($this->dir) . ' AND((1' . we_users_util::makeOwnersSql() . ')' .
			getWsQueryForSelector($this->table, false) . ')' . ($this->order ? (' ORDER BY IsFolder DESC,' . $this->order) : '')
		);
	}

}
