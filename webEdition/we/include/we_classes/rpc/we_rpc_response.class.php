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
class we_rpc_response{

	var $Success = true;
	var $Reason = '';
	var $DataArray = array();

	function __construct(){

	}

	function getStatus(){
		return $this->Success;
	}

	function setStatus($success){
		$this->Success = $success;
	}

	function getReason(){
		return $this->Reason;
	}

	function setReason($reason){
		$this->Reason = $reason;
	}

	function addData($name, $data){
		$this->DataArray[$name] = $data;
	}

	public function setData($name, $data){
		$this->addData($name, $data);
	}

	function getData($name){
		return isset($this->DataArray[$name]) ? $this->DataArray[$name] : "";
	}

	function getDataArray(){
		return $this->DataArray;
	}

	function merge($response){
		$this->setStatus($response->getStatus());
		$this->setReason($response->getReason());
		$this->DataArray = array_merge($this->DataArray, $response->getDataArray());
	}

}
