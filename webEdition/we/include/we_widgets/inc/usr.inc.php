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
$oTblCont = new we_html_table(
	array(
	"id" => "m_" . $iCurrId . "_inline",
	"style" => "width:" . $iWidth . "px;",
	), 1, 1);
$oTblCont->setCol(0, 0, null, $inline);
$aLang = array(
	g_l('cockpit', '[users_online]'), ' (<span id="num_users">' . $UO->getNumUsers() . '</span>)'
);

$oTblDiv = $oTblCont->getHtml();
