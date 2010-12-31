<?php
/**
 * webEdition CMS
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


include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/listview/"."listviewBase.class.php");
include_once(WE_OBJECT_MODULE_DIR . "we_objectFile.inc.php");

/**
* class    we_listview_multiobject
* @desc    class for tag <we:listview type="multiobject">
*
*/

class we_listview_multiobject extends listviewBase {

	var $DB_WE2; /* 2nd DB Object */
	var $classID = "";  /* ID of a class */
	var $objects = "";  /* Comma sepearated list of all objetcs to show in this listview */
	var $triggerID = 0; /* ID of a document which to use for displaying thr detail page */
	var $condition = ""; /* condition string (like SQL) */
	var $ClassName = "we_listview_multiobject";
	var $Path=""; /* internal: Path of document which to use for displaying thr detail page */
	var $IDs=array();
	var $searchable = true;
	var $Record = array();
	var $customerFilterType = 'off';
	var $languages = ""; //string of Languages, separated by ,
	var $seourls = false;
	var $hidedirindex = false;
	
	/**
	 * we_listview_multiobject()
	 * @desc    constructor of class
	 *
	 * @param   name          string - name of listview
	 * @param   rows          integer - number of rows to display per page
	 * @param   offset        integer - start offset of first page
	 * @param   order         string - field name(s) to order by
	 * @param   desc          boolean - set to true, if order should be descendend
	 * @param   cats          string - comma separated categories
	 * @param   catOr         boolean - set to true if it should be an "OR condition"
	 * @param   condition     string - condition string (like SQL)
	 * @param   triggerID     integer - ID of a document which to use for displaying the detail page
	 * @param   cols          ??
	 * @param   seeMode       boolean - value if objects shall be accessible in seeMode (default true)
	 * @param   searchable 	  boolean - if false then show also documents which are not marked as searchable
	 * @param	unknown_type  $calendar
	 * @param	unknown_type  $datefield
	 * @param	unknown_type  $date
	 * @param	unknown_type  $weekstart
	 * @param	string        $categoryids
	 *
	 */
	function we_listview_multiobject($name="0", $rows=9999999, $offset=0, $order="", $desc=false, $cats="", $catOr="", $condition="", $triggerID="",$cols="", $seeMode=true, $searchable=true, $calendar="", $datefield="", $date="", $weekstart="", $categoryids='', $customerFilterType='off',$docID=0,$languages='',$hidedirindex=false,$seourls=false){

		listviewBase::listviewBase($name, $rows, $offset, $order, $desc, $cats, $catOr, 0, $cols, $calendar, $datefield, $date, $weekstart, $categoryids, $customerFilterType);
		if(isset($GLOBALS['we_lv_array']) && sizeof($GLOBALS['we_lv_array']) > 1) {
			$parent_lv = $GLOBALS['we_lv_array'][(sizeof($GLOBALS['we_lv_array'])-1)];
			$data = unserialize($parent_lv->DB_WE->Record['we_'.$name]);
		} elseif(isset($GLOBALS["lv"])) {
			if(isset($GLOBALS["lv"]->object)) {
				$data = unserialize($GLOBALS['lv']->object->DB_WE->Record['we_'.$name]);
			} else {
				if ($GLOBALS["lv"]->ClassName == 'we_listview_shoppingCart') {
					$data = unserialize($GLOBALS['lv']->Record[$name]);
				} else {
					$data = unserialize($GLOBALS['lv']->DB_WE->Record['we_'.$name]);
				}
			}
		} else {
			$data = unserialize($GLOBALS['we_doc']->getElement($name));
		}

		if (!$data) {
			return;
		}
		// remove not set values
		$temp = $data['objects'];
		$empty = array_keys($temp, "");
		$objects = array();
		foreach($temp as $key => $val) {
			if(!in_array($key, $empty)) {
				array_push($objects, $val);
			}
		}

		$this->DB_WE2    = new DB_WE();
		$this->classID   = $data['class'];
		$this->objects   = $objects;

		$this->triggerID = $triggerID;
		$this->condition = $condition;
		$this->languages = $languages;
		$this->languages = $this->languages ? $this->languages : (isset($GLOBALS["we_lv_languages"]) ? $GLOBALS["we_lv_languages"] : "");
		$this->seourls=$seourls;
		$this->hidedirindex=$hidedirindex;

		$_obxTable = OBJECT_X_TABLE.$this->classID;
		
		if ($this->languages !=''){
			$where_lang = ' AND (';
			$langArray = makeArrayFromCSV($this->languages);
			$where_lang .= $_obxTable.".OF_Language = '".$langArray[0]."' ";
			for ($i = 1; $i < count($langArray); $i++) {
    			$where_lang .= "OR ".$_obxTable.".OF_Language = '".$langArray[$i]."' ";
			}

			$where_lang .= ' ) ';
		} else {
			$where_lang = '';
		}
		
		$this->seeMode   = $seeMode;	//	edit objects in seeMode
		$this->searchable = $searchable;
		$this->docID = $docID; //Bug #3720
		$this->Record    = array();

		$this->condition = $this->condition ? $this->condition : (isset($GLOBALS["we_lv_condition"]) ? $GLOBALS["we_lv_condition"] : "");
		$this->languages = $this->languages ? $this->languages : (isset($GLOBALS["we_lv_languages"]) ? $GLOBALS["we_lv_languages"] : "");



		if($this->desc && (!eregi(".+ desc$",$this->order))){
			$this->order .= " DESC";
		}

		if($this->triggerID){
			$this->Path = id_to_path($this->triggerID,FILE_TABLE,$this->DB_WE);
		}else{
			$this->Path = (isset($GLOBALS["we_doc"]) ? $GLOBALS["we_doc"]->Path : '');
		}

		// IMPORTANT for seeMode !!!! #5317
		$this->LastDocPath = '';
		if (isset($_SESSION['last_webEdition_document'])) {
			$this->LastDocPath = $_SESSION['last_webEdition_document']['Path'];
		}

		$order = "";

		$matrix = array();

		$join = $this->fillMatrix($matrix,$this->classID,$this->DB_WE);

		$calendar_select="";
		$calendar_where="";
		if($calendar!="") $this->fetchCalendar($this->condition,$calendar_select,$calendar_where,$matrix);

		$sqlParts = $this->makeSQLParts($matrix,$this->classID,$this->order,$this->condition);

		if (isset($GLOBALS['we_doc'])) {
			$pid_tail = makePIDTail($GLOBALS["we_doc"]->ParentID,$this->classID,$this->DB_WE,$GLOBALS["we_doc"]->Table);
		} else {
			$pid_tail = '1';
		}

		$cat_tail = getCatSQLTail($this->cats,OBJECT_X_TABLE.$this->classID,$this->catOr,$this->DB_WE,"OF_Category", true, $this->categoryids);
		$weDocumentCustomerFilter_tail = "";
		if ($this->customerFilterType != 'off' && defined("CUSTOMER_FILTER_TABLE")) {
			$weDocumentCustomerFilter_tail = weDocumentCustomerFilter::getConditionForListviewQuery($this);

		}

		if($sqlParts["tables"]){
			$q = "SELECT " . OBJECT_X_TABLE .  $this->classID . ".ID as ID $calendar_select FROM ".$sqlParts["tables"]." WHERE ". OBJECT_X_TABLE . $this->classID . ".OF_ID IN (".implode(",", $this->objects).") AND ".($this->searchable ? " ". OBJECT_X_TABLE . $this->classID . ".OF_IsSearchable=1 AND" : "")." ".$pid_tail." AND " . OBJECT_X_TABLE .  $this->classID.".OF_ID != 0 ".($join ? " AND ($join) " : "").$cat_tail." ".($sqlParts["publ_cond"] ? (" AND ".$sqlParts["publ_cond"]) : "")." ".($sqlParts["cond"] ? (" AND (".$sqlParts["cond"].") ") : "").$calendar_where.$weDocumentCustomerFilter_tail.$sqlParts['groupBy'];
			$this->DB_WE->query($q);

			$mapping = array(); // KEY = ID -> VALUE = ROWID
			$i = 0;
			while($this->DB_WE->next_record()){
				$mapping[$this->DB_WE->Record["ID"]] = $i;
				$i++;
				array_push($this->IDs,$this->DB_WE->f("ID"));
				if($calendar!=""){
					$this->calendar_struct["storage"][$this->DB_WE->f("ID")]=(int)$this->DB_WE->f("Calendar");
				}
			}

			if($this->order == "") {
				$this->anz_all = sizeof($this->objects);

			} else {
				$this->anz_all = 0;
				$count = array_count_values($this->objects);
				foreach($mapping as $objid => $rowid) {
					if (isset($count[$objid])) {
						for($i = 0; $i < $count[$objid]; $i++) {
							$this->anz_all++;
						}
					}
				}

			}

			$q = "SELECT ".$sqlParts["fields"].$calendar_select." FROM ".$sqlParts["tables"]." WHERE  ". OBJECT_X_TABLE . $this->classID . ".OF_ID IN (".implode(",", $this->objects).") AND ".($this->searchable ? " ". OBJECT_X_TABLE . $this->classID . ".OF_IsSearchable=1 AND" : "")." ".$pid_tail.$where_lang." AND " . OBJECT_X_TABLE . $this->classID.".OF_ID != 0 ".($join ? " AND ($join) " : "").$cat_tail.$weDocumentCustomerFilter_tail." ".($sqlParts["publ_cond"] ? (" AND ".$sqlParts["publ_cond"]) : "")." ".($sqlParts["cond"] ? (" AND (".$sqlParts["cond"].") ") : "").$calendar_where.$sqlParts['groupBy'].$sqlParts["order"].(($rows > 0 && $this->order != "") ? (" limit ".$this->start.",".$this->rows) : "");
			
			
			$this->DB_WE->query($q);

			$mapping = array(); // KEY = ID -> VALUE = ROWID
			$i = 0;
			while($this->DB_WE->next_record()) {
				$mapping[$this->DB_WE->Record["OF_ID"]] = $i;
				$i++;
			}

			if($this->order == "") {
				for($i = $offset; $i < min($offset+$rows, sizeof($this->objects)); $i++) {
					if(in_array($this->objects[$i], array_keys($mapping))) {
						array_push($this->Record, $mapping[$this->objects[$i]]);
					}
				}

			} else {
				$count = array_count_values($this->objects);
				foreach($mapping as $objid => $rowid) {
					for($i = 0; $i < $count[$objid]; $i++) {
						array_push($this->Record, $rowid);
					}
				}

			}
			$this->anz = sizeof($this->Record);

		}else{
			$this->anz_all = 0;
			$this->anz = 0;
		}
		if($calendar!="") {
			$this->postFetchCalendar();
		}
	}

