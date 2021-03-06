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
//$this->Groups[] = 'input_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes[] = new weTagData_selectAttribute('htmlspecialchars', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_textAttribute('prefix', false, '');
$this->Attributes[] = new weTagData_textAttribute('suffix', false, '');
$this->Attributes[] = new weTagData_textAttribute('delimiter', false, '');
