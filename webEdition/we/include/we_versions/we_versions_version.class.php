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
class we_versions_version{
	protected $ID;
	protected $documentID;
	protected $documentTable;
	protected $documentElements;
	protected $documentScheduler;
	protected $documentCustomFilter;
	protected $timestamp;
	protected $status;
	protected $version = 1;
	protected $binaryPath;
	protected $Filehash;
	protected $modifications;
	protected $modifierID;
	protected $IP;
	protected $Browser;
	protected $ContentType;
	protected $Text;
	protected $ParentID;
	protected $Icon;
	protected $CreationDate;
	protected $CreatorID;
	protected $Path;
	protected $TemplateID;
	protected $Filename;
	protected $Extension;
	protected $IsDynamic;
	protected $IsSearchable;
	protected $ClassName;
	protected $DocType;
	protected $Category;
	protected $RestrictOwners;
	protected $Owners;
	protected $OwnersReadOnly;
	protected $Language;
	protected $WebUserID;
	protected $Workspaces;
	protected $ExtraWorkspaces;
	protected $ExtraWorkspacesSelected;
	protected $Templates;
	protected $ExtraTemplates;
	protected $MasterTemplateID;
	protected $TableID;
	protected $ObjectID; //FIXME: remove??
	protected $IsClassFolder;
	protected $IsNotEditable; //FIXME: remove??
	protected $Charset;
	protected $active;
	protected $fromScheduler;
	protected $fromImport;
	protected $resetFromVersion;
	public $contentTypes = array();
	public $persistent_slots = array();
	public $modFields = array();

	/**
	 *  Constructor for class 'weVersions'
	 */
	public function __construct(){
		$this->contentTypes = self::getContentTypesVersioning();

		/**
		 * fields from tblFile and tblObjectFiles which can be modified
		 */
		$this->modFields = array(
			'status' => 1,
			'ParentID' => 2,
			'Text' => 3,
			'IsSearchable' => 4,
			'Category' => 5,
			'CreatorID' => 6,
			'RestrictOwners' => 7,
			'Owners' => 8,
			'OwnersReadOnly' => 9,
			'Language' => 10,
			'WebUserID' => 11,
			'documentElements' => 12,
			'documentScheduler' => 13,
			'documentCustomFilter' => 14,
			'TemplateID' => 15,
			'Filename' => 16,
			'Extension' => 17,
			'IsDynamic' => 18,
			'DocType' => 19,
			'Workspaces' => 20,
			'ExtraWorkspaces' => 21,
			'ExtraWorkspacesSelected' => 22,
			'Templates' => 23,
			'ExtraTemplates' => 24,
			'Charset' => 25,
			'InGlossar' => 26
		);
	}

	/**
	 * @return unknown
	 */
	public function getActive(){
		return $this->active;
	}

	/**
	 * @return unknown
	 */
	public function getBinaryPath(){
		return $this->binaryPath;
	}

	/**
	 * @return unknown
	 */
	public function getFilehash(){
		return $this->Filehash;
	}

	/**
	 * @return unknown
	 */
	public function getBrowser(){
		return $this->browser;
	}

	/**
	 * @return unknown
	 */
	public function getCategory(){
		return $this->category;
	}

	/**
	 * @return unknown
	 */
	public function getCharset(){
		return $this->charset;
	}

	/**
	 * @return unknown
	 */
	public function getClassName(){
		return $this->className;
	}

	/**
	 * @return unknown
	 */
	public function getContentType(){
		return $this->contentType;
	}

	/**
	 * @return unknown
	 */
	public function getCreationDate(){
		return $this->creationDate;
	}

	/**
	 * @return unknown
	 */
	public function getCreatorID(){
		return $this->creatorID;
	}

	/**
	 * @return unknown
	 */
	public function getDocType(){
		return $this->docType;
	}

	/**
	 * @return unknown
	 */
	public function getDocumentCustomFilter(){
		return $this->documentCustomFilter;
	}

	/**
	 * @return unknown
	 */
	public function getDocumentElements(){
		return $this->documentElements;
	}

	/**
	 * @return unknown
	 */
	public function getDocumentID(){
		return $this->documentID;
	}

	/**
	 * @return unknown
	 */
	public function getDocumentScheduler(){
		return $this->documentScheduler;
	}

	/**
	 * @return unknown
	 */
	public function getDocumentTable(){
		return $this->documentTable;
	}

	/**
	 * @return unknown
	 */
	public function getExtension(){
		return $this->extension;
	}

	/**
	 * @return unknown
	 */
	public function getExtraTemplates(){
		return $this->ExtraTemplates;
	}

	/**
	 * @return unknown
	 */
	public function getMasterTemplateID(){
		return $this->MasterTemplateID;
	}

	/**
	 * @return unknown
	 */
	public function getExtraWorkspaces(){
		return $this->extraWorkspaces;
	}

	/**
	 * @return unknown
	 */
	public function getExtraWorkspacesSelected(){
		return $this->extraWorkspacesSelected;
	}

	/**
	 * @return unknown
	 */
	public function getFilename(){
		return $this->filename;
	}

	/**
	 * @return unknown
	 */
	public function getFromImport(){
		return $this->fromImport;
	}

	/**
	 * @return unknown
	 */
	public function getFromScheduler(){
		return $this->fromScheduler;
	}

	/**
	 * @return unknown
	 */
	public function getIcon(){
		return $this->icon;
	}

	/**
	 * @return unknown
	 */
	public function getID(){
		return $this->iD;
	}

	/**
	 * @return unknown
	 */
	public function getIP(){
		return $this->iP;
	}

	/**
	 * @return unknown
	 */
	public function getIsClassFolder(){
		return $this->isClassFolder;
	}

	/**
	 * @return unknown
	 */
	public function getIsDynamic(){
		return $this->isDynamic;
	}

	/**
	 * @return unknown
	 */
	public function getIsNotEditable(){//fixme:remove
		return $this->isNotEditable;
	}

	/**
	 * @return unknown
	 */
	public function getIsSearchable(){
		return $this->isSearchable;
	}

	/**
	 * @return unknown
	 */
	public function getLanguage(){
		return $this->language;
	}

	/**
	 * @return unknown
	 */
	public function getModifications(){
		return $this->modifications;
	}

	/**
	 * @return unknown
	 */
	public function getModifierID(){
		return $this->modifierID;
	}

	/**
	 * @return unknown
	 */
	public function getObjectID(){
		return $this->objectID;
	}

	/**
	 * @return unknown
	 */
	public function getOwners(){
		return $this->owners;
	}

	/**
	 * @return unknown
	 */
	public function getOwnersReadOnly(){
		return $this->ownersReadOnly;
	}

	/**
	 * @return unknown
	 */
	public function getParentID(){
		return $this->parentID;
	}

	/**
	 * @return unknown
	 */
	public function getPath(){
		return $this->path;
	}

	/**
	 * @return unknown
	 */
	public function getResetFromVersion(){
		return $this->resetFromVersion;
	}

	/**
	 * @return unknown
	 */
	public function getRestrictOwners(){
		return $this->restrictOwners;
	}

	function getStatus(){
		return $this->status;
	}

	/**
	 * @return unknown
	 */
	public function getTableID(){
		return $this->tableID;
	}

	/**
	 * @return unknown
	 */
	public function getTemplateID(){
		return $this->templateID;
	}

	/**
	 * @return unknown
	 */
	public function getTemplates(){
		return $this->templates;
	}

	/**
	 * @return unknown
	 */
	public function getText(){
		return $this->text;
	}

	/**
	 * @return unknown
	 */
	public function getTimestamp(){
		return $this->timestamp;
	}

	function getVersion(){
		return $this->version;
	}

	/**
	 * @return unknown
	 */
	public function getWebUserID(){
		return $this->webUserID;
	}

	/**
	 * @return unknown
	 */
	public function getWorkspaces(){
		return $this->workspaces;
	}

	/**
	 * @param unknown_type $active
	 */
	public function setActive($active){
		$this->active = $active;
	}

	/**
	 * @param unknown_type $binaryPath
	 */
	public function setBinaryPath($binaryPath){
		$this->binaryPath = $binaryPath;
	}

	/**
	 * @param unknown_type $Filehash
	 */
	public function setFilehash($Filehash){
		$this->Filehash = $Filehash;
	}

	/**
	 * @param unknown_type $Browser
	 */
	public function setBrowser($browser){
		$this->browser = $browser;
	}

	/**
	 * @param unknown_type $Category
	 */
	public function setCategory($category){
		$this->category = $category;
	}

