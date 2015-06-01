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
if($we_doc->EditPageNr != we_base_constants::WE_EDITPAGE_WORKSPACE){
	$parts = array(
		array(
			'headline' => g_l('weClass', '[path]'),
			'html' => $GLOBALS['we_doc']->formPath(),
			'space' => 140,
			'icon' => 'path.gif'
		),
		array(
			'headline' => g_l('modules_object', '[default]'),
			'html' => $GLOBALS['we_doc']->formDefault(),
			'space' => 140,
			'icon' => 'default.gif'
		),
		array(
			'headline' => g_l('weClass', '[Charset]'),
			'html' => $GLOBALS['we_doc']->formCharset(),
			'space' => 140,
			'icon' => 'charset.gif'
		),
		array(
			'headline' => g_l('weClass', '[CSS]'),
			'html' => $GLOBALS['we_doc']->formCSS(),
			'space' => 140,
			'icon' => 'css.gif'
		),
		array(
			'headline' => g_l('modules_object', '[copyClass]'),
			'html' => $GLOBALS['we_doc']->formCopyDocument(),
			'space' => 140,
			'icon' => 'copy.gif'
		)
	);
} else {
	$parts = array(
		array(
			'headline' => g_l('weClass', '[workspaces]'),
			'html' => $GLOBALS['we_doc']->formWorkspaces(),
			'space' => 140,
			'icon' => 'workspace.gif'
		),
		array(
			'headline' => g_l('modules_object', '[behaviour]'),
			'html' => $GLOBALS['we_doc']->formWorkspacesFlag(),
			'space' => 140,
			'icon' => 'display.gif'
		)
	);
}
echo we_html_multiIconBox::getJS() .
	we_html_multiIconBox::getHTML('', '100%', $parts, 30, '', -1, '', '', false);