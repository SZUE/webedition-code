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
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Base Class for Dialog Windows
 *
 * @category   we
 * @package none
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_layout_Dialog extends we_ui_layout_HTMLPage{

	protected $_headline = "";

	/**
	 * Constructor
	 *
	 * Sets object properties if set in $properties array
	 *
	 * @param array $properties associative array containing named object properties
	 * @return void
	 */
	public function __construct($properties = null){
		$this->addInlineJS('
self.dialog = opener[self.name + "_Object"];
');
		parent::__construct($properties);
	}

	/**
	 * set headline
	 *
	 * @param string $headline
	 */
	public function setHeadline($headline){
		$this->_headline = $headline;
	}

	/**
	 * retrieve headline
	 *
	 * @return string
	 */
	public function getHeadline(){
		return $this->_headline;
	}

}