	function tableInMatrix($matrix,$table){
		if(OBJECT_X_TABLE.$this->classID == $table) return true;
		foreach($matrix as $foo){
			if($foo["table"] == $table) return true;
		}
		return false;
	}

	function fillMatrix(&$matrix,$classID,$db=""){
		if(!$db) $db = new DB_WE();
		$table = OBJECT_X_TABLE.$classID;
		$joinWhere = "";
		$tableInfo = we_objectFile::getSortedTableInfo($classID,true,$db);
		foreach($tableInfo as $fieldInfo){
			if(preg_match('/(.+?)_(.*)/',$fieldInfo["name"],$regs)){
				$type = $regs[1];
				$name = $regs[2];
				if($type == "object" && $name != $this->classID){
					if(!isset($matrix["we_object_".$name]["type"]) || !$matrix["we_object_".$name]["type"]){
						$matrix["we_object_".$name]["type"] = $type;
						$matrix["we_object_".$name]["table"] = $table;
						$matrix["we_object_".$name]["classID"] = $classID;
						$foo = $this->fillMatrix($matrix,$name,$db);
						$joinWhere .= " ".OBJECT_X_TABLE.$classID.".object_".$name."=". OBJECT_X_TABLE.$name.".OF_ID AND ".($foo ? "$foo AND " : "");
					}
				}else{
					$matrix[$name]["type"] = $type;
					$matrix[$name]["table"] = $table;
					$matrix[$name]["classID"] = $classID;
				}
			}
		}
		return ereg_replace('^(.*)AND $','\1',$joinWhere);
	}


