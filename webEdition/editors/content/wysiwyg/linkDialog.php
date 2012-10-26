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
 * @package    webEdition_wysiwyg
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
if(!(isset($_REQUEST['we_dialog_args']) && isset($_REQUEST['we_dialog_args']['outsideWE']) && $_REQUEST['we_dialog_args']['outsideWE'] == 1)){
	we_html_tools::protect();
}
$dialog = new weHyperlinkDialog();
$dialog->initByHttp();
$dialog->registerCmdFn("weDoLinkCmd");
print $dialog->getHTML();

function weDoLinkCmd($args){
	if((!isset($args["href"])) || $args["href"] == "http://"){
		$args["href"] = "";
	}
	$param = ($args["param"] ? "?" . str_replace("?", "", $args["param"]) : "");
	$param = trim($param, '&');
	$anchor = ($args["anchor"] ? "#" . str_replace("#", "", $args["anchor"]) : "");
	$anchor = trim($anchor);
	$href = $args["href"] . $param . $anchor;

	if(!(isset($_REQUEST['we_dialog_args']['editor']) && $_REQUEST['we_dialog_args']['editor'] == "tinyMce")){
		return we_html_element::jsElement(
			'top.opener.weWysiwygObject_' . $args["editname"] . '.createLink("' . $href . '","' . $args["target"] . '","' . $args["class"] . '","' . $args["lang"] . '","' . $args["hreflang"] . '","' . $args["title"] . '","' . $args["accesskey"] . '","' . $args["tabindex"] . '","' . $args["rel"] . '","' . $args["rev"] . '");
top.close();
');
	} else{
		//$pos = strpos($href, 'mailto:');
		if(strpos($href, 'mailto:') === 0){
			$href = $args["href"];
			foreach($args as $key=>$val){
				$args[$key] = '';
			}
		}

		$out = '<form name="tiny_form">
			<input type="hidden" name="href" value="'. $href . '">
			<input type="hidden" name="target" value="'. $args["target"] . '">
			<input type="hidden" name="class" value="'. $args["class"] . '">
			<input type="hidden" name="lang" value="'. $args["lang"] . '">
			<input type="hidden" name="hreflang" value="'. $args["hreflang"] . '">
			<input type="hidden" name="title" value="'. $args["title"] . '">
			<input type="hidden" name="accesskey" value="'. $args["accesskey"] . '">
			<input type="hidden" name="tabindex" value="'. $args["tabindex"] . '">
			<input type="hidden" name="rel" value="'. $args["rel"] . '">
			<input type="hidden" name="rev" value="'. $args["rev"] . '">
			</form>';

		$pathToTinyMce = WEBEDITION_DIR . 'editors/content/tinymce/jscripts/tiny_mce/';
		$out .= we_html_element::jsScript($pathToTinyMce . 'tiny_mce_popup.js') .
				we_html_element::jsScript($pathToTinyMce . 'utils/mctabs.js') .
				we_html_element::jsScript($pathToTinyMce . 'utils/form_utils.js') .
				we_html_element::jsScript($pathToTinyMce . 'utils/validate.js') .
				we_html_element::jsScript($pathToTinyMce . 'utils/editable_selects.js') .
				we_html_element::jsScript($pathToTinyMce . 'plugins/advlink/js/advlink_insert.js');
//t_e("out",$out);
		return $out;
	}
}
