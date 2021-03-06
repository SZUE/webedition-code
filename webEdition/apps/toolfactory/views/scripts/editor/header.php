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
$translate = we_core_Local::addTranslation('apps.xml');

$htmlPage = we_ui_layout_HTMLPage::getInstance();

$propertiesTitle = $translate->_('Properties');

$we_tabs = new we_ui_controls_Tabs(
	array(
	'contentFrame' => 'parent.edbody.',
	'tabs' => array(
		array(
			'id' => 'idPropertyTab',
			'text' => $propertiesTitle,
			'bottomline' => false,
			'reload' => true,
			'active' => true,
			'title' => $propertiesTitle,
			'hidden' => false
		)
	)
	)
);

$htmlPage->addJSFile(LIB_DIR . 'we/app/js/EditorHeader.js');

$htmlPage->setBodyAttributes(array('class' => 'weEditorHeader', 'onload' => 'setFrameSize()', 'onresize' => 'setFrameSize()'));

$titlePathGroup = oldHtmlspecialchars($this->model->IsFolder ? $translate->_('Folder') : $translate->_('Entry'));
$titlePathName = oldHtmlspecialchars($this->model->Text);

$htmlPage->addHTML(
	'<div id="main">
		<div id="headrow">
			&nbsp;<strong><span id="titlePathGroup">' .
	$titlePathGroup . '</span>:&nbsp;<span id="titlePathName">' .
	$titlePathName . '</span><div id="mark" style="display: none;">*</div></strong>
		</div>
	');

$htmlPage->addElement($we_tabs);

$htmlPage->addHTML('</div>');

$js = <<<EOS

	weEventController.register("save", function(data, sender) {
		self.unmark();
	});

	weEventController.register("docChanged", function(data, sender) {
		var path = "";
		if(parent.edbody.document.we_form.ParentPath!==undefined) {
			path = parent.edbody.document.we_form.ParentPath.value + "/";
		}

		path += parent.edbody.document.we_form.Text.value;
		path = path.replace(/</g,"&lt;").replace(/>/g,"&gt;");
		self.setTitlePath("", path.replace(/\/\//,"/"));
		self.mark();
	});


EOS;

$htmlPage->addInlineJS($js);

echo $htmlPage->getHTML();
