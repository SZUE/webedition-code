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
class weOrderContainer{
	// DEBUG
	var $debug = false;
	// private Target Frame
	var $targetFrame = "";
	// private containerId
	var $containerId = "";
	// private containerType
	var $containerType = "";

	public function __construct($targetFrame, $id, $type = "div"){
		$this->targetFrame = $targetFrame;
		$this->containerId = $id;
		$this->containerType = $type;
	}

	function getJS($jsPath){
		if(!defined('weOrderContainer_JS_loaded')){
			$src = we_html_element::jsScript($jsPath . '/weOrderContainer.js');
			define("weOrderContainer_JS_loaded", true);
		} else {
			$src = '';
		}
		$src .= we_html_element::jsElement('var ' . $this->containerId . ' = new weOrderContainer("' . $this->containerId . '");');

		return $src;
	}

// end: getJs

	function getContainer($attribs = array()){

		$style = ($this->debug ? ' style="display: block; border: 1px #ff0000 solid;"' : '');

		$attrib = "";
		foreach($attribs as $name => $value){
			$attrib .= " " . $name . "=\"" . $value . "\"";
		}

		$src = '<' . $this->containerType . ' id="' . $this->containerId . '"' . $style . $attrib . '>'
			. '</' . $this->containerType . '>';

		return $src;
	}

// end: getContainer

	function getCmd($mode, $uniqueid = false, $afterid = false){
		$prefix = $this->targetFrame . "." . $this->containerId;
		$afterid = ($afterid ? "'" . $afterid . "'" : "null");

		switch(strtolower($mode)){
			case 'add':
				$cmd = $prefix . ".add(document, '" . $uniqueid . "', $afterid);";
				break;
			case 'reload':
				$cmd = $prefix . ".reload(document, '" . $uniqueid . "');";
				break;
			case 'delete':
			case 'del':
				$cmd = $prefix . ".del('" . $uniqueid . "');";
				break;
			case 'moveup':
			case 'up':
				$cmd = $prefix . ".up('" . $uniqueid . "');";
				break;
			case 'movedown':
			case 'down':
				$cmd = $prefix . ".down('" . $uniqueid . "');";
				break;
			default:
				$cmd = "";
				break;
		}

		return $cmd;
	}

// end: getCmd

	function getResponse($mode, $uniqueid, $string = "", $afterid = false){

		$cmd = $this->getCmd($mode, $uniqueid, $afterid);
		if($cmd == ""){
			return "";
		}

		$style = ($this->debug ?
				' style="display: block; width: 90%; height: 90%; overflow: auto; border: 1px #ff0000 solid; font-family: verdana, arial; font-size: 11px; color: #000000; padding: 5px;"' :
				' style="display: none;"');

		return ($string != "" || $this->debug ?
				'<' . $this->containerType . ' id="' . $this->containerId . '"' . $style . '>'
				. $string
				. '</' . $this->containerType . '>' : '') .
			we_html_element::jsElement($cmd) .
			$this->getDisableButtonJS();
	}

// end: getResponse

	function getDisableButtonJS(){

		return we_html_element::jsElement('
for(i=0; i < ' . $this->targetFrame . '.' . $this->containerId . '.position.length; i++) {
	id = ' . $this->targetFrame . '.' . $this->containerId . '.position[i];
	id = id.replace(/entry_/, "");
	' . $this->targetFrame . '.weButton.enable("btn_direction_up_" + id);
	' . $this->targetFrame . '.weButton.enable("btn_direction_down_" + id);
		if(i == 0) {
			' . $this->targetFrame . '.weButton.disable("btn_direction_up_" + id);
		}
		if(i+1 == ' . $this->targetFrame . '.' . $this->containerId . '.position.length) {
			' . $this->targetFrame . '.weButton.disable("btn_direction_down_" + id);
		}
	}');
	}

}
