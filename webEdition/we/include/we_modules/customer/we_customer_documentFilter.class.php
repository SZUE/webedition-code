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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Customer filter (model) for document (or object) filters
 *
 */
class we_customer_documentFilter extends we_customer_abstractFilter{

	const ACCESS = 'f_1';
	const CONTROLONTEMPLATE = 'f_2';
	const NO_ACCESS = 'f_3';
	const NO_LOGIN = 'f_4';

	/**
	 * db-id of filter
	 *
	 * @var integer
	 */
	private $_id = 0;

	/**
	 * Id of model (document or object)
	 *
	 * @var integer
	 */
	private $_modelId = 0;

	/**
	 * DocumentType of model (eg. text/webEdition)
	 *
	 * @var string
	 */
	private $_modelType = '';

	/**
	 * Table where model is stored in db (eg. FILE_TABLE)
	 *
	 * @var string
	 */
	private $_modelTable = '';

	/**
	 * Flag if access control is made by template or not
	 *
	 * @var boolean
	 */
	private $_accessControlOnTemplate = false;

	/**
	 * Id of Document which is shown when customer is not logged in
	 *
	 * @var boolean
	 */
	private $_errorDocNoLogin = 0;

	/**
	 * Id of Document which is shown when customer has no acces
	 *
	 * @var boolean
	 */
	private $_errorDocNoAccess = 0;

	/**
	 * Constructor for PHP 5
	 *
	 * @param integer $id
	 * @param integer $modelId
	 * @param string $modelType
	 * @param string $modelTable
	 * @param boolean $accessControlOnTemplate
	 * @param integer $errorDocNoLogin
	 * @param integer $errorDocNoAccess
	 * @param integer $mode
	 * @param array $specificCustomers
	 * @param array $filter
	 * @param array $whiteList
	 * @param array $blackList
	 * @return we_customer_documentFilter
	 */
	function __construct($id = 0, $modelId = 0, $modelType = '', $modelTable = '', $accessControlOnTemplate = true, $errorDocNoLogin = 0, $errorDocNoAccess = 0, $mode = we_customer_abstractFilter::OFF, $specificCustomers = array(), $filter = array(), $whiteList = array(), $blackList = array()){
		parent::__construct($mode, $specificCustomers, $blackList, $whiteList, $filter);
		$this->setId($id);
		$this->setModelId($modelId);
		$this->setModelType($modelType);
		$this->setModelTable($modelTable);
		$this->setAccessControlOnTemplate($accessControlOnTemplate);
		$this->setErrorDocNoLogin($errorDocNoLogin);
		$this->setErrorDocNoAccess($errorDocNoAccess);
	}

	/**
	 * initializes and returns filter object from db object. Called after $db->query();
	 *
	 * @param we_db $db
	 * @return we_customer_documentFilter
	 */
	function getFilterByDbHash(&$hash){
		$_f = @unserialize($hash['filter']);
		return new self(
			intval($hash['id']), intval($hash['modelId']), $hash['modelType'], $hash['modelTable'], intval($hash['accessControlOnTemplate']), intval($hash['errorDocNoLogin']), intval($hash['errorDocNoAccess']), intval($hash['mode']), makeArrayFromCSV($hash['specificCustomers']), $_f, makeArrayFromCSV($hash['whiteList']), makeArrayFromCSV($hash['blackList'])
		);
	}

	/**
	 * initializes and returns filter object from request
	 *
	 * param webeditionDocument or objectFile
	 * @param mixed $model
	 * @return we_customer_documentFilter
	 */
	static function getCustomerFilterFromRequest(&$model){
		if($_REQUEST['wecf_mode'] == we_customer_abstractFilter::OFF){
			return self::getEmptyDocumentCustomerFilter();
		} else {
			$_specificCustomers = self::getSpecificCustomersFromRequest();
			$_blackList = self::getBlackListFromRequest();
			$_whiteList = self::getWhiteListFromRequest();
			$_filter = self::getFilterFromRequest();


			return new self(
				intval($_REQUEST['weDocumentCustomerFilter_id']), intval($model->ID), $model->ContentType, $model->Table, ($_REQUEST['wecf_accessControlOnTemplate'] == "onTemplate") ? 1 : 0, intval($_REQUEST['wecf_noLoginId']), intval($_REQUEST['wecf_noAccessId']), intval($_REQUEST['wecf_mode']), $_specificCustomers, $_filter, $_whiteList, $_blackList
			);
		}
	}

