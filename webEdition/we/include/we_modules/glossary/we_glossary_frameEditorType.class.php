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
class we_glossary_frameEditorType extends we_glossary_frameEditor{

	function Header($weGlossaryFrames){
		$we_tabs = new we_tabs();

		$we_tabs->addTab(new we_tab("#", g_l('modules_glossary', '[overview]'), we_tab::ACTIVE, "setTab('1');"));

		return self::buildHeader($weGlossaryFrames, $we_tabs, g_l('modules_glossary', '[type]'), g_l('modules_glossary', '[' . array_pop(explode('_', we_base_request::_(we_base_request::STRING, 'cmdid'))) . ']'));
	}

	function Body($weGlossaryFrames){
		$_js = '';

		$Temp = explode("_", we_base_request::_(we_base_request::STRING, 'cmdid'));
		$Language = $Temp[0] . "_" . $Temp[1];
		$Type = $Temp[2];
		$Cache = new we_glossary_cache($Language);
		$id = we_base_request::_(we_base_request::INT, 'ID', 0);
		if($id){
			switch(we_base_request::_(we_base_request::STRING, 'do')){
				case 'delete':
					if($GLOBALS['DB_WE']->query('DELETE FROM ' . GLOSSARY_TABLE . ' WHERE ID IN (' . implode(',', $id) . ')')){
						foreach($id as $_id){
							$_js .= $weGlossaryFrames->View->topFrame . '.deleteEntry(' . $_id . ');';
						}
					}
					$Cache->write();
					break;

				case 'publish':
					$GLOBALS['DB_WE']->query('UPDATE ' . GLOSSARY_TABLE . ' SET Published=UNIX_TIMESTAMP() WHERE ID IN (' . implode(',', $id) . ')');
					$Cache->write();
					break;

				case 'unpublish':
					$GLOBALS['DB_WE']->query('UPDATE ' . GLOSSARY_TABLE . ' SET Published=0 WHERE ID IN (' . implode(',', $id) . ')');
					$Cache->write();
					break;

				default:
					break;
			}
		}
		unset($Cache);

		// ---> Search Start

		$Rows = we_base_request::_(we_base_request::INT, 'Rows', 10);
		$Offset = we_base_request::_(we_base_request::INT, 'Offset', 0);
		$Order = we_base_request::_(we_base_request::STRING, 'Order', 'Text');
		$Sort = we_base_request::_(we_base_request::STRING, 'Sort', 'ASC');
		$Where = "Language = '" . $Language . "' AND Type = '" . $Type . "'";
		if(($kw = strtolower(we_base_request::_(we_base_request::RAW, 'Keyword')))){
			$Where .= " AND ("
				. "lcase(Text) LIKE '%" . $kw . "%' OR "
				. "lcase(Title) LIKE '%" . $kw . "%' OR "
				. "lcase(Description) LIKE '%" . $kw . "%')";
		}
		if(we_base_request::_(we_base_request::BOOL, 'GreenOnly')){
			$Where .= " AND Published > 0";
		}

		$Search = new we_glossary_search(GLOSSARY_TABLE);
		$Search->setFields(array("*"));
		$Search->setLimit($Offset, $Rows);
		$Search->setOrder($Order, $Sort);
		$Search->setWhere($Where);

		// ---> Search End
		// ---> some javascript code
		$cmdid = we_base_request::_(we_base_request::STRING, 'cmdid');

		$_js .= $weGlossaryFrames->topFrame . '.editor.edheader.location="' . $weGlossaryFrames->frameset . '?pnt=edheader&cmd=glossary_view_type&cmdid=' . $cmdid . '";
						' . $weGlossaryFrames->topFrame . '.editor.edfooter.location="' . $weGlossaryFrames->frameset . '?pnt=edfooter&cmd=glossary_view_type&cmdid=' . $cmdid . '";
		function AllItems(){
			if(document.we_form.selectAll.value == 0) {
				temp = true;
				document.we_form.selectAll.value = 1;
			} else {
				temp = false;
				document.we_form.selectAll.value = 0;
			}
			for (var x = 0; x< document.we_form.elements.length; x++) {
				var y = document.we_form.elements[x];
				if(y.name == \'ID[]\') {
					y.checked = temp;
				}
			}
		}
		function SubmitForm() {
			document.we_form.submit();
		}
		function next() {
			document.we_form.Offset.value = parseInt(document.we_form.Offset.value) + ' . $Rows . ';
			SubmitForm();
		}
		function prev() {
			document.we_form.Offset.value = parseInt(document.we_form.Offset.value) - ' . $Rows . ';
			SubmitForm();
		}
		function jump(val) {
			document.we_form.Offset.value = val;
			SubmitForm();
		}
		';

		$js = we_html_element::jsElement($_js);

		// ---> end of javascript
		// ---> build content

		$content = self::getHTMLPreferences($Search, $Type, $Language) .
			($Search->countItems() ?
				self::getHTMLPrevNext($Search) .
				self::getHTMLSearchResult($weGlossaryFrames, $Search, $Type) .
				self::getHTMLPrevNext($Search, true) :
				'<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(632, 12) . '</td>
		</tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td class="defaultfont">' . g_l('modules_glossary', '[no_entries_found]') . '</td>
		</tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(632, 12) . '</td>
		</tr>
		</table>');


		// ---> end of uilding content

		$parts = array(
			0 => array(
				'headline' => '',
				'html' => $content,
				'space' => 0,
			),
		);

		$out = we_html_element::htmlDiv(array('id' => 'tab1'), we_html_multiIconBox::getHTML('', "100%", $parts, 30, '', -1, '', '', false));

		$content = $js . $out;

		return self::buildBody($weGlossaryFrames, $content);
	}

	function Footer($weGlossaryFrames){

		return self::buildFooter($weGlossaryFrames, "");
	}

	private static function getHTMLSearchResult($weGlossaryFrames, &$Search, $Type){

		$Search->execute();

		$retVal = "";

		$headline = array(
			array('dat' => '',),
			array('dat' => g_l('modules_glossary', '[show]'),),
			array('dat' => g_l('modules_glossary', '[' . $Type . ']'),)
		);

		switch($Type){

			case we_glossary_glossary::TYPE_ABBREVATION:
			case we_glossary_glossary::TYPE_ACRONYM:
				$headline[3] = array('dat' => g_l('modules_glossary', '[announced_word]'),);
				break;

			case we_glossary_glossary::TYPE_FOREIGNWORD:
			case we_glossary_glossary::TYPE_TEXTREPLACE:
				break;

			case we_glossary_glossary::TYPE_LINK:
				$headline[3] = array('dat' => g_l('modules_glossary', '[link_mode]'),);
				$headline[4] = array('dat' => g_l('modules_glossary', '[link_url]'),);
				break;
		}

		$headline[] = array('dat' => g_l('modules_glossary', '[date_published]'),);
		$headline[] = array('dat' => g_l('modules_glossary', '[date_modified]'),);

		$content = array();
		while($Search->next()){
			$show = '<i class="fa fa-lg fa-circle" style="color:#006DB8;"></i>';
			/* if($Search->getField('Published')) {
			  $show = '<i class="fa fa-lg fa-circle" style="color:#E7E7E7;"></i>';
			  } */

			$temp = array(
				array(
					'dat' => '<input type="checkbox" name="ID[]" value="' . $Search->getField('ID') . '" />',
					'height' => 25,
					'align' => 'center',
					'bgcolor' => '#ffffff',
				),
				array(
					'dat' => $show,
					'height' => 25,
					'align' => 'center',
					'bgcolor' => '#ffffff',
				),
				array(
					'dat' => '<a href="javascript://" onclick="' . $weGlossaryFrames->topFrame . '.editor.edbody.location=\'' . $weGlossaryFrames->frameset . '?pnt=edbody&cmd=edit_glossary_' . $Type . '&cmdid=' . $Search->getField('ID') . '&tabnr=\'+' . $weGlossaryFrames->topFrame . '.activ_tab;">' . oldHtmlspecialchars($Search->getField('Text')) . '</a>',
					'height' => 25,
					'align' => 'left',
					'bgcolor' => '#ffffff',
				)
			);

			$values = we_unserialize($Search->getField('Attributes'));
			switch($Type){

				case we_glossary_glossary::TYPE_ABBREVATION:
				case we_glossary_glossary::TYPE_ACRONYM:
					$temp[3] = array(
						'dat' => ($Search->getField('Title') ? oldHtmlspecialchars($Search->getField('Title')) : "-"),
						'height' => 25,
						'align' => 'left',
						'bgcolor' => '#ffffff',
					);
					break;

				case we_glossary_glossary::TYPE_FOREIGNWORD:
				case we_glossary_glossary::TYPE_TEXTREPLACE:
					break;

				case we_glossary_glossary::TYPE_LINK:
					$url = "";
					switch($values['mode']){
						case 'intern':
							$url = $values['InternLinkPath'];
							$mode = g_l('modules_glossary', '[link_intern]');
							break;
						case 'extern':
							$url = $values['ExternUrl'];
							$mode = g_l('modules_glossary', '[link_extern]');
							break;
						case 'object':
							$url = $values['ObjectLinkPath'];
							$mode = g_l('modules_glossary', '[link_object]');
							break;
						case 'category':
							if($values['modeCategory'] === "extern"){
								$url = $values['CategoryUrl'];
							} else {
								$url = $values['CategoryInternLinkPath'];
							}
							$mode = g_l('modules_glossary', '[link_category]');
							break;
					}
					$temp[3] = array(
						'dat' => $mode,
						'height' => 25,
						'align' => 'left',
						'bgcolor' => '#ffffff',
					);
					$temp[4] = array(
						'dat' => $url,
						'height' => 25,
						'align' => 'left',
						'bgcolor' => '#ffffff',
					);
					break;
			}
			$temp[] = array(
				'dat' => $Search->getField('Published') > 0 ? str_replace(" - ", "<br />", date(g_l('date', '[format][default]'), $Search->getField('Published'))) : "-",
				'height' => 25,
				'align' => 'center',
				'bgcolor' => '#ffffff',
			);
			$temp[] = array(
				'dat' => $Search->getField('ModDate') > 0 ? str_replace(" - ", "<br />", date(g_l('date', '[format][default]'), $Search->getField('ModDate'))) : "-",
				'height' => 25,
				'align' => 'center',
				'bgcolor' => '#ffffff',
			);
			$content[] = $temp;
		}

		$retVal .= we_html_tools::htmlDialogBorder3(636, 0, $content, $headline);

		return $retVal;
	}

	private static function getHTMLPreferences($Search, $Type, $Language){

		$button = we_html_button::create_button(we_html_button::SEARCH, "javascript:SubmitForm();");
		$newButton = we_html_button::create_button("new_entry", "javascript:we_cmd('new_glossary_" . $Type . "','" . $Language . "');", true, 100, 22, "", "", !permissionhandler::hasPerm("NEW_GLOSSARY"));

		$_rows = array(10 => 10, 25 => 25, 50 => 50, 100 => 100);

		return we_html_element::htmlHiddens(array(
				"we_transaction" => $GLOBALS['we_transaction'],
				"Order" => $Search->Order,
				"Offset" => $Search->Offset,
				"Sort" => $Search->Sort,
				"selectAll" => 0,
				"do" => ""
			)) . '
		<table width="637" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80"></td>
			<td width="157"></td>
			<td width="280"></td>
			<td width="20"></td>
			<td width="100"></td>
		</tr>
		<tr>
			<td class="defaultgray">' . g_l('modules_glossary', '[search]') . '</td>
			<td colspan="2">' . we_html_tools::htmlTextInput('Keyword', 24, we_base_request::_(we_base_request::RAW, 'Keyword', ''), "", "style=\"width: 430px\"") . '</td>
			<td>' . we_html_tools::getPixel(18, 2) . '</td>
			<td>' . $button . '</td>
		</tr>
		<tr>
			<td colspan="5">' . we_html_tools::getPixel(18, 12) . '</td>
		</tr>
		<tr>
			<td class="defaultgray">' . g_l('modules_glossary', '[view]') . '</td>
			<td>' . we_html_tools::htmlSelect("Rows", $_rows, 1, $Search->Rows, "", array('onchange' => "SubmitForm();")) . '</td>
			<td>' . we_html_forms::checkboxWithHidden(we_base_request::_(we_base_request::BOOL, 'GreenOnly'), "GreenOnly", g_l('modules_glossary', '[show_only_visible_items]'), false, "defaultfont", "jump(0);") . '</td>
			<td>' . we_html_tools::getPixel(18, 2) . '</td>
			<td>' . $newButton . '</td>
		</tr>
		<tr>
			<td colspan="5">' . we_html_tools::getPixel(18, 12) . '</td>
		</tr>
		</table>';
	}

	private static function getHTMLPrevNext($Search, $extended = false){

		$sum = $Search->countItems();
		$min = ($Search->Offset) + 1;
		$max = min($Search->Offset + $Search->Rows, $sum);

		$prev = ($Search->Offset > 0 ?
				we_html_button::create_button(we_html_button::BACK, "javascript:prev();") : //bt_back
				we_html_button::create_button(we_html_button::BACK, "", true, 100, 22, "", "", true));

		$next = ($Search->Offset + $Search->Rows >= $sum ?
				we_html_button::create_button(we_html_button::NEXT, "", true, 100, 22, "", "", true) :
				we_html_button::create_button(we_html_button::NEXT, "javascript:next();")); //bt_next


		$pages = $Search->getPages();

		$select = we_html_tools::htmlSelect("TmpOffset", $pages, 1, $Search->Offset, false, array("onchange" => "jump(this.value);"));

		return '
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(195, 12) . '</td>
			<td>' . we_html_tools::getPixel(437, 12) . '</td>
		</tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . ($extended && (permissionhandler::hasPerm("DELETE_GLOSSARY") || permissionhandler::hasPerm("NEW_GLOSSARY")) ? we_html_button::create_button("selectAll", "javascript: AllItems();") : "") . '</td>
			<td align="right"><table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td></td>
					<td>' . $prev . '</td>
					<td>' . we_html_tools::getPixel(10, 2) . '</td>
					<td class="defaultfont"><b>' . ($Search->Rows == 1 ? $min : $min . '-' . $max) . ' ' . g_l('global', '[from]') . ' ' . $sum . '</b></td>
					<td>' . we_html_tools::getPixel(10, 2) . '</td>
					<td>' . $next . '</td>
					<td>' . we_html_tools::getPixel(10, 2) . '</td>
					<td>' . $select . '</td>
				</tr>
				</table></td>
		</tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(195, 12) . '</td>
			<td>' . we_html_tools::getPixel(437, 12) . '</td>
		</tr>
		' .
			($extended ?
				'<tr>
			<td colspan="3">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">' . (permissionhandler::hasPerm("DELETE_GLOSSARY") ? we_html_button::create_button(we_html_button::TRASH, "javascript: if(confirm('" . g_l('modules_glossary', '[confirm_delete]') . "')) { document.we_form.elements.do.value='delete'; SubmitForm(); }") . '</td>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">&nbsp;' . g_l('modules_glossary', '[delete_selected_items]') : "") . '</td>
				</tr>
				</table>
			</td>
		<tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(195, 12) . '</td>
			<td>' . we_html_tools::getPixel(437, 12) . '</td>
		</tr>
		<tr>
			<td colspan="3">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">' . (permissionhandler::hasPerm("NEW_GLOSSARY") ? we_html_button::create_button("fa:btn_function_publish,fa-lg fa-sun-o", "javascript: if(confirm('" . g_l('modules_glossary', '[confirm_publish]') . "')) { document.we_form.elements.do.value='publish'; SubmitForm(); }") . '</td>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">&nbsp;' . g_l('modules_glossary', '[publish_selected_items]') : "") . '</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>' . we_html_tools::getPixel(5, 1) . '</td>
			<td>' . we_html_tools::getPixel(195, 12) . '</td>
			<td>' . we_html_tools::getPixel(437, 12) . '</td>
		</tr>
		<tr>
			<td colspan="3">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">' . (permissionhandler::hasPerm("NEW_GLOSSARY") ? we_html_button::create_button("fa:btn_function_unpublish,fa-lg fa-moon-o", "javascript: if(confirm('" . g_l('modules_glossary', '[confirm_unpublish]') . "')) { document.we_form.elements.do.value='unpublish'; SubmitForm(); }") . '</td>
					<td>' . we_html_tools::getPixel(5, 1) . '</td>
					<td class="small">&nbsp;' . g_l('modules_glossary', '[unpublish_selected_items]') : "") . '</td>
				</tr>
				</table>
			</td>
		</tr>' :
				'') .
			'</table>';
	}

}
