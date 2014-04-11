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
 * @package    webEdition_class
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/** the parent class of storagable webEdition classes */
abstract class we_class{

	//constants for retrieving data from DB

	const LOAD_MAID_DB = 0;
	const LOAD_TEMP_DB = 1;
	const LOAD_REVERT_DB = 2; //we_temporaryDocument::revert gibst nicht mehr siehe #5789
	const LOAD_SCHEDULE_DB = 3;
	//constants define where to write document (guess)
	const SUB_DIR_NO = 0;
	const SUB_DIR_YEAR = 1;
	const SUB_DIR_YEAR_MONTH = 2;
	const SUB_DIR_YEAR_MONTH_DAY = 3;

	/* Name of the class => important for reconstructing the class from outside the class */

	var $ClassName = __CLASS__;
	/* In this array are all storagable class variables */
	var $persistent_slots = array('ClassName', 'Name', 'ID', 'Table', 'wasUpdate', 'InWebEdition');
	/* Name of the Object that was createt from this class */
	var $Name = '';

	/* ID from the database record */
	var $ID = 0;

	/* database table in which the object is stored */
	var $Table = '';

	/* Database Object */
	protected $DB_WE;

	/* optional ID for being able to manipulate the ID for DB inserts, requires a getter and setter */
	protected $insertID = 0;

	/* Flag which is set when the file is not new */
	var $wasUpdate = 0;
	public $InWebEdition = 0;
	var $PublWhenSave = 1;
	var $IsTextContentDoc = false;
	public $LoadBinaryContent = false;
	var $fileExists = 1;
	protected $errMsg = '';

	//Overwrite
	public function we_new(){

	}

	//Overwrite
	function we_initSessDat($sessDat){

	}

	/* Constructor */

	function __construct(){
		$this->Name = md5(uniqid(__FILE__, true));
		$this->ClassName = get_class($this); //$this is different from self!
		$this->DB_WE = new DB_WE();
	}

	/* Intialize the class. If $sessDat (array) is set, the class will be initialized from this array */

	function init(){
		$this->we_new();
	}

	/* set the protected variable insertID */

	function setInsertID($insertID){
		$this->insertID = $insertID;
	}

	/* get the protected variable insertID */

	function getInsertID(){
		return $this->insertID;
	}

	/* returns the url $in with $we_transaction appended */

	public static function url($in){
		return $in . ( strpos($in, '?') !== FALSE ? '&' : '?') . 'we_transaction=' . $GLOBALS['we_transaction'];
	}

	/* returns the code for a hidden " we_transaction-field" */

	public static function hiddenTrans(){
		return '<input type="hidden" name="we_transaction" value="' . $GLOBALS['we_transaction'] . '" />';
	}

	/* must be overwritten by child */

	function saveInSession(/* &$save */){

	}

	/* creates a text-input field for entering Data that will be stored at the $elements Array */

	function formInput($name, $size = 25, $type = 'txt'){
		return $this->formTextInput($type, $name, (g_l('weClass', '[' . $name . ']') ? g_l('weClass', '[' . $name . ']') : $name), $size);
	}

	/* creates a color field. when user clicks, a colorchooser opens. Data that will be stored at the $elements Array */

	function formColor($width, $name, $size = 25, $type = 'txt', $height = 18, $isTag = false){
		$value = $this->getElement($name);
		if(!$isTag){
			$width -= 4;
		}
		$formname = 'we_' . $this->Name . '_' . $type . '[' . $name . ']';
		$out = $this->htmlHidden($formname, $this->getElement($name)) . '<table cellpadding="0" cellspacing="0" border="1"><tr><td' . ($value ? (' bgcolor="' . $value . '"') : '') . '><a href="javascript:setScrollTo();we_cmd(\'openColorChooser\',\'' . $formname . '\',document.we_form.elements[\'' . $formname . '\'].value);">' . we_html_tools::getPixel($width, $height) . '</a></td></tr></table>';
		return g_l('weClass', '[' . $name . ']', true) !== false ? we_html_tools::htmlFormElementTable($out, g_l('weClass', '[' . $name . ']')) : $out;
	}

	function formTextInput($elementtype, $name, $text, $size = 24, $maxlength = '', $attribs = '', $textalign = 'left', $textclass = 'defaultfont'){
		if(!$elementtype){
			$ps = $this->$name;
		}
		return we_html_tools::htmlFormElementTable($this->htmlTextInput(($elementtype ? ('we_' . $this->Name . '_' . $elementtype . "[$name]") : ('we_' . $this->Name . '_' . $name)), $size, ($elementtype ? $this->getElement($name) : $ps), $maxlength, $attribs), $text, $textalign, $textclass);
	}

