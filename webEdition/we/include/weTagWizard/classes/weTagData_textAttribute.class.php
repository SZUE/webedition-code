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
class weTagData_textAttribute extends weTagDataAttribute{

	/**
	 * @return string
	 */
	function getCodeForTagWizard(){

		return '<table class="attribute"><tr>
						<td class="attributeName">' . $this->getLabelCodeForTagWizard() . '</td>
						<td class="attributeField">' .
				we_html_element::htmlInput(array(
					'name' => $this->Name,
					'id' => $this->getIdName(),
					'value' => $this->Value,
					'class' => 'wetextinput'
				)) .
				'</td>
					</tr></table>';
	}

}
