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

		include_once($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we/include/we.inc.php");

		protect();
		if (we_hasPerm("administrator")) {

			if (isset($_REQUEST['clearlog']) && $_REQUEST['clearlog'] == 1) {
				$GLOBALS['DB_WE']->query("DELETE FROM " . FORMMAIL_BLOCK_TABLE);
			} else if (isset($_REQUEST['clearEntry'])) {
				$GLOBALS['DB_WE']->query("DELETE FROM " . FORMMAIL_BLOCK_TABLE . " WHERE id=" . abs($_REQUEST['clearEntry']));
			}

			$close = we_button::create_button("close","javascript:self.close();");
			$refresh = we_button::create_button("refresh","javascript:location.reload();");
			$deleteLogBut = we_button::create_button("clear_log","javascript:clearLog()");


			$headline = array();

			$headline[0] = array('dat' => we_htmlElement::htmlB(g_l('prefs','[ip_address]')));
			$headline[1] = array('dat' => we_htmlElement::htmlB(g_l('prefs','[blocked_until]')));
			$headline[2] = array('dat' => "");


			$content = array();

			$count = 15;
			$start = (isset($_REQUEST['start']) ? $_REQUEST['start'] : 0);
			$start = $start < 0 ? 0 : $start;

			$nextprev = "";

			$num_all = f("SELECT count( id ) AS num_all FROM " . FORMMAIL_BLOCK_TABLE,"num_all", $GLOBALS['DB_WE']);

			$GLOBALS['DB_WE']->query("SELECT * FROM " . FORMMAIL_BLOCK_TABLE . " ORDER BY blockedUntil DESC LIMIT ".abs($start).",".abs($count));
			$num_rows = $GLOBALS['DB_WE']->num_rows();
			if ($num_rows > 0) {
				$ind = 0;
				while ($GLOBALS['DB_WE']->next_record()) {

					$content[$ind] = array();
					$content[$ind][0]['dat'] = $GLOBALS['DB_WE']->f("ip");
					if ($GLOBALS['DB_WE']->f("blockedUntil") == -1) {
						$content[$ind][1]['dat'] = htmlspecialchars(g_l('prefs','[forever]'));
					} else {
						$content[$ind][1]['dat'] = date(g_l('weEditorInfo',"[date_format]"),$GLOBALS['DB_WE']->f("blockedUntil"));
					}
					$content[$ind][2]['dat'] = '<a href="javascript:clearEntry('.$GLOBALS['DB_WE']->f("id").',\''.$GLOBALS['DB_WE']->f("ip").'\')">' . g_l('prefs','[unblock]'). '</a>';

					$ind++;
				}

				$nextprev = '<table style="margin-top: 10px;" border="0" cellpadding="0" cellspacing="0"><tr><td>';
				if($start> 0){
					$nextprev .= we_button::create_button("back", $_SERVER['SCRIPT_NAME'] . "?start=".($start-$count)); //bt_back
				}else{
					$nextprev .= we_button::create_button("back", "", false, 100, 22, "", "", true);
				}

				$nextprev .= we_html_tools::getPixel(23,1)."</td><td align='center' class='defaultfont' width='120'><b>".($start+1)."&nbsp;-&nbsp;";

				$nextprev .= min($num_all, $start+$count);

				$nextprev .= "&nbsp;".g_l('global',"[from]")." ".($num_all)."</b></td><td>".we_html_tools::getPixel(23,1);

				$next = $start + $count;

				if($next < $num_all){
					$nextprev .= we_button::create_button("next", $_SERVER['SCRIPT_NAME'] . "?start=".$next); //bt_next
				}else{
					$nextprev .= we_button::create_button("next", "", "", 100, 22, "", "", true);
				}
				$nextprev .= "</td></tr></table>";

				$parts = array();

				$parts[]=array(
						'headline' => '',
						'html' => we_html_tools::htmlDialogBorder3(730,300,$content,$headline) . $nextprev,
						'space' => 0,
						'noline'=>1

				);
			} else {
				$parts[]=array(
						'headline' => '',
						'html' => 	we_htmlElement::htmlSpan(array('class'=>'middlefontgray'), g_l('prefs','[log_is_empty]')) .
									we_htmlElement::htmlBr() .
									we_htmlElement::htmlBr() ,
						'space' => 0,
						'noline'=>1

				);

			}

			$body=we_htmlElement::htmlBody(array("class"=>"weDialogBody"),
					we_multiIconBox::getHTML("show_log_data","100%",$parts,30,we_button::position_yes_no_cancel($refresh,$close,$deleteLogBut),-1,'','',false,g_l('prefs','[formmail_log]'),"",558) .
					we_htmlElement::jsElement("self.focus();")

			);


			$script = '<script type="text/javascript">

function clearLog() {
	if (confirm("'.addslashes(g_l('prefs','[clear_log_question]')).'")) {
		document.location="'.$_SERVER['SCRIPT_NAME'].'?clearlog=1";
	}
}
function clearEntry(id,ip) {
	var txt = "' . addslashes(g_l('prefs','[clear_block_entry_question]')) . '";


	if (confirm(txt.replace(/%s/,ip))) {
		document.location="'.$_SERVER['SCRIPT_NAME'].'?clearEntry="+id;
	}
}

</script>';

			print getHTMLDocument($body,$script);
		}


	function getHTMLDocument($body,$head=""){
		$head=str_replace(WE_DEFAULT_TITLE, g_l('prefs','[formmail_log]'), WE_DEFAULT_HEAD)."\n" . STYLESHEET . "\n".$head;
		return we_htmlElement::htmlHtml(
					we_htmlElement::htmlHead($head).
					$body
				);
	}