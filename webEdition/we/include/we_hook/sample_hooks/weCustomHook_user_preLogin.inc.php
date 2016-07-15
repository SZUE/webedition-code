<?php

/**
 * webEdition CMS
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

/**
 * if hook execution is enabled this function will be executed
 * when publishing a document, template, object or class
 * The array $param has all information about the respective document.
 *
 * IMPORTANT!
 * Copy this file to the custom_hooks folder when doing any changes
 * Files in the sample_hooks folder are not executed and are not update-safe and will be overwritten by the next webEdition update
 *
 * When using the WE-APP WE:Hookmanagement, this is done automatically by the WE-APP
 *
 * @param array $param
 */
function weCustomHook_user_preLogin($param){
	$hookHandler = $param['hookHandler'];
	$data = &$param['user']; //contains $REQUEST['s'] which is the submitted login-data
	switch($param['type']){
		case 'normal':
		case 'autoLogin':
	}
	$hookHandler->setErrorString('Login denied');
}