	/**
	 * @param unknown_type $Charset
	 */
	public function setCharset($charset){
		$this->charset = $charset;
	}

	/**
	 * @param unknown_type $ClassName
	 */
	public function setClassName($className){
		$this->className = $className;
	}

	/**
	 * @param unknown_type $ContentType
	 */
	public function setContentType($contentType){
		$this->contentType = $contentType;
	}

	/**
	 * @param unknown_type $CreationDate
	 */
	public function setCreationDate($creationDate){
		$this->creationDate = $creationDate;
	}

	/**
	 * @param unknown_type $CreatorID
	 */
	public function setCreatorID($creatorID){
		$this->creatorID = $creatorID;
	}

	/**
	 * @param unknown_type $DocType
	 */
	public function setDocType($docType){
		$this->docType = $docType;
	}

	/**
	 * @param unknown_type $documentCustomFilter
	 */
	public function setDocumentCustomFilter($documentCustomFilter){
		$this->documentCustomFilter = $documentCustomFilter;
	}

	/**
	 * @param unknown_type $documentElements
	 */
	public function setDocumentElements($documentElements){
		$this->documentElements = $documentElements;
	}

	/**
	 * @param unknown_type $documentID
	 */
	public function setDocumentID($documentID){
		$this->documentID = $documentID;
	}

	/**
	 * @param unknown_type $documentScheduler
	 */
	public function setDocumentScheduler($documentScheduler){
		$this->documentScheduler = $documentScheduler;
	}

	/**
	 * @param unknown_type $documentTable
	 */
	public function setDocumentTable($documentTable){
		$this->documentTable = $documentTable;
	}

	/**
	 * @param unknown_type $Extension
	 */
	public function setExtension($extension){
		$this->extension = $extension;
	}

	/**
	 * @param unknown_type $ExtraTemplates
	 */
	public function setExtraTemplates($ExtraTemplates){
		$this->ExtraTemplates = $ExtraTemplates;
	}

	/**
	 * @param unknown_type $MasterTemplateID
	 */
	public function setMasterTemplateID($MasterTemplateID){
		$this->MasterTemplateID = $MasterTemplateID;
	}

	/**
	 * @param unknown_type $ExtraWorkspaces
	 */
	public function setExtraWorkspaces($extraWorkspaces){
		$this->extraWorkspaces = $extraWorkspaces;
	}

	/**
	 * @param unknown_type $ExtraWorkspacesSelected
	 */
	public function setExtraWorkspacesSelected($extraWorkspacesSelected){
		$this->extraWorkspacesSelected = $extraWorkspacesSelected;
	}

	/**
	 * @param unknown_type $Filename
	 */
	public function setFilename($filename){
		$this->filename = $filename;
	}

	/**
	 * @param unknown_type $fromImport
	 */
	public function setFromImport($fromImport){
		$this->fromImport = $fromImport;
	}

	/**
	 * @param unknown_type $fromScheduler
	 */
	public function setFromScheduler($fromScheduler){
		$this->fromScheduler = $fromScheduler;
	}

	/**
	 * @param unknown_type $Icon
	 */
	public function setIcon($icon){
		$this->icon = $icon;
	}

	/**
	 * @param unknown_type $ID
	 */
	public function setID($iD){
		$this->iD = $iD;
	}

	/**
	 * @param unknown_type $IP
	 */
	public function setIP($iP){
		$this->iP = $iP;
	}

	/**
	 * @param unknown_type $IsClassFolder
	 */
	public function setIsClassFolder($isClassFolder){
		$this->isClassFolder = $isClassFolder;
	}

	/**
	 * @param unknown_type $IsDynamic
	 */
	public function setIsDynamic($isDynamic){
		$this->isDynamic = $isDynamic;
	}

	/**
	 * @param unknown_type $IsNotEditable
	 */
	public function setIsNotEditable($isNotEditable){//FIXME:remove?
		$this->isNotEditable = $isNotEditable;
	}

	/**
	 * @param unknown_type $IsSearchable
	 */
	public function setIsSearchable($isSearchable){
		$this->isSearchable = $isSearchable;
	}

	/**
	 * @param unknown_type $Language
	 */
	public function setLanguage($language){
		$this->language = $language;
	}

	/**
	 * @param unknown_type $modifications
	 */
	public function setModifications($modifications){
		$this->modifications = $modifications;
	}

	/**
	 * @param unknown_type $modifierID
	 */
	public function setModifierID($modifierID){
		$this->modifierID = $modifierID;
	}

	/**
	 * @param unknown_type $ObjectID
	 *
	 */
	public function setObjectID($objectID){//FIXME:remove?
		$this->objectID = $objectID;
	}

	/**
	 * @param unknown_type $Owners
	 */
	public function setOwners($owners){
		$this->owners = $owners;
	}

	/**
	 * @param unknown_type $OwnersReadOnly
	 */
	public function setOwnersReadOnly($ownersReadOnly){
		$this->ownersReadOnly = $ownersReadOnly;
	}

	/**
	 * @param unknown_type $ParentID
	 */
	public function setParentID($parentID){
		$this->parentID = $parentID;
	}

	/**
	 * @param unknown_type $Path
	 */
	public function setPath($path){
		$this->path = $path;
	}

	/**
	 * @param unknown_type $resetFromVersion
	 */
	public function setResetFromVersion($resetFromVersion){
		$this->resetFromVersion = $resetFromVersion;
	}

	/**
	 * @param unknown_type $RestrictOwners
	 */
	public function setRestrictOwners($restrictOwners){
		$this->restrictOwners = $restrictOwners;
	}

	function setStatus($status){
		$this->status = $status;
	}

	/**
	 * @param unknown_type $tableID
	 */
	public function setTableID($tableID){
		$this->tableID = $tableID;
	}

	/**
	 * @param unknown_type $templateID
	 */
	public function setTemplateID($templateID){
		$this->templateID = $templateID;
	}

	/**
	 * @param unknown_type $Templates
	 */
	public function setTemplates($templates){
		$this->templates = $templates;
	}

	/**
	 * @param unknown_type $Text
	 */
	public function setText($text){
		$this->text = $text;
	}

	/**
	 * @param unknown_type $timestamp
	 */
	public function setTimestamp($timestamp){
		$this->timestamp = $timestamp;
	}

	function setVersion($version){
		$this->version = $version;
	}

	/**
	 * @param unknown_type $WebUserID
	 */
	public function setWebUserID($webUserID){
		$this->webUserID = $webUserID;
	}

	/**
	 * @param unknown_type $Workspaces
	 */
	public function setWorkspaces($workspaces){
		$this->workspaces = $workspaces;
	}

	/**
	 * ContentTypes which apply for versioning
	 * all except classes, templates and folders
	 */
	public static function getContentTypesVersioning(){

		$contentTypes = array();
		$contentTypes[] = 'all';
		$ct = we_base_ContentTypes::inst();
		foreach($ct->getContentTypes() as $k){
//if($k != "object" && $k != "text/weTmpl" && $k != "folder") { vor #4120
			if($k != "object" && $k != "folder" && $k != "class_folder"){
				$contentTypes[] = $k;
			}
		}
		return $contentTypes;
	}

	/**
	 * @abstract set first document object if no versions exist
	 * for contentType = text/webedition
	 */
	public function setInitialDocObject($obj){
		if(is_object($obj) && $obj->ID && in_array($obj->ContentType, self::getContentTypesVersioning())){
			$_SESSION['weS']['versions']['versionToCompare'][$obj->Table][$obj->ID] = self::getHashValue(self::removeUnneededCompareFields(self::objectToArray($obj)));

			if(!$this->versionsExist($obj->ID, $obj->ContentType)){
				$_SESSION['weS']['versions']['initialVersions'] = true;
				$this->save($obj);
			}
		}
	}

	/**
	 * @abstract count versions
	 */
	public function countVersions($id, $contentType){
		return f('SELECT COUNT(1) FROM ' . VERSIONS_TABLE . ' WHERE documentId=' . intval($id) . " AND ContentType = '" . escape_sql_query($contentType) . "'", '', new DB_WE());
	}

	/**
	 * @abstract looks if versions exist for the document
	 */
	public static function versionsExist($id, $contentType){
		return (self::countVersions($id, $contentType) > 0);
	}

