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

/**
 * Base class for views.
 *
 */
class we_rpc_view{
	var $CmdShell;
	protected $protocol;

	public function __construct($cmdshell, $protocol){
		$this->CmdShell = $cmdshell;
		$this->protocol = $protocol;
	}

	function getResponse(we_rpc_response $response){
		return $response->getData('data');
	}

}
