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
class rpcGetRssView extends we_rpc_jsonView{

	/**
	 * @param we_rpc_response $response
	 * @return string
	 */
	function getResponse($response){
		return
			'var weResponse = {
			"type":"' . ($response->Success ? "response" : "error") . '",
			"data":"' . addslashes(str_replace(array("\n", "\r"), " ", $response->getData("data"))) . '",
			"titel":"' . addslashes($response->getData("titel")) . '",
			"widgetType":"' . addslashes($response->getData("widgetType")) . '",
			"widgetId":"' . addslashes($response->getData("widgetId")) . '"
		};';
	}

}
