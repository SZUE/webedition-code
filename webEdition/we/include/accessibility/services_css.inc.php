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
$i = 0;

$validationService[] = new validationService($i++, 'default', 'css', g_l('validation', '[service_css_upload]'), 'jigsaw.w3.org', '/css-validator/validator', 'post', 'file', 'fileupload', 'text/css', 'usermedium=all&submit=check', '.css', 1);
$validationService[] = new validationService($i++, 'default', 'css', g_l('validation', '[service_css_url]'), 'jigsaw.w3.org', '/css-validator/validator', 'get', 'uri', 'url', 'text/css', 'usermedium=all', '.css', 1);
