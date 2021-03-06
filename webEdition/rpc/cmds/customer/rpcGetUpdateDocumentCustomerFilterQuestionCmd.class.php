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
class rpcGetUpdateDocumentCustomerFilterQuestionCmd extends we_rpc_cmd{

	function execute(){
		$resp = new we_rpc_response();

		// compare filter of document with fitler of folder
		$filterOfFolder = $this->getFilterOfFolder(we_base_request::_(we_base_request::INT, 'folderId', 0), we_base_request::_(we_base_request::TABLE, 'table', FILE_TABLE));

		if(($trans = we_base_request::_(we_base_request::TRANSACTION, 'we_transaction', ''))){
			$we_dt = isset($_SESSION['weS']['we_data'][$trans]) ? $_SESSION['weS']['we_data'][$trans] : "";
		}

		// filter of document
		$doc = we_base_request::_(we_base_request::STRING, 'classname');
		$document = new $doc;
		$document->we_initSessDat($we_dt);
		$filterOfDocument = $document->documentCustomerFilter;

		$ret = (we_customer_documentFilter::filterAreQual($filterOfFolder, $filterOfDocument, true) ? 'false' : 'true');

		$resp->setData('data', $ret);

		return $resp;
	}

	function getFilterOfFolder($id, $table){
		if($id > 0){
			$folder = new we_folder();
			$folder->initByID($id, $table);
			return $folder->documentCustomerFilter;
		}
		return "";
	}

}
