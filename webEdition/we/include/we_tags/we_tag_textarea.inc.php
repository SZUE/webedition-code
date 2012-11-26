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
function we_tag_textarea($attribs, $content){
	if(($foo = attributFehltError($attribs, "name", __FUNCTION__))){
		return $foo;
	}

	$name = weTag_getAttribute("name", $attribs);
	$xml = weTag_getAttribute("xml", $attribs, XHTML_DEFAULT, true);
	$removeFirstParagraph = weTag_getAttribute("removefirstparagraph", $attribs, defined("REMOVEFIRSTPARAGRAPH_DEFAULT") ? REMOVEFIRSTPARAGRAPH_DEFAULT : true, true);
	$attribs = removeAttribs($attribs, array('removefirstparagraph'));

	$html = weTag_getAttribute("html", $attribs, true, true);
	$autobrAttr = weTag_getAttribute("autobr", $attribs, false, true);
	$spellcheck = weTag_getAttribute('spellcheck', $attribs, 'true');

	$autobr = $GLOBALS['we_doc']->getElement($name, "autobr");
	if(strlen($autobr) == 0){
		$autobr = $autobrAttr ? "on" : "off";
	}
	$showAutobr = isset($attribs["autobr"]);
	if(!$showAutobr && $GLOBALS['we_editmode']){
		$autobr = "off";
		$GLOBALS['we_doc']->elements[$name]["autobr"] = "off";
		$GLOBALS['we_doc']->saveInSession($_SESSION['weS']['we_data'][$GLOBALS['we_transaction']]);
	}

	$autobrName = 'we_' . $GLOBALS['we_doc']->Name . '_txt[' . $name . '#autobr]';
	$fieldname = 'we_' . $GLOBALS['we_doc']->Name . '_txt[' . $name . ']';
	$value = $GLOBALS['we_doc']->getElement($name) ? $GLOBALS['we_doc']->getElement($name) : $content;

	if($GLOBALS['we_editmode']){
		if((!$GLOBALS['we_doc']->getElement($name)) && $value){ // when not inlineedit, we need to save the content in the object, if the field is empty
			$GLOBALS['we_doc']->setElement($name, $value);
			$GLOBALS['we_doc']->saveInSession($_SESSION['weS']['we_data'][$GLOBALS['we_transaction']]);
		}
		return we_forms::weTextarea($fieldname, $value, $attribs, $autobr, $autobrName, $showAutobr, $GLOBALS['we_doc']->getHttpPath(), false, false, $xml, $removeFirstParagraph, '', ($spellcheck == 'true'));
	} else{
		$fieldVal = $GLOBALS['we_doc']->getField($attribs);
		$fieldVal = $removeFirstParagraph ? removeFirstParagraph($fieldVal) : $fieldVal;
		$fieldVal = weTag_getAttribute("htmlspecialchars", $attribs, false, true) ? $fieldVal : htmlspecialchars_decode($fieldVal);
		return $fieldVal;
	}
}

function DOMRemove(DOMNode $from) {;
	$sibling = $from->firstChild;
	do {
		$next = $sibling->nextSibling;
		$from->parentNode->insertBefore($sibling, $from);
	} while ($sibling = $next);
	$from->parentNode->removeChild($from);
}

function removeFirstParagraph($string){
	$string = $GLOBALS['we_doc']->elements['Charset']['dat'] != 'UTF-8' ? convertCharsetEncoding($GLOBALS['we_doc']->elements['Charset']['dat'], 'UTF-8', $string) : $string;
	if(strpos(ltrim($string,' '),'<p>') === 0){
		$dom = new DOMDocument;
		$dom->loadHTML($string);
		$nodes = $dom->getElementsByTagName('p');
		DOMRemove($nodes->item(0));
		$string = $dom->saveXML();
		$string = preg_replace('/(<html>|<\/html>|<body>|<\/body>)/i', '', $string);
		}
	return $GLOBALS['we_doc']->elements['Charset']['dat'] != 'UTF-8' ? convertCharsetEncoding('UTF-8', $GLOBALS['we_doc']->elements['Charset']['dat'], $string) : $string;;
}