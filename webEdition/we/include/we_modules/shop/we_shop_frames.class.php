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
class we_shop_frames extends we_modules_frame{
	const TAB_OVERVIEW = 0;
	const TAB_ORDERLIST = 1;
	const TAB_ADMIN1 = 0;
	const TAB_ADMIN2 = 1;
	const TAB_ADMIN3 = 2;

	public function __construct($frameset){
		parent::__construct($frameset);
		$this->module = 'shop';
		$this->treeDefaultWidth = 204;
		$this->hasIconbar = true;
		$this->Tree = new we_tree_shop($this->frameset, "top.content", "top.content", "top.content.cmd");
		$this->View = new we_shop_view($frameset, 'top.content');
	}

	function getHTMLIconbar(){ //TODO: move this to weShopView::getHTMLIconbar();
		$extraHead = we_html_element::jsElement('
function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "openOrder":
			//TODO: check this adress: mit oder ohne tree? Bisher: left
			if(top.content.doClick) {
				top.content.doClick(args[1], args[2], args[3]);//TODO: check this adress
			}
			break;
		default:
			// not needed yet
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}

		');

//	$bid = we_base_request::_(we_base_request::INT, 'bid', 0);
//	$cid = f('SELECT IntCustomerID FROM ' . SHOP_TABLE . ' WHERE IntOrderID=' . $bid, '', $this->db);
		$data = getHash("SELECT IntOrderID,DATE_FORMAT(DateOrder,'" . g_l('date', '[format][mysqlDate]') . "') AS orddate FROM " . SHOP_TABLE . ' GROUP BY IntOrderID ORDER BY IntID DESC LIMIT 1', $this->db);

		$headline = $data ? '<a style="text-decoration: none;" href="javascript:we_cmd(\'openOrder\', ' . $data['IntOrderID'] . ',\'shop\',\'' . SHOP_TABLE . '\');">' . sprintf(g_l('modules_shop', '[lastOrder]'), $data['IntOrderID'], $data['orddate']) . '</a>' : '';

/// config
		$feldnamen = explode('|', f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_pref"', '', $this->db));
		for($i = 0; $i <= 3; $i++){
			$feldnamen[$i] = isset($feldnamen[$i]) ? $feldnamen[$i] : '';
		}
		$fe = explode(',', $feldnamen[3]);

		$classid = $fe[0];


		/* TODO: we have this or similar code at least four times!! */

		$resultO = array_shift($fe);

// wether the resultset ist empty?
		$resultD = f('SELECT 1 FROM ' . LINK_TABLE . ' WHERE Name="' . WE_SHOP_TITLE_FIELD_NAME . '" LIMIT 1', '', $this->db);

		$c = 0;
		$iconBarTable = new we_html_table(array('class' => 'iconBar'), 1, 4);

		$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_shop_extArt,fa-lg fa-cart-plus', "javascript:top.opener.top.we_cmd('new_article')", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_USER")));
		$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_shop_delOrd,fa-lg fa-shopping-cart,fa-lg fa-trash-o', "javascript:top.opener.top.we_cmd('delete_shop')", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_USER")));

		if($resultD){
			$iconBarTable->addCol();
			$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_shop_sum,fa-lg fa-line-chart', "javascript:top.content.editor.location=WE().consts.dirs.WEBEDITION_DIR + 'we_showMod.php?mod=shop&pnt=editor&top=1&typ=document '", true));
		} elseif($resultO){
			$iconBarTable->addCol();
			$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_shop_sum,fa-lg fa-line-chart', "javascript:top.content.editor.location=WE().consts.dirs.WEBEDITION_DIR + 'we_showMod.php?mod=shop&pnt=editor&top=1&typ=object&ViewClass=$classid '", true));
		}

		$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_shop_pref,fa-lg fa-pencil,fa-lg fa-list-alt', "javascript:top.opener.top.we_cmd('pref_shop')", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_USER")));
		$iconBarTable->setCol(0, $c++, null, we_html_button::create_button('fa:btn_payment_val,fa-lg fa-long-arrow-right,fa-lg fa-money', "javascript:top.opener.top.we_cmd('payment_val')", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_USER")));

		if($headline){
			$iconBarTable->addCol();
			$iconBarTable->setCol(0, $c++, array('style' => 'text-align:right', 'class' => 'header_shop'), '<span style="margin-left:15px">' . $headline . '</span>');
		}

		return $this->getHTMLDocument(we_html_element::htmlBody(array('id' => 'iconBar'), $iconBarTable->getHTML()), $extraHead);
	}

	protected function getHTMLCmd(){
		return $this->getHTMLDocument(we_html_element::htmlBody());
	}

	protected function getHTMLEditor($extraUrlParams = '', $extraHead = ''){//TODO: maybe abandon the split between former Top- and other editor files
		if(we_base_request::_(we_base_request::BOOL, 'top')){//doing what have been done in edit_shop_editorFramesetTop before
			return $this->getHTMLEditorTop();
		}

//do what have been done in edit_shop_editorFrameset before

		$bid = we_base_request::_(we_base_request::INT, 'bid', 0);
		$mid = we_base_request::_(we_base_request::STRING, 'mid', 0);
		$yearView = we_base_request::_(we_base_request::INT, 'ViewYear', 0);
		$home = we_base_request::_(we_base_request::BOOL, 'home');

		if($home){
			$bodyURL = WEBEDITION_DIR . 'we_showMod.php?mod=shop&home=1';
		} elseif($mid){
			$year = substr($mid, (strlen($mid) - 4));
			$month = str_replace($year, '', $mid);
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_revenueTop.php?ViewYear=' . $year . '&ViewMonth=' . $month;
		} elseif($yearView){
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_revenueTop.php?ViewYear=' . $yearView;
		} else {
			$bodyURL = WEBEDITION_DIR . 'we_showMod.php?mod=shop&bid=' . $bid;
		}

		return $this->getHTMLDocument(
				we_html_element::htmlBody(array('class' => 'moduleEditor'), we_html_element::htmlIFrame('edheader', WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=edheader&home=' . $home . '&mid=' . $mid . $yearView . '&bid=' . $bid, '', '', '', false, 'editorHeader') .
					we_html_element::htmlIFrame('edbody', $bodyURL . '&pnt=edbody', 'bottom: 0px;', 'border:0px;width:100%;height:100%;', '', true, 'editorBody')
				)
		);
	}

	function getHTMLEditorTop(){// TODO: merge getHTMLRight and getHTMLRightTop
		$DB_WE = $this->db;

		$home = we_base_request::_(we_base_request::BOOL, 'home');
		$mid = we_base_request::_(we_base_request::INT, 'mid', 0);
		$bid = we_base_request::_(we_base_request::INT, 'bid', 0);

// config
		$feldnamen = explode('|', f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_pref"', '', $DB_WE));
		for($i = 0; $i <= 3; $i++){
			$feldnamen[$i] = isset($feldnamen[$i]) ? $feldnamen[$i] : '';
		}
		$fe = explode(',', $feldnamen[3]);

		$classid = $fe[0];


		$resultO = array_shift($fe);

// wether the resultset ist empty?
		$resultD = f('SELECT 1 FROM ' . LINK_TABLE . ' WHERE Name="' . $DB_WE->escape(WE_SHOP_TITLE_FIELD_NAME) . '" LIMIT 1', '', $DB_WE);

		if($home){
			$bodyURL = WEBEDITION_DIR . 'we_showMod.php?mod=shop&home=1&pnt=edbody'; //same as in getHTMLRight()
		} elseif($resultD && !$resultO){ // docs but no objects
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_article_extend.php?typ=document';
		} elseif(!$resultD && $resultO){ // no docs but objects
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_article_extend.php?typ=object&ViewClass=' . $classid;
		} elseif($resultD && $resultO){
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_article_extend.php?typ=document';
		} else {
			$bodyURL = WE_SHOP_MODULE_DIR . 'edit_shop_revenueTop.php';
		}

		$body = we_html_element::htmlIFrame('edheader', WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=edheader&top=1&home=' . $home . '&mid=' . $mid . '&bid=' . $bid . '&typ=object&ViewClass=' . $classid, 'position:absolute;top:0px;height:60px;left:0px;right:0px;', '', '', false) .
			we_html_element::htmlIFrame('edbody', $bodyURL, 'position:absolute;top:60px;bottom:0px;left:0px;right:0px;', '', '', true);
		return $this->getHTMLDocument(we_html_element::htmlBody([], $body));
	}

	protected function getHTMLEditorHeader($mode = 0){
		$DB_WE = $this->db;
		if(we_base_request::_(we_base_request::BOOL, 'home')){
			return parent::getHTMLEditorHeader(0);
		}

		if(we_base_request::_(we_base_request::BOOL, 'top')){
			return $this->getHTMLEditorHeaderTop();
		}

		$bid = we_base_request::_(we_base_request::INT, 'bid', 0);

		$hash = getHash('SELECT IntCustomerID,DATE_FORMAT(DateOrder,"' . g_l('date', '[format][mysqlDate]') . '") AS d FROM ' . SHOP_TABLE . ' WHERE IntOrderID=' . $bid . ' LIMIT 1', $DB_WE);
		if($hash){
			$cid = $hash['IntCustomerID'];
			$cdat = $hash['d'];
		} else {
			$cid = 0;
			$cdat = '';
		}
		$we_tabs = new we_tabs();

		if(!empty($_REQUEST["mid"]) && $_REQUEST["mid"] != '00'){
			$we_tabs->addTab(g_l('tabs', '[module][overview]'), true, 0);
		} else {
			$we_tabs->addTab(g_l('tabs', '[module][orderdata]'), true, "setTab(" . self::TAB_OVERVIEW . ");");
			$we_tabs->addTab(g_l('tabs', '[module][orderlist]'), false, "setTab(" . self::TAB_ORDERLIST . ");");
		}

		$textPre = g_l('modules_shop', $bid > 0 ? '[orderList][order]' : '[order_view]');
		$textPost = !empty($_REQUEST['mid']) && $_REQUEST['mid'] > 0 ? (strlen($_REQUEST['mid']) > 5 ? g_l('modules_shop', '[month][' . substr($_REQUEST['mid'], 0, -5) . ']') . " " . substr($_REQUEST['mid'], -5, 4) : substr($_REQUEST['mid'], 1)) : ($bid ? sprintf(g_l('modules_shop', '[orderNo]'), $bid, $cdat) : '');

		$tab_head = we_tabs::getHeader('
function setTab(tab) {
	switch (tab) {
		case ' . self::TAB_OVERVIEW . ':
			parent.edbody.document.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edbody&bid=' . $bid . '";
			break;
		case ' . self::TAB_ORDERLIST . ':
			parent.edbody.document.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=customerOrderList&cid=' . $cid . '";
			break;
	}
}');

		$tab_body_content = '<div id="main"><div id="headrow"><b>' . str_replace(" ", "&nbsp;", $textPre) . ':&nbsp;</b><span id="h_path" class="header_small"><b id="titlePath">' . str_replace(" ", "&nbsp;", $textPost) . '</b></span></div>' .
			$we_tabs->getHTML() .
			'</div>';
		$tab_body = we_html_element::htmlBody(array("onresize" => "weTabs.setFrameSize()", "onload" => "weTabs.setFrameSize()", "id" => "eHeaderBody"), $tab_body_content);

		return $this->getHTMLDocument($tab_body, $tab_head);
	}

	function getHTMLEditorHeaderTop(){
//$yid = we_base_request::_(we_base_request::INT, "ViewYear", date("Y"));
//$bid = we_base_request::_(we_base_request::INT, "bid", 0);
//$cid = f('SELECT IntCustomerID FROM ' . SHOP_TABLE . ' WHERE IntOrderID=' . intval($bid), "IntCustomerID", $this->db);
		$data = getHash("SELECT IntOrderID,DATE_FORMAT(DateOrder,'" . g_l('date', '[format][mysqlDate]') . "') AS orddate FROM " . SHOP_TABLE . ' GROUP BY IntOrderID ORDER BY IntID DESC LIMIT 1', $this->db);
		$headline = ($data ? sprintf(g_l('modules_shop', '[lastOrder]'), $data["IntOrderID"], $data["orddate"]) : '');

/// config
		$feldnamen = explode('|', f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_pref"', '', $this->db));
		$fe = isset($feldnamen[3]) ? explode(",", $feldnamen[3]) : array(0);

		$classid = $fe[0];
		$resultO = array_shift($fe);

// wether the resultset ist empty?
		$resultD = f('SELECT 1 FROM ' . LINK_TABLE . ' WHERE Name="' . WE_SHOP_TITLE_FIELD_NAME . '" LIMIT 1', '', $this->db);

// grep the last element from the year-set, wich is the current year
		$yearTrans = f('SELECT DATE_FORMAT(DateOrder,"%Y") AS DateOrd FROM ' . SHOP_TABLE . ' ORDER BY DateOrd DESC LIMIT 1', '', $this->db);


		$we_tabs = new we_tabs();
		if(!empty($_REQUEST["mid"])){
			$we_tabs->addTab(g_l('tabs', '[module][overview]'), true, "//");
		} else {
			switch(true){
				default:
				case ($resultD):
					$we_tabs->addTab(g_l('tabs', '[module][admin_1]'), true, "setTab(" . self::TAB_ADMIN1 . ");");
				case ($resultO):
					$we_tabs->addTab(g_l('tabs', '[module][admin_2]'), (!$resultD), "setTab(" . self::TAB_ADMIN2 . ");");
				case (isset($yearTrans) && $yearTrans != 0):
					$we_tabs->addTab(g_l('tabs', '[module][admin_3]'), false, "setTab(" . self::TAB_ADMIN3 . ");");
					break;
			}
		}

		$tab_head = we_tabs::getHeader('
function setTab(tab) {
	switch (tab) {
		case ' . self::TAB_ADMIN1 . ':
			parent.edbody.document.location = WE().consts.dirs.WE_MODULES_DIR+"shop/edit_shop_article_extend.php?typ=document";
			break;
		case ' . self::TAB_ADMIN2 . ':
			parent.edbody.document.location = WE().consts.dirs.WE_MODULES_DIR+"shop/edit_shop_article_extend.php?typ=object&ViewClass=' . $classid . '";
			break;
		' . (isset($yearTrans) ? '
		case ' . self::TAB_ADMIN2 . ':
			parent.edbody.document.location = WE().consts.dirs.WE_MODULES_DIR+"shop/edit_shop_revenueTop.php?ViewYear=' . $yearTrans . '" // " + treeData.yearshop
			break;
		' : '') . '
	}
}');

		$tab_body_content = '<div id="main"><div id="headrow">&nbsp;' . we_html_element::htmlB($headline) . '</div>' .
			$we_tabs->getHTML() .
			'</div>';
		$tab_body = we_html_element::htmlBody(array('id' => 'eHeaderBody'), $tab_body_content);

		return $this->getHTMLDocument($tab_body, $tab_head);
	}

	public function getHTML($what = '', $mode = '', $step = 0){

		switch($what){
			case 'iconbar':
				return $this->getHTMLIconbar();
			case 'frameset':
				$bid = we_base_request::_(we_base_request::INT, 'bid');
				return $this->getHTMLFrameset($this->Tree->getJSTreeCode(), ($bid > 0 ? '&bid=' . ($bid === -1 ? intval(f('SELECT MAX(IntOrderID) FROM ' . SHOP_TABLE, '', $this->db)) : $bid) : '&top=1&home=1'));
			case 'exitQuestion':
				return we_shop_frames::showExitQuestion();
			case 'customerOrderList':
				return self::showCustomerOrderList();
			case 'pref_shop':
				return self::showPrefDialog();
			case 'savePrefDialog':
				return self::savePrefDialog();
			case 'payment_val':
				return self::showPaymentDialog();
			case 'savePaymentDialog':
				return self::savePaymentDialog();
			case 'edit_shop_status':
				return self::showStatusMailsDialog();
			case 'edit_shop_vats':
				return self::showVatsDialog();
			case 'edit_shop_vat_country':
				return self::showVatCountryDialog();
			case 'edit_shop_categories':
				return self::showCategoriesDialog();
			case 'edit_shop_shipping':
				return self::showDialogShipping();
			case 'edit_order_properties':
				$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
				we_html_tools::protect($protect);
				return $this->View->getProperties();

			default:
				return parent::getHTML($what, $mode, $step);
		}
	}

	protected function getHTMLEditorBody(){
		if(we_base_request::_(we_base_request::BOOL, 'home')){
			return $this->View->getHomeScreen();
		}
		return $this->View->getProperties();
	}

	public static function showExitQuestion(){
		$yes = 'opener.top.hot=0;opener.top.we_cmd("save");self.close()';
		$no = 'opener.top.hot=0;opener.top.we_cmd("close");self.close();';
		$cancel = 'self.close();';

		echo we_html_tools::getHtmlTop(''/* FIXME: missing title */, '', '', '', '
<body class="weEditorBody" onBlur="self.focus()" onload="self.focus()">' .
			we_html_tools::htmlYesNoCancelDialog(g_l('modules_shop', '[exit_question]'), '<span class="fa-stack fa-lg" style="color:#F2F200;"><i class="fa fa-exclamation-triangle fa-stack-2x" ></i><i style="color:black;" class="fa fa-exclamation fa-stack-1x"></i></span>', "ja", "nein", "abbrechen", $yes, $no, $cancel) . //GL
			'</body>');
	}

	private static function showCustomerOrderList(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : [false];
		we_html_tools::protect($protect);

		if(($cid = we_base_request::_(we_base_request::INT, 'cid'))){
			$Kundenname = f('SELECT CONCAT(Forename," ",Surname) AS Name FROM ' . CUSTOMER_TABLE . ' WHERE ID=' . $cid);
			$orderList = we_shop_functions::getCustomersOrderList($cid);
		} else {
			$Kundenname = $orderList = '';
		}
		return we_html_tools::getHtmlTop('', '', '', '', we_html_element::htmlBody(['class' => "weEditorBody"], we_html_tools::htmlDialogLayout($orderList, g_l('modules_shop', '[order_liste]') . "&nbsp;" . $Kundenname)));
	}

	private static function prepareFieldname($str){
		return (strpos($str, '_') ?
				substr_replace($str, '/', strpos($str, '_'), 1) :
				$str);
	}

	private static function showPrefDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);

		$DB_WE = $GLOBALS['DB_WE'];

		$ignoreFields = explode(',', we_shop_shop::ignoredEditFields);

		$customerTableFields = $DB_WE->metadata(CUSTOMER_TABLE);
		$selectFields['-'] = '-';
		foreach($customerTableFields as $tblField){
			if(!in_array($tblField['name'], $ignoreFields)){
				$selectFields[$tblField['name']] = $tblField['name'];
			}
		}

		$shoplocation = f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_location"');
		$categorymode = f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="category_mode"');

		$CLFields = we_unserialize(f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_CountryLanguage"'), array(
			'stateField' => '-',
			'stateFieldIsISO' => 0,
			'languageField' => '-',
			'languageFieldIsISO' => 0
		));


//	generate html-output table
		$htmlTable = new we_html_table(array('class' => 'default withBigSpace', 'width' => 410), 10, 3);


//	NumberFormat - currency and taxes
		$feldnamen = explode('|', f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_pref"'));

		//$fe = (isset($feldnamen[3]) ? explode(',', $feldnamen[3]) : []);

		if(!isset($feldnamen[4])){
			$feldnamen[4] = '-';
		}

		$row = 0;
//we_html_tools::htmlSelectCountry('weShopVatCountry', '', 1, [], false, array('id' => 'weShopVatCountry'), 200)

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[shopcats][use_shopCats]'));
		$htmlTable->setCol($row, 1, array('style' => 'width:10px;'));
		$yesno = array(0 => 'false', 1 => 'true');
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('categorymode', $yesno, 1, $categorymode, false, array("id" => "categorymode", "onchange" => "document.getElementById('shop_holders_location').style.display = (this.value == 1 ? '' : 'none'); document.getElementById('shop_holders_location_br').style.display = (this.value == 1 ? '' : 'none');")));
		$htmlTable->setRow($row, array('id' => 'shop_holders_location_br', 'style' => 'display:' . ($categorymode ? '' : 'none')));

		$htmlTable->setRow($row, array('id' => 'shop_holders_location', 'style' => 'display:' . ($categorymode ? '' : 'none')));
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[shopcats][shopHolderCountry]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelectCountry('shoplocation', '', 1, array($shoplocation), false, array('id' => 'shoplocation'), 280));

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[waehrung]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput('waehr', 6, $feldnamen[0]));

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont', 'style' => 'vertical-align:top'], g_l('modules_shop', '[mwst]'));
		$htmlTable->setCol($row++, 2, ['class' => 'defaultfont', 'style' => 'padding-bottom:5px;'], we_html_tools::htmlTextInput('mwst', 6, $feldnamen[1]) . '&nbsp;%');
		$htmlTable->setCol($row++, 0, ['colspan' => 3, 'class' => 'small'], we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[mwst_expl]'), we_html_tools::TYPE_INFO, "400", false, 45));

		$list = ['german' => 'german', 'english' => 'english', 'french' => 'french', 'swiss' => 'swiss'];
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[format]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('format', $list, 1, $feldnamen[2]));


		$pager = ['default' => '-', 5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35, 40 => 40, 45 => 45, 50 => 50];

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[pageMod]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('pag', $pager, 1, $feldnamen[4]));


		if(defined('OBJECT_TABLE')){
			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[classID]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput('classID', 100, (isset($feldnamen[3]) ? $feldnamen[3] : ''), '', '', 'text', 280) . '<br/><span class="small">&nbsp;' . g_l('modules_shop', '[classIDext]') . ' </span>');
		}

// look for all available fields in tblCustomer
		$DB_WE->query('SHOW FIELDS FROM ' . CUSTOMER_TABLE);

		$extraIgnore = explode(',', we_shop_shop::ignoredExtraShowFields);
		$showFields = [];

		while($DB_WE->next_record()){
			if(!in_array($DB_WE->f('Field'), $ignoreFields)){
				$showFields[$DB_WE->f('Field')] = self::prepareFieldname($DB_WE->f('Field'));
			}
		}
		asort($showFields);
		$orderFields = $showFields;
		foreach($extraIgnore as $cur){
			unset($showFields[$cur]);
		}

//	get the already selected fields ...
		$entry = f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="edit_shop_properties"');

// ...
		if(($fields = we_unserialize($entry))){
			// we have an array with following syntax:
			// array ( 'customerFields' => array('fieldname ...',...)
			//         'orderCustomerFields' => array('fieldname', ...) )
		} else {
			t_e('unsupported Shop-Settings found');
		}

		$htmlTable->setCol($row, 0, array('class' => 'defaultfont', 'style' => 'vertical-align:top'), g_l('modules_shop', '[preferences][customerFields]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('orderfields[]', $showFields, (count($showFields) > 5 ? 5 : count($showFields)), implode(',', $fields['customerFields']), true, [], 'value', 280));

		$htmlTable->setCol($row, 0, array('class' => 'defaultfont', 'style' => 'vertical-align:top'), g_l('modules_shop', '[preferences][orderCustomerFields]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('ordercustomerfields[]', $orderFields, min(count($orderFields), 5), implode(',', $fields['orderCustomerFields']), true, [], 'value', 280));

		$htmlTable->setCol($row, 0, array('class' => 'defaultfont', 'style' => 'vertical-align:top'), g_l('modules_shop', '[preferences][CountryField]'));

		/* 		$countrySelect = we_class::htmlSelect('stateField', $selectFields, 1, $CLFields['stateField']);
		  $countrySelectISO = we_html_forms::checkboxWithHidden($CLFields['stateFieldIsISO'], 'stateFieldIsISO', g_l('modules_shop', '[preferences][ISO-Kodiert]'), false, "defaultfont");
		 */

		$htmlTable->setCol($row, 0, array('class' => 'defaultfont', 'style' => 'vertical-align:top'), g_l('modules_shop', '[preferences][LanguageField]'));
		$languageSelect = we_class::htmlSelect('languageField', $selectFields, 1, $CLFields['languageField']);
		$languageSelectISO = we_html_forms::checkboxWithHidden($CLFields['languageFieldIsISO'], 'languageFieldIsISO', g_l('modules_shop', '[preferences][ISO-Kodiert]'), false, "defaultfont");
		$htmlTable->setColContent($row++, 2, $languageSelect . '<br/>' . $languageSelectISO);


		$buttons = we_html_button::position_yes_no_cancel(we_html_button::create_button(we_html_button::SAVE, 'javascript:if(document.getElementById("categorymode").value == 1 && document.getElementById("shoplocation").value === ""){' . we_message_reporting::getShowMessageCall(g_l('modules_shop', '[preferences][save_alert]'), we_message_reporting::WE_MESSAGE_ERROR) . '}else{document.we_form.submit();}'), '', we_html_button::create_button(we_html_button::CANCEL, 'javascript:self.close();'));

		return we_html_tools::getHtmlTop('', '', '', '', we_html_element::htmlBody(['class' => "weDialogBody", 'onload' => "self.focus();"], '<form name="we_form" method="post" action="' . WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=savePrefDialog" style="margin-left:8px; margin-top:16px;">
	' . we_html_tools::htmlDialogLayout($htmlTable->getHtml(), g_l('modules_shop', '[pref]'), $buttons) . '</form>'));
	}

	private static function savePrefDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);
		$DB_WE = $GLOBALS['DB_WE'];
		if(($format = we_base_request::_(we_base_request::RAW, "format"))){ //	save data in arrays ..
			$settings = ['shop_location' => we_base_request::_(we_base_request::STRING, 'shoplocation'),
				'category_mode' => we_base_request::_(we_base_request::INT, 'categorymode'),
			];

			foreach($settings as $dbField => $value){
				$DB_WE->query('REPLACE INTO ' . SETTINGS_TABLE . ' SET ' . we_database_base::arraySetter(array(
						'tool' => "shop",
						'pref_name' => $dbField,
						'pref_value' => $value
				)));
			}

			$DB_WE->query('REPLACE ' . SETTINGS_TABLE . ' SET ' . we_database_base::arraySetter(array(
					'tool' => 'shop',
					'pref_name' => "shop_pref",
					'pref_value' => we_base_request::_(we_base_request::STRING, "waehr") . '|' . we_base_request::_(we_base_request::STRING, "mwst") . '|' . $format . '|' . we_base_request::_(we_base_request::STRING, "classID", 0) . '|' . we_base_request::_(we_base_request::STRING, "pag")
			)));

			$fields['customerFields'] = we_base_request::_(we_base_request::STRING, 'orderfields', []);
			$fields['orderCustomerFields'] = we_base_request::_(we_base_request::STRING, 'ordercustomerfields', []);

			// check if field exists
			$DB_WE->query('REPLACE ' . SETTINGS_TABLE . ' SET ' . we_database_base::arraySetter(array(
					'tool' => 'shop',
					'pref_name' => 'edit_shop_properties',
					'pref_value' => we_serialize($fields, SERIALIZE_JSON)
			)));

			$CLFields['stateField'] = we_base_request::_(we_base_request::RAW, 'stateField', '-');
			$CLFields['stateFieldIsISO'] = we_base_request::_(we_base_request::STRING, 'stateFieldIsISO', 0);
			$CLFields['languageField'] = we_base_request::_(we_base_request::STRING, 'languageField', '-');
			$CLFields['languageFieldIsISO'] = we_base_request::_(we_base_request::RAW, 'languageFieldIsISO', 0);

			// check if field exists
			$DB_WE->query('REPLACE ' . SETTINGS_TABLE . ' SET tool="shop",pref_name="shop_CountryLanguage", pref_value="' . $DB_WE->escape(we_serialize($CLFields)) . '"');
			// Update Country Field in weShopVatRule
			$weShopVatRule = we_shop_vatRule::getShopVatRule();
			$weShopVatRule->stateField = $CLFields['stateField'];
			$weShopVatRule->stateFieldIsISO = $CLFields['stateFieldIsISO'];
			$weShopVatRule->save();
			// Update Language Field in weShopStatusMails
			$weShopStatusMails = we_shop_statusMails::getShopStatusMails();
			$weShopStatusMails->LanguageData['languageField'] = $CLFields['languageField'];
			$weShopStatusMails->LanguageData['languageFieldIsISO'] = $CLFields['languageFieldIsISO'];
			$weShopStatusMails->save();

			//	Close window when finished
			return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement('self.close();'), we_html_element::htmlBody());
		}
	}

	private static function showPaymentDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);

		$DB_WE = $GLOBALS['DB_WE'];

//	NumberFormat - currency and taxes
		$feldnamen = explode('|', f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="payment_details"'));

		for($i = 0; $i <= 18; $i++){
			$feldnamen[$i] = isset($feldnamen[$i]) ? $feldnamen[$i] : '';
		}

		$row = 0;
//hier
		$Parts = [];

		if(defined('CUSTOMER_TABLE')){
			$htmlTable = new we_html_table(array('class' => 'default withSpace', 'width' => "100%"), 4, 3);
			$htmlTable->setCol($row++, 0, array('colspan' => 4, 'class' => 'defaultfont'), g_l('modules_shop', '[FormFieldsTxt]'));

			$custfields = [];

			$DB_WE->query("SHOW FIELDS FROM " . CUSTOMER_TABLE);
			while($DB_WE->next_record()){
				$custfields[$DB_WE->f("Field")] = $DB_WE->f("Field");
			}

			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldForname]'));
			$htmlTable->setCol($row, 1, array('style' => 'width:10px;'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldForname', $custfields, 1, $feldnamen[0]));

			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldSurname]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldSurname', $custfields, 1, $feldnamen[1]));

			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldStreet]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldStreet', $custfields, 1, $feldnamen[2]));

			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldZip]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldZip', $custfields, 1, $feldnamen[3]));

			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldCity]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldCity', $custfields, 1, $feldnamen[4]));


			$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[fieldEmail]'));
			$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('fieldEmail', array_merge(array(""), $custfields), 1, $feldnamen[18]));

			$Parts[] = array("html" => $htmlTable->getHtml());
		}

// PayPal
		$htmlTable = new we_html_table(['class' => 'default withSpace', 'width' => "100%"], 4, 3);
		$row = 0;
		$htmlTable->setCol($row++, 0, ['class' => 'weDialogHeadline', 'colspan' => 4], g_l('modules_shop', '[paypal][name]'));

		$list1 = ["AI" => "Anguilla", "AR" => "Argentina", "AU" => "Australia", "AT" => "Austria", "BE" => "Belgium", "BR" => "Brazil", "CA" => "Canada", "CL" => "Chile", "CN" => "China", "CR" => "Costa Rica", "CY" => "Cyprus", "CZ" => "Czech Republic", "DK" => "Denmark", "DO" => "Dominican Rep.", "EC" => "Equador", "EE" => "Estonia", "FI" => "Finland", "FR" => "France", "DE" => "Deutschland", "GR" => "Greece", "HK" => "Hong Kong"];
		$list2 = ["HU" => "Hungary", "IS" => "Iceland", "IN" => "India", "IE" => "Ireland", "IL" => "Israel", "IT" => "Italy", "JM" => "Jamaica", "JP" => "Japan", "LV" => "Latvia", "LT" => "Lithuania", "LU" => "Luxemburg", "MY" => "Malaysia", "MT" => "Malta", "MX" => "Mexico"];
		$list3 = ["NL" => "Netherlands", "NZ" => "New Zealand", "NO" => "Norway", "PL" => "Poland", "PT" => "Portugal", "SG" => "Singapore", "SK" => "Slovakia", "ZA" => "South Afrika", "KR" => "South Korea", "ES" => "Spain", "SE" => "Sweden", "CH" => "Switzerland", "TW" => "Taiwan", "TH" => "Thailand", "TR" => "Turkey", "GB" => "United Kingdom", "United States" => "US", "Uruguay" => "UY", "Venezuela" => "VE"];
		$list = array_merge($list1, $list2, $list3);

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[lc]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('lc', $list, 1, $feldnamen[5]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[paypalLcTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[paypalbusiness]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("ppB", 30, $feldnamen[6], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[paypalbTxt]') . ' </span>');

		$paypalPV = array("default" => "PayPal-Shop", "test" => "Sandbox (Test) ");
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[paypalSB]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('psb', $paypalPV, 1, $feldnamen[7]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[paypalSBTxt]') . ' </span>');

		$Parts[] = array("html" => $htmlTable->getHtml());

// saferpay
		$htmlTable = new we_html_table(['class' => 'default withSpace', 'width' => "100%"], 10, 3);
		$row = 0;
		$htmlTable->setCol($row++, 0, ['class' => 'weDialogHeadline', 'colspan' => 4], g_l('modules_shop', '[saferpay]'));

		$saferPayLang = array("en" => "english", "de" => "deutsch", "fr" => "francais", "it" => "italiano");
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayTermLang]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('lcS', $saferPayLang, 1, $feldnamen[8]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayLcTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayID]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("spAID", 30, $feldnamen[9], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayIDTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpaybusiness]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("spB", 30, $feldnamen[10], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpaybTxt]') . ' </span>');

		$saferPayCollect = array("no" => g_l('modules_shop', '[saferpayNo]'), "yes" => g_l('modules_shop', '[saferpayYes]'));
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayAllowCollect]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('spC', $saferPayCollect, 1, $feldnamen[11]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayAllowCollectTxt]') . ' </span>');

		$saferPayDelivery = array("no" => g_l('modules_shop', '[saferpayNo]'), "yes" => g_l('modules_shop', '[saferpayYes]'));
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayDelivery]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('spD', $saferPayDelivery, 1, $feldnamen[12]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayDeliveryTxt]') . ' </span>');

		$saferPayConfirm = array("no" => g_l('modules_shop', '[saferpayNo]'), "yes" => g_l('modules_shop', '[saferpayYes]'));
		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayUnotify]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlSelect('spCo', $saferPayConfirm, 1, $feldnamen[13]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayUnotifyTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayProviderset]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("spPS", 30, $feldnamen[14], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayProvidersetTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayCMDPath]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("spcmdP", 30, $feldnamen[15], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayCMDPathTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, ['class' => 'defaultfont'], g_l('modules_shop', '[saferpayconfPath]'));
		$htmlTable->setColContent($row++, 2, we_html_tools::htmlTextInput("spconfP", 30, $feldnamen[16], "", "", "text", 128) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpayconfPathTxt]') . ' </span>');

		$htmlTable->setCol($row, 0, array('class' => 'defaultfont', 'style' => 'padding-bottom:20px;'), g_l('modules_shop', '[saferpaydesc]'));
		$htmlTable->setColContent($row++, 2, we_class::htmlTextArea("spdesc", 2, 30, $feldnamen[17]) . '<span class="small">&nbsp;' . g_l('modules_shop', '[saferpaydescTxt]') . ' </span>');

		$Parts[] = ["html" => $htmlTable->getHtml()];

		$buttons = we_html_button::position_yes_no_cancel(we_html_button::create_button(we_html_button::SAVE, "javascript:document.we_form.submit();"), "", we_html_button::create_button(we_html_button::CANCEL, "javascript:self.close();")
		);

		return we_html_tools::getHtmlTop('', '', '', '', we_html_element::htmlBody(['class' => "weDialogBody", 'onload' => "self.focus();"], '<form name="we_form" method="post" style="margin-top:16px;" action="' . WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=savePaymentDialog"">
' . we_html_multiIconBox::getHTML('', $Parts, 30, $buttons, -1, '', '', false, g_l('modules_shop', '[paymentP]'), '', '', 'auto') . '</form>'));
	}

	private static function savePaymentDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);
		$DB_WE = $GLOBALS['DB_WE'];

		if(($fname = we_base_request::_(we_base_request::STRING, "fieldForname"))){ //	save data in arrays ..
			$DB_WE->query('REPLACE INTO ' . SETTINGS_TABLE . ' SET ' . we_database_base::arraySetter(array(
					'pref_value' => $fname . "|" . we_base_request::_(we_base_request::STRING, "fieldSurname") . "|" . we_base_request::_(we_base_request::STRING, "fieldStreet") . "|" . we_base_request::_(we_base_request::STRING, "fieldZip") . "|" . we_base_request::_(we_base_request::STRING, "fieldCity") . "|" . we_base_request::_(we_base_request::STRING, "lc") . "|" . we_base_request::_(we_base_request::STRING, "ppB") . "|" . we_base_request::_(we_base_request::STRING, "psb") . "|" . we_base_request::_(we_base_request::STRING, "lcS") . "|" . we_base_request::_(we_base_request::STRING, "spAID") . "|" . we_base_request::_(we_base_request::STRING, "spB") . "|" . we_base_request::_(we_base_request::STRING, "spC") . "|" . we_base_request::_(we_base_request::STRING, "spD") . "|" . we_base_request::_(we_base_request::STRING, "spCo") . "|" . we_base_request::_(we_base_request::STRING, "spPS") . "|" . we_base_request::_(we_base_request::STRING, "spcmdP") . "|" . we_base_request::_(we_base_request::STRING, "spconfP") . "|" . we_base_request::_(we_base_request::STRING, "spdesc") . "|" . we_base_request::_(we_base_request::STRING, "fieldEmail"),
					'tool' => 'shop',
					'pref_name' => 'payment_details',
			)));

			//	Close window when finished
			return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement('self.close();'), we_html_element::htmlBody());
		}
	}

	private static function showStatusMailsDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);
		$DB_WE = $GLOBALS['DB_WE'];

		$jsFunction = '
	function we_submitForm(url){
		var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
		f.action = url;
    	f.method = "post";

    	f.submit();
			return true;
    }

	function doUnload() {
		WE().util.jsWindow.prototype.closeAll(window);
	}

	function we_cmd(){
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

		switch (args[0]) {
			case "close":
				window.close();
				break;
			case "save":
				we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_status");
				break;
			default:
				top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
		}
	}';

