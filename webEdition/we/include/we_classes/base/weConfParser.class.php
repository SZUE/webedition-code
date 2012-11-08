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
class weConfParser{

	private $_content;
	private $_data;

	function __construct($content){
		$this->_content = $content;
		$this->_parse();
	}

	function getConfParserByFile($file){
		$fileContents = implode('', file($file));
		return new self($fileContents);
	}

	function saveToFile($file){
		return weFile::save($file, $this->getFileContent(), 'wb');
	}

	function getValue($key){
		return isset($this->_data[$key]) ? $this->_data[$key] : '';
	}

	function setValue($key, $value){
		$this->_data[$key] = $value;
	}

	function getData(){
		return $this->this->_data;
	}

	function getContent(){
		return $this->_content;
	}

	static function changeSourceCode($type, $text, $key, $value, $active = true, $comment = ''){
		switch($type){
			case 'add':
				return trim($text, "\n\t ") . "\n\n" .
					self::makeDefine($key, $value, $active, $comment);
			case 'define':
				$match = array();
				if(preg_match('|/?/?define\(\s*(["\']' . preg_quote($key) . '["\'])\s*,\s*([^\r\n]+)\);[\r\n]?|Ui', $text, $match)){
					return str_replace($match[0], self::makeDefine($key, $value, $active), $text);
				}
		}

		return $text;
	}

	function _addSlashes($in){
		return str_replace(array("\\", '"', "\$"), array("\\\\", '\"', "\\\$"), $in);
	}

	function _stripSlashes($in){
		return str_replace(array("\\\\", "\\\"", "\\\$"), array("\\", '"', "\$"), $in);
	}

	function getFileContent(){
		$out = '<?php

/**
 * webEdition CMS
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


/**
 * Configuration file for webEdition
 * =================================
 *
 * Holds the globals settings of webEdition.
 *
 * NOTE:
 * =====
 * Edit this file ONLY if you know exactly what you are doing!
 */

';
		foreach($this->_data as $key => $val){
			$out .= self::makeDefine($key, $val) . "\n\n";
		}

		return $out;
	}

	static function makeDefine($key, $val, $active = true, $comment = ''){
		return ($comment ? '//' . $comment . "\n" : '') . ($active ? '' : "//") . 'define(\'' . $key . '\', ' .
			(is_bool($val) || $val == 'true' || $val == 'false' ? ($val ? 'true' : 'false') :
				(!is_numeric($val) ? '"' . self::_addSlashes($val) . '"' : intval($val))) . ');';
	}

//FIXME: this won't work with current implementation
	function _correctMatchValue($value){
		// remove whitespaces at beginning and end
		$value = trim($value);
		if(is_numeric($value)){
			// convert to a real number
			$value = 1 * $value;
		} else if(strlen($value) >= 2){
			// remove starting and ending quotes
			$value = trim($value, '"\'');
		} else{
			// something is not right, so  correct it as an empty string
			$value = '';
		}
		return self::_stripSlashes($value);
	}

	//FIXME: parse & add comments! this won't work with current implementation
	function _parse(){
		// reset data array
		$this->_data = array();
		$match = array();
		if($this->_content){
			$pattern = '|define\(\s*["\']([^"]+)["\']\s*,\s*([^\r\n]+)\);[\r\n]?|Ui';
			if(preg_match_all($pattern, $this->_content, $match, PREG_PATTERN_ORDER)){
				for($i = 0; $i < count($match[1]); $i++){
					$this->_data[$match[1][$i]] = self::_correctMatchValue($match[2][$i]);
				}
			}
		}
	}

}