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
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);
$this->Module = 'voting';
$this->DefaultValue = '<we:repeat>
</we:repeat>';

$this->Attributes[] = new weTagData_textAttribute('name', true, '');
$this->Attributes[] = new weTagData_textAttribute('groupid', false, '');
$this->Attributes[] = new weTagData_textAttribute('version', false, '');
$this->Attributes[] = new weTagData_textAttribute('rows', false, '');
$this->Attributes[] = new weTagData_textAttribute('offset', false, '');
$this->Attributes[] = new weTagData_selectAttribute('desc', array(new weTagDataOption('true'),
	), false, '');
$this->Attributes[] = new weTagData_textAttribute('order', false, '');
$this->Attributes[] = new weTagData_selectAttribute('subgroup', weTagData_selectAttribute::getTrueFalse(), false, '');