	function formInputField($elementtype, $name, $text, $size, $width, $maxlength = '', $attribs = '', $textalign = 'left', $textclass = 'defaultfont'){
		if(!$elementtype){
			$ps = $this->$name;
		}
		return we_html_tools::htmlFormElementTable($this->htmlTextInput(($elementtype ? ('we_' . $this->Name . '_' . $elementtype . '[' . $name . ']') : ('we_' . $this->Name . '_' . $name)), $size, ($elementtype && $this->getElement($name) != '' ? $this->getElement($name) : (isset($GLOBALS['meta'][$name]) ? $GLOBALS['meta'][$name]['default'] : (isset($ps) ? $ps : '') )), $maxlength, $attribs, 'text', $width), $text, $textalign, $textclass);
	}

	function formTextArea($elementtype, $name, $text, $rows = 10, $cols = 30, array $attribs = array(), $textalign = 'left', $textclass = 'defaultfont'){
		return we_html_tools::htmlFormElementTable(self::htmlTextArea(($elementtype ? ('we_' . $this->Name . '_' . $elementtype . "[$name]") : ('we_' . $this->Name . '_' . $name)), $rows, $cols, $this->getElement($name), $attribs), $text, $textalign, $textclass);
	}

	function formSelectFromArray($elementtype, $name, $vals, $text, $size, $multiple, array $attribs){
		$pop = $this->htmlSelect(('we_' . $this->Name . '_' . $elementtype . "[$name]"), $vals, $size, $this->getElement($name), $multiple, $attribs);
		return we_html_tools::htmlFormElementTable($pop, $text, 'left', 'defaultfont');
	}

	function htmlTextInput($name, $size = 24, $value = '', $maxlength = '', $attribs = '', $type = 'text', $width = 0, $height = 0){
		return we_html_tools::htmlTextInput($name, $size, $value, $maxlength, $attribs, $type, $width, $height);
	}

	function htmlHidden($name, $value = ''){
		return we_html_element::htmlHidden(array('name' => trim($name), 'value' => oldHtmlspecialchars($value)));
	}

	static function htmlTextArea($name, $rows = 10, $cols = 30, $value = '', array $attribs = array()){
		return we_html_element::htmlTextArea(
				array_merge(array(
				'name' => trim($name),
				'class' => 'defaultfont wetextarea',
				'rows' => abs($rows),
				'cols' => abs($cols),
					), $attribs
				), ($value ? (oldHtmlspecialchars($value)) : ''));
	}

	//fixme: add auto-grouping, add format
	function htmlSelect($name, array $values, $size = 1, $selectedIndex = '', $multiple = false, array $attribs = array(), $compare = 'value', $width = 0){
		$optgroup = false;
		$selIndex = $multiple ? explode(',', $selectedIndex) : array($selectedIndex);
		$ret = '';
		foreach($values as $value => $text){
			if($text == we_html_tools::OPTGROUP){
				if($optgroup){
					$ret .= '</optgroup>';
				}
				$optgroup = true;
				$ret .= '<optgroup label="' . oldHtmlspecialchars($value) . '">';
				continue;
			}

			$ret .= '<option value="' . oldHtmlspecialchars($value) . '"' . (in_array((($compare == 'value') ? $value : $text), $selIndex) ? ' selected="selected"' : '') . '>' . $text . '</option>';
		}
		return we_html_element::htmlSelect(array_merge($attribs, array(
				'id' => trim($name),
				'class' => "weSelect defaultfont",
				'name' => trim($name),
				'size' => abs($size),
				($multiple ? 'multiple' : null) => 'multiple',
				($width ? 'width' : null) => $width
				)), $ret);
	}

	############## new fns
	/* creates a select field for entering Data that will be stored at the $elements Array */

	function formSelectElement($width, $name, $values, $type = 'txt', $size = 1, array $attribs = array()){
		return we_html_tools::htmlFormElementTable(
				we_html_tools::html_select('we_' . $this->Name . '_' . $type . "[$name]", $size, $values, $this->getElement($name), array_merge(array(
					'class' => 'defaultfont',
					'width' => $width,
						), $attribs))
				, g_l('weClass', '[' . $name . ']'));
	}

	function formInput2($width, $name, $size = 25, $type = 'txt', $attribs = ''){
		return $this->formInputField($type, $name, (g_l('weClass', '[' . $name . ']', true) != false ? g_l('weClass', '[' . $name . ']') : $name), $size, $width, '', $attribs);
	}

	/* creates a text-input field for entering Data that will be stored at the $elements Array and shows information from another Element */

	function formInputInfo2($width, $name, $size, $type = 'txt', $attribs = '', $infoname = ''){
		$info = $this->getElement($infoname);
		$infotext = ' (' . (g_l('weClass', '[' . $infoname . ']') != false ? g_l('weClass', '[' . $infoname . ']') : $infoname) . ': ' . $info . ')';
		return $this->formInputField($type, $name, (g_l('weClass', '[' . $name . ']') !== false ? g_l('weClass', '[' . $name . ']') : $name) . $infotext, $size, $width, '', $attribs);
	}

