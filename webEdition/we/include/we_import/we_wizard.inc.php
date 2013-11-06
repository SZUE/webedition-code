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
class we_wizard{

	var $path = '';

	function __construct(){
		$this->path = WE_INCLUDES_DIR . 'we_import/we_wiz_frameset.php';
	}

	function getWizFrameset(){
		$args = "pnt=wizbody";
		if(isset($_REQUEST['we_cmd'][1])){
			$args .= "&we_cmd[1]=" . $_REQUEST['we_cmd'][1];
		}

		$body = we_html_element::htmlBody(array('style' => 'background-color:grey;margin: 0px;position:fixed;top:0px;left:0px;right:0px;bottom:0px;border:0px none;', "onload" => "wiz_next('wizbody', '" . $this->path . "?" . $args . "');")
				, we_html_element::htmlDiv(array('style' => 'position:absolute;top:0px;bottom:0px;left:0px;right:0px;')
					, we_html_element::htmlIFrame('wizbody', HTML_DIR . "white.html", 'position:absolute;top:0px;bottom:40px;left:0px;right:0px;overflow: auto') .
					we_html_element::htmlIFrame('wizbusy', HTML_DIR . "white.html", 'position:absolute;height:40px;bottom:0px;left:0px;right:0px;overflow: hidden;') .
					we_html_element::htmlIFrame('wizcmd', $this->path . "?pnt=wizcmd", 'position:absolute;bottom:0px;height:0px;left:0px;right:0px;overflow: hidden;')
		));


		$addJS = (defined("OBJECT_TABLE")) ?
			"			self.wizbody.document.forms['we_form'].elements['v[import_type]'][0].checked=true;" : "";
		$weSessionId = session_id();


		$ajaxJS = <<<HTS
var ajaxUrl = "/webEdition/rpc/rpc.php";

var weGetCategoriesHandleSuccess = function(o){
	if(o.responseText !== undefined){
		var json = eval('('+o.responseText+')');

		for(var elemNr in json.elemsById){
			for(var propNr in json.elemsById[elemNr].props){
				var propval = json.elemsById[elemNr].props[propNr].val;
				propval = propval.replace(/\\\'/g,"'");
				propval = propval.replace(/'/g,"\\\'");
				var eId = json.elemsById[elemNr].elemId;
				eval("self.wizbody.document.getElementById(json.elemsById["+elemNr+"].elemId)."+json.elemsById[elemNr].props[propNr].prop+"='"+propval+"'");
			}
		}
	}
}

var weGetCategoriesHandleFailure = function(o){
	alert("failure");
}

var weGetCategoriesCallback = {
	success: weGetCategoriesHandleSuccess,
	failure: weGetCategoriesHandleFailure,
	scope: self.frame,
	timeout: 1500
};

function weGetCategories(obj,cats,part,target) {
	ajaxData = 'protocol=json&cmd=GetCategory&weSessionId={$weSessionId}&obj='+obj+'&cats='+cats+'&part='+part+'&targetId=docCatTable&catfield=v['+obj+'Categories]';
	_executeAjaxRequest('POST',ajaxUrl, weGetCategoriesCallback, ajaxData);
}

function _executeAjaxRequest(method, aUrl, callback, ajaxData){
	return YAHOO.util.Connect.asyncRequest(method, aUrl, callback, ajaxData);
}

HTS;

		return we_html_element::htmlDocType() . we_html_element::htmlHtml(
				we_html_element::htmlHead(
					we_html_tools::getHtmlInnerHead(g_l('import', "[title]")) .
					we_html_element::jsScript(JS_DIR . 'windows.js') .
					we_html_element::jsScript(JS_DIR . 'we_showMessage.js') .
					we_html_element::jsScript(JS_DIR . 'libs/yui/yahoo-min.js') .
					we_html_element::jsScript(JS_DIR . 'libs/yui/event-min.js') .
					we_html_element::jsScript(JS_DIR . 'libs/yui/json-min.js') .
					we_html_element::jsScript(JS_DIR . 'libs/yui/connection-min.js') .
					we_html_element::jsElement("
function wiz_next(frm, url) {
	eval('window.'+frm+'.location.href=\"'+url+'\"');
}
function we_cmd() {
	var args = '';
	var url = '" . WEBEDITION_DIR . "we_cmd.php?';
	for(var i = 0; i < arguments.length; i++) {
		url += 'we_cmd['+i+']='+escape(arguments[i]);
		if(i < (arguments.length - 1)) {
			url += '&';
		}
	}
	switch (arguments[0]) {
		case 'openDirselector':
		case 'openDocselector':
			new jsWindow(url,'we_fileselector',-1,-1," . we_fileselector::WINDOW_DOCSELECTOR_WIDTH . "," . we_fileselector::WINDOW_DOCSELECTOR_HEIGHT . ",true,true,true);
			break;
		case 'browse_server':
			new jsWindow(url,'browse_server',-1,-1,840,400,true,false,true);
			break;
		case 'openCatselector':
			new jsWindow(url,'we_catselector',-1,-1," . we_fileselector::WINDOW_CATSELECTOR_WIDTH . "," . we_fileselector::WINDOW_CATSELECTOR_HEIGHT . ",true,true,true);
			break;
		case 'add_docCat':" . $addJS . "
			if(self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value.indexOf(','+arguments[1]+',') == -1) {
				var cats = arguments[1].split(/,/);
				for(var i=0; i<cats.length; i++) {
					if(cats[i] && (self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value.indexOf(','+cats[i]+',') == -1)) {
						if(self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value) {
							self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value=self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value+cats[i]+',';
						} else {
							self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value=','+cats[i]+',';
						}
						setTimeout(\"weGetCategories('doc',self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value,'rows')\",100);
					}
				}
			}
			break;
		case 'delete_docCat':
			if(self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value.indexOf(','+arguments[1]+',') != -1) {
				if(self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value) {
					re = new RegExp(','+arguments[1]+',');
					self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value = self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value.replace(re,',');
					if(self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value == ',') {
						self.wizbody.document.forms['we_form'].elements['v[docCategories]'].value = '';
					}
				}
				self.wizbody.we_submit_form(self.wizbody.document.forms['we_form'], 'wizbody', '" . $this->path . "');
			}
			break;
		case 'add_objCat':
			self.wizbody.document.forms['we_form'].elements['v[import_type]'][1].checked=true;
			if(self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value.indexOf(','+arguments[1]+',') == -1) {
				var cats = arguments[1].split(/,/);
				for(var i=0; i<cats.length; i++) {
					if(cats[i] && (self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value.indexOf(','+cats[i]+',') == -1)) {
						if(self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value) {
							self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value=self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value+cats[i]+',';
						} else {
							self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value=','+cats[i]+',';
						}
						setTimeout(\"weGetCategories('obj',self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value,'rows')\",100);
					}
				}
			}
			break;
		case 'delete_objCat':
			if(self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value.indexOf(','+arguments[1]+',') != -1) {
				if(self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value) {
					re = new RegExp(','+arguments[1]+',');
					self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value = self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value.replace(re,',');
					if(self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value == ',') {
						self.wizbody.document.forms['we_form'].elements['v[objCategories]'].value = '';
					}
				}
				self.wizbody.we_submit_form(self.wizbody.document.forms['we_form'], 'wizbody', '" . $this->path . "');
			}
			break;
		case 'reload_editpage':
			break;
		default:
			for (var i=0; i < arguments.length; i++) {
				args += 'arguments['+i+']' + ((i < (arguments.length-1))? ',' : '');
			}
			eval('top.opener.top.we_cmd('+args+')');
	}
}" . $ajaxJS
					) . STYLESHEET) .
				$body
		);
	}

	function getWizBody($type = '', $step = 0, $mode = 0){
		$a = array(
			'name' => 'we_form'
		);
		if($type == 'GXMLImport' && $step == 1){
			$a["onsubmit"] = 'return false;';
		}
		if($step == 1){
			$a["enctype"] = 'multipart/form-data';
		}
		$_step = 'get' . $type . 'Step' . $step;
		list($js, $content) = $this->$_step();
		$doOnLoad = isset($_REQUEST['noload']) ? false : true;
		return we_html_element::htmlDocType() . we_html_element::htmlHtml(
				we_html_element::htmlHead(
					STYLESHEET .
					we_html_element::jsScript(JS_DIR . "windows.js") .
					we_html_element::jsElement($js)) .
				we_html_element::htmlBody(array(
					"class" => "weDialogBody",
					"onload" => $doOnLoad ? "parent.wiz_next('wizbusy', '" . $this->path . "?pnt=wizbusy&mode=" . $mode . "&type=" . (isset($_REQUEST['type']) ? $_REQUEST['type'] : '') . "'); self.focus();" : "if(set_button_state) set_button_state();"
					), we_html_element::htmlForm($a, we_html_element::htmlHidden(array("name" => "pnt", "value" => "wizbody")) .
						we_html_element::htmlHidden(array("name" => "type", "value" => $type)) .
						we_html_element::htmlHidden(array("name" => "v[type]", "value" => $type)) .
						we_html_element::htmlHidden(array("name" => "step", "value" => $step)) .
						we_html_element::htmlHidden(array("name" => "mode", "value" => $mode)) .
						we_html_element::htmlHidden(array("name" => "button_state", "value" => 0)) .
						$content
					)
				)
		);
	}

	function getWizBusy(){
		$pb = $js = "";
		if(isset($_REQUEST["mode"]) && $_REQUEST["mode"] == 1){
			$WE_PB = new we_progressBar(0, 0, true);
			$WE_PB->setStudLen(200);
			$WE_PB->addText($text = g_l('import', "[import_progress]"), 0, "pb1");
			$pb = $WE_PB->getJSCode() .
				we_html_element::htmlDiv(array('id' => 'progress'), $WE_PB->getHTML());
			$js = we_html_element::jsElement('
function finish(rebuild) {
	var std = top.wizbusy.document.getElementById("standardDiv");
	if(typeof(std)!="undefined"){
		std.style.display = "none";
	}
	var cls = top.wizbusy.document.getElementById("closeDiv");
	if(typeof( cls)!="undefined"){
		 cls.style.display = "block";
	}
	if(rebuild) {
		top.opener.top.openWindow("' . WEBEDITION_DIR . 'we_cmd.php?we_cmd[0]=rebuild&step=2&btype=rebuild_all&responseText=' . g_l('import', '[finished_success]') . '","rebuildwin",-1,-1,600,130,0,true);
	}
}

top.wizcmd.cycle();
top.wizcmd.we_import(1,-2' . ((isset($_REQUEST['type']) && $_REQUEST['type'] == 'WXMLImport') ? ',1' : '') . ');'
			);
		}

		$cancelButton = we_button::create_button("cancel", "javascript:parent.wizbody.handle_event('cancel');", false, -1, -1, '', '', false, false);
		$prevButton = we_button::create_button("back", "javascript:parent.wizbody.handle_event('previous');", true, -1, -1, "", "", true, false);
		$nextButton = we_button::create_button("next", "javascript:parent.wizbody.handle_event('next');", true, -1, -1, "", "", false, false);
		$closeButton = we_button::create_button("close", "javascript:parent.wizbody.handle_event('cancel');", true, -1, -1, "", "", false, false);

		$prevNextButtons = $prevButton ? we_button::create_button_table(array($prevButton, $nextButton)) : null;

		$content = new we_html_table(array("border" => 0, "cellpadding" => 0, "cellspacing" => 0, "width" => "100%"), 1, 2);
		$content->setCol(0, 0, null, $pb);
		$content->setCol(0, 1, array("align" => "right"), '
<div id="standardDiv">' . we_button::position_yes_no_cancel($prevNextButtons, null, $cancelButton, 10, "", array(), 10) . '</div>
<div id="closeDiv" style="display:none;">' . $closeButton . '</div>'
		);

		print we_html_element::htmlDocType() . we_html_element::htmlHtml(
				we_html_element::htmlHead(
					STYLESHEET .
					we_button::create_state_changer()) .
				we_html_element::htmlBody(array(
					"class" => "weDialogButtonsBody",
					"onload" => "top.wizbody.set_button_state();",
					'style' => 'overflow:hidden;'
					), $content->getHtml() . $js
				)
		);
	}

	function getWizCmd($type = 'normal'){
		$out = '';
		$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
		if($mode == ''){
			$mode = 0;
		}
		/* 		$numFiles = isset($_REQUEST['numFiles']) ? $_REQUEST['numFiles'] : -1;
		  $uniquePath = isset($_REQUEST['uniquePath']) ? $_REQUEST['uniquePath'] : '';
		  $currFileId = isset($_REQUEST['currFileId']) ? $_REQUEST['currFileId'] : -1;
		 */
		if(isset($_REQUEST['v'])){
			$v = $_REQUEST['v'];
			$v["import_ChangeEncoding"] = isset($v["import_ChangeEncoding"]) ? $v["import_ChangeEncoding"] : 0;
			$v["import_XMLencoding"] = isset($v["import_XMLencoding"]) ? $v["import_XMLencoding"] : '';
			$v["import_TARGETencoding"] = isset($v["import_TARGETencoding"]) ? $v["import_TARGETencoding"] : '';
		}

		if(isset($v["mode"]) && $v["mode"] == 1){
			$records = isset($_REQUEST["records"]) ? $_REQUEST["records"] : array();
			$we_flds = isset($_REQUEST["we_flds"]) ? $_REQUEST["we_flds"] : array();
			$attrs = isset($_REQUEST['attrs']) ? $_REQUEST['attrs'] : array();
			$attributes = isset($_REQUEST['attributes']) ? $_REQUEST['attributes'] : array();

			switch($v['cid']){
				case -2:
					$h = $this->getHdns('v', $v);
					if($v["type"] != "" && $v["type"] != "WXMLImport"){
						$h.=$this->getHdns("records", $records) .
							$this->getHdns("we_flds", $we_flds);
					}
					if($v["type"] == "GXMLImport"){
						$h.=$this->getHdns("attributes", $attributes) .
							$this->getHdns("attrs", $attrs);
					}

					$JScript = ($type == "first_steps_wizard" ? "
top.leWizardProgress.set(0);
top.leWizardProgress.show()
top.weButton.disable('next')
top.weButton.disable('back')
top.weButton.enable('reload')
function we_import_handler(e) { we_import(1,-2); }
top.document.getElementById('function_reload').onmouseup = we_import_handler;" :
							'top.wizbusy.setProgressText("pb1","' . g_l('import', "[prepare_progress]") . '");'
						);


					$out .= we_html_element::htmlForm(array("name" => "we_form"), $h) .
						we_html_element::jsElement($JScript . 'setTimeout("we_import(1,-1);",15);');
					break;

				case -1:
					switch($v["type"]){
						case 'WXMLImport':

							if($type != "first_steps_wizard"){
								print we_html_element::jsElement('
if (top.wizbody && top.wizbody.addLog){
	top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(10, 10)) . '<br>");
	top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(10, 10)) . we_html_element::htmlB(g_l('import', '[start_import]') . ' - ' . date("d.m.Y H:i:s")) . '<br><br>");
	top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(20, 5)) . we_html_element::htmlB(g_l('import', '[prepare]')) . '<br>");
	top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(20, 5)) . we_html_element::htmlB(g_l('import', '[import]')) . '<br>");
}');
								flush();
							}

							$path = TEMP_PATH . weFile::getUniqueId() . '/';
							we_util_File::createLocalFolder($path);

							if(is_dir($path)){
								$num_files = weXMLImport::splitFile($_SERVER['DOCUMENT_ROOT'] . $v['import_from'], $path, 1);
								++$num_files;
							}
							break;
						case 'GXMLImport':
							$parse = new XML_SplitFile($_SERVER['DOCUMENT_ROOT'] . $v["import_from"]);
							$parse->splitFile(($v["type"] == "GXMLImport") ? "*/" . $v["rcd"] : "*/child::*", (isset($v["from_elem"])) ? $v["from_elem"] : FALSE, (isset($v["to_elem"])) ? $v["to_elem"] : FALSE, 1);
							break;
						case 'CSVImport':
							switch($v['csv_enclosed']){
								case 'double_quote':
									$encl = '"';
									break;
								case 'single_quote':
									$encl = "'";
									break;
								case 'none':
									$encl = '';
									break;
							}
							$cp = new we_import_CSV;
							$cp->setFile($_SERVER['DOCUMENT_ROOT'] . $v['import_from']);
							$del = ($v['csv_seperator'] != "\\t") ? (($v['csv_seperator'] != '') ? $v['csv_seperator'] : ' ') : '	';
							$cp->setDelim($del);
							$cp->setEnclosure($encl);
							$cp->parseCSV();
							$num_files = 0;
							$unique_id = weFile::getUniqueId(); // #6590, changed from: uniqid(microtime())

							$path = TEMP_PATH . $unique_id;
							we_util_File::createLocalFolder($path);

							if($cp->isOK()){
								$fieldnames = ($v['csv_fieldnames']) ? 0 : 1;
								$num_rows = $cp->CSVNumRows();
								$num_fields = $cp->CSVNumFields();

								for($i = 0; $i < $num_rows + $fieldnames; $i++){
									$d[0] = $d[1] = '';
									for($j = 0; $j < $num_fields; $j++){
										$d[1] .= (!$fieldnames ?
												(($cp->CSVFieldName($j) != "") ?
													$encl . str_replace($encl, "\\" . $encl, $cp->CSVFieldName($j)) . $encl :
													'') :
												$encl . 'f_' . $j . $encl);
										$d[0] .= ($fieldnames && $i == 0) ?
											(($cp->CSVFieldName($j) != '') ? $encl . str_replace($encl, "\\" . $encl, $cp->CSVFieldName($j)) . $encl : "") :
											(($cp->Fields[(!$fieldnames) ? $i : ($i - 1)][$j] != "") ?
												$encl . str_replace($encl, "\\" . $encl, $cp->Fields[(!$fieldnames) ? $i : ($i - 1)][$j]) . $encl : "");
										if($j + 1 < $num_fields){
											$d[1] .= $del;
											$d[0] .= $del;
										}
									}
									weFile::save($path . '/temp_' . $i . '.csv', implode("\n", $d), 'wb');
									$num_files++;
								}
							}
							break;
					}

					$h = $this->getHdns("v", $v);
					if($v["type"] != "WXMLImport"){
						$h.=$this->getHdns("records", $records) . $this->getHdns("we_flds", $we_flds);
					}
					if($v["type"] == "GXMLImport"){
						$h.=$this->getHdns("attributes", $attributes) . $this->getHdns("attrs", $attrs);
					}
					$h .= we_html_element::htmlHidden(array("name" => "v[numFiles]", "value" => ($v["type"] != "GXMLImport") ? $num_files : $parse->fileId)) .
						we_html_element::htmlHidden(array("name" => "v[uniquePath]", "value" => ($v["type"] != "GXMLImport") ? $path : $parse->path));

					$out .= we_html_element::htmlForm(array("name" => "we_form"), $h) . we_html_element::jsElement(
							"setTimeout(\"we_import(1,0);\",15);");
					break;

				case $v['numFiles']:
					$out.=self::importFinished($v, $type);
					break;
				default:
					$fields = array();
					if($v["type"] == "WXMLImport"){

						$hiddens = $this->getHdns("v", $v);

						if(intval($v['cid']) == 0){
							// clear session data
							weXMLExIm::unsetPerserves();
						}

						$ref = false;
						if($v["cid"] >= $v["numFiles"] - 1){ // finish import
							$xmlExIm = new weImportUpdater();
							$xmlExIm->loadPerserves();
							$xmlExIm->setOptions(array(
								'handle_documents' => $v['import_docs'],
								'handle_templates' => $v['import_templ'],
								'handle_objects' => isset($v['import_objs']) ? $v['import_objs'] : 0,
								'handle_classes' => isset($v['import_classes']) ? $v['import_classes'] : 0,
								'handle_doctypes' => $v['import_dt'],
								'handle_categorys' => $v['import_ct'],
								'handle_binarys' => $v['import_binarys'],
								'document_path' => $v['doc_dir_id'],
								'template_path' => $v['tpl_dir_id'],
								'handle_collision' => $v['collision'],
								'restore_doc_path' => $v['restore_doc_path'],
								'restore_tpl_path' => $v['restore_tpl_path'],
								'handle_owners' => $v['import_owners'],
								'owners_overwrite' => $v['owners_overwrite'],
								'owners_overwrite_id' => $v['owners_overwrite_id'],
								'handle_navigation' => $v['import_navigation'],
								'navigation_path' => $v['navigation_dir_id'],
								'handle_thumbnails' => $v['import_thumbnails'],
								'change_encoding' => $v['import_ChangeEncoding'],
								'xml_encoding' => $v['import_XMLencoding'],
								'target_encoding' => $v['import_TARGETencoding'],
								'rebuild' => $v['rebuild']
							));

							if($xmlExIm->RefTable->current == 0){
								if($type != "first_steps_wizard"){
									print we_html_element::jsElement('
										if (top.wizbody.addLog){
											top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(20, 5)) . we_html_element::htmlB(g_l('import', '[update_links]')) . '");
										}');
									flush();
								}
							}

							$ref = null;

							for($i = 0; $i < $xmlExIm->UpdateItemsCount; $i++){
								$ref = $xmlExIm->RefTable->getNext();
								if(!empty($ref)){
									if(isset($ref->ContentType) && isset($ref->ID)){
										$doc = weContentProvider::getInstance($ref->ContentType, $ref->ID, $ref->Table);
									}
									$xmlExIm->updateObject($doc);
								} else {
									break;
								}
							}

							if(!empty($ref)){
								$xmlExIm->savePerserves(false);

								$JScript = ($type == "first_steps_wizard" ?
										"top.leWizardProgress.set(Math.floor(((" . (int) ($v['cid'] + $xmlExIm->RefTable->current) . "+1)/" . (int) ($xmlExIm->RefTable->getLastCount() + $v["numFiles"]) . ")*100));
function we_import_handler(e) {
	we_import(1," . ($v['cid'] - 1) . ");
}
top.document.getElementById('function_reload').onmouseup = we_import_handler;" :
										"top.wizbusy.setProgressText('pb1','" . g_l('import', '[update_links]') . $xmlExIm->RefTable->current . '/' . count($xmlExIm->RefTable->Storage) . "');
										top.wizbusy.setProgress(Math.floor(((" . (int) ($v['cid'] + $xmlExIm->RefTable->current) . "+1)/" . (int) ($xmlExIm->RefTable->getLastCount() + $v["numFiles"]) . ")*100));"
									);


								$out .= we_html_element::htmlForm(array("name" => "we_form"), $hiddens .
										we_html_element::jsElement($JScript . "setTimeout('we_import(1," . $v['cid'] . ");',15);"));
							} else {
								if($type == "first_steps_wizard"){
									$_SESSION['weS']['fsw_importRefTable'] = isset($_SESSION['weS']['ExImRefTable']) ? $_SESSION['weS']['ExImRefTable'] : array();

									$JScript = "
function we_import_handler(e) {
	we_import(1," . ($v['numFiles'] - 1) . ");
}
top.document.getElementById('function_reload').onmouseup = we_import_handler;
setTimeout('we_import(1," . $v['numFiles'] . ");',15);";
								} else {

									$JScript = "
top.wizbusy.finish(" . $xmlExIm->options['rebuild'] . ");
setTimeout('we_import(1," . $v['numFiles'] . ");',15);";
								}
								$out .= we_html_element::htmlForm(array("name" => "we_form"), $hiddens . we_html_element::jsElement($JScript));

								$xmlExIm->unsetPerserves();
							}
						} else { // do import
							$xmlExIm = new weXMLImport();
							$chunk = $v["uniquePath"] . basename($v["import_from"]) . "_" . $v["cid"];
							if(file_exists($chunk)){
								$xmlExIm->loadPerserves();
								$xmlExIm->setOptions(array(
									'handle_documents' => $v['import_docs'],
									'handle_templates' => $v['import_templ'],
									'handle_objects' => isset($v['import_objs']) ? $v['import_objs'] : 0,
									'handle_classes' => isset($v['import_classes']) ? $v['import_classes'] : 0,
									'handle_doctypes' => $v['import_dt'],
									'handle_categorys' => $v['import_ct'],
									'handle_binarys' => $v['import_binarys'],
									'document_path' => $v['doc_dir_id'],
									'template_path' => $v['tpl_dir_id'],
									'handle_collision' => $v['collision'],
									'restore_doc_path' => $v['restore_doc_path'],
									'restore_tpl_path' => $v['restore_tpl_path'],
									'handle_owners' => $v['import_owners'],
									'owners_overwrite' => $v['owners_overwrite'],
									'owners_overwrite_id' => $v['owners_overwrite_id'],
									'handle_navigation' => $v['import_navigation'],
									'navigation_path' => $v['navigation_dir_id'],
									'handle_thumbnails' => $v['import_thumbnails'],
									'change_encoding' => $v['import_ChangeEncoding'],
									'xml_encoding' => $v['import_XMLencoding'],
									'target_encoding' => $v['import_TARGETencoding'],
									'rebuild' => $v['rebuild']
								));
								$imported = $xmlExIm->import($chunk);
								$xmlExIm->savePerserves(false);
								if($imported){
									$ref = $xmlExIm->RefTable->getLast();

									$_status = g_l('import', '[import]');

									switch($ref->ContentType){
										case 'weBinary':
										case 'category':
										case 'objectFile':
											$_path_info = $ref->Path;
											break;
										case 'doctype':
											$_path_info = f('SELECT DocType FROM ' . escape_sql_query($ref->Table) . ' WHERE ID = ' . intval($ref->ID), 'DocType', new DB_WE());
											break;
										case 'weNavigationRule':
											$_path_info = f('SELECT NavigationName FROM ' . escape_sql_query($ref->Table) . ' WHERE ID = ' . intval($ref->ID), 'NavigationName', new DB_WE());
											break;
										case 'weThumbnail':
											$_path_info = f('SELECT Name FROM ' . escape_sql_query($ref->Table) . ' WHERE ID = ' . intval($ref->ID), 'Name', new DB_WE());
											break;
										default:
											$_path_info = id_to_path($ref->ID, $ref->Table);
											break;
									}
									$_progress_text = we_html_element::htmlB(
											g_l('contentTypes', '[' . $ref->ContentType . ']', true) != '' ?
												g_l('contentTypes', '[' . $ref->ContentType . ']') :
												(g_l('import', '[' . $ref->ContentType . ']', true) != '' ?
													g_l('import', '[' . $ref->ContentType . ']') : ''
												)
										) . '&nbsp;&nbsp;' . $_path_info;

									if(strlen($_progress_text) > 75){
										$_progress_text = addslashes(substr($_progress_text, 0, 65) . '<acronym title="' . $_path_info . '">...</acronym>' . substr($_progress_text, -10));
									}

									if($type != "first_steps_wizard"){
										print we_html_element::jsElement(
												'if (top.wizbody.addLog){
												top.wizbody.addLog("' . addslashes(we_html_tools::getPixel(50, 5)) . $_progress_text . '<br>");
											}');
										flush();
									}
								} else {
									$_status = g_l('import', '[skip]');
								}

								$_counter_text = g_l('import', '[item]') . ' ' . $v['cid'] . '/' . ($v['numFiles'] - 2) . '';

								if($type == 'first_steps_wizard'){
									$JScript = "
top.leWizardProgress.set(Math.floor(((" . $v['cid'] . "+1)/" . (int) (2 * $v["numFiles"]) . ")*100));
function we_import_handler(e) {
	we_import(1," . $v["cid"] . ");
}
top.document.getElementById('function_reload').onmouseup = we_import_handler;";
								} else {
									$JScript = "
top.wizbusy.setProgressText('pb1','" . $_status . " - " . $_counter_text . "');
top.wizbusy.setProgress(Math.floor(((" . $v['cid'] . "+1)/" . (int) (2 * $v["numFiles"]) . ")*100));";
								}

								$out .= we_html_element::htmlForm(array("name" => "we_form"), $hiddens .
										we_html_element::jsElement($JScript . "setTimeout('we_import(1," . ($v["cid"] + 1) . ");',15);"));
							}
						}
						break;
					} else if($v['type'] == 'GXMLImport'){
						$hiddens = $this->getHdns('v', $v) . $this->getHdns('records', $records) . $this->getHdns("we_flds", $we_flds) . $this->getHdns("attributes", $attributes);
						$xp = new we_xml_parser($v['uniquePath'] . '/temp_' . $v['cid'] . '.xml');

						foreach($records as $record){
							$nodeSet = $xp->evaluate($xp->root . '/' . $we_flds[$record]);
							$xPath = '';
							$loop = 0;
							$firstNode = '';
							foreach($nodeSet as $node){
								if($loop == 0){
									$firstNode = $node;
									$loop++;
								}
								$list = $xp->getAttributes($node);
								$flag = true;
								$decAttrs = we_tag_tagParser::makeArrayFromAttribs(base64_decode($attributes[$record]));
								foreach($decAttrs as $key => $value){
									if(!isset($list[$key]) || $list[$key] != $value){
										$flag = false;
									}
								}
								if($flag){
									$xPath = $node;
									break;
								}
							}
							if($xPath == ''){
								$xPath = $firstNode;
							}
							$fields = $fields + array($record => $xp->getData($xPath));
						}
						if($v['pfx_fn'] == 1){
							$v['rcd_pfx'] = $xp->getData($xp->root . '/' . $v["rcd_pfx"] . "[1]");
							if($v['rcd_pfx'] == ''){
								$v['rcd_pfx'] = g_l('import', ($v['import_type'] == 'documents' ? '[pfx_doc]' : '[pfx_obj]'));
							}
						}
					} else if($v["type"] == "CSVImport"){
						$hiddens = $this->getHdns("v", $v) . $this->getHdns("records", $records) . $this->getHdns("we_flds", $we_flds);
						switch($v["csv_enclosed"]){
							case 'double_quote':
								$encl = '"';
								break;
							case 'single_quote':
								$encl = "'";
								break;
							case 'none':
								$encl = '';
								break;
						}
						$cp = new we_import_CSV;
						$cp->setFile($v['uniquePath'] . '/temp_' . $v["cid"] . ".csv");
						$cp->setDelim($v['csv_seperator']);
						$cp->setEnclosure($encl);
						$cp->parseCSV();
						$recs = array();
						$names = array();
						for($i = 0; $i < $cp->CSVNumFields(); $i++){
							$names[$i] = $cp->CSVFieldName($i);
							$recs[$names[$i]] = $cp->Fields[0][$i];
						}
						foreach($we_flds as $name => $value){
							$fields[$name] = (isset($recs[$value]) ? $recs[$value] : '');
						}
						if($v['pfx_fn'] == 1){
							$v['rcd_pfx'] = $recs[$v['rcd_pfx']];

							if($v['rcd_pfx'] == ''){
								$v['rcd_pfx'] = g_l('import', ($v['import_type'] == 'documents' ? '[pfx_doc]' : '[pfx_obj]'));
							}
						}
					}

					if($v['type'] != 'WXMLImport'){
						if(isset($v["dateFields"])){
							$dateFields = makeArrayFromCSV($v["dateFields"]);
							if(($v["sTimeStamp"] == "Format" && $v["timestamp"] != "") || ($v["sTimeStamp"] == "GMT")){
								foreach($dateFields as $dateField){
									$fields[$dateField] = importFunctions::date2Timestamp($fields[$dateField], ($v["sTimeStamp"] != "GMT") ? $v["timestamp"] : "");
								}
							}
						}

						$rcd_name = ($v["pfx_fn"] == 1) ? $v["rcd_pfx"] : $v["asoc_prefix"];
						if($v["import_type"] == "documents"){
							$_isSelectable = f('SELECT IsSearchable FROM ' . DOC_TYPES_TABLE . ' WHERE ID = ' . intval($v["docType"]), 'IsSearchable', new DB_WE());
							importFunctions::importDocument($v["store_to_id"], $v["we_TemplateID"], $fields, $v["docType"], $v["docCategories"], $rcd_name, $v["is_dynamic"], $v["we_Extension"], true, $_isSelectable, $v['collision']);
						} else if($v["import_type"] == "objects"){
							importFunctions::importObject($v["classID"], $fields, $v["objCategories"], $rcd_name, true, $v['collision']);
						}
					}


					if($type == 'first_steps_wizard'){
						$JScript = "
top.leWizardProgress.set(Math.floor(((" . $v["cid"] . "+1)/" . $v["numFiles"] . ")*100));
function we_import_handler(e) {
	we_import(1," . $v["cid"] . ");
}
top.document.getElementById('function_reload').onmouseup = we_import_handler;";
					} else {
						$JScript = "
top.wizbusy.setProgressText('pb1','" . g_l('import', "[import]") . "');
top.wizbusy.setProgress(Math.floor(((" . $v["cid"] . "+1)/" . $v["numFiles"] . ")*100));";
					}

					$out .= we_html_element::htmlForm(array("name" => "we_form"), $hiddens .
							we_html_element::jsElement($JScript . "setTimeout('we_import(1," . ($v["cid"] + 1) . ");',15);"));
					break;
			} // end switch
		} else if($mode != 1){
			$out .= we_html_element::htmlForm(array("name" => "we_form"), we_html_element::htmlHidden(array("name" => "v[mode]", "value" => "")) .
					we_html_element::htmlHidden(array("name" => "v[cid]", "value" => "")) .
					we_html_element::htmlHidden(array("name" => "mode", "value" => "")) .
					we_html_element::htmlHidden(array("name" => "type", "value" => "")) .
					we_html_element::htmlHidden(array("name" => "cid", "value" => "")));
		}

		return we_html_element::htmlDocType() . we_html_element::htmlHtml(
				we_html_element::htmlHead(
					we_html_element::jsElement(
						"function addField(form, fieldType, fieldName, fieldValue) {
							if (document.getElementById) {
								var input = document.createElement('INPUT');
								if (document.all) {
									input.type = fieldType;
									input.name = fieldName;
									input.value = fieldValue;
								}
								else if (document.getElementById) {
									input.setAttribute('type', fieldType);
									input.setAttribute('name', fieldName);
									input.setAttribute('value', fieldValue);
								}
								form.appendChild(input);
							}
						}
						function getField(form, fieldName) {
							if (!document.all){
								return form[fieldName];
							}else{
								for (var e = 0; e < form.elements.length; e++){
									if (form.elements[e].name == fieldName){
										return form.elements[e];
									}
								}
							}
							return null;
						}
						function removeField(form, fieldName) {
							var field = getField (form, fieldName);
							if (field && !field.length){
								field.parentNode.removeChild(field);
							}
						}
						function toggleField (form, fieldName, value) {
							var field = getField (form, fieldName);
							if (field){
								removeField (form, fieldName);
							}else{
								addField (form, 'hidden', fieldName, value);
							}
						}
						function cycle() {
							var test = '';
							var cf = self.document.forms['we_form'];
							var bf = top.wizbody.document.forms['we_form'];
							for (var i = 0; i < bf.elements.length; i++) {
								if ((bf.elements[i].name.indexOf('v') > -1) || (bf.elements[i].name.indexOf('records') > -1) ||
									(bf.elements[i].name.indexOf('we_flds') > -1) || (bf.elements[i].name.indexOf('attributes') > -1)) {
									addField(cf, 'hidden', bf.elements[i].name, bf.elements[i].value);
								}
							}
						}
						function we_import(mode, cid) {
							if(arguments[2]==1){
								top.wizbody.location = '" . $this->path . "?pnt=wizbody&step=3&type=WXMLImport&noload=1';
							};
							var we_form = self.document.forms['we_form'];
							we_form.elements['v[mode]'].value = mode;
							we_form.elements['v[cid]'].value = cid;
							we_form.target = '" . ($type == "first_steps_wizard" ? "_self" : "wizcmd" ) . "';
							we_form.action = '" . ($type == "first_steps_wizard" ? $_SERVER['SCRIPT_NAME'] . "?leWizard=" . $_REQUEST['leWizard'] . "&leStep=" . $_REQUEST['leStep'] . "&we_cmd[0]=" . $_REQUEST['we_cmd'][0] : $this->path . "?pnt=wizcmd" ) . "';
							we_form.method = 'post';
							we_form.submit();
						}"
				)) .
				we_html_element::htmlBody(array('style' => 'overflow:hidden;'), $out));
	}

