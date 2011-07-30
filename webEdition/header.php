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


include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_html_tools.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_classes/permissionhandler/permissionhandler.class.php");
?>
		<div style="position:absolute;top:0;left:0;right:0;bottom:0;border:0;">
			<div style="position:absolute;top:0;bottom:0;left:0;right:60px;"><?php
				include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/headermenu.php");?>
       </div>
			<iframe src="/webEdition/headermenu.php" style="border:0;width:100%;overflow: hidden;" name="header_menu"></iframe>
<?php
if(defined("MESSAGING_SYSTEM") && (!isset($_REQUEST["SEEM_edit_include"]) || !$_REQUEST["SEEM_edit_include"] )) { ?>
       <div style="position:absolute;top:0;bottom:0;right:0;width:60px;">
				<iframe src="<?php print WE_MESSAGING_MODULE_PATH; ?>header_msg.php" style="border:0;width:100%;overflow: hidden;" name="header_msg"></iframe>
			</div>
<?php } ?>
     </div>
