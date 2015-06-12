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
echo we_html_tools::getHtmlTop() .
 STYLESHEET .
 '</head><body>';

//Bug 6119: Keine Unterstützung für curl in der XML_RSS Klasse
//daher Umstellung den Inhalt des Feeds selbst zu holen
$parsedurl = parse_url($_SESSION["prefs"]["cockpit_rss_feed_url"]);
$http_request = new we_http_request($parsedurl['path'], $parsedurl['host'], 'GET');
$http_request->executeHttpRequest();
$http_response = new we_http_response($http_request->getHttpResponseStr());
if(isset($http_response->http_headers['Location'])){//eine Weiterleitung ist aktiv
	$parsedurl = parse_url($http_response->http_headers['Location']);
	$http_request = new we_http_request($parsedurl['path'], $parsedurl['host'], 'GET');
	$http_request->executeHttpRequest();
	$http_response = new we_http_response($http_request->getHttpResponseStr());
}
$feeddata = $http_response->http_body;

$rss = new we_xml_rss($feeddata, null, $GLOBALS['WE_BACKENDCHARSET']); // Umstellung in der XML_RSS-Klasse: den string, und nicht die url weiterzugeben
$rss->parse();
$rss_out = '<div id="rss">';
foreach($rss->getItems() as $item){
	$rss_out .= '<b>' . $item['title'] . '</b><p>' . $item['description'] . " " .
		(isset($item['link']) && !empty($item['link']) ? '<a href="' . $item['link'] . '" target="_blank">' . g_l('cockpit', '[more]') . '</a>' : '') .
		"</p>" .
		we_html_tools::getPixel(1, 10) . we_html_element::htmlBr();
}
$rss_out .= '</div>';
echo $rss_out .
 '</body></html>';
