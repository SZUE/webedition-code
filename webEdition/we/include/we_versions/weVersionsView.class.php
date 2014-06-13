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
class weVersionsView{

	public $db;
	public $version;
	public $searchclass;

	/**
	 *  Constructor for class 'weVersionsView'
	 */
	function __construct(){

		$this->db = new DB_WE();
		$this->version = new weVersions();
		$this->searchclass = new weVersionsSearch();
		$this->searchclass->initData();
	}

	/**
	 * @abstract create javascript-Code for versions-tab
	 * @return string javascript-code
	 */
	function getJS(){

		//add height of each input row to calculate the scrollContent-height
		$h = 0;
		$addinputRows = '';
		if($this->searchclass->mode){
			$h += 37;
			$addinputRows = '
							for(i=0;i<newID;i++) {
								scrollheight = scrollheight + 26;
							}';
		}

		return we_html_element::jsElement('
function init() {
	sizeScrollContent();
}

function printScreen() {

	var scrollContent = document.getElementById("scrollContent");
	var hScrollContent = scrollContent.innerHeight ? scrollContent.innerHeight : scrollContent.offsetHeight;

	var contentTable = document.getElementById("contentTable");
	var hContentTable = contentTable.innerHeight ? contentTable.innerHeight : contentTable.offsetHeight;

	//hContentTable = hContentTable-500;

	scrollContent.style.height = hContentTable+"px";
	window.print();

	setTimeout(function(){setCrollContent(hScrollContent);},2000);
}

function setCrollContent(hScrollContent) {
	var scrollContent = document.getElementById("scrollContent");
	scrollContent.style.height = hScrollContent+"px";
}

function sizeScrollContent() {

	var elem = document.getElementById("filterTable");
	if(elem) {
		newID = elem.rows.length-1;

			scrollheight = ' . $h . ';

			' . $addinputRows . '

			var h = window.innerHeight ? window.innerHeight : document.body.offsetHeight;
			var scrollContent = document.getElementById("scrollContent");

			var height = 240;
			if((h - height)>0) {
				scrollContent.style.height=(h - height)+"px";
			}
			if((scrollContent.offsetHeight - scrollheight)>0){
				scrollContent.style.height = (scrollContent.offsetHeight - scrollheight) +"px";
			}
	}
}

var ajaxURL = "' . WEBEDITION_DIR . 'rpc/rpc.php";

var ajaxCallbackResultList = {
	success: function(o) {
	if(typeof(o.responseText) != "undefined" && o.responseText != "") {
		document.getElementById("scrollContent").innerHTML = o.responseText;
		makeAjaxRequestParametersTop();
		makeAjaxRequestParametersBottom();
	}
},
	failure: function(o) {
	}
}

var ajaxCallbackParametersTop = {
	success: function(o) {
	if(typeof(o.responseText) != "undefined" && o.responseText != "") {
		document.getElementById("parametersTop").innerHTML = o.responseText;
	}
},
	failure: function(o) {
	}
}
var ajaxCallbackParametersBottom = {
	success: function(o) {
	if(typeof(o.responseText) != "undefined" && o.responseText != "") {
		document.getElementById("parametersBottom").innerHTML = o.responseText;
	}
},
	failure: function(o) {
	}
}

function search(newSearch) {

	if(newSearch) {
		document.we_form.searchstart.value=0;
	}
	makeAjaxRequestDoclist();

}

function makeAjaxRequestDoclist() {

	var args = "";
	var newString = "";
	for(var i = 0; i < document.we_form.elements.length; i++) {
		newString = document.we_form.elements[i].name;
		args += "&we_cmd["+escape(newString)+"]="+escape(document.we_form.elements[i].value);
	}
	var scroll = document.getElementById("scrollContent");
	scroll.innerHTML = "<table border=\'0\' width=\'100%\' height=\'100%\'><tr><td align=\'center\'><img src=' . IMAGE_DIR . 'logo-busy.gif /></td></tr></table>";
	YAHOO.util.Connect.asyncRequest("POST", ajaxURL, ajaxCallbackResultList, "protocol=json&cns=versionlist&cmd=GetSearchResult&classname=' . $GLOBALS['we_doc']->ClassName . '&id=' . $GLOBALS['we_doc']->ID . '&table=' . $GLOBALS['we_doc']->Table . '&we_transaction=' . $GLOBALS['we_transaction'] . '"+args+"");

}

function makeAjaxRequestParametersTop() {
	var args = "";
	var newString = "";
	for(var i = 0; i < document.we_form.elements.length; i++) {
		newString = document.we_form.elements[i].name;
		args += "&we_cmd["+escape(newString)+"]="+escape(document.we_form.elements[i].value);
	}
	YAHOO.util.Connect.asyncRequest("POST", ajaxURL, ajaxCallbackParametersTop, "protocol=json&position=top&cns=versionlist&cmd=GetSearchParameters&path=' . $GLOBALS['we_doc']->Path . '&text=' . $GLOBALS['we_doc']->Text . '&classname=' . $GLOBALS['we_doc']->ClassName . '&id=' . $GLOBALS['we_doc']->ID . '&we_transaction=' . $GLOBALS['we_transaction'] . '"+args+"");

}

function makeAjaxRequestParametersBottom() {
	var args = "";
	var newString = "";
	for(var i = 0; i < document.we_form.elements.length; i++) {
		newString = document.we_form.elements[i].name;
		args += "&we_cmd["+escape(newString)+"]="+escape(document.we_form.elements[i].value);
	}
	YAHOO.util.Connect.asyncRequest("POST", ajaxURL, ajaxCallbackParametersBottom, "protocol=json&position=bottom&cns=versionlist&cmd=GetSearchParameters&classname=' . $GLOBALS['we_doc']->ClassName . '&id=' . $GLOBALS['we_doc']->ID . '&we_transaction=' . $GLOBALS['we_transaction'] . '"+args+"");

}

var ajaxCallbackDeleteVersion = {
	success: function(o) {
	},
	failure: function(o) {
	}
}

function deleteVersionAjax() {

	var args = "";
	var check = "";
	var newString = "";
	var checkboxes = document.getElementsByName("deleteVersion");
	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked) {
				if(check!="") check += ",";
				check += checkboxes[i].value;
					newString = checkboxes[i].name;
		}
	}
	args += "&we_cmd["+escape(newString)+"]="+escape(check);
	var scroll = document.getElementById("scrollContent");
	scroll.innerHTML = "<table border=\'0\' width=\'100%\' height=\'100%\'><tr><td align=\'center\'><img src=' . IMAGE_DIR . 'logo-busy.gif /></td></tr></table>";

	YAHOO.util.Connect.asyncRequest("POST", ajaxURL, ajaxCallbackDeleteVersion, "protocol=json&cns=versionlist&cmd=DeleteVersion&"+args+"");

}


function deleteVers() {
	var checkAll = document.getElementsByName("deleteAllVersions");
	var checkboxes = document.getElementsByName("deleteVersion");
	var check = false;

	for(var i = 0; i < checkboxes.length; i++) {
		if(checkboxes[i].checked) {
			check = true;
			break;
		}
	}

	if(checkboxes.length==0) {
		check = false;
	}

	if(check==false) {
		' . we_message_reporting::getShowMessageCall(
					g_l('versions', '[notChecked]'), we_message_reporting::WE_MESSAGE_NOTICE) . '
	}else {
		Check = confirm("' . g_l('versions', '[deleteVersions]') . '");
		if (Check == true) {
			var checkAll = document.getElementsByName("deleteAllVersions");
			var label = document.getElementById("label_deleteAllVersions");
			if(checkAll[0].checked) {
				checkAll[0].checked = false;
				label.innerHTML = "' . g_l('versions', '[mark]') . '";
				if(document.we_form.searchstart.value!=0) {
					document.we_form.searchstart.value=document.we_form.searchstart.value - ' . $this->searchclass->anzahl . ';
				}
			}else {
				allChecked = true;
				var checkboxes = document.getElementsByName("deleteVersion");
				for(var i = 0; i < checkboxes.length; i++) {
					if(checkboxes[i].checked == false) {
						allChecked = false;
					}
				}
				if(allChecked) {
					if(document.we_form.searchstart.value!=0) {
						document.we_form.searchstart.value=document.we_form.searchstart.value - ' . $this->searchclass->anzahl . ';
					}
				}
			}

			deleteVersionAjax();
			setTimeout(\'search(false);\', 800);
		}
	}
}

function checkAll() {

	var checkAll = document.getElementsByName("deleteAllVersions");
	var checkboxes = document.getElementsByName("deleteVersion");
	var check = false;
	var label = document.getElementById("label_deleteAllVersions");
	label.innerHTML = "' . g_l('versions', '[mark]') . '";
	if(checkAll[0].checked) {
		check = true;
		label.innerHTML = "' . g_l('versions', '[notMark]') . '";
	}
	for(var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = check;
	}

}

