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
class rpcGetSearchResultCmd extends we_rpc_cmd{

	function execute(){

		$resp = new we_rpc_response();

		if(($trans = we_base_request::_(we_base_request::TRANSACTION, 'we_transaction', 0))){
			$we_dt = isset($_SESSION['weS']['we_data'][$trans]) ? $_SESSION['weS']['we_data'][$trans] : '';
		}
		$class = $we_dt[0]['ClassName']; //we_base_request::_(we_base_request::STRING, 'classname');
		$document = new $class;
		$document->we_initSessDat($we_dt);

		$versionsView = new we_versions_view($document->versionsModel);

		$GLOBALS['we_cmd_obj'] = 1;
		$content = $versionsView->getVersionsOfDoc();
		$code = $versionsView->tabListContent($content);

		$resp->setData("data", $code);

		return $resp;
	}

}
