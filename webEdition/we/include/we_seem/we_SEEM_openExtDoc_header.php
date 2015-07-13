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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

we_html_tools::protect();
//	Header for a none webEdition document opened with webEdition

$_webEditionSiteUrl = getServerUrl() . SITE_DIR;
$url = we_base_request::_(we_base_request::URL, 'url');
$_errormsg = (strpos($url, $_webEditionSiteUrl) === 0 ?
		g_l('SEEM', '[ext_doc_tmp]') :
		sprintf(g_l('SEEM', '[ext_doc]'), $url));


$_table = new we_html_table(array('class' => 'default withSpace', 'style' => 'margin:5px 0 20px 0'), 1, 2);
$_table->setColContent(0, 1, we_html_element::htmlImg(array("src" => IMAGE_DIR . "alert.gif", "width" => 25, "height" => 27)));
$_table->setCol(0, 1, array("class" => "middlefontred", 'style' => 'padding-left:9px;'), $_errormsg);


echo we_html_tools::getHtmlTop(''/* FIXME: missing title */, '', '', STYLESHEET .
	we_html_element::jsScript(JS_DIR . 'attachKeyListener.js'), we_html_element::htmlBody(array("id" => 'eHeaderBody',), $_table->getHtml())
);
