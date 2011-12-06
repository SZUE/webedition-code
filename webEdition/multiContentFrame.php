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

include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we.inc.php");

protect();

$_cmd_string = '';

if (isset($_REQUEST['SEEM_edit_include']) && $_REQUEST['SEEM_edit_include']) {
	for ($i=1; $i<4; $i++) {
		$_cmd_string .= ",'" . $_REQUEST['we_cmd'][$i] . "'";

	}
	$_cmd_string .= ",'SEEM_edit_include'";
}

we_html_tools::htmlTop();
?>
<script type="text/javascript"><!--
	function we_cmd(){
		var args = "";
		for(var i = 0; i < arguments.length; i++){
			args += 'arguments['+i+']' + ( (i < (arguments.length-1)) ? ',' : '');
		}
		eval('parent.we_cmd('+args+')');
	}

	function doSafariLoad() {
		window.frames["multiEditorDocumentControllerFrame"].document.location = "<?php print WEBEDITION_DIR ?>multiEditor/EditorFrameController.php";

	}

	function startMultiEditor() {
		we_cmd('start_multi_editor'<?php print $_cmd_string; ?>);

	}
//-->
</script>
</head>
<body>
<div style="position:absolute;top:0;bottom:0;right:0;left:0;overflow: hidden;background-color: white;">
       <div style="position:absolute;top:0;height:22px;width:100%;" id="multiEditorDocumentTabsFrameDiv">
				<iframe src="<?php print WEBEDITION_DIR ?>multiEditor/multiTabs.php" style="border:0;width: 100%;height:100%;overflow: hidden;" name="multiEditorDocumentTabsFrame"></iframe>
			</div>
       <div style="position: absolute;height:0;bottom: 0;left:0;right:0;">
				<iframe src="<?php print WEBEDITION_DIR ?>multiEditor/EditorFrameController.php" name="multiEditorDocumentControllerFrame" style="border:0;overflow: hidden;width:100%;height:100%;" onload="startMultiEditor();"></iframe>
			</div>
			<div style="position:absolute;top:22px;bottom:0;left:0;right:0;overflow: auto;">
				<iframe src="<?php print WEBEDITION_DIR ?>multiEditor/multiEditorFrameset.php" name="multiEditorEditorFramesets" style="border:0;width:100%;height:100%;overflow: hidden;"></iframe>
       </div>
     </div>
</body>
</html>