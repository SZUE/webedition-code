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

include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_global.inc.php");

	switch ($_REQUEST['we_cmd'][0]) {
	case "openCatselector" :
		$noChoose = isset($_REQUEST['we_cmd'][8]) ? $_REQUEST['we_cmd'][8] : "";
	case "openDirselector" :
	case "openSelector" :
	case "openCatselector" :
	case "openDelSelector" :
		$id = isset($_REQUEST['we_cmd'][1]) ? $_REQUEST['we_cmd'][1] : "";
		$table = isset($_REQUEST['we_cmd'][2]) ? $_REQUEST['we_cmd'][2] : "";
		$JSIDName = we_cmd_dec(3);
		$JSTextName = we_cmd_dec(4);
		$JSCommand = we_cmd_dec(5);
		$sessionID = isset($_REQUEST['we_cmd'][6]) ? $_REQUEST['we_cmd'][6] : "";
		$rootDirID = isset($_REQUEST['we_cmd'][7]) ? $_REQUEST['we_cmd'][7] : "";
		$filter = isset($_REQUEST['we_cmd'][8]) ? $_REQUEST['we_cmd'][8] : "";
		$multiple = isset($_REQUEST['we_cmd'][9]) ? $_REQUEST['we_cmd'][9] : "";

		break;
	case "openDocselector" :
		$id = isset($_REQUEST['we_cmd'][1]) ? $_REQUEST['we_cmd'][1] : "";
		$table = isset($_REQUEST['we_cmd'][2]) ? $_REQUEST['we_cmd'][2] : "";
		$JSIDName = we_cmd_dec(3);
		$JSTextName = we_cmd_dec(4);
		$JSCommand = we_cmd_dec(5);
		$sessionID = isset($_REQUEST['we_cmd'][6]) ? $_REQUEST['we_cmd'][6] : "";
		$rootDirID = isset($_REQUEST['we_cmd'][7]) ? $_REQUEST['we_cmd'][7] : "";
		$filter = isset($_REQUEST['we_cmd'][8]) ? $_REQUEST['we_cmd'][8] : "";
		$open_doc = isset($_REQUEST['we_cmd'][9]) ? $_REQUEST['we_cmd'][9] : "";
		$multiple = isset($_REQUEST['we_cmd'][10]) ? $_REQUEST['we_cmd'][10] : "";
		$canSelectDir = isset($_REQUEST['we_cmd'][11]) ? $_REQUEST['we_cmd'][11] : "";
		break;
}

switch ($_REQUEST['we_cmd'][0]) {
	case "openDirselector" :
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we_dirSelect.php");
		break;
	case "openSelector" :
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we_fs.php");
		break;
	case "openDocselector" :
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we_docSelect.php");
		break;
	case "openCatselector" :
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we_catSelect.php");
		break;
	case "openDelSelector" :
		include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we_delSelect.php");
		break;
}