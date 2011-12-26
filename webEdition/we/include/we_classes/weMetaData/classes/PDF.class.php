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
 * @abstract implementation class of metadata reader for PDF metadata
 */
class weMetaData_PDF extends weMetaData{

	var $accesstypes = array("read", "write");

	function __construct($filetype){
		parent::__construct($filetype);
	}

	function weMetaData_PDF($filetype){
		$this->filetype = $filetype;
	}

	function _checkDependencies(){
		return false;
	}

	function _getMetaData($selection = ""){
		if(!$this->_valid)
			return false;
		if(is_array($selection)){
			// fetch some
		} else{
			// fetch all
		}
		return $this->metadata;
	}

}
