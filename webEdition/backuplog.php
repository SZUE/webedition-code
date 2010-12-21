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

		require_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we.inc.php");

		protect();
		
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/"."we.inc.php");
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/"."we_html_tools.inc.php");
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/html/we_button.inc.php");
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_classes/html/we_multibox.inc.php");
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/we/include/we_language/".$GLOBALS["WE_LANGUAGE"]."/backup.inc.php");
		include_once($_SERVER["DOCUMENT_ROOT"]."/webEdition/lib/we/core/autoload.php");
		

		$_parts = array();
if (we_hasPerm("BACKUPLOG")){
		$_parts[] = array(
					'headline'=> $l_backup["view_log"],
					'html'=> '',
					'space'=>10
					);
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].BACKUP_DIR.'data/lastlog.php') ){
			$_parts[] = array(
					'headline'=> '',
					'html'=> '<p>'.$l_backup["view_log_not_found"].'</p>',
					'space'=>10
					);
		
		} else {
			$log = file_get_contents ($_SERVER['DOCUMENT_ROOT'].BACKUP_DIR.'data/lastlog.php');
			$_parts[] = array(
					'headline'=> '',
					'html'=> '<pre>'.$log.'</pre>',
					'space'=>10
					);
		
		}
} else {
			$_parts[] = array(
					'headline'=> '',
					'html'=> '<p>'.$l_backup["view_log_no_perm"].'</p>',
					'space'=>10
					);

}		
		
		
?>
<html>
<head>
 
<title><?php print $l_backup["view_log"];?></title>
<script type="text/javascript" src="<?php print JS_DIR; ?>attachKeyListener.js"></script>
<script type="text/javascript" src="<?php print JS_DIR; ?>keyListener.js"></script>
<script type="text/javascript">
	function closeOnEscape() {
		return true;
	}
	

</script>

<?php
		print STYLESHEET;
?>

</head>

<body class="weDialogBody" style="overflow:hidden;" onLoad="self.focus();">
<div id="info" style="display: block;">
<?php	

$we_button = new we_button();

$buttons = $we_button->position_yes_no_cancel(
			$we_button->create_button("close", "javascript:self.close()"),
			'',
			''
		);
	
		print we_multiIconBox::getJS();
		print we_multiIconBox::getHTML('',500, $_parts,30,$buttons,-1,'','',false, "", "", 620, "auto");
		
?>
</div>

</body>
</html>