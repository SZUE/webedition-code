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
 * Base Class for all Customer Filters (Model)
 *
 */
abstract class weAbstractCustomerFilter{

	const OFF = 0;
	const ALL = 1;
	const SPECIFIC = 2;
	const FILTER = 3;
	const NONE = 4;
	const OP_EQ = 0;
	const OP_NEQ = 1;
	const OP_LESS = 2;
	const OP_LEQ = 3;
	const OP_GREATER = 4;
	const OP_GEQ = 5;
	const OP_STARTS_WITH = 6;
	const OP_ENDS_WITH = 7;
	const OP_CONTAINS = 8;
	const OP_IN = 9;

	/**
	 * Mode. Can be OFF, ALL, SPECIFIC, FILTER
	 *
	 * @var integer
	 */
	var $_mode = self::OFF;

	/**
	 * Array with customer ids. Only relevant when $_mode is SPECIFIC
	 *
	 * @var array
	 */
	var $_specificCustomers = array();

	/**
	 * Array with customer ids. Only relevant when $_mode is FILTER
	 *
	 * @var array
	 */
	var $_blackList = array();

	/**
	 * Array with customer ids. Only relevant when $_mode is FILTER
	 *
	 * @var array
	 */
	var $_whiteList = array();

	/**
	 * Array with filter Settings
	 *
	 * @var array
	 */
	var $_filter = array();

	/* ################### CONSTRUCTOR #################### */

	/**
	 *
	 * @param integer $mode
	 * @param array $specificCustomers
	 * @param array $blackList
	 * @param array $whiteList
	 * @param array $filter
	 * @return weAbstractCustomerFilter
	 */
	function __construct($mode = self::OFF, $specificCustomers = array(), $blackList = array(), $whiteList = array(), $filter = array()){
		$this->setMode($mode);
		$this->setSpecificCustomers($specificCustomers);
		if(is_array($blackList)){
			$this->setBlackList($blackList);
		}
		if(is_array($whiteList)){
			$this->setWhiteList($whiteList);
		}
		if(is_array($filter)){
			$this->setFilter($filter);
		}
	}

	/* ##################### End of constructor ################################ */

	/**
	 * checks if customer has access with the actual filter object
	 *
	 * @return boolean
	 */
	function customerHasAccess(){
		switch($this->_mode){
			case self::OFF:
				return true;
			case self::ALL:
				return self::customerIsLogedIn();
			case self::NONE:
				return !self::customerIsLogedIn();
			case self::SPECIFIC:
				if(!self::customerIsLogedIn()){
					return false;
				}
				return in_array($_SESSION["webuser"]["ID"], $this->_specificCustomers);
			case self::FILTER:
				if(!( isset($_SESSION) && isset($_SESSION["webuser"]) && isset($_SESSION["webuser"]["ID"]) )){
					return false;
				}
				return self::customerHasFilterAccess();
		}
		return false;
	}

	/**
	 * Checks if customer matches $this->_filter array
	 *
	 * @return boolean
	 */
	function customerHasFilterAccess(){

		if(in_array($_SESSION["webuser"]["ID"], $this->_blackList)){
			return false;
		} else if(in_array($_SESSION["webuser"]["ID"], $this->_whiteList)){
			return true;
		}

		$_filter_op = array(
			self::OP_EQ => '%s==%s',
			self::OP_NEQ => '%s<>%s',
			self::OP_LESS => '%s<%s',
			self::OP_LEQ => '%s<=%s',
			self::OP_GREATER => '%s>%s',
			self::OP_GEQ => '%s>=%s',
			self::OP_STARTS_WITH => 'self::startsWith(%s,%s)',
			self::OP_ENDS_WITH => 'self::endsWith(%s,%s)',
			self::OP_CONTAINS => 'self::contains(%s,%s)',
			self::OP_IN => 'self::in(%s,%s)'
		);

		$_conditions = array();

		$_flag = false;
		foreach($this->_filter as $_filter){
			$_conditions[] = (($_filter["logic"] && $_flag) ? ($_filter["logic"] == 'AND' ? ' && ' : ' || ') : '') . sprintf(
					$_filter_op[$_filter["operation"]], self::quote4Eval($_SESSION["webuser"][$_filter["field"]]), self::quote4Eval($_filter["value"])
			);
			$_flag = true;
		}

		$_hasPermission = false;
		eval('if (' . implode('', $_conditions) . ') { $_hasPermission = true; }');

		return $_hasPermission;
	}

	/**
	 * Creates and returns the filter array from $_REQUEST
	 *
	 * @static
	 * @return array
	 */
	static function getFilterFromRequest(){
		$_filter = array();

		if(isset($_REQUEST['filterSelect_0'])){


			$_parse = true;
			$_count = 0;

			while($_parse) {
				if(isset($_REQUEST['filterSelect_' . $_count])){

					if(isset($_REQUEST['filterLogic_' . $_count]) && $_REQUEST['filterLogic_' . $_count] == 'OR'){
						$_logic = 'OR';
					} else{
						$_logic = 'AND';
					}
					if(isset($_REQUEST['filterValue_' . $_count]) && trim($_REQUEST['filterValue_' . $_count]) <> ''){
						$_filter[] = array(
							'logic' => $_logic,
							'field' => $_REQUEST['filterSelect_' . $_count],
							'operation' => $_REQUEST['filterOperation_' . $_count],
							'value' => $_REQUEST['filterValue_' . $_count]
						);
					}
					$_count++;
				} else{
					$_parse = false;
				}
			}
		}
		return $_filter;
	}