	/**
	 * initializes and returns filter object from model
	 *
	 * @param mixed $model
	 * @return we_customer_documentFilter
	 */
	static function getFilterOfDocument(&$model, $db = ''){
		return self::getFilterByIdAndTable($model->ID, $model->Table, $db);
	}

	/**
	 * initializes and returns filter object
	 *
	 * @param integer $id
	 * @param string $contentType
	 * @return we_customer_documentFilter
	 */
	static function getFilterByIdAndTable($id, $table, $db = ''){
		$db = ($db ? $db : new DB_WE());
		$hash = getHash('SELECT * FROM ' . CUSTOMER_FILTER_TABLE . ' WHERE modelTable="' . $db->escape(stripTblPrefix($table)) . '" AND modelId = ' . intval($id), $db);
		if(!empty($hash)){
			return self::getFilterByDbHash($hash);
		}
		return ''; // important do NOT return null
	}

	/**
	 * get additional condition for listviews
	 *
	 * @param we_listview $listview
	 * @return string
	 */
	function getConditionForListviewQuery(&$listview){
		if($listview->customerFilterType === 'off' || $listview->customerFilterType === 'false'){
			return '';
		}
		$_queryTail = '';
		$_allowedCTs = array('text/webedition', 'objectFile');

		// if customer is not logged in, all documents/objects with filters must be hidden
		$_restrictedFilesForCustomer = self::_getFilesWithRestrictionsOfCustomer($listview);

		if(get_class($listview) == 'we_search_listview'){ // search
			// build query from restricted files, regard search and normal listview
			foreach($_restrictedFilesForCustomer as $ct => $_fileArray){

				if(in_array($ct, $_allowedCTs)){

					$_idField = ($ct == 'text/webedition' ? 'DID' : 'OID');
					if(!empty($_fileArray)){
						$_queryTail .= ' AND ' . $_idField . ' NOT IN(' . implode(', ', $_fileArray) . ')';
					}
				}
			}
		} else {

			$_fileArray = array();
			// build query from restricted files, regard search and normal listview
			foreach($_restrictedFilesForCustomer as $ct => $_fileArray){

				if(in_array($ct, $_allowedCTs)){

					$_idField = ($ct == 'text/webedition' ?
							FILE_TABLE . '.ID' :
							OBJECT_X_TABLE . $listview->classID . '.OF_ID');
				}
			}
			if(!empty($_fileArray)){
				$_queryTail = ' AND ' . $_idField . ' NOT IN(' . implode(', ', $_fileArray) . ')';
			}
		}
		return $_queryTail;
	}

	/**
	 * returns empty filter object
	 *
	 * @return we_customer_documentFilter
	 */
	static function getEmptyDocumentCustomerFilter(){
		return new self();
	}

	/**
	 * compares two filters and returns true if they have equal data
	 *
	 * @param we_customer_documentFilter $filter1
	 * @param we_customer_documentFilter $filter2
	 * @param boolean $applyCheck if also model data should be compared
	 * @static
	 * @return boolean
	 */
	function filterAreQual($filter1 = '', $filter2 = '', $applyCheck = false){

		if($filter1 === ''){
			$filter1 = self::getEmptyDocumentCustomerFilter();
		}
		if($filter2 === ''){
			$filter2 = self::getEmptyDocumentCustomerFilter();
		}

		$checkFields = array('modelTable', 'accessControlOnTemplate', 'errorDocNoLogin', 'errorDocNoAccess', 'mode', 'specificCustomers', 'filter', 'whiteList', 'blackList');
		if(!$applyCheck){
			$checkFields[] = 'modelId';
			$checkFields[] = 'modelType';
		}

		for($i = 0; $i < count($checkFields); $i++){
			$_fn = 'get' . ucfirst($checkFields[$i]);
			if($filter1->$_fn($i) != $filter2->$_fn($i)){
				return false;
			}
		}
		return true;
	}

