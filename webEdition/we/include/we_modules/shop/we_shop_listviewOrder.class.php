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
 * class    we_shop_listviewOrder
 * @desc    class for tag <we:listview type="'>
 *
 */
class we_shop_listviewOrder extends we_listview_base{
	var $ClassName = __CLASS__;
	var $condition = '';
	var $Path = '';
	var $docID = 0;
	var $hidedirindex = false;

	/**
	 * @desc    constructor of class
	 *
	 * @param   $name          string - name of listview
	 * @param   $rows          integer - number of rows to display per page
	 * @param   $order         string - field name(s) to ORDER BY
	 * @param   $desc		   string - if desc order
	 * @param   $condition	   string - condition of listview
	 * @param   $cols		   string - number of cols (default = 1)
	 * @param   $docID	   	   string - id of a document where a we:customer tag is on
	 *
	 */
	function __construct($name, $rows, $offset, $order, $desc, $condition, $cols, $docID, $hidedirindex){

		parent::__construct($name, $rows, $offset, $order, $desc, '', false, 0, $cols);
		if($GLOBALS['WE_MAIN_DOC']->InWebEdition){
			//do nothing inside we
			return;
		}
		$this->docID = $docID;
		$this->condition = $condition ? : (isset($GLOBALS['we_lv_condition']) ? $GLOBALS['we_lv_condition'] : '');

		if(strpos($this->condition, 'ID') !== false && strpos($this->condition, 'IntID') === false){
			$this->condition = str_replace('ID', 'IntID', $this->condition);
		}
		// und nun sind alle anderen kaputt und werden repariert
		$this->condition = str_replace('OrderIntID', 'OrderID', $this->condition);
		$this->condition = str_replace('CustomerIntID', 'CustomerID', $this->condition);
		$this->condition = str_replace('ArticleIntID', 'ArticleID', $this->condition);

		if(strpos($this->condition, 'OrderID') !== false && strpos($this->condition, 'IntOrderID') === false){
			$this->condition = str_replace('OrderID', 'IntOrderID', $this->condition);
		}
		if(strpos($this->condition, 'CustomerID') !== false && strpos($this->condition, 'IntCustomerID') === false){
			$this->condition = str_replace('CustomerID', 'IntCustomerID', $this->condition);
		}
		if(strpos($this->condition, 'ArticleID') !== false && strpos($this->condition, 'IntArticleID') === false){
			$this->condition = str_replace('ArticleID', 'IntArticleID', $this->condition);
		}
		if(strpos($this->condition, 'Quantity') !== false && strpos($this->condition, 'IntQuantity') === false){
			$this->condition = str_replace('Quantity', 'IntQuantity', $this->condition);
		}
		if(strpos($this->condition, 'Payment_Type') !== false && strpos($this->condition, 'IntPayment_Type') === false){
			$this->condition = str_replace('Payment_Type', 'Payment_Type', $this->condition);
		}

		$this->Path = $this->docID ? id_to_path($this->docID, FILE_TABLE, $this->DB_WE) : (isset($GLOBALS['we_doc']) ? $GLOBALS['we_doc']->Path : '');

		$this->hidedirindex = $hidedirindex;
		// IMPORTANT for seeMode !!!! #5317
		$this->LastDocPath = (isset($_SESSION['weS']['last_webEdition_document'])) ? $_SESSION['weS']['last_webEdition_document']['Path'] : '';


		if($this->desc && $this->order != '' && (!preg_match('|.+ desc$|i', $this->order))){
			$this->order .= ' DESC';
		}

		if($this->order != ''){
			if(trim($this->order) === 'ID' || trim($this->order) === 'CustomerID' || trim($this->order) === 'ArticleID' || trim($this->order) === 'Quantity' || trim($this->order) === 'Payment_Type'){
				$this->order = 'Int' . $this->order;
			}
			$orderstring = ' ORDER BY ' . $this->order . ' ';
		} else {
			$orderstring = '';
		}

		$where = ($this->condition ? (' WHERE ' . $this->condition) : '') . ' GROUP BY IntOrderID';

		$this->anz_all = f('SELECT COUNT(1) FROM ' . SHOP_TABLE . $where, '', $this->DB_WE);
		$format = array();
		foreach(we_shop_statusMails::$StatusFields as $field){
			$format[] = 'UNIX_TIMESTAMP(' . $field . ') AS ' . $field;
		}
		foreach(we_shop_statusMails::$MailFields as $field){
			$format[] = 'UNIX_TIMESTAMP(' . $field . ') AS ' . $field;
		}

		$this->DB_WE->query('SELECT IntOrderID as OrderID, IntCustomerID as CustomerID, IntPayment_Type as Payment_Type, strSerialOrder,' . implode(',', $format) . ' FROM ' . SHOP_TABLE . $where . ' ' . $orderstring . ' ' . (($this->maxItemsPerPage > 0) ? (' LIMIT ' . $this->start . ',' . max(100, $this->maxItemsPerPage)) : ''));
		$this->anz = $this->DB_WE->num_rows();
	}

