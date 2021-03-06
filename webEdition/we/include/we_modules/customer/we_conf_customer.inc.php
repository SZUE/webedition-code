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
define('CUSTOMER_TABLE', TBL_PREFIX . 'tblWebUser');
define('CUSTOMER_FILTER_TABLE', TBL_PREFIX . 'tblcustomerfilter');
define('CUSTOMER_AUTOLOGIN_TABLE', TBL_PREFIX . 'tblWebUserAutoLogin');
define('CUSTOMER_SESSION_TABLE', TBL_PREFIX . 'tblWebUserSessions');
define('CUSTOMER_AUTOLOGIN_LIFETIME', 31536000);
define('CUSTOMER_SESSION_LIFETIME', 300);
define('WE_CUSTOMER_MODULE_PATH', WE_MODULES_PATH . 'customer/');
define('WE_CUSTOMER_MODULE_DIR', WE_MODULES_DIR . 'customer/');

we_base_request::registerTables(array(
	'CUSTOMER_TABLE' => CUSTOMER_TABLE,
	'CUSTOMER_FILTER_TABLE' => CUSTOMER_FILTER_TABLE,
	'CUSTOMER_AUTOLOGIN_TABLE' => CUSTOMER_AUTOLOGIN_TABLE,
	'CUSTOMER_SESSION_TABLE' => CUSTOMER_SESSION_TABLE
));
