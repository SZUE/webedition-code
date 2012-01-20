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


include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we.inc.php");
we_html_tools::protect();
$dialog = new weColorDialog();
$dialog->initByHttp();
$dialog->registerOkJsFN("weDoColorJS");
print $dialog->getHTML();

function weDoColorJS(){


	return '
var type = document.we_form.elements["we_dialog_args[type]"].value;
var color = document.we_form.elements["we_dialog_args[color]"].value;
var editorObj;
var name;

if(type == "forecolor" || type == "backcolor"){
	eval("editorObj = top.opener.weWysiwygObject_"+document.we_form.elements["we_dialog_args[editname]"].value);
}else{
	name = document.we_form.elements["we_dialog_args[name]"].value;
}
switch(type){
	case "forecolor":
		editorObj.setForecolor(color);
		break;
	case "backcolor":
		editorObj.setBackcolor(color);
		break;
	case "dialog":
		var inp = top.opener.document.we_form.elements[name];
		inp.value = color;
		inp.style.backgroundColor = color;
		break;
}
top.close();
';

}
?>