	function formSelect2($width, $name, $table, $val, $txt, $text, $sqlTail = '', $size = 1, $selectedIndex = '', $multiple = false, $onChange = '', array $attribs = array(), $textalign = 'left', $textclass = 'defaultfont', $precode = '', $postcode = '', $firstEntry = '', $gap = 20){
		$vals = array();
		if($firstEntry){
			$vals[$firstEntry[0]] = $firstEntry[1];
		}
		$this->DB_WE->query('SELECT ' . $this->DB_WE->escape($val) . ',' . $this->DB_WE->escape($txt) . ' FROM ' . $this->DB_WE->escape($table) . ' ' . $sqlTail);
		while($this->DB_WE->next_record(MYSQL_ASSOC)){
			$v = $this->DB_WE->f($val);
			$t = $this->DB_WE->f($txt);
			$vals[$v] = $t;
		}
		$vals = we_html_tools::groupArray($vals, false, 1);
		$myname = 'we_' . $this->Name . '_' . $name;



		$ps = $this->$name;

		$pop = $this->htmlSelect($myname . ($multiple ? 'Tmp' : ''), $vals, $size, $ps, $multiple, array_merge(array(
			'onchange' => $onChange . ($multiple ? ";var we_sel='';for(i=0;i<this.options.length;i++){if(this.options[i].selected){we_sel += (this.options[i].value + ',');};};if(we_sel){we_sel=we_sel.substring(0,we_sel.length-1)};this.form.elements['" . $myname . "'].value=we_sel;" : '')
				), $attribs), 'value', $width);

		if($precode || $postcode){
			$pop = '<table border="0" cellpadding="0" cellspacing="0"><tr>' . ($precode ? ('<td>' . $precode . '</td><td>' . we_html_tools::getPixel($gap, 2) . '</td>') : '') . '<td>' . $pop . '</td>' . ($postcode ? ('<td>' . we_html_tools::getPixel($gap, 2) . '</td><td>' . $postcode . '</td>') : '') . '</tr></table>';
		}
		return ($multiple ? $this->htmlHidden($myname, $selectedIndex) : '') . we_html_tools::htmlFormElementTable($pop, $text, $textalign, $textclass);
	}

	function formSelect4($width, $name, $table, $val, $txt, $text, $sqlTail = '', $size = 1, $selectedIndex = '', $multiple = false, $onChange = '', array $attribs = array(), $textalign = 'left', $textclass = 'defaultfont', $precode = '', $postcode = '', $firstEntry = '', $gap = 20){
		$vals = array();
		if($firstEntry){
			$vals[$firstEntry[0]] = $firstEntry[1];
		}
		$this->DB_WE->query('SELECT ' . $this->DB_WE->escape($val) . ',' . $this->DB_WE->escape($txt) . ' FROM ' . $this->DB_WE->escape($table) . ' ' . $sqlTail);
		while($this->DB_WE->next_record(MYSQL_ASSOC)){
			$v = $this->DB_WE->f($val);
			$t = $this->DB_WE->f($txt);
			$vals[$v] = $t;
		}
		$myname = 'we_' . $this->Name . '_' . $name;

		$pop = $this->htmlSelect($myname, $vals, $size, $selectedIndex, $multiple, array_merge(array('onchange' => $onChange), $attribs), 'value', $width);
		if($precode || $postcode){
			$pop = '<table border="0" cellpadding="0" cellspacing="0"><tr>' . ($precode ? ('<td>' . $precode . '</td><td>' . we_html_tools::getPixel($gap, 2) . '</td>') : '') . '<td>' . $pop . '</td>' . ($postcode ? ('<td>' . we_html_tools::getPixel($gap, 2) . '</td><td>' . $postcode . '</td>') : '') . '</tr></table>';
		}
		return we_html_tools::htmlFormElementTable($pop, $text, $textalign, $textclass);
	}

##### NEWSTUFF ####
# public ##################

	public function initByID($ID, $Table = FILE_TABLE, $from = we_class::LOAD_MAID_DB){
		$Table = ($Table ? $Table : FILE_TABLE);

		$this->ID = intval($ID);
		$this->Table = $Table;
		$this->we_load($from);
		$GLOBALS['we_ID'] = $ID; //FIXME: check if we need this !!
		$GLOBALS['we_Table'] = $Table;
		// init Customer Filter !!!!
		if(isset($this->documentCustomerFilter) && defined('CUSTOMER_TABLE')){
			$this->initWeDocumentCustomerFilterFromDB();
		}
	}

	/**
	 * inits weDocumentCustomerFilter from db regarding the modelId
	 * is called from "we_textContentDocument::we_load"
	 * @see we_textContentDocument::we_load
	 */
	function initWeDocumentCustomerFilterFromDB(){
		$this->documentCustomerFilter = we_customer_documentFilter::getFilterOfDocument($this);
	}

	public function we_load(/* $from = we_class::LOAD_MAID_DB */){
		$this->i_getPersistentSlotsFromDB();
	}

	public function we_save(/* $resave = 0 */){
		$this->wasUpdate = $this->ID > 0;
		return $this->i_savePersistentSlotsToDB();
	}

	public function we_publish(/* $DoNotMark = false, $saveinMainDB = true */){
		return true; // overwrite
	}

	public function we_unpublish(/* $DoNotMark = false */){
		return true; // overwrite
	}

