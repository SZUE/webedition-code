<?php

//NOTE you are inside the constructor of weTagData.class.php

$this->NeedsEndTag = false;
//$this->Groups[] = 'input_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$id_document = new weTagData_selectorAttribute('id', FILE_TABLE, 'text/webedition,image/*,text/css,text/js,application/*', true, '');
$id_object = (defined("OBJECT_FILES_TABLE") ? new weTagData_selectorAttribute('id', OBJECT_FILES_TABLE, 'objectFile', true, '') : null);
$triggerid = new weTagData_selectorAttribute('triggerid', FILE_TABLE, 'text/webedition', false, '');
$hidedirindex = new weTagData_selectAttribute('hidedirindex', weTagData_selectAttribute::getTrueFalse(), false, '');
$objectseourls = new weTagData_selectAttribute('objectseourls', weTagData_selectAttribute::getTrueFalse(), false, '');
$to = new weTagData_selectAttribute('to', array(new weTagDataOption('screen'),
	new weTagDataOption('request'),
	new weTagDataOption('post'),
	new weTagDataOption('get'),
	new weTagDataOption('global'),
	new weTagDataOption('session'),
	new weTagDataOption('top'),
	new weTagDataOption('self'),
	new weTagDataOption('sessionfield'),
	), false, '');
$nameto = new weTagData_textAttribute('nameto', false, '');
$this->Attributes = array();
$this->TypeAttribute = new weTagData_typeAttribute('type', array(
	new weTagDataOption('document', false, '', array($id_document, $hidedirindex, $to, $nameto), array($id_document)),
	new weTagDataOption('object', false, 'object', array($id_object, $triggerid, $hidedirindex, $objectseourls, $to, $nameto), array($id_object))), false, '');

$this->Attributes = array($id_document, $id_object, $triggerid, $to, $nameto, $hidedirindex, $objectseourls);