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
class we_rpc_jsonView extends we_rpc_view{

	/**
	 * @param we_rpc_response $response
	 * @return string
	 */
	function getResponse($response){
		$status = ($response->Success ? "response" : "error");


		// DONT TOUCH THIS -  this is also  used forDreamweaver extension !
		return
			'var weResponse = {
			"type":"' . $status . '",
			"data":"' . addslashes($response->getData("data")) . '"
		};'
		;
	}

}
