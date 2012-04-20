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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
function we_tag_bannerSelect($attribs){
	global $DB_WE;
	$foo = attributFehltError($attribs, "name", __FUNCTION__);
	if($foo)
		return $foo;

	$name = weTag_getAttribute("name", $attribs);
	$customer = weTag_getAttribute("customer", $attribs, false, true);
	$rootdir = weTag_getAttribute("rootdir", $attribs, "/");
	$firstentry = weTag_getAttribute("firstentry", $attribs);
	$showpath = weTag_getAttribute("showpath", $attribs, false, true);
	$submitonchange = weTag_getAttribute("submitonchange", $attribs, false, true);

	$where = " WHERE IsFolder=0 ";

	$newAttribs = removeAttribs($attribs, array('showpath', 'rootdir', 'firstentry', 'submitonchange', 'customer'));
	if($submitonchange){
		$newAttribs['onchange'] = 'we_submitForm();';
	}

	$options = '';
	if($firstentry){
		$options = getHtmlTag('option', array('value' => ''), $firstentry, true);
		$select .= '<option value="">' . $firstentry . "</option>\n";
	}
	$DB_WE->query("SELECT ID,Text,Path,Customers FROM " . BANNER_TABLE . " $where ORDER BY Path");
	while($DB_WE->next_record()) {
		if((!defined("CUSTOMER_TABLE")) || (!$customer) || ($customer && defined("CUSTOMER_TABLE") && weBanner::customerOwnsBanner($_SESSION["webuser"]["ID"], $DB_WE->f("ID")))){
			if(!isset($_REQUEST[$name])){
				$_REQUEST[$name] = $DB_WE->f("Path");
			}
			if($_REQUEST[$name] == $DB_WE->f("Path")){
				$options .= getHtmlTag('option', array('value' => $DB_WE->f("Path"), 'selected' => 'selected'), $showpath ? $DB_WE->f("Path") : $DB_WE->f("Text")) . "\n";
			} else{
				$options .= getHtmlTag('option', array('value' => $DB_WE->f("Path")), $showpath ? $DB_WE->f("Path") : $DB_WE->f("Text")) . "\n";
			}
		}
	}

	if(isset($_REQUEST[$name])){
		$GLOBALS[$name] = $_REQUEST[$name];
	}
	return getHtmlTag('select', $newAttribs, $options, true);
}
