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
define('VOTING_TABLE', TBL_PREFIX . 'tblvoting');
define('VOTING_LOG_TABLE', TBL_PREFIX . 'tblvotinglog');

define('WE_VOTING_MODULE_DIR', WE_MODULES_DIR . 'voting/');

we_base_request::registerTables(array(
	'VOTING_TABLE' => VOTING_TABLE,
	'VOTING_LOG_TABLE' => VOTING_LOG_TABLE
));
