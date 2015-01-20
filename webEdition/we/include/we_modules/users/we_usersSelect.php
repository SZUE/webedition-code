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

we_html_tools::protect(array("NEW_USER", "NEW_GROUP", "SAVE_USER", "SAVE_GROUP", "DELETE_USER", "DELETE_GROUP"));

$_SERVER['SCRIPT_NAME'] = WE_USERS_MODULE_DIR . 'we_usersSelect.php';

$fs = new we_users_selector(we_base_request::_(we_base_request::INT, "id", 0), we_base_request::_(we_base_request::TABLE, 'table', USER_TABLE), isset($JSIDName) ? $JSIDName : '', isset($JSTextName) ? $JSTextName : '', isset($JSCommand) ? $JSCommand : '', we_base_request::_(we_base_request::RAW, "order", ""), we_base_request::_(we_base_request::INT, "rootDirID", 0), we_base_request::_(we_base_request::STRING, "filter", ""), we_base_request::_(we_base_request::BOOL, "multiple"));

$fs->printHTML(we_base_request::_(we_base_request::INT, "what", we_selector_file::FRAMESET));
