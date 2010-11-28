<?php
function we_tag_quicktime($attribs, $content){
	// Include Quicktime class
	include_once ($_SERVER["DOCUMENT_ROOT"] . "/webEdition/we/include/we_classes/we_quicktimeDocument.inc.php");

	// Define globals
	global $we_editmode;

	$foo = attributFehltError($attribs, "name", "quicktime");
	if ($foo)
		return $foo;

	$name = we_getTagAttribute("name", $attribs);
	$id = $GLOBALS["we_doc"]->getElement($name, "bdid");
	$id = $id ? $id : we_getTagAttribute("id", $attribs);
	$fname = 'we_' . $GLOBALS["we_doc"]->Name . '_img[' . $name . '#bdid]';
	$startid = we_getTagAttribute("startid", $attribs, "");
	$parentid = we_getTagAttribute("parentid", $attribs, "0");

	$showcontrol = we_getTagAttribute("showcontrol", $attribs, "true", true, true);
	$showquicktime = we_getTagAttribute("showquicktime", $attribs, "true", true, true);

	$attribs = removeAttribs($attribs, array(
		'showcontrol', 'showquicktime', 'startid', 'parentid'
	));

	if ($we_editmode && !$showquicktime) {
		$out = '';
	} else {
		$out = $GLOBALS["we_doc"]->getField($attribs, "quicktime");
	}

	if ($showcontrol && $we_editmode) {
		include_once ($_SERVER["DOCUMENT_ROOT"] . "/webEdition/we/include/we_classes/html/we_button.inc.php");
		$we_button = new we_button();
		$quicktime_button = $we_button->create_button(
				"image:btn_edit_quicktime",
				"javascript:we_cmd('openDocselector','" . ($id != "" ? $id : $startid) . "', '" . FILE_TABLE . "', 'document.forms[\'we_form\'].elements[\'" . $fname . "\'].value', '', 'opener.setScrollTo();opener.top.we_cmd(\'reload_editpage\');opener._EditorFrame.setEditorIsHot(true);', '" . session_id() . "',". $parentid . ", 'video/quicktime', " . (we_hasPerm(
						"CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . ")",
				true);
		$clear_button = $we_button->create_button(
				"image:btn_function_trash",
				"javascript:we_cmd('remove_image', '" . $name . "')",
				true);

		$out = "
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\" background=\"" . IMAGE_DIR . "backgrounds/aquaBackground.gif\" style=\"border: solid #006DB8 1px;\">
				<tr>
					<td class=\"weEditmodeStyle\">$out
						<input type=\"hidden\" name=\"$fname\" value=\"" . $GLOBALS["we_doc"]->getElement(
				$name,
				"bdid") . "\"></td>
				</tr>
				<tr>
					<td class=\"weEditmodeStyle\" align=\"center\">";
		$out .= $we_button->create_button_table(array(
			$quicktime_button, $clear_button
		), 5) . "</td></tr></table>";
	}
	//	When in SEEM - Mode add edit-Button to tag - textarea
	return $out;
}?>