	public function we_republish(){
		return true;
	}

	public function we_delete(){
		if(LANGLINK_SUPPORT){
			switch($this->ClassName){
				case 'we_objectFile':
					$deltype = 'tblObjectFile';
					break;
				case 'we_webEditionDocument':
					$deltype = 'tblFile';
					break;
				case 'we_docTypes':
					$deltype = 'tblDocTypes';
					break;
				default:
					$deltype = '';
			}
			$this->DB_WE->query('DELETE FROM ' . LANGLINK_TABLE . " WHERE DocumentTable='" . $deltype . "' AND (DID=" . intval($this->ID) . ' OR LDID=' . intval($this->ID) . ')');
		}
		return $this->DB_WE->query('DELETE FROM ' . $this->DB_WE->escape($this->Table) . ' WHERE ID=' . intval($this->ID));
	}

	protected function i_setElementsFromHTTP(){
		if($_REQUEST){
			// do not set REQUEST VARS into the document
			if(($_REQUEST['we_cmd'][0] == 'switch_edit_page' && isset($_REQUEST['we_cmd'][3])) || ($_REQUEST['we_cmd'][0] == 'save_document' && isset($_REQUEST['we_cmd'][7]) && $_REQUEST['we_cmd'][7] == 'save_document')){
				return true;
			}

			foreach($_REQUEST as $n => $v){
				if(preg_match('#^we_' . $this->Name . '_([^\[]+)$#', $n, $regs) && in_array($regs[1], $this->persistent_slots)){
					$this->$regs[1] = $v;
				}
			}
		}
	}

	protected function i_getPersistentSlotsFromDB($felder = '*'){
		$fields = getHash('SELECT ' . $felder . ' FROM ' . $this->DB_WE->escape($this->Table) . ' WHERE ID=' . intval($this->ID), $this->DB_WE);
		if($fields){
			foreach($fields as $k => $v){
				if($k && in_array($k, $this->persistent_slots)){
					$this->{$k} = $v;
				}
			}
		} else {
			$this->fileExists = 0;
		}
	}

	protected function i_fixCSVPrePost($in){
		if($in){
			return ',' . trim($in, ',') . ',';
		}
		return $in;
	}

	protected function i_savePersistentSlotsToDB($felder = ''){
		$tableInfo = $this->DB_WE->metadata($this->Table);
		$feldArr = $felder ? makeArrayFromCSV($felder) : $this->persistent_slots;
		$fields = array();
		if(!$this->wasUpdate && $this->insertID){
			if(f('SELECT 1 FROM ' . $this->DB_WE->escape($this->Table) . ' WHERE ID=' . intval($this->insertID), '', $this->DB_WE)){
				return false;
			}
		}
		foreach($tableInfo as $info){

			$fieldName = $info['name'];
			if(in_array($fieldName, $feldArr)){
				$val = isset($this->$fieldName) ? $this->$fieldName : '';

				if($fieldName == 'Category'){ // Category-Fix!
					$val = $this->i_fixCSVPrePost($val);
				}
				if($fieldName != 'ID'){
					$fields[$fieldName] = $val;
				}
				if(!$this->wasUpdate && $this->insertID && $fieldName == 'ID'){//for Apps to be able to manipulate Insert-ID
					$fields['ID'] = $this->insertID;
					$this->insertID = 0;
				}
			}
		}
		if(!empty($fields)){
			$where = ($this->wasUpdate ? ' WHERE ID=' . intval($this->ID) : '');
			$ret = (bool) ($this->DB_WE->query(($this->wasUpdate ? 'UPDATE ' : 'INSERT INTO ') . $this->DB_WE->escape($this->Table) . ' SET ' . we_database_base::arraySetter($fields) . $where));
			$this->ID = ($this->wasUpdate ? $this->ID : $this->DB_WE->getInsertId());
			return $ret;
		}
		return false;
	}

	public function i_descriptionMissing(){
		return false;
	}

	function setDocumentControlElements(){
		//	function is overwritten in we_webEditionDocument
	}

	function executeDocumentControlElements(){
		//	function is overwritten in we_webEditionDocument
	}

	function isValidEditPage($editPageNr){

		return (is_array($this->EditPageNrs) ?
				in_array($editPageNr, $this->EditPageNrs) :
				false);
	}

	protected function updateRemoteLang($db, $id, $lang, $type){
		//overwrite if needed <= diese verwenden!
	}

