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
//start autoloader!
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();

$cmd = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0);
if(!$cmd){
	t_e('call without command, might be an error');
	exit();
}

function findInclude($cmd){
	switch($cmd){
		case ''://empty command
			exit();
		case 'we_selector_delete':
			if(isset($_SESSION['weS']['seemForOpenDelSelector']['Table'])){
				unset($_SESSION['weS']['seemForOpenDelSelector']['Table']);
			}
		//no break
		case 'we_selector_category':
		case 'we_selector_directory':
		case 'we_selector_document':
		case 'we_selector_file':
		case 'we_selector_image':
			return 'selectors.inc.php';
		case 'selectorEdit':
			return 'we_editors/selectorEdit.inc.php';
		case 'selectorBrowse':
			return 'we_selectorBrowse.inc.php';
		case 'selectorBrowseCmd':
			return 'we_selectorBrowseCmd.inc.php';
		case 'we_fileupload_editor':
			return 'we_editors/we_fileupload.inc.php';
		case 'backupLog':
			return 'we_exim/backup/backuplog.inc.php';
		case 'newMsg':
			return 'newMsg.inc.php';
		case 'phpinfo':
		case 'sysinfo':
			return 'sysinfo.inc.php';
		case 'versions_preview':
			return 'we_versions/weVersionsPreview.inc.php';
		case 'versions_wizard':
			return 'we_versions/we_versions.inc.php';
		case 'versioning_log':
			return 'we_versions/versionsLog.inc.php';
		case 'import_files':
			return 'we_import_files.inc.php';
		case 'reset_home':
		case 'mod_home':
			return 'we_modules/home.inc.php';
		case 'loadSidebarDocument':
			return 'sidebar.inc.php';
		case 'siteImport':
		case 'siteImportCreateWePageSettings':
		case 'siteImportSaveWePageSettings':
		case 'updateSiteImportTable':
			return 'we_siteimport.inc.php';
		case 'loadTree':
			return 'loadTree.inc.php';
		case 'open_tag_wizzard':
			return 'weTagWizard/we_tag_wizzard.inc.php';
		case 'change_passwd':
			return 'we_editors/we_editPasswd.inc.php';
		case 'exit_delete':
		case 'exit_move':
		case 'home':
		case 'open_cockpit':
			return 'home.inc.php';
		case 'logout':
			return 'we_logout.inc.php';
		case 'openColorChooser':
			return 'we_editors/we_colorChooser.inc.php';
		case 'add_dt_template':
		case 'change_docType':
		case 'deleteDocType':
		case 'deleteDocTypeok':
		case 'delete_dt_template':
		case 'doctypes':
		case 'dt_add_cat':
		case 'dt_delete_cat':
		case 'newDocType':
		case 'save_docType':
			return 'we_editors/doctypeEdit.inc.php';
		case 'rebuild':
			return 'we_editors/we_rebuild.inc.php';
		case 'help':
			return '';
		case 'info':
			return 'we_show_info.inc.php';
		case 'openPreferences':
			return 'we_editors/we_preferences_frameset.inc.php';
		case 'editThumbs':
			return 'we_editors/we_thumbnails.inc.php';
		case 'editNewCollection':
			return 'we_editors/we_newCollection.inc.php';
		case 'editMetadataFields':
			return 'we_editors/edit_metadatafields.inc.php';
		case 'show':
			$GLOBALS['FROM_WE_SHOW_DOC'] = true;
			return 'we_showDocument.inc.php';
		case 'open_url_in_editor': // Beim ungewollten Verlassen (Klick auf Link im Bearbeitenmodus) des Editors wird die Location auf diese Seite weitergeleitet. Hier wird dann ein Kommando gebildet
			return 'we_seem/open_url_in_editor.inc.php';
		case 'open_form_in_editor': // Formular wird an dieses Skript umgeleitet, hier wird ein Kommando daraus gebaut, um das Dokument korrekt zu �ffnen
			return 'we_seem/open_form_in_editor.inc.php';
		case 'open_extern_document'; // wird ben�tigt um ein externes Dokument aufzurufen
			return 'we_seem/we_SEEM_openExtDoc_frameset.inc.php';
		case 'edit_document_with_parameters':
			$GLOBALS['parastr'] = we_base_request::_(we_base_request::RAW_CHECKED, 'we_cmd', '', 4);
		case 'edit_document':
		case 'edit_folder':
		case 'new_document':
		case 'new_folder':
			return 'we_editors/we_edit_frameset.inc.php';
		case 'edit_include_document':
			return 'we_editors/SEEM_edit_include_document.inc.php';
		case 'load_edit_header':
			return 'we_editors/we_editor_header.inc.php';
		case 'load_edit_footer':
		case 'reload_editfooter':
			return 'we_editors/we_editor_footer.inc.php';
		case 'load_import':
		case 'do_import':
			return 'we_editors/we_import_editor.inc.php';
		//case 'delete_alias':
		case 'weNaviEditor':
			return 'we_modules/navigation/weNaviEditor.inc.php';
		case 'add_cat':
		case 'add_entry_to_list':
		case 'add_link_to_linklist':
		case 'add_navi':
		case 'change_link':
		case 'change_linklist':
		case 'copyDocument':
		case 'del_thumb':
		case 'delete_all_cats':
		case 'delete_all_navi':
		case 'delete_cat':
		case 'delete_link':
		case 'delete_linklist':
		case 'delete_list':
		case 'delete_navi':
		case 'doImage_convertGIF':
		case 'doImage_convertJPEG':
		case 'doImage_convertPNG':
		case 'doImage_crop':
		case 'do_add_thumbnails':
		case 'doctype_changed':
		case 'down_entry_at_list':
		case 'down_link_at_list':
		case 'insert_entry_at_list':
		case 'insert_link_at_linklist':
		case 'load_editor':
		case 'new_alias':
		case 'publish':
		case 'reload_editpage':
		case 'remove_image':
		case 'resizeImage':
		case 'restore_defaults':
		case 'revert_published':
		case 'rotateImage':
		case 'save_document':
		case 'switch_edit_page':
		case 'template_changed':
		case 'unpublish':
		case 'up_entry_at_list':
		case 'up_link_at_list':
		case 'update_file':
		case 'update_image':
		case 'wrap_on_off':
		//variants
		case 'insert_variant':
		case 'move_variant_down':
		case 'move_variant_up':
		case 'preview_variant':
		case 'remove_variant':
			return 'we_editors/we_editor.inc.php';
		case 'edit_linklist':
		case 'edit_link':
		case 'edit_link_at_class':
		case 'edit_link_at_object':
			return 'we_editors/we_linklistedit.inc.php';
		case 'closeFolder':
		case 'load':
		case 'loadFolder':
			return 'we_load.inc.php';
		case 'delete':
			return (we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 1) ? 'we_delete.inc.php' : 'home.inc.php');
		case 'move':
			return (we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 1) ? 'we_move.inc.php' : 'home.inc.php');
		case 'addToCollection':
			return (we_base_request::_(we_base_request::BOOL, 'we_cmd', false, 1) ? 'we_addToCollection.inc.php' : 'home.inc.php');
		case 'delete_single_document':
		case 'do_delete':
			return 'we_delete.inc.php';
		case 'delInfo':
			return 'we_delInfo.inc.php';
		case 'do_move':
		case 'move_single_document':
			return 'we_move.inc.php';
		case 'moveInfo':
			return 'we_moveInfo.inc.php';
		case 'do_addToCollection':
			return 'we_addToCollection.inc.php';
		case 'show_binaryDoc':
			return 'we_editors/we_showBinaryDoc.inc.php';
		case 'pref_ext_changed':
			return 'we_prefs.inc.php';
		case 'exit_doc_question':
			return 'we_editors/we_exit_doc_question.inc.php';
		case 'exit_multi_doc_question':
			return 'we_editors/we_exit_multi_doc_question.inc.php';
		case 'browse_server':
			return 'we_editors/we_sfileselector_frameset.inc.php';
		case 'make_backup':
			return 'we_editors/we_make_backup.inc.php';
		case 'recover_backup':
			return 'we_editors/we_recover_backup.inc.php';
		case 'messageConsole':
			return 'jsMessageConsole/messageConsole.inc.php';
		case 'import_docs':
			return 'we_editors/we_import_documents.inc.php';
		case 'start_multi_editor':
			return 'multiEditor/start_multi_editor.inc.php';
		case 'import':
			return 'we_import/we_import_frameset.inc.php';
		case 'export':
			return 'we_modules/export/export_frameset.php';
		case 'copyFolder':
			return 'copyFolder.inc.php';
		case 'copyWeDocumentCustomerFilter':
			return 'we_modules/customer/we_customer_copyWeDocumentFilter.inc.php';
		case 'changeLanguageRecursive':
			return 'changeLanguage_rec.inc.php';
		case 'changeTriggerIDRecursive':
			return 'changeTriggerID_rec.inc.php';
		case 'add_thumbnail':
			return 'we_editors/add_thumbnail.inc.php';
		case 'image_convertJPEG':
		case 'image_crop':
		case 'image_resize':
		case 'image_rotate':
			return 'we_editors/image_edit.inc.php';
		case 'open_wysiwyg_window':
			return 'wysiwygWindow.inc.php';
		//  stuff about accessibility/validation
		case 'checkDocument':
			return 'we_editors/checkDocument.inc.php'; //  Here request is performed
		case 'customValidationService':
			return 'we_editors/customizeValidation.inc.php'; //  edit parameters
		case 'widget_cmd':
			return 'we_widgets/cmd.inc.php';
		case 'tool_weSearch_edit':
			$_REQUEST['tool'] = 'weSearch';
			return 'we_tools/tools_frameset.php';

		default:
			//	In we.inc.php all names of the active modules have already been searched
