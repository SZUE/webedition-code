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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

we_html_tools::protect();

$what = (isset($_REQUEST["pnt"])) ? $_REQUEST["pnt"] : "frameset";

if(!isset($we_transaction)){
	$we_transaction = 0;
}
$transaction = $what == 'frameset' ? $we_transaction : (isset($_REQUEST['we_transaction']) ? $_REQUEST['we_transaction'] : 'no_request');

$weFrame = new weMessagingFrames(WE_MESSAGING_MODULE_DIR . 'edit_messaging_frameset.php', isset($_REQUEST["viewclass"]) ? $_REQUEST["viewclass"] : 'message', 
		isset($_REQUEST["we_transaction"]) ? $_REQUEST["we_transaction"] : 'no_request', $we_transaction);
$weFrame->getHTML($what);