// initialise the shopStatusMails Object
		if(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0) === 'saveShopStatusMails'){
			// initialise the vatRule by request
			$weShopStatusMails = we_shop_statusMails::initByRequest();
			$weShopStatusMails->save();
		} else {

			$weShopStatusMails = we_shop_statusMails::getShopStatusMails();
		}

// array with all rules

		$ignoreFields = array('ID', 'Forename', 'Surname', 'Password', 'Username', 'ParentID', 'Path', 'IsFolder', 'Text');
		$customerTableFields = $DB_WE->metadata(CUSTOMER_TABLE);
		$selectFields['-'] = '-';
		foreach($customerTableFields as $tblField){
			if(!in_array($tblField['name'], $ignoreFields)){
				$selectFields[$tblField['name']] = $tblField['name'];
			}
		}

		$frontendL = $GLOBALS["weFrontendLanguages"];
		foreach($frontendL as &$lcvalue){
			$lccode = explode('_', $lcvalue);
			$lcvalue = $lccode[0];
		}
		unset($lcvalue);

		$tabStatus = new we_html_table(['class' => 'withSpace'], $rows_num = 5, $cols_num = 6 + count($frontendL));

		$tabStatus->setCol(0, 1, ['colspan' => 4, 'class' => 'defaultfont bold', 'style' => 'text-align:center;background-color:yellow;'], g_l('modules_shop', '[statusmails][AnzeigeDaten]'));
		$tabStatus->setCol(0, 5, ['colspan' => 1 + count($frontendL), 'class' => 'defaultfont bold', 'style' => 'text-align:center;background-color:lightblue;'], g_l('modules_shop', '[statusmails][Dokumente]'));
		$tabStatus->setCol(1, 0, ['class' => 'defaultfont bold'], g_l('modules_shop', '[statusmails][fieldname]'));
		$tabStatus->setCol(1, 1, ['class' => 'defaultfont bold'], g_l('modules_shop', '[statusmails][hidefield]'));
		$tabStatus->setCol(1, 2, ['class' => "defaultfont bold"], g_l('modules_shop', '[statusmails][hidefieldCOV]'));
		$tabStatus->setCol(1, 3, ['class' => "defaultfont bold"], g_l('modules_shop', '[statusmails][fieldtext]'));
		$tabStatus->setCol(1, 4, ['class' => "defaultfont bold"], g_l('modules_shop', '[statusmails][EMailssenden]'));
		$tabStatus->setCol(1, 5, ['class' => "defaultfont bold"], g_l('modules_shop', '[statusmails][defaultDocs]'));

		foreach($frontendL as $pos => $langkey){
			$tabStatus->setCol(1, 6 + $pos, ['class' => "defaultfont bold"], g_l('languages', '[' . $langkey . ']') . ' (' . $langkey . ')');
		}

		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 0, array('class' => "defaultfont bold", "width" => 120), $fieldname);
		}
		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 1, ['class' => 'defaultfont'], we_html_forms::checkboxWithHidden($weShopStatusMails->FieldsHidden[$fieldname], 'FieldsHidden[' . $fieldname . ']', g_l('modules_shop', '[statusmails][hidefieldJa]'), false, "defaultfont"));
		}

		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 2, ['class' => 'defaultfont'], we_html_forms::checkboxWithHidden($weShopStatusMails->FieldsHiddenCOV[$fieldname], 'FieldsHiddenCOV[' . $fieldname . ']', g_l('modules_shop', '[statusmails][hidefieldJa]'), false, "defaultfont"));
		}

		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 3, ['class' => 'defaultfont'], '<input name="FieldsText[' . $fieldname . ']" size="15" type="text" value="' . $weShopStatusMails->FieldsText[$fieldname] . '" />');
		}

		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 4, ['class' => 'defaultfont'], we_html_forms::radioButton(0, ($weShopStatusMails->FieldsMails[$fieldname] == 0 ? '1' : '0'), 'FieldsMails[' . $fieldname . ']', g_l('modules_shop', '[statusmails][EMailssendenNein]')) .
				we_html_forms::radioButton(1, ($weShopStatusMails->FieldsMails[$fieldname] == 1 ? '1' : '0'), 'FieldsMails[' . $fieldname . ']', g_l('modules_shop', '[statusmails][EMailssendenHand]')) .
				we_html_forms::radioButton(2, ($weShopStatusMails->FieldsMails[$fieldname] == 2 ? '1' : '0'), 'FieldsMails[' . $fieldname . ']', g_l('modules_shop', '[statusmails][EMailssendenAuto]')));
		}

		foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
			$tabStatus->setCol($fieldkey + 2, 5, ['class' => 'defaultfont'], we_html_tools::htmlTextInput("FieldsDocuments[default][" . $fieldname . "]", 15, $weShopStatusMails->FieldsDocuments['default'][$fieldname]));
		}

		foreach($frontendL as $pos => $langkey){
			foreach(we_shop_statusMails::$StatusFields as $fieldkey => $fieldname){
				$tabStatus->setCol($fieldkey + 2, 6 + $pos, ['class' => 'defaultfont'], we_html_tools::htmlTextInput('FieldsDocuments[' . $langkey . '][' . $fieldname . ']', 15, $weShopStatusMails->FieldsDocuments[$langkey][$fieldname]));
			}
		}

		$tabEMail = new we_html_table(['class' => 'withSpace'], $rows_num = 4, $cols_num = 6);
		$tabEMail->setCol(0, 0, ['class' => 'defaultfont', "width" => 220], g_l('modules_shop', '[statusmails][AbsenderAdresse]') .
			'<br/>' . we_html_tools::htmlTextInput("EMailData[address]", 30, $weShopStatusMails->EMailData['address']));
		$tabEMail->setCol(1, 0, ['class' => 'defaultfont', "width" => 220], g_l('modules_shop', '[statusmails][AbsenderName]') .
			'<br/>' . we_html_tools::htmlTextInput("EMailData[name]", 30, $weShopStatusMails->EMailData['name']));
		$tabEMail->setCol(2, 0, ['class' => 'defaultfont', "width" => 220], g_l('modules_shop', '[statusmails][bcc]') .
			'<br/>' . we_html_tools::htmlTextInput("EMailData[bcc]", 30, $weShopStatusMails->EMailData['bcc']));
		$tabEMail->setCol(0, 1, ['class' => 'defaultfont', "width" => 340], g_l('modules_shop', '[statusmails][EMailFeld]') .
			'<br/>' . we_class::htmlSelect('EMailData[emailField]', $selectFields, 1, $weShopStatusMails->EMailData['emailField']));
		$tabEMail->setCol(1, 1, ['class' => 'defaultfont', "width" => 340], g_l('modules_shop', '[statusmails][TitelFeld]') .
			'<br/>' . we_class::htmlSelect('EMailData[titleField]', $selectFields, 1, $weShopStatusMails->EMailData['titleField']));
		$tabEMail->setCol(2, 1, ['class' => 'defaultfont', "width" => 340], g_l('modules_shop', '[statusmails][DocumentSubjectField]') .
			'<br/>' . we_html_tools::htmlTextInput("EMailData[DocumentSubjectField]", 30, $weShopStatusMails->EMailData['DocumentSubjectField']));
		$tabEMail->setCol(3, 0, ['class' => 'defaultfont', "width" => 340], g_l('modules_shop', '[statusmails][DocumentAttachmentFieldA]') . '<br/>' . we_html_tools::htmlTextInput("EMailData[DocumentAttachmentFieldA]", 30, $weShopStatusMails->EMailData['DocumentAttachmentFieldA']));
		$tabEMail->setCol(3, 1, ['class' => 'defaultfont', "width" => 340], g_l('modules_shop', '[statusmails][DocumentAttachmentFieldB]') . '<br/>' . we_html_tools::htmlTextInput("EMailData[DocumentAttachmentFieldB]", 30, $weShopStatusMails->EMailData['DocumentAttachmentFieldB']));

		$tabSprache = new we_html_table(['class' => 'withSpace'], $rows_num = 2, $cols_num = 5);
		$tabSprache->setCol(0, 0, array('class' => 'defaultfont', "width" => 220), we_html_forms::checkboxWithHidden($weShopStatusMails->LanguageData['useLanguages'], 'LanguageData[useLanguages]', g_l('modules_shop', '[statusmails][useLanguages]'), false, "defaultfont"));
		$tabSprache->setCol(0, 2, array('class' => 'defaultfont', "width" => 220), g_l('modules_shop', '[statusmails][SprachenFeld]') . we_class::htmlSelect('LanguageData[languageField]', $selectFields, 1, $weShopStatusMails->LanguageData['languageField']) . we_html_forms::checkboxWithHidden($weShopStatusMails->LanguageData['languageFieldIsISO'], 'LanguageData[languageFieldIsISO]', g_l('modules_shop', '[preferences][ISO-Kodiert]'), false, "defaultfont"));

		$parts = [
			[
				'headline' => g_l('modules_shop', '[statusmails][AnzeigeDaten]'),
				'space' => we_html_multiIconBox::SPACE_MED,
				'html' => '',
				'noline' => 1
			],
			[
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[statusmails][hintDokumente]'), we_html_tools::TYPE_INFO, 650, false),
				'noline' => 1
			],
			[
				'headline' => '',
				'html' => $tabStatus->getHtml()
			],
			[
				'headline' => g_l('modules_shop', '[statusmails][EMailDaten]'),
				'html' => '',
				'space' => we_html_multiIconBox::SPACE_MED,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[statusmails][hintEMailDaten]'), we_html_tools::TYPE_INFO, 650, false),
				'noline' => 1
			],
			['space' => we_html_multiIconBox::SPACE_MED,
				'html' => $tabEMail->getHtml(),
			], [
				'headline' => g_l('modules_shop', '[statusmails][Spracheinstellungen]'),
				'space' => we_html_multiIconBox::SPACE_MED,
				'html' => '',
				'noline' => 1
			], [
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[statusmails][hintSprache]'), we_html_tools::TYPE_INFO, 650, false),
				'noline' => 1
			], [
				'space' => we_html_multiIconBox::SPACE_MED,
				'html' => $tabSprache->getHtml(),
				'noline' => 1
			], [
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[statusmails][hintISO]'), we_html_tools::TYPE_INFO, 650, false),
			]
		];

		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement($jsFunction), we_html_element::htmlBody(['class' => "weDialogBody", 'onload' => "window.focus();"], '<form name="we_form" method="post" action="' . WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=edit_shop_status">
		<input type="hidden" name="we_cmd[0]" value="saveShopStatusMails" />' .
					we_html_multiIconBox::getHTML('weShopStatusMails', $parts, 30, we_html_button::position_yes_no_cancel(
							we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd(\'save\');'), '', we_html_button::create_button(we_html_button::CANCEL, 'javascript:we_cmd(\'close\');')
						), -1, '', '', false, g_l('modules_shop', '[statusmails][box_headline]'), '', '', 'scroll'
					) .
					'</form>'));
	}

	private static function showVatsDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);

		$saveSuccess = false;
		$onsaveClose = we_base_request::_(we_base_request::BOOL, 'onsaveclose', false);
		switch(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0)){
			case 'saveVat':
				$province = we_base_request::_(we_base_request::STRING, 'weShopVatProvince');
				$territory = we_base_request::_(we_base_request::STRING, 'weShopVatCountry') . ($province ? '-' . $province : '');

				$weShopVat = new we_shop_vat(we_base_request::_(we_base_request::INT, 'weShopVatId'), we_base_request::_(we_base_request::STRING, 'weShopVatText'), we_base_request::_(we_base_request::FLOAT, 'weShopVatVat'), we_base_request::_(we_base_request::FLOAT, 'weShopVatStandard'), $territory, we_base_request::_(we_base_request::STRING, 'weShopVatTextProvince'));

				if(($newId = we_shop_vats::saveWeShopVAT($weShopVat))){
					$weShopVat->id = $newId;
					unset($newId);
					$saveSuccess = true;
					$jsMessage = g_l('modules_shop', '[vat][save_success]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_NOTICE;
				} else {
					$jsMessage = g_l('modules_shop', '[vat][save_error]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_ERROR;
				}

				break;

			case 'deleteVat':
				if(we_shop_vats::deleteVatById(we_base_request::_(we_base_request::INT, 'weShopVatId'))){
					$jsMessage = g_l('modules_shop', '[vat][delete_success]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_NOTICE;
				} else {
					$jsMessage = g_l('modules_shop', '[vat][delete_error]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_ERROR;
				}
				break;
		}


		if(!isset($weShopVat)){
			$weShopVat = new we_shop_vat(0, g_l('modules_shop', '[vat][new_vat_name]'), 19, 0);
		}

// at top of page show a table with all actual vats
		$allVats = we_shop_vats::getAllShopVATs();

		$vatJavaScript = '
WE().consts.g_l.shop.vat_confirm_delete="' . g_l('modules_shop', '[vat][js_confirm_delete]') . '";
var allVats = {
	vat_0: {
		id:0,
		text:"' . g_l('modules_shop', '[vat][new_vat_name]') . '",
		vat:19,
		standard:0,
		country:"DE",
		province:"",
		textProvince:""
	}
};';
		if($allVats){
			$vatTable = '
	<div style="height:300px; width: 550px; padding-right: 40px; overflow:auto;">
		<table class="defaultfont">
		<tr>
			<td><strong>Id</strong></td>
			<td><strong>' . g_l('modules_shop', '[vat][vat_form_name]') . '</strong></td>
			<td><strong>' . g_l('modules_shop', '[vat][vat_form_vat]') . '</strong></td>
			<td><strong>' . g_l('modules_shop', '[vat][vat_form_StateRegion]') . '</strong></td>
			<td><strong>ISO</strong></td>
			<td><strong>' . g_l('modules_shop', '[vat][vat_form_standard]') . '</strong></td>
		</tr>';

			foreach($allVats as $weShopVat){
				$vatJavaScript .='
		allVats["vat_' . $weShopVat->id . '"] = {"id":"' . $weShopVat->id . '","text":"' . $weShopVat->getNaturalizedText() . '", "vat":"' . $weShopVat->vat . '", "standard":"' . ($weShopVat->standard ? 1 : 0) . '", "territory":"' . $weShopVat->territory . '", "country":"' . $weShopVat->country . '", "province":"' . $weShopVat->province . '", "textProvince":"' . $weShopVat->textProvince . '"};';

				$vatTable .= '
		<tr>
			<td>' . $weShopVat->id . '</td>
			<td>' . oldHtmlspecialchars($weShopVat->getNaturalizedText()) . '</td>
			<td>' . $weShopVat->vat . '%</td>
			<td>' . $weShopVat->textTerritory . '</td>
			<td>' . $weShopVat->territory . '</td>
			<td>' . g_l('global', ($weShopVat->standard ? '[yes]' : '[no]')) . '</td>
			<td>' . we_html_button::create_button(we_html_button::EDIT, 'javascript:we_cmd(\'edit\',\'' . $weShopVat->id . '\');') . '</td>
			<td>' . we_html_button::create_button(we_html_button::TRASH, 'javascript:we_cmd(\'delete\',\'' . $weShopVat->id . '\');') . '</td>
		</tr>';
				unset($weShopVat);
			}

			$vatTable .= '</table>
	</div>';
		} else {
			$vatTable = '';
		}

		$plusBut = we_html_button::create_button(we_html_button::PLUS, 'javascript:we_cmd(\'addVat\')');

// formular to edit the vats
		$selPredefinedNames = we_html_tools::htmlSelect(
				'sel_predefinedNames', array_merge(['---'], we_shop_vat::getPredefinedNames()), 1, 0, false, ['onchange' => "var elem=document.getElementById('weShopVatText');elem.value=this.options[this.selectedIndex].text;this.selectedIndex=0"]
		);

		$formVat = '
<form name="we_form" method="post" action="' . WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=edit_shop_vats">
<input type="hidden" name="weShopVatId" id="weShopVatId" value="' . $weShopVat->id . '" />
<input type="hidden" name="onsaveclose" value="0" />
<input type="hidden" name="we_cmd[0]" value="saveVat" />
<table class="defaultfont" id="editShopVatForm" style="display:none;">
<tr>
	<td colspan="2"><strong>' . g_l('modules_shop', '[vat][vat_edit_form_headline]') . '</strong></td>
</tr>
<tr>
	<td style="width:100px">' . g_l('modules_shop', '[vat][vat_form_name]') . ':</td>
	<td><input class="wetextinput" type="text" id="weShopVatText" name="weShopVatText" value="' . $weShopVat->text . '" />' . $selPredefinedNames . '</td>
</tr>
<tr>
	<td>' . g_l('modules_shop', '[vat][vat_form_vat]') . ':</td>
	<td><input class="wetextinput" type="text" id="weShopVatVat" name="weShopVatVat" value="' . $weShopVat->vat . '" onkeypress="return WE().util.IsDigit(event);" />%</td>
</tr>

<tr>
	<td>' . g_l('modules_shop', '[vat][vat_edit_form_state]') . ':</td>
	<td>' . we_html_tools::htmlSelectCountry('weShopVatCountry', '', 1, [], false, array('id' => 'weShopVatCountry'), 200) . '</td>
</tr>
<tr>
	<td>' . g_l('modules_shop', '[vat][vat_edit_form_province]') . ':</td>
	<td>(-<input style="width:6em" class="wetextinput" type="text" id="weShopVatProvince" name="weShopVatProvince" value="" />)</td>
</tr>

<tr>
	<td>' . g_l('modules_shop', '[vat][vat_form_standard]') . ':</td>
	<td><select id="weShopVatStandard" name="weShopVatStandard">
			<option value="1"' . ($weShopVat->standard ? ' selected="selected"' : '') . '>' . g_l('modules_shop', '[vat][vat_edit_form_yes]') . '</option>
			<option value="0"' . ($weShopVat->standard ? '' : ' selected="selected"') . '>' . g_l('modules_shop', '[vat][vat_edit_form_no]') . '</option>
		</select>
	</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td>' . we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd(\'save_notclose\');') . ' ' . we_html_button::create_button(we_html_button::CANCEL, 'javascript:we_cmd(\'cancel_notclose\');') . '</td>
</tr>
</table>
</form>';

		$parts = [
			['html' => $vatTable],
			['html' => $plusBut],
			['html' => $formVat,],
		];

		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement(
					$vatJavaScript .
					(isset($jsMessage) ? we_message_reporting::getShowMessageCall($jsMessage, $jsMessageType) . ($saveSuccess && $onsaveClose ? 'window.close()' : '') : '')) . we_html_element::jsScript(JS_DIR . 'we_modules/shop/edit_shop_vats.js'), we_html_element::htmlBody(['class' => 'weDialogBody', 'onload' => "window.focus();addListeners();"], we_html_multiIconBox::getHTML('weShopVates', $parts, 30, we_html_button::formatButtons(we_html_button::create_button(we_html_button::CLOSE, 'javascript:we_cmd(\'close\');')), -1, '', '', false, g_l('modules_shop', '[vat][vat_edit_form_headline_box]'), "", ''
		)));
	}

	private static function showVatCountryDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);
		$DB_WE = $GLOBALS['DB_WE'];

		$jsFunction = '
