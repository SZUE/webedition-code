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
function we_tag_url($attribs){
	if(($foo = attributFehltError($attribs, 'id', __FUNCTION__))){
		return $foo;
	}
	static $urls = array();
	$type = weTag_getAttribute('type', $attribs, 'document', we_base_request::STRING);
	$id = weTag_getAttribute('id', $attribs, 0, we_base_request::STRING);
	$triggerid = weTag_getAttribute('triggerid', $attribs, 0, we_base_request::INT);
	$hidedirindex = weTag_getAttribute('hidedirindex', $attribs, TAGLINKS_DIRECTORYINDEX_HIDE, we_base_request::BOOL);
	$objectseourls = weTag_getAttribute('objectseourls', $attribs, TAGLINKS_OBJECTSEOURLS, we_base_request::BOOL);
	if(is_numeric($id) && (isset($urls[$type . $id]))){ // do only work you have never done before
		return $urls[$type . $id];
	}
	if($id != 'self' && $id != 'top' && intval($id) == 0){
		$url = '/';
	} else {
		$urlNotSet = true;
		if(($id === 'self' || $id === 'top') && $type === 'document'){
			$doc = we_getDocForTag($id, true); // check if we should use the top document or the  included document
			$testid = $doc->ID;
			if($id === 'top'){//check for object
				if($GLOBALS['WE_MAIN_DOC'] instanceof we_objectFile){//ein object
					$triggerid = ($triggerid ? : $GLOBALS['WE_MAIN_ID']);

					$path_parts = pathinfo(id_to_path($triggerid));
					if($objectseourls && $GLOBALS['WE_MAIN_DOC']->Url && show_SeoLinks()){
						$url = ($path_parts['dirname'] != '/' ? $path_parts['dirname'] : '') . '/' .
							($hidedirindex && seoIndexHide($path_parts['basename']) ?
								'' : $path_parts['filename'] . '/') .
							$GLOBALS['WE_MAIN_DOC']->Url;
					} else {
						//FIXME: check if $GLOBALS['we_obj'] can be used instead of $GLOBALS['WE_MAIN_DOC']->OF_ID
						$url = ($hidedirindex && seoIndexHide($path_parts['basename']) ?
								($path_parts['dirname'] != '/' ? $path_parts['dirname'] : '') . '/?we_objectID=' . $GLOBALS['WE_MAIN_DOC']->OF_ID :
								$GLOBALS['WE_MAIN_DOC']->Path . '?we_objectID=' . $GLOBALS['WE_MAIN_DOC']->OF_ID);
					}
					$urlNotSet = false;
				}
			}
		} else {
			$testid = $id;
		}
		if($urlNotSet){
			if($type === 'document'){
				$row = getHash('SELECT Path,IsFolder,IsDynamic FROM ' . FILE_TABLE . ' WHERE ID=' . intval($testid));
				$url = isset($row['Path']) ? ($row['Path'] . ($row['IsFolder'] ? '/' : '')) : '';
				$path_parts = pathinfo($url);
				if($hidedirindex && TAGLINKS_DIRECTORYINDEX_HIDE && seoIndexHide($path_parts['basename'])){
					$url = ($path_parts['dirname'] != '/' ? $path_parts['dirname'] : '') . '/';
				}
			} else {
				$row = getHash('SELECT ID,Url,TriggerID FROM ' . OBJECT_FILES_TABLE . ' WHERE ID=' . intval($testid));
				if(!$row){
					$urls[$type . $id] = '';
					return '';
				}
				if(!$triggerid){
					$triggerid = ($row['TriggerID'] ? : $GLOBALS['WE_MAIN_DOC']->ID);
				}
				$path_parts = pathinfo(id_to_path($triggerid));
				if($objectseourls && $row['Url'] != '' && show_SeoLinks()){
					$url = ($path_parts['dirname'] != '/' ? $path_parts['dirname'] : '') . '/' .
						($hidedirindex && seoIndexHide($path_parts['basename']) ?
							'' : $path_parts['filename'] . '/' ) .
						$row['Url'];
				} else {
					$url = ($hidedirindex && seoIndexHide($path_parts['basename']) ?
							($path_parts['dirname'] != '/' ? $path_parts['dirname'] : '') . '/?we_objectID=' . $row['ID'] :
							id_to_path($triggerid) . '?we_objectID=' . $row['ID']
						);
				}
			}
		}
	}
	$urls[$type . $id] = $url;
	return $url;
}