	/**
	 * If documents, objects, folders and docTypes are saved and there is no LANGLINK_SUPPORT we must check, whether there is a change of language:
	 * if so, we must delete eventual netries in tblLangLink (entered before LANGLINK_SUPPORT wa stopped. <= TODO: Merge this with next method!
	 */
	protected function checkRemoteLanguage($table, $isfolder = false){
		if(isset($_REQUEST['we_' . $this->Name . '_Language']) && $_REQUEST['we_' . $this->Name . '_Language'] != ''){
			$type = stripTblPrefix($table);
			$isobject = ($type == 'tblObjectFile') ? 1 : 0;
			$type = ($isfolder && $isobject) ? 'tblFile' : ($isobject ? 'tblObjectFile' : $type);
			$newLang = $_REQUEST['we_' . $this->Name . '_Language'];

			$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND IsObject = ' . intval($isobject) . ' AND IsFolder = ' . intval($isfolder) . ' AND DID=' . intval($this->ID));
			//$langChange = false;
			$delete = false;
			while($this->DB_WE->next_record()){
				$delete = ($this->DB_WE->Record['DLocale'] != $newLang) ? true : false;
			}
			if($delete){
				$this->DB_WE->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE DID = ' . intval($this->ID) . ' AND DocumentTable="' . $type . '" AND IsFolder = ' . intval($isfolder) . ' AND IsObject = ' . intval($isobject));
				if(!$isfolder){
					$this->DB_WE->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE LDID = ' . intval($this->ID) . ' AND DocumentTable="' . $type . '" AND IsFolder = 0 AND IsObject = ' . intval($isobject));
				}
			}
		}
	}

