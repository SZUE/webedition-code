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
function we_tag_ifHtmlMail(){
	if(isset($GLOBALS['WE_HTMLMAIL'])){
		return ((bool) $GLOBALS['WE_HTMLMAIL']);
	}

	if(isset($GLOBALS['we_editmode'])){
		return ($GLOBALS['we_editmode'] ? //editmode always HTML Mode
						true :
						($GLOBALS['we_doc']->InWebEdition ? !(bool) $GLOBALS['we_doc']->getEditorPersistent('newsletterFormat') : true) //newsletterFormat: html=false, text=true
				);
	}
	return true;
}
