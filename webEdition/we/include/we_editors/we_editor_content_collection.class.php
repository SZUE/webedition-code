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
class we_editor_content_collection extends we_editor_base{

	public function show(){
		//$weSuggest = & weSuggest::getInstance();
		return $this->getPage($this->we_doc->formCollection(), we_html_element::jsScript(JS_DIR . 'collection_init.js', '', ['id' => 'loadVarCollection', 'data-dynamicVars' => setDynamicVar($this->we_doc->getJSDynamic())]));
	}

}
