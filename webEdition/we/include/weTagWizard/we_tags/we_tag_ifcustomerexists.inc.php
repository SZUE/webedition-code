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
$this->Module = 'customer';
$this->Deprecated = true;
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);