	/**
	 * gets the right error document id
	 *
	 * @param String $errorConstant
	 * @return integer
	 */
	function getErrorDoc($errorConstant){

		$_ret = 0;
		switch($errorConstant){

			case self::NO_LOGIN:
				$_ret = ($this->_errorDocNoLogin ? $this->_errorDocNoLogin : $this->_errorDocNoAccess);
				break;

			case self::NO_ACCESS:
				$_ret = ($this->_errorDocNoAccess ? $this->_errorDocNoAccess : $this->_errorDocNoLogin);
				break;
			default:
				break;
		}
		return $_ret;
	}

	/**
	 * saves the filter data in db. Call this on save method of model
	 *
	 * param webeditionDocument or objectFile
	 * @param mixed $model
	 */
	function saveForModel(&$model){
		$_db = new DB_WE();

		// check if there were any changes?
		$_docCustomerFilter = $model->documentCustomerFilter; // filter of document
		$_tmp = self::getFilterOfDocument($model, $_db); // filter stored in Database

		if(!self::filterAreQual($_docCustomerFilter, $_tmp)){ // the filter changed
			self::deleteForModel($model, $_db);

			if($_docCustomerFilter->getMode() != we_customer_abstractFilter::OFF && $model->ID){ // only save if its is active
				$_filter = $_docCustomerFilter->getFilter();
				$_filter = !empty($_filter) ? serialize($_filter) : '';
				$_specificCustomers = $_docCustomerFilter->getSpecificCustomers();
				$_specificCustomers = !empty($_specificCustomers) ? makeCSVFromArray($_specificCustomers, true) : '';
				$_blackList = $_docCustomerFilter->getBlackList();
				$_blackList = !empty($_blackList) ? makeCSVFromArray($_blackList, true) : '';
				$_whiteList = $_docCustomerFilter->getWhiteList();
				$_whiteList = !empty($_whiteList) ? makeCSVFromArray($_whiteList, true) : '';

				$_query = 'REPLACE INTO ' . CUSTOMER_FILTER_TABLE . ' SET ' . we_database_base::arraySetter(array(
						'modelId' => $model->ID,
						'modelType' => $model->ContentType,
						'modelTable' => stripTblPrefix($model->Table),
						'accessControlOnTemplate' => $_docCustomerFilter->getAccessControlOnTemplate(),
						'errorDocNoLogin' => $_docCustomerFilter->getErrorDocNoLogin(),
						'errorDocNoAccess' => $_docCustomerFilter->getErrorDocNoAccess(),
						'mode' => $_docCustomerFilter->getMode(),
						'specificCustomers' => $_specificCustomers,
						'filter' => $_filter,
						'whiteList' => $_whiteList,
						'blackList' => $_blackList
				));


				$_db->query($_query);
			}
		}
		unset($_db);
	}

	/**
	 * Call this function, when model is deleted !
	 * this function is called, when model with filter is saved (filters are resaved)
	 *
	 * param webeditionDocument or objectFile
	 * @param mixed $model
	 */
	function deleteForModel(&$model, $db = ''){
		if($model->ID){
			$_db = ($db ? $db : new DB_WE());
			$_db->query('DELETE FROM ' . CUSTOMER_FILTER_TABLE . ' WHERE modelId=' . $model->ID . ' AND modelType="' . $model->ContentType . '" AND modelTable="' . stripTblPrefix($model->Table) . '"');
		}
	}

	/**
	 * Call this function, if customer is deleted
	 *
	 * @param we_customer_customer $webUser
	 */
	function deleteWebUser(&$webUser){

		if($webUser->ID){
			$_db = new DB_WE();
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET specificCustomers=REPLACE(specificCustomers,",' . $webUser->ID . ',",",") WHERE specificCustomers LIKE "%,' . $webUser->ID . ',%"');
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET specificCustomers="" WHERE specificCustomers=","');
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET whiteList=REPLACE(whiteList,",' . $webUser->ID . ',",",") WHERE whiteList LIKE "%,' . $webUser->ID . ',%"');
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET whiteList="" WHERE whiteList=","');
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET blackList=REPLACE(blackList,",' . $webUser->ID . ',",",") WHERE blackList LIKE "%,' . $webUser->ID . ',%"');
			$_db->query('UPDATE ' . CUSTOMER_FILTER_TABLE . ' SET blackList="" WHERE blackList=","');
			unset($_db);
		}
	}