		var ajaxCallbackResetVersion = {
	success: function(o) {
		if(typeof(o.responseText) != "undefined") {
			//top.we_cmd("save_document","' . $GLOBALS['we_transaction'] . '","0","1","0", "","");

			setTimeout(\'search(false);\', 500);
			// reload current document => reload all open Editors on demand

			var _usedEditors =  top.weEditorFrameController.getEditorsInUse();
			for (frameId in _usedEditors) {

				if ( _usedEditors[frameId].getEditorIsActive() ) { // reload active editor
					_usedEditors[frameId].setEditorReloadAllNeeded(true);
					_usedEditors[frameId].setEditorIsActive(true);

				} else {
					_usedEditors[frameId].setEditorReloadAllNeeded(true);
				}
			}
			_multiEditorreload = true;

			//reload tree
			top.we_cmd("load", "' . $GLOBALS['we_doc']->Table . '" ,0);

		}
	},
	failure: function(o) {
	}
}

function resetVersionAjax(id, documentID, version, table) {
	YAHOO.util.Connect.asyncRequest("POST", ajaxURL, ajaxCallbackResetVersion, "protocol=json&cns=versionlist&cmd=ResetVersion&id="+id+"&documentID="+documentID+"&version="+version+"&documentTable="+table+"&we_transaction=' . $GLOBALS['we_transaction'] . '");
}

function resetVersion(id, documentID, version, table) {
	Check = confirm("' . g_l('versions', '[resetVersions]') . '");
	if (Check == true) {
		if(document.getElementById("publishVersion_"+id)!=null) {
			if(document.getElementById("publishVersion_"+id).checked) {
				id += "___1";
			}else {
				id += "___0";
			}
		}
		resetVersionAjax(id, documentID, version, table);
	}

}

function previewVersion(ID) {
	top.we_cmd("versions_preview", ID, 0);
	//new jsWindow("' . WEBEDITION_DIR . 'we/include/we_versions/weVersionsPreview.php?ID="+ID+"", "version_preview",-1,-1,1000,750,true,true,true,true);

}

function switchSearch(mode) {
	document.we_form.mode.value=mode;
	var defSearch = document.getElementById("defSearch");
	var advSearch = document.getElementById("advSearch");
	var advSearch2 = document.getElementById("advSearch2");
	var advSearch3 = document.getElementById("advSearch3");
	var scrollContent = document.getElementById("scrollContent");

	scrollheight = 37;

	var elem = document.getElementById("filterTable");
	newID = elem.rows.length-1;

	for(i=0;i<newID;i++) {
		scrollheight = scrollheight + ' . (we_base_browserDetect::isIE() ? '22' : '26') . ';
	}

	if (mode==1) {
		scrollContent.style.height = (scrollContent.offsetHeight - scrollheight) +"px";
		defSearch.style.display = "none";
		advSearch.style.display = "block";
		advSearch2.style.display = "block";
		advSearch3.style.display = "block";
	}else {
		scrollContent.style.height = (scrollContent.offsetHeight + scrollheight) +"px";
		defSearch.style.display = "block";
		advSearch.style.display = "none";
		advSearch2.style.display = "none";
		advSearch3.style.display = "none";
	}

}

var msBack=0;
var diffBack = 0;
var msNext=0;
var diffNext = 0;

function next(anzahl){
	var zeit = new Date();
	if(msBack!=0) {
		diffBack = zeit.getTime() - msBack;
	}
	msBack = zeit.getTime();
	if(diffBack>1000 || diffBack==0) {
		document.we_form.elements[\'searchstart\'].value = parseInt(document.we_form.elements[\'searchstart\'].value) + anzahl;

		search(false);
	}
}

function back(anzahl){
	var zeit = new Date();
	if(msNext!=0) {
		diffNext = zeit.getTime() - msNext;
	}
	msNext = zeit.getTime();
	if(diffNext>1000 || diffNext==0) {
		document.we_form.elements[\'searchstart\'].value = parseInt(document.we_form.elements[\'searchstart\'].value) - anzahl;

		search(false);
	}

}

function setOrder(order){
	columns = new Array("version", "modifierID", "timestamp");
	for(var i=0;i<columns.length;i++) {
		if(order!=columns[i]) {
			deleteArrow = document.getElementById(""+columns[i]+"");
			deleteArrow.innerHTML = "";
		}
	}
	arrow = document.getElementById(""+order+"");
	orderVal = document.we_form.elements["order"].value;

	if(order+" DESC"==orderVal){
		document.we_form.elements["order"].value=order;
		arrow.innerHTML = "<img border=\"0\" width=\"11\" height=\"8\" src=\"' . IMAGE_DIR . 'arrow_sort_asc.gif\" />";
	}else{
		document.we_form.elements["order"].value=order+" DESC";
		arrow.innerHTML = "<img border=\"0\" width=\"11\" height=\"8\" src=\"' . IMAGE_DIR . 'arrow_sort_desc.gif\" />";
	}
	search(false);

}


var rows = ' . (isset(
					$_REQUEST["searchFields"]) ? count($_REQUEST["searchFields"]) - 1 : 0) . ';

function newinput() {
	var searchFields = "' . str_replace(
					"\n", '\n', addslashes(
						we_html_tools::htmlSelect(
							'searchFields[__we_new_id__]', $this->searchclass->getFields(), 1, "", false, array('class' => "defaultfont", 'id' => "searchFields[__we_new_id__]", 'onchange' => 'changeit(this.value, __we_new_id__);')))) . '";
	var locationFields = "' . str_replace(
					"\n", '\n', addslashes(
						we_html_tools::htmlSelect(
							'location[__we_new_id__]', we_search_search::getLocation(), 1, "", false, array('class' => "defaultfont", 'disabled' => 'disabled', 'id' => "location[__we_new_id__]")))) . '";
	var search = "' . str_replace(
					"\n", '\n', addslashes(
						we_html_tools::htmlSelect(
							'search[__we_new_id__]', $this->searchclass->getModFields(), 1, "", false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => "search[__we_new_id__]")))) . '";

	var elem = document.getElementById("filterTable");
	newID = elem.rows.length-1;
	rows++;

	var scrollContent = document.getElementById("scrollContent");
	scrollContent.style.height = scrollContent.offsetHeight - 26 +"px";


	if(elem){
		var newRow = document.createElement("TR");
			newRow.setAttribute("id", "filterRow_" + rows);

			var cell = document.createElement("TD");
			cell.innerHTML=searchFields.replace(/__we_new_id__/g,rows);
			newRow.appendChild(cell);

			 cell = document.createElement("TD");
				 cell.setAttribute("id", "td_location["+rows+"]");
				 cell.innerHTML=locationFields.replace(/__we_new_id__/g,rows);
				 newRow.appendChild(cell);

		cell = document.createElement("TD");
		cell.setAttribute("id", "td_search["+rows+"]");
			cell.innerHTML=search.replace(/__we_new_id__/g,rows);
			newRow.appendChild(cell);

			cell = document.createElement("TD");
			cell.setAttribute("id", "td_delButton["+rows+"]");
			cell.innerHTML=\'' . we_html_button::create_button(
					"image:btn_function_trash", "javascript:delRow('+rows+')") . '\';
			newRow.appendChild(cell);

		elem.appendChild(newRow);
	}
}

