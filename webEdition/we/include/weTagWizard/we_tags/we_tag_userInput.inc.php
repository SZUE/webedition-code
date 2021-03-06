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

$name = new weTagData_textAttribute('name', true, '');
$property = new weTagData_selectAttribute('property', weTagData_selectAttribute::getTrueFalse(), false, '');
$checked = new weTagData_selectAttribute('checked', weTagData_selectAttribute::getTrueFalse(), false, '');
$editable = new weTagData_selectAttribute('editable', weTagData_selectAttribute::getTrueFalse(), false, '');
$xml = new weTagData_selectAttribute('xml', weTagData_selectAttribute::getTrueFalse(), false, '');
$removefirstparagraph = new weTagData_selectAttribute('removefirstparagraph', weTagData_selectAttribute::getTrueFalse(), false, '');
$size = new weTagData_textAttribute('size');
$maxlength = new weTagData_textAttribute('maxlength');
$format = new weTagData_textAttribute('format');
$value = new weTagData_textAttribute('value');
$values = new weTagData_textAttribute('values');
$hidden = new weTagData_selectAttribute('hidden', weTagData_selectAttribute::getTrueFalse(), false, '');
$currentdate = new weTagData_selectAttribute('currentdate', weTagData_selectAttribute::getTrueFalse(), false, '');
$cols = new weTagData_textAttribute('cols');
$rows = new weTagData_textAttribute('rows');
$pure = new weTagData_selectAttribute('pure', weTagData_selectAttribute::getTrueFalse(), false, '');
$autobr = new weTagData_selectAttribute('autobr', weTagData_selectAttribute::getTrueFalse(), false, '');
$width = new weTagData_textAttribute('width');
$height = new weTagData_textAttribute('height');
$bgcolor = new weTagData_textAttribute('bgcolor');
$class = new weTagData_textAttribute('class');
$style = new weTagData_textAttribute('style');
$classes = new weTagData_textAttribute('classes');
$hideautobr = new weTagData_selectAttribute('hideautobr', weTagData_selectAttribute::getTrueFalse(), false, '');
$wysiwyg = new weTagData_selectAttribute('wysiwyg', weTagData_selectAttribute::getTrueFalse(), false, '');
$buttonpos = new weTagData_choiceAttribute('buttonpos', array(new weTagDataOption('top'), new weTagDataOption('bottom')), false, false, '');
$commands = new weTagData_choiceAttribute('commands', we_wysiwyg_editor::getEditorCommands(true), false, true, '');
$contextmenu = new weTagData_choiceAttribute('contextmenu', we_wysiwyg_editor::getEditorCommands(true), false, true, '');
if(defined('FILE_TABLE')){
	$editorcss = new weTagData_selectorAttribute('editorcss', FILE_TABLE, 'text/css', false, '');
}
$ignoredocumentcss = new weTagData_selectAttribute('ignoredocumentcss', weTagData_selectAttribute::getTrueFalse(), false, '');
$fontnames = new weTagData_choiceAttribute('fontnames', array(new weTagDataOption('arial'),
	new weTagDataOption('courier'),
	new weTagDataOption('tahoma'),
	new weTagDataOption('times'),
	new weTagDataOption('verdana'),
	new weTagDataOption('wingdings'),
	), false, true, '');
$parentid = new weTagData_selectorAttribute('parentid', FILE_TABLE, weTagData_selectorAttribute::FOLDER, true, 'customer');
$quality = new weTagData_selectAttribute('quality', array(new weTagDataOption('0'),
	new weTagDataOption('1'),
	new weTagDataOption('2'),
	new weTagDataOption('3'),
	new weTagDataOption('4'),
	new weTagDataOption('5'),
	new weTagDataOption('6'),
	new weTagDataOption('7'),
	new weTagDataOption('8'),
	new weTagDataOption('9'),
	new weTagDataOption('10'),
	), false, 'customer');
