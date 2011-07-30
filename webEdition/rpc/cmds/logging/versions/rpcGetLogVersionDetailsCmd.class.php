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
 * @package    webEdition_rpc
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

class rpcGetLogVersionDetailsCmd extends rpcCmd {

	function execute() {

		$resp = new rpcResponse();

		include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_logging/versions/versionsLogView.class.php");


		$id = $_REQUEST['id'];

		$code = versionsLogView::handleData($id);

		$resp->setData("data",$code) ;

		return $resp;
	}


}
