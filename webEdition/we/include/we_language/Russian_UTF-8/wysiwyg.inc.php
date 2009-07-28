<?php

/**
 * webEdition CMS
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
 * @package    webEdition_language
 * @copyright  Copyright (c) 2008 living-e AG (http://www.living-e.com)
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Language file: wysiwyg.inc.php
 * Provides language strings.
 * Language: English
 */
include_once(dirname(__FILE__)."/enc_".basename(__FILE__));
include_once(dirname(__FILE__)."/enc_wysiwyg_js.inc.php");

$GLOBALS["l_wysiwyg"]["window_title"] = "Редактировать поле %s'";

$GLOBALS["l_wysiwyg"]["format"] = "Формат";
$GLOBALS["l_wysiwyg"]["fontsize"] = "Размер шрифта";
$GLOBALS["l_wysiwyg"]["fontname"] = "Название шрифта";
$GLOBALS["l_wysiwyg"]["css_style"] = "Стиль CSS";

$GLOBALS["l_wysiwyg"]["normal"] = "Обычный";
$GLOBALS["l_wysiwyg"]["h1"] = "1 Заголовок";
$GLOBALS["l_wysiwyg"]["h2"] = "2 Заголовок";
$GLOBALS["l_wysiwyg"]["h3"] = "3 Заголовок";
$GLOBALS["l_wysiwyg"]["h4"] = "4 Заголовок";
$GLOBALS["l_wysiwyg"]["h5"] = "5 Заголовок";
$GLOBALS["l_wysiwyg"]["h6"] = "6 Заголовок";
$GLOBALS["l_wysiwyg"]["pre"] = "Отформатированный";
$GLOBALS["l_wysiwyg"]["address"] = "Адрес";

$GLOBALS['l_wysiwyg']['spellcheck'] = 'Spellchecking'; // TRANSLATE
?>