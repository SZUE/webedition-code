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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

we_html_tools::protect();
if(!preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction'])){
	exit();
}

$messaging = new we_messaging_messaging($_SESSION['weS']['we_data'][$_REQUEST['we_transaction']]);
$messaging->set_login_data($_SESSION["user"]["ID"], $_SESSION["user"]["Username"]);
$messaging->init($_SESSION['weS']['we_data'][$_REQUEST['we_transaction']]);
echo we_html_tools::getHtmlTop(g_l('modules_messaging', '[search_advanced]')) .
 STYLESHEET;
?>
<script type="text/javascript">
<!--
<?php
if(isset($_REQUEST['save']) && $_REQUEST['save'] == 1){
	$messaging->set_search_settings($_REQUEST['search_fields'], (isset($_REQUEST['search_folders']) && is_array($_REQUEST['search_folders'])) ? $_REQUEST['search_folders'] : array());
	$messaging->saveInSession($_SESSION['weS']['we_data'][$_REQUEST['we_transaction']]);
	?>
		self.close();
	//-->
	</script>
	</head>
	<body>
	</body>
	</html>
	<?php
} else {
	?>
	function save_settings() {
	document.search_adv.submit();
	}
	//-->
	</script>
	</head>

	<body class="weDialogBody">
		<form action="<?php print WE_MESSAGING_MODULE_DIR; ?>messaging_search_advanced.php" name="search_adv" >
			<input type="hidden" name="we_transaction" value="<?php echo filterXss($_REQUEST['we_transaction']) ?>" />
			<input type="hidden" name="save" value="1" />

			<?php
			$table = '<table cellpadding="10" cellspacing="0" border="0">
<tr>
    <td valign="top" class="defaultgray">' . g_l('modules_messaging', '[to_search_fields]') . '</td>
    <td><select name="search_fields[]" size="3" multiple>
    ' . $messaging->print_select_search_fields() . '
        </select></td>
</tr>
<tr>
    <td valign="top" class="defaultgray">' . g_l('modules_messaging', '[to_search_folders]') . '</td>
    <td><select name="search_folders[]" size="5" multiple>
    ' . $messaging->print_select_search_folders() . '
        </select>
    </td>
</table>';

			$_buttontable = we_html_button::position_yes_no_cancel(we_html_button::create_button("ok", "javascript:save_settings();"), null, we_html_button::create_button("cancel", "javascript:self.close()"));

			print we_html_tools::htmlDialogLayout($table, "", $_buttontable, "90%");
			?>

		</form>
	</body>
	</html>

	<?php
}