	/**
	 * @abstract get versions of one document / object
	 * @return array of version-records of one document / object
	 */
	function loadVersionsOfId($id, $table, $where = ''){

		$versionArr = array();
		$versionArray = array();
		$db = new DB_WE();
		$tblFields = self::getFieldsFromTable(VERSIONS_TABLE, $db);

		$db->query('SELECT * FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($id) . ' AND documentTable="' . $db->escape($table) . '" ' . $where . ' ORDER BY version ASC');
		while($db->next_record()){
			foreach($tblFields as $k => $v){
				$versionArray[$v] = $db->f("" . $v);
			}

			$versionArr[] = $versionArray;
		}

		return $versionArr;
	}

	/**
	 * @abstract get one version of document / object
	 * @return array of version-records of one document / object
	 */
	function loadVersion($where = "1"){
		$versionArray = array();
		$db = new DB_WE();
		$tblFields = self::getFieldsFromTable(VERSIONS_TABLE, $db);

		$db->query('SELECT * FROM ' . VERSIONS_TABLE . ' ' . $where);
		while($db->next_record()){
			foreach($tblFields as $k => $v){
				$versionArray[$v] = $db->f("" . $v);
			}
		}

		return $versionArray;
	}

	/**
	 * @abstract cases in which versions are created
	 * 1. if documents are imported
	 * 2. there exists no version-record of a document but in tblfile oder tblobjectsfile (document/object was not created new)
	 * 3. if document / object is saved, published or unpublished
	 */
	public function save($docObj, $status = "saved"){
		if(!isset($_SESSION["user"]["ID"])){
			return;
		}
		$_SESSION['weS']['versions']['fromImport'] = 0;

		$cmd0 = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0);
		$cmd = we_base_request::_(we_base_request::STRING, 'cmd');
//import
		if(we_base_request::_(we_base_request::BOOL, "jupl")){
			$_SESSION['weS']['versions']['fromImport'] = 1;
			$this->saveVersion($docObj);
		} elseif(we_base_request::_(we_base_request::STRING, "pnt") === "wizcmd"){
			switch(we_base_request::_(we_base_request::STRING, "v", '', "type")){
				case we_import_functions::TYPE_CSV:
				case we_import_functions::TYPE_GENERIC_XML:
					$_SESSION['weS']['versions']['fromImport'] = 1;
					$this->saveVersion($docObj);
					break;
				default :
					if(isset($_SESSION['weS']['ExImRefTable'])){
						foreach($_SESSION['weS']['ExImRefTable'] as $v){
							if($v["ID"] == $docObj->ID){
								$_SESSION['weS']['versions']['fromImport'] = 1;
								$this->saveVersion($docObj);
							}
						}
					}
			}
		} elseif($cmd0 === "siteImport" || $cmd0 === "import_files"){
			$_SESSION['weS']['versions']['fromImport'] = 1;
			$this->saveVersion($docObj);
		} elseif((isset($_SESSION['weS']['versions']['fromScheduler']) && $_SESSION['weS']['versions']['fromScheduler']) || ($cmd0 === "save_document" || $cmd0 === "unpublish" || $cmd0 === "revert_published") || $cmd === "ResetVersion" || $cmd === "PublishDocs" || $cmd === "ResetVersionsWizard" || (we_base_request::_(we_base_request::STRING, "type") === "reset_versions") || (isset($_SESSION['weS']['versions']['initialVersions']) && $_SESSION['weS']['versions']['initialVersions'])){
			if(isset($_SESSION['weS']['versions']['initialVersions'])){
				unset($_SESSION['weS']['versions']['initialVersions']);
			}
			$this->saveVersion($docObj, $status);
		}
	}

	/**
	 * @abstract apply preferences
	 */
	function CheckPreferencesCtypes($ct){

//if folder was saved don' make versions (if path was changed of folder)
		if(isset($GLOBALS['we_doc']->ClassName)){
			if(($GLOBALS['we_doc'] instanceof we_folder) || ($GLOBALS['we_doc'] instanceof we_class_folder)){
				return false;
			}
		}

//apply content types in preferences

		switch($ct){
			case we_base_ContentTypes::WEDOCUMENT:
				return VERSIONING_TEXT_WEBEDITION;
			case we_base_ContentTypes::IMAGE:
				return VERSIONING_IMAGE;
			case we_base_ContentTypes::HTML:
				return VERSIONING_TEXT_HTML;
			case we_base_ContentTypes::JS:
				return VERSIONING_TEXT_JS;
			case we_base_ContentTypes::CSS:
				return VERSIONING_TEXT_CSS;
			case we_base_ContentTypes::TEXT:
				return VERSIONING_TEXT_PLAIN;
			case we_base_ContentTypes::HTACESS:
				return VERSIONING_TEXT_HTACCESS;
			case we_base_ContentTypes::TEMPLATE:
				return VERSIONING_TEXT_WETMPL;
			case we_base_ContentTypes::VIDEO:
				return VERSIONING_VIDEO;
			case we_base_ContentTypes::AUDIO:
				return VERSIONING_AUDIO;
			case we_base_ContentTypes::FLASH:
				return VERSIONING_FLASH;
			case we_base_ContentTypes::QUICKTIME:
				return VERSIONING_QUICKTIME;
			case we_base_ContentTypes::APPLICATION:
				return VERSIONING_SONSTIGE;
			case we_base_ContentTypes::XML:
				return VERSIONING_TEXT_XML;
			case we_base_ContentTypes::OBJECT_FILE:
				return VERSIONING_OBJECT;
		}

		return true;
	}

	function CheckPreferencesTime($docID, $docTable){

		$db = new DB_WE();

		if($docTable == TEMPLATES_TABLE){
			$prefTimeDays = (VERSIONS_TIME_DAYS_TMPL != '-1') ? VERSIONS_TIME_DAYS_TMPL : "";
			$prefTimeWeeks = (VERSIONS_TIME_WEEKS_TMPL != '-1') ? VERSIONS_TIME_WEEKS_TMPL : "";
			$prefTimeYears = (VERSIONS_TIME_YEARS_TMPL != '-1') ? VERSIONS_TIME_YEARS_TMPL : "";
		} else {
			$prefTimeDays = (VERSIONS_TIME_DAYS != "-1") ? VERSIONS_TIME_DAYS : "";
			$prefTimeWeeks = (VERSIONS_TIME_WEEKS != "-1") ? VERSIONS_TIME_WEEKS : "";
			$prefTimeYears = (VERSIONS_TIME_YEARS != "-1") ? VERSIONS_TIME_YEARS : "";
		}

		$prefTime = 0;
		if($prefTimeDays != ""){
			$prefTime = $prefTime + $prefTimeDays;
		}
		if($prefTimeWeeks != ""){
			$prefTime = $prefTime + $prefTimeWeeks;
		}
		if($prefTimeYears != ""){
			$prefTime = $prefTime + $prefTimeYears;
		}

		if($prefTime != 0){
			$deletetime = time() - $prefTime;
//initial version always stays
			$where = ' timestamp < ' . $deletetime . ' AND CreationDate!=timestamp ';
			$this->deleteVersion('', $where);
		}
		$prefAnzahl = intval($docTable == TEMPLATES_TABLE ? VERSIONS_ANZAHL_TMPL : VERSIONS_ANZAHL);

		$anzahl = f('SELECT COUNT(1) FROM ' . VERSIONS_TABLE . " WHERE documentId=" . intval($docID) . " AND documentTable='" . $db->escape($docTable) . "'", "", $db);

		if($anzahl > $prefAnzahl && $prefAnzahl != ""){
			$toDelete = $anzahl - $prefAnzahl;
			$m = 0;
			$db->query('SELECT ID, version FROM ' . VERSIONS_TABLE . " WHERE documentId=" . intval($docID) . " AND documentTable='" . $db->escape($docTable) . "' ORDER BY version ASC LIMIT " . intval($toDelete));
			while($db->next_record()){
				if($m < $toDelete){
					$this->deleteVersion($db->f('ID'), '');
					$m++;
				}
			}
		}
	}