	private function importFinished($v, $type){
		switch($type){
			case 'first_steps_wizard':
				$JScript = "top.leWizardProgress.set(100);
							top.leWizardProgress.hide();
							top.weButton.enable('next');
							top.opener.top.we_cmd('load', top.opener.top.treeData.table ,0);" .
					//. "top.opener.top.header.location.reload();\n"
					"function we_import_handler(e) { we_import(1," . $v["numFiles"] . "); }
							top.document.getElementById('function_reload').onmouseup = we_import_handler;";
				break;
			default:
				$JScript = "top.wizbusy.setProgressText('pb1','" . g_l('import', '[finish_progress]') . "');
							top.wizbusy.setProgress(100);
							top.opener.top.we_cmd('load', top.opener.top.treeData.table ,0);" .
					//. "top.opener.top.header.location.reload();\n"
					"if(top.opener.top.top.weEditorFrameController.getActiveDocumentReference().quickstart && typeof(top.opener.top.weEditorFrameController.getActiveDocumentReference().quickstart) != 'undefined') top.opener.top.weEditorFrameController.getActiveDocumentReference().location.reload();
							if(top.wizbusy && top.wizbusy.document.getElementById('progress')) {
							progress = top.wizbusy.document.getElementById('progress');
							if(typeof(progress)!='undefined'){
									progress.style.display = 'none';
								}
							}" .
					($v['type'] == 'WXMLImport' ?
						"if (top.wizbody && top.wizbody.addLog) {
								top.wizbody.addLog(\"<br>" . addslashes(we_html_tools::getPixel(10, 10)) . we_html_element::htmlB(g_l('import', '[end_import]') . " - " . date("d.m.Y H:i:s")) . "<br><br>\");
								}" :
						we_message_reporting::getShowMessageCall(g_l('import', '[finish_import]'), we_message_reporting::WE_MESSAGE_NOTICE) . 'setTimeout("top.close()",100);'
					);
		}

		return we_html_element::jsElement($JScript);
	}

}
