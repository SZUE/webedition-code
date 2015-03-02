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

function convertDate($date){
	return implode('.', array_reverse(explode('-', $date)));
}

/**
 * Creates the HTML code for the date picker button
 *
 * @param unknown_type $_label
 * @param unknown_type $_name
 * @param unknown_type $_btn
 * @return unknown
 */
function getDateSelector($_label, $_name, $_btn){
	$btnDatePicker = we_html_button::create_button('image:date_picker', 'javascript:', null, null, null, null, null, null, false, $_btn);
	$oSelector = new we_html_table(array('cellpadding' => 0, 'cellspacing' => 0, 'border' => 0, 'id' => $_name . '_cell'), 1, 5);
	$oSelector->setCol(0, 0, array('class' => 'middlefont'), $_label);
	$oSelector->setCol(0, 1, null, we_html_tools::getPixel(5, 1));
	$oSelector->setCol(0, 2, null, we_html_tools::htmlTextInput($_name, 55, '', 10, 'id="' . $_name . '" readonly="1"', "text", 70, 0));
	$oSelector->setCol(0, 3, null, we_html_tools::getPixel(5, 1));
	$oSelector->setCol(0, 4, null, we_html_element::htmlA(array("href" => "#"), $btnDatePicker));
	return $oSelector->getHTML();
}

/**
 * Creates the HTML code with the note list
 *
 * @param unknown_type $_sql
 * @param unknown_type $bDate
 * @return unknown
 */
function getNoteList($_sql, $bDate, $bDisplay){
	global $DB_WE;
	$DB_WE->query($_sql);
	$_notes = '<table width="100%" cellspacing="0" cellpadding="0" border="0">';
	$_rcd = 0;
	$_fields = array(
		'ID',
		'WidgetName',
		'UserID',
		'CreationDate',
		'Title',
		'Text',
		'Priority',
		'Valid',
		'ValidFrom',
		'ValidUntil'
	);
	while($DB_WE->next_record()){
		foreach($_fields as $_fld){
			$dbf = $DB_WE->f($_fld);

			$_fldValue = CheckAndConvertISObackend(str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#039;', '&quot;'), ($_fld === 'ValidUntil' && ($dbf === '3000-01-01' || $dbf === '0000-00-00' || !$dbf) ? '' : $dbf)));
			$_notes .= we_html_element::htmlHidden(
					array(
						'id' => $_rcd . '_' . $_fld,
						'style' => 'display:none;',
						'value' => $_fldValue
			));
		}

		$validity = $DB_WE->f("Valid");
		switch($bDate){
			case 1 :
				$showDate = ($validity === 'always' ? '-' : convertDate($DB_WE->f("ValidFrom")));
				break;
			case 2 :
				$showDate = ($validity === 'always' || $validity === 'date' ? '-' : convertDate($DB_WE->f("ValidUntil")));
				break;
			default :
				$showDate = convertDate($DB_WE->f("CreationDate"));
		}

		$today = date("Ymd");
		$vFrom = str_replace('-', '', $DB_WE->f("ValidFrom"));
		$vTill = str_replace('-', '', $DB_WE->f("ValidUntil"));
		if($bDisplay == 1 && $DB_WE->f("Valid") != 'always'){
			if($DB_WE->f('Valid') === 'date'){
				if($today < $vFrom){
					continue;
				}
			} else {
				if($today < $vFrom || $today > $vTill){
					continue;
				}
			}
		}
		$showTitle = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#039;', '&quot;'), $DB_WE->f("Title"));
		$_notes .= '<tr style="cursor:pointer;" id="' . $_rcd . '_tr" onmouseover="fo=document.forms[0];if(fo.elements.mark.value==\'\'){setColor(this,' . $_rcd . ',\'#EDEDED\');}" onmouseout="fo=document.forms[0];if(fo.elements.mark.value==\'\'){setColor(this,' . $_rcd . ',\'#FFFFFF\');}" onmousedown="selectNote(' . $_rcd . ');">
		<td width="5">' . we_html_tools::getPixel(5, 1) . '</td>
		<td width="15" height="20" valign="middle" nowrap>' . we_html_element::htmlImg(
				array(
					"src" => IMAGE_DIR . "pd/prio_" . $DB_WE->f("Priority") . ".gif",
					"width" => 13,
					"height" => 14
			)) . '</td>
		<td width="5">' . we_html_tools::getPixel(5, 1) . '</td>
		<td width="60" valign="middle" class="middlefont" align="center">' . $showDate . '</td>
		<td width="5">' . we_html_tools::getPixel(5, 1) . '</td>
		<td valign="middle" class="middlefont">' . CheckAndConvertISObackend($showTitle) . '</td>
		<td width="5">' . we_html_tools::getPixel(5, 1) . '</td>
		</tr>';
		$_rcd++;
	}
	$_notes .= '</table>';
	return $_notes;
}

