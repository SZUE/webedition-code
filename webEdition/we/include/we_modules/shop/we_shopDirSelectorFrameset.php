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
we_html_tools::protect();
$_REQUEST['id'] = $_REQUEST['we_cmd'][1];
$_REQUEST['JSIDName'] = $_REQUEST['we_cmd'][2];
$_REQUEST['JSTextName'] = $_REQUEST['we_cmd'][3];
$_REQUEST['JSCommand'] = $_REQUEST['we_cmd'][4];

require_once(WE_MODULES_PATH . 'raw/we_ShopDirSelect.php');
