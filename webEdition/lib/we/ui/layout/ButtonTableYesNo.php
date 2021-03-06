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
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Class to display a ButtonTable Yes / No / Cancel Choice
 *
 * @category   we
 * @package none
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_layout_ButtonTableYesNo extends we_ui_layout_ButtonTable{

	/**
	 * Default class name for button
	 */
	const kButtonTableClassNormal = 'we_ui_layout_ButtonTable';

	/**
	 * html of yes or ok button
	 *
	 * @var string
	 */
	protected $_yesOkButton = '';

	/**
	 * html of no button
	 *
	 * @var string
	 */
	protected $_noButton = '';

	/**
	 * html of cancel button
	 *
	 * @var string
	 */
	protected $_cancelButton = '';

	/**
	 * Retrieve yes or ok button
	 *
	 * @return string
	 */
	public function getYesOkButton(){
		return $this->_yesOkButton;
	}

	/**
	 * Set yes or ok button
	 *
	 * @param string $_yesOkButton
	 */
	public function setYesOkButton($_yesOkButton){
		$this->_yesOkButton = $_yesOkButton;
	}

	/**
	 * Retrieve no button
	 *
	 * @return string
	 */
	public function getNoButton(){
		return $this->_noButton;
	}

	/**
	 * Set no button
	 *
	 * @param string $_noButton
	 */
	public function setNoButton($_noButton){
		$this->_noButton = $_noButton;
	}

	/**
	 * Retrieve cancel button
	 *
	 * @return string
	 */
	public function getCancelButton(){
		return $this->_cancelButton;
	}

	/**
	 * Set cancel button
	 *
	 * @param string $_cancelButton
	 */
	public function setCancelButton($_cancelButton){
		$this->_cancelButton = $_cancelButton;
	}

	/**
	 * This function prepares ok, no, cancel - buttons matching to the OS
	 * and places them at the right ($align) side
	 *
	 * For Mac OS         : NO, CANCEL, YES
	 * For Windows & Linux: OK, NO, CANCEL
	 *
	 * @return string
	 */
	function preparesButtonTableYesNo(){
		$yes_ok_button = $this->getYesOkButton();
		$no_button = $this->getNoButton();
		$cancel_button = $this->getCancelButton();
		$client = we_ui_Client::getInstance();

		if($client->getSystem() == we_ui_Client::kSystemMacOS){
			if(is_object($no_button)){
				$this->addElement($no_button);
				$this->nextColumn();
			}
			if(is_object($cancel_button)){
				$this->addElement($cancel_button);
				$this->nextColumn();
			}
			if(is_object($yes_ok_button)){
				$this->addElement($yes_ok_button);
				$this->nextColumn();
			}
		} else {
			if(is_object($yes_ok_button)){
				$this->addElement($yes_ok_button);
				$this->nextColumn();
			}
			if(is_object($no_button)){
				$this->addElement($no_button);
				$this->nextColumn();
			}
			if(is_object($cancel_button)){
				$this->addElement($cancel_button);
				$this->nextColumn();
			}
		}
	}

	/**
	 * called before _renderHTML() is called
	 * for HTMLDocuments we don't need to do anything here,
	 * so we overwrite it with an empty function
	 *
	 * @return void
	 */
	protected function _willRenderHTML(){
		$this->preparesButtonTableYesNo();

		parent::_willRenderHTML();
	}

}