function we_submitForm(url){
var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}

	f.action = url;
	f.method = "post";

	f.submit();
	return true;
}

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd(){
var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "close":
			window.close();
			break;
		case "save":
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_vat_country");
			break;
		default:
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}';

// initialise the vatRuleObject
		if(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0) === 'saveVatRule'){

			// initialise the vatRule by request
			$weShopVatRule = we_shop_vatRule::initByRequest();
			$weShopVatRule->save();
		} else {
			$weShopVatRule = we_shop_vatRule::getShopVatRule();
		}

// array with all rules

		$customerTableFields = $DB_WE->metadata(CUSTOMER_TABLE);
		foreach($customerTableFields as $tblField){
			$selectFields[$tblField['name']] = $tblField['name'];
		}

// default value f�r mwst
		$defaultInput = we_class::htmlSelect('defaultValue', array('true' => 'true', 'false' => 'false'), 1, $weShopVatRule->defaultValue);
// select field containing land
		$countrySelect = we_class::htmlSelect('stateField', $selectFields, 1, $weShopVatRule->stateField);
		$countrySelectISO = we_html_forms::checkboxWithHidden($weShopVatRule->stateFieldIsISO, 'stateFieldIsISO', g_l('modules_shop', '[preferences][ISO-Kodiert]'), false, "defaultfont");
