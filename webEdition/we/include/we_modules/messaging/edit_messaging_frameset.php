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
$protect = we_base_moduleInfo::isActive('messaging') && we_users_util::canEditModule('messaging') ? null : array(false);
we_html_tools::protect($protect);


$what = weRequest('string', "pnt", "frameset");

if(!isset($we_transaction)){//FIXME: can this ever be set except register globals???
	$we_transaction = 0;
}
$transaction = $what == 'frameset' ? $we_transaction : weRequest('transaction', 'we_transaction', 'no_request');//FIXME: is $transaction used anywhere?

$weFrame = new we_messaging_frames(WE_MESSAGING_MODULE_DIR . 'edit_messaging_frameset.php', weRequest('string', "viewclass", 'message'), weRequest('transaction', 'we_transaction', 'no_request'), $we_transaction);
echo $weFrame->getHTMLDocumentHeader();
$weFrame->View->processVariables();
$weFrame->View->processCommands();
echo $weFrame->getHTML($what);
