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
function we_parse_tag_customer($attribs, $content, array $arr){
	$name = weTag_getParserAttribute('name', $arr);
	if($name && strpos($name, ' ') !== false){
		return parseError(sprintf(g_l('parser', '[name_with_space]'), 'customer'));
	}

	return '<?php global $lv;
		if(' . we_tag_tagParser::printTag('customer', $attribs) . '){?>' . $content . '<?php }
		we_post_tag_listview(); ?>';
}

function we_tag_customer($attribs){
	if(!defined('WE_CUSTOMER_MODULE_PATH')){
		echo modulFehltError('Customer', __FUNCTION__);
		return false;
	}

	$condition = weTag_getAttribute('condition', $attribs, 0);
	$we_cid = weTag_getAttribute('id', $attribs, 0);
	$name = weTag_getAttribute('name', $attribs);
	$_showName = weTag_getAttribute('_name_orig', $attribs);
	$size = weTag_getAttribute('size', $attribs, 30);
	$hidedirindex = weTag_getAttribute('hidedirindex', $attribs, TAGLINKS_DIRECTORYINDEX_HIDE, true);

	if(!isset($GLOBALS['we_lv_array'])){
		$GLOBALS['we_lv_array'] = array();
	}

	if($name){
		if(strpos($name, ' ') !== false){
			echo parseError(sprintf(g_l('parser', '[name_with_space]'), 'object'));
			return false;
		}

		$we_doc = $GLOBALS['we_doc'];
		$we_cid = intval(($we_doc->getElement($name, 'bdid') ?
				$we_doc->getElement($name, 'bdid') :
				($we_doc->getElement($name) ?
					$we_doc->getElement($name) :
					$we_cid)
		));

		$we_cid = $we_cid ? : we_base_request::_(we_base_request::INT, 'we_cid', 0);
		$path = f('SELECT Path FROM ' . CUSTOMER_TABLE . ' WHERE ID=' . $we_cid);
		$textname = 'we_' . $GLOBALS['we_doc']->Name . '_customer[' . $name . '_path]';
		$idname = 'we_' . $GLOBALS['we_doc']->Name . '_customer[' . $name . '#bdid]';
		$table = CUSTOMER_TABLE;
		$delbutton = we_html_button::create_button('image:btn_function_trash', "javascript:document.forms[0].elements['" . $idname . "'].value=0;document.forms[0].elements['" . $textname . "'].value='';_EditorFrame.setEditorIsHot(false);we_cmd('reload_editpage');");
		$button = we_html_button::create_button('select', "javascript:we_cmd('openSelector',document.forms[0].elements['" . $idname . "'].value,'" . $table . "','document.forms[\'we_form\'].elements[\'" . $idname . "\'].value','document.forms[\'we_form\'].elements[\'" . $textname . "\'].value','opener.we_cmd(\'reload_editpage\');opener._EditorFrame.setEditorIsHot(true);','',0,'',1)");

		if($GLOBALS['we_editmode']){
			?>
			<table style="border-style:none;" class="weEditTable spacing0 padding0">
				<tr>
					<td style="padding:0 6px;"><b><?php echo $_showName; ?></b></td>
					<td><?php echo we_html_tools::hidden($idname, $we_cid) ?></td>
					<td><?php echo we_html_tools::htmlTextInput($textname, $size, $path, '', ' readonly', 'text', 0, 0); ?></td>
					<td><?php we_html_tools::getPixel(6, 4); ?></td>
					<td><?php echo $button; ?></td>
					<td><?php we_html_tools::getPixel(6, 4); ?></td>
					<td><?php echo $delbutton; ?></td>
				</tr>
			</table><?php
		}
	} else {

		$we_cid = $we_cid ? : we_base_request::_(we_base_request::INT, 'we_cid', 0);
	}

	$GLOBALS['lv'] = new we_customer_customertag($we_cid, $condition, $hidedirindex);
	if(is_array($GLOBALS['we_lv_array'])){
		$GLOBALS['we_lv_array'][] = clone($GLOBALS['lv']);
	}
	if($GLOBALS['lv']->avail){
//implement seem
	}
	return $GLOBALS['lv']->avail;
}
