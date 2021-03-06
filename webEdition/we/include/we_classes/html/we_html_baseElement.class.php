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

/**
 * Filename:    we_baseElement.inc.php
 *
 * Function:    Utility class that implements basic html elements operations
 *
 * Description: Provides functions for creating html tags
 */
class we_html_baseElement{
	var $uid;
	var $tag_name = '';
	var $need_end_tag = true;
	var $attribs = array('style' => array());
	var $content = '';
	private $rfc = 'html4';

	/**
	 * Constructor
	 * @param		$attribs								array
	 * @param		$content								string
	 *
	 * @return		we_html_baseElement
	 */
	function __construct($tagname = "", $need_end_tag = true, $attribs = '', $content = '', $rfc = 'html4'){
		$this->setTagName($tagname);
		$this->setNeedEndTag($need_end_tag);
		$this->setAttributes($attribs);
		$this->setContent($content);
		$this->rfc = $rfc;
	}

	/**
	 * Function fills uniquie id attribute with random value
	 *
	 * @return	void
	 */
	function setUniquieID($secure = true){
		$this->uid = ($secure ? md5(uniqid(__FILE__, true)) : str_replace('.', '', uniqid('', true)));
	}

	/**
	 * Function returns copy of object
	 *
	 * @return     we_html_baseElement
	 */
	protected function copy(){
		return clone $this;
	}

	/**
	 * Function sets tag name
	 *
	 * @param		$tagname								string
	 *
	 * @return		void
	 */
	function setTagName($tagname){
		$this->tag_name = $tagname;
	}

	/**
	 * Function sets need_end_tag element attribute. Attribute need_end_tag indicates when the element needs end tag.
	 *
	 * @param		$need_end_tag							bool
	 *
	 * @return		void
	 */
	function setNeedEndTag($need_end_tag){
		$this->need_end_tag = $need_end_tag;
	}

	/*
	 * Function sets element attributes
	 *
	 * @param		$attribs								array
	 *
	 * @return		void
	 */

	function setAttributes($attribs){
		$widthHeightNotInStyle = $this->tag_name === 'applet';
		if(is_array($attribs)){
			foreach($attribs as $k => $v){
				if($k){
					$this->setAttribute($k, $v, $widthHeightNotInStyle);
				}
			}
		}
	}

	/**
	 * Function sets element attribute
	 *
	 * @param		$attrib_name							string
	 * @param		$attrib_value							string
	 *
	 * @return		void
	 */
	function setAttribute($attrib_name, $attrib_value, $widthHeightNotInStyle = false){
		switch($attrib_name){
			case 'style':
				$attrib_value = rtrim($attrib_value, '; ');
				$vals = explode(';', $attrib_value);
				foreach($vals as $val){
					$val = trim($val);
					if($val){
						/*if(count(explode(':', $val))==1){
							t_e($val);
						}*/
						list($k, $v) = explode(':', $val);
						$this->setStyle(trim($k), trim($v));
					}
				}
				break;
			case 'valign':
				$this->setStyle('vertical-align', $attrib_value);
				break;
			case 'width':
				$widthHeightNotInStyle ? $this->attribs[$attrib_name] = $attrib_value : $this->setStyle('width', $attrib_value . (is_numeric($attrib_value) ? 'px' : ''));
				break;
			case 'height':
				$widthHeightNotInStyle ? $this->attribs[$attrib_name] = $attrib_value : $this->setStyle('height', $attrib_value . (is_numeric($attrib_value) ? 'px' : ''));
				break;
			case 'border':
				$this->setStyle('border-width', $attrib_value . (is_numeric($attrib_value) ? 'px' : ''));
				break;
			case 'bgcolor':
				$this->setStyle('background-color', $attrib_value);
				break;
			case 'background':
				$this->setStyle('background-image', 'url(' . $attrib_value . ')');
				break;
			default:
				$this->attribs[$attrib_name] = $attrib_value;
		}
	}

	function setStyle($type, $val){
		$this->attribs['style'][trim($type)] = trim($val);
	}

	function hasStyle($type){
		return isset($this->attribs['style'][trim($type)]);
	}

	/**
	 * Function gets element attribute
	 *
	 * @param		$attrib_name							string
	 *
	 * @return		string
	 */
	function getAttribute($attrib_name){
		return $this->attribs[$attrib_name];
	}

	/**
	 * Function sets element content
	 *
	 * @param		$content							string
	 *
	 * @return		void
	 */
	function setContent($content){
		$this->content = $content;
	}

	/**
	 * Function append content
	 *
	 * @param		$content							string
	 *
	 * @return		void
	 */
	function appendContent($content){
		$this->content.=$content;
	}

	/**
	 * The function generate HTML code for the tag
	 *
	 * @param		$object								we_baseElement
	 *
	 * @return		string
	 */
	static function getHtmlCode($object){
		return $object->getHTML();
	}

	function getHTML(){
		$out = '<' . $this->tag_name;
		foreach($this->attribs as $k => $v){
			if($k === 'style'){
				if($v){
					$out.=' ' . $k . '="';
					foreach($v as $kk => $vv){
						$out.=$kk . ':' . $vv . ';';
					}
					$out.='"';
				}
			} else if($v !== ''){
				if(is_array($v)){
					t_e($v);
				}
				$out.=' ' . $k . '="' . $v . '"';
			} else {//empty attribs
				switch($k){
					case 'SCRIPTABLE':
					case 'MAYSCRIPT':
						$out.=' ' . $k;
						break;
					case 'disabled':
					case 'multiple':
					case 'noshade':
					case 'nowrap':
					case 'readonly':
					case 'checked':
					case 'selected':
						$out.=' ' . $k . '="' . $k . '"';
						break;
					default:
						$out.=' ' . $k . '=""';
						break;
				}
			}
		}
		if(is_array($this->content)){
			t_e($this);
		}
		$out.=($this->need_end_tag === 'selfclose' ? '/' : '') . '>' .
			$this->content .
			($this->need_end_tag === true ? "</" . $this->tag_name . ">" : '');

		return $out;
	}

}