function getCSS(){
	return '
body{
	background-color:transparent;
}
.cl_notes{
	background-color:#FFFFFF;
}
#notices{
	position:relative;
	top:0px;
	display:block;
	height:250px;
	overflow:auto;
}
#props{
	position:absolute;
	bottom:0px;
	display:none;
}
#view{
	position:absolute;
	bottom:0px;
	display:block;
	height:22px;
}
.wetextinput{
	color:black;
	border:#AAAAAA solid 1px;
	height:18px;
	vertical-align:middle;
	' . (we_base_browserDetect::isIE() ? '' : 'line-height:normal;') . ';
	font-size:' . ((we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11)) . 'px;
}
input.wetextinput:focus {
	color:black;
	border:#888888 solid 1px;
	background-color:#DCE6F2;
	height:18px;
	' . (we_base_browserDetect::isIE() ? '' : 'line-height:normal;') . ';
	font-size:' . ((we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11)) . 'px;
}
.wetextarea {
	color:black;
	border:#AAAAAA solid 1px;
	height:80px;
	' . (we_base_browserDetect::isIE() ? '' : 'line-height:normal;') . ';
	font-size:' . ((we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11)) . 'px;
}
textarea.wetextarea:focus {
	color:black;
	border:#888888 solid 1px;
	background-color:#DCE6F2;
	height:80px;
	' . (we_base_browserDetect::isIE() ? '' : 'line-height:normal;') . ';
	font-size:' . ((we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11)) . 'px;
}
select{
	border:#AAAAAA solid 1px;
}';
}

we_html_tools::protect();
/**
 * Table with the notes
 * @var string
 */
$_sInitProps = substr(we_base_request::_(we_base_request::STRINGC, 'we_cmd', '', 0), -5); //binary data
$bSort = $_sInitProps{0};
$bDisplay = $_sInitProps{1};
$bDate = $_sInitProps{2};
$bPrio = $_sInitProps{3};
$bValid = $_sInitProps{4};
$title = base64_decode(we_base_request::_(we_base_request::RAW, 'we_cmd', '', 4));
$type = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 6);

switch(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 2)){
	case 'delete' :
		$DB_WE->query('DELETE FROM ' . NOTEPAD_TABLE . ' WHERE ID=' . we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1));
		break;
	case 'update' :
		list($q_ID, $q_Title, $q_Text, $q_Priority, $q_Valid, $q_ValidFrom, $q_ValidUntil) = explode(';', we_base_request::_(we_base_request::STRING, 'we_cmd', '', 1));
		$entTitle = str_replace(array("'", '"'), array('&#039;', '&quot;'), base64_decode($q_Title));
		$entText = str_replace(array("'", '"'), array('&#039;', '&quot;'), base64_decode($q_Text));
		if($q_Valid === "always" || $q_Valid === "date"){
			$q_ValidUntil = "3000-01-01";
		}
		$DB_WE->query('UPDATE ' . NOTEPAD_TABLE . ' SET ' . we_database_base::arraySetter(array(
				'Title' => $entTitle,
				'Text' => $entText,
				'Priority' => $q_Priority,
				'Valid' => $q_Valid,
				'ValidFrom' => $q_ValidFrom,
				'ValidUntil' => $q_ValidUntil)) . ' WHERE ID = ' . intval($q_ID));
		break;
	case 'insert' :
		list($q_Title, $q_Text, $q_Priority, $q_Valid, $q_ValidFrom, $q_ValidUntil) = explode(';', we_base_request::_(we_base_request::STRING, 'we_cmd', '', 1));
		if($q_Valid === "always"){
			$q_ValidUntil = "3000-01-01";
			$q_ValidFrom = date("Y-m-d");
		} elseif($q_Valid === "date"){
			$q_ValidUntil = "3000-01-01";
		}

		$entTitle = str_replace(array("'", '"'), array('&#039;', '&quot;'), base64_decode($q_Title));
		$entText = str_replace(array("'", '"'), array('&#039;', '&quot;'), base64_decode($q_Text));
		$DB_WE->query('INSERT INTO ' . NOTEPAD_TABLE . ' SET ' . we_database_base::arraySetter(array(
				'WidgetName' => $title,
				'UserID' => intval($_SESSION['user']['ID']),
				'CreationDate' => sql_function('CURRENT_DATE()'),
				'Title' => $entTitle,
				'Text' => $entText,
				'Priority' => $q_Priority,
				'Valid' => $q_Valid,
				'ValidFrom' => $q_ValidFrom,
				'ValidUntil' => $q_ValidUntil
		)));
		break;
}

