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
 * @package none
 * @subpackage we_ui_abstract
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Base class for elements in html forms
 *
 * @category   we
 * @package none
 * @subpackage we_ui_abstract
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
abstract class we_ui_abstract_AbstractFormElement extends we_ui_abstract_AbstractElement{

	/**
	 * id attribute
	 *
	 * @var string
	 */
	protected $_name = '';

	/**
	 * Retrieve name attribute
	 *
	 * @return string
	 */
	public function getName(){
		return $this->_name;
	}

	/**
	 * Set name attribute
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name){
		$this->_name = $name;
	}

}
