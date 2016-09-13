<?php
//NOTE you are inside the constructor of weTagData.class.php

$this->NeedsEndTag = true;
$this->Groups[] = 'if_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes = [
	new weTagData_multiSelectorAttribute('id', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, 'ID', false, ''),
	new weTagData_selectAttribute('doc', [new weTagDataOption('top'),
		new weTagDataOption('self'),
		new weTagDataOption('listview'),
		], false, ''),
];
