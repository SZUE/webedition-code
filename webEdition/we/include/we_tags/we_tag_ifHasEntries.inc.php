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
function we_tag_ifHasEntries(){
	if(isset($GLOBALS['weNavigationItemArray']) && is_array($GLOBALS['weNavigationItemArray'])){
		$element = end($GLOBALS['weNavigationItemArray']);
		if(!empty($element->items)){
			foreach($element->items as $item){
				//FIXME: this causes many new objects. optimize while creating entries
				if($item->isVisible()){
					return true;
				}
			}
		}
	}
	return false;
}
