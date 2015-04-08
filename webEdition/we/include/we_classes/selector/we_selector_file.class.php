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
class we_selector_file{
	const FRAMESET = 0;
	const HEADER = 1;
	const FOOTER = 2;
	const BODY = 3;
	const CMD = 4;
	const SETDIR = 5;
	const CREATE_CAT = 7;
	const NEWFOLDER = 7;
	const CREATEFOLDER = 8;
	const DO_RENAME_CAT = 9;
	const RENAMEFOLDER = 9;
	const DO_RENAME_ENTRY = 10;
	const DORENAMEFOLDER = 10;
	const DEL = 11;
	const PREVIEW = 11;
	const PROPERTIES = 12;
	const CHANGE_CAT = 13;
	const WINDOW_SELECTOR_WIDTH = 900;
	const WINDOW_SELECTOR_HEIGHT = 685;
	const WINDOW_DIRSELECTOR_WIDTH = 900;
	const WINDOW_DIRSELECTOR_HEIGHT = 600;
	const WINDOW_DOCSELECTOR_WIDTH = 900;
	const WINDOW_DOCSELECTOR_HEIGHT = 685;
	const WINDOW_CATSELECTOR_WIDTH = 900;
	const WINDOW_CATSELECTOR_HEIGHT = 638;
	const WINDOW_DELSELECTOR_WIDTH = 900;
	const WINDOW_DELSELECTOR_HEIGHT = 600;

	var $dir = 0;
	var $id = 0;
	var $path = '/';
	var $lastdir = '';
	protected $table = FILE_TABLE;
	var $tableHeadlines = '';
	var $JSCommand = '';
	var $JSTextName;
	var $JSIDName;
	protected $db;
	var $sessionID = '';
	protected $fields = 'ID,ParentID,Text,Path,IsFolder';
	var $values = array();
	var $openerFormName = 'we_form';
	protected $order = 'Text';
	protected $canSelectDir = true;
	var $rootDirID = 0;
	protected $filter = '';
	protected $col2js;
	protected $title = '';
	protected $startID = 0;
	protected $multiple = true;
	protected $open_doc = 0;

	public function __construct($id, $table = FILE_TABLE, $JSIDName = "", $JSTextName = "", $JSCommand = "", $order = "", $rootDirID = 0, $multiple = true, $filter = "", $startID = 0){
		if(defined('CUSTOMER_TABLE') && $table == CUSTOMER_TABLE){
			$this->fields = str_replace('Text', 'CONCAT(Text," (",Forename," ", Surname,")") AS Text', $this->fields);
		}

		if(!isset($_SESSION['weS']['we_fs_lastDir'])){
			$_SESSION['weS']['we_fs_lastDir'] = array($table => 0);
		}

		$this->order = ($order ? : $this->order);

		$this->db = new DB_WE();
		$this->id = $id;
		$this->lastDir = isset($_SESSION['weS']['we_fs_lastDir'][$table]) ? intval($_SESSION['weS']['we_fs_lastDir'][$table]) : 0;
//check table

		$this->table = $table;
		switch($this->table){//FIXME: are there more types with icon? category?
			case FILE_TABLE:
			case TEMPLATES_TABLE:
			case (defined('OBJECT_FILES_TABLE') ? OBJECT_FILES_TABLE : 'OBJECT_FILES_TABLE'):
			case (defined('OBJECT_TABLE') ? OBJECT_TABLE : 'OBJECT_TABLE'):
				$this->fields.= ',Icon';
				break;
			default:
		}

		$this->JSIDName = $JSIDName;
		$this->JSTextName = $JSTextName;
		$this->JSCommand = $JSCommand;
		$this->rootDirID = intval($rootDirID);
		$this->filter = $filter;
		$this->startID = $startID;
		$this->multiple = $multiple;
		$this->setDirAndID();
		$this->setTableLayoutInfos();
	}