// states which must always pay vat

		$textAreaLiableStates = we_class::htmlTextArea('liableToVat', 3, 30, implode("\n", $weShopVatRule->liableToVat));
// states which must never pay vat

		$textAreaNotLiableStates = we_class::htmlTextArea('notLiableToVat', 3, 30, implode("\n", $weShopVatRule->notLiableToVat));
// states which must only pay under certain circumstances
// if we make more rules possible - adjust here
		$actCondition = $weShopVatRule->conditionalRules[0];

		$conditionTextarea = we_class::htmlTextArea('conditionalStates[]', 3, 30, implode("\n", $actCondition['states']));
		$conditionField = we_class::htmlSelect('conditionalCustomerField[]', $selectFields, 1, $actCondition['customerField']);
		$conditionSelect = we_class::htmlSelect('conditionalCondition[]', array('is_empty' => g_l('modules_shop', '[vat_country][condition_is_empty]'), 'is_set' => g_l('modules_shop', '[vat_country][condition_is_set]')), 1, $actCondition['condition']);
		$conditionReturn = we_class::htmlSelect('conditionalReturn[]', array('false' => 'false', 'true' => 'true'), 1, $actCondition['returnValue']);

		$parts = [
			['headline' => g_l('modules_shop', '[vat_country][defaultReturn]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $defaultInput,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[vat_country][defaultReturn_desc]'), we_html_tools::TYPE_INFO, 600),
			],
			['headline' => g_l('modules_shop', '[vat_country][stateField]') . ':',
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $countrySelect . $countrySelectISO,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[vat_country][stateField_desc]'), we_html_tools::TYPE_INFO, 600),
			],
			['headline' => g_l('modules_shop', '[vat_country][statesLiableToVat]') . ':',
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $textAreaLiableStates,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[vat_country][statesLiableToVat_desc]'), we_html_tools::TYPE_INFO, 600),
			],
			['headline' => g_l('modules_shop', '[vat_country][statesNotLiableToVat]') . ':',
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $textAreaNotLiableStates,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[vat_country][statesNotLiableToVat_desc]'), we_html_tools::TYPE_INFO, 600),
			],
			['headline' => g_l('modules_shop', '[vat_country][statesSpecialRules]') . ':',
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $conditionTextarea,
				'noline' => 1
			],
			['html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[vat_country][statesSpecialRules_desc]'), we_html_tools::TYPE_INFO, 600),
				'noline' => 1
			],
			['headline' => g_l('modules_shop', '[vat_country][statesSpecialRules_condition]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $conditionField . ' ' . $conditionSelect,
				'noline' => 1
			],
			['headline' => g_l('modules_shop', '[vat_country][statesSpecialRules_result]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $conditionReturn
			]
		];

		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement($jsFunction), we_html_element::htmlBody(['class' => "weDialogBody", 'onload' => "window.focus();"], '<form name="we_form" method="post" action="' . WEBEDITION_DIR . 'we_showMod.php?mod=shop&pnt=edit_shop_vat_country">
		<input type="hidden" name="we_cmd[0]" value="saveVatRule" />' .
					we_html_multiIconBox::getHTML('weShopCountryVat', $parts, 30, we_html_button::position_yes_no_cancel(
							we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd(\'save\');'), '', we_html_button::create_button(we_html_button::CANCEL, 'javascript:we_cmd(\'close\');')
						), -1, '', '', false, g_l('modules_shop', '[vat_country][box_headline]'), '', 741
					) . '</form>'));
	}

	private static function showCategoriesDialog(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);

		$DB_WE = $GLOBALS['DB_WE'];
