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
class weTagData_multiSelectorAttribute extends weTagDataAttribute{

	/**
	 * @var string
	 */
	var $Table;

	/**
	 * @var string
	 */
	var $Selectable;

	/**
	 * @var string
	 */
	var $TextName = 'text';

	/**
	 * @param string $name
	 * @param string $table
	 * @param string $selectable
	 * @param string $textName
	 * @param boolean $required
	 */
	function __construct($name, $table, $selectable, $textName = 'path', $required = false, $module = '', $description = '', $deprecated = false){

		$this->Table = $table;
		$this->Selectable = $selectable;
		$this->TextName = $textName;

		parent::__construct($name, $required, $module, $description, $deprecated);
	}

	/**
	 * @return string
	 */
	function getCodeForTagWizard(){
		$we_cmd = 'we_selector_file';
		switch($this->Table){
			case USER_TABLE :
				$we_cmd = 'we_users_selector';
				break;
			case CATEGORY_TABLE :
				$we_cmd = 'we_selector_category';
				break;
		}

		$button = we_html_button::create_button('select', "javascript:we_cmd('" . $we_cmd . "', 0, '" . $this->Table . "', '', '', 'fillIDs();var foo2=\\'\\'; if(all" . $this->TextName . "s.length>=2){foo2=all" . $this->TextName . "s.substring(1,all" . $this->TextName . "s.length-1)};var foo=opener.document.getElementById(\\'" . $this->getIdName() . "\\'); if(foo.value){foo.value += \\',\\'+ foo2;}else{foo.value = foo2;};')");

		return '
<table class="attribute">
<tr>
	<td class="attributeName">' . $this->getLabelCodeForTagWizard() . '</td>
	<td class="attributeField">' . we_html_element::htmlTextArea(array('name' => $this->Name, 'id' => $this->getIdName(), 'class' => 'wetextinput wetextarea')) . '</td>
	<td class="attributeButton">' . $button . '</td>
</tr>
</table>';
	}

}
