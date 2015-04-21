<?php

/**
 * webEdition CMS
 *
 * $Rev: 9679 $
 * $Author: mokraemer $
 * $Date: 2015-04-07 21:46:58 +0200 (Tue, 07 Apr 2015) $
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
class we_dialog_gallery extends we_dialog_base{

	var $dialogWidth = 370;
	var $JsOnly = true;
	var $changeableArgs = array('collid',
		'tmpl',
		'templateIDs'
	);

	function __construct($noInternals = false){
		parent::__construct();
		$this->dialogTitle = 'Gallerie einfügen';// FIXME: G_L();
		$this->noInternals = $noInternals;
		$this->defaultInit();
	}

	function defaultInit(){
		$this->args['collid'] = 0;
		$this->args['tmpl'] = 0;
		$this->args['templateIDs'] = '';
	}

	public static function getTinyMceJS(){
		return parent::getTinyMceJS() . 
			we_html_element::jsElement('
var size = {
	"docSelect": {
	"width":' . we_selector_file::WINDOW_DOCSELECTOR_WIDTH . ',
					"height":' . we_selector_file::WINDOW_DOCSELECTOR_HEIGHT . '
	}
};'
			) . we_html_element::jsScript(WE_JS_TINYMCE_DIR . 'plugins/wegallery/js/gallery_init.js') . 
				weSuggest::getYuiFiles();
	}

	function getOkJs(){
		return '
WegalleryDialog.insert();
top.close();
';
	}

	function getDialogContentHTML(){
		$textname = 'we_targetname';
		$idname = 'we_dialog_args[collid]';
		$yuiSuggest = & weSuggest::getInstance();
		$yuiSuggest->setAcId('ID');
		$yuiSuggest->setContentType(we_base_ContentTypes::COLLECTION);
		$yuiSuggest->setInput($textname, isset($this->args['collid']) && $this->args['collid'] ? id_to_path($this->args['collid'], VFILE_TABLE) : '');
		$yuiSuggest->setMaxResults(4);
		$yuiSuggest->setMayBeEmpty(true);
		$yuiSuggest->setResult($idname, isset($this->args['collid']) ? $this->args['collid'] : 0);
		$yuiSuggest->setSelector(weSuggest::DocSelector);
		$yuiSuggest->setTable(VFILE_TABLE);

		$yuiSuggest->setCheckFieldValue(false);
		$yuiSuggest->setNoAutoInit(true);

		$yuiSuggest->setWidth(234);
		$yuiSuggest->setContainerWidth(300);
		$wecmdenc1 = we_base_request::encCmd('top.document.we_form.elements["' . $idname . '"].value');
		$wecmdenc2 = we_base_request::encCmd('top.document.we_form.elements["' . $textname . '"].value');
		$yuiSuggest->setSelectButton(we_html_button::create_button("select", "javascript:we_cmd('openDocselector',document.we_form.elements['" . $idname . "'].value,'" . VFILE_TABLE . "','" . $wecmdenc1 . "','" . $wecmdenc2 . "','','',0)"), 4);
		$yuiSuggest->setOpenButton(we_html_button::create_button("image:edit_edit", "javascript:if(document.we_form.elements['" . $idname . "'].value){opener.top.doClickDirect(document.we_form.elements['" . $idname . "'].value,'" . we_base_ContentTypes::COLLECTION . "','" . VFILE_TABLE . "'); return false}"));
		$yuiSuggest->setAdditionalButton(we_html_button::create_button("image:btn_add_collection", "javascript:top.we_cmd('edit_new_collection','" . $wecmdenc1 . "','" . $wecmdenc2 . "',-1,'" . stripTblPrefix(FILE_TABLE) . "', 'wegallery');", true, 0, 0, "", "", false, false), 4);

		$btnTrash = we_html_button::create_button('image:btn_function_trash', "javascript:document.we_form.elements['" . $idname . "'].value=0;document.we_form.elements['" . $textname . "'].value=''");

		$collid = we_html_tools::htmlFormElementTable($yuiSuggest->getHTML(), 'Sammlung');
		$tempArr = id_to_path(isset($this->args['templateIDs']) ? $this->args['templateIDs'] : '', TEMPLATES_TABLE, null, false, true);
		$templatesArr = array('----');
		foreach($tempArr as $k => $v){
			$templatesArr[$k] = $v;
		}
		$input = we_html_tools::htmlSelect('we_dialog_args[tmpl]', $templatesArr, 1, (isset($this->args['tmpl']) ? id_to_path($this->args['tmpl'], TEMPLATES_TABLE) : 0), false, array(), '', 430);
		$tmpl = we_html_tools::htmlFormElementTable($input, 'Template');
		
		$btnTrash = we_html_button::create_button('image:btn_function_trash', "javascript:document.we_form.elements['" . $idname . "'].value=0;document.we_form.elements['" . $textname . "'].value=''");
		/*
		$trash = '<table cellpadding="0" style="border-spacing: 0px;border-style:none">
			<tbody>
			<tr><td class="defaultfont" align="left" colspan="1"></td></tr>
			<tr style="height:1px"><td colspan="1">&nbsp;</td></tr>
			<tr style="height:1px"><td colspan="1"></td></tr>
			<tr><td style="padding-left:12px;">' . $btnTrash . '
			</td></tr>
			</tbody>
		</table>';
		 * 
		 */

		$table = '<table border="0" cellpadding="0" cellspacing="0">
<tr><td>' . $collid . '</td><td>' . $trash . '</td></tr>
<tr><td>' . we_html_tools::getPixel(225, 10) . '</td></tr>
<tr><td>' . $tmpl . '</td></tr>
	
<tr><td>' . we_html_tools::getPixel(225, 24) . '</td></tr>
<tr><td>' . $btnTrash . ' Gallerie entfernen</td></tr>
</table>';

		return $table;
	}

	function getDialogButtons(){
		$buttons = array();
		$buttons[] = parent::getDialogButtons();

		return we_html_button::create_button_table($buttons);
	}

}