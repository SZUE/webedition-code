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
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Class to display a Select
 *
 * @category   we
 * @package none
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_controls_SelectObjectfield extends we_ui_controls_Select{

	/**
	 * Default class name for Select
	 */
	const kSelectClass = 'we_ui_controls_Select';

	/**
	 * class name for disabled Select
	 */
	const kSelectClassDisabled = 'we_ui_controls_Select_disabled';

	/**
	 * objectclassid
	 *
	 * @var integer
	 */
	protected $_size = '';

	/**
	 * Constructor
	 *
	 * Sets object properties if set in $properties array
	 *
	 * @param array $properties associative array containing named object properties
	 * @return void
	 */
	public function __construct($properties = null){
		parent::__construct($properties);


		// add needed CSS files
		$this->addCSSFile(we_ui_layout_Themes::computeCSSURL(__CLASS__));

		// add needed JS Files
		$this->addJSFile(we_ui_abstract_AbstractElement::computeJSURL(__CLASS__));
	}

	/**
	 * Retrieve objectclass of select
	 *
	 * @return array
	 */
	public function getObjectclassid(){
		return $this->_objectclassid;
	}

	/**
	 * Set objectclass of select
	 *
	 * @param int $_objectclassid
	 */
	public function setObjectclassid($_objectclassid){
		$this->_objectclassid = $_objectclassid;
	}

	/**
	 * Renders and returns HTML of options
	 *
	 * @return string
	 */
	public function getOptionsHTML(){
		if(we_base_moduleInfo::isActive(we_base_moduleInfo::OBJECT)){
			$db = new DB_WE();
			$db->query('SHOW FIELDS FROM ' . OBJECT_X_TABLE . $this->getObjectclassid());
			$this->addOption(0, '-');
			while($db->next_record()){
				$this->addOption($db->f("Field"), $db->f("Field"));
			}
		}
		return parent::getOptionsHTML();
	}

}