//FIXME: mak sowme view class for this editor and use processVariables() and processCommands()?
//process request
		$shopCategoriesDir = ($val = we_base_request::_(we_base_request::INT, 'weShopCatDir', false)) !== false ? $val : we_shop_category::getShopCatDir();
		$relations = [];
		$saveSuccess = false;
		$onsaveClose = we_base_request::_(we_base_request::BOOL, 'onsaveclose', false);

		if($shopCategoriesDir !== -1 && we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0) === 'saveShopCatRels'){
			$saveSuccess = we_shop_category::saveShopCatsDir($shopCategoriesDir);

			$destPrincipleIds = [];
			foreach(we_base_request::_(we_base_request::INT, 'weShopCatDestPrinciple', []) as $k => $v){
				if($v){
					$destPrincipleIds[] = intval($k);
				}
			}
			$saveSuccess &= we_shop_category::saveSettingDestPrinciple(implode(',', $destPrincipleIds));

			//FIXME: get destPrinciple and isActive from db at once
			$isInactiveIds = [];
			foreach(we_base_request::_(we_base_request::INT, 'weShopCatIsActive', []) as $k => $v){
				if(!$v){
					$isInactiveIds[] = intval($k);
				}
			}
			$saveSuccess &= we_shop_category::saveSettingIsInactive(implode(',', $isInactiveIds));

			$saveCatIds = [];
			$relations = we_base_request::_(we_base_request::STRING, 'weShopCatRels');
			foreach($relations as $k => $v){
				foreach($v as $id){
					if(!isset($saveCatIds[$id])){
						$saveCatIds[$id] = [];
					}
					$saveCatIds[$id][] = intval($k);
				}
			}

			//reset all vat-category relations before saving the new set of relations
			$saveSuccess &= $DB_WE->query('UPDATE ' . WE_SHOP_VAT_TABLE . ' SET categories=""');
			foreach($saveCatIds as $vatId => $catIds){
				$saveSuccess &= $DB_WE->query('UPDATE ' . WE_SHOP_VAT_TABLE . ' SET categories="' . implode(',', $catIds) . '" WHERE id=' . intval($vatId));
			}

			if($saveSuccess){
				$jsMessage = g_l('modules_shop', '[shopcats][save_success]');
				$jsMessageType = we_message_reporting::WE_MESSAGE_NOTICE;
			} else {
				$jsMessage = g_l('modules_shop', '[shopcats][save_error]');
				$jsMessageType = we_message_reporting::WE_MESSAGE_ERROR;
			}
		} else {
			//please select category dir...
		}

