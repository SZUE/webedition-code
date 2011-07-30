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


include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/html/we_button.inc.php");
protect();
if($cmd == "ok"){
	$wf_text = $_REQUEST["wf_text"];
	$wf_select = isset($_REQUEST["wf_select"]) ? $_REQUEST["wf_select"] : "";

	$force = (!weWorkflowUtility::isUserInWorkflow($we_doc->ID,$we_doc->Table,$_SESSION["user"]["ID"]));

	$ok = weWorkflowUtility::approve($we_doc->ID,$we_doc->Table,$_SESSION["user"]["ID"],$wf_text,$force);

	if($ok){
		$msg = g_l('modules_workflow','['.$we_doc->Table.'][pass_workflow_ok]');
		$msgType = WE_MESSAGE_NOTICE;

		//	in SEEM-Mode back to Preview page
		if($_SESSION["we_mode"] == "seem"){

			$script = "opener.top.we_cmd('switch_edit_page'," .WE_EDITPAGE_PREVIEW . ",'" . $we_transaction . "');";
		} else if($_SESSION["we_mode"] == "normal"){

			$script = 'opener.top.weEditorFrameController.getActiveDocumentReference().frames[3].location.reload();';
		}

		if(($we_doc->EditPageNr == WE_EDITPAGE_PROPERTIES || $we_doc->EditPageNr == WE_EDITPAGE_INFO)){
			$script .= 'opener.top.we_cmd("switch_edit_page","'.$we_doc->EditPageNr.'","'.$we_transaction.'");'; // wird in Templ eingef�gt
		}
	}else{
		$msg = g_l('modules_workflow','['.$we_doc->Table.'][pass_workflow_notok]');
		$msgType = WE_MESSAGE_ERROR;
				//	in SEEM-Mode back to Preview page
		if($_SESSION["we_mode"] == "seem"){

			$script = "opener.top.we_cmd('switch_edit_page'," .WE_EDITPAGE_PREVIEW . ",'" . $we_transaction . "');";
		} else if($_SESSION["we_mode"] == "normal"){

			$script = '';
		}
	}
	print '<script  type="text/javascript"><!--
'.$script.'
' . we_message_reporting::getShowMessageCall($msg,  $msgType) . ';
top.close();
//-->
</script>
';
}
 print STYLESHEET; ?>
</head>
<body class="weDialogBody"><center>
<?php if($cmd=="ok"){ 
	}else{ ?>
<form action="<?php print WEBEDITION_DIR; ?>we_cmd.php" method="post">
<?php

$we_button = new we_button();

$okbut     = $we_button->create_button("ok", "javascript:document.forms[0].submit()");
$cancelbut = $we_button->create_button("cancel", "javascript:top.close()");


$content = '<table border="0" cellpadding="0" cellspacing="0">
';
$wf_textarea = '<textarea name="wf_text" rows="7" cols="50" style="width:360;height:190"></textarea>';
$content .= '<tr>
<td class="defaultfont">'.g_l('modules_workflow','[message]').'</td>
</tr>
<tr>
<td>'.$wf_textarea.'</td>
</tr>
</table>
';

$_buttons = $we_button->position_yes_no_cancel(	$okbut,
												"",
												$cancelbut);
$frame = htmlDialogLayout($content,g_l('modules_workflow','[pass_workflow]'), $_buttons);

print $frame;

print '	<input type="hidden" name="cmd" value="ok" />
		<input type="hidden" name="we_cmd[0]" value="'.$_REQUEST["we_cmd"][0].'" />
		<input type="hidden" name="we_cmd[1]" value="'.$we_transaction.'" />';
?>
</form>
<?php } ?>
</center>
</body>
</html>