switch($bSort){
	case 1 :
		$q_sort = 'Priority DESC, Title';
		break;
	case 2 :
		$q_sort = 'ValidFrom, Title';
		break;
	case 3 :
		$q_sort = 'Title';
		break;
	case 4 :
		$q_sort = 'ValidUntil, Title';
		break;
	default :
		$q_sort = 'CreationDate, Title';
}

$_sql = 'SELECT * FROM ' . NOTEPAD_TABLE . " WHERE
		WidgetName = '" . $GLOBALS['DB_WE']->escape($title) . "' AND
		UserID = " . intval($_SESSION['user']['ID']) .
	($bDisplay ?
		" AND (
			Valid = 'always' OR (
				Valid = 'date' AND ValidFrom <= DATE_FORMAT(NOW(), \"%Y-%m-%d\")
			) OR (
				Valid = 'period' AND ValidFrom <= DATE_FORMAT(NOW(), \"%Y-%m-%d\") AND ValidUntil >= DATE_FORMAT(NOW(), \"%Y-%m-%d\")
			)
		)" : ''
	) .
	' ORDER BY ' . $q_sort;

// validity settings
$sctValid = we_html_tools::htmlSelect("sct_valid", array(
		g_l('cockpit', '[always]'), g_l('cockpit', '[from_date]'), g_l('cockpit', '[period]')
		), 1, g_l('cockpit', '[always]'), false, array('style' => "width:100px;", 'onchange' => "toggleTblValidity()"), 'value', 100, 'middlefont');
$oTblValidity = new we_html_table(array(
	"cellpadding" => 0, "cellspacing" => 0, "border" => 0, "id" => "oTblValidity"
	), 1, 3);
$oTblValidity->setCol(0, 0, null, getDateSelector(g_l('cockpit', '[from]'), "f_ValidFrom", "_from"));
$oTblValidity->setCol(0, 1, null, we_html_tools::getPixel(10, 1));
$oTblValidity->setCol(0, 2, null, getDateSelector(g_l('cockpit', '[until]'), "f_ValidUntil", "_until"));
$oTblPeriod = new we_html_table(array(
	"width" => "100%", "cellpadding" => 0, "cellspacing" => 0, "border" => 0
	), 1, 2);
$oTblPeriod->setCol(0, 0, array(
	"class" => "middlefont"
	), $sctValid);
$oTblPeriod->setCol(0, 1, array(
	"align" => "right"
	), $oTblValidity->getHTML());

// Edit note prio settings
$rdoPrio = array(
	we_html_forms::radiobutton(0, 0, "rdo_prio", g_l('cockpit', '[high]'), true, "middlefont", "", false, "", 0, ""),
	we_html_forms::radiobutton(1, 0, "rdo_prio", g_l('cockpit', '[medium]'), true, "middlefont", "", false, "", 0, ""),
	we_html_forms::radiobutton(2, 1, "rdo_prio", g_l('cockpit', '[low]'), true, "middlefont", "", false, "", 0, "")
);
$oTblPrio = new we_html_table(array("cellpadding" => 0, "cellspacing" => 0, "border" => 0), 1, 8);
$oTblPrio->setCol(0, 0, null, $rdoPrio[0]);
$oTblPrio->setCol(0, 1, null, we_html_element::htmlImg(
		array(
			"src" => IMAGE_DIR . "pd/prio_high.gif",
			"width" => 13,
			"height" => 14,
			"style" => "margin-left:5px"
)));
$oTblPrio->setCol(0, 2, null, we_html_tools::getPixel(15, 1));
$oTblPrio->setCol(0, 3, null, $rdoPrio[1]);
$oTblPrio->setCol(
	0, 4, null, we_html_element::htmlImg(
		array(
			"src" => IMAGE_DIR . "pd/prio_medium.gif",
			"width" => 13,
			"height" => 14,
			"style" => "margin-left:5px"
)));
$oTblPrio->setCol(0, 5, null, we_html_tools::getPixel(15, 1));
$oTblPrio->setCol(0, 6, null, $rdoPrio[2]);
$oTblPrio->setCol(
	0, 7, null, we_html_element::htmlImg(
		array(
			"src" => IMAGE_DIR . "pd/prio_low.gif",
			"width" => 13,
			"height" => 14,
			"style" => "margin-left:5px"
)));

// Edit note buttons
$delete_button = we_html_button::create_button("delete", "javascript:deleteNote();", false, 0, 0, "", "", true, false);
$cancel_button = we_html_button::create_button("cancel", "javascript:cancelNote();", false, 0, 0);
$save_button = we_html_button::create_button("save", "javascript:saveNote();");
$buttons = we_html_button::position_yes_no_cancel($delete_button, $cancel_button, $save_button);

// Edit note dialog
$oTblProps = new we_html_table(array(
	"width" => "100%", "cellpadding" => 0, "cellspacing" => 0, "border" => 0
	), 9, 2);
$oTblProps->setCol(0, 0, array(
	"class" => "middlefont"
	), g_l('cockpit', '[valid]') . '&nbsp;');
$oTblProps->setCol(0, 1, array(
	"colspan" => 2, "align" => "right"
	), $oTblPeriod->getHTML());
$oTblProps->setCol(1, 0, null, we_html_tools::getPixel(1, 8));
$oTblProps->setCol(2, 0, array(
	"class" => "middlefont"
	), g_l('cockpit', '[prio]'));
$oTblProps->setCol(2, 1, null, $oTblPrio->getHTML());
$oTblProps->setCol(3, 0, null, we_html_tools::getPixel(1, 8));
$oTblProps->setCol(4, 0, array(
	"class" => "middlefont"
	), g_l('cockpit', '[title]'));
$oTblProps->setCol(
	4, 1, null, we_html_tools::htmlTextInput(
		"props_title", 255, "", 255, "", "text", "100%", 0));
$oTblProps->setCol(5, 0, null, we_html_tools::getPixel(1, 8));
$oTblProps->setCol(6, 0, array(
	"class" => "middlefont", "valign" => "top"
	), g_l('cockpit', '[note]'));
$oTblProps->setCol(
	6, 1, null, we_html_element::htmlTextArea(
		array(
		'name' => 'props_text',
		'id' => 'previewCode',
		'style' => 'width:100%;height:60px;',
		'class' => 'wetextinput',
		), ""));
$oTblProps->setCol(7, 0, null, we_html_tools::getPixel(1, 8));
$oTblProps->setCol(8, 0, array(
	"colspan" => 3
	), $buttons);

// Button: add note
$oTblBtnProps = new we_html_table(array(
	"width" => "100%", "cellpadding" => 0, "cellspacing" => 0, "border" => 0
	), 1, 1);
$oTblBtnProps->setCol(0, 0, array(
	"align" => "right"
	), we_html_button::create_button("image:btn_add_note", "javascript:displayNote();", false, 0, 0));

// Table with the note list
$oPad = new we_html_table(
	array(
	"style" => "table-layout:fixed;width:100%;padding-top:6px;padding-bottom:6px;background-color:white;",
	"cellpadding" => 0,
	"cellspacing" => 0,
	"border" => 0,
	), 1, 1);

$oPad->setCol(0, 0, array("colspan" => 3, "class" => "cl_notes"), we_html_element::htmlDiv(array(
		"id" => "notices"
		), getNoteList($_sql, $bDate, $bDisplay)));

$_notepad = $oPad->getHTML() .
	we_html_element::htmlDiv(array("id" => "props"), $oTblProps->getHTML()) .
	we_html_element::htmlDiv(array("id" => "view"), $oTblBtnProps->getHTML());

echo we_html_element::htmlDocType() . we_html_element::htmlHtml(
	we_html_element::htmlHead(
		we_html_tools::getHtmlInnerHead(g_l('cockpit', '[notepad]')) . STYLESHEET . we_html_element::cssElement(
			getCSS()) . we_html_element::linkElement(
			array(
				"rel" => "stylesheet",
				"type" => "text/css",
				"href" => LIB_DIR . "additional/jscalendar/skins/aqua/theme.css",
				"title" => "Aqua"
		)) . we_html_element::jsScript(LIB_DIR . "additional/jscalendar/calendar.js") .
		we_html_element::jsScript(WE_INCLUDES_DIR . 'we_language/' . $GLOBALS["WE_LANGUAGE"] . "/calendar.js") .
		we_html_element::jsScript(LIB_DIR . "additional/jscalendar/calendar-setup.js") .
		we_html_element::jsElement(
			(($type === "pad/pad") ? "
var _sObjId='" . we_base_request::_(we_base_request::STRING, 'we_cmd', 0, 5) . "';
var _sCls_=parent.gel(_sObjId+'_cls').value;
var _sType='pad';
var _sTb='" . g_l('cockpit', '[notes]') . " - " . $title . "';
" : "
var _sObjId='m_" . we_base_request::_(we_base_request::INT, 'we_cmd', 0, 5) . "';
var _sTb='" . $title . "';
var _sInitProps='" . $_sInitProps . "';") . "
var _ttlB64Esc='';
if(typeof parent.base64_encode=='function')_ttlB64Esc=escape(parent.base64_encode(_sTb));

// saves a note, using the function rpc() in home.inc.php (750)
function saveNote(){
	var fo=document.forms[0];
	var _id=fo.elements.mark.value;
	var q_init;
	if(_id!='') q_init=getInitialQueryById(_id);
	else q_init={'Validity':'always','ValidFrom':'','ValidUntil':'','Priority':'low','Title':'','Text':''};
	var q_curr=getCurrentQuery();
	var hot=false;
	var idx=['Title','Text','Priority','Validity','ValidFrom','ValidUntil'];
	var csv='';
	var idx_len=idx.length;
	for(var i=0;i<idx_len;i++){
		if(q_init[idx[i]]!=q_curr[idx[i]])hot=true;
		csv+=(idx[i]=='Title'||idx[i]=='Text')?parent.base64_encode(q_curr[idx[i]]):q_curr[idx[i]];
		if(i<idx_len-1)csv+=';';
	}

	if(_id!=''){
		if(hot){
			// update note

			if(q_curr['Validity'] == 'period') {
				weValidFrom = q_curr['ValidFrom'].replace(/-/g, '');
				weValidUntil = q_curr['ValidUntil'].replace(/-/g, '');
				if(weValidFrom>weValidUntil) {
					" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[until_befor_from]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
					return false;
				}
			}
			if(q_curr['Title']=='') {
				" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[title_empty]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
				return false;
			}
			var q_ID=gel(_id+'_ID').value;
			parent.rpc(_ttlB64Esc.concat(','+_sInitProps),(q_ID+';'+encodeURI(csv)),'update','',_ttlB64Esc,_sObjId,'pad/pad',escape(q_curr['Title']),escape(q_curr['Text']));
		}else{
			" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[note_not_modified]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
		}
	}else{
		if(hot){
			// insert note
			if(q_curr['Validity'] == 'period') {
				weValidFrom = q_curr['ValidFrom'].replace(/-/g, '');
				weValidUntil = q_curr['ValidUntil'].replace(/-/g, '');
				if(weValidFrom>weValidUntil) {
					" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[until_befor_from]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
					return false;
				} else if(!weValidFrom || !weValidUntil) {
					" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[date_empty]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
					return false;
				}
			} else if(q_curr['Validity'] == 'date' && !q_curr['ValidFrom']){
					" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[date_empty]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
					return false;
			}
			if(q_curr['Title']=='') {
				" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[title_empty]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
				return false;
			}
			parent.rpc(_ttlB64Esc.concat(','+_sInitProps),escape(csv),'insert','',_ttlB64Esc,_sObjId,'pad/pad',escape(q_curr['Title']),escape(q_curr['Text']));
		}else{
			" . we_message_reporting::getShowMessageCall(
	g_l('cockpit', '[title_empty]'), we_message_reporting::WE_MESSAGE_NOTICE) . "
		}
	}
}") . we_html_element::jsScript(JS_DIR . 'widgets/pad.js')) . we_html_element::htmlBody(
		array(
		"marginwidth" => 0,
		"marginheight" => 0,
		"leftmargin" => 0,
		"topmargin" => 0,
		"onload" => (($type === "pad/pad") ? "if(parent!=self)init();" : "") . 'calendarSetup();toggleTblValidity();'
		), we_html_element::htmlForm(array("style" => "display:inline;"), we_html_element::htmlDiv(
				array("id" => "pad"), $_notepad .
				we_html_element::htmlHidden(array("name" => "mark", "value" => ""))
))));
