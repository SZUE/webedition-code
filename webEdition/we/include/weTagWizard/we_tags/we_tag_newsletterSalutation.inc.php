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
//$this->Groups[] = 'if_tags';
$this->Module = 'newsletter';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$this->Attributes[] = new weTagData_selectAttribute('type', array(new weTagDataOption('email'),
	new weTagDataOption('salutation', false, 'newsletter'), new weTagDataOption('title', false, 'newsletter'), new weTagDataOption('firstname', false, 'newsletter'), new weTagDataOption('lastname', false, 'newsletter')), false, '');