	function encodeEregString($in){

		$out = "";
		for($i=0;$i<strlen($in);$i++){
			$out .= "&".ord(substr($in,$i,1)).";";
		}
			return "'".$out."'";
	}

	function decodeEregString($in){
		return "'".preg_replace("/&([^;]+);/e","chr('\\1')",$in)."'";
	}

	function makeSQLParts($matrix,$classID,$order,$cond){
		$out = array();
		$from = array();
		$orderArr = array();
		$descArr = array();
		$ordertmp = array();

		$cond = str_replace('&gt;','>',$cond);
		$cond = str_replace('&lt;','<',$cond);

		$cond = " ".preg_replace("/'([^']*)'/e","\$this->encodeEregString('\\1')",$cond)." ";


		if($order && ($order != "random()")){
			$foo = makeArrayFromCSV($order);
			foreach($foo as $f){
				$g = explode(" ",trim($f));
				array_push($orderArr,$g[0]);
				if(isset($g[1]) && strtolower(trim($g[1])) == "desc"){
					array_push($descArr,1);
				}else{
					array_push($descArr,0);
				}
			}
		}

		//get Metadata for class (default title, etc.)
		//BugFix #4629
		$_fieldnames = getHash("SELECT DefaultDesc,DefaultTitle,DefaultKeywords FROM " .OBJECT_TABLE . " WHERE ID='".$classID."'",$this->DB_WE);
		$_selFields = "";
		foreach($_fieldnames as $_key => $_val) {
			if(empty($_val) || $_val=='_') // bug #4657
				continue;
			if (!is_numeric($_key)) {
				if ($_val && $_key == "DefaultDesc") {
					$_selFields .= OBJECT_X_TABLE . $classID .'.'. $_val . " as we_Description,";
				} else if ($_key == "DefaultTitle") {
					$_selFields .= OBJECT_X_TABLE . $classID .'.'. $_val . " as we_Title,";
				} else if ($_val && $_key == "DefaultKeywords") {
					$_selFields .= OBJECT_X_TABLE . $classID .'.'. $_val . " as we_Keywords,";
				}
			}
		}


		$f = OBJECT_X_TABLE . $classID . ".ID as ID," . OBJECT_X_TABLE . $classID . ".OF_Templates as OF_Templates," . OBJECT_X_TABLE . $classID . ".OF_ID as OF_ID," . OBJECT_X_TABLE . $classID . ".OF_Category as OF_Category," . OBJECT_X_TABLE . $classID . ".OF_Text as OF_Text,". OBJECT_X_TABLE . $classID . ".OF_Url as OF_Url," . OBJECT_X_TABLE . $classID . ".OF_Language as OF_Language,";
		$f.=$_selFields;
		foreach($matrix as $n=>$p){
			$n2 = $n;
			if(substr($n,0,10) =="we_object_"){
				$n = substr($n,10);
			}
			$f .= $p["table"].".".$p["type"]."_".$n." as we_".$n2.",";
			array_push($from,$p["table"]);
			if(in_array($n,$orderArr)){
				$pos = getArrayKey($n,$orderArr);
				$ordertmp[$pos] = $p["table"].".".$p["type"]."_".$n.($descArr[$pos] ? " DESC" : "");
			}
			$cond = preg_replace("/([\!\=%&\(\*\+\.\/<>|~ ])$n([\!\=%&\)\*\+\.\/<>|~ ])/","$1".$p["table"].".".$p["type"]."_".$n."$2",$cond);
		}

		$cond = preg_replace("/'([^']*)'/e","\$this->decodeEregString('\\1')",$cond);

		ksort($ordertmp);
		if(	$order == "we_id" || $order == "we_filename" || $order == "we_published"){

				$order = str_replace("we_id",OBJECT_X_TABLE . $classID . ".OF_ID",$order);
				$order = str_replace("we_filename",OBJECT_X_TABLE . $classID . ".OF_Text",$order);
				$order = str_replace("we_published",OBJECT_X_TABLE . $classID . ".OF_Published",$order);

				$order = " ORDER BY $order ".($this->desc ? " DESC" : "");
		}else if($order == "random()"){
			$order = " ORDER BY RANDOM ";
		}else{
			$order = "";
			$order = makeCSVFromArray($ordertmp);
			if($order){
				$order = " ORDER BY $order ";
			}
		}
		$tb = array();
		$from = array_unique($from);
		foreach($from as $val){
			array_push($tb,$val);
		}

		$out["fields"] = ereg_replace('^(.*),$','\1',$f);
		if($order==" ORDER BY RANDOM "){
			$out["fields"] .= ", RAND() as RANDOM ";
		}
		$out["order"] = $order;
		$out["tables"] = makeCSVFromArray($tb);
		if (count($tb) > 1) {
			$out["groupBy"] = " GROUP BY " . OBJECT_X_TABLE . $classID . ".ID ";
		} else {
			$out["groupBy"] = "";
		}
		$out["publ_cond"] = "";
		foreach($tb as $t){
			$out["publ_cond"] .= " ( $t.OF_Published > 0 OR $t.OF_ID = 0) AND ";
		}
		$out["publ_cond"] = ereg_replace('^(.*)AND $','\1',$out["publ_cond"]);
		if($out["publ_cond"]){
			$out["publ_cond"]  = " ( ".$out["publ_cond"] ." ) ";
		}
		$out["cond"] = trim($cond);
		return $out;
	}


