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
 * @subpackage we_util_Sys
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
/**
 * Class to check php settings
  * @deprecated since version 6.4.0
*
 * @category   we
 * @package none
 * @subpackage we_util_Sys
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_util_Sys_Php{

	/**
 * @deprecated since version 6.4.0
	 * get php version
	 * @return String phpversion string without any manufacturer-part (i.e. set on ubuntu)
	 */
	public static function version(){
	t_e('deprecated',__FUNCTION__);
		return preg_replace('/[a-z-]/', '', PHP_VERSION);
	}

	/**
	 * compares specified PHP version with the currently installed webEdition version
	 * @param int $reference target version to be compared to current webEdition version
	 * @param string $operator
	 * @see we_util_Sys::_versionCompare()
 * @deprecated since version 6.4.0
	 * @example we_util_Sys_PHP::versionCompare("5.1");
	 * @example we_util_Sys_PHP::versionCompare("5.1", "<");
	 */
	public static function versionCompare($version = "", $operator = ""){
t_e('deprecated',__FUNCTION__);
		$currentVersion = self::version();
		return ($currentVersion === false || empty($version) ?
				false :
				parent::_versionCompare($version, $currentVersion, $operator));
	}

	/**
	 * checks if a given php extension is loaded
 * @deprecated since version 6.4.0
	 * @return boolean
	 */
	public static function extension($ext = ""){
t_e('deprecated',__FUNCTION__);
		return ($ext ? extension_loaded($ext) : false);
	}

	/**
	 * checks if a given ini-variable is available and returns its value
	 * @return value of the requested php.ini variable
 * @deprecated since version 6.4.0
	 * 			returns (bool)true if value is "1", "On" or "true"
	 * 			returns (bool)false if value is "0", "Off" or "false"
	 */
	public static function ini($var = ""){
	t_e('deprecated',__FUNCTION__);
	if(!$var){
			return false;
		}
		$_value = ini_get($var);
		switch($_value){
			case "0":
			case "Off":
			case "off":
			case "false":
				return false;
			case "1":
			case "On":
			case "on":
			case "true":
				return true;
			default:
				return $_value;
		}
	}

}