//make category dirs select
		$DB_WE->query('SELECT ID,Path FROM ' . CATEGORY_TABLE . ' ORDER BY Path');
		$allCategoryDirs = array('-1' => g_l('modules_shop', '[shopcats][select_shopCatDir]'));
		while($DB_WE->next_record()){
			$data = $DB_WE->getRecord();
			$allCategoryDirs[$data['ID']] = $data['Path'];
		}
		$selCategoryDirs = we_html_tools::htmlSelect('weShopCatDir', $allCategoryDirs, 1, $shopCategoriesDir, false, array('id' => 'weShopCatDir', 'onchange' => 'we_submitForm(WE().consts.dirs.WEBEDITION_DIR + \'we_showMod.php?mod=shop&pnt=edit_shop_categories\');'));

		if($shopCategoriesDir && intval($shopCategoriesDir) !== -1){
			$allVats = we_shop_vats::getAllShopVATs();
			$vatGroups = [];
			if(count($allVats) > 0){
				$doWriteRelations = !$relations ? true : false;
				foreach($allVats as $vatObj){
					if(!isset($vatGroups[$vatObj->territory])){
						$vatGroups[$vatObj->territory] = [];
						$vatGroups[$vatObj->territory]['selOptions'] = array(0 => ' ');
					}
					$vatGroups[$vatObj->territory]['textTerritory'] = $vatObj->textTerritory;
					$vatGroups[$vatObj->territory]['selOptions'][$vatObj->id] = $vatObj->getNaturalizedText() . ': ' . $vatObj->vat . '%';

					if($doWriteRelations){
						foreach($catArr = explode(',', $vatObj->categories) as $cat){
							if($cat){
								if(!isset($relations[$cat])){
									$relations[$cat] = [];
								}
								$relations[$cat][$vatObj->territory] = $vatObj->id;
							}
						}
					}
				}
			}

			$shopCategories = we_shop_category::getShopCatFieldsFromDir('', false, true, $shopCategoriesDir, true, true, true, '', 'Path');
			$catsTable = new we_html_table(['class' => 'withSpace'], (count($shopCategories) * 6), 5);
			$catsDirTable = new we_html_table(['class' => 'withSpace'], 7, 5);
			if(is_array($shopCategories) && count($shopCategories) > 1){
				$i = $iTmp = 0;

				foreach($shopCategories as $k => $cat){
					$table = $catsTable;
					$isShopCatsDir = false;
					if($cat['ID'] == $shopCategoriesDir){
						$isShopCatsDir = true;
						$table = $catsDirTable;
						$iTmp = $i;
						$i = 0;
					}

					$table->setCol($i, 1, array('class' => "defaultfont bold", "width" => 140), '<abbr title="ShopCatID: ' . $cat['ID'] . '">' . $cat['Category'] . '</abbr>');
					$table->setCol($i, 2, array('class' => 'defaultfont', "width" => 20));
					$table->setCol($i++, 3, array('class' => 'defaultfont bold', 'colspan' => 2, 'width' => 174), $cat['Path']);
					if($cat['ID'] != $shopCategoriesDir){
						$table->setCol($i, 3, array('class' => 'defaultfont', 'width' => 174), g_l('modules_shop', '[shopcats][active_shopCat]'));
						$table->setCol($i++, 4, array('class' => 'defaultfont', 'width' => 240), we_html_forms::checkboxWithHidden(($cat['IsInactive'] == 0), 'weShopCatIsActive[' . $cat['ID'] . ']', '', false, '', 'we_switch_active_by_id(' . $cat['ID'] . ')'));
					}

					//set attribute $unique for radio button to 'true' for corret labels
					$taxPrinciple = we_html_forms::radioButton(0, ($cat['DestPrinciple'] == 0 ? '1' : '0'), 'weShopCatDestPrinciple[' . $cat['ID'] . ']', g_l('modules_shop', '[shopcats][text_originPrinciple]'), true, 'defaultfont', 'we_switch_principle_by_id(' . $cat['ID'] . ', this, ' . ($isShopCatsDir ? 'true' : 'false') . ')') .
						we_html_forms::radioButton(1, ($cat['DestPrinciple'] == 1 ? '1' : '0'), 'weShopCatDestPrinciple[' . $cat['ID'] . ']', g_l('modules_shop', '[shopcats][text_destPrinciple]'), true, 'defaultfont', 'we_switch_principle_by_id(' . $cat['ID'] . ', this, ' . ($isShopCatsDir ? 'true' : 'false') . ')') .
						we_html_element::htmlHidden('taxPrinciple_tmp[' . $cat['ID'] . ']', $cat['DestPrinciple'], 'taxPrinciple_tmp[' . $cat['ID'] . ']');

					$table->setRow($i, ['id' => 'destPrincipleRow_' . $cat['ID'], 'style' => ($cat['IsInactive'] == 1 ? 'display: none;' : '')]);
					$table->setCol($i, 3, ['class' => 'defaultfont', 'width' => 174, 'style' => 'padding-bottom: 10px'], g_l('modules_shop', '[shopcats][title_taxationMode]'));
					$table->setCol($i++, 4, ['class' => 'defaultfont', 'width' => 240, 'style' => 'padding-bottom: 10px'], $taxPrinciple);

					if(!count($allVats)){
						$table->setCol($i, 3, ['class' => 'defaultfont', 'width' => 140], g_l('modules_shop', '[shopcats][warning_noVatsDefined]'));
					} else {
						$holderCountryTable = new we_html_table(['class' => 'default'], 1, 2);
						$countriesTable = new we_html_table(['class' => 'default'], max((count($allVats) - 1), 1), 2);

						$c = -1;
						foreach($vatGroups as $k => $v){
							if(we_shop_category::getDefaultCountry() == $k){
								$innerTable = $holderCountryTable;
								$num = 0;
								$isDefCountry = true;
							} else {
								$innerTable = $countriesTable;
								$c++;
								$num = $c;
								$isDefCountry = false;
							}

							$value = !empty($relations[$cat['ID']][$k]) ? $relations[$cat['ID']][$k] : 0;
							$selAttribs = array('id' => 'weShopCatRels[' . $cat['ID'] . '][' . $k . ']');
							$sel = we_html_tools::htmlSelect('weShopCatRels[' . $cat['ID'] . '][' . $k . ']', $v['selOptions'], 1, $value, false, $selAttribs, 'value', 220);

							$innerTable->setCol($num, 0, array('class' => 'defaultfont' . ($isDefCountry ? ' bold' : ''), 'width' => 184, 'style' => ($isDefCountry ? '' : 'padding-bottom: 8px;')), ($v['textTerritory'] ? : 'N.N.'));
							$innerTable->setCol($num, 1, array('class' => 'defaultfont', 'width' => 220), $sel);
						}
					}
					$table->setRow($i, array('id' => 'defCountryRow_' . $cat['ID'], 'style' => ($cat['IsInactive'] == 0 ? '' : 'display: none;')));
					$table->setCol($i++, 3, array('class' => 'defaultfont', 'colspan' => 2, 'width' => 424), $holderCountryTable->getHtml());
					$table->setRow($i, array('id' => 'countriesRow_' . $cat['ID'], 'style' => ($cat['IsInactive'] == 1 || $cat['DestPrinciple'] == 0 ? 'display: none;' : '')));
					$table->setCol($i++, 3, array('class' => 'defaultfont', 'colspan' => 2, 'width' => 424), $countriesTable->getHtml());

					$table->setCol($i, 1, array('class' => 'defaultfont', 'width' => 20), '');
					$table->setCol($i++, 2, array('style' => 'padding-bottom: 20px', 'class' => 'defaultfont', 'width' => 140), '');

					$i = $cat['ID'] == $shopCategoriesDir ? $iTmp : $i;
				}
				$catsTableHtml = $catsTable->getHtml();
				$catsDirTableHtml = $catsDirTable->getHtml();
			} else {
				$catsTableHtml = g_l('modules_shop', '[shopcats][warning_shopCatDirEmpty]');
				$catsDirTableHtml = g_l('modules_shop', '[shopcats][warning_shopCatDirEmpty]');
			}
		} else {
			$catsTableHtml = $catsDirTableHtml = g_l('modules_shop', '[shopcats][warning_noShopCatDir]');
		}


		$jsFunction = '
	var hot = 0;

	function addListeners(){
		for(var i = 1; i < document.we_form.elements.length; i++){
			document.we_form.elements[i].addEventListener("change",function(){
				hot = 1;
			});
		}
	}

	function we_submitForm(url){
		var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
		f.action = url;
		f.method = "post";
		f.submit();
		return true;
	}

	function doUnload() {
		WE().util.jsWindow.prototype.closeAll(window);
	}