	/**
	 * Deletes all filters for given modelIds of table
	 * call this, when several models are deleted
	 */
	function deleteModel(array $modelIds, $table){
		if(!empty($modelIds)){
			$_db = new DB_WE();
			$_db->query('DELETE FROM ' . CUSTOMER_FILTER_TABLE . ' WHERE	modelId IN (' . implode(', ', $modelIds) . ')	AND modelTable = "' . $table . '"');
			unset($_db);
		}
	}

	/**
	 * private function. gets all file ids which customer can not accesss
	 *
	 * @param we_listview $listview
	 * @return array
	 */
	function _getFilesWithRestrictionsOfCustomer(&$listview){
		$_db = new DB_WE();
		$_cid = isset($_SESSION["webuser"]["ID"]) ? $_SESSION["webuser"]["ID"] : 0;
		$_filesWithRestrictionsForCustomer = array();
		$_defaultQuery = !self::customerIsLogedIn() ? '(mode=' . we_customer_abstractFilter::ALL . ') OR ' : '';

		$_blacklistQuery = " (mode=" . we_customer_abstractFilter::FILTER . " AND blackList LIKE '%,$_cid,%') ";
		$_whiteLlistQuery = " (mode=" . we_customer_abstractFilter::FILTER . " AND whiteList NOT LIKE '%,$_cid,%') ";
		$_specificCustomersQuery = " (mode=" . we_customer_abstractFilter::SPECIFIC . " AND specificCustomers NOT LIKE '%,$_cid,%') ";

		$_accessControlOnTemplateQuery = ( ($listview->customerFilterType != 'all' && $listview->customerFilterType != 'true') ? ' AND (accessControlOnTemplate = 0) ' : '' );

		// detect all files/objects with restrictions
		switch($listview->ClassName){
			case 'we_search_listview':
				$_queryForIds = 'SELECT * FROM ' . CUSTOMER_FILTER_TABLE . " WHERE $_defaultQuery $_blacklistQuery OR ( ($_specificCustomersQuery OR $_whiteLlistQuery) $_accessControlOnTemplateQuery)";
				break;
			case 'we_listview': // type="document"
				$_queryForIds = 'SELECT * FROM ' . CUSTOMER_FILTER_TABLE . " WHERE modelType='text/webedition'  AND ($_defaultQuery $_blacklistQuery OR ( ($_specificCustomersQuery OR $_whiteLlistQuery) $_accessControlOnTemplateQuery))";
				break;
			case 'we_object_listview':
			case 'we_object_listviewMultiobject': // type="object"
				$_queryForIds = 'SELECT * FROM ' . CUSTOMER_FILTER_TABLE . " WHERE modelType='objectFile' AND ($_defaultQuery $_blacklistQuery OR ( ($_specificCustomersQuery OR $_whiteLlistQuery) $_accessControlOnTemplateQuery))";
				break;
		}
		// if customer is not logged in=> return NO_LOGIN
		// else return correct filter
		// execute the query (get all existing filters)
		$_db->query($_queryForIds);

		if(!self::customerIsLogedIn()){ // visitor is not logged in
			// Vistior is not logged in => Visitor has no Access to files with filters!
			while($_db->next_record()){
				$_filesWithRestrictionsForCustomer[$_db->f("modelType")][] = $_db->f("modelId");
			}
		} else { // visitor has logged in
			$_filters = array();
			if($_db->num_rows()){

				while($_db->next_record()){
					$_filters[] = self::getFilterByDbHash($_db->Record);
				}
			}

			$__tmp = array();

			foreach($_filters as $filter){
				$_perm = $filter->accessForVisitor($__tmp, array("id" => $filter->getModelId(), "contentType" => $filter->getModelType()), false, true);
				switch($_perm){
					case self::NO_ACCESS:
					case self::NO_LOGIN:
						$_filesWithRestrictionsForCustomer[$filter->getModelType()][] = $filter->getModelId();
						break;
					case self::CONTROLONTEMPLATE:
						if($listview->customerFilterType == 'all' || $listview->customerFilterType == 'true'){
							$_filesWithRestrictionsForCustomer[$filter->getModelType()][] = $filter->getModelId();
						}
						break;
				}
			}
		}
		unset($_db);
		return $_filesWithRestrictionsForCustomer;
	}