	function next_record(){
		$ret = $this->DB_WE->next_record();
		if($ret){
			$strSerialOrder = we_unserialize($this->DB_WE->Record['strSerialOrder']);
			unset($this->DB_WE->Record['strSerialOrder']);

			if(is_array($strSerialOrder['we_sscf'])){
				foreach($strSerialOrder['we_sscf'] as $key => &$value){
					$this->DB_WE->Record[$key] = $value;
				}
				unset($value);
			}
			if(is_array($strSerialOrder['we_shopPriceShipping'])){
				foreach($strSerialOrder['we_shopPriceShipping'] as $key => &$value){
					$this->DB_WE->Record['Shipping_' . $key] = $value;
				}
				unset($value);
			}
			if(is_array($strSerialOrder['we_shopCustomer'])){
				foreach($strSerialOrder['we_shopCustomer'] as $key => &$value){
					if(!is_numeric($key)){
						$this->DB_WE->Record['Customer_' . $key] = $value;
					}
				}
				unset($value);
			}
			if(isset($strSerialOrder['we_shopPriceIsNet'])){
				$this->DB_WE->Record['shopPriceIsNet'] = $strSerialOrder['we_shopPriceIsNet'];
			}
			if(isset($strSerialOrder['we_shopCalcVat'])){
				$this->DB_WE->Record['shopCalcVat'] = $strSerialOrder['we_shopCalcVat'];
			}
			//Fix #7993
			if(isset($strSerialOrder['we_shopPricename'])){
				$this->DB_WE->Record['shopPricename'] = $strSerialOrder['we_shopPricename'];
			}

			//$this->DB_WE->Record['CustomerID'] = $this->DB_WE->Record['IntCustomerID'];
			$this->DB_WE->Record['we_cid'] = $this->DB_WE->Record['CustomerID'];
			//$this->DB_WE->Record['OrderID'] = $this->DB_WE->Record['IntOrderID'];
			$this->DB_WE->Record['we_orderid'] = $this->DB_WE->Record['OrderID'];
			$this->DB_WE->Record['wedoc_Path'] = $this->Path . '?we_orderid=' . $this->DB_WE->Record['OrderID'];
			$this->DB_WE->Record['WE_PATH'] = $this->Path . '?we_orderid=' . $this->DB_WE->Record['OrderID'];
			$this->DB_WE->Record['WE_TEXT'] = $this->DB_WE->Record['OrderID'];
			$this->DB_WE->Record['WE_ID'] = $this->DB_WE->Record['OrderID'];
			$this->DB_WE->Record['we_wedoc_lastPath'] = $this->LastDocPath . '?we_orderid=' . $this->DB_WE->Record['OrderID'];
			$this->count++;
			return true;
		}
		$this->stop_next_row = $this->shouldPrintEndTR();
		if($this->cols && ($this->count <= $this->maxItemsPerPage) && !$this->stop_next_row){
			$this->DB_WE->Record = array(
				'WE_PATH' => '',
				'WE_TEXT' => '',
				'WE_ID' => '',
			);
			$this->count++;
			return true;
		}

		return false;
	}

	function f($key){
		return $this->DB_WE->f($key);
	}

}
