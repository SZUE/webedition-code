<?php
/**
 * webEdition CMS
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


include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/"."we.inc.php");
protect();
$foo = getHash("SELECT * FROM " . USER_TABLE . " WHERE ID=".abs($we_user_locked)."",$DB_WE);
?>
<script language="JavaScript" type="text/javascript"><!--
<?php print we_message_reporting::getShowMessageCall(sprintf( g_l('alert',"[file_locked]"), $foo["Vorname"], $foo["Nachname"] ), WE_MESSAGE_NOTICE); ?>
//-->
</script>