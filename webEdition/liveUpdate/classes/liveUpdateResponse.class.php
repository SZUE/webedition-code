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
class liveUpdateResponse{

	var $Type;
	var $Headline;
	var $Content;
	var $Header;
	var $Code;
	var $EncodedCode;
	var $Encoding = false;

	function initByArray($respArray){
		foreach($respArray as $key => $value){
			$this->$key = $value;
		}

		if($this->Encoding && $this->EncodedCode){
			$this->Code = base64_decode($this->EncodedCode);
		}
	}

	/**
	 * init the object with the response from the update-server
	 *
	 * @param string $response
	 * @return boolean
	 */
	function initByHttpResponse($response){
		if(($respArr = liveUpdateResponse::responseToArray($response))){
			$this->initByArray($respArr);
			return true;
		}
		return false;
	}

	function isError(){
		return ($this->Type === 'state' && $this->State === 'error');
	}

	function getField($fieldname){
		return (isset($this->$fieldname) ? $this->$fieldname : '');
	}

	function responseToArray($response){
		$respArray = @unserialize(base64_decode($response));
		return (is_array($respArray) ? $respArray : false);
	}

	function getOutput(){
		switch($this->Type){
			case 'template':
				return liveUpdateTemplates::getHtml($this->Headline, $this->Content, $this->Header);
			case 'executePatches':
				return liveUpdateFunctionsServer::executeAllPatches();
			case 'eval':
				//t_e($this->Code);
				$c = strtr($this->Code, array(
/*					'we_forms' => 'we_html_forms',
					'$we_button->' => 'we_html_button::',
					'new we_button()' => '""',
 */
					'getMysqlVer' => 'we_database_base::getMysqlVer',
				));
//FIXME:eval
				//t_e($c);
				return eval('?>' . $c);
			case 'state':
				return liveUpdateFrames::htmlStateMessage();
			//return 'Meldung vom Server:<br />Status: ' . $this->State . '<br />Meldung: ' . $this->Message;
			default:
				return $this->Type . ' is not implemented yet';
		}
	}

}
