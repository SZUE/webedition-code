<?php
/**
 * webEdition SDK
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
 * @subpackage we_app_controller
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Base EditorAction Controller
 *
 * @category   we
 * @package none
 * @subpackage we_app_controller
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_app_controller_EditorAction extends Zend_Controller_Action{
	/**
	 * view
	 */
	public $view;

	/**
	 * model
	 */
	protected $_model;

	/**
	 * The default action - show the home page
	 * @return void
	 */
	public function indexAction(){
		$this->_setupModel(true);
		if($this->getRequest()->getParam(we_base_ContentTypes::FOLDER) == 1){
			$this->_model->IsFolder = 1;
			$this->_model->ContentType = we_base_ContentTypes::FOLDER;
		}
		$this->_renderDefaultView('editor/index.php');
	}

	/**
	 * The body action - show the body
	 * @return void
	 */
	public function bodyAction(){
		$this->_setupModel();
		$this->_processPostVars();
		$this->_renderDefaultView('editor/body.php');
	}

	/**
	 * The header action - show the header
	 */
	public function headerAction(){
		$this->_setupModel();
		$this->_renderDefaultView('editor/header.php');
	}

	/**
	 * The footer action - show the footer
	 * @return void
	 */
	public function footerAction(){
		$this->_setupModel();
		$this->_renderDefaultView('editor/footer.php');
	}

	/**
	 * The exit doc question action - show the exit doc question
	 * @return void
	 */
	public function exitdocquestionAction(){
		$this->view = new Zend_View();
		$this->view->setScriptPath('views/scripts');
		$this->view->cmdstack = $this->getRequest()->getParam('cmdstack');
		echo $this->view->render('editor/exitDocQuestion.php');
	}

	/**
	 * Render Default View - show the default view
	 * @return void
	 */
	protected function _renderDefaultView($viewscript){
		$this->view = new Zend_View();
		$this->_setupParameter();
		$this->_setupParamString();
		$this->view->setScriptPath('views/scripts');
		echo $this->view->render($viewscript);
	}

	/**
	 * setup the parameter string
	 * @return void
	 */
	protected function _setupParamString(){
		$this->view->paramString = (!empty($this->view->tab) ? '/tab/' . $this->view->tab : '') . (!empty($this->view->modelId) ? '/modelId/' . $this->view->modelId : '');
	}

	/**
	 * setup parameter
	 * @return void
	 */
	protected function _setupParameter(){
		$this->view->tab = $this->getRequest()->getParam('tab', 0);
		$this->view->sid = $this->getRequest()->getParam('sid', '');
		$this->view->modelId = $this->getRequest()->getParam('modelId', 0);
		$this->view->model = $this->_model;
	}

	/**
	 * process POST variables
	 * @return void
	 */
	protected function _processPostVars(){
		//FIMXE: POST
		$this->_model->setFields($_POST);
	}

	/**
	 * setup the model
	 * @return void
	 */
	protected function _setupModel($forceNew = false){
		$appName = $this->getFrontController()->getParam('appName');
		$session = new we_sdk_namespace($appName);

		if($forceNew === false && isset($session->model)){
			$this->_model = $session->model;
		} else {
			try{
				$args = array($appName . "_models_Default");
				$modelId = $this->getRequest()->getParam('modelId');
				if($modelId){
					$args[] = $modelId;
				}
				$serviceObj = new we_service_Cmd();
				$this->_model = $serviceObj->createModel($args);
			} catch (we_service_Exception $e){
				we_util_Log::errorLog($e->getMessage());
				return;
			}

			unset($session->model);
			$session->model = $this->_model;
		}
	}

}
