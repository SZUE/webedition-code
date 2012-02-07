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
function we_parse_tag_order($attribs, $content) {
	return '<?php global $lv;
		if('.we_tag_tagParser::printTag('order', $attribs).'){?>' . $content . '<?php }
		we_post_tag_listview(); ?>';
}

function we_tag_order($attribs, $content) {
	if (!defined('WE_SHOP_MODULE_DIR')) {
		print modulFehltError('Shop', '"order"');
		return false;
	}

	$condition = weTag_getAttribute("condition", $attribs, 0);
	$we_orderid = weTag_getAttribute("id", $attribs, 0);

	$hidedirindex = weTag_getAttribute("hidedirindex", $attribs, (defined('TAGLINKS_DIRECTORYINDEX_HIDE') && TAGLINKS_DIRECTORYINDEX_HIDE), true);

	if (!isset($GLOBALS["we_lv_array"])) {
		$GLOBALS["we_lv_array"] = array();
	}


	$we_orderid = $we_orderid ? $we_orderid : (isset($_REQUEST["we_orderid"]) ? $_REQUEST["we_orderid"] : 0);


	$GLOBALS["lv"] = new we_shop_ordertag($we_orderid, $condition, $hidedirindex);
	$lv = clone($GLOBALS["lv"]); // for backwards compatibility
	if (is_array($GLOBALS["we_lv_array"])){
		array_push($GLOBALS["we_lv_array"], clone($GLOBALS["lv"]));
	}
	return $GLOBALS["lv"]->avail;
}