function changeit(value, rowNr){
			var searchFields = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'searchFields[__we_new_id__]', $this->searchclass->getFields(), 1, "", false, array('class' => "defaultfont", 'id' => "searchFields[__we_new_id__]", 'onchange' => "changeit(this.value, __we_new_id__);")))) . '";
			var locationFields = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'location[__we_new_id__]', we_search_search::getLocation(), 1, "", false, array('class' => "defaultfont", 'id' => "location[__we_new_id__]")))) . '";
			var search = "' . addslashes(
					we_html_tools::htmlTextInput(
						'search[__we_new_id__]', 24, "", "", " class=\"wetextinput\" id=\"search[__we_new_id__]\" ", "text", 190)) . '";

			var row = document.getElementById("filterRow_"+rowNr);
			var locationTD = document.getElementById("td_location["+rowNr+"]");
			var searchTD = document.getElementById("td_search["+rowNr+"]");
			var delButtonTD = document.getElementById("td_delButton["+rowNr+"]");
			var location = document.getElementById("location["+rowNr+"]");

			if(value=="allModsIn") {
		if (locationTD!=null) {
			location.disabled = true;
		}
			row.removeChild(searchTD);
			if (delButtonTD!=null) {
					row.removeChild(delButtonTD);
			}

		search = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'search[__we_new_id__]', $this->searchclass->getModFields(), 1, "", false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => "search[__we_new_id__]")))) . '";

		var cell = document.createElement("TD");
		cell.setAttribute("id", "td_search["+rowNr+"]");
		cell.innerHTML=search.replace(/__we_new_id__/g,rowNr);
		row.appendChild(cell);

		cell = document.createElement("TD");
		cell.setAttribute("id", "td_delButton["+rowNr+"]");
		cell.innerHTML=\'' . we_html_button::create_button('image:btn_function_trash', "javascript:delRow('+rowNr+')") . '\';
		row.appendChild(cell);
		}else if(value=="timestamp") {
		row.removeChild(locationTD);

		locationFields = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'location[__we_new_id__]', we_search_search::getLocation("date"), 1, "", false, array('class' => "defaultfont", 'id' => "location[__we_new_id__]")))) . '";

		var cell = document.createElement("TD");
		cell.setAttribute("id", "td_location["+rowNr+"]");
		cell.innerHTML=locationFields.replace(/__we_new_id__/g,rowNr);
		row.appendChild(cell);

		row.removeChild(searchTD);

		var innerhtml= "<table id=\"search["+rowNr+"]_cell\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td></td><td></td><td>\n"
		+ "<input class=\"wetextinput\" name=\"search["+rowNr+"]\" size=\"55\" value=\"\" maxlength=\"10\" id=\"search["+rowNr+"]\" readonly=\"1\" style=\"width: 100px;\" type=\"text\" />\n"
		+ "</td><td>&nbsp;</td><td><a href=\"#\">\n"
		+ "<table id=\"date_picker_from"+rowNr+"\" class=\"weBtn\" onmouseout=\"weButton.out(this);\" onmousedown=\"weButton.down(this);\" onmouseup=\"if(weButton.up(this)){;}\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
		+ "<tbody><tr><td class=\"weBtnLeft\"></td><td class=\"weBtnMiddle\" >"
		+ "<img src=\"' . BUTTONS_DIR . 'icons/date_picker.gif\" class=\"weBtnImage\" alt=\"\"/>"
	+ "</td><td class=\"weBtnRight\"></td></tr></tbody></table></a></td></tr></tbody></table>";


	cell = document.createElement("TD");
	cell.setAttribute("id", "td_search["+rowNr+"]");
	cell.innerHTML = innerhtml;
	row.appendChild(cell);

	Calendar.setup({inputField:"search["+rowNr+"]", ifFormat:"%d.%m.%Y", button:"date_picker_from"+rowNr+"", align:"Tl", singleClick:true});

	if (delButtonTD!=null) {
	row.removeChild(delButtonTD);
	}

	cell = document.createElement("TD");
	cell.setAttribute("id", "td_delButton["+rowNr+"]");
	cell.innerHTML = \'' . we_html_button::create_button('image:btn_function_trash', "javascript:delRow('+rowNr+')") . '\';
		row.appendChild(cell);
	}else if(value=="modifierID") {
		if (locationTD!=null) {
			location.disabled = true;
		}
		row.removeChild(searchTD);
		if (delButtonTD!=null) {
			row.removeChild(delButtonTD);
		}

		search = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'search[__we_new_id__]', $this->searchclass->getUsers(), 1, "", false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => "search[__we_new_id__]")))) . '";

		var cell = document.createElement("TD");
		cell.setAttribute("id", "td_search["+rowNr+"]");
		cell.innerHTML=search.replace(/__we_new_id__/g,rowNr);
		row.appendChild(cell);

		cell = document.createElement("TD");
		cell.setAttribute("id", "td_delButton["+rowNr+"]");
		cell.innerHTML=\'' . we_html_button::create_button(
					"image:btn_function_trash", "javascript:delRow('+rowNr+')") . '\';
		row.appendChild(cell);
	}else if(value=="status") {
		if (locationTD!=null) {
			location.disabled = true;
		}
		row.removeChild(searchTD);
		if (delButtonTD!=null) {
			row.removeChild(delButtonTD);
		}

		search = "' . str_replace(
					"\n", "\\n", addslashes(
						we_html_tools::htmlSelect(
							'search[__we_new_id__]', $this->searchclass->getStats(), 1, "", false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => "search[__we_new_id__]")))) . '";

		var cell = document.createElement("TD");
		cell.setAttribute("id", "td_search["+rowNr+"]");
		cell.innerHTML=search.replace(/__we_new_id__/g,rowNr);
		row.appendChild(cell);

		cell = document.createElement("TD");
		cell.setAttribute("id", "td_delButton["+rowNr+"]");
		cell.innerHTML=\'' . we_html_button::create_button(
					"image:btn_function_trash", "javascript:delRow('+rowNr+')") . '\';
		row.appendChild(cell);
	}
		}

