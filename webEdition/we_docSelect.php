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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();

$_SERVER['SCRIPT_NAME'] = WEBEDITION_DIR . 'we_docSelect.php';

$fs = new we_selector_document(weRequest('int', "id", 0), weRequest('table', "table", (defined('FILE_TABLE') ? FILE_TABLE : 'FF')), weRequest('js', "JSIDName", ""), weRequest('js', "JSTextName", ""), weRequest('js', "JSCommand", ""), weRequest('raw', "order", ""), 0, weRequest('int', "we_editDirID", 0), weRequest('raw', "we_FolderText", ""), weRequest('raw', "filter", ""), weRequest('int', "rootDirID", 0), weRequest('bool', "open_doc") ? ($table == (defined('FILE_TABLE') ? FILE_TABLE : 'FF') ? permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") : ($table == (defined('OBJECT_FILES_TABLE') ? OBJECT_FILES_TABLE : 'OF') ? permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_OBJECTS") : false)) : false, weRequest('bool', "multiple"), weRequest('bool', "canSelectDir"), isset($extInstanceId) ? $extInstanceId : '');


$fs->printHTML(weRequest('int', "what", we_selector_file::FRAMESET));
