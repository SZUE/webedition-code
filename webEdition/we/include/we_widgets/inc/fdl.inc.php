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
$oTblDiv = we_html_element::htmlDiv(array(
			'id' => 'm_' . $iCurrId . '_inline',
			"style" => "width:100%;height:" . ($aPrefs[$aProps[0]]["height"] - 25) . "px;overflow:auto;"
				), we_html_element::htmlDiv(array('id' => 'fdl_data'), $failedLoginHTML)
);
$aLang = array(
	g_l('cockpit', '[kv_failedLogins][headline]') . ' (' . $maxRows . ')', ""
);