function calendarSetup(x){
	for(i=0;i<x;i++) {
		if(document.getElementById("date_picker_from"+i+"") != null) {
			Calendar.setup({inputField:"search["+i+"]",ifFormat:"%d.%m.%Y",button:"date_picker_from"+i+"",align:"Tl",singleClick:true});
		}
	}
}

function delRow(id) {
	var scrollContent = document.getElementById("scrollContent");
	scrollContent.style.height = scrollContent.offsetHeight + 26 +"px";

	var elem = document.getElementById("filterTable");
	if(elem){
		trows = elem.rows;
		rowID = "filterRow_" + id;

				for (i=0;i<trows.length;i++) {
					if(rowID == trows[i].id) {
						elem.deleteRow(i);
					}
				}
	}
}');
	}

	/**
	 * @abstract create html-Code for filter-selects
	 * @return string html-Code
	 */
	function getBodyTop(){

		$out = '<table cellpadding="0" cellspacing="0" id="defSearch" border="0" width="550" style="margin-left:20px;display:' . ($this->searchclass->mode ? 'none' : 'block') . ';">
<tr>
	<td class="weDocListSearchHeadline">' . g_l('versions', '[weSearch]') . '</td>
	<td>' . we_html_tools::getPixel(10, 2) . '</td>
	<td>' . we_html_tools::getPixel(40, 2) . we_html_button::create_button("image:btn_direction_right", "javascript:switchSearch(1)", false) . '</td>
	<td width="100%">' . we_html_tools::getPixel(10, 2) . '</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" id="advSearch" width="550" style="margin-left:20px;display:' . ($this->searchclass->mode ? 'block' : 'none') . ';">
<tr>
	<td class="weDocListSearchHeadline">' . g_l('versions', '[weSearch]') . '</td>
	<td>' . we_html_tools::getPixel(10, 2) . '</td>
	<td>' . we_html_tools::getPixel(40, 2) . we_html_button::create_button("image:btn_direction_down", "javascript:switchSearch(0)", false) . '</td>
	<td width="100%">' . we_html_tools::getPixel(10, 2) . '</td>
</tr>
</table>
<table cellpadding="2" cellspacing="0"  id="advSearch2" border="0" style="margin-left:20px;display:' . ($this->searchclass->mode ? 'block' : 'none') . ';">
<tbody id="filterTable">
<tr>
	<td>' . we_class::hiddenTrans() . '</td>
</tr>';

		$r = $r2 = $r3 = array();

		if(isset($this->searchclass->search) && is_array($this->searchclass->search)){
			foreach($this->searchclass->search as $k => $v){
				$r[] = $this->searchclass->search[$k];
			}
		}
		if(isset($this->searchclass->searchFields) && is_array($this->searchclass->search)){
			foreach($this->searchclass->searchFields as $k => $v){
				$r2[] = $this->searchclass->searchFields[$k];
			}
		}
		if(isset($_REQUEST['location']) && is_array($_REQUEST['location'])){
			foreach($_REQUEST['searchFields'] as $k => $v){
				$r3[] = we_base_request::_(we_base_request::RAW, 'location', "disabled", $k);
			}
		}

		$this->searchclass->search = $r;
		$this->searchclass->searchFields = $r2;
		$this->searchclass->location = $r3;

		for($i = 0; $i < $this->searchclass->height; $i++){

			$button = we_html_button::create_button("image:btn_function_trash", "javascript:delRow(" . $i . ");", true, "", "", "", "", false);

			$search = we_html_tools::htmlSelect(
					"search[" . $i . "]", $this->searchclass->getModFields(), 1, (isset($this->searchclass->search) && is_array($this->searchclass->search) && isset(
						$this->searchclass->search[$i]) ? $this->searchclass->search[$i] : ""), false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => 'search[' . $i . ']'));

			$locationDisabled = "disabled";
			$handle = "";

			if(isset($this->searchclass->searchFields[$i])){

				if($this->searchclass->searchFields[$i] == "allModsIn"){
					$search = we_html_tools::htmlSelect(
							"search[" . $i . "]", $this->searchclass->getModFields(), 1, (isset($this->searchclass->search) && is_array($this->searchclass->search) && isset(
								$this->searchclass->search[$i]) ? $this->searchclass->search[$i] : ""), false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => 'search[' . $i . ']'));
				}
				if($this->searchclass->searchFields[$i] == "modifierID"){
					$search = we_html_tools::htmlSelect(
							"search[" . $i . "]", $this->searchclass->getUsers(), 1, (isset($this->searchclass->search) && is_array($this->searchclass->search) && isset(
								$this->searchclass->search[$i]) ? $this->searchclass->search[$i] : ""), false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => 'search[' . $i . ']'));
				}
				if($this->searchclass->searchFields[$i] == "status"){
					$search = we_html_tools::htmlSelect(
							"search[" . $i . "]", $this->searchclass->getStats(), 1, (isset($this->searchclass->search) && is_array($this->searchclass->search) && isset(
								$this->searchclass->search[$i]) ? $this->searchclass->search[$i] : ""), false, array('class' => "defaultfont", 'style' => "width:190px;", 'id' => 'search[' . $i . ']'));
				}
				if($this->searchclass->searchFields[$i] == "timestamp"){
					$locationDisabled = "";
					$handle = "date";
					$search = we_html_tools::getDateSelector("search[" . $i . "]", "_from" . $i, $this->searchclass->search[$i]);
				}
			}

			$out .= '
				<tr id="filterRow_' . $i . '">
					<td>' . we_html_tools::htmlSelect(
					"searchFields[" . $i . "]", $this->searchclass->getFields(), 1, (isset($this->searchclass->searchFields) && is_array($this->searchclass->searchFields) && isset(
						$this->searchclass->searchFields[$i]) ? $this->searchclass->searchFields[$i] : ""), false, array('class' => "defaultfont", 'id' => 'searchFields[' . $i . ']', 'onchange' => 'changeit(this.value, ' . $i . ');')) . '</td>
					<td id="td_location[' . $i . ']">' . we_html_tools::htmlSelect(
					"location[" . $i . "]", we_search_search::getLocation($handle), 1, (isset($this->searchclass->location) && is_array($this->searchclass->location) && isset(
						$this->searchclass->location[$i]) ? $this->searchclass->location[$i] : ""), false, array('class' => "defaultfont", $locationDisabled => $locationDisabled, 'id' => 'location[' . $i . ']')) . '</td>
					<td id="td_search[' . $i . ']">' . $search . '</td>
					<td id="td_delButton[' . $i . ']">' . $button . '</td>
				</tr>
				';
		}

		$out .= '</tbody></table>
