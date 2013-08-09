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
class we_shop_ordertag{

	var $DB_WE;
	var $class = "";
	var $id = 0;
	var $ClassName = __CLASS__;
	var $object = "";
	var $avail = false;
	var $hidedirindex = false;

	function __construct($id = 0, $condition = "", $hidedirindex = false){
		$this->DB_WE = new DB_WE;
		$this->id = $id;
		$this->hidedirindex = $hidedirindex;
		$unique = md5(uniqid(__FILE__, true));

		if($this->id){
			$this->object = new we_shop_listviewOrder($unique, 1, 0, "", 0, "(IntOrderID=" . intval($this->id) . ")" . ($condition ? " AND $condition" : ""), "", 0, $hidedirindex);
			if($this->object->next_record()){
				$this->avail = true;
			}
		}
	}

	function f($key){
		if($this->id){
			return $this->object->f($key);
		} else{
			return "";
		}
	}

}

?>