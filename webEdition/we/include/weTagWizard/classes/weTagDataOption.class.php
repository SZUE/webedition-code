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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
class weTagDataOption {

	/**
	 * @var string
	 */
	var $Name;
	/**
	 * value of this option, if differs from $Name
	 * @var string
	 */
	var $Value;
	/**
	 * all allowed attributes, when selecting this option
	 * @var array
	 */
	var $AllowedAttributes;
	/**
	 * required attributes, when selecting this option
	 * @var array
	 */
	var $RequiredAttributes;
	/**
	 * @var string
	 */
	var $Module;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $allowedAttributes
	 * @param array $requiredAttributes
	 */
	function __construct($name, $value = false, $module = '', $allowedAttributes = array(), $requiredAttributes = array()) {

		$this->Name = $name;

		$this->Value = ($value === false) ? $name : $value;


		// clean allowed and required attributes in case not all modules are installed
		$this->AllowedAttributes = $allowedAttributes;
		$this->RequiredAttributes = $requiredAttributes;
		$this->Module = $module;
	}

	/**
	 * @return array
	 */
	function getAssoziation() {
		return array(
				"$this->Value" => "$this->Name"
		);
	}

	/**
	 * @return string
	 */
	function getName() {
		return $this->Name;
	}

	/**
	 * @return array
	 */
	function getAllowedAttributes($tagAttributes = array()) {
		$arr = array();
		foreach ($this->AllowedAttributes as $attribute) {
			if(!is_object($attribute)){
				t_e($attribute);
				continue;
			}
			$attr=$attribute->getIdName();
			if (in_array($attr, $tagAttributes)) {
				$arr[] = $attr;
			}
		}
		return $arr;
	}

	/**
	 * @return array
	 */
	function getRequiredAttributes($tagAttributes = array()) {

		$arr = array();
		foreach ($this->RequiredAttributes as $attribute) {
			$attr=$attribute->getIdName();
			if (in_array($attr, $tagAttributes)) {
				$arr[] = $attr;
			}
		}
		return $arr;
	}

	function addTypeAttribute($attr){
		array_unshift($this->AllowedAttributes,$attr);
	}

	/**
	 * checks if this attribute should be used, checks if needed modules are installed
	 * @return boolean
	 */
	function useOption() {
		return ($this->Module == '' || in_array($this->Module, $GLOBALS['_we_active_modules']));
}
}
