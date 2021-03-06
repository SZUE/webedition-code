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
class we_backup_XMLParser{
	var $parseError;
	var $Nodes = array();
	var $Handle = 0;
	var $Mark;
	var $XPaths = array();

	function parse(&$data, $encoding = 'ISO-8859-1'){
		if(empty($data)){
			return FALSE;
		}
		//parser only supports ISO-8859-1, US-ASCII, UTF-8
		switch($encoding){
			case 'US-ASCII':
			case 'ISO-8859-1':
			case 'UTF-8':
				break;
			default:
				$encoding = 'ISO-8859-1';
		}
		// Initialize the parser
		$parser = xml_parser_create($encoding);

		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

		//  XML_OPTION_SKIP_WHITE has to be set to 0
		// in php4 if the option is set to 1, all new line characters
		// will be removed from node content, even from a CDATA section
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 0);

		if(!xml_parse_into_struct($parser, $data, $this->Nodes)){
			$this->parseError = xml_get_current_line_number($parser) . ': ' . xml_Error_string(xml_get_error_code($parser)) . ' - ' .
				xml_get_current_byte_index($parser) . '(' . substr($data, xml_get_current_byte_index($parser) - 10, 20) . ')';
			return FALSE;
		}

		xml_parser_free($parser);
		//$this->normalize();
	}

	function normalize(){

		$newNodes = array();

		$count = 0;
		$level = array();
		$level_prefix = 'l';

		foreach($this->Nodes as $k => $v){

			if($v['type'] === 'open' || $v['type'] === 'complete'){

				$new = array();

				$new['name'] = $v['tag'];

				if(isset($v['attributes'])){
					$new['attributes'] = $v['attributes'];
				}

				if(isset($v['value'])){
					$new['value'] = $v['value'];
				}

				if($count && isset($level[$level_prefix . $v['level']])){
					$newNodes[$level[$level_prefix . $v['level']]]['next'] = $count;
				}

				$level[$level_prefix . $v['level']] = $count;

				$newNodes[$count] = $new;
				$count++;
			}

			if($v['type'] === 'close'){
				array_pop($level);
			}
		}

		unset($this->Nodes);
		$this->Nodes = $newNodes;
	}

	function normalizeWithXpaths(){

		$newNodes = array();

		$xpaths = array();
		$parent_xpaths = array();
		$element_counter = array();



		$count = 0;
		$level = array();

		foreach($this->Nodes as $k => $v){

			if($v['type'] === 'open' || $v['type'] === 'complete'){

				$new = array();

				$new['name'] = $v['tag'];

				if(isset($v['attributes'])){
					$new['attributes'] = $v['attributes'];
				}

				if(isset($v['value'])){
					$new['value'] = $v['value'];
				}


				if($count && isset($level[$v['level']])){
					$newNodes[$level[$v['level']]]['next'] = $count;
				}

				// xpath --------------------
				$parent = ($v['level'] > 1 ? $parent_xpaths[$v['level'] - 1] : '') . '/';

				if(!isset($element_counter[$v['level']])){
					$element_counter[$v['level']] = array();
				}

				if(isset($element_counter[$v['level']][$v['tag']])){
					$element_counter[$v['level']][$v['tag']] ++;
				} else {
					$element_counter[$v['level']][$v['tag']] = 1;
				}

				$xpath = $parent . $v['tag'] . '[' . $element_counter[$v['level']][$v['tag']] . ']';

				//$xpaths[$xpath] = $count;
				$xpaths[$count] = $xpath;

				if($v['type'] === 'open'){
					$parent_xpaths[$v['level']] = $xpath;
				}
				// xpath ends -----------------------------


				$level[$v['level']] = $count;

				$newNodes[$count] = $new;
				$count++;
			}

			if($v['type'] === 'close'){
				array_pop($level);
				// xpath --------------
				array_pop($parent_xpaths);
				array_pop($element_counter);
				// xpath ends ---------
			}
		}

		unset($this->Nodes);
		$this->Nodes = $newNodes;
	}

	function next(){
		if($this->Handle < (count($this->Nodes) - 1)){
			$this->Handle++;
		} else {
			return null;
		}
	}

	function nextSibling(){

		if(isset($this->Nodes[$this->Handle]['next'])){
			$this->Handle = $this->Nodes[$this->Handle]['next'];
			return true;
		} else {
			return false;
		}
	}

	function seek($position){
		$this->Handle = $position;
	}

	function getNodeName(){
		return isset($this->Nodes[$this->Handle]['name']) ? $this->Nodes[$this->Handle]['name'] : '';
	}

	function getNodeData(){
		return (isset($this->Nodes[$this->Handle]['value'])) ?
			$this->Nodes[$this->Handle]['value'] :
			null;
	}

	function getNodeAttributes(){
		return (isset($this->Nodes[$this->Handle]['attributes'])) ?
			$this->Nodes[$this->Handle]['attributes'] :
			null;
	}

	function addMark($name){

		$this->Mark[$name] = $this->Handle;
	}

	function gotoMark($name){

		if(isset($this->Mark[$name])){
			$this->Handle = $this->Mark[$name];
			return true;
		}

		return false;
	}

	function deleteMark($name){
		if(isset($this->Mark[$name])){
			unset($this->Mark[$name]);
			return true;
		}
		return false;
	}

	function getChildren($node_id, array &$children){

		$this->addMark('getChildren');

		$this->seek($node_id);

		$this->next();
		$children[] = $this->Handle;
		while($this->nextSibling()){
			$children[] = $this->Handle;
		}

		$this->gotoMark('getChildren');
		$this->deleteMark('getChildren');

		if(!empty($children)){
			return true;
		}
		return false;
	}

	function hasChildren($node_id){

		$return = false;
		$this->addMark('hasChildern');
		$this->seek($node_id);
		$next_id = 0;
		$next_sibling_id = 0;


		if($this->next()){
			$next_id = $this->Handle;
		}

		$this->seek($node_id);
		if($this->nextSibling()){
			$next_sibling_id = $this->Handle;
		}

		if($next_id != $next_sibling_id){
			$return = true;
		}

		$this->gotoMark('hasChildern');
		$this->deleteMark('hasChildern');
		return false;
	}

}
