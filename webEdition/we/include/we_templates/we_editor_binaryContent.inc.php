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
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_html_tools.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_classes/html/we_button.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_classes/html/we_multiIconBox.class.inc.php");

htmlTop();

echo we_htmlElement::jsScript(JS_DIR.'windows.js');
	include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_editors/we_editor_script.inc.php");

	print STYLESHEET;

?>

</head>

<body class="weEditorBody">
	<form name="we_form" method="post" onsubmit="return false;">
		<?php $we_doc->pHiddenTrans(); ?>
		<?php


		$parts = array();
		array_push($parts,array("icon"=>"upload.gif", "headline"=>"","html"=>$GLOBALS['we_doc']->formUpload(),"space"=>140));
		if (method_exists($GLOBALS['we_doc'],"formProperties")) array_push($parts,array("icon"=>"attrib.gif", "headline"=>g_l('weClass',"[attribs]"),"html"=>$GLOBALS['we_doc']->formProperties(),"space"=>140));
		array_push($parts,array("icon"=>"meta.gif", "headline"=>g_l('weClass',"[metadata]"),"html"=>$GLOBALS['we_doc']->formMetaInfos().$GLOBALS['we_doc']->formMetaData(),"space"=>140));
		print we_multiIconBox::getJS();
		print we_multiIconBox::getHTML("weImgProp","100%",$parts,20);

		?>
	</form>
</body>

</html>