<table cellpadding="0" cellspacing="0" id="advSearch3" border="0" style="margin-left:20px;display:' . ($this->searchclass->mode ? 'block' : 'none') . ';">
	<tr>
		<td colspan="4">' . we_html_tools::getPixel(20, 10) . '</td>
	</tr>
	<tr>
		<td width="215">' . we_html_button::create_button("add", "javascript:newinput();") . '</td>
		<td width="155"></td>
		<td width="188" align="right">' . we_html_button::create_button("search", "javascript:search(true);") . '</td>
		<td></td>
	</tr>
	</table>
	<div style="border-top: 1px solid #AFB0AF;margin-top:20px;clear:both;"></div>' .
			we_html_element::jsElement("calendarSetup(" . $this->searchclass->height . ");");

		return $out;
	}

	/**
	 * @abstract create html-Code for paging on top
	 * @return string html-Code
	 */
	function getParameterTop($foundItems){
		$anzahl_all = array(
			10 => 10, 25 => 25, 50 => 50, 100 => 100
		);

		$order = we_base_request::_(we_base_request::RAW, 'we_cmd', $this->searchclass->order, 'order');
		$mode = we_base_request::_(we_base_request::RAW, 'we_cmd', $this->searchclass->mode, 'mode');
		$height = we_base_request::_(we_base_request::RAW, 'we_cmd', $this->searchclass->height, 'height');
		$_anzahl = we_base_request::_(we_base_request::RAW, 'we_cmd', $this->searchclass->anzahl, 'anzahl');
		$we_transaction = we_base_request::_(we_base_request::TRANSACTION, 'we_cmd', $GLOBALS['we_transaction'], 'we_transaction');
		$Text = we_base_request::_(we_base_request::RAW, 'text', $GLOBALS['we_doc']->Text);
		$ID = we_base_request::_(we_base_request::RAW, 'id', $GLOBALS['we_doc']->ID);
		$Path = we_base_request::_(we_base_request::RAW, 'path', $GLOBALS['we_doc']->Path);


		return we_html_tools::hidden("we_transaction", $we_transaction) .
			we_html_tools::hidden("order", $order) .
			we_html_tools::hidden("mode", $mode) .
			we_html_tools::hidden("height", $height) .
			'<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>' . we_html_tools::getPixel(1, 20) . '</td>
</tr>
<tr id="beschreibung_print" class="defaultfont">
	<td>
	<strong>' . g_l('versions', '[versions]') . ':</strong><br/>
	<br/><strong>' . g_l('versions', '[Text]') . ':</strong> ' . $Text . '
	<br/><strong>' . g_l('versions', '[documentID]') . ':</strong> ' . $ID . '
	<br/><strong>' . g_l('versions', '[path]') . ':</strong> ' . $Path . '
	</td>
</tr>
 <tr>
	<td>' . we_html_tools::getPixel(19, 12) . '</td>
	<td id="eintraege_pro_seite" style="font-size:12px;width:130px;">' . g_l('versions', '[eintraege_pro_seite]') . ':</td>
	<td class="defaultgray" style="width:70px;">' .
			we_html_tools::htmlSelect('anzahl', $anzahl_all, 1, $_anzahl, "", array('id' => "anzahl", 'onchange' => 'this.form.elements[\'searchstart\'].value=0;search(false);')) . '
	</td>
	<td class="defaultfont" id="eintraege">' . g_l('versions', '[eintraege]') . '</td>
	<td>' . self::getNextPrev($foundItems) . '</td>
	<td id="print" class="defaultfont">' . we_html_tools::getPixel(
				10, 12) . '<a href="javascript:printScreen();">' . g_l('versions', '[printPage]') . '</a></td>