	/**
	 * Creates and returns the specificCustomers array from $_REQUEST
	 *
	 * @static
	 * @return array
	 */
	static function getSpecificCustomersFromRequest(){
		$_customers = array();

		if(isset($_REQUEST['specificCustomersEditControl'])){
			$i = 0;
			while(true) {
				if(isset($_REQUEST[$_REQUEST['specificCustomersEditControl'] . '_variant0_' . $_REQUEST['specificCustomersEditControl'] . '_item' . $i])){
					$_customers[] = $_REQUEST[$_REQUEST['specificCustomersEditControl'] . '_variant0_' . $_REQUEST['specificCustomersEditControl'] . '_item' . $i];
					$i++;
				} else{
					break;
				}
			}
		}
		return weConvertToIds($_customers, CUSTOMER_TABLE);
	}

	/**
	 * Creates and returns the black list array from $_REQUEST
	 *
	 * @static
	 * @return array
	 */
	static function getBlackListFromRequest(){
		$_blackList = array();

		if(isset($_REQUEST['blackListEditControl'])){
			$i = 0;
			while(true) {
				if(isset($_REQUEST[$_REQUEST['blackListEditControl'] . '_variant0_' . $_REQUEST['blackListEditControl'] . '_item' . $i])){
					$_blackList[] = $_REQUEST[$_REQUEST['blackListEditControl'] . '_variant0_' . $_REQUEST['blackListEditControl'] . '_item' . $i];
					$i++;
				} else{
					break;
				}
			}
		}
		return weConvertToIds($_blackList, CUSTOMER_TABLE);
	}

	/**
	 * Creates and returns the white list array from $_REQUEST
	 *
	 * @static
	 * @return array
	 */
	static function getWhiteListFromRequest(){
		$_whiteList = array();

		if(isset($_REQUEST['whiteListEditControl'])){
			$i = 0;
			while(true) {
				if(isset($_REQUEST[$_REQUEST['whiteListEditControl'] . '_variant0_' . $_REQUEST['whiteListEditControl'] . '_item' . $i])){
					$_whiteList[] = $_REQUEST[$_REQUEST['whiteListEditControl'] . '_variant0_' . $_REQUEST['whiteListEditControl'] . '_item' . $i];
					$i++;
				} else{
					break;
				}
			}
		}
		return weConvertToIds($_whiteList, CUSTOMER_TABLE);
	}

	/**
	 * Checks if $haystack starts with $needle. If so, returns true, otherwise false
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @static
	 * @return boolean
	 */
	static function startsWith($haystack, $needle){
		return (strpos($haystack, $needle) === 0);
	}

	/**
	 * Checks if $haystack ends with $needle. If so, returns true, otherwise false
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @static
	 * @return boolean
	 */
	static function endsWith($haystack, $needle){
		$pos = strlen($haystack) - strlen($needle);
		return (strpos($haystack, $needle) === $pos);
	}

	/**
	 * Checks if $haystack contains $needle. If so, returns true, otherwise false
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @static
	 * @return boolean
	 */
	static function contains($haystack, $needle){
		return (strpos($haystack, $needle) !== false);
	}

	/**
	 * Checks if $value is one of the CSV Values of $comp
	 *
	 * @param string $value
	 * @param string $comp (CSV)
	 * @static
	 * @return boolean
	 */
	static function in($value, $comp){
		$comp = str_replace('\\,', '__WE_COMMA__', $comp);
		$arr = explode(',', $comp);
		foreach($arr as &$cur){
			$cur = str_replace('__WE_COMMA__', ',', $cur);
		}
		$value = explode(',', $value);
		return count(array_intersect($value, $arr)) > 0;
	}

	/**
	 * Checks if Customer is logged in. Returns true or f alse
	 *
	 * @return boolean
	 */
	static function customerIsLogedIn(){
		return isset($_SESSION) && isset($_SESSION["webuser"]) && isset($_SESSION["webuser"]["ID"]);
	}

	/**
	 * Quotes strings for use in eval statements
	 *
	 * @param string $in
	 * @static
	 * @return string
	 */
	static function quote4Eval($in){
		if(is_numeric($in)){
			return $in;
		} else{
			return '"' . str_replace("\\'", "'", addslashes($in)) . '"';
		}
	}

	/* #########################################################################################
	  ############################### mutator and accessor methods ##############################
	  ######################################################################################### */

	/**
	 * mutator method for $this->_mode
	 *
	 * @param integer $mode
	 */
	function setMode($mode){
		$this->_mode = $mode;
	}

	/**
	 * accessor method for $this->_mode
	 *
	 * @return integer
	 */
	function getMode(){
		return $this->_mode;
	}

	/**
	 * mutator method for $this->_specificCustomers
	 *
	 * @param array $mode
	 */
	function setSpecificCustomers($specificCustomers){
		$this->_specificCustomers = $specificCustomers;
	}

	/**
	 * accessor method for $this->_specificCustomers
	 *
	 * @return array
	 */
	function getSpecificCustomers(){
		return $this->_specificCustomers;
	}

	/**
	 * mutator method for $this->_blackList
	 *
	 * @param array $mode
	 */
	function setBlackList($blackList){
		$this->_blackList = $blackList;
	}

	/**
	 * accessor method for $this->_blackList
	 *
	 * @return array
	 */
	function getBlackList(){
		return $this->_blackList;
	}

	/**
	 * mutator method for $this->_whiteList
	 *
	 * @param array $mode
	 */
	function setWhiteList($whiteList){
		$this->_whiteList = $whiteList;
	}

	/**
	 * accessor method for $this->_whiteList
	 *
	 * @return array
	 */
	function getWhiteList(){
		return $this->_whiteList;
	}

	/**
	 * mutator method for $this->_filter
	 *
	 * @param array $mode
	 */
	function setFilter($filter){
		$this->_filter = $filter;
	}

	/**
	 * accessor method for $this->_filter
	 *
	 * @return array
	 */
	function getFilter(){
		return $this->_filter;
	}

}
