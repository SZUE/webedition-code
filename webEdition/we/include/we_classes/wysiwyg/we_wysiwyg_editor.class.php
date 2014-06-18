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
//make sure we know which browser is used

class we_wysiwyg_editor{

	var $name = '';
	private $origName = '';
	private $fieldName = '';
	private $fieldName_clean = '';
	var $width = '';
	var $height = '';
	var $ref = '';
	var $propstring = '';
	var $elements = array();
	var $value = '';
	var $restrictContextmenu = '';
	private $tinyPlugins = array();
	private $wePlugins = array('weadaptunlink', 'weadaptbold', 'weadaptitalic', 'weimage', 'advhr', 'weabbr', 'weacronym', 'welang', 'wevisualaid', 'weinsertbreak', 'wespellchecker', 'welink', 'wefullscreen');
	private $createContextmenu = true;
	private $filteredElements = array();
	private $bgcol = '';
	private $buttonpos = '';
	private $tinyParams = '';
	private $templates = '';
	private $fullscreen = '';
	private $className = '';
	private $fontnamesCSV = '';
	private $fontnames = array();
	private $tinyFonts = '';
	private $tinyFormatblock = '';
	private $maxGroupWidth = 0;
	private $outsideWE = false;
	private $xml = false;
	private $removeFirstParagraph = true;
	var $charset = '';
	private $inlineedit = true;
	private $cssClasses = '';
	private $cssClassesJS = '';
	private $cssClassesCSV = '';
	var $Language = '';
	private $_imagePath;
	private $_image_languagePath;
	private $baseHref = '';
	private $showSpell = true;
	private $isFrontendEdit = false;
	private $htmlSpecialchars = true; // in wysiwyg default was "true" (although Tag-Hilfe says "false")
	private $contentCss = '';
	private $isInPopup = false;
	public static $editorType = WYSIWYG_TYPE; //FIXME: remove after old editor is removed

	const CONDITIONAL = true;

	function __construct($name, $width, $height, $value = '', $propstring = '', $bgcol = '', $fullscreen = '', $className = '', $fontnames = '', $outsideWE = false, $xml = false, $removeFirstParagraph = true, $inlineedit = true, $baseHref = '', $charset = '', $cssClasses = '', $Language = '', $test = '', $spell = true, $isFrontendEdit = false, $buttonpos = 'top', $oldHtmlspecialchars = true, $contentCss = '', $origName = '', $tinyParams = '', $contextmenu = '', $isInPopup = false, $templates = ''){
		$this->propstring = $propstring ? ',' . $propstring . ',' : '';
		$this->restrictContextmenu = $contextmenu ? ',' . $contextmenu . ',' : '';
		$this->createContextmenu = trim($contextmenu, " ,'") == 'none' || trim($contextmenu, " ,'") == 'false' ? false : true;
		$this->name = $name;
		if(preg_match('|^.+\[.+\]$|i', $this->name)){
			$this->fieldName = preg_replace('/^.+\[(.+)\]$/', '\1', $this->name);
			$this->fieldName_clean = str_replace(array('-', '.', '#'), array('_minus_', '_dot_', '_sharp_'), $this->fieldName);
		};
		$this->origName = $origName;
		$this->bgcol = (self::$editorType != 'tinyMCE' && empty($bgcol)) ? 'white' : $bgcol;
		$this->tinyParams = str_replace('\'', '"', trim($tinyParams, ' ,'));
		$this->templates = trim($templates, ',');
		$this->xml = $xml;
		if(self::$editorType == 'tinyMCE'){
			$this->xml = $this->xml ? "xhtml" : "html";
		}
		$this->removeFirstParagraph = $removeFirstParagraph;
		$this->inlineedit = $inlineedit;
		$this->fullscreen = $fullscreen;
		$this->className = $className;
		$this->buttonpos = (self::$editorType == 'tinyMCE' ? $buttonpos : 'top');
		$this->statuspos = $this->buttonpos != 'external' ? $this->buttonpos : 'bottom';
		$this->outsideWE = $outsideWE;
		$this->fontnamesCSV = $fontnames;
		if(self::$editorType == 'tinyMCE'){
			if($fontnames){
				$fn = explode(',', $fontnames);
				$tf = '';
				foreach($fn as $val){
					$tf .= $val . '=' . strtolower($val) . ';';
				}
				$this->tinyFonts = substr($tf, 0, -1);
			} else {
				$this->tinyFonts = 'Arial=arial,helvetica,sans-serif;' .
					'Courier New=courier new,courier;' .
					'Geneva=Geneva, Arial, Helvetica, sans-serif;' .
					'Georgia=Georgia, Times New Roman, Times, serif;' .
					'Tahoma=Tahoma;' .
					'Times New Roman=Times New Roman,Times,serif;' .
					'Verdana=Verdana, Arial, Helvetica, sans-serif;' .
					'Wingdings=wingdings,zapf dingbats';
			}
		} else {
			$fn = $fontnames ? explode(',', $fontnames) : array('Arial, Helvetica, sans-serif', 'Courier New, Courier, mono', 'Geneva, Arial, Helvetica, sans-serif', 'Georgia, Times New Roman, Times, serif', 'Tahoma', 'Times New Roman, Times, serif', 'Verdana, Arial, Helvetica, sans-serif', 'Wingdings');
			foreach($fn as &$font){
				$font = strtolower(str_replace(';', ',', $font));
				$this->fontnames[$font] = $font;
			}
		}
		$this->cssClasses = $cssClasses;
		$this->cssClassesCSV = $cssClasses;
		if($this->cssClasses != '' && self::$editorType == 'tinyMCE'){
			$cc = explode(',', $this->cssClasses);
			$tf = '';
			$jsCl = '';
			foreach($cc as $val){
				$tf .= $val . '=' . $val . ';';
				$jsCl .= '"' . $val . '"' . ',';
			}
			$this->cssClasses = rtrim($tf, ';');
			$this->cssClassesJS = rtrim($jsCl, ',');
		}
		$this->contentCss = $contentCss;

		$this->Language = $Language;
		$this->showSpell = $spell;
		$this->htmlSpecialchars = $oldHtmlspecialchars;
		$this->isFrontendEdit = $isFrontendEdit;

		$this->_imagePath = IMAGE_DIR . 'wysiwyg/';
		$this->_image_languagePath = WE_INCLUDES_DIR . 'we_language/' . $GLOBALS['WE_LANGUAGE'] . '/wysiwyg/';

		$this->baseHref = $baseHref ? $baseHref : we_base_util::getGlobalPath();
		$this->charset = $charset;

		$this->width = $width;
		$this->height = $height;
		$this->ref = preg_replace('%[^0-9a-zA-Z_]%', '', $this->name);
		$this->hiddenValue = $value;
		$this->isInPopup = $isInPopup;

		if($inlineedit){
			if($value){
				if(self::$editorType == 'tinyMCE'){
					//FIXME: what to do with scripts??
				} else {
					$value = strtr($value, array("\\" => "\\\\", "\n" => '\n', "\r" => '\r'));
					$value = str_replace(array('script', 'Script', 'SCRIPT',), array('##scr#ipt##', '##Scr#ipt##', '##SCR#IPT##',), $value);
					$value = preg_replace('%<\?xml[^>]*>%i', '', $value);
					$value = str_replace(array('<?', '?>',), array('||##?##||', '##||?||##'), $value);
				}
			}
		}

		$this->setToolbarElements();
		$this->setFilteredElements();
		$this->getMaxGroupWidth();
		$this->value = $value;
	}

	public function getIsFrontendEdit(){
		return $this->isFrontendEdit;
	}

