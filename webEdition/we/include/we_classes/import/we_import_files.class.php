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
class we_import_files{
	var $parentID = 0;
	var $step = 0;
	private $type = 'FileImport';
	var $sameName = "overwrite";
	var $importMetadata = true;
	private $imgsSearchable = false;
	var $cmd = '';
	var $thumbs = '';
	var $width = '';
	var $height = '';
	var $widthSelect = 'pixel';
	var $heightSelect = 'pixel';
	var $keepRatio = 1;
	var $quality = 8;
	var $degrees = 0;
	var $categories = '';
	private $nextCmd = '';
	private $fileNameTemp = '';
	private $partNum = 0;
	private $partCount = 0;
	private $isPreset = false;

	const CHUNK_SIZE = 256;

	function __construct(){
		if(($catarray = we_base_request::_(we_base_request::STRING_LIST, 'fu_doc_categories'))){
			$cats = [];
			foreach($catarray as $cat){
				// bugfix Workarround #700
				$cats[] = (is_numeric($cat) ?
					$cat :
					path_to_id($cat, CATEGORY_TABLE, $GLOBALS['DB_WE']));
			}
			$this->categories = implode(',', $cats);
		} else {
			$this->categories = we_base_request::_(we_base_request::INTLIST, 'fu_doc_categories', $this->categories);
		}
		$this->isPreset = we_base_request::_(we_base_request::INT, 'we_cmd', false, 1) || we_base_request::_(we_base_request::RAW, 'we_cmd', false, 2);
		$this->parentID = we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1) ?: we_base_request::_(we_base_request::INT, "fu_file_parentID", $this->parentID);
		$this->nextCmd = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 2) ?: (we_base_request::_(we_base_request::STRING, 'nextCmd', '') ?: '');
		$this->sameName = we_base_request::_(we_base_request::STRING, "fu_file_sameName", $this->sameName);
		$this->importMetadata = we_base_request::_(we_base_request::INT, "fu_doc_importMetadata", $this->importMetadata);
		$this->imgsSearchable = we_base_request::_(we_base_request::INT, "fu_doc_isSearchable", $this->imgsSearchable);
		$this->step = we_base_request::_(we_base_request::INT, "step", $this->step);
		$this->cmd = we_base_request::_(we_base_request::RAW, "cmd", $this->cmd);
		$this->thumbs = we_base_request::_(we_base_request::INTLIST, 'fu_doc_thumbs', $this->thumbs);
		if(!we_fileupload::EDIT_IMAGES_CLIENTSIDE){
			$this->width = we_base_request::_(we_base_request::INT, "fu_doc_width", $this->width);
			$this->height = we_base_request::_(we_base_request::INT, "fu_doc_height", $this->height);
			$this->widthSelect = we_base_request::_(we_base_request::STRING, "fu_doc_widthSelect", $this->widthSelect);
			$this->heightSelect = we_base_request::_(we_base_request::STRING, "fu_doc_heightSelect", $this->heightSelect);
			$this->keepRatio = we_base_request::_(we_base_request::BOOL, "fu_doc_keepRatio", $this->keepRatio);
			$this->quality = we_base_request::_(we_base_request::INT, "fu_doc_quality", $this->quality);
			$this->degrees = we_base_request::_(we_base_request::INT, "fu_doc_degrees", $this->degrees);
		}
		$this->partNum = we_base_request::_(we_base_request::INT, "wePartNum", 0);
		$this->partCount = we_base_request::_(we_base_request::INT, "wePartCount", 0);
		$this->fileNameTemp = we_base_request::_(we_base_request::FILE, "weFileNameTemp", '');
	}

	function getHTML(){
		switch($this->cmd){
			case "content" :
				return $this->_getContent();
			case "buttons" :
				return $this->getButtons();
			default :
				return $this->_getFrameset();
		}
	}

	function _getContent(){
		switch(we_base_request::_(we_base_request::INT, 'step', 1)){
			default:
			case 1:
				return $this->getStep1();
			case 2:
				return $this->getStep2();
		}
	}

	function getStep1(){
		unset($_SESSION['weS']['WE_IMPORT_FILES_ERRORs']);

		$fileupload = new we_fileupload_ui_editor();

		$moreFields = we_fileupload::EDIT_IMAGES_CLIENTSIDE ? [] : [
			'imageResize' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false, 'noline' => true],
			'imageRotate' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false, 'noline' => true],
			'imageQuality' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false, 'noline' => true],
		];
		$fileupload->setFormElements(array_merge($moreFields, [
			'uploader' => ['set' => false],
			'parentId' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false, 'noline' => true],
			'sameName' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false],
			'importMeta' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false, 'noline' => true],
			'categories' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false],
			'isSearchable' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false],
			'attributes' => ['set' => true, 'multiIconBox' => true, 'rightHeadline' => true],
			'thumbnails' => ['set' => true, 'multiIconBox' => true, 'space' => we_html_multiIconBox::SPACE_MED2, 'rightHeadline' => false],
		]));

		$nc = $this->nextCmd;
		$pid = $this->parentID;
		$ips = $this->isPreset;
		$fileupload->loadImageEditPropsFromSession();
		if($ips){
			$this->nextCmd = $nc;
			$this->parentID = $pid;
		}

		$fileupload->setFieldParentID(['setField' => true,
			'preset' => ($this->parentID ?: 0),
			'setFixed' => false,
		]);

		// create Start Screen ##############################################################################
		$parts = [
			$fileupload->getFormParentID('we_form'),
			$fileupload->getFormIsSearchable(),
			$fileupload->getFormSameName(),
			$fileupload->getFormCategories()
		];

		if(we_base_permission::hasPerm("NEW_GRAFIK")){
			$parts = array_merge($parts, [
				$fileupload->getFormImportMeta()
			]);

			if(we_base_imageEdit::gd_version() > 0){
				$parts = array_merge($parts, [
					$fileupload->getFormThumbnails(),
					$fileupload->getFormImageResize(),
					$fileupload->getFormImageRotate(),
					$fileupload->getFormImageQuality()
				]);
			} else {
				$parts[] = [
					'headline' => '',
					'html' => we_html_tools::htmlAlertAttentionBox(g_l('importFiles', '[add_description_nogdlib]'), we_html_tools::TYPE_INFO, ""),
				];
			}
			$foldAt = we_fileupload::EDIT_IMAGES_CLIENTSIDE ? -1 : 3;
		} else {
			$foldAt = -1;
		}

		$content = we_html_element::htmlHiddens(['we_cmd[0]' => 'import_files',
				'nextCmd' => $this->nextCmd,
				'cmd' => 'content',
				'step' => '1',
				'type' => $this->type
			]) .
			we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
			we_html_multiIconBox::getHTML("weimportfiles", $parts, 30, "", $foldAt, g_l('importFiles', '[image_options_open]'), g_l('importFiles', '[image_options_close]'), false, g_l('importFiles', '[step1]'));

		$startsrceen = we_html_element::htmlDiv(
				["id" => "start"
				], we_html_element::htmlForm(
					["action" => WEBEDITION_DIR . "we_cmd.php",
					"name" => "we_form",
					"method" => "post"
					], $content));

		$body = we_html_element::htmlBody(["class" => "weDialogBody"
				], $startsrceen);

		return $this->_getHtmlPage($body, we_html_element::jsScript(JS_DIR . 'weCmd_apply.js'));
	}

	function getStep2(){
		$uploader = new we_fileupload_ui_importer('we_File');
		$uploader->setImageEditProps(['parentID' => $this->parentID,
			'sameName' => $this->sameName,
			'importMetadata' => $this->importMetadata,
			'isSearchable' => $this->imgsSearchable,
			'thumbnails' => $this->thumbs,
			'imageWidth' => $this->width,
			'imageHeight' => $this->height,
			'widthSelect' => $this->widthSelect,
			'heightSelect' => $this->heightSelect,
			'keepRatio' => $this->keepRatio,
			'quality' => $this->quality,
			'degrees' => $this->degrees,
			'categories' => $this->categories
		]);
		$uploader->saveImageEditPropsInSession();
		$uploader->setNextCmd($this->nextCmd);
		$body = $uploader->getHTML($this->getHiddens(true)); // TODO: set form and hiddens here

		return we_html_tools::getHtmlTop(g_l('import', '[title]'), '', '', $uploader->getCss() . $uploader->getJs() . we_html_multiIconBox::getDynJS("uploadFiles", 30), $body);
	}

	private function getButtons(){
		$bodyAttribs = ['class' => "weDialogButtonsBody"];
		$cancelButton = we_html_button::create_button(we_html_button::CANCEL, "javascript:handleEvent('cancel')", '', 0, 0, '', '', false, false);

		$prevButton = we_html_button::create_button(we_html_button::BACK, "javascript:top.handleEvent('previous');", '', 0, 0, "", "", false);
		$nextButton = we_html_button::create_button(we_html_button::NEXT, "javascript:top.handleEvent('next');", '', 0, 0, "", "", $this->step > 0, false);

		// TODO: let we_fileupload set pb
		$pb = new we_gui_progressBar(0, 200);
		$pb->addText(sprintf(g_l('importFiles', '[import_file]'), 1), we_gui_progressBar::TOP, "progress_title");
		$progressbar = '<div id="progressbar" style="margin:0 0 6px 12px;' . (($this->step == 0) ? 'display:none;' : '') . '">' . $pb->getHTML() . '</div>';

		$table = new we_html_table(['class' => 'default', "width" => "100%"], 1, 2);
		$table->setCol(0, 0, null, $progressbar);
		$table->setCol(0, 1, ["styke" => "text-align:right"], we_html_element::htmlDiv(['id' => 'normButton'], we_html_button::position_yes_no_cancel(($prevButton ? $prevButton . $nextButton : null), null, $cancelButton, 10, '', [
], 10)));

		$content = $table->getHtml();
		$body = we_html_element::htmlBody($bodyAttribs, $content);

		return $this->_getHtmlPage($body, we_gui_progressBar::getJSCode());
	}

	function getHiddens($noCmd = false){
		$moreHiddens = we_fileupload::EDIT_IMAGES_CLIENTSIDE ? [] : ['fu_doc_width' => $this->width,
			'fu_doc_height' => $this->height,
			'fu_doc_widthSelect' => $this->widthSelect,
			'fu_doc_heightSelect' => $this->heightSelect,
			'fu_doc_keepRatio' => $this->keepRatio,
			'fu_doc_degrees' => $this->degrees,
			'fu_doc_quality' => $this->quality,
		];

		return ($noCmd ? '' : we_html_element::htmlHidden('cmd', 'buttons')) . we_html_element::htmlHiddens(array_merge(['step' => 2,
				// these are used by we_fileupload to grasp these values AND by editor to have when going back one step
				'type' => $this->type,
				'fu_file_parentID' => $this->parentID,
				'fu_file_sameName' => $this->sameName,
				'fu_doc_thumbs' => $this->thumbs,
				'fu_doc_categories' => $this->categories,
				'fu_doc_isSearchable' => $this->imgsSearchable,
				'fu_doc_importMetadata' => $this->importMetadata,
					], $moreHiddens));
	}

	function _getFrameset(){
		$step = we_base_request::_(we_base_request::INT, 'step', -1);

		$body = we_html_element::htmlBody(['id' => 'weMainBody']
				, we_html_element::htmlIFrame('wizbody', WEBEDITION_DIR . "we_cmd.php?we_cmd[0]=import_files&we_cmd[1]=" . $this->parentID . "&cmd=content" . ($step > -1 ? '&step=' . $step : '') . '&we_cmd[2]=' . $this->nextCmd, 'position:absolute;top:0px;bottom:40px;left:0px;right:0px;') .
				we_html_element::htmlIFrame('wizbusy', WEBEDITION_DIR . "we_cmd.php?we_cmd[0]=import_files&cmd=buttons" . ($step > -1 ? '&step=' . $step : '') . '&we_cmd[2]=' . $this->nextCmd, 'position:absolute;bottom:0px;height:40px;left:0px;right:0px;overflow: hidden;', '', '', false)
		);

		return $this->_getHtmlPage($body, we_html_element::jsScript(JS_DIR . 'dialogs/we_dialog_base.js') . we_html_element::jsScript(JS_DIR . 'import_wizardBase.js'));
	}

	function _getHtmlPage($body, $js = ""){
		return we_html_tools::getHtmlTop(g_l('import', '[title]'), '', '', $js, $body);
	}

}