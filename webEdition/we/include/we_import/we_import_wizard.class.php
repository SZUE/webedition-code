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
class we_import_wizard{
	var $TemplateID = 0;
	public $fileUploader = null;

	public function getHTML($what, $type, $step, $mode){
		switch($what){
			case "wizframeset":
				return $this->getWizFrameset();
			case "wizbody":
				return $this->getWizBody($type, $step, $mode);
			case "wizbusy":
				return $this->getWizBusy();
			case "wizcmd":
				return $this->getWizCmd();
		}
	}

	private function getWizFrameset(){
		$args = 'pnt=wizbody' .
			(($cmd1 = we_base_request::_(we_base_request::STRING, 'we_cmd', false, 1)) ? '&we_cmd[1]=' . $cmd1 : '');

		$body = we_html_element::htmlBody(['id' => 'weMainBody', "onload" => "wiz_next('wizbody', WE().consts.dirs.WEBEDITION_DIR+'we_cmd.php?we_cmd[0]=import&" . $args . "');"]
				, we_html_element::htmlIFrame('wizbody', "about:blank", 'position:absolute;top:0px;bottom:40px;left:0px;right:0px;') .
				we_html_element::htmlIFrame('wizbusy', "about:blank", 'position:absolute;height:40px;bottom:0px;left:0px;right:0px;overflow: hidden;', '', '', false) .
				we_html_element::htmlIFrame('wizcmd', WEBEDITION_DIR . 'we_cmd.php?we_cmd[0]=import&pnt=wizcmd', 'position:absolute;bottom:0px;height:0px;left:0px;right:0px;overflow: hidden;')
		);

		return we_html_tools::getHtmlTop(g_l('import', '[title]'), '', '', we_html_element::jsScript(JS_DIR . 'import_wizardBase.js'), $body);
	}

	private function getWizBody($type = '', $step = 0, $mode = 0){
		// FIXME: probably obsolete
		$continue = true;
		if($this->fileUploader){
//				$continue = $this->fileUploader->processFileRequest();
		}

		if($continue){
			$a = [
				'name' => 'we_form'
			];
			if($type == we_import_functions::TYPE_GENERIC_XML && $step == 1){
				$a["onsubmit"] = 'return false;';
			}
			if($step == 1){
				$a["enctype"] = 'multipart/form-data';
			}
			$jsCmd = new we_base_jsCmd();
			switch($type){
				default:
					$content = $this->getStep0($jsCmd);
					break;
				case we_import_functions::TYPE_GENERIC_XML:
					switch($step){
						default:
						case 1:
							$content = $this->getGXMLImportStep1($jsCmd);
							break;
						case 2:
							$content = $this->getGXMLImportStep2($jsCmd);
							break;
						case 3:
							$content = $this->getGXMLImportStep3($jsCmd);
							break;
					}
					break;
				case we_import_functions::TYPE_CSV:
					switch($step){
						default:
						case 1:
							$content = $this->getCSVImportStep1($jsCmd);
							break;
						case 2:
							$content = $this->getCSVImportStep2($jsCmd);
							break;
						case 3:
							$content = $this->getCSVImportStep3($jsCmd);
							break;
					}
					break;
				case we_import_functions::TYPE_WE_XML:
					switch($step){
						default:
						case 1:
							$content = $this->getWXMLImportStep1($jsCmd);
							break;
						case 2:
							$content = $this->getWXMLImportStep2($jsCmd);
							break;
						case 3:
							$content = $this->getWXMLImportStep3($jsCmd);
							break;
					}
					break;
			}

			$doOnLoad = !we_base_request::_(we_base_request::BOOL, 'noload');
			return we_html_tools::getHtmlTop('', '', '', ($this->fileUploader ? $this->fileUploader->getCss() . $this->fileUploader->getJs() : '') .
					we_html_element::jsScript(JS_DIR . 'import_wizardWizbody.js') .
					$jsCmd->getCmds(), we_html_element::htmlBody(["class" => "weDialogBody",
						"onload" => $doOnLoad ? "parent.wiz_next('wizbusy', WE().consts.dirs.WEBEDITION_DIR+'we_cmd.php?we_cmd[0]=import&pnt=wizbusy&mode=" . $mode . "&type=" . (we_base_request::_(we_base_request::RAW, 'type', '')) . "'); self.focus();" : "if(set_button_state){set_button_state()};"
						], we_html_element::htmlForm($a, we_html_element::htmlHiddens(["pnt" => "wizbody",
								"type" => $type,
								"v[type]" => $type,
								"step" => $step,
								"mode" => $mode,
								"button_state" => 0]) .
							$content
						)
					)
			);
		}
	}

	private function getWizBusy(){
		$jsCmd = new we_base_jsCmd();
		$jsCmd->addCmd('set_button_state');
		$pb = '';

		if(we_base_request::_(we_base_request::INT, "mode") == 1){
			$WE_PB = new we_progressBar(0, 200);
			$WE_PB->addText($text = g_l('import', '[import_progress]'), we_progressBar::TOP, "pb1");
			$pb = we_progressBar::getJSCode() . we_html_element::htmlDiv(['id' => 'progress'], $WE_PB->getHTML());

			// make jsCmd => we need a minimal we_cmd on this frame
			$jsCmd->addCmd('cycle');
			$jsCmd->addCmd('we_import', 1, '-2' . ((we_base_request::_(we_base_request::STRING, 'type') == we_import_functions::TYPE_WE_XML) ? ',1' : ''), false);
		}

		$cancelButton = we_html_button::create_button(we_html_button::CANCEL, "javascript:top.handleEvent('cancel');", '', 0, 0, '', '', false, false);
		$prevButton = we_html_button::create_button(we_html_button::BACK, "javascript:top.handleEvent('previous');", '', 0, 0, "", "", true, false);
		$nextButton = we_html_button::create_button(we_html_button::NEXT, "javascript:top.handleEvent('next');", '', 0, 0, "", "", false, false, '_btn');
		$closeButton = we_html_button::create_button(we_html_button::CLOSE, "javascript:top.handleEvent('cancel');", '', 0, 0, "", "", false, false);

		$prevNextButtons = $prevButton ? $prevButton . $nextButton : null;

		$content = new we_html_table(['class' => 'default', "width" => "100%"], 1, 2);
		$content->setCol(0, 0, null, '');
		$content->setCol(0, 1, ['style' => "text-align:right"],
				we_html_element::htmlDiv(['id' => 'standardDiv'], we_html_button::position_yes_no_cancel($prevNextButtons, null, $cancelButton, 10, "", [], 10)) .
				we_html_element::htmlDiv(['id' => 'closeDiv', 'style' => 'display:none;'], $closeButton)
		);

		echo we_html_tools::getHtmlTop('', '', '', $jsCmd->getCmds() . $pb, we_html_element::htmlBody(["class" => "weDialogButtonsBody",
					'style' => 'overflow:hidden;'
				], $content->getHtml()
			)
		);
	}

	private function xmlExImSetOpt(we_exim_XMLImport $xmlExIm, array $v){
		$xmlExIm->setOptions(['handle_documents' => $v['import_docs'],
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
		]);
	}