	protected function setDirAndID(){
		$id = $this->id;
		if($id > 0){
			// get default Directory
			$this->db->query('SELECT ' . $this->fields . ' FROM ' . $this->db->escape($this->table) . ' WHERE ID=' . intval($id));

			// getValues of selected Dir
			if($this->db->next_record()){
				$this->values = $this->db->getRecord();

				$this->dir = ($this->values['IsFolder'] ?
						$id :
						$this->values['ParentID']);

				$this->path = $this->values['Path'];
				return;
			}
		}
		$this->setDefaultDirAndID($id === 0 ? false : true);
	}

	protected function setDefaultDirAndID($setLastDir){
		$this->dir = $this->startID ? : ($setLastDir ? ( isset($_SESSION['weS']['we_fs_lastDir'][$this->table]) ? intval($_SESSION['weS']['we_fs_lastDir'][$this->table]) : 0 ) : 0);
		$this->id = $this->dir;

		$this->path = '';

		$this->values = array(
			'ParentID' => 0,
			'Text' => '/',
			'Path' => '/',
			'IsFolder' => 1
		);
	}

	function isIDInFolder($ID, $folderID, we_database_base $db = null){
		if($folderID == $ID){
			return true;
		}
		$db = ($db ? : new DB_WE());
		$pid = f('SELECT ParentID FROM ' . $db->escape($this->table) . ' WHERE ID=' . intval($ID), '', $db);
		if($pid == $folderID){
			return true;
		}
		if($pid != 0){
			return $this->isIDInFolder($pid, $folderID, $db);
		}
		return false;
	}

	function query(){
		$wsQuery = $this->table == NAVIGATION_TABLE && get_ws($this->table) ? ' ' . getWsQueryForSelector($this->table) : '';
		$this->db->query('SELECT ' . $this->fields . ' FROM ' . $this->db->escape($this->table) . ' WHERE ParentID=' . intval($this->dir) . ' ' .
			( ($this->filter ? ($this->table == CATEGORY_TABLE ? 'AND IsFolder = "' . $this->db->escape($this->filter) . '" ' : 'AND ContentType = "' . $this->db->escape($this->filter) . '" ') : '' ) . $wsQuery ) .
			($this->order ? (' ORDER BY IsFolder DESC,' . $this->order) : ''));
		$_SESSION['weS']['we_fs_lastDir'][$this->table] = $this->dir;
	}

	function next_record(){
		return $this->db->next_record();
	}

	function f($key){
		return $this->db->f($key);
	}

	function printHTML($what = we_selector_file::FRAMESET){
		switch($what){
			case self::HEADER:
				$this->printHeaderHTML();
				break;
			case self::FOOTER:
				$this->printFooterHTML();
				break;
			case self::BODY:
				$this->printBodyHTML();
				break;
			case self::CMD:
				$this->printCmdHTML();
				break;
			case self::FRAMESET:
			default:
				$this->printFramesetHTML();
		}
	}

	function printFramesetHTML(){
		$this->setDirAndID(); //set correct directory
		echo we_html_tools::getHtmlTop($this->title, '', 'frameset') .
		we_html_element::jsScript(JS_DIR . 'keyListener.js') .
		we_html_element::jsScript(JS_DIR . 'windows.js') .
		$this->getFramesetJavaScriptDef() .
		$this->getFramsetJSFile() .
		$this->getExitOpen() .
		$this->printFramesetJSFunctions() .
		we_html_element::jsElement('self.focus();');
		?>
		</head>
		<?php
		echo $this->getFrameset();
	}

	protected function getFramsetJSFile(){
		return we_html_element::jsScript(JS_DIR . 'selectors/file_selector.js');
	}

