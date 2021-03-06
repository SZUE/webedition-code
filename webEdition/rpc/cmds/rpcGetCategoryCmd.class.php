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
class rpcGetCategoryCmd extends we_rpc_cmd{

	function execute(){
		$resp = new we_rpc_response();
		$error = array();
		// check for necessory params
		if(!($obj=we_base_request::_(we_base_request::STRING, 'obj'))){
			$error[] = "Missing field obj";
		}
		if(!we_base_request::_(we_base_request::STRING, 'cats')){
			$error[] = "Missing field cats";
		}
		if(we_base_request::_(we_base_request::STRING, 'part') === 'table' && (!we_base_request::_(we_base_request::BOOL, 'target'))){
			$error[] = "Missing target for table";
		}

		if($error){
			$resp->setData("error", $error);
		} else {
			//$part = we_base_request::_(we_base_request::STRING, 'part',"rows");
			$target = we_base_request::_(we_base_request::STRING, 'target', $obj . "CatTable");
			$catField = we_base_request::_(we_base_request::STRING, 'catfield', '');
			$categories = $this->getCategory($obj, we_base_request::_(we_base_request::INTLIST, 'cats', ''), $catField);
			$categories = strtr($categories, array("\r" => '', "\n" => ''));
			$resp->setData("elementsById", array($target => array("innerHTML" => $categories)));
		}
		return $resp;
	}

	function getCategory($obj, $categories, $catField = ''){
		$cats = new we_chooser_multiDirExtended(410, $categories, 'delete_' . $obj . 'Cat', '', '', '"we/category"', CATEGORY_TABLE);
		$cats->setRowPrefix($obj);
		$cats->setCatField($catField);
		$cats->setOnchangeSetHot(false);
		return $cats->getTableRows();
	}

}
