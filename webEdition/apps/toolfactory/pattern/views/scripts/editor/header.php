

$translate = we_core_Local::addTranslation('apps.xml');

$htmlPage = we_ui_layout_HTMLPage::getInstance();

$activTab = we_base_request::_(we_base_request::STRING, 'activTab', 'idPropertyTab');

$propertiesTitle = $translate->_('Properties');
$newTabTitle = $translate->_('New Tab');

$we_tabs = new we_ui_controls_Tabs(
array(
'contentFrame'=>'parent.edbody.',
'tabs'=>array(
array(
'id'=>'idPropertyTab',
'text'=>$propertiesTitle,
'bottomline' => false,
'reload'=>true,
'active'=>($activTab==='idPropertyTab') ? true : false,
'title'=>$propertiesTitle,
'hidden'=>false
),
array(
'id'=>'idNewTab',
'text'=>$newTabTitle,
'bottomline' => false,
'reload'=>true,
'active'=>($activTab==='idNewTab') ? true : false,
'title'=>$newTabTitle,
'hidden'=>false
)
)
)
);

$htmlPage->addJSFile(LIB_DIR.'we/app/js/EditorHeader.js');

$htmlPage->setBodyAttributes(array('class'=>'weEditorHeader', 'onload'=>'setFrameSize()', 'onresize'=>'setFrameSize()'));

$titlePathGroup = oldHtmlspecialchars($this->model->IsFolder ? $translate->_('Folder') : $translate->_('Entry'));
$titlePathName = oldHtmlspecialchars($this->model->Path);

$htmlPage->addHTML(
'<div id="main">
	<div id="headrow">
		&nbsp;<strong><span id="titlePathGroup">'.
				$titlePathGroup . '</span>:&nbsp;<span id="titlePathName">'.
				$titlePathName . '</span><div id="mark" style="display: none;">*</div></strong>
	</div>
	');

	$htmlPage->addElement($we_tabs);

	$htmlPage->addHTML('</div>');

$js = <<<EOS

	weEventController.register("save", function(data, sender) {
	self.setTitlePath("", data.model.Path);
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
