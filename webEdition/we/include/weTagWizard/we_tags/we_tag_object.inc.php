<?php
/**
 * //NOTE you are inside the constructor of weTagData.class.php
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
*/
$this->NeedsEndTag = true;
$this->Groups[] = 'input_tags';
$this->Module = 'object';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes[] = new weTagData_textAttribute('name', false, '');
$this->Attributes[] = new weTagData_textAttribute('text', false, '');
if(defined('OBJECT_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('classid', OBJECT_TABLE, 'object', false, '');
}
if(defined('OBJECT_FILES_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('id', OBJECT_FILES_TABLE, 'objectFile', false, '');
}
$this->Attributes[] = new weTagData_textAttribute('size', false, '');
if(defined('FILE_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('triggerid', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, false, '');
}
$this->Attributes[] = new weTagData_selectAttribute('hidedirindex', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('objectseourls', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('searchable', weTagData_selectAttribute::getTrueFalse(), false, '');
