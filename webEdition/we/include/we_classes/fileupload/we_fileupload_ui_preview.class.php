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
class we_fileupload_ui_preview extends we_fileupload_ui_base{
	protected $formElements = array(
		'uploader' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'importMeta' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'isSearchable' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'categories' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'parentId' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'sameName' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'attributes' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'thumbnails' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'imageResize' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'imageRotate' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'imageQuality' => array('set' => false, 'multiIconBox' => true, 'rightHeadline' => true, 'noline' => true),
		'tableProperties' => array('foldAtNr' => -1, 'foldAtOpen' => '', 'foldAtClose' => '')
	);
	protected $isExternalBtnUpload = false;
	protected $parentID = array(
		'setField' => false,
		'preset' => IMAGESTARTID_DEFAULT,
		'setFixed' => false,
	);
	protected $transaction;
	protected $contentType;
	protected $extension;

	public function __construct($contentType = '', $extension = ''){
		parent::__construct('we_File');

		$this->doCommitFile = true;
		$this->contentType = $contentType;
		$this->responseClass = 'we_fileupload_resp_import';
		//$type = 'binDoc';
		$this->callback = '';
		$this->type = 'preview';
		$this->extension = $extension;
		$this->setInternalProgress(array('isInternalProgress' => true));
		$this->internalProgress['width'] = 170;
		$this->setTypeCondition('accepted', array($contentType));
		$this->setDimensions(array('width' => 200, 'dragHeight' => 116, 'alertBoxWidth' => 507));
		//$this->binDocProperties = $this->getDocProperties();
		$this->moreFieldsToAppend = array_merge($this->moreFieldsToAppend, array(
			array('fu_doc_importMetadata', 'text'),
			array('fu_file_sameName', 'text'),
			array('fu_doc_focusX', 'text'),
			array('fu_doc_focusY', 'text'),
		));
		$this->cliensideImageEditing = true;
	}

