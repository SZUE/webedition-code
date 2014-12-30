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
$GLOBALS['show_stylesheet'] = true;
define('NO_SESS', 1); //no need for a session
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
//no protect, since login dialog is shown bad

header('Content-Type: text/css', true);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT', true);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT', true);
header('Cache-Control: max-age=86400, must-revalidate', true);
header('Pragma: ', true);
?>
.weSelect {
border: #AAAAAA solid 1px;
color: black;
box-sizing: border-box;<?php /* NOTE: FF doesn't know this */ ?>
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.wetextinput {
color: black;
border: #AAAAAA solid 1px;
box-sizing: border-box;
height: 20px;
<?php echo (we_base_browserDetect::isIE()) ? '' : 'line-height: 18px;'; ?>
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.wetextinput[disabled] {
background-color: #EEEEEE;
}

.weMarkInputError,
input:invalid {
background-color: #ff8888 ! important;
}

input.wetextinput:focus{
border: #888888 solid 1px;
background-color: #dce6f2;
}


.wetextarea {
color: black;
border: #AAAAAA solid 1px;
height: 80px;
<?php echo (we_base_browserDetect::isIE()) ? '' : 'line-height: 18px;'; ?>
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

textarea.wetextarea:focus {
color: black;
border: #888888 solid 1px;
background-color: #dce6f2;
height: 80px;
}

.multichooser {
background-color:white;
border: 1px grey solid;
}

body {
letter-spacing: normal ! important;
}

body.aqua {
background-image:url('<?php echo IMAGE_DIR ?>backgrounds/aquaBackground.gif');
background-color:#bfbfbf;
background-repeat:repeat;
margin:0px 0px 0px 0px;
}

body.grey{
background-color:#bfbfbf;
background-repeat:repeat;
margin:0px 0px 0px 0px;
}

.defaultfont {
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.defaultfont a {
color: black;
}

.defaultfont a:visited {
color: black;
}

.defaultfont a:active {
color: #006DB8;
}

.objectDescription {
padding: 4px 0 4px 0;
max-width:50em;
}

.npdefaultfont {
color: red;
font-size: <?php echo (we_base_browserDetect::isMAC() ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.changeddefaultfont {
color: blue;
font-size: <?php echo (we_base_browserDetect::isMAC() ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.changed,
a.changed{ /*elements which are saved & published*/
color: #3366CC;
cursor: pointer;
}

.notpublished,
a.notpublished {
color: red;
cursor: pointer;
}

.changed a,
.notpublished a{
text-decoration:none;
}

.npdefaultfont a,
.npdefaultfont a:visited {
color: red;
}

.npdefaultfont a:active {
color: #006DB8;
}

.shopContentfont {
vertical-align: top;
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:center;
}

.shopContentfontSmall {
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 9 : ((we_base_browserDetect::isUNIX()) ? 11 : 10); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:center;
}

.shopContentfontAlert {
color: #800000;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:left;
}

.shopContentfontGreySmall {
color: #666666;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 9 : ((we_base_browserDetect::isUNIX()) ? 11 : 10); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:left;
}

.shopContentfontR {
vertical-align: top;
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:right;
}

.shopContentfontGR {
color: #666666;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:right;
}

.npshopContentfontR {
color: red;
}

.npshopContentfont {
color: red;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:center;
}

.npshopContentfont a,
.npshopContentfont a:visited {
color: red;
}

.npshopContentfont a:active {
color: #006DB8;
}

.pshopContentfontR {
color: green;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:right;
}

.pshopContentfont,
.pdefaultfont{
color: green;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
text-align:center;
}

.pshopContentfont a,
.pshopContentfont a:visited,
.pdefaultfont a,
.pdefaultfont a:visited {
color: green;
}

.pshopContentfont a:active,
.pdefaultfont a:active {
color: #006DB8;
}

.middlefont {
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;

}

.middlefont a,
.middlefont a:visited {
color: black;
}

.middlefont a:active {
color: #006DB8;
}

.middlefontgray {
color: #666666;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.middlefontgray a,
.middlefontgray a:visited {
color: #666666;
}

.middlefontgray a:active {
color: #006DB8;
}

.middlefontred {
color: red;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 10 : ((we_base_browserDetect::isUNIX()) ? 12 : 11); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.middlefontred a,
.middlefontred a:visited {
color: red;
}

.middlefontred a:active {
color: #006DB8;
}

.defaultgray {
color: #666666;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.defaultgray a,
.defaultgray a:visited {
color: #666666;
}

.defaultgray a:active {
color: #006DB8;
}

.small {
color: black;
font-size: <?php echo (we_base_browserDetect::isGecko() && we_base_browserDetect::isWin() ? 9 : ((we_base_browserDetect::isUNIX()) ? 11 : 9)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.header_small {
color: #006699;
font-size: <?php echo (we_base_browserDetect::isGecko() && we_base_browserDetect::isWin() ? 11 : ((we_base_browserDetect::isUNIX()) ? 10 : 10)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}


.header_shop {
color: #006699;
font-size: 11px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
background-image: url(<?php echo WE_MODULES_DIR; ?>shop/images/shopInfast.gif);
background-position: bottom left;
background-repeat: no-repeat;
}

.shop_th {
color: #000000;
font-size: 12px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
padding-bottom:5px;
font-weight:bold;
}

.shop_fontView {
color: #666666;
font-size: 12px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.big {
color: black;
text-align: left;
font-size: <?php echo (we_base_browserDetect::isGecko() && we_base_browserDetect::isWin() ? 14 : ((we_base_browserDetect::isUNIX()) ? 15 : 13)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.header {
color: black;
font-weight: bold;
font-size: 20px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.tree {
color: black;
font-size: <?php echo (we_base_browserDetect::isGecko() && we_base_browserDetect::isWin() ? 10 : ((we_base_browserDetect::isUNIX()) ? 11 : 9)); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
border-width: 0px;
border-collapse: collapse;
padding: 0px;
}

.tree a {
text-decoration:none;
}

.tree img {
width: 16px;
height: 18px;
vertical-align: middle;
}

img.treeKreuz{
	width:19px;
}

.selector {
color: black;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.selector a {
text-decoration:none;
}

.tableHeader {
color: #ffffff;
font-weight: bold;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.tableHeader a,
.tableHeader a:visited {
text-decoration:none;
color: #ffffff;
}

.tableHeader a:active {
color: #ff0000;
}

.todo_hist_hdr {
color: #006DB8;
}

.defaultfontred {
color: #6CBFF9;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
}

.blockWrapper {
overflow: auto !important;
display: block ;
background-color: white;
padding: 0px;
}

.weDefaultStyle{
background: transparent;
background-color: transparent;
border: 0px;
color: #000000;
cursor: default;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: normal;
margin: 0px;
padding:0px;
text-align: left;
text-decoration: none;
}

.navigation_normal{
padding: 1px;
margin: 0px;
}

.navigation_hover{
margin: 0px;
border-bottom:	#000000 solid 1px;
border-left:	#CCCCCC solid 1px;
border-right:	#000000 solid 1px;
border-top:		#CCCCCC solid 1px;
cursor:pointer;
}

optgroup{
font-weight: bold;
font-style: normal;
}

optgroup.lvl1{
color: darkblue;
}

optgroup.lvl2{
margin-left: 10px;
}


/*	Following: styles for accessibility	*/
.weHide{
display:none;
}

.weDialogHeadline {
color: #000000;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: bold;
}


.weMultiIconBoxHeadline {
color: #6078A2;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: bold;
}

.weMultiIconBoxHeadlineThin {
color: #6078A2;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: normal;
}

.weMultiIconBoxHeadline a {
color: #6078A2;
}

.weDialogBody {
margin: 0;
padding: 0;
background-color: #EDEDED;
}

.weEditorBody {
margin: 0;
padding: 10px 0px;
background-color: #EDEDED;
}

.weDialogButtonsBody {
margin: 0;
padding: 10px 10px;
background-color: #EDEDED;
background-image: url(<?php echo IMAGE_DIR; ?>edit/editfooterback.gif);
}

.weTreeHeader {
background-color: #F0EFF0;
margin:0px;
padding: 10px 10px;
border-bottom: 1px solid black;
height:129px;
}


.weTreeHeaderMove {
background-color: #F0EFF0;
margin:0px;
padding: 10px 10px;
border-bottom: 1px solid black;
height:139px;
}

.weObjectPreviewHeadline {
color: #6078A2;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 11 : ((we_base_browserDetect::isUNIX()) ? 13 : 12); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: bold;
margin-bottom:3px;
}

.weSidebarBody {
background	: #ffffff url(<?php echo IMAGE_DIR; ?>backgrounds/sidebarBackground.gif) no-repeat fixed bottom right;
margin		: 5px;
padding		: 0px;
}

.weDocListSearchHeadline {
color: #6078A2;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 13 : ((we_base_browserDetect::isUNIX()) ? 15 : 14); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: bold;
margin-top:6px;
}

.weDocListSearchHeadlineDivs {
color: #6078A2;
font-size: <?php echo (we_base_browserDetect::isMAC()) ? 13 : ((we_base_browserDetect::isUNIX()) ? 15 : 14); ?>px;
font-family: <?php echo g_l('css', '[font_family]'); ?>;
font-weight: bold;
}

div.le_widget{
margin-top:1ex;
margin-right:1em;
}

div.le_widget:first-child{
margin-top:0px;
}