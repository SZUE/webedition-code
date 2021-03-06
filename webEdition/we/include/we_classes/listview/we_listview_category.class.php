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
 * class
 * @desc    class for tag <we:listview>
 *
 */
class we_listview_category extends we_listview_base{
	var $parentID = 0;
	var $catID = 0;
	var $variant = 'default';
	var $hidedirindex = false;

	/**
	 * constructor of class
	 *
	 * @param   name          string  - name of listview
	 * @param   rows          integer - number of rows to display per page
	 * @param   offset        integer - start offset of first page
	 * @param   order         string  - field name(s) to order by
	 * @param   desc          boolean - set to true, if order should be descendend
	 * @param   parentID         integer - Id from Parent Category
	 * @param   variant       string - at the moment only "default" supported
	 * @param   cols   		  integer - to display a table this is the number of cols
	 *
	 */
	function __construct($name, $rows, $offset, $order, $desc, $parentID, $catID, $cols, $parentidname, $hidedirindex){
		parent::__construct($name, $rows, $offset, $order, $desc, '', false, '', $cols);
		$this->parentID = we_base_request::_(we_base_request::INT, $parentidname, intval($parentID));
		$this->catID = trim($catID);

		if(stripos($this->order, " desc") !== false){//was #3849
			$this->order = str_ireplace(" desc", "", $this->order);
			$this->desc = true;
		}

		$this->order = trim($this->order);

		$orderstring = $this->order ? (' ORDER BY ' . $this->order . ($this->desc ? ' DESC' : '')) : '';

		$this->hidedirindex = $hidedirindex;

		if($this->catID){
			$cids = explode(",", $this->catID);
			$tail = "";
			foreach($cids as $cid){
				$tail .= 'ID=' . intval($cid) . ' OR ';
			}
			$tail = preg_replace('/^(.+) OR /', '${1}', $tail);
			$tail = '(' . $tail . ')';
		} else {
			$tail = ' ParentID=' . intval($this->parentID) . ' ';
		}

		$this->anz_all = f('SELECT COUNT(1) FROM ' . CATEGORY_TABLE . ' WHERE ' . $tail, '', $this->DB_WE);

		$this->DB_WE->query('SELECT *' . ($this->order === 'random()' ? ', RAND() as RANDOM' : '') . ' FROM ' . CATEGORY_TABLE . ' WHERE ' . $tail . ' ' . ($this->order === 'random()' ? 'ORDER BY RANDOM' : $orderstring) . (($this->maxItemsPerPage > 0) ? (' LIMIT ' . $this->start . ',' . $this->maxItemsPerPage) : ''));
		$this->anz = $this->DB_WE->num_rows();

		$this->count = 0;
	}

	function next_record(){
		if($this->DB_WE->next_record()){
			$this->Record = array(
				'WE_PATH' => $this->DB_WE->f('Path'),
				'WE_TITLE' => $this->DB_WE->f('Title'),
				'WE_DESCRIPTION' => $this->DB_WE->f('Description'),
				'Category' => $this->DB_WE->f('Category'),
				'WE_ID' => $this->DB_WE->f('ID'),
				'ParentID' => $this->DB_WE->f('ParentID'),
			);
			$this->Record['Path'] = $this->Record['WE_PATH'];
			$this->Record['ID'] = $this->Record['WE_ID'];
			$this->Record['Title'] = $this->Record['WE_TITLE'];
			$this->Record['Description'] = $this->Record['WE_DESCRIPTION'];

			$this->count++;
			return true;
		}
		$this->stop_next_row = $this->shouldPrintEndTR();
		if($this->cols && ($this->count <= $this->maxItemsPerPage) && !$this->stop_next_row){
			$this->Record = array();
			$this->DB_WE->Record = array(
				"WE_PATH" => '',
				"WE_TEXT" => '',
				"WE_ID" => '',
			);
			$this->count++;
			return true;
		}
		return false;
	}

}
