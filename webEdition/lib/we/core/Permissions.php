<?php

/**
 * webEdition SDK
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package    we_core
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Base class for permissions
 *
 * @category   we
 * @package    we_core
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_core_Permissions{

	/**
	 * check on specific permission
	 *
	 * @param string $perm
	 * @return boolean
	 */
	static function hasPerm($perm){
		return permissionhandler::hasPerm(strtoupper($perm));
	}

	/**
	 * check on permission to see a page
	 *
	 * @return string
	 */
	static function protect(){

		if(!isset($_SESSION["user"]["Username"]) || $_SESSION["user"]["Username"] == ""){
			$page = new we_ui_layout_HTMLPage();
			$page->addJSFile(JS_DIR . 'we_showMessage.js');

			$message = we_util_Strings::quoteForJSString(g_l('alert' ,'[perms_no_permissions]'), false);

			$messageCall = we_message_reporting::getShowMessageCall($message, we_message_reporting::WE_MESSAGE_NOTICE);

			$page->addInlineJS($messageCall . 'if (opener) {top.close();} else {location="/webEdition"}');
			print $page->getHTML();
			exit();
		}
	}

}