</tr>
<tr>
	<td colspan="12">' . we_html_tools::getPixel(1, 12) . '</td>
</tr>
</table>';
	}

	/**
	 * @abstract create html-Code for paging on bottom
	 * @return string html-Code
	 */
	function getParameterBottom($foundItems){
		return '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;">
<tr id="paging_bottom">
 <td>' . we_html_tools::getPixel(19, 12) . '</td>
 <td style="font-size:12px;width:130px;">' . we_html_tools::getPixel(30, 12) . '</td>
 <td class="defaultgray" style="width:70px;">' . we_html_tools::getPixel(30, 12) . '</td>
 <td style="width:370px;" id="bottom">' . self::getNextPrev($foundItems) . '</td>
</tr>
</table>';
	}

	/**
	 * @abstract generates html for 'previous' / 'next'
	 * @return string html
	 */
	function getNextPrev($we_search_anzahl){

		if(isset($_REQUEST['we_cmd']['obj'])){
			$thisObj = new weVersionsView();
			$anzahl = $_SESSION['weS']['versions']['anzahl'];
			$searchstart = $_SESSION['weS']['versions']['searchstart'];
		} else {
			$thisObj = $this;
			$anzahl = $thisObj->searchclass->anzahl;
			$searchstart = $thisObj->searchclass->searchstart;
		}

		$out = '<table cellpadding="0" cellspacing="0" border="0"><tr><td id="zurueck">' .
			($searchstart ?
				we_html_button::create_button("back", "javascript:back(" . $anzahl . ");") :
				we_html_button::create_button("back", "", true, 100, 22, "", "", true)) .
			'</td><td>' . we_html_tools::getPixel(10, 2) . '</td>
				<td class="defaultfont"><b>' . (($we_search_anzahl) ? $searchstart + 1 : 0) . '-' .
			(($we_search_anzahl - $searchstart) < $anzahl ?
				$we_search_anzahl :
				$searchstart + $anzahl) .
			' ' . g_l('global', "[from]") . ' ' . $we_search_anzahl . '</b></td><td>' . we_html_tools::getPixel(10, 2) . '</td><td id="weiter">' .
			(($searchstart + $anzahl) < $we_search_anzahl ?
				we_html_button::create_button("next", "javascript:next(" . $anzahl . ");") : //bt_back
				we_html_button::create_button("next", "", true, 100, 22, "", "", true)) .
			'</td><td>' . we_html_tools::getPixel(10, 2) . '</td><td>';

		$pages = array();
		for($i = 0; $i < ceil($we_search_anzahl / $anzahl); $i++){
			$pages[($i * $anzahl)] = ($i + 1);
		}

		$page = ceil($searchstart / $anzahl) * $anzahl;

		$select = we_html_tools::htmlSelect('page', $pages, 1, $page, false, array('id' => 'pageselect', 'onchange' => 'this.form.elements[\'searchstart\'].value=this.value;search(false);'));

		if(!isset($_REQUEST['we_cmd']['setInputSearchstart'])){
			if(!defined("searchstart")){
				define('searchstart', true);
				$out .= we_html_tools::hidden("searchstart", $searchstart);
			}
		}

		$out .= $select .
			'</td></tr></table>';

		return $out;
	}

	/**
	 * @abstract generates content for versions found
	 * @return array with content
	 */
	function getVersionsOfDoc(){

		$thisObj = (isset($_REQUEST['we_cmd']['obj']) ? new weVersionsView() : $this);
		$id = we_base_request::_(we_base_request::INT, 'id', $GLOBALS['we_doc']->ID);
		$table = we_base_request::_(we_base_request::TABLE, 'table', $GLOBALS['we_doc']->Table);
		$_order = we_base_request::_(we_base_request::RAW, 'we_cmd', $thisObj->searchclass->order, 'order');

		$content = array();
		$modificationText = '';

		$where = $thisObj->searchclass->getWhere();
		$_versions = $thisObj->version->loadVersionsOfId($id, $table, $where);
		$resultCount = count($_versions);
		$_SESSION['weS']['versions']['foundItems'] = $resultCount;

		if($resultCount > 0){
			$sortierung = explode(' ', $_order);

			foreach($_versions as $k => $v){
				$_Result[] = $_versions[$k];
			}

			if($sortierung[0] == "modifierID"){
				usort($_Result, array($thisObj, 'sortResultListUser' . (isset($sortierung[1]) ? 'DESC' : 'ASC')));
			} else {
				$sortText = $sortierung[0];
				$sortHow = SORT_ASC;
				$sort1 = array();
				if(isset($sortierung[1])){
					$sortHow = SORT_DESC;
				}
				foreach($_Result as $key => $row){
					$sort1[$key] = strtolower($row[$sortText]);
				}

				array_multisort($sort1, $sortHow, $_Result);
			}

			$_versions = $_Result;
		}

		for($f = 0; $f < $resultCount; $f++){

			$modificationText = $thisObj->getTextForMod($_versions[$f]["modifications"], $_versions[$f]["status"]);
			$user = $_versions[$f]["modifierID"] ? id_to_path($_versions[$f]["modifierID"], USER_TABLE, $this->db) : g_l('versions', '[unknown]');
			$vers = $_versions[$f]["version"];
			$disabledReset = ($_versions[$f]["active"] == 1) ? true : false;
			if(!permissionhandler::hasPerm("ADMINISTRATOR") && !permissionhandler::hasPerm("RESET_VERSIONS")){
				$disabledReset = true;
			}
			$fromScheduler = ($_versions[$f]["fromScheduler"]) ? g_l('versions', '[fromScheduler]') : "";
			$fromImport = ($_versions[$f]["fromImport"]) ? g_l('versions', '[fromImport]') : "";
			$resetFromVersion = ($_versions[$f]["resetFromVersion"]) ? "--" . g_l('versions', '[resetFromVersion]') . $_versions[$f]["resetFromVersion"] . "--" : "";

			$content[] = array(
				array("dat" => '<nobr>' . $vers . '</nobr>'),
				array("dat" => '<nobr>' . we_util_Strings::shortenPath($user, 30) . '</nobr>'),
				array("dat" => '<nobr>' . ($_versions[$f]["timestamp"] ? date("d.m.y - H:i:s", $_versions[$f]["timestamp"]) : "-") . ' </nobr>'),
				array("dat" => (($modificationText != '') ? $modificationText : '') .
					($fromScheduler ? $fromScheduler : '') .
					($fromImport ? $fromImport : '') .
					($resetFromVersion ? $resetFromVersion : '')),
				array("dat" => (permissionhandler::hasPerm("ADMINISTRATOR")) ? we_html_forms::checkbox($_versions[$f]["ID"], 0, "deleteVersion", "", false, "defaultfont", "") : ""),
				array("dat" => "<span class='printShow'>" . we_html_button::create_button("reset", "javascript:resetVersion('" . $_versions[$f]["ID"] . "','" . $_versions[$f]["documentID"] . "','" . $_versions[$f]["version"] . "','" . $_versions[$f]["documentTable"] . "');", true, 100, 22, "", "", $disabledReset) . "</span>"),
				array("dat" => "<span class='printShow'>" . we_html_button::create_button("preview", "javascript:previewVersion('" . $_versions[$f]["ID"] . "');") . "</span>" . we_html_tools::getPixel(1, 1)),
				array("dat" => "<span class='printShow'>" .
					(($_versions[$f]["ContentType"] == we_base_ContentTypes::WEDOCUMENT || $_versions[$f]["ContentType"] == we_base_ContentTypes::HTML || $_versions[$f]["ContentType"] == "objectFile") ?
						we_html_forms::checkbox($_versions[$f]["ID"], 0, "publishVersion_" . $_versions[$f]["ID"], g_l('versions', '[publishIfReset]'), false, "middlefont", "") :
						'') .
					'</span>' . we_html_tools::getPixel(1, 1)),
			);
		}

		return $content;
	}

	/**
	 * @abstract generates headline-titles for columns
	 * @return array with headlines
	 */
	function makeHeadLines(){
		return array(
			array("dat" => '<a href="javascript:setOrder(\'version\');">' . g_l('versions', '[version]') . '</a> <span id="version" >' . $this->getSortImage('version') . '</span>'),
			array("dat" => '<a href="javascript:setOrder(\'modifierID\');">' . g_l('versions', '[user]') . '</a> <span id="modifierID" >' . $this->getSortImage('modifierID') . '</span>'),
			array("dat" => '<a href="javascript:setOrder(\'timestamp\');">' . g_l('versions', '[modTime]') . '</a> <span id="timestamp" >' . $this->getSortImage('timestamp') . '</span>'),
			array("dat" => g_l('versions', '[modifications]')),
			array("dat" => (permissionhandler::hasPerm("ADMINISTRATOR") ? '<div style="margin:0px 0px 5px 0px;" id="deleteButton">' . we_html_button::create_button(
						"image:btn_function_trash", "javascript:deleteVers();") . '</div>' : '') .
				we_html_forms::checkbox(1, 0, "deleteAllVersions", g_l('versions', '[mark]'), false, "middlefont", "checkAll();")),
			array("dat" => we_html_tools::getPixel(1, 1)),
			array("dat" => we_html_tools::getPixel(1, 1)),
			array("dat" => we_html_tools::getPixel(1, 1)),
			array("dat" => we_html_tools::getPixel(1, 1)),
		);
	}

	/**
	 * @abstract sort array in case of 'modifierID'
	 * @return array
	 */
	function sortResultListUserASC($a, $b){
		return strnatcasecmp($a['modifierID'], $b['modifierID']);
	}

	function sortResultListUserDESC($a, $b){
		return strnatcasecmp($b['modifierID'], $a['modifierID']);
	}

	/**
	 * @abstract generate html list for modifications
	 * @return string
	 */
	function getTextForMod($modString, $status){
		$statusTxt = ($status == "published" ? "<div style='color:#ff0000;'>" . g_l('versions', '[' . $status . ']') . "</div>" : '');

		if($modString == ""){
			return $statusTxt;
		}

		$out = '<div>';

		$modifications = makeArrayFromCSV($modString);
		$m = 0;
		foreach($modifications as $k => $v){
			foreach($this->version->modFields as $key => $val){
				if($v == $val){
					$out .= "<strong>- " . g_l('versions', '[' . $key . ']') . '</strong><br/>';
				}
			}
			$m++;
		}

		$out .= "<span style='color:#ff0000;'>" . $statusTxt . '</span>
			</div>';

		return $out;
	}

	/**
	 * @abstract generate html for SortImage
	 * @return string
	 */
	function getSortImage($for){
		$order = we_base_request::_(we_base_request::RAW, 'order', $this->searchclass->order);

		if(strpos($order, $for) === 0){
			if(strpos($order, 'DESC')){
				return '<img border="0" width="11" height="8" src="' . IMAGE_DIR . 'arrow_sort_desc.gif" />';
			}
			return '<img border="0" width="11" height="8" src="' . IMAGE_DIR . 'arrow_sort_asc.gif" />';
		}

		return we_html_tools::getPixel(11, 8);
	}

	/**
	 * @abstract generate html for version list
	 * @return string
	 */
	function tblList($content, $headline){
		$anz = count($headline) - 1;
		return '
<table border="0" style="background-color:#fff;" width="100%" cellpadding="5" cellspacing="0">
<tr>
	<td valign="top" style="width:15px;border-bottom:1px solid #AFB0AF;">' . we_html_tools::getPixel(15, 1) . '</td>
	<td valign="top" style="width:110px;border-bottom:1px solid #AFB0AF;" class="middlefont">' . $headline[0]["dat"] . we_html_tools::getPixel(110, 1) . '</td>
	<td valign="top" style="width:15em;border-bottom:1px solid #AFB0AF;" class="middlefont">' . $headline[1]["dat"] . we_html_tools::getPixel(110, 1) . '</td>
	<td valign="top" style="width:120px;border-bottom:1px solid #AFB0AF;" class="middlefont">' . $headline[2]["dat"] . we_html_tools::getPixel(120, 1) . '</td>
	<td valign="top" style="width:120px;border-bottom:1px solid #AFB0AF;" class="middlefont">' . $headline[4]["dat"] . we_html_tools::getPixel(120, 1) . '</td>
	<td valign="top" style="width:auto;border-bottom:1px solid #AFB0AF;" class="middlefont">' . $headline[3]["dat"] . '</td>
</tr>
</table>
<div id="scrollContent" style="background-color:#fff;width:100%">' .
			$this->tabListContent($this->searchclass->searchstart, $this->searchclass->anzahl, $content) .
			'</div>';
	}

	function tabListContent($searchstart, $anzahl, $content){
		$out = '<table border="0" cellpadding="5" cellspacing="0" width="100%" id="contentTable">';

		$anz = count($content);
		$x = $searchstart + $anzahl;

		if($x > $anz){
			$x = $x - ($x - $anz);
		}
		for($m = $searchstart; $m < $x; $m++){
			$out .= '<tr>' . self::tblListRow($content[$m]) . '</tr>';
		}

		$out .= '</tbody></table>';

		return $out;
	}

	function tblListRow($content){

		$anz = count($content) - 1;
		return '<td valign="top" style="width:15px;">' . we_html_tools::getPixel(1, 1) . '</td>
<td valign="top" style="width:110px;height:30px;" class="middlefont">' . ((isset($content[0]["dat"]) && $content[0]["dat"]) ? $content[0]["dat"] : "&nbsp;") . '</td>
<td valign="top" style="width:15em;" class="middlefont">' . ((isset($content[1]["dat"]) && $content[1]["dat"]) ? $content[1]["dat"] : "&nbsp;") . '</td>
<td valign="top" style="width:120px;" class="middlefont">' . ((isset($content[2]["dat"]) && $content[2]["dat"]) ? $content[2]["dat"] : "&nbsp;") . '</td>
<td valign="top" style="width:120px;" class="middlefont">' . ((isset($content[4]["dat"]) && $content[4]["dat"]) ? $content[4]["dat"] : "&nbsp;") . '</td>
<td valign="top" rowspan="2" style="line-height:20px;width:auto;border-bottom:1px solid #D1D1D1;" class="middlefont">' . ((isset($content[3]["dat"]) && $content[3]["dat"]) ? $content[3]["dat"] : "&nbsp;") . '</td>
</tr>
<tr>
<td style="width:15px;border-bottom:1px solid #D1D1D1;">' . we_html_tools::getPixel(15, 1) . '</td>
<td valign="top" colspan="2" style="width:220px;border-bottom:1px solid #D1D1D1;" class="middlefont">' . ((isset($content[5]["dat"]) && $content[5]["dat"]) ? $content[5]["dat"] : "&nbsp;") . ((isset($content[7]["dat"]) && $content[7]["dat"]) ? $content[7]["dat"] : "&nbsp;") . '</td>
<td valign="top" style="width:120px;border-bottom:1px solid #D1D1D1;" class="middlefont">' . ((isset($content[6]["dat"]) && $content[6]["dat"]) ? $content[6]["dat"] : "&nbsp;") . '</td>
<td valign="top" style="width:120px;border-bottom:1px solid #D1D1D1;" class="middlefont">' . we_html_tools::getPixel(120, 1) . '</td>
<td valign="top" style="width:auto;border-bottom:1px solid #D1D1D1;" class="middlefont">' . we_html_tools::getPixel(1, 1) . '</td>';
	}

	function getHTMLforVersions($content){
		$uniqname = md5(uniqid(__FUNCTION__, true));

		$out = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="defaultfont">';

		foreach($content as $i => $c){

			$mainContent = (isset($c["html"]) && $c["html"]) ? $c["html"] : "";

			$rightContent = '<div class="defaultfont">' . $mainContent . '</div>';

			$out .= '<div style="margin-left:0px" id="div_' . $uniqname . '_' . $i . '">' .
				$rightContent .
				'</div>';
		}

		$out .= '</td></tr></table>';

		return $out;
	}

}
