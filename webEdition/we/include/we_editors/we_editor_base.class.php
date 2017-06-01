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
abstract class we_editor_base{
	protected $jsCmd = null;
	protected $we_doc = null;
	protected $charset = '';
	protected $title = '';

	public function __construct(we_contents_root $we_doc){
		$this->jsCmd = new we_base_jsCmd();
		$this->we_doc = $we_doc;
	}

	public function getJsCmd(){
		return $this->jsCmd;
	}

	public abstract function show();

	/**
	 *
	 * @param type $form
	 * @param type $header
	 * @param type $bodyAttr
	 * @param type $formAttr set to false if no form should be generated,else set form attributes
	 * @return type
	 */
	protected function getPage($form, $header = '', $bodyAttr = [], $formAttr = []){
		return we_html_tools::getHtmlTop($this->title, $this->charset, '', $header .
				we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
				we_editor_script::get() .
				$this->jsCmd->getCmds(), we_html_element::htmlBody(array_merge(
						[
					'class' => "weEditorBody",
					'onunload' => "doUnload()"
						], $bodyAttr), ($formAttr === false ?
					$form :
					we_html_element::htmlForm(array_merge([
						'name' => "we_form",
						'method' => "post",
						'onsubmit' => "return false;"
							], $formAttr), we_contents_base::hiddenTrans() .
						$form .
						we_html_element::htmlHidden("we_complete_request", 1)))
				)
		);
	}

}
