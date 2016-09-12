<?php
//NOTE you are inside the constructor of weTagData.class.php

$this->NeedsEndTag = true;
$this->Groups[] = 'if_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes = [new weTagData_selectAttribute('type', [new weTagDataOption('document'),
		new weTagDataOption('object'),
		new weTagDataOption('customer'),
		], false, ''),
	new weTagData_textAttribute('formname', false, '')
];