function we_cmd(){
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

		switch (args[0]) {
			case "close":
				if(hot){
					new (WE().util.jsWindow)(this, WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=exitQuestion","we_exit_doc_question",-1,-1,380,130,true,false,true);
				} else {
					window.close();
				}
				break;
			case "save":
				document.we_form["we_cmd[0]"].value = "saveShopCatRels";
				document.we_form.onsaveclose.value = 1;
				we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_categories");
				break;
			case "save_notclose":
				document.we_form["we_cmd[0]"].value = "saveShopCatRels";
				we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_categories");
				break;
			default:
				top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
		}
	}

	function we_switch_active_by_id(id){
		try{
			document.getElementById("destPrincipleRow_" + id).style.display =
				document.getElementById("defCountryRow_" + id).style.display =
				(document.getElementById("check_weShopCatIsActive[" + id + "]").checked) ? "" : "none";

			document.getElementById("countriesRow_" + id).style.display =
				document.getElementById("check_weShopCatIsActive[" + id + "]").checked &&
				(document.getElementById("taxPrinciple_tmp[" + id + "]").value == 1) ? "" : "none";
		} catch(e){}
	}

	function we_switch_principle_by_id(id, obj, isShopCatsDir){
		try{
			var active = isShopCatsDir ? true : document.getElementById("check_weShopCatIsActive[" + id + "]").checked;

			document.getElementById("taxPrinciple_tmp[" + id + "]").value = obj.value;
			document.getElementById("countriesRow_" + id).style.display =
				(active && obj.value == 1) ? "" : "none";
		} catch(e){}
	}

	' . (isset($jsMessage) ? we_message_reporting::getShowMessageCall($jsMessage, $jsMessageType) . ($saveSuccess && $onsaveClose ? 'window.close()' : '') : '');

		$parts = [
			['headline' => g_l('modules_shop', '[shopcats][text_shopCatDir]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => $selCategoryDirs,
			],
			['headline' => g_l('modules_shop', '[shopcats][text_editShopCatDir]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => '',
				'noline' => 1
			],
			['headline' => '',
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[shopcats][info_edit_shopCatDir]'), we_html_tools::TYPE_INFO, '614', false, 100),
				'noline' => 1
			],
			['headline' => '',
				'html' => $catsDirTableHtml,
			],
			['headline' => g_l('modules_shop', '[shopcats][text_editShopCats]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => '',
				'noline' => 1
			],
			['headline' => '',
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('modules_shop', '[shopcats][info_editShopCats]'), we_html_tools::TYPE_INFO, "614", false, 101),
				'noline' => 1
			],
			['headline' => '',
				'html' => $catsTableHtml,
				'noline' => 1
			],
			['headline' => '',
			//'html' => $debug_output
		]];

		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement($jsFunction), we_html_element::htmlBody([ 'class' => "weDialogBody", 'onload' => "window.focus(); addListeners();"], '<form name="we_form" method="post" >
	<input type="hidden" name="we_cmd[0]" value="load" /><input type="hidden" name="onsaveclose" value="0" />' .
					we_html_multiIconBox::getHTML('weShopCategories', $parts, 30, we_html_button::position_yes_no_cancel(
							we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd(\'save_notclose\');'), '', we_html_button::create_button(we_html_button::CLOSE, 'javascript:we_cmd(\'close\');')
						), -1, '', '', false, g_l('modules_shop', '[shopcats][title_editorShopCats]'), '', '', 'scroll'
					) . '</form>'));
	}

	private static function showDialogShipping(){
		$protect = we_base_moduleInfo::isActive(we_base_moduleInfo::SHOP) && we_users_util::canEditModule(we_base_moduleInfo::SHOP) ? null : array(false);
		we_html_tools::protect($protect);
		$DB_WE = $GLOBALS['DB_WE'];
		$weShippingControl = we_shop_shippingControl::getShippingControl();

		switch(we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0)){
			case 'newShipping':
				$weShipping = we_shop_shipping::getNewEmptyShipping();
				break;

			case 'editShipping':
				$weShipping = $weShippingControl->getShippingById(we_base_request::_(we_base_request::STRING, 'weShippingId'));
				break;

			case 'deleteShipping':
				$weShippingControl->delete(we_base_request::_(we_base_request::STRING, 'weShippingId'));
				break;

			case 'saveShipping':
				$weShippingControl->setByRequest($_REQUEST); //FIXME: bad this is unchecked!!!
				if($weShippingControl->save()){
					$jsMessage = g_l('modules_shop', '[shipping][save_success]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_NOTICE;
				} else {
					$jsMessage = g_l('modules_shop', '[shipping][save_error]');
					$jsMessageType = we_message_reporting::WE_MESSAGE_ERROR;
				}
				if(($sid = we_base_request::_(we_base_request::STRING, 'weShippingId')) !== false){
					$weShipping = $weShippingControl->getShippingById($sid);
				}
				break;
		}

// show shippingControl
// first show fields: country, vat, isNet?

		$customerTableFields = $DB_WE->metadata(CUSTOMER_TABLE);
		$selectFieldsCtl = $selectFieldsVat = $selectFieldsTbl = [];
		foreach($customerTableFields as $tblField){
			$selectFieldsTbl[$tblField['name']] = $tblField['name'];
		}
		$shopVats = we_shop_vats::getAllShopVATs();
		foreach($shopVats as $shopVat){ //Fix #9625 use shopVat->Id as key instead of the sorted array $id!
			$selectFieldsVat[$shopVat->id] = $shopVat->vat . '% - ' . $shopVat->getNaturalizedText() . ' (' . $shopVat->territory . ')';
		}
// selectBox with all existing shippings
// select menu with all available shipping costs
		foreach($weShippingControl->shippings as $key => $shipping){
			$selectFieldsCtl[$key] = $shipping->text;
		}

		$parts = [
			['headline' => g_l('modules_shop', '[vat_country][stateField]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_class::htmlSelect('stateField', $selectFieldsTbl, 1, $weShippingControl->stateField, false, [], 'value', 280),
				'noline' => 1
			],
			['headline' => g_l('modules_shop', '[mwst]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_class::htmlSelect('vatId', $selectFieldsVat, 1, $weShippingControl->vatId, false, [], 'value', 280),
				'noline' => 1
			],
			['headline' => g_l('modules_shop', '[shipping][prices_are_net]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_class::htmlSelect('isNet', array(1 => g_l('global', '[true]'), 0 => g_l('global', '[false]')), 1, $weShippingControl->isNet, false, [], 'value', 280)
			],
			['headline' => g_l('modules_shop', '[shipping][insert_packaging]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => '<table class="default defaultfont">
	<tr>
		<td>' . we_class::htmlSelect('editShipping', $selectFieldsCtl, 4, we_base_request::_(we_base_request::RAW, 'weShippingId', ''), false, array('onchange' => 'document.location=WE().consts.dirs.WEBEDITION_DIR + \'we_showMod.php?mod=shop&pnt=edit_shop_shipping&we_cmd[0]=editShipping&weShippingId=\' + this.options[this.selectedIndex].value;'), 'value', 280) . '</td>
		<td style="width:10px;"></td>
		<td style="vertical-align:top">'
				. we_html_button::create_button('new_entry', 'javascript:we_cmd(\'newEntry\');') .
				'<div style="margin:5px;"></div>' .
				we_html_button::create_button(we_html_button::DELETE, 'javascript:we_cmd(\'delete\')') .
				'</td>
	</tr>
	</table>'
			]
		];


// if a shipping should be edited, show it in a form

		if(isset($weShipping)){ // show the shipping which must be edited
			$parts[] = ['headline' => g_l('modules_shop', '[shipping][name]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_html_tools::htmlTextInput('weShipping_text', 24, $weShipping->text) . we_html_element::htmlHidden('weShippingId', $weShipping->id),
				'noline' => 1
			];
			$parts[] = ['headline' => g_l('modules_shop', '[shipping][countries]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_class::htmlTextArea('weShipping_countries', 4, 21, implode("\n", $weShipping->countries)),
				'noline' => 1
			];
			// foreach ...
			// form table with every value -> cost entry
			if($weShipping->shipping){

				$tblPart = '';
				for($i = 0; $i < count($weShipping->shipping); $i++){

					$tblRowName = 'weShippingId_' . $i;

					$tblPart .= '
			<tr id="' . $tblRowName . '">
				<td>' . we_html_tools::htmlTextInput('weShipping_cartValue[]', 24, $weShipping->cartValue[$i], '', 'onkeypress="return WE().util.IsDigit(event);"') . '</td>
				<td></td>
				<td>' . we_html_tools::htmlTextInput('weShipping_shipping[]', 20, $weShipping->shipping[$i], '', 'onkeypress="return WE().util.IsDigit(event);"') . '</td>
				<td></td>
				<td>' . we_html_button::create_button(we_html_button::TRASH, "we_cmd('deleteShippingCostTableRow','" . $tblRowName . "');") . '</td>
			</tr>';
				}
			}

			$parts[] = ['headline' => g_l('modules_shop', '[shipping][costs]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' =>
				'<table style="width:100%" class="default defaultfont" id="shippingCostTable">
		<tr>
			<td><b>' . g_l('modules_shop', '[shipping][order_value]') . '</b></td>
			<td style="width:10px;"></td>
			<td><b>' . g_l('modules_shop', '[shipping][shipping_costs]') . '</b></td>
			<td style="width:10px"></td>
		</tr>
		<tbody id="shippingCostTableEntries">
	' . $tblPart . '
		</tbody>
	</table>' .
				we_html_button::create_button(we_html_button::PLUS, 'javascript:we_cmd(\'addShippingCostTableRow\',\'12\');'),
				'noline' => 1
			];
			$parts[] = ['headline' => 'Standard',
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_class::htmlSelect('weShipping_default', array(1 => g_l('global', '[true]'), 0 => g_l('global', '[false]')), 1, $weShipping->default),
				'noline' => 1
			];
		}


		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsElement('
function closeOnEscape() {
	return true;
}

function doUnload() {
	WE().util.jsWindow.prototype.closeAll(window);
}

function we_cmd(){
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "save":
			we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_shipping");
			break;
		case "close":
			window.close();
			break;
		case "delete":
			if (confirm("' . g_l('modules_shop', '[delete][shipping]') . '")) {
				var we_cmd_field = document.getElementById("we_cmd_field");
				we_cmd_field.value = "deleteShipping";
				we_submitForm(WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_shipping");

			}
			break;
		case "newEntry":
			document.location = WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=shop&pnt=edit_shop_shipping&we_cmd[0]=newShipping";
			break;
		case "addShippingCostTableRow":
			addShippingCostTableRow();
			break;
		case "deleteShippingCostTableRow":
			deleteShippingCostTableRow(args[1]);
			break;
		default :
			top.opener.top.we_cmd.apply(this, Array.prototype.slice.call(arguments));
			break;
	}
}

// this is for new entries.
var entryPosition = 0;

function addShippingCostTableRow() {

	tbl = document.getElementById("shippingCostTableEntries");

	entryId = "New" + "" + entryPosition++;

	theNewRow = document.createElement("TR");
	theNewRow.setAttribute("id", "weShippingId_" + entryId);

	var cell1 = document.createElement("TD");
	cell1.innerHTML=\'<input class="wetextinput" type="text" name="weShipping_cartValue[]" size="24" />\';
			var cell2 = document.createElement("TD");
	var cell3 = document.createElement("TD");
	cell3.innerHTML=\'<input class="wetextinput" type="text" name="weShipping_shipping[]" size="24" />\';
	var cell4 = document.createElement("TD");
	var cell5 = document.createElement("TD");

	var tmp=\'' . addslashes(we_html_button::create_button(we_html_button::TRASH, "we_cmd('deleteShippingCostTableRow', 'weShippingId_#####placeHolder#####');")) . '\';

cell5.innerHTML=tmp.replace("#####placeHolder#####",entryId);
	theNewRow.appendChild(cell1);
	theNewRow.appendChild(cell2);
	theNewRow.appendChild(cell3);
	theNewRow.appendChild(cell4);
	theNewRow.appendChild(cell5);

	// append new row
	tbl.appendChild(theNewRow);
}

function deleteShippingCostTableRow(rowId) {
	tbl = document.getElementById("shippingCostTable");
	tableRows = tbl.rows;

	for (i=0;i<tableRows.length;i++) {
		if(rowId == tableRows[i].id) {
			tbl.deleteRow(i);
		}
	}
}

function we_submitForm(url){
	var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
	f.action = url;
	f.method = "post";

	f.submit();
	return true;
}' .
					(isset($jsMessage) ? we_message_reporting::getShowMessageCall($jsMessage, $jsMessageType) : '')
				), we_html_element::htmlBody([ 'class' => "weDialogBody", 'onload' => "window.focus();"], '<form name="we_form">
		<input type="hidden" id="we_cmd_field" name="we_cmd[0]" value="saveShipping" />' .
					we_html_multiIconBox::getHTML('weShipping', $parts, 30, we_html_button::position_yes_no_cancel(
							we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd(\'save\');'), '', we_html_button::create_button(we_html_button::CLOSE, 'javascript:we_cmd(\'close\');')
						), -1, '', '', false, g_l('modules_shop', '[shipping][shipping_package]')
					) . '</form>'));
	}

}