	/**
	 * @abstract make new version-entry in DB
	 */
	function saveVersion($document, $status = "saved"){
		if(!isset($_SESSION['user']['ID'])){
			return;
		}
		$documentObj = "";
		$db = new DB_WE();
		if(is_object($document)){
			$documentObj = $document;
			$document = self::objectToArray($document);
		}

		if(isset($document["documentCustomerFilter"]) && is_object($document["documentCustomerFilter"])){
			$document["documentCustomerFilter"] = self::objectToArray($document["documentCustomerFilter"]);
		}

//preferences
		if(!$this->CheckPreferencesCtypes($document["ContentType"])){
			return;
		}

		if(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0) === "save_document" &&
			we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 5)){
			$status = "published";
		}

		if($document["ContentType"] != we_base_ContentTypes::OBJECT_FILE && $document["ContentType"] != we_base_ContentTypes::WEDOCUMENT && $document["ContentType"] != we_base_ContentTypes::HTML && !($document["ContentType"] == we_base_ContentTypes::TEMPLATE && defined('VERSIONS_CREATE_TMPL') && VERSIONS_CREATE_TMPL)){
			$status = "saved";
		}

		if($this->IsScheduler() && $status != "unpublished" && $status != "deleted"){
			$status = "published";
		}

		if(isset($_SESSION['weS']['versions']['doPublish']) && $_SESSION['weS']['versions']['doPublish']){
			$status = "published";
		}

		switch($document["ContentType"]){
			case we_base_ContentTypes::TEMPLATE:
				if((defined('VERSIONS_CREATE_TMPL') && VERSIONS_CREATE_TMPL)){
					if($status != "published" && !we_base_request::_(we_base_request::BOOL, 'we_cmd', true, 5)){
						return;
			}
					break;
				}
				return;
			case we_base_ContentTypes::OBJECT_FILE:
			case we_base_ContentTypes::WEDOCUMENT:
			case we_base_ContentTypes::HTML:
				if((defined('VERSIONS_CREATE') && VERSIONS_CREATE) && $status != "published" && !we_base_request::_(we_base_request::BOOL, 'we_cmd', true, 5)){
					return;
			}
		}

