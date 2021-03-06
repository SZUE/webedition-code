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

/**
 * Filter model class for navigation tool
 *
 */
class we_navigation_customerFilter extends we_customer_abstractFilter{
	var $_useDocumentFilter = true;

	public function __construct($mode = self::OFF, array $specificCustomers = array(), array $blackList = array(), array $whiteList = array(), array $filter = array()){
		parent::__construct($mode, $specificCustomers, $blackList, $whiteList, $filter);
	}

	/**
	 * initialize object with a naviagtion model
	 *
	 * @param we_navigation_navigation $navModel
	 */
	function initByNavModel(&$navModel){
		// convert navigation data into data the filter model needs

		$custFilter = $navModel->CustomerFilter;

		$this->updateCustomerFilter($custFilter);

		$specCust = (isset($navModel->Customers) && is_array($navModel->Customers)) ? $navModel->Customers : array();

		$mode = we_customer_abstractFilter::OFF;
		if($navModel->LimitAccess){
			if($navModel->LimitAccess == 2){
				$mode = we_customer_abstractFilter::NOT_LOGGED_IN_USERS;
			} else if($navModel->LimitAccess == 1 && $navModel->ApplyFilter){
				$mode = we_customer_abstractFilter::FILTER;
			} else if($navModel->AllCustomers == 1){
				$mode = we_customer_abstractFilter::ALL;
			} else if(count($specCust) > 0){
				$mode = we_customer_abstractFilter::SPECIFIC;
			}
		}

		// end convert data

		$this->setBlackList(isset($navModel->BlackList) && is_array($navModel->BlackList) ? $navModel->BlackList : array());
		$this->setWhiteList(isset($navModel->WhiteList) && is_array($navModel->WhiteList) ? $navModel->WhiteList : array());
		$this->setSpecificCustomers($specCust);

		$this->setFilter($custFilter);
		$this->setMode($mode);
		$this->setUseDocumentFilter($navModel->UseDocumentFilter);
	}

	/**
	 * initialize object with a navigation item
	 *
	 * @param we_navigation_item $navItem
	 */
	function initByNavItem(&$navItem){
		switch($navItem->limitaccess){
			case 0:
				$this->setMode(we_customer_abstractFilter::OFF);
				return;
			case 2:
				$this->setMode(we_customer_abstractFilter::NOT_LOGGED_IN_USERS);
				return;
			case 1:
			default:
				if(isset($navItem->customers['filter']) && is_array($navItem->customers['filter']) && count($navItem->customers['filter'])){
					$this->setMode(we_customer_abstractFilter::FILTER);
					$custFilter = $navItem->customers['filter'];
					$this->updateCustomerFilter($custFilter);
					$this->setFilter($custFilter);

					if(isset($navItem->customers['blacklist']) && is_array($navItem->customers['blacklist']) && count($navItem->customers['blacklist'])){
						$this->setBlackList($navItem->customers['blacklist']);
					}
					if(isset($navItem->customers['whitelist']) && is_array($navItem->customers['whitelist']) && count($navItem->customers['whitelist'])){
						$this->setWhiteList($navItem->customers['whitelist']);
					}
				} else if(isset($navItem->customers['id']) && is_array($navItem->customers['id']) && count($navItem->customers['id'])){
					$this->setMode(we_customer_abstractFilter::SPECIFIC);
					$this->setSpecificCustomers($navItem->customers['id']);
				} else {
					$this->setMode(we_customer_abstractFilter::ALL);
				}
		}
	}

	/**
	 * converts old style (prior we 5.1) navigation filters to new format
	 *
	 * @param array $custFilter
	 */
	function updateCustomerFilter(&$custFilter){
		if(isset($custFilter['AND']) && isset($custFilter['OR'])){ // old style filter => convert into new style
			$newFilter = array();
			foreach($custFilter['AND'] as $f){
				$newFilter[] = array(
					'logic' => 'AND',
					'field' => $f['operand1'],
					'operation' => $f['operator'],
					'value' => $f['operand2']
				);
			}
			foreach($custFilter['OR'] as $f){
				$newFilter[] = array(
					'logic' => 'OR',
					'field' => $f['operand1'],
					'operation' => $f['operator'],
					'value' => $f['operand2']
				);
			}
			$custFilter = $newFilter;
		}
	}

	function getUseDocumentFilter(){
		return $this->_useDocumentFilter;
	}

	function setUseDocumentFilter($useDocumentFilter){
		$this->_useDocumentFilter = $useDocumentFilter;
	}

	static function getUseDocumentFilterFromRequest(){
		return we_base_request::_(we_base_request::BOOL, 'wecf_useDocumentFilter');
	}

	static function translateModeToNavModel($mode, &$model){
		switch($mode){

			case we_customer_abstractFilter::FILTER:
				$model->LimitAccess = 1;
				$model->ApplyFilter = 1;
				$model->AllCustomers = 1;
				break;

			case we_customer_abstractFilter::SPECIFIC:
				$model->LimitAccess = 1;
				$model->ApplyFilter = 0;
				$model->AllCustomers = 0;
				break;

			case we_customer_abstractFilter::ALL:
				$model->LimitAccess = 1;
				$model->ApplyFilter = 0;
				$model->AllCustomers = 1;
				break;

			case we_customer_abstractFilter::NOT_LOGGED_IN_USERS:
				$model->LimitAccess = 2;
				$model->ApplyFilter = 0;
				$model->AllCustomers = 0;
				break;

			default:
				$model->LimitAccess = 0;
		}
	}

	public static function updateByFilter(&$filterObj, $id, $table){
		switch($filterObj->getMode()){
			case we_customer_abstractFilter::FILTER:
				$limitAccess = 1;
				$applyFilter = 1;
				$allCustomers = 1;
				break;

			case we_customer_abstractFilter::SPECIFIC:
				$limitAccess = 1;
				$applyFilter = 0;
				$allCustomers = 0;
				break;

			case we_customer_abstractFilter::ALL:
				$limitAccess = 1;
				$applyFilter = 0;
				$allCustomers = 1;
				break;

			case we_customer_abstractFilter::NOT_LOGGED_IN_USERS:
				$limitAccess = 2;
				$applyFilter = 0;
				$allCustomers = 0;
				break;
			default:
				$limitAccess = 0;
				$applyFilter = 0;
				$allCustomers = 0;
		}


		$DB_WE = new DB_WE();
		$DB_WE->query('UPDATE ' . NAVIGATION_TABLE . ' SET ' .
			we_database_base::arraySetter(array(
				'LimitAccess' => $limitAccess,
				'ApplyFilter' => $applyFilter,
				'AllCustomers' => $allCustomers,
				'Customers' => implode(',', $filterObj->getSpecificCustomers()),
				//FIXME: this is due to customerfilter
				'CustomerFilter' => we_serialize($filterObj->getFilter(), SERIALIZE_JSON),
				'BlackList' => implode(',', $filterObj->getBlackList()),
				'WhiteList' => implode(',', $filterObj->getWhiteList())
			)) .
			' WHERE UseDocumentFilter=1 AND ' . we_navigation_navigation::getNavCondition($id, $table));
	}

}
