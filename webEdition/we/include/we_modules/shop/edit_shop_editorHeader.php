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
if (isset($_REQUEST["home"]) && $_REQUEST["home"]) {
	print '<body bgcolor="#F0EFF0"></body></html>';
	exit;
}

we_html_tools::protect();

we_html_tools::htmlTop();

print STYLESHEET;

$bid = isset($_REQUEST["bid"]) ? intval($_REQUEST["bid"]) : 0;

list($cid, $cdat) = getHash('SELECT IntCustomerID,DATE_FORMAT(DateOrder,"' . g_l('date', '[format][mysqlDate]') . '") FROM ' . SHOP_TABLE . ' WHERE IntOrderID=' . intval($bid), $DB_WE);
$order = getHash('SELECT IntOrderID,DATE_FORMAT(DateOrder,"' . g_l('date', '[format][mysqlDate]') . '") as orddate FROM ' . SHOP_TABLE . ' GROUP BY IntOrderID ORDER BY IntID DESC LIMIT 1', $DB_WE);
$headline = (empty($order) ? '' : sprintf(g_l('modules_shop', '[lastOrder]'), $order["IntOrderID"], $order["orddate"]));
$textPost = ($bid ? sprintf(g_l('modules_shop', '[orderNo]'), $_REQUEST['bid'], $cdat) : '');


$we_tabs = new we_tabs();

if (isset($_REQUEST["mid"]) && $_REQUEST["mid"] && $_REQUEST["mid"] != '00') {

	$we_tabs->addTab(new we_tab('#', g_l('tabs', "[module][overview]"), 'TAB_ACTIVE', 0));
} else {
	$we_tabs->addTab(new we_tab('#', g_l('tabs', "[module][orderdata]"), "TAB_ACTIVE", "setTab(0);"));
	$we_tabs->addTab(new we_tab("#", g_l('tabs', "[module][orderlist]"), "TAB_NORMAL", "setTab(1);"));
}

$textPre = isset($_REQUEST['bid']) && $_REQUEST['bid'] > 0 ? g_l('modules_shop', '[orderList][order]') : g_l('modules_shop', '[order_view]');
$textPost = isset($_REQUEST['mid']) && $_REQUEST['mid'] > 0 ? (strlen($_REQUEST['mid']) > 5 ? g_l('modules_shop', '[month][' . substr($_REQUEST['mid'], 0, -5) . ']') . " " . substr($_REQUEST['mid'], -5, 4) : substr($_REQUEST['mid'], 1)) : $textPost;
$we_tabs->onResize();
$tab_head = $we_tabs->getHeader();

$tab_body = '<div id="main" >' . we_html_tools::getPixel(100, 3) . '<div style="margin:0px;padding-left:10px;" id="headrow"><nobr><b>' . str_replace(" ", "&nbsp;", $textPre) . ':&nbsp;</b><span id="h_path" class="header_small"><b id="titlePath">' . str_replace(" ", "&nbsp;", $textPost) . '</b></span></nobr></div>' . we_html_tools::getPixel(100, 3) .
				$we_tabs->getHTML() .
				'</div>';
?>
<script type="text/javascript">
	<!--
	function setTab(tab) {
		switch (tab) {
			case 0:
				parent.edbody.document.location = 'edit_shop_properties.php?bid=<?php print $bid; ?>';
				break;
			case 1:
				parent.edbody.document.location = 'edit_shop_orderlist.php?cid=<?php print $cid; ?>';
				break;
		}
	}

	top.content.hloaded = 1;
	//-->
</script>
<?php
print $tab_head;
?>
<body bgcolor="white" background="<?php print IMAGE_DIR; ?>backgrounds/header_with_black_line.gif" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" onLoad="setFrameSize()" onResize="setFrameSize()">
	<?php
	print $tab_body;
	?>

</body>
</html>