//look if there were made changes
		if(isset($_SESSION['weS']['versions']['versionToCompare'][$document["Table"]][$document["ID"]]) && $_SESSION['weS']['versions']['versionToCompare'][$document["Table"]][$document['ID']] != ''){
			$lastEntry = $_SESSION['weS']['versions']['versionToCompare'][$document['Table']][$document['ID']];

			$diffExists = (is_array($document) && $lastEntry ?
					(self::getHashValue(self::removeUnneededCompareFields($document)) != $lastEntry) :
					false);

			$lastEntry = self::getLastEntry($document['ID'], $document['Table'], $db);

			switch($status){
				case 'published':
				case 'saved':
					if(isset($lastEntry['status']) && $status == $lastEntry['status'] && !$diffExists && $this->versionsExist($document['ID'], $document['ContentType'])){
						return;
					}
			}
		}

		$mods = true;
		$tblversionsFields = self::getFieldsFromTable(VERSIONS_TABLE, $db);

		$set = array();

		foreach($tblversionsFields as $fieldName){
			if($fieldName != 'ID'){
				$set[$fieldName] = (isset($document[$fieldName]) ?
						$document[$fieldName] :
						$this->makePersistentEntry($fieldName, $status, $document, $documentObj)
					);
			}
		}


		if($set && $mods){
			$db->query('INSERT INTO ' . VERSIONS_TABLE . ' SET ' . we_database_base::arraySetter($set));
			$vers = (isset($document["version"]) ? $document["version"] : $this->version);
			$db->query('UPDATE ' . VERSIONS_TABLE . ' SET active=0 WHERE documentID=' . intval($document['ID']) . ' AND documentTable="' . $db->escape($document["Table"]) . '" AND version!=' . intval($vers));
			$_SESSION['weS']['versions']['versionToCompare'][$document["Table"]][$document["ID"]] = self::getHashValue(self::removeUnneededCompareFields($document));
		}
		$this->CheckPreferencesTime($document['ID'], $document['Table']);
	}

	/**
	 * @abstract give the persistent fieldnames the values if you save, publish or unpublish
	 * persistent fieldnames are fields which are not in tblfile or tblobjectsfile and are always saved
	 * @return value of field
	 */
	private function makePersistentEntry($fieldName, $status, $document, $documentObj){
		$entry = '';
		$db = new DB_WE();

		switch($fieldName){
			case "documentID":
				$entry = $document["ID"];
				break;
			case 'documentTable':
				$entry = $document['Table']; //FIXME: check if this is tblFile or Prefixed version
				break;
			case 'documentElements':
				if(isset($document['elements']) && is_array($document['elements'])){
					$entry = sql_function('x\'' . bin2hex(gzcompress(serialize($document["elements"]), 9)) . '\'');
				}
				break;
			case 'documentScheduler':
				if(isset($document['schedArr']) && is_array($document['schedArr'])){
					$entry = sql_function('x\'' . bin2hex(gzcompress(serialize($document["schedArr"]), 9)) . '\'');
				}
				break;
			case "documentCustomFilter":
				if(isset($document["documentCustomerFilter"]) && is_array($document["documentCustomerFilter"])){
					$entry = sql_function('x\'' . bin2hex(gzcompress(serialize($document["documentCustomerFilter"]), 9)) . '\'');
				}
				break;
			case 'timestamp':
				$lastEntryVersion = f('SELECT ID FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($document["ID"]) . ' AND documentTable="' . $db->escape($document["Table"]) . '" LIMIT 1', 'ID', $db);
				$entry = ($lastEntryVersion ? time() : $document['CreationDate']);
				break;
			case 'status':
				$this->setStatus($status);
				$entry = $status;
				break;
			case 'Charset':
				if(isset($document['elements']['Charset']['dat'])){
					$entry = $document['elements']['Charset']['dat'];
				}
				break;
			case 'version':
				$lastEntryVersion = f('SELECT MAX(version) AS version FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($document["ID"]) . ' AND documentTable="' . $db->escape($document["Table"]) . '"', 'version', $db);
				if($lastEntryVersion){
					$newVersion = $lastEntryVersion + 1;
					$this->setVersion($newVersion);
				}
				$entry = $this->getVersion();
				break;
			case 'binaryPath':
				$binaryPath = '';
				$this->Filehash = '';
				switch($document['ContentType']){
					case 'objectFile':
					case we_base_ContentTypes::TEMPLATE:
						break;
					default:
						$documentPath = substr($document["Path"], 1);
						$siteFile = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . $documentPath;

						$vers = $this->getVersion();

						$versionName = $document['ID'] . '_' . $document['Table'] . '_' . $vers . $document['Extension'];
						$binaryPath = VERSION_DIR . $versionName . '.gz';

						if($document['IsDynamic']){
							$this->writePreviewDynFile($document['ID'], $siteFile, $_SERVER['DOCUMENT_ROOT'] . $binaryPath, $documentObj);
						} elseif(file_exists($siteFile) && $document['Extension'] === '.php' && ($document['ContentType'] == we_base_ContentTypes::WEDOCUMENT || $document['ContentType'] == we_base_ContentTypes::HTML)){
							we_base_file::save($_SERVER['DOCUMENT_ROOT'] . $binaryPath, gzencode(file_get_contents($siteFile), 9));
						} elseif(isset($document['TemplatePath']) && $document['TemplatePath'] && substr($document['TemplatePath'], -18) != '/' . we_template::NO_TEMPLATE_INC && $document['ContentType'] == we_base_ContentTypes::WEDOCUMENT){
							$includeTemplate = preg_replace('/.tmpl$/i', '.php', $document['TemplatePath']);
							$this->writePreviewDynFile($document['ID'], $includeTemplate, $_SERVER['DOCUMENT_ROOT'] . $binaryPath, $documentObj);
						} else {
							we_base_file::save($_SERVER['DOCUMENT_ROOT'] . $binaryPath, gzencode(file_get_contents($siteFile), 9));
						}
						$usepath = $_SERVER['DOCUMENT_ROOT'] . $binaryPath;
						if(file_exists($usepath) && is_file($usepath)){
							$this->Filehash = sha1_file($usepath);
						}
				}
				$this->binaryPath = $binaryPath;
				$entry = $binaryPath;
				break;
			case 'modifications':
				$modifications = array();

				/* get fields which can be changed */
				$fields = self::getFieldsFromTable(VERSIONS_TABLE, $db);

				$vals = getHash('SELECT ' . implode(',', $fields) . ' FROM ' . VERSIONS_TABLE . ' WHERE version<' . intval($this->version) . " AND status != 'deleted' AND documentID=" . intval($document["ID"]) . " AND documentTable='" . $db->escape($document["Table"]) . "' ORDER BY version DESC LIMIT 1");
				foreach($fields as $val){
					if(isset($this->modFields[$val]) && isset($vals[$val])){
						$lastEntryField = isset($vals[$val]) ? $vals[$val] : '';

						if($val === "Text" && $document["ContentType"] != we_base_ContentTypes::OBJECT_FILE){
							$val = "";
						}

						if(isset($document[$val])){
							switch($val){
								case 'DocType':
								case 'IsSearchable':
								case 'WebUserID':
								case 'TemplateID':
									if(!$document[$val]){
										$document[$val] = 0;
									}
									break;
							}
							if($document[$val] != $lastEntryField){
								$modifications[] = $val;
							} elseif(($lastEntryField === '' && $document[$val] === '') || ($lastEntryField == $document[$val])){
// do nothing
							} else {
								$modifications[] = $val;
							}
						} else {
							if($val === 'documentElements' || $val === 'documentScheduler' || $val === 'documentCustomFilter'){
								$newData = array();
								$diff = array();
								if(!$lastEntryField){
									$lastEntryField = array();
								} else {
									$lastEntryField = unserialize(
										(substr_compare($lastEntryField, 'a%3A', 0, 4) == 0 ?
											html_entity_decode(urldecode($lastEntryField), ENT_QUOTES) :
											gzuncompress($lastEntryField))
									);
								}
								switch($val){
									case 'documentElements':
//TODO: imi: check if we need next-level information from nested arrays
										if($document["elements"]){
											$newData = $document["elements"];
											foreach($newData as $k => $vl){
												if(isset($lastEntryField[$k]) && is_array($lastEntryField[$k]) && is_array($vl)){
													if(isset($vl['dat'])){
														$vl['dat'] = is_array($vl['dat']) ? serialize($vl['dat']) : $vl['dat'];
													}
													if(isset($lastEntryField[$k]['dat'])){
														$lastEntryField[$k]['dat'] = is_array($lastEntryField[$k]['dat']) ? serialize($lastEntryField[$k]['dat']) : $lastEntryField[$k]['dat'];
													}
													$_diff = array_diff_assoc($vl, $lastEntryField[$k]);
													if(!empty($_diff) && isset($_diff['dat'])){
														$diff[] = $_diff;
													}
												}
											}
										}
										break;
									case 'documentScheduler':
//TODO: imi: check if count() is ok (do we allways have two arrays?)
										if(count($document["schedArr"]) != count($lastEntryField)){
											$diff['schedArr'] = true;
										} elseif(!empty($document["schedArr"])){
											$newData = $document["schedArr"];
											foreach($newData as $k => $vl){
												if(isset($lastEntryField[$k]) && is_array($lastEntryField[$k]) && is_array($vl)){
													$_tmpArr1 = array();
													$_tmpArr2 = array();
													foreach($vl as $_k => $_v){
														$_tmpArr1[$_k] = is_array($_v) ? serialize($_v) : $_v;
													}
													foreach($lastEntryField[$k] as $_k => $_v){
														$_tmpArr2[$_k] = is_array($_v) ? serialize($_v) : $_v;
													}
													$_diff = array_diff_assoc($_tmpArr1, $_tmpArr2);
													if(!empty($_diff)){
														$diff = $_diff;
													}
												}
											}
										}
										break;
									case 'documentCustomFilter':
//TODO: imi: check if we need both foreach
										if(isset($document["documentCustomerFilter"]) && is_array($document["documentCustomerFilter"]) && is_array($lastEntryField)){
											$_tmpArr1 = array();
											$_tmpArr2 = array();
											foreach($document["documentCustomerFilter"] as $_k => $_v){
												$_tmpArr1[$_k] = is_array($_v) ? serialize($_v) : $_v;
											}
											foreach($lastEntryField as $_k => $_v){
												$_tmpArr2[$_k] = is_array($_v) ? serialize($_v) : $_v;
											}
											$_diff = array_diff_assoc($_tmpArr1, $_tmpArr2);
											if(!empty($_diff)){
												$diff['documentCustomerFilter'] = $_diff;
											}
										}

										break;
								}

								if(!empty($diff)){
									$modifications[] = $val;
								}
							}

							if($val === 'status' && $lastEntryField != $this->status){
								$modifications[] = $val;
							}
						}
					}
				}

				$modConstants = $this->getConstantsOfMod($modifications);

				$entry = ($modConstants ? : '');
				break;
			case 'modifierID':
				$entry = (isset($_SESSION['user']['ID'])) ? $_SESSION['user']['ID'] : '';
				break;
			case 'IP':
				$ip = $_SERVER['REMOTE_ADDR'];
				$entry = $ip;
				break;
			case 'Browser':
				$browser = $_SERVER['HTTP_USER_AGENT'];
				$entry = $browser;
				break;
			case 'active':
				$entry = 1;
				break;
			case 'fromScheduler':
				$entry = $this->IsScheduler();
				break;
			case 'fromImport':
				$entry = (isset($_SESSION['weS']['versions']['fromImport']) && $_SESSION['weS']['versions']['fromImport']) ? 1 : 0;
				break;
			case 'resetFromVersion':
				$entry = (isset($document['resetFromVersion']) && $document['resetFromVersion'] != '') ? $document['resetFromVersion'] : 0;
				break;
			default:
				$entry = '';
		}

		return $entry;
	}

	/**
	 * @abstract look if scheduler was called
	 * @return boolean
	 */
	function IsScheduler(){
		$fromScheduler = 0;
		if(isset($_SESSION['weS']['versions']['fromScheduler'])){
			$fromScheduler = $_SESSION['weS']['versions']['fromScheduler'];
		}

		return $fromScheduler;
	}

	/**
	 * @abstract get differences between two arrays
	 * @return true if they differ, false if not
	 */
	/*
	  private static function array_diff_values(array $newArr, array $oldArr){
	  if(empty($newArr) && empty($oldArr)){
	  return false;
	  }
	  //we can't use serialize, since the data in the array might be different
	  //$ret = array();
	  $keys = array_merge(array_keys($newArr), array_keys($oldArr));
	  foreach($keys as $k){
	  if(!isset($newArr[$k]) || !isset($oldArr[$k])){
	  //$ret[] = $k;
	  return true;
	  } elseif(is_array($newArr[$k]) && !is_array($oldArr[$k]) || !is_array($newArr[$k]) && is_array($oldArr[$k])){
	  //$ret[] = $k;
	  return true;
	  } elseif(is_array($newArr[$k])){
	  $tmp = self::array_diff_values($newArr[$k], $oldArr[$k]);
	  if($tmp){
	  //$ret[] = array($k => $tmp);
	  return true;
	  }
	  } elseif($newArr[$k] != $oldArr[$k]){
	  //$ret[] = $k;
	  return true;
	  }
	  }
	  return false;
	  }
	 */

	/**
	 * @abstract create file to preview dynamic documents
	 */
	function writePreviewDynFile($id, $siteFile, $tmpFile, $document){
		we_base_file::save($tmpFile, gzencode($this->getDocContent($document, $siteFile), 9));
	}

	function getDocContent($we_doc, $includepath = ""){
		update_time_limit(0);
		$requestBackup = $_REQUEST;
		$docBackup = isset($GLOBALS['we_doc']) ? $GLOBALS['we_doc'] : false;
		$GLOBALS['getDocContentVersioning'] = true;
		$transBackup = $GLOBALS['we_transaction'];

		extract($GLOBALS, EXTR_SKIP); // globalen Namensraum herstellen.

		$isdyn = isset($GLOBALS['WE_IS_DYN']) ? $GLOBALS['WE_IS_DYN'] : 'notSet';

//usually the site file always exists
		if($includepath != '' && file_exists($includepath)){
			ob_start();
			include($includepath);
			ob_end_clean();
			$_REQUEST = $requestBackup;
			extract($GLOBALS, EXTR_SKIP); // globalen Namensraum herstellen.

			ob_start();
			include($includepath);
			$contents = ob_get_clean();
		} else {
			ob_start();
			if(!defined('NO_SESS')){
				define('NO_SESS', 1);
			}
			$GLOBALS['WE_IS_DYN'] = 1;
			$we_transaction = '';
			$we_ContentType = $we_doc->ContentType;
			$_REQUEST['we_cmd'] = array();
			$_REQUEST['we_cmd'][1] = $we_doc->ID;
			$FROM_WE_SHOW_DOC = true;
			include(WE_INCLUDES_PATH . 'we_showDocument.inc.php');
			$contents = ob_get_clean();
		}

		//Note: some globals are overwritten by the above code, restore at least we_transaction
		$GLOBALS['we_transaction'] = $transBackup;

		if($docBackup){
			$GLOBALS['we_doc'] = $docBackup;
		} else {
			unset($GLOBALS['we_doc']);
		}
		$_REQUEST = $requestBackup;

		if($isdyn === 'notSet'){
			if(isset($GLOBALS['WE_IS_DYN'])){
				unset($GLOBALS['WE_IS_DYN']);
			}
		} else {
			$GLOBALS['WE_IS_DYN'] = $isdyn;
		}

		unset($GLOBALS['getDocContentVersioning']);

		return $contents;
	}

	/**
	 * @abstract save version-entry in DB which is marked as deleted
	 */
	function setVersionOnDelete($docID, $docTable, $ct, we_database_base $db){
		if(!isset($_SESSION["user"]["ID"])){
			return;
		}
		$lastEntry = array_merge(self::getLastEntry($docID, $docTable, $db), array(
			'timestamp' => time(),
			'status' => "deleted",
			'modifications' => 1,
			'modifierID' => $_SESSION["user"]["ID"],
			'IP' => $_SERVER['REMOTE_ADDR'],
			'Browser' => $_SERVER['HTTP_USER_AGENT'],
			'active' => 1,
			'fromScheduler' => $this->IsScheduler(),
		));
		$lastEntry['version'] = (isset($lastEntry['version'])) ? $lastEntry['version'] + 1 : 1;

		unset($lastEntry['ID']);

		//preferences
		$doDelete = $this->CheckPreferencesCtypes($ct);

		// always write delete versions, if enabled, so ignore VERSIONS_CREATE
		if($lastEntry && $doDelete){
			$db->query('INSERT INTO ' . VERSIONS_TABLE . ' SET ' . we_database_base::arraySetter($lastEntry));
			$db->query('UPDATE ' . VERSIONS_TABLE . ' SET active=0 WHERE documentID=' . intval($docID) . ' AND documentTable="' . $db->escape($docTable) . '" AND version!=' . intval($lastEntry['version']));
		}

		$this->CheckPreferencesTime($docID, $docTable);
	}

	/**
	 * @abstract delete version entry from db and delete version files
	 */
	function deleteVersion($ID = 0, $where = ''){

		if(isset($_SESSION["user"]["ID"])){
			$db = new DB_WE();
			if(!empty($ID)){
				$w = 'ID=' . intval($ID);
			} elseif(!empty($where)){
				$w = $where;
			}


			$data = getHash('SELECT ID,documentID,version,Text,ContentType,documentTable,Path,binaryPath FROM ' . VERSIONS_TABLE . ' WHERE ' . $w . ' LIMIT 1', $db);
			$binaryPath = "";
			if($data){
				$binaryPath = $db->f('binaryPath');
				$_SESSION['weS']['versions']['logDeleteIds'][$db->f('ID')] = array(
					'Text' => $data['Text'],
					'ContentType' => $data['ContentType'],
					'Path' => $data['Path'],
					'Version' => $data['version'],
					'documentID' => $data['documentID'],
				);
			}

			$filePath = $_SERVER['DOCUMENT_ROOT'] . $binaryPath;
			$binaryPathUsed = f('SELECT binaryPath FROM ' . VERSIONS_TABLE . ' WHERE ID!=' . intval($ID) . " AND binaryPath='" . $db->escape($binaryPath) . "' LIMIT 1", "", $db);

			if(file_exists($filePath) && !$binaryPathUsed){
				@unlink($filePath);
			}

			$db->query('DELETE FROM ' . VERSIONS_TABLE . ' WHERE ' . $w);
		}
	}

	/**
	 * @abstract reset version
	 */
	function resetVersion($ID, $version, $publish){
		$db = new DB_WE();

		if(isset($_SESSION["user"]["ID"])){
			$resetArray = array();
			$tblFields = array();
			$tableInfo = $db->metadata(VERSIONS_TABLE);
			$we_transaction = we_base_request::_(we_base_request::TRANSACTION, "we_transaction", 0);

			foreach($tableInfo as $cur){
				$tblFields[] = $cur["name"];
			}

			$db->query('SELECT * FROM ' . VERSIONS_TABLE . ' WHERE ID=' . intval($ID));

			if($db->next_record()){
				foreach($tblFields as $k => $v){
					$resetArray[$v] = $db->f($v);
				}
			}

			if(is_array($resetArray) && !empty($resetArray)){
				$resetDoc = new $resetArray["ClassName"]();

				foreach($resetArray as $k => $v){

					if(isset($resetDoc->$k)){
						if($k != "ID"){
							$resetDoc->$k = $v;
						}
					} else {
						switch($k){
							case "documentID":
								$resetDoc->ID = $v;
								break;
							case "documentElements":
								if($v){
									$docElements = unserialize((substr_compare($v, 'a%3A', 0, 4) == 0 ?
											html_entity_decode(urldecode($v), ENT_QUOTES) :
											gzuncompress($v))
									);
									$resetDoc->elements = $docElements;
								}
								break;
							case 'documentScheduler':
								if($v){
									$docElements = unserialize((substr_compare($v, 'a%3A', 0, 4) == 0 ?
											html_entity_decode(urldecode($v), ENT_QUOTES) :
											gzuncompress($v))
									);
									$resetDoc->schedArr = $docElements;
								}
								break;
							case 'documentCustomFilter':
								if($v){
									$docElements = unserialize((substr_compare($v, 'a%3A', 0, 4) == 0 ?
											html_entity_decode(urldecode($v), ENT_QUOTES) :
											gzuncompress($v))
									);
									$resetDoc->documentCustomerFilter = new we_customer_documentFilter();
									foreach($docElements as $k => $v){
										if(isset($resetDoc->documentCustomerFilter->$k)){
											if($v != "" || !empty($v)){
												$resetDoc->documentCustomerFilter->$k = $v;
											}
										}
									}
								}
								break;
						}
					}
				}

				if($resetDoc->ContentType == we_base_ContentTypes::IMAGE){
					$lastBinaryPath = f('SELECT binaryPath FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($resetArray["documentID"]) . " AND documentTable='" . $resetArray["documentTable"] . "' AND version <='" . $version . "' AND binaryPath !='' ORDER BY version DESC LIMIT 1", 'binaryPath', $db);
					$resetDoc->elements["data"]["dat"] = $_SERVER['DOCUMENT_ROOT'] . $lastBinaryPath;
				}

				$resetDoc->EditPageNr = $_SESSION['weS']['EditPageNr'];

				$existsInFileTable = f('SELECT ID FROM ' . $db->escape($resetArray["documentTable"]) . ' WHERE ID=' . intval($resetDoc->ID), "", $db);
//if document was deleted

				if(!$existsInFileTable){
//save this id and contenttype to turn the id for the versions
					$oldId = $resetDoc->ID;
					$oldCt = $resetDoc->ContentType;
					$resetDoc->ID = 0;
					$lastEntryVersion = f('SELECT version FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($resetArray["documentID"]) . " AND documentTable='" . $db->escape($resetArray["documentTable"]) . "' ORDER BY version DESC LIMIT 1", "version", $db);
					$resetDoc->version = $lastEntryVersion + 1;
				}

				if($resetArray["ParentID"] != 0){
//if folder was deleted
					$existsPath = f('SELECT 1 FROM ' . $db->escape($resetArray["documentTable"]) . ' WHERE ID=' . intval($resetArray["ParentID"]) . ' AND IsFolder=1', '', $db);

					if(!$existsPath){
// create old folder if it does not exists

						$folders = explode('/', $resetArray["Path"]);
						foreach($folders as $k => $v){
							if($k != 0 && $k != (count($folders) - 1)){

								$parentID = (isset($_SESSION['weS']['versions']['lastPathID'])) ? $_SESSION['weS']['versions']['lastPathID'] : 0;
								$folder = (defined('OBJECT_FILES_TABLE') && $resetArray['documentTable'] == OBJECT_FILES_TABLE ?
										new we_class_folder() : new we_folder());

								$folder->we_new();
								$folder->setParentID($parentID);
								$folder->Table = $resetArray["documentTable"];
								$folder->Text = $v;
								$folder->CreationDate = time();
								$folder->ModDate = time();
								$folder->Filename = $v;
								$folder->Published = time();
								$folder->Path = $folder->getPath();
								$folder->CreatorID = isset($_SESSION["user"]["ID"]) ? $_SESSION["user"]["ID"] : "";
								$folder->ModifierID = isset($_SESSION["user"]["ID"]) ? $_SESSION["user"]["ID"] : "";
								$existsFolderPathID = f('SELECT ID FROM ' . $db->escape($resetArray["documentTable"]) . " WHERE Path='" . $db->escape($folder->Path) . "' AND IsFolder=1 ", '', $db);
								if(empty($existsFolderPathID)){
									$folder->we_save();
									$_SESSION['weS']['versions']['lastPathID'] = $folder->ID;
								} else {
									$_SESSION['weS']['versions']['lastPathID'] = $existsFolderPathID;
								}
							}
						}

						$resetDoc->ID = 0;
						$resetDoc->ParentID = $_SESSION['weS']['versions']['lastPathID'];
						$resetDoc->Path = $resetArray["Path"];
					}
				}

				$existsFile = f('SELECT COUNT(1) as Count FROM ' . $db->escape($resetArray["documentTable"]) . ' WHERE ID!=' . intval($resetArray["documentID"]) . " AND Path= '" . $db->escape($resetDoc->Path) . "' ", '', $db);

				$doPark = false;
				if($existsFile){
					$resetDoc->Path = str_replace($resetDoc->Text, "_" . $resetArray["documentID"] . "_" . $resetDoc->Text, $resetDoc->Path);
					$resetDoc->Text = "_" . $resetArray["documentID"] . "_" . $resetDoc->Text;
					if(isset($resetDoc->Filename) && $resetDoc->Filename != ""){
						$resetDoc->Filename = "_" . $resetArray["documentID"] . "_" . $resetDoc->Filename;
						$publish = 0;
						$doPark = true;
					}
				}

				if((isset($_SESSION['weS']['versions']['lastPathID']))){
					unset($_SESSION['weS']['versions']['lastPathID']);
				}

				$resetDoc->resetFromVersion = $version;

				$resetDoc->saveInSession($_SESSION['weS']['we_data'][$we_transaction]);

				$GLOBALS['we_doc'] = $resetDoc;


				we_temporaryDocument::delete($resetDoc->ID, $resetDoc->Table, $db);
//$resetDoc->initByID($resetDoc->ID);
				$resetDoc->ModDate = time();
				$resetDoc->Published = $resetArray["timestamp"];

				$wasPublished = f('SELECT status FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($resetArray["documentID"]) . " AND documentTable='" . $db->escape($resetArray["documentTable"]) . "' AND status='published' ORDER BY version DESC LIMIT 1", '', $db);
				$publishedDoc = $_SERVER['DOCUMENT_ROOT'] . $resetDoc->Path;
				$publishedDocExists = true;
				if($resetArray['ContentType'] != we_base_ContentTypes::OBJECT_FILE){
					$publishedDocExists = file_exists($publishedDoc);
				}
				if($doPark || !$wasPublished || !$publishedDocExists){
					$resetDoc->Published = 0;
				}
				if($publish){
					$_SESSION['weS']['versions']['doPublish'] = true;
				}
				$resetDoc->we_save();
				if($publish){
					unset($_SESSION['weS']['versions']['doPublish']);
					$resetDoc->we_publish();
				}

				if(defined('WORKFLOW_TABLE') && $resetDoc->ContentType == we_base_ContentTypes::WEDOCUMENT){
					if(we_workflow_utility::inWorkflow($resetDoc->ID, $resetDoc->Table)){
						we_workflow_utility::removeDocFromWorkflow($resetDoc->ID, $resetDoc->Table, $_SESSION["user"]["ID"], "");
					}
				}

				$_SESSION['weS']['versions']['logResetIds'][$resetArray['ID']] = array(
					'Text' => $resetArray['Text'],
					'ContentType' => $resetArray['ContentType'],
					'Path' => $resetArray['Path'],
					'Version' => $resetArray['version'],
					'documentID' => $resetArray['documentID'],
				);

//update versions if id or path were changed
				if(!$existsInFileTable){
					$db->query('UPDATE ' . VERSIONS_TABLE . ' SET documentID=' . intval($resetDoc->ID) . ',ParentID=' . intval($resetDoc->ParentID) . ',active=0 WHERE documentID=' . intval($oldId) . " AND ContentType='" . $db->escape($oldCt) . "'");
				}
			}
		}
	}

	public static function showValue($k, $v, $table = ''){
		$val = self::_showValue($k, $v, $table);
		return ($val ? : '&nbsp;');
	}

	/**
	 * @abstract return the fieldvalue that has been changed
	 */
	private static function _showValue($k, $v, $table){

		$pathLength = 41;

		$db = new DB_WE();

		switch($k){
			case 'timestamp':
				return date("d.m.y - H:i:s", $v);
			case 'status':
				return g_l('versions', '[' . $v . ']');
			case 'ParentID':
				return id_to_path($v, $table);
			case 'modifierID':
			case 'CreatorID':
				return id_to_path($v, USER_TABLE);
			case 'MasterTemplateID':
			case 'TemplateID':
				return ($v == 0 ? '' : id_to_path($v, TEMPLATES_TABLE));
			case 'InGlossar':
			case 'IsDynamic':
			case 'IsSearchable':
				return g_l('versions', ($v == 1) ? '[activ]' : '[notactiv]');
			case 'DocType':
				return f('SELECT DocType FROM ' . DOC_TYPES_TABLE . ' WHERE ID=' . intval($v), 'DocType', $db);
			case 'RestrictOwners':
				return g_l('versions', ($v == 1) ? '[activ]' : '[notactiv]');
			case 'Language':
				return isset($GLOBALS['weFrontendLanguages'][$v]) ? $GLOBALS['weFrontendLanguages'][$v] : '';
			case 'WebUserID':
				return id_to_path($v, CUSTOMER_TABLE);
			case 'Workspaces':
				$fieldValueText = '';
				if($v != ''){
					$vals = makeArrayFromCSV($v);
					if(!empty($vals)){
						foreach($vals as $k){
							if($fieldValueText != ''){
								$fieldValueText .= '<br/>';
							}
							$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($k, FILE_TABLE), $pathLength);
						}
					}
				}
				return $fieldValueText;
			case 'ExtraWorkspaces':
				$fieldValueText = '';
				if($v != ''){
					$vals = makeArrayFromCSV($v);
					if(!empty($vals)){
						foreach($vals as $k){
							if($fieldValueText != ""){
								$fieldValueText .= "<br/>";
							}
							$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($k, FILE_TABLE), $pathLength);
						}
					}
				}
				return $fieldValueText;
			case 'ExtraWorkspacesSelected':
				$fieldValueText = '';
				if($v != ''){
					$vals = makeArrayFromCSV($v);
					if(!empty($vals)){
						foreach($vals as $k){
							if(!empty($fieldValueText)){
								$fieldValueText .= '<br/>';
							}
							$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($k, FILE_TABLE), $pathLength);
						}
					}
				}
				return $fieldValueText;
			case 'Templates':
				$fieldValueText = '';
				if($v != ''){
					$vals = makeArrayFromCSV($v);
					if(!empty($vals)){
						foreach($vals as $k){
							if(!empty($fieldValueText)){
								$fieldValueText .= '<br/>';
							}
							$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($k, FILE_TABLE), $pathLength);
						}
					}
				}
				return $fieldValueText;
			case 'ExtraTemplates':
				$fieldValueText = '';
				if($v != ''){
					$vals = makeArrayFromCSV($v);
					if(!empty($vals)){
						foreach($vals as $k){
							if(!empty($fieldValueText)){
								$fieldValueText .= '<br/>';
							}
							$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($k, FILE_TABLE), $pathLength);
						}
					}
				}
				return $fieldValueText;
			case 'fromScheduler':
				return g_l('versions', ($v == 1) ? '[yes]' : '[no]');
			case 'fromImport':
				return g_l('versions', ($v == 1) ? '[yes]' : '[no]');
			case 'resetFromVersion':
				return ($v == 0) ? "-" : $v;
			case 'Category':
				$fieldValueText = "";
				$v = makeArrayFromCSV($v);
				if(!empty($v)){
					foreach($v as $key){
						if($fieldValueText != ""){
							$fieldValueText .= "<br/>";
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, CATEGORY_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
			case 'Owners':
				$fieldValueText = "";
				$v = makeArrayFromCSV($v);
				if(!empty($v)){
					foreach($v as $key){
						if($fieldValueText != ""){
							$fieldValueText .= "<br/>";
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, USER_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
			case 'OwnersReadOnly':
				$fieldValueText = "";
				if($v != '' && !is_array($v)){
					$v = unserialize($v);
				}
				if(is_array($v) && !empty($v)){
					foreach($v as $key => $val){
						if($fieldValueText != ""){
							$fieldValueText .= "<br/>";
						}
						$stat = g_l('versions', ($val == 1) ? '[activ]' : '[notactiv]');
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, USER_TABLE), $pathLength) . ": " . $stat;
					}
				}
				return $fieldValueText;
			case 'weInternVariantElement':
				$fieldValueText = "";
				if($v != '' && !is_array($v)){
					$v = unserialize($v);
				}
				if(is_array($v) && !empty($v)){
					foreach($v as $key => $val){
						if(is_array($val)){
							foreach($val as $k => $vl){
								if($k != ""){
									$fieldValueText .= "<strong>" . $k . "</strong><br/>";
								}
								if(is_array($val)){
									foreach($vl as $key3 => $val3){
										if($key3 != ""){
											$fieldValueText .= $key3 . ': ';
										}
										if(isset($val3['dat']) && $val3['dat'] != ""){
											$fieldValueText .= $val3['dat'] . '<br/>';
										}
									}
								}
							}
						}
					}
				}
				return $fieldValueText . '<br/>';
//Scheduler
			case 'task':
				return ($v ? g_l('versions', '[' . $k . '_' . $v . ']') : '');
			case 'type':
				return g_l('versions', '[type_' . $v . ']');
			case 'active':
				return g_l('versions', ($v == 1) ? '[yes]' : '[no]');
			case 'months':
				$months = array();
				if(is_array($v) && !empty($v)){
					foreach($v as $k => $v){
						if($v == 1){
							$months[] = g_l('date', '[month][short][' . $k . ']');
						}
					}
				}
				return makeCSVFromArray($months, false, ', ');
			case 'days':
				$days = array();
				if(is_array($v) && !empty($v)){
					foreach($v as $k => $v){
						if($v == 1){
							$day = $k + 1;
							if(strlen($day) == 1){
								$day = '0' . $day;
							}
							$days[] = $day;
						}
					}
				}
				return makeCSVFromArray($days, false, ', ');
			case 'weekdays':
				$weekdays = array();
				if(is_array($v) && !empty($v)){
					foreach($v as $k => $v){
						if($v == 1){
							$weekdays[] = g_l('date', '[day][short][' . $k . ']');
						}
					}
				}

				return makeCSVFromArray($weekdays, false, ", ");
			case 'time':
				return date('d.m.y - H:i:s', $v);
			case 'doctypeAll':
				return ($v == 1) ? g_l('versions', '[yes]') : '';
			case 'DoctypeID':
				return f('SELECT DocType FROM ' . DOC_TYPES_TABLE . ' WHERE ID=' . intval($v), 'DocType', $db);
			case 'CategoryIDs':
				$fieldValueText = "";
				$v = makeArrayFromCSV($v);
				if(!empty($v)){
					foreach($v as $key){
						if($fieldValueText != ''){
							$fieldValueText .= '<br/>';
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, CATEGORY_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
//Customer Filter
			case '_id':
				return ($v ? : 0);
			case '_accessControlOnTemplate':
				return g_l('versions', ($v == 1) ? '[yes]' : '[no]');
			case '_errorDocNoLogin':
				return we_util_Strings::shortenPathSpace(id_to_path($v, FILE_TABLE), $pathLength);
			case '_errorDocNoAccess':
				return we_util_Strings::shortenPathSpace(id_to_path($v, FILE_TABLE), $pathLength);
			case '_mode':
				switch($v){
					case 0:
						return g_l('modules_customerFilter', '[mode_off]');
					case 1:
						return g_l('modules_customerFilter', '[mode_all]');
					case 2:
						return g_l('modules_customerFilter', '[mode_specific]');
					case 3:
						return g_l('modules_customerFilter', '[mode_filter]');
					default:
						return '';
				}
			case '_specificCustomers':
				$fieldValueText = "";
				if(is_array($v) && !empty($v)){
					foreach($v as $key){
						if($fieldValueText != ''){
							$fieldValueText .= '<br/>';
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, CUSTOMER_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
			case '_blackList':
				$fieldValueText = "";
				if(is_array($v) && !empty($v)){
					foreach($v as $key){
						if($fieldValueText != ""){
							$fieldValueText .= "<br/>";
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, CUSTOMER_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
			case '_whiteList':
				$fieldValueText = '';
				if(is_array($v) && !empty($v)){
					foreach($v as $key){
						if($fieldValueText != ''){
							$fieldValueText .= '<br/>';
						}
						$fieldValueText .= we_util_Strings::shortenPathSpace(id_to_path($key, CUSTOMER_TABLE), $pathLength);
					}
				}
				return $fieldValueText;
			case '_filter':
				$fieldValueText = "";
				if(is_array($v) && !empty($v)){
					foreach($v as $key => $val){
						$fieldValueText .= $key . ":<br/>";
						if(is_array($val) && !empty($val)){
							foreach($val as $key2 => $val2){
								$fieldValueText .= $key2 . ':' . $val2 . '<br/>';
							}
						}
					}
				}
				return $fieldValueText;
			default:
				return $v;
		}
	}

	/**
	 * @abstract get array of fieldnames from $table
	 * @return array of fieldnames
	 */
	private static function getFieldsFromTable($table, we_database_base $db){
		$fieldNames = array();

		$tableInfo = $db->metadata($table);
		foreach($tableInfo as $cur){
			$fieldNames[] = $cur["name"];
		}

		return $fieldNames;
	}

	/**
	 * @abstract convert object to array
	 * @return array
	 */
	private static function objectToArray($obj){
		$arr = array();
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;

		foreach($_arr as $key => $val){
			//$val = (is_array($val) || is_object($val)) ? self::objectToArray($val) : $val;
			$arr[$key] = (is_array($val) ? self::objectToArray($val) : (is_object($val) ? serialize($val) : $val));
		}

		return $arr;
	}

	private static function removeUnneededCompareFields(&$doc){
		unset($doc['Published'], $doc['ModDate'], $doc['RebuildDate'], $doc['EditPageNr'], $doc['DocStream'], $doc['DB_WE'], $doc['Filehash'], $doc['usedElementNames'], $doc['hasVariants'], $doc['editorSaves']);
		return $doc;
	}

	private static function getHashValue(array $a, $inner = false){
		$tmp = '';
		$keys = array_keys($a);
		sort($keys);
		foreach($keys as $key){
			$tmp.=$key . (is_array($a[$key]) ? self::getHashValue($a[$key], true) : $a[$key]);
		}
		return ($inner ? $tmp : md5($tmp));
	}

	/**
	 * @abstract get last record of $docID which was saved or published
	 * @return array with fields and values
	 */
	private static function getLastEntry($docID, $docTable, we_database_base $db){
		return getHash('SELECT * FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($docID) . " AND documentTable='" . $db->escape($docTable) . "' AND status IN ('saved','published','unpublished','deleted') ORDER BY version DESC LIMIT 1", $db, MYSQL_ASSOC);
	}

	public static function versionExists($docID, $docTable){
		$db = new DB_WE();
		return f('SELECT 1 FROM ' . VERSIONS_TABLE . ' WHERE documentID=' . intval($docID) . " AND documentTable='" . $db->escape($docTable) . "' AND status IN ('saved','published','unpublished','deleted') LIMIT 1", '', $db);
	}

	/**
	 * @abstract get values of modifications for DB-entry
	 * @return array with fields and values
	 */
	function getConstantsOfMod($modArray){
		$const = array();
		foreach($modArray as $v){
			if(isset($this->modFields[$v])){
				$const[] = $this->modFields[$v];
			}
		}

		return makeCSVFromArray($const);
	}

	public static function todo($data, $printIt = true){
		if($printIt){
			$_newLine = count($_SERVER['argv']) ? "\n" : "<br/>\n";
		}

		switch($data["type"]){
			case 'version_delete':
				/* FIXME: why is this not active???

				  weVersions::deleteVersion($data["ID"]);
				  $_SESSION['weS']['versions']['logDeleteIds'][$data["ID"]]['Version'] = $data["version"];
				  $_SESSION['weS']['versions']['logDeleteIds'][$data["ID"]]['Text'] = $data["text"];
				  $_SESSION['weS']['versions']['logDeleteIds'][$data["ID"]]['ContentType'] = $data["contenttype"];
				  $_SESSION['weS']['versions']['logDeleteIds'][$data["ID"]]['Path'] = $data["path"];
				  $_SESSION['weS']['versions']['logDeleteIds'][$data["ID"]]['documentID'] = $data["documentID"];
				 */
				break;
			case "version_reset" :
				$publish = we_base_request::_(we_base_request::BOOL, 'reset_doPublish');
				self::resetVersion($data["ID"], $data["version"], $publish);

				//FIXME: isn't this already set in resetVersion
				$_SESSION['weS']['versions']['logResetIds'][$data["ID"]] = array(
					'Text' => $data["text"],
					'ContentType' => $data["contenttype"],
					'Path' => $data["path"],
					'Version' => $data["version"],
					'documentID' => $data["documentID"],
				);

				break;

			default :
				return false;
		}
	}

}