	function next_record(){

		$fetch = false;
		if($this->calendar_struct["calendar"] != "") {
			if($this->count < $this->anz){
				listviewBase::next_record();
				$fetch=$this->calendar_struct["forceFetch"];
				$this->DB_WE->Record = array();
			} else {
				return false;
			}
		}

		if($this->calendar_struct["calendar"] == "" || $fetch) {

			if($this->count < sizeof($this->Record)) {
				$paramName = "we_objectID";
				$this->DB_WE->record($this->Record[$this->count]);
				$this->DB_WE->Record["we_wedoc_Path"] = $this->Path."?$paramName=".$this->DB_WE->Record["OF_ID"];
				$path_parts = pathinfo($this->Path);
				if ($this->seourls && $this->DB_WE->Record['OF_Url']!=''){
					if (defined('NAVIGATION_DIRECTORYINDEX_NAMES') && NAVIGATION_DIRECTORYINDEX_NAMES !='' && $this->hidedirindex && in_array($path_parts['basename'],explode(',',NAVIGATION_DIRECTORYINDEX_NAMES)) ){
						$this->DB_WE->Record["we_WE_PATH"] = ($path_parts['dirname']!=DIRECTORY_SEPARATOR ? $path_parts['dirname']:'').DIRECTORY_SEPARATOR. $this->DB_WE->Record['OF_Url'];
					} else {
						$this->DB_WE->Record["we_WE_PATH"] = ($path_parts['dirname']!=DIRECTORY_SEPARATOR ? $path_parts['dirname']:'').DIRECTORY_SEPARATOR.$path_parts['filename'].DIRECTORY_SEPARATOR. $this->DB_WE->Record['OF_Url'];
					}
				} else {
					if (defined('NAVIGATION_DIRECTORYINDEX_NAMES') && NAVIGATION_DIRECTORYINDEX_NAMES !='' && $this->hidedirindex && in_array($path_parts['basename'],explode(',',NAVIGATION_DIRECTORYINDEX_NAMES)) ){
						$this->DB_WE->Record["we_WE_PATH"] = ($path_parts['dirname']!=DIRECTORY_SEPARATOR ? $path_parts['dirname']:'').DIRECTORY_SEPARATOR."?$paramName=".$this->DB_WE->Record["OF_ID"];
					} else {
						$this->DB_WE->Record["we_WE_PATH"] = $this->Path."?$paramName=".$this->DB_WE->Record["OF_ID"];
					}				
				}
				
				$this->DB_WE->Record["we_WE_TEXT"] = $this->DB_WE->Record["OF_Text"];
				$this->DB_WE->Record["we_WE_ID"] = $this->DB_WE->Record["OF_ID"];
				$this->DB_WE->Record["we_wedoc_Category"] = $this->DB_WE->Record["OF_Category"];

				// for seeMode #5317
				$this->DB_WE->Record["we_wedoc_lastPath"] = $this->LastDocPath."?$paramName=".$this->DB_WE->Record["OF_ID"];
				$this->count++;
				return true;

			} else if($this->cols && ($this->count < $this->rows)) {

				$this->DB_WE->Record = array();
				$this->DB_WE->Record["WE_PATH"] = "";
				$this->DB_WE->Record["WE_TEXT"] = "";
				$this->DB_WE->Record["WE_ID"] = "";
				$this->count++;
				return true;

			}

		}

		if($this->calendar_struct["calendar"]!="") {
			return true;
		}

		return false;
	}

	function f($key){
		return $this->DB_WE->f("we_".$key);
	}


}

?>