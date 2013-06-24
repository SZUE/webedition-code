<?php

//NOTE you are inside the constructor of weTagData.class.php

$this->NeedsEndTag = false;
$this->Groups[] = 'input_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes[] = new weTagData_textAttribute('name', true, '');
$this->Attributes[] = new weTagData_textAttribute('size', false, '');
$this->Attributes[] = new weTagData_selectAttribute('type', array(new weTagDataOption('all'),
	new weTagDataOption('int'),
	new weTagDataOption('ext'),
	), false, '');
$this->Attributes[] = new weTagData_selectAttribute('include', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('file', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('directory', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('reload', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('hidedirindex', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_textAttribute('user', false, 'users');
$this->Attributes[] = new weTagData_textAttribute('rootdir', false, '');
$this->Attributes[] = new weTagData_selectAttribute('to', array(new weTagDataOption('screen'),
	new weTagDataOption('request'),
	new weTagDataOption('post'),
	new weTagDataOption('get'),
	new weTagDataOption('global'),
	new weTagDataOption('session'),
	new weTagDataOption('top'),
	new weTagDataOption('self'),
	new weTagDataOption('sessionfield'),
	), false, '');
$this->Attributes[] = new weTagData_textAttribute('nameto', false, '');