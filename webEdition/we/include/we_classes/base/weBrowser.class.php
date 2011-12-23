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



class weBrowser {

	function getDownloadLinkText() {

		$map = array(
			"de" => "Deutsch",
			"nl" => "Dutch",
			"fi" => "Finnish",
			"fr" => "French",
			"pl" => "Polish",
			"ru" => "Russian",
			"es" => "Spanish"
		);

		$tmp = explode("_",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		$lang = (isset ($map[$tmp[0]]) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we/include/we_language/" . $map[$tmp[0]]))
						  ?  $map[$tmp[0]]  :  $GLOBALS["WE_LANGUAGE"];


		if (isset($_SERVER['HTTP_USER_AGENT'])) {

			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			if (strpos($ua,"safari") !== false) {
				$out = g_l('browser','[save_link_as_SAFARI]');
			} else if (strpos($ua,"msie") !== false) {
				$out = g_l('browser','[save_link_as_IE]');
			} else if (strpos($ua,"firefox") !== false) {
				$out = g_l('browser','[save_link_as_FF]');
			} else if (strpos($ua,"seamonkey") !== false) {
				$out = g_l('browser','[save_link_as_SM]');
			} else if (strpos($ua,"gecko") !== false) {
				$out = g_l('browser','[save_link_as_SM]');
			} else {
				$out = g_l('browser','[save_link_as_DEFAULT]');
			}

			return nl2br(htmlspecialchars(preg_replace('#<br\s*/?\s*>#i',"\n",$out)));
		}


	}

}
