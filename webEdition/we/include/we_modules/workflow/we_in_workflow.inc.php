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

we_html_tools::protect();
?>
<script  type="text/javascript"><!--
	top.opener.top.toggleBusy(0);
//-->

</script>
<?php
if($cmd == "ok"){
	$wf_text = $_REQUEST["wf_text"];
	$wf_select = $_REQUEST["wf_select"];
	if(weWorkflowUtility::insertDocInWorkflow($we_doc->ID,$we_doc->Table,$wf_select,$_SESSION["user"]["ID"],$wf_text)){
		$msg = g_l('modules_workflow','['.stripTblPrefix($we_doc->Table).'][in_workflow_ok]');
		$msgType = we_message_reporting::WE_MESSAGE_NOTICE;
		if($_SESSION["we_mode"] == "seem"){

			$script = "opener.top.we_cmd('switch_edit_page'," .WE_EDITPAGE_PREVIEW . ",'" . $we_transaction . "');";
		} else if($_SESSION["we_mode"] == "normal"){

			$script = 'opener.top.weEditorFrameController.getActiveDocumentReference().frames[3].location.reload();';
		}

		if($_REQUEST["we_cmd"][2]){ // make same new
			$we_doc->makeSameNew();
			$we_doc->saveInSession($_SESSION["we_data"][$we_transaction]); // save the changed object in session
			$script .= 'opener.top.we_cmd("switch_edit_page","'.$we_doc->EditPageNr.'","'.$we_transaction.'");'; // wird in Templ eingef�gt
		}else{
			if(($we_doc->EditPageNr == WE_EDITPAGE_PROPERTIES || $we_doc->EditPageNr == WE_EDITPAGE_INFO)){

				$script .= 'opener.top.we_cmd("switch_edit_page","'.$we_doc->EditPageNr.'","'.$we_transaction.'");'; // wird in Templ eingef�gt
				}
			}
		}
		else {
			$msg = g_l('modules_workflow','['.stripTblPrefix($we_doc->Table).'][in_workflow_notok]');
			$msgType = we_message_reporting::WE_MESSAGE_ERROR;
			if($_SESSION["we_mode"] == "seem"){

				$script = "opener.top.we_cmd('switch_edit_page'," .WE_EDITPAGE_PREVIEW . ",'" . $we_transaction . "');";
			} else if($_SESSION["we_mode"] == "normal"){

			$script = '';
		}
		}
		print '
			<script  type="text/javascript"><!--
				'.$script.'
				' . we_message_reporting::getShowMessageCall($msg, $msgType) . '
				self.close();
			//-->
			</script>';
	}
 print STYLESHEET; ?>
</head>

<body class="weDialogBody">
	<center>
		<?php if($cmd!="ok"){
				if($we_doc->Table==FILE_TABLE) {
					$wfDoc = weWorkflowUtility::getWorkflowDocumentForDoc($we_doc->DocType, $we_doc->Category, $we_doc->ParentID);
				}
				else {
					$wfDoc = weWorkflowUtility::getWorkflowDocumentForObject($we_doc->TableID, $we_doc->Category, $we_doc->ParentID);
				}
				$wfID=$wfDoc->workflowID;
			 if($wfID){ ?>
				<form action="<?php print WEBEDITION_DIR; ?>we_cmd.php" method="post">
					<?php
						$wf_select = '<select name="wf_select" size="1">';
						$wfs = weWorkflowUtility::getAllWorkflows(WE_WORKFLOW_STATE_ACTIVE,$we_doc->Table);
						foreach($wfs as $wID=>$wfname) {
							$wf_select .= '<option value="'.$wID.'"'.(($wID == $wfID) ? ' selected' : '').'>'.htmlspecialchars($wfname)."</option>\n";
						}
						$wf_select .= '</select>';

						$okbut     = we_button::create_button("ok", "javascript:document.forms[0].submit()");
						$cancelbut = we_button::create_button("cancel","javascript:top.close()");

						$content = '<table border="0" cellpadding="0" cellspacing="0">';

						if(we_hasPerm("PUBLISH")) {
							$wf_textarea = '<textarea name="wf_text" rows="5" cols="50" style="width:360;height:150"></textarea>';
							$content .= '
								<tr>
									<td class="defaultfont">
										'.g_l('modules_workflow','[workflow]').'</td>
								</tr>
								<tr>
									<td>
										'.$wf_select.'</td>
								</tr>
								<tr>
									<td>
										'.we_html_tools::getPixel(2,5).'</td>
								</tr>';
						}
						else {
							$wf_textarea = '<textarea name="wf_text" rows="7" cols="50" style="width:360;height:190"></textarea>';
							$content .= '<input type="hidden" name="wf_select" value="'.$wfID.'" />';
						}
						$content .= '
								<tr>
									<td class="defaultfont">
										'.g_l('modules_workflow','[message]').'</td>
								</tr>
								<tr>
									<td>
										'.$wf_textarea.'</td>
								</tr>
							</table>';

						$_buttons = we_button::position_yes_no_cancel(	$okbut,
																		"",
																		$cancelbut);


						$frame = we_html_tools::htmlDialogLayout($content,g_l('modules_workflow','[in_workflow]'), $_buttons);

						print $frame;
						print '
							<input type="hidden" name="cmd" value="ok" />
							<input type="hidden" name="we_cmd[0]" value="'.$_REQUEST["we_cmd"][0].'" />
							<input type="hidden" name="we_cmd[1]" value="'.$we_transaction.'" />
							<input type="hidden" name="we_cmd[2]" value="'.$_REQUEST["we_cmd"][2].'" />';
					?>
				</form>
			<?php }else{ ?>
				<script  type="text/javascript"><!--
					<?php print we_message_reporting::getShowMessageCall( (($we_doc->Table==FILE_TABLE) ? g_l('modules_workflow','[no_wf_defined]') : g_l('modules_workflow','[no_wf_defined_object]') ), we_message_reporting::WE_MESSAGE_ERROR); ?>
					top.close();
				//-->
				</script>
			<?php }} ?>
	</center>
</body>

</html>