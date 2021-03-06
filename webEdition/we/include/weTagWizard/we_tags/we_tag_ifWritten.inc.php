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
$this->Groups[] = 'if_tags';
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes = array(
	new weTagData_selectAttribute('type', array(
		new weTagDataOption('document'),
		new weTagDataOption('object'),
		new weTagDataOption('customer'),
		), false, ''),
	new weTagData_textAttribute('formname', false, '')
);