$keepratio = new weTagData_selectAttribute('keepratio', weTagData_selectAttribute::getTrueFalse(), false, 'customer');
$maximize = new weTagData_selectAttribute('maximize', weTagData_selectAttribute::getTrueFalse(), false, 'customer');
$bordercolor = new weTagData_textAttribute('bordercolor', false, 'customer');
$checkboxstyle = new weTagData_textAttribute('checkboxstyle', false, 'customer');
$checkboxclass = new weTagData_textAttribute('checkboxclass', false, 'customer');
$inputstyle = new weTagData_textAttribute('inputstyle', false, 'customer');
$inputclass = new weTagData_textAttribute('inputclass', false, 'customer');
$checkboxtext = new weTagData_textAttribute('checkboxtext', false, 'customer');
$doc = new weTagData_selectAttribute('doc', array(
	new weTagDataOption('self'),
	new weTagDataOption('top'),
	), false, '');
$minyear = new weTagData_textAttribute('minyear');
$maxyear = new weTagData_textAttribute('maxyear');
$thumbnail = new weTagData_sqlRowAttribute('thumbnail', THUMBNAILS_TABLE, false, 'Name', '', '', '');


$this->TypeAttribute = new weTagData_typeAttribute('type', array(
	new weTagDataOption('textinput', false, '', array($name, $property, $editable, $size, $maxlength, $value, $class, $style), array($name)),
	new weTagDataOption('textarea', false, '', array($name, $property, $editable, $value, $cols, $rows, $autobr, $width, $height, $bgcolor, $class, $style, $hideautobr, $wysiwyg, $wysiwyg, $buttonpos, $ignoredocumentcss, $editorcss, $commands, $contextmenu, $fontnames, $classes), array($name)),
	new weTagDataOption('checkbox', false, '', array($name, $property, $checked, $editable), array($name)),
	new weTagDataOption('radio', false, '', array($name, $property, $checked, $editable, $value), array($name)),
	new weTagDataOption('choice', false, '', array($name, $property, $editable, $size, $maxlength, $value, $values, $class, $style), array($name)),
	new weTagDataOption('select', false, '', array($name, $property, $editable, $size, $value, $values, $class, $style), array($name)),
	new weTagDataOption('hidden', false, '', array($name, $property, $value), array($name)),
	new weTagDataOption('print', false, '', array($name, $property), array($name)),
	new weTagDataOption('date', false, '', array($name, $property, $editable, $format, $value, $minyear, $maxyear, $hidden), array($name)),
	new weTagDataOption('password', false, '', array(array())),
	new weTagDataOption('img', false, 'customer', array($name, $editable, $size, $value, $width, $height, $thumbnail, $parentid, $quality, $keepratio, $maximize, $bordercolor, $checkboxstyle, $checkboxclass, $inputstyle, $inputclass, $checkboxtext), array($name, $parentid)),
	new weTagDataOption('flashmovie', false, 'customer', array($name, $editable, $size, $value, $width, $height, $parentid, $quality, $keepratio, $maximize, $bordercolor, $checkboxstyle, $checkboxclass, $inputstyle, $inputclass, $checkboxtext), array($name, $parentid)),
	new weTagDataOption('quicktime', false, 'customer', array($name, $editable, $size, $value, $width, $height, $parentid, $quality, $keepratio, $maximize, $bordercolor, $checkboxstyle, $checkboxclass, $inputstyle, $inputclass, $checkboxtext), array($name, $parentid)),
	new weTagDataOption('binary', false, 'customer', array($name, $editable, $size, $value, $parentid, $bordercolor, $checkboxstyle, $checkboxclass, $inputstyle, $inputclass, $checkboxtext), array($name, $parentid)),
	new weTagDataOption('country', false, '', array($name, $size, $class, $style, $doc, $value), array($name)),
	new weTagDataOption('language', false, '', array($name, $size, $class, $style, $doc, $value), array($name))), true, '');

$this->Attributes = array($name, $property, $checked, $editable, $xml, $removefirstparagraph, $size, $maxlength, $format, $value, $values, $hidden, $currentdate, $cols,
	$rows, $pure, $autobr, $width, $height, $bgcolor, $class, $style, $wysiwyg, $buttonpos, $ignoredocumentcss, $editorcss, $commands, $contextmenu, $classes, $fontnames, $parentid, $quality, $keepratio, $maximize, $thumbnail, $bordercolor,
	$checkboxstyle, $checkboxclass, $inputstyle, $inputclass, $checkboxtext, $doc, $minyear, $maxyear);
