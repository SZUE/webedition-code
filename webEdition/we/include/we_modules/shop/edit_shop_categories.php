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
//TODO: make read, save and process relations-data more concise

require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
$protect = we_base_moduleInfo::isActive('shop') && we_users_util::canEditModule('shop') ? null : array(false);
we_html_tools::protect($protect);

//FIXME: mak sowme view class for this editor and use processVariables() and processCommands()?
//process request
$shopCategoriesDir = ($val = we_base_request::_(we_base_request::INT, 'weShopCatDir', false)) !== false ? $val : we_shop_category::getShopCatDir();//(f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="shop" AND pref_name="shop_cats_dir"', '', $DB_WE, -1));
$relations = array();

if($shopCategoriesDir !== -1 && we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0) === 'saveShopCatRels'){
		$success = we_shop_category::saveShopCatsDir($shopCategoriesDir);

		$destPrincipleIds = array();
		foreach(we_base_request::_(we_base_request::INT, 'weShopCatDestPrinciple', array()) as $k => $v){
			if($v){
				$destPrincipleIds[] = intval($k);
			}
		}
		$success &= we_shop_category::saveSettingDestPrinciple(implode(',', $destPrincipleIds));

		//FIXME: get destPrinciple and isActive from db at once
		$isActiveIds = array();
		foreach(we_base_request::_(we_base_request::INT, 'weShopCatIsActive', array()) as $k => $v){
			if($v){
				$isActiveIds[] = intval($k);
			}
		}
		$success &= we_shop_category::saveSettingIsActive(implode(',', $isActiveIds));

		$saveCatIds = array();
		$relations = we_base_request::_(we_base_request::STRING, 'weShopCatRels');
		foreach($relations as $k => $v){
			foreach($v as $id){
				if(!isset($saveCatIds[$id])){
					$saveCatIds[$id] = array();
				}
				$saveCatIds[$id][] = intval($k);
			}
		}

		//reset all vat-category relations before saving the new set of relations
		$success &= $DB_WE->query('UPDATE ' . WE_SHOP_VAT_TABLE . ' SET categories=""');
		foreach($saveCatIds as $vatId => $catIds){
			$success &= $DB_WE->query('UPDATE ' . WE_SHOP_VAT_TABLE . ' SET categories="' . implode(',', $catIds) . '" WHERE id=' . intval($vatId));
		}

		if($success){
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
$DB_WE->query('SELECT ID,Path FROM ' . CATEGORY_TABLE . ' WHERE IsFolder = 1 ORDER BY Path');
$allCategoryDirs = array('-1' => 'bitte wählen');//GL
while($DB_WE->next_record()){
	$data = $DB_WE->getRecord();
	$allCategoryDirs[$data['ID']] = $data['Path'];
}
$selCategoryDirs = we_html_tools::htmlSelect('weShopCatDir', $allCategoryDirs, 1, $shopCategoriesDir, false, array('id' => 'weShopCatDir', 'onchange' => 'we_submitForm(\'' . $_SERVER['SCRIPT_NAME'] . '\');'));

if(intval($shopCategoriesDir) !== -1){
	$shopCategories = we_shop_category::getShopCatFieldsFromDir('', true, $shopCategoriesDir, true, true, true, '', 'Path');

	//Categories/VATs-Matrix
	$DB_WE->query('SELECT id, text, vat, territory, textProvince, categories FROM ' . WE_SHOP_VAT_TABLE);
	$allVats = array();
	$doWriteRelations = !$relations ? true : false;

	while($DB_WE->next_record()){
		$data = $DB_WE->getRecord();

		if(!isset($allVats[$data['territory']])){
			$allVats[$data['territory']] = array();
			$allVats[$data['territory']]['selOptions'][0] = 'please select';//GL
		}

		$vat = new we_shop_vat($data['id'], $data['text'], $data['vat'], 0, $data['territory'], $data['textProvince']);
		$allVats[$data['territory']]['textTerritory'] = $vat->textTerritory;
		$allVats[$data['territory']]['selOptions'][$vat->id] = $vat->getNaturalizedText() . ': ' . $vat->vat . '%';

		if($doWriteRelations){
			$catArr = explode(',', $data['categories']);
			foreach($catArr = explode(',', $data['categories']) as $cat){
				if(!isset($relations[$cat])){
					$relations[$cat] = array();
				}
				$relations[$cat][$data['territory']] = $data['id'];
			}
		}
	}

	//FIXME: throw out row design variant when sure, we do not need it
	if(count($allVats) < 0){
		$catsDirMatrix = new we_html_table(array("border" => 0, "cellpadding" => 2, "cellspacing" => 4), (1 + 2 ), count($allVats) + 4);
		$catsMatrix = new we_html_table(array("border" => 0, "cellpadding" => 2, "cellspacing" => 4), (count($shopCategories) + 2 ), count($allVats) + 4);

		$i = 0;
		$j = 0;
		$catsDirMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 30), '');
		$catsMatrix->setCol(0, $j++, array("class" => "defaultfont", "style" => "font-weight:normal", "nowrap" => "nowrap", "width" => 30), '');//GL
		$catsDirMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 20), '');
		$catsMatrix->setCol(0, $j++, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 20), '');
		$catsDirMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), '');
		$catsMatrix->setCol(0, $j++, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), '');


		foreach($allVats as $v){
			$catsDirMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), $v['textTerritory']);
			$catsMatrix->setCol(0, $j++, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), $v['textTerritory']);
		}
		$catsDirMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), 'Destination Principle');//GL
		$catsMatrix->setCol(0, $j, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), 'Destination Principle');//GL

		//generate columns
		if(count($shopCategories)){
			$i = $iTmp = 1;

			foreach($shopCategories as $cat){
				$matrix = $catsMatrix;
				if($cat['ID'] == $shopCategoriesDir){
					$matrix = $catsDirMatrix;
					$iTmp = $i;
					$i = 1;
				}

				$j = 0;
				$matrix->setCol(++$i, $j++, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 30), ($cat['ID'] == $shopCategoriesDir ? '' : (we_html_forms::checkboxWithHidden(($cat['IsActive'] == 1), 'weShopCatIsActive[' . $cat['ID'] . ']', ''))));
				$matrix->setCol($i, $j++, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 20), ($cat['ID'] . ': '));
				$matrix->setCol($i, $j++, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), $cat['Category'] . ($cat['IsFolder'] ? '/' : ''));
				if(!count($allVats)){
					$matrix->setCol($i, $j, array("class" => "defaultfont", "style" => "font-weight:normal", "nowrap" => "nowrap", "width" => 110), 'no vats defined yet');//GL
				} else {
					foreach($allVats as $k => $v){
						$value = isset($relations[$cat['ID']][$k]) && $relations[$cat['ID']][$k] ? $relations[$cat['ID']][$k] : 0;
						$sel = we_html_tools::htmlSelect('weShopCatRels[' . $cat['ID'] . '][' . $k . ']', $v['selOptions'], 1, $value, false, array(), 'value', 180);
						$matrix->setCol($i, $j++, array("class" => "defaultfont", "style" => "font-weight:normal", "nowrap" => "nowrap", "width" => 110), $sel);
					}
				}
				$matrix->setCol($i, $j++, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 110), we_html_forms::checkboxWithHidden(($cat['DestPrinciple'] == 1), 'weShopCatDestPrinciple[' . $cat['ID'] . ']', ''));

				$i = $cat['ID'] == $shopCategoriesDir ? $iTmp : $i;
			}
			$catsDirMatrixHtml = $catsDirMatrix->getHtml();
			$catsMatrixHtml = $catsMatrix->getHtml();
			
		} else {
			$catsMatrixHtml = "Das ausgewählte Kategorie-Verzeichnis enthält keine Einträge.";//GL
		}
	} else {
		$catsMatrix = new we_html_table(array("border" => 0, "cellpadding" => 2, "cellspacing" => 4), (count($shopCategories) * (count($allVats) + 3)), 5);
		$catsDirMatrix = new we_html_table(array("border" => 0, "cellpadding" => 2, "cellspacing" => 4), ((count($allVats) + 3)), 5);
		if(count($shopCategories)){
			$i = $iTmp = 0;

			foreach($shopCategories as $k => $cat){
				$matrix = $catsMatrix;
				if($cat['ID'] == $shopCategoriesDir){
					$matrix = $catsDirMatrix;
					$iTmp = $i;
					$i = 0;
				}

				$j = 0;
				$matrix->setCol($i, 0, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 30), ($cat['ID'] == $shopCategoriesDir ? '' : (we_html_forms::checkboxWithHidden(($cat['IsActive'] == 1), 'weShopCatIsActive[' . $cat['ID'] . ']', ''))));
				$matrix->setCol($i, 1, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 20), $cat['ID'] . ': ');
				$matrix->setCol($i, 2, array("class" => "defaultfont", "style" => "font-weight:bold", "nowrap" => "nowrap", "width" => 140), $cat['Category']);
				$matrix->setCol($i++, 3, array("class" => "defaultfont", "style" => "font-weight:bold", "colspan" => "2","nowrap" => "nowrap", "width" => 110), $cat['Path']);//GL
				$matrix->setCol($i, 3, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), 'Destination Principle');//GL
				$matrix->setCol($i++, 4, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), we_html_forms::checkboxWithHidden(($cat['DestPrinciple'] == 1), 'weShopCatDestPrinciple[' . $cat['ID'] . ']', ''));
				
				if(!count($allVats)){
					$matrix->setCol($i, 3, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), 'no vats defined yet');//GL
				} else {
					foreach($allVats as $k => $v){
						$value = isset($relations[$cat['ID']][$k]) && $relations[$cat['ID']][$k] ? $relations[$cat['ID']][$k] : 0;
						$sel = we_html_tools::htmlSelect('weShopCatRels[' . $cat['ID'] . '][' . $k . ']', $v['selOptions'], 1, $value, false, array(), 'value', 240);
						$matrix->setCol($i, 3, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), $v['textTerritory']);
						$matrix->setCol($i++, 4, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 110), $sel);
					}
				}
				$matrix->setCol($i, 1, array("class" => "defaultfont", "nowrap" => "nowrap", "width" => 20), '');
				$matrix->setCol($i++, 2, array("style" => "padding-bottom: 20px", "class" => "defaultfont", "nowrap" => "nowrap", "width" => 140), '');

				$i = $cat['ID'] == $shopCategoriesDir ? $iTmp : $i;
			}
		} else {
			$catsMatrix = "Das ausgewählte Kategoeir-Verzeichnis enthält keine Einträge.";//GL
			$catsDirMatrix = "Das ausgewählte Kategoeir-Verzeichnis enthält keine Einträge.";//GL
		}
		$catsMatrixHtml = $catsMatrix->getHtml();
		$catsDirMatrixHtml = $catsDirMatrix->getHtml();
	}
} else {
	$catsMatrixHtml = $catsDirMatrixHtml = "Sie habe noch kein Kategorie-Verzeichnis gewählt.";//GL
}

