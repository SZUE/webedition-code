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
class we_chooser_multiDirAndTemplate extends we_chooser_multiDir{
	var $tmplcsv = "";
	var $tmplSelectName = "";
	var $mustTemplateIDs = "";
	var $tmplArr = "";
	var $tmplVals = array();
	var $tmplWs = "";
	var $mustTemplateIDsArr;
	var $mustPaths;
	var $create = 0;

	public function __construct($width, $ids, $cmd_del, $addbut, $ws = "", $tmplcsv = "", $tmplSelectName = "", $mustTemplateIDs = "", $tmplWs = "", $fields = "Path", $table = FILE_TABLE, $css = "defaultfont"){
		parent::__construct($width, $ids, $cmd_del, $addbut, $ws, $fields, $table, $css);
		$this->lines = 2;
		$this->tmplcsv = $tmplcsv;
		$this->tmplSelectName = $tmplSelectName;
		$this->mustTemplateIDs = $mustTemplateIDs;
		$this->mustTemplateIDsArr = makeArrayFromCSV($this->mustTemplateIDs);
		$this->tmplArr = makeArrayFromCSV($this->tmplcsv);
		$this->tmplValsArr = getPathsFromTable(TEMPLATES_TABLE, $this->db, we_base_constants::FILE_ONLY, get_ws(TEMPLATES_TABLE), "Path");
		$this->tmplWs = $tmplWs;
		$this->mustPaths = makeArrayFromCSV(id_to_path($this->mustTemplateIDsArr, TEMPLATES_TABLE, $this->db));
		foreach($this->mustTemplateIDsArr as $i => $id){
			if(!in_array($id, array_keys($this->tmplValsArr))){
				$this->tmplValsArr[$id] = isset($this->mustPaths[$i]) ? $this->mustPaths[$i] : "";
			}
		}
	}

	function getRootLine($lineNr){

		switch($lineNr){
			case 0:
				return '<tr>
	<td class="chooserFileIcon" data-contenttype="folder"></td>
	<td class="' . $this->css . '">/</td>
	<td>' . ((($this->isEditable() && $this->cmd_del) || $this->CanDelete) ?
						we_html_button::create_button(we_html_button::TRASH, "javascript:" . $this->getJsSetHot() . ($this->extraDelFn ? : "") . ";we_cmd('" . $this->cmd_del . "','0');") :
						"") . '</td>
</tr>';
			case 1:
				return $this->getLine($lineNr);
		}
	}

	function getLine($lineNr){
		switch($lineNr){
			case 0:
				return parent::getLine($lineNr);
			case 1:
				if($this->create){
					$but = we_html_button::create_button('fa:btn_add_template,fa-plus,fa-lg fa-file-code-o', "javascript:we_cmd('object_create_tmpfromClass','0','" . $this->nr . "','" . $GLOBALS["we_transaction"] . "')");
				} else {
					$but = we_html_button::create_button(we_html_button::VIEW, "javascript:we_cmd('object_preview_objectFile','0','" . (isset($this->tmplArr[$this->nr]) ? $this->tmplArr[$this->nr] : "") . "','" . $GLOBALS["we_transaction"] . "')");
				}
				$path = id_to_path(isset($this->tmplArr[$this->nr]) ? $this->tmplArr[$this->nr] : "", TEMPLATES_TABLE, $this->db);
				if($this->isEditable()){
					$tmplSelect = we_html_tools::htmlSelect($this->tmplSelectName . "_" . $this->nr, $this->tmplValsArr, 1, isset($this->tmplArr[$this->nr]) ? $this->tmplArr[$this->nr] : "", false, array("onchange" => $this->getJsSetHot()));
					return '<tr><td></td><td><span class="small"><b>' . g_l('weClass', '[template]') . ':</b></span><br/>' . $tmplSelect . '</td><td style="vertical-align:bottom">' . $but . '</td></tr>';
				}
				return '<tr><td></td><td class="' . $this->css . '"><span class="small"><b>' . g_l('weClass', '[template]') . ':</b></span><br/>' . $path . we_html_element::htmlHidden($this->tmplSelectName . "_" . $this->nr, (isset($this->tmplArr[$this->nr]) ? $this->tmplArr[$this->nr] : "")) . '" /></td><td style="vertical-align:bottom">' . $but . '</td></tr>';
		}
	}

}
