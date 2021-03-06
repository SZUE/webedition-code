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
function we_parse_tag_answers($attribs, $content){
	if(!defined('VOTING_TABLE')){
		return modulFehltError('Voting', __FUNCTION__);
	}
	return '<?php while(' . we_tag_tagParser::printTag('answers', $attribs) . '){?>' . $content . '<?php }?>';
}

function we_tag_answers(){
	if(isset($GLOBALS["_we_voting"]) && $GLOBALS["_we_voting"]->getNext()){
		return true;
	}

	if(isset($GLOBALS['_we_voting']))
		$GLOBALS['_we_voting']->resetSets();
	return false;
}
