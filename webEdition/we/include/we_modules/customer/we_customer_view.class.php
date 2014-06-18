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
/* the parent class of storagable webEdition classes */

class we_customer_view extends weModuleView{

	var $customer;
	var $settings;

	const ERR_SAVE_BRANCH = -10;
	const ERR_SAVE_FIELD_INVALID = -7;
	const ERR_SAVE_PROPERTY = -5;
	const ERR_SAVE_FIELD_EXISTS = -4;
	const ERR_SAVE_FIELD_NOT_EMPTY = -3;

	function __construct($frameset = '', $topframe = 'top.content'){
		parent::__construct($frameset, $topframe);
		$this->customer = new we_customer_customer();
		$this->settings = new we_customer_settings();
		$this->settings->customer = & $this->customer;
		$this->settings->load();
	}

	//----------- Utility functions ------------------

	function htmlHidden($name, $value = ''){
		return we_html_element::htmlHidden(array('name' => trim($name), 'value' => oldHtmlspecialchars($value)));
	}

	function setFramesetName($frameset){
		$this->frameset = $frameset;
	}

	function setTopFrame($frame){
		$this->topFrame = $frame;
	}

	function getCommonHiddens($cmds = array()){
		return $this->htmlHidden('cmd', (isset($cmds['cmd']) ? $cmds['cmd'] : '')) .
			$this->htmlHidden('pnt', (isset($cmds['pnt']) ? $cmds['pnt'] : '')) .
			$this->htmlHidden('cmdid', (isset($cmds['cmdid']) ? $cmds['cmdid'] : '')) .
			$this->htmlHidden('activ_sort', (isset($cmds['activ_sort']) ? $cmds['activ_sort'] : '')) .
			$this->htmlHidden('branch', we_base_request::_(we_base_request::STRING, 'branch', g_l('modules_customer', '[common]')));
	}

