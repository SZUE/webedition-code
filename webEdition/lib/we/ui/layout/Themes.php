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
define('WE_DEFAULT_THEME_NAME', 'default');
define('WE_THEMES_DIR', '/we/ui/themes');
define('WE_APP_THEMES_DIR', '/ui/themes');

if(!defined('WE_THEME_NAME')){
	define('WE_THEME_NAME', WE_DEFAULT_THEME_NAME);
}

/**
 * Class to handle the themes
 *
 * @category   we
 * @package none
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_layout_Themes{

	/**
	 * return css theme url
	 *
	 * @var string
	 */
	static public function computeCSSURL($classname, $filename = 'style.css'){
		if(substr($classname, 0, 3) === 'we_'){
			$relPath = WE_THEMES_DIR . '/' . WE_THEME_NAME . '/' . $classname . '/' . $filename;
			if(file_exists(WE_LIB_PATH . $relPath)){
				return LIB_DIR . $relPath;
			}

			$relPath = WE_THEMES_DIR . '/' . WE_DEFAULT_THEME_NAME . '/' . $classname . '/' . $filename;
			if(file_exists(WE_LIB_PATH . $relPath)){
				return LIB_DIR . $relPath;
			}
		} else {
			$parts = explode('_', $classname);
			$appName = $parts[0];

			$relPath = '/' . $appName . WE_APP_THEMES_DIR . '/' . WE_THEME_NAME . '/' . $classname . '/' . $filename;
			if(file_exists(WE_APPS_PATH . $relPath)){
				return WE_APPS_DIR . $relPath;
			}

			$relPath = '/' . $appName . WE_APP_THEMES_DIR . '/' . WE_DEFAULT_THEME_NAME . '/' . $classname . '/' . $filename;
			if(file_exists(WE_APPS_PATH . $relPath)){
				return WE_APPS_DIR . $relPath;
			}
		}
		return '';
	}

}