	private function getWizCmd($type = 'normal'){
		$jsCmd = new we_base_jsCmd();
		$out = '';
		$mode = we_base_request::_(we_base_request::INT, 'mode', 0);
		$v = we_base_request::_(we_base_request::STRING, 'v');
		$v["import_ChangeEncoding"] = isset($v["import_ChangeEncoding"]) ? $v["import_ChangeEncoding"] : 0;
		$v["import_XMLencoding"] = isset($v["import_XMLencoding"]) ? $v["import_XMLencoding"] : '';
		$v["import_TARGETencoding"] = isset($v["import_TARGETencoding"]) ? $v["import_TARGETencoding"] : '';

		if(isset($v["mode"]) && $v["mode"] == 1){
			$records = we_base_request::_(we_base_request::RAW, "records", []);
			$we_flds = we_base_request::_(we_base_request::RAW, "we_flds", []);
			$attrs = we_base_request::_(we_base_request::RAW, 'attrs', []);
			$attributes = we_base_request::_(we_base_request::RAW, 'attributes', []);

			switch($v['cid']){
				case -2:
					$h = $this->getHdns('v', $v);
					if($v["type"] != "" && $v["type"] != we_import_functions::TYPE_WE_XML){
						$h .= $this->getHdns("records", $records) .
							$this->getHdns("we_flds", $we_flds);
					}
					if($v["type"] == we_import_functions::TYPE_GENERIC_XML){
						$h .= $this->getHdns("attributes", $attributes) .
							$this->getHdns("attrs", $attrs);
					}

					$jsCmd->addCmd('setProgressText_footer', 'pb1', g_l('import', '[prepare_progress]'));
					$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => -1]);

					$out .= we_html_element::htmlForm(['name' => 'we_form'], $h);
					break;

				case -1:
					switch($v["type"]){
						case we_import_functions::TYPE_WE_XML:
							$jsCmd->addCmd('addLog_buffered', [
									we_html_element::htmlB(g_l('import', '[start_import]') . ' - ' . date("d.m.Y H:i:s")),
									we_html_element::htmlB(g_l('import', '[prepare]')),
									we_html_element::htmlB(g_l('import', '[import]'))
								]);

							$path = TEMP_PATH . we_base_file::getUniqueId() . '/';
							we_base_file::createLocalFolderByPath($path);

							if(is_dir($path)){
								$num_files = we_exim_XMLImport::splitFile($_SERVER['DOCUMENT_ROOT'] . $v['import_from'], $path, 1);
								++$num_files;
							}
							break;
						case we_import_functions::TYPE_GENERIC_XML:
							$parse = new we_xml_splitFile($_SERVER['DOCUMENT_ROOT'] . $v["import_from"]);
							$parse->splitFile("*/" . $v["rcd"], (isset($v["from_elem"])) ? $v["from_elem"] : false, (isset($v["to_elem"])) ? $v["to_elem"] : false, 1);
							break;
						case we_import_functions::TYPE_CSV:
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
							$unique_id = we_base_file::getUniqueId(); // #6590, changed from: uniqid(microtime())

							$path = TEMP_PATH . $unique_id;
							we_base_file::createLocalFolderByPath($path);

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
									we_base_file::save($path . '/temp_' . $i . '.csv', implode("\n", $d), 'wb');
									$num_files++;
								}
							}
							break;
					}

					$h = $this->getHdns("v", $v);
					if($v["type"] != we_import_functions::TYPE_WE_XML){
						$h .= $this->getHdns("records", $records) . $this->getHdns("we_flds", $we_flds);
					}
					if($v["type"] == we_import_functions::TYPE_GENERIC_XML){
						$h .= $this->getHdns("attributes", $attributes) . $this->getHdns("attrs", $attrs);
					}
					$h .= we_html_element::htmlHiddens(["v[numFiles]" => ($v["type"] != we_import_functions::TYPE_GENERIC_XML) ? $num_files : $parse->fileId,
							"v[uniquePath]" => ($v["type"] != we_import_functions::TYPE_GENERIC_XML) ? $path : $parse->path]);

					$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => 0]);

					$out .= we_html_element::htmlForm(['name' => 'we_form'], $h);
					break;

				case $v['numFiles']:
					$out .= self::importFinished($v, $type);
					break;
				default:
					$fields = [];
					switch($v["type"]){
						case we_import_functions::TYPE_WE_XML:
							$hiddens = $this->getHdns("v", $v);

							if(intval($v['cid']) == 0){
								// clear session data
								we_exim_XMLExIm::unsetPerserves();
							}

							$ref = false;
							if($v["cid"] >= $v["numFiles"] - 1){ // finish import
								$xmlExIm = new we_import_updater();
								$xmlExIm->loadPerserves();
								$this->xmlExImSetOpt(xmlExImSetOpt, $v);
								if($xmlExIm->RefTable->current == 0){
									$jsCmd->addCmd('addLog_buffered', [we_html_element::htmlB(g_l('import', '[update_links]'))]);
								}

								$ref = null;

								while(($ref = $xmlExIm->RefTable->getNext()) !== null){
									if(isset($ref->ContentType) && isset($ref->ID)){
										$doc = we_exim_contentProvider::getInstance($ref->ContentType, $ref->ID, $ref->Table);
										$xmlExIm->updateObject($doc);
									}
								}

								if($ref){
									$xmlExIm->savePerserves();

									$jsCmd->addCmd('setProgressText_footer', 'pb1', g_l('import', '[update_links]') . $xmlExIm->RefTable->current . '/' . $xmlExIm->RefTable->getCount());
									$jsCmd->addCmd('setProgress_footer', (int) ((($v['cid'] + $xmlExIm->RefTable->current + 1) / ($xmlExIm->RefTable->getCount() + $v["numFiles"])) * 100));
									$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => $v['cid']]);

									$out .= we_html_element::htmlForm(['name' => 'we_form'], $hiddens);
								} else {
									//FIXME: if update needs more steps they must be handled here
									we_updater::doUpdate('internal');

									$jsCmd->addCmd('finish', $xmlExIm->options['rebuild']);
									$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => $v['numFiles']]);
								}

								$out .= we_html_element::htmlForm(['name' => 'we_form'], $hiddens);
								$xmlExIm->unsetPerserves();
							} else { // do import
								$xmlExIm = new we_exim_XMLImport();
								$chunk = $v["uniquePath"] . basename($v["import_from"]) . "_" . $v["cid"];
								if(file_exists($chunk)){
									$xmlExIm->loadPerserves();
									$this->xmlExImSetOpt(xmlExImSetOpt, $v);
									$imported = $xmlExIm->import($chunk);
									$xmlExIm->savePerserves();
									if($imported){
										$status = g_l('import', '[import]');
										$ref = $xmlExIm->RefTable->getLast();

										switch($ref->ContentType){
											case 'weBinary':
											case 'category':
											case 'objectFile':
												$path_info = $ref->Path;
												break;
											case 'doctype':
												$path_info = f('SELECT DocType FROM ' . escape_sql_query($ref->Table) . ' WHERE ID=' . intval($ref->ID));
												break;
											case we_base_ContentTypes::NAVIGATIONRULE:
												$path_info = f('SELECT NavigationName FROM ' . escape_sql_query($ref->Table) . ' WHERE ID=' . intval($ref->ID));
												break;
											case 'weThumbnail':
												$path_info = f('SELECT Name FROM ' . escape_sql_query($ref->Table) . ' WHERE ID=' . intval($ref->ID));
												break;
											default:
												$path_info = id_to_path($ref->ID, $ref->Table);
												break;
										}
										$progress_text = we_html_element::htmlB(
												g_l('contentTypes', '[' . $ref->ContentType . ']', true) ?:
												(g_l('import', '[' . $ref->ContentType . ']', true) ?: '' )
											) . '  ' . $path_info;
										$jsCmd->addCmd('addLog_buffered', [$progress_text]);
									} else {
										$status = g_l('import', '[skip]');
										$jsCmd->addCmd('addLog_buffered', [g_l('import', '[skip]') . '<br/>']);
									}

									$counter_text = g_l('import', '[item]') . ' ' . $v['cid'] . '/' . ($v['numFiles'] - 2);

									$jsCmd->addCmd('setProgressText_footer', 'pb1', $status . ' - ' . $counter_text);
									$jsCmd->addCmd('setProgress_footer', (int) (((intval($v['cid']) + 1) / (2 * intval($v["numFiles"]))) * 100));
									$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => ($v["cid"] + 1)]);

									$out .= we_html_element::htmlForm(['name' => 'we_form'], $hiddens);
								}
							}
							break 2;
						case we_import_functions::TYPE_GENERIC_XML:
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
								$fields = $fields + [$record => $xp->getData($xPath)];
							}
							if($v['pfx_fn'] == 1){
								$v['rcd_pfx'] = $xp->getData($xp->root . '/' . $v["rcd_pfx"] . "[1]");
								if($v['rcd_pfx'] == ''){
									$v['rcd_pfx'] = g_l('import', ($v['import_type'] === 'documents' ? '[pfx_doc]' : '[pfx_obj]'));
								}
							}
							break;
						case we_import_functions::TYPE_CSV:
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
							list($v["classID"]) = explode('_', $v['classID']);
							$cp = new we_import_CSV;
							$cp->setFile($v['uniquePath'] . '/temp_' . $v["cid"] . ".csv");
							$cp->setDelim($v['csv_seperator']);
							$cp->setEnclosure($encl);
							$cp->setFromCharset($v['encoding']);
							$cp->parseCSV();
							$recs = [];
							$names = [];
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
									$v['rcd_pfx'] = g_l('import', ($v['import_type'] === 'documents' ? '[pfx_doc]' : '[pfx_obj]'));
								}
							}
					}

					if($v['type'] != we_import_functions::TYPE_WE_XML){
						if(isset($v["dateFields"])){
							$dateFields = makeArrayFromCSV($v["dateFields"]);
							if(($v["sTimeStamp"] === "Format" && $v["timestamp"] != "") || ($v["sTimeStamp"] === "GMT")){
								foreach($dateFields as $dateField){
									$fields[$dateField] = we_import_functions::date2Timestamp($fields[$dateField], ($v["sTimeStamp"] != "GMT") ? $v["timestamp"] : "");
								}
							}
						}

						$rcd_name = ($v['pfx_fn'] == 1) ? $v['rcd_pfx'] : $v['asoc_prefix'];
						switch($v['import_type']){
							case 'documents':
								$IsSearchable = $v["docType"] > 0 ? (!empty($v['doc_search'])) || f('SELECT IsSearchable FROM ' . DOC_TYPES_TABLE . ' WHERE ID=' . intval($v["docType"]), '', new DB_WE()) : false;
								if(!we_import_functions::importDocument($v["store_to_id"], $v["we_TemplateID"], $fields, $v["docType"], $v["docCategories"], $rcd_name, $v["is_dynamic"], $v["we_Extension"], isset($v['doc_publish']) ? $v['doc_publish'] : true, $IsSearchable, isset($v['encoding']) ? DEFAULT_CHARSET : '' //if charset is set, we know csv was converted to defaultcharset
										, $v['collision'])){
									t_e('warning', 'import of entry failed', $fields);
								}
								break;
							case 'objects':
								if(!we_import_functions::importObject($v["classID"], $fields, $v["objCategories"], $rcd_name, isset($v['obj_publish']) ? $v['obj_publish'] : true, isset($v['obj_search']) ? $v['obj_search'] : true, isset($v['obj_path_id']) ? $v['obj_path_id'] : 0, isset($v['encoding']) ? DEFAULT_CHARSET : '' //if charset is set, we know csv was converted to defaultcharset
										, $v['collision'])){
									t_e('warning', 'import of entry failed', $fields);
								}
								break;
						}
					}

					$jsCmd->addCmd('setProgressText_footer', 'pb1', g_l('import', '[import]'));
					$jsCmd->addCmd('setProgress_footer', (int) (((intval($v['cid']) + 1) / intva($v["numFiles"])) * 100));
					$jsCmd->addCmd('call_delayed', ['function' => 'we_import', 'delay' => 15, 'param_1' => 1, 'param_2' => ($v["cid"] + 1)]);

					$out .= we_html_element::htmlForm(['name' => 'we_form'], $hiddens);
					break;
			} // end switch
		} else if($mode != 1){
			$out .= we_html_element::htmlForm(['id' => 'wizardBaseForm', "name" => "we_form"], we_html_element::htmlHiddens(["v[mode]" => "",
						"v[cid]" => "",
						"mode" => "",
						"type" => "",
						"cid" => ""]));
		}

		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsScript(JS_DIR . 'import_wizardBase.js') . $jsCmd->getCmds(), we_html_element::htmlBody(['style' => 'overflow:hidden;'], $out));
	}

	private function importFinished(we_base_jsCmd $jsCmd, $v, $type){
		$jsCmd->addCmd('doOnImportFinished', ['progressText' => g_l('import', '[finish_progress]')]);

		if($type = we_import_functions::TYPE_WE_XML){
			$jsCmd->addCmd('addLog_buffered', [we_html_element::htmlB(g_l('import', '[end_import]') . " - " . date("d.m.Y H:i:s"))]);
		} else {
			$jsCmd->addMsg(g_l('import', '[finish_import]'), we_message_reporting::WE_MESSAGE_NOTICE);
			$jsCmd->addCmd('call_delayed', ['function' => 'close', 'delay' => 100]);
		}
	}

	private function formCategory2(we_base_jsCmd $jsCmd, $obj, $categories){
		$addbut = we_html_button::create_button(we_html_button::ADD, "javascript:top.we_cmd('we_selector_category',0,'" . CATEGORY_TABLE . "','','','add_" . $obj . "Cat')", '', 0, 0, '', '', (!we_base_permission::hasPerm('EDIT_KATEGORIE')));
		$cats = new we_chooser_multiDirExtended(410, $categories, 'delete_' . $obj . 'Cat', $addbut, '', '"we/category"', CATEGORY_TABLE);
		$cats->setRowPrefix($obj);
		$cats->setCatField('v[" . $obj . "Categories]');
		return $cats->get($jsCmd);
	}

	/**
	 * @return array
	 * @param integer $classID
	 * @desc returns an array with all the fields of the class with the given $classID
	 */
	private static function getClassFields($classID){
		$db = new DB_WE();
		$dv = we_unserialize(f('SELECT DefaultValues FROM ' . OBJECT_TABLE . ' WHERE ID=' . intval($classID), '', $db));
		$tableInfo_sorted = we_objectFile::getSortedTableInfo($classID, true, $db);
		$fields = [];
		$regs = [];
		foreach($tableInfo_sorted as $cur){
			// bugfix 8141
			if(preg_match('/(.+?)_(.*)/', $cur['name'], $regs)){
				$fields[] = ['name' => $regs[2], 'type' => $regs[1]];
			}
		}
		return $fields;
	}

	/**
	 * @return boolean
	 * @param string $type
	 * @desc returns true if the field is a text field
	 */
	private static function isTextField($type){
		switch($type){
			case 'input':
			case 'text':
			case 'meta':
			case 'checkbox': //Bugfix #4733
				return true;
			default:
				return false;
		}
	}

	/**
	 * @return boolean
	 * @param string $type
	 * @desc returns true if the field is a text field
	 */
	private static function isDateField($type){
		return ($type === 'date');
	}

	/**
	 * @return boolean
	 * @param string $type
	 * @desc returns true if the field is numeric
	 */
	private static function isNumericField($type){
		switch($type){
			case 'int':
			case 'float':
				return true;
			default:
				return false;
		}
	}

	/**
	 * @return string
	 * @param array $v
	 * @desc returns a string of hidden fields
	 */
	protected function getHdns($v, $a, $ignore = []){
		$hdns = '';
		foreach($a as $key => $value){
			if(!in_array($key, $ignore)){
				$hdns .= we_html_element::htmlHidden($v . '[' . $key . ']', $value);
			}
		}
		return $hdns;
	}

	protected function getStep0(we_base_jsCmd $jsCmd){
		$defaultVal = we_import_functions::TYPE_LOCAL_FILES;


		if(!we_base_permission::hasPerm('FILE_IMPORT')){
			$defaultVal = we_import_functions::TYPE_SITE;
			if(!we_base_permission::hasPerm('SITE_IMPORT')){
				$defaultVal = we_import_functions::TYPE_WE_XML;
				if(!we_base_permission::hasPerm('WXML_IMPORT')){
					$defaultVal = we_import_functions::TYPE_GENERIC_XML;
					if(!we_base_permission::hasPerm('GENERICXML_IMPORT')){
						$defaultVal = we_import_functions::TYPE_CSV;
						if(!we_base_permission::hasPerm('CSV_IMPORT')){
							$defaultVal = '';
						}
					}
				}
			}
		}

		$cmd = we_base_request::_(we_base_request::RAW, 'we_cmd', ['import', $defaultVal]);
		$cmd[1] = empty($cmd[1]) ? we_import_functions::TYPE_LOCAL_FILES : $cmd[1];
		$expat = (function_exists('xml_parser_create')) ? true : false;

		$tblFiles = new we_html_table(['class' => 'default withSpace'], 2, 1);
		$tblFiles->setCol(0, 0, [], we_html_forms::radiobutton('file_import', ($cmd[1] == we_import_functions::TYPE_LOCAL_FILES), 'type', g_l('import', '[file_import]'), true, 'defaultfont', '', !we_base_permission::hasPerm('FILE_IMPORT'), g_l('import', '[txt_file_import]'), 0, 384));
		$tblFiles->setCol(1, 0, [], we_html_forms::radiobutton('site_import', ($cmd[1] == we_import_functions::TYPE_SITE), 'type', g_l('import', '[site_import]'), true, 'defaultfont', '', !we_base_permission::hasPerm('SITE_IMPORT'), g_l('import', '[txt_site_import]'), 0, 384));
		$tblData = new we_html_table(['class' => 'default withSpace'], 3, 1);
		$tblData->setCol(0, 0, [], we_html_forms::radiobutton(we_import_functions::TYPE_WE_XML, ($cmd[1] == we_import_functions::TYPE_WE_XML), 'type', g_l('import', '[wxml_import]'), true, 'defaultfont', '', (!we_base_permission::hasPerm('WXML_IMPORT') || !$expat), g_l('import', ($expat ? '[txt_wxml_import]' : '[add_expat_support]')), 0, 384));
		$tblData->setCol(1, 0, [], we_html_forms::radiobutton(we_import_functions::TYPE_GENERIC_XML, ($cmd[1] == we_import_functions::TYPE_GENERIC_XML), 'type', g_l('import', '[gxml_import]'), true, 'defaultfont', '', (!we_base_permission::hasPerm('GENERICXML_IMPORT') || !$expat), g_l('import', ($expat ? '[txt_gxml_import]' : '[add_expat_support]')), 0, 384));
		$tblData->setCol(2, 0, [], we_html_forms::radiobutton(we_import_functions::TYPE_CSV, ($cmd[1] == we_import_functions::TYPE_CSV), 'type', g_l('import', '[csv_import]'), true, 'defaultfont', '', !we_base_permission::hasPerm('CSV_IMPORT'), g_l('import', '[txt_csv_import]'), 0, 384));

		$parts = [
			[
				'headline' => g_l('import', '[import_file]'),
				'html' => $tblFiles->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1],
			['headline' => g_l('import', '[import_data]'),
				'html' => $tblData->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1],
		];
		return we_html_multiIconBox::getHTML('', $parts, 30, '', -1, '', '', false, g_l('import', '[title]'));
	}

	private function getWXMLImportStep1(we_base_jsCmd $jsCmd){
		$v = we_base_request::_(we_base_request::STRING, 'v', []);
		$doc_root = get_def_ws();
		$tmpl_root = get_def_ws(TEMPLATES_TABLE);
		$nav_root = get_def_ws(NAVIGATION_TABLE);

		$hdns = we_html_element::htmlHiddens(['v[doc_dir_id]' => (isset($v['doc_dir_id']) ? $v['doc_dir_id'] : $doc_root),
				'v[tpl_dir_id]' => (isset($v['tpl_dir_id']) ? $v['tpl_dir_id'] : $tmpl_root),
				'v[doc_dir]' => (isset($v['doc_dir']) ? $v['doc_dir'] : id_to_path($doc_root)),
				'v[tpl_dir]' => (isset($v['tpl_dir']) ? $v['tpl_dir'] : id_to_path($tmpl_root, TEMPLATES_TABLE)),
				'v[import_from]' => (isset($v['import_from']) ? $v['import_from'] : 0),
				'v[navigation_dir_id]' => (isset($v['navigation_dir_id']) ? $v['navigation_dir_id'] : $nav_root),
				'v[navigation_dir]' => (isset($v['navigation_dir']) ? $v['navigation_dir'] : id_to_path($nav_root, NAVIGATION_TABLE)),
				'v[import_docs]' => (isset($v['import_docs'])) ? $v['import_docs'] : 0,
				'v[import_templ]' => (isset($v['import_templ'])) ? $v['import_templ'] : 0,
				'v[import_thumbnails]' => (isset($v['import_thumbnails'])) ? $v['import_thumbnails'] : 0,
				'v[import_objs]' => (isset($v['import_objs'])) ? $v['import_objs'] : 0,
				'v[import_classes]' => (isset($v['import_classes'])) ? $v['import_classes'] : 0,
				'v[restore_doc_path]' => (isset($v['restore_doc_path'])) ? $v['restore_doc_path'] : 1,
				'v[restore_tpl_path]' => (isset($v['restore_tpl_path'])) ? $v['restore_tpl_path'] : 1,
				'v[import_dt]' => (isset($v['import_dt'])) ? $v['import_dt'] : 0,
				'v[import_ct]' => (isset($v['import_ct'])) ? $v['import_ct'] : 0,
				'v[import_binarys]' => (isset($v['import_binarys'])) ? $v['import_binarys'] : 0,
				'v[import_owners]' => (isset($v['import_owners'])) ? $v['import_owners'] : 0,
				'v[owners_overwrite]' => (isset($v['owners_overwrite'])) ? $v['owners_overwrite'] : 0,
				'v[owners_overwrite_id]' => (isset($v['owners_overwrite_id'])) ? $v['owners_overwrite_id'] : 0,
				'v[owners_overwrite_path]' => (isset($v['owners_overwrite_path'])) ? $v['owners_overwrite_path'] : '/',
				'v[import_navigation]' => (isset($v['import_navigation'])) ? $v['import_navigation'] : 0,
				'v[rebuild]' => (isset($v['rebuild'])) ? $v['rebuild'] : 1,
				'v[mode]' => (isset($v['mode']) ? $v['mode'] : 0),
				'v[btnState_next]' => 'enabled',
				'v[btnState_back]' => 'enabled'
		]);

		$importFromButton = (we_base_permission::hasPerm('CAN_SELECT_EXTERNAL_FILES')) ? we_html_button::create_button(we_html_button::SELECT, "javascript: we_cmd('browse_server', 'v[fserver]', '', top.wizbody.document.we_form.elements['v[fserver]'].value, 'setFormField,v[rdofloc],1,radio,0')") : '';
		$inputLServer = we_html_tools::htmlTextInput('v[fserver]', 30, (isset($v['fserver']) ? $v['fserver'] : '/'), 255, 'readonly', 'text', 300);
		$importFromServer = we_html_tools::htmlFormElementTable($inputLServer, '', 'left', 'defaultfont', $importFromButton, '', '', '', '', 0);

		$inputLLocal = $this->fileUploader->getHTML();
		$importFromLocal = we_html_tools::htmlFormElementTable($inputLLocal, '', 'left', 'defaultfont', '', '', '', '', '', 0);
		$rdoLServer = we_html_forms::radiobutton('lServer', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lServer') : 1, 'v[rdofloc]', g_l('import', '[fileselect_server]'));
		$rdoLLocal = we_html_forms::radiobutton('lLocal', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lLocal') : 0, 'v[rdofloc]', g_l('import', '[fileselect_local]'));
		$importLocs = new we_html_table(['class' => 'default'], 6, 1);
		$importLocs->setColContent(0, 0, $rdoLServer);
		$importLocs->setColContent(1, 0, $importFromServer);
		$importLocs->setCol(3, 0, ['style' => 'padding-top:4px;'], $rdoLLocal);
		$importLocs->setCol(4, 0, [], $this->fileUploader->getHtmlAlertBoxes());
		$importLocs->setCol(5, 0, [], $importFromLocal);
		$fn_colsn = new we_html_table(['class' => 'default withSpace'], 4, 1);
		$fn_colsn->setCol(0, 0, [], we_html_tools::htmlAlertAttentionBox(g_l('import', '[collision_txt]'), we_html_tools::TYPE_ALERT, 410));
		$fn_colsn->setCol(1, 0, [], we_html_forms::radiobutton('replace', (empty($v['collision']) || $v['collision'] === 'replace'), 'v[collision]', g_l('import', '[replace]'), true, 'defaultfont', '', false, g_l('import', '[replace_txt]'), 0, 384));
		$fn_colsn->setCol(2, 0, [], we_html_forms::radiobutton('rename', (!empty($v['collision']) && $v['collision'] === 'rename'), 'v[collision]', g_l('import', '[rename]'), true, 'defaultfont', '', false, g_l('import', '[rename_txt]'), 0, 384));
		$fn_colsn->setCol(3, 0, [], we_html_forms::radiobutton('skip', (!empty($v['collision']) && $v['collision'] === 'skip'), 'v[collision]', g_l('import', '[skip]'), true, 'defaultfont', '', false, g_l('import', '[skip_txt]'), 0, 384));

		$parts = [
			['headline' => g_l('import', '[import]'),
				'html' => $importLocs->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED],
			['headline' => g_l('import', '[file_collision]'),
				'html' => $fn_colsn->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED]
		];

		$znr = -1;
		$content = $hdns . we_html_multiIconBox::getHTML(we_import_functions::TYPE_WE_XML, $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', '[wxml_import]'));
		return $content;
	}

	private function getWXMLImportStep2(we_base_jsCmd $jsCmd){
		$v = we_base_request::_(we_base_request::STRING, 'v', []);
		$upload_error = false;

		if($v['rdofloc'] === 'lLocal'){
			if((!$v['import_from'] = $this->fileUploader->commitUploadedFile())){
				$upload_error = $this->fileUploader->getError();
			}
		}

		$we_valid = true;

		$hdns = we_html_element::htmlHiddens(['v[type]' => $v['type'],
				'v[mode]' => (isset($v['mode'])) ? $v['mode'] : 0,
				'v[fserver]' => $v['fserver'],
				'v[rdofloc]' => $v['rdofloc'],
				'v[import_from]' => $v['import_from'],
				'v[collision]' => isset($v['collision']) ? $v['collision'] : 0,
		]);

		$hdnsBtnStates = we_html_element::htmlHiddens([
				'v[btnState_next]' => (($we_valid) ? ((isset($v['mode']) && $v['mode'] == 1) ? 'disabled' : 'enabled') : 'disabled'),
				'v[btnState_back]' => 'enabled'
		]);

		$return = ['', ''];
		$cmd = new we_base_jsCmd();

		if($upload_error){
			$cmd->addMsg($upload_error, we_message_reporting::WE_MESSAGE_ERROR);
			$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
			$return[1] = $hdnsBtnStates . $cmd->getCmds();
			return $return;
		}

		$import_file = $_SERVER['DOCUMENT_ROOT'] . $v['import_from'];
		if(we_backup_util::getFormat($import_file) != 'xml'){
			$cmd->addMsg(g_l('import', '[format_unknown]'), we_message_reporting::WE_MESSAGE_ERROR);
			$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
			$return[1] = $hdnsBtnStates . $cmd->getCmds();
			return $return;
		}
		$xml_type = we_backup_util::getXMLImportType($import_file);
		switch($xml_type){
			case 'backup':
				$return[0] = '';
				if(we_base_permission::hasPerm('IMPORT')){
					$cmd->addCmd('confirm_start_recoverBackup');
					$return[1] = $cmd->getCmds();
				} else {
					$cmd->addMsg(g_l('import', '[backup_file_found]'), we_message_reporting::WE_MESSAGE_ERROR);
					$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
					$return[1] = $hdnsBtnStates . $cmd->getCmds();
				}
				return $return;
			case 'customer':
				$cmd->addMsg(g_l('import', '[customer_import_file_found]'), we_message_reporting::WE_MESSAGE_ERROR);
				$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
				$return[1] = $hdnsBtnStates . $cmd->getCmds();
				return $return;
			case 'unreadble':
				$cmd->addMsg(g_l('backup', '[file_not_readable]'), we_message_reporting::WE_MESSAGE_ERROR);
				$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
				$return[1] = $hdnsBtnStates . $cmd->getCmds();
				return $return;
			case 'unknown':
				$cmd->addMsg(g_l('import', '[format_unknown]'), we_message_reporting::WE_MESSAGE_ERROR);
				$cmd->addCmd('we_cmd', ['handle_event', 'previous']);
				$return[1] = $hdnsBtnStates . $cmd->getCmds();
				return $return;
		}

		$parts = [];
		if($we_valid){
			$tbl_extra = new we_html_table([], 5, 1);

			// import documents
			$tbl_extra->setCol(0, 0, null, we_html_forms::checkboxWithHidden((!empty($v['import_docs'])) ? true : false, 'v[import_docs]', g_l('import', '[import_docs]'), false, 'defaultfont', "toggle('doc_table')"));

			$rootDirID = get_def_ws();
			$weSuggest = & weSuggest::getInstance();
			$weSuggest->setAcId('DocPath');
			$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
			$weSuggest->setInput('v[doc_dir]', (isset($v['doc_dir']) ? $v['doc_dir'] : id_to_path($rootDirID)), ['onfocus' => "top.setFormField('_v[restore_doc_path]', false, 'checkbox');"]);
			$weSuggest->setMaxResults(10);
			$weSuggest->setRequired(true);
			$weSuggest->setResult("v[doc_dir_id]", (isset($v["doc_dir_id"]) ? $v["doc_dir_id"] : $rootDirID));
			$weSuggest->setSelector(weSuggest::DirSelector);
			$weSuggest->setTable(FILE_TABLE);
			$weSuggest->setWidth(280);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',top.wizbody.document.we_form.elements['v[doc_dir_id]'].value,'" . FILE_TABLE . "','v[doc_dir_id]','v[doc_dir]','','','" . $rootDirID . "')"));


			$docPath = $weSuggest->getHTML();

			$dir_table = new we_html_table(['id' => 'doc_table', 'style' => 'margin-left:20px;'], 3, 2);
			if((isset($v['import_docs']) && !$v['import_docs'])){
				$dir_table->setStyle('display', 'none');
			}
			$dir_table->setCol(0, 0, null, we_html_tools::htmlAlertAttentionBox(g_l('import', '[documents_desc]'), we_html_tools::TYPE_ALERT, 390, true, 50));
			$dir_table->setCol(1, 0, null, $docPath);
			$dir_table->setCol(2, 0, null, we_html_forms::checkboxWithHidden((!empty($v['restore_doc_path'])), 'v[restore_doc_path]', g_l('import', '[maintain_paths]'), false, "defaultfont", "self.document.we_form.elements['v[doc_dir]'].value='/';"));

			$tbl_extra->setCol(1, 0, null, $dir_table->getHtml());

			// --------------
			// import templates
			$rootDirID = get_def_ws(TEMPLATES_TABLE);
			$tbl_extra->setCol(2, 0, ['colspan' => 2], we_html_forms::checkboxWithHidden((!empty($v['import_templ'])), 'v[import_templ]', g_l('import', '[import_templ]'), false, 'defaultfont', "toggle('tpl_table')"));

			$weSuggest->setAcId('TemplPath');
			$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
			$weSuggest->setInput('v[tpl_dir]', (isset($v['tpl_dir']) ? $v['tpl_dir'] : id_to_path($rootDirID, TEMPLATES_TABLE)), ['onfocus' => "top.setFormField('_v[restore_tpl_path]', false, 'checkbox');"]);
			$weSuggest->setMaxResults(10);
			$weSuggest->setRequired(true);
			$weSuggest->setResult('v[tpl_dir_id]', (isset($v['tpl_dir_id'])) ? $v['tpl_dir_id'] : $rootDirID);
			$weSuggest->setSelector(weSuggest::DirSelector);
			$weSuggest->setTable(TEMPLATES_TABLE);
			$weSuggest->setWidth(280);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',top.wizbody.document.we_form.elements['v[tpl_dir_id]'].value,'" . TEMPLATES_TABLE . "','v[tpl_dir_id]','v[tpl_dir]','','','" . $rootDirID . "')"));

			$docPath = $weSuggest->getHTML();

			$dir_table = new we_html_table(['id' => 'tpl_table', 'style' => 'margin-left:20px;'], 3, 2);
			if((isset($v['import_templ']) && !$v['import_templ'])){
				$dir_table->setStyle('display', 'none');
			}
			$dir_table->setCol(0, 0, null, we_html_tools::htmlAlertAttentionBox(g_l('import', '[templates_desc]'), we_html_tools::TYPE_ALERT, 390, true, 50));
			$dir_table->setCol(1, 0, null, $docPath);
			$dir_table->setCol(2, 0, null, we_html_forms::checkboxWithHidden((!empty($v['restore_tpl_path'])) ? true : false, 'v[restore_tpl_path]', g_l('import', '[maintain_paths]'), false, 'defaultfont', "self.document.we_form.elements['v[tpl_dir]'].value='/';"));


			$tbl_extra->setCol(3, 0, null, $dir_table->getHtml());

			$tbl_extra->setCol(4, 0, ["colspan" => 2], we_html_forms::checkboxWithHidden((!empty($v["import_thumbnails"])) ? true : false, "v[import_thumbnails]", g_l('import', '[import_thumbnails]'), false, "defaultfont"));


			$parts[] = ["headline" => g_l('import', '[handle_document_options]') . '<br/>' . g_l('import', '[handle_template_options]'),
				"html" => $tbl_extra->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED
			];

			if(defined('OBJECT_TABLE')){
				$tbl_extra = new we_html_table([], 2, 1);
				$tbl_extra->setCol(0, 0, null, we_html_forms::checkboxWithHidden((!empty($v["import_objs"])) ? true : false, "v[import_objs]", g_l('import', '[import_objs]')));
				$tbl_extra->setCol(1, 0, null, we_html_forms::checkboxWithHidden((!empty($v["import_classes"])) ? true : false, "v[import_classes]", g_l('import', '[import_classes]')));

				$parts[] = ["headline" => g_l('import', '[handle_object_options]') . '<br/>' . g_l('import', '[handle_class_options]'),
					"html" => $tbl_extra->getHTML(),
					'space' => we_html_multiIconBox::SPACE_MED
				];
			}

			$tbl_extra = new we_html_table([], 4, 1);
			$tbl_extra->setCol(0, 0, null, we_html_forms::checkboxWithHidden((!empty($v["import_dt"])) ? true : false, "v[import_dt]", g_l('import', '[import_doctypes]')));
			$tbl_extra->setCol(1, 0, null, we_html_forms::checkboxWithHidden((!empty($v["import_ct"])) ? true : false, "v[import_ct]", g_l('import', '[import_cats]')));
			$tbl_extra->setCol(2, 0, null, we_html_forms::checkboxWithHidden((!empty($v["import_navigation"])) ? true : false, "v[import_navigation]", g_l('import', '[import_navigation]'), false, 'defaultfont', "toggle('navigation_table')"));

			// --
			$weSuggest->setAcId("NaviPath");
			$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
			$weSuggest->setInput("v[navigation_dir]", (isset($v["navigation_dir"]) ? $v["navigation_dir"] : id_to_path($rootDirID)));
			$weSuggest->setMaxResults(10);
			$weSuggest->setRequired(true);
			$weSuggest->setResult("v[navigation_dir_id]", (isset($v["navigation_dir_id"])) ? $v["navigation_dir_id"] : $rootDirID);
			$weSuggest->setSelector(weSuggest::DirSelector);
			$weSuggest->setTable(NAVIGATION_TABLE);
			$weSuggest->setWidth(280);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_navigation_dirSelector',document.we_form.elements[\"v[navigation_dir_id]\"].value,'v[navigation_dir_id]','v[navigation_dir]');"));

			$docPath = $weSuggest->getHTML();

			$dir_table = new we_html_table(["id" => "navigation_table", 'style' => 'margin-left:20px;'], 2, 1);
			if((isset($v["import_navigation"]) && !$v["import_navigation"])){
				$dir_table->setStyle('display', 'none');
			}
			$dir_table->setCol(0, 0, null, we_html_tools::htmlAlertAttentionBox(g_l('import', '[navigation_desc]'), we_html_tools::TYPE_ALERT, 390));
			$dir_table->setCol(1, 0, null, $docPath);

			$tbl_extra->setCol(3, 0, null, $dir_table->getHtml());

			$xml_encoding = we_xml_parser::getEncoding($import_file);

			$parts[] = [
				"headline" => g_l('import', '[handle_doctype_options]') . '<br/>' . g_l('import', '[handle_category_options]'),
				"html" => '<input type="hidden" name="v[import_XMLencoding]" value="' . $xml_encoding . '" />' . $tbl_extra->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED
			];


			if(DEFAULT_CHARSET != '' && (DEFAULT_CHARSET === 'ISO-8859-1' || DEFAULT_CHARSET === 'UTF-8' ) && ($xml_encoding === 'ISO-8859-1' || $xml_encoding === 'UTF-8' )){
				if(($xml_encoding != DEFAULT_CHARSET)){
					$parts[] = [
						'headline' => g_l('import', '[encoding_headline]'),
						'html' => we_html_forms::checkboxWithHidden((!empty($v['import_ChangeEncoding'])) ? true : false, 'v[import_ChangeEncoding]', g_l('import', '[encoding_change]') . $xml_encoding . g_l('import', '[encoding_to]') . DEFAULT_CHARSET . g_l('import', '[encoding_default]')) . we_html_element::htmlHiddens([
							"v[import_XMLencoding]" => $xml_encoding, "v[import_TARGETencoding]" => DEFAULT_CHARSET]),
						'space' => we_html_multiIconBox::SPACE_MED
					];
				}
			} else {
				$parts[] = ['headline' => g_l('import', '[encoding_headline]'),
					'html' => we_html_forms::checkboxWithHidden((!empty($v['import_ChangeEncoding'])) ? true : false, 'v[import_ChangeEncoding]', g_l('import', '[encoding_noway]') . we_html_element::htmlHidden("v[import_XMLencoding]", $xml_encoding), false, "defaultfont", '', true),
					'space' => we_html_multiIconBox::SPACE_MED
				];
			}

			$parts[] = ['headline' => g_l('import', '[handle_file_options]'),
				'html' => we_html_forms::checkboxWithHidden((!empty($v['import_binarys'])) ? true : false, 'v[import_binarys]', g_l('import', '[import_files]')),
				'space' => we_html_multiIconBox::SPACE_MED
			];

			$parts[] = ['headline' => g_l('import', '[rebuild]'),
				'html' => we_html_forms::checkboxWithHidden((!empty($v['rebuild'])) ? true : false, 'v[rebuild]', g_l('import', '[rebuild_txt]')),
				'space' => we_html_multiIconBox::SPACE_MED
			];

			$header = we_base_file::loadPart($_SERVER['DOCUMENT_ROOT'] . $v['import_from'], 0, 512);

			if(empty($header)){
				$hdnsBtnStates = we_html_element::htmlHiddens([
						'v[btnState_next]' => 'disabled',
						'v[btnState_back]' => 'enabled'
				]);
				$parts = [
					['headline' => '',
						'html' => we_html_tools::htmlAlertAttentionBox(g_l('import', '[invalid_path]'), we_html_tools::TYPE_ALERT, 530),
					]
				];
				$content = $hdns . $hdnsBtnStates . we_html_multiIconBox::getHTML(we_import_functions::TYPE_WE_XML, $parts, 30, '', -1, '', '', false, g_l('import', '[warning]'));
				return ['', $content];
			}

			$show_owner_opt = strpos($header, '<we:info>') !== false;

			if($show_owner_opt){
				$tbl_extra = new we_html_table([], 2, 1);
				$tbl_extra->setCol(0, 0, null, we_html_forms::checkboxWithHidden((!empty($v['import_owners'])) ? true : false, 'v[import_owners]', g_l('import', '[handle_owners]')));
				$tbl_extra->setCol(1, 0, null, we_html_forms::checkboxWithHidden((!empty($v['owners_overwrite'])) ? true : false, 'v[owners_overwrite]', g_l('import', '[owner_overwrite]')));

				$tbl_extra2 = we_html_element::htmlDiv(['style' => 'margin:20px 20px 0 0;'], $this->formWeChooser(USER_TABLE, '', 0, 'v[owners_overwrite_id]', (isset($v['owners_overwrite_id']) ? $v['owners_overwrite_id'] : 0), 'v[owners_overwrite_path]', (isset($v['owners_overwrite_path']) ? $v['owners_overwrite_path'] : '/')));

				$parts[] = [
					'headline' => g_l('import', '[handle_owners_option]'),
					'html' => we_html_tools::htmlAlertAttentionBox(g_l('import', '[notexist_overwrite]'), we_html_tools::TYPE_ALERT, 530) . $tbl_extra->getHTML() . $tbl_extra2,
					'space' => we_html_multiIconBox::SPACE_MED
				];
			} else {
				$hdns .= we_html_element::htmlHiddens([
						'v[import_owners]' => 0,
						'v[owners_overwrite]' => 0,
						'v[owners_overwrite_id]' => 0]);
			}
		} else {
			$parts[] = ['headline' => g_l('import', '[xml_file]'),
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('import', '[invalid_wxml]'), we_html_tools::TYPE_ALERT, 530),
				'space' => we_html_multiIconBox::SPACE_MED
			];
		}

		$znr = -1;
		$content = $hdns . $hdnsBtnStates . we_html_multiIconBox::getHTML(we_import_functions::TYPE_WE_XML, $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', ($we_valid ? '[import_options]' : '[wxml_import]')));
		return $content;
	}

	private function getWXMLImportStep3(we_base_jsCmd $jsCmd){
		$hdns = we_html_element::htmlHiddens(['v[btnState_next]' => 'disabled', 'v[btnState_back]' => 'disabled']);
		$parts = [
			['headline' => '',
				'html' => we_html_element::htmlDiv(['class' => 'blockWrapper', 'style' => 'width: 520px; height: 400px; border:1px #dce6f2 solid;', 'id' => 'log'], ''),
			]
		];
		$content = $hdns . we_html_multiIconBox::getHTML(we_import_functions::TYPE_WE_XML, $parts, 30, '', -1, '', '', false, g_l('import', '[log]'));

		return $content;
	}

	/**
	 * Generic XML Import Step 1
	 *
	 * @return unknown
	 */
	private function getGXMLImportStep1(we_base_jsCmd $jsCmd){
		global $DB_WE;
		$v = we_base_request::_(we_base_request::STRING, 'v', []);

		if(isset($v['docType']) && $v['docType'] != -1 && we_base_request::_(we_base_request::BOOL, 'doctypeChanged')){
			$values = getHash('SELECT ParentID,Extension,IsDynamic,Category FROM ' . DOC_TYPES_TABLE . ' WHERE ID=' . intval($v["docType"]), $GLOBALS['DB_WE']);
			$v['store_to_id'] = $values['ParentID'];
			$v['store_to_path'] = id_to_path($v['store_to_id']);
			$v['we_Extension'] = $values['Extension'];
			$v['is_dynamic'] = $values['IsDynamic'];
			$v['docCategories'] = $values['Category'];
		}

		$hdns = we_html_element::htmlHiddens(['v[importDataType]' => '',
				'v[import_from]' => (isset($v['import_from']) ? $v['import_from'] : ''),
				'v[docCategories]' => (isset($v['docCategories']) ? $v['docCategories'] : ''),
				'v[objCategories]' => (isset($v['objCategories']) ? $v['objCategories'] : ''),
				//'v[store_to_id]', 'value' => (isset($v['store_to_id']) ? $v['store_to_id'] : 0))).
				'v[collision]' => (isset($v['collision']) ? $v['collision'] : 'rename'),
				'doctypeChanged' => 0,
				'v[we_TemplateID]' => 0,
				//'v[we_TemplateName]', 'value' => '/')).
				'v[is_dynamic]' => (isset($v['is_dynamic']) ? $v['is_dynamic'] : 0),
				'v[btnState_next]' => 'enabled',
				'v[btnState_back]' => 'enabled'
		]);


		if(!defined('OBJECT_TABLE')){
			$hdns .= we_html_element::htmlHidden('v[import_type]', 'documents');
		}

		$v['import_type'] = isset($v['import_type']) ? $v['import_type'] : 'documents';

		$importFromButton = (we_base_permission::hasPerm('CAN_SELECT_EXTERNAL_FILES')) ? we_html_button::create_button(we_html_button::SELECT, "javascript: we_cmd('browse_server', 'v[fserver]', '', document.we_form.elements['v[fserver]'].value, 'setFormField,v[rdofloc],1,radio,0');") : "";
		$inputLServer = we_html_tools::htmlTextInput('v[fserver]', 30, (isset($v['fserver']) ? $v['fserver'] : '/'), 255, 'readonly', 'text', 300);
		$importFromServer = we_html_tools::htmlFormElementTable($inputLServer, '', 'left', 'defaultfont', $importFromButton, '', '', '', '', 0);

		//FIXME: still need condition?
		$inputLLocal = $this->fileUploader->getHTML();
		$importFromLocal = we_html_tools::htmlFormElementTable($inputLLocal, '', 'left', 'defaultfont', '', '', '', '', '', 0);
		$rdoLServer = we_html_forms::radiobutton('lServer', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lServer') : 1, 'v[rdofloc]', g_l('import', '[fileselect_server]'));
		$rdoLLocal = we_html_forms::radiobutton('lLocal', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lLocal') : 0, 'v[rdofloc]', g_l('import', '[fileselect_local]'));
		$importLocs = new we_html_table(['class' => 'default'], 7, 1);
		$tblRow = 0;
		$importLocs->setCol($tblRow++, 0, [], $rdoLServer);
		$importLocs->setCol($tblRow++, 0, [], $importFromServer);
		$importLocs->setCol($tblRow++, 0, ['style' => 'padding-top:4px;'], $rdoLLocal);
		//FIXME: still need condition?
		$importLocs->setCol($tblRow++, 0, [], $this->fileUploader->getHtmlAlertBoxes());
		$importLocs->setCol($tblRow++, 0, [], $importFromLocal);

		$DB_WE->query('SELECT dt.ID,dt.DocType FROM ' . DOC_TYPES_TABLE . ' dt ORDER BY dt.DocType');
		$DTselect = new we_html_select(['name' => 'v[docType]',
			'class' => 'weSelect',
			'onclick' => (defined('OBJECT_TABLE')) ? "top.setFormField('v[import_type]', true, 'radio', 0);" : '',
			'onchange' => "top.setFormField('doctypeChanged', 1, 'hidden'); top.weChangeDocType(this);",
			'style' => 'width: 300px'
		]);
		$optid = 0;
		$DTselect->insertOption($optid, -1, g_l('import', '[none]'));

		$v['docType'] = isset($v['docType']) ? $v['docType'] : -1;
		while($DB_WE->next_record()){
			$optid++;
			$DTselect->insertOption($optid, $DB_WE->f('ID'), $DB_WE->f('DocType'));
			if($v['docType'] == $DB_WE->f('ID')){
				$DTselect->selectOption($DB_WE->f('ID'));
			}
		}
		$doctypeElement = we_html_tools::htmlFormElementTable($DTselect->getHTML(), g_l('import', '[doctype]'), 'left', 'defaultfont');

		/*		 * * templateElement *************************************************** */
		/* $ueberschrift = (we_base_permission::hasPerm('CAN_SEE_TEMPLATES')?
		  '<a href="javascript:goTemplate(document.we_form.elements[\'' . $idname . '\'].value)">' . g_l('import', '[template]') . '</a>':
		  g_l('import', '[template]')); */

		$myid = (isset($v['we_TemplateID'])) ? $v['we_TemplateID'] : 0;
		//$path = f('SELECT Path FROM ' . TEMPLATES_TABLE . ' WHERE ID=' . intval($myid), 'Path', $DB_WE);

		/*		 * ******************************************************************** */
		$weSuggest = & weSuggest::getInstance();

		$TPLselect = new we_html_select(['name' => 'docTypeTemplateId',
			'class' => 'weSelect',
			'onclick' => (defined('OBJECT_TABLE')) ? "top.setFormField('v[import_type]', true, 'radio', 0);" : '',
			//'onchange'  => "we_submit_form(self.document.we_form, 'wizbody', '".$this->path."');",
			'style' => 'width: 300px'
		]);

		if($v['docType'] != -1 && !empty($TPLselect->childs)){
			$displayDocType = 'display:block';
			$displayNoDocType = 'display:none';
			$foo = getHash('SELECT TemplateID,Templates FROM ' . DOC_TYPES_TABLE . ' dt WHERE dt.ID=' . intval($v['docType']), $DB_WE);
			$ids_arr = makeArrayFromCSV($foo['Templates']);
			$paths_arr = id_to_path($foo['Templates'], TEMPLATES_TABLE, null, true);

			$optid = 0;
			while(list(, $templateID) = each($ids_arr)){
				$TPLselect->insertOption($optid, $templateID, $paths_arr[$optid]);
				$optid++;
				if(isset($v['we_TemplateID']) && $v['we_TemplateID'] == $templateID){
					$TPLselect->selectOption($templateID);
				}
			}
		} else {
			$displayDocType = 'display:none';
			$displayNoDocType = 'display:block';
		}

		$templateElement = "<div id='docTypeLayer' style='" . $displayDocType . "'>" . we_html_tools::htmlFormElementTable($TPLselect->getHTML(), g_l('import', '[template]'), "left", "defaultfont") . "</div>";

		$weSuggest->setAcId('TmplPath');
		$weSuggest->setContentType('folder,' . we_base_ContentTypes::TEMPLATE);
		$weSuggest->setInput('v[we_TemplateName]', (isset($v['we_TemplateName']) ? $v['we_TemplateName'] : ''), ['onfocus' => "top.setFormField('v[import_type]', true, 'radio', 0);"]);
		$weSuggest->setMaxResults(10);
		$weSuggest->setResult('noDocTypeTemplateId', $myid);
		$weSuggest->setSelector(weSuggest::DocSelector);
		$weSuggest->setTable(TEMPLATES_TABLE);
		$weSuggest->setWidth(300);
		$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_document',top.wizbody.document.we_form.elements['noDocTypeTemplateId'].value,'" . TEMPLATES_TABLE . "','noDocTypeTemplateId','v[we_TemplateName]','reload_editpage','','','" . we_base_ContentTypes::TEMPLATE . "',1)"));
		$weSuggest->setLabel(g_l('import', '[template]'));

		$templateElement .= "<div id='noDocTypeLayer' style='" . $displayNoDocType . "'>" . $weSuggest->getHTML() . "</div>";

		$docCategories = $this->formCategory2($jsCmd, 'doc', isset($v['docCategories']) ? $v['docCategories'] : '');
		$docCats = new we_html_table(['class' => 'default'], 2, 2);
		$docCats->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', 'class' => 'defaultfont lowContrast'], g_l('import', '[categories]'));
		$docCats->setCol(0, 1, ['style' => 'width:150px;'], $docCategories);

		$weSuggest->setAcId('DirPath');
		$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
		$weSuggest->setInput('v[store_to_path]', (isset($v['store_to_path']) ? $v['store_to_path'] : '/'), ['onfocus' => "top.setFormField('v[import_type]', true, 'radio', 0);"]);
		$weSuggest->setMaxResults(10);
		$weSuggest->setRequired(true);
		$weSuggest->setResult('v[store_to_id]', (isset($v['store_to_id']) ? $v['store_to_id'] : 0));
		$weSuggest->setSelector(weSuggest::DirSelector);
		$weSuggest->setWidth(300);
		$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',top.wizbody.document.we_form.elements['v[store_to_id]'].value,'" . FILE_TABLE . "','v[store_to_id]','v[store_to_path]','','','0')"));
		$weSuggest->setLabel(g_l('import', '[import_dir]'));

		$storeTo = $weSuggest->getHTML();

		$radioDocs = we_html_forms::radiobutton('documents', ($v['import_type'] === 'documents'), 'v[import_type]', g_l('import', '[documents]'));
		$radioObjs = we_html_forms::radiobutton('objects', ($v['import_type'] === 'objects'), 'v[import_type]', g_l('import', '[objects]'), true, 'defaultfont', "self.document.we_form.elements['v[store_to_path]'].value='/'; WE().layout.weSuggest.checkRequired(window,self.document.we_form.elements['v[store_to_path]'].id); if(self.document.we_form.elements['v[we_TemplateName]']!==undefined) { self.document.we_form.elements['v[we_TemplateName]'].value=''; WE().layout.weSuggest.checkRequired(window,self.document.we_form.elements['v[we_TemplateName]'].id); }", (defined('OBJECT_TABLE') ? false : true));

		$v['classID'] = isset($v['classID']) ? $v['classID'] : -1;
		$CLselect = new we_html_select(['name' => 'v[classID]',
			'class' => 'weSelect',
			'onclick' => "top.setFormField('v[import_type]', true, 'radio', 1);",
			'style' => 'width: 150px'
		]);
		$optid = 0;
		$ac = implode(',', we_users_util::getAllowedClasses($DB_WE));
		if($ac){
			$DB_WE->query('SELECT ID,Text FROM ' . OBJECT_TABLE . ' ' . ($ac ? ' WHERE ID IN(' . $ac . ') ' : '') . 'ORDER BY Text');
			while($DB_WE->next_record()){
				$optid++;
				$CLselect->insertOption($optid, $DB_WE->f('ID'), $DB_WE->f('Text'));
				if($DB_WE->f('ID') == $v['classID']){
					$CLselect->selectOption($DB_WE->f('ID'));
				}
			}
		} else {
			$CLselect->insertOption($optid, -1, g_l('import', '[none]'));
		}

		$objClass = new we_html_table(['class' => 'default'], 2, 2);
		$objClass->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', 'class' => 'defaultfont lowContrast'], g_l('import', '[class]'));
		$objClass->setCol(0, 1, ['style' => 'width:150px;'], $CLselect->getHTML());

		$objCategories = $this->formCategory2($jsCmd, 'obj', isset($v['objCategories']) ? $v['objCategories'] : '');
		$objCats = new we_html_table(['class' => 'default'], 2, 2);
		$objCats->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', 'class' => 'defaultfont lowContrast'], g_l('import', '[categories]'));
		$objCats->setCol(0, 1, ['style' => 'width:150px;'], $objCategories);

		$objects = new we_html_table(['class' => 'default'], 3, 2);
		$objects->setCol(0, 0, ['colspan' => 3, 'class' => 'withBigSpace'], $radioObjs);
		$objects->setCol(1, 0, ['style' => 'width:50px;']);
		$objects->setCol(1, 1, [], $objClass->getHTML());
		$objects->setCol(2, 1, [], $objCats->getHTML());

		$specifyDoc = new we_html_table(['class' => 'default'], 1, 2);
		$specifyDoc->setCol(0, 1, ['style' => 'vertical-align:bottom'], we_html_forms::checkbox(3, (isset($v['is_dynamic']) ? $v['is_dynamic'] : 0), 'chbxIsDynamic', g_l('import', '[isDynamic]'), true, 'defaultfont', "this.form.elements['v[is_dynamic]'].value=this.checked? 1 : 0; switchExt();"));
		$specifyDoc->setCol(0, 0, ['style' => 'padding-right:20px;'], we_html_tools::htmlFormElementTable(we_html_tools::getExtensionPopup('v[we_Extension]', (isset($v['we_Extension']) ? $v['we_Extension'] : '.html'), we_base_ContentTypes::inst()->getExtension(we_base_ContentTypes::WEDOCUMENT), 100), g_l('import', '[extension]')));

		$parts = [
			['headline' => g_l('import', '[import]'),
				'html' => $importLocs->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED],
			['headline' => (defined('OBJECT_TABLE')) ? $radioDocs : g_l('import', '[documents]'),
				'html' => $doctypeElement . ' ' . $templateElement . ' ' . $storeTo . ' ' . $specifyDoc->getHTML() . ' ' .
				we_html_tools::htmlFormElementTable($docCategories, g_l('import', '[categories]'), 'left', 'defaultfont'),
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1]
		];

		if(defined('OBJECT_TABLE')){
			$parts[] = ['headline' => $radioObjs,
				'html' => (defined('OBJECT_TABLE')) ? we_html_tools::htmlFormElementTable($CLselect->getHTML(), g_l('import', '[class]'), 'left', 'defaultfont') . ' ' .
				we_html_tools::htmlFormElementTable($objCategories, g_l('import', '[categories]'), 'left', 'defaultfont') : '',
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1
			];
		}

		$znr = -1;

		return $hdns .
			we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
			we_html_multiIconBox::getHTML('xml', $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', '[gxml_import]'));
	}

	/**
	 * Generic XML Import Step 2
	 *
	 */
	private function getGXMLImportStep2(we_base_jsCmd $jsCmd){
		$parts = [];
		$hdns = "\n";
		$v = we_base_request::_(we_base_request::STRING, 'v');
		$upload_error = false;

		if($v['rdofloc'] === 'lLocal'){
			if((!$v['import_from'] = $this->fileUploader->commitUploadedFile())){
				$upload_error = $this->fileUploader->getError();
			}
		}

		$vars = ['rdofloc', 'fserver', 'flocal', 'importDataType', 'docCategories', 'objCategories', 'store_to_id', 'is_dynamic', 'import_from', 'docType',
			'we_TemplateName', 'we_TemplateID', 'store_to_path', 'we_Extension', 'import_type', 'classID', 'sct_node', 'rcd', 'from_elem', 'to_elem', 'collision'];
		foreach($vars as $var){
			$hdns .= we_html_element::htmlHidden('v[' . $var . ']', (isset($v[$var])) ? $v[$var] : '');
		}
		$hdns .= we_html_element::htmlHiddens(['v[mode]' => 0, 'v[cid]', 'value' => -2]);

		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $v['import_from']) && is_readable($_SERVER['DOCUMENT_ROOT'] . $v['import_from']))){
			$xp = new we_xml_parser($_SERVER['DOCUMENT_ROOT'] . $v['import_from']);
			$xmlWellFormed = ($xp->parseError === '') ? true : false;

			if($xmlWellFormed){
				// Node-set with paths to the child nodes.
				$node_set = $xp->evaluate('*/child::*');
				$children = $xp->nodes[$xp->root]['children'];

				$recs = [];
				foreach($children as $key => $value){
					$flag = true;
					for($k = 1; $k < ($value + 1); $k++){
						if(!$xp->hasChildNodes($xp->root . '/' . $key . '[' . $k . ']')){
							$flag = false;
						}
					}
					if($flag){
						$recs[$key] = $value;
					}
				}
				$isSingleNode = (count($recs) == 1);
				$hasChildNode = (!empty($recs));
			}
			if($xmlWellFormed && $hasChildNode){
				$rcdSelect = new we_html_select(['name' => 'we_select',
					'class' => 'weSelect',
					(($isSingleNode) ? 'disabled' : 'style') => '',
					'onchange' => "onChangeSelectXMLNode(this);"
				]);
				$optid = 0;
				foreach($recs as $value => $text){
					if($optid == 0){
						$firstOptVal = $text;
					}
					$rcdSelect->addOption($text, $value);
					if(isset($v['rcd'])){
						if($text == $v['rcd']){
							$rcdSelect->selectOption($value);
						}
					}
					$optid++;
				}

				$tblSelect = new we_html_table([], 1, 7);
				$tblSelect->setCol(0, 1, [], $rcdSelect->getHtml());
				$tblSelect->setCol(0, 2, ['width' => 20]);
				$tblSelect->setCol(0, 3, ['class' => 'defaultfont'], g_l('import', '[num_data_sets]'));
				$tblSelect->setCol(0, 4, [], we_html_tools::htmlTextInput('v[from_iElem]', 4, 1, 5, 'align=right', 'text', 50, '', '', ($isSingleNode && ($firstOptVal == 1)) ? 1 : 0));
				$tblSelect->setCol(0, 5, ['class' => 'defaultfont'], g_l('import', '[to]'));
				$tblSelect->setCol(0, 6, [], we_html_tools::htmlTextInput('v[to_iElem]', 4, $firstOptVal, 5, 'align=right', 'text', 50, '', '', ($isSingleNode && ($firstOptVal == 1)) ? 1 : 0));

				$tblFrame = new we_html_table([], 3, 2);
				$tblFrame->setCol(0, 0, ['colspan' => 2, 'class' => 'defaultfont'], ($isSingleNode) ? we_html_tools::htmlAlertAttentionBox(g_l('import', '[well_formed]') . ' ' . g_l('import', '[select_elements]'), we_html_tools::TYPE_INFO, 530) :
						we_html_tools::htmlAlertAttentionBox(g_l('import', '[xml_valid_1]') . ' ' . $optid . ' ' . g_l('import', '[xml_valid_m2]'), we_html_tools::TYPE_INFO, 530));
				$tblFrame->setCol(1, 0, ['colspan' => 2]);
				$tblFrame->setCol(2, 1, [], $tblSelect->getHtml());

				$parts[] = ['html' => $tblFrame->getHtml(), 'noline' => 1];
			} else {
				$parts[] = ['html' => we_html_tools::htmlAlertAttentionBox(g_l('import', (!$xmlWellFormed ? '[not_well_formed]' : '[missing_child_node]')), we_html_tools::TYPE_ALERT, 530),
					'noline' => 1];
			}
		} else {
			$xmlWellFormed = $hasChildNode = false;

			if($upload_error){ // uploaded file nok: get error from uploader
				$parts[] = ['html' => we_html_tools::htmlAlertAttentionBox($upload_error, we_html_tools::TYPE_ALERT, 530), 'noline' => 1];
			} else { // file from server nok
				if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $v['import_from'])){
					$parts[] = ['html' => we_html_tools::htmlAlertAttentionBox(g_l('import', '[file_exists]') . $_SERVER['DOCUMENT_ROOT'] . $v['import_from'], we_html_tools::TYPE_ALERT, 530),
						'noline' => 1];
				} elseif(!is_readable($_SERVER['DOCUMENT_ROOT'] . $v['import_from'])){
					$parts[] = ['html' => we_html_tools::htmlAlertAttentionBox(g_l('import', '[file_readable]'), we_html_tools::TYPE_ALERT, 530), 'noline' => 1];
				}
			}
		}

		$znr = -1;

		$content = $hdns .
			we_html_element::htmlHiddens(['v[btnState_next]' => (($xmlWellFormed && $hasChildNode) ? 'enabled' : 'disabled'),
				'v[btnState_back]' => 'enabled'
			]) .
			we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
			we_html_multiIconBox::getHTML('xml', $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', '[select_data_set]'));

		return $content;
	}

	private function getGXMLImportStep3(we_base_jsCmd $jsCmd){
		$v = we_base_request::_(we_base_request::STRING, 'v');
		if(isset($v['att_pfx'])){
			$v['att_pfx'] = base64_encode($v['att_pfx']);
		}
		$records = we_base_request::_(we_base_request::RAW, 'records', []);
		$we_flds = we_base_request::_(we_base_request::RAW, 'we_flds', []);
		$attrs = we_base_request::_(we_base_request::RAW, 'attrs', []);
		foreach($attrs as $name => $value){
			$attrs[$name] = base64_encode($value);
		}

		$hdns = $this->getHdns('v', $v) .
			($records ? $this->getHdns('records', $records) : '') .
			($we_flds ? $this->getHdns('we_flds', $we_flds) : '') .
			($attrs ? $this->getHdns('attributes', $attrs) : '') .
			//$hdns .= ' => 'v[cid]', 'value' => -2));
			we_html_element::htmlHiddens(['v[pfx_fn]' => ((!isset($v['pfx_fn'])) ? 0 : $v['pfx_fn']),
				(isset($v['rdo_timestamp']) ? 'v[sTimeStamp]' : '') => (isset($v['rdo_timestamp']) ? $v['rdo_timestamp'] : ''),
				'v[btnState_next]' => ((we_base_request::_(we_base_request::INT, 'mode') != 1) ? 'enabled' : 'disabled'),
				'v[btnState_back]' => 'enabled'
		]);

		$db = new DB_WE();
		$records = $dateFields = [];

		if($v['import_type'] === 'documents'){
			$templateCode = f('SELECT c.Dat FROM ' . CONTENT_TABLE . ' c WHERE c.DocumentTable="' . stripTblPrefix(TEMPLATES_TABLE) . '" AND c.DID=' . intval($v['we_TemplateID']) . ' AND c.nHash=x\'' . md5("completeData") . '\'', '', $db);
			$tp = new we_tag_tagParser($templateCode);
			$tags = $tp->getAllTags();
			$regs = [];

			foreach($tags as $tag){
				if(preg_match('|<we:([^> /]+)|i', $tag, $regs)){
					$tagname = $regs[1];
					if(preg_match('|name="([^"]+)"|i', $tag, $regs) && ($tagname != "var") && ($tagname != "field")){
						$name = $regs[1];
						switch($tagname){
							// tags with text content, links and hrefs
							case 'input':
								if(in_array('date', we_tag_tagParser::makeArrayFromAttribs($tag))){
									$dateFields[] = $name;
								}
							case 'textarea':
							case 'href':
							case 'link':
								$records[] = $name;
								break;
						}
					}
				}
			}
			$records[] = 'Title';
			$records[] = 'Description';
			$records[] = 'Keywords';
			$records[] = 'Charset';
			$records = array_unique($records);
		} else {
			$classFields = self::getClassFields($v['classID']);
			foreach($classFields as $classField){
				if(self::isTextField($classField['type']) || self::isNumericField($classField['type']) || self::isDateField($classField['type'])){
					$records[] = $classField['name'];
				}
				if(self::isDateField($classField['type'])){
					$dateFields[] = $classField['name'];
				}
			}
		}
		$xp = new we_xml_parser($_SERVER['DOCUMENT_ROOT'] . $v['import_from']);
		$nodeSet = $xp->evaluate($xp->root . '/' . $v['rcd'] . '[1]/child::*');
		$val_nodes = $val_attrs = [];

		foreach($nodeSet as $node){
			$nodeName = $xp->nodeName($node);
			$tmp_nodes = [$nodeName => $nodeName];
			$val_nodes = $val_nodes + $tmp_nodes;

			if($xp->hasAttributes($node)){
				$val_attrs = $val_attrs + ['@n:' => g_l('import', '[none]')];
				$attributes = $xp->getAttributes($node);

				foreach($attributes as $name => $value){
					$tmp_attrs = [$name => $name];
					$val_attrs = $val_attrs + $tmp_attrs;
				}
			}
		}
		if(empty($val_attrs)){
			$val_attrs = ['@n:' => g_l('import', '[none]')];
		}

		$th = [['dat' => g_l('import', '[we_flds]')], ['dat' => g_l('import', '[rcd_flds]')], ['dat' => g_l('import', '[attributes]')]];
		$rows = [];

		reset($records);
		$i = 0;
		while(list(, $record) = each($records)){
			$hdns .= we_html_element::htmlHidden('records[' . $i . ']', $record);
			$sct_we_fields = new we_html_select(['name' => 'we_flds[' . $record . ']',
				'class' => 'weSelect',
				'onclick' => '',
				'style' => ''
			]);

			reset($val_nodes);
			$sct_we_fields->addOption('', g_l('import', '[any]'));
			foreach($val_nodes as $value => $text){
				$sct_we_fields->addOption(oldHtmlspecialchars($value), $text);
				if(isset($we_flds[$record])){
					if($value == $we_flds[$record]){
						$sct_we_fields->selectOption($value);
					}
				} elseif($value == $record){
					$sct_we_fields->selectOption($value);
				}
			}
			switch($record){
				case 'Title':
					$new_record = g_l('import', '[we_title]');
					break;
				case 'Description':
					$new_record = g_l('import', '[we_description]');
					break;
				case 'Keywords':
					$new_record = g_l('import', '[we_keywords]');
					break;
				default:
					$new_record = '';
			}
			$rows[] = [['dat' => ($new_record != '') ? $new_record : $record],
				['dat' => $sct_we_fields->getHTML()],
				['dat' => we_html_tools::htmlTextInput('attrs[' . $record . ']', 30, (isset($attrs[$record]) ? base64_decode($attrs[$record]) : ''), 255, '', 'text', 100)]
			];
			$i++;
		}

		// Associated prefix selector.
		$asocPfx = new we_html_table(['class' => 'default'], 1, 1);
		$asocPfx->setCol(0, 0, ['class' => 'defaultfont'], g_l('import', '[pfx]') . '<br/><br/>' .
			we_html_tools::htmlTextInput('v[asoc_prefix]', 30, (isset($v['asoc_prefix']) ? $v['asoc_prefix'] : g_l('import', ($v['import_type'] === 'documents' ? '[pfx_doc]' : '[pfx_obj]'))), 255, "onclick=\"top.setFormField('v[rdo_filename]', true, 'radio', 0);\"", "text", 150));

		// Assigned record or attribute field selectors.
		$rcdPfxSelect = new we_html_select(['name' => 'v[rcd_pfx]',
			'class' => 'weSelect',
			'onclick' => "top.setFormField('v[pfx_fn]', 1, 'hidden'); top.setFormField('v[rdo_filename]', true, 'radio', 1);",
			'style' => 'width: 150px'
		]);

		foreach($val_nodes as $value => $text){
			$rcdPfxSelect->addOption(oldHtmlspecialchars($value), $text);
			if(isset($v['rcd_pfx'])){
				if($text == $v['rcd_pfx']){
					$rcdPfxSelect->selectOption($value);
				}
			}
		}

		$attPfxSelect = we_html_tools::htmlTextInput('v[att_pfx]', 30, (isset($v['att_pfx']) ? base64_decode($v['att_pfx']) : ''), 255, "onclick=\"top.setFormField('v[rdo_filename]', true, 'radio', 1);\"", "text", 100);

		$asgndFld = new we_html_table(['class' => 'default'], 1, 3);
		$asgndFld->setCol(0, 0, ['class' => 'defaultfont'], g_l('import', '[rcd_fld]') . '<br/><br/>' . $rcdPfxSelect->getHTML());
		$asgndFld->setCol(0, 1, ['width' => 20], '');
		$asgndFld->setCol(0, 2, ['class' => 'defaultfont'], g_l('import', '[attributes]') . '<br/><br/>' . $attPfxSelect);

		// Filename selector.
		$fn = new we_html_table(['class' => 'default'], 3, 2);
		$fn->setCol(0, 0, ['colspan' => 2], we_html_forms::radiobutton(0, (!isset($v['rdo_filename']) ? true : ($v['rdo_filename'] == 0) ? true : false), 'v[rdo_filename]', g_l('import', '[auto]'), true, 'defaultfont', "self.document.we_form.elements['v[pfx_fn]'].value=0;"));
		$fn->setCol(1, 0, ['style' => 'padding-left:25px;'], $asocPfx->getHTML());
		$fn->setCol(2, 0, ['colspan' => 2, 'style' => 'padding-top:5px;'], we_html_forms::radiobutton(1, (!isset($v['rdo_filename']) ? false : ($v['rdo_filename'] == 1) ? true : false), "v[rdo_filename]", g_l('import', '[asgnd]'), true, "defaultfont", "self.document.we_form.elements['v[pfx_fn]'].value=1;"));
		$fn->setCol(4, 0, ['style' => 'padding-left:25px;'], $asgndFld->getHTML());

		$parts = [['html' => '<br/>' . we_html_tools::htmlDialogBorder3(510, $rows, $th, 'defaultfont')]];
		if(!empty($dateFields)){
			// Timestamp
			$tStamp = new we_html_table(['class' => 'default withSpace'], 4, 1);
			$tStamp->setCol(0, 0, ['colspan' => 2], we_html_forms::radiobutton('Unix', (!isset($v['rdo_timestamp']) ? 1 : ($v['rdo_timestamp'] === 'Unix') ? 1 : 0), 'v[rdo_timestamp]', g_l('import', '[uts]'), true, 'defaultfont', '', 0, g_l('import', '[unix_timestamp]'), 0, 384));
			$tStamp->setCol(1, 0, ['colspan' => 2], we_html_forms::radiobutton('GMT', (!isset($v['rdo_timestamp']) ? 0 : ($v['rdo_timestamp'] === 'GMT') ? 1 : 0), 'v[rdo_timestamp]', g_l('import', '[gts]'), true, 'defaultfont', '', 0, g_l('import', '[gmt_timestamp]'), 0, 384));
			$tStamp->setCol(2, 0, ['colspan' => 2], we_html_forms::radiobutton('Format', (!isset($v['rdo_timestamp']) ? 0 : ($v['rdo_timestamp'] === 'Format') ? 1 : 0), 'v[rdo_timestamp]', g_l('import', '[fts]'), true, 'defaultfont', '', 0, g_l('import', '[format_timestamp]'), 0, 384));
			$tStamp->setCol(3, 0, ['style' => 'padding-left:25px;'], we_html_tools::htmlTextInput('v[timestamp]', 30, (isset($v['timestamp']) ? $v['timestamp'] : ''), '', "onclick=\"top.setFormField('v[rdo_timestamp]', true, 'radio', 2);\"", "text", 150));

			$parts[] = ['headline' => g_l('import', '[format_date]'),
				'html' => $tStamp->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED
			];
			if(!isset($v['dateFields'])){
				$hdns .= we_html_element::htmlHidden('v[dateFields]', implode(',', $dateFields));
			}
		}

		$parts[] = ['headline' => g_l('import', '[name]'),
			'html' => $fn->getHTML(),
			'space' => we_html_multiIconBox::SPACE_MED,
			'noline' => 1
		];

		$conflict = isset($v['collision']) ? $v['collision'] : 'rename';
		$fn_colsn = new we_html_table(['class' => 'default withSpace'], 3, 1);
		$fn_colsn->setCol(0, 0, [], we_html_forms::radiobutton('rename', $conflict === 'rename', 'nameconflict', g_l('import', '[rename]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='rename';"));
		$fn_colsn->setCol(1, 0, [], we_html_forms::radiobutton('replace', $conflict === 'replace', 'nameconflict', g_l('import', '[replace]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='replace';"));
		$fn_colsn->setCol(2, 0, [], we_html_forms::radiobutton('skip', $conflict === 'skip', 'nameconflict', g_l('import', '[skip]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='skip';"));

		$parts[] = ['headline' => g_l('import', '[name_collision]'),
			'html' => $fn_colsn->getHTML(),
			'space' => we_html_multiIconBox::SPACE_MED2
		];

		$znr = -1;

		$content = $hdns .
			we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
			we_html_multiIconBox::getHTML('xml', $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', '[assign_record_fields]'));

		return $content;
	}

	private function getCSVImportStep1(we_base_jsCmd $jsCmd){
		$v = we_base_request::_(we_base_request::STRING, 'v');

		$v['import_type'] = isset($v['import_type']) ? $v['import_type'] : 'documents';

		$importFromButton = (we_base_permission::hasPerm('CAN_SELECT_EXTERNAL_FILES')) ? we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('browse_server', 'v[fserver]', '', document.we_form.elements['v[fserver]'].value)") : '';
		$inputLServer = we_html_tools::htmlTextInput('v[fserver]', 30, (isset($v['fserver']) ? $v['fserver'] : '/'), 255, "readonly onclick=\"top.setFormField('v[rdofloc]', true, 'radio', 0);\"", "text", 300);
		$importFromServer = we_html_tools::htmlFormElementTable($inputLServer, '', 'left', 'defaultfont', $importFromButton, '', "", "", "", 0);

		$inputLLocal = $this->fileUploader->getHTML();
		$importFromLocal = we_html_tools::htmlFormElementTable($inputLLocal, '', 'left', 'defaultfont', '', "", "", "", "", 0);
		$rdoLServer = we_html_forms::radiobutton('lServer', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lServer') : 1, 'v[rdofloc]', g_l('import', '[fileselect_server]'));
		$rdoLLocal = we_html_forms::radiobutton('lLocal', (isset($v['rdofloc'])) ? ($v['rdofloc'] === 'lLocal') : 0, 'v[rdofloc]', g_l('import', '[fileselect_local]'));
		$importLocs = new we_html_table(['class' => 'default'], 7, 1);
		$tblRow = 0;
		$importLocs->setCol($tblRow++, 0, [], $rdoLServer);
		$importLocs->setCol($tblRow++, 0, [], $importFromServer);
		$importLocs->setCol($tblRow++, 0, ['style' => 'padding-top:4px;'], $rdoLLocal);
		// FIXME: still need condition?
		$importLocs->setCol($tblRow++, 0, [], $this->fileUploader->getHtmlAlertBoxes());
		$importLocs->setCol($tblRow++, 0, [], $importFromLocal);

		$iptDel = we_html_tools::htmlTextInput('v[csv_seperator]', 2, (isset($v['csv_seperator']) ? (($v['csv_seperator'] != '') ? $v['csv_seperator'] : ' ') : ';'), 2, '', 'text', 30);
		$fldDel = new we_html_select(['name' => 'v[sct_csv_seperator]', 'class' => 'weSelect', 'onchange' => "this.form.elements['v[csv_seperator]'].value=this.options[this.selectedIndex].innerHTML.substr(0,2);this.selectedIndex=options[0];",
			'style' => "width: 130px"]); // FIXME: register change listener
		$fldDel->addOption('', '');
		$fldDel->addOption('semicolon', g_l('import', '[semicolon]'));
		$fldDel->addOption('comma', g_l('import', '[comma]'));
		$fldDel->addOption('colon', g_l('import', '[colon]'));
		$fldDel->addOption('tab', g_l('import', '[tab]'));
		$fldDel->addOption('space', g_l('import', '[space]'));
		if(isset($v['sct_csv_seperator'])){
			$fldDel->selectOption($v['sct_csv_seperator']);
		}

		$charSet = new we_html_select(['name' => 'v[file_format]', 'class' => 'weSelect',]);
		$charSet->addOption('win', 'Windows');
		$charSet->addOption('unix', 'Unix');
		$charSet->addOption('mac', 'Mac');
		if(isset($v['file_format'])){
			$charSet->selectOption($v['file_format']);
		}

		$txtDel = new we_html_select(['name' => 'v[csv_enclosed]', 'class' => 'weSelect', 'style' => 'width: 300px']);
		$txtDel->addOption('double_quote', g_l('import', '[double_quote]'));
		$txtDel->addOption('single_quote', g_l('import', '[single_quote]'));
		$txtDel->addOption('none', g_l('import', '[none]'));
		if(isset($v['csv_enclosed'])){
			$txtDel->selectOption($v['csv_enclosed']);
		}

		$rowDef = we_html_forms::checkbox('', (isset($v['csv_fieldnames']) ? $v['csv_fieldnames'] : true), 'checkbox_fieldnames', g_l('import', '[contains]'), true, 'defaultfont', "this.form.elements['v[csv_fieldnames]'].value=this.checked ? 1 : 0;");

		$csvSettings = new we_html_table(['class' => 'default withSpace'], 4, 1);
		$csvSettings->setCol(0, 0, ['class' => 'defaultfont'], g_l('import', '[file_format]') . '<br/><br/>' . $charSet->getHtml());
		$csvSettings->setCol(1, 0, ['class' => 'defaultfont'], g_l('import', '[field_delimiter]') . '<br/><br/>' . $iptDel . ' ' . $fldDel->getHtml());
		$csvSettings->setCol(2, 0, ['class' => 'defaultfont'], g_l('import', '[text_delimiter]') . '<br/><br/>' . $txtDel->getHtml());
		$csvSettings->setCol(3, 0, [], $rowDef);

		$parts = [
			['headline' => g_l('import', '[import]'),
				'html' => $importLocs->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED], ['headline' => g_l('import', '[field_options]'),
				'html' => $csvSettings->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1
			]
		];

		$content = we_html_element::htmlHiddens([
				'v[csv_fieldnames]' => (isset($v['csv_fieldnames'])) ? $v['csv_fieldnames'] : 1,
				'v[import_from]' => (isset($v['import_from']) ? $v['import_from'] : ''),
				'v[csv_escaped]' => (isset($v['csv_escaped'])) ? $v['csv_escaped'] : '',
				'v[collision]' => (isset($v['collision'])) ? $v['collision'] : 'rename',
				'v[csv_terminated]' => (isset($v['csv_terminated'])) ? $v['csv_terminated'] : '',
				'v[btnState_next]' => 'enabled',
				'v[btnState_back]' => 'enabled'
		]);

		$content .= we_html_multiIconBox::getHTML('csv', $parts, 30, '', -1, '', '', false, g_l('import', '[csv_import]'));

		return $content;
	}

	private function getCSVImportStep2(we_base_jsCmd $jsCmd){
		global $DB_WE;
		$v = we_base_request::_(we_base_request::STRING, 'v');
		$upload_error = false;

		$btnStateNext = 'enabled';
		$btnStateBack = 'enabled';

		if($v['rdofloc'] === 'lLocal'){
			if((!$v['import_from'] = $this->fileUploader->commitUploadedFile())){
				$upload_error = $this->fileUploader->getError();
			}
		} else {
			$realPath = realpath($_SERVER['DOCUMENT_ROOT'] . $v['import_from']);
			if(strpos($realPath, $_SERVER['DOCUMENT_ROOT']) === FALSE){
				t_e('warning', 'Acess outside document_root forbidden!', $realPath);
			} else {
				$contents = we_base_file::load($fp, 'r');
				$v['import_from'] = TEMP_DIR . 'we_csv_' . we_base_file::getUniqueId() . '.csv';
				$replacement = str_replace("\r", "\n", $contents);
				we_base_file::save($fp, $replacement, 'w+');
			}
		}

		if(isset($v['docType']) && $v['docType'] != -1 && we_base_request::_(we_base_request::BOOL, 'doctypeChanged')){
			$values = getHash('SELECT ParentID,Extension,IsDynamic,Category FROM ' . DOC_TYPES_TABLE . ' dt WHERE dt.ID=' . intval($v['docType']));
			$v['store_to_id'] = $values['ParentID'];

			$v['store_to_path'] = id_to_path($v['store_to_id']);
			$v['we_Extension'] = $values['Extension'];
			$v['is_dynamic'] = $values['IsDynamic'];
			$v['docCategories'] = $values['Category'];
		}
		$hdns = we_html_element::htmlHiddens(['v[mode]' => (isset($v['mode']) ? $v['mode'] : ''),
				'v[import_from]' => $v['import_from'],
				'v[collision]' => $v['collision'],
				'v[rdofloc]' => $v['rdofloc'],
				'v[fserver]' => $v['fserver'],
				'v[csv_fieldnames]' => $v['csv_fieldnames'],
				'v[csv_seperator]' => trim($v['csv_seperator']),
				'v[csv_enclosed]' => $v['csv_enclosed'],
				'v[csv_escaped]' => $v['csv_escaped'],
				'v[csv_terminated]' => $v['csv_terminated'],
				'v[docCategories]' => (isset($v['docCategories']) ? $v['docCategories'] : ''),
				'v[objCategories]' => (isset($v['objCategories']) ? $v['objCategories'] : ''),
				//rray('name' => 'v[store_to_id]', 'value' => (isset($v['store_to_id']) ? $v['store_to_id'] : 0))).
				'v[we_TemplateID]' => (isset($v['we_TemplateID']) ? $v['we_TemplateID'] : 0),
				'v[is_dynamic]' => (isset($v['is_dynamic']) ? $v['is_dynamic'] : 0),
				'doctypeChanged' => 0,
				'v[file_format]' => $v['file_format'],
				(defined('OBJECT_TABLE') ? '' : 'v[import_type]') => 'documents',
		]);

		$v['import_type'] = isset($v['import_type']) ? $v['import_type'] : 'documents';

		$DTselect = new we_html_select(['name' => 'v[docType]',
			'class' => 'weSelect',
			'onclick' => (defined('OBJECT_TABLE')) ? "top.setFormField('v[import_type]', true, 'radio', 0);" : '',
			'onchange' => "top.setFormField('doctypeChanged', 1, 'hidden'); top.weChangeDocType(this);",
			'style' => 'width: 300px'
		]);
		$optid = 0;
		$DTselect->insertOption($optid, -1, g_l('import', '[none]'));

		$v['docType'] = isset($v['docType']) ? $v['docType'] : -1;
		$DB_WE->query('SELECT dt.ID,dt.DocType FROM ' . DOC_TYPES_TABLE . ' dt ORDER BY dt.DocType');
		while($DB_WE->next_record()){
			$optid++;
			$DTselect->insertOption($optid, $DB_WE->f('ID'), $DB_WE->f('DocType'));
			if($v['docType'] == $DB_WE->f('ID')){
				$DTselect->selectOption($DB_WE->f('ID'));
			}
		}

		$doctypeElement = we_html_tools::htmlFormElementTable($DTselect->getHTML(), g_l('import', '[doctype]'), "left", "defaultfont");

		/*		 * * templateElement *************************************************** */
		/* $ueberschrift = (we_base_permission::hasPerm("CAN_SEE_TEMPLATES") ?
		  '<a href="javascript:goTemplate(document.we_form.elements[\'' . $idname . '\'].value)">' . g_l('import', '[template]') . '</a>' :
		  g_l('import', '[template]')); */

		$myid = (isset($v["we_TemplateID"])) ? $v["we_TemplateID"] : 0;
		//$path = f('SELECT Path FROM ' . $DB_WE->escape(TEMPLATES_TABLE) . " WHERE ID=" . intval($myid), "Path", $DB_WE);


		$weSuggest = & weSuggest::getInstance();

		$TPLselect = new we_html_select(["name" => "docTypeTemplateId",
			"class" => "weSelect",
			"onclick" => "top.setFormField('v[import_type]', true, 'radio', 0);",
			'style' => "width: 300px"
		]);

		if($v["docType"] != -1){
			$foo = f('SELECT Templates FROM ' . DOC_TYPES_TABLE . ' dt WHERE dt.ID=' . intval($v["docType"]), '', $DB_WE);
			$ids_arr = makeArrayFromCSV($foo);
			$paths_arr = id_to_path($foo, TEMPLATES_TABLE, null, true);


			$optid = 0;
			foreach($ids_arr as $templateID){
				$TPLselect->insertOption($optid, $templateID, $paths_arr[$optid]);
				++$optid;
				if(isset($v["we_TemplateID"]) && $v["we_TemplateID"] == $templateID){
					$TPLselect->selectOption($templateID);
				}
			}
		} else {
			$displayDocType = 'display:none';
			$displayNoDocType = 'display:block';
		}
		$weSuggest->setAcId("TmplPath");
		$weSuggest->setContentType('folder,' . we_base_ContentTypes::TEMPLATE);
		$weSuggest->setInput("v[we_TemplateName]", (isset($v["we_TemplateName"]) ? $v["we_TemplateName"] : ""), ["onfocus" => "self.document.we_form.elements['v[import_type]'][0].checked=true;"]);
		$weSuggest->setMaxResults(10);
		$weSuggest->setResult('noDocTypeTemplateId', $myid);
		$weSuggest->setSelector(weSuggest::DocSelector);
		$weSuggest->setTable(TEMPLATES_TABLE);
		$weSuggest->setWidth(300);
		$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_document',top.wizbody.document.we_form.elements['noDocTypeTemplateId'].value,'" . TEMPLATES_TABLE . "','noDocTypeTemplateId','v[we_TemplateName]','reload_editpage','','','" . we_base_ContentTypes::TEMPLATE . "',1)"));
		$weSuggest->setLabel(g_l('import', '[template]'));

		$templateElement = "<div id='docTypeLayer' style='" . $displayDocType . "'>" . we_html_tools::htmlFormElementTable($TPLselect->getHTML(), g_l('import', '[template]'), "left", "defaultfont") . "</div>
<div id='noDocTypeLayer' style='" . $displayNoDocType . "'>" . $weSuggest->getHTML() . "</div>";

		$weSuggest->setAcId("DirPath");
		$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
		$weSuggest->setInput("v[store_to_path]", (isset($v["store_to_path"]) ? $v["store_to_path"] : "/"), ["onfocus" => "self.document.we_form.elements['v[import_type]'][0].checked=true;"]);
		$weSuggest->setMaxResults(10);
		$weSuggest->setRequired(true);
		$weSuggest->setResult("v[store_to_id]", (isset($v["store_to_id"]) ? $v["store_to_id"] : 0));
		$weSuggest->setSelector(weSuggest::DirSelector);
		$weSuggest->setWidth(300);
		$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',top.wizbody.document.we_form.elements['v[store_to_id]'].value,'" . FILE_TABLE . "','v[store_to_id]','v[store_to_path]','','','0')"));
		$weSuggest->setLabel(g_l('import', '[import_dir]'));

		$storeTo = $weSuggest->getHTML();

		$seaPu = new we_html_table(['class' => 'default'], 2, 1);
		$seaPu->setCol(1, 0, [], we_html_forms::checkboxWithHidden(!empty($v["doc_search"]), 'v[doc_search]', g_l('weClass', '[IsSearchable]'), false, 'defaultfont'));
		$seaPu->setCol(0, 0, [], we_html_forms::checkboxWithHidden(isset($v["doc_publish"]) ? $v["doc_publish"] : true, 'v[doc_publish]', g_l('buttons_global', '[publish][value]'), false, 'defaultfont'));

		$docCategories = $this->formCategory2($jsCmd, "doc", isset($v["docCategories"]) ? $v["docCategories"] : "");
		$docCats = new we_html_table(['class' => 'default'], 1, 2);
		$docCats->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', "class" => "defaultfont lowContrast"], g_l('import', '[categories]'));
		$docCats->setCol(0, 1, ['style' => 'width:150px;'], $docCategories);

		$radioDocs = we_html_forms::radiobutton('documents', ($v["import_type"] === 'documents'), "v[import_type]", g_l('import', '[documents]'));
		$radioObjs = we_html_forms::radiobutton('objects', ($v["import_type"] === 'objects'), "v[import_type]", g_l('import', '[objects]'), true, "defaultfont", "self.document.we_form.elements['v[store_to_path]'].value='/'; WE().layout.weSuggest.checkRequired(window,self.document.we_form.elements['v[store_to_path]'].id); if(self.document.we_form.elements['v[we_TemplateName]']!==undefined) { self.document.we_form.elements['v[we_TemplateName]'].value=''; WE().layout.weSuggest.checkRequired(window,self.document.we_form.elements['v[we_TemplateName]'].id); }", (defined('OBJECT_TABLE') ? false : true));

		$optid = 0;
		if(defined('OBJECT_TABLE')){
			$v["classID"] = isset($v["classID"]) ? $v["classID"] : -1;
			$CLselect = new we_html_select(['id' => 'classID',
				"name" => "v[classID]",
				"class" => "weSelect",
				"onclick" => "top.setFormField('v[import_type]', true, 'radio', 1);",
				'onchange' => "top.onChangeSelectObject(this);",
				'style' => "width: 150px"
			]);
			$ac = implode(',', we_users_util::getAllowedClasses($DB_WE));
			if($ac){
				$DB_WE->query('SELECT o.ID,o.Text,of.ID AS FID FROM ' . OBJECT_TABLE . ' o LEFT JOIN ' . OBJECT_FILES_TABLE . ' of ON o.Text=of.Text WHERE ' . ($ac ? '  o.ID IN(' . $ac . ') AND ' : '') . ' of.IsFolder=1 AND of.ParentID=0 ORDER BY o.Text');
				while($DB_WE->next_record()){
					if(!$optid){
						$first = '/' . $DB_WE->f("Text");
						$firstID = $DB_WE->f("FID");
					}
					$optid++;
					$CLselect->insertOption($optid, $DB_WE->f("ID") . '_' . $DB_WE->f("FID"), $DB_WE->f("Text"));
					if($DB_WE->f("ID") == $v["classID"]){
						$CLselect->selectOption($DB_WE->f("ID"));
					}
				}
			} else {
				$CLselect->insertOption($optid, -1, g_l('import', '[none]'));
			}

			$objClass = new we_html_table(['class' => 'default'], 1, 2);
			$objClass->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', "class" => "defaultfont lowContrast"], g_l('import', '[class]'));
			$objClass->setCol(0, 1, ['style' => 'width:150px;'], $CLselect->getHTML());


			$weSuggest->setAcId('ObjPath');
			$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
			$weSuggest->setInput("v[obj_path]", (isset($v["obj_path"]) ? $v["obj_path"] : isset($first) ? $first : '/'), ["onfocus" => "self.document.we_form.elements['v[import_type]'][1].checked=true;"]);
			$weSuggest->setMaxResults(10);
			$weSuggest->setRequired(true);
			$weSuggest->setResult("v[obj_path_id]", (isset($v["obj_path_id"]) ? $v["obj_path_id"] : (isset($firstID) ? $firstID : 0)));
			$weSuggest->setSelector(weSuggest::DirSelector);
			$weSuggest->setTable(OBJECT_FILES_TABLE);
			$weSuggest->setWidth(300);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',document.we_form.elements['v[obj_path]'].value,'" . OBJECT_FILES_TABLE . "','v[obj_path_id]','v[obj_path]','','',document.we_form.elements['v[classID]'].value.split('_')[1])"));
			$weSuggest->setLabel(g_l('import', '[import_dir]'));

			$objStoreTo = $weSuggest->getHTML();

			$objSeaPu = new we_html_table(['class' => 'default'], 2, 1);
			$objSeaPu->setCol(1, 0, [], we_html_forms::checkboxWithHidden(!empty($v["obj_search"]), 'v[obj_search]', g_l('weClass', '[IsSearchable]'), false, 'defaultfont'));
			$objSeaPu->setCol(0, 0, [], we_html_forms::checkboxWithHidden(isset($v["obj_publish"]) ? $v["obj_publish"] : true, 'v[obj_publish]', g_l('buttons_global', '[publish][value]'), false, 'defaultfont'));
			$objCategories = $this->formCategory2($jsCmd, "obj", isset($v["objCategories"]) ? $v["objCategories"] : "");
			$objCats = new we_html_table(['class' => 'default'], 1, 2);
			$objCats->setCol(0, 0, ['style' => 'vertical-align:top;width:130px;', "class" => "defaultfont lowContrast"], g_l('import', '[categories]'));
			$objCats->setCol(0, 1, ['style' => 'width:150px;'], $objCategories);

			$objects = new we_html_table(['class' => 'default withBigSpace'], 3, 2);
			$objects->setCol(0, 0, ["colspan" => 3, 'style' => 'width:50px;'], $radioObjs);
			$objects->setCol(1, 1, [], $objClass->getHTML());
			$objects->setCol(2, 1, [], $objCats->getHTML());
		}

		$specifyDoc = new we_html_table(['class' => 'default'], 1, 2);
		$specifyDoc->setCol(0, 1, ['style' => 'vertical-align:bottom;'], we_html_forms::checkbox(3, (isset($v["is_dynamic"]) ? $v["is_dynamic"] : 0), "chbxIsDynamic", g_l('import', '[isDynamic]'), true, "defaultfont", "this.form.elements['v[is_dynamic]'].value=this.checked? 1 : 0; top.switchExt();"));
		$specifyDoc->setCol(0, 0, ['style' => 'padding-right:20px;'], we_html_tools::htmlFormElementTable(we_html_tools::getExtensionPopup("v[we_Extension]", (isset($v["we_Extension"]) ? $v["we_Extension"] : ".html"), we_base_ContentTypes::inst()->getExtension(we_base_ContentTypes::WEDOCUMENT), 100), g_l('import', '[extension]')));

		if((file_exists($_SERVER['DOCUMENT_ROOT'] . $v["import_from"]) && is_readable($_SERVER['DOCUMENT_ROOT'] . $v["import_from"]))){
			$parts = [[
				"headline" => (defined('OBJECT_TABLE')) ? $radioDocs : g_l('import', '[documents]'),
				"html" => $doctypeElement .
				$templateElement .
				$storeTo .
				$specifyDoc->getHTML() .
				$seaPu->getHtml() .
				we_html_tools::htmlFormElementTable($docCategories, g_l('import', '[categories]'), "left", "defaultfont"),
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1
				]
			];
			if(defined('OBJECT_TABLE')){
				$parts[] = ["headline" => $radioObjs,
					"html" => we_html_tools::htmlFormElementTable($CLselect->getHTML(), g_l('import', '[class]'), "left", "defaultfont") .
					$objStoreTo .
					$objSeaPu->getHtml() .
					we_html_tools::htmlFormElementTable($objCategories, g_l('import', '[categories]'), "left", "defaultfont"),
					'space' => we_html_multiIconBox::SPACE_MED,
					'noline' => 1
				];
			}
		} else {
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $v["import_from"])){
				$parts = [[
					"html" => we_html_tools::htmlAlertAttentionBox(g_l('import', '[file_exists]') . $_SERVER['DOCUMENT_ROOT'] . $v["import_from"], we_html_tools::TYPE_ALERT, 530),
					'noline' => 1
					]
				];
				$btnStateNext = 'disabled';
				$btnStateBack = 'enabled';
			} else if(!is_readable($_SERVER['DOCUMENT_ROOT'] . $v["import_from"])){
				$parts = [[
					"html" => we_html_tools::htmlAlertAttentionBox(g_l('import', '[file_readable]'), we_html_tools::TYPE_ALERT, 530),
					'noline' => 1
					]
				];
				$btnStateNext = 'disabled';
			} else {
				$parts = [];
			}
		}

		return $hdns . we_html_element::htmlHiddens(['v[btnState_next]' => $btnStateNext, 'v[btnState_back]' => $btnStateBack]) .
			we_html_multiIconBox::getHTML('csv', $parts, 30, "", -1, "", "", false, g_l('import', '[csv_import]'));
	}

	private function getCSVImportStep3(we_base_jsCmd $jsCmd){
		$tid = we_base_request::_(we_base_request::INT, 'v', 0, 'we_TemplateID');
		$tname = we_base_request::_(we_base_request::FILE, 'v', '', 'we_TemplateName');
		if($tname && !$tid){
			$_REQUEST["v"]['we_TemplateID'] = path_to_id($tname, TEMPLATES_TABLE, $GLOBALS['DB_WE']);
		}

		$v = we_base_request::_(we_base_request::STRING, 'v');

		$records = we_base_request::_(we_base_request::RAW, 'records', []);
		$we_flds = we_base_request::_(we_base_request::STRING, 'we_flds', []);
		$attrs = we_base_request::_(we_base_request::STRING, 'attrs', []);

		$csvFile = $_SERVER['DOCUMENT_ROOT'] . we_base_request::_(we_base_request::FILE, 'v', '', "import_from");
		if(file_exists($csvFile) && is_readable($csvFile)){
			$data = we_base_file::loadPart($csvFile);
			$encoding = mb_detect_encoding($data, 'UTF-8,ISO-8859-1,ISO-8859-15');
		}

		$hdns = $this->getHdns("v", we_base_request::_(we_base_request::STRING, "v")) .
			($records ? $this->getHdns("records", $records) : "") .
			($we_flds ? $this->getHdns("we_flds", $we_flds) : "") .
			($attrs ? $this->getHdns("attrs", $attrs) : "") .
			we_html_element::htmlHiddens(["v[startCSVImport]" => we_base_request::_(we_base_request::BOOL, 'v', false, "startCSVImport"),
				"v[cid]" => -2,
				"v[encoding]" => $encoding,
				"v[pfx_fn]" => we_base_request::_(we_base_request::STRING, 'v', 0, "pfx_fn")]) . /* rdo_timestamp is a string: 'GMT', 'UNIX' or 'Format' */
			(($tm = we_base_request::_(we_base_request::STRING, 'v', '', 'rdo_timestamp')) !== false ? we_html_element::htmlHidden("v[sTimeStamp]", $tm) : '');

		$db = new DB_WE();
		$records = $dateFields = [];

		if(we_base_request::_(we_base_request::STRING, 'v', '', "import_type") === "documents"){
			$templateCode = f('SELECT c.Dat FROM ' . CONTENT_TABLE . ' c WHERE c.DocumentTable="' . stripTblPrefix(TEMPLATES_TABLE) . '" AND c.DID=' . we_base_request::_(we_base_request::INT, 'v', 0, 'we_TemplateID') . ' AND c.nHash=x\'' . md5("completeData") . '\'', '', $db);
			$tp = new we_tag_tagParser($templateCode);

			$tags = $tp->getAllTags();

			if($tags){
				$regs = [];
				foreach($tags as $tag){
					if(preg_match('|<we:([^> /]+)|i', $tag, $regs)){
						$tagname = $regs[1];
						if(preg_match('|name="([^"]+)"|i', $tag, $regs) && ($tagname != "var") && ($tagname != "field")){
							$name = $regs[1];
							switch($tagname){
								// tags with text content, links and hrefs
								case "input":
									if(in_array('date', we_tag_tagParser::makeArrayFromAttribs($tag))){
										$dateFields[] = $name;
									}
								case "textarea":
								case "href":
								case "link":
									$records[] = $name;
									break;
							}
						}
					}
				}
				$records = array_unique($records);
			} else {
				$records[] = "Title";
				$records[] = "Description";
				$records[] = "Keywords";
			}
		} else {
			list($class) = explode('_', we_base_request::_(we_base_request::STRING, 'v', 0, "classID"));
			$classFields = self::getClassFields($class);
			foreach($classFields as $classField){
				if(self::isTextField($classField["type"]) || self::isNumericField($classField["type"]) || self::isDateField($classField["type"])){
					$records[] = $classField['name'];
				}
				if(self::isDateField($classField["type"])){
					$dateFields[] = $classField['name'];
				}
			}
		}

		if(file_exists($csvFile) && is_readable($csvFile)){
			switch(we_base_request::_(we_base_request::STRING, 'v', '', 'csv_enclosed')){
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

			$cp->setData($data);
			$cp->setDelim(we_base_request::_(we_base_request::RAW_CHECKED, 'v', '', 'csv_seperator'));
			$cp->setEnclosure($encl);
			$cp->setFromCharset($encoding);
			$cp->parseCSV();
			$num = count($cp->FieldNames);
			$recs = [];
			for($c = 0; $c < $num; $c++){
				$recs[$c] = $cp->CSVFieldName($c);
			}
			$val_nodes = [];
			for($i = 0; $i < count($recs); $i++){
				if(we_base_request::_(we_base_request::BOOL, 'v', false, 'csv_fieldnames') && $recs[$i] != ""){
					$val_nodes[$recs[$i]] = $recs[$i];
				} else {
					$val_nodes['f_' . $i] = g_l('import', '[record_field]') . ($i + 1);
				}
			}
		}

		$th = [['dat' => g_l('import', '[we_flds]')], ['dat' => g_l('import', '[rcd_flds]')]];
		$rows = [];

		$i = 0;
		foreach($records as $record){
			$hdns .= we_html_element::htmlHidden("records[$i]", $record);
			$sct_we_fields = new we_html_select(["name" => 'we_flds[' . $record . ']',
				"class" => "weSelect",
				"onclick" => "",
				'style' => ""
			]);
			$sct_we_fields->addOption("", g_l('import', '[any]'));
			foreach($val_nodes as $value => $text){
				$b64_value = we_base_request::_(we_base_request::BOOL, 'v', false, "startCSVImport") ? $value : base64_encode($value);
				$sct_we_fields->addOption($b64_value, oldHtmlspecialchars($text));
				if(isset($we_flds[$record])){
					if($value == base64_decode($we_flds[$record])){
						$sct_we_fields->selectOption($b64_value);
					}
				} elseif($value == $record){
					$sct_we_fields->selectOption($b64_value);
				}
			}

			switch($record){
				case "Title":
					$new_record = g_l('import', '[we_title]');
					break;
				case "Description":
					$new_record = g_l('import', '[we_description]');
					break;
				case "Keywords":
					$new_record = g_l('import', '[we_keywords]');
					break;
				default:
					$new_record = '';
			}
			$rows[] = [['dat' => ($new_record != "") ? $new_record : $record], ['dat' => $sct_we_fields->getHTML()]];
			++$i;
		}

		// Associated prefix selector.
		$asocPfx = new we_html_table(['class' => 'default'], 1, 1);
		$asocPfx->setCol(0, 0, ['class' => 'defaultfont'], g_l('import', '[pfx]') . "<br/><br/>" .
			we_html_tools::htmlTextInput("v[asoc_prefix]", 30, (isset($v["asoc_prefix"]) ? $v["asoc_prefix"] : g_l('import', ($v["import_type"] === "documents" ? '[pfx_doc]' : '[pfx_obj]'))), 255, "onclick=\"top.setFormField('v[rdo_filename]', true, 'radio', 0);\"", "text", 150));

		// Assigned record or attribute field selectors.
		$rcdPfxSelect = new we_html_select(["name" => "v[rcd_pfx]",
			"class" => "weSelect",
			"onclick" => "top.setFormField('v[pfx_fn]', 1, 'hidden'); top.setFormField('v[rdo_filename]', true, 'radio', 1);",
			'style' => "width: 150px"
		]);

		foreach($val_nodes as $value => $text){
			$rcdPfxSelect->addOption(oldHtmlspecialchars($value), $text);
			if($value == we_base_request::_(we_base_request::STRING, 'v', '', "rcd_pfx")){
				$rcdPfxSelect->selectOption($value);
			}
		}

		$asgndFld = new we_html_table(['class' => 'default'], 1, 1);
		$asgndFld->setCol(0, 0, ['class' => 'defaultfont'], g_l('import', '[rcd_fld]') . "<br/><br/>" . $rcdPfxSelect->getHTML());

		// Filename selector.
		$fn = new we_html_table(['class' => 'default'], 5, 1);
		$fn->setCol(0, 0, ["colspan" => 2], we_html_forms::radiobutton(0, (!isset($v["rdo_filename"]) ? true : ($v["rdo_filename"] == 0) ? true : false), "v[rdo_filename]", g_l('import', '[auto]'), true, "defaultfont", "self.document.we_form.elements['v[pfx_fn]'].value=0;"));
		$fn->setCol(1, 0, ['style' => 'padding-left:25px;'], $asocPfx->getHTML());
		$fn->setCol(2, 0, ["height" => 5], "");
		$fn->setCol(3, 0, ["colspan" => 2], we_html_forms::radiobutton(1, (!isset($v["rdo_filename"]) ? false : ($v["rdo_filename"] == 1) ? true : false), "v[rdo_filename]", g_l('import', '[asgnd]'), true, "defaultfont", "self.document.we_form.elements['v[pfx_fn]'].value=1;"));
		$fn->setCol(4, 0, ['style' => 'padding-left:25px;'], $asgndFld->getHTML());

		$parts = [["html" => "<br/>" . we_html_tools::htmlDialogBorder3(510, $rows, $th, "defaultfont")]];


		if(!empty($dateFields)){
			// Timestamp
			$tStamp = new we_html_table(['class' => 'default withSpace'], 4, 1);
			$tStamp->setCol(0, 0, ["colspan" => 2], we_html_forms::radiobutton("Unix", (!isset($v["rdo_timestamp"]) ? 1 : ($v["rdo_timestamp"] === "Unix") ? 1 : 0), "v[rdo_timestamp]", g_l('import', '[uts]'), true, "defaultfont", "", 0, g_l('import', '[unix_timestamp]'), 0, 384));
			$tStamp->setCol(1, 0, ["colspan" => 2], we_html_forms::radiobutton("GMT", (!isset($v["rdo_timestamp"]) ? 0 : ($v["rdo_timestamp"] === "GMT") ? 1 : 0), "v[rdo_timestamp]", g_l('import', '[gts]'), true, "defaultfont", "", 0, g_l('import', '[gmt_timestamp]'), 0, 384));
			$tStamp->setCol(2, 0, ["colspan" => 2], we_html_forms::radiobutton("Format", (!isset($v["rdo_timestamp"]) ? 0 : ($v["rdo_timestamp"] === "Format") ? 1 : 0), "v[rdo_timestamp]", g_l('import', '[fts]'), true, "defaultfont", "", 0, g_l('import', '[format_timestamp]'), 0, 384));
			$tStamp->setCol(3, 0, ['style' => 'padding-left:25px;'], we_html_tools::htmlTextInput("v[timestamp]", 30, (isset($v["timestamp"]) ? $v["timestamp"] : ""), "", "onclick=\"top.setFormField('v[rdo_timestamp]', true, 'radio', 2);\"", "text", 150));

			$parts[] = ["headline" => g_l('import', '[format_date]'),
				"html" => $tStamp->getHTML(),
				'space' => we_html_multiIconBox::SPACE_MED2
			];
			if(!isset($v["dateFields"])){
				$hdns .= we_html_element::htmlHidden("v[dateFields]", implode(',', $dateFields));
			}
		}

		$conflict = isset($v["collision"]) ? $v["collision"] : 'rename';
		$fn_colsn = new we_html_table(['class' => 'default withSpace'], 3, 1);
		$fn_colsn->setCol(0, 0, [], we_html_forms::radiobutton("rename", $conflict === "rename", "nameconflict", g_l('import', '[rename]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='rename';"));
		$fn_colsn->setCol(1, 0, [], we_html_forms::radiobutton("replace", $conflict === "replace", "nameconflict", g_l('import', '[replace]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='replace';"));
		$fn_colsn->setCol(2, 0, [], we_html_forms::radiobutton("skip", $conflict === "skip", "nameconflict", g_l('import', '[skip]'), true, 'defaultfont', "self.document.we_form.elements['v[collision]'].value='skip';"));

		$parts[] = ['headline' => g_l('import', '[name_collision]'),
			'html' => $fn_colsn->getHTML(),
			'space' => we_html_multiIconBox::SPACE_MED2
		];

		$parts[] = ['headline' => g_l('import', '[name]'),
			'html' => $fn->getHTML(),
			'space' => we_html_multiIconBox::SPACE_MED2
		];

		$znr = -1;

		$content = $hdns .
			we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
			we_html_multiIconBox::getHTML('csv', $parts, 30, '', $znr, g_l('weClass', '[moreProps]'), g_l('weClass', '[lessProps]'), false, g_l('import', '[assign_record_fields]'));

		return $content;
	}

	private function formWeChooser($table = FILE_TABLE, $width = '', $rootDirID = 0, $IDName = 'ID', $IDValue = 0, $Pathname = 'Path', $Pathvalue = '/', $cmd = ''){
		$Pathvalue = (empty($Pathvalue) ? f('SELECT Path FROM ' . escape_sql_query($table) . ' WHERE ID=' . intval($IDValue), '', new DB_WE()) : $Pathvalue);
		$button = we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_file',document.we_form.elements['" . $IDName . "'].value,'" . $table . "','" . $IDName . "','" . $Pathname . "','" . $cmd . "','','" . $rootDirID . "')");
		return we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput($Pathname, 30, $Pathvalue, '', 'readonly', 'text', $width, 0), '', 'left', 'defaultfont', we_html_element::htmlHidden($IDName, $IDValue), $button);
	}

	public static function getFrameset(){
		$wizard = new self();

		$what = we_base_request::_(we_base_request::STRING, "pnt", 'wizframeset');
		$type = we_base_request::_(we_base_request::STRING, "type", '');
		$step = we_base_request::_(we_base_request::INT, "step", 0);
		$mode = we_base_request::_(we_base_request::INT, "mode", 0);

		if($type && ($step == 1 || $step == 2) && $what === 'wizbody'){
			$acceptedMime = $acceptedExt = [];
			switch($type){
				case we_import_functions::TYPE_GENERIC_XML:
					$name = 'uploaded_xml_file';
					$acceptedMime = ['text/xml'];
					$acceptedExt = ['.xml'];
					$genericFileNameTemp = TEMP_DIR . 'we_xml_' . we_fileupload::REPLACE_BY_UNIQUEID . '.xml';
					break;
				case we_import_functions::TYPE_WE_XML:
					$name = 'uploaded_xml_file';
					$acceptedMime = ['text/xml'];
					$acceptedExt = ['.xml'];
					$genericFileNameTemp = TEMP_DIR . we_fileupload::REPLACE_BY_UNIQUEID . '_w.xml';
					break;
				case we_import_functions::TYPE_CSV:
					$name = 'uploaded_csv_file';
					$acceptedExt = ['.csv', '.txt'];
					$genericFileNameTemp = TEMP_DIR . 'we_csv_' . we_fileupload::REPLACE_BY_UNIQUEID . '.csv';
					break;
				default:
					break;
			}

			switch($step){
				case 2:
					$wizard->fileUploader = new we_fileupload_resp_base();
					break;
				default:
					$wizard->fileUploader = new we_fileupload_ui_base($name);
					$wizard->fileUploader->setNextCmd('fileupload_callback' . $type);
					$wizard->fileUploader->setExternalUiElements(['contentName' => 'wizbody', 'btnUploadName' => 'next_btn']);
					$wizard->fileUploader->setCmdFileSelectOnclick('fileupload_doOnFileSelect');
					$wizard->fileUploader->setInternalProgress(['isInternalProgress' => true, 'width' => 200]);
					$wizard->fileUploader->setGenericFileName($genericFileNameTemp);
					$wizard->fileUploader->setDimensions(['width' => 410, 'marginTop' => 12]);
			}
			$wizard->fileUploader->setTypeCondition('accepted', $acceptedMime, $acceptedExt);
		}

		echo $wizard->getHTML($what, $type, $step, $mode);
	}

	public static function getJSLangConsts(){
		return 'WE().consts.g_l.import={
	backup_file_found:\'' . g_l('import', '[backup_file_found]') . '\',
	backup_file_found_question:\'' . g_l('import', '[backup_file_found_question]') . '\',
	errorEmptyDateFormat:"' . we_message_reporting::prepareMsgForJS(g_l('siteimport', '[errorEmptyDateFormat]')) . '",
	format_timestamp:"' . g_l('import', '[format_timestamp]') . '",
	invalid_path:"' . g_l('import', '[invalid_path]') . '",
	nameOfTemplateAlert:"' . g_l('siteimport', '[nameOfTemplateAlert]') . '",
	num_elements:"' . g_l('import', '[num_elements]') . '",
	pleaseSelectTemplateAlert:"' . g_l('siteimport', '[pleaseSelectTemplateAlert]') . '",
	root_dir_1:"' . g_l('importFiles', '[root_dir_1]') . '",
	root_dir_2:"' . g_l('importFiles', '[root_dir_2]') . '",
	root_dir_3:"' . g_l('importFiles', '[root_dir_3]') . '",
	select_docType:"' . g_l('import', '[select_docType]') . '",
	select_seperator:"' . g_l('import', '[select_seperator]') . '",
	select_source_file:"' . g_l('import', '[select_source_file]') . '",
	startEndMarkAlert:"' . g_l('siteimport', '[startEndMarkAlert]') . '",
	we_filename_notValid:"' . g_l('alert', '[we_filename_notValid]') . '",
};';
	}

}
