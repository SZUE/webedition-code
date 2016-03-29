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
class rpcTriggerJSErrorCmd extends we_rpc_cmd{

	function execute(){
		if(isset($_REQUEST['we_cmd']) && function_exists('log_error_message')){
			log_error_message(E_JS, print_r($_REQUEST['we_cmd'], true), empty($_REQUEST['we_cmd']['file']) ? '' : $_REQUEST['we_cmd']['file'], empty($_REQUEST['we_cmd']['line']) ? 0 : $_REQUEST['we_cmd']['line'], true);
		}
	}

}
