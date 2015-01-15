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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

we_html_tools::protect();
echo we_html_tools::getHtmlTop() .
 STYLESHEET;


//	Here begins the code for showing the correct frameset.
//	To improve readability the different cases are outsourced
//	in several functions, for SEEM, normal or edit_include-Mode.

/**
 * function startNormalMode()
 * @desc	This function writes the frameset in the resizeframe for the webedition-start
  in the normal mode.
 */
function getSidebarWidth(){
// Get the width of the sidebar
	if(SIDEBAR_DISABLED != 1 && SIDEBAR_SHOW_ON_STARTUP == 1){
		return SIDEBAR_DEFAULT_WIDTH;
	}
	return 0;
}

function startNormalMode(){
	$_sidebarwidth = getSidebarWidth();
	$_treewidth = isset($_COOKIE["treewidth_main"]) && ($_COOKIE["treewidth_main"] >= weTree::MinWidth) ? $_COOKIE["treewidth_main"] : weTree::DefaultWidth;
	?>
	<div style="position:absolute;top:0px;bottom:0px;left:0px;right:0px;border: 0px;">
		<div style="width:<?php echo $_treewidth; ?>px;display:block;" id="bframeDiv">
			<?php include(WE_INCLUDES_PATH . 'baumFrame.inc.php'); ?>
		</div>
		<div style="position:absolute;top:0px;bottom:0px;right:<?php echo $_sidebarwidth; ?>px;left:<?php echo $_treewidth; ?>px;border-left:1px solid black;overflow: hidden;" id="bm_content_frameDiv">
			<iframe frameBorder="0" src="<?php echo WEBEDITION_DIR; ?>multiContentFrame.php" name="bm_content_frame" style="border:0px;width:100%;height:100%;overflow: hidden;"></iframe>
		</div>
		<?php if(!(SIDEBAR_DISABLED == 1)){ ?>
			<div style="position:absolute;top:0px;bottom:0px;right:0px;width:<?php echo $_sidebarwidth; ?>px;border-left:1px solid black;" id="sidebarDiv">
				<?php
				$weFrame = new we_sidebar_frames();
				$weFrame->getHTML('');
				?>
			</div>
		<?php } ?>
	</div>
	<?php
}

/**
 * function startEditInclude()
 * @desc	This function writes the frameset in the resizeframe for an edit-include-window
 */
function startEditIncludeMode(){
	$we_cmds = "we_cmd[0]=edit_document&";

	foreach(we_base_request::_(we_base_request::STRING, 'we_cmd') as $i => $v){
		$we_cmds .= "we_cmd[" . $i . "]=" . $v . "&";
	}
}

/**
 * function startSEEMMode()
 * @desc	This function writes the frameset in the resizeframe for the webedition-start
  in the SEEM-mode.
 */
function startSEEMMode(){
	$_sidebarwidth = getSidebarWidth();
	?>
	<div style="position:absolute;top:0px;bottom:0px;left:0px;right:0px;border: 0px;">
		<div id="bframeDiv">
			<?php include(WE_INCLUDES_PATH . 'baumFrame.inc.php'); ?>
		</div>
		<div style="position:absolute;top:0px;bottom:0px;right:<?php echo $_sidebarwidth; ?>px;left:0px;border-left:1px solid black;overflow: hidden;" id="bm_content_frameDiv">
			<iframe frameBorder="0" src="<?php echo WEBEDITION_DIR; ?>multiContentFrame.php" name="bm_content_frame" style="border:0px;width:100%;height:100%;overflow: hidden;"></iframe>
		</div>
		<?php if($_sidebarwidth > 0){ ?>
			<div style="position:absolute;top:0px;bottom:0px;right:0px;width:<?php echo $_sidebarwidth; ?>px;border-left:1px solid black;" id="sidebarDiv">
				<?php
				$weFrame = new we_sidebar_frames();
				$weFrame->getHTML('');
				?>

			</div>
		<?php } ?>
	</div>
	<?php
}
?>

<script type="text/javascript"><!--
	function we_cmd() {
		var args = "";
		switch (arguments[0]) {
			case "loadVTab":
				var op = top.makeFoldersOpenString();
				parent.we_cmd("load", arguments[1], 0, op, top.treeData.table);
				break;
			default:

				for (var i = 0; i < arguments.length; i++) {
					args += 'arguments[' + i + ']' + ((i < (arguments.length - 1)) ? ',' : '');
				}
				eval('parent.we_cmd(' + args + ')');
		}
	}



//-->
</script>
</head>
<body id="resizeFrame" onload="top.start();">
	<?php
//	Here begins the controller of the page
//  Edit an included file with SEEM.
	if(we_base_request::_(we_base_request::BOOL, 'SEEM_edit_include')){
		startEditIncludeMode();

//  We are in SEEM-Mode
	} else if($_SESSION['weS']['we_mode'] == we_base_constants::MODE_SEE){
		startSEEMMode();

//  Open webEdition normally
	} else {
		startNormalMode();
	}
	?>
</body>
</html>