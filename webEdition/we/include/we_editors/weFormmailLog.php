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
if(permissionhandler::hasPerm('administrator')){

	if(weRequest('bool', 'clearlog')){
		$GLOBALS['DB_WE']->query('DELETE FROM ' . FORMMAIL_LOG_TABLE);
	}

	$close = we_html_button::create_button("close", "javascript:self.close();");
	$refresh = we_html_button::create_button("refresh", "javascript:location.reload();");
	$deleteLogBut = we_html_button::create_button("clear_log", "javascript:clearLog()");


	$headline = array(
		array('dat' => we_html_element::htmlB("IP Adresse")),
		array('dat' => we_html_element::htmlB("Datum/Uhrzeit"))
	);

	$content = array();

	$count = 15;
	$start = weRequest('int', 'start', 0);
	$start = $start < 0 ? 0 : $start;

	$nextprev = "";

	$num_all = f('SELECT count(1) FROM ' . FORMMAIL_LOG_TABLE);

	$GLOBALS['DB_WE']->query('SELECT ip,DATE_FORMAT(unixTime,"' . g_l('weEditorInfo', '[mysql_date_format]') . '") AS unixTime FROM ' . FORMMAIL_LOG_TABLE . ' ORDER BY unixTime DESC LIMIT ' . abs($start) . ',' . abs($count));
	$num_rows = $GLOBALS['DB_WE']->num_rows();
	if($num_rows > 0){
		while($GLOBALS['DB_WE']->next_record()){
			$content[] = array(
				array('dat' => $GLOBALS['DB_WE']->f("ip")),
				array('dat' => $GLOBALS['DB_WE']->f("unixTime"))
			);
		}

		$next = $start + $count;

		$nextprev = '<table style="margin-top: 10px;" border="0" cellpadding="0" cellspacing="0"><tr><td>' .
			($start > 0 ?
				we_html_button::create_button("back", $_SERVER['SCRIPT_NAME'] . "?start=" . ($start - $count)) : //bt_back
				we_html_button::create_button("back", "", false, 100, 22, "", "", true)
			) .
			we_html_tools::getPixel(23, 1) . "</td><td align='center' class='defaultfont' width='120'><b>" . ($start + 1) . "&nbsp;-&nbsp;" .
			min($num_all, $start + $count) .
			"&nbsp;" . g_l('global', "[from]") . " " . ($num_all) . "</b></td><td>" . we_html_tools::getPixel(23, 1) .
			($next < $num_all ?
				we_html_button::create_button("next", $_SERVER['SCRIPT_NAME'] . "?start=" . $next) : //bt_next
				we_html_button::create_button("next", "", "", 100, 22, "", "", true)
			) .
			"</td></tr></table>";

		$parts = array(
			array(
				'headline' => '',
				'html' => we_html_tools::htmlDialogBorder3(730, 300, $content, $headline) . $nextprev,
				'space' => 0,
				'noline' => 1
			)
		);
	} else {
		$parts = array(
			array(
				'headline' => '',
				'html' => we_html_element::htmlSpan(array('class' => 'middlefontgray'), g_l('prefs', '[log_is_empty]')) .
				we_html_element::htmlBr() .
				we_html_element::htmlBr(),
				'space' => 0,
				'noline' => 1
			)
		);
	}

	$body = we_html_element::htmlBody(array("class" => "weDialogBody"), we_html_multiIconBox::getHTML("show_log_data", "100%", $parts, 30, we_html_button::position_yes_no_cancel($refresh, $close, $deleteLogBut), -1, '', '', false, g_l('prefs', '[formmail_log]'), "", 558) .
			we_html_element::jsElement("self.focus();")
	);


	$script = we_html_element::jsElement('
function clearLog() {
	if (confirm("' . addslashes(g_l('prefs', '[clear_log_question]')) . '")) {
		document.location="' . $_SERVER['SCRIPT_NAME'] . '?clearlog=1";
	}
}');

	echo getHTMLDocument($body, $script);
}

function getHTMLDocument($body, $head = ""){
	$head = we_html_tools::getHtmlInnerHead(g_l('prefs', '[formmail_log]')) . STYLESHEET . $head;
	return we_html_element::htmlDocType() . we_html_element::htmlHtml(
			we_html_element::htmlHead($head) .
			$body
	);
}