	function getJSTop(){
		$mod = we_base_request::_(we_base_request::STRING, 'mod', '');
		$modData = we_base_moduleInfo::getModuleData($mod);
		$title = isset($modData['text']) ? 'webEdition ' . g_l('global', '[modules]') . ' - ' . $modData['text'] : '';

		return we_html_element::jsScript(JS_DIR . 'windows.js') . we_html_element::jsElement('
var get_focus = 1;
var activ_tab = 0;
var hot= 0;
var scrollToVal=0;

function setHot() {
	hot = "1";
}

parent.document.title = "' . $title . '";

function usetHot() {
	hot = "0";
}

function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}

function we_cmd() {
	var args = "";
	var url = "' . WEBEDITION_DIR . 'we_cmd.php?"; for(var i = 0; i < arguments.length; i++){ url += "we_cmd["+i+"]="+escape(arguments[i]); if(i < (arguments.length - 1)){ url += "&"; }}
	if(hot == "1" && arguments[0] != "save_customer") {
		if(confirm("' . g_l('modules_customer', '[save_changed_customer]') . '")) {
			arguments[0] = "save_customer";
		} else {
			top.content.usetHot();
		}
	}
	switch (arguments[0]) {
		case "exit_customer":
			if(hot != "1") {
				eval(\'top.opener.top.we_cmd("exit_modules")\');
			}
			break;
		case "new_customer":
			if(' . $this->topFrame . '.editor.edbody.loaded) {
				' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value = arguments[0];
				' . $this->topFrame . '.editor.edbody.document.we_form.cmdid.value = arguments[1];
				' . $this->topFrame . '.editor.edbody.submitForm();
			} else {
				setTimeout(\'we_cmd("new_customer");\', 10);
			}
			break;

		case "delete_customer":
			if(top.content.editor.edbody.document.we_form.cmd.value=="home") return;
			' . (!permissionhandler::hasPerm("DELETE_CUSTOMER") ?
					('
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			') :
					('
					if (' . $this->topFrame . '.editor.edbody.loaded) {
						if (confirm("' . g_l('modules_customer', '[delete_alert]') . '")) {
							' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value=arguments[0];
							' . $this->topFrame . '.editor.edbody.submitForm();
						}
					} else {
						' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[nothing_to_delete]'), we_message_reporting::WE_MESSAGE_ERROR) . '
					}

			')) . '
			break;

		case "save_customer":
			if(top.content.editor.edbody.document.we_form.cmd.value=="home") return;
			' . ((!permissionhandler::hasPerm("EDIT_CUSTOMER") && !permissionhandler::hasPerm("NEW_CUSTOMER")) ?
					('
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			') :
					('

					if (' . $this->topFrame . '.editor.edbody.loaded) {
							' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value=arguments[0];
							' . $this->topFrame . '.editor.edbody.submitForm();
					} else {
						' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[nothing_to_save]'), we_message_reporting::WE_MESSAGE_ERROR) . '
					}
			')) . '
			top.content.usetHot();
			break;

		case "customer_edit":
			' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value=arguments[0];
			' . $this->topFrame . '.editor.edbody.document.we_form.cmdid.value=arguments[1];
			' . $this->topFrame . '.editor.edbody.submitForm();
		break;
		case "show_admin":
		case "show_sort_admin":
			if(' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value=="home") ' . $this->topFrame . '.editor.edbody.document.we_form.home.value="1";
			' . $this->topFrame . '.editor.edbody.document.we_form.cmd.value=arguments[0];
			' . $this->topFrame . '.editor.edbody.document.we_form.cmdid.value=arguments[1];
			' . $this->topFrame . '.editor.edbody.submitForm();
		break;
		case "show_search":
		case "show_customer_settings":
		case "export_customer":
		case "import_customer":
			' . $this->topFrame . '.editor.edbody.we_cmd(arguments[0]);
		break;
		case "load":
			' . $this->topFrame . '.cmd.location="' . $this->frameset . '?pnt=cmd&pid="+arguments[1]+"&offset="+arguments[2]+"&sort="+arguments[3];
		break;
		default:
			for (var i = 0; i < arguments.length; i++) {
				args += "arguments["+i+"]" + ((i < (arguments.length-1)) ? "," : "");
			}
			eval("top.opener.top.we_cmd(" + args + ")");
	}
}');
	}

	function getJSProperty(){
		return we_html_element::jsScript(JS_DIR . 'windows.js') .
			we_html_element::jsElement('
var loaded=0;

function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}

function we_cmd() {
	var args = "";
	var url = "' . WEBEDITION_DIR . 'we_cmd.php?"; for(var i = 0; i < arguments.length; i++){ url += "we_cmd["+i+"]="+escape(arguments[i]); if(i < (arguments.length - 1)){ url += "&"; }}

	switch (arguments[0]) {
		case "browse_users":
			new jsWindow(url,"browse_users",-1,-1,500,300,true,false,true);
		break;
		case "openDocselector":
			new jsWindow(url,"we_fileselector",-1,-1,' . we_selector_file::WINDOW_DOCSELECTOR_WIDTH . ',' . we_selector_file::WINDOW_DOCSELECTOR_HEIGHT . ',true,true,true,true);
		break;
		case "switchPage":
			document.we_form.cmd.value=arguments[0];
			document.we_form.branch.value=arguments[1];
			submitForm();
		break;
		case "show_search":
			keyword = top.content.we_form_treefooter.keyword.value;
			new jsWindow("' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=search&search=1&keyword=" + keyword,"search",-1,-1,650,600,true,true,true,false);
			break;
		case "show_customer_settings":
			new jsWindow("' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=settings","customer_settings",-1,-1,570,270,true,true,true,false);
			break;
		case "export_customer":
			new jsWindow("' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=export","export_customer",-1,-1,640,600,true,true,true,false);
			break;
		case "import_customer":
			new jsWindow("' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=import","import_customer",-1,-1,640,600,true,true,true,false);
			break;
		default:
			for (var i = 0; i < arguments.length; i++) {
				args += "arguments["+i+"]" + ((i < (arguments.length-1)) ? "," : "");
			}
			eval("top.content.we_cmd("+args+")");
	}
}

function setMultiSelectData(name,max){
	var tmp="";
	for (var i=0; i<max; ++i){
		var val=document.getElementsByName(name+"_multi_"+i)[0];
		if(val.checked){
			tmp+=val.value+",";
		}
	}
	tmp=tmp.substr(0,tmp.length-1);
	document.getElementsByName(name)[0].value=tmp;
	top.content.setHot();
}

function formatDate(date,format){

	var daynum=date.getDate();
	var day=daynum.toString();
	if(format.search("d")!=-1)
		if(daynum<10) day="0"+day;

	format=format.replace("d",day);
	format=format.replace("j",day);

	var monthnum=date.getMonth()+1;
	var month=monthnum.toString();
	if(format.search("m")!=-1)
		if(monthnum<10) month="0"+month;

	format=format.replace("m",month);
	format=format.replace("n",month);

	format=format.replace("Y",date.getFullYear());
	var yearnum=date.getYear();
	var year=yearnum.toString();
	format=format.replace("y",year.substr(2,2));

	var hournum=date.getHours();
	var hour=hournum.toString();
	if(format.search("H")!=-1)
		if(hournum<10) hour="0"+hour;

	format=format.replace("H",hour);
	format=format.replace("G",hour);

	var minnum=date.getMinutes();
	var min=minnum.toString();
	if(minnum<10) min="0"+min;

	format=format.replace("i",min);


	/*var secnum=date.getSeconds();
	var sec=secnum.toString();
	if(secnum<10) sec="0"+sec;*/

	var sec="00";

	format=format.replace("s",sec);

	format=format.replace(/\\\/g,"");

	return format;
}

function refreshForm(){
	if(document.we_form.cmd.value!="home"){
		we_cmd("switchPage",' . $this->topFrame . '.activ_tab);
		' . $this->topFrame . '.editor.edheader.location="' . $this->frameset . '?pnt=edheader&text=' . urlencode($this->customer->Text) . '";
	}
}' . $this->getJSSubmitFunction());
	}

	function getJSSortAdmin(){
		return we_customer_add::getJSSortAdmin($this);
	}

	function getJSAdmin(){
		return '
function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}

function we_cmd(){
	var args = "";
	var url = "' . $this->frameset . '?"; for(var i = 0; i < arguments.length; i++){ url += "we_cmd["+i+"]="+escape(arguments[i]); if(i < (arguments.length - 1)){ url += "&"; }}

	switch (arguments[0]) {
		case "open_add_field":
			var branch=document.we_form.branch.value;
			url = "' . $this->frameset . '?pnt=field_editor&art=add&branch="+branch;
			new jsWindow(url,"field_editor",-1,-1,380,250,true,false,true);
		break;
		case "open_edit_field":
			var field=document.we_form.fields_select.value;
			var branch=document.we_form.branch.value;
			if(field=="") {
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_field]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else{
					url = "' . $this->frameset . '?pnt=field_editor&art=edit&field="+field+"&branch="+branch;
					new jsWindow(url,"field_editor",-1,-1,380,250,true,false,true);
			}
		break;
		case "delete_field":
			var field=document.we_form.fields_select.value;
			if(field=="") {
			 ' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_field]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else{
				if(confirm("' . g_l('modules_customer', '[del_fild_question]') . '")){
					document.we_form.cmd.value=arguments[0];
					submitForm();
				}
			}
		break;
		case "reset_edit_order":
			var field=document.we_form.fields_select.value;
			var branch=document.we_form.branch.value;
				if(confirm("' . g_l('modules_customer', '[reset_edit_order_question]') . '")){
					document.we_form.cmd.value=arguments[0];
					submitForm();
				}
		break;
		case "move_field_up":
			var field=document.we_form.fields_select.value;
			var branch=document.we_form.branch.value;
			if(field=="") {
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_field]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else{
					document.we_form.cmd.value=arguments[0];
					submitForm();
			}
		break;
		case "move_field_down":
			var field=document.we_form.fields_select.value;
			var branch=document.we_form.branch.value;
			if(field=="") {
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_field]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else{
					document.we_form.cmd.value=arguments[0];
					submitForm();
			}
		break;
		case "open_edit_branch":
			var branch=document.we_form.branch_select.options[document.we_form.branch_select.selectedIndex].text;
			if(branch=="") {
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[no_branch]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else if(branch=="' . g_l('modules_customer', '[other]') . '") {
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[branch_no_edit]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			} else{
					url = "' . $this->frameset . '?pnt=branch_editor&art=edit&&branch="+branch;
					new jsWindow(url,"field_editor",-1,-1,380,250,true,false,true);
			}
		break;
		case "save_branch":
		case "save_field":
			var field_name=document.we_form.name.value;
			if(field_name=="" || field_name.match(/[^a-zA-Z0-9\_]/)!=null){
				' . we_message_reporting::getShowMessageCall(g_l('modules_customer', '[we_fieldname_notValid]'), we_message_reporting::WE_MESSAGE_ERROR) . '
			}
			else{
				document.we_form.cmd.value=arguments[0];
				submitForm("field_editor");
			}
		break;
		default:
			for (var i = 0; i < arguments.length; i++) {
				args += \'arguments[\'+i+\']\' + ((i < (arguments.length-1)) ? \',\' : \'\');
			}
			eval(\'top.content.we_cmd(\'+args+\')\');
	}
}

function selectBranch(){
	var f=document.we_form;
	var txt=f.branch;
	var sel=f.branch_select.options[f.branch_select.selectedIndex].text;

	f.cmd.value="switchBranch";
	txt.value=sel;
	submitForm();

}

function saveField(){
	document.we_form.cmd.value="save_field";
	submitForm();
}' .
			$this->getJSSubmitFunction("customer_admin");
	}

	function getJSTreeHeader(){
		return '
function applySort(){
	document.we_form_treeheader.pnt.value="cmd";
	document.we_form_treeheader.cmd.value="applySort";
	submitForm("", "", "", "we_form_treeheader");
}

function addSorting(sortname) {
	var found=false;
	len = document.we_form_treeheader.sort.options.length;
	for(i=0;i<len;i++) {
		if(document.we_form_treeheader.sort.options[i].value==sortname){
			found = true;
		}
	}
	if(!found){
		document.we_form_treeheader.sort.options[len] = new Option(sortname,sortname);
	}

}

/*
//obsolete after removing treeheader frame
function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}
*/

/*
//this function was used to loop treeheader.we_cmd() to content.we_cmd: obsolete, because all treeheader-JS was moved to content
function we_cmd(){
	var args = "";
	var url = "' . $this->frameset . '?"; for(var i = 0; i < arguments.length; i++){ url += "we_cmd["+i+"]="+escape(arguments[i]); if(i < (arguments.length - 1)){ url += "&"; }}
	switch (arguments[0]) {
		default:
			for (var i = 0; i < arguments.length; i++) {
				args += \'arguments[\'+i+\']\' + ((i < (arguments.length-1)) ? \',\' : \'\');
			}
			eval(\'top.content.we_cmd(\'+args+\')\')//da;
	}
}
*/' .
			$this->getJSSubmitFunction('cmd', 'post', 'we_form_treeheader');
	}

	function getJSSearch(){
		return we_customer_add::getJSSearch($this);
	}

	function getJSSettings(){
		return '
function doUnload() {
	if (!!jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			eval("jsWindow" + i + "Object.close()");
		}
	}
}

function we_cmd(){
	var args = "";
	var url = "' . $this->frameset . '?"; for(var i = 0; i < arguments.length; i++){ url += "we_cmd["+i+"]="+escape(arguments[i]); if(i < (arguments.length - 1)){ url += "&"; }}
	switch (arguments[0]) {
		case "save_settings":
			document.we_form.cmd.value=arguments[0];
			submitForm();
		break;
		default:
	}
}

self.focus();' . $this->getJSSubmitFunction("customer_settings");
	}

	/* use parent
	  function getJSSubmitFunctionBack($def_target = 'edbody', $def_method = 'post'){}
	 */

	function processCommands(){
		switch(we_base_request::_(we_base_request::STRING, 'cmd')){
			case 'new_customer':
				$this->customer = new we_customer_customer();
				$this->settings->initCustomerWithDefaults($this->customer);
				echo we_html_element::jsElement(
					$this->topFrame . '.editor.edheader.location="' . $this->frameset . '?pnt=edheader&text=' . urlencode($this->customer->Text) . '";' .
					$this->topFrame . '.editor.edfooter.location="' . $this->frameset . '?pnt=edfooter";'
				);
				break;
			case 'customer_edit':
				$this->customer = new we_customer_customer(we_base_request::_(we_base_request::INT, "cmdid"));
				echo we_html_element::jsElement(
					$this->topFrame . '.editor.edheader.location="' . $this->frameset . '?pnt=edheader&text=' . urlencode($this->customer->Text) . '";' .
					$this->topFrame . '.editor.edfooter.location="' . $this->frameset . '?pnt=edfooter";'
				);
				break;
			case 'save_customer':
				$js = '';
				$this->customer->Username = trim($this->customer->Username);
				if($this->customer->Username == ''){
					echo we_html_element::jsElement(
						we_message_reporting::getShowMessageCall(g_l('modules_customer', '[username_empty]'), we_message_reporting::WE_MESSAGE_ERROR)
					);
					break;
				}

				if($this->customer->filenameNotValid()){
					print we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_customer', '[we_filename_notValid]'), we_message_reporting::WE_MESSAGE_ERROR));
					break;
				}

				$newone = true;
				if($this->customer->ID){
					$newone = false;
				}

				$exists = f('SELECT ID FROM ' . CUSTOMER_TABLE . ' WHERE Username="' . $this->db->escape($this->customer->Username) . '"' . ($newone ? '' : ' AND ID!=' . $this->customer->ID), 'ID', $this->db);
				if($exists){
					print we_html_element::jsElement(
							we_message_reporting::getShowMessageCall(sprintf(g_l('modules_customer', '[username_exists]'), $this->customer->Username), we_message_reporting::WE_MESSAGE_ERROR)
					);
					break;
				}
				$c = unserialize($_SESSION['weS']['customer_session']);
				if($c->Password != $this->customer->Password || $this->customer->LoginDenied || $this->customer->AutoLoginDenied){//bei Password�nderungen die persistentlogins l�schen
					$this->db->query('DELETE FROM ' . CUSTOMER_AUTOLOGIN_TABLE . ' WHERE WebUserID=' . intval($this->customer->ID));
				}
				//FIXME: what if save failes (e.g. Hooks?)
				$this->customer->save();

				$tt = addslashes(f('SELECT ' . $this->settings->treeTextFormatSQL . ' AS treeFormat FROM ' . CUSTOMER_TABLE . ' WHERE ID=' . intval($this->customer->ID), 'treeFormat', $this->db));
				if($newone){
					$js = '
var attribs = new Array();
attribs["icon"]="' . $this->customer->Icon . '";
attribs["id"]="' . $this->customer->ID . '";
attribs["typ"]="item";
attribs["parentid"]="0";
attribs["text"]="' . $tt . '";
attribs["disable"]="0";
attribs["tooltip"]="' . (($this->customer->Forename != "" || $this->customer->Surname != "") ? $this->customer->Forename . "&nbsp;" . $this->customer->Surname : "") . '";' .
						$this->topFrame . '.treeData.addSort(new ' . $this->topFrame . '.node(attribs));';
					$js .= $this->topFrame . '.applySort();';
				} else {
					$js = $this->topFrame . '.updateEntry(' . $this->customer->ID . ',"' . $tt . '");' . "\n";
				}


				print we_html_element::jsElement(
						$js .
						we_message_reporting::getShowMessageCall(sprintf(g_l('modules_customer', '[customer_saved_ok]'), addslashes($this->customer->Text)), we_message_reporting::WE_MESSAGE_NOTICE)
				);
				break;
			case 'delete_customer':
				$oldid = $this->customer->ID;
				$this->customer->delete();
				$this->customer = new we_customer_customer();

				print we_html_element::jsElement(
						we_message_reporting::getShowMessageCall(g_l('modules_customer', '[customer_deleted]'), we_message_reporting::WE_MESSAGE_NOTICE) .
						$this->topFrame . '.deleteEntry("' . $oldid . '"); ' .
						$this->topFrame . '.editor.edheader.location="' . $this->frameset . '?home=1&pnt=edheader"; ' .
						$this->topFrame . '.editor.edbody.location="' . $this->frameset . '?home=1&pnt=edbody"; ' .
						$this->topFrame . '.editor.edfooter.location="' . $this->frameset . '?home=1&pnt=edfooter";'
				);

				break;
			case 'switchPage':

				break;

			case 'show_admin':
				$js = '
						url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=customer_admin";
						new jsWindow(url,"customer_admin",-1,-1,600,420,true,true,true,false);
					';
				print we_html_element::jsScript(JS_DIR . "windows.js") .
					we_html_element::jsElement($js);
				break;
			case 'save_field':
				$branch = we_base_request::_(we_base_request::STRING, 'branch');
				$field = we_base_request::_(we_base_request::STRING, 'field');
				$field_name = we_base_request::_(we_base_request::STRING, 'name');
				$field_type = we_base_request::_(we_base_request::STRING, 'field_type');
				$field_default = we_base_request::_(we_base_request::STRING, 'field_default');

				$saveret = $this->saveField($field, $branch, $field_name, $field_type, $field_default);

				switch($saveret){
					case self::ERR_SAVE_BRANCH:
						$js = we_message_reporting::getShowMessageCall(g_l('modules_customer', '[branch_no_edit]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					case self::ERR_SAVE_FIELD_INVALID:
						$js = we_message_reporting::getShowMessageCall(g_l('modules_customer', '[we_fieldname_notValid]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					case self::ERR_SAVE_PROPERTY:
						$js = we_message_reporting::getShowMessageCall(sprintf(g_l('modules_customer', '[cannot_save_property]'), $field_name), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					case self::ERR_SAVE_FIELD_EXISTS:
						$js = we_message_reporting::getShowMessageCall(g_l('modules_customer', '[fieldname_exists]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					case self::ERR_SAVE_FIELD_NOT_EMPTY:
						$js = we_message_reporting::getShowMessageCall(g_l('modules_customer', '[field_not_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					default:
						$this->customer->loadPresistents();
						$sort = $this->settings->getEditSort();
						$sortarray = makeArrayFromCSV($sort);
						$orderedarray = $this->customer->persistent_slots;

						$cs = count($sortarray);
						$co = count($orderedarray);
						if(count($sortarray) != count($orderedarray)){
							if(count($sortarray) < count($orderedarray)){
								$sortarray[] = max($sortarray) + 1;
							}
							if(count($sortarray) < count($orderedarray)){
								$sortarray[] = max($sortarray) + 1;
							}
							if(count($sortarray) < count($orderedarray)){
								$sortarray[] = max($sortarray) + 1;
							}
							if(count($sortarray) != count($orderedarray)){
								$sortarray = range(0, count($orderedarray) - 1);
							}
						}
						$this->settings->setEditSort(makeCSVFromArray($sortarray, true));
						$this->settings->save();

						$js = '
							opener.submitForm();
							opener.opener.refreshForm();
							close();';
				}
				print we_html_element::jsElement($js);

				break;
			case 'delete_field':
				$field = we_base_request::_(we_base_request::STRING, 'fields_select');

				$sort = $this->settings->getEditSort();
				$sortarray = makeArrayFromCSV($sort);
				$orderedarray = $this->customer->persistent_slots;
				if(count($sortarray) != count($orderedarray)){
					$sortarray = range(0, count($orderedarray) - 1);
				}
				$orderedarray = array_combine($sortarray, $orderedarray);
				ksort($orderedarray);
				$curpos = array_search($field, $orderedarray);
				$curposS = array_search($curpos, $sortarray);
				unset($sortarray[$curposS]);
				foreach($sortarray as &$val){
					if($val >= $curpos){
						$val--;
					}
				}
				if($sortarray[count($sortarray) - 1] == ''){
					array_pop($sortarray);
				}
				$this->settings->setEditSort(makeCSVFromArray($sortarray, true));
				$this->settings->save();

				$ber = '';
				$fname = $this->customer->transFieldName($field, $ber);

				$this->deleteField(($ber == '' && preg_match('%' . g_l('modules_customer', '[other]') . '%i', $field) ? $fname : $field));

				$this->customer->loadPresistents();
				$js = we_message_reporting::getShowMessageCall(sprintf(g_l('modules_customer', '[field_deleted]'), $fname, $ber), we_message_reporting::WE_MESSAGE_NOTICE) .
					'opener.refreshForm();';
				print we_html_element::jsElement($js);

				break;
			case 'reset_edit_order':
				$orderedarray = $this->customer->persistent_slots;
				$sortarray = range(0, count($orderedarray) - 1);
				$this->settings->setEditSort(makeCSVFromArray($sortarray, true));
				$this->settings->save();
				break;
			case 'move_field_up':
				$field = we_base_request::_(we_base_request::STRING, 'fields_select');
				$sort = $this->settings->getEditSort();
				$sortarray = makeArrayFromCSV($sort);
				$orderedarray = $this->customer->persistent_slots;
				if(count($sortarray) != count($orderedarray)){
					if(count($sortarray) < count($orderedarray)){
						$sortarray[] = max($sortarray) + 1;
					}
					if(count($sortarray) != count($orderedarray)){
						$sortarray = range(0, count($orderedarray) - 1);
					}
				}
				$orderedarray = array_combine($sortarray, $orderedarray);
				ksort($orderedarray);

				$curpos = array_search($field, $orderedarray);
				$curpos1 = $curpos - 1;
				if($curpos != 0){
					$sort = str_replace(array(',' . $curpos . ',', ',' . $curpos1 . ','), array(',XX,', ',YY,'), $sort);
					$sort = str_replace(array(',XX,', ',YY,'), array(',' . $curpos1 . ',', ',' . $curpos . ','), $sort);

					$this->settings->setEditSort($sort);
					$this->settings->save();
					$this->customer->loadPresistents();
				}
				$js = 'opener.refreshForm();';
				print we_html_element::jsElement($js);

				break;
			case 'move_field_down':
				$field = we_base_request::_(we_base_request::STRING, 'fields_select');
				$sort = $this->settings->getEditSort();
				$sortarray = makeArrayFromCSV($sort);
				$orderedarray = $this->customer->persistent_slots;
				if(count($sortarray) != count($orderedarray)){
					if(count($sortarray) < count($orderedarray)){
						$sortarray[] = max($sortarray) + 1;
					}
					if(count($sortarray) != count($orderedarray)){
						$sortarray = range(0, count($orderedarray) - 1);
					}
				}
				$orderedarray = array_combine($sortarray, $orderedarray);
				ksort($orderedarray);

				$curpos = array_search($field, $orderedarray);
				$curpos1 = $curpos + 1;
				if($curpos != count($orderedarray) - 1){
					$sort = str_replace(array(',' . $curpos . ',', ',' . $curpos1 . ','), array(',XX,', ',YY,'), $sort);
					$sort = str_replace(array(',XX,', ',YY,'), array(',' . $curpos1 . ',', ',' . $curpos . ','), $sort);
					$this->settings->setEditSort($sort);
					$this->settings->save();
					$this->customer->loadPresistents();
				}
				$js = 'opener.refreshForm();';
				print we_html_element::jsElement($js);

				break;
			case 'save_branch':
				$branch_new = we_base_request::_(we_base_request::STRING, 'name', '');
				$branch_old = we_base_request::_(we_base_request::STRING, 'branch', '');

				if($branch_new == g_l('modules_customer', '[common]') || $branch_new == g_l('modules_customer', '[other]') || $branch_new == g_l('modules_customer', '[all]')){
					print we_html_element::jsElement(
							we_message_reporting::getShowMessageCall(g_l('modules_customer', '[branch_no_edit]'), we_message_reporting::WE_MESSAGE_ERROR)
					);
					return;
				}

				if($branch_new != $branch_old){
					$arr = $this->customer->getBranchesNames();

					if(in_array($branch_new, $arr)){
						print we_html_element::jsElement(
								we_message_reporting::getShowMessageCall(g_l('modules_customer', '[name_exists]'), we_message_reporting::WE_MESSAGE_ERROR)
						);
						return;
					}
				}

				if($this->saveBranch($branch_old, $branch_new) == -5){
					we_message_reporting::getShowMessageCall(sprintf(g_l('modules_customer', '[cannot_save_property]'), $field), we_message_reporting::WE_MESSAGE_ERROR);
				} else {
					$this->customer->loadPresistents();
					$js = '
opener.document.we_form.branch.value="' . g_l('modules_customer', '[other]') . '";
opener.submitForm();
opener.opener.document.we_form.branch.value="' . g_l('modules_customer', '[common]') . '";
opener.opener.refreshForm();
close();';
				}
				print we_html_element::jsElement($js);

				break;
			case 'show_sort_admin':
				$js = '
url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=sort_admin";
new jsWindow(url,"sort_admin",-1,-1,750,500,true,true,true,true);';
				print we_html_element::jsScript(JS_DIR . 'windows.js');
				print we_html_element::jsElement($js);

				break;
			case 'add_sort':
				$cout = 0;
				$found = false;
				while(!$found){
					$cname = g_l('modules_customer', '[sort_name]') . $cout;
					if(!in_array($cname, array_keys($this->settings->SortView)))
						$found = true;
					$cout++;
				}
				$this->settings->SortView[$cname] = array();

				break;
			case 'del_sort':
				if(($i = we_base_request::_(we_base_request::INT, 'sortindex')) !== false){
					we_base_util::new_array_splice($this->settings->SortView, $i, 1);
				}
				break;
			case 'add_sort_field':
				if(($i = we_base_request::_(we_base_request::INT, 'sortindex')) !== false){
					$this->settings->SortView[$i][] = array('branch' => '', 'field' => '', 'order' => '');
				}
				break;
			case 'del_sort_field':
				if(($i = we_base_request::_(we_base_request::INT, 'sortindex')) !== false &&
					($j = we_base_request::_(we_base_request::INT, 'fieldindex')) !== false){

					array_splice($this->settings->SortView[$i], $j, 1);
				}
				break;
			case 'save_sort':

				$this->settings->save();
				$_sorting = 'opener.' . $this->topFrame . '.addSorting("' . g_l('modules_customer', '[no_sort]') . '");' . "\n";
				foreach(array_keys($this->settings->SortView) as $_sort){
					$_sorting .= 'opener.' . $this->topFrame . '.addSorting("' . $_sort . '");' . "\n";
				}

				$js = we_message_reporting::getShowMessageCall(g_l('modules_customer', '[sort_saved]'), we_message_reporting::WE_MESSAGE_NOTICE) . '
var selected = opener.' . $this->topFrame . '.document.we_form_treeheader.sort.selectedIndex;
opener.' . $this->topFrame . '.document.we_form_treeheader.sort.options.length=0;
' . $_sorting . '

if(selected<opener.' . $this->topFrame . '.document.we_form_treeheader.sort.options.length){
	opener.' . $this->topFrame . '.document.we_form_treeheader.sort.selectedIndex = selected;
} else {
	opener.' . $this->topFrame . '.document.we_form_treeheader.sort.selectedIndex = opener.' . $this->topFrame . '.document.we_form_treeheader.sort.options.length-1;
}

opener.' . $this->topFrame . '.applySort();
self.close();';
				echo we_html_element::jsScript(JS_DIR . "we_showMessage.js") .
				we_html_element::jsElement($js);
				break;
			case 'applySort':
				echo we_html_element::jsElement($$this->topFrame . '.clearTree();');
				break;
			case 'show_search':
				echo we_html_element::jsScript(JS_DIR . "windows.js") .
				we_html_element::jsElement('url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=search&search=1&keyword=' . we_base_request::_(we_base_request::STRING, "keyword") . '";
						new jsWindow(url,"search",-1,-1,650,600,true,true,true,false);');
				break;
			case 'show_customer_settings':
				echo we_html_element::jsScript(JS_DIR . "windows.js") .
				we_html_element::jsElement('url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=settings";
						new jsWindow(url,"customer_settings",-1,-1,550,250,true,true,true,false);');
				break;
			case 'import_customer':
				echo we_html_element::jsScript(JS_DIR . "windows.js") .
				we_html_element::jsElement('url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=import";
						new jsWindow(url,"import_customer",-1,-1,640,600,true,true,true,false);');
				break;
			case 'export_customer':
				echo we_html_element::jsScript(JS_DIR . "windows.js") .
				we_html_element::jsElement('url ="' . WE_CUSTOMER_MODULE_DIR . 'edit_customer_frameset.php?pnt=export";
						new jsWindow(url,"export_customer",-1,-1,640,600,true,true,true,false);');
				break;
			case 'save_settings':

				foreach($this->settings->getAllSettings() as $k => $v){
					$set = we_base_request::_(we_base_request::STRING, $k);
					if($set !== false){
						$this->settings->setSettings($k, $set);
					}
				}
				foreach($this->settings->properties as $k => $v){
					$set = we_base_request::_(we_base_request::STRING, $k);
					if($set !== false){
						$this->settings->properties[$k] = $set;
					}
				}
				echo we_html_element::jsScript(JS_DIR . 'we_showMessage.js') .
				we_html_element::jsElement(
					$this->settings->save() ?
						we_message_reporting::getShowMessageCall(g_l('modules_customer', '[settings_saved]'), we_message_reporting::WE_MESSAGE_NOTICE) . 'self.close();' :
						we_message_reporting::getShowMessageCall(g_l('modules_customer', '[settings_not_saved]'), we_message_reporting::WE_MESSAGE_NOTICE)
				);
				break;
			default:
		}


		$_SESSION['weS']['customer_session'] = serialize($this->customer);
	}

	function processVariables(){
		if(isset($_SESSION['weS']['customer_session'])){
			$this->customer = unserialize($_SESSION['weS']['customer_session']);
		}
		if(($sid = we_base_request::_(we_base_request::INT, 'sid'))){
			$this->customer = new we_customer_customer($sid);
			$_SESSION['weS']['customer_session'] = serialize($this->customer);
		}
		if(is_array($this->customer->persistent_slots)){
			foreach($this->customer->persistent_slots as $val){
				$varname = $val;
				switch($varname){
					case 'LoginDenied':
						if(we_base_request::_(we_base_request::BOOL, 'LoginDenied')){
							$this->customer->LoginDenied = 1;
						} elseif(we_base_request::_(we_base_request::STRINGC, 'Username')){
							$this->customer->LoginDenied = 0;
						}
						break;
					case 'Password':
						$pw = we_base_request::_(we_base_request::RAW, 'Password');
						if($pw && $pw != we_customer_customer::NOPWD_CHANGE){//keep old pwd
							$this->customer->Password = we_customer_customer::cryptPassword($pw);
						}
						break;
					default:
						if(($v = we_base_request::_(we_base_request::STRINGC, $varname))){
							$this->customer->{$val} = $v;
						}
				}
			}
		}


		if(we_base_request::_(we_base_request::STRING, 'pnt') == 'sort_admin'){
			$counter = we_base_request::_(we_base_request::INT, 'counter');

			if($counter !== false){
				$this->settings->SortView = array();

				for($i = 0; $i < $counter; $i++){

					$sort_name = we_base_request::_(we_base_request::STRING, 'sort_' . $i);
					$sort_name = $sort_name ? $sort_name : g_l('modules_customer', '[sort_name]') . '_' . $i;


					$fcounter = we_base_request::_(we_base_request::INT, 'fcounter_' . $i, 1);

					if($fcounter > -1){
						$this->settings->SortView[$sort_name] = array();
					}
					for($j = 0; $j < $fcounter; $j++){
						$new = array();
						if(($b = we_base_request::_(we_base_request::STRING, 'branch_' . $i . '_' . $j))){
							$new['branch'] = $b;
						}
						if(($field = we_base_request::_(we_base_request::STRING, 'field_' . $i . '_' . $j))){
							$new['field'] = ($new['branch'] == g_l('modules_customer', '[common]') ?
									str_replace(g_l('modules_customer', '[common]') . '_', '', $field) :
									$field);
						}
						if(($func = we_base_request::_(we_base_request::STRING, 'function_' . $i . '_' . $j))){
							$new['function'] = $func;
						}
						if(($ord = we_base_request::_(we_base_request::STRING, 'order_' . $i . '_' . $j))){
							$new['order'] = $ord;
						}
						$this->settings->SortView[$sort_name][$j] = $new;
					}
				}
			}
		}
	}

	function getFieldProperties($field){
		$ret = array();

		//if(preg_match(g_l('modules_customer','[other]'),$field)) $field=str_replace(g_l('modules_customer','[other]')."_","",$field);

		$props = $this->customer->getFieldDbProperties($field);

		if(isset($props['Field'])){
			$ret['name'] = $this->customer->transFieldName($props['Field'], $branch);
		}
		if(isset($props['Type'])){
			$ret['type'] = $this->settings->getFieldType($props['Field']);
		}

		$ret['default'] = (isset($this->settings->FieldAdds[$field]['default']) ? $this->settings->FieldAdds[$field]['default'] : '');

		return $ret;
	}

	// field - contains full field name with branche
	// branch - branch name
	// field_name - field name without branch name
	// field_default - predefined values

	function saveField($field, $branch, $field_name, $field_type, $field_default){
		if($branch == g_l('modules_customer', '[common]')){
			return self::ERR_SAVE_BRANCH;
		}

		if($branch == g_l('modules_customer', '[other]')){
			$field = str_replace(g_l('modules_customer', '[other]') . '_', '', $field);
		}
		if($field_name == ''){
			return self::ERR_SAVE_FIELD_NOT_EMPTY;
		}

		$h = $this->customer->getFieldDbProperties($field);

		$new_field_name = (($branch && $branch != g_l('modules_customer', '[other]')) ? $branch . '_' : '') . $field_name;

		if(preg_match('|[^a-z0-9\_]|i', $new_field_name)){
			return self::ERR_SAVE_FIELD_INVALID;
		}

		if($field != $new_field_name && count($this->customer->getFieldDbProperties($new_field_name))){
			return self::ERR_SAVE_FIELD_EXISTS;
		}

		if($this->customer->isProperty($field) || $this->customer->isProtected($field) || $this->customer->isProperty($new_field_name) || $this->customer->isProtected($new_field_name)){
			return self::ERR_SAVE_PROPERTY;
		}
		if($branch == g_l('modules_customer', '[other]') && $this->settings->isReserved($new_field_name)){
			return self::ERR_SAVE_PROPERTY;
		}

		if($h){
			$this->settings->removeFieldAdd($field);
		}
		$this->settings->storeFieldAdd($new_field_name, 'default', $field_default);
		$this->settings->storeFieldAdd($new_field_name, 'type', $field_type);


		$this->db->query('ALTER TABLE ' . CUSTOMER_TABLE . ' ' . ((count($h)) ? 'CHANGE ' . $field : 'ADD') . ' ' . $new_field_name . ' ' . $this->settings->getDbType($field_type, $new_field_name) . ' NOT NULL;');

		$this->settings->save();
	}

	function deleteField($field){
		$h = $this->customer->getFieldDbProperties($field);

		if($h){
			$this->db->query('ALTER TABLE ' . $this->customer->table . ' DROP ' . $field);
		}

		$this->settings->removeFieldAdd($field);

		$this->settings->save();
	}

	function saveBranch($old_branch, $new_branch){
		$h = $this->customer->getFieldsDbProperties();
		foreach($h as $k => $v){
			if(strpos($k, $old_branch) !== false){
				$banche = '';
				$fieldname = $this->customer->transFieldName($k, $banche);
				if($banche == $old_branch && $fieldname != ''){
					$this->db->query('ALTER TABLE ' . $this->customer->table . ' CHANGE ' . $k . ' ' . $new_branch . '_' . $fieldname . ' ' . $v['Type'] . (!empty($v["Default"]) ? " DEFAULT '" . $v["Default"] . "'" : '') . ' NOT NULL');
				}
			}
		}

		$this->settings->renameFieldAdds($old_branch, $new_branch);
		$this->settings->save();
	}

	function getSearchResults($keyword, $res_num = 0){
		if(!$res_num){
			$res_num = $this->settings->getMaxSearchResults();
		}

		$arr = explode(' ', strToLower($keyword));
		$array = array(
			'AND' => array($arr[0]),
			'OR' => array(),
			'AND NOT' => array()
		);


		for($i = 1; $i < count($arr); $i++){
			switch($arr[$i]){
				case 'not':
					$i++;
					$array['AND NOT'][count($array['NOT'])] = $arr[$i];
					break;
				case 'and':
					$i++;
					$array['AND'][count($array['AND'])] = $arr[$i];
					break;
				case'or':
					$i++;
					$array['OR'][count($array['OR'])] = $arr[$i];
					break;
				default:
			}
		}
		$condition = '';

		foreach($array as $ak => $av){
			foreach($av as $value){
				$conditionarr = array();
				foreach($this->customer->persistent_slots as $field){
					if(!$this->customer->isProtected($field) && $field != "Password"){
						$conditionarr[] = "$field LIKE '%$value%'";
					}
				}
				$condition.=($condition ?
						' ' . $ak . ' (' . implode(' OR ', $conditionarr) . ')' :
						' (' . implode(' OR ', $conditionarr) . ')'
					);
			}
		}

		$this->db->query('SELECT ID, CONCAT(Username, " (",Forename," ",Surname,")") AS user FROM ' . $this->db->escape($this->customer->table) . ($condition ? ' WHERE ' . $condition : '') . ' ORDER BY Username' . " LIMIT 0,$res_num");
		return array_map('oldHtmlspecialchars', $this->db->getAllFirst(false));
	}

}
