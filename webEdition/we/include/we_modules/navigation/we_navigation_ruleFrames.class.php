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
class we_navigation_ruleFrames{
	public $Frameset;
	public $Controller;
	public $db;

	function __construct(){
		$this->Frameset = WEBEDITION_DIR . 'we_showMod.php?mod=navigation';
		$this->Controller = new we_navigation_ruleControl();
		$this->db = new DB_WE();
		$weSuggest = &we_gui_suggest::getInstance();
	}

	function getHTML($what){
		switch($what){
			case 'ruleFrameset' :
				echo $this->getHTMLFrameset();
				break;
			case 'ruleContent' :
				echo $this->getHTMLContent();
				break;
			default :
				t_e(__FILE__ . ": unknown reference $what");
		}
	}

	function getHTMLFrameset(){
		return we_html_tools::getHtmlTop(g_l('navigation', '[menu_highlight_rules]'), '', '', '', we_html_element::htmlBody(['class ' => 'weDialogBody']
					, we_html_element::htmlIFrame('content', WEBEDITION_DIR . 'we_showMod.php?mod=navigation&pnt=ruleContent', 'position:absolute;top:0px;bottom:0px;left:0px;right:0px;overflow: hidden') .
					we_html_element::htmlIFrame('cmdFrame', "about:blank", 'position:absolute;bottom:0px;height:0px;left:0px;right:0px;overflow: hidden;')
		));
	}

