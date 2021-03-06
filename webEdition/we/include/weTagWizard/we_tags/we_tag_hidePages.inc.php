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

$this->Attributes[] = new weTagData_choiceAttribute('pages', array(new weTagDataOption('all'),
	new weTagDataOption('properties'),
	new weTagDataOption('edit'),
	new weTagDataOption('information'),
	new weTagDataOption('preview'),
	new weTagDataOption('validation'),
	new weTagDataOption('customer'),
	new weTagDataOption('versions'),
	new weTagDataOption('schedpro'),
	new weTagDataOption('variants'),
	), false, true, '');
