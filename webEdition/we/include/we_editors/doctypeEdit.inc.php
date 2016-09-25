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
we_html_tools::protect(['CAN_SEE_TEMPLATES', 'EDIT_DOCTYPE']);

$we_doc = new we_docTypes();

// Initialize variables
$reloadMenu = "";

$jsCmd = new we_base_jsCmd();

switch(($wecmd0 = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0))){
	case "save_docType":
		if(!permissionhandler::hasPerm("EDIT_DOCTYPE")){
			$jsCmd->addCmd('msg', ['msg' => g_l('weClass', '[no_perms]'), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
			break;
		}
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$we_transaction]);
		if(preg_match('|[\'",]|', $we_doc->DocType)){
			$jsCmd->addCmd('msg', ['msg' => g_l('alert', '[doctype_hochkomma]'), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
		} else if(!$we_doc->DocType){
			$jsCmd->addCmd('msg', ['msg' => g_l('alert', '[doctype_empty]'), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
		} elseif(($id = f('SELECT ID FROM ' . DOC_TYPES_TABLE . ' dt WHERE dt.DocType="' . $GLOBALS['DB_WE']->escape($we_doc->DocType) . '" LIMIT 1')) && ($we_doc->ID != $id)){
			$jsCmd->addCmd('msg', ['msg' => sprintf(g_l('weClass', '[doctype_save_nok_exist]'), $we_doc->DocType), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
		} elseif($we_doc->we_save()){
			$jsCmd->addCmd('msg', ['msg' => sprintf(g_l('weClass', '[doctype_save_ok]'), $we_doc->DocType), 'prio' => we_message_reporting::WE_MESSAGE_NOTICE]);
			$reloadMenu = we_main_headermenu::getMenuReloadCode();
		} else {
			echo "ERROR";
		}
		break;
	case "newDocType":
		if(($dt = we_base_request::_(we_base_request::STRING, 'we_cmd', 0, 1))){
			$we_doc->DocType = urldecode($dt);
			$reloadMenu = we_main_headermenu::getMenuReloadCode();
			$we_doc->we_save();
		}
		break;
	case "deleteDocTypeok":
		if(!permissionhandler::hasPerm("EDIT_DOCTYPE")){
			$jsCmd->addCmd('msg', ['msg' => g_l('alert', '[no_perms]'), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
			break;
		}
		$id = we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1);
		$name = f('SELECT DocType FROM ' . DOC_TYPES_TABLE . ' dt WHERE dt.ID=' . $id);
		$del = false;
		if($name){
			if(f('SELECT 1 FROM ' . FILE_TABLE . ' WHERE DocType=' . $id . ' LIMIT 1')){
				$jsCmd->addCmd('msg', ['msg' => sprintf(g_l('weClass', '[doctype_delete_nok]'), $name), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
			} else {
				$GLOBALS['DB_WE']->query('DELETE FROM ' . DOC_TYPES_TABLE . ' WHERE ID=' . $id);

				// Fast Fix for deleting entries from tblLangLink: #5840
				$GLOBALS['DB_WE']->query('DELETE FROM ' . LANGLINK_TABLE . ' WHERE DocumentTable="tblDocTypes" AND (DID=' . $id . ' OR LDID=' . $id . ')');
				$jsCmd->addCmd('msg', ['msg' => sprintf(g_l('weClass', '[doctype_delete_ok]'), $name), 'prio' => we_message_reporting::WE_MESSAGE_NOTICE]);
				$del = true;
			}
			if($del){
				if(($id = f('SELECT ID FROM ' . DOC_TYPES_TABLE . ' dt ORDER BY dt.DocType LIMIT 1'))){
					$we_doc->initByID($id, DOC_TYPES_TABLE);
				}
			} else {
				$we_doc->initByID(we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1), DOC_TYPES_TABLE);
			}
		}
		break;
	case 'add_dt_template':
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$we_transaction]);
		$foo = makeArrayFromCSV($we_doc->Templates);
		$ids = we_base_request::_(we_base_request::INTLISTA, 'we_cmd', [], 1);
		foreach($ids as $id){
			if(!in_array($id, $foo)){
				$foo[] = $id;
			}
		}
		$we_doc->Templates = implode(',', $foo);
		break;
	case 'delete_dt_template':
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$we_transaction]);
		$foo = makeArrayFromCSV($we_doc->Templates);
		$cmd1 = we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1);
		if($cmd1 && ($pos = array_search($cmd1, $foo, false)) !== false){
			unset($foo[$pos]);
		}
		if($we_doc->TemplateID == $cmd1){
			$we_doc->TemplateID = ($foo ? $foo[0] : 0);
		}
		$we_doc->Templates = implode(',', $foo);
		break;
	case "dt_add_cat":
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$we_transaction]);
		if(($id = we_base_request::_(we_base_request::INTLISTA, 'we_cmd', [], 1))){
			$we_doc->addCat($id);
		}
		break;
	case "dt_delete_cat":
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$we_transaction]);
		if(($id = we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1))){
			$we_doc->delCat($id);
		}
		break;
	default:
		$id = (we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1) ? : 0);
		if(!$id){
			$dtq = we_docTypes::getDoctypeQuery($GLOBALS['DB_WE']);
			$id = f('SELECT dt.ID FROM ' . DOC_TYPES_TABLE . ' dt LEFT JOIN ' . FILE_TABLE . ' dtf ON dt.ParentID=dtf.ID ' . $dtq['join'] . ' WHERE ' . $dtq['where'] . ' LIMIT 1');
		}

		if($id){
			$we_doc->initByID($id, DOC_TYPES_TABLE);
		}
}