	/**
	 * Before writing LangLinks to the db, we must check the Document-Locale: if it has changed, we must update or clear
	 * existing LangLinks from and to this document.
	 */
	protected function setLanguageLink($LangLinkArray, $type, $isfolder = false, $isobject = false){
		if(!(LANGLINK_SUPPORT)){
			return true;
		}
		$newLang = $oldLang = '';
		if(isset($_REQUEST['we_' . $this->Name . '_Language']) && $_REQUEST['we_' . $this->Name . '_Language'] != ''){
			$newLang = $_REQUEST['we_' . $this->Name . '_Language'];
			$db = new DB_WE();
			$documentTable = ($type == 'tblObjectFile') ? 'tblObjectFiles' : $type;
			$ownDocumentTable = ($isfolder && $isobject) ? FILE_TABLE : addTblPrefix($documentTable);
			$origLinks = array();

			if(!$isfolder){
				$oldLang = f('SELECT Language FROM ' . $ownDocumentTable . ' WHERE ID=' . intval($this->ID), 'Language', $db);
				if($newLang != $oldLang){// language changed
					// what langs where linked before document-language changed?
					$origLangs = array();
					$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . ' AND IsObject = ' . intval($isobject) . ' AND IsFolder = ' . intval($isfolder));
					while($this->DB_WE->next_record()){
						$origLangs[] = $this->DB_WE->Record['Locale'];
						$origLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
					}
					// because of UNIQUE-Indexes we do first delete obsolete entries in tblLangLink
					// => after optimizing executeSetLanguageLink() this will be obsolete!
					$this->DB_WE->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE (DID=' . intval($this->ID) . ' OR LDID=' . intval($this->ID) . ') AND IsFolder = 0 AND IsObject = ' . intval($isobject) . ' AND DocumentTable="' . $type . '"');

					// links FROM folders to the actual we_document/object must be updated right here.
					// if updating leads to conflict, we must delete a link
					$DB_WE2 = new DB_WE();
					$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE LDID=' . intval($this->ID) . ' AND IsFolder = 1');
					while($this->DB_WE->next_record()){
						$deleteIt = ($this->DB_WE->Record['DLocale'] == $newLang);
						if(!$deleteIt){
							$DB_WE2->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE DID=' . $this->DB_WE->Record['DID'] . ' AND IsFolder = 1;');
							while($DB_WE2->next_record()){
								$deleteIt = ($DB_WE2->Record['Locale'] == $newLang) ? true : $deleteIt;
							}
						}
						if($deleteIt){
							$DB_WE2->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE LDID = ' . $this->DB_WE->Record['LDID'] . ' AND DID = ' . $this->DB_WE->Record['DID'] . ' AND IsFolder = 1');
						} else {
							$DB_WE2->query('UPDATE ' . LANGLINK_TABLE . ' SET LOCALE="' . $newLang . '" WHERE LDID = ' . $this->DB_WE->Record['LDID'] . ' AND DID=' . $this->DB_WE->Record['DID'] . ' AND IsFolder = 1');
						}
					}

					// if there is no conflict we can set new links and call prepareSetLanguageLinks()
					if(!in_array($newLang, $origLangs)){
						return ($this->prepareSetLanguageLink($LangLinkArray, $origLinks, true, $newLang, $type, $isfolder, $isobject, $ownDocumentTable)) ? true : false;
					} else {
						$we_responseText = g_l('weClass', '[languageLinksLocaleChanged]'); //,$we_doc->Path
						$_js = we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_NOTICE);
						print we_html_element::htmlDocType() . we_html_element::htmlHtml(we_html_element::htmlHead(we_html_element::jsElement($_js)));
						return true;
					}
				} else {//default case: there was now change of page language. Loop method call to another method, preparing LangLinks
					return ($this->prepareSetLanguageLink($LangLinkArray, $origLinks, false, $oldLang, $type, $isfolder, $isobject, $ownDocumentTable)) ? true : false;
				}
			} else {//isfolder
				if(f('SELECT DLocale FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND IsObject = ' . intval($isobject) . ' AND IsFolder = 1 AND DID=' . intval($this->ID), 'DLocale', $this->DB_WE) != $newLang){
					$this->DB_WE->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE DID = ' . intval($this->ID) . ' AND DocumentTable = "tblFile" AND IsFolder = 1 AND IsObject = ' . intval($isobject));
				}
				return ($this->prepareSetLanguageLink($LangLinkArray, $origLinks, false, $newLang, $type, $isfolder, $isobject, $ownDocumentTable)) ? true : false;
			}
		}
	}

	/**
	 * In this method the links of $LangLinkArray are testet twice:
	 * 1) We only write new or changed LangLinks to db, if LangLink-Locale and Locale of the targe-document/object fit together.
	 * 2) In recursive-mode we only one document/object to another, if their respective link-chains are not in conflict.
	 */
	private function prepareSetLanguageLink($LangLinkArray, $origLinks, $langChange = false, $ownLocale, $type, $isfolder = false, $isobject = false, $ownDocumentTable){
		$documentTable = ($type == 'tblObjectFile') ? 'tblObjectFiles' : $type; // we could take these  from setLanguageLink()...
		$ownDocumentTable = ($isfolder && $isobject) ? FILE_TABLE : addTblPrefix($documentTable);

		if(in_array(0, $LangLinkArray) || in_array('', $LangLinkArray)){
			if(!$langChange){
				$origLinks = array();
				$this->DB_WE->query('SELECT Locale,LDID FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . ' AND IsObject = ' . intval($isobject) . ' AND IsFolder = ' . intval($isfolder));
				while($this->DB_WE->next_record()){
					$origLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
				}
			}
			$tmpLangLinkArray = array();
			foreach($LangLinkArray as $locale => $LDID){

				if(!($LDID == '' || $LDID == 0 || $LDID == -1)){
					$tmpLangLinkArray[$locale] = $LDID;
				} elseif(array_key_exists($locale, $origLinks)){
					$tmpLangLinkArray[$locale] = -1;
				}
			}
			$LangLinkArray = $tmpLangLinkArray;
		}

		$k = 0;
		foreach($LangLinkArray as $locale => $LDID){
			$k = ($locale == $ownLocale) ? $k : $k + 1;
			$newOrChanged = false;
			if(($actualLDID = f('SELECT LDID FROM ' . LANGLINK_TABLE . " WHERE Locale='" . $locale . "' AND DID=" . intval($this->ID) . " AND DocumentTable='" . $type . "' AND IsObject = " . intval($isobject) . " AND IsFolder = " . intval($isfolder), 'LDID', $this->DB_WE))){
				if($actualLDID != $LDID){
					$newOrChanged = true; //changed
				}
			} else {
				$newOrChanged = true; //new
			}
			if(($newOrChanged || $langChange) && !($LDID == -1)){

				// from Folders, links lead only to documents, never to objects
				//$fileTable = $isfolder ? FILE_TABLE : ($isobject ? OBJECT_FILES_TABLE : FILE_TABLE);

				if(($fileLang = f('SELECT Language FROM ' . addTblPrefix($documentTable) . ' WHERE ID=' . intval($LDID), '', $this->DB_WE))){
					if($fileLang != $locale){
						$we_responseText = sprintf(g_l('weClass', '[languageLinksLangNotok]'), $locale, $fileLang, $locale);
						$_js = we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_NOTICE);
						print we_html_element::htmlDocType() . we_html_element::htmlHtml(we_html_element::htmlHead(we_html_element::jsElement($_js)));
						return true;
					} else {
						if(!$isfolder){
							$setThisLink = true;
							$actualLangs = $actualLinks = array();
							$this->DB_WE->query('SELECT Locale,LDID FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . " AND IsObject = " . intval($isobject) . " AND IsFolder = " . intval($isfolder));
							while($this->DB_WE->next_record()){
								$actualLangs[] = $this->DB_WE->Record['Locale'];
								$actualLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
							}
							$actualLangs[] = $ownLocale;

							$targetLangs = $targetLinks = array();
							$this->DB_WE->query('SELECT Locale,LDID FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($LDID) . " AND IsObject = " . intval($isobject) . " AND IsFolder = " . intval($isfolder));
							while($this->DB_WE->next_record()){
								$targetLangs[] = $this->DB_WE->Record['Locale'];
								$targetLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
							}

							if(count($actualLangs) > 1 || count($targetLangs) > 0){
								$intersect = array_intersect($actualLangs, $targetLangs);
								$setThisLink = count($intersect) > 0 ? false : true;
							}

							if(!$newOrChanged){
								$setThisLink = true;
							}

							if($setThisLink){
								// instead of modifying db-Enries, we delete them and create new ones
								if(isset($actualLinks[$locale]) && $actualLinks[$locale] > 0){
									$deleteObsoleteArray = $actualLinks;
									$deleteObsoleteArray[$locale] = -1;
									$this->executeSetLanguageLink($deleteObsoleteArray, $type, $isfolder, $isobject);
								}

								$preparedLinkArray = $actualLinks;
								$preparedLinkArray[$locale] = $LDID;
								foreach($targetLinks as $targetLocale => $targetLDID){
									$preparedLinkArray[$targetLocale] = $targetLDID;
								}
								$this->executeSetLanguageLink($preparedLinkArray, $type, $isfolder, $isobject);
							} else {
								$we_responseText = sprintf(g_l('weClass', '[languageLinksConflicts]'), $locale);
								$_js = we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_NOTICE);
								print we_html_element::htmlDocType() . we_html_element::htmlHtml(we_html_element::htmlHead(we_html_element::jsElement($_js)));
								return true;
							}
						} else {//!isfolder
							$double = f('SELECT DID FROM ' . LANGLINK_TABLE . " WHERE DocumentTable = '" . $type . "' AND DLocale = '" . $ownLocale . "' AND Locale = '" . $locale . "' AND LDID = " . intval($LDID) . " AND IsObject = " . intval($isobject) . " AND IsFolder = 1", 'DID', $this->DB_WE);

							if($double == 0){
								$actualLinks = array();
								$this->DB_WE->query('SELECT Locale,LDID FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . " AND IsObject = " . intval($isobject) . " AND IsFolder = " . intval($isfolder));
								while($this->DB_WE->next_record()){
									$actualLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
								}
								$actualLinks[$locale] = $LDID;
								$this->executeSetLanguageLink($actualLinks, $type, $isfolder, $isobject);
							} else {
								$we_responseText = sprintf(g_l('weClass', '[languageLinksConflicts]'), $locale);
								$_js = we_message_reporting::getShowMessageCall($we_responseText, we_message_reporting::WE_MESSAGE_NOTICE);
								print we_html_element::htmlDocType() . we_html_element::htmlHtml(we_html_element::htmlHead(we_html_element::jsElement($_js)));
								return true;
							}
						}
					}
				}
			}// end of new or changed link
			else {//delete links
				$actualLinks = array();
				$this->DB_WE->query('SELECT Locale,LDID FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . " AND IsObject = " . intval($isobject) . " AND IsFolder = " . intval($isfolder));
				while($this->DB_WE->next_record()){
					$actualLinks[$this->DB_WE->Record['Locale']] = $this->DB_WE->Record['LDID'];
				}
				$preparedLinkArray = $actualLinks;
				$preparedLinkArray[$locale] = $LDID;
				$this->executeSetLanguageLink($preparedLinkArray, $type, $isfolder, $isobject);
			}
		}//foreach
		return true;
	}

	//FIXME: in this method tblLangLink.ID is used and a lot of things are obsolete => to be cleaned in 6.3.1
	private function executeSetLanguageLink($LangLinkArray, $type, $isfolder = false, $isobject = false){
		$db = new DB_WE();
		if(is_array($LangLinkArray)){
			$orig = array();
			$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($this->ID) . ' AND IsFolder=' . intval($isfolder) . ' AND IsObject=' . intval($isobject));
			while($this->DB_WE->next_record()){
				$orig[] = $this->DB_WE->Record;
			}
			$max = count($orig);
			if(!$isfolder){//folders never have backlinks BUT the document linked to the folder CAN have them if linked to another document
				for($j = 0; $j < $max; $j++){
					$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="' . $type . '" AND DID=' . intval($orig[$j]['LDID']));
					while($this->DB_WE->next_record()){
						$orig[] = $this->DB_WE->Record;
					}
				}
			}

			foreach($LangLinkArray as $locale => $LDID){ //obsolete if we call executeSetLanguageLink with only the link to bechanged (instead of whole $LangLinkArray)
				if(($ID = f('SELECT ID FROM ' . LANGLINK_TABLE . " WHERE DocumentTable='" . $type . "' AND DID=" . intval($this->ID) . " AND Locale='" . $locale . "' AND isFolder='" . intval($isfolder) . "' AND IsObject=" . intval($isobject), 'ID', $this->DB_WE))){
					$this->DB_WE->query('UPDATE ' . LANGLINK_TABLE . ' SET LDID=' . intval($LDID) . ",DLocale='" . $this->Language . "' WHERE ID=" . intval($ID) . ' AND DocumentTable="' . $type . '"');
				} elseif($locale != $this->Language && $LDID > 0){
					$this->DB_WE->query('INSERT INTO ' . LANGLINK_TABLE . ' SET DID=' . intval($this->ID) . ",DLocale='" . $this->Language . "',IsFolder=" . intval($isfolder) . ", IsObject=" . intval($isobject) . ", LDID=" . intval($LDID) . ", Locale='" . $locale . "', DocumentTable='" . $type . "'");
				}

				if(!$isfolder && $LDID && $LDID != $this->ID){
					$q = '';
					if(($ID = f('SELECT ID FROM ' . LANGLINK_TABLE . " WHERE DocumentTable='" . $type . "' AND DID=" . intval($LDID) . " AND Locale='" . $this->Language . "' AND IsObject=" . intval($isobject), 'ID', $this->DB_WE))){
						if($LDID > 0){
							$this->DB_WE->query('UPDATE ' . LANGLINK_TABLE . ' SET DID=' . intval($LDID) . ", DLocale='" . $locale . "', LDID=" . intval($this->ID) . ",Locale='" . $this->Language . "' WHERE ID=" . intval($ID) . ' AND DocumentTable="' . $type . '"');
						} elseif($LDID < 0){// here we could delete istead of update (and then delete later...)
							$this->DB_WE->query('UPDATE ' . LANGLINK_TABLE . ' SET DID=' . intval($LDID) . ", DLocale='" . $locale . "', LDID=0,Locale='" . $this->Language . "' WHERE ID=" . intval($ID) . ' AND DocumentTable="' . $type . '"');
						}
					} elseif($LDID > 0){
						$this->DB_WE->query('INSERT INTO ' . LANGLINK_TABLE . ' SET DID=' . intval($LDID) . ", DLocale='" . $locale . "', LDID=" . intval($this->ID) . ", Locale='" . $this->Language . "', IsObject=" . intval($isobject) . ", DocumentTable='" . $type . "'");
					}
				}

				if(!$isfolder && $LDID < 0 && $LDID != $this->ID){
					if($LDID > 0){// never happens!
						$this->DB_WE->query('REPLACE INTO ' . LANGLINK_TABLE . ' SET DID=' . intval($LDID) . ", DLocale='" . $locale . "', LDID=" . intval($this->ID) . ", Locale='" . $this->Language . "', IsObject=" . intval($isobject) . ", DocumentTable='" . $type . "'");
					}
				}
			}//foreach

			if(!$isfolder){
				foreach($LangLinkArray as $locale => $LDID){
					if($LDID > 0){
						$rows = array();
						$this->DB_WE->query('SELECT * FROM ' . LANGLINK_TABLE . ' WHERE  DID=' . intval($this->ID) . "  AND DocumentTable='" . $type . "' AND IsObject=" . intval($isobject));
						while($this->DB_WE->next_record()){
							$rows[] = $this->DB_WE->Record;
						}
						if(count($rows) > 1){
							for($i = 0; $i < count($rows); $i++){
								$j = ($i + 1) % count($rows);
								if($rows[$i]['LDID'] && $rows[$j]['LDID']){
									$this->DB_WE->query('REPLACE INTO ' . LANGLINK_TABLE . ' SET DID=' . intval($rows[$i]['LDID']) . ", DLocale='" . $rows[$i]['Locale'] . "', LDID=" . intval($rows[$j]['LDID']) . ", Locale='" . $rows[$j]['Locale'] . "', IsObject=" . intval($isobject) . ", DocumentTable='" . $type . "'");
									$this->DB_WE->query('REPLACE INTO ' . LANGLINK_TABLE . ' SET DID=' . intval($rows[$j]['LDID']) . ", DLocale='" . $rows[$j]['Locale'] . "', LDID=" . intval($rows[$i]['LDID']) . ", Locale='" . $rows[$i]['Locale'] . "', IsObject=" . intval($isobject) . ", DocumentTable='" . $type . "'");
								}
							}
						}
					}
					if($LDID < 0){
						foreach($orig as $origrow){
							if($origrow['DLocale'] == $locale){
								$this->DB_WE->query('SELECT ID FROM ' . LANGLINK_TABLE . ' WHERE  DID=' . intval($origrow['DID']) . " AND DLocale='" . $locale . "' AND DocumentTable='" . $type . "' AND IsObject=" . intval($isobject));
								$ids = array();
								while($this->DB_WE->next_record()){
									$ids[] = $this->DB_WE->Record['ID'];
								}
								if(!empty($ids)){
									$this->DB_WE->query('UPDATE ' . LANGLINK_TABLE . ' SET LDID=0 WHERE ID IN(' . implode(',', $ids) . ') AND DocumentTable="' . $type . '"');
								}
							}
							if($origrow['Locale'] == $locale){
								$this->DB_WE->query('SELECT ID FROM ' . LANGLINK_TABLE . ' WHERE  LDID=' . intval($origrow['LDID']) . " AND Locale='" . $locale . "' AND DocumentTable='" . $type . "' AND IsObject=" . intval($isobject));
								$ids = array();
								while($this->DB_WE->next_record()){
									$ids[] = $this->DB_WE->Record['ID'];
								}
								if(!empty($ids)){
									$this->DB_WE->query('UPDATE ' . LANGLINK_TABLE . ' SET LDID=0 WHERE ID IN(' . implode(',', $ids) . ') AND DocumentTable="' . $type . '"');
								}
							}
						}
					}
				}
			}
			$db->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE DID=0 OR LDID=0');
		}
	}

	/*	 * returns error-messages recorded during an operation, currently only save is used */

	public function getErrMsg(){
		return ($this->errMsg != '' ? '\n' . str_replace("\n", '\n', $this->errMsg) : '');
	}

	//FIXME: this is temporary
	public function getDBf($field){
		return $this->DB_WE->f($field);
	}

}
