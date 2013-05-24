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
$what = isset($_REQUEST['pnt']) ? $_REQUEST['pnt'] : 'frameset';
$mode = isset($_REQUEST['art']) ? $_REQUEST['art'] : 0;

//We need to set this (and in corresponding frames, since the data in database is formated this way
if(!($mode == 'export' && isset($_REQUEST['step']) && $_REQUEST['step'] == 5) && $what != 'frameset'){
	we_html_tools::htmlTop('', DEFAULT_CHARSET);
}

$ExImport = $weFrame = null;

if($what == 'export' || $what == 'eibody' || $what == 'eifooter' || $what == 'eiload' || $what == 'import' || $what == 'eiupload'){
	$ExImport = new weCustomerEIWizard();

	$step = (isset($_REQUEST['step']) ? $_REQUEST['step'] : 0);
} else{
	$weFrame = new weCustomerFrames();
	$weFrame->View->processVariables();
	$weFrame->View->processCommands();
}

$weFrame->getHTML($what, $mode, $step);