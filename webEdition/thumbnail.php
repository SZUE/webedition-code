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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();
session_write_close();
//FIXME: send no perms img; but better an invalid picture, than access to unallowed images

$imageId = we_base_request::_(we_base_request::INT, 'id', 0);
$thumbID = we_base_request::_(we_base_request::INT, 'thumbID', 0);

if($thumbID){
	$img = new we_imageDocument();
	$img->initByID($imageId, FILE_TABLE, false);
	$thumbObj = new we_thumbnail();
	$thumbObj->initByThumbID($thumbID, $img->ID, $img->Filename, $img->Path, $img->Extension, $img->getElement('origwidth'), $img->getElement('origheight'), $img->getDocument());
	if(!$thumbObj->exists()){
		$thumbObj->createThumb();
	}
	$file = $thumbObj->getOutputPath(true, false);
	$imageExt = $thumbObj->getOutputformat();
} else {
	$imagePath = we_base_request::_(we_base_request::FILE, 'path', '');
	$imageSizeW = we_base_request::_(we_base_request::INT, 'size', 0, 'width');
	$imageSizeH = we_base_request::_(we_base_request::INT, 'size', $imageSizeW, 'height');
	$extension = we_base_request::_(we_base_request::STRING, 'extension', '');

	if(!($imageId || $imagePath) && !$imageSizeW && !$extension){
		exit();
	}

	$whiteList = we_base_ContentTypes::inst()->getExtension(we_base_ContentTypes::IMAGE);

	if(!in_array(strtolower($extension), $whiteList)){
		exit();
	}

	$imageExt = substr($extension, 1);
	$file = we_base_imageEdit::createPreviewThumb($imagePath, $imageId, $imageSizeW, $imageSizeH, $imageExt);
}
if(file_exists($file) && is_readable($file)){
	$stat = stat($file);
	$etag = md5($imageId . $stat['size'] . $stat['ctime'] . $stat['mtime']);
	header('Etag: "' . $etag . '"');
	header('Expires: -1');
	header('Cache-Control: max-age=60'); //they stay in cache for 60 seconds, before reasking the server for a new version!
	header_remove('Pragma');
	if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
		we_html_tools::setHttpCode(304);
	} else {
		header('Content-type: image/' . $imageExt);
		header('Content-Length: ' . filesize($file));
		readfile($file);
	}
} else {
	we_html_tools::setHttpCode(404);
}
