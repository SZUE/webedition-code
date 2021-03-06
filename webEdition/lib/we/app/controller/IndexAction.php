<?php

/**
 * webEdition SDK
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
 * @package none
 * @subpackage we_app_controller
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Base IndexAction Controller
 *
 * @category   we
 * @package none
 * @subpackage we_app_controller
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_app_controller_IndexAction extends Zend_Controller_Action{

	/**
	 * The default action - show the home page
	 * @return void
	 */
	public function indexAction(){
/*		we_html_tools::setHttpCode(307);
		$tmp=  str_replace('index.php', '', $this->getFrontController()->getBaseUrl());
		header('Location: '.$tmp.'redirect.php/frameset/index');

 */
		if(strpos($this->getFrontController()->getBaseUrl(), 'index.php') === false){
			$this->_redirect('index.php/frameset/index');
		} else {
			$this->_redirect('frameset/index');
		}
	}

}
