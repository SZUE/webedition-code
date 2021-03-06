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
//$this->Module = '';
$this->Description = g_l('weTag', '[' . $tagName . '][description]', true);

$id = new weTagData_selectorAttribute('id', FILE_TABLE, we_base_ContentTypes::WEDOCUMENT, true, '');
$target = new weTagData_choiceAttribute('target', array(
	new weTagDataOption('_top'),
	new weTagDataOption('_parent'),
	new weTagDataOption('_self'),
	new weTagDataOption('_blank'),
	), false, false, '');
$confirm = new weTagData_textAttribute('confirm', false, '');
$button = new weTagData_selectAttribute('button', weTagData_selectAttribute::getTrueFalse(), false, '');
$hrefonly = new weTagData_selectAttribute('hrefonly', weTagData_selectAttribute::getTrueFalse(), false, '');
$class = new weTagData_textAttribute('class', false, '');
$style = new weTagData_textAttribute('style', false, '');
$params = new weTagData_textAttribute('params', false, '');
$hidedirindex = new weTagData_selectAttribute('hidedirindex', weTagData_selectAttribute::getTrueFalse(), false, '');
$amount = new weTagData_textAttribute('amount', false, 'shop');
$delarticle = new weTagData_selectAttribute('delarticle', weTagData_selectAttribute::getTrueFalse(), false, '');
$delshop = new weTagData_selectAttribute('delshop', weTagData_selectAttribute::getTrueFalse(), false, 'shop');
$shopname = new weTagData_textAttribute('shopname', false, 'shop');
$editself = new weTagData_selectAttribute('editself', weTagData_selectAttribute::getTrueFalse(), false, '');
$delete = new weTagData_selectAttribute('delete', weTagData_selectAttribute::getTrueFalse(), false, '');
$xml = new weTagData_selectAttribute('xml', weTagData_selectAttribute::getTrueFalse(), false, '');


$this->TypeAttribute = new weTagData_typeAttribute('edit', array(
	new weTagDataOption('', false, '', array($id, $target, $confirm, $button, $hrefonly, $class, $style, $params, $hidedirindex), array($id)),
	new weTagDataOption('document', false, '', array($id, $target, $confirm, $button, $hrefonly, $class, $style, $params, $hidedirindex, $editself, $delete), array($id)),
	new weTagDataOption('object', false, 'object', array($id, $target, $confirm, $button, $hrefonly, $class, $style, $params, $hidedirindex, $editself, $delete), array($id)),
	new weTagDataOption('shop', false, 'shop', array($id, $target, $confirm, $button, $hrefonly, $class, $style, $params, $hidedirindex, $amount, $delarticle, $delshop, $shopname,), array($id)))
	, false, '');


$this->Attributes = array($id, $target, $confirm, $button, $hrefonly, $class, $style, $params, $hidedirindex, $amount, $delarticle, $delshop, $shopname, $editself, $delete, $xml);