	function getHTMLContent(){
		// content contains textarea with all so far existing rules
		$weSuggest = & we_gui_suggest::getInstance();

		$allRules = we_navigation_ruleControl::getAllNavigationRules();

		$rules = [];

		foreach($allRules as $navigationRule){
			$rules[$navigationRule->ID] = $navigationRule->NavigationName;
		}
		asort($rules);

		$parts = [
			[
				'headline' => g_l('navigation', '[rules][available_rules]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => '<table class="default">
	<tr><td>' . we_html_tools::htmlSelect('navigationRules', $rules, 8, '', false, ['style' => "width: 275px;", 'onclick' => 'we_cmd(\'navigation_edit_rule\', this.value)']) . '</td>
		<td style="vertical-align:top">' . we_html_button::create_button('new_entry', 'javascript:we_cmd("new_navigation_rule")') . '<div style="height:10px;"></div>' . we_html_button::create_button('delete', 'javascript:we_cmd("delete_navigation_rule")') . '
		</td>
	</tr>
</table>'
			], [
				'headline' => g_l('navigation', '[rules][rule_name]'),
				'space' => we_html_multiIconBox::SPACE_BIG,
				'html' => we_html_tools::htmlTextInput('NavigationName', 24, '', '', 'style="width: 275px;"'),
				'noline' => 1
			],
		];

		$weSuggest->setAcId("NavigationIDPath");
		$weSuggest->setContentType("folder,weNavigation");
		$weSuggest->setInput('NavigationIDPath');
		$weSuggest->setMaxResults(10);
		$weSuggest->setTable(NAVIGATION_TABLE);
		$weSuggest->setResult('NavigationID');
		$weSuggest->setSelector(we_gui_suggest::DocSelector);
		$weSuggest->setWidth(275);
		$weSuggest->setSelectButton(
			// IMI: replace enc (eval)
			we_html_button::create_button('select', "javascript:we_cmd('we_selector_file', document.we_form.elements.NavigationID.value, '" . NAVIGATION_TABLE . "', 'document.we_form.elements.NavigationID.value', 'document.we_form.elements.NavigationIDPath.value')"), 10);

		$weAcSelector = $weSuggest->getHTML();

		$parts[] = [
			'headline' => g_l('navigation', '[rules][rule_navigation_link]'),
			'space' => we_html_multiIconBox::SPACE_BIG,
			'html' => $weAcSelector,
			'noline' => 1
		];

		$selectionTypes = [we_navigation_navigation::DYN_DOCTYPE => g_l('global', '[documents]')];
		if(defined('OBJECT_TABLE')){
			$selectionTypes[we_navigation_navigation::DYN_CLASS] = g_l('global', '[objects]');
		}

		$parts[] = [
			'headline' => g_l('navigation', '[rules][rule_applies_for]'),
			'space' => we_html_multiIconBox::SPACE_BIG,
			'html' => we_html_tools::htmlSelect('SelectionType', $selectionTypes, 1, 0, false, ['style' => "width: 275px;", 'onchange' => "switchType(this.value);"])
		];

// getDoctypes
		$docTypes = [0 => g_l('navigation', '[no_entry]')];
		$dtq = we_docTypes::getDoctypeQuery($this->db);
		$this->db->query('SELECT dt.ID,dt.DocType FROM ' . DOC_TYPES_TABLE . ' dt LEFT JOIN ' . FILE_TABLE . ' dtf ON dt.ParentID=dtf.ID ' . $dtq['join'] . ' WHERE ' . $dtq['where']);
		while($this->db->next_record()){
			$docTypes[$this->db->f('ID')] = $this->db->f('DocType');
		}

		$weSuggest->setAcId("FolderIDPath");
		$weSuggest->setContentType(we_base_ContentTypes::FOLDER);
		$weSuggest->setInput('FolderIDPath');
		$weSuggest->setMaxResults(10);
		$weSuggest->setResult('FolderID');
		$weSuggest->setSelector(we_gui_suggest::DirSelector);
		$weSuggest->setWidth(275);
		$weSuggest->setSelectButton(we_html_button::create_button('select', "javascript:we_cmd('we_selector_directory', document.we_form.elements.FolderID.value, '" . FILE_TABLE . "', 'FolderID', 'FolderIDPath')"), 10);
		$weSuggest->setTrashButton(we_html_button::create_button(we_html_button::TRASH, "javascript:document.we_form.elements.FolderID.value = '';document.we_form.elements.FolderIDPath.value = '';"));

		$weAcSelector = $weSuggest->getHTML();

		$formTable = '<table class="default">
<tr id="trFolderID">
	<td class="weMultiIconBoxHeadline" style="vertical-align:top">' . g_l('navigation', '[rules][rule_folder]') . '</td>
	<td colspan="5">' . $weAcSelector . '</td>
</tr>
<tr id="trDoctypeID">
	<td style="height: 40px;" class="weMultiIconBoxHeadline">' . g_l('navigation', '[rules][rule_doctype]') . '</td>
	<td>' . we_html_tools::htmlSelect('DoctypeID', $docTypes, 1, 0, false, ['style' => "width: 275px;"]) . '</td>
</tr>';

		if(defined('OBJECT_TABLE')){

			$weSuggest->setAcId("ClassIDPath");
			$weSuggest->setContentType("folder,object");
			$weSuggest->setInput("ClassIDPath");
			$weSuggest->setMaxResults(10);
			$weSuggest->setResult('ClassID');
			$weSuggest->setSelector(we_gui_suggest::DocSelector);
			$weSuggest->setTable(OBJECT_TABLE);
			$weSuggest->setWidth(275);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_document',document.we_form.elements.ClassID.value, '" . OBJECT_TABLE . "','ClassID','ClassIDPath','get_workspaces')"), 10);

			$weAcSelector = $weSuggest->getHTML();

			$formTable .= '<tr id="trClassID">
	<td class="weMultiIconBoxHeadline">' . g_l('navigation', '[rules][rule_class]') . '</td>
	<td colspan="3">' . $weAcSelector . '</td>
</tr>
<tr id="trWorkspaceID">
	<td style="height: 40px;" class="weMultiIconBoxHeadline">' . g_l('navigation', '[rules][rule_workspace]') . '</td>
	<td>' . we_html_tools::htmlSelect('WorkspaceID', [], 1, '', false, ['style' => "width: 275px;"]) . '</td>
</tr>';
		}
		$formTable .= '
<tr id="trCategories">
	<td style="width: 200px;vertical-align:top" class="weMultiIconBoxHeadline">' . g_l('navigation', '[rules][rule_categories]') . '</td>
	<td colspan="4">
		' . $this->getHTMLCategory() . '
	</td>
</tr>
</table>';

		$parts[] = [
			'html' => $formTable,
		];

		$saveButton = we_html_button::create_button(we_html_button::SAVE, 'javascript:we_cmd("save_navigation_rule");');
		$closeButton = we_html_button::create_button(we_html_button::CLOSE, 'javascript:top.window.close();');
		return we_html_tools::getHtmlTop('', '', '', we_html_element::jsScript(JS_DIR . 'formFunctions.js') .
				we_html_element::jsScript(WE_JS_MODULES_DIR . 'navigation/navigationRule.js', 'initCat();', ['id' => 'loadVarNavigationRules', 'data-rules' => setDynamicVar([
						'dependencies' => [
							we_navigation_navigation::DYN_CLASS => ["ClassID", "WorkspaceID", "Categories"],
							we_navigation_navigation::DYN_DOCTYPE => ["FolderID", "DoctypeID", "Categories"]
						],
						'trashButton' => we_html_button::create_button(we_html_button::TRASH, 'javascript:#####placeHolder#####;')
				])]), we_html_element::htmlBody(['onload' => "switchType(document.we_form.SelectionType.value)",
					'class' => "weDialogBody"], we_html_element::htmlForm(['name' => 'we_form', 'target' => "cmdFrame", 'method' => "post", 'action' => WEBEDITION_DIR . 'we_showMod.php?mod=navigation&pnt=ruleCmd'], we_html_element::htmlHiddens([
							'cmd' => '',
							'ID' => '0'
						]) .
						we_html_multiIconBox::getHTML('navigationRules', $parts, 30, we_html_button::position_yes_no_cancel($saveButton, null, $closeButton), -1, '', '', false, g_l('navigation', '[rules][navigation_rules]')))
		));
	}

	private function getHTMLCategory(){
		$addbut = we_html_button::create_button('add', "javascript:we_cmd('we_selector_category',0,'" . CATEGORY_TABLE . "','','','opener.addCat(top.fileSelect.data.allPaths, top.fileSelect.data.allIDs);')");

		$table = new we_html_table(['id' => 'CategoriesBlock', 'style' => 'display: block;', 'class' => 'default withSpace'], 2, 1);

		$table->setColContent(0, 0, we_html_element::htmlDiv([
				'id' => 'categories',
				'class' => 'blockWrapper',
				'style' => 'width: 380px; height: 80px; border: #AAAAAA solid 1px;'
		]));

		$table->setCol(1, 0, ['colspan' => 2, 'style' => 'text-align:right'], we_html_button::create_button(we_html_button::DELETE_ALL, "javascript:removeAllCats()") . $addbut);

		return $table->getHtml() .
			we_html_element::htmlHiddens([
				'CategoriesControl' => 0,
			]);
	}

	public function process(we_base_jsCmd $jscmd){
		ob_start();
		$this->Controller->processVariables();
		$this->Controller->processCommands($jscmd);
		$GLOBALS['extraJS'] = $jscmd->getCmds() . ob_get_clean();
	}

}