<?php

/**
 * webEdition SDK
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package none
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Class to build a HTML page
 *
 * @category   we
 * @package none
 * @subpackage we_ui_layout
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_layout_HTMLPage extends we_ui_abstract_AbstractElement{

	/**
	 * title tag
	 *
	 * @var string
	 */
	protected $_title = 'webEdition (http://www.webedition.org)';

	/**
	 * app name
	 *
	 * @var string
	 */
	protected $_appName = '';

	/**
	 * array that holds internal css code to include into page
	 *
	 * @var array
	 */
	protected $_inlineCSS = array();

	/*
	 * Holds the HTML for a frameset
	 *
	 * @var string
	 */
	protected $_framesetHTML = '';

	/**
	 * array that holds internal js code to include into page
	 *
	 * @var array
	 */
	protected $_inlineJS = array();

	/**
	 * string with innerHTML of the <body> Tag
	 *
	 * @var string
	 */
	protected $_bodyHTML = '';

	/**
	 * string with name of charset
	 *
	 * @var string
	 */
	protected $_charset = '';

	/**
	 * string with doctype tag
	 *
	 * @var string
	 */
	protected $_doctype = '';

	/**
	 * array with attributes to insert in the body tag
	 *
	 * @var string
	 */
	protected $_bodyAttributes = array();
	protected $_isTopFrame = false;

	/**
	 * adds HTML Code to innerHTML of body tag
	 *
	 * @param string $html
	 * @return void
	 */
	public function addHTML($html){
		$this->_bodyHTML .= $html;
	}

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct(){
		$this->_doctype = we_html_element::htmlDocType();
		$charset = we_core_Local::getComputedUICharset();
		$this->setCharset($charset);

		$controller = Zend_Controller_Front::getInstance();
		$this->_appName = $controller->getParam('appName') ? : '';
		if($controller->getResponse()){
			$controller->getResponse()->setHeader('Content-Type', 'text/html; charset=' . $charset, true);
		} else {
			we_html_tools::headerCtCharset('text/html', $charset);
		}
		parent::__construct();
		$this->addCSSFile(we_ui_layout_Themes::computeCSSUrl(__CLASS__));
	}

	/*
	 * gets a singleton instance.
	 *
	 * @return we_ui_layout_HTMLPage
	 */

	public static function getInstance(){
		static $__instance = NULL;
		if($__instance === NULL){
			$__instance = new self();
		}
		return $__instance;
	}

	/*
	 * avoid calling clone()
	 */

	private function __clone(){

	}

	/**
	 * adds an element to the page. The elements HTML
	 * will be added to the innerHTML of the body tag
	 *
	 * @param we_ui_abstract_AbstractElement $elem
	 * @return void
	 */
	public function addElement($elem){
		$this->addCSSFiles($elem->getCSSFiles());
		$this->addJSFiles($elem->getJSFiles());
		$this->_bodyHTML .= $elem->getHTML();
	}

	/**
	 * adds CSS code to the page
	 * Will be inserted into the header section of the page
	 * using the style tag
	 *
	 * @param string $css CSS code to add
	 * @return void
	 */
	public function addInlineCSS($css){
		if($css){
			$this->_inlineCSS[] = $css;
		}
	}

	/**
	 * adds JavaScript code to the page
	 * Will be inserted into the header section of the page
	 * using the script tag
	 *
	 * @param string $js JavaScript code to add
	 * @return void
	 */
	public function addInlineJS($js){
		if($js){
			$this->_inlineJS[] = $js;
		}
	}

	/**
	 * adds body attribute
	 *
	 * @param string $name  name of attribute
	 * @param string $value value of attribute
	 * @return void
	 */
	public function addBodyAttribute($name, $value){
		$this->_bodyAttributes[$name] = $value;
	}

	/**
	 * renders and returns the HTML code of the page
	 * will be called from getHTML()
	 *
	 * @return string
	 */
	protected function _renderHTML(){

		$this->addJSFiles(array(
			LIB_DIR . 'we/core/JsonRpc.js',
		));

		$js = '';
		// write in all frames except in top frame
		if(!$this->_isTopFrame){
			$js = <<<EOS

function weGetTop() {
	return  (self != parent && parent.weGetTop !== undefined?
		parent.weGetTop():parent);
}

function weCC() {
	if (weGetTop().we_core_CmdController !== undefined) {
		return weGetTop().we_core_CmdController.getInstance();
	} else if (opener){
		if (opener.we_core_CmdController !== undefined) {
			return opener.we_core_CmdController.getInstance();
		} else if (opener.weCC !== undefined){
			return opener.weCC();
		}
	}
	return null;
}

function weEC() {
	var topFrame = weGetTop();
	if (topFrame.weEventController !== undefined) {
		return topFrame.weEventController;
	} else if (opener){
		if (opener.weEventController !== undefined) {
			return opener.weEventController;
		} else if (opener.weEC != undefined){
			return opener.weEC();
		}
	}
	return null;
}

var weCmdController = weCC();
var weEventController = weEC();

EOS;
		} else {

			$this->addJSFile(LIB_DIR . 'we/core/CmdController.js');
			$this->addJSFile(LIB_DIR . 'we/core/EventController.js');

			if(!$this->_appName || ($this->_appName && !we_app_Common::isJMenu($this->_appName))){
				for($i = 0; $i < count($this->_CSSFiles); $i++){
					if($this->_CSSFiles[$i]['path'] == we_ui_layout_Themes::computeCSSUrl(__CLASS__)){
						unset($this->_CSSFiles[$i]);
					}
				}
				$this->addCSSFile(WEBEDITION_DIR . 'css/menu/pro_drop_1.css');
				$this->addJSFile(JS_DIR . 'menu/clickMenu.js');
				$this->addCSSFile(LIB_DIR . 'we/ui/themes/default/we_ui_controls_MessageConsole/style.css');
			}
			$js = <<<EOS

var weCmdController = we_core_CmdController.getInstance();
var weEventController = new we_core_EventController();
EOS;
		}
		$html = // add doctype tag if not empty
				($this->getDoctype() !== '' ? $this->getDoctype() . "\n" : '') .
				// add <html> tag
				'<html' . ($this->getLang() !== '' ? ' lang="' . $this->getLang() . '"' : '') . '>' .
				// add <header> tag
				'<head>' .
				// add meta tag for charset if not empty
				($this->getCharset() !== '' ? we_html_tools::htmlMetaCtCharset($this->getCharset()) : '') .
				// add title tag if not empty
				($this->getTitle() !== '' ? '<title>' . $this->getTitle() . '</title>' : '');

		// add link tags for external CSS files
		foreach($this->_CSSFiles as $file){
			$html .= '<link rel="stylesheet" type="text/css" href="' . $file['path'] . '" media="' . $file['media'] . '" />';
		}

		// add inline CSS
		if($this->_inlineCSS){
			$html .= "<style>";
			foreach($this->_inlineCSS as $code){
				$html .= $code . "\n";
			}
			$html .= "</style>";
		}

		$html .= STYLESHEET .
			we_html_element::cssLink(CSS_DIR . 'apps.css') .
			we_html_element::jsScript(JS_DIR . 'global.js', 'initWE();') .
			YAHOO_FILES;

		// add javascript tags for external JavaScript files
		foreach($this->_JSFiles as $file){
			$html .= we_html_element::jsScript($file);
		}

		$html .= we_html_element::jsElement($js . implode('', $this->_inlineJS)) .
				// add head end tag
				'</head>';
		return $html . ($this->_framesetHTML !== '' ?
						$this->_framesetHTML :
						getHtmlTag('body', $this->_bodyAttributes, $this->getBodyHTML()) ) .
				'</html>';
	}

	/**
	 * called before _renderHTML() is called
	 * for HTMLDocuments we don't need to do anything here,
	 * so we overwrite it with an empty function
	 *
	 * @return void
	 */
	protected function _willRenderHTML(){

	}

	/**
	 * Retrieve body attributes as an associative array
	 *
	 * @return array
	 */
	public function getBodyAttributes(){
		return $this->_bodyAttributes;
	}

	/**
	 * Retrieve innerHTML of body tag
	 *
	 * @return string
	 */
	public function getBodyHTML(){
		return $this->_bodyHTML;
	}

	/**
	 * Retrieve charset of page
	 *
	 * @return string
	 */
	public function getCharset(){
		return $this->_charset;
	}

	/**
	 * Retrieve doctype tag of page
	 *
	 * @return string
	 */
	public function getDoctype(){
		return $this->_doctype;
	}

	/**
	 * Retrieve frameset HTML
	 *
	 * @return string
	 */
	public function getFramesetHTML(){
		return $this->_framesetHTML;
	}

	/**
	 * set body attributes
	 *
	 * @param array $bodyAttributes
	 * @return void
	 */
	public function setBodyAttributes($bodyAttributes){
		$this->_bodyAttributes = $bodyAttributes;
	}

	/**
	 * set innerHTML of body tag
	 *
	 * @param string $bodyHTML
	 * @return void
	 */
	public function setBodyHTML($bodyHTML){
		$this->_bodyHTML = $bodyHTML;
	}

	/**
	 * set charset of page
	 *
	 * @param string $charset
	 * @return void
	 */
	public function setCharset($charset){
		$this->_charset = $charset;
	}

	/**
	 * set doctype tag of page
	 *
	 * @param string $doctype
	 * @return void
	 */
	public function setDoctype($doctype){
		$this->_doctype = $doctype;
	}

	/**
	 * Set frameset HTML of page
	 *
	 * @param string $frameset
	 * @return void
	 */
	public function setFramesetHTML($frameset){
		$this->_framesetHTML = $frameset;
	}

	/**
	 * set frameset  for page
	 *
	 * @param we_ui_layout_Frameset $frameset
	 * @return void
	 */
	public function setFrameset($frameset){
		$this->_framesetHTML = $frameset->getHTML($this->_isTopFrame, $this->_appName);
	}

	/**
	 * @return unknown
	 */
	public function getIsTopFrame(){
		return $this->_isTopFrame;
	}

	/**
	 * @return unknown
	 */
	public function isTopFrame(){
		return $this->getIsTopFrame();
	}

	/**
	 * @param unknown_type $isTopFrame
	 */
	public function setIsTopFrame($isTopFrame){
		$this->_isTopFrame = $isTopFrame;
	}

}
