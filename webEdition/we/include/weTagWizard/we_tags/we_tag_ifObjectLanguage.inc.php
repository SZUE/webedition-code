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
$this->Module = 'object';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);
$locales = array();
foreach($GLOBALS["weFrontendLanguages"] as $lv){
	$locales[] = new weTagDataOption($lv);
	;
}
$this->Attributes[] = new weTagData_choiceAttribute('match', $locales, false, true, '');