	public function getCss(){
		return we_html_element::cssLink(CSS_DIR . 'we_fileupload.css') . we_html_element::cssElement('
			div.we_file_drag{
				width: ' . $this->dimensions['dragWidth'] . 'px;
			}
			div.filedrag_content_left{
				padding-left: ' . ($this->dimensions['dragWidth'] * 0.04) . 'px;
				width: ' . ($this->dimensions['dragWidth'] * 0.56) . 'px;
			}
			div.filedrag_content_right{
				/*width: ' . ($this->dimensions['dragWidth'] * 0.4) . 'px;*/
			}
			div.filedrag_preview_left{
				padding-left: ' . ($this->dimensions['dragWidth'] * 0.04) . 'px;
				width: ' . ($this->dimensions['dragWidth'] * 0.56) . 'px;
			}');
	}

	public function getDivBtnUploadCancel($width = 170){
		return we_html_element::htmlDiv([], $this->getButtonWrapped('upload', true, $width) . $this->getButtonWrapped('cancel'), false, $width);
	}

	protected function getDivBtnInputReset($width){
		return we_html_element::htmlDiv(array('style' => 'margin-top:18px;'), $this->getButtonWrapped('reset', false, $width) . $this->getButtonWrapped('browse', false, $width));
	}

	protected function getHtmlDropZone($type = 'preview', $thumbnailSmall = ''){
		$dropText = g_l('newFile', $this->isDragAndDrop ? '[drop_text_ok]' : '[drop_text_nok]');

		$content = we_html_element::htmlDiv(array('id' => 'div_fileupload_fileDrag_state_0', 'class' => 'we_file_drag_content', 'style' => (!$this->isDragAndDrop ? 'border-color:white;' : '')/* , 'ondragenter' => "alert('wrong div')" */), we_html_element::htmlDiv(array('id' => 'div_filedrag_content_left', 'class' => 'filedrag_content_left', 'style' => (!$this->isDragAndDrop ? 'font-size:14px' : '')), $dropText) .
				we_html_element::htmlDiv(array('id' => 'div_filedrag_content_right', 'class' => 'filedrag_content_right'), ($thumbnailSmall ? : we_html_element::jsElement('document.write(WE().util.getTreeIcon("' . $this->contentType . '"));')))
			) .
			we_html_element::htmlDiv(array('id' => 'div_fileupload_fileDrag_state_1', 'class' => 'we_file_drag_preview', 'style' => (!$this->isDragAndDrop ? 'border-color:rgb(243, 247, 255);' : 'display:none;')), we_html_element::htmlDiv(array('id' => 'div_upload_fileDrag_innerLeft', 'class' => 'filedrag_preview_left'), we_html_element::htmlDiv(array('id' => 'span_fileDrag_inner_filename')) .
					we_html_element::htmlDiv(array('id' => 'span_fileDrag_inner_size', 'style' => 'padding-top: 4px;')) .
					we_html_element::htmlDiv(array('id' => 'span_fileDrag_inner_type')) .
					we_html_element::htmlDiv(array('id' => 'span_fileDrag_inner_edit', 'style' => 'display:none;padding-top: 4px;'))
				) .
				we_html_element::htmlDiv(array('id' => 'div_upload_fileDrag_innerRight', 'class' => 'filedrag_preview_right'), '')
			) .
			we_html_element::htmlDiv(array('id' => 'div_fileupload_fileDrag_mask', 'class' => 'we_file_drag_mask'), we_html_element::htmlDiv(array('class' => 'we_file_drag_maskSpinner'), '<i class="fa fa-2x fa-spinner fa-pulse"></i></span>') .
				we_html_element::htmlDiv(array('id' => 'image_edit_mask_text', 'class' => 'we_file_drag_maskBusyText'))
		);

		return self::getHtmlLoup() . ($this->isDragAndDrop ? we_html_element::htmlDiv(array('id' => 'div_we_File_fileDrag', 'class' => 'we_file_drag'), $content) : $content);
	}

	protected function getHiddens(){
		return $hiddens = parent::getHiddens() . we_html_element::htmlHiddens(array(
				'we_doc_ct' => $this->contentType,
				'we_doc_ext' => $this->extension,
				'fu_doc_focusX' => 0,
				'fu_doc_focusY' => 0,
		));
	}

	public static function getFormImageEditClientside($multimport = false, $disabled = false){
		$editCheckbox = we_html_forms::checkboxWithHidden(false, 'fuOpts_doEdit', $multimport ? g_l('importFiles', '[edit_imgsBeforeUpload]') : g_l('importFiles', '[edit_imgBeforeUpload]'), false, 'defaultfont', 'document.getElementById(\'editImage\').style.display=(this.checked ? \'block\' : \'none\');if(!this.checked){' . $reeditCmd . '}');
		$attribs = array_merge($disabled ? array('disabled' => 'disabled') : array(), array('name' => 'fuOpts_scale', 'type' => 'text', 'class' => 'wetextinput optsScaleInput' . ($multimport ? ' multiimport' : ''), 'autocomplete' =>'off'));
		$scaleValue = we_html_element::htmlInput($attribs);
		$scalePropositions = we_html_tools::htmlSelect('fuOpts_scaleProps',
				array('' => '', 320 => 320, 640 => 640, 1280 => 1280, 1440 => 1440, 1600 => 1600, 1920 => 1920, 2560 => 2560),
					1, 0, false, [], '', '', 'weSelect optsScalePropositions' . ($multimport ? ' multiimport' : ''));
		$scaleWhatSelect = we_html_tools::htmlSelect('fuOpts_scaleWhat', array(
				'pixel_l' => g_l('importFiles', '[edit_pixel_longest]'),
				'pixel_w' => g_l('importFiles', '[edit_pixel_width]'),
				'pixel_h' => g_l('importFiles', '[edit_pixel_height]')
				), 1, 0, false, ($disabled ? array('disabled' => 'disabled') : array()), '', $multimport ? 0 : 155, 'weSelect optsUnitSelect' . ($multimport ? ' multiimport' : ''));
		$scaleHelp = we_html_element::htmlDiv(array('data-index' => '0', 'class' => 'optsRowScaleHelp'), '<span class="fa-stack alertIcon" style="color:black;"><i class="fa fa-question-circle" ></i></span>' . we_html_element::htmlDiv(array('class' => 'optsRowScaleHelpText')));
		$rotateSelect = we_html_tools::htmlSelect('fuOpts_rotate', array(
				0 => g_l('weClass', '[rotate0]'),
				180 => g_l('weClass', '[rotate180]'),
				270 => g_l('weClass', '[rotate90l]'),
				90 => g_l('weClass', '[rotate90r]'),
				), 1, 0, false, ($disabled ? array('disabled' => 'disabled') : array()), '28', 0, 'weSelect optsRotateSelect' . ($multimport ? ' multiimport' : ''));
		$quality = we_html_element::htmlInput(array('type' => 'range', 'value' => 100, 'min' => 10, 'max' => 100, 'step' => 5, 'name' => 'fuOpts_quality', 'class' => 'optsQuality'));
		$btnProcess = we_html_button::create_button(we_html_button::MAKE_PREVIEW, "javascript:", true, 0, 0, '', '', true, false, '_weFileupload', false, $title = 'Bearbeitungsvorschau erstellen', 'weFileupload_btnImgEditRefresh');

		return we_html_element::htmlDiv([], $editCheckbox) .
			we_html_element::htmlDiv(array('class' => 'imgEditOpts', 'id' => 'editImage'),
				we_html_element::htmlDiv(array('class' => 'scaleDiv'),
					we_html_element::htmlDiv(array('class' => 'labelContainer'), g_l('importFiles', '[scale_label]') .':') .
					we_html_element::htmlDiv(array('class' => 'inputContainer'), $scaleWhatSelect . ' ' . $scalePropositions .  $scaleValue) . $scaleHelp
				) .
				we_html_element::htmlDiv(array('class' => 'rotationDiv'),
					we_html_element::htmlDiv(array('class' => 'labelContainer'), g_l('importFiles', '[rotate_label]') .':') .
					we_html_element::htmlDiv(array('class' => 'inputContainer'), $rotateSelect)
				) .
				we_html_element::htmlDiv(array('class' => 'qualityDiv'),
					we_html_element::htmlDiv(array('class' => 'labelContainer'), g_l('weClass', '[quality]') . ':') .
					we_html_element::htmlDiv(array('class' => 'inputContainer'), $quality) .
					we_html_element::htmlDiv(array('id' => 'qualityValue', 'class' => 'qualityValueContainer'), 100) .
					(!$multimport ? we_html_element::htmlDiv(array('class' => 'btnContainer'), $btnProcess) : '')
				) .
				($multimport ? we_html_element::htmlDiv(array('class' => 'btnDiv'),
					we_html_element::htmlDiv(array('class' => 'btnContainer' . ($multimport ? ' multiimport' : '')), $btnProcess)
				) : '')
		);
	}

	public function getFormImportMeta(){
		$name = 'importMeta';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set']){
			return false;
		}

		$html = we_html_forms::checkboxWithHidden(($this->imageEditProps['importMetadata'] ? true : false), 'fu_doc_importMetadata', g_l('importFiles', '[import_metadata]'), false, 'defaultfont', '');
		$headline = g_l('importFiles', '[metadata]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	function getFormCategories(){
		$name = 'categories';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$width_size = 378;
		$addbut = we_html_button::create_button(we_html_button::ADD, "javascript:we_cmd('we_selector_category',-1,'" . CATEGORY_TABLE . "','','','fillIDs();opener.addCat(top.allPaths);opener.selectCategories();')");
		$del_but = addslashes(we_html_button::create_button(we_html_button::TRASH, 'javascript:#####placeHolder#####;if(typeof \'selectCategories\' !== \'undefined\'){selectCategories()};'));
		$js = we_html_element::jsScript(JS_DIR . 'utils/multi_edit.js');
		$variant_js = '
var categories_edit = new multi_edit("categoriesDiv",document.forms[0],0,"' . $del_but . '",' . ($width_size - 10) . ',false);
categories_edit.addVariant();';

		$cats = makeArrayFromCSV($this->imageEditProps['categories']);
		if(is_array($cats)){
			foreach($cats as $cat){
				$variant_js .='
categories_edit.addItem();
categories_edit.setItem(0,(categories_edit.itemCount-1),"' . id_to_path($cat, CATEGORY_TABLE) . '");';
			}
		}

		$variant_js .= 'categories_edit.showVariant(0);';
		$js .= we_html_element::jsElement($variant_js);

		$table = new we_html_table(
			array(
			'id' => 'CategoriesBlock',
			'style' => 'display: block;',
			'class' => 'default withSpace'
			), 2, 1);

		$table->setColContent(0, 0, we_html_element::htmlDiv(
				array(
					'id' => 'categoriesDiv',
					'class' => 'blockWrapper',
					'style' => 'width: ' . ($width_size) . 'px; height: 60px; border: #AAAAAA solid 1px;'
		)));
		$table->setCol(1, 0, array('colspan' => 2, 'style' => 'text-align:right'
			), we_html_button::create_button(we_html_button::DELETE_ALL, "javascript:removeAllCats()") . $addbut
		);

		$js .= we_html_element::jsElement('
function removeAllCats(){
	if(categories_edit.itemCount>0){
		while(categories_edit.itemCount>0){
			categories_edit.delItem(categories_edit.itemCount);
		}
		categories_edit.showVariant(0);
		selectCategories();
	}
}

function addCat(paths){
	var path = paths.split(",");
	for (var i = 0; i < path.length; i++) {
		if(path[i]!="") {
			categories_edit.addItem();
			categories_edit.setItem(0,(categories_edit.itemCount-1),path[i]);
		}
	}
	categories_edit.showVariant(0);
	//selectCategories();
}

function selectCategories() {
	var cats = [];
	for(var i=0;i<categories_edit.itemCount;i++){
		cats.push(categories_edit.form.elements[categories_edit.name+"_variant0_"+categories_edit.name+"_item"+i].value);
	}
	categories_edit.form.fu_doc_categories.value=cats.join(",");
}');

		$html = $table->getHtml() . $js . we_html_element::htmlHidden('fu_doc_categories', '');
		$headline = g_l('global', '[categorys]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormIsSearchable($isMultiIconBox = true){
		$name = 'isSearchable';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set']){
			return;
		}

		$html = we_html_element::htmlDiv([], we_html_forms::checkboxWithHidden(($this->imageEditProps['isSearchable'] ? true : false), 'fu_doc_isSearchable', g_l('importFiles', '[imgsSearchable_label]'), false, 'defaultfont', ''));
		$headline = g_l('importFiles', '[imgsSearchable]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormSameName(){
		$name = 'sameName';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set']){
			return;
		}

		$html = we_html_element::htmlDiv(array('style' => 'margin:10px 0 0 0;'),
				//we_html_tools::htmlAlertAttentionBox(g_l('importFiles', '[sameName_expl]'), we_html_tools::TYPE_INFO, 380) .
				we_html_element::htmlDiv(array('style' => 'margin-top:10px'), //g_l('newFile', '[caseFileExists]') . '<br/>' .
					we_html_forms::radiobutton('overwrite', false, "sameName", g_l('importFiles', '[sameName_overwrite]'), false, "defaultfont", 'document.we_form.fu_file_sameName.value=this.value;') .
					we_html_forms::radiobutton('rename', true, "sameName", g_l('importFiles', '[sameName_rename]'), false, "defaultfont", 'document.we_form.fu_file_sameName.value=this.value;') .
					we_html_forms::radiobutton('nothing', false, "sameName", g_l('importFiles', '[sameName_nothing]'), false, "defaultfont", 'document.we_form.fu_file_sameName.value=this.value;')
				) .
				we_html_tools::hidden('fu_file_sameName', 'rename')
		);

		$headline = g_l('importFiles', '[sameName_headline]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormParentID($formName = 'we_form'){// TODO: set formName as class prop
		$name = 'parentId';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set']){
			return;
		}

		$parentID = $this->parentID['preset'] ? (is_numeric($this->parentID['preset']) ? $this->parentID['preset'] : path_to_id($this->parentID['preset'])) : ($this->imageEditProps['parentID'] ? : (IMAGESTARTID_DEFAULT ? : 0));
		$DB_WE = new DB_WE();
		$parentID = !$parentID ? 0 : (!f('SELECT 1 FROM ' . FILE_TABLE . ' WHERE ID=' . intval($parentID) . ' AND IsFolder=1', '', $DB_WE) ? 0 : $parentID);

		if(($ws = get_ws(FILE_TABLE, true))){
			if(!(we_users_util::in_workspace($parentID, $ws, FILE_TABLE))){
				$parentID = intval(reset($ws));

				if($this->parentID['setFixed']){
					t_e('wrong uploader configuration: fixed parentID not in user\'s ws', $ws, $this->parentID['preset']);
				}
			}
		}

		if(!$this->parentID['setFixed']){
			$yuiSuggest = &weSuggest::getInstance();
			$cmd1 = "document." . $formName . ".fu_file_parentID.value";
			$wecmdenc2 = we_base_request::encCmd("document." . $formName . ".parentPath.value");
			$wecmdenc3 = ''; //we_base_request::encCmd();
			$but = we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_directory',$parentID,'" . FILE_TABLE . "','" . we_base_request::encCmd($cmd1) . "','" . $wecmdenc2 . "','" . $wecmdenc3 . "','',0,'" . we_base_ContentTypes::FOLDER . "'," . (permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . ");");
			/*
			  $but = we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd({
			  'we_cmd[0]': 'we_selector_directory',
			  'we_cmd[1]': " . $parentID . ",
			  'we_cmd[2]': '" . FILE_TABLE . "',
			  'we_cmd[3]': '" . we_base_request::encCmd($cmd1) . "',
			  'we_cmd[4]': '" . $wecmdenc2 . "',
			  'we_cmd[5]': '" . $wecmdenc3 . "',
			  'we_cmd[6]': '',
			  'we_cmd[7]': 0,
			  'we_cmd[8]': '" . we_base_ContentTypes::FOLDER . "',
			  'we_cmd[9]': " . (permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . "
			  });");
			 *
			 */
			$yuiSuggest->setAcId("fu_file_parentID");
			$yuiSuggest->setContentType(we_base_ContentTypes::FOLDER);
			$yuiSuggest->setInput("parentPath", $parentID ? id_to_path($parentID, FILE_TABLE) : '/', '', false);
			$yuiSuggest->setMaxResults(10);
			$yuiSuggest->setMayBeEmpty(true);
			$yuiSuggest->setResult("fu_file_parentID", $parentID);
			$yuiSuggest->setSelector(weSuggest::DirSelector);
			$yuiSuggest->setWidth(326);
			$yuiSuggest->setSelectButton($but);

			$html = $yuiSuggest->getHTML();
		} else {
			$parentPath = id_to_path($parentID);
			$html = we_html_element::htmlInput(array('value' => $parentPath, 'disabled' => 'disabled')) .
				we_html_button::create_button(we_html_button::SELECT, '', '', '', '', '', '', true) .
				we_html_element::htmlHiddens(array(
					'fu_file_parentID' => $parentID,
			));
		}

		$headline = g_l('importFiles', '[destination_dir]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormThumbnails($thumbs = ''){
		$name = 'thumbnails';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$thumbnails = new we_html_select(array(
			'multiple' => 'multiple',
			'name' => 'thumbnails_tmp',
			'id' => 'thumbnails_tmp',
			'class' => 'defaultfont',
			'size' => 6,
			'style' => 'width: 378px;',
			'onchange' => "this.form.fu_doc_thumbs.value='';for(var i=0;i<this.options.length;i++){if(this.options[i].selected){this.form.fu_doc_thumbs.value +=(this.options[i].value + ',');}};this.form.fu_doc_thumbs.value=this.form.fu_doc_thumbs.value.replace(/^(.+),$/,'$1');"
		));
		$DB_WE = new DB_WE();
		$DB_WE->query('SELECT ID,Name,description FROM ' . THUMBNAILS_TABLE . ' ORDER BY Name');

		$thumbsArr = explode(',', trim($this->imageEditProps['thumbnails'], ' ,'));
		while($DB_WE->next_record()){
			$attribs = array(
				'title' => $DB_WE->f('description'),
			);
			$attribs = in_array($DB_WE->f('ID'), $thumbsArr) ? array_merge($attribs, array('selected' => 'selected')) : $attribs;
			$thumbnails->addOption($DB_WE->f('ID'), $DB_WE->f('Name'), $attribs);
		}

		$html = g_l('importFiles', '[thumbnails]') . "<br/><br/>" . $thumbnails->getHtml() . we_html_element::htmlHidden('fu_doc_thumbs', $this->imageEditProps['thumbnails']);
		$headline = g_l('thumbnails', '[create_thumbnails]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormImageAttributes(){
		$name = 'attributes';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$html = we_html_element::htmlDiv([], we_html_element::htmlLabel([], 'Alternativ Text') . '<br>' . we_html_tools::htmlTextInput('fu_doc_alt', 24, '', '', '', 'text', 378)) .
			we_html_element::htmlDiv([], we_html_element::htmlLabel([], 'Titel') . '<div>' . we_html_tools::htmlTextInput('fu_doc_title', 24, '', '', '', 'text', 378) . '</div>');
		$headline = 'Attribute';

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormImageResize(){
		$name = 'imageResize';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$widthInput = we_html_tools::htmlTextInput("fu_doc_width", 10, intval($this->imageEditProps['imageWidth']), '', '', "text", 60);
		$heightInput = we_html_tools::htmlTextInput("fu_doc_height", 10, intval($this->imageEditProps['imageHeight']), '', '', "text", 60);
		$widthSelect = '<select class="weSelect" name="fu_doc_widthSelect"><option value="pixel"' . ($this->imageEditProps['widthSelect'] === 'pixel' ? ' selected="selected"' : '') . '>' . g_l('weClass', '[pixel]') . '</option><option value="percent"' . ($this->imageEditProps['widthSelect'] !== 'pixel' ? ' selected="selected"' : '') . '>' . g_l('weClass', '[percent]') . '</option></select>';
		$heightSelect = '<select class="weSelect" name="fu_doc_heightSelect"><option value="pixel"' . ($this->imageEditProps['heightSelect'] === 'pixel' ? ' selected="selected"' : '') . '>' . g_l('weClass', '[pixel]') . '</option><option value="percent"' . ($this->imageEditProps['heightSelect'] !== 'pixel' ? ' selected="selected"' : '') . '>' . g_l('weClass', '[percent]') . '</option></select>';
		$ratio_checkbox = we_html_forms::checkboxWithHidden(($this->imageEditProps['keepRatio'] ? true : false), 'fu_doc_keepRatio', g_l('thumbnails', '[ratio]'), false, 'defaultfont', '');

		$html = '<table>
			<tr>
			<td class="defaultfont">' . g_l('weClass', '[width]') . ':</td>
			<td>' . $widthInput . '</td>
			<td>' . $widthSelect . '</td>
			</tr>
			<tr>
			<td class="defaultfont">' . g_l('weClass', '[height]') . ':</td>
			<td>' . $heightInput . '</td>
			<td>' . $heightSelect . '</td>
			</tr>
			<tr>
			<td colspan="3">' . $ratio_checkbox . '</td>
			</tr>
			</table>';

		$headline = g_l('weClass', '[resize]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormImageRotate(){
		$name = 'imageRotate';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$degrees = intval($this->imageEditProps['degrees']);
		$radio0 = we_html_forms::radiobutton(0, $degrees === 0, "fu_doc_degrees", g_l('weClass', '[rotate0]'));
		$radio180 = we_html_forms::radiobutton(180, $degrees === 180, "fu_doc_degrees", g_l('weClass', '[rotate180]'));
		$radio90l = we_html_forms::radiobutton(90, $degrees === 90, "fu_doc_degrees", g_l('weClass', '[rotate90l]'));
		$radio90r = we_html_forms::radiobutton(270, $degrees === 270, "fu_doc_degrees", g_l('weClass', '[rotate90r]'));

		$html = $radio0 . $radio180 . $radio90l . $radio90r;
		$headline = g_l('weClass', '[rotate]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public function getFormImageQuality(){ // obsolete
		$name = 'imageQuality';
		if(!isset($this->formElements[$name]) || !$this->formElements[$name]['set'] || !permissionhandler::hasPerm("NEW_GRAFIK")){
			return;
		}

		$html = we_base_imageEdit::qualitySelect("fu_doc_quality", $this->imageEditProps['quality']);
		$headline = g_l('weClass', '[quality]');

		return $this->formElements[$name]['multiIconBox'] ? $this->makeMultiIconRow($name, $headline, $html) : $html;
	}

	public static function getHtmlLoup(){
		$divLoupe = we_html_element::htmlDiv(array('id' => 'we_fileUpload_loupe', 'class' => 'editorLoupe'), we_html_element::htmlDiv(array('id' => 'we_fileUpload_loupeInner', 'class' => 'editorLoupeInner')) .
				we_html_element::htmlDiv(array('id' => 'we_fileUpload_loupeInfo', 'class' => 'editorLoupeInfo')) .
				we_html_element::htmlDiv(array('id' => 'we_fileUpload_focusPoint', 'class' => 'editorFocusPoint'))
		);
		$divLoupeFallback = we_html_element::htmlDiv(array('id' => 'we_fileUpload_loupeFallback', 'class' => 'editorLoupeFallback'));//editorLoupeFallback
		$divLoupeCrosshairH = we_html_element::htmlDiv(array('class' => 'editorCrosshairH'));
		$divLoupeCrosshairV = we_html_element::htmlDiv(array('class' => 'editorCrosshairV'));
		$divFixedFocusPoint = we_html_element::htmlDiv(array('id' => 'editorFocuspointFixed', 'class' => 'editorFocusPoint focusPointOnSet'));
		$divLoupeSpinner = we_html_element::htmlDiv(array('id' => 'we_fileUpload_spinner', 'class' => 'editorLoupeSpinner'), we_html_element::htmlSpan([], '<i class="fa fa-2x fa-spinner fa-pulse"></i>'));

		return $divLoupe . $divLoupeFallback . $divLoupeCrosshairH . $divLoupeCrosshairV . $divFixedFocusPoint . $divLoupeSpinner . $divLoupeMessage;
	}

	protected function makeMultiIconRow($formname, $headline, $html){
		$row = array(
			'headline' => $headline,
			'html' => $html,
			'class' => 'weFileUploadEditorElem' . (!empty($this->formElements[$formname]['class']) ? ' ' . $this->formElements[$formname]['class'] : ''),
			//'noline' => ,//$this->formElements[$formname]['noline'],
			'forceRightHeadline' => $this->formElements[$formname]['rightHeadline'],
			'space' => !empty($this->formElements[$formname]['space']) ? $this->formElements[$formname]['space'] : 0
		);

		return !empty($this->formElements[$formname]['noline']) ? array_merge($row, array('noline' => true)) : $row;
	}

	public function getJsBtnCmd($btn = 'upload'){
		$call = 'window.we_FileUpload.' . ($btn === 'upload' ? 'startUpload()' : 'cancelUpload()');

		return 'if(window.we_FileUpload === undefined){alert("what\'s wrong?");}else{' . $call . ';}';
	}

	public static function getJsOnLeave($callback, $type = 'switch_tab'){
		if($type === 'switch_tab'){
			$parentObj = 'WE().layout.weEditorFrameController';
			$frame = 'WE().layout.weEditorFrameController.getVisibleEditorFrame()';
		} else {
			$parentObj = 'top._EditorFrame';
			$frame = '_EditorFrame.getContentEditor()';
		}

		return "var fileupload; if(" . $parentObj . " !== undefined && (fileUpload = " . $frame . ".we_FileUpload) !== undefined && fileUpload.getType() === 'binDoc'){fileUpload.doUploadIfReady(function(){" . $callback . "})}else{" . $callback . "}";
	}

	public function setIsExternalBtnUpload($isExternal){
		$this->isExternalBtnUpload = $isExternal;
	}

	public function setFieldParentID($parentID = []){
		$this->parentID = array_merge($this->parentID, $parentID);
	}

	public function setFormElements($formElements = []){
		$this->formElements = array_merge($this->formElements, $formElements);
	}

}