//	so we only have to use the array $GLOBALS['_we_active_integrated_modules']
			list($m, $m2) = explode('_', $cmd);
			$m = ($m == 'we' ? $m2 : $m);
			if(in_array($m, $GLOBALS['_we_active_integrated_modules'])){
				if(($INCLUDE = include(WE_MODULES_PATH . $m . '/we_cmd_' . $m . '.inc.php'))){
					return $INCLUDE;
				}
			}
			// search tools for command
			if(($INCLUDE = we_tool_lookup::getPhpCmdInclude())){
				return $INCLUDE;
			}
			//	In we.inc.php all names of the installed modules have already been searched
			//	so we only have to use the array $we_active_integrated_modules

			$mods = we_base_moduleInfo::getIntegratedModules(false);
			foreach($mods as $m){
				if($cmd == $m['name'] . '_edit_ifthere' && !we_base_moduleInfo::isActive($m['name'])){
					$GLOBALS['moduleName'] = $m['text_short'];
					return 'weInfoPages/messageModuleNotActivated.inc.php';
				}
			}
			//	This is ONLY used in the edit-mode of the documents.
			//	This statement prevents the page from being reloaded.
			echo we_html_element::jsElement('parent.openedWithWE=true;');
			t_e('error', 'command \'' . $cmd . '\' not known!');
			exit('command \'' . $cmd . '\' not known!');
	}
}

if(($inc = findInclude($cmd))){
	if($inc !== true){
		require((substr($inc, 0, 5) === 'apps/' ? WEBEDITION_PATH : WE_INCLUDES_PATH) . $inc);
	}
	//  When pressing a link in edit-mode, the page is being reloaded from
	//  webedition. If a webedition link was pressed this page shall not be
	//  reloaded. All entries in this array represent values for we_cmd[0]
	//  when the javascript command shall NOT be inserted (p.ex while saving the file.)
	//	This is ONLY used in the edit-mode of the documents.
	//  This statement prevents the page from being reloaded
	switch($cmd){
		case 'siteImport':
		case 'mod_home':
		case 'import_images':
		case 'getWeDocFromID':
		case 'rebuild':
		case 'open_url_in_editor':
		case 'open_form_in_editor':
		case 'users_unlock':
		case 'edit_document':
		case 'load_editor':
		case 'load_edit_header':
		case 'load_edit_footer':
		case 'exchange':
		case 'validateDocument':
		case 'show':
		case 'we_fileupload_editor':
			break;
		default:
			echo we_html_element::jsElement('parent.openedWithWE=true;');
			break;
	}
}