$yuiSuggest = & weSuggest::getInstance();
$GLOBALS['DB_WE']->query('SELECT CONCAT("\'",REPLACE(dt.DocType,"\'","\\\\\'"),"\'") FROM ' . DOC_TYPES_TABLE . ' dt ORDER BY dt.DocType');

echo we_html_tools::getHtmlTop(g_l('weClass', '[doctypes]')) .
 weSuggest::getYuiFiles() .
 we_html_element::jsScript(JS_DIR . 'doctypeEdit.js', '', ['id' => 'loadVarDoctypeEdit', 'data-doctype' => setDynamicVar([
		'docTypeNames' => $GLOBALS['DB_WE']->getAll(true)
])]);
//FIXME: currently we don't have a class so we can't move js-g_l
?>
<script><!--
<?php
echo (empty($reloadMenu) ? '' : $reloadMenu . ';');

switch($wecmd0){
	case "deleteDocType":
		if(!permissionhandler::hasPerm("EDIT_DOCTYPE")){
			$jsCmd->addCmd('msg', ['msg' => g_l('alert', '[no_perms]'), 'prio' => we_message_reporting::WE_MESSAGE_ERROR]);
			break;
		}
		?>
		if (confirm("<?php printf(g_l('weClass', '[doctype_delete_prompt]'), $we_doc->DocType); ?>")) {
			we_cmd("deleteDocTypeok", "<?= we_base_request::_(we_base_request::INT, 'we_cmd', 0, 1); ?>");
		}
		<?php
		break;
	case "deleteDocTypeok":
		echo we_main_headermenu::getMenuReloadCode();
		break;
}
?>
//-->
</script>
<?= $jsCmd->getCmds(); ?>
</head>

<body class="weDialogBody" onunload="doUnload()" onload="self.focus();">
	<form name="we_form" action="" method="post" onsubmit="return false">
		<?php
		echo we_class::hiddenTrans();

		if($we_doc->ID){
			$parts = [["headline" => g_l('weClass', '[doctypes]'),
				"html" => $we_doc->formDocTypeHeader(),
				'space' => we_html_multiIconBox::SPACE_MED
				],
				["headline" => g_l('weClass', '[name]'),
					"html" => $we_doc->formName(),
					'space' => we_html_multiIconBox::SPACE_MED
				],
				["headline" => g_l('global', '[templates]'),
					"html" => $we_doc->formDocTypeTemplates(),
					'space' => we_html_multiIconBox::SPACE_MED
				],
				["headline" => g_l('weClass', '[defaults]'),
					"html" => $we_doc->formDocTypeDefaults(),
					'space' => we_html_multiIconBox::SPACE_MED
				]
			];
		} else {
			$parts = [["headline" => "",
				"html" => we_html_button::create_button('new_doctype', "javascript:we_cmd('newDocType')"),
				]
			];
		}

		$cancelbut = we_html_button::create_button(we_html_button::CLOSE, "javascript:self.close();if(top.opener.we_cmd){top.opener.we_cmd('switch_edit_page',0);}");

		$buttons = ($we_doc->ID ?
				we_html_button::position_yes_no_cancel(we_html_button::create_button(we_html_button::SAVE, "javascript:we_cmd('save_docType', '" . $we_transaction . "')"), "", $cancelbut) :
				'<div style="text-align:right">' . $cancelbut . '</div>');


		echo we_html_multiIconBox::getJS() .
		we_html_multiIconBox::getHTML("", $parts, 30, $buttons) .
		$yuiSuggest->getYuiJs();
		?>
	</form>
</body>

</html>

<?php
$we_doc->saveInSession($_SESSION['weS']['we_data'][$we_transaction]);