	function getFramesetJavaScriptDef(){
		$startPathQuery = new DB_WE();
		$startPathQuery->query('SELECT Path FROM ' . $startPathQuery->escape($this->table) . ' WHERE ID=' . intval($this->dir));
		$startPath = $startPathQuery->next_record() ? $startPathQuery->f('Path') : '/';
		if($this->id == 0){
			$this->path = '/';
		}
		return we_html_element::jsElement('
var weSelectorWindow = true;
var currentID="' . $this->id . '";
var currentDir="' . $this->dir . '";
var currentPath="' . $this->path . '";
var currentText="' . (isset($this->values["Text"]) ? $this->values["Text"] : '') . '";
var currentType="' . (isset($this->filter) ? $this->filter : "") . '";
var startPath="' . $startPath . '";
var parentID=' . intval(($this->dir ? f('SELECT ParentID FROM ' . $this->db->escape($this->table) . ' WHERE ID=' . intval($this->dir), '', $this->db) : 0)) . ';
var table="' . $this->table . '";
var order="' . $this->order . '";
var entries = [];
var clickCount=0;
var wasdblclick=0;
var tout=null;
var mk=null;

var queryType={
	"CMD":' . self::CMD . ',
	"DEL":' . self::DEL . ',
	"PROPERTIES":' . self::PROPERTIES . ',
	"PREVIEW":' . self::PREVIEW . ',
	"NEWFOLDER":' . self::NEWFOLDER . ',
	"CREATEFOLDER":' . self::CREATEFOLDER . ',
	"RENAMEFOLDER":' . self::RENAMEFOLDER . ',
	"CREATE_CAT":' . self::CREATE_CAT . ',
	"DO_RENAME_ENTRY":' . self::DO_RENAME_ENTRY . ',
	"SETDIR":' . self::SETDIR . '
};

var dirs={
	"TREE_ICON_DIR":"' . TREE_ICON_DIR . '",
	"WEBEDITION_DIR":"' . WEBEDITION_DIR . '",
	"ICON_DIR":"' . ICON_DIR . '"
};

var options={
  "rootDirID":' . $this->rootDirID . ',
	"table":"' . $this->table . '",
	"formtarget":"' . $_SERVER["SCRIPT_NAME"] . '",
	"rootDirID":' . $this->rootDirID . ',
	"multiple":' . intval($this->multiple) . ',
	"needIEEscape":' . intval(we_base_browserDetect::isIE() && $GLOBALS['WE_BACKENDCHARSET'] != 'UTF-8') . ',
	"open_doc":"' . $this->open_doc . '"
};

var consts={
	"FOLDER_ICON":"' . we_base_ContentTypes::FOLDER_ICON . '",
	"DORENAMEFOLDER":"' . self::DORENAMEFOLDER . '",
	"CREATEFOLDER":"' . self::CREATEFOLDER . '"
};

var g_l={
	"deleteQuestion":\'' . g_l('fileselector', '[deleteQuestion]') . '\',
	"new_folder_name":"' . g_l('fileselector', '[new_folder_name]') . '",
	"date_format":"' . date(g_l('date', '[format][default]')) . '",
	"folder":"' . g_l('contentTypes', '[folder]') . '"
};

');
	}

	protected function getFrameset(){
		return
			STYLESHEET .
			we_html_element::cssLink(CSS_DIR . 'selectors.css') .
			'<body class="selector">' .
			we_html_element::htmlIFrame('fsheader', $this->getFsQueryString(we_selector_file::HEADER), '', '', '', false) .
			we_html_element::htmlIFrame('fsbody', $this->getFsQueryString(we_selector_file::BODY), '', '', '', true) .
			we_html_element::htmlIFrame('fsfooter', $this->getFsQueryString(we_selector_file::FOOTER), '', '', '', false) .
			we_html_element::htmlIFrame('fscmd', 'about:blank', '', '', '', false) .
			'</body>
</html>';
	}

	protected function getExitOpen(){
		$frameRef = $this->JSTextName && strpos($this->JSTextName, ".document.") > 0 ? substr($this->JSTextName, 0, strpos($this->JSTextName, ".document.") + 1) : "";
		return we_html_element::jsElement('
function exit_open(){' . ($this->JSIDName ? '
	opener.' . $this->JSIDName . '=currentID;' : '') .
				($this->JSTextName ? 'opener.' . $this->JSTextName . '= currentID ? currentPath : "";
	if((!!opener.parent) && (!!opener.parent.frames.editHeader) && (!!opener.parent.frames.editHeader.setPathGroup)) {
			if(currentType!="")	{
				switch(currentType){
					case "noalias":
						setTabsCurPath = "@"+currentText;
						break;
					default:
						setTabsCurPath = currentPath;
				}
				if(getEntry(currentID).isFolder) opener.parent.frames.editHeader.setPathGroup(setTabsCurPath);
				else opener.parent.frames.editHeader.setPathName(setTabsCurPath);
				opener.parent.frames.editHeader.setTitlePath();
			}
	}
	if(!!opener.' . $frameRef . 'YAHOO && !!opener.' . $frameRef . 'YAHOO.autocoml) {  opener.' . $frameRef . 'YAHOO.autocoml.selectorSetValid(opener.' . str_replace('.value', '.id', $this->JSTextName) . '); }
	' : '') .
				($this->JSCommand ?
					'	' . str_replace('WE_PLUS', '+', $this->JSCommand) . ';' : '') .
				'	self.close();
	}'
		);
	}

	protected function getFsQueryString($what){
		return $_SERVER['SCRIPT_NAME'] . '?what=' . $what . '&table=' . $this->table . '&id=' . $this->id . '&order=' . $this->order . '&startID=' . $this->startID . '&filter=' . $this->filter;
	}

	protected function printFramesetJSFunctionAddEntries(){
		$ret = '';
		while($this->next_record()){
			$ret.= 'addEntry(' . $this->f('ID') . ',"' . $this->f('Icon') . '","' . addcslashes($this->f('Text'), '"') . '",' . ($this->f('IsFolder') | 0) . ',"' . addcslashes($this->f('Path'), '"') . '");';
		}
		return we_html_element::jsElement($ret);
	}

	protected function printFramesetJSFunctions(){
		$this->query();
		return
			$this->printFramesetJSFunctionAddEntries() .
			we_html_element::jsElement('
var allIDs ="";
var allPaths ="";
var allTexts ="";
var allIsFolder ="";

function fillIDs() {
	allIDs =",";
	allPaths =",";
	allTexts =",";
	allIsFolder =",";

	for	(var i=0;i < entries.length; i++) {
		if (isFileSelected(entries[i].ID)) {
			allIDs += (entries[i].ID + ",");
			allPaths += (entries[i].path + ",");
			allTexts += (entries[i].text + ",");
			allIsFolder += (entries[i].isFolder + ",");
		}
	}
	if(currentID != ""){
		if(allIDs.indexOf(","+currentID+",") == -1){
			allIDs += (currentID + ",");
		}
	}
	if(currentPath != ""){
		if(allPaths.indexOf(","+currentPath+",") == -1){
			allPaths += (currentPath + ",");
			allTexts += (we_makeTextFromPath(currentPath) + ",");
		}
	}

	if (allIDs == ",") {
		allIDs = "";
	}
	if (allPaths == ",") {
		allPaths = "";
	}
	if (allTexts == ",") {
		allTexts = "";
	}

	if (allIsFolder == ",") {
		allIsFolder = "";
	}
}

function we_makeTextFromPath(path){
	position =  path.lastIndexOf("/");
	if(position > -1 &&  position < path.length){
		return path.substring(position+1);
	}else{
		return "";
	}
}');
	}

	protected function printBodyHTML(){
		echo we_html_tools::getHtmlTop('', '', '4Trans') .
		we_html_element::jsScript(JS_DIR . 'utils/jsErrorHandler.js') .
		STYLESHEET_SCRIPT .
		we_html_element::cssLink(CSS_DIR . 'selectors.css') .
		$this->getWriteBodyHead() .
		'</head>
				<body class="selectorBody" onload="top.writeBody(self.document.body);" onclick="weonclick(event);"></body></html>';
	}

	protected function getWriteBodyHead(){
		return we_html_element::jsElement('
var ctrlpressed=false;
var shiftpressed=false;
var wasdblclick=false;
var inputklick=false;
var tout=null;
function weonclick(e){
		if(document.all){
			if(e.ctrlKey || e.altKey){
				ctrlpressed=true;
			}
			if(e.shiftKey){
				shiftpressed=true;
			}
		}else{
			if(e.altKey || e.metaKey || e.ctrlKey){
				ctrlpressed=true;
			}
			if(e.shiftKey){
				shiftpressed=true;
			}
		}
		if(top.options.multiple){
		if((self.shiftpressed==false) && (self.ctrlpressed==false)){
			top.unselectAllFiles();
		}
		}else{
		top.unselectAllFiles();
		}
}');
	}

	protected function printHeaderHTML(){
		$this->setDirAndID();
		echo we_html_tools::getHtmlTop() .
		STYLESHEET .
		we_html_element::cssLink(CSS_DIR . 'selectors.css') .
		we_html_element::jsScript(JS_DIR . 'images.js') .
		we_html_element::jsElement($this->printHeaderJSDef()) .
		we_html_element::jsScript(JS_DIR . 'selectors/header.js') . '
</head>
	<body class="selectorHeader">
		<form name="we_form" method="post">' .
		((!defined('OBJECT_TABLE')) || $this->table != OBJECT_TABLE ?
			$this->printHeaderTable() : '') .
		$this->printHeaderHeadlines() .
		'</form>
	</body>
</html>';
	}

	protected function printHeaderTableExtraCols(){
		// overwrite
	}

	protected function printHeaderOptions(){
		$pid = $this->dir;
		$out = '';
		$c = $z = 0;
		while($pid != 0){
			$c++;
			$this->db->query('SELECT ID,Text,ParentID FROM ' . $this->db->escape($this->table) . ' WHERE ID=' . intval($pid));
			if($this->db->next_record()){
				$out = '<option value="' . $this->db->f('ID') . '"' . (($z == 0) ? ' selected="selected"' : '') . '>' . $this->db->f('Text') . '</option>' . $out;
				$z++;
			}
			$pid = $this->db->f('ParentID');
			if($c > 500){
				$pid = 0;
			}
		}
		return '<option value="0">/</option>' . $out;
	}

	protected function printHeaderTable(){
		return '
<table class="selectorHeaderTable">
	<tr valign="middle">
		<td class="defaultfont lookinText">' . g_l('fileselector', '[lookin]') . '</td>
		<td><select name="lookin" class="weSelect" size="1" onchange="top.setDir(this.options[this.selectedIndex].value);" class="defaultfont" style="width:100%">' .
			$this->printHeaderOptions() . '
		</select>
		</td>
		<td>' . we_html_button::create_button("root_dir", "javascript:if(rootDirButsState){top.setRootDir();}", false, 40, 22, "", "", ($this->dir == 0), false) . '</td>
		<td>' . we_html_button::create_button("image:btn_fs_back", "javascript:top.goBackDir();", false, 40, 22, "", "", ($this->dir == 0), false) . '</td>' .
			$this->printHeaderTableExtraCols() .
			'</tr>
</table>';
	}

	function printHeaderHeadlines(){
		return '
<table class="headerLines">
	<tr>
		<th class="selector treeIcon"></th>
		<th class="selector filename"><a href="#" onclick="javascript:top.orderIt(\'Text\');">' . g_l('fileselector', '[filename]') . '</a></th>
		<th class="selector remain"></th>
	</tr>
</table>';
	}

	protected function printHeaderJSDef(){
		return 'var rootDirButsState = ' . (($this->dir == 0) ? 0 : 1) . ';';
	}

	protected function printCmdHTML(){
		echo we_html_element::jsElement('
top.clearEntries();' .
			$this->printCmdAddEntriesHTML() .
			$this->printCMDWriteAndFillSelectorHTML() .
			(($this->dir) == 0 ?
				'top.fsheader.disableRootDirButs();' :
				'top.fsheader.enableRootDirButs();') .
			'top.currentPath = "' . $this->path . '";
top.parentID = "' . $this->values["ParentID"] . '";
');
	}

	protected function printCmdAddEntriesHTML(){
		$ret = '';
		$this->query();
		while($this->next_record()){
			$ret.= 'top.addEntry(' . $this->f("ID") . ',"' . $this->f("Icon") . '","' . addcslashes(str_replace(array("\n", "\r"), "", $this->f("Text")), '"') . '",' . $this->f("IsFolder") . ',"' . addcslashes(str_replace(array("\n", "\r"), "", $this->f("Path")), '"') . '");';
		}
		return $ret;
	}

	protected function printCMDWriteAndFillSelectorHTML(){
		$pid = $this->dir;
		$out = '';
		$c = 0;
		while($pid != 0){
			$c++;
			$this->db->query('SELECT ID,Text,ParentID FROM ' . $this->db->escape($this->table) . ' WHERE ID=' . intval($pid));
			if($this->db->next_record()){
				$out = 'top.fsheader.addOption("' . $this->db->f('Text') . '",' . $this->db->f('ID') . ');' . $out;
			}
			$pid = $this->db->f('ParentID');
			if($c > 500){
				$pid = 0;
			}
		}
		return '
top.writeBody(top.fsbody.document.body);
top.fsheader.clearOptions();
top.fsheader.addOption("/",0);' .
			$out . '
top.fsheader.selectIt();';
	}

	protected function printFooterHTML(){
		echo we_html_tools::getHtmlTop() .
		STYLESHEET .
		we_html_element::cssLink(CSS_DIR . 'selectors.css') .
		$this->printFooterJSDef() .
		we_html_element::jsElement('
function disableDelBut(){
	switch_button_state("delete", "delete_enabled", "disabled");
}
function enableDelBut(){
	switch_button_state("delete", "delete_enabled", "enabled");
}') . '
</head>
	<body class="selectorFooter">
	<form name="we_form" target="fscmd">' .
		$this->printFooterTable() . '
	</form>
	</body>
</html>';
	}

	protected function printFooterJSDef(){
		return we_html_element::jsElement("
function press_ok_button() {
	if(document.we_form.fname.value==''){
		top.exit_close();
	}else{
		top.exit_open();
	};
}");
	}

	protected function printFooterTable(){
		$cancel_button = we_html_button::create_button("cancel", "javascript:top.exit_close();");
		$yes_button = we_html_button::create_button("ok", "javascript:press_ok_button();");
		$buttons = we_html_button::position_yes_no_cancel($yes_button, null, $cancel_button);
		return '
<table class="footer">
	<tr>
		<td class="defaultfont">
			<b>' . g_l('fileselector', '[name]') . '</b>
		</td>
		<td></td>
		<td class="defaultfont" align="left">' . we_html_tools::htmlTextInput("fname", 24, $this->values["Text"], "", "style=\"width:100%\" readonly=\"readonly\"") . '
		</td>
	</tr>
	<tr>
		<td width="70"></td>
		<td width="10"></td>
		<td></td>
	</tr>
	</table><div id="footerButtons">' . $buttons . '</div>';
	}

	private function setTableLayoutInfos(){
		//FIXME: should we add a column for extension?
		switch($this->table){
			case (defined('OBJECT_TABLE') ? OBJECT_TABLE : 'OBJECT_TABLE'):
			case TEMPLATES_TABLE:
				$this->col2js = "entries[i].ID";
				$this->tableHeadlines = "
<th class='selector treeIcon'></th>
<th class='selector filename'><a href='#' onclick='javascript:top.orderIt(\"Text\");'>" . g_l('fileselector', '[filename]') . "</a></th>
<th class='selector title'>ID</th>
<th class='selector modddate'><a href='#' onclick='javascript:top.orderIt(\"ModDate\");'>" . g_l('fileselector', '[modified]') . "</a></th>
<th class='selector remain'></th>";
				break;
			default:
				$this->col2js = "entries[i].title";
				$this->tableHeadlines = "
<th class='selector treeIcon'></th>
<th class='selector filename'><a href='#' onclick='javascript:top.orderIt(\"Text\");'>" . g_l('fileselector', '[filename]') . "</a></th>
<th class='selector title'>" . g_l('fileselector', '[title]') . "</th>
<th class='selector moddate'><a href='#' onclick='javascript:top.orderIt(\"ModDate\");'>" . g_l('fileselector', '[modified]') . "</a></th>
<th class='selector remain'></th>";
		}
	}

}
