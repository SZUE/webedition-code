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
 * @subpackage we_ui_dialog
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Class to build a Dialog with a Ok and Cancel Button.
 *
 * @category   we
 * @package none
 * @subpackage we_ui_dialog
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_dialog_OkCancelDialog extends we_ui_layout_Dialog{

	/**
	 * yesAction attribute
	 *
	 * @var string
	 */
	protected $_okAction = '';

	/**
	 * cancelAction attribute
	 *
	 * @var string
	 */
	protected $_cancelAction = '';

	/**
	 * message attribute
	 *
	 * @var string
	 */
	protected $_message = '';

	/**
	 * encodeMessage attribute
	 *
	 * @var boolean
	 */
	protected $_encodeMessage = true;

	/*
	 * Static variable to hold singleton instance
	 */
	protected $_topClose = true;

	/**
	 * Constructor
	 *
	 * Sets object properties if set in $properties array
	 *
	 * @param array $properties associative array containing named object properties
	 * @return void
	 */
	public function __construct($properties = null){
		parent::__construct($properties);

		// add needed CSS files
		$this->addCSSFile(we_ui_layout_Themes::computeCSSURL(__CLASS__));
	}

	/**
	 * Renders and returns HTML of OkCancelDialog
	 *
	 * @return string
	 */
	protected function _renderHTML(){

		$translate = we_core_Local::addTranslation('apps.xml');

		$table = new we_ui_layout_Table();
		$table->nextColumn();
		if($this->_headline != ""){
			$this->_bodyHTML = '<div class ="we_ui_dialog_Headline">' . nl2br($this->_headline) . "</div>" . $this->_bodyHTML;
		}

		$table->addHTML('<div>' . nl2br($this->_encodeMessage ? oldHtmlspecialchars($this->_message) : $this->_message) . '</div>');
		$this->addElement($table);

		$buttonOk = new we_ui_controls_Button(array('text' => $translate->_('Ok'), 'onClick' => $this->_okAction . '; ' . ($this->_topClose ? 'top.close()' : ''), 'type' => 'onClick', 'width' => 100));

		if(!$this->_okAction){
			$buttonOk->setDisabled(true);
		}

		$buttonCancel = new we_ui_controls_Button(array('text' => $translate->_('Cancel'), 'onClick' => 'top.close()', 'type' => 'onClick', 'width' => 100));

		$buttonTable = new we_ui_layout_ButtonTableYesNo();
		$buttonTable->setYesOkButton($buttonOk);
		$buttonTable->setCancelButton($buttonCancel);
		$buttonTable->setStyle('margin-top:10px;margin-right:10px;margin-left:auto;');

		$buttonsHTML = '<div class="editfooter">' . $buttonTable->getHTML() . '</div>';
		$this->addCSSFiles($buttonTable->getCSSFiles());
		$this->addJSFiles($buttonTable->getJSFiles());
		$this->addHTML($buttonsHTML);

		return parent::_renderHTML();
	}

	/**
	 * retrieve cancelAction
	 *
	 * @return string
	 */
	public function getCancelAction(){
		return $this->_cancelAction;
	}

	/**
	 * retrieve message
	 *
	 * @return string
	 */
	public function getMessage(){
		return $this->_message;
	}

	/**
	 * retrieve okAction
	 *
	 * @return string
	 */
	public function getOkAction(){
		return $this->_okAction;
	}

	/**
	 * set cancelAction
	 *
	 * @param string $cancelAction
	 */
	public function setCancelAction($cancelAction){
		$this->_cancelAction = $cancelAction;
	}

	/**
	 * set message
	 *
	 * @param string $message
	 */
	public function setMessage($message){
		$this->_message = $message;
	}

	/**
	 * set topClose
	 *
	 * @param boolean $close
	 */
	public function setTopClose($close){
		$this->_topClose = $close;
	}

	/**
	 * set okAction
	 *
	 * @param string $yesAction
	 */
	public function setOkAction($okAction){
		$this->_okAction = $okAction;
	}

	/**
	 * retrieve encodeMessage
	 *
	 * @return string
	 */
	public function getEncodeMessage(){
		return $this->_encodeMessage;
	}

	/**
	 * set encodeMessage
	 *
	 * @param string $encodeMessage
	 */
	public function setEncodeMessage($encodeMessage){
		$this->_encodeMessage = $encodeMessage;
	}

}
