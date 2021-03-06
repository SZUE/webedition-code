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
// Define needed JS
require_once(WE_INCLUDES_PATH . 'we_editors/we_preferences_config.inc.php');

function getPreferencesFooterJS(){
	$tmp = '';
	foreach(array_keys($GLOBALS['tabs']) as $key){
		$tmp.="document.getElementById('content').contentDocument.getElementById('setting_" . $key . "').style.display = 'none';";
	}
	$javascript = <<< END_OF_SCRIPT
var countSaveTrys = 0;
function we_save() {
		$tmp
	// update setting for message_reporting
	WE().session.messageSettings = document.getElementById('content').contentDocument.getElementById("message_reporting").value;

	if(WE().layout.weEditorFrameController.getActiveDocumentReference().quickstart){
		var oCockpit=WE().layout.weEditorFrameController.getActiveDocumentReference();
		var _fo=document.getElementById('content').contentDocument.forms[0];
		var oSctCols=_fo.elements['newconf[cockpit_amount_columns]'];
		var iCols=oSctCols.options[oSctCols.selectedIndex].value;
		if(iCols!=oCockpit._iLayoutCols){
			oCockpit.modifyLayoutCols(iCols);
		}
	}

	document.getElementById('content').contentDocument.getElementById('setting_save').style.display = '';
	document.getElementById('content').contentDocument.we_form.save_settings.value = 1;

	document.getElementById('content').contentDocument.we_form.submit();
 }

END_OF_SCRIPT;
	return we_html_element::jsElement($javascript);
}

/* * ***************************************************************************
 * RENDER FILE
 * *************************************************************************** */

function getPreferencesFooter(){
	$okbut = we_html_button::create_button(we_html_button::SAVE, 'javascript:we_save();');
	$cancelbut = we_html_button::create_button(we_html_button::CLOSE, 'javascript:top.close()');

	return we_html_element::htmlDiv(array('class' => 'weDialogButtonsBody', 'style' => 'height:100%;'), we_html_button::position_yes_no_cancel($okbut, '', $cancelbut, 10, '', '', 0));
}
