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
function we_parse_tag_orderitem($attribs, $content){
	return '<?php global $lv;
		if(' . we_tag_tagParser::printTag('orderitem', $attribs) . '){?>' . $content . '<?php }
		we_post_tag_listview(); ?>';
}

function we_tag_orderitem($attribs){

	if(!defined('WE_SHOP_MODULE_PATH')){
		echo modulFehltError('Shop', __FUNCTION__);
		return false;
	}

	$condition = weTag_getAttribute("condition", $attribs, 0);
	$we_orderitemid = weTag_getAttribute("id", $attribs, 0);

	$hidedirindex = weTag_getAttribute("hidedirindex", $attribs, TAGLINKS_DIRECTORYINDEX_HIDE, true);

	$condition = ($condition ? $condition . ' AND ' : '') . "IntID = " . $we_orderitemid;

	if(!isset($GLOBALS["we_lv_array"])){
		$GLOBALS["we_lv_array"] = array();
	}

	$we_orderitemid = $we_orderitemid ? : we_base_request::_(we_base_request::INT, "we_orderitemid", 0);


	$GLOBALS["lv"] = new we_shop_orderitemtag(intval($we_orderitemid), $condition, $hidedirindex);
	if(is_array($GLOBALS["we_lv_array"])){
		$GLOBALS["we_lv_array"][] = clone($GLOBALS["lv"]);
	}
	return $GLOBALS["lv"]->avail;
}
