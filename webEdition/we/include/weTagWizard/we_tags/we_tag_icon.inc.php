<?php
/**
 * //NOTE you are inside the constructor of weTagData.class.php
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
*/
$this->NeedsEndTag = false;
//$this->Groups[] = 'input_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

if(defined('FILE_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('id', FILE_TABLE, '', true, '');
}
$this->Attributes[] = new weTagData_selectAttribute('xml', weTagData_selectAttribute::getTrueFalse(), false, '');
//$this->Attributes[] = new weTagData_textAttribute('cachelifetime', false, '');
