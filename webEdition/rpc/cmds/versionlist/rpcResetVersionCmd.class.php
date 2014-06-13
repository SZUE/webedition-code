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
class rpcResetVersionCmd extends rpcCmd{

	function execute(){
		$id = we_base_request::_(we_base_request::RAW, 'id');

		we_html_tools::protect();

		$ids = (stristr($id, ',') ?
				explode(',', $id) :
				array($id)
			);


		$_SESSION['weS']['versions']['logResetIds'] = array();

		foreach($ids as $id){

			$parts = array();

			if(stristr($id, '___')){
				$parts = explode("___", $id);
			}
			$id = isset($parts[0]) ? $parts[0] : $id;
			$publish = isset($parts[1]) ? $parts[1] : 0;

			if(($version = we_base_request::_(we_base_request::INT, "version", 0)) == 0){
				$version = f('SELECT version FROM ' . VERSIONS_TABLE . ' WHERE ID=' . intval($id));
			}

			weVersions::resetVersion($id, $version, $publish);
		}

		if(!empty($_SESSION['weS']['versions']['logResetIds'])){
			$versionslog = new versionsLog();
			$versionslog->saveVersionsLog($_SESSION['weS']['versions']['logResetIds'], versionsLog::VERSIONS_RESET);
		}
		unset($_SESSION['weS']['versions']['logResetIds']);
	}

}