	public static function getEditorCommands($isTag){
		$commands = array(
			'font' => array('fontname', 'fontsize'),
			'prop' => array('formatblock', 'applystyle', 'bold', 'italic', 'underline', 'subscript', 'superscript', 'strikethrough', 'styleprops', 'removeformat', 'removetags'),
			'xhtmlxtras' => array('cite', 'acronym', 'abbr', 'lang', 'del', 'ins', 'ltr', 'rtl'),
			'color' => array('forecolor', 'backcolor'),
			'justify' => array('justifyleft', 'justifycenter', 'justifyright', 'justifyfull'),
			'list' => array('insertunorderedlist', 'insertorderedlist', 'indent', 'outdent', 'blockquote'),
			'link' => array('createlink', 'unlink', 'anchor'),
			'table' => array('inserttable', 'deletetable', 'editcell', 'editrow', 'insertcolumnleft', 'insertcolumnright', 'deletecol', 'insertrowabove', 'insertrowbelow', 'deleterow', 'increasecolspan', 'decreasecolspan'),
			'insert' => array('insertimage', 'hr', 'inserthorizontalrule', 'insertspecialchar', 'insertbreak', 'insertdate', 'inserttime'),
			'copypaste' => array(/* 'cut', 'copy', 'paste', */'pastetext', 'pasteword'),
			'layer' => array('insertlayer', 'movebackward', 'moveforward', 'absolute'),
			'essential' => array('undo', 'redo', 'spellcheck', 'selectall', 'search', 'replace', 'fullscreen', 'visibleborders'),
			'advanced' => array('editsource', 'template')
		);

		$tmp = array_keys($commands);
		unset($tmp[0]); //unsorted
		if($isTag){
			$ret = array(new weTagDataOption(g_l('wysiwyg', '[groups]'), we_html_tools::OPTGROUP));

			foreach($tmp as $command){
				$ret[] = new weTagDataOption($command);
			}
			foreach($commands as $key => $values){
				$ret[] = new weTagDataOption($key, we_html_tools::OPTGROUP);
				foreach($values as $value){
					$ret[] = new weTagDataOption($value);
				}
			}

			return $ret;
		}
		$ret = array(
			'',
			g_l('wysiwyg', '[groups]') => we_html_tools::OPTGROUP
		);
		$ret = array_merge($ret, $tmp);
		foreach($commands as $key => $values){
			$ret = array_merge($ret, array($key => we_html_tools::OPTGROUP), $values);
		}
		return $ret;
	}

	function getMaxGroupWidth(){
		$w = 0;
		foreach($this->filteredElements as $i => $v){
			if($v->classname == 'we_wysiwyg_ToolbarSeparator'){
				$this->maxGroupWidth = max($w, $this->maxGroupWidth);
				$w = 0;
			} else {
				$w += $v->width;
			}
		}
		$this->maxGroupWidth = max($w, $this->maxGroupWidth);
		$this->maxGroupWidth += self::$editorType == 'tinyMCE' ? 2 : 0;
	}

