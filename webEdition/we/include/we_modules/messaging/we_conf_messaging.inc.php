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
define('MESSAGING_SYSTEM', '1');
define('MESSAGES_TABLE', TBL_PREFIX . 'tblMessages');
define('MSG_ACCOUNTS_TABLE', TBL_PREFIX . 'tblMsgAccounts');
define('MSG_ADDRBOOK_TABLE', TBL_PREFIX . 'tblMsgAddrbook');
define('MSG_FOLDERS_TABLE', TBL_PREFIX . 'tblMsgFolders');
define('MSG_TODO_TABLE', TBL_PREFIX . 'tblTODO');
define('MSG_TODOHISTORY_TABLE', TBL_PREFIX . 'tblTODOHistory');


define('WE_MESSAGING_MODULE_DIR', WE_MODULES_DIR . 'messaging/');
define('WE_JS_MESSAGING_MODULE_DIR', WE_JS_MODULES_DIR . 'messaging/');
define('WE_MESSAGING_MODULE_PATH', WE_MODULES_PATH . 'messaging/');

we_base_request::registerTables(array(MESSAGES_TABLE, MSG_ACCOUNTS_TABLE, MSG_ADDRBOOK_TABLE, MSG_FOLDERS_TABLE, MSG_TODO_TABLE, MSG_TODOHISTORY_TABLE));
