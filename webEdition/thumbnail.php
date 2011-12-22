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

include_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_inc_min.inc.php');

//we_html_tools::protect();

if(!isset($_REQUEST['id']) || $_REQUEST['id']==''||
				!isset($_REQUEST['path']) || $_REQUEST['path']==''||
				!isset($_REQUEST['size']) || $_REQUEST['size']==''||
				!isset($_REQUEST['extension']) || $_REQUEST['extension']=='') {
	exit();
}

$imageId = $_REQUEST['id'];
$imagePath = $_REQUEST['path'];
$imageSizeW = $_REQUEST['size'];
if (isset($_REQUEST['size2'])){
	$imageSizeH =$_REQUEST['size2'];
} else {
	$imageSizeH =$imageSizeW ;
}

$whiteList = array();
$ct=new we_base_ContentTypes();
$exts = $ct->getExtension('image/*');
if(!empty($exts)){
	$whiteList = makeArrayFromCSV($exts);
}

if (!in_array(strtolower($_REQUEST['extension']), $whiteList)) {
	exit();
}

$imageExt = substr($_REQUEST['extension'], 1, strlen($_REQUEST['extension']));

$thumbpath = we_image_edit::createPreviewThumb($imagePath, $imageId, $imageSizeW, $imageSizeH, substr($_REQUEST['extension'], 1));
header("Content-type: image/" . $imageExt );
readfile($_SERVER['DOCUMENT_ROOT'] . $thumbpath);