	static function getHeaderHTML($loadDialogRegistry = false){
		if(defined('WE_WYSIWG_HEADER')){
			if($loadDialogRegistry && !defined('WE_WYSIWG_HEADER_REG')){
				define('WE_WYSIWG_HEADER_REG', 1);
				return we_html_element::jsScript(JS_DIR . 'weTinyMceDialogs.js');
			}
			return '';
		}
		define('WE_WYSIWG_HEADER', 1);
		if($loadDialogRegistry){
			define('WE_WYSIWG_HEADER_REG', 1);
		}
		switch(self::$editorType){
			case 'tinyMCE':
				//FIXME: remove onchange - bad practise
				return we_html_element::cssElement('
.tbButtonWysiwygBorder {
	border: 1px solid #006DB8;
	background-image: url(' . IMAGE_DIR . 'pixel.gif);
	margin: 0px;
	padding:4px;
	text-align: left;
	text-decoration: none;
	position: relative;
	overflow: auto;
	height: auto;
	width: auto;
}') .
					we_html_element::jsScript(WEBEDITION_DIR . 'editors/content/tinymce/jscripts/tiny_mce/tiny_mce.js') .
					($loadDialogRegistry ? we_html_element::jsScript(JS_DIR . 'weTinyMceDialogs.js') : '') .
					we_html_element::jsScript(JS_DIR . 'weTinyMceFunctions.js') .
					we_html_element::jsElement('
function tinyMCECallRegisterDialog(win,action){
	if(typeof(top.isRegisterDialogHere) != "undefined"){
		try{
			top.weRegisterTinyMcePopup(win,action);
		} catch(err) {}
	} else {
		if(typeof(top.opener.isRegisterDialogHere) != "undefined"){
			try{
				top.opener.weRegisterTinyMcePopup(win,action);
			} catch(err){}
		} else {
			try{
				top.opener.tinyMCECallRegisterDialog(win,action);
			} catch(err){}
		}
	}
}') .
					we_html_element::jsElement('
function weWysiwygSetHiddenTextSync(){
	weWysiwygSetHiddenText();
	setTimeout(weWysiwygSetHiddenTextSync,500);
}

function weWysiwygSetHiddenText(arg) {
	try {
		if (weWysiwygIsIntialized) {
			for (var i = 0; i < we_wysiwygs.length; i++) {
				we_wysiwygs[i].setHiddenText(arg);
			}
		}else{
			}
	} catch(e) {
		// Nothing
	}
}');
			default:
			case 'default':

				return '
				<style type="text/css">
					.tbButton {
						border: 1px solid #F4F4F4;
						padding: 0px;
						margin: 0px;
						text-align: left;
						text-decoration: none;
						position: relative;
					}
					.tbButtonMouseOverUp {
						border-bottom: 1px solid #000000;
						border-left: 1px solid #CCCCCC;
						border-right: 1px solid #000000;
						border-top: 1px solid #CCCCCC;
						cursor:pointer;
						margin: 0px;
						padding:0px;
						text-align: left;
						text-decoration: none;
						position: relative;
					}
					.tbButtonMouseOverDown {
						border-bottom: 1px solid #CCCCCC;
						border-left: 1px solid #000000;
						border-right: 1px solid #CCCCCC;
						border-top: 1px solid #000000;
						cursor: pointer;
						margin: 0px;
						padding: 0px;
						text-align: left;
						text-decoration: none;
						position: relative;
					}
					.tbButtonDown {
						background-image: url(' . IMAGE_DIR . 'java_menu/background_dark.gif);
						border-bottom: #CCCCCC solid 1px;
						border-left: #000000 solid 1px;
						border-right: #CCCCCC solid 1px;
						border-top:  #000000 solid 1px;
						margin: 0px;
						padding:0px;
						text-align: left;
						text-decoration: none;
						position: relative;
					}
					.tbButtonsHR {
						border-top:  #000000 solid 1px;
						border-bottom:  #CCCCCC solid 1px;
						margin: 0px;
						padding:0px;
						text-align: left;
						text-decoration: none;
						position: relative;
					}
					.tbButtonWysiwygBorder {
						border: 1px solid #006DB8;
						background-image: url(' . IMAGE_DIR . 'pixel.gif);
						margin: 0px;
						padding:4px;
						text-align: left;
						text-decoration: none;
						position: relative;
						overflow: auto;
						height: auto;
						width: auto;
					}
					.tbButtonWysiwygBackground{
						background-image: url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif) ! important;
					}
					.tbButtonWysiwygDefaultStyle{
						background: transparent;
						background-color: transparent;
						background-image: url(' . IMAGE_DIR . 'pixel.gif);
						border: 0px;
						color: #000000;
						cursor: default;
						font-size: ' . ((we_base_browserDetect::isMAC()) ? "11px" : ((we_base_browserDetect::isUNIX()) ? "13px" : "12px")) . ';
						font-family: ' . g_l('css', '[font_family]') . ';
						font-weight: normal;
						margin: 0px;
						padding:0px;
						text-align: left;
						text-decoration: none;
						left: auto ! important;
						right: auto ! important;
						width: auto ! important;
						height: auto ! important;
					}

				</style>' . we_html_element::jsElement('
					var we_wysiwygs = new Array();
					var we_wysiwyg_lng = new Array();
					var isGecko = ' . (we_base_browserDetect::isGecko() ? 'true' : 'false') . ';
					var isOpera = ' . (we_base_browserDetect::isOpera() ? 'true' : 'false') . ';
					var isIE = ' . (we_base_browserDetect::isIE() ? 'true' : 'false') . ';
					var ieVersion = ' . we_base_browserDetect::getIEVersion() . ';
					var isIE9 = ' . ((we_base_browserDetect::isIE() && we_base_browserDetect::getIEVersion() == 9) ? 'true' : 'false') . ';
					var weWysiwygLoaded = false;
					var weNodeList = new Array();
					var weWysiwygFolderPath = "' . WEBEDITION_DIR . 'editors/content/wysiwyg/";
					var weWysiwygImagesFolderPath = "' . IMAGE_DIR . 'wysiwyg/";
					var weWysiwygBgGifPath = "' . IMAGE_DIR . 'backgrounds/aquaBackground.gif";
					var weWysiwygIsIntialized = false;

					var wePopupMenuArray = new Array();

					// Bugfix do not overwrite body.onload !!!
					function weEvent(){}
					weEvent.addEvent = function(e, name, f) {
						if (e.addEventListener) {
							e.addEventListener(
								name,
								f,
								true);
						}
						if(e.attachEvent){
							e.attachEvent("on" + name, f);
						}
					}

					//window.onerror = weNothing;
					//  Bugfix do not overwrite body.onload !!!
					weEvent.addEvent(window,"load", weWysiwygInitializeIt);
					//window.onload = weWysiwygInitializeIt + window.onload;

					function weNothing() {
						return true;
					}

					function weWysiwygInitializeIt() {
						for (var i=0;i<we_wysiwygs.length;i++) {
							we_wysiwygs[i].start();
						}
						for (var i=0;i<we_wysiwygs.length;i++) {
							we_wysiwygs[i].finalize();
							we_wysiwygs[i].windowFocus();
							we_wysiwygs[i].setButtonsState();
						}
						self.focus();
						weWysiwygIsIntialized = true;
						weWysiwygSetHiddenTextSync();
					}

					function weWysiwygSetHiddenTextSync(){
						weWysiwygSetHiddenText();
						setTimeout(weWysiwygSetHiddenTextSync,500);
					}

					function weWysiwygSetHiddenText(arg) {
						try {
							if (weWysiwygIsIntialized) {
								for (var i = 0; i < we_wysiwygs.length; i++) {
									we_wysiwygs[i].setHiddenText(arg);
								}
							}else{
								}
						} catch(e) {
							// Nothing
						}
					}') .
					we_html_element::jsScript(JS_DIR . 'we_showMessage.js') .
					(we_base_browserDetect::isSafari() ? we_html_element::jsScript(WEBEDITION_DIR . 'editors/content/wysiwyg/weWysiwygSafari.js') .
						we_html_element::jsScript(JS_DIR . 'weDOM_Safari.js') : we_html_element::jsScript(WEBEDITION_DIR . 'editors/content/wysiwyg/weWysiwyg.js'));
		}
	}

	function getAllCmds(){
		$arr = array('formatblock',
			'fontname',
			'fontsize',
			'applystyle',
			'bold',
			'italic',
			'underline',
			'subscript',
			'superscript',
			'strikethrough',
			'removeformat',
			'removetags',
			'forecolor',
			'backcolor',
			'justifyleft',
			'justifycenter',
			'justifyright',
			'justifyfull',
			'insertunorderedlist',
			'insertorderedlist',
			'indent',
			'outdent',
			'createlink',
			'unlink',
			'anchor',
			'insertimage',
			'inserthorizontalrule',
			'insertspecialchar',
			'inserttable',
			'editcell',
			'insertcolumnright',
			'insertcolumnleft',
			'insertrowabove',
			'insertrowbelow',
			'deletecol',
			'deleterow',
			'increasecolspan',
			'decreasecolspan',
			'fullscreen',
			'undo',
			'redo',
			'visibleborders',
			'editsource',
			'insertbreak',
			'acronym',
			'abbr',
			'lang'
		);

		if(self::$editorType == 'tinyMCE'){
			array_push($arr, 'absolute', 'blockquote', 'cite', 'del', 'emotions', 'hr', 'ins', 'insertdate', 'insertlayer', 'inserttime', 'ltr', 'movebackward', 'moveforward', 'nonbreaking', 'pastetext', 'pasteword', 'replace', 'rtl', 'search', 'styleprops', 'template', 'editrow', 'deletetable', 'selectall');
		} else {
			array_push($arr, 'edittable', 'caption', 'removecaption', 'importrtf');
		}

		if(defined('SPELLCHECKER')){
			$arr[] = "spellcheck";
		}
		return $arr;
	}

	function setToolbarElements(){// TODO: declare setToolbarElements
		$formatblockArr = we_base_browserDetect::isIE() ? array(
			"normal" => g_l('wysiwyg', "[normal]"),
			"p" => g_l('wysiwyg', "[paragraph]"),
			"h1" => g_l('wysiwyg', "[h1]"),
			"h2" => g_l('wysiwyg', "[h2]"),
			"h3" => g_l('wysiwyg', "[h3]"),
			"h4" => g_l('wysiwyg', "[h4]"),
			"h5" => g_l('wysiwyg', "[h5]"),
			"h6" => g_l('wysiwyg', "[h6]"),
			"pre" => g_l('wysiwyg', "[pre]"),
			"address" => g_l('wysiwyg', "[address]")
			) :
			(we_base_browserDetect::isSafari() ? array(
				"div" => g_l('wysiwyg', "[normal]"),
				"p" => g_l('wysiwyg', "[paragraph]"),
				"h1" => g_l('wysiwyg', "[h1]"),
				"h2" => g_l('wysiwyg', "[h2]"),
				"h3" => g_l('wysiwyg', "[h3]"),
				"h4" => g_l('wysiwyg', "[h4]"),
				"h5" => g_l('wysiwyg', "[h5]"),
				"h6" => g_l('wysiwyg', "[h6]"),
				"pre" => g_l('wysiwyg', "[pre]"),
				"address" => g_l('wysiwyg', "[address]"),
				"blockquote" => "blockquote"
				) :
				array(
				"normal" => g_l('wysiwyg', "[normal]"),
				"p" => g_l('wysiwyg', "[paragraph]"),
				"h1" => g_l('wysiwyg', "[h1]"),
				"h2" => g_l('wysiwyg', "[h2]"),
				"h3" => g_l('wysiwyg', "[h3]"),
				"h4" => g_l('wysiwyg', "[h4]"),
				"h5" => g_l('wysiwyg', "[h5]"),
				"h6" => g_l('wysiwyg', "[h6]"),
				"pre" => g_l('wysiwyg', "[pre]"),
				"address" => g_l('wysiwyg', "[address]"),
				"code" => "Code",
				//"cite" => "Cite",
				//"q" => "q",
				"blockquote" => "blockquote"
		));

		$this->tinyFormatblock = implode(',', array_keys($formatblockArr));

		//group: font
		$this->elements = array(new we_wysiwyg_ToolbarSelect($this, "fontname", g_l('wysiwyg', "[fontname]"), $this->fontnames, 92));
		if($this->width < 194){
			$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		}
		$this->elements[] = new we_wysiwyg_ToolbarSelect($this, 'fontsize', g_l('wysiwyg', '[fontsize]'), we_base_browserDetect::isSafari() ? array(
				'8px' => '8px',
				'9px' => '9px',
				'10px' => '10px',
				'11px' => '11px',
				'12px' => '12px',
				'13px' => '13px',
				'14px' => '14px',
				'15px' => '15px',
				'16px' => '16px',
				'17px' => '17px',
				'18px' => '18px',
				'19px' => '19px',
				'20px' => '20px',
				'21px' => '21px',
				'22px' => '22px',
				'24px' => '24px',
				'26px' => '26px',
				'28px' => '28px',
				'30px' => '30px',
				'36px' => '36px'
				) : array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7
				), 92);
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);

		//group: prop
		$this->elements[] = new we_wysiwyg_ToolbarSelect($this, "formatblock", g_l('wysiwyg', "[format]"), $formatblockArr, 92);
		if($this->width < 194){
			$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		}
		$this->elements[] = new we_wysiwyg_ToolbarSelect($this, "applystyle", g_l('wysiwyg', "[css_style]"), array(), self::$editorType == 'tinyMCE' ? 92 : 120 );
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "bold", $this->_image_languagePath . "bold.gif", g_l('wysiwyg', "[bold]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "italic", $this->_image_languagePath . "italic.gif", g_l('wysiwyg', "[italic]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "underline", $this->_image_languagePath . "underline.gif", g_l('wysiwyg', "[underline]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "subscript", $this->_imagePath . "subscript.gif", g_l('wysiwyg', "[subscript]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "superscript", $this->_imagePath . "superscript.gif", g_l('wysiwyg', "[superscript]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "strikethrough", $this->_imagePath . "strikethrough.gif", g_l('wysiwyg', "[strikethrough]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "styleprops", "", ""); // tinyMCE only: we do not need icon or tooltip
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "removeformat", $this->_imagePath . "removeformat.gif", g_l('wysiwyg', "[removeformat]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "removetags", $this->_imagePath . "removetags.gif", g_l('wysiwyg', "[removetags]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: xhtmlxtras
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "cite", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "acronym", $this->_image_languagePath . "acronym.gif", g_l('wysiwyg', "[acronym]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "abbr", $this->_image_languagePath . "abbr.gif", g_l('wysiwyg', "[abbr]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "lang", $this->_imagePath . "lang.gif", g_l('wysiwyg', "[language]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "del", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "ins", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "ltr", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "rtl", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: color
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "forecolor", $this->_imagePath . "setforecolor.gif", g_l('wysiwyg', "[fore_color]"), 32);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "backcolor", $this->_imagePath . "setbackcolor.gif", g_l('wysiwyg', "[back_color]"), 32);
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: justify
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "justifyleft", $this->_imagePath . "justifyleft.gif", g_l('wysiwyg', "[justify_left]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "justifycenter", $this->_imagePath . "justifycenter.gif", g_l('wysiwyg', "[justify_center]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "justifyright", $this->_imagePath . "justifyright.gif", g_l('wysiwyg', "[justify_right]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "justifyfull", $this->_imagePath . "justifyfull.gif", g_l('wysiwyg', "[justify_full]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: list
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertunorderedlist", $this->_imagePath . "unorderlist.gif", g_l('wysiwyg', "[unordered_list]"), 32);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertorderedlist", $this->_imagePath . "orderlist.gif", g_l('wysiwyg', "[ordered_list]"), 32);
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "indent", $this->_imagePath . "indent.gif", g_l('wysiwyg', "[indent]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "outdent", $this->_imagePath . "outdent.gif", g_l('wysiwyg', "[outdent]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "blockquote", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: link
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "createlink", $this->_imagePath . "hyperlink.gif", g_l('wysiwyg', "[hyperlink]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "unlink", $this->_imagePath . "unlink.gif", g_l('wysiwyg', "[unlink]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "anchor", $this->_imagePath . "anchor.gif", g_l('wysiwyg', "[insert_edit_anchor]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: table
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "inserttable", $this->_imagePath . "inserttable.gif", g_l('wysiwyg', "[inserttable]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "edittable", $this->_imagePath . "edittable.gif", g_l('wysiwyg', "[edittable]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "deletetable", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "editcell", $this->_imagePath . "editcell.gif", g_l('wysiwyg', "[editcell]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "editrow", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertcolumnleft", $this->_imagePath . "insertcol_left.gif", g_l('wysiwyg', "[insertcolumnleft]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertcolumnright", $this->_imagePath . "insertcol_right.gif", g_l('wysiwyg', "[insertcolumnright]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "deletecol", $this->_imagePath . "deletecols.gif", g_l('wysiwyg', "[deletecol]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertrowabove", $this->_imagePath . "insertrow_above.gif", g_l('wysiwyg', "[insertrowabove]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertrowbelow", $this->_imagePath . "insertrow_below.gif", g_l('wysiwyg', "[insertrowbelow]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "deleterow", $this->_imagePath . "deleterows.gif", g_l('wysiwyg', "[deleterow]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "increasecolspan", $this->_imagePath . "inc_col.gif", g_l('wysiwyg', "[increasecolspan]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "decreasecolspan", $this->_imagePath . "dec_col.gif", g_l('wysiwyg', "[decreasecolspan]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "caption", $this->_imagePath . "caption.gif", g_l('wysiwyg', "[addcaption]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "removecaption", $this->_imagePath . "removecaption.gif", g_l('wysiwyg', "[removecaption]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: insert
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertimage", $this->_imagePath . "image.gif", g_l('wysiwyg', "[insert_edit_image]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "hr", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "inserthorizontalrule", $this->_imagePath . "rule.gif", g_l('wysiwyg', "[inserthorizontalrule]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertspecialchar", $this->_imagePath . "specialchar.gif", g_l('wysiwyg', "[insertspecialchar]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "nonbreaking", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertbreak", $this->_imagePath . "br.gif", g_l('wysiwyg', "[insert_br]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertdate", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "inserttime", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: copypaste
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "pastetext", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "pasteword", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "importrtf", $this->_imagePath . "rtf.gif", g_l('wysiwyg', "[rtf_import]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: layer
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "insertlayer", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "movebackward", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "moveforward", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "absolute", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: essential
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "undo", $this->_imagePath . "undo.gif", g_l('wysiwyg', "[undo]"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "redo", $this->_imagePath . "redo.gif", g_l('wysiwyg', "[redo]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		if(defined('SPELLCHECKER') && $this->showSpell){
			$this->elements[] = new we_wysiwyg_ToolbarButton($this, 'spellcheck', $this->_imagePath . 'spellcheck.gif', g_l('wysiwyg', '[spellcheck]'));
		}
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "selectall", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "search", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "replace", "", "");
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this, self::CONDITIONAL);
		if(!$this->fullscreen){
			$this->elements[] = new we_wysiwyg_ToolbarButton($this, "fullscreen", $this->_imagePath . "fullscreen.gif", g_l('wysiwyg', "[fullscreen]"));
		}
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "visibleborders", $this->_imagePath . "visibleborders.gif", g_l('wysiwyg', "[visible_borders]"));
		$this->elements[] = new we_wysiwyg_ToolbarSeparator($this);
		//group: advanced
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "editsource", $this->_imagePath . "editsourcecode.gif", g_l('wysiwyg', "[edit_sourcecode"));
		$this->elements[] = new we_wysiwyg_ToolbarButton($this, "template", "", "");

		//if legacy editor throw conditional seperators out
		if(self::$editorType != 'tinyMCE'){
			for($i = 0; $i < count($this->elements); $i++){
				if($this->elements[$i]->classname == 'we_wysiwyg_ToolbarSeparator'&& $this->elements[$i]->conditional){
					unset($this->elements[$i]);
				}
			}
		}
	}

	function getWidthOfElem($startPos, $end){//TODO: throw out if obsolete
		$w = 0;
		for($i = $startPos; $i <= $end; $i++){
			$w += $this->filteredElements[$i]->width;
		}
		return $w;
	}

	function setFilteredElements(){
		$lastSep = true;
		foreach($this->elements as $elem){
			if(is_object($elem) && $elem->showMe){
				if((!$lastSep) || ($elem->classname != "we_wysiwyg_ToolbarSeparator")){
					$this->filteredElements[] = $elem;
				}
				$lastSep = ($elem->classname == "we_wysiwyg_ToolbarSeparator");
			}
		}
		if($this->filteredElements){
			if($this->filteredElements[count($this->filteredElements) - 1]->classname == 'we_wysiwyg_ToolbarSeparator'){
				array_pop($this->filteredElements);
			}
		}
	}

	function hasSep($rowArr){
		foreach($rowArr as $i => $elem){
			if($elem->classname == "we_wysiwyg_ToolbarSeparator"){
				return true;
			}
		}
		return false;
	}

	function getHTML($value = ''){
		return ($this->inlineedit ? $this->getInlineHTML() : $this->getEditButtonHTML($value));
	}

	function getEditButtonHTML($value = ''){
		list($tbwidth, $tbheight) = $this->getToolbarWidthAndHeight();
		$tbheight += self::$editorType == 'tinyMCE' ? 18 : 0;
		$fns = '';
		foreach($this->fontnames as $fn){
			$fns .= str_replace(",", ";", $fn) . ",";
		}
		$js_function = $this->isFrontendEdit ? 'open_wysiwyg_win' : 'we_cmd';
		$param4 = !$this->isFrontendEdit ? (self::$editorType !== 'tinyMCE' ? we_base_request::encCmd($value) : '') : we_base_request::encCmd('frontend');

		return we_html_button::create_button("image:btn_edit_edit", "javascript:" . $js_function . "('open_wysiwyg_window', '" . $this->name . "','" . max(220, $this->width) . "', '" . $this->height . "','" . $param4 . "','" . $this->propstring . "','" . $this->className . "','" . rtrim($fns, ',') . "',
			'" . $this->outsideWE . "','" . $tbwidth . "','" . $tbheight . "','" . $this->xml . "','" . $this->removeFirstParagraph . "','" . $this->bgcol . "','" . $this->baseHref . "','" . $this->charset . "','" . $this->cssClassesCSV . "','" . $this->Language . "','" . we_base_request::encCmd($this->contentCss) . "',
			'" . $this->origName . "','" . we_base_request::encCmd($this->tinyParams) . "','" . we_base_request::encCmd($this->restrictContextmenu) . "', 'true', '" . $this->isFrontendEdit . "','" . $this->templates . "');", true, 25);
	}

	function parseInternalImageSrc($value){
		$editValue = $value;
		$regs = array();
		if(preg_match_all('/src="' . we_base_link::TYPE_INT_PREFIX . '(\\d+)/i', $editValue, $regs, PREG_SET_ORDER)){
			foreach($regs as $reg){
				$path = f('SELECT Path FROM ' . FILE_TABLE . ' WHERE ID=' . intval($reg[1]), 'Path', $GLOBALS['DB_WE']);
				$editValue = str_ireplace('src="' . we_base_link::TYPE_INT_PREFIX . $reg[1], 'src="' . $path . "?id=" . $reg[1], $editValue);
			}
		}
		if(preg_match_all('/src="' . we_base_link::TYPE_THUMB_PREFIX . '([^" ]+)/i', $editValue, $regs, PREG_SET_ORDER)){
			foreach($regs as $reg){
				list($imgID, $thumbID) = explode(',', $reg[1]);
				$thumbObj = new we_thumbnail();
				$thumbObj->initByImageIDAndThumbID($imgID, $thumbID);
				$editValue = str_ireplace('src="' . we_base_link::TYPE_THUMB_PREFIX . $reg[1], 'src="' . $thumbObj->getOutputPath() . "?thumb=" . $reg[1], $editValue);
				unset($thumbObj);
			}
		}

		return $editValue;
	}

	function getToolbarRows(){
		$width = $this->width - 12;
		$maxGroupWidth = $this->maxGroupWidth - 2;
		if(self::$editorType != 'tinyMCE'){
			$width = $this->width;
			$maxGroupWidth = $this->maxGroupWidth;
		}

		$tmpElements = $this->filteredElements;
		$rows = array();
		$rownr = 0;
		$rows[$rownr] = array();
		$rowwidth = 0;
		while(!empty($tmpElements)){
			if(!$this->hasSep($rows[$rownr]) || $rowwidth <= max($width, $maxGroupWidth)){
				//TinyMCE: There is a 5px border on the left, another 5px on the right looks nicer, and buttons/blocks of buttons have a 1 px border on the left and right = 12px
				$rows[$rownr][] = array_shift($tmpElements);
				$rowwidth += $rows[$rownr][count($rows[$rownr]) - 1]->width;
			} else {
				if(!empty($rows[$rownr])){
					if($rows[$rownr][count($rows[$rownr]) - 1]->classname == "we_wysiwyg_ToolbarSeparator"){
						array_pop($rows[$rownr]);
						$rownr++;
						$rowwidth = 0;
						$rows[$rownr] = array();
					} else {
						do{
							array_unshift($tmpElements, array_pop($rows[$rownr]));
						} while($tmpElements[0]->classname != "we_wysiwyg_ToolbarSeparator");
						array_shift($tmpElements);
						$rownr++;
						$rowwidth = 0;
						$rows[$rownr] = array();
					}
				}
			}
		}
		return $rows;
	}

	function getToolbarWidthAndHeight(){

		$rows = $this->getToolbarRows();
		$toolbarheight = 0;
		$min_w = 0;
		$row_w = 0;
		foreach($rows as $curRow){
			$rowheight = 0;
			foreach($curRow as $curCol){
				$rowheight = max($rowheight, $curCol->height);
				$row_w += $curCol->width;
			}
			$toolbarheight += ($rowheight + 2);
			$min_w = max($min_w, $row_w);
			$row_w = 0;
		}

		$realWidth = max($min_w, $this->width);
		return array($realWidth, $toolbarheight);
	}

	function getContextmenuCommands(){
		if(count($this->filteredElements) == 0){
			return '{}';
		}
		$ret = '';
		foreach($this->filteredElements as $elem){
			$ret .= $elem->classname == 'we_wysiwyg_ToolbarButton' && $elem->showMeInContextmenu && self::wysiwygCmdToTiny($elem->cmd) ? '"' . self::wysiwygCmdToTiny($elem->cmd) . '":true,' : '';
		}
		return trim($ret, ',') !== '' ? '{' . trim($ret, ',') . '}' : 'false';
	}

	static function wysiwygCmdToTiny($cmd){
		$cmdMapping = array(
			'abbr' => 'weabbr',
			'acronym' => 'weacronym',
			'anchor' => 'anchor',
			'applystyle' => 'styleselect',
			'backcolor' => 'backcolor',
			'bold' => 'weadaptbold',
			//'copy' => 'copy',
			'createlink' => 'welink',
			//'cut' => 'cut',
			'decreasecolspan' => 'split_cells',
			'deletecol' => 'delete_col',
			'deleterow' => 'delete_row',
			'editcell' => 'cell_props',
			'editsource' => 'code',
			'fontname' => 'fontselect',
			'fontsize' => 'fontsizeselect',
			'forecolor' => 'forecolor',
			'formatblock' => 'formatselect',
			'fullscreen' => 'wefullscreen',
			'increasecolspan' => 'merge_cells',
			'indent' => 'indent',
			'insertbreak' => 'weinsertbreak',
			'insertcolumnleft' => 'col_before ',
			'insertcolumnright' => 'col_after',
			'inserthorizontalrule' => 'advhr',
			'insertimage' => 'weimage',
			'insertorderedlist' => 'numlist',
			'insertrowabove' => 'row_before',
			'insertrowbelow' => 'row_after',
			'insertspecialchar' => 'charmap',
			'inserttable' => 'table',
			'insertunorderedlist' => 'bullist',
			'italic' => 'weadaptitalic',
			'justifycenter' => 'justifycenter',
			'justifyfull' => 'justifyfull',
			'justifyleft' => 'justifyleft',
			'justifyright' => 'justifyright',
			'lang' => 'welang',
			'outdent' => 'outdent',
			//'paste' => 'paste',
			'redo' => 'redo',
			'removeformat' => 'removeformat',
			'removetags' => 'cleanup',
			'spellcheck' => 'wespellchecker',
			'strikethrough' => 'strikethrough',
			'subscript' => 'sub',
			'superscript' => 'sup',
			'underline' => 'underline',
			'undo' => 'undo',
			'unlink' => 'weadaptunlink',
			'visibleborders' => 'wevisualaid',
			// the following commands exist only in tinyMCE
			'absolute' => 'absolute',
			'blockquote' => 'blockquote',
			'cite' => 'cite',
			'del' => 'del',
			'deletetable' => 'delete_table',
			'editrow' => 'row_props',
			'emotions' => 'emotions',
			'hr' => 'hr',
			'ins' => 'ins',
			'insertdate' => 'insertdate',
			'insertlayer' => 'insertlayer',
			'inserttime' => 'inserttime',
			'ltr' => 'ltr',
			'movebackward' => 'movebackward',
			'moveforward' => 'moveforward',
			'nonbreaking' => 'nonbreaking',
			'pastetext' => 'pastetext',
			'pasteword' => 'pasteword',
			'replace' => 'replace',
			'rtl' => 'rtl',
			'search' => 'search',
			'selectall' => 'selectall',
			'styleprops' => 'styleprops',
			'template' => 'template',
			'editrow' => 'row_props',
			'deletetable' => 'delete_table'
			
			// table controlls are not mapped from wysiwyg to tinyMCE:
			//'notmapped1' => 'attribs',
			//'notmapped2' => 'insertimage', // replaced by weimage
			//'notmapped3' => 'insertfile',
			//'notmapped4' => 'preview', // will not be implemented: we should only use we-preview
			//'notmapped5' => 'media',
			//'notmapped6' => 'visualchars', //seems not to work
			//'notmapped7' => 'iespell',
			//'notmapped8' => 'pagebreak',
			//'notmapped9' => 'template',
		);
		return $cmdMapping[$cmd] != '--' ? $cmdMapping[$cmd] : '';
	}

	function setPlugin($name, $doSet){
		if($doSet){
			$this->tinyPlugins[] = $name;
		}
		return $doSet;
	}

	function getTemplates(){
		$tmplArr = explode(',', str_replace(' ', '', $this->templates));
		$templates = '';
		for($i = 0; $i < count($tmplArr); $i++){
			$tmplDoc = new we_document();
			$tmplDoc->initByID(intval($tmplArr[$i]));
			if(($tmplDoc->ContentType == we_base_ContentTypes::APPLICATION && ($tmplDoc->Extension == '.html' || $tmplDoc->Extension == '.htm')) || $tmplDoc->ContentType == we_base_ContentTypes::WEDOCUMENT){
				$templates .= '{title: "' . (isset($tmplDoc->elements['Title']['dat']) && $tmplDoc->elements['Title']['dat'] ? $tmplDoc->elements['Title']['dat'] : "no title " . ($i + 1)) . '", src : "' . $tmplDoc->Path . '", description: "' . (isset($tmplDoc->elements['Description']['dat']) && $tmplDoc->elements['Description']['dat'] ? $tmplDoc->elements['Description']['dat'] : "no description " . ($i + 1)) . '"},';
			}
		}

		return $templates ? 'template_templates : [' . rtrim($templates, ',') . '],' : 'template_templates : [],';
	}

	function getInlineHTML(){
		$rows = $this->getToolbarRows();
		$editValue = $this->parseInternalImageSrc($this->value);

		switch(self::$editorType){
			case 'tinyMCE':
				list($lang) = explode('_', $GLOBALS["weDefaultFrontendLanguage"]);

				//write theme_advanced_buttons_X
				$tinyRows = '';
				$allCommands = array();
				$i = 0;
				$k = 1;
				$pastetext = 0;

				foreach($rows as $outer){
					$tinyRows .= 'theme_advanced_buttons' . $k . ' : "';
					$j = 0;
					foreach($outer as $inner){
						if($rows[$i][$j]->cmd == ''){
							$tinyRows .= $rows[$i][$j]->conditional ? '' : 'separator,';
						} else if(self::wysiwygCmdToTiny($rows[$i][$j]->cmd)){
							$tinyRows .= self::wysiwygCmdToTiny($rows[$i][$j]->cmd) . ',';
							$allCommands[] .= self::wysiwygCmdToTiny($rows[$i][$j]->cmd);
						}
						$j++;
					}
					$tinyRows = rtrim($tinyRows, ',') . '",';
					$i++;
					$k++;
				}
				$tinyRows .= 'theme_advanced_buttons' . $k . ' : "",';

				$this->tinyPlugins = implode(',', array_unique($this->tinyPlugins));
				$this->wePlugins = implode(',', array_intersect($this->wePlugins, $allCommands));
				$plugins = ($this->createContextmenu ? 'wecontextmenu,' : '') .
					($this->tinyPlugins ? $this->tinyPlugins . ',' : '') .
					($this->wePlugins ? $this->wePlugins . ',' : '') .
					'weutil,autolink,template,wewordcount'; //TODO: load "templates" on demand as we do it with other plugins
				//fast fix for textarea-height. TODO, when wysiwyg is thrown out: use or rewrite existing methods like getToolbarWithAndHeight()
				$toolBarHeight = $this->buttonpos == 'external' ? 0 : ($k - 1) * 26 + 22 - $k * 3;
				$this->height += $toolBarHeight;

				if(preg_match('/^#[a-f0-9]{6}$/i', $this->bgcol)){
					$this->bgcol = substr($this->bgcol, 1);
				} else if(!preg_match('/^[a-f0-9]{6}$/i', $this->bgcol) && !preg_match('/^[a-z]*$/i', $this->bgcol)){
					$this->bgcol = '';
				}

				$wefullscreenVars = array(
					'outsideWE' => $this->outsideWE ? "1" : "",
					'xml' => $this->xml ? "1" : "",
					'removeFirstParagraph' => $this->removeFirstParagraph ? "1" : "0",
				);

				$contentCss = empty($this->contentCss) ? '' : $this->contentCss . ',';
				$editorLang = we_core_Local::weLangToLocale($GLOBALS['WE_LANGUAGE']);
				$editorLangSuffix = $editorLang == 'de' ? 'de_' : '';

				return we_html_element::jsElement('
					' . ($this->fieldName ? '
/* -- tinyMCE -- */

/*
To adress an instance of tinyMCE by JavaScript from anywhere on this document use:
TinyWrapper("SOME_WE_FIELDNAME").getEditor();

To adress the div container of an editor inlineedit=false use:
TinyWrapper("SOME_WE_FIELDNAME").getDiv();

WE_FIELDNAME of THIS instance is: "' . $this->fieldName . '"
*/

/*
//if you want to add additional event listeners to THIS instance of tinyMCE
//copy the following function to your webEdition template and edit its content
' . ($this->fieldName_clean == $this->fieldName ? '' : '//ATTENTION: the field name in the following function name was changed due to javasript restrictions!') . '

function we_tinyMCE_' . $this->fieldName_clean . '_init(ed){
	//you can adress this instance of tinyMCE using variable ed:
	//var this_editor = ed;
	//or:
	var this_editor = TinyWrapper("' . $this->fieldName . '").getEditor();

	//to adress other instances of tinyMCE on this same page use:
	TinyWrapper("OTHER_WE_FIELDNAME").getEditor();

	//example of adding event listener
	var this_editor = TinyWrapper("' . $this->fieldName . '");
	this_editor.on("KeyPress", function(ed, event){
			//console.log(ed.editorId);
			//console.log(event.charCode);
	});
}
*/

/*
read more about event listeners of the tiny editor object in the tinyMCE API,
and have a look at /webEdition/js/weTinyMceFunctions to see what TinyWrapper can do for you
*/

' : '') . '

var weclassNames_tinyMce = new Array (' . $this->cssClassesJS . ');

var tinyMceTranslationObject = {' . $editorLang . ':{
	we:{
		"group_link":"' . g_l('wysiwyg', "[links]") . '",//(insert_hyperlink)
		"group_copypaste":"' . g_l('wysiwyg', "[insert_text]") . '",
		"group_advanced":"' . g_l('wysiwyg', "[advanced]") . '",
		"group_insert":"' . g_l('wysiwyg', "[insert]") . '",
		"group_indent":"' . g_l('wysiwyg', "[indent]") . '",
		//"group_view":"' . g_l('wysiwyg', "[view]") . '",
		"group_table":"' . g_l('wysiwyg', "[table]") . '",
		"group_edit":"' . g_l('wysiwyg', "[edit]") . '",
		"group_layer":"' . g_l('wysiwyg', "[layer]") . '",
		"group_xhtml":"' . g_l('wysiwyg', "[xhtml_extras]") . '",
		"tt_weinsertbreak":"' . g_l('wysiwyg', "[insert_br]") . '",
		"tt_welink":"' . g_l('wysiwyg', "[hyperlink]") . '",
		"tt_weimage":"' . g_l('wysiwyg', "[insert_edit_image]") . '",
		"tt_wefullscreen":"' . g_l('wysiwyg', "[fullscreen]") . '",
		"tt_welang":"' . g_l('wysiwyg', "[language]") . '",
		"tt_wespellchecker":"' . g_l('wysiwyg', "[spellcheck]") . '",
		"tt_wevisualaid":"' . g_l('wysiwyg', "[visualaid]") . '",
		"cm_inserttable":"' . g_l('wysiwyg', "[insert_table]") . '",
		"cm_table_props":"' . g_l('wysiwyg', "[edit_table]") . '"
	}}};


var tinyMceConfObject__' . $this->fieldName_clean . ' = {
	wePluginClasses : {
		"weadaptbold" : "' . $editorLangSuffix . 'weadaptbold",
		"weadaptitalic" : "' . $editorLangSuffix . 'weadaptitalic",
		"weabbr" : "' . $editorLangSuffix . 'weabbr",
		"weacronym" : "' . $editorLangSuffix . 'weacronym"
	},

	weFullscrenParams : {
		"outsideWE" : "' . $wefullscreenVars['outsideWE'] . '",
		"xml" : "' . $wefullscreenVars['xml'] . '",
		"removeFirstParagraph" : "' . $wefullscreenVars['removeFirstParagraph'] . '",
		"baseHref" : "' . urlencode($this->baseHref) . '",
		"charset" : "' . $this->charset . '",
		"cssClasses" : "' . urlencode($this->cssClasses) . '",
		"fontnames" : "' . urlencode($this->fontnamesCSV) . '",
		"bgcolor" : "' . $this->bgcol . '",
		"language" : "' . $this->Language . '",
		"screenWidth" : screen.availWidth-10,
		"screenHeight" : screen.availHeight - 70,
		"className" : "' . $this->className . '",
		"propString" : "' . urlencode($this->propstring) . '",
		"contentCss" : "' . urlencode($this->contentCss) . '",
		"origName" : "' . urlencode($this->origName) . '",
		"tinyParams" : "' . urlencode($this->tinyParams) . '",
		"contextmenu" : "' . urlencode(trim($this->restrictContextmenu, ',')) . '",
		"templates" : "' . $this->templates . '"
	},
	weClassNames_urlEncoded : "' . urlencode($this->cssClassesCSV) . '",
	weIsFrontend : "' . ($this->isFrontendEdit ? 1 : 0) . '",
	weWordCounter : 0,
	weRemoveFirstParagraph : "' . ($this->removeFirstParagraph ? 1 : 0) . '",

	language : "' . $lang . '",
	mode : "exact",
	elements : "' . $this->name . '",
	theme : "advanced",
	//dialog_type : "modal",

	accessibility_warnings : false,
	relative_urls : false, //important!
	convert_urls : false, //important!
	//force_br_newlines : true,
	force_p_newlines : 0, // value 0 instead of true (!) prevents adding additional lines with <p>&nbsp</p> when inlineedit="true"
	//forced_root_block : "",

	entity_encoding : "named",
	entities : "160,nbsp",
	element_format: "' . $this->xml . '",
	body_class : "' . ($this->className ? $this->className . " " : "") . 'wetextarea tiny-wetextarea wetextarea-' . $this->origName . '",

	//CallBacks
	//file_browser_callback : "openWeFileBrowser",
	//onchange_callback : "tinyMCEchanged",

	plugins : "' . $plugins . '",
	we_restrict_contextmenu: ' . $this->getContextmenuCommands() . ',

	// Theme options
	' . $tinyRows . '
	theme_advanced_toolbar_location : "' . $this->buttonpos . '", //external: toolbar floating on top of textarea
	theme_advanced_fonts: "' . $this->tinyFonts . '",
	theme_advanced_styles: "' . $this->cssClasses . '",
	theme_advanced_blockformats : "' . $this->tinyFormatblock . '",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "' . $this->statuspos . '",
	theme_advanced_resizing : false,
	//theme_advanced_source_editor_height : "500",
	//theme_advanced_source_editor_width : "700",
	theme_advanced_default_foreground_color : "#FF0000",
	theme_advanced_default_background_color : "#FFFF99",
	plugin_preview_height : "300",
	plugin_preview_width : "500",
	theme_advanced_disable : "",
	//paste_text_use_dialog: true,
	//fullscreen_new_window: true,
	content_css : "' . WEBEDITION_DIR . 'editors/content/tinymce/we_tinymce/contentCssFirst.php?' . time() . '=,' . $contentCss . WEBEDITION_DIR . 'editors/content/tinymce/we_tinymce/contentCssLast.php?' . time() . '=&tinyMceBackgroundColor=' . $this->bgcol . '",
	popup_css_add : "' . WEBEDITION_DIR . 'editors/content/tinymce/we_tinymce/tinyDialogCss.php",
	' . (in_array('template', $allCommands) ? $this->getTemplates() : '') . '

	// Skin options
	skin : "o2k7",
	skin_variant : "silver",

	' . ($this->tinyParams ? '//params from attribute tinyparams
	' . $this->tinyParams . ',' : '') . '

	//Fix: ad attribute id to anchor
	init_instance_callback: function(ed) {
		ed.serializer.addNodeFilter("a", function(nodes) {
			tinymce.each(nodes, function(node) {
				if(!node.attr("href") && !node.attr("id")){
					node.attr("id", node.attr("name"));
				}
			});
		});
	},

	setup : function(ed){

		ed.settings.language = "' . we_core_Local::weLangToLocale($GLOBALS['WE_LANGUAGE']) . '";

		ed.onInit.add(function(ed, o){
			//TODO: clean up the mess in here!
			ed.pasteAsPlainText = ' . $pastetext . ';
			ed.controlManager.setActive("pastetext", ' . $pastetext . ');
			var openerDocument = ' . (!$this->isInPopup ? '""' : ($this->isFrontendEdit ? 'top.opener.document' : 'top.opener.top.weEditorFrameController.getVisibleEditorFrame().document')) . ';
			' . ($this->isInPopup ? '
			try{
				ed.setContent(openerDocument.getElementById("' . $this->name . '").value)
			}catch(e){
				//console.log("failed getting content from main window");
			}
			' : '') . '
			' . ($this->fieldName ? '
			tinyEditors["' . $this->fieldName . '"] = ed;

			var hasOpener = false;
			try{
				hasOpener = opener ? true : false;
			} catch(e){}

			if(typeof we_tinyMCE_' . $this->fieldName_clean . '_init != "undefined"){
				try{
					we_tinyMCE_' . $this->fieldName_clean . '_init(ed);
				} catch(e){
					//nothing
				}
			} else if(hasOpener){
				if(opener.top.weEditorFrameController){
					//we are in backend
					var editor = opener.top.weEditorFrameController.ActiveEditorFrameId;
					var wedoc = null;
					try{
						wedoc = opener.top.rframe.bm_content_frame.frames[editor].frames["contenteditor_" + editor];
						wedoc.tinyEditorsInPopup["' . $this->fieldName . '"] = ed;
						wedoc.we_tinyMCE_' . $this->fieldName_clean . '_init(ed);
					}catch(e){
						//opener.console.log("no external init function for ' . $this->fieldName . ' found");
					}
					try{
						wedoc = opener.top.rframe.bm_content_frame.frames[editor].frames["editor_" + editor];
						wedoc.tinyEditorsInPopup["' . $this->fieldName . '"] = ed;
						wedoc.we_tinyMCE_' . $this->fieldName_clean . '_init(ed);
					}catch(e){
						//opener.console.log("no external init function for ' . $this->fieldName . ' found");
					}
				} else{
					//we are in frontend
					try{
						opener.tinyEditorsInPopup["' . $this->fieldName . '"] = ed;
						opener.we_tinyMCE_' . $this->fieldName_clean . '_init(ed);
					}catch(e){
						//opener.console.log("no external init function for ' . $this->fieldName . ' defined");
					}
				}
			} else{
				//console.log("no external init function for ' . $this->fieldName . ' defined");
			}
			' : '') . '
		});

		ed.onPostProcess.add(function(ed, o) {
			var c = document.createElement("div");
			c.innerHTML = o.content;
			var first = c.firstChild;

			if(first){
				if(first.innerHTML == "&nbsp;" && first == c.lastChild){
				c.innerHTML = "";
			}
			else if(ed.settings.weRemoveFirstParagraph === "1" && first.nodeName == "P"){
				var useDiv = false, div = document.createElement("div"), attribs = ["style", "class", "dir"];
				div.innerHTML = first.innerHTML;

				for(var i=0;i<attribs.length;i++){
					if(first.hasAttribute(attribs[i])){
						div.setAttribute(attribs[i], first.getAttribute(attribs[i]));
						useDiv = true;
					}
				}
				if(useDiv){
					c.replaceChild(div, first);
				} else{
					c.removeChild(first);
					c.innerHTML = first.innerHTML + c.innerHTML;
					}
				}
			}
			o.content = c.innerHTML;
		});' .($this->isFrontendEdit ? '' : '

		/* set EditorFrame.setEditorIsHot(true) */

		// we look for editorLevel and weEditorFrameController just once at editor init
		var editorLevel = "";
		var weEditorFrame = null;

		if(typeof(_EditorFrame) != "undefined"){
			editorLevel = "inline";
			weEditorFrame = _EditorFrame;
		} else {
			if(top.opener != null && typeof(top.opener.top.weEditorFrameController) != "undefined" && typeof(top.isWeDialog) == "undefined"){
				editorLevel = "popup";
				weEditorFrame = top.opener.top.weEditorFrameController;
			} else {
				editorLevel = "fullscreen";
				weEditorFrame = null;
			}
		}

		// if editorLevel = "inline" we use a local copy of weEditorFrame.EditorIsHot
		var weEditorFrameIsHot = false;
		try{
			weEditorFrameIsHot = editorLevel == "inline" ? weEditorFrame.EditorIsHot : false;
		}catch(e){}

		// listeners for editorLevel = "inline"
		//could be rather CPU-intensive. But weEditorFrameIsHot is nearly allways true, so we could try
		/*
		ed.onKeyDown.add(function(ed) {
			if(!weEditorFrameIsHot && editorLevel == "inline" && ed.isDirty()){
				try{
					weEditorFrame.setEditorIsHot(true);
				} catch(e) {}
				weEditorFrameIsHot = true;
			}
		});
		*/

		ed.onChange.add(function(ed) {
			if(!weEditorFrameIsHot && editorLevel == "inline" && ed.isDirty()){
				try{
					weEditorFrame.setEditorIsHot(true);
				} catch(e) {}
				weEditorFrameIsHot = true;
			}
		});

		ed.onNodeChange.add(function(ed, cm, n) {
			var td = ed.dom.getParent(n, "td");
			if(typeof td === "object" && td && td.getElementsByTagName("p").length === 1){
				var inner = td.getElementsByTagName("p")[0].innerHTML;
				td.innerHTML = "";
				ed.selection.setContent(inner)
			}
		});

		ed.onClick.add(function(ed) {
			if(!weEditorFrameIsHot && editorLevel == "inline" && ed.isDirty()){
				try{
					weEditorFrame.setEditorIsHot(true);
				} catch(e) {}
				weEditorFrameIsHot = true;
			}
		});

		ed.onPaste.add(function(ed) {
			if(!weEditorFrameIsHot && editorLevel == "inline" && ed.isDirty()){
				try{
					weEditorFrame.setEditorIsHot(true);
				} catch(e) {}
				weEditorFrameIsHot = true;
			}
		});

		// onSave (= we_save and we_publish) we reset the (tiny-internal) flag weEditorFrameIsHot to false
		ed.onSaveContent.add(function(ed) {
			weEditorFrameIsHot = false;
			// if is popup and we click on ok
			if(editorLevel == "popup" && ed.isDirty()){
				try{
					weEditorFrame.setEditorIsHot(true);
				} catch(e) {}
			}
		});
		') . '
	}
}
tinyMCE.addI18n(tinyMceTranslationObject);
tinyMCE.init(tinyMceConfObject__' . $this->fieldName_clean . ');
') .
					'
<textarea wrap="off" style="color:#eeeeee; background-color:#eeeeee;  width:' . (max($this->width, $this->maxGroupWidth + 8)) . 'px; height:' . $this->height . 'px;" id="' . $this->name . '" name="' . $this->name . '">' . str_replace(array('\n', '&'), array('', '&amp;'), $editValue) . '</textarea>';

			case 'default':

//parseInternalLinks($editValue,0);

				$min_w = 0;
				$row_w = 0;
				$pixelrow = '<tr><td style="background-image:url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif);" class="tbButtonWysiwygDefaultStyle tbButtonWysiwygBackground">' . we_html_tools::getPixel($this->width, 2) . '</td></tr>';
				$linerow = '<tr><td ><div class="tbButtonsHR" class="tbButtonWysiwygDefaultStyle"></div></td></tr>';
				$out = we_html_element::jsElement('var weLastPopupMenu = null; var wefoo = "' . $this->ref . 'edit"; wePopupMenuArray[wefoo] = new Array();') . '<table id="' . $this->ref . 'edit_table" border="0" cellpadding="0" cellspacing="0" width="' . $this->width . '" class="tbButtonWysiwygDefaultStyle"><tr><td  style="background-image:url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif);" class="tbButtonWysiwygDefaultStyle tbButtonWysiwygBackground">';
				foreach($rows as $r => $curRow){
					$out .= '<table border="0" cellpadding="0" cellspacing="0" class="tbButtonWysiwygDefaultStyle"><tr>';
					foreach($curRow as $curCol){
						$out .= '<td class="tbButtonWysiwygDefaultStyle">' . $curCol->getHTML() . '</td>';
						$row_w += $curCol->width;
					}
					$min_w = max($min_w, $row_w);
					$row_w = 0;
					$out .= '</tr></table></td></tr>' . (($r < count($rows) - 1) ? $linerow : $pixelrow) . '<tr><td ' . (($r < (count($rows) - 1)) ? (' style="background-image:url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif);"') : '') . ' class="tbButtonWysiwygDefaultStyle' . (($r < (count($rows) - 1)) ? ' tbButtonWysiwygBackground' : '') . '">';
				}

				$realWidth = max($min_w, $this->width);
				$out .= '<table border="0" cellpadding="0" cellspacing="0"  class="tbButtonWysiwygDefaultStyle"><tr><td class="tbButtonWysiwygDefaultStyle"><textarea wrap="off" style="color:black; display: none;font-family: courier; font-size: 10pt; width:' . $realWidth . 'px; height:' . $this->height . 'px;" id="' . $this->ref . 'edit_src" name="' . $this->ref . 'edit_src"></textarea><iframe contenteditable  width="' . $realWidth . 'px" height="' . $this->height . 'px" name="' . $this->ref . 'edit" id="' . $this->ref . 'edit" allowTransparency="true" ' .
					'style="display: block;color: black;border: 1px solid #A5ACB2;' .
					(we_base_browserDetect::isSafari() ? '-khtml-user-select:none;"  src="' . WEBEDITION_DIR . 'editors/content/wysiwyg/empty.html"' : '"') .
					'></iframe></td></tr>
</table></td></tr></table><input type="hidden" id="' . $this->name . '" name="' . $this->name . '" value="' . oldHtmlspecialchars($this->hiddenValue) . '" /><div id="' . $this->ref . 'edit_buffer" style="display: none;"></div>
' . we_html_element::jsElement('
var ' . $this->ref . 'Obj = null;
' . $this->ref . 'Obj = new weWysiwyg("' . $this->ref . 'edit","' . $this->name . '","' . str_replace("\"", "\\\"", $this->value) . '","' . str_replace("\"", "\\\"", $editValue) . '",\'' . $this->fullscreen . '\',\'' . $this->className . '\',\'' . $this->propstring . '\',\'' . $this->bgcol . '\',' . ($this->outsideWE ? "true" : "false") . ',"' . $this->baseHref . '","' . $this->xml . '","' . $this->removeFirstParagraph . '","' . $this->charset . '","' . $this->cssClasses . '","' . $this->Language . '", "' . ($this->isFrontendEdit ? 1 : 0) . '");
we_wysiwygs[we_wysiwygs.length] = ' . $this->ref . 'Obj;

function ' . $this->ref . 'editShowContextMenu(event){
	return ' . $this->ref . 'Obj.showContextMenu(event);
}
function ' . $this->ref . 'editonkeydown(){
	return we_on_key_down(' . $this->ref . 'Obj);
}
function ' . $this->ref . 'editonkeyup(){
	return we_on_key_up(' . $this->ref . 'Obj);
}
function ' . $this->ref . 'editonmouseup(){
	return we_on_mouse_up(' . $this->ref . 'Obj);
}
function ' . $this->ref . 'editonfocus(){
	return we_on_focus(' . $this->ref . 'Obj);
}
function ' . $this->ref . 'editonblur(){
	return we_on_blur(' . $this->ref . 'Obj);
}');
				return $out;
		}
	}

}