echo we_html_tools::getHtmlTop() . STYLESHEET;

$jsFunction = '
	function we_submitForm(url){
		var f = self.document.we_form;
		f.action = url;
		f.method = "post";
		f.submit();
	}

	function doUnload() {
		if (!!jsWindow_count) {
			for (i = 0; i < jsWindow_count; i++) {
				eval("jsWindow" + i + "Object.close()");
			}
		}
	}

	function we_cmd(){
		switch (arguments[0]) {
			case "close":
				window.close();
			break;

			case "save":
				document.forms["we_form"]["we_cmd[0]"].value = "saveShopCatRels";
				we_submitForm("' . $_SERVER['SCRIPT_NAME'] . '");
			break;
		}
	}

	' . (isset($jsMessage) ? we_message_reporting::getShowMessageCall($jsMessage, $jsMessageType) : '');

$parts = array(
	array(
		'headline' => 'Kategorien-Verzeichnis',//GL
		'space' => 200,
		'html' => $selCategoryDirs,

	),
);

$parts[] = array(
	'headline' => 'Kategorie-Verzeichnis bearbeiten',
	'space' => 400,
	'html' => '',
	'noline' => 1
);

$parts[] = array(
	'headline' => '',
	'space' => 0,
	'html' => $catsDirMatrixHtml,
);

$parts[] = array(
	'headline' => 'Kategorien bearbeiten',
	'space' => 200,
	'html' => '',
	'noline' => 1
);

$parts[] = array(
	'headline' => '',
	'space' => 0,
	'html' => $catsMatrixHtml,
	'noline' => 1
);

$parts[] = array(
	'headline' => '',
	'space' => 0,
	//'html' => $debug_output
);

echo we_html_element::jsElement($jsFunction) .
 '</head>
<body class="weDialogBody" onload="window.focus();">
	<form name="we_form" method="post" >
	<input type="hidden" name="we_cmd[0]" value="load" />' .
 we_html_multiIconBox::getHTML(
	'weShopCategories', 700, $parts, 30, we_html_button::position_yes_no_cancel(
		we_html_button::create_button('save', 'javascript:we_cmd(\'save\');'), '', we_html_button::create_button('cancel', 'javascript:we_cmd(\'close\');')
	), -1, '', '', false, 'Shop-Kategorien', '', '', 'scroll'
) . '</form>

</body>
</html>';
