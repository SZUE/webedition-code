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
class we_glossary_frames extends we_modules_frame{

	public function __construct($frameset){
		parent::__construct($frameset);
		$this->module = "glossary";
		$this->treeDefaultWidth = 280;

		$this->Tree = new we_glossary_tree($this->frameset, "top.content", "top.content", "top.content.cmd");
		$this->View = new we_glossary_view($frameset);
	}

	function getHTML($what = '', $mode = '', $step = 0){
		switch($what){
			case 'frameset':
				return $this->getHTMLFrameset($this->Tree->getJSTreeCode());
			default:
				return parent::getHTML($what, $mode, $step);
		}
	}

	protected function getHTMLEditorHeader($mode = 0){
		if(we_base_request::_(we_base_request::BOOL, "home")){
			//FIXME: remove
			return parent::getHTMLEditorHeader(0);
		}
		$cmdid = we_base_request::_(we_base_request::STRING, 'cmdid');
		if($cmdid && !is_numeric($cmdid)){
			$this->View->Glossary->Language = substr($cmdid, 0, 5);
		}
		switch(we_base_request::_(we_base_request::STRING, 'cmd')){
			// Folder View
			case 'glossary_view_folder':
				return we_glossary_frameEditorFolder::Header($this);
			// Type View
			case 'glossary_view_type':
				return we_glossary_frameEditorType::Header($this);
			// Exception View
			case 'glossary_view_exception':
			case 'save_exception':
				return we_glossary_frameEditorException::Header($this);
			// Item View
			default:
				return we_glossary_frameEditorItem::Header($this);
		}
	}

	protected function getHTMLEditorBody(){
		if(we_base_request::_(we_base_request::BOOL, 'home')){
			return $this->View->getHomeScreen();
		}
		$cmdid = we_base_request::_(we_base_request::STRING, 'cmdid');
		if($cmdid && !is_numeric($cmdid)){
			$this->View->Glossary->Language = substr($cmdid, 0, 5);
		}
		switch(we_base_request::_(we_base_request::STRING, 'cmd')){
			// Folder View
			case 'glossary_view_folder':
				return we_glossary_frameEditorFolder::Body($this);
			// Type View
			case 'glossary_view_type':
				return we_glossary_frameEditorType::Body($this);
			// Exception View
			case 'glossary_view_exception':
			case 'save_exception':
				return we_glossary_frameEditorException::Body($this);
			// Item View
			default:
				return we_glossary_frameEditorItem::Body($this);
		}
	}

	protected function getHTMLEditorFooter($btn_cmd = '', $extraHead = ''){
		if(we_base_request::_(we_base_request::BOOL, "home")){
			return parent::getHTMLEditorFooter('');
		}
		$cmdid = we_base_request::_(we_base_request::STRING, 'cmdid');
		if($cmdid && !is_numeric($cmdid)){
			$this->View->Glossary->Language = substr($cmdid, 0, 5);
		}
		switch(we_base_request::_(we_base_request::STRING, 'cmd')){
			// Folder View
			case 'glossary_view_folder':
				return we_glossary_frameEditorFolder::Footer($this);
			// Type View
			case 'glossary_view_type':
				return we_glossary_frameEditorType::Footer($this);
			// Exception View
			case 'glossary_view_exception':
			case 'save_exception':
				return we_glossary_frameEditorException::Footer($this);
			// Item View
			default:
				return we_glossary_frameEditorItem::Footer($this);
		}
	}

	protected function getHTMLCmd(){
		if(($pid = we_base_request::_(we_base_request::RAW, "pid")) === false){
			return $this->getHTMLDocument(we_html_element::htmlBody());
		}

		$offset = we_base_request::_(we_base_request::INT, "offset", 0);

		return $this->getHTMLDocument(
				we_html_element::htmlBody(array(), we_html_element::htmlForm(array("name" => "we_form"), we_html_element::htmlHiddens(array(
							"pnt" => "cmd",
							"cmd" => "no_cmd"))
					)
				), we_html_element::jsElement(
					($pid ?
						'' :
						'top.content.treeData.clear();
top.content.treeData.add(top.content.node.prototype.rootEntry(\'' . $pid . '\',\'root\',\'root\'));'
					) . $this->Tree->getJSLoadTree(!$pid, we_glossary_tree::getItems($pid, $offset, $this->Tree->default_segment)))
		);
	}

}
