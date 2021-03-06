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
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);
$this->Module = 'shop';

$this->Attributes[] = new weTagData_textAttribute('shopname', true, '');
$this->Attributes[] = new weTagData_textAttribute('pricename', true, '');
$this->Attributes[] = new weTagData_selectAttribute('netprices', weTagData_selectAttribute::getTrueFalse(), false, '');
$this->Attributes[] = new weTagData_selectAttribute('usevat', weTagData_selectAttribute::getTrueFalse(), false, '');

$this->Attributes[] = new weTagData_textAttribute('languagecode', false, '');
$this->Attributes[] = new weTagData_textAttribute('shipping', false, '');
$this->Attributes[] = new weTagData_textAttribute('shippingisnet', false, '');
$this->Attributes[] = new weTagData_textAttribute('shippingvatrate', false, '');

if(defined('FILE_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('434', 'onsuccess', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, false, '');
}
if(defined('FILE_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('435', 'onfailure', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, false, '');
}
if(defined('FILE_TABLE')){
	$this->Attributes[] = new weTagData_selectorAttribute('436', 'onabortion', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, false, '');
}
