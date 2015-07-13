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
abstract class we_base_country{
	const TERRITORY = 'territory';
	const REGION = 'region';
	const LANGUAGE = 'language';
	const SCRIPT = 'script';
	const MONTH = 'months';
	const DAY = 'days';

	static $last = array();

	private static function loadLang($langcode){
		$file = WE_INCLUDES_PATH . 'country/' . $langcode . '.inc.php';
		if(!file_exists($file)){
			echo 'no file' . $file;

			return false;
		}

		self::$last = array(
			$langcode => include($file)
		);
		return true;
	}

	public static function getTranslation($countrykey, $type, $langcode){
		if(!isset(self::$last[$langcode]) && !self::loadLang($langcode)){
			return false;
		}
		return empty(self::$last[$langcode][$type][$countrykey]) ? '' : self::$last[$langcode][$type][$countrykey];
	}

	public static function getTranslationList($type, $langcode){
		if(!isset(self::$last[$langcode]) && !self::loadLang($langcode)){
			return false;
		}
		return empty(self::$last[$langcode][$type]) ? '' : self::$last[$langcode][$type];
	}

}
