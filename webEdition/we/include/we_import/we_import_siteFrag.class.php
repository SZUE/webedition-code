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
class we_import_siteFrag extends taskFragment{

	var $_obj = null;

	function __construct($obj){
		$this->_obj = $obj;
		parent::__construct(
			"siteImport", 1, 0, array(
			"marginwidth" => 15, "marginheight" => 10, "leftmargin" => 15, "topmargin" => 10
		));
	}

	function init(){
		$this->alldata = $this->_obj->_files;
	}

	function doTask(){
		$path = substr($this->data["path"], strlen($_SERVER['DOCUMENT_ROOT']));
		$progress = intval((100 / count($this->alldata)) * $this->currentTask);
		$progressText = we_util_Strings::shortenPath($path, 30);

		if($this->data["contentType"] == "post/process"){
			we_import_site::postprocessFile($this->data["path"], $this->data["sourceDir"], $this->data["destDirID"]);
		} else {
			$ret = we_import_site::importFile($this->data["path"], $this->data["contentType"], $this->data["sourceDir"], $this->data["destDirID"], $this->data["sameName"], $this->data["thumbs"], $this->data["width"], $this->data["height"], $this->data["widthSelect"], $this->data["heightSelect"], $this->data["keepRatio"], $this->data["quality"], $this->data["degrees"], $this->data["importMetadata"]);
			if(!empty($ret)){
				t_e('import error:', $ret);
			}
		}
		print we_html_element::jsElement('
top.siteimportbuttons.document.getElementById("progressBarDiv").style.display="block";
top.siteimportbuttons.weButton.disable("back");
top.siteimportbuttons.weButton.disable("next");
top.siteimportbuttons.setProgress(' . $progress . ');
top.siteimportbuttons.document.getElementById("progressTxt").innerHTML="' . oldHtmlspecialchars($progressText, ENT_QUOTES) . '";');
	}

	function finish(){
		print we_html_element::jsElement(
				"top.siteimportbuttons.setProgress(100);setTimeout('" . we_message_reporting::getShowMessageCall(
					g_l('siteimport', "[importFinished]"), we_message_reporting::WE_MESSAGE_NOTICE) . "top.close();',100);top.opener.top.we_cmd('load','" . FILE_TABLE . "');");
	}

	function printHeader(){
		print we_html_element::htmlDocType() . we_html_element::htmlhtml(we_html_element::htmlHead(//FIXME: missing title
					we_html_tools::getHtmlInnerHead() .
					STYLESHEET), false);
	}

}
