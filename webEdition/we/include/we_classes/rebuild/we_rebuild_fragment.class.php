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
class we_rebuild_fragment extends we_fragment_base{

	function doTask(){
		$this->updateProgressBar();
		we_rebuild_base::rebuild($this->data);
	}

	function updateProgressBar(){
		$percent = round((100 / count($this->alldata)) * (1 + $this->currentTask));
		echo we_html_element::jsElement('if(parent.wizbusy.document.getElementById("progr")){parent.wizbusy.document.getElementById("progr").style.display="";};parent.wizbusy.setProgressText("pb1",(parent.wizbusy.document.getElementById("progr") ? "' . addslashes(we_util_Strings::shortenPath($this->data["path"], 33)) . '" : "' . g_l('rebuild', "[savingDocument]") . addslashes(we_util_Strings::shortenPath($this->data["path"], 60)) . '") );parent.wizbusy.setProgress(' . $percent . ');');
		flush();
	}

	function finish(){
		$responseText = we_base_request::_(we_base_request::STRING, 'responseText', '');

		echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(addslashes($responseText ? : g_l('rebuild', "[finished]")), we_message_reporting::WE_MESSAGE_NOTICE) . '
			top.close();');
	}

	function printHeader(){
		we_html_tools::protect();
		echo we_html_tools::getHtmlTop() .
		'</head>';
	}

	function printBodyTag($attributes = ""){

	}

	function printFooter(){
		//note we need to subtract this, since it was already added in constructor loop.
		$this->currentTask = $this->currentTask - $this->taskPerFragment + 1;

		$this->printJSReload();
	}

}
