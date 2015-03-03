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
if(!$_SESSION['user']['Username']){
	session_id;
}

we_html_tools::protect(array('BROWSE_SERVER', 'SITE_IMPORT', 'ADMINISTRATOR'));
echo we_html_tools::getHtmlTop('', '', 'frameset');

$docroot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
$cmd1 = we_base_request::_(we_base_request::CMD, 'we_cmd', '', 1);


$filter = we_base_request::_(we_base_request::STRING, 'we_cmd', 'all_Types', 2);
$url = we_base_request::_(we_base_request::URL, 'we_cmd', '', 3);
$currentDir = str_replace('\\', '/', ( $url ?
				($url === '/' ? '' :
						( parse_url($url) === FALSE && is_dir($docroot . $url) ?
								$url :
								dirname($url))) :
				''));
$currentName = ($filter != we_base_ContentTypes::FOLDER ? basename($url) : '');
if(!file_exists($docroot . $currentDir . '/' . $currentName)){
	$currentDir = '';
	$currentName = '';
}
$currentID = $docroot . $currentDir . ($filter == we_base_ContentTypes::FOLDER || $filter === 'filefolder' ? '' : (($currentDir != '') ? '/' : '') . $currentName);

$currentID = str_replace('\\', '/', $currentID);

$rootDir = we_base_request::_(we_base_request::FILE, 'we_cmd', '', 5);
$selectOwn = we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 6);
?>
<script type="text/javascript"><!--
	var rootDir = "<?php echo $rootDir; ?>";
	var currentID = "<?php echo $currentID; ?>";
	var currentDir = "<?php echo str_replace($rootDir, '', $currentDir); ?>";
	var currentName = "<?php echo $currentName; ?>";
	var currentFilter = "<?php echo str_replace(' ', '%20', g_l('contentTypes', '[' . $filter . ']', true) !== false ? g_l('contentTypes', '[' . $filter . ']') : ''); ?>";
	var filter = '<?php echo $filter; ?>';
	var browseServer = <?php echo $cmd1 ? 'false' : 'true'; ?>

	var currentType = "<?php echo ($filter == we_base_ContentTypes::FOLDER) ? we_base_ContentTypes::FOLDER : ''; ?>";
	var sitepath = "<?php echo $docroot; ?>";
	var dirsel = 1;
	var scrollToVal = 0;
	var allentries = new Array();

	function exit_close() {
		if (!browseServer) {
			var foo = (!currentID || (currentID === sitepath) ? "/" : currentID.substring(sitepath.length));

			opener.<?php echo $cmd1? : 'x'; ?> = foo;
			if (!!opener.postSelectorSelect) {
				opener.postSelectorSelect('selectFile');
			}
		}
<?php
if(($cmd4 = we_base_request::_(we_base_request::CMD, 'we_cmd', '', 4))){
	echo $cmd4 . ';';
}
?>
		close();
	}

	self.focus();

	function closeOnEscape() {
		return true;

	}
//-->
</script>
<?php
echo we_html_element::jsScript(JS_DIR . 'keyListener.js') . STYLESHEET;
?>
</head>
<body onload="top.fscmd.selectDir();">
	<?php
	$footerHeight = (we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 2) ? 60 : 90);
	echo we_html_element::htmlIFrame('fsheader', 'we_sselector_header.php?ret=' . ($cmd1 ? 1 : 0) . '&filter=' . $filter . '&currentDir=' . $currentDir, 'position:absolute;top:0px;height:73px;left:0px;right:0px;', '', '', false) .
	we_html_element::htmlIFrame('fsbody', 'about:blank', 'position:absolute;top:73px;bottom:' . $footerHeight . 'px;left:0px;right:0px;', '', '', true) .
	we_html_element::htmlIFrame('fsfooter', 'we_sselector_footer.php?ret=' . ($cmd1 ? 1 : 0) . '&filter=' . $filter . '&currentName=' . $currentName, 'position:absolute;bottom:0px;height:' . $footerHeight . 'px;left:0px;right:0px;', '', '', false) .
	we_html_element::htmlIFrame('fscmd', 'we_sselector_cmd.php?ret=' . ($cmd1 ? 1 : 0) . '&filter=' . $filter . '&currentName=' . $currentName . '&selectOwn=' . $selectOwn, 'display:none;', '', '', false);
	?>
</body>
</html>