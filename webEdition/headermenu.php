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

include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_html_tools.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/permissionhandler/permissionhandler.class.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/java_menu/weJavaMenu.inc.php");

?>
<link rel="stylesheet" type="text/css" href="<?php print WEBEDITION_DIR; ?>css/menu/pro_drop_1.css" />
<!-- IE 6 -->
<?php $_BROWSER = new we_browserDetect();
if($_BROWSER->getBrowser()== 'ie' && $_BROWSER->getBrowserVersion()<7){?>
<script src="<?php print WEBEDITION_DIR; ?>css/menu/stuHover.js" type="text/javascript"></script>
<?php }?>
<?php

//	width of java-/XUL-Menu
$_menu_width = 360;
$port = defined("HTTP_PORT") ? HTTP_PORT : "";

// all available elements
$jmenu = null;
$navigationButtons = array();

if ( !isset($_REQUEST["SEEM_edit_include"]) ) { // there is only a menu when not in seem_edit_include!

	if( // menu for normalmode
		isset($_SESSION["we_mode"]) && $_SESSION["we_mode"] == "normal" ){

		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/java_menu/we_menu.inc.php");
		ksort ($we_menu);
	    $jmenu = new weJavaMenu($we_menu,SERVER_NAME,"top.load",getServerProtocol(),$port,$_menu_width,30);


	} else { // menu for seemode

		if(permissionhandler::isUserAllowedForAction("header", "with_java")){

			include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/java_menu/we_menu_seem.inc.php");
	    	ksort ($we_menu);
	    	$jmenu = new weJavaMenu($we_menu,SERVER_NAME,"top.load",getServerProtocol(),$port,$_menu_width,30);

		} else {
			//  no menu in this case !
			$navigationButtons[] = array(
				"onclick" => "top.we_cmd('dologout');",
				"imagepath" => "/navigation/close.gif",
				"text" => g_l('javaMenu_global',"[close]")
			);
		}
	}
	$navigationButtons = array_merge($navigationButtons, array(
			array("onclick" => "top.we_cmd('start_multi_editor');", "imagepath" => "/navigation/home.gif", "text" => g_l('javaMenu_global',"[home]")),
			array("onclick" => "top.weNavigationHistory.navigateReload();", "imagepath" => "/navigation/reload.gif", "text" => g_l('javaMenu_global',"[reload]")),
			array("onclick" => "top.weNavigationHistory.navigateBack();", "imagepath" => "/navigation/back.gif", "text" => g_l('javaMenu_global',"[back]")),
			array("onclick" => "top.weNavigationHistory.navigateNext();", "imagepath" => "/navigation/next.gif", "text" => g_l('javaMenu_global',"[next]")),

		)
	);
}

?>
		<script type="text/javascript" src="<?php print JS_DIR; ?>images.js"></script>
		<script src="<?php print JS_DIR; ?>weSidebar.php" type="text/javascript"></script>
		<script type="text/javascript">
			// initialize siebar in webedition.php
			top.weSidebar = weSidebar;

			preload("busy_icon","<?php print IMAGE_DIR; ?>logo-busy.gif");
			preload("empty_icon","<?php print IMAGE_DIR; ?>pixel.gif");
			function toggleBusy(foo){
				if(!document.images["busy"]){
					setTimeout("toggleBusy("+foo+")",200);
				}else{
					changeImage(null,"busy",(foo ? "busy_icon" : "empty_icon"));
				}
			}

		</script>
	</head>
	<body>
		<div style="position:absolute;top:0;left:0;right:0;bottom:0;border:0;background-image: url(<?php print IMAGE_DIR ?>java_menu/background.gif); background-repeat: repeat-x;">
			<div style="position:relative;border:0;float:left;" >
<?php
if ($jmenu) {
	print $jmenu->getCode(false);
}?>
			</div>
			<div style="position:relative;bottom:0;border:0;padding-left: 10px;float:left;" >
<?php

$amount = sizeof($navigationButtons);
if($amount) {

	for ($i=0; $i<$amount; $i++) {
		print "
			<div style=\"float:left;margin-top:5px;\" class=\"navigation_normal\" onclick=\"{$navigationButtons[$i]["onclick"]}\" onmouseover=\"this.className='navigation_hover'\" onmouseout=\"this.className='navigation_normal'\"><img border=\"0\" hspace=\"2\" src=\"" . IMAGE_DIR . "{$navigationButtons[$i]["imagepath"]}\" width=\"17\" height=\"18\" alt=\"{$navigationButtons[$i]["text"]}\" title=\"{$navigationButtons[$i]["text"]}\"></div>
		";
	}
}
?></div>
<div style="position:absolute;top:0;bottom:0;right:10px;border:0;" >


				<?php
					include_once( $_SERVER['DOCUMENT_ROOT'] . "/webEdition/we/include/jsMessageConsole/messageConsole.inc.php" );
					print createMessageConsole("mainWindow");
				?>
				<img src="<?php print IMAGE_DIR ?>pixel.gif" alt="" name="busy" width="20" height="19">
				<img src="<?php print IMAGE_DIR ?>pixel.gif" alt="" width="10" height="19">
				<img src="<?php print IMAGE_DIR ?>webedition.gif" alt="" width="78" height="25">
				<img src="<?php print IMAGE_DIR ?>pixel.gif" alt="" width="5" height="19">
			</div>
		</div>
</body>
</html>