	/**
	 * checks if visitor has acces to see the document or object
	 *
	 * @param mixed $model
	 * @param array $modelHash
	 * @param boolean $_fromIfRegisteredUser
	 * @return string
	 */
	function accessForVisitor(&$model, $modelHash = array(), $_fromIfRegisteredUser = false, $_fromListviewCheck = false){
		if(!empty($model)){
			$modelHash["id"] = $model->ID;
			$modelHash["contentType"] = $model->ContentType;
		}
		if($modelHash["id"] == $this->getModelId() && $modelHash["contentType"] == $this->getModelType()){ // model is correct
			if(!$_fromListviewCheck && $this->getAccessControlOnTemplate() && !$_fromIfRegisteredUser){
				// access control is on template (for we:ifregisteredUser)
				return self::CONTROLONTEMPLATE;
			}

			if(!self::customerIsLogedIn()){ // no customer logged in
				// visitor is NOT logged in
				return self::NO_LOGIN;
			}

			if(!$this->customerHasAccess()){
				return self::NO_ACCESS;
			}
		}
		return self::ACCESS;
	}

	/* ############################ Accessors and Mutators ################################### */

	/**
	 * Mutator method for $this->_id
	 *
	 * @param integer $id
	 */
	function setId($id){
		$this->_id = $id;
	}

	/**
	 * Accessor method for $this->_id
	 *
	 * @return integer
	 */
	function getId(){
		return $this->_id;
	}

	/**
	 * Mutator method for $this->_modelId
	 *
	 * @param integer $modelId
	 */
	function setModelId($modelId){
		$this->_modelId = $modelId;
	}

	/**
	 * Accessor method for $this->_modelId
	 *
	 * @return integer
	 */
	function getModelId(){
		return $this->_modelId;
	}

	/**
	 * Mutator method for $this->_modelType
	 *
	 * @param string $modelType
	 */
	function setModelType($modelType){
		$this->_modelType = $modelType;
	}

	/**
	 * Accessor method for $this->_modelType
	 *
	 * @return string
	 */
	function getModelType(){
		return $this->_modelType;
	}

	/**
	 * Mutator method for $this->_modelTable
	 *
	 * @param string $modelTable
	 */
	function setModelTable($modelTable){
		$this->_modelTable = $modelTable;
	}

	/**
	 * Accessor method for $this->_modelTable
	 *
	 * @return string
	 */
	function getModelTable(){
		return $this->_modelTable;
	}

	/**
	 * Mutator method for $this->_accessControlOnTemplate
	 *
	 * @param boolean $accessControlOnTemplate
	 */
	function setAccessControlOnTemplate($accessControlOnTemplate){
		$this->_accessControlOnTemplate = $accessControlOnTemplate;
	}

	/**
	 * Accessor method for $this->_accessControlOnTemplate
	 *
	 * @return boolean
	 */
	function getAccessControlOnTemplate(){
		return $this->_accessControlOnTemplate;
	}

	/**
	 * Mutator method for $this->_errorDocNoLogin
	 *
	 * @param integer $errorDocNoLogin
	 */
	function setErrorDocNoLogin($errorDocNoLogin){
		$this->_errorDocNoLogin = $errorDocNoLogin;
	}

	/**
	 * Accessor method for $this->_errorDocNoLogin
	 *
	 * @return integer
	 */
	function getErrorDocNoLogin(){
		return $this->_errorDocNoLogin;
	}

	/**
	 * Mutator method for $this->_errorDocNoAccess
	 *
	 * @param integer $errorDocNoAccess
	 */
	function setErrorDocNoAccess($errorDocNoAccess){
		$this->_errorDocNoAccess = $errorDocNoAccess;
	}

	/**
	 * Accessor method for $this->_errorDocNoAccess
	 *
	 * @return integer
	 */
	function getErrorDocNoAccess(){
		return $this->_errorDocNoAccess;
	}

}
