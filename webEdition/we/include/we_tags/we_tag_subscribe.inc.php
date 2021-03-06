<?php

/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
function we_tag_subscribe(array $attribs){
	$type = weTag_getAttribute("type", $attribs, "email", we_base_request::STRING);
	$values = weTag_getAttribute("values", $attribs, '', we_base_request::RAW);
	$value = weTag_getAttribute("value", $attribs, '', we_base_request::RAW);
	$xml = weTag_getAttribute('xml', $attribs, XHTML_DEFAULT, we_base_request::BOOL);
	$checked = weTag_getAttribute("checked", $attribs, false, we_base_request::BOOL);

	switch($type){
		case "listCheckbox":
			$nr = isset($GLOBALS["WE_TAG_SUBSCRIBE_LISTCHECKBOX_NR"]) ? ($GLOBALS["WE_TAG_SUBSCRIBE_LISTCHECKBOX_NR"] + 1) : 0;
			$GLOBALS["WE_TAG_SUBSCRIBE_LISTCHECKBOX_NR"] = $nr;

			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value'));

			$newAttribs['type'] = 'checkbox';
			$newAttribs['name'] = 'we_subscribe_list__[' . $nr . ']';
			$newAttribs['value'] = $nr;

			if($checked || (($list = we_base_request::_(we_base_request::HTML, "we_subscribe_list__")) && in_array($nr, $list))){
				$newAttribs['checked'] = 'checked';
			}

			return getHtmlTag('input', array('type' => 'hidden', 'name' => 'we_use_lists__', 'value' => 1, 'xml' => $xml)) .
				getHtmlTag('input', $newAttribs);

		case "listSelect":
			if($values){
				$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values', 'lists', 'maxlength', 'checked'));
				$newAttribs['name'] = 'we_subscribe_list__[]';
				$newAttribs['multiple'] = 'multiple';
				$options = '';
				$vals = makeArrayFromCSV($values);
				foreach($vals as $i => $v){
					$options .= (($list = we_base_request::_(we_base_request::HTML, "we_subscribe_list__")) && in_array($i, $list) ?
							getHtmlTag('option', array('value' => $i, 'selected' => 'selected'), oldHtmlspecialchars($v)) :
							getHtmlTag('option', array('value' => $i), oldHtmlspecialchars($v)));
				}
				return getHtmlTag('input', array('type' => 'hidden', 'name' => 'we_use_lists__', 'value' => 1, 'xml' => $xml)) .
					getHtmlTag('select', $newAttribs, $options, true);
			}
			return '';

		case "htmlCheckbox":
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'checked', 'values'));
			$newAttribs['name'] = 'we_subscribe_html__';
			$newAttribs['type'] = 'checkbox';
			$newAttribs['value'] = 1;
			if($checked || we_base_request::_(we_base_request::BOOL, "we_subscribe_html__")){
				$newAttribs['checked'] = 'checked';
			}
			return getHtmlTag('input', $newAttribs);

		case "htmlSelect":
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'size', 'values', 'maxlength', 'checked'));
			$newAttribs['name'] = 'we_subscribe_html__';
			$value = weTag_getAttribute("value", $attribs, false, we_base_request::BOOL);
			$ishtml = we_base_request::_(we_base_request::BOOL, "we_subscribe_html__", (isset($attribs["value"]) ? $value : false));
			$values = ($values ? makeArrayFromCSV($values) : array("Text", "HTML"));

			if($ishtml){
				$options = getHtmlTag('option', array('value' => 0), oldHtmlspecialchars($values[0])) . "\n";
				$options .= getHtmlTag('option', array('value' => 1, 'selected' => 'selected'), oldHtmlspecialchars($values[1])) . "\n";
			} else {
				$options = getHtmlTag('option', array('value' => 0, 'selected' => 'selected'), oldHtmlspecialchars($values[0])) . "\n";
				$options .= getHtmlTag('option', array('value' => 1), oldHtmlspecialchars($values[1])) . "\n";
			}
			return getHtmlTag('select', $newAttribs, $options, true);

		case "firstname":
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values'));

			$newAttribs['type'] = 'text';
			$newAttribs['name'] = 'we_subscribe_firstname__';

			$newAttribs['value'] = we_base_request::_(we_base_request::HTML, "we_subscribe_firstname__", $value);

			return getHtmlTag('input', $newAttribs);

		case "salutation":
			if($values){
				$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values', 'maxlength', 'checked'));
				$name = 'we_subscribe_salutation__';
				$value = we_base_request::_(we_base_request::HTML, 'we_subscribe_salutation__', $value);
				return we_getSelectField($name, $value, $values, $newAttribs, true); //same function like <we:sessionField type="select">
			}
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values'));
			$newAttribs['name'] = 'we_subscribe_salutation__';
			$newAttribs['type'] = 'text';
			$newAttribs['value'] = we_base_request::_(we_base_request::HTML, 'we_subscribe_salutation__', $value);

			return getHtmlTag('input', $newAttribs);

		case "title":
			if($values){
				$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values', 'maxlength', 'checked'));
				$name = 'we_subscribe_title__';
				$value = we_base_request::_(we_base_request::HTML, "we_subscribe_title__", $value);
				return we_getSelectField($name, $value, $values, $newAttribs, true); //same function like <we:sessionField type="select">
			}
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values'));
			$newAttribs['name'] = 'we_subscribe_title__';
			$newAttribs['type'] = 'text';
			$newAttribs['value'] = we_base_request::_(we_base_request::HTML, "we_subscribe_title__", $value);

			return getHtmlTag('input', $newAttribs);
		case "lastname":
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values'));
			$newAttribs['type'] = 'text';
			$newAttribs['name'] = 'we_subscribe_lastname__';
			$newAttribs['value'] = we_base_request::_(we_base_request::HTML, "we_subscribe_lastname__", $value);
			return getHtmlTag('input', $newAttribs);

		case "email":
		default:
			$newAttribs = removeAttribs($attribs, array('name', 'type', 'value', 'values'));
			$newAttribs['type'] = 'text';
			$newAttribs['name'] = 'we_subscribe_email__';
			$newAttribs['value'] = we_base_request::_(we_base_request::EMAIL, "we_subscribe_email__", $value);

			return getHtmlTag('input', $newAttribs); // '<input type="text" name="we_subscribe_email__"'.($attr ? " $attr" : "").($value ? ' value="'.oldHtmlspecialchars($value).'"' : '').($xml ? ' /' : '').' />';
	}